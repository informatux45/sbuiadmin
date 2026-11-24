<?php
/**
 * Module stats : administration (rapports façon Matomo, journal des
 * visites, temps réel, réglages)
 *
 * Droits (module.php) : les rapports agrégés sur la ligne « Statistiques »,
 * le journal des visites et le temps réel (visites une par une) et les
 * réglages sur leurs propres lignes.
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

$module_page = 'stats';
$sbsmarty->assign('module_page', $module_page);
$module_url = _AM_SITE_PROTOCOL . SBUIADMIN_URL . SBUIADMIN_BASE . '?p=' . $module_page;
$sbsmarty->assign('module_url', $module_url);

$sb_msg_error = false;
$sb_msg_valid = false;

require_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'tracker.php');
require_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'archive.php');
require_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'dashboard.php');
require_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'geoip.php');
stats_use_site_timezone();
$db = stats_db();
// Entretien horaire (archivage, purge, base des pays) quand le site ne peut
// pas le faire après ses réponses (sans PHP-FPM) : pages de rapports seulement
if ($db && $_SERVER['REQUEST_METHOD'] === 'GET' && !in_array($action, array('export', 'dashsave', 'archive', 'purgedel', 'geoip'), true)) {
	try { stats_maintenance_maybe($db, null, false); } catch (Throwable $e) { }
}

$action = is_string($_GET['a'] ?? null) ? $_GET['a'] : '';

// -----------------------------------------------------------------------
// Pages de rapports : clé a= => titre, section du menu, blocs (rapports)
// -----------------------------------------------------------------------
$sb_stats_pages = array(
	'dashboard' => array('Tableau de bord', 'Accueil', array()),
	'overview'  => array('Vue d\'ensemble', 'Visiteurs', array()),
	'realtime'  => array('Temps réel', 'Visiteurs', array()),
	'visitors'  => array('Journal des visites', 'Visiteurs', array()),
	'devices'   => array('Appareils', 'Visiteurs', array('device', 'resolution')),
	'software'  => array('Logiciels', 'Visiteurs', array('browser', 'os', 'browser_version', 'os_version')),
	'locations' => array('Lieux et langues', 'Visiteurs', array('country', 'language')),
	'times'     => array('Heures et jours', 'Visiteurs', array('hour', 'weekday')),
	'engagement' => array('Fidélité', 'Visiteurs', array('visit_pages', 'visit_duration')),
	'pages'     => array('Pages', 'Comportement', array('page')),
	'entry'     => array('Pages d\'entrée', 'Comportement', array('entry')),
	'exit'      => array('Pages de sortie', 'Comportement', array('exit')),
	'titles'    => array('Titres des pages', 'Comportement', array('title')),
	'outlinks'  => array('Liens sortants', 'Comportement', array('outlink')),
	'downloads' => array('Téléchargements', 'Comportement', array('download')),
	'notfound'  => array('Pages introuvables', 'Comportement', array('404')),
	'channels'  => array('Vue d\'ensemble', 'Acquisition', array('referer_type')),
	'search'    => array('Moteurs et mots-clés', 'Acquisition', array('search', 'keyword')),
	'websites'  => array('Sites web', 'Acquisition', array('website')),
	'social'    => array('Réseaux sociaux', 'Acquisition', array('social')),
	'campaigns' => array('Campagnes', 'Acquisition', array('campaign', 'campaign_source')),
	'settings'  => array('Réglages', 'Administration', array()),
);
$sb_page = isset($sb_stats_pages[$action]) ? $action : (in_array($action, array('archive', 'purgedel', 'export', 'dashsave', 'geoip'), true) ? $action : 'dashboard');

// Liens du menu des rapports : masqués sans le droit de leur ligne
$sb_menu = array();
foreach ($sb_stats_pages as $k => $def) {
	$rights_key = in_array($k, array('visitors', 'realtime'), true) ? 'stats:visitors' : ($k === 'settings' ? 'stats:settings' : 'stats');
	if (function_exists('sbHasMenuLinkRight') && !sbHasMenuLinkRight('index.php?p=stats' . ($k === 'dashboard' ? '' : '&a=' . $k), $rights_key)) continue;
	$sb_menu[$def[1]][$k] = $def[0];
}

// Utilisateur courant, et droit de voir les visites une par une
$sb_uid = function_exists('sbGetCurrentUserId') ? (int) sbGetCurrentUserId() : 0;
$sb_can_visitors = !function_exists('sbHasMenuLinkRight') || sbHasMenuLinkRight('index.php?p=stats&a=visitors', 'stats:visitors');

// -----------------------------------------------------------------------
// Présentation des libellés
// -----------------------------------------------------------------------
function stats_admin_label($report, $label) {
	static $maps = null;
	if ($maps === null) {
		$maps = array(
			'device'       => array('desktop' => 'Ordinateur', 'smartphone' => 'Smartphone', 'tablet' => 'Tablette', 'tv' => 'Télévision'),
			'referer_type' => array('direct' => 'Accès direct', 'search' => 'Moteurs de recherche', 'website' => 'Sites web', 'social' => 'Réseaux sociaux', 'campaign' => 'Campagnes'),
			'weekday'      => array('1' => 'Lundi', '2' => 'Mardi', '3' => 'Mercredi', '4' => 'Jeudi', '5' => 'Vendredi', '6' => 'Samedi', '7' => 'Dimanche'),
			'visit_pages'  => array('0' => '0 page', '1' => '1 page'),
		);
	}
	$label = (string) $label;
	if (isset($maps[$report][$label])) return $maps[$report][$label];
	if ($report === 'hour') return $label . ' h - ' . sprintf('%02d', ((int) $label + 1) % 24) . ' h';
	if ($report === 'visit_pages') return $label . ' pages';
	if ($report === 'country') return stats_admin_country_name($label);
	if ($report === 'language') return $label === 'xx' ? 'Inconnue' : (function_exists('locale_get_display_name') ? (locale_get_display_name($label, 'fr') ?: $label) : $label);
	if ($report === 'title' && $label === '(sans titre)') return 'Sans titre';
	return $label;
}

/** Nom français d'un pays (code ISO), par intl, sinon la table sb_country (anglais) */
function stats_admin_country_name($cc) {
	static $names = null;
	$cc = strtoupper((string) $cc);
	if ($cc === '' || $cc === 'XX') return 'Inconnu';
	if (function_exists('locale_get_display_region')) {
		$n = locale_get_display_region('-' . $cc, 'fr');
		if (is_string($n) && $n !== '' && $n !== $cc) return $n;
	}
	if ($names === null) {
		$names = array();
		$db = stats_db();
		foreach (($db ? $db->all('SELECT `country_iso`, `country_printable_name` FROM {sb_country}') : false) ?: array() as $r) $names[strtoupper($r['country_iso'])] = $r['country_printable_name'];
	}
	return $names[$cc] ?? $cc;
}

