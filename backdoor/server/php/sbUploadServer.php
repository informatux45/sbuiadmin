<?php
/**
 * Admin Startbootstrap
 * UPLOAD MEDIAS
 *
 * @link http://dev.informatux.com/
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

// -----------------------------------------------------------------------
// Ce script est appelé DIRECTEMENT par l'uploader JS (pas via index.php) -
// il ne passait donc jamais par la vérification des droits du routeur
// (sbHasRight), ni même par une simple vérification de connexion : hors
// mode Adminer (voir/ajouter/supprimer), N'IMPORTE QUI pouvait uploader un
// fichier ici sans être connecté. Bootstrap minimal (sans Smarty/tpl -
// seulement DB + session + droits) pour fermer ça.
// -----------------------------------------------------------------------
defined('SBUIADMIN_PATH') or define('SBUIADMIN_PATH', dirname(__FILE__, 3));
defined('SBUIADMIN_URL')  or define('SBUIADMIN_URL', $_SERVER['SERVER_NAME'] . (isset($_SERVER['SERVER_PORT']) ? ':' . $_SERVER['SERVER_PORT'] : '') . rtrim(dirname($_SERVER['SCRIPT_NAME'], 3), '/') . '/');

session_start([
	'cookie_lifetime' => 86400,
]);

require_once(SBUIADMIN_PATH . '/inc/sbuiadmin-config.php');
require_once(SBUIADMIN_PATH . '/inc/sbuiadmin-rights.php');
require_once(_AM_SMARTY_DIR . 'Smarty.class.php'); // la classe "sql" hérite de Smarty
require_once(SBUIADMIN_PATH . '/inc/class/sbuiadmin-sql.php');
require_once(SBUIADMIN_PATH . '/inc/class/sbuiadmin-sanitize.php');
require_once(SBUIADMIN_PATH . '/inc/class/sbuiadmin-users.php');

$sbsql      = new sql();
$sbsanitize = new sanitize();
$sbusers    = new user();

function sbUploadDeny($message) {
	http_response_code(403);
	header('Content-Type: text/plain');
	echo json_encode(array('success' => false, 'error' => $message));
	exit;
}

if (!isset($_SESSION['sbuiadmin_user_name']) || trim($_SESSION['sbuiadmin_user_name']) == '') {
	sbUploadDeny('Non authentifié.');
}
if (!sbHasRight('medias', 'add')) {
	sbUploadDeny('Droit "ajouter" requis sur les médias.');
}

// Include the uploader class
require_once '../../server/php/qqFileUploader.php';

// Get Settings
$sb_upload_config = file('../../inc/admin/settings.txt');

// File path
$sbfiles_medias_subdir = (isset($_REQUEST['subdir']) && $_REQUEST['subdir'] != '') ? '/' . rtrim($_REQUEST['subdir'], "/") : '';
$sbfiles_medias_dir = '../../' . trim($sb_upload_config[6]) . $sbfiles_medias_subdir;

$uploader = new qqFileUploader();

// Specify the list of valid extensions, ex. array("jpeg", "xml", "bmp")
$uploader->allowedExtensions = array();

// Specify max file size in bytes.
//$uploader->sizeLimit = 10 * 1024 * 1024;

// Specify the input name set in the javascript.
$uploader->inputName = 'qqfile';

// If you want to use resume feature for uploader, specify the folder to save parts.
$uploader->chunksFolder = 'chunks';

// Call handleUpload() with the name of the folder, relative to PHP's getcwd()
$result = $uploader->handleUpload($sbfiles_medias_dir);
// To save the upload with a specified name, set the second parameter.
//$result = $uploader->handleUpload($sbfiles_medias_dir, sbRewriteString($uploader->getUploadName()));

// To return a name used for uploaded file you can use the following line.
$result['uploadName'] = $uploader->getUploadName();


header("Content-Type: text/plain");
echo json_encode($result);
