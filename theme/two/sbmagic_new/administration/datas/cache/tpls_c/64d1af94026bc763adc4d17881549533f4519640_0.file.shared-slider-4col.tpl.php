<?php
/* Smarty version 3.1.29, created on 2016-12-19 10:41:04
  from "/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/shared/shared-slider-4col.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_5857ab30627dd1_51856247',
  'file_dependency' => 
  array (
    '64d1af94026bc763adc4d17881549533f4519640' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/shared/shared-slider-4col.tpl',
      1 => 1474964126,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5857ab30627dd1_51856247 ($_smarty_tpl) {
?>


	<div class="col-lg-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				<span class="fa fa-square fa-fw"></span> <strong>Entête de la page</strong>
			</div>
			<!-- /.panel-heading -->
			<div class="panel-body">
				<div class="entete_view">
					<img src="<?php echo @constant('_AM_SITE_IMG_URL');?>
slider-headpage.jpg" alt="Choisissez un slider" title="Choisissez un slider" style="max-width: 100%;" />
					<select id="" onchange="javascript:$('#headpage').val(this.value)" class="form-control">
						<option value="">Sélectionnez un Slider</option>
						<?php
$_from = $_smarty_tpl->tpl_vars['theme_headpage']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_slide_0_saved_item = isset($_smarty_tpl->tpl_vars['slide']) ? $_smarty_tpl->tpl_vars['slide'] : false;
$_smarty_tpl->tpl_vars['slide'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['slide']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['slide']->value) {
$_smarty_tpl->tpl_vars['slide']->_loop = true;
$__foreach_slide_0_saved_local_item = $_smarty_tpl->tpl_vars['slide'];
?>
							<option value="<?php echo $_smarty_tpl->tpl_vars['slide']->value['id'];?>
"<?php if ($_smarty_tpl->tpl_vars['headpage']->value == $_smarty_tpl->tpl_vars['slide']->value['id']) {?> selected=""<?php }?>><?php echo $_smarty_tpl->tpl_vars['slide']->value['title'];?>
</option>
						<?php
$_smarty_tpl->tpl_vars['slide'] = $__foreach_slide_0_saved_local_item;
}
if ($__foreach_slide_0_saved_item) {
$_smarty_tpl->tpl_vars['slide'] = $__foreach_slide_0_saved_item;
}
?>
					</select>
				</div>
			</div>
			<!-- /.panel-body -->
		</div>
		<!-- /.panel -->
	</div>
	<!-- /.col-lg-4 --><?php }
}
