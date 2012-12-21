-- phpMyAdmin SQL Dump
-- version 3.3.2deb1ubuntu1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 21, 2012 at 01:24 AM
-- Server version: 5.1.66
-- PHP Version: 5.3.2-1ubuntu4.18

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `cunyalerts`
--

-- --------------------------------------------------------

--
-- Table structure for table `Courses`
--

CREATE TABLE IF NOT EXISTS `Courses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `schoolId` int(11) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `credits` double DEFAULT NULL,
  `hours` double DEFAULT NULL,
  `division` varchar(100) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `schoolId` (`schoolId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `Schools`
--

CREATE TABLE IF NOT EXISTS `Schools` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shortName` varchar(25) DEFAULT NULL,
  `longName` varchar(300) DEFAULT NULL,
  `street` varchar(125) DEFAULT NULL,
  `city` varchar(125) DEFAULT NULL,
  `state` varchar(2) DEFAULT NULL,
  `zip` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `Seats`
--

CREATE TABLE IF NOT EXISTS `Seats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sectionId` int(11) NOT NULL,
  `seats` int(11) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `sectionId` (`sectionId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `Sections`
--

CREATE TABLE IF NOT EXISTS `Sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `termId` int(11) NOT NULL,
  `courseId` int(11) NOT NULL,
  `name` varchar(6) DEFAULT NULL,
  `code` varchar(8) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `termId` (`termId`),
  KEY `courseId` (`courseId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `SectionTimes`
--

CREATE TABLE IF NOT EXISTS `SectionTimes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sectionId` int(11) NOT NULL,
  `days` varchar(10) DEFAULT NULL,
  `startTime` time DEFAULT NULL,
  `endTime` time DEFAULT NULL,
  `instructor` varchar(150) DEFAULT NULL,
  `room` varchar(50) DEFAULT NULL,
  `online` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sectionId` (`sectionId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `Terms`
--

CREATE TABLE IF NOT EXISTS `Terms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `termCode` varchar(10) DEFAULT NULL,
  `schoolId` int(11) NOT NULL,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `schoolId` (`schoolId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE IF NOT EXISTS `Users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` binary(40) NOT NULL,
  `email` varchar(200) DEFAULT NULL,
  `smsNumber` varchar(20) DEFAULT NULL,
  `facebookId` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `UserSections`
--

CREATE TABLE IF NOT EXISTS `UserSections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `sectionId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sectionId` (`sectionId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
