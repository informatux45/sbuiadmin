

				<!-- START COPYRIGHT -->
                <div id="copyright">
                    <div class="inner group">
                        <div class="left">
                            {insert name="sbGetConfig" id="footer"}
                        </div>
                        <div class="right">
                            {if $sb_theme_infos.theme_infos_facebook}<a href="{$sb_theme_infos.theme_infos_facebook}" class="socials-small facebook-small" title="Facebook">Facebook</a>{/if}
                            {if $sb_theme_infos.theme_infos_twitter}<a href="{$sb_theme_infos.theme_infos_twitter}" class="socials-small twitter-small" title="Twitter">Twitter</a>{/if}
                            {if $sb_theme_infos.theme_infos_skype}<a href="{$sb_theme_infos.theme_infos_skype}" class="socials-small skype-small" title="Skype">Skype</a>{/if}
                            {if $sb_theme_infos.theme_infos_google_plus}<a href="{$sb_theme_infos.theme_infos_google_plus}" class="socials-small google-small" title="Google +">Google +</a>{/if}
                            {if $sb_theme_infos.theme_infos_pinterest}<a href="{$sb_theme_infos.theme_infos_pinterest}" class="socials-small pinterest-small" title="Pinterest">Pinterest</a>{/if}
                            {if $sb_theme_infos.theme_infos_instagram}<a href="{$sb_theme_infos.theme_infos_instagram}" class="socials-small instagram-small" title="Instagram">Instagram</a>{/if}
                            {if $sb_theme_infos.theme_infos_vimeo}<a href="{$sb_theme_infos.theme_infos_vimeo}" class="socials-small vimeo-small" title="Vimeo">Vimeo</a>{/if}
                            {if $sb_theme_infos.theme_infos_viadeo}<a href="{$sb_theme_infos.theme_infos_viadeo}" class="socials-small viadeo-small" title="Viadeo">Viadeo</a>{/if}
                            {if $sb_theme_infos.theme_infos_youtube}<a href="{$sb_theme_infos.theme_infos_youtube}" class="socials-small youtube-small" title="Youtube">Youtube</a>{/if}
                            {if $sb_theme_infos.theme_infos_linkedin}<a href="{$sb_theme_infos.theme_infos_linkedin}" class="socials-small linkedin-small" title="Linkedin">Linkedin</a>{/if}
                        </div>
                    </div>
                </div>
                <!-- END COPYRIGHT -->
            </div>
            <!-- END WRAPPER -->
        </div>
        <!-- END BG SHADOW -->

		{insert name="sbGetPlugins"}
        
        <script type="text/javascript" src="{$smarty.const.SB_THEME_URL}js/jquery.custom.js"></script>
        <script type="text/javascript" src="{$smarty.const.SB_THEME_URL}js/contact.js"></script>
        <script type="text/javascript" src="{$smarty.const.SB_THEME_URL}js/jquery.mobilemenu.js"></script>
        
    </body>
    <!-- END BODY -->
</html>