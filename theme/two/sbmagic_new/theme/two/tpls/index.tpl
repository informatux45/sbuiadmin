{* ===================================== *}
{* Template THEME VIEW :                 *}
{* - index (Default FULL WIDTH)          *}
{* - 404                                 *}
{* ===================================== *}

{include file='header.tpl'}

{include file='navigation.tpl'}

	{if !$theme_view || $theme_view == 'index'}
		{* --------------------------------- *}
		{* -------- Default : INDEX -------- *}
		{* --------------------------------- *}
		{include file="inc/index.tpl"}
		
	{elseif $theme_view == '404'}
		{* --------------------------------- *}
		{* --------- Page Contact ---------- *}
		{* --------------------------------- *}
		{include file="inc/page-404.tpl"}

	{/if}
	
{include file='footer.tpl'}