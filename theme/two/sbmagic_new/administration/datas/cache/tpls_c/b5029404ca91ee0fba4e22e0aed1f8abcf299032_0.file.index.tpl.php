<?php
/* Smarty version 3.1.29, created on 2016-12-19 09:58:51
  from "/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/system/index.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_5857a14b3f0c90_87441751',
  'file_dependency' => 
  array (
    'b5029404ca91ee0fba4e22e0aed1f8abcf299032' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/system/index.tpl',
      1 => 1473074052,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:sb_header.tpl' => 1,
    'file:sb_footer.tpl' => 1,
  ),
),false)) {
function content_5857a14b3f0c90_87441751 ($_smarty_tpl) {
?>

	


	
	<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:sb_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('module'=>$_smarty_tpl->tpl_vars['module_page']->value,'pageindex'=>'Dashboard'), 0, false);
?>

	

			<?php if ($_smarty_tpl->tpl_vars['sbmagic_user_type']->value == 'admin') {?>
            <!-- .row -->
            <div class="row">
				
				<?php if ($_smarty_tpl->tpl_vars['sb_warning_installer_lock']->value) {?>
					<div class="alert alert-danger">
						Le répertoire INSTALL existe toujours ! Supprimer le !! <a class='alert-link' href='#'>Vite</a> !!!
					</div>
				<?php }?>
				
				<?php if ($_smarty_tpl->tpl_vars['sb_warning_install_file']->value) {?>
					<div class="alert alert-danger">
						Le fichier INSTALL.PHP existe toujours ! Supprimer le !! <a class='alert-link' href='#'>Vite</a> !!!
					</div>
				<?php }?>
				
				<?php if ($_smarty_tpl->tpl_vars['sb_warning_admin_user']->value) {?>
					<div class="alert alert-danger">
						L'utilisateur <a href="index.php?p=users">ADMIN</a> existe toujours ! Créez d'autres utilisateurs et supprimer le !! <a class='alert-link' href='#'>Vite</a> !!!
					</div>
				<?php }?>

                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-users fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['sb_users_cpt']->value)===null||$tmp==='' ? 0 : $tmp);?>
</div>
                                    <div>Utilisateurs</div>
                                </div>
                            </div>
                        </div>
                        <a href="index.php?p=users">
                            <div class="panel-footer">
                                <span class="pull-left">Détails utilisateurs</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-gears fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo @constant('_AM_SERVER_PHP_VERSION_ID');?>
</div>
                                    <div>Version PHP</div>
                                </div>
                            </div>
                        </div>
                        <a href="index.php?p=server">
                            <div class="panel-footer">
                                <span class="pull-left">Détails configuration serveur</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-database fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo @constant('_AM_DB_HOST');?>
</div>
                                    <div>DB Host</div>
                                </div>
                            </div>
                        </div>
                        <a href="index.php?p=settings">
                            <div class="panel-footer">
                                <span class="pull-left">Détails configuration</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
				
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-upload fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo @constant('_AM_MEDIAS_SIZE_LIMIT');?>
</div>
                                    <div>Upload limit</div>
                                </div>
                            </div>
                        </div>
                        <a href="index.php?p=settings">
                            <div class="panel-footer">
                                <span class="pull-left">Détails paramètres</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>

            <!-- /.row -->
            </div>
			<?php }?>


            <!-- .row -->
            <div class="row">
				
				
				<?php if ($_smarty_tpl->tpl_vars['sb_dashboard_status1_table']->value != '') {?>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-<?php echo $_smarty_tpl->tpl_vars['sb_dashboard_status1_icon']->value;?>
 fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['sb_dashboard_status1_cpt']->value)===null||$tmp==='' ? 0 : $tmp);?>
</div>
                                    <div><?php echo sbDisplayLang($_smarty_tpl->tpl_vars['sb_dashboard_status1_title']->value);?>
</div>
                                </div>
                            </div>
                        </div>
                        <a href="<?php echo $_smarty_tpl->tpl_vars['sb_dashboard_status1_link']->value;?>
">
                            <div class="panel-footer">
                                <span class="pull-left">Détails</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
				<?php }?>
				
				<?php if ($_smarty_tpl->tpl_vars['sb_dashboard_status2_table']->value != '') {?>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-<?php echo $_smarty_tpl->tpl_vars['sb_dashboard_status2_icon']->value;?>
 fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['sb_dashboard_status2_cpt']->value)===null||$tmp==='' ? 0 : $tmp);?>
</div>
                                    <div><?php echo sbDisplayLang($_smarty_tpl->tpl_vars['sb_dashboard_status2_title']->value);?>
</div>
                                </div>
                            </div>
                        </div>
                        <a href="<?php echo $_smarty_tpl->tpl_vars['sb_dashboard_status2_link']->value;?>
">
                            <div class="panel-footer">
                                <span class="pull-left">Détails</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
				<?php }?>
				
				<?php if ($_smarty_tpl->tpl_vars['sb_dashboard_status3_table']->value != '') {?>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-<?php echo $_smarty_tpl->tpl_vars['sb_dashboard_status3_icon']->value;?>
 fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['sb_dashboard_status3_cpt']->value)===null||$tmp==='' ? 0 : $tmp);?>
</div>
                                    <div><?php echo sbDisplayLang($_smarty_tpl->tpl_vars['sb_dashboard_status3_title']->value);?>
</div>
                                </div>
                            </div>
                        </div>
                        <a href="<?php echo $_smarty_tpl->tpl_vars['sb_dashboard_status3_link']->value;?>
">
                            <div class="panel-footer">
                                <span class="pull-left">Détails</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
				<?php }?>
				
				<?php if ($_smarty_tpl->tpl_vars['sb_dashboard_status4_table']->value != '') {?>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-<?php echo $_smarty_tpl->tpl_vars['sb_dashboard_status4_icon']->value;?>
 fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['sb_dashboard_status4_cpt']->value)===null||$tmp==='' ? 0 : $tmp);?>
</div>
                                    <div><?php echo sbDisplayLang($_smarty_tpl->tpl_vars['sb_dashboard_status4_title']->value);?>
</div>
                                </div>
                            </div>
                        </div>
                        <a href="<?php echo $_smarty_tpl->tpl_vars['sb_dashboard_status4_link']->value;?>
">
                            <div class="panel-footer">
                                <span class="pull-left">Détails</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
				<?php }?>
				
            </div>
            <!-- /.row -->
			
			
			<!-- .row -->
            <div class="row">
				
				<?php if ($_smarty_tpl->tpl_vars['sb_dashboard_status1_table']->value != '') {?>
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-<?php echo $_smarty_tpl->tpl_vars['sb_dashboard_status1_icon']->value;?>
 fa-fw"></i> <?php echo $_smarty_tpl->tpl_vars['sb_dashboard_status1_title']->value;?>

                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="list-group">
								<?php
$_from = $_smarty_tpl->tpl_vars['sb_dashboard_status1_all']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_status1all_0_saved_item = isset($_smarty_tpl->tpl_vars['status1all']) ? $_smarty_tpl->tpl_vars['status1all'] : false;
$_smarty_tpl->tpl_vars['status1all'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['status1all']->iteration=0;
$_smarty_tpl->tpl_vars['status1all']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['status1all']->value) {
$_smarty_tpl->tpl_vars['status1all']->_loop = true;
$_smarty_tpl->tpl_vars['status1all']->iteration++;
$__foreach_status1all_0_saved_local_item = $_smarty_tpl->tpl_vars['status1all'];
?>
									<?php if ($_smarty_tpl->tpl_vars['status1all']->iteration == 11) {
break 1;
}?>
									<a href="#" class="list-group-item">
										<i class="fa fa-th-list fa-fw"></i> <?php echo mb_convert_encoding(sbDisplayLang($_smarty_tpl->tpl_vars['status1all']->value[$_smarty_tpl->tpl_vars['sb_dashboard_status1_tbcol']->value]), 'UTF-8', 'HTML-ENTITIES');?>

										
									</a>
								<?php
$_smarty_tpl->tpl_vars['status1all'] = $__foreach_status1all_0_saved_local_item;
}
if ($__foreach_status1all_0_saved_item) {
$_smarty_tpl->tpl_vars['status1all'] = $__foreach_status1all_0_saved_item;
}
?>
                            </div>
                            <!-- /.list-group -->
                            <a href="<?php echo $_smarty_tpl->tpl_vars['sb_dashboard_status1_link']->value;?>
" class="btn btn-default btn-block">Voir <?php echo strtolower($_smarty_tpl->tpl_vars['sb_dashboard_status1_title']->value);?>
</a>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                </div>
                <!-- /.col-lg-4 -->
				<?php }?>

				<?php if ($_smarty_tpl->tpl_vars['sb_dashboard_status2_table']->value != '') {?>
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-<?php echo $_smarty_tpl->tpl_vars['sb_dashboard_status2_icon']->value;?>
 fa-fw"></i> <?php echo $_smarty_tpl->tpl_vars['sb_dashboard_status2_title']->value;?>

                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="list-group">
								<?php
$_from = $_smarty_tpl->tpl_vars['sb_dashboard_status2_all']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_status2all_1_saved_item = isset($_smarty_tpl->tpl_vars['status2all']) ? $_smarty_tpl->tpl_vars['status2all'] : false;
$_smarty_tpl->tpl_vars['status2all'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['status2all']->iteration=0;
$_smarty_tpl->tpl_vars['status2all']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['status2all']->value) {
$_smarty_tpl->tpl_vars['status2all']->_loop = true;
$_smarty_tpl->tpl_vars['status2all']->iteration++;
$__foreach_status2all_1_saved_local_item = $_smarty_tpl->tpl_vars['status2all'];
?>
									<?php if ($_smarty_tpl->tpl_vars['status2all']->iteration == 11) {
break 1;
}?>
									<a href="#" class="list-group-item">
										<i class="fa fa-th-list fa-fw"></i> <?php echo mb_convert_encoding(sbDisplayLang($_smarty_tpl->tpl_vars['status2all']->value[$_smarty_tpl->tpl_vars['sb_dashboard_status2_tbcol']->value]), 'UTF-8', 'HTML-ENTITIES');?>

										
									</a>
								<?php
$_smarty_tpl->tpl_vars['status2all'] = $__foreach_status2all_1_saved_local_item;
}
if ($__foreach_status2all_1_saved_item) {
$_smarty_tpl->tpl_vars['status2all'] = $__foreach_status2all_1_saved_item;
}
?>
                            </div>
                            <!-- /.list-group -->
                            <a href="<?php echo $_smarty_tpl->tpl_vars['sb_dashboard_status2_link']->value;?>
" class="btn btn-default btn-block">Voir <?php echo strtolower($_smarty_tpl->tpl_vars['sb_dashboard_status2_title']->value);?>
</a>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                </div>
                <!-- /.col-lg-4 -->
				<?php }?>
				
				<?php if ($_smarty_tpl->tpl_vars['sb_dashboard_status3_table']->value != '') {?>
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-<?php echo $_smarty_tpl->tpl_vars['sb_dashboard_status3_icon']->value;?>
 fa-fw"></i> <?php echo $_smarty_tpl->tpl_vars['sb_dashboard_status3_title']->value;?>

                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="list-group">
								<?php
$_from = $_smarty_tpl->tpl_vars['sb_dashboard_status3_all']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_status3all_2_saved_item = isset($_smarty_tpl->tpl_vars['status3all']) ? $_smarty_tpl->tpl_vars['status3all'] : false;
$_smarty_tpl->tpl_vars['status3all'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['status3all']->iteration=0;
$_smarty_tpl->tpl_vars['status3all']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['status3all']->value) {
$_smarty_tpl->tpl_vars['status3all']->_loop = true;
$_smarty_tpl->tpl_vars['status3all']->iteration++;
$__foreach_status3all_2_saved_local_item = $_smarty_tpl->tpl_vars['status3all'];
?>
									<?php if ($_smarty_tpl->tpl_vars['status3all']->iteration == 11) {
break 1;
}?>
									<a href="#" class="list-group-item">
										<i class="fa fa-th-list fa-fw"></i> <?php echo mb_convert_encoding(sbDisplayLang($_smarty_tpl->tpl_vars['status3all']->value[$_smarty_tpl->tpl_vars['sb_dashboard_status3_tbcol']->value]), 'UTF-8', 'HTML-ENTITIES');?>

										
									</a>
								<?php
$_smarty_tpl->tpl_vars['status3all'] = $__foreach_status3all_2_saved_local_item;
}
if ($__foreach_status3all_2_saved_item) {
$_smarty_tpl->tpl_vars['status3all'] = $__foreach_status3all_2_saved_item;
}
?>
                            </div>
                            <!-- /.list-group -->
                            <a href="<?php echo $_smarty_tpl->tpl_vars['sb_dashboard_status3_link']->value;?>
" class="btn btn-default btn-block">Voir <?php echo strtolower($_smarty_tpl->tpl_vars['sb_dashboard_status3_title']->value);?>
</a>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                </div>
                <!-- /.col-lg-4 -->
				<?php }?>

			</div>
			<!-- /.row -->
				
			<!-- .row -->
            <div class="row">
				
				<?php if ($_smarty_tpl->tpl_vars['sb_dashboard_status4_table']->value != '') {?>
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-<?php echo $_smarty_tpl->tpl_vars['sb_dashboard_status4_icon']->value;?>
 fa-fw"></i> <?php echo $_smarty_tpl->tpl_vars['sb_dashboard_status4_title']->value;?>

                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="list-group">
								<?php
$_from = $_smarty_tpl->tpl_vars['sb_dashboard_status4_all']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_status4all_3_saved_item = isset($_smarty_tpl->tpl_vars['status4all']) ? $_smarty_tpl->tpl_vars['status4all'] : false;
$_smarty_tpl->tpl_vars['status4all'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['status4all']->iteration=0;
$_smarty_tpl->tpl_vars['status4all']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['status4all']->value) {
$_smarty_tpl->tpl_vars['status4all']->_loop = true;
$_smarty_tpl->tpl_vars['status4all']->iteration++;
$__foreach_status4all_3_saved_local_item = $_smarty_tpl->tpl_vars['status4all'];
?>
									<?php if ($_smarty_tpl->tpl_vars['status4all']->iteration == 11) {
break 1;
}?>
									<a href="#" class="list-group-item">
										<i class="fa fa-th-list fa-fw"></i> <?php echo mb_convert_encoding(sbDisplayLang($_smarty_tpl->tpl_vars['status4all']->value[$_smarty_tpl->tpl_vars['sb_dashboard_status4_tbcol']->value]), 'UTF-8', 'HTML-ENTITIES');?>

										
									</a>
								<?php
$_smarty_tpl->tpl_vars['status4all'] = $__foreach_status4all_3_saved_local_item;
}
if ($__foreach_status4all_3_saved_item) {
$_smarty_tpl->tpl_vars['status4all'] = $__foreach_status4all_3_saved_item;
}
?>
                            </div>
                            <!-- /.list-group -->
                            <a href="<?php echo $_smarty_tpl->tpl_vars['sb_dashboard_status4_link']->value;?>
" class="btn btn-default btn-block">Voir <?php echo strtolower($_smarty_tpl->tpl_vars['sb_dashboard_status4_title']->value);?>
</a>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                </div>
                <!-- /.col-lg-4 -->
				<?php }?>

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
