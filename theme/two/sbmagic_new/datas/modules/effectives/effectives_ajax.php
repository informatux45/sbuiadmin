<?php
/**
 * Plugin Name: SbMagic EFFECTIVES
 * Description: Gestionnaire d'effectifs (equins)
 * Version: 0.1.1
 * Agency: Agence DOLLAR
 * Agency URI: //dollar.fr
 * Author: BooBoo
 * Author URI: //www.informatux.com/
 */

// ---------------------------
// SESSION Initialisation
// ---------------------------
session_start();

// ----------------------------------------- 
// --- Load Default Include for AJAX Request
// -----------------------------------------
include_once('../../../sbconfig.php');
include_once('../../../header.php');
global $sbsmarty, $sbsanitize, $sbsql, $sbpage;

// ---------------------------
// Define some important stuff
// ---------------------------
define('MODULEFILE', basename(__FILE__, "_ajax.php"));
define('MODULENAME', 'Effectives');
define('MODULEVERSION','0.1.1');

// ---------------------------
// Security Check
// ---------------------------
if (!defined('SB_PATH')) {
	die('You cannot load this file directly!');
}

// ---------------------------
// Include Module Common Infos
// ---------------------------
$sblang_effectives = (SBLANG && $_SESSION['lang'] != 'en') ? SBLANG : 'en_US';
include_once( SB_MODULES_DIR . MODULEFILE . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . $sblang_effectives . '.php' );
include_once( SB_MODULES_DIR . MODULEFILE . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'functions.php' );

// ---------------------------
// Tables SQL
// ---------------------------
$module['tables']['effectives']      = _AM_DB_PREFIX . "sb_effectives";
$module['tables']['effectivescat']   = _AM_DB_PREFIX . "sb_effectives_category";
$module['tables']['effectivessett']  = _AM_DB_PREFIX . "sb_effectives_settings";
$module['tables']['effectivesmedia'] = _AM_DB_PREFIX . "sb_effectives_medias";
$module['tables']['effectivesprod']  = _AM_DB_PREFIX . "sb_effectives_production";

// ---------------------------
// --- Switch with Op GET
// ---------------------------
$id = intval($_POST['id']);
$op = $_POST['op'];

if ($_POST['is'] == 'station') {
	// --- SQL Request (Effective)
	$query5    = "SELECT * FROM {$module['tables']['effectives']} WHERE id = '$id' AND active = '1'";
	$request5  = $sbsql->query($query5);
	$effective = $sbsql->assoc($request5);
	
	$name_lower = strtolower(sbRewriteString($sbsanitize->stripTags($sbsanitize->displayLang($effective['name'], $_SESSION['lang']))));
	$name_upper = strtoupper(sbRewriteString($sbsanitize->stripTags($sbsanitize->displayLang($effective['name'], $_SESSION['lang']))));
	
	//echo '<h1 class="acenter in_station">' . $name_upper . '</h1>';
	//echo '<h2 class="acenter in_station">&nbsp;' . strtoupper($sbsanitize->stripTags($sbsanitize->displayLang($effective['projection'], $_SESSION['lang']))) . '&nbsp;</h2>';
	echo '<h1 class="">' . $name_upper . '</h1>';
	echo '<h2 class="">&nbsp;' . strtoupper($sbsanitize->stripTags($sbsanitize->displayLang($effective['projection'], $_SESSION['lang']))) . '&nbsp;</h2>';
	
	echo '<div class="sb_pages_content_in_station">';
}

