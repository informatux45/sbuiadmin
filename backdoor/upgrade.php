<?php
/**
 * Admin Startbootstrap
 * UPGRADE SBUIADMIN (backend)
 *
 * @link http://dev.informatux.com/
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */
 
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date dans le passé
 
// ----------------------
// Session Initialization
// ----------------------
// --- Nom de la session : DOIT etre pose avant session_start(), sinon ce
// --- point d'entree repose sa propre session sous PHPSESSID et perd tout
// --- ce que les autres y ont mis. Voir inc/sbsession.php.
require_once(__DIR__ . '/../inc/sbsession.php');
if (session_status() !== PHP_SESSION_ACTIVE) session_start(); // inclus par index.php : session déjà ouverte
 
// ----------------------
// Global defined
// ----------------------
defined('SBUIADMIN_PATH') or define('SBUIADMIN_PATH', dirname(__FILE__));
defined('SBUIADMIN_URL') or define('SBUIADMIN_URL', $_SERVER['SERVER_NAME'].dirname($_SERVER["REQUEST_URI"].'?').'/');
defined('SBUIADMIN_BASE') or define('SBUIADMIN_BASE', basename(__FILE__));
defined('SBUIADMIN_NAME') or define('SBUIADMIN_NAME', 'SBMagic');
defined('SBUIADMIN_ID') or define('SBUIADMIN_ID', 'sbuiadmin');

// ----------------------
// Global include
// ----------------------
include 'inc/sbuiadmin-header.php';
// ----------------------

// ----------------------
// Define Globals
// ----------------------
global $sbdebug, $sbsmarty, $sbsanitize, $sbusers, $sbform, $sbpage, $sbmedias;
// ----------------------
 
// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// Blocking direct access to plugin      -=
// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
defined('SBUIADMIN_PATH') or die('Are you crazy!');

// -----------------------------------------------------------------------
// Ce script est appelé DIRECTEMENT en AJAX (pas via index.php) : le
// "defined(SBUIADMIN_PATH) or die" ci-dessus ne protège rien, la constante
// est définie plus haut dans ce même fichier. N'importe qui pouvait donc
// déclencher une mise à jour qui télécharge du code (en HTTP clair) et
// écrase les fichiers du back-office. On exige une session admin active
// + le droit "modifier" sur la configuration, en POST uniquement.
// -----------------------------------------------------------------------
$sb_upgrade_user = isset($_SESSION['sbuiadmin_user_name']) ? trim($_SESSION['sbuiadmin_user_name']) : '';
if ($_SERVER['REQUEST_METHOD'] !== 'POST'
	|| !_AM_UPGRADE_MODE
	|| $sb_upgrade_user === ''
	|| !$sbusers->checkUserIsActive($sb_upgrade_user)
	|| !sbHasRight('settings', 'edit')) {
	http_response_code(403);
	echo '0|Accès refusé.';
	exit;
}

// ----------------------
// Initialization
// ----------------------
$mode     = isset($_POST['m']) ? $_POST['m'] : 'core';
$return   = '';
$filelist = '';
// ---------------------------------------------------
// ---------------------------------------------------
// Write your own code after these lines
// ---------------------------------------------------
// ---------------------------------------------------
switch($mode) {
	default:
	case "core":
		// --- Check if all files are writables
		if (!$sbupgrade->check_if_are_writable()) {
			echo '0|Tous les fichiers ne sont pas ouvert en écriture !';
			ob_flush(); // the buffer contents are discarded
		} else {
			// --- Files are writables
			foreach ($sbupgrade->writable_files as $file => $value) {
				$filelist .= ($value == 'no') ? $file . " = " . $value . "<br>" : '';
				ob_flush(); // the buffer contents are discarded
			}
			// --- Check if upgrade is good... or not
			if ($sbupgrade->update_files() === true) {
				echo '1|Votre système a été mis à niveau version '.$sbupgrade->server_version;
				ob_flush(); // the buffer contents are discarded
			} else {
				echo '0|Erreur lors de la mise à niveau<br>'.$filelist;
			}
		}
		
	break;

	case "modules":
		
	break;
}

?>

