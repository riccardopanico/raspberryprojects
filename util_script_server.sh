#!/bin/bash

SCRIPT_DIR="/home/webserver/setup"
FLASK_DIR="/home/webserver/flask_project"
DATABASE_NAME="datacenter"

echo "Configurazione di MariaDB..."
sudo mysql -u root -praspberry -e "
DROP USER IF EXISTS 'niva'@'%';
CREATE USER 'niva'@'%' IDENTIFIED BY '01NiVa18';
DROP DATABASE IF EXISTS $DATABASE_NAME;
CREATE DATABASE IF NOT EXISTS $DATABASE_NAME;
GRANT ALL PRIVILEGES ON *.* TO 'niva'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;
"
sudo systemctl restart mysql

echo "Clonazione del progetto Flask..."
sudo rm -rf "$FLASK_DIR"
sudo git clone https://github.com/riccardopanico/flask_project.git "$FLASK_DIR"
sudo cp "$SCRIPT_DIR/.env_flask_server" "$FLASK_DIR/.env"
sudo chown -R webserver:www-data "$FLASK_DIR"
sudo chmod -R 775 "$FLASK_DIR"

echo "Configurazione del virtual environment per il progetto Flask..."
cd "$FLASK_DIR"
python3 -m venv "$FLASK_DIR/venv"

if [ -d "$FLASK_DIR/venv" ]; then
    source "$FLASK_DIR/venv/bin/activate"
    pip install -r "$FLASK_DIR/requirements.txt"
    echo "Esecuzione delle migrazioni del database Flask..."
    flask db init
    flask db migrate -m "Inizializzazione del database"
    flask db upgrade
    deactivate
else
    echo "Errore: il virtual environment non è stato creato correttamente."
    exit 1
fi

