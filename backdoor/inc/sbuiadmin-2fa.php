<?php
/**
 * Admin Startbootstrap
 * Double authentification (2FA) du back-office
 *
 * Après le mot de passe (formulaire, cookie "Se souvenir de moi" ou module
 * front "user"), un code à 8 chiffres valable 5 minutes est envoyé à
 * l'adresse e-mail du compte. Tant qu'il n'est pas validé,
 * $_SESSION['sb2fa_ok'] n'est pas posé : sbGetCurrentUserId() renvoie 0, donc
 * aucun droit nulle part (upload, upgrade, migration compris) et index.php
 * n'affiche que la saisie du code.
 *
 * Envoi autonome : PHPMailer s'il est installé, sinon mail().
 *
 * Désactivée par défaut (installation neuve : aucun envoi d'e-mails
 * paramétré). S'active dans Configuration, et seulement après avoir saisi un
 * code de test reçu par e-mail : un envoi qui ne fonctionne pas ne peut plus
 * bloquer l'accès à l'administration.
 *
 * SECOURS : si l'envoi d'e-mails ne fonctionne pas sur un serveur, créer le
 * fichier backdoor/inc/admin/2fa-disabled désactive la 2FA (voir
 * sb2faDisabled() dans sbuiadmin-rights.php). Il faut déjà un accès aux
 * fichiers du serveur pour le créer : ce n'est pas une porte dérobée.
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// Blocking direct access to plugin      -=
// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
defined('SBUIADMIN_PATH') or die('Are you crazy!');

defined('SB2FA_CODE_LENGTH')  or define('SB2FA_CODE_LENGTH', 8);   // Nombre de chiffres du code
defined('SB2FA_TTL')          or define('SB2FA_TTL', 300);         // Validité du code (secondes)
defined('SB2FA_MAX_ATTEMPTS') or define('SB2FA_MAX_ATTEMPTS', 5);  // Essais avant déconnexion
defined('SB2FA_RESEND_DELAY') or define('SB2FA_RESEND_DELAY', 60); // Délai minimum entre deux envois (secondes)
defined('SB2FA_MAX_SENDS')    or define('SB2FA_MAX_SENDS', 4);     // Envois maximum par tentative de connexion

/**
 * Masque une adresse e-mail pour l'affichage (pa****@ex*****.fr)
 */
function sb2faMaskEmail($email) {
	list($local, $domain) = array_pad(explode('@', $email, 2), 2, '');
	$dot  = strrpos($domain, '.');
	$host = ($dot !== false) ? substr($domain, 0, $dot) : $domain;
	$tld  = ($dot !== false) ? substr($domain, $dot) : '';
	$mask = function ($s, $keep) { return substr($s, 0, $keep) . str_repeat('*', max(strlen($s) - $keep, 3)); };
	return $mask($local, 2) . '@' . $mask($host, 2) . $tld;
}

/**
 * Ferme complètement la session et le jeton "Se souvenir de moi"
 */
function sb2faResetSession() {
	global $sbusers;
	if (isset($_COOKIE['sbuiadmin_remember']) && $_COOKIE['sbuiadmin_remember'] != '') {
		$sbusers->deleteRememberTokenBySelector($_COOKIE['sbuiadmin_remember']);
	}
	if (function_exists('sbSetAuthCookie')) sbSetAuthCookie('sbuiadmin_remember', '', time() - 3600);
	$_SESSION = array();
	session_regenerate_id(true);
}

/**
 * Adresse d'expédition : celle du site public (SBFROMEMAIL dans sbconfig.php,
 * qui n'est pas chargé au back-office - on lit la constante sans l'inclure),
 * sinon l'identifiant SMTP, sinon noreply@<domaine>.
 */
function sb2faSender() {
	if (defined('SBFROMEMAIL') && filter_var(SBFROMEMAIL, FILTER_VALIDATE_EMAIL)) return SBFROMEMAIL;
	$config = @file_get_contents(dirname(SBUIADMIN_PATH) . '/sbconfig.php');
	if ($config && preg_match("/define\\(\\s*'SBFROMEMAIL'\\s*,\\s*'([^']+)'/", $config, $m) && filter_var($m[1], FILTER_VALIDATE_EMAIL)) {
		return $m[1];
	}
	$smtp_user = (string)sbGetConfig('email_smtp_username');
	if (filter_var($smtp_user, FILTER_VALIDATE_EMAIL)) return $smtp_user;
	$host = preg_replace('/^www\./', '', isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'localhost');
	return 'noreply@' . $host;
}

/**
 * Envoi de l'e-mail du code. PHPMailer (avec la config SMTP de
 * Configuration > Contact), livré dans vendor/. mail() seulement en
 * secours, si vendor/ manque sur le serveur.
 *
 * @return bool
 */
