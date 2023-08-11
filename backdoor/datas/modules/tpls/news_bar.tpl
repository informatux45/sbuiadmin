{* News Bar Buttons action *}

<div class="well well-sm">
	{*<h4>Actions</h4>*}
	
	<div class="btn-group">
		<button type="button" class="btn btn-outline btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
			Articles
			<span class="caret"></span>
		</button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li><a href="{$sbsmarty.const._AM_SITE_URL}index.php?p=news">Tous les articles</a></li>
			<li class="divider"></li>
			<li><a href="{$sbsmarty.const._AM_SITE_URL}index.php?p=news&a=add">+1 article</a></li>
		</ul>
	</div>
	&nbsp;&nbsp;
	<div class="btn-group">
		<button type="button" class="btn btn-outline btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
			Catégories
			<span class="caret"></span>
		</button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li><a href="{$sbsmarty.const._AM_SITE_URL}index.php?p=news&a=category">Toutes les catégories</a></li>
			<li class="divider"></li>
			<li><a href="{$sbsmarty.const._AM_SITE_URL}index.php?p=news&a=categoryadd">+1 catégorie</a></li>
		</ul>
	</div>
	&nbsp;&nbsp;
	<button class="btn btn-outline btn-success" type="button" onclick="location.href='index.php?p=news&a=settings'">
		Paramètres
	</button>
	&nbsp;&nbsp;
	<button class="btn btn-outline btn-danger" type="button" data-toggle="modal" data-target="#sbnews_shortcodes">
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