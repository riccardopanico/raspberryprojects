#!/bin/bash

# Acquisire il primo parametro (tipo rotazione display)
DYSPLAY_ROTATION=$1

# Verificare se il parametro di rotazione è stato fornito
if [ -z "$DYSPLAY_ROTATION" ]; then
    DYSPLAY_ROTATION=0
fi

# Blocco case sul parametro di rotazione
case "$DYSPLAY_ROTATION" in
    0)
        echo "Nessuna rotazione applicata (default)."
        ROTAZIONE_PARAMETRO=""
        ;;
    90)
        echo "Ruotando lo schermo di 90 gradi in senso orario."
        ROTAZIONE_PARAMETRO="_90"
        ;;
    180)
        echo "Ruotando lo schermo di 180 gradi."
        ROTAZIONE_PARAMETRO="_180"
        ;;
    270)
        echo "Ruotando lo schermo di 270 gradi in senso orario."
        ROTAZIONE_PARAMETRO="_270"
        ;;
    *)
        echo "Opzione di rotazione non valida: $DYSPLAY_ROTATION"
        echo "Usa 0, 90, 180, o 270 come parametro di rotazione."
        exit 1
        ;;
esac

# Aggiorno il sistema
echo "Aggiornamento della lista dei pacchetti in corso..."
sudo apt update -y >/dev/null 2>&1
echo "Aggiornamento dei pacchetti in corso..."
sudo apt upgrade -y >/dev/null 2>&1
echo "Aggiornamento del firmware Raspberry Pi in corso..."
sudo rpi-update -y >/dev/null 2>&1

# Backup cmdline.txt e config.txt
sudo cp /boot/cmdline.txt /boot/cmdline.txt.bak
sudo cp /boot/config.txt /boot/config.txt.bak
sudo cp /boot/firmware/cmdline.txt /boot/firmware/cmdline.txt.bak
sudo cp /boot/firmware/config.txt /boot/firmware/config.txt.bak

# Creo i servizi systemd
sudo cp ~/systemd/chromium-kiosk.service /etc/systemd/system/chromium-kiosk.service
sudo cp ~/systemd/flask.service /etc/systemd/system/flask.service
sudo cp ~/systemd/getty-override.conf /etc/systemd/system/getty@tty1.service.d/getty-override.conf
sudo systemctl daemon-reload >/dev/null 2>&1

# Installazioni necessarie

# Leggi i pacchetti da un file
if [ ! -f packages-list.txt ]; then
    echo "Errore: Il file packages-list.txt non esiste!"
    exit 1
fi

# Leggi tutti i pacchetti dal file in un array
# mapfile -t PACKAGES < packages-list.txt
PACKAGES=("xorg" "xserver-xorg-input-evdev" "xinput-calibrator" "openbox" "chromium-browser" "git" "apache2" "mariadb-server" "npm" "python3-pip" "network-manager" "plymouth" "plymouth-themes" "vsftpd" "xinput")