function sb2faMail($to, $to_name, $subject, $html) {
	$sender    = sb2faSender();
	$site      = defined('_AM_SITE_TITLE') ? _AM_SITE_TITLE : 'Administration';
	$mail      = sbMailer(); // PHPMailer (vendor/) + SMTP de Configuration > Contact

	if ($mail) {
		$mail->setFrom($sender, $site);
		$mail->addAddress($to, $to_name);
		$mail->Subject = $subject;
		$mail->msgHTML($html);
		return (bool)$mail->send();
	}

	$headers = "MIME-Version: 1.0\r\n"
	         . "Content-Type: text/html; charset=UTF-8\r\n"
	         . "From: =?UTF-8?B?" . base64_encode($site) . "?= <" . $sender . ">\r\n";
	return @mail($to, '=?UTF-8?B?' . base64_encode($subject) . '?=', $html, $headers);
}

/**
 * Génère un nouveau code et l'envoie à l'adresse du compte
 *
 * @return string 'ok' | 'noemail' | 'mailfail'
 */
function sb2faSendCode($username) {
	global $sbusers;

	$email = trim(html_entity_decode((string)$sbusers->getUserInfo($username, 'email'), ENT_QUOTES, 'UTF-8'));
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return 'noemail';

	$code    = sb2faNewCode();
	$pending = (isset($_SESSION['sb2fa']) && $_SESSION['sb2fa']['user'] === $username) ? $_SESSION['sb2fa'] : null;

	$_SESSION['sb2fa'] = array(
		'user'     => $username,
		'hash'     => password_hash($code, PASSWORD_DEFAULT),
		'expires'  => time() + SB2FA_TTL,
		'attempts' => 0,
		'sent_at'  => time(),
		'sends'    => ($pending ? $pending['sends'] : 0) + 1,
		'email'    => $email,
	);

	$site = defined('_AM_SITE_TITLE') ? _AM_SITE_TITLE : 'Administration';
	return sb2faMail($email, $username, 'Code de connexion - ' . $site, sb2faCodeHtml($code, "Voici votre code de connexion à l'administration du site")) ? 'ok' : 'mailfail';
}

/**
 * Nouveau code aléatoire de SB2FA_CODE_LENGTH chiffres
 */
function sb2faNewCode() {
	return str_pad((string)random_int(0, (int)str_repeat('9', SB2FA_CODE_LENGTH)), SB2FA_CODE_LENGTH, '0', STR_PAD_LEFT);
}

/**
 * Corps HTML de l'e-mail d'un code
 */
function sb2faCodeHtml($code, $intro) {
	$minutes = (int)round(SB2FA_TTL / 60);
	return '<p>Bonjour,</p>'
	     . '<p>' . htmlspecialchars($intro) . '&nbsp;:</p>'
	     . '<p style="font-size:28px;font-weight:bold;letter-spacing:6px;font-family:monospace;">' . $code . '</p>'
	     . '<p>Ce code est valable ' . $minutes . '&nbsp;minutes.</p>'
	     . '<p>Demande effectuée depuis l\'adresse IP ' . htmlspecialchars($_SERVER['REMOTE_ADDR']) . ' le ' . date('d/m/Y') . ' à ' . date('H:i') . '.<br>'
	     . 'Si vous n\'êtes pas à l\'origine de cette demande, changez immédiatement votre mot de passe et prévenez l\'administrateur du site.</p>';
}

/**
 * Activation (Configuration) : envoie un code de test à l'adresse du compte
 * connecté. La 2FA n'est activée qu'une fois ce code saisi
 * (sb2faActivationCheck), preuve que l'envoi d'e-mails fonctionne.
 *
 * @return string 'ok' | 'noemail' | 'mailfail' | 'wait'
 */
function sb2faActivationStart($username) {
	global $sbusers;

	$email = trim(html_entity_decode((string)$sbusers->getUserInfo($username, 'email'), ENT_QUOTES, 'UTF-8'));
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return 'noemail';
	$pending = $_SESSION['sb2fa_activation'] ?? null;
	if ($pending && $pending['user'] === $username && time() - $pending['sent_at'] < SB2FA_RESEND_DELAY) return 'wait';

	$code = sb2faNewCode();
	$_SESSION['sb2fa_activation'] = array(
		'user'     => $username,
		'hash'     => password_hash($code, PASSWORD_DEFAULT),
		'expires'  => time() + SB2FA_TTL,
		'attempts' => 0,
		'sent_at'  => time(),
		'email'    => $email,
	);
	$site = defined('_AM_SITE_TITLE') ? _AM_SITE_TITLE : 'Administration';
	if (sb2faMail($email, $username, 'Activation de la double authentification - ' . $site, sb2faCodeHtml($code, "Voici le code qui active la double authentification de l'administration du site"))) return 'ok';
	unset($_SESSION['sb2fa_activation']);
	return 'mailfail';
}

/**
 * Vérifie le code de test d'activation
 *
 * @return string 'ok' | 'none' | 'expired' | 'wrong' | 'locked'
 */
