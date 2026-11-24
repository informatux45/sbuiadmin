<?php
/**
 * Module stats : carte du dashboard « Évolution des visites » (courbe SVG
 * dessinée côté serveur : ni script ni feuille de style du module)
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'widget-common.php';

return array(
	'title'  => 'Évolution des visites',
	'icon'   => 'chart-line',
	'link'   => 'index.php?p=stats&a=overview',
	'format' => 'card',
	'width'  => 8,
	'description' => 'Visites par jour sur les derniers jours (module Statistiques).',
	'params' => array(
		'days' => array('label' => 'Période', 'type' => 'select', 'options' => array('14' => '14 jours', '30' => '30 jours', '90' => '90 jours'), 'default' => '30'),
	),
	'render' => function (array $p) {
		$db = stats_widget_db();
		if (!$db) return '<p>Statistiques indisponibles.</p>';
		$days = max(7, min(90, (int) $p['days']));
		$to = date('Y-m-d');
		$from = date('Y-m-d', strtotime('-' . ($days - 1) . ' days'));
		$series = stats_series($db, $from, $to);
		$vals = array_map(function ($m) { return (int) $m['visits']; }, array_values($series));
		$total = array_sum($vals);
		$max = max(1, max($vals));
		$w = 600; $h = 140; $pad = 6;
		$step = count($vals) > 1 ? ($w - 2 * $pad) / (count($vals) - 1) : 0;
		$pts = array();
		foreach ($vals as $i => $v) $pts[] = round($pad + $i * $step, 1) . ',' . round($h - $pad - ($v / $max) * ($h - 2 * $pad), 1);
		$area = $pad . ',' . ($h - $pad) . ' ' . implode(' ', $pts) . ' ' . round($pad + (count($vals) - 1) * $step, 1) . ',' . ($h - $pad);
		$label = htmlspecialchars(number_format($total, 0, ',', ' ') . ' visite(s) en ' . $days . ' jours, maximum ' . $max . ' par jour');
		return '<p style="margin:0 0 8px;color:var(--t-muted);font-size:13px">' . $label . '</p>'
			. '<svg viewBox="0 0 ' . $w . ' ' . $h . '" width="100%" height="140" preserveAspectRatio="none" role="img" aria-label="' . $label . '">'
			. '<polygon points="' . $area . '" fill="var(--primary)" opacity="0.12"/>'
			. '<polyline points="' . implode(' ', $pts) . '" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linejoin="round" stroke-linecap="round" vector-effect="non-scaling-stroke"/>'
			. '</svg>'
			. '<p style="display:flex;justify-content:space-between;margin:4px 0 0;color:var(--t-light);font-size:11px"><span>' . htmlspecialchars(date('d/m', strtotime($from))) . '</span><span>' . htmlspecialchars(date('d/m', strtotime($to))) . '</span></p>';
	},
);
