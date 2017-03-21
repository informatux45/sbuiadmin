{* --- Show Blocs infos --- *}

{insert name="sbGetContentCmsBlocks" pid="$page_id" lang="`$smarty.session.lang`" assign=blocks}

{foreach from=$blocks item=block}

	<div id="bloc_{$block.id}" class="">

		{if $block.title}
			<h3>{$block.title|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}</h3>
		{/if}

		{$block.content|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"|@sbGetShortcode}

	</div> <!-- bloc_{$block.id} -->

{/foreach}