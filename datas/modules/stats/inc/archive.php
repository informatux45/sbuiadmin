<?php
/**
 * Module stats : rapports calculés par jour (lot 3), comme l'archivage de
 * Matomo. Chaque jour terminé est résumé une fois dans sb_stats_daily ;
 * les rapports lisent ces résumés, et calculent à la volée les jours pas
 * encore archivés (aujourd'hui). Les données brutes (journal des visites)
 * sont ensuite purgées au-delà de raw_retention jours.
 *
 * Calcul en PHP (pas de fonctions de date SQL) : identique en MySQL et
 * SQLite.
 *
 * Les visiteurs uniques ne s'additionnent pas d'un jour à l'autre :
 * l'empreinte change chaque jour (aucun suivi d'un jour sur l'autre, par
 * choix). Sur une période, « visiteurs » est la somme des visiteurs du jour.
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

if (!defined('SB_PATH') && !defined('SBUIADMIN_PATH')) die('Are you crazy!');

/** Colonnes de mesure d'une ligne de rapport */
function stats_metric_names() {
	return array('visits', 'visitors', 'actions', 'hits', 'duration', 'bounces', 'entries', 'exits');
}

/**
 * Rapports connus : clé => libellé. 'summary' n'a qu'une ligne (libellé '').
 * Les rapports par visite portent visits, actions, hits (pages vues),
 * duration et bounces ; les rapports d'actions portent hits, visits
 * (visites distinctes) et duration (temps passé).
 */
function stats_reports() {
	return array(
		'summary'         => 'Vue d\'ensemble',
		'hour'            => 'Heure locale',
		'weekday'         => 'Jour de la semaine',
		'referer_type'    => 'Canaux',
		'search'          => 'Moteurs de recherche',
		'keyword'         => 'Mots-clés',
		'website'         => 'Sites référents',
		'social'          => 'Réseaux sociaux',
		'campaign'        => 'Campagnes',
		'campaign_source' => 'Sources de campagne',
		'browser'         => 'Navigateurs',
		'browser_version' => 'Versions de navigateurs',
		'os'              => 'Systèmes',
		'os_version'      => 'Versions de systèmes',
		'device'          => 'Appareils',
		'resolution'      => 'Résolutions d\'écran',
		'country'         => 'Pays',
		'language'        => 'Langues',
		'visit_pages'     => 'Pages par visite',
		'visit_duration'  => 'Durée des visites',
		'entry'           => 'Pages d\'entrée',
		'exit'            => 'Pages de sortie',
		'page'            => 'Pages',
		'title'           => 'Titres des pages',
		'404'             => 'Pages introuvables',
		'outlink'         => 'Liens sortants',
		'download'        => 'Téléchargements',
	);
}

/** Tranche de pages par visite (comme Matomo) */
function stats_pages_bucket($n) {
	$n = (int) $n;
	if ($n <= 5) return (string) max(0, $n);
	foreach (array(10 => '6-10', 15 => '11-15', 20 => '16-20', 30 => '21-30', 50 => '31-50') as $max => $label) if ($n <= $max) return $label;
	return '51+';
}

/** Tranche de durée de visite, en secondes (comme Matomo) */
function stats_duration_bucket($s) {
	$s = (int) $s;
	$steps = array(10 => '0-10 s', 30 => '11-30 s', 60 => '31-60 s', 120 => '1-2 min', 240 => '2-4 min', 420 => '4-7 min', 600 => '7-10 min', 900 => '10-15 min', 1800 => '15-30 min');
	foreach ($steps as $max => $label) if ($s <= $max) return $label;
	return '30 min et +';
}

/** Ajoute des mesures à une ligne de rapport */
function stats_add(array &$rep, $report, $label, array $m) {
	$label = mb_substr((string) $label, 0, 255, 'UTF-8');
	if (!isset($rep[$report][$label])) $rep[$report][$label] = array_fill_keys(stats_metric_names(), 0);
	foreach ($m as $k => $v) $rep[$report][$label][$k] += (int) $v;
}

