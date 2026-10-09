<?php
/**
 * Admin Startbootstrap
 * SBUIADMIN Réglages (base de données)
 *
 * Les réglages vivent dans la table {préfixe}sb_config, avec les autres
 * entrées de configuration du CMS (une ligne par réglage, colonne updated_at
 * datée par MySQL à chaque modification). Seuls les accès à
 * la base (et la clé de chiffrement des secrets) restent hors base, dans
 * sbdbconfig.php, cherché comme wp-config.php : un niveau au-dessus du site
 * d'abord, puis à la racine du site. Les variables d'environnement
 * SBUIADMIN_DB_* passent avant le fichier.
 *
 * L'ancien backdoor/inc/admin/settings.txt (positionnel) est migré
 * automatiquement au premier chargement, puis vidé (copie de sauvegarde
 * posée à côté de sbdbconfig.php). La table sb_settings de la 4.11 est
 * fusionnée dans sb_config puis supprimée (sbSettingsMergeOldTable()).
 *
 * Chargé par sbconfig.php (site) et inc/sbuiadmin-config.php (admin).
 *
 * @link http://dev.informatux.com/
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

if (defined('SBUIADMIN_SETTINGS_LOADED')) return;
define('SBUIADMIN_SETTINGS_LOADED', true);

// Permet l'include de sbdbconfig.php (appelé directement, il ne fait rien)
defined('SBUIADMIN_DBCONFIG') or define('SBUIADMIN_DBCONFIG', true);

defined('SB_SETTINGS_SITE_ROOT')  or define('SB_SETTINGS_SITE_ROOT', dirname(__DIR__, 2));
defined('SB_SETTINGS_LEGACY_FILE') or define('SB_SETTINGS_LEGACY_FILE', __DIR__ . '/admin/settings.txt');
defined('SB_SETTINGS_ENC_PREFIX') or define('SB_SETTINGS_ENC_PREFIX', 'sbenc1:');

/**
 * Ancienne position dans settings.txt => nom du réglage.
 * Les positions 2-5 et 21 (accès base) vont dans sbdbconfig.php.
 * 19, 20 et 22 (Google reCAPTCHA) ne sont pas reprises : remplacé par ALTCHA.
 */
function sbSettingsLegacyMap() {
	return array(
		0  => 'customer_name',
		1  => 'administrators',
		6  => 'medias_dir',
		7  => 'upload_size_limit',
		8  => 'modules',
		9  => 'debug_admin',
		10 => 'debug_form',
		11 => 'debug_smarty_admin',
		12 => 'upload_exts',
		13 => 'medias_url',
		14 => 'upload_item_limit',
		15 => 'site_url',
		16 => 'sandbox',
		17 => 'cms',
		18 => 'scaling_maxsize',
		23 => 'upgrade_mode',
		24 => 'maintenance',
		25 => 'debug_front',
		26 => 'debug_smarty_front',
		27 => 'smarty_force_compile',
		28 => 'rewrite_url',
		29 => 'smarty_caching',
		30 => 'smarty_cache_lifetime',
		31 => 'medias_per_page',
		32 => 'flood_enabled',
		33 => 'flood_expiration',
		34 => 'flood_login_delay',
		35 => 'toast_duration',
		36 => 'pagebuilder_modules',
	);
}

