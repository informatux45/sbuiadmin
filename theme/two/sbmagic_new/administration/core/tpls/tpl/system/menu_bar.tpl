{* Pages Bar Buttons action *}

<div class="well well-sm">
	{*<h4>Actions</h4>*}

		<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=menu'">
			Tous les menus
		</button>
		&nbsp;
		<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=menu&a=add'">
			Ajouter un menu
		</button>
		{if $smarty.get.id}
		&nbsp;
		<button class="btn btn-success" type="button" onclick="location.href='index.php?p=menu&a=sort&id={$smarty.get.id}'">
			Trier les pages du menu
		</button>
		{/if}

		{if $smarty.get.a == 'sort'}
		&nbsp;
		<button class="btn btn-warning" type="button" onclick="location.href='index.php?p=menu&a=edit&id={$smarty.get.id}'">
			Retour au menu
		</button>
		{/if}
		
</div>