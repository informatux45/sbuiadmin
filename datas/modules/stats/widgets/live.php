<?php
/**
 * Module stats : tuile du dashboard « Visiteurs en ligne » (30 dernières minutes)
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'widget-common.php';

return array(
	'title'  => 'Visiteurs en ligne',
	'icon'   => 'signal',
	'link'   => 'index.php?p=stats&a=realtime',
	'format' => 'kpi',
	'description' => 'Visites actives sur le site depuis 30 minutes (module Statistiques).',
	'params' => array(),
	'render' => function (array $p) {
		$db = stats_widget_db();
		if (!$db) return array('value' => '—');
		$now = time();
		$v30 = (int) $db->value('SELECT COUNT(*) FROM {sb_stats_visit} WHERE `last_action` >= ?', array(date('Y-m-d H:i:s', $now - 1800)));
		$v24 = (int) $db->value('SELECT COUNT(*) FROM {sb_stats_visit} WHERE `last_action` >= ?', array(date('Y-m-d H:i:s', $now - 86400)));
		return array('value' => (string) $v30, 'compare' => $v24 . ' visite(s) sur 24 heures');
	},
);
