<?php
/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage PluginsModifier
 */

/**
 * Smarty sbConvertDate modifier plugin
 * Type:     modifier
 * Name:     sbConvertDate
 * Purpose:  Show date ISO in various format, this method return string to display
 *
 * @param string      $string
 * @param format|ISO  $format
 *
 * @return string
 */
function smarty_modifier_sbConvertDate($string, $format = 'ISO') {
    // Get a valid string without illegal characters
    return sbConvertDate($string, $format);
}