/**
 * Calcule tous les rapports de la période [$from, $to[ (dates et heures
 * 'Y-m-d H:i:s') depuis les données brutes. Les visites comptent pour le
 * jour de leur début ; les actions pour le jour où elles ont lieu.
 * @return array rapport => libellé => mesures (stats_metric_names())
 */
function stats_aggregate(sbdb $db, $from, $to) {
	$rep = array();
	$visits = $db->all('SELECT `id`, `visitor`, `first_action`, `actions`, `pageviews`, `duration`, `entry_url`, `exit_url`, `referer_type`, `referer_name`,
		`referer_keyword`, `campaign_source`, `campaign_medium`, `country`, `language`, `browser`, `browser_version`, `os`, `os_version`, `device`, `resolution`
		FROM {sb_stats_visit} WHERE `first_action` >= ? AND `first_action` < ?', array($from, $to)) ?: array();

	$uniq = array();
	foreach ($visits as $v) {
		$bounce = (int) $v['actions'] <= 1 ? 1 : 0;
		$m = array('visits' => 1, 'actions' => $v['actions'], 'hits' => $v['pageviews'], 'duration' => $v['duration'], 'bounces' => $bounce);
		stats_add($rep, 'summary', '', $m);
		$uniq[$v['visitor']] = true;
		$ts = strtotime($v['first_action']);
		stats_add($rep, 'hour', date('H', $ts), $m);
		stats_add($rep, 'weekday', date('N', $ts), $m);
		$type = in_array($v['referer_type'], array('direct', 'search', 'website', 'social', 'campaign'), true) ? $v['referer_type'] : 'direct';
		stats_add($rep, 'referer_type', $type, $m);
		if ($type !== 'direct' && $v['referer_name'] !== '') stats_add($rep, $type, $v['referer_name'], $m);
		if (in_array($type, array('search', 'campaign'), true) && $v['referer_keyword'] !== '') stats_add($rep, 'keyword', $v['referer_keyword'], $m);
		if ($type === 'campaign' && $v['campaign_source'] !== '') stats_add($rep, 'campaign_source', $v['campaign_source'] . ($v['campaign_medium'] !== '' ? ' / ' . $v['campaign_medium'] : ''), $m);
		stats_add($rep, 'browser', $v['browser'] !== '' ? $v['browser'] : 'Autre', $m);
		stats_add($rep, 'browser_version', trim($v['browser'] . ' ' . $v['browser_version']) ?: 'Autre', $m);
		stats_add($rep, 'os', $v['os'] !== '' ? $v['os'] : 'Autre', $m);
		stats_add($rep, 'os_version', trim($v['os'] . ' ' . $v['os_version']) ?: 'Autre', $m);
		stats_add($rep, 'device', $v['device'] !== '' ? $v['device'] : 'desktop', $m);
		if ($v['resolution'] !== '') stats_add($rep, 'resolution', $v['resolution'], $m);
		stats_add($rep, 'country', $v['country'] !== '' ? strtolower($v['country']) : 'xx', $m);
		stats_add($rep, 'language', $v['language'] !== '' ? $v['language'] : 'xx', $m);
		stats_add($rep, 'visit_pages', stats_pages_bucket($v['pageviews']), array('visits' => 1));
		stats_add($rep, 'visit_duration', stats_duration_bucket($v['duration']), array('visits' => 1));
		if ($v['entry_url'] !== '') stats_add($rep, 'entry', $v['entry_url'], array('visits' => 1, 'entries' => 1, 'bounces' => $bounce, 'actions' => $v['actions']));
		if ($v['exit_url'] !== '') stats_add($rep, 'exit', $v['exit_url'], array('visits' => 1, 'exits' => 1));
	}
	if (isset($rep['summary'][''])) $rep['summary']['']['visitors'] = count($uniq);

	// Actions du jour (pages, titres, 404, liens, fichiers)
	$actions = $db->all('SELECT `visit_id`, `type`, `url`, `title`, `time_spent` FROM {sb_stats_action} WHERE `created_at` >= ? AND `created_at` < ?', array($from, $to)) ?: array();
	$seen = array();
	foreach ($actions as $a) {
		$type = (string) $a['type'];
		$keys = array();
		if ($type === 'page') {
			$keys[] = array('page', $a['url']);
			$keys[] = array('title', $a['title'] !== '' ? $a['title'] : '(sans titre)');
		} elseif (in_array($type, array('404', 'outlink', 'download'), true)) {
			$keys[] = array($type, $type === 'download' && $a['title'] !== '' ? $a['title'] . ' | ' . $a['url'] : $a['url']);
		}
		foreach ($keys as $k) {
			$id = $k[0] . "\0" . $k[1] . "\0" . $a['visit_id'];
			stats_add($rep, $k[0], $k[1], array('hits' => 1, 'duration' => $a['time_spent'], 'visits' => isset($seen[$id]) ? 0 : 1));
			$seen[$id] = true;
		}
	}
	return $rep;
}

/** Début et fin (exclue) d'un jour 'Y-m-d' */
function stats_day_bounds($day) {
	$ts = strtotime($day . ' 00:00:00');
	return array(date('Y-m-d 00:00:00', $ts), date('Y-m-d 00:00:00', strtotime('+1 day', $ts)));
}

/** Archive un jour : ses lignes sont remplacées (rejouable) */
function stats_archive_day(sbdb $db, $day) {
	if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $day)) return false;
	list($from, $to) = stats_day_bounds($day);
	$rep = stats_aggregate($db, $from, $to);
	return $db->transaction(function () use ($db, $day, $rep) {
		if ($db->delete('sb_stats_daily', '`day` = ?', array($day)) === false) throw new RuntimeException($db->error());
		foreach ($rep as $report => $rows) {
			foreach ($rows as $label => $m) {
				if ($db->insert('sb_stats_daily', array('day' => $day, 'report' => $report, 'label' => (string) $label) + $m) === false) throw new RuntimeException($db->error());
			}
		}
		return true;
	}) === true;
}

