# ************************************************************
# Sequel Pro SQL dump
# Version 4135
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 74.220.208.20 (MySQL 5.5.37-log)
# Database: howmany1_goalsforhumanity
# Generation Time: 2014-06-07 02:47:35 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table active_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `active_users`;

CREATE TABLE `active_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(56) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `lockout_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `active_users` WRITE;
/*!40000 ALTER TABLE `active_users` DISABLE KEYS */;

INSERT INTO `active_users` (`id`, `ip`, `time`, `status`, `lockout_time`)
VALUES
	(8,'68.40.207.112','2014-06-05 18:20:16',1,'2014-06-05 18:20:16'),
	(9,'68.40.205.180','2014-06-06 19:59:44',1,'2014-06-06 19:59:44');

/*!40000 ALTER TABLE `active_users` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table game_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `game_log`;

CREATE TABLE `game_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `location` int(11) DEFAULT NULL,
  `player1_id` int(11) DEFAULT NULL,
  `player2_id` int(11) DEFAULT NULL,
  `result` int(1) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table new_goal
# ------------------------------------------------------------

DROP TABLE IF EXISTS `new_goal`;

CREATE TABLE `new_goal` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `verify_code` varchar(256) DEFAULT NULL,
  `captcha_code` varchar(6) DEFAULT NULL,
  `ip_address` varchar(56) DEFAULT NULL,
  `goal` varchar(256) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `new_goal` WRITE;
/*!40000 ALTER TABLE `new_goal` DISABLE KEYS */;

INSERT INTO `new_goal` (`id`, `verify_code`, `captcha_code`, `ip_address`, `goal`, `status`)
VALUES
	(1,NULL,'14181','68.40.207.112','hello',2),
	(2,NULL,'14181','68.40.207.112','universe',2),
	(6,NULL,'99972','65.173.238.2','get firefighters in latcha',4),
	(7,NULL,'36908','65.173.238.2','make spicier meats in our universe',4),
	(8,NULL,'14181','68.40.207.112','demilitarization of the world',2),
	(9,NULL,'14181','68.40.207.112','get rid of Atomic weapons',2),
	(10,NULL,'14181','68.40.207.112','net neutrality',2),
	(11,NULL,'72180','65.173.238.2','Build larger A1 Abrahms tanks for \'Merica',4),
	(12,NULL,'43634','65.173.238.2','Viagra for squirrels',4),
	(13,NULL,'39335','65.173.238.2','Resource based economy',1),
	(14,NULL,'14181','68.40.207.112','build larger buildings',2),
	(15,NULL,'18334','108.171.16.95','Poverty hello',4),
	(16,NULL,'14181','68.40.207.112','invent awesome new things',2),
	(17,NULL,'18841','65.173.238.2','Save the honey bees',3),
	(18,NULL,'14181','68.40.207.112','make things awesome',2),
	(19,NULL,'14181','68.40.207.112','Free Speech',2),
	(20,NULL,'14181','68.40.207.112','do things that matter',2),
	(21,NULL,'14181','68.40.207.112','more candy',1);

/*!40000 ALTER TABLE `new_goal` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table ratings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ratings`;

CREATE TABLE `ratings` (
  `playerid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `rating` decimal(18,9) DEFAULT NULL,
  `goal` varchar(256) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  PRIMARY KEY (`playerid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `ratings` WRITE;
/*!40000 ALTER TABLE `ratings` DISABLE KEYS */;

INSERT INTO `ratings` (`playerid`, `rating`, `goal`, `time`)
VALUES
	(1,1457.186826683,'Economic equality','2014-03-29 12:14:57'),
	(2,1443.452842789,'Explore the Universe','2014-03-29 12:14:57'),
	(3,1423.049353473,'Pollute less','2014-03-29 12:14:57'),
	(4,1625.278505744,'Stop global warming','2014-03-29 12:14:57'),
	(5,1609.084483159,'The empowerment of Women','2014-03-29 12:14:57'),
	(6,1358.249618399,'Regime change in North Korea','2014-03-29 12:14:57'),
	(7,1613.707404849,'End poverty','2014-03-29 12:14:57'),
	(8,1355.024640375,'Legalize Drugs','2014-03-29 12:14:57'),
	(9,1610.390434822,'Equal pay for equal work','2014-03-29 12:14:57'),
	(16,1376.879893610,'Resource based Economy','2014-03-29 12:14:57'),
	(11,1504.634142327,'Campaign finance reform','2014-03-29 12:14:57'),
	(12,1660.940127624,'Better Education','2014-03-29 12:14:57'),
	(13,1538.041121290,'Get rid of Atomic Weapons','2014-03-29 12:14:57'),
	(14,1443.965883758,'Demilitarization of the World','2014-03-29 12:14:57'),
	(15,1502.673504686,'Net neutrality','2014-03-29 12:14:57'),
	(17,1470.932960741,'Save the honey bees','2014-05-30 12:14:57'),
	(18,1500.000000000,'Free Speech','2014-06-05 12:14:57');

/*!40000 ALTER TABLE `ratings` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table remaining_games
# ------------------------------------------------------------

DROP TABLE IF EXISTS `remaining_games`;

CREATE TABLE `remaining_games` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(56) DEFAULT NULL,
  `player1_id` int(11) DEFAULT NULL,
  `player2_id` int(11) DEFAULT NULL,
  `vkey` varchar(60) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `remaining_games` WRITE;
/*!40000 ALTER TABLE `remaining_games` DISABLE KEYS */;

INSERT INTO `remaining_games` (`id`, `ip`, `player1_id`, `player2_id`, `vkey`, `time`)
VALUES
	(200,'68.40.205.180',17,5,'cf0bfca1da13b23ad96732a716d623baa55b51d8','2014-06-06 19:59:44'),
	(199,'68.40.205.180',18,16,'0ecc24463d20e6c4fd0278c65a2d72b8480bed8a','2014-06-06 19:59:44'),
	(198,'68.40.205.180',14,3,'9e220fa10dee54d10990149c45e7d01429312b3b','2014-06-06 19:59:44'),
	(197,'68.40.205.180',15,2,'9383e5ca340f0b31f59823904842465d9d2998b5','2014-06-06 19:59:44'),
	(192,'68.40.207.112',15,7,'d722b82f3c35ce2eab171f33770df4ca4ce3c52e','2014-06-05 18:20:16'),
	(191,'68.40.207.112',17,13,'30e043edb833317bd9f1bcb4d3c376775a479cf2','2014-06-05 18:20:16'),
	(190,'68.40.207.112',11,14,'684c743119b899239bcd3d5de9876fbfc979d815','2014-06-05 18:20:16'),
	(201,'68.40.205.180',8,1,'956fec413d323c09fb02f494ded0f41e6e479ea8','2014-06-06 19:59:44'),
	(202,'68.40.205.180',12,6,'819ae4c6f6eae653b5ddf0ce4d7c82cdfb6ac943','2014-06-06 19:59:44'),
	(203,'68.40.205.180',13,4,'b7240c6bfe636d877646a0e656a2317534d0fae9','2014-06-06 19:59:44'),
	(204,'68.40.205.180',7,9,'8a03d5ab4a26672e2ded164c3656aa1f03322abb','2014-06-06 19:59:44');

/*!40000 ALTER TABLE `remaining_games` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
