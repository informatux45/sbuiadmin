<?php
/**
 * Module gallery : entrée du menu de l'administration
 * (lue par backdoor/main.php pour un module installé)
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

return array(
	'main'  => SBUIADMIN_MENU_GALLERY,
	'group' => 'user', // user OR admin
	'li'    => array(
		array('title' => SBUIADMIN_MENU_GALLERY_ALL,    'link' => 'index.php?p=gallery'),
		array('title' => SBUIADMIN_MENU_GALLERY_PHOTOS, 'link' => 'index.php?p=gallery&a=photo'),
	),
);

?>
