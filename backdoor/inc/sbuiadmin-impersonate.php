<?php
/**
 * Admin Startbootstrap
 * SBUIADMIN Prendre l'identité d'un utilisateur (point 73)
 *
 * Un administrateur (Configuration > Générale > Les administrateurs) voit
 * l'administration exactement comme un utilisateur : menu, droits, modules.
 * La session bascule sur le compte visé, l'administrateur d'origine est gardé
 * à part et retrouvé sans mot de passe ni code 2FA.
 *
 *  - sbImpersonating()         : identité prise en cours ?
 *  - sbImpersonator()          : identifiant de l'administrateur d'origine
 *  - sbImpersonateStart($id)   : prend l'identité, '' ou message d'erreur
 *  - sbImpersonateStop($why)   : revient au compte d'origine (true si rendu)
 *
 * Jamais sur un autre administrateur, ni en cascade. lastlogin/logintime du
 * compte visé ne bougent pas, sa messagerie est en lecture seule.
 *
 * Chargé par inc/sbuiadmin-config.php (admin).
 *
 * @link http://dev.informatux.com/
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

if (defined('SBUIADMIN_IMPERSONATE_LOADED')) return;
define('SBUIADMIN_IMPERSONATE_LOADED', true);

/** Identité prise en cours dans cette session ? */
function sbImpersonating() {
	return !empty($_SESSION['sbuiadmin_impersonator']['user']) && is_string($_SESSION['sbuiadmin_impersonator']['user']);
}

/** Identifiant de l'administrateur d'origine ('' hors identité prise) */
function sbImpersonator() {
	return sbImpersonating() ? (string) $_SESSION['sbuiadmin_impersonator']['user'] : '';
}

/** Identifiant déclaré dans la liste des administrateurs ? */
function sbImpersonateIsAdmin($username) {
	global $sbadministrators;
	return in_array(trim((string) $username), array_map('trim', (array) $sbadministrators), true);
}

/** Trace dans le journal des connexions et dans Sentinelle */
function sbImpersonateLog($event, $admin, $target) {
	global $sbusers;
	$sbusers->updateAccessLog('impersonate', $event, $admin);
	if (function_exists('sbSecurityEvent')) {
		sbSecurityEvent('impersonate', 'warning', html_entity_decode($event, ENT_QUOTES, 'UTF-8'), 'logged', $admin . ' > ' . $target);
	}
}

/**
 * Prend l'identité de l'utilisateur $id
 * @return string '' si fait, sinon le motif du refus
 */
function sbImpersonateStart($id) {
	global $sbusers;
	$admin = isset($_SESSION['sbuiadmin_user_name']) ? trim((string) $_SESSION['sbuiadmin_user_name']) : '';
	if (sbImpersonating()) return 'Vous avez déjà pris l\'identité d\'un utilisateur.';
	if ($admin === '' || !sbImpersonateIsAdmin($admin) || sbGetCurrentUserId() === 0) return 'Réservé aux administrateurs.';

	$id  = intval($id);
	$row = ($id > 0 && sbDb()) ? sbDb()->row("SELECT username, password, active FROM {sb_users} WHERE id = ?", array($id)) : null;
	if (!$row) return 'Utilisateur introuvable.';
	$target = trim((string) $row['username']);
	if ($target === '' || $target === $admin) return 'Impossible de prendre votre propre identité.';
	if (sbImpersonateIsAdmin($target)) return 'Impossible de prendre l\'identité d\'un autre administrateur.';
	if ((string) $row['active'] === '0') return 'Ce compte est désactivé.';

	session_regenerate_id(true);
	$_SESSION['sbuiadmin_impersonator'] = array(
		'user'  => $admin,
		'hash'  => (string) $_SESSION['sbuiadmin_user_password'],
		'since' => time(),
	);
	$_SESSION['sbuiadmin_user_name']     = $target;
	$_SESSION['sbuiadmin_user_password'] = (string) $row['password'];
	// L'administrateur a déjà validé sa 2FA : pas de code pour le compte visé
	$_SESSION['sb2fa_ok'] = $target;
	unset($_SESSION['sb2fa'], $_SESSION['sb_warning_admin_default']);

	sbImpersonateLog(sprintf(SBUIADMIN_MSG_LOG_IMPERSONATE_START, $admin, $target, sbLoginLockIp()), $admin, $target);
	return '';
}

/**
 * Revient au compte de l'administrateur d'origine
 * @param string $why 'stop' (bouton), 'logout', ou motif d'un retour forcé
 * @return bool true si la session est rendue à l'administrateur
 */
function sbImpersonateStop($why = 'stop') {
	global $sbusers;
	if (!sbImpersonating()) return false;
	$orig   = $_SESSION['sbuiadmin_impersonator'];
	$admin  = (string) $orig['user'];
	$target = isset($_SESSION['sbuiadmin_user_name']) ? (string) $_SESSION['sbuiadmin_user_name'] : '';
	unset($_SESSION['sbuiadmin_impersonator']);

	$event = sprintf(SBUIADMIN_MSG_LOG_IMPERSONATE_STOP, $admin, $target, max(1, (int) ceil((time() - (int) $orig['since']) / 60)));
	if ($why !== 'stop' && $why !== 'logout') $event .= ' (' . $why . ')';
	sbImpersonateLog($event, $admin, $target);
	if ($why === 'logout') return false;

	// Compte d'origine modifié entre-temps (mot de passe, désactivation,
	// retiré des administrateurs) : pas de retour, la session tombe
	if (!$sbusers->checkSessionHash($admin, (string) $orig['hash']) || !$sbusers->checkUserIsActive($admin) || !sbImpersonateIsAdmin($admin)) {
		$_SESSION = array();
		session_regenerate_id(true);
		return false;
	}
	session_regenerate_id(true);
	$_SESSION['sbuiadmin_user_name']     = $admin;
	$_SESSION['sbuiadmin_user_password'] = (string) $orig['hash'];
	$_SESSION['sb2fa_ok']                = $admin;
	unset($_SESSION['sb2fa'], $_SESSION['sb_warning_admin_default']);
	return true;
}
