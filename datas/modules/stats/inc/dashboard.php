<?php
/**
 * Module stats : tableau de bord à widgets (lot 5), comme celui de Matomo.
 * Chaque utilisateur a sa disposition (sb_stats_dashboard) : nombre de
 * colonnes, widgets de chaque colonne dans l'ordre, widgets réduits.
 * Disposition enregistrée en JSON après contrôle (widgets connus, sans
 * doublon, 40 au plus).
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

if (!defined('SB_PATH') && !defined('SBUIADMIN_PATH')) die('Are you crazy!');

/**
 * Widgets proposés : id => array(titre, type, rapport, droit). Types : kpis,
 * evolution, realtime, visits, report. Droit 'visitors' : visites une par
 * une (ligne « Journal des visites » des droits).
 */
function stats_dashboard_widgets() {
	$out = array(
		'kpis'       => array('Indicateurs clés', 'kpis', '', ''),
		'evolution'  => array('Évolution des visites', 'evolution', '', ''),
		'realtime'   => array('Visiteurs en temps réel', 'realtime', '', 'visitors'),
		'lastvisits' => array('Dernières visites', 'visits', '', 'visitors'),
	);
	foreach (stats_reports() as $key => $title) {
		$key = (string) $key; // '404' : clé entière dans un tableau PHP
		if ($key === 'summary') continue;
		$out[$key] = array($title, 'report', $key, '');
	}
	return $out;
}

/** Disposition par défaut (première visite, ou « Réinitialiser ») */
function stats_dashboard_default() {
	return array(
		'cols'      => 2,
		'columns'   => array(
			array('kpis', 'evolution', 'page', 'entry'),
			array('realtime', 'referer_type', 'device', 'search', 'website'),
		),
		'collapsed' => array(),
	);
}

/**
 * Disposition contrôlée : colonnes 1 à 3, widgets connus (et permis), sans
 * doublon, 40 au plus. Les widgets d'une colonne supprimée passent dans la
 * dernière colonne gardée.
 * @param mixed $layout tableau ou JSON
 * @param array|null $allowed ids permis (null : tous)
 */
function stats_dashboard_normalize($layout, $allowed = null) {
	if (is_string($layout)) $layout = json_decode($layout, true);
	if (!is_array($layout)) return stats_dashboard_default();
	$known = stats_dashboard_widgets();
	// Identifiants toujours en texte : la clé '404' devient l'entier 404 dans un tableau PHP
	if ($allowed !== null) $allowed = array_map('strval', $allowed);
	$cols = (int) ($layout['cols'] ?? 2);
	$cols = max(1, min(3, $cols));
	$seen = array();
	$columns = array_fill(0, $cols, array());
	foreach (array_values((array) ($layout['columns'] ?? array())) as $i => $list) {
		$target = min($i, $cols - 1);
		foreach ((array) $list as $id) {
			if (is_int($id)) $id = (string) $id;
			if (!is_string($id) || !isset($known[$id]) || isset($seen[$id]) || count($seen) >= 40) continue;
			if ($allowed !== null && !in_array($id, $allowed, true)) continue;
			$seen[$id] = true;
			$columns[$target][] = $id;
		}
	}
	$collapsed = array();
	foreach ((array) ($layout['collapsed'] ?? array()) as $id) {
		if (is_string($id) && isset($seen[$id]) && !in_array($id, $collapsed, true)) $collapsed[] = $id;
	}
	return array('cols' => $cols, 'columns' => $columns, 'collapsed' => $collapsed);
}

/** Disposition de l'utilisateur (défaut s'il n'en a pas) */
function stats_dashboard_get(sbdb $db, $userId, $allowed = null) {
	$json = $userId > 0 ? $db->value('SELECT `layout` FROM {sb_stats_dashboard} WHERE `user_id` = ?', array((int) $userId)) : null;
	return stats_dashboard_normalize($json ? (string) $json : stats_dashboard_default(), $allowed);
}

/** Enregistre la disposition (contrôlée) ; true si écrite */
function stats_dashboard_save(sbdb $db, $userId, $layout, $allowed = null) {
	if ((int) $userId <= 0) return false;
	$clean = stats_dashboard_normalize($layout, $allowed);
	return $db->upsert('sb_stats_dashboard', array('user_id' => (int) $userId, 'layout' => json_encode($clean), 'updated_at' => date('Y-m-d H:i:s')), array('user_id'));
}

/** Disposition remise à zéro (ligne supprimée) */
function stats_dashboard_reset(sbdb $db, $userId) {
	return (int) $userId > 0 && $db->delete('sb_stats_dashboard', '`user_id` = ?', array((int) $userId)) !== false;
}
