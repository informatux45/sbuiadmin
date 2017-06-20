{* ========================================== *}
{* Template THEME VIEW :                      *}
{* ------------------------------------------ *}
{* - index without title (Default FULL WIDTH) *}
{* - contact                                  *}
{* ========================================== *}

{include file='header.tpl'}
{include file='navigation.tpl'}

	{* -------- Default : INDEX -------- *}
	{if !$theme_view || $theme_view == 'index'}
		{include file="inc/index.tpl" sidebar="index"}
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