<?php
/**
 * Plugin Name: SBUIADMIN NEWS
 * Description: Gestionnaire d'articles
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
$module['name']        = 'SbMagic NEWS';
$module['dirname']     = basename(dirname(__FILE__));
$module['version']     = '0.1.1';
$module['description'] = "Gestionnaire d'articles";
$module['author']      = "BooBoo";
// -------------------------------------------------
// --- Tables SQL
// -------------------------------------------------
$module['tables']['news']     = _AM_DB_PREFIX . "sb_news";
$module['tables']['newscat']  = _AM_DB_PREFIX . "sb_news_category";
$module['tables']['newssett'] = _AM_DB_PREFIX . "sb_news_settings";
// -------------------------------------------------

?>