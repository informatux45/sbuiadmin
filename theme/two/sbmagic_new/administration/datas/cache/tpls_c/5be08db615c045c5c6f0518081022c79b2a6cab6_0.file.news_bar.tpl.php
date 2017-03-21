<?php
/* Smarty version 3.1.29, created on 2016-12-12 15:40:00
  from "/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/system/news_bar.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_584eb6c09511d5_35251947',
  'file_dependency' => 
  array (
    '5be08db615c045c5c6f0518081022c79b2a6cab6' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/system/news_bar.tpl',
      1 => 1476256682,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_584eb6c09511d5_35251947 ($_smarty_tpl) {
?>


<div class="well well-sm">
	

		<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=news'">
			Tous les articles
		</button>
		&nbsp;
		<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=news&a=category'">
			Toutes les catégories
		</button>
		&nbsp;
		<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=news&a=add'">
			Ajouter un article
		</button>
		&nbsp;
		<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=news&a=categoryadd'">
			Ajouter une catégorie
		</button>
		&nbsp;
		<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=news&a=settings'">
			Paramètres
		</button>
		&nbsp;
		<button class="btn btn-danger" type="button" data-toggle="modal" data-target="#sbnews_shortcodes">
			Shortcodes
		</button>
		
		<?php if ($_GET['a'] == 'category') {?>
			&nbsp;
			<button class="btn btn-success" type="button" onclick="location.href='index.php?p=news&a=sort'">
				Trier les catégories
			</button>
		<?php }?>
		
		<?php if ($_GET['a'] == 'tpl_single') {?>
			&nbsp;
			<button class="btn btn-success" type="button" onclick="location.href='index.php?p=news&a=tpl_list&id=<?php echo $_GET['id'];?>
'">
				Template LIST
			</button>
		<?php } elseif ($_GET['a'] == 'tpl_list') {?>
			&nbsp;
			<button class="btn btn-success" type="button" onclick="location.href='index.php?p=news&a=tpl_single&id=<?php echo $_GET['id'];?>
'">
				Template SINGLE
			</button>
		<?php }?>
		
</div>

<?php if (!$_GET['a']) {?>
<div class="panel panel-success">
	<div class="panel-heading">
		INFOS News
	</div>
	<div class="panel-body">
		<table class="table">
			<thead>
				<tr>
					<th style="text-align: center;">Articles Totals</th>
					<th style="text-align: center;">Articles Actifs</th>
					<th style="text-align: center;">Catégories Totales</th>
					<th style="text-align: center;">Catégories Actives</th>
				</tr>
			</thead>
			<tbody>
				<tr style="text-align: center;">
					<td><?php echo $_smarty_tpl->tpl_vars['total_news']->value;?>
</td>
					<td><?php echo $_smarty_tpl->tpl_vars['total_news_active']->value;?>
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
