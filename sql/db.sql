-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : Dim 29 nov. 2020 à 19:06
-- Version du serveur :  5.7.31
-- Version de PHP : 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Base de données : `duck_love`
--

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(200) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`id`, `name`, `description`, `published`, `create_date`) VALUES
(1, 'Vêtements', 'Différents types de vêtements', 1, '2020-11-28 00:00:00'),
(2, 'Bricolage', 'Des outils pour refaire votre intérieur et extérieur', 0, '2020-11-18 00:00:00'),
(3, 'Informatique', 'Tout le nécessaire pour vos besoins', 1, '2020-11-04 00:00:00'),
(4, 'Electroménager', 'Du frigo jusqu\'au four...', 0, '2020-11-17 00:00:00'),
(5, 'Décoration', 'Pour réveiller l\'âme de designer en vous', 1, '2020-11-15 00:00:00'),
(6, 'Jeux & Jouets', 'Pour tous les âges...', 1, '2020-11-14 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `category_item`
--

DROP TABLE IF EXISTS `category_item`;
CREATE TABLE IF NOT EXISTS `category_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_category_item_category_id` (`category_id`),
  KEY `fk_category_item_item_id` (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `category_item`
--

INSERT INTO `category_item` (`id`, `category_id`, `item_id`) VALUES
(1, 5, 1),
(2, 2, 2),
(3, 1, 3),
(4, 3, 4),
(5, 2, 5),
(6, 3, 6),
(7, 2, 7),
(8, 5, 7),
(9, 6, 8),
(10, 6, 9),
(11, 2, 10),
(12, 5, 10),
(13, 1, 11),
(14, 1, 12),
(15, 3, 13),
(16, 3, 14),
(17, 3, 15),
(18, 3, 16),
(19, 6, 17),
(20, 2, 18),
(21, 5, 18),
(22, 2, 19),
(23, 1, 20),
(24, 3, 21),
(25, 6, 22);

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` text NOT NULL,
  `published` tinyint(1) NOT NULL,
  `item_id` int(11) NOT NULL,
  `create_user_id` int(11) NOT NULL,
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_comment_create_user_id` (`create_user_id`),
  KEY `fk_comment_item_id` (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `comment`
--

INSERT INTO `comment` (`id`, `message`, `published`, `item_id`, `create_user_id`, `create_date`) VALUES
(1, 'La souris ne fonctionne pas après 2 heures d\'utilisation', 1, 6, 4, '2020-11-29 00:00:00'),
(2, 'Vraiment trop basse', 1, 10, 3, '2020-11-29 00:00:00'),
(3, 'Très belles paires, je recommende!!', 0, 3, 4, '2020-11-24 00:00:00'),
(4, 'Difficile à monter', 1, 10, 6, '2020-11-29 00:00:00'),
(5, 'Le meilleur produit ever !', 1, 22, 1, '2020-11-29 17:06:55');

-- --------------------------------------------------------

--
-- Structure de la table `item`
--

DROP TABLE IF EXISTS `item`;
CREATE TABLE IF NOT EXISTS `item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `details` text NOT NULL,
  `published` tinyint(1) NOT NULL,
  `create_date` datetime NOT NULL,
  `create_user_id` int(11) NOT NULL,
  `filename` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_item_create_user_id` (`create_user_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `item`
--

INSERT INTO `item` (`id`, `title`, `description`, `details`, `published`, `create_date`, `create_user_id`, `filename`) VALUES
(1, 'Chaises hautes', 'Chaises hautes pour table ', 'Chaises hautes pour table haute', 1, '2020-11-13 00:00:00', 5, NULL),
(2, 'Marteau', 'Marteau pour tout usage', 'Rien de plus à dire', 0, '2020-11-16 00:00:00', 3, NULL),
(3, 'Sneakers', 'Paire de sneakers ', 'Coloris au choix', 1, '2020-11-24 00:00:00', 2, NULL),
(4, 'Clavier', 'Clavier Azerty classique', 'Clavier avec toutes ses touches', 0, '2020-11-06 00:00:00', 1, NULL),
(5, 'Tournevis', 'Tournevis cruciforme', 'Tournevis cruciforme qui convient à toutes les vis', 0, '2020-11-17 00:00:00', 3, NULL),
(6, 'Souris', 'Souris classique', 'Souris classique à 3 boutons', 1, '2020-11-21 00:00:00', 6, NULL),
(7, 'Lampe', 'Lampe d\'intérieur', 'Lampe d\'intérieur 16V', 1, '2020-11-18 00:00:00', 4, NULL),
(8, 'Monopoly', 'Monopoly pour jouer avec toute la famille', 'Monopoly pour jouer avec toute la famille et s\'énerver ensemble', 1, '2020-11-13 00:00:00', 5, NULL),
(9, 'Mille-bornes', 'Mille-bornes tout ce qu\'il y a de plus classique', 'Le premier arrivé à 1000 a gagné', 1, '2020-11-15 00:00:00', 2, NULL),
(10, 'Table-basse', 'Table-basse pour votre salon', 'Elle est vraiment basse', 1, '2020-11-20 00:00:00', 1, NULL),
(11, 'Chemises', 'Chemises avec grand choix de motifs', 'Pour toujours avoir l\'air classe', 1, '2020-11-19 00:00:00', 4, NULL),
(12, 'Jeans', 'Jeans avec différents types', 'Slim/Bootcut/Straight...', 1, '2020-11-22 00:00:00', 2, NULL),
(13, 'Clavier n°2', 'Clavier Azerty classique n°2', 'Clavier avec toutes ses touches', 1, '2020-11-29 00:00:00', 4, NULL),
(14, 'Clavier n°3', 'Clavier Azerty classique n°3', 'Clavier avec toutes ses touches', 1, '2020-11-30 00:00:00', 1, NULL),
(15, 'Souris n°2', 'Souris classique n°2', 'Souris classique à 3 boutons n°2', 1, '2020-11-25 00:00:00', 3, NULL),
(16, 'Souris n°3', 'Souris classique n°3', 'Souris classique à 3 boutons n°3', 1, '2020-11-29 00:00:00', 3, NULL),
(17, 'Cluedo', 'Cluedo  version Deluxe', 'Cluedo avec encore plus de personnages,objets de meutre, etc...', 1, '2020-11-16 00:00:00', 5, NULL),
(18, 'Etagères', 'Etagères à monter soi-même', 'Etagères avec possibilté de montage différent', 1, '2020-11-19 00:00:00', 3, NULL),
(19, 'Perçeuse', 'Perçeuse multi-fonctions', 'Perçeuse avec de nombreux embouts détachables', 1, '2020-11-24 00:00:00', 6, NULL),
(20, 'T-shirts', 'T-shirts pour homme/femme', 'T-shirts pour toutes les tailles homme/femme', 1, '2020-11-23 00:00:00', 4, NULL),
(21, 'Ecran', 'Ecran 18 pouces', 'Ecran 18 pouces full HD', 1, '2020-11-17 00:00:00', 1, NULL),
(22, 'Sony PlayStation 4', 'Une superbe PS4', 'Une superbe Sony PlayStation 4', 1, '2020-11-29 17:58:08', 1, 'ps4.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `user_access_id` int(11) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `create_date` datetime NOT NULL,
  `access_key` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `fk_user_access_id` (`user_access_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `last_name`, `first_name`, `email`, `user_access_id`, `published`, `create_date`, `access_key`) VALUES
(1, 'efranzoni', '5c0297505b6149829da1671b3fd3c2f1', 'FRANZONI', 'Enzo', 'enzofranzo@hotmail.fr', 1, 1, '2020-11-04 00:00:00', NULL),
(2, 'groussel', 'b0893d0f535eae07c84b6420669e51f3', 'ROUSSEL', 'Guillaume', 'guillaume.roussel@lacatholille.fr', 1, 1, '2020-11-30 00:00:00', NULL),
(3, 'rchardon', '6db469cf320301b5a8fa154008c1aefe', 'CHARDON', 'Romain', 'romain.chardon@lacatholille.fr', 2, 0, '2020-11-18 00:00:00', NULL),
(4, 'hmouloundou', '68ed381d94787a99256e7dbe4e99598e', 'MOULOUNDOU', 'Harvey', 'harvey.mouloundou@lacatholille.fr', 3, 1, '2020-11-17 00:00:00', NULL),
(5, 'spetrowski', 'df27809562918beaffef6c06c62d2ed5', 'PETROWSKI', 'Sasha', 'sasha.petrowski@lacatholille.fr', 2, 1, '2020-11-23 00:00:00', NULL),
(6, 'nungureanu', '0e15bb8b0a9656292620e4cbe57ee6ec', 'UNGUREANU', 'Nicolas', 'nicolas.ungureanu@lacatholille.fr', 2, 1, '2020-11-26 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `user_access`
--

DROP TABLE IF EXISTS `user_access`;
CREATE TABLE IF NOT EXISTS `user_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `user_access`
--

INSERT INTO `user_access` (`id`, `name`) VALUES
(1, 'Administrateur'),
(2, 'Auteur'),
(3, 'Éditorialiste');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `category_item`
--
ALTER TABLE `category_item`
  ADD CONSTRAINT `fk_category_item_category_id` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `fk_category_item_item_id` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`);

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `fk_comment_create_user_id` FOREIGN KEY (`create_user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk_comment_item_id` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`);

--
-- Contraintes pour la table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `fk_create_user_id` FOREIGN KEY (`create_user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_access_id` FOREIGN KEY (`user_access_id`) REFERENCES `user_access` (`id`);
COMMIT;
