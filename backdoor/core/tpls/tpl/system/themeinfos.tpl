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
			
			{* Notes col lg 6 *}
			<div class="grid">

				<section class="col-8 card">
                    <div class="card-head">
						<div class="card-title-wrap">
							<span class="eyebrow">Thème</span>
							<h2 class="card-title">{$legend_add_edit}</h2>
						</div>
                    </div>
							{* Afficher le formulaire EDIT *}
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
								<span class="eyebrow">Thème</span>
								<h2 class="card-title">Aperçu du thème actuel</h2>
							</div>
						</div>
							<div class="theme_view">
								<img id="img_theme_view" src="{$sb_theme_view}" title="" />
							</div>
					</div>
					<!-- /.card -->
				</div>
				<!-- /.col-4 -->

            </div>
            <!-- /.grid -->

		<!-- ------------------------------------------------------------ -->
		<!-- Page-Level Scripts - Use this space this write your own code -->
		<!-- ------------------------------------------------------------ -->
		<script>
		$(document).ready(function() {
			// Your own code
			$("#theme_view").change(function () {
				var str = $( "select#theme_view option:selected" ).val();
				var new_theme_view = '{$smarty.const.SB_URL}theme/'+str+'/screenshot-index.jpg';
				if (str != '') {
					$('img#img_theme_view').attr('src', new_theme_view);
				} else {
					$('img#img_theme_view').attr('src', '{$smarty.const._AM_SITE_IMG_URL}theme-noview.jpg');
				}
			}).change();

		});
		</script>

	{include file='sb_footer.tpl' page='false' pagef='false'}