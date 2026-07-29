-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 14, 2017 at 04:38 PM
-- Server version: 5.7.17-0ubuntu0.16.04.1
-- PHP Version: 5.6.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sbuadmin_testing`
--

-- --------------------------------------------------------

--
-- Table structure for table `sb_attempts`
--

DROP TABLE IF EXISTS `<DB_PREFIX>sb_attempts`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_attempts` (
  `ip` varchar(15) NOT NULL,
  `count` int(11) NOT NULL,
  `expiredate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table Attempts';

-- --------------------------------------------------------

--
-- Structure de la table `sb_blocked_history`
--

CREATE TABLE `<DB_PREFIX>sb_blocked_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `count` tinyint(4) UNSIGNED NOT NULL DEFAULT '1',
  `blockedtime` bigint(20) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `infos` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `sb_blocked_ip`
--

CREATE TABLE `<DB_PREFIX>sb_blocked_ip` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ip` varchar(20) NOT NULL,
  `count` tinyint(4) UNSIGNED NOT NULL DEFAULT '1',
  `blockedtime` bigint(20) NOT NULL,
  `expirationtime` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `reason` varchar(255) NOT NULL,
  `infos` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sb_blocs`
--

DROP TABLE IF EXISTS `<DB_PREFIX>sb_blocs`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_blocs` (
  `id` int(11) NOT NULL,
  `pages_id` varchar(255) NOT NULL COMMENT 'Pages IDs (separate by | )',
  `modules_id` varchar(255) NOT NULL COMMENT 'Module dirnames (separate by | )',
  `name` varchar(100) NOT NULL COMMENT 'Nom du bloc',
  `title` text NOT NULL COMMENT 'titre du bloc (cote client)',
  `content` text NOT NULL COMMENT 'Contenu du bloc',
  `position` varchar(100) NOT NULL,
  `various_view` varchar(100) DEFAULT NULL COMMENT 'Additional HTML file ',
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `sort` int(11) NOT NULL COMMENT 'Tri des blocs'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Blocs associes aux pages';

-- --------------------------------------------------------

--
-- Table structure for table `sb_blocs_sort`
--

DROP TABLE IF EXISTS `<DB_PREFIX>sb_blocs_sort`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_blocs_sort` (
  `id` int(11) NOT NULL,
  `bloc_id` int(11) NOT NULL COMMENT 'ID des blocs',
  `page_id` int(11) DEFAULT NULL COMMENT 'ID des pages',
  `module_id` varchar(50) DEFAULT NULL COMMENT 'Nom du module (nom du repertoire)',
  `sort` int(11) NOT NULL COMMENT 'Tri des blocs par page'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sb_config`
--

DROP TABLE IF EXISTS `<DB_PREFIX>sb_config`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_config` (
  `id` int(11) NOT NULL,
  `config` varchar(50) NOT NULL COMMENT 'Nom de la configuration',
  `content` text NOT NULL COMMENT 'Valeur de la configuration'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sb_config`
--

INSERT INTO `<DB_PREFIX>sb_config` (`id`, `config`, `content`) VALUES
(1, 'css', '.fright {float: right;} .fcenter {float: center;} .fleft {float: left;} .aright {text-align: right;} .acenter {text-align: center;} .aleft {text-align: left;} .dnone {display: none !important;}'),
(2, 'javascript', 'jQuery(document).ready(function() { 	// Recherche cach&eacute;e 	jQuery(&#039;#votrediv&#039;).css(&#039;color&#039;,&#039;red&#039;); });'),
(3, 'header', '[fr]&lt;h3&gt;Bienvenue sur SBUIADMIN&lt;/h3&gt;  &lt;h5&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit.&lt;/h5&gt;  &lt;p&gt;Cras rutrum, massa non blandit convallis, est lacus gravida enim, eu fermentum ligula orci et tortor.&lt;/p&gt; &lt;a href=&quot;#&quot;&gt;Lire la suite&lt;/a&gt;[/fr]'),
(4, 'footer', '[fr]&amp;copy; [CS name=sbyear] &amp;bull; www.votresite.com &amp;bull; Cr&amp;eacute;&amp;eacute; &amp;amp; r&amp;eacute;alis&amp;eacute; par &lt;a href=&quot;//informatux.com&quot; target=&quot;_blank&quot;&gt;informatux.com&lt;/a&gt;[/fr]'),
(5, 'email_to', 'contact@votresite.fr'),
(6, 'email_publickey', ''),
(7, 'email_privatekey', ''),
(8, 'email_subject', 'Message de votre site'),
(9, 'coming-soon', '1'),
(10, 'coming-soon-url', 'comingSoon'),
(11, 'coming-soon-title', 'SBUIADMIN'),
(12, 'coming-soon-title2', '&lt;span style=&quot;font-size: 2.7em;&quot;&gt;Site en maintenance&lt;/span&gt;&lt;br&gt;Nous revenons tr&egrave;s vite&lt;br&gt;'),
(13, 'coming-soon-text', 'We are a team of talented people with big ideas and creative minds.&lt;br /&gt; We are here to make your website a lot more effective and profitable.&lt;br /&gt; Genesis Coming Soon Template is a perfect solution to keep your visitors&lt;br /&gt; interested while preparing site for launch.&lt;br /&gt; We know what&amp;#39;s the best and we&amp;#39;re here for you.'),
(14, 'coming-soon-tel', '02 32 45 67 89'),
(15, 'coming-soon-address', 'Rue de la bourse, 75016 Paris'),
(16, 'coming-soon-email', 'info@big-society.com'),
(17, 'coming-soon-facebook', 'https://fr-fr.facebook.com/public/Patrice-Bouthier'),
(18, 'coming-soon-twitter', '#'),
(19, 'coming-soon-youtube', 'https://www.youtube.com/channel/UCLk-U6SQ6Syj1XXkvdVgoAQ'),
(20, 'multilang', '0'),
(21, 'plugins', ''),
(22, 'fonts', ''),
(23, 'seo-keywords', 'sbuiadmin,cms,bootstrap,sbadmin2'),
(24, 'seo-description', 'Le CMS Bootstrap by BooBoo'),
(25, 'coming-soon-type', 'image'),
(26, 'coming-soon-image', 'news-3.jpg'),
(27, 'coming-soon-video', 'E5MO0h7NIqY'),
(28, 'coming-soon-dark', '0'),
(29, 'coming-soon-date', '31/05/2050'),
(30, 'coming-soon-google-plus', '#'),
(31, 'toolbarck', '0'),
(32, 'seo-rating', 'general'),
(33, 'seo-robots', 'index,follow'),
(34, 'seo-author', 'BooBoo'),
(35, 'seo-copyright', 'SBUIADMIN By Booboo'),
(36, 'seo-generator', 'SBUIADMIN'),
(37, 'seo-google-site-verification', ''),
(38, 'seo-google-analytics', ''),
(39, 'theme_infos_tel', '07.80.53.23.67'),
(40, 'theme_infos_address', 'Rue de la bourse 75016 Paris, FR'),
(41, 'theme_infos_email', 'contact@informatux.com'),
(42, 'theme_infos_facebook', 'https://fr-fr.facebook.com/public/Patrice-Bouthier'),
(43, 'theme_infos_twitter', ''),
(44, 'theme_infos_google_plus', 'https://plus.google.com/109974847432830295737'),
(45, 'theme_infos_pinterest', ''),
(46, 'theme_infos_instagram', ''),
(47, 'theme_infos_skype', 'skype:informatux27'),
(48, 'theme_infos_viadeo', 'http://fr.viadeo.com/fr/profile/patrice.bouthier'),
(49, 'theme_infos_vimeo', ''),
(50, 'theme_infos_youtube', 'https://www.youtube.com/channel/UCLk-U6SQ6Syj1XXkvdVgoAQ'),
(51, 'theme_infos_linkedin', ''),
(52, 'theme_infos_github', ''),
(53, 'cookie-lifetime', '86400'),
(54, 'email_smtp', '0'),
(55, 'email_smtp_host', ''),
(56, 'email_smtp_auth', '0'),
(57, 'email_smtp_port', ''),
(58, 'email_smtp_username', ''),
(59, 'email_smtp_password', ''),
(60, 'email_smtp_secure', ''),
(61, 'email_smtp_debug', '0');

-- --------------------------------------------------------

--
-- Table structure for table `sb_dashboard_widgets`
--

DROP TABLE IF EXISTS `<DB_PREFIX>sb_dashboard_widgets`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_dashboard_widgets` (
  `id` int(11) NOT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'table',
  `position` int(11) NOT NULL DEFAULT 0,
  `table_name` varchar(64) NOT NULL,
  `value_column` varchar(64) NOT NULL,
  `date_column` varchar(64) NOT NULL DEFAULT '',
  `widget_key` varchar(50) NOT NULL DEFAULT '',
  `location` varchar(255) NOT NULL DEFAULT '',
  `content` longtext,
  `title` varchar(100) NOT NULL,
  `link` varchar(255) NOT NULL DEFAULT '',
  `icon` varchar(50) NOT NULL DEFAULT '',
  `color` varchar(20) NOT NULL DEFAULT 'primary',
  `show_chart` tinyint(1) NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sb_dashboard_widgets`
--

INSERT INTO `<DB_PREFIX>sb_dashboard_widgets` (`id`, `type`, `position`, `table_name`, `value_column`, `date_column`, `widget_key`, `location`, `title`, `link`, `icon`, `color`, `show_chart`, `active`) VALUES
(1, 'system', 0, '', '', '', 'users_count', '', 'Utilisateurs', 'index.php?p=users', 'users', 'primary', 0, 1),
(2, 'system', 1, '', '', '', 'php_version', '', 'Version PHP', 'index.php?p=database', 'code', 'info', 0, 1),
(3, 'system', 2, '', '', '', 'db_host', '', 'DB Host', 'index.php?p=settings', 'database', 'purple', 0, 1),
(4, 'system', 3, '', '', '', 'upload_limit', '', 'Upload limit', 'index.php?p=settings', 'upload', 'warning', 0, 1),
(5, 'table', 4, 'sb_sandbox', 'nom', '', '', '', 'Nom (Sandbox)', 'index.php?p=sandbox', 'ambulance', 'primary', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sb_contact`
--

DROP TABLE IF EXISTS `<DB_PREFIX>sb_contact`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_contact` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `recipients` text NOT NULL COMMENT 'destinataires',
  `subject` text NOT NULL,
  `form` text NOT NULL,
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `sort` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sb_country`
--

DROP TABLE IF EXISTS `<DB_PREFIX>sb_country`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_country` (
  `country_iso` char(2) NOT NULL,
  `country_name` varchar(80) NOT NULL,
  `country_printable_name` varchar(80) NOT NULL,
  `country_iso3` char(3) DEFAULT NULL,
  `country_numcode` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sb_country`
--

INSERT INTO `<DB_PREFIX>sb_country` (`country_iso`, `country_name`, `country_printable_name`, `country_iso3`, `country_numcode`) VALUES
("AD", "ANDORRA", "Andorra", "AND", 20),
("AE", "UNITED ARAB EMIRATES", "United Arab Emirates", "ARE", 784),
("AF", "AFGHANISTAN", "Afghanistan", "AFG", 4),
("AG", "ANTIGUA AND BARBUDA", "Antigua and Barbuda", "ATG", 28),
("AI", "ANGUILLA", "Anguilla", "AIA", 660),
("AL", "ALBANIA", "Albania", "ALB", 8),
("AM", "ARMENIA", "Armenia", "ARM", 51),
("AN", "NETHERLANDS ANTILLES", "Netherlands Antilles", "ANT", 530),
("AO", "ANGOLA", "Angola", "AGO", 24),
("AQ", "ANTARCTICA", "Antarctica", NULL, NULL),
("AR", "ARGENTINA", "Argentina", "ARG", 32),
("AS", "AMERICAN SAMOA", "American Samoa", "ASM", 16),
("AT", "AUSTRIA", "Austria", "AUT", 40),
("AU", "AUSTRALIA", "Australia", "AUS", 36),
("AW", "ARUBA", "Aruba", "ABW", 533),
("AZ", "AZERBAIJAN", "Azerbaijan", "AZE", 31),
("BA", "BOSNIA AND HERZEGOVINA", "Bosnia and Herzegovina", "BIH", 70),
("BB", "BARBADOS", "Barbados", "BRB", 52),
("BD", "BANGLADESH", "Bangladesh", "BGD", 50),
("BE", "BELGIUM", "Belgium", "BEL", 56),
("BF", "BURKINA FASO", "Burkina Faso", "BFA", 854),
("BG", "BULGARIA", "Bulgaria", "BGR", 100),
("BH", "BAHRAIN", "Bahrain", "BHR", 48),
("BI", "BURUNDI", "Burundi", "BDI", 108),
("BJ", "BENIN", "Benin", "BEN", 204),
("BM", "BERMUDA", "Bermuda", "BMU", 60),
("BN", "BRUNEI DARUSSALAM", "Brunei Darussalam", "BRN", 96),
("BO", "BOLIVIA", "Bolivia", "BOL", 68),
("BR", "BRAZIL", "Brazil", "BRA", 76),
("BS", "BAHAMAS", "Bahamas", "BHS", 44),
("BT", "BHUTAN", "Bhutan", "BTN", 64),
("BV", "BOUVET ISLAND", "Bouvet Island", NULL, NULL),
("BW", "BOTSWANA", "Botswana", "BWA", 72),
("BY", "BELARUS", "Belarus", "BLR", 112),
("BZ", "BELIZE", "Belize", "BLZ", 84),
("CA", "CANADA", "Canada", "CAN", 124),
("CC", "COCOS (KEELING) ISLANDS", "Cocos (Keeling) Islands", NULL, NULL),
("CD", "CONGO, THE DEMOCRATIC REPUBLIC OF THE", "Congo, the Democratic Republic of the", "COD", 180),
("CF", "CENTRAL AFRICAN REPUBLIC", "Central African Republic", "CAF", 140),
("CG", "CONGO", "Congo", "COG", 178),
("CH", "SWITZERLAND", "Switzerland", "CHE", 756),
("CI", "COTE D'IVOIRE", "Cote D'Ivoire", "CIV", 384),
("CK", "COOK ISLANDS", "Cook Islands", "COK", 184),
("CL", "CHILE", "Chile", "CHL", 152),
("CM", "CAMEROON", "Cameroon", "CMR", 120),
("CN", "CHINA", "China", "CHN", 156),
("CO", "COLOMBIA", "Colombia", "COL", 170),
("CR", "COSTA RICA", "Costa Rica", "CRI", 188),
("CS", "SERBIA AND MONTENEGRO", "Serbia and Montenegro", NULL, NULL),
("CU", "CUBA", "Cuba", "CUB", 192),
("CV", "CAPE VERDE", "Cape Verde", "CPV", 132),
("CX", "CHRISTMAS ISLAND", "Christmas Island", NULL, NULL),
("CY", "CYPRUS", "Cyprus", "CYP", 196),
("CZ", "CZECH REPUBLIC", "Czech Republic", "CZE", 203),
("DE", "GERMANY", "Germany", "DEU", 276),
("DJ", "DJIBOUTI", "Djibouti", "DJI", 262),
("DK", "DENMARK", "Denmark", "DNK", 208),
("DM", "DOMINICA", "Dominica", "DMA", 212),
("DO", "DOMINICAN REPUBLIC", "Dominican Republic", "DOM", 214),
("DZ", "ALGERIA", "Algeria", "DZA", 12),
("EC", "ECUADOR", "Ecuador", "ECU", 218),
("EE", "ESTONIA", "Estonia", "EST", 233),
("EG", "EGYPT", "Egypt", "EGY", 818),
("EH", "WESTERN SAHARA", "Western Sahara", "ESH", 732),
("ER", "ERITREA", "Eritrea", "ERI", 232),
("ES", "SPAIN", "Spain", "ESP", 724),
("ET", "ETHIOPIA", "Ethiopia", "ETH", 231),
("FI", "FINLAND", "Finland", "FIN", 246),
("FJ", "FIJI", "Fiji", "FJI", 242),
("FK", "FALKLAND ISLANDS (MALVINAS)", "Falkland Islands (Malvinas)", "FLK", 238),
("FM", "MICRONESIA, FEDERATED STATES OF", "Micronesia, Federated States of", "FSM", 583),
("FO", "FAROE ISLANDS", "Faroe Islands", "FRO", 234),
("FR", "FRANCE", "France", "FRA", 250),
("GA", "GABON", "Gabon", "GAB", 266),
("GB", "UNITED KINGDOM", "United Kingdom", "GBR", 826),
("GD", "GRENADA", "Grenada", "GRD", 308),
("GE", "GEORGIA", "Georgia", "GEO", 268),
("GF", "FRENCH GUIANA", "French Guiana", "GUF", 254),
("GH", "GHANA", "Ghana", "GHA", 288),
("GI", "GIBRALTAR", "Gibraltar", "GIB", 292),
("GL", "GREENLAND", "Greenland", "GRL", 304),
("GM", "GAMBIA", "Gambia", "GMB", 270),
("GN", "GUINEA", "Guinea", "GIN", 324),
("GP", "GUADELOUPE", "Guadeloupe", "GLP", 312),
("GQ", "EQUATORIAL GUINEA", "Equatorial Guinea", "GNQ", 226),
("GR", "GREECE", "Greece", "GRC", 300),
("GS", "SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS", "South Georgia and the South Sandwich Islands", NULL, NULL),
("GT", "GUATEMALA", "Guatemala", "GTM", 320),
("GU", "GUAM", "Guam", "GUM", 316),
("GW", "GUINEA-BISSAU", "Guinea-Bissau", "GNB", 624),
("GY", "GUYANA", "Guyana", "GUY", 328),
("HK", "HONG KONG", "Hong Kong", "HKG", 344),
("HM", "HEARD ISLAND AND MCDONALD ISLANDS", "Heard Island and Mcdonald Islands", NULL, NULL),
("HN", "HONDURAS", "Honduras", "HND", 340),
("HR", "CROATIA", "Croatia", "HRV", 191),
("HT", "HAITI", "Haiti", "HTI", 332),
("HU", "HUNGARY", "Hungary", "HUN", 348),
("ID", "INDONESIA", "Indonesia", "IDN", 360),
("IE", "IRELAND", "Ireland", "IRL", 372),
("IL", "ISRAEL", "Israel", "ISR", 376),
("IN", "INDIA", "India", "IND", 356),
("IO", "BRITISH INDIAN OCEAN TERRITORY", "British Indian Ocean Territory", NULL, NULL),
("IQ", "IRAQ", "Iraq", "IRQ", 368),
("IR", "IRAN, ISLAMIC REPUBLIC OF", "Iran, Islamic Republic of", "IRN", 364),
("IS", "ICELAND", "Iceland", "ISL", 352),
("IT", "ITALY", "Italy", "ITA", 380),
("JM", "JAMAICA", "Jamaica", "JAM", 388),
("JO", "JORDAN", "Jordan", "JOR", 400),
("JP", "JAPAN", "Japan", "JPN", 392),
("KE", "KENYA", "Kenya", "KEN", 404),
("KG", "KYRGYZSTAN", "Kyrgyzstan", "KGZ", 417),
("KH", "CAMBODIA", "Cambodia", "KHM", 116),
("KI", "KIRIBATI", "Kiribati", "KIR", 296),
("KM", "COMOROS", "Comoros", "COM", 174),
("KN", "SAINT KITTS AND NEVIS", "Saint Kitts and Nevis", "KNA", 659),
("KP", "KOREA, DEMOCRATIC PEOPLE'S REPUBLIC OF", "Korea, Democratic People's Republic of", "PRK", 408),
("KR", "KOREA, REPUBLIC OF", "Korea, Republic of", "KOR", 410),
("KW", "KUWAIT", "Kuwait", "KWT", 414),
("KY", "CAYMAN ISLANDS", "Cayman Islands", "CYM", 136),
("KZ", "KAZAKHSTAN", "Kazakhstan", "KAZ", 398),
("LA", "LAO PEOPLE'S DEMOCRATIC REPUBLIC", "Lao People's Democratic Republic", "LAO", 418),
("LB", "LEBANON", "Lebanon", "LBN", 422),
("LC", "SAINT LUCIA", "Saint Lucia", "LCA", 662),
("LI", "LIECHTENSTEIN", "Liechtenstein", "LIE", 438),
("LK", "SRI LANKA", "Sri Lanka", "LKA", 144),
("LR", "LIBERIA", "Liberia", "LBR", 430),
("LS", "LESOTHO", "Lesotho", "LSO", 426),
("LT", "LITHUANIA", "Lithuania", "LTU", 440),
("LU", "LUXEMBOURG", "Luxembourg", "LUX", 442),
("LV", "LATVIA", "Latvia", "LVA", 428),
("LY", "LIBYAN ARAB JAMAHIRIYA", "Libyan Arab Jamahiriya", "LBY", 434),
("MA", "MOROCCO", "Morocco", "MAR", 504),
("MC", "MONACO", "Monaco", "MCO", 492),
("MD", "MOLDOVA, REPUBLIC OF", "Moldova, Republic of", "MDA", 498),
("MG", "MADAGASCAR", "Madagascar", "MDG", 450),
("MH", "MARSHALL ISLANDS", "Marshall Islands", "MHL", 584),
("MK", "MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF", "Macedonia, the Former Yugoslav Republic of", "MKD", 807),
("ML", "MALI", "Mali", "MLI", 466),
("MM", "MYANMAR", "Myanmar", "MMR", 104),
("MN", "MONGOLIA", "Mongolia", "MNG", 496),
("MO", "MACAO", "Macao", "MAC", 446),
("MP", "NORTHERN MARIANA ISLANDS", "Northern Mariana Islands", "MNP", 580),
("MQ", "MARTINIQUE", "Martinique", "MTQ", 474),
("MR", "MAURITANIA", "Mauritania", "MRT", 478),
("MS", "MONTSERRAT", "Montserrat", "MSR", 500),
("MT", "MALTA", "Malta", "MLT", 470),
("MU", "MAURITIUS", "Mauritius", "MUS", 480),
("MV", "MALDIVES", "Maldives", "MDV", 462),
("MW", "MALAWI", "Malawi", "MWI", 454),
("MX", "MEXICO", "Mexico", "MEX", 484),
("MY", "MALAYSIA", "Malaysia", "MYS", 458),
("MZ", "MOZAMBIQUE", "Mozambique", "MOZ", 508),
("NA", "NAMIBIA", "Namibia", "NAM", 516),
("NC", "NEW CALEDONIA", "New Caledonia", "NCL", 540),
("NE", "NIGER", "Niger", "NER", 562),
("NF", "NORFOLK ISLAND", "Norfolk Island", "NFK", 574),
("NG", "NIGERIA", "Nigeria", "NGA", 566),
("NI", "NICARAGUA", "Nicaragua", "NIC", 558),
("NL", "NETHERLANDS", "Netherlands", "NLD", 528),
("NO", "NORWAY", "Norway", "NOR", 578),
("NP", "NEPAL", "Nepal", "NPL", 524),
("NR", "NAURU", "Nauru", "NRU", 520),
("NU", "NIUE", "Niue", "NIU", 570),
("NZ", "NEW ZEALAND", "New Zealand", "NZL", 554),
("OM", "OMAN", "Oman", "OMN", 512),
("PA", "PANAMA", "Panama", "PAN", 591),
("PE", "PERU", "Peru", "PER", 604),
("PF", "FRENCH POLYNESIA", "French Polynesia", "PYF", 258),
("PG", "PAPUA NEW GUINEA", "Papua New Guinea", "PNG", 598),
("PH", "PHILIPPINES", "Philippines", "PHL", 608),
("PK", "PAKISTAN", "Pakistan", "PAK", 586),
("PL", "POLAND", "Poland", "POL", 616),
("PM", "SAINT PIERRE AND MIQUELON", "Saint Pierre and Miquelon", "SPM", 666),
("PN", "PITCAIRN", "Pitcairn", "PCN", 612),
("PR", "PUERTO RICO", "Puerto Rico", "PRI", 630),
("PS", "PALESTINIAN TERRITORY, OCCUPIED", "Palestinian Territory, Occupied", NULL, NULL),
("PT", "PORTUGAL", "Portugal", "PRT", 620),
("PW", "PALAU", "Palau", "PLW", 585),
("PY", "PARAGUAY", "Paraguay", "PRY", 600),
("QA", "QATAR", "Qatar", "QAT", 634),
("RE", "REUNION", "Reunion", "REU", 638),
("RO", "ROMANIA", "Romania", "ROM", 642),
("RU", "RUSSIAN FEDERATION", "Russian Federation", "RUS", 643),
("RW", "RWANDA", "Rwanda", "RWA", 646),
("SA", "SAUDI ARABIA", "Saudi Arabia", "SAU", 682),
("SB", "SOLOMON ISLANDS", "Solomon Islands", "SLB", 90),
("SC", "SEYCHELLES", "Seychelles", "SYC", 690),
("SD", "SUDAN", "Sudan", "SDN", 736),
("SE", "SWEDEN", "Sweden", "SWE", 752),
("SG", "SINGAPORE", "Singapore", "SGP", 702),
("SH", "SAINT HELENA", "Saint Helena", "SHN", 654),
("SI", "SLOVENIA", "Slovenia", "SVN", 705),
("SJ", "SVALBARD AND JAN MAYEN", "Svalbard and Jan Mayen", "SJM", 744),
("SK", "SLOVAKIA", "Slovakia", "SVK", 703),
("SL", "SIERRA LEONE", "Sierra Leone", "SLE", 694),
("SM", "SAN MARINO", "San Marino", "SMR", 674),
("SN", "SENEGAL", "Senegal", "SEN", 686),
("SO", "SOMALIA", "Somalia", "SOM", 706),
("SR", "SURINAME", "Suriname", "SUR", 740),
("ST", "SAO TOME AND PRINCIPE", "Sao Tome and Principe", "STP", 678),
("SV", "EL SALVADOR", "El Salvador", "SLV", 222),
("SY", "SYRIAN ARAB REPUBLIC", "Syrian Arab Republic", "SYR", 760),
("SZ", "SWAZILAND", "Swaziland", "SWZ", 748),
("TC", "TURKS AND CAICOS ISLANDS", "Turks and Caicos Islands", "TCA", 796),
("TD", "CHAD", "Chad", "TCD", 148),
("TF", "FRENCH SOUTHERN TERRITORIES", "French Southern Territories", NULL, NULL),
("TG", "TOGO", "Togo", "TGO", 768),
("TH", "THAILAND", "Thailand", "THA", 764),
("TJ", "TAJIKISTAN", "Tajikistan", "TJK", 762),
("TK", "TOKELAU", "Tokelau", "TKL", 772),
("TL", "TIMOR-LESTE", "Timor-Leste", NULL, NULL),
("TM", "TURKMENISTAN", "Turkmenistan", "TKM", 795),
("TN", "TUNISIA", "Tunisia", "TUN", 788),
("TO", "TONGA", "Tonga", "TON", 776),
("TR", "TURKEY", "Turkey", "TUR", 792),
("TT", "TRINIDAD AND TOBAGO", "Trinidad and Tobago", "TTO", 780),
("TV", "TUVALU", "Tuvalu", "TUV", 798),
("TW", "TAIWAN, PROVINCE OF CHINA", "Taiwan, Province of China", "TWN", 158),
("TZ", "TANZANIA, UNITED REPUBLIC OF", "Tanzania, United Republic of", "TZA", 834),
("UA", "UKRAINE", "Ukraine", "UKR", 804),
("UG", "UGANDA", "Uganda", "UGA", 800),
("UM", "UNITED STATES MINOR OUTLYING ISLANDS", "United States Minor Outlying Islands", NULL, NULL),
("US", "UNITED STATES", "United States", "USA", 840),
("UY", "URUGUAY", "Uruguay", "URY", 858),
("UZ", "UZBEKISTAN", "Uzbekistan", "UZB", 860),
("VA", "HOLY SEE (VATICAN CITY STATE)", "Holy See (Vatican City State)", "VAT", 336),
("VC", "SAINT VINCENT AND THE GRENADINES", "Saint Vincent and the Grenadines", "VCT", 670),
("VE", "VENEZUELA", "Venezuela", "VEN", 862),
("VG", "VIRGIN ISLANDS, BRITISH", "Virgin Islands, British", "VGB", 92),
("VI", "VIRGIN ISLANDS, U.S.", "Virgin Islands, U.s.", "VIR", 850),
("VN", "VIET NAM", "Viet Nam", "VNM", 704),
("VU", "VANUATU", "Vanuatu", "VUT", 548),
("WF", "WALLIS AND FUTUNA", "Wallis and Futuna", "WLF", 876),
("WS", "SAMOA", "Samoa", "WSM", 882),
("YE", "YEMEN", "Yemen", "YEM", 887),
("YT", "MAYOTTE", "Mayotte", NULL, NULL),
("ZA", "SOUTH AFRICA", "South Africa", "ZAF", 710),
("ZM", "ZAMBIA", "Zambia", "ZMB", 894),
("ZW", "ZIMBABWE", "Zimbabwe", "ZWE", 716);

-- --------------------------------------------------------

--
-- Table structure for table `sb_flood`
--

DROP TABLE IF EXISTS `<DB_PREFIX>sb_flood`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_flood` (
  `ip` varchar(18) NOT NULL DEFAULT '',
  `time` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sb_logaccess`
--

DROP TABLE IF EXISTS `<DB_PREFIX>sb_logaccess`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_logaccess` (
  `id` int(11) NOT NULL,
  `logaccess_type` varchar(10) NOT NULL,
  `logaccess_date` int(10) NOT NULL,
  `logaccess_user` varchar(20) NOT NULL,
  `logaccess_event` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table Activity log';

-- --------------------------------------------------------

--
-- Table structure for table `sb_menu`
--

DROP TABLE IF EXISTS `<DB_PREFIX>sb_menu`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_menu` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `tag` varchar(50) NOT NULL COMMENT 'Smarty variable',
  `pages` varchar(255) NOT NULL COMMENT 'Pages IDs (separate by | )',
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sb_menu`
--

INSERT INTO `<DB_PREFIX>sb_menu` (`id`, `name`, `tag`, `pages`, `active`) VALUES
(1, 'Main menu', 'main_menu', '1|2|4|3', 1);


-- --------------------------------------------------------

--
-- Table structure for table `sb_faq`
--

DROP TABLE IF EXISTS `<DB_PREFIX>sb_faq`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_faq` (
  `id` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `question` varchar(255) NOT NULL,
  `response` text NOT NULL,
  `sort` int(11) NOT NULL DEFAULT 0,
  `active` tinyint(4) NOT NULL DEFAULT 1 COMMENT '0: inactive, 1: active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sb_faq_category`
--

DROP TABLE IF EXISTS `<DB_PREFIX>sb_faq_category`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_faq_category` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT 1 COMMENT '0: inactive, 1: active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sb_messages`
--

DROP TABLE IF EXISTS `<DB_PREFIX>sb_messages`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` int(11) NOT NULL,
  `read_at` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sb_news`
--

DROP TABLE IF EXISTS `<DB_PREFIX>sb_news`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_news` (
  `id` bigint(20) NOT NULL,
  `catid` varchar(50) NOT NULL COMMENT 'Categories',
  `viewed` bigint(20) NOT NULL,
  `title` text NOT NULL,
  `subtitle` text NOT NULL,
  `desc_short` text NOT NULL,
  `desc_full` text NOT NULL,
  `image` varchar(200) DEFAULT NULL,
  `date` varchar(10) NOT NULL,
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sb_news`
--

INSERT INTO `<DB_PREFIX>sb_news` (`id`, `catid`, `title`, `subtitle`, `desc_short`, `desc_full`, `image`, `date`, `active`) VALUES
(1, '1', '[fr]Lorem Ipsum callum[/fr]', '[fr][/fr]', '[fr]Sed ac luctus mauris. Nullam semper tortor nec orci sagittis, vel vestibulum metus consectetur. Suspendisse tincidunt, nunc ut pharetra dapibus, erat sapien aliquet arcu, vel egestas urna metus vitae libero. Aenean sodales eros vitae dui ornare posuere ac non turpis. Maecenas vel diam tincidunt, malesuada dolor congue, laoreet lacus. Vestibulum consectetur massa eget dui vehicula maximus. Maecenas ut sodales velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ut mauris massa. Proin scelerisque fringilla feugiat. Duis feugiat eleifend nunc sit amet convallis. Vestibulum vel nunc sit amet quam varius rhoncus. Mauris in sapien id elit sodales faucibus. Proin augue mauris, dapibus at pellentesque quis, iaculis in arcu.[/fr]', '[fr]Sed ac luctus mauris. Nullam semper tortor nec orci sagittis, vel vestibulum metus consectetur. Suspendisse tincidunt, nunc ut pharetra dapibus, erat sapien aliquet arcu, vel egestas urna metus vitae libero. Aenean sodales eros vitae dui ornare posuere ac non turpis. Maecenas vel diam tincidunt, malesuada dolor congue, laoreet lacus. Vestibulum consectetur massa eget dui vehicula maximus. Maecenas ut sodales velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ut mauris massa. Proin scelerisque fringilla feugiat. Duis feugiat eleifend nunc sit amet convallis. Vestibulum vel nunc sit amet quam varius rhoncus. Mauris in sapien id elit sodales faucibus. Proin augue mauris, dapibus at pellentesque quis, iaculis in arcu.&lt;br /&gt; &lt;br /&gt; Sed ac luctus mauris. Nullam semper tortor nec orci sagittis, vel vestibulum metus consectetur. Suspendisse tincidunt, nunc ut pharetra dapibus, erat sapien aliquet arcu, vel egestas urna metus vitae libero. Aenean sodales eros vitae dui ornare posuere ac non turpis. Maecenas vel diam tincidunt, malesuada dolor congue, laoreet lacus. Vestibulum consectetur massa eget dui vehicula maximus. Maecenas ut sodales velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ut mauris massa. Proin scelerisque fringilla feugiat. Duis feugiat eleifend nunc sit amet convallis. Vestibulum vel nunc sit amet quam varius rhoncus. Mauris in sapien id elit sodales faucibus. Proin augue mauris, dapibus at pellentesque quis, iaculis in arcu.&lt;br /&gt; &lt;br /&gt; Sed ac luctus mauris. Nullam semper tortor nec orci sagittis, vel vestibulum metus consectetur. Suspendisse tincidunt, nunc ut pharetra dapibus, erat sapien aliquet arcu, vel egestas urna metus vitae libero. Aenean sodales eros vitae dui ornare posuere ac non turpis. Maecenas vel diam tincidunt, malesuada dolor congue, laoreet lacus. Vestibulum consectetur massa eget dui vehicula maximus. Maecenas ut sodales velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ut mauris massa. Proin scelerisque fringilla feugiat. Duis feugiat eleifend nunc sit amet convallis. Vestibulum vel nunc sit amet quam varius rhoncus. Mauris in sapien id elit sodales faucibus. Proin augue mauris, dapibus at pellentesque quis, iaculis in arcu.[/fr]', 'news-5.jpg', '2017-05-18', 1),
(2, '1', '[fr]Curabitur sed nunc placerat[/fr]', '[fr][/fr]', '[fr]Sed ac luctus mauris. Nullam semper tortor nec orci sagittis, vel vestibulum metus consectetur. Suspendisse tincidunt, nunc ut pharetra dapibus, erat sapien aliquet arcu, vel egestas urna metus vitae libero. Aenean sodales eros vitae dui ornare posuere ac non turpis. Maecenas vel diam tincidunt, malesuada dolor congue, laoreet lacus. Vestibulum consectetur massa eget dui vehicula maximus. Maecenas ut sodales velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ut mauris massa. Proin scelerisque fringilla feugiat. Duis feugiat eleifend nunc sit amet convallis. Vestibulum vel nunc sit amet quam varius rhoncus. Mauris in sapien id elit sodales faucibus. Proin augue mauris, dapibus at pellentesque quis, iaculis in arcu.[/fr]', '[fr]Sed ac luctus mauris. Nullam semper tortor nec orci sagittis, vel vestibulum metus consectetur. Suspendisse tincidunt, nunc ut pharetra dapibus, erat sapien aliquet arcu, vel egestas urna metus vitae libero. Aenean sodales eros vitae dui ornare posuere ac non turpis. Maecenas vel diam tincidunt, malesuada dolor congue, laoreet lacus. Vestibulum consectetur massa eget dui vehicula maximus. Maecenas ut sodales velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ut mauris massa. Proin scelerisque fringilla feugiat. Duis feugiat eleifend nunc sit amet convallis. Vestibulum vel nunc sit amet quam varius rhoncus. Mauris in sapien id elit sodales faucibus. Proin augue mauris, dapibus at pellentesque quis, iaculis in arcu.&lt;br /&gt; &lt;br /&gt; Sed ac luctus mauris. Nullam semper tortor nec orci sagittis, vel vestibulum metus consectetur. Suspendisse tincidunt, nunc ut pharetra dapibus, erat sapien aliquet arcu, vel egestas urna metus vitae libero. Aenean sodales eros vitae dui ornare posuere ac non turpis. Maecenas vel diam tincidunt, malesuada dolor congue, laoreet lacus. Vestibulum consectetur massa eget dui vehicula maximus. Maecenas ut sodales velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ut mauris massa. Proin scelerisque fringilla feugiat. Duis feugiat eleifend nunc sit amet convallis. Vestibulum vel nunc sit amet quam varius rhoncus. Mauris in sapien id elit sodales faucibus. Proin augue mauris, dapibus at pellentesque quis, iaculis in arcu.&lt;br /&gt; &lt;br /&gt; Sed ac luctus mauris. Nullam semper tortor nec orci sagittis, vel vestibulum metus consectetur. Suspendisse tincidunt, nunc ut pharetra dapibus, erat sapien aliquet arcu, vel egestas urna metus vitae libero. Aenean sodales eros vitae dui ornare posuere ac non turpis. Maecenas vel diam tincidunt, malesuada dolor congue, laoreet lacus. Vestibulum consectetur massa eget dui vehicula maximus. Maecenas ut sodales velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ut mauris massa. Proin scelerisque fringilla feugiat. Duis feugiat eleifend nunc sit amet convallis. Vestibulum vel nunc sit amet quam varius rhoncus. Mauris in sapien id elit sodales faucibus. Proin augue mauris, dapibus at pellentesque quis, iaculis in arcu.[/fr]', 'news-4.jpg', '2017-05-15', 1),
(3, '1', '[fr]Praesent consequat sit amet[/fr]', '[fr][/fr]', '[fr]Sed ac luctus mauris. Nullam semper tortor nec orci sagittis, vel vestibulum metus consectetur. Suspendisse tincidunt, nunc ut pharetra dapibus, erat sapien aliquet arcu, vel egestas urna metus vitae libero. Aenean sodales eros vitae dui ornare posuere ac non turpis. Maecenas vel diam tincidunt, malesuada dolor congue, laoreet lacus. Vestibulum consectetur massa eget dui vehicula maximus. Maecenas ut sodales velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ut mauris massa. Proin scelerisque fringilla feugiat. Duis feugiat eleifend nunc sit amet convallis. Vestibulum vel nunc sit amet quam varius rhoncus. Mauris in sapien id elit sodales faucibus. Proin augue mauris, dapibus at pellentesque quis, iaculis in arcu.[/fr]', '[fr]Sed ac luctus mauris. Nullam semper tortor nec orci sagittis, vel vestibulum metus consectetur. Suspendisse tincidunt, nunc ut pharetra dapibus, erat sapien aliquet arcu, vel egestas urna metus vitae libero. Aenean sodales eros vitae dui ornare posuere ac non turpis. Maecenas vel diam tincidunt, malesuada dolor congue, laoreet lacus. Vestibulum consectetur massa eget dui vehicula maximus. Maecenas ut sodales velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ut mauris massa. Proin scelerisque fringilla feugiat. Duis feugiat eleifend nunc sit amet convallis. Vestibulum vel nunc sit amet quam varius rhoncus. Mauris in sapien id elit sodales faucibus. Proin augue mauris, dapibus at pellentesque quis, iaculis in arcu.&lt;br /&gt; &lt;br /&gt; Sed ac luctus mauris. Nullam semper tortor nec orci sagittis, vel vestibulum metus consectetur. Suspendisse tincidunt, nunc ut pharetra dapibus, erat sapien aliquet arcu, vel egestas urna metus vitae libero. Aenean sodales eros vitae dui ornare posuere ac non turpis. Maecenas vel diam tincidunt, malesuada dolor congue, laoreet lacus. Vestibulum consectetur massa eget dui vehicula maximus. Maecenas ut sodales velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ut mauris massa. Proin scelerisque fringilla feugiat. Duis feugiat eleifend nunc sit amet convallis. Vestibulum vel nunc sit amet quam varius rhoncus. Mauris in sapien id elit sodales faucibus. Proin augue mauris, dapibus at pellentesque quis, iaculis in arcu.&lt;br /&gt; &lt;br /&gt; Sed ac luctus mauris. Nullam semper tortor nec orci sagittis, vel vestibulum metus consectetur. Suspendisse tincidunt, nunc ut pharetra dapibus, erat sapien aliquet arcu, vel egestas urna metus vitae libero. Aenean sodales eros vitae dui ornare posuere ac non turpis. Maecenas vel diam tincidunt, malesuada dolor congue, laoreet lacus. Vestibulum consectetur massa eget dui vehicula maximus. Maecenas ut sodales velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ut mauris massa. Proin scelerisque fringilla feugiat. Duis feugiat eleifend nunc sit amet convallis. Vestibulum vel nunc sit amet quam varius rhoncus. Mauris in sapien id elit sodales faucibus. Proin augue mauris, dapibus at pellentesque quis, iaculis in arcu.[/fr]', 'news-3.jpg', '2017-05-10', 1),
(4, '1', '[fr]Fusce dictum quam nisl[/fr]', '[fr][/fr]', '[fr]Sed ac luctus mauris. Nullam semper tortor nec orci sagittis, vel vestibulum metus consectetur. Suspendisse tincidunt, nunc ut pharetra dapibus, erat sapien aliquet arcu, vel egestas urna metus vitae libero. Aenean sodales eros vitae dui ornare posuere ac non turpis. Maecenas vel diam tincidunt, malesuada dolor congue, laoreet lacus. Vestibulum consectetur massa eget dui vehicula maximus. Maecenas ut sodales velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ut mauris massa. Proin scelerisque fringilla feugiat. Duis feugiat eleifend nunc sit amet convallis. Vestibulum vel nunc sit amet quam varius rhoncus. Mauris in sapien id elit sodales faucibus. Proin augue mauris, dapibus at pellentesque quis, iaculis in arcu.[/fr]', '[fr]Sed ac luctus mauris. Nullam semper tortor nec orci sagittis, vel vestibulum metus consectetur. Suspendisse tincidunt, nunc ut pharetra dapibus, erat sapien aliquet arcu, vel egestas urna metus vitae libero. Aenean sodales eros vitae dui ornare posuere ac non turpis. Maecenas vel diam tincidunt, malesuada dolor congue, laoreet lacus. Vestibulum consectetur massa eget dui vehicula maximus. Maecenas ut sodales velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ut mauris massa. Proin scelerisque fringilla feugiat. Duis feugiat eleifend nunc sit amet convallis. Vestibulum vel nunc sit amet quam varius rhoncus. Mauris in sapien id elit sodales faucibus. Proin augue mauris, dapibus at pellentesque quis, iaculis in arcu.&lt;br /&gt; &lt;br /&gt; Sed ac luctus mauris. Nullam semper tortor nec orci sagittis, vel vestibulum metus consectetur. Suspendisse tincidunt, nunc ut pharetra dapibus, erat sapien aliquet arcu, vel egestas urna metus vitae libero. Aenean sodales eros vitae dui ornare posuere ac non turpis. Maecenas vel diam tincidunt, malesuada dolor congue, laoreet lacus. Vestibulum consectetur massa eget dui vehicula maximus. Maecenas ut sodales velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ut mauris massa. Proin scelerisque fringilla feugiat. Duis feugiat eleifend nunc sit amet convallis. Vestibulum vel nunc sit amet quam varius rhoncus. Mauris in sapien id elit sodales faucibus. Proin augue mauris, dapibus at pellentesque quis, iaculis in arcu.&lt;br /&gt; &lt;br /&gt; Sed ac luctus mauris. Nullam semper tortor nec orci sagittis, vel vestibulum metus consectetur. Suspendisse tincidunt, nunc ut pharetra dapibus, erat sapien aliquet arcu, vel egestas urna metus vitae libero. Aenean sodales eros vitae dui ornare posuere ac non turpis. Maecenas vel diam tincidunt, malesuada dolor congue, laoreet lacus. Vestibulum consectetur massa eget dui vehicula maximus. Maecenas ut sodales velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ut mauris massa. Proin scelerisque fringilla feugiat. Duis feugiat eleifend nunc sit amet convallis. Vestibulum vel nunc sit amet quam varius rhoncus. Mauris in sapien id elit sodales faucibus. Proin augue mauris, dapibus at pellentesque quis, iaculis in arcu.[/fr]', 'news-2.jpg', '2017-04-28', 1),
(5, '1', '[fr]Quisque nec congue diam[/fr]', '[fr][/fr]', '[fr]Sed ac luctus mauris. Nullam semper tortor nec orci sagittis, vel vestibulum metus consectetur. Suspendisse tincidunt, nunc ut pharetra dapibus, erat sapien aliquet arcu, vel egestas urna metus vitae libero. Aenean sodales eros vitae dui ornare posuere ac non turpis. Maecenas vel diam tincidunt, malesuada dolor congue, laoreet lacus. Vestibulum consectetur massa eget dui vehicula maximus. Maecenas ut sodales velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ut mauris massa. Proin scelerisque fringilla feugiat. Duis feugiat eleifend nunc sit amet convallis. Vestibulum vel nunc sit amet quam varius rhoncus. Mauris in sapien id elit sodales faucibus. Proin augue mauris, dapibus at pellentesque quis, iaculis in arcu.[/fr]', '[fr]Sed ac luctus mauris. Nullam semper tortor nec orci sagittis, vel vestibulum metus consectetur. Suspendisse tincidunt, nunc ut pharetra dapibus, erat sapien aliquet arcu, vel egestas urna metus vitae libero. Aenean sodales eros vitae dui ornare posuere ac non turpis. Maecenas vel diam tincidunt, malesuada dolor congue, laoreet lacus. Vestibulum consectetur massa eget dui vehicula maximus. Maecenas ut sodales velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ut mauris massa. Proin scelerisque fringilla feugiat. Duis feugiat eleifend nunc sit amet convallis. Vestibulum vel nunc sit amet quam varius rhoncus. Mauris in sapien id elit sodales faucibus. Proin augue mauris, dapibus at pellentesque quis, iaculis in arcu.&lt;br /&gt; &lt;br /&gt; Sed ac luctus mauris. Nullam semper tortor nec orci sagittis, vel vestibulum metus consectetur. Suspendisse tincidunt, nunc ut pharetra dapibus, erat sapien aliquet arcu, vel egestas urna metus vitae libero. Aenean sodales eros vitae dui ornare posuere ac non turpis. Maecenas vel diam tincidunt, malesuada dolor congue, laoreet lacus. Vestibulum consectetur massa eget dui vehicula maximus. Maecenas ut sodales velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ut mauris massa. Proin scelerisque fringilla feugiat. Duis feugiat eleifend nunc sit amet convallis. Vestibulum vel nunc sit amet quam varius rhoncus. Mauris in sapien id elit sodales faucibus. Proin augue mauris, dapibus at pellentesque quis, iaculis in arcu.&lt;br /&gt; &lt;br /&gt; Sed ac luctus mauris. Nullam semper tortor nec orci sagittis, vel vestibulum metus consectetur. Suspendisse tincidunt, nunc ut pharetra dapibus, erat sapien aliquet arcu, vel egestas urna metus vitae libero. Aenean sodales eros vitae dui ornare posuere ac non turpis. Maecenas vel diam tincidunt, malesuada dolor congue, laoreet lacus. Vestibulum consectetur massa eget dui vehicula maximus. Maecenas ut sodales velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ut mauris massa. Proin scelerisque fringilla feugiat. Duis feugiat eleifend nunc sit amet convallis. Vestibulum vel nunc sit amet quam varius rhoncus. Mauris in sapien id elit sodales faucibus. Proin augue mauris, dapibus at pellentesque quis, iaculis in arcu.[/fr]', 'news-1.jpg', '2017-03-21', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sb_news_category`
--

DROP TABLE IF EXISTS `<DB_PREFIX>sb_news_category`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_news_category` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `subtitle` text,
  `tpl_list` text,
  `tpl_single` text,
  `module_show` varchar(50) DEFAULT NULL COMMENT 'normal,masonry,...',
  `module_show_masonry` int(11) DEFAULT NULL COMMENT 'columns width (pixels)',
  `photo` varchar(255) DEFAULT NULL,
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `sort` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sb_news_category`
--

INSERT INTO `<DB_PREFIX>sb_news_category` (`id`, `title`, `subtitle`, `tpl_list`, `tpl_single`, `module_show`, `module_show_masonry`, `photo`, `active`, `sort`) VALUES
(1, '[fr]Sports[/fr]', '[fr][/fr]', NULL, NULL, 'float', 200, '', 1, 0);


-- --------------------------------------------------------

--
-- Table structure for table `sb_news_settings`
--

DROP TABLE IF EXISTS `<DB_PREFIX>sb_news_settings`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_news_settings` (
  `id` int(11) NOT NULL,
  `item_per_page` int(11) NOT NULL COMMENT 'article par page (categorie)',
  `module_start` tinyint(4) NOT NULL COMMENT '0: liste des categories, 1: categorie specifique',
  `catid` text NOT NULL COMMENT 'Demarrage par ces categories',
  `catid_module_show` int(11) DEFAULT NULL COMMENT 'Catégorie affichage principal',
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
  `comments` varchar(20) DEFAULT NULL,
  `comments_user` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sb_news_settings`
--

INSERT INTO `<DB_PREFIX>sb_news_settings` (`id`, `item_per_page`, `module_start`, `catid`, `breadcrumb`, `title_h1`, `title_h2`, `theme_view_cat`, `theme_view_list`, `theme_view_single`, `other_news`, `other_news_per_page`, `other_news_title`, `other_news_type`, `news_next_prev`) VALUES
(1, 10, 1, 1, 1, 1, 1, 'index', 'index', 'index', 1, 4, 'Autres articles', 'latest', 'arrow');


-- --------------------------------------------------------

--
-- Table structure for table `sb_pages`
--

DROP TABLE IF EXISTS `<DB_PREFIX>sb_pages`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_pages` (
  `id` int(11) NOT NULL,
  `menu` varchar(100) NOT NULL COMMENT 'Texte du menu',
  `title` varchar(255) NOT NULL COMMENT 'Titre de la page',
  `content` text NOT NULL,
  `seo_url` text NOT NULL,
  `url_custom` varchar(255) DEFAULT NULL,
  `seo_keywords` text NOT NULL COMMENT 'Mots cles additionnels de la page',
  `seo_description` varchar(155) NOT NULL COMMENT 'Meta description additionnels de la page',
  `module_view` varchar(50) NOT NULL COMMENT 'Module view for the current page',
  `theme_view` varchar(50) NOT NULL COMMENT 'Theme view defined by CMS theme',
  `various_view` varchar(100) DEFAULT NULL COMMENT 'Additional HTML file',
  `headpage` text NOT NULL COMMENT 'Code entete page theme (si declare dans config)',
  `photo` text COMMENT 'Banner',
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `sort` int(11) NOT NULL COMMENT 'Tri des pages / menus'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='Pages libres du site';

--
-- Dumping data for table `sb_pages`
--

INSERT INTO `<DB_PREFIX>sb_pages` (`id`, `menu`, `title`, `content`, `seo_url`, `url_custom`, `seo_keywords`, `seo_description`, `module_view`, `theme_view`, `various_view`, `headpage`, `photo`, `active`, `sort`) VALUES
(1, '[fr]Accueil[/fr]', '[fr]Accueil[/fr]', '[fr]&lt;p&gt;Thank you for using SBUIADMIN CMS. This is your homepage, so please change this text to be what you want.&lt;/p&gt;  &lt;ul&gt; 	&lt;li&gt;&lt;a href=&quot;#&quot;&gt;SBUIADMIN CMS Documentation&lt;/a&gt;  	&lt;ul&gt; 		&lt;li&gt;&lt;a href=&quot;#&quot;&gt;How to Create a SBUIADMIN Theme&lt;/a&gt;&lt;/li&gt; 	&lt;/ul&gt; 	&lt;/li&gt; 	&lt;li&gt;&lt;a href=&quot;#&quot;&gt;SBUIADMIN Support Forums&lt;/a&gt;&lt;/li&gt; &lt;/ul&gt;  &lt;h2&gt;Header 2&lt;/h2&gt;  &lt;p&gt;Lorem ipsum &lt;em&gt;dolor sit amet&lt;/em&gt;, &lt;strong&gt;consectetur adipiscing elit&lt;/strong&gt;. Donec &lt;code&gt;this is code&lt;/code&gt; venenatis augue. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Integer vulputate pretium augue.&lt;/p&gt;  &lt;h3&gt;Header 3&lt;/h3&gt;  &lt;pre&gt; &lt;code class=&quot;language-css&quot;&gt;#header h1 a {  	display: block;  	width: 300px;  	height: 80px;  }&lt;/code&gt;&lt;/pre&gt;  &lt;h4&gt;Header 4&lt;/h4&gt;  &lt;ol&gt; 	&lt;li&gt;Lorem ipsum dolor sit amet&lt;/li&gt; 	&lt;li&gt;Consectetur adipiscing elit&lt;/li&gt; 	&lt;li&gt;Donec ut est risus, placerat venenatis augue&lt;/li&gt; &lt;/ol&gt;  &lt;blockquote&gt;A blockquote. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec ut est risus, placerat venenatis augue. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.&lt;/blockquote&gt;[/fr]', '', '', 'mon,site,page,accueil', 'La page Accueil de mon site', '', 'index', 'page-features.html', '1', '', 1, 0),
(2, '[fr]News[/fr]', '[fr]News[/fr]', '[fr][/fr]', 'news', '', '', '', 'news', 'index', '', '', '', 1, 0),
(3, '[fr]Contact[/fr]', '[fr]Contact[/fr]', '[fr][/fr]', 'contact', '', '', '', '', 'index-contact', NULL, '', '', 1, 0),
(4, '[fr]Login[/fr]', '[fr]Login[/fr]', '[fr]&lt;h1&gt;Module USER&lt;/h1&gt;  &lt;p&gt;&amp;nbsp;&lt;/p&gt;  &lt;h3&gt;Utilisation du module USER dans votre code&lt;/h3&gt;  &lt;ol&gt; 	&lt;li&gt;&amp;nbsp;Editeur WYSIWYG : &lt;strong style=&quot;color: red;&quot;&gt;&amp;lsqb;CS name=sbuser icontext=1 menu=li menu_class=mymenuclass href_class=myhrefclass&amp;rsqb;&lt;/strong&gt;&lt;/li&gt; 	&lt;li&gt;&amp;nbsp;Smarty Templates : &lt;strong style=&quot;color: red;&quot;&gt;{insert name=&amp;quot;sbDoShortcode&amp;quot; code=&amp;quot;&amp;lsqb;CS name=sbuser icontext=1 menu=li menu_class=mymenuclass href_class=myhrefclass&amp;rsqb;&amp;quot;}&lt;/strong&gt;&lt;/li&gt; &lt;/ol&gt;  &lt;h3&gt;Les param&amp;egrave;tres&lt;/h3&gt;  &lt;ul&gt; 	&lt;li&gt;name : &lt;strong style=&quot;color: red;&quot;&gt;sbuser &lt;/strong&gt;(tout le temps)&lt;/li&gt; 	&lt;li&gt;icontext : &lt;strong style=&quot;color: red;&quot;&gt;1&lt;/strong&gt; (activer le texte et les ic&amp;ocirc;nes - optionnel)&lt;/li&gt; 	&lt;li&gt;menu : &lt;strong style=&quot;color: red;&quot;&gt;li&lt;/strong&gt; (int&amp;eacute;grer &amp;agrave; un menu ou liste &amp;agrave; puce - optionnel)&lt;/li&gt; 	&lt;li&gt;menu_class : &lt;strong style=&quot;color: red;&quot;&gt;class_name&lt;/strong&gt; (ajouter une classe &amp;agrave; la balise LI - optionnel)&lt;/li&gt; 	&lt;li&gt;href_class : &lt;strong style=&quot;color: red;&quot;&gt;class_name&lt;/strong&gt; (ajouter une classe &amp;agrave; la balise A&lt;/li&gt; &lt;/ul&gt;  &lt;h3&gt;Module URLs&lt;/h3&gt;  &lt;ul&gt; 	&lt;li&gt;&lt;strong style=&quot;color: red;&quot;&gt;http://www.votresite.com/index.php?p=user&lt;/strong&gt; (mode rewrite d&amp;eacute;sactiv&amp;eacute;)&lt;/li&gt; 	&lt;li&gt;&lt;strong style=&quot;color: red;&quot;&gt;http://www.votresite.com/user&lt;/strong&gt; (mode rewrite activ&amp;eacute;)&lt;/li&gt; &lt;/ul&gt;  &lt;h3&gt;Exemples&lt;/h3&gt; &amp;lsqb;CS name=sbuser href_class=myhrefclass1&amp;rsqb;&lt;br /&gt; [CS name=sbuser href_class=myhrefclass1]&lt;br /&gt; &lt;br /&gt; &amp;lsqb;CS name=sbuser icontext=1 href_class=myhrefclass2&amp;rsqb;&lt;br /&gt; [CS name=sbuser icontext=1 href_class=myhrefclass2]&lt;br /&gt; &lt;br /&gt; &amp;lsqb;CS name=sbuser icontext=1 menu=li menu_class=mymenuclass3 href_class=myhrefclass3&amp;rsqb;&lt;br /&gt; [CS name=sbuser icontext=1 menu=li menu_class=mymenuclass3 href_class=myhrefclass3]&lt;br /&gt; &amp;nbsp;[/fr]', 'login', '', '', '', 'user', 'index', '', '', '', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `sb_sandbox`
--

DROP TABLE IF EXISTS `<DB_PREFIX>sb_sandbox`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_sandbox` (
  `id` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `yourname` varchar(255) NOT NULL DEFAULT '',
  `montant` varchar(50) NOT NULL DEFAULT '',
  `seo_url` varchar(255) NOT NULL DEFAULT '',
  `country` varchar(100) NOT NULL DEFAULT '',
  `dob` varchar(20) NOT NULL DEFAULT '',
  `color` varchar(20) NOT NULL DEFAULT '',
  `tags` varchar(255) NOT NULL DEFAULT '',
  `pdf` varchar(255) NOT NULL DEFAULT '',
  `photo` varchar(255) NOT NULL DEFAULT '',
  `video` varchar(255) NOT NULL DEFAULT '',
  `option_one` tinyint(1) NOT NULL DEFAULT 0,
  `option_two` tinyint(1) NOT NULL DEFAULT 0,
  `option_three` tinyint(1) NOT NULL DEFAULT 0,
  `type` varchar(5) NOT NULL DEFAULT '',
  `selection` varchar(50) NOT NULL DEFAULT '',
  `comment` text,
  `comment_editor1` longtext,
  `comment_editor2` longtext,
  `comment_editor3` longtext,
  `page_builder_content` longtext,
  `sort` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Table SANDBOX (kitchen-sink des champs du formulaire)';

--
-- Dumping data for table `sb_sandbox`
--

INSERT INTO `<DB_PREFIX>sb_sandbox` (`id`, `active`, `yourname`, `montant`, `seo_url`, `country`, `dob`, `color`, `tags`, `pdf`, `photo`, `video`, `option_one`, `option_two`, `option_three`, `type`, `selection`, `comment`, `comment_editor1`, `comment_editor2`, `comment_editor3`, `page_builder_content`, `sort`) VALUES
(1, 1, 'Rhona O. Ruiz', '150.00', 'rhona-ruiz', 'France', '', '#2563eb', 'demo,exemple', '', '', '', 1, 0, 1, '1', 'Selection 1', '', '', '', '', '', 0),
(2, 1, 'Madison M. Orr', '89.00', 'madison-orr', 'France', '', '#10b981', 'demo', '', '', '', 0, 1, 0, '2', 'Selection 2', '', '', '', '', '', 1),
(3, 1, 'Penelope M. Jones', '210.50', 'penelope-jones', 'France', '', '#f59e0b', '', '', '', '', 1, 1, 0, '3', 'Selection 3', '', '', '', '', '', 2),
(4, 1, 'Flavia Z. Slater', '45.00', 'flavia-slater', 'France', '', '#ef4444', '', '', '', '', 0, 0, 0, '1', '', '', '', '', '', '', 3),
(5, 0, 'Beverly M. Pratt', '12.00', 'beverly-pratt', 'France', '', '#8b5cf6', '', '', '', '', 0, 0, 1, '2', '', '', '', '', '', '', 4);

-- --------------------------------------------------------

--
-- Table structure for table `sb_sessions`
--

DROP TABLE IF EXISTS `<DB_PREFIX>sb_sessions`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_sessions` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `hash` varchar(32) NOT NULL,
  `expiredate` datetime NOT NULL,
  `ip` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table Session';

-- --------------------------------------------------------

--
-- Table structure for table `sb_shop_accounting_export`
--

DROP TABLE IF EXISTS `<DB_PREFIX>sb_shop_accounting_export`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_shop_accounting_export` (
  `id` int(11) NOT NULL,
  `plugin_id` varchar(50) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `config` text NOT NULL COMMENT 'JSON propre au plugin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sb_shop_accounting_log`
--

DROP TABLE IF EXISTS `<DB_PREFIX>sb_shop_accounting_log`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_shop_accounting_log` (
  `id` int(11) NOT NULL,
  `oid` int(11) NOT NULL,
  `plugin_id` varchar(50) NOT NULL,
  `status` enum('succes','echec','en_attente') NOT NULL DEFAULT 'en_attente',
  `message` text NOT NULL,
  `sent_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sb_shop_category`
--

DROP TABLE IF EXISTS `<DB_PREFIX>sb_shop_category`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_shop_category` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `photo` varchar(200) NOT NULL DEFAULT '',
  `sort` int(11) NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sb_shop_config`
--

DROP TABLE IF EXISTS `<DB_PREFIX>sb_shop_config`;
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

--
-- Dumping data for table `sb_shop_config`
--

INSERT INTO `<DB_PREFIX>sb_shop_config` (`id`, `is_tva`, `currency`, `currency_text`, `currency_position`, `n_decimals`, `per_page`, `invoice_prefix`, `invoice_format_plugin`, `unique_code_root`, `unique_code_key`, `unique_code_pattern`, `unique_code_length`) VALUES
(1, 1, 'EUR', '€', 1, 2, 12, 'FAC', 'facturx', '', '', 'alphanumeric', 16);

-- --------------------------------------------------------

--
-- Table structure for table `sb_shop_discount`
--

DROP TABLE IF EXISTS `<DB_PREFIX>sb_shop_discount`;
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

-- --------------------------------------------------------

--
-- Table structure for table `sb_shop_email`
--

DROP TABLE IF EXISTS `<DB_PREFIX>sb_shop_email`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_shop_email` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `subject_customer` varchar(255) NOT NULL DEFAULT '',
  `body_customer` text NOT NULL,
  `subject_admin` varchar(255) NOT NULL DEFAULT '',
  `body_admin` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sb_shop_order`
--

DROP TABLE IF EXISTS `<DB_PREFIX>sb_shop_order`;
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

-- --------------------------------------------------------

--
-- Table structure for table `sb_shop_order_detail`
--

DROP TABLE IF EXISTS `<DB_PREFIX>sb_shop_order_detail`;
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

-- --------------------------------------------------------

--
-- Table structure for table `sb_shop_payment`
--

DROP TABLE IF EXISTS `<DB_PREFIX>sb_shop_payment`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_shop_payment` (
  `id` int(11) NOT NULL,
  `plugin_id` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `production` tinyint(1) NOT NULL DEFAULT 0,
  `sort` int(11) NOT NULL DEFAULT 0,
  `config` text NOT NULL COMMENT 'JSON clés API'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sb_shop_product`
--

DROP TABLE IF EXISTS `<DB_PREFIX>sb_shop_product`;
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

-- --------------------------------------------------------

--
-- Table structure for table `sb_shop_transport`
--

DROP TABLE IF EXISTS `<DB_PREFIX>sb_shop_transport`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_shop_transport` (
  `id` int(11) NOT NULL,
  `plugin_id` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `sort` int(11) NOT NULL DEFAULT 0,
  `config` text NOT NULL COMMENT 'JSON propre au plugin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sb_shop_unique`
--

DROP TABLE IF EXISTS `<DB_PREFIX>sb_shop_unique`;
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

-- --------------------------------------------------------

-- Table structure for table `sb_slider`
--

DROP TABLE IF EXISTS `<DB_PREFIX>sb_slider`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_slider` (
  `id` int(11) NOT NULL,
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
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sb_slider`
--

INSERT INTO `<DB_PREFIX>sb_slider` (`id`, `title`, `jquery`, `responsive`, `auto`, `pause`, `speed`, `randomstart`, `mode`, `preloadimages`, `controls`, `autocontrols`, `autohover`, `captions`, `adaptiveheight`, `adaptiveheightspeed`, `slidemargin`, `video`, `usecss`, `pager`, `pagertype`, `active`) VALUES
(1, 'Mon slider', 0, 1, 1, 4000, 500, 1, 'horizontal', 'visible', 1, 0, 0, 0, 0, 500, 0, 0, 1, 1, 'full', 1);


-- --------------------------------------------------------

--
-- Table structure for table `sb_slider_photos`
--

DROP TABLE IF EXISTS `<DB_PREFIX>sb_slider_photos`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_slider_photos` (
  `id` int(11) NOT NULL,
  `sid` int(11) NOT NULL COMMENT 'Slider id',
  `title` varchar(255) NOT NULL COMMENT 'Nom de la photo',
  `photo` varchar(255) NOT NULL COMMENT 'Nom de l''image physique',
  `type` varchar(10) NOT NULL COMMENT 'video, photo',
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `sort` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sb_slider_photos`
--

INSERT INTO `<DB_PREFIX>sb_slider_photos` (`id`, `sid`, `title`, `photo`, `type`, `active`, `sort`) VALUES
(1, 1, 'Mont Saint Michel', 'slider-5.jpg', 'photo', 1, 1),
(2, 1, 'New York', 'slider-3.jpg', 'photo', 1, 2),
(3, 1, 'Rafting', 'slider-2.jpg', 'photo', 1, 3),
(4, 1, 'Mon chalet &agrave; la montagne', 'slider-1.jpg', 'photo', 1, 4),
(5, 1, 'Miam Miam', 'slider-4.jpg', 'photo', 1, 5);


-- --------------------------------------------------------

--
-- Table structure for table `sb_tabbs`
--

DROP TABLE IF EXISTS `<DB_PREFIX>sb_tabbs`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_tabbs` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sb_tabbs_tab`
--

DROP TABLE IF EXISTS `<DB_PREFIX>sb_tabbs_tab`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_tabbs_tab` (
  `id` int(11) NOT NULL,
  `tid` int(11) NOT NULL COMMENT 'TABBS id',
  `title` text NOT NULL,
  `content` text NOT NULL,
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `sort` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sb_table`
--

DROP TABLE IF EXISTS `<DB_PREFIX>sb_table`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_table` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` varchar(10) NOT NULL COMMENT 'option1, option2, ...',
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sb_table_datas`
--

DROP TABLE IF EXISTS `<DB_PREFIX>sb_table_datas`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_table_datas` (
  `id` int(11) NOT NULL,
  `tid` int(11) NOT NULL COMMENT 'Table ID',
  `content` text NOT NULL COMMENT 'Contenus',
  `sort` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sb_table_structure`
--

DROP TABLE IF EXISTS `<DB_PREFIX>sb_table_structure`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_table_structure` (
  `id` int(11) NOT NULL,
  `tid` int(11) NOT NULL COMMENT 'Table ID',
  `title` varchar(255) NOT NULL,
  `field_type` varchar(50) NOT NULL COMMENT 'photo,text,date,textarea,textareahtml,link...',
  `field_target` varchar(50) NOT NULL,
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `sort` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sb_users`
--

DROP TABLE IF EXISTS `<DB_PREFIX>sb_users`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_users` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(128) NOT NULL,
  `email` varchar(100) NOT NULL,
  `logintime` int(11) NOT NULL,
  `lastlogin` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `activekey` varchar(15) NOT NULL DEFAULT '0',
  `resetkey` varchar(15) NOT NULL DEFAULT '0',
  `menu` text NOT NULL COMMENT 'Liste du menu inaccessible ( separe par des | )',
  `groupe` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL DEFAULT '',
  `nom` varchar(50) NOT NULL DEFAULT '',
  `telephone` varchar(30) NOT NULL DEFAULT '',
  `fonction` varchar(100) NOT NULL DEFAULT '',
  `profession` varchar(100) NOT NULL DEFAULT '',
  `centres_interet` text NOT NULL,
  `infos_complementaires` text NOT NULL,
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT 'Nom de fichier dans upload/avatars/ (racine du site) - vide = repli sur Gravatar'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table Users';

--
-- Dumping data for table `sb_users`
--

INSERT INTO `<DB_PREFIX>sb_users` (`id`, `username`, `password`, `email`, `logintime`, `lastlogin`, `active`, `activekey`, `resetkey`, `menu`, `groupe`, `prenom`, `nom`, `telephone`, `fonction`, `profession`, `centres_interet`, `infos_complementaires`, `avatar`) VALUES
(1, 'admin', 'OUovZTFHdGNmaThNL1RZU0tyVXNmZz09Ojrdt++k07oZd9AcRrsXNqow', 'admin-reply@votresite.com', 0, 0, 1, '0', '0', '', '', '', '', '', '', '', '', '', '');

--
-- Table structure for table `sb_users_remember_tokens`
-- (Point 1, audit sécurité 2026-07-29) : jetons "Se souvenir de moi"
-- (sélecteur/validateur) - remplace le stockage du mot de passe chiffré
-- directement dans le cookie. `validator_hash` est un sha256 du validateur
-- aléatoire (pas du mot de passe - haute entropie, un hash rapide suffit
-- ici, contrairement à `sb_users`.`password`).
--

DROP TABLE IF EXISTS `<DB_PREFIX>sb_users_remember_tokens`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_users_remember_tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `selector` varchar(24) NOT NULL,
  `validator_hash` varchar(64) NOT NULL,
  `expires` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sb_users_rights`
--

DROP TABLE IF EXISTS `<DB_PREFIX>sb_users_rights`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_users_rights` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `module` varchar(50) NOT NULL,
  `can_view` tinyint(1) NOT NULL DEFAULT 1,
  `can_add` tinyint(1) NOT NULL DEFAULT 1,
  `can_edit` tinyint(1) NOT NULL DEFAULT 1,
  `can_delete` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Droits granulaires par utilisateur et par module (voir/ajouter/modifier/supprimer) - absence de ligne = accès complet';

--
-- Indexes for dumped tables
--

--
-- Index pour la table `sb_blocked_history`
--
ALTER TABLE `<DB_PREFIX>sb_blocked_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sb_blocked_ip`
--
ALTER TABLE `<DB_PREFIX>sb_blocked_ip`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sb_blocs`
--
ALTER TABLE `<DB_PREFIX>sb_blocs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sb_blocs_sort`
--
ALTER TABLE `<DB_PREFIX>sb_blocs_sort`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sb_config`
--
ALTER TABLE `<DB_PREFIX>sb_config`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `config` (`config`),
  ADD KEY `config_2` (`config`);

--
-- Indexes for table `sb_dashboard_widgets`
--
ALTER TABLE `<DB_PREFIX>sb_dashboard_widgets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sb_contact`
--
ALTER TABLE `<DB_PREFIX>sb_contact`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sb_country`
--
ALTER TABLE `<DB_PREFIX>sb_country`
  ADD PRIMARY KEY (`country_iso`);

--
-- Indexes for table `sb_flood`
--
ALTER TABLE `<DB_PREFIX>sb_flood`
  ADD PRIMARY KEY (`ip`);

--
-- Indexes for table `sb_logaccess`
--
ALTER TABLE `<DB_PREFIX>sb_logaccess`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sb_menu`
--
ALTER TABLE `<DB_PREFIX>sb_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sb_faq`
--
ALTER TABLE `<DB_PREFIX>sb_faq`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sb_faq_category`
--
ALTER TABLE `<DB_PREFIX>sb_faq_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sb_messages`
--
ALTER TABLE `<DB_PREFIX>sb_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `recipient_id` (`recipient_id`,`read_at`);

--
-- Indexes for table `sb_news`
--
ALTER TABLE `<DB_PREFIX>sb_news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sb_news_category`
--
ALTER TABLE `<DB_PREFIX>sb_news_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sb_news_settings`
--
ALTER TABLE `<DB_PREFIX>sb_news_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sb_pages`
--
ALTER TABLE `<DB_PREFIX>sb_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sb_sandbox`
--
ALTER TABLE `<DB_PREFIX>sb_sandbox`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sb_sessions`
--
ALTER TABLE `<DB_PREFIX>sb_sessions`
  ADD PRIMARY KEY (`id`);

--
--
-- Indexes for table `sb_shop_accounting_export`
--
ALTER TABLE `<DB_PREFIX>sb_shop_accounting_export`
  ADD PRIMARY KEY (`id`),
  ADD KEY `plugin_id` (`plugin_id`);

--
-- Indexes for table `sb_shop_accounting_log`
--
ALTER TABLE `<DB_PREFIX>sb_shop_accounting_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oid` (`oid`),
  ADD KEY `plugin_id` (`plugin_id`);

--
-- Indexes for table `sb_shop_category`
--
ALTER TABLE `<DB_PREFIX>sb_shop_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sb_shop_config`
--
ALTER TABLE `<DB_PREFIX>sb_shop_config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sb_shop_discount`
--
ALTER TABLE `<DB_PREFIX>sb_shop_discount`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `sb_shop_email`
--
ALTER TABLE `<DB_PREFIX>sb_shop_email`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `sb_shop_order`
--
ALTER TABLE `<DB_PREFIX>sb_shop_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_uid` (`client_uid`);

--
-- Indexes for table `sb_shop_order_detail`
--
ALTER TABLE `<DB_PREFIX>sb_shop_order_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oid` (`oid`),
  ADD KEY `pid` (`pid`);

--
-- Indexes for table `sb_shop_payment`
--
ALTER TABLE `<DB_PREFIX>sb_shop_payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `plugin_id` (`plugin_id`);

--
-- Indexes for table `sb_shop_product`
--
ALTER TABLE `<DB_PREFIX>sb_shop_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `catid` (`catid`),
  ADD KEY `reference` (`reference`);

--
-- Indexes for table `sb_shop_transport`
--
ALTER TABLE `<DB_PREFIX>sb_shop_transport`
  ADD PRIMARY KEY (`id`),
  ADD KEY `plugin_id` (`plugin_id`);

--
-- Indexes for table `sb_shop_unique`
--
ALTER TABLE `<DB_PREFIX>sb_shop_unique`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `order_detail_id` (`order_detail_id`),
  ADD KEY `pid` (`pid`);

-- Indexes for table `sb_slider`
--
ALTER TABLE `<DB_PREFIX>sb_slider`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sb_slider_photos`
--
ALTER TABLE `<DB_PREFIX>sb_slider_photos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sb_tabbs`
--
ALTER TABLE `<DB_PREFIX>sb_tabbs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sb_tabbs_tab`
--
ALTER TABLE `<DB_PREFIX>sb_tabbs_tab`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sb_table`
--
ALTER TABLE `<DB_PREFIX>sb_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sb_table_datas`
--
ALTER TABLE `<DB_PREFIX>sb_table_datas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sb_table_structure`
--
ALTER TABLE `<DB_PREFIX>sb_table_structure`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sb_users`
--
ALTER TABLE `<DB_PREFIX>sb_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sb_users_remember_tokens`
--
ALTER TABLE `<DB_PREFIX>sb_users_remember_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `selector` (`selector`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `sb_users_rights`
--
ALTER TABLE `<DB_PREFIX>sb_users_rights`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_module` (`user_id`,`module`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT pour la table `sb_blocked_history`
--
ALTER TABLE `<DB_PREFIX>sb_blocked_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `sb_blocked_ip`
--
ALTER TABLE `<DB_PREFIX>sb_blocked_ip`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sb_blocs`
--
ALTER TABLE `<DB_PREFIX>sb_blocs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sb_blocs_sort`
--
ALTER TABLE `<DB_PREFIX>sb_blocs_sort`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sb_config`
--
ALTER TABLE `<DB_PREFIX>sb_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;
--
-- AUTO_INCREMENT for table `sb_dashboard_widgets`
--
ALTER TABLE `<DB_PREFIX>sb_dashboard_widgets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `sb_contact`
--
ALTER TABLE `<DB_PREFIX>sb_contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sb_logaccess`
--
ALTER TABLE `<DB_PREFIX>sb_logaccess`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sb_menu`
--
ALTER TABLE `<DB_PREFIX>sb_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `sb_faq`
--
ALTER TABLE `<DB_PREFIX>sb_faq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sb_faq_category`
--
ALTER TABLE `<DB_PREFIX>sb_faq_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sb_messages`
--
ALTER TABLE `<DB_PREFIX>sb_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sb_news`
--
ALTER TABLE `<DB_PREFIX>sb_news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sb_news_category`
--
ALTER TABLE `<DB_PREFIX>sb_news_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sb_news_settings`
--
ALTER TABLE `<DB_PREFIX>sb_news_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `sb_pages`
--
ALTER TABLE `<DB_PREFIX>sb_pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `sb_sandbox`
--
ALTER TABLE `<DB_PREFIX>sb_sandbox`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sb_sessions`
--
ALTER TABLE `<DB_PREFIX>sb_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
--
-- AUTO_INCREMENT for table `sb_shop_accounting_export`
--
ALTER TABLE `<DB_PREFIX>sb_shop_accounting_export`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sb_shop_accounting_log`
--
ALTER TABLE `<DB_PREFIX>sb_shop_accounting_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sb_shop_category`
--
ALTER TABLE `<DB_PREFIX>sb_shop_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sb_shop_config`
--
ALTER TABLE `<DB_PREFIX>sb_shop_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sb_shop_discount`
--
ALTER TABLE `<DB_PREFIX>sb_shop_discount`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sb_shop_email`
--
ALTER TABLE `<DB_PREFIX>sb_shop_email`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sb_shop_order`
--
ALTER TABLE `<DB_PREFIX>sb_shop_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sb_shop_order_detail`
--
ALTER TABLE `<DB_PREFIX>sb_shop_order_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sb_shop_payment`
--
ALTER TABLE `<DB_PREFIX>sb_shop_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sb_shop_product`
--
ALTER TABLE `<DB_PREFIX>sb_shop_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sb_shop_transport`
--
ALTER TABLE `<DB_PREFIX>sb_shop_transport`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sb_shop_unique`
--
ALTER TABLE `<DB_PREFIX>sb_shop_unique`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
-- AUTO_INCREMENT for table `sb_slider`
--
ALTER TABLE `<DB_PREFIX>sb_slider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sb_slider_photos`
--
ALTER TABLE `<DB_PREFIX>sb_slider_photos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sb_tabbs`
--
ALTER TABLE `<DB_PREFIX>sb_tabbs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sb_tabbs_tab`
--
ALTER TABLE `<DB_PREFIX>sb_tabbs_tab`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sb_table`
--
ALTER TABLE `<DB_PREFIX>sb_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sb_table_datas`
--
ALTER TABLE `<DB_PREFIX>sb_table_datas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sb_table_structure`
--
ALTER TABLE `<DB_PREFIX>sb_table_structure`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sb_users`
--
ALTER TABLE `<DB_PREFIX>sb_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `sb_users_remember_tokens`
--
ALTER TABLE `<DB_PREFIX>sb_users_remember_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sb_users_rights`
--
ALTER TABLE `<DB_PREFIX>sb_users_rights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
