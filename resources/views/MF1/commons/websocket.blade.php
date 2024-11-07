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
        socket = new WebSocket('ws://{{ $websocket_host }}:{{ $websocket_port }}');

        socket.onopen = function() {
            console.log('Connesso al server WebSocket');
            isConnected = true;
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
                    sendMessage();
                    break;
                case "updateUI":
                    // Esempio di azione che aggiorna l'interfaccia utente
                    console.log("Aggiornamento UI con dati: ", parsedData['payload']);
                    break;
                case "notify":
                    // Esempio di notifica
                    alert(parsedData['message']);
                    break;
                case "alert_spola":
                    // Esempio di notifica
                    openModal('alert_spola');
                    break;
                case "alert_olio":
                    // Esempio di notifica
                    openModal('alert_olio');
                    break;
                default:
                    console.warn("Azione sconosciuta ricevuta dal server: ", parsedData['action']);
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

    // Esempio di funzione per inviare un messaggio generico
    function sendGenericMessage() {
        queueMessage({
            action: "ping",
            payload: "Hello, server!"
        });
    }

    // Avvia la connessione WebSocket
    connectWebSocket();
</script>
