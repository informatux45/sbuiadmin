<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.sbdebug.php
 * Type:     function
 * Name:     sbdebug
 * Purpose:  outputs debug array
 * -------------------------------------------------------------
 */
function smarty_function_sbdebug($params, Smarty_Internal_Template $template) {
    // Global include CLASS File
	//sb_global_include(SBMAGIC_PATH . '/inc/debug/Kint.class.php');

	// ----------------------------------------------------------
	// USAGE :
	// ----------------------------------------------------------	
	// Kint::dump( $_SERVER );
	// --- or, even easier, use a shorthand:
	// d( $_SERVER );
	// --- or, to seize execution after dumping use dd();
	// dd( $_SERVER ); // same as d( $_SERVER ); die; 
	// --- to see trace:
	// Kint::trace();
	// --- or pass 1 to a dumper function
	// Kint::dump( 1 );
	// ----------------------------------------------------------
	// TEXT-ONLY OUTPUT
	// s( $variable );
	// --- and
	// sd( $variable ); // to exit immediately afterwards
	// ----------------------------------------------------------
	// +Kint::dump(); will bypass the nesting depth limit.
    // ---    When outputting very complex objects, you may receive *DEPTH TOO GREAT* messages, use this modifier to ignore them for that one call.
    // ---    Be warned, it may cause your browser to hang in extreme cases.
    // -Kint::dump(); will clean all previous output to screen before displaying the dump.
    // ---    Use it to show the dump at the very top of the page.
    // ---    Extremely useful when dumping variables inside HTML; powerful combined with dd();
    // ---    Be warned, it may cause your browser to hang in extreme cases.
    // ---    May fail to work in rare cases when ob_clean() and ob_start() are used beforehand.
    // @Kint::dump(); will return the output of the Kint::dump() instead of displaying it on screen.
    // ---    Useful for logging to file.
    // !Kint::dump(); will display the dump expanded by default so you don't have to click :)
	// ----------------------------------------------------------
	// --- to disable all output
	// Kint::enabled(false);
	// --- further calls, this one included, will not yield any output
	// d('Get off my lawn!'); // no effect
	// ----------------------------------------------------------
	
	// Initialize
	$result = "";
	$result .= Kint::dump($_SERVER);
	$result .= Kint::dump($_POST);
	$result .= Kint::dump($_GET);
	
	if ($params['odump'])
		$result .= Kint::dump($params['odump']);
	
	if ($params['debugsql'])
		$result .= Kint::dump($params['debugsql']);
	else
		$result .= "NOTICE: <strong>assign :: missing 'sql' parameter in SBDEBUG</strong>";
	
	// If file_content
	if ($params['file_content'] != '')
		$result .- Kint::dump($params['file_content']);
	
    return $result;

}

?>