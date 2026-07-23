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
					<span class="eyebrow">Contact</span>
					<h1 class="hero-title">Contact</h1>
					<p class="hero-sub">Gérez vos formulaires de contact et leurs champs.</p>
				</div>
				<div class="hero-actions">
					<div class="dd-wrap">
						<button class="btn btn--outline-primary" data-dropdown>
							Formulaires
							<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
						</button>
						<div class="dd-menu" role="menu" style="min-width:220px">
							<a class="dd-menu-item" href="{$smarty.const._AM_SITE_URL}index.php?p=contact">Tous les formulaires</a>
							<div class="dd-divider"></div>
							<a class="dd-menu-item" href="{$smarty.const._AM_SITE_URL}index.php?p=contact&a=add">+1 formulaire</a>
						</div>
					</div>
					<a class="btn btn--outline-primary" href="{$smarty.const._AM_SITE_URL}index.php?p=contact&a=settings">Paramètres</a>
				</div>
			</section>

            <div class="grid">

				{if $all}
                <section class="col-12 card">
                    <div class="card-head">
						<div class="card-title-wrap">
							<h2 class="card-title">Gestion de vos formulaires de contact</h2>
						</div>
                    </div>
                            <div class="data-toolbar">
								<div class="data-toolbar-left">
									<div class="input-icon" style="flex:1;max-width:320px">
										<span class="ico"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg></span>
										<input class="input" type="search" placeholder="Rechercher..." data-datatable-search="dataTables-contact">
									</div>
								</div>
							</div>
							<div style="overflow-x:auto">
                                <table class="data-table" id="dataTables-contact" data-datatable>
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
										{if $allcontact}
											{foreach from=$allcontact item=contact}
												<tr class="data-row">
													<td>{$contact.title|unescape:"htmlall"|@sbDisplayLang}</td>
													<td>[CS id={$contact.id} name=sbcontact class=yourclass form=formname{$contact.id}]</td>
													<td>
														<div class="data-cell-actions">
															<span class="btn--icon" style="color:{if $contact.active}var(--success){else}var(--danger){/if}" title="Statut {if $contact.active}visible{else}non visible{/if}">
																<svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
															</span>
															<a class="btn--icon" href="{$module_url}&a=edit&id={$contact.id}" title="Modifier">
																<svg viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 1 1 3 3L7 19l-4 1 1-4z"/></svg>
															</a>
															<a class="btn--icon" data-confirm="Sûr de vouloir supprimer ceci ?" href="{$module_url}&a=del&id={$contact.id}" title="Supprimer">
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
							<div class="data-foot" data-datatable-foot="dataTables-contact">
								<div class="data-foot-info" data-foot-info></div>
								<div class="pager"></div>
							</div>

                </section>
				{/if}

				{if !$all && $smarty.get.a && ($smarty.get.a != 'contact' || $smarty.get.a != 'settings') }
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
							{* Aide Contact *}
							<img src="img/contact.jpg" alt="" style="width: 100%;" />
							{if $smarty.get.a != 'settings'}
							<br><br>
							Si les champs suivants ne sont pas remplis, les paramètres par défaut
							seront utilisés :<br>
							- Email(s) de destinataires<br>
							- Sujet<br>
							<br>
							<u>Le formulaire de contact</u><br>
							<br>
							Il doit contenir au minimum les 2 champs suivants :<br>
							- nom (de votre correspondant)<br>
							- email (de votre correspondant)<br>
							Deux boutons sont prévus à cet effet (NAME, EMAIL).<br>
							<br>
							Vous pouvez insérer du HTML.<br>
							<br>
							Placer votre curseur dans votre formulaire, puis cliquez sur le type de
							champs (bouton) que vous souhaitez insérer.<br>
							Les champs doivent contenir un nom (sans espaces, sans caractères
							accentués et sans ponctuations) et/ou un caractère d'obligation pour
							être rempli.<br>
							<br>
							<span style="text-decoration: underline;">Exemples :</span> <br>
							<br>
							Champs TEXT avec obligation d'être rempli<br>
							<span style="font-weight: bold;">[TEXT name=telephone/required=required]</span><br>
							<br>
							Champs TEXT avec obligation d'être rempli et placeholder<br>
							<span style="font-weight: bold;">[TEXT name=telephone/required=required/placeholder=Votre téléphone]</span><br>
							<br>
							Champs CHECKBOX avec obligation d'être rempli<br>
							<span style="font-weight: bold;">[CHECKBOX name=telephone/required=required]</span><br>
							<br>
							Champs TEXTAREA sans obligation d'être rempli<br>
							<span style="font-weight: bold;">[TEXTAREA name=message]</span><br>
							<br>
							Chams SELECT avec obligation d'être rempli<br>
							<span style="font-weight: bold;">[SELECT name=selection/
							options=choisissez un choix|choix 1|choix 2|choix 3|choix 4/value=0|10|20|30|40/
							required=required]</span><br>
							<span style="font-style: italic;">Les choix doivent être séparés par
							des |</span><br style="font-style: italic;">
							<span style="font-style: italic;"> Mettre autant de "value" que de
							"options"</span><br style="font-style: italic;">
							<span style="font-style: italic;">- options: Textes dans le champs SELECT</span><br
							style="font-style: italic;">
							<span style="font-style: italic;">- value: Valeurs affectées à chaque
							choix</span><span style="font-weight: bold;"></span><br>
							<br>
							Champs SUBMIT<br>
							<span style="font-weight: bold;">[SUBMIT name=go]</span><br>
							<span style="font-style: italic;">Si vous choisissez le RECAPTCHA INVISIBLE, celui-ci devient le bouton SUBMIT donc ne pas insérez le bouton SUBMIT cause double emploi.</span>
							{/if}
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
			
	{include file='sb_footer.tpl' page='false' pagef='false'}

