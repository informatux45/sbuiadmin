<?php
/**
 * Module tabbs : entrée du menu de l'administration
 * (lue par backdoor/main.php pour un module installé)
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

return array(
	'main'  => SBUIADMIN_MENU_TABBS,
	'group' => 'user', // user OR admin
	'li'    => array(
		array('title' => SBUIADMIN_MENU_TABBS_ALL,     'link' => 'index.php?p=tabbs'),
		array('title' => SBUIADMIN_MENU_TABBS_ONGLETS, 'link' => 'index.php?p=tabbs&a=alltabs'),
	),
);

?>
