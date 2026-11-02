<?php
/**
 * Module table, migration 1.0.3 : jeton CSRF retiré des données
 *
 * Depuis la 4.44, chaque ajout ou modification d'une ligne enregistrait
 * aussi le champ caché sbuiadmin_csrf_token dans sb_table_datas.content
 * (JSON), affiché ensuite dans l'admin et dans les extraits de la
 * recherche publique. Les entrées « sbuiadmin_csrf_token » sont retirées,
 * le reste de la ligne est gardé tel quel. Relancée : sans effet.
 * Retour : rien à défaire.
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

return array(
	'description' => 'jeton CSRF retiré des données des tableaux',
	'reversible'  => true,

	'up' => function (SbMigration $m) {
		if (!$m->tableExists('sb_table_datas') || !function_exists('sbDb') || !sbDb()) return;
		$rows = sbDb()->all("SELECT id, content FROM {sb_table_datas} WHERE content LIKE '%sbuiadmin\\_csrf\\_token%'");
		foreach ($rows ?: array() as $row) {
			$items = json_decode((string) $row['content'], true);
			if (!is_array($items)) continue;
			$kept = array();
			foreach ($items as $item) {
				if (is_array($item) && ($item['i'] ?? '') === 'sbuiadmin_csrf_token') continue;
				$kept[] = $item;
			}
			if (count($kept) === count($items)) continue;
			// Même encodage qu'à l'enregistrement (backdoor/datas/modules/table.php)
			sbDb()->update('sb_table_datas', array('content' => json_encode($kept, JSON_FORCE_OBJECT)), 'id = ?', array((int) $row['id']));
		}
	},

	'down' => function (SbMigration $m) {
	},
);
