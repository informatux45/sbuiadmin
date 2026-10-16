<?php
/**
 * Module tabbs : description, version, tables et fichiers
 * (lu par backdoor/inc/sbuiadmin-modules.php)
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

if (!defined('SB_PATH') && !defined('SBUIADMIN_PATH')) die('Are you crazy!');

return array(
	'name'          => 'tabbs',
	'title'         => 'Tabbs',
	'icon'          => 'list-alt', // Font Awesome (menu, carte Modules)
	'description'   => "Contenus présentés en onglets adaptatifs (accordéon sur mobile), à insérer dans une page par code court.",
	'version'       => '1.0.1',
	'requires_core' => '4.29',
	'author'        => 'INFORMATUX',
	'routes'        => array(),
	'page_module'   => false,
	'shortcodes'    => array('sbtabbs'),
	'tables'        => array(
		'sb_tabbs'          => array('title' => 'Tabbs', 'link' => 'index.php?p=tabbs'),
		'sb_tabbs_tab'      => array('title' => 'Onglets', 'link' => 'index.php?p=tabbs&a=alltabs'),
	),
	'files'         => array(
		'backdoor/datas/modules/tabbs.php',
		'backdoor/datas/modules/tpls/tabbs.tpl',
		'backdoor/img/modules/tabbs_example_1.png',
	),
	'rights'        => array(
		'submodules' => array('deltab' => 'Onglets', 'alltabs' => 'Onglets', 'tabadd' => 'Onglets', 'tabedit' => 'Onglets'),
		'actions'    => array('sort' => 'edit', 'alltabs' => 'view'),
	),
	'pagebuilder'   => array('tabbs.content_fr' => 'Tabbs – Contenu'),
	'search'        => array('fr' => 'Onglets', 'en' => 'Tabs', 'order' => 50),
);

?>
