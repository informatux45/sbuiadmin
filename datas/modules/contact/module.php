<?php
/**
 * Module contact : description, version, tables et fichiers
 * (lu par backdoor/inc/sbuiadmin-modules.php)
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

if (!defined('SB_PATH') && !defined('SBUIADMIN_PATH')) die('Are you crazy!');

return array(
	'name'          => 'contact',
	'title'         => 'Contact',
	'icon'          => 'envelope', // Font Awesome (menu, carte Modules)
	'description'   => "Formulaires de contact personnalisables (champs, destinataires, protection ALTCHA), à insérer dans une page par code court.",
	'version'       => '1.0.0',
	'requires_core' => '4.29',
	'author'        => 'INFORMATUX',
	'routes'        => array(),
	'page_module'   => true,
	'shortcodes'    => array('sbcontact', 'sbcontactajax'),
	'tables'        => array(
		'sb_contact' => array('title' => 'Formulaires de contact', 'link' => 'index.php?p=contact'),
	),
	'files'         => array(
		'backdoor/datas/modules/contact.php',
		'backdoor/datas/modules/tpls/contact.tpl',
		'backdoor/img/contact.jpg',
	),
	'rights'        => array(
		'submodules' => array('settings' => 'Paramètres'),
		'actions'    => array('settings' => 'edit'),
	),
	'search'        => array('fr' => 'Formulaire', 'en' => 'Form', 'order' => 30),
);

?>
