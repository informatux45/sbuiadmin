<?php
/* Smarty version 3.1.29, created on 2016-11-29 15:32:53
  from "/Applications/MAMP/htdocs/sbmagic_new/theme/two/tpls/navigation.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_583d9195292ac4_50520835',
  'file_dependency' => 
  array (
    '5274913b09ebe016f1bb6687f3605bba180c6c65' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/theme/two/tpls/navigation.tpl',
      1 => 1475150353,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_583d9195292ac4_50520835 ($_smarty_tpl) {
?>


        <div class="responsive-header visible-xs visible-sm">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <a href="<?php echo @constant('SB_URL');?>
">
                            <img class="responsive-header-logo" src="<?php echo @constant('SB_THEME_URL');?>
img/logo.png" alt="<?php echo $_smarty_tpl->tpl_vars['sb_site_title']->value;?>
" title="<?php echo $_smarty_tpl->tpl_vars['sb_site_title']->value;?>
">
                        </a>
                    </div>
                </div>
                <a href="#" class="toggle-menu"><i class="fa fa-bars"></i></a>
                <div class="main-navigation responsive-menu">
                    <?php echo insert_sbGetMenuCms(array('mclass' => 'navigation', 'mid' => 'nav', 'mtag' => 'main_menu', 'mlang' => ((string)$_SESSION['lang'])),$_smarty_tpl);?>

                </div>
            </div>
        </div>

        <!-- SIDEBAR -->
        <div class="sidebar-menu hidden-xs hidden-sm">
            <div class="top-section">
                <div class="profile-image">
                    <img src="<?php echo @constant('SB_THEME_URL');?>
img/logo.png" alt="<?php echo $_smarty_tpl->tpl_vars['sb_site_title']->value;?>
">
                </div>
                <h3 class="profile-title"><?php echo $_smarty_tpl->tpl_vars['sb_site_title']->value;?>
</h3>
                <p class="profile-description"><?php echo $_smarty_tpl->tpl_vars['sb_site_title']->value;?>
</p>
            </div> <!-- top-section -->
            <div class="main-navigation">
                <?php echo insert_sbGetMenuCms(array('mclass' => 'navigation', 'mid' => 'nav', 'mtag' => 'main_menu', 'mlang' => ((string)$_SESSION['lang'])),$_smarty_tpl);?>

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
                <?php echo insert_sbGetConfig(array('id' => "header"),$_smarty_tpl);?>

            </div>
        </div> <!-- .sidebar-menu --><?php }
}
