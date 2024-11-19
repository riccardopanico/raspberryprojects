-- --------------------------------------------------------
-- Host:                         192.168.0.97
-- Versione server:              10.11.6-MariaDB-0+deb12u1 - Debian 12
-- S.O. server:                  debian-linux-gnu
-- HeidiSQL Versione:            12.6.0.6765
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
CREATE DATABASE IF NOT EXISTS `raspberryprojects` /*!40100 DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci */;
USE `raspberryprojects`;

-- Dump della struttura di tabella raspberryprojects.campionatura
DROP TABLE IF EXISTS `campionatura`;
CREATE TABLE IF NOT EXISTS `campionatura` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campione` varchar(255) NOT NULL,
  `start` datetime NOT NULL DEFAULT current_timestamp(),
  `stop` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dump dei dati della tabella raspberryprojects.campionatura: ~64 rows (circa)
DELETE FROM `campionatura`;
INSERT INTO `campionatura` (`id`, `campione`, `start`, `stop`) VALUES
	(34, 'campione_test', '2024-10-30 14:48:15', '2024-10-30 14:48:23'),
	(35, 'campione_test', '2024-10-30 14:48:32', '2024-10-30 14:48:48'),
	(36, 'campione_test', '2024-10-30 14:49:02', '2024-10-30 14:49:04'),
	(37, 'campione_test', '2024-10-30 16:06:13', NULL);

-- Dump della struttura di tabella raspberryprojects.impostazioni
DROP TABLE IF EXISTS `impostazioni`;
CREATE TABLE IF NOT EXISTS `impostazioni` (
  `codice` varchar(50) NOT NULL,
  `descrizione` varchar(255) NOT NULL,
  `valore` varchar(4000) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`codice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- Dump dei dati della tabella raspberryprojects.impostazioni: ~35 rows (circa)
DELETE FROM `impostazioni`;
INSERT INTO `impostazioni` (`codice`, `descrizione`, `valore`, `created_at`, `updated_at`, `deleted_at`) VALUES
	('alert_olio', 'Alert Olio', '1', '2024-11-07 13:07:14', '2024-11-12 11:58:56', NULL),
	('alert_spola', 'Alert Spola', '0', '2024-11-04 11:17:56', '2024-11-12 15:24:36', NULL),
	('articolo', 'Articolo', 'ZRACHELLE-0', '2024-01-16 10:37:44', '2024-05-13 06:15:51', NULL),
	('commessa', 'Commessa', '123', '2024-10-08 10:43:19', '2024-11-11 14:28:10', NULL),
	('data_cambio_olio', 'Data Cambio Olio', '07/11/2024 16:28:37', '2024-11-07 16:44:51', NULL, NULL),
	('data_cambio_spola', 'Data Cambio Spola', '12/11/2024 16:24:36', '2024-10-21 11:26:03', '2024-11-12 15:24:36', NULL),
	('distanza_reset_olio', 'Distanza Reset Olio', '100', '2024-10-08 10:43:39', NULL, NULL),
	('dns_nameservers', 'DNS Name Servers', '8.8.8.8', '2023-06-27 14:54:28', '2024-01-15 15:37:36', NULL),
	('fattore_taratura', 'Fattore Taratura', '78', '2024-10-28 11:26:37', '2024-11-12 10:59:24', NULL),
	('gateway', 'Gateway', '192.168.10.253', '2023-06-20 10:50:37', '2024-01-12 09:56:57', NULL),
	('id_macchina', 'ID Macchina', '1', '2023-02-27 09:58:22', '2023-11-28 10:12:39', NULL),
	('id_operatore', 'Badge', '0010452223', '2023-06-28 15:07:22', '2024-11-14 13:12:38', NULL),
	('impulsi', 'Impulsi', '0', '2024-10-21 11:31:20', '2024-10-21 11:31:21', NULL),
	('ip_local_server', 'IP Server Locale', '192.168.10.207', '2023-06-22 15:12:48', '2024-01-12 09:57:20', NULL),
	('ip_macchina', 'IP Macchina', '192.168.10.201', '2023-06-20 08:43:43', '2024-01-12 09:56:43', NULL),
	('last_barcode', 'Ultimo Barcode Scansionato', '123', '2023-05-31 09:30:21', '2024-11-08 10:27:22', NULL),
	('lunghezza_totale', 'Lunghezza totale', '0.00', '2024-10-21 11:32:17', '2024-10-21 11:32:18', NULL),
	('manuale_uso', 'Manuale d\'uso', 'manuale_uso.pdf', '2023-02-27 10:43:33', NULL, NULL),
	('misurazione_filo', 'Misurazione Filo', '0', '2024-10-21 11:30:13', '2024-10-21 11:30:48', NULL),
	('network_interface', 'Interfaccia di Rete', 'wlan0', '2023-06-07 17:21:06', NULL, NULL),
	('network_name', 'Nome Rete', 'elata', '2023-06-20 10:51:41', '2024-01-29 15:19:36', NULL),
	('network_password', 'Password Rete', 'Elata1923', '2023-06-20 10:52:12', '2024-01-29 15:19:36', NULL),
	('operativita', 'Operatività', '00:00:00', '2024-10-21 11:33:30', '2024-10-21 11:33:31', NULL),
	('parametro_olio', 'Parametro Olio (ore)', '2', '2024-11-08 09:36:59', '2024-11-12 10:59:24', NULL),
	('parametro_olio_attivo', 'Parametro Olio Attivo', '0', '2024-11-08 09:42:29', '2024-11-12 10:59:24', NULL),
	('parametro_spola', 'Parametro Spola (metri)', '100', '2024-10-21 12:14:10', '2024-11-12 10:59:24', NULL),
	('parametro_spola_attivo', 'Parametro Spola Attivo', '0', '2024-11-08 09:41:47', '2024-11-12 10:59:24', NULL),
	('porta_local_server', 'Porta Server Locale', '1404', '2023-06-22 15:13:27', NULL, NULL),
	('richiesta_filato', 'Richiesta Filato', '0', '2024-10-08 10:45:11', '2024-11-08 16:33:50', NULL),
	('richiesta_intervento', 'Richiesta Intervento', '0', '2023-02-27 10:02:31', '2024-11-08 16:33:54', NULL),
	('subnet', 'Subnet', '255.255.255.0', '2023-06-20 10:49:21', '2024-01-04 16:14:01', NULL),
	('token', 'Token', '123', '2023-03-02 14:48:23', NULL, NULL),
	('ultimo_reset_olio', 'Ultimo Reset Olio', '0', '2024-10-08 10:45:58', '2024-10-08 08:46:48', NULL),
	('velocita', 'Velocità', '0.00', '2024-10-21 11:32:51', '2024-10-21 11:32:52', NULL),
	('websocket_host', 'WebSocket Host', '192.168.0.97', '2024-10-29 11:09:30', NULL, NULL),
	('websocket_port', 'WebSocket Port', '8765', '2024-10-29 11:12:01', NULL, NULL);

