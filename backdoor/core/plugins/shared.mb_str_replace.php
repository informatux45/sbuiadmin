<?php
/**
 * Smarty shared plugin
 *
 * @package    Smarty
 * @subpackage PluginsShared
 */
if (!function_exists('smarty_mb_str_replace')) {
    /**
     * Multibyte string replace
     *
     * @param string|string[] $search  the string to be searched
     * @param string|string[] $replace the replacement string
     * @param string          $subject the source string
     * @param int             &$count  number of matches found
     *
     * @return string replaced string
     * @author Rodney Rehm
     */
    function smarty_mb_str_replace($search, $replace, $subject, &$count = 0)
    {
        if (!is_array($search) && is_array($replace)) {
            return false;
        }
        if (is_array($subject)) {
            // call mb_replace for each single string in $subject
            foreach ($subject as &$string) {
                $string = smarty_mb_str_replace($search, $replace, $string, $c);
                $count += $c;
            }
        } elseif (is_array($search)) {
            if (!is_array($replace)) {
                foreach ($search as &$string) {
                    $subject = smarty_mb_str_replace($string, $replace, $subject, $c);
                    $count += $c;
                }
            } else {
                $n = max(count($search), count($replace));
                while ($n--) {
                    $subject = smarty_mb_str_replace(current($search), current($replace), $subject, $c);
                    $count += $c;
                    next($search);
                    next($replace);
                }
            }
        } else {
            // SBUIADMIN : sans mbregex (déprécié en PHP 8.6, supprimé en PHP 9).
            // En UTF-8, une recherche par octets ne peut pas tomber au milieu
            // d'un caractère : explode() suffit. Autre jeu : passage par UTF-8.
            $subject = (string) $subject;
            $search = (string) $search;
            $replace = (string) $replace;
            if ($search === '') {
                $count = 0;
                return $subject;
            }
            $convert_result = (bool) strcasecmp(Smarty::$_CHARSET, 'UTF-8');
            if ($convert_result) {
                $subject = mb_convert_encoding($subject, 'UTF-8', Smarty::$_CHARSET);
                $search = mb_convert_encoding($search, 'UTF-8', Smarty::$_CHARSET);
                $replace = mb_convert_encoding($replace, 'UTF-8', Smarty::$_CHARSET);
            }
            $parts = explode($search, $subject);
            $count = count($parts) - 1;
            $subject = implode($replace, $parts);
            if ($convert_result) {
                $subject = mb_convert_encoding($subject, Smarty::$_CHARSET, 'UTF-8');
            }
        }
        return $subject;
    }
}
