{* Pages Bar Buttons action *}

<div class="well well-sm">
	{*<h4>Actions</h4>*}

	<div class="btn-group">
		<button type="button" class="btn btn-outline btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
			Menu
			<span class="caret"></span>
		</button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li><a href="{$smarty.const._AM_SITE_URL}index.php?p=menu">Tous les menus</a></li>
			<li class="divider"></li>
			<li><a href="{$smarty.const._AM_SITE_URL}index.php?p=menu&a=add">+1 menu</a></li>
		</ul>
	</div>

	{if isset($smarty.get.id) && $smarty.get.a != 'sort'}
	&nbsp;&nbsp;
	<button class="btn btn-success" type="button" onclick="location.href='index.php?p=menu&a=sort&id={$smarty.get.id}'">
		Trier les pages du menu
	</button>
	{/if}

	{if isset($smarty.get.a) && $smarty.get.a == 'sort'}
	&nbsp;&nbsp;
	<button class="btn btn-warning" type="button" onclick="location.href='index.php?p=menu&a=edit&id={$smarty.get.id}'">
		Retour au menu
	</button>
	{/if}
		
</div>