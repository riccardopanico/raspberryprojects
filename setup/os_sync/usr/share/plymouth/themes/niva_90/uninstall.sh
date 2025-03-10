#!/bin/bash
echo Please enter your sudo password if you are prompted to do so.
echo Uninstalling the niva_90 theme...
sudo update-alternatives --quiet --remove default.plymouth /usr/share/plymouth/themes/niva_90/niva_90.plymouth
sudo rm -rf /usr/share/plymouth/themes/niva_90
sudo update-alternatives --quiet --auto default.plymouth
sudo update-initramfs -u
echo Done!
echo Testing...
sudo plymouthd
sudo plymouth --show-splash
sleep 10
sudo plymouth quit
echo Done!
echo Have a nice day!