/**
 * Archive les jours terminés pas encore archivés ($max au plus par appel).
 * Un jour n'est archivé qu'une fois le délai de visite écoulé après minuit
 * (une visite de 23 h 50 peut encore recevoir des actions).
 * @return int nombre de jours archivés
 */
function stats_archive_pending(sbdb $db, $now = null, $max = 31) {
	$now = $now ?? time();
	$timeout = max(1, min(1440, (int) stats_setting('visit_timeout') ?: 30));
	$last = date('Y-m-d', strtotime('-1 day', strtotime(date('Y-m-d', $now - $timeout * 60))));
	$done = stats_setting('archived_until');
	if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $done)) {
		$first = $db->value('SELECT MIN(`first_action`) FROM {sb_stats_visit}');
		if (!$first) return 0;
		$done = date('Y-m-d', strtotime('-1 day', strtotime(substr((string) $first, 0, 10))));
	}
	$n = 0;
	$day = date('Y-m-d', strtotime('+1 day', strtotime($done)));
	while ($day <= $last && $n < $max) {
		if (!stats_archive_day($db, $day)) break;
		stats_settings_save(array('archived_until' => $day));
		$n++;
		$day = date('Y-m-d', strtotime('+1 day', strtotime($day)));
	}
	return $n;
}

/**
 * Purge : données brutes des jours archivés au-delà de raw_retention jours
 * (0 : jamais), rapports au-delà de report_retention mois (0 : jamais).
 * @return array visites et lignes de rapport supprimées
 */
