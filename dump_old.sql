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
CREATE DATABASE IF NOT EXISTS `raspberryprojects` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `raspberryprojects`;

-- Dump della struttura di tabella raspberryprojects.alembic_version
DROP TABLE IF EXISTS `alembic_version`;
CREATE TABLE IF NOT EXISTS `alembic_version` (
  `version_num` varchar(32) NOT NULL,
  PRIMARY KEY (`version_num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dump dei dati della tabella raspberryprojects.alembic_version: ~1 rows (circa)
DELETE FROM `alembic_version`;
INSERT INTO `alembic_version` (`version_num`) VALUES
	('357a2711ce1a');

-- Dump della struttura di tabella raspberryprojects.campionatura
DROP TABLE IF EXISTS `campionatura`;
CREATE TABLE IF NOT EXISTS `campionatura` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campione` varchar(255) NOT NULL,
  `start` datetime NOT NULL,
  `stop` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dump dei dati della tabella raspberryprojects.campionatura: ~2 rows (circa)
DELETE FROM `campionatura`;
INSERT INTO `campionatura` (`id`, `campione`, `start`, `stop`, `created_at`) VALUES
	(1, 'campione_test', '2024-11-20 10:11:49', '2024-11-20 10:12:17', '2024-11-20 10:11:49'),
	(2, 'campione_test', '2024-11-20 10:44:28', '2024-11-20 10:44:41', '2024-11-20 10:44:28');

-- Dump della struttura di tabella raspberryprojects.devices
DROP TABLE IF EXISTS `devices`;
CREATE TABLE IF NOT EXISTS `devices` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `id_dispositivo` int(11) NOT NULL,
  `indirizzo_ip` varchar(45) NOT NULL,
  `indirizzo_mac` varchar(17) NOT NULL,
  `gateway` varchar(45) NOT NULL,
  `subnet_mask` varchar(45) NOT NULL,
  `indirizzo_dns` varchar(45) NOT NULL,
  `porta_comunicazione` int(11) NOT NULL,
  `protocollo_comunicazione` varchar(10) NOT NULL,
  `id_cespite` int(11) NOT NULL,
  `matricola` varchar(50) NOT NULL,
  `numero_inventario` varchar(50) NOT NULL,
  `data_collaudo` datetime NOT NULL,
  `data_ultima_manutenzione` datetime NOT NULL,
  `data_installazione` datetime NOT NULL,
  `descrizione` varchar(255) DEFAULT NULL,
  `modello` varchar(50) NOT NULL,
  `data_garanzia` datetime DEFAULT NULL,
  PRIMARY KEY (`id`,`id_dispositivo`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `devices_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dump dei dati della tabella raspberryprojects.devices: ~0 rows (circa)
DELETE FROM `devices`;

-- Dump della struttura di tabella raspberryprojects.impostazioni
DROP TABLE IF EXISTS `impostazioni`;
CREATE TABLE IF NOT EXISTS `impostazioni` (
  `codice` varchar(50) NOT NULL,
  `descrizione` varchar(255) NOT NULL,
  `valore` varchar(4000) DEFAULT NULL,
  PRIMARY KEY (`codice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dump dei dati della tabella raspberryprojects.impostazioni: ~36 rows (circa)
DELETE FROM `impostazioni`;
INSERT INTO `impostazioni` (`codice`, `descrizione`, `valore`) VALUES
	('alert_olio', 'Alert Olio', '0'),
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
	('richiesta_filato', 'Richiesta Filato', '1'),
	('richiesta_intervento', 'Richiesta Intervento', '0'),
	('subnet', 'Subnet', '255.255.255.0'),
	('token', 'Token', '123'),
	('ultimo_reset_olio', 'Ultimo Reset Olio', '0'),
	('velocita', 'Velocità', '0.00'),
	('websocket_host', 'WebSocket Host', '192.168.0.97'),
	('websocket_port', 'WebSocket Port', '8765');

-- Dump della struttura di tabella raspberryprojects.log_operazioni
DROP TABLE IF EXISTS `log_operazioni`;
CREATE TABLE IF NOT EXISTS `log_operazioni` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `id_macchina` int(11) NOT NULL,
  `id_operatore` varchar(50) NOT NULL,
  `codice` varchar(255) NOT NULL,
  `valore` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dump dei dati della tabella raspberryprojects.log_operazioni: ~22 rows (circa)
DELETE FROM `log_operazioni`;
INSERT INTO `log_operazioni` (`id`, `data`, `id_macchina`, `id_operatore`, `codice`, `valore`) VALUES
	(1, '2024-11-20 10:11:37', 1, '0010452223', 'id_operatore', '0010452223'),
	(2, '2024-11-20 11:09:01', 1, '0010452223', 'richiesta_filato', '1'),
	(3, '2024-11-20 11:09:14', 1, '0010452223', 'parametro_olio_attivo', '1'),
	(4, '2024-11-20 11:09:14', 1, '0010452223', 'parametro_olio', '2'),
	(5, '2024-11-20 11:09:14', 1, '0010452223', 'parametro_spola_attivo', '0'),
	(6, '2024-11-20 11:09:14', 1, '0010452223', 'parametro_spola', '100'),
	(7, '2024-11-20 11:09:14', 1, '0010452223', 'fattore_taratura', '78'),
	(8, '2024-11-20 11:09:25', 1, '0010452223', 'parametro_olio_attivo', '0'),
	(9, '2024-11-20 11:09:25', 1, '0010452223', 'parametro_olio', '2'),
	(10, '2024-11-20 11:09:25', 1, '0010452223', 'parametro_spola_attivo', '0'),
	(11, '2024-11-20 11:09:25', 1, '0010452223', 'parametro_spola', '100'),
	(12, '2024-11-20 11:09:25', 1, '0010452223', 'fattore_taratura', '78'),
	(13, '2024-11-20 11:09:34', 1, '0010452223', 'parametro_olio_attivo', '0'),
	(14, '2024-11-20 11:09:34', 1, '0010452223', 'parametro_olio', '2'),
	(15, '2024-11-20 11:09:34', 1, '0010452223', 'parametro_spola_attivo', '1'),
	(16, '2024-11-20 11:09:34', 1, '0010452223', 'parametro_spola', '100'),
	(17, '2024-11-20 11:09:34', 1, '0010452223', 'fattore_taratura', '78'),
	(18, '2024-11-20 11:10:59', 1, '0010452223', 'parametro_olio_attivo', '0'),
	(19, '2024-11-20 11:10:59', 1, '0010452223', 'parametro_olio', '2'),
	(20, '2024-11-20 11:10:59', 1, '0010452223', 'parametro_spola_attivo', '0'),
	(21, '2024-11-20 11:10:59', 1, '0010452223', 'parametro_spola', '100'),
	(22, '2024-11-20 11:10:59', 1, '0010452223', 'fattore_taratura', '78');

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dump dei dati della tabella raspberryprojects.log_orlatura: ~4 rows (circa)
DELETE FROM `log_orlatura`;
INSERT INTO `log_orlatura` (`id`, `id_macchina`, `id_operatore`, `consumo`, `tempo`, `commessa`, `data`) VALUES
	(1, 1, '0010452223', 3.45, 1, '123', '2024-11-20 10:44:31'),
	(2, 1, '0010452223', 6.36, 3, '123', '2024-11-20 10:44:36'),
	(3, 1, '0010452223', 3.34, 3, '123', '2024-11-20 10:44:40'),
	(4, 1, '0010452223', 4.53, 1, '123', '2024-11-20 11:10:24');

-- Dump della struttura di tabella raspberryprojects.operatori
DROP TABLE IF EXISTS `operatori`;
CREATE TABLE IF NOT EXISTS `operatori` (
  `id` varchar(50) NOT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `cognome` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dump dei dati della tabella raspberryprojects.operatori: ~3 rows (circa)
DELETE FROM `operatori`;
INSERT INTO `operatori` (`id`, `nome`, `cognome`) VALUES
	('0001752390', 'Mario', 'Rossi'),
	('0001978094', 'Luigi', 'Verdi'),
	('0010452223', 'Riccardo', 'Panico');

-- Dump della struttura di tabella raspberryprojects.tasks
DROP TABLE IF EXISTS `tasks`;
CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_dispositivo` int(11) NOT NULL,
  `tipo_intervento` varchar(50) NOT NULL,
  `data_intervento` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dump dei dati della tabella raspberryprojects.tasks: ~0 rows (circa)
DELETE FROM `tasks`;

-- Dump della struttura di tabella raspberryprojects.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `user_type` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dump dei dati della tabella raspberryprojects.users: ~0 rows (circa)
DELETE FROM `users`;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
