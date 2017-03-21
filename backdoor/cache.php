<?php
/**
 * Admin Startbootstrap
 * Manage CACHE (Admin / Front)
 *
 * @link http://dev.informatux.com/
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// Blocking direct access to plugin      -=
// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
defined('SBMAGIC_PATH') or die('Are you crazy!');

// -----------------------
// Include Config CMS
// -----------------------
include_once('../sbconfig.php');

// -----------------------
// Module URL
// -----------------------
$module_page = 'cache';
$sbsmarty->assign('module_page', $module_page);
// -----------------------
$module_url = _AM_SITE_PROTOCOL . SBMAGIC_URL . SBMAGIC_BASE . '?p=' . $module_page;
$sbsmarty->assign('module_url', $module_url);
 
// -----------------------
// Message status
// -----------------------
$sb_msg_error = false;
$sb_msg_valid = false;

// ---------------------------------------------------
// ---------------------------------------------------
// Write your own code after these lines
// ---------------------------------------------------
// ---------------------------------------------------

// --------------------------------
// Initialize Form
// --------------------------------
$formName        = "edit_form";
$formType        = "edit";
$btn_add_edit    = "Vider tous les caches";
$legend_add_edit = "Modifier &laquo;&nbsp;<span style='color: red;'>%s</span>&nbsp;&raquo;";

$action = $_GET['op'];
switch($action) {

	default:
	case "cache":
		// --------------------------------
		// --- Tableau des caches
		// --------------------------------
		$all_url_caches = ['ADMIN Core'      => _AM_SITE_URL . 'datas/cache/core' 
						  ,'ADMIN Medias'    => _AM_SITE_URL . 'datas/cache/medias'
						  ,'ADMIN Templates' => _AM_SITE_URL . 'datas/cache/tpls_c'
						  ,'FRONT Core'      => SB_URL . 'datas/cache/core' 
						  ,'FRONT Medias'    => SB_URL . 'datas/cache/medias'
						  ,'FRONT Templates' => SB_URL . 'datas/cache/tpls_c'
						  ];
		$all_dir_caches = [_AM_SITE_DIR . 'datas/cache/core' 
						  ,_AM_SITE_DIR . 'datas/cache/medias'
						  ,_AM_SITE_DIR . 'datas/cache/tpls_c'
						  ,SB_PATH . 'datas/cache/core' 
						  ,SB_PATH . 'datas/cache/medias'
						  ,SB_PATH . 'datas/cache/tpls_c'
						  ];		

		// --------------------------------
		// --- Control form submit --------
		// --------------------------------
		if ($_POST['form_submit']) {
			
			// --- CLEAN CACHES
			$clean_cache = 0;
			for($i = 0; $i < count($all_dir_caches); $i++) {
				$clean = array_map('unlink', glob("$all_dir_caches[$i]/*"));
				if (!$clean) $clean_cache++;
			}
			
			if ($clean_cache) {
				// --- Message SUCCES
				$sb_msg_valid = 'Caches nettoyés avec succès';
			} else {
				// --- Message ERROR
				$sb_msg_error = 'Clean Cache Error!';
			}
			
		}
		
		// --------------------------------		
		// --- Define variables
		$formAction = $module_url;
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
		// --- Add inputs and more
		// --------------------------------
		// Listes des caches FRONT / ADMIN
		// --------------------------------
		$sbform->addAnything(sbListDirectories($all_url_caches));
		// --------------------------------
		// --- Hiddens / Buttons
		// --------------------------------	
		$sbform->addInput('hidden', '', array('name' => 'form_submit', 'value' => "$formName"));
		$sbform->addInput('submit', '', array('value' => "$btn_add_edit"));
		// --------------------------------	
		// --- Close Form
		// --------------------------------	
		$sbform->closeForm();
		// --------------------------------
	break;

}

function sbListDirectories($array) {
	$html_directories = '<div class="panel-body">
							<p class="red">Tous ces répertoires seront vidés.</p>
							<div class="table-responsive">
								<table class="table table-bordered table-striped">
									<thead>
										<tr>
											<th></th>
											<th>
												Liste des répertoires
											</th>
										</tr>
									</thead>
									<tbody>';
	foreach($array as $k => $v) {
		$html_directories .= '<tr>';
		$html_directories .= '<th>' . $k . '</th>';
		$html_directories .= '<td>' . $v . '</td>';
		$html_directories .= '</tr>';
	}
	
	$html_directories .= '
									</tbody>
								</table>
							</div>
						</div>';
	
	return $html_directories;
}

// ---------------------------------------------------
// ---------------------------------------------------
// IMPORTANT: Don't remove these lines
// ---------------------------------------------------
// ---------------------------------------------------
// ----------------------------------------
// ASSIGN Page TITLE - Modify this |
// ----------------------------------------
$sbsmarty->assign('page_title', 'Cache');
// --- Legend ADD or EDIT
$sbsmarty->assign('legend_add_edit', sprintf($legend_add_edit, $sbsanitize->displayText($action, 'UTF-8', 0, 1)));

// ----------------------
// ASSIGN Message status
// ----------------------
$sbsmarty->assign('sb_msg_error', $sb_msg_error);
$sbsmarty->assign('sb_msg_valid', $sb_msg_valid);

// ----------------------
// CLOSE SQL
// ----------------------
$sbsql->close();

?>