/** Valeurs par défaut (réglage absent de la table) */
function sbSettingsDefaults() {
	return array(
		'customer_name'         => '',
		'administrators'        => '',
		'medias_dir'            => '../upload',
		'upload_size_limit'     => '10M',
		'modules'               => '',
		'debug_admin'           => '0',
		'debug_form'            => '0',
		'debug_smarty_admin'    => '0',
		'upload_exts'           => 'jpg,jpeg,png,gif,webp,pdf,mp4',
		'medias_url'            => '',
		'upload_item_limit'     => '20',
		'site_url'              => '',
		'sandbox'               => '0',
		'cms'                   => '1',
		'scaling_maxsize'       => '1024',
		'altcha_login'          => '1',
		'altcha_hmac_secret'    => '',
		'altcha_hmac_key_secret' => '',
		'altcha_cost'           => '2000',
		'altcha_counter'        => '5000',
		'altcha_expire'         => '600',
		'login_lock_enabled'    => '1',
		'login_lock_window'     => '15',
		'login_lock_duration'   => '15',
		'login_lock_max_login'  => '10',
		'login_lock_max_ip'     => '20',
		'upgrade_mode'          => '0',
		'maintenance'           => '0',
		'debug_front'           => '0',
		'debug_smarty_front'    => '0',
		'smarty_force_compile'  => '0',
		'rewrite_url'           => '0',
		'smarty_caching'        => '0',
		'smarty_cache_lifetime' => '120',
		'medias_per_page'       => '20',
		'flood_enabled'         => '0',
		'flood_expiration'      => '86400',
		'flood_login_delay'     => '4',
		'toast_duration'        => '7',
		'pagebuilder_modules'   => '',
	);
}

/** Réglages stockés chiffrés dans sb_config */
function sbSettingsSecretNames() {
	return array('altcha_hmac_secret', 'altcha_hmac_key_secret');
}

/** Toutes les entrées de sb_config stockées chiffrées (sbGetConfig() les déchiffre) */
function sbConfigSecretNames() {
	return array_merge(array('email_smtp_password'), sbSettingsSecretNames());
}

/** Anciens réglages Google reCAPTCHA, supprimés par sbAltchaSecrets() */
function sbSettingsObsoleteNames() {
	return array('recaptcha_public', 'recaptcha_secret', 'captcha_mode');
}

// -----------------------------------------------------------------------
// sbdbconfig.php
// -----------------------------------------------------------------------

/** Identifiant du site (chemin réel de sa racine), inscrit dans sbdbconfig.php */
function sbDbConfigSiteId() {
	$r = @realpath(SB_SETTINGS_SITE_ROOT);
	return $r ? $r : SB_SETTINGS_SITE_ROOT;
}

/**
 * Dossiers où chercher sbdbconfig.php, du plus sûr au moins sûr :
 * array(dossier, partagé avec d'autres sites ?, inscriptible par l'installation ?)
 *  1. private/ du compte (à côté de la racine web, ex. ISPConfig w3/private)
 *  2. dossiers entre le site et la racine web (ex. web/ pour web/sbuiadmin)
 *  3. dossier du compte, au-dessus de la racine web (ex. w3/)
 *  4. autres dossiers du compte (ex. w3/conf), en lecture seulement
 *  5. racine du site, puis son inc/ et celui de l'administration
 *     (lecture seulement : emplacements où l'on peut le déplacer à la main)
 * Jamais les dossiers voisins du site dans la racine web : ce sont d'autres sites.
 */
function sbDbConfigDirs() {
	$root = sbDbConfigSiteId();
	$docroot = !empty($_SERVER['DOCUMENT_ROOT']) ? @realpath($_SERVER['DOCUMENT_ROOT']) : false;
	if (!$docroot || ($root !== $docroot && strpos($root . DIRECTORY_SEPARATOR, $docroot . DIRECTORY_SEPARATOR) !== 0)) {
		$docroot = dirname($root); // hors contexte web (CLI) : site supposé dans un sous-dossier
	}
	$account = dirname($docroot);
	$dirs = array();
	if (@is_dir($account . DIRECTORY_SEPARATOR . 'private')) $dirs[] = array($account . DIRECTORY_SEPARATOR . 'private', true, true);
	for ($d = dirname($root); $d !== $account && strlen($d) >= strlen($docroot) && $d !== dirname($d); $d = dirname($d)) {
		$dirs[] = array($d, true, true);
	}
	$dirs[] = array($account, true, true);
	$others = @glob($account . DIRECTORY_SEPARATOR . '*', GLOB_ONLYDIR);
	foreach (is_array($others) ? $others : array() as $d) {
		$real = @realpath($d);
		if (!$real || $real === $docroot || basename($real) === 'private' || strpos($docroot . DIRECTORY_SEPARATOR, $real . DIRECTORY_SEPARATOR) === 0) continue;
		$dirs[] = array($real, true, false);
	}
	$dirs[] = array($root, false, true);
	$dirs[] = array($root . DIRECTORY_SEPARATOR . 'inc', false, false);
	$admin_inc = @realpath(__DIR__);
	if ($admin_inc && $admin_inc !== $root . DIRECTORY_SEPARATOR . 'inc') $dirs[] = array($admin_inc, false, false);
	return $dirs;
}

