{* CMS Config Bar Buttons action *}

<div class="well well-sm">
	{*<h4>Actions</h4>*}

	<button class="btn {if $smarty.get.p == 'settings'}{else}btn-outline{/if} btn-primary" type="button" onclick="location.href='index.php?p=settings'">
		Générale
	</button>
	&nbsp;
	<button class="btn {if $smarty.get.p == 'session'}{else}btn-outline{/if} btn-primary" type="button" onclick="location.href='index.php?p=session'">
		Session
	</button>
	&nbsp;
	<button class="btn {if $smarty.get.p == 'server'}{else}btn-outline{/if} btn-primary" type="button" onclick="location.href='index.php?p=server'">
		Serveur
	</button>
	&nbsp;
	<button class="btn {if $smarty.get.p == 'cache'}{else}btn-outline{/if} btn-primary" type="button" onclick="location.href='index.php?p=cache'">
		Cache
	</button>
	&nbsp;
	<button class="btn {if $smarty.get.p == 'dashboard'}{else}btn-outline{/if} btn-primary" type="button" onclick="location.href='index.php?p=dashboard'">
		Dashboard
	</button>
	&nbsp;
	<button class="btn {if $smarty.get.p == 'toolbarck'}{else}btn-outline{/if} btn-primary" type="button" onclick="location.href='index.php?p=toolbarck'">
		Toolbar CKEditor
	</button>
	&nbsp;
	<button class="btn {if $smarty.get.p == 'theme'}{else}btn-outline{/if} btn-primary" type="button" onclick="location.href='index.php?p=theme'">
		Thème
	</button>
	&nbsp;
	<button class="btn {if $smarty.get.p == 'themeinfos'}{else}btn-outline{/if} btn-primary" type="button" onclick="location.href='index.php?p=themeinfos'">
		Thème infos
	</button>
	&nbsp;
	<button class="btn btn-info" type="button" onclick="window.open('{$smarty.const.SB_ADMIN_URL}assets/samples/')">
		BOOTSTRAP Samples
	</button>
		
</div>
