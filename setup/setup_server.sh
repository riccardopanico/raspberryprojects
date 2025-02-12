#!/bin/bash

USER="pi"
# USER="webserver"
SCRIPT_DIR=$(dirname "$(realpath "$0")")
# SCRIPT_DIR="/home/$USER/setup"
FLASK_DIR="/home/$USER/flask_project"
MIGRATIONS_DIR="$FLASK_DIR/migrations/versions"
HOME_DIR="/home/$USER"
DATABASE_NAME="IndustrySyncDB"

echo "Installazione delle dipendenze in corso..."
sudo apt install -y --no-install-recommends mariadb-server python3-pip python3-dev build-essential libmariadb-dev git apache2 npm uuid-runtime libaio1 >/dev/null 2>&1
echo "Installazione completata!"

echo "Configurazione di MariaDB..."
sudo mysql -u root -praspberry -e "
DROP USER IF EXISTS 'niva'@'%';
CREATE USER 'niva'@'%' IDENTIFIED BY '01NiVa18';
DROP DATABASE IF EXISTS $DATABASE_NAME;
CREATE DATABASE IF NOT EXISTS $DATABASE_NAME;
GRANT ALL PRIVILEGES ON *.* TO 'niva'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;
"
echo "Abilito connessioni da esterno (optional)"
sudo bash -c 'echo "[mysqld]" >> /etc/mysql/my.cnf'
sudo bash -c 'echo "bind-address = 0.0.0.0" >> /etc/mysql/my.cnf'
sudo systemctl restart mysql

echo "Clonazione del progetto Flask..."
sudo rm -rf "$FLASK_DIR"
mkdir -p "$FLASK_DIR"
sudo git clone --branch datacenter https://github.com/riccardopanico/flask_project.git "$FLASK_DIR" >/dev/null 2>&1
sudo cp "$SCRIPT_DIR/.env_flask" "$FLASK_DIR/.env"
sudo chown -R "$USER:www-data" "$FLASK_DIR"
sudo chmod -R 775 "$FLASK_DIR"

echo "Configurazione del virtual environment per il progetto Flask..."
cd "$FLASK_DIR"
python3 -m venv "$FLASK_DIR/venv"

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
sudo cp "$SCRIPT_DIR/os_sync/etc/systemd/system/flask.service" /etc/systemd/system/flask.service
sudo sed -i "s|__PATH__|$FLASK_DIR|g" /etc/systemd/system/flask.service
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
