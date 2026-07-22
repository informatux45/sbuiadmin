{* -------------- *}
{* --- MODULE --- *}	
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page page='false'}
	{* ---------------- End Headers --------------- *}
			
		{* ------------------------------------------------ *}
		{*       Write your own code after this line        *}
		{* ------------------------------------------------ *}
		
			{include file='system/settings_bar.tpl'}

			<div class="grid">

		        <section class="col-12 card">
                    <div class="card-head">
						<div class="card-title-wrap">
							<span class="eyebrow">Maintenance</span>
							<h2 class="card-title">Vider les caches FRONT & ADMIN</h2>
						</div>
                    </div>
							{* Afficher le formulaire ADD/EDIT *}
							{include_php file='form.php'}
                </section>

            </div>
            <!-- /.grid -->
	
		<!-- ------------------------------------------------------------ -->
		<!-- Page-Level Scripts - Use this space this write your own code -->
		<!-- ------------------------------------------------------------ -->
		<script>
		$(document).ready(function() {

		});
		</script>
			
	{include file='sb_footer.tpl' page='false' pagef='false'}

