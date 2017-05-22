
	</div> <!-- End Container -->

    <!-- Footer Area -->
	<div class="footer-container">
    	<div class="container">
        	<div class="row footer-row">
                <div class="span3 footer-col">
                    <h5>A propos</h5>
					<img class="footer-logo" src="{$smarty.const.SB_THEME_URL}img/logo.png" title="{$sb_site_title}" alt="{$sb_site_title}" />
					<br /><br />
                    <address>
                        <strong>Big Society</strong><br />
                        123 Main St, Suite 500<br />
                        New York, NY 12345<br />
                    </address>
                    <ul class="social-icons">
                        <li><a href="#" class="social-icon facebook"></a></li>
                        <li><a href="#" class="social-icon twitter"></a></li>
                        <li><a href="#" class="social-icon dribble"></a></li>
                        <li><a href="#" class="social-icon rss"></a></li>
                        <li><a href="#" class="social-icon forrst"></a></li>
                    </ul>
                </div>
                <div class="span3 footer-col">
                    <h5>Tweets</h5>
                    <ul>
                        <li><a href="#">@room122</a> Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
                        <li><a href="#">@room122</a> In interdum felis fermentum ipsum molestie sed porttitor ligula rutrum. Morbi blandit ultricies ultrices.</li>
                        <li><a href="#">@room122</a> Vivamus nec lectus sed orci molestie molestie. Etiam mattis neque eu orci rutrum aliquam.</li>
                    </ul>
                </div>
                <div class="span3 footer-col">
                    <h5>News</h5>
					{insert name="sbDoShortcode" code="[CS name=sbnews_blocks_recent count=3]"}
                </div>
                <div class="span3 footer-col">
                    <h5>Photos</h5>
                    <ul class="img-feed">
						{for $img_feed=1 to 12}
							<li>
								<a href="#"><img src="{$smarty.const.SB_THEME_URL}img/gallery/flickr-img-1.jpg" alt="Image Feed"></a>
							</li>
						{/for}
                    </ul>
                </div>
            </div>

            <div class="row"><!-- Begin Sub Footer -->
                <div class="span12 footer-col footer-sub">
                    <div class="row no-margin">
                        <div class="span6">
							<span class="left">
								{insert name="sbGetConfig" id="footer"}
							</span>
						</div>
                        <div class="span6">
                            <span class="right">
								{insert name='sbGetMenuCms' mclass='nav_footer' mid='nav_footer' mtag='main_menu' mlang="`$smarty.session.lang`"}
                            </span>
                        </div>
                    </div>
                </div>
            </div><!-- End Sub Footer -->

        </div>
    </div><!-- End Footer --> 
    
    <!-- Scroll to Top -->  
    <div id="toTop" class="hidden-phone hidden-tablet">Back to Top</div>
	
	{insert name="sbGetPlugins"}
    
</body>
</html>