-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 25, 2013 at 07:24 PM
-- Server version: 5.5.25
-- PHP Version: 5.4.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: 'd3cd8099c6fdd4bffac166af5f7f0a4e9'
--

-- --------------------------------------------------------

--
-- Table structure for table 'conversations'
--

CREATE TABLE conversations (
  conversation_id int(11) NOT NULL AUTO_INCREMENT,
  sublet_id int(11) DEFAULT NULL,
  participant1_id int(11) DEFAULT NULL,
  visible1 tinyint(1) NOT NULL DEFAULT '1',
  participant2_id int(11) DEFAULT NULL,
  visible2 tinyint(1) NOT NULL DEFAULT '1',
  title varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  last_message_id int(11) DEFAULT NULL,
  created datetime DEFAULT NULL,
  modified datetime DEFAULT NULL,
  PRIMARY KEY (conversation_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=29 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
