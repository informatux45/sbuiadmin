
    <footer class="footer">
        <div class="footer-top">
            <div class="container">
                <div class="row">
                    <div class="footer-col col-md-4">
                        <h5>Adresse</h5>
                        <p>
							<strong>{$sb_site_title|default:""}</strong><br />
							{$sb_theme_infos.theme_infos_address|@nl2br}
							<br />
							{$sb_theme_infos.theme_infos_email|escape:'mail'}
						</p>
                    </div>
                    <div class="footer-col col-md-4">
                        <h5>Partage</h5>
                        <ul class="footer-share">
                            {if $sb_theme_infos.theme_infos_facebook}<li><a href="{$sb_theme_infos.theme_infos_facebook}"><i class="fa fa-facebook"></i></a></li>{/if}
                            {if $sb_theme_infos.theme_infos_twitter}<li><a href="{$sb_theme_infos.theme_infos_twitter}"><i class="fa fa-twitter"></i></a></li>{/if}
                            {if $sb_theme_infos.theme_infos_google_plus}<li><a href="#"><i class="fa fa-google-plus"></i></a></li>{/if}
                            {if $sb_theme_infos.theme_infos_pinterest}<li><a href="{$sb_theme_infos.theme_infos_pinterest}"><i class="fa fa-pinterest"></i></a></li>{/if}
                            {if $sb_theme_infos.theme_infos_instagram}<li><a href="{$sb_theme_infos.theme_infos_instagram}"><i class="fa fa-instagram"></i></a></li>{/if}
                            {if $sb_theme_infos.theme_infos_vimeo}<li><a href="{$sb_theme_infos.theme_infos_vimeo}"><i class="fa fa-vimeo"></i></a></li>{/if}
                            {if $sb_theme_infos.theme_infos_viadeo}<li><a href="{$sb_theme_infos.theme_infos_viadeo}"><i class="fa fa-viadeo"></i></a></li>{/if}
                            {if $sb_theme_infos.theme_infos_youtube}<li><a href="{$sb_theme_infos.theme_infos_youtube}"><i class="fa fa-youtube"></i></a></li>{/if}
                            {if $sb_theme_infos.theme_infos_skype}<li><a href="{$sb_theme_infos.theme_infos_skype}"><i class="fa fa-skype"></i></a></li>{/if}
                            {if $sb_theme_infos.theme_infos_linkedin}<li><a href="{$sb_theme_infos.theme_infos_linkedin}"><i class="fa fa-linkedin"></i></a></li>{/if}

                        </ul>
                    </div>
                    <div class="footer-col col-md-4">
                        <h5>A propos de nous</h5>
                        {insert name="sbGetConfig" id="header"}
                    </div>
                </div>
            </div>
        </div><!-- footer top -->
        <div class="footer-bottom">
            <div class="container">
                <div class="col-md-12">
                    <p>{insert name="sbGetConfig" id="footer"}</p>
                </div>
            </div>
        </div>
    </footer><!-- footer -->

    <script src="{$smarty.const.SB_THEME_URL}js/bootstrap.min.js"></script>
    <script src="{$smarty.const.SB_THEME_URL}js/jquery.flexslider-min.js"></script>
    <script src="{$smarty.const.SB_THEME_URL}js/jquery.fancybox.pack.js"></script>
    <script src="{$smarty.const.SB_THEME_URL}js/jquery.waypoints.min.js"></script>
    <script src="{$smarty.const.SB_THEME_URL}js/retina.min.js"></script>
    <script src="{$smarty.const.SB_THEME_URL}js/modernizr.js"></script>
    <script src="{$smarty.const.SB_THEME_URL}js/main.js"></script>

	{insert name="sbGetPlugins"}

</body>
</html>