<?php
/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage PluginsModifier
 */

/**
 * Smarty trim modifier plugin
 * Type:     modifier
 * Name:     trim
 * Purpose:  Strip whitespace (or other characters) from the beginning and end of a string
 *
 * @param string           $string
 * @param character|null   $character
 *
 * @return array
 */
function smarty_modifier_trim($string, $character = null) {
    // Strip whitespace (or other characters) from the end of a string
    return trim($string, $character);
}
