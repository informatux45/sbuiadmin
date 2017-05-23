

				<!-- START PRIMARY -->
				<div id="primary" class="sidebar-{$sidebar|default:"no"}">
					
					 <!-- START INNER GROUP -->
				    <div {insert name=sbGetSectionClassId class="inner group" evenid="" op="`$smarty.get.op`" page="`$page_id`" id="`$smarty.get.id`" ti="`$sb_title`"}>
						
				        <!-- START CONTENT -->
				        <div id="content-page" class="content group">
							{insert name="sbGetContentCms" o1="$page_view" o2="$module_view"}
				        </div>
				        <!-- END CONTENT -->
				        
						{if $sidebar != "no"}
				        <!-- START SIDEBAR -->
				        <div class="sidebar group">
							{insert name="sbGetContentCms" o1="$page_view_blocks" o2="$module_view_blocks"}
				        </div>
				        <!-- END SIDEBAR -->
						{/if}

				    </div> <!-- END INNER GROUP -->
					
				</div>
				<!-- END PRIMARY -->