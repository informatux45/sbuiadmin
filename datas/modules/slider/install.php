<?php
/**
 * Module slider : schéma de ses tables, pour l'installer seul (onglet
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
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_slider` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `jquery` tinyint(4) NOT NULL COMMENT 'Chargement de jquery',
  `responsive` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `auto` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `pause` int(11) NOT NULL,
  `speed` int(11) NOT NULL,
  `randomstart` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `mode` varchar(50) NOT NULL,
  `preloadimages` varchar(50) NOT NULL,
  `controls` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `autocontrols` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `autohover` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `captions` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `adaptiveheight` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `adaptiveheightspeed` int(11) NOT NULL,
  `slidemargin` int(11) NOT NULL,
  `video` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `usecss` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `pager` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `pagertype` varchar(50) NOT NULL COMMENT 'full, short',
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_slider_photos` (
  `id` int(11) NOT NULL,
  `sid` int(11) NOT NULL COMMENT 'Slider id',
  `title` varchar(255) NOT NULL COMMENT 'Nom de la photo',
  `photo` varchar(255) NOT NULL COMMENT 'Nom de l''image physique',
  `type` varchar(10) NOT NULL COMMENT 'video, photo',
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `sort` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `<DB_PREFIX>sb_slider`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `<DB_PREFIX>sb_slider_photos`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `<DB_PREFIX>sb_slider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `<DB_PREFIX>sb_slider_photos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
SQL
);
