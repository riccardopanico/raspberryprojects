-- --------------------------------------------------------
-- Host:                         192.168.0.97
-- Versione server:              10.11.6-MariaDB-0+deb12u1 - Debian 12
-- S.O. server:                  debian-linux-gnu
-- HeidiSQL Versione:            12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dump della struttura del database raspberryprojects
DROP DATABASE IF EXISTS `raspberryprojects`;
CREATE DATABASE IF NOT EXISTS `raspberryprojects` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `raspberryprojects`;

-- Dump della struttura di tabella raspberryprojects.alembic_version
DROP TABLE IF EXISTS `alembic_version`;
CREATE TABLE IF NOT EXISTS `alembic_version` (
  `version_num` varchar(32) NOT NULL,
  PRIMARY KEY (`version_num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dump dei dati della tabella raspberryprojects.alembic_version: ~0 rows (circa)
DELETE FROM `alembic_version`;
INSERT INTO `alembic_version` (`version_num`) VALUES
	('c7e82f2810d2');

-- Dump della struttura di tabella raspberryprojects.campionatura
DROP TABLE IF EXISTS `campionatura`;
CREATE TABLE IF NOT EXISTS `campionatura` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campione` varchar(255) NOT NULL,
  `start` datetime NOT NULL,
  `stop` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dump dei dati della tabella raspberryprojects.campionatura: ~0 rows (circa)
DELETE FROM `campionatura`;

-- Dump della struttura di tabella raspberryprojects.devices
DROP TABLE IF EXISTS `devices`;
CREATE TABLE IF NOT EXISTS `devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `mac_address` varchar(17) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `gateway` varchar(45) NOT NULL,
  `subnet_mask` varchar(45) NOT NULL,
  `dns_address` varchar(45) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `device_id` (`device_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `devices_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dump dei dati della tabella raspberryprojects.devices: ~0 rows (circa)
DELETE FROM `devices`;
INSERT INTO `devices` (`id`, `device_id`, `user_id`, `mac_address`, `ip_address`, `gateway`, `subnet_mask`, `dns_address`, `created_at`) VALUES
	(5, 1, 6, '00:1A:2B:3C:4D:5E', '192.168.0.97', '192.168.0.1', '255.255.255.0', '8.8.8.8', '2024-11-22 12:18:16');

-- Dump della struttura di tabella raspberryprojects.impostazioni
DROP TABLE IF EXISTS `impostazioni`;
CREATE TABLE IF NOT EXISTS `impostazioni` (
  `codice` varchar(50) NOT NULL,
  `descrizione` varchar(255) NOT NULL,
  `valore` varchar(4000) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`codice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dump dei dati della tabella raspberryprojects.impostazioni: ~19 rows (circa)
DELETE FROM `impostazioni`;
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

-- Dump della struttura di tabella raspberryprojects.log_operazioni
DROP TABLE IF EXISTS `log_operazioni`;
CREATE TABLE IF NOT EXISTS `log_operazioni` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `id_macchina` int(11) NOT NULL,
  `id_operatore` varchar(50) NOT NULL,
  `codice` varchar(255) NOT NULL,
  `valore` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dump dei dati della tabella raspberryprojects.log_operazioni: ~5 rows (circa)
DELETE FROM `log_operazioni`;
INSERT INTO `log_operazioni` (`id`, `data`, `id_macchina`, `id_operatore`, `codice`, `valore`, `created_at`) VALUES
	(1, '2024-11-25 16:21:12', 1, '0010452223', 'id_operatore', '0010452223', '2024-11-25 16:21:12'),
	(2, '2024-11-26 09:10:28', 1, '0010452223', 'id_operatore', '0010452223', '2024-11-26 09:10:28'),
	(3, '2024-11-26 09:11:44', 1, '0010452223', 'richiesta_filato', '1', '2024-11-26 09:11:44'),
	(4, '2024-11-27 15:31:21', 1, '0010452223', 'id_operatore', '0010452223', '2024-11-27 15:31:21'),
	(5, '2024-11-27 15:31:32', 1, '0010452223', 'richiesta_intervento', '1', '2024-11-27 15:31:32'),
	(6, '2024-11-27 16:12:01', 1, '0010452223', 'id_operatore', '0010452223', '2024-11-27 16:12:01');

-- Dump della struttura di tabella raspberryprojects.log_orlatura
DROP TABLE IF EXISTS `log_orlatura`;
CREATE TABLE IF NOT EXISTS `log_orlatura` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_macchina` int(11) NOT NULL,
  `id_operatore` varchar(50) NOT NULL,
  `consumo` decimal(11,2) NOT NULL,
  `tempo` int(11) NOT NULL,
  `commessa` varchar(255) NOT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dump dei dati della tabella raspberryprojects.log_orlatura: ~0 rows (circa)
DELETE FROM `log_orlatura`;

-- Dump della struttura di tabella raspberryprojects.tasks
DROP TABLE IF EXISTS `tasks`;
CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device_id` int(11) NOT NULL,
  `task_type` varchar(50) NOT NULL,
  `sent` int(11) DEFAULT 0,
  `status` varchar(50) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dump dei dati della tabella raspberryprojects.tasks: ~0 rows (circa)
DELETE FROM `tasks`;
INSERT INTO `tasks` (`id`, `device_id`, `task_type`, `sent`, `status`, `created_at`) VALUES
	(1, 1, 'richiesta_intervento', 1, 'UNASSIGNED', '2024-11-27 15:31:32');

-- Dump della struttura di tabella raspberryprojects.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `badge` varchar(50) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `user_type` varchar(50) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dump dei dati della tabella raspberryprojects.users: ~0 rows (circa)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `badge`, `username`, `password_hash`, `user_type`, `name`, `last_name`, `email`, `created_at`) VALUES
	(6, '0010452223', 'PiDevice1', 'scrypt:32768:8:1$pBsAj8fskHZBUtqP$e912d3b6a4361aa222f694384e20b6f3ec521f2c7ab6baff6bde3a0497779b056049c7b107b05ab3d5c2fe3a9ff1e3ba61dc750381c8a977145311553d4287f7', 'device', NULL, NULL, NULL, '2024-11-22 12:18:16');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
