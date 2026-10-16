<?php
/**
 * Module boutique : entrée du menu de l'administration
 * (lue par backdoor/main.php pour un module installé)
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

return array(
	'main'  => SBUIADMIN_MENU_BOUTIQUE,
	'group' => 'user', // user OR admin
	'li'    => array(
		array('title' => SBUIADMIN_MENU_BOUTIQUE_PRODUCTS,   'link' => 'index.php?p=boutique'),
		array('title' => SBUIADMIN_MENU_BOUTIQUE_CATEGORIES, 'link' => 'index.php?p=boutique&a=category'),
		array('title' => SBUIADMIN_MENU_BOUTIQUE_SETTINGS,   'link' => 'index.php?p=boutique&a=settings'),
	),
);

?>
