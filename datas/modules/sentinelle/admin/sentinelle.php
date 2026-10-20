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
			if ($sb_bad) {
				$sb_msg_error = 'IP de confiance : adresse ou plage invalide (' . htmlspecialchars(implode(', ', array_slice($sb_bad, 0, 5))) . '). Rien n\'a été enregistré.';
			} elseif ($sb_days < 1 || $sb_days > 3650) {
				$sb_msg_error = 'Conservation du journal : de 1 à 3650 jours. Rien n\'a été enregistré.';
			} elseif (sbSettingsSave(array('sentinelle_mode' => $sb_mode, 'sentinelle_retention' => (string) $sb_days, 'sentinelle_trusted' => implode("\n", $sb_ranges)))) {
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
			false, 'Jamais bloquées par Sentinelle (vos connexions, votre bureau). Une adresse IPv4/IPv6 ou une plage CIDR par ligne. Votre adresse actuelle : <strong>' . htmlspecialchars(sbClientIp()) . '</strong>.');
		$sbform->addInput('hidden', '', array('name' => 'form_submit', 'value' => $formName));
		$sbform->addInput('reset', '', array('value' => 'Reset'));
		$sbform->closeForm();

		$sbsmarty->assign('sb_sentinelle_page', 'settings');
		$sbsmarty->assign('sb_form_id', $formName);
		$sbsmarty->assign('sb_form_submit_value', 'Modifier');
		$sbsmarty->assign('legend_add_edit', 'Réglages de Sentinelle');
		$sbsmarty->assign('sb_sentinelle_rescue_path', basename(SBUIADMIN_PATH) . '/inc/admin/sentinelle-disabled');
		break;

	case 'purge':
	case 'del':
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
		$sbsmarty->assign('sb_sentinelle_page', 'log');
		$sbsmarty->assign('sb_sentinelle_ready', $sb_sentinelle_ready);
		$sbsmarty->assign('sb_sentinelle_rows', $sb_rows);
		$sbsmarty->assign('sb_sentinelle_kpi', $sb_kpi);
		$sbsmarty->assign('sb_sentinelle_types', $sb_types);
		$sbsmarty->assign('sb_sentinelle_filter', $sb_filter);
		$sbsmarty->assign('sb_sentinelle_mode', sentinelle_mode());
		$sbsmarty->assign('sb_sentinelle_retention', sentinelle_retention());
		$sbsmarty->assign('sb_table_header', array('Date', 'IP', 'Événement', 'Gravité', 'Action', 'Côté', 'Adresse', 'Détail', ''));
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
