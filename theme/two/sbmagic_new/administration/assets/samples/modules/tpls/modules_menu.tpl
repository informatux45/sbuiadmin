{* ------------------------------------ *}
{* ------------------------------------ *}
{* Sample of menu entry for your module	 *}
{* ------------------------------------ *}
{* ------------------------------------ *}

{*<li>
	<a href="index.php?p=graduates"><i class="fa fa-certificate fa-fw"></i> Graduates<span class="fa arrow"></span></a>
	<ul class="nav nav-second-level{if $active == 'graduates'} collapse in{/if}">
		<li>
			<a {if $active == 'graduates' && !$smarty.get.a}class="active"{/if} href="index.php?p=graduates">Tous les graduates</a>
		</li>
		<li>
			<a {if $active == 'graduates' && $smarty.get.a == 'add'}class="active"{/if} href="index.php?p=graduates&a=add">Ajouter un graduate</a>
		</li>
		<li>
			<a {if $active == 'graduates' && $smarty.get.a == 'sort'}class="active"{/if} href="index.php?p=graduates&a=sort">Trier les graduates</a>
		</li>
	</ul>	
</li>*}

{* ------------------------------------ *}
{* ------------------------------------ *}
{* Put your own entries after this line *}
{* ------------------------------------ *}
{* ------------------------------------ *}

<li>
	<a href="index.php?p=graduates"><i class="fa fa-certificate fa-fw"></i> Graduates<span class="fa arrow"></span></a>
	<ul class="nav nav-second-level{if $active == 'graduates'} collapse in{/if}">
		<li>
			<a {if $active == 'graduates' && !$smarty.get.a}class="active"{/if} href="index.php?p=graduates">Tous les graduates</a>
		</li>
		<li>
			<a {if $active == 'graduates' && $smarty.get.a == 'add'}class="active"{/if} href="index.php?p=graduates&a=add">Ajouter un graduate</a>
		</li>
		<li>
			<a {if $active == 'graduates' && $smarty.get.a == 'sort'}class="active"{/if} href="index.php?p=graduates&a=sort">Trier les graduates</a>
		</li>
	</ul>	
</li>
<li>
	<a href="index.php?p=poulinage"><i class="fa fa-star-half-empty fa-fw"></i> Poulinage<span class="fa arrow"></span></a>
	<ul class="nav nav-second-level{if $active == 'poulinage' || $active == 'foal'} collapse in{/if}">
		<li>
			<a {if $active == 'poulinage' && !$smarty.get.a}class="active"{/if} href="index.php?p=poulinage">Toutes les juments</a>
		</li>
		<li>
			<a {if $active == 'poulinage' && $smarty.get.a == 'add'}class="active"{/if} href="index.php?p=poulinage&a=add">Ajouter une jument</a>
		</li>
		<li>
			<a {if $active == 'foal' && !$smarty.get.a}class="active"{/if} href="index.php?p=foal">Tous les produits</a>
		</li>
		<li>
			<a {if $active == 'foal' && $smarty.get.a == 'add'}class="active"{/if} href="index.php?p=foal&a=add">Ajouter un produit</a>
		</li>
	</ul>	
</li>