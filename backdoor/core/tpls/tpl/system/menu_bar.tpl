{* Pages Bar Buttons action *}

<div class="data-toolbar" style="margin-bottom:20px">
	<div class="data-toolbar-left">
		<div class="dd-wrap">
			<button type="button" class="btn btn--ghost" data-dropdown style="display:inline-flex;align-items:center;gap:6px">
				Menu
				<svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0"><path d="m6 9 6 6 6-6"/></svg>
			</button>
			<div class="dd-menu" role="menu" style="min-width:220px">
				<a class="dd-menu-item" href="{$smarty.const._AM_SITE_URL}index.php?p=menu">Tous les menus</a>
				<div class="dd-divider"></div>
				<a class="dd-menu-item" href="{$smarty.const._AM_SITE_URL}index.php?p=menu&a=add">+1 menu</a>
			</div>
		</div>

		{if isset($smarty.get.id) && $smarty.get.a != 'sort'}
		<button class="btn btn--primary" type="button" onclick="location.href='index.php?p=menu&a=sort&id={$smarty.get.id}'">
			Trier les pages du menu
		</button>
		{/if}

		{if isset($smarty.get.a) && $smarty.get.a == 'sort'}
		<button class="btn btn--primary" type="button" onclick="location.href='index.php?p=menu&a=edit&id={$smarty.get.id}'">
			Retour au menu
		</button>
		{/if}
	</div>
</div>