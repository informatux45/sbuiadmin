<?php
/**
 * Module sentinelle : description, version, tables et fichiers
 * (lu par backdoor/inc/sbuiadmin-modules.php)
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

if (!defined('SB_PATH') && !defined('SBUIADMIN_PATH')) die('Are you crazy!');

return array(
	'name'          => 'sentinelle',
	'title'         => 'Sentinelle',
	'icon'          => 'shield-halved', // Font Awesome (menu, carte Modules)
	'description'   => "Sécurité du site : filtres d'attaques (injection SQL, XSS, inclusion, scanners...), journal, IP bannies, alertes par e-mail, IP de confiance, mode observation ou protection.",
	'version'       => '1.2.0',
	'requires_core' => '4.56',
	'author'        => 'INFORMATUX',
	'routes'        => array(),
	'page_module'   => false,
	'shortcodes'    => array(),
	'tables'        => array(
		'sb_sentinelle_log' => array('title' => 'Journal de sécurité', 'link' => 'index.php?p=sentinelle'),
		'sb_sentinelle_ban' => array('title' => 'IP bannies', 'link' => 'index.php?p=sentinelle&a=bans'),
	),
	'files'         => array(),
	'rights'        => array(
		'submodules' => array('settings' => 'Réglages', 'purge' => 'Journal', 'export' => 'Journal', 'bans' => 'IP bannies', 'banadd' => 'IP bannies', 'bandel' => 'IP bannies'),
		'actions'    => array('settings' => 'edit', 'purge' => 'delete', 'export' => 'view', 'bans' => 'view', 'banadd' => 'add', 'bandel' => 'delete'),
	),
	// Bannir en un clic depuis le journal (lien GET) : jeton exigé
	'csrf_get'      => array('banadd'),
);

?>
