{* -------------- *}
{* --- MODULE --- *}	
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page page='false'}
	{* ---------------- End Headers --------------- *}
			
			{* ------------------------------------------------ *}
			{*       Write your own code after this line        *}
			{* ------------------------------------------------ *}
			
			{include file='system/users_bar.tpl'}
			
			{if isset($allips)}
				<div class="grid">
					<section class="col-12">
						<div class="alert warning">
							<span class="ico"><svg viewBox="0 0 24 24"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><path d="M12 9v4M12 17h.01"/></svg></span>
							<div class="body">
								<div class="title">Anti-Flood</div>
								<a class="sbmedia-display" href="{$smarty.const.SB_ADMIN_URL}img/ddos-attack-http-flood-example.png" rel="facebox" style="float: left; display: block; margin: 0 1em 0.5em 0;">
									<img src="{$smarty.const.SB_ADMIN_URL}img/ddos-attack-http-flood-example-thumbnail.jpg" alt="">
								</a>
								<p>Une attaque par HTTP flood est une forme particulière d'attaque DDoS (Distributed Denial of Service). Il s'agit d'une tentative de faire planter le site Web ou l'application en le visitant depuis différents endroits. Une attaque par HTTP flood est souvent appelée layer 7 attack. La « Layer 7 », pour « couche 7 » en français, fait référence à la « couche application » du modèle OSI. Le modèle indique qu'Internet se compose de sept couches.</p>
								<p>Une attaque dans cette couche consiste à prendre des ressources du réseau ou du serveur. Dès que le matériel ne dispose plus de ressources suffisantes, le client a besoin de plus de temps pour répondre aux demandes. Comme d'innombrables demandes sont encore adressées au matériel, une surcharge constante du système est créée et le serveur ou l'ensemble du réseau n'est plus accessible.</p>
								<p>Quand les attaquants ont recours au HTTP flood, ils essaient de provoquer un crash du serveur en utilisant des requêtes normales.</p>
								<p>Une attaque par HTTP flood est basée sur les requêtes GET ou POST du client. Le client, c'est-à-dire le navigateur qui appelle la page Web, envoie une de ces demandes, le serveur traite la demande et renvoie le résultat au client.</p>
								<p>La requête GET est utilisée pour récupérer des contenus statiques tels que des images ou des blocs de texte. Une demande POST est utilisée lorsque l'accès à des ressources dynamiques est requis. En termes simples, la méthode GET reçoit des données du serveur et la méthode POST envoie des données au serveur. Les deux méthodes peuvent être utilisées dans l'attaque, mais la méthode POST est davantage utilisée car elle implique un traitement plus complexe pour le serveur.</p>
								<p>L'attaque par HTTP flood repose sur le fait que de nombreuses demandes de ce type sont faites simultanément sur une longue période de temps. Un botnet (ou « réseau de machines zombies ») est généralement utilisé pour augmenter le volume des demandes. L'attaque par HTTP flood est conçue de sorte que le serveur alloue la plus grande ressource possible à chaque requête. Dans une situation normale, cela est voulu, car le serveur ne reçoit pas des milliers ou des centaines de milliers de demandes par minute. Compte tenu du nombre élevé de demandes et d'appels, l'attaquant s'attend toutefois à ce que le serveur soit surchargé par les demandes à forte intensité de traitement et à ce que la page ou l'application Web ne s'affiche plus correctement.</p>
							</div>
						</div>
					</section>

					<section class="col-12 card">
						<div class="card-head">
							<div class="card-title-wrap">
								<span class="eyebrow">Utilisateurs</span>
								<h2 class="card-title">Gestion des IPs bloquées</h2>
							</div>
						</div>
								{if isset($allips)}
								<div class="data-toolbar">
									<div class="data-toolbar-left">
										<div class="input-icon" style="flex:1;max-width:320px">
											<span class="ico"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg></span>
											<input class="input" type="search" placeholder="Rechercher..." data-datatable-search="dataTables-blockedip">
										</div>
									</div>
								</div>
								<div style="overflow-x:auto">
									<table class="data-table" id="dataTables-blockedip" data-datatable data-page-size="15">
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
											{if $allblockedip}
												{foreach from=$allblockedip item=blockedip}
													<tr class="data-row">
														<td>{$blockedip.id}</td>
														<td>{$blockedip.ip}</td>
														<td data-sort-value="{$blockedip.blockedtime}">{$blockedip.blockedtime|date_format:'%d-%m-%Y à %H:%M:%S'}</td>
														<td data-sort-value="{$blockedip.expirationtime}">{$blockedip.expirationtime|date_format:'%d-%m-%Y à %H:%M:%S'}</td>
														<td>
															<button class="btn--icon" aria-label="Voir le détail" data-toggle="modal" data-target="#blocked{$blockedip.id}">
																<svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
															</button>
															<!-- Modal -->
															<div class="modal fade" id="blocked{$blockedip.id}" tabindex="-1" role="dialog" aria-labelledby="blocked{$blockedip.id}Label" aria-hidden="true">
																<div class="modal-dialog" role="document">
																	<div class="modal-content" style="text-align: left;">
																		<div class="modal-header">
																			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																				<span aria-hidden="true">&times;</span>
																			</button>
																			<h4 class="modal-title" style="font-weight: bold;" id="blocked{$blockedip.id}">
																				FLOOD détecté sur l'adresse IP : {$blockedip.ip}
																			</h4>
																		</div>
																		<div class="modal-body">
																			{insert name="sbExplodeJson" json="{$blockedip.infos}" assign="user_json"}
																			{$user_json}
																		</div> <!-- ./modal-body -->
																		<div class="modal-footer">
																			<button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
																		</div>
																	</div>
																</div>
															</div>
														</td>
														<td>{$blockedip.reason}</td>
														<td>
															<div class="data-cell-actions">
																<button class="btn--icon" aria-label="Débloquer" onclick="if(confirm('Vous souhaitez débloquer cette adresse IP ?')) window.location.href='{$module_url}&a=delblockedip&id={$blockedip.id}';">
																	<svg viewBox="0 0 24 24"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6h14z"/></svg>
																</button>
															</div>
														</td>
													</tr>
												{/foreach}
											{/if}
										</tbody>
									</table>
								</div>
								<div class="data-foot" data-datatable-foot="dataTables-blockedip">
									<div class="data-foot-info" data-foot-info></div>
									<div class="pager"></div>
								</div>
								{/if}
					</section>
				</div>
				<!-- /.grid -->
			{/if}

			{* Notes full width *}
			{if $all}
				<div class="grid">
					<section class="col-12">
						<div class="alert primary">
							<span class="ico"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg></span>
							<div class="body">
								<div class="title">Vos informations</div>
								Vous êtes connecté sous l'utilisateur : <strong>{$sbuiadmin_user_name}</strong> &middot; Votre groupe d'utilisateur : {$sbuiadmin_user_type|@strtoupper}
							</div>
						</div>
					</section>

					<section class="col-12 card">
						<div class="card-head">
							<div class="card-title-wrap">
								<span class="eyebrow">Utilisateurs</span>
								<h2 class="card-title">{if $all}Gestion de vos utilisateurs{else}{$legend_add_edit}{/if}</h2>
							</div>
						</div>
								{if $all}
								<div class="data-toolbar">
									<div class="data-toolbar-left">
										<div class="input-icon" style="flex:1;max-width:320px">
											<span class="ico"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg></span>
											<input class="input" type="search" placeholder="Rechercher un utilisateur..." data-datatable-search="dataTables-users">
										</div>
									</div>
								</div>
								<div style="overflow-x:auto">
									<table class="data-table" id="dataTables-users" data-datatable data-page-size="25">
										<thead>
											<tr>
												{foreach from=$sb_table_header item=header}
													<th{if $header@first || $header@last} data-sort="false"{/if}>
														{$header}{if !$header@first && !$header@last} <span class="sort"><svg viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></svg></span>{/if}
													</th>
												{/foreach}
											</tr>
										</thead>
										<tbody>
											{if $alluser}
												{foreach from=$alluser item=user}
													<tr class="data-row">
														<td><img src="{$user.email|@sbGetGravatar}" style="width:28px;height:28px;border-radius:50%;" /></td>
														<td>{$user.username|@sbGetUserGroup|upper}</td>
														<td>{$user.username}</td>
														<td>{$user.email}</td>
														<td data-sort-value="{$user.lastlogin}">{$user.lastlogin|date_format:"%d.%m.%Y - %R"}</td>
														<td>
															<div class="data-cell-actions">
																<span class="btn--icon" style="color:{if $user.active}var(--success){else}var(--danger){/if}" title="Statut {if $user.active}visible{else}non visible{/if}">
																	<svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
																</span>
																<a class="btn--icon" href="{$module_url}&a=menu&id={$user.id}" title="Autorisation menu">
																	<svg viewBox="0 0 24 24"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
																</a>
																<a class="btn--icon" href="{$module_url}&a=edit&id={$user.id}" title="Modifier">
																	<svg viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 1 1 3 3L7 19l-4 1 1-4z"/></svg>
																</a>
																{if $user.username != $sbuiadmin_user_name}
																	<a class="btn--icon" data-confirm="Sûr de vouloir supprimer ceci ?" href="{$module_url}&a=del&id={$user.id}" title="Supprimer">
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
								<div class="data-foot" data-datatable-foot="dataTables-users">
									<div class="data-foot-info" data-foot-info></div>
									<div class="pager"></div>
								</div>
								{/if}
					</section>
				</div>
				<!-- /.grid -->
			{/if}

			{if !$all && $smarty.get.a != 'blockedip' && $smarty.get.a != 'delblockedip'}
				<div class="grid">
					<section class="{if $allmenu}col-12{else}col-8{/if} card">
						<div class="card-head">
							<div class="card-title-wrap">
								<span class="eyebrow">Utilisateurs</span>
								<h2 class="card-title">{$legend_add_edit|unescape:"htmlall"} - {$smarty.get.a}</h2>
							</div>
						</div>
							{* Afficher le formulaire ADD/EDIT *}
							{include_php file='form.php'}
					</section>

					{if !$allmenu}
					<div class="col-4">
						{* ------------------------------------ *}
						{* --- Include Shared Panel Actions --- *}
						{include file='shared/shared-panel-actions.tpl'}
						{* ------------------------------------ *}
						{* ------------------------------------ *}
					</div>
					<!-- /.col-4 -->
					{/if}
				</div>
				<!-- /.grid -->
			{/if}
	
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

