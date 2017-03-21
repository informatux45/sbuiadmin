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
		&nbsp;
		<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=tabbs&a=add'">
			Ajouter un tabbs
		</button>		
		&nbsp;
		<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=tabbs&a=tabadd'">
			Ajouter un onglet
		</button>
		
		{if $smarty.get.a == 'edit'}
		&nbsp;
		<button class="btn btn-warning" type="button" onclick="location.href='index.php?p=tabbs&a=sort&tid={$smarty.get.id}'">
			Trier les onglets
		</button>		
		{/if}

</div>