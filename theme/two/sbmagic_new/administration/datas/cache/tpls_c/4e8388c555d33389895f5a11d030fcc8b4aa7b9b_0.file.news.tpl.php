<?php
/* Smarty version 3.1.29, created on 2016-12-12 15:40:00
  from "/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/system/news.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_584eb6c05d6782_75877479',
  'file_dependency' => 
  array (
    '4e8388c555d33389895f5a11d030fcc8b4aa7b9b' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/system/news.tpl',
      1 => 1476257790,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:sb_header.tpl' => 1,
    'file:system/news_bar.tpl' => 1,
    'file:sb_footer.tpl' => 1,
  ),
),false)) {
function content_584eb6c05d6782_75877479 ($_smarty_tpl) {
if (!is_callable('smarty_function_cycle')) require_once '/Applications/MAMP/htdocs/sbmagic_new/administration/core/plugins/function.cycle.php';
?>

	


	
	<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:sb_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('module'=>$_smarty_tpl->tpl_vars['module_page']->value), 0, false);
?>

	
			
			
			
			
			
			<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:system/news_bar.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


            <div class="row">
				
				<?php if ($_smarty_tpl->tpl_vars['all']->value) {?>
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <span class="fa fa-rss fa-fw"></span> <strong><?php if ($_smarty_tpl->tpl_vars['all']->value) {?>Gestion de vos articles<?php } else {
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
$__foreach_news_1_saved_item = isset($_smarty_tpl->tpl_vars['news']) ? $_smarty_tpl->tpl_vars['news'] : false;
$_smarty_tpl->tpl_vars['news'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['news']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['news']->value) {
$_smarty_tpl->tpl_vars['news']->_loop = true;
$__foreach_news_1_saved_local_item = $_smarty_tpl->tpl_vars['news'];
?>
											<tr class="<?php echo smarty_function_cycle(array('values'=>"odd,even"),$_smarty_tpl);?>
 gradeX">
												<td><?php echo mb_convert_encoding($_smarty_tpl->tpl_vars['news']->value['date'], 'UTF-8', 'HTML-ENTITIES');?>
</td>
												<td><?php echo sbDisplayLang(mb_convert_encoding($_smarty_tpl->tpl_vars['news']->value['title'], 'UTF-8', 'HTML-ENTITIES'));?>
</td>
												<td><?php echo sbDisplayLang(mb_convert_encoding($_smarty_tpl->tpl_vars['news']->value['catname'], 'UTF-8', 'HTML-ENTITIES'));?>
</td>
												<td>[CS id=<?php echo $_smarty_tpl->tpl_vars['news']->value['id'];?>
 name=sbnews_item]</td>
												<td>
													<span class="glyphicon glyphicon-eye-open <?php if ($_smarty_tpl->tpl_vars['news']->value['active']) {?>green<?php } else { ?>red<?php }?>" title="Statut <?php if ($_smarty_tpl->tpl_vars['news']->value['active']) {?>visible<?php } else { ?>non visible<?php }?>"></span>
													&nbsp;
													<a class="glyphicon glyphicon-cog" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=edit&id=<?php echo $_smarty_tpl->tpl_vars['news']->value['id'];?>
" title="Modifier"></a>
													&nbsp;
													<a class="glyphicon glyphicon-remove red jConfirm" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=del&id=<?php echo $_smarty_tpl->tpl_vars['news']->value['id'];?>
" title="Supprimer"></a>
												</td>
											</tr>										
										<?php
$_smarty_tpl->tpl_vars['news'] = $__foreach_news_1_saved_local_item;
}
if ($__foreach_news_1_saved_item) {
$_smarty_tpl->tpl_vars['news'] = $__foreach_news_1_saved_item;
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
                            <span class="fa fa-rss fa-fw"></span> <strong><?php if ($_smarty_tpl->tpl_vars['allcategory']->value) {?>Gestion de vos catégories<?php } else {
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
												<td>[CS id=<?php echo $_smarty_tpl->tpl_vars['category']->value['id'];?>
 name=sbnews_latest]</td>
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

					<div class="col-lg-<?php if ($_GET['a'] == 'categoryedit' || $_GET['a'] == 'tpl_list' || $_GET['a'] == 'tpl_single') {?>8<?php } else { ?>12<?php }?>">
						<div class="panel panel-default">
							<div class="panel-heading">
								<span class="fa fa-rss fa-fw"></span> <strong><?php echo $_smarty_tpl->tpl_vars['legend_add_edit']->value;?>
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
												
													<p><i>TITRE de l'article</i><br>
													<b>{$news.title}</b>
													</p>
													<p><i>DESCRIPTION courte (intro)</i><br>
													<b>{$news.desc_short}</b>
													</p>
													<p><i>DESCRIPTION entière</i><br>
													<b>{$news.desc_full}</b>
													</p>
													<p><i>IMAGE</i><br>
													<b>{$news.image}</b>
													</p>
													<p><i>LIEN IMAGE</i><br>
													<b>{$smarty.const._AM_MEDIAS_URL}/{$news.image}</b>
													</p>
													<p><i>DATE (US)</i><br>
													<b>{$news.date}</b>
													</p>
													<p><i>DATE (FR)</i><br>
													<b>{$news.date|@sbConvertDate:"FR"}</b>
													</p>
													<p><i>LIEN ARTICLE</i><br>
													<b>{seo url="index.php?p=news&op=article&id={$news.id}" rewrite="news/article/{$news.id}<br>/{$news.title|unescape:"htmlall"<br>|@sbDisplayLang:"`$smarty.session.lang`"|<br>strip_tags|@sbRewriteString|@strtolower}"}</b>
													</p>
													<p><i>PIPEs possible</i><br>
													<b>|unescape:"htmlall"</b>&nbsp;(Echappement HTML)<br>
													<b>|@sbDisplayLang:"`$smarty.session.lang`"</b>&nbsp;(Convertion Langue Session)
													</p>
												
											<?php } else { ?>
												
													<p><i>TITRE de l'article</i><br>
													<b>{$item.title}</b>
													</p>
													<p><i>SOUS TITRE de l'article</i><br>
													<b>{$item.subtitle}</b>
													</p>
													<p><i>DESCRIPTION entière</i><br>
													<b>{$item.desc_full}</b>
													</p>
													<p><i>IMAGE</i><br>
													<b>{$item.image}</b>
													</p>
													<p><i>LIEN IMAGE</i><br>
													<b>{$smarty.const._AM_MEDIAS_URL}/{$item.image}</b>
													</p>
													<p><i>DATE (US)</i><br>
													<b>{$item.date}</b>
													</p>
													<p><i>DATE (FR)</i><br>
													<b>{$item.date|@sbConvertDate:"FR"}</b>
													</p>
													<p><i>PIPEs possible</i><br>
													<b>|unescape:"htmlall"</b>&nbsp;(Echappement HTML)<br>
													<b>|@sbDisplayLang:"`$smarty.session.lang`"</b>&nbsp;(Convertion Langue Session)
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
										<button onclick="location.href='index.php?p=news&a=tpl_list&id=<?php echo $_GET['id'];?>
'" type="button" class="btn btn-success">
											Template LIST
										</button>
									</p>
									<br>
									<p style="text-align: center;">
										<b>Modifier le template de l'article (SINGLE)</b>
										<br>
										<button onclick="location.href='index.php?p=news&a=tpl_single&id=<?php echo $_GET['id'];?>
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
				
			<div class="modal fade" id="sbnews_shortcodes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title" id="myModalLabel">Shorcodes</h4>
						</div>
						<div class="modal-body" style="overflow: hidden;">
							<img src="img/modules/sbnews_shortcodes_1.png" alt="" style="float: left; margin: 0 15px 10px 0; border: 1px solid gray; width: 220px;" />
							<div style="font-size: 12px;">
							<span style="font-weight: bold;">[CS name=sbnews_blocks_recent]</span><br>
							<span style="font-style: italic;">Affiche le bloc d'articles de toutes les catégories au nombre par défaut de 5</span><br>
							<span style="font-weight: bold;">[CS name=sbnews_blocks_recent count=3]</span><br>
							<span style="font-style: italic;">Affiche le bloc d'articles de toutes les catégories au nombre de 3</span><br>
							<span style="font-weight: bold;">[CS name=sbnews_blocks_recent count=3 truncate=40]</span><br>
							<span style="font-style: italic;">Affiche le bloc d'articles de toutes les catégories au nombre de 3 avec des titres tronquer à 40 caractères</span><br>
							<span style="font-weight: bold;">[CS name=sbnews_blocks_recent count=3 truncate=40 id=1]</span><br>
							<span style="font-style: italic;">Affiche le bloc d'articles de la catégorie à l'ID 1 au nombre de 3 avec des titres tronquer à 40 caractères</span>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>
	
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
