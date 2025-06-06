-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 06 juin 2025 à 07:55
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `authpage`
--

-- --------------------------------------------------------

--
-- Structure de la table `logs`
--

DROP TABLE IF EXISTS `logs`;
CREATE TABLE IF NOT EXISTS `logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `role` varchar(50) NOT NULL,
  `action` text NOT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `logs`
--

INSERT INTO `logs` (`id`, `role`, `action`, `date`) VALUES
(1, 'utilisateur', 'Connexion', '2025-05-28 07:34:18'),
(2, 'utilisateur', 'Déconnexion', '2025-05-28 07:34:18'),
(3, 'admin', 'Suppression d’un utilisateur', '2025-05-28 07:34:18');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `login` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('admin','utilisateur') NOT NULL DEFAULT 'utilisateur',
  `email` varchar(255) NOT NULL,
  `couleur` varchar(7) DEFAULT '#00ffff',
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `login`, `mot_de_passe`, `role`, `email`, `couleur`) VALUES
(1, 'admin', '$2y$10$3oU5czLG1Up1GXlM/YaYK.84cE0II3VPBW2KyxT0LEFmYWpmOijOu', 'admin', 'mukilan@gmail.com', '#00ffff'),
(2, 'Mukitest', '$2y$10$bLoCPVwhAkgGpHlPgSj8hONiAQdJGOnDesbvtunEy2efAVd7OyETy', 'utilisateur', 'test@gmail.com', '#00ffff'),
(3, 'test', '$2y$10$iBc8cEXrpiBdE/trvMbuce1nphzc/99hqQxI081WeiOimKWWsqbYa', 'admin', 'yu@gmail.com', '#8aa8a8'),
(4, 'susi', '$2y$10$BQRS0IQ4hzkhKmWLPrrhluO3mvzxwx/UbTjBbje1Nvxc9jd1LaJLu', 'utilisateur', 'susi@gmail.com', '#f50000');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
