{* --- Show CONTACT Page Blocs infos --- *}

{if $smarty.get.p != 'pages'}

	{insert name="sbGetContentCmsBlocks" pid="`$smarty.const.MODULENAME|lower`" lang="`$smarty.session.lang`" mod="module" assign=blocks}
	
	{foreach from=$blocks item=block}
	
		<div id="bloc_{$block.id}" class="">
	
			{if $block.title}
				<h3>{$block.title|unescape:"htmlall"|sbDisplayLang:"`$smarty.session.lang`"}</h3>
			{/if}
	
			{$block.content|unescape:"htmlall"|sbDisplayLang:"`$smarty.session.lang`"|sbGetShortcode}

			{if $block.various_view != ""}
				{include file="{$smarty.const.SB_VARIOUS_DIR}{$block.various_view}"}
			{/if}
	
		</div> <!-- bloc_{$block.id} -->
	
	{/foreach}

{/if}