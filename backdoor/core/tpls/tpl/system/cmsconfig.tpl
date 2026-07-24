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
					{elseif $action_edit == 'comingsoon'}Coming Soon
					{elseif $action_edit == 'multilang'}Multilangue
					{elseif $action_edit == 'plugins'}Plugins
					{elseif $action_edit == 'fonts'}Polices
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
					{elseif $action_edit == 'fonts'}Choisissez et générez le code d'intégration des Google Fonts.
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
						{elseif $action_edit == 'comingsoon'}Coming Soon
						{elseif $action_edit == 'multilang'}Multilangue
						{elseif $action_edit == 'plugins'}Plugins
						{elseif $action_edit == 'fonts'}Polices
						{elseif $action_edit == 'seo'}SEO
						{else}Header / Footer
						{/if}
						<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
					</button>
					<div class="dd-menu" role="menu">
						<a class="dd-menu-item" href="index.php?p=cmsconfig"{if $action_edit == 'headerfooter'} style="color:var(--primary);font-weight:600"{/if}>Header / Footer</a>
						<a class="dd-menu-item" href="index.php?p=cmsconfig&op=css"{if $action_edit == 'css'} style="color:var(--primary);font-weight:600"{/if}>CSS</a>
						<a class="dd-menu-item" href="index.php?p=cmsconfig&op=javascript"{if $action_edit == 'javascript'} style="color:var(--primary);font-weight:600"{/if}>Javascript</a>
						<a class="dd-menu-item" href="index.php?p=cmsconfig&op=comingsoon"{if $action_edit == 'comingsoon'} style="color:var(--primary);font-weight:600"{/if}>Coming Soon</a>
						<a class="dd-menu-item" href="index.php?p=cmsconfig&op=multilang"{if $action_edit == 'multilang'} style="color:var(--primary);font-weight:600"{/if}>Multilangue</a>
						<a class="dd-menu-item" href="index.php?p=cmsconfig&op=plugins"{if $action_edit == 'plugins'} style="color:var(--primary);font-weight:600"{/if}>Plugins</a>
						<a class="dd-menu-item" href="index.php?p=cmsconfig&op=fonts"{if $action_edit == 'fonts'} style="color:var(--primary);font-weight:600"{/if}>Polices</a>
						<a class="dd-menu-item" href="index.php?p=cmsconfig&op=seo"{if $action_edit == 'seo'} style="color:var(--primary);font-weight:600"{/if}>SEO</a>
					</div>
				</div>
			</div>
		</section>

        {if $action_edit != 'fonts' && $action_edit != 'seo'}

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
		
	{elseif $action_edit == 'fonts'}
	
		<div class="grid">
			<section class="col-12">
				<div class="alert info">
					<span class="ico"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg></span>
					<div class="body">Placer le tag suivant dans le HEADER de votre thème pour activer l'utilisation des Google Fonts : <span style='font-weight: bold; color: var(--success);'>&lbrace;insert name="sbGetFonts"&rbrace;</span></div>
				</div>
			</section>

		        <section class="col-6 card">
                    <div class="card-head">
						<div class="card-title-wrap">
							<h2 class="card-title">Google Font Selector</h2>
						</div>
                    </div>
							<input id="font" class="input" type="text" style="margin-bottom:10px" />

							<select id="select" class="select">
								<option value="">Weight</option>
								<option value="100">100</option>
								<option value="200">200</option>
								<option value="300">300</option>
								<option value="400">400</option>
								<option value="500">500</option>
								<option value="600">600</option>
								<option value="700">700</option>
								<option value="800">800</option>
								<option value="900">900</option>
							</select>
						
							&nbsp;&nbsp;
							
							<select id="style" class="select">
								<option value="">Style</option>
								<option value="normal">normal</option>
								<option value="italic">italic</option>
								<option value="oblique">oblique</option>
							</select>
							
							&nbsp;&nbsp;
							
							<select id="size" class="select">
								<option value="">Size</option>
								<option value="10px">10px</option>
								<option value="12px">12px</option>
								<option value="14px">14px</option>
								<option value="16px">16px</option>
								<option value="18px">18px</option>
								<option value="20px">20px</option>
								<option value="24px">24px</option>
								<option value="28px">28px</option>
								<option value="32px">32px</option>
								<option value="36px">36px</option>
								<option value="40px">40px</option>
								<option value="48px">48px</option>
							</select>
						
							<br><br>
							
							<textarea class="fonts-textarea">
Lorem Ipsum is simply dummy text of the printing and typesetting industry.
Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.
It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</textarea>
							<br><br>
							
							<hr />
							
							<input id="font-generator-button" type="text" class="input" value=''>
							&nbsp;&nbsp;
							<button type="button" class="btn btn--primary" onclick="appendChildGenerator()">insérer</button>
							
							<hr />
							
							<u>Usage en CSS :</u><p><pre>font-family: '<span id="font-usage-css"></span>', sans-serif;</pre></p>
                </section>

		        <section class="col-6 card">
                    <div class="card-head">
						<div class="card-title-wrap">
							<h2 class="card-title">Modifier le code des fonts (polices)</h2>
						</div>
                    </div>
							{* Afficher le formulaire ADD/EDIT *}
							{include_php file='form.php'}
                </section>

		</div>
		<!-- /.grid -->
		
		<link rel="stylesheet" type="text/css" href="inc/plugins/googlefonts/fontselect.css" />
		<style>
			select.select { width: 100px; float: right; }
			textarea.fonts-textarea { width: 100%; height: 200px !important; border: 0 !important; }
			input#font-generator-button { width: 370px; float: left; border: 1px solid #5cb85c; background-color: #d2ff9c }
			pre { background-color: #fffb97; }
		</style>
		<script src="inc/plugins/googlefonts/jquery.fontselect.js"></script>
		
		<script>
			function appendChildGenerator() {
				t = document.createTextNode($('#font-generator-button').val() + "\n"); 
				p = document.getElementById("fonts"); 
				p.appendChild(t);
			}
			
			$(function() {
				$('#font').fontselect().change(function(){
					// replace + signs with spaces for css
					var font = $(this).val().replace(/\+/g, ' ');
					// split font into family and weight
					font = font.split(':');
					// set family on textarea
					$('textarea.fonts-textarea').css('font-family', font[0]);
					// replace spaces signs with + for url
					var font_url = font[0].replace(/\s/g, '+');
					$('#font-generator-button').attr("value", '<link href="https://fonts.googleapis.com/css?family='+font_url+'" rel="stylesheet">');
					$('#font-usage-css').html(font[0]);
				});
				
				$('#select').on('change', function() {
					// set weight on textarea
					$('textarea.fonts-textarea').css('font-weight', $(this).val());            
				});
		
				$('#style').on('change', function() {
					// set style on textarea
					$('textarea.fonts-textarea').css('font-style', $(this).val());            
				});
		
				$('#size').on('change', function() {
					// set style on textarea
					$('textarea.fonts-textarea').css('font-size', $(this).val());            
				});
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
					alert( "Erreur sitemap génération !!" );
				})
				.always(function(data) {
					$("#seo-sitemap-loader").css('visibility','hidden');
					$("#seo-sitemap").html(data);
					alert( "Génération sitemap.xml terminée\nVérifier la log (Résultat)" );
				});
			}
		</script>
	
	{/if}
			
	{include file='sb_footer.tpl' page='false' pagef='false'}

