<?php
/**
 * Module news, migration 1.0.9 : gabarits de catégorie en MEDIUMTEXT
 *
 * tpl_list et tpl_single (gabarits Smarty saisis dans l'admin, CSS et JS
 * embarqués compris) étaient en TEXT, limité à 65 535 octets : au-delà,
 * MySQL strict refuse l'enregistrement, sinon le gabarit est tronqué.
 * Passés en MEDIUMTEXT (16 Mo), toujours NULL permis. Une colonne déjà en
 * MEDIUMTEXT ou LONGTEXT n'est pas touchée. SQLite : rien (type sans limite).
 * Retour : rien à défaire (1.0.8 lit la colonne quel que soit son type).
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

return array(
	'description' => 'gabarits de catégorie jusqu\'à 16 Mo (MEDIUMTEXT)',
	'reversible'  => true,

	'up' => function (SbMigration $m) {
		if (!$m->tableExists('sb_news_category')) return;
		$db = function_exists('sbDb') ? sbDb() : null;
		if (!$db || $db->driver() === 'sqlite') return;
		foreach (array('tpl_list', 'tpl_single') as $col) {
			if (!$m->columnExists('sb_news_category', $col)) continue;
			$type = strtolower((string) $db->value('SELECT DATA_TYPE FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?', array($m->table('sb_news_category'), $col)));
			if (in_array($type, array('mediumtext', 'longtext'), true)) continue;
			$m->modifyColumn('sb_news_category', $col, 'mediumtext NULL');
		}
	},

	'down' => function (SbMigration $m) {
	},
);
