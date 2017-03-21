{* --- GRADUATES Breadcrumb --- *}

{if $sbgraduates_options.breadcrumb}

	<style>
		@import url({$smarty.const.SB_URL}datas/modules/graduates/inc/style_breadcrumb.css);
	</style>

	<div class="sbgraduates_breadcrumb_header">
		
		{if $sbgraduates_nav1}
			<span class="sbgraduates_breadcrumb_name"><a href="{seo url="index.php?p=graduates" rewrite="graduates"}">{$smarty.const._CMS_GRADUATES_S}</a></span>
		{else}
			<span class="sbgraduates_breadcrumb_name">{$smarty.const._CMS_GRADUATES_S}</span>
		{/if}
		    
		{if $sbgraduates_nav1 AND !$sbgraduates_nav2}
			&nbsp;&raquo;&nbsp;<span class="sbgraduates_breadcrumb_name">{$sbgraduates_nav1}</span>
		{elseif $sbgraduates_nav1 AND $sbgraduates_nav2}
			&nbsp;&raquo;&nbsp;<span class="sbgraduates_breadcrumb_name"><a href="{seo url="index.php?p=graduates&op=category&id={$sbgraduates_item_cat_id}" rewrite="graduates/category/{$sbgraduates_item_cat_id}/{$sbgraduates_cat_title|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"|strip_tags|@sbRewriteString|@strtolower}"}">{$sbgraduates_nav1}</a></span>
		{/if}
		    
		{if $sbgraduates_nav2}
			&nbsp;&raquo;&nbsp;<span class="sbgraduates_breadcrumb_name">{$sbgraduates_nav2}</span>
		{/if}
	</div>

{/if}

{if $sbgraduates_options.title_h1}
	<h1>
		<a href="{seo url="index.php?p=graduates" rewrite="graduates"}">{$smarty.const._CMS_GRADUATES_S}</a>
	</h1>
{/if}
	
{if $sbgraduates_options.title_h2}
	{if $sbgraduates_item_cat_title}
		<h2>
			<a href="{seo url="index.php?p=graduates&op=category&id={$sbgraduates_item_cat_id}" rewrite="graduates/category/{$sbgraduates_item_cat_id}/{$sbgraduates_item_cat_title|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"|strip_tags|@sbRewriteString|@strtolower}"}">
				{$sbgraduates_item_cat_title} {if $sbgraduates_item_cat_subtitle}- {$sbgraduates_item_cat_subtitle}{/if}
			</a>
		</h2>
	{elseif $sbgraduates_cat_title}
		<h2>
			{$sbgraduates_cat_title} {if $sbgraduates_item_cat_subtitle}- {$sbgraduates_item_cat_subtitle}{/if}
		</h2>
	{/if}
{/if}

<div class="sbgraduates-clear-both"> </div>
