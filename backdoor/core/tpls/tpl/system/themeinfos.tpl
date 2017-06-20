{* -------------- *}
{* --- SYSTEM --- *}	
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page}
	{* ---------------- End Headers --------------- *}
			
			{* ------------------------------------------------ *}
			{*       Write your own code after this line        *}
			{* ------------------------------------------------ *}
			
			{include file='system/settings_bar.tpl'}
			
			{* Notes col lg 6 *}
			<div class="row">
                
				<div class="col-lg-7">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-magic fa-fw"></i> {$legend_add_edit}
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
							{* Afficher le formulaire EDIT *}
							{include_php file='form.php'}
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-7 -->
				
				<div class="col-lg-5">
					<div class="panel panel-default">
						<div class="panel-heading">
							<span class="fa fa-columns fa-fw"></span> <strong>Actions</strong>
						</div>
						<!-- /.panel-heading -->
						<div class="panel-body">
							<input type="submit" value="{$sb_form_submit_value}" class="btn btn-default btn-submit" form="{$sb_form_id}">
						</div>
						<!-- /.panel-body -->
					</div>
					<div class="panel panel-default">
						<div class="panel-heading">
							<span class="fa fa-columns fa-fw"></span> <strong>Aperçu du thème actuel</strong>
						</div>
						<!-- /.panel-heading -->
						<div class="panel-body">
							<div class="theme_view">
								<img id="img_theme_view" src="{$sb_theme_view}" title="" />
							</div>
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

	{include file='sb_footer.tpl'}