<?php
/**
 * Module store (ex-« boutique », renommé en 4.40) : description, version,
 * tables et fichiers (lu par backdoor/inc/sbuiadmin-modules.php)
 *
 * Nouveau nom, nouvelles tables (sb_store_*), plus aucune route ni classe :
 * un site qui a sa propre boutique (dossier « boutique », tables sb_shop_*,
 * routes shop/account) n'est plus jamais concerné. 'replaces' : à
 * l'installation, une « boutique » OFFICIELLE installée est reprise (tables
 * renommées, droits, registre, anciens fichiers retirés), voir
 * sbModTakeover() dans backdoor/inc/sbuiadmin-update-modules.php.
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */
if (!defined('SB_PATH') && !defined('SBUIADMIN_PATH')) die('Are you crazy!');
return array(
	'name'          => 'store',
	'title'         => 'E-commerce',
	'icon'          => 'star', // Font Awesome (menu, carte Modules)
	'description'   => "Boutique en ligne : produits, catégories, commandes, paiements, transport, remises et export comptable.",
	'version'       => '2.0.1',
	'requires_core' => '4.40',
	'author'        => 'INFORMATUX',
	'routes'        => array(), // pas de front
	'page_module'   => false,
	'shortcodes'    => array(),
	'tables'        => array(
		'sb_store_product'           => array('title' => 'Produits', 'link' => 'index.php?p=store'),
		'sb_store_category'          => array('title' => 'Catégories boutique', 'link' => 'index.php?p=store&a=category'),
		'sb_store_config'            => array('title' => 'Paramètres boutique', 'link' => 'index.php?p=store&a=settings'),
		'sb_store_order'             => array('title' => 'Commandes'),
		'sb_store_order_detail'      => array('title' => 'Détail des commandes'),
		'sb_store_payment'           => array('title' => 'Paiements'),
		'sb_store_transport'         => array('title' => 'Transport'),
		'sb_store_discount'          => array('title' => 'Remises'),
		'sb_store_email'             => array('title' => 'E-mails boutique'),
		'sb_store_unique'            => array('title' => 'Codes uniques'),
		'sb_store_accounting_export' => array('title' => 'Exports comptables'),
		'sb_store_accounting_log'    => array('title' => 'Journal comptable'),
	),
	'files'         => array(
		'backdoor/datas/modules/store.php',
		'backdoor/datas/modules/tpls/store.tpl',
	),
	'replaces'      => array(
		'module' => 'boutique',
		'author' => 'INFORMATUX',
		'tables' => array(
			'sb_shop_product'           => 'sb_store_product',
			'sb_shop_category'          => 'sb_store_category',
			'sb_shop_config'            => 'sb_store_config',
			'sb_shop_order'             => 'sb_store_order',
			'sb_shop_order_detail'      => 'sb_store_order_detail',
			'sb_shop_payment'           => 'sb_store_payment',
			'sb_shop_transport'         => 'sb_store_transport',
			'sb_shop_discount'          => 'sb_store_discount',
			'sb_shop_email'             => 'sb_store_email',
			'sb_shop_unique'            => 'sb_store_unique',
			'sb_shop_accounting_export' => 'sb_store_accounting_export',
			'sb_shop_accounting_log'    => 'sb_store_accounting_log',
		),
		'files'  => array(
			'backdoor/datas/modules/boutique.php',
			'backdoor/datas/modules/tpls/boutique.tpl',
			'backdoor/inc/class/sbuiadmin-cart.php',
			'backdoor/inc/class/sbuiadmin-account.php',
		),
	),
	'rights'        => array(
		'submodules' => array('category' => 'Catégories', 'categorydel' => 'Catégories', 'categoryadd' => 'Catégories', 'categoryedit' => 'Catégories', 'settings' => 'Paramètres'),
		'actions'    => array('sort' => 'edit', 'category' => 'view', 'settings' => 'edit'),
	),
);

?>
