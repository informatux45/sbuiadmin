<?php
/**
 * Module sentinelle, migration 1.2.0 : adresses et plages bannies
 * (sb_sentinelle_ban). Retour : la table supprimée.
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

return array(
	'description' => 'adresses et plages bannies',
	'reversible'  => true,

	'up' => function (SbMigration $m) {
		$m->createTable('sb_sentinelle_ban', "
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`ip_range` varchar(50) NOT NULL DEFAULT '' COMMENT 'adresse ou plage CIDR',
			`reason` varchar(255) NOT NULL DEFAULT '',
			`source` varchar(10) NOT NULL DEFAULT 'manual' COMMENT 'manual, auto',
			`created_at` datetime NOT NULL,
			`expires_at` datetime DEFAULT NULL COMMENT 'NULL : permanent',
			`hits` int(11) NOT NULL DEFAULT 0,
			`last_hit` datetime DEFAULT NULL,
			PRIMARY KEY (`id`),
			KEY `expires_at` (`expires_at`)");
	},

	'down' => function (SbMigration $m) {
		$m->dropTable('sb_sentinelle_ban');
	},
);
