<?php
/**
 * Plugin Name: SBUIADMIN SEARCH
 * Description: Module de recherche
 * Version: 0.2.0
 * Author: BooBoo
 * Author URI: //www.informatux.com/
 */

 // Security Check
if (!defined('SB_PATH')) {
	die('You cannot load this file directly!');
}

# Define some important stuff
define('MODULEFILE', basename(__FILE__, ".php"));
define('MODULENAME', 'Search');
define('MODULEVERSION','0.2.0');

# Include Module Common Infos
include_once( SB_MODULES_DIR . MODULEFILE . DIRECTORY_SEPARATOR . 'common.php' );
global $module, $sbsmarty, $sbsanitize, $sbsql, $sbpage;

# Include Module Lang + Functions
$sblang_search = (SBLANG && $_SESSION['lang'] != 'en') ? SBLANG : 'en_US';
include_once( SB_MODULES_DIR . MODULEFILE . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . $sblang_search . '.php' );
include_once( SB_MODULES_DIR . MODULEFILE . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'functions.php' );

// --------------------------------------------------
// --- Vues
// --------------------------------------------------
$module['template_main']        = MODULEFILE . '_index.tpl';
$module['template_main_blocks'] = MODULEFILE . '_index_blocks.tpl';
// --- Utilisee uniquement en acces direct (index.php?p=search). Quand le
// --- module est appele par une page du CMS (module_view = search), c'est la
// --- vue de theme de la page qui s'applique, pas celle-ci.
$module['theme_main']           = 'index-title';

// --- Resultats par page
defined('SB_SEARCH_PER_PAGE') OR define('SB_SEARCH_PER_PAGE', 20);

// --------------------------------------------------
// --- Cache Smarty : DESACTIVE pour ce module.
// --- index.php calcule son cache_id sur REQUEST_URI (Point 23) : avec le
// --- cache actif, chaque valeur de ?s= creerait son propre fichier de cache.
// --- Deux problemes, pas un :
// ---  1. des resultats servis perimes pendant toute la duree de vie du cache
// ---     (une heure par defaut) alors qu'une recherche doit refleter l'etat
// ---     reel du site ;
// ---  2. surtout, un parametre PUBLIC et libre qui pilote la creation de
// ---     fichiers : n'importe quel visiteur peut remplir datas/cache en
// ---     faisant varier ?s= a l'infini.
// --- On n'agit que sur la requete en cours, le reglage global n'est pas touche.
// --------------------------------------------------
$sbsmarty->caching = false;

// --------------------------------------------------
// --- Saisie du visiteur
// --- "s" est le parametre de reference ; "q" est accepte par commodite
// --- (liens externes, anciens signets), et le POST pour un formulaire qui
// --- n'aurait pas ete genere par le shortcode [CS name=sbsearch].
// --------------------------------------------------
$sb_search_raw = '';
if (isset($_GET['s']))       $sb_search_raw = $_GET['s'];
elseif (isset($_GET['q']))   $sb_search_raw = $_GET['q'];
elseif (isset($_POST['s']))  $sb_search_raw = $_POST['s'];
$sb_search_raw = $sbsanitize->stopXSS(trim((string)$sb_search_raw));

$sb_search_terms = sbSearchTerms($sb_search_raw);
$sb_search_page  = (isset($_GET['l'])) ? max(0, intval($_GET['l'])) : 0;

$lang    = (isset($_SESSION['lang']) && $_SESSION['lang'] != '') ? $_SESSION['lang'] : 'fr';
$results = array();

// --------------------------------------------------
// --- Etats sans recherche
// --------------------------------------------------
if ($sb_search_raw === '') {
	// --- Arrivee sur la page sans avoir rien demande
	$sbsmarty->assign('sb_search_state', 'empty');

} elseif (empty($sb_search_terms)) {
	// --- Saisie trop courte : tous les termes ont ete ecartes
	$sbsmarty->assign('sb_search_state', 'tooshort');

} else {
	// ==================================================
	// --- RECHERCHE
	// ==================================================
	$sbsmarty->assign('sb_search_state', 'done');

	// --------------------------------------------------
	// --- 1. PAGES (titre + contenu)
	// --------------------------------------------------
	$where   = sbSearchWhere(array('title', 'content'), $sb_search_terms);
	$query   = "SELECT id, menu, title, seo_url, url_custom, content
				FROM {$module['tables']['pages']}
				WHERE active = '1'" . $where . " ORDER BY sort ASC";
	$request = $sbsql->query($query);
	$rows    = $sbsql->toarray($request);
	if ($rows) {
		foreach ($rows as $row) {
			// --- Le LIKE ne fait que degrossir : il travaille sur la forme
			// --- STOCKEE et confond le mot cherche avec le nom d'une entite
			// --- HTML ("amp", "eacute"...). Voir sbSearchMatches().
			if (!sbSearchMatches(array($row['title'], $row['content']), $sb_search_terms)) continue;
			$results[] = array(
				'module'  => 'pages',
				'type'    => _CMS_SEARCH_SRC_PAGES,
				'title'   => sbSearchPageLabel($row),
				'excerpt' => sbSearchExcerpt($row['content'], $sb_search_terms),
				'url'     => sbSearchPageUrl($row),
				'host'    => false,
			);
		}
	}

	// --------------------------------------------------
	// --- 2. ACTUALITES (titre + sous-titre + chapo + article)
	// --------------------------------------------------
	$where   = sbSearchWhere(array('title', 'subtitle', 'desc_short', 'desc_full'), $sb_search_terms);
	$query   = "SELECT id, title, subtitle, desc_short, desc_full
				FROM {$module['tables']['news']}
				WHERE active = '1'" . $where . " ORDER BY date DESC";
	$request = $sbsql->query($query);
	$rows    = $sbsql->toarray($request);
	if ($rows) {
		foreach ($rows as $row) {
			if (!sbSearchMatches(array($row['title'], $row['subtitle'], $row['desc_short'], $row['desc_full']), $sb_search_terms)) continue;
			$excerpt = sbSearchExcerpt($row['desc_full'], $sb_search_terms);
			if ($excerpt === '') $excerpt = sbSearchExcerpt($row['desc_short'], $sb_search_terms);
			$results[] = array(
				'module'  => 'news',
				'type'    => _CMS_SEARCH_SRC_NEWS,
				'title'   => sbSearchTitle($row['title']),
				'excerpt' => $excerpt,
				'url'     => sbSearchNewsUrl($row['id'], $row['title']),
				'host'    => false,
			);
		}
	}

	// --------------------------------------------------
	// --- 3. FORMULAIRES (titre UNIQUEMENT)
	// --- La table sb_contact contient les DEFINITIONS de formulaires, pas
	// --- les messages recus. La colonne "recipients" porte des adresses
	// --- e-mail et la colonne "form" la structure du formulaire : ni l'une
	// --- ni l'autre ne doit etre cherchee ni exposee a un visiteur.
	// --------------------------------------------------
	$where   = sbSearchWhere(array('title'), $sb_search_terms);
	$query   = "SELECT id, title
				FROM {$module['tables']['contact']}
				WHERE active = '1'" . $where . " ORDER BY sort ASC";
	$request = $sbsql->query($query);
	$rows    = $sbsql->toarray($request);
	if ($rows) {
		foreach ($rows as $row) {
			if (!sbSearchMatches(array($row['title']), $sb_search_terms)) continue;
			$title = sbSearchTitle($row['title']);
			// --- Deux shortcodes possibles pour un meme formulaire
			$host  = sbSearchHostFor('sbcontact', $row['id']);
			if (!$host) $host = sbSearchHostFor('sbcontactajax', $row['id']);
			$results[] = array(
				'module'  => 'contact',
				'type'    => _CMS_SEARCH_SRC_CONTACT,
				'title'   => $title,
				'excerpt' => '',
				'url'     => ($host) ? $host['url'] : false,
				'host'    => ($host) ? $host['label'] : false,
			);
		}
	}

	// --------------------------------------------------
	// --- 4. TABLEAUX (nom du tableau + intitules de colonnes + cellules)
	// --- Trois tables, un seul resultat par tableau : on collecte d'abord
	// --- les ids qui correspondent, avec l'extrait du texte qui a declenche
	// --- la correspondance.
	// --------------------------------------------------
	$hits = array();

	$where   = sbSearchWhere(array('name'), $sb_search_terms);
	$request = $sbsql->query("SELECT id, name FROM {$module['tables']['table']} WHERE active = '1'" . $where);
	$rows    = $sbsql->toarray($request);
	if ($rows) foreach ($rows as $row) $hits[intval($row['id'])] = '';

	$where   = sbSearchWhere(array('content'), $sb_search_terms);
	$request = $sbsql->query("SELECT tid, content FROM {$module['tables']['tabledatas']} WHERE 1 = 1" . $where);
	$rows    = $sbsql->toarray($request);
	if ($rows) foreach ($rows as $row) {
		$tid = intval($row['tid']);
		if (empty($hits[$tid])) $hits[$tid] = $row['content'];
	}

	$where   = sbSearchWhere(array('title'), $sb_search_terms);
	$request = $sbsql->query("SELECT tid, title FROM {$module['tables']['tablestruct']} WHERE active = '1'" . $where);
	$rows    = $sbsql->toarray($request);
	if ($rows) foreach ($rows as $row) {
		$tid = intval($row['tid']);
		if (empty($hits[$tid])) $hits[$tid] = $row['title'];
	}

	if (!empty($hits)) {
		$in      = implode(',', array_map('intval', array_keys($hits)));
		$request = $sbsql->query("SELECT id, name FROM {$module['tables']['table']} WHERE active = '1' AND id IN ($in)");
		$rows    = $sbsql->toarray($request);
		if ($rows) {
			foreach ($rows as $row) {
				$cells = sbSearchTableCells($hits[intval($row['id'])]);
				if (!sbSearchMatches(array($row['name'], $cells), $sb_search_terms)) continue;
				$host = sbSearchHostFor('sbtable', $row['id']);
				$results[] = array(
					'module'  => 'table',
					'type'    => _CMS_SEARCH_SRC_TABLE,
					'title'   => sbSearchTitle($row['name']),
					// --- Les cellules sont stockees en JSON, pas en texte
					'excerpt' => sbSearchExcerpt($cells, $sb_search_terms),
					'url'     => ($host) ? $host['url'] : false,
					'host'    => ($host) ? $host['label'] : false,
				);
			}
		}
	}

	// --------------------------------------------------
	// --- 5. TABBS (nom du groupe + titre et contenu des onglets)
	// --------------------------------------------------
	$hits = array();

	$where   = sbSearchWhere(array('name'), $sb_search_terms);
	$request = $sbsql->query("SELECT id, name FROM {$module['tables']['tabbs']} WHERE active = '1'" . $where);
	$rows    = $sbsql->toarray($request);
	if ($rows) foreach ($rows as $row) $hits[intval($row['id'])] = '';

	$where   = sbSearchWhere(array('title', 'content'), $sb_search_terms);
	$request = $sbsql->query("SELECT tid, title, content FROM {$module['tables']['tabbstab']} WHERE active = '1'" . $where);
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
		$request = $sbsql->query("SELECT id, name FROM {$module['tables']['tabbs']} WHERE active = '1' AND id IN ($in)");
		$rows    = $sbsql->toarray($request);
		if ($rows) {
			foreach ($rows as $row) {
				if (!sbSearchMatches(array($row['name'], $hits[intval($row['id'])]), $sb_search_terms)) continue;
				$host = sbSearchHostFor('sbtabbs', $row['id']);
				$results[] = array(
					'module'  => 'tabbs',
					'type'    => _CMS_SEARCH_SRC_TABBS,
					'title'   => sbSearchTitle($row['name']),
					'excerpt' => sbSearchExcerpt($hits[intval($row['id'])], $sb_search_terms),
					'url'     => ($host) ? $host['url'] : false,
					'host'    => ($host) ? $host['label'] : false,
				);
			}
		}
	}

	// --------------------------------------------------
	// --- 6. TELECHARGEMENTS (titre + description)
	// --- Seul module sans page publique qui possede malgre tout une URL
	// --- propre : celle qui sert le fichier.
	// --------------------------------------------------
	$where   = sbSearchWhere(array('title', 'description'), $sb_search_terms);
	$query   = "SELECT id, title, description, filename, randkey
				FROM {$module['tables']['download']}
				WHERE active = '1'" . $where . " ORDER BY id DESC";
	$request = $sbsql->query($query);
	$rows    = $sbsql->toarray($request);
	if ($rows) {
		foreach ($rows as $row) {
			if (!sbSearchMatches(array($row['title'], $row['description']), $sb_search_terms)) continue;
			$title = sbSearchTitle($row['title']);
			$key   = $sbsanitize->sTrim($row['randkey']);
			// --- Le slug se calcule sur le titre NON echappe : sbSearchTitle()
			// --- passe par htmlSpecialChars et un "&" deviendrait "amp" dans
			// --- l'URL. Le slug n'est que decoratif, la cle porte le routage.
			$slug  = $sbsanitize->stripTags(strtolower(sbRewriteString($sbsanitize->displayLang($row['title'], $lang))));
			$host  = sbSearchHostFor('sbdownload', $row['id']);
			$results[] = array(
				'module'  => 'download',
				'type'    => _CMS_SEARCH_SRC_DOWNLOAD,
				'title'   => $title,
				'excerpt' => sbSearchExcerpt($row['description'], $sb_search_terms),
				'url'     => sbGetSeoUrl("index.php?p=download&k=$key", "download/item/$key/$slug", false),
				'host'    => ($host) ? $host['label'] : false,
			);
		}
	}

	// --------------------------------------------------
	// --- 7. GALERIES (titre de la galerie + titre des photos)
	// --------------------------------------------------
	$hits = array();

	$where   = sbSearchWhere(array('title'), $sb_search_terms);
	$request = $sbsql->query("SELECT id, title FROM {$module['tables']['gallery']} WHERE active = '1'" . $where);
	$rows    = $sbsql->toarray($request);
	if ($rows) foreach ($rows as $row) $hits[intval($row['id'])] = '';

	$where   = sbSearchWhere(array('title'), $sb_search_terms);
	$request = $sbsql->query("SELECT gid, title FROM {$module['tables']['galleryphotos']} WHERE active = '1'" . $where);
	$rows    = $sbsql->toarray($request);
	if ($rows) foreach ($rows as $row) {
		$gid = intval($row['gid']);
		if (empty($hits[$gid])) $hits[$gid] = $row['title'];
	}

	if (!empty($hits)) {
		$in      = implode(',', array_map('intval', array_keys($hits)));
		$request = $sbsql->query("SELECT id, title FROM {$module['tables']['gallery']} WHERE active = '1' AND id IN ($in)");
		$rows    = $sbsql->toarray($request);
		if ($rows) {
			foreach ($rows as $row) {
				if (!sbSearchMatches(array($row['title'], $hits[intval($row['id'])]), $sb_search_terms)) continue;
				$host = sbSearchHostFor('sbgallery', $row['id']);
				$results[] = array(
					'module'  => 'gallery',
					'type'    => _CMS_SEARCH_SRC_GALLERY,
					'title'   => sbSearchTitle($row['title']),
					'excerpt' => sbSearchExcerpt($hits[intval($row['id'])], $sb_search_terms),
					'url'     => ($host) ? $host['url'] : false,
					'host'    => ($host) ? $host['label'] : false,
				);
			}
		}
	}

	// ==================================================
	// --- Tri : un terme present dans le TITRE fait remonter le resultat,
	// --- sinon l'ordre des sources ci-dessus est conserve (tri stable).
	// ==================================================
	$ordered = array();
	foreach ($results as $position => $row) {
		$score = 0;
		foreach ($sb_search_terms as $term) {
			if (mb_stripos($row['title'], $term, 0, 'UTF-8') !== false) { $score = 1; break; }
		}
		$ordered[] = array('score' => $score, 'position' => $position, 'row' => $row);
	}
	usort($ordered, function($a, $b) {
		if ($a['score'] != $b['score']) return ($b['score'] - $a['score']);
		return ($a['position'] - $b['position']);
	});
	$results = array();
	foreach ($ordered as $entry) $results[] = $entry['row'];

	// ==================================================
	// --- Pagination (en PHP : les resultats viennent de 7 sources, il n'y a
	// --- pas de requete unique a limiter avec un LIMIT SQL)
	// ==================================================
	$total = count($results);
	if ($sb_search_page >= $total) $sb_search_page = 0;
	$page_results = array_slice($results, $sb_search_page, SB_SEARCH_PER_PAGE);

	$sbsmarty->assign('sb_search_results', $page_results);
	$sbsmarty->assign('sb_search_total', $total);
	$sbsmarty->assign('sb_search_page', $sb_search_page);
	$sbsmarty->assign('sb_search_per_page', SB_SEARCH_PER_PAGE);
	$sbsmarty->assign('sb_search_prev', ($sb_search_page > 0) ? sbSearchUrl($sb_search_raw, max(0, $sb_search_page - SB_SEARCH_PER_PAGE)) : false);
	$sbsmarty->assign('sb_search_next', (($sb_search_page + SB_SEARCH_PER_PAGE) < $total) ? sbSearchUrl($sb_search_raw, $sb_search_page + SB_SEARCH_PER_PAGE) : false);
}

// --------------------------------------------------
// --- Commun a tous les etats
// --------------------------------------------------
// --- Echappement complet : la saisie du visiteur est reaffichee telle quelle
$sbsmarty->assign('sb_search_query', sbSearchEscape($sb_search_raw));
$sbsmarty->assign('sb_search_min_length', SB_SEARCH_MIN_LENGTH);
$sbsmarty->assign('sb_search_form', shortcode_sbsearch(array()));
$sbsmarty->assign('sb_pages_title', _CMS_SEARCH_TITLE);

?>