# Ottieni l'elenco dei pacchetti già installati
INSTALLED_PACKAGES=$(dpkg --get-selections | grep -v deinstall | awk '{print $1}')
TOTAL_PACKAGES=${#PACKAGES[@]}
INSTALLED_COUNT=0
TO_INSTALL=0
echo "Sto controllando quali pacchetti devono essere installati..."
for PACKAGE in "${PACKAGES[@]}"; do
    # Controlla se il pacchetto è già installato
    if ! echo "$INSTALLED_PACKAGES" | grep -qw "$PACKAGE"; then
        echo "Installando $PACKAGE..."
        sudo apt install -y --no-install-recommends $PACKAGE >/dev/null 2>&1
        # Incrementa il contatore dei pacchetti installati
        ((TO_INSTALL++))
        # Incrementa il contatore dei pacchetti totali
        ((INSTALLED_COUNT++))
        # Calcola la percentuale
        PERCENTAGE=$(( 100 * TO_INSTALL / TOTAL_PACKAGES ))
        # Stampa la percentuale solo se un pacchetto viene installato
        echo "Progresso: $PERCENTAGE% completato"
    fi
done

if [ $TO_INSTALL -eq 0 ]; then
    echo "Tutti i pacchetti sono già installati!"
else
    echo "Installazione completata! Pacchetti installati: $TO_INSTALL"
fi

# # Installazione driver schermo touch
# cd ~
# sudo rm -rf LCD-show
# git clone https://github.com/goodtft/LCD-show.git
# chmod -R 755 LCD-show
# cd LCD-show/
# sed -i 's/^sudo reboot/#sudo reboot/' ~/LCD-show/LCD5-show
# sed -i 's/^sudo reboot/#sudo reboot/' ~/LCD-show/rotate.sh
# sudo ./LCD5-show $DYSPLAY_ROTATION

# Aggiunta dei parametri al file /boot/firmware/cmdline.txt
# SE IL DYSPLAY E' RUOTATO COL PARAMETRO fbcon=rotate:1, allora RIMUOVIRE IL PARAMETRO boot=silent
# boot=silent inibisce la visualizzazione di messaggi di sistema, questo può impedire l'esecuzione di Plymouth o causare problemi con il framebuffer
# Aggiunta dei parametri al file /boot/firmware/cmdline.txt
sudo sed -i '$s/$/ fbcon=rotate:1 splash quiet plymouth.ignore-serial-consoles logo.nologo vt.global_cursor_default=0 loglevel=0 elevator=deadline dwc_otg.lpm_enable=0/' /boot/firmware/cmdline.txt
if [ "$DYSPLAY_ROTATION" -eq 0 ]; then
    sudo sed -i '$s/$/ boot=silent/' /boot/firmware/cmdline.txt
fi
sudo sed -i '/^\[all\]/a # Configurazione dello schermo HDMI-1\nhdmi_group=2\nhdmi_mode=87\nhdmi_cvt=800 480 66 1 0 0 0\ndisable_splash=1' /boot/firmware/config.txt

# # rimuro il tutti i files di configurazione
# sudo rm -f /etc/X11/xorg.conf.d/*
# # Creazione del file di configurazione del display corretto
# sudo bash -c 'cat << EOF > /etc/X11/xorg.conf.d/99-vc4.conf
# Section "OutputClass"
#     Identifier "vc4"
#     MatchDriver "vc4"
#     Driver "modesetting"
#     Option "PrimaryGPU" "true"
# EndSection
# EOF'

# Configurazione per avviare l'interfaccia grafica
echo "Configuro l'interfaccia grafica..."
echo '[[ -z $DISPLAY && $XDG_VTNR -eq 1 ]] && exec startx -- -nocursor -quiet > /dev/null 2>&1' >> ~/.profile
# Configurazione dell'interfaccia grafica per l'esecuzione di openbox
echo 'exec openbox-session' > ~/.xinitrc
# Configurazione di Openbox autostart
mkdir -p ~/.config/openbox
cat << EOF > ~/.config/openbox/autostart
sudo systemctl start flask.service
sudo systemctl start chromium-kiosk.service
EOF

# svuoto i file
# commento tutte le righe
# per un avvio pulito
sudo sh -c 'echo -n > /etc/motd'
sudo sh -c 'echo -n > /etc/issue'
sudo sh -c 'echo -n > /etc/issue.net'
sudo sed -i 's/^/#/' /etc/pam.d/login
sudo sed -i 's/^/#/' /etc/profile.d/sshpwd.sh

echo "Creazione del virtual host per raspberryprojects.local"
sudo bash -c 'echo "127.0.0.1       raspberryprojects.local" >> /etc/hosts'
sudo cp ~/apache2/raspberryprojects.conf /etc/apache2/sites-available/raspberryprojects.conf
sudo a2enmod rewrite
sudo a2ensite raspberryprojects.conf
sudo systemctl restart apache2

echo "Impostando logo personalizzato all'avvio"
sudo mkdir /usr/share/plymouth/themes/niva
sudo cp ~/img/logoniva$ROTAZIONE_PARAMETRO.png /usr/share/plymouth/themes/niva/logoniva.png
sudo cp ~/plymouth/niva.plymouth /usr/share/plymouth/themes/niva/niva.plymouth
sudo cp ~/plymouth/niva.script /usr/share/plymouth/themes/niva/niva.script
sudo plymouth-set-default-theme -R niva --rebuild-initrd
sudo update-initramfs -u

# Per abilitare connessioni da esterno (optional)
sudo bash -c 'echo "[mysqld]" >> /etc/mysql/my.cnf'
sudo bash -c 'echo "bind-address = 0.0.0.0" >> /etc/mysql/my.cnf'

sudo systemctl restart mysql
sudo mysql -u root -praspberry -e "
CREATE USER 'niva'@'%' IDENTIFIED BY '01NiVa18';
GRANT ALL PRIVILEGES ON *.* TO 'niva'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;
"
sudo mysql -u root -praspberry < dump.sql

# Installazione PHP 8
echo "Installazione PHP 8..."
sudo wget -qO /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg >/dev/null 2>&1
echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | sudo tee /etc/apt/sources.list.d/php.list >/dev/null
sudo apt update -y >/dev/null 2>&1
sudo apt install -y php8.1-common php8.1-cli php8.1-curl php8.1-gd php8.1-mbstring php8.1-xml php8.1-zip php8.1-mysql libapache2-mod-php8.1 >/dev/null 2>&1
# sudo service apache2 restart >/dev/null 2>&1

# Installazione Composer
echo "Installazione Composer..."
sudo wget -qO composer-setup.php https://getcomposer.org/installer >/dev/null 2>&1
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer >/dev/null 2>&1
composer --version >/dev/null 2>&1
sudo rm -rf composer-setup.php >/dev/null 2>&1
sudo composer self-update >/dev/null 2>&1

echo "Configurazione server FTP..."
sudo apt install -y vsftpd >/dev/null 2>&1
sudo cp /etc/vsftpd.conf /etc/vsftpd.conf.bkp >/dev/null 2>&1
sudo cp ~/vsftp/vsftpd.conf /etc/vsftpd.conf
sudo systemctl restart vsftpd >/dev/null 2>&1

echo "Installazione Laravel - Clonando raspberryprojects..."
cd /var/www/html
sudo git clone https://github.com/riccardopanico/raspberryprojects.git >/dev/null 2>&1
sudo chown -R pi:www-data /var/www/html/raspberryprojects >/dev/null 2>&1
sudo chmod -R 775 /var/www/html/raspberryprojects >/dev/null 2>&1

echo "Installazione dipendenze laravel..."
cd /var/www/html/raspberryprojects
sudo COMPOSER_ALLOW_SUPERUSER=1 composer install >/dev/null 2>&1
sudo npm install >/dev/null 2>&1
sudo npm run dev >/dev/null 2>&1

echo "Creazione .env..."
ENV_FILE="/var/www/html/raspberryprojects/.env"
sudo cp ~/.env_laravel $ENV_FILE
sudo sed -i "s/^DB_CONNECTION=.*/DB_CONNECTION=mysql/" $ENV_FILE
sudo sed -i "s/^DB_HOST=.*/DB_HOST=localhost/" $ENV_FILE
sudo sed -i "s/^DB_PORT=.*/DB_PORT=3306/" $ENV_FILE
sudo sed -i "s/^DB_DATABASE=.*/DB_DATABASE=raspberryprojects/" $ENV_FILE
sudo sed -i "s/^DB_USERNAME=.*/DB_USERNAME=niva/" $ENV_FILE
sudo sed -i "s/^DB_PASSWORD=.*/DB_PASSWORD=01NiVa18/" $ENV_FILE

# Clonazione del progetto Flask
echo "Clonazione del progetto Flask..."
cd /var/www/html/raspberryprojects/python
sudo git clone https://github.com/riccardopanico/flask_project.git >/dev/null 2>&1
sudo chown -R pi:www-data /var/www/html/raspberryprojects/python >/dev/null 2>&1
sudo chmod -R 775 /var/www/html/raspberryprojects/python >/dev/null 2>&1
sudo cp ~/.env_flask /var/www/html/raspberryprojects/python/flask_project/.env

# Setup del virtual environment e dipendenze per il progetto Flask
echo "Configurazione del virtual environment per il progetto Flask..."
cd /var/www/html/raspberryprojects/python/flask_project
python3 -m venv venv
source venv/bin/activate
pip install -r requirements.txt

# # Inizializzazione del database Flask
# echo "Inizializzazione del database Flask..."
# export FLASK_APP=app
# export FLASK_ENV=development
# flask db init
# flask db migrate -m "Initial migration."
# flask db upgrade

# Uscita dal virtual environment
deactivate

echo "
#######  ###  #     #  #######
#         #   ##    #  #
#         #   # #   #  #
#####     #   #  #  #  #####
#         #   #   # #  #
#         #   #    ##  #
#        ###  #     #  #######
"
