{* -------------- *}
{* --- SYSTEM --- *}	
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page}
	{* ---------------- End Headers --------------- *}
			
			{* ------------------------------------------------ *}
			{*       Write your own code after this line        *}
			{* ------------------------------------------------ *}

			{* Notes col lg 6 *}
			<div class="row">
				
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-folder-open fa-fw"></i> Vos fichiers
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
							{* HTML Text Formatted *}
							{$filetree}
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-6 -->

                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-eye fa-fw"></i> Visualiser
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
							{* HTML Text Formatted *}
							<div id="filetree_view"></div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-6 -->

            </div>
            <!-- /.row -->

		<!-- ------------------------------------------------------------ -->
		<!-- Page-Level Scripts - Use this space this write your own code -->
		<!-- ------------------------------------------------------------ -->
		<script>
		$(document).ready(function() {
			// Your own code

		});
		</script>

	{include file='sb_footer.tpl'}