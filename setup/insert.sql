USE `raspberryprojects`;
-- Dump dei dati della tabella raspberryprojects.impostazioni: ~14 rows (circa)
INSERT INTO `impostazioni` (`codice`, `descrizione`, `valore`, `created_at`) VALUES
	('alert_olio', 'Alert Olio', '1', '2024-11-21 16:45:10'),
	('alert_spola', 'Alert Spola', '0', '2024-11-21 16:45:10'),
	('articolo', 'Articolo', 'ZRACHELLE-0', '2024-11-21 16:45:10'),
	('commessa', 'Commessa', '123', '2024-11-21 16:45:10'),
	('data_cambio_olio', 'Data Cambio Olio', '07/11/2024 16:28:37', '2024-11-21 16:45:10'),
	('data_cambio_spola', 'Data Cambio Spola', '12/11/2024 16:24:36', '2024-11-21 16:45:10'),
	('distanza_reset_olio', 'Distanza Reset Olio', '100', '2024-11-21 16:45:10'),
	('dns_nameservers', 'DNS Name Servers', '8.8.8.8', '2024-11-21 16:45:10'),
	('fattore_taratura', 'Fattore Taratura', '78', '2024-11-21 16:45:10'),
	('gateway', 'Gateway', '192.168.10.253', '2024-11-21 16:45:10'),
	('id_macchina', 'ID Macchina', '1', '2024-11-21 16:45:10'),
	('id_operatore', 'Badge', '0010452223', '2024-11-21 16:45:10'),
	('ip_local_server', 'IP Server Locale', '192.168.10.207', '2024-11-21 16:45:10'),
	('ip_macchina', 'IP Macchina', '192.168.10.201', '2024-11-21 16:45:10'),
	('last_barcode', 'Ultimo Barcode Scansionato', '123', '2024-11-21 16:45:10'),
	('manuale_uso', 'Manuale d\'uso', 'manuale_uso.pdf', '2024-11-21 16:45:10'),
	('network_interface', 'Interfaccia di Rete', 'wlan0', '2024-11-21 16:45:10'),
	('network_name', 'Nome Rete', 'elata', '2024-11-21 16:45:10'),
	('network_password', 'Password Rete', 'Elata1923', '2024-11-21 16:45:10'),
	('parametro_olio', 'Parametro Olio (ore)', '2', '2024-11-21 16:45:10'),
	('parametro_olio_attivo', 'Parametro Olio Attivo', '0', '2024-11-21 16:45:10'),
	('parametro_spola', 'Parametro Spola (metri)', '100', '2024-11-21 16:45:10'),
	('parametro_spola_attivo', 'Parametro Spola Attivo', '0', '2024-11-21 16:45:10'),
	('porta_local_server', 'Porta Server Locale', '1404', '2024-11-21 16:45:10'),
	('richiesta_filato', 'Richiesta Filato', '0', '2024-11-21 16:45:10'),
	('richiesta_intervento', 'Richiesta Intervento', '0', '2024-11-21 16:45:10'),
	('subnet', 'Subnet', '255.255.255.0', '2024-11-21 16:45:10'),
	('websocket_host', 'WebSocket Host', '192.168.0.97', '2024-11-21 16:45:10'),
	('websocket_port', 'WebSocket Port', '8765', '2024-11-21 16:45:10');

-- Dump dei dati della tabella raspberryprojects.log_operazioni: ~3 rows (circa)
INSERT INTO `log_operazioni` (`id`, `data`, `id_macchina`, `id_operatore`, `codice`, `valore`, `created_at`) VALUES
	(1, '2024-11-25 16:21:12', 1, '0010452223', 'id_operatore', '0010452223', '2024-11-25 16:21:12'),
	(2, '2024-11-26 09:10:28', 1, '0010452223', 'id_operatore', '0010452223', '2024-11-26 09:10:28'),
	(3, '2024-11-26 09:11:44', 1, '0010452223', 'richiesta_filato', '1', '2024-11-26 09:11:44');

-- Dump dei dati della tabella raspberryprojects.users: ~1 rows (circa)
INSERT INTO `users` (`id`, `badge`, `username`, `password_hash`, `user_type`, `name`, `last_name`, `email`, `created_at`) VALUES
	(6, '0010452223', 'PiDevice1', 'scrypt:32768:8:1$pBsAj8fskHZBUtqP$e912d3b6a4361aa222f694384e20b6f3ec521f2c7ab6baff6bde3a0497779b056049c7b107b05ab3d5c2fe3a9ff1e3ba61dc750381c8a977145311553d4287f7', 'device', NULL, NULL, NULL, '2024-11-22 12:18:16');

-- Dump dei dati della tabella raspberryprojects.devices: ~1 rows (circa)
INSERT INTO `devices` (`id`, `device_id`, `user_id`, `mac_address`, `ip_address`, `gateway`, `subnet_mask`, `dns_address`, `created_at`) VALUES
	(5, 1, 6, '00:1A:2B:3C:4D:5E', '192.168.0.97', '192.168.0.1', '255.255.255.0', '8.8.8.8', '2024-11-22 12:18:16');
