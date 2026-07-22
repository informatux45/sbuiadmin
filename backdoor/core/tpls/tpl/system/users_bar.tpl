{* Pages Bar Buttons action *}

<div class="data-toolbar" style="margin-bottom:20px">
	<div class="data-toolbar-left">
		<div class="dd-wrap">
			<button type="button" class="btn btn--ghost" data-dropdown style="display:inline-flex;align-items:center;gap:6px">
				Utilisateurs
				<svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0"><path d="m6 9 6 6 6-6"/></svg>
			</button>
			<div class="dd-menu" role="menu" style="min-width:220px">
				<a class="dd-menu-item" href="{$smarty.const._AM_SITE_URL}index.php?p=users">Tous les utilisateurs</a>
				<div class="dd-divider"></div>
				<a class="dd-menu-item" href="{$smarty.const._AM_SITE_URL}index.php?p=users&a=add">+1 utilisateur</a>
				<a class="dd-menu-item" style="color:var(--danger)" href="{$smarty.const._AM_SITE_URL}index.php?p=settings">+1 administrateur</a>
			</div>
		</div>

		<button class="btn btn--primary" type="button" onclick="window.open('https://fr.gravatar.com/')">
			Gravatar
		</button>

		<div class="dd-wrap">
			<button type="button" class="btn btn--ghost" data-dropdown style="display:inline-flex;align-items:center;gap:6px">
				IPs Bloquées
				<svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0"><path d="m6 9 6 6 6-6"/></svg>
			</button>
			<div class="dd-menu" role="menu" style="min-width:220px">
				<a class="dd-menu-item" href="{$smarty.const._AM_SITE_URL}index.php?p=users&a=blockedip">IP(s) Bloquée(s)</a>
				<a class="dd-menu-item" href="{$smarty.const._AM_SITE_URL}index.php?p=users&a=blockedipsettings">Param&egrave;tres</a>
			</div>
		</div>
	</div>
</div>