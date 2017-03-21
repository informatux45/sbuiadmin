{* Graduates Bar Buttons action *}

<div class="well well-sm">
	{*<h4>Actions</h4>*}


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
		
		{if $smarty.get.a == 'categoryedit' || $smarty.get.a == 'tpl_list' || $smarty.get.a == 'tpl_single' || $smarty.get.a == 'settingscategory'}
		&nbsp;
		<button class="btn btn-warning" type="button" onclick="location.href='index.php?p=graduates&a=sortgraduates&catid={$smarty.get.id}'">
			Trier les graduates
		</button>		
		{/if}

 		{if $smarty.get.a == 'tpl_single'}
			&nbsp;
			<button class="btn btn-success" type="button" onclick="location.href='index.php?p=graduates&a=tpl_list&id={$smarty.get.id}'">
				Template LIST
			</button>
		{elseif $smarty.get.a == 'tpl_list'}
			&nbsp;
			<button class="btn btn-success" type="button" onclick="location.href='index.php?p=graduates&a=tpl_single&id={$smarty.get.id}'">
				Template SINGLE
			</button>
		{/if}

</div>

{if !$smarty.get.a}
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
					<td>{$total_graduates}</td>
					<td>{$total_graduates_active}</td>
					<td>{$total_categories}</td>
					<td>{$total_categories_active}</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
{/if}