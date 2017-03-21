<?php
/* Smarty version 3.1.29, created on 2016-11-29 15:32:53
  from "/Applications/MAMP/htdocs/sbmagic_new/theme/two/tpls/inc/index.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_583d91952f91a7_63523555',
  'file_dependency' => 
  array (
    '45ad61faf8580e4a5a50f452b97ca61853b8176d' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/theme/two/tpls/inc/index.tpl',
      1 => 1475564796,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_583d91952f91a7_63523555 ($_smarty_tpl) {
?>

		<?php if ($_smarty_tpl->tpl_vars['sb_page_headpage']->value) {?>
        <div class="banner-bg" id="top">
			<?php echo insert_sbDoShortcode(array('code' => "[CS id=".((string)$_smarty_tpl->tpl_vars['sb_page_headpage']->value)." name=sbslider]"),$_smarty_tpl);?>

        </div>
		<?php }?>

        <!-- MAIN CONTENT -->
        <div class="main-content">
            <div class="fluid-container">

                <div class="content-wrapper">
                
                    <!-- CONTENT -->
                    <div <?php echo insert_sbGetSectionClassId(array('class' => "page-section", 'evenid' => '', 'op' => ((string)$_GET['op']), 'page' => ((string)$_smarty_tpl->tpl_vars['page_id']->value), 'id' => ((string)$_GET['id']), 'ti' => ((string)$_smarty_tpl->tpl_vars['sb_title']->value)),$_smarty_tpl);?>
>
						<div class="row">
							<div class="col-md-12">
								
								<?php echo insert_sbGetContentCms(array('o1' => ((string)$_smarty_tpl->tpl_vars['page_view']->value), 'o2' => ((string)$_smarty_tpl->tpl_vars['module_view']->value)),$_smarty_tpl);?>

								
							</div>
						</div> <!-- #about -->
                    </div>
                
                    <hr>

                    <div class="row" id="footer">
                        <div class="col-md-12 text-center">
                            <p class="copyright-text">
								<div id="responsive-footer">
									 <?php echo insert_sbGetConfig(array('id' => "header"),$_smarty_tpl);?>

								</div>
								<?php echo insert_sbGetConfig(array('id' => "footer"),$_smarty_tpl);?>

							</p>
                        </div>
                    </div>

                </div>

            </div>
        </div><?php }
}
