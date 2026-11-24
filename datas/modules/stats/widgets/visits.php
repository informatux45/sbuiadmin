<?php
/**
 * Module stats : tuile du dashboard « Visites » (période comparée à la précédente)
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'widget-common.php';

return array(
	'title'  => 'Visites',
	'icon'   => 'users',
	'link'   => 'index.php?p=stats',
	'format' => 'kpi',
	'description' => 'Visites du site sur la période, comparées à la période précédente (module Statistiques).',
	'params' => array(
		'period' => array('label' => 'Période', 'type' => 'select', 'options' => stats_widget_period_options(), 'default' => 'day'),
	),
	'render' => function (array $p) {
		$db = stats_widget_db();
		if (!$db) return array('value' => '—');
		$per = stats_widget_period($p['period']);
		$cur = stats_report($db, 'summary', $per['from'], $per['to'])[''] ?? array('visits' => 0, 'hits' => 0);
		$prev = stats_report($db, 'summary', $per['prev_from'], $per['prev_to'])[''] ?? array('visits' => 0);
		return array(
			'value'   => number_format((int) $cur['visits'], 0, ',', ' '),
			'compare' => number_format((int) $cur['hits'], 0, ',', ' ') . ' page(s) vue(s) · ' . (int) $prev['visits'] . ' sur la période précédente',
			'trend'   => (int) $prev['visits'] > 0 ? round(((int) $cur['visits'] - (int) $prev['visits']) / (int) $prev['visits'] * 100, 1) : null,
		);
	},
);
