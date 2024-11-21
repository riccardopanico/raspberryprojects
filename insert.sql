INSERT INTO `impostazioni` (`codice`, `descrizione`, `valore`) VALUES
    ('alert_olio', 'Alert Olio', '1'),
    ('alert_spola', 'Alert Spola', '0'),
    ('articolo', 'Articolo', 'ZRACHELLE-0'),
    ('commessa', 'Commessa', '123'),
    ('data_cambio_olio', 'Data Cambio Olio', '07/11/2024 16:28:37'),
    ('data_cambio_spola', 'Data Cambio Spola', '12/11/2024 16:24:36'),
    ('distanza_reset_olio', 'Distanza Reset Olio', '100'),
    ('dns_nameservers', 'DNS Name Servers', '8.8.8.8'),
    ('fattore_taratura', 'Fattore Taratura', '78'),
    ('gateway', 'Gateway', '192.168.10.253'),
    ('id_macchina', 'ID Macchina', '1'),
    ('id_operatore', 'Badge', '0010452223'),
    ('impulsi', 'Impulsi', '0'),
    ('ip_local_server', 'IP Server Locale', '192.168.10.207'),
    ('ip_macchina', 'IP Macchina', '192.168.10.201'),
    ('last_barcode', 'Ultimo Barcode Scansionato', '123'),
    ('lunghezza_totale', 'Lunghezza totale', '0.00'),
    ('manuale_uso', 'Manuale d\'uso', 'manuale_uso.pdf'),
    ('misurazione_filo', 'Misurazione Filo', '0'),
    ('network_interface', 'Interfaccia di Rete', 'wlan0'),
    ('network_name', 'Nome Rete', 'elata'),
    ('network_password', 'Password Rete', 'Elata1923'),
    ('operativita', 'Operatività', '00:00:00'),
    ('parametro_olio', 'Parametro Olio (ore)', '2'),
    ('parametro_olio_attivo', 'Parametro Olio Attivo', '0'),
    ('parametro_spola', 'Parametro Spola (metri)', '100'),
    ('parametro_spola_attivo', 'Parametro Spola Attivo', '0'),
    ('porta_local_server', 'Porta Server Locale', '1404'),
    ('richiesta_filato', 'Richiesta Filato', '0'),
    ('richiesta_intervento', 'Richiesta Intervento', '0'),
    ('subnet', 'Subnet', '255.255.255.0'),
    ('token', 'Token', '123'),
    ('ultimo_reset_olio', 'Ultimo Reset Olio', '0'),
    ('velocita', 'Velocità', '0.00'),
    ('websocket_host', 'WebSocket Host', '192.168.0.97'),
    ('websocket_port', 'WebSocket Port', '8765');

INSERT INTO `operatori` (`id`, `nome`, `cognome`) VALUES
    ('0001752390', 'Mario', 'Rossi'),
    ('0001978094', 'Luigi', 'Verdi'),
    ('0010452223', 'Riccardo', 'Panico');
