{* News Bar Buttons action *}

<div class="well well-sm">
	{*<h4>Actions</h4>*}

		<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=news'">
			Tous les articles
		</button>
		&nbsp;
		<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=news&a=category'">
			Toutes les catégories
		</button>
		&nbsp;
		<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=news&a=settings'">
			Paramètres
		</button>
		&nbsp;
		<button class="btn btn-danger" type="button" data-toggle="modal" data-target="#sbnews_shortcodes">
			Shortcodes
		</button>
		
		{if isset($smarty.get.a) && $smarty.get.a == 'category'}
			&nbsp;
			<button class="btn btn-success" type="button" onclick="location.href='index.php?p=news&a=sort'">
				Trier les catégories
			</button>
		{/if}
		
		{if isset($smarty.get.a) && $smarty.get.a == 'tpl_single'}
			&nbsp;
			<button class="btn btn-success" type="button" onclick="location.href='index.php?p=news&a=tpl_list&id={$smarty.get.id}'">
				Template LIST
			</button>
		{elseif isset($smarty.get.a) && $smarty.get.a == 'tpl_list'}
			&nbsp;
			<button class="btn btn-success" type="button" onclick="location.href='index.php?p=news&a=tpl_single&id={$smarty.get.id}'">
				Template SINGLE
			</button>
		{/if}
		
		<br><br>
		
		<button class="btn btn-info" type="button" onclick="location.href='index.php?p=news&a=add'">
			<span class="glyphicon glyphicon-plus">1 article </span>
		</button>
		&nbsp;
		<button class="btn btn-info" type="button" onclick="location.href='index.php?p=news&a=categoryadd'">
			<span class="glyphicon glyphicon-plus">1 catégorie </span>
		</button>
		
</div>

{if !isset($smarty.get.a)}
<div class="panel panel-yellow">
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
					<td>{$total_news}</td>
					<td>{$total_news_active}</td>
					<td>{$total_categories}</td>
					<td>{$total_categories_active}</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
{/if}