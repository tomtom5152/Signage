-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 19, 2013 at 12:14 PM
-- Server version: 5.5.29
-- PHP Version: 5.3.10-1ubuntu3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ci_signage`
--

-- --------------------------------------------------------

--
-- Table structure for table `signage_content`
--

CREATE TABLE IF NOT EXISTS `signage_content` (
  `idcontent` int(11) NOT NULL AUTO_INCREMENT,
  `idfeed` int(11) NOT NULL,
  `content` text NOT NULL,
  `content_type` varchar(6) NOT NULL,
  `duration` int(3) NOT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  `start` date NOT NULL,
  `end` date NOT NULL,
  `iduser` int(11) NOT NULL,
  PRIMARY KEY (`idcontent`),
  KEY `idfeeds` (`idfeed`),
  KEY `idusers` (`iduser`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=113 ;

-- --------------------------------------------------------

--
-- Table structure for table `signage_feed`
--

CREATE TABLE IF NOT EXISTS `signage_feed` (
  `idfeed` int(11) NOT NULL AUTO_INCREMENT,
  `feedname` varchar(150) NOT NULL,
  `idgroup` int(11) NOT NULL,
  PRIMARY KEY (`idfeed`),
  KEY `idgroup` (`idgroup`),
  KEY `idgroup_2` (`idgroup`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `signage_group`
--

CREATE TABLE IF NOT EXISTS `signage_group` (
  `idgroup` int(11) NOT NULL AUTO_INCREMENT,
  `groupname` varchar(150) NOT NULL,
  PRIMARY KEY (`idgroup`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `signage_group_membership`
--

CREATE TABLE IF NOT EXISTS `signage_group_membership` (
  `idgroupmembership` int(11) NOT NULL AUTO_INCREMENT,
  `idgroup` int(11) NOT NULL,
  `iduser` int(11) NOT NULL,
  PRIMARY KEY (`idgroupmembership`),
  KEY `idgroup` (`idgroup`),
  KEY `iduser` (`iduser`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Table structure for table `signage_screen`
--

CREATE TABLE IF NOT EXISTS `signage_screen` (
  `idscreen` int(11) NOT NULL AUTO_INCREMENT,
  `screenname` varchar(80) DEFAULT NULL,
  `location` varchar(80) DEFAULT NULL,
  `hash` varchar(32) NOT NULL,
  PRIMARY KEY (`idscreen`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `signage_screen_feed`
--

CREATE TABLE IF NOT EXISTS `signage_screen_feed` (
  `idscreenfeed` int(11) NOT NULL AUTO_INCREMENT,
  `idscreen` int(11) NOT NULL,
  `idfeed` int(11) NOT NULL,
  PRIMARY KEY (`idscreenfeed`),
  KEY `idscreen` (`idscreen`),
  KEY `idfeed` (`idfeed`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

--
-- Table structure for table `signage_user`
--

CREATE TABLE IF NOT EXISTS `signage_user` (
  `iduser` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(128) NOT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `isAdmin` smallint(1) NOT NULL,
  PRIMARY KEY (`iduser`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `signage_content`
--
ALTER TABLE `signage_content`
  ADD CONSTRAINT `signage_content_ibfk_2` FOREIGN KEY (`idfeed`) REFERENCES `signage_feed` (`idfeed`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `signage_content_ibfk_3` FOREIGN KEY (`iduser`) REFERENCES `signage_user` (`iduser`) ON UPDATE CASCADE;

--
-- Constraints for table `signage_feed`
--
ALTER TABLE `signage_feed`
  ADD CONSTRAINT `signage_feed_ibfk_1` FOREIGN KEY (`idgroup`) REFERENCES `signage_group` (`idgroup`) ON UPDATE CASCADE;

--
-- Constraints for table `signage_group_membership`
--
ALTER TABLE `signage_group_membership`
  ADD CONSTRAINT `signage_group_membership_ibfk_1` FOREIGN KEY (`idgroup`) REFERENCES `signage_group` (`idgroup`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `signage_group_membership_ibfk_2` FOREIGN KEY (`iduser`) REFERENCES `signage_user` (`iduser`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `signage_screen_feed`
--
ALTER TABLE `signage_screen_feed`
  ADD CONSTRAINT `signage_screen_feed_ibfk_1` FOREIGN KEY (`idscreen`) REFERENCES `signage_screen` (`idscreen`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `signage_screen_feed_ibfk_2` FOREIGN KEY (`idfeed`) REFERENCES `signage_feed` (`idfeed`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
