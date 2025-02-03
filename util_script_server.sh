USER="pi"
# USER="webserver"
SCRIPT_DIR="/home/$USER/setup"
FLASK_DIR="/home/$USER/flask_project"
MIGRATIONS_DIR="$FLASK_DIR/migrations/versions"
HOME_DIR="/home/$USER"
DATABASE_NAME="IndustrySyncDB"

cd "$HOME_DIR"

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

echo "Configurazione del virtual environment per il progetto Flask..."
cd "$FLASK_DIR"
python3 -m venv "$FLASK_DIR/venv"

if [ -d "$FLASK_DIR/venv" ]; then
    source "$FLASK_DIR/venv/bin/activate"
    flask db init >/dev/null 2>&1
    flask db migrate -m "Inizializzazione del database" >/dev/null 2>&1
    flask db upgrade >/dev/null 2>&1
    deactivate
else
    echo "Errore: il virtual environment non è stato creato correttamente."
    exit 1
fi

################################################################
USER="pi"
# USER="webserver"
SCRIPT_DIR="/home/$USER/setup"
FLASK_DIR="/home/$USER/flask_project"
MIGRATIONS_DIR="$FLASK_DIR/migrations/versions"
HOME_DIR="/home/$USER"
DATABASE_NAME="IndustrySyncDB"

cd "$HOME_DIR"

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
