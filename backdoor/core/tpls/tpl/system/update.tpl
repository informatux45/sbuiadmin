{* -------------- *}
{* --- MODULE --- *}
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page page='false'}
	{* ---------------- End Headers --------------- *}

			{include file='shared/shared-settings-hero.tpl'}

			<div id="sbupd" class="grid"
				 data-token="{$sb_upd_token|escape}"
				 data-url="index.php?p=update&amp;ajax="
				 data-status="update-status.php?t={$sb_upd_token|escape}">

				{if $sb_msg_error}
				<section class="col-12">
					<div class="alert danger"><span class="ico"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/></svg></span><div class="body">{$sb_msg_error|escape}</div></div>
				</section>
				{/if}

				{* --- Versions --- *}
				<section class="col-6 card">
					<div class="card-head">
						<div class="card-title-wrap">
							<h2 class="card-title">Version de SBUIADMIN</h2>
						</div>
						<button type="button" class="btn btn--outline-primary btn--sm" data-upd-action="check">Vérifier maintenant</button>
					</div>
					<div class="card-body">
						<p>Version installée : <strong>{$sb_upd_state.current|escape}</strong> — schéma de la base : <strong>{$sb_upd_db_version|escape}</strong></p>
						{if $sb_upd_db_pending.migrations}
						<div class="alert warning" style="margin:4px 0 14px"><span class="ico"><svg viewBox="0 0 24 24"><path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z"/><path d="M12 9v4M12 17h.01"/></svg></span><div class="body">
							<strong>Base de données à mettre à niveau</strong><br>
							Les fichiers sont en {$sb_upd_db_pending.to|escape} mais la base en est restée au schéma {$sb_upd_db_pending.from|escape} (fichiers mis à jour hors de cette page : FTP, copie…).<br>
							Migration{if $sb_upd_db_pending.many}s{/if} en attente : <strong>{$sb_upd_db_pending.list|escape}</strong>. La base est sauvegardée avant, et remise telle quelle si une migration échoue.
							<div class="field" style="margin-top:10px;max-width:340px">
								<label class="field-label" for="sbupd-db-password">Votre mot de passe (confirmation)</label>
								<input class="input" type="password" id="sbupd-db-password" autocomplete="current-password">
							</div>
							<button type="button" class="btn btn--primary" style="margin-top:10px" data-upd-action="dbupgrade">Mettre la base à niveau</button>
						</div></div>
						{/if}
						{if $sb_upd_state.latest}
							<p>Dernière version publiée : <strong>{$sb_upd_state.latest.version|escape}</strong>
							{if $sb_upd_state.latest.url} — <a href="{$sb_upd_state.latest.url|escape}" target="_blank" rel="noopener">voir sur GitHub</a>{/if}</p>
						{/if}
						<p style="color:var(--t-muted)">Dernière vérification : {$sb_upd_last_check} (automatique une fois par jour, à l'ouverture du tableau de bord).
						{if $sb_upd_state.error}<br><span style="color:var(--danger)">Dernière vérification en échec : {$sb_upd_state.error|escape}</span>{/if}</p>
						<p style="color:var(--t-muted)">Seules les versions signées (Ed25519) publiées sur <a href="https://github.com/informatux45/sbuiadmin/releases" target="_blank" rel="noopener">GitHub</a> sont proposées ; l'archive, la liste des fichiers et chaque fichier écrit sont vérifiés.</p>
						{if !$sb_upd_ready}<div class="alert warning"><span class="ico"><svg viewBox="0 0 24 24"><path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z"/><path d="M12 9v4M12 17h.01"/></svg></span><div class="body">Extensions PHP manquantes (zip, curl ou sodium) : mise à jour impossible depuis l'administration.</div></div>{/if}
					</div>
				</section>

				{if $sb_upd_available}
				{* --- Mise à jour disponible --- *}
				<section class="col-6 card" id="sbupd-card" style="background:var(--warning-soft);border:1px solid var(--warning)">
					<div class="card-head">
						<div class="card-title-wrap">
							<h2 class="card-title" style="color:var(--warning);display:flex;align-items:center;gap:8px"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" style="flex:none"><path d="M21 12a9 9 0 1 1-3-6.7L21 8"/><path d="M21 3v5h-5"/></svg>SBUIADMIN {$sb_upd_state.latest.version|escape} est disponible</h2>
						</div>
					</div>
					<div class="card-body">
						{if $sb_upd_state.latest.notes}
							<div class="sbupd-notes">{$sb_upd_notes_html}</div>
						{/if}

						<div class="alert info" style="margin-top:12px"><span class="ico"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg></span><div class="body">
							Une sauvegarde chiffrée des fichiers remplacés et de la base de données est faite automatiquement : en cas d'échec, le retour à la version actuelle est automatique, et la mise à jour peut ensuite être annulée (une fois) depuis cette page.
							Pendant la copie, le site et l'administration affichent « mise à jour en cours ».
							Jamais touchés : <code>upload/</code>, <code>.htaccess</code>, <code>sbconfig.php</code>, <code>inc/cmscustom.php</code>, réglages, caches, installeur, et les modules (mis à jour dans la carte « Modules »).
						</div></div>

						<div id="sbupd-step1" style="margin-top:16px">
							<button type="button" class="btn btn--primary" data-upd-action="prepare"{if !$sb_upd_ready} disabled{/if}>Préparer la mise à jour</button>
							<span class="sbupd-hint" style="color:var(--t-muted)">Téléchargement et vérifications, rien n'est encore modifié.</span>
						</div>

						<div id="sbupd-step2" style="display:none;margin-top:16px">
							<div id="sbupd-plan"></div>
							<label class="check" id="sbupd-confirm-wrap" style="margin-top:10px">
								<input type="checkbox" id="sbupd-confirm"> <span class="box"></span>
								<span id="sbupd-confirm-label">Je sais que ces fichiers modifiés localement seront remplacés (leur version actuelle est gardée dans la sauvegarde).</span>
							</label>
							<div class="field" style="margin-top:12px;max-width:340px">
								<label class="field-label" for="sbupd-password">Votre mot de passe (confirmation)</label>
								<input class="input" type="password" id="sbupd-password" autocomplete="current-password">
							</div>
							<div style="margin-top:12px">
								<button type="button" class="btn btn--primary" data-upd-action="run">Lancer la mise à jour</button>
								<button type="button" class="btn btn--ghost" data-upd-action="cancel">Annuler</button>
							</div>
						</div>
					</div>
				</section>
				{else}
				<section class="col-6 card" style="background:var(--success-soft);border:1px solid var(--success)">
					<div class="card-head">
						<div class="card-title-wrap">
							<h2 class="card-title" style="color:var(--success);display:flex;align-items:center;gap:8px"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" style="flex:none"><circle cx="12" cy="12" r="10"/><path d="m8 12 3 3 5-6"/></svg>SBUIADMIN est à jour</h2>
						</div>
					</div>
					<div class="card-body"><p>Aucune version plus récente que la {$sb_upd_state.current|escape} n'est publiée.<br>Les modules se mettent à jour séparément, dans la carte « Modules ».</p></div>
				</section>
				{/if}

				{* --- Modules : mis à jour séparément du core --- *}
				{literal}<style>.sbmod-table th:nth-child(n+4){text-align:center}.sbmod-table tr:not(#sbmod-slot) td:nth-child(4),.sbmod-table tr:not(#sbmod-slot) td:nth-child(5),.sbmod-table tr:not(#sbmod-slot) td:nth-child(6){text-align:center}.table tbody tr#sbmod-slot:hover td{background:transparent}#sbmod-panel{background:var(--bg-muted)}.sbmod-table td:nth-child(3),.sbmod-table th:nth-child(3){padding-right:40px}.sbmod-table td:nth-child(4),.sbmod-table td:nth-child(5),.sbmod-table th:nth-child(4),.sbmod-table th:nth-child(5){white-space:nowrap;padding-right:24px}.sbmod-busy{display:flex;flex-direction:column;align-items:center;gap:10px;padding:22px 12px;text-align:center}.sbmod-busy strong{font-size:15px;color:var(--primary)}.sbmod-busy small{color:var(--t-muted)}.sbbtn-spin{display:inline-block;width:14px;height:14px;border:2px solid currentColor;border-right-color:transparent;border-radius:50%;animation:sbmodspin .8s linear infinite;vertical-align:-2px;margin-right:6px}.sbmod-spin{width:34px;height:34px;border:3px solid var(--border);border-top-color:var(--primary);border-radius:50%;animation:sbmodspin .8s linear infinite}@keyframes sbmodspin{to{transform:rotate(360deg)}}.sbmod-ico{display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:50%;background:var(--primary-soft);color:var(--primary);font-size:16px}</style>{/literal}
				<section class="col-12 card" id="sbmod">
					<div class="card-head">
						<div class="card-title-wrap">
							<h2 class="card-title">Modules</h2>
						</div>
						<button type="button" class="btn btn--outline-primary btn--sm" data-mod-action="check"{if !$sb_upd_ready} disabled{/if}>Vérifier les modules</button>
					</div>
					<div class="card-body">
						<p style="color:var(--t-muted)">Chaque module a sa version et se met à jour indépendamment du core.<br>Dernière vérification : {$sb_mod_checked}{if $sb_mod_cache.core_version} (version publiée {$sb_mod_cache.core_version|escape}){/if}.
						{if $sb_mod_cache.error}<br><span style="color:var(--danger)">Dernière vérification en échec : {$sb_mod_cache.error|escape}</span>{/if}</p>
						<table class="table sbmod-table">
							<thead><tr><th style="width:52px"></th><th>Module</th><th>Description</th><th>Version</th><th>Publiée</th><th>État</th><th>Actions</th></tr></thead>
							<tbody>
							{foreach $sb_mod_catalog as $mod}
								<tr>
									<td><span class="sbmod-ico"><i class="{$mod.icon|sbFaClass} fa-fw"></i></span></td>
									<td><strong>{$mod.title|escape}</strong><br><code style="font-size:11px">{$mod.name|escape}</code></td>
									<td style="max-width:420px">{$mod.description|escape}</td>
									<td>{if $mod.local}{$mod.local|escape}{else}—{/if}</td>
									<td>{if $mod.site}<small title="Module propre au site : jamais installé ni mis à jour depuis GitHub">propre au site</small>{elseif $mod.remote}{$mod.remote|escape}{if !$mod.remote_ok}<br><small style="color:var(--warning)">core {$mod.requires|escape} requis</small>{/if}{else}—{/if}</td>
									<td>
										{if $mod.state == 'absent'}<span class="badge info">Sur GitHub</span>
										{elseif $mod.state == 'present'}<span class="badge warning">Non installé</span>
										{elseif $mod.state == 'outdated'}<span class="badge warning">Mise à jour disponible</span>
										{elseif $mod.state == 'dbbehind'}<span class="badge warning" data-tooltip="Fichiers en {$mod.local|escape}, base en {$mod.db_version|escape}">Base à mettre à jour</span>
										{else}<span class="badge success">Installé</span>{/if}
										{if $mod.present && !$mod.local_ok}<br><small style="color:var(--warning)">core {$mod.local_requires|escape} requis</small>{/if}
									</td>
									<td style="white-space:nowrap">
										{foreach $mod.actions as $op}
											<button type="button" class="btn btn--sm {if $op == 'uninstall' || $op == 'rollback'}btn--ghost{else}btn--outline-primary{/if}" data-mod-action="plan" data-mod="{$mod.name|escape}" data-op="{$op|escape}"{if $op == 'rollback'} data-tooltip="Annuler {$mod.rollback.label|escape} du {$mod.rollback.date|escape}"{/if}{if !$sb_upd_ready && ($op == 'update' || !$mod.present)} disabled{/if}>
												{if $op == 'update'}Mettre à jour{elseif $op == 'migrate'}Mettre à jour la base{elseif $op == 'install'}Installer{elseif $op == 'uninstall'}Désinstaller{else}Annuler{/if}
											</button>
										{/foreach}
										{foreach $mod.blocked as $op => $why}
											<span data-tooltip="{$why|escape}"><button type="button" class="btn btn--sm btn--outline-primary" disabled>{if $op == 'update'}Mettre à jour{elseif $op == 'migrate'}Mettre à jour la base{else}Installer{/if}</button></span>
										{/foreach}
										{if isset($mod.blocked.install)}<br><small style="color:var(--warning);white-space:normal">{$mod.blocked.install|escape}</small>{/if}
									</td>
								</tr>
							{foreachelse}
								<tr><td colspan="7" style="color:var(--t-muted)">Aucun module : cliquez sur « Vérifier les modules » pour lire ceux publiés sur GitHub.</td></tr>
							{/foreach}
							</tbody>
						</table>

						<div id="sbmod-panel" style="display:none;margin-top:16px;padding:16px;border:1px solid var(--border);border-radius:10px">
							<h3 id="sbmod-panel-title" style="margin:0 0 8px"></h3>
							<div id="sbmod-panel-body"></div>
							<label class="check" id="sbmod-confirm-wrap" style="margin-top:10px;display:none">
								<input type="checkbox" id="sbmod-confirm"> <span class="box"></span>
								<span id="sbmod-confirm-label"></span>
							</label>
							<div id="sbmod-remove-wrap" style="margin-top:12px;display:none">
								<label class="check">
									<input type="checkbox" id="sbmod-remove"> <span class="box"></span>
									<span><strong>Supprimer le module</strong></span>
								</label>
								<div style="display:flex;gap:8px;align-items:flex-start;margin-top:6px;color:var(--warning)">
									<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" style="flex:none;margin-top:1px"><path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z"/><path d="M12 9v4M12 17h.01"/></svg>
									<span>Supprime aussi ses données, ses tables SQL et ses fichiers du serveur (sauvegarde chiffrée faite avant). Il pourra être réinstallé depuis GitHub, sans ses données.</span>
								</div>
								<div id="sbmod-remove-info" class="alert warning" style="display:none;margin-top:10px"><span class="ico"><svg viewBox="0 0 24 24"><path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z"/><path d="M12 9v4M12 17h.01"/></svg></span><div class="body" id="sbmod-remove-body"></div></div>
							</div>
							<div id="sbmod-form">
							<div class="field" style="margin-top:12px;max-width:340px">
								<label class="field-label" for="sbmod-password">Votre mot de passe (confirmation)</label>
								<input class="input" type="password" id="sbmod-password" autocomplete="current-password">
							</div>
							<div style="margin-top:12px">
								<button type="button" class="btn btn--primary" data-mod-action="run" id="sbmod-run">Confirmer</button>
								<button type="button" class="btn btn--ghost" data-mod-action="close">Annuler</button>
							</div>
							</div>
						</div>
						<div class="alert primary" style="margin-top:12px"><span class="ico"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg></span><div class="body">
							<strong>Installer</strong> télécharge le module depuis GitHub s'il n'est pas sur le serveur, crée ses tables et l'active (menu, droits, pages, codes courts) ;<br>
							<strong>Mettre à jour</strong> remplace ses fichiers par la version publiée et met sa base à niveau ;<br>
							<strong>Désinstaller</strong> le désactive en gardant ses données et ses fichiers ;<br>
							<strong>Supprimer le module</strong> (case de la désinstallation) retire aussi ses données, ses tables et ses fichiers du serveur.<br>
							Chaque opération est précédée d'une sauvegarde chiffrée (fichiers et tables du module) et annulée automatiquement en cas d'échec ; « <strong>Annuler</strong> » sur la ligne du module revient sur la dernière opération, tant que rien n'a bougé depuis.
						</div></div>
					</div>
				</section>

				{* --- Progression (mise à jour ou retour arrière) --- *}
				<section class="col-12 card" id="sbupd-progress" style="display:none">
					<div class="card-body">
						{* Attente (cercle animé) tant que l'avancement n'est pas chiffré *}
						<div id="sbupd-busy" class="sbmod-busy" style="display:none"><div class="sbmod-spin" role="status" aria-label="En cours"></div><strong id="sbupd-busy-text"></strong><small id="sbupd-busy-sub"></small></div>
						<div id="sbupd-bar" style="height:10px;background:var(--bg-muted);border-radius:99px;overflow:hidden">
							<div id="sbupd-bar-fill" style="height:100%;width:0;background:var(--primary);transition:width .3s"></div>
						</div>
						<p id="sbupd-step-text" style="margin-top:8px;text-align:center"></p>
					</div>
				</section>

				{* --- Erreur / opération précédente --- *}
				<section class="col-12" style="display:none" id="sbupd-error"><div class="alert danger"><span class="ico"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/></svg></span><div class="body" id="sbupd-error-text"></div></div></section>
				{if $sb_upd_job && ($sb_upd_job.status == 'failed' || $sb_upd_job.status == 'running' || $sb_upd_job.status == 'rolledback')}
				<section class="col-12">
					<div class="alert {if $sb_upd_job.status == 'rolledback'}info{else}warning{/if}"><span class="ico"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg></span><div class="body">
						Dernière opération vers {$sb_upd_job.to|escape} : {$sb_upd_job.step|escape}
					</div></div>
				</section>
				{/if}

				{* --- Retour arrière (dernière mise à jour seulement) --- *}
				<section class="col-6 card">
					<div class="card-head"><div class="card-title-wrap"><h2 class="card-title">Retour arrière</h2></div></div>
					<div class="card-body">
						{if $sb_upd_rollback}
							<p>Dernière mise à jour : <strong>{$sb_upd_rollback.from|escape} → {$sb_upd_rollback.to|escape}</strong> le {$sb_upd_rollback.date_fr} ({$sb_upd_rollback.size_mo} Mo chiffrés).</p>
							{if $sb_upd_rollback.migrations}
								{if $sb_upd_rollback.needs_dump}
									<div class="alert warning"><span class="ico"><svg viewBox="0 0 24 24"><path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z"/><path d="M12 9v4M12 17h.01"/></svg></span><div class="body">Cette mise à jour a modifié la base de façon non réversible : le retour remettra la base telle qu'elle était le {$sb_upd_rollback.date_fr}. <strong>Les contenus saisis depuis seront perdus.</strong></div></div>
									<label class="check"><input type="checkbox" id="sbupd-accept-loss"> <span class="box"></span> J'accepte la perte des contenus saisis depuis cette date.</label>
								{else}
									<p style="color:var(--t-muted)">La base sera ramenée au schéma {$sb_upd_rollback.db_from|escape} en annulant les migrations ({$sb_upd_rollback.migrations_str|escape}) : les contenus saisis depuis sont conservés. Si l'annulation échoue, la base est remise depuis la sauvegarde.</p>
								{/if}
							{/if}
							<div class="field" style="margin-top:10px;max-width:340px">
								<label class="field-label" for="sbupd-rb-password">Votre mot de passe (confirmation)</label>
								<input class="input" type="password" id="sbupd-rb-password" autocomplete="current-password">
							</div>
							<button type="button" class="btn btn--outline-primary" style="margin-top:10px" data-upd-action="rollback" data-backup="{$sb_upd_rollback.name|escape}" data-version="{$sb_upd_rollback.from|escape}" data-needs-dump="{if $sb_upd_rollback.needs_dump}1{else}0{/if}">Revenir à la version {$sb_upd_rollback.from|escape}</button>
							<p style="color:var(--t-muted);margin-top:10px">Un seul cran : seule la dernière mise à jour peut être annulée. Fichiers remplacés ou retirés remis, fichiers ajoutés supprimés.</p>
						{else}
							<p style="color:var(--t-muted)">Aucune mise à jour à annuler.<br>Seule la dernière mise à jour faite depuis cette page peut être annulée, une seule fois.</p>
						{/if}
					</div>
				</section>

				{* --- Sauvegardes : emplacement et sécurité --- *}
				<section class="col-6 card">
					<div class="card-head"><div class="card-title-wrap"><h2 class="card-title">Sauvegardes et sécurité</h2></div></div>
					<div class="card-body">
						{if $sb_upd_storage}
							<p>Emplacement :
							{if $sb_upd_storage.mode == 'custom'}<strong>dossier imposé</strong> (SBUIADMIN_BACKUP_DIR ou backup_dir de sbdbconfig.php)
							{elseif $sb_upd_storage.mode == 'private'}<strong>à côté de sbdbconfig.php</strong>
							{else}<strong>dans le site</strong> (dossier au nom aléatoire){/if}
							{if $sb_upd_storage.outside} — <span style="color:var(--success)">hors du dossier publié ✓</span>{/if}</p>
							<p><code style="font-size:11px;word-break:break-all">{$sb_upd_storage.path|escape}</code></p>
							{if $sb_upd_selftest == 'exposed'}
								<div class="alert danger"><span class="ico"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/></svg></span><div class="body">Le serveur web sert ce dossier (l'interdiction n'est pas appliquée, cas de nginx par exemple). Les sauvegardes restent chiffrées et leur nom imprévisible, mais placez-les hors du site : variable d'environnement <code>SBUIADMIN_BACKUP_DIR</code> ou clé <code>'backup_dir'</code> de sbdbconfig.php.</div></div>
							{elseif $sb_upd_selftest == 'protected'}
								<p style="color:var(--success)">Auto-test : dossier refusé au web ✓</p>
							{elseif $sb_upd_selftest == 'unknown' && !$sb_upd_storage.outside}
								<p style="color:var(--warning)">Auto-test impossible : vérifiez que ce dossier n'est pas accessible par le web.</p>
							{/if}
						{/if}
						{if $sb_upd_vendor_foreign}
							<div class="alert warning"><span class="ico"><svg viewBox="0 0 24 24"><path d="M12 9v4M12 17h.01"/><circle cx="12" cy="12" r="10"/></svg></span><div class="body">Dossiers de <code>vendor/</code> propres au site :
							{foreach $sb_upd_vendor_foreign as $v}<code>{$v.name|escape}</code>{if $v.open} (ouvert au web){/if}{if !$v@last}, {/if}{/foreach}.<br>
							<code>vendor/.htaccess</code> refuse tout au web : un fichier servi depuis ces dossiers (retour de paiement, script, image) doit être rouvert par un <code>.htaccess</code> dans son dossier (<code>Require all granted</code>).</div></div>
						{/if}
						<p style="color:var(--t-muted)">Sauvegardes chiffrées (libsodium, clé de sbdbconfig.php), droits 600, une seule gardée.<br>Mot de passe redemandé avant toute mise à jour ou retour arrière ; chaque opération est notée dans le journal des connexions.<br>Ces sauvegardes servent à annuler une mise à jour, pas à remplacer les sauvegardes externes du serveur.</p>
					</div>
				</section>

				{* --- Historique --- *}
				<section class="col-12 card">
					<div class="card-head"><div class="card-title-wrap"><h2 class="card-title">Historique</h2></div></div>
					<div class="card-body">
						{if $sb_upd_history}
							<table class="table">
								<thead><tr><th>Date</th><th>Opération</th><th>Version</th><th>Fichiers</th><th>Base</th><th>Par</th></tr></thead>
								<tbody>
								{foreach $sb_upd_history as $h}
									<tr>
										<td>{$h.date|escape}</td>
										<td>{$h.result|default:'mise à jour'|escape}</td>
										<td>{$h.from|escape} → {$h.to|escape}</td>
										<td>{if $h.files}{$h.files|escape} écrit(s), {$h.deleted|escape} retiré(s){if $h.modified} ({$h.modified|escape} modifié(s) localement){/if}{/if}</td>
										<td>{if $h.migrations}{$h.migrations|escape} migration(s){/if}</td>
										<td>{$h.user|escape}</td>
									</tr>
								{/foreach}
								</tbody>
							</table>
						{else}
							<p style="color:var(--t-muted)">Aucune mise à jour effectuée depuis l'administration.</p>
						{/if}
					</div>
				</section>

			</div>
			<!-- /.grid -->

		<script src="{$smarty.const._AM_SITE_URL}assets/sbupdate.js?v={$sb_upd_js_ver}"></script>

	{include file='sb_footer.tpl' page='false' pagef='false'}
