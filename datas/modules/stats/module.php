<?php
/**
 * Module stats : description, version, tables et fichiers
 * (lu par backdoor/inc/sbuiadmin-modules.php)
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

if (!defined('SB_PATH') && !defined('SBUIADMIN_PATH')) die('Are you crazy!');

return array(
	'name'          => 'stats',
	'title'         => 'Statistiques',
	'icon'          => 'chart-line', // Font Awesome (menu, carte Modules)
	'description'   => "Mesure d'audience sans cookie ni service externe : visites, pages, sources de trafic, appareils, pays, temps réel, tableau de bord à widgets.",
	'version'       => '1.0.0',
	'requires_core' => '4.90',
	'author'        => 'INFORMATUX',
	'routes'        => array(),
	'page_module'   => false,
	'shortcodes'    => array(),
	'tables'        => array(
		'sb_stats_visit'   => array('title' => 'Statistiques : visites', 'link' => 'index.php?p=stats'),
		'sb_stats_action'  => array('title' => 'Statistiques : pages vues', 'link' => 'index.php?p=stats'),
		'sb_stats_daily'   => array('title' => 'Statistiques : rapports par jour', 'link' => 'index.php?p=stats'),
		'sb_stats_dashboard' => array('title' => 'Statistiques : tableaux de bord', 'link' => 'index.php?p=stats'),
		// Réglages et sels de l'empreinte : jamais affichés par un widget « Table SQL »
		'sb_stats_setting' => array('title' => 'Statistiques : réglages', 'link' => 'index.php?p=stats&a=settings', 'dashboard' => false),
	),
	'files'         => array(),
	// Fil d'Ariane de l'administration (a= => libellé)
	'crumbs'        => array(
		'dashboard' => 'Tableau de bord', 'dashsave' => 'Tableau de bord', 'overview' => 'Vue d\'ensemble', 'realtime' => 'Temps réel', 'visitors' => 'Journal des visites', 'devices' => 'Appareils',
		'software' => 'Logiciels', 'locations' => 'Lieux et langues', 'times' => 'Heures et jours', 'engagement' => 'Fidélité',
		'pages' => 'Pages', 'entry' => 'Pages d\'entrée', 'exit' => 'Pages de sortie', 'titles' => 'Titres des pages',
		'outlinks' => 'Liens sortants', 'downloads' => 'Téléchargements', 'notfound' => 'Pages introuvables',
		'channels' => 'Acquisition', 'search' => 'Moteurs et mots-clés', 'websites' => 'Sites web', 'social' => 'Réseaux sociaux',
		'campaigns' => 'Campagnes', 'settings' => 'Réglages', 'archive' => 'Réglages', 'purgedel' => 'Réglages', 'geoip' => 'Réglages',
	),
	// Droits : les rapports (agrégés) d'un côté ; le journal des visites et
	// le temps réel (visites une par une) et les réglages à part
	'rights'        => array(
		'submodules' => array('visitors' => 'Journal des visites', 'realtime' => 'Journal des visites',
			'settings' => 'Réglages', 'archive' => 'Réglages', 'purgedel' => 'Réglages', 'geoip' => 'Réglages'),
		'actions'    => array('settings' => 'edit', 'archive' => 'edit', 'geoip' => 'edit', 'purgedel' => 'delete', 'export' => 'view', 'visitors' => 'view', 'realtime' => 'view'),
	),
);

?>
