<?php
/* Smarty version 3.1.29, created on 2016-12-12 11:57:13
  from "/Applications/MAMP/htdocs/sbmagic_new/administration/datas/modules/tpls/tabbs.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_584e8289ae93f5_52319415',
  'file_dependency' => 
  array (
    'b3f88b20fdc1cd70fe4b76ffea980239701b059b' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/administration/datas/modules/tpls/tabbs.tpl',
      1 => 1480937408,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:sb_header.tpl' => 1,
    'file:tabbs_bar.tpl' => 1,
    'file:sb_footer.tpl' => 1,
  ),
),false)) {
function content_584e8289ae93f5_52319415 ($_smarty_tpl) {
if (!is_callable('smarty_function_cycle')) require_once '/Applications/MAMP/htdocs/sbmagic_new/administration/core/plugins/function.cycle.php';
?>

	


	
	<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:sb_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('module'=>$_smarty_tpl->tpl_vars['module_page']->value), 0, false);
?>

	
			
			
			
			
			
			<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:tabbs_bar.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


            <div class="row">
				
				<div class="col-lg-12">
					<div class="panel panel-info">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion" href="#collapse" aria-expanded="false" class="collapsed"><i class="fa fa-arrow-circle-down"></i> Que fait TABBS ?</a>
							</h4>
						</div>
						<div id="collapse" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
							<div class="panel-body">
								<p style="font-size: 1.2em;">
									TABBS permet :
									<ol>
										<li>d'afficher des contenus dans des onglets</li>
										<li>de créez autant d'onglets que vous le souhaitez</li>
										<li>d'insérer du texte, du code HTML, des shortcodes</li>
									</ol>
									Les onglets sont responsives et vous pouvez les customiser par CSS.
								</p>
								<p>
									<img src="img/modules/tabbs_example_1.png" alt="Exemple TABBS" style="max-width: 100%;" />
								</p>
							</div>
						</div>
					</div>
				</div>
				
				<?php if ($_smarty_tpl->tpl_vars['all']->value) {?>

					<div class="col-lg-12">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<span class="fa fa-list-alt fa-fw"></span> <strong><?php if ($_smarty_tpl->tpl_vars['all']->value) {?>Gestion de vos tabbs<?php } else {
echo $_smarty_tpl->tpl_vars['legend_add_edit']->value;
}?></strong>
							</div>
							<!-- /.panel-heading -->
							<div class="panel-body">
								<div class="dataTable_wrapper">
									<table class="table table-striped table-bordered table-hover" id="dataTables-tabbs">
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
$__foreach_tabbs_1_saved_item = isset($_smarty_tpl->tpl_vars['tabbs']) ? $_smarty_tpl->tpl_vars['tabbs'] : false;
$_smarty_tpl->tpl_vars['tabbs'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['tabbs']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['tabbs']->value) {
$_smarty_tpl->tpl_vars['tabbs']->_loop = true;
$__foreach_tabbs_1_saved_local_item = $_smarty_tpl->tpl_vars['tabbs'];
?>
												<tr class="<?php echo smarty_function_cycle(array('values'=>"odd,even"),$_smarty_tpl);?>
 gradeX">
													<td><?php echo sbDisplayLang(mb_convert_encoding($_smarty_tpl->tpl_vars['tabbs']->value['name'], 'UTF-8', 'HTML-ENTITIES'));?>
</td>
													<td>[CS id=<?php echo $_smarty_tpl->tpl_vars['tabbs']->value['id'];?>
 name=sbtabbs]</td>
													<td>
														<span class="glyphicon glyphicon-eye-open <?php if ($_smarty_tpl->tpl_vars['tabbs']->value['active']) {?>green<?php } else { ?>red<?php }?>" title="Statut <?php if ($_smarty_tpl->tpl_vars['tabbs']->value['active']) {?>visible<?php } else { ?>non visible<?php }?>"></span>
														&nbsp;
														<a class="glyphicon glyphicon-cog" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=edit&id=<?php echo $_smarty_tpl->tpl_vars['tabbs']->value['id'];?>
" title="Modifier"></a>
														&nbsp;
														<a class="glyphicon glyphicon-sort-by-attributes" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=sort&tid=<?php echo $_smarty_tpl->tpl_vars['tabbs']->value['id'];?>
" title="Trier les onglets"></a>
														&nbsp;
														<a class="glyphicon glyphicon-remove red jConfirm" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=del&id=<?php echo $_smarty_tpl->tpl_vars['tabbs']->value['id'];?>
" title="Supprimer"></a>
													</td>
												</tr>										
											<?php
$_smarty_tpl->tpl_vars['tabbs'] = $__foreach_tabbs_1_saved_local_item;
}
if ($__foreach_tabbs_1_saved_item) {
$_smarty_tpl->tpl_vars['tabbs'] = $__foreach_tabbs_1_saved_item;
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
				
				<?php if ($_smarty_tpl->tpl_vars['alltabs']->value) {?>
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <span class="fa fa-list-alt fa-fw"></span> <strong>Gestion de vos onglets</strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-tabbstab">
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
$_from = $_smarty_tpl->tpl_vars['alltabs']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_tabs_3_saved_item = isset($_smarty_tpl->tpl_vars['tabs']) ? $_smarty_tpl->tpl_vars['tabs'] : false;
$_smarty_tpl->tpl_vars['tabs'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['tabs']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['tabs']->value) {
$_smarty_tpl->tpl_vars['tabs']->_loop = true;
$__foreach_tabs_3_saved_local_item = $_smarty_tpl->tpl_vars['tabs'];
?>
											<tr class="<?php echo smarty_function_cycle(array('values'=>"odd,even"),$_smarty_tpl);?>
 gradeX">
												<td><?php echo sbDisplayLang(mb_convert_encoding($_smarty_tpl->tpl_vars['tabs']->value['title'], 'UTF-8', 'HTML-ENTITIES'));?>
</td>
												<td><?php echo sbDisplayLang(mb_convert_encoding($_smarty_tpl->tpl_vars['tabs']->value['catname'], 'UTF-8', 'HTML-ENTITIES'));?>
</td>
												<td>
													<span class="glyphicon glyphicon-eye-open <?php if ($_smarty_tpl->tpl_vars['tabs']->value['active']) {?>green<?php } else { ?>red<?php }?>" title="Statut <?php if ($_smarty_tpl->tpl_vars['tabs']->value['active']) {?>visible<?php } else { ?>non visible<?php }?>"></span>
													&nbsp;
													<a class="glyphicon glyphicon-cog" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=tabedit&id=<?php echo $_smarty_tpl->tpl_vars['tabs']->value['id'];?>
" title="Modifier"></a>
													&nbsp;
													<a class="glyphicon glyphicon-remove red jConfirm" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=tabdel&id=<?php echo $_smarty_tpl->tpl_vars['tabs']->value['id'];?>
" title="Supprimer"></a>
												</td>
											</tr>										
										<?php
$_smarty_tpl->tpl_vars['tabs'] = $__foreach_tabs_3_saved_local_item;
}
if ($__foreach_tabs_3_saved_item) {
$_smarty_tpl->tpl_vars['tabs'] = $__foreach_tabs_3_saved_item;
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

				<?php if ((!$_smarty_tpl->tpl_vars['all']->value || !$_smarty_tpl->tpl_vars['alltabs']->value) && ($_GET['a'] && $_GET['a'] != 'alltabs' && $_GET['a'] != 'del' && $_GET['a'] != 'tabdel')) {?>
					<div class="col-lg-12">
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
			$('#dataTables-tabbs').DataTable({
					order: [ 0, 'asc' ],
					responsive: true,
					"lengthMenu": [25, 50, 75, 100, 150]
			});
			$('#dataTables-tabbstab').DataTable({
					order: [ [1, 'asc'], [0, 'asc'] ],
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
