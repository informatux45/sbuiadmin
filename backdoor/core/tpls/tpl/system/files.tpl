{* -------------- *}
{* --- SYSTEM --- *}	
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page page='false'}
	{* ---------------- End Headers --------------- *}
			
			{* ------------------------------------------------ *}
			{*       Write your own code after this line        *}
			{* ------------------------------------------------ *}

			{* Notes col lg 6 *}
			<div class="grid">

                <section class="col-6 card">
                    <div class="card-head">
						<div class="card-title-wrap">
							<span class="eyebrow">Fichiers</span>
							<h2 class="card-title">Vos fichiers</h2>
						</div>
                    </div>
							{* HTML Text Formatted *}
							{$filetree}
                </section>

                <section class="col-6 card">
                    <div class="card-head">
						<div class="card-title-wrap">
							<span class="eyebrow">Fichiers</span>
							<h2 class="card-title">Visualiser</h2>
						</div>
                    </div>
							{* HTML Text Formatted *}
							<div id="filetree_view"></div>
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