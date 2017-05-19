

			<div class="span12">
				<h2>{$sb_pages_title}</h2>
				<img class="error-404-image group" src="{$smarty.const.SB_THEME_URL}img/features/404.png" title="Error 404" alt="404" />
				<div class="error-404-text group">
					<p class="lead">
						{$smarty.const._CMS_GLOBAL_404|@sprintf:"{$smarty.const.SB_URL}"}
					</p>
					
					<div class="input-append">
						<form method="get" id="searchform" action="#">
							<input id="appendedInputButton" size="16" placeholder="Recherche sur {$sb_site_title}" type="text">
							<button class="btn" type="button"><i class="icon-search"></i></button>
						</form>
					</div>
					
				</div>
			</div> <!-- End span12 -->