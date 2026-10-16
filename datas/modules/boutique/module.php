<?php
/**
 * Module boutique : description, version, tables et fichiers
 * (lu par backdoor/inc/sbuiadmin-modules.php)
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

if (!defined('SB_PATH') && !defined('SBUIADMIN_PATH')) die('Are you crazy!');

return array(
	'name'          => 'boutique',
	'title'         => 'Boutique',
	'icon'          => 'shopping-cart', // Font Awesome (menu, carte Modules)
	'description'   => "Boutique en ligne : produits, catégories, commandes, paiements, transport, remises et export comptable.",
	'version'       => '1.0.0',
	'requires_core' => '4.29',
	'author'        => 'INFORMATUX',
	'routes'        => array('shop', 'account'),
	'page_module'   => false,
	'shortcodes'    => array(),
	'tables'        => array(
		'sb_shop_product'           => array('title' => 'Produits', 'link' => 'index.php?p=boutique'),
		'sb_shop_category'          => array('title' => 'Catégories boutique', 'link' => 'index.php?p=boutique&a=category'),
		'sb_shop_config'            => array('title' => 'Paramètres boutique', 'link' => 'index.php?p=boutique&a=settings'),
		'sb_shop_order'             => array('title' => 'Commandes'),
		'sb_shop_order_detail'      => array('title' => 'Détail des commandes'),
		'sb_shop_payment'           => array('title' => 'Paiements'),
		'sb_shop_transport'         => array('title' => 'Transport'),
		'sb_shop_discount'          => array('title' => 'Remises'),
		'sb_shop_email'             => array('title' => 'E-mails boutique'),
		'sb_shop_unique'            => array('title' => 'Codes uniques'),
		'sb_shop_accounting_export' => array('title' => 'Exports comptables'),
		'sb_shop_accounting_log'    => array('title' => 'Journal comptable'),
	),
	'files'         => array(
		'backdoor/datas/modules/boutique.php',
		'backdoor/datas/modules/tpls/boutique.tpl',
		'backdoor/inc/class/sbuiadmin-cart.php',
		'backdoor/inc/class/sbuiadmin-account.php',
	),
	'rights'        => array(
		'submodules' => array('category' => 'Catégories', 'categorydel' => 'Catégories', 'categoryadd' => 'Catégories', 'categoryedit' => 'Catégories', 'settings' => 'Paramètres'),
		'actions'    => array('sort' => 'edit', 'category' => 'view', 'settings' => 'edit'),
	),
);

?>
