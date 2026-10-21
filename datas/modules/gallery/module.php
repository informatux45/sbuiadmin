<?php
/**
 * Module gallery : description, version, tables et fichiers
 * (lu par backdoor/inc/sbuiadmin-modules.php)
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

if (!defined('SB_PATH') && !defined('SBUIADMIN_PATH')) die('Are you crazy!');

return array(
	'name'          => 'gallery',
	'title'         => 'Galeries',
	'icon'          => 'picture-o', // Font Awesome (menu, carte Modules)
	'description'   => "Galeries de photos et de vidéos avec visionneuse, à insérer dans un contenu par code court.",
	'version'       => '1.0.1',
	'requires_core' => '4.29',
	'author'        => 'INFORMATUX',
	'routes'        => array('gallery'),
	'page_module'   => false,
	'shortcodes'    => array('sbgallery'),
	'tables'        => array(
		'sb_gallery'        => array('title' => 'Galeries', 'link' => 'index.php?p=gallery'),
		'sb_gallery_photos' => array('title' => 'Photos des galeries', 'link' => 'index.php?p=gallery&a=photo'),
	),
	'files'         => array(
		'backdoor/datas/modules/gallery.php',
		'backdoor/datas/modules/tpls/gallery.tpl',
	),
	'rights'        => array(
		// "photo" en premier : c'est la clé canonique de la ligne (voir
		// sbGetEffectiveModuleKey). "sort" trie les photos d'une galerie,
		// pas les galeries elles-memes - il appartient donc a cette section.
		'submodules' => array('photo' => 'Photos / vidéos', 'photoadd' => 'Photos / vidéos', 'photoedit' => 'Photos / vidéos', 'delphoto' => 'Photos / vidéos', 'sort' => 'Photos / vidéos'),
		'actions'    => array('sort' => 'edit', 'photo' => 'view'),
	),
	'search'        => array('fr' => 'Galerie', 'en' => 'Gallery', 'order' => 70),
);

?>
