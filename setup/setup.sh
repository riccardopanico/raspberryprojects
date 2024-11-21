#!/bin/bash

DYSPLAY_ROTATION=$1
SCRIPT_DIR=$(dirname "$(realpath "$0")")
PROJECT_DIR="/var/www/html/raspberryprojects"
FLASK_DIR="$PROJECT_DIR/python/flask_project"
$DATABASE_NAME="raspberryprojects"

if [ -z "$DYSPLAY_ROTATION" ]; then
    DYSPLAY_ROTATION=0
fi

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

echo "Aggiornamento della lista dei pacchetti in corso..." && sudo apt update -y >/dev/null 2>&1
echo "Aggiornamento dei pacchetti in corso..." && sudo apt upgrade -y >/dev/null 2>&1
echo "Aggiornamento del firmware Raspberry Pi in corso..." && sudo rpi-update -y >/dev/null 2>&1

echo "Copia dei file di configurazione in corso..."
if [ -f /boot/cmdline.txt ]; then
    sudo cp /boot/cmdline.txt /boot/cmdline.txt.bak
fi

if [ -f /boot/config.txt ]; then
    sudo cp /boot/config.txt /boot/config.txt.bak
fi

if [ -f /boot/firmware/cmdline.txt ]; then
    sudo cp /boot/firmware/cmdline.txt /boot/firmware/cmdline.txt.bak
fi

if [ -f /boot/firmware/config.txt ]; then
    sudo cp /boot/firmware/config.txt /boot/firmware/config.txt.bak
fi

echo "Installazione delle dipendenze in corso..."
sudo apt install -y --no-install-recommends xorg xserver-xorg-input-evdev xinput-calibrator openbox chromium-browser git apache2 mariadb-server npm python3-pip python3-dev build-essential network-manager plymouth plymouth-themes vsftpd xinput >/dev/null 2>&1
echo "Installazione completata!"

echo "Configurazione dello schermo in corso..."
sudo sed -i '$s/$/ fbcon=rotate:1 splash quiet plymouth.ignore-serial-consoles logo.nologo vt.global_cursor_default=0 loglevel=0 elevator=deadline dwc_otg.lpm_enable=0/' /boot/firmware/cmdline.txt
if [ "$DYSPLAY_ROTATION" -eq 0 ]; then
    sudo sed -i '$s/$/ boot=silent/' /boot/firmware/cmdline.txt
fi
sudo sed -i '/^\[all\]/a # Configurazione dello schermo HDMI-1\nhdmi_group=2\nhdmi_mode=87\nhdmi_cvt=800 480 66 1 0 0 0\ndisable_splash=1' /boot/firmware/config.txt

echo "Configuro l'interfaccia grafica..."
echo '[[ -z $DISPLAY && $XDG_VTNR -eq 1 ]] && exec startx -- -nocursor -quiet > /dev/null 2>&1' >> "$HOME/.profile"
echo 'exec openbox-session' > "$HOME/.xinitrc"
mkdir -p "$HOME/.config/openbox"
cat << EOF > "$HOME/.config/openbox/autostart"
sudo systemctl start flask.service
sudo systemctl start chromium-kiosk.service
EOF

echo "Impostazione del motd e issue in corso..."
sudo sh -c 'echo -n > /etc/motd'
sudo sh -c 'echo -n > /etc/issue'
sudo sh -c 'echo -n > /etc/issue.net'
sudo sed -i 's/^/#/' /etc/pam.d/login
sudo sed -i 's/^/#/' /etc/profile.d/sshpwd.sh

echo "Creazione del virtual host per raspberryprojects.local"
sudo bash -c 'echo "127.0.0.1       raspberryprojects.local" >> /etc/hosts'
sudo cp "$SCRIPT_DIR/apache2/raspberryprojects.conf" /etc/apache2/sites-available/raspberryprojects.conf
sudo a2enmod rewrite
sudo a2ensite raspberryprojects.conf
sudo systemctl restart apache2

echo "Impostando logo personalizzato all'avvio"
sudo mkdir -p /usr/share/plymouth/themes/niva
sudo cp "$SCRIPT_DIR/img/logoniva$ROTAZIONE_PARAMETRO.png" /usr/share/plymouth/themes/niva/logoniva.png
sudo cp "$SCRIPT_DIR/plymouth/niva.plymouth" /usr/share/plymouth/themes/niva/niva.plymouth
sudo cp "$SCRIPT_DIR/plymouth/niva.script" /usr/share/plymouth/themes/niva/niva.script
sudo plymouth-set-default-theme -R niva --rebuild-initrd
sudo update-initramfs -u

sudo systemctl restart mysql
sudo mysql -u root -praspberry -e "
DROP USER IF EXISTS 'niva'@'%';
CREATE USER 'niva'@'%' IDENTIFIED BY '01NiVa18';
DROP DATABASE IF EXISTS $DATABASE_NAME;
CREATE DATABASE IF NOT EXISTS $DATABASE_NAME;
GRANT ALL PRIVILEGES ON *.* TO 'niva'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;
"
sudo mysql -u root -praspberry < "$SCRIPT_DIR/insert.sql"

