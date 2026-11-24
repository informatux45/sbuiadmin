<?php
/**
 * Module stats : schéma de ses tables, pour l'installer seul (onglet
 * Modules de Configuration > Mise à jour). Une table déjà présente (données
 * gardées à la désinstallation) n'est pas recréée ; ses migrations
 * (migrations/<version>.php) la mettent ensuite à niveau.
 *
 * Aucune adresse IP n'est enregistrée : le visiteur est une empreinte
 * hachée, renouvelée chaque jour (voir stats_visitor_hash()).
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

return array(
	'sql' => <<<'SQL'
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_stats_visit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `visitor` char(16) NOT NULL DEFAULT '' COMMENT 'empreinte du jour (sans IP)',
  `first_action` datetime NOT NULL,
  `last_action` datetime NOT NULL,
  `last_action_id` int(11) NOT NULL DEFAULT 0,
  `actions` int(11) NOT NULL DEFAULT 0,
  `pageviews` int(11) NOT NULL DEFAULT 0,
  `duration` int(11) NOT NULL DEFAULT 0 COMMENT 'secondes',
  `entry_url` varchar(255) NOT NULL DEFAULT '',
  `exit_url` varchar(255) NOT NULL DEFAULT '',
  `referer_type` varchar(10) NOT NULL DEFAULT 'direct' COMMENT 'direct, search, website, social, campaign',
  `referer_name` varchar(100) NOT NULL DEFAULT '',
  `referer_url` varchar(255) NOT NULL DEFAULT '',
  `referer_keyword` varchar(100) NOT NULL DEFAULT '',
  `campaign_source` varchar(100) NOT NULL DEFAULT '',
  `campaign_medium` varchar(100) NOT NULL DEFAULT '',
  `country` char(2) NOT NULL DEFAULT '',
  `language` varchar(8) NOT NULL DEFAULT '',
  `browser` varchar(32) NOT NULL DEFAULT '',
  `browser_version` varchar(16) NOT NULL DEFAULT '',
  `os` varchar(32) NOT NULL DEFAULT '',
  `os_version` varchar(16) NOT NULL DEFAULT '',
  `device` varchar(16) NOT NULL DEFAULT '' COMMENT 'desktop, smartphone, tablet, tv',
  `resolution` varchar(11) NOT NULL DEFAULT '',
  `js` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1 : vu par le traceur JS',
  PRIMARY KEY (`id`),
  KEY `visitor_last` (`visitor`, `last_action`),
  KEY `first_action` (`first_action`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_stats_action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `visit_id` int(11) NOT NULL DEFAULT 0,
  `pv` char(16) NOT NULL DEFAULT '' COMMENT 'jeton de page vue (traceur JS)',
  `created_at` datetime NOT NULL,
  `type` varchar(10) NOT NULL DEFAULT 'page' COMMENT 'page, 404, download, outlink, event, search',
  `url` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `time_spent` int(11) NOT NULL DEFAULT 0 COMMENT 'secondes avant l''action suivante',
  PRIMARY KEY (`id`),
  KEY `visit_id` (`visit_id`),
  KEY `pv` (`pv`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_stats_daily` (
  `day` date NOT NULL,
  `report` varchar(20) NOT NULL DEFAULT '',
  `label` varchar(255) NOT NULL DEFAULT '',
  `visits` int(11) NOT NULL DEFAULT 0,
  `visitors` int(11) NOT NULL DEFAULT 0,
  `actions` int(11) NOT NULL DEFAULT 0,
  `hits` int(11) NOT NULL DEFAULT 0 COMMENT 'pages vues, clics',
  `duration` int(11) NOT NULL DEFAULT 0 COMMENT 'secondes (somme)',
  `bounces` int(11) NOT NULL DEFAULT 0,
  `entries` int(11) NOT NULL DEFAULT 0,
  `exits` int(11) NOT NULL DEFAULT 0,
  UNIQUE KEY `day_report_label` (`day`, `report`, `label`),
  KEY `report_day` (`report`, `day`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_stats_dashboard` (
  `user_id` int(11) NOT NULL,
  `layout` text NOT NULL COMMENT 'JSON : colonnes, widgets, réduits',
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_stats_setting` (
  `name` varchar(64) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
SQL
);