/** Icône Font Awesome d'un libellé (appareils, canaux), '' sinon */
function stats_admin_icon($report, $label) {
	$icons = array(
		'device'       => array('desktop' => 'desktop', 'smartphone' => 'mobile-screen', 'tablet' => 'tablet-screen-button', 'tv' => 'tv'),
		'referer_type' => array('direct' => 'right-to-bracket', 'search' => 'magnifying-glass', 'website' => 'globe', 'social' => 'share-nodes', 'campaign' => 'bullhorn'),
	);
	return $icons[$report][(string) $label] ?? '';
}

/** Durée lisible : 1 min 05 s, 2 h 03 min */
function stats_admin_duration($s) {
	$s = max(0, (int) round($s));
	if ($s < 60) return $s . ' s';
	if ($s < 3600) return intdiv($s, 60) . ' min ' . sprintf('%02d', $s % 60) . ' s';
	return intdiv($s, 3600) . ' h ' . sprintf('%02d', intdiv($s % 3600, 60)) . ' min';
}

function stats_admin_pct($part, $total, $dec = 0) {
	return $total > 0 ? round(100 * $part / $total, $dec) : 0;
}

/** Variation en % (null si pas de base de comparaison) */
function stats_admin_trend($now, $before) {
	if ($before <= 0) return null;
	return round(100 * ($now - $before) / $before);
}

/** Adresse absolue d'une page du site (lien depuis les rapports) */
function stats_admin_site_link($path) {
	$path = (string) $path;
	if ($path === '' || $path[0] !== '/') return '';
	$site = (string) sbSetting('site_url');
	$scheme = parse_url($site, PHP_URL_SCHEME) ?: 'https';
	$host = parse_url($site, PHP_URL_HOST);
	if (!$host) return '';
	$port = parse_url($site, PHP_URL_PORT);
	return $scheme . '://' . $host . ($port ? ':' . $port : '') . $path;
}

/**
 * Colonnes d'un rapport : clé => array(titre, type). Types : num, pct (de
 * visites), dur (durée moyenne), ratio, bounce (taux de rebond)
 */
function stats_admin_columns($report) {
	if (in_array($report, array('page', 'title'), true)) return array('hits' => array('Vues', 'num'), 'visits' => array('Visites', 'num'), 'avg_time' => array('Temps moyen', 'dur'));
	if ($report === 'entry') return array('entries' => array('Entrées', 'num'), 'bounce' => array('Rebond', 'bounce'), 'apv' => array('Actions / entrée', 'ratio'));
	if ($report === 'exit') return array('exits' => array('Sorties', 'num'));
	if (in_array($report, array('404', 'outlink', 'download'), true)) return array('hits' => array($report === '404' ? 'Vues' : 'Clics', 'num'), 'visits' => array('Visites', 'num'));
	if (in_array($report, array('visit_pages', 'visit_duration'), true)) return array('visits' => array('Visites', 'num'));
	return array('visits' => array('Visites', 'num'), 'apv' => array('Actions / visite', 'ratio'), 'avg_dur' => array('Durée moy.', 'dur'), 'bounce' => array('Rebond', 'bounce'));
}

/** Colonne principale (barre de part, tri) d'un rapport */
function stats_admin_main_metric($report) {
	if (in_array($report, array('page', 'title', '404', 'outlink', 'download'), true)) return 'hits';
	if ($report === 'entry') return 'entries';
	if ($report === 'exit') return 'exits';
	return 'visits';
}

/**
 * Bloc de rapport prêt pour le gabarit : lignes (libellé, lien, icône,
 * valeurs formatées, part), total, données du graphique éventuel
 */
