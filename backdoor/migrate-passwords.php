<?php
/**
 * Admin Startbootstrap
 * MIGRATION DES MOTS DE PASSE : chiffrement réversible -> password_hash()
 *
 * @link http://dev.informatux.com/
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

// -----------------------------------------------------------------------
// Depuis le 2026-10-01, encrypt()/decrypt() n'existent plus dans les
// classes users et account : seul password_verify() est accepté à la
// connexion. Un compte dont le mot de passe est encore à l'ancien format
// (chiffré avec une clé codée en dur) ne peut donc plus se connecter tant
// que ce script n'a pas tourné.
//
// - Déchiffre une dernière fois chaque ancien mot de passe (méthode copiée
//   ici, elle n'existe plus ailleurs) et le remplace par password_hash() :
//   chacun garde exactement son mot de passe.
// - Tables : sb_users (back-office) et sb_account (clients, si la table
//   existe). Colonnes password passées en VARCHAR(255).
// - Par lots (bcrypt est volontairement lent), relance automatique jusqu'à
//   la fin. Idempotent : les valeurs déjà hachées sont ignorées.
// - Une valeur indéchiffrable (ancien format mcrypt d'avant PHP 7.1, valeur
//   abîmée...) est laissée telle quelle et listée : ce compte devra recevoir
//   un nouveau mot de passe depuis Utilisateurs.
//
// ACCÈS : en ligne de commande (php backdoor/migrate-passwords.php), ou
// dans le navigateur avec une session admin déjà valide et le droit
// "modifier" sur les utilisateurs. Un admin qui s'est connecté depuis le
// 2026-07-29 est déjà migré (bascule automatique à la connexion à cette
// époque) : c'est lui qui lance la migration des autres comptes. Si AUCUN
// admin ne peut se connecter, passer par la ligne de commande.
//
// Livré avec le socle pour que chaque installation puisse migrer après
// mise à jour. Réservé aux admins (ou à la ligne de commande) ; une fois la
// migration terminée il n'a plus rien à faire et peut être supprimé.
// -----------------------------------------------------------------------

$sbmig_cli = (php_sapi_name() === 'cli');

if ($sbmig_cli) {
	chdir(__DIR__);
	$_SERVER['SERVER_NAME'] = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'localhost';
	$_SERVER['REQUEST_URI'] = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/backdoor/migrate-passwords.php';
} else {
	header('Content-Type: text/html; charset=utf-8');
	header('X-Robots-Tag: noindex');
	header('Cache-Control: no-cache, must-revalidate');
	require_once(__DIR__ . '/../inc/sbsession.php');
	session_start();
}

// ----------------------
// Amorçage du back-office (connexion BDD, classes, droits)
// ----------------------
defined('SBUIADMIN_PATH') or define('SBUIADMIN_PATH', dirname(__FILE__));
defined('SBUIADMIN_URL') or define('SBUIADMIN_URL', $_SERVER['SERVER_NAME'].dirname($_SERVER["REQUEST_URI"].'?').'/');
defined('SBUIADMIN_BASE') or define('SBUIADMIN_BASE', basename(__FILE__));
defined('SBUIADMIN_NAME') or define('SBUIADMIN_NAME', 'SBMagic');
defined('SBUIADMIN_ID') or define('SBUIADMIN_ID', 'sbuiadmin');
include 'inc/sbuiadmin-header.php';
global $sbsql, $sbusers;
@set_time_limit(120);

if (!$sbmig_cli) {
	$sbmig_user = isset($_SESSION['sbuiadmin_user_name']) ? trim($_SESSION['sbuiadmin_user_name']) : '';
	if ($sbmig_user === '' || !$sbusers->checkUserIsActive($sbmig_user) || !sbHasRight('users', 'edit')) {
		http_response_code(404);
		exit;
	}
}

define('SBMIG_BATCH', 150); // comptes hachés par appel (bcrypt ~50-100 ms chacun)

// ----------------------
// Ancienne méthode de déchiffrement (openssl aes-256-cbc), clé propre à
// chaque classe - copie de l'ancien decrypt(), branche PHP >= 7.1
// ----------------------
function sbmigLegacyDecrypt($encrypted_text, $key) {
	$encryption_key = base64_decode($key);
	$parts = explode('::', base64_decode((string)$encrypted_text), 2);
	if (count($parts) != 2) return false;
	list($encrypted_data, $iv2) = $parts;
	if (strlen($iv2) != openssl_cipher_iv_length('aes-256-cbc')) return false;
	return @openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv2);
}

function sbmigIsHashed($value) {
	$info = password_get_info((string)$value);
	return !empty($info['algo']);
}

$sbmig_tables = array(
	'users'   => array('table' => _AM_DB_PREFIX . 'sb_users',   'key' => '(D$9=h!S2olla$rS3+huY!NX', 'null' => 'NOT NULL'),
	'account' => array('table' => _AM_DB_PREFIX . 'sb_account', 'key' => '(D$9=h!S2info$rS3+huY!NX', 'null' => 'NULL DEFAULT NULL'),
);

$sbmig_report    = array();
$sbmig_remaining = 0;

do {
	$sbmig_remaining = 0;
	foreach ($sbmig_tables as $name => $t) {
		$table = $t['table'];

		// --- Table absente (sb_account n'existe que si le module clients est installé)
		if (!$sbsql->assoc($sbsql->query("SHOW TABLES LIKE '" . $sbsql->escape_string($table) . "'"))) {
			$sbmig_report[$name] = array('table absente' => $table);
			continue;
		}

		// --- Colonne password en VARCHAR(255) (recommandation PHP pour PASSWORD_DEFAULT)
		$col = $sbsql->assoc($sbsql->query("SHOW COLUMNS FROM `$table` LIKE 'password'"));
		if ($col && stripos($col['Type'], 'varchar(255)') === false) {
			$sbsql->query("ALTER TABLE `$table` MODIFY `password` VARCHAR(255) " . $t['null']);
		}

		// --- Clé primaire
		$pk = $sbsql->assoc($sbsql->query("SHOW KEYS FROM `$table` WHERE Key_name = 'PRIMARY'"));
		$pk = $pk ? $pk['Column_name'] : false;
		if (!$pk) { $sbmig_report[$name] = array('erreur' => 'clé primaire introuvable'); continue; }

		// --- Lot de mots de passe pas encore hachés (bcrypt : $2y$, argon : $argon)
		$after   = isset($sbmig_report[$name]['after']) ? $sbmig_report[$name]['after'] : (isset($_GET['after_' . $name]) ? intval($_GET['after_' . $name]) : 0);
		$where   = "password IS NOT NULL AND password <> '' AND password NOT LIKE '\$2y\$%' AND password NOT LIKE '\$argon%'";
		$rows    = $sbsql->toarray($sbsql->query("SELECT `$pk` AS pk, password FROM `$table` WHERE $where AND `$pk` > " . intval($after) . " ORDER BY `$pk` LIMIT " . SBMIG_BATCH));
		$done    = 0;
		$failed  = array();
		$last_pk = intval($after);

		foreach ((array)$rows as $row) {
			$last_pk = (int)$row['pk'];
			if (sbmigIsHashed($row['password'])) continue;
			$plain = sbmigLegacyDecrypt($row['password'], $t['key']);
			if ($plain === false || $plain === '') {
				$failed[] = $row['pk'];
				continue;
			}
			$hash = password_hash($plain, PASSWORD_DEFAULT);
			if ($sbsql->query("UPDATE `$table` SET password = '" . $sbsql->escape_string($hash) . "' WHERE `$pk` = " . (int)$row['pk'])) $done++;
		}

		$left   = $sbsql->assoc($sbsql->query("SELECT COUNT(*) AS n FROM `$table` WHERE $where AND `$pk` > " . (int)$last_pk));
		$stuck  = $sbsql->toarray($sbsql->query("SELECT `$pk` AS pk FROM `$table` WHERE $where AND `$pk` <= " . (int)$last_pk));
		$hashed = $sbsql->assoc($sbsql->query("SELECT COUNT(*) AS n FROM `$table` WHERE password LIKE '\$2y\$%' OR password LIKE '\$argon%'"));
		$total  = $sbsql->assoc($sbsql->query("SELECT COUNT(*) AS n FROM `$table`"));
		$sbmig_report[$name] = array(
			'hachés ce lot'              => $done,
			'déjà hachés'                => (int)$hashed['n'],
			'restant à traiter'          => (int)$left['n'],
			'indéchiffrables (ids)'      => implode(',', array_map(function ($r) { return $r['pk']; }, (array)$stuck)),
			'total'                      => (int)$total['n'],
			'after'                      => $last_pk,
		);
		// Lot vide alors qu'il en resterait (requête en échec) : on s'arrête
		// plutôt que de boucler sans fin.
		if (!$rows) $left['n'] = 0;
		$sbmig_remaining += (int)$left['n'];
	}
	// En ligne de commande : on enchaîne les lots dans le même processus
} while ($sbmig_cli && $sbmig_remaining > 0);

// ----------------------
// Affichage (+ relance automatique du lot suivant dans le navigateur)
// ----------------------
if ($sbmig_cli) {
	foreach ($sbmig_report as $name => $r) {
		echo "== $name\n";
		foreach ($r as $k => $v) if ($k != 'after') echo "  $k : $v\n";
	}
	echo "Migration terminée. Ce script peut maintenant être supprimé.\n";
	exit;
}

$next = '?';
foreach ($sbmig_report as $name => $r) if (isset($r['after'])) $next .= 'after_' . $name . '=' . $r['after'] . '&';

echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Migration des mots de passe</title>';
if ($sbmig_remaining > 0) echo '<meta http-equiv="refresh" content="1;url=' . htmlspecialchars(rtrim($next, '&')) . '">';
echo '</head><body style="font-family:sans-serif"><h1>Migration des mots de passe</h1>';
foreach ($sbmig_report as $name => $r) {
	echo '<h2>' . htmlspecialchars($name) . '</h2><ul>';
	foreach ($r as $k => $v) if ($k != 'after') echo '<li>' . htmlspecialchars($k) . ' : <strong>' . htmlspecialchars((string)$v) . '</strong></li>';
	echo '</ul>';
}
echo ($sbmig_remaining > 0)
	? '<p>Lot suivant dans 1 seconde… (' . $sbmig_remaining . ' restants)</p>'
	: '<p style="color:green;font-weight:bold">Migration terminée (ce script peut maintenant être supprimé). Les comptes indéchiffrables listés ci-dessus doivent recevoir un nouveau mot de passe (Utilisateurs).</p>';
echo '</body></html>';
