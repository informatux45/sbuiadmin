{* --- Show Pages Infos --- *}

	{$sb_pages_content}
	
	{* --- Gabarit annexe : pas ici si le thème l'affiche lui-même ($theme['various_in_theme'] dans son config.php) --- *}
	{if $sb_pages_various && !$sb_theme_various}
		{include file="{$smarty.const.SB_VARIOUS_DIR}{$sb_pages_various}"}
	{/if}