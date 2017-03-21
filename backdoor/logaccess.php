<?php
/**
 * Admin Startbootstrap
 * Log ACCESS (logs)
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
// Module URL
// -----------------------
$module_page = 'logaccess';
$sbsmarty->assign('module_page', $module_page);
// -----------------------
$module_url = _AM_SITE_PROTOCOL . SBMAGIC_URL . SBMAGIC_BASE . '?p=' . $module_page;
$sbsmarty->assign('module_url', $module_url);
 
// -----------------------
// Message status
// -----------------------
$sb_msg_error = false;
$sb_msg_valid = false;

// --------------------------------------------
// --------------------------------------------
// --------------------------------------------
// --------------------------------------------
// --------------------------------------------

$table       = _AM_DB_PREFIX . "sb_logaccess";
$text        = "Log";

$action = $_GET['a'];
switch($action) {
	case "del":
	default:
		// Action DELETE
		if ($action == 'del') {
			$get_id   = intval($_GET['id']);
			$query[2] = "DELETE FROM $table WHERE id = '$get_id'";
			$request  = $sbsql->query($query[2]);
			
			if ($request)
				$sb_msg_valid = $text . ' supprimée avec succès';
			else
				$sb_msg_error = 'Error: Write Error (DEL)!';
		}

		// Initialisation
		$sb_table_header =  array('ID', 'Date', 'Username', 'Type', 'Event');
		$sbsmarty->assign('sb_table_header', $sb_table_header);
		
		// Contents table
		$query[0] = "SELECT * FROM $table";
		$request2  = $sbsql->query($query[0]);
		$result2   = $sbsql->toarray($request2);
		
		$sbsmarty->assign('all', $result2);
		
		// --- Debug SQL
		if (_AM_SITE_DEBUG) {
			$alldel_debug = 'ALL: ' . $query[0];
			if (isset($action) && $action == 'del') {				  
				$alldel_debug .= "\n" . 'DEL: ' . $query[2];
			}
			$sbsmarty->assign('sbdebugsql', $alldel_debug);
		}
		
	break;
}
 
// ----------------------
// ASSIGN Page TITLE
// ----------------------
$sbsmarty->assign('page_title', 'Journaux de connexion');

// ----------------------
// ASSIGN Message status
// ----------------------
$sbsmarty->assign('sb_msg_error', $sb_msg_error);
$sbsmarty->assign('sb_msg_valid', $sb_msg_valid);

?>