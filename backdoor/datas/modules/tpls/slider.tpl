{* -------------- *}
{* --- MODULE --- *}	
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page page='false'}
	{* ---------------- End Headers --------------- *}
			
			{* ------------------------------------------------ *}
			{*       Write your own code after this line        *}
			{* ------------------------------------------------ *}
			
			{include file='slider_bar.tpl'}

            <div class="row">
				
				{if isset($all) && !isset($smarty.get.a)}
                <div class="col-lg-12">
                    <div class="well">
						<img src="img/screenshot-slider.jpg" style="width: 675px; max-width: 100%;" alt="" />
                    </div>
                </div>
                <!-- /.col-lg-12 -->
				{/if}
				
				{if isset($all) && !isset($smarty.get.a)}
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span class="fa fa-sliders fa-fw"></span> <strong>{if $all}Gestion de vos slider{else}{$legend_add_edit}{/if}</strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="data-toolbar">
								<div class="data-toolbar-left">
									<div class="input-icon" style="flex:1;max-width:320px">
										<span class="ico"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg></span>
										<input class="input" type="search" placeholder="Rechercher..." data-datatable-search="dataTables-sliders">
									</div>
								</div>
							</div>
							<div style="overflow-x:auto">
                                <table class="data-table" id="dataTables-sliders" data-datatable>
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
										{if isset($allslider)}
											{foreach from=$allslider item=slider}
												<tr class="data-row">
													<td>{$slider.title|unescape:"htmlall"|@sbDisplayLang}</td>
													<td>{$slider.mode|unescape:"htmlall"}</td>
													<td>{$slider.cpt_img|default:0}</td>
													<td>[CS id={$slider.id} name=sbslider]</td>
													<td>
														<div class="data-cell-actions">
															<span class="btn--icon" style="color:{if $slider.active}var(--success){else}var(--danger){/if}" title="Statut {if $slider.active}visible{else}non visible{/if}">
																<svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
															</span>
															<a class="btn--icon" href="{$module_url}&a=photo&sid={$slider.id}" title="Toutes les photos">
																<svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
															</a>
															<a class="btn--icon" href="{$module_url}&a=sort&sid={$slider.id}" title="Trier les photos">
																<svg viewBox="0 0 24 24"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
															</a>
															<a class="btn--icon" href="{$module_url}&a=edit&id={$slider.id}" title="Modifier">
																<svg viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 1 1 3 3L7 19l-4 1 1-4z"/></svg>
															</a>
															<a class="btn--icon jConfirm" href="{$module_url}&a=del&id={$slider.id}" title="Supprimer">
																<svg viewBox="0 0 24 24"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6h14z"/></svg>
															</a>
														</div>
													</td>
												</tr>
											{/foreach}
										{/if}
                                    </tbody>
                                </table>
                            </div>
							<div class="data-foot" data-datatable-foot="dataTables-sliders">
								<div class="data-foot-info" data-foot-info></div>
								<div class="pager"></div>
							</div>

                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
				{/if}
				
				{if isset($allphoto)}
				
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span class="fa fa-sliders fa-fw"></span> <strong>{if $allphoto}Gestion de vos slider{else}{$legend_add_edit}{/if}</strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="data-toolbar">
								<div class="data-toolbar-left">
									<div class="input-icon" style="flex:1;max-width:320px">
										<span class="ico"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg></span>
										<input class="input" type="search" placeholder="Rechercher..." data-datatable-search="dataTables-photos">
									</div>
								</div>
							</div>
							<div style="overflow-x:auto">
                                <table class="data-table" id="dataTables-photos" data-datatable>
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
										{foreach from=$allphoto item=photo}
											<tr class="data-row">
												<td>{$photo.sort}</td>
												<td>{$photo.photo}</td>
												<td>{$photo.title|unescape:"htmlall"|@sbDisplayLang}</td>
												<td>
													<div class="data-cell-actions">
														<span class="btn--icon" style="color:{if $photo.active}var(--success){else}var(--danger){/if}" title="Statut {if $photo.active}visible{else}non visible{/if}">
															<svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
														</span>
														<a class="btn--icon" href="{$module_url}&a=photoedit&id={$photo.id}&sid={$sid}" title="Modifier">
															<svg viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 1 1 3 3L7 19l-4 1 1-4z"/></svg>
														</a>
														<a class="btn--icon jConfirm" href="{$module_url}&a=delphoto&sid={$sid}&id={$photo.id}" title="Supprimer">
															<svg viewBox="0 0 24 24"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6h14z"/></svg>
														</a>
													</div>
												</td>
											</tr>
										{/foreach}
                                    </tbody>
                                </table>
                            </div>
							<div class="data-foot" data-datatable-foot="dataTables-photos">
								<div class="data-foot-info" data-foot-info></div>
								<div class="pager"></div>
							</div>

                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
				{/if}
				
				{if (!isset($all) || !isset($allphoto)) && (isset($smarty.get.a) && $smarty.get.a != 'photo') }
					{if $smarty.get.a == 'edit' || $smarty.get.a == 'add'}
						<style>
						/* --- Icons 2 (form) --- */
						.input-group-addon, .input-group-btn { width: auto !important; }
						</style>
					{/if}
					<div class="col-lg-8">
						<div class="panel panel-default">
							<div class="panel-heading">
								<span class="fa fa-sliders fa-fw"></span> <strong>{$legend_add_edit}</strong>
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
						<div class="panel panel-primary">
							<div class="panel-heading">
								<span class="fa fa-exclamation-circle fa-fw"></span> <strong>AIDE</strong>
							</div>
							<!-- /.panel-heading -->
							<div class="panel-body">
								<img src="img/screenshot-slider.jpg" style="width: 100%; max-width: 675px;" alt="" />
							</div>
						</div>
				{/if}
				
            </div>
            <!-- /.row -->
	
		<!-- ------------------------------------------------------------ -->
		<!-- Page-Level Scripts - Use this space this write your own code -->
		<!-- ------------------------------------------------------------ -->
		<script>
		$(document).ready(function() {
			{if $sort}
				$( "#sortable" ).sortable({
					axis: "y",
					placeholder: "ui-state-highlight",
					{*update: function() { 
						var order = $('#sortable').sortable('serialize'); 
						$.post('sortable.php',order);
					}*}
				});
				$( "#sortable" ).disableSelection();
			{/if}
		});
		</script>
			
	{include file='sb_footer.tpl' page='false' pagef='false'}

