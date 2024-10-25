import tkinter as tk
from tkinter import ttk, messagebox
import spidev
import time
import threading
import RPi.GPIO as GPIO
import pygame
import os
import subprocess

# Disabilita gli avvisi GPIO
GPIO.setwarnings(False)

# Imposta GPIO per il pin EN
GPIO.setmode(GPIO.BCM)
EN_PIN = 17
GPIO.setup(EN_PIN, GPIO.OUT)

# Imposta SPI
spi = spidev.SpiDev()
spi.open(0, 0)
spi.max_speed_hz = 500000
spi.mode = 0b00

# Comandi LS7366R per gestire l'encoder
CLEAR_COUNTER = 0x20
READ_COUNTER = 0x60
WRITE_MDR0 = 0x88  # Comando per scrivere nel registro MDR0
WRITE_MDR1 = 0x90  # Comando per scrivere nel registro MDR1

# Parametri dell'encoder e del rullino
PUNTI_PER_GIRO = 100
DIAMETRO_RULLINO = 11.0
CIRCONFERENZA_RULLINO = 34.55749
LUNGHEZZA_PER_IMPULSO = CIRCONFERENZA_RULLINO / PUNTI_PER_GIRO / 10
lettura_precedente_encoder = 0
lunghezza_totale_filo = 0.0
lunghezza_precedente_filo = 0.0  # Lunghezza precedente
scostamento_filo = 0.0  # Scostamento tra attuale e precedente
impulsi_totali = 0
record_totali = []  # Lista per memorizzare i record
record_window = None
led_accesso = False
fattore_taratura = 1.0
tempo_precedente = time.time()
velocita = 0.0
timer_attivo = False
inizio_operativita = None
tempo_totale_operativita = 0.0
tempo_operativita = 0.0  # Timer indipendente di Operatività
ultimo_impulso_time = 0.0
encoder_fermo = True
tempo_fermo = 2.0  # Il tempo in secondi dopo il quale memorizzare i record se l'encoder è fermo
record_automatico = False  # Flag per evitare salvataggi multipli
numero_campionamento = 1  # Numero progressivo del campionamento
file_fattore_taratura = "/home/pi/Desktop/MF1-DEF-ENCODER/fattore_taratura.txt"
file_record = "/home/pi/Desktop/MF1-DEF-ENCODER/record.txt"
file_tempo_totale = "/home/pi/Desktop/MF1-DEF-ENCODER/tempo_totale.txt"
cambio_spola = False  # Variabile per tracciare se è stato premuto il tasto cambio spola

# Variabile di stato per gestire il reset del timer
timer_resettato = False

# Inizializza pygame per il suono
pygame.mixer.init()
pygame.mixer.music.load("/home/pi/Desktop/beep-10.wav")

# Funzione per scrivere un comando tramite SPI
def write_byte(command, value=None):
    if value is not None:
        spi.xfer2([command, value])
    else:
        spi.xfer2([command])

# Funzione per configurare l'encoder in modalità X4 quadratura
def configure_encoder_x4_mode():
    MDR0_CONFIG = 0x03
    write_byte(WRITE_MDR0, MDR0_CONFIG)

# Funzione per resettare i registri e il contatore
def reset_registers():
    write_byte(WRITE_MDR0, 0x03)
    write_byte(WRITE_MDR1, 0x00)
    write_byte(CLEAR_COUNTER)

# Funzione per leggere il contatore dell'encoder con gestione dell'overflow
def read_counter():
    result = spi.xfer2([READ_COUNTER, 0x00, 0x00, 0x00, 0x00])
    count = (result[1] << 24) | (result[2] << 16) | (result[3] << 8) | result[4]
    if count & 0x80000000:
        count -= 0x100000000
    return count

# Funzione per salvare il fattore di taratura nel file
def save_fattore_taratura_to_file():
    with open(file_fattore_taratura, "w") as f:
        f.write(f"{fattore_taratura:.2f}")

