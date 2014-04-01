# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.5.33)
# Database: goalsforhumanity
# Generation Time: 2014-04-01 04:17:04 +0000
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



# Dump of table ratings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ratings`;

CREATE TABLE `ratings` (
  `playerid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `rating` decimal(18,9) DEFAULT NULL,
  `goal` varchar(256) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  PRIMARY KEY (`playerid`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

LOCK TABLES `ratings` WRITE;
/*!40000 ALTER TABLE `ratings` DISABLE KEYS */;

INSERT INTO `ratings` (`playerid`, `rating`, `goal`, `time`)
VALUES
	(1,1499.743097530,'have more lols','2014-03-29 12:14:57'),
	(2,1499.743097530,'explore the universe','2014-03-29 12:14:57'),
	(3,1513.645967120,'polute less','2014-03-29 12:14:57'),
	(4,1525.256902470,'stop global warming','2014-03-29 12:14:57'),
	(5,1523.204203650,'the empowerment of women','2014-03-29 12:14:57'),
	(6,1497.282456850,'regime change in North Korea','2014-03-29 12:14:57'),
	(7,1542.326161490,'end poverty','2014-03-29 12:14:57'),
	(8,1429.639648280,'legalize drugs','2014-03-29 12:14:57'),
	(9,1397.474718480,'stopping religious bigotry','2014-03-29 12:14:57'),
	(10,1475.000000000,'preventing genocide','2014-03-29 12:14:57'),
	(11,1525.256902470,'Equal pay for equal work.','2014-03-29 12:14:57');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
