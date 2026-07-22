{* -------------- *}
{* --- SYSTEM --- *}	
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page page='false'}
	{* ---------------- End Headers --------------- *}
			
			{* ------------------------------------------------ *}
			{*       Write your own code after this line        *}
			{* ------------------------------------------------ *}
			
			{include file='system/settings_bar.tpl'}

			{* Notes full width *}
			<div class="grid">
                <section class="col-12">
					<div class="alert info">
						<span class="ico"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg></span>
						<div class="body">
							<strong>Si vous activez la configuration de la toolbar de CKEditor, celle-ci sera activée sur tous les éditeurs CKEditor présents dans les formulaires de votre administration.<br>Elle prendra effet en remplacement des configurations BASIC, SIMPLE, FULL.</strong>
							<br><br>
							Une fois que vous avez configuré votre toolbar, copiez la configuration dans le fichier suivant :<br>
							<i>{$smarty.const._AM_SITE_DIR}inc/admin/<strong>ckeditor.php</strong></i>
						</div>
					</div>
                </section>
            </div>
            <!-- /.grid -->

			<div class="grid">
                <section class="col-12 card">
                    <div class="card-head">
						<div class="card-title-wrap">
							<span class="eyebrow">CKEditor</span>
							<h2 class="card-title">{$legend_add_edit}</h2>
						</div>
                    </div>
							{* Afficher le formulaire ADD/EDIT *}
							{include_php file='form.php'}
                </section>
            </div>
            <!-- /.grid -->

			{* Notes 12 col *}
			<div class="grid">
                <section class="col-12 card">
                    <div class="card-head">
						<div class="card-title-wrap">
							<span class="eyebrow">CKEditor</span>
							<h2 class="card-title">Toolbar Configurator</h2>
						</div>
                    </div>
							<iframe src="inc/js/editor/ckeditor/samples/toolbarconfigurator/index.html#basic" width="100%" height="1000px" style="border: none;"></iframe>
                </section>
            </div>
            <!-- /.grid -->

		<!-- ------------------------------------------------------------ -->
		<!-- Page-Level Scripts - Use this space this write your own code -->
		<!-- ------------------------------------------------------------ -->
		<script>
		$(document).ready(function() {
			// Your own code

		});
		</script>

	{include file='sb_footer.tpl' page='false' pagef='false'}