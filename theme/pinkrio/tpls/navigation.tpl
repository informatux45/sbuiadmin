
                <!-- START HEADER -->
                <div id="header" class="group">
                    
                    <div class="group inner">
                        
                        <!-- START LOGO -->
                        <div id="logo" class="group">
                            <a href="{$smarty.const.SB_URL}" title="{$sb_site_title}"><img src="{$smarty.const.SB_THEME_URL}images/logo.png" title="{$sb_site_title}" alt="{$sb_site_title}" /></a><br>
                            {insert name="sbDoShortcode" code="[CS name=sbuser icontext=1 href_class=login_group]"}
                        </div>
                        <!-- END LOGO -->
                        
                        <div id="sidebar-header" class="group">
                            <div class="widget-first widget yit_text_quote">
                                <blockquote class="text-quote-quote">
                                    {insert name="sbGetConfig" id="header"}
                                </blockquote>
                            </div>
                        </div>
                        <div class="clearer"></div>
                        
                        <!-- START MAIN NAVIGATION -->
                        <hr>
                        <div class="menu classic">
                            {insert name='sbGetMenuCms' mclass='menu' mid='nav' mtag='main_menu' mlang="`$smarty.session.lang`"}
                        </div>
                        <!-- END MAIN NAVIGATION -->
                        
                        <div id="header-shadow"></div>
                        <div id="menu-shadow"></div>
                        
                    </div>
                    
                </div>
                <!-- END HEADER -->
                
                <!-- START SLIDER -->
                {if $sb_page_headpage}
                <div id="slider" class="slider">
                    <div class="shadowWrapper">
                        {insert name="sbDoShortcode" code="[CS id=`$sb_page_headpage` name=sbslider]"}
                        <div class="shadow-left"></div>
                        <div class="shadow-right"></div>
                    </div>
                </div>
                {/if}