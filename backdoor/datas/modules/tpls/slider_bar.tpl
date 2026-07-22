{* Pages Bar Buttons action *}

<div class="data-toolbar" style="margin-bottom:20px">
	<div class="data-toolbar-left">
		<div class="dd-wrap">
			<button type="button" class="btn btn--ghost" data-dropdown style="display:inline-flex;align-items:center;gap:6px">
				Sliders
				<svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0"><path d="m6 9 6 6 6-6"/></svg>
			</button>
			<div class="dd-menu" role="menu" style="min-width:220px">
				<a class="dd-menu-item" href="{$smarty.const._AM_SITE_URL}index.php?p=slider">Tous les sliders</a>
				<div class="dd-divider"></div>
				<a class="dd-menu-item" href="{$smarty.const._AM_SITE_URL}index.php?p=slider&a=add">+1 slider</a>
				<a class="dd-menu-item" href="{$smarty.const._AM_SITE_URL}index.php?p=slider&a=photoadd">+1 photo / vidéo</a>
			</div>
		</div>

		{if isset($smarty.get.a) && $smarty.get.a == 'photo'}
			<button class="btn btn--ghost" type="button" onclick="location.href='index.php?p=slider&a=sort&sid={$sid}'">
				Trier les photos
			</button>
			<button class="btn btn--ghost" type="button" onclick="location.href='index.php?p=slider&a=edit&id={$sid}'">
				Retour aux paramètres du slider
			</button>
		{/if}

		{if isset($smarty.get.a) && $smarty.get.a == 'edit'}
			<button class="btn btn--ghost" type="button" onclick="location.href='index.php?p=slider&a=photo&sid={$smarty.get.id}'">
				Toutes les photos
			</button>
		{/if}

		{if isset($smarty.get.a) && ($smarty.get.a == 'sort' || $smarty.get.a == 'photoedit')}
			<button class="btn btn--ghost" type="button" onclick="location.href='index.php?p=slider&a=photo&sid={$sid}'">
				Retour aux photos
			</button>
			<button class="btn btn--ghost" type="button" onclick="location.href='index.php?p=slider&a=edit&id={$sid}'">
				Retour aux paramètres du slider
			</button>
		{/if}
	</div>
</div>