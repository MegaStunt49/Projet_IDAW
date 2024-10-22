-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 22 oct. 2024 à 12:09
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
-- Base de données : `projet`
--

-- --------------------------------------------------------

--
-- Structure de la table `aliment`
--

DROP TABLE IF EXISTS `aliment`;
CREATE TABLE IF NOT EXISTS `aliment` (
  `ID_ALIMENT` int NOT NULL AUTO_INCREMENT,
  `ID_TYPE_ALIMENT` int NOT NULL,
  `LIBELLE` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`ID_ALIMENT`),
  KEY `FK_A_POUR_TYPE` (`ID_TYPE_ALIMENT`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `aliment`
--

INSERT INTO `aliment` (`ID_ALIMENT`, `ID_TYPE_ALIMENT`, `LIBELLE`) VALUES
(1, 1, 'Tomate'),
(2, 1, 'Courgette'),
(3, 1, 'Aubergine'),
(4, 3, 'Ratatouille'),
(5, 1, 'Potimarron');

-- --------------------------------------------------------

--
-- Structure de la table `caracteristique`
--

DROP TABLE IF EXISTS `caracteristique`;
CREATE TABLE IF NOT EXISTS `caracteristique` (
  `ID_CARACTERISTIQUE` int NOT NULL,
  `ID_UNITE` int NOT NULL,
  `LIBELLE` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`ID_CARACTERISTIQUE`),
  KEY `FK_A_POUR_UNITE` (`ID_UNITE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `caracteristique`
--

INSERT INTO `caracteristique` (`ID_CARACTERISTIQUE`, `ID_UNITE`, `LIBELLE`) VALUES
(1, 1, 'glucides'),
(2, 1, 'lipides'),
(3, 1, 'protéines'),
(4, 1, 'sel'),
(5, 1, 'sucre'),
(6, 3, 'calories'),
(7, 1, 'eau'),
(8, 1, 'fibres'),
(9, 1, 'potassium'),
(10, 1, 'vitamine C'),
(11, 1, 'vitamine B9'),
(12, 1, 'vitamine B5');

-- --------------------------------------------------------

--
-- Structure de la table `contient`
--

DROP TABLE IF EXISTS `contient`;
CREATE TABLE IF NOT EXISTS `contient` (
  `ID_REPAS` int NOT NULL,
  `ID_ALIMENT` int NOT NULL,
  `QUANTITE` decimal(10,3) NOT NULL,
  PRIMARY KEY (`ID_REPAS`,`ID_ALIMENT`),
  KEY `FK_CONTIENT2` (`ID_ALIMENT`,`ID_REPAS`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `contient`
--

INSERT INTO `contient` (`ID_REPAS`, `ID_ALIMENT`, `QUANTITE`) VALUES
(6, 4, 1000.000);

-- --------------------------------------------------------

--
-- Structure de la table `contient_pour_100g`
--

DROP TABLE IF EXISTS `contient_pour_100g`;
CREATE TABLE IF NOT EXISTS `contient_pour_100g` (
  `ID_ALIMENT` int NOT NULL,
  `ID_CARACTERISTIQUE` int NOT NULL,
  `QUANTITE` decimal(10,3) NOT NULL,
  PRIMARY KEY (`ID_ALIMENT`,`ID_CARACTERISTIQUE`),
  KEY `FK_CONTIENT_POUR_100G2` (`ID_CARACTERISTIQUE`,`ID_ALIMENT`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `contient_pour_100g`
--

INSERT INTO `contient_pour_100g` (`ID_ALIMENT`, `ID_CARACTERISTIQUE`, `QUANTITE`) VALUES
(1, 1, 3.590),
(1, 6, 20.300),
(1, 7, 94.100),
(1, 8, 1.000),
(1, 9, 0.210),
(1, 10, 0.016);

-- --------------------------------------------------------

--
-- Structure de la table `est_compose_de`
--

DROP TABLE IF EXISTS `est_compose_de`;
CREATE TABLE IF NOT EXISTS `est_compose_de` (
  `ID_ALIMENT` int NOT NULL,
  `ID_ALIMENT_ENFANT` int NOT NULL,
  `PROPORTION` decimal(6,3) NOT NULL,
  PRIMARY KEY (`ID_ALIMENT`,`ID_ALIMENT_ENFANT`),
  KEY `FK_EST_COMPOSE_DE2` (`ID_ALIMENT_ENFANT`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `est_compose_de`
--

INSERT INTO `est_compose_de` (`ID_ALIMENT`, `ID_ALIMENT_ENFANT`, `PROPORTION`) VALUES
(4, 1, 33.000),
(4, 2, 33.000),
(4, 3, 33.000);

-- --------------------------------------------------------

--
-- Structure de la table `niveau_sportif`
--

DROP TABLE IF EXISTS `niveau_sportif`;
CREATE TABLE IF NOT EXISTS `niveau_sportif` (
  `ID_NIVEAU` int NOT NULL,
  `LIBELLE` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`ID_NIVEAU`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `niveau_sportif`
--

INSERT INTO `niveau_sportif` (`ID_NIVEAU`, `LIBELLE`) VALUES
(0, 'Aucun'),
(1, 'Amateur'),
(2, 'Intermédiaire'),
(3, 'Professionnel');

-- --------------------------------------------------------

--
-- Structure de la table `repas`
--

DROP TABLE IF EXISTS `repas`;
CREATE TABLE IF NOT EXISTS `repas` (
  `ID_REPAS` int NOT NULL AUTO_INCREMENT,
  `LOGIN` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `DATE_HEURE` datetime NOT NULL,
  PRIMARY KEY (`ID_REPAS`),
  KEY `FK_EST_MANGE_PAR` (`LOGIN`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `repas`
--

INSERT INTO `repas` (`ID_REPAS`, `LOGIN`, `DATE_HEURE`) VALUES
(1, 'teddyRider', '2023-10-20 16:30:52'),
(2, 'teddyRider', '2024-10-18 15:32:40'),
(3, 'jeanMichel42', '2024-10-09 16:32:56'),
(4, 'suzanneMichel69', '2024-10-09 16:33:12'),
(5, 'TonyParCoeur', '2024-10-10 16:33:24'),
(6, 'teddyRider', '2024-10-10 16:33:39'),
(7, 'teddyRider', '2024-10-18 15:10:42'),
(8, 'teddyRider', '2024-09-18 15:10:42');

-- --------------------------------------------------------

--
-- Structure de la table `sexe`
--

DROP TABLE IF EXISTS `sexe`;
CREATE TABLE IF NOT EXISTS `sexe` (
  `ID_SEXE` int NOT NULL,
  `LIBELLE` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`ID_SEXE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `sexe`
--

INSERT INTO `sexe` (`ID_SEXE`, `LIBELLE`) VALUES
(1, 'Male'),
(2, 'Female');

-- --------------------------------------------------------

--
-- Structure de la table `type_d_aliment`
--

DROP TABLE IF EXISTS `type_d_aliment`;
CREATE TABLE IF NOT EXISTS `type_d_aliment` (
  `ID_TYPE_ALIMENT` int NOT NULL,
  `LIBELLE` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`ID_TYPE_ALIMENT`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `type_d_aliment`
--

INSERT INTO `type_d_aliment` (`ID_TYPE_ALIMENT`, `LIBELLE`) VALUES
(1, 'légume'),
(2, 'fruit'),
(3, 'produit transformé'),
(4, 'viande'),
(5, 'poisson');

-- --------------------------------------------------------

--
-- Structure de la table `unite`
--

DROP TABLE IF EXISTS `unite`;
CREATE TABLE IF NOT EXISTS `unite` (
  `ID_UNITE` int NOT NULL,
  `LIBELLE` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`ID_UNITE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `unite`
--

INSERT INTO `unite` (`ID_UNITE`, `LIBELLE`) VALUES
(1, 'g'),
(2, 'mL'),
(3, 'kcal');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `LOGIN` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ID_NIVEAU` int NOT NULL,
  `ID_SEXE` int NOT NULL,
  `PASSWORD` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ANNEE_NAISSANCE` smallint NOT NULL,
  `PSEUDO` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`LOGIN`),
  KEY `FK_A_POUR_GENRE` (`ID_SEXE`),
  KEY `FK_A_POUR_NIVEAU` (`ID_NIVEAU`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`LOGIN`, `ID_NIVEAU`, `ID_SEXE`, `PASSWORD`, `ANNEE_NAISSANCE`, `PSEUDO`, `email`) VALUES
('DupontMartin123', 1, 1, 'commun', 1983, 'Dupont', 'martin.dupont@gmail.com'),
('jeanMichel42', 2, 1, 'pwd', 1967, 'JeanMich', 'JeanMich@el.com'),
('suzanneMichel69', 1, 2, 'passwordCompliqué', 1995, 'suzanneMichel', 'suzanneMich@el.com'),
('teddyRider', 3, 1, 'Judo4TheWin', 1989, 'Teddy', 'teddy@ursa.fr'),
('TonyParCoeur', 3, 1, 'LesDécimalesDePi!', 1982, 'TroisQuatorze', 'Pierre.Pipi@pipi.pi');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `aliment`
--
ALTER TABLE `aliment`
  ADD CONSTRAINT `FK_A_POUR_TYPE` FOREIGN KEY (`ID_TYPE_ALIMENT`) REFERENCES `type_d_aliment` (`ID_TYPE_ALIMENT`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Contraintes pour la table `caracteristique`
--
ALTER TABLE `caracteristique`
  ADD CONSTRAINT `FK_A_POUR_UNITE` FOREIGN KEY (`ID_UNITE`) REFERENCES `unite` (`ID_UNITE`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Contraintes pour la table `contient`
--
ALTER TABLE `contient`
  ADD CONSTRAINT `FK_CONTIENT` FOREIGN KEY (`ID_REPAS`) REFERENCES `repas` (`ID_REPAS`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_CONTIENT2` FOREIGN KEY (`ID_ALIMENT`) REFERENCES `aliment` (`ID_ALIMENT`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Contraintes pour la table `contient_pour_100g`
--
ALTER TABLE `contient_pour_100g`
  ADD CONSTRAINT `FK_CONTIENT_POUR_100G` FOREIGN KEY (`ID_ALIMENT`) REFERENCES `aliment` (`ID_ALIMENT`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_CONTIENT_POUR_100G2` FOREIGN KEY (`ID_CARACTERISTIQUE`) REFERENCES `caracteristique` (`ID_CARACTERISTIQUE`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Contraintes pour la table `est_compose_de`
--
ALTER TABLE `est_compose_de`
  ADD CONSTRAINT `FK_EST_COMPOSE_DE` FOREIGN KEY (`ID_ALIMENT`) REFERENCES `aliment` (`ID_ALIMENT`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_EST_COMPOSE_DE2` FOREIGN KEY (`ID_ALIMENT_ENFANT`) REFERENCES `aliment` (`ID_ALIMENT`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Contraintes pour la table `repas`
--
ALTER TABLE `repas`
  ADD CONSTRAINT `FK_EST_MANGE_PAR` FOREIGN KEY (`LOGIN`) REFERENCES `utilisateur` (`LOGIN`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `FK_A_POUR_GENRE` FOREIGN KEY (`ID_SEXE`) REFERENCES `sexe` (`ID_SEXE`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_A_POUR_NIVEAU` FOREIGN KEY (`ID_NIVEAU`) REFERENCES `niveau_sportif` (`ID_NIVEAU`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;