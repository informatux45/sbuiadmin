<?php
/**
 * Module sentinelle : entrée du menu de l'administration
 * (lue par backdoor/main.php pour un module installé)
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

return array(
	'main'  => SBUIADMIN_MENU_SENTINELLE,
	'group' => 'admin', // user OR admin
	'li'    => array(
		array('title' => SBUIADMIN_MENU_SENTINELLE_LOG,      'link' => 'index.php?p=sentinelle'),
		array('title' => SBUIADMIN_MENU_SENTINELLE_SETTINGS, 'link' => 'index.php?p=sentinelle&a=settings'),
	),
);

?>
