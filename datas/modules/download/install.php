<?php
/**
 * Module download : schéma de ses tables, pour l'installer seul (onglet
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
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_download` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `description` text DEFAULT NULL,
  `filename` varchar(255) NOT NULL DEFAULT '' COMMENT 'Nom du fichier',
  `size` varchar(32) DEFAULT NULL,
  `downloaded` int(11) NOT NULL DEFAULT 0 COMMENT 'Nombre de telechargements',
  `randkey` varchar(80) NOT NULL DEFAULT '' COMMENT 'sbGenerateRandKey() renvoie 65 caracteres',
  `active` tinyint(4) NOT NULL DEFAULT 1 COMMENT '0: inactive, 1: active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `<DB_PREFIX>sb_download`
  ADD PRIMARY KEY (`id`),
  ADD KEY `randkey` (`randkey`);

ALTER TABLE `<DB_PREFIX>sb_download`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
SQL
);
