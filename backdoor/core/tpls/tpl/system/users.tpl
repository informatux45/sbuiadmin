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
			
			{if $allips}
				<div class="row">
					<div class="col-lg-12">
						<div class="alert alert-warning" style="border: 3px solid;">
							<div style="float: left; display: block; clear: both; margin: 0 1em 0em 0;">
								<a class="sbmedia-display" href="{$smarty.const.SB_ADMIN_URL}img/ddos-attack-http-flood-example.png" rel="facebox">
									<img src="{$smarty.const.SB_ADMIN_URL}img/ddos-attack-http-flood-example-thumbnail.jpg" alt="">
								</a>
							</div>
							<h4>Anti-Flood</h4>
							<p>Une attaque par HTTP flood est une forme particulière d'attaque DDoS (Distributed Denial of Service). Il s'agit d'une tentative de faire planter le site Web ou l'application en le visitant depuis différents endroits. Une attaque par HTTP flood est souvent appelée layer 7 attack. La « Layer 7 », pour « couche 7 » en français, fait référence à la « couche application » du modèle OSI. Le modèle indique qu'Internet se compose de sept couches.</p>
							<p>Une attaque dans cette couche consiste à prendre des ressources du réseau ou du serveur. Dès que le matériel ne dispose plus de ressources suffisantes, le client a besoin de plus de temps pour répondre aux demandes. Comme d'innombrables demandes sont encore adressées au matériel, une surcharge constante du système est créée et le serveur ou l'ensemble du réseau n'est plus accessible.</p>
							<p>Quand les attaquants ont recours au HTTP flood, ils essaient de provoquer un crash du serveur en utilisant des requêtes normales.</p>
							<p>Une attaque par HTTP flood est basée sur les requêtes GET ou POST du client. Le client, c'est-à-dire le navigateur qui appelle la page Web, envoie une de ces demandes, le serveur traite la demande et renvoie le résultat au client.</p>
							<p>La requête GET est utilisée pour récupérer des contenus statiques tels que des images ou des blocs de texte. Une demande POST est utilisée lorsque l'accès à des ressources dynamiques est requis. En termes simples, la méthode GET reçoit des données du serveur et la méthode POST envoie des données au serveur. Les deux méthodes peuvent être utilisées dans l'attaque, mais la méthode POST est davantage utilisée car elle implique un traitement plus complexe pour le serveur.</p>
							<p>L'attaque par HTTP flood repose sur le fait que de nombreuses demandes de ce type sont faites simultanément sur une longue période de temps. Un botnet (ou « réseau de machines zombies ») est généralement utilisé pour augmenter le volume des demandes. L'attaque par HTTP flood est conçue de sorte que le serveur alloue la plus grande ressource possible à chaque requête. Dans une situation normale, cela est voulu, car le serveur ne reçoit pas des milliers ou des centaines de milliers de demandes par minute. Compte tenu du nombre élevé de demandes et d'appels, l'attaquant s'attend toutefois à ce que le serveur soit surchargé par les demandes à forte intensité de traitement et à ce que la page ou l'application Web ne s'affiche plus correctement.</p>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-lg-12">
						<div class="panel panel-{if $allips}primary{else}default{/if}">
							<div class="panel-heading">
								<span class="fa fa-lock fa-fw"></span> <strong>Gestion des IPs bloqu&eacute;es</strong>
							</div>
							<!-- /.panel-heading -->
							<div class="panel-body">
								{if $allips}
								<div class="dataTable_wrapper">
									<table class="table table-striped table-bordered table-hover" id="dataTables-blockedip">
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
											{if $allblockedip}
												{foreach from=$allblockedip item=blockedip}
													<tr class="{cycle values="odd,even"} gradeX">
														<td>{$blockedip.id}</td>
														<td>{$blockedip.ip}</td>
														<td>{$blockedip.blockedtime|date_format:'%d-%m-%Y à %H:%M:%S'}</td>
														<td>{$blockedip.expirationtime|date_format:'%d-%m-%Y à %H:%M:%S'}</td>
														<td>
															<button class="btn btn-default glyphicon glyphicon-eye-open" title="Voir le détail" data-toggle="modal" data-target="#blocked{$blockedip.id}"></button>
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
															<a class="glyphicon glyphicon-remove red" onclick="return confirm('Vous souhaitez débloquer cette adresse IP ?')" href="{$module_url}&a=delblockedip&id={$blockedip.id}" title="Débloquer"></a>
														</td>
													</tr>
												{/foreach}
											{/if}
										</tbody>
									</table>
								</div>
								<!-- /.table-responsive -->
								{/if}
							</div>
							<!-- /.panel-body -->
						</div>
						<!-- /.panel -->
					</div>
					<!-- /.col-lg-12 -->
				</div>
				<!-- /.row -->
			{/if}
			
			{* Notes full width *}
			{if $all}
				<div class="row">
					<div class="col-lg-12">
						<div class="panel panel-warning">
							<div class="panel-heading">
								<span class="fa fa-info-circle"></span> <strong>Vos informations</strong>
							</div>
							<!-- /.panel-heading -->
							<div class="panel-body">
								{* HTML Text Formatted *}
								Vous êtes connecté sous l'utilisateur : <span style="color: red;">{$sbuiadmin_user_name}</span>
								<br>
								Votre groupe d'utilisateur : {$sbuiadmin_user_type|@strtoupper}
							</div>
							<!-- /.panel-body -->
						</div>
						<!-- /.panel -->
					</div>
					<!-- /.col-lg-12 -->
				</div>
				<!-- /.row -->
	
				<div class="row">
					<div class="col-lg-12">
						<div class="panel panel-{if $all}primary{else}default{/if}">
							<div class="panel-heading">
								<span class="fa {if $sort}fa-th-list{else}fa-user{/if} fa-fw"></span> <strong>{if $all}Gestion de vos utilisateurs{else}{$legend_add_edit}{/if}</strong>
							</div>
							<!-- /.panel-heading -->
							<div class="panel-body">
								{if $all}
								<div class="dataTable_wrapper">
									<table class="table table-striped table-bordered table-hover" id="dataTables-users">
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
											{if $alluser}
												{foreach from=$alluser item=user}
													<tr class="{cycle values="odd,even"} gradeX">
														<td><img src="{$user.email|@sbGetGravatar}" class="img-thumbnail" /></td>
														<td>{$user.username|@sbGetUserGroup|upper}</td>
														<td>{$user.username}</td>
														<td>{$user.email}</td>
														<td>{$user.lastlogin|date_format:"%d.%m.%Y - %R"}</td>
														<td>
															<span class="glyphicon glyphicon-eye-open {if $user.active}green{else}red{/if}" title="Statut {if $user.active}visible{else}non visible{/if}"></span>
															&nbsp;
															<a class="glyphicon glyphicon-list yellow" href="{$module_url}&a=menu&id={$user.id}" title="Autorisation menu"></a>
															&nbsp;
															<a class="glyphicon glyphicon-cog" href="{$module_url}&a=edit&id={$user.id}" title="Modifier"></a>
															&nbsp;												
															{if $user.username != $sbuiadmin_user_name}
																<a class="glyphicon glyphicon-remove red jConfirm" href="{$module_url}&a=del&id={$user.id}" title="Supprimer"></a>
															{/if}
														</td>
													</tr>										
												{/foreach}
											{/if}
										</tbody>
									</table>
								</div>
								<!-- /.table-responsive -->
								{/if}
							</div>
							<!-- /.panel-body -->
						</div>
						<!-- /.panel -->
					</div>
					<!-- /.col-lg-12 -->
				</div>
				<!-- /.row -->
			{/if}
			
			{if !$all && $smarty.get.a != 'blockedip' && $smarty.get.a != 'delblockedip'}
				<div class="col-lg-{if $allmenu}12{else}8{/if}">
					<div class="panel panel-default">
						<div class="panel-heading">
							<span class="fa fa-group fa-fw"></span> <strong>{$legend_add_edit|unescape:"htmlall"} - {$smarty.get.a}</strong>
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
				
				{if !$allmenu}
				<div class="col-lg-4">
					{* ------------------------------------ *}
					{* --- Include Shared Panel Actions --- *}
					{include file='shared/shared-panel-actions.tpl'}
					{* ------------------------------------ *}
					{* ------------------------------------ *}
				</div>
				<!-- /.col-lg-4 -->
				{/if}
			{/if}
	
		<!-- ------------------------------------------------------------ -->
		<!-- Page-Level Scripts - Use this space this write your own code -->
		<!-- ------------------------------------------------------------ -->
		<script>
		$(document).ready(function() {
			$('#dataTables-users').DataTable({
					order: [ 0, 'asc' ],
					responsive: true,
					"lengthMenu": [25, 50, 75, 100, 150]
			});
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

