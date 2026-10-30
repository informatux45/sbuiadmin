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

// --- Nom de la session : DOIT etre pose avant session_start(), sinon ce
// --- point d'entree repose sa propre session sous PHPSESSID et perd tout
// --- ce que les autres y ont mis. Voir inc/sbsession.php.
require_once(__DIR__ . '/../../../inc/sbsession.php');
session_start(sbSessionStartOptions([
	'cookie_lifetime' => 86400,
]));

require_once(SBUIADMIN_PATH . '/inc/sbuiadmin-config.php');
require_once(SBUIADMIN_PATH . '/inc/sbuiadmin-rights.php');
require_once(_AM_SMARTY_DIR . 'Smarty.class.php');
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

// Jeton CSRF (même jeton de session que les formulaires de l'admin, envoyé
// par Fine Uploader dans le corps de la requête)
if (empty($_SESSION['sbuiadmin_csrf_token']) || !isset($_POST['sbuiadmin_csrf_token']) || !is_string($_POST['sbuiadmin_csrf_token'])
	|| !hash_equals($_SESSION['sbuiadmin_csrf_token'], $_POST['sbuiadmin_csrf_token'])) {
	sbUploadDeny('Jeton de sécurité invalide, rechargez la page.');
}
session_write_close(); // plus rien à écrire : ne bloque pas les autres requêtes

// Include the uploader class
require_once '../../server/php/qqFileUploader.php';

/** Taille du réglage (« 10M », « 10MB », « 512KB »...) en octets, 0 si illisible */
function sbUploadLimitBytes($v) {
	if (!preg_match('/^\s*(\d+(?:[.,]\d+)?)\s*([KMGT]?)B?\s*$/i', (string) $v, $m)) return 0;
	return (int) round((float) str_replace(',', '.', $m[1]) * pow(1024, (int) strpos('_KMGT', strtoupper($m[2] ?: '_'))));
}

/**
 * Le contenu correspond-il à l'extension ? (un .jpg doit être une image, un
 * .pdf un PDF...). Les images sont redimensionnées par le navigateur avant
 * l'envoi, qui peut changer leur format : toute image reconnue est acceptée.
 */
function sbUploadContentMatches($file, $ext) {
	$head = (string) @file_get_contents($file, false, null, 0, 1024);
	switch ($ext) {
		case 'jpg': case 'jpeg': case 'png': case 'gif': case 'webp':
			$info = @getimagesize($file);
			$types = array(IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF);
			if (defined('IMAGETYPE_WEBP')) $types[] = IMAGETYPE_WEBP;
			return $info !== false && in_array($info[2], $types, true);
		case 'pdf':           return strpos($head, '%PDF-') !== false;
		case 'mp4': case 'm4v': return substr($head, 4, 4) === 'ftyp';
		case 'zip':           return strncmp($head, "PK\x03\x04", 4) === 0 || strncmp($head, "PK\x05\x06", 4) === 0;
		case 'gz':            return strncmp($head, "\x1f\x8b", 2) === 0;
	}
	return true;
}


// -----------------------------------------------------------------------
// Être connecté ne suffit pas : allowedExtensions est vide (tout type
// accepté, .php compris) et subdir / qqfilename / qquuid arrivaient tels
// quels jusqu'au chemin d'écriture ("../" = écriture n'importe où sous
// la racine web).
// -----------------------------------------------------------------------
foreach (array('subdir', 'qqfilename', 'qquuid') as $sb_upload_param) {
	if (isset($_REQUEST[$sb_upload_param]) && strpos($_REQUEST[$sb_upload_param], "\0") !== false) {
		sbUploadDeny('Chemin invalide.');
	}
}
// ".." refusé dans subdir seulement : pour le nom, basename() plus bas
// suffit, et "Programme..pdf" doit rester accepté.
if (isset($_REQUEST['subdir']) && strpos($_REQUEST['subdir'], '..') !== false) {
	sbUploadDeny('Chemin invalide.');
}
if (isset($_REQUEST['qquuid']) && !preg_match('/^[A-Za-z0-9-]{1,64}$/', $_REQUEST['qquuid'])) {
	sbUploadDeny('Identifiant invalide.');
}
$sb_upload_name = isset($_REQUEST['qqfilename']) ? $_REQUEST['qqfilename'] : (isset($_FILES['qqfile']['name']) ? $_FILES['qqfile']['name'] : '');
$sb_upload_name = basename(str_replace('\\', '/', $sb_upload_name));
// Chaque segment après un point est testé, pas seulement le dernier :
// "shell.php.jpg" passe sur un Apache avec AddHandler mal réglé.
$sb_upload_parts = explode('.', strtolower($sb_upload_name));
array_shift($sb_upload_parts);
foreach ($sb_upload_parts as $sb_upload_ext) {
	if (preg_match('/^(php\d*|phtml|phar|pht|phps|cgi|pl|py|sh|shtml|asp|aspx|jsp)$/', $sb_upload_ext)) {
		sbUploadDeny('Type de fichier interdit.');
	}
}
if (in_array(strtolower($sb_upload_name), array('.htaccess', '.user.ini', 'web.config')) || $sb_upload_name === '' || $sb_upload_name[0] === '.') {
	sbUploadDeny('Nom de fichier interdit.');
}

