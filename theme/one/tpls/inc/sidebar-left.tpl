
				<!-- START PRIMARY -->
				<div id="primary" class="sidebar-left">
					
					 <!-- START INNER GROUP -->
				    <div class="inner group">
						
				        <!-- START CONTENT -->
				        <div id="content-page" class="content group">
							{insert name="sbGetContentCms" o1="$page_view" o2="$module_view"}
				        </div>
				        <!-- END CONTENT -->
				        
				        <!-- START SIDEBAR -->
				        <div class="sidebar group">
							{insert name="sbGetContentCms" o1="$page_view_blocks" o2="$module_view_blocks"}
				        </div>
				        <!-- END SIDEBAR -->

				    </div> <!-- END INNER GROUP -->
					
				</div>
				<!-- END PRIMARY -->