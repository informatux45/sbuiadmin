				

        <div class="banner-bg" id="top">
			{insert name="sbDoShortcode" code="[CS id=404 name=sbslider]"}
        </div>

        <!-- MAIN CONTENT -->
        <div class="main-content">
            <div class="fluid-container">

                <div class="content-wrapper">
                
                    <!-- ABOUT -->
                    <div class="page-section{if $smarty.get.op} {$smarty.get.op}{/if}" id="page_{$smarty.get.id}">
						<div class="row">
							<div class="col-md-12">
									<p class="acenter">
										<img class="error-404-image group" src="{$smarty.const.SB_THEME_URL}img/features/404.png" title="Error 404" alt="404" />
										<br><br>
										{$smarty.const._CMS_GLOBAL_404|@sprintf:"{$smarty.const.SB_URL}"}
										<br><br>										
										<form method="post" action="#" class="search-form" style="margin: 0 auto; width: 50%;">
										  <div class="input-group">
											<input type="text" class="form-control" placeholder="Search">
											<div class="input-group-btn">
											  <button class="btn btn-default" type="submit">
												<i class="fa fa-search"></i>
											  </button>
											</div>
										  </div>
										</form>
										<br><br>
									</p>
								
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