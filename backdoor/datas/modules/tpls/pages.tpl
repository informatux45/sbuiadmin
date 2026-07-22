{* -------------- *}
{* --- MODULE --- *}	
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page page='false'}
	{* ---------------- End Headers --------------- *}
			
			{* ------------------------------------------------ *}
			{*       Write your own code after this line        *}
			{* ------------------------------------------------ *}
			
			{include file='pages_bar.tpl'}

            <div class="row">
				{if $all}
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span class="fa fa-copy fa-fw"></span> <strong>{if $all}Gestion de vos pages{else}{$legend_add_edit}{/if}</strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="data-toolbar">
								<div class="data-toolbar-left">
									<div class="input-icon" style="flex:1;max-width:320px">
										<span class="ico"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg></span>
										<input class="input" type="search" placeholder="Rechercher..." data-datatable-search="dataTables-pages">
									</div>
								</div>
							</div>
							<div style="overflow-x:auto">
                                <table class="data-table" id="dataTables-pages" data-datatable>
                                    <thead>
                                        <tr>
                                            {foreach from=$sb_table_header item=header}
												<th{if $header@last} data-sort="false"{/if}>
													{$header}{if !$header@last} <span class="sort"><svg viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></svg></span>{/if}
												</th>
											{/foreach}
                                        </tr>
                                    </thead>
                                    <tbody>
										{if $allpage}
											{foreach from=$allpage item=page}
												<tr class="data-row">
													<td>{$page.title|unescape:"htmlall"|@sbDisplayLang}</td>
													<td>{$page.theme_view|unescape:"htmlall"}</td>
													<td>{$page.module_view|unescape:"htmlall"}</td>
													<td><a href="{$seo_url_cms}{$page.seo_url|unescape:"htmlall"}" target="_blank">{$page.seo_url|unescape:"htmlall"}</a></td>
													<td>
														<div class="data-cell-actions">
															<span class="btn--icon" style="color:{if $page.active}var(--success){else}var(--danger){/if}" title="Statut {if $page.active}visible{else}non visible{/if}">
																<svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
															</span>
															<a class="btn--icon" href="{$module_url}&a=edit{if $page.url_custom != ''}custom{/if}&id={$page.id}" title="Modifier">
																<svg viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 1 1 3 3L7 19l-4 1 1-4z"/></svg>
															</a>
															{if $page.seo_url != '' || $page.url_custom != ''}
															<a class="btn--icon jConfirm" href="{$module_url}&a=del&id={$page.id}" title="Supprimer">
																<svg viewBox="0 0 24 24"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6h14z"/></svg>
															</a>
															{/if}
														</div>
													</td>
												</tr>
											{/foreach}
										{/if}
                                    </tbody>
                                </table>
                            </div>
							<div class="data-foot" data-datatable-foot="dataTables-pages">
								<div class="data-foot-info" data-foot-info></div>
								<div class="pager"></div>
							</div>

                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
				
				{else}
				
                <div class="col-lg-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span class="fa fa-copy fa-fw"></span> <strong>{if $all}Gestion de vos pages{else}{$legend_add_edit}{/if}</strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
							{* Afficher le formulaire ADD/EDIT *}
							{include_php file='form.php'}
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-8 -->

				<div class="col-lg-4">
					{* ------------------------------------ *}
					{* --- Include Shared Panel Actions --- *}
					{include file='shared/shared-panel-actions.tpl'}
					{* ------------------------------------ *}
					{* ------------------------------------ *}
					{if $smarty.get.a != 'addcustom' AND $smarty.get.a != 'editcustom'}
					<div class="panel panel-default">
						<div class="panel-heading">
							<span class="fa fa-columns fa-fw"></span> <strong>Choix du template (VIEW)</strong>
						</div>
						<!-- /.panel-heading -->
						<div class="panel-body">
							<div class="theme_view">
								<img id="img_theme_view" src="" title="" />
							</div>
						</div>
						<!-- /.panel-body -->
					</div>
					<!-- /.panel -->
					{/if}
				</div>
				<!-- /.col-lg-4 -->

				{if isset($show_headpage)}
					{include file='shared/shared-slider-4col.tpl'}
				{/if}


				{/if}
            </div>
            <!-- /.row -->
	
		<!-- ------------------------------------------------------------ -->
		<!-- Page-Level Scripts - Use this space this write your own code -->
		<!-- ------------------------------------------------------------ -->
		<script>
		$(document).ready(function() {
			$("#theme_view").change(function () {
				var str = $( "select#theme_view option:selected" ).val();
				var new_theme_view = $( "select#theme_view option:selected" ).attr('rel') + 'screenshot-'+str+'.jpg';
				if (str != '') {
					$('img#img_theme_view').attr('src', new_theme_view);
				} else {
					$('img#img_theme_view').attr('src', '{$smarty.const._AM_SITE_IMG_URL}theme-noview.jpg');
				}
			}).change();
		});
		</script>
			
	{include file='sb_footer.tpl' page='false' pagef='false'}

