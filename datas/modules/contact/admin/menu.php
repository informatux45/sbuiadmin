<?php
/**
 * Module contact : entrée du menu de l'administration
 * (lue par backdoor/main.php pour un module installé)
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

return array(
	'main'  => SBUIADMIN_MENU_CONTACT,
	'group' => 'user', // user OR admin
	'li'    => array(
		array('title' => SBUIADMIN_MENU_CONTACT_ALL,      'link' => 'index.php?p=contact'),
		array('title' => SBUIADMIN_MENU_CONTACT_SETTINGS, 'link' => 'index.php?p=contact&a=settings'),
	),
);

?>
