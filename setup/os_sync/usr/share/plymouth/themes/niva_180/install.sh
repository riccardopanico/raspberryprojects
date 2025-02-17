#!/bin/bash
echo Please enter your sudo password if you are prompted to do so.
echo Installing the niva_180 theme...
sudo mkdir /usr/share/plymouth/themes/niva_180
sudo cp -rf ./ /usr/share/plymouth/themes/niva_180
sudo update-alternatives --quiet --install /usr/share/plymouth/themes/default.plymouth default.plymouth /usr/share/plymouth/themes/niva_180/niva_180.plymouth 100
sudo update-alternatives --quiet --set default.plymouth /usr/share/plymouth/themes/niva_180/niva_180.plymouth
sudo update-initramfs -u
echo Done!
echo Testing...
sudo plymouthd
sudo plymouth --show-splash
sleep 10
sudo plymouth quit
echo Done!
echo Have a nice day!