<?php
/**
 * Module sentinelle : tuile du dashboard « Attaques »
 * (événements de la période, comparés à la période précédente)
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');
if (!function_exists('sentinelle_db')) require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'guard.php';

return array(
	'title'  => 'Attaques',
	'icon'   => 'shield-halved',
	'link'   => 'index.php?p=sentinelle',
	'format' => 'kpi',
	'description' => 'Événements de sécurité de Sentinelle sur la période, comparés à la période précédente.',
	'params' => array(
		'hours' => array('label' => 'Période', 'type' => 'select', 'options' => array('24' => '24 heures', '168' => '7 jours', '720' => '30 jours'), 'default' => '24'),
		'level' => array('label' => 'Gravité', 'type' => 'select', 'options' => array('info' => 'Tous les événements', 'warning' => 'Alertes et critiques', 'critical' => 'Critiques seulement'), 'default' => 'info'),
	),
	'render' => function (array $p) {
		$dbt = sentinelle_db();
		if (!$dbt) return array('value' => '—');
		list($db, $table) = $dbt;
		$h = (int) $p['hours'];
		$levels = array('info' => "('info','warning','critical')", 'warning' => "('warning','critical')", 'critical' => "('critical')");
		$in = $levels[$p['level']] ?? $levels['info'];
		$now = time();
		$d1 = date('Y-m-d H:i:s', $now - $h * 3600);
		$d2 = date('Y-m-d H:i:s', $now - 2 * $h * 3600);
		$res = mysqli_query($db, "SELECT SUM(created_at >= '$d1') AS cur, SUM(created_at < '$d1') AS prev, COUNT(DISTINCT IF(created_at >= '$d1', ip, NULL)) AS ips FROM `$table` WHERE created_at >= '$d2' AND severity IN $in");
		$r = $res ? mysqli_fetch_assoc($res) : null;
		$cur = (int) ($r['cur'] ?? 0); $prev = (int) ($r['prev'] ?? 0);
		return array(
			'value'      => number_format($cur, 0, ',', ' '),
			'compare'    => $prev . ' sur la période précédente · ' . (int) ($r['ips'] ?? 0) . ' IP',
			'trend'      => $prev > 0 ? round(($cur - $prev) / $prev * 100, 1) : null,
			'trend_good' => 'down',
		);
	},
);
