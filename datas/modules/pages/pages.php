<?php
/**
 * Plugin Name: SBUIADMIN PAGES
 * Description: Gestionnaire de pages libres
 * Version: 0.1.2
 * Author: BooBoo
 * Author URI: //www.informatux.com/
 */

 // Security Check
if (!defined('SB_PATH')) {
	die('You cannot load this file directly!');
}

# Define some important stuff
define('MODFILE', basename(__FILE__, ".php"));
define('MODNAME', 'Pages');
define('MODVERSION','0.1.2');

# Include Module Common Infos
include_once( SB_MODULES_DIR . MODFILE . DIRECTORY_SEPARATOR . 'common.php' );
global $modpage, $sbsmarty, $sbsanitize, $sbsql, $sbpage;

# Define TPL to show (view)
$id       = $sbsanitize->stopXSS($_GET['id']);
$template = 'index';
# Assign template main
$modpage['template_main'] = MODFILE . '_' . $template . '.tpl';

// --------------------------
// --- Switch with Op GET
// --------------------------
$query   = "SELECT * FROM {$modpage['tables']['pages']} WHERE seo_url = '$id'";
$request = $sbsql->query($query);
$assoc   = $sbsql->assoc($request);
// --- Assign Array
$sb_pages_title   = $sbsanitize->displayLang(utf8_encode($assoc['title']), $_SESSION['lang']);
$sb_pages_content = $sbsanitize->displayLang(utf8_encode($assoc['content']), $_SESSION['lang']);
$sb_pages_active  = $assoc['active'];

if ($sb_pages_active) {
	// --------------------------
	// --- Assign:
	// --- Theme view
	// --- Module view
	// --- Page active
	// --------------------------
	$modpage['theme_main'] = $assoc['theme_view'];
	$modpage['module_main'] = $assoc['module_view'];
	
	// --------------------------
	// --- Get Additional HTML Content
	// --------------------------
	$additional_html_content = (!empty($assoc['various_view'])) ? $assoc['various_view'] : false;
	
	// --------------------------
	// --- Assign Title Page
	// --------------------------
	$sbsmarty->assign('sb_title', $sb_pages_title); // Title of Module PAGES
	$sbsmarty->assign('sb_pages_title', $sb_pages_title);
	$sbsmarty->assign('sb_pages_content', sbGetShortcode($sb_pages_content));
	$sbsmarty->assign('sb_pages_various', $additional_html_content);
	
	// --------------------------
	// --- Assign Page ID
	// --------------------------
	$sbsmarty->assign('page_id', $assoc['id']);

	// --------------------------
	// --- Assign Page Seo Url
	// --------------------------
	$sbsmarty->assign('sb_seo_url', $assoc['seo_url']);
	
	// --------------------------
	// --- Assign Page Metas
	// --- 1. Keywords
	// --- 2. Description
	// --------------------------
	$sbsmarty->assign('sb_seo_keywords', $assoc['seo_keywords']);	
	$sbsmarty->assign('sb_seo_description', $assoc['seo_description']);
	
	// --------------------------
	// --- Assign Page Head if exists
	// --------------------------
	if ($assoc['headpage'])
		$sbsmarty->assign('sb_page_headpage', $assoc['headpage']);
	
	// --------------------------
	// --- Assign Image if exists (Banner)
	// --------------------------
	if ($assoc['photo'])
		$sbsmarty->assign('sb_page_image', $assoc['photo']);

	// --------------------------
	// --- Add Template BLOCKS (depends on the theme view choosen)
	// --------------------------
	$modpage['template_main_blocks'] = MODFILE . '_' . $template . '_blocks.tpl';
} else {
	// --------------------------
	// --- Assign Title Page
	// --------------------------
	$sbsmarty->assign('sb_pages_title', _CMS_GLOBAL_PAGE_NOT_FOUND);	
}

// --------------------------
// --- Status to display the page
// --------------------------
$modpage['page_active'] = $assoc['active'];


?>
