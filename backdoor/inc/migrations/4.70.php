<?php
/**
 * Migration 4.70 : inc/admin/theme.txt supprimé partout
 *
 * La 4.63 le gardait quand le sbconfig.php du site le lisait encore (ancien
 * sbconfig.php, jamais remplacé par les mises à jour : il s'arrête sans lui
 * et en tire SBTHEME). Ces lignes connues sont réécrites pour lire le thème en
 * base (sbSettingsTheme(), chargé par sbconfig.php depuis la 4.14), puis le
 * fichier est supprimé. Le sbconfig.php d'origine est gardé en base
 * (sb_config « sbconfig_before_470 ») pour le retour arrière.
 *
 * Rien n'est touché si le sbconfig.php ne ressemble à aucune version connue,
 * si le résultat n'est pas du PHP valide, ou si le fichier n'est pas
 * inscriptible : theme.txt reste alors en place, comme avant.
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

$sb470_cfg   = dirname(SBUIADMIN_PATH) . '/sbconfig.php';
$sb470_theme = SBUIADMIN_PATH . '/inc/admin/theme.txt';

/** Ancien sbconfig.php -> lecture du thème en base ; null si inconnu ou invalide */
$sb470_rewrite = function ($src) {
	if (!is_string($src) || strpos($src, 'theme.txt') === false) return null;
	$settings = strpos($src, 'sbuiadmin-settings.php');
	if ($settings === false) return null;
	$new = preg_replace('/^([ \t]*)\$sb_theme_config\s*=\s*file\([^;]*theme\.txt\'\s*\)\s*;/m', '$1$sb_theme_config    = array(); // 4.70 : thème en base, voir sbSettingsTheme()', $src, 1, $n);
	if (!$n) return null;
	$new = str_replace(array('_sbcfg($sb_theme_config, 0)', 'trim($sb_theme_config[0])'), 'sbSettingsTheme()', $new, $n);
	if (!$n) return null;
	$new = str_replace('read positional config files (theme.txt)', 'read positional config files', $new);
	// Plus aucune lecture du fichier, et le thème lu après le chargement des réglages
	if (strpos($new, 'theme.txt') !== false || strpos($new, '$sb_theme_config[') !== false) return null;
	if (strpos($new, 'sbSettingsTheme()', strpos($new, 'sbuiadmin-settings.php')) === false
		|| strpos($new, 'define(\'SBTHEME\'') < strpos($new, 'sbuiadmin-settings.php')) return null;
	try {
		token_get_all($new, TOKEN_PARSE);
	} catch (Throwable $e) {
		return null;
	}
	return $new;
};

/** Écriture atomique (fichier temporaire + renommage), droits gardés */
$sb470_write = function ($file, $data) {
	$tmp = $file . '.sb470-' . bin2hex(random_bytes(4));
	if (@file_put_contents($tmp, $data, LOCK_EX) !== strlen($data)) { @unlink($tmp); return false; }
	$perms = @fileperms($file);
	if ($perms !== false) @chmod($tmp, $perms & 0777);
	if (!@rename($tmp, $file)) { @unlink($tmp); return false; }
	if (function_exists('opcache_invalidate')) @opcache_invalidate($file, true);
	return true;
};

/** Thème valide en base (sinon repris de theme.txt, comme la 4.63) */
$sb470_theme_in_db = function (SbMigration $m) use ($sb470_theme) {
	$ok = function ($name) {
		return is_string($name) && preg_match('/^[A-Za-z0-9_-]{1,64}$/', $name) && is_dir(dirname(SBUIADMIN_PATH) . '/theme/' . $name);
	};
	$db = sbSettingsDb();
	if (!$db) throw new RuntimeException('Base indisponible');
	$t = $m->table('sb_config');
	$r = $db->query("SELECT `content` FROM `$t` WHERE `config` = 'theme'");
	if ($r === false) throw new RuntimeException('Requête en échec : ' . $db->error);
	$row = $r->fetch_row();
	if ($row && $ok((string) $row[0])) return (string) $row[0];
	$lines = is_file($sb470_theme) ? @file($sb470_theme, FILE_IGNORE_NEW_LINES) : false;
	$theme = $lines ? trim((string) $lines[0]) : '';
	if (!$ok($theme)) return '';
	$m->query($row
		? "UPDATE `$t` SET `content` = '$theme' WHERE `config` = 'theme'"
		: "INSERT INTO `$t` (`config`, `content`) VALUES ('theme', '$theme')");
	return $theme;
};

return array(
	'description' => 'fichier theme.txt retiré, y compris des anciens sbconfig.php',
	'reversible'  => true,

	'up' => function (SbMigration $m) use ($sb470_cfg, $sb470_theme, $sb470_rewrite, $sb470_write, $sb470_theme_in_db) {
		$src = @file_get_contents($sb470_cfg);
		if ($src === false) return; // sbconfig.php illisible : rien n'est touché
		if (strpos($src, 'theme.txt') !== false) {
			if ($sb470_theme_in_db($m) === '') return; // aucun thème sûr en base : fichier gardé
			$new = $sb470_rewrite($src);
			if ($new === null || !is_writable($sb470_cfg)) return;
			$m->addConfig('sbconfig_before_470', base64_encode($src)); // jamais écrasé : le premier original reste
			if (!$sb470_write($sb470_cfg, $new)) return;
		}
		if (is_file($sb470_theme)) {
			$sb470_theme_in_db($m);
			@unlink($sb470_theme);
		}
	},

	// sbconfig.php d'origine remis (s'il n'a pas changé depuis), theme.txt recréé
	'down' => function (SbMigration $m) use ($sb470_cfg, $sb470_theme, $sb470_rewrite, $sb470_write) {
		$db = sbSettingsDb();
		if (!$db) throw new RuntimeException('Base indisponible');
		$t = $m->table('sb_config');
		$r = $db->query("SELECT `config`, `content` FROM `$t` WHERE `config` IN ('sbconfig_before_470', 'theme')");
		if ($r === false) throw new RuntimeException('Requête en échec : ' . $db->error);
		$rows = array();
		while ($row = $r->fetch_row()) $rows[$row[0]] = (string) $row[1];
		if (!isset($rows['sbconfig_before_470'])) return;
		$orig = base64_decode($rows['sbconfig_before_470'], true);
		$cur  = @file_get_contents($sb470_cfg);
		if (is_string($orig) && $cur !== false && $sb470_rewrite($orig) === $cur) {
			$theme = $rows['theme'] ?? '';
			if (preg_match('/^[A-Za-z0-9_-]{1,64}$/', $theme) && !is_file($sb470_theme)) @file_put_contents($sb470_theme, $theme . "\n", LOCK_EX);
			if (is_file($sb470_theme) && !$sb470_write($sb470_cfg, $orig)) throw new RuntimeException('sbconfig.php impossible à remettre');
		}
		$m->deleteConfig('sbconfig_before_470');
	},
);
