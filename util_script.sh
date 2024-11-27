sudo systemctl stop flask.service
cd /var/www/html/raspberryprojects/python/flask_project
source venv/bin/activate
/var/www/html/raspberryprojects/python/flask_project/venv/bin/gunicorn --workers 1 --threads 8 --timeout 60 --bind 0.0.0.0:5000 manage:app

sudo mysql -u root -praspberry -e "
DROP USER IF EXISTS 'niva'@'%';
CREATE USER 'niva'@'%' IDENTIFIED BY '01NiVa18';
DROP DATABASE IF EXISTS raspberryprojects;
CREATE DATABASE IF NOT EXISTS raspberryprojects;
GRANT ALL PRIVILEGES ON *.* TO 'niva'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;
"
sudo rm -rf /var/www/html/raspberryprojects/python/flask_project/migrations

echo "Migrazione del database Flask..."
flask db init
flask db migrate
flask db upgrade

sudo mysql -u root -praspberry < /home/pi/setup/insert.sql
#---------------------------------------------------------------------------------

#!/bin/bash

SCRIPT_DIR="/home/pi/setup"
PROJECT_DIR="/var/www/html/raspberryprojects"
FLASK_DIR="$PROJECT_DIR/python/flask_project"
DATABASE_NAME="raspberryprojects"

sudo mysql -u root -praspberry -e "
DROP USER IF EXISTS 'niva'@'%';
CREATE USER 'niva'@'%' IDENTIFIED BY '01NiVa18';
DROP DATABASE IF EXISTS $DATABASE_NAME;
CREATE DATABASE IF NOT EXISTS $DATABASE_NAME;;
GRANT ALL PRIVILEGES ON *.* TO 'niva'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;
"
sudo rm -rf "$PROJECT_DIR"

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

sudo mysql -u root -praspberry < "$SCRIPT_DIR/insert.sql"

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
