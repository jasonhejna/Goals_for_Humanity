# ************************************************************
# Sequel Pro SQL dump
# Version 4135
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: howmanycupsofcoffee.com (MySQL 5.5.37-log)
# Database: howmany1_goalsforhumanity
# Generation Time: 2014-04-27 23:15:16 +0000
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
	(1,'68.40.207.112','2014-04-27 17:00:46',1,'2014-04-27 17:00:46');

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
  `captcha_code` varchar(6) DEFAULT NULL,
  `ip_address` varchar(56) DEFAULT NULL,
  `goal` varchar(256) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `new_goal` WRITE;
/*!40000 ALTER TABLE `new_goal` DISABLE KEYS */;

INSERT INTO `new_goal` (`id`, `captcha_code`, `ip_address`, `goal`, `status`)
VALUES
	(1,'82078','68.40.207.112','hello',2);

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
	(1,1455.316914170,'economic equality','2014-03-29 12:14:57'),
	(2,1638.292492628,'explore the universe','2014-03-29 12:14:57'),
	(3,1395.297237403,'pollute less','2014-03-29 12:14:57'),
	(4,1610.112944611,'stop global warming','2014-03-29 12:14:57'),
	(5,1458.201294759,'the empowerment of women','2014-03-29 12:14:57'),
	(6,1420.107232936,'regime change in North Korea','2014-03-29 12:14:57'),
	(7,1526.890509676,'end poverty','2014-03-29 12:14:57'),
	(8,1456.232888052,'legalize drugs','2014-03-29 12:14:57'),
	(9,1374.549610213,'Equal pay for equal work','2014-03-29 12:14:57'),
	(10,1504.153269507,'free speech and equality for everyone','2014-03-29 12:14:57'),
	(11,1507.058888017,'Campaign finance reform','2014-03-29 12:14:57'),
	(12,1582.359873909,'Better education','2014-03-29 12:14:57');

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
	(1,'68.40.207.112',11,7,'b2a27684caac4dc47592ee7487bfe3b9a42ea194','2014-04-24 23:59:15'),
	(2,'68.40.207.112',2,12,'1ebeff36b8f96b83c88fdd25771de314b07e914f','2014-04-24 23:59:15'),
	(3,'68.40.207.112',3,5,'a9191a275f604faf5570f388a9b98fe28390faa6','2014-04-24 23:59:15'),
	(4,'68.40.207.112',10,8,'3e8b574fbed4c62e4695f37cca7cafd70dd77dff','2014-04-24 23:59:15'),
	(5,'68.40.207.112',9,6,'87f64ed8a687e98d23c4c505c5b3c3899555b27e','2014-04-24 23:59:15'),
	(6,'68.40.207.112',1,4,'c23f44b738aa5dd46f0dab6dcb381dfef3366662','2014-04-24 23:59:15'),
	(7,'68.40.207.112',10,11,'985bb01579b8bf9a5bef366fcd3e0c535cac9d13','2014-04-27 17:00:46'),
	(8,'68.40.207.112',6,7,'62a34fcfe55b0958b842fe9544e485057c02f0b6','2014-04-27 17:00:46'),
	(9,'68.40.207.112',1,12,'ce00a9a1682b560becc47969aeeb756001d33e65','2014-04-27 17:00:46'),
	(10,'68.40.207.112',8,3,'4a18a2f30e7d787afb90c33b8df7626c86bf687a','2014-04-27 17:00:46'),
	(11,'68.40.207.112',2,4,'3fd12e3ece2997003ce07cda5ac69b31878291bf','2014-04-27 17:00:46'),
	(12,'68.40.207.112',9,5,'0d9b6088c6cacf3a5d4f24d4f6f9454b2dd5de95','2014-04-27 17:00:46');

/*!40000 ALTER TABLE `remaining_games` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
