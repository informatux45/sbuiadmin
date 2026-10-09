<?php
/**
 * Admin Startbootstrap
 * SBUIADMIN ALTCHA (anti-robot auto-hébergé)
 *
 * Une seule API pour tout SBUIADMIN : connexion à l'administration,
 * connexion du module user, formulaires du module contact, et tout module
 * qui en a besoin. Aucun module ne garde de clé à lui.
 *
 *  - sbAltchaWidget()  : HTML du widget (et de ses scripts, une fois par page)
 *  - sbAltchaVerify()  : vérifie la réponse postée (champ "altcha"), une seule
 *                        fois par défi
 *  - sbAltchaServe()   : défi JSON, servi par altcha.php à la racine du site
 *
 * Preuve de travail PBKDF2/SHA-256 (bibliothèque altcha-org/altcha, vendor/,
 * widget assets/altcha/). Aucun appel à un service extérieur. Les deux clés
 * HMAC sont tirées au sort au premier usage et rangées chiffrées dans
 * sb_config (Réglages > ALTCHA).
 *
 * Chargé par sbconfig.php (site) et inc/sbuiadmin-config.php (admin).
 *
 * @link http://dev.informatux.com/
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

if (defined('SBUIADMIN_ALTCHA_LOADED')) return;
define('SBUIADMIN_ALTCHA_LOADED', true);

defined('SB_ALTCHA_VERSION') or define('SB_ALTCHA_VERSION', '3.3.0'); // widget (assets/altcha/)
defined('SB_ALTCHA_FIELD')   or define('SB_ALTCHA_FIELD', 'altcha');  // champ posté par le widget

/**
 * ALTCHA utilisable ? (PHP 8.1+, bibliothèque livrée dans vendor/, base
 * joignable pour les clés et l'anti-rejeu)
 */
function sbAltchaAvailable() {
	static $ok = null;
	if ($ok !== null) return $ok;
	$ok = false;
	if (PHP_VERSION_ID < 80100) return $ok;
	if (!class_exists('\\AltchaOrg\\Altcha\\Altcha')) {
		$autoload = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
		if (is_readable($autoload)) require_once $autoload;
	}
	$ok = class_exists('\\AltchaOrg\\Altcha\\Altcha') && sbSettingsDb() && sbAltchaSecrets() !== false;
	if (!$ok) error_log('SBUIADMIN ALTCHA indisponible (PHP 8.1+, vendor/altcha-org et base requis) : formulaires non protégés');
	return $ok;
}

/**
 * Les deux clés HMAC (signature du défi, signature de la clé dérivée).
 * Absentes : tirées au sort et enregistrées chiffrées - c'est aussi le
 * moment où les anciens réglages Google reCAPTCHA sont retirés de la base.
 * @return array|false
 */
function sbAltchaSecrets() {
	static $secrets = null;
	if ($secrets !== null) return $secrets;
	$hmac = sbSetting('altcha_hmac_secret');
	$key  = sbSetting('altcha_hmac_key_secret');
	if ($hmac === '' || $key === '') {
		$new = array();
		if ($hmac === '') $new['altcha_hmac_secret'] = $hmac = bin2hex(random_bytes(32));
		if ($key === '')  $new['altcha_hmac_key_secret'] = $key = bin2hex(random_bytes(32));
		if (!sbSettingsSave($new)) return $secrets = false;
		sbAltchaDropObsolete();
	}
	return $secrets = array($hmac, $key);
}

/** Retire de la base les clés Google reCAPTCHA (réglages et module contact) */
function sbAltchaDropObsolete() {
	$link = sbSettingsDb();
	if (!$link) return;
	$quote = function ($v) use ($link) { return "'" . mysqli_real_escape_string($link, $v) . "'"; };
	$obsolete = array_merge(sbSettingsObsoleteNames(), array('email_publickey', 'email_privatekey'));
	@mysqli_query($link, "DELETE FROM `" . sbSettingsTable() . "` WHERE `config` IN (" . implode(',', array_map($quote, $obsolete)) . ")");
}

