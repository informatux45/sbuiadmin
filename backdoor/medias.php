<?php
/**
 * Admin Startbootstrap
 * Manage MEDIAS
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
defined('SBUIADMIN_PATH') or die('Are you crazy!');

 
// -----------------------
// Module URL
// -----------------------
$module_page = 'medias';
$sbsmarty->assign('module_page', $module_page);
// -----------------------
$module_url = _AM_SITE_PROTOCOL . SBUIADMIN_URL . SBUIADMIN_BASE . '?p=' . $module_page;
$sbsmarty->assign('module_url', $module_url);

// -----------------------
// Module Short URL
// -----------------------
$module_short_url = SBUIADMIN_BASE . '?p=' . $module_page;
$sbsmarty->assign('module_short_url', $module_short_url); 
 
// -----------------------
// Message status
// -----------------------
$sb_msg_error = false;
$sb_msg_valid = false;

// -----------------------
// Global MEDIAS
// -----------------------
global $sbfiles_medias_dirs_allowed, $sbfiles_medias_exts_allowed;

// Get ID of the input for DOM Inject
$sbid = intval($_GET['id']);

// -----------------------
// Remove a media from the list
// -----------------------
if (isset($_GET['del']) && $_GET['del'] != '') {
	$media_name = $_GET['del'];
	if (!file_exists($media_name)) {
		// File does not exist
		$sb_msg_error = "Erreur : Le fichier n'existe pas (DEL) !";
	} else {
		$remove_file = @unlink($media_name);
		if ($remove_file)
			$sb_msg_valid = 'Fichier supprimé avec succès';
		else
			$sb_msg_error = 'Error: Write Error (DEL)!';
	}
}

// -----------------------
// Scan multiple directories for all files, no sub-dirs
// with an array of extensions
// -----------------------
$sbfiles_arr = $sbmedias->scan(_AM_MEDIAS_DIR, $sbfiles_medias_exts_allowed);
$sbfiles_new = [];
$sbfiles     = [];

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

// --- ASSIGN sbfile medias infos
$sbsmarty->assign('sbfiles_medias_exts_allowed', $sbfiles_medias_exts_allowed);
$sbsmarty->assign('sbfiles_medias_dirs_allowed', $sbfiles_medias_dirs_allowed);

// --- ASSIGN Infos ini_get
$sbsmarty->assign('media_ini_get_post_max_size', sbIniGet('post_max_size'));
$sbsmarty->assign('media_ini_get_upload_max_filesize', sbIniGet('upload_max_filesize'));
$sbsmarty->assign('media_ini_get_file_uploads', sbIniGet('file_uploads'));

// --- ASSIGN Infos Uploads
$sbtimestamp = time();
$sbsmarty->assign('sbtimestamp', $sbtimestamp);
$sbsmarty->assign('sbtoken', md5('unique_salt' . $sbtimestamp));

// ---------------------------------------------------
// ---------------------------------------------------
// IMPORTANT: Don't remove these lines
// ---------------------------------------------------
// ---------------------------------------------------
// ----------------------------------------
// ASSIGN Page TITLE - Modify this |
// ----------------------------------------
$sbsmarty->assign('page_title', 'MEDIAS');

// ----------------------
// ASSIGN Message status
// ----------------------
$sbsmarty->assign('sb_msg_error', $sb_msg_error);
$sbsmarty->assign('sb_msg_valid', $sb_msg_valid);

// ----------------------
// CLOSE SQL
// ----------------------
// $sbsql->close();

?>