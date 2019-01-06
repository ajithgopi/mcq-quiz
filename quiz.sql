-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 13, 2018 at 04:08 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `quiz`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin', 'password123');

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE IF NOT EXISTS `answers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `regid` int(11) NOT NULL,
  `question` int(11) NOT NULL,
  `copt` int(11) NOT NULL,
  `score` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `exams`
--

CREATE TABLE IF NOT EXISTS `exams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(127) NOT NULL,
  `time_alloted` int(11) NOT NULL,
  `start_date` datetime NOT NULL,
  `nquestions` int(11) NOT NULL,
  `secured` tinyint(1) NOT NULL,
  `pos_mark` float NOT NULL,
  `neg_mark` float NOT NULL,
  `show_scores` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `exams`
--

INSERT INTO `exams` (`id`, `name`, `time_alloted`, `start_date`, `nquestions`, `secured`, `pos_mark`, `neg_mark`, `show_scores`) VALUES
(1, 'Default Exam', 30, '1970-01-01 05:30:00', 10, 1, 1, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `question_pool`
--

CREATE TABLE IF NOT EXISTS `question_pool` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `exam` int(11) NOT NULL,
  `question` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `opt_1` varchar(127) NOT NULL,
  `opt_2` varchar(127) NOT NULL,
  `opt_3` varchar(127) NOT NULL,
  `opt_4` varchar(127) NOT NULL,
  `right_opt` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `question_sets`
--

CREATE TABLE IF NOT EXISTS `question_sets` (
  `id` bigint(10) unsigned NOT NULL AUTO_INCREMENT,
  `exam` int(11) NOT NULL,
  `qcode` int(11) NOT NULL,
  `question` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `registrations`
--

CREATE TABLE IF NOT EXISTS `registrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `regcode` varchar(15) NOT NULL,
  `participants` varchar(100) NOT NULL,
  `institute` varchar(100) NOT NULL,
  `exam` int(11) NOT NULL,
  `date_registered` datetime NOT NULL,
  `date_completed` datetime NOT NULL,
  `qcode` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `active_exam` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `active_exam`) VALUES
(1, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
