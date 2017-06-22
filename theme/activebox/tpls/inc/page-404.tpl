

	<!-- Page Content --> 
    <section {insert name=sbGetSectionClassId class="features section" evenid="features" op="`$smarty.get.op`" page="`$page_id`" id="`$smarty.get.id`" ti="`$sb_title`"}>
        <div class="container">
            <div class="row">
				
				<h1>{$sb_pages_title}</h1>
				<img class="error-404-image group" src="{$smarty.const.SB_THEME_URL}images/features/404.png" title="Error 404" alt="404" />
				<div class="error-404-text group">
					<div class="row">
						<p class="feature">
							<br>
							{$smarty.const._CMS_GLOBAL_404|@sprintf:"{$smarty.const.SB_URL}"}
						</p>
						
						<div class="input-append">
							<form method="get" id="searchform" action="#">
								<div class="input-group col-md-4 col-sm-12 text-left">
									<input id="inlineFormInputGroup" class="form-control" placeholder="{$smarty.const._CMS_GLOBAL_SEARCH}" type="search">
									<div class="input-group-addon"><i class="fa fa-search" aria-hidden="true"></i></div>
								</div>
							</form>
						</div>
					</div>
				</div>

            </div>
        </div>
    </section><!-- Page Content -->