# abilita l'autologin (System Options -> Auto Login -> Console Autologin)
# abilita VNC e SPI (Interface Options -> Enable VNC and SPI)
# abilita linguaggio Italiano (Localisation Options)
sudo raspi-config

scp C:\xampp\htdocs\raspberryprojects\setup.zip pi@192.168.0.97:/home/pi/setup.zip

unzip setup.zip && cd setup && chmod +x setup.sh && ./setup.sh 270
unzip setup.zip && cd setup && chmod +x setup_server.sh && ./setup_server.sh

sudo systemctl status flask.service
sudo systemctl restart flask.service
sudo systemctl restart chromium-kiosk.service

sudo systemctl stop flask.service
/home/pi/flask_project/venv/bin/gunicorn --workers 1 --threads 8 --timeout 60 --bind 0.0.0.0:5000 manage:app
/var/www/html/raspberryprojects/python/flask_project/venv/bin/gunicorn --workers 1 --threads 8 --timeout 60 --bind 0.0.0.0:5000 manage:app
python /home/pi/flask_project/manage.py

# Secure installation of MySQL...
sudo mysql_secure_installation
# Riavvia apache
sudo service apache2 restart
# rimuovi chiave
ssh-keygen -R 192.168.0.150

pip freeze > requirements.txt

cd /var/www/html/raspberryprojects/python/flask_project
source venv/bin/activate