# Funzione per caricare il fattore di taratura dal file
def load_fattore_taratura_from_file():
    global fattore_taratura
    if os.path.exists(file_fattore_taratura):
        with open(file_fattore_taratura, "r") as f:
            fattore_taratura = float(f.read().strip())

# Funzione per salvare il tempo totale di operatività nel file
def save_tempo_totale_to_file():
    with open(file_tempo_totale, "w") as f:
        f.write(f"{tempo_totale_operativita:.2f}")

# Funzione per caricare il tempo totale di operatività dal file
def load_tempo_totale_from_file():
    global tempo_totale_operativita
    if os.path.exists(file_tempo_totale):
        with open(file_tempo_totale, "r") as f:
            tempo_totale_operativita = float(f.read().strip())

# Funzione per salvare i record nel file
def save_record_to_file(impulsi, lunghezza, velocita, fattore_taratura, tempo_operativita):
    global numero_campionamento, cambio_spola, lunghezza_precedente_filo
    with open(file_record, "a") as f:
        f.write(f"Campionamento {numero_campionamento}: Lunghezza: {lunghezza:.6f} cm, Fattore di taratura: {fattore_taratura:.2f}, Tempo operatività: {tempo_operativita:.2f} s\n")
        f.write(f"TEMPO TOTALE DI OPERATIVITA': {tempo_totale_operativita:.2f} s\n")
        if cambio_spola:
            f.write(f"Campionamento {numero_campionamento}: Cambio spola effettuato\n")
    lunghezza_precedente_filo = lunghezza_totale_filo  # Salva la lunghezza precedente prima di azzerarla
    numero_campionamento += 1
    cambio_spola = False  # Reset flag cambio spola dopo il salvataggio

# Funzione per visualizzare "RECORD SALVATO" in verde per 2 secondi
def mostra_record_salvato():
    record_salvato_label.config(text="RECORD SALVATO", fg="green")
    root.after(2000, lambda: record_salvato_label.config(text=""))

