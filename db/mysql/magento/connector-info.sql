-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 28, 2012 at 06:21 AM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `magento`
--
use magento;
-- --------------------------------------------------------

--
-- Table structure for table `sohyper_event_connectors`
--

DELETE FROM `sohyper_event_connectors` where connectorid=6;
INSERT INTO `sohyper_event_connectors` (`connectorid`, `connectorname`, `api_key`, `api_secret_key`, `connectorimage`, `api_active`) VALUES
(6, 'Linkedin', 'r1vi65ap0wfn', 'RaXDe1SRROdfF4q3', 'linkedin.jpg', 'yes');

-- --------------------------------------------------------
-- --------------------------------------------------------

