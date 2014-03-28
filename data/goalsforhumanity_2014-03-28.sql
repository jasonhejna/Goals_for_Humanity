# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.5.33)
# Database: goalsforhumanity
# Generation Time: 2014-03-28 13:42:34 +0000
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8;

LOCK TABLES `active_users` WRITE;
/*!40000 ALTER TABLE `active_users` DISABLE KEYS */;

INSERT INTO `active_users` (`id`, `ip`, `time`, `status`)
VALUES
	(57,'::1','2014-03-27 23:14:38',1);

/*!40000 ALTER TABLE `active_users` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table games
# ------------------------------------------------------------

DROP TABLE IF EXISTS `games`;

CREATE TABLE `games` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `player1_id` int(11) DEFAULT NULL,
  `player2_id` int(11) DEFAULT NULL,
  `key` varchar(128) DEFAULT NULL,
  `ip` varchar(56) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

LOCK TABLES `games` WRITE;
/*!40000 ALTER TABLE `games` DISABLE KEYS */;

INSERT INTO `games` (`id`, `player1_id`, `player2_id`, `key`, `ip`, `time`)
VALUES
	(21,6,3,'zNeataKAjD/0tjY54ASVZ+x73tQNU39FEYvtf6zL6U1psRq7fl2B7Fu88COPevs3xYAZehSc6zB5zp2egxOUbkaa7nVJDYQO3Atm4ZE+kl3/4y12WM6vg719mESjQw6S','::1','2014-03-22 15:12:00'),
	(22,10,1,'EEt3puEPmKkeKQbhd8XIHu4I+Oj/IeKc51izbfEIUZ99f8XUJAAUE+jgzM5Rcf+dVUmFAINEWWPI6f+TyxgLWFwI+KQzDAhomQDmhKt/RF/0r4VkslLWEOAcJeuB26Xs','::1','2014-03-22 15:12:04'),
	(23,3,8,'5fCvJBAUjSfe2RHENpEa0U8xN3p/yrLo2SVzRKvWWvYXMssMLM87r8c5oF12lKEAsriT6WVGHKsQASEq3JDXVYT3AYNJRdjrhqLt/DIVT4RplffJQcr6BklfWXNFHSy0','::1','2014-03-22 15:12:08'),
	(24,5,2,'KeBeC82t5nI3XbbkPQY240aOmmX4bdIQyeRy9NhVYlM8s91K2IV1t0xSvUm0u3pU3DHLOYkK5aQX8fyeMO1DeHOTtO1ux7z7nOrSsSoCYTJH3yZYijaiO2m9rnM/f+UR','::1','2014-03-22 15:12:11'),
	(25,2,7,'TADN/3+yabOCnQeBlJo54ZA4EwqD4AJ/KR2D8ibsvYIPeZdjTJrKW++kACyjtuKi0f7j4mS6OR+1euRklgtrNGp63qt0e9B1UElEFlwYNeB90eoacdTdklT71sMgW38D','::1','2014-03-22 15:12:12'),
	(26,10,4,'q0Qms/lEqndLu6rbv4yQikBCfWrx8c3pPp9DjQIj5C0elXwQevOlvssIOisy2egzPrnR3wsh/SMeSPwYoHGXTSRhytTaCVncv/pudNLBIU9oxPW9C3YZ+1UG0P37KJ0O','::1','2014-03-22 15:12:13'),
	(27,5,9,'kKVD3B9ImSLOSxMFnHooJGwS+fJmQ2UI5oZXzIrJEemcfoWpQ6xw4ACKck/E/dXytZkx4U4/ajQm40SOdaCdRUoMPIFiZ0hL2M1QSz2WmFGhP7ij1JxNV+o3j8RZOztM','::1','2014-03-22 15:12:16'),
	(28,8,9,'Ve8ATk94n0y0bNvNkxzsLWFScn983wKG6PgFU3MCRzzIZkYWbb1EhDUYgW1EgEzENBOd8VdTTmS9yHcC5fqVV+1SxZ6FliJDM4krCUPWzZAMrUxsvjy1WlbcEYGexWsL','::1','2014-03-22 15:12:18'),
	(29,8,4,'EvPL6iLMjJsWlOBzSfFg3Q3iAWFEd0SLd0TLaDKxv17HRK3a9662d8+Pa/wgiWAz2Hkt3TylVSOInB0jZ/uM8NIZT8+BgRV/JaA/nLyj4p7RwYToS6Nh/kjpsq1PpkpK','::1','2014-03-22 15:12:19'),
	(30,10,1,'NrfbRm2DazKKtnQcz7G2d3/Vc86RM9tihDAR5jwupiUjvk7iE884rXd0ivSkunLUMEDR0bPjtQDHx0leVZSbyZWqxaaZwojkH2GTqR3R5sAaatLZILxMowwRPwj3NFBd','::1','2014-03-22 15:12:20'),
	(31,10,4,'zgYbxTVOvbpe5DjgoirDamBvRB2Cf2r9Nk67JeyNKD9pdY4v02q4m6UZ0psMH8X7FzplKzYRNWF4ohz44d7dqV7FokRGF7S+v/69wEbRQTIcpj/8KMzX743Ox0sSTqqL','::1','2014-03-22 15:12:21'),
	(32,7,10,'rREPPuhB7L+OfOzQuhfoL8W/270NQpB8BJKgI+KB3KVRK0bTzgUFJneh0PhOPtmZkYSP7utxl/J22J4FEIk9G62UmHXqvXIuxearVDuLTre5faBbMGV/emPH3/0DUPyk','::1','2014-03-22 15:12:22'),
	(33,4,10,'Y5rF2o5RtWb3ZolFKwBrcqoSh+1y1dS703GPXCHjUb74ioPgsZSu4UiNi3NUDH1EDupIdSx4f1jVIhs7xbQCkeTOXvlw2rMS0IYjZL5ovnA6UiAZ4aIXL9gngJf+tGnA','::1','2014-03-22 15:12:24'),
	(34,6,9,'VuhH+AJx3u/s2YmFAVubgFzZqgTDiAGLRLkKIDr7gpJiODrP2Xr0ngDL73mVqXCsvG4P8WAUYtZFeJJ2QTwR4qYHKd2xV1DjbZYWFeaxbAuaT4bdRRV0IMGkzb7DTJQA','::1','2014-03-22 15:12:26'),
	(35,3,2,'TwtKGYUr+C/e8AYo2z+5nsn/ElCmklFMUwGD3sHjf2uRiozejskPc4EyI/VdosQeYTSExjzGnF2qNdGd8sSE1Fy+XMfGTgP1MhxG9ElwuWxzDrhdSl57rp0n9UTNH2XP','::1','2014-03-22 15:12:29');

/*!40000 ALTER TABLE `games` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table ip_logs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ip_logs`;

CREATE TABLE `ip_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(56) DEFAULT NULL,
  `log_time` datetime DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

LOCK TABLES `ratings` WRITE;
/*!40000 ALTER TABLE `ratings` DISABLE KEYS */;

INSERT INTO `ratings` (`playerid`, `rating`, `goal`, `time`)
VALUES
	(1,1500.000000000,'have more lols',NULL),
	(2,1500.000000000,'explore the universe',NULL),
	(3,1500.000000000,'polute less',NULL),
	(4,1500.000000000,'stop global warming',NULL),
	(5,1500.000000000,'the empowerment of women',NULL),
	(6,1500.000000000,'regime change in North Korea',NULL),
	(7,1500.000000000,'end poverty',NULL),
	(8,1500.000000000,'legalize drugs',NULL),
	(9,1500.000000000,'stopping religious bigotry',NULL),
	(10,1500.000000000,'preventing genocide',NULL),
	(11,1500.000000000,'Equal pay for equal work.',NULL);

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
  `key` varchar(128) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
