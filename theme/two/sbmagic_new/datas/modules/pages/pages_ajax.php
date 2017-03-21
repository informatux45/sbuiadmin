<?php
/**
 * Plugin Name: SbMagic PAGES
 * Description: Gestionnaire de pages libres
 * Version: 0.1.1
 * Agency: Agence DOLLAR
 * Agency URI: //dollar.fr
 * Author: BooBoo
 * Author URI: //www.informatux.com/
 */

// ---------------------------
// SESSION Initialisation
// ---------------------------
session_start();
 
// ----------------------------------------- 
// --- Load Default Include for AJAX Request
// -----------------------------------------
include_once('../../../sbconfig.php');
include_once('../../../header.php');
global $sbsmarty, $sbsanitize, $sbsql, $sbpage;

// ---------------------------
// Define some important stuff
// ---------------------------
define('MODFILE', basename(__FILE__, "_ajax.php"));
define('MODNAME', 'Pages');
define('MODVERSION','0.1.1');

// ---------------------------
// Security Check
// ---------------------------
if (!defined('SB_PATH')) {
	die('You cannot load this file directly!');
}

// ---------------------------
// Include Module Common Infos
// ---------------------------
$modpage['tables']['pages'] = _AM_DB_PREFIX . "sb_pages";

// --------------------------
// --- Switch with Op GET
// --------------------------
$id      = $_POST['id'];
$query   = "SELECT * FROM {$modpage['tables']['pages']} WHERE seo_url = '$id'";
$request = $sbsql->query($query);
$assoc   = $sbsql->assoc($request);
// --- Assign
$sb_pages_ajax    = '';
$sb_pages_title   = $sbsanitize->displayLang(utf8_encode($assoc['title']), $_SESSION['lang']);
$sb_pages_title2  = $sbsanitize->displayLang(utf8_encode($assoc['seo_keywords']), $_SESSION['lang']);
$sb_pages_content = $sbsanitize->displayLang(utf8_encode($assoc['content']), $_SESSION['lang']);
$sb_pages_active  = $assoc['active'];

if ($sb_pages_active) {

	$sb_pages_ajax .= '<h1>' . $sb_pages_title . '</h1>';
	if (trim($sb_pages_title2) != '') $sb_pages_ajax .= '<h2>' . $sbsanitize->stripTags($sb_pages_title2) . '</h2>';
	$sb_pages_ajax .= '<div class="sb_pages_content">' . sbGetShortcode($sb_pages_content) . '</div>';

} else {

	$sb_pages_ajax .= '<h1>Page not found, sorry!</h1>';	

}

echo $sb_pages_ajax;

?>