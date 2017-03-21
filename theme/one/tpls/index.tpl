{* ===================================== *}
{* Template THEME VIEW :                 *}
{* - index (Default FULL WIDTH)          *}
{* - contact                             *}
{* - sidebar-left                        *}
{* - sidebar-right                       *}
{* ===================================== *}

{include file='header.tpl'}

{include file='navigation.tpl'}

	{if !$theme_view || $theme_view == 'index'}
		{* --------------------------------- *}
		{* -------- Default : INDEX -------- *}
		{* --------------------------------- *}
		{include file="inc/index.tpl"}
		
	{elseif $theme_view == 'contact'}
		{* --------------------------------- *}
		{* --------- Page Contact ---------- *}
		{* --------------------------------- *}
		{include file="inc/contact.tpl"}
		
	{elseif $theme_view == 'sidebar-left'}
		{* --------------------------------- *}
		{* ----- Page with Sidebar LEFT ---- *}
		{* --------------------------------- *}
		{include file="inc/sidebar-left.tpl"}

	{elseif $theme_view == 'sidebar-right'}
		{* --------------------------------- *}
		{* ---- Page with Sidebar RIGHT ---- *}
		{* --------------------------------- *}
		{include file="inc/sidebar-right.tpl"}

	{/if}
	
{include file='footer.tpl'}