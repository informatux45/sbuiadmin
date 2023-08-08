<?php
/**
 * Smarty plugin
 *
 * @package    SBUIAdmin
 * @subpackage PluginsFunction
 */

/**
 * Smarty {include_php} plugin
 * Type:     function<br>
 * Name:     include_php<br>
 * Purpose:  old function to display forms in php
 *
 * @author BooBoo <informatux45 at gmail dot com>
 *
 * @param array                    $params   parameters
 * @param Smarty_Internal_Template $template template object
 *
 * Usage:
 * {include_php}
 *
 * @return string
 */

function smarty_function_include_php($params, &$smarty) {
    
    global $sbform;
    
    $form = $params['file'];

    // Initialize
    $return_html = "";
    
    switch($form) {
        case "form.php":
            // on l'affiche
            $return_html .= $sbform;
            // on compte et affiche ses éléments (debugging only)
            if (_AM_SITE_DEBUG_FORM) {
                $return_html .= $sbform->showElems();
                $return_html .= $sbform->countElems();
            }
            // on libère les ressources
            $sbform->freeForm();
        break;
        default:
            $return_html .= "Formulaire inconnu !";
        break;
    }
    
    return $return_html;

}
?>
