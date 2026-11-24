<?php
/**
 * Module stats, migration 0.3.0 : rapports calculés par jour
 * (sb_stats_daily), comme l'archivage de Matomo. Retour : la table supprimée.
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

return array(
	'description' => 'rapports calculés par jour',
	'reversible'  => true,

	'up' => function (SbMigration $m) {
		$m->createTable('sb_stats_daily', "
  `day` date NOT NULL,
  `report` varchar(20) NOT NULL DEFAULT '',
  `label` varchar(255) NOT NULL DEFAULT '',
  `visits` int(11) NOT NULL DEFAULT 0,
  `visitors` int(11) NOT NULL DEFAULT 0,
  `actions` int(11) NOT NULL DEFAULT 0,
  `hits` int(11) NOT NULL DEFAULT 0 COMMENT 'pages vues, clics',
  `duration` int(11) NOT NULL DEFAULT 0 COMMENT 'secondes (somme)',
  `bounces` int(11) NOT NULL DEFAULT 0,
  `entries` int(11) NOT NULL DEFAULT 0,
  `exits` int(11) NOT NULL DEFAULT 0,
  UNIQUE KEY `day_report_label` (`day`, `report`, `label`),
  KEY `report_day` (`report`, `day`)", 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci');
	},

	'down' => function (SbMigration $m) {
		$m->dropTable('sb_stats_daily');
	},
);
