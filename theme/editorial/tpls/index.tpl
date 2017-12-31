{* ===================================== *}
{* Template THEME VIEW :                 *}
{* - index (Default FULL WIDTH)          *}
{* - page-404                            *}
{* ===================================== *}

{include file='header.tpl'}

	{if !$theme_view || $theme_view == 'index'}
		{* --------------------------------- *}
		{* -------- Default : INDEX -------- *}
		{* --------------------------------- *}
		{include file="inc/index.tpl" sidebar="index"}

	{* -------- INDEX-TITLE -------- *}
	{elseif !$theme_view || $theme_view == 'index-title'}
		{* --------------------------------- *}
		{* -------- Default : INDEX -------- *}
		{* --------------------------------- *}
		{include file="inc/index.tpl" sidebar="index-title"}

	{* -------- INDEX-WITHOUT-TITLE -------- *}
	{elseif !$theme_view || $theme_view == 'index-without-title'}
		{* --------------------------------- *}
		{* -------- Default : INDEX -------- *}
		{* --------------------------------- *}
		{include file="inc/index.tpl" sidebar="index-without-title"}
		
	{* -------- INDEX-CONTACT -------- *}
	{elseif $theme_view == 'index-contact'}
		{* --------------------------------- *}
		{* --------- Page Contact ---------- *}
		{* --------------------------------- *}
		{include file="inc/index.tpl" sidebar="index-contact"}
		
	{elseif $theme_view == 'page-404'}
		{* ----------------------------- *}
		{* --------- Page 404 ---------- *}
		{* ----------------------------- *}
		{include file="inc/page-404.tpl"}

	{/if}
	
	{include file='navigation.tpl'}
	
{include file='footer.tpl'}
