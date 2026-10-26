<?php
/**
 * Migration 4.43 : rattrapage du schéma des bases anciennes
 *
 * Des tables et colonnes du core n'ont longtemps existé que dans
 * l'installeur (sb_users_rights, sb_users_remember_tokens, sb_messages,
 * colonnes de profil de sb_users, de sb_sandbox, sb_news.viewed...) : une
 * base d'avant les migrations (4.13 et antérieures) ne les recevait jamais.
 * Cette migration amène toute base au schéma de l'installeur 4.43 :
 *  - table du core absente : créée (colonnes, clés) ;
 *  - table de module absente : créée si le module est inscrit comme
 *    installé (sb_modules : la migration 4.29 l'inscrit à sa version
 *    actuelle, ses propres migrations ne passaient donc jamais, ex.
 *    sb_contact_messages) ; sinon laissée (son installation la crée) ;
 *  - colonne absente : ajoutée à sa place ;
 *  - varchar/char trop court (ex. sb_users.password) : élargi, jamais
 *    rétréci, et seulement si aucune valeur NULL ne peut le faire échouer.
 * Aucune donnée n'est modifiée ni supprimée, aucune clé unique n'est
 * ajoutée à une table existante. Relancée : sans effet. Retour : rien à
 * défaire (ce qui est ajouté reste compatible avec les versions
 * précédentes). Schéma ci-dessous produit depuis
 * backdoor/install/sql_dump/sbuiadmin_create_7.1_and_more.sql.
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

$sb_schema_443 = array (
  'sb_attempts' => 
  array (
    'module' => '',
    'definition' => '`ip` varchar(15) NOT NULL, `count` int NOT NULL, `expiredate` datetime NOT NULL',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT=\'Table Attempts\'',
    'columns' => 
    array (
      'ip' => 'varchar(15) NOT NULL',
      'count' => 'int NOT NULL',
      'expiredate' => 'datetime NOT NULL',
    ),
  ),
  'sb_blocked_history' => 
  array (
    'module' => '',
    'definition' => '`id` bigint unsigned NOT NULL AUTO_INCREMENT, `count` tinyint unsigned NOT NULL DEFAULT \'1\', `blockedtime` bigint NOT NULL, `ip` varchar(20) NOT NULL, `reason` varchar(255) NOT NULL, `infos` text, PRIMARY KEY (`id`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8',
    'columns' => 
    array (
      'id' => 'bigint unsigned NOT NULL AUTO_INCREMENT',
      'count' => 'tinyint unsigned NOT NULL DEFAULT \'1\'',
      'blockedtime' => 'bigint NOT NULL',
      'ip' => 'varchar(20) NOT NULL',
      'reason' => 'varchar(255) NOT NULL',
      'infos' => 'text',
    ),
  ),
  'sb_blocked_ip' => 
  array (
    'module' => '',
    'definition' => '`id` bigint unsigned NOT NULL AUTO_INCREMENT, `ip` varchar(20) NOT NULL, `count` tinyint unsigned NOT NULL DEFAULT \'1\', `blockedtime` bigint NOT NULL, `expirationtime` bigint unsigned NOT NULL DEFAULT \'0\', `reason` varchar(255) NOT NULL, `infos` text, PRIMARY KEY (`id`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8',
    'columns' => 
    array (
      'id' => 'bigint unsigned NOT NULL AUTO_INCREMENT',
      'ip' => 'varchar(20) NOT NULL',
      'count' => 'tinyint unsigned NOT NULL DEFAULT \'1\'',
      'blockedtime' => 'bigint NOT NULL',
      'expirationtime' => 'bigint unsigned NOT NULL DEFAULT \'0\'',
      'reason' => 'varchar(255) NOT NULL',
      'infos' => 'text',
    ),
  ),
  'sb_blocs' => 
  array (
    'module' => '',
    'definition' => '`id` int NOT NULL AUTO_INCREMENT, `pages_id` varchar(255) NOT NULL COMMENT \'Pages IDs (separate by | )\', `modules_id` varchar(255) NOT NULL COMMENT \'Module dirnames (separate by | )\', `name` varchar(100) NOT NULL COMMENT \'Nom du bloc\', `title` text NOT NULL COMMENT \'titre du bloc (cote client)\', `content` text NOT NULL COMMENT \'Contenu du bloc\', `position` varchar(100) NOT NULL, `various_view` varchar(100) DEFAULT NULL COMMENT \'Additional HTML file \', `active` tinyint NOT NULL COMMENT \'0: inactive, 1: active\', `sort` int NOT NULL COMMENT \'Tri des blocs\', PRIMARY KEY (`id`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT=\'Blocs associes aux pages\'',
    'columns' => 
    array (
      'id' => 'int NOT NULL AUTO_INCREMENT',
      'pages_id' => 'varchar(255) NOT NULL COMMENT \'Pages IDs (separate by | )\'',
      'modules_id' => 'varchar(255) NOT NULL COMMENT \'Module dirnames (separate by | )\'',
      'name' => 'varchar(100) NOT NULL COMMENT \'Nom du bloc\'',
      'title' => 'text NOT NULL COMMENT \'titre du bloc (cote client)\'',
      'content' => 'text NOT NULL COMMENT \'Contenu du bloc\'',
      'position' => 'varchar(100) NOT NULL',
      'various_view' => 'varchar(100) DEFAULT NULL COMMENT \'Additional HTML file \'',
      'active' => 'tinyint NOT NULL COMMENT \'0: inactive, 1: active\'',
      'sort' => 'int NOT NULL COMMENT \'Tri des blocs\'',
    ),
  ),
  'sb_blocs_sort' => 
  array (
    'module' => '',
    'definition' => '`id` int NOT NULL AUTO_INCREMENT, `bloc_id` int NOT NULL COMMENT \'ID des blocs\', `page_id` int DEFAULT NULL COMMENT \'ID des pages\', `module_id` varchar(50) DEFAULT NULL COMMENT \'Nom du module (nom du repertoire)\', `sort` int NOT NULL COMMENT \'Tri des blocs par page\', PRIMARY KEY (`id`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8',
    'columns' => 
    array (
      'id' => 'int NOT NULL AUTO_INCREMENT',
      'bloc_id' => 'int NOT NULL COMMENT \'ID des blocs\'',
      'page_id' => 'int DEFAULT NULL COMMENT \'ID des pages\'',
      'module_id' => 'varchar(50) DEFAULT NULL COMMENT \'Nom du module (nom du repertoire)\'',
      'sort' => 'int NOT NULL COMMENT \'Tri des blocs par page\'',
    ),
  ),
  'sb_config' => 
  array (
    'module' => '',
    'definition' => '`id` int NOT NULL AUTO_INCREMENT, `config` varchar(50) NOT NULL COMMENT \'Nom de la configuration\', `content` text NOT NULL COMMENT \'Valeur de la configuration\', `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT \'Dernière modification\', PRIMARY KEY (`id`), UNIQUE KEY `config` (`config`), KEY `config_2` (`config`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8',
    'columns' => 
    array (
      'id' => 'int NOT NULL AUTO_INCREMENT',
      'config' => 'varchar(50) NOT NULL COMMENT \'Nom de la configuration\'',
      'content' => 'text NOT NULL COMMENT \'Valeur de la configuration\'',
      'updated_at' => 'datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT \'Dernière modification\'',
    ),
  ),
  'sb_contact' => 
  array (
    'module' => 'contact',
    'definition' => '`id` int NOT NULL AUTO_INCREMENT, `title` varchar(255) NOT NULL, `recipients` text NOT NULL COMMENT \'destinataires\', `subject` text NOT NULL, `form` text NOT NULL, `active` tinyint NOT NULL COMMENT \'0: inactive, 1: active\', `sort` int NOT NULL DEFAULT \'0\', PRIMARY KEY (`id`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8',
    'columns' => 
    array (
      'id' => 'int NOT NULL AUTO_INCREMENT',
      'title' => 'varchar(255) NOT NULL',
      'recipients' => 'text NOT NULL COMMENT \'destinataires\'',
      'subject' => 'text NOT NULL',
      'form' => 'text NOT NULL',
      'active' => 'tinyint NOT NULL COMMENT \'0: inactive, 1: active\'',
      'sort' => 'int NOT NULL DEFAULT \'0\'',
    ),
  ),
  'sb_contact_messages' => 
  array (
    'module' => 'contact',
    'definition' => '`id` int NOT NULL AUTO_INCREMENT, `form_id` int NOT NULL DEFAULT \'0\' COMMENT \'0: page Contact du module\', `created_at` datetime NOT NULL, `name` varchar(255) NOT NULL DEFAULT \'\', `email` varchar(255) NOT NULL DEFAULT \'\', `fields` mediumtext NOT NULL COMMENT \'champs saisis (JSON)\', `recipients` text NOT NULL COMMENT \'destinataires valides\', `sent` tinyint(1) NOT NULL DEFAULT \'0\' COMMENT \'1: mail envoyé\', `error` varchar(255) NOT NULL DEFAULT \'\' COMMENT \'erreur d\'\'envoi\', `is_read` tinyint(1) NOT NULL DEFAULT \'0\', PRIMARY KEY (`id`), KEY `form_created` (`form_id`,`created_at`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci',
    'columns' => 
    array (
      'id' => 'int NOT NULL AUTO_INCREMENT',
      'form_id' => 'int NOT NULL DEFAULT \'0\' COMMENT \'0: page Contact du module\'',
      'created_at' => 'datetime NOT NULL',
      'name' => 'varchar(255) NOT NULL DEFAULT \'\'',
      'email' => 'varchar(255) NOT NULL DEFAULT \'\'',
      'fields' => 'mediumtext NOT NULL COMMENT \'champs saisis (JSON)\'',
      'recipients' => 'text NOT NULL COMMENT \'destinataires valides\'',
      'sent' => 'tinyint(1) NOT NULL DEFAULT \'0\' COMMENT \'1: mail envoyé\'',
      'error' => 'varchar(255) NOT NULL DEFAULT \'\' COMMENT \'erreur d\'\'envoi\'',
      'is_read' => 'tinyint(1) NOT NULL DEFAULT \'0\'',
    ),
  ),
  'sb_country' => 
  array (
    'module' => '',
    'definition' => '`country_iso` char(2) NOT NULL, `country_name` varchar(80) NOT NULL, `country_printable_name` varchar(80) NOT NULL, `country_iso3` char(3) DEFAULT NULL, `country_numcode` smallint DEFAULT NULL, PRIMARY KEY (`country_iso`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8',
    'columns' => 
    array (
      'country_iso' => 'char(2) NOT NULL',
      'country_name' => 'varchar(80) NOT NULL',
      'country_printable_name' => 'varchar(80) NOT NULL',
      'country_iso3' => 'char(3) DEFAULT NULL',
      'country_numcode' => 'smallint DEFAULT NULL',
    ),
  ),
  'sb_dashboard_widgets' => 
  array (
    'module' => '',
    'definition' => '`id` int NOT NULL AUTO_INCREMENT, `type` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'table\', `position` int NOT NULL DEFAULT \'0\', `table_name` varchar(64) COLLATE utf8mb4_general_ci NOT NULL, `value_column` varchar(64) COLLATE utf8mb4_general_ci NOT NULL, `date_column` varchar(64) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\', `widget_key` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\', `location` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\', `content` longtext COLLATE utf8mb4_general_ci, `title` varchar(100) COLLATE utf8mb4_general_ci NOT NULL, `link` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\', `icon` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\', `color` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'primary\', `show_chart` tinyint(1) NOT NULL DEFAULT \'0\', `active` tinyint(1) NOT NULL DEFAULT \'1\', PRIMARY KEY (`id`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci',
    'columns' => 
    array (
      'id' => 'int NOT NULL AUTO_INCREMENT',
      'type' => 'varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'table\'',
      'position' => 'int NOT NULL DEFAULT \'0\'',
      'table_name' => 'varchar(64) COLLATE utf8mb4_general_ci NOT NULL',
      'value_column' => 'varchar(64) COLLATE utf8mb4_general_ci NOT NULL',
      'date_column' => 'varchar(64) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\'',
      'widget_key' => 'varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\'',
      'location' => 'varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\'',
      'content' => 'longtext COLLATE utf8mb4_general_ci',
      'title' => 'varchar(100) COLLATE utf8mb4_general_ci NOT NULL',
      'link' => 'varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\'',
      'icon' => 'varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\'',
      'color' => 'varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'primary\'',
      'show_chart' => 'tinyint(1) NOT NULL DEFAULT \'0\'',
      'active' => 'tinyint(1) NOT NULL DEFAULT \'1\'',
    ),
  ),
  'sb_download' => 
  array (
    'module' => 'download',
    'definition' => '`id` int NOT NULL AUTO_INCREMENT, `title` text COLLATE utf8mb4_general_ci NOT NULL, `description` text COLLATE utf8mb4_general_ci, `filename` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\' COMMENT \'Nom du fichier\', `size` varchar(32) COLLATE utf8mb4_general_ci DEFAULT NULL, `downloaded` int NOT NULL DEFAULT \'0\' COMMENT \'Nombre de telechargements\', `randkey` varchar(80) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\' COMMENT \'sbGenerateRandKey() renvoie 65 caracteres\', `active` tinyint NOT NULL DEFAULT \'1\' COMMENT \'0: inactive, 1: active\', PRIMARY KEY (`id`), KEY `randkey` (`randkey`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci',
    'columns' => 
    array (
      'id' => 'int NOT NULL AUTO_INCREMENT',
      'title' => 'text COLLATE utf8mb4_general_ci NOT NULL',
      'description' => 'text COLLATE utf8mb4_general_ci',
      'filename' => 'varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\' COMMENT \'Nom du fichier\'',
      'size' => 'varchar(32) COLLATE utf8mb4_general_ci DEFAULT NULL',
      'downloaded' => 'int NOT NULL DEFAULT \'0\' COMMENT \'Nombre de telechargements\'',
      'randkey' => 'varchar(80) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\' COMMENT \'sbGenerateRandKey() renvoie 65 caracteres\'',
      'active' => 'tinyint NOT NULL DEFAULT \'1\' COMMENT \'0: inactive, 1: active\'',
    ),
  ),
  'sb_faq' => 
  array (
    'module' => 'faq',
    'definition' => '`id` int NOT NULL AUTO_INCREMENT, `category` int NOT NULL, `question` varchar(255) COLLATE utf8mb4_general_ci NOT NULL, `response` text COLLATE utf8mb4_general_ci NOT NULL, `sort` int NOT NULL DEFAULT \'0\', `active` tinyint NOT NULL DEFAULT \'1\' COMMENT \'0: inactive, 1: active\', PRIMARY KEY (`id`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci',
    'columns' => 
    array (
      'id' => 'int NOT NULL AUTO_INCREMENT',
      'category' => 'int NOT NULL',
      'question' => 'varchar(255) COLLATE utf8mb4_general_ci NOT NULL',
      'response' => 'text COLLATE utf8mb4_general_ci NOT NULL',
      'sort' => 'int NOT NULL DEFAULT \'0\'',
      'active' => 'tinyint NOT NULL DEFAULT \'1\' COMMENT \'0: inactive, 1: active\'',
    ),
  ),
  'sb_faq_category' => 
  array (
    'module' => 'faq',
    'definition' => '`id` int NOT NULL AUTO_INCREMENT, `name` text COLLATE utf8mb4_general_ci NOT NULL, `active` tinyint NOT NULL DEFAULT \'1\' COMMENT \'0: inactive, 1: active\', PRIMARY KEY (`id`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci',
    'columns' => 
    array (
      'id' => 'int NOT NULL AUTO_INCREMENT',
      'name' => 'text COLLATE utf8mb4_general_ci NOT NULL',
      'active' => 'tinyint NOT NULL DEFAULT \'1\' COMMENT \'0: inactive, 1: active\'',
    ),
  ),
  'sb_flood' => 
  array (
    'module' => '',
    'definition' => '`ip` varchar(18) NOT NULL DEFAULT \'\', `time` int NOT NULL DEFAULT \'0\', PRIMARY KEY (`ip`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8',
    'columns' => 
    array (
      'ip' => 'varchar(18) NOT NULL DEFAULT \'\'',
      'time' => 'int NOT NULL DEFAULT \'0\'',
    ),
  ),
  'sb_gallery' => 
  array (
    'module' => 'gallery',
    'definition' => '`id` int NOT NULL AUTO_INCREMENT, `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL, `css` text COLLATE utf8mb4_general_ci, `javascript` text COLLATE utf8mb4_general_ci, `template` text COLLATE utf8mb4_general_ci, `active` tinyint NOT NULL DEFAULT \'1\' COMMENT \'0: inactive, 1: active\', PRIMARY KEY (`id`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci',
    'columns' => 
    array (
      'id' => 'int NOT NULL AUTO_INCREMENT',
      'title' => 'varchar(255) COLLATE utf8mb4_general_ci NOT NULL',
      'css' => 'text COLLATE utf8mb4_general_ci',
      'javascript' => 'text COLLATE utf8mb4_general_ci',
      'template' => 'text COLLATE utf8mb4_general_ci',
      'active' => 'tinyint NOT NULL DEFAULT \'1\' COMMENT \'0: inactive, 1: active\'',
    ),
  ),
  'sb_gallery_photos' => 
  array (
    'module' => 'gallery',
    'definition' => '`id` int NOT NULL AUTO_INCREMENT, `gid` int NOT NULL DEFAULT \'0\' COMMENT \'Gallery id\', `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL COMMENT \'Nom de la photo\', `photo` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\' COMMENT \'Nom de l\'\'image physique, ou URL de la video\', `type` varchar(16) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'photo\' COMMENT \'video, photo\', `active` tinyint NOT NULL DEFAULT \'1\' COMMENT \'0: inactive, 1: active\', `sort` int NOT NULL DEFAULT \'0\', PRIMARY KEY (`id`), KEY `gid` (`gid`,`sort`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci',
    'columns' => 
    array (
      'id' => 'int NOT NULL AUTO_INCREMENT',
      'gid' => 'int NOT NULL DEFAULT \'0\' COMMENT \'Gallery id\'',
      'title' => 'varchar(255) COLLATE utf8mb4_general_ci NOT NULL COMMENT \'Nom de la photo\'',
      'photo' => 'varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\' COMMENT \'Nom de l\'\'image physique, ou URL de la video\'',
      'type' => 'varchar(16) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'photo\' COMMENT \'video, photo\'',
      'active' => 'tinyint NOT NULL DEFAULT \'1\' COMMENT \'0: inactive, 1: active\'',
      'sort' => 'int NOT NULL DEFAULT \'0\'',
    ),
  ),
  'sb_logaccess' => 
  array (
    'module' => '',
    'definition' => '`id` int NOT NULL AUTO_INCREMENT, `logaccess_type` varchar(10) NOT NULL, `logaccess_date` int NOT NULL, `logaccess_user` varchar(20) NOT NULL, `logaccess_event` text NOT NULL, PRIMARY KEY (`id`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT=\'Table Activity log\'',
    'columns' => 
    array (
      'id' => 'int NOT NULL AUTO_INCREMENT',
      'logaccess_type' => 'varchar(10) NOT NULL',
      'logaccess_date' => 'int NOT NULL',
      'logaccess_user' => 'varchar(20) NOT NULL',
      'logaccess_event' => 'text NOT NULL',
    ),
  ),
  'sb_menu' => 
  array (
    'module' => '',
    'definition' => '`id` int NOT NULL AUTO_INCREMENT, `name` varchar(100) NOT NULL, `tag` varchar(50) NOT NULL COMMENT \'Smarty variable\', `pages` varchar(255) NOT NULL COMMENT \'Pages IDs (separate by | )\', `active` tinyint NOT NULL COMMENT \'0: inactive, 1: active\', PRIMARY KEY (`id`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8',
    'columns' => 
    array (
      'id' => 'int NOT NULL AUTO_INCREMENT',
      'name' => 'varchar(100) NOT NULL',
      'tag' => 'varchar(50) NOT NULL COMMENT \'Smarty variable\'',
      'pages' => 'varchar(255) NOT NULL COMMENT \'Pages IDs (separate by | )\'',
      'active' => 'tinyint NOT NULL COMMENT \'0: inactive, 1: active\'',
    ),
  ),
  'sb_messages' => 
  array (
    'module' => '',
    'definition' => '`id` int NOT NULL AUTO_INCREMENT, `sender_id` int NOT NULL, `recipient_id` int NOT NULL, `message` text COLLATE utf8mb4_general_ci NOT NULL, `created_at` int NOT NULL, `read_at` int NOT NULL DEFAULT \'0\', PRIMARY KEY (`id`), KEY `sender_id` (`sender_id`), KEY `recipient_id` (`recipient_id`,`read_at`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci',
    'columns' => 
    array (
      'id' => 'int NOT NULL AUTO_INCREMENT',
      'sender_id' => 'int NOT NULL',
      'recipient_id' => 'int NOT NULL',
      'message' => 'text COLLATE utf8mb4_general_ci NOT NULL',
      'created_at' => 'int NOT NULL',
      'read_at' => 'int NOT NULL DEFAULT \'0\'',
    ),
  ),
  'sb_modules' => 
  array (
    'module' => '',
    'definition' => '`name` varchar(32) COLLATE utf8mb4_general_ci NOT NULL, `version` varchar(20) COLLATE utf8mb4_general_ci NOT NULL, `db_version` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\', `installed_at` datetime NOT NULL, `updated_at` datetime DEFAULT NULL, PRIMARY KEY (`name`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci',
    'columns' => 
    array (
      'name' => 'varchar(32) COLLATE utf8mb4_general_ci NOT NULL',
      'version' => 'varchar(20) COLLATE utf8mb4_general_ci NOT NULL',
      'db_version' => 'varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\'',
      'installed_at' => 'datetime NOT NULL',
      'updated_at' => 'datetime DEFAULT NULL',
    ),
  ),
  'sb_news' => 
  array (
    'module' => 'news',
    'definition' => '`id` int NOT NULL AUTO_INCREMENT, `catid` varchar(50) NOT NULL COMMENT \'Categories\', `title` text NOT NULL, `subtitle` text NOT NULL, `desc_short` text NOT NULL, `desc_full` text NOT NULL, `image` varchar(200) DEFAULT NULL, `date` varchar(10) NOT NULL, `viewed` int NOT NULL DEFAULT \'0\' COMMENT \'Compteur de lectures\', `active` tinyint NOT NULL COMMENT \'0: inactive, 1: active\', PRIMARY KEY (`id`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8',
    'columns' => 
    array (
      'id' => 'int NOT NULL AUTO_INCREMENT',
      'catid' => 'varchar(50) NOT NULL COMMENT \'Categories\'',
      'title' => 'text NOT NULL',
      'subtitle' => 'text NOT NULL',
      'desc_short' => 'text NOT NULL',
      'desc_full' => 'text NOT NULL',
      'image' => 'varchar(200) DEFAULT NULL',
      'date' => 'varchar(10) NOT NULL',
      'viewed' => 'int NOT NULL DEFAULT \'0\' COMMENT \'Compteur de lectures\'',
      'active' => 'tinyint NOT NULL COMMENT \'0: inactive, 1: active\'',
    ),
  ),
  'sb_news_category' => 
  array (
    'module' => 'news',
    'definition' => '`id` int NOT NULL AUTO_INCREMENT, `title` text NOT NULL, `subtitle` text, `tpl_list` text, `tpl_single` text, `module_show` varchar(50) DEFAULT NULL COMMENT \'normal,masonry,...\', `module_show_masonry` int DEFAULT NULL COMMENT \'columns width (pixels)\', `photo` varchar(255) DEFAULT NULL, `active` tinyint NOT NULL COMMENT \'0: inactive, 1: active\', `sort` int NOT NULL, PRIMARY KEY (`id`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8',
    'columns' => 
    array (
      'id' => 'int NOT NULL AUTO_INCREMENT',
      'title' => 'text NOT NULL',
      'subtitle' => 'text',
      'tpl_list' => 'text',
      'tpl_single' => 'text',
      'module_show' => 'varchar(50) DEFAULT NULL COMMENT \'normal,masonry,...\'',
      'module_show_masonry' => 'int DEFAULT NULL COMMENT \'columns width (pixels)\'',
      'photo' => 'varchar(255) DEFAULT NULL',
      'active' => 'tinyint NOT NULL COMMENT \'0: inactive, 1: active\'',
      'sort' => 'int NOT NULL',
    ),
  ),
  'sb_news_settings' => 
  array (
    'module' => 'news',
    'definition' => '`id` int NOT NULL AUTO_INCREMENT, `item_per_page` int NOT NULL COMMENT \'article par page (categorie)\', `module_start` tinyint NOT NULL COMMENT \'0: liste des categories, 1: categorie specifique\', `catid` text NOT NULL COMMENT \'Demarrage par ces categories\', `catid_module_show` int DEFAULT NULL COMMENT \'Catégorie affichage principal\', `breadcrumb` tinyint NOT NULL COMMENT \'0: inactive, 1: active\', `title_h1` tinyint NOT NULL COMMENT \'0: inactive, 1: active\', `title_h2` tinyint NOT NULL COMMENT \'0: inactive, 1: active\', `theme_view_cat` varchar(100) NOT NULL DEFAULT \'index\' COMMENT \'Theme view du module LISTE DES CATEGORIES\', `theme_view_list` varchar(100) NOT NULL DEFAULT \'index\' COMMENT \'Theme view du module LISTE DES ARTICLES\', `theme_view_single` varchar(100) NOT NULL DEFAULT \'index\' COMMENT \'Theme view du module ARTICLE\', `other_news` tinyint NOT NULL, `other_news_per_page` int NOT NULL, `other_news_title` varchar(255) NOT NULL DEFAULT \'Autres articles\', `other_news_type` varchar(20) NOT NULL COMMENT \'random, latest, first\', `news_next_prev` varchar(20) NOT NULL DEFAULT \'arrow\' COMMENT \'arrow, title\', `comments` varchar(20) DEFAULT NULL, `comments_user` varchar(100) DEFAULT NULL, PRIMARY KEY (`id`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8',
    'columns' => 
    array (
      'id' => 'int NOT NULL AUTO_INCREMENT',
      'item_per_page' => 'int NOT NULL COMMENT \'article par page (categorie)\'',
      'module_start' => 'tinyint NOT NULL COMMENT \'0: liste des categories, 1: categorie specifique\'',
      'catid' => 'text NOT NULL COMMENT \'Demarrage par ces categories\'',
      'catid_module_show' => 'int DEFAULT NULL COMMENT \'Catégorie affichage principal\'',
      'breadcrumb' => 'tinyint NOT NULL COMMENT \'0: inactive, 1: active\'',
      'title_h1' => 'tinyint NOT NULL COMMENT \'0: inactive, 1: active\'',
      'title_h2' => 'tinyint NOT NULL COMMENT \'0: inactive, 1: active\'',
      'theme_view_cat' => 'varchar(100) NOT NULL DEFAULT \'index\' COMMENT \'Theme view du module LISTE DES CATEGORIES\'',
      'theme_view_list' => 'varchar(100) NOT NULL DEFAULT \'index\' COMMENT \'Theme view du module LISTE DES ARTICLES\'',
      'theme_view_single' => 'varchar(100) NOT NULL DEFAULT \'index\' COMMENT \'Theme view du module ARTICLE\'',
      'other_news' => 'tinyint NOT NULL',
      'other_news_per_page' => 'int NOT NULL',
      'other_news_title' => 'varchar(255) NOT NULL DEFAULT \'Autres articles\'',
      'other_news_type' => 'varchar(20) NOT NULL COMMENT \'random, latest, first\'',
      'news_next_prev' => 'varchar(20) NOT NULL DEFAULT \'arrow\' COMMENT \'arrow, title\'',
      'comments' => 'varchar(20) DEFAULT NULL',
      'comments_user' => 'varchar(100) DEFAULT NULL',
    ),
  ),
  'sb_pages' => 
  array (
    'module' => '',
    'definition' => '`id` int NOT NULL AUTO_INCREMENT, `menu` varchar(100) NOT NULL COMMENT \'Texte du menu\', `title` varchar(255) NOT NULL COMMENT \'Titre de la page\', `content` text NOT NULL, `seo_url` text NOT NULL, `url_custom` varchar(255) DEFAULT NULL, `seo_keywords` text NOT NULL COMMENT \'Mots cles additionnels de la page\', `seo_description` varchar(155) NOT NULL COMMENT \'Meta description additionnels de la page\', `module_view` varchar(50) NOT NULL COMMENT \'Module view for the current page\', `theme_view` varchar(50) NOT NULL COMMENT \'Theme view defined by CMS theme\', `various_view` varchar(100) DEFAULT NULL COMMENT \'Additional HTML file\', `headpage` text NOT NULL COMMENT \'Code entete page theme (si declare dans config)\', `photo` text COMMENT \'Banner\', `active` tinyint NOT NULL COMMENT \'0: inactive, 1: active\', `sort` int NOT NULL COMMENT \'Tri des pages / menus\', PRIMARY KEY (`id`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT=\'Pages libres du site\'',
    'columns' => 
    array (
      'id' => 'int NOT NULL AUTO_INCREMENT',
      'menu' => 'varchar(100) NOT NULL COMMENT \'Texte du menu\'',
      'title' => 'varchar(255) NOT NULL COMMENT \'Titre de la page\'',
      'content' => 'text NOT NULL',
      'seo_url' => 'text NOT NULL',
      'url_custom' => 'varchar(255) DEFAULT NULL',
      'seo_keywords' => 'text NOT NULL COMMENT \'Mots cles additionnels de la page\'',
      'seo_description' => 'varchar(155) NOT NULL COMMENT \'Meta description additionnels de la page\'',
      'module_view' => 'varchar(50) NOT NULL COMMENT \'Module view for the current page\'',
      'theme_view' => 'varchar(50) NOT NULL COMMENT \'Theme view defined by CMS theme\'',
      'various_view' => 'varchar(100) DEFAULT NULL COMMENT \'Additional HTML file\'',
      'headpage' => 'text NOT NULL COMMENT \'Code entete page theme (si declare dans config)\'',
      'photo' => 'text COMMENT \'Banner\'',
      'active' => 'tinyint NOT NULL COMMENT \'0: inactive, 1: active\'',
      'sort' => 'int NOT NULL COMMENT \'Tri des pages / menus\'',
    ),
  ),
  'sb_sandbox' => 
  array (
    'module' => '',
    'definition' => '`id` int NOT NULL AUTO_INCREMENT, `active` tinyint(1) NOT NULL DEFAULT \'1\', `yourname` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\', `montant` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\', `seo_url` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\', `country` varchar(100) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\', `dob` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\', `color` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\', `tags` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\', `pdf` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\', `photo` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\', `video` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\', `option_one` tinyint(1) NOT NULL DEFAULT \'0\', `option_two` tinyint(1) NOT NULL DEFAULT \'0\', `option_three` tinyint(1) NOT NULL DEFAULT \'0\', `type` varchar(5) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\', `selection` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\', `comment` text COLLATE utf8mb4_general_ci, `comment_editor1` longtext COLLATE utf8mb4_general_ci, `comment_editor2` longtext COLLATE utf8mb4_general_ci, `comment_editor3` longtext COLLATE utf8mb4_general_ci, `page_builder_content` longtext COLLATE utf8mb4_general_ci, `sort` int NOT NULL DEFAULT \'0\', PRIMARY KEY (`id`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT=\'Table SANDBOX (kitchen-sink des champs du formulaire)\'',
    'columns' => 
    array (
      'id' => 'int NOT NULL AUTO_INCREMENT',
      'active' => 'tinyint(1) NOT NULL DEFAULT \'1\'',
      'yourname' => 'varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\'',
      'montant' => 'varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\'',
      'seo_url' => 'varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\'',
      'country' => 'varchar(100) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\'',
      'dob' => 'varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\'',
      'color' => 'varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\'',
      'tags' => 'varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\'',
      'pdf' => 'varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\'',
      'photo' => 'varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\'',
      'video' => 'varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\'',
      'option_one' => 'tinyint(1) NOT NULL DEFAULT \'0\'',
      'option_two' => 'tinyint(1) NOT NULL DEFAULT \'0\'',
      'option_three' => 'tinyint(1) NOT NULL DEFAULT \'0\'',
      'type' => 'varchar(5) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\'',
      'selection' => 'varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\'',
      'comment' => 'text COLLATE utf8mb4_general_ci',
      'comment_editor1' => 'longtext COLLATE utf8mb4_general_ci',
      'comment_editor2' => 'longtext COLLATE utf8mb4_general_ci',
      'comment_editor3' => 'longtext COLLATE utf8mb4_general_ci',
      'page_builder_content' => 'longtext COLLATE utf8mb4_general_ci',
      'sort' => 'int NOT NULL DEFAULT \'0\'',
    ),
  ),
  'sb_sessions' => 
  array (
    'module' => '',
    'definition' => '`id` int NOT NULL AUTO_INCREMENT, `uid` int NOT NULL, `username` varchar(30) NOT NULL, `hash` varchar(32) NOT NULL, `expiredate` datetime NOT NULL, `ip` varchar(15) NOT NULL, PRIMARY KEY (`id`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT=\'Table Session\'',
    'columns' => 
    array (
      'id' => 'int NOT NULL AUTO_INCREMENT',
      'uid' => 'int NOT NULL',
      'username' => 'varchar(30) NOT NULL',
      'hash' => 'varchar(32) NOT NULL',
      'expiredate' => 'datetime NOT NULL',
      'ip' => 'varchar(15) NOT NULL',
    ),
  ),
  'sb_slider' => 
  array (
    'module' => 'slider',
    'definition' => '`id` int NOT NULL AUTO_INCREMENT, `title` varchar(255) NOT NULL, `jquery` tinyint NOT NULL COMMENT \'Chargement de jquery\', `responsive` tinyint NOT NULL COMMENT \'0: inactive, 1: active\', `auto` tinyint NOT NULL COMMENT \'0: inactive, 1: active\', `pause` int NOT NULL, `speed` int NOT NULL, `randomstart` tinyint NOT NULL COMMENT \'0: inactive, 1: active\', `mode` varchar(50) NOT NULL, `preloadimages` varchar(50) NOT NULL, `controls` tinyint NOT NULL COMMENT \'0: inactive, 1: active\', `autocontrols` tinyint NOT NULL COMMENT \'0: inactive, 1: active\', `autohover` tinyint NOT NULL COMMENT \'0: inactive, 1: active\', `captions` tinyint NOT NULL COMMENT \'0: inactive, 1: active\', `adaptiveheight` tinyint NOT NULL COMMENT \'0: inactive, 1: active\', `adaptiveheightspeed` int NOT NULL, `slidemargin` int NOT NULL, `video` tinyint NOT NULL COMMENT \'0: inactive, 1: active\', `usecss` tinyint NOT NULL COMMENT \'0: inactive, 1: active\', `pager` tinyint NOT NULL COMMENT \'0: inactive, 1: active\', `pagertype` varchar(50) NOT NULL COMMENT \'full, short\', `active` tinyint NOT NULL COMMENT \'0: inactive, 1: active\', PRIMARY KEY (`id`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8',
    'columns' => 
    array (
      'id' => 'int NOT NULL AUTO_INCREMENT',
      'title' => 'varchar(255) NOT NULL',
      'jquery' => 'tinyint NOT NULL COMMENT \'Chargement de jquery\'',
      'responsive' => 'tinyint NOT NULL COMMENT \'0: inactive, 1: active\'',
      'auto' => 'tinyint NOT NULL COMMENT \'0: inactive, 1: active\'',
      'pause' => 'int NOT NULL',
      'speed' => 'int NOT NULL',
      'randomstart' => 'tinyint NOT NULL COMMENT \'0: inactive, 1: active\'',
      'mode' => 'varchar(50) NOT NULL',
      'preloadimages' => 'varchar(50) NOT NULL',
      'controls' => 'tinyint NOT NULL COMMENT \'0: inactive, 1: active\'',
      'autocontrols' => 'tinyint NOT NULL COMMENT \'0: inactive, 1: active\'',
      'autohover' => 'tinyint NOT NULL COMMENT \'0: inactive, 1: active\'',
      'captions' => 'tinyint NOT NULL COMMENT \'0: inactive, 1: active\'',
      'adaptiveheight' => 'tinyint NOT NULL COMMENT \'0: inactive, 1: active\'',
      'adaptiveheightspeed' => 'int NOT NULL',
      'slidemargin' => 'int NOT NULL',
      'video' => 'tinyint NOT NULL COMMENT \'0: inactive, 1: active\'',
      'usecss' => 'tinyint NOT NULL COMMENT \'0: inactive, 1: active\'',
      'pager' => 'tinyint NOT NULL COMMENT \'0: inactive, 1: active\'',
      'pagertype' => 'varchar(50) NOT NULL COMMENT \'full, short\'',
      'active' => 'tinyint NOT NULL COMMENT \'0: inactive, 1: active\'',
    ),
  ),
  'sb_slider_photos' => 
  array (
    'module' => 'slider',
    'definition' => '`id` int NOT NULL AUTO_INCREMENT, `sid` int NOT NULL COMMENT \'Slider id\', `title` varchar(255) NOT NULL COMMENT \'Nom de la photo\', `photo` varchar(255) NOT NULL COMMENT \'Nom de l\'\'image physique\', `type` varchar(10) NOT NULL COMMENT \'video, photo\', `active` tinyint NOT NULL COMMENT \'0: inactive, 1: active\', `sort` int NOT NULL, PRIMARY KEY (`id`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8',
    'columns' => 
    array (
      'id' => 'int NOT NULL AUTO_INCREMENT',
      'sid' => 'int NOT NULL COMMENT \'Slider id\'',
      'title' => 'varchar(255) NOT NULL COMMENT \'Nom de la photo\'',
      'photo' => 'varchar(255) NOT NULL COMMENT \'Nom de l\'\'image physique\'',
      'type' => 'varchar(10) NOT NULL COMMENT \'video, photo\'',
      'active' => 'tinyint NOT NULL COMMENT \'0: inactive, 1: active\'',
      'sort' => 'int NOT NULL',
    ),
  ),
  'sb_store_accounting_export' => 
  array (
    'module' => 'store',
    'definition' => '`id` int NOT NULL AUTO_INCREMENT, `plugin_id` varchar(50) COLLATE utf8mb4_general_ci NOT NULL, `active` tinyint(1) NOT NULL DEFAULT \'0\', `config` text COLLATE utf8mb4_general_ci NOT NULL COMMENT \'JSON propre au plugin\', PRIMARY KEY (`id`), KEY `plugin_id` (`plugin_id`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci',
    'columns' => 
    array (
      'id' => 'int NOT NULL AUTO_INCREMENT',
      'plugin_id' => 'varchar(50) COLLATE utf8mb4_general_ci NOT NULL',
      'active' => 'tinyint(1) NOT NULL DEFAULT \'0\'',
      'config' => 'text COLLATE utf8mb4_general_ci NOT NULL COMMENT \'JSON propre au plugin\'',
    ),
  ),
  'sb_store_accounting_log' => 
  array (
    'module' => 'store',
    'definition' => '`id` int NOT NULL AUTO_INCREMENT, `oid` int NOT NULL, `plugin_id` varchar(50) COLLATE utf8mb4_general_ci NOT NULL, `status` enum(\'succes\',\'echec\',\'en_attente\') COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'en_attente\', `message` text COLLATE utf8mb4_general_ci NOT NULL, `sent_at` int DEFAULT NULL, PRIMARY KEY (`id`), KEY `oid` (`oid`), KEY `plugin_id` (`plugin_id`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci',
    'columns' => 
    array (
      'id' => 'int NOT NULL AUTO_INCREMENT',
      'oid' => 'int NOT NULL',
      'plugin_id' => 'varchar(50) COLLATE utf8mb4_general_ci NOT NULL',
      'status' => 'enum(\'succes\',\'echec\',\'en_attente\') COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'en_attente\'',
      'message' => 'text COLLATE utf8mb4_general_ci NOT NULL',
      'sent_at' => 'int DEFAULT NULL',
    ),
  ),
  'sb_store_category' => 
  array (
    'module' => 'store',
    'definition' => '`id` int NOT NULL AUTO_INCREMENT, `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL, `description` text COLLATE utf8mb4_general_ci NOT NULL, `photo` varchar(200) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\', `sort` int NOT NULL DEFAULT \'0\', `active` tinyint(1) NOT NULL DEFAULT \'1\', PRIMARY KEY (`id`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci',
    'columns' => 
    array (
      'id' => 'int NOT NULL AUTO_INCREMENT',
      'title' => 'varchar(255) COLLATE utf8mb4_general_ci NOT NULL',
      'description' => 'text COLLATE utf8mb4_general_ci NOT NULL',
      'photo' => 'varchar(200) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\'',
      'sort' => 'int NOT NULL DEFAULT \'0\'',
      'active' => 'tinyint(1) NOT NULL DEFAULT \'1\'',
    ),
  ),
  'sb_store_config' => 
  array (
    'module' => 'store',
    'definition' => '`id` int NOT NULL AUTO_INCREMENT, `is_tva` tinyint(1) NOT NULL DEFAULT \'1\', `currency` varchar(10) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'EUR\', `currency_text` varchar(10) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'€\', `currency_position` tinyint(1) NOT NULL DEFAULT \'1\', `n_decimals` tinyint(1) NOT NULL DEFAULT \'2\', `per_page` int NOT NULL DEFAULT \'12\', `invoice_prefix` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'FAC\', `invoice_format_plugin` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'facturx\', `unique_code_root` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\', `unique_code_key` varchar(100) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\', `unique_code_pattern` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'alphanumeric\', `unique_code_length` int NOT NULL DEFAULT \'16\', PRIMARY KEY (`id`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci',
    'columns' => 
    array (
      'id' => 'int NOT NULL AUTO_INCREMENT',
      'is_tva' => 'tinyint(1) NOT NULL DEFAULT \'1\'',
      'currency' => 'varchar(10) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'EUR\'',
      'currency_text' => 'varchar(10) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'€\'',
      'currency_position' => 'tinyint(1) NOT NULL DEFAULT \'1\'',
      'n_decimals' => 'tinyint(1) NOT NULL DEFAULT \'2\'',
      'per_page' => 'int NOT NULL DEFAULT \'12\'',
      'invoice_prefix' => 'varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'FAC\'',
      'invoice_format_plugin' => 'varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'facturx\'',
      'unique_code_root' => 'varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\'',
      'unique_code_key' => 'varchar(100) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\'',
      'unique_code_pattern' => 'varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'alphanumeric\'',
      'unique_code_length' => 'int NOT NULL DEFAULT \'16\'',
    ),
  ),
  'sb_store_discount' => 
  array (
    'module' => 'store',
    'definition' => '`id` int NOT NULL AUTO_INCREMENT, `code` varchar(50) COLLATE utf8mb4_general_ci NOT NULL, `plugin_id` varchar(50) COLLATE utf8mb4_general_ci NOT NULL, `active` tinyint(1) NOT NULL DEFAULT \'1\', `expiration` int DEFAULT NULL, `code_limit` int NOT NULL DEFAULT \'0\', `code_usage` int NOT NULL DEFAULT \'0\', `valeur` decimal(10,2) NOT NULL DEFAULT \'0.00\', `product_id` int DEFAULT NULL, `uid` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\', `description` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\', PRIMARY KEY (`id`), UNIQUE KEY `code` (`code`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci',
    'columns' => 
    array (
      'id' => 'int NOT NULL AUTO_INCREMENT',
      'code' => 'varchar(50) COLLATE utf8mb4_general_ci NOT NULL',
      'plugin_id' => 'varchar(50) COLLATE utf8mb4_general_ci NOT NULL',
      'active' => 'tinyint(1) NOT NULL DEFAULT \'1\'',
      'expiration' => 'int DEFAULT NULL',
      'code_limit' => 'int NOT NULL DEFAULT \'0\'',
      'code_usage' => 'int NOT NULL DEFAULT \'0\'',
      'valeur' => 'decimal(10,2) NOT NULL DEFAULT \'0.00\'',
      'product_id' => 'int DEFAULT NULL',
      'uid' => 'varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\'',
      'description' => 'varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\'',
    ),
  ),
  'sb_store_email' => 
  array (
    'module' => 'store',
    'definition' => '`id` int NOT NULL AUTO_INCREMENT, `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL, `subject_customer` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\', `body_customer` text COLLATE utf8mb4_general_ci NOT NULL, `subject_admin` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\', `body_admin` text COLLATE utf8mb4_general_ci NOT NULL, PRIMARY KEY (`id`), UNIQUE KEY `name` (`name`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci',
    'columns' => 
    array (
      'id' => 'int NOT NULL AUTO_INCREMENT',
      'name' => 'varchar(100) COLLATE utf8mb4_general_ci NOT NULL',
      'subject_customer' => 'varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\'',
      'body_customer' => 'text COLLATE utf8mb4_general_ci NOT NULL',
      'subject_admin' => 'varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\'',
      'body_admin' => 'text COLLATE utf8mb4_general_ci NOT NULL',
    ),
  ),
  'sb_store_order' => 
  array (
    'module' => 'store',
    'definition' => '`id` int NOT NULL AUTO_INCREMENT, `client_uid` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\', `client_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\', `client_email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\', `date` int NOT NULL, `status` tinyint NOT NULL DEFAULT \'0\', `payment_plugin` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\', `invoice_num` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\', `invoice_generated` tinyint(1) NOT NULL DEFAULT \'0\', `total` decimal(10,2) NOT NULL DEFAULT \'0.00\', `comment` text COLLATE utf8mb4_general_ci NOT NULL, PRIMARY KEY (`id`), KEY `client_uid` (`client_uid`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci',
    'columns' => 
    array (
      'id' => 'int NOT NULL AUTO_INCREMENT',
      'client_uid' => 'varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\'',
      'client_name' => 'varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\'',
      'client_email' => 'varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\'',
      'date' => 'int NOT NULL',
      'status' => 'tinyint NOT NULL DEFAULT \'0\'',
      'payment_plugin' => 'varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\'',
      'invoice_num' => 'varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\'',
      'invoice_generated' => 'tinyint(1) NOT NULL DEFAULT \'0\'',
      'total' => 'decimal(10,2) NOT NULL DEFAULT \'0.00\'',
      'comment' => 'text COLLATE utf8mb4_general_ci NOT NULL',
    ),
  ),
  'sb_store_order_detail' => 
  array (
    'module' => 'store',
    'definition' => '`id` int NOT NULL AUTO_INCREMENT, `oid` int NOT NULL, `pid` int NOT NULL, `reference` varchar(100) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\', `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\', `description` text COLLATE utf8mb4_general_ci NOT NULL, `photo` varchar(200) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\', `quantity` int NOT NULL DEFAULT \'1\', `price` decimal(10,2) NOT NULL DEFAULT \'0.00\', `total_price` decimal(10,2) NOT NULL DEFAULT \'0.00\', `mode` enum(\'physique\',\'dematerialise\') COLLATE utf8mb4_general_ci DEFAULT NULL, `tva` text COLLATE utf8mb4_general_ci NOT NULL COMMENT \'JSON figé au moment de la commande\', `transport_title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\', `transport_price` decimal(10,2) NOT NULL DEFAULT \'0.00\', `promo_code` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\', `promo_type` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\', `promo_price` decimal(10,2) NOT NULL DEFAULT \'0.00\', `promo_description` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\', `client_message` text COLLATE utf8mb4_general_ci NOT NULL, `gift_package` tinyint(1) NOT NULL DEFAULT \'0\', PRIMARY KEY (`id`), KEY `oid` (`oid`), KEY `pid` (`pid`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci',
    'columns' => 
    array (
      'id' => 'int NOT NULL AUTO_INCREMENT',
      'oid' => 'int NOT NULL',
      'pid' => 'int NOT NULL',
      'reference' => 'varchar(100) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\'',
      'title' => 'varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\'',
      'description' => 'text COLLATE utf8mb4_general_ci NOT NULL',
      'photo' => 'varchar(200) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\'',
      'quantity' => 'int NOT NULL DEFAULT \'1\'',
      'price' => 'decimal(10,2) NOT NULL DEFAULT \'0.00\'',
      'total_price' => 'decimal(10,2) NOT NULL DEFAULT \'0.00\'',
      'mode' => 'enum(\'physique\',\'dematerialise\') COLLATE utf8mb4_general_ci DEFAULT NULL',
      'tva' => 'text COLLATE utf8mb4_general_ci NOT NULL COMMENT \'JSON figé au moment de la commande\'',
      'transport_title' => 'varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\'',
      'transport_price' => 'decimal(10,2) NOT NULL DEFAULT \'0.00\'',
      'promo_code' => 'varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\'',
      'promo_type' => 'varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\'',
      'promo_price' => 'decimal(10,2) NOT NULL DEFAULT \'0.00\'',
      'promo_description' => 'varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\'',
      'client_message' => 'text COLLATE utf8mb4_general_ci NOT NULL',
      'gift_package' => 'tinyint(1) NOT NULL DEFAULT \'0\'',
    ),
  ),
  'sb_store_payment' => 
  array (
    'module' => 'store',
    'definition' => '`id` int NOT NULL AUTO_INCREMENT, `plugin_id` varchar(50) COLLATE utf8mb4_general_ci NOT NULL, `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL, `active` tinyint(1) NOT NULL DEFAULT \'0\', `production` tinyint(1) NOT NULL DEFAULT \'0\', `sort` int NOT NULL DEFAULT \'0\', `config` text COLLATE utf8mb4_general_ci NOT NULL COMMENT \'JSON clés API\', PRIMARY KEY (`id`), KEY `plugin_id` (`plugin_id`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci',
    'columns' => 
    array (
      'id' => 'int NOT NULL AUTO_INCREMENT',
      'plugin_id' => 'varchar(50) COLLATE utf8mb4_general_ci NOT NULL',
      'title' => 'varchar(255) COLLATE utf8mb4_general_ci NOT NULL',
      'active' => 'tinyint(1) NOT NULL DEFAULT \'0\'',
      'production' => 'tinyint(1) NOT NULL DEFAULT \'0\'',
      'sort' => 'int NOT NULL DEFAULT \'0\'',
      'config' => 'text COLLATE utf8mb4_general_ci NOT NULL COMMENT \'JSON clés API\'',
    ),
  ),
  'sb_store_product' => 
  array (
    'module' => 'store',
    'definition' => '`id` int NOT NULL AUTO_INCREMENT, `catid` int NOT NULL DEFAULT \'0\', `reference` varchar(100) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\', `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL, `description` text COLLATE utf8mb4_general_ci NOT NULL, `description_short` text COLLATE utf8mb4_general_ci NOT NULL, `custom` text COLLATE utf8mb4_general_ci NOT NULL, `price` decimal(10,2) NOT NULL DEFAULT \'0.00\', `priceht` decimal(10,2) NOT NULL DEFAULT \'0.00\', `tva_assujetti` tinyint(1) NOT NULL DEFAULT \'1\' COMMENT \'Certaines formes de societe (franchise en base) en sont dispensees\', `tva` text COLLATE utf8mb4_general_ci NOT NULL COMMENT \'JSON: tva1/tva2/tva3 {libelle,nom,taux,montant_ht,compte}\', `photo` varchar(200) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\', `photos` text COLLATE utf8mb4_general_ci NOT NULL COMMENT \'JSON galerie\', `phys_visuals` text COLLATE utf8mb4_general_ci NOT NULL COMMENT \'JSON visuels version physique\', `poids` decimal(10,3) NOT NULL DEFAULT \'0.000\', `allow_physical` tinyint(1) NOT NULL DEFAULT \'1\', `allow_dematerialise` tinyint(1) NOT NULL DEFAULT \'0\', `digital_delivery_plugin` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\', `active` tinyint(1) NOT NULL DEFAULT \'1\', `sort` int NOT NULL DEFAULT \'0\', PRIMARY KEY (`id`), KEY `catid` (`catid`), KEY `reference` (`reference`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci',
    'columns' => 
    array (
      'id' => 'int NOT NULL AUTO_INCREMENT',
      'catid' => 'int NOT NULL DEFAULT \'0\'',
      'reference' => 'varchar(100) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\'',
      'title' => 'varchar(255) COLLATE utf8mb4_general_ci NOT NULL',
      'description' => 'text COLLATE utf8mb4_general_ci NOT NULL',
      'description_short' => 'text COLLATE utf8mb4_general_ci NOT NULL',
      'custom' => 'text COLLATE utf8mb4_general_ci NOT NULL',
      'price' => 'decimal(10,2) NOT NULL DEFAULT \'0.00\'',
      'priceht' => 'decimal(10,2) NOT NULL DEFAULT \'0.00\'',
      'tva_assujetti' => 'tinyint(1) NOT NULL DEFAULT \'1\' COMMENT \'Certaines formes de societe (franchise en base) en sont dispensees\'',
      'tva' => 'text COLLATE utf8mb4_general_ci NOT NULL COMMENT \'JSON: tva1/tva2/tva3 {libelle,nom,taux,montant_ht,compte}\'',
      'photo' => 'varchar(200) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\'',
      'photos' => 'text COLLATE utf8mb4_general_ci NOT NULL COMMENT \'JSON galerie\'',
      'phys_visuals' => 'text COLLATE utf8mb4_general_ci NOT NULL COMMENT \'JSON visuels version physique\'',
      'poids' => 'decimal(10,3) NOT NULL DEFAULT \'0.000\'',
      'allow_physical' => 'tinyint(1) NOT NULL DEFAULT \'1\'',
      'allow_dematerialise' => 'tinyint(1) NOT NULL DEFAULT \'0\'',
      'digital_delivery_plugin' => 'varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\'',
      'active' => 'tinyint(1) NOT NULL DEFAULT \'1\'',
      'sort' => 'int NOT NULL DEFAULT \'0\'',
    ),
  ),
  'sb_store_transport' => 
  array (
    'module' => 'store',
    'definition' => '`id` int NOT NULL AUTO_INCREMENT, `plugin_id` varchar(50) COLLATE utf8mb4_general_ci NOT NULL, `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL, `active` tinyint(1) NOT NULL DEFAULT \'1\', `sort` int NOT NULL DEFAULT \'0\', `config` text COLLATE utf8mb4_general_ci NOT NULL COMMENT \'JSON propre au plugin\', PRIMARY KEY (`id`), KEY `plugin_id` (`plugin_id`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci',
    'columns' => 
    array (
      'id' => 'int NOT NULL AUTO_INCREMENT',
      'plugin_id' => 'varchar(50) COLLATE utf8mb4_general_ci NOT NULL',
      'title' => 'varchar(255) COLLATE utf8mb4_general_ci NOT NULL',
      'active' => 'tinyint(1) NOT NULL DEFAULT \'1\'',
      'sort' => 'int NOT NULL DEFAULT \'0\'',
      'config' => 'text COLLATE utf8mb4_general_ci NOT NULL COMMENT \'JSON propre au plugin\'',
    ),
  ),
  'sb_store_unique' => 
  array (
    'module' => 'store',
    'definition' => '`id` int NOT NULL AUTO_INCREMENT, `order_detail_id` int NOT NULL, `pid` int NOT NULL, `code` varchar(50) COLLATE utf8mb4_general_ci NOT NULL, `file_path` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\', `status` enum(\'valide\',\'utilise\',\'expire\') COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'valide\', `created_at` int NOT NULL, `used_at` int DEFAULT NULL, `expires_at` int DEFAULT NULL, PRIMARY KEY (`id`), UNIQUE KEY `code` (`code`), KEY `order_detail_id` (`order_detail_id`), KEY `pid` (`pid`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci',
    'columns' => 
    array (
      'id' => 'int NOT NULL AUTO_INCREMENT',
      'order_detail_id' => 'int NOT NULL',
      'pid' => 'int NOT NULL',
      'code' => 'varchar(50) COLLATE utf8mb4_general_ci NOT NULL',
      'file_path' => 'varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'\'',
      'status' => 'enum(\'valide\',\'utilise\',\'expire\') COLLATE utf8mb4_general_ci NOT NULL DEFAULT \'valide\'',
      'created_at' => 'int NOT NULL',
      'used_at' => 'int DEFAULT NULL',
      'expires_at' => 'int DEFAULT NULL',
    ),
  ),
  'sb_tabbs' => 
  array (
    'module' => 'tabbs',
    'definition' => '`id` int NOT NULL AUTO_INCREMENT, `name` varchar(100) NOT NULL, `active` tinyint NOT NULL COMMENT \'0: inactive, 1: active\', PRIMARY KEY (`id`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8',
    'columns' => 
    array (
      'id' => 'int NOT NULL AUTO_INCREMENT',
      'name' => 'varchar(100) NOT NULL',
      'active' => 'tinyint NOT NULL COMMENT \'0: inactive, 1: active\'',
    ),
  ),
  'sb_tabbs_tab' => 
  array (
    'module' => 'tabbs',
    'definition' => '`id` int NOT NULL AUTO_INCREMENT, `tid` int NOT NULL COMMENT \'TABBS id\', `title` text NOT NULL, `content` text NOT NULL, `active` tinyint NOT NULL COMMENT \'0: inactive, 1: active\', `sort` int NOT NULL, PRIMARY KEY (`id`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8',
    'columns' => 
    array (
      'id' => 'int NOT NULL AUTO_INCREMENT',
      'tid' => 'int NOT NULL COMMENT \'TABBS id\'',
      'title' => 'text NOT NULL',
      'content' => 'text NOT NULL',
      'active' => 'tinyint NOT NULL COMMENT \'0: inactive, 1: active\'',
      'sort' => 'int NOT NULL',
    ),
  ),
  'sb_table' => 
  array (
    'module' => 'table',
    'definition' => '`id` int NOT NULL AUTO_INCREMENT, `name` varchar(100) NOT NULL, `type` varchar(10) NOT NULL COMMENT \'option1, option2, ...\', `active` tinyint NOT NULL COMMENT \'0: inactive, 1: active\', PRIMARY KEY (`id`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8',
    'columns' => 
    array (
      'id' => 'int NOT NULL AUTO_INCREMENT',
      'name' => 'varchar(100) NOT NULL',
      'type' => 'varchar(10) NOT NULL COMMENT \'option1, option2, ...\'',
      'active' => 'tinyint NOT NULL COMMENT \'0: inactive, 1: active\'',
    ),
  ),
  'sb_table_datas' => 
  array (
    'module' => 'table',
    'definition' => '`id` int NOT NULL AUTO_INCREMENT, `tid` int NOT NULL COMMENT \'Table ID\', `content` text NOT NULL COMMENT \'Contenus\', `sort` int NOT NULL DEFAULT \'0\', PRIMARY KEY (`id`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8',
    'columns' => 
    array (
      'id' => 'int NOT NULL AUTO_INCREMENT',
      'tid' => 'int NOT NULL COMMENT \'Table ID\'',
      'content' => 'text NOT NULL COMMENT \'Contenus\'',
      'sort' => 'int NOT NULL DEFAULT \'0\'',
    ),
  ),
  'sb_table_structure' => 
  array (
    'module' => 'table',
    'definition' => '`id` int NOT NULL AUTO_INCREMENT, `tid` int NOT NULL COMMENT \'Table ID\', `title` varchar(255) NOT NULL, `field_type` varchar(50) NOT NULL COMMENT \'photo,text,date,textarea,textareahtml,link...\', `field_target` varchar(50) NOT NULL, `active` tinyint NOT NULL COMMENT \'0: inactive, 1: active\', `sort` int NOT NULL DEFAULT \'0\', PRIMARY KEY (`id`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8',
    'columns' => 
    array (
      'id' => 'int NOT NULL AUTO_INCREMENT',
      'tid' => 'int NOT NULL COMMENT \'Table ID\'',
      'title' => 'varchar(255) NOT NULL',
      'field_type' => 'varchar(50) NOT NULL COMMENT \'photo,text,date,textarea,textareahtml,link...\'',
      'field_target' => 'varchar(50) NOT NULL',
      'active' => 'tinyint NOT NULL COMMENT \'0: inactive, 1: active\'',
      'sort' => 'int NOT NULL DEFAULT \'0\'',
    ),
  ),
  'sb_users' => 
  array (
    'module' => '',
    'definition' => '`id` int NOT NULL AUTO_INCREMENT, `username` varchar(30) NOT NULL, `password` varchar(128) NOT NULL, `email` varchar(100) NOT NULL, `logintime` int NOT NULL, `lastlogin` int NOT NULL, `active` tinyint(1) NOT NULL DEFAULT \'0\', `activekey` varchar(15) NOT NULL DEFAULT \'0\', `resetkey` varchar(15) NOT NULL DEFAULT \'0\', `menu` text NOT NULL COMMENT \'Liste du menu inaccessible ( separe par des | )\', `groupe` varchar(50) NOT NULL, `prenom` varchar(50) NOT NULL DEFAULT \'\', `nom` varchar(50) NOT NULL DEFAULT \'\', `telephone` varchar(30) NOT NULL DEFAULT \'\', `fonction` varchar(100) NOT NULL DEFAULT \'\', `profession` varchar(100) NOT NULL DEFAULT \'\', `centres_interet` text NOT NULL, `infos_complementaires` text NOT NULL, `avatar` varchar(255) NOT NULL DEFAULT \'\' COMMENT \'Nom de fichier dans upload/avatars/ (racine du site) - vide = repli sur Gravatar\', PRIMARY KEY (`id`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT=\'Table Users\'',
    'columns' => 
    array (
      'id' => 'int NOT NULL AUTO_INCREMENT',
      'username' => 'varchar(30) NOT NULL',
      'password' => 'varchar(128) NOT NULL',
      'email' => 'varchar(100) NOT NULL',
      'logintime' => 'int NOT NULL',
      'lastlogin' => 'int NOT NULL',
      'active' => 'tinyint(1) NOT NULL DEFAULT \'0\'',
      'activekey' => 'varchar(15) NOT NULL DEFAULT \'0\'',
      'resetkey' => 'varchar(15) NOT NULL DEFAULT \'0\'',
      'menu' => 'text NOT NULL COMMENT \'Liste du menu inaccessible ( separe par des | )\'',
      'groupe' => 'varchar(50) NOT NULL',
      'prenom' => 'varchar(50) NOT NULL DEFAULT \'\'',
      'nom' => 'varchar(50) NOT NULL DEFAULT \'\'',
      'telephone' => 'varchar(30) NOT NULL DEFAULT \'\'',
      'fonction' => 'varchar(100) NOT NULL DEFAULT \'\'',
      'profession' => 'varchar(100) NOT NULL DEFAULT \'\'',
      'centres_interet' => 'text NOT NULL',
      'infos_complementaires' => 'text NOT NULL',
      'avatar' => 'varchar(255) NOT NULL DEFAULT \'\' COMMENT \'Nom de fichier dans upload/avatars/ (racine du site) - vide = repli sur Gravatar\'',
    ),
  ),
  'sb_users_remember_tokens' => 
  array (
    'module' => '',
    'definition' => '`id` int NOT NULL AUTO_INCREMENT, `user_id` int NOT NULL, `selector` varchar(24) COLLATE utf8mb4_general_ci NOT NULL, `validator_hash` varchar(64) COLLATE utf8mb4_general_ci NOT NULL, `expires` int NOT NULL, PRIMARY KEY (`id`), UNIQUE KEY `selector` (`selector`), KEY `user_id` (`user_id`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci',
    'columns' => 
    array (
      'id' => 'int NOT NULL AUTO_INCREMENT',
      'user_id' => 'int NOT NULL',
      'selector' => 'varchar(24) COLLATE utf8mb4_general_ci NOT NULL',
      'validator_hash' => 'varchar(64) COLLATE utf8mb4_general_ci NOT NULL',
      'expires' => 'int NOT NULL',
    ),
  ),
  'sb_users_rights' => 
  array (
    'module' => '',
    'definition' => '`id` int NOT NULL AUTO_INCREMENT, `user_id` int NOT NULL, `module` varchar(50) COLLATE utf8mb4_general_ci NOT NULL, `can_view` tinyint(1) NOT NULL DEFAULT \'1\', `can_add` tinyint(1) NOT NULL DEFAULT \'1\', `can_edit` tinyint(1) NOT NULL DEFAULT \'1\', `can_delete` tinyint(1) NOT NULL DEFAULT \'1\', PRIMARY KEY (`id`), UNIQUE KEY `user_module` (`user_id`,`module`)',
    'options' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT=\'Droits granulaires par utilisateur et par module (voir/ajouter/modifier/supprimer) - absence de ligne = accès complet\'',
    'columns' => 
    array (
      'id' => 'int NOT NULL AUTO_INCREMENT',
      'user_id' => 'int NOT NULL',
      'module' => 'varchar(50) COLLATE utf8mb4_general_ci NOT NULL',
      'can_view' => 'tinyint(1) NOT NULL DEFAULT \'1\'',
      'can_add' => 'tinyint(1) NOT NULL DEFAULT \'1\'',
      'can_edit' => 'tinyint(1) NOT NULL DEFAULT \'1\'',
      'can_delete' => 'tinyint(1) NOT NULL DEFAULT \'1\'',
    ),
  ),
);

return array(
	'description' => 'rattrapage du schéma des bases anciennes (tables et colonnes du core)',
	'reversible'  => true,

	'up' => function (SbMigration $m) use ($sb_schema_443) {
		// Type actuel d'une colonne (connexion de la mise à jour, si disponible)
		$db  = function_exists('sbUpdDb') ? sbUpdDb() : null;
		$col = function ($table, $column) use ($db, $m) {
			if (!($db instanceof mysqli)) return null;
			$r = $db->query("SELECT COLUMN_TYPE, IS_NULLABLE FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '"
				. $db->real_escape_string($m->table($table)) . "' AND COLUMN_NAME = '" . $db->real_escape_string($column) . "'");
			return $r ? $r->fetch_assoc() : null;
		};
		// Modules inscrits comme installés (sb_modules, créée plus haut si absente)
		$installed = array();
		if ($db instanceof mysqli && $m->tableExists('sb_modules')) {
			$r = $db->query('SELECT name FROM `' . $m->table('sb_modules') . '`');
			while ($r && ($row = $r->fetch_row())) $installed[$row[0]] = true;
		}
		foreach ($sb_schema_443 as $table => $t) {
			if (!$m->tableExists($table)) {
				if ($t['module'] === '' || isset($installed[$t['module']])) $m->createTable($table, $t['definition'], $t['options']);
				continue;
			}
			$prev = null;
			foreach ($t['columns'] as $column => $def) {
				if (!$m->columnExists($table, $column)) {
					if (stripos($def, 'AUTO_INCREMENT') === false) $m->addColumn($table, $column, $def, $prev);
				} elseif (preg_match('/^(var)?char\((\d+)\)/i', $def, $want) && ($cur = $col($table, $column))
					&& preg_match('/^(var)?char\((\d+)\)/i', $cur['COLUMN_TYPE'], $have) && (int) $have[2] < (int) $want[2]
					&& ($cur['IS_NULLABLE'] === 'NO' || stripos($def, 'NOT NULL') === false)) {
					$m->modifyColumn($table, $column, $def);
				}
				$prev = $column;
			}
		}
		// Page de maintenance : bandeau « Fork me on GitHub » (affiché par défaut)
		$m->addConfig('coming-soon-github', '1');
	},

	'down' => function (SbMigration $m) {
	},
);
