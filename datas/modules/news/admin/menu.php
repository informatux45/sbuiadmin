<?php
/**
 * Module news : entrée du menu de l'administration
 * (lue par backdoor/main.php pour un module installé)
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

return array(
	'main'  => SBUIADMIN_MENU_NEWS,
	'group' => 'user', // user OR admin
	'li'    => array(
		array('title' => SBUIADMIN_MENU_NEWS_ALL,        'link' => 'index.php?p=news'),
		array('title' => SBUIADMIN_MENU_NEWS_CATEGORIES, 'link' => 'index.php?p=news&a=category'),
		array('title' => SBUIADMIN_MENU_NEWS_SETTINGS,   'link' => 'index.php?p=news&a=settings'),
	),
);

?>
