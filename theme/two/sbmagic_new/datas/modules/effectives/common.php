<?php
/**
 * Plugin Name: SbMagic NEWS
 * Description: Gestionnaire d'articles
 * Version: 0.1.1
 * Agency: Agence DOLLAR
 * Agency URI: //dollar.fr
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
$module['name']        = 'SbMagic EFFECTIFS';
$module['dirname']     = basename(dirname(__FILE__));
$module['version']     = '0.1.1';
$module['description'] = "Gestionnaire d'effectifs (equins)";
$module['author']      = "BooBoo";
// -------------------------------------------------
// --- Tables SQL
// -------------------------------------------------
$module['tables']['effectives']      = _AM_DB_PREFIX . "sb_effectives";
$module['tables']['effectivescat']   = _AM_DB_PREFIX . "sb_effectives_category";
$module['tables']['effectivessett']  = _AM_DB_PREFIX . "sb_effectives_settings";
$module['tables']['effectivesmedia'] = _AM_DB_PREFIX . "sb_effectives_medias";
$module['tables']['effectivesprod']  = _AM_DB_PREFIX . "sb_effectives_production";
// -------------------------------------------------

?>