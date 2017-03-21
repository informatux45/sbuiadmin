<?php
/**
 * Plugin Name: SBUIADMIN Slider
 * Description: Générateur de slider / carousel
 * Version: 0.1.1
 * Author: BooBoo
 * Author URI: //www.informatux.com/
 */

 // Security Check
if (!defined('SB_PATH')) {
	die('You cannot load this file directly!');
}

# Define some important stuff
define('MODFILE', basename(__FILE__, ".php"));
define('MODNAME', 'Slider');
define('MODVERSION','0.1.1');

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
$query   = "SELECT * FROM {$modpage['tables']['slider']} WHERE id = '$id'";
$request = $sbsql->query($query);
$assoc   = $sbsql->assoc($request);
// --- Assign Array
$sb_slider_title   = $sbsanitize->displayLang(utf8_encode($assoc['title']), $_SESSION['lang']);
$sb_slider_content = $sbsanitize->displayLang(utf8_encode($assoc['content']), $_SESSION['lang']);
$sb_slider_active  = $assoc['active'];
$sbsmarty->assign('sb_pages_content', $sb_pages_content);

if ($sb_slider_active) {
	// --------------------------
	// ......
	// --------------------------
	// .......
	
	// --------------------------
	// --- Add Template BLOCKS (depends on the theme view choosen)
	// --------------------------
	$modpage['template_main_blocks'] = MODFILE . '_' . $template . '_blocks.tpl';
} else {
	// --------------------------
	// --- Assign Title Page
	// --------------------------
	$sbsmarty->assign('sb_pages_title', 'Page not found, sorry!');	
}

// --------------------------
// --- Status to display the page
// --------------------------
$modpage['page_active'] = $sb_slider_active;


?>