function stats_purge(sbdb $db, $now = null) {
	$now = $now ?? time();
	$out = array('visits' => 0, 'reports' => 0);
	$raw = max(0, min(3650, (int) stats_setting('raw_retention')));
	$done = stats_setting('archived_until');
	if ($raw > 0 && preg_match('/^\d{4}-\d{2}-\d{2}$/', $done)) {
		// Jamais un jour pas encore archivé (ses rapports seraient perdus)
		$cut = min(date('Y-m-d 00:00:00', strtotime('-' . $raw . ' days', $now)), date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($done))));
		$max = (int) $db->value('SELECT MAX(`id`) FROM {sb_stats_visit} WHERE `first_action` < ?', array($cut));
		if ($max > 0) {
			$db->delete('sb_stats_action', '`visit_id` <= ?', array($max));
			$out['visits'] = (int) $db->delete('sb_stats_visit', '`id` <= ? AND `first_action` < ?', array($max, $cut));
		}
	}
	$months = max(0, min(240, (int) stats_setting('report_retention')));
	if ($months > 0) {
		$out['reports'] = (int) $db->delete('sb_stats_daily', '`day` < ?', array(date('Y-m-d', strtotime('-' . $months . ' months', $now))));
	}
	return $out;
}

/**
 * Entretien (archivage puis purge), au plus une fois par heure : une seule
 * requête l'obtient (UPDATE conditionnel), même si plusieurs arrivent ensemble.
 * @return bool true si l'entretien a eu lieu
 */
function stats_maintenance(sbdb $db, $now = null, $force = false, $maxDays = 31) {
	$now = $now ?? time();
	if (!$force) {
		$db->insertIgnore('sb_stats_setting', array('name' => 'archive_last', 'value' => '0'));
		$claimed = $db->update('sb_stats_setting', array('value' => (string) $now), '`name` = ? AND CAST(`value` AS ' . ($db->driver() === 'sqlite' ? 'INTEGER' : 'SIGNED') . ') < ?', array('archive_last', $now - 3600));
		if ($claimed !== 1) return false;
	}
	@set_time_limit(300);
	stats_archive_pending($db, $now, max(1, (int) $maxDays));
	stats_purge($db, $now);
	stats_geoip_auto_update($now);
	stats_settings(true);
	return true;
}

/**
 * Rapport sur une période de jours [$from, $to] ('Y-m-d', inclus) : jours
 * archivés lus dans sb_stats_daily, les autres calculés à la volée.
 * @param string $order colonne de tri (stats_metric_names()), décroissant
 * @return array libellé => mesures, trié
 */
function stats_report(sbdb $db, $report, $from, $to, $order = 'visits', $limit = 0) {
	if (!isset(stats_reports()[$report]) || $from > $to) return array();
	$out = array();
	$done = stats_setting('archived_until');
	$done = preg_match('/^\d{4}-\d{2}-\d{2}$/', $done) ? $done : '0000-00-00';
	$metrics = stats_metric_names();
	if ($from <= $done) {
		$sum = implode(', ', array_map(function ($c) { return 'SUM(`' . $c . '`) AS `' . $c . '`'; }, $metrics));
		foreach ($db->all('SELECT `label`, ' . $sum . ' FROM {sb_stats_daily} WHERE `report` = ? AND `day` >= ? AND `day` <= ? GROUP BY `label`',
			array($report, $from, min($to, $done))) ?: array() as $r) {
			foreach ($metrics as $c) $out[$r['label']][$c] = (int) $r[$c];
		}
	}
	if ($to > $done) {
		$start = max($from, date('Y-m-d', strtotime('+1 day', strtotime($done))));
		$rep = stats_aggregate($db, $start . ' 00:00:00', date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($to))));
		foreach ($rep[$report] ?? array() as $label => $m) {
			foreach ($metrics as $c) $out[$label][$c] = ($out[$label][$c] ?? 0) + $m[$c];
		}
	}
	$order = in_array($order, $metrics, true) ? $order : 'visits';
	uksort($out, function ($a, $b) use ($out, $order) {
		return ($out[$b][$order] <=> $out[$a][$order]) ?: strcmp((string) $a, (string) $b);
	});
	return $limit > 0 ? array_slice($out, 0, (int) $limit, true) : $out;
}

