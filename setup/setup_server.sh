#!/bin/bash

SCRIPT_DIR=$(dirname "$(realpath "$0")")
FLASK_DIR="$SCRIPT_DIR/flask_project"

echo "Aggiornamento della lista dei pacchetti in corso..." && sudo apt update -y #>/dev/null 2>&1
echo "Aggiornamento dei pacchetti in corso..." && sudo apt upgrade -y #>/dev/null 2>&1
echo "Aggiornamento del firmware Raspberry Pi in corso..." && sudo rpi-update -y #>/dev/null 2>&1

echo "Installazione delle dipendenze in corso..."
sudo apt install -y --no-install-recommends mariadb-server python3-pip python3-dev build-essential
echo "Installazione completata!"

echo "Copia dei file di configurazione in corso..."
sudo cp /boot/cmdline.txt /boot/cmdline.txt.bak
sudo cp /boot/config.txt /boot/config.txt.bak
sudo cp /boot/firmware/cmdline.txt /boot/firmware/cmdline.txt.bak
sudo cp /boot/firmware/config.txt /boot/firmware/config.txt.bak


sudo systemctl restart mysql
sudo mysql -u root -praspberry -e "
CREATE USER 'niva'@'%' IDENTIFIED BY '01NiVa18';
GRANT ALL PRIVILEGES ON *.* TO 'niva'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;
"
sudo mysql -u root -praspberry < "$SCRIPT_DIR/dump.sql"

echo "Abilito connessioni da esterno (optional)"
sudo bash -c 'echo "[mysqld]" >> /etc/mysql/my.cnf'
sudo bash -c 'echo "bind-address = 0.0.0.0" >> /etc/mysql/my.cnf'

echo "Clonazione del progetto Flask..."
sudo git clone https://github.com/riccardopanico/flask_project.git "$FLASK_DIR" #>/dev/null 2>&1
sudo cp "$SCRIPT_DIR/.env_flask" "$FLASK_DIR"/.env"
sudo chown -R pi:www-data "$FLASK_DIR"
sudo chmod -R 775 "$FLASK_DIR"

echo "Configurazione del virtual environment per il progetto Flask..."
cd "$FLASK_DIR"
python3 -m venv "$FLASK_DIR/venv"
source "$FLASK_DIR/venv/bin/activate"
pip install -r "$FLASK_DIR/requirements.txt" #>/dev/null 2>&1
deactivate

echo "Creazione dei servizi in corso..."
sudo cp "$SCRIPT_DIR/systemd/flask.service" /etc/systemd/system/flask.service
sudo systemctl daemon-reload #>/dev/null 2>&1
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
