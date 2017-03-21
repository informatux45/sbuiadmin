<?php
/* Smarty version 3.1.29, created on 2016-12-12 16:27:49
  from "/Applications/MAMP/htdocs/sbmagic_new/administration/datas/modules/tpls/table_bar.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_584ec1f56cb949_68707356',
  'file_dependency' => 
  array (
    '28345125fd0beeea01a8a5748258c5bbc59ef981' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/administration/datas/modules/tpls/table_bar.tpl',
      1 => 1481555465,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_584ec1f56cb949_68707356 ($_smarty_tpl) {
?>


<div class="well well-sm">
	

		<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=table'">
			Tous les tableaux
		</button>
		&nbsp;
		<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=table&a=add'">
			Ajouter un tableau
		</button>		
		
		<?php if ($_GET['a'] == 'editfield' || $_GET['a'] == 'delfield') {?>
		&nbsp;
		<button class="btn btn-warning" type="button" onclick="location.href='index.php?p=table&a=editdatas&tid=<?php echo $_GET['tid'];?>
'">
			Ses données
		</button>
		<?php }?>

		<?php if ($_GET['a'] == 'editdatas' || $_GET['a'] == 'deldatas') {?>
		&nbsp;
		<button class="btn btn-success" type="button" onclick="location.href='index.php?p=table&a=editfield&tid=<?php echo $_GET['tid'];?>
'">
			Sa structure
		</button>
		<?php }?>

</div><?php }
}
