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
	$icon_text  = (isset($param['icontext']) && $param['icontext'] == '1') ? true : false;
	$icon_menu  = (isset($param['menu']) && $param['menu'] == 'li') ? true : false;
	$class_menu = (isset($param['menu_class'])) ? $param['menu_class'] : "";
	$class_href = (isset($param['href_class'])) ? $param['href_class'] : "";
	// --- Initialization URL
	$login_url   = (SBREWRITEURL === true) ? SB_URL . 'user' : SB_URL . 'index.php?p=user';
	$admin_url   = SB_ADMIN_URL;
	$logout_url  = (SBREWRITEURL === true) ? SB_URL . 'user/?ac=logout' : SB_URL . 'index.php?p=user&ac=logout';
	$login_text  = (isset($_SESSION['sbuiadmin_user_name']) && $_SESSION['sbuiadmin_user_name'] != '') ? _CMS_USER_ICON_TEXT_PROFILE : _CMS_USER_ICON_TEXT_LOGIN;
	$admin_text  = _CMS_USER_ICON_TEXT_ADMIN;
	$logout_text = _CMS_USER_ICON_TEXT_LOGOUT;
	// --- LOGIN / PROFILE
	if ($icon_menu) $user_html .= '<li class="' . $class_menu . '">';
	$user_html .= '<a href="' . $login_url . '" class="' . $class_href . '" title="' . $login_text . '">';
	$user_html .= '<i class="fa fa-user"></i>';
	if ($icon_text) $user_html .= ' ' . $login_text;
	$user_html .= '</a>';
	if ($icon_menu) $user_html .= '</li>';
	// --- ADMIN BACKEND (if admin group)
	global $sbuiadmin_user_type;
	if ($sbuiadmin_user_type == 'admin') {
		if ($icon_menu)
			$user_html .= '<li class="' . $class_menu . '">';
		else
			$user_html .= '&nbsp;&nbsp;';
		$user_html .= '<a href="' . $admin_url . '" class="' . $class_href . '" title="' . $admin_text . '">';
		$user_html .= '<i class="fa fa-cogs"></i>';
		if ($icon_text) $user_html .= ' ' . $admin_text;
		$user_html .= '</a>';
		if ($icon_menu) $user_html .= '</li>';
	}

	// --- Check if user is logging
	if (isset($_SESSION['sbuiadmin_user_name']) && $_SESSION['sbuiadmin_user_name'] != '') {
		// --- Initialization LOGOUT
		if ($icon_menu)
			$user_html .= '<li class="' . $class_menu . '">';
		else
			$user_html .= '&nbsp;&nbsp;';
		$user_html .= '<a href="' . $logout_url . '" class="' . $class_href . '" title="' . $logout_text . '">';
		$user_html .= '<i class="fa fa-lock"></i>';
		if ($icon_text) $user_html .= ' ' . $logout_text;
		$user_html .= '</a>';		
		if ($icon_menu) $user_html .= '</li>';
	}

	return $user_html;
	
}

?>
