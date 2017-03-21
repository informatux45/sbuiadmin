<?php
/* Smarty version 3.1.29, created on 2016-11-28 17:06:44
  from "/Applications/MAMP/htdocs/sbmagic_new/administration/datas/modules/tpls/graduates_bar.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_583c5614cbca74_65249946',
  'file_dependency' => 
  array (
    '7acbbdfdb1a3d93029de6b2291dfb74009fa504d' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/administration/datas/modules/tpls/graduates_bar.tpl',
      1 => 1477380321,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_583c5614cbca74_65249946 ($_smarty_tpl) {
?>


<div class="well well-sm">
	


		<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=graduates'">
			Tous les graduates
		</button>
		&nbsp;
		<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=graduates&a=category'">
			Toutes les catégories
		</button>
		&nbsp;		
		<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=graduates&a=add'">
			Ajouter un graduate
		</button>		
		&nbsp;
		<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=graduates&a=categoryadd'">
			Ajouter une catégorie
		</button>
		&nbsp;
		<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=graduates&a=settings'">
			Paramètres
		</button>
		&nbsp;
		<button class="btn btn-outline btn-success" type="button" onclick="location.href='index.php?p=graduates&a=sort'">
			Trier les catégories
		</button>
		
		<?php if ($_GET['a'] == 'categoryedit' || $_GET['a'] == 'tpl_list' || $_GET['a'] == 'tpl_single' || $_GET['a'] == 'settingscategory') {?>
		&nbsp;
		<button class="btn btn-warning" type="button" onclick="location.href='index.php?p=graduates&a=sortgraduates&catid=<?php echo $_GET['id'];?>
'">
			Trier les graduates
		</button>		
		<?php }?>

 		<?php if ($_GET['a'] == 'tpl_single') {?>
			&nbsp;
			<button class="btn btn-success" type="button" onclick="location.href='index.php?p=graduates&a=tpl_list&id=<?php echo $_GET['id'];?>
'">
				Template LIST
			</button>
		<?php } elseif ($_GET['a'] == 'tpl_list') {?>
			&nbsp;
			<button class="btn btn-success" type="button" onclick="location.href='index.php?p=graduates&a=tpl_single&id=<?php echo $_GET['id'];?>
'">
				Template SINGLE
			</button>
		<?php }?>

</div>

<?php if (!$_GET['a']) {?>
<div class="panel panel-yellow">
	<div class="panel-heading">
		<span class="fa fa-info-circle fa-fw"></span> INFOS Graduates
	</div>
	<div class="panel-body">
		<table class="table">
			<thead>
				<tr>
					<th style="text-align: center;">Graduates Totals</th>
					<th style="text-align: center;">Graduates Actifs</th>
					<th style="text-align: center;">Catégories Totales</th>
					<th style="text-align: center;">Catégories Actives</th>
				</tr>
			</thead>
			<tbody>
				<tr style="text-align: center;">
					<td><?php echo $_smarty_tpl->tpl_vars['total_graduates']->value;?>
</td>
					<td><?php echo $_smarty_tpl->tpl_vars['total_graduates_active']->value;?>
</td>
					<td><?php echo $_smarty_tpl->tpl_vars['total_categories']->value;?>
</td>
					<td><?php echo $_smarty_tpl->tpl_vars['total_categories_active']->value;?>
</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<?php }
}
}
