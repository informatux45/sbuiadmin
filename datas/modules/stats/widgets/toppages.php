<?php
/**
 * Module stats : carte du dashboard « Pages les plus vues »
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'widget-common.php';

return array(
	'title'  => 'Pages les plus vues',
	'icon'   => 'file-lines',
	'link'   => 'index.php?p=stats&a=pages',
	'format' => 'card',
	'width'  => 6,
	'description' => 'Pages du site les plus vues sur la période (module Statistiques).',
	'params' => array(
		'period' => array('label' => 'Période', 'type' => 'select', 'options' => stats_widget_period_options(), 'default' => 'week'),
		'nb'     => array('label' => 'Nombre de pages', 'type' => 'number', 'default' => 5, 'min' => 3, 'max' => 20),
	),
	'render' => function (array $p) {
		$db = stats_widget_db();
		if (!$db) return '<p>Statistiques indisponibles.</p>';
		$per = stats_widget_period($p['period']);
		$rows = '';
		foreach (stats_report($db, 'title', $per['from'], $per['to'], 'hits', max(3, min(20, (int) $p['nb']))) as $title => $m) {
			$rows .= '<tr><td>' . htmlspecialchars($title === '(sans titre)' ? 'Sans titre' : (string) $title) . '</td><td style="text-align:right">' . (int) $m['hits'] . '</td></tr>';
		}
		if ($rows === '') return '<p>Aucune page vue sur la période.</p>';
		return '<div style="overflow-x:auto"><table class="data-table"><thead><tr><th>Page</th><th style="text-align:right">Vues</th></tr></thead><tbody>' . $rows . '</tbody></table></div>';
	},
);
