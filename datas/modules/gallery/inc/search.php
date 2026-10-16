<?php
/**
 * Module gallery : source de la recherche du site (module search, core)
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
* GALERIES (titre de la galerie + titre des photos)
* @return array resultats ('title', 'excerpt', 'url', 'host')
*/
if (!function_exists("sbSearchSource_gallery")) {
	function sbSearchSource_gallery($terms, $lang) {
		global $sbsql;
		$results   = array();
		$hits      = array();
		$t_gallery = sbSearchTable('sb_gallery');

		$where   = sbSearchWhere(array('title'), $terms);
		$request = $sbsql->query("SELECT id, title FROM {$t_gallery} WHERE active = '1'" . $where);
		$rows    = $sbsql->toarray($request);
		if ($rows) foreach ($rows as $row) $hits[intval($row['id'])] = '';

		$where   = sbSearchWhere(array('title'), $terms);
		$request = $sbsql->query("SELECT gid, title FROM " . sbSearchTable('sb_gallery_photos') . " WHERE active = '1'" . $where);
		$rows    = $sbsql->toarray($request);
		if ($rows) foreach ($rows as $row) {
			$gid = intval($row['gid']);
			if (empty($hits[$gid])) $hits[$gid] = $row['title'];
		}

		if (!empty($hits)) {
			$in      = implode(',', array_map('intval', array_keys($hits)));
			$request = $sbsql->query("SELECT id, title FROM {$t_gallery} WHERE active = '1' AND id IN ($in)");
			$rows    = $sbsql->toarray($request);
			if ($rows) {
				foreach ($rows as $row) {
					if (!sbSearchMatches(array($row['title'], $hits[intval($row['id'])]), $terms)) continue;
					$host = sbSearchHostFor('sbgallery', $row['id']);
					$results[] = array(
						'title'   => sbSearchTitle($row['title']),
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

?>
