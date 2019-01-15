SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `item`;
CREATE TABLE `item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `list_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `description` text,
  `img` varchar(255),
  `url` text,
  `price` decimal(5,2) DEFAULT NULL,
  `reservation_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `reservation`;
CREATE TABLE `reservation` (
  `no` int(11) NOT NULL AUTO_INCREMENT,
  `purchaser` varchar(20) DEFAULT NULL,
  `participant` text NOT NULL,
  `message` text,
  PRIMARY KEY (`no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `comment`;
CREATE TABLE `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `list_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `sender` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- uploaded = sur le serveur et local = present à la base et à ne pas supprimer

DROP TABLE IF EXISTS `image`;
CREATE TABLE `image` (
  `basename` varchar(255) NOT NULL,
  `uploaded` boolean NOT NULL,
  `local` text NOT NULL,
  PRIMARY KEY (`basename`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `account`;
CREATE TABLE `account` (
  `username` varchar(20) NOT NULL,
  `first_name` text NOT NULL,
  `last_name` text NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `participant` boolean DEFAULT FALSE,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `image` VALUES
('opera.jpg', true, true),
('index.jpg', true, true),
('diner.jpg', true, true),
('connaissance.jpg', true, true),
('cocktail.jpg', true, true),
('cadeaux.jpg', true, true),
('animateur.jpg', true, true),
('champagne.jpg', true, true),
('musique.jpg', true, true),
('poirelregarder.jpg', true, true),
('gouter.jpg', true, true),
('film.jpg', true, true),
('rose.jpg', true, true),
('bonroi.jpg', true, true),
('origami.jpg', true, true),
('bricolage.jpg', true, true),
('grandrue.jpg', true, true),
('place.jpg', true, true),
('bijoux.jpg', true, true),
('contact.jpg', true, true),
('concert.jpg', true, true),
('apparthotel.jpg', true, true),
('hotelreine.jpg', true, true),
('hotel_haussonville_logo.jpg', true, true),
('boitedenuit.jpg', true, true),
('laser.jpg', true, true),
('fort.jpg', true, true);

INSERT INTO `item` (`id`, `list_id`, `name`, `description`, `img`, `url`, `price`) VALUES
(1,	2,	'Champagne',	'Bouteille de champagne + flutes + jeux à gratter',	'champagne.jpg',	'',	20.00),
(2,	2,	'Musique',	'Partitions de piano à 4 mains',	'musique.jpg',	'',	25.00),
(3,	2,	'Exposition',	'Visite guidée de l’exposition ‘REGARDER’ à la galerie Poirel',	'poirelregarder.jpg',	'',	14.00),
(4,	3,	'Goûter',	'Goûter au FIFNL',	'gouter.jpg',	'',	20.00),
(5,	3,	'Projection',	'Projection courts-métrages au FIFNL',	'film.jpg',	'',	10.00),
(6,	2,	'Bouquet',	'Bouquet de roses et Mots de Marion Renaud',	'rose.jpg',	'',	16.00),
(7,	2,	'Diner Stanislas',	'Diner à La Table du Bon Roi Stanislas (Apéritif /Entrée / Plat / Vin / Dessert / Café / Digestif)',	'bonroi.jpg',	'',	60.00),
(8,	3,	'Origami',	'Baguettes magiques en Origami en buvant un thé',	'origami.jpg',	'',	12.00),
(9,	3,	'Livres',	'Livre bricolage avec petits-enfants + Roman',	'bricolage.jpg',	'',	24.00),
(10,	2,	'Diner  Grand Rue ',	'Diner au Grand’Ru(e) (Apéritif / Entrée / Plat / Vin / Dessert / Café)',	'grandrue.jpg',	'',	59.00),
(11,	0,	'Visite guidée',	'Visite guidée personnalisée de Saint-Epvre jusqu’à Stanislas',	'place.jpg',	'',	11.00),
(12,	2,	'Bijoux',	'Bijoux de manteau + Sous-verre pochette de disque + Lait après-soleil',	'bijoux.jpg',	'',	29.00),
(19,	0,	'Jeu contacts',	'Jeu pour échange de contacts',	'contact.png',	'',	5.00),
(22,	0,	'Concert',	'Un concert à Nancy',	'concert.jpg',	'',	17.00),
(23,	1,	'Appart Hotel',	'Appart’hôtel Coeur de Ville, en plein centre-ville',	'apparthotel.jpg',	'',	56.00),
(24,	2,	'Hôtel d\'Haussonville',	'Hôtel d\'Haussonville, au coeur de la Vieille ville à deux pas de la place Stanislas',	'hotel_haussonville_logo.jpg',	'',	169.00),
(25,	1,	'Boite de nuit',	'Discothèque, Boîte tendance avec des soirées à thème & DJ invités',	'boitedenuit.jpg',	'',	32.00),
(26,	1,	'Planètes Laser',	'Laser game : Gilet électronique et pistolet laser comme matériel, vous voilà équipé.',	'laser.jpg',	'',	15.00),
(27,	1,	'Fort Aventure',	'Découvrez Fort Aventure à Bainville-sur-Madon, un site Accropierre unique en Lorraine ! Des Parcours Acrobatiques pour petits et grands, Jeu Mission Aventure, Crypte de Crapahute, Tyrolienne, Saut à l\'élastique inversé, Toboggan géant... et bien plus encore.',	'fort.jpg',	'',	25.00);

DROP TABLE IF EXISTS `list`;
CREATE TABLE `list` (
  `no` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `expiration` date DEFAULT NULL,
  `owner_name` varchar(20) DEFAULT NULL,
  `access_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `modify_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `public` boolean DEFAULT FALSE,
  PRIMARY KEY (`no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `list` (`no`, `user_id`, `title`, `description`, `expiration`, `access_token`) VALUES
(1,	1,	'Pour fêter le bac !',	'Pour un week-end à Nancy qui nous fera oublier les épreuves. ',	'2018-06-27',	'nosecure1'),
(2,	2,	'Liste de mariage d\'Alice et Bob',	'Nous souhaitons passer un week-end royal à Nancy pour notre lune de miel :)',	'2018-06-30',	'nosecure2'),
(3,	3,	'C\'est l\'anniversaire de Charlie',	'Pour lui préparer une fête dont il se souviendra :)',	'2017-12-12',	'nosecure3');
