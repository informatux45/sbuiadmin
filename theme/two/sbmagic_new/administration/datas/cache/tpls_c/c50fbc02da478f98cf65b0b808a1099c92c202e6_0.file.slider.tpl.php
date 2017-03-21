<?php
/* Smarty version 3.1.29, created on 2016-12-12 15:21:51
  from "/Applications/MAMP/htdocs/sbmagic_new/administration/datas/modules/tpls/slider.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_584eb27fc7bd71_39907853',
  'file_dependency' => 
  array (
    'c50fbc02da478f98cf65b0b808a1099c92c202e6' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/administration/datas/modules/tpls/slider.tpl',
      1 => 1476452533,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:sb_header.tpl' => 1,
    'file:slider_bar.tpl' => 1,
    'file:sb_footer.tpl' => 1,
  ),
),false)) {
function content_584eb27fc7bd71_39907853 ($_smarty_tpl) {
if (!is_callable('smarty_function_cycle')) require_once '/Applications/MAMP/htdocs/sbmagic_new/administration/core/plugins/function.cycle.php';
?>

	


	
	<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:sb_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('module'=>$_smarty_tpl->tpl_vars['module_page']->value), 0, false);
?>

	
			
			
			
			
			
			<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:slider_bar.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


            <div class="row">
				
                <div class="col-lg-12">
                    <div class="well">
						<img src="img/screenshot-slider.jpg" style="width: 675px; max-width: 100%;" alt="" />
                    </div>
                </div>
                <!-- /.col-lg-12 -->
				
				<?php if ($_smarty_tpl->tpl_vars['all']->value) {?>
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <span class="fa fa-sliders fa-fw"></span> <strong><?php if ($_smarty_tpl->tpl_vars['all']->value) {?>Gestion de vos slider<?php } else {
echo $_smarty_tpl->tpl_vars['legend_add_edit']->value;
}?></strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-sliders">
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
$__foreach_slider_1_saved_item = isset($_smarty_tpl->tpl_vars['slider']) ? $_smarty_tpl->tpl_vars['slider'] : false;
$_smarty_tpl->tpl_vars['slider'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['slider']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['slider']->value) {
$_smarty_tpl->tpl_vars['slider']->_loop = true;
$__foreach_slider_1_saved_local_item = $_smarty_tpl->tpl_vars['slider'];
?>
											<tr class="<?php echo smarty_function_cycle(array('values'=>"odd,even"),$_smarty_tpl);?>
 gradeX">
												<td><?php echo sbDisplayLang(mb_convert_encoding($_smarty_tpl->tpl_vars['slider']->value['title'], 'UTF-8', 'HTML-ENTITIES'));?>
</td>
												<td><?php echo mb_convert_encoding($_smarty_tpl->tpl_vars['slider']->value['mode'], 'UTF-8', 'HTML-ENTITIES');?>
</td>
												<td><?php echo (($tmp = @$_smarty_tpl->tpl_vars['slider']->value['cpt_img'])===null||$tmp==='' ? 0 : $tmp);?>
</td>
												<td>[CS id=<?php echo $_smarty_tpl->tpl_vars['slider']->value['id'];?>
 name=sbslider]</td>
												<td>
													<span class="glyphicon glyphicon-eye-open <?php if ($_smarty_tpl->tpl_vars['slider']->value['active']) {?>green<?php } else { ?>red<?php }?>" title="Statut <?php if ($_smarty_tpl->tpl_vars['slider']->value['active']) {?>visible<?php } else { ?>non visible<?php }?>"></span>
													&nbsp;
													<a class="glyphicon glyphicon-picture" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=photo&sid=<?php echo $_smarty_tpl->tpl_vars['slider']->value['id'];?>
" title="Toutes les photos"></a>
													&nbsp;
													<a class="glyphicon glyphicon-sort-by-attributes" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=sort&sid=<?php echo $_smarty_tpl->tpl_vars['slider']->value['id'];?>
" title="Trier les photos"></a>
													&nbsp;
													<a class="glyphicon glyphicon-cog" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=edit&id=<?php echo $_smarty_tpl->tpl_vars['slider']->value['id'];?>
" title="Modifier"></a>
													&nbsp;
													<a class="glyphicon glyphicon-remove red jConfirm" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=del&id=<?php echo $_smarty_tpl->tpl_vars['slider']->value['id'];?>
" title="Supprimer"></a>
												</td>
											</tr>										
										<?php
$_smarty_tpl->tpl_vars['slider'] = $__foreach_slider_1_saved_local_item;
}
if ($__foreach_slider_1_saved_item) {
$_smarty_tpl->tpl_vars['slider'] = $__foreach_slider_1_saved_item;
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
				
				<?php if ($_smarty_tpl->tpl_vars['allphoto']->value) {?>
				
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <span class="fa fa-sliders fa-fw"></span> <strong><?php if ($_smarty_tpl->tpl_vars['allphoto']->value) {?>Gestion de vos slider<?php } else {
echo $_smarty_tpl->tpl_vars['legend_add_edit']->value;
}?></strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-photos">
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
$_from = $_smarty_tpl->tpl_vars['allphoto']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_photo_3_saved_item = isset($_smarty_tpl->tpl_vars['photo']) ? $_smarty_tpl->tpl_vars['photo'] : false;
$_smarty_tpl->tpl_vars['photo'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['photo']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['photo']->value) {
$_smarty_tpl->tpl_vars['photo']->_loop = true;
$__foreach_photo_3_saved_local_item = $_smarty_tpl->tpl_vars['photo'];
?>
											<tr class="<?php echo smarty_function_cycle(array('values'=>"odd,even"),$_smarty_tpl);?>
 gradeX">
												<td><?php echo $_smarty_tpl->tpl_vars['photo']->value['sort'];?>
</td>
												<td><?php echo $_smarty_tpl->tpl_vars['photo']->value['photo'];?>
</td>
												<td><?php echo sbDisplayLang(mb_convert_encoding($_smarty_tpl->tpl_vars['photo']->value['title'], 'UTF-8', 'HTML-ENTITIES'));?>
</td>
												<td>
													<span class="glyphicon glyphicon-eye-open <?php if ($_smarty_tpl->tpl_vars['photo']->value['active']) {?>green<?php } else { ?>red<?php }?>" title="Statut <?php if ($_smarty_tpl->tpl_vars['photo']->value['active']) {?>visible<?php } else { ?>non visible<?php }?>"></span>
													&nbsp;
													<a class="glyphicon glyphicon-cog" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=photoedit&id=<?php echo $_smarty_tpl->tpl_vars['photo']->value['id'];?>
&sid=<?php echo $_smarty_tpl->tpl_vars['sid']->value;?>
" title="Modifier"></a>
													&nbsp;
													<a class="glyphicon glyphicon-remove red jConfirm" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=delphoto&sid=<?php echo $_smarty_tpl->tpl_vars['sid']->value;?>
&id=<?php echo $_smarty_tpl->tpl_vars['photo']->value['id'];?>
" title="Supprimer"></a>
												</td>
											</tr>										
										<?php
$_smarty_tpl->tpl_vars['photo'] = $__foreach_photo_3_saved_local_item;
}
if ($__foreach_photo_3_saved_item) {
$_smarty_tpl->tpl_vars['photo'] = $__foreach_photo_3_saved_item;
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
				
				<?php if ((!$_smarty_tpl->tpl_vars['all']->value || !$_smarty_tpl->tpl_vars['allphoto']->value) && ($_GET['a'] && $_GET['a'] != 'photo')) {?>
				<?php if ($_GET['a'] == 'edit' || $_GET['a'] == 'add') {?>
					<style>
					/* --- Icons 2 (form) --- */
					.input-group-addon, .input-group-btn { width: auto !important; }
					</style>
				<?php }?>
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span class="fa fa-sliders fa-fw"></span> <strong><?php echo $_smarty_tpl->tpl_vars['legend_add_edit']->value;?>
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
			$('#dataTables-sliders').DataTable({
					responsive: true,
					"lengthMenu": [25, 50, 75, 100, 150]
			});
			$('#dataTables-photos').DataTable({
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
