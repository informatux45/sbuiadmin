{* CMS Config Bar Buttons action *}

<div class="well well-sm">
	{*<h4>Actions</h4>*}

	<button class="btn {if $smarty.get.p == 'cmsconfig' && !isset($smarty.get.op)}{else}btn-outline{/if} btn-primary" type="button" onclick="location.href='index.php?p=cmsconfig'">
		Header / Footer
	</button>
	&nbsp;
	<button class="btn {if $smarty.get.p == 'cmsconfig' && (isset($smarty.get.op) && $smarty.get.op == 'css')}{else}btn-outline{/if} btn-primary" type="button" onclick="location.href='index.php?p=cmsconfig&op=css'">
		CSS Header
	</button>
	&nbsp;
	<button class="btn {if $smarty.get.p == 'cmsconfig' && (isset($smarty.get.op) && $smarty.get.op == 'javascript')}{else}btn-outline{/if} btn-primary" type="button" onclick="location.href='index.php?p=cmsconfig&op=javascript'">
		JAVASCRIPT Code
	</button>
	&nbsp;
	<button class="btn {if $smarty.get.p == 'cmsconfig' && (isset($smarty.get.op) && $smarty.get.op == 'comingsoon')}{else}btn-outline{/if} btn-primary" type="button" onclick="location.href='index.php?p=cmsconfig&op=comingsoon'">
		Coming Soon
	</button>
	&nbsp;
	<button class="btn {if $smarty.get.p == 'cmsconfig' && (isset($smarty.get.op) && $smarty.get.op == 'multilang')}{else}btn-outline{/if} btn-primary" type="button" onclick="location.href='index.php?p=cmsconfig&op=multilang'">
		Multilangue
	</button>
	&nbsp;
	<button class="btn {if $smarty.get.p == 'cmsconfig' && (isset($smarty.get.op) && $smarty.get.op == 'plugins')}{else}btn-outline{/if} btn-primary" type="button" onclick="location.href='index.php?p=cmsconfig&op=plugins'">
		Plugins
	</button>
	&nbsp;
	<button class="btn {if $smarty.get.p == 'cmsconfig' && (isset($smarty.get.op) && $smarty.get.op == 'fonts')}{else}btn-outline{/if} btn-primary" type="button" onclick="location.href='index.php?p=cmsconfig&op=fonts'">
		Fonts
	</button>
	&nbsp;
	<button class="btn {if $smarty.get.p == 'cmsconfig' && (isset($smarty.get.op) && $smarty.get.op == 'seo')}{else}btn-outline{/if} btn-primary" type="button" onclick="location.href='index.php?p=cmsconfig&op=seo'">
		SEO
	</button>
		
</div>