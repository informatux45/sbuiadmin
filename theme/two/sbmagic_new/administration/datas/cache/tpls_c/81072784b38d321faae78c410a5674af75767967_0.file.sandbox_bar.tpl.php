<?php
/* Smarty version 3.1.29, created on 2016-12-19 16:20:54
  from "/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/system/sandbox_bar.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_5857fad6573405_55589888',
  'file_dependency' => 
  array (
    '81072784b38d321faae78c410a5674af75767967' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/system/sandbox_bar.tpl',
      1 => 1472829748,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5857fad6573405_55589888 ($_smarty_tpl) {
?>


<div class="well well-sm">
	

	<button class="btn btn-outline btn-success" type="button" onclick="location.href='index.php?p=sandbox'">
		Tous les enregistrements
	</button>
	&nbsp;
	<button class="btn btn-outline btn-warning" type="button" onclick="location.href='index.php?p=sandbox&a=add'">
		Ajouter un enregistrement
	</button>
	&nbsp;
	<button class="btn btn-outline btn-danger" type="button" onclick="location.href='index.php?p=sandbox&a=sort'">
		Trier les enregistrements
	</button>
	&nbsp;
	<button class="btn btn-outline btn-info" type="button" onclick="location.href='index.php?p=sandbox'">
		Autres liens
	</button>
	&nbsp;
	<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=sandbox'">
		Autres liens 2
	</button>
	&nbsp;
	<button class="btn btn-outline btn-default" type="button" onclick="location.href='index.php?p=sandbox'">
		Autres liens 3
	</button>

</div><?php }
}
