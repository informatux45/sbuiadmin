<?php
/**
 * Module download : description, version, tables et fichiers
 * (lu par backdoor/inc/sbuiadmin-modules.php)
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

if (!defined('SB_PATH') && !defined('SBUIADMIN_PATH')) die('Are you crazy!');

return array(
	'name'          => 'download',
	'title'         => 'Téléchargements',
	'icon'          => 'download', // Font Awesome (menu, carte Modules)
	'description'   => "Boutons de téléchargement de fichiers, avec compteur et lien protégé par clé, à insérer dans un contenu par code court.",
	'version'       => '1.0.0',
	'requires_core' => '4.29',
	'author'        => 'INFORMATUX',
	'routes'        => array('download'),
	'page_module'   => false,
	'shortcodes'    => array('sbdownload'),
	'tables'        => array(
		'sb_download' => array('title' => 'Téléchargements', 'link' => 'index.php?p=download'),
	),
	'files'         => array(
		'backdoor/datas/modules/download.php',
		'backdoor/datas/modules/tpls/download.tpl',
	),
	'search'        => array('fr' => 'Téléchargement', 'en' => 'Download', 'order' => 60),
);

?>
