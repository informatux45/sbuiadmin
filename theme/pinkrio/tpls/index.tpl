{* ===================================== *}
{* Template THEME VIEW :                 *}
{* - index (Default FULL WIDTH)          *}
{* - contact                             *}
{* - sidebar-left                        *}
{* - sidebar-right                       *}
{* ===================================== *}

	{* -------- Default : INDEX -------- *}
	{if !$theme_view || $theme_view == 'index'}
		{include file='header.tpl' viewtype='stretched'}		
		{include file='navigation.tpl'}
		{include file="inc/index.tpl" sidebar="no"}
	{/if}

	{* -------- INDEX-LEFT-SIDEBAR -------- *}
	{if $theme_view == 'index-left-sidebar'}
		{include file='header.tpl' viewtype='stretched'}		
		{include file='navigation.tpl'}
		{include file="inc/index.tpl" sidebar="left"}
	{/if}

	{* -------- INDEX-RIGHT-SIDEBAR -------- *}
	{if $theme_view == 'index-right-sidebar'}
		{include file='header.tpl' viewtype='stretched'}		
		{include file='navigation.tpl'}
		{include file="inc/index.tpl" sidebar="right"}
	{/if}

	{* -------- INDEX-CONTACT -------- *}
	{if $theme_view == 'index-contact'}
		{include file='header.tpl' viewtype='stretched'}		
		{include file='navigation.tpl'}
		{include file="inc/index-contact.tpl" sidebar="no"}
	{/if}

	{* -------- BOXED-WITH-SHADOW-FULL-WIDTH -------- *}
	{if $theme_view == 'boxed-full-width'}
		{include file='header.tpl' viewtype='boxed'}		
		{include file='navigation.tpl'}
		{include file="inc/index.tpl" sidebar="no"}
	{/if}

	{* -------- BOXED-WITH-SHADOW-LEFT-SIDEBAR -------- *}
	{if $theme_view == 'boxed-left-sidebar'}
		{include file='header.tpl' viewtype='boxed'}		
		{include file='navigation.tpl'}
		{include file="inc/index.tpl" sidebar="left"}
	{/if}

	{* -------- BOXED-WITH-SHADOW-RIGHT-SIDEBAR -------- *}
	{if $theme_view == 'boxed-right-sidebar'}
		{include file='header.tpl' viewtype='boxed'}		
		{include file='navigation.tpl'}
		{include file="inc/index.tpl" sidebar="right"}
	{/if}
	
{include file='footer.tpl'}