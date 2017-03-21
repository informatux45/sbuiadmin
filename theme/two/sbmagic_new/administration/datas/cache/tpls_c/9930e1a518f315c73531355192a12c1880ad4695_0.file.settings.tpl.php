<?php
/* Smarty version 3.1.29, created on 2016-12-14 08:51:00
  from "/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/system/settings.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_5850f9e4e072d6_98634049',
  'file_dependency' => 
  array (
    '9930e1a518f315c73531355192a12c1880ad4695' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/system/settings.tpl',
      1 => 1481555464,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:sb_header.tpl' => 1,
    'file:sb_footer.tpl' => 1,
  ),
),false)) {
function content_5850f9e4e072d6_98634049 ($_smarty_tpl) {
?>

	


	
	<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:sb_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('module'=>$_smarty_tpl->tpl_vars['module_page']->value), 0, false);
?>

	
			
			
			
			
			
			
			<div class="row">
                
				<div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-cubes fa-fw"></i> Configuration
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
									<tr class="info gradeX">
										<td>
											Nom du client
										</td>
										<td>
											<?php echo $_smarty_tpl->tpl_vars['sb_config_customer_name']->value;?>

										</td>
									</tr>
									<tr class="gradeX">
										<td>
											Nom des Administrateurs
										</td>
										<td>
											<?php echo $_smarty_tpl->tpl_vars['sb_config_administrators']->value;?>

										</td>
									</tr>
									<tr class="info gradeX">
										<td>
											Database Host
										</td>
										<td>
											<?php echo $_smarty_tpl->tpl_vars['sb_config_dbhost']->value;?>

										</td>
									</tr>
									<tr class="gradeX">
										<td>
											Database Name
										</td>
										<td>
											<?php echo $_smarty_tpl->tpl_vars['sb_config_dbname']->value;?>

										</td>
									</tr>
									<tr class="info gradeX">
										<td>
											Database User
										</td>
										<td>
											<?php echo $_smarty_tpl->tpl_vars['sb_config_dbuser']->value;?>

										</td>
									</tr>
									<tr class="gradeX">
										<td>
											Database Password
										</td>
										<td>
											<?php echo $_smarty_tpl->tpl_vars['sb_config_dbpwd']->value;?>

										</td>
									</tr>
									<tr class="info gradeX">
										<td>
											Database Prefix Table
										</td>
										<td>
											<?php echo $_smarty_tpl->tpl_vars['sb_config_dbprefix']->value;?>

										</td>
									</tr>
									<tr class="gradeX">
										<td>
											Répertoire d'uploads
										</td>
										<td>
											<?php echo $_smarty_tpl->tpl_vars['sb_config_diruploads']->value;?>

										</td>
									</tr>
									</tr>
									<tr class="info gradeX">
										<td>
											URL d'uploads
										</td>
										<td>
											<?php echo $_smarty_tpl->tpl_vars['sb_config_urluploads']->value;?>

										</td>
									</tr>
									<tr class="gradeX">
										<td>
											Upload Max autorisé
										</td>
										<td>
											<?php echo $_smarty_tpl->tpl_vars['sb_config_upload_max']->value;?>

										</td>
									</tr>
									<tr class="info gradeX">
										<td>
											Upload Extensions autorisées
										</td>
										<td>
											<?php echo $_smarty_tpl->tpl_vars['sb_config_upload_exts']->value;?>

										</td>
									</tr>
									<tr class="gradeX">
										<td>
											Nombre d'uploads simultanés autorisées
										</td>
										<td>
											<?php echo $_smarty_tpl->tpl_vars['sb_config_upload_limit']->value;?>

										</td>
									</tr>
									<tr class="info gradeX">
										<td>
											Taille maximum autorisée pour vos médias (px)<br>(hauteur et largeur confondues)
										</td>
										<td>
											<?php echo $_smarty_tpl->tpl_vars['sb_config_scaling_maxsize']->value;?>

										</td>
									</tr>
									<tr class="gradeX">
										<td>
											Nom des modules autorisés
										</td>
										<td>
											<?php echo $_smarty_tpl->tpl_vars['sb_config_modules']->value;?>

										</td>
									</tr>
									<tr class="info gradeX">
										<td>
											Google ReCaptcha Clé Publique
										</td>
										<td>
											<?php echo $_smarty_tpl->tpl_vars['sb_config_recaptcha_public']->value;?>

										</td>
									</tr>
									<tr class="gradeX">
										<td>
											Google ReCaptcha Clé Secrète
										</td>
										<td>
											<?php echo $_smarty_tpl->tpl_vars['sb_config_recaptcha_secret']->value;?>

										</td>
									</tr>
									<tr class="info gradeX">
										<td>
											Mode debug général (Kint)
										</td>
										<td>
											<?php if ($_smarty_tpl->tpl_vars['sb_config_debug_general']->value == 1) {?>Activé<?php } else { ?>Désactivé<?php }?>
										</td>
									</tr>
									<tr class="gradeX">
										<td>
											Mode debug formulaire
										</td>
										<td>
											<?php if ($_smarty_tpl->tpl_vars['sb_config_debug_form']->value == 1) {?>Activé<?php } else { ?>Désactivé<?php }?>
										</td>
									</tr>
									<tr class="info gradeX">
										<td>
											Mode debug Smarty
										</td>
										<td>
											<?php if ($_smarty_tpl->tpl_vars['sb_config_debug_smarty']->value == 1) {?>Activé<?php } else { ?>Désactivé<?php }?>
										</td>
									</tr>
									<tr class="gradeX">
										<td>
											Sandbox
										</td>
										<td>
											<?php if ($_smarty_tpl->tpl_vars['sb_config_sandbox']->value == 1) {?>Activé<?php } else { ?>Désactivé<?php }?>
										</td>
									</tr>
									<tr class="info gradeX">
										<td>
											CMS
										</td>
										<td>
											<?php if ($_smarty_tpl->tpl_vars['sb_config_cms']->value == 1) {?>Activé<?php } else { ?>Désactivé<?php }?>
										</td>
									</tr>
									<tr class="gradeX">
										<td>
											Captcha (Login)
										</td>
										<td>
											<?php if ($_smarty_tpl->tpl_vars['sb_config_captcha_mode']->value == 1) {?>Activé<?php } else { ?>Désactivé<?php }?>
										</td>
									</tr>
									<tr class="info gradeX">
										<td>
											Mode UPGRADE
										</td>
										<td>
											<?php if ($_smarty_tpl->tpl_vars['sb_config_upgrade_mode']->value == 1) {?>Activé<?php } else { ?>Désactivé<?php }?>
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
