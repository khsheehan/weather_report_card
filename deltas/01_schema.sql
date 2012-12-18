# ************************************************************
# Sequel Pro SQL dump
# Version 3408
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.5.28)
# Database: wrc
# Generation Time: 2012-12-18 17:01:14 +0000
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
  `date_prediction` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_3_day` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `location_id` int(11) NOT NULL,
  `temp_hi` int(11) NOT NULL,
  `temp_lo` int(11) NOT NULL,
  `pop` int(11) NOT NULL,
  PRIMARY KEY (`forecast_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table location_codes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `location_codes`;

CREATE TABLE `location_codes` (
  `location_id` int(11) unsigned NOT NULL,
  `soure_id` int(11) NOT NULL,
  `code` text NOT NULL,
  PRIMARY KEY (`location_id`,`soure_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table locations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `locations`;

CREATE TABLE `locations` (
  `location_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`location_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table results
# ------------------------------------------------------------

DROP TABLE IF EXISTS `results`;

CREATE TABLE `results` (
  `result_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `temp_hi` int(11) NOT NULL,
  `temp_lo` int(11) NOT NULL,
  `precipitation` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`result_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table sources
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sources`;

CREATE TABLE `sources` (
  `source_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `scrape_url` text NOT NULL,
  PRIMARY KEY (`source_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
