<?php
/* Smarty version 3.1.29, created on 2016-12-14 08:57:21
  from "/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/system/transfert.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_5850fb6188f073_61064300',
  'file_dependency' => 
  array (
    'ef19380650e2c72c6b9760c1d3e0e947491f16d9' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/system/transfert.tpl',
      1 => 1477045680,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
    'file:footer.tpl' => 1,
  ),
),false)) {
function content_5850fb6188f073_61064300 ($_smarty_tpl) {
if (!is_callable('smarty_modifier_truncate')) require_once '/Applications/MAMP/htdocs/sbmagic_new/administration/core/plugins/modifier.truncate.php';
$_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


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

	<?php
$_from = $_smarty_tpl->tpl_vars['medias_all']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_sbmedia_0_saved_item = isset($_smarty_tpl->tpl_vars['sbmedia']) ? $_smarty_tpl->tpl_vars['sbmedia'] : false;
$_smarty_tpl->tpl_vars['sbmedia'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['sbmedia']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['sbmedia']->value) {
$_smarty_tpl->tpl_vars['sbmedia']->_loop = true;
$__foreach_sbmedia_0_saved_local_item = $_smarty_tpl->tpl_vars['sbmedia'];
?>
		
		<?php if ($_GET['editor'] == 'ck') {?>
			<p class="sbmedia" target="_parent" onclick="sbTransfertCkeditor('<?php echo @constant('_AM_MEDIAS_URL');?>
/<?php echo sbFileRealname($_smarty_tpl->tpl_vars['sbmedia']->value);?>
')">
		<?php } elseif ($_GET['editor'] == 'tiny') {?>
			<p class="sbmedia" target="_parent" onclick='sbTransfertTiny("<?php echo $_smarty_tpl->tpl_vars['sbmedia']->value;?>
","<?php echo $_GET['id'];?>
","<?php echo @constant('_AM_MEDIAS_URL');?>
")'>
		<?php } else { ?>
			<p class="sbmedia" target="_parent" onclick='sbTransfert("<?php echo $_smarty_tpl->tpl_vars['sbmedia']->value;?>
","<?php echo $_GET['id'];?>
")'>
		<?php }?>

			
			<?php if (sbGetIfIsImg($_smarty_tpl->tpl_vars['sbmedia']->value)) {?>
				<img src="thumb.php?src=<?php echo $_smarty_tpl->tpl_vars['sbmedia']->value;?>
&size=x84&ignore=1&crop=0" title="<?php echo sbRewriteTags(sbFilename($_smarty_tpl->tpl_vars['sbmedia']->value));?>
" />
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
		
		</p>
		
	<?php
$_smarty_tpl->tpl_vars['sbmedia'] = $__foreach_sbmedia_0_saved_local_item;
}
if ($__foreach_sbmedia_0_saved_item) {
$_smarty_tpl->tpl_vars['sbmedia'] = $__foreach_sbmedia_0_saved_item;
}
?>
	
	<link href="inc/js/upload/fine-uploader-gallery.min.css" media="screen" rel="stylesheet" type="text/css" />
	<?php echo '<script'; ?>
 src="inc/js/upload/jquery.fine-uploader.min.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
>
	$(document).ready(function() {
		// Create an instance of Fine Uploader and bind to the DOM/template
		$('#fine-uploader-gallery').fineUploader({
			template: 'qq-template-gallery',
			request: {
				endpoint: 'server/php/sbUploadServer.php'
			},
			scaling: {
				sendOriginal: false,
				includeExif: true,
				sizes: [
					{ name: "", maxSize: <?php echo @constant('_AM_MEDIAS_SCALING_SIXE_MAX');?>
 }
				]
			},
			thumbnails: {
				placeholders: {
					waitingPath: 'inc/js/upload/placeholders/waiting-generic.png',
					notAvailablePath: 'inc/js/upload/placeholders/not_available-generic.png'
				}
			},
			validation: {
				allowedExtensions: [<?php
$_from = $_smarty_tpl->tpl_vars['sbfiles_medias_exts_allowed']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_ext_allowed_1_saved_item = isset($_smarty_tpl->tpl_vars['ext_allowed']) ? $_smarty_tpl->tpl_vars['ext_allowed'] : false;
$__foreach_ext_allowed_1_total = $_smarty_tpl->smarty->ext->_foreach->count($_from);
$_smarty_tpl->tpl_vars['ext_allowed'] = new Smarty_Variable();
$__foreach_ext_allowed_1_iteration=0;
$_smarty_tpl->tpl_vars['ext_allowed']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['ext_allowed']->value) {
$_smarty_tpl->tpl_vars['ext_allowed']->_loop = true;
$__foreach_ext_allowed_1_iteration++;
$_smarty_tpl->tpl_vars['ext_allowed']->last = $__foreach_ext_allowed_1_iteration == $__foreach_ext_allowed_1_total;
$__foreach_ext_allowed_1_saved_local_item = $_smarty_tpl->tpl_vars['ext_allowed'];
?>"<?php echo $_smarty_tpl->tpl_vars['ext_allowed']->value;?>
"<?php if (!$_smarty_tpl->tpl_vars['ext_allowed']->last) {?>,<?php }
$_smarty_tpl->tpl_vars['ext_allowed'] = $__foreach_ext_allowed_1_saved_local_item;
}
if ($__foreach_ext_allowed_1_saved_item) {
$_smarty_tpl->tpl_vars['ext_allowed'] = $__foreach_ext_allowed_1_saved_item;
}
?>],
				itemLimit: <?php echo @constant('_AM_MEDIAS_ITEM_LIMIT');?>
,
				sizeLimit: <?php echo sbToByteSize(@constant('_AM_MEDIAS_SIZE_LIMIT'));?>
, // Bytes
			},
			multiple: false,
			callbacks: {
				onComplete: function(id, name, response) {
					if (response.success) {
						window.location.reload();
					}
				}
			}
		})
		
	});
	<?php echo '</script'; ?>
>

<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
