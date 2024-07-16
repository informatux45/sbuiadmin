<?php

/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage PluginsModifierCompiler
 */
/**
 * Smarty intval modifier plugin
 * Type:     modifier
 * Name:     intval
 * Purpose:  Get the integer value of a variable
 *
 * @param array $params parameters
 *
 * @return string with compiled code
 */
function smarty_modifiercompiler_intval($params) {
    return 'intval((int) ' . $params[ 0 ] . ')';
}
