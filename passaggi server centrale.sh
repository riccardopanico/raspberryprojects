# aggiorno il sistema
sudo apt update -y && sudo apt upgrade -y && sudo rpi-update -y

# abilita l'autologin (System Options -> Auto Login -> Console Autologin)
sudo raspi-config

# installazioni necessarie
sudo apt install -y --no-install-recommends git apache2 mariadb-server vsftpd

# Creazione della directory del progetto
mkdir -p flask_project
cd flask_project

# Creazione dell'ambiente virtuale Python
python -m venv flask
. flask/bin/activate

# Installazione delle dipendenze Flask
pip install -r requirements.txt

# Variabili d'ambiente all'avvio
# export FLASK_APP=manage.py
echo "export FLASK_APP='/home/pi/flask_project/manage.py'" >> ~/.bashrc
source ~/.bashrc

sudo mysql -u root

CREATE DATABASE central_server_db;
CREATE USER 'niva'@'%' IDENTIFIED BY '01NiVa18';
GRANT ALL PRIVILEGES ON *.* TO 'niva'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;
EXIT;

# Installazione MySQL
sudo mysql_secure_installation

# Per abilitare connessioni da esterno (optional)
sudo bash -c 'echo "[mysqld]" >> /etc/mysql/my.cnf'
sudo bash -c 'echo "bind-address = 0.0.0.0" >> /etc/mysql/my.cnf'

sudo systemctl restart mysql

sudo nano /etc/systemd/system/central_server.service
# inserire quanto di seguito
[Unit]
Description=Gunicorn instance to serve Flask app
After=network.target

[Service]
User=pi
Group=www-data
WorkingDirectory=/home/pi/flask_project
Environment="PATH=/home/pi/flask_project/flask/bin"
Environment="FLASK_ENV=production"
ExecStart=/home/pi/flask_project/flask/bin/gunicorn --workers 1 --threads 8 --timeout 60 --bind 0.0.0.0:5000 manage:app
Restart=always
RestartSec=5s
Umask 007

[Install]
WantedBy=multi-user.target

. flask_project/flask/bin/activate
# Migration e Upgrade
flask db init
flask db migrate
flask db upgrade

# Abilita e controlla il servizio
sudo systemctl enable central_server
sudo systemctl start central_server
sudo systemctl status central_server

# Abilita e riavvia il servizio
sudo systemctl daemon-reload
sudo systemctl restart central_server

# Assicurati che i moduli siano abilitati
sudo a2enmod proxy proxy_http proxy_fcgi rewrite
sudo a2ensite central_server
sudo systemctl restart apache2

pip freeze > requirements.txt

zip -r api_flask.zip ./*
