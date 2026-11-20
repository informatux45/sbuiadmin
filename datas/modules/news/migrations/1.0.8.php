<?php
/**
 * Module news, migration 1.0.8 : sous-catégories (sb_news_category.subcat_id)
 *
 * subcat_id = identifiant de la catégorie PARENTE, 0 pour une catégorie
 * principale. Hiérarchie bornée à un niveau (contrôlé par l'admin).
 * Colonne déjà présente (bases héritées des versions 2.x) : son type et sa
 * valeur par défaut sont normalisés, les données gardées.
 * Retour : la colonne est laissée en place (1.0.7 l'ignore), pour ne pas
 * perdre les rattachements au retour en 1.0.8.
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

return array(
	'description' => 'sous-catégories d\'actualités (catégorie parente)',
	'reversible'  => true,

	'up' => function (SbMigration $m) {
		if (!$m->tableExists('sb_news_category')) return;
		$def = "int(11) NOT NULL DEFAULT 0 COMMENT 'Categorie parente (0 : categorie principale)'";
		if ($m->columnExists('sb_news_category', 'subcat_id')) $m->modifyColumn('sb_news_category', 'subcat_id', $def);
		else $m->addColumn('sb_news_category', 'subcat_id', $def, 'id');
	},

	'down' => function (SbMigration $m) {
	},
);
