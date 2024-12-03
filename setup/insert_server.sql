USE `datacenter`;

-- Dump dei dati della tabella datacenter.users: ~1 rows (circa)
INSERT INTO `users` (`id`, `badge`, `username`, `password_hash`, `user_type`, `name`, `last_name`, `email`, `created_at`) VALUES
	(6, '0010452223', 'PiDevice1', 'scrypt:32768:8:1$pBsAj8fskHZBUtqP$e912d3b6a4361aa222f694384e20b6f3ec521f2c7ab6baff6bde3a0497779b056049c7b107b05ab3d5c2fe3a9ff1e3ba61dc750381c8a977145311553d4287f7', 'device', NULL, NULL, NULL, '2024-11-22 12:18:16');

-- Dump dei dati della tabella datacenter.devices: ~1 rows (circa)
INSERT INTO `devices` (`id`, `device_id`, `user_id`, `mac_address`, `ip_address`, `gateway`, `subnet_mask`, `dns_address`, `created_at`) VALUES
	(5, 1, 6, '00:1A:2B:3C:4D:5E', '192.168.0.97', '192.168.0.1', '255.255.255.0', '8.8.8.8', '2024-11-22 12:18:16');
