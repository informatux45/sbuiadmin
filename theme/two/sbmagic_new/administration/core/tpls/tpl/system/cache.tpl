{* -------------- *}
{* --- MODULE --- *}	
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page}
	{* ---------------- End Headers --------------- *}
			
		{* ------------------------------------------------ *}
		{*       Write your own code after this line        *}
		{* ------------------------------------------------ *}

			<div class="row">
		
		        <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span class="fa fa-cubes fa-fw"></span> <strong>Vider les caches FRONT & ADMIN</strong>
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

