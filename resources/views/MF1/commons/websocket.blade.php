<script>
    // Definizione delle variabili
    let socket;
    let isConnected = false;
    let isWaitingForServer = false;
    let retryInterval = 1000; // Intervallo di riconnessione iniziale (1 secondo)
    const maxInterval = 30000; // Intervallo massimo di riconnessione (30 secondi)
    const messageQueue = [];

    // Funzione per connettere il WebSocket
    function connectWebSocket() {
        socket = new WebSocket(`ws://${window.location.hostname}:{{ $websocket_port }}`);

        socket.onopen = function() {
            console.log('Connesso al server WebSocket');
            isConnected = true;
            toggleLoader(false);
            retryInterval = 1000; // Reimposta l'intervallo di riconnessione al valore iniziale
            if (messageQueue.length > 0) {
                sendMessage();
            }
        };

        socket.onmessage = function(event) {
            console.log('Messaggio dal server', event.data);
            handleServerMessage(event.data);
        };

        socket.onclose = function() {
            console.log('Disconnesso dal server WebSocket, tentativo di riconnessione...');
            isConnected = false;
            setTimeout(reconnectWebSocket, retryInterval);
            retryInterval = Math.min(maxInterval, retryInterval *
            2); // Incrementa l'intervallo esponenzialmente fino al massimo
        };

        socket.onerror = function(error) {
            console.error('Errore WebSocket', error);
            // Chiudi la connessione per avviare una riconnessione
            socket.close();
        };
    }

    // Funzione per gestire la riconnessione
    function reconnectWebSocket() {
        if (!isConnected) {
            connectWebSocket();
        }
    }

    // Funzione per gestire i messaggi ricevuti dal server
    function handleServerMessage(data) {
        let parsedData;
        try {
            parsedData = JSON.parse(data);
        } catch (e) {
            console.error('Errore nel parsing del messaggio JSON:', e);
            return;
        }

        // Gestisci le diverse azioni
        if (parsedData['action']) {
            switch (parsedData['action']) {
                case "readyForNext":
                    isWaitingForServer = false;
                    toggleLoader(false); // Ferma il loader
                    sendMessage();
                    break;
                case "dati_orlatura":
                    let data = parsedData['data'];
                    $('[data-key="consumo_commessa"]').text(`${(data.consumo_commessa/100).toFixed(2)} m`);
                    $('[data-key="tempo_commessa"]').text(formatTimeInHoursMinutesSeconds(data.tempo_commessa));
                    $('[data-key="consumo_totale"]').text(`${(data.consumo_totale/100).toFixed(2)} m`);
                    $('[data-key="tempo_totale"]').text(formatTimeInHoursMinutesSeconds(data.tempo_totale));
                    try {
                        if(campionaturaId){
                            $('#consumo_campionatura').text(`${(data.consumo_campionatura/100).toFixed(2)} m`);
                            $('#tempo_campionatura').text(formatTimeInHoursMinutesSeconds(data.tempo_campionatura));
                        }
                    } catch (error) {
                        // console.log(error);
                    }
                    break;
                case "alert_spola":
                    openModal('alert_spola');
                    break;
                case "alert_olio":
                    openModal('alert_olio');
                    break;
                default:
                    console.warn("Azione sconosciuta ricevuta dal server: ", parsedData['action']);
            }
        }

        if (parsedData['message']) {
            const icon = parsedData['icon'] ? parsedData['icon'] : null;
            const popup = Swal.fire({
                title: parsedData['message'],
                text: ' ',
                icon: icon,
                showCancelButton: false,
                showConfirmButton: parsedData['showConfirmButton'] ? parsedData['showConfirmButton'] : true,
                customClass: {
                    popup: 'zoom-swal-popup'
                },
                // didOpen: () => {
                //     if(parsedData['timer']) {
                //         let secondi = parsedData['timer'] / 1000;
                //         // startCountdown(secondi);
                //     }
                // },
                // willClose: () => { clearCountdown(); }
            });

            if (parsedData['autoclose'] && parsedData['autoclose'] === true) {
                let timerAutoclose = parsedData['timer'] ? parsedData['timer'] : 2000;

                autocloseMsgTimer = setTimeout(function() {
                    if (!parsedData['showConfirmButton']) {
                        Swal.close();
                    }
                    autocloseMsgTimer = null;
                }, timerAutoclose);

                popup.then(() => {
                    if (autocloseMsgTimer !== null) {
                        clearTimeout(autocloseMsgTimer);
                        autocloseMsgTimer = null;
                    }
                });
            }
        }
    }

    // Funzione per inviare un messaggio alla coda
    function queueMessage(message) {
        messageQueue.push(JSON.stringify(message));
        sendMessage();
    }

    // Funzione per inviare messaggi al server
    function sendMessage() {
        if (messageQueue.length > 0) {
            if (!isWaitingForServer) {
                if (isConnected && socket.readyState === WebSocket.OPEN) {
                    let message = messageQueue.shift();
                    console.log('Messaggio da inviare:', message);
                    socket.send(message);
                    isWaitingForServer = true;
                } else {
                    console.log('WebSocket non è aperto. Riprovo a inviare il messaggio.');
                }
            } else {
                console.log('In attesa di una risposta dal server.');
            }
        } else {
            console.log('Nessun messaggio da inviare.');
            isWaitingForServer = false;
        }
    }

    // Avvia la connessione WebSocket
    connectWebSocket();
</script>
