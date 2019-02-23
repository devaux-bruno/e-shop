-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  mer. 06 fév. 2019 à 17:05
-- Version du serveur :  10.1.34-MariaDB
-- Version de PHP :  7.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `dev19_boutique`
--

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `id_commande` int(3) NOT NULL,
  `id_membre` int(3) NOT NULL,
  `montant` int(3) NOT NULL,
  `date_enregistrement` datetime NOT NULL,
  `etat` enum('en cours de traitement','envoyer','livré') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id_commande`, `id_membre`, `montant`, `date_enregistrement`, `etat`) VALUES
(2, 4, 120, '2019-02-06 10:11:17', 'en cours de traitement'),
(3, 8, 282, '2019-02-06 14:18:05', 'en cours de traitement'),
(4, 8, 324, '2019-02-06 14:25:40', 'en cours de traitement'),
(5, 1, 324, '2019-02-06 15:15:26', 'en cours de traitement');

-- --------------------------------------------------------

--
-- Structure de la table `details_commande`
--

CREATE TABLE `details_commande` (
  `id_details_commande` int(3) NOT NULL,
  `id_commande` int(3) NOT NULL,
  `id_produit` int(3) NOT NULL,
  `quantite` int(3) NOT NULL,
  `prix` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `details_commande`
--

INSERT INTO `details_commande` (`id_details_commande`, `id_commande`, `id_produit`, `quantite`, `prix`) VALUES
(1, 2, 21, 1, 55),
(2, 2, 26, 1, 45),
(3, 3, 5, 1, 75),
(4, 3, 15, 1, 65),
(5, 3, 14, 2, 25),
(6, 3, 25, 1, 45),
(7, 4, 1, 1, 105),
(8, 4, 5, 1, 75),
(9, 4, 7, 1, 25),
(10, 4, 24, 1, 65),
(11, 5, 1, 1, 105),
(12, 5, 19, 1, 55),
(13, 5, 5, 1, 75),
(14, 5, 18, 1, 35);

-- --------------------------------------------------------

--
-- Structure de la table `membre`
--

CREATE TABLE `membre` (
  `id_membre` int(3) NOT NULL,
  `pseudo` varchar(20) NOT NULL,
  `mdp` varchar(60) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `prenom` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `civilite` enum('m','f') NOT NULL,
  `ville` varchar(20) NOT NULL,
  `code_postal` int(5) UNSIGNED ZEROFILL NOT NULL,
  `adresse` varchar(100) NOT NULL,
  `statut` int(1) NOT NULL,
  `image_profil` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `membre`
--

INSERT INTO `membre` (`id_membre`, `pseudo`, `mdp`, `nom`, `prenom`, `email`, `civilite`, `ville`, `code_postal`, `adresse`, `statut`, `image_profil`) VALUES
(1, 'Pinpin', '730c7cf2430e67ba540451b9f2b7e6b1', 'Pinpin', 'Ponpon', 'pinpin@gmail.com', 'm', 'Paris', 75001, '36 Quai des orgies, 3éme étages', 1, 'image_profil-Pinpin.jpg'),
(2, 'Pioupiou', '730c7cf2430e67ba540451b9f2b7e6b1', 'God', 'Michel', 'blabla@gmail.com', 'm', 'Paris', 75001, '22 bis route de la police', 0, 'image_profil-Pioupiou.jpg'),
(3, 'Badboy69', '730c7cf2430e67ba540451b9f2b7e6b1', 'Sparow', 'Jack', 'badboy69@grosgeek.com', 'm', 'Lyon', 69000, '11 Avenue du geek, 3éme étages, 3ème porte à gauche', 0, 'image_profil-Badboy69.jpg'),
(4, 'Lolitadu13', '730c7cf2430e67ba540451b9f2b7e6b1', 'Belleblonde', 'Natasha', 'Lolitasuce@gmail.com', 'm', 'Marseille', 13000, '13 Boulevard des tapins', 1, 'image_profil-Lolitadu13.jpg'),
(6, 'BGdu75', '730c7cf2430e67ba540451b9f2b7e6b1', 'BG', 'le', 'bg75@gmail.com', 'm', 'paris', 75000, '32 rue des bg', 0, 'image_profil-BGdu75.jpg'),
(7, 'bebelol', '730c7cf2430e67ba540451b9f2b7e6b1', 'bebe', 'junio', 'bebe@gmail.com', 'm', 'bebeland', 99999, '39 boulevard des couches culotes', 0, 'image_profil-bebelol.jpg'),
(8, 'mario', '730c7cf2430e67ba540451b9f2b7e6b1', 'Bros', 'Mario', 'mario@mario.fr', 'm', 'Marioland', 98535, '32 rue du boulgour', 1, 'image_profil-mario.jpg'),
(9, 'lola', '730c7cf2430e67ba540451b9f2b7e6b1', 'palooza', 'lola', 'lola@gmail.com', 'f', 'paris', 75001, '32 rue du chewing gum', 0, 'image_profil-images (3).jpg');

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `id_produit` int(3) NOT NULL,
  `reference` varchar(20) NOT NULL,
  `categorie` varchar(20) NOT NULL,
  `titre` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `couleur` varchar(20) NOT NULL,
  `taille` varchar(5) NOT NULL,
  `public` enum('m','f','mixte') NOT NULL,
  `photo` varchar(250) NOT NULL,
  `prix` int(3) NOT NULL,
  `stock` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id_produit`, `reference`, `categorie`, `titre`, `description`, `couleur`, `taille`, `public`, `photo`, `prix`, `stock`) VALUES