switch($op) {
	default: // Show conformation
	case "conformation":
		// --- SQL Request (Detail EFFECTIVE)
		$query        = "SELECT pedigree_desc FROM {$module['tables']['effectives']} WHERE id = '$id' AND active = '1'";
		$request      = $sbsql->query($query);
		$conformation = $sbsql->assoc($request);
		
		$html_conformation  = '';
		
		$html_conformation .= '<div class="conformation">';
		
		if (!$conformation) {
			
			$html_conformation .= _CMS_EFFECTIVES_NOCONFORMATION;
		
		} else {
			
			$html_conformation .= $sbsanitize->displayLang(sbGetShortcode($conformation['pedigree_desc']), $_SESSION['lang']);
			
		}
		
		$html_conformation .= '</div>';
		
		echo $html_conformation;


	break;
	case "medias": // Show medias
		// --- SQL Request (Medias)
		$query2   = "SELECT * FROM {$module['tables']['effectivesmedia']} WHERE eid = '$id' AND active = '1' ORDER BY sort ASC";
		$request2 = $sbsql->query($query2);
		$medias   = $sbsql->toarray($request2);
		
		$html_medias = '';

		if (!$medias) {
			
			echo _CMS_EFFECTIVES_NOMEDIAS;

		} else {
		
			echo '<div class="medias">';
			
			foreach($medias as $media) {
				$params = ['type' => $media['type']
						  ,'url' => $media['url']
						  ,'title' => $media['title']
						  ];
				
				// {insert name=sbeffectives_medias type="`$media.type`" url="`$media.url`" title="`$media.title`"}
				$html_medias .= insert_sbeffectives_medias($params);
			}
		
			$html_medias .= '</div>';
		
			echo $html_medias;
		}
		
	break;
	case "production": // Show production

		// --- SQL Request (Effective)
		$query4   = "SELECT * FROM {$module['tables']['effectives']} WHERE id = '$id' AND active = '1'";
		$request4 = $sbsql->query($query4);
		$item     = $sbsql->assoc($request4);
		
		$html_production  = '';
		
		$html_production .= '<div class="header">';
		
		$html_production .= '<p class="type">';
		$html_production .= $sbsanitize->displayLang($item['colour'], $_SESSION['lang']) . ' - ' . $item['size'] . ' - ' . sbConvertDate($item['date'], "YEAR");
		$html_production .= '</p>'; // END .type
		
		$html_production .= '<p class="description">';
		$html_production .= $sbsanitize->displayLang($item['description'], $_SESSION['lang']);
		$html_production .= '</p>'; // END .description
		
		$html_production .= '</div>'; // END .header

		// --- SQL Request (Production)
		$query3     = "SELECT * FROM {$module['tables']['effectivesprod']} WHERE eid = '$id' AND active = '1' ORDER BY sort ASC";
		$request3   = $sbsql->query($query3);
		$production = $sbsql->toarray($request3);
		
		if (!$production) {
			
			echo _CMS_EFFECTIVES_NOPROD;

		} else {
			
			$html_production .= '<div class="production">';
			
			foreach($production as $prod) {
				
				$html_production .= '<p>';

				if ($prod['date'])
					$html_production .= sbConvertDate($prod['date'], "YEAR") . ' ';

				if ($prod['group_bold'])
					$html_production .= '<b>' . $sbsanitize->displayLang($prod['name'], $_SESSION['lang']) . '</b>';
				else
					$html_production .= $sbsanitize->displayLang($prod['name'], $_SESSION['lang']);
	
				if ($prod['sex']) {
					$html_production .= ' (' . strtolower($prod['sex']) . '.';
					if ($sbsanitize->displayLang($prod['colour']))
						$html_production .= $sbsanitize->displayLang($prod['colour']);
					$html_production .= ')';
				}

 				if ($prod['photo']) {
					$params = ['type' => 'photo'
							  ,'url' => $prod['photo']
							  ,'title' => $prod['name']
							  ,'media' => 'icon'
							  ];
					// {insert name=sbeffectives_medias type="photo" url="`$prod.photo`" title="`$prod.name`" media="icon"}
					$html_production .= insert_sbeffectives_medias($params);
				}

				if ($prod['video']) {
					$params = ['type' => 'youtube'
							  ,'url' => $prod['video']
							  ,'title' => $prod['name']
							  ,'media' => 'icon'
							  ];
					// {insert name=sbeffectives_medias type="youtube" url="`$prod.video`" title="`$prod.name`" media="icon"}
					$html_production .= insert_sbeffectives_medias($params);
				}

				if ($prod['pedigree']) {
					$params = ['type' => 'pdf'
							  ,'url' => $prod['pedigree']
							  ,'title' => $prod['name']
							  ,'media' => 'icon'
							  ];
					// {insert name=sbeffectives_medias type="pdf" url="`$prod.pedigree`" title="`$prod.name`" media="icon"}
					$html_production .= insert_sbeffectives_medias($params);
				}
					
				if ($sbsanitize->displayLang($prod['dam'], $_SESSION['lang'])) {
					$html_production .= '<br>';
					$html_production .= '<i class="sbeffectives-single-dam-sire">';
					$html_production .= $sbsanitize->displayLang($prod['dam'], $_SESSION['lang']);
					if ($sbsanitize->displayLang($prod['sire_dam'], $_SESSION['lang'])) {
						$html_production .= ' ' . _CMS_EFFECTIVES_BY . ' ';
						$html_production .= $sbsanitize->displayLang($prod['sire_dam'], $_SESSION['lang']);
					}
					$html_production .= '</i>';
				}
	
				if ($sbsanitize->displayLang($prod['performance'], $_SESSION['lang']))
						$html_production .= '<br>' . $sbsanitize->displayLang($prod['performance'], $_SESSION['lang']);
				
				$html_production .= '</p>';
			
			}
			
			$html_production .= '</div>'; // End .production
			
			echo $html_production;
		}
	break;
	case "brochure":
		// --- SQL Request (Detail EFFECTIVE)
		$query    = "SELECT description_extend FROM {$module['tables']['effectives']} WHERE id = '$id' AND active = '1'";
		$request  = $sbsql->query($query);
		$brochure = $sbsql->assoc($request);
		
		$html_brochure  = '';
		
		$html_brochure .= '<div class="brochure">';
		
		if (!$brochure) {
			
			$html_brochure .= _CMS_EFFECTIVES_NOBROCHURE;
		
		} else {
			
			$html_brochure .= $sbsanitize->displayLang(sbGetShortcode($brochure['description_extend']), $_SESSION['lang']);
			
		}
		
		$html_brochure .= '</div>';
		
		echo $html_brochure;


	break;
}

