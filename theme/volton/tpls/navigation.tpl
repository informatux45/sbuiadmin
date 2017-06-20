

        <div class="responsive-header visible-xs visible-sm">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <a href="{$smarty.const.SB_URL}">
                            <img class="responsive-header-logo" src="{$smarty.const.SB_THEME_URL}img/logo.png" alt="{$sb_site_title}" title="{$sb_site_title}">
                        </a>
                    </div>
                </div>
                <a href="#" class="toggle-menu"><i class="fa fa-bars"></i></a>
                <div class="main-navigation responsive-menu">
                    {insert name='sbGetMenuCms' mclass='navigation' mid='nav' mtag='main_menu' mlang="`$smarty.session.lang`"}
                </div>
            </div>
        </div>

        <!-- SIDEBAR -->
        <div class="sidebar-menu hidden-xs hidden-sm">
            <div class="top-section">
                <div class="profile-image">
                    <img src="{$smarty.const.SB_THEME_URL}img/logo.png" alt="{$sb_site_title}">
                </div>
                <h3 class="profile-title">{$sb_site_title}</h3>
                <p class="profile-description">{$sb_site_title}</p>
            </div> <!-- top-section -->
            <p class="row acenter">
                {*ul class="navigation">*}
                    {insert name="sbDoShortcode" code="[CS name=sbuser]"}
                {*</ul>*}
            </p>
            <div class="main-navigation">
                {insert name='sbGetMenuCms' mclass='navigation' mid='nav' mtag='main_menu' mlang="`$smarty.session.lang`"}
            </div> <!-- .main-navigation -->
            <div class="social-icons">
                <ul>
                    {if $sb_theme_infos.theme_infos_facebook}<li><a href="{$sb_theme_infos.theme_infos_facebook}"><i class="fa fa-facebook"></i></a></li>{/if}
                    {if $sb_theme_infos.theme_infos_twitter}<li><a href="{$sb_theme_infos.theme_infos_twitter}"><i class="fa fa-twitter"></i></a></li>{/if}
                    {if $sb_theme_infos.theme_infos_linkedin}<li><a href="{$sb_theme_infos.theme_infos_linkedin}"><i class="fa fa-linkedin"></i></a></li>{/if}
                    {if $sb_theme_infos.theme_infos_google_plus}<li><a href="{$sb_theme_infos.theme_infos_google_plus}"><i class="fa fa-google-plus"></i></a></li>{/if}
                    {if $sb_theme_infos.theme_infos_youtube}<li><a href="{$sb_theme_infos.theme_infos_youtube}"><i class="fa fa-youtube"></i></a></li>{/if}
                    {if $sb_theme_infos.theme_infos_vimeo}<li><a href="{$sb_theme_infos.theme_infos_vimeo}"><i class="fa fa-vimeo"></i></a></li>{/if}
                    {if $sb_theme_infos.theme_infos_pinterest}<li><a href="{$sb_theme_infos.theme_infos_pinterest}"><i class="fa fa-pinterest"></i></a></li>{/if}
                    {if $sb_theme_infos.theme_infos_instagram}<li><a href="{$sb_theme_infos.theme_infos_instagram}"><i class="fa fa-instagram"></i></a></li>{/if}
                    {if $sb_theme_infos.theme_infos_viadeo}<li><a href="{$sb_theme_infos.theme_infos_viadeo}"><i class="fa fa-viadeo"></i></a></li>{/if}
                    {if $sb_theme_infos.theme_infos_skype}<li><a href="{$sb_theme_infos.theme_infos_skype}"><i class="fa
                    fa-skype"></i></a></li>{/if}
                </ul>
            </div> <!-- .social-icons -->
            <div class="sidebar-bottom-section">
                {insert name="sbGetConfig" id="header"}
            </div>
        </div> <!-- .sidebar-menu -->