/** Fichiers candidats, dans l'ordre de recherche */
function sbDbConfigCandidates() {
	$candidates = array();
	$env = getenv('SBUIADMIN_DBCONFIG');
	if ($env) $candidates[] = $env;
	$own = 'sbdbconfig-' . basename(sbDbConfigSiteId()) . '.php';
	foreach (sbDbConfigDirs() as $dir) {
		// Dossier partagé : le nom propre au site d'abord (plusieurs sites
		// sur un même compte), puis le nom générique
		if ($dir[1]) $candidates[] = $dir[0] . DIRECTORY_SEPARATOR . $own;
		$candidates[] = $dir[0] . DIRECTORY_SEPARATOR . 'sbdbconfig.php';
	}
	return array_values(array_unique($candidates));
}

/** Contenu d'un sbdbconfig.php (tableau) ou false */
function sbDbConfigRead($file) {
	if (!@is_file($file) || !@is_readable($file)) return false;
	$data = include $file;
	return is_array($data) ? $data : false;
}

/** Ce fichier appartient-il à ce site ? (sans marque : oui, fichiers d'avant la marque) */
function sbDbConfigIsOurs($data) {
	return is_array($data) && (empty($data['site']) || $data['site'] === sbDbConfigSiteId());
}

/** Chemin du sbdbconfig.php de ce site, ou false */
function sbDbConfigPath($reset = false) {
	static $path = null;
	if ($path !== null && !$reset) return $path;
	$path = false;
	foreach (sbDbConfigCandidates() as $file) {
		if (sbDbConfigIsOurs(sbDbConfigRead($file))) { $path = $file; break; }
	}
	return $path;
}
function sbDbConfigPathReset() {
	return sbDbConfigPath(true);
}

/**
 * Accès base + clé : variables d'environnement d'abord, puis sbdbconfig.php,
 * puis (installation pas encore migrée) l'ancien settings.txt.
 * @return array host, name, user, password, prefix, key, source
 */
function sbDbConfig($reload = false) {
	static $config = null;
	if ($config !== null && !$reload) return $config;

	$config = array('host' => '', 'name' => '', 'user' => '', 'password' => '', 'prefix' => '', 'key' => '', 'source' => 'none');

	$file = $reload ? sbDbConfigPathReset() : sbDbConfigPath();
	if ($file) {
		$data = sbDbConfigRead($file);
		if (is_array($data)) {
			foreach (array('host', 'name', 'user', 'password', 'prefix', 'key') as $k) {
				if (isset($data[$k])) $config[$k] = (string) $data[$k];
			}
			$config['source'] = $file;
		}
	}

	if (getenv('SBUIADMIN_DB_HOST') !== false && getenv('SBUIADMIN_DB_NAME') !== false && getenv('SBUIADMIN_DB_USER') !== false) {
		$config['host']     = (string) getenv('SBUIADMIN_DB_HOST');
		$config['name']     = (string) getenv('SBUIADMIN_DB_NAME');
		$config['user']     = (string) getenv('SBUIADMIN_DB_USER');
		$config['password'] = (string) getenv('SBUIADMIN_DB_PASSWORD');
		$config['prefix']   = (string) getenv('SBUIADMIN_DB_PREFIX');
		$config['source']   = 'env';
	}
	if (getenv('SBUIADMIN_SECRET_KEY') !== false) {
		$config['key'] = (string) getenv('SBUIADMIN_SECRET_KEY');
	}

	if ($config['source'] === 'none') {
		$legacy = sbSettingsLegacyLines();
		if ($legacy && trim($legacy[2]) !== '') {
			$config['key']      = '';
			$config['host']     = trim($legacy[2]);
			$config['name']     = trim($legacy[3]);
			$config['user']     = trim($legacy[4]);
			$config['password'] = trim($legacy[5]);
			$config['prefix']   = isset($legacy[21]) ? trim($legacy[21]) : '';
			$config['source']   = 'legacy';
		}
	}
	return $config;
}

