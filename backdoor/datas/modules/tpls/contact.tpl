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
					<p class="hero-sub">Gérez vos formulaires de contact, leurs champs et les messages reçus.</p>
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
							<div class="dd-divider"></div>
							<a class="dd-menu-item" href="{$smarty.const._AM_SITE_URL}index.php?p=contact&a=messages&id=0">Messages de la page Contact{if isset($page_messages) && $page_messages.unread} ({$page_messages.unread} non lu{if $page_messages.unread > 1}s{/if}){/if}</a>
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
															<span class="btn--icon" style="color:{if $contact.active}var(--success){else}var(--danger){/if}" data-tooltip="Statut {if $contact.active}visible{else}non visible{/if}">
																<svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
															</span>
															<a class="btn--icon" href="{$module_url}&a=messages&id={$contact.id}" data-tooltip="Messages ({$contact.messages.total}{if $contact.messages.unread}, {$contact.messages.unread} non lu{if $contact.messages.unread > 1}s{/if}{/if})"{if $contact.messages.unread} style="color:var(--primary)"{/if}>
																<svg viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 5L2 7"/></svg>
															</a>
															<a class="btn--icon" href="{$module_url}&a=edit&id={$contact.id}" data-tooltip="Modifier">
																<svg viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 1 1 3 3L7 19l-4 1 1-4z"/></svg>
															</a>
															<a class="btn--icon" data-confirm="Sûr de vouloir supprimer ceci ?" href="{$module_url}&a=del&id={$contact.id}" data-tooltip="Supprimer">
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

				{assign var=sbact value=$smarty.get.a|default:''}
				{if $sbact == 'messages' || $sbact == 'messagedel'}
                <section class="col-12 card">
                    <div class="card-head">
						<div class="card-title-wrap">
							<h2 class="card-title">Messages reçus : {$form_title|escape}</h2>
						</div>
						<a class="btn btn--outline-primary btn--sm" href="{$module_url}">Tous les formulaires</a>
                    </div>
							<p class="help-block">Messages enregistrés à chaque envoi, même si le mail n'est pas parti. {if $retention}Supprimés automatiquement après {$retention} mois{else}Conservés sans limite{/if} (<a href="{$module_url}&a=settings">Paramètres</a>).</p>
                            <div class="data-toolbar">
								<div class="data-toolbar-left">
									<div class="input-icon" style="flex:1;max-width:320px">
										<span class="ico"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg></span>
										<input class="input" type="search" placeholder="Rechercher..." data-datatable-search="dataTables-messages">
									</div>
								</div>
							</div>
							<div style="overflow-x:auto">
                                <table class="data-table" id="dataTables-messages" data-datatable>
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
										{foreach from=$messages item=msg}
											<tr class="data-row"{if !$msg.is_read} style="font-weight:600"{/if}>
												<td data-sort-value="{$msg.created_at|escape}">{$msg.created_at|date_format:"%d/%m/%Y %H:%M"}</td>
												<td>{$msg.name|escape}</td>
												<td>{$msg.email|escape}</td>
												<td>{if $msg.sent}<span class="badge success">envoyé</span>{else}<span class="badge danger" data-tooltip="{$msg.error|escape}">échec</span>{/if}</td>
												<td>
													<div class="data-cell-actions">
														<a class="btn--icon" href="{$module_url}&a=message&id={$msg.id}" data-tooltip="Lire">
															<svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
														</a>
														<a class="btn--icon" data-confirm="Sûr de vouloir supprimer ce message ?" href="{$module_url}&a=messagedel&id={$form_id}&mid={$msg.id}" data-tooltip="Supprimer">
															<svg viewBox="0 0 24 24"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6h14z"/></svg>
														</a>
													</div>
												</td>
											</tr>
										{/foreach}
                                    </tbody>
                                </table>
                            </div>
							<div class="data-foot" data-datatable-foot="dataTables-messages">
								<div class="data-foot-info" data-foot-info></div>
								<div class="pager"></div>
							</div>
                </section>
				{/if}

				{if $sbact == 'message' && $message}
                <section class="col-12 card">
                    <div class="card-head">
						<div class="card-title-wrap">
							<h2 class="card-title">Message du {$message.created_at|date_format:"%d/%m/%Y à %H:%M"} ({$form_title|escape})</h2>
						</div>
						<a class="btn btn--outline-primary btn--sm" href="{$module_url}&a=messages&id={$message.form_id}">Retour aux messages</a>
                    </div>
							<table class="data-table">
								<tbody>
									{foreach from=$message.fields key=field item=value}
									<tr>
										<th style="width:200px;white-space:nowrap">{$field|replace:'-':' '|escape}</th>
										<td>{$value|escape|nl2br}</td>
									</tr>
									{/foreach}
									<tr>
										<th>Envoi</th>
										<td>{if $message.sent}<span class="badge success">envoyé</span> à {$message.recipients|escape}{else}<span class="badge danger">échec</span> {$message.error|escape}{/if}</td>
									</tr>
								</tbody>
							</table>
							<p style="margin-top:12px">
								<a class="btn btn--primary btn--sm" href="mailto:{$message.email|escape}">Répondre</a>
								<a class="btn btn--danger btn--sm" data-confirm="Sûr de vouloir supprimer ce message ?" href="{$module_url}&a=messagedel&id={$message.form_id}&mid={$message.id}">Supprimer</a>
							</p>
                </section>
				{/if}

				{if !$all && $sbact && $sbact != 'messages' && $sbact != 'messagedel' && $sbact != 'message'}
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
							<br>
							Anti-robot ALTCHA<br>
							<span style="font-weight: bold;">[ALTCHA]</span><br>
							<span style="font-style: italic;">Toujours exigé à l'envoi : à placer avant le bouton SUBMIT (ajouté automatiquement s'il manque).</span>
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

