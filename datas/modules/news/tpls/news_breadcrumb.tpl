{* --- News Breadcrumb --- *}

{if $sbnews_options.breadcrumb}

	<style>
		@import url({$smarty.const.SB_URL}datas/modules/news/inc/style_breadcrumb.css);
	</style>

	<div class="sbnews_breadcrumb_header">
		
		{if $sbnews_nav1}
			<span class="sbnews_breadcrumb_name"><a href="{seo url="index.php?p=news" rewrite="news"}">{$smarty.const._CMS_NEWS_NEWS}</a></span>
		{else}
			<span class="sbnews_breadcrumb_name">{$smarty.const._CMS_NEWS_NEWS}</span>
		{/if}
		    
		{if $sbnews_nav1 AND !$sbnews_nav2}
			&nbsp;&raquo;&nbsp;<span class="sbnews_breadcrumb_name">{$sbnews_nav1}</span>
		{elseif $sbnews_nav1 AND $sbnews_nav2}
			&nbsp;&raquo;&nbsp;<span class="sbnews_breadcrumb_name"><a href="{seo url="index.php?p=news&op=category&id={$sbnews_item_cat_id}" rewrite="news/category/{$sbnews_item_cat_id}/{$sbnews_item_cat_title|@sbDisplayLang:"`$smarty.session.lang`"|strip_tags|@sbRewriteString|unescape:"htmlall"|@strtolower}"}">{$sbnews_nav1}</a></span>
		{/if}
		    
		{if $sbnews_nav2}
			&nbsp;&raquo;&nbsp;<span class="sbnews_breadcrumb_name">{$sbnews_nav2}</span>
		{/if}
		
		<!-- ===== BREADCRUMB (JSON) ===== -->
		<script type="application/ld+json">
			{literal}{{/literal}
				"@context": "http://schema.org",
				"@type": "BreadcrumbList",
				"itemListElement":
					[{literal}{{/literal}
						"@type": "ListItem",
						"position": 1,
						"item": {literal}{{/literal}
							"@id": "{seo url="index.php?p=news" rewrite="news"}",
							"name": "{$smarty.const._CMS_NEWS_NEWS}"
						{literal}}{/literal}
					{literal}}{/literal},
					{if $sbnews_nav1 AND !$sbnews_nav2}
						{literal}{{/literal}
							"@type": "ListItem",
							"position": 2,
							"item": {literal}{{/literal}
								"@id": "",
								"name": "{$sbnews_nav1}"
							{literal}}{/literal}
						{literal}}{/literal},
					{elseif $sbnews_nav1 AND $sbnews_nav2}
						{literal}{{/literal}
							"@type": "ListItem",
							"position": 2,
							"item": {literal}{{/literal}
								"@id": "{seo url="index.php?p=news&op=category&id={$sbnews_item_cat_id}" rewrite="news/category/{$sbnews_item_cat_id}/{$sbnews_item_cat_title|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"|strip_tags|@sbRewriteString|@strtolower}"}",
								"name": "{$sbnews_nav1}"
							{literal}}{/literal}
						{literal}}{/literal},
					{/if}
					{if $sbnews_nav2}
						{literal}{{/literal}
							"@type": "ListItem",
							"position": 3,
							"item": {literal}{{/literal}
								"@id": "",
								"name": "{$sbnews_nav2}"
							{literal}}{/literal}
						{literal}}{/literal}
					{/if}
				]
			{literal}}{/literal}
		</script>
		
	</div>

{/if}

{if $sbnews_options.title_h1}
	<h1>
		<a href="{seo url="index.php?p=news" rewrite="news"}">{$smarty.const._CMS_NEWS_NEWS}</a>
	</h1>
{/if}
	
{if $sbnews_options.title_h2}
	{if $sbnews_item_cat_title}
		<h2>
			<a href="{seo url="index.php?p=news&op=category&id={$sbnews_item_cat_id}" rewrite="news/category/{$sbnews_item_cat_id}/{$sbnews_item_cat_title|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"|strip_tags|@sbRewriteString|@strtolower}"}">
				{$sbnews_item_cat_title}
			</a>
		</h2>
	{elseif $sbnews_cat_title}
		<h2>
			{$sbnews_cat_title}
		</h2>
	{/if}
{/if}

<div class="sbnews-clear-both"> </div>
