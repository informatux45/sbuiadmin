<?php
/**
 * Module news, migration 1.0.5 : sb_news.viewed avec DEFAULT 0
 *
 * Les bases d'avant les dumps corrigés ont viewed NOT NULL sans valeur par
 * défaut : en mode strict, « Ajouter une actualité » échouait. Colonne
 * absente : ajoutée. Retour : rien à défaire (compatible avec 1.0.4).
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

return array(
	'description' => 'compteur de lectures avec valeur par défaut',
	'reversible'  => true,

	'up' => function (SbMigration $m) {
		if (!$m->tableExists('sb_news')) return;
		$def = "int(11) NOT NULL DEFAULT 0 COMMENT 'Compteur de lectures'";
		if ($m->columnExists('sb_news', 'viewed')) $m->modifyColumn('sb_news', 'viewed', $def);
		else $m->addColumn('sb_news', 'viewed', $def, 'date');
	},

	'down' => function (SbMigration $m) {
	},
);