// File path
// subdir= : segments [A-Za-z0-9_-], et le dossier réel (liens symboliques
// résolus) doit exister sous celui des médias.
$sbfiles_medias_subdir = '';
if (isset($_REQUEST['subdir'])) {
	if (!is_string($_REQUEST['subdir'])) sbUploadDeny('Chemin invalide.');
	$sb_upload_subdir = trim($_REQUEST['subdir'], '/');
	if ($sb_upload_subdir !== '') {
		if (!preg_match('#^[A-Za-z0-9_-]+(/[A-Za-z0-9_-]+)*$#', $sb_upload_subdir)) sbUploadDeny('Chemin invalide.');
		$sbfiles_medias_subdir = '/' . $sb_upload_subdir;
	}
}
$sbfiles_medias_dir = '../../' . _AM_MEDIAS_DIR . $sbfiles_medias_subdir;
$sb_upload_root = realpath('../../' . _AM_MEDIAS_DIR);
$sb_upload_real = realpath($sbfiles_medias_dir);
if (!$sb_upload_root || !$sb_upload_real || !is_dir($sb_upload_real)
	|| ($sb_upload_real !== $sb_upload_root && strpos($sb_upload_real . DIRECTORY_SEPARATOR, $sb_upload_root . DIRECTORY_SEPARATOR) !== 0)) {
	sbUploadDeny('Dossier de destination invalide.');
}

$uploader = new qqFileUploader();

// Specify the list of valid extensions, ex. array("jpeg", "xml", "bmp")
// --- Liste blanche : la liste noire ci-dessus arrête les scripts serveur,
// --- mais laissait passer .html / .svg / .xml, servis depuis le domaine du
// --- site avec leur JavaScript. Liste calculée dans sbuiadmin-config.php
// --- (réglage borné à une liste sûre). Vide = qqFileUploader accepte TOUT.
if (empty($sbfiles_medias_exts_allowed)) {
	sbUploadDeny('Aucun type de fichier autorisé (voir Configuration).');
}
$uploader->allowedExtensions = $sbfiles_medias_exts_allowed;

// Taille max. : celle de la Configuration, appliquée ici aussi (Fine Uploader
// ne la contrôlait que dans le navigateur), sans dépasser celle de PHP
$sb_upload_limit = sbUploadLimitBytes(_AM_MEDIAS_SIZE_LIMIT);
if ($sb_upload_limit > 0 && $sb_upload_limit < $uploader->sizeLimit) $uploader->sizeLimit = $sb_upload_limit;

// Envoi en plusieurs morceaux non pris en charge (dossier chunks absent) :
// refusé d'emblée, il échappait au contrôle du contenu et de la taille totale
if (isset($_REQUEST['qqtotalparts']) && (int) $_REQUEST['qqtotalparts'] > 1) {
	sbUploadDeny('Envoi en plusieurs morceaux non pris en charge.');
}

// Contenu réel du fichier reçu
$sb_upload_ext = strtolower(pathinfo($sb_upload_name, PATHINFO_EXTENSION));
if (!isset($_FILES['qqfile']['tmp_name']) || !is_uploaded_file($_FILES['qqfile']['tmp_name'])) {
	sbUploadDeny('Aucun fichier reçu.');
}
if (!sbUploadContentMatches($_FILES['qqfile']['tmp_name'], $sb_upload_ext)) {
	sbUploadDeny('Le contenu du fichier ne correspond pas à son extension (.' . $sb_upload_ext . ').');
}

// Specify the input name set in the javascript.
$uploader->inputName = 'qqfile';

// If you want to use resume feature for uploader, specify the folder to save parts.
$uploader->chunksFolder = 'chunks';

// Call handleUpload() with the name of the folder, relative to PHP's getcwd()
// Nom déjà assaini ci-dessus (basename) : ne pas laisser handleUpload()
// relire qqfilename brut.
$result = $uploader->handleUpload($sbfiles_medias_dir, $sb_upload_name);
// To save the upload with a specified name, set the second parameter.
//$result = $uploader->handleUpload($sbfiles_medias_dir, sbRewriteString($uploader->getUploadName()));

// To return a name used for uploaded file you can use the following line.
$result['uploadName'] = $uploader->getUploadName();


header("Content-Type: text/plain");
echo json_encode($result);
