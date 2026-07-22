{* Pages Bar Buttons action *}

<div class="data-toolbar" style="margin-bottom:20px">
	<div class="data-toolbar-left">
		<div class="dd-wrap">
			<button type="button" class="btn btn--ghost" data-dropdown style="display:inline-flex;align-items:center;gap:6px">
				Formulaires
				<svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0"><path d="m6 9 6 6 6-6"/></svg>
			</button>
			<div class="dd-menu" role="menu" style="min-width:220px">
				<a class="dd-menu-item" href="{$smarty.const._AM_SITE_URL}index.php?p=contact">Tous les formulaires</a>
				<div class="dd-divider"></div>
				<a class="dd-menu-item" href="{$smarty.const._AM_SITE_URL}index.php?p=contact&a=add">+1 formulaire</a>
			</div>
		</div>
	</div>
	<div class="data-toolbar-right">
		<button class="btn btn--primary" type="button" onclick="location.href='index.php?p=contact&a=settings'">
			Paramètres
		</button>
	</div>
</div>