<?php
/**
 * Module store (boutique) : entrée du menu de l'administration
 * (lue par backdoor/main.php pour un module installé)
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

return array(
	'main'  => SBUIADMIN_MENU_STORE,
	'group' => 'user', // user OR admin
	'li'    => array(
		array('title' => SBUIADMIN_MENU_STORE_PRODUCTS,   'link' => 'index.php?p=store'),
		array('title' => SBUIADMIN_MENU_STORE_CATEGORIES, 'link' => 'index.php?p=store&a=category'),
		array('title' => SBUIADMIN_MENU_STORE_SETTINGS,   'link' => 'index.php?p=store&a=settings'),
	),
);

?>
