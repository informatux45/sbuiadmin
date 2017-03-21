{* -------------- *}
{* --- SYSTEM --- *}	
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page}
	{* ---------------- End Headers --------------- *}
			
			{* ------------------------------------------------ *}
			{*       Write your own code after this line        *}
			{* ------------------------------------------------ *}

			{* Notes full width *}
			<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <i class="fa fa-info-circle fa-fw"></i> Information
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
							{* HTML Text Formatted *}
							<p style="color: red;"><strong>Si vous activez la configuration de la toolbar de CKEditor, celle-ci sera activée sur tous les éditeurs CKEditor présents dans les formulaires de votre administration.<br>Elle prendra effet en remplacement des configurations BASIC, SIMPLE, FULL.</strong></p>
							Une fois que vous avez configuré votre toolbar, copiez la configuration dans le fichier suivant :<br>
							<i>{$smarty.const._AM_SITE_DIR}inc/admin/<strong>ckeditor.php</strong></i>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
			
			<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <i class="fa fa-cubes fa-fw"></i> {$legend_add_edit}
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

			{* Notes 12 col *}
			<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-cube fa-fw"></i> Toolbar Configurator
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
							<iframe src="inc/js/editor/ckeditor/samples/toolbarconfigurator/index.html#basic" width="100%" height="1000px" style="border: none;"></iframe>
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
			// Your own code

		});
		</script>

	{include file='sb_footer.tpl'}