/** Bornes des réglages numériques (valeur saisie hors bornes = défaut) */
function sbAltchaParams() {
	$int = function ($name, $default, $min, $max) {
		$v = sbSetting($name);
		return (ctype_digit($v) && (int) $v >= $min && (int) $v <= $max) ? (int) $v : $default;
	};
	return array(
		'cost'    => $int('altcha_cost', 2000, 100, 100000),   // itérations PBKDF2 par essai
		'counter' => $int('altcha_counter', 5000, 10, 1000000), // essais maximum (difficulté)
		'expire'  => $int('altcha_expire', 600, 60, 86400),     // validité du défi, en secondes
	);
}

function sbAltchaInstance() {
	list($hmac, $key) = sbAltchaSecrets();
	return new \AltchaOrg\Altcha\Altcha($hmac, $key);
}

/** Nouveau défi signé (tableau prêt pour json_encode) */
function sbAltchaChallenge() {
	$p = sbAltchaParams();
	// Le serveur choisit le compteur (mode déterministe) : il connaît la clé
	// dérivée et en signe le hachage, la vérification ne refait aucun calcul.
	$options = new \AltchaOrg\Altcha\CreateChallengeOptions(
		new \AltchaOrg\Altcha\Algorithm\Pbkdf2(),
		$p['cost'],
		32,
		'00',
		random_int((int) ceil($p['counter'] / 2), $p['counter']),
		null,
		null,
		time() + $p['expire']
	);
	return sbAltchaInstance()->createChallenge($options)->toArray();
}

/** Point d'entrée JSON du défi (altcha.php) */
function sbAltchaServe() {
	header('Content-Type: application/json; charset=utf-8');
	header('Cache-Control: no-store, max-age=0');
	header('X-Robots-Tag: noindex, nofollow');
	if (!sbAltchaAvailable()) {
		http_response_code(503);
		echo json_encode(array('error' => 'unavailable'));
		return;
	}
	echo json_encode(sbAltchaChallenge(), JSON_UNESCAPED_SLASHES);
}

/**
 * Vérifie la réponse du widget. Chaque défi n'est accepté qu'une fois.
 * Sans ALTCHA utilisable (voir sbAltchaAvailable()), renvoie true : le
 * blocage des tentatives reste en place, et un serveur sans la
 * bibliothèque ne doit pas fermer l'accès à l'administration.
 * La raison d'un refus est dans $GLOBALS['sb_altcha_error'] :
 * missing, invalid, expired, replay.
 * @param string|null $payload réponse du widget (défaut : $_POST['altcha'])
 * @return bool
 */
function sbAltchaVerify($payload = null) {
	$GLOBALS['sb_altcha_error'] = '';
	if (!sbAltchaAvailable()) return true;
	if ($payload === null) $payload = isset($_POST[SB_ALTCHA_FIELD]) ? $_POST[SB_ALTCHA_FIELD] : '';
	if (!is_string($payload) || $payload === '') {
		$GLOBALS['sb_altcha_error'] = 'missing';
		return false;
	}
	if (strlen($payload) > 8192) {
		$GLOBALS['sb_altcha_error'] = 'invalid';
		return false;
	}
	try {
		$options = new \AltchaOrg\Altcha\VerifySolutionOptions($payload, new \AltchaOrg\Altcha\Algorithm\Pbkdf2());
		$result  = sbAltchaInstance()->verifySolution($options);
	} catch (\Throwable $e) {
		$GLOBALS['sb_altcha_error'] = 'invalid';
		return false;
	}
	if (!$result->verified) {
		$GLOBALS['sb_altcha_error'] = $result->expired ? 'expired' : 'invalid';
		return false;
	}
	$expires = (int) ceil((float) $options->payload->challenge->parameters->expiresAt);
	if (!sbAltchaRemember((string) $options->payload->challenge->signature, $expires)) {
		$GLOBALS['sb_altcha_error'] = 'replay';
		return false;
	}
	return true;
}

