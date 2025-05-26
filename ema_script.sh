#!/bin/bash

# Parametri di configurazione
IP_STATICO="${1:-$(nmcli -t -f IP4.ADDRESS connection show --active | grep 'ethernet' | head -n 1 | cut -d'/' -f1)}"  # Indirizzo IP statico senza subnet mask, preleva il valore corrente se non specificato.
NETMASK="${2:-$(nmcli -t -f IP4.ADDRESS connection show --active | grep 'ethernet' | head -n 1 | cut -d'/' -f2)}"    # Subnet mask, preleva il valore corrente se non specificato.
GATEWAY="${3:-$(nmcli -t -f IP4.GATEWAY connection show --active | grep 'ethernet' | head -n 1)}"                     # Gateway predefinito, preleva il valore corrente se non specificato.
DNS="${4:-$(nmcli -t -f IP4.DNS connection show --active | grep 'ethernet' | head -n 1)}"                              # Server DNS, preleva il valore corrente se non specificato.

# Trova la connessione Ethernet attiva
ACTIVE_CONNECTION=$(nmcli -t -f NAME,TYPE connection show --active | grep 'ethernet' | cut -d':' -f1 | head -n 1)

# Controlla se è stata trovata una connessione attiva
if [ -z "$ACTIVE_CONNECTION" ]; then
    echo "Errore: Nessuna connessione Ethernet attiva trovata."
    exit 1
fi

echo "Connessione Ethernet attiva trovata: $ACTIVE_CONNECTION"

# Configurare l'IP statico con la netmask
IP_CONFIG="$IP_STATICO/$NETMASK"
nmcli connection modify "$ACTIVE_CONNECTION" ipv4.addresses "$IP_CONFIG" \
    ipv4.gateway "$GATEWAY" \
    ipv4.dns "$DNS" \
    ipv4.method manual

# Applica le modifiche con timeout
nmcli --wait 2 connection down "$ACTIVE_CONNECTION" > /dev/null 2>&1
nmcli --wait 2 connection up "$ACTIVE_CONNECTION" > /dev/null 2>&1

# Verifica la configurazione senza output interattivo
#nmcli -t connection show "$ACTIVE_CONNECTION"

echo "Configurazione completata con successo!"
