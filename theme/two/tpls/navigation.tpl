

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
            <div class="main-navigation">
                {insert name='sbGetMenuCms' mclass='navigation' mid='nav' mtag='main_menu' mlang="`$smarty.session.lang`"}
            </div> <!-- .main-navigation -->
            <div class="social-icons">
                <ul>
                    <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                    <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                    <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                    <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                    <li><a href="#"><i class="fa fa-youtube"></i></a></li>
                </ul>
            </div> <!-- .social-icons -->
            <div class="sidebar-bottom-section">
                {insert name="sbGetConfig" id="header"}
            </div>
        </div> <!-- .sidebar-menu -->