<?php
/* Smarty version 3.1.29, created on 2016-12-09 16:10:54
  from "/Applications/MAMP/htdocs/sbmagic_new/theme/one/tpls/navigation.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_584ac97e883493_04814228',
  'file_dependency' => 
  array (
    '680304b3d81ca283f6053e82a819bbd596bbb24b' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/theme/one/tpls/navigation.tpl',
      1 => 1473338292,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_584ac97e883493_04814228 ($_smarty_tpl) {
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
                                <?php echo insert_sbGetConfig(array('id' => "header"),$_smarty_tpl);?>

                            </div>
                        </div>
                        <div class="clearer"></div>
                        
                        <!-- START MAIN NAVIGATION -->
                        <div class="menu classic">
                            <?php echo insert_sbGetMenuCms(array('mclass' => 'menu', 'mid' => 'nav', 'mtag' => 'main_menu', 'mlang' => ((string)$_SESSION['lang'])),$_smarty_tpl);?>

                        </div>
                        <!-- END MAIN NAVIGATION -->
                        
                        
                        <div id="menu-shadow"></div>
                        
                    </div>
                    
                </div>
                <!-- END HEADER --><?php }
}
