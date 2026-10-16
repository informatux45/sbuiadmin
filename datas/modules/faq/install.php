<?php
/**
 * Module faq : schéma de ses tables, pour l'installer seul (onglet
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
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_faq` (
  `id` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `question` varchar(255) NOT NULL,
  `response` text NOT NULL,
  `sort` int(11) NOT NULL DEFAULT 0,
  `active` tinyint(4) NOT NULL DEFAULT 1 COMMENT '0: inactive, 1: active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_faq_category` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT 1 COMMENT '0: inactive, 1: active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `<DB_PREFIX>sb_faq`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `<DB_PREFIX>sb_faq_category`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `<DB_PREFIX>sb_faq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `<DB_PREFIX>sb_faq_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
SQL
);
