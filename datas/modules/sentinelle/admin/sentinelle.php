<?php
/**
 * Module sentinelle : administration (journal de sécurité, réglages)
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

// -----------------------
// Module URL
// -----------------------
$module_page = 'sentinelle';
$sbsmarty->assign('module_page', $module_page);
$module_url = _AM_SITE_PROTOCOL . SBUIADMIN_URL . SBUIADMIN_BASE . '?p=' . $module_page;
$sbsmarty->assign('module_url', $module_url);

// Centre de sécurité : administrateurs seulement (adresses IP, comptes visés)
if (($GLOBALS['sbuiadmin_user_type'] ?? '') !== 'admin') {
	http_response_code(403);
	$sbsmarty->display('403.tpl');
	exit;
}

$sb_msg_error = false;
$sb_msg_valid = false;
$action = is_string($_GET['a'] ?? null) ? $_GET['a'] : '';
// Chargé même si le fichier de secours désactive Sentinelle (page accessible)
require_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'guard.php');
$sb_sentinelle_ready = sentinelle_db() !== null;
$sbsmarty->assign('sb_sentinelle_rescue', is_file(sbSentinelleRescueFile()));

switch ($action) {
	case 'settings':
		// --------------------------------
		// --- Réglages
		// --------------------------------
		$formName = 'sentinelle_settings_form';
		if (!empty($_POST['form_submit'])) {
			$sb_mode = ($_POST['sentinelle_mode'] ?? '') === 'protect' ? 'protect' : 'observe';
			$sb_days = (int) ($_POST['sentinelle_retention'] ?? 90);
			list($sb_ranges, $sb_bad) = sbClientIpParseTrusted(is_string($_POST['sentinelle_trusted'] ?? null) ? $_POST['sentinelle_trusted'] : '');
			// Filtres : off / log / on (défaut on, non enregistré)
			$sb_rules = array();
			foreach (array_keys(sentinelle_rules()) as $sb_rule) {
				$sb_val = $_POST['sentinelle_rule'][$sb_rule] ?? 'on';
				if (in_array($sb_val, array('off', 'log'), true)) $sb_rules[] = $sb_rule . '=' . $sb_val;
			}
			$sb_exclude = array();
			$sb_exclude_bad = array();
			foreach (preg_split('/[\s,;]+/', is_string($_POST['sentinelle_exclude'] ?? null) ? $_POST['sentinelle_exclude'] : '') as $sb_name) {
				if ($sb_name === '') continue;
				if (preg_match('/^[A-Za-z0-9_\-\[\]]{1,64}$/', $sb_name)) $sb_exclude[$sb_name] = $sb_name; else $sb_exclude_bad[] = $sb_name;
			}
			$sb_alert_email = trim(is_string($_POST['sentinelle_alert_email'] ?? null) ? $_POST['sentinelle_alert_email'] : '');
			$sb_alert_level = in_array($_POST['sentinelle_alert_level'] ?? '', array('off', 'warning', 'critical'), true) ? $_POST['sentinelle_alert_level'] : 'critical';
			$sb_digest = ($_POST['sentinelle_digest'] ?? '') === 'on' ? '1' : '0';
			$sb_integrity_auto = ($_POST['sentinelle_integrity_auto'] ?? '') === 'on' ? '1' : '0';
			$sb_rate = (int) ($_POST['sentinelle_rate'] ?? 120);
			$sb_ab = (int) ($_POST['sentinelle_autoban'] ?? 10);
			$sb_abw = (int) ($_POST['sentinelle_autoban_window'] ?? 10);
			$sb_abd = (int) ($_POST['sentinelle_autoban_duration'] ?? 86400);
			$sb_404 = (int) ($_POST['sentinelle_404'] ?? 24);
			$sb_geo = trim(is_string($_POST['sentinelle_geo_key'] ?? null) ? $_POST['sentinelle_geo_key'] : '');
			// Préfixes surveillés : au moins deux niveaux (vendor/mpdf8/), jamais un dossier entier du site
			$sb_prefixes = array();
			$sb_prefixes_bad = array();
			foreach (preg_split('/[\r\n]+/', is_string($_POST['sentinelle_integrity_prefixes'] ?? null) ? $_POST['sentinelle_integrity_prefixes'] : '') as $sb_p) {
				$sb_p = trim(str_replace('\\', '/', $sb_p), " \t/");
				if ($sb_p === '') continue;
				if (strpos($sb_p, '..') === false && preg_match('#^[A-Za-z0-9_.\-]+(/[A-Za-z0-9_.\-]+)+$#', $sb_p) && strlen($sb_p) <= 200) $sb_prefixes[$sb_p] = $sb_p . '/'; else $sb_prefixes_bad[] = $sb_p;
			}
			if ($sb_prefixes_bad) {
				$sb_msg_error = 'Préfixes surveillés : chemin invalide ou trop large (' . htmlspecialchars(implode(', ', array_slice($sb_prefixes_bad, 0, 5))) . '), il faut au moins deux niveaux, ex. vendor/mpdf8/. Rien n\'a été enregistré.';
			} elseif ($sb_404 !== 0 && ($sb_404 < 5 || $sb_404 > 100000)) {
				$sb_msg_error = 'Balayage (404) : de 5 à 100 000 par heure (0 = inactif). Rien n\'a été enregistré.';
			} elseif ($sb_geo !== '' && !preg_match('/^[A-Za-z0-9]{8,64}$/', $sb_geo)) {
				$sb_msg_error = 'Clé ipstack : caractères inattendus. Rien n\'a été enregistré.';
			} elseif ($sb_rate !== 0 && ($sb_rate < 20 || $sb_rate > 10000)) {
				$sb_msg_error = 'Rafales : de 20 à 10 000 requêtes par minute (0 = inactif). Rien n\'a été enregistré.';
			} elseif ($sb_ab < 0 || $sb_ab > 1000 || $sb_abw < 1 || $sb_abw > 1440 || !in_array($sb_abd, array(3600, 86400, 604800, 2592000), true)) {
				$sb_msg_error = 'Bannissement automatique : réglages invalides. Rien n\'a été enregistré.';
			} elseif ($sb_alert_email !== '' && !filter_var($sb_alert_email, FILTER_VALIDATE_EMAIL)) {
				$sb_msg_error = 'Alertes : adresse e-mail invalide. Rien n\'a été enregistré.';
			} elseif ($sb_exclude_bad) {
				$sb_msg_error = 'Paramètres exclus : nom invalide (' . htmlspecialchars(implode(', ', array_slice($sb_exclude_bad, 0, 5))) . '). Rien n\'a été enregistré.';
			} elseif ($sb_bad) {
				$sb_msg_error = 'IP de confiance : adresse ou plage invalide (' . htmlspecialchars(implode(', ', array_slice($sb_bad, 0, 5))) . '). Rien n\'a été enregistré.';
			} elseif ($sb_days < 1 || $sb_days > 3650) {
				$sb_msg_error = 'Conservation du journal : de 1 à 3650 jours. Rien n\'a été enregistré.';
			} elseif (sbSettingsSave(array('sentinelle_mode' => $sb_mode, 'sentinelle_retention' => (string) $sb_days, 'sentinelle_trusted' => implode("\n", $sb_ranges),
				'sentinelle_rules' => implode(',', $sb_rules), 'sentinelle_exclude' => implode("\n", $sb_exclude),
				'sentinelle_alert_email' => $sb_alert_email, 'sentinelle_alert_level' => $sb_alert_level, 'sentinelle_digest' => $sb_digest,
				'sentinelle_integrity_auto' => $sb_integrity_auto, 'sentinelle_rate' => (string) $sb_rate, 'sentinelle_autoban' => (string) $sb_ab, 'sentinelle_autoban_window' => (string) $sb_abw, 'sentinelle_autoban_duration' => (string) $sb_abd,
				'sentinelle_404' => (string) $sb_404, 'sentinelle_geo_key' => $sb_geo, 'sentinelle_integrity_prefixes' => implode("\n", $sb_prefixes)))) {
				$sb_msg_valid = 'Réglages de Sentinelle enregistrés';
			} else {
				$sb_msg_error = 'Error: Write Error (EDIT)!';
			}
		}

		$formAction = $module_url . '&a=settings';
		$sbform->openForm(array('action' => $formAction, 'name' => $formName, 'id' => $formName, 'reloadpage' => $formAction, 'submitpage' => $formAction));
		$sbform->addRadio('Mode', array(
			array('text' => 'Observation (journal seulement)', 'value' => 'observe'),
			array('text' => 'Protection (les attaques détectées sont bloquées)', 'value' => 'protect'),
		), array('name' => 'sentinelle_mode', 'checked' => sentinelle_mode()), false, '<br>', "Commencez par l'observation : consultez le journal quelques jours, ajoutez vos adresses aux IP de confiance, puis passez en protection.");
		$sbform->addInput('text', 'Conservation du journal (jours)', array('name' => 'sentinelle_retention', 'value' => (string) sentinelle_retention(), 'placeholder' => '90'), true, false, 'Les lignes plus anciennes sont supprimées automatiquement.');
		$sbform->addTextarea('IP de confiance', htmlspecialchars(implode("\n", sentinelle_trusted())), array('name' => 'sentinelle_trusted', 'rows' => '4', 'placeholder' => sbClientIp()),
			false, 'Jamais bloquées par Sentinelle (vos connexions, votre bureau). En protection, déclarez-y aussi tout service externe qui surveille le site (disponibilité, référencement) : ceux qui se présentent comme Googlebot sont pris pour de faux robots. Une adresse IPv4/IPv6 ou une plage CIDR par ligne. Votre adresse actuelle : <strong>' . htmlspecialchars(sbClientIp()) . '</strong>.');
		// Filtres d'attaques
		$sbform->addBreak('Filtres d\'attaques');
		$sb_rule_set = array();
		foreach (explode(',', sbSetting('sentinelle_rules')) as $sb_pair) {
			$sb_kv = explode('=', trim($sb_pair), 2);
			if (count($sb_kv) === 2) $sb_rule_set[$sb_kv[0]] = $sb_kv[1];
		}
		foreach (sentinelle_rules() as $sb_rule => $sb_label) {
			$sbform->openSelect($sb_label, array('name' => 'sentinelle_rule[' . $sb_rule . ']', 'id' => 'sentinelle_rule_' . $sb_rule));
			foreach (array('on' => 'Selon le mode (bloqué en protection)', 'log' => 'Noter seulement', 'off' => 'Inactif') as $sb_val => $sb_txt) {
				$sb_args = array('value' => $sb_val);
				if (($sb_rule_set[$sb_rule] ?? 'on') === $sb_val) $sb_args['selected'] = '';
				$sbform->addOption($sb_txt, $sb_args);
			}
			$sbform->closeSelect();
		}
		$sbform->addTextarea('Paramètres jamais inspectés', htmlspecialchars(implode("\n", array_keys(sentinelle_excluded()))), array('name' => 'sentinelle_exclude', 'rows' => '3', 'placeholder' => 'message'),
			false, "Nom d'un champ ou d'un paramètre d'adresse par ligne (ex. un champ de formulaire qui accepte du code). Les mots de passe, jetons et secrets ne sont jamais inspectés. Les administrateurs connectés et les IP de confiance ne sont pas filtrés.");
		// Rafales et bannissement automatique
		$sbform->addBreak('Rafales et bannissement automatique');
		$sbform->addInput('text', 'Requêtes par minute et par IP', array('name' => 'sentinelle_rate', 'value' => (string) (int) sbSetting('sentinelle_rate', '120'), 'placeholder' => '120'), false, false,
			'Au-delà : rafale notée, et en protection IP bannie automatiquement. 0 = inactif. Les robots des moteurs (Google, Bing...) vérifiés par leur DNS ne sont pas comptés.');
		$sbform->addInput('text', 'Bannir après (événements graves)', array('name' => 'sentinelle_autoban', 'value' => (string) (int) sbSetting('sentinelle_autoban', '10'), 'placeholder' => '10'), false, false,
			'En protection : attaques bloquées, événements critiques, échecs de connexion, scanners... depuis une même IP. 0 = jamais. Les IP de confiance ne sont jamais bannies.');
		$sbform->addInput('text', 'Sur une durée de (minutes)', array('name' => 'sentinelle_autoban_window', 'value' => (string) (int) sbSetting('sentinelle_autoban_window', '10'), 'placeholder' => '10'), false, false, '');
		$sbform->openSelect('Durée du bannissement automatique', array('name' => 'sentinelle_autoban_duration', 'id' => 'sentinelle_autoban_duration'));
		foreach (array('3600' => '1 heure', '86400' => '24 heures', '604800' => '7 jours', '2592000' => '30 jours') as $sb_val => $sb_txt) {
			$sb_args = array('value' => $sb_val);
			if (sbSetting('sentinelle_autoban_duration', '86400') === (string) $sb_val) $sb_args['selected'] = '';
			$sbform->addOption($sb_txt, $sb_args);
		}
		$sbform->closeSelect('Récidive (deux bannissements automatiques en 30 jours) : durée multipliée par 30, au plus 90 jours.');
		$sbform->addInput('text', 'Balayage de fichiers : pages introuvables (404) par IP et par heure', array('name' => 'sentinelle_404', 'value' => (string) (int) sbSetting('sentinelle_404', '24'), 'placeholder' => '24'), false, false,
			'Au-delà : un événement « scanner » est noté (et compte pour le bannissement automatique en protection). 0 = inactif. Repère les scanners lents qui cherchent des fichiers un par un.');
		$sbform->addInput('text', 'Clé API ipstack (pays des adresses, facultatif)', array('name' => 'sentinelle_geo_key', 'value' => htmlspecialchars(sbSetting('sentinelle_geo_key')), 'placeholder' => 'laisser vide'), false, false,
			'Facultatif : le pays est déjà lu sans clé derrière Cloudflare (CF-IPCountry). Sinon, une clé ipstack gratuite remplit le pays des adresses (mis en cache 30 jours).');
		// Intégrité des fichiers
		$sbform->addBreak('Intégrité des fichiers');
		$sbform->addCheckbox('Analyse quotidienne', array(array('text' => 'Comparer chaque jour les fichiers à la version signée', 'name' => 'sentinelle_integrity_auto', 'checked' => sbSetting('sentinelle_integrity_auto', '1') === '1' ? '1' : '0')), '', false, '<br />', "Lancée à la première visite de l'administration de la journée, après l'affichage de la page. Un écart déclenche un événement critique (et une alerte si elle est réglée).");
		$sbform->addTextarea('Préfixes surveillés', htmlspecialchars(sbSetting('sentinelle_integrity_prefixes')), array('name' => 'sentinelle_integrity_prefixes', 'placeholder' => 'vendor/mpdf8/', 'style' => 'height:90px;font-family:monospace'), false,
			"Un dossier par ligne (au moins deux niveaux). Les nouveaux fichiers PHP dessous restent signalés, mais en alerte et non en critique : pour une bibliothèque du site qui se met à jour. Les fichiers isolés s'approuvent depuis la page Intégrité.");
		// Alertes par e-mail
		$sbform->addBreak('Alertes par e-mail');
		$sbform->addInput('text', 'Destinataire des alertes', array('name' => 'sentinelle_alert_email', 'value' => htmlspecialchars(sbSetting('sentinelle_alert_email')), 'placeholder' => 'securite@exemple.fr'), false, false, 'Vide : aucune alerte. Envoi par le SMTP de Contact > Paramètres.');
		$sbform->openSelect('Alerter pour', array('name' => 'sentinelle_alert_level', 'id' => 'sentinelle_alert_level'));
		foreach (array('critical' => 'Les événements critiques', 'warning' => 'Les alertes et événements critiques', 'off' => 'Aucune alerte immédiate') as $sb_val => $sb_txt) {
			$sb_args = array('value' => $sb_val);
			if (sbSetting('sentinelle_alert_level', 'critical') === $sb_val) $sb_args['selected'] = '';
			$sbform->addOption($sb_txt, $sb_args);
		}
		$sbform->closeSelect('Au plus un e-mail toutes les 15 minutes, qui regroupe les événements survenus depuis le précédent.');
		$sbform->addCheckbox('Résumé quotidien', array(array('text' => 'Recevoir chaque matin le résumé de la veille', 'name' => 'sentinelle_digest', 'checked' => sbSetting('sentinelle_digest') === '1' ? '1' : '0')), '', false, '<br />', "Envoyé à la première visite du site après 7 h.");
		$sbform->addInput('hidden', '', array('name' => 'form_submit', 'value' => $formName));
		$sbform->addInput('reset', '', array('value' => 'Reset'));
		$sbform->closeForm();

		$sbsmarty->assign('sb_sentinelle_page', 'settings');
		$sbsmarty->assign('sb_form_id', $formName);
		$sbsmarty->assign('sb_form_submit_value', 'Modifier');
		$sbsmarty->assign('legend_add_edit', 'Réglages de Sentinelle');
		$sbsmarty->assign('sb_sentinelle_rescue_path', basename(SBUIADMIN_PATH) . '/inc/admin/sentinelle-disabled');
		break;

	case 'approve':
	case 'unapprove':
		// --------------------------------
		// --- Fichiers propres au site : approuver ceux de l'analyse, ou tout
		// --- retirer (POST, mot de passe redemandé, tentatives comptées)
		// --------------------------------
		require_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'integrity.php');
		$sb_user = (string) ($_SESSION['sbuiadmin_user_name'] ?? '');
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			$sb_msg_error = 'Action à lancer depuis la page Intégrité.';
		} elseif (sbLoginLocked($sb_user)) {
			$sb_msg_error = 'Trop de tentatives échouées : réessayez dans quelques minutes.';
		} elseif (!$GLOBALS['sbusers']->login($sb_user, (string) ($_POST['password'] ?? ''))) {
			sbLoginFailed($sb_user);
			$sb_msg_error = 'Mot de passe incorrect : rien n\'a été changé.';
		} elseif ($action === 'unapprove') {
			if (sentinelle_integrity_unapprove_all($sb_user)) {
				sentinelle_integrity_run();
				$sb_msg_valid = 'Approbations retirées : les fichiers propres au site sont de nouveau signalés';
			} else {
				$sb_msg_error = 'Approbations : table absente, mettez à jour la base du module (Mise à jour > Modules).';
			}
		} else {
			@set_time_limit(120);
			$sb_scan = sentinelle_integrity_scan();
			$sb_n = $sb_scan['error'] === '' ? sentinelle_integrity_approve($sb_scan, $sb_user) : -1;
			if ($sb_scan['error'] !== '') $sb_msg_error = 'Analyse impossible : ' . htmlspecialchars($sb_scan['error']);
			elseif ($sb_n < 0) $sb_msg_error = 'Approbations : table absente, mettez à jour la base du module (Mise à jour > Modules).';
			else {
				sentinelle_integrity_run();
				$sb_msg_valid = $sb_n . ' fichier(s) propre(s) au site approuvé(s) : un changement de leur contenu sera signalé';
			}
		}
		$action = 'integrity';
		$sb_integrity_done = true;
		// no break : page Intégrité

	case 'integrity':
		// --------------------------------
		// --- Intégrité des fichiers (analyse à la demande : POST)
		// --------------------------------
		require_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'integrity.php');
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($sb_integrity_done)) {
			@set_time_limit(120);
			$sb_sum = sentinelle_integrity_run();
			if ($sb_sum['error'] !== '') $sb_msg_error = 'Analyse impossible : ' . htmlspecialchars($sb_sum['error']);
			elseif (array_sum(array_intersect_key($sb_sum['counts'], array('modified' => 1, 'added' => 1, 'upload' => 1, 'approved_changed' => 1)))) $sb_msg_error = 'Analyse terminée : des fichiers sont à vérifier';
			elseif ($sb_sum['counts']['watched']) $sb_msg_valid = 'Analyse terminée : ' . (int) $sb_sum['checked'] . ' fichiers conformes, ' . (int) $sb_sum['counts']['watched'] . ' PHP nouveau(x) sous un préfixe surveillé';
			else $sb_msg_valid = 'Analyse terminée : ' . (int) $sb_sum['checked'] . ' fichiers conformes à la version signée';
		}
		$sbsmarty->assign('sb_sentinelle_page', 'integrity');
		$sb_isum = json_decode(sbSetting('sentinelle_integrity'), true);
		$sbsmarty->assign('sb_integrity', $sb_isum);
		$sbsmarty->assign('sb_to_approve', (int) ($sb_isum['counts']['added'] ?? 0) + (int) ($sb_isum['counts']['watched'] ?? 0) + (int) ($sb_isum['counts']['approved_changed'] ?? 0));
		$sbsmarty->assign('sb_integrity_auto', sbSetting('sentinelle_integrity_auto', '1') === '1');
		$sbsmarty->assign('sb_integrity_prefixes', sentinelle_integrity_prefixes());
		$sbsmarty->assign('sb_integrity_approved', count(sentinelle_integrity_approved()));
		break;

	case 'advisor':
		// --------------------------------
		// --- Conseiller sécurité (score gardé pour la tuile du dashboard)
		// --------------------------------
		require_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'advisor.php');
		$sb_checks = sentinelle_advisor_checks();
		$sb_score = sentinelle_advisor_score($sb_checks);
		$sb_count = array('ok' => 0, 'warn' => 0, 'bad' => 0, 'unknown' => 0, 'info' => 0);
		$sb_by_group = array();
		foreach (sentinelle_advisor_groups() as $sb_g => $sb_label) $sb_by_group[$sb_g] = array('label' => $sb_label, 'checks' => array());
		foreach ($sb_checks as $sb_ch) {
			$sb_count[$sb_ch['status']]++;
			$sb_by_group[$sb_ch['group']]['checks'][] = $sb_ch;
		}
		// À corriger d'abord : rouges puis orange
		$sb_order = array('bad' => 0, 'warn' => 1, 'unknown' => 2, 'info' => 3, 'ok' => 4);
		foreach ($sb_by_group as &$sb_grp) usort($sb_grp['checks'], function ($a, $b) use ($sb_order) { return $sb_order[$a['status']] <=> $sb_order[$b['status']]; });
		unset($sb_grp);
		sbSettingsSave(array('sentinelle_advisor' => json_encode(array('score' => $sb_score, 'date' => date('Y-m-d H:i:s')) + $sb_count)));
		$sbsmarty->assign('sb_sentinelle_page', 'advisor');
		$sbsmarty->assign('sb_advisor_score', $sb_score);
		$sbsmarty->assign('sb_advisor_count', $sb_count);
		$sbsmarty->assign('sb_advisor_groups', $sb_by_group);
		break;

	case 'bans':
	case 'banadd':
	case 'bandel':
		// --------------------------------
		// --- IP bannies (jeton CSRF vérifié par backdoor/index.php)
		// --------------------------------
		$sb_durations = array('3600' => '1 heure', '86400' => '24 heures', '604800' => '7 jours', '2592000' => '30 jours', '0' => 'Permanent');
		if ($sb_sentinelle_ready && $action === 'banadd') {
			$sb_src = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $_GET;
			$sb_range = is_string($sb_src['ip'] ?? null) ? trim($sb_src['ip']) : '';
			$sb_dur = is_string($sb_src['d'] ?? null) && isset($sb_durations[$sb_src['d']]) ? (int) $sb_src['d'] : 86400;
			$sb_reason = is_string($sb_src['reason'] ?? null) ? trim($sb_src['reason']) : 'Banni depuis le journal';
			$sb_err = sentinelle_ban_add($sb_range, $sb_dur, $sb_reason, 'manual');
			if ($sb_err === '') $sb_msg_valid = htmlspecialchars(sbClientIpNormalizeRange($sb_range)) . ' bannie (' . $sb_durations[(string) $sb_dur] . ')';
			else $sb_msg_error = 'Bannissement refusé : ' . htmlspecialchars($sb_err);
		}
		if ($sb_sentinelle_ready && $action === 'bandel') {
			if (sentinelle_ban_remove((int) ($_GET['id'] ?? 0))) $sb_msg_valid = 'Bannissement levé';
			else $sb_msg_error = 'Bannissement introuvable';
		}
		$sb_bans = array();
		if ($sb_sentinelle_ready) {
			list($db, $table) = sentinelle_db('sb_sentinelle_ban');
			$now = date('Y-m-d H:i:s');
			$res = @mysqli_query($db, "SELECT * FROM `$table` ORDER BY (expires_at IS NULL OR expires_at > '$now') DESC, id DESC LIMIT 500");
			while ($res && ($r = mysqli_fetch_assoc($res))) {
				$r['active'] = $r['expires_at'] === null || $r['expires_at'] > $now;
				$sb_bans[] = $r;
			}
		}
		$sbsmarty->assign('sb_sentinelle_page', 'bans');
		$sbsmarty->assign('sb_sentinelle_ready', $sb_sentinelle_ready);
		$sbsmarty->assign('sb_sentinelle_bans', $sb_bans);
		$sbsmarty->assign('sb_sentinelle_durations', $sb_durations);
		$sbsmarty->assign('sb_sentinelle_my_ip', sbClientIp());
		$sbsmarty->assign('sb_table_header', array('Adresse ou plage', 'Motif', 'Origine', 'Depuis', "Jusqu'au", 'Tentatives', ''));
		break;

	case 'purge':
	case 'del':
	case 'export':
	default:
		// --------------------------------
		// --- Suppressions (jeton CSRF vérifié par backdoor/index.php)
		// --------------------------------
		if ($sb_sentinelle_ready && $action === 'del') {
			list($db, $table) = sentinelle_db();
			$sb_id = (int) ($_GET['id'] ?? 0);
			$stmt = mysqli_prepare($db, "DELETE FROM `$table` WHERE id = ?");
			mysqli_stmt_bind_param($stmt, 'i', $sb_id);
			if (mysqli_stmt_execute($stmt)) $sb_msg_valid = 'Ligne du journal supprimée';
			else $sb_msg_error = 'Error: Write Error (DEL)!';
			mysqli_stmt_close($stmt);
		}
		if ($sb_sentinelle_ready && $action === 'purge') {
			list($db, $table) = sentinelle_db();
			$sb_scope = is_string($_GET['scope'] ?? null) ? $_GET['scope'] : '';
			if ($sb_scope === 'all') {
				$sb_ok = mysqli_query($db, "DELETE FROM `$table`");
			} elseif ($sb_scope === 'old') {
				$sb_ok = sentinelle_purge_old();
			} else {
				$sb_ok = false;
			}
			if ($sb_ok) $sb_msg_valid = $sb_scope === 'all' ? 'Journal vidé' : 'Lignes anciennes supprimées';
			else $sb_msg_error = 'Purge impossible';
		}

		// --------------------------------
		// --- Journal (filtres type, gravité, IP, période)
		// --------------------------------
		$sb_types = sentinelle_types();
		$sb_filter = array(
			'type'     => isset($sb_types[$_GET['type'] ?? '']) ? $_GET['type'] : '',
			'severity' => in_array($_GET['severity'] ?? '', array('info', 'warning', 'critical'), true) ? $_GET['severity'] : '',
			'ip'       => filter_var($_GET['ip'] ?? '', FILTER_VALIDATE_IP) ? $_GET['ip'] : '',
			'days'     => in_array($_GET['days'] ?? '', array('1', '7', '30', 'all'), true) ? $_GET['days'] : '7',
		);
		$sb_rows = array();
		$sb_kpi = array('day' => 0, 'week' => 0, 'ips' => 0, 'critical' => 0);
		if ($sb_sentinelle_ready) {
			list($db, $table) = sentinelle_db();
			if (mt_rand(1, 10) === 1) sentinelle_purge_old();
			$where = array(); $types = ''; $args = array();
			foreach (array('type', 'severity', 'ip') as $f) {
				if ($sb_filter[$f] !== '') { $where[] = "`$f` = ?"; $types .= 's'; $args[] = $sb_filter[$f]; }
			}
			if ($sb_filter['days'] !== 'all') { $where[] = 'created_at >= ?'; $types .= 's'; $args[] = date('Y-m-d H:i:s', time() - (int) $sb_filter['days'] * 86400); }
			$sql = "SELECT * FROM `$table`" . ($where ? ' WHERE ' . implode(' AND ', $where) : '') . ' ORDER BY id DESC LIMIT 1000';
			$stmt = mysqli_prepare($db, $sql);
			if ($stmt) {
				if ($args) mysqli_stmt_bind_param($stmt, $types, ...$args);
				mysqli_stmt_execute($stmt);
				$res = mysqli_stmt_get_result($stmt);
				while ($res && ($r = mysqli_fetch_assoc($res))) {
					$r['type_label'] = $sb_types[$r['type']] ?? $r['type'];
					$sb_rows[] = $r;
				}
				mysqli_stmt_close($stmt);
			}
			$d1 = date('Y-m-d H:i:s', time() - 86400);
			$d7 = date('Y-m-d H:i:s', time() - 7 * 86400);
			$res = mysqli_query($db, "SELECT SUM(created_at >= '$d1') AS day, COUNT(*) AS week, COUNT(DISTINCT IF(created_at >= '$d1', ip, NULL)) AS ips, SUM(severity = 'critical' AND created_at >= '$d1') AS critical FROM `$table` WHERE created_at >= '$d7'");
			if ($res && ($r = mysqli_fetch_assoc($res))) foreach ($sb_kpi as $k => $v) $sb_kpi[$k] = (int) $r[$k];
		}
		// Export CSV (filtres en cours), avant tout affichage
		if ($action === 'export') {
			while (ob_get_level() > 0) ob_end_clean();
			header('Content-Type: text/csv; charset=UTF-8');
			header('Content-Disposition: attachment; filename="sentinelle-' . date('Y-m-d') . '.csv"');
			header('Cache-Control: no-store');
			$out = fopen('php://output', 'w');
			fwrite($out, "\xEF\xBB\xBF");
			fputcsv($out, array('Date', 'IP', 'Type', 'Événement', 'Gravité', 'Action', 'Côté', 'Méthode', 'Adresse', 'Navigateur', 'Identifiant', 'Détail'), ';', '"', '');
			foreach ($sb_rows as $r) {
				$line = array($r['created_at'], $r['ip'], $r['type'], $r['type_label'], $r['severity'], $r['action'], $r['context'], $r['method'], $r['uri'], $r['user_agent'], $r['user'], $r['detail']);
				// Une cellule commençant par = + - @ serait une formule dans un tableur
				foreach ($line as $i => $cell) if (preg_match('/^[=+\-@\t\r]/', (string) $cell)) $line[$i] = "'" . $cell;
				fputcsv($out, $line, ';', '"', '');
			}
			fclose($out);
			exit;
		}
		$sbsmarty->assign('sb_sentinelle_page', 'log');
		$sbsmarty->assign('sb_sentinelle_bans_count', count(sentinelle_bans_active()));
		$sbsmarty->assign('sb_sentinelle_my_ip', sbClientIp());
		$sbsmarty->assign('sb_sentinelle_ready', $sb_sentinelle_ready);
		$sbsmarty->assign('sb_sentinelle_rows', $sb_rows);
		$sbsmarty->assign('sb_sentinelle_kpi', $sb_kpi);
		$sbsmarty->assign('sb_sentinelle_types', $sb_types);
		$sbsmarty->assign('sb_sentinelle_filter', $sb_filter);
		$sbsmarty->assign('sb_sentinelle_mode', sentinelle_mode());
		$sbsmarty->assign('sb_sentinelle_retention', sentinelle_retention());
		$sbsmarty->assign('sb_table_header', array('Date', 'IP', 'Pays', 'Événement', 'Gravité', 'Action', 'Côté', 'Adresse', 'Détail', ''));
		break;
}

// ----------------------
// ASSIGN Page TITLE
// ----------------------
$sbsmarty->assign('page_title', 'Sentinelle');

// ----------------------
// ASSIGN Message status
// ----------------------
$sbsmarty->assign('sb_msg_error', $sb_msg_error);
$sbsmarty->assign('sb_msg_valid', $sb_msg_valid);

?>
