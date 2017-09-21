<?php
/**
 * Admin Startbootstrap
 * Manage Files TRANSFERT
 *
 * @link http://dev.informatux.com/
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// Blocking direct access to plugin      -=
// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
defined('SBMAGIC_PATH') or die('Are you crazy!');

// -----------------------
// Include Config CMS
// -----------------------
include_once('../sbconfig.php');
 
// -----------------------
// Module URL
// -----------------------
$module_page = 'transfert';
$sbsmarty->assign('module_page', $module_page);
// -----------------------
$module_url = _AM_SITE_PROTOCOL . SBMAGIC_URL . SBMAGIC_BASE . '?p=' . $module_page;
$sbsmarty->assign('module_url', $module_url);
 
// -----------------------
// Message status
// -----------------------
$sb_msg_error = false;
$sb_msg_valid = false;

// -----------------------
// Global MEDIAS
// -----------------------
if (isset($_GET['subdir']) && $_GET['subdir'] != '') $sbfiles_medias_dirs_allowed = rtrim($sbfiles_medias_dirs_allowed . DIRECTORY_SEPARATOR . $_GET['subdir'], "/");
global $sbfiles_medias_dirs_allowed, $sbfiles_medias_exts_allowed;

// ---------------------------------------------------
// ---------------------------------------------------
// Write your own code after these lines
// ---------------------------------------------------
// ---------------------------------------------------

// -----------------------
// Scan multiple directories for all files, no sub-dirs
// with an array of extensions
// -----------------------
if ($_GET['ext']) {
	// Form extensions
	$sbfiles_medias_exts_allowed = explode(",", $_GET['ext']);
	$sbfiles_arr = $sbmedias->scan($sbfiles_medias_dirs_allowed, $sbfiles_medias_exts_allowed);
} else {
	// Default (config administration)
	$sbfiles_arr = $sbmedias->scan($sbfiles_medias_dirs_allowed, $sbfiles_medias_exts_allowed);	
}

$sbfiles_new = array();
$sbfiles     = array();

// --- Change the key to filectime 
for($i = 0; $i < count($sbfiles_arr); $i++) {
	if (!is_null($sbfiles_arr[$i])) {
		$key = (filectime($sbfiles_arr[$i])) ? filectime($sbfiles_arr[$i]) : $i;
		$sbfiles_new[$i]['file'] = $sbfiles_arr[$i];
		$sbfiles_new[$i]['time'] = $key;
	}
}
// --- Sort by Last arrived and next by filename
$sbfiles_new = sbArrayOrderby($sbfiles_new, 'time', SORT_DESC, 'file', SORT_ASC);

foreach($sbfiles_new as $key => $val) {
	$sbfiles[] = str_replace("\\", "/", $val['file']);
}

$sbsmarty->assign('medias_all', $sbfiles);


// ---------------------------------------------------
// ---------------------------------------------------
// IMPORTANT: Don't remove these lines
// ---------------------------------------------------
// ---------------------------------------------------
// ----------------------------------------
// ASSIGN Page TITLE - Modify this |
// ----------------------------------------
$sbsmarty->assign('page_title', 'MEDIAS TRANSFERT');

// ----------------------
// ASSIGN Message status
// ----------------------
$sbsmarty->assign('sb_msg_error', $sb_msg_error);
$sbsmarty->assign('sb_msg_valid', $sb_msg_valid);

// ----------------------
// CLOSE SQL (if open)
// ----------------------
// $sbsql->close();

?>
