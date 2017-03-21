<?php
/* Smarty version 3.1.29, created on 2016-12-16 15:58:56
  from "/Applications/MAMP/htdocs/sbmagic_new/administration/datas/modules/tpls/effectives.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_5854013086d2a3_87265816',
  'file_dependency' => 
  array (
    '64d6e8142a1e192ed29a3c6b1c1f1210cc862a8f' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/administration/datas/modules/tpls/effectives.tpl',
      1 => 1481703331,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:sb_header.tpl' => 1,
    'file:effectives_bar.tpl' => 1,
    'file:shared/shared-slider-4col.tpl' => 1,
    'file:sb_footer.tpl' => 1,
  ),
),false)) {
function content_5854013086d2a3_87265816 ($_smarty_tpl) {
if (!is_callable('smarty_function_cycle')) require_once '/Applications/MAMP/htdocs/sbmagic_new/administration/core/plugins/function.cycle.php';
?>

	


	
	<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:sb_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('module'=>$_smarty_tpl->tpl_vars['module_page']->value), 0, false);
?>

	
			
			
			
			
			
			<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:effectives_bar.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


            <div class="row">
				
				<?php if ($_smarty_tpl->tpl_vars['all']->value) {?>
					<div class="col-lg-12">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<span class="fa fa-trophy fa-fw"></span> <strong><?php if ($_smarty_tpl->tpl_vars['all']->value) {?>Gestion de vos effectifs<?php } else {
echo $_smarty_tpl->tpl_vars['legend_add_edit']->value;
}?></strong>
							</div>
							<!-- /.panel-heading -->
							<div class="panel-body">
								<div class="dataTable_wrapper">
									<table class="table table-striped table-bordered table-hover" id="dataTables-news">
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
$__foreach_effectives_1_saved_item = isset($_smarty_tpl->tpl_vars['effectives']) ? $_smarty_tpl->tpl_vars['effectives'] : false;
$_smarty_tpl->tpl_vars['effectives'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['effectives']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['effectives']->value) {
$_smarty_tpl->tpl_vars['effectives']->_loop = true;
$__foreach_effectives_1_saved_local_item = $_smarty_tpl->tpl_vars['effectives'];
?>
												<tr class="<?php echo smarty_function_cycle(array('values'=>"odd,even"),$_smarty_tpl);?>
 gradeX">
													<td><?php echo mb_convert_encoding($_smarty_tpl->tpl_vars['effectives']->value['date'], 'UTF-8', 'HTML-ENTITIES');?>
</td>
													<td><?php echo sbDisplayLang(mb_convert_encoding($_smarty_tpl->tpl_vars['effectives']->value['name'], 'UTF-8', 'HTML-ENTITIES'));?>
</td>
													<td><?php echo sbDisplayLang(mb_convert_encoding($_smarty_tpl->tpl_vars['effectives']->value['catname'], 'UTF-8', 'HTML-ENTITIES'));?>
</td>
													<td>[CS id=<?php echo $_smarty_tpl->tpl_vars['effectives']->value['id'];?>
 name=sbeffectives_item]</td>
													<td>
														<span class="glyphicon glyphicon-eye-open <?php if ($_smarty_tpl->tpl_vars['effectives']->value['active']) {?>green<?php } else { ?>red<?php }?>" title="Statut <?php if ($_smarty_tpl->tpl_vars['effectives']->value['active']) {?>visible<?php } else { ?>non visible<?php }?>"></span>
														&nbsp;
														<a class="glyphicon glyphicon-picture" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=medias&id=<?php echo $_smarty_tpl->tpl_vars['effectives']->value['id'];?>
" title="Tous les medias"></a>
														&nbsp;
														<a class="glyphicon glyphicon-knight" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=production&eid=<?php echo $_smarty_tpl->tpl_vars['effectives']->value['id'];?>
" title="Toute la production"></a>
														&nbsp;
														<a class="glyphicon glyphicon-cog" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=edit&id=<?php echo $_smarty_tpl->tpl_vars['effectives']->value['id'];?>
" title="Modifier"></a>
														&nbsp;
														<a class="glyphicon glyphicon-remove red jConfirm" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=del&id=<?php echo $_smarty_tpl->tpl_vars['effectives']->value['id'];?>
" title="Supprimer"></a>
													</td>
												</tr>										
											<?php
$_smarty_tpl->tpl_vars['effectives'] = $__foreach_effectives_1_saved_local_item;
}
if ($__foreach_effectives_1_saved_item) {
$_smarty_tpl->tpl_vars['effectives'] = $__foreach_effectives_1_saved_item;
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
				
				<?php if ($_smarty_tpl->tpl_vars['allcategory']->value) {?>
				
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <span class="fa fa-trophy fa-fw"></span> <strong><?php if ($_smarty_tpl->tpl_vars['allcategory']->value) {?>Gestion de vos catégories<?php } else {
echo $_smarty_tpl->tpl_vars['legend_add_edit']->value;
}?></strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-categories">
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
$_from = $_smarty_tpl->tpl_vars['allcategory']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_category_3_saved_item = isset($_smarty_tpl->tpl_vars['category']) ? $_smarty_tpl->tpl_vars['category'] : false;
$_smarty_tpl->tpl_vars['category'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['category']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['category']->value) {
$_smarty_tpl->tpl_vars['category']->_loop = true;
$__foreach_category_3_saved_local_item = $_smarty_tpl->tpl_vars['category'];
?>
											<tr class="<?php echo smarty_function_cycle(array('values'=>"odd,even"),$_smarty_tpl);?>
 gradeX">
												<td><?php echo $_smarty_tpl->tpl_vars['category']->value['sort'];?>
</td>
												<td><?php echo sbDisplayLang(mb_convert_encoding($_smarty_tpl->tpl_vars['category']->value['title'], 'UTF-8', 'HTML-ENTITIES'));?>
</td>
												<td><?php echo sbDisplayLang(mb_convert_encoding($_smarty_tpl->tpl_vars['category']->value['subtitle'], 'UTF-8', 'HTML-ENTITIES'));?>
</td>
												<td>
													<span class="glyphicon glyphicon-eye-open <?php if ($_smarty_tpl->tpl_vars['category']->value['active']) {?>green<?php } else { ?>red<?php }?>" title="Statut <?php if ($_smarty_tpl->tpl_vars['category']->value['active']) {?>visible<?php } else { ?>non visible<?php }?>"></span>
													&nbsp;
													<a class="glyphicon glyphicon-object-align-top yellow" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=tpl_list&id=<?php echo $_smarty_tpl->tpl_vars['category']->value['id'];?>
" title="TPL LIST"></a>
													&nbsp;
													<a class="glyphicon glyphicon-object-align-left yellow" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=tpl_single&id=<?php echo $_smarty_tpl->tpl_vars['category']->value['id'];?>
" title="TPL SINGLE"></a>
													&nbsp;
													<a class="glyphicon glyphicon-wrench green" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=settingscategory&id=<?php echo $_smarty_tpl->tpl_vars['category']->value['id'];?>
" title="Paramètres"></a>
													&nbsp;
													<a class="glyphicon glyphicon-cog" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=categoryedit&id=<?php echo $_smarty_tpl->tpl_vars['category']->value['id'];?>
" title="Modifier"></a>
													&nbsp;
													<a class="glyphicon glyphicon-remove red jConfirm" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=categorydel&id=<?php echo $_smarty_tpl->tpl_vars['category']->value['id'];?>
" title="Supprimer"></a>
												</td>
											</tr>										
										<?php
$_smarty_tpl->tpl_vars['category'] = $__foreach_category_3_saved_local_item;
}
if ($__foreach_category_3_saved_item) {
$_smarty_tpl->tpl_vars['category'] = $__foreach_category_3_saved_item;
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
				
				<?php if ($_smarty_tpl->tpl_vars['allmedias']->value) {?>
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <span class="fa fa-trophy fa-fw"></span> <strong><?php if ($_smarty_tpl->tpl_vars['allmedias']->value) {?>Gestion de vos medias<?php } else {
echo $_smarty_tpl->tpl_vars['legend_add_edit']->value;
}?></strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-categories">
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
$_from = $_smarty_tpl->tpl_vars['allmedias']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_media_5_saved_item = isset($_smarty_tpl->tpl_vars['media']) ? $_smarty_tpl->tpl_vars['media'] : false;
$_smarty_tpl->tpl_vars['media'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['media']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['media']->value) {
$_smarty_tpl->tpl_vars['media']->_loop = true;
$__foreach_media_5_saved_local_item = $_smarty_tpl->tpl_vars['media'];
?>
											<tr class="<?php echo smarty_function_cycle(array('values'=>"odd,even"),$_smarty_tpl);?>
 gradeX">
												<td><?php echo $_smarty_tpl->tpl_vars['media']->value['sort'];?>
</td>
												<td><?php echo sbDisplayLang(mb_convert_encoding($_smarty_tpl->tpl_vars['media']->value['title'], 'UTF-8', 'HTML-ENTITIES'));?>
</td>
												<td><?php echo sbDisplayLang(mb_convert_encoding($_smarty_tpl->tpl_vars['media']->value['effective_name'], 'UTF-8', 'HTML-ENTITIES'));?>
</td>
												<td>
													<span class="glyphicon glyphicon-eye-open <?php if ($_smarty_tpl->tpl_vars['media']->value['active']) {?>green<?php } else { ?>red<?php }?>" title="Statut <?php if ($_smarty_tpl->tpl_vars['media']->value['active']) {?>visible<?php } else { ?>non visible<?php }?>"></span>
													&nbsp;
													<a class="glyphicon glyphicon-cog" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=mediasedit&id=<?php echo $_smarty_tpl->tpl_vars['media']->value['id'];?>
&eid=<?php echo $_smarty_tpl->tpl_vars['media']->value['eid'];?>
" title="Modifier"></a>
													&nbsp;
													<a class="glyphicon glyphicon-remove red jConfirm" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=mediasdel&id=<?php echo $_smarty_tpl->tpl_vars['media']->value['eid'];?>
&mid=<?php echo $_smarty_tpl->tpl_vars['media']->value['id'];?>
" title="Supprimer"></a>
												</td>
											</tr>										
										<?php
$_smarty_tpl->tpl_vars['media'] = $__foreach_media_5_saved_local_item;
}
if ($__foreach_media_5_saved_item) {
$_smarty_tpl->tpl_vars['media'] = $__foreach_media_5_saved_item;
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

				<?php if ($_smarty_tpl->tpl_vars['allproduction']->value) {?>
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <span class="fa fa-trophy fa-fw"></span> <strong><?php if ($_smarty_tpl->tpl_vars['allproduction']->value) {?>Gestion de vos productions<?php } else {
echo $_smarty_tpl->tpl_vars['legend_add_edit']->value;
}?></strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-categories">
                                    <thead>
                                        <tr>
                                            <?php
$_from = $_smarty_tpl->tpl_vars['sb_table_header']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_header_6_saved_item = isset($_smarty_tpl->tpl_vars['header']) ? $_smarty_tpl->tpl_vars['header'] : false;
$_smarty_tpl->tpl_vars['header'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['header']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['header']->value) {
$_smarty_tpl->tpl_vars['header']->_loop = true;
$__foreach_header_6_saved_local_item = $_smarty_tpl->tpl_vars['header'];
?>
												<th>
													<?php echo $_smarty_tpl->tpl_vars['header']->value;?>

												</th>
											<?php
$_smarty_tpl->tpl_vars['header'] = $__foreach_header_6_saved_local_item;
}
if ($__foreach_header_6_saved_item) {
$_smarty_tpl->tpl_vars['header'] = $__foreach_header_6_saved_item;
}
?>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php
$_from = $_smarty_tpl->tpl_vars['allproduction']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_production_7_saved_item = isset($_smarty_tpl->tpl_vars['production']) ? $_smarty_tpl->tpl_vars['production'] : false;
$_smarty_tpl->tpl_vars['production'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['production']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['production']->value) {
$_smarty_tpl->tpl_vars['production']->_loop = true;
$__foreach_production_7_saved_local_item = $_smarty_tpl->tpl_vars['production'];
?>
											<tr class="<?php echo smarty_function_cycle(array('values'=>"odd,even"),$_smarty_tpl);?>
 gradeX">
												<td><?php echo $_smarty_tpl->tpl_vars['production']->value['sort'];?>
</td>
												<td><?php echo sbDisplayLang(mb_convert_encoding($_smarty_tpl->tpl_vars['production']->value['name'], 'UTF-8', 'HTML-ENTITIES'));?>
</td>
												<td><?php echo sbDisplayLang(mb_convert_encoding($_smarty_tpl->tpl_vars['production']->value['effective_name'], 'UTF-8', 'HTML-ENTITIES'));?>
</td>
												<td>
													<span class="glyphicon glyphicon-eye-open <?php if ($_smarty_tpl->tpl_vars['production']->value['active']) {?>green<?php } else { ?>red<?php }?>" title="Statut <?php if ($_smarty_tpl->tpl_vars['production']->value['active']) {?>visible<?php } else { ?>non visible<?php }?>"></span>
													&nbsp;
													<a class="glyphicon glyphicon-cog" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=productionedit&id=<?php echo $_smarty_tpl->tpl_vars['production']->value['id'];?>
&eid=<?php echo $_smarty_tpl->tpl_vars['production']->value['eid'];?>
" title="Modifier"></a>
													&nbsp;
													<a class="glyphicon glyphicon-remove red jConfirm" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=productiondel&id=<?php echo $_smarty_tpl->tpl_vars['production']->value['id'];?>
&eid=<?php echo $_smarty_tpl->tpl_vars['production']->value['eid'];?>
" title="Supprimer"></a>
												</td>
											</tr>										
										<?php
$_smarty_tpl->tpl_vars['production'] = $__foreach_production_7_saved_local_item;
}
if ($__foreach_production_7_saved_item) {
$_smarty_tpl->tpl_vars['production'] = $__foreach_production_7_saved_item;
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

				<?php if ((!$_smarty_tpl->tpl_vars['all']->value || !$_smarty_tpl->tpl_vars['allcategory']->value || !$_smarty_tpl->tpl_vars['allmedias']->value || !$_smarty_tpl->tpl_vars['allproduction']->value) && ($_GET['a'] && $_GET['a'] != 'category' && $_GET['a'] != 'medias' && $_GET['a'] != 'production' && $_GET['a'] != 'mediasdel')) {?>
					<div class="col-lg-<?php if ($_GET['a'] == 'categoryedit' || $_GET['a'] == 'tpl_list' || $_GET['a'] == 'tpl_single' || $_smarty_tpl->tpl_vars['show_headpage']->value || $_smarty_tpl->tpl_vars['effectives_help']->value) {?>8<?php } else { ?>12<?php }?>">
						<div class="panel panel-default">
							<div class="panel-heading">
								<span class="fa fa-trophy fa-fw"></span> <strong><?php echo $_smarty_tpl->tpl_vars['legend_add_edit']->value;?>
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
					
					<?php if ($_smarty_tpl->tpl_vars['show_headpage']->value) {?>
						<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:shared/shared-slider-4col.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

					<?php }?>
					
					<?php if ($_smarty_tpl->tpl_vars['effectives_help']->value) {?>
						<div class="col-lg-4">
							<div class="panel panel-default">
								<div class="panel-heading">
									<span class="fa fa-info-circle fa-fw"></span> <strong>Aide</strong>
								</div>
								<!-- /.panel-heading -->
								<div class="panel-body">
									<?php echo html_entity_decode($_smarty_tpl->tpl_vars['effectives_help']->value);?>

								</div>
								<!-- /.panel-body -->
							</div>
							<!-- /.panel -->
						</div>
						<!-- /.col-lg-4 -->
					<?php }?>
					
					<?php if ($_GET['a'] == 'categoryedit' || $_GET['a'] == 'tpl_list' || $_GET['a'] == 'tpl_single') {?>
						<?php if ($_GET['a'] == 'tpl_list' || $_GET['a'] == 'tpl_single') {?>
							<div class="col-lg-4">
								<div class="panel panel-red">
									<div class="panel-heading">
										Variables à utiliser dans votre code <?php if ($_GET['a'] == 'tpl_list') {?>TPL LIST<?php } else { ?>TPL SINGLE<?php }?>
									</div>
									<div class="panel-body">
										<p>
											<?php if ($_GET['a'] == 'tpl_list') {?>
												
													<style>
														ul.tpl_list li {font-size: 12px; list-style: none; margin-left: -40px;}
													</style>
													<p>VARIABLES :
													<ul class="tpl_list">
														<li>Catégorie: <b>{$effectives.catname}</b></li>
														<li>Nom: <b>{$effectives.name}</b></li>
														<li>Image: <b>{$smarty.const._AM_MEDIAS_URL}/{$effectives.photo}</b></li>
														<li>Père: <b>{$effectives.sire}</b></li>
														<li>Mère: <b>{$effectives.dam}</b></li>
														<li>Père de mère: <b>{$effectives.sire_dam}</b></li>
														<li>Saillie: <b>{$effectives.projection}</b></li>
														<li>Lien catégorie:<br>
														<b>{seo url="index.php?p=effectives&op=article&id={$effectives.id}" rewrite="effectives/article/{$effectives.id}/{$effectives.title|unescape:"htmlall"<br>|@sbDisplayLang:"`$smarty.session.lang`"<br>|strip_tags|@sbRewriteString|@strtolower}"}</b></li>
														<li></li>
													</ul>
													</p>
													<p>CONSTANTES :
													<ul class="tpl_list">
														<li><b>{$smarty.const._CMS_EFFECTIVES_AND}</b>:  &</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_BY}</b>:  par</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_READ_ITEM}</b>:  Consulter sa page</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES}</b>:  Effectif</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_S}</b>:  Effectifs</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_ALL}</b>:  Tous les effectifs</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_NOITEM}</b>:  Pas d'effectifs disponible !</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_NOCATEGORIES}</b>:  Aucune catégorie disponible !</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_BORN}</b>:  né en</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_BORNDATE}</b>:  en</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_BREEDER}</b>:  Eleveur</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_OWNER}</b>:  Propriétaire</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_NOPROD}</b>:  Aucune production</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_NOMEDIAS}</b>:  Aucun médias</li>
													</ul>
													</p>
													<p>PIPEs possible :<br>
													<b>|unescape:"htmlall"</b>&nbsp;(Echappement HTML)<br>
													<b>|@sbDisplayLang:"`$smarty.session.lang`"</b>&nbsp;(Conversion Langue Session)
													</p>
												
											<?php } else { ?>
												
													<style>
														ul.tpl_single li {font-size: 12px; list-style: none; margin-left: -40px;}
													</style>
													<p>VARIABLES :
													<ul class="tpl_single">
														<li>Date (Année): <b>{$item.date|@sbConvertDate:"YEAR"}</b></li>
														<li>Date (US): <b>{$item.date}</b></li>
														<li>Date (FR): <b>{$item.date|@sbConvertDate:"FR"}</b></li>
														<li>Nom: <b>{$item.name}</b></li>
														<li>Sous titre 1: <b>{$item.subtitle1}</b></li>
														<li>Sous titre 2: <b>{$item.subtitle2}</b></li>
														<li>Sexe: <b>{$item.sex}</b></li>
														<li>Robe: <b>{$item.colour}</b></li>
														<li>Taile: <b>{$item.size}</b></li>
														<li>Père: <b>{$item.sire}</b></li>
														<li>Mère: <b>{$item.dam}</b></li>
														<li>Père de mère: <b>{$item.sire_dam}</b></li>
														<li>Pays: <b>{$item.origine}</b></li>
														<li>Eleveur: <b>{$item.breeder}</b></li>
														<li>Propriétaire: <b>{$item.owner}</b></li>
														<li>Chrono: <b>{$item.chrono}</b></li>
														<li>Gains: <b>{$item.winnings}</b></li>
														<li>Statut: <b>{$item.status}</b></li>
														<li>Saillie: <b>{$item.projection}</b></li>
														<li>Description: <b>{$item.description}</b></li>
														<li>Pedigree Desc: <b>{$item.pedigree_desc}</b></li>
														<li>Pedigree (PDF):<br><b>{$smarty.const._AM_MEDIAS_URL}/{$item.pedigree}</b></li>
														<li>Pedigree Extend (PDF):<br><b>{$smarty.const._AM_MEDIAS_URL}/{$item.pedigree_extend}</b></li>
														<li>Photo:<br><b>{$smarty.const._AM_MEDIAS_URL}/{$item.photo}</b></li>
													</ul>
													</p>
													<p>TITRES :
													<ul class="tpl_single">
														<li>Description: <b>{$sbeffectives_options.title_description}</b></li>
														<li>Pedigree: <b>{$sbeffectives_options.title_pedigree}</b></li>
														<li>Production: <b>{$sbeffectives_options.title_production}</b></li>
														<li>Medias: <b>{$sbeffectives_options.title_medias}</b></li>
													</ul>
													</p>
													<p>PRODUCTION (Boucle type) :
													<ul class="tpl_single">
														<li><b>
															{foreach from=$production item=prod}<br>
																&lt;p><br>
																	{if $prod.date}<br>
																		{$prod.date|@sbConvertDate:"YEAR"} -<br>
																	{/if}<br>
																	{if $prod.group_bold}<br>
																		&lt;b>{$prod.name|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}&lt;/b><br>
																	{else}<br>
																		{$prod.name|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}<br>
																	{/if}<br>
																	<br>
																	{if $prod.sex}<br>
																		({$prod.sex|lower}.{if $prod.colour|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"} {$prod.colour|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}{/if})<br>
																	{/if}<br>
																	<br>
																	{if $prod.photo}<br>
																		{insert name=sbeffectives_medias type="photo" url="`$prod.photo`" title="`$prod.name`" media="icon"}<br>
																	{/if}<br>
																	{if $prod.video}<br>
																		{insert name=sbeffectives_medias type="youtube" url="`$prod.video`" title="`$prod.name`" media="icon"}<br>
																	{/if}<br>
																	{if $prod.pedigree}<br>
																		{insert name=sbeffectives_medias type="pdf" url="`$prod.pedigree`" title="`$prod.name`" media="icon"}<br>
																	{/if}<br>
																	<br>
																	{if $prod.dam|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}<br>
																		&lt;br><br>
																		&lt;i class="sbeffectives-single-dam-sire"><br>
																		{$prod.dam|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}<br>
																		{if $prod.sire_dam|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}<br>
																			{$smarty.const._CMS_EFFECTIVES_BY}<br>
																			{$prod.sire_dam|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}<br>
																		{/if}<br>
																		&lt;/i><br>
																	{/if}<br>
																	<br>
																	{if $prod.performance|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}<br>
																		&lt;br><br>
																		{$prod.performance|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}<br>
																	{/if}<br>
																&lt;/p><br>
															{/foreach}<br>
														</b></li>
													</ul>
													</p>
													<p>MEDIAS (Boucle type) :
													<ul class="tpl_single">
														<li><b>
															{foreach from=$medias item=media}<br>
																{insert name=sbeffectives_medias type="`$media.type`" url="`$media.url`" title="`$media.title`"}<br>
															{/foreach}<br>
														</b></li>
													</ul>
													<p>CONSTANTES :
													<ul class="tpl_single">
														<li><b>{$smarty.const._CMS_EFFECTIVES_AND}</b>:  &</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_BY}</b>:  par</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_READ_ITEM}</b>:  Consulter sa page</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES}</b>:  Effectif</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_S}</b>:  Effectifs</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_ALL}</b>:  Tous les effectifs</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_NOITEM}</b>:  Pas d'effectifs disponible !</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_NOCATEGORIES}</b>:  Aucune catégorie disponible !</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_BORN}</b>:  né en</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_BORNDATE}</b>:  en</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_BREEDER}</b>:  Eleveur</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_OWNER}</b>:  Propriétaire</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_NOPROD}</b>:  Aucune production</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_NOMEDIAS}</b>:  Aucun médias</li>
													</ul>
													</p>
													<p>PIPEs possible :<br>
													<b>|unescape:"htmlall"</b>&nbsp;(Echappement HTML)<br>
													<b>|@sbDisplayLang:"`$smarty.session.lang`"</b>&nbsp;(Conversion Langue Session)
													</p>
												
											<?php }?>
										</p>
									</div>
									<div class="panel-footer">
										<a href="http://www.smarty.net/" target="_blank">Voir Doc smarty pour plus d'infos</a>
									</div>
								</div>
							</div>
							<!-- /.col-lg-4 -->
						<?php }?>
						<?php if ($_GET['a'] != 'tpl_list' && $_GET['a'] != 'tpl_single') {?>
						<div class="col-lg-4">
							<div class="panel panel-success">
								<div class="panel-heading">
									Templates Catégorie
								</div>
								<div class="panel-body">
									<p style="text-align: center;">
										<b>Modifier le template de l'intro (LIST)</b>
										<br>
										<button onclick="location.href='index.php?p=effectives&a=tpl_list&id=<?php echo $_GET['id'];?>
'" type="button" class="btn btn-success">
											Template LIST
										</button>
									</p>
									<br>
									<p style="text-align: center;">
										<b>Modifier le template de l'article (SINGLE)</b>
										<br>
										<button onclick="location.href='index.php?p=effectives&a=tpl_single&id=<?php echo $_GET['id'];?>
'" type="button" class="btn btn-success">
											Template SINGLE
										</button>
									</p>
								</div>
							</div>
						</div>
						<?php }?>

						<!-- /.col-lg-4 -->
					<?php }?>
				
				<?php }?>
				
            </div>
            <!-- /.row -->
	
		<!-- ------------------------------------------------------------ -->
		<!-- Page-Level Scripts - Use this space this write your own code -->
		<!-- ------------------------------------------------------------ -->
		<?php echo '<script'; ?>
 src="inc/plugins/ace/ace.js" type="text/javascript" charset="utf-8"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
>
		$(document).ready(function() {
			$('#dataTables-news').DataTable({
					order: [  [2, 'asc'], [0, 'desc'] ],
					responsive: true,
					"lengthMenu": [25, 50, 75, 100, 150]
			});
			$('#dataTables-categories').DataTable({
					order: [ 0, 'asc' ],
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

			var $editor = $('#code');
			if ($editor.length > 0) {
				var editor = ace.edit('code');
				editor.setTheme("ace/theme/textmate");
				editor.session.setMode("ace/mode/smarty");
				editor.getSession().setTabSize(4);
				editor.getSession().setUseWrapMode(true);
				editor.setShowPrintMargin(true);
				editor.setHighlightActiveLine(true);
				$editor.closest('form').submit(function() {
					var code = editor.getValue();
					$('input[name="code_hidden"]').val(code);
				});
			}
			
		});
		<?php echo '</script'; ?>
>
			
	<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:sb_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<?php }
}