/** Le chemin donné est-il servi par le web (sous la racine du site) ? */
function sbDbConfigIsInsideSite($file) {
	$real = @realpath($file);
	$root = @realpath(SB_SETTINGS_SITE_ROOT);
	return $real && $root && strpos($real, $root . DIRECTORY_SEPARATOR) === 0;
}

/** Contenu PHP de sbdbconfig.php */
function sbDbConfigRender(array $c) {
	$out  = "<?php\n";
	$out .= "// SBUIADMIN - accès à la base de données et clé de chiffrement des secrets.\n";
	$out .= "// Généré le " . date('Y-m-d H:i:s') . ". Ne jamais commiter ni partager ce fichier.\n";
	$out .= "// Appelé directement par le web, il ne renvoie rien.\n";
	$out .= "if (!defined('SBUIADMIN_DBCONFIG')) { http_response_code(404); exit; }\n";
	$out .= "return array(\n";
	foreach (array('host', 'name', 'user', 'password', 'prefix', 'key') as $k) {
		$out .= "\t" . var_export($k, true) . ' => ' . var_export(isset($c[$k]) ? (string) $c[$k] : '', true) . ",\n";
	}
	// Site propriétaire : un autre site du même compte ignore ce fichier
	$out .= "\t'site' => " . var_export(sbDbConfigSiteId(), true) . ",\n";
	$out .= ");\n";
	return $out;
}

/**
 * Écrit sbdbconfig.php au premier emplacement inscriptible (au-dessus du site
 * d'abord). Une clé est générée si absente.
 * @return string|false chemin écrit
 */
function sbDbConfigWrite(array $c) {
	if (empty($c['key'])) $c['key'] = base64_encode(random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES));
	$content = sbDbConfigRender($c);
	$targets = array();
	// Fichier déjà en place pour ce site : on le réécrit là où il est
	$current = sbDbConfigPath(true);
	if ($current) $targets[] = $current;
	$env = getenv('SBUIADMIN_DBCONFIG');
	if ($env) $targets[] = $env;
	foreach (sbDbConfigDirs() as $dir) {
		if (!$dir[2]) continue;
		$generic = $dir[0] . DIRECTORY_SEPARATOR . 'sbdbconfig.php';
		// Nom générique déjà pris par un autre site : nom propre à ce site
		$targets[] = ($dir[1] && @file_exists($generic) && !sbDbConfigIsOurs(sbDbConfigRead($generic)))
			? $dir[0] . DIRECTORY_SEPARATOR . 'sbdbconfig-' . basename(sbDbConfigSiteId()) . '.php'
			: $generic;
	}
	foreach (array_unique($targets) as $file) {
		$dir = dirname($file);
		$exists = @file_exists($file);
		if ($exists ? !@is_writable($file) : (!@is_dir($dir) || !@is_writable($dir))) continue;
		if ($exists && !sbDbConfigIsOurs(sbDbConfigRead($file))) continue;
		if (@file_put_contents($file, $content, LOCK_EX) === strlen($content)) {
			@chmod($file, 0640);
			sbDbConfigPath(true);
			return $file;
		}
	}
	return false;
}

// -----------------------------------------------------------------------
// Chiffrement des secrets (sodium)
// -----------------------------------------------------------------------

function sbSecretKey() {
	$c = sbDbConfig();
	$key = base64_decode($c['key'], true);
	return ($key !== false && strlen($key) === SODIUM_CRYPTO_SECRETBOX_KEYBYTES) ? $key : false;
}

