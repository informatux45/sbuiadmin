{* Graduates Bar Buttons action *}

<div class="well well-sm">
	{*<h4>Actions</h4>*}

	<div class="btn-group">
		<button type="button" class="btn btn-outline btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
			Tableaux
			<span class="caret"></span>
		</button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li><a href="{$smarty.const._AM_SITE_URL}index.php?p=table">Tous les tableaux</a></li>
			<li class="divider"></li>
			<li><a href="{$smarty.const._AM_SITE_URL}index.php?p=table&a=add">+1 tableau</a></li>
		</ul>
	</div>	
	
	{if isset($smarty.get.a) && ($smarty.get.a == 'editfield' || $smarty.get.a == 'delfield' || $smarty.get.a == 'sortstructure' || $smarty.get.a == 'sortdatas')}
	&nbsp;&nbsp;
	<button class="btn btn-outline btn-warning" type="button" onclick="location.href='index.php?p=table&a=editdatas&tid={$smarty.get.tid}'">
		Ses données
	</button>
	{/if}

	{if isset($smarty.get.a) && ($smarty.get.a == 'editdatas' || $smarty.get.a == 'deldatas' || $smarty.get.a == 'sortstructure' || $smarty.get.a == 'sortdatas')}
	&nbsp;&nbsp;
	<button class="btn btn-outline btn-success" type="button" onclick="location.href='index.php?p=table&a=editfield&tid={$smarty.get.tid}'">
		Sa structure
	</button>
	{/if}

</div>