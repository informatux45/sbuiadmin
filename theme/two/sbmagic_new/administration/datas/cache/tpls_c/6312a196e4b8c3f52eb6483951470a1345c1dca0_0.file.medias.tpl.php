<?php
/* Smarty version 3.1.29, created on 2016-12-12 09:05:52
  from "/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/system/medias.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_584e5a602adcb7_31171712',
  'file_dependency' => 
  array (
    '6312a196e4b8c3f52eb6483951470a1345c1dca0' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/system/medias.tpl',
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
function content_584e5a602adcb7_31171712 ($_smarty_tpl) {
if (!is_callable('smarty_modifier_truncate')) require_once '/Applications/MAMP/htdocs/sbmagic_new/administration/core/plugins/modifier.truncate.php';
?>

	


	
	<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:sb_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('module'=>$_smarty_tpl->tpl_vars['module_page']->value), 0, false);
?>

	
			
			
			
			

			
			<div class="row">
				
                <div class="col-lg-6">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <span class="fa fa-upload"></span> <strong>Upload medias</strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">

							<!-- Fine Uploader Gallery template
							====================================================================== -->
							<?php echo '<script'; ?>
 type="text/template" id="qq-template-gallery">
								<div class="qq-uploader-selector qq-uploader qq-gallery" qq-drop-area-text="Drop files here">
									<div class="qq-total-progress-bar-container-selector qq-total-progress-bar-container">
										<div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-total-progress-bar-selector qq-progress-bar qq-total-progress-bar"></div>
									</div>
									<div class="qq-upload-drop-area-selector qq-upload-drop-area" qq-hide-dropzone>
										<span class="qq-upload-drop-area-text-selector"></span>
									</div>
									<div class="qq-upload-button-selector qq-upload-button">
										<div>Upload files</div>
									</div>
									<span class="qq-drop-processing-selector qq-drop-processing">
										<span>Processing dropped files...</span>
										<span class="qq-drop-processing-spinner-selector qq-drop-processing-spinner"></span>
									</span>
									<ul class="qq-upload-list-selector qq-upload-list" role="region" aria-live="polite" aria-relevant="additions removals">
										<li>
											<span role="status" class="qq-upload-status-text-selector qq-upload-status-text"></span>
											<div class="qq-progress-bar-container-selector qq-progress-bar-container">
												<div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-progress-bar-selector qq-progress-bar"></div>
											</div>
											<span class="qq-upload-spinner-selector qq-upload-spinner"></span>
											<div class="qq-thumbnail-wrapper">
												<img class="qq-thumbnail-selector" qq-max-size="120" qq-server-scale>
											</div>
											<button type="button" class="qq-upload-cancel-selector qq-upload-cancel">X</button>
											<button type="button" class="qq-upload-retry-selector qq-upload-retry">
												<span class="qq-btn qq-retry-icon" aria-label="Retry"></span>
												Retry
											</button>
					
											<div class="qq-file-info">
												<div class="qq-file-name">
													<span class="qq-upload-file-selector qq-upload-file"></span>
													<span class="qq-edit-filename-icon-selector qq-edit-filename-icon" aria-label="Edit filename"></span>
												</div>
												<input class="qq-edit-filename-selector qq-edit-filename" tabindex="0" type="text">
												<span class="qq-upload-size-selector qq-upload-size"></span>
												<button type="button" class="qq-btn qq-upload-delete-selector qq-upload-delete">
													<span class="qq-btn qq-delete-icon" aria-label="Delete"></span>
												</button>
												<button type="button" class="qq-btn qq-upload-pause-selector qq-upload-pause">
													<span class="qq-btn qq-pause-icon" aria-label="Pause"></span>
												</button>
												<button type="button" class="qq-btn qq-upload-continue-selector qq-upload-continue">
													<span class="qq-btn qq-continue-icon" aria-label="Continue"></span>
												</button>
											</div>
										</li>
									</ul>
					
									<dialog class="qq-alert-dialog-selector">
										<div class="qq-dialog-message-selector"></div>
										<div class="qq-dialog-buttons">
											<button type="button" class="qq-cancel-button-selector">Close</button>
										</div>
									</dialog>
					
									<dialog class="qq-confirm-dialog-selector">
										<div class="qq-dialog-message-selector"></div>
										<div class="qq-dialog-buttons">
											<button type="button" class="qq-cancel-button-selector">No</button>
											<button type="button" class="qq-ok-button-selector">Yes</button>
										</div>
									</dialog>
					
									<dialog class="qq-prompt-dialog-selector">
										<div class="qq-dialog-message-selector"></div>
										<input type="text">
										<div class="qq-dialog-buttons">
											<button type="button" class="qq-cancel-button-selector">Cancel</button>
											<button type="button" class="qq-ok-button-selector">Ok</button>
										</div>
									</dialog>
								</div>
							<?php echo '</script'; ?>
>
							
							<!-- Fine Uploader DOM Element
							====================================================================== -->
							<div id="fine-uploader-gallery"></div>

                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-6 -->
				
                <div class="col-lg-6">
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <span class="fa fa-info-circle"></span> <strong>Informations</strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
							<ul>
								<li>Upload des fichiers : <?php if ($_smarty_tpl->tpl_vars['media_ini_get_file_uploads']->value) {?><span class="green">ON</span><?php } else { ?><span class="red">OFF</span><?php }?></li>
								<li>Taille serveur maximum autorisée : <?php echo $_smarty_tpl->tpl_vars['media_ini_get_post_max_size']->value;?>
</li>
								<li>Taille maximum autorisée à l'upload : <span class="red"><?php echo @constant('_AM_MEDIAS_SIZE_LIMIT');?>
</span></li>
								<li>Types de fichier autorisés : <?php
$_from = $_smarty_tpl->tpl_vars['sbfiles_medias_exts_allowed']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_ext_allowed_0_saved_item = isset($_smarty_tpl->tpl_vars['ext_allowed']) ? $_smarty_tpl->tpl_vars['ext_allowed'] : false;
$_smarty_tpl->tpl_vars['ext_allowed'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['ext_allowed']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['ext_allowed']->value) {
$_smarty_tpl->tpl_vars['ext_allowed']->_loop = true;
$__foreach_ext_allowed_0_saved_local_item = $_smarty_tpl->tpl_vars['ext_allowed'];
?><span class="sbmedia_ext_allowed"><?php echo $_smarty_tpl->tpl_vars['ext_allowed']->value;?>
</span> <?php
$_smarty_tpl->tpl_vars['ext_allowed'] = $__foreach_ext_allowed_0_saved_local_item;
}
if (!$_smarty_tpl->tpl_vars['ext_allowed']->_loop) {
?>Aucune<?php
}
if ($__foreach_ext_allowed_0_saved_item) {
$_smarty_tpl->tpl_vars['ext_allowed'] = $__foreach_ext_allowed_0_saved_item;
}
?></li>
								<li>Répertoire de depôt : <?php
$_from = $_smarty_tpl->tpl_vars['sbfiles_medias_dirs_allowed']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_dir_allowed_1_saved_item = isset($_smarty_tpl->tpl_vars['dir_allowed']) ? $_smarty_tpl->tpl_vars['dir_allowed'] : false;
$_smarty_tpl->tpl_vars['dir_allowed'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['dir_allowed']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['dir_allowed']->value) {
$_smarty_tpl->tpl_vars['dir_allowed']->_loop = true;
$__foreach_dir_allowed_1_saved_local_item = $_smarty_tpl->tpl_vars['dir_allowed'];
?><span class="sbmedia_ext_allowed"><?php echo $_smarty_tpl->tpl_vars['dir_allowed']->value;?>
</span> <?php
$_smarty_tpl->tpl_vars['dir_allowed'] = $__foreach_dir_allowed_1_saved_local_item;
}
if (!$_smarty_tpl->tpl_vars['dir_allowed']->_loop) {
?>Aucun<?php
}
if ($__foreach_dir_allowed_1_saved_item) {
$_smarty_tpl->tpl_vars['dir_allowed'] = $__foreach_dir_allowed_1_saved_item;
}
?></li>
								<li>Nombre de fichiers uploadables simultanément : <?php echo @constant('_AM_MEDIAS_ITEM_LIMIT');?>
</li>
							</ul>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-6 -->

            </div>
            <!-- /.row -->
			

			
			<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span class="fa fa-desktop"></span> <strong>Medias</strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
							
							<?php
$_from = $_smarty_tpl->tpl_vars['medias_all']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_sbmedia_2_saved_item = isset($_smarty_tpl->tpl_vars['sbmedia']) ? $_smarty_tpl->tpl_vars['sbmedia'] : false;
$_smarty_tpl->tpl_vars['sbmedia'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['sbmedia']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['sbmedia']->value) {
$_smarty_tpl->tpl_vars['sbmedia']->_loop = true;
$__foreach_sbmedia_2_saved_local_item = $_smarty_tpl->tpl_vars['sbmedia'];
?>
								<?php if ($_smarty_tpl->tpl_vars['sbtranfer_media']->value == true) {?>
									<p class="sbmedia" target="_parent" onclick='transfert("<?php echo $_smarty_tpl->tpl_vars['sbmedia']->value;?>
","<?php echo $_smarty_tpl->tpl_vars['sbid']->value;?>
")'>
								<?php } else { ?>
									<p class="sbmedia">
								<?php }?>
								
								
								<?php if (sbGetIfIsImg($_smarty_tpl->tpl_vars['sbmedia']->value)) {?>
									<a class="sbmedia-display" href="<?php echo $_smarty_tpl->tpl_vars['sbmedia']->value;?>
" rel="facebox">
										<img src="thumb.php?src=<?php echo $_smarty_tpl->tpl_vars['sbmedia']->value;?>
&size=x84&ignore=1&crop=0" title="<?php echo sbRewriteTags(sbFilename($_smarty_tpl->tpl_vars['sbmedia']->value));?>
" />
									</a>
								<?php } else { ?>
									<?php $_smarty_tpl->assign('other' , insert_sbFileOtherImg (array('f' => $_smarty_tpl->tpl_vars['sbmedia']->value),$_smarty_tpl), true);?>
									<span class="fa fa-<?php echo $_smarty_tpl->tpl_vars['other']->value;?>
-o fa-fw sbmedia-other"></span>
								<?php }?>
								
								
								<span><?php echo smarty_modifier_truncate(sbRewriteTags(sbFilename($_smarty_tpl->tpl_vars['sbmedia']->value)),20,'..',true);?>
</span>
								
								
								<span class="sbmedia-realname"><?php echo smarty_modifier_truncate(sbFileRealname($_smarty_tpl->tpl_vars['sbmedia']->value),20,'..',true);?>
</span>
								
								
								<span class="sbmedia-weight"><?php echo sbDisplayMediaSize($_smarty_tpl->tpl_vars['sbmedia']->value);?>
</span>
								
								
								<span class="sbmedia-info">
									<?php if (!sbGetIfIsImg($_smarty_tpl->tpl_vars['sbmedia']->value)) {?>
										<?php echo sbModifiedFileTime($_smarty_tpl->tpl_vars['sbmedia']->value,"fr");?>

									<?php } else { ?>
										<?php echo sbGetInfoImg($_smarty_tpl->tpl_vars['sbmedia']->value,"width");?>
 x <?php echo sbGetInfoImg($_smarty_tpl->tpl_vars['sbmedia']->value,"height");?>

									<?php }?>
								</span>
								
								
								<span class="sbmedia-mime"><?php echo sbDisplayMediaMime($_smarty_tpl->tpl_vars['sbmedia']->value);?>
</span>
								
								
								<a class="btn btn-info btn-circle sbmedia-delete jConfirm" type="button" href="<?php echo $_smarty_tpl->tpl_vars['module_short_url']->value;?>
&del=<?php echo $_smarty_tpl->tpl_vars['sbmedia']->value;?>
">
									<i class="fa fa-times"></i>
								</a>
								
								</p>
							<?php
$_smarty_tpl->tpl_vars['sbmedia'] = $__foreach_sbmedia_2_saved_local_item;
}
if (!$_smarty_tpl->tpl_vars['sbmedia']->_loop) {
?>
							
							<?php
}
if ($__foreach_sbmedia_2_saved_item) {
$_smarty_tpl->tpl_vars['sbmedia'] = $__foreach_sbmedia_2_saved_item;
}
?>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
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

	<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:sb_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('pagef'=>'upload'), 0, false);
}
}