/**
 * Évolution jour par jour d'une mesure du résumé (graphique) sur [$from, $to]
 * @return array 'Y-m-d' => mesures (jours sans visite à 0)
 */
function stats_series(sbdb $db, $from, $to) {
	$out = array();
	for ($d = $from; $d <= $to; $d = date('Y-m-d', strtotime('+1 day', strtotime($d)))) {
		$out[$d] = array_fill_keys(stats_metric_names(), 0);
	}
	$done = stats_setting('archived_until');
	$done = preg_match('/^\d{4}-\d{2}-\d{2}$/', $done) ? $done : '0000-00-00';
	if ($from <= $done) {
		foreach ($db->all("SELECT * FROM {sb_stats_daily} WHERE `report` = 'summary' AND `day` >= ? AND `day` <= ?", array($from, min($to, $done))) ?: array() as $r) {
			$day = substr((string) $r['day'], 0, 10);
			foreach (stats_metric_names() as $c) $out[$day][$c] = (int) $r[$c];
		}
	}
	for ($d = max($from, date('Y-m-d', strtotime('+1 day', strtotime($done)))); $d <= $to; $d = date('Y-m-d', strtotime('+1 day', strtotime($d)))) {
		list($a, $b) = stats_day_bounds($d);
		$rep = stats_aggregate($db, $a, $b);
		if (isset($rep['summary'][''])) $out[$d] = $rep['summary'][''];
	}
	return $out;
}

// -----------------------------------------------------------------------
// Périodes (sélecteur de l'administration, comme Matomo)
// -----------------------------------------------------------------------

/**
 * Période demandée : jour, semaine (lundi-dimanche), mois, année ou plage.
 * @param string $period day, week, month, year, range
 * @param string $date   jour de référence 'Y-m-d' (défaut : aujourd'hui)
 * @param string $from,$to bornes d'une plage
 * @return array period, date, from, to (bornés à aujourd'hui), prev_from,
 *               prev_to (période précédente de même longueur), label,
 *               chart_from, chart_to, chart_step (day ou month)
 */
