<?php
/**
 * Module table : schéma de ses tables, pour l'installer seul (onglet
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
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_table` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` varchar(10) NOT NULL COMMENT 'option1, option2, ...',
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_table_datas` (
  `id` int(11) NOT NULL,
  `tid` int(11) NOT NULL COMMENT 'Table ID',
  `content` text NOT NULL COMMENT 'Contenus',
  `sort` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_table_structure` (
  `id` int(11) NOT NULL,
  `tid` int(11) NOT NULL COMMENT 'Table ID',
  `title` varchar(255) NOT NULL,
  `field_type` varchar(50) NOT NULL COMMENT 'photo,text,date,textarea,textareahtml,link...',
  `field_target` varchar(50) NOT NULL,
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `sort` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `<DB_PREFIX>sb_table`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `<DB_PREFIX>sb_table_datas`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `<DB_PREFIX>sb_table_structure`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `<DB_PREFIX>sb_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `<DB_PREFIX>sb_table_datas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `<DB_PREFIX>sb_table_structure`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
SQL
);