echo "Abilito connessioni da esterno (optional)"
sudo bash -c 'echo "[mysqld]" >> /etc/mysql/my.cnf'
sudo bash -c 'echo "bind-address = 0.0.0.0" >> /etc/mysql/my.cnf'

echo "Installazione PHP 8..."
sudo wget -qO /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg >/dev/null 2>&1
echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | sudo tee /etc/apt/sources.list.d/php.list >/dev/null
sudo apt update -y >/dev/null 2>&1
sudo apt install -y php8.1-common php8.1-cli php8.1-curl php8.1-gd php8.1-mbstring php8.1-xml php8.1-zip php8.1-mysql libapache2-mod-php8.1 >/dev/null 2>&1
# sudo service apache2 restart >/dev/null 2>&1

echo "Installazione Composer..."
sudo wget -qO composer-setup.php https://getcomposer.org/installer >/dev/null 2>&1
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer >/dev/null 2>&1
composer --version >/dev/null 2>&1
sudo rm -rf composer-setup.php
sudo composer self-update >/dev/null 2>&1

echo "Configurazione server FTP..."
sudo apt install -y vsftpd >/dev/null 2>&1
sudo cp /etc/vsftpd.conf /etc/vsftpd.conf.bkp >/dev/null 2>&1
sudo cp "$SCRIPT_DIR/vsftp/vsftpd.conf" /etc/vsftpd.conf
sudo systemctl restart vsftpd >/dev/null 2>&1

echo "Installazione Laravel - Clonando raspberryprojects..."
cd /var/www/html
sudo git clone https://github.com/riccardopanico/raspberryprojects.git >/dev/null 2>&1
sudo chown -R pi:www-data "$PROJECT_DIR"
sudo chmod -R 775 "$PROJECT_DIR"

echo "Installazione dipendenze laravel..."
cd "$PROJECT_DIR"
sudo COMPOSER_ALLOW_SUPERUSER=1 composer install >/dev/null 2>&1
sudo npm install >/dev/null 2>&1
sudo npm run dev >/dev/null 2>&1

echo "Creazione .env..."
ENV_FILE=""$PROJECT_DIR/.env""
sudo cp "$SCRIPT_DIR/.env_laravel" "$ENV_FILE"
sudo sed -i "s/^DB_CONNECTION=.*/DB_CONNECTION=mysql/" "$ENV_FILE"
sudo sed -i "s/^DB_HOST=.*/DB_HOST=localhost/" "$ENV_FILE"
sudo sed -i "s/^DB_PORT=.*/DB_PORT=3306/" "$ENV_FILE"
sudo sed -i "s/^DB_DATABASE=.*/DB_DATABASE=raspberryprojects/" "$ENV_FILE"
sudo sed -i "s/^DB_USERNAME=.*/DB_USERNAME=niva/" "$ENV_FILE"
sudo sed -i "s/^DB_PASSWORD=.*/DB_PASSWORD=01NiVa18/" "$ENV_FILE"

echo "Clonazione del progetto Flask..."
cd "$PROJECT_DIR"
sudo mkdir python
sudo chown -R pi:www-data "$PROJECT_DIR/python"
sudo chmod -R 775 "$PROJECT_DIR/python"
cd "$PROJECT_DIR/python"
sudo git clone https://github.com/riccardopanico/flask_project.git >/dev/null 2>&1
sudo cp "$SCRIPT_DIR/.env_flask" "$PROJECT_DIR/python/flask_project/.env"
sudo chown -R pi:www-data "$PROJECT_DIR/python"
sudo chmod -R 775 "$PROJECT_DIR/python"

echo "Configurazione del virtual environment per il progetto Flask..."
cd "$PROJECT_DIR/python/flask_project"
python3 -m venv venv
source venv/bin/activate
pip install -r requirements.txt >/dev/null 2>&1

if [ -d "$FLASK_DIR/venv" ]; then
    source "$FLASK_DIR/venv/bin/activate"
    pip install -r "$FLASK_DIR/requirements.txt" >/dev/null 2>&1
    echo "Esecuzione delle migrazioni del database Flask..."
    flask db init >/dev/null 2>&1
    flask db migrate -m "Inizializzazione del database" >/dev/null 2>&1
    flask db upgrade >/dev/null 2>&1
    deactivate
else
    echo "Errore: il virtual environment non è stato creato correttamente."
    exit 1
fi

echo "Creazione dei servizi in corso..."
sudo cp "$SCRIPT_DIR/systemd/chromium-kiosk.service" /etc/systemd/system/chromium-kiosk.service
sudo cp "$SCRIPT_DIR/systemd/flask.service" /etc/systemd/system/flask.service
sudo cp "$SCRIPT_DIR/systemd/getty-override.conf" /etc/systemd/system/getty@tty1.service.d/getty-override.conf
sudo systemctl daemon-reload >/dev/null 2>&1

echo "
#######  ###  #     #  #######
#         #   ##    #  #
#         #   # #   #  #
#####     #   #  #  #  #####
#         #   #   # #  #
#         #   #    ##  #
#        ###  #     #  #######
"