function stats_admin_block(sbdb $db, $report, array $p, $limit = 100) {
	$main = stats_admin_main_metric($report);
	$rows = stats_report($db, $report, $p['from'], $p['to'], $main);
	// Heures et jours : toutes les valeurs, dans l'ordre naturel
	if ($report === 'hour' || $report === 'weekday') {
		$all = array();
		$keys = $report === 'hour' ? array_map(function ($h) { return sprintf('%02d', $h); }, range(0, 23)) : array_map('strval', range(1, 7));
		foreach ($keys as $k) $all[$k] = $rows[$k] ?? array_fill_keys(stats_metric_names(), 0);
		$rows = $all;
	}
	if ($report === 'visit_pages' || $report === 'visit_duration') {
		$order = $report === 'visit_pages' ? array('0', '1', '2', '3', '4', '5', '6-10', '11-15', '16-20', '21-30', '31-50', '51+')
			: array('0-10 s', '11-30 s', '31-60 s', '1-2 min', '2-4 min', '4-7 min', '7-10 min', '10-15 min', '15-30 min', '30 min et +');
		$sorted = array();
		foreach ($order as $k) if (isset($rows[$k])) $sorted[$k] = $rows[$k];
		$rows = $sorted;
	}
	$total = 0;
	foreach ($rows as $m) $total += $m[$main];
	$cols = stats_admin_columns($report);
	$out = array();
	$i = 0;
	$other = null;
	foreach ($rows as $label => $m) {
		if ($limit > 0 && ++$i > $limit) {
			if ($other === null) $other = array_fill_keys(stats_metric_names(), 0);
			foreach (stats_metric_names() as $c) $other[$c] += $m[$c];
			continue;
		}
		$out[] = stats_admin_row($report, (string) $label, $m, $cols, $main, $total);
	}
	if ($other !== null) $out[] = stats_admin_row($report, 'Autres (' . ($i - $limit) . ')', $other, $cols, $main, $total, true);
	$chart = null;
	if (in_array($report, array('device', 'referer_type'), true) && $total > 0) {
		// Anneau : 5 parts au plus, le reste dans « Autres »
		$slices = array(); $rest = 0; $n = 0;
		foreach ($rows as $label => $m) {
			if (++$n <= 5) $slices[] = array('label' => stats_admin_label($report, $label), 'value' => (int) $m[$main]);
			else $rest += (int) $m[$main];
		}
		if ($rest > 0) $slices[] = array('label' => 'Autres', 'value' => $rest);
		$chart = array('type' => 'donut', 'data' => $slices);
	} elseif ($report === 'country' && $total > 0) {
		// Carte du monde (jsVectorMap du thème) : codes ISO en majuscules
		$vals = array(); $names = array();
		foreach ($rows as $label => $m) {
			if ($label === 'xx' || !preg_match('/^[a-z]{2}$/', (string) $label)) continue;
			$vals[strtoupper($label)] = (int) $m[$main];
			$names[strtoupper($label)] = stats_admin_label('country', $label);
		}
		if ($vals) $chart = array('type' => 'map', 'data' => $vals, 'names' => $names);
	} elseif (in_array($report, array('hour', 'weekday'), true) && $total > 0) {
		$bars = array();
		foreach ($rows as $label => $m) $bars[] = array('label' => $report === 'hour' ? $label . ' h' : mb_substr(stats_admin_label($report, $label), 0, 3, 'UTF-8') . '.', 'value' => (int) $m[$main]);
		$chart = array('type' => 'bars', 'data' => $bars);
	}
	return array(
		'attribution' => in_array($report, array('country'), true),
		'report' => $report, 'title' => stats_reports()[$report] ?? $report, 'main' => $main,
		'main_title' => $cols[$main][0] ?? 'Visites', 'columns' => $cols, 'rows' => $out, 'total' => $total,
		'chart' => $chart, 'chart_json' => $chart ? json_encode($chart, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) : '',
	);
}

function stats_admin_row($report, $label, array $m, array $cols, $main, $total, $isOther = false) {
	$values = array();
	foreach ($cols as $k => $def) {
		switch ($k) {
			case 'avg_time': $v = stats_admin_duration($m['hits'] > 0 ? $m['duration'] / $m['hits'] : 0); break;
			case 'avg_dur':  $v = stats_admin_duration($m['visits'] > 0 ? $m['duration'] / $m['visits'] : 0); break;
			case 'apv':      $base = $report === 'entry' ? $m['entries'] : $m['visits']; $v = $base > 0 ? number_format($m['actions'] / $base, 1, ',', ' ') : '0'; break;
			case 'bounce':   $base = $report === 'entry' ? $m['entries'] : $m['visits']; $v = stats_admin_pct($m['bounces'], $base) . ' %'; break;
			default:         $v = number_format((int) ($m[$k] ?? 0), 0, ',', ' ');
		}
		$values[$k] = $v;
	}
	$isUrl = in_array($report, array('page', 'entry', 'exit', '404'), true);
	$link = '';
	if ($isOther) $link = '';
	elseif ($isUrl) $link = stats_admin_site_link($label);
	elseif (in_array($report, array('outlink'), true) && preg_match('~^https?://~', $label)) $link = $label;
	elseif ($report === 'website') $link = 'https://' . $label . '/';
	return array(
		'label' => $isOther ? $label : stats_admin_label($report, $label), 'raw' => $label, 'link' => $link, 'icon' => $isOther ? '' : stats_admin_icon($report, $label),
		'values' => $values, 'share' => stats_admin_pct($m[$main], $total, 1), 'other' => $isOther,
	);
}

// -----------------------------------------------------------------------
// Période (paramètres de l'adresse, gardés de page en page)
// -----------------------------------------------------------------------
$p = stats_period(is_string($_GET['period'] ?? null) ? $_GET['period'] : 'day', is_string($_GET['date'] ?? null) ? $_GET['date'] : '',
	is_string($_GET['from'] ?? null) ? $_GET['from'] : '', is_string($_GET['to'] ?? null) ? $_GET['to'] : '');
