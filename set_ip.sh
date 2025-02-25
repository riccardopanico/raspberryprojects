#!/bin/bash

# ===============================
# Script per configurare un IP statico su una connessione Ethernet attiva
# ===============================

# Parametri di configurazione (accetta un'interfaccia di rete come primo argomento opzionale)
INTERFACCIA="${1:-$(nmcli -t -f DEVICE connection show --active | grep 'ethernet' | head -n 1)}"
IP_STATICO="${2:-$(nmcli -t -f IP4.ADDRESS connection show "$INTERFACCIA" | head -n 1 | cut -d'/' -f1)}"  # Indirizzo IP statico (senza subnet mask)
NETMASK="${3:-$(nmcli -t -f IP4.ADDRESS connection show "$INTERFACCIA" | head -n 1 | cut -d'/' -f2)}"    # Subnet mask
GATEWAY="${4:-$(nmcli -t -f IP4.GATEWAY connection show "$INTERFACCIA" | head -n 1)}"                     # Gateway predefinito
DNS="${5:-$(nmcli -t -f IP4.DNS connection show "$INTERFACCIA" | head -n 1)}"                              # Server DNS

# Se NETMASK è vuoto, assegna un valore di default (24)
if [ -z "$NETMASK" ]; then
    NETMASK=24
    echo "Attenzione: Subnet mask non trovata, impostata a /$NETMASK di default."
fi

# Verifica se l'interfaccia esiste
if ! ip link show "$INTERFACCIA" > /dev/null 2>&1; then
    echo "Errore: L'interfaccia $INTERFACCIA non esiste."
    exit 1
fi

# Verifica se la connessione esiste in NetworkManager
if ! nmcli connection show "$INTERFACCIA" > /dev/null 2>&1; then
    echo "Attenzione: Nessun profilo di connessione trovato per $INTERFACCIA, creazione in corso..."
    sudo nmcli connection add type ethernet ifname "$INTERFACCIA" con-name "$INTERFACCIA"
fi

# Identifica la connessione Ethernet attiva
ACTIVE_CONNECTION=$(nmcli -t -f NAME,DEVICE connection show --active | grep "$INTERFACCIA" | cut -d':' -f1 | head -n 1)

# Verifica se è stata trovata una connessione Ethernet attiva
if [ -z "$ACTIVE_CONNECTION" ]; then
    echo "Errore: Nessuna connessione Ethernet attiva trovata per l'interfaccia $INTERFACCIA."
    exit 1
fi

echo "Connessione Ethernet attiva trovata: $ACTIVE_CONNECTION su interfaccia $INTERFACCIA"

# Costruisce la stringa di configurazione IP (IP + subnet mask)
IP_CONFIG="$IP_STATICO/$NETMASK"

# Configura la connessione con IP statico, gateway e DNS
nmcli connection modify "$ACTIVE_CONNECTION" \
    ipv4.addresses "$IP_CONFIG" \
    ipv4.gateway "$GATEWAY" \
    ipv4.dns "$DNS" \
    ipv4.method manual

# Riavvia la connessione per applicare le modifiche (con timeout di 2 secondi)
nmcli --wait 2 connection down "$ACTIVE_CONNECTION" > /dev/null 2>&1
nmcli --wait 2 connection up "$ACTIVE_CONNECTION" > /dev/null 2>&1

# Messaggio di conferma
echo "Configurazione completata con successo su interfaccia $INTERFACCIA!"
