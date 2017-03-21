-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Client :  localhost:3306
-- Généré le :  Lun 02 Janvier 2017 à 09:31
-- Version du serveur :  5.5.42
-- Version de PHP :  5.6.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `sbmagic_new`
--

-- --------------------------------------------------------

--
-- Structure de la table `pr08T5y_sb_attempts`
--

CREATE TABLE `pr08T5y_sb_attempts` (
  `ip` varchar(15) NOT NULL,
  `count` int(11) NOT NULL,
  `expiredate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table Attempts';

-- --------------------------------------------------------

--
-- Structure de la table `pr08T5y_sb_blocs`
--

CREATE TABLE `pr08T5y_sb_blocs` (
  `id` int(11) NOT NULL,
  `pages_id` varchar(255) NOT NULL COMMENT 'Pages IDs (separate by | )',
  `modules_id` varchar(255) NOT NULL COMMENT 'Modules IDs (separate by | )',
  `name` varchar(100) NOT NULL COMMENT 'Nom du bloc',
  `title` text NOT NULL COMMENT 'titre du bloc (cÃ´tÃ© client)',
  `content` text NOT NULL COMMENT 'Contenu du bloc',
  `position` varchar(100) NOT NULL,
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `sort` int(11) NOT NULL COMMENT 'Tri des blocs'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='Blocs associÃ©s aux pages';

--
-- Contenu de la table `pr08T5y_sb_blocs`
--

INSERT INTO `pr08T5y_sb_blocs` (`id`, `pages_id`, `modules_id`, `name`, `title`, `content`, `position`, `active`, `sort`) VALUES
(1, '4|6', 'contact|news|search', 'Contact TEST', '[fr][/fr]', '[fr]&lt;div class=&quot;widget-first widget contact-info&quot;&gt;\r\n&lt;h3&gt;Contacts&lt;/h3&gt;\r\n\r\n&lt;div class=&quot;sidebar-nav&quot;&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;Location: DOLLAR, Normandie - France&lt;/li&gt;\r\n	&lt;li&gt;Phone: 02 31 65 38 91&lt;/li&gt;\r\n	&lt;li&gt;Fax: +39 0035 356 765&lt;/li&gt;\r\n	&lt;li&gt;Email: dollar@dollar.fr&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;/div&gt;\r\n&lt;/div&gt;[/fr]', '', 1, 0),
(2, '2', 'effectives|news', 'News R&eacute;centes', '[fr]Derniers articles[/fr]', '[fr][CS name=sbnews_blocks_recent count=3 truncate=40 id=1]&lt;br /&gt;\r\n[CS name=sbnews_blocks_recent count=3 truncate=40 id=2][/fr]', '', 1, 0),
(3, '2|3', 'news', 'TEST New bloc news', '[fr]New block NEWS[/fr]', '[fr]&lt;img alt=&quot;&quot; src=&quot;http://localhost/sbmagic_new/upload//2000px-apple-logo-black-svg-3.png&quot; style=&quot;width: 50px; height: 50px;&quot; /&gt;[/fr]', '', 1, 0),
(4, '6', '', 'Bloc 1', '[fr][/fr]', '[fr]Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut in sem turpis. Ut congue, nunc quis pulvinar feugiat, ligula felis viverra nisi, id blandit turpis sapien eu arcu. Suspendisse potenti. Morbi bibendum tincidunt tortor, nec maximus ligula posuere et. Ut at malesuada metus, ultrices faucibus ipsum. Suspendisse potenti. Quisque accumsan magna ligula, eget tempus lectus gravida eu. Nam tincidunt leo lacus.[/fr]', '', 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `pr08T5y_sb_blocs_sort`
--

CREATE TABLE `pr08T5y_sb_blocs_sort` (
  `id` int(11) NOT NULL,
  `bloc_id` int(11) NOT NULL COMMENT 'ID des blocs',
  `page_id` int(11) NOT NULL COMMENT 'ID des pages',
  `module_id` varchar(50) NOT NULL COMMENT 'Nom du module (nom du répertoire)',
  `sort` int(11) NOT NULL COMMENT 'Tri des blocs par page'
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `pr08T5y_sb_blocs_sort`
--

INSERT INTO `pr08T5y_sb_blocs_sort` (`id`, `bloc_id`, `page_id`, `module_id`, `sort`) VALUES
(40, 2, 2, '', 2),
(41, 2, 0, 'effectives', 0),
(42, 2, 0, 'news', 0),
(54, 3, 2, '', 0),
(55, 3, 3, '', 0),
(56, 3, 0, 'news', 0),
(62, 0, 6, '', 0),
(63, 1, 4, '', 0),
(64, 1, 6, '', 0),
(65, 1, 0, 'contact', 0),
(66, 1, 0, 'news', 0),
(67, 1, 0, 'search', 0);

-- --------------------------------------------------------

--
-- Structure de la table `pr08T5y_sb_config`
--

CREATE TABLE `pr08T5y_sb_config` (
  `id` int(11) NOT NULL,
  `config` varchar(50) NOT NULL COMMENT 'Nom de la configuration',
  `content` text NOT NULL COMMENT 'Valeur de la configuration'
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `pr08T5y_sb_config`
--

INSERT INTO `pr08T5y_sb_config` (`id`, `config`, `content`) VALUES
(1, 'css', '.fright {float: right;}\r\n.fcenter {float: center;}\r\n.fleft {float: left;}\r\n.aright {text-align: right;}\r\n.acenter {text-align: center;}\r\n.aleft {text-align: left;}\r\n.dnone {display: none !important;}\r\n\r\n\r\n/* $$$$$$$$$$$$$$$$$$$$$$ */\r\n/* Update CSS Style theme */\r\n/* $$$$$$$$$$$$$$$$$$$$$$ */\r\n\r\n/* =============== */\r\n/* === General === */\r\n/* =============== */\r\nh1, h2, h3, h4, h5 {\r\n	font-family: &quot;Lora&quot;, serif;\r\n	margin-bottom: 40px;\r\n	text-transform: uppercase;\r\n}\r\nh1 {font-size: 2em}\r\nh2 {font-size: 1.7em}\r\nh3 {font-size: 1.4em}\r\nh4 {font-size: 1.2em}\r\nh5 {font-size: 1em}'),
(2, 'javascript', 'jQuery(document).ready(function() {\r\n    \r\n});'),
(3, 'header', '[fr]&amp;ldquo; Pourquoi se jeter &amp;agrave; l&amp;#39;eau avant que la barque n&amp;#39;ait chavir&amp;eacute; ? &amp;rdquo;&lt;br /&gt;\r\n&lt;strong style=&quot;color: black;&quot;&gt;Agence DOLLAR&lt;/strong&gt;[/fr]'),
(4, 'footer', '[fr]&amp;copy; [CS name=sbyear] &amp;bull; www.votresite.com &amp;bull; Cr&amp;eacute;&amp;eacute; &amp;amp; r&amp;eacute;alis&amp;eacute; par &lt;a href=&quot;//informatux.com&quot; target=&quot;_blank&quot;&gt;informatux.com&lt;/a&gt;[/fr]'),
(5, 'email_to', 'patrice@dollar.fr'),
(6, 'email_publickey', '6Le5yigTAAAAAFbkFGVio97hx7A2BZ_ZeDuageVz'),
(7, 'email_privatekey', '6Le5yigTAAAAANXobtlzJPGOKSKjlvAPBofjYvS_'),
(8, 'email_subject', '[fr]Message de votre site[/fr]'),
(9, 'coming-soon', '1'),
(10, 'coming-soon-url', 'comingsoon'),
(11, 'coming-soon-title', 'Ma belle entreprise'),
(12, 'coming-soon-title2', 'La piu bella'),
(13, 'coming-soon-text', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam pretium tellus eget justo volutpat suscipit. Nullam in tellus ac lectus pulvinar molestie et ut arcu. Fusce non nisl quis metus pharetra luctus. Duis massa urna, scelerisque vitae felis consequat, lacinia egestas risus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Donec nisl nunc, tempus quis ex id, posuere convallis justo. Integer sed turpis id dolor congue elementum vel in metus. Nullam porta, erat vitae vehicula dapibus, augue mi congue elit, ac cursus ligula turpis eget urna. Praesent lorem quam, tincidunt et mi eu, lacinia mattis ex. Sed feugiat luctus lacinia. Cras malesuada, tellus vitae ultricies pulvinar, diam tellus rutrum lacus, ac consequat risus odio id ex. Suspendisse vehicula urna sem, eu dictum lacus sagittis eget. Ut fringilla, ante commodo bibendum hendrerit, est ipsum sollicitudin lectus, a suscipit nulla ipsum eget augue.'),
(14, 'coming-soon-tel', '02 32 45 67 89'),
(15, 'coming-soon-address', 'Haras de la Hetraie, 50680 CERISY LA FOR&Ecirc;T'),
(16, 'coming-soon-email', 'info@ma-belle-entreprise.com'),
(17, 'coming-soon-facebook', '#'),
(18, 'coming-soon-twitter', '#'),
(19, 'coming-soon-youtube', '#'),
(20, 'multilang', '0'),
(21, 'plugins', 'lightbox'),
(28, 'fonts', '&lt;link href=&quot;https://fonts.googleapis.com/css?family=Almendra+Display&quot; rel=&quot;stylesheet&quot;&gt;\r\n&lt;link href=&quot;https://fonts.googleapis.com/css?family=Ribeye+Marrow&quot; rel=&quot;stylesheet&quot;&gt;'),
(29, 'seo-keywords', 'keywords,general,seo'),
(30, 'seo-description', 'SEO Description g&eacute;n&eacute;ral CMS Configuration'),
(31, 'coming-soon-type', 'video-youtube'),
(32, 'coming-soon-image', ''),
(33, 'coming-soon-video', 'Ekr05T9Iaio'),
(34, 'coming-soon-dark', '0'),
(35, 'coming-soon-date', '30/11/2016'),
(36, 'coming-soon-google-plus', '#'),
(37, 'toolbarck', '0');

-- --------------------------------------------------------

--
-- Structure de la table `pr08T5y_sb_contact`
--

CREATE TABLE `pr08T5y_sb_contact` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `recipients` text NOT NULL COMMENT 'destinataires',
  `subject` text NOT NULL,
  `form` text NOT NULL,
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `sort` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `pr08T5y_sb_contact`
--

INSERT INTO `pr08T5y_sb_contact` (`id`, `title`, `recipients`, `subject`, `form`, `active`, `sort`) VALUES
(3, 'R&eacute;servation de saillie', '', '[fr]R&eacute;servation de saillie[/fr]', '&lt;fieldset&gt;\r\n&lt;ul&gt;\r\n&lt;li class=&quot;text-field&quot;&gt;\r\n&lt;div class=&quot;input-prepend&quot;&gt;&lt;span class=&quot;add-on&quot;&gt;&lt;i class=&quot;icon-user&quot;&gt;&lt;/i&gt;&lt;/span&gt;\r\n[TEXT name=name/required=required/placeholder=Votre nom]&lt;/div&gt;\r\n&lt;/li&gt;\r\n&lt;li class=&quot;text-field&quot;&gt;\r\n&lt;div class=&quot;input-prepend&quot;&gt;&lt;span class=&quot;add-on&quot;&gt;&lt;i class=&quot;icon-phone&quot;&gt;&lt;/i&gt;&lt;/span&gt;\r\n[TEXT name=telephone/required=required/placeholder=Votre t&eacute;l&eacute;phone]&lt;/div&gt;\r\n&lt;/li&gt;\r\n&lt;li class=&quot;text-field&quot;&gt;\r\n&lt;div class=&quot;input-prepend&quot;&gt;&lt;span class=&quot;add-on&quot;&gt;&lt;i class=&quot;icon-envelope&quot;&gt;&lt;/i&gt;&lt;/span&gt;\r\n[TEXT name=email/required=required/placeholder=Votre email]&lt;/div&gt;\r\n&lt;/li&gt;\r\n&lt;li class=&quot;text-field&quot;&gt;\r\n&lt;div class=&quot;input-prepend&quot;&gt;\r\n[SELECT name=etalon/options=Choisissez un &eacute;talon|Network|Buck&#039;s Boom|Kitkou/value=0|Network|Buck&#039;s Boom|Kitkou/required=required/style=border: 1px solid lightgrey;]&lt;/div&gt;\r\n&lt;/li&gt;\r\n&lt;li class=&quot;text-field&quot;&gt;\r\n&lt;div class=&quot;input-prepend&quot;&gt;\r\n[TEXT name=jument/required=required/placeholder=Nom de la jument]&lt;/div&gt;\r\n&lt;/li&gt;\r\n&lt;li class=&quot;text-field&quot;&gt;\r\n&lt;div class=&quot;input-prepend&quot;&gt;\r\n[TEXT name=sire/required=required/placeholder=N&deg; de SIRE]&lt;/div&gt;\r\n&lt;/li&gt;\r\n&lt;li class=&quot;text-field&quot;&gt;\r\n&lt;div class=&quot;input-prepend&quot;&gt;\r\n[TEXT name=pere/required=required/placeholder=P&egrave;re]&lt;/div&gt;\r\n&lt;/li&gt;\r\n&lt;li class=&quot;text-field&quot;&gt;\r\n&lt;div class=&quot;input-prepend&quot;&gt;\r\n[TEXT name=mere/required=required/placeholder=M&egrave;re]&lt;/div&gt;\r\n&lt;/li&gt;\r\n&lt;li class=&quot;text-field&quot;&gt;\r\n&lt;div class=&quot;input-prepend&quot;&gt;\r\n[TEXT name=saillie-en-2016-par/required=required/placeholder=Saillie en 2016 par]&lt;/div&gt;\r\n&lt;/li&gt;\r\n&lt;li class=&quot;text-field&quot;&gt;\r\n&lt;div class=&quot;input-prepend&quot;&gt;\r\n[TEXT name=resultat-2016/required=required/placeholder=R&eacute;sultat]&lt;/div&gt;\r\n&lt;/li&gt;\r\n&lt;li class=&quot;text-field&quot;&gt;\r\n&lt;div class=&quot;input-prepend&quot;&gt;\r\n[TEXT name=saillie-en-2015-par/required=required/placeholder=Saillie en 2015 par]&lt;/div&gt;\r\n&lt;/li&gt;\r\n&lt;li class=&quot;text-field&quot;&gt;\r\n&lt;div class=&quot;input-prepend&quot;&gt;\r\n[TEXT name=resultat-2015/required=required/placeholder=R&eacute;sultat]&lt;/div&gt;\r\n&lt;/li&gt;\r\n&lt;li class=&quot;text-field&quot;&gt;\r\n&lt;div class=&quot;input-prepend&quot;&gt;\r\nMAIDEN&amp;nbsp;&amp;nbsp;[SELECT name=maiden/options=oui|non/value=oui|non/required=required]&lt;/div&gt;\r\n&lt;/li&gt;\r\n&lt;li class=&quot;text-field&quot; style=&quot;width: 100%;&quot;&gt;\r\n&lt;div class=&quot;input-prepend&quot;&gt;\r\n[RECAPTCHA]&lt;/div&gt;\r\n&lt;/li&gt;\r\n&lt;li class=&quot;submit-button&quot;&gt;\r\n&lt;div class=&quot;input-prepend&quot;&gt;\r\n[SUBMIT name=go/value=Envoyer/class=sendmail aligncenter]&lt;/div&gt;\r\n&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;/fieldset&gt;', 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `pr08T5y_sb_country`
--

CREATE TABLE `pr08T5y_sb_country` (
  `country_iso` char(2) NOT NULL,
  `country_name` varchar(80) NOT NULL,
  `country_printable_name` varchar(80) NOT NULL,
  `country_iso3` char(3) DEFAULT NULL,
  `country_numcode` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `pr08T5y_sb_country`
--

INSERT INTO `pr08T5y_sb_country` (`country_iso`, `country_name`, `country_printable_name`, `country_iso3`, `country_numcode`) VALUES
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
('CI', 'COTE D''IVOIRE', 'Cote D''Ivoire', 'CIV', 384),
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
('KP', 'KOREA, DEMOCRATIC PEOPLE''S REPUBLIC OF', 'Korea, Democratic People''s Republic of', 'PRK', 408),
('KR', 'KOREA, REPUBLIC OF', 'Korea, Republic of', 'KOR', 410),
('KW', 'KUWAIT', 'Kuwait', 'KWT', 414),
('KY', 'CAYMAN ISLANDS', 'Cayman Islands', 'CYM', 136),
('KZ', 'KAZAKHSTAN', 'Kazakhstan', 'KAZ', 398),
('LA', 'LAO PEOPLE''S DEMOCRATIC REPUBLIC', 'Lao People''s Democratic Republic', 'LAO', 418),
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
-- Structure de la table `pr08T5y_sb_effectives`
--

CREATE TABLE `pr08T5y_sb_effectives` (
  `id` int(11) NOT NULL,
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
  `pedigree_extend` varchar(255) NOT NULL COMMENT 'Fichier PDF supplÃ©mentaire',
  `pedigree_desc` text NOT NULL,
  `breeder` varchar(100) NOT NULL COMMENT 'Eleveur',
  `owner` varchar(100) NOT NULL COMMENT 'PropriÃ©taire',
  `description` text NOT NULL,
  `description_extend` text NOT NULL COMMENT 'Description supplémentaire',
  `headpage` text NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `pr08T5y_sb_effectives`
--

INSERT INTO `pr08T5y_sb_effectives` (`id`, `catid`, `name`, `subtitle1`, `subtitle2`, `photo`, `date`, `sex`, `chrono`, `winnings`, `status`, `colour`, `size`, `sire`, `dam`, `sire_dam`, `projection`, `origine`, `pedigree`, `pedigree_extend`, `pedigree_desc`, `breeder`, `owner`, `description`, `description_extend`, `headpage`, `active`, `sort`) VALUES
(1, 1, 'NETWORK', '', '', 'le-haras-a-propos.jpg', '1997-09-01', 'M', '', '10 173 100 &euro;', '', '[fr]Noir Pangar&eacute;[/fr]', '1,69m', 'MONSUN (GER)', 'NOTE (GER)', 'RELIANCE (FR)', '10.000 &euro; HT Poulain Vivant Prix Public', 'Allemagne', '', '', '&lt;a href=&quot;http://haras-enki.com/upload/NETWORK-PEDIGREE_600x326.jpg&quot; data-lightbox=&quot;Network&quot; data-title=&quot;Pedigree Network&quot;&gt;&lt;img alt=&quot;&quot; src=&quot;http://haras-enki.com/upload/NETWORK-PEDIGREE_600x326.jpg&quot; width=&quot;100%&quot; /&gt;&lt;/a&gt;', 'Gest&uuml;t Wittekindshof', '', '[fr]Champion &amp;eacute;talon, Network est un fils du recherch&amp;eacute; Monsun. Gagnant de Gr.2 en plat, il est le p&amp;egrave;re du meilleur steeple-chaser d&amp;#39;Angleterre Sprinter Sacr&amp;eacute;, laur&amp;eacute;at de 9 Gr.1 dont deux &amp;eacute;ditions du &amp;quot;Queen Mother Chapion Chase&amp;quot; &amp;agrave; Cheltenham, des vainqueurs de Gr.1 Rubi Ball, Saint Are, Rubi Light, d&amp;#39;Adriana des Mottes, laur&amp;eacute;ate de Gr.1 en 2014, de Voiladenuo (Prix Leon Rambaud Gr.2), Vent Sombre (Grand Steeple-Chase d&amp;#39;Enghien Gr.2, Grand-Prix de Pau Gr.3) en 2015 et encore de Ball d&amp;#39;Arc (Gr.2) et d&amp;#39;Acapella Bourgeois (Gr.2) en 2016 ...&amp;nbsp;Acquis 290.000&amp;euro; &amp;agrave; la vente d&amp;#39;&amp;eacute;levage Arqana en d&amp;eacute;cembre 2015 &amp;agrave; Deauville, il s&amp;#39;installe au Haras d&amp;#39;Enki dans l&amp;#39;Allier en 2016.&lt;br /&gt;\r\n&lt;span class=&quot;marker&quot;&gt;A MODIFIER SI BESOIN ....&lt;/span&gt;[/fr]', '[fr][CS id=3 name=sbcontact class=contact-form][/fr]', '2', 1, 1),
(2, 1, 'BUCK&#039;S BOUM', '', '', 'buck-s-boum.jpg', '2005-04-26', 'M', '', '', '', '[fr]Bai[/fr]', '1,68m', 'CADOUDAL (FR)', 'BUCK&#039;S (FR)', 'LE GLORIEUX (GB)', '3.000 &euro; HT Poulain Vivant', 'France', '', 'BUCKS-BOUM-extend.pdf', '&lt;img alt=&quot;&quot; src=&quot;http://haras-enki.com/upload/PEDIGREE-BUCK-S-BOUM.jpg&quot; /&gt;', 'Henri Poulat', '', '[fr]PRINCIPALES PERFORMANCES&lt;br /&gt;\r\n3 ans : 1er Prix de Grenoble (Auteuil - Haies - 3 600 m) - 2&amp;egrave; Prix&amp;nbsp;Cambac&amp;eacute;r&amp;egrave;s Gr.1 (Auteuil - Haies - 3 600 m) - 3&amp;egrave; Prix de l&amp;rsquo;Hippodrome&amp;nbsp;du Ch&amp;acirc;teau Gontier (Auteuil - Haies - 3 600 m) - 4&amp;egrave; Prix du Douet&amp;nbsp;(Clairefontaine - Haies - 3 200 m).&amp;nbsp;4 ans : 5&amp;egrave; Prix Amadou Gr.2 (Auteuil - Haies - 3 900 m).&lt;br /&gt;\r\n&lt;br /&gt;\r\n&lt;span class=&quot;marker&quot;&gt;TEXTE A COMPLETER / MODIFIER&lt;/span&gt;[/fr]', '[fr][CS id=3 name=sbcontact class=contact-form][/fr]', '3', 1, 2),
(3, 1, 'KITKOU', '', '', 'KITKOU.jpg', '2013-04-19', 'M', '', '', '', '[fr]Bai[/fr]', '1,68m', 'MARTALINE (GB)', 'KOTKITA (FR)', 'SUBOTICA (FR)', '1.500&euro; HT Poulain Vivant', 'France', 'KITKOU_fiche_HN.pdf', '', '&lt;img alt=&quot;&quot; src=&quot;http://haras-enki.com/upload/PEDIGREE-KITKOU.jpg&quot; /&gt;', 'Comte Pierre de Montesson', '', '[fr]Propre fr&amp;egrave;re de la championne 2015 Kotkikova, donc n&amp;eacute; de la t&amp;ecirc;te de liste Martaline et de la souche formidable souche &amp;quot;K&amp;quot; du Haras des Coudraies, Kitkou d&amp;eacute;bute &amp;eacute;talon en 2016 au Haras d&amp;#39;Enki.&lt;br /&gt;\r\nConserv&amp;eacute; entier pour devenir &amp;eacute;talon, Kitkou est rest&amp;eacute; in&amp;eacute;dit des suites d&amp;#39;un souci d&amp;#39;un voile du palet qui l&amp;#39;aurait contraint &amp;agrave; une op&amp;eacute;ration donc la castration. C&amp;#39;est pourquoi il rentre au haras d&amp;egrave;s l&amp;#39;&amp;acirc;ge de 3 ans, pr&amp;eacute;sentant d&amp;eacute;j&amp;agrave; un fort mod&amp;egrave;le d&amp;#39;1,68 m.&lt;br /&gt;\r\nTriple syst&amp;egrave;me de prime :\r\n&lt;ul&gt;\r\n	&lt;li&gt;Prime de 10.000 &amp;euro; &amp;agrave; l&amp;#39;&amp;eacute;leveur du Premier Pur Sang gagnant en Obstacle.&lt;/li&gt;\r\n	&lt;li&gt;Prime de 10.000 &amp;euro; &amp;agrave; l&amp;#39;&amp;eacute;leveur du Premier AQPS gagnant en Obstacle.&lt;/li&gt;\r\n	&lt;li&gt;Prime de 10.000 &amp;euro; au propri&amp;eacute;taire du Premier gagnant en Obstacle.&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;br /&gt;\r\n&lt;span class=&quot;marker&quot;&gt;A MODIFIER SI BESOIN ....&lt;/span&gt;[/fr]', '[fr][CS id=3 name=sbcontact class=contact-form][/fr]', '4', 1, 3);

-- --------------------------------------------------------

--
-- Structure de la table `pr08T5y_sb_effectives_category`
--

CREATE TABLE `pr08T5y_sb_effectives_category` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `subtitle` text NOT NULL,
  `tpl_list` text NOT NULL,
  `tpl_single` text NOT NULL,
  `module_show` varchar(50) NOT NULL COMMENT 'normal,masonry,...',
  `module_show_masonry` int(11) NOT NULL COMMENT 'columns width (pixels)',
  `photo` varchar(255) NOT NULL,
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `sort` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `pr08T5y_sb_effectives_category`
--

INSERT INTO `pr08T5y_sb_effectives_category` (`id`, `title`, `subtitle`, `tpl_list`, `tpl_single`, `module_show`, `module_show_masonry`, `photo`, `active`, `sort`) VALUES
(1, '[fr]Etalons[/fr]', '[fr][/fr]', '', '', 'masonrycss', 400, 'le-haras-a-propos.jpg', 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `pr08T5y_sb_effectives_medias`
--

CREATE TABLE `pr08T5y_sb_effectives_medias` (
  `id` int(11) NOT NULL,
  `eid` int(11) NOT NULL COMMENT 'Effective ID',
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) NOT NULL,
  `url` text NOT NULL,
  `type` varchar(10) NOT NULL COMMENT 'pdf, youtube, video, photo',
  `active` tinyint(1) NOT NULL,
  `sort` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `pr08T5y_sb_effectives_medias`
--

INSERT INTO `pr08T5y_sb_effectives_medias` (`id`, `eid`, `title`, `subtitle`, `url`, `type`, `active`, `sort`) VALUES
(5, 1, '[fr]Prix La Haye Jousselin 2011 - Rubi Ball[/fr]', '[fr][/fr]', 'wjIYj_54lHI', 'youtube', 1, 0),
(8, 3, '[fr]CONFORMATION KITKOU[/fr]', '[fr][/fr]', 'KITKOU_CONFORMATION.jpg', 'photo', 1, 2),
(9, 2, '[fr]BUCKS BOUM - Etalon Show du Centre-Est 2013[/fr]', '[fr][/fr]', 'yJT1-xjYrJU', 'youtube', 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `pr08T5y_sb_effectives_production`
--

CREATE TABLE `pr08T5y_sb_effectives_production` (
  `id` int(11) NOT NULL,
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
  `sort` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `pr08T5y_sb_effectives_production`
--

INSERT INTO `pr08T5y_sb_effectives_production` (`id`, `eid`, `name`, `sex`, `date`, `colour`, `group_bold`, `dam`, `sire_dam`, `performance`, `photo`, `video`, `pedigree`, `active`, `sort`) VALUES
(1, 1, 'SPRINTER SACR&Eacute;', 'H', '2006-04-23', '', 1, '', '', '[fr]&lt;strong&gt;Meilleur steeple-chaser d&amp;#39;Angleterre.&amp;nbsp;Laur&amp;eacute;at de 9 Gr.1 dont deux &amp;eacute;ditions du &amp;quot;Queen Mother Chapion Chase&amp;quot; &amp;agrave; Cheltenham.&lt;/strong&gt;&lt;br /&gt;\r\nArkle Chase &lt;strong&gt;Gr.1&lt;/strong&gt;, Maghull Novices Chase &lt;strong&gt;Gr.1&lt;/strong&gt;, Tingle Creek Chase &lt;strong&gt;Gr.1&lt;/strong&gt;, Clarence House Steeple-Chase &lt;strong&gt;Gr.1&lt;/strong&gt;, Queen Mother Chase &lt;strong&gt;Gr.1&lt;/strong&gt;, Melling Chase &lt;strong&gt;Gr.1&lt;/strong&gt;, Champion Chase &lt;strong&gt;Gr.1&lt;/strong&gt;[/fr]', 'sprinter-sacre.jpg', '', '', 1, 3),
(2, 1, 'RUBI BALL', 'H', '2005-01-01', 'Alezan', 1, '', '', '[fr]&lt;strong&gt;Gagnant de Gr1,&amp;nbsp;16 victoires et 18 places en 41 sorties.&amp;nbsp;&lt;/strong&gt;Prix La Haye Jousselin &lt;strong&gt;Gr.1&lt;/strong&gt; (2 fois),&amp;nbsp;Prix F. Dufaure &lt;strong&gt;Gr.1&lt;/strong&gt;,&amp;nbsp;Gd Prix de Pau &lt;strong&gt;Gr.3&lt;/strong&gt; (2 fois),&amp;nbsp;Prix Morgex &lt;strong&gt;Gr.3&lt;/strong&gt;[/fr]', 'rubi-ball_-747.jpg', '', '', 1, 2),
(4, 3, 'PREMIERE ANN&Eacute;E DE MONTE', '', '2016-01-01', '', 0, '', '', '[fr]- 48 Juments saillie...&lt;br /&gt;\r\n&lt;br /&gt;\r\n&lt;span class=&quot;marker&quot;&gt;A MODIFIER SI BESOIN ....&lt;/span&gt;[/fr]', '', '', '', 1, 0),
(5, 2, 'FAVORITO BUCK&rsquo;S', 'M', '2012-01-01', '', 1, '', '', '[fr]Prix Finot &lt;strong&gt;L&lt;/strong&gt;.[/fr]', '', '', '', 1, 0),
(6, 2, 'Nuits Premier Cru', 'M', '2012-01-01', '', 0, '', '', '[fr]Prix Vallon des Auffes &amp;agrave; Avignon[/fr]', '', '', '', 1, 0),
(7, 2, 'Par Amour', 'F', '2012-01-01', '', 0, '', '', '[fr]Prix des Ambulances du Port &amp;agrave; Niort&lt;br /&gt;\r\n&lt;br /&gt;\r\n&lt;span class=&quot;marker&quot;&gt;SUITE A COMPLETER&lt;/span&gt;[/fr]', '', '', '', 1, 0),
(8, 1, 'SAINT ARE', 'H', '2006-04-05', 'Bai', 1, '', '', '[fr]Sefton Novices&amp;rsquo; Hurdle &lt;b&gt;Gr.1&lt;/b&gt;, Hp Chase &lt;b&gt;L.&amp;nbsp;&lt;/b&gt;[/fr]', 'Saint-are.jpg', '', '', 1, 4),
(9, 1, 'ADRIANA DES MOTTES', 'F', '2010-03-25', 'Bai', 1, '', '', '[fr]Mares Novice Hurdle Championship Final &lt;b&gt;Gr.1 &lt;/b&gt;(2014[/fr]', 'adriannadesmottes_.jpg', '', '', 1, 5);

-- --------------------------------------------------------

--
-- Structure de la table `pr08T5y_sb_effectives_settings`
--

CREATE TABLE `pr08T5y_sb_effectives_settings` (
  `id` int(11) NOT NULL,
  `effectives_per_page` int(11) NOT NULL COMMENT 'Effectifs par page (catÃ©gorie)',
  `module_start` tinyint(4) NOT NULL COMMENT '0: liste des catÃ©gories, 1: catÃ©gorie spÃ©cifique',
  `breadcrumb` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `title_h1` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `title_h2` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `title_description` varchar(255) NOT NULL,
  `title_description_extend` text NOT NULL,
  `title_pedigree` varchar(255) NOT NULL,
  `title_production` varchar(255) NOT NULL,
  `title_medias` varchar(255) NOT NULL,
  `catid` int(11) NOT NULL COMMENT 'DÃ©marrage par cette catÃ©gorie',
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
  `effectives_help` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `pr08T5y_sb_effectives_settings`
--

INSERT INTO `pr08T5y_sb_effectives_settings` (`id`, `effectives_per_page`, `module_start`, `breadcrumb`, `title_h1`, `title_h2`, `title_description`, `title_description_extend`, `title_pedigree`, `title_production`, `title_medias`, `catid`, `chrono`, `status`, `photo`, `origine`, `pedigree`, `pedigree_extend`, `pedigree_desc`, `date`, `sire`, `dam`, `sire_dam`, `sex`, `winnings`, `size`, `projection`, `colour`, `breeder`, `owner`, `description`, `description_extend`, `subtitle1`, `subtitle2`, `production_sex`, `production_date`, `production_colour`, `production_dam`, `production_sire_dam`, `production_photo`, `production_video`, `production_pedigree`, `theme_view_cat`, `theme_view_list`, `theme_view_single`, `effectives_help`) VALUES
(1, 10, 0, 1, 1, 1, 'Description', 'R&eacute;servation de saillie', 'Pedigree', 'Production', 'Medias', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 'index', 'index', 'index', '');

-- --------------------------------------------------------

--
-- Structure de la table `pr08T5y_sb_flood`
--

CREATE TABLE `pr08T5y_sb_flood` (
  `ip` varchar(18) NOT NULL DEFAULT '',
  `time` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `pr08T5y_sb_graduates`
--

CREATE TABLE `pr08T5y_sb_graduates` (
  `id` int(11) NOT NULL,
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
  `sort` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `pr08T5y_sb_graduates`
--

INSERT INTO `pr08T5y_sb_graduates` (`id`, `catid`, `name`, `sire_dam_info`, `photo`, `perf_1`, `video_1`, `perf_2`, `video_2`, `perf_3`, `video_3`, `perf_4`, `video_4`, `perf_5`, `video_5`, `perf_6`, `video_6`, `perf_7`, `video_7`, `perf_8`, `video_8`, `perf_9`, `video_9`, `perf_10`, `video_10`, `breeder`, `owner`, `headpage`, `active`, `sort`) VALUES
(1, 1, 'Nom du cheval', 'p de m', 'le-haras-a-propos.jpg', 'P&eacute;rf 1', 'https://youtu.be/_JOup2ch9Vs', 'P&eacute;rf 2', 'https://youtu.be/_JOup2ch9Vs', 'P&eacute;rf 3', 'http://video3.mp4', 'P&eacute;rf 4', 'https://youtu.be/_JOup2ch9Vs', 'P&eacute;rf 5', 'https://youtu.be/_JOup2ch9Vs', 'P&eacute;rf 6', 'https://youtu.be/_JOup2ch9Vs_6', 'P&eacute;rf 7', 'https://youtu.be/_JOup2ch9Vs_7', 'P&eacute;rf 8', 'https://youtu.be/_JOup2ch9Vs_8', 'P&eacute;rf 9', 'https://youtu.be/_JOup2ch9Vs_9', 'P&eacute;rf 10', 'https://youtu.be/_JOup2ch9Vs_10', 'Entra&icirc;neur de bouzolles', 'Propri&eacute;taire de bouzolles', 0, 1, 2),
(2, 1, 'Nom du cheval 2', 'p&egrave;re et m&egrave;re', 'pouliniere.jpg', 'P&eacute;rf 1', 'https://youtu.be/_JOup2ch9Vs', 'perf 2 &eacute;&eacute;&eacute;', 'https://youtu.be/_JOup2ch9Vs', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'GestuÌˆt Wittekindshof', 'Gertrude d&eacute; pach&ocirc;s', 0, 1, 3),
(3, 1, 'Nom du cheval 3', 'p&egrave;re &amp; m&egrave;re &amp; p&egrave;re de m&egrave;re &eacute;&eacute;&eacute;', 'BUCKS-BOUM.jpg', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Henri Poulat', 'Gertrude d&eacute; pach&ocirc;s', 0, 1, 4),
(4, 1, 'Nom du cheval 4', 'p&egrave;re &amp; m&egrave;re &amp; p&egrave;re de m&egrave;re', 'Saint-are.jpg', 'P&eacute;rf 1', 'https://youtu.be/_JOup2ch9Vs', 'perf 22', 'http://video2.mp4', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Robert de la Palma', 'Gertrude d&eacute; pach&ocirc;s', 0, 1, 5),
(5, 1, 'Nom du cheval 5', 'p&egrave;re et m&egrave;re', 'BUCK-S-BOUM_CONFORMATION.jpg', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'eleveur &eacute;&eacute;&eacute;', 'Propri&eacute;taire de bouzolles', 0, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `pr08T5y_sb_graduates_category`
--

CREATE TABLE `pr08T5y_sb_graduates_category` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `subtitle` text NOT NULL,
  `tpl_list` text NOT NULL,
  `tpl_single` text NOT NULL,
  `module_show` varchar(50) NOT NULL COMMENT 'normal,masonry,...',
  `module_show_masonry` int(11) NOT NULL COMMENT 'columns width (pixels)',
  `photo` varchar(255) NOT NULL,
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `sort` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `pr08T5y_sb_graduates_category`
--

INSERT INTO `pr08T5y_sb_graduates_category` (`id`, `title`, `subtitle`, `tpl_list`, `tpl_single`, `module_show`, `module_show_masonry`, `photo`, `active`, `sort`) VALUES
(1, '[fr]cat 1 graduates[/fr]', '[fr]Sous titre cat 1 graduates[/fr]', '', '', 'normal', 250, 'icon-p-user.png', 1, 2),
(2, '[fr]cat 2 graduates[/fr]', '[fr]Sous titre cat 2 graduates[/fr]', '', '', 'masonry', 200, 'custom-wp.jpg', 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `pr08T5y_sb_graduates_settings`
--

CREATE TABLE `pr08T5y_sb_graduates_settings` (
  `id` int(11) NOT NULL,
  `graduates_per_page` int(11) NOT NULL COMMENT 'Graduates par page (catégorie)',
  `module_start` tinyint(4) NOT NULL COMMENT '0: liste des catégories, 1: catégorie spécifique',
  `breadcrumb` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `title_h1` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `title_h2` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `catid` int(11) NOT NULL COMMENT 'Démarrage par cette catégorie',
  `theme_view_cat` varchar(100) NOT NULL DEFAULT 'index' COMMENT 'Theme view du module LISTE DES CATEGORIES',
  `theme_view_list` varchar(100) NOT NULL DEFAULT 'index' COMMENT 'Theme view du module LISTE DES EFFECTIFS',
  `theme_view_single` varchar(100) NOT NULL DEFAULT 'index' COMMENT 'Theme view du module single GRADUATES',
  `graduates_help` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `pr08T5y_sb_graduates_settings`
--

INSERT INTO `pr08T5y_sb_graduates_settings` (`id`, `graduates_per_page`, `module_start`, `breadcrumb`, `title_h1`, `title_h2`, `catid`, `theme_view_cat`, `theme_view_list`, `theme_view_single`, `graduates_help`) VALUES
(1, 10, 0, 1, 1, 1, 1, 'index', 'index', 'index', '');

-- --------------------------------------------------------

--
-- Structure de la table `pr08T5y_sb_logaccess`
--

CREATE TABLE `pr08T5y_sb_logaccess` (
  `id` int(11) NOT NULL,
  `logaccess_type` varchar(10) NOT NULL,
  `logaccess_date` int(10) NOT NULL,
  `logaccess_user` varchar(20) NOT NULL,
  `logaccess_event` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8 COMMENT='Table Activity log';

--
-- Contenu de la table `pr08T5y_sb_logaccess`
--

INSERT INTO `pr08T5y_sb_logaccess` (`id`, `logaccess_type`, `logaccess_date`, `logaccess_user`, `logaccess_event`) VALUES
(1, 'login', 1475582446, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(2, 'login', 1475593326, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(3, 'login', 1475650054, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(4, 'error', 1475754870, 'admin', 'Identification non autoris&eacute;e depuis [::1] - propablement un intru'),
(5, 'login', 1475754875, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(6, 'login', 1476087604, 'jerome', 'Utilisateur [jerome] identifi&eacute; depuis [::1]'),
(7, 'login', 1476358969, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(8, 'login', 1476359321, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(9, 'login', 1476434902, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(10, 'login', 1477051265, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(11, 'login', 1477298783, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(12, 'login', 1478268507, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(13, 'login', 1478766884, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(14, 'error', 1479221418, 'admin', 'Identification non autoris&eacute;e depuis [::1] - propablement un intru'),
(15, 'login', 1479221423, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(16, 'error', 1479887979, 'admin', 'Identification non autoris&eacute;e depuis [::1] - propablement un intru'),
(17, 'login', 1479887985, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(18, 'error', 1479891008, 'admin', 'Identification non autoris&eacute;e depuis [::1] - propablement un intru'),
(19, 'login', 1479891013, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(20, 'login', 1479905863, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(21, 'login', 1479999893, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(22, 'login', 1480067552, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(23, 'login', 1480067662, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(24, 'login', 1480067956, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(25, 'login', 1480068008, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(26, 'login', 1480068079, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(27, 'login', 1480078615, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(28, 'login', 1480081006, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(29, 'login', 1480081137, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(30, 'login', 1480081372, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(31, 'login', 1480081404, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(32, 'login', 1480081438, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(33, 'login', 1480081465, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(34, 'login', 1480081469, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(35, 'login', 1480081472, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(36, 'login', 1480081488, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(37, 'login', 1480081581, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(38, 'login', 1480084162, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(39, 'login', 1480322689, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(40, 'login', 1480340631, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(41, 'login', 1480406607, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(42, 'login', 1480424615, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(43, 'login', 1480516851, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(44, 'error', 1480579243, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1] avec une erreur captcha'),
(45, 'login', 1480579266, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(46, 'login', 1480579284, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(47, 'login', 1480599102, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(48, 'login', 1480665883, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(49, 'error', 1480926553, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1] avec une erreur captcha'),
(50, 'login', 1480926573, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(51, 'login', 1480928562, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(52, 'login', 1480943277, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(53, 'login', 1481029154, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(54, 'login', 1481097350, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(55, 'login', 1481106051, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(56, 'login', 1481121515, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(57, 'login', 1481183786, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(58, 'login', 1481201850, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(59, 'login', 1481270255, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(60, 'login', 1481288005, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(61, 'error', 1481529788, 'admin', 'Identification non autoris&eacute;e depuis [::1] - propablement un intru'),
(62, 'login', 1481529793, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(63, 'login', 1481534000, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [192.168.1.31]'),
(64, 'login', 1481534181, 'jerome', 'Utilisateur [jerome] identifi&eacute; depuis [192.168.1.31]'),
(65, 'login', 1481548408, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(66, 'login', 1481701846, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(67, 'login', 1481900336, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(68, 'login', 1482134755, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(69, 'error', 1482160850, 'admin', 'Identification non autoris&eacute;e depuis [::1] - propablement un intru'),
(70, 'login', 1482160854, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]'),
(71, 'login', 1482241255, 'patrice', 'Utilisateur [patrice] identifi&eacute; depuis [::1]');

-- --------------------------------------------------------

--
-- Structure de la table `pr08T5y_sb_menu`
--

CREATE TABLE `pr08T5y_sb_menu` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `tag` varchar(50) NOT NULL COMMENT 'Smarty variable',
  `pages` varchar(255) NOT NULL COMMENT 'Pages IDs (separate by | )',
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `pr08T5y_sb_menu`
--

INSERT INTO `pr08T5y_sb_menu` (`id`, `name`, `tag`, `pages`, `active`) VALUES
(1, 'Main menu', 'main_menu', '6|5|4|3|2|1', 1);

-- --------------------------------------------------------

--
-- Structure de la table `pr08T5y_sb_news`
--

CREATE TABLE `pr08T5y_sb_news` (
  `id` int(11) NOT NULL,
  `catid` varchar(50) NOT NULL COMMENT 'Categories',
  `title` text NOT NULL,
  `subtitle` text NOT NULL,
  `desc_short` text NOT NULL,
  `desc_full` text NOT NULL,
  `image` varchar(200) NOT NULL,
  `date` varchar(10) NOT NULL,
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active'
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `pr08T5y_sb_news`
--

INSERT INTO `pr08T5y_sb_news` (`id`, `catid`, `title`, `subtitle`, `desc_short`, `desc_full`, `image`, `date`, `active`) VALUES
(6, '1', '[fr]Arriv&eacute;e de  NETWORK au Haras d&#039;Enki[/fr]', '[fr][/fr]', '[fr]&lt;span class=&quot;marker&quot;&gt;TEXTE &amp;amp; PHOTO A VERIFIER / MODIFIER&lt;/span&gt;\r\n&lt;br /&gt;Le Haras d&amp;rsquo;Enki et ses propri&amp;eacute;taires remercient tous les tr&amp;eacute;s nombreux sympathisants de &lt;strong&gt;NETWORK&lt;/strong&gt; pour les messages amicaux qu&amp;rsquo;ils ont re&amp;ccedil;us apr&amp;egrave;s l&amp;rsquo;achat du cheval. &amp;quot; &lt;strong&gt;NETWORK&lt;/strong&gt; fera la monte &amp;agrave; 10.000 &amp;euro;. Mais les personnes ayant acquis par ailleurs une part de son voisin de boxe &lt;strong&gt;KITKOU&lt;/strong&gt; pourront avoir la saillie &amp;agrave; 7.000 &amp;euro;, tandis que ceux prenant une saillie dudit &lt;strong&gt;KITKOU&lt;/strong&gt; b&amp;eacute;n&amp;eacute;ficieront du tarif de 8.000 &amp;euro;[/fr]', '[fr]&lt;span class=&quot;marker&quot;&gt;TEXTE &amp;amp; PHOTO A VERIFIER / MODIFIER&lt;/span&gt;\r\n&lt;br /&gt;&lt;p&gt;Network &amp;eacute;tait logiquement l&amp;#39;objet de tous les int&amp;eacute;r&amp;ecirc;ts lorsqu&amp;#39;il est entr&amp;eacute; en sc&amp;egrave;ne dans le ring de vente d&amp;#39;Arqana le 8 d&amp;eacute;cembre 2015. Il symbolisait le terme d&amp;eacute;finitif de l&amp;#39;histoire des Haras Nationaux avec les &amp;eacute;talons, une histoire qui, gr&amp;acirc;ce &amp;agrave; cette excellente inspiration de &lt;em&gt;Fran&amp;ccedil;ois Gorioux&lt;/em&gt; et &lt;em&gt;Michel de Gigou &lt;/em&gt;il y a pr&amp;egrave;s de 15 ans, se termine en fanfare.&lt;/p&gt;\r\n\r\n&lt;p&gt;Acquis 290.000 &amp;euro; par &lt;em&gt;David Powell&lt;/em&gt; pour le compte de la famille Papot, il a &amp;eacute;t&amp;eacute; confi&amp;eacute; &amp;agrave; Christelle et Est&amp;egrave;ve Rouchvarger, les animateurs du Haras d&amp;#39;Enki dans l&amp;#39;Allier. Pour palier &amp;agrave; ses divers probl&amp;egrave;mes rencontr&amp;eacute;s jusqu&amp;#39;alors, et surtout de libido, il change radicalement de r&amp;eacute;gime &amp;agrave; l&amp;#39;&amp;acirc;ge de 18 ans pour ce qui est sa 4&amp;egrave;me vie.&lt;br /&gt;\r\n&lt;br /&gt;\r\nPour faire court, le destin de NETWORK a commenc&amp;eacute; en Allemagne. Fils de &lt;strong&gt;Monsun&lt;/strong&gt;, alors en d&amp;eacute;but de carri&amp;egrave;re d&amp;#39;&amp;eacute;talon avant de se r&amp;eacute;v&amp;eacute;ler un chef de race, avec une m&amp;egrave;re par le m&amp;eacute;morable fran&amp;ccedil;ais &lt;strong&gt;Reliance&lt;/strong&gt; (la fameuse g&amp;eacute;n&amp;eacute;ration 1962 avec &lt;strong&gt;Sea Bird&lt;/strong&gt; et &lt;strong&gt;Carvin&lt;/strong&gt;), Network figure parmi les meilleurs 3 ans de sa g&amp;eacute;n&amp;eacute;ration. Il remporte ainsi l&amp;#39;Oppenheim Union Rennen, un Gr.2 pr&amp;eacute;paratoire au Derby sur les 2200 m de Cologne. sous la f&amp;eacute;rule d&amp;#39;&lt;em&gt;Andreas Schutz&lt;/em&gt;. Un an plus tard, fin 2001, deux &amp;eacute;missaires fran&amp;ccedil;ais pr&amp;eacute;-cit&amp;eacute;s, l&amp;#39;ach&amp;egrave;tent pour le compte des Haras Nationaux, dont il n&amp;#39;est pas encore question de la fin.&lt;/p&gt;\r\n\r\n&lt;p&gt;La 2&amp;egrave;me vie de Network commence alors en tant qu&amp;#39;&amp;eacute;talon HN. Il d&amp;eacute;bute &amp;agrave; la station de Cercy dans la Ni&amp;egrave;vre, puis voyage au Lion d&amp;#39;Angers, &amp;agrave; Sartilly dans la Manche, &amp;agrave; Tr&amp;eacute;ban dans l&amp;#39;Allier. Il est d&amp;eacute;j&amp;agrave; r&amp;eacute;put&amp;eacute; d&amp;eacute;licat &amp;agrave; saillir, mais d&amp;egrave;s sa 1&amp;egrave;re production, son talent de reproducteur s&amp;#39;affirme des 2 c&amp;ocirc;t&amp;eacute;s de la Manche.&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;Il y a 3 ans, alors que le plan de fermeture des HN est act&amp;eacute;, Network s&amp;#39;installe au Haras de Cercy pour sa 3&amp;egrave;me vie, d&amp;eacute;finitive... en attendant la suite ! Mais alors que Network atteint le sommet de sa popularit&amp;eacute; gr&amp;acirc;ce &amp;agrave; tous ses champions dont Sprinter Sacr&amp;eacute;, alors que les Anglais et Irlandais deviennent de plus en plus voraces de sa production, ses soucis pour saillir s&amp;#39;amplifient jusqu&amp;#39;&amp;agrave; devenir un r&amp;eacute;el probl&amp;egrave;me de fonctionnement pour l&amp;#39;organisation du haras.&lt;/p&gt;[/fr]', 'le-haras-a-propos.jpg', '2015-12-18', 1),
(7, '1', '[fr]Propre fr&egrave;re de KOTKIKOVA, KITKOU s&#039;installe &eacute;talon au Haras d&#039;Enki &agrave; 3 ans en 2016[/fr]', '[fr][/fr]', '[fr]&lt;span class=&quot;marker&quot;&gt;TEXTE &amp;amp; PHOTO A VERIFIER / MODIFIER&lt;/span&gt;\r\n&lt;br /&gt;Propre fr&amp;egrave;re de la championne Kotkikova, Kitkou sera le 1er fils de Martaline &amp;agrave; s&amp;#39;installer &amp;eacute;talon lorsqu&amp;#39;il saillira ses premi&amp;egrave;res juments d&amp;egrave;s l&amp;#39;&amp;acirc;ge de 3 ans au printemps 2016. In&amp;eacute;dit, il s&amp;#39;installe au Haras d&amp;#39;Enki, chez Christelle et Est&amp;egrave;ve Rouchvarger, aux c&amp;ocirc;t&amp;eacute;s de Network, Buck&amp;#39;s Boum et Spider Flight dans l&amp;#39;Allier.[/fr]', '[fr]&lt;span class=&quot;marker&quot;&gt;TEXTE &amp;amp; PHOTO A VERIFIER / MODIFIER&lt;/span&gt;\r\n&lt;br /&gt;Propre fr&amp;egrave;re de la championne Kotkikova, Kitkou sera le 1er fils de Martaline &amp;agrave; s&amp;#39;installer &amp;eacute;talon lorsqu&amp;#39;il saillira ses premi&amp;egrave;res juments d&amp;egrave;s l&amp;#39;&amp;acirc;ge de 3 ans au printemps 2016. In&amp;eacute;dit, il s&amp;#39;installe au Haras d&amp;#39;Enki, chez Christelle et Est&amp;egrave;ve Rouchvarger, aux c&amp;ocirc;t&amp;eacute;s de Network, Buck&amp;#39;s Boum et Spider Flight dans l&amp;#39;Allier.&lt;br /&gt;\r\n&lt;br /&gt;\r\nIllustr&amp;eacute; derni&amp;egrave;rement avec Kotkikova, une ph&amp;eacute;nom&amp;egrave;ne de Jean-Paul Gallorini qui a &amp;eacute;t&amp;eacute; la meilleure 3 ans puis 4 ans de sa g&amp;eacute;n&amp;eacute;ration sur le steeple, laur&amp;eacute;ate de 4 groupes dont le Prix Ferdinand Dufaure de toute une classe ainsi que de 2 Listed, la souche des &amp;quot;K&amp;quot; d&amp;eacute;velopp&amp;eacute;e par le d&amp;eacute;funt Comte de Montesson au Haras des Coudraies dans l&amp;#39;Orne s&amp;#39;impose comme l&amp;#39;une des meilleures sinon la plus prolixe des familles d&amp;#39;obstacle des temps modernes.&lt;br /&gt;\r\n&lt;br /&gt;\r\nReservoir de cracks, r&amp;eacute;v&amp;eacute;l&amp;eacute;e avec les 2 fr&amp;egrave;res Katko et Kotkijet, cette souche a donn&amp;eacute; une nombre incalculable de grands vainqueurs &amp;agrave; Auteuil. Les 2 vainqueurs pr&amp;eacute;cit&amp;eacute; de Grand Steeple-Chase de Paris &amp;eacute;taient respectivement par Carmarthen et Cadoudal. Pierre de Montesson s&amp;#39;est toujours appuy&amp;eacute; sur des &amp;eacute;talons d&amp;#39;obstacle les plus confirm&amp;eacute;s et c&amp;#39;est ainsi qu&amp;#39;il a logiquement utilis&amp;eacute; les services de Martaline avec Kotkita, elle-m&amp;ecirc;me laur&amp;eacute;ate de Gr.1 dans le Prix Cambac&amp;eacute;r&amp;egrave;s, &amp;agrave; 2 reprises en 2010 puis 2012. Le foal n&amp;eacute; en 2013, nomm&amp;eacute; Kitkou, a &amp;eacute;t&amp;eacute; acquis par la Famille Papot et conserv&amp;eacute; pr&amp;eacute;cieusement entier dans la perspective d&amp;#39;en faire un &amp;eacute;talon, car il associait ce p&amp;eacute;digr&amp;eacute;e qu&amp;#39;on ne pr&amp;eacute;sente plus avec le mod&amp;egrave;le.[/fr]', 'kitkou.jpg', '2015-12-20', 1),
(8, '1', '[fr]Spider Flight au Haras d&#039;Enki pour une saison en 2016[/fr]', '[fr][/fr]', '[fr]Issu du croisement entre deux champions Montjeu &amp;ndash; Karly Flight, Spider Flight, jeune &amp;eacute;talon dont les premiers produits sont yearlings, sera disponible au Haras d&amp;rsquo;Enki en 2016. Il y a &amp;eacute;t&amp;eacute; plac&amp;eacute; par son &amp;eacute;leveur et propri&amp;eacute;taire Patrick Boiteau pour une saison apr&amp;egrave;s des d&amp;eacute;buts d&amp;rsquo;&amp;eacute;talon au Haras de la Courlais.[/fr]', '[fr]Entrain&amp;eacute; par Andr&amp;eacute; Fabre en d&amp;eacute;but de carri&amp;egrave;re puis par Alex Pantall et enfin par Nobert Leenders, Spider Flight avait donn&amp;eacute; des espoirs d&amp;egrave;s l&amp;rsquo;&amp;acirc;ge de 2 ans en d&amp;eacute;butant 4&amp;egrave;me dans le Prix de Belleville. 3&amp;egrave;me de &amp;quot;B&amp;quot; &amp;agrave; 3 ans dans le Prix Nasrullah derri&amp;egrave;re C&amp;eacute;l&amp;eacute;brissime et Chinchon, le poulain a ensuite remport&amp;eacute; 7 de ses combats. Sa m&amp;egrave;re n&amp;rsquo;est autre que la championne Karly Flight, auteur du doubl&amp;eacute; Prix Ferdinand Dufaure (Gr.1) et Prix Renaud du Vivier (Gr.1) &amp;agrave; 4 ans et encore laur&amp;eacute;ate des Prix Leon Rambaud (Gr.2) et La Barka (Gr.2) &amp;agrave; 5 ans avant d&amp;rsquo;&amp;ecirc;tre rentr&amp;eacute;e poulini&amp;egrave;re par son &amp;eacute;leveur Patrick Boiteau. Spider Flight est son premier produit, suivi entre autres de Chutney Flight (Dylan Thomas), laur&amp;eacute;at en plat de la Listed Prix Madame Jean Couturi&amp;eacute; et en obstacle du Prix de Royan &amp;agrave; Auteuil. Elle aussi a &amp;eacute;t&amp;eacute; conserv&amp;eacute;e poulini&amp;egrave;re. Elle &amp;eacute;tait suit&amp;eacute;e en 2015 d&amp;rsquo;un m&amp;acirc;le de Fuiss&amp;eacute;.&lt;br /&gt;\r\n&lt;br /&gt;\r\nLes premiers produits de Spider Flight sont aujourd&amp;rsquo;hui yearlings. Sa deuxi&amp;egrave;me g&amp;eacute;n&amp;eacute;ration, qui est n&amp;eacute;e cette ann&amp;eacute;e, avait &amp;eacute;t&amp;eacute; &amp;agrave; l&amp;rsquo;honneur lors du Show AQPS de l&amp;rsquo;Ouest au Lion d&amp;rsquo;Angers en septembre dernier. En effet, sa fille par la jument Talitha de Clerval fut sacr&amp;eacute;e meilleure jeune foal femelle et son fils par Vakina avait &amp;eacute;t&amp;eacute; couronn&amp;eacute; dans la cat&amp;eacute;gorie des jeunes foals m&amp;acirc;les. Le d&amp;eacute;placer dans le centre de la France est une option strat&amp;eacute;gique comme nous le confiait Patrick Boiteau. Il pourra y saillir de nouvelles poulini&amp;egrave;res et donc de nouvelles souches. L&amp;rsquo;&amp;eacute;leveur des &amp;quot;Light&amp;quot;&amp;nbsp;continuera &amp;agrave; utiliser son &amp;eacute;talon.[/fr]', 'spider-flight-news.jpg', '2015-11-12', 0),
(9, '1', '[fr]Indian Daffodil, &eacute;talon au Haras d&#039;Enki en 2014[/fr]', '[fr][/fr]', '[fr]N&amp;eacute; en 2005, &amp;eacute;lev&amp;eacute; au Haras de Meautry et gagnant de Gr. 3, Indian Daffodil a &amp;eacute;t&amp;eacute; transf&amp;eacute;r&amp;eacute; de Normandie vers l&amp;#39;Auvergne.[/fr]', '[fr]&lt;p&gt;Indian Daffodil, fils d&amp;#39;Hernando et de Danseuse Indienne, une fille de Danehill, &amp;eacute;tait un 2 ans invaincu chez Jean-Claude Rouget.&amp;nbsp;Gagnant du Prix de Crevecoeur en d&amp;eacute;butant devant Hello Morning et High Rock en battant &amp;eacute;galement Falco (non plac&amp;eacute;), Indian Daffodil avait par ailleurs remport&amp;eacute; le Prix des Feuillants, une course B disput&amp;eacute;e &amp;agrave; Longchamp en fin de saison, qui a entre autre &amp;eacute;t&amp;eacute; remport&amp;eacute; par Pour Moi, le gagnant du Derby d&amp;#39;Epsom en 2011.&lt;/p&gt;\r\n\r\n&lt;p&gt;Deux fois 2e de Listed pour sa rentr&amp;eacute;e &amp;agrave; 3 ans, Indian Daffodil avait pris le premier accessit dans le Prix de Suresnes derri&amp;egrave;re Vision d&amp;#39;Etat. Epargn&amp;eacute; de Prix du Jockey-Club, le poulain a alors profit&amp;eacute; d&amp;#39;une opposition appauvrie dans le Prix Daphnis, un Gr. 3 qu&amp;#39;Indian Daffodil s&amp;#39;est octroy&amp;eacute;. 3e en fin de saison du Prix Andr&amp;eacute; Baboin, Grand-Prix des Provinces (Gr. 3) derri&amp;egrave;re Chopastair et Anabaa&amp;#39;s Creation, Indian Daffodil a su profiter de deux nouvelles saisons de courses, gagnant &amp;agrave; 4 et 5 ans.&lt;br /&gt;\r\n&lt;br /&gt;\r\nDemi-fr&amp;egrave;re de Devious Indian (Prix Quincey Gr. 3) et d&amp;#39;Etendard Indien (Prix Lut&amp;egrave;ce Gr. 3 derri&amp;egrave;re Reefscape), Indian Daffodil, jonquille indienne en anglais, est entr&amp;eacute; au haras en 2011. Seuls deux fils d&amp;#39;Hernando font la monte en France : Alianthus au Haras de Victot et Indian Daffodil lui-m&amp;ecirc;me issu de la famille de Poliglote, d&amp;#39;Indian Danehill&amp;nbsp;et de plus de trente chevaux de caract&amp;egrave;re gras sous la premi&amp;egrave;re, deuxi&amp;egrave;me et troisi&amp;egrave;me m&amp;egrave;re ! Indian Daffodil fera la monte au Haras d&amp;#39;Enki aux c&amp;ocirc;t&amp;eacute;s d&amp;#39;Agent Secret pour 1.500&amp;euro; HT la saillie en 2014.&lt;/p&gt;[/fr]', 'indian-daffodil-news.jpg', '2014-02-07', 0),
(10, '1', '[fr]Anzillero et Caballo Raptor au Haras d&#039;Enki dans l&#039;Allier[/fr]', '[fr][/fr]', '[fr]Apr&amp;egrave;s avoir pass&amp;eacute; deux riches saisons avec son &amp;eacute;talon d&amp;eacute;butant Buck&amp;#39;s Boum, Esteve Rouchvarger compl&amp;egrave;te sa cour avec 2 autres &amp;eacute;talons, &amp;eacute;galement au profil obstacle : Caballo Raptor et Anzillero, qui arrivera lui &amp;agrave; la moiti&amp;eacute; de la saison.[/fr]', '[fr]Install&amp;eacute; pr&amp;egrave;s de Vichy en 2011, Esteve Rouchvarger a d&amp;eacute;marr&amp;eacute; avec un cheval dont le profil a tout de suite suscit&amp;eacute; l&amp;#39;int&amp;eacute;r&amp;ecirc;t. Propre fr&amp;egrave;re du crack Big Buck&amp;#39;s, tr&amp;egrave;s sign&amp;eacute; de son p&amp;egrave;re Cadoudal et lui-m&amp;ecirc;me excellent en course puisqu&amp;#39;il a gagn&amp;eacute; &amp;agrave; Auteuil avant de conclure 2e de Gr.1 dans le Prix Cambac&amp;eacute;r&amp;egrave;s, Buck&amp;#39;s Boum a logiquement motiv&amp;eacute; les &amp;eacute;leveurs de l&amp;#39;Allier, dont beaucoup font de l&amp;#39;obstacle. Il a sailli 66 juments et 2011 et 81 en 2012.&lt;br /&gt;\r\n&lt;br /&gt;\r\nPour sa 3e saison, alors qu&amp;#39;il a acquis de nouvelles installations l&amp;#39;an dernier, Est&amp;egrave;ve Rouchvarger aura une cour de 3 &amp;eacute;talons. Ainsi, en provenance du Haras de Tr&amp;eacute;ban, provient Caballo Raptor. Cet &amp;eacute;talon gagnant de Gr.1 &amp;agrave; Auteuil dans le Prix Renaud du Vivier produit bien malgr&amp;eacute; le tr&amp;egrave;s peu de juments saillies en d&amp;eacute;but de carri&amp;egrave;re o&amp;ugrave; il &amp;eacute;tait stationn&amp;eacute; dans l&amp;#39;ouest mais au tarif prohibitif de 3500 &amp;Acirc;Â€. Ainsi, bien que n&amp;#39;ayant qu&amp;#39;une dizaine de produits d&amp;eacute;clar&amp;eacute;s depuis sa 1e g&amp;eacute;n&amp;eacute;ration en 2006, il a d&amp;eacute;j&amp;agrave; donn&amp;eacute; 9 vainqueurs en France dont Coquard (6 victoires) et Badoudal, titulaire de 7 victoires et 2e de Gr.1 dans le Prix Ferdinand Dufaure &amp;agrave; Auteuil. D&amp;eacute;sormais, il fait la monte &amp;agrave; 1250 &amp;Acirc;Â€ et a attir&amp;eacute; 49 juments en 2011 et 34 en 2012.&lt;br /&gt;\r\n&lt;br /&gt;\r\nPar ailleurs, Anzillero arrivera en provenance de Lorraine seulement le 15 avril. En effet, le cousin de Galileo qui fait la monte &amp;agrave; l&amp;#39;Elevage d&amp;#39;Airy &amp;agrave; Verdun couvrira les juments de Claude-Yves Pelsy au d&amp;eacute;but du printemps puis &amp;quot;descendra&amp;quot; dans l&amp;#39;Allier. &amp;quot; On fait la double saison mais en restant en France ! &amp;quot; plaisante Est&amp;egrave;ve Rouchvarger. Claude-Yves Pelsy est content de la production de son cheval et souhaite continuer &amp;agrave; l&amp;#39;utiliser. Mais il peut arriver en avril chez nous car la client&amp;egrave;le d&amp;#39;obstacle ne fait pas saillir n&amp;eacute;cessairement en tout d&amp;eacute;but de saison. Ses juments qui ne seraient pas encore pleines suivront le cheval dans l&amp;#39;Allier. Et si des juments de l&amp;#39;Allier doivent &amp;ecirc;tre saillies avant avril, elles pourront aller en Lorraine. Son tarif 2013 n&amp;#39;est pas d&amp;eacute;finitif. S&amp;#39;il r&amp;eacute;ussit aussi bien que l&amp;#39;hiver dernier &amp;agrave; Pau, il pourrait augmenter un peu.&amp;quot;&lt;br /&gt;\r\n&lt;br /&gt;\r\nToisant 1,70 m, gagnant de Gr.1, Anzillero fait la monte &amp;agrave; 1500 &amp;Acirc;Â€. C&amp;#39;est le p&amp;egrave;re de Ulcar d&amp;#39;Airy, 3e du Prix James Hennessy, mais aussi de Tiepolero, vendu en Irlande &amp;agrave; Willie Mullins apr&amp;egrave;s un succ&amp;egrave;s &amp;agrave; Strasbourg.[/fr]', 'anzillero-caballo-raptor-news.jpg', '2012-11-22', 0),
(14, '1', '[fr]Lady Needles 2002[/fr]', '[fr]Travail d&#039;Orf&egrave;vre (16) &amp; Lady Needles (02)[/fr]', '[fr]&lt;em&gt;Par SRI PEKAN (US) &amp;amp; CREME VELOUTEE (IE) par LAW SOCIETY (US)&lt;/em&gt;&lt;br /&gt;\r\nCette plac&amp;eacute;e de &lt;strong&gt;Listed&lt;/strong&gt; en plat compte 5 Victoires et 16 Places.&lt;br /&gt;\r\n175 743 &amp;euro; de gains et nous &amp;agrave; d&amp;eacute;j&amp;agrave; offert avec ses deux premiers poulains 2 gagnants :\r\n&lt;ul&gt;\r\n	&lt;li&gt;2011 -&amp;nbsp;&lt;strong&gt;C&amp;oelig;ur Pirate Enki&lt;/strong&gt; (h. bai par NICKNAME), 1 Victoire&amp;nbsp;et 4 Places,&amp;nbsp;17355&amp;euro; de gains.&lt;/li&gt;\r\n	&lt;li&gt;2012 -&amp;nbsp;&lt;strong&gt;Par Amour&lt;/strong&gt; (f. baie par BUCK&amp;#39;S BOUM), 1 Victoire&amp;nbsp;et 7 Places,&amp;nbsp;29145&amp;euro; de gains.&lt;/li&gt;\r\n	&lt;li&gt;2013 -&amp;nbsp;Lady Enki (f. baie par KAPGARDE).&lt;/li&gt;\r\n	&lt;li&gt;2014 - Pao Enki (m. bai par&amp;nbsp;MUHTATHIR).&lt;/li&gt;\r\n	&lt;li&gt;2015 - Fuidy Enki (m. bai &amp;nbsp;par FUISS&amp;Eacute;).&lt;/li&gt;\r\n	&lt;li&gt;2016 - Travail d&amp;#39;Orf&amp;egrave;vre (m. Gris par MARTALINE)&lt;/li&gt;\r\n	&lt;li&gt;2017 - Saillie en 2016 par&amp;nbsp;BUCK&amp;#39;S BOUM -&amp;nbsp;A terme le xx/xx/xxxx&lt;/li&gt;\r\n&lt;/ul&gt;[/fr]', '[fr]&lt;em&gt;Par SRI PEKAN (US) &amp;amp; CREME VELOUTEE (IE) par LAW SOCIETY (US)&lt;/em&gt;&lt;br /&gt;\r\nCette plac&amp;eacute;e de &lt;strong&gt;Listed&lt;/strong&gt; en plat compte 5 Victoires et 16 Places.&lt;br /&gt;\r\n175 743 &amp;euro; de gains et nous &amp;agrave; d&amp;eacute;j&amp;agrave; offert avec ses deux premiers poulains 2 gagnants :\r\n&lt;ul&gt;\r\n	&lt;li&gt;2011 -&amp;nbsp;&lt;strong&gt;C&amp;oelig;ur Pirate Enki&lt;/strong&gt; (h. bai par NICKNAME), 1 Victoire&amp;nbsp;et 4 Places,&amp;nbsp;17355&amp;euro; de gains.&lt;/li&gt;\r\n	&lt;li&gt;2012 -&amp;nbsp;&lt;strong&gt;Par Amour&lt;/strong&gt; (f. baie par BUCK&amp;#39;S BOUM), 1 Victoire&amp;nbsp;et 7 Places,&amp;nbsp;29145&amp;euro; de gains.&lt;/li&gt;\r\n	&lt;li&gt;2013 -&amp;nbsp;Lady Enki (f. baie par KAPGARDE).&lt;/li&gt;\r\n	&lt;li&gt;2014 - Pao Enki (m. bai par&amp;nbsp;MUHTATHIR).&lt;/li&gt;\r\n	&lt;li&gt;2015 - Fuidy Enki (m. bai &amp;nbsp;par FUISS&amp;Eacute;).&lt;/li&gt;\r\n	&lt;li&gt;2016 - Travail d&amp;#39;Orf&amp;egrave;vre (m. Gris par MARTALINE)&lt;/li&gt;\r\n	&lt;li&gt;2017 - Saillie en 2016 par&amp;nbsp;BUCK&amp;#39;S BOUM -&amp;nbsp;A terme le xx/xx/xxxx&lt;/li&gt;\r\n&lt;/ul&gt;[/fr]', 'LadyNeedles-127.jpg', '2016-09-19', 1),
(15, '1', '[fr]Cadiane 2003[/fr]', '[fr]Cadiane (03)[/fr]', '[fr]&lt;em&gt;Par&amp;nbsp;CADOUDAL &amp;amp; SAINTE ALBANE par SAINT ANDREWS&lt;/em&gt;&lt;br /&gt;\r\nCette fille du crack &amp;eacute;talon Cadoudal est notre premi&amp;egrave;re jument et nous a donn&amp;eacute; de tr&amp;egrave;s grande joie avec :\r\n&lt;ul&gt;\r\n	&lt;li&gt;2009 -&amp;nbsp;&lt;strong&gt;Grace &amp;agrave; Toi Enki&lt;/strong&gt; (h. alezan par ARGENT BLEU), 5 Victoires et 8 Places 79420&amp;euro; de gains&lt;/li&gt;\r\n	&lt;li&gt;2010 -&amp;nbsp;&lt;strong&gt;Yala Enki&lt;/strong&gt; (h. bai fonc&amp;eacute; par NICKNAME) gagnant de &lt;strong&gt;Listed&lt;/strong&gt; et plac&amp;eacute; de &lt;strong&gt;Gr.I&lt;/strong&gt; et de &lt;strong&gt;Gr.II&lt;/strong&gt; en France et en Angleterre, 81225&amp;euro; de gains.&lt;/li&gt;\r\n	&lt;li&gt;2012 - Grace &amp;agrave; Vous Enki (m. bai par DREAM WELL)&lt;/li&gt;\r\n	&lt;li&gt;2017 - Saillie en 2016 par NETWORK (DE) - A terme le xx/xx/xxxx&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;br /&gt;\r\n&lt;br /&gt;\r\n&amp;nbsp;[/fr]', '[fr]&lt;em&gt;Par&amp;nbsp;CADOUDAL &amp;amp; SAINTE ALBANE par SAINT ANDREWS&lt;/em&gt;&lt;br /&gt;\r\nCette fille du crack &amp;eacute;talon Cadoudal est notre premi&amp;egrave;re jument et nous a donn&amp;eacute; de tr&amp;egrave;s grande joie avec :\r\n&lt;ul&gt;\r\n	&lt;li&gt;2009 -&amp;nbsp;&lt;strong&gt;Grace &amp;agrave; Toi Enki&lt;/strong&gt; (h. alezan par ARGENT BLEU), 5 Victoires et 8 Places 79420&amp;euro; de gains&lt;/li&gt;\r\n	&lt;li&gt;2010 -&amp;nbsp;&lt;strong&gt;Yala Enki&lt;/strong&gt; (h. bai fonc&amp;eacute; par NICKNAME) gagnant de &lt;strong&gt;Listed&lt;/strong&gt; et plac&amp;eacute; de &lt;strong&gt;Gr.I&lt;/strong&gt; et de &lt;strong&gt;Gr.II&lt;/strong&gt; en France et en Angleterre, 81225&amp;euro; de gains.&lt;/li&gt;\r\n	&lt;li&gt;2012 - Grace &amp;agrave; Vous Enki (m. bai&amp;nbsp;par DREAM WELL)&lt;/li&gt;\r\n	&lt;li&gt;2017 - Saillie en 2016 par NETWORK (DE) - A terme le xx/xx/xxxx&lt;/li&gt;\r\n&lt;/ul&gt;[/fr]', 'Cadiane-014.jpg', '2016-09-19', 1),
(16, '1', '[fr]Holly Girl 2004[/fr]', '[fr][/fr]', '[fr]&lt;em&gt;Par TESTA ROSSA (Au) &amp;amp; MISS HERNANDO (GB) par HERNANDO&lt;/em&gt;&lt;br /&gt;\r\nCette gagnante de quint&amp;eacute; en plat 5 Victoires et 13 Places 108 798 &amp;euro; de gains est issue de la souche de Mr Soula. &amp;nbsp;&lt;br /&gt;\r\nS&amp;oelig;ur de Darwind 196&amp;nbsp;346 &amp;euro; de gains 7 Victoires et 18 Places,&amp;nbsp;Miss Talma 92&amp;nbsp;158 &amp;euro; de gains 3 Victoires et 21 Places, et ni&amp;egrave;ce de&amp;nbsp; Worms 14 Victoires et 44 Places 389&amp;nbsp;072 &amp;euro; de gains,Kiny Country 11 Victoires et 36 Places 397&amp;nbsp;755 &amp;euro; de gains,&amp;nbsp;Leeds 4 Victoires et 15 Places 234 502 &amp;euro; euros de gains,&amp;nbsp;&amp;nbsp;Bed Ford 8 Victoires et 15 Places 190&amp;nbsp;407 &amp;euro; de gains,&amp;nbsp;&amp;nbsp;Adams 18 Victoires et 34 Places 261&amp;nbsp;425 &amp;euro; de gains.&lt;br /&gt;\r\nA d&amp;eacute;j&amp;agrave; produit :&amp;nbsp;\r\n&lt;ul&gt;\r\n	&lt;li&gt;2013&lt;strong&gt;&amp;nbsp;- &lt;/strong&gt;Enki Girl&amp;nbsp;(f. bai&amp;nbsp;par YOUMZAIN), &amp;nbsp;5 Courses et 4 Places, 17730 &amp;euro; de gains. Merci &amp;agrave; son entourage.&lt;/li&gt;\r\n	&lt;li&gt;2014 - Lily Enki (f. baie par BUCK&amp;#39;S BOUM).&lt;/li&gt;\r\n	&lt;li&gt;2015 - Pipo Baratin (m. baie par LINDA&amp;#39;S LAD)&lt;/li&gt;\r\n	&lt;li&gt;2016 - Holly Boum (m. bai par BUCK&amp;#39;S BOUM)&lt;/li&gt;\r\n	&lt;li&gt;2017 - Saillie en 2016 par BUCK&amp;#39;S BOUM - A terme le xx/xx/xxxx&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;br /&gt;\r\n&amp;nbsp;[/fr]', '[fr]&lt;em&gt;Par TESTA ROSSA (Au) &amp;amp; MISS HERNANDO (GB) par HERNANDO&lt;/em&gt;&lt;br /&gt;\r\nCette gagnante de quint&amp;eacute; en plat 5 Victoires et 13 Places 108 798 &amp;euro; de gains est issue de la souche de Mr Soula. &amp;nbsp;&lt;br /&gt;\r\nS&amp;oelig;ur de Darwind 196&amp;nbsp;346 &amp;euro; de gains 7 Victoires et 18 Places,&amp;nbsp;Miss Talma 92&amp;nbsp;158 &amp;euro; de gains 3 Victoires et 21 Places, et ni&amp;egrave;ce de&amp;nbsp; Worms 14 Victoires et 44 Places 389&amp;nbsp;072 &amp;euro; de gains,Kiny Country 11 Victoires et 36 Places 397&amp;nbsp;755 &amp;euro; de gains,&amp;nbsp;Leeds 4 Victoires et 15 Places 234 502 &amp;euro; euros de gains,&amp;nbsp;&amp;nbsp;Bed Ford 8 Victoires et 15 Places 190&amp;nbsp;407 &amp;euro; de gains,&amp;nbsp;&amp;nbsp;Adams 18 Victoires et 34 Places 261&amp;nbsp;425 &amp;euro; de gains.&lt;br /&gt;\r\nA d&amp;eacute;j&amp;agrave; produit :&amp;nbsp;\r\n&lt;ul&gt;\r\n	&lt;li&gt;2013&lt;strong&gt;&amp;nbsp;- &lt;/strong&gt;Enki Girl&amp;nbsp;(f. bai&amp;nbsp;par YOUMZAIN), &amp;nbsp;5 Courses et 4 Places, 17730 &amp;euro; de gains. Merci &amp;agrave; son entourage.&lt;/li&gt;\r\n	&lt;li&gt;2014 - Lily Enki (f. baie par BUCK&amp;#39;S BOUM).&lt;/li&gt;\r\n	&lt;li&gt;2015 - Pipo Baratin (m. baie par LINDA&amp;#39;S LAD)&lt;/li&gt;\r\n	&lt;li&gt;2016 - Holly Boum (m. bai par BUCK&amp;#39;S BOUM)&lt;/li&gt;\r\n	&lt;li&gt;2017 - Saillie en 2016 par BUCK&amp;#39;S BOUM - A terme le xx/xx/xxxx&lt;/li&gt;\r\n&lt;/ul&gt;[/fr]', 'HarasdEnky_logo_1000x626.jpg', '2016-09-19', 1),
(17, '1', '[fr]Sevilla 2006[/fr]', '[fr]Sevilla (06)[/fr]', '[fr]&lt;em&gt;Par LAVIRCO &amp;amp; GRACE DE VONNAS par CADOUDAL&lt;/em&gt;&lt;br /&gt;\r\nLa s&amp;oelig;ur de la M&amp;egrave;re du Champion Blue Dragon 10 Victoires et 2 Places 631180 &amp;euro;,&amp;nbsp;Prix Alain du Breuil GRI, Renaud du Vivier &lt;strong&gt;Gr.I&lt;/strong&gt;, L&amp;eacute;on Rambaud &lt;strong&gt;Gr.II&lt;/strong&gt;, Hypot&amp;egrave;se &lt;strong&gt;Gr.III&lt;/strong&gt; de Maison Laffitte &lt;strong&gt;Gr.II&lt;/strong&gt;, Questarad &lt;strong&gt;Gr.III&lt;/strong&gt; Prix J de Vienne.&lt;br /&gt;\r\n&amp;nbsp;[/fr]', '[fr]&lt;em&gt;Par LAVIRCO &amp;amp; GRACE DE VONNAS par CADOUDAL&lt;/em&gt;&lt;br /&gt;\r\nLa s&amp;oelig;ur de la M&amp;egrave;re du Champion Blue Dragon 10 Victoires et 2 Places 631180 &amp;euro;,&amp;nbsp;Prix Alain du Breuil GRI, Renaud du Vivier &lt;strong&gt;Gr.I&lt;/strong&gt;, L&amp;eacute;on Rambaud &lt;strong&gt;Gr.II&lt;/strong&gt;, Hypot&amp;egrave;se &lt;strong&gt;Gr.III&lt;/strong&gt; de Maison Laffitte &lt;strong&gt;Gr.II&lt;/strong&gt;, Questarad &lt;strong&gt;Gr.III&lt;/strong&gt; Prix J de Vienne.[/fr]', 'Sevilla-094.jpg', '2016-09-19', 1),
(18, '1', '[fr]Just Pegasus 2006[/fr]', '[fr]Just Pegasus (06)[/fr]', '[fr]&lt;em&gt;Par FUSAISHI PEGASUS (US) &amp;amp; JUST TOO TOO (US) par IN EXCESS (IE)&lt;/em&gt;&lt;br /&gt;\r\nCette jument de c&amp;oelig;ur qui nous a offert de grandes joies compte 3 Victoires et 3 Places, 2&lt;sup&gt;&amp;egrave;me&lt;/sup&gt; en d&amp;eacute;butant de la championne de la Famille Lerner Celimene et 6&lt;sup&gt;&amp;egrave;me&lt;/sup&gt; du Prix Saint Alary &lt;strong&gt;Gr.I&lt;/strong&gt;&amp;nbsp;.\r\n&lt;ul&gt;\r\n	&lt;li&gt;2013 - Big Pegasus (f. baie par BUCK&amp;#39;S BOUM)&lt;/li&gt;\r\n	&lt;li&gt;2014 - Paakame Enki (f. baie par IRISH WELLS)&lt;/li&gt;\r\n	&lt;li&gt;2015 - Enki Princess (f. baie par MUHTATHIR)&lt;/li&gt;\r\n	&lt;li&gt;2016 - Cousu Main (m. bai par BUCK&amp;#39;S BOUM)&lt;/li&gt;\r\n	&lt;li&gt;2017 - Saillie en 2016 par KITKOU - A terme le xx/xx/xxxx&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;br /&gt;\r\n&amp;nbsp;[/fr]', '[fr]&lt;em&gt;Par FUSAISHI PEGASUS (US) &amp;amp; JUST TOO TOO (US) par IN EXCESS (IE)&lt;/em&gt;&lt;br /&gt;\r\nCette jument de c&amp;oelig;ur qui nous a offert de grandes joies compte 3 Victoires et 3 Places, 2&lt;sup&gt;&amp;egrave;me&lt;/sup&gt; en d&amp;eacute;butant de la championne de la Famille Lerner Celimene et 6&lt;sup&gt;&amp;egrave;me&lt;/sup&gt; du Prix Saint Alary &lt;strong&gt;Gr.I&lt;/strong&gt;&amp;nbsp;.\r\n&lt;ul&gt;\r\n	&lt;li&gt;2013 - Big Pegasus (f. baie par BUCK&amp;#39;S BOUM)&lt;/li&gt;\r\n	&lt;li&gt;2014 - Paakame Enki (f. baie par IRISH WELLS)&lt;/li&gt;\r\n	&lt;li&gt;2015 - Enki Princess (f. baie par MUHTATHIR)&lt;/li&gt;\r\n	&lt;li&gt;2016 - Cousu Main (m. bai par BUCK&amp;#39;S BOUM)&lt;/li&gt;\r\n	&lt;li&gt;2017 - Saillie en 2016 par KITKOU - A terme le xx/xx/xxxx&lt;/li&gt;\r\n&lt;/ul&gt;[/fr]', 'Just-Pegasus-136.jpg', '2016-09-19', 1),
(19, '1', '[fr]Balli Flight 2008[/fr]', '[fr]Balli Flight (08)[/fr]', '[fr]&lt;em&gt;Par BALLINGARY (IE) &amp;amp; SUNDAY FLIGHT par JOHNNY O&amp;#39; DAY (US)&lt;/em&gt;&lt;br /&gt;\r\nCette gagnante est tout simplement la s&amp;oelig;ur de deux gagnants de Haye Jousselin &lt;strong&gt;Gr.I&lt;/strong&gt;,&amp;nbsp;Sunny Flight 16 Victoires et 15 places 572743 &amp;euro; et&amp;nbsp;Golden Flight 11 Victoires et 10 places 769975 &amp;euro;, et ni&amp;egrave;ce de la championne Karly Flight 13 Victoires et 9 Places 820038 &amp;euro; de gains.\r\n&lt;ul&gt;\r\n	&lt;li&gt;2014 - Dame Vesta (f. baie par SAINT DES SAINTS&lt;/li&gt;\r\n	&lt;li&gt;2015 - Corbeval (m. alezan par KAPGARDE)&lt;/li&gt;\r\n	&lt;li&gt;2016 - Enki Flight (m. bai par BUCK&amp;#39;S BOUM)&lt;/li&gt;\r\n	&lt;li&gt;2017 - Saillie en 2016 par MARTALINE - A terme le xx/xx/xxxx&lt;/li&gt;\r\n&lt;/ul&gt;[/fr]', '[fr]&lt;em&gt;Par BALLINGARY (IE) &amp;amp; SUNDAY FLIGHT par JOHNNY O&amp;#39; DAY (US)&lt;/em&gt;&lt;br /&gt;\r\nCette gagnante est tout simplement la s&amp;oelig;ur de deux gagnants de Haye Jousselin &lt;strong&gt;Gr.I&lt;/strong&gt;,&amp;nbsp;Sunny Flight 16 Victoires et 15 places 572743 &amp;euro; et&amp;nbsp;Golden Flight 11 Victoires et 10 places 769975 &amp;euro;, et ni&amp;egrave;ce de la championne Karly Flight 13 Victoires et 9 Places 820038 &amp;euro; de gains.\r\n&lt;ul&gt;\r\n	&lt;li&gt;2014 - Dame Vesta (f. baie par SAINT DES SAINTS&lt;/li&gt;\r\n	&lt;li&gt;2015 - Corbeval (m. alezan par KAPGARDE)&lt;/li&gt;\r\n	&lt;li&gt;2016 - Enki Flight (m. bai par BUCK&amp;#39;S BOUM)&lt;/li&gt;\r\n	&lt;li&gt;2017 - Saillie en 2016 par MARTALINE - A terme le xx/xx/xxxx&lt;/li&gt;\r\n&lt;/ul&gt;[/fr]', 'BalliFlight-009.jpg', '2016-09-19', 1),
(20, '1', '[fr]Buck&#039;s Bravo 2008[/fr]', '[fr][/fr]', '[fr]&lt;em&gt;par KAPGARDE &amp;amp; BUCK&amp;#39;S BEAUTY par LYPHARD&amp;#39;S WISH&lt;/em&gt;&lt;br /&gt;\r\nCette plac&amp;eacute;e de &lt;strong&gt;Listed&lt;/strong&gt; &amp;agrave; Auteuil (Prix Finot) et la ni&amp;egrave;ce du ph&amp;eacute;nom&amp;egrave;ne Big Buck&amp;rsquo;s 23 Victoires.\r\n&lt;ul&gt;\r\n	&lt;li&gt;2014 - Buck&amp;#39;s Bin&amp;#39;s (m. bai par KHALKEVI)&lt;/li&gt;\r\n	&lt;li&gt;2016 - Buck&amp;#39;s Boggle (m. bai par TIGER GROOM)&lt;/li&gt;\r\n	&lt;li&gt;2017 - Saillie en 2016 par SPIDER FLIGHT - A terme le xx/xx/xxxx&lt;/li&gt;\r\n&lt;/ul&gt;[/fr]', '[fr]&lt;em&gt;par KAPGARDE &amp;amp; BUCK&amp;#39;S BEAUTY par LYPHARD&amp;#39;S WISH&lt;/em&gt;&lt;br /&gt;\r\nCette plac&amp;eacute;e de &lt;strong&gt;Listed&lt;/strong&gt; &amp;agrave; Auteuil (Prix Finot) et la ni&amp;egrave;ce du ph&amp;eacute;nom&amp;egrave;ne Big Buck&amp;rsquo;s 23 Victoires.\r\n&lt;ul&gt;\r\n	&lt;li&gt;2014 - Buck&amp;#39;s Bin&amp;#39;s (m. bai par KHALKEVI)&lt;/li&gt;\r\n	&lt;li&gt;2016 - Buck&amp;#39;s Boggle (m. bai par TIGER GROOM)&lt;/li&gt;\r\n	&lt;li&gt;2017 - Saillie en 2016 par SPIDER FLIGHT - A terme le xx/xx/xxxx&lt;/li&gt;\r\n&lt;/ul&gt;[/fr]', 'HarasdEnky_logo_1000x626.jpg', '2016-09-19', 1),
(21, '1', '[fr]Ulster Noire 2008[/fr]', '[fr]Ulster Noire (08)[/fr]', '[fr]&lt;em&gt;Par MARTALINE (GB) &amp;amp; HATILADE par ROYAL CHARTER&lt;/em&gt;&lt;br /&gt;\r\nCette fille du top &amp;eacute;talon Martaline est la s&amp;oelig;ur du crack Questarabad, grande course de haie d&amp;rsquo;Auteuil &lt;strong&gt;Gr.I&lt;/strong&gt;, Prix Renaud du Vivier &lt;strong&gt;Gr.I&lt;/strong&gt;, Grand Prix d&amp;rsquo;Automne &lt;strong&gt;Gr.I&lt;/strong&gt; (&lt;strong&gt;2 fois&lt;/strong&gt;), L&amp;eacute;on Rambaud &lt;strong&gt;Gr.II&lt;/strong&gt; (&lt;strong&gt;2 fois&lt;/strong&gt;) de Compi&amp;egrave;gne &lt;strong&gt;Gr.III&lt;/strong&gt; (&lt;strong&gt;2 fois&lt;/strong&gt;), Carmarthen &lt;strong&gt;Gr.III&lt;/strong&gt; (&lt;strong&gt;2 fois&lt;/strong&gt;), Juign&amp;eacute; &lt;strong&gt;Gr.III&lt;/strong&gt; (&lt;strong&gt;2 fois&lt;/strong&gt;) de Maison Laffitte &lt;strong&gt;Gr.III&lt;/strong&gt; et 1&amp;nbsp;547790&amp;euro; de gains.\r\n&lt;ul&gt;\r\n	&lt;li&gt;2016 - Grace Italienne (f. baie par DREAM WELL)&lt;/li&gt;\r\n	&lt;li&gt;2017 - Saillie en 2016 par NETWORK - A terme le xx/xx/xxxx&lt;/li&gt;\r\n&lt;/ul&gt;[/fr]', '[fr]&lt;em&gt;Par MARTALINE (GB) &amp;amp; HATILADE par ROYAL CHARTER&lt;/em&gt;&lt;br /&gt;\r\nCette fille du top &amp;eacute;talon Martaline est la s&amp;oelig;ur du crack Questarabad, grande course de haie d&amp;rsquo;Auteuil &lt;strong&gt;Gr.I&lt;/strong&gt;, Prix Renaud du Vivier &lt;strong&gt;Gr.I&lt;/strong&gt;, Grand Prix d&amp;rsquo;Automne &lt;strong&gt;Gr.I&lt;/strong&gt; (&lt;strong&gt;2 fois&lt;/strong&gt;), L&amp;eacute;on Rambaud &lt;strong&gt;Gr.II&lt;/strong&gt; (&lt;strong&gt;2 fois&lt;/strong&gt;) de Compi&amp;egrave;gne &lt;strong&gt;Gr.III&lt;/strong&gt; (&lt;strong&gt;2 fois&lt;/strong&gt;), Carmarthen &lt;strong&gt;Gr.III&lt;/strong&gt; (&lt;strong&gt;2 fois&lt;/strong&gt;), Juign&amp;eacute; &lt;strong&gt;Gr.III&lt;/strong&gt; (&lt;strong&gt;2 fois&lt;/strong&gt;) de Maison Laffitte &lt;strong&gt;Gr.III&lt;/strong&gt; et 1&amp;nbsp;547790&amp;euro; de gains.\r\n&lt;ul&gt;\r\n	&lt;li&gt;2016 - Grace Italienne (f. baie par DREAM WELL)&lt;/li&gt;\r\n	&lt;li&gt;2017 - Saillie en 2016 par NETWORK - A terme le xx/xx/xxxx&lt;/li&gt;\r\n&lt;/ul&gt;[/fr]', 'Photo-Juments-010.jpg', '2016-09-19', 1),
(22, '1', '[fr]Buck&#039;s Bandit 2010[/fr]', '[fr]Buck&#039;s Bandit (10)[/fr]', '[fr]&lt;em&gt;Par TREMPOLINO (US) &amp;amp; BUCK&amp;#39;S par LE GLORIEUX (GB)&lt;/em&gt;&lt;br /&gt;\r\nS&amp;oelig;ur du ph&amp;eacute;nom&amp;egrave;ne Big Buck&amp;rsquo;s 23 Victoires World Hurdle &lt;strong&gt;Gr.I&lt;/strong&gt;, 4 fois Liverpool Hurdel &lt;strong&gt;Gr.I&lt;/strong&gt;, 4 fois Long Walk Hurdel &lt;strong&gt;Gr.I&lt;/strong&gt;,&amp;nbsp;3 fois Long distance Hurdle &lt;strong&gt;Gr.II&lt;/strong&gt;, 4 fois 1 309 055 &amp;pound; et propre s&amp;oelig;ur de Buck&amp;rsquo;s Bank 3 Victoires en obstacle, grand steeple de Clairefontaine, &lt;strong&gt;Listed&lt;/strong&gt; 2&lt;sup&gt;&amp;egrave;me&lt;/sup&gt; Prix Violon II.\r\n&lt;ul&gt;\r\n	&lt;li&gt;2015 - Buck&amp;#39;s Brasilia (f. noir pangar&amp;eacute; par Blue Bresil)&lt;/li&gt;\r\n	&lt;li&gt;2017 - Saillie en 2016 par NETWORK - A terme le xx/xx/xxxx&lt;/li&gt;\r\n&lt;/ul&gt;[/fr]', '[fr]&lt;em&gt;Par TREMPOLINO (US) &amp;amp; BUCK&amp;#39;S par LE GLORIEUX (GB)&lt;/em&gt;&lt;br /&gt;\r\nS&amp;oelig;ur du ph&amp;eacute;nom&amp;egrave;ne Big Buck&amp;rsquo;s 23 Victoires World Hurdle &lt;strong&gt;Gr.I&lt;/strong&gt;, 4 fois Liverpool Hurdel &lt;strong&gt;Gr.I&lt;/strong&gt;, 4 fois Long Walk Hurdel &lt;strong&gt;Gr.I&lt;/strong&gt;,&amp;nbsp;3 fois Long distance Hurdle &lt;strong&gt;Gr.II&lt;/strong&gt;, 4 fois 1 309 055 &amp;pound; et propre s&amp;oelig;ur de Buck&amp;rsquo;s Bank 3 Victoires en obstacle, grand steeple de Clairefontaine, &lt;strong&gt;Listed&lt;/strong&gt; 2&lt;sup&gt;&amp;egrave;me&lt;/sup&gt; Prix Violon II.\r\n&lt;ul&gt;\r\n	&lt;li&gt;2015 - Buck&amp;#39;s Brasilia (f. noir pangar&amp;eacute; par Blue Bresil)&lt;/li&gt;\r\n	&lt;li&gt;2017 - Saillie en 2016 par NETWORK - A terme le xx/xx/xxxx&lt;/li&gt;\r\n&lt;/ul&gt;[/fr]', 'BucksBandit-073.jpg', '2016-09-19', 1),
(23, '1', '[fr]Cozumel Island 2011[/fr]', '[fr]Cozumel Island (11)[/fr]', '[fr]&lt;em&gt;Par MARTALINE (GB) &amp;amp; CHICITA DU BELAIS par NIKOS (GB)&lt;/em&gt;&lt;br /&gt;\r\nLa Petite derni&amp;egrave;re de nos p&amp;eacute;pettes est une descendante proche de la&amp;nbsp; grande famille du Berlais petite fille de Shinca, elle est la ni&amp;egrave;ce de Chim&amp;egrave;re du Berlais Prix Cambac&amp;eacute;r&amp;eacute;s &lt;strong&gt;Gr.I&lt;/strong&gt; 331000 &amp;euro; de gains,&amp;nbsp;Shinco du Berlais 4 Victoires et 27 Places et&amp;nbsp; 555655 &amp;euro; de gains, Lucky du Berlais 4 Victoires et&amp;nbsp; 16 Places 310880 &amp;euro; de gains,&amp;nbsp;Sherkan du Berlais 3 Victoires et 22 Places 237956 &amp;euro; de gains,&amp;nbsp;Famille de Bonito du Berlais 11 Victoires et 4 Places 757850 &amp;euro; de gains et&amp;nbsp;Nikita du Berlais 8 Victoires et 6 Places 503948 &amp;euro; de gains.&lt;br /&gt;\r\n&amp;nbsp;[/fr]', '[fr]&lt;em&gt;Par MARTALINE (GB) &amp;amp; CHICITA DU BELAIS par NIKOS (GB)&lt;/em&gt;&lt;br /&gt;\r\nLa Petite derni&amp;egrave;re de nos p&amp;eacute;pettes est une descendante proche de la&amp;nbsp; grande famille du Berlais petite fille de Shinca, elle est la ni&amp;egrave;ce de Chim&amp;egrave;re du Berlais Prix Cambac&amp;eacute;r&amp;eacute;s &lt;strong&gt;Gr.I&lt;/strong&gt; 331000 &amp;euro; de gains,&amp;nbsp;Shinco du Berlais 4 Victoires et 27 Places et&amp;nbsp; 555655 &amp;euro; de gains, Lucky du Berlais 4 Victoires et&amp;nbsp; 16 Places 310880 &amp;euro; de gains,&amp;nbsp;Sherkan du Berlais 3 Victoires et 22 Places 237956 &amp;euro; de gains,&amp;nbsp;Famille de Bonito du Berlais 11 Victoires et 4 Places 757850 &amp;euro; de gains et&amp;nbsp;Nikita du Berlais 8 Victoires et 6 Places 503948 &amp;euro; de gains.[/fr]', 'CozumelIland-142.jpg', '2016-09-19', 1),
(24, '1', '[fr]Sainte Oui Oui 2012[/fr]', '[fr]Sainte Oui Oui (12)[/fr]', '[fr]&lt;em&gt;Par SAINT DES SAINTS &amp;amp; COBEE DE LINIERS par PASQUINI&lt;/em&gt;&lt;br /&gt;\r\nCette fille du top &amp;eacute;talon Saint des Saints et la s&amp;oelig;ur du champion Prince Oui Oui 6 Victoires et 12 Places 426180&amp;euro; de gains, Prix Cambac&amp;eacute;r&amp;egrave;s &lt;strong&gt;Gr.I&lt;/strong&gt;, Prix Carmarthen &lt;strong&gt;Gr.III&lt;/strong&gt;, 2&lt;sup&gt;&amp;egrave;me&lt;/sup&gt; Prix Renaud du Vivier &lt;strong&gt;Gr.I&lt;/strong&gt;.\r\n&lt;ul&gt;\r\n	&lt;li&gt;Saillie en 2016 par NETWORK - A terme le xx/xx/xxxx&lt;/li&gt;\r\n&lt;/ul&gt;[/fr]', '[fr]&lt;em&gt;Par SAINT DES SAINTS &amp;amp; COBEE DE LINIERS par PASQUINI&lt;/em&gt;&lt;br /&gt;\r\nCette fille du top &amp;eacute;talon Saint des Saints et la s&amp;oelig;ur du champion Prince Oui Oui 6 Victoires et 12 Places 426180&amp;euro; de gains, Prix Cambac&amp;eacute;r&amp;egrave;s &lt;strong&gt;Gr.I&lt;/strong&gt;, Prix Carmarthen &lt;strong&gt;Gr.III&lt;/strong&gt;, 2&lt;sup&gt;&amp;egrave;me&lt;/sup&gt; Prix Renaud du Vivier &lt;strong&gt;Gr.I&lt;/strong&gt;.\r\n&lt;ul&gt;\r\n	&lt;li&gt;Saillie en 2016 par NETWORK - A terme le xx/xx/xxxx&lt;/li&gt;\r\n&lt;/ul&gt;[/fr]', 'SainteOuiOui-076.jpg', '2016-09-19', 1),
(25, '2', '[fr]A propos[/fr]', '[fr][/fr]', '[fr]&lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras facilisis lorem nec est cursus euismod. Morbi facilisis ut lorem nec rhoncus. Suspendisse porta nulla ac posuere sollicitudin. Vivamus placerat nibh nisi. Pellentesque vitae consequat lorem. Curabitur mattis ac leo vel rutrum. Sed tristique faucibus lobortis. Maecenas sit amet massa varius, mollis lorem ullamcorper, aliquam nulla. Integer tincidunt risus commodo tempus placerat. Ut placerat diam nec consectetur cursus.&lt;br /&gt;\r\n&lt;br /&gt;\r\n&lt;span class=&quot;marker&quot;&gt;TEXTES &amp;amp; PHOTOS A ME FOURNIR&lt;/span&gt;&lt;/p&gt;[/fr]', '[fr]&lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras facilisis lorem nec est cursus euismod. Morbi facilisis ut lorem nec rhoncus. Suspendisse porta nulla ac posuere sollicitudin. Vivamus placerat nibh nisi. Pellentesque vitae consequat lorem. Curabitur mattis ac leo vel rutrum. Sed tristique faucibus lobortis. Maecenas sit amet massa varius, mollis lorem ullamcorper, aliquam nulla. Integer tincidunt risus commodo tempus placerat. Ut placerat diam nec consectetur cursus.&lt;/p&gt;\r\n\r\n&lt;p&gt;Nam et mauris vitae ante elementum iaculis. Fusce eleifend orci dui, porta pretium sem scelerisque convallis. Vivamus eu elementum orci, in vehicula massa. Donec luctus, mauris non consectetur tempor, dui metus luctus sem, quis malesuada nisi ligula eget ex. Sed commodo nunc at mauris dictum, in semper augue congue. Donec condimentum porta nisl nec scelerisque. Nunc in tellus in risus lobortis commodo. Vivamus a elementum nunc. Mauris non est ipsum. Quisque eget diam in urna consectetur viverra et vel quam. Aliquam sit amet efficitur enim. Donec pulvinar nulla urna. Etiam tincidunt odio vitae purus imperdiet, efficitur condimentum nunc dictum.&lt;/p&gt;\r\n\r\n&lt;p&gt;Etiam faucibus nibh lectus, ut suscipit felis euismod a. Duis eget ligula at nisi gravida gravida a eu velit. Quisque eu ante a dui posuere vestibulum. Mauris euismod dui sed felis posuere fringilla. Nulla justo dui, sagittis vitae malesuada eu, dapibus imperdiet mauris. Maecenas facilisis urna non porttitor iaculis. Etiam eros nunc, mollis eu ipsum vel, dapibus semper massa. Suspendisse at facilisis sapien, id eleifend magna. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla feugiat volutpat rhoncus. Nunc sagittis sodales lorem, eget egestas orci tempus vitae.&lt;/p&gt;\r\n\r\n&lt;p&gt;Nulla pellentesque nisl felis, nec accumsan lacus mattis ut. Nulla non velit convallis, sodales mauris a, sollicitudin nibh. Nunc eu dictum ligula, non ullamcorper ipsum. Praesent condimentum pellentesque mi ac efficitur. Vivamus imperdiet quis felis sed luctus. Nunc tristique ligula ante, eu dignissim nisl efficitur et. Quisque eget justo sed ex tincidunt ornare et sit amet nibh. Suspendisse ac nunc interdum quam consequat commodo. Quisque lobortis et neque sed tincidunt. Etiam ultricies lacus nec nisi gravida, ac mollis libero condimentum.&lt;/p&gt;\r\n\r\n&lt;br /&gt;\r\n&lt;span class=&quot;marker&quot;&gt;TEXTES &amp;amp; PHOTOS A ME FOURNIR&lt;/span&gt;&lt;/p&gt;[/fr]', 'le-haras-a-propos.jpg', '2016-09-20', 1),
(26, '2', '[fr]Histoire[/fr]', '[fr][/fr]', '[fr]&lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras facilisis lorem nec est cursus euismod. Morbi facilisis ut lorem nec rhoncus. Suspendisse porta nulla ac posuere sollicitudin. Vivamus placerat nibh nisi. Pellentesque vitae consequat lorem. Curabitur mattis ac leo vel rutrum. Sed tristique faucibus lobortis. Maecenas sit amet massa varius, mollis lorem ullamcorper, aliquam nulla. Integer tincidunt risus commodo tempus placerat. Ut placerat diam nec consectetur cursus.&lt;/p&gt;\r\n\r\n&lt;br /&gt;\r\n&lt;span class=&quot;marker&quot;&gt;TEXTES &amp;amp; PHOTOS A ME FOURNIR&lt;/span&gt;&lt;/p&gt;[/fr]', '[fr]&lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras facilisis lorem nec est cursus euismod. Morbi facilisis ut lorem nec rhoncus. Suspendisse porta nulla ac posuere sollicitudin. Vivamus placerat nibh nisi. Pellentesque vitae consequat lorem. Curabitur mattis ac leo vel rutrum. Sed tristique faucibus lobortis. Maecenas sit amet massa varius, mollis lorem ullamcorper, aliquam nulla. Integer tincidunt risus commodo tempus placerat. Ut placerat diam nec consectetur cursus.&lt;/p&gt;\r\n\r\n&lt;p&gt;Nam et mauris vitae ante elementum iaculis. Fusce eleifend orci dui, porta pretium sem scelerisque convallis. Vivamus eu elementum orci, in vehicula massa. Donec luctus, mauris non consectetur tempor, dui metus luctus sem, quis malesuada nisi ligula eget ex. Sed commodo nunc at mauris dictum, in semper augue congue. Donec condimentum porta nisl nec scelerisque. Nunc in tellus in risus lobortis commodo. Vivamus a elementum nunc. Mauris non est ipsum. Quisque eget diam in urna consectetur viverra et vel quam. Aliquam sit amet efficitur enim. Donec pulvinar nulla urna. Etiam tincidunt odio vitae purus imperdiet, efficitur condimentum nunc dictum.&lt;/p&gt;\r\n\r\n&lt;p&gt;Etiam faucibus nibh lectus, ut suscipit felis euismod a. Duis eget ligula at nisi gravida gravida a eu velit. Quisque eu ante a dui posuere vestibulum. Mauris euismod dui sed felis posuere fringilla. Nulla justo dui, sagittis vitae malesuada eu, dapibus imperdiet mauris. Maecenas facilisis urna non porttitor iaculis. Etiam eros nunc, mollis eu ipsum vel, dapibus semper massa. Suspendisse at facilisis sapien, id eleifend magna. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla feugiat volutpat rhoncus. Nunc sagittis sodales lorem, eget egestas orci tempus vitae.&lt;/p&gt;\r\n\r\n&lt;p&gt;Nulla pellentesque nisl felis, nec accumsan lacus mattis ut. Nulla non velit convallis, sodales mauris a, sollicitudin nibh. Nunc eu dictum ligula, non ullamcorper ipsum. Praesent condimentum pellentesque mi ac efficitur. Vivamus imperdiet quis felis sed luctus. Nunc tristique ligula ante, eu dignissim nisl efficitur et. Quisque eget justo sed ex tincidunt ornare et sit amet nibh. Suspendisse ac nunc interdum quam consequat commodo. Quisque lobortis et neque sed tincidunt. Etiam ultricies lacus nec nisi gravida, ac mollis libero condimentum.&lt;/p&gt;\r\n\r\n&lt;br /&gt;\r\n&lt;span class=&quot;marker&quot;&gt;TEXTES &amp;amp; PHOTOS A ME FOURNIR&lt;/span&gt;&lt;/p&gt;[/fr]', 'le-haras-histoire.jpg', '2016-09-20', 1),
(27, '2', '[fr]Infrastructure[/fr]', '[fr][/fr]', '[fr]&lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras facilisis lorem nec est cursus euismod. Morbi facilisis ut lorem nec rhoncus. Suspendisse porta nulla ac posuere sollicitudin. Vivamus placerat nibh nisi. Pellentesque vitae consequat lorem. Curabitur mattis ac leo vel rutrum. Sed tristique faucibus lobortis. Maecenas sit amet massa varius, mollis lorem ullamcorper, aliquam nulla. Integer tincidunt risus commodo tempus placerat. Ut placerat diam nec consectetur cursus.&lt;/p&gt;\r\n\r\n&lt;br /&gt;\r\n&lt;span class=&quot;marker&quot;&gt;TEXTES &amp;amp; PHOTOS A ME FOURNIR&lt;/span&gt;&lt;/p&gt;[/fr]', '[fr]&lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras facilisis lorem nec est cursus euismod. Morbi facilisis ut lorem nec rhoncus. Suspendisse porta nulla ac posuere sollicitudin. Vivamus placerat nibh nisi. Pellentesque vitae consequat lorem. Curabitur mattis ac leo vel rutrum. Sed tristique faucibus lobortis. Maecenas sit amet massa varius, mollis lorem ullamcorper, aliquam nulla. Integer tincidunt risus commodo tempus placerat. Ut placerat diam nec consectetur cursus.&lt;/p&gt;\r\n\r\n&lt;p&gt;Nam et mauris vitae ante elementum iaculis. Fusce eleifend orci dui, porta pretium sem scelerisque convallis. Vivamus eu elementum orci, in vehicula massa. Donec luctus, mauris non consectetur tempor, dui metus luctus sem, quis malesuada nisi ligula eget ex. Sed commodo nunc at mauris dictum, in semper augue congue. Donec condimentum porta nisl nec scelerisque. Nunc in tellus in risus lobortis commodo. Vivamus a elementum nunc. Mauris non est ipsum. Quisque eget diam in urna consectetur viverra et vel quam. Aliquam sit amet efficitur enim. Donec pulvinar nulla urna. Etiam tincidunt odio vitae purus imperdiet, efficitur condimentum nunc dictum.&lt;/p&gt;\r\n\r\n&lt;p&gt;Etiam faucibus nibh lectus, ut suscipit felis euismod a. Duis eget ligula at nisi gravida gravida a eu velit. Quisque eu ante a dui posuere vestibulum. Mauris euismod dui sed felis posuere fringilla. Nulla justo dui, sagittis vitae malesuada eu, dapibus imperdiet mauris. Maecenas facilisis urna non porttitor iaculis. Etiam eros nunc, mollis eu ipsum vel, dapibus semper massa. Suspendisse at facilisis sapien, id eleifend magna. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla feugiat volutpat rhoncus. Nunc sagittis sodales lorem, eget egestas orci tempus vitae.&lt;/p&gt;\r\n\r\n&lt;p&gt;Nulla pellentesque nisl felis, nec accumsan lacus mattis ut. Nulla non velit convallis, sodales mauris a, sollicitudin nibh. Nunc eu dictum ligula, non ullamcorper ipsum. Praesent condimentum pellentesque mi ac efficitur. Vivamus imperdiet quis felis sed luctus. Nunc tristique ligula ante, eu dignissim nisl efficitur et. Quisque eget justo sed ex tincidunt ornare et sit amet nibh. Suspendisse ac nunc interdum quam consequat commodo. Quisque lobortis et neque sed tincidunt. Etiam ultricies lacus nec nisi gravida, ac mollis libero condimentum.&lt;br /&gt;\r\n&lt;br /&gt;\r\n&amp;nbsp;&lt;/p&gt;\r\n&lt;br /&gt;\r\n&lt;span class=&quot;marker&quot;&gt;TEXTES &amp;amp; PHOTOS A ME FOURNIR&lt;/span&gt;&lt;/p&gt;[/fr]', 'le-haras-infrastructure.jpg', '2016-09-20', 1);
INSERT INTO `pr08T5y_sb_news` (`id`, `catid`, `title`, `subtitle`, `desc_short`, `desc_full`, `image`, `date`, `active`) VALUES
(28, '1', '[fr]Kalifko s&rsquo;affirme comme le leader incontest&eacute; des 3ans[/fr]', '[fr][/fr]', '[fr]&lt;span class=&quot;marker&quot;&gt;TEXTE &amp;amp; PHOTO A VERIFIER / MODIFIER&lt;/span&gt;&lt;br /&gt;\r\n&lt;em&gt;(source: JDG) -&amp;nbsp;&lt;/em&gt;Meilleur poulain de 3ans au printemps, suite &amp;agrave; sa brillante victoire dans le Prix Aguado (Gr3), Kalifko (Montmartre) &amp;eacute;tait tr&amp;egrave;s attendu pour sa rentr&amp;eacute;e, qu&amp;#39;il a effectu&amp;eacute;e dans le Prix Robert Lejeune (L). Il a confirm&amp;eacute; son statut en s&amp;rsquo;imposant plus facilement que ne l&amp;rsquo;indique l&amp;rsquo;&amp;eacute;cart de deux longueurs et demie entre lui et son dauphin, Invicter (Sholokhov). Le cheval de la famille Papot a gagn&amp;eacute; avec de la marge et il est sans conteste le meilleur &amp;eacute;l&amp;eacute;ment de sa g&amp;eacute;n&amp;eacute;ration, poulains et pouliches confondus. Il faudra &amp;ecirc;tre tr&amp;egrave;s fort pour le battre dans les Prix Georges de Talhoue&amp;Igrave;t-Roy (Gr2) et Cambaceres (Gr1).[/fr]', '[fr]&lt;span class=&quot;marker&quot;&gt;TEXTE &amp;amp; PHOTO A VERIFIER / MODIFIER&lt;/span&gt;\r\n&lt;br /&gt;\r\n&lt;em&gt;(source: JDG) &lt;/em&gt;&lt;br /&gt;\r\nMeilleur poulain de 3ans au printemps, suite &amp;agrave; sa brillante victoire dans le Prix Aguado (Gr3), Kalifko (Montmartre) &amp;eacute;tait tr&amp;egrave;s attendu pour sa rentr&amp;eacute;e, qu&amp;#39;il a effectu&amp;eacute;e dans le Prix Robert Lejeune (L). Il a confirm&amp;eacute; son statut en s&amp;rsquo;imposant plus facilement que ne l&amp;rsquo;indique l&amp;rsquo;&amp;eacute;cart de deux longueurs et demie entre lui et son dauphin, Invicter (Sholokhov). Le cheval de la famille Papot a gagn&amp;eacute; avec de la marge et il est sans conteste le meilleur &amp;eacute;l&amp;eacute;ment de sa g&amp;eacute;n&amp;eacute;ration, poulains et pouliches confondus. Il faudra &amp;ecirc;tre tr&amp;egrave;s fort pour le battre dans les Prix Georges de Talhoue&amp;Igrave;t-Roy (Gr2) et Cambaceres (Gr1).&lt;br /&gt;\r\nKalifko a pris de la force. Ces &amp;eacute;preuves de rentr&amp;eacute;e sont toujours un peu pie&amp;egrave;ge pour les jeunes &amp;eacute;l&amp;eacute;ments. Encore plus quand le cheval de classe doit rendre du poids &amp;agrave; ses adversaires. Mais Kalifko s&amp;rsquo;est montr&amp;eacute; appliqu&amp;eacute; comme au premier semestre. Il a galop&amp;eacute; avec beaucoup d&amp;rsquo;&amp;eacute;nergie &amp;agrave; trois longueurs de l&amp;rsquo;animateur, Invicter. Tous deux ont &amp;eacute;volu&amp;eacute; avec une dizaine de longueurs d&amp;rsquo;avance sur le reste du peloton. Dans le tournant d&amp;rsquo;Auteuil, Morgan Regairaz, jockey de Kalifko, a repris son partenaire, laissant Invicter prendre du champ. Dans le tournant final, il y a eu un regroupement g&amp;eacute;n&amp;eacute;ral. Kalifko a acc&amp;eacute;l&amp;eacute;r&amp;eacute; en pleine piste &amp;agrave; l&amp;rsquo;entr&amp;eacute;e de la ligne droite. Invicter lui a longtemps tenu t&amp;ecirc;te c&amp;ocirc;t&amp;eacute; corde, mais, dans les quatre-vingts derniers m&amp;egrave;tres, Kalifko a pris ais&amp;eacute;ment l&amp;rsquo;avantage au prix d&amp;rsquo;une belle fin de course. Le tout sans &amp;ecirc;tre sollicit&amp;eacute; &amp;agrave; la cravache par Morgan Regairaz.[/fr]', 'kalifko_2.jpg', '2016-09-20', 1);

-- --------------------------------------------------------

--
-- Structure de la table `pr08T5y_sb_news_category`
--

CREATE TABLE `pr08T5y_sb_news_category` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `subtitle` text NOT NULL,
  `tpl_list` text NOT NULL,
  `tpl_single` text NOT NULL,
  `module_show` varchar(50) NOT NULL COMMENT 'normal,masonry,...',
  `module_show_masonry` int(11) NOT NULL COMMENT 'columns width (pixels)',
  `photo` varchar(255) NOT NULL,
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `sort` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `pr08T5y_sb_news_category`
--

INSERT INTO `pr08T5y_sb_news_category` (`id`, `title`, `subtitle`, `tpl_list`, `tpl_single`, `module_show`, `module_show_masonry`, `photo`, `active`, `sort`) VALUES
(1, '[fr]Cat&eacute;gorie 1[/fr]', '[fr][/fr]', '<div id=''toto''></div>', '<span class="date-article">{$item.date|sbConvertDate:"FR"}</span>\r\n<h3>{$item.title|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}</h3>\r\n		{if $item.subtitle|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`" != ""}\r\n			<h4>{$item.subtitle|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}</h4>\r\n		{/if}\r\n		<p class="sbnews-single">\r\n		    <a class="fancybox" rel="group" href="{$smarty.const._AM_MEDIAS_URL}{$item.image}" data-title="{$item.title|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}" data-lightbox="article">\r\n			    <img class="sbnews-single-img" src="{$smarty.const._AM_MEDIAS_URL}{$item.image}" alt="{$item.title|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}">\r\n			</a>\r\n			<div class="sbnews-single-text">\r\n				{$item.desc_full|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}\r\n			</div>\r\n		</p>\r\n\r\n		<div class="sbnews-clear-both"> </div>\r\n				\r\n		<div class="sbnews-single-next-prev">\r\n			{if $sbnews_options.news_next_prev == "title"}\r\n				{if $next_prev.next}\r\n					<a class="next_a" href="{seo url="index.php?p=news&op=article&id={$next_prev.next}" rewrite="news/article/{$next_prev.next}/{$next_prev.next_title|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"|@strip_tags|@sbRewriteString|@strtolower}"}">\r\n						â‡Â {$next_prev.next_title|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"|@strip_tags|truncate:"40":"..."}\r\n					</a>\r\n				{/if}\r\n				{if $next_prev.prev}\r\n					<a class="prev_a" href="{seo url="index.php?p=news&op=article&id={$next_prev.prev}" rewrite="news/article/{$next_prev.prev}/{$next_prev.prev_title|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"|@strip_tags|@sbRewriteString|@strtolower}"}">\r\n						{$next_prev.prev_title|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"|@strip_tags|truncate:"40":"..."}Â â‡’\r\n					</a>\r\n				{/if}\r\n			{else}\r\n				{if $next_prev.next}\r\n				<span class="next">\r\n					<a href="{seo url="index.php?p=news&op=article&id={$next_prev.next}" rewrite="news/article/{$next_prev.next}/{$next_prev.next_title|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"|@strip_tags|@sbRewriteString|@strtolower}"}">\r\n						â€º\r\n					</a>\r\n				</span>\r\n				{/if}\r\n				{if $next_prev.prev}\r\n				<span class="prev">\r\n					<a href="{seo url="index.php?p=news&op=article&id={$next_prev.prev}" rewrite="news/article/{$next_prev.prev}/{$next_prev.prev_title|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"|@strip_tags|@sbRewriteString|@strtolower}"}">\r\n						â€¹\r\n					</a>\r\n				</span>\r\n				{/if}\r\n			{/if}\r\n		</div>\r\n\r\n		<div class="sbnews-clear-both"> </div>\r\n		\r\n		{if $sbnews_options.other_news}\r\n			{math equation="x - 2" x=$sbnews_options.other_news_per_page assign=media_768}\r\n			{math equation="x - 1" x=$media_768 assign=media_544}\r\n			<style>\r\n				.sbnews-single-others {\r\n					-webkit-columns: {$sbnews_options.other_news_per_page|default:5};\r\n					-moz-columns: {$sbnews_options.other_news_per_page|default:5};\r\n					columns: {$sbnews_options.other_news_per_page|default:5};\r\n				}\r\n				.sbnews-othernews {\r\n					height: 300px;\r\n				}\r\n				@media screen and (max-width: 768px) {\r\n					.sbnews-single-others {\r\n						-webkit-columns: {$media_768};\r\n						-moz-columns: {$media_768};\r\n						columns: {$media_768};\r\n					}\r\n					.sbnews-othernews {\r\n					  margin-bottom: 10px;\r\n					}\r\n				}\r\n				@media screen and (max-width: 544px) {\r\n					.sbnews-single-others {\r\n						-webkit-columns: {$media_544};\r\n						-moz-columns: {$media_544};\r\n						columns: {$media_544};\r\n					}\r\n					.sbnews-othernews {\r\n					  margin-bottom: 10px;\r\n					}\r\n				}\r\n			</style>\r\n    		{if $sbnews_options.other_news_title}\r\n    			<h2 class="sbnews-single-others-h2">{$sbnews_options.other_news_title}</h2>\r\n    		{/if}\r\n    		<div class="sbnews-single-others">\r\n    			{foreach from=$sbnews_other_news item=other}\r\n    			    <a href="{seo url="index.php?p=news&op=article&id={$other.id}" rewrite="news/article/{$other.id}/{$other.title|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"|@strip_tags|@sbRewriteString|@strtolower}"}">\r\n    				<div class="sbnews-othernews" style="{if $other.image}background: url({$smarty.const._AM_MEDIAS_URL}/{$other.image}) no-repeat center center;{/if}">\r\n    					<span class="title">{$other.title|unescape:"htmlall"|@strip_tags|@sbDisplayLang:"`$smarty.session.lang`"|upper}</span>\r\n    					<span class="content">{$other.desc_short|unescape:"htmlall"|@strip_tags|@sbDisplayLang:"`$smarty.session.lang`"|lower|truncate:150:"..."}</span>\r\n    				</div>\r\n    				</a>\r\n    			{/foreach}\r\n    		</div>\r\n		{/if}\r\n		\r\n		<div class="sbnews-clear-both"> </div>', 'masonrycss', 300, '', 1, 0),
(2, '[fr]Cat&eacute;gorie 2[/fr]', '[fr][/fr]', '', '', 'masonrycss', 0, '', 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `pr08T5y_sb_news_settings`
--

CREATE TABLE `pr08T5y_sb_news_settings` (
  `id` int(11) NOT NULL,
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
  `news_next_prev` varchar(20) NOT NULL DEFAULT 'arrow' COMMENT 'arrow, title'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `pr08T5y_sb_news_settings`
--

INSERT INTO `pr08T5y_sb_news_settings` (`id`, `item_per_page`, `module_start`, `catid`, `breadcrumb`, `title_h1`, `title_h2`, `theme_view_cat`, `theme_view_list`, `theme_view_single`, `other_news`, `other_news_per_page`, `other_news_title`, `other_news_type`, `news_next_prev`) VALUES
(1, 10, 0, 1, 1, 1, 1, 'boxed-left-sidebar', 'boxed-left-sidebar', 'boxed-left-sidebar', 1, 3, 'Autres articles', 'random', 'title');

-- --------------------------------------------------------

--
-- Structure de la table `pr08T5y_sb_pages`
--

CREATE TABLE `pr08T5y_sb_pages` (
  `id` int(11) NOT NULL,
  `menu` varchar(100) NOT NULL COMMENT 'Texte du menu',
  `title` varchar(255) NOT NULL COMMENT 'Titre de la page',
  `content` text NOT NULL,
  `content_src` text NOT NULL COMMENT 'Source Page Builder',
  `seo_url` text NOT NULL,
  `url_custom` varchar(255) NOT NULL,
  `seo_keywords` text NOT NULL COMMENT 'Mots cles additionnels de la page',
  `seo_description` varchar(155) NOT NULL COMMENT 'Meta description additionnels de la page',
  `module_view` varchar(50) NOT NULL COMMENT 'Module view for the current page',
  `theme_view` varchar(50) NOT NULL COMMENT 'Theme view defined by CMS theme',
  `headpage` text NOT NULL COMMENT 'Code entete page theme (si declare dans config)',
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `sort` int(11) NOT NULL COMMENT 'Tri des pages / menus'
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='Pages libres du site';

--
-- Contenu de la table `pr08T5y_sb_pages`
--

INSERT INTO `pr08T5y_sb_pages` (`id`, `menu`, `title`, `content`, `content_src`, `seo_url`, `url_custom`, `seo_keywords`, `seo_description`, `module_view`, `theme_view`, `headpage`, `active`, `sort`) VALUES
(1, '[fr]Accueil[/fr]', '[fr]Accueil[/fr]', '[fr]&lt;p&gt;[CS id=1 name=sbtabbs]&lt;br /&gt;\r\n&lt;br /&gt;\r\n[CS id=1 name=sbtable]&lt;br /&gt;\r\n&lt;br /&gt;\r\nThank you for using SBMAGIC CMS. This is your homepage, so please change this text to be what you want.&lt;/p&gt;\r\n\r\n&lt;ul&gt;\r\n	&lt;li&gt;&lt;a href=&quot;#&quot;&gt;SBMAGIC CMS Documentation&lt;/a&gt;\r\n\r\n	&lt;ul&gt;\r\n		&lt;li&gt;&lt;a href=&quot;#&quot;&gt;How to Create a SBMAGIC Theme&lt;/a&gt;&lt;/li&gt;\r\n	&lt;/ul&gt;\r\n	&lt;/li&gt;\r\n	&lt;li&gt;&lt;a href=&quot;#&quot;&gt;SBMAGIC Support Forums&lt;/a&gt;&lt;/li&gt;\r\n&lt;/ul&gt;\r\n\r\n&lt;h2&gt;Header 2&lt;/h2&gt;\r\n\r\n&lt;p&gt;Lorem ipsum &lt;em&gt;dolor sit amet&lt;/em&gt;, &lt;strong&gt;consectetur adipiscing elit&lt;/strong&gt;. Donec &lt;code&gt;this is code&lt;/code&gt; venenatis augue. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Integer vulputate pretium augue.&lt;/p&gt;\r\n\r\n&lt;h3&gt;Header 3&lt;/h3&gt;\r\n\r\n&lt;pre&gt;\r\n&lt;code class=&quot;language-css&quot;&gt;#header h1 a { \r\n	display: block; \r\n	width: 300px; \r\n	height: 80px; \r\n}&lt;/code&gt;&lt;/pre&gt;\r\n\r\n&lt;h4&gt;Header 4&lt;/h4&gt;\r\n\r\n&lt;ol&gt;\r\n	&lt;li&gt;Lorem ipsum dolor sit amet&lt;/li&gt;\r\n	&lt;li&gt;Consectetur adipiscing elit&lt;/li&gt;\r\n	&lt;li&gt;Donec ut est risus, placerat venenatis augue&lt;/li&gt;\r\n&lt;/ol&gt;\r\n\r\n&lt;blockquote&gt;A blockquote. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec ut est risus, placerat venenatis augue. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.&lt;/blockquote&gt;[/fr]', '', '', '', 'mon,site,page,accueil', 'La page d&#039;accueil de mon site', 'contact', 'index-right-sidebar', '5', 1, 0),
(2, '[fr]News[/fr]', '[fr]Actualit&eacute;s du haras[/fr]', '[fr]Actus[/fr]', '', 'actualites', '', '', '', 'news', 'boxed-left-sidebar', '', 1, 0),
(3, '[fr]Effectifs[/fr]', '[fr]Effectifs de l&#039;&eacute;curie[/fr]', '[fr]EFFECTIFS[/fr]', '', 'effectifs-de-l-ecurie', '', '', '', 'effectives', 'boxed-left-sidebar', '', 1, 0),
(4, '[fr]Contact[/fr]', '[fr]Contactez nous[/fr]', '[fr][/fr]', '', 'contact', '', '', '', 'contact', 'boxed-right-sidebar', '5', 1, 0),
(5, '[fr]Graduates[/fr]', '[fr]Graduates[/fr]', '[fr][/fr]', '', 'graduates', '', '', '', 'graduates', 'index', '', 1, 0),
(6, '[fr]PAGE 1[/fr]', '[fr]Page 1[/fr]', '[fr]&lt;div id=&quot;lipsum&quot;&gt;\r\n&lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut in sem turpis. Ut congue, nunc quis pulvinar feugiat, ligula felis viverra nisi, id blandit turpis sapien eu arcu. Suspendisse potenti. Morbi bibendum tincidunt tortor, nec maximus ligula posuere et. Ut at malesuada metus, ultrices faucibus ipsum. Suspendisse potenti. Quisque accumsan magna ligula, eget tempus lectus gravida eu. Nam tincidunt leo lacus.&lt;/p&gt;\r\n\r\n&lt;p&gt;Nulla tincidunt eleifend sapien elementum malesuada. Aenean scelerisque tincidunt quam, vitae suscipit justo ultrices ac. Donec lobortis luctus felis, vitae dapibus lectus pharetra sed. Donec efficitur lacus ut odio feugiat ullamcorper. Sed posuere nibh vel molestie convallis. Ut a nulla dignissim, posuere purus sit amet, volutpat quam. Nullam purus justo, imperdiet eget mauris a, cursus rhoncus nunc. Cras fermentum rutrum diam pretium vulputate. Proin tempor euismod massa in volutpat. Curabitur aliquam, orci eu varius euismod, nisi ligula mattis metus, sit amet bibendum leo leo sed nulla. Maecenas ac convallis tortor. Vivamus vitae accumsan lacus. In faucibus egestas ultrices.&lt;/p&gt;\r\n\r\n&lt;p&gt;Donec nisl justo, molestie et eleifend in, condimentum nec quam. Sed efficitur ante consectetur libero sagittis tincidunt. Sed eleifend lorem sit amet faucibus consequat. Etiam et justo ipsum. Nam venenatis nisi in odio scelerisque volutpat. Duis feugiat felis sit amet sapien fringilla elementum. Phasellus quis semper orci. Nulla tortor erat, malesuada id ligula quis, efficitur volutpat velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae;&lt;/p&gt;\r\n\r\n&lt;p&gt;Praesent convallis diam ut finibus pellentesque. Suspendisse aliquam nisi at consectetur varius. Donec euismod, nisl ac egestas pulvinar, est tortor iaculis ipsum, eget feugiat lacus velit quis tortor. Nunc condimentum dictum sem, sit amet volutpat lorem venenatis eget. Curabitur quis lorem sapien. Pellentesque ac odio dui. Vivamus a nibh malesuada, dignissim nisl a, hendrerit felis. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Mauris tempor arcu quis malesuada tristique. Cras lacinia leo quis enim pharetra, et auctor arcu tincidunt. Donec efficitur luctus est nec eleifend. Etiam volutpat metus nec ipsum porta luctus. Praesent vehicula nec quam vitae mattis.&lt;/p&gt;\r\n\r\n&lt;p&gt;Cras tempor risus sit amet dapibus bibendum. Etiam ultrices ex risus, et sodales dui convallis ac. Nam ultrices tortor scelerisque velit sagittis molestie. Maecenas vitae aliquam elit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Quisque quis ipsum sit amet velit auctor ornare vitae ut diam. Pellentesque sit amet sapien quis purus euismod convallis. Nulla facilisi. Fusce iaculis porttitor nisi et iaculis. Aenean interdum lacinia odio. Cras porttitor volutpat lectus vitae porttitor.&lt;/p&gt;\r\n&lt;/div&gt;[/fr]', '', 'page-1', '', '', '', '', 'index-left-sidebar', '', 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `pr08T5y_sb_sandbox`
--

CREATE TABLE `pr08T5y_sb_sandbox` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `sire` varchar(255) NOT NULL,
  `sire_dam` varchar(255) NOT NULL,
  `dob` smallint(4) NOT NULL COMMENT 'AnnÃ©e de naissance',
  `country` varchar(200) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `video` varchar(255) NOT NULL,
  `pedigree` varchar(255) NOT NULL,
  `comment_mare` text NOT NULL,
  `comment_prod` text NOT NULL,
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `sort` int(11) DEFAULT NULL COMMENT 'Tri des juments'
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COMMENT='Table SANDBOX';

--
-- Contenu de la table `pr08T5y_sb_sandbox`
--

INSERT INTO `pr08T5y_sb_sandbox` (`id`, `name`, `sire`, `sire_dam`, `dob`, `country`, `photo`, `video`, `pedigree`, `comment_mare`, `comment_prod`, `active`, `sort`) VALUES
(1, 'SOUTH SISTER', 'KENDOR', '', 2010, 'FRANCE', 'jument-test2-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Comm&eacute;nta&icirc;re sur la j&ucirc;ment', 'Commentaire sur la pr&ocirc;d', 1, 0),
(2, 'BALDER PHENIX', 'PRESENTING', '', 2010, 'FRANCE', 'jument-test1-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire Jument', 'Commentaire Production', 1, 0),
(3, 'SAFFARONA', 'BARATHEA', '', 2007, 'FRANCE', 'jument-test2-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire SAFFARONA', 'Commentaire Production SAFFARONA', 1, 0),
(4, 'VALIDORA', 'KENDOR', '', 2011, 'FRANCE', 'jument-test1-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire VALIDORA', 'Commentaire Production VALIDORA', 1, 0),
(5, 'STUNNING', 'SLEW O&#039;GOLD', '', 1998, 'FRANCE', 'jument-test2-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire STUNNING', 'Commentaire Production STUNNING', 1, 0),
(6, 'TUNIS', 'WINGED LOVE', '', 2007, 'FRANCE', 'jument-test1-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire TUNIS', 'Commentaire Production TUNIS', 1, 0),
(7, 'ITASCA', 'UNFUWAIN', '', 2009, 'FRANCE', 'jument-test2-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire ITASCA', 'Commentaire Production ITASCA', 1, 0),
(8, 'UDANA', 'ASHKALANI', '', 2010, 'FRANCE', 'jument-test2-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire UDANA', 'Commentaire Production UDANA', 1, 0),
(9, 'QUATRE OR', 'CUPIDON', '', 2004, 'FRANCE', 'jument-test1-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire QUATRE OR', 'Commentaire Production QUATRE OR', 1, 0),
(10, 'ACENTELA', 'GALILEO', '', 2009, 'FRANCE', 'jument-test1-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire ACENTELA', 'Commentaire Production ACENTELA', 1, 0),
(11, 'SIENNA MAY', 'HIGHEST HONOR', '', 2006, 'FRANCE', 'jument-test2-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire SIENNA MAY', 'Commentaire Production SIENNA MAY', 1, 0),
(12, 'YERBA SOLDADO', 'MACHIAVELLIAN', '', 2010, 'FRANCE', 'jument-test1-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire YERBA SOLDADO', 'Commentaire Production YERBA SOLDADO', 1, 0),
(13, 'BECOMES YOU', 'LOMITAS', '', 2006, 'FRANCE', 'jument-test2-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire BECOMES YOU', 'Commentaire Production BECOMES YOU', 1, 0),
(14, 'SELVA REAL', 'PIVOTAL', '', 2011, 'FRANCE', 'jument-test1-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire SELVA REAL', 'Commentaire Production SELVA REAL', 1, 0),
(15, 'PARCIMONIE', 'DISTANT RELATIVE', '', 2007, 'FRANCE', 'jument-test2-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire PARCIMONIE', 'Commentaire Production PARCIMONIE', 1, 0),
(16, 'JOLIE LAIDE', 'MACHIAVELLIAN', '', 2007, 'FRANCE', 'jument-test2-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire JOLIE LAIDE', 'Commentaire Production JOLIE LAIDE', 1, 0),
(17, 'EASTER ROSE', 'AJRAAS', '', 2010, 'FRANCE', 'jument-test1-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire EASTER ROSE', 'Commentaire Production EASTER ROSE', 1, 0),
(18, 'FINEST CAPE', 'NOMBRE PREMIER', '', 2006, 'FRANCE', 'jument-test2-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire FINEST CAPE', 'Commentaire Production FINEST CAPE', 1, 0),
(19, 'EPATHA', 'AKARAD', '', 2003, 'FRANCE', 'jument-test2-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire EPATHA', 'Commentaire Production EPATHA', 1, 0),
(20, 'THET VIVA', 'APRIL NIGHT', '', 2005, 'FRANCE', 'jument-test2-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire THET VIVA', 'Commentaire Production THET VIVA', 1, 0),
(21, 'AL WASIL', 'KINGMAMBO', '', 2012, 'FRANCE', 'jument-test1-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire AL WASIL', 'Commentaire Production AL WASIL', 1, 0),
(22, 'CHANSONNETTE', 'GENEROUS', '', 2005, 'FRANCE', 'jument-test2-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire CHANSONNETTE', 'Commentaire Production CHANSONNETTE', 1, 0),
(23, 'GRACIOULSY', 'ORPEN', '', 2011, 'FRANCE', 'jument-test2-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire GRACIOULSY', 'Commentaire Production GRACIOULSY', 1, 0),
(24, 'HIDDEN COVE', 'ARAZI', '', 2010, 'FRANCE', 'jument-test1-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire HIDDEN COVE', 'Commentaire Production HIDDEN COVE', 1, 0),
(25, 'IFRANNE', '', '', 0, 'FRANCE', 'jument-test2-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire IFRANNE', 'Commentaire Production IFRANNE', 1, 0),
(26, 'LOVE ALOFT', 'SADLER&#039;S WELL', '', 2012, 'FRANCE', 'jument-test2-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire LOVE ALOFT', 'Commentaire Production LOVE ALOFT', 1, 0),
(27, 'PARCELLE PERDUE', 'ZAFONIC', '', 2006, 'FRANCE', 'jument-test1-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire PARCELLE PERDUE', 'Commentaire Production PARCELLE PERDUE', 1, 0),
(28, 'PYGMALION', 'IRISH RIVER', '', 1998, 'FRANCE', 'jument-test2-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire PYGMALION', 'Commentaire Production PYGMALION', 1, 0),
(29, 'RIO AMABLE', 'LINAMIX', '', 2009, 'FRANCE', 'jument-test1-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire RIO AMABLE', 'Commentaire Production RIO AMABLE', 1, 0),
(30, 'THAT&#039;S NELLIE', 'ROBIN DES CHAMPS', '', 2011, 'FRANCE', 'jument-test1-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire THAT&#039;S NELLIE', 'Commentaire Production THAT&#039;S NELLIE', 1, 0),
(31, 'TORECILLAS', 'DANSILI', '', 2012, 'FRANCE', 'jument-test2-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire TORECILLAS', 'Commentaire Production TORECILLAS', 1, 0),
(32, 'UNA VIVA', 'APRIL NIGHT', '', 2008, 'FRANCE', 'jument-test2-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'commentaire UNA VIVA', 'Commentaire Production UNA VIVA', 1, 0),
(33, 'VADARIYA', 'TREMPOLINO', '', 2012, 'FRANCE', 'jument-test2-compressor.jpg', 'https://www.youtube.com/watch?v=_pVCS8HbrmI', 'pdf-sample.pdf', 'Commentaire VADARIYA', 'Commentaire Production VADARIYA', 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `pr08T5y_sb_sessions`
--

CREATE TABLE `pr08T5y_sb_sessions` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `hash` varchar(32) NOT NULL,
  `expiredate` datetime NOT NULL,
  `ip` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table Session';

-- --------------------------------------------------------

--
-- Structure de la table `pr08T5y_sb_slider`
--

CREATE TABLE `pr08T5y_sb_slider` (
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `pr08T5y_sb_slider`
--

INSERT INTO `pr08T5y_sb_slider` (`id`, `title`, `jquery`, `responsive`, `auto`, `pause`, `speed`, `randomstart`, `mode`, `preloadimages`, `controls`, `autocontrols`, `autohover`, `captions`, `adaptiveheight`, `adaptiveheightspeed`, `slidemargin`, `video`, `usecss`, `pager`, `pagertype`, `active`) VALUES
(1, 'Accueil', 1, 1, 1, 4000, 500, 0, 'fade', 'visible', 1, 0, 0, 1, 0, 500, 0, 0, 1, 0, 'full', 1),
(2, 'Network', 1, 1, 1, 0, 0, 0, 'fade', 'visible', 0, 0, 0, 1, 0, 500, 0, 0, 1, 0, 'full', 1),
(3, 'Buck&#039;s Boom', 1, 1, 1, 0, 0, 1, 'fade', 'visible', 1, 0, 0, 0, 0, 500, 0, 0, 1, 0, 'full', 1),
(4, 'Kitkou', 1, 1, 0, 0, 0, 0, 'fade', 'visible', 0, 0, 0, 0, 0, 500, 0, 0, 1, 0, 'full', 1),
(5, 'homepage', 1, 1, 1, 4000, 500, 1, 'fade', 'visible', 1, 0, 0, 0, 0, 500, 0, 0, 1, 0, 'full', 1);

-- --------------------------------------------------------

--
-- Structure de la table `pr08T5y_sb_slider_photos`
--

CREATE TABLE `pr08T5y_sb_slider_photos` (
  `id` int(11) NOT NULL,
  `sid` int(11) NOT NULL COMMENT 'Slider id',
  `title` varchar(255) NOT NULL COMMENT 'Nom de la photo',
  `photo` varchar(255) NOT NULL COMMENT 'Nom de l''image physique',
  `type` varchar(10) NOT NULL COMMENT 'video, photo',
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `sort` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `pr08T5y_sb_slider_photos`
--

INSERT INTO `pr08T5y_sb_slider_photos` (`id`, `sid`, `title`, `photo`, `type`, `active`, `sort`) VALUES
(7, 1, 'RUBI B&Acirc;LL (NETWORK &amp; HYGIE) &lt;br&gt;  LA HAYE JOUSSEL&Iuml;N  Gr.1 x 2 &lt;br&gt;FERDINAND DUFAURE  Gr.1111111111', 'rubi-ball_network_1200x430.jpg', 'photo', 1, 2),
(8, 1, 'BIENVENUE AU HARAS D&#039;ENKI', 'entree-Haras_1200x430.jpg', '', 1, 1),
(9, 1, 'TEXTE PHOTO ...', '_TB-CROUPE-A4_1200x430.jpg', '', 1, 3),
(15, 3, 'Buck&#039;s Boom', 'buck-s-boum-etalon.jpg', 'photo', 1, 0),
(18, 4, 'Kitkou', 'photo-Kitkou-173_1200x430.jpg', '', 1, 0),
(19, 2, 'RUBI BALL - NETWORK', 'rubi-ball_.jpg', '', 1, 0),
(20, 5, '1', 'galerie-ef.de cropped.jpg', 'photo', 1, 0),
(21, 5, '2', '4-montar-a-caballo-roquetas.jpg', 'photo', 1, 0),
(22, 5, '3', '2-paseo-a-caballo-roquetas.jpg', 'photo', 1, 0),
(23, 5, '4', 'content_banner_balcony_1200x300.jpg', 'photo', 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `pr08T5y_sb_tabbs`
--

CREATE TABLE `pr08T5y_sb_tabbs` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `pr08T5y_sb_tabbs`
--

INSERT INTO `pr08T5y_sb_tabbs` (`id`, `name`, `active`) VALUES
(1, 'Tabbs 1', 1),
(2, 'Tabbs 2', 1),
(3, 'Tabbs 3', 1);

-- --------------------------------------------------------

--
-- Structure de la table `pr08T5y_sb_tabbs_tab`
--

CREATE TABLE `pr08T5y_sb_tabbs_tab` (
  `id` int(11) NOT NULL,
  `tid` int(11) NOT NULL COMMENT 'TABBS id',
  `title` text NOT NULL,
  `content` text NOT NULL,
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `sort` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `pr08T5y_sb_tabbs_tab`
--

INSERT INTO `pr08T5y_sb_tabbs_tab` (`id`, `tid`, `title`, `content`, `active`, `sort`) VALUES
(1, 1, '[fr]Ong 1[/fr]', '[fr]TABBS permet d&amp;#39;afficher des contenus dans des onglets.&lt;br /&gt;\r\nVous pouvez cr&amp;eacute;ez autant d&amp;#39;onglets que vous le souhaitez.&lt;br /&gt;\r\nIls peuvents contenir du texte, du code HTML, des shortcodes.&lt;br /&gt;\r\nLes onglets sont responsives.&lt;br /&gt;\r\nVous pouvez les customiser par CSS.[/fr]', 1, 1),
(2, 1, '[fr]Ong 2[/fr]', '[fr]dfs qfds qfdsqfdsq&lt;br /&gt;\r\n[CS id=25 name=sbnews_item][/fr]', 1, 2),
(3, 2, '[fr]Ong 3[/fr]', '[fr]fdsq fdsqfdq&lt;br /&gt;\r\nfdsqfdsqfdsqfdsq&lt;br /&gt;\r\nfdsq fdsq fdsqf&lt;br /&gt;\r\ndsqfdsqf[/fr]', 1, 0),
(4, 3, '[fr]Ong 4[/fr]', '[fr]uytruty ruyt ruytr uytr&lt;br /&gt;\r\nuytr ytrytruytruytr uyrtu&lt;br /&gt;\r\nytr uytr ytr uytr uyt ruytr[/fr]', 1, 2),
(5, 3, '[fr]Trouth[/fr]', '[fr][CS id=3 name=sbtabbs][/fr]', 1, 1),
(6, 1, '[fr]Tableau 1[/fr]', '[fr][/fr]', 1, 3);

-- --------------------------------------------------------

--
-- Structure de la table `pr08T5y_sb_table`
--

CREATE TABLE `pr08T5y_sb_table` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` varchar(10) NOT NULL COMMENT 'option1, option2, ...',
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `pr08T5y_sb_table`
--

INSERT INTO `pr08T5y_sb_table` (`id`, `name`, `type`, `active`) VALUES
(1, 'Chevaux &agrave; vendre', 'option2', 1);

-- --------------------------------------------------------

--
-- Structure de la table `pr08T5y_sb_table_datas`
--

CREATE TABLE `pr08T5y_sb_table_datas` (
  `id` int(11) NOT NULL,
  `tid` int(11) NOT NULL COMMENT 'Table ID',
  `content` text NOT NULL COMMENT 'Contenus',
  `sort` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `pr08T5y_sb_table_datas`
--

INSERT INTO `pr08T5y_sb_table_datas` (`id`, `tid`, `content`, `sort`) VALUES
(17, 1, '{"0":{"i":"lot","v":"0987ttt"},"1":{"i":"photo","v":"http://localhost/sbmagic_new/upload/adriannadesmottes_.jpg"},"2":{"i":"nom","v":"TOTO DEL CATCHATOREeeee"},"3":{"i":"sexe","v":"femelleeeee"},"4":{"i":"pedigree","v":"http://pedigree.frrrr"},"5":{"i":"video","v":"https://www.youtube.com/watch?v=_pVCS8HbrmI"},"6":{"i":"pere-mere","v":"HREGDSQFESQfssssss"},"7":{"i":"acheteur","v":"Rototooooo"},"8":{"i":"enchere","v":"1.515.377.542&euro;&euro;&euro;&euro;"},"9":{"i":"id","v":"17"}}', 4),
(22, 1, '{"0":{"i":"lot","v":"567A"},"1":{"i":"photo","v":"http://localhost/sbmagic_new/upload/trevise.jpg"},"2":{"i":"nom","v":"Ready CAS&#039;H"},"3":{"i":"sexe","v":"M&acirc;le"},"4":{"i":"pedigree","v":"http://pdf-sample.pdf"},"5":{"i":"video","v":"http://haufor.com/video/unAmourdHaufor/unAmourdHaufor_PxCharlesTiecerlin.mp4"},"6":{"i":"pere-mere","v":"P&egrave;re M&egrave;re P&egrave;re de m&#039;&egrave;re"},"7":{"i":"acheteur","v":"BooB&ocirc;o"},"8":{"i":"enchere","v":"1.515.377.542&euro;"},"9":{"i":"id","v":"22"}}', 2),
(23, 1, '{"0":{"i":"lot","v":"0789"},"1":{"i":"photo","v":"http://localhost/sbmagic_new/upload/1450331624_legumes-cbf-pro.jpg"},"2":{"i":"nom","v":"HHH"},"3":{"i":"sexe","v":"M&acirc;le"},"4":{"i":"pedigree","v":"http://pdf-sample.pdf"},"5":{"i":"video","v":"https://www.youtube.com/watch?v=_pVCS8HbrmI"},"6":{"i":"pere-mere","v":"fdsqfdsq"},"7":{"i":"acheteur","v":"BooB&ocirc;o"},"8":{"i":"enchere","v":"1.270.592&euro;"},"9":{"i":"id","v":"23"}}', 1),
(25, 1, '{"0":{"i":"lot","v":"9874G"},"1":{"i":"photo","v":"http://localhost/sbmagic_new/upload/rubi-ball_-747.jpg"},"2":{"i":"nom","v":"Ready CAS&#039;Hette"},"3":{"i":"sexe","v":"Hongre"},"4":{"i":"pedigree","v":"http://pedigree-cash.fr"},"5":{"i":"video","v":"https://www.youtube.com/watch?v=HjEE145NRyI"},"6":{"i":"pere-mere","v":"P&egrave;re M&#039;&egrave;re&quot;hhehe&quot;"},"7":{"i":"acheteur","v":"El B&ocirc;oBoo"},"8":{"i":"enchere","v":"1.222.876&pound;"},"9":{"i":"id","v":"25"}}', 3);

-- --------------------------------------------------------

--
-- Structure de la table `pr08T5y_sb_table_structure`
--

CREATE TABLE `pr08T5y_sb_table_structure` (
  `id` int(11) NOT NULL,
  `tid` int(11) NOT NULL COMMENT 'Table ID',
  `title` varchar(255) NOT NULL,
  `field_type` varchar(50) NOT NULL COMMENT 'photo,text,date,textarea,textareahtml,link...',
  `field_target` varchar(50) NOT NULL,
  `active` tinyint(4) NOT NULL COMMENT '0: inactive, 1: active',
  `sort` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `pr08T5y_sb_table_structure`
--

INSERT INTO `pr08T5y_sb_table_structure` (`id`, `tid`, `title`, `field_type`, `field_target`, `active`, `sort`) VALUES
(1, 1, 'Lot', 'text', '', 1, 1),
(2, 1, 'Pedigree', 'link_file', 'blank', 1, 5),
(3, 1, 'Photo', 'link_image', 'lightbox', 1, 2),
(4, 1, 'Vid&eacute;o', 'link_video', 'lightbox_fancy', 1, 6),
(5, 1, '[fr]Nom[/fr]', 'text', '', 1, 3),
(6, 1, 'Sexe', 'text', '', 1, 4),
(7, 1, '[fr]P&egrave;re &amp; M&egrave;re[/fr]', 'textarea', '', 1, 7),
(8, 1, '[fr]Acheteur[/fr]', 'text', '', 1, 8),
(9, 1, '[fr]Ench&egrave;re[/fr]', 'text', '', 1, 9);

-- --------------------------------------------------------

--
-- Structure de la table `pr08T5y_sb_users`
--

CREATE TABLE `pr08T5y_sb_users` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(128) NOT NULL,
  `email` varchar(100) NOT NULL,
  `logintime` int(11) NOT NULL,
  `lastlogin` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `activekey` varchar(15) NOT NULL DEFAULT '0',
  `resetkey` varchar(15) NOT NULL DEFAULT '0',
  `menu` text NOT NULL COMMENT 'Liste du menu inaccessible ( séparé par des | )',
  `groupe` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Table Users';

--
-- Contenu de la table `pr08T5y_sb_users`
--

INSERT INTO `pr08T5y_sb_users` (`id`, `username`, `password`, `email`, `logintime`, `lastlogin`, `active`, `activekey`, `resetkey`, `menu`, `groupe`) VALUES
(1, 'admin', '99pxlOJyAWx1UswLgblCziBfdWeREDCTYlWP2RWWkAc=', 'admin-reply@votresite.com', 0, 0, 1, '0', '0', '', ''),
(2, 'patrice', 'gTkVSlmQqnQP1hKXXc+y4DwUwZ7z3XqQR8XJIr0skq8=', 'patrice@dollar.fr', 1482241255, 1481534000, 1, '0', '0', '', ''),
(3, 'jerome', 'gTkVSlmQqnQP1hKXXc+y4DwUwZ7z3XqQR8XJIr0skq8=', 'jerome@dollar.fr', 1481534181, 0, 1, '0', '0', 'medias|cmsconfig|toto', '');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `pr08T5y_sb_blocs`
--
ALTER TABLE `pr08T5y_sb_blocs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pr08T5y_sb_blocs_sort`
--
ALTER TABLE `pr08T5y_sb_blocs_sort`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pr08T5y_sb_config`
--
ALTER TABLE `pr08T5y_sb_config`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `config` (`config`),
  ADD KEY `config_2` (`config`);

--
-- Index pour la table `pr08T5y_sb_contact`
--
ALTER TABLE `pr08T5y_sb_contact`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pr08T5y_sb_country`
--
ALTER TABLE `pr08T5y_sb_country`
  ADD PRIMARY KEY (`country_iso`);

--
-- Index pour la table `pr08T5y_sb_effectives`
--
ALTER TABLE `pr08T5y_sb_effectives`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pr08T5y_sb_effectives_category`
--
ALTER TABLE `pr08T5y_sb_effectives_category`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pr08T5y_sb_effectives_medias`
--
ALTER TABLE `pr08T5y_sb_effectives_medias`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pr08T5y_sb_effectives_production`
--
ALTER TABLE `pr08T5y_sb_effectives_production`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pr08T5y_sb_effectives_settings`
--
ALTER TABLE `pr08T5y_sb_effectives_settings`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pr08T5y_sb_flood`
--
ALTER TABLE `pr08T5y_sb_flood`
  ADD PRIMARY KEY (`ip`);

--
-- Index pour la table `pr08T5y_sb_graduates`
--
ALTER TABLE `pr08T5y_sb_graduates`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pr08T5y_sb_graduates_category`
--
ALTER TABLE `pr08T5y_sb_graduates_category`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pr08T5y_sb_graduates_settings`
--
ALTER TABLE `pr08T5y_sb_graduates_settings`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pr08T5y_sb_logaccess`
--
ALTER TABLE `pr08T5y_sb_logaccess`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pr08T5y_sb_menu`
--
ALTER TABLE `pr08T5y_sb_menu`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pr08T5y_sb_news`
--
ALTER TABLE `pr08T5y_sb_news`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pr08T5y_sb_news_category`
--
ALTER TABLE `pr08T5y_sb_news_category`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pr08T5y_sb_news_settings`
--
ALTER TABLE `pr08T5y_sb_news_settings`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pr08T5y_sb_pages`
--
ALTER TABLE `pr08T5y_sb_pages`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pr08T5y_sb_sandbox`
--
ALTER TABLE `pr08T5y_sb_sandbox`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pr08T5y_sb_sessions`
--
ALTER TABLE `pr08T5y_sb_sessions`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pr08T5y_sb_slider`
--
ALTER TABLE `pr08T5y_sb_slider`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pr08T5y_sb_slider_photos`
--
ALTER TABLE `pr08T5y_sb_slider_photos`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pr08T5y_sb_tabbs`
--
ALTER TABLE `pr08T5y_sb_tabbs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pr08T5y_sb_tabbs_tab`
--
ALTER TABLE `pr08T5y_sb_tabbs_tab`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pr08T5y_sb_table`
--
ALTER TABLE `pr08T5y_sb_table`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pr08T5y_sb_table_datas`
--
ALTER TABLE `pr08T5y_sb_table_datas`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pr08T5y_sb_table_structure`
--
ALTER TABLE `pr08T5y_sb_table_structure`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pr08T5y_sb_users`
--
ALTER TABLE `pr08T5y_sb_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `pr08T5y_sb_blocs`
--
ALTER TABLE `pr08T5y_sb_blocs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `pr08T5y_sb_blocs_sort`
--
ALTER TABLE `pr08T5y_sb_blocs_sort`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=68;
--
-- AUTO_INCREMENT pour la table `pr08T5y_sb_config`
--
ALTER TABLE `pr08T5y_sb_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=38;
--
-- AUTO_INCREMENT pour la table `pr08T5y_sb_contact`
--
ALTER TABLE `pr08T5y_sb_contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `pr08T5y_sb_effectives`
--
ALTER TABLE `pr08T5y_sb_effectives`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `pr08T5y_sb_effectives_category`
--
ALTER TABLE `pr08T5y_sb_effectives_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `pr08T5y_sb_effectives_medias`
--
ALTER TABLE `pr08T5y_sb_effectives_medias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT pour la table `pr08T5y_sb_effectives_production`
--
ALTER TABLE `pr08T5y_sb_effectives_production`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT pour la table `pr08T5y_sb_effectives_settings`
--
ALTER TABLE `pr08T5y_sb_effectives_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `pr08T5y_sb_graduates`
--
ALTER TABLE `pr08T5y_sb_graduates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT pour la table `pr08T5y_sb_graduates_category`
--
ALTER TABLE `pr08T5y_sb_graduates_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `pr08T5y_sb_graduates_settings`
--
ALTER TABLE `pr08T5y_sb_graduates_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `pr08T5y_sb_logaccess`
--
ALTER TABLE `pr08T5y_sb_logaccess`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=72;
--
-- AUTO_INCREMENT pour la table `pr08T5y_sb_menu`
--
ALTER TABLE `pr08T5y_sb_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `pr08T5y_sb_news`
--
ALTER TABLE `pr08T5y_sb_news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT pour la table `pr08T5y_sb_news_category`
--
ALTER TABLE `pr08T5y_sb_news_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `pr08T5y_sb_news_settings`
--
ALTER TABLE `pr08T5y_sb_news_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `pr08T5y_sb_pages`
--
ALTER TABLE `pr08T5y_sb_pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT pour la table `pr08T5y_sb_sandbox`
--
ALTER TABLE `pr08T5y_sb_sandbox`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT pour la table `pr08T5y_sb_sessions`
--
ALTER TABLE `pr08T5y_sb_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `pr08T5y_sb_slider`
--
ALTER TABLE `pr08T5y_sb_slider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT pour la table `pr08T5y_sb_slider_photos`
--
ALTER TABLE `pr08T5y_sb_slider_photos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT pour la table `pr08T5y_sb_tabbs`
--
ALTER TABLE `pr08T5y_sb_tabbs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `pr08T5y_sb_tabbs_tab`
--
ALTER TABLE `pr08T5y_sb_tabbs_tab`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT pour la table `pr08T5y_sb_table`
--
ALTER TABLE `pr08T5y_sb_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `pr08T5y_sb_table_datas`
--
ALTER TABLE `pr08T5y_sb_table_datas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT pour la table `pr08T5y_sb_table_structure`
--
ALTER TABLE `pr08T5y_sb_table_structure`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT pour la table `pr08T5y_sb_users`
--
ALTER TABLE `pr08T5y_sb_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
