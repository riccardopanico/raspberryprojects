#!/bin/bash

SCRIPT_DIR=$(dirname "$(realpath "$0")")
FLASK_DIR="/home/pi/flask_project"

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
sudo apt install -y --no-install-recommends mariadb-server python3-pip python3-dev build-essential
echo "Installazione completata!"

echo "Configurazione di MariaDB..."
sudo systemctl restart mysql
sudo mysql -u root -praspberry -e "
DROP USER IF EXISTS 'niva'@'%';
CREATE USER 'niva'@'%' IDENTIFIED BY '01NiVa18';
GRANT ALL PRIVILEGES ON *.* TO 'niva'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;
"
sudo mysql -u root -praspberry < "$SCRIPT_DIR/dump.sql"

echo "Abilito connessioni da esterno (optional)"
sudo bash -c 'echo "[mysqld]" >> /etc/mysql/my.cnf'
sudo bash -c 'echo "bind-address = 0.0.0.0" >> /etc/mysql/my.cnf'

echo "Clonazione del progetto Flask..."
if [ -d "$FLASK_DIR" ]; then
    sudo rm -rf "$FLASK_DIR"
fi
sudo git clone https://github.com/riccardopanico/flask_project.git "$FLASK_DIR" >/dev/null 2>&1
sudo cp "$SCRIPT_DIR/.env_flask_server" "$FLASK_DIR/.env"
sudo chown -R pi:www-data "$FLASK_DIR"
sudo chmod -R 775 "$FLASK_DIR"

echo "Configurazione del virtual environment per il progetto Flask..."
cd "$FLASK_DIR"
python3 -m venv "$FLASK_DIR/venv"

if [ -d "$FLASK_DIR/venv" ]; then
    source "$FLASK_DIR/venv/bin/activate"
    pip install -r "$FLASK_DIR/requirements.txt" >/dev/null 2>&1
    deactivate
else
    echo "Errore: il virtual environment non è stato creato correttamente."
    exit 1
fi

echo "Creazione dei servizi in corso..."
sudo cp "$SCRIPT_DIR/systemd/flask_server.service" /etc/systemd/system/flask.service
sudo systemctl daemon-reload >/dev/null 2>&1
sudo systemctl enable flask.service

echo "
#######  ###  #     #  #######
#         #   ##    #  #
#         #   # #   #  #
#####     #   #  #  #  #####
#         #   #   # #  #
#         #   #    ##  #
#        ###  #     #  #######
"
