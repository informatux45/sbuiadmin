<?php
/* Smarty version 3.1.29, created on 2016-12-12 15:21:51
  from "/Applications/MAMP/htdocs/sbmagic_new/administration/datas/modules/tpls/slider_bar.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_584eb27fed9a68_27185253',
  'file_dependency' => 
  array (
    'd84f9fc39a12bf41d1b676270ceb5725b25e6abf' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/administration/datas/modules/tpls/slider_bar.tpl',
      1 => 1476453318,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_584eb27fed9a68_27185253 ($_smarty_tpl) {
?>


<div class="well well-sm">

		<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=slider'">
			Tous les sliders
		</button>
		&nbsp;
		<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=slider&a=add'">
			Ajouter un slider
		</button>
		&nbsp;
		<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=slider&a=photoadd'">
			Ajouter une photo / vidéo
		</button>
		
		<?php if ($_GET['a'] == 'photo') {?>
			&nbsp;
			<button class="btn btn-success" type="button" onclick="location.href='index.php?p=slider&a=sort&sid=<?php echo $_smarty_tpl->tpl_vars['sid']->value;?>
'">
				Trier les photos
			</button>
			&nbsp;
			<button class="btn btn-warning" type="button" onclick="location.href='index.php?p=slider&a=edit&id=<?php echo $_smarty_tpl->tpl_vars['sid']->value;?>
'">
				Retour aux paramètres du slider
			</button>
		<?php }?>
		
		<?php if ($_GET['a'] == 'edit') {?>
			&nbsp;
			<button class="btn btn-warning" type="button" onclick="location.href='index.php?p=slider&a=photo&sid=<?php echo $_GET['id'];?>
'">
				Toutes les photos
			</button>
		<?php }?>

		<?php if ($_GET['a'] == 'sort' || $_GET['a'] == 'photoedit') {?>
			&nbsp;
			<button class="btn btn-warning" type="button" onclick="location.href='index.php?p=slider&a=photo&sid=<?php echo $_smarty_tpl->tpl_vars['sid']->value;?>
'">
				Retour aux photos
			</button>
			&nbsp;
			<button class="btn btn-warning" type="button" onclick="location.href='index.php?p=slider&a=edit&id=<?php echo $_smarty_tpl->tpl_vars['sid']->value;?>
'">
				Retour aux paramètres du slider
			</button>
		<?php }?>
		
</div><?php }
}
