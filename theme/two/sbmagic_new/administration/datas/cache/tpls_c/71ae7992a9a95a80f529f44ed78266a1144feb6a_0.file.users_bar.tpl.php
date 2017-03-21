<?php
/* Smarty version 3.1.29, created on 2016-12-12 16:01:32
  from "/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/system/users_bar.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_584ebbcc407fc8_42612139',
  'file_dependency' => 
  array (
    '71ae7992a9a95a80f529f44ed78266a1144feb6a' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/system/users_bar.tpl',
      1 => 1475489541,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_584ebbcc407fc8_42612139 ($_smarty_tpl) {
?>


<div class="well well-sm">

		<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=users'">
			Tous les utilisateurs
		</button>
		&nbsp;
		<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=users&a=add'">
			Ajouter un utilisateur
		</button>
		&nbsp;
		<button class="btn btn-primary" type="button" onclick="location.href='https://fr.gravatar.com/'">
			Gravatar
		</button>
		&nbsp;
		<button class="btn btn-danger" type="button" onclick="location.href='index.php?p=settings'">
			Ajouter un administrateur
		</button>

</div><?php }
}
