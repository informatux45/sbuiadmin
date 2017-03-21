<?php
/**
 * Plugin Name: SbMagic GRADUATES
 * Description: Gestionnaire de meilleurs élèves
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
$module['name']        = 'SbMagic GRADUATES';
$module['dirname']     = basename(dirname(__FILE__));
$module['version']     = '0.1.1';
$module['description'] = "Gestionnaire de meilleurs élèves (equins)";
$module['author']      = "BooBoo";
// -------------------------------------------------
// --- Tables SQL
// -------------------------------------------------
$module['tables']['graduates']     = _AM_DB_PREFIX . "sb_graduates";
$module['tables']['graduatescat']  = _AM_DB_PREFIX . "sb_graduates_category";
$module['tables']['graduatessett'] = _AM_DB_PREFIX . "sb_graduates_settings";
// -------------------------------------------------

?>