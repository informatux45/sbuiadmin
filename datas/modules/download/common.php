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

// -------------------------------------------------
// --- Global MODULE
// -------------------------------------------------
$module['name']        = 'SBUIADMIN DOWNLOAD';
$module['dirname']     = basename(dirname(__FILE__));
$module['version']     = '0.1.1';
$module['description'] = "Gestionnaire de boutons de téléchargement de fichiers";
$module['author']      = "BooBoo";
// -------------------------------------------------
// --- Tables SQL
// -------------------------------------------------
$module['tables']['download'] = _AM_DB_PREFIX . "sb_download";
// -------------------------------------------------

?>
