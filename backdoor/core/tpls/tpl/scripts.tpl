    <!-- Adminator theme JS bundle -->
    <script defer src="assets/adminator/runtime.js"></script>
    <script defer src="assets/adminator/vendor-fullcalendar.js"></script>
    <script defer src="assets/adminator/vendor-chartjs.js"></script>
    <script defer src="assets/adminator/vendors.js"></script>
    <script defer src="assets/adminator/2026.js"></script>
    <script defer src="assets/adminator/datatable.js"></script>
    <script defer src="assets/adminator/confirm.js"></script>
    <script defer src="assets/adminator/lightbox.js"></script>
    <script defer src="assets/adminator/modal.js"></script>

	{if $page != 'login'}
    <!-- jscroll -->
	{*<script src="inc/js/jscroll/jquery.jscroll.min.js" type="text/javascript"></script>*}

	<!-- OutdatedBrowser -->
	<script src="inc/plugins/outdatedbrowser/outdatedbrowser.min.js"></script>
    <!-- OutdatedBrowser plugin call -->
    <script>
        //event listener form DOM ready
        function addLoadEvent(func) {
            var oldonload = window.onload;
            if (typeof window.onload != 'function') {
                window.onload = func;
            } else {
                window.onload = function() {
                    if (oldonload) {
                        oldonload();
                    }
                    func();
                }
            }
        }
        //call function after DOM ready
        addLoadEvent(function(){
            outdatedBrowser({
                bgColor: '#f25648',
                color: '#ffffff',
                lowerThan: 'transform',
                languagePath: 'inc/plugins/outdatedbrowser/lang/fr.html'
            })
        });
    </script>
	
	<script>
	$(document).ready(function() {
		// -----------------------------------------------------
		// Lightbox (rel="facebox") and delete confirmations are now handled globally by
		// assets/adminator/lightbox.js and assets/adminator/confirm.js
		// via the data-confirm="..." attribute — see scripts.tpl's <script> includes above.
		// -----------------------------------------------------
		// Enable infinite scroll
		// USAGE : add --> class="infinite-scroll" to your div
		// Web : http://jscroll.com/
		//$('.infinite-scroll').jscroll();		
		// -----------------------------------------------------
		// Fixed Div Submit Button (Forms)
		var num = 50; //number of pixels before modifying styles
		$(window).bind('scroll', function () {
			if ($(window).scrollTop() > num) {
				$('.sbmenufixed').addClass('fixed');
			} else {
				$('.sbmenufixed').removeClass('fixed');
			}
		});
		// -----------------------------------------------------
	});
	</script>
	{/if}

	{if isset($pagef) && $pagef == 'upload'}
	{*<link href="inc/js/upload/fine-uploader.min.css" media="screen" rel="stylesheet" type="text/css" />*}
	{*<link href="inc/js/upload/fine-uploader-new.min.css" media="screen" rel="stylesheet" type="text/css" />*}
	<link href="inc/js/upload/fine-uploader-gallery.min.css" media="screen" rel="stylesheet" type="text/css" />
	<script src="inc/js/upload/jquery.fine-uploader.min.js"></script>
	{*<script src="inc/js/upload/iframe.xss.response.js"></script>*}
	<script>
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
                allowedExtensions: [{foreach $sbfiles_medias_exts_allowed as $ext_allowed}"{$ext_allowed}"{if !$ext_allowed@last},{/if}{/foreach}],
                itemLimit: {$smarty.const._AM_MEDIAS_ITEM_LIMIT},
                sizeLimit: {$smarty.const._AM_MEDIAS_SIZE_LIMIT|@sbToByteSize}, // Bytes
            },
			callbacks: {
				onError: function(id, name, errorReason, xhrOrXdr) {
					alert(qq.format("Erreur sur le fichier n.{} - {}.  Raison: {}", id, name, errorReason));
				},
				onAllComplete: function(succeeded, failed) {
					// Reload once the whole batch is done (not per file) so newly
					// uploaded medias show up immediately in the list below.
					if (succeeded.length > 0) {
						window.location.href = "{$module_url}";
					}
				}
			}
        })
    });
	</script>
	{/if}
