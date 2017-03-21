<?php
/* Smarty version 3.1.29, created on 2016-11-28 17:04:23
  from "/Applications/MAMP/htdocs/sbmagic_new/administration/datas/modules/tpls/contact_bar.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_583c5587c4e203_31095744',
  'file_dependency' => 
  array (
    '714e7c62d8df09bfa5f600308e7ec7fff89a1c92' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/administration/datas/modules/tpls/contact_bar.tpl',
      1 => 1475672694,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_583c5587c4e203_31095744 ($_smarty_tpl) {
?>


<div class="well well-sm">

		<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=contact'">
			Tous les formulaires
		</button>
		&nbsp;
		<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=contact&a=add'">
			Ajouter un formulaire
		</button>
		&nbsp;
		<button class="btn btn-success" type="button" onclick="location.href='index.php?p=contact&a=settings'">
			Paramètres généraux
		</button>

</div><?php }
}
