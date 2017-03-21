{* Graduates Bar Buttons action *}

<div class="well well-sm">
	{*<h4>Actions</h4>*}

	<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=tabbs'">
		Tous les tabbs
	</button>
	&nbsp;	
	<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=tabbs&a=alltabs'">
		Tous les onglets
	</button>
	
	{if $smarty.get.a == 'edit'}
	&nbsp;
	<button class="btn btn-warning" type="button" onclick="location.href='index.php?p=tabbs&a=sort&tid={$smarty.get.id}'">
		Trier les onglets
	</button>		
	{/if}

	<br><br>
	
	<button class="btn btn-info" type="button" onclick="location.href='index.php?p=tabbs&a=add'">
		<span class="glyphicon glyphicon-plus">1 tabbs </span>
	</button>		
	&nbsp;
	<button class="btn btn-info" type="button" onclick="location.href='index.php?p=tabbs&a=tabadd'">
		<span class="glyphicon glyphicon-plus">1 onglet </span>
	</button>

</div>