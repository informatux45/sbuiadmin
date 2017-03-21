<?php
/* Smarty version 3.1.29, created on 2016-12-19 10:36:21
  from "/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/system/menu_bar.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_5857aa15c0ad54_07172433',
  'file_dependency' => 
  array (
    '8a58f1443c040de66aae78cef65630b27dd3f87c' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/system/menu_bar.tpl',
      1 => 1472829748,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5857aa15c0ad54_07172433 ($_smarty_tpl) {
?>


<div class="well well-sm">
	

		<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=menu'">
			Tous les menus
		</button>
		&nbsp;
		<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=menu&a=add'">
			Ajouter un menu
		</button>
		<?php if ($_GET['id']) {?>
		&nbsp;
		<button class="btn btn-success" type="button" onclick="location.href='index.php?p=menu&a=sort&id=<?php echo $_GET['id'];?>
'">
			Trier les pages du menu
		</button>
		<?php }?>

		<?php if ($_GET['a'] == 'sort') {?>
		&nbsp;
		<button class="btn btn-warning" type="button" onclick="location.href='index.php?p=menu&a=edit&id=<?php echo $_GET['id'];?>
'">
			Retour au menu
		</button>
		<?php }?>
		
</div><?php }
}
