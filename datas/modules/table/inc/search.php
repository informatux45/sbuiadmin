<?php
/**
 * Module table : source de la recherche du site (module search, core)
 * Chargé par sbSearchProviders() quand le module est installé.
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

if (!defined('SB_PATH')) {
	die('You cannot load this file directly!');
}

/**
* Valeurs d'une ligne de tableau.
*
* sb_table_datas.content ne contient pas du texte mais du JSON produit par
* l'administration : {"0":{"i":"<champ>","v":"<valeur>"}, ...}. Sans ce
* decodage, l'extrait affiche au visiteur serait le JSON brut, accolades et
* noms de champs compris.
* @param	string	$json
* @return	string	valeurs concatenees, ou la chaine d'origine si ce n'est
*					pas du JSON exploitable
*/
if (!function_exists("sbSearchTableCells")) {
	function sbSearchTableCells($json) {
		$decoded = json_decode((string)$json, true);
		if (!is_array($decoded)) return (string)$json;

		$values = array();
		foreach ($decoded as $cell) {
			if (is_array($cell) && isset($cell['v']) && trim((string)$cell['v']) !== '') $values[] = $cell['v'];
		}

		return (empty($values)) ? '' : implode(' - ', $values);
	}
}

/**
* TABLEAUX (nom du tableau + intitules de colonnes + cellules)
* Trois tables, un seul resultat par tableau : on collecte d'abord les ids
* qui correspondent, avec l'extrait du texte qui a declenche la correspondance.
* @return array resultats ('title', 'excerpt', 'url', 'host')
*/
if (!function_exists("sbSearchSource_table")) {
	function sbSearchSource_table($terms, $lang) {
		global $sbsql;
		$results = array();
		$hits    = array();
		$t_table = sbSearchTable('sb_table');

		$where   = sbSearchWhere(array('name'), $terms);
		$request = $sbsql->query("SELECT id, name FROM {$t_table} WHERE active = '1'" . $where);
		$rows    = $sbsql->toarray($request);
		if ($rows) foreach ($rows as $row) $hits[intval($row['id'])] = '';

		$where   = sbSearchWhere(array('content'), $terms);
		$request = $sbsql->query("SELECT tid, content FROM " . sbSearchTable('sb_table_datas') . " WHERE 1 = 1" . $where);
		$rows    = $sbsql->toarray($request);
		if ($rows) foreach ($rows as $row) {
			$tid = intval($row['tid']);
			if (empty($hits[$tid])) $hits[$tid] = $row['content'];
		}

		$where   = sbSearchWhere(array('title'), $terms);
		$request = $sbsql->query("SELECT tid, title FROM " . sbSearchTable('sb_table_structure') . " WHERE active = '1'" . $where);
		$rows    = $sbsql->toarray($request);
		if ($rows) foreach ($rows as $row) {
			$tid = intval($row['tid']);
			if (empty($hits[$tid])) $hits[$tid] = $row['title'];
		}

		if (!empty($hits)) {
			$in      = implode(',', array_map('intval', array_keys($hits)));
			$request = $sbsql->query("SELECT id, name FROM {$t_table} WHERE active = '1' AND id IN ($in)");
			$rows    = $sbsql->toarray($request);
			if ($rows) {
				foreach ($rows as $row) {
					$cells = sbSearchTableCells($hits[intval($row['id'])]);
					if (!sbSearchMatches(array($row['name'], $cells), $terms)) continue;
					$host = sbSearchHostFor('sbtable', $row['id']);
					$results[] = array(
						'title'   => sbSearchTitle($row['name']),
						// --- Les cellules sont stockees en JSON, pas en texte
						'excerpt' => sbSearchExcerpt($cells, $terms),
						'url'     => ($host) ? $host['url'] : false,
						'host'    => ($host) ? $host['label'] : false,
					);
				}
			}
		}
		return $results;
	}
}

?>
