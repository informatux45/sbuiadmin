<?php
/* Smarty version 3.1.29, created on 2016-12-19 10:41:58
  from "/Applications/MAMP/htdocs/sbmagic_new/theme/three/tpls/footer.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_5857ab66644fb0_11078208',
  'file_dependency' => 
  array (
    'de6f4f835996b5637abf63627bf2d02a4dfa7aff' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/theme/three/tpls/footer.tpl',
      1 => 1481533031,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5857ab66644fb0_11078208 ($_smarty_tpl) {
?>


				<!-- START COPYRIGHT -->
                <div id="copyright">
                    <div class="inner group">
                        <div class="left">
                            <?php echo insert_sbGetConfig(array('id' => "footer"),$_smarty_tpl);?>

                        </div>
                        <div class="right">
                            <a href="#" class="socials-small facebook-small" title="Facebook">facebook</a>
                            <a href="#" class="socials-small rss-small" title="Rss">rss</a>
                            <a href="#" class="socials-small twitter-small" title="Twitter">twitter</a>
                            <a href="#" class="socials-small flickr-small" title="Flickr">flickr</a>
                            <a href="#" class="socials-small skype-small" title="Skype">skype</a>
                            <a href="#" class="socials-small google-small" title="Google">google</a>
                            <a href="#" class="socials-small pinterest-small" title="Pinterest">pinterest</a>
                        </div>
                    </div>
                </div>
                <!-- END COPYRIGHT -->
            </div>
            <!-- END WRAPPER -->
        </div>
        <!-- END BG SHADOW -->

		<?php echo insert_sbGetPlugins(array(),$_smarty_tpl);?>

        
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
        
    </body>
    <!-- END BODY -->
</html><?php }
}
