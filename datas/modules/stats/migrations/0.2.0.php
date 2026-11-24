<?php
/**
 * Module stats, migration 0.2.0 : jeton de page vue (sb_stats_action.pv),
 * qui relie les messages du traceur JS (beacon.php) à la page servie.
 * Retour : la colonne et son index supprimés.
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

return array(
	'description' => 'jeton de page vue pour le traceur JS',
	'reversible'  => true,

	'up' => function (SbMigration $m) {
		$m->addColumn('sb_stats_action', 'pv', "char(16) NOT NULL DEFAULT '' COMMENT 'jeton de page vue (traceur JS)'", 'visit_id');
		$m->addIndex('sb_stats_action', 'pv', array('pv'));
	},

	'down' => function (SbMigration $m) {
		$m->dropIndex('sb_stats_action', 'pv');
		$m->dropColumn('sb_stats_action', 'pv');
	},
);
