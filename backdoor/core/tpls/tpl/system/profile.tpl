{* -------------- *}
{* --- SYSTEM --- *}
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page page='false'}
	{* ---------------- End Headers --------------- *}

			{* ------------------------------------------------ *}
			{*       Write your own code after this line        *}
			{* ------------------------------------------------ *}

			<section class="hero">
				<div class="hero-text">
					<span class="eyebrow">Compte</span>
					<h1 class="hero-title">Mon profil</h1>
					<p class="hero-sub">Vos informations personnelles. Pour l'identifiant, l'email ou le statut de votre compte, contactez un administrateur.</p>
				</div>
			</section>

			<div class="grid">

				{* ------------------------------------ *}
				{* --- Carte photo + identité --------- *}
				{* ------------------------------------ *}
				<section class="col-4 card" style="display:flex;flex-direction:column;align-items:center;text-align:center;padding:0 20px 28px 20px;overflow:hidden">
					<div style="width:calc(100% + 40px);margin:0 -20px 0 -20px;height:76px;background:linear-gradient(135deg,var(--primary),var(--primary-light));flex-shrink:0"></div>
					<img src="{$profile.avatar_url}" alt="" style="width:140px;height:140px;margin-top:-58px;border-radius:16px;object-fit:cover;border:4px solid var(--bg-card);box-shadow:var(--shadow-card)">
					<h2 style="margin:14px 0 6px 0;font-size:19px;color:var(--t-base)">
						{$profile.prenom} {$profile.nom}
					</h2>
					<span style="font-size:13px;font-weight:600;padding:3px 11px;border-radius:20px;margin-bottom:10px;{if $sbuiadmin_user_type == 'admin'}background:var(--primary-soft);color:var(--primary){else}background:var(--bg-muted);color:var(--t-muted){/if}">
						{if $sbuiadmin_user_type == 'admin'}Admin{else}User{/if}
					</span>
					<p style="margin:0 0 18px 0;color:var(--t-muted);font-size:14px">
						{if $profile.fonction}{$profile.fonction}{if $profile.profession} &middot; {$profile.profession}{/if}{elseif $profile.profession}{$profile.profession}{else}&nbsp;{/if}
					</p>
					<a class="btn btn--primary" href="{$smarty.const._AM_SITE_URL}index.php?p=users&a=edit&id={$sbuiadmin_user_id}">
						Modifier mon profil
					</a>
				</section>

				{* ------------------------------------ *}
				{* --- Colonne infos ------------------- *}
				{* ------------------------------------ *}
				<div class="col-8" style="display:flex;flex-direction:column;gap:20px">

					<section class="card">
						<div class="card-head">
							<div class="card-title-wrap">
								<h2 class="card-title">Informations de contact</h2>
							</div>
						</div>
						<div class="grid">
							<div class="col-6" style="display:flex;align-items:center;gap:12px">
								<span class="btn--icon" style="background:var(--primary-soft);color:var(--primary);pointer-events:none">
									<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
								</span>
								<div>
									<span style="display:block;font-size:12px;color:var(--t-muted)">Téléphone</span>
									<span style="display:flex;align-items:center;gap:6px;color:var(--t-base)">
										{if $profile.telephone}
											{$profile.telephone}
											<button type="button" class="sb-copy-btn" data-copy="{$profile.telephone}" data-tooltip="Copier" style="background:none;border:0;cursor:pointer;color:var(--t-muted);padding:2px;display:inline-flex;flex-shrink:0">
												<svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
											</button>
										{else}
											<em style="color:var(--t-light);font-style:italic">Non renseigné</em>
										{/if}
									</span>
								</div>
							</div>
							<div class="col-6" style="display:flex;align-items:center;gap:12px">
								<span class="btn--icon" style="background:var(--primary-soft);color:var(--primary);pointer-events:none">
									<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
								</span>
								<div>
									<span style="display:block;font-size:12px;color:var(--t-muted)">E-mail</span>
									<span style="display:flex;align-items:center;gap:6px;color:var(--t-base)">
										{$profile.email}
										<button type="button" class="sb-copy-btn" data-copy="{$profile.email}" data-tooltip="Copier" style="background:none;border:0;cursor:pointer;color:var(--t-muted);padding:2px;display:inline-flex;flex-shrink:0">
											<svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
										</button>
									</span>
								</div>
							</div>
							<div class="col-6" style="display:flex;align-items:center;gap:12px;margin-top:20px">
								<span class="btn--icon" style="background:var(--primary-soft);color:var(--primary);pointer-events:none">
									<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
								</span>
								<div>
									<span style="display:block;font-size:12px;color:var(--t-muted)">Fonction / poste</span>
									<span style="color:var(--t-base)">
										{if $profile.fonction}{$profile.fonction}{else}<em style="color:var(--t-light);font-style:italic">Non renseigné</em>{/if}
									</span>
								</div>
							</div>
							<div class="col-6" style="display:flex;align-items:center;gap:12px;margin-top:20px">
								<span class="btn--icon" style="background:var(--primary-soft);color:var(--primary);pointer-events:none">
									<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
								</span>
								<div>
									<span style="display:block;font-size:12px;color:var(--t-muted)">Dernière connexion</span>
									<span style="color:var(--t-base)">{$profile.last_login}</span>
								</div>
							</div>
						</div>
					</section>

					{if $profile.centres_interet || $profile.infos_complementaires}
					<section class="card">
						<div class="card-head">
							<div class="card-title-wrap">
								<h2 class="card-title">À propos</h2>
							</div>
						</div>
						{if $profile.centres_interet}
						<div style="margin-bottom:{if $profile.infos_complementaires}18px{else}0{/if}">
							<span style="display:block;font-size:12px;color:var(--t-muted);text-transform:uppercase;letter-spacing:.03em;margin-bottom:6px">Centres d'intérêt</span>
							<p style="margin:0;color:var(--t-base);white-space:pre-line">{$profile.centres_interet}</p>
						</div>
						{/if}
						{if $profile.infos_complementaires}
						<div>
							<span style="display:block;font-size:12px;color:var(--t-muted);text-transform:uppercase;letter-spacing:.03em;margin-bottom:6px">Infos complémentaires</span>
							<p style="margin:0;color:var(--t-base);white-space:pre-line">{$profile.infos_complementaires}</p>
						</div>
						{/if}
					</section>
					{/if}

				</div>
				<!-- /.col-8 -->

            </div>
            <!-- /.grid -->

		<!-- ------------------------------------------------------------ -->
		<!-- Page-Level Scripts - Use this space this write your own code -->
		<!-- ------------------------------------------------------------ -->
		<script>
		$(document).ready(function() {
			// Copier au clic (téléphone/e-mail) - Clipboard API avec repli
			// textarea+execCommand pour les contextes sans HTTPS.
			$(document).on('click', '.sb-copy-btn', function () {
				var value = $(this).attr('data-copy');
				var $btn = $(this);
				var original = $btn.html();
				var showCopied = function () {
					$btn.html('<svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg>');
					setTimeout(function () { $btn.html(original); }, 1200);
				};
				if (navigator.clipboard && navigator.clipboard.writeText) {
					navigator.clipboard.writeText(value).then(showCopied);
				} else {
					var $tmp = $('<textarea>').val(value).css('position', 'fixed').css('left', '-9999px').appendTo('body');
					$tmp.select();
					document.execCommand('copy');
					$tmp.remove();
					showCopied();
				}
			});
		});
		</script>

	{include file='sb_footer.tpl' page='false' pagef='false'}
