<?php
/**
 * Module contact : schéma de ses tables, pour l'installer seul (onglet
 * Modules de Configuration > Mise à jour). Une table déjà présente (données
 * gardées à la désinstallation) n'est pas recréée ; ses migrations
 * (migrations/<version>.php) la mettent ensuite à niveau.
 * Extrait de backdoor/install/sql_dump/sbuiadmin_create.sql.
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

return array(
	'sql' => <<<'SQL'
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_contact` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `recipients` text NOT NULL COMMENT 'destinataires',
  `subject` text NOT NULL,
  `form` text NOT NULL,
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `sort` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `<DB_PREFIX>sb_contact`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `<DB_PREFIX>sb_contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_contact_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` int(11) NOT NULL DEFAULT '0' COMMENT '0: page Contact du module',
  `created_at` datetime NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `fields` mediumtext NOT NULL COMMENT 'champs saisis (JSON)',
  `recipients` text NOT NULL COMMENT 'destinataires valides',
  `sent` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1: mail envoyé',
  `error` varchar(255) NOT NULL DEFAULT '' COMMENT 'erreur d''envoi',
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `form_created` (`form_id`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
SQL
);
