<?php
/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage PluginsModifier
 */

/**
 * Smarty ltrim modifier plugin
 * Type:     modifier
 * Name:     ltrim
 * Purpose:  Strip whitespace (or other characters) from the beginning of a string
 *
 * @param string           $string
 * @param character|null   $character
 *
 * @return array
 */
function smarty_modifier_ltrim($string, $character = null) {
    // Strip whitespace (or other characters) from the end of a string
    // PHP 8.1+ : null en 2e argument (ou en chaine) est deprecie, PHP 9 le refusera
    $string = (string) ($string ?? '');
    return ($character === null) ? ltrim($string) : ltrim($string, (string) $character);
}
