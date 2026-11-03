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

// Bulle rouge : messages non lus, pour qui a le droit de les voir (ligne Contact → Messages)
$sb_contact_unread = 0;
if (function_exists('sbDb') && sbDb() && function_exists('sbHasMenuLinkRight') && sbHasMenuLinkRight('index.php?p=contact&a=messages', 'contact:messages')) {
	$sb_contact_unread = (int) sbDb()->value('SELECT COUNT(*) FROM {sb_contact_messages} WHERE is_read = 0');
}

return array(
	'badge'       => $sb_contact_unread,
	'badge_title' => $sb_contact_unread . ' message' . ($sb_contact_unread > 1 ? 's' : '') . ' non lu' . ($sb_contact_unread > 1 ? 's' : ''),
	'main'  => SBUIADMIN_MENU_CONTACT,
	'group' => 'user', // user OR admin
	'li'    => array(
		array('title' => SBUIADMIN_MENU_CONTACT_ALL,      'link' => 'index.php?p=contact'),
		array('title' => SBUIADMIN_MENU_CONTACT_SETTINGS, 'link' => 'index.php?p=contact&a=settings'),
	),
);

?>
