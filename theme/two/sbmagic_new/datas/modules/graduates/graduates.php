<?php
/**
 * Plugin Name: SbMagic GRADUATES
 * Description: Gestionnaire de meilleurs élèves (equins)
 * Version: 0.1.1
 * Agency: Agence DOLLAR
 * Agency URI: //dollar.fr
 * Author: BooBoo
 * Author URI: //www.informatux.com/
 */

// Security Check
if (!defined('SB_PATH')) {
	die('You cannot load this file directly!');
}

# Define some important stuff
define('MODULEFILE', basename(__FILE__, ".php"));
define('MODULENAME', 'Graduates');
define('MODULEVERSION','0.1.1');

# Include Module Common Infos
include_once( SB_MODULES_DIR . MODULEFILE . DIRECTORY_SEPARATOR . 'common.php' );
global $module, $sbsmarty, $sbsanitize, $sbsql, $sbpage;

# Include Module Common Infos
$sblang_graduates = (SBLANG && $_SESSION['lang'] != 'en') ? SBLANG : 'en_US';
include_once( SB_MODULES_DIR . MODULEFILE . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . $sblang_graduates . '.php' );
include_once( SB_MODULES_DIR . MODULEFILE . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'functions.php' );

// ------------------------
// --- Get Graduates options
// ------------------------
$initOptions       = "SELECT * FROM {$module['tables']['graduatessett']} WHERE id = '1'";
$request_options   = $sbsql->query($initOptions);
$graduates_options = $sbsql->assoc($request_options);
$sbsmarty->assign('sbgraduates_options', $graduates_options);
// --- Check start index or category
// --- 0: Category list
// --- 1: Category (catid)
if ($graduates_options['module_start'] && (!$_GET['op'])) {
	$_GET['op'] = 'category';
	$_GET['id'] = $graduates_options['catid'];
} elseif (!$news_options['module_start'] && (!$_GET['op'])) {
	$_GET['op'] = false;
	$_GET['id'] = false;
}
// --- Item per page
$graduates_per_page = $graduates_options['graduates_per_page'];
// ------------------------

# -------------------------
# Define TPL to show (view)
if (!$_GET['op'] && !$_GET['id']) {
	$op       = 'index';
	$template = 'index';
} else {
	$op       = $sbsanitize->addSlashes($_GET['op']);
	$id       = intval($_GET['id']);
	$template = 'display_'.$op;
}
$module['template_main'] = MODULEFILE . '_' . $template . '.tpl';
# -------------------------

