<?php
/**
 * Plugin Name: SBUIADMIN USER
 * Description: Gestionnaire de users
 * Version: 0.1.1
 * Author: BooBoo
 * Author URI: //www.informatux.com/
 * File: functions.php
 */

// Security Check
if (!defined('SB_PATH')) {
	die('You cannot load this file directly!');
}

/**
 * Get USER MENU
 * icontext		int			$param icontext
 * name			string		$param name (function name after 'shortcode_')
 * menu 		string		$param menu (li)
 * menu_class	string		$param menu_class (class for li tag)
 * href_class	string		$param href_class (class for a tag)
 * Editeur WYSIWYG : [CS name=sbuser icontext=1 menu_class=mymenuclass href_class=myhrefclass]
 * Smarty TPLs : {insert name="sbDoShortcode" code="[CS name=sbuser icontext=1 menu_class=mymenuclass href_class=myhrefclass]"}
 * return HTML
 */
function shortcode_sbuser($param = '') {
	global $sbsanitize;

	// --- Initialization
	$icon_text = $icon_menu = $class_menu = $class_href = $user_html = "";
	// --- Assign variables
	$icon_text  = ($param['icontext'] == '1') ? true : false;
	$icon_menu  = ($param['menu'] == 'li') ? true : false;
	$class_menu = (isset($param['menu_class'])) ? $param['menu_class'] : "";
	$class_href = (isset($param['href_class'])) ? $param['href_class'] : "";
	// --- Initialization URL
	$login_url  = (SBREWRITEURL === true) ? SB_URL . 'user' : SB_URL . 'index.php?p=user';
	$logout_url = (SBREWRITEURL === true) ? SB_URL . 'user/?ac=logout' : SB_URL . 'index.php?p=user&ac=logout';
	$login_text = (isset($_SESSION['sbmagic_user_name']) && $_SESSION['sbmagic_user_name'] != '') ? 'Profil' : 'Login';
	// --- Initialization LOGIN / PROFILE
	if ($icon_menu) $user_html .= '<li class="' . $class_menu . '">';
	$user_html .= '<a href="' . $login_url . '" class="' . $class_href . '">';
	$user_html .= '<i class="fa fa-user"></i>';
	if ($icon_text) $user_html .= ' ' . $login_text;
	$user_html .= '</a>';
	if ($icon_menu) $user_html .= '</li>';

	// --- Check if user is logging
	if (isset($_SESSION['sbmagic_user_name']) && $_SESSION['sbmagic_user_name'] != '') {
		// --- Initialization LOGOUT
		if ($icon_menu)
			$user_html .= '<li>';
		else
			$user_html .= '&nbsp;&nbsp;';
		$user_html .= '<a href="' . $logout_url . '">';
		$user_html .= '<i class="fa fa-lock"></i>';
		if ($icon_text) $user_html .= ' logout';
		$user_html .= '</a>';		
		if ($icon_menu) $user_html .= '</li>';
	}

	return $user_html;
	
}

?>