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
					<span class="eyebrow">MÉDIAS</span>
					<h1 class="hero-title">Galeries</h1>
					<p class="hero-sub">Gérez vos galeries photos et vidéos, et leur gabarit d'affichage.</p>
				</div>
				<div class="hero-actions">
					<div class="dd-wrap">
						<button class="btn btn--outline-primary" data-dropdown>
							Galeries
							<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
						</button>
						<div class="dd-menu" role="menu" style="min-width:220px">
							<a class="dd-menu-item" href="{$smarty.const._AM_SITE_URL}index.php?p=gallery">Toutes les galeries</a>
							<div class="dd-divider"></div>
							<a class="dd-menu-item" href="{$smarty.const._AM_SITE_URL}index.php?p=gallery&a=add">+1 galerie</a>
						</div>
					</div>
					<div class="dd-wrap">
						<button class="btn btn--outline-primary" data-dropdown>
							Photos
							<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
						</button>
						<div class="dd-menu" role="menu" style="min-width:220px">
							{if isset($gid) && $gid}
								<a class="dd-menu-item" href="{$smarty.const._AM_SITE_URL}index.php?p=gallery&a=photo&gid={$gid}">Toutes les photos</a>
								<a class="dd-menu-item" href="{$smarty.const._AM_SITE_URL}index.php?p=gallery&a=sort&gid={$gid}">Trier les photos</a>
								<div class="dd-divider"></div>
							{/if}
							<a class="dd-menu-item" href="{$smarty.const._AM_SITE_URL}index.php?p=gallery&a=photoadd">+1 photo</a>
						</div>
					</div>
					{if isset($gid) && $gid && isset($smarty.get.a) && ($smarty.get.a == 'photo' || $smarty.get.a == 'sort' || $smarty.get.a == 'photoedit')}
						<button class="btn btn--ghost" type="button" onclick="location.href='index.php?p=gallery&a=edit&id={$gid}'">
							Réglages de la galerie
						</button>
					{/if}
					{if isset($smarty.get.a) && $smarty.get.a == 'edit'}
						<button class="btn btn--ghost" type="button" onclick="location.href='index.php?p=gallery&a=photo&gid={$smarty.get.id}'">
							Toutes les photos
						</button>
					{/if}
					<button class="btn btn--ghost" type="button" data-toggle="modal" data-target="#sbgallery_shortcodes">
						Shortcodes
					</button>
				</div>
			</section>

            <div class="grid">

				{* ---------------------------------------------- *}
				{* --- Liste des GALERIES                     --- *}
				{* ---------------------------------------------- *}
				{if isset($all) && $all}

					<section class="col-12 card">
						<div class="card-head">
							<div class="card-title-wrap">
								<h2 class="card-title">Gestion de vos galeries</h2>
							</div>
						</div>
							<div class="data-toolbar">
								<div class="data-toolbar-left">
									<div class="input-icon" style="flex:1;max-width:320px">
										<span class="ico"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg></span>
										<input class="input" type="search" placeholder="Rechercher une galerie..." data-datatable-search="dataTables-galleries">
									</div>
								</div>
							</div>
							<div style="overflow-x:auto">
								<table class="data-table" id="dataTables-galleries" data-datatable>
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
										{if $allgallery}
											{foreach from=$allgallery item=gallery}
												<tr class="data-row">
													<td>{$gallery.title|unescape:"htmlall"|@sbDisplayLang}</td>
													<td>{$gallery.cpt_img|default:0}</td>
													<td><code>[CS name=sbgallery id={$gallery.id}]</code></td>
													<td>
														<div class="data-cell-actions">
															<span class="btn--icon" style="color:{if $gallery.active}var(--success){else}var(--danger){/if}" data-tooltip="Statut {if $gallery.active}visible{else}non visible{/if}">
																<svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
															</span>
															<a class="btn--icon" href="{$module_url}&a=photo&gid={$gallery.id}" data-tooltip="Toutes les photos">
																<svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
															</a>
															<a class="btn--icon" href="{$module_url}&a=sort&gid={$gallery.id}" data-tooltip="Trier les photos">
																<svg viewBox="0 0 24 24"><path d="M3 6h18M6 12h12M10 18h4"/></svg>
															</a>
															<a class="btn--icon" href="{$module_url}&a=edit&id={$gallery.id}" data-tooltip="Modifier">
																<svg viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 1 1 3 3L7 19l-4 1 1-4z"/></svg>
															</a>
															<a class="btn--icon" data-confirm="Sûr de vouloir supprimer cette galerie et toutes ses photos ?" href="{$module_url}&a=del&id={$gallery.id}" data-tooltip="Supprimer">
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
							<div class="data-foot" data-datatable-foot="dataTables-galleries">
								<div class="data-foot-info" data-foot-info></div>
								<div class="pager"></div>
							</div>

					</section>

				{/if}

				{* ---------------------------------------------- *}
				{* --- Liste des PHOTOS d'une galerie         --- *}
				{* ---------------------------------------------- *}
				{if isset($allphoto) && $allphoto}

					<section class="col-12 card">
						<div class="card-head">
							<div class="card-title-wrap">
								<h2 class="card-title">Photos de la galerie</h2>
							</div>
						</div>
							<div class="data-toolbar">
								<div class="data-toolbar-left">
									<div class="input-icon" style="flex:1;max-width:320px">
										<span class="ico"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg></span>
										<input class="input" type="search" placeholder="Rechercher une photo..." data-datatable-search="dataTables-photos">
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
														<span class="btn--icon" style="color:{if $photo.active}var(--success){else}var(--danger){/if}" data-tooltip="Statut {if $photo.active}visible{else}non visible{/if}">
															<svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
														</span>
														<a class="btn--icon" href="{$module_url}&a=photoedit&id={$photo.id}&gid={$gid}" data-tooltip="Modifier">
															<svg viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 1 1 3 3L7 19l-4 1 1-4z"/></svg>
														</a>
														<a class="btn--icon" data-confirm="Sûr de vouloir supprimer cette photo ?" href="{$module_url}&a=delphoto&gid={$gid}&id={$photo.id}" data-tooltip="Supprimer">
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

				{* ---------------------------------------------- *}
				{* --- Formulaires ADD / EDIT / SORT          --- *}
				{* ---------------------------------------------- *}
				{if (!isset($all) || !$all) && (!isset($allphoto) || !$allphoto) && isset($smarty.get.a) && $smarty.get.a != 'photo' && $smarty.get.a != 'del'}

					<section class="col-8 card">
						<div class="card-head">
							<div class="card-title-wrap">
								<h2 class="card-title">{$legend_add_edit}</h2>
							</div>
						</div>
							{* Afficher le formulaire ADD/EDIT/SORT *}
							{include_php file='form.php'}
					</section>

					{if $smarty.get.a != 'sort'}
					<div class="col-4">
						{* ------------------------------------ *}
						{* --- Include Shared Panel Actions --- *}
						{include file='shared/shared-panel-actions.tpl'}
						{* ------------------------------------ *}

						{if $smarty.get.a == 'add' || $smarty.get.a == 'edit'}
						<div class="card">
							<div class="card-head">
								<div class="card-title-wrap">
									<h2 class="card-title">Les trois éditeurs</h2>
								</div>
							</div>
							<div class="alert info">
								<span class="ico"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg></span>
								<div class="body">
									<strong>CSS</strong> et <strong>JAVASCRIPT</strong> : saisissez le code seul,
									sans les balises <code>&lt;style&gt;</code> ni <code>&lt;script&gt;</code>.
								</div>
							</div>
							<div class="alert info">
								<span class="ico"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg></span>
								<div class="body">
									<strong>TEMPLATE</strong> : ce HTML est répété <em>pour chaque photo</em> de la
									galerie. Trois marqueurs y sont remplacés :
									<br><code>{ldelim}IMAGE{rdelim}</code> — URL de l'image
									<br><code>{ldelim}IMAGE_THUMB{rdelim}</code> — vignette 150×150
									<br><code>{ldelim}IMAGE_NAME{rdelim}</code> — nom de la photo
								</div>
							</div>
						</div>
						{/if}
					</div>
					{/if}

				{/if}

            </div>
            <!-- /.grid -->

			<div class="modal fade" id="sbgallery_shortcodes" tabindex="-1" role="dialog" aria-labelledby="sbgallery_shortcodes_label" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title" id="sbgallery_shortcodes_label">Shortcodes</h4>
						</div>
						<div class="modal-body">
							<div style="font-size: 12px;">
							<span style="font-style: italic;">Ce module n'a pas de page publique : il s'utilise uniquement en insérant un shortcode dans un contenu (page, article, onglet, bloc...).</span><br><br>
							<span style="font-weight: bold;">[CS name=sbgallery id=1]</span><br>
							<span style="font-style: italic;">Affiche la galerie à l'ID 1, chaque photo active étant rendue avec le gabarit de la galerie</span><br><br>
							<span style="font-weight: bold;">Insertion directe dans un tpl (module inc)</span><br>
							<span style="font-style: italic;">Pour afficher une galerie directement dans le template d'un module, sans passer par un contenu éditable, insérer le shortcode via le modifier Smarty <code>sbGetShortcode</code> :</span><br>
							<code>{ldelim}"[CS name=sbgallery id=1]"|sbGetShortcode{rdelim}</code><br><br>
							<span style="font-style: italic;">Ou via la fonction Smarty <code>insert</code> <code>sbDoShortcode</code> (ex : navigation.tpl, index.tpl d'un thème) :</span><br>
							<code>{ldelim}insert name="sbDoShortcode" code="[CS name=sbgallery id=1]"{rdelim}</code>
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

		<!-- ------------------------------------------------------------ -->
		<!-- Page-Level Scripts - Use this space this write your own code -->
		<!-- ------------------------------------------------------------ -->
		<script>
		$(document).ready(function() {
			{if $sort}
				$( "#sortable" ).sortable({
					axis: "y",
					placeholder: "ui-state-highlight"
				});
				$( "#sortable" ).disableSelection();
			{/if}
		});
		</script>

		{* ------------------------------------------------------------ *}
		{* Editeurs ACE : uniquement sur les ecrans add / edit d'une     *}
		{* galerie, seuls a poser les div#css / #javascript / #template. *}
		{* ------------------------------------------------------------ *}
		{if isset($smarty.get.a) && ($smarty.get.a == 'add' || $smarty.get.a == 'edit')}
		<script src="inc/plugins/ace/ace.js" type="text/javascript" charset="utf-8"></script>
		<script>
		$(document).ready(function() {
			// --- Un editeur ACE par champ, chacun recopie sa valeur dans son
			// --- champ cache au submit. Le marqueur code_ready permet au PHP
			// --- de distinguer un champ vide VOULU d'un JS qui n'a pas tourne.
			function sbAceBind(divId, mode, hiddenName) {
				var $editor = $('#' + divId);
				if ($editor.length === 0) return;
				var editor = ace.edit(divId);
				editor.setTheme("ace/theme/textmate");
				editor.session.setMode("ace/mode/" + mode);
				editor.getSession().setTabSize(4);
				editor.getSession().setUseWrapMode(true);
				editor.setShowPrintMargin(true);
				editor.setHighlightActiveLine(true);
				$editor.closest('form').submit(function() {
					$('input[name="' + hiddenName + '"]').val(editor.getValue());
					$('input[name="code_ready"]').val('1');
				});
			}
			sbAceBind('css', 'css', 'css_hidden');
			sbAceBind('javascript', 'javascript', 'javascript_hidden');
			sbAceBind('template', 'smarty', 'template_hidden');
		});
		</script>
		{/if}

	{include file='sb_footer.tpl' page='false' pagef='false'}
