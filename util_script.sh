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
flask db init >/dev/null 2>&1
flask db migrate >/dev/null 2>&1
flask db upgrade >/dev/null 2>&1

sudo mysql -u root -praspberry < /var/www/html/raspberryprojects/insert.sql
#---------------------------------------------------------------------------------

SCRIPT_DIR="/home/pi/setup"

sudo rm -rf /var/www/html/raspberryprojects

echo "Installazione Laravel - Clonando raspberryprojects..."
cd /var/www/html
sudo git clone https://github.com/riccardopanico/raspberryprojects.git >/dev/null 2>&1
sudo chown -R pi:www-data /var/www/html/raspberryprojects
sudo chmod -R 775 /var/www/html/raspberryprojects

echo "Installazione dipendenze laravel..."
cd /var/www/html/raspberryprojects
sudo COMPOSER_ALLOW_SUPERUSER=1 composer install >/dev/null 2>&1
sudo npm install >/dev/null 2>&1
sudo npm run dev >/dev/null 2>&1

echo "Creazione .env..."
ENV_FILE="/var/www/html/raspberryprojects/.env"
sudo cp "$SCRIPT_DIR/.env_laravel" "$ENV_FILE"
sudo sed -i "s/^DB_CONNECTION=.*/DB_CONNECTION=mysql/" "$ENV_FILE"
sudo sed -i "s/^DB_HOST=.*/DB_HOST=localhost/" "$ENV_FILE"
sudo sed -i "s/^DB_PORT=.*/DB_PORT=3306/" "$ENV_FILE"
sudo sed -i "s/^DB_DATABASE=.*/DB_DATABASE=raspberryprojects/" "$ENV_FILE"
sudo sed -i "s/^DB_USERNAME=.*/DB_USERNAME=niva/" "$ENV_FILE"
sudo sed -i "s/^DB_PASSWORD=.*/DB_PASSWORD=01NiVa18/" "$ENV_FILE"

echo "Clonazione del progetto Flask..."
cd /var/www/html/raspberryprojects
sudo mkdir python
sudo chown -R pi:www-data /var/www/html/raspberryprojects/python
sudo chmod -R 775 /var/www/html/raspberryprojects/python
cd /var/www/html/raspberryprojects/python
sudo git clone https://github.com/riccardopanico/flask_project.git >/dev/null 2>&1
sudo cp "$SCRIPT_DIR/.env_flask" /var/www/html/raspberryprojects/python/flask_project/.env
sudo chown -R pi:www-data /var/www/html/raspberryprojects/python
sudo chmod -R 775 /var/www/html/raspberryprojects/python

echo "Configurazione del virtual environment per il progetto Flask..."
cd /var/www/html/raspberryprojects/python/flask_project
python3 -m venv venv
source venv/bin/activate
pip install -r requirements.txt >/dev/null 2>&1
deactivate

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
