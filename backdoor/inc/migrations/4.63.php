<?php
/**
 * Migration 4.63 : inc/admin/theme.txt supprimé
 *
 * Le thème est en base depuis la 4.14 (réglage « theme » de sb_config) et le
 * core ne lit plus le fichier. Il est GARDÉ quand le sbconfig.php du site le
 * lit encore (ancien sbconfig.php, jamais remplacé par les mises à jour : il
 * s'arrête sans lui).
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

$sb463_file = SBUIADMIN_PATH . '/inc/admin/theme.txt';

/** Le sbconfig.php du site lit-il encore theme.txt ? */
$sb463_needed = function () {
	$cfg = @file_get_contents(dirname(SBUIADMIN_PATH) . '/sbconfig.php');
	return $cfg === false || strpos($cfg, 'theme.txt') !== false;
};

return array(
	'description' => 'fichier theme.txt retiré (thème en base)',
	'reversible'  => true,

	'up' => function (SbMigration $m) use ($sb463_file, $sb463_needed) {
		if (!is_file($sb463_file)) return;
		// Thème resté vide en base (ligne créée vide avant la 4.14) : repris du fichier
		$ok = function ($name) {
			return is_string($name) && preg_match('/^[A-Za-z0-9_-]{1,64}$/', $name) && is_dir(dirname(SBUIADMIN_PATH) . '/theme/' . $name);
		};
		$db = sbSettingsDb();
		if (!$db) throw new RuntimeException('Base indisponible');
		$t = $m->table('sb_config');
		$r = $db->query("SELECT `content` FROM `$t` WHERE `config` = 'theme'");
		if ($r === false) throw new RuntimeException('Requête en échec : ' . $db->error);
		$row = $r->fetch_row();
		if (!$row || !$ok((string) $row[0])) {
			$lines = @file($sb463_file, FILE_IGNORE_NEW_LINES);
			$theme = $lines ? trim((string) $lines[0]) : '';
			if (!$ok($theme)) return; // rien d'utilisable : fichier gardé
			$m->query($row
				? "UPDATE `$t` SET `content` = '$theme' WHERE `config` = 'theme'"
				: "INSERT INTO `$t` (`config`, `content`) VALUES ('theme', '$theme')");
		}
		if (!$sb463_needed()) @unlink($sb463_file);
	},

	// Rien à recréer : le core de la 4.62 lit d'abord le thème en base
	'down' => function (SbMigration $m) {
	},
);
