<?php
/**
 * Admin Startbootstrap
 * SBUIADMIN Modules : registre des modules (site et administration)
 *
 * Un module est un dossier datas/modules/<nom>/ qui contient module.php
 * (description, version, tables, codes courts, routes, droits...). Ses
 * autres fichiers (contrôleur et gabarit d'admin, images) sont listés dans
 * module.php ('files').
 *
 * Trois états :
 *  - absent        : pas de dossier (disponible sur GitHub seulement)
 *  - présent       : fichiers sur le disque, non installé (jamais chargé)
 *  - installé      : ligne dans sb_modules, chargé par le site et l'admin
 *
 * pages, user et search font partie du core : toujours chargés, sans
 * module.php. Tant que la table sb_modules n'existe pas (migration 4.29 non
 * passée), tout module présent est considéré comme installé.
 *
 * Chargé par sbuiadmin-config.php (admin) et sbuiadmin-functions.php (site).
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

if (!defined('SB_PATH') && !defined('SBUIADMIN_PATH')) die('Are you crazy!');
if (defined('SBUIADMIN_MODULES_LOADED')) return;
define('SBUIADMIN_MODULES_LOADED', true);

/** Modules du core : toujours chargés, jamais installés ni retirés */
function sbModuleCore() {
	return array('pages', 'user', 'search');
}

/**
 * Dossier datas/modules/ : à côté du dossier d'administration (SBUIADMIN_PATH,
 * un chemin dans l'admin ; true sur le site), sinon backdoor/inc -> racine
 */
function sbModulesDir() {
	$root = (defined('SBUIADMIN_PATH') && is_string(SBUIADMIN_PATH) && is_dir(SBUIADMIN_PATH)) ? dirname(SBUIADMIN_PATH) : dirname(__DIR__, 2);
	return $root . DIRECTORY_SEPARATOR . 'datas' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR;
}

function sbModuleValidName($name) {
	return is_string($name) && (bool) preg_match('/^[a-z][a-z0-9_]{1,31}$/', $name);
}

/** Lit et contrôle datas/modules/<nom>/module.php */
function sbModuleLoadMeta($name) {
	$file = sbModulesDir() . $name . DIRECTORY_SEPARATOR . 'module.php';
	if (!sbModuleValidName($name) || !is_file($file)) return null;
	$m = include $file;
	if (!is_array($m) || ($m['name'] ?? '') !== $name || !preg_match('/^\d+\.\d+\.\d+$/', (string) ($m['version'] ?? ''))) return null;
	$m += array(
		'title' => $name, 'icon' => 'puzzle-piece', 'description' => '', 'author' => '', 'requires_core' => '0',
		'routes' => array(), 'page_module' => false, 'shortcodes' => array(), 'tables' => array(),
		'files' => array(), 'rights' => array(), 'pagebuilder' => array(), 'search' => array(),
	);
	return $m;
}

/**
 * Modules présents sur le disque (hors core), triés par nom.
 * @return array nom => métadonnées
 */
function sbModulesPresent($reset = false) {
	static $list = null;
	if ($list !== null && !$reset) return $list;
	$list = array();
	foreach ((array) @scandir(sbModulesDir()) as $name) {
		if (in_array($name, sbModuleCore(), true)) continue;
		$m = sbModuleLoadMeta($name);
		if ($m) $list[$name] = $m;
	}
	ksort($list, SORT_STRING);
	return $list;
}

/**
 * Modules installés (table sb_modules).
 * @return array|null nom => ligne, null si la table n'existe pas encore
 */
function sbModulesInstalled($reset = false) {
	static $rows = false;
	if ($rows !== false && !$reset) return $rows;
	$rows = null;
	$db = function_exists('sbSettingsDb') ? sbSettingsDb() : null;
	if (!($db instanceof mysqli)) return $rows;
	$c = function_exists('sbDbConfig') ? sbDbConfig() : array();
	$table = (string) ($c['prefix'] ?? '') . 'sb_modules';
	$res = @mysqli_query($db, 'SELECT name, version, db_version, installed_at, updated_at FROM `' . str_replace('`', '', $table) . '`');
	if (!$res) return $rows;
	$rows = array();
	while ($r = mysqli_fetch_assoc($res)) $rows[$r['name']] = $r;
	mysqli_free_result($res);
	return $rows;
}

/** Module chargé par le site et l'admin : core, ou présent ET installé */
function sbModuleActive($name) {
	if (in_array($name, sbModuleCore(), true)) return true;
	$present = sbModulesPresent();
	if (!isset($present[$name])) return false;
	$installed = sbModulesInstalled();
	return ($installed === null) || isset($installed[$name]);
}

/** Modules installés et présents (hors core) : nom => métadonnées */
function sbModulesActive() {
	$out = array();
	foreach (sbModulesPresent() as $name => $m) if (sbModuleActive($name)) $out[$name] = $m;
	return $out;
}

/**
 * Dossiers de datas/modules/ chargés par le site : core, modules installés,
 * et dossiers sans module.php (modules maison, $sb_safe_modules_cms)
 */
function sbModulesLoadable() {
	$out = array();
	foreach ((array) @scandir(sbModulesDir()) as $name) {
		if ($name === '.' || $name === '..' || !is_dir(sbModulesDir() . $name)) continue;
		if (!is_file(sbModulesDir() . $name . DIRECTORY_SEPARATOR . 'module.php') || sbModuleActive($name)) $out[] = $name;
	}
	return $out;
}

/**
 * Premiers segments d'URL réécrite servis par un module (/news/..., /user/...) :
 * user (core), routes des modules installés qui ont un dossier du même nom,
 * modules maison sans module.php. Tout autre segment est un identifiant de page.
 */
function sbModulesRewriteNames() {
	$out = array('user');
	foreach (sbModulesLoadable() as $name) {
		if (in_array($name, sbModuleCore(), true)) continue;
		if (sbModuleMeta($name) === null || in_array($name, sbModuleRoutes(true), true)) $out[] = $name;
	}
	return $out;
}

function sbModuleMeta($name) {
	$present = sbModulesPresent();
	return $present[$name] ?? null;
}

/**
 * Pages publiques (index.php?p=) servies par les modules.
 * @param bool $activeOnly false : celles de tous les modules présents
 */
function sbModuleRoutes($activeOnly = true) {
	$out = array();
	foreach (($activeOnly ? sbModulesActive() : sbModulesPresent()) as $m) {
		foreach ((array) $m['routes'] as $r) $out[] = (string) $r;
	}
	return array_values(array_unique($out));
}

/** Une clé de métadonnées fusionnée sur les modules installés (tables, pagebuilder...) */
function sbModulesCollect($key) {
	$out = array();
	foreach (sbModulesActive() as $m) {
		if (is_array($m[$key] ?? null)) $out = array_merge($out, $m[$key]);
	}
	return $out;
}

/** Chemin de datas/modules/<nom>/admin/<fichier>, ou null s'il n'existe pas */
function sbModuleAdminFile($name, $file) {
	$path = sbModulesDir() . $name . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . $file;
	return is_file($path) ? $path : null;
}

/** Langue d'administration des modules installés (constantes) */
function sbModulesLoadAdminLang($lang) {
	foreach (array_keys(sbModulesActive()) as $name) {
		$path = sbModuleAdminFile($name, 'lang' . DIRECTORY_SEPARATOR . basename((string) $lang) . '.php');
		if ($path === null) $path = sbModuleAdminFile($name, 'lang' . DIRECTORY_SEPARATOR . 'french.php');
		if ($path !== null) include_once $path;
	}
}

?>
