<?php
/** *****************************************************************************
*                      INFORMATUX page class (UTF8)                             *
/** *****************************************************************************
* @author     Patrice BOUTHIER <contact[at]informatux.com>                      *
* @copyright  1996-2016 INFORMATUX                                              *
* @link       http://www.informatux.com/                                        *
* @since      1.0                                                               *
* @version    CVS: 1.8                                                          *
* ----------------------------------------------------------------------------- *
* Copyright (c) 2011, INFORMATUX Solutions and web development                  *
* All rights reserved.                                                          *
***************************************************************************** **/

// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// Blocking direct access to plugin      -=
// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
defined('SBUIADMIN_PATH') or die('Are you crazy!');


class page extends Smarty {

	/**
	* Format Header Module Page
	* @param  string  $modulePage
	* @return string
	*/
	public function getHeader($modulePage) {

	}
	
	public function pageStart($page_current, $page_limit) {
		return ($page_current > 1) ? ($page_current*$page_limit)-$page_limit : 0;
	}
	
	public function pageCurrent($page) {
		return (isset($page) && is_numeric($page)) ? intval($page) : 1;
	}


}

?>