<?php
/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage PluginsModifier
 */

/**
 * Smarty sbDisplayLang modifier plugin
 * Type:     modifier
 * Name:     sbDisplayLang
 * Purpose:  Return lang string
 *
 * @param string   $string
 * @param lang|fr  $lang
 *
 * @return string
 */
function smarty_modifier_sbDisplayLang($string, $lang = "fr") {
    // Show the language session (fr OR en OR ...)
    return sbDisplayLang($string, $lang = "fr");
}
