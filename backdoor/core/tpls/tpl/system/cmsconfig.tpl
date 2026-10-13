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
				<span class="eyebrow">Configuration CMS</span>
				<h1 class="hero-title">
					{if $action_edit == 'css'}CSS
					{elseif $action_edit == 'javascript'}Javascript
					{elseif $action_edit == 'comingsoon'}Maintenance
					{elseif $action_edit == 'multilang'}Multilangue
					{elseif $action_edit == 'plugins'}Plugins
					{elseif $action_edit == 'seo'}SEO
					{else}Header / Footer
					{/if}
				</h1>
				<p class="hero-sub">
					{if $action_edit == 'css'}Ajoutez du CSS personnalisé injecté dans le header du site.
					{elseif $action_edit == 'javascript'}Ajoutez du code Javascript personnalisé injecté dans le site.
					{elseif $action_edit == 'comingsoon'}Configurez la page affichée en mode maintenance.
					{elseif $action_edit == 'multilang'}Gérez les langues disponibles sur le site.
					{elseif $action_edit == 'plugins'}Activez les plugins JS/CSS tiers du site.
					{elseif $action_edit == 'seo'}Outils de référencement et génération du sitemap.
					{else}Personnalisez le header et le footer du site.
					{/if}
				</p>
			</div>
			<div class="hero-actions">
				<div class="dd-wrap">
					<button class="btn btn--outline-primary" data-dropdown aria-label="Sections CMS config">
						{if $action_edit == 'css'}CSS
						{elseif $action_edit == 'javascript'}Javascript
						{elseif $action_edit == 'comingsoon'}Maintenance
						{elseif $action_edit == 'multilang'}Multilangue
						{elseif $action_edit == 'plugins'}Plugins
						{elseif $action_edit == 'seo'}SEO
						{else}Header / Footer
						{/if}
						<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
					</button>
					<div class="dd-menu" role="menu">
						<a class="dd-menu-item" href="index.php?p=cmsconfig"{if $action_edit == 'headerfooter'} style="color:var(--primary);font-weight:600"{/if}>Header / Footer</a>
						<a class="dd-menu-item" href="index.php?p=cmsconfig&op=css"{if $action_edit == 'css'} style="color:var(--primary);font-weight:600"{/if}>CSS</a>
						<a class="dd-menu-item" href="index.php?p=cmsconfig&op=javascript"{if $action_edit == 'javascript'} style="color:var(--primary);font-weight:600"{/if}>Javascript</a>
						<a class="dd-menu-item" href="index.php?p=cmsconfig&op=comingsoon"{if $action_edit == 'comingsoon'} style="color:var(--primary);font-weight:600"{/if}>Maintenance</a>
						<a class="dd-menu-item" href="index.php?p=cmsconfig&op=multilang"{if $action_edit == 'multilang'} style="color:var(--primary);font-weight:600"{/if}>Multilangue</a>
						<a class="dd-menu-item" href="index.php?p=cmsconfig&op=plugins"{if $action_edit == 'plugins'} style="color:var(--primary);font-weight:600"{/if}>Plugins</a>
						<a class="dd-menu-item" href="index.php?p=cmsconfig&op=seo"{if $action_edit == 'seo'} style="color:var(--primary);font-weight:600"{/if}>SEO</a>
					</div>
				</div>
			</div>
		</section>

        {if $action_edit != 'seo'}

			<div class="grid">

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
                    <div class="card">
                        <div class="card-head">
							<div class="card-title-wrap">
								<h2 class="card-title" style="display:flex;align-items:center;gap:6px"><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg> Aide</h2>
							</div>
                        </div>
							{if $action_edit == 'headerfooter'}
								{$cmsconfig_headerfooter_help}							
							{elseif $action_edit == 'email'}
								{$cmsconfig_email_help}
							{elseif $action_edit == 'comingsoon'}
								{$cmsconfig_comingsoon_help}
							{elseif $action_edit == 'multilang'}
								{$cmsconfig_multilang_help}
							{elseif $action_edit == 'plugins'}
								{$cmsconfig_plugins_help}
							{else}
								{$cmsconfig_help}
							{/if}

                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col-4 -->

            </div>
            <!-- /.grid -->
	
		<!-- ------------------------------------------------------------ -->
		<!-- Page-Level Scripts - Use this space this write your own code -->
		<!-- ------------------------------------------------------------ -->
		<script src="inc/plugins/ace/ace.js" type="text/javascript" charset="utf-8"></script>
		<script>
		$(document).ready(function() {
			var $editor = $('#code');
			if ($editor.length > 0) {
				var editor = ace.edit('code');
				editor.setTheme("ace/theme/textmate");
				editor.session.setMode("ace/mode/{$action_edit}");
				editor.getSession().setTabSize(4);
				editor.getSession().setUseWrapMode(true);
				editor.setShowPrintMargin(true);
				editor.setHighlightActiveLine(true);
				$editor.closest('form').submit(function() {
					var code = editor.getValue();
					$('input[name="code_hidden"]').val(code);
				});
			}
		});
		</script>
		
	{elseif $action_edit == 'seo'}
	
		<div class="grid">

			<section class="col-12 card">
				<div class="card-head">
					<div class="card-title-wrap">
						<h2 class="card-title">Liens</h2>
					</div>
				</div>
						<div style="display:flex;flex-wrap:wrap;gap:10px">
							<button type="button" class="btn btn--primary" onclick="window.open('https://www.google.com/webmasters/tools/home?hl=fr')">Webmaster TOOLS</button>
							<button type="button" class="btn btn--primary" onclick="window.open('https://www.google.com/webmasters/tools/submit-url')">Google ADDURL</button>
							<button type="button" class="btn btn--primary" onclick="window.open('https://developers.google.com/speed/libraries/')">Google Hosted Libraries</button>
							<button type="button" class="btn btn--primary" onclick="window.open('https://search.google.com/search-console?hl=fr')">Console Developers</button>
						</div>
			</section>

			<section class="col-6 card">
				<div class="card-head">
					<div class="card-title-wrap">
						<h2 class="card-title">{$legend_add_edit}</h2>
					</div>
				</div>
						{* Afficher le formulaire ADD/EDIT *}
						{include_php file='form.php'}
			</section>

			<section class="col-6 card">
				<div class="card-head">
					<div class="card-title-wrap">
						<h2 class="card-title">Sitemap</h2>
					</div>
				</div>
						<p>
							<button type="button" class="btn btn--primary" onclick="sbSeoSitemap()">Générer le sitemap.xml</button>
							<span id="seo-sitemap-loader" style="visibility: hidden;">&nbsp;&nbsp;&nbsp;<img src="img/loader.gif" alt="" /><i>&nbsp;&nbsp;Création en cours...</i></span>
						</p>
						<div class="field">
							<label class="field-label" for="seo-sitemap">Sitemap générateur (Résultat)</label>
							<textarea id="seo-sitemap" class="textarea" style="height: 177px !important;" name="seo_sitemap" disabled="disabled"></textarea>
						</div>
			</section>

		</div>
		<!-- /.grid -->
		
		<script>
			function sbSeoSitemap() {
				// --- Initialization
				$("#seo-sitemap-loader").css('visibility','visible');
				$("#seo-sitemap").html('');
				// --- Assign handlers immediately after making the request,
				// --- and remember the jqXHR object for this request
				$.ajax({
					method: "POST",
					url: "{$smarty.const._AM_SITE_URL}inc/plugins/sitemap/sitemap_generator.php",
					data: {}
				})
				.fail(function() {
					sbToast("Erreur sitemap génération !!", 'error');
				})
				.always(function(data) {
					$("#seo-sitemap-loader").css('visibility','hidden');
					$("#seo-sitemap").html(data);
					sbToast("Génération sitemap.xml terminée\nVérifier la log (Résultat)", 'success');
				});
			}
		</script>
	
	{/if}
			
	{include file='sb_footer.tpl' page='false' pagef='false'}

