<?php
/**
 * Module table : description, version, tables et fichiers
 * (lu par backdoor/inc/sbuiadmin-modules.php)
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

if (!defined('SB_PATH') && !defined('SBUIADMIN_PATH')) die('Are you crazy!');

return array(
	'name'          => 'table',
	'title'         => 'Tableaux',
	'icon'          => 'table', // Font Awesome (menu, carte Modules)
	'description'   => "Tableaux de données dynamiques et adaptatifs (colonnes et lignes éditables), à insérer dans une page par code court.",
	'version'       => '1.0.2',
	'requires_core' => '4.29',
	'author'        => 'INFORMATUX',
	'routes'        => array(),
	'page_module'   => false,
	'shortcodes'    => array('sbtable'),
	'tables'        => array(
		'sb_table'           => array('title' => 'Tableaux', 'link' => 'index.php?p=table'),
		'sb_table_datas'     => array('title' => 'Données des tableaux', 'link' => 'index.php?p=table'),
		'sb_table_structure' => array('title' => 'Structure des tableaux', 'link' => 'index.php?p=table'),
	),
	'files'         => array(
		'backdoor/datas/modules/table.php',
		'backdoor/datas/modules/tpls/table.tpl',
		'backdoor/img/modules/sb_table_full.png',
		'backdoor/img/modules/sb_table_option1.png',
		'backdoor/img/modules/sb_table_option2.png',
	),
	'rights'        => array(
		'actions'    => array('sortstructure' => 'edit', 'sortdatas' => 'edit'),
	),
	'search'        => array('fr' => 'Tableau', 'en' => 'Table', 'order' => 40),
);

?>
