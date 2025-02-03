<style>
	body { background-color: #FFF9E5 !important; }
</style>

{include file='header.tpl' page='false'}

	<!-- Fine Uploader Gallery template
	====================================================================== -->
	<script type="text/template" id="qq-template-gallery">
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
	</script>
	
	<!-- Fine Uploader DOM Element
	====================================================================== -->
	<div id="fine-uploader-gallery"></div>

	{foreach $medias_all as $sbmedia}
		
		{if isset($smarty.get.editor) && $smarty.get.editor == 'ck'}
			<p class="sbmedia" target="_parent" onclick="sbTransfertCkeditor('{$smarty.const._AM_MEDIAS_URL}/{$sbmedia|@sbFileRealname}')">
		{elseif isset($smarty.get.editor) && $smarty.get.editor == 'tiny'}
			<p class="sbmedia" target="_parent" onclick='sbTransfertTiny("{$sbmedia}","{$smarty.get.id}","{$smarty.const._AM_MEDIAS_URL}")'>
		{else}
			<p class="sbmedia" target="_parent" onclick='sbTransfert("{$sbmedia}","{$smarty.get.id}")'>
		{/if}

			{* Display thumbnail on the fly OR in the cache if exist and if it's an image ;-) *}
			{if $sbmedia|@sbGetIfIsImg}
				<img src="thumb.php?src={$sbmedia}&size=x84" title="{$sbmedia|@sbFilename|@sbRewriteTags}" />
			{else}
				{insert name=sbFileOtherImg assign=other f=$sbmedia}
				<span class="fa fa-{$other}-o fa-fw sbmedia-other"></span>
			{/if}
			
			{* Display File name truncate *}
			<span>{$sbmedia|@sbFilename|@sbRewriteTags|truncate:20:'..':true}</span>
			
			{* Display File real name truncate *}
			<span class="sbmedia-realname">{$sbmedia|@sbFileRealname|truncate:20:'..':true}</span>
			
			{* Display File size *}
			<span class="sbmedia-weight">{$sbmedia|@sbDisplayMediaSize}</span>
			
			{* Display File Width x Height *}
			<span class="sbmedia-info">
				{if !$sbmedia|@sbGetIfIsImg}
					{$sbmedia|@sbModifiedFileTime:"fr"}
				{else}
					{$sbmedia|@sbGetInfoImg:"width"} x {$sbmedia|@sbGetInfoImg:"height"}
				{/if}
			</span>
			
			{* Display File Type mime *}
			<span class="sbmedia-mime">{$sbmedia|@sbDisplayMediaMime}</span>
		
		</p>
		
	{/foreach}
	
	<link href="inc/js/upload/fine-uploader-gallery.min.css" media="screen" rel="stylesheet" type="text/css" />
	<script src="inc/js/upload/jquery.fine-uploader.min.js"></script>
	<script>
	$(document).ready(function() {
		// Create an instance of Fine Uploader and bind to the DOM/template
		$('#fine-uploader-gallery').fineUploader({
			template: 'qq-template-gallery',
			request: {
				endpoint: 'server/php/sbUploadServer.php{if isset($smarty.get.subdir)}?subdir={$smarty.get.subdir}{/if}'
			},
			scaling: {
				sendOriginal: false,
				includeExif: true,
				sizes: [
					{ name: "", maxSize: {$smarty.const._AM_MEDIAS_SCALING_SIXE_MAX} }
				]
			},
			thumbnails: {
				placeholders: {
					waitingPath: 'inc/js/upload/placeholders/waiting-generic.png',
					notAvailablePath: 'inc/js/upload/placeholders/not_available-generic.png'
				}
			},
			validation: {
				allowedExtensions: [{if isset($sbfiles_medias_exts_allowed)}{foreach $sbfiles_medias_exts_allowed as $ext_allowed}"{$ext_allowed}"{if !$ext_allowed@last},{/if}{/foreach}{else}"(jpg,jpeg,png,gif,pdf,xml,mp4)"{/if}],
				itemLimit: {$smarty.const._AM_MEDIAS_ITEM_LIMIT},
				sizeLimit: {$smarty.const._AM_MEDIAS_SIZE_LIMIT|@sbToByteSize}, // Bytes
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
	</script>

{include file='footer.tpl'}
