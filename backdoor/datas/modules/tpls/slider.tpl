{* -------------- *}
{* --- MODULE --- *}	
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page page='false'}
	{* ---------------- End Headers --------------- *}
			
			{* ------------------------------------------------ *}
			{*       Write your own code after this line        *}
			{* ------------------------------------------------ *}
			
			<section class="hero">
				<div class="hero-text">
					<span class="eyebrow">Slider</span>
					<h1 class="hero-title">Slider</h1>
					<p class="hero-sub">Gérez vos sliders et leurs photos ou vidéos.</p>
				</div>
				<div class="hero-actions">
					<div class="dd-wrap">
						<button class="btn btn--outline-primary" data-dropdown>
							Sliders
							<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
						</button>
						<div class="dd-menu" role="menu" style="min-width:220px">
							<a class="dd-menu-item" href="{$smarty.const._AM_SITE_URL}index.php?p=slider">Tous les sliders</a>
							<div class="dd-divider"></div>
							<a class="dd-menu-item" href="{$smarty.const._AM_SITE_URL}index.php?p=slider&a=add">+1 slider</a>
							<a class="dd-menu-item" href="{$smarty.const._AM_SITE_URL}index.php?p=slider&a=photoadd">+1 photo / vidéo</a>
						</div>
					</div>

					{if isset($smarty.get.a) && $smarty.get.a == 'photo'}
						<button class="btn btn--ghost" type="button" onclick="location.href='index.php?p=slider&a=sort&sid={$sid}'">
							Trier les photos
						</button>
						<button class="btn btn--ghost" type="button" onclick="location.href='index.php?p=slider&a=edit&id={$sid}'">
							Retour aux paramètres du slider
						</button>
					{/if}

					{if isset($smarty.get.a) && $smarty.get.a == 'edit'}
						<button class="btn btn--ghost" type="button" onclick="location.href='index.php?p=slider&a=photo&sid={$smarty.get.id}'">
							Toutes les photos
						</button>
					{/if}

					{if isset($smarty.get.a) && ($smarty.get.a == 'sort' || $smarty.get.a == 'photoedit')}
						<button class="btn btn--ghost" type="button" onclick="location.href='index.php?p=slider&a=photo&sid={$sid}'">
							Retour aux photos
						</button>
						<button class="btn btn--ghost" type="button" onclick="location.href='index.php?p=slider&a=edit&id={$sid}'">
							Retour aux paramètres du slider
						</button>
					{/if}
				</div>
			</section>

            <div class="grid">

				{if isset($all) && !isset($smarty.get.a)}
                <section class="col-12 card">
					<img src="img/screenshot-slider.jpg" style="width: 675px; max-width: 100%;border-radius:8px" alt="" />
                </section>
				{/if}

				{if isset($all) && !isset($smarty.get.a)}
                <section class="col-12 card">
                    <div class="card-head">
						<div class="card-title-wrap">
							<h2 class="card-title">Gestion de vos slider</h2>
						</div>
                    </div>
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
															<a class="btn--icon" data-confirm="Sûr de vouloir supprimer ceci ?" href="{$module_url}&a=del&id={$slider.id}" title="Supprimer">
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

                </section>
				{/if}

				{if isset($allphoto)}

                <section class="col-12 card">
                    <div class="card-head">
						<div class="card-title-wrap">
							<h2 class="card-title">{if $allphoto}Gestion de vos slider{else}{$legend_add_edit}{/if}</h2>
						</div>
                    </div>
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
														<a class="btn--icon" data-confirm="Sûr de vouloir supprimer ceci ?" href="{$module_url}&a=delphoto&sid={$sid}&id={$photo.id}" title="Supprimer">
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

                </section>
				{/if}

				{if (!isset($all) || !isset($allphoto)) && (isset($smarty.get.a) && $smarty.get.a != 'photo') }
					<section class="col-8 card">
						<div class="card-head">
							<div class="card-title-wrap">
								<h2 class="card-title">{$legend_add_edit}</h2>
							</div>
						</div>
							{* Afficher le formulaire ADD/EDIT *}
							{include_php file='form.php'}
					</section>
					<div class="col-4">
						{* ------------------------------------ *}
						{* --- Include Shared Panel Actions --- *}
						{include file='shared/shared-panel-actions.tpl'}
						{* ------------------------------------ *}
						{* ------------------------------------ *}
						<div class="card">
							<div class="card-head">
								<div class="card-title-wrap">
									<h2 class="card-title">Aide</h2>
								</div>
							</div>
							<img src="img/screenshot-slider.jpg" style="width: 100%; max-width: 675px;border-radius:8px" alt="" />
						</div>
						<!-- /.card -->
					</div>
					<!-- /.col-4 -->
				{/if}

            </div>
            <!-- /.grid -->
	
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