function sb2faActivationCheck($username, $code) {
	$pending = $_SESSION['sb2fa_activation'] ?? null;
	if (!$pending || $pending['user'] !== $username) return 'none';
	$code = preg_replace('/\D/', '', (string)$code);
	$_SESSION['sb2fa_activation']['attempts']++;
	if (time() > $pending['expires']) {
		unset($_SESSION['sb2fa_activation']);
		return 'expired';
	}
	if (strlen($code) == SB2FA_CODE_LENGTH && password_verify($code, $pending['hash'])) {
		unset($_SESSION['sb2fa_activation']);
		return 'ok';
	}
	if ($_SESSION['sb2fa_activation']['attempts'] >= SB2FA_MAX_ATTEMPTS) {
		unset($_SESSION['sb2fa_activation']);
		return 'locked';
	}
	return 'wrong';
}

/**
 * Porte d'entrée 2FA : à appeler quand une session existe mais n'a pas
 * validé le code. Affiche la saisie et termine le script, ou valide le code
 * et redirige vers le tableau de bord.
 */
function sb2faGate() {
	global $sbsmarty, $sbusers;

	$username = (string)$_SESSION['sbuiadmin_user_name'];
	$ip       = $_SERVER['REMOTE_ADDR'];
	$pending  = (isset($_SESSION['sb2fa']) && $_SESSION['sb2fa']['user'] === $username) ? $_SESSION['sb2fa'] : null;
	$error    = '';
	$info     = '';

	if (!$pending) {
		// --- Première étape : envoi du code
		$status = sb2faSendCode($username);
		if ($status != 'ok') {
			$sbusers->updateAccessLog('error', sprintf("Double authentification impossible pour [%s] depuis [%s] : %s", $username, $ip, ($status == 'noemail') ? 'aucune adresse e-mail valide' : "échec de l'envoi du code"), $username);
			sb2faResetSession();
			$sbsmarty->assign('sbuiadmin_access_code', ($status == 'noemail') ? 'E5' : 'E6');
			$sbsmarty->display('system/login.tpl');
			exit;
		}
		$info = 'Un code de connexion vient de vous être envoyé.';

	} elseif (isset($_POST['sb2fa_code'])) {
		// --- Vérification du code saisi
		$code = preg_replace('/\D/', '', (string)$_POST['sb2fa_code']);
		$_SESSION['sb2fa']['attempts']++;

		if (time() > $pending['expires']) {
			$error = 'Ce code a expiré. Demandez un nouveau code.';
		} elseif (strlen($code) == SB2FA_CODE_LENGTH && password_verify($code, $pending['hash'])) {
			// --- Code valide : la session devient pleinement authentifiée
			unset($_SESSION['sb2fa']);
			session_regenerate_id(true);
			$_SESSION['sb2fa_ok'] = $username;
			$sbusers->updateAccessLog('login', sprintf("Double authentification validée pour [%s] depuis [%s]", $username, $ip), $username);
			header('Location: index.php');
			exit;
		} elseif ($_SESSION['sb2fa']['attempts'] >= SB2FA_MAX_ATTEMPTS) {
			if (function_exists('sbLoginFailed')) sbLoginFailed($username); // blocage temporaire
			$sbusers->updateAccessLog('error', sprintf("Double authentification : trop d'essais pour [%s] depuis [%s]", $username, $ip), $username);
			sb2faResetSession();
			$sbsmarty->assign('sbuiadmin_access_code', 'E7');
			$sbsmarty->display('system/login.tpl');
			exit;
		} else {
			if (function_exists('sbLoginFailed')) sbLoginFailed($username); // blocage temporaire
			$left  = SB2FA_MAX_ATTEMPTS - $_SESSION['sb2fa']['attempts'];
			$error = "Code incorrect. Il vous reste $left essai" . ($left > 1 ? 's' : '') . '.';
			$sbusers->updateAccessLog('error', sprintf("Double authentification : code incorrect pour [%s] depuis [%s]", $username, $ip), $username);
		}

	} elseif (isset($_GET['sb2fa']) && $_GET['sb2fa'] == 'resend') {
		// --- Nouvel envoi demandé
		$wait = SB2FA_RESEND_DELAY - (time() - $pending['sent_at']);
		if ($pending['sends'] >= SB2FA_MAX_SENDS) {
			$error = 'Nombre maximum d\'envois atteint. Annulez puis reconnectez-vous.';
		} elseif ($wait > 0) {
			$error = "Merci de patienter encore $wait secondes avant de redemander un code.";
		} elseif (sb2faSendCode($username) == 'ok') {
			$info = 'Un nouveau code vient de vous être envoyé.';
		} else {
			$error = "L'envoi du code a échoué, réessayez dans un instant.";
		}
	}

	$sbsmarty->assign('sb2fa_email', sb2faMaskEmail($_SESSION['sb2fa']['email']));
	$sbsmarty->assign('sb2fa_minutes', (int)round(SB2FA_TTL / 60));
	$sbsmarty->assign('sb2fa_length', SB2FA_CODE_LENGTH);
	$sbsmarty->assign('sb2fa_error', $error);
	$sbsmarty->assign('sb2fa_info', $info);
	$sbsmarty->display('system/login2fa.tpl');
	exit;
}
