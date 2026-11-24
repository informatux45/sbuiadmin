<?php
/**
 * Module stats : carte du dashboard « Sources de trafic » (canaux)
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'widget-common.php';

return array(
	'title'  => 'Sources de trafic',
	'icon'   => 'route',
	'link'   => 'index.php?p=stats&a=channels',
	'format' => 'card',
	'width'  => 4,
	'description' => 'Part des visites par canal : accès direct, moteurs, sites, réseaux sociaux, campagnes (module Statistiques).',
	'params' => array(
		'period' => array('label' => 'Période', 'type' => 'select', 'options' => stats_widget_period_options(), 'default' => 'week'),
	),
	'render' => function (array $p) {
		$db = stats_widget_db();
		if (!$db) return '<p>Statistiques indisponibles.</p>';
		$per = stats_widget_period($p['period']);
		$labels = array('direct' => 'Accès direct', 'search' => 'Moteurs de recherche', 'website' => 'Sites web', 'social' => 'Réseaux sociaux', 'campaign' => 'Campagnes');
		$rows = stats_report($db, 'referer_type', $per['from'], $per['to']);
		$total = 0;
		foreach ($rows as $m) $total += (int) $m['visits'];
		if ($total === 0) return '<p>Aucune visite sur la période.</p>';
		$out = '';
		foreach ($rows as $type => $m) {
			$pct = round(100 * (int) $m['visits'] / $total);
			$out .= '<div style="margin:0 0 10px"><div style="display:flex;justify-content:space-between;font-size:13px"><span>' . htmlspecialchars($labels[$type] ?? (string) $type) . '</span><span>' . (int) $m['visits'] . ' · ' . $pct . ' %</span></div>'
				. '<div style="height:4px;background:var(--border-soft);border-radius:4px;margin-top:4px;overflow:hidden"><span style="display:block;height:100%;width:' . $pct . '%;background:var(--primary)"></span></div></div>';
		}
		return $out;
	},
);
