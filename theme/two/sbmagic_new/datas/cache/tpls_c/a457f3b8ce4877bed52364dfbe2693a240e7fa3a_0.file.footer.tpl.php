<?php
/* Smarty version 3.1.29, created on 2016-11-29 15:32:53
  from "/Applications/MAMP/htdocs/sbmagic_new/theme/two/tpls/footer.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_583d91953e47e4_29690329',
  'file_dependency' => 
  array (
    'a457f3b8ce4877bed52364dfbe2693a240e7fa3a' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/theme/two/tpls/footer.tpl',
      1 => 1476345173,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_583d91953e47e4_29690329 ($_smarty_tpl) {
?>


        <?php echo '<script'; ?>
 src="<?php echo @constant('SB_THEME_URL');?>
js/vendor/jquery-1.10.2.min.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 src="<?php echo @constant('SB_THEME_URL');?>
js/min/plugins.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 src="<?php echo @constant('SB_THEME_URL');?>
dist/js/lightbox.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript">
			
			$('.toggle-menu').click(function(){
				$('.responsive-menu').slideToggle();
				return false;
			});
		<?php echo '</script'; ?>
>
		
		<?php echo insert_sbGetPlugins(array(),$_smarty_tpl);?>


    </body>
</html><?php }
}
