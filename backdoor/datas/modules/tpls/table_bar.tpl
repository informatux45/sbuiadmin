{* Graduates Bar Buttons action *}

<div class="data-toolbar" style="margin-bottom:20px">
	<div class="data-toolbar-left">
		<div class="dd-wrap">
			<button type="button" class="btn btn--ghost" data-dropdown style="display:inline-flex;align-items:center;gap:6px">
				Tableaux
				<svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0"><path d="m6 9 6 6 6-6"/></svg>
			</button>
			<div class="dd-menu" role="menu" style="min-width:220px">
				<a class="dd-menu-item" href="{$smarty.const._AM_SITE_URL}index.php?p=table">Tous les tableaux</a>
				<div class="dd-divider"></div>
				<a class="dd-menu-item" href="{$smarty.const._AM_SITE_URL}index.php?p=table&a=add">+1 tableau</a>
			</div>
		</div>

		{if isset($smarty.get.a) && ($smarty.get.a == 'editfield' || $smarty.get.a == 'delfield' || $smarty.get.a == 'sortstructure' || $smarty.get.a == 'sortdatas')}
		<button class="btn btn--ghost" type="button" onclick="location.href='index.php?p=table&a=editdatas&tid={$smarty.get.tid}'">
			Ses données
		</button>
		{/if}

		{if isset($smarty.get.a) && ($smarty.get.a == 'editdatas' || $smarty.get.a == 'deldatas' || $smarty.get.a == 'sortstructure' || $smarty.get.a == 'sortdatas')}
		<button class="btn btn--ghost" type="button" onclick="location.href='index.php?p=table&a=editfield&tid={$smarty.get.tid}'">
			Sa structure
		</button>
		{/if}
	</div>
</div>