/** Chiffre une valeur (vide = vide). Sans clé, la valeur reste en clair. */
function sbSecretSeal($value) {
	$value = (string) $value;
	if ($value === '' || strpos($value, SB_SETTINGS_ENC_PREFIX) === 0) return $value;
	$key = sbSecretKey();
	if (!$key) return $value;
	$nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
	return SB_SETTINGS_ENC_PREFIX . base64_encode($nonce . sodium_crypto_secretbox($value, $nonce, $key));
}

/** Déchiffre une valeur ; une valeur en clair (ancienne) est rendue telle quelle. */
function sbSecretOpen($value) {
	$value = (string) $value;
	if (strpos($value, SB_SETTINGS_ENC_PREFIX) !== 0) return $value;
	$key = sbSecretKey();
	$raw = base64_decode(substr($value, strlen(SB_SETTINGS_ENC_PREFIX)), true);
	if (!$key || $raw === false || strlen($raw) <= SODIUM_CRYPTO_SECRETBOX_NONCEBYTES) return '';
	$plain = sodium_crypto_secretbox_open(substr($raw, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES), substr($raw, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES), $key);
	return ($plain === false) ? '' : $plain;
}

// -----------------------------------------------------------------------
// Base de données
// -----------------------------------------------------------------------

/**
 * Hôte tel que saisi : "hôte", "hôte:port" ou "hôte:/chemin/socket".
 * @return array [hôte, port, socket|false]
 */
function sbDbHostParts($host) {
	$host = (string) $host;
	if (strpos($host, ':') === false) return array($host, 3306, false);
	list($h, $extra) = explode(':', $host, 2);
	return preg_match('/^\d+$/', $extra) ? array($h, (int) $extra, false) : array($h, 3306, $extra);
}

/** Connexion mysqli dédiée aux réglages (null si impossible) */
function sbSettingsDb($reset = false) {
	static $link = false;
	if ($reset && $link) @mysqli_close($link);
	if ($link !== false && !$reset) return $link;
	$link = null;
	$c = sbDbConfig();
	if ($c['host'] === '' || $c['name'] === '' || !function_exists('mysqli_connect')) return $link;
	mysqli_report(MYSQLI_REPORT_OFF);
	list($host, $port, $socket) = sbDbHostParts($c['host']);
	$l = @mysqli_connect($host, $c['user'], $c['password'], $c['name'], $port, $socket ?: null);
	if ($l) {
		mysqli_set_charset($l, 'utf8mb4');
		$link = $l;
	}
	return $link;
}

/** Table des réglages : sb_config, partagée avec la configuration du CMS */
function sbSettingsTable() {
	$c = sbDbConfig();
	return $c['prefix'] . 'sb_config';
}

/** Ancienne table des réglages (4.11), fusionnée dans sb_config */
function sbSettingsOldTable() {
	$c = sbDbConfig();
	return $c['prefix'] . 'sb_settings';
}

/**
 * sb_config présente, avec sa colonne updated_at : NULL pour les valeurs
 * d'avant la colonne, puis datée par MySQL à l'ajout et à chaque
 * modification réelle d'une valeur (y compris par les écrans du CMS qui
 * écrivent dans sb_config sans la nommer).
 * @return bool table utilisable
 */
