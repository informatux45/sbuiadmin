<?php
/**
 * Module news : description, version, tables et fichiers
 * (lu par backdoor/inc/sbuiadmin-modules.php)
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

if (!defined('SB_PATH') && !defined('SBUIADMIN_PATH')) die('Are you crazy!');

return array(
	'name'          => 'news',
	'title'         => 'Actualités',
	'icon'          => 'rss', // Font Awesome (menu, carte Modules)
	'description'   => "Articles classés par catégories, avec page d'actualités, gabarits de liste et d'article, et blocs « derniers articles » par code court.",
	'version'       => '1.0.2',
	'requires_core' => '4.29',
	'author'        => 'INFORMATUX',
	'routes'        => array('news'),
	'page_module'   => true,
	'shortcodes'    => array('sbnews_item', 'sbnews_latest', 'sbnews_blocks_recent'),
	'tables'        => array(
		'sb_news'          => array('title' => 'Actualités', 'link' => 'index.php?p=news'),
		'sb_news_category' => array('title' => 'Catégories actualités', 'link' => 'index.php?p=news&a=category'),
		'sb_news_settings' => array('title' => 'Paramètres actualités', 'link' => 'index.php?p=news&a=settings'),
	),
	'files'         => array(
		'backdoor/datas/modules/news.php',
		'backdoor/datas/modules/tpls/news.tpl',
		'backdoor/img/news-template.jpg',
		'backdoor/img/modules/sbnews_shortcodes_1.png',
	),
	'rights'        => array(
		'submodules' => array('category' => 'Catégories', 'categorydel' => 'Catégories', 'categoryadd' => 'Catégories', 'categoryedit' => 'Catégories', 'settings' => 'Paramètres', 'settingscategory' => 'Paramètres'),
		'actions'    => array('settings' => 'edit', 'settingscategory' => 'edit', 'tpl_list' => 'edit', 'tpl_single' => 'edit', 'sort' => 'edit'),
	),
	'pagebuilder'   => array('news.desc_full_fr' => 'Actualités – Article'),
	'search'        => array('fr' => 'Actualité', 'en' => 'News', 'order' => 20),
);

?>
