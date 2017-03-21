<?php
/* Smarty version 3.1.29, created on 2016-12-05 11:11:22
  from "/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/system/toolbarck.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_58453d4a3a5030_65132865',
  'file_dependency' => 
  array (
    'f1aead1d041d0a21bd81c728ccd88e3844940410' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/system/toolbarck.tpl',
      1 => 1480583354,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:sb_header.tpl' => 1,
    'file:sb_footer.tpl' => 1,
  ),
),false)) {
function content_58453d4a3a5030_65132865 ($_smarty_tpl) {
?>

	


	
	<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:sb_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('module'=>$_smarty_tpl->tpl_vars['module_page']->value), 0, false);
?>

	
			
			
			
			

			
			<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <i class="fa fa-info-circle fa-fw"></i> Information
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
							
							<p style="color: red;"><strong>Si vous activez la configuration de la toolbar de CKEditor, celle-ci sera activée sur tous les éditeurs CKEditor présents dans les formulaires de votre administration.<br>Elle prendra effet en remplacement des configurations BASIC, SIMPLE, FULL.</strong></p>
							Une fois que vous avez configuré votre toolbar, copiez la configuration dans le fichier suivant :<br>
							<i><?php echo @constant('_AM_SITE_DIR');?>
inc/admin/<strong>ckeditor.php</strong></i>
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
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <i class="fa fa-cubes fa-fw"></i> <?php echo $_smarty_tpl->tpl_vars['legend_add_edit']->value;?>

                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
							
							<?php include_once ('/Applications/MAMP/htdocs/sbmagic_new/administration/form.php');?>

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
                            <i class="fa fa-cube fa-fw"></i> Toolbar Configurator
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
							<iframe src="inc/js/editor/ckeditor/samples/toolbarconfigurator/index.html#basic" width="100%" height="1000px" style="border: none;"></iframe>
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
