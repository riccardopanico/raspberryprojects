#!/bin/bash

SCRIPT_DIR=$(dirname "$(realpath "$0")")
FLASK_DIR="/home/webserver/flask_project"
MIGRATIONS_DIR="$FLASK_DIR/migrations/versions"
DATABASE_NAME="datacenter"

echo "Installazione delle dipendenze in corso..."
sudo apt install -y --no-install-recommends mariadb-server python3-pip python3-dev build-essential libmariadb-dev git >/dev/null 2>&1
echo "Installazione completata!"

echo "Configurazione di MariaDB..."
sudo systemctl restart mysql
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

echo "Clonazione del progetto Flask..."
if [ -d "$FLASK_DIR" ]; then
    sudo rm -rf "$FLASK_DIR"
fi
sudo git clone --branch datacenter https://github.com/riccardopanico/flask_project.git "$FLASK_DIR" >/dev/null 2>&1
sudo cp "$SCRIPT_DIR/.env_flask_server" "$FLASK_DIR/.env"
sudo chown -R webserver:www-data "$FLASK_DIR"
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
    echo "Copia e processamento delle migrazioni personalizzate..."
    PREVIOUS_REVISION=$(flask db heads | grep -oE "^[a-f0-9]{12}")
    mkdir -p "$MIGRATIONS_DIR"
    for file in $(ls "$SCRIPT_DIR/migrations/versions/"*.py | sort); do
        NEW_REVISION=$(uuidgen | tr -d '-' | tr '[:upper:]' '[:lower:]' | head -c 12)
        BASENAME=$(basename "$file" .py)
        TARGET_FILE="$MIGRATIONS_DIR/${NEW_REVISION}_$(echo "$BASENAME" | tr ' ' '_').py"
        cp "$file" "$TARGET_FILE"
        sed -i "s/^revision = None/revision = '$NEW_REVISION'/g" "$TARGET_FILE"
        sed -i "s/^down_revision = None/down_revision = '$PREVIOUS_REVISION'/g" "$TARGET_FILE"
        echo "Migrazione personalizzata processata: $TARGET_FILE"
        PREVIOUS_REVISION=$NEW_REVISION
    done
    flask db upgrade >/dev/null 2>&1
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
