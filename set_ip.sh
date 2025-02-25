#!/bin/bash

# ===============================
# Script avanzato per configurare un IP statico su Ethernet o WiFi
# ===============================

set -e  # Interrompe lo script in caso di errore

# Funzione per mostrare l'uso corretto dello script
usage() {
    echo "Uso: sudo $0 <interfaccia> <ip_statico> <netmask> <gateway> <dns> [ssid]"
    echo "Esempio: sudo $0 eth0 192.168.1.100 24 192.168.1.1 8.8.8.8"
    echo "         sudo $0 wlan0 192.168.0.50 24 192.168.0.1 8.8.4.4 MyWiFi"
    exit 1
}

# Verifica che siano stati forniti almeno i parametri essenziali
if [ $# -lt 5 ]; then
    echo "Errore: Numero di parametri errato."
    usage
fi

INTERFACCIA="$1"
IP_STATICO="$2"
NETMASK="$3"
GATEWAY="$4"
DNS="$5"
SSID="$6"

# Verifica che l'interfaccia esista
if ! ip link show "$INTERFACCIA" > /dev/null 2>&1; then
    echo "Errore: L'interfaccia $INTERFACCIA non esiste."
    exit 1
fi

# Verifica il tipo di connessione dell'interfaccia
CONN_TYPE=$(nmcli -t -f GENERAL.TYPE device show "$INTERFACCIA" | cut -d':' -f2 | tr -d ' ')
if [ -z "$CONN_TYPE" ]; then
    echo "Errore: impossibile determinare il tipo di connessione per $INTERFACCIA."
    exit 1
fi

# Verifica che il formato dell'IP sia valido
if ! [[ "$IP_STATICO" =~ ^([0-9]{1,3}\.){3}[0-9]{1,3}$ ]]; then
    echo "Errore: Formato IP non valido ($IP_STATICO)."
    exit 1
fi

# Verifica che il formato del Gateway sia valido
if ! [[ "$GATEWAY" =~ ^([0-9]{1,3}\.){3}[0-9]{1,3}$ ]]; then
    echo "Errore: Formato Gateway non valido ($GATEWAY)."
    exit 1
fi

# Verifica che il formato del DNS sia valido
if ! [[ "$DNS" =~ ^([0-9]{1,3}\.){3}[0-9]{1,3}$ ]]; then
    echo "Errore: Formato DNS non valido ($DNS)."
    exit 1
fi

# Verifica che la subnet mask sia un numero valido tra 0 e 32
if ! [[ "$NETMASK" =~ ^[0-9]+$ ]] || [ "$NETMASK" -lt 0 ] || [ "$NETMASK" -gt 32 ]; then
    echo "Errore: Subnet mask non valida ($NETMASK)."
    exit 1
fi

# Elimina eventuali vecchi profili per la stessa interfaccia
nmcli connection delete "$INTERFACCIA" --wait 2 > /dev/null 2>&1 || true

# Creazione nuova connessione se necessario
if [ "$CONN_TYPE" == "wifi" ]; then
    if [ -z "$SSID" ]; then
        echo "Errore: L'SSID è richiesto per creare una nuova connessione WiFi."
        exit 1
    fi
    echo "Creazione di un nuovo profilo WiFi per $INTERFACCIA con SSID $SSID..."
    nmcli connection add type wifi ifname "$INTERFACCIA" con-name "$INTERFACCIA" ssid "$SSID"
else
    echo "Creazione di un nuovo profilo Ethernet per $INTERFACCIA..."
    nmcli connection add type ethernet ifname "$INTERFACCIA" con-name "$INTERFACCIA"
fi

# Identifica la connessione attiva
ACTIVE_CONNECTION=$(nmcli -t -f NAME,DEVICE connection show --active | grep "$INTERFACCIA" | cut -d':' -f1 | head -n 1)

# Verifica se è stata trovata una connessione attiva
if [ -z "$ACTIVE_CONNECTION" ]; then
    echo "Errore: Nessuna connessione attiva trovata per l'interfaccia $INTERFACCIA."
    exit 1
fi

echo "Configurazione IP in corso per $INTERFACCIA..."

# Costruisce la stringa di configurazione IP (IP + subnet mask)
IP_CONFIG="$IP_STATICO/$NETMASK"

# Configura la connessione con IP statico, gateway e DNS
nmcli connection modify "$ACTIVE_CONNECTION" \
    ipv4.addresses "$IP_CONFIG" \
    ipv4.gateway "$GATEWAY" \
    ipv4.dns "$DNS" \
    ipv4.method manual

# Riavvia la connessione per applicare le modifiche (con timeout di 2 secondi)
nmcli --wait 2 connection down "$ACTIVE_CONNECTION" > /dev/null 2>&1 || true
nmcli --wait 2 connection up "$ACTIVE_CONNECTION" > /dev/null 2>&1 || true

# Messaggio di conferma
echo "Configurazione completata con successo su interfaccia $INTERFACCIA con IP $IP_STATICO/$NETMASK"