// --------------------------
// --- Switch with Op GET
// --------------------------
switch($op) {
	default: // Show all categories of GRADUATES
		// --- SQL Request
		$query   = "SELECT * FROM {$module['tables']['graduatescat']} WHERE active = '1' ORDER BY sort ASC";
		$request = $sbsql->query($query);
		$result  = $sbsql->toarray($request);
		// --- Assign Array
		$sbsmarty->assign('categories', $result);
		
		// --------------------------
		// --- Assign Title Page
		// --------------------------
		$sb_modules_title = _CMS_GRADUATES_ALL;
		// --- Assign graduates title
		$sbsmarty->assign('sb_pages_title', $sb_modules_title);
		//$sbsmarty->assign('page_id', strtolower(constant(MODULENAME)));

		// --------------------------
		// --- Choose theme view
		// --------------------------
		$module['theme_main'] = ($graduates_options['theme_view_cat']) ? $graduates_options['theme_view_cat'] : 'index';
		
	break;

	case "category": // Show GRADUATES From a category
		// --- Initialization
		$graduates_page     = (isset($_GET['l'])) ? intval($_GET['l']) : 0;
		// --- SQL Request
		$initQ = "SELECT * FROM {$module['tables']['graduates']} WHERE catid = '$id' AND active = '1' ORDER BY sort ASC ";
		// --- Total GRADUATES
		$queryT = $sbsql->query($initQ);
		$totalT = $sbsql->numrows();

		$query   = $initQ . "LIMIT $graduates_page, $graduates_per_page";
		$request = $sbsql->query($query);
		$result  = $sbsql->toarray($request);
		// --- Assign Array
		$sbsmarty->assign('all', $result);
		// --- Assign Graduates info Page
		$sbsmarty->assign('graduates_per_page', $graduates_per_page);
		$sbsmarty->assign('graduates_total',$totalT);
		$sbsmarty->assign('graduates_page', $graduates_page);
		$sbsmarty->assign('graduates_total', floor($totalT / $graduates_per_page));
		
		// --------------------------
		// --- Assign Title Page
		// --------------------------
		$queryN   = "SELECT * FROM {$module['tables']['graduatescat']} WHERE id = '$id'";
		$requestN = $sbsql->query($queryN);
		$assoc    = $sbsql->assoc($requestN);
		// --- Infos
		//$sb_graduates_title = _CMS_GRADUATES . ' ' . $sbsanitize->displayLang(utf8_encode($assoc['title']), $_SESSION['lang']);
		$sb_modules_title = $sbsanitize->displayLang(utf8_encode($assoc['title']), $_SESSION['lang']);
		if ($assoc['subtitle'] != '')
			$sb_modules_title .= ' - ' . $sbsanitize->displayLang(utf8_encode($assoc['subtitle']), $_SESSION['lang']);
		// --- Assign graduates title
		$sbsmarty->assign('sb_pages_title', $sb_modules_title);
		//$sbsmarty->assign('page_id', strtolower(sbRewriteString($sbsanitize->displayLang(utf8_encode($assoc['title']), $_SESSION['lang']))));
		// --- Assign graduates category title (template)
		$sbsmarty->assign('sbgraduates_cat_title', $sbsanitize->displayLang(utf8_encode($assoc['title']), $_SESSION['lang']));
		$sbsmarty->assign('sbgraduates_cat_subtitle', $sbsanitize->displayLang(utf8_encode($assoc['subtitle']), $_SESSION['lang']));
		$sbsmarty->assign('sbgraduates_cat_tpl_list', $sbsanitize->sTrim($assoc['tpl_list']));
		$sbsmarty->assign('sbgraduates_module_show', $assoc['module_show']);
		$sbsmarty->assign('sbgraduates_module_show_masonry', $assoc['module_show_masonry']);
		// --- Breadcrumb
		$sbsmarty->assign('sbgraduates_nav1', $sbsanitize->displayLang(utf8_encode($assoc['title']), $_SESSION['lang']));
		
		// --------------------------
		// --- Choose theme view
		// --------------------------
		$module['theme_main'] = ($graduates_options['theme_view_list']) ? $graduates_options['theme_view_list'] : 'index';

	break;

	case "article": // Show article detail of GRADUATES
		// --- SQL Request (Detail GRADUATE)
		$query   = "SELECT * FROM {$module['tables']['graduates']} WHERE id = '$id' AND active = '1'";
		$request = $sbsql->query($query);
		$item    = $sbsql->assoc($request);
		// --- Assign Array
		$sbsmarty->assign('item', $item);

		// --------------------------
		// --- Assign Title Page
		// --------------------------
		$catid    = intval($item['catid']);
		$queryN   = "SELECT id, title, subtitle, tpl_single FROM {$module['tables']['graduatescat']} WHERE id = '$catid'";
		$requestN = $sbsql->query($queryN);
		$assoc    = $sbsql->assoc($requestN);
		// --- Check if type
		//$sb_graduates_title = _CMS_GRADUATES . ' ' . $sbsanitize->displayLang(utf8_encode($assoc['title']), $_SESSION['lang']);
		$sb_modules_title = $sbsanitize->displayLang(utf8_encode($assoc['title']), $_SESSION['lang']) . ' ' . $sbsanitize->displayLang(utf8_encode($item['name']), $_SESSION['lang']);
		if ($assoc['subtitle'] != '')
			$sb_modules_title .= ' - ' . $sbsanitize->displayLang(utf8_encode($assoc['subtitle']), $_SESSION['lang']);
		// --- Assign graduates title
		$sbsmarty->assign('sb_pages_title', $sb_modules_title);
		//$sbsmarty->assign('page_id', strtolower(sbRewriteString($sbsanitize->displayLang(utf8_encode($item['name']), $_SESSION['lang']))));
		// --- Assign graduates category title (template)
		$sbsmarty->assign('sbgraduates_item_cat_id', intval($assoc['id']));
		$sbsmarty->assign('sbgraduates_item_cat_title', $sbsanitize->displayLang(utf8_encode($assoc['title']), $_SESSION['lang']));
		$sbsmarty->assign('sbgraduates_item_cat_subtitle', $sbsanitize->displayLang(utf8_encode($assoc['subtitle']), $_SESSION['lang']));
		$sbsmarty->assign('sbgraduates_item_tpl_single', $sbsanitize->sTrim($assoc['tpl_single']));
		// --- Breadcrumb
		$sbsmarty->assign('sbgraduates_nav1', $sbsanitize->displayLang(utf8_encode($assoc['title']), $_SESSION['lang']));
		$sbsmarty->assign('sbgraduates_nav2', $sbsanitize->displayLang(utf8_encode($item['name']), $_SESSION['lang']));
		
		// --------------------------
		// --- Assign Page Head if exists
		// --------------------------
		if ($item['headpage'])
			$sbsmarty->assign('sb_page_headpage', $item['headpage']);
			
		// --------------------------
		// --- Choose theme view
		// --------------------------
		$module['theme_main'] = ($graduates_options['theme_view_single']) ? $graduates_options['theme_view_single'] : 'index';
		
	break;
}

// --------------------------
// --- Assign:
// --- Module view
// --- Page active
// --------------------------
//$module['module_main'] = $assoc['module_view'];

// --------------------------
// --- Add Template BLOCKS (depends on the theme view choosen)
// --------------------------
$module['template_main_blocks'] = MODULEFILE . '_index_blocks.tpl';

?>