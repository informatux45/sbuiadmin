
                <!-- START HEADER -->
                <div id="header" class="group">
                    
                    <div class="group inner">
                        
                        <!-- START LOGO -->
                        <div id="logo" class="group">
                            <a href="{$smarty.const.SB_URL}" title="{$sb_site_title}"><img src="{$smarty.const.SB_THEME_URL}images/logo.png" title="{$sb_site_title}" alt="{$sb_site_title}" /></a>
                        </div>
                        <!-- END LOGO -->
                        
                        <div id="sidebar-header" class="group">
                            <div class="widget-first widget yit_text_quote">
                                {insert name="sbGetConfig" id="header"}
                            </div>
                        </div>
                        <div class="clearer"></div>
                        
                        <!-- START MAIN NAVIGATION -->
                        <div class="menu classic">
                            {insert name='sbGetMenuCms' mclass='menu' mid='nav' mtag='main_menu' mlang="`$smarty.session.lang`"}
                        </div>
                        <!-- END MAIN NAVIGATION -->
                        
                        {*<div id="header-shadow"></div>*}
                        <div id="menu-shadow"></div>
                        
                    </div>
                    
                </div>
                <!-- END HEADER -->