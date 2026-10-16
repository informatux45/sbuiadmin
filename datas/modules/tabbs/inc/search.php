<?php
/**
 * Module tabbs : source de la recherche du site (module search, core)
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
* TABBS (nom du groupe + titre et contenu des onglets)
* @return array resultats ('title', 'excerpt', 'url', 'host')
*/
if (!function_exists("sbSearchSource_tabbs")) {
	function sbSearchSource_tabbs($terms, $lang) {
		global $sbsql;
		$results = array();
		$hits    = array();
		$t_tabbs = sbSearchTable('sb_tabbs');

		$where   = sbSearchWhere(array('name'), $terms);
		$request = $sbsql->query("SELECT id, name FROM {$t_tabbs} WHERE active = '1'" . $where);
		$rows    = $sbsql->toarray($request);
		if ($rows) foreach ($rows as $row) $hits[intval($row['id'])] = '';

		$where   = sbSearchWhere(array('title', 'content'), $terms);
		$request = $sbsql->query("SELECT tid, title, content FROM " . sbSearchTable('sb_tabbs_tab') . " WHERE active = '1'" . $where);
		$rows    = $sbsql->toarray($request);
		if ($rows) foreach ($rows as $row) {
			$tid = intval($row['tid']);
			// --- Concatenation de deux champs MULTILINGUES : sans danger ici car
			// --- sbSearchDecoded() extrait TOUS les blocs [fr]...[/fr], la ou
			// --- displayLang() ne lit que le premier et jetterait le second.
			if (empty($hits[$tid])) $hits[$tid] = $row['content'] . ' ' . $row['title'];
		}

		if (!empty($hits)) {
			$in      = implode(',', array_map('intval', array_keys($hits)));
			$request = $sbsql->query("SELECT id, name FROM {$t_tabbs} WHERE active = '1' AND id IN ($in)");
			$rows    = $sbsql->toarray($request);
			if ($rows) {
				foreach ($rows as $row) {
					if (!sbSearchMatches(array($row['name'], $hits[intval($row['id'])]), $terms)) continue;
					$host = sbSearchHostFor('sbtabbs', $row['id']);
					$results[] = array(
						'title'   => sbSearchTitle($row['name']),
						'excerpt' => sbSearchExcerpt($hits[intval($row['id'])], $terms),
						'url'     => ($host) ? $host['url'] : false,
						'host'    => ($host) ? $host['label'] : false,
					);
				}
			}
		}
		return $results;
	}
}

/**
* Hotes de shortcodes, en deux sauts : le contenu d'un onglet peut porter un
* shortcode, mais l'onglet lui-meme n'a pas d'URL - il s'affiche via
* [CS name=sbtabbs id=<groupe>], dont l'hote est deja indexe (pages,
* actualites). On resout donc l'onglet vers l'hote de son groupe.
*/
if (!function_exists("sbSearchHosts_tabbs")) {
	function sbSearchHosts_tabbs($collect, array &$index) {
		global $sbsql;
		$query   = "SELECT tid, content
					FROM " . sbSearchTable('sb_tabbs_tab') . "
					WHERE active = '1' AND content LIKE '%[CS %'";
		$request = $sbsql->query($query);
		$rows    = $sbsql->toarray($request);
		if ($rows) {
			foreach ($rows as $row) {
				$host = isset($index['sbtabbs'][intval($row['tid'])]) ? $index['sbtabbs'][intval($row['tid'])] : false;
				if ($host) $collect($row['content'], $host['url'], $host['label']);
			}
		}
	}
}

?>
