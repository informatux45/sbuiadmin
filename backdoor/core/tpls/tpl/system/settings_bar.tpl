{* CMS Config Bar Buttons action *}

<div class="data-toolbar" style="margin-bottom:20px">
	<div class="data-toolbar-left" style="flex-wrap:wrap">
		<button class="btn {if $smarty.get.p == 'settings'}btn--primary{else}btn--ghost{/if}" type="button" onclick="location.href='index.php?p=settings'">
			Générale
		</button>
		<button class="btn {if $smarty.get.p == 'session'}btn--primary{else}btn--ghost{/if}" type="button" onclick="location.href='index.php?p=session'">
			Session
		</button>
		<button class="btn {if $smarty.get.p == 'server'}btn--primary{else}btn--ghost{/if}" type="button" onclick="location.href='index.php?p=server'">
			Serveur
		</button>
		<button class="btn {if $smarty.get.p == 'cache'}btn--primary{else}btn--ghost{/if}" type="button" onclick="location.href='index.php?p=cache'">
			Cache
		</button>
		<button class="btn {if $smarty.get.p == 'dashboard'}btn--primary{else}btn--ghost{/if}" type="button" onclick="location.href='index.php?p=dashboard'">
			Dashboard
		</button>
		<button class="btn {if $smarty.get.p == 'toolbarck'}btn--primary{else}btn--ghost{/if}" type="button" onclick="location.href='index.php?p=toolbarck'">
			Toolbar CKEditor
		</button>
		<button class="btn {if $smarty.get.p == 'theme'}btn--primary{else}btn--ghost{/if}" type="button" onclick="location.href='index.php?p=theme'">
			Thème
		</button>
		<button class="btn {if $smarty.get.p == 'themeinfos'}btn--primary{else}btn--ghost{/if}" type="button" onclick="location.href='index.php?p=themeinfos'">
			Thème infos
		</button>
		<button class="btn btn--ghost" type="button" onclick="window.open('{$smarty.const.SB_ADMIN_URL}assets/samples/')">
			BOOTSTRAP Samples
		</button>
	</div>
</div>
