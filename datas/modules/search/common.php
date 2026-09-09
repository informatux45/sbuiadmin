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
// --- Ce module ne possede aucune table : il interroge celles des autres
// --- modules. Les tables declarees ici sont donc celles des 7 sources de
// --- contenu couvertes par la recherche (demande du client), plus sb_blocs
// --- qui sert uniquement a retrouver le contenu HOTE d'un shortcode.
// -------------------------------------------------
$module['tables']['pages']         = _AM_DB_PREFIX . "sb_pages";
$module['tables']['news']          = _AM_DB_PREFIX . "sb_news";
$module['tables']['contact']       = _AM_DB_PREFIX . "sb_contact";
$module['tables']['table']         = _AM_DB_PREFIX . "sb_table";
$module['tables']['tabledatas']    = _AM_DB_PREFIX . "sb_table_datas";
$module['tables']['tablestruct']   = _AM_DB_PREFIX . "sb_table_structure";
$module['tables']['tabbs']         = _AM_DB_PREFIX . "sb_tabbs";
$module['tables']['tabbstab']      = _AM_DB_PREFIX . "sb_tabbs_tab";
$module['tables']['download']      = _AM_DB_PREFIX . "sb_download";
$module['tables']['gallery']       = _AM_DB_PREFIX . "sb_gallery";
$module['tables']['galleryphotos'] = _AM_DB_PREFIX . "sb_gallery_photos";
$module['tables']['blocs']         = _AM_DB_PREFIX . "sb_blocs";
// -------------------------------------------------

?>
