{* --- EFFECTIVES Display All Categories --- *}

{include file='effectives/tpls/effectives_header.tpl'}

{include file='effectives/tpls/effectives_breadcrumb.tpl'}

{foreach from=$categories item=cat}

	<div data-content="{$cat.title|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}" class="sbeffectives-allcats" onclick="javascript:window.location='{seo url="index.php?p=effectives&op=category&id={$cat.id}" rewrite="effectives/category/{$cat.id}/{$cat.title|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"|strip_tags|@sbRewriteString|@strtolower}"}'" style="{if $cat.photo}background: url({$smarty.const._AM_MEDIAS_URL}/{$cat.photo}) no-repeat center center;{/if}"></div>

{foreachelse}

	{$smarty.const._CMS_EFFECTIVES_NOCATEGORIES}

{/foreach}