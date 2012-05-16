-- phpMyAdmin SQL Dump
-- version 3.4.5deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 16, 2012 at 11:41 AM
-- Server version: 5.1.62
-- PHP Version: 5.3.6-13ubuntu3.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ltt2012`
--

-- --------------------------------------------------------

--
-- Table structure for table `bids`
--

CREATE TABLE IF NOT EXISTS `bids` (
  `bid_id` int(31) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(31) unsigned NOT NULL,
  `discount_id` int(31) unsigned NOT NULL,
  `price` int(31) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `extended_validity` int(31) unsigned NOT NULL DEFAULT '3600' COMMENT 'o kolko sekund sa prezdlzi platnost zlavy',
  `winning` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'ci je tento bid posledny a teda vyhral drazbu',
  PRIMARY KEY (`bid_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE IF NOT EXISTS `discounts` (
  `discount_id` int(31) NOT NULL AUTO_INCREMENT,
  `title` varchar(127) COLLATE utf8_slovak_ci NOT NULL,
  `asking_price` int(31) NOT NULL COMMENT 'vyvolavacia cena',
  `timestamp_start` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'cas, kedy odstartovala drazba',
  PRIMARY KEY (`discount_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(31) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8_slovak_ci NOT NULL,
  `password` varchar(31) COLLATE utf8_slovak_ci NOT NULL,
  `first_name` varchar(31) COLLATE utf8_slovak_ci NOT NULL,
  `last_name` varchar(31) COLLATE utf8_slovak_ci NOT NULL,
  `nick` varchar(31) COLLATE utf8_slovak_ci NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_accesses`
--

CREATE TABLE IF NOT EXISTS `user_accesses` (
  `user_access_id` int(31) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(31) unsigned NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `url` varchar(127) COLLATE utf8_slovak_ci NOT NULL,
  PRIMARY KEY (`user_access_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
