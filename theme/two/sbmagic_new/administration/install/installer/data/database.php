<?php

$SQL[] = "DROP TABLE IF EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_sandbox`";
$SQL[] = "CREATE TABLE IF NOT EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_sandbox` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `sire` varchar(255) NOT NULL,
  `sire_dam` varchar(255) NOT NULL,
  `dob` smallint(4) NOT NULL COMMENT 'Année de naissance',
  `country` varchar(200) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `video` varchar(255) NOT NULL,
  `pedigree` varchar(255) NOT NULL,
  `comment_mare` text NOT NULL,
  `comment_prod` text NOT NULL,
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `sort` int(11) DEFAULT NULL COMMENT 'Tri des juments',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COMMENT='Table SANDBOX'";

$SQL[] = "INSERT INTO `" . _AM_CREATE_DB_PREFIX . "sb_sandbox` VALUES ('1', 'SOUTH SISTER', 'KENDOR', '', '2010', 'FRANCE', 'jument-test2-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Comm&eacute;nta&icirc;re sur la j&ucirc;ment', 'Commentaire sur la pr&ocirc;d', '1', '0'), 
('2', 'BALDER PHENIX', 'PRESENTING', '', '2010', 'FRANCE', 'jument-test1-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire Jument', 'Commentaire Production', '1', '0'), 
('3', 'SAFFARONA', 'BARATHEA', '', '2007', 'FRANCE', 'jument-test2-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire SAFFARONA', 'Commentaire Production SAFFARONA', '1', '0'), 
('4', 'VALIDORA', 'KENDOR', '', '2011', 'FRANCE', 'jument-test1-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire VALIDORA', 'Commentaire Production VALIDORA', '1', '0'), 
('5', 'STUNNING', 'SLEW O&#039;GOLD', '', '1998', 'FRANCE', 'jument-test2-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire STUNNING', 'Commentaire Production STUNNING', '1', '0'), 
('6', 'TUNIS', 'WINGED LOVE', '', '2007', 'FRANCE', 'jument-test1-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire TUNIS', 'Commentaire Production TUNIS', '1', '0'), 
('7', 'ITASCA', 'UNFUWAIN', '', '2009', 'FRANCE', 'jument-test2-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire ITASCA', 'Commentaire Production ITASCA', '1', '0'), 
('8', 'UDANA', 'ASHKALANI', '', '2010', 'FRANCE', 'jument-test2-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire UDANA', 'Commentaire Production UDANA', '1', '0'), 
('9', 'QUATRE OR', 'CUPIDON', '', '2004', 'FRANCE', 'jument-test1-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire QUATRE OR', 'Commentaire Production QUATRE OR', '1', '0'), 
('10', 'ACENTELA', 'GALILEO', '', '2009', 'FRANCE', 'jument-test1-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire ACENTELA', 'Commentaire Production ACENTELA', '1', '0'), 
('11', 'SIENNA MAY', 'HIGHEST HONOR', '', '2006', 'FRANCE', 'jument-test2-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire SIENNA MAY', 'Commentaire Production SIENNA MAY', '1', '0'), 
('12', 'YERBA SOLDADO', 'MACHIAVELLIAN', '', '2010', 'FRANCE', 'jument-test1-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire YERBA SOLDADO', 'Commentaire Production YERBA SOLDADO', '1', '0'), 
('13', 'BECOMES YOU', 'LOMITAS', '', '2006', 'FRANCE', 'jument-test2-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire BECOMES YOU', 'Commentaire Production BECOMES YOU', '1', '0'), 
('14', 'SELVA REAL', 'PIVOTAL', '', '2011', 'FRANCE', 'jument-test1-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire SELVA REAL', 'Commentaire Production SELVA REAL', '1', '0'), 
('15', 'PARCIMONIE', 'DISTANT RELATIVE', '', '2007', 'FRANCE', 'jument-test2-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire PARCIMONIE', 'Commentaire Production PARCIMONIE', '1', '0'), 
('16', 'JOLIE LAIDE', 'MACHIAVELLIAN', '', '2007', 'FRANCE', 'jument-test2-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire JOLIE LAIDE', 'Commentaire Production JOLIE LAIDE', '1', '0'), 
('17', 'EASTER ROSE', 'AJRAAS', '', '2010', 'FRANCE', 'jument-test1-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire EASTER ROSE', 'Commentaire Production EASTER ROSE', '1', '0'), 
('18', 'FINEST CAPE', 'NOMBRE PREMIER', '', '2006', 'FRANCE', 'jument-test2-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire FINEST CAPE', 'Commentaire Production FINEST CAPE', '1', '0'), 
('19', 'EPATHA', 'AKARAD', '', '2003', 'FRANCE', 'jument-test2-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire EPATHA', 'Commentaire Production EPATHA', '1', '0'), 
('20', 'THET VIVA', 'APRIL NIGHT', '', '2005', 'FRANCE', 'jument-test2-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire THET VIVA', 'Commentaire Production THET VIVA', '1', '0'), 
('21', 'AL WASIL', 'KINGMAMBO', '', '2012', 'FRANCE', 'jument-test1-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire AL WASIL', 'Commentaire Production AL WASIL', '1', '0'), 
('22', 'CHANSONNETTE', 'GENEROUS', '', '2005', 'FRANCE', 'jument-test2-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire CHANSONNETTE', 'Commentaire Production CHANSONNETTE', '1', '0'), 
('23', 'GRACIOULSY', 'ORPEN', '', '2011', 'FRANCE', 'jument-test2-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire GRACIOULSY', 'Commentaire Production GRACIOULSY', '1', '0'), 
('24', 'HIDDEN COVE', 'ARAZI', '', '2010', 'FRANCE', 'jument-test1-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire HIDDEN COVE', 'Commentaire Production HIDDEN COVE', '1', '0'), 
('25', 'IFRANNE', '', '', '0', 'FRANCE', 'jument-test2-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire IFRANNE', 'Commentaire Production IFRANNE', '1', '0'), 
('26', 'LOVE ALOFT', 'SADLER&#039;S WELL', '', '2012', 'FRANCE', 'jument-test2-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire LOVE ALOFT', 'Commentaire Production LOVE ALOFT', '1', '0'), 
('27', 'PARCELLE PERDUE', 'ZAFONIC', '', '2006', 'FRANCE', 'jument-test1-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire PARCELLE PERDUE', 'Commentaire Production PARCELLE PERDUE', '1', '0'), 
('28', 'PYGMALION', 'IRISH RIVER', '', '1998', 'FRANCE', 'jument-test2-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire PYGMALION', 'Commentaire Production PYGMALION', '1', '0'), 
('29', 'RIO AMABLE', 'LINAMIX', '', '2009', 'FRANCE', 'jument-test1-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire RIO AMABLE', 'Commentaire Production RIO AMABLE', '1', '0'), 
('30', 'THAT&#039;S NELLIE', 'ROBIN DES CHAMPS', '', '2011', 'FRANCE', 'jument-test1-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire THAT&#039;S NELLIE', 'Commentaire Production THAT&#039;S NELLIE', '1', '0'), 
('31', 'TORECILLAS', 'DANSILI', '', '2012', 'FRANCE', 'jument-test2-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire TORECILLAS', 'Commentaire Production TORECILLAS', '1', '0'), 
('32', 'UNA VIVA', 'APRIL NIGHT', '', '2008', 'FRANCE', 'jument-test2-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'commentaire UNA VIVA', 'Commentaire Production UNA VIVA', '1', '0'), 
('33', 'VADARIYA', 'TREMPOLINO', '', '2012', 'FRANCE', 'jument-test2-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire VADARIYA', 'Commentaire Production VADARIYA', '1', '0')";


$SQL[] = "DROP TABLE IF EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_logaccess`";
$SQL[] = "CREATE TABLE IF NOT EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_logaccess` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `logaccess_type` varchar(10) NOT NULL,
  `logaccess_date` int(10) NOT NULL,
  `logaccess_user` varchar(20) NOT NULL,
  `logaccess_event` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table Activity log'";


$SQL[] = "DROP TABLE IF EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_attempts`";
$SQL[] = "CREATE TABLE IF NOT EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_attempts` (
  `ip` varchar(15) NOT NULL,
  `count` int(11) NOT NULL,
  `expiredate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table Attempts'";


$SQL[] = "DROP TABLE IF EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_sessions`";
$SQL[] = "CREATE TABLE IF NOT EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `hash` varchar(32) NOT NULL,
  `expiredate` datetime NOT NULL,
  `ip` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table Session'";


$SQL[] = "DROP TABLE IF EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_users`";
$SQL[] = "CREATE TABLE IF NOT EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `password` varchar(128) NOT NULL,
  `email` varchar(100) NOT NULL,
  `logintime` int(11) NOT NULL,
  `lastlogin` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `activekey` varchar(15) NOT NULL DEFAULT '0',
  `resetkey` varchar(15) NOT NULL DEFAULT '0',
  `menu` text NOT NULL COMMENT 'Liste du menu inaccessible ( séparé par des | )',
  `groupe` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table Users'";

$SQL[] = "INSERT INTO `" . _AM_CREATE_DB_PREFIX . "sb_users` (`id`, `username`, `password`, `email`, `logintime`, `lastlogin`, `active`, `activekey`, `resetkey`) VALUES
(1, 'admin', '99pxlOJyAWx1UswLgblCziBfdWeREDCTYlWP2RWWkAc=', 'admin-reply@votresite.com', 0, 0, 1, '0', '0');";


$SQL[] = "DROP TABLE IF EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_blocs`";
$SQL[] = "CREATE TABLE IF NOT EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_blocs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pages_id` varchar(255) NOT NULL COMMENT 'Pages IDs (separate by | )',
  `modules_id` varchar(255) NOT NULL COMMENT 'Module dirnames (separate by | )',
  `name` varchar(100) NOT NULL COMMENT 'Nom du bloc',
  `title` text NOT NULL COMMENT 'titre du bloc (côté client)',
  `content` text NOT NULL COMMENT 'Contenu du bloc',
  `position` varchar(100) NOT NULL,
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `sort` int(11) NOT NULL COMMENT 'Tri des blocs',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Blocs associés aux pages'";


$SQL[] = "DROP TABLE IF EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_blocs_sort`";
$SQL[] = "CREATE TABLE IF NOT EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_blocs_sort` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bloc_id` int(11) NOT NULL COMMENT 'ID des blocs',
  `page_id` int(11) NOT NULL COMMENT 'ID des pages',
  `module_id` varchar(50) NOT NULL COMMENT 'Nom du module (nom du répertoire)',
  `sort` int(11) NOT NULL COMMENT 'Tri des blocs par page',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";


$SQL[] = "DROP TABLE IF EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_config`";
$SQL[] = "CREATE TABLE IF NOT EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `config` varchar(50) NOT NULL COMMENT 'Nom de la configuration',
  `content` text NOT NULL COMMENT 'Valeur de la configuration',
  PRIMARY KEY (`id`),
  UNIQUE KEY `config` (`config`),
  KEY `config_2` (`config`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8";

$SQL[] = "INSERT INTO `" . _AM_CREATE_DB_PREFIX . "sb_config` (`id`, `config`, `content`) VALUES
(1, 'css', '.fright {float: right;}\r\n.fcenter {float: center;}\r\n.fleft {float: left;}\r\n.aright {text-align: right;}\r\n.acenter {text-align: center;}\r\n.aleft {text-align: left;}\r\n.dnone {display: none !important;}'),
(2, 'javascript', 'jQuery(document).ready(function() {\r\n	// Recherche cach&eacute;e\r\n	jQuery(&#039;#votrediv&#039;).css(&#039;color&#039;,&#039;red&#039;);\r\n});'),
(3, 'header', ''),
(4, 'footer', '[fr]&amp;copy; [CS name=sbyear] &amp;bull; www.votresite.com &amp;bull; Cr&amp;eacute;&amp;eacute; &amp;amp; r&amp;eacute;alis&amp;eacute; par &lt;a href=&quot;//informatux.com&quot; target=&quot;_blank&quot;&gt;informatux.com&lt;/a&gt;[/fr]'),
(5, 'email_to', 'contact@votresite.fr'),
(6, 'email_publickey', ''),
(7, 'email_privatekey', ''),
(8, 'email_subject', 'Message de votre site'),
(9, 'coming-soon', '1'),
(10, 'coming-soon-url', 'comingsoon'),
(11, 'coming-soon-title', 'Ma belle entreprise'),
(12, 'coming-soon-title2', ''),
(13, 'coming-soon-text', ''),
(14, 'coming-soon-tel', '02 32 45 67 89'),
(15, 'coming-soon-address', 'Rue de la bourse, 75016 Paris'),
(16, 'coming-soon-email', 'info@ma-belle-entreprise.com'),
(17, 'coming-soon-facebook', ''),
(18, 'coming-soon-twitter', ''),
(19, 'coming-soon-youtube', ''),
(20, 'multilang', '0'),
(21, 'plugins', ''),
(22, 'fonts', ''),
(23, 'seo-keywords', ''),
(24, 'seo-description', ''),
(25, 'coming-soon-type', 'image'),
(26, 'coming-soon-image', ''),
(27, 'coming-soon-video', ''),
(28, 'coming-soon-dark', '0'),
(29, 'coming-soon-date', ''),
(30, 'coming-soon-google-plus', '#'),
(31, 'toolbarck', '0')";

$SQL[] = "DROP TABLE IF EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_contact`";
$SQL[] = "CREATE TABLE `" . _AM_CREATE_DB_PREFIX . "sb_contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `recipients` text NOT NULL COMMENT 'destinataires',
  `subject` text NOT NULL,
  `form` text NOT NULL,
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8";


$SQL[] = "DROP TABLE IF EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_country`";
$SQL[] = "CREATE TABLE IF NOT EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_country` (
  `country_iso` char(2) NOT NULL,
  `country_name` varchar(80) NOT NULL,
  `country_printable_name` varchar(80) NOT NULL,
  `country_iso3` char(3) DEFAULT NULL,
  `country_numcode` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`country_iso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";

$SQL[] = "INSERT INTO `" . _AM_CREATE_DB_PREFIX . "sb_country` (`country_iso`, `country_name`, `country_printable_name`, `country_iso3`, `country_numcode`) VALUES
('AF', 'AFGHANISTAN', 'Afghanistan', 'AFG', 4),
('AL', 'ALBANIA', 'Albania', 'ALB', 8),
('DZ', 'ALGERIA', 'Algeria', 'DZA', 12),
('AS', 'AMERICAN SAMOA', 'American Samoa', 'ASM', 16),
('AD', 'ANDORRA', 'Andorra', 'AND', 20),
('AO', 'ANGOLA', 'Angola', 'AGO', 24),
('AI', 'ANGUILLA', 'Anguilla', 'AIA', 660),
('AQ', 'ANTARCTICA', 'Antarctica', NULL, NULL),
('AG', 'ANTIGUA AND BARBUDA', 'Antigua and Barbuda', 'ATG', 28),
('AR', 'ARGENTINA', 'Argentina', 'ARG', 32),
('AM', 'ARMENIA', 'Armenia', 'ARM', 51),
('AW', 'ARUBA', 'Aruba', 'ABW', 533),
('AU', 'AUSTRALIA', 'Australia', 'AUS', 36),
('AT', 'AUSTRIA', 'Austria', 'AUT', 40),
('AZ', 'AZERBAIJAN', 'Azerbaijan', 'AZE', 31),
('BS', 'BAHAMAS', 'Bahamas', 'BHS', 44),
('BH', 'BAHRAIN', 'Bahrain', 'BHR', 48),
('BD', 'BANGLADESH', 'Bangladesh', 'BGD', 50),
('BB', 'BARBADOS', 'Barbados', 'BRB', 52),
('BY', 'BELARUS', 'Belarus', 'BLR', 112),
('BE', 'BELGIUM', 'Belgium', 'BEL', 56),
('BZ', 'BELIZE', 'Belize', 'BLZ', 84),
('BJ', 'BENIN', 'Benin', 'BEN', 204),
('BM', 'BERMUDA', 'Bermuda', 'BMU', 60),
('BT', 'BHUTAN', 'Bhutan', 'BTN', 64),
('BO', 'BOLIVIA', 'Bolivia', 'BOL', 68),
('BA', 'BOSNIA AND HERZEGOVINA', 'Bosnia and Herzegovina', 'BIH', 70),
('BW', 'BOTSWANA', 'Botswana', 'BWA', 72),
('BV', 'BOUVET ISLAND', 'Bouvet Island', NULL, NULL),
('BR', 'BRAZIL', 'Brazil', 'BRA', 76),
('IO', 'BRITISH INDIAN OCEAN TERRITORY', 'British Indian Ocean Territory', NULL, NULL),
('BN', 'BRUNEI DARUSSALAM', 'Brunei Darussalam', 'BRN', 96),
('BG', 'BULGARIA', 'Bulgaria', 'BGR', 100),
('BF', 'BURKINA FASO', 'Burkina Faso', 'BFA', 854),
('BI', 'BURUNDI', 'Burundi', 'BDI', 108),
('KH', 'CAMBODIA', 'Cambodia', 'KHM', 116),
('CM', 'CAMEROON', 'Cameroon', 'CMR', 120),
('CA', 'CANADA', 'Canada', 'CAN', 124),
('CV', 'CAPE VERDE', 'Cape Verde', 'CPV', 132),
('KY', 'CAYMAN ISLANDS', 'Cayman Islands', 'CYM', 136),
('CF', 'CENTRAL AFRICAN REPUBLIC', 'Central African Republic', 'CAF', 140),
('TD', 'CHAD', 'Chad', 'TCD', 148),
('CL', 'CHILE', 'Chile', 'CHL', 152),
('CN', 'CHINA', 'China', 'CHN', 156),
('CX', 'CHRISTMAS ISLAND', 'Christmas Island', NULL, NULL),
('CC', 'COCOS (KEELING) ISLANDS', 'Cocos (Keeling) Islands', NULL, NULL),
('CO', 'COLOMBIA', 'Colombia', 'COL', 170),
('KM', 'COMOROS', 'Comoros', 'COM', 174),
('CG', 'CONGO', 'Congo', 'COG', 178),
('CD', 'CONGO, THE DEMOCRATIC REPUBLIC OF THE', 'Congo, the Democratic Republic of the', 'COD', 180),
('CK', 'COOK ISLANDS', 'Cook Islands', 'COK', 184),
('CR', 'COSTA RICA', 'Costa Rica', 'CRI', 188),
('CI', 'COTE D''IVOIRE', 'Cote D''Ivoire', 'CIV', 384),
('HR', 'CROATIA', 'Croatia', 'HRV', 191),
('CU', 'CUBA', 'Cuba', 'CUB', 192),
('CY', 'CYPRUS', 'Cyprus', 'CYP', 196),
('CZ', 'CZECH REPUBLIC', 'Czech Republic', 'CZE', 203),
('DK', 'DENMARK', 'Denmark', 'DNK', 208),
('DJ', 'DJIBOUTI', 'Djibouti', 'DJI', 262),
('DM', 'DOMINICA', 'Dominica', 'DMA', 212),
('DO', 'DOMINICAN REPUBLIC', 'Dominican Republic', 'DOM', 214),
('EC', 'ECUADOR', 'Ecuador', 'ECU', 218),
('EG', 'EGYPT', 'Egypt', 'EGY', 818),
('SV', 'EL SALVADOR', 'El Salvador', 'SLV', 222),
('GQ', 'EQUATORIAL GUINEA', 'Equatorial Guinea', 'GNQ', 226),
('ER', 'ERITREA', 'Eritrea', 'ERI', 232),
('EE', 'ESTONIA', 'Estonia', 'EST', 233),
('ET', 'ETHIOPIA', 'Ethiopia', 'ETH', 231),
('FK', 'FALKLAND ISLANDS (MALVINAS)', 'Falkland Islands (Malvinas)', 'FLK', 238),
('FO', 'FAROE ISLANDS', 'Faroe Islands', 'FRO', 234),
('FJ', 'FIJI', 'Fiji', 'FJI', 242),
('FI', 'FINLAND', 'Finland', 'FIN', 246),
('FR', 'FRANCE', 'France', 'FRA', 250),
('GF', 'FRENCH GUIANA', 'French Guiana', 'GUF', 254),
('PF', 'FRENCH POLYNESIA', 'French Polynesia', 'PYF', 258),
('TF', 'FRENCH SOUTHERN TERRITORIES', 'French Southern Territories', NULL, NULL),
('GA', 'GABON', 'Gabon', 'GAB', 266),
('GM', 'GAMBIA', 'Gambia', 'GMB', 270),
('GE', 'GEORGIA', 'Georgia', 'GEO', 268),
('DE', 'GERMANY', 'Germany', 'DEU', 276),
('GH', 'GHANA', 'Ghana', 'GHA', 288),
('GI', 'GIBRALTAR', 'Gibraltar', 'GIB', 292),
('GR', 'GREECE', 'Greece', 'GRC', 300),
('GL', 'GREENLAND', 'Greenland', 'GRL', 304),
('GD', 'GRENADA', 'Grenada', 'GRD', 308),
('GP', 'GUADELOUPE', 'Guadeloupe', 'GLP', 312),
('GU', 'GUAM', 'Guam', 'GUM', 316),
('GT', 'GUATEMALA', 'Guatemala', 'GTM', 320),
('GN', 'GUINEA', 'Guinea', 'GIN', 324),
('GW', 'GUINEA-BISSAU', 'Guinea-Bissau', 'GNB', 624),
('GY', 'GUYANA', 'Guyana', 'GUY', 328),
('HT', 'HAITI', 'Haiti', 'HTI', 332),
('HM', 'HEARD ISLAND AND MCDONALD ISLANDS', 'Heard Island and Mcdonald Islands', NULL, NULL),
('VA', 'HOLY SEE (VATICAN CITY STATE)', 'Holy See (Vatican City State)', 'VAT', 336),
('HN', 'HONDURAS', 'Honduras', 'HND', 340),
('HK', 'HONG KONG', 'Hong Kong', 'HKG', 344),
('HU', 'HUNGARY', 'Hungary', 'HUN', 348),
('IS', 'ICELAND', 'Iceland', 'ISL', 352),
('IN', 'INDIA', 'India', 'IND', 356),
('ID', 'INDONESIA', 'Indonesia', 'IDN', 360),
('IR', 'IRAN, ISLAMIC REPUBLIC OF', 'Iran, Islamic Republic of', 'IRN', 364),
('IQ', 'IRAQ', 'Iraq', 'IRQ', 368),
('IE', 'IRELAND', 'Ireland', 'IRL', 372),
('IL', 'ISRAEL', 'Israel', 'ISR', 376),
('IT', 'ITALY', 'Italy', 'ITA', 380),
('JM', 'JAMAICA', 'Jamaica', 'JAM', 388),
('JP', 'JAPAN', 'Japan', 'JPN', 392),
('JO', 'JORDAN', 'Jordan', 'JOR', 400),
('KZ', 'KAZAKHSTAN', 'Kazakhstan', 'KAZ', 398),
('KE', 'KENYA', 'Kenya', 'KEN', 404),
('KI', 'KIRIBATI', 'Kiribati', 'KIR', 296),
('KP', 'KOREA, DEMOCRATIC PEOPLE''S REPUBLIC OF', 'Korea, Democratic People''s Republic of', 'PRK', 408),
('KR', 'KOREA, REPUBLIC OF', 'Korea, Republic of', 'KOR', 410),
('KW', 'KUWAIT', 'Kuwait', 'KWT', 414),
('KG', 'KYRGYZSTAN', 'Kyrgyzstan', 'KGZ', 417),
('LA', 'LAO PEOPLE''S DEMOCRATIC REPUBLIC', 'Lao People''s Democratic Republic', 'LAO', 418),
('LV', 'LATVIA', 'Latvia', 'LVA', 428),
('LB', 'LEBANON', 'Lebanon', 'LBN', 422),
('LS', 'LESOTHO', 'Lesotho', 'LSO', 426),
('LR', 'LIBERIA', 'Liberia', 'LBR', 430),
('LY', 'LIBYAN ARAB JAMAHIRIYA', 'Libyan Arab Jamahiriya', 'LBY', 434),
('LI', 'LIECHTENSTEIN', 'Liechtenstein', 'LIE', 438),
('LT', 'LITHUANIA', 'Lithuania', 'LTU', 440),
('LU', 'LUXEMBOURG', 'Luxembourg', 'LUX', 442),
('MO', 'MACAO', 'Macao', 'MAC', 446),
('MK', 'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF', 'Macedonia, the Former Yugoslav Republic of', 'MKD', 807),
('MG', 'MADAGASCAR', 'Madagascar', 'MDG', 450),
('MW', 'MALAWI', 'Malawi', 'MWI', 454),
('MY', 'MALAYSIA', 'Malaysia', 'MYS', 458),
('MV', 'MALDIVES', 'Maldives', 'MDV', 462),
('ML', 'MALI', 'Mali', 'MLI', 466),
('MT', 'MALTA', 'Malta', 'MLT', 470),
('MH', 'MARSHALL ISLANDS', 'Marshall Islands', 'MHL', 584),
('MQ', 'MARTINIQUE', 'Martinique', 'MTQ', 474),
('MR', 'MAURITANIA', 'Mauritania', 'MRT', 478),
('MU', 'MAURITIUS', 'Mauritius', 'MUS', 480),
('YT', 'MAYOTTE', 'Mayotte', NULL, NULL),
('MX', 'MEXICO', 'Mexico', 'MEX', 484),
('FM', 'MICRONESIA, FEDERATED STATES OF', 'Micronesia, Federated States of', 'FSM', 583),
('MD', 'MOLDOVA, REPUBLIC OF', 'Moldova, Republic of', 'MDA', 498),
('MC', 'MONACO', 'Monaco', 'MCO', 492),
('MN', 'MONGOLIA', 'Mongolia', 'MNG', 496),
('MS', 'MONTSERRAT', 'Montserrat', 'MSR', 500),
('MA', 'MOROCCO', 'Morocco', 'MAR', 504),
('MZ', 'MOZAMBIQUE', 'Mozambique', 'MOZ', 508),
('MM', 'MYANMAR', 'Myanmar', 'MMR', 104),
('NA', 'NAMIBIA', 'Namibia', 'NAM', 516),
('NR', 'NAURU', 'Nauru', 'NRU', 520),
('NP', 'NEPAL', 'Nepal', 'NPL', 524),
('NL', 'NETHERLANDS', 'Netherlands', 'NLD', 528),
('AN', 'NETHERLANDS ANTILLES', 'Netherlands Antilles', 'ANT', 530),
('NC', 'NEW CALEDONIA', 'New Caledonia', 'NCL', 540),
('NZ', 'NEW ZEALAND', 'New Zealand', 'NZL', 554),
('NI', 'NICARAGUA', 'Nicaragua', 'NIC', 558),
('NE', 'NIGER', 'Niger', 'NER', 562),
('NG', 'NIGERIA', 'Nigeria', 'NGA', 566),
('NU', 'NIUE', 'Niue', 'NIU', 570),
('NF', 'NORFOLK ISLAND', 'Norfolk Island', 'NFK', 574),
('MP', 'NORTHERN MARIANA ISLANDS', 'Northern Mariana Islands', 'MNP', 580),
('NO', 'NORWAY', 'Norway', 'NOR', 578),
('OM', 'OMAN', 'Oman', 'OMN', 512),
('PK', 'PAKISTAN', 'Pakistan', 'PAK', 586),
('PW', 'PALAU', 'Palau', 'PLW', 585),
('PS', 'PALESTINIAN TERRITORY, OCCUPIED', 'Palestinian Territory, Occupied', NULL, NULL),
('PA', 'PANAMA', 'Panama', 'PAN', 591),
('PG', 'PAPUA NEW GUINEA', 'Papua New Guinea', 'PNG', 598),
('PY', 'PARAGUAY', 'Paraguay', 'PRY', 600),
('PE', 'PERU', 'Peru', 'PER', 604),
('PH', 'PHILIPPINES', 'Philippines', 'PHL', 608),
('PN', 'PITCAIRN', 'Pitcairn', 'PCN', 612),
('PL', 'POLAND', 'Poland', 'POL', 616),
('PT', 'PORTUGAL', 'Portugal', 'PRT', 620),
('PR', 'PUERTO RICO', 'Puerto Rico', 'PRI', 630),
('QA', 'QATAR', 'Qatar', 'QAT', 634),
('RE', 'REUNION', 'Reunion', 'REU', 638),
('RO', 'ROMANIA', 'Romania', 'ROM', 642),
('RU', 'RUSSIAN FEDERATION', 'Russian Federation', 'RUS', 643),
('RW', 'RWANDA', 'Rwanda', 'RWA', 646),
('SH', 'SAINT HELENA', 'Saint Helena', 'SHN', 654),
('KN', 'SAINT KITTS AND NEVIS', 'Saint Kitts and Nevis', 'KNA', 659),
('LC', 'SAINT LUCIA', 'Saint Lucia', 'LCA', 662),
('PM', 'SAINT PIERRE AND MIQUELON', 'Saint Pierre and Miquelon', 'SPM', 666),
('VC', 'SAINT VINCENT AND THE GRENADINES', 'Saint Vincent and the Grenadines', 'VCT', 670),
('WS', 'SAMOA', 'Samoa', 'WSM', 882),
('SM', 'SAN MARINO', 'San Marino', 'SMR', 674),
('ST', 'SAO TOME AND PRINCIPE', 'Sao Tome and Principe', 'STP', 678),
('SA', 'SAUDI ARABIA', 'Saudi Arabia', 'SAU', 682),
('SN', 'SENEGAL', 'Senegal', 'SEN', 686),
('CS', 'SERBIA AND MONTENEGRO', 'Serbia and Montenegro', NULL, NULL),
('SC', 'SEYCHELLES', 'Seychelles', 'SYC', 690),
('SL', 'SIERRA LEONE', 'Sierra Leone', 'SLE', 694),
('SG', 'SINGAPORE', 'Singapore', 'SGP', 702),
('SK', 'SLOVAKIA', 'Slovakia', 'SVK', 703),
('SI', 'SLOVENIA', 'Slovenia', 'SVN', 705),
('SB', 'SOLOMON ISLANDS', 'Solomon Islands', 'SLB', 90),
('SO', 'SOMALIA', 'Somalia', 'SOM', 706),
('ZA', 'SOUTH AFRICA', 'South Africa', 'ZAF', 710),
('GS', 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS', 'South Georgia and the South Sandwich Islands', NULL, NULL),
('ES', 'SPAIN', 'Spain', 'ESP', 724),
('LK', 'SRI LANKA', 'Sri Lanka', 'LKA', 144),
('SD', 'SUDAN', 'Sudan', 'SDN', 736),
('SR', 'SURINAME', 'Suriname', 'SUR', 740),
('SJ', 'SVALBARD AND JAN MAYEN', 'Svalbard and Jan Mayen', 'SJM', 744),
('SZ', 'SWAZILAND', 'Swaziland', 'SWZ', 748),
('SE', 'SWEDEN', 'Sweden', 'SWE', 752),
('CH', 'SWITZERLAND', 'Switzerland', 'CHE', 756),
('SY', 'SYRIAN ARAB REPUBLIC', 'Syrian Arab Republic', 'SYR', 760),
('TW', 'TAIWAN, PROVINCE OF CHINA', 'Taiwan, Province of China', 'TWN', 158),
('TJ', 'TAJIKISTAN', 'Tajikistan', 'TJK', 762),
('TZ', 'TANZANIA, UNITED REPUBLIC OF', 'Tanzania, United Republic of', 'TZA', 834),
('TH', 'THAILAND', 'Thailand', 'THA', 764),
('TL', 'TIMOR-LESTE', 'Timor-Leste', NULL, NULL),
('TG', 'TOGO', 'Togo', 'TGO', 768),
('TK', 'TOKELAU', 'Tokelau', 'TKL', 772),
('TO', 'TONGA', 'Tonga', 'TON', 776),
('TT', 'TRINIDAD AND TOBAGO', 'Trinidad and Tobago', 'TTO', 780),
('TN', 'TUNISIA', 'Tunisia', 'TUN', 788),
('TR', 'TURKEY', 'Turkey', 'TUR', 792),
('TM', 'TURKMENISTAN', 'Turkmenistan', 'TKM', 795),
('TC', 'TURKS AND CAICOS ISLANDS', 'Turks and Caicos Islands', 'TCA', 796),
('TV', 'TUVALU', 'Tuvalu', 'TUV', 798),
('UG', 'UGANDA', 'Uganda', 'UGA', 800),
('UA', 'UKRAINE', 'Ukraine', 'UKR', 804),
('AE', 'UNITED ARAB EMIRATES', 'United Arab Emirates', 'ARE', 784),
('GB', 'UNITED KINGDOM', 'United Kingdom', 'GBR', 826),
('US', 'UNITED STATES', 'United States', 'USA', 840),
('UM', 'UNITED STATES MINOR OUTLYING ISLANDS', 'United States Minor Outlying Islands', NULL, NULL),
('UY', 'URUGUAY', 'Uruguay', 'URY', 858),
('UZ', 'UZBEKISTAN', 'Uzbekistan', 'UZB', 860),
('VU', 'VANUATU', 'Vanuatu', 'VUT', 548),
('VE', 'VENEZUELA', 'Venezuela', 'VEN', 862),
('VN', 'VIET NAM', 'Viet Nam', 'VNM', 704),
('VG', 'VIRGIN ISLANDS, BRITISH', 'Virgin Islands, British', 'VGB', 92),
('VI', 'VIRGIN ISLANDS, U.S.', 'Virgin Islands, U.s.', 'VIR', 850),
('WF', 'WALLIS AND FUTUNA', 'Wallis and Futuna', 'WLF', 876),
('EH', 'WESTERN SAHARA', 'Western Sahara', 'ESH', 732),
('YE', 'YEMEN', 'Yemen', 'YEM', 887),
('ZM', 'ZAMBIA', 'Zambia', 'ZMB', 894),
('ZW', 'ZIMBABWE', 'Zimbabwe', 'ZWE', 716)";


$SQL[] = "DROP TABLE IF EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_flood`";
$SQL[] = "CREATE TABLE IF NOT EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_flood` (
  `ip` varchar(18) NOT NULL DEFAULT '',
  `time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";


$SQL[] = "DROP TABLE IF EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_menu`";
$SQL[] = "CREATE TABLE IF NOT EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `tag` varchar(50) NOT NULL COMMENT 'Smarty variable',
  `pages` varchar(255) NOT NULL COMMENT 'Pages IDs (separate by | )',
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";

$SQL[] = "INSERT INTO `" . _AM_CREATE_DB_PREFIX . "sb_menu` (`id`, `name`, `tag`, `pages`, `active`) VALUES
(1, 'Main menu', 'main_menu', '1', 1)";


$SQL[] = "DROP TABLE IF EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_pages`";
$SQL[] = "CREATE TABLE IF NOT EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu` varchar(100) NOT NULL COMMENT 'Texte du menu',
  `title` varchar(255) NOT NULL COMMENT 'Titre de la page',
  `content` text NOT NULL,
  `seo_url` text NOT NULL,
  `url_custom` varchar(255) NOT NULL,
  `seo_keywords` text NOT NULL COMMENT 'Mots cles additionnels de la page',
  `seo_description` varchar(155) NOT NULL COMMENT 'Meta description additionnels de la page',
  `module_view` varchar(50) NOT NULL COMMENT 'Module view for the current page',
  `theme_view` varchar(50) NOT NULL COMMENT 'Theme view defined by CMS theme',
  `headpage` text NOT NULL COMMENT 'Code entete page theme (si declare dans config)',
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `sort` int(11) NOT NULL COMMENT 'Tri des pages / menus',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Pages libres du site'";

$SQL[] = "INSERT INTO `" . _AM_CREATE_DB_PREFIX . "sb_pages` (`id`, `menu`, `title`, `content`, `seo_url`, `url_custom`, `seo_keywords`, `seo_description`, `module_view`, `theme_view`, `headpage`, `active`, `sort`) VALUES
(1, '[fr]Accueil[/fr]', '[fr]Accueil[/fr]', '[fr]&lt;p&gt;Thank you for using SBMAGIC CMS. This is your homepage, so please change this text to be what you want.&lt;/p&gt;\r\n\r\n&lt;ul&gt;\r\n	&lt;li&gt;&lt;a href=&quot;#&quot;&gt;SBMAGIC CMS Documentation&lt;/a&gt;\r\n\r\n	&lt;ul&gt;\r\n		&lt;li&gt;&lt;a href=&quot;#&quot;&gt;How to Create a SBMAGIC Theme&lt;/a&gt;&lt;/li&gt;\r\n	&lt;/ul&gt;\r\n	&lt;/li&gt;\r\n	&lt;li&gt;&lt;a href=&quot;#&quot;&gt;SBMAGIC Support Forums&lt;/a&gt;&lt;/li&gt;\r\n&lt;/ul&gt;\r\n\r\n&lt;h2&gt;Header 2&lt;/h2&gt;\r\n\r\n&lt;p&gt;Lorem ipsum &lt;em&gt;dolor sit amet&lt;/em&gt;, &lt;strong&gt;consectetur adipiscing elit&lt;/strong&gt;. Donec &lt;code&gt;this is code&lt;/code&gt; venenatis augue. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Integer vulputate pretium augue.&lt;/p&gt;\r\n\r\n&lt;h3&gt;Header 3&lt;/h3&gt;\r\n\r\n&lt;pre&gt;\r\n&lt;code class=&quot;language-css&quot;&gt;#header h1 a { \r\n	display: block; \r\n	width: 300px; \r\n	height: 80px; \r\n}&lt;/code&gt;&lt;/pre&gt;\r\n\r\n&lt;h4&gt;Header 4&lt;/h4&gt;\r\n\r\n&lt;ol&gt;\r\n	&lt;li&gt;Lorem ipsum dolor sit amet&lt;/li&gt;\r\n	&lt;li&gt;Consectetur adipiscing elit&lt;/li&gt;\r\n	&lt;li&gt;Donec ut est risus, placerat venenatis augue&lt;/li&gt;\r\n&lt;/ol&gt;\r\n\r\n&lt;blockquote&gt;A blockquote. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec ut est risus, placerat venenatis augue. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.&lt;/blockquote&gt;[/fr]', '', '', 'mon,site,page,accueil', 'La page d\'accueil de mon site', '', 'index', '', 1, 0)";



$SQL[] = "DROP TABLE IF EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_slider`";
$SQL[] = "CREATE TABLE IF NOT EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_slider` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `jquery` tinyint(4) NOT NULL COMMENT 'Chargement de jquery',
  `responsive` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `auto` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `pause` int(11) NOT NULL,
  `speed` int(11) NOT NULL,
  `randomstart` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `mode` varchar(50) NOT NULL,
  `preloadimages` varchar(50) NOT NULL,
  `controls` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `autocontrols` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `autohover` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `captions` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `adaptiveheight` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `adaptiveheightspeed` int(11) NOT NULL,
  `slidemargin` int(11) NOT NULL,
  `video` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `usecss` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `pager` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `pagertype` varchar(50) NOT NULL COMMENT 'full, short',
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";


$SQL[] = "DROP TABLE IF EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_slider_photos`";
$SQL[] = "CREATE TABLE IF NOT EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_slider_photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) NOT NULL COMMENT 'Slider id',
  `title` varchar(255) NOT NULL COMMENT 'Nom de la photo',
  `photo` varchar(255) NOT NULL COMMENT 'Nom de l''image physique',
  `type` varchar(10) NOT NULL COMMENT 'video, photo',
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";


$SQL[] = "DROP TABLE IF EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_news`";
$SQL[] = "CREATE TABLE IF NOT EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `catid` varchar(50) NOT NULL COMMENT 'Categories',
  `title` text NOT NULL,
  `subtitle` text NOT NULL,
  `desc_short` text NOT NULL,
  `desc_full` text NOT NULL,
  `image` varchar(200) NOT NULL,
  `date` varchar(10) NOT NULL,
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";


$SQL[] = "DROP TABLE IF EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_news_category`";
$SQL[] = "CREATE TABLE IF NOT EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_news_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `subtitle` text NOT NULL,
  `tpl_list` text NOT NULL,
  `tpl_single` text NOT NULL,
  `module_show` varchar(50) NOT NULL COMMENT 'normal,masonry,...',
  `module_show_masonry` int(11) NOT NULL COMMENT 'columns width (pixels)',
  `photo` varchar(255) NOT NULL,
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";


$SQL[] = "DROP TABLE IF EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_news_settings`";
$SQL[] = "CREATE TABLE IF NOT EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_news_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_per_page` int(11) NOT NULL COMMENT 'article par page (catégorie)',
  `module_start` tinyint(4) NOT NULL COMMENT '0: liste des catégories, 1: catégorie spécifique',
  `catid` int(11) NOT NULL COMMENT 'Démarrage par cette catégorie',
  `breadcrumb` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `title_h1` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `title_h2` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `theme_view_cat` varchar(100) NOT NULL DEFAULT 'index' COMMENT 'Theme view du module LISTE DES CATEGORIES',
  `theme_view_list` varchar(100) NOT NULL DEFAULT 'index' COMMENT 'Theme view du module LISTE DES ARTICLES',
  `theme_view_single` varchar(100) NOT NULL DEFAULT 'index' COMMENT 'Theme view du module ARTICLE',
  `other_news` tinyint(4) NOT NULL,
  `other_news_per_page` int(11) NOT NULL,
  `other_news_title` varchar(255) NOT NULL DEFAULT 'Autres articles',
  `other_news_type` varchar(20) NOT NULL COMMENT 'random, latest, first',
  `news_next_prev` varchar(20) NOT NULL DEFAULT 'arrow' COMMENT 'arrow, title',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";

$SQL[] = "INSERT INTO `" . _AM_CREATE_DB_PREFIX . "sb_news_settings` (`id`, `item_per_page`, `module_start`, `catid`, `breadcrumb`, `title_h1`, `title_h2`, `theme_view_cat`, `theme_view_list`, `theme_view_single`, `other_news`, `other_news_per_page`, `other_news_title`, `other_news_type`) VALUES
(1, 10, 0, 1, 1, 1, 1, 'index', 'index', 'index', 1, 5, 'Autres articles', 'latest')";


$SQL[] = "DROP TABLE IF EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_effectives`";
$SQL[] = "CREATE TABLE IF NOT EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_effectives` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `catid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `subtitle1` varchar(255) NOT NULL,
  `subtitle2` text NOT NULL,
  `photo` varchar(255) NOT NULL,
  `date` varchar(10) NOT NULL,
  `sex` varchar(50) NOT NULL,
  `chrono` varchar(255) NOT NULL,
  `winnings` varchar(50) NOT NULL COMMENT 'Gains',
  `status` varchar(255) NOT NULL,
  `colour` varchar(255) NOT NULL COMMENT 'Robe',
  `size` varchar(255) NOT NULL,
  `sire` varchar(255) NOT NULL,
  `dam` varchar(255) NOT NULL,
  `sire_dam` varchar(255) NOT NULL,
  `projection` varchar(255) NOT NULL COMMENT 'Saillie',
  `origine` varchar(255) NOT NULL COMMENT 'pays',
  `pedigree` text NOT NULL,
  `pedigree_extend` varchar(255) NOT NULL COMMENT 'Fichier PDF supplémentaire',
  `pedigree_desc` text NOT NULL,
  `breeder` varchar(100) NOT NULL COMMENT 'Eleveur',
  `owner` varchar(100) NOT NULL COMMENT 'Propriétaire',
  `description` text NOT NULL,
  `description_extend` text NOT NULL,
  `headpage` text NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";


$SQL[] = "DROP TABLE IF EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_effectives_category`";
$SQL[] = "CREATE TABLE IF NOT EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_effectives_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `subtitle` text NOT NULL,
  `tpl_list` text NOT NULL,
  `tpl_single` text NOT NULL,
  `module_show` varchar(50) NOT NULL COMMENT 'normal,masonry,...',
  `module_show_masonry` int(11) NOT NULL COMMENT 'columns width (pixels)',
  `photo` varchar(255) NOT NULL,
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";


$SQL[] = "DROP TABLE IF EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_effectives_medias`";
$SQL[] = "CREATE TABLE IF NOT EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_effectives_medias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eid` int(11) NOT NULL COMMENT 'Effective ID',
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) NOT NULL,
  `url` text NOT NULL,
  `type` varchar(10) NOT NULL COMMENT 'pdf, youtube, video, photo',
  `active` tinyint(1) NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";


$SQL[] = "DROP TABLE IF EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_effectives_production`";
$SQL[] = "CREATE TABLE IF NOT EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_effectives_production` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eid` int(11) NOT NULL COMMENT 'Effective ID',
  `name` varchar(255) NOT NULL,
  `sex` varchar(10) NOT NULL,
  `date` varchar(10) NOT NULL,
  `colour` varchar(255) NOT NULL,
  `group_bold` tinyint(4) NOT NULL,
  `dam` varchar(255) NOT NULL,
  `sire_dam` varchar(255) NOT NULL,
  `performance` text NOT NULL,
  `photo` varchar(255) NOT NULL,
  `video` varchar(255) NOT NULL,
  `pedigree` varchar(255) NOT NULL,
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";


$SQL[] = "DROP TABLE IF EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_effectives_settings`";
$SQL[] = "CREATE TABLE IF NOT EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_effectives_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `effectives_per_page` int(11) NOT NULL COMMENT 'Effectifs par page (catégorie)',
  `module_start` tinyint(4) NOT NULL COMMENT '0: liste des catégories, 1: catégorie spécifique',
  `breadcrumb` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `title_h1` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `title_h2` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `title_description` varchar(255) NOT NULL,
  `title_description_extend` varchar(255) NOT NULL,
  `title_pedigree` varchar(255) NOT NULL,
  `title_production` varchar(255) NOT NULL,
  `title_medias` varchar(255) NOT NULL,
  `catid` int(11) NOT NULL COMMENT 'Démarrage par cette catégorie',
  `chrono` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `status` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `photo` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `origine` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `pedigree` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `pedigree_extend` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `pedigree_desc` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `date` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `sire` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `dam` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `sire_dam` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `sex` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `winnings` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `size` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `projection` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `colour` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `breeder` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `owner` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `description` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `description_extend` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `subtitle1` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `subtitle2` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `production_sex` tinyint(4) NOT NULL,
  `production_date` tinyint(4) NOT NULL,
  `production_colour` tinyint(4) NOT NULL,
  `production_dam` tinyint(4) NOT NULL,
  `production_sire_dam` tinyint(4) NOT NULL,
  `production_photo` tinyint(4) NOT NULL,
  `production_video` tinyint(4) NOT NULL,
  `production_pedigree` tinyint(4) NOT NULL,
  `theme_view_cat` varchar(100) NOT NULL DEFAULT 'index' COMMENT 'Theme view du module LISTE DES CATEGORIES',
  `theme_view_list` varchar(100) NOT NULL DEFAULT 'index' COMMENT 'Theme view du module LISTE DES EFFECTIFS',
  `theme_view_single` varchar(100) NOT NULL DEFAULT 'index' COMMENT 'Theme view du module ARTICLE',
  `effectives_help` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";

$SQL[] = "INSERT INTO `" . _AM_CREATE_DB_PREFIX . "sb_effectives_settings` (`id`, `effectives_per_page`, `module_start`, `breadcrumb`, `title_h1`, `title_h2`, `title_description`, `title_description_extend`, `title_pedigree`, `title_production`, `title_medias`, `catid`, `chrono`, `status`, `photo`, `origine`, `pedigree`, `pedigree_extend`, `pedigree_desc`, `date`, `sire`, `dam`, `sire_dam`, `sex`, `winnings`, `size`, `projection`, `colour`, `breeder`, `owner`, `description`, `description_extend`, `subtitle1`, `subtitle2`, `production_sex`, `production_date`, `production_colour`, `production_dam`, `production_sire_dam`, `production_photo`, `production_video`, `production_pedigree`, `theme_view_cat`, `theme_view_list`, `theme_view_single`, `effectives_help`) VALUES (1, 10, 0, 1, 1, 1, 'Description', 'R&eacute;servation de saillie', 'Pedigree', 'Production', 'Medias', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 'index', 'index', 'index', '')";


$SQL[] = "DROP TABLE IF EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_graduates`";
$SQL[] = "CREATE TABLE `" . _AM_CREATE_DB_PREFIX . "sb_graduates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `catid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `sire_dam_info` varchar(255) NOT NULL COMMENT 'Père & Mère (Père de mère)',
  `photo` varchar(255) NOT NULL,
  `perf_1` varchar(200) NOT NULL,
  `video_1` varchar(255) NOT NULL,
  `perf_2` varchar(200) NOT NULL,
  `video_2` varchar(255) NOT NULL,
  `perf_3` varchar(200) NOT NULL,
  `video_3` varchar(255) NOT NULL,
  `perf_4` varchar(200) NOT NULL,
  `video_4` varchar(255) NOT NULL,
  `perf_5` varchar(200) NOT NULL,
  `video_5` varchar(255) NOT NULL,
  `perf_6` varchar(200) NOT NULL,
  `video_6` varchar(255) NOT NULL,
  `perf_7` varchar(200) NOT NULL,
  `video_7` varchar(255) NOT NULL,
  `perf_8` varchar(200) NOT NULL,
  `video_8` varchar(255) NOT NULL,
  `perf_9` varchar(200) NOT NULL,
  `video_9` varchar(255) NOT NULL,
  `perf_10` varchar(200) NOT NULL,
  `video_10` varchar(255) NOT NULL,
  `breeder` varchar(255) NOT NULL COMMENT 'Entraîneur',
  `owner` varchar(255) NOT NULL COMMENT 'Propriétaire',
  `headpage` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL COMMENT '0: inactive, 1: active',
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";


$SQL[] = "DROP TABLE IF EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_graduates_category`";
$SQL[] = "CREATE TABLE `" . _AM_CREATE_DB_PREFIX . "sb_graduates_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `subtitle` text NOT NULL,
  `tpl_list` text NOT NULL,
  `tpl_single` text NOT NULL,
  `module_show` varchar(50) NOT NULL COMMENT 'normal,masonry,...',
  `module_show_masonry` int(11) NOT NULL COMMENT 'columns width (pixels)',
  `photo` varchar(255) NOT NULL,
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";


$SQL[] = "DROP TABLE IF EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_graduates_settings`";
$SQL[] = "CREATE TABLE `" . _AM_CREATE_DB_PREFIX . "sb_graduates_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `graduates_per_page` int(11) NOT NULL COMMENT 'Graduates par page (catégorie)',
  `module_start` tinyint(4) NOT NULL COMMENT '0: liste des catégories, 1: catégorie spécifique',
  `breadcrumb` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `title_h1` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `title_h2` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `catid` int(11) NOT NULL COMMENT 'Démarrage par cette catégorie',
  `theme_view_cat` varchar(100) NOT NULL DEFAULT 'index' COMMENT 'Theme view du module LISTE DES CATEGORIES',
  `theme_view_list` varchar(100) NOT NULL DEFAULT 'index' COMMENT 'Theme view du module LISTE DES EFFECTIFS',
  `theme_view_single` varchar(100) NOT NULL DEFAULT 'index' COMMENT 'Theme view du module single GRADUATES',
  `graduates_help` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";

$SQL[] = "INSERT INTO `" . _AM_CREATE_DB_PREFIX . "sb_graduates_settings` (`id`, `graduates_per_page`, `module_start`, `breadcrumb`, `title_h1`, `title_h2`, `catid`, `theme_view_cat`, `theme_view_list`, `theme_view_single`, `graduates_help`) VALUES
(1, 10, 0, 1, 1, 1, 1, 'index', 'index', 'index', '')";


$SQL[] = "DROP TABLE IF EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_tabbs`";
$SQL[] = "CREATE TABLE `" . _AM_CREATE_DB_PREFIX . "sb_tabbs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";


$SQL[] = "DROP TABLE IF EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_tabbs_tab`";
$SQL[] = "CREATE TABLE `" . _AM_CREATE_DB_PREFIX . "sb_tabbs_tab` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL COMMENT 'TABBS id',
  `title` text NOT NULL,
  `content` text NOT NULL,
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";


$SQL[] = "DROP TABLE IF EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_table`";
$SQL[] = "CREATE TABLE IF NOT EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `type` varchar(10) NOT NULL COMMENT 'option1, option2, ...',
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";


$SQL[] = "DROP TABLE IF EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_table_datas`";
$SQL[] = "CREATE TABLE IF NOT EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_table_datas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL COMMENT 'Table ID',
  `content` text NOT NULL COMMENT 'Contenus',
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";


$SQL[] = "DROP TABLE IF EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_table_structure`";
$SQL[] = "CREATE TABLE IF NOT EXISTS `" . _AM_CREATE_DB_PREFIX . "sb_table_structure` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL COMMENT 'Table ID',
  `title` varchar(255) NOT NULL,
  `field_type` varchar(50) NOT NULL COMMENT 'photo,text,date,textarea,textareahtml,link...',
  `field_target` varchar(50) NOT NULL,
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";

?>