-- Dump della struttura di tabella raspberryprojects.log_operazioni
DROP TABLE IF EXISTS `log_operazioni`;
CREATE TABLE IF NOT EXISTS `log_operazioni` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `id_macchina` int(11) NOT NULL,
  `id_operatore` varchar(50) DEFAULT '',
  `codice` varchar(255) NOT NULL DEFAULT '',
  `valore` varchar(255) NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=728 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci ROW_FORMAT=DYNAMIC;

-- Dump dei dati della tabella raspberryprojects.log_operazioni: ~617 rows (circa)
DELETE FROM `log_operazioni`;
INSERT INTO `log_operazioni` (`id`, `data`, `id_macchina`, `id_operatore`, `codice`, `valore`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, '2024-01-12 10:56:30', 1, '0025711970', 'network_name', 'Elata1923', '2024-01-12 09:56:30', '2024-01-12 09:56:30', NULL),
	(2, '2024-01-12 10:56:48', 1, '0025711970', 'ip_macchina', '192.168.10.201', '2024-01-12 09:56:48', '2024-01-12 09:56:48', NULL),
	(3, '2024-01-12 10:57:02', 1, '0025711970', 'gateway', '192.168.10.253', '2024-01-12 09:57:02', '2024-01-12 09:57:02', NULL),
	(4, '2024-01-12 10:57:20', 1, '0025711970', 'ip_local_server', '192.168.10.207', '2024-01-12 09:57:20', '2024-01-12 09:57:20', NULL);

-- Dump della struttura di tabella raspberryprojects.log_orlatura
DROP TABLE IF EXISTS `log_orlatura`;
CREATE TABLE IF NOT EXISTS `log_orlatura` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_macchina` int(11) NOT NULL,
  `id_operatore` varchar(50) NOT NULL,
  `consumo` float(11,2) NOT NULL DEFAULT 0.00,
  `tempo` int(11) NOT NULL DEFAULT 0,
  `commessa` varchar(255) NOT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=515 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- Dump dei dati della tabella raspberryprojects.log_orlatura: ~301 rows (circa)
DELETE FROM `log_orlatura`;
INSERT INTO `log_orlatura` (`id`, `id_macchina`, `id_operatore`, `consumo`, `tempo`, `commessa`, `data`) VALUES
	(183, 1, '0010452223', 21.46, 2, 'abc', '2024-10-31 16:57:36'),
	(184, 1, '0010452223', 23.74, 2, 'abc', '2024-10-31 16:57:40'),
	(185, 1, '0010452223', 8.71, 2, 'abc', '2024-10-31 16:57:47'),
	(186, 1, '0010452223', 27.92, 5, 'abc', '2024-10-31 17:06:56');

-- Dump della struttura di tabella raspberryprojects.operatori
DROP TABLE IF EXISTS `operatori`;
CREATE TABLE IF NOT EXISTS `operatori` (
  `id` varchar(50) NOT NULL DEFAULT '0',
  `nome` varchar(50) DEFAULT NULL,
  `cognome` varchar(50) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- Dump dei dati della tabella raspberryprojects.operatori: ~3 rows (circa)
DELETE FROM `operatori`;
INSERT INTO `operatori` (`id`, `nome`, `cognome`, `created_at`, `updated_at`, `deleted_at`) VALUES
	('0001752390', 'Mario', 'Rossi', '2024-10-17 15:25:44', NULL, NULL),
	('0001978094', 'Luigi', 'Verdi', '2024-10-17 15:25:44', NULL, NULL),
	('0010452223', 'Riccardo', 'Panico', '2024-10-17 15:25:44', NULL, NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