function stats_period($period, $date = '', $from = '', $to = '', $now = null) {
	$now = $now ?? time();
	$today = date('Y-m-d', $now);
	$valid = function ($d) { return is_string($d) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $d) && checkdate((int) substr($d, 5, 2), (int) substr($d, 8, 2), (int) substr($d, 0, 4)); };
	$period = in_array($period, array('day', 'week', 'month', 'year', 'range'), true) ? $period : 'day';
	$date = $valid($date) && $date <= $today ? $date : $today;
	$ts = strtotime($date);
	$add = function ($d, $n) { return date('Y-m-d', strtotime(($n >= 0 ? '+' : '') . $n . ' day', strtotime($d))); };
	$mois = array('', 'janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre');
	$fr = function ($d) use ($mois) { $t = strtotime($d); return (int) date('j', $t) . ' ' . $mois[(int) date('n', $t)] . ' ' . date('Y', $t); };
	$step = 'day';
	switch ($period) {
		case 'week':
			$a = date('Y-m-d', strtotime('monday this week', $ts));
			$b = $add($a, 6);
			$label = 'Semaine du ' . $fr($a);
			$ca = $add($a, -21); $cb = $b;
			break;
		case 'month':
			$a = date('Y-m-01', $ts);
			$b = date('Y-m-t', $ts);
			$label = ucfirst($mois[(int) date('n', $ts)]) . ' ' . date('Y', $ts);
			$ca = $a; $cb = $b;
			break;
		case 'year':
			$a = date('Y-01-01', $ts);
			$b = date('Y-12-31', $ts);
			$label = 'Année ' . date('Y', $ts);
			$ca = $a; $cb = $b; $step = 'month';
			break;
		case 'range':
			$a = $valid($from) ? $from : $add($today, -29);
			$b = $valid($to) ? $to : $today;
			if ($a > $b) list($a, $b) = array($b, $a);
			if ($b > $today) $b = $today;
			if ($a > $b) $a = $b;
			if ((strtotime($b) - strtotime($a)) / 86400 > 3660) $a = $add($b, -3660);
			$label = 'Du ' . $fr($a) . ' au ' . $fr($b);
			$ca = $a; $cb = $b;
			if ((strtotime($b) - strtotime($a)) / 86400 > 120) $step = 'month';
			break;
		default:
			$a = $b = $date;
			$label = ucfirst(date('Y-m-d', $ts) === $today ? 'aujourd\'hui, ' . $fr($date) : ($add($today, -1) === $date ? 'hier, ' . $fr($date) : $fr($date)));
			$ca = $add($date, -29); $cb = $date;
	}
	$days = (int) round((strtotime($b) - strtotime($a)) / 86400) + 1;
	return array(
		'period' => $period, 'date' => $date, 'from' => $a, 'to' => min($b, $today), 'end' => $b, 'days' => $days,
		'prev_from' => $add($a, -$days), 'prev_to' => $add($a, -1), 'label' => $label,
		'chart_from' => $ca, 'chart_to' => min($cb, $today), 'chart_end' => $cb, 'chart_step' => $step,
	);
}

/**
 * Série du graphique d'évolution : points par jour, ou par mois (année,
 * longue plage). Les jours à venir de la période restent vides (null).
 * @return array liste de array('key', 'label', mesures...)
 */
function stats_chart_points(sbdb $db, array $p) {
	$series = $p['chart_from'] <= $p['chart_to'] ? stats_series($db, $p['chart_from'], $p['chart_to']) : array();
	$mois = array('', 'janv.', 'févr.', 'mars', 'avr.', 'mai', 'juin', 'juil.', 'août', 'sept.', 'oct.', 'nov.', 'déc.');
	$out = array();
	for ($d = $p['chart_from']; $d <= $p['chart_end']; $d = date('Y-m-d', strtotime('+1 day', strtotime($d)))) {
		$key = $p['chart_step'] === 'month' ? substr($d, 0, 7) : $d;
		$t = strtotime($d);
		if (!isset($out[$key])) {
			$out[$key] = array('key' => $key, 'label' => $p['chart_step'] === 'month' ? $mois[(int) date('n', $t)] . ' ' . date('Y', $t) : date('d/m', $t), 'future' => true)
				+ array_fill_keys(stats_metric_names(), 0);
		}
		if (isset($series[$d])) {
			$out[$key]['future'] = false;
			foreach (stats_metric_names() as $c) $out[$key][$c] += (int) $series[$d][$c];
		}
	}
	return array_values($out);
}


/**
 * Mise à jour automatique de la base des pays (réglage geoip_auto, licence
 * acceptée) : une fois par mois, à partir du 3 (le fichier du mois paraît
 * le 1er), au plus une tentative par jour en cas d'échec.
 */
function stats_geoip_auto_update($now = null) {
	$now = $now ?? time();
	if (stats_setting('geoip_auto') !== '1' || stats_setting('geoip_license') === '' || (int) date('j', $now) < 3) return false;
	if (stats_setting('geoip_version') === date('Y-m', $now)) return false;
	if ((int) stats_setting('geoip_try') > $now - 86400) return false;
	stats_settings_save(array('geoip_try' => (string) $now));
	require_once(__DIR__ . DIRECTORY_SEPARATOR . 'geoip.php');
	list($err) = stats_geoip_update(null, $now);
	return $err === '';
}
