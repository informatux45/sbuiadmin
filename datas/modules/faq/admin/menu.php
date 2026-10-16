<?php
/**
 * Module faq : entrée du menu de l'administration
 * (lue par backdoor/main.php pour un module installé)
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

return array(
	'main'  => SBUIADMIN_MENU_FAQ,
	'group' => 'user', // user OR admin
	'li'    => array(
		array('title' => SBUIADMIN_MENU_FAQ_ALL,        'link' => 'index.php?p=faq'),
		array('title' => SBUIADMIN_MENU_FAQ_CATEGORIES, 'link' => 'index.php?p=faq&a=category'),
	),
);

?>
