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
(1, 'css', '.fright {float: right;}\r\n.fcenter {float: center;}\r\n.fleft {float: left;}\r\n.aright {text-align: right;}\r\n.acenter {text-align: center;}\r\n.aleft {text-align: left;}\r\n.dnone {display: none !important;}'),
(2, 'javascript', 'jQuery(document).ready(function() {\r\n	// Recherche cach&eacute;e\r\n	jQuery(&#039;#votrediv&#039;).css(&#039;color&#039;,&#039;red&#039;);\r\n});'),
(3, 'header', '[fr]&lt;h3&gt;Bienvenue sur SBUIADMIN&lt;/h3&gt;\r\n\r\n&lt;h5&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit.&lt;/h5&gt;\r\n\r\n&lt;p&gt;Cras rutrum, massa non blandit convallis, est lacus gravida enim, eu fermentum ligula orci et tortor.&lt;/p&gt;\r\n&lt;a href=&quot;#&quot;&gt;Lire la suite&lt;/a&gt;[/fr]'),
(4, 'footer', '[fr]&amp;copy; [CS name=sbyear] &amp;bull; www.votresite.com &amp;bull; Cr&amp;eacute;&amp;eacute; &amp;amp; r&amp;eacute;alis&amp;eacute; par &lt;a href=&quot;//informatux.com&quot; target=&quot;_blank&quot;&gt;informatux.com&lt;/a&gt;[/fr]'),
(5, 'email_to', 'contact@votresite.fr'),
(6, 'email_publickey', ''),
(7, 'email_privatekey', ''),
(8, 'email_subject', 'Message de votre site'),
(9, 'coming-soon', '1'),
(10, 'coming-soon-url', 'comingSoon'),
(11, 'coming-soon-title', 'SBUIADMIN'),
(12, 'coming-soon-title2', 'SBUIADMIN By INFORMATUX'),
(13, 'coming-soon-text', 'We are a team of talented people with big ideas and creative minds.&lt;br /&gt;\r\nWe are here to make your website a lot more effective and profitable.&lt;br /&gt;\r\nGenesis Coming Soon Template is a perfect solution to keep your visitors&lt;br /&gt;\r\ninterested while preparing site for launch.&lt;br /&gt;\r\nWe know what&amp;#39;s the best and we&amp;#39;re here for you.'),
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
(25, 'coming-soon-type', 'video'),
(26, 'coming-soon-image', ''),
(27, 'coming-soon-video', 'E5MO0h7NIqY'),
(28, 'coming-soon-dark', '0'),
(29, 'coming-soon-date', '31/05/2018'),
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
(40, 'theme_infos_address', 'Rue de la bourse\r\n75016 Paris, FR'),
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
('AD', 'ANDORRA', 'Andorra', 'AND', 20),
('AE', 'UNITED ARAB EMIRATES', 'United Arab Emirates', 'ARE', 784),
('AF', 'AFGHANISTAN', 'Afghanistan', 'AFG', 4),
('AG', 'ANTIGUA AND BARBUDA', 'Antigua and Barbuda', 'ATG', 28),
('AI', 'ANGUILLA', 'Anguilla', 'AIA', 660),
('AL', 'ALBANIA', 'Albania', 'ALB', 8),
('AM', 'ARMENIA', 'Armenia', 'ARM', 51),
('AN', 'NETHERLANDS ANTILLES', 'Netherlands Antilles', 'ANT', 530),
('AO', 'ANGOLA', 'Angola', 'AGO', 24),
('AQ', 'ANTARCTICA', 'Antarctica', NULL, NULL),
('AR', 'ARGENTINA', 'Argentina', 'ARG', 32),
('AS', 'AMERICAN SAMOA', 'American Samoa', 'ASM', 16),
('AT', 'AUSTRIA', 'Austria', 'AUT', 40),
('AU', 'AUSTRALIA', 'Australia', 'AUS', 36),
('AW', 'ARUBA', 'Aruba', 'ABW', 533),
('AZ', 'AZERBAIJAN', 'Azerbaijan', 'AZE', 31),
('BA', 'BOSNIA AND HERZEGOVINA', 'Bosnia and Herzegovina', 'BIH', 70),
('BB', 'BARBADOS', 'Barbados', 'BRB', 52),
('BD', 'BANGLADESH', 'Bangladesh', 'BGD', 50),
('BE', 'BELGIUM', 'Belgium', 'BEL', 56),
('BF', 'BURKINA FASO', 'Burkina Faso', 'BFA', 854),
('BG', 'BULGARIA', 'Bulgaria', 'BGR', 100),
('BH', 'BAHRAIN', 'Bahrain', 'BHR', 48),
('BI', 'BURUNDI', 'Burundi', 'BDI', 108),
('BJ', 'BENIN', 'Benin', 'BEN', 204),
('BM', 'BERMUDA', 'Bermuda', 'BMU', 60),
('BN', 'BRUNEI DARUSSALAM', 'Brunei Darussalam', 'BRN', 96),
('BO', 'BOLIVIA', 'Bolivia', 'BOL', 68),
('BR', 'BRAZIL', 'Brazil', 'BRA', 76),
('BS', 'BAHAMAS', 'Bahamas', 'BHS', 44),
('BT', 'BHUTAN', 'Bhutan', 'BTN', 64),
('BV', 'BOUVET ISLAND', 'Bouvet Island', NULL, NULL),
('BW', 'BOTSWANA', 'Botswana', 'BWA', 72),
('BY', 'BELARUS', 'Belarus', 'BLR', 112),
('BZ', 'BELIZE', 'Belize', 'BLZ', 84),
('CA', 'CANADA', 'Canada', 'CAN', 124),
('CC', 'COCOS (KEELING) ISLANDS', 'Cocos (Keeling) Islands', NULL, NULL),
('CD', 'CONGO, THE DEMOCRATIC REPUBLIC OF THE', 'Congo, the Democratic Republic of the', 'COD', 180),
('CF', 'CENTRAL AFRICAN REPUBLIC', 'Central African Republic', 'CAF', 140),
('CG', 'CONGO', 'Congo', 'COG', 178),
('CH', 'SWITZERLAND', 'Switzerland', 'CHE', 756),
('CI', 'COTE D\'IVOIRE', 'Cote D\'Ivoire', 'CIV', 384),
('CK', 'COOK ISLANDS', 'Cook Islands', 'COK', 184),
('CL', 'CHILE', 'Chile', 'CHL', 152),
('CM', 'CAMEROON', 'Cameroon', 'CMR', 120),
('CN', 'CHINA', 'China', 'CHN', 156),
('CO', 'COLOMBIA', 'Colombia', 'COL', 170),
('CR', 'COSTA RICA', 'Costa Rica', 'CRI', 188),
('CS', 'SERBIA AND MONTENEGRO', 'Serbia and Montenegro', NULL, NULL),
('CU', 'CUBA', 'Cuba', 'CUB', 192),
('CV', 'CAPE VERDE', 'Cape Verde', 'CPV', 132),
('CX', 'CHRISTMAS ISLAND', 'Christmas Island', NULL, NULL),
('CY', 'CYPRUS', 'Cyprus', 'CYP', 196),
('CZ', 'CZECH REPUBLIC', 'Czech Republic', 'CZE', 203),
('DE', 'GERMANY', 'Germany', 'DEU', 276),
('DJ', 'DJIBOUTI', 'Djibouti', 'DJI', 262),
('DK', 'DENMARK', 'Denmark', 'DNK', 208),
('DM', 'DOMINICA', 'Dominica', 'DMA', 212),
('DO', 'DOMINICAN REPUBLIC', 'Dominican Republic', 'DOM', 214),
('DZ', 'ALGERIA', 'Algeria', 'DZA', 12),
('EC', 'ECUADOR', 'Ecuador', 'ECU', 218),
('EE', 'ESTONIA', 'Estonia', 'EST', 233),
('EG', 'EGYPT', 'Egypt', 'EGY', 818),
('EH', 'WESTERN SAHARA', 'Western Sahara', 'ESH', 732),
('ER', 'ERITREA', 'Eritrea', 'ERI', 232),
('ES', 'SPAIN', 'Spain', 'ESP', 724),
('ET', 'ETHIOPIA', 'Ethiopia', 'ETH', 231),
('FI', 'FINLAND', 'Finland', 'FIN', 246),
('FJ', 'FIJI', 'Fiji', 'FJI', 242),
('FK', 'FALKLAND ISLANDS (MALVINAS)', 'Falkland Islands (Malvinas)', 'FLK', 238),
('FM', 'MICRONESIA, FEDERATED STATES OF', 'Micronesia, Federated States of', 'FSM', 583),
('FO', 'FAROE ISLANDS', 'Faroe Islands', 'FRO', 234),
('FR', 'FRANCE', 'France', 'FRA', 250),
('GA', 'GABON', 'Gabon', 'GAB', 266),
('GB', 'UNITED KINGDOM', 'United Kingdom', 'GBR', 826),
('GD', 'GRENADA', 'Grenada', 'GRD', 308),
('GE', 'GEORGIA', 'Georgia', 'GEO', 268),
('GF', 'FRENCH GUIANA', 'French Guiana', 'GUF', 254),
('GH', 'GHANA', 'Ghana', 'GHA', 288),
('GI', 'GIBRALTAR', 'Gibraltar', 'GIB', 292),
('GL', 'GREENLAND', 'Greenland', 'GRL', 304),
('GM', 'GAMBIA', 'Gambia', 'GMB', 270),
('GN', 'GUINEA', 'Guinea', 'GIN', 324),
('GP', 'GUADELOUPE', 'Guadeloupe', 'GLP', 312),
('GQ', 'EQUATORIAL GUINEA', 'Equatorial Guinea', 'GNQ', 226),
('GR', 'GREECE', 'Greece', 'GRC', 300),
('GS', 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS', 'South Georgia and the South Sandwich Islands', NULL, NULL),
('GT', 'GUATEMALA', 'Guatemala', 'GTM', 320),
('GU', 'GUAM', 'Guam', 'GUM', 316),
('GW', 'GUINEA-BISSAU', 'Guinea-Bissau', 'GNB', 624),
('GY', 'GUYANA', 'Guyana', 'GUY', 328),
('HK', 'HONG KONG', 'Hong Kong', 'HKG', 344),
('HM', 'HEARD ISLAND AND MCDONALD ISLANDS', 'Heard Island and Mcdonald Islands', NULL, NULL),
('HN', 'HONDURAS', 'Honduras', 'HND', 340),
('HR', 'CROATIA', 'Croatia', 'HRV', 191),
('HT', 'HAITI', 'Haiti', 'HTI', 332),
('HU', 'HUNGARY', 'Hungary', 'HUN', 348),
('ID', 'INDONESIA', 'Indonesia', 'IDN', 360),
('IE', 'IRELAND', 'Ireland', 'IRL', 372),
('IL', 'ISRAEL', 'Israel', 'ISR', 376),
('IN', 'INDIA', 'India', 'IND', 356),
('IO', 'BRITISH INDIAN OCEAN TERRITORY', 'British Indian Ocean Territory', NULL, NULL),
('IQ', 'IRAQ', 'Iraq', 'IRQ', 368),
('IR', 'IRAN, ISLAMIC REPUBLIC OF', 'Iran, Islamic Republic of', 'IRN', 364),
('IS', 'ICELAND', 'Iceland', 'ISL', 352),
('IT', 'ITALY', 'Italy', 'ITA', 380),
('JM', 'JAMAICA', 'Jamaica', 'JAM', 388),
('JO', 'JORDAN', 'Jordan', 'JOR', 400),
('JP', 'JAPAN', 'Japan', 'JPN', 392),
('KE', 'KENYA', 'Kenya', 'KEN', 404),
('KG', 'KYRGYZSTAN', 'Kyrgyzstan', 'KGZ', 417),
('KH', 'CAMBODIA', 'Cambodia', 'KHM', 116),
('KI', 'KIRIBATI', 'Kiribati', 'KIR', 296),
('KM', 'COMOROS', 'Comoros', 'COM', 174),
('KN', 'SAINT KITTS AND NEVIS', 'Saint Kitts and Nevis', 'KNA', 659),
('KP', 'KOREA, DEMOCRATIC PEOPLE\'S REPUBLIC OF', 'Korea, Democratic People\'s Republic of', 'PRK', 408),
('KR', 'KOREA, REPUBLIC OF', 'Korea, Republic of', 'KOR', 410),
('KW', 'KUWAIT', 'Kuwait', 'KWT', 414),
('KY', 'CAYMAN ISLANDS', 'Cayman Islands', 'CYM', 136),
('KZ', 'KAZAKHSTAN', 'Kazakhstan', 'KAZ', 398),
('LA', 'LAO PEOPLE\'S DEMOCRATIC REPUBLIC', 'Lao People\'s Democratic Republic', 'LAO', 418),
('LB', 'LEBANON', 'Lebanon', 'LBN', 422),
('LC', 'SAINT LUCIA', 'Saint Lucia', 'LCA', 662),
('LI', 'LIECHTENSTEIN', 'Liechtenstein', 'LIE', 438),
('LK', 'SRI LANKA', 'Sri Lanka', 'LKA', 144),
('LR', 'LIBERIA', 'Liberia', 'LBR', 430),
('LS', 'LESOTHO', 'Lesotho', 'LSO', 426),
('LT', 'LITHUANIA', 'Lithuania', 'LTU', 440),
('LU', 'LUXEMBOURG', 'Luxembourg', 'LUX', 442),
('LV', 'LATVIA', 'Latvia', 'LVA', 428),
('LY', 'LIBYAN ARAB JAMAHIRIYA', 'Libyan Arab Jamahiriya', 'LBY', 434),
('MA', 'MOROCCO', 'Morocco', 'MAR', 504),
('MC', 'MONACO', 'Monaco', 'MCO', 492),
('MD', 'MOLDOVA, REPUBLIC OF', 'Moldova, Republic of', 'MDA', 498),
('MG', 'MADAGASCAR', 'Madagascar', 'MDG', 450),
('MH', 'MARSHALL ISLANDS', 'Marshall Islands', 'MHL', 584),
('MK', 'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF', 'Macedonia, the Former Yugoslav Republic of', 'MKD', 807),
('ML', 'MALI', 'Mali', 'MLI', 466),
('MM', 'MYANMAR', 'Myanmar', 'MMR', 104),
('MN', 'MONGOLIA', 'Mongolia', 'MNG', 496),
('MO', 'MACAO', 'Macao', 'MAC', 446),
('MP', 'NORTHERN MARIANA ISLANDS', 'Northern Mariana Islands', 'MNP', 580),
('MQ', 'MARTINIQUE', 'Martinique', 'MTQ', 474),
('MR', 'MAURITANIA', 'Mauritania', 'MRT', 478),
('MS', 'MONTSERRAT', 'Montserrat', 'MSR', 500),
('MT', 'MALTA', 'Malta', 'MLT', 470),
('MU', 'MAURITIUS', 'Mauritius', 'MUS', 480),
('MV', 'MALDIVES', 'Maldives', 'MDV', 462),
('MW', 'MALAWI', 'Malawi', 'MWI', 454),
('MX', 'MEXICO', 'Mexico', 'MEX', 484),
('MY', 'MALAYSIA', 'Malaysia', 'MYS', 458),
('MZ', 'MOZAMBIQUE', 'Mozambique', 'MOZ', 508),
('NA', 'NAMIBIA', 'Namibia', 'NAM', 516),
('NC', 'NEW CALEDONIA', 'New Caledonia', 'NCL', 540),
('NE', 'NIGER', 'Niger', 'NER', 562),
('NF', 'NORFOLK ISLAND', 'Norfolk Island', 'NFK', 574),
('NG', 'NIGERIA', 'Nigeria', 'NGA', 566),
('NI', 'NICARAGUA', 'Nicaragua', 'NIC', 558),
('NL', 'NETHERLANDS', 'Netherlands', 'NLD', 528),
('NO', 'NORWAY', 'Norway', 'NOR', 578),
('NP', 'NEPAL', 'Nepal', 'NPL', 524),
('NR', 'NAURU', 'Nauru', 'NRU', 520),
('NU', 'NIUE', 'Niue', 'NIU', 570),
('NZ', 'NEW ZEALAND', 'New Zealand', 'NZL', 554),
('OM', 'OMAN', 'Oman', 'OMN', 512),
('PA', 'PANAMA', 'Panama', 'PAN', 591),
('PE', 'PERU', 'Peru', 'PER', 604),
('PF', 'FRENCH POLYNESIA', 'French Polynesia', 'PYF', 258),
('PG', 'PAPUA NEW GUINEA', 'Papua New Guinea', 'PNG', 598),
('PH', 'PHILIPPINES', 'Philippines', 'PHL', 608),
('PK', 'PAKISTAN', 'Pakistan', 'PAK', 586),
('PL', 'POLAND', 'Poland', 'POL', 616),
('PM', 'SAINT PIERRE AND MIQUELON', 'Saint Pierre and Miquelon', 'SPM', 666),
('PN', 'PITCAIRN', 'Pitcairn', 'PCN', 612),
('PR', 'PUERTO RICO', 'Puerto Rico', 'PRI', 630),
('PS', 'PALESTINIAN TERRITORY, OCCUPIED', 'Palestinian Territory, Occupied', NULL, NULL),
('PT', 'PORTUGAL', 'Portugal', 'PRT', 620),
('PW', 'PALAU', 'Palau', 'PLW', 585),
('PY', 'PARAGUAY', 'Paraguay', 'PRY', 600),
('QA', 'QATAR', 'Qatar', 'QAT', 634),
('RE', 'REUNION', 'Reunion', 'REU', 638),
('RO', 'ROMANIA', 'Romania', 'ROM', 642),
('RU', 'RUSSIAN FEDERATION', 'Russian Federation', 'RUS', 643),
('RW', 'RWANDA', 'Rwanda', 'RWA', 646),
('SA', 'SAUDI ARABIA', 'Saudi Arabia', 'SAU', 682),
('SB', 'SOLOMON ISLANDS', 'Solomon Islands', 'SLB', 90),
('SC', 'SEYCHELLES', 'Seychelles', 'SYC', 690),
('SD', 'SUDAN', 'Sudan', 'SDN', 736),
('SE', 'SWEDEN', 'Sweden', 'SWE', 752),
('SG', 'SINGAPORE', 'Singapore', 'SGP', 702),
('SH', 'SAINT HELENA', 'Saint Helena', 'SHN', 654),
('SI', 'SLOVENIA', 'Slovenia', 'SVN', 705),
('SJ', 'SVALBARD AND JAN MAYEN', 'Svalbard and Jan Mayen', 'SJM', 744),
('SK', 'SLOVAKIA', 'Slovakia', 'SVK', 703),
('SL', 'SIERRA LEONE', 'Sierra Leone', 'SLE', 694),
('SM', 'SAN MARINO', 'San Marino', 'SMR', 674),
('SN', 'SENEGAL', 'Senegal', 'SEN', 686),
('SO', 'SOMALIA', 'Somalia', 'SOM', 706),
('SR', 'SURINAME', 'Suriname', 'SUR', 740),
('ST', 'SAO TOME AND PRINCIPE', 'Sao Tome and Principe', 'STP', 678),
('SV', 'EL SALVADOR', 'El Salvador', 'SLV', 222),
('SY', 'SYRIAN ARAB REPUBLIC', 'Syrian Arab Republic', 'SYR', 760),
('SZ', 'SWAZILAND', 'Swaziland', 'SWZ', 748),
('TC', 'TURKS AND CAICOS ISLANDS', 'Turks and Caicos Islands', 'TCA', 796),
('TD', 'CHAD', 'Chad', 'TCD', 148),
('TF', 'FRENCH SOUTHERN TERRITORIES', 'French Southern Territories', NULL, NULL),
('TG', 'TOGO', 'Togo', 'TGO', 768),
('TH', 'THAILAND', 'Thailand', 'THA', 764),
('TJ', 'TAJIKISTAN', 'Tajikistan', 'TJK', 762),
('TK', 'TOKELAU', 'Tokelau', 'TKL', 772),
('TL', 'TIMOR-LESTE', 'Timor-Leste', NULL, NULL),
('TM', 'TURKMENISTAN', 'Turkmenistan', 'TKM', 795),
('TN', 'TUNISIA', 'Tunisia', 'TUN', 788),
('TO', 'TONGA', 'Tonga', 'TON', 776),
('TR', 'TURKEY', 'Turkey', 'TUR', 792),
('TT', 'TRINIDAD AND TOBAGO', 'Trinidad and Tobago', 'TTO', 780),
('TV', 'TUVALU', 'Tuvalu', 'TUV', 798),
('TW', 'TAIWAN, PROVINCE OF CHINA', 'Taiwan, Province of China', 'TWN', 158),
('TZ', 'TANZANIA, UNITED REPUBLIC OF', 'Tanzania, United Republic of', 'TZA', 834),
('UA', 'UKRAINE', 'Ukraine', 'UKR', 804),
('UG', 'UGANDA', 'Uganda', 'UGA', 800),
('UM', 'UNITED STATES MINOR OUTLYING ISLANDS', 'United States Minor Outlying Islands', NULL, NULL),
('US', 'UNITED STATES', 'United States', 'USA', 840),
('UY', 'URUGUAY', 'Uruguay', 'URY', 858),
('UZ', 'UZBEKISTAN', 'Uzbekistan', 'UZB', 860),
('VA', 'HOLY SEE (VATICAN CITY STATE)', 'Holy See (Vatican City State)', 'VAT', 336),
('VC', 'SAINT VINCENT AND THE GRENADINES', 'Saint Vincent and the Grenadines', 'VCT', 670),
('VE', 'VENEZUELA', 'Venezuela', 'VEN', 862),
('VG', 'VIRGIN ISLANDS, BRITISH', 'Virgin Islands, British', 'VGB', 92),
('VI', 'VIRGIN ISLANDS, U.S.', 'Virgin Islands, U.s.', 'VIR', 850),
('VN', 'VIET NAM', 'Viet Nam', 'VNM', 704),
('VU', 'VANUATU', 'Vanuatu', 'VUT', 548),
('WF', 'WALLIS AND FUTUNA', 'Wallis and Futuna', 'WLF', 876),
('WS', 'SAMOA', 'Samoa', 'WSM', 882),
('YE', 'YEMEN', 'Yemen', 'YEM', 887),
('YT', 'MAYOTTE', 'Mayotte', NULL, NULL),
('ZA', 'SOUTH AFRICA', 'South Africa', 'ZAF', 710),
('ZM', 'ZAMBIA', 'Zambia', 'ZMB', 894),
('ZW', 'ZIMBABWE', 'Zimbabwe', 'ZWE', 716);

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
-- Table structure for table `sb_news`
--

DROP TABLE IF EXISTS `<DB_PREFIX>sb_news`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_news` (
  `id` int(11) NOT NULL,
  `catid` varchar(50) NOT NULL COMMENT 'Categories',
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
(1, '1', '[fr]Lorem Ipsum callum[/fr]', '[fr][/fr]', '[fr]Sed ac luctus mauris. Nullam semper tortor nec orci sagittis, vel vestibulum metus consectetur. Suspendisse tincidunt, nunc ut pharetra dapibus, erat sapien aliquet arcu, vel egestas urna metus vitae libero. Aenean sodales eros vitae dui ornare posuere ac non turpis. Maecenas vel diam tincidunt, malesuada dolor congue, laoreet lacus. Vestibulum consectetur massa eget dui vehicula maximus. Maecenas ut sodales velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ut mauris massa. Proin scelerisque fringilla feugiat. Duis feugiat eleifend nunc sit amet convallis. Vestibulum vel nunc sit amet quam varius rhoncus. Mauris in sapien id elit sodales faucibus. Proin augue mauris, dapibus at pellentesque quis, iaculis in arcu.[/fr]', '[fr]Sed ac luctus mauris. Nullam semper tortor nec orci sagittis, vel vestibulum metus consectetur. Suspendisse tincidunt, nunc ut pharetra dapibus, erat sapien aliquet arcu, vel egestas urna metus vitae libero. Aenean sodales eros vitae dui ornare posuere ac non turpis. Maecenas vel diam tincidunt, malesuada dolor congue, laoreet lacus. Vestibulum consectetur massa eget dui vehicula maximus. Maecenas ut sodales velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ut mauris massa. Proin scelerisque fringilla feugiat. Duis feugiat eleifend nunc sit amet convallis. Vestibulum vel nunc sit amet quam varius rhoncus. Mauris in sapien id elit sodales faucibus. Proin augue mauris, dapibus at pellentesque quis, iaculis in arcu.&lt;br /&gt;\r\n&lt;br /&gt;\r\nSed ac luctus mauris. Nullam semper tortor nec orci sagittis, vel vestibulum metus consectetur. Suspendisse tincidunt, nunc ut pharetra dapibus, erat sapien aliquet arcu, vel egestas urna metus vitae libero. Aenean sodales eros vitae dui ornare posuere ac non turpis. Maecenas vel diam tincidunt, malesuada dolor congue, laoreet lacus. Vestibulum consectetur massa eget dui vehicula maximus. Maecenas ut sodales velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ut mauris massa. Proin scelerisque fringilla feugiat. Duis feugiat eleifend nunc sit amet convallis. Vestibulum vel nunc sit amet quam varius rhoncus. Mauris in sapien id elit sodales faucibus. Proin augue mauris, dapibus at pellentesque quis, iaculis in arcu.&lt;br /&gt;\r\n&lt;br /&gt;\r\nSed ac luctus mauris. Nullam semper tortor nec orci sagittis, vel vestibulum metus consectetur. Suspendisse tincidunt, nunc ut pharetra dapibus, erat sapien aliquet arcu, vel egestas urna metus vitae libero. Aenean sodales eros vitae dui ornare posuere ac non turpis. Maecenas vel diam tincidunt, malesuada dolor congue, laoreet lacus. Vestibulum consectetur massa eget dui vehicula maximus. Maecenas ut sodales velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ut mauris massa. Proin scelerisque fringilla feugiat. Duis feugiat eleifend nunc sit amet convallis. Vestibulum vel nunc sit amet quam varius rhoncus. Mauris in sapien id elit sodales faucibus. Proin augue mauris, dapibus at pellentesque quis, iaculis in arcu.[/fr]', 'news-5.jpg', '2017-05-18', 1),
(2, '1', '[fr]Curabitur sed nunc placerat[/fr]', '[fr][/fr]', '[fr]Sed ac luctus mauris. Nullam semper tortor nec orci sagittis, vel vestibulum metus consectetur. Suspendisse tincidunt, nunc ut pharetra dapibus, erat sapien aliquet arcu, vel egestas urna metus vitae libero. Aenean sodales eros vitae dui ornare posuere ac non turpis. Maecenas vel diam tincidunt, malesuada dolor congue, laoreet lacus. Vestibulum consectetur massa eget dui vehicula maximus. Maecenas ut sodales velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ut mauris massa. Proin scelerisque fringilla feugiat. Duis feugiat eleifend nunc sit amet convallis. Vestibulum vel nunc sit amet quam varius rhoncus. Mauris in sapien id elit sodales faucibus. Proin augue mauris, dapibus at pellentesque quis, iaculis in arcu.[/fr]', '[fr]Sed ac luctus mauris. Nullam semper tortor nec orci sagittis, vel vestibulum metus consectetur. Suspendisse tincidunt, nunc ut pharetra dapibus, erat sapien aliquet arcu, vel egestas urna metus vitae libero. Aenean sodales eros vitae dui ornare posuere ac non turpis. Maecenas vel diam tincidunt, malesuada dolor congue, laoreet lacus. Vestibulum consectetur massa eget dui vehicula maximus. Maecenas ut sodales velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ut mauris massa. Proin scelerisque fringilla feugiat. Duis feugiat eleifend nunc sit amet convallis. Vestibulum vel nunc sit amet quam varius rhoncus. Mauris in sapien id elit sodales faucibus. Proin augue mauris, dapibus at pellentesque quis, iaculis in arcu.&lt;br /&gt;\r\n&lt;br /&gt;\r\nSed ac luctus mauris. Nullam semper tortor nec orci sagittis, vel vestibulum metus consectetur. Suspendisse tincidunt, nunc ut pharetra dapibus, erat sapien aliquet arcu, vel egestas urna metus vitae libero. Aenean sodales eros vitae dui ornare posuere ac non turpis. Maecenas vel diam tincidunt, malesuada dolor congue, laoreet lacus. Vestibulum consectetur massa eget dui vehicula maximus. Maecenas ut sodales velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ut mauris massa. Proin scelerisque fringilla feugiat. Duis feugiat eleifend nunc sit amet convallis. Vestibulum vel nunc sit amet quam varius rhoncus. Mauris in sapien id elit sodales faucibus. Proin augue mauris, dapibus at pellentesque quis, iaculis in arcu.&lt;br /&gt;\r\n&lt;br /&gt;\r\nSed ac luctus mauris. Nullam semper tortor nec orci sagittis, vel vestibulum metus consectetur. Suspendisse tincidunt, nunc ut pharetra dapibus, erat sapien aliquet arcu, vel egestas urna metus vitae libero. Aenean sodales eros vitae dui ornare posuere ac non turpis. Maecenas vel diam tincidunt, malesuada dolor congue, laoreet lacus. Vestibulum consectetur massa eget dui vehicula maximus. Maecenas ut sodales velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ut mauris massa. Proin scelerisque fringilla feugiat. Duis feugiat eleifend nunc sit amet convallis. Vestibulum vel nunc sit amet quam varius rhoncus. Mauris in sapien id elit sodales faucibus. Proin augue mauris, dapibus at pellentesque quis, iaculis in arcu.[/fr]', 'news-4.jpg', '2017-05-15', 1),
(3, '1', '[fr]Praesent consequat sit amet[/fr]', '[fr][/fr]', '[fr]Sed ac luctus mauris. Nullam semper tortor nec orci sagittis, vel vestibulum metus consectetur. Suspendisse tincidunt, nunc ut pharetra dapibus, erat sapien aliquet arcu, vel egestas urna metus vitae libero. Aenean sodales eros vitae dui ornare posuere ac non turpis. Maecenas vel diam tincidunt, malesuada dolor congue, laoreet lacus. Vestibulum consectetur massa eget dui vehicula maximus. Maecenas ut sodales velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ut mauris massa. Proin scelerisque fringilla feugiat. Duis feugiat eleifend nunc sit amet convallis. Vestibulum vel nunc sit amet quam varius rhoncus. Mauris in sapien id elit sodales faucibus. Proin augue mauris, dapibus at pellentesque quis, iaculis in arcu.[/fr]', '[fr]Sed ac luctus mauris. Nullam semper tortor nec orci sagittis, vel vestibulum metus consectetur. Suspendisse tincidunt, nunc ut pharetra dapibus, erat sapien aliquet arcu, vel egestas urna metus vitae libero. Aenean sodales eros vitae dui ornare posuere ac non turpis. Maecenas vel diam tincidunt, malesuada dolor congue, laoreet lacus. Vestibulum consectetur massa eget dui vehicula maximus. Maecenas ut sodales velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ut mauris massa. Proin scelerisque fringilla feugiat. Duis feugiat eleifend nunc sit amet convallis. Vestibulum vel nunc sit amet quam varius rhoncus. Mauris in sapien id elit sodales faucibus. Proin augue mauris, dapibus at pellentesque quis, iaculis in arcu.&lt;br /&gt;\r\n&lt;br /&gt;\r\nSed ac luctus mauris. Nullam semper tortor nec orci sagittis, vel vestibulum metus consectetur. Suspendisse tincidunt, nunc ut pharetra dapibus, erat sapien aliquet arcu, vel egestas urna metus vitae libero. Aenean sodales eros vitae dui ornare posuere ac non turpis. Maecenas vel diam tincidunt, malesuada dolor congue, laoreet lacus. Vestibulum consectetur massa eget dui vehicula maximus. Maecenas ut sodales velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ut mauris massa. Proin scelerisque fringilla feugiat. Duis feugiat eleifend nunc sit amet convallis. Vestibulum vel nunc sit amet quam varius rhoncus. Mauris in sapien id elit sodales faucibus. Proin augue mauris, dapibus at pellentesque quis, iaculis in arcu.&lt;br /&gt;\r\n&lt;br /&gt;\r\nSed ac luctus mauris. Nullam semper tortor nec orci sagittis, vel vestibulum metus consectetur. Suspendisse tincidunt, nunc ut pharetra dapibus, erat sapien aliquet arcu, vel egestas urna metus vitae libero. Aenean sodales eros vitae dui ornare posuere ac non turpis. Maecenas vel diam tincidunt, malesuada dolor congue, laoreet lacus. Vestibulum consectetur massa eget dui vehicula maximus. Maecenas ut sodales velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ut mauris massa. Proin scelerisque fringilla feugiat. Duis feugiat eleifend nunc sit amet convallis. Vestibulum vel nunc sit amet quam varius rhoncus. Mauris in sapien id elit sodales faucibus. Proin augue mauris, dapibus at pellentesque quis, iaculis in arcu.[/fr]', 'news-3.jpg', '2017-05-10', 1),
(4, '1', '[fr]Fusce dictum quam nisl[/fr]', '[fr][/fr]', '[fr]Sed ac luctus mauris. Nullam semper tortor nec orci sagittis, vel vestibulum metus consectetur. Suspendisse tincidunt, nunc ut pharetra dapibus, erat sapien aliquet arcu, vel egestas urna metus vitae libero. Aenean sodales eros vitae dui ornare posuere ac non turpis. Maecenas vel diam tincidunt, malesuada dolor congue, laoreet lacus. Vestibulum consectetur massa eget dui vehicula maximus. Maecenas ut sodales velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ut mauris massa. Proin scelerisque fringilla feugiat. Duis feugiat eleifend nunc sit amet convallis. Vestibulum vel nunc sit amet quam varius rhoncus. Mauris in sapien id elit sodales faucibus. Proin augue mauris, dapibus at pellentesque quis, iaculis in arcu.[/fr]', '[fr]Sed ac luctus mauris. Nullam semper tortor nec orci sagittis, vel vestibulum metus consectetur. Suspendisse tincidunt, nunc ut pharetra dapibus, erat sapien aliquet arcu, vel egestas urna metus vitae libero. Aenean sodales eros vitae dui ornare posuere ac non turpis. Maecenas vel diam tincidunt, malesuada dolor congue, laoreet lacus. Vestibulum consectetur massa eget dui vehicula maximus. Maecenas ut sodales velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ut mauris massa. Proin scelerisque fringilla feugiat. Duis feugiat eleifend nunc sit amet convallis. Vestibulum vel nunc sit amet quam varius rhoncus. Mauris in sapien id elit sodales faucibus. Proin augue mauris, dapibus at pellentesque quis, iaculis in arcu.&lt;br /&gt;\r\n&lt;br /&gt;\r\nSed ac luctus mauris. Nullam semper tortor nec orci sagittis, vel vestibulum metus consectetur. Suspendisse tincidunt, nunc ut pharetra dapibus, erat sapien aliquet arcu, vel egestas urna metus vitae libero. Aenean sodales eros vitae dui ornare posuere ac non turpis. Maecenas vel diam tincidunt, malesuada dolor congue, laoreet lacus. Vestibulum consectetur massa eget dui vehicula maximus. Maecenas ut sodales velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ut mauris massa. Proin scelerisque fringilla feugiat. Duis feugiat eleifend nunc sit amet convallis. Vestibulum vel nunc sit amet quam varius rhoncus. Mauris in sapien id elit sodales faucibus. Proin augue mauris, dapibus at pellentesque quis, iaculis in arcu.&lt;br /&gt;\r\n&lt;br /&gt;\r\nSed ac luctus mauris. Nullam semper tortor nec orci sagittis, vel vestibulum metus consectetur. Suspendisse tincidunt, nunc ut pharetra dapibus, erat sapien aliquet arcu, vel egestas urna metus vitae libero. Aenean sodales eros vitae dui ornare posuere ac non turpis. Maecenas vel diam tincidunt, malesuada dolor congue, laoreet lacus. Vestibulum consectetur massa eget dui vehicula maximus. Maecenas ut sodales velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ut mauris massa. Proin scelerisque fringilla feugiat. Duis feugiat eleifend nunc sit amet convallis. Vestibulum vel nunc sit amet quam varius rhoncus. Mauris in sapien id elit sodales faucibus. Proin augue mauris, dapibus at pellentesque quis, iaculis in arcu.[/fr]', 'news-2.jpg', '2017-04-28', 1),
(5, '1', '[fr]Quisque nec congue diam[/fr]', '[fr][/fr]', '[fr]Sed ac luctus mauris. Nullam semper tortor nec orci sagittis, vel vestibulum metus consectetur. Suspendisse tincidunt, nunc ut pharetra dapibus, erat sapien aliquet arcu, vel egestas urna metus vitae libero. Aenean sodales eros vitae dui ornare posuere ac non turpis. Maecenas vel diam tincidunt, malesuada dolor congue, laoreet lacus. Vestibulum consectetur massa eget dui vehicula maximus. Maecenas ut sodales velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ut mauris massa. Proin scelerisque fringilla feugiat. Duis feugiat eleifend nunc sit amet convallis. Vestibulum vel nunc sit amet quam varius rhoncus. Mauris in sapien id elit sodales faucibus. Proin augue mauris, dapibus at pellentesque quis, iaculis in arcu.[/fr]', '[fr]Sed ac luctus mauris. Nullam semper tortor nec orci sagittis, vel vestibulum metus consectetur. Suspendisse tincidunt, nunc ut pharetra dapibus, erat sapien aliquet arcu, vel egestas urna metus vitae libero. Aenean sodales eros vitae dui ornare posuere ac non turpis. Maecenas vel diam tincidunt, malesuada dolor congue, laoreet lacus. Vestibulum consectetur massa eget dui vehicula maximus. Maecenas ut sodales velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ut mauris massa. Proin scelerisque fringilla feugiat. Duis feugiat eleifend nunc sit amet convallis. Vestibulum vel nunc sit amet quam varius rhoncus. Mauris in sapien id elit sodales faucibus. Proin augue mauris, dapibus at pellentesque quis, iaculis in arcu.&lt;br /&gt;\r\n&lt;br /&gt;\r\nSed ac luctus mauris. Nullam semper tortor nec orci sagittis, vel vestibulum metus consectetur. Suspendisse tincidunt, nunc ut pharetra dapibus, erat sapien aliquet arcu, vel egestas urna metus vitae libero. Aenean sodales eros vitae dui ornare posuere ac non turpis. Maecenas vel diam tincidunt, malesuada dolor congue, laoreet lacus. Vestibulum consectetur massa eget dui vehicula maximus. Maecenas ut sodales velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ut mauris massa. Proin scelerisque fringilla feugiat. Duis feugiat eleifend nunc sit amet convallis. Vestibulum vel nunc sit amet quam varius rhoncus. Mauris in sapien id elit sodales faucibus. Proin augue mauris, dapibus at pellentesque quis, iaculis in arcu.&lt;br /&gt;\r\n&lt;br /&gt;\r\nSed ac luctus mauris. Nullam semper tortor nec orci sagittis, vel vestibulum metus consectetur. Suspendisse tincidunt, nunc ut pharetra dapibus, erat sapien aliquet arcu, vel egestas urna metus vitae libero. Aenean sodales eros vitae dui ornare posuere ac non turpis. Maecenas vel diam tincidunt, malesuada dolor congue, laoreet lacus. Vestibulum consectetur massa eget dui vehicula maximus. Maecenas ut sodales velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ut mauris massa. Proin scelerisque fringilla feugiat. Duis feugiat eleifend nunc sit amet convallis. Vestibulum vel nunc sit amet quam varius rhoncus. Mauris in sapien id elit sodales faucibus. Proin augue mauris, dapibus at pellentesque quis, iaculis in arcu.[/fr]', 'news-1.jpg', '2017-03-21', 1);

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
(1, '[fr]Accueil[/fr]', '[fr]Accueil[/fr]', '[fr]&lt;p&gt;Thank you for using SBUIADMIN CMS. This is your homepage, so please change this text to be what you want.&lt;/p&gt;\r\n\r\n&lt;ul&gt;\r\n	&lt;li&gt;&lt;a href=&quot;#&quot;&gt;SBUIADMIN CMS Documentation&lt;/a&gt;\r\n\r\n	&lt;ul&gt;\r\n		&lt;li&gt;&lt;a href=&quot;#&quot;&gt;How to Create a SBUIADMIN Theme&lt;/a&gt;&lt;/li&gt;\r\n	&lt;/ul&gt;\r\n	&lt;/li&gt;\r\n	&lt;li&gt;&lt;a href=&quot;#&quot;&gt;SBUIADMIN Support Forums&lt;/a&gt;&lt;/li&gt;\r\n&lt;/ul&gt;\r\n\r\n&lt;h2&gt;Header 2&lt;/h2&gt;\r\n\r\n&lt;p&gt;Lorem ipsum &lt;em&gt;dolor sit amet&lt;/em&gt;, &lt;strong&gt;consectetur adipiscing elit&lt;/strong&gt;. Donec &lt;code&gt;this is code&lt;/code&gt; venenatis augue. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Integer vulputate pretium augue.&lt;/p&gt;\r\n\r\n&lt;h3&gt;Header 3&lt;/h3&gt;\r\n\r\n&lt;pre&gt;\r\n&lt;code class=&quot;language-css&quot;&gt;#header h1 a { \r\n	display: block; \r\n	width: 300px; \r\n	height: 80px; \r\n}&lt;/code&gt;&lt;/pre&gt;\r\n\r\n&lt;h4&gt;Header 4&lt;/h4&gt;\r\n\r\n&lt;ol&gt;\r\n	&lt;li&gt;Lorem ipsum dolor sit amet&lt;/li&gt;\r\n	&lt;li&gt;Consectetur adipiscing elit&lt;/li&gt;\r\n	&lt;li&gt;Donec ut est risus, placerat venenatis augue&lt;/li&gt;\r\n&lt;/ol&gt;\r\n\r\n&lt;blockquote&gt;A blockquote. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec ut est risus, placerat venenatis augue. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.&lt;/blockquote&gt;[/fr]', '', '', 'mon,site,page,accueil', 'La page Accueil de mon site', '', 'index', 'page-features.html', '1', '', 1, 0),
(2, '[fr]News[/fr]', '[fr]News[/fr]', '[fr][/fr]', 'news', '', '', '', 'news', 'index', '', '', '', 1, 0),
(3, '[fr]Contact[/fr]', '[fr]Contact[/fr]', '[fr][/fr]', 'contact', '', '', '', '', 'index-contact', NULL, '', '', 1, 0),
(4, '[fr]Login[/fr]', '[fr]Login[/fr]', '[fr]&lt;h1&gt;Module USER&lt;/h1&gt;\r\n\r\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;h3&gt;Utilisation du module USER dans votre code&lt;/h3&gt;\r\n\r\n&lt;ol&gt;\r\n	&lt;li&gt;&amp;nbsp;Editeur WYSIWYG : &lt;strong style=&quot;color: red;&quot;&gt;&amp;lsqb;CS name=sbuser icontext=1 menu=li menu_class=mymenuclass href_class=myhrefclass&amp;rsqb;&lt;/strong&gt;&lt;/li&gt;\r\n	&lt;li&gt;&amp;nbsp;Smarty Templates : &lt;strong style=&quot;color: red;&quot;&gt;{insert name=&amp;quot;sbDoShortcode&amp;quot; code=&amp;quot;&amp;lsqb;CS name=sbuser icontext=1 menu=li menu_class=mymenuclass href_class=myhrefclass&amp;rsqb;&amp;quot;}&lt;/strong&gt;&lt;/li&gt;\r\n&lt;/ol&gt;\r\n\r\n&lt;h3&gt;Les param&amp;egrave;tres&lt;/h3&gt;\r\n\r\n&lt;ul&gt;\r\n	&lt;li&gt;name : &lt;strong style=&quot;color: red;&quot;&gt;sbuser &lt;/strong&gt;(tout le temps)&lt;/li&gt;\r\n	&lt;li&gt;icontext : &lt;strong style=&quot;color: red;&quot;&gt;1&lt;/strong&gt; (activer le texte et les ic&amp;ocirc;nes - optionnel)&lt;/li&gt;\r\n	&lt;li&gt;menu : &lt;strong style=&quot;color: red;&quot;&gt;li&lt;/strong&gt; (int&amp;eacute;grer &amp;agrave; un menu ou liste &amp;agrave; puce - optionnel)&lt;/li&gt;\r\n	&lt;li&gt;menu_class : &lt;strong style=&quot;color: red;&quot;&gt;class_name&lt;/strong&gt; (ajouter une classe &amp;agrave; la balise LI - optionnel)&lt;/li&gt;\r\n	&lt;li&gt;href_class : &lt;strong style=&quot;color: red;&quot;&gt;class_name&lt;/strong&gt; (ajouter une classe &amp;agrave; la balise A&lt;/li&gt;\r\n&lt;/ul&gt;\r\n\r\n&lt;h3&gt;Module URLs&lt;/h3&gt;\r\n\r\n&lt;ul&gt;\r\n	&lt;li&gt;&lt;strong style=&quot;color: red;&quot;&gt;http://www.votresite.com/index.php?p=user&lt;/strong&gt; (mode rewrite d&amp;eacute;sactiv&amp;eacute;)&lt;/li&gt;\r\n	&lt;li&gt;&lt;strong style=&quot;color: red;&quot;&gt;http://www.votresite.com/user&lt;/strong&gt; (mode rewrite activ&amp;eacute;)&lt;/li&gt;\r\n&lt;/ul&gt;\r\n\r\n&lt;h3&gt;Exemples&lt;/h3&gt;\r\n&amp;lsqb;CS name=sbuser href_class=myhrefclass1&amp;rsqb;&lt;br /&gt;\r\n[CS name=sbuser href_class=myhrefclass1]&lt;br /&gt;\r\n&lt;br /&gt;\r\n&amp;lsqb;CS name=sbuser icontext=1 href_class=myhrefclass2&amp;rsqb;&lt;br /&gt;\r\n[CS name=sbuser icontext=1 href_class=myhrefclass2]&lt;br /&gt;\r\n&lt;br /&gt;\r\n&amp;lsqb;CS name=sbuser icontext=1 menu=li menu_class=mymenuclass3 href_class=myhrefclass3&amp;rsqb;&lt;br /&gt;\r\n[CS name=sbuser icontext=1 menu=li menu_class=mymenuclass3 href_class=myhrefclass3]&lt;br /&gt;\r\n&amp;nbsp;[/fr]', 'login', '', '', '', 'user', 'index', '', '', '', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `sb_sandbox`
--

DROP TABLE IF EXISTS `<DB_PREFIX>sb_sandbox`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_sandbox` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `telephone` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT 'Tri'
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8 COMMENT='Table SANDBOX';

--
-- Dumping data for table `sb_sandbox`
--

INSERT INTO `<DB_PREFIX>sb_sandbox` (`id`, `nom`, `telephone`, `email`, `company`, `country`, `active`) VALUES
(1, 'Rhona O. Ruiz', '06 16 84 89 00', 'diam@aliquet.org', 'Quam Vel PC', 'France', '1'),
(2, 'Madison M. Orr', '06 40 04 29 02', 'dolor.dolor.tempus@pellentesque.co.uk', 'Nec Quam Curabitur Company', 'France', '1'),
(3, 'Penelope M. Jones', '02 27 81 73 65', 'Fusce@dictumeleifendnunc.co.uk', 'Curae; Consulting', 'France', '1'),
(4, 'Flavia Z. Slater', '08 01 41 97 59', 'semper@interdumfeugiatSed.com', 'Ac Sem Ut Limited', 'France', '1'),
(5, 'Beverly M. Pratt', '05 69 65 17 99', 'In.nec.orci@pedeSuspendisse.co.uk', 'Iaculis Enim PC', 'France', '1'),
(6, 'Kenyon Y. Bates', '01 24 70 83 65', 'In.lorem@dui.edu', 'Maecenas Consulting', 'France', '1'),
(7, 'Russell W. Hoover', '03 69 51 09 10', 'rutrum@egestas.com', 'Integer Vitae LLC', 'France', '1'),
(8, 'Brynne A. Mckenzie', '09 26 08 19 59', 'Suspendisse.commodo@vel.org', 'Nulla Tempor Associates', 'France', '1'),
(9, 'Doris L. Wong', '04 37 42 95 89', 'Donec@egestas.net', 'Velit Dui Semper Ltd', 'France', '1'),
(10, 'Selma I. Palmer', '07 47 73 06 42', 'magnis.dis@eu.edu', 'Nulla PC', 'France', '1'),
(11, 'Teagan U. Craig', '03 85 93 78 87', 'malesuada@nibh.net', 'Orci Quis Corp.', 'France', '1'),
(12, 'Hall R. Allison', '08 31 05 80 29', 'Sed.eu@varius.org', 'Ultricies Company', 'France', '1'),
(13, 'Tara W. Barton', '01 27 99 15 40', 'Duis.gravida.Praesent@ligula.co.uk', 'Lectus Cum Sociis Consulting', 'France', '1'),
(14, 'Aubrey U. Mccormick', '09 09 41 81 43', 'Cras.eget.nisi@nondapibus.ca', 'Eros Non Company', 'France', '1'),
(15, 'Demetrius D. Shepard', '05 62 20 06 00', 'taciti@rhoncusidmollis.edu', 'Odio Sagittis Semper Inc.', 'France', '1'),
(16, 'Isaiah O. Morrow', '09 57 97 23 67', 'eu@mollisnon.org', 'Quis Urna Industries', 'France', '1'),
(17, 'Nolan B. Gilbert', '05 81 31 80 95', 'odio.Phasellus.at@DonecnibhQuisque.com', 'Iaculis Odio Corporation', 'France', '1'),
(18, 'Julian F. Walker', '01 60 83 21 78', 'vulputate.risus@imperdietnec.org', 'Sit Amet Massa LLC', 'France', '1'),
(19, 'Shana F. Ashley', '06 01 65 02 59', 'lorem.Donec.elementum@in.edu', 'Libero Lacus Varius Limited', 'France', '1'),
(20, 'Tanek Z. Daniels', '08 60 19 43 98', 'euismod.urna@placeratorcilacus.net', 'Et Tristique Industries', 'France', '1'),
(21, 'Jenette T. Valdez', '03 31 66 04 14', 'ornare.facilisis@semperrutrum.net', 'Ligula Corp.', 'France', '1'),
(22, 'Gillian I. Manning', '01 15 27 63 69', 'velit.Pellentesque.ultricies@tinciduntadipiscing.org', 'Magnis Dis Corporation', 'France', '1'),
(23, 'Christen G. Keller', '07 75 95 31 39', 'conubia.nostra@pellentesqueeget.ca', 'Eu Nibh Company', 'France', '1'),
(24, 'Cadman M. Pollard', '07 04 22 88 17', 'eleifend.non@lacusMaurisnon.com', 'Sed Inc.', 'France', '1'),
(25, 'Unity J. Harvey', '01 35 11 90 31', 'aliquam.arcu@mollis.net', 'Nisi Cum LLC', 'France', '1'),
(26, 'Nolan H. Stewart', '08 81 82 71 24', 'per.inceptos@nonvestibulum.com', 'Sit Amet LLP', 'France', '1'),
(27, 'Drake W. Barrett', '09 17 79 25 61', 'gravida.nunc.sed@diamdictumsapien.co.uk', 'Non Corp.', 'France', '1'),
(28, 'Madison Q. Beasley', '08 30 31 52 20', 'volutpat.nunc.sit@Inlorem.ca', 'Facilisis Eget Consulting', 'France', '1'),
(29, 'Bo M. Benjamin', '02 09 13 22 56', 'lacus.Mauris@nisiMauris.net', 'Euismod Mauris Eu Ltd', 'France', '1'),
(30, 'Zena B. Kelly', '05 66 38 07 65', 'felis.Nulla@pharetraNamac.com', 'Velit Pellentesque Corporation', 'France', '1'),
(31, 'Sara N. Bird', '02 15 02 94 56', 'ultricies@bibendumDonec.net', 'Nunc Ac Ltd', 'France', '1'),
(32, 'Geraldine G. Turner', '08 78 45 81 04', 'primis.in.faucibus@purusNullam.com', 'Maecenas Malesuada Corporation', 'France', '1'),
(33, 'Kasimir L. Arnold', '07 55 25 41 16', 'imperdiet@necdiamDuis.co.uk', 'Orci Luctus Associates', 'France', '1'),
(34, 'Camden H. Johns', '07 96 85 23 87', 'nibh.Aliquam.ornare@Phasellus.org', 'Donec Nibh Institute', 'France', '1'),
(35, 'Allen A. Salas', '04 24 25 31 89', 'gravida.non@pretium.ca', 'Erat Neque Industries', 'France', '1'),
(36, 'Cherokee D. Gentry', '09 77 97 95 96', 'sem@estmollis.com', 'Aliquet Nec Industries', 'France', '1'),
(37, 'Tanya K. Mcgowan', '01 50 43 45 99', 'Nulla.tempor.augue@duiquis.ca', 'Malesuada Integer Id Limited', 'France', '1'),
(38, 'Alvin Q. Townsend', '03 96 29 72 72', 'bibendum@sapien.org', 'Donec Feugiat Metus Inc.', 'France', '1'),
(39, 'Beck O. Spence', '03 74 29 83 47', 'sit.amet.orci@dolorelit.ca', 'Fringilla Donec Feugiat Institute', 'France', '1'),
(40, 'Shelby H. Buck', '03 76 30 93 12', 'nec@ametluctus.net', 'Ut Eros Non Limited', 'France', '1'),
(41, 'Darryl V. Morgan', '09 37 79 57 38', 'magnis@arcuMorbi.edu', 'Sem Eget Massa Corp.', 'France', '1'),
(42, 'Calista K. Gaines', '09 89 40 09 89', 'id.mollis@ametluctus.ca', 'Augue Industries', 'France', '1'),
(43, 'Melinda O. Adams', '02 68 90 76 32', 'malesuada.id@iaculisaliquet.edu', 'Mollis Non Cursus Industries', 'France', '1'),
(44, 'Risa V. Lott', '02 82 89 78 44', 'lacus@enimdiamvel.org', 'Lacus PC', 'France', '1'),
(45, 'Hashim K. Huber', '06 71 11 83 38', 'elementum@Vivamuseuismod.ca', 'Elementum PC', 'France', '1'),
(46, 'Evan G. Cleveland', '04 03 92 71 02', 'nunc@aliquam.co.uk', 'Massa LLP', 'France', '1'),
(47, 'Summer K. Morrison', '02 99 87 40 45', 'eu.arcu.Morbi@sodaleselit.edu', 'Cursus Associates', 'France', '1'),
(48, 'Wesley X. Graham', '09 15 82 55 46', 'erat.Vivamus.nisi@euismod.org', 'Nibh Quisque Consulting', 'France', '1'),
(49, 'Cedric R. Madden', '01 84 80 55 30', 'Aliquam.ultrices.iaculis@felis.co.uk', 'Ultricies Adipiscing Institute', 'France', '1'),
(50, 'Calista Y. Wade', '08 87 68 64 55', 'magna@ultriciesdignissim.net', 'Ornare Institute', 'France', '1'),
(51, 'Leslie F. Carlson', '09 20 70 23 94', 'orci.quis@odiovelest.com', 'Molestie Sed Id Corporation', 'France', '1'),
(52, 'Odette Q. Mcfarland', '04 02 21 68 00', 'consectetuer.adipiscing.elit@accumsanneque.org', 'Molestie Sodales Mauris Corp.', 'France', '1'),
(53, 'Elton H. Potts', '09 91 21 03 07', 'vitae.sodales@loremut.org', 'Proin Mi Corporation', 'France', '1'),
(54, 'Moses S. Blake', '08 44 97 61 28', 'Donec.est@penatibuset.com', 'Et Industries', 'France', '1'),
(55, 'Jenette Y. Wong', '01 57 62 94 20', 'dui.lectus.rutrum@odio.ca', 'Suspendisse Sed LLC', 'France', '1'),
(56, 'Axel A. Valencia', '09 60 11 93 46', 'eget.varius.ultrices@nequesedsem.net', 'Aliquam Nisl LLC', 'France', '1'),
(57, 'Kiayada L. Smith', '07 93 60 29 29', 'ac@amet.ca', 'Lacus Limited', 'France', '1'),
(58, 'Chelsea W. Acosta', '01 05 76 47 33', 'arcu.Curabitur@idmagna.co.uk', 'Mauris Ipsum Corporation', 'France', '1'),
(59, 'Iona T. Raymond', '09 81 59 71 20', 'neque@lorem.edu', 'Aliquam Ornare Libero Corp.', 'France', '1'),
(60, 'Zoe V. Merrill', '03 63 94 98 60', 'bibendum@tristiquesenectus.edu', 'Accumsan Sed Industries', 'France', '1'),
(61, 'Merrill N. Bernard', '05 78 24 05 83', 'Morbi@nasceturridiculusmus.org', 'Leo Cras Vehicula Associates', 'France', '1'),
(62, 'Price B. Hensley', '07 38 42 56 41', 'justo@lacinia.edu', 'Lacus Incorporated', 'France', '1'),
(63, 'Whoopi O. Haley', '06 53 75 37 58', 'arcu.iaculis@convalliserateget.com', 'Ut Inc.', 'France', '1'),
(64, 'Sandra D. Parker', '09 86 61 24 29', 'Morbi@penatibusetmagnis.com', 'Nonummy LLC', 'France', '1'),
(65, 'Azalia C. Lowery', '07 41 16 25 82', 'Mauris.quis@ornareplacerat.ca', 'Lacus Consulting', 'France', '1'),
(66, 'Drake K. Salinas', '04 25 46 76 19', 'Integer.in.magna@egetmagnaSuspendisse.net', 'Ut Semper Limited', 'France', '1'),
(67, 'Pamela D. Evans', '04 62 26 99 63', 'nisi.magna.sed@quamquisdiam.co.uk', 'Egestas Nunc Sed Inc.', 'France', '1'),
(68, 'Deacon X. Sanchez', '07 19 06 78 75', 'accumsan.sed.facilisis@justofaucibuslectus.net', 'Est Ac Mattis Company', 'France', '1'),
(69, 'Gay H. Walters', '09 11 86 77 98', 'tellus.lorem.eu@variuseteuismod.co.uk', 'Malesuada Fringilla Ltd', 'France', '1'),
(70, 'Lars X. Pollard', '04 23 96 41 51', 'arcu.vel.quam@fringillacursuspurus.com', 'Porttitor Consulting', 'France', '1'),
(71, 'Nomlanga H. Mendoza', '01 63 27 59 59', 'magna.tellus.faucibus@tempuseuligula.edu', 'Ac Inc.', 'France', '1'),
(72, 'Quinn B. Reid', '07 67 49 59 85', 'euismod.ac@quismassa.com', 'Lacinia Mattis Inc.', 'France', '1'),
(73, 'Tyrone Q. Woodward', '08 68 52 35 78', 'tristique.aliquet@feugiat.org', 'In Sodales Foundation', 'France', '1'),
(74, 'Lysandra E. Townsend', '04 11 95 77 42', 'Etiam.gravida.molestie@semPellentesque.net', 'Parturient Montes Nascetur PC', 'France', '1'),
(75, 'Cailin A. Jensen', '07 73 56 39 38', 'Mauris.eu@atfringillapurus.ca', 'Eget Venenatis Consulting', 'France', '1'),
(76, 'Chadwick N. Hess', '08 91 77 25 73', 'lacus.varius@vulputatelacus.edu', 'Enim Curabitur Massa Limited', 'France', '1'),
(77, 'Heidi T. Hicks', '06 11 33 27 69', 'nec.leo.Morbi@fringillaeuismodenim.org', 'Sed Dictum Eleifend Corporation', 'France', '1'),
(78, 'Nomlanga T. Roth', '02 46 63 79 04', 'ullamcorper.nisl@musDonec.net', 'Risus Varius Orci Associates', 'France', '1'),
(79, 'Erica A. Rush', '07 31 98 30 14', 'elit.pharetra.ut@Donecnibh.org', 'Hendrerit Incorporated', 'France', '1'),
(80, 'Karly C. Pratt', '08 05 32 89 13', 'Cras.sed@PraesentluctusCurabitur.org', 'Vitae Mauris Associates', 'France', '1'),
(81, 'Athena V. Emerson', '08 17 75 61 38', 'Fusce@lectusantedictum.ca', 'Fringilla Est Mauris Incorporated', 'France', '1'),
(82, 'Leah K. Haney', '01 65 33 03 86', 'Sed.congue@ridiculus.com', 'Dolor Sit PC', 'France', '1'),
(83, 'Brittany W. Pickett', '09 82 13 30 15', 'eu.ultrices.sit@tortordictumeu.co.uk', 'Pharetra Nam Ac LLP', 'France', '1'),
(84, 'Amela L. Torres', '01 15 23 30 69', 'odio@senectusetnetus.ca', 'Pellentesque Ltd', 'France', '1'),
(85, 'Lillian Y. Reeves', '08 89 37 77 87', 'nec@odio.edu', 'Lectus Sit Company', 'France', '1'),
(86, 'Garrison J. Carney', '03 66 78 62 24', 'morbi.tristique.senectus@parturientmontesnascetur.co.uk', 'At Augue PC', 'France', '1'),
(87, 'Calista P. Rivera', '06 07 91 41 63', 'cursus@nonleoVivamus.com', 'Diam Sed Consulting', 'France', '1'),
(88, 'Louis A. Wooten', '06 53 30 02 84', 'mi.lorem@anteMaecenas.org', 'Duis Consulting', 'France', '1'),
(89, 'Ginger M. Vargas', '08 16 18 04 94', 'erat@sed.ca', 'Lacus Varius Et Company', 'France', '1'),
(90, 'Iris A. Fry', '09 40 86 76 14', 'gravida.nunc@Aeneangravida.org', 'Eu Placerat Eget Institute', 'France', '1'),
(91, 'Kylan N. Velazquez', '03 55 67 74 74', 'purus@Duisami.edu', 'Dui Fusce Industries', 'France', '1'),
(92, 'Serina Q. Guthrie', '07 57 39 67 07', 'euismod.est.arcu@pretiumnequeMorbi.net', 'Duis Company', 'France', '1'),
(93, 'Rajah E. Bradshaw', '06 34 76 68 53', 'velit.Pellentesque@at.net', 'Cras Limited', 'France', '1'),
(94, 'Ocean O. Spence', '02 64 94 39 44', 'euismod.est.arcu@metus.ca', 'Magna Industries', 'France', '1'),
(95, 'Phyllis P. Nieves', '07 24 20 04 82', 'molestie.tortor@fringillaestMauris.ca', 'Nullam Vitae Foundation', 'France', '1'),
(96, 'Samantha B. Dalton', '09 59 58 38 83', 'hendrerit.consectetuer@vulputateeu.co.uk', 'In Institute', 'France', '1'),
(97, 'Mona T. Zamora', '06 09 84 71 62', 'Nunc@aliquam.co.uk', 'Quam Quis Consulting', 'France', '1'),
(98, 'Jacob X. Sosa', '05 29 42 26 31', 'Sed.diam.lorem@inhendrerit.co.uk', 'Libero Inc.', 'France', '1'),
(99, 'Jillian X. Dunn', '05 13 05 75 50', 'luctus@In.org', 'Ut Institute', 'France', '1'),
(100, 'Lucy B. Merritt', '08 40 04 13 70', 'eget@dictum.co.uk', 'Dis Parturient Montes Company', 'France', '1');

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
  `groupe` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table Users';

--
-- Dumping data for table `sb_users`
--

INSERT INTO `<DB_PREFIX>sb_users` (`id`, `username`, `password`, `email`, `logintime`, `lastlogin`, `active`, `activekey`, `resetkey`, `menu`, `groupe`) VALUES
(1, 'admin', 'OUovZTFHdGNmaThNL1RZU0tyVXNmZz09Ojrdt++k07oZd9AcRrsXNqow', 'admin-reply@votresite.com', 0, 0, 1, '0', '0', '', '');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for dumped tables
--

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
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
