# abilita l'autologin (System Options -> Auto Login -> Console Autologin)
# abilita VNC e SPI (Interface Options -> Enable VNC and SPI)
# abilita linguaggio Italiano (Localisation Options)
sudo raspi-config

scp C:\xampp\htdocs\raspberryprojects\setup.zip pi@192.168.0.97:/home/pi/setup.zip

unzip setup.zip && cd setup && chmod +x setup.sh && ./setup.sh 90
unzip setup.zip && cd setup && chmod +x setup_server.sh && ./setup_server.sh

sudo systemctl restart flask.service
sudo systemctl restart chromium-kiosk.service
/var/www/html/raspberryprojects/python/flask_project/venv/bin/gunicorn --workers 1 --threads 8 --timeout 60 --bind 0.0.0.0:5000 manage:app

# per ottenere la lista dei pacchetti installati, si usa il comando
dpkg --get-selections | grep -v deinstall | awk '{print $1}' > packages-list.txt
# Secure installation of MySQL...
sudo mysql_secure_installation
# Riavvia apache
sudo service apache2 restart
# per riavviare il sistema, si usa il comando
clear && sudo systemctl stop getty@tty1.service && sudo reboot --no-wall
# per spegnere il sistema, si usa il comando
clear && sudo systemctl stop getty@tty1.service && sudo poweroff --no-wall
# rimuovi chiave
ssh-keygen -R 192.168.0.150

pip freeze > requirements.txt
