-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
<<<<<<< Updated upstream
-- Généré le : dim. 14 jan. 2024 à 00:10
=======
-- Généré le : mer. 17 jan. 2024 à 16:03
>>>>>>> Stashed changes
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
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id_User` int(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id_User`, `prenom`, `nom`, `email`, `password`) VALUES
(1, 'kelyan', 'taverny', 'kelyan@gmail.com', 'kelyantaverny'),
(2, '', '', 'marie@gmail.com', 'mariemarie'),
(3, '', '', 'pierre@gmail.com', 'pierre'),
<<<<<<< Updated upstream
(4, 'Islam', 'AIT-SLIMANE', 'islam61886@gmail.com', 'azertyui');
=======
(5, 'Islam', 'AIT-SLIMANE', 'islam61886@gmail.com', '$2y$10$9fREBhPAKzHgBHNhXYbdl.aR5cOK1msmJPQP/cJUMHCMw0i3spNpW');
>>>>>>> Stashed changes

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_User`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
<<<<<<< Updated upstream
  MODIFY `id_User` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
=======
  MODIFY `id_User` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
>>>>>>> Stashed changes
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
