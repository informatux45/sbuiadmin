<?php
/**
 * Plugin Name: SBUIADMIN SEARCH
 * Description: Module de recherche
 * Version: 0.2.0
 * Author: BooBoo
 * Author URI: //www.informatux.com/
 * File: common.php
 */

 // Security Check
if (!defined('SB_PATH')) {
	die('You cannot load this file directly!');
}

// -------------------------------------------------
// --- Global MODULE
// -------------------------------------------------
$module['name']        = 'SBUIADMIN SEARCH';
$module['dirname']     = basename(dirname(__FILE__));
$module['version']     = MODULEVERSION;
$module['description'] = "Module de recherche";
$module['author']      = "BooBoo";
// -------------------------------------------------
// --- Tables SQL
// -------------------------------------------------
// --- Ce module ne possede aucune table : il interroge celles des pages et
// --- des blocs (hote d'un shortcode). Les modules installes fournissent leur
// --- propre source (datas/modules/<nom>/inc/search.php).
// -------------------------------------------------
$module['tables']['pages']         = _AM_DB_PREFIX . "sb_pages";
$module['tables']['blocs']         = _AM_DB_PREFIX . "sb_blocs";
// -------------------------------------------------

?>
