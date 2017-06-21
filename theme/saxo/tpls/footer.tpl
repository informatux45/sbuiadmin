
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
                        <strong>{$sb_site_title|default:""}</strong><br />
                        {$sb_theme_infos.theme_infos_address|@nl2br}
						<br />
						{$sb_theme_infos.theme_infos_email|escape:'mail'}
                    </address>
                    <ul class="social-icons">
						{if $sb_theme_infos.theme_infos_facebook}<a href="{$sb_theme_infos.theme_infos_facebook}" class="fa-stack fa-2x" aria-hidden="true">
							<i class="fa fa-square-o fa-stack-2x"></i>
							<i class="fa fa-facebook fa-stack-1x fa-inverse"></i>
						</a>{/if}
						{if $sb_theme_infos.theme_infos_twitter}<a href="{$sb_theme_infos.theme_infos_twitter}" class="fa-stack fa-2x" aria-hidden="true">
							<i class="fa fa-square-o fa-stack-2x"></i>
							<i class="fa fa-twitter fa-stack-1x fa-inverse"></i>
						</a>{/if}
						{if $sb_theme_infos.theme_infos_google_plus}<a href="{$sb_theme_infos.theme_infos_google_plus}" class="fa-stack fa-2x" aria-hidden="true">
							<i class="fa fa-square-o fa-stack-2x"></i>
							<i class="fa fa-google fa-stack-1x fa-inverse"></i>
						</a>{/if}
						{if $sb_theme_infos.theme_infos_pinterest}<a href="{$sb_theme_infos.theme_infos_pinterest}" class="fa-stack fa-2x" aria-hidden="true">
							<i class="fa fa-square-o fa-stack-2x"></i>
							<i class="fa fa-pinterest fa-stack-1x fa-inverse"></i>
						</a>{/if}
						{if $sb_theme_infos.theme_infos_instagram}<a href="{$sb_theme_infos.theme_infos_instagram}" class="fa-stack fa-2x" aria-hidden="true">
							<i class="fa fa-square-o fa-stack-2x"></i>
							<i class="fa fa-instagram fa-stack-1x fa-inverse"></i>
						</a>{/if}
						{if $sb_theme_infos.theme_infos_skype}<a href="{$sb_theme_infos.theme_infos_skype}" class="fa-stack fa-2x" aria-hidden="true">
							<i class="fa fa-square-o fa-stack-2x"></i>
							<i class="fa fa-skype fa-stack-1x fa-inverse"></i>
						</a>{/if}
						{if $sb_theme_infos.theme_infos_viadeo}<a href="{$sb_theme_infos.theme_infos_viadeo}" class="fa-stack fa-2x" aria-hidden="true">
							<i class="fa fa-square-o fa-stack-2x"></i>
							<i class="fa fa-viadeo fa-stack-1x fa-inverse"></i>
						</a>{/if}
						{if $sb_theme_infos.theme_infos_vimeo}<a href="{$sb_theme_infos.theme_infos_vimeo}" class="fa-stack fa-2x" aria-hidden="true">
							<i class="fa fa-square-o fa-stack-2x"></i>
							<i class="fa fa-vimeo fa-stack-1x fa-inverse"></i>
						</a>{/if}
						{if $sb_theme_infos.theme_infos_youtube}<a href="{$sb_theme_infos.theme_infos_youtube}" class="fa-stack fa-2x" aria-hidden="true">
							<i class="fa fa-square-o fa-stack-2x"></i>
							<i class="fa fa-youtube fa-stack-1x fa-inverse"></i>
						</a>{/if}
						{if $sb_theme_infos.theme_infos_linkedin}<a href="{$sb_theme_infos.theme_infos_linkedin}" class="fa-stack fa-2x" aria-hidden="true">
							<i class="fa fa-square-o fa-stack-2x"></i>
							<i class="fa fa-linkedin fa-stack-1x fa-inverse"></i>
						</a>{/if}
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