-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 15 juil. 2025 à 15:00
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `vote_app`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id_admin` int NOT NULL,
  `nom_admin` varchar(100) NOT NULL,
  `email_admin` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  PRIMARY KEY (`id_admin`),
  UNIQUE KEY `email_admin` (`email_admin`)
) ;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`id_admin`, `nom_admin`, `email_admin`, `mot_de_passe`) VALUES
(1, 'RASOANAIVO Jean', 'rasoanaivojean@gmail.com', '$2y$10$ROVP8rkcj8MSS9SHNxq3EOKzzZqPIbM1.MxyhBNeitQe8XO2UV2fC');

-- --------------------------------------------------------

--
-- Structure de la table `candidatures`
--

DROP TABLE IF EXISTS `candidatures`;
CREATE TABLE IF NOT EXISTS `candidatures` (
  `id_candidature` int NOT NULL AUTO_INCREMENT,
  `id_etudiant` int DEFAULT NULL,
  `id_scrutin` int DEFAULT NULL,
  `date_depot` datetime DEFAULT CURRENT_TIMESTAMP,
  `statut_validation` enum('en attente','validée','rejetée') DEFAULT 'en attente',
  `slogan_candidat` text NOT NULL,
  `programme_candidat` text NOT NULL,
  `photo_candidat` text NOT NULL,
  `id_admin` int DEFAULT NULL,
  PRIMARY KEY (`id_candidature`),
  KEY `fk_candidature_etudiant` (`id_etudiant`),
  KEY `fk_candidature_scrutin` (`id_scrutin`),
  KEY `fk_candidature_admin` (`id_admin`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `candidatures`
--

INSERT INTO `candidatures` (`id_candidature`, `id_etudiant`, `id_scrutin`, `date_depot`, `statut_validation`, `slogan_candidat`, `programme_candidat`, `photo_candidat`, `id_admin`) VALUES
(3, 6, 6, '2025-07-15 17:21:48', 'rejetée', 'GGG', 'RRRR', 'photo_687663fc76d1b6.08892428.png', 1);

-- --------------------------------------------------------

--
-- Structure de la table `etudiants`
--

DROP TABLE IF EXISTS `etudiants`;
CREATE TABLE IF NOT EXISTS `etudiants` (
  `id_etudiant` int NOT NULL AUTO_INCREMENT,
  `nom_etudiant` varchar(100) NOT NULL,
  `prenom_etudiant` varchar(100) NOT NULL,
  `nie_etudiant` varchar(12) NOT NULL,
  `email_etudiant` varchar(200) NOT NULL,
  `classe_etudiant` varchar(30) NOT NULL,
  `mdp_etudiant` varchar(200) NOT NULL,
  `date_inscription` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_etudiant`),
  UNIQUE KEY `nie_etudiant` (`nie_etudiant`),
  UNIQUE KEY `email_etudiant` (`email_etudiant`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `etudiants`
--

INSERT INTO `etudiants` (`id_etudiant`, `nom_etudiant`, `prenom_etudiant`, `nie_etudiant`, `email_etudiant`, `classe_etudiant`, `mdp_etudiant`, `date_inscription`) VALUES
(1, 'Luck', 'Georgi', 'SE20234433', 'ferdinomax@gmail.com', 'L2 IRD', '$2y$10$Yce9LJn7AP/s3zxz7mwzb.Zx7C1./qLRm9YLckoPSoZ6L5tdAa6QK', '2025-07-12 16:29:31'),
(2, 'RAZAFINIRINA', 'Maximin Ferdino', 'SE20240352', 'ferdinomax1@gmail.com', 'L1 SIO1', '$2y$10$o08pJ8tKjyvcVccvE6BR8ejxjixVlGa2T8ruXIiKsTfY7m16A/x/O', '2025-07-12 16:31:09'),
(3, 'RASOANDRAINY', 'Julie', 'SE20230066', 'juliera@gmail.com', 'L1 MEGP', '$2y$10$hXhk./YQ4riVkTT5L292XOmtcPhV80H21zkiIpSxrq4G6c/Nx00b.', '2025-07-12 16:33:27'),
(4, 'RANDRIA', 'Naivo', 'SE20220005', 'naivo@gmail.com', 'L2 IRD', '$2y$10$MjCWIxALkkXOSJQ4pOxuB.nVrE7LH1ppu8QDhSLSGFVUJkmlrOp9W', '2025-07-15 09:00:49'),
(5, 'RAMAROSON', 'Lydio Charnel', 'SE20240005', 'lydiocharnel@gmail.com', 'L1 SIO1', '$2y$10$EBcQcCRsERWCV95s8KmspOB5EcoCuWZ8F.rShwwA.wVK9E8tgMMAe', '2025-07-15 13:23:59'),
(6, 'RASOARINIVO', 'Léantice', 'SE20240002', 'leantice@gmail.com', 'L1 SIO2', '$2y$10$1Y8lh1LHqpNb94zqv6nI.uiSdTSkDklRaIX1d2a6bUUBE8RXD9eEy', '2025-07-15 13:40:42');

-- --------------------------------------------------------

--
-- Structure de la table `scrutins`
--

DROP TABLE IF EXISTS `scrutins`;
CREATE TABLE IF NOT EXISTS `scrutins` (
  `id_scrutin` int NOT NULL AUTO_INCREMENT,
  `intitule_scrutin` text NOT NULL,
  `classe_concernee` varchar(50) NOT NULL,
  `date_heure_debut` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_heure_fin` datetime NOT NULL,
  `nb_etudiants_inscrits` int DEFAULT '0',
  `nb_participants` int DEFAULT '0',
  `taux_participation` decimal(5,2) DEFAULT '0.00',
  `id_admin` int DEFAULT NULL,
  PRIMARY KEY (`id_scrutin`),
  KEY `fk_scrutin_admin` (`id_admin`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `scrutins`
--

INSERT INTO `scrutins` (`id_scrutin`, `intitule_scrutin`, `classe_concernee`, `date_heure_debut`, `date_heure_fin`, `nb_etudiants_inscrits`, `nb_participants`, `taux_participation`, `id_admin`) VALUES
(6, 'Election de délégué', 'L1 SIO2', '2025-07-15 17:20:00', '2025-07-16 17:20:00', 0, 0, 0.00, 1);

-- --------------------------------------------------------

--
-- Structure de la table `vote`
--

DROP TABLE IF EXISTS `vote`;
CREATE TABLE IF NOT EXISTS `vote` (
  `id_vote` int NOT NULL AUTO_INCREMENT,
  `id_scrutin` int DEFAULT NULL,
  `id_electeur` int DEFAULT NULL,
  `id_candidat` int DEFAULT NULL,
  `date_vote` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_vote`),
  UNIQUE KEY `id_scrutin` (`id_scrutin`,`id_electeur`),
  KEY `fk_vote_electeur` (`id_electeur`),
  KEY `fk_vote_candidat` (`id_candidat`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