# Funzione per aggiornare il display degli impulsi e la lunghezza del filo
def update_encoder_display():
    global lettura_precedente_encoder, lunghezza_totale_filo, scostamento_filo, impulsi_totali, tempo_precedente, velocita, timer_attivo, inizio_operativita, encoder_fermo, tempo_totale_operativita, ultimo_impulso_time, record_automatico, timer_resettato, tempo_operativita

    # Leggi il valore corrente dell'encoder
    impulsi_totali = read_counter()

    # Calcola la differenza tra la lettura corrente e la lettura precedente
    differenza_impulsi = impulsi_totali - lettura_precedente_encoder
    lettura_precedente_encoder = impulsi_totali

    # Se l'encoder genera impulsi
    if differenza_impulsi != 0:
        ultimo_impulso_time = time.time()  # Aggiorna il tempo dell'ultimo impulso
        record_automatico = False  # Reset del flag quando l'encoder è attivo
        macchina_stato_label.config(text="MACCHINA IN FUNZIONE", fg="red")  # Macchina in funzione
        if encoder_fermo:  # Se l'encoder era fermo, riattiva il timer
            inizio_operativita = time.time()  # Imposta il tempo di inizio per operatività
            encoder_fermo = False
            timer_attivo = True
            timer_resettato = False  # Ripristina lo stato normale del timer
    else:
        # Se l'encoder non genera impulsi per più di 2 secondi, ferma il timer e memorizza i record
        if time.time() - ultimo_impulso_time >= tempo_fermo:
            if not encoder_fermo and timer_attivo:
                tempo_totale_operativita += time.time() - inizio_operativita
            encoder_fermo = True
            timer_attivo = False
            macchina_stato_label.config(text="MACCHINA FERMA", fg="green")  # Macchina ferma

            # Se non abbiamo ancora salvato il record durante questo periodo di fermo, memorizziamo i record
            if not record_automatico:
                save_record_automatico()
                record_automatico = True
                reset_encoder()  # Effettua il reset per nuovi campionamenti

    # Calcola la lunghezza lineare per gli impulsi rilevati e applica il fattore di taratura
    lunghezza_aggiornata = differenza_impulsi * LUNGHEZZA_PER_IMPULSO * fattore_taratura
    lunghezza_totale_filo += lunghezza_aggiornata

    # Calcola lo scostamento tra l'attuale e la precedente
    scostamento_filo = lunghezza_totale_filo - lunghezza_precedente_filo

    # Determina se lo scostamento è positivo o negativo
    if scostamento_filo >= 0:
        scostamento_label.config(text=f"Scostamento: +{scostamento_filo:.6f} cm")
    else:
        scostamento_label.config(text=f"Scostamento: {scostamento_filo:.6f} cm")

    # Aggiorna i valori visualizzati
    filo_label.config(text=f"Lunghezza totale (attuale): {lunghezza_totale_filo:.6f} cm")
    lunghezza_precedente_label.config(text=f"Lunghezza totale (precedente): {lunghezza_precedente_filo:.6f} cm")

    # Aggiorna il timer di operatività solo se non è stato resettato
    if not timer_resettato:
        if not encoder_fermo:
            tempo_operativita = time.time() - inizio_operativita  # Calcola il tempo di operatività corrente
        else:
            tempo_operativita = 0.0  # Ferma il timer quando l'encoder è fermo

        ore = int(tempo_operativita // 3600)
        minuti = int((tempo_operativita % 3600) // 60)
        secondi = int(tempo_operativita % 60)
        timer_label.config(text=f"Operatività: {ore:02d}:{minuti:02d}:{secondi:02d}")

    # Aggiorna il tempo totale di operatività con il valore salvato
    totale_ore = int(tempo_totale_operativita // 3600)
    totale_minuti = int((tempo_totale_operativita % 3600) // 60)
    totale_secondi = int(tempo_totale_operativita % 60)
    tempo_totale_label.config(text=f"TEMPO TOTALE DI OPERATIVITÀ: {totale_ore:02d}:{totale_minuti:02d}:{totale_secondi:02d}")

    # Aggiorna il display ogni 500ms
    root.after(500, update_encoder_display)

# Funzione per salvare i record automaticamente quando l'encoder si ferma
def save_record_automatico():
    global record_totali, impulsi_totali, lunghezza_totale_filo, velocita, fattore_taratura, tempo_totale_operativita
    record_totali.append((impulsi_totali, lunghezza_totale_filo, velocita, fattore_taratura, tempo_totale_operativita))
    print(f"Record salvato automaticamente: Lunghezza: {lunghezza_totale_filo:.6f} cm")

    # Scrivi i dati nel file
    save_record_to_file(impulsi_totali, lunghezza_totale_filo, velocita, fattore_taratura, tempo_totale_operativita)

    # Visualizza il messaggio "RECORD SALVATO"
    mostra_record_salvato()

    # Aggiorna la finestra dei record
    update_records()

# Funzione per resettare l'encoder e prepararsi per nuovi campionamenti
def reset_encoder():
    global lettura_precedente_encoder, lunghezza_totale_filo, impulsi_totali, timer_attivo, inizio_operativita, tempo_totale_operativita, lunghezza_precedente_filo

    reset_registers()
    time.sleep(0.1)
    configure_encoder_x4_mode()
    lettura_precedente_encoder = read_counter()
    lunghezza_totale_filo = 0.0  # Azzera la lunghezza totale attuale
    impulsi_totali = 0
    timer_attivo = False
    inizio_operativita = None
    timer_label.config(text="Operatività: 00:00:00")
    filo_label.config(text="Lunghezza totale (attuale): 0.000000 cm")

# Funzione per registrare cambio spola con messaggio di conferma
def registra_cambio_spola():
    global cambio_spola
    if messagebox.askyesno("Conferma Cambio Spola", "Sei sicuro di voler registrare il cambio spola?"):
        cambio_spola = True
        save_record_automatico()
        print("Cambio spola effettuato")

# Funzione per aprire il file dei campionamenti
file_process = None
def apri_file_record():
    global file_process
    if file_process is None or file_process.poll() is not None:  # Verifica che il processo non sia già attivo
        # Usa "gedit", "leafpad", "mousepad" o "pluma" per aprire il file in un editor grafico
        try:
            file_process = subprocess.Popen(["gedit", file_record])  # Sostituisci "gedit" con l'editor disponibile
        except FileNotFoundError:
            messagebox.showerror("Errore", "Editor di testo non trovato!")

# Funzione per creare e aggiornare la finestra dei record
def create_record_window():
    global record_window
    if record_window is None or not record_window.winfo_exists():
        record_window = tk.Toplevel(root)
        record_window.title("Record Lunghezze")
        record_window.geometry("500x400")
        record_label = tk.Label(record_window, text="Record misurazioni:")
        record_label.pack(pady=10)
        clear_button = tk.Button(record_window, text="Cancella Record", command=clear_records)
        clear_button.pack(pady=10)
    update_records()

# Funzione per cancellare i record
def clear_records():
    global record_totali
    record_totali = []
    update_records()

# Funzione per aggiornare i record nella finestra esistente
def update_records():
    global record_window
    if record_window is None or not record_window.winfo_exists():
        return  # Esci dalla funzione se la finestra non esiste
    for widget in record_window.winfo_children():
        if isinstance(widget, tk.Label) and "Record misurazioni:" not in widget.cget("text"):
            widget.destroy()
    for i, (impulsi, lunghezza, velocita, fattore_taratura, tempo_operativita) in enumerate(record_totali, 1):
        scostamento = 0.0 if i == 1 or record_totali[i-2][1] == 0 else ((lunghezza - record_totali[i-2][1]) / record_totali[i-2][1]) * 100
        record_entry = tk.Label(record_window, text=f"Misurazione {i}: Lunghezza: {lunghezza:.6f} cm, Fattore taratura: {fattore_taratura:.2f}, Operatività: {tempo_operativita:.2f} s, Scostamento: {scostamento:.2f}%")
        record_entry.pack()

# Funzione per migliorare la taratura e salvarla nel file
def aumenta_taratura():
    global fattore_taratura
    fattore_taratura += 0.01
    taratura_label.config(text=f"Fattore di taratura: {fattore_taratura:.2f}")
    save_fattore_taratura_to_file()  # Salva il nuovo fattore di taratura

def diminuisci_taratura():
    global fattore_taratura
    fattore_taratura -= 0.01
    taratura_label.config(text=f"Fattore di taratura: {fattore_taratura:.2f}")
    save_fattore_taratura_to_file()  # Salva il nuovo fattore di taratura

# Funzione per cancellare i record nel file con conferma
def cancella_record():
    if messagebox.askyesno("Conferma", "Sei sicuro di voler cancellare tutti i record?"):
        with open(file_record, "w") as f:
            f.write("")  # Svuota il contenuto del file
        clear_records()  # Cancella i record anche dalla memoria

# Crea l'interfaccia grafica (GUI)
root = tk.Tk()
root.title("Misurazione precisa della lunghezza del filato")
root.configure(bg="#f0f0f0")

# Carica il fattore di taratura e il tempo totale di operatività all'avvio
load_fattore_taratura_from_file()
load_tempo_totale_from_file()

# Layout migliorato GUI
style = ttk.Style()
style.configure("TButton", font=("Helvetica", 16), padding=12)  # Aumenta la dimensione dei pulsanti

main_frame = tk.Frame(root, bg="#f0f0f0")
main_frame.pack(pady=20, padx=20)

colonna1 = tk.Frame(main_frame, bg="#f0f0f0")
colonna1.pack(side=tk.LEFT, padx=15)

filo_label = tk.Label(colonna1, text="Lunghezza totale (attuale): 0.000000 cm", font=("Helvetica", 14, "bold"), bg="#f0f0f0", fg="#333")
filo_label.pack(pady=10)

scostamento_label = tk.Label(colonna1, text="Scostamento: 0.000000 cm", font=("Helvetica", 14, "bold"), bg="#f0f0f0", fg="#333")
scostamento_label.pack(pady=10)

lunghezza_precedente_label = tk.Label(colonna1, text="Lunghezza totale (precedente): 0.000000 cm", font=("Helvetica", 14, "bold"), bg="#f0f0f0", fg="#333")
lunghezza_precedente_label.pack(pady=10)

record_salvato_label = tk.Label(colonna1, text="", font=("Helvetica", 16, "bold"), bg="#f0f0f0")
record_salvato_label.pack(pady=10)

macchina_stato_label = tk.Label(colonna1, text="MACCHINA FERMA", font=("Helvetica", 16, "bold"), bg="#f0f0f0", fg="green")
macchina_stato_label.pack(pady=10)

colonna2 = tk.Frame(main_frame, bg="#f0f0f0")
colonna2.pack(side=tk.LEFT, padx=15)

# Mostra il tempo totale di operatività
tempo_totale_label = tk.Label(colonna2, text="TEMPO TOTALE DI OPERATIVITÀ: 00:00:00", font=("Helvetica", 14), bg="#f0f0f0", fg="#333")
tempo_totale_label.pack(pady=10)

timer_label = tk.Label(colonna2, text="Operatività: 00:00:00", font=("Helvetica", 14), bg="#f0f0f0", fg="#333")
timer_label.pack(pady=10)

taratura_label = tk.Label(colonna2, text=f"Fattore di taratura: {fattore_taratura:.2f}", font=("Helvetica", 14), bg="#f0f0f0", fg="#333")
taratura_label.pack(pady=10)

tasto_piu = ttk.Button(colonna2, text="+", command=aumenta_taratura)
tasto_piu.pack(pady=5)

tasto_meno = ttk.Button(colonna2, text="-", command=diminuisci_taratura)
tasto_meno.pack(pady=5)

# Creiamo un frame separato per i pulsanti in basso
bottom_frame = tk.Frame(root, bg="#f0f0f0")
bottom_frame.pack(side=tk.BOTTOM, pady=20)

# Prima riga di pulsanti
button_row1 = tk.Frame(bottom_frame, bg="#f0f0f0")
button_row1.pack(side=tk.TOP, pady=5)

cambio_spola_button = ttk.Button(button_row1, text="Cambio Spola", command=registra_cambio_spola)
cambio_spola_button.pack(side=tk.LEFT, padx=5)

record_button = ttk.Button(button_row1, text="Visualizza Record", command=create_record_window)
record_button.pack(side=tk.LEFT, padx=5)

# Seconda riga di pulsanti
button_row2 = tk.Frame(bottom_frame, bg="#f0f0f0")
button_row2.pack(side=tk.TOP, pady=5)

apri_file_button = ttk.Button(button_row2, text="APRI FILE RECORD", command=apri_file_record)
apri_file_button.pack(side=tk.LEFT, padx=5)

cancella_record_button = ttk.Button(button_row2, text="Cancella Record", command=cancella_record)
cancella_record_button.pack(side=tk.LEFT, padx=5)

# Funzione di avvio del monitoraggio
def start_monitor():
    reset_encoder()
    update_encoder_display()

# Avvio del thread di monitoraggio
threading.Thread(target=start_monitor).start()

# Funzione per chiudere l'applicazione e pulire GPIO e SPI
def on_closing():
    save_tempo_totale_to_file()  # Salva il tempo totale di operatività
    GPIO.cleanup()
    spi.close()
    root.destroy()

root.protocol("WM_DELETE_WINDOW", on_closing)
root.mainloop()
