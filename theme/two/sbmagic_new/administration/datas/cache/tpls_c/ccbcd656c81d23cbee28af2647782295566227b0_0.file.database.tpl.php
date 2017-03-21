<?php
/* Smarty version 3.1.29, created on 2016-12-19 09:59:25
  from "/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/system/database.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_5857a16d4c21a6_10436257',
  'file_dependency' => 
  array (
    'ccbcd656c81d23cbee28af2647782295566227b0' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/system/database.tpl',
      1 => 1472829748,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:sb_header.tpl' => 1,
    'file:sb_footer.tpl' => 1,
  ),
),false)) {
function content_5857a16d4c21a6_10436257 ($_smarty_tpl) {
?>

	


	
	<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:sb_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('module'=>$_smarty_tpl->tpl_vars['module_page']->value), 0, false);
?>

	
			
			
			
			

			
			<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <i class="fa fa-info-circle fa-fw"></i> Vos informations de connexion
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
							
							<ul>
								<li>Nom de l'hôte : <?php echo @constant('_AM_DB_HOST');?>
</li>
								<li>Nom du user : <?php echo @constant('_AM_DB_USER');?>
</li>
							</ul>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->

			
			<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-database fa-fw"></i> Database
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
							<iframe src="inc/plugins/bddadmin/index.php" width="100%" height="500px" style="border: none;"></iframe>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->

		<!-- ------------------------------------------------------------ -->
		<!-- Page-Level Scripts - Use this space this write your own code -->
		<!-- ------------------------------------------------------------ -->
		<?php echo '<script'; ?>
>
		$(document).ready(function() {
			// Your own code

		});
		<?php echo '</script'; ?>
>

	<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:sb_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
