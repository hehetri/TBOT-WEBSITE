/*
SQLyog Community v8.71 
MySQL - 5.6.25-0ubuntu0.15.04.1 : Database - evo
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`evo` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `evo`;

/*Table structure for table `Event` */

DROP TABLE IF EXISTS `Event`;

CREATE TABLE `Event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account` varchar(21) DEFAULT NULL,
  `name` varchar(21) DEFAULT NULL,
  `christmas2017` int(11) DEFAULT '0',
  `halloween2018` int(11) DEFAULT '0',
  `easter2019` int(11) DEFAULT '0',
  `halloween2019` int(11) DEFAULT '0',
  `christmas2019` int(11) DEFAULT '0',
  `halloween2020` int(11) DEFAULT '0',
  `christmas2020` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=158 DEFAULT CHARSET=latin1;

/*Table structure for table `Ipbanned` */

DROP TABLE IF EXISTS `Ipbanned`;

CREATE TABLE `Ipbanned` (
  `number` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(20) DEFAULT NULL,
  `banned` int(11) DEFAULT NULL,
  PRIMARY KEY (`number`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

/*Table structure for table `LobbyMsg` */

DROP TABLE IF EXISTS `LobbyMsg`;

CREATE TABLE `LobbyMsg` (
  `number` int(11) NOT NULL AUTO_INCREMENT,
  `message` varchar(255) NOT NULL,
  `color` int(11) NOT NULL DEFAULT '2',
  `showonce` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`number`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Table structure for table `PvPRanks` */

DROP TABLE IF EXISTS `PvPRanks`;

CREATE TABLE `PvPRanks` (
  `Rank` varchar(50) DEFAULT NULL,
  `RankID` int(11) DEFAULT NULL,
  `RequiredKills` int(11) DEFAULT NULL,
  `RequiredLevel` int(11) DEFAULT NULL,
  `DropStract` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `blocklist` */

DROP TABLE IF EXISTS `blocklist`;

CREATE TABLE `blocklist` (
  `number` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  `blocked` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`number`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Table structure for table `bout_channels` */

DROP TABLE IF EXISTS `bout_channels`;

CREATE TABLE `bout_channels` (
  `id` tinyint(225) NOT NULL AUTO_INCREMENT,
  `name` varchar(225) NOT NULL,
  `minlevel` int(3) NOT NULL,
  `maxlevel` int(3) NOT NULL,
  `players` int(3) NOT NULL,
  `status` int(1) NOT NULL,
  `channelip` varchar(20) DEFAULT NULL,
  `server` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Table structure for table `bout_characters` */

DROP TABLE IF EXISTS `bout_characters`;

CREATE TABLE `bout_characters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) NOT NULL,
  `name` varchar(40) NOT NULL,
  `bot` int(3) NOT NULL DEFAULT '1',
  `exp` int(11) NOT NULL DEFAULT '0',
  `level` int(11) NOT NULL DEFAULT '1',
  `hp` int(11) NOT NULL DEFAULT '1000',
  `gigas` int(11) NOT NULL DEFAULT '500',
  `attmin` int(11) NOT NULL DEFAULT '30',
  `attmax` int(11) NOT NULL DEFAULT '35',
  `attmintrans` int(11) NOT NULL DEFAULT '90',
  `attmaxtrans` int(11) NOT NULL DEFAULT '105',
  `transgauge` int(11) NOT NULL DEFAULT '1000',
  `crit` int(11) NOT NULL DEFAULT '0',
  `evade` int(11) NOT NULL DEFAULT '0',
  `specialtrans` int(11) NOT NULL DEFAULT '0',
  `speed` int(11) NOT NULL DEFAULT '1000',
  `transdef` int(11) NOT NULL DEFAULT '0',
  `transbotatt` int(11) NOT NULL DEFAULT '1000',
  `transspeed` int(11) NOT NULL DEFAULT '0',
  `defense` int(11) NOT NULL DEFAULT '500',
  `rangeatt` int(11) NOT NULL DEFAULT '1000',
  `luk` int(11) NOT NULL DEFAULT '0',
  `botstract` int(11) NOT NULL DEFAULT '0',
  `equiphead` int(25) NOT NULL DEFAULT '0',
  `equipbody` int(25) NOT NULL DEFAULT '0',
  `equiparm` int(25) NOT NULL DEFAULT '0',
  `equipminibot` int(25) NOT NULL DEFAULT '0',
  `equipgun` int(25) NOT NULL DEFAULT '0',
  `equipefield` int(25) NOT NULL DEFAULT '0',
  `equipwing` int(25) NOT NULL DEFAULT '0',
  `equipshield` int(25) NOT NULL DEFAULT '0',
  `equiparmpart` int(25) NOT NULL DEFAULT '0',
  `equipflag1` int(25) NOT NULL DEFAULT '0',
  `equipflag2` int(25) NOT NULL DEFAULT '0',
  `equippassivskill` int(25) NOT NULL DEFAULT '0',
  `equipaktivskill` int(25) NOT NULL DEFAULT '0',
  `equippack` int(25) NOT NULL DEFAULT '0',
  `equiptransbot` int(25) NOT NULL DEFAULT '0',
  `equipmerc` int(25) NOT NULL DEFAULT '0',
  `equipmerc2` int(25) NOT NULL DEFAULT '0',
  `equipheadcoin` int(25) NOT NULL DEFAULT '0',
  `equipminibotcoin` int(25) NOT NULL DEFAULT '0',
  `muteStime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `muted` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2130 DEFAULT CHARSET=latin1;

/*Table structure for table `bout_coin_flush` */

DROP TABLE IF EXISTS `bout_coin_flush`;

CREATE TABLE `bout_coin_flush` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) DEFAULT NULL,
  `coins` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `bout_deleted` */

DROP TABLE IF EXISTS `bout_deleted`;

CREATE TABLE `bout_deleted` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `bout_exptable` */

DROP TABLE IF EXISTS `bout_exptable`;

CREATE TABLE `bout_exptable` (
  `Level` int(255) NOT NULL,
  `ReqExp` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `bout_exptable` */

insert  into `bout_exptable`(`Level`,`ReqExp`) values (1,0),(2,23),(3,68),(4,135),(5,225),(6,338),(7,473),(8,630),(9,810),(10,1013),(11,1238),(12,1485),(13,1755),(14,2048),(15,2363),(16,2700),(17,3060),(18,3443),(19,3848),(20,4275),(21,4725),(22,5198),(23,5693),(24,6210),(25,6750),(26,7313),(27,7898),(28,8505),(29,9135),(30,9788),(31,10463),(32,11160),(33,11880),(34,12623),(35,13388),(36,14175),(37,14985),(38,15818),(39,16673),(40,17550),(41,18450),(42,19373),(43,20318),(44,21285),(45,22275),(46,23288),(47,24323),(48,25380),(49,26460),(50,27563),(51,28688),(52,29835),(53,31005),(54,32198),(55,33413),(56,34650),(57,35910),(58,37193),(59,38498),(60,39825),(61,41175),(62,42548),(63,43943),(64,45360),(65,46800),(66,48263),(67,49748),(68,51255),(69,52785),(70,54338),(71,55913),(72,57510),(73,59130),(74,60773),(75,62438),(76,64125),(77,65835),(78,67568),(79,69323),(80,71100),(81,72900),(82,74723),(83,76568),(84,78435),(85,80325),(86,82238),(87,84173),(88,86130),(89,88110),(90,90113),(91,92138),(92,94185),(93,96255),(94,98348),(95,100463),(96,102600),(97,104760),(98,106943),(99,109148),(100,111375),(101,116676),(102,119613),(103,123192),(104,126231),(105,128932),(106,131970),(107,135347),(108,139736),(109,145138),(110,150409),(111,155798),(112,161187),(113,166577),(114,171966),(115,177356),(116,182745),(117,188135),(118,193542),(119,198914),(120,204303);

/*Table structure for table `bout_inventory` */

DROP TABLE IF EXISTS `bout_inventory`;

CREATE TABLE `bout_inventory` (
  `name` varchar(25) NOT NULL,
  `item1` int(25) NOT NULL DEFAULT '0',
  `item2` int(25) NOT NULL DEFAULT '0',
  `item3` int(25) NOT NULL DEFAULT '0',
  `item4` int(25) NOT NULL DEFAULT '0',
  `item5` int(25) NOT NULL DEFAULT '0',
  `item6` int(25) NOT NULL DEFAULT '0',
  `item7` int(25) NOT NULL DEFAULT '0',
  `item8` int(25) NOT NULL DEFAULT '0',
  `item9` int(25) NOT NULL DEFAULT '0',
  `item10` int(25) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `bout_items` */

DROP TABLE IF EXISTS `bout_items`;

CREATE TABLE `bout_items` (
  `id` int(25) NOT NULL,
  `name` varchar(50) NOT NULL,
  `reqlevel` int(2) NOT NULL,
  `buyable` int(1) NOT NULL DEFAULT '0',
  `buy` int(14) NOT NULL,
  `sell` int(14) NOT NULL,
  `coins` int(25) NOT NULL DEFAULT '0',
  `days` int(5) NOT NULL DEFAULT '0',
  `bot` int(1) NOT NULL,
  `part` int(3) NOT NULL,
  `script` text,
  `duration` int(11) NOT NULL DEFAULT '1',
  `icon` int(11) DEFAULT '0',
  `description` varchar(110) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `buyable` (`buyable`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `bout_mob_log` */

DROP TABLE IF EXISTS `bout_mob_log`;

CREATE TABLE `bout_mob_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level` int(4) DEFAULT NULL,
  `mobinfo` varchar(50) DEFAULT NULL,
  `error` varchar(30) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10371 DEFAULT CHARSET=latin1;

/*Table structure for table `bout_users` */

DROP TABLE IF EXISTS `bout_users`;

CREATE TABLE `bout_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) NOT NULL,
  `password` varchar(40) NOT NULL,
  `email` varchar(40) NOT NULL,
  `coins` int(25) NOT NULL DEFAULT '0',
  `banned` int(1) NOT NULL DEFAULT '0',
  `online` int(1) NOT NULL DEFAULT '0',
  `current_ip` varchar(100) NOT NULL DEFAULT '',
  `logincount` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `last_ip` varchar(100) NOT NULL DEFAULT '',
  `lastlogin` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `Position` int(1) NOT NULL DEFAULT '0',
  `forumaccount` varchar(40) DEFAULT NULL,
  `bantime` int(11) DEFAULT '0',
  `banStime` timestamp NULL DEFAULT NULL,
  `banreason` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1906 DEFAULT CHARSET=latin1;

/*Table structure for table `boxhack_log` */

DROP TABLE IF EXISTS `boxhack_log`;

CREATE TABLE `boxhack_log` (
  `nr` int(11) NOT NULL AUTO_INCREMENT,
  `lvl` int(11) DEFAULT NULL,
  `roomowner` varchar(20) DEFAULT NULL,
  `roomplayers` text,
  PRIMARY KEY (`nr`)
) ENGINE=MyISAM AUTO_INCREMENT=86 DEFAULT CHARSET=utf8;

/*Table structure for table `charname_changes` */

DROP TABLE IF EXISTS `charname_changes`;

CREATE TABLE `charname_changes` (
  `number` int(11) NOT NULL AUTO_INCREMENT,
  `account_name` varchar(24) NOT NULL,
  `new_name` varchar(24) NOT NULL,
  `old_name` varchar(24) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`number`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;

/*Table structure for table `coindrops` */

DROP TABLE IF EXISTS `coindrops`;

CREATE TABLE `coindrops` (
  `number` int(11) NOT NULL AUTO_INCREMENT,
  `itemid` int(11) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`number`)
) ENGINE=InnoDB AUTO_INCREMENT=216 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

/*Data for the table `coindrops` */

insert  into `coindrops`(`number`,`itemid`,`type`) values (1,9417401,'head'),(2,9417402,'head'),(3,9417403,'head'),(4,9417001,'head'),(5,9417002,'head'),(6,9417003,'head'),(7,9416301,'head'),(8,9416302,'head'),(9,9416303,'head'),(10,9416101,'head'),(11,9416102,'head'),(12,9416103,'head'),(13,9416001,'head'),(14,9416002,'head'),(15,9416003,'head'),(16,9415901,'head'),(17,9415902,'head'),(18,9415903,'head'),(19,9415801,'head'),(20,9415802,'head'),(21,9415803,'head'),(22,9415601,'head'),(23,9415602,'head'),(24,9415603,'head'),(25,9415301,'head'),(26,9415302,'head'),(27,9415303,'head'),(28,9415201,'head'),(29,9415202,'head'),(30,9415203,'head'),(31,9415001,'head'),(32,9415002,'head'),(33,9415003,'head'),(34,9414601,'head'),(35,9414602,'head'),(36,9414603,'head'),(37,9414101,'head'),(38,9414102,'head'),(39,9414103,'head'),(40,9412801,'head'),(41,9412802,'head'),(42,9412803,'head'),(43,9412601,'head'),(44,9412602,'head'),(45,9412603,'head'),(46,9412501,'head'),(47,9412502,'head'),(48,9412503,'head'),(49,9412401,'head'),(50,9412402,'head'),(51,9412403,'head'),(52,9412201,'head'),(53,9412202,'head'),(54,9412203,'head'),(55,9412001,'head'),(56,9412002,'head'),(57,9412003,'head'),(58,9411901,'head'),(59,9411902,'head'),(60,9411903,'head'),(61,9411801,'head'),(62,9411802,'head'),(63,9411803,'head'),(64,9411701,'head'),(65,9411702,'head'),(66,9411703,'head'),(67,9411601,'head'),(68,9411602,'head'),(69,9411603,'head'),(70,9411501,'head'),(71,9411502,'head'),(72,9411503,'head'),(73,9417501,'head'),(91,9022201,'minibot'),(92,9022202,'minibot'),(93,9022203,'minibot'),(94,9022301,'minibot'),(95,9022302,'minibot'),(96,9022303,'minibot'),(97,9022401,'minibot'),(98,9022402,'minibot'),(99,9022403,'minibot'),(100,9022601,'minibot'),(101,9022602,'minibot'),(102,9022603,'minibot'),(103,9022701,'minibot'),(104,9022702,'minibot'),(105,9022703,'minibot'),(106,9022801,'minibot'),(107,9022802,'minibot'),(108,9022803,'minibot'),(109,9022901,'minibot'),(110,9022902,'minibot'),(111,9022903,'minibot'),(112,9023001,'minibot'),(113,9023002,'minibot'),(114,9023003,'minibot'),(115,9023101,'minibot'),(116,9023102,'minibot'),(117,9023103,'minibot'),(118,9023301,'minibot'),(119,9023302,'minibot'),(120,9023303,'minibot'),(121,9023401,'minibot'),(122,9023402,'minibot'),(123,9023403,'minibot'),(124,9023801,'minibot'),(125,9023802,'minibot'),(126,9023803,'minibot'),(127,9024101,'minibot'),(128,9024102,'minibot'),(129,9024103,'minibot'),(130,9024201,'minibot'),(131,9024202,'minibot'),(132,9024203,'minibot'),(133,9024301,'minibot'),(134,9024302,'minibot'),(135,9024303,'minibot'),(136,9024401,'minibot'),(137,9024402,'minibot'),(138,9024403,'minibot'),(139,9024801,'minibot'),(140,9024802,'minibot'),(141,9024803,'minibot'),(142,9025001,'minibot'),(143,9025002,'minibot'),(144,9025003,'minibot'),(145,9025801,'minibot'),(146,9025802,'minibot'),(147,9025803,'minibot'),(148,4040107,'trans'),(149,4040207,'trans'),(150,4040307,'trans'),(151,4040407,'trans'),(152,4040507,'trans'),(153,4040607,'trans'),(154,4040707,'trans'),(155,4040807,'trans'),(156,4040907,'trans'),(157,4041007,'trans'),(158,4041407,'trans'),(159,4041507,'trans'),(160,3040100,'wing'),(161,3040200,'wing'),(162,3041100,'wing'),(163,3041200,'wing'),(164,3041300,'wing'),(165,3041400,'wing'),(166,3041600,'wing'),(167,3041700,'wing'),(168,3041800,'wing'),(169,3041900,'wing'),(170,3022801,'gun'),(171,3022101,'gun'),(172,3022401,'gun'),(173,3022201,'gun'),(174,3022601,'gun'),(175,3022501,'gun'),(176,3022301,'gun'),(177,4052101,'merc'),(178,4051401,'merc'),(179,4051101,'merc'),(180,4051301,'merc'),(181,4051001,'merc'),(182,4050801,'merc'),(183,4051201,'merc'),(184,4050901,'merc'),(185,6000001,'gold'),(186,6000002,'gold'),(187,6000003,'gold'),(188,7412100,'eventitem'),(189,7416100,'eventitem'),(190,3070601,'eventitem'),(191,3070701,'eventitem'),(192,3070401,'flag'),(193,3070402,'flag'),(194,3070403,'flag'),(195,3080401,'flag'),(196,3080402,'flag'),(197,3080403,'flag'),(198,3072001,'flag'),(199,3072002,'flag'),(200,3072003,'flag'),(201,3082001,'flag'),(202,3082002,'flag'),(203,3082003,'flag'),(204,3071301,'flag'),(205,3071302,'flag'),(206,3071303,'flag'),(207,3081301,'flag'),(208,3081302,'flag'),(209,3081303,'flag'),(210,3071201,'flag'),(211,3071202,'flag'),(212,3071203,'flag'),(213,9071001,'flag'),(214,9071002,'flag'),(215,9071003,'flag');

/*Table structure for table `drop_log` */

DROP TABLE IF EXISTS `drop_log`;

CREATE TABLE `drop_log` (
  `nr` int(11) NOT NULL AUTO_INCREMENT,
  `drop` int(11) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `dropsort` int(11) DEFAULT NULL,
  `player` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`nr`)
) ENGINE=MyISAM AUTO_INCREMENT=100041 DEFAULT CHARSET=utf8;

/*Table structure for table `drops` */

DROP TABLE IF EXISTS `drops`;

CREATE TABLE `drops` (
  `number` int(11) NOT NULL AUTO_INCREMENT,
  `itemid` int(11) DEFAULT NULL,
  `minlevel` int(11) DEFAULT NULL,
  `maxlevel` int(11) DEFAULT NULL,
  `type` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`number`)
) ENGINE=InnoDB AUTO_INCREMENT=246 DEFAULT CHARSET=utf8;

/*Data for the table `drops` */

insert  into `drops`(`number`,`itemid`,`minlevel`,`maxlevel`,`type`) values (1,3030111,1,30,'ef'),(2,3030112,1,30,'ef'),(3,3030113,1,30,'ef'),(4,3030121,1,30,'ef'),(5,3030122,1,30,'ef'),(6,3030123,1,30,'ef'),(7,3030131,1,30,'ef'),(8,3030132,1,30,'ef'),(9,3030133,1,30,'ef'),(10,3030211,30,60,'ef'),(11,3030212,30,60,'ef'),(12,3030213,30,60,'ef'),(13,3030221,30,60,'ef'),(14,3030222,30,60,'ef'),(15,3030223,30,60,'ef'),(16,3030231,30,60,'ef'),(17,3030232,30,60,'ef'),(18,3030233,30,60,'ef'),(19,3030311,60,110,'ef'),(20,3030312,60,110,'ef'),(21,3030313,60,110,'ef'),(22,3030321,60,110,'ef'),(23,3030322,60,110,'ef'),(24,3030323,60,110,'ef'),(25,3030331,60,110,'ef'),(26,3030332,60,110,'ef'),(27,3030333,60,110,'ef'),(28,3010201,1,30,'minibot'),(29,3010202,1,30,'minibot'),(30,3010203,1,30,'minibot'),(31,3010301,1,30,'minibot'),(32,3010302,1,30,'minibot'),(33,3010303,1,30,'minibot'),(34,3010401,1,30,'minibot'),(35,3010402,1,30,'minibot'),(36,3010403,1,30,'minibot'),(40,3010701,30,120,'minibot'),(41,3010702,30,120,'minibot'),(42,3010703,30,120,'minibot'),(46,3020101,1,20,'gun'),(47,3020102,1,20,'gun'),(48,3020103,1,20,'gun'),(49,3020111,1,20,'gun'),(50,3020112,1,20,'gun'),(51,3020113,1,20,'gun'),(52,3020201,1,20,'gun'),(53,3020202,1,20,'gun'),(54,3020203,1,20,'gun'),(55,3020401,30,60,'gun'),(56,3020402,30,60,'gun'),(57,3020403,30,60,'gun'),(58,3020501,30,60,'gun'),(59,3020502,30,60,'gun'),(60,3020503,30,60,'gun'),(61,3020601,30,60,'gun'),(62,3020602,30,60,'gun'),(63,3020603,30,60,'gun'),(64,3020801,60,110,'gun'),(65,3020802,60,110,'gun'),(66,3020803,60,110,'gun'),(67,3020901,60,110,'gun'),(68,3020902,60,110,'gun'),(69,3020903,60,110,'gun'),(70,3021001,60,110,'gun'),(71,3021002,60,110,'gun'),(72,3021003,60,110,'gun'),(73,3060101,20,40,'shoulder'),(74,3060102,20,40,'shoulder'),(75,3060103,20,40,'shoulder'),(76,3060104,20,40,'shoulder'),(77,3060105,20,40,'shoulder'),(78,3060106,20,40,'shoulder'),(79,3060107,20,40,'shoulder'),(80,3060110,20,40,'shoulder'),(81,3060109,20,40,'shoulder'),(82,3060301,40,50,'shoulder'),(83,3060302,40,50,'shoulder'),(84,3060303,40,50,'shoulder'),(85,3060304,40,50,'shoulder'),(86,3060305,40,50,'shoulder'),(87,3060306,40,50,'shoulder'),(88,3060307,40,50,'shoulder'),(89,3060308,40,50,'shoulder'),(90,3060309,40,50,'shoulder'),(91,3060401,50,60,'shoulder'),(92,3060402,50,60,'shoulder'),(93,3060403,50,60,'shoulder'),(94,3060404,50,60,'shoulder'),(95,3060405,50,60,'shoulder'),(96,3060406,50,60,'shoulder'),(97,3060407,50,60,'shoulder'),(98,3060408,50,60,'shoulder'),(99,3060409,50,60,'shoulder'),(100,3060501,60,70,'shoulder'),(101,3060502,60,70,'shoulder'),(102,3060503,60,70,'shoulder'),(103,3060504,60,70,'shoulder'),(104,3060505,60,70,'shoulder'),(105,3060506,60,70,'shoulder'),(106,3060507,60,70,'shoulder'),(107,3060508,60,70,'shoulder'),(108,3060509,60,70,'shoulder'),(109,3060601,70,80,'shoulder'),(110,3060602,70,80,'shoulder'),(111,3060603,70,80,'shoulder'),(112,3060604,70,80,'shoulder'),(113,3060605,70,80,'shoulder'),(114,3060606,70,80,'shoulder'),(115,3060607,70,80,'shoulder'),(116,3060608,70,80,'shoulder'),(117,3060609,70,80,'shoulder'),(118,3060701,80,110,'shoulder'),(119,3060702,80,110,'shoulder'),(120,3060703,80,110,'shoulder'),(121,3060704,80,110,'shoulder'),(122,3060705,80,110,'shoulder'),(123,3060706,80,110,'shoulder'),(124,3060707,80,110,'shoulder'),(125,3060708,80,110,'shoulder'),(126,3060709,80,110,'shoulder'),(127,3050101,20,30,'shield'),(128,3050102,20,30,'shield'),(129,3050103,20,30,'shield'),(130,3050104,20,30,'shield'),(131,3050105,20,30,'shield'),(132,3050106,20,30,'shield'),(133,3050107,20,30,'shield'),(134,3050108,20,30,'shield'),(135,3050109,20,30,'shield'),(136,3050201,30,40,'shield'),(137,3050202,30,40,'shield'),(138,3050203,30,40,'shield'),(139,3050204,30,40,'shield'),(140,3050205,30,40,'shield'),(141,3050206,30,40,'shield'),(142,3050207,30,40,'shield'),(143,3050208,30,40,'shield'),(144,3050209,30,40,'shield'),(145,3050301,40,50,'shield'),(146,3050302,40,50,'shield'),(147,3050303,40,50,'shield'),(148,3050304,40,50,'shield'),(149,3050305,40,50,'shield'),(150,3050306,40,50,'shield'),(151,3050307,40,50,'shield'),(152,3050308,40,50,'shield'),(153,3050309,40,50,'shield'),(154,3050401,50,60,'shield'),(155,3050402,50,60,'shield'),(156,3050403,50,60,'shield'),(157,3050404,50,60,'shield'),(158,3050405,50,60,'shield'),(159,3050406,50,60,'shield'),(160,3050407,50,60,'shield'),(161,3050408,50,60,'shield'),(162,3050409,50,60,'shield'),(163,3050501,60,70,'shield'),(164,3050502,60,70,'shield'),(165,3050503,60,70,'shield'),(166,3050504,60,70,'shield'),(167,3050505,60,70,'shield'),(168,3050506,60,70,'shield'),(169,3050507,60,70,'shield'),(170,3050508,60,70,'shield'),(171,3050509,60,70,'shield'),(172,3050601,70,80,'shield'),(173,3050602,70,80,'shield'),(174,3050603,70,80,'shield'),(175,3050604,70,80,'shield'),(176,3050605,70,80,'shield'),(177,3050606,70,80,'shield'),(178,3050607,70,80,'shield'),(179,3050608,70,80,'shield'),(180,3050609,70,80,'shield'),(181,3050701,80,110,'shield'),(182,3050702,80,110,'shield'),(183,3050703,80,110,'shield'),(184,3050704,80,110,'shield'),(185,3050705,80,110,'shield'),(186,3050706,80,110,'shield'),(187,3050707,80,110,'shield'),(188,3050708,80,110,'shield'),(189,3050709,80,110,'shield'),(190,3051300,1,20,'shield'),(191,3061300,1,20,'shoulder'),(192,3032300,95,110,'ef'),(193,3050800,95,110,'shield'),(194,3060800,95,110,'shoulder'),(195,3060801,95,110,'shoulder'),(196,3060802,95,110,'shoulder'),(197,3060803,95,110,'shoulder'),(198,3060804,95,110,'shoulder'),(199,3060805,95,110,'shoulder'),(200,3060806,95,110,'shoulder'),(201,3060807,95,110,'shoulder'),(202,3060808,95,110,'shoulder'),(203,3060809,95,110,'shoulder'),(204,3010190,95,110,'minibot'),(205,3021801,70,80,'gun'),(206,3021802,70,80,'gun'),(207,3021803,70,80,'gun'),(208,3021901,80,90,'gun'),(209,3021902,80,90,'gun'),(210,3021903,80,90,'gun'),(211,3022001,90,110,'gun'),(212,3022002,90,110,'gun'),(213,3022003,90,110,'gun'),(214,3021501,20,30,'gun'),(215,3021502,20,30,'gun'),(216,3021503,20,30,'gun'),(217,3032801,90,120,'ef'),(218,3032802,90,120,'ef'),(219,3032803,90,120,'ef'),(220,3032804,90,120,'ef'),(221,3032805,90,120,'ef'),(222,3032806,90,120,'ef'),(223,3032807,90,120,'ef'),(224,3032808,90,120,'ef'),(226,3032809,90,120,'ef'),(227,3023001,90,120,'gun'),(228,3061401,105,120,'shoulder'),(229,3061402,105,120,'shoulder'),(230,3061403,105,120,'shoulder'),(231,3061404,105,120,'shoulder'),(232,3061405,105,120,'shoulder'),(233,3061406,105,120,'shoulder'),(234,3061407,105,120,'shoulder'),(235,3061408,105,120,'shoulder'),(236,3061409,105,120,'shoulder'),(237,3051401,105,120,'shield'),(238,3051402,105,120,'shield'),(239,3051403,105,120,'shield'),(240,3051404,105,120,'shield'),(241,3051405,105,120,'shield'),(242,3051406,105,120,'shield'),(243,3051407,105,120,'shield'),(244,3051408,105,120,'shield'),(245,3051409,105,120,'shield');

/*Table structure for table `friends` */

DROP TABLE IF EXISTS `friends`;

CREATE TABLE `friends` (
  `nr` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(16) DEFAULT NULL,
  `name2` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`nr`)
) ENGINE=MyISAM AUTO_INCREMENT=901 DEFAULT CHARSET=utf8;

/*Table structure for table `gifts` */

DROP TABLE IF EXISTS `gifts`;

CREATE TABLE `gifts` (
  `nr` int(11) NOT NULL AUTO_INCREMENT,
  `from` varchar(20) DEFAULT NULL,
  `to` varchar(20) DEFAULT NULL,
  `message` varchar(100) DEFAULT NULL,
  `gift` varchar(20) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`nr`)
) ENGINE=MyISAM AUTO_INCREMENT=5352 DEFAULT CHARSET=utf8;

/*Table structure for table `guildapp` */

DROP TABLE IF EXISTS `guildapp`;

CREATE TABLE `guildapp` (
  `number` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  `guildname` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`number`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

/*Table structure for table `guildmembers` */

DROP TABLE IF EXISTS `guildmembers`;

CREATE TABLE `guildmembers` (
  `number` int(11) NOT NULL AUTO_INCREMENT,
  `guild` varchar(16) DEFAULT NULL,
  `player` varchar(15) DEFAULT NULL,
  `guildid` int(11) DEFAULT NULL,
  `points` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`number`)
) ENGINE=MyISAM AUTO_INCREMENT=411 DEFAULT CHARSET=utf8;

/*Table structure for table `guilds` */

DROP TABLE IF EXISTS `guilds`;

CREATE TABLE `guilds` (
  `number` int(11) NOT NULL AUTO_INCREMENT,
  `Guildname` varchar(20) DEFAULT NULL,
  `leader` varchar(20) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `total_points` int(11) DEFAULT '0',
  `leader_points` int(11) DEFAULT '0',
  `notice` text,
  `maxmemb` int(11) DEFAULT '20',
  PRIMARY KEY (`number`)
) ENGINE=MyISAM AUTO_INCREMENT=182 DEFAULT CHARSET=utf8;

/*Table structure for table `item_times` */

DROP TABLE IF EXISTS `item_times`;

CREATE TABLE `item_times` (
  `number` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `accountname` varchar(40) DEFAULT NULL,
  `time` int(11) NOT NULL,
  `location` varchar(10) NOT NULL,
  `itemid` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`number`)
) ENGINE=InnoDB AUTO_INCREMENT=75260 DEFAULT CHARSET=utf8;

/*Table structure for table `lobbylist` */

DROP TABLE IF EXISTS `lobbylist`;

CREATE TABLE `lobbylist` (
  `number` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(24) NOT NULL,
  `name` varchar(24) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL,
  `online` int(11) NOT NULL DEFAULT '1',
  `bottype` int(11) DEFAULT '0',
  `num` int(11) NOT NULL,
  `Rnum` int(11) DEFAULT '-1',
  `Channel` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`number`)
) ENGINE=InnoDB AUTO_INCREMENT=715 DEFAULT CHARSET=utf8;

/*Table structure for table `rooms` */

DROP TABLE IF EXISTS `rooms`;

CREATE TABLE `rooms` (
  `id` int(11) DEFAULT '0',
  `ip` varchar(20) NOT NULL,
  `port` int(5) NOT NULL DEFAULT '0',
  `number` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`number`)
) ENGINE=MyISAM AUTO_INCREMENT=9574 DEFAULT CHARSET=latin1;

/*Table structure for table `sector_E_log` */

DROP TABLE IF EXISTS `sector_E_log`;

CREATE TABLE `sector_E_log` (
  `nr` int(11) NOT NULL AUTO_INCREMENT,
  `level` int(11) DEFAULT NULL,
  `roommaster` text,
  `roomplayers` longtext,
  `time` text,
  `kills` int(11) DEFAULT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`nr`)
) ENGINE=MyISAM AUTO_INCREMENT=1831 DEFAULT CHARSET=utf8;

/*Table structure for table `sector_log` */

DROP TABLE IF EXISTS `sector_log`;

CREATE TABLE `sector_log` (
  `nr` int(11) NOT NULL AUTO_INCREMENT,
  `level` int(11) DEFAULT NULL,
  `roommaster` text,
  `roomplayers` longtext,
  `time` text,
  `kills` int(11) DEFAULT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`nr`)
) ENGINE=MyISAM AUTO_INCREMENT=170201 DEFAULT CHARSET=utf8;

/*Table structure for table `stashes` */

DROP TABLE IF EXISTS `stashes`;

CREATE TABLE `stashes` (
  `number` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `stas1` int(11) NOT NULL DEFAULT '0',
  `stas2` int(11) NOT NULL DEFAULT '0',
  `stas3` int(11) NOT NULL DEFAULT '0',
  `stas4` int(11) NOT NULL DEFAULT '0',
  `stas5` int(11) NOT NULL DEFAULT '0',
  `stas6` int(11) NOT NULL DEFAULT '0',
  `stas7` int(11) NOT NULL DEFAULT '0',
  `stas8` int(11) NOT NULL DEFAULT '0',
  `stas9` int(11) NOT NULL DEFAULT '0',
  `stas10` int(11) NOT NULL DEFAULT '0',
  `stashnr` int(11) NOT NULL,
  PRIMARY KEY (`number`)
) ENGINE=InnoDB AUTO_INCREMENT=931 DEFAULT CHARSET=utf8;

/*Table structure for table `trade` */

DROP TABLE IF EXISTS `trade`;

CREATE TABLE `trade` (
  `number` int(11) NOT NULL AUTO_INCREMENT,
  `name1` varchar(16) NOT NULL,
  `name2` varchar(16) NOT NULL,
  `item1` int(11) DEFAULT '-1',
  `item2` int(11) DEFAULT '-1',
  `item3` int(11) DEFAULT '-1',
  `item4` int(11) DEFAULT '-1',
  `item5` int(11) DEFAULT '-1',
  `item6` int(11) DEFAULT '-1',
  `gigas1` int(11) DEFAULT '0',
  `gigas2` int(11) DEFAULT '0',
  `bs1` int(11) DEFAULT '0',
  `bs2` int(11) DEFAULT '0',
  `status1` int(11) DEFAULT '0',
  `status2` int(11) DEFAULT '0',
  PRIMARY KEY (`number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
