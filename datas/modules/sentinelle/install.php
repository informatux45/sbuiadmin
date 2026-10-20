<?php
/**
 * Module sentinelle : schéma de ses tables, pour l'installer seul (onglet
 * Modules de Configuration > Mise à jour). Une table déjà présente (données
 * gardées à la désinstallation) n'est pas recréée ; ses migrations
 * (migrations/<version>.php) la mettent ensuite à niveau.
 * Extrait de backdoor/install/sql_dump/sbuiadmin_create_7.1_and_more.sql.
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

return array(
	'sql' => <<<'SQL'
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_sentinelle_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `ip` varchar(45) NOT NULL DEFAULT '',
  `country` varchar(2) NOT NULL DEFAULT '',
  `type` varchar(32) NOT NULL DEFAULT '',
  `severity` varchar(10) NOT NULL DEFAULT 'warning' COMMENT 'info, warning, critical',
  `action` varchar(10) NOT NULL DEFAULT 'logged' COMMENT 'logged, blocked, banned',
  `context` varchar(10) NOT NULL DEFAULT '' COMMENT 'front, admin',
  `method` varchar(10) NOT NULL DEFAULT '',
  `uri` varchar(255) NOT NULL DEFAULT '',
  `user_agent` varchar(255) NOT NULL DEFAULT '',
  `user` varchar(64) NOT NULL DEFAULT '',
  `detail` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `created_at` (`created_at`),
  KEY `ip_type` (`ip`, `type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_sentinelle_ban` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_range` varchar(50) NOT NULL DEFAULT '' COMMENT 'adresse ou plage CIDR',
  `reason` varchar(255) NOT NULL DEFAULT '',
  `source` varchar(10) NOT NULL DEFAULT 'manual' COMMENT 'manual, auto',
  `created_at` datetime NOT NULL,
  `expires_at` datetime DEFAULT NULL COMMENT 'NULL : permanent',
  `hits` int(11) NOT NULL DEFAULT 0,
  `last_hit` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expires_at` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
SQL
);
