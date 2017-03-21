{* --- Effectives Breadcrumb --- *}

{if $sbeffectives_options.breadcrumb}

	<style>
		@import url({$smarty.const.SB_URL}datas/modules/effectives/inc/style_breadcrumb.css);
	</style>

	<div class="sbeffectives_breadcrumb_header">
		
		{if $sbeffectives_nav1}
			<span class="sbeffectives_breadcrumb_name"><a href="{seo url="index.php?p=effectives" rewrite="effectives"}">{$smarty.const._CMS_EFFECTIVES_S}</a></span>
		{else}
			<span class="sbeffectives_breadcrumb_name">{$smarty.const._CMS_EFFECTIVES_S}</span>
		{/if}
		    
		{if $sbeffectives_nav1 AND !$sbeffectives_nav2}
			&nbsp;&raquo;&nbsp;<span class="sbeffectives_breadcrumb_name">{$sbeffectives_nav1}</span>
		{elseif $sbeffectives_nav1 AND $sbeffectives_nav2}
			&nbsp;&raquo;&nbsp;<span class="sbeffectives_breadcrumb_name"><a href="{seo url="index.php?p=effectives&op=category&id={$sbeffectives_item_cat_id}" rewrite="effectives/category/{$sbeffectives_item_cat_id}/{$sbeffectives_cat_title|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"|strip_tags|@sbRewriteString|@strtolower}"}">{$sbeffectives_nav1}</a></span>
		{/if}
		    
		{if $sbeffectives_nav2}
			&nbsp;&raquo;&nbsp;<span class="sbeffectives_breadcrumb_name">{$sbeffectives_nav2}</span>
		{/if}
	</div>

{/if}

{if $sbeffectives_options.title_h1}
	<h1>
		<a href="{seo url="index.php?p=effectives" rewrite="effectives"}">{$smarty.const._CMS_EFFECTIVES_S}</a>
	</h1>
{/if}
	
{if $sbeffectives_options.title_h2}
	{if $sbeffectives_item_cat_title}
		<h2>
			<a href="{seo url="index.php?p=effectives&op=category&id={$sbeffectives_item_cat_id}" rewrite="effectives/category/{$sbeffectives_item_cat_id}/{$sbeffectives_item_cat_title|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"|strip_tags|@sbRewriteString|@strtolower}"}">
				{$sbeffectives_item_cat_title} {if $sbeffectives_item_cat_subtitle}- {$sbeffectives_item_cat_subtitle}{/if}
			</a>
		</h2>
	{elseif $sbeffectives_cat_title}
		<h2>
			{$sbeffectives_cat_title} {if $sbeffectives_item_cat_subtitle}- {$sbeffectives_item_cat_subtitle}{/if}
		</h2>
	{/if}
{/if}

<div class="sbeffectives-clear-both"> </div>