$sb_qs = '&period=' . $p['period'] . ($p['period'] === 'range' ? '&from=' . $p['from'] . '&to=' . $p['to'] : '&date=' . $p['date']);
$sbsmarty->assign('sb_stats_period', $p);
$sbsmarty->assign('sb_stats_qs', $sb_qs);
$sbsmarty->assign('sb_stats_today', date('Y-m-d'));

if (!$db) {
	$sb_msg_error = 'Base de données indisponible.';
	$sb_page = 'error';
}

switch ($sb_page) {
	case 'error':
		break;

	// --------------------------------
	// --- Tableau de bord : disposition enregistrée (AJAX, jeton CSRF vérifié par le routeur)
	// --------------------------------
	case 'dashsave':
		$sb_allowed = array();
		foreach (stats_dashboard_widgets() as $id => $w) if ($w[3] !== 'visitors' || $sb_can_visitors) $sb_allowed[] = $id;
		$sb_ok = false;
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && $sb_uid > 0) {
			if (($_POST['op'] ?? '') === 'reset') $sb_ok = stats_dashboard_reset($db, $sb_uid);
			elseif (is_string($_POST['layout'] ?? null) && strlen($_POST['layout']) <= 20000) $sb_ok = stats_dashboard_save($db, $sb_uid, $_POST['layout'], $sb_allowed);
		}
		while (ob_get_level() > 0) ob_end_clean();
		http_response_code($sb_ok ? 200 : 400);
		header('Content-Type: application/json; charset=UTF-8');
		header('Cache-Control: no-store');
		echo json_encode(array('ok' => $sb_ok));
		exit;

	// --------------------------------
	// --- Réglages (et actions d'entretien)
	// --------------------------------
	case 'geoip':
	case 'archive':
	case 'purgedel':
	case 'settings':
		if ($sb_page === 'geoip' && $_SERVER['REQUEST_METHOD'] === 'POST') {
			$sb_op = is_string($_POST['op'] ?? null) ? $_POST['op'] : '';
			if ($sb_op === 'update') {
				if (stats_setting('geoip_license') === '' && ($_POST['geoip_accept'] ?? '') !== 'on') {
					$sb_msg_error = 'Cochez l\'acceptation de la licence de DB-IP pour télécharger la base.';
				} else {
					if (stats_setting('geoip_license') === '') stats_settings_save(array('geoip_license' => date('Y-m-d H:i:s')));
					@set_time_limit(300);
					list($sb_err, $sb_info) = stats_geoip_update();
					if ($sb_err !== '') $sb_msg_error = 'Base des pays : ' . htmlspecialchars($sb_err);
					else $sb_msg_valid = 'Base des pays ' . htmlspecialchars($sb_info['version']) . ' installée (' . ($sb_info['sha1_checked'] === '1' ? 'empreinte SHA1 vérifiée' : 'empreinte non publiée, structure vérifiée') . ').';
				}
			} elseif ($sb_op === 'auto') {
				stats_settings_save(array('geoip_auto' => ($_POST['geoip_auto'] ?? '') === 'on' ? '1' : '0'));
				$sb_msg_valid = 'Mise à jour automatique ' . (stats_setting('geoip_auto') === '1' ? 'activée' : 'désactivée') . '.';
			} elseif ($sb_op === 'remove') {
				if (stats_geoip_remove()) $sb_msg_valid = 'Base des pays supprimée.'; else $sb_msg_error = 'Suppression impossible.';
			}
		}
		// POST seulement : droit « modifier » des Réglages et jeton CSRF (routeur) ; 366 jours au plus par clic
		if ($sb_page === 'archive' && $_SERVER['REQUEST_METHOD'] === 'POST') {
			$sb_n = stats_archive_pending($db, null, 366);
			$sb_purged = stats_purge($db);
			$sb_msg_valid = $sb_n . ' jour(s) archivé(s)' . ($sb_purged['visits'] ? ', ' . $sb_purged['visits'] . ' visite(s) ancienne(s) purgée(s)' : '') . '.';
		}
		if ($sb_page === 'purgedel') {
			// Toutes les statistiques effacées (réglages et clé gardés)
			$sb_ok = $db->delete('sb_stats_action', '1 = 1') !== false && $db->delete('sb_stats_visit', '1 = 1') !== false && $db->delete('sb_stats_daily', '1 = 1') !== false;
			if ($sb_ok) stats_settings_save(array('archived_until' => ''));
			if ($sb_ok) $sb_msg_valid = 'Toutes les statistiques ont été effacées.'; else $sb_msg_error = 'Effacement impossible : ' . htmlspecialchars($db->error());
		}
		$formName = 'stats_settings_form';
		if (!empty($_POST['form_submit'])) {
			$sb_in = function ($k) { return is_string($_POST[$k] ?? null) ? trim($_POST[$k]) : ''; };
			list($sb_ranges, $sb_bad) = sbClientIpParseTrusted($sb_in('excluded_ips'));
			$sb_params = array(); $sb_params_bad = array();
			foreach (preg_split('/[\s,;]+/', $sb_in('excluded_params')) as $sb_n) {
				if ($sb_n === '') continue;
				if (preg_match('/^[A-Za-z0-9_\-\[\]]{1,64}$/', $sb_n)) $sb_params[$sb_n] = $sb_n; else $sb_params_bad[] = $sb_n;
			}
			$sb_timeout = (int) $sb_in('visit_timeout');
			$sb_raw = (int) $sb_in('raw_retention');
			$sb_rep = (int) $sb_in('report_retention');
			$sb_rt = (int) $sb_in('realtime_minutes');
			if ($sb_bad) {
				$sb_msg_error = 'Adresses exclues : adresse ou plage invalide (' . htmlspecialchars(implode(', ', array_slice($sb_bad, 0, 5))) . '). Rien n\'a été enregistré.';
			} elseif ($sb_params_bad) {
				$sb_msg_error = 'Paramètres retirés : nom invalide (' . htmlspecialchars(implode(', ', array_slice($sb_params_bad, 0, 5))) . '). Rien n\'a été enregistré.';
			} elseif ($sb_timeout < 5 || $sb_timeout > 240) {
				$sb_msg_error = 'Fin de visite : de 5 à 240 minutes. Rien n\'a été enregistré.';
			} elseif ($sb_raw !== 0 && ($sb_raw < 7 || $sb_raw > 3650)) {
				$sb_msg_error = 'Journal des visites : de 7 à 3 650 jours (0 = sans limite). Rien n\'a été enregistré.';
			} elseif ($sb_rt < 0 || $sb_rt > 480) {
				$sb_msg_error = 'Temps réel : de 0 à 480 minutes. Rien n\'a été enregistré.';
			} elseif ($sb_rep < 0 || $sb_rep > 240) {
				$sb_msg_error = 'Rapports : de 1 à 240 mois (0 = sans limite). Rien n\'a été enregistré.';
			} elseif (stats_settings_save(array(
				'enabled' => ($_POST['enabled'] ?? '') === 'on' ? '1' : '0',
				'exclude_admin' => ($_POST['exclude_admin'] ?? '') === 'on' ? '1' : '0',
				'respect_dnt' => ($_POST['respect_dnt'] ?? '') === 'on' ? '1' : '0',
				'excluded_ips' => implode("\n", $sb_ranges), 'excluded_params' => implode(', ', $sb_params),
				'visit_timeout' => (string) $sb_timeout, 'raw_retention' => (string) $sb_raw, 'report_retention' => (string) $sb_rep, 'realtime_minutes' => (string) $sb_rt,
			))) {
				$sb_msg_valid = 'Réglages enregistrés';
			} else {
				$sb_msg_error = 'Error: Write Error (EDIT)!';
			}
		}

		$formAction = $module_url . '&a=settings';
		$sbform->openForm(array('action' => $formAction, 'name' => $formName, 'id' => $formName, 'reloadpage' => $formAction, 'submitpage' => $formAction));
		$sbform->addBreak('Collecte');
		$sbform->addCheckbox('Mesure d\'audience', array(array('text' => 'Active', 'name' => 'enabled', 'checked' => stats_setting('enabled') === '1' ? '1' : '0')), '', false, '<br />', 'Désactivée : plus rien n\'est noté, les statistiques déjà recueillies restent consultables.');
		$sbform->addCheckbox('Utilisateurs connectés', array(array('text' => 'Ne pas compter les utilisateurs connectés à l\'administration', 'name' => 'exclude_admin', 'checked' => stats_setting('exclude_admin') === '1' ? '1' : '0')), '', false, '<br />', '');
		$sbform->addCheckbox('Do Not Track', array(array('text' => 'Ne pas compter les navigateurs qui demandent à ne pas être suivis (DNT, GPC)', 'name' => 'respect_dnt', 'checked' => stats_setting('respect_dnt') === '1' ? '1' : '0')), '', false, '<br />',
			'Facultatif : sans cookie ni adresse IP enregistrée, la mesure est exemptée de consentement (recommandations de la CNIL).');
		$sbform->addTextarea('Adresses IP exclues', htmlspecialchars(stats_setting('excluded_ips')), array('name' => 'excluded_ips', 'rows' => '3', 'placeholder' => sbClientIp()), false,
			'Une adresse IPv4/IPv6 ou une plage CIDR par ligne (votre bureau, un service de surveillance). Votre adresse actuelle : <strong>' . htmlspecialchars(sbClientIp()) . '</strong>.');
		$sbform->addInput('text', 'Paramètres d\'adresse retirés', array('name' => 'excluded_params', 'value' => htmlspecialchars(stats_setting('excluded_params')), 'placeholder' => 'sort, ref'), false, false,
			'Séparés par des virgules : ces paramètres sont ôtés des adresses enregistrées (les pages ne se dédoublent pas dans les rapports). Les jetons, mots de passe, campagnes (utm_...) et le code d\'accès de maintenance le sont toujours.');
		$sbform->addInput('text', 'Fin de visite après (minutes d\'inactivité)', array('name' => 'visit_timeout', 'value' => (string) (int) stats_setting('visit_timeout'), 'placeholder' => '30'), true, false, 'Comme Matomo : 30 minutes par défaut.');
		$sbform->addInput('text', 'Temps réel : actualisation automatique pendant (minutes)', array('name' => 'realtime_minutes', 'value' => (string) (int) stats_setting('realtime_minutes'), 'placeholder' => '30'), true, false,
			'La page Temps réel se recharge toutes les 30 secondes pendant cette durée, puis s\'arrête (un bouton la relance) : la session d\'administration peut alors expirer normalement. 0 = pas d\'actualisation automatique.');
		$sbform->addBreak('Conservation');
		$sbform->addInput('text', 'Journal des visites (jours)', array('name' => 'raw_retention', 'value' => (string) (int) stats_setting('raw_retention'), 'placeholder' => '180'), true, false,
			'Visites une par une (journal, temps réel). Au-delà, elles sont supprimées : les rapports, déjà calculés jour par jour, restent complets. 0 = sans limite.');
		$sbform->addInput('text', 'Rapports (mois)', array('name' => 'report_retention', 'value' => (string) (int) stats_setting('report_retention'), 'placeholder' => '0'), true, false, '0 = gardés sans limite (quelques lignes par jour).');
		$sbform->addInput('hidden', '', array('name' => 'form_submit', 'value' => $formName));
		$sbform->addInput('reset', '', array('value' => 'Reset'));
		$sbform->closeForm();

		$sb_status = array(
			'archived_until' => stats_setting('archived_until'),
			'visits'         => (int) $db->value('SELECT COUNT(*) FROM {sb_stats_visit}'),
			'actions'        => (int) $db->value('SELECT COUNT(*) FROM {sb_stats_action}'),
			'oldest'         => (string) $db->value('SELECT MIN(`first_action`) FROM {sb_stats_visit}'),
			'daily'          => (int) $db->value('SELECT COUNT(*) FROM {sb_stats_daily}'),
			'first_day'      => (string) $db->value('SELECT MIN(`day`) FROM {sb_stats_daily}'),
			'last_run'       => (int) stats_setting('archive_last'),
		);
		$sbsmarty->assign('sb_stats_status', $sb_status);
		$sb_gfile = stats_geoip_file();
		$sbsmarty->assign('sb_stats_geoip', array(
			'installed' => is_file($sb_gfile), 'version' => stats_setting('geoip_version'), 'size' => is_file($sb_gfile) ? round(filesize($sb_gfile) / 1048576, 1) : 0,
			'updated' => (int) stats_setting('geoip_updated'), 'checked' => stats_setting('geoip_checked') === '1', 'sha1' => stats_setting('geoip_sha1'),
			'license' => stats_setting('geoip_license'), 'auto' => stats_setting('geoip_auto') === '1',
			'old' => is_file($sb_gfile) && (int) stats_setting('geoip_updated') < time() - 45 * 86400,
			'writable' => ($sb_gdir = stats_geoip_dir(true)) !== '' && is_writable($sb_gdir), 'curl' => function_exists('curl_init'),
		));
		$sbsmarty->assign('sb_form_id', $formName);
		$sbsmarty->assign('sb_form_submit_value', 'Modifier');
		$sbsmarty->assign('legend_add_edit', 'Réglages des statistiques');
		$sb_page = 'settings';
		break;

	// --------------------------------
	// --- Export CSV d'un rapport (période en cours)
	// --------------------------------
	case 'export':
		$sb_report = is_string($_GET['r'] ?? null) ? $_GET['r'] : '';
		if (!isset(stats_reports()[$sb_report]) || $sb_report === 'summary') {
			$sb_msg_error = 'Rapport inconnu.';
			$sb_page = 'overview';
			break;
		}
		$sb_block = stats_admin_block($db, $sb_report, $p, 0);
		while (ob_get_level() > 0) ob_end_clean();
		header('Content-Type: text/csv; charset=UTF-8');
		header('Content-Disposition: attachment; filename="statistiques-' . preg_replace('/[^a-z0-9_]/', '', $sb_report) . '-' . $p['from'] . '_' . $p['to'] . '.csv"');
		header('Cache-Control: no-store');
		$out = fopen('php://output', 'w');
		fwrite($out, "\xEF\xBB\xBF");
		$head = array($sb_block['title']);
		foreach ($sb_block['columns'] as $def) $head[] = $def[0];
		$head[] = 'Part (%)';
		fputcsv($out, $head, ';', '"', '');
		foreach ($sb_block['rows'] as $r) {
			$line = array_merge(array($r['label']), array_values($r['values']), array(str_replace('.', ',', (string) $r['share'])));
			// Une cellule commençant par = + - @ serait une formule dans un tableur
			foreach ($line as $i => $cell) if (preg_match('/^[=+\-@\t\r]/', (string) $cell)) $line[$i] = "'" . $cell;
			fputcsv($out, $line, ';', '"', '');
		}
		fclose($out);
		exit;
}

