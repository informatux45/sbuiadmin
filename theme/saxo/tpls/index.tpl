{* ========================================== *}
{* Template THEME VIEW :                      *}
{* ------------------------------------------ *}
{* - index without title (Default FULL WIDTH) *}
{* - index with Title (FULL WIDTH)            *}
{* - contact                                  *}
{* - sidebar-left                             *}
{* - sidebar-right                            *}
{* ========================================== *}

{include file='header.tpl'}
{include file='navigation.tpl'}

	{* -------- Default : INDEX -------- *}
	{if !$theme_view || $theme_view == 'index'}
		{include file="inc/index.tpl" sidebar="index"}
	{/if}

	{* -------- INDEX / TITLE -------- *}
	{if $theme_view == 'index-title'}
		{include file="inc/index.tpl" sidebar="title"}
	{/if}

	{* -------- INDEX-LEFT-SIDEBAR -------- *}
	{if $theme_view == 'index-left-sidebar'}
		{include file="inc/index.tpl" sidebar="left"}
	{/if}

	{* -------- INDEX-RIGHT-SIDEBAR -------- *}
	{if $theme_view == 'index-right-sidebar'}
		{include file="inc/index.tpl" sidebar="right"}
	{/if}

	{* -------- INDEX-CONTACT -------- *}
	{if $theme_view == 'index-contact'}
		{include file="inc/index.tpl" sidebar="contact"}
	{/if}

	{* -------- PAGE 404 -------- *}
	{if $theme_view == 'page-404'}
		{include file="inc/page-404.tpl"}
	{/if}
	
{include file='footer.tpl'}