<?php
/**
 * Module boutique : schéma de ses tables, pour l'installer seul (onglet
 * Modules de Configuration > Mise à jour). Une table déjà présente (données
 * gardées à la désinstallation) n'est pas recréée ; ses migrations
 * (migrations/<version>.php) la mettent ensuite à niveau.
 * Extrait de backdoor/install/sql_dump/sbuiadmin_create.sql.
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

return array(
	'sql' => <<<'SQL'
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_shop_accounting_export` (
  `id` int(11) NOT NULL,
  `plugin_id` varchar(50) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `config` text NOT NULL COMMENT 'JSON propre au plugin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_shop_accounting_log` (
  `id` int(11) NOT NULL,
  `oid` int(11) NOT NULL,
  `plugin_id` varchar(50) NOT NULL,
  `status` enum('succes','echec','en_attente') NOT NULL DEFAULT 'en_attente',
  `message` text NOT NULL,
  `sent_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_shop_category` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `photo` varchar(200) NOT NULL DEFAULT '',
  `sort` int(11) NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_shop_config` (
  `id` int(11) NOT NULL,
  `is_tva` tinyint(1) NOT NULL DEFAULT 1,
  `currency` varchar(10) NOT NULL DEFAULT 'EUR',
  `currency_text` varchar(10) NOT NULL DEFAULT '€',
  `currency_position` tinyint(1) NOT NULL DEFAULT 1,
  `n_decimals` tinyint(1) NOT NULL DEFAULT 2,
  `per_page` int(11) NOT NULL DEFAULT 12,
  `invoice_prefix` varchar(20) NOT NULL DEFAULT 'FAC',
  `invoice_format_plugin` varchar(50) NOT NULL DEFAULT 'facturx',
  `unique_code_root` varchar(20) NOT NULL DEFAULT '',
  `unique_code_key` varchar(100) NOT NULL DEFAULT '',
  `unique_code_pattern` varchar(50) NOT NULL DEFAULT 'alphanumeric',
  `unique_code_length` int(11) NOT NULL DEFAULT 16
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_shop_discount` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `plugin_id` varchar(50) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `expiration` int(11) DEFAULT NULL,
  `code_limit` int(11) NOT NULL DEFAULT 0,
  `code_usage` int(11) NOT NULL DEFAULT 0,
  `valeur` decimal(10,2) NOT NULL DEFAULT 0.00,
  `product_id` int(11) DEFAULT NULL,
  `uid` varchar(50) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_shop_email` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `subject_customer` varchar(255) NOT NULL DEFAULT '',
  `body_customer` text NOT NULL,
  `subject_admin` varchar(255) NOT NULL DEFAULT '',
  `body_admin` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_shop_order` (
  `id` int(11) NOT NULL,
  `client_uid` varchar(50) NOT NULL DEFAULT '',
  `client_name` varchar(255) NOT NULL DEFAULT '',
  `client_email` varchar(255) NOT NULL DEFAULT '',
  `date` int(11) NOT NULL,
  `status` tinyint(2) NOT NULL DEFAULT 0,
  `payment_plugin` varchar(50) NOT NULL DEFAULT '',
  `invoice_num` varchar(50) NOT NULL DEFAULT '',
  `invoice_generated` tinyint(1) NOT NULL DEFAULT 0,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `comment` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_shop_order_detail` (
  `id` int(11) NOT NULL,
  `oid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `reference` varchar(100) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `photo` varchar(200) NOT NULL DEFAULT '',
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `mode` enum('physique','dematerialise') DEFAULT NULL,
  `tva` text NOT NULL COMMENT 'JSON figé au moment de la commande',
  `transport_title` varchar(255) NOT NULL DEFAULT '',
  `transport_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `promo_code` varchar(50) NOT NULL DEFAULT '',
  `promo_type` varchar(50) NOT NULL DEFAULT '',
  `promo_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `promo_description` varchar(255) NOT NULL DEFAULT '',
  `client_message` text NOT NULL,
  `gift_package` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_shop_payment` (
  `id` int(11) NOT NULL,
  `plugin_id` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `production` tinyint(1) NOT NULL DEFAULT 0,
  `sort` int(11) NOT NULL DEFAULT 0,
  `config` text NOT NULL COMMENT 'JSON clés API'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_shop_product` (
  `id` int(11) NOT NULL,
  `catid` int(11) NOT NULL DEFAULT 0,
  `reference` varchar(100) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `description_short` text NOT NULL,
  `custom` text NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `priceht` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tva_assujetti` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Certaines formes de societe (franchise en base) en sont dispensees',
  `tva` text NOT NULL COMMENT 'JSON: tva1/tva2/tva3 {libelle,nom,taux,montant_ht,compte}',
  `photo` varchar(200) NOT NULL DEFAULT '',
  `photos` text NOT NULL COMMENT 'JSON galerie',
  `phys_visuals` text NOT NULL COMMENT 'JSON visuels version physique',
  `poids` decimal(10,3) NOT NULL DEFAULT 0.000,
  `allow_physical` tinyint(1) NOT NULL DEFAULT 1,
  `allow_dematerialise` tinyint(1) NOT NULL DEFAULT 0,
  `digital_delivery_plugin` varchar(50) NOT NULL DEFAULT '',
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `sort` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_shop_transport` (
  `id` int(11) NOT NULL,
  `plugin_id` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `sort` int(11) NOT NULL DEFAULT 0,
  `config` text NOT NULL COMMENT 'JSON propre au plugin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_shop_unique` (
  `id` int(11) NOT NULL,
  `order_detail_id` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `file_path` varchar(255) NOT NULL DEFAULT '',
  `status` enum('valide','utilise','expire') NOT NULL DEFAULT 'valide',
  `created_at` int(11) NOT NULL,
  `used_at` int(11) DEFAULT NULL,
  `expires_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `<DB_PREFIX>sb_shop_accounting_export`
  ADD PRIMARY KEY (`id`),
  ADD KEY `plugin_id` (`plugin_id`);

ALTER TABLE `<DB_PREFIX>sb_shop_accounting_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oid` (`oid`),
  ADD KEY `plugin_id` (`plugin_id`);

ALTER TABLE `<DB_PREFIX>sb_shop_category`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `<DB_PREFIX>sb_shop_config`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `<DB_PREFIX>sb_shop_discount`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

ALTER TABLE `<DB_PREFIX>sb_shop_email`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

ALTER TABLE `<DB_PREFIX>sb_shop_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_uid` (`client_uid`);

ALTER TABLE `<DB_PREFIX>sb_shop_order_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oid` (`oid`),
  ADD KEY `pid` (`pid`);

ALTER TABLE `<DB_PREFIX>sb_shop_payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `plugin_id` (`plugin_id`);

ALTER TABLE `<DB_PREFIX>sb_shop_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `catid` (`catid`),
  ADD KEY `reference` (`reference`);

ALTER TABLE `<DB_PREFIX>sb_shop_transport`
  ADD PRIMARY KEY (`id`),
  ADD KEY `plugin_id` (`plugin_id`);

ALTER TABLE `<DB_PREFIX>sb_shop_unique`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `order_detail_id` (`order_detail_id`),
  ADD KEY `pid` (`pid`);

ALTER TABLE `<DB_PREFIX>sb_shop_accounting_export`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `<DB_PREFIX>sb_shop_accounting_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `<DB_PREFIX>sb_shop_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `<DB_PREFIX>sb_shop_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `<DB_PREFIX>sb_shop_discount`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `<DB_PREFIX>sb_shop_email`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `<DB_PREFIX>sb_shop_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `<DB_PREFIX>sb_shop_order_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `<DB_PREFIX>sb_shop_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `<DB_PREFIX>sb_shop_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `<DB_PREFIX>sb_shop_transport`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `<DB_PREFIX>sb_shop_unique`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
SQL
);
