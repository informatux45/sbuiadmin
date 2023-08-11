{* Graduates Bar Buttons action *}

<div class="well well-sm">
	{*<h4>Actions</h4>*}
	
	<div class="btn-group">
		<button type="button" class="btn btn-outline btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
			Tabbs
			<span class="caret"></span>
		</button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li><a href="{$smarty.const._AM_SITE_URL}index.php?p=tabbs">Tous les tabbs</a></li>
			<li class="divider"></li>
			<li><a href="{$smarty.const._AM_SITE_URL}index.php?p=tabbs&a=add">+1 tabbs</a></li>
		</ul>
	</div>
	&nbsp;&nbsp;
	<div class="btn-group">
		<button type="button" class="btn btn-outline btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
			Onglets
			<span class="caret"></span>
		</button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li><a href="{$smarty.const._AM_SITE_URL}index.php?p=tabbs&a=alltabs">Tous les onglets</a></li>
			<li class="divider"></li>
			<li><a href="{$smarty.const._AM_SITE_URL}index.php?p=tabbs&a=tabadd">+1 onglet</a></li>
		</ul>
	</div>
	
	{if isset($smarty.get.a) && $smarty.get.a == 'edit'}
	&nbsp;
	<button class="btn btn-warning" type="button" onclick="location.href='index.php?p=tabbs&a=sort&tid={$smarty.get.id}'">
		Trier les onglets
	</button>		
	{/if}

</div>