    {if $page != 'login'}</div>{/if}
    <!-- /#wrapper -->

    <!-- Bootstrap Core JavaScript -->
    <script src="assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="assets/bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="assets/dist/js/sb-admin-2.js"></script>

	{if $page != 'login'}
    <!-- DataTables JavaScript -->
    <script src="assets/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="assets/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
	
    <!-- jConfirm -->	
	<link href="inc/js/jconfirm/jConfirm-v2.min.css" media="screen" rel="stylesheet" type="text/css" />
	<script src="inc/js/jconfirm/jConfirm-v2.min.js" type="text/javascript"></script>

    <!-- jscroll -->	
	{*<script src="inc/js/jscroll/jquery.jscroll.min.js" type="text/javascript"></script>*}
	
    <!-- Facebox (lightbox) -->	
	<link href="inc/js/facebox/facebox.css" media="screen" rel="stylesheet" type="text/css" />
	<script src="inc/js/facebox/facebox.js" type="text/javascript"></script>
	
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
		// Enable tooltip
		$('.tooltip').tooltip({
			selector: "[data-toggle=tooltip]",
			container: "body"
		})
		// -----------------------------------------------------
		// Enable lightbox
		// USAGE : add --> rel="facebox" <-- to your links
		// Web : http://defunkt.io/facebox/
		$('a[rel*=facebox], img[rel*=facebox]').facebox({
			loadingImage : 'inc/js/facebox/loading.gif',
			closeImage   : 'inc/js/facebox/closelabel.png'
		});
		// -----------------------------------------------------
		// Enable jConfirm box
		// USAGE : add --> class="jConfirm"
		//         add --> href="url.php"
		//         to your button, tag a, ...
		// Web : http://flwebsites.biz/jConfirm/
		$('.jConfirm').jConfirm({ 
			message: "Sûr de vouloir supprimer ceci ?", 
			confirm: "OK", 
			cancel: "Ou pas...", 
			openNow: false,
			callback: function(elem){
				window.location.href = elem.attr('href'); 
			} 
		});
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
				$('.sbmenufixed').addClass('fixed panel-red').removeClass('panel-primary');
			} else {
				$('.sbmenufixed').removeClass('fixed panel-red').addClass('panel-primary');
			}
		});
		// -----------------------------------------------------
	});
	</script>
	{/if}

	{if $pagef == 'upload'}
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
				onComplete: function(id, name, response) {
					if (response.success) {
						//window.location.href="{$module_url}";
					}
				},
				onError: function(id, name, errorReason, xhrOrXdr) {
					alert(qq.format("Erreur sur le fichier n.{} - {}.  Raison: {}", id, name, errorReason));
				}
			}
        })
    });
	</script>
	{/if}
