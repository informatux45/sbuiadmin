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
	global $sbsanitize;
	
	// Initialize
	$i18n_debug = false;
	$i18n_txt   = $params['i18n'];
	$i18n_lang  = (isset($params['l'])) ? $params['l'] : $_SESSION['lang'];
	$i18n_cat   = (isset($params['category'])) ? $params['category'] : 'general';
	
	// Search if entry exists (clé stockée telle qu'avant : entités + antislashs)
	$i18_search = $sbsanitize->addSlashes($sbsanitize->htmlEntities($i18n_txt));
	$result     = sbDb()->row("SELECT json FROM {sb_i18n} WHERE i18n = ?", array($i18_search));
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
		sbDb()->insert('sb_i18n', array('i18n' => $i18_search, 'category' => (string) $i18n_cat, 'json' => '{"' . $i18n_lang . '": "' . $i18_search . '"}'));
		$i18n_result    = $i18n_txt;
		// --- Debug
		if ($i18n_debug) $i18n_result = "Error: " . sbDb()->error();
		// ------------------------------------
	}
	
    return $i18n_result;

}

?>