<?php
/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage PluginsModifier
 */

/**
 * Smarty sbRewriteString modifier plugin
 * Type:     modifier
 * Name:     sbRewriteString
 * Purpose:  Return rewrite string
 *
 * @param string   $string
 *
 * @return string
 */
function smarty_modifier_sbRewriteString($string) {
    // Get a valid string without illegal characters
    return sbRewriteString($string);
}
