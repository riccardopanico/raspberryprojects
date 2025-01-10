SCRIPT_DIR="/home/pi/setup"
PROJECT_DIR="/var/www/html/raspberryprojects"
FLASK_DIR="$PROJECT_DIR/python/flask_project"
MIGRATIONS_DIR="$FLASK_DIR/migrations/versions"
DATABASE_NAME="IndustrySyncDB"

cd "$FLASK_DIR"

sudo systemctl restart mysql
sudo mysql -u root -praspberry -e "
DROP USER IF EXISTS 'niva'@'%';
CREATE USER 'niva'@'%' IDENTIFIED BY '01NiVa18';
DROP DATABASE IF EXISTS $DATABASE_NAME;
CREATE DATABASE IF NOT EXISTS $DATABASE_NAME;;
GRANT ALL PRIVILEGES ON *.* TO 'niva'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;
"

source "$FLASK_DIR/venv/bin/activate"
sudo rm -rf "$FLASK_DIR/migrations/"
flask db init
flask db migrate -m "Migrazioni generate dai modelli"
flask db upgrade
echo "Copia e processamento delle migrazioni personalizzate..."
PREVIOUS_REVISION=$(flask db heads | grep -oE "^[a-f0-9]{12}")
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
flask db upgrade
deactivate

###############################################
SCRIPT_DIR="/home/pi/setup"
PROJECT_DIR="/var/www/html/raspberryprojects"
FLASK_DIR="$PROJECT_DIR/python/flask_project"
MIGRATIONS_DIR="$FLASK_DIR/migrations/versions"
DATABASE_NAME="IndustrySyncDB"

sudo rm -rf "$FLASK_DIR"

sudo systemctl restart mysql
sudo mysql -u root -praspberry -e "
DROP USER IF EXISTS 'niva'@'%';
CREATE USER 'niva'@'%' IDENTIFIED BY '01NiVa18';
DROP DATABASE IF EXISTS $DATABASE_NAME;
CREATE DATABASE IF NOT EXISTS $DATABASE_NAME;;
GRANT ALL PRIVILEGES ON *.* TO 'niva'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;
"

echo "Clonazione del progetto Flask..."
cd "$PROJECT_DIR"
sudo mkdir -p python
sudo chown -R pi:www-data "$PROJECT_DIR/python"
sudo chmod -R 775 "$PROJECT_DIR/python"
cd "$PROJECT_DIR/python"
sudo git clone --branch device https://github.com/riccardopanico/flask_project.git "$FLASK_DIR" >/dev/null 2>&1
sudo cp "$SCRIPT_DIR/.env_flask" "$FLASK_DIR/.env"
sudo chown -R pi:www-data "$PROJECT_DIR/python"
sudo chmod -R 775 "$PROJECT_DIR/python"

echo "Configurazione del virtual environment per il progetto Flask..."
cd "$FLASK_DIR"
python3 -m venv venv
source "$FLASK_DIR/venv/bin/activate"
pip install -r "$FLASK_DIR/requirements.txt" >/dev/null 2>&1
sudo rm -rf "$FLASK_DIR/migrations/"
flask db init
flask db migrate -m "Migrazioni generate dai modelli"
flask db upgrade
echo "Copia e processamento delle migrazioni personalizzate..."
PREVIOUS_REVISION=$(flask db heads | grep -oE "^[a-f0-9]{12}")
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
flask db upgrade
deactivate

###############################################
SCRIPT_DIR="/home/pi/setup"
PROJECT_DIR="/var/www/html/raspberryprojects"
FLASK_DIR="$PROJECT_DIR/python/flask_project"
MIGRATIONS_DIR="$FLASK_DIR/migrations/versions"
DATABASE_NAME="IndustrySyncDB"

sudo systemctl restart mysql
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
ENV_FILE="$PROJECT_DIR/.env"
sudo cp "$SCRIPT_DIR/.env_laravel" "$ENV_FILE"
sudo sed -i "s/^DB_CONNECTION=.*/DB_CONNECTION=mysql/" "$ENV_FILE"
sudo sed -i "s/^DB_HOST=.*/DB_HOST=localhost/" "$ENV_FILE"
sudo sed -i "s/^DB_PORT=.*/DB_PORT=3306/" "$ENV_FILE"
sudo sed -i "s/^DB_DATABASE=.*/DB_DATABASE=raspberryprojects/" "$ENV_FILE"
sudo sed -i "s/^DB_USERNAME=.*/DB_USERNAME=niva/" "$ENV_FILE"
sudo sed -i "s/^DB_PASSWORD=.*/DB_PASSWORD=01NiVa18/" "$ENV_FILE"

echo "Clonazione del progetto Flask..."
cd "$PROJECT_DIR"
sudo mkdir -p python
sudo chown -R pi:www-data "$PROJECT_DIR/python"
sudo chmod -R 775 "$PROJECT_DIR/python"
cd "$PROJECT_DIR/python"
sudo git clone --branch device https://github.com/riccardopanico/flask_project.git "$FLASK_DIR" >/dev/null 2>&1
sudo cp "$SCRIPT_DIR/.env_flask" "$FLASK_DIR/.env"
sudo chown -R pi:www-data "$PROJECT_DIR/python"
sudo chmod -R 775 "$PROJECT_DIR/python"

echo "Configurazione del virtual environment per il progetto Flask..."
cd "$FLASK_DIR"
python3 -m venv venv
source "$FLASK_DIR/venv/bin/activate"
pip install -r "$FLASK_DIR/requirements.txt" >/dev/null 2>&1
sudo rm -rf "$FLASK_DIR/migrations/"
flask db init
flask db migrate -m "Migrazioni generate dai modelli"
flask db upgrade
echo "Copia e processamento delle migrazioni personalizzate..."
PREVIOUS_REVISION=$(flask db heads | grep -oE "^[a-f0-9]{12}")
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
flask db upgrade
deactivate

echo "Creazione dei servizi in corso..."
sudo mkdir -p /etc/systemd/system/getty@tty1.service.d
sudo cp "$SCRIPT_DIR/systemd/chromium-kiosk.service" /etc/systemd/system/chromium-kiosk.service
sudo cp "$SCRIPT_DIR/systemd/flask.service" /etc/systemd/system/flask.service
sudo cp "$SCRIPT_DIR/systemd/getty-override.conf" /etc/systemd/system/getty@tty1.service.d/getty-override.conf
sudo systemctl daemon-reload >/dev/null 2>&1
