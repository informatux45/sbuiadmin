<?php
/**
 * Module contact : tuile du dashboard « Messages non lus »
 * (messages reçus par tous les formulaires, table sb_contact_messages)
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

return array(
	'title'  => 'Messages non lus',
	'icon'   => 'envelope',
	'link'   => 'index.php?p=contact',
	'format' => 'kpi',
	'description' => 'Messages de contact pas encore lus, avec le nombre reçu sur les 7 derniers jours.',
	'render' => function (array $p) {
		$sbsql = $GLOBALS['sbsql'] ?? null;
		if (!$sbsql) return array('value' => '—');
		$d7  = date('Y-m-d H:i:s', time() - 7 * 86400);
		$row = $sbsql->assoc($sbsql->query('SELECT SUM(is_read = 0) AS unread, SUM(created_at >= \'' . $d7 . '\') AS week FROM ' . _AM_DB_PREFIX . 'sb_contact_messages'));
		if (!$row) return array('value' => '—');
		$week = (int) $row['week'];
		return array(
			'value'   => (int) $row['unread'],
			'compare' => $week . ($week > 1 ? ' reçus' : ' reçu') . ' sur 7 jours',
		);
	},
);
