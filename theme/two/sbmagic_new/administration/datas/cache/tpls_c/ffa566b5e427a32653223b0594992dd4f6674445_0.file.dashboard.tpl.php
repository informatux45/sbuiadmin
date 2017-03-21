<?php
/* Smarty version 3.1.29, created on 2016-12-12 15:38:55
  from "/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/system/dashboard.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_584eb67fa31dd4_18026557',
  'file_dependency' => 
  array (
    'ffa566b5e427a32653223b0594992dd4f6674445' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/system/dashboard.tpl',
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
function content_584eb67fa31dd4_18026557 ($_smarty_tpl) {
?>

	


	
	<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:sb_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('module'=>$_smarty_tpl->tpl_vars['module_page']->value), 0, false);
?>

	
			
			
			
			
			
			
			<div class="row">
                
				<div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-dashboard fa-fw"></i> Configuration
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
							
							<?php include_once ('/Applications/MAMP/htdocs/sbmagic_new/administration/form.php');?>

                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-6 -->
				
                <div class="col-lg-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <i class="fa fa-info-circle fa-fw"></i> Rappel de votre configuration
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
							
							<table class="table table-striped table-bordered table-hover" id="dataTables-settings">
								<thead>
									<tr>
										<th>
											Config
										</th>
										<th>
											Valeur
										</th>
									</tr>
								</thead>
								<tbody>
									<tr class="danger gradeX">
										<td>
											Tables SQL
										</td>
										<td>
											<?php echo $_smarty_tpl->tpl_vars['sb_dashboard_tables']->value;?>

										</td>
									</tr>
									<tr class="gradeX warning">
										<td>
											[STATUS 1] Table SQL
										</td>
										<td>
											<?php echo $_smarty_tpl->tpl_vars['sb_dashboard_status1_table']->value;?>

										</td>
									</tr>
									<tr class="gradeX warning">
										<td>
											[STATUS 1] Table (champs)
										</td>
										<td>
											<?php echo $_smarty_tpl->tpl_vars['sb_dashboard_status1_tbcol']->value;?>

										</td>
									</tr>
									<tr class="gradeX warning">
										<td>
											[STATUS 1] Titre
										</td>
										<td>
											<?php echo $_smarty_tpl->tpl_vars['sb_dashboard_status1_title']->value;?>

										</td>
									</tr>
									<tr class="gradeX warning">
										<td>
											[STATUS 1] Lien relatif
										</td>
										<td>
											<?php echo $_smarty_tpl->tpl_vars['sb_dashboard_status1_link']->value;?>

										</td>
									</tr>
									<tr class="gradeX warning">
										<td>
											[STATUS 1] Icône
										</td>
										<td>
											<?php echo $_smarty_tpl->tpl_vars['sb_dashboard_status1_icon']->value;?>

										</td>
									</tr>
									<tr class="gradeX success">
										<td>
											[STATUS 2] Table SQL
										</td>
										<td>
											<?php echo $_smarty_tpl->tpl_vars['sb_dashboard_status2_table']->value;?>

										</td>
									</tr>
									<tr class="gradeX success">
										<td>
											[STATUS 2] Table (champs)
										</td>
										<td>
											<?php echo $_smarty_tpl->tpl_vars['sb_dashboard_status2_tbcol']->value;?>

										</td>
									</tr>
									<tr class="gradeX success">
										<td>
											[STATUS 2] Titre
										</td>
										<td>
											<?php echo $_smarty_tpl->tpl_vars['sb_dashboard_status2_title']->value;?>

										</td>
									</tr>
									<tr class="gradeX success">
										<td>
											[STATUS 2] Lien relatif
										</td>
										<td>
											<?php echo $_smarty_tpl->tpl_vars['sb_dashboard_status2_link']->value;?>

										</td>
									</tr>
									<tr class="gradeX success">
										<td>
											[STATUS 2] Icône
										</td>
										<td>
											<?php echo $_smarty_tpl->tpl_vars['sb_dashboard_status2_icon']->value;?>

										</td>
									</tr>
									<tr class="gradeX warning">
										<td>
											[STATUS 3] Table SQL
										</td>
										<td>
											<?php echo $_smarty_tpl->tpl_vars['sb_dashboard_status3_table']->value;?>

										</td>
									</tr>
									<tr class="gradeX warning">
										<td>
											[STATUS 3] Table (champs)
										</td>
										<td>
											<?php echo $_smarty_tpl->tpl_vars['sb_dashboard_status3_tbcol']->value;?>

										</td>
									</tr>
									<tr class="gradeX warning">
										<td>
											[STATUS 3] Titre
										</td>
										<td>
											<?php echo $_smarty_tpl->tpl_vars['sb_dashboard_status3_title']->value;?>

										</td>
									</tr>
									<tr class="gradeX warning">
										<td>
											[STATUS 3] Lien relatif
										</td>
										<td>
											<?php echo $_smarty_tpl->tpl_vars['sb_dashboard_status3_link']->value;?>

										</td>
									</tr>
									<tr class="gradeX warning">
										<td>
											[STATUS 3] Icône
										</td>
										<td>
											<?php echo $_smarty_tpl->tpl_vars['sb_dashboard_status3_icon']->value;?>

										</td>
									</tr>
									<tr class="gradeX success">
										<td>
											[STATUS 4] Table SQL
										</td>
										<td>
											<?php echo $_smarty_tpl->tpl_vars['sb_dashboard_status4_table']->value;?>

										</td>
									</tr>
									<tr class="gradeX success">
										<td>
											[STATUS 4] Table (champs)
										</td>
										<td>
											<?php echo $_smarty_tpl->tpl_vars['sb_dashboard_status4_tbcol']->value;?>

										</td>
									</tr>
									<tr class="gradeX success">
										<td>
											[STATUS 4] Titre
										</td>
										<td>
											<?php echo $_smarty_tpl->tpl_vars['sb_dashboard_status4_title']->value;?>

										</td>
									</tr>
									<tr class="gradeX success">
										<td>
											[STATUS 4] Lien relatif
										</td>
										<td>
											<?php echo $_smarty_tpl->tpl_vars['sb_dashboard_status4_link']->value;?>

										</td>
									</tr>
									<tr class="gradeX success">
										<td>
											[STATUS 4] Icône
										</td>
										<td>
											<?php echo $_smarty_tpl->tpl_vars['sb_dashboard_status4_icon']->value;?>

										</td>
									</tr>
								</tbody>
							</table>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-6 -->
				
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
