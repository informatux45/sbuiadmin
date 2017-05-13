<?php
/**
 * Smarty plugin
 *
 * @package    SBUIAdmin
 * @subpackage PluginsFunction
 */

/**
 * Smarty {seo} plugin
 * Type:     function<br>
 * Name:     seo<br>
 * Purpose:  seo url, web display results
 *
 * @author BooBoo <informatux45 at gmail dot com>
 *
 * @param array                    $params   parameters
 * @param Smarty_Internal_Template $template template object
 *
 * Usage:
 * {seo url="index.php?lang=`$smarty.session.lang`&id=privacy" rewrite="`$smarty.session.lang`/privacy"}
 * {seo url="index.php?p=pages&id=contact" rewrite="pages/contact"}
 *
 * @return string	normal url / seo url (rewrite url)
 */

function smarty_function_seo($params, &$smarty) {

	$seo_url    = $params['rewrite'];
	$normal_url = $params['url'];
	$encode_url = $params['encode'];
	$base_url   = SB_URL;

	if (SBREWRITEURL === true) {
	    if ($encode_url == 'url')
			return urlencode($base_url . $seo_url);
	    else
			return $base_url . $seo_url;
	}

	return $base_url . $normal_url;
}
?>
