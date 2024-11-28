
SCRIPT_DIR="/home/pi/setup"
PROJECT_DIR="/home/pi"
FLASK_DIR="$PROJECT_DIR/flask_project"
DATABASE_NAME="datacenter"

sudo mysql -u root -praspberry -e "
DROP USER IF EXISTS 'niva'@'%';
CREATE USER 'niva'@'%' IDENTIFIED BY '01NiVa18';
DROP DATABASE IF EXISTS $DATABASE_NAME;
CREATE DATABASE IF NOT EXISTS $DATABASE_NAME;;
GRANT ALL PRIVILEGES ON *.* TO 'niva'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;
"

sudo rm -rf "$FLASK_DIR/migrations"

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

sudo mysql -u root -praspberry < "$SCRIPT_DIR/insert_server.sql"

echo "Creazione dei servizi in corso..."
sudo cp "$SCRIPT_DIR/systemd/flask_server.service" /etc/systemd/system/flask.service
sudo systemctl daemon-reload >/dev/null 2>&1

#---------------------------------------------------------------------------------

#!/bin/bash

SCRIPT_DIR="/home/pi/setup"
PROJECT_DIR="/home/pi"
FLASK_DIR="$PROJECT_DIR/flask_project"
DATABASE_NAME="datacenter"

sudo mysql -u root -praspberry -e "
DROP USER IF EXISTS 'niva'@'%';
CREATE USER 'niva'@'%' IDENTIFIED BY '01NiVa18';
DROP DATABASE IF EXISTS $DATABASE_NAME;
CREATE DATABASE IF NOT EXISTS $DATABASE_NAME;;
GRANT ALL PRIVILEGES ON *.* TO 'niva'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;
"
sudo rm -rf "$FLASK_DIR"

echo "Clonazione del progetto Flask..."
cd "$PROJECT_DIR"
sudo git clone https://github.com/riccardopanico/flask_project.git >/dev/null 2>&1
sudo cp "$SCRIPT_DIR/.env_flask_server" "$FLASK_DIR/.env"
sudo chown -R pi:www-data "$PROJECT_DIR"
sudo chmod -R 775 "$PROJECT_DIR"

echo "Configurazione del virtual environment per il progetto Flask..."
cd "$FLASK_DIR"
python3 -m venv venv
source venv/bin/activate
pip install -r requirements.txt >/dev/null 2>&1

if [ -d "$FLASK_DIR/venv" ]; then
    source "$FLASK_DIR/venv/bin/activate"
    pip install -r "$FLASK_DIR/requirements.txt" >/dev/null 2>&1
    echo "Esecuzione delle migrazioni del database Flask..."
    flask db init
    flask db migrate -m "Inizializzazione del database"
    flask db upgrade
    deactivate
else
    echo "Errore: il virtual environment non è stato creato correttamente."
    exit 1
fi

sudo mysql -u root -praspberry < "$SCRIPT_DIR/insert_server.sql"

echo "Creazione dei servizi in corso..."
sudo cp "$SCRIPT_DIR/systemd/flask_server.service" /etc/systemd/system/flask.service
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
