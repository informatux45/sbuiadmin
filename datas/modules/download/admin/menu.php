<?php
/**
 * Module download : entrée du menu de l'administration
 * (lue par backdoor/main.php pour un module installé)
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

return array(
	'main'  => SBUIADMIN_MENU_DOWNLOAD,
	'group' => 'user', // user OR admin
);

?>
