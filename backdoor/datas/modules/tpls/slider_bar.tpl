{* Pages Bar Buttons action *}

<div class="well well-sm">
	
	<div class="btn-group">
		<button type="button" class="btn btn-outline btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
			Sliders
			<span class="caret"></span>
		</button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li><a href="{$sbsmarty.const._AM_SITE_URL}index.php?p=slider">Tous les sliders</a></li>
			<li class="divider"></li>
			<li><a href="{$sbsmarty.const._AM_SITE_URL}index.php?p=slider&a=add">+1 slider</a></li>
			<li><a href="{$sbsmarty.const._AM_SITE_URL}index.php?p=slider&a=photoadd">+1 photo / vidéo</a></li>
		</ul>
	</div>

	
	{if isset($smarty.get.a) && $smarty.get.a == 'photo'}
		&nbsp;&nbsp;
		<button class="btn btn-outline btn-success" type="button" onclick="location.href='index.php?p=slider&a=sort&sid={$sid}'">
			Trier les photos
		</button>
		&nbsp;
		<button class="btn btn-outline btn-warning" type="button" onclick="location.href='index.php?p=slider&a=edit&id={$sid}'">
			Retour aux paramètres du slider
		</button>
	{/if}
	
	{if isset($smarty.get.a) && $smarty.get.a == 'edit'}
		&nbsp;&nbsp;
		<button class="btn btn-outline btn-warning" type="button" onclick="location.href='index.php?p=slider&a=photo&sid={$smarty.get.id}'">
			Toutes les photos
		</button>
	{/if}

	{if isset($smarty.get.a) && ($smarty.get.a == 'sort' || $smarty.get.a == 'photoedit')}
		&nbsp;&nbsp;
		<button class="btn btn-outline btn-warning" type="button" onclick="location.href='index.php?p=slider&a=photo&sid={$sid}'">
			Retour aux photos
		</button>
		&nbsp;
		<button class="btn btn-outline btn-warning" type="button" onclick="location.href='index.php?p=slider&a=edit&id={$sid}'">
			Retour aux paramètres du slider
		</button>
	{/if}
		
</div>