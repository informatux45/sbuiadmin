<?php
/**
 * Module slider : description, version, tables et fichiers
 * (lu par backdoor/inc/sbuiadmin-modules.php)
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

if (!defined('SB_PATH') && !defined('SBUIADMIN_PATH')) die('Are you crazy!');

return array(
	'name'          => 'slider',
	'title'         => 'Sliders',
	'icon'          => 'sliders', // Font Awesome (menu, carte Modules)
	'description'   => "Diaporamas d'images et de vidéos (bxSlider), à insérer par code court ou en tête de page.",
	'version'       => '1.0.1',
	'requires_core' => '4.29',
	'author'        => 'INFORMATUX',
	'routes'        => array(),
	'page_module'   => false,
	'shortcodes'    => array('sbslider'),
	'tables'        => array(
		'sb_slider'        => array('title' => 'Sliders', 'link' => 'index.php?p=slider'),
		'sb_slider_photos' => array('title' => 'Photos des sliders', 'link' => 'index.php?p=slider'),
	),
	'files'         => array(
		'backdoor/datas/modules/slider.php',
		'backdoor/datas/modules/tpls/slider.tpl',
		'backdoor/img/screenshot-slider.jpg',
	),
	'rights'        => array(
		'actions'    => array('sort' => 'edit', 'photo' => 'view'),
	),
);

?>
