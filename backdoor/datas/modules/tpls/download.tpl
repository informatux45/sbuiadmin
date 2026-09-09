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
					<span class="eyebrow">FICHIERS</span>
					<h1 class="hero-title">Téléchargements</h1>
					<p class="hero-sub">Gérez vos fichiers téléchargeables et leurs boutons.</p>
				</div>
				<div class="hero-actions">
					<div class="dd-wrap">
						<button class="btn btn--outline-primary" data-dropdown>
							Téléchargements
							<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
						</button>
						<div class="dd-menu" role="menu" style="min-width:220px">
							<a class="dd-menu-item" href="{$smarty.const._AM_SITE_URL}index.php?p=download">Tous les téléchargements</a>
							<div class="dd-divider"></div>
							<a class="dd-menu-item" href="{$smarty.const._AM_SITE_URL}index.php?p=download&a=add">+1 téléchargement</a>
						</div>
					</div>
					<button class="btn btn--ghost" type="button" data-toggle="modal" data-target="#sbdownload_shortcodes">
						Shortcodes
					</button>
				</div>
			</section>

			{if isset($all) && (!isset($smarty.get.a) || $smarty.get.a == '' || $smarty.get.a == 'del')}
			<div class="card" style="margin-bottom:20px">
				<div class="card-head">
					<div class="card-title-wrap">
						<span class="eyebrow">Aperçu</span>
						<h2 class="card-title">Infos Téléchargements</h2>
					</div>
				</div>
				<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:18px">
					<div class="stat-cell">
						<div class="stat-cell-label">Téléchargements effectués</div>
						<div class="stat-cell-value">{$total_downloads|default:0}</div>
					</div>
					<div class="stat-cell">
						<div class="stat-cell-label">Fichiers actifs</div>
						<div class="stat-cell-value">{$total_download_active|default:0}</div>
					</div>
					<div class="stat-cell">
						<div class="stat-cell-label">Fichiers inactifs</div>
						<div class="stat-cell-value">{$total_download_inactive|default:0}</div>
					</div>
				</div>
			</div>
			{/if}

            <div class="grid">

				{if isset($all) && (!isset($smarty.get.a) || $smarty.get.a == '' || $smarty.get.a == 'del')}

					<section class="col-12 card">
						<div class="card-head">
							<div class="card-title-wrap">
								<h2 class="card-title">Gestion de vos téléchargements</h2>
							</div>
						</div>
							<div class="data-toolbar">
								<div class="data-toolbar-left">
									<div class="input-icon" style="flex:1;max-width:320px">
										<span class="ico"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg></span>
										<input class="input" type="search" placeholder="Rechercher un fichier..." data-datatable-search="dataTables-download">
									</div>
								</div>
							</div>
							<div style="overflow-x:auto">
								<table class="data-table" id="dataTables-download" data-datatable>
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
										{if $alldownload}
											{foreach from=$alldownload item=download}
												<tr class="data-row">
													<td>{$download.title|unescape:"htmlall"|@sbDisplayLang}</td>
													<td>{$download.size|unescape:"htmlall"|@sbDisplayLang}</td>
													<td>{$download.downloaded}</td>
													<td><code>[CS name=sbdownload id={$download.id}]</code></td>
													<td>
														<div class="data-cell-actions">
															<span class="btn--icon" style="color:{if $download.active}var(--success){else}var(--danger){/if}" data-tooltip="Statut {if $download.active}visible{else}non visible{/if}">
																<svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
															</span>
															<a class="btn--icon" href="{$module_url}&a=edit&id={$download.id}" data-tooltip="Modifier">
																<svg viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 1 1 3 3L7 19l-4 1 1-4z"/></svg>
															</a>
															<a class="btn--icon" data-confirm="Sûr de vouloir supprimer ceci ?" href="{$module_url}&a=del&id={$download.id}" data-tooltip="Supprimer">
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
							<div class="data-foot" data-datatable-foot="dataTables-download">
								<div class="data-foot-info" data-foot-info></div>
								<div class="pager"></div>
							</div>

					</section>

				{/if}

				{if isset($smarty.get.a) && ($smarty.get.a == 'add' || $smarty.get.a == 'edit')}

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

						<div class="card">
							<div class="card-head">
								<div class="card-title-wrap">
									<h2 class="card-title">Aspect du bouton</h2>
								</div>
							</div>
							<div class="alert info">
								<span class="ico"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg></span>
								<div class="body">
									Le shortcode affiche un bouton reprenant le nom du fichier, sa taille
									et son nombre de téléchargements. Si vous laissez le champ
									<strong>Taille</strong> vide, rien n'est affiché à sa place.
								</div>
							</div>
						</div>
					</div>

				{/if}

            </div>
            <!-- /.grid -->

			<div class="modal fade" id="sbdownload_shortcodes" tabindex="-1" role="dialog" aria-labelledby="sbdownload_shortcodes_label" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title" id="sbdownload_shortcodes_label">Shortcodes</h4>
						</div>
						<div class="modal-body">
							<div style="font-size: 12px;">
							<span style="font-style: italic;">Ce module n'a pas de page publique : il s'utilise uniquement en insérant un shortcode dans un contenu (page, article, onglet, bloc...).</span><br><br>
							<span style="font-weight: bold;">[CS name=sbdownload id=1]</span><br>
							<span style="font-style: italic;">Affiche le bouton de téléchargement du fichier à l'ID 1</span><br><br>
							<span style="font-weight: bold;">Insertion directe dans un tpl (module inc)</span><br>
							<span style="font-style: italic;">Pour afficher un bouton directement dans le template d'un module, sans passer par un contenu éditable, insérer le shortcode via le modifier Smarty <code>sbGetShortcode</code> :</span><br>
							<code>{ldelim}"[CS name=sbdownload id=1]"|sbGetShortcode{rdelim}</code><br><br>
							<span style="font-style: italic;">Ou via la fonction Smarty <code>insert</code> <code>sbDoShortcode</code> (ex : navigation.tpl, index.tpl d'un thème) :</span><br>
							<code>{ldelim}insert name="sbDoShortcode" code="[CS name=sbdownload id=1]"{rdelim}</code>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn--ghost" data-dismiss="modal">Fermer</button>
						</div>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>

	{include file='sb_footer.tpl' page='false' pagef='false'}
