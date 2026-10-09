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
				<section class="col-12 card">
					<div class="card-head">
						<div class="card-title-wrap">
							<h2 class="card-title">Version de SBUIADMIN</h2>
						</div>
						<button type="button" class="btn btn--outline-primary btn--sm" data-upd-action="check">Vérifier maintenant</button>
					</div>
					<div class="card-body">
						<p>Version installée : <strong>{$sb_upd_state.current|escape}</strong> — schéma de la base : <strong>{$sb_upd_db_version|escape}</strong></p>
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
				<section class="col-12 card" id="sbupd-card">
					<div class="card-head">
						<div class="card-title-wrap">
							<h2 class="card-title">SBUIADMIN {$sb_upd_state.latest.version|escape} est disponible</h2>
						</div>
					</div>
					<div class="card-body">
						{if $sb_upd_state.latest.notes}
							<div class="sbupd-notes">{$sb_upd_notes_html}</div>
						{/if}

						<div class="alert info" style="margin-top:12px"><span class="ico"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg></span><div class="body">
							Une sauvegarde chiffrée des fichiers remplacés et de la base de données est faite automatiquement : en cas d'échec, le retour à la version actuelle est automatique, et la mise à jour peut ensuite être annulée (une fois) depuis cette page.
							Pendant la copie, le site et l'administration affichent « mise à jour en cours ».
							Jamais touchés : <code>upload/</code>, <code>.htaccess</code>, <code>sbconfig.php</code>, <code>inc/cmscustom.php</code>, réglages, caches, installeur.
						</div></div>

						<div id="sbupd-step1">
							<button type="button" class="btn btn--primary" data-upd-action="prepare"{if !$sb_upd_ready} disabled{/if}>Préparer la mise à jour</button>
							<span class="sbupd-hint" style="color:var(--t-muted)">Téléchargement et vérifications, rien n'est encore modifié.</span>
						</div>

						<div id="sbupd-step2" style="display:none">
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
				<section class="col-12 card">
					<div class="card-body"><p><strong>SBUIADMIN est à jour.</strong></p></div>
				</section>
				{/if}

				{* --- Progression (mise à jour ou retour arrière) --- *}
				<section class="col-12 card" id="sbupd-progress" style="display:none">
					<div class="card-body">
						<div style="height:10px;background:var(--bg-muted);border-radius:99px;overflow:hidden">
							<div id="sbupd-bar-fill" style="height:100%;width:0;background:var(--primary);transition:width .3s"></div>
						</div>
						<p id="sbupd-step-text" style="margin-top:8px"></p>
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
							<p style="color:var(--t-muted)">Aucune mise à jour à annuler. Seule la dernière mise à jour faite depuis cette page peut être annulée, une seule fois.</p>
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
						<p style="color:var(--t-muted)">Sauvegardes chiffrées (libsodium, clé de sbdbconfig.php), droits 600, une seule gardée. Mot de passe redemandé avant toute mise à jour ou retour arrière ; chaque opération est notée dans le journal des connexions. Ces sauvegardes servent à annuler une mise à jour, pas à remplacer les sauvegardes externes du serveur.</p>
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

		<script src="{$smarty.const._AM_SITE_URL}assets/sbupdate.js?v={$smarty.const._AM_START_VERSION}"></script>

	{include file='sb_footer.tpl' page='false' pagef='false'}
