<?php
/**
 * Module stats : entrée du menu de l'administration
 * (lue par backdoor/main.php pour un module installé)
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

return array(
	'main'  => SBUIADMIN_MENU_STATS,
	'group' => 'user', // user OR admin
	'li'    => array(
		array('title' => SBUIADMIN_MENU_STATS_DASHBOARD, 'link' => 'index.php?p=stats'),
		array('title' => SBUIADMIN_MENU_STATS_OVERVIEW,  'link' => 'index.php?p=stats&a=overview'),
		array('title' => SBUIADMIN_MENU_STATS_REALTIME,  'link' => 'index.php?p=stats&a=realtime'),
		array('title' => SBUIADMIN_MENU_STATS_VISITORS,  'link' => 'index.php?p=stats&a=visitors'),
		array('title' => SBUIADMIN_MENU_STATS_PAGES,     'link' => 'index.php?p=stats&a=pages'),
		array('title' => SBUIADMIN_MENU_STATS_CHANNELS,  'link' => 'index.php?p=stats&a=channels'),
		array('title' => SBUIADMIN_MENU_STATS_SETTINGS,  'link' => 'index.php?p=stats&a=settings'),
	),
);

?>
