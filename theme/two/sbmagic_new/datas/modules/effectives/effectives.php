<?php
/**
 * Plugin Name: SbMagic EFFECTIVES
 * Description: Gestionnaire d'effectifs (equins)
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
define('MODULENAME', 'Effectives');
define('MODULEVERSION','0.1.1');

# Include Module Common Infos
include_once( SB_MODULES_DIR . MODULEFILE . DIRECTORY_SEPARATOR . 'common.php' );
global $module, $sbsmarty, $sbsanitize, $sbsql, $sbpage;

# Include Module Common Infos
$sblang_effectives = (SBLANG && $_SESSION['lang'] != 'en') ? SBLANG : 'en_US';
include_once( SB_MODULES_DIR . MODULEFILE . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . $sblang_effectives . '.php' );
include_once( SB_MODULES_DIR . MODULEFILE . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'functions.php' );

// ------------------------
// --- Get Effectives options
// ------------------------
$initOptions        = "SELECT * FROM {$module['tables']['effectivessett']} WHERE id = '1'";
$request_options    = $sbsql->query($initOptions);
$effectives_options = $sbsql->assoc($request_options);
$sbsmarty->assign('sbeffectives_options', $effectives_options);
// --- Check start index or category
// --- 0: Category list
// --- 1: Category (catid)
if ($effectives_options['module_start'] && (!$_GET['op'])) {
	$_GET['op'] = 'category';
	$_GET['id'] = $effectives_options['catid'];
} elseif (!$news_options['module_start'] && (!$_GET['op'])) {
	$_GET['op'] = false;
	$_GET['id'] = false;
}
// --- Item per page
$effectives_per_page = $effectives_options['effectives_per_page'];
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
	default: // Show all categories of EFFECTIVES
		// --- SQL Request
		$query   = "SELECT * FROM {$module['tables']['effectivescat']} WHERE active = '1' ORDER BY sort ASC";
		$request = $sbsql->query($query);
		$result  = $sbsql->toarray($request);
		// --- Assign Array
		$sbsmarty->assign('categories', $result);
		
		// --------------------------
		// --- Assign Title Page
		// --------------------------
		$sb_modules_title = _CMS_EFFECTIVES_ALL;
		// --- Assign effectives title
		$sbsmarty->assign('sb_pages_title', $sb_modules_title);
		//$sbsmarty->assign('page_id', strtolower(constant(MODULENAME)));

		// --------------------------
		// --- Choose theme view
		// --------------------------
		$module['theme_main'] = ($effectives_options['theme_view_cat']) ? $effectives_options['theme_view_cat'] : 'index';
		
	break;

	case "category": // Show EFFECTIVES From a category
		// --- Initialization
		$effectives_page     = (isset($_GET['l'])) ? intval($_GET['l']) : 0;
		// --- SQL Request
		$initQ = "SELECT * FROM {$module['tables']['effectives']} WHERE catid = '$id' AND active = '1' ORDER BY sort ASC ";
		// --- Total EFFECTIVES
		$queryT = $sbsql->query($initQ);
		$totalT = $sbsql->numrows();

		$query   = $initQ . "LIMIT $effectives_page, $effectives_per_page";
		$request = $sbsql->query($query);
		$result  = $sbsql->toarray($request);
		// --- Assign Array
		$sbsmarty->assign('all', $result);
		// --- Assign Effectives info Page
		$sbsmarty->assign('effectives_per_page', $effectives_per_page);
		$sbsmarty->assign('effectives_total',$totalT);
		$sbsmarty->assign('effectives_page', $effectives_page);
		$sbsmarty->assign('effectives_total', floor($totalT / $effectives_per_page));
		
		// --------------------------
		// --- Assign Title Page
		// --------------------------
		$queryN   = "SELECT * FROM {$module['tables']['effectivescat']} WHERE id = '$id'";
		$requestN = $sbsql->query($queryN);
		$assoc    = $sbsql->assoc($requestN);
		// --- Infos
		//$sb_effectives_title = _CMS_EFFECTIVES . ' ' . $sbsanitize->displayLang(utf8_encode($assoc['title']), $_SESSION['lang']);
		$sb_modules_title = $sbsanitize->displayLang(utf8_encode($assoc['title']), $_SESSION['lang']);
		if ($assoc['subtitle'] != '')
			$sb_modules_title .= ' - ' . $sbsanitize->displayLang(utf8_encode($assoc['subtitle']), $_SESSION['lang']);
		// --- Assign effectives title
		$sbsmarty->assign('sb_pages_title', $sb_modules_title);
		//$sbsmarty->assign('page_id', strtolower(sbRewriteString($sbsanitize->displayLang(utf8_encode($assoc['title']), $_SESSION['lang']))));
		// --- Assign effectives category title (template)
		$sbsmarty->assign('sbeffectives_cat_title', $sbsanitize->displayLang(utf8_encode($assoc['title']), $_SESSION['lang']));
		$sbsmarty->assign('sbeffectives_cat_subtitle', $sbsanitize->displayLang(utf8_encode($assoc['subtitle']), $_SESSION['lang']));
		$sbsmarty->assign('sbeffectives_cat_tpl_list', $sbsanitize->displayText($assoc['tpl_list']));
		$sbsmarty->assign('sbeffectives_module_show', $assoc['module_show']);
		$sbsmarty->assign('sbeffectives_module_show_masonry', $assoc['module_show_masonry']);
		// --- Breadcrumb
		$sbsmarty->assign('sbeffectives_nav1', $sbsanitize->displayLang(utf8_encode($assoc['title']), $_SESSION['lang']));
		
		// --------------------------
		// --- Choose theme view
		// --------------------------
		$module['theme_main'] = ($effectives_options['theme_view_list']) ? $effectives_options['theme_view_list'] : 'index';

	break;

	case "article": // Show article detail of EFFECTIVES
		// --- SQL Request (Detail EFFECTIVE)
		$query   = "SELECT * FROM {$module['tables']['effectives']} WHERE id = '$id' AND active = '1'";
		$request = $sbsql->query($query);
		$item    = $sbsql->assoc($request);
		// --- Assign Array
		$sbsmarty->assign('item', $item);
		
		// --- SQL Request (Medias)
		$query2   = "SELECT * FROM {$module['tables']['effectivesmedia']} WHERE eid = '$id' AND active = '1' ORDER BY sort ASC";
		$request2 = $sbsql->query($query2);
		$item2    = $sbsql->toarray($request2);
		// --- Assign Array
		$sbsmarty->assign('medias', $item2);

		// --- SQL Request (Production)
		$query3   = "SELECT * FROM {$module['tables']['effectivesprod']} WHERE eid = '$id' AND active = '1' ORDER BY sort ASC";
		$request3 = $sbsql->query($query3);
		$item3    = $sbsql->toarray($request3);
		// --- Assign Array
		$sbsmarty->assign('production', $item3);
		
		// --------------------------
		// --- Assign Title Page
		// --------------------------
		$catid    = intval($item['catid']);
		$queryN   = "SELECT id, title, subtitle, tpl_single FROM {$module['tables']['effectivescat']} WHERE id = '$catid'";
		$requestN = $sbsql->query($queryN);
		$assoc    = $sbsql->assoc($requestN);
		// --- Check if type
		//$sb_effectives_title = _CMS_EFFECTIVES . ' ' . $sbsanitize->displayLang(utf8_encode($assoc['title']), $_SESSION['lang']);
		$sb_modules_title = $sbsanitize->displayLang(utf8_encode($assoc['title']), $_SESSION['lang']) . ' ' . $sbsanitize->displayLang(utf8_encode($item['name']), $_SESSION['lang']);
		if ($assoc['subtitle'] != '')
			$sb_modules_title .= ' - ' . $sbsanitize->displayLang(utf8_encode($assoc['subtitle']), $_SESSION['lang']);
		// --- Assign effectives title
		$sbsmarty->assign('sb_pages_title', $sb_modules_title);
		//$sbsmarty->assign('page_id', strtolower(sbRewriteString($sbsanitize->displayLang(utf8_encode($item['name']), $_SESSION['lang']))));
		// --- Assign effectives category title (template)
		$sbsmarty->assign('sbeffectives_item_cat_id', intval($assoc['id']));
		$sbsmarty->assign('sbeffectives_item_cat_title', $sbsanitize->displayLang(utf8_encode($assoc['title']), $_SESSION['lang']));
		$sbsmarty->assign('sbeffectives_item_cat_subtitle', $sbsanitize->displayLang(utf8_encode($assoc['subtitle']), $_SESSION['lang']));
		$sbsmarty->assign('sbeffectives_item_tpl_single', $sbsanitize->displayText($assoc['tpl_single']));
		// --- Breadcrumb
		$sbsmarty->assign('sbeffectives_nav1', $sbsanitize->displayLang(utf8_encode($assoc['title']), $_SESSION['lang']));
		$sbsmarty->assign('sbeffectives_nav2', $sbsanitize->displayLang(utf8_encode($item['name']), $_SESSION['lang']));
		
		// --------------------------
		// --- Assign Page Head if exists
		// --------------------------
		if ($item['headpage'])
			$sbsmarty->assign('sb_page_headpage', $item['headpage']);
			
		// --------------------------
		// --- Choose theme view
		// --------------------------
		$module['theme_main'] = ($effectives_options['theme_view_single']) ? $effectives_options['theme_view_single'] : 'index';
		
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