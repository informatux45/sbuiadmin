<?php
/**
 * Migration 4.14 : réglages des fichiers de inc/admin/ en base (sb_config)
 *
 *  - theme : thème du site, lu dans theme.txt. Le fichier est GARDÉ :
 *    l'ancien sbconfig.php (jamais remplacé par les mises à jour) s'arrête
 *    sans lui ; le réglage en base l'emporte (inc/sbuiadmin-settings.php).
 *  - dashboard.txt : plus utilisé (tuiles du tableau de bord en table
 *    sb_dashboard_widgets) ; contenu gardé dans legacy_dashboard_txt, fichier
 *    supprimé.
 *  - settings.txt : vide depuis la 4.11, supprimé (un settings.txt encore
 *    rempli est laissé à la migration automatique des réglages).
 *  - twofa_enabled : la double authentification devient un réglage
 *    (désactivée par défaut). Gardée active seulement si elle a déjà
 *    fonctionné sur ce site (code validé noté dans le journal).
 *
 * Exécutée par le moteur de la version précédente : uniquement l'API 4.13
 * de SbMigration, aucune fonction nouvelle de la 4.14.
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

$sb414_dir = SBUIADMIN_PATH . '/inc/admin/';

/** Valeur d'une ligne de sb_config (null si absente) */
$sb414_get = function (SbMigration $m, $config) {
	$db = sbSettingsDb();
	if (!$db) throw new RuntimeException('Base indisponible');
	$r = $db->query('SELECT `content` FROM `' . $m->table('sb_config') . "` WHERE `config` = '" . $db->real_escape_string($config) . "'");
	if ($r === false) throw new RuntimeException('Requête en échec : ' . $db->error);
	$row = $r->fetch_row();
	return $row ? (string) $row[0] : null;
};

/** Nom de thème valide (lettres, chiffres, - et _) présent dans theme/ */
$sb414_theme_ok = function ($name) {
	return is_string($name) && preg_match('/^[A-Za-z0-9_-]{1,64}$/', $name) && is_dir(dirname(SBUIADMIN_PATH) . '/theme/' . $name);
};

return array(
	'description' => 'thème, tableau de bord et double authentification en base',
	'reversible'  => true,

	'up' => function (SbMigration $m) use ($sb414_dir, $sb414_get, $sb414_theme_ok) {
		// Thème
		$lines = @file($sb414_dir . 'theme.txt', FILE_IGNORE_NEW_LINES);
		$theme = $lines ? trim((string) $lines[0]) : '';
		$m->addConfig('theme', $sb414_theme_ok($theme) ? $theme : 'saxo');

		// dashboard.txt : contenu gardé, puis fichier supprimé
		$dash = $sb414_dir . 'dashboard.txt';
		if (is_file($dash)) {
			$m->addConfig('legacy_dashboard_txt', (string) @file_get_contents($dash));
			if ($sb414_get($m, 'legacy_dashboard_txt') !== null) @unlink($dash);
		}

		// settings.txt vide : supprimé
		$set = $sb414_dir . 'settings.txt';
		if (is_file($set) && trim((string) @file_get_contents($set)) === '') @unlink($set);

		// Double authentification : active seulement si elle a déjà fonctionné
		$used = false;
		if ($m->tableExists('sb_logaccess')) {
			$db = sbSettingsDb();
			$r = $db->query('SELECT COUNT(*) FROM `' . $m->table('sb_logaccess') . "` WHERE `logaccess_event` LIKE 'Double authentification valid%'");
			$used = $r && (int) $r->fetch_row()[0] > 0;
		}
		$m->addConfig('twofa_enabled', $used ? '1' : '0');
	},

	'down' => function (SbMigration $m) use ($sb414_dir, $sb414_get, $sb414_theme_ok) {
		// theme.txt : thème actuel (l'ancien code ne lit que le fichier)
		$theme = $sb414_get($m, 'theme');
		if ($sb414_theme_ok($theme)) @file_put_contents($sb414_dir . 'theme.txt', $theme . "\n", LOCK_EX);

		// dashboard.txt et settings.txt recréés
		$dash = $sb414_get($m, 'legacy_dashboard_txt');
		if ($dash !== null && !is_file($sb414_dir . 'dashboard.txt')) @file_put_contents($sb414_dir . 'dashboard.txt', $dash, LOCK_EX);
		if (!is_file($sb414_dir . 'settings.txt')) @file_put_contents($sb414_dir . 'settings.txt', '', LOCK_EX);

		$m->deleteConfig('theme');
		$m->deleteConfig('legacy_dashboard_txt');
		$m->deleteConfig('twofa_enabled');
	},
);
