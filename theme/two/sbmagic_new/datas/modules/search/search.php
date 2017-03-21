<?php
/**
 * Plugin Name: SbMagic PAGES
 * Description: Gestionnaire de pages libres
 * Version: 0.1.1
 * Agency: Agence DOLLAR
 * Agency URI: //dollar.fr
 * Author: BooBoo
 * Author URI: //www.informatux.com/
 */

 // Security Check
if (!defined('SB_PATH')) {
	die('You cannot load this file directly!');
}

# Define some important stuff
define('MODULEFILE', basename(__FILE__, ".php"));
define('MODULENAME', 'Search');
define('MODULEVERSION','0.1.1');

# Include Module Common Infos
include_once( SB_MODULES_DIR . MODULEFILE . DIRECTORY_SEPARATOR . 'common.php' );
global $module, $sbsmarty, $sbsanitize, $sbsql, $sbpage;
?>
<script type="text/javascript" src="<?php echo SB_MODULES_URL . 'real_estate' . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'module' . DIRECTORY_SEPARATOR . 'functions.js'; ?>"></script>
<?php

# Define TPL to show (view)
$id       = $sbsanitize->stopXSS($_GET['id']);
$template = 'index';
# Assign template main
$module['template_main'] = MODULEFILE . '_' . $template . '.tpl';


// --------------------------
// --- Initialization
// --------------------------
$sbsmarty->assign('biens_directory_path', '../uploads/biens/');
$bien_per_page = 50;
$bien_page     = (isset($_GET['l'])) ? intval($_GET['l']) : 0;
// --- Check COOKIE
if (isset($_COOKIE['PHPSESSID']))
	$selection_customer = $_COOKIE['PHPSESSID'];
$sb_search_empty = false;
// -------------------------------------------------

// --------------------------
// --- INPUTs POST Form
// --------------------------
$search_reference = (isset($_POST['reference']) && $_POST['reference'] != '') ? " AND t1.reference = '".intval($_POST['reference'])."'" : false;
$search_pricemin  = (isset($_POST['pricemin']) && $_POST['pricemin'] != '') ? intval($_POST['pricemin']) : false;
$search_pricemax  = (isset($_POST['pricemax']) && $_POST['pricemax'] != '') ? intval($_POST['pricemax']) : false;
$search_areamin   = (isset($_POST['areamin']) && $_POST['areamin'] != '') ? intval($_POST['areamin']) : false;
$search_areamax   = (isset($_POST['areamax']) && $_POST['areamax'] != '') ? intval($_POST['areamax']) : false;
if (!empty($_POST['place'])) {
	// var_dump($_POST['place']);
	$search_place       = " AND (";
	$search_place_or    = " OR ";
	$search_place_arg   = "t1.departement = ";
	$search_place_count = count($_POST['place']);
	foreach($_POST['place'] as $key => $val) {
		$search_place .= $search_place_arg . $val;
		if (($key + 1) < $search_place_count)
			$search_place .= $search_place_or;
	}
	$search_place      .= ")";
}

// --------------------------
// --- Construct End of SQL Query
// --------------------------
if ($search_reference) {
	// Si reference, rien d'autre n'est pris en compte
	// pas besoin ;-)
	$construct_search_request = $search_reference;
} elseif (empty($_POST['place']) && !$search_pricemin && !$search_pricemax && !$search_areamin && !$search_areamax && !$search_reference) {
	// Dans le cas où rien n'est entré
	// On indique au visiteur qu'il faut qu'il choisisse un critère ;-)
	$construct_search_request = "";
	$sb_search_empty          = 'empty'; 
	$sbsmarty->assign('sb_search_empty', $sb_search_empty);
} else {
	// Construct with search_place 1st
	$construct_search_request = $search_place;
	// Construct with search_price
	if ($search_pricemin && $search_pricemax) {
		$construct_search_request .= " AND (t1.price BETWEEN $search_pricemin AND $search_pricemax)";
	} else {
		if ($search_pricemin)
			$construct_search_request .= " AND (t1.price >= $search_pricemin)";
		if ($search_pricemax)
			$construct_search_request .= " AND (t1.price <= $search_pricemax)";		
	}
	// Construct with search_area
	if ($search_areamin && $search_areamax) {
		$construct_search_request .= " AND (t1.area BETWEEN $search_areamin AND $search_areamax)";
	} else {
		if ($search_areamin)
			$construct_search_request .= " AND (t1.area >= $search_areamin)";
		if ($search_areamax)
			$construct_search_request .= " AND (t1.area <= $search_areamax)";
	}	
}

// --------------------------
// --- SQL Request (SEARCH)
// --------------------------
$initQ  = "SELECT t1.*,
		   t2.id AS iddep, t2.departement AS namedep,
		   t3.region AS region,
		   t4.name AS photo,
		   t5.name AS flagname,
		   t6.session, t6.id_biens AS session_id_biens, t6.date
		   FROM {$module['tables']['bien']} AS t1
		   LEFT JOIN {$module['tables']['phot']} AS t4 ON (t1.id = t4.id_biens)
		   LEFT JOIN {$module['tables']['dept']} AS t2 ON (t1.departement = t2.id)
		   LEFT JOIN {$module['tables']['regi']} AS t3 ON (t2.id_region = t3.id)
		   LEFT JOIN {$module['tables']['flag']} AS t5 ON (t5.id = t1.flag)
		   LEFT JOIN {$module['tables']['sele']} AS t6 ON (t1.id = t6.id_biens AND t6.session = '$selection_customer')
		   WHERE t4.sort = '1' AND t1.active = '1' AND t1.price > 0
		   %s
		   ORDER BY t1.date DESC ";

$initQ = sprintf($initQ, $construct_search_request);
		   
// --- Total ADS
$queryT = $sbsql->query($initQ);
$totalT = $sbsql->numrows();

$query   = $initQ . "LIMIT $bien_page, $bien_per_page";
$request = $sbsql->query($query);
$result  = $sbsql->toarray($request);

if ($result) {

	// --- Assign Array
	$sbsmarty->assign('all', $result);
	// --- Assign Bien info Page
	$sbsmarty->assign('bien_per_page', $bien_per_page);
	$sbsmarty->assign('bien_total',$totalT);
	$sbsmarty->assign('bien_page', $bien_page);
	$sbsmarty->assign('page_total', floor($totalT / $bien_per_page));
	
	// --- Exception (Selection)
	// Link selection (add / remove)
	$sbsmarty->assign('sb_search_selection', 'real_estate');

} else {
	// ---------------------------
	// --- Assign Search no result
	// ---------------------------
	$sbsmarty->assign('sb_no_search', 'empty');	
}

// --------------------------
// --- Choose theme view
// --------------------------
$module['theme_main'] = 'index';
// --------------------------
// --- Add Template BLOCKS (depends on the theme view choosen)
// --------------------------
$module['template_main_blocks'] = MODULEFILE . '_' . $template . '_blocks.tpl';
// --------------------------
// --- Assign Title Page
// --------------------------
$sbsmarty->assign('sb_pages_title', _CMS_GLOBAL_SEARCH);



?>