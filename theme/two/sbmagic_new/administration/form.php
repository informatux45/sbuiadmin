<?php
/**
 * Admin Startbootstrap
 * Submit FORMs in Smarty
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

global $sbform;

// on l'affiche
echo $sbform;

// on compte et affiche ses éléments (debugging only)
if (_AM_SITE_DEBUG_FORM) {
	echo $sbform->showElems ();
	echo $sbform->countElems ();
}

// on libère les ressources
$sbform->freeForm ();

?>