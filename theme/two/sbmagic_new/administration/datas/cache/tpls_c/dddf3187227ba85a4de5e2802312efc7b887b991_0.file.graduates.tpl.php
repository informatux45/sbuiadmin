<?php
/* Smarty version 3.1.29, created on 2016-11-28 17:06:44
  from "/Applications/MAMP/htdocs/sbmagic_new/administration/datas/modules/tpls/graduates.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_583c5614b170e4_41670336',
  'file_dependency' => 
  array (
    'dddf3187227ba85a4de5e2802312efc7b887b991' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/administration/datas/modules/tpls/graduates.tpl',
      1 => 1477385904,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:sb_header.tpl' => 1,
    'file:graduates_bar.tpl' => 1,
    'file:shared/shared-slider-4col.tpl' => 1,
    'file:sb_footer.tpl' => 1,
  ),
),false)) {
function content_583c5614b170e4_41670336 ($_smarty_tpl) {
if (!is_callable('smarty_function_cycle')) require_once '/Applications/MAMP/htdocs/sbmagic_new/administration/core/plugins/function.cycle.php';
?>

	


	
	<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:sb_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('module'=>$_smarty_tpl->tpl_vars['module_page']->value), 0, false);
?>

	
			
			
			
			
			
			<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:graduates_bar.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


            <div class="row">
				
				<?php if ($_smarty_tpl->tpl_vars['all']->value) {?>
					<div class="col-lg-12">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<span class="fa fa-certificate fa-fw"></span> <strong><?php if ($_smarty_tpl->tpl_vars['all']->value) {?>Gestion de vos meilleurs élèves<?php } else {
echo $_smarty_tpl->tpl_vars['legend_add_edit']->value;
}?></strong>
							</div>
							<!-- /.panel-heading -->
							<div class="panel-body">
								<div class="dataTable_wrapper">
									<table class="table table-striped table-bordered table-hover" id="dataTables-graduates">
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
$__foreach_graduates_1_saved_item = isset($_smarty_tpl->tpl_vars['graduates']) ? $_smarty_tpl->tpl_vars['graduates'] : false;
$_smarty_tpl->tpl_vars['graduates'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['graduates']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['graduates']->value) {
$_smarty_tpl->tpl_vars['graduates']->_loop = true;
$__foreach_graduates_1_saved_local_item = $_smarty_tpl->tpl_vars['graduates'];
?>
												<tr class="<?php echo smarty_function_cycle(array('values'=>"odd,even"),$_smarty_tpl);?>
 gradeX">
													<td><?php echo sbDisplayLang(mb_convert_encoding($_smarty_tpl->tpl_vars['graduates']->value['name'], 'UTF-8', 'HTML-ENTITIES'));?>
</td>
													<td><?php echo sbDisplayLang(mb_convert_encoding($_smarty_tpl->tpl_vars['graduates']->value['catname'], 'UTF-8', 'HTML-ENTITIES'));?>
</td>
													<td>[CS id=<?php echo $_smarty_tpl->tpl_vars['graduates']->value['id'];?>
 name=sbgraduates_item]</td>
													<td>
														<span class="glyphicon glyphicon-eye-open <?php if ($_smarty_tpl->tpl_vars['graduates']->value['active']) {?>green<?php } else { ?>red<?php }?>" title="Statut <?php if ($_smarty_tpl->tpl_vars['graduates']->value['active']) {?>visible<?php } else { ?>non visible<?php }?>"></span>
														&nbsp;
														<a class="glyphicon glyphicon-cog" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=edit&id=<?php echo $_smarty_tpl->tpl_vars['graduates']->value['id'];?>
" title="Modifier"></a>
														&nbsp;
														<a class="glyphicon glyphicon-remove red jConfirm" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=del&id=<?php echo $_smarty_tpl->tpl_vars['graduates']->value['id'];?>
" title="Supprimer"></a>
													</td>
												</tr>										
											<?php
$_smarty_tpl->tpl_vars['graduates'] = $__foreach_graduates_1_saved_local_item;
}
if ($__foreach_graduates_1_saved_item) {
$_smarty_tpl->tpl_vars['graduates'] = $__foreach_graduates_1_saved_item;
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
                            <span class="fa fa-certificate fa-fw"></span> <strong><?php if ($_smarty_tpl->tpl_vars['allcategory']->value) {?>Gestion de vos catégories<?php } else {
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

				<?php if ((!$_smarty_tpl->tpl_vars['all']->value || !$_smarty_tpl->tpl_vars['allcategory']->value) && ($_GET['a'] && $_GET['a'] != 'category')) {?>
					<div class="col-lg-<?php if ($_GET['a'] == 'categoryedit' || $_GET['a'] == 'tpl_list' || $_GET['a'] == 'tpl_single' || $_smarty_tpl->tpl_vars['show_headpage']->value) {?>8<?php } else { ?>12<?php }?>">
						<div class="panel panel-default">
							<div class="panel-heading">
								<span class="fa fa-certificate fa-fw"></span> <strong><?php echo $_smarty_tpl->tpl_vars['legend_add_edit']->value;?>
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
					
					<?php if ($_smarty_tpl->tpl_vars['graduates_help']->value) {?>
						<div class="col-lg-4">
							<div class="panel panel-default">
								<div class="panel-heading">
									<span class="fa fa-info-circle fa-fw"></span> <strong>Aide</strong>
								</div>
								<!-- /.panel-heading -->
								<div class="panel-body">
									<?php echo $_smarty_tpl->tpl_vars['graduates_help']->value;?>

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
											<?php if ($_GET['a'] == 'tpl_list' || $_GET['a'] == 'tpl_single') {?>
												
													<style>
														ul.tpl_list li {font-size: 12px; list-style: none; margin-left: -40px;}
													</style>
													<p>VARIABLES :
													<ul class="tpl_list">
														<li>Id: <b>{$graduates.id}</b></li>
														<li>Nom: <b>{$graduates.name}</b></li>
														<li>Image: <b>{$smarty.const._AM_MEDIAS_URL}/{$graduates.photo}</b></li>
														<li>Père / Mère: <b>{$graduates.sire_dam_info}</b></li>
														<li>Eleveur: <b>{$graduates.breeder}</b></li>
														<li>Owner: <b>{$graduates.owner}</b></li>
														<li>Perf 1: <b>{$graduates.perf_1}</b></li>
														<li>Vidéo 1: <b>{$graduates.video_1}</b></li>
														<li>...</li>
														<li>Perf 10: <b>{$graduates.perf_10}</b></li>
														<li>Vidéo 10: <b>{$graduates.video_10}</b></li>
														<li>Lien article:<br>
														<b>{seo url="index.php?p=graduates&op=article&id={$graduates.id}" rewrite="graduates/article/{$graduates.id}/{$graduates.name|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"|strip_tags|@sbRewriteString|@strtolower}"}</b></li>
														<li></li>
													</ul>
													</p>
													<p>CONSTANTES :
													<ul class="tpl_list">
														<li><b>{$smarty.const._CMS_GRADUATES}</b>:  Meilleur élève</li>
														<li><b>{$smarty.const._CMS_GRADUATES_S}</b>:  Meilleurs élèves</li>
														<li><b>{$smarty.const._CMS_GRADUATES_ALL}</b>:  Tous les meilleurs élèves</li>
														<li><b>{$smarty.const._CMS_GRADUATES_NOITEM}</b>:  Pas de meilleur élève disponible !</li>
														<li><b>{$smarty.const._CMS_GRADUATES_NOCATEGORIES}</b>:  Aucune catégorie disponible !</li>
														<li><b>{$smarty.const._CMS_GRADUATES_READ_ITEM}</b>:  Consulter sa page</li>
														<li><b>{$smarty.const._CMS_GRADUATES_BREEDER}</b>:  Eleveur</li>
														<li><b>{$smarty.const._CMS_GRADUATES_OWNER}</b>:  Propriétaire</li>
														<li><b>{$smarty.const._CMS_GRADUATES_NOMEDIAS}</b>:  Aucun médias</li>
														<li><b>{$smarty.const._CMS_GRADUATES_NOGRADUATES}</b>:  Pas de graduates pour cette catégorie !</li>
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
										<button onclick="location.href='index.php?p=graduates&a=tpl_list&id=<?php echo $_GET['id'];?>
'" type="button" class="btn btn-success">
											Template LIST
										</button>
									</p>
									<br>
									<p style="text-align: center;">
										<b>Modifier le template de l'article (SINGLE)</b>
										<br>
										<button onclick="location.href='index.php?p=graduates&a=tpl_single&id=<?php echo $_GET['id'];?>
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
			$('#dataTables-graduates').DataTable({
					order: [ 0, 'desc' ],
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
