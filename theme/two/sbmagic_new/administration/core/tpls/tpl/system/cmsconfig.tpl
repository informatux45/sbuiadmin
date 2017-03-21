{* -------------- *}
{* --- MODULE --- *}	
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page}
	{* ---------------- End Headers --------------- *}
			
		{* ------------------------------------------------ *}
		{*       Write your own code after this line        *}
		{* ------------------------------------------------ *}
		
		{include file='system/cmsconfig_bar.tpl'}
				
        {if $action_edit != 'fonts' && $action_edit != 'seo'}

			<div class="row">
		
		        <div class="col-lg-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span class="fa fa-gears fa-fw"></span> <strong>{$legend_add_edit}</strong>
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

                <div class="col-lg-4">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <span class="fa fa-exclamation-circle fa-fw"></span> <strong>AIDE</strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
							
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
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-4 -->

            </div>
            <!-- /.row -->
	
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
	
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<span class="fa fa-gears fa-fw"></span> <strong>{$legend_add_edit}</strong>
					</div>
					<!-- /.panel-heading -->
					<div class="panel-body">
						Placer le tag suivant dans le HEADER de votre thème pour activer l'utilisation des Google Fonts : <span style='font-weight: bold; color: green;'>&lbrace;insert name="sbGetFonts"&rbrace;</span>
					</div>
					<!-- /.panel-body -->
				</div>
				<!-- /.panel -->
			</div>
			<!-- /.col-lg-12 -->
		</div>
		<!-- /.row -->
		
		<div class="row">
			
		        <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span class="fa fa-google fa-fw"></span> <strong>GOOGLE FONT SELECTOR</strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
							<input id="font" type="text" />
							
							&nbsp;&nbsp;
							
							<select id="select" class="form-control">
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
							
							<select id="style" class="form-control">
								<option value="">Style</option>
								<option value="normal">normal</option>
								<option value="italic">italic</option>
								<option value="oblique">oblique</option>
							</select>
							
							&nbsp;&nbsp;
							
							<select id="size" class="form-control">
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
							
							<input id="font-generator-button" type="text" class="form-control" value=''>
							&nbsp;&nbsp;
							<button type="button" class="btn btn-outline btn-success" onclick="appendChildGenerator()">insérer</button>
							
							<hr />
							
							<u>Usage en CSS :</u><p><pre>font-family: '<span id="font-usage-css"></span>', sans-serif;</pre></p>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-8 -->
				
		        <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span class="fa fa-font fa-fw"></span> <strong>Modifier le code des fonts (polices)</strong>
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
                <!-- /.col-lg-4 -->
			
		</div>
		<!-- /.row -->
		
		<link rel="stylesheet" type="text/css" href="inc/plugins/googlefonts/fontselect.css" />
		<style>
			select.form-control { width: 100px; float: right; }
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
	
		<div class="row">

			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<span class="fa fa-external-link fa-fw"></span> <strong>Liens</strong>
					</div>
					<!-- /.panel-heading -->
					<div class="panel-body">
						<button type="button" class="btn btn-success" onclick="location.href='https://www.google.com/webmasters/tools/home?hl=fr'">Webmaster TOOLS</button>
						&nbsp;&nbsp;
						<button type="button" class="btn btn-success" onclick="location.href='https://www.google.com/webmasters/tools/submit-url'">Google ADDURL</button>
						&nbsp;&nbsp;
						<button type="button" class="btn btn-success" onclick="location.href='https://developers.google.com/speed/libraries/'">Google Hosted Libraries</button>
						&nbsp;&nbsp;
						<button type="button" class="btn btn-success" onclick="location.href='https://console.developers.google.com/apis/library?hl=FR'">Console Developers</button>
					</div>
					<!-- /.panel-body -->
				</div>
				<!-- /.panel -->
			</div>
			<!-- /.col-lg-12 -->

			<div class="col-lg-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<span class="fa fa-lightbulb-o fa-fw"></span> <strong>{$legend_add_edit}</strong>
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
			<!-- /.col-lg-6 -->

			<div class="col-lg-6">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<span class="fa fa-sitemap fa-fw"></span> <strong>Sitemap</strong>
					</div>
					<!-- /.panel-heading -->
					<div class="panel-body">
						<p>
							<button type="button" class="btn btn-outline btn-primary btn-lg" onclick="sbSeoSitemap()">Générer le sitemap.xml</button>
							<span id="seo-sitemap-loader" style="visibility: hidden;">&nbsp;&nbsp;&nbsp;<img src="img/loader.gif" alt="" /><i>&nbsp;&nbsp;Création en cours...</i></span>
						</p>
						<br>
						<div class="form-group">
							<label for="">Sitemap générateur (Résultat)</label><br>
							<textarea id="seo-sitemap" style="height: 177px !important;" name="seo_sitemap" class="form-control" disabled="disabled"></textarea>
						</div>
					</div>
					<!-- /.panel-body -->
				</div>
				<!-- /.panel -->
			</div>
			<!-- /.col-lg-6 -->

		</div>
		<!-- /.row -->
		
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
			
	{include file='sb_footer.tpl'}

