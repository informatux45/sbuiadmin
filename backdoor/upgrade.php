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
session_start();
 
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

// ----------------------
// Initialization
// ----------------------
$mode     = $_POST['m'];
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

