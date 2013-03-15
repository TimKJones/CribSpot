CREATE TABLE IF NOT EXISTS users (
  user_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  username varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  password varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  first_name varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  last_name varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  email varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  phone varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  group_id int(11) DEFAULT NULL,
  university_id int(11) NOT NULL,
  verified tinyint(1) DEFAULT NULL,
  vericode varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  facebook_userid INTEGER,
  twitter_userid INTEGER,
  twitter_auth_token VARCHAR(255),
  twitter_auth_token_secret VARCHAR(255),
  linkedin_verified boolean,
  created datetime DEFAULT NULL,
  modified datetime DEFAULT NULL,
  PRIMARY KEY (user_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=15 ;

--
-- Dumping data for table `users`
--

INSERT INTO users VALUES
(1, 'testt', 'testtest', 'Michael', 'Schmatz', 'tester@umich.edu', '2489744572', NULL, 0, NULL, '', 0, 0, '0', '0', false, '2013-02-04 05:08:14', '2013-02-04 05:08:14'),
(7, 'test7', '4c9300979fff8917614c63ff6039e7ad315cda71', 'test', 'test', 'test2@test.com', '2489744555', 1, 0, 1, '510f54a87d2f9', 0, 0, '0', '0', false, '2013-02-04 06:26:48', '2013-02-04 20:54:18'),
(14, 'schmatz', '37f18954b5498f536755ced3e5a7f6b4f2dbcce7', 'Michael', 'Schmatz', 'schmatz@umich.edu', '2489744570', 1, 1, 0, '5125afc990f30', 0, 0, '0', '0', false, '2013-02-21 05:25:29', '2013-02-21 05:25:29');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 15, 2013 at 02:52 AM
-- Server version: 5.5.25
-- PHP Version: 5.4.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
