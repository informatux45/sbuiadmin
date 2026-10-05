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
defined('SBUIADMIN_PATH') or die('Are you crazy!');

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
$module_url = _AM_SITE_PROTOCOL . SBUIADMIN_URL . SBUIADMIN_BASE . '?p=' . $module_page;
$sbsmarty->assign('module_url', $module_url);
 
// -----------------------
// Message status
// -----------------------
$sb_msg_error = false;
$sb_msg_valid = false;

// -----------------------
// Global MEDIAS
// -----------------------
global $sbfiles_medias_dirs_allowed, $sbfiles_medias_exts_allowed;
// Le "global" doit précéder la lecture/modification qui suit - avant ce
// correctif, la ligne ci-dessous s'exécutait avant l'import global et ne
// modifiait donc qu'une variable locale jamais relue par scan() plus bas
// (bug resté latent : "subdir" n'avait jamais servi ailleurs dans le code).
// subdir= : segments [A-Za-z0-9_-] seulement, et le dossier doit rester sous
// celui des médias ("../../" listait toute la racine web). Un sous-dossier
// bien formé mais absent donne une liste vide (créé au premier upload).
$sb_transfert_subdir = '';
$sb_transfert_empty  = false;
if (isset($_GET['subdir']) && is_string($_GET['subdir']) && preg_match('#^[A-Za-z0-9_-]+(/[A-Za-z0-9_-]+)*$#', trim($_GET['subdir'], '/'))) {
	$sb_transfert_base = realpath($sbfiles_medias_dirs_allowed);
	$sb_transfert_dir  = realpath($sbfiles_medias_dirs_allowed . '/' . trim($_GET['subdir'], '/'));
	if ($sb_transfert_base && $sb_transfert_dir === false) {
		$sb_transfert_subdir = trim($_GET['subdir'], '/');
		$sb_transfert_empty  = true;
	} elseif ($sb_transfert_base && is_dir($sb_transfert_dir) && strpos($sb_transfert_dir, $sb_transfert_base . DIRECTORY_SEPARATOR) === 0) {
		$sb_transfert_subdir = trim($_GET['subdir'], '/');
		$sbfiles_medias_dirs_allowed = $sbfiles_medias_dirs_allowed . '/' . $sb_transfert_subdir;
	}
}
// id= est recopié dans les onclick de transfert.tpl
$sb_transfert_id = (isset($_GET['id']) && is_string($_GET['id'])) ? preg_replace('/[^A-Za-z0-9_-]/', '', $_GET['id']) : '';

// ---------------------------------------------------
// ---------------------------------------------------
// Write your own code after these lines
// ---------------------------------------------------
// ---------------------------------------------------

// -----------------------
// Scan multiple directories for all files, no sub-dirs
// with an array of extensions
// -----------------------
// ext= ne peut que restreindre la liste de la configuration : il est
// recopié dans le <script> de transfert.tpl (sans échappement Smarty).
if (!empty($_GET['ext']) && is_string($_GET['ext'])) {
	$sb_transfert_exts = array_values(array_intersect(array_map(function ($e) { return strtolower(trim($e)); }, explode(",", (string) $_GET['ext'])), $sbfiles_medias_exts_allowed));
	if ($sb_transfert_exts) $sbfiles_medias_exts_allowed = $sb_transfert_exts;
}
$sbfiles_arr = $sb_transfert_empty ? array() : $sbmedias->scan($sbfiles_medias_dirs_allowed, $sbfiles_medias_exts_allowed);

$sbfiles_new = array();
$sbfiles     = array();

// Limit files
$limit_files = (isset($_GET['limitfiles']) && intval($_GET['limitfiles']) > 0) ? intval($_GET['limitfiles']) : false;

// --- Change the key to filectime 
for($i = 0; $i < count($sbfiles_arr); $i++) {
	if (!is_null($sbfiles_arr[$i])) {
		$key = (filectime($sbfiles_arr[$i])) ? filectime($sbfiles_arr[$i]) : $i;
		$sbfiles_new[$i]['file'] = $sbfiles_arr[$i];
		$sbfiles_new[$i]['time'] = $key;
		if ($limit_files) {
			// Check if counter reached out
			if ($i == $limit_files) break;
		}
	}
}
// --- Sort by Last arrived and next by filename
$sbfiles_new = sbArrayOrderby($sbfiles_new, 'time', SORT_DESC, 'file', SORT_ASC);

foreach($sbfiles_new as $key => $val) {
	$sbfiles[] = str_replace("\\", "/", $val['file']);
}

$sbsmarty->assign('medias_all', $sbfiles);
// Jamais transmis à Smarty auparavant - transfert.tpl retombait donc
// toujours sur son fallback "(jpg,jpeg,png,gif,pdf,xml,mp4)" (une seule
// chaîne, pas un tableau), qui ne matche jamais aucune extension côté
// Fine Uploader et rejette systématiquement tout upload.
$sbsmarty->assign('sbfiles_medias_exts_allowed', $sbfiles_medias_exts_allowed);
$sbsmarty->assign('sb_transfert_subdir', $sb_transfert_subdir);
$sbsmarty->assign('sb_transfert_id', $sb_transfert_id);


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
