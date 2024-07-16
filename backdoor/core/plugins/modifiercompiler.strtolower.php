<?php
/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage PluginsModifierCompiler
 */
/**
 * Smarty strtolower modifier plugin
 * Type:     modifier
 * Name:     strtolower
 * Purpose:  convert string to lowercase
 *
 * @link   https://www.smarty.net/manual/en/language.modifier.lower.php lower (Smarty online manual)
 * @author Monte Ohrt <monte at ohrt dot com>
 * @author Uwe Tews
 *
 * @param array $params parameters
 *
 * @return string with compiled code
 */
function smarty_modifiercompiler_strtolower($params)
{
    if (Smarty::$_MBSTRING) {
        return 'mb_strtolower((string) ' . $params[ 0 ] . ', \'' . addslashes(Smarty::$_CHARSET) . '\')';
    }
    // no MBString fallback
    return 'strtolower((string) ' . $params[ 0 ] . ')';
}
