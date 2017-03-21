<?php
/* Smarty version 3.1.29, created on 2016-12-09 16:10:54
  from "/Applications/MAMP/htdocs/sbmagic_new/theme/one/tpls/footer.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_584ac97e9428d7_66092933',
  'file_dependency' => 
  array (
    '221cac9ec880db6b79886e8c87783bc092d4d14c' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/theme/one/tpls/footer.tpl',
      1 => 1476345144,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_584ac97e9428d7_66092933 ($_smarty_tpl) {
?>

				<!-- START COPYRIGHT -->
                <div id="copyright">
                    <div class="inner group">
                        <div class="center">
                            <?php echo insert_sbGetConfig(array('id' => "footer"),$_smarty_tpl);?>

                        </div>
                    </div>
                </div>
                <!-- END COPYRIGHT -->
            </div>
            <!-- END WRAPPER -->
        </div>
        <!-- END BG SHADOW -->
        
        <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo @constant('SB_THEME_URL');?>
js/jquery.custom.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo @constant('SB_THEME_URL');?>
js/contact.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo @constant('SB_THEME_URL');?>
js/jquery.mobilemenu.js"><?php echo '</script'; ?>
>
		
		<?php echo insert_sbGetPlugins(array(),$_smarty_tpl);?>

		
    </body>
    <!-- END BODY -->
</html><?php }
}
