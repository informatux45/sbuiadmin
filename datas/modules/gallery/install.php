<?php
/**
 * Module gallery : schéma de ses tables, pour l'installer seul (onglet
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
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_gallery` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `css` text DEFAULT NULL,
  `javascript` text DEFAULT NULL,
  `template` text DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT 1 COMMENT '0: inactive, 1: active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_gallery_photos` (
  `id` int(11) NOT NULL,
  `gid` int(11) NOT NULL DEFAULT 0 COMMENT 'Gallery id',
  `title` varchar(255) NOT NULL COMMENT 'Nom de la photo',
  `photo` varchar(255) NOT NULL DEFAULT '' COMMENT 'Nom de l''image physique, ou URL de la video',
  `type` varchar(16) NOT NULL DEFAULT 'photo' COMMENT 'video, photo',
  `active` tinyint(4) NOT NULL DEFAULT 1 COMMENT '0: inactive, 1: active',
  `sort` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `<DB_PREFIX>sb_gallery`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `<DB_PREFIX>sb_gallery_photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gid` (`gid`,`sort`);

ALTER TABLE `<DB_PREFIX>sb_gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `<DB_PREFIX>sb_gallery_photos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
SQL
);
