{* --- NEWS display a specific category --- *}

{include file='news/tpls/news_header.tpl'}

{include file='news/tpls/news_breadcrumb.tpl'}

{* --- Sous-catégories de la catégorie affichée (rien si aucune) --- *}
{if $sbnews_subcats}
	<div class="sbnews-subcats">
		<p class="sbnews-subcats-title">{$smarty.const._CMS_NEWS_SUBCATEGORIES}</p>
		<ul>
			{foreach from=$sbnews_subcats item=subcat}
				{assign var="subcatname" value=$subcat.title|unescape:"htmlall"|sbDisplayLang:"`$smarty.session.lang`"|strip_tags}
				<li><a href='{seo url="index.php?p=news&op=category&id={$subcat.id}" rewrite="news/category/{$subcat.id}/{$subcatname|sbRewriteString|strtolower}"}'>{$subcatname}</a></li>
			{/foreach}
		</ul>
	</div>
{/if}

{if $sbnews_module_show == 'masonry'}
	<link rel="stylesheet" href="{$smarty.const.SB_MODULES_URL}{$smarty.const.MODULEFILE}/inc/style_category_masonry.css">
	<style>
		.sbnews {
			max-width: {$sbnews_module_show_masonry|intval|default:"500"}px;
			width: 100%;
		}
	</style>
	<script src="{$smarty.const.SB_MODULES_URL}{$smarty.const.MODULEFILE}/inc/masonry.pkgd.min.js"></script>
{elseif $sbnews_module_show == 'float'}
	{assign var="no_masonry" value="true"}
	<link rel="stylesheet" href="{$smarty.const.SB_MODULES_URL}{$smarty.const.MODULEFILE}/inc/style_category_float.css">
{elseif $sbnews_module_show == 'masonrycss'}
	<link rel="stylesheet" href="{$smarty.const.SB_MODULES_URL}{$smarty.const.MODULEFILE}/inc/style_category_masonrycss.css">
{else}
	{assign var="no_masonry" value="true"}
	<link rel="stylesheet" href="{$smarty.const.SB_MODULES_URL}{$smarty.const.MODULEFILE}/inc/style_category_normal.css">
{/if}

{if $sbnews_module_show == 'masonry'}
	<div id="sbnews" class="sbnews-layout-masonry" data-masonry='{ "itemSelector": ".sbnews" }'>
{else}
	{* Classe de la disposition choisie : un thème peut cibler chacune *}
	<div id="sbnews" class="sbnews-layout-{$sbnews_module_show|default:'normal'}">
{/if}

{foreach from=$all item=news}
	{* Check if TPL_LIST custom *}
	{if $sbnews_cat_tpl_list != ''}
	
		{* Show TPL LIST template *}
		{eval var=$sbnews_cat_tpl_list}
		
	{else}
		
		{* Show List news DEFAULT *}
		<div class="sbnews {cycle values="even,odd"}">

			<div class="sbnews-div-l">
				<a class="" href="{seo url="index.php?p=news&op=article&id={$news.id}" rewrite="news/article/{$news.id}/{$news.title|unescape:"htmlall"|sbDisplayLang:"`$smarty.session.lang`"|strip_tags|sbRewriteString|strtolower}"}">
					<img src="{if $news.image}{$smarty.const.SB_URL}thumb.php?src={$smarty.const._AM_MEDIAS_URL}{$news.image}&size=480x&crop=1{else}{$smarty.const.SB_MODULES_URL}{$smarty.const.MODULEFILE}/images/news-img.jpg{/if}" alt="{$news.title|unescape:"htmlall"|sbDisplayLang:"`$smarty.session.lang`"}" style="width: 100%;">
				</a>
			</div>
		
			<div class="sbnews-div-r">
				<h3>
					{$news.title|unescape:"htmlall"|sbDisplayLang:"`$smarty.session.lang`"}
				</h3>
				{if $news.catname}
					<p class="sbnews-category">
						{assign var=catid value="|"|explode:$news.catid} 
						<a href='{seo url="index.php?p=news&op=category&id={$news.catid}" rewrite="news/category/{$catid[0]}/{$news.catname|unescape:"htmlall"|sbDisplayLang:"`$smarty.session.lang`"|strip_tags|sbRewriteString|strtolower}"}'>{$news.catname|unescape:"htmlall"|sbDisplayLang:"`$smarty.session.lang`"}</a>
					</p>
				{/if}
				<p class="sbnews-date">
					{$news.date|sbConvertDate:"FR"}
				</p>
				<p class="sbnews-p">
					{if $no_masonry == "true"}
						{$news.desc_short|unescape:"htmlall"|sbDisplayLang:"`$smarty.session.lang`"}
					{else}
						{$news.desc_short|unescape:"htmlall"|sbDisplayLang:"`$smarty.session.lang`"|truncate:60:".."}
					{/if}
				</p>
				<span class="sbnews-link-item">
					<a href="{seo url="index.php?p=news&op=article&id={$news.id}" rewrite="news/article/{$news.id}/{$news.title|unescape:"htmlall"|sbDisplayLang:"`$smarty.session.lang`"|strip_tags|sbRewriteString|strtolower}"}">{$smarty.const._CMS_NEWS_READ_ITEM}</a>
				</span>
			</div>

			<div class="sbnews-clear-both"> </div>

		</div>
		
	{/if}

{foreachelse}

	{$smarty.const._CMS_NEWS_NONEWS}

{/foreach}

</div> {* --- End div sbnews --- *}

	{if $all}
		{include file='news/tpls/news_pagination.tpl'}
	{/if}
