<?php
/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage PluginsModifier
 */

/**
 * Smarty sbGetShortcode modifier plugin
 * Type:     modifier
 * Name:     sbGetShortcode
 * Purpose:  Get Shortcode for page
 *
 * @param string        $string
 * @param smarty|false  $format
 *
 * @return html string
 */
function smarty_modifier_sbGetShortcode($string, $smarty = false) {
    // Get a valid string without illegal characters
    return sbGetShortcode($string, $smarty);
}
