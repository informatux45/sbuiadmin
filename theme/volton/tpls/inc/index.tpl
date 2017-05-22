
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
								
								{if $sidebar == 'contact'}
									<!-- CONTACT -->
									<h4 class="widget-title">PLACE TO TALK WITH ME</h4>
									<p>Vestibulum ac iaculis erat, in semper dolor. Maecenas et lorem molestie, maximus justo dignissim, cursus nisl. Nullam at ante quis ex pharetra pulvinar quis id dolor. Integer lorem odio, euismod ut sem sit amet, imperdiet condimentum diam.</p>
									<form action="#" method="post" class="contact-form">
										<fieldset class="col-md-4 col-sm-6">
											<input type="text" id="your-name" placeholder="Your Name...">
										</fieldset>
										<fieldset class="col-md-4 col-sm-6">
											<input type="email" id="email" placeholder="Your Email...">
										</fieldset>
										<fieldset class="col-md-4 col-sm-12">
											<input type="text" id="your-subject" placeholder="Subject...">
										</fieldset>
										<fieldset class="col-md-12 col-sm-12">
											<textarea name="message" id="message" cols="30" rows="6" placeholder="Leave your message..."></textarea>
										</fieldset>
										<fieldset class="col-md-12 col-sm-12">
											<input type="submit" class="button big default" value="Send Message">
										</fieldset>
									</form>
								{/if}
								
							</div>
						</div> <!-- .row -->
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