<?php
/* Smarty version 3.1.29, created on 2016-12-20 14:40:55
  from "/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/header_tpl.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_585934e7b45326_17009434',
  'file_dependency' => 
  array (
    'fd6fdc9573f3b68b48ba074821e9cecd8b441537' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/header_tpl.tpl',
      1 => 1472829746,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_585934e7b45326_17009434 ($_smarty_tpl) {
if (!is_callable('smarty_function_sbdebug')) require_once '/Applications/MAMP/htdocs/sbmagic_new/administration/core/plugins/function.sbdebug.php';
?>


			
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php if ($_smarty_tpl->tpl_vars['page_title']->value) {
echo $_smarty_tpl->tpl_vars['page_title']->value;
} else {
echo $_smarty_tpl->tpl_vars['pageindex']->value;
}?></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
			
			<?php if (@constant('_AM_SITE_DEBUG')) {?>
				
				
				
				
				
				<?php echo smarty_function_sbdebug(array('debugsql'=>$_smarty_tpl->tpl_vars['sbdebugsql']->value,'odump'=>$_smarty_tpl->tpl_vars['sbodump']->value,'file_content'=>$_smarty_tpl->tpl_vars['file_content']->value),$_smarty_tpl);?>

				<p></p>
			<?php }?>
			
			
			<?php if ($_smarty_tpl->tpl_vars['sb_msg_error']->value || $_smarty_tpl->tpl_vars['sb_msg_valid']->value) {?>

				<?php if ($_smarty_tpl->tpl_vars['sb_msg_valid']->value) {?>
					
					<div class="alert alert-success alert-dismissable">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<?php echo $_smarty_tpl->tpl_vars['sb_msg_valid']->value;?>

					</div>
				<?php } elseif ($_smarty_tpl->tpl_vars['sb_msg_error']->value) {?>
					
					<div class="alert alert-danger alert-dismissable">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 <?php echo $_smarty_tpl->tpl_vars['sb_msg_error']->value;?>

					</div>
				<?php }?>
				
			<?php }
}
}
