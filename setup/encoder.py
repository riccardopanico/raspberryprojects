import spidev
import time
import threading
import RPi.GPIO as GPIO
import os

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
fattore_taratura = 1.0
file_fattore_taratura = "fattore_taratura.txt"
file_record = "record.txt"

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

# Funzione per salvare i record nel file
def save_record_to_file(impulsi, lunghezza):
    with open(file_record, "a") as f:
        f.write(f"Impulsi: {impulsi}, Lunghezza: {lunghezza:.6f} cm\n")
        print(f"Impulsi: {impulsi}, Lunghezza: {lunghezza:.6f} cm\n")

# Funzione per monitorare l'encoder e salvare periodicamente i dati
def monitor_encoder():
    global lettura_precedente_encoder, lunghezza_totale_filo

    while True:
        impulsi_totali = read_counter()
        differenza_impulsi = impulsi_totali - lettura_precedente_encoder
        lettura_precedente_encoder = impulsi_totali

        lunghezza_aggiornata = differenza_impulsi * LUNGHEZZA_PER_IMPULSO * fattore_taratura
        lunghezza_totale_filo += lunghezza_aggiornata

        save_record_to_file(impulsi_totali, lunghezza_totale_filo)
        time.sleep(10)  # Salva i dati ogni 10 secondi

# Carica il fattore di taratura e configura l'encoder
load_fattore_taratura_from_file()
reset_registers()
configure_encoder_x4_mode()

# Avvio del monitoraggio dell'encoder in un thread separato
threading.Thread(target=monitor_encoder).start()

# Pulizia GPIO e SPI alla chiusura
def cleanup():
    GPIO.cleanup()
    spi.close()

try:
    while True:
        time.sleep(1)  # Mantieni il programma attivo
except KeyboardInterrupt:
    cleanup()
