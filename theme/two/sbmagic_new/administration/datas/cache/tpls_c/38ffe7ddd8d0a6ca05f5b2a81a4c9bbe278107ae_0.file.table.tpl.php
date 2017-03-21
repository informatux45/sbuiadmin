<?php
/* Smarty version 3.1.29, created on 2016-12-12 16:27:49
  from "/Applications/MAMP/htdocs/sbmagic_new/administration/datas/modules/tpls/table.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_584ec1f5291f56_87884106',
  'file_dependency' => 
  array (
    '38ffe7ddd8d0a6ca05f5b2a81a4c9bbe278107ae' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/administration/datas/modules/tpls/table.tpl',
      1 => 1481555465,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:sb_header.tpl' => 1,
    'file:table_bar.tpl' => 1,
    'file:sb_footer.tpl' => 1,
  ),
),false)) {
function content_584ec1f5291f56_87884106 ($_smarty_tpl) {
if (!is_callable('smarty_function_cycle')) require_once '/Applications/MAMP/htdocs/sbmagic_new/administration/core/plugins/function.cycle.php';
if (!is_callable('smarty_modifier_truncate')) require_once '/Applications/MAMP/htdocs/sbmagic_new/administration/core/plugins/modifier.truncate.php';
?>

	


	
	<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:sb_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('module'=>$_smarty_tpl->tpl_vars['module_page']->value), 0, false);
?>

	
			
			
			
			
			
			<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:table_bar.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


            <div class="row">
				
				<?php if ($_smarty_tpl->tpl_vars['all']->value) {?>

					<div class="col-lg-12">

						<div class="panel panel-warning">
							<div class="panel-heading">
								<span class="fa fa-info-circle"></span> <strong>Informations</strong>
							</div>
							<!-- /.panel-heading -->
							<div class="panel-body">
								
								La suppression d'un tableau comporte <b class="red">la perte</b> de toutes ses données et de sa structure !
							</div>
							<!-- /.panel-body -->
						</div>

						<div class="panel panel-primary">
							<div class="panel-heading">
								<span class="fa fa-list-alt fa-fw"></span> <strong><?php if ($_smarty_tpl->tpl_vars['all']->value) {?>Gestion de vos tableaux<?php } else {
echo $_smarty_tpl->tpl_vars['legend_add_edit']->value;
}?></strong>
							</div>
							<!-- /.panel-heading -->
							<div class="panel-body">
								<div class="dataTable_wrapper">
									<table class="table table-striped table-bordered table-hover" id="dataTables-table">
										<thead>
											<tr>
												<?php
$_from = $_smarty_tpl->tpl_vars['sb_table_header']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_header_0_saved_item = isset($_smarty_tpl->tpl_vars['header']) ? $_smarty_tpl->tpl_vars['header'] : false;
$_smarty_tpl->tpl_vars['header'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['header']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['header']->value) {
$_smarty_tpl->tpl_vars['header']->_loop = true;
$__foreach_header_0_saved_local_item = $_smarty_tpl->tpl_vars['header'];
?>
													<th>
														<?php echo $_smarty_tpl->tpl_vars['header']->value;?>

													</th>
												<?php
$_smarty_tpl->tpl_vars['header'] = $__foreach_header_0_saved_local_item;
}
if ($__foreach_header_0_saved_item) {
$_smarty_tpl->tpl_vars['header'] = $__foreach_header_0_saved_item;
}
?>
											</tr>
										</thead>
										<tbody>
											<?php
$_from = $_smarty_tpl->tpl_vars['all']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_table_1_saved_item = isset($_smarty_tpl->tpl_vars['table']) ? $_smarty_tpl->tpl_vars['table'] : false;
$_smarty_tpl->tpl_vars['table'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['table']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['table']->value) {
$_smarty_tpl->tpl_vars['table']->_loop = true;
$__foreach_table_1_saved_local_item = $_smarty_tpl->tpl_vars['table'];
?>
												<tr class="<?php echo smarty_function_cycle(array('values'=>"odd,even"),$_smarty_tpl);?>
 gradeX">
													<td><?php echo sbDisplayLang(mb_convert_encoding($_smarty_tpl->tpl_vars['table']->value['name'], 'UTF-8', 'HTML-ENTITIES'));?>
</td>
													<td>[CS id=<?php echo $_smarty_tpl->tpl_vars['table']->value['id'];?>
 name=sbtable]</td>
													<td><?php echo $_smarty_tpl->tpl_vars['table']->value['type'];?>
</td>
													<td>
														<span class="glyphicon glyphicon-eye-open <?php if ($_smarty_tpl->tpl_vars['table']->value['active']) {?>green<?php } else { ?>red<?php }?>" title="Statut <?php if ($_smarty_tpl->tpl_vars['table']->value['active']) {?>visible<?php } else { ?>non visible<?php }?>"></span>
														&nbsp;
														<a class="glyphicon glyphicon-cog" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=edit&id=<?php echo $_smarty_tpl->tpl_vars['table']->value['id'];?>
" title="Modifier"></a>
														&nbsp;
														<a class="glyphicon glyphicon-wrench" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=editfield&tid=<?php echo $_smarty_tpl->tpl_vars['table']->value['id'];?>
" title="Modifier la structure"></a>
														&nbsp;
														<a class="glyphicon glyphicon-sort-by-attributes" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=editdatas&tid=<?php echo $_smarty_tpl->tpl_vars['table']->value['id'];?>
" title="Modifier les données"></a>
														&nbsp;
														<a class="glyphicon glyphicon-remove red jConfirm" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=del&id=<?php echo $_smarty_tpl->tpl_vars['table']->value['id'];?>
" title="Supprimer"></a>
													</td>
												</tr>										
											<?php
$_smarty_tpl->tpl_vars['table'] = $__foreach_table_1_saved_local_item;
}
if ($__foreach_table_1_saved_item) {
$_smarty_tpl->tpl_vars['table'] = $__foreach_table_1_saved_item;
}
?>
										</tbody>
									</table>
								</div>
								<!-- /.table-responsive -->
	
							</div>
							<!-- /.panel-body -->
						</div>
						<!-- /.panel -->
					</div>
					<!-- /.col-lg-12 -->
				<?php }?>

				<?php if (!$_smarty_tpl->tpl_vars['all']->value) {?>
					<div class="col-lg-8">
						<div class="panel panel-default">
							<div class="panel-heading">
								<span class="fa fa-list-alt fa-fw"></span> <strong><?php echo $_smarty_tpl->tpl_vars['legend_add_edit']->value;?>
</strong>
							</div>
							<!-- /.panel-heading -->
							<div class="panel-body">
								
								<?php include_once ('/Applications/MAMP/htdocs/sbmagic_new/administration/form.php');?>

							</div>
							<!-- /.panel-body -->
						</div>
						<!-- /.panel -->
					</div>
					<!-- /.col-lg-8 -->
					
					
					<div class="col-lg-4">
						<div class="panel panel-red">
							<div class="panel-heading">
								<i class="fa fa-info-circle fa-fw"></i> Aide
							</div>
							<div class="panel-body">
								<p>
									<?php if ($_smarty_tpl->tpl_vars['allstructure']->value) {?>
										<u>Les types de champs utilisés pour une colonne de votre tableau :</u>
										<br>
										<b>date</b>: Input DATE<br>
										<b>texte</b>: Input TEXT<br>
										<b>textarea</b>: Textarea<br>
										<b>textareaHTML</b>: Editeur CKEditor<br>
										<b>photo</b>: Input PHOTO<br>
										<b>link_image</b>: Icône (lien vers photo)<br>
										<b>link_video</b>: Icône (lien vers vidéo)<br>
										<b>link_file</b>: Icône (lien vers fichier)<br>
										<br>
										<u>Les targets de champs :</u><br>
										<b>blank</b>: Ouvre le lien dans un nouvel onglet<br>
										<b>lightbox</b>: Ouvre votre lien dans une lightbox (NB: <i>Ne pas oublier d'activer la lightbox dans les plugins de l'administration</i>)<br>
										<b>lightbox_fancy</b>: Ouvre votre lien dans une lightbox FANCYBOX (NB: <i>Ne pas oublier d'activer la lightbox dans les plugins de l'administration</i>)<br>
									<?php } elseif ($_smarty_tpl->tpl_vars['alldatas']->value) {?>
										Saissisez les données de votre tableau par entrée (ligne).<br>
										<br>
										Si vous souhaitez modifier la structure de votre tableau, cliquez sur
										le bouton « sa structure ».<br>
										<br>
										Les <span style="color: red; background-color: yellow;">champs en jaune</span>
										dans le
										formulaire et dans votre tableau ci-dessous sont des champs (colonnes
										de tableau) qui ne seront pas affichées sur votre site car elles sont
										désactivées dans la structure de votre tableau. Ils ne sont pas cachés
										dans la saisie de données vous permettant de saisir des informations
										pour plus tard en réactivant le champs dans la structure du tableau.<br>
										<br>
									<?php } elseif ($_GET['a'] == 'edit' || $_GET['a'] == 'add') {?>
										Tableau pleine page (FULL)<br>
										<a style="cursor: pointer;" data-toggle="modal" data-target="#table_full"><img src="<?php echo @constant('_AM_SITE_IMG_URL');?>
modules/sb_table_full.png" alt="" style="max-width: 100%;" /></a>
										<!-- Modal -->
										<div class="modal fade" id="table_full" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
														<h4 class="modal-title" id="myModalLabel">Tableau pleine page (FULL)</h4>
													</div>
													<div class="modal-body">
														<img src="<?php echo @constant('_AM_SITE_IMG_URL');?>
modules/sb_table_full.png" alt="" style="max-width: 100%;" />
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
													</div>
												</div>
												<!-- /.modal-content -->
											</div>
											<!-- /.modal-dialog -->
										</div>
										<!-- /.modal -->
										<br><br>
										Tableau Option 1 (responsive CSS)<br>
										<a style="cursor: pointer;" data-toggle="modal" data-target="#table_option1"><img src="<?php echo @constant('_AM_SITE_IMG_URL');?>
modules/sb_table_option1.png" alt="" style="max-width: 100%;" /></a>
										<!-- Modal -->
										<div class="modal fade" id="table_option1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
														<h4 class="modal-title" id="myModalLabel">Tableau pleine page (FULL)</h4>
													</div>
													<div class="modal-body">
														<img src="<?php echo @constant('_AM_SITE_IMG_URL');?>
modules/sb_table_option1.png" alt="" style="max-width: 100%;" />
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
													</div>
												</div>
												<!-- /.modal-content -->
											</div>
											<!-- /.modal-dialog -->
										</div>
										<!-- /.modal -->
										<br><br>
										Tableau Option 2 (responsive CSS / JS)<br>
										<a style="cursor: pointer;" data-toggle="modal" data-target="#table_option2"><img src="<?php echo @constant('_AM_SITE_IMG_URL');?>
modules/sb_table_option2.png" alt="" style="max-width: 100%;" /></a>
										<!-- Modal -->
										<div class="modal fade" id="table_option2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
														<h4 class="modal-title" id="myModalLabel">Tableau Option 2 (responsive CSS / JS)</h4>
													</div>
													<div class="modal-body">
														<img src="<?php echo @constant('_AM_SITE_IMG_URL');?>
modules/sb_table_option2.png" alt="" style="max-width: 100%;" />
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
													</div>
												</div>
												<!-- /.modal-content -->
											</div>
											<!-- /.modal-dialog -->
										</div>
										<!-- /.modal -->
									<?php }?>
								</p>
							</div>
						</div>
					</div>
					<!-- /.col-lg-4 -->
					
					
				<?php }?>
				
				<?php if ($_smarty_tpl->tpl_vars['allstructure']->value) {?>

					<div class="col-lg-12">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<span class="fa fa-list-alt fa-fw"></span> <strong>Gestion des colonnes du tableau &laquo; <?php echo $_smarty_tpl->tpl_vars['tabname']->value;?>
 &raquo;</strong>
							</div>
							<!-- /.panel-heading -->
							<div class="panel-body">
								<div class="dataTable_wrapper">
									<table class="table table-striped table-bordered table-hover" id="dataTables-tablestructure">
										<thead>
											<tr>
												<?php
$_from = $_smarty_tpl->tpl_vars['sb_table_header']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_header_2_saved_item = isset($_smarty_tpl->tpl_vars['header']) ? $_smarty_tpl->tpl_vars['header'] : false;
$_smarty_tpl->tpl_vars['header'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['header']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['header']->value) {
$_smarty_tpl->tpl_vars['header']->_loop = true;
$__foreach_header_2_saved_local_item = $_smarty_tpl->tpl_vars['header'];
?>
													<th>
														<?php echo $_smarty_tpl->tpl_vars['header']->value;?>

													</th>
												<?php
$_smarty_tpl->tpl_vars['header'] = $__foreach_header_2_saved_local_item;
}
if ($__foreach_header_2_saved_item) {
$_smarty_tpl->tpl_vars['header'] = $__foreach_header_2_saved_item;
}
?>
											</tr>
										</thead>
										<tbody>
											<?php
$_from = $_smarty_tpl->tpl_vars['allstructure']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_column_3_saved_item = isset($_smarty_tpl->tpl_vars['column']) ? $_smarty_tpl->tpl_vars['column'] : false;
$_smarty_tpl->tpl_vars['column'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['column']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['column']->value) {
$_smarty_tpl->tpl_vars['column']->_loop = true;
$__foreach_column_3_saved_local_item = $_smarty_tpl->tpl_vars['column'];
?>
												<tr class="<?php echo smarty_function_cycle(array('values'=>"odd,even"),$_smarty_tpl);?>
 gradeX"<?php if (!$_smarty_tpl->tpl_vars['column']->value['active']) {?> style="background-color: yellow;"<?php }?>>
													<td><?php echo $_smarty_tpl->tpl_vars['column']->value['sort'];?>
</td>
													<td><?php echo sbDisplayLang(mb_convert_encoding($_smarty_tpl->tpl_vars['column']->value['title'], 'UTF-8', 'HTML-ENTITIES'));?>
</td>
													<td><?php echo $_smarty_tpl->tpl_vars['column']->value['field_type'];?>
</td>
													<td><?php echo $_smarty_tpl->tpl_vars['column']->value['field_target'];?>
</td>
													<td>
														<span class="glyphicon glyphicon-eye-open <?php if ($_smarty_tpl->tpl_vars['column']->value['active']) {?>green<?php } else { ?>red<?php }?>" title="Statut <?php if ($_smarty_tpl->tpl_vars['column']->value['active']) {?>visible<?php } else { ?>non visible<?php }?>"></span>
														&nbsp;
														<a class="glyphicon glyphicon-cog" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=editfield&tid=<?php echo $_smarty_tpl->tpl_vars['column']->value['tid'];?>
&id=<?php echo $_smarty_tpl->tpl_vars['column']->value['id'];?>
" title="Modifier"></a>
														&nbsp;
														<a class="glyphicon glyphicon-remove red jConfirm" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=delfield&tid=<?php echo $_smarty_tpl->tpl_vars['column']->value['tid'];?>
&id=<?php echo $_smarty_tpl->tpl_vars['column']->value['id'];?>
" title="Supprimer"></a>
													</td>
												</tr>										
											<?php
$_smarty_tpl->tpl_vars['column'] = $__foreach_column_3_saved_local_item;
}
if ($__foreach_column_3_saved_item) {
$_smarty_tpl->tpl_vars['column'] = $__foreach_column_3_saved_item;
}
?>
										</tbody>
									</table>
								</div>
								<!-- /.table-responsive -->
	
							</div>
							<!-- /.panel-body -->
						</div>
						<!-- /.panel -->
					</div>
					<!-- /.col-lg-12 -->
					
					<div class="col-lg-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								<span class="fa fa-list-alt fa-fw"></span> <strong>Trier les colonnes du tableau &laquo; <span class="red"><?php echo $_smarty_tpl->tpl_vars['tabname']->value;?>
</span> &raquo;</strong>
							</div>
							<!-- /.panel-heading -->
							<div class="panel-body">
								
								<?php echo insert_sortfield(array('tid' => ((string)$_GET['tid'])),$_smarty_tpl);?>

							</div>
							<!-- /.panel-body -->
						</div>
						<!-- /.panel -->
					</div>
					<!-- /.col-lg-12 -->
					
				<?php }?>
				
				<?php if ($_smarty_tpl->tpl_vars['alldatas']->value) {?>

					<div class="col-lg-12">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<span class="fa fa-list-alt fa-fw"></span> <strong>Gestion des données du tableau &laquo; <?php echo $_smarty_tpl->tpl_vars['tabname']->value;?>
 &raquo;</strong>
							</div>
							<!-- /.panel-heading -->
							<div class="panel-body">
								<div class="dataTable_wrapper">
									<table class="table table-striped table-bordered table-hover" id="dataTables-tablestructure">
										<h5 style="margin-bottom: 20px; color: red;">Les colonnes en rouge sont désactivées et ne sont pas affichées sur votre site !</h5>
										<thead>
											<tr>
												<?php
$_from = $_smarty_tpl->tpl_vars['sb_table_header']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_header_4_saved_item = isset($_smarty_tpl->tpl_vars['header']) ? $_smarty_tpl->tpl_vars['header'] : false;
$_smarty_tpl->tpl_vars['header'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['header']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['header']->value) {
$_smarty_tpl->tpl_vars['header']->_loop = true;
$__foreach_header_4_saved_local_item = $_smarty_tpl->tpl_vars['header'];
?>
													<th>
														<?php echo $_smarty_tpl->tpl_vars['header']->value;?>

													</th>
												<?php
$_smarty_tpl->tpl_vars['header'] = $__foreach_header_4_saved_local_item;
}
if ($__foreach_header_4_saved_item) {
$_smarty_tpl->tpl_vars['header'] = $__foreach_header_4_saved_item;
}
?>
											</tr>
										</thead>
										<tbody>
											<?php
$_from = $_smarty_tpl->tpl_vars['alldatas']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_datas_5_saved_item = isset($_smarty_tpl->tpl_vars['datas']) ? $_smarty_tpl->tpl_vars['datas'] : false;
$_smarty_tpl->tpl_vars['datas'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['datas']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['datas']->value) {
$_smarty_tpl->tpl_vars['datas']->_loop = true;
$__foreach_datas_5_saved_local_item = $_smarty_tpl->tpl_vars['datas'];
?>
												<tr class="<?php echo smarty_function_cycle(array('values'=>"odd,even"),$_smarty_tpl);?>
 gradeX">
													<td>
														<?php echo $_smarty_tpl->tpl_vars['datas']->value['sort'];?>

													</td>
													<?php $_smarty_tpl->assign("data_arr" , insert_jsondatas (array('datas' => ((string)$_smarty_tpl->tpl_vars['datas']->value['content']), 'tid' => ((string)$_GET['tid'])),$_smarty_tpl), true);?>
													<?php
$_from = $_smarty_tpl->tpl_vars['data_arr']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_d_6_saved_item = isset($_smarty_tpl->tpl_vars['d']) ? $_smarty_tpl->tpl_vars['d'] : false;
$_smarty_tpl->tpl_vars['d'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['d']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['d']->value) {
$_smarty_tpl->tpl_vars['d']->_loop = true;
$__foreach_d_6_saved_local_item = $_smarty_tpl->tpl_vars['d'];
?>
														<td><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['d']->value,25,"...",true);?>
</td>
													<?php
$_smarty_tpl->tpl_vars['d'] = $__foreach_d_6_saved_local_item;
}
if ($__foreach_d_6_saved_item) {
$_smarty_tpl->tpl_vars['d'] = $__foreach_d_6_saved_item;
}
?>
													<td>
														<a class="glyphicon glyphicon-cog" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=editdatas&tid=<?php echo $_smarty_tpl->tpl_vars['datas']->value['tid'];?>
&id=<?php echo $_smarty_tpl->tpl_vars['datas']->value['id'];?>
" title="Modifier"></a>
														&nbsp;
														<a class="glyphicon glyphicon-remove red jConfirm" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=deldatas&tid=<?php echo $_smarty_tpl->tpl_vars['datas']->value['tid'];?>
&id=<?php echo $_smarty_tpl->tpl_vars['datas']->value['id'];?>
" title="Supprimer"></a>
													</td>
												</tr>										
											<?php
$_smarty_tpl->tpl_vars['datas'] = $__foreach_datas_5_saved_local_item;
}
if ($__foreach_datas_5_saved_item) {
$_smarty_tpl->tpl_vars['datas'] = $__foreach_datas_5_saved_item;
}
?>
										</tbody>
									</table>
								</div>
								<!-- /.table-responsive -->
	
							</div>
							<!-- /.panel-body -->
						</div>
						<!-- /.panel -->
					</div>
					<!-- /.col-lg-12 -->
					
					<div class="col-lg-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								<span class="fa fa-list-alt fa-fw"></span> <strong>Trier les données du tableau &laquo; <span class="red"><?php echo $_smarty_tpl->tpl_vars['tabname']->value;?>
</span> &raquo;</strong>
							</div>
							<!-- /.panel-heading -->
							<div class="panel-body">
								
								<?php echo insert_sortdatas(array('tid' => ((string)$_GET['tid'])),$_smarty_tpl);?>

							</div>
							<!-- /.panel-body -->
						</div>
						<!-- /.panel -->
					</div>
					<!-- /.col-lg-12 -->
					
				<?php }?>
				
            </div>
            <!-- /.row -->
	
		<!-- ------------------------------------------------------------ -->
		<!-- Page-Level Scripts - Use this space this write your own code -->
		<!-- ------------------------------------------------------------ -->
		<?php echo '<script'; ?>
>
		$(document).ready(function() {
			$('#dataTables-table').DataTable({
					order: [ 1, 'desc' ],
					responsive: true,
					"lengthMenu": [25, 50, 75, 100, 150]
			});
			
			$('#dataTables-tablestructure').DataTable({
					order: [ [0, 'asc'] ],
					responsive: true,
					"lengthMenu": [25, 50, 75, 100, 150]
			});
			<?php if ($_smarty_tpl->tpl_vars['sort']->value) {?>
				$( "#sortable" ).sortable({
					axis: "y",
					placeholder: "ui-state-highlight",
					
				});
				$( "#sortable" ).disableSelection();
			<?php }?>			
		});
		<?php echo '</script'; ?>
>
			
	<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:sb_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<?php }
}
