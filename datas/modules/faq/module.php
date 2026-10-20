<?php
/**
 * Module faq : description, version, tables et fichiers
 * (lu par backdoor/inc/sbuiadmin-modules.php)
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

if (!defined('SB_PATH') && !defined('SBUIADMIN_PATH')) die('Are you crazy!');

return array(
	'name'          => 'faq',
	'title'         => 'FAQ',
	'icon'          => 'question-circle', // Font Awesome (menu, carte Modules)
	'description'   => "Foire aux questions : questions et réponses classées par catégories, à insérer dans une page par code court.",
	'version'       => '1.0.1',
	'requires_core' => '4.29',
	'author'        => 'INFORMATUX',
	'routes'        => array(),
	'page_module'   => false,
	'shortcodes'    => array('sbfaq'),
	'tables'        => array(
		'sb_faq'          => array('title' => 'FAQ', 'link' => 'index.php?p=faq'),
		'sb_faq_category' => array('title' => 'Catégories FAQ', 'link' => 'index.php?p=faq&a=category'),
	),
	'files'         => array(
		'backdoor/datas/modules/faq.php',
		'backdoor/datas/modules/tpls/faq.tpl',
	),
	'rights'        => array(
		'submodules' => array('category' => 'Catégories', 'categorydel' => 'Catégories', 'categoryadd' => 'Catégories', 'categoryedit' => 'Catégories'),
		'actions'    => array('sort' => 'edit', 'category' => 'view'),
	),
	'pagebuilder'   => array('faq.response' => 'FAQ – Réponse'),
);

?>
