{* --- NEWS Display All Categories --- *}

{include file='news/tpls/news_header.tpl'}

{include file='news/tpls/news_breadcrumb.tpl'}

{foreach from=$categories item=cat}

	<div data-content="{$cat.title|unescape:"htmlall"|sbDisplayLang:"`$smarty.session.lang`"}" class="sbnews-allcats" onclick="javascript:window.location='{seo url="index.php?p=news&op=category&id={$cat.id}" rewrite="news/category/{$cat.id}/{$cat.title|unescape:"htmlall"|sbDisplayLang:"`$smarty.session.lang`"|strip_tags|sbRewriteString|strtolower}"}'" style="{if $cat.photo}background: url({$smarty.const._AM_MEDIAS_URL}/{$cat.photo}) no-repeat center center;{/if}"></div>

{foreachelse}

	{$smarty.const._CMS_NEWS_NOCATEGORIES}

{/foreach}