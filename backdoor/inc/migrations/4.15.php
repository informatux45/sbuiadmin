<?php
/**
 * Migration 4.15 : JavaScript d'exemple de l'installation corrigé
 *
 * Le JavaScript personnalisé livré à l'installation (CMS config > Header /
 * Footer) tenait sur une ligne avec un commentaire « // » qui avalait la
 * fin du code : « SyntaxError: Unexpected end of input » sur tout le front.
 * Seules les deux valeurs d'exemple d'origine sont remplacées (jamais un
 * code saisi par l'utilisateur). Sans retour : la valeur corrigée reste
 * valable avec la version précédente.
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

return array(
	'description' => "JavaScript d'exemple de l'installation corrigé",
	'reversible'  => true,

	'up' => function (SbMigration $m) {
		$db = sbSettingsDb();
		if (!$db) throw new RuntimeException('Base indisponible');
		$fixed = 'jQuery(document).ready(function() { /* Recherche cach&eacute;e */ jQuery(&#039;#votrediv&#039;).css(&#039;color&#039;,&#039;red&#039;); });';
		$old = array(
			"jQuery(document).ready(function() { \t// Recherche cach&eacute;e \tjQuery(&#039;#votrediv&#039;).css(&#039;color&#039;,&#039;red&#039;); });",
			"jQuery(document).ready(function() {rn\t// Recherche cach&eacute;ern\tjQuery(&#039;#votrediv&#039;).css(&#039;color&#039;,&#039;red&#039;);rn});",
		);
		$q = function ($v) use ($db) { return "'" . $db->real_escape_string($v) . "'"; };
		$m->query('UPDATE `' . $m->table('sb_config') . '` SET `content` = ' . $q($fixed)
			. " WHERE `config` = 'javascript' AND `content` IN (" . implode(', ', array_map($q, $old)) . ')');
	},

	'down' => function (SbMigration $m) {
		// Rien à défaire : la valeur corrigée fonctionne aussi en 4.14
	},
);