/**
 * Anti-rejeu : mémorise la signature du défi jusqu'à son expiration.
 * @return bool false si déjà utilisée (ou mémorisation impossible)
 */
function sbAltchaRemember($signature, $expires) {
	$link = sbSettingsDb();
	if (!$link) return false;
	$c = sbDbConfig();
	$t = $c['prefix'] . 'sb_altcha_used';
	if (!@mysqli_query($link, "CREATE TABLE IF NOT EXISTS `$t` (
		`sig` char(64) NOT NULL,
		`expires` int unsigned NOT NULL,
		PRIMARY KEY (`sig`),
		KEY `expires` (`expires`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4")) return false;
	@mysqli_query($link, "DELETE FROM `$t` WHERE `expires` < " . time());
	$sig  = hash('sha256', $signature);
	$stmt = mysqli_prepare($link, "INSERT IGNORE INTO `$t` (`sig`, `expires`) VALUES (?, ?)");
	if (!$stmt) return false;
	mysqli_stmt_bind_param($stmt, 'si', $sig, $expires);
	$ok = mysqli_stmt_execute($stmt) && mysqli_stmt_affected_rows($stmt) === 1;
	mysqli_stmt_close($stmt);
	return $ok;
}

/**
 * URL de la racine du site, sans protocole (la page peut être servie en
 * https alors que l'URL enregistrée est en http)
 */
function sbAltchaBaseUrl() {
	$url = sbSetting('site_url');
	if ($url !== '') return rtrim(preg_replace('#^https?:#i', '', $url), '/') . '/';
	// URL du site pas encore réglée : chemin relatif depuis l'administration
	$script = isset($_SERVER['SCRIPT_FILENAME']) ? @realpath(dirname($_SERVER['SCRIPT_FILENAME'])) : false;
	return ($script && $script === @realpath(dirname(__DIR__))) ? '../' : '/';
}

/**
 * HTML du widget, à placer dans le <form> avant le bouton d'envoi. Les
 * scripts (type="module", sans code en ligne : compatible avec une CSP
 * stricte, ajouter seulement worker-src 'self') ne sont émis qu'une fois.
 * @param array $attrs attributs du widget (auto, language, display...)
 * @return string vide si ALTCHA n'est pas utilisable
 */
function sbAltchaWidget(array $attrs = array()) {
	static $assets = false;
	if (!sbAltchaAvailable()) return '';
	$base = sbAltchaBaseUrl();
	$v    = '?v=' . SB_ALTCHA_VERSION;
	$lang = (isset($_SESSION['lang']) && $_SESSION['lang'] === 'en') ? 'en' : 'fr-fr';
	$attrs = array_merge(array(
		'name'      => SB_ALTCHA_FIELD,
		'challenge' => $base . 'altcha.php',
		'auto'      => 'onsubmit', // un seul clic : vérifie, puis envoie le formulaire
		'language'  => $lang,
	), $attrs);

	$html = '';
	if (!$assets) {
		$assets = true;
		$html .= '<link rel="stylesheet" href="' . $base . 'assets/altcha/altcha.css' . $v . '">';
		$html .= '<script type="module" src="' . $base . 'assets/altcha/altcha.min.js' . $v . '"></script>';
		$html .= '<script type="module" src="' . $base . 'assets/altcha/i18n/fr-fr.js' . $v . '"></script>';
		$html .= '<script type="module" src="' . $base . 'assets/altcha/sbaltcha.js' . $v . '"></script>';
	}
	$html .= '<altcha-widget';
	foreach ($attrs as $k => $val) {
		$html .= ' ' . htmlspecialchars($k, ENT_QUOTES, 'UTF-8') . '="' . htmlspecialchars((string) $val, ENT_QUOTES, 'UTF-8') . '"';
	}
	$html .= '></altcha-widget>';
	return $html;
}
