<?php
/* Smarty version 3.1.29, created on 2016-12-20 14:40:55
  from "/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/scripts.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_585934e7c1cc62_39415077',
  'file_dependency' => 
  array (
    '499ee4e4004693971025c874e9c8723564651595' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/scripts.tpl',
      1 => 1482146499,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_585934e7c1cc62_39415077 ($_smarty_tpl) {
?>
    </div>
    <!-- /#wrapper -->

    <!-- Bootstrap Core JavaScript -->
    <?php echo '<script'; ?>
 src="assets/bower_components/bootstrap/dist/js/bootstrap.min.js"><?php echo '</script'; ?>
>

    <!-- Metis Menu Plugin JavaScript -->
    <?php echo '<script'; ?>
 src="assets/bower_components/metisMenu/dist/metisMenu.min.js"><?php echo '</script'; ?>
>

    <!-- Custom Theme JavaScript -->
    <?php echo '<script'; ?>
 src="assets/dist/js/sb-admin-2.js"><?php echo '</script'; ?>
>

	<?php if ($_smarty_tpl->tpl_vars['page']->value != 'login') {?>
    <!-- DataTables JavaScript -->
    <?php echo '<script'; ?>
 src="assets/bower_components/datatables/media/js/jquery.dataTables.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="assets/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"><?php echo '</script'; ?>
>
	
    <!-- jConfirm -->	
	<link href="inc/js/jconfirm/jConfirm-v2.min.css" media="screen" rel="stylesheet" type="text/css" />
	<?php echo '<script'; ?>
 src="inc/js/jconfirm/jConfirm-v2.min.js" type="text/javascript"><?php echo '</script'; ?>
>

    <!-- jscroll -->	
	
	
    <!-- Facebox (lightbox) -->	
	<link href="inc/js/facebox/facebox.css" media="screen" rel="stylesheet" type="text/css" />
	<?php echo '<script'; ?>
 src="inc/js/facebox/facebox.js" type="text/javascript"><?php echo '</script'; ?>
>
	
	<!-- OutdatedBrowser -->
	<?php echo '<script'; ?>
 src="inc/plugins/outdatedbrowser/outdatedbrowser.min.js"><?php echo '</script'; ?>
>
    <!-- OutdatedBrowser plugin call -->
    <?php echo '<script'; ?>
>
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
    <?php echo '</script'; ?>
>
	
	<?php echo '<script'; ?>
>
	$(document).ready(function() {
		// Enable tooltip
		$('.tooltip').tooltip({
			selector: "[data-toggle=tooltip]",
			container: "body"
		})
		// Enable lightbox
		// USAGE : add --> rel="facebox" <-- to your links
		// Web : http://defunkt.io/facebox/
		$('a[rel*=facebox], img[rel*=facebox]').facebox({
			loadingImage : 'inc/js/facebox/loading.gif',
			closeImage   : 'inc/js/facebox/closelabel.png'
		});
		
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

		// Enable infinite scroll
		// USAGE : add --> class="infinite-scroll" to your div
		// Web : http://jscroll.com/
		//$('.infinite-scroll').jscroll();
	});
	<?php echo '</script'; ?>
>
	<?php }?>

	<?php if ($_smarty_tpl->tpl_vars['pagef']->value == 'upload') {?>
	
	
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
$__foreach_ext_allowed_0_saved_item = isset($_smarty_tpl->tpl_vars['ext_allowed']) ? $_smarty_tpl->tpl_vars['ext_allowed'] : false;
$__foreach_ext_allowed_0_total = $_smarty_tpl->smarty->ext->_foreach->count($_from);
$_smarty_tpl->tpl_vars['ext_allowed'] = new Smarty_Variable();
$__foreach_ext_allowed_0_iteration=0;
$_smarty_tpl->tpl_vars['ext_allowed']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['ext_allowed']->value) {
$_smarty_tpl->tpl_vars['ext_allowed']->_loop = true;
$__foreach_ext_allowed_0_iteration++;
$_smarty_tpl->tpl_vars['ext_allowed']->last = $__foreach_ext_allowed_0_iteration == $__foreach_ext_allowed_0_total;
$__foreach_ext_allowed_0_saved_local_item = $_smarty_tpl->tpl_vars['ext_allowed'];
?>"<?php echo $_smarty_tpl->tpl_vars['ext_allowed']->value;?>
"<?php if (!$_smarty_tpl->tpl_vars['ext_allowed']->last) {?>,<?php }
$_smarty_tpl->tpl_vars['ext_allowed'] = $__foreach_ext_allowed_0_saved_local_item;
}
if ($__foreach_ext_allowed_0_saved_item) {
$_smarty_tpl->tpl_vars['ext_allowed'] = $__foreach_ext_allowed_0_saved_item;
}
?>],
                itemLimit: <?php echo @constant('_AM_MEDIAS_ITEM_LIMIT');?>
,
                sizeLimit: <?php echo sbToByteSize(@constant('_AM_MEDIAS_SIZE_LIMIT'));?>
, // Bytes
            },
			callbacks: {
				onComplete: function(id, name, response) {
					if (response.success) {
						//window.location.href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
";
					}
				}
			}
        })
    });
	<?php echo '</script'; ?>
>
	<?php }
}
}