// ---------------------------------------------
// ---------------------------------------------
// ---------------------------------------------
// ---------------------------------------------
// ---------------------------------------------
// --- Exception for category 2 (in station) ---
// ---------------------------------------------
// ---------------------------------------------
// ---------------------------------------------
// ---------------------------------------------
// ---------------------------------------------



if ($_POST['is'] == 'station') {

	echo '</div>'; // END .sb_pages_content
	
	echo "<!-- --------------------------------- -->
		  <!-- ------------ BUTTONS ------------ -->
		  <!-- --------------------------------- -->";
	echo '<div class="content-effective-button button_in_station">';
	if ($effective['pedigree_extend'])
		echo '<a href="' . _AM_MEDIAS_URL . $effective['pedigree_extend'] . '" target="_blank" class="button acenter">PEDIGREE DETAILLÉ</a>';
	else
		echo '<a class="button disabled acenter">PEDIGREE DETAILLÉ</a>';
	
	if ($effective['description_extend'])
		echo "<a href=\"javascript:get_infos_in_station('" . SB_MODULES_URL . "effectives', 'brochure', '{$effective['id']}', '$name_lower');\" class=\"button acenter\">BROCHURE</a>";
	else
		echo '<a class="button disabled acenter">BROCHURE</a>';
	
	// (url, opa, ida, id)
	echo "<a href=\"javascript:get_infos_in_station('" . SB_MODULES_URL . "effectives', 'medias', '{$effective['id']}', '$name_lower');\" class=\"button acenter\">VIDÉOS</a>";
	echo "<a href=\"javascript:get_infos_in_station('" . SB_MODULES_URL . "effectives', 'conformation', '{$effective['id']}', '$name_lower');\" class=\"button acenter\">CONFORMATION</a>";
	echo "<a href=\"javascript:content_show('" . SB_MODULES_URL . "pages', 'obtenir-une-offre', '80');\" class=\"button acenter\">OBTENIR UNE OFFRE</a>";
	echo "<a href=\"javascript:content_show('" . SB_MODULES_URL . "pages', 'simulateur-croisements', '80');\" class=\"button acenter\">TEST CROISEMENT</a>";
	
	echo '</div>'; // END .content-effective-button

}

?>