// --------------------------------
// --- Briques communes (pages et widgets du tableau de bord)
// --------------------------------
/** Indicateurs clés de la période, comparés à la période précédente */
function stats_admin_kpis(sbdb $db, array $p) {
	$cur  = stats_report($db, 'summary', $p['from'], $p['to'])[''] ?? array_fill_keys(stats_metric_names(), 0);
	$prev = stats_report($db, 'summary', $p['prev_from'], $p['prev_to'])[''] ?? array_fill_keys(stats_metric_names(), 0);
	$kpi = function ($m) {
		return array(
			'visits' => $m['visits'], 'visitors' => $m['visitors'], 'actions' => $m['actions'], 'hits' => $m['hits'],
			'bounce' => $m['visits'] > 0 ? round(100 * $m['bounces'] / $m['visits']) : 0,
			'avg' => $m['visits'] > 0 ? $m['duration'] / $m['visits'] : 0,
			'apv' => $m['visits'] > 0 ? $m['actions'] / $m['visits'] : 0,
		);
	};
	$k1 = $kpi($cur); $k0 = $kpi($prev);
	return array(
		array('label' => 'Visites', 'icon' => 'users', 'value' => number_format($k1['visits'], 0, ',', ' '), 'trend' => stats_admin_trend($k1['visits'], $k0['visits']), 'good' => 'up'),
		array('label' => 'Visiteurs uniques', 'icon' => 'user-clock', 'value' => number_format($k1['visitors'], 0, ',', ' '), 'trend' => stats_admin_trend($k1['visitors'], $k0['visitors']), 'good' => 'up'),
		array('label' => 'Pages vues', 'icon' => 'eye', 'value' => number_format($k1['hits'], 0, ',', ' '), 'trend' => stats_admin_trend($k1['hits'], $k0['hits']), 'good' => 'up'),
		array('label' => 'Actions par visite', 'icon' => 'route', 'value' => number_format($k1['apv'], 1, ',', ' '), 'trend' => stats_admin_trend($k1['apv'], $k0['apv']), 'good' => 'up'),
		array('label' => 'Durée moyenne', 'icon' => 'hourglass-half', 'value' => stats_admin_duration($k1['avg']), 'trend' => stats_admin_trend($k1['avg'], $k0['avg']), 'good' => 'up'),
		array('label' => 'Taux de rebond', 'icon' => 'right-from-bracket', 'value' => $k1['bounce'] . ' %', 'trend' => $k0['visits'] > 0 ? $k1['bounce'] - $k0['bounce'] : null, 'good' => 'down', 'points' => true),
	);
}

