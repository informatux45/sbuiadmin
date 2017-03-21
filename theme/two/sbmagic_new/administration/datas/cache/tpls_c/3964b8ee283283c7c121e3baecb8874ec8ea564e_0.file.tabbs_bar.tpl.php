<?php
/* Smarty version 3.1.29, created on 2016-12-12 11:57:13
  from "/Applications/MAMP/htdocs/sbmagic_new/administration/datas/modules/tpls/tabbs_bar.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_584e8289c4f351_27959838',
  'file_dependency' => 
  array (
    '3964b8ee283283c7c121e3baecb8874ec8ea564e' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/administration/datas/modules/tpls/tabbs_bar.tpl',
      1 => 1480432318,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_584e8289c4f351_27959838 ($_smarty_tpl) {
?>


<div class="well well-sm">
	

		<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=tabbs'">
			Tous les tabbs
		</button>
		&nbsp;	
		<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=tabbs&a=alltabs'">
			Tous les onglets
		</button>
		&nbsp;
		<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=tabbs&a=add'">
			Ajouter un tabbs
		</button>		
		&nbsp;
		<button class="btn btn-outline btn-primary" type="button" onclick="location.href='index.php?p=tabbs&a=tabadd'">
			Ajouter un onglet
		</button>
		
		<?php if ($_GET['a'] == 'edit') {?>
		&nbsp;
		<button class="btn btn-warning" type="button" onclick="location.href='index.php?p=tabbs&a=sort&tid=<?php echo $_GET['id'];?>
'">
			Trier les onglets
		</button>		
		<?php }?>

</div><?php }
}
