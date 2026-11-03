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
		$db = function_exists('sbDb') ? sbDb() : null;
		if (!$db) return array('value' => '—');
		$d7  = date('Y-m-d H:i:s', time() - 7 * 86400);
		$row = $db->row('SELECT SUM(CASE WHEN is_read = 0 THEN 1 ELSE 0 END) AS unread, SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) AS week FROM {sb_contact_messages}', array($d7));
		if (!$row) return array('value' => '—');
		$week = (int) $row['week'];
		return array(
			'value'   => (int) $row['unread'],
			'compare' => $week . ($week > 1 ? ' reçus' : ' reçu') . ' sur 7 jours',
		);
	},
);
