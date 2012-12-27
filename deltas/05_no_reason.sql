# ************************************************************
# Sequel Pro SQL dump
# Version 3408
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.5.28)
# Database: wrc
# Generation Time: 2012-12-27 17:26:26 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table forecasts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `forecasts`;

CREATE TABLE `forecasts` (
  `forecast_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `source_id` int(11) NOT NULL,
  `date_prediction` date NOT NULL,
  `date_3_day` date NOT NULL,
  `location_id` int(11) NOT NULL,
  `temp_hi` int(11) NOT NULL,
  `temp_lo` int(11) NOT NULL,
  `pop` int(11) NOT NULL,
  PRIMARY KEY (`forecast_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `forecasts` WRITE;
/*!40000 ALTER TABLE `forecasts` DISABLE KEYS */;

INSERT INTO `forecasts` (`forecast_id`, `source_id`, `date_prediction`, `date_3_day`, `location_id`, `temp_hi`, `temp_lo`, `pop`)
VALUES
	(1,2,'2012-12-27','2012-12-30',1,32,24,10),
	(2,2,'2012-12-27','2012-12-30',2,61,44,0),
	(3,2,'2012-12-27','2012-12-30',3,31,29,20),
	(4,2,'2012-12-27','2012-12-30',4,58,50,0),
	(5,3,'2012-12-27','2012-12-30',1,36,27,0),
	(6,3,'2012-12-27','2012-12-30',2,62,44,0),
	(7,3,'2012-12-27','2012-12-30',3,28,24,0),
	(8,3,'2012-12-27','2012-12-30',4,58,47,0);

/*!40000 ALTER TABLE `forecasts` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table grades
# ------------------------------------------------------------

DROP TABLE IF EXISTS `grades`;

CREATE TABLE `grades` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `grade` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `source_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `grades` WRITE;
/*!40000 ALTER TABLE `grades` DISABLE KEYS */;

INSERT INTO `grades` (`id`, `date`, `grade`, `location_id`, `source_id`)
VALUES
	(1,'2012-12-10',50,1,1),
	(2,'2012-12-10',40,2,1),
	(3,'2012-12-10',42,3,1),
	(4,'2012-12-10',90,1,2),
	(5,'2012-12-10',80,2,2),
	(6,'2012-12-10',85,3,2);

/*!40000 ALTER TABLE `grades` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table location_codes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `location_codes`;

CREATE TABLE `location_codes` (
  `location_id` int(11) unsigned NOT NULL,
  `source_id` int(11) NOT NULL,
  `code` text NOT NULL,
  PRIMARY KEY (`location_id`,`source_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `location_codes` WRITE;
/*!40000 ALTER TABLE `location_codes` DISABLE KEYS */;

INSERT INTO `location_codes` (`location_id`, `source_id`, `code`)
VALUES
	(1,1,'KNYC.html'),
	(1,2,'10019'),
	(1,3,'new-york-ny/10019/daily-weather-forecast/3714_pc?day=4'),
	(2,1,'KCQT.html'),
	(2,2,'90037'),
	(2,3,'los-angeles-ca/90037/daily-weather-forecast/37870_pc?day=4'),
	(3,1,'KMDW.html'),
	(3,2,'60638'),
	(3,3,'chicago-il/60638/daily-weather-forecast/26492_pc?day=4'),
	(4,1,'KHOU.html'),
	(4,2,'77061'),
	(4,3,'houston-tx/77061/daily-weather-forecast/33526_pc?day=4'),
	(5,1,'KPHL.html'),
	(5,2,'19153'),
	(5,3,'philadelphia-pa/19153/daily-weather-forecast/7867_pc?day=4');

/*!40000 ALTER TABLE `location_codes` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table locations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `locations`;

CREATE TABLE `locations` (
  `location_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`location_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `locations` WRITE;
/*!40000 ALTER TABLE `locations` DISABLE KEYS */;

INSERT INTO `locations` (`location_id`, `name`)
VALUES
	(1,'New York, NY'),
	(2,'Los Angeles, CA'),
	(3,'Chicago, IL'),
	(4,'Houston, TX'),
	(5,'Philadelphia, PA');

/*!40000 ALTER TABLE `locations` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table results
# ------------------------------------------------------------

DROP TABLE IF EXISTS `results`;

CREATE TABLE `results` (
  `result_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `temp_hi` int(11) NOT NULL,
  `temp_lo` int(11) NOT NULL,
  `precipitation` int(11) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`result_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `results` WRITE;
/*!40000 ALTER TABLE `results` DISABLE KEYS */;

INSERT INTO `results` (`result_id`, `location_id`, `temp_hi`, `temp_lo`, `precipitation`, `date`)
VALUES
	(1,1,34,30,0,'2012-12-26'),
	(2,2,59,52,0,'2012-12-26'),
	(3,3,33,32,0,'2012-12-26'),
	(4,4,43,38,0,'2012-12-26');

/*!40000 ALTER TABLE `results` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table sources
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sources`;

CREATE TABLE `sources` (
  `source_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `scrape_url` text NOT NULL,
  PRIMARY KEY (`source_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `sources` WRITE;
/*!40000 ALTER TABLE `sources` DISABLE KEYS */;

INSERT INTO `sources` (`source_id`, `name`, `scrape_url`)
VALUES
	(1,'National Weather Service','http://w1.weather.gov/data/obhistory/'),
	(2,'Weather Channel','http://www.weather.com/weather/5-day/'),
	(3,'AccuWeather','http://www.accuweather.com/en/us/');

/*!40000 ALTER TABLE `sources` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
