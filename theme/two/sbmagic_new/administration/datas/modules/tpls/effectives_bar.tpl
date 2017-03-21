{* News Bar Buttons action *}

<div class="well well-sm">
	{*<h4>Actions</h4>*}

		<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=effectives'">
			Tous les effectifs
		</button>
		&nbsp;
		<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=effectives&a=category'">
			Toutes les catégories
		</button>
		{if $smarty.get.id && ($smarty.get.a == 'categoryedit' || $smarty.get.a == 'edit')}
		&nbsp;
		<button class="btn btn-success" type="button" onclick="location.href='index.php?p=effectives&a=sorteffectives&id={if $smarty.get.a == "edit"}{$id}{else}{$smarty.get.id}{/if}'">
			Trier les effectifs
		</button>
		{/if}
		
		{if $smarty.get.a == 'edit'}
			&nbsp;
			<button class="btn btn-danger" type="button" onclick="location.href='index.php?p=effectives&a=production&eid={$smarty.get.id}'">
				Toute sa production
			</button>
			&nbsp;
			<button class="btn btn-danger" type="button" onclick="location.href='index.php?p=effectives&a=medias&id={$smarty.get.id}'">
				Tous ses medias
			</button>
		{/if}

		{if $smarty.get.a == 'category'}
			&nbsp;
			<button class="btn btn-success" type="button" onclick="location.href='index.php?p=effectives&a=sort'">
				Trier les catégories
			</button>
		{/if}

		{if $smarty.get.a == 'medias'}
			&nbsp;
			<button class="btn btn-success" type="button" onclick="location.href='index.php?p=effectives&a=sortmedias&eid={$smarty.get.id}'">
				Trier les medias
			</button>
		{/if}

		{if $smarty.get.a == 'production'}
			&nbsp;
			<button class="btn btn-success" type="button" onclick="location.href='index.php?p=effectives&a=sortproduction&eid={$smarty.get.eid}'">
				Trier les productions
			</button>
		{/if}

		{if $smarty.get.a == 'mediasedit' OR $smarty.get.a == 'sortmedias'}
			&nbsp;
			<button class="btn btn-success" type="button" onclick="location.href='index.php?p=effectives&a=medias&id={$smarty.get.eid}'">
				Retour aux médias
			</button>
		{/if}

		{if $smarty.get.a == 'productionedit' OR $smarty.get.a == 'sortproduction'}
			&nbsp;
			<button class="btn btn-success" type="button" onclick="location.href='index.php?p=effectives&a=production&eid={$smarty.get.eid}'">
				Retour aux productions
			</button>
		{/if}
		
		{if $smarty.get.a == 'tpl_single'}
			&nbsp;
			<button class="btn btn-success" type="button" onclick="location.href='index.php?p=effectives&a=tpl_list&id={$smarty.get.id}'">
				Template LIST
			</button>
		{elseif $smarty.get.a == 'tpl_list'}
			&nbsp;
			<button class="btn btn-success" type="button" onclick="location.href='index.php?p=effectives&a=tpl_single&id={$smarty.get.id}'">
				Template SINGLE
			</button>
		{/if}

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

{if !$smarty.get.a}
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
					<td>{$total_effectives}</td>
					<td>{$total_effectives_active}</td>
					<td>{$total_categories}</td>
					<td>{$total_categories_active}</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
{/if}