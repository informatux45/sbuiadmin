<?php
/**
 * Module sentinelle : carte du dashboard « IP les plus actives »
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');
if (!function_exists('sentinelle_db')) require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'guard.php';

return array(
	'title'  => 'IP les plus actives',
	'icon'   => 'network-wired',
	'link'   => 'index.php?p=sentinelle',
	'format' => 'card',
	'width'  => 6,
	'description' => 'Adresses à l\'origine du plus grand nombre d\'événements de sécurité, avec leur bannissement éventuel.',
	'params' => array(
		'days' => array('label' => 'Période', 'type' => 'select', 'options' => array('1' => '24 heures', '7' => '7 jours', '30' => '30 jours'), 'default' => '7'),
		'nb'   => array('label' => 'Nombre d\'adresses', 'type' => 'number', 'default' => 5, 'min' => 3, 'max' => 20),
	),
	'render' => function (array $p) {
		$dbt = sentinelle_db();
		if (!$dbt) return '<p>Journal indisponible.</p>';
		list($db, $table) = $dbt;
		$since = date('Y-m-d H:i:s', time() - (int) $p['days'] * 86400);
		$res = mysqli_query($db, "SELECT ip, MAX(country) AS country, COUNT(*) AS n, SUM(severity = 'critical') AS crit, MAX(created_at) AS last, SUBSTRING_INDEX(GROUP_CONCAT(type ORDER BY id DESC), ',', 1) AS type FROM `$table` WHERE created_at >= '$since' GROUP BY ip ORDER BY n DESC LIMIT " . (int) $p['nb']);
		$types = sentinelle_types();
		$rows = '';
		while ($res && ($r = mysqli_fetch_assoc($res))) {
			$banned = sentinelle_banned($r['ip']) ? ' <span class="badge danger">bannie</span>' : '';
			$rows .= '<tr><td><a href="index.php?p=sentinelle&amp;ip=' . rawurlencode($r['ip']) . '&amp;days=all">' . htmlspecialchars($r['ip']) . '</a>' . ($r['country'] ? ' <small>' . htmlspecialchars($r['country']) . '</small>' : '') . $banned . '</td>'
				. '<td>' . (int) $r['n'] . ((int) $r['crit'] ? ' <small>(' . (int) $r['crit'] . ' critique' . ((int) $r['crit'] > 1 ? 's' : '') . ')</small>' : '') . '</td>'
				. '<td>' . htmlspecialchars($types[$r['type']] ?? $r['type']) . '</td>'
				. '<td>' . htmlspecialchars(date('d.m H:i', strtotime($r['last']))) . '</td></tr>';
		}
		if ($rows === '') return '<p>Aucun événement sur la période.</p>';
		return '<div style="overflow-x:auto"><table class="data-table"><thead><tr><th>IP</th><th>Événements</th><th>Dernier type</th><th>Dernier</th></tr></thead><tbody>' . $rows . '</tbody></table></div>';
	},
);
