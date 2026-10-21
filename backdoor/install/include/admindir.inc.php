<?php
/**
 * Installation neuve : nom du dossier d'administration tiré au hasard
 * (« backdoor » est connu de tous, c'est l'adresse de la page de connexion).
 *
 * Nom = 5 syllabes consonne + voyelle + 2 chiffres (ex. vokadilumi47),
 * tiré par random_int() : 80^5 x 100, soit plus de 300 milliards de noms.
 * La méthode est publique, le résultat imprévisible : aucune liste de mots
 * (publiée avec le dépôt), aucun appel à Internet.
 *
 * Le renommage se fait tout à la fin (complete_installation.php, appelé par
 * la page finale) : SBADMIN écrit dans sbconfig.php, puis le dossier
 * renommé ; en cas d'échec, sbconfig.php est remis comme avant.
 */

/** Nom tiré au hasard */
function sbInstAdminDirGenerate() {
	$consonants = 'bcdfgjklmnprstvz';
	$vowels     = 'aeiou';
	$name = '';
	for ($i = 0; $i < 5; $i++) {
		$name .= $consonants[random_int(0, strlen($consonants) - 1)] . $vowels[random_int(0, strlen($vowels) - 1)];
	}
	return $name . str_pad((string) random_int(0, 99), 2, '0', STR_PAD_LEFT);
}

/** Noms refusés : adresses connues des robots et dossiers du CMS */
function sbInstAdminDirReserved() {
	return array('admin', 'administrator', 'administration', 'administrateur', 'backdoor', 'backend', 'backoffice',
		'login', 'wp-admin', 'cpanel', 'manager', 'gestion', 'sbuiadmin', 'install', 'installer', 'setup',
		'upload', 'uploads', 'theme', 'themes', 'datas', 'data', 'inc', 'include', 'includes', 'assets', 'vendor',
		'cache', 'private', 'public', 'api', 'cgi-bin', 'core', 'server', 'outils', 'tools', 'tmp', 'test');
}

/**
 * Nom refusé ? (texte de l'erreur, '' si accepté)
 * @param string $name    nom saisi
 * @param string $current nom actuel du dossier (accepté tel quel s'il n'est pas réservé)
 * @param string $root    racine du site
 */
function sbInstAdminDirError($name, $current, $root) {
	if (!is_string($name) || !preg_match('/^[a-z][a-z0-9]{5,31}$/', $name)) {
		return "Dossier d'administration : de 6 à 32 lettres minuscules et chiffres, en commençant par une lettre.";
	}
	if (in_array($name, sbInstAdminDirReserved(), true)) {
		return "Dossier d'administration : « " . $name . " » est un nom trop connu ou déjà utilisé par le CMS, choisissez-en un autre.";
	}
	if ($name !== $current && file_exists(rtrim($root, '/') . '/' . $name)) {
		return "Dossier d'administration : « " . $name . " » existe déjà à la racine du site.";
	}
	return '';
}

/** Ligne SBADMIN de sbconfig.php remplacée (null si la ligne est introuvable) */
function sbInstAdminDirConfig($config, $name) {
	$count = 0;
	$out = preg_replace("/(defined\\('SBADMIN'\\)\\s*OR\\s*define\\('SBADMIN',\\s*')[^']*('\\);)/i", '${1}' . $name . '${2}', (string) $config, 1, $count);
	return $count === 1 ? $out : null;
}

/**
 * Le renommage est-il possible ? (texte de l'erreur, '' si oui) - vérifié
 * avant de le proposer, pour ne jamais annoncer une adresse qui n'existera pas
 */
function sbInstAdminDirCheck($root, $current, $new) {
	$root = rtrim($root, '/');
	$cfg  = $root . '/sbconfig.php';
	if ($new === $current) return '';
	if ($error = sbInstAdminDirError($new, $current, $root)) return $error;
	if (!is_dir($root . '/' . $current) || is_link($root . '/' . $current)) return "dossier d'administration introuvable";
	if (!is_writable($root)) return "la racine du site n'est pas inscriptible";
	if (!is_file($cfg) || !is_writable($cfg)) return "sbconfig.php n'est pas inscriptible";
	if (sbInstAdminDirConfig(file_get_contents($cfg), $new) === null) return "ligne SBADMIN introuvable dans sbconfig.php";
	return '';
}

/**
 * Renomme : SBADMIN dans sbconfig.php, puis le dossier. Échec du renommage
 * = sbconfig.php remis comme avant (l'ancienne adresse reste valable).
 * @return string '' si fait, sinon l'erreur
 */
function sbInstAdminDirRename($root, $current, $new) {
	$root = rtrim($root, '/');
	if ($error = sbInstAdminDirCheck($root, $current, $new)) return $error;
	if ($new === $current) return '';
	$cfg    = $root . '/sbconfig.php';
	$before = file_get_contents($cfg);
	$after  = sbInstAdminDirConfig($before, $new);
	if (@file_put_contents($cfg, $after, LOCK_EX) === false) return "écriture de sbconfig.php impossible";
	if (function_exists('opcache_invalidate')) @opcache_invalidate($cfg, true);
	if (!@rename($root . '/' . $current, $root . '/' . $new)) {
		@file_put_contents($cfg, $before, LOCK_EX);
		if (function_exists('opcache_invalidate')) @opcache_invalidate($cfg, true);
		return "renommage du dossier refusé par le serveur (droits)";
	}
	return '';
}
