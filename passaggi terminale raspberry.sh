# abilita l'autologin (System Options -> Auto Login -> Console Autologin)
# abilita VNC e SPI (Interface Options -> Enable VNC and SPI)
# abilita linguaggio Italiano (104 con tastiera con invio a forma di L )
sudo raspi-config

scp C:\xampp\htdocs\raspberryprojects\setup.zip pi@192.168.0.97:/home/pi/setup.zip

unzip setup.zip && cd setup && chmod +x setup.sh && ./setup.sh
unzip setup.zip && cd setup && chmod +x setup.sh && ./setup.sh 270
unzip setup.zip && cd setup && chmod +x setup_server.sh && ./setup_server.sh

sudo systemctl status chromium-kiosk.service
sudo systemctl stop chromium-kiosk.service
sudo systemctl disable chromium-kiosk.service
sudo systemctl restart chromium-kiosk.service
sudo systemctl status flask.service
sudo systemctl restart flask.service
sudo systemctl stop flask.service
sudo systemctl disable flask.service
sudo systemctl daemon-reload
python /var/www/html/raspberryprojects/python/flask_project/manage.py
python /home/pi/flask_project/manage.py

# Secure installation of MySQL...
sudo mysql_secure_installation
# Riavvia apache
sudo service apache2 restart
# rimuovi chiave
ssh-keygen -R 192.168.0.150

pip freeze > requirements.txt

sudo systemctl stop flask.service
cd /var/www/html/raspberryprojects/python/flask_project
source venv/bin/activate
/var/www/html/raspberryprojects/python/flask_project/venv/bin/gunicorn --workers 1 --threads 8 --timeout 60 --bind 0.0.0.0:5000 manage:app

sudo systemctl stop flask.service
cd /home/webserver/flask_project
source venv/bin/activate
/home/webserver/flask_project/venv/bin/gunicorn --workers 1 --threads 8 --timeout 60 --bind 0.0.0.0:5000 manage:app

sudo systemctl stop flask.service
cd /home/pi/flask_project
source venv/bin/activate
/home/pi/flask_project/venv/bin/gunicorn --workers 1 --threads 8 --timeout 60 --bind 0.0.0.0:5000 manage:app

# se websocket non funziona
pkill -f "python"

stduser
PwD01NiVa18













chmod +x install.sh && chmod +x uninstall.sh && chmod +x test.sh




sudo nano /etc/systemd/system/plymouth-wait-for-animation.service
sudo nano /usr/share/plymouth/themes/niva/niva.plymouth
sudo nano /etc/plymouth/plymouthd.conf


sudo systemctl daemon-reload
sudo update-initramfs -u

sudo systemctl enable plymouth-wait-for-animation.service
sudo systemctl start plymouth-wait-for-animation.service
sudo nano /etc/plymouth/plymouthd.conf
sudo apt-get install plymouth plymouth-themes
