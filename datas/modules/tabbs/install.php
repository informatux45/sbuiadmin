<?php
/**
 * Module tabbs : schéma de ses tables, pour l'installer seul (onglet
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
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_tabbs` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_tabbs_tab` (
  `id` int(11) NOT NULL,
  `tid` int(11) NOT NULL COMMENT 'TABBS id',
  `title` text NOT NULL,
  `content` text NOT NULL,
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `sort` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `<DB_PREFIX>sb_tabbs`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `<DB_PREFIX>sb_tabbs_tab`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `<DB_PREFIX>sb_tabbs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `<DB_PREFIX>sb_tabbs_tab`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
SQL
);
