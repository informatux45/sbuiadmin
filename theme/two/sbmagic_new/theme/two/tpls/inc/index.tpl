
		{if $sb_page_headpage}
        <div class="banner-bg" id="top">
			{insert name="sbDoShortcode" code="[CS id=`$sb_page_headpage` name=sbslider]"}
        </div>
		{/if}

        <!-- MAIN CONTENT -->
        <div class="main-content">
            <div class="fluid-container">

                <div class="content-wrapper">
                
                    <!-- CONTENT -->
                    <div {insert name=sbGetSectionClassId class="page-section" evenid="" op="`$smarty.get.op`" page="`$page_id`" id="`$smarty.get.id`" ti="`$sb_title`"}>
						<div class="row">
							<div class="col-md-12">
								
								{insert name="sbGetContentCms" o1="$page_view" o2="$module_view"}
								
							</div>
						</div> <!-- #about -->
                    </div>
                
                    <hr>

                    <div class="row" id="footer">
                        <div class="col-md-12 text-center">
                            <p class="copyright-text">
								<div id="responsive-footer">
									 {insert name="sbGetConfig" id="header"}
								</div>
								{insert name="sbGetConfig" id="footer"}
							</p>
                        </div>
                    </div>

                </div>

            </div>
        </div>