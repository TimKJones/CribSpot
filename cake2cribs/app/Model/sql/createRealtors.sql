-- phpMyAdmin SQL Dump
-- version 3.5.2
-- http://www.phpmyadmin.net
--
-- Host: us01-user01.crtks9njytxu.us-east-1.rds.amazonaws.com
-- Generation Time: Feb 13, 2013 at 08:27 PM
-- Server version: 5.5.27-log
-- PHP Version: 5.3.10-1ubuntu3.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `d3cd8099c6fdd4bffac166af5f7f0a4e9`
--

-- --------------------------------------------------------

--
-- Table structure for table `realtors`
--

CREATE TABLE IF NOT EXISTS `realtors` (
  `realtor_id` int(11) NOT NULL AUTO_INCREMENT,
  `company` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`realtor_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4145 ;

--
-- Dumping data for table `realtors`
--

INSERT INTO `realtors` (`realtor_id`, `company`, `username`, `password`, `email`) VALUES
(4097, 'Murphy Property', '\\N', '\\N', '\\N'),
(4098, 'Allmand', '\\N', '\\N', '\\N'),
(4099, 'American Campus', '\\N', '\\N', '\\N'),
(4100, 'Arbor ', '\\N', '\\N', '\\N'),
(4101, 'Arbor Stone ', '\\N', '\\N', '\\N'),
(4102, 'Arch Realty', '\\N', '\\N', '\\N'),
(4103, 'Bartonbrook', '\\N', '\\N', '\\N'),
(4104, 'Cabrio Properties', '\\N', '\\N', '\\N'),
(4105, 'Campus Management', '\\N', '\\N', '\\N'),
(4106, 'Campus Realty', '\\N', '\\N', '\\N'),
(4107, 'Cappo/Deinco', '\\N', '\\N', '\\N'),
(4108, 'CareOne', '\\N', '\\N', '\\N'),
(4109, 'Carlson Properties', '\\N', '\\N', '\\N'),
(4110, 'CMB', '\\N', '\\N', '\\N'),
(4111, 'Copi Properties', '\\N', '\\N', '\\N'),
(4112, 'Gottschalk', '\\N', '\\N', '\\N'),
(4113, 'Gruber', '\\N', '\\N', '\\N'),
(4114, 'Hill Street', '\\N', '\\N', '\\N'),
(4115, 'Huron Tower Apts.', '\\N', '\\N', '\\N'),
(4116, 'IPM', '\\N', '\\N', '\\N'),
(4117, 'J. Keller Properties', '\\N', '\\N', '\\N'),
(4118, 'Jaeger Properties', '\\N', '\\N', '\\N'),
(4119, 'JMS  ', '\\N', '\\N', '\\N'),
(4120, 'Jones ', '\\N', '\\N', '\\N'),
(4121, 'Keys Management', '\\N', '\\N', '\\N'),
(4122, 'Madison Company', '\\N', '\\N', '\\N'),
(4123, 'Metro Property ', '\\N', '\\N', '\\N'),
(4124, 'Michigan Rental', '\\N', '\\N', '\\N'),
(4125, 'Old Town Realty', '\\N', '\\N', '\\N'),
(4126, 'Oppenheimer  ', '\\N', '\\N', '\\N'),
(4127, 'Peppers Properties', '\\N', '\\N', '\\N'),
(4128, 'Pine Valley', '\\N', '\\N', '\\N'),
(4129, 'PMSI', '\\N', '\\N', '\\N'),
(4130, 'Prime Housing', '\\N', '\\N', '\\N'),
(4131, 'Smiley Properties', '\\N', '\\N', '\\N'),
(4132, 'Sterling University', '\\N', '\\N', '\\N'),
(4133, 'Tree City ', '\\N', '\\N', '\\N'),
(4134, 'U. of Michigan', '\\N', '\\N', '\\N'),
(4135, 'University Places', '\\N', '\\N', '\\N'),
(4136, 'University Towers ', '\\N', '\\N', '\\N'),
(4137, 'Varsity ', '\\N', '\\N', '\\N'),
(4138, 'Wessinger Properties', '\\N', '\\N', '\\N'),
(4139, 'Willowtree Apts.', '\\N', '\\N', '\\N'),
(4140, 'Wilson White Company', '\\N', '\\N', '\\N'),
(4141, 'Zaragon ', '\\N', '\\N', '\\N'),
(4142, 'Panhellenic', '\\N', '\\N', '\\N'),
(4143, 'Interfraternity', '\\N', '\\N', '\\N'),
(4144, 'Post Realty', '\\N', '\\N', '\\N');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
