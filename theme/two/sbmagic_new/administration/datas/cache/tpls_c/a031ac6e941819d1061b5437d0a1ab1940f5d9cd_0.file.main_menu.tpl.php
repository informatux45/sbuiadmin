<?php
/* Smarty version 3.1.29, created on 2016-12-20 14:40:55
  from "/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/main_menu.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_585934e7b17784_04324331',
  'file_dependency' => 
  array (
    'a031ac6e941819d1061b5437d0a1ab1940f5d9cd' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/main_menu.tpl',
      1 => 1476090679,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_585934e7b17784_04324331 ($_smarty_tpl) {
?>







<li id="index">
	<a <?php if ($_smarty_tpl->tpl_vars['active']->value == 'index') {?>class="active"<?php }?> href="index.php"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
</li>




<?php if ($_smarty_tpl->tpl_vars['sb_sandbox']->value == 1) {?>
<li id="sandbox">
	<a <?php if ($_smarty_tpl->tpl_vars['active']->value == 'sandbox') {?>class="active"<?php }?> href="index.php?p=sandbox"><i class="fa fa-ambulance fa-fw"></i> Sandbox</a>
</li>
<?php }?>

<?php echo $_smarty_tpl->tpl_vars['sb_main_menu_admin']->value;?>


<?php echo $_smarty_tpl->tpl_vars['sb_main_menu']->value;
}
}
