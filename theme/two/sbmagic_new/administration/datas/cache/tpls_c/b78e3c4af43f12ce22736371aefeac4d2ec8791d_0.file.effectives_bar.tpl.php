<?php
/* Smarty version 3.1.29, created on 2016-12-16 15:58:56
  from "/Applications/MAMP/htdocs/sbmagic_new/administration/datas/modules/tpls/effectives_bar.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_58540130cc0c13_50831091',
  'file_dependency' => 
  array (
    'b78e3c4af43f12ce22736371aefeac4d2ec8791d' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/administration/datas/modules/tpls/effectives_bar.tpl',
      1 => 1478695020,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_58540130cc0c13_50831091 ($_smarty_tpl) {
?>


<div class="well well-sm">
	

		<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=effectives'">
			Tous les effectifs
		</button>
		&nbsp;
		<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=effectives&a=category'">
			Toutes les catégories
		</button>
		<?php if ($_GET['id'] && ($_GET['a'] == 'categoryedit' || $_GET['a'] == 'edit')) {?>
		&nbsp;
		<button class="btn btn-success" type="button" onclick="location.href='index.php?p=effectives&a=sorteffectives&id=<?php if ($_GET['a'] == "edit") {
echo $_smarty_tpl->tpl_vars['id']->value;
} else {
echo $_GET['id'];
}?>'">
			Trier les effectifs
		</button>
		<?php }?>
		
		<?php if ($_GET['a'] == 'edit') {?>
			&nbsp;
			<button class="btn btn-danger" type="button" onclick="location.href='index.php?p=effectives&a=production&eid=<?php echo $_GET['id'];?>
'">
				Toute sa production
			</button>
			&nbsp;
			<button class="btn btn-danger" type="button" onclick="location.href='index.php?p=effectives&a=medias&id=<?php echo $_GET['id'];?>
'">
				Tous ses medias
			</button>
		<?php }?>

		<?php if ($_GET['a'] == 'category') {?>
			&nbsp;
			<button class="btn btn-success" type="button" onclick="location.href='index.php?p=effectives&a=sort'">
				Trier les catégories
			</button>
		<?php }?>

		<?php if ($_GET['a'] == 'medias') {?>
			&nbsp;
			<button class="btn btn-success" type="button" onclick="location.href='index.php?p=effectives&a=sortmedias&eid=<?php echo $_GET['id'];?>
'">
				Trier les medias
			</button>
		<?php }?>

		<?php if ($_GET['a'] == 'production') {?>
			&nbsp;
			<button class="btn btn-success" type="button" onclick="location.href='index.php?p=effectives&a=sortproduction&eid=<?php echo $_GET['eid'];?>
'">
				Trier les productions
			</button>
		<?php }?>

		<?php if ($_GET['a'] == 'mediasedit' || $_GET['a'] == 'sortmedias') {?>
			&nbsp;
			<button class="btn btn-success" type="button" onclick="location.href='index.php?p=effectives&a=medias&id=<?php echo $_GET['eid'];?>
'">
				Retour aux médias
			</button>
		<?php }?>

		<?php if ($_GET['a'] == 'productionedit' || $_GET['a'] == 'sortproduction') {?>
			&nbsp;
			<button class="btn btn-success" type="button" onclick="location.href='index.php?p=effectives&a=production&eid=<?php echo $_GET['eid'];?>
'">
				Retour aux productions
			</button>
		<?php }?>
		
		<?php if ($_GET['a'] == 'tpl_single') {?>
			&nbsp;
			<button class="btn btn-success" type="button" onclick="location.href='index.php?p=effectives&a=tpl_list&id=<?php echo $_GET['id'];?>
'">
				Template LIST
			</button>
		<?php } elseif ($_GET['a'] == 'tpl_list') {?>
			&nbsp;
			<button class="btn btn-success" type="button" onclick="location.href='index.php?p=effectives&a=tpl_single&id=<?php echo $_GET['id'];?>
'">
				Template SINGLE
			</button>
		<?php }?>

		<br><br>

		<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=effectives&a=add'">
			Ajouter un effectif
		</button>
		&nbsp;
		<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=effectives&a=mediasadd'">
			Ajouter un media
		</button>
		&nbsp;
		<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=effectives&a=categoryadd'">
			Ajouter une catégorie
		</button>
		&nbsp;
		<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=effectives&a=productionadd'">
			Ajouter une production
		</button>
		&nbsp;
		<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=effectives&a=settings'">
			Paramètres
		</button>
		
</div>

<?php if (!$_GET['a']) {?>
<div class="panel panel-yellow">
	<div class="panel-heading">
		<span class="fa fa-info-circle fa-fw"></span> INFOS Effectifs
	</div>
	<div class="panel-body">
		<table class="table">
			<thead>
				<tr>
					<th style="text-align: center;">Effectifs Totals</th>
					<th style="text-align: center;">Effectifs Actifs</th>
					<th style="text-align: center;">Catégories Totales</th>
					<th style="text-align: center;">Catégories Actives</th>
				</tr>
			</thead>
			<tbody>
				<tr style="text-align: center;">
					<td><?php echo $_smarty_tpl->tpl_vars['total_effectives']->value;?>
</td>
					<td><?php echo $_smarty_tpl->tpl_vars['total_effectives_active']->value;?>
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
