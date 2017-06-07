{* Graduates Bar Buttons action *}

<div class="well well-sm">
	{*<h4>Actions</h4>*}

	<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=table'">
		Tous les tableaux
	</button>		
	
	{if $smarty.get.a == 'editfield' || $smarty.get.a == 'delfield' || $smarty.get.a == 'sortstructure' || $smarty.get.a == 'sortdatas'}
	&nbsp;
	<button class="btn btn-warning" type="button" onclick="location.href='index.php?p=table&a=editdatas&tid={$smarty.get.tid}'">
		Ses données
	</button>
	{/if}

	{if $smarty.get.a == 'editdatas' || $smarty.get.a == 'deldatas' || $smarty.get.a == 'sortstructure' || $smarty.get.a == 'sortdatas'}
	&nbsp;
	<button class="btn btn-success" type="button" onclick="location.href='index.php?p=table&a=editfield&tid={$smarty.get.tid}'">
		Sa structure
	</button>
	{/if}

	<br><br>
	
	<button class="btn btn-info" type="button" onclick="location.href='index.php?p=table&a=add'">
		<span class="glyphicon glyphicon-plus">1 tableau </span>
	</button>

</div>