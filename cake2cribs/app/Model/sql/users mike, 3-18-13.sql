-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 19, 2013 at 09:24 PM
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
-- Table structure for table 'users'
--

CREATE TABLE users (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  username varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  first_name varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  last_name varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  email varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  phone varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  group_id int(11) DEFAULT NULL,
  university_id int(11) NOT NULL,
  verified tinyint(1) DEFAULT NULL,
  vericode varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  created datetime DEFAULT NULL,
  modified datetime DEFAULT NULL,
  password_reset_token varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  password_reset_date datetime NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=25 ;

--
-- Dumping data for table 'users'
--

INSERT INTO users (id, username, `password`, first_name, last_name, email, phone, group_id, university_id, verified, vericode, created, modified, password_reset_token, password_reset_date) VALUES
(1, 'testt', 'testtest', 'Michael', 'Schmatz', 'tester@umich.edu', '2489744572', NULL, 0, NULL, '', '2013-02-04 05:08:14', '2013-02-04 05:08:14', '', '0000-00-00 00:00:00'),
(2, 'test2', 'testtest', 'test', 'test', 'test@test.com', '2489744572', NULL, 0, NULL, '', '2013-02-04 05:16:58', '2013-02-04 05:16:58', '', '0000-00-00 00:00:00'),
(3, 'test3', '37f18954b5498f536755ced3e5a7f6b4f2dbcce7', 'test', 'test', 'test@test.com', '2489744752', NULL, 0, NULL, '', '2013-02-04 05:21:54', '2013-02-04 05:21:54', '', '0000-00-00 00:00:00'),
(4, 'test4', '4c9300979fff8917614c63ff6039e7ad315cda71', 'test', 'test', 'test@test.com', '2489744572', NULL, 0, NULL, '', '2013-02-04 05:22:37', '2013-02-04 05:22:37', '', '0000-00-00 00:00:00'),
(5, 'test5', '4c9300979fff8917614c63ff6039e7ad315cda71', 'test', 'test', 'test@umich.edu', '2489744572', NULL, 0, NULL, '', '2013-02-04 05:43:26', '2013-02-04 05:43:26', '', '0000-00-00 00:00:00'),
(6, 'test6', '4c9300979fff8917614c63ff6039e7ad315cda71', 'test', 'test', 'test@test.io', '2489744573', 1, 0, 0, '', '2013-02-04 05:58:40', '2013-02-04 05:58:40', '', '0000-00-00 00:00:00'),
(7, 'test7', '4c9300979fff8917614c63ff6039e7ad315cda71', 'test', 'test', 'test2@test.com', '2489744555', 1, 0, 1, '510f54a87d2f9', '2013-02-04 06:26:48', '2013-02-04 20:54:18', '', '0000-00-00 00:00:00'),
(15, 'schmatz', '37f18954b5498f536755ced3e5a7f6b4f2dbcce7', 'Michael', 'Schmatz', 'schmatz@umich.edu', '2489744570', 1, 1, 1, '512a8909a2914', '2013-02-24 21:41:29', '2013-02-24 21:43:42', '', '0000-00-00 00:00:00'),
(17, 'archer', '39b903eff3c4a1e01b2fd7805cb145fd8ca4176e', 'Sterling', 'Archer', 'archer@gmail.com', '7348837492', 1, 3943, 1, '512c2bdd7798f', '2013-02-26 04:28:29', '2013-02-26 04:28:29', '', '0000-00-00 00:00:00'),
(18, 'nigel', '39b903eff3c4a1e01b2fd7805cb145fd8ca4176e', 'nigel', 'thornberry', 't@gmail.com', '7348837151', 1, 2, 0, '512c2c7e8aa58', '2013-02-26 04:31:10', '2013-02-26 04:31:10', '', '0000-00-00 00:00:00'),
(19, 'michael', '39b903eff3c4a1e01b2fd7805cb145fd8ca4176e', 'Mike', 'Stratman', 'mikenike192@gmail.com', '7348837499', 1, 3943, 1, '512d01a7b256a', '2013-02-26 19:40:39', '2013-03-19 21:15:02', '', '0000-00-00 00:00:00');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
