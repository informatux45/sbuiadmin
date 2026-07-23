{* -------------- *}
{* --- MODULE --- *}	
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page}
	{* ---------------- End Headers --------------- *}
			
		{* ------------------------------------------------ *}
		{*       Write your own code after this line        *}
		{* ------------------------------------------------ *}
		
			{include file='shared/shared-settings-hero.tpl'}
			
			<style></style>

			<div class="grid">

		        <section class="col-12 card">
                    <div class="card-head">
						<div class="card-title-wrap">
							<h2 class="card-title">Données serveur en temps réel</h2>
						</div>
                    </div>

							<iframe src="server/status/dashboard.html" width="100%" height="650px" style="border: none;"></iframe>

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
			
	{include file='sb_footer.tpl'}

