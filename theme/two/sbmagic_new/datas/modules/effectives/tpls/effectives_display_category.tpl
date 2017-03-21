{* --- EFFECTIVES display a specific category --- *}

{include file='effectives/tpls/effectives_header.tpl'}

{include file='effectives/tpls/effectives_breadcrumb.tpl'}

{if $sbeffectives_module_show == 'masonry'}
	<link rel="stylesheet" href="{$smarty.const.SB_MODULES_URL}{$smarty.const.MODULEFILE}/inc/style_category_masonry.css">
	<style>
		.sbeffectives {
			max-width: {$sbeffectives_module_show_masonry|intval|default:"500"}px;
			width: 100%;
		}
	</style>
	<script src="{$smarty.const.SB_MODULES_URL}{$smarty.const.MODULEFILE}/inc/masonry.pkgd.min.js"></script>
{elseif $sbeffectives_module_show == 'float'}
	<link rel="stylesheet" href="{$smarty.const.SB_MODULES_URL}{$smarty.const.MODULEFILE}/inc/style_category_float.css">
{elseif $sbeffectives_module_show == 'masonrycss'}
	<link rel="stylesheet" href="{$smarty.const.SB_MODULES_URL}{$smarty.const.MODULEFILE}/inc/style_category_masonrycss.css">
{else}
	<link rel="stylesheet" href="{$smarty.const.SB_MODULES_URL}{$smarty.const.MODULEFILE}/inc/style_category_normal.css">
{/if}

{if $sbeffectives_module_show == 'masonry'}
	<div id="sbeffectives" data-masonry='{ "itemSelector": ".sbeffectives" }'>
{else}
	<div id="sbeffectives">	
{/if}

{foreach from=$all item=effectives}
	{* Check if TPL_LIST custom *}
	{if $sbeffectives_cat_tpl_list != ''}
	
		{* Show TPL LIST template *}
		{eval var=$sbeffectives_cat_tpl_list}
		
	{else}
		
		{* Show List effectives DEFAULT *}
		<div class="sbeffectives {cycle values="even,odd"}">

			<div class="sbeffectives-div-l">
				<a class="" href="{seo url="index.php?p=effectives&op=article&id={$effectives.id}" rewrite="effectives/article/{$effectives.id}/{$effectives.name|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"|strip_tags|@sbRewriteString|@strtolower}"}">
					<img src="{$smarty.const._AM_MEDIAS_URL}/{$effectives.photo}" alt="{$effectives.name|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}" style="width: 100%;">
				</a>
			</div>
		
			<div class="sbeffectives-div-r">
				<h3>
					{$effectives.name|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}
				</h3>
				<p class="sbeffectives-info">
					{$effectives.sire|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}
					{$smarty.const._CMS_EFFECTIVES_AND}
					{$effectives.dam|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}
					{$smarty.const._CMS_EFFECTIVES_BY}
					{$effectives.sire_dam|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}
				</p>
				<p class="sbeffectives-p">
					{$effectives.projection|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}
				</p>
				<p class="sbeffectives-link-item">
					<a href="{seo url="index.php?p=effectives&op=article&id={$effectives.id}" rewrite="effectives/article/{$effectives.id}/{$effectives.name|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"|strip_tags|@sbRewriteString|@strtolower}"}">
						{$smarty.const._CMS_EFFECTIVES_READ_ITEM}
					</a>
				</p>
			</div>

			<div class="sbeffectives-clear-both"> </div>

		</div>
		
	{/if}

{foreachelse}

	{$smarty.const._CMS_EFFECTIVES_NOEFFECTIVES}

{/foreach}

</div> {* --- End div sbeffectives --- *}

	{if $all}
		{include file='effectives/tpls/effectives_pagination.tpl'}
	{/if}
