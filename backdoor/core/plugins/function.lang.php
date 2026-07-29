<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.lang.php
 * Type:     function
 * Name:     lang
 * Purpose:  outputs lang (I18n)
 * @param    i18n     String to search
 * @param    l        Langage: fr | en | es | ..
 * -------------------------------------------------------------
 */
function smarty_function_lang($params, Smarty_Internal_Template $template) {
    // Global include CLASS File (SQL)
	global $sbsql, $sbsanitize;
	
	// Initialize
	$i18n_debug = false;
	$i18n_txt   = $params['i18n'];
	$i18n_lang  = (isset($params['l'])) ? $params['l'] : $_SESSION['lang'];
	$i18n_cat   = (isset($params['category'])) ? $params['category'] : 'general';
	$i18n_table = _AM_DB_PREFIX . 'sb_i18n'; 
	
	// Search if entry exists
	$i18_search = $sbsanitize->addSlashes($sbsanitize->addSlashes($sbsanitize->htmlEntities($i18n_txt)));
	$query      = "SELECT json FROM $i18n_table WHERE i18n = '$i18_search'";
	$request    = $sbsql->query($query);
	$result     = $sbsql->assoc($request);
	if ($result) {
		// ------------------------------------
		// If result - Extract JSON (i18n_lang)
		// ------------------------------------
		$json = json_decode($result['json']);
		if ($json) {
			// Key i18n_lang (ex: fr)
			$i18n_result = $json->{$i18n_lang};
		} else {
			$i18n_result = $result['json'];
		}
		// ------------------------------------
	} else {
		// ------------------------------------
		// No result - Create entry
		// ------------------------------------
		$query_create   = 'INSERT INTO '.$i18n_table.' (i18n, category, json) VALUES (\''.$i18_search.'\', \''.$i18n_cat.'\', \'{"'.$i18n_lang.'": "'.$i18_search.'"}\')';
		$request_create = $sbsql->query($query_create);
		$i18n_result    = $i18n_txt;
		// --- Debug
		if ($i18n_debug) $i18n_result = "Error: ".$sbsql->error();
		// ------------------------------------
	}
	
    return $i18n_result;

}

?>