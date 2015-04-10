-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 31, 2015 at 10:04 AM
-- Server version: 5.6.17-log
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `hotel`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE IF NOT EXISTS `booking` (
  `boo_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `boo_StartDate` datetime NOT NULL,
  `boo_EndDate` datetime NOT NULL,
  `sui_ID` int(10) unsigned NOT NULL,
  `ppl_ID` int(10) unsigned NOT NULL,
  `ppl_IDAgent` int(10) unsigned NOT NULL,
  PRIMARY KEY (`boo_ID`),
  KEY `sui_IDBook_idx` (`sui_ID`),
  KEY `ppl_IDClient_idx` (`ppl_ID`),
  KEY `ppl_IDBookAgent_idx` (`ppl_IDAgent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE IF NOT EXISTS `log` (
  `log_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_StartDate` datetime NOT NULL,
  `log_EndDate` datetime NOT NULL,
  `sui_ID` int(10) unsigned NOT NULL,
  `ppl_ID` int(10) unsigned NOT NULL,
  `ppl_IDAgent` int(10) unsigned NOT NULL,
  `log_Cancelled` tinyint(1) NOT NULL DEFAULT '0',
  `log_CheckedOut` datetime NOT NULL,
  PRIMARY KEY (`log_ID`),
  KEY `sui_ID_idx` (`sui_ID`),
  KEY `ppl_ID_idx` (`ppl_ID`),
  KEY `agentID_idx` (`ppl_IDAgent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE IF NOT EXISTS `payment` (
  `pay_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ppl_ID` int(10) unsigned NOT NULL,
  `pay_Amt` decimal(10,0) unsigned NOT NULL,
  PRIMARY KEY (`pay_ID`),
  KEY `clientID_idx` (`ppl_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `people`
--

CREATE TABLE IF NOT EXISTS `people` (
  `ppl_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ppl_LName` varchar(45) NOT NULL,
  `ppl_FName` varchar(45) NOT NULL,
  `ppl_Email` varchar(80) NOT NULL,
  `ppl_Password` varchar(32) NOT NULL,
  `ppl_Type` varchar(20) NOT NULL,
  `ppl_Balance` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ppl_ID`),
  UNIQUE KEY `ppl_Email_UNIQUE` (`ppl_Email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `suite`
--

CREATE TABLE IF NOT EXISTS `suite` (
  `sui_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sui_Name` varchar(45) NOT NULL,
  `sui_Available` int(11) NOT NULL DEFAULT '10',
  `sui_CPN` decimal(10,0) unsigned NOT NULL,
  PRIMARY KEY (`sui_ID`),
  UNIQUE KEY `sui_Name_UNIQUE` (`sui_Name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `sui_IDBook` FOREIGN KEY (`sui_ID`) REFERENCES `suite` (`sui_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `ppl_IDClient` FOREIGN KEY (`ppl_ID`) REFERENCES `people` (`ppl_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `ppl_IDBookAgent` FOREIGN KEY (`ppl_IDAgent`) REFERENCES `people` (`ppl_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `log`
--
ALTER TABLE `log`
  ADD CONSTRAINT `sui_ID` FOREIGN KEY (`sui_ID`) REFERENCES `suite` (`sui_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `ppl_ID` FOREIGN KEY (`ppl_ID`) REFERENCES `people` (`ppl_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `agentIDLog` FOREIGN KEY (`ppl_IDAgent`) REFERENCES `people` (`ppl_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `clientID` FOREIGN KEY (`ppl_ID`) REFERENCES `people` (`ppl_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
