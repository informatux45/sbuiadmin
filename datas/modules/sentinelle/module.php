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
	'description'   => "Sécurité du site : filtres d'attaques (injection SQL, XSS, inclusion, scanners...), journal des attaques et des accès refusés, IP de confiance, mode observation ou protection.",
	'version'       => '1.1.0',
	'requires_core' => '4.55',
	'author'        => 'INFORMATUX',
	'routes'        => array(),
	'page_module'   => false,
	'shortcodes'    => array(),
	'tables'        => array(
		'sb_sentinelle_log' => array('title' => 'Journal de sécurité', 'link' => 'index.php?p=sentinelle'),
	),
	'files'         => array(),
	'rights'        => array(
		'submodules' => array('settings' => 'Réglages', 'purge' => 'Journal'),
		'actions'    => array('settings' => 'edit', 'purge' => 'delete'),
	),
);

?>