function sbSettingsEnsureTable($link) {
	static $done = array();
	$t = sbSettingsTable();
	if (isset($done[$t])) return $done[$t];
	if (!mysqli_query($link, "CREATE TABLE IF NOT EXISTS `$t` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`config` varchar(50) NOT NULL COMMENT 'Nom de la configuration',
		`content` text NOT NULL COMMENT 'Valeur de la configuration',
		`updated_at` datetime NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Dernière modification',
		PRIMARY KEY (`id`),
		UNIQUE KEY `config` (`config`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8")) return $done[$t] = false;
	$res = mysqli_query($link, "SHOW COLUMNS FROM `$t` LIKE 'updated_at'");
	if ($res && mysqli_num_rows($res) === 0) {
		// Ajoutée vide (date inconnue), puis datée pour les ajouts suivants
		if (@mysqli_query($link, "ALTER TABLE `$t` ADD `updated_at` datetime NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'Dernière modification'")) {
			@mysqli_query($link, "ALTER TABLE `$t` MODIFY `updated_at` datetime NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Dernière modification'");
		}
	}
	return $done[$t] = true;
}

/** sb_config a-t-elle sa colonne updated_at ? */
function sbSettingsHasUpdatedAt($link) {
	$res = @mysqli_query($link, "SHOW COLUMNS FROM `" . sbSettingsTable() . "` LIKE 'updated_at'");
	return $res && mysqli_num_rows($res) > 0;
}

/**
 * Installations 4.11 : réglages de sb_settings copiés dans sb_config (dates de
 * modification comprises), puis sb_settings supprimée. Sans effet si elle
 * n'existe pas.
 * @return bool fusion faite
 */
function sbSettingsMergeOldTable($link) {
	$old = sbSettingsOldTable();
	$res = @mysqli_query($link, "SHOW TABLES LIKE '" . mysqli_real_escape_string($link, $old) . "'");
	if (!$res || mysqli_num_rows($res) === 0) return false;
	if (!sbSettingsEnsureTable($link)) return false;
	@mysqli_query($link, "SELECT GET_LOCK('sbsettings_migrate', 10)");
	$t  = sbSettingsTable();
	$ok = sbSettingsHasUpdatedAt($link)
		? @mysqli_query($link, "INSERT INTO `$t` (`config`, `content`, `updated_at`) SELECT `name`, `value`, `updated_at` FROM `$old` ON DUPLICATE KEY UPDATE `content` = VALUES(`content`), `updated_at` = VALUES(`updated_at`)")
		: @mysqli_query($link, "INSERT INTO `$t` (`config`, `content`) SELECT `name`, `value` FROM `$old` ON DUPLICATE KEY UPDATE `content` = VALUES(`content`)");
	if ($ok && !@mysqli_query($link, "DROP TABLE `$old`")) {
		$GLOBALS['sb_settings_warnings'][] = "Réglages copiés dans sb_config, mais l'ancienne table " . $old . " n'a pu être supprimée (droit DROP) : la supprimer à la main.";
	}
	@mysqli_query($link, "SELECT RELEASE_LOCK('sbsettings_migrate')");
	return (bool) $ok;
}

/** Lignes de l'ancien settings.txt s'il contient encore des réglages, sinon false */
function sbSettingsLegacyLines() {
	if (!@is_file(SB_SETTINGS_LEGACY_FILE)) return false;
	$lines = @file(SB_SETTINGS_LEGACY_FILE, FILE_IGNORE_NEW_LINES);
	if (!$lines || count($lines) < 22) return false;
	return array_pad($lines, 37, '');
}

/**
 * Migration de l'ancien settings.txt : sbdbconfig.php, table sb_config,
 * secrets de sb_config chiffrés, puis settings.txt vidé (copie à côté de
 * sbdbconfig.php). Sans effet si déjà faite.
 */
function sbSettingsMigrate() {
	$legacy = sbSettingsLegacyLines();
	$c = sbDbConfig();

	// 1. Accès base : settings.txt -> sbdbconfig.php. Un settings.txt rempli
	// après une migration (réinstallation) est la source la plus récente :
	// ses accès remplacent ceux du fichier (la clé est conservée).
	$legacy_db = ($legacy && trim($legacy[2]) !== '') ? array(
		'host' => trim($legacy[2]), 'name' => trim($legacy[3]), 'user' => trim($legacy[4]),
		'password' => trim($legacy[5]), 'prefix' => trim($legacy[21]),
	) : null;
	if ($legacy_db && $c['source'] !== 'env') {
		$same = true;
		foreach ($legacy_db as $k => $v) if ($c[$k] !== $v) $same = false;
		if ($c['source'] === 'legacy' || !$same) {
			$legacy_db['key'] = ($c['source'] === 'legacy') ? '' : $c['key'];
			if (!sbDbConfigWrite($legacy_db)) {
				$GLOBALS['sb_settings_warnings'][] = "sbdbconfig.php n'a pu être écrit (dossier non inscriptible) : les accès à la base sont encore lus dans settings.txt.";
				return false;
			}
			$c = sbDbConfig(true);
		}
	}
	if (!$legacy) return true;

	$link = sbSettingsDb();
	if (!$link || !sbSettingsEnsureTable($link)) return false;
	@mysqli_query($link, "SELECT GET_LOCK('sbsettings_migrate', 10)");

	// 2. Réglages : settings.txt -> sb_config (écrase, voir plus haut)
	$t = sbSettingsTable();
	$secret = sbSettingsSecretNames();
	$stmt = mysqli_prepare($link, "INSERT INTO `$t` (`config`, `content`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `content` = VALUES(`content`)");
	$ok = (bool) $stmt;
	if ($ok) {
		foreach (sbSettingsLegacyMap() as $i => $name) {
			$value = trim($legacy[$i]);
			if (in_array($name, $secret, true)) $value = sbSecretSeal($value);
			mysqli_stmt_bind_param($stmt, 'ss', $name, $value);
			$ok = mysqli_stmt_execute($stmt) && $ok;
		}
	}
	if ($ok) sbConfigSealSecrets($link);

	// 3. Copie de l'ancien fichier hors de portée du web, puis vidage
	if ($ok) {
		$dbfile = sbDbConfigPath();
		$dir = $dbfile ? dirname($dbfile) : dirname(SB_SETTINGS_LEGACY_FILE);
		$backup = $dir . DIRECTORY_SEPARATOR . 'sbsettings-backup-' . date('Ymd-His') . '.php';
		$copy = "<?php http_response_code(404); exit; ?>\n" . (string) @file_get_contents(SB_SETTINGS_LEGACY_FILE);
		if (@file_put_contents($backup, $copy, LOCK_EX) === strlen($copy)) {
			@chmod($backup, 0640);
			@file_put_contents(SB_SETTINGS_LEGACY_FILE, '', LOCK_EX);
		} else {
			$GLOBALS['sb_settings_warnings'][] = "Réglages migrés en base, mais la copie de settings.txt n'a pu être écrite : settings.txt n'a pas été vidé.";
		}
	}
	@mysqli_query($link, "SELECT RELEASE_LOCK('sbsettings_migrate')");
	return $ok;
}

/** Chiffre les secrets du module contact encore en clair dans sb_config */
function sbConfigSealSecrets($link) {
	if (!sbSecretKey()) return;
	$c = sbDbConfig();
	$t = $c['prefix'] . 'sb_config';
	foreach (sbConfigSecretNames() as $name) {
		$stmt = mysqli_prepare($link, "SELECT `content` FROM `$t` WHERE `config` = ?");
		if (!$stmt) return;
		// bind_result plutôt que get_result : ce dernier exige mysqlnd
		mysqli_stmt_bind_param($stmt, 's', $name);
		mysqli_stmt_execute($stmt);
		$content = null;
		mysqli_stmt_bind_result($stmt, $content);
		$found = mysqli_stmt_fetch($stmt);
		mysqli_stmt_close($stmt);
		if (!$found || $content === null || $content === '' || strpos($content, SB_SETTINGS_ENC_PREFIX) === 0) continue;
		$sealed = sbSecretSeal($content);
		$up = mysqli_prepare($link, "UPDATE `$t` SET `content` = ? WHERE `config` = ?");
		mysqli_stmt_bind_param($up, 'ss', $sealed, $name);
		mysqli_stmt_execute($up);
	}
}

/** Lignes de réglages présentes dans sb_config (false si la requête échoue) */
function sbSettingsFetch($link) {
	$names = array_map(function ($n) use ($link) { return "'" . mysqli_real_escape_string($link, $n) . "'"; }, array_keys(sbSettingsDefaults()));
	$res = @mysqli_query($link, "SELECT `config`, `content` FROM `" . sbSettingsTable() . "` WHERE `config` IN (" . implode(',', $names) . ")");
	if (!$res) return false;
	$rows = array();
	while ($row = mysqli_fetch_row($res)) $rows[$row[0]] = $row[1];
	return $rows;
}

/** Charge tous les réglages (valeurs par défaut pour les absents) */
function sbSettingsLoad() {
	$settings = sbSettingsDefaults();
	$link = sbSettingsDb();
	if ($link) {
		$rows = sbSettingsFetch($link);
		// Aucun réglage dans sb_config : installation 4.11, réglages encore
		// dans sb_settings (fusion, une seule fois)
		if (!$rows && sbSettingsMergeOldTable($link)) $rows = sbSettingsFetch($link);
		if ($rows !== false) {
			$settings = array_merge($settings, $rows);
			$GLOBALS['sb_settings_loaded_from_db'] = true;
		}
	}
	foreach (sbSettingsSecretNames() as $name) {
		if (isset($settings[$name])) $settings[$name] = sbSecretOpen($settings[$name]);
	}
	return $settings;
}

// -----------------------------------------------------------------------
// API
// -----------------------------------------------------------------------

/** Valeur d'un réglage (chaîne, sans espaces autour) */
function sbSetting($name, $default = '') {
	return isset($GLOBALS['sb_settings'][$name]) ? trim((string) $GLOBALS['sb_settings'][$name]) : $default;
}

/** Réglage booléen ("1") */
function sbSettingBool($name) {
	return sbSetting($name) === '1';
}

/**
 * Enregistre des réglages (nom => valeur). Les secrets sont chiffrés.
 * @param bool $only_missing n'écrit que les réglages absents de la table
 *                           (installation en mode mise à jour)
 * @return bool
 */
function sbSettingsSave(array $values, $only_missing = false) {
	$link = sbSettingsDb();
	if (!$link || !sbSettingsEnsureTable($link)) return false;
	$t = sbSettingsTable();
	$secret = sbSettingsSecretNames();
	$known = sbSettingsDefaults();
	// updated_at : posée par MySQL (ajout, ou valeur réellement modifiée)
	$stmt = mysqli_prepare($link, $only_missing
		? "INSERT IGNORE INTO `$t` (`config`, `content`) VALUES (?, ?)"
		: "INSERT INTO `$t` (`config`, `content`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `content` = VALUES(`content`)");
	if (!$stmt) return false;
	$ok = true;
	foreach ($values as $name => $value) {
		if (!array_key_exists($name, $known)) continue;
		$value = trim((string) $value);
		$stored = in_array($name, $secret, true) ? sbSecretSeal($value) : $value;
		mysqli_stmt_bind_param($stmt, 'ss', $name, $stored);
		if (mysqli_stmt_execute($stmt)) {
			if (!$only_missing || mysqli_stmt_affected_rows($stmt) > 0) $GLOBALS['sb_settings'][$name] = $value;
		} else {
			$ok = false;
		}
	}
	return $ok;
}

/**
 * Ancien tableau positionnel ($sb_settings_config) pour le code tiers qui le
 * lirait encore. Les accès à la base n'y figurent plus.
 */
function sbSettingsLegacyArray() {
	$out = array_fill(0, 37, '');
	foreach (sbSettingsLegacyMap() as $i => $name) $out[$i] = sbSetting($name);
	return $out;
}

// -----------------------------------------------------------------------
// Chargement (SBUIADMIN_SETTINGS_NO_AUTOLOAD : fonctions seules, sans
// migration ni lecture - utilisé par la page « Requis Serveur »)
// -----------------------------------------------------------------------
if (defined('SBUIADMIN_SETTINGS_NO_AUTOLOAD')) return;
$GLOBALS['sb_settings_warnings'] = array();
if (sbSettingsLegacyLines()) {
	sbSettingsMigrate();
}
$GLOBALS['sb_settings'] = sbSettingsLoad();
