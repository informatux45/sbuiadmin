{* -------------- *}
{* --- SYSTEM --- *}	
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page page='false'}
	{* ---------------- End Headers --------------- *}
			
			{* ------------------------------------------------ *}
			{*       Write your own code after this line        *}
			{* ------------------------------------------------ *}

			{* Notes 6 col *}
			<div class="grid">

                <section class="col-6 card">
                    <div class="card-head">
						<div class="card-title-wrap">
							<h2 class="card-title">Upload medias</h2>
						</div>
                    </div>

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

                </section>

                <section class="col-6 card">
                    <div class="card-head">
						<div class="card-title-wrap">
							<h2 class="card-title">Informations</h2>
						</div>
                    </div>
							<ul>
								<li>Upload des fichiers : {if $media_ini_get_file_uploads}<span class="green">ON</span>{else}<span class="red">OFF</span>{/if}</li>
								<li>Taille post maximum autorisée (post_max_size) : {$media_ini_get_post_max_size}</li>
								<li>Taille upload maximum autorisée (upload_max_filesize) : {$media_ini_get_upload_max_filesize}</li>
								<li>Taille maximum autorisée à l'upload (<a href="{$smarty.const._AM_SITE_URL}index.php?p=settings">Configuration)</a> : <span class="red">{$smarty.const._AM_MEDIAS_SIZE_LIMIT}</span>
									<br><small style="color:var(--warning)">Si des fichiers volumineux (ou autres) ne passent pas à l'upload, vérifiez directement la configuration serveur (upload_max_filesize / post_max_size ci-dessus).</small>
								</li>
								<li>Types de fichier autorisés : {foreach $sbfiles_medias_exts_allowed as $ext_allowed}<span class="sbmedia_ext_allowed">{$ext_allowed}</span> {foreachelse}Aucune{/foreach}</li>
								<li>Répertoire de depôt : {foreach $sbfiles_medias_dirs_allowed as $dir_allowed}<span class="sbmedia_ext_allowed">{$dir_allowed}</span> {foreachelse}Aucun{/foreach}</li>
								<li>Nombre de fichiers uploadables simultanément : {$smarty.const._AM_MEDIAS_ITEM_LIMIT}</li>
							</ul>
                </section>

            </div>
            <!-- /.grid -->


			{* Notes full width *}
			<div class="grid" style="margin-top:20px">
                <section class="col-12 card">
                    <div class="card-head">
						<div class="card-title-wrap">
							<span class="eyebrow">Médias</span>
						</div>
                    </div>
							{* HTML Text Formatted *}
							<div style="overflow:hidden;padding-top:10px">
							{foreach $medias_all as $sbmedia}
								{if $sbtranfer_media == true}
									<p class="sbmedia" target="_parent" onclick='transfert("{$sbmedia}","{$sbid}")'>
								{else}
									<p class="sbmedia">
								{/if}
								
								{* Display thumbnail on the fly OR in the cache if exist and if it's an image ;-) *}
								{if $sbmedia|@sbGetIfIsImg}
									<a class="sbmedia-display" href="{$sbmedia}" rel="facebox">
										<img src="thumb.php?src={$sbmedia}&size=x84" title="{$sbmedia|@sbFilename|@sbRewriteTags}" />
									</a>
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
								
								{* Display Delete Button *}
								<a class="sbmedia-delete" data-confirm="Sûr de vouloir supprimer ceci ?" type="button" href="{$module_short_url}&del={$sbmedia}{if isset($smarty.get.page)}&page={$smarty.get.page}{/if}">
									<i class="fa fa-times"></i>
								</a>
								
								</p>
							{foreachelse}

							{/foreach}
							</div>
							{if $sbpagination}
							<div style="margin-top:10px">{$sbpagination}</div>
							{/if}
                </section>
            </div>
            <!-- /.grid -->

		{*include file='scripts.tpl' pagef='upload'*}

		<!-- ------------------------------------------------------------ -->
		<!-- Page-Level Scripts - Use this space this write your own code -->
		<!-- ------------------------------------------------------------ -->
		<script>
		$(document).ready(function() {
			// Your own code

		});
		</script>

	{include file='sb_footer.tpl' page='false' pagef='upload'}
