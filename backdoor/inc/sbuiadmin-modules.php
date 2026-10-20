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
 * 'origin' => 'site' dans module.php : module propre au site, jamais
 * rapproché d'un module publié sur GitHub du même nom (ni installé ni mis
 * à jour depuis GitHub), ses fichiers restent hors de la mise à jour du core.
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
		'origin' => '', 'replaces' => array(), 'modifiers' => array(), 'csrf_get' => array(),
	);
	$m['origin'] = ($m['origin'] === 'site') ? 'site' : '';
	return $m;
}

/** Module propre au site ('origin' => 'site') : ignoré par le catalogue GitHub */
function sbModuleIsSite($name) {
	return (sbModuleMeta($name)['origin'] ?? '') === 'site';
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

/**
 * Contrôleur d'administration livré dans le dossier du module
 * (datas/modules/<nom>/admin/<nom>.php), pour un module installé ;
 * null sinon (repli sur backdoor/datas/modules/<nom>.php)
 */
function sbModuleAdminController($name) {
	if (!sbModuleValidName($name) || in_array($name, sbModuleCore(), true) || !sbModuleActive($name)) return null;
	return sbModuleAdminFile($name, $name . '.php');
}

/** Gabarits d'administration du module (datas/modules/<nom>/admin/tpls/), ou null */
function sbModuleAdminTplDir($name) {
	if (sbModuleAdminController($name) === null) return null;
	$dir = sbModulesDir() . $name . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'tpls' . DIRECTORY_SEPARATOR;
	return is_dir($dir) ? $dir : null;
}

/**
 * Widgets du dashboard livrés par les modules installés :
 * datas/modules/<nom>/widgets/<clé>.php (clé : 17 caractères au plus,
 * « nom:clé » tient dans sb_dashboard_widgets.widget_key). Un fichier en
 * erreur est ignoré, jamais une page blanche.
 * @return array 'nom:clé' => définition normalisée (voir sbModuleWidgetLoad())
 */
function sbModuleWidgets($reset = false) {
	static $list = null;
	if ($list !== null && !$reset) return $list;
	$list = array();
	foreach (array_keys(sbModulesActive()) as $name) {
		foreach ((array) @scandir(sbModulesDir() . $name . DIRECTORY_SEPARATOR . 'widgets') as $file) {
			if (!preg_match('/^([a-z][a-z0-9_]{0,16})\.php$/', (string) $file, $m)) continue;
			$w = sbModuleWidgetLoad($name, $m[1]);
			if ($w) $list[$name . ':' . $m[1]] = $w;
		}
	}
	return $list;
}

/**
 * Lit et contrôle un widget de module. Le fichier retourne :
 *   'title' (titre proposé), 'icon', 'link', 'description',
 *   'format' => 'kpi' (tuile : render() retourne une valeur, ou
 *   array('value', 'compare' => texte sous la valeur, 'trend' => % signé,
 *   'trend_good' => 'up'|'down' : sens favorable)) ou 'card' (carte :
 *   render() retourne du HTML, ou un tableau de variables pour
 *   widgets/<clé>.tpl, ou affiche son HTML avec echo),
 *   'width' => 4|6|8|12 (colonnes d'une carte, 4 par défaut),
 *   'scripts' => array('chemin/relatif.js' ou 'https://...') chargés une
 *   seule fois avant les cartes,
 *   'params' => array('nom' => array('label', 'type' => text|number|select|yesno,
 *   'default', 'min', 'max', 'options' => valeur => libellé, 'required', 'help')),
 *   'render' => function (array $params) { ... }
 */
function sbModuleWidgetLoad($name, $key) {
	$dir  = sbModulesDir() . $name . DIRECTORY_SEPARATOR . 'widgets' . DIRECTORY_SEPARATOR;
	$file = $dir . $key . '.php';
	if (!sbModuleValidName($name) || !is_file($file)) return null;
	try {
		$w = include $file;
	} catch (Throwable $e) {
		return null;
	}
	if (!is_array($w) || !isset($w['render']) || !is_callable($w['render'])) return null;

	$params = array();
	foreach ((array) ($w['params'] ?? array()) as $p => $d) {
		if (!preg_match('/^[a-z][a-z0-9_]{0,31}$/', (string) $p) || !is_array($d)) continue;
		$type = in_array($d['type'] ?? 'text', array('text', 'number', 'select', 'yesno'), true) ? ($d['type'] ?? 'text') : 'text';
		$opts = array();
		foreach ((array) ($d['options'] ?? array()) as $v => $l) $opts[(string) $v] = (string) $l;
		if ($type === 'select' && !$opts) continue;
		$min = isset($d['min']) ? (int) $d['min'] : null;
		$max = isset($d['max']) ? (int) $d['max'] : null;
		switch ($type) {
			case 'number': $def = (int) ($d['default'] ?? ($min ?? 0)); break;
			case 'yesno':  $def = empty($d['default']) ? 0 : 1; break;
			case 'select': $def = isset($opts[(string) ($d['default'] ?? '')]) ? (string) $d['default'] : (string) array_key_first($opts); break;
			default:       $def = (string) ($d['default'] ?? '');
		}
		$params[$p] = array(
			'label' => (string) ($d['label'] ?? $p), 'type' => $type, 'default' => $def,
			'min' => $min, 'max' => $max, 'options' => $opts,
			'required' => !empty($d['required']), 'help' => (string) ($d['help'] ?? ''),
		);
	}

	$scripts = array();
	foreach ((array) ($w['scripts'] ?? array()) as $src) {
		$src = (string) $src;
		if (preg_match('#^https://[^\s"\'<>]+$#', $src) || (preg_match('#^[A-Za-z0-9_./~-]+(\?[A-Za-z0-9_.=&-]*)?$#', $src) && strpos($src, '//') === false)) $scripts[] = $src;
	}

	$meta = sbModuleMeta($name);
	return array(
		'module'       => $name,
		'module_title' => (string) ($meta['title'] ?? $name),
		'key'          => $key,
		'title'        => (string) ($w['title'] ?? $key),
		'icon'         => (string) ($w['icon'] ?? ($meta['icon'] ?? 'puzzle-piece')),
		'link'         => (string) ($w['link'] ?? ''),
		'description'  => (string) ($w['description'] ?? ''),
		'format'       => (($w['format'] ?? 'kpi') === 'card') ? 'card' : 'kpi',
		'width'        => sbModuleWidgetWidth($w['width'] ?? 4),
		'scripts'      => array_values(array_unique($scripts)),
		'params'       => $params,
		'render'       => $w['render'],
		'tpl'          => is_file($dir . $key . '.tpl') ? $dir . $key . '.tpl' : null,
	);
}

/** Largeur d'une carte (colonnes de la grille) : 4, 6, 8 ou 12, sinon 4 */
function sbModuleWidgetWidth($width) {
	return in_array((int) $width, array(4, 6, 8, 12), true) ? (int) $width : 4;
}

/**
 * Paramètres d'un widget de module contrôlés selon sa définition (valeur
 * absente : valeur par défaut). Les textes sont rendus tels quels : au
 * widget de les échapper.
 * @return array array(valeurs, message d'erreur ou '')
 */
function sbModuleWidgetParams(array $w, $input) {
	$input = is_array($input) ? $input : array();
	$out   = array();
	foreach ($w['params'] as $p => $d) {
		$v = (isset($input[$p]) && is_scalar($input[$p])) ? trim((string) $input[$p]) : '';
		switch ($d['type']) {
			case 'number':
				if ($v === '') $v = (string) $d['default'];
				if (!preg_match('/^-?\d{1,9}$/', $v) || ($d['min'] !== null && (int) $v < $d['min']) || ($d['max'] !== null && (int) $v > $d['max'])) {
					$range = ($d['min'] !== null && $d['max'] !== null) ? ' entre ' . $d['min'] . ' et ' . $d['max'] : '';
					return array(array(), '« ' . $d['label'] . ' » : nombre' . $range . ' attendu.');
				}
				$out[$p] = (int) $v;
				break;
			case 'select':
				if ($v === '') $v = $d['default'];
				if (!isset($d['options'][$v])) return array(array(), '« ' . $d['label'] . ' » : choix invalide.');
				$out[$p] = $v;
				break;
			case 'yesno':
				$out[$p] = ($v === '1') ? 1 : (($v === '0') ? 0 : $d['default']);
				break;
			default:
				// Texte : vidé volontairement dans le formulaire, ou absent (défaut)
				if (!isset($input[$p])) $v = $d['default'];
				$v = mb_substr($v, 0, 255);
				if ($v === '' && $d['required']) return array(array(), '« ' . $d['label'] . ' » est obligatoire.');
				$out[$p] = $v;
		}
	}
	return array($out, '');
}

/**
 * Calcul d'un widget de module pour le dashboard ($json : paramètres
 * enregistrés, $width : largeur choisie à l'ajout, vide = celle du
 * widget). Une exception du widget donne une tuile « indisponible ».
 * @return array|null null : widget masqué (module retiré ou désactivé,
 *   fichier disparu, droit « voir » du module refusé)
 */
function sbModuleWidgetRender($id, $json, $width = '') {
	$all = sbModuleWidgets();
	if (!isset($all[$id])) return null;
	$w = $all[$id];
	if (function_exists('sbCheckRightRow')) {
		$uid = function_exists('sbGetCurrentUserId') ? sbGetCurrentUserId() : 0;
		if ($uid <= 0 || !sbCheckRightRow($w['module'], 'view', $uid)) return null;
	}

	$out = array(
		'format' => $w['format'], 'value' => '', 'compare' => '', 'trend' => null, 'html' => '', 'error' => false,
		'width' => ((string) $width !== '') ? sbModuleWidgetWidth($width) : $w['width'], 'scripts' => $w['scripts'],
	);
	list($params, $err) = sbModuleWidgetParams($w, json_decode((string) $json, true));
	// Définition changée depuis l'enregistrement : valeurs par défaut
	if ($err !== '') list($params, $err) = sbModuleWidgetParams($w, array());
	if ($err !== '') { $out['error'] = true; return $out; }

	$level = ob_get_level();
	ob_start();
	try {
		$r    = call_user_func($w['render'], $params);
		$echo = (string) ob_get_contents();
		if ($w['format'] === 'kpi') {
			if (is_array($r)) {
				$out['value']   = is_scalar($r['value'] ?? null) ? (string) $r['value'] : '';
				$out['compare'] = is_scalar($r['compare'] ?? null) ? (string) $r['compare'] : '';
				$out['trend']   = sbModuleWidgetTrend($r['trend'] ?? null, $r['trend_good'] ?? 'up');
			} else {
				$out['value'] = is_scalar($r) ? (string) $r : '';
			}
		} elseif (is_array($r) && $w['tpl'] !== null) {
			global $sbsmarty;
			$tpl = $sbsmarty->createTemplate('file:' . $w['tpl'], $sbsmarty);
			$tpl->assign($r + array('params' => $params));
			$out['html'] = $tpl->fetch();
		} else {
			$out['html'] = is_string($r) ? $r : $echo;
		}
	} catch (Throwable $e) {
		$out['error'] = true;
	} finally {
		while (ob_get_level() > $level) ob_end_clean();
	}
	return $out;
}

/**
 * Pastille de tendance d'une tuile de module : flèche selon le signe,
 * couleur selon le sens favorable ($good 'down' : une baisse est verte)
 * @return array|null direction (flèche), tone (couleur), percent (texte)
 */
function sbModuleWidgetTrend($trend, $good = 'up') {
	if (!is_int($trend) && !is_float($trend) && !(is_string($trend) && is_numeric($trend))) return null;
	$trend = round((float) $trend, 1);
	if (!is_finite($trend)) return null;
	$direction = ($trend > 0) ? 'up' : (($trend < 0) ? 'down' : 'flat');
	$tone = ($direction === 'flat') ? 'flat' : ((($direction === 'up') === ($good !== 'down')) ? 'up' : 'down');
	$percent = str_replace('.', ',', rtrim(rtrim(number_format(abs($trend), 1, '.', ''), '0'), '.'));
	return array('direction' => $direction, 'tone' => $tone, 'percent' => $percent);
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
