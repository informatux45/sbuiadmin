{* -------------- *}
{* --- MODULE --- *}	
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page}
	{* ---------------- End Headers --------------- *}
			
			{* ------------------------------------------------ *}
			{*       Write your own code after this line        *}
			{* ------------------------------------------------ *}
			
			{include file='pages_bar.tpl'}

            <div class="row">
				{if $all}
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <span class="fa fa-copy fa-fw"></span> <strong>{if $all}Gestion de vos pages{else}{$legend_add_edit}{/if}</strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-pages">
                                    <thead>
                                        <tr>
                                            {foreach from=$sb_table_header item=header}
												<th>
													{$header}
												</th>
											{/foreach}
                                        </tr>
                                    </thead>
                                    <tbody>
										{if $allpage}
											{foreach from=$allpage item=page}
												<tr class="{cycle values="odd,even"} gradeX">
													<td>{$page.title|unescape:"htmlall"|@sbDisplayLang}</td>
													<td>{$page.theme_view|unescape:"htmlall"}</td>
													<td>{$page.module_view|unescape:"htmlall"}</td>
													<td><a href="{$seo_url_cms}{$page.seo_url|unescape:"htmlall"}" target="_blank">{$page.seo_url|unescape:"htmlall"}</a></td>
													<td>
														<span class="glyphicon glyphicon-eye-open {if $page.active}green{else}red{/if}" title="Statut {if $page.active}visible{else}non visible{/if}"></span>
														&nbsp;
														<a class="glyphicon glyphicon-cog" href="{$module_url}&a=edit{if $page.url_custom != ''}custom{/if}&id={$page.id}" title="Modifier"></a>
														{if $page.seo_url != '' || $page.url_custom != ''}
														&nbsp;
														<a class="glyphicon glyphicon-remove red jConfirm" href="{$module_url}&a=del&id={$page.id}" title="Supprimer"></a>
														{/if}
													</td>
												</tr>										
											{/foreach}
										{/if}
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->

                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
				
				{else}
				
                <div class="col-lg-{if $smarty.get.a == 'addcustom' OR $smarty.get.a == 'editcustom'}12{else}8{/if}">
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

				{if $smarty.get.a != 'addcustom' AND $smarty.get.a != 'editcustom'}
					<div class="col-lg-4">
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
					</div>
					<!-- /.col-lg-4 -->

					{if $show_headpage}
						{include file='shared/shared-slider-4col.tpl'}
					{/if}

				{/if}

				{/if}
            </div>
            <!-- /.row -->
	
		<!-- ------------------------------------------------------------ -->
		<!-- Page-Level Scripts - Use this space this write your own code -->
		<!-- ------------------------------------------------------------ -->
		<script>
		$(document).ready(function() {
			$('#dataTables-pages').DataTable({
					responsive: true,
					"lengthMenu": [25, 50, 75, 100, 150]
			});
			$("#theme_view").change(function () {
				var str = $( "select#theme_view option:selected" ).val();
				var new_theme_view = '{$smarty.const.SB_THEME_URL|replace:"`$smarty.const.SBADMIN`":''}screenshot-'+str+'.jpg';
				if (str != '') {
					$('img#img_theme_view').attr('src', new_theme_view);
				} else {
					$('img#img_theme_view').attr('src', '{$smarty.const._AM_SITE_IMG_URL}theme-noview.jpg');
				}
			}).change();
		});
		</script>
			
	{include file='sb_footer.tpl'}

