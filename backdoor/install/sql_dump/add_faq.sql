-- --------------------------------------------------------
-- Module FAQ - à exécuter une seule fois sur une installation
-- existante qui n'a pas encore ces deux tables (les nouvelles
-- installations les créent automatiquement).
-- --------------------------------------------------------

--
-- Table structure for table `sb_faq`
--

CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_faq` (
  `id` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `question` varchar(255) NOT NULL,
  `response` text NOT NULL,
  `sort` int(11) NOT NULL DEFAULT 0,
  `active` tinyint(4) NOT NULL DEFAULT 1 COMMENT '0: inactive, 1: active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `sb_faq_category`
--

CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sb_faq_category` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT 1 COMMENT '0: inactive, 1: active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes
--

ALTER TABLE `<DB_PREFIX>sb_faq`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `<DB_PREFIX>sb_faq_category`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT
--

ALTER TABLE `<DB_PREFIX>sb_faq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `<DB_PREFIX>sb_faq_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
