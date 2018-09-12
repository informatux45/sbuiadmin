<?php
/**
 * Plugin Name: SBUIADMIN NEWS
 * Description: Gestionnaire d'articles
 * Version: 0.1.2
 * Author: BooBoo
 * Author URI: //www.informatux.com/
 */

// Security Check
if (!defined('SB_PATH')) {
	die('You cannot load this file directly!');
}

# Define some important stuff
define('MODULEFILE', basename(__FILE__, ".php"));
define('MODULENAME', 'News');
define('MODULEVERSION','0.1.2');

# Include Module Common Infos
include_once( SB_MODULES_DIR . MODULEFILE . DIRECTORY_SEPARATOR . 'common.php' );
global $module, $sbsmarty, $sbsanitize, $sbsql, $sbpage;

# Include Module Common Infos
$sblang_news = (SBLANG && $_SESSION['lang'] != 'en') ? SBLANG : 'en_US';
include_once( SB_MODULES_DIR . MODULEFILE . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . $sblang_news . '.php' );
include_once( SB_MODULES_DIR . MODULEFILE . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'functions.php' );

// ------------------------
// --- Get News options
// ------------------------
$initOptions     = "SELECT * FROM {$module['tables']['newssett']} WHERE id = '1'";
$request_options = $sbsql->query($initOptions);
$news_options    = $sbsql->assoc($request_options);
$sbsmarty->assign('sbnews_options', $news_options);
// --- Check start index or categories
// --- 0: Categories list
// --- 1: Categories (catids)
if ($news_options['module_start'] && (!$_GET['op'])) {
	$_GET['op'] = 'categories';
	$_GET['id'] = $news_options['catid'];
} elseif (!$news_options['module_start'] && (!$_GET['op'])) {
	$_GET['op'] = false;
	$_GET['id'] = false;
}
// --- Item per page
$news_per_page = $news_options['item_per_page'];

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
	default: // Show all categories of NEWS
		// --- SQL Request
		$query   = "SELECT * FROM {$module['tables']['newscat']} WHERE active = '1' ORDER BY sort ASC";
		$request = $sbsql->query($query);
		$result  = $sbsql->toarray($request);
		// --- Assign Array
		$sbsmarty->assign('categories', $result);
		
		// --------------------------
		// --- Assign Title Page
		// --------------------------
		$sb_news_title = _CMS_NEWS_ALLNEWS;
		// --- Assign news title
		$sbsmarty->assign('sb_pages_title', $sb_news_title);
		//f$sbsmarty->assign('page_id', strtolower(constant(MODULENAME)));

		// --------------------------
		// --- Choose theme view
		// --------------------------
		$module['theme_main'] = ($news_options['theme_view_cat']) ? $news_options['theme_view_cat'] : 'index';

	break;

	case "categories":
		// --- Initialization
		$news_page = (isset($_GET['l'])) ? intval($_GET['l']) : 0;
		// --- Construct WHERE
		$where_categories = "";
		$all_categories   = explode("|", $news_options['catid']);
		if ($all_categories) {
			for($i = 0; $i < count($all_categories); ++$i) {
				$category_id = $all_categories[$i];
				$where_categories .= " (catid LIKE '%$category_id%' AND active = '1')";
				if (($i + 1) < count($all_categories)) $where_categories .= " OR";
			}
		}
		// --- SQL Request
		$initQ = "SELECT * FROM {$module['tables']['news']} WHERE $where_categories ORDER BY date DESC ";
		//die($initQ);
		// --- Total NEWS
		$queryT = $sbsql->query($initQ);
		$totalT = $sbsql->numrows();

		$query   = $initQ . "LIMIT $news_page, $news_per_page";
		$request = $sbsql->query($query);
		$result  = $sbsql->toarray($request);
		// --- Assign Array
		$sbsmarty->assign('all', $result);
		// --- Assign News info Page
		$sbsmarty->assign('news_per_page', $news_per_page);
		$sbsmarty->assign('news_total',$totalT);
		$sbsmarty->assign('news_page', $news_page);
		$sbsmarty->assign('news_total', floor($totalT / $news_per_page));
		
		// --------------------------
		// --- Assign Title Page
		// --------------------------
		$catid_module_show = $news_options['catid_module_show'];
		$queryN   = "SELECT * FROM {$module['tables']['newscat']} WHERE id = '$catid_module_show'";
		$requestN = $sbsql->query($queryN);
		$assoc   = $sbsql->assoc($requestN);
		// --- Infos
		$sb_news_title = _CMS_NEWS_ALLNEWS;
		// --- Assign news title
		$sbsmarty->assign('sb_pages_title', $sb_news_title);
		// --- Assign news category title (template)
		$sbsmarty->assign('sbnews_cat_title', $sb_news_title);
		$sbsmarty->assign('sbnews_cat_subtitle', '');
		$sbsmarty->assign('sbnews_cat_tpl_list', $sbsanitize->sTrim($assoc['tpl_list']));
		$sbsmarty->assign('sbnews_module_show', $assoc['module_show']);
		$sbsmarty->assign('sbnews_module_show_masonry', $assoc['module_show_masonry']);
		// --- Breadcrumb
		$sbsmarty->assign('sbnews_nav1', _CMS_NEWS_ALLNEWS);

		// --------------------------
		// --- Choose theme view
		// --------------------------
		$module['theme_main'] = ($news_options['theme_view_list']) ? $news_options['theme_view_list'] : 'index';
	break;

	case "category": // Show a category of NEWS
		// --- Initialization
		$news_page     = (isset($_GET['l'])) ? intval($_GET['l']) : 0;
		// --- SQL Request
		$initQ = "SELECT * FROM {$module['tables']['news']} WHERE catid LIKE '%$id%' AND active = '1' ORDER BY date DESC ";
		// --- Total NEWS
		$queryT = $sbsql->query($initQ);
		$totalT = $sbsql->numrows();

		$query   = $initQ . "LIMIT $news_page, $news_per_page";
		$request = $sbsql->query($query);
		$result  = $sbsql->toarray($request);
		// --- Assign Array
		$sbsmarty->assign('all', $result);
		// --- Assign News info Page
		$sbsmarty->assign('news_per_page', $news_per_page);
		$sbsmarty->assign('news_total',$totalT);
		$sbsmarty->assign('news_page', $news_page);
		$sbsmarty->assign('news_total', floor($totalT / $news_per_page));
		
		// --------------------------
		// --- Assign Title Page
		// --------------------------
		$queryN   = "SELECT * FROM {$module['tables']['newscat']} WHERE id = '$id'";
		$requestN = $sbsql->query($queryN);
		$assoc   = $sbsql->assoc($requestN);
		// --- Infos
		//$sb_news_title = _CMS_NEWS_NEWS . ' ' . $sbsanitize->displayLang(utf8_encode($assoc['title']), $_SESSION['lang']);
		$sb_news_title = $sbsanitize->displayLang(utf8_encode($assoc['title']), $_SESSION['lang']);
		if ($assoc['subtitle'] != '')
			$sb_news_title .= ' - ' . $sbsanitize->displayLang(utf8_encode($assoc['subtitle']), $_SESSION['lang']);
		// --- Assign news title
		$sbsmarty->assign('sb_pages_title', $sb_news_title);
		//$sbsmarty->assign('page_id', strtolower(sbRewriteString($sbsanitize->displayLang(utf8_encode($assoc['title']), $_SESSION['lang']))));
		// --- Assign news category title (template)
		$sbsmarty->assign('sbnews_cat_title', $sbsanitize->displayLang(utf8_encode($assoc['title']), $_SESSION['lang']));
		$sbsmarty->assign('sbnews_cat_subtitle', $sbsanitize->displayLang(utf8_encode($assoc['subtitle']), $_SESSION['lang']));
		$sbsmarty->assign('sbnews_cat_tpl_list', $sbsanitize->sTrim($assoc['tpl_list']));
		$sbsmarty->assign('sbnews_module_show', $assoc['module_show']);
		$sbsmarty->assign('sbnews_module_show_masonry', $assoc['module_show_masonry']);
		// --- Breadcrumb
		$sbsmarty->assign('sbnews_nav1', $sbsanitize->displayLang(utf8_encode($assoc['title']), $_SESSION['lang']));

		// --------------------------
		// --- Choose theme view
		// --------------------------
		$module['theme_main'] = ($news_options['theme_view_list']) ? $news_options['theme_view_list'] : 'index';

	break;

	case "article": // Show article detail of NEWS
		// --- SQL Request
		$query   = "SELECT * FROM {$module['tables']['news']} WHERE id = '$id' AND active = '1'";
		$request = $sbsql->query($query);
		$item    = $sbsql->assoc($request);
		// --- Assign Array
		$sbsmarty->assign('item', $item);
		
		// --------------------------
		// --- Assign Title Page
		// --------------------------
		$catid    = intval($item['catid']);
		$queryN   = "SELECT id, title, subtitle, tpl_single FROM {$module['tables']['newscat']} WHERE id = '$catid'";
		$requestN = $sbsql->query($queryN);
		$assoc    = $sbsql->assoc($requestN);
		// --- Check if type
		//$sb_news_title = _CMS_NEWS_NEWS . ' ' . $sbsanitize->displayLang(utf8_encode($assoc['title']), $_SESSION['lang']);
		$sb_news_title = $sbsanitize->displayLang(utf8_encode($assoc['title']), $_SESSION['lang']) . ' - ' . $sbsanitize->displayLang(utf8_encode($item['title']), $_SESSION['lang']);
		if ($assoc['subtitle'] != '')
			$sb_news_title .= ' - ' . $sbsanitize->displayLang(utf8_encode($assoc['subtitle']), $_SESSION['lang']);
		// --- Assign news title
		$sbsmarty->assign('sb_pages_title', $sb_news_title);
		//$sbsmarty->assign('page_id', strtolower(sbRewriteString($sbsanitize->displayLang(utf8_encode($item['title']), $_SESSION['lang']))));
		// --- Assign news category title (template)
		$sbsmarty->assign('sbnews_item_cat_id', intval($assoc['id']));
		$sbsmarty->assign('sbnews_item_cat_title', $sbsanitize->displayLang(utf8_encode($assoc['title']), $_SESSION['lang']));
		$sbsmarty->assign('sbnews_item_tpl_single', $sbsanitize->sTrim($assoc['tpl_single']));
		// --- Breadcrumb
		$sbsmarty->assign('sbnews_nav1', $sbsanitize->displayLang(utf8_encode($assoc['title']), $_SESSION['lang']));
		$sbsmarty->assign('sbnews_nav2',  $sbsanitize->displayLang(utf8_encode($item['title']), $_SESSION['lang']));
		
		// --- SQL Request (Next / Prev)
		$query_np = "SELECT (SELECT id FROM {$module['tables']['news']} s1 WHERE s1.date < s.date AND s1.active = 1 AND catid = '$catid' ORDER BY date DESC LIMIT 1 OFFSET 0) as next,
							(SELECT title FROM {$module['tables']['news']} s1 WHERE s1.date < s.date AND s1.active = 1 AND catid = '$catid' ORDER BY date DESC LIMIT 1 OFFSET 0) as next_title,
							(SELECT id FROM {$module['tables']['news']} s2 WHERE s2.date > s.date AND s2.active = 1 AND catid = '$catid' ORDER BY date ASC LIMIT 1 OFFSET 0) as prev,
							(SELECT title FROM {$module['tables']['news']} s2 WHERE s2.date > s.date AND s2.active = 1 AND catid = '$catid' ORDER BY date ASC LIMIT 1 OFFSET 0) as prev_title
							FROM {$module['tables']['news']} s
							WHERE id = $id ";
		$request_np = $sbsql->query($query_np);
		$result_np  = $sbsql->assoc($request_np);
		$sbsmarty->assign('next_prev', $result_np);
		
		// --------------------------
		// --- Other news
		// --------------------------
		if ($news_options['other_news']) {
			$other_news_per_page = intval($news_options['other_news_per_page']);
			// --- SQL Request
			$query   = "SELECT * FROM {$module['tables']['news']} WHERE catid = '$catid' AND active = '1' AND id != '$id' ";
			switch($news_options['other_news_type']) {
				default;
				case "latest":
					$query .= "ORDER BY date DESC LIMIT $other_news_per_page";
				break;
				case "first":
					$query .= "ORDER BY date ASC LIMIT $other_news_per_page";					
				break;
				case "random":
					$query .= "ORDER BY RAND() LIMIT $other_news_per_page";
				break;
			}
			$request = $sbsql->query($query);
			$result  = $sbsql->toarray($request);
			$sbsmarty->assign('sbnews_other_news', $result);
		}
		
		// --------------------------
		// --- Choose theme view
		// --------------------------
		$module['theme_main'] = ($news_options['theme_view_single']) ? $news_options['theme_view_single'] : 'index';

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
