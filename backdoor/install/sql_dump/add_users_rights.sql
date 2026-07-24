-- --------------------------------------------------------
-- Droits granulaires par utilisateur - à exécuter une seule fois sur
-- une installation existante qui n'a pas encore cette table (les
-- nouvelles installations la créent automatiquement).
-- --------------------------------------------------------

--
-- Table structure for table `sb_users_rights`
--

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
-- Indexes
--

ALTER TABLE `<DB_PREFIX>sb_users_rights`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_module` (`user_id`,`module`);

--
-- AUTO_INCREMENT
--

ALTER TABLE `<DB_PREFIX>sb_users_rights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
