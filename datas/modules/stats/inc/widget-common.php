<?php
/**
 * Module stats : initialisation commune des widgets du dashboard du CMS
 * (bibliothèques du module, fuseau horaire du site)
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

require_once(__DIR__ . DIRECTORY_SEPARATOR . 'tracker.php');
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'archive.php');

/** Base prête pour un widget (fuseau du site appliqué), ou null */
function stats_widget_db() {
	stats_use_site_timezone();
	return stats_db();
}

/** Période d'un widget (day, week, month) */
function stats_widget_period($key) {
	return stats_period(in_array($key, array('day', 'week', 'month'), true) ? $key : 'day');
}

/** Options de période communes aux widgets */
function stats_widget_period_options() {
	return array('day' => 'Aujourd\'hui', 'week' => 'Cette semaine', 'month' => 'Ce mois-ci');
}
