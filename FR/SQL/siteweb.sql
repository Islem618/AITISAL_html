-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 06 juin 2025 à 13:22
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `siteweb`
--

-- --------------------------------------------------------

--
-- Structure de la table `conversation`
--

CREATE TABLE `conversation` (
                                `id` int(11) NOT NULL,
                                `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `conversation`
--

INSERT INTO `conversation` (`id`, `created_at`) VALUES
                                                    (1, '2025-05-31 16:36:12'),
                                                    (2, '2025-05-31 16:36:24'),
                                                    (3, '2025-05-31 16:37:18'),
                                                    (4, '2025-05-31 16:37:21'),
                                                    (5, '2025-05-31 16:37:22'),
                                                    (6, '2025-05-31 16:37:30'),
                                                    (7, '2025-05-31 16:37:37'),
                                                    (8, '2025-05-31 17:59:47'),
                                                    (9, '2025-05-31 19:41:44'),
                                                    (10, '2025-05-31 19:41:49'),
                                                    (11, '2025-05-31 19:41:50'),
                                                    (12, '2025-05-31 19:42:02'),
                                                    (13, '2025-05-31 19:42:03'),
                                                    (14, '2025-05-31 19:42:11'),
                                                    (15, '2025-05-31 19:42:28'),
                                                    (16, '2025-05-31 19:43:39'),
                                                    (17, '2025-05-31 19:43:45'),
                                                    (18, '2025-05-31 19:43:45'),
                                                    (19, '2025-05-31 19:43:47'),
                                                    (20, '2025-06-01 15:59:23');

-- --------------------------------------------------------

--
-- Structure de la table `conversation_user`
--

CREATE TABLE `conversation_user` (
                                     `conversation_id` int(11) NOT NULL,
                                     `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `conversation_user`
--

INSERT INTO `conversation_user` (`conversation_id`, `user_id`) VALUES
                                                                   (1, 32),
                                                                   (1, 33),
                                                                   (2, 32),
                                                                   (2, 34),
                                                                   (3, 32),
                                                                   (3, 34),
                                                                   (4, 31),
                                                                   (4, 34),
                                                                   (5, 33),
                                                                   (5, 34),
                                                                   (6, 32),
                                                                   (6, 34),
                                                                   (7, 31),
                                                                   (7, 32),
                                                                   (8, 33),
                                                                   (8, 34),
                                                                   (9, 32),
                                                                   (9, 35),
                                                                   (10, 33),
                                                                   (10, 35),
                                                                   (11, 34),
                                                                   (11, 35),
                                                                   (12, 35),
                                                                   (12, 37),
                                                                   (13, 35),
                                                                   (13, 36),
                                                                   (14, 31),
                                                                   (14, 35),
                                                                   (15, 32),
                                                                   (15, 36),
                                                                   (16, 33),
                                                                   (16, 37),
                                                                   (17, 31),
                                                                   (17, 37),
                                                                   (18, 32),
                                                                   (18, 37),
                                                                   (19, 36),
                                                                   (19, 37),
                                                                   (20, 34),
                                                                   (20, 37);

-- --------------------------------------------------------

--
-- Structure de la table `friendship`
--

CREATE TABLE `friendship` (
                              `id` int(11) NOT NULL,
                              `from_id` int(11) NOT NULL,
                              `to_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `friendship`
--

INSERT INTO `friendship` (`id`, `from_id`, `to_id`) VALUES
                                                        (1, 34, 31),
                                                        (2, 32, 33),
                                                        (3, 32, 33),
                                                        (4, 32, 33),
                                                        (5, 32, 34),
                                                        (6, 32, 31),
                                                        (7, 32, 33),
                                                        (8, 32, 34),
                                                        (9, 32, 34),
                                                        (10, 34, 33),
                                                        (11, 35, 32),
                                                        (12, 35, 33),
                                                        (13, 35, 34),
                                                        (14, 35, 37),
                                                        (15, 35, 36),
                                                        (16, 35, 31),
                                                        (17, 32, 36),
                                                        (18, 37, 33),
                                                        (19, 37, 31),
                                                        (20, 37, 32),
                                                        (21, 37, 36),
                                                        (22, 34, 37);

-- --------------------------------------------------------

--
-- Structure de la table `interest`
--

CREATE TABLE `interest` (
                            `id` int(11) NOT NULL,
                            `label` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `interest`
--

INSERT INTO `interest` (`id`, `label`) VALUES
                                           (4, 'Cinéma'),
                                           (7, 'Cook'),
                                           (8, 'Gaming'),
                                           (2, 'Music'),
                                           (3, 'Programming'),
                                           (5, 'Reading'),
                                           (1, 'Sport'),
                                           (6, 'Travel');

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

CREATE TABLE `message` (
                           `id` int(11) NOT NULL,
                           `conversation_id` int(11) NOT NULL,
                           `from_id` int(11) NOT NULL,
                           `content` text NOT NULL,
                           `created_at` datetime DEFAULT current_timestamp(),
                           `media_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `message`
--

INSERT INTO `message` (`id`, `conversation_id`, `from_id`, `content`, `created_at`, `media_path`) VALUES
                                                                                                      (1, 1, 32, 'Harry ?', '2025-05-31 16:36:18', NULL),
                                                                                                      (2, 2, 32, 'Michel ?', '2025-05-31 16:36:29', NULL),
                                                                                                      (4, 7, 32, 'Coucou Kelyan', '2025-05-31 17:44:26', NULL),
                                                                                                      (5, 2, 34, 'Islam ?', '2025-05-31 18:00:06', NULL),
                                                                                                      (6, 2, 32, 'Le chat privé fonctionne ?', '2025-05-31 18:00:18', NULL),
                                                                                                      (7, 2, 34, 'SUPER !', '2025-05-31 18:00:33', NULL),
                                                                                                      (10, 2, 34, 'test notifications !', '2025-06-04 21:01:02', NULL),
                                                                                                      (11, 2, 34, '2eme test', '2025-06-04 21:01:36', NULL),
                                                                                                      (12, 2, 34, 'test notifications plus poussées ?', '2025-06-04 21:14:41', NULL),
                                                                                                      (13, 5, 34, 'Bonjour Harry', '2025-06-04 21:50:08', NULL),
                                                                                                      (14, 3, 34, 'Islam ?', '2025-06-04 21:53:58', NULL),
                                                                                                      (15, 3, 34, 'Islam 2 ?', '2025-06-04 21:59:34', NULL),
                                                                                                      (16, 3, 34, 'Islam 3?', '2025-06-04 22:06:21', NULL),
                                                                                                      (17, 3, 34, 'notification', '2025-06-04 22:18:48', NULL),
                                                                                                      (18, 3, 34, 'esqd', '2025-06-04 22:21:31', NULL),
                                                                                                      (19, 3, 34, 'ezqsdf', '2025-06-04 22:38:11', NULL),
                                                                                                      (20, 3, 34, 'zqdqd', '2025-06-04 22:38:40', NULL),
                                                                                                      (21, 3, 34, 'qzdqd', '2025-06-04 22:38:41', NULL),
                                                                                                      (22, 3, 34, 'qzdqzdqd', '2025-06-04 22:38:42', NULL),
                                                                                                      (23, 3, 34, 'qzdqdz', '2025-06-04 22:38:43', NULL),
                                                                                                      (24, 3, 34, 'qzdqdqd', '2025-06-04 22:38:48', NULL),
                                                                                                      (25, 3, 34, 'qzdqzdq', '2025-06-04 22:38:49', NULL),
                                                                                                      (26, 3, 34, 'qsefdsqdfqfdq', '2025-06-05 00:06:12', NULL),
                                                                                                      (27, 3, 34, 'drg', '2025-06-05 00:06:50', NULL),
                                                                                                      (28, 3, 34, 'drggd', '2025-06-05 00:06:51', NULL),
                                                                                                      (29, 3, 34, 'dgdrg', '2025-06-05 00:06:52', NULL),
                                                                                                      (30, 3, 34, 'dgdrgd', '2025-06-05 00:06:53', NULL),
                                                                                                      (31, 3, 34, 'drgdrgd', '2025-06-05 00:06:55', NULL),
                                                                                                      (32, 3, 34, 'dgdrgd', '2025-06-05 00:06:57', NULL),
                                                                                                      (33, 3, 34, 'drgrdgrd', '2025-06-05 00:06:58', NULL),
                                                                                                      (34, 3, 34, 'sefsfsf', '2025-06-05 00:34:55', NULL),
                                                                                                      (35, 3, 34, 'sfesefs', '2025-06-05 00:34:56', NULL),
                                                                                                      (36, 3, 34, 'sefsfdsf', '2025-06-05 00:34:59', NULL),
                                                                                                      (37, 3, 34, 'sefsdfsf', '2025-06-05 00:35:01', NULL),
                                                                                                      (38, 3, 34, 'sefsffsdf', '2025-06-05 00:35:05', NULL),
                                                                                                      (39, 3, 34, 'qzdqds', '2025-06-05 00:42:22', NULL),
                                                                                                      (40, 3, 34, 'qzddqd', '2025-06-05 00:42:23', NULL),
                                                                                                      (41, 3, 34, 'qzdqdqs', '2025-06-05 00:44:12', NULL),
                                                                                                      (42, 3, 34, 'qzdqdsd', '2025-06-05 00:44:14', NULL),
                                                                                                      (43, 3, 34, 'drfgggf', '2025-06-05 00:45:11', NULL),
                                                                                                      (44, 3, 34, 'dfgdfgd', '2025-06-05 00:45:14', NULL),
                                                                                                      (45, 3, 34, 'qdzqzdqzdfqf', '2025-06-05 00:45:31', NULL),
                                                                                                      (46, 3, 34, 'qzdqzfdfzd', '2025-06-05 00:45:33', NULL),
                                                                                                      (47, 3, 34, 'qzrqdzsdq', '2025-06-05 00:47:03', NULL),
                                                                                                      (48, 3, 34, 'qzddqzfdqd', '2025-06-05 00:47:06', NULL),
                                                                                                      (49, 3, 34, 'qzdqdqdq', '2025-06-05 00:50:55', NULL),
                                                                                                      (50, 3, 34, 'qzdqdqddqd', '2025-06-05 00:50:57', NULL),
                                                                                                      (51, 3, 34, 'qzdqdzqdq', '2025-06-05 00:50:59', NULL),
                                                                                                      (52, 18, 37, 'sefezsfrezsg', '2025-06-05 15:15:38', NULL),
                                                                                                      (53, 18, 32, 'sefsf', '2025-06-05 15:34:31', NULL),
                                                                                                      (63, 18, 37, 'coucou', '2025-06-06 01:36:28', 'uploads/messages/msg_37_1749166588.png');

-- --------------------------------------------------------

--
-- Structure de la table `notification`
--

CREATE TABLE `notification` (
                                `id` int(11) NOT NULL,
                                `recipient_user_id` int(11) NOT NULL,
                                `type` enum('new_message','new_friend_request','friend_accepted','interest_match','new_public_post') NOT NULL,
                                `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`payload`)),
                                `is_read` tinyint(1) DEFAULT 0,
                                `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `post`
--

CREATE TABLE `post` (
                        `id` int(11) NOT NULL,
                        `author_id` int(11) NOT NULL,
                        `content` text NOT NULL,
                        `created_at` datetime DEFAULT current_timestamp(),
                        `media_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `post`
--

INSERT INTO `post` (`id`, `author_id`, `content`, `created_at`, `media_path`) VALUES
                                                                                  (1, 32, 'qzdqsd', '2025-05-21 23:04:37', NULL),
                                                                                  (2, 34, 'C\'est moi Michel !!!', '2025-05-21 23:22:53', NULL),
(3, 32, 'Mais y a Harry-Potter aussi ?', '2025-05-21 23:53:51', NULL),
(4, 34, 'On dirait bien que si !!!', '2025-05-21 23:54:17', NULL),
(5, 32, 'Michel je test un nouvelle version du code !', '2025-05-30 02:01:35', NULL),
(6, 34, 'Ehh je vois ton message s\'afficher en temps réel sans que j\'ai besoin de recharger !?', '2025-05-30 02:02:00', NULL),
(7, 32, 'Exactement ce que je voulais implémenter, super !', '2025-05-30 02:02:16', NULL),
(8, 34, 'test', '2025-05-31 01:43:24', NULL),
(9, 37, 'test pdp', '2025-06-05 14:56:31', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id_User` int(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `telephone` int(255) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `ville` varchar(255) NOT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `last_notif_checked` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `photo_profil` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id_User`, `prenom`, `nom`, `email`, `password`, `telephone`, `adresse`, `ville`, `photo_path`, `last_notif_checked`, `photo_profil`) VALUES
(31, 'kelyan', 'taverny', 'kelyan@gmail.com', '$2y$10$C/dymLKb.C.BaQ3TwnCEveFdkzEU/EufaBRj8ksppJCeox9g1bvUW', 0, '', '', NULL, '1970-01-01 00:00:00', NULL),
(32, 'Islam', 'AIT-SLIMANE', 'islam61886@gmail.com', '$2y$10$sT7.dsA4hJC.4pwFjTvm1OGWBgabkI0z.1FhwFMOF7s49b/5v2/x6', 0, '24 rue Jean Jacques Rousseau', 'Issy-Les-Moulineaux', 'uploads/photos/profil_32.jpg', '2025-06-06 13:22:00', NULL),
(33, 'Harry', 'Potter', 'patmoleetcornedrue@gmail.com', '$2y$10$K3W4cu3ffflulu5yECiK9u.PG6HJkWa3HE69X.9ar82W/it8zpOYC', 0, '', '', NULL, '1970-01-01 00:00:00', NULL),
(34, 'Michel', 'Aulas', 'thegamer0092130@gmail.com', '$2y$10$DQz3/93LiaDgjdBOt0u6O.OLNTqNMNjGoOMmor6Zz39y6mugUyiYi', 0, '', '', 'uploads/photos/profil_34.png', '2025-06-05 14:10:17', NULL),
(35, 'Manel', 'BEN NASR', 'isas61270@eleve.isep.fr', '$2y$10$y4Et8NSqja5M5d09zmbg4OjvBsXw7TE62Zg1LX5DigJIToe5g6uqq', 0, '', '', NULL, '1970-01-01 00:00:00', NULL),
(36, 'Ron', 'Weasley', 'raapp2002@gmail.com', '$2y$10$/B3/Lk2J7Hletl.VdE1rq.gVt6zUSNFRhI21enkhxg/BkzCFtg/NG', 0, '', '', NULL, '1970-01-01 00:00:00', NULL),
(37, 'Thomas', 'Perrier', 'thomasperrier3@gmail.com', '$2y$10$qbECNp6Z3WcEVjETW5Xho.0yA0qakQjjXkNQWepDdk/os95coYk2i', 0, '', '', 'uploads/photos/profil_37.png', '2025-06-06 01:37:29', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `user_interest`
--

CREATE TABLE `user_interest` (
  `user_id` int(11) NOT NULL,
  `interest_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user_interest`
--

INSERT INTO `user_interest` (`user_id`, `interest_id`, `created_at`) VALUES
(32, 1, '2025-06-06 00:18:34'),
(32, 2, '2025-06-06 00:18:34'),
(32, 4, '2025-06-06 00:18:34'),
(32, 5, '2025-06-06 00:18:34'),
(32, 6, '2025-06-06 00:18:34'),
(32, 8, '2025-06-06 00:18:34'),
(34, 2, '2025-06-04 18:25:19'),
(34, 4, '2025-06-04 18:25:19'),
(34, 5, '2025-06-04 18:25:19'),
(34, 8, '2025-06-04 18:25:19'),
(37, 2, '2025-06-04 18:25:19'),
(37, 4, '2025-06-04 18:25:19'),
(37, 8, '2025-06-04 18:25:19');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `conversation`
--
ALTER TABLE `conversation`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `conversation_user`
--
ALTER TABLE `conversation_user`
  ADD PRIMARY KEY (`conversation_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `friendship`
--
ALTER TABLE `friendship`
  ADD PRIMARY KEY (`id`),
  ADD KEY `from_id` (`from_id`),
  ADD KEY `to_id` (`to_id`);

--
-- Index pour la table `interest`
--
ALTER TABLE `interest`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `label` (`label`);

--
-- Index pour la table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conversation_id` (`conversation_id`),
  ADD KEY `from_id` (`from_id`);

--
-- Index pour la table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recipient_user_id` (`recipient_user_id`);

--
-- Index pour la table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author_id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_User`);

--
-- Index pour la table `user_interest`
--
ALTER TABLE `user_interest`
  ADD PRIMARY KEY (`user_id`,`interest_id`),
  ADD KEY `interest_id` (`interest_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `conversation`
--
ALTER TABLE `conversation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `friendship`
--
ALTER TABLE `friendship`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pour la table `interest`
--
ALTER TABLE `interest`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT pour la table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `post`
--
ALTER TABLE `post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id_User` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `conversation_user`
--
ALTER TABLE `conversation_user`
  ADD CONSTRAINT `conversation_user_ibfk_1` FOREIGN KEY (`conversation_id`) REFERENCES `conversation` (`id`),
  ADD CONSTRAINT `conversation_user_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id_User`);

--
-- Contraintes pour la table `friendship`
--
ALTER TABLE `friendship`
  ADD CONSTRAINT `friendship_ibfk_1` FOREIGN KEY (`from_id`) REFERENCES `user` (`id_User`),
  ADD CONSTRAINT `friendship_ibfk_2` FOREIGN KEY (`to_id`) REFERENCES `user` (`id_User`);

--
-- Contraintes pour la table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`conversation_id`) REFERENCES `conversation` (`id`),
  ADD CONSTRAINT `message_ibfk_2` FOREIGN KEY (`from_id`) REFERENCES `user` (`id_User`);

--
-- Contraintes pour la table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `notification_ibfk_1` FOREIGN KEY (`recipient_user_id`) REFERENCES `user` (`id_User`);

--
-- Contraintes pour la table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `user` (`id_User`);

--
-- Contraintes pour la table `user_interest`
--
ALTER TABLE `user_interest`
  ADD CONSTRAINT `user_interest_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id_User`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_interest_ibfk_2` FOREIGN KEY (`interest_id`) REFERENCES `interest` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;