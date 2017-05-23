

                <!-- START PRIMARY -->
				<div id="primary" class="sidebar-no">
					
					 <!-- START INNER GROUP -->
				    <div class="inner group">
						
				        <!-- START CONTENT -->
				        <div id="content-index" class="content group">
				            <img class="error-404-image group" src="{$smarty.const.SB_THEME_URL}images/features/404.png" title="Error 404" alt="404" />
				            <div class="error-404-text group">
				                <p>
									{$smarty.const._CMS_GLOBAL_404|@sprintf:"{$smarty.const.SB_URL}"}
								</p>
								
				                <form method="get" id="searchform" action="#">
				                    <div><label class="screen-reader-text" for="s">Search for:</label>
				                        <input type="text" value="" name="s" id="s" />
				                        <input type="submit" id="searchsubmit" value="Search" />
				                    </div>
				                </form>
				            </div>
				        </div>
				        <!-- END CONTENT -->

				    </div> <!-- END INNER GROUP -->
				</div>
				<!-- END PRIMARY -->