/** Données du graphique d'évolution (JSON pour data-sbst-chart) */
function stats_admin_chart_json(sbdb $db, array $p) {
	$pts = stats_chart_points($db, $p);
	return json_encode(array('type' => 'line', 'step' => $p['chart_step'], 'points' => array_map(function ($x) {
		return array('label' => $x['label'], 'visits' => $x['future'] ? null : $x['visits'], 'hits' => $x['future'] ? null : $x['hits']);
	}, $pts)), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
}

/** Visites prêtes pour l'affichage (libellés, icônes, actions) */
function stats_admin_visits(sbdb $db, array $visits) {
	$ids = array_map(function ($v) { return (int) $v['id']; }, $visits);
	$acts = array();
	if ($ids) {
		foreach ($db->all('SELECT `visit_id`, `created_at`, `type`, `url`, `title`, `time_spent` FROM {sb_stats_action} WHERE `visit_id` IN (' . implode(',', $ids) . ') ORDER BY `id`') ?: array() as $a) {
			$a['link'] = in_array($a['type'], array('page', '404'), true) ? stats_admin_site_link($a['url']) : (preg_match('~^https?://~', $a['url']) ? $a['url'] : '');
			$a['time'] = substr((string) $a['created_at'], 11, 5);
			$a['spent'] = (int) $a['time_spent'] > 0 ? stats_admin_duration($a['time_spent']) : '';
			$acts[(int) $a['visit_id']][] = $a;
		}
	}
	foreach ($visits as $i => $v) {
		$v['actions_list'] = $acts[(int) $v['id']] ?? array();
		$v['date_fr'] = date('d/m/Y H:i', strtotime($v['first_action']));
		$v['time_fr'] = date('H:i', strtotime($v['last_action']));
		$v['duration_fr'] = stats_admin_duration($v['duration']);
		$v['device_label'] = stats_admin_label('device', $v['device'] ?: 'desktop');
		$v['device_icon'] = stats_admin_icon('device', $v['device'] ?: 'desktop');
		$v['referer_label'] = stats_admin_label('referer_type', $v['referer_type']);
		$v['referer_icon'] = stats_admin_icon('referer_type', $v['referer_type']);
		$v['language_label'] = stats_admin_label('language', $v['language'] ?: 'xx');
		$v['country_label'] = $v['country'] !== '' ? stats_admin_country_name($v['country']) : '';
		$v['ago'] = stats_admin_duration(time() - strtotime($v['last_action']));
		$visits[$i] = $v;
	}
	return $visits;
}

/** Temps réel : compteurs des 30 dernières minutes et des 24 dernières heures */
function stats_admin_live(sbdb $db) {
	$since = date('Y-m-d H:i:s', time() - 1800);
	$day = date('Y-m-d H:i:s', time() - 86400);
	return array(
		'visits30' => (int) $db->value('SELECT COUNT(*) FROM {sb_stats_visit} WHERE `last_action` >= ?', array($since)),
		'actions30' => (int) $db->value('SELECT COUNT(*) FROM {sb_stats_action} WHERE `created_at` >= ?', array($since)),
		'visits24' => (int) $db->value('SELECT COUNT(*) FROM {sb_stats_visit} WHERE `last_action` >= ?', array($day)),
		'actions24' => (int) $db->value('SELECT COUNT(*) FROM {sb_stats_action} WHERE `created_at` >= ?', array($day)),
	);
}

// --------------------------------
// --- Pages de rapports
// --------------------------------
if ($sb_page === 'dashboard') {
	// Widgets du tableau de bord de l'utilisateur (permis par ses droits)
	$sb_widgets_all = stats_dashboard_widgets();
	$sb_allowed = array();
	foreach ($sb_widgets_all as $id => $w) if ($w[3] !== 'visitors' || $sb_can_visitors) $sb_allowed[] = $id;
	$sb_layout = stats_dashboard_get($db, $sb_uid, $sb_allowed);
	$sb_dash = array();
	foreach ($sb_layout['columns'] as $ci => $ids) {
		$sb_dash[$ci] = array();
		foreach ($ids as $id) {
			$w = $sb_widgets_all[$id];
			$item = array('id' => $id, 'title' => $w[0], 'type' => $w[1], 'collapsed' => in_array($id, $sb_layout['collapsed'], true));
			switch ($w[1]) {
				case 'kpis':      $item['kpis'] = stats_admin_kpis($db, $p); break;
				case 'evolution': $item['chart_json'] = stats_admin_chart_json($db, $p); break;
				case 'realtime':
					$item['live'] = stats_admin_live($db);
					$item['visits'] = stats_admin_visits($db, $db->all('SELECT * FROM {sb_stats_visit} WHERE `last_action` >= ? ORDER BY `last_action` DESC LIMIT 5', array(date('Y-m-d H:i:s', time() - 1800))) ?: array());
					break;
				case 'visits':
					$range = array($p['from'] . ' 00:00:00', date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($p['to']))));
					$item['visits'] = stats_admin_visits($db, $db->all('SELECT * FROM {sb_stats_visit} WHERE `first_action` >= ? AND `first_action` < ? ORDER BY `first_action` DESC LIMIT 10', $range) ?: array());
					break;
				default:          $item['block'] = stats_admin_block($db, $w[2], $p, 10);
			}
			$sb_dash[$ci][] = $item;
		}
	}
	$sb_catalog = array();
	foreach ($sb_allowed as $id) $sb_catalog[$id] = $sb_widgets_all[$id][0];
	asort($sb_catalog, SORT_FLAG_CASE | SORT_STRING);
	$sbsmarty->assign('sb_stats_dash', $sb_dash);
	$sbsmarty->assign('sb_stats_layout', $sb_layout);
	$sbsmarty->assign('sb_stats_layout_json', json_encode($sb_layout, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT));
	$sbsmarty->assign('sb_stats_catalog', $sb_catalog);
	$sbsmarty->assign('sb_stats_compare_label', 'vs période préc.');
} elseif ($sb_page === 'overview') {
	$sbsmarty->assign('sb_stats_kpis', stats_admin_kpis($db, $p));
	$sbsmarty->assign('sb_stats_chart_json', stats_admin_chart_json($db, $p));
	$sb_blocks = array();
	foreach (array('referer_type' => 0, 'device' => 0, 'page' => 10, 'entry' => 10, 'search' => 10, 'website' => 10) as $r => $lim) $sb_blocks[] = stats_admin_block($db, $r, $p, $lim ?: 100);
	$sbsmarty->assign('sb_stats_blocks', $sb_blocks);
	$sbsmarty->assign('sb_stats_compare_label', 'par rapport à la période précédente');
} elseif ($sb_page === 'visitors' || $sb_page === 'realtime') {
	// Visites une par une : journal (période) ou temps réel (30 dernières minutes)
	$sb_pg = max(1, (int) ($_GET['pg'] ?? 1));
	$sb_per = 25;
	if ($sb_page === 'realtime') {
		$sbsmarty->assign('sb_stats_live', stats_admin_live($db));
		$sbsmarty->assign('sb_stats_refresh_max', max(0, min(480, (int) stats_setting('realtime_minutes'))));
		$sb_visits = $db->all('SELECT * FROM {sb_stats_visit} WHERE `last_action` >= ? ORDER BY `last_action` DESC LIMIT 30', array(date('Y-m-d H:i:s', time() - 86400))) ?: array();
		$sb_total = count($sb_visits);
	} else {
		$range = array($p['from'] . ' 00:00:00', date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($p['to']))));
		$sb_total = (int) $db->value('SELECT COUNT(*) FROM {sb_stats_visit} WHERE `first_action` >= ? AND `first_action` < ?', $range);
		$sb_visits = $db->all('SELECT * FROM {sb_stats_visit} WHERE `first_action` >= ? AND `first_action` < ? ORDER BY `first_action` DESC ' . $db->limit($sb_per, ($sb_pg - 1) * $sb_per), $range) ?: array();
	}
	$sbsmarty->assign('sb_stats_visits', stats_admin_visits($db, $sb_visits));
	$sbsmarty->assign('sb_stats_pager', array('page' => $sb_pg, 'pages' => max(1, (int) ceil($sb_total / $sb_per)), 'total' => $sb_total));
} elseif ($sb_page !== 'settings' && $sb_page !== 'error') {
	$sb_blocks = array();
	foreach ($sb_stats_pages[$sb_page][2] as $r) $sb_blocks[] = stats_admin_block($db, $r, $p);
	$sbsmarty->assign('sb_stats_blocks', $sb_blocks);
}

$sbsmarty->assign('sb_stats_page', $sb_page);
$sbsmarty->assign('sb_stats_page_title', $sb_stats_pages[$sb_page][0] ?? 'Statistiques');
$sbsmarty->assign('sb_stats_section', $sb_stats_pages[$sb_page][1] ?? '');
$sbsmarty->assign('sb_stats_menu', $sb_menu);
$sbsmarty->assign('sb_stats_asset_v', (int) @filemtime(__DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'stats-admin.js') + (int) @filemtime(__DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'stats-admin.css'));

// ----------------------
// ASSIGN Page TITLE
// ----------------------
$sbsmarty->assign('page_title', 'Statistiques');

// ----------------------
// ASSIGN Message status
// ----------------------
$sbsmarty->assign('sb_msg_error', $sb_msg_error);
$sbsmarty->assign('sb_msg_valid', $sb_msg_valid);

?>
