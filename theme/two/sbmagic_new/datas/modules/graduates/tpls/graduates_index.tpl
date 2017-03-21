{* --- GRADUATES Display All Categories --- *}

{include file='graduates/tpls/graduates_header.tpl'}

{include file='graduates/tpls/graduates_breadcrumb.tpl'}

{foreach from=$categories item=cat}

	<div data-content="{$cat.title|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}" class="sbgraduates-allcats" onclick="javascript:window.location='{seo url="index.php?p=graduates&op=category&id={$cat.id}" rewrite="graduates/category/{$cat.id}/{$cat.title|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"|strip_tags|@sbRewriteString|@strtolower}"}'" style="{if $cat.photo}background: url({$smarty.const._AM_MEDIAS_URL}/{$cat.photo}) no-repeat center center;{/if}"></div>

{foreachelse}

	{$smarty.const._CMS_GRADUATES_NOCATEGORIES}

{/foreach}