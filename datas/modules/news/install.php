<?php
/**
 * Module news : schéma de ses tables, pour l'installer seul (onglet
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
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_news` (
  `id` bigint(20) NOT NULL,
  `catid` varchar(50) NOT NULL COMMENT 'Categories',
  `title` text NOT NULL,
  `subtitle` text NOT NULL,
  `desc_short` text NOT NULL,
  `desc_full` text NOT NULL,
  `image` varchar(200) DEFAULT NULL,
  `date` varchar(10) NOT NULL,
  `viewed` int(11) NOT NULL DEFAULT 0 COMMENT 'Compteur de lectures',
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_news_category` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `subtitle` text,
  `tpl_list` text,
  `tpl_single` text,
  `module_show` varchar(50) DEFAULT NULL COMMENT 'normal,masonry,...',
  `module_show_masonry` int(11) DEFAULT NULL COMMENT 'columns width (pixels)',
  `photo` varchar(255) DEFAULT NULL,
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `sort` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_news_settings` (
  `id` int(11) NOT NULL,
  `item_per_page` int(11) NOT NULL COMMENT 'article par page (categorie)',
  `module_start` tinyint(4) NOT NULL COMMENT '0: liste des categories, 1: categorie specifique',
  `catid` text NOT NULL COMMENT 'Demarrage par ces categories',
  `catid_module_show` int(11) DEFAULT NULL COMMENT 'Catégorie affichage principal',
  `breadcrumb` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `title_h1` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `title_h2` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `theme_view_cat` varchar(100) NOT NULL DEFAULT 'index' COMMENT 'Theme view du module LISTE DES CATEGORIES',
  `theme_view_list` varchar(100) NOT NULL DEFAULT 'index' COMMENT 'Theme view du module LISTE DES ARTICLES',
  `theme_view_single` varchar(100) NOT NULL DEFAULT 'index' COMMENT 'Theme view du module ARTICLE',
  `other_news` tinyint(4) NOT NULL,
  `other_news_per_page` int(11) NOT NULL,
  `other_news_title` varchar(255) NOT NULL DEFAULT 'Autres articles',
  `other_news_type` varchar(20) NOT NULL COMMENT 'random, latest, first',
  `news_next_prev` varchar(20) NOT NULL DEFAULT 'arrow' COMMENT 'arrow, title',
  `comments` varchar(20) DEFAULT NULL,
  `comments_user` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `<DB_PREFIX>sb_news`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `<DB_PREFIX>sb_news_category`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `<DB_PREFIX>sb_news_settings`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `<DB_PREFIX>sb_news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `<DB_PREFIX>sb_news_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `<DB_PREFIX>sb_news_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
SQL
);
