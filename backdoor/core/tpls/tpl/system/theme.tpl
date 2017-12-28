{* -------------- *}
{* --- SYSTEM --- *}	
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page}
	{* ---------------- End Headers --------------- *}
			
		{* ------------------------------------------------ *}
		{*       Write your own code after this line        *}
		{* ------------------------------------------------ *}
		
		{include file='system/settings_bar.tpl'}
		
		<div class="row">
			{section name=theme loop=$sb_themes}
				<div class="col-lg-6">
					<div class="well theme_block{if $sb_themes[theme] == $sb_theme_name} theme_block_active{/if}">
						<p>
							<img class="theme_img" src="thumb.php?src={$smarty.const.SB_URL}theme/{$sb_themes[theme]}/screenshot-index.jpg&size=200x180&ignore=1&crop=0" title="Thème {$sb_themes[theme]}" />
							<h4>{$sb_themes[theme]|capitalize}{if $sb_themes[theme] == $sb_theme_name} (Activé){/if}</h4>
							{* --- Description from header file config --- *}
							<div class="theme_description">
								{assign var="sb_theme_config_file" value="{$smarty.const.SB_PATH}theme/{$sb_themes[theme]}/config.php"}
								Description: <i>{$sb_theme_config_file|@sbGetFileDocData:"Description"}</i>
								<br>
								Version: <i>{$sb_theme_config_file|@sbGetFileDocData:"Version"}</i>
								<br>
								Auteur: <i>{if $sb_theme_config_file|@sbGetFileDocData:"Author_URI"}<a target="_blank" href="{$sb_theme_config_file|@sbGetFileDocData:"Author_URI"}">{$sb_theme_config_file|@sbGetFileDocData:"Author"}</a>{else}{$sb_theme_config_file|@sbGetFileDocData:"Author"}{/if}</i>
							</div>
							{if $sb_themes[theme] != $sb_theme_name}
							<div class="theme_activate">
								<a href="{$formAction}&th={$sb_themes[theme]}" class="btn btn-default btn-submit theme_selection">Activer ce thème</a>
							</div>
							{/if}
						</p>
					</div>
				</div>
			{/section}
		</div>

		<!-- ------------------------------------------------------------ -->
		<!-- Page-Level Scripts - Use this space this write your own code -->
		<!-- ------------------------------------------------------------ -->
		<script>
		$(document).ready(function() {
			// Your own code

		});
		</script>

	{include file='sb_footer.tpl'}