<?php
/**
 * Plugin Name: SBUIADMIN DOWNLOAD
 * Description: Gestionnaire de boutons de téléchargement de fichiers
 * Version: 0.1.1
 * Author: BooBoo
 * Author URI: //www.informatux.com/
 */

// Security Check
if (!defined('SB_PATH')) {
	die('You cannot load this file directly!');
}

# Define some important stuff
define('MODULEFILE', basename(__FILE__, ".php"));
define('MODULENAME', 'Download');
define('MODULEVERSION','0.1.1');

# Include Module Common Infos
include_once( SB_MODULES_DIR . MODULEFILE . DIRECTORY_SEPARATOR . 'common.php' );
global $module, $sbsmarty, $sbsanitize, $sbsql, $sbpage;

# Include Module Common Infos
$sblang_news = (SBLANG && $_SESSION['lang'] != 'en') ? SBLANG : 'en_US';
include_once( SB_MODULES_DIR . MODULEFILE . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . $sblang_news . '.php' );
include_once( SB_MODULES_DIR . MODULEFILE . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'functions.php' );

# -------------------------

// --------------------------
// --- Ce module ne rend aucune page : il sert le fichier demandé puis sort.
// --- L'affichage se fait par le shortcode [CS name=sbdownload id=X]
// --- (voir inc/functions.php), qui construit le bouton de téléchargement.
// --------------------------
// --- Récupération de la clé (sbRewriteUrl place le 3e segment dans "id")
$key = '';
if (SBREWRITEURL && isset($_GET['id'])) {
	$key = $sbsanitize->stopXSS($_GET['id']);
} elseif (isset($_GET['k'])) {
	$key = $sbsanitize->stopXSS($_GET['k']);
}

// --- Une clé est un jeton alphanumérique généré par sbGenerateRandKey()
if ($key !== '' && preg_match('/^[A-Za-z0-9]+$/', $key)) {

	$key_sql = $sbsql->escape_string($key);
	$query   = "SELECT * FROM {$module['tables']['download']} WHERE active = '1' AND randkey = '$key_sql'";
	$request = $sbsql->query($query);
	$file    = $sbsql->assoc($request);

	if ($file) {
		// --------------------------
		// --- basename() obligatoire : "filename" vient du formulaire d'admin
		// --- et sortirait de upload/ avec un "../" (traversée de répertoire).
		// --------------------------
		$file_name = basename($sbsanitize->htmlEntitiesDecode($file['filename']));
		$file_dir  = realpath(SB_PATH . 'upload');
		$file_path = ($file_dir !== false) ? realpath($file_dir . DIRECTORY_SEPARATOR . $file_name) : false;

		// --- On ne sert que ce qui est réellement sous upload/
		if ($file_path !== false && is_file($file_path) && strpos($file_path, $file_dir . DIRECTORY_SEPARATOR) === 0) {

			// --- Compteur de téléchargements
			$query_downloaded = "UPDATE {$module['tables']['download']} SET downloaded = downloaded + 1 WHERE randkey = '$key_sql'";
			$sbsql->query($query_downloaded);

			// --- Purge de tout ce qui aurait déjà été émis avant l'en-tête
			while (ob_get_level() > 0) ob_end_clean();

			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="' . $file_name . '"');
			header('Content-Length: ' . filesize($file_path));
			header('X-Content-Type-Options: nosniff');
			readfile($file_path);
			exit();
		}
	}
}

// --------------------------
// --- Clé absente, invalide, inactive, ou fichier manquant sur le disque :
// --- on rend la page 404 du thème plutôt qu'une page blanche.
// --------------------------
http_response_code(404); // page inexistante : un 200 ici fait indexer la 404 par Google
$sbsmarty->display("404.tpl");
exit();

?>