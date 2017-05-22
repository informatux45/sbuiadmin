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
		
	{* -------- INDEX-CONTACT -------- *}
	{elseif $theme_view == 'index-contact'}
		{* --------------------------------- *}
		{* --------- Page Contact ---------- *}
		{* --------------------------------- *}
		{include file="inc/index.tpl" sidebar="contact"}
		
	{elseif $theme_view == '404'}
		{* ----------------------------- *}
		{* --------- Page 404 ---------- *}
		{* ----------------------------- *}
		{include file="inc/page-404.tpl"}

	{/if}
	
{include file='footer.tpl'}