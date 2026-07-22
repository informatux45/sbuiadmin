{* CMS Config Bar Buttons action *}

<div class="data-toolbar" style="margin-bottom:20px">
	<div class="data-toolbar-left" style="flex-wrap:wrap">
		<button class="btn {if $smarty.get.p == 'cmsconfig' && !isset($smarty.get.op)}btn--primary{else}btn--ghost{/if}" type="button" onclick="location.href='index.php?p=cmsconfig'">
			Header / Footer
		</button>
		<button class="btn {if $smarty.get.p == 'cmsconfig' && (isset($smarty.get.op) && $smarty.get.op == 'css')}btn--primary{else}btn--ghost{/if}" type="button" onclick="location.href='index.php?p=cmsconfig&op=css'">
			CSS Header
		</button>
		<button class="btn {if $smarty.get.p == 'cmsconfig' && (isset($smarty.get.op) && $smarty.get.op == 'javascript')}btn--primary{else}btn--ghost{/if}" type="button" onclick="location.href='index.php?p=cmsconfig&op=javascript'">
			JAVASCRIPT Code
		</button>
		<button class="btn {if $smarty.get.p == 'cmsconfig' && (isset($smarty.get.op) && $smarty.get.op == 'comingsoon')}btn--primary{else}btn--ghost{/if}" type="button" onclick="location.href='index.php?p=cmsconfig&op=comingsoon'">
			Coming Soon
		</button>
		<button class="btn {if $smarty.get.p == 'cmsconfig' && (isset($smarty.get.op) && $smarty.get.op == 'multilang')}btn--primary{else}btn--ghost{/if}" type="button" onclick="location.href='index.php?p=cmsconfig&op=multilang'">
			Multilangue
		</button>
		<button class="btn {if $smarty.get.p == 'cmsconfig' && (isset($smarty.get.op) && $smarty.get.op == 'plugins')}btn--primary{else}btn--ghost{/if}" type="button" onclick="location.href='index.php?p=cmsconfig&op=plugins'">
			Plugins
		</button>
		<button class="btn {if $smarty.get.p == 'cmsconfig' && (isset($smarty.get.op) && $smarty.get.op == 'fonts')}btn--primary{else}btn--ghost{/if}" type="button" onclick="location.href='index.php?p=cmsconfig&op=fonts'">
			Fonts
		</button>
		<button class="btn {if $smarty.get.p == 'cmsconfig' && (isset($smarty.get.op) && $smarty.get.op == 'seo')}btn--primary{else}btn--ghost{/if}" type="button" onclick="location.href='index.php?p=cmsconfig&op=seo'">
			SEO
		</button>
	</div>
</div>