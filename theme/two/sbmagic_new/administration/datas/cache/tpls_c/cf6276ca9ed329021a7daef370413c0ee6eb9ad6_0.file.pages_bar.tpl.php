<?php
/* Smarty version 3.1.29, created on 2016-12-19 10:41:48
  from "/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/system/pages_bar.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_5857ab5c42a4d0_79872560',
  'file_dependency' => 
  array (
    'cf6276ca9ed329021a7daef370413c0ee6eb9ad6' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/system/pages_bar.tpl',
      1 => 1474037301,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5857ab5c42a4d0_79872560 ($_smarty_tpl) {
?>


<div class="well well-sm">
	

		<button class="btn btn-outline btn-danger" type="button" onclick="location.href='index.php?p=pages'">
			Toutes les pages
		</button>
		&nbsp;
		<button class="btn btn-outline btn-danger" type="button" onclick="location.href='index.php?p=pages&a=add'">
			Ajouter une page
		</button>
		&nbsp;
		<button class="btn btn-outline btn-danger" type="button" onclick="location.href='index.php?p=pages&a=addcustom'">
			Ajouter une page (Custom)
		</button>
		&nbsp;
		<button class="btn btn-outline btn-danger" type="button" onclick="location.href='index.php?p=blocs'">
			Tous les blocs
		</button>
		&nbsp;
		<button class="btn btn-outline btn-danger" type="button" onclick="location.href='index.php?p=blocs&a=add'">
			Ajouter un bloc
		</button>

</div><?php }
}