(1, 'GRJEANS01', 'Jeans', 'Jeans Homme Gris', 'Authentique Jeans gris', 'gris', 'M', 'm', 'GRJEANS01-jean-republic-denim-gris-jeans-gris-delave-dechire-slim-coupe-jogging-wfrcrdu-1279-400x400.jpg', 105, 0),
(5, 'GRVESTE01', 'Veste', 'Veste en Jeans Grise', 'Authentique Veste en Jeans Grise 100% coton \r\nMADE IN FRANCE', 'gris', 'M', 'm', 'GRVESTE01-blouson-veste-urban-locker-gris-veste-homme-grise-bi-matiere-jean-et-sweat-a-capuche-851-400x400.jpg', 75, 12),
(7, 'GRTSHIRT01', 'T-shirt', 'T-shirt', 'T-shirt moulant Homme 100%coton', 'gris', 'L', 'm', 'GRTSHIRT01-__00001_Sous-vetement-thermique-ZARGUN.jpg', 25, 14),
(8, 'BLJEANS01', 'Jeans', 'Jeans fashion', 'Authentique Jeans MADE IN FRANCE 100% coton', 'bleu', 'M', 'mixte', 'BLJEANS01-2bd7dbce_1544448601_l_levis_251340_A.jpg', 75, 15),
(14, 'VTSHIRT01', 'T-shirt', 'T-shirt vert', 'Authentique T-shirt Vert 100%coton  MADE IN FRANCE', 'vert', 'M', 'mixte', 'VTSHIRT01-_20170905172553-medium..jpg', 25, 13),
(15, 'NRPULL01', 'Pull', 'Pull noir BG', 'Authentique Pull Noir 100%coton  MADE IN FRANCE', 'noir', 'XL', 'm', 'NRPULL01-img_TRIC02_ONTARIO2_NOIR_vue1.jpg', 65, 14),
(16, 'MRJEANS', 'Jeans', 'Jeans Tabac', 'Authentique Jeans couleur caca 100%coton  MADE IN FRANCE', 'marron', 'M', 'f', 'MRJEANS-pantalon-bio-cintre-tabac.jpg', 95, 25),
(18, 'SWTSHIRT', 'T-shirt', 'T-shirt STAR WARS', 'Authentique et officiel T-shirt Star Wars 100%coton  MADE IN FRANCE', 'noir', 'S', 'mixte', 'SWTSHIRT-t-shirt-logo-star-wars.jpg', 35, 49),
(19, 'DUOPULL01', 'Pull', 'Pull Duo Bleu &amp; Rouge ', 'Authentique Pull Bi-color 100%coton  MADE IN FRANCE', 'bleu', 'S', 'mixte', 'DUOPULL01-Fila-Pull-À-Capuche-Hommes-Nuit-Blocked-Sweat.jpg', 55, 24),
(20, 'GRVESTE02', 'Veste', 'Veste Grise', 'Authentique veste grise 100%coton  MADE IN FRANCE', 'gris', 'L', 'mixte', 'GRVESTE02-veste-de-concours-hkm-marburg.jpg', 145, 10),
(21, 'RDROBE01', 'Robe', 'Robe rouge', 'Robe Glamour Rouge', 'rouge', 'M', 'f', 'RDROBE01-bordeaux-robe-de-soiree-courte-femme-sexy-manche-l.jpg', 55, 14),
(22, 'NRROBE01', 'Robe', 'Robe noir', 'Robe Charme noir', 'noir', 'S', 'f', 'NRROBE01-d776dbffdad374e74d4344017fde3921.jpg', 65, 15),
(23, 'BLROBE01', 'Robe', 'Robe bleu', 'Robe soirée bleu', 'bleu', 'M', 'f', 'BLROBE01-2276280_2.jpg', 65, 15),
(24, 'RDPYJAMA01', 'Pyjama', 'Pyjama \"Set de table\"', 'Pyjama à carreaux rouge pour Femme 100%lin', 'rouge', 'M', 'f', 'RDPYJAMA01-706075.jpg', 65, 24),
(25, 'BLPYJAMA01', 'Pyjama', 'Pyjama Homme', 'Pyjama pour Homme bleu 100%soie', 'bleu', 'M', 'm', 'BLPYJAMA01-05203-Pyjama-jersey-de-coton-bio-homme-living-crafts-gris.jpg', 45, 4),
(26, 'CSPYJAMA01', 'Pyjama', 'Pyjama Licorne', 'Pyjama Licorne 100%coton MADE IN CHINA', 'blanc', 'S', 'm', 'CSPYJAMA01-joli-kigurumi-pyjama-licorne-bleu.jpg', 45, 0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id_commande`),
  ADD KEY `id_membre` (`id_membre`);

--
-- Index pour la table `details_commande`
--
ALTER TABLE `details_commande`
  ADD PRIMARY KEY (`id_details_commande`),
  ADD KEY `id_commande` (`id_commande`),
  ADD KEY `id_produit` (`id_produit`);

--
-- Index pour la table `membre`
--
ALTER TABLE `membre`
  ADD PRIMARY KEY (`id_membre`),
  ADD UNIQUE KEY `pseudo` (`pseudo`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id_produit`),
  ADD UNIQUE KEY `reference` (`reference`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id_commande` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `details_commande`
--
ALTER TABLE `details_commande`
  MODIFY `id_details_commande` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `membre`
--
ALTER TABLE `membre`
  MODIFY `id_membre` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `id_produit` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `commande_ibfk_1` FOREIGN KEY (`id_membre`) REFERENCES `membre` (`id_membre`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `details_commande`
--
ALTER TABLE `details_commande`
  ADD CONSTRAINT `details_commande_ibfk_1` FOREIGN KEY (`id_commande`) REFERENCES `commande` (`id_commande`) ON UPDATE CASCADE,
  ADD CONSTRAINT `details_commande_ibfk_2` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id_produit`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
