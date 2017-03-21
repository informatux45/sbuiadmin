<?php
/* Smarty version 3.1.29, created on 2016-12-19 10:41:58
  from "/Applications/MAMP/htdocs/sbmagic_new/theme/three/tpls/navigation.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_5857ab66510366_20100780',
  'file_dependency' => 
  array (
    '233bd1332b181f041a973c6c24aaf018c4428249' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/theme/three/tpls/navigation.tpl',
      1 => 1475757111,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5857ab66510366_20100780 ($_smarty_tpl) {
?>

                <!-- START HEADER -->
                <div id="header" class="group">
                    
                    <div class="group inner">
                        
                        <!-- START LOGO -->
                        <div id="logo" class="group">
                            <a href="<?php echo @constant('SB_URL');?>
" title="<?php echo $_smarty_tpl->tpl_vars['sb_site_title']->value;?>
"><img src="<?php echo @constant('SB_THEME_URL');?>
images/logo.png" title="<?php echo $_smarty_tpl->tpl_vars['sb_site_title']->value;?>
" alt="<?php echo $_smarty_tpl->tpl_vars['sb_site_title']->value;?>
" /></a>
                        </div>
                        <!-- END LOGO -->
                        
                        <div id="sidebar-header" class="group">
                            <div class="widget-first widget yit_text_quote">
                                <blockquote class="text-quote-quote">
                                    <?php echo insert_sbGetConfig(array('id' => "header"),$_smarty_tpl);?>

                                </blockquote>
                            </div>
                        </div>
                        <div class="clearer"></div>
                        
                        <!-- START MAIN NAVIGATION -->
                        <div class="menu classic">
                            <?php echo insert_sbGetMenuCms(array('mclass' => 'menu', 'mid' => 'nav', 'mtag' => 'main_menu', 'mlang' => ((string)$_SESSION['lang'])),$_smarty_tpl);?>

                        </div>
                        <!-- END MAIN NAVIGATION -->
                        
                        <div id="header-shadow"></div>
                        <div id="menu-shadow"></div>
                        
                    </div>
                    
                </div>
                <!-- END HEADER -->
                
                <!-- START SLIDER -->
                <?php if ($_smarty_tpl->tpl_vars['sb_page_headpage']->value) {?>
                <div id="slider" class="slider">
                    <div class="shadowWrapper">
                        <?php echo insert_sbDoShortcode(array('code' => "[CS id=".((string)$_smarty_tpl->tpl_vars['sb_page_headpage']->value)." name=sbslider]"),$_smarty_tpl);?>

                        <div class="shadow-left"></div>
                        <div class="shadow-right"></div>
                    </div>
                </div>
                <?php }
}
}
