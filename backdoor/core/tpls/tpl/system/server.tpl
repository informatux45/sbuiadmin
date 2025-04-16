{* -------------- *}
{* --- MODULE --- *}	
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page}
	{* ---------------- End Headers --------------- *}
			
		{* ------------------------------------------------ *}
		{*       Write your own code after this line        *}
		{* ------------------------------------------------ *}
		
			{include file='system/settings_bar.tpl'}
			
			<style></style>

			<div class="row">
		
		        <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span class="fa fa-cubes fa-fw"></span> <strong>Données serveur en temps réel</strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">

							<iframe src="server/status/dashboard.html" width="100%" height="650px" style="border: none;"></iframe>

                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->

            </div>
            <!-- /.row -->
	
		<!-- ------------------------------------------------------------ -->
		<!-- Page-Level Scripts - Use this space this write your own code -->
		<!-- ------------------------------------------------------------ -->
		<script>
		$(document).ready(function() {

		});
		</script>
			
	{include file='sb_footer.tpl'}

