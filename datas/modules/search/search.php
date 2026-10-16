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
	// --- 2. MODULES INSTALLES : chacun fournit sa source dans
	// --- datas/modules/<nom>/inc/search.php (voir sbSearchProviders())
	// --------------------------------------------------
	foreach (sbSearchProviders() as $provider => $info) {
		$source = 'sbSearchSource_' . $provider;
		if (!function_exists($source)) continue;
		foreach ((array) $source($sb_search_terms, $lang) as $row) {
			$results[] = array_merge($row, array('module' => $provider, 'type' => $info['label']));
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
	// --- Pagination (en PHP : les resultats viennent de plusieurs sources, il n'y a
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
