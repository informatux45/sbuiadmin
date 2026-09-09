<?php
/**
 * Plugin Name: SBUIADMIN GALLERY
 * Description: Gestionnaire de galerie photos / videos
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
$module['name']        = 'SBUIADMIN GALLERY';
$module['dirname']     = basename(dirname(__FILE__));
$module['version']     = '0.1.1';
$module['description'] = "Gestionnaire de galerie photos / videos";
$module['author']      = "BooBoo";
// -------------------------------------------------
// --- Tables SQL
// -------------------------------------------------
$module['tables']['gallery']       = _AM_DB_PREFIX . "sb_gallery";
$module['tables']['galleryphotos'] = _AM_DB_PREFIX . "sb_gallery_photos";
// --- Pas de table de réglages pour ce module : gallerysett était déclarée
// --- ici alors que sb_gallery_settings n'a jamais existé en base.
// --- Le module ne rend aucune page, il s'utilise via le shortcode
// --- [CS name=sbgallery id=X] (voir inc/functions.php).
// -------------------------------------------------

?>
