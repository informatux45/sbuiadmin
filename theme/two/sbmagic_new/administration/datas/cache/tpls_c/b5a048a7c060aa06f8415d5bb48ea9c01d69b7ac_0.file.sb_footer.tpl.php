<?php
/* Smarty version 3.1.29, created on 2016-12-20 14:40:55
  from "/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/sb_footer.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_585934e7bd5f37_46508723',
  'file_dependency' => 
  array (
    'b5a048a7c060aa06f8415d5bb48ea9c01d69b7ac' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/sb_footer.tpl',
      1 => 1477408746,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:scripts.tpl' => 1,
    'file:footer.tpl' => 1,
  ),
),false)) {
function content_585934e7bd5f37_46508723 ($_smarty_tpl) {
?>




        </div>
        <!-- /#page-wrapper -->

<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:scripts.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
