# Laravel Raspberry Pi Project

Questo è un progetto Laravel che fornisce un'applicazione web per la gestione e configurazione di un dispositivo Raspberry Pi, con funzionalità di autenticazione, salvataggio delle impostazioni, e operazioni di sistema come il riavvio e lo spegnimento. Il progetto utilizza tecnologie come Laravel per il backend e un set di script di configurazione per preparare l'ambiente su Raspberry Pi.

## Struttura del Progetto

La struttura del progetto è la seguente:

# Laravel Raspberry Pi Project

Questo è un progetto Laravel che fornisce un'applicazione web per la gestione e configurazione di un dispositivo Raspberry Pi, con funzionalità di autenticazione, salvataggio delle impostazioni, e operazioni di sistema come il riavvio e lo spegnimento. Il progetto utilizza tecnologie come Laravel per il backend e un set di script di configurazione per preparare l'ambiente su Raspberry Pi.

## Struttura del Progetto

La struttura del progetto è la seguente:


- `app/`: Contiene i controller e i modelli principali del progetto.
  - `Http/Controllers/`: Contiene i controller per la gestione dell'autenticazione e delle operazioni principali.
  - `Http/Middleware/`: Contiene i middleware per la gestione dell'autenticazione tramite token.
  - `Models/`: Eventuali modelli aggiuntivi per la gestione dei dati.
- `routes/`: Contiene i file di routing per le rotte web e API.
  - `web.php`: Gestisce le rotte web e le operazioni protette da autenticazione.
  - `api.php`: Gestisce le API per la configurazione e gestione del dispositivo.
- `resources/views/`: Contiene le viste HTML per il frontend.
- `.env`: File di configurazione per le variabili di ambiente (come database e credenziali).
- `artisan`: Strumento CLI per la gestione del progetto Laravel.
- `composer.json`: File di configurazione per le dipendenze PHP.
- `requirements.txt`: File per le dipendenze Python necessarie su Raspberry Pi.

## Prerequisiti

- Raspberry Pi con Raspbian OS
- PHP 7.x o superiore
- Composer
- MySQL o altro DB
- VNC e SPI abilitati su Raspberry Pi

## Installazione

### Configurazione iniziale di Raspberry Pi

   Esegui il seguente comando per abilitare l'autologin, VNC, SPI e il linguaggio italiano:

   ```bash
   sudo raspi-config
```
### Carica i file di configurazione sul Raspberry Pi

   Esegui il seguente comando per abilitare l'autologin, VNC, SPI e il linguaggio italiano:

   ```bash
   scp C:\xampp\htdocs\raspberryprojects\setup.zip pi@192.168.0.97:/home/pi/setup.zip
   unzip setup.zip && cd setup && chmod +x setup.sh && ./setup.sh 90
   ```
### Riavvia Raspberry Pi

   Esegui il seguente comando per riavviare il sistema:

   ```bash
   sudo systemctl restart flask.service
   sudo systemctl restart chromium-kiosk.service
   ```

### Installa i pacchetti richiesti: 

    ```bash
    dpkg --get-selections | grep -v deinstall | awk '{print $1}' > packages-list.txt
    ```

### Configura MySQL

    ```bash
    sudo mysql_secure_installation
    ```

### Riavvia Apache

    ```bash
    sudo systemctl restart apache2
    ```

### Installa le dipendenze Python

    ```bash
    pip freeze > requirements.txt
    ```

## Configurazione del progetto Laravel

    - Crea un file .env nella directory principale e aggiungi le variabili di configurazione necessarie (come DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, 
    DB_PASSWORD).
    - Esegui il comando di Laravel per generare la chiave dell'applicazione:

        ```bash
        php artisan key:generate
        ```
## Esecuzione del Server

    ```bash
    php artisan serve --host=0.0.0.0 --port=8000
    ```
    
    - L'applicazione sarà disponibile all'indirizzo http://<raspberry_pi_ip>:8000.

## Configura il database

    - Se stai utilizzando MySQL, assicurati che il database sia configurato correttamente nel file .env.

### Funzionalità Principali

    - Autenticazione: Implementa il login e logout tramite un controller di autenticazione.
    - Gestione delle impostazioni: Permette di visualizzare, modificare e salvare le impostazioni del dispositivo.
    - Operazioni di sistema: Fornisce funzionalità come riavvio, spegnimento e campionatura.
    - API: Espone un set di API protette da token per la configurazione e gestione delle impostazioni del dispositivo.

## Esempi di API
    
- **Ottenere una impostazione**
    Endpoint: `/api/getSetting/{setting}`
    Metodo: `GET`
    - Risposta:
    ```json
    { "setting_name": "setting_value" }
    ```
- **Impostare una configurazione**        
    Endpoint: `/api/setSetting/{setting}`
    Metodo: POST
    Dati richiesti:
    ```json
    {
    "value": "new_value"
    }
    ```
    - Risposta
    ```json
    { "msg": "Setting updated successfully" }
    ```

### Gestione delle Rotte
- Rotte Web: Gestiscono le operazioni di visualizzazione e modifica delle impostazioni tramite interfaccia utente.
- Rotte API: Esponiamo un'API per la configurazione del dispositivo, accessibile tramite token di autenticazione.
## Web Routes
- login : Gestisce la pagina di login per gli utenti.
- home : La pagina principale dopo l'autenticazione.
- impostazioni : Pannello per configurare le impostazioni del dispositivo.
- reboot : Riavvia il dispositivo.
- shutdown : Spegne il dispositivo.
## API Routes
- getSettings : Ottiene tutte le impostazioni del dispositivo.
- setSetting : Imposta una configurazione specifica del dispositivo.
