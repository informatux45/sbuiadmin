<?php
/**
 * Migration 4.29 : registre des modules installés (sb_modules)
 *
 * Une ligne par module installé (voir inc/sbuiadmin-modules.php) ; un module
 * présent sur le disque sans ligne n'est plus chargé. Tous les modules
 * présents au passage de la migration sont inscrits : le site reste
 * identique. Retour : la table supprimée, tout module présent redevient
 * chargé, comme en 4.28.
 *
 * Depuis l'admin, une migration est exécutée par le code de la version
 * PRÉCÉDENTE (déjà en mémoire) : le registre des modules, nouveau en 4.29,
 * n'y est pas chargé. Il l'est ici, depuis le disque (copié avant les
 * migrations).
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');
if (!function_exists('sbModulesPresent')) require_once SBUIADMIN_PATH . '/inc/sbuiadmin-modules.php';

return array(
	'description' => 'registre des modules installés',
	'reversible'  => true,

	'up' => function (SbMigration $m) {
		$m->createTable('sb_modules', "
			`name` varchar(32) NOT NULL,
			`version` varchar(20) NOT NULL,
			`db_version` varchar(20) NOT NULL DEFAULT '',
			`installed_at` datetime NOT NULL,
			`updated_at` datetime DEFAULT NULL,
			PRIMARY KEY (`name`)");
		$db = sbSettingsDb();
		if (!$db) throw new RuntimeException('Base indisponible');
		foreach (sbModulesPresent(true) as $name => $meta) {
			$m->query('INSERT IGNORE INTO `' . $m->table('sb_modules') . '` (`name`, `version`, `db_version`, `installed_at`) VALUES ('
				. "'" . $db->real_escape_string($name) . "', '" . $db->real_escape_string($meta['version']) . "', '" . $db->real_escape_string($meta['version']) . "', NOW())");
		}
		sbModulesInstalled(true);
	},

	'down' => function (SbMigration $m) {
		$m->dropTable('sb_modules');
		sbModulesInstalled(true);
	},
);
