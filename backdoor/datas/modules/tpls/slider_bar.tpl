{* Pages Bar Buttons action *}

<div class="well well-sm">

	<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=slider'">
		Tous les sliders
	</button>
	
	{if $smarty.get.a == 'photo'}
		&nbsp;
		<button class="btn btn-success" type="button" onclick="location.href='index.php?p=slider&a=sort&sid={$sid}'">
			Trier les photos
		</button>
		&nbsp;
		<button class="btn btn-warning" type="button" onclick="location.href='index.php?p=slider&a=edit&id={$sid}'">
			Retour aux paramètres du slider
		</button>
	{/if}
	
	{if $smarty.get.a == 'edit'}
		&nbsp;
		<button class="btn btn-warning" type="button" onclick="location.href='index.php?p=slider&a=photo&sid={$smarty.get.id}'">
			Toutes les photos
		</button>
	{/if}

	{if $smarty.get.a == 'sort' || $smarty.get.a == 'photoedit'}
		&nbsp;
		<button class="btn btn-warning" type="button" onclick="location.href='index.php?p=slider&a=photo&sid={$sid}'">
			Retour aux photos
		</button>
		&nbsp;
		<button class="btn btn-warning" type="button" onclick="location.href='index.php?p=slider&a=edit&id={$sid}'">
			Retour aux paramètres du slider
		</button>
	{/if}
	
	<br><br>
	
	<button class="btn btn-info" type="button" onclick="location.href='index.php?p=slider&a=add'">
		<span class="glyphicon glyphicon-plus">1 slider </span>
	</button>
	&nbsp;
	<button class="btn btn-info" type="button" onclick="location.href='index.php?p=slider&a=photoadd'">
		<span class="glyphicon glyphicon-plus">1 photo / vidéo </span>
	</button>
		
</div>