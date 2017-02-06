-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 06, 2017 at 04:52 PM
-- Server version: 5.5.54-0+deb8u1-log
-- PHP Version: 5.6.29-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `project_041216`
--

DELIMITER $$
--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `SPLIT_STR`(
x VARCHAR(255),
delim VARCHAR(12),
pos INT
) RETURNS varchar(255) CHARSET utf8
RETURN REPLACE(SUBSTRING(SUBSTRING_INDEX(x, delim, pos),
CHAR_LENGTH(SUBSTRING_INDEX(x, delim, pos -1)) + 1),
delim, '')$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
`id` int(11) NOT NULL,
  `start_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `text` varchar(255) NOT NULL,
  `program` varchar(255) NOT NULL,
  `format` varchar(255) NOT NULL,
  `remark` varchar(255) NOT NULL,
  `rec_type` varchar(100) NOT NULL,
  `event_length` bigint(20) NOT NULL,
  `event_pid` int(11) NOT NULL,
  `order_ref` varchar(20) NOT NULL,
  `bp_code` varchar(20) NOT NULL,
  `duration1` int(11) NOT NULL,
  `sframe` int(11) NOT NULL,
  `dframe` int(11) NOT NULL,
  `eframe` int(11) NOT NULL,
  `episode` varchar(100) NOT NULL,
  `segment` varchar(100) NOT NULL,
  `serial_type` varchar(255) NOT NULL,
  `asset` varchar(255) DEFAULT NULL,
  `input_type` varchar(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `createdby` varchar(100) NOT NULL,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updatedby` varchar(100) NOT NULL,
  `invoiced` tinyint(1) NOT NULL,
  `rate_agreement` int(11) DEFAULT NULL,
  `rateAgreement_Line` int(11) DEFAULT NULL,
  `ordered` tinyint(1) NOT NULL,
  `invoice_no` varchar(20) DEFAULT NULL,
  `rateValidated` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=333714 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `events_lshape`
--

CREATE TABLE IF NOT EXISTS `events_lshape` (
`id` int(11) NOT NULL,
  `start_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `text` varchar(255) NOT NULL,
  `program` varchar(255) NOT NULL,
  `format` varchar(255) NOT NULL,
  `remark` varchar(255) NOT NULL,
  `rec_type` varchar(100) NOT NULL,
  `event_length` bigint(20) NOT NULL,
  `event_pid` int(11) NOT NULL,
  `order_ref` varchar(20) NOT NULL,
  `bp_code` varchar(20) NOT NULL,
  `duration1` int(11) NOT NULL,
  `sframe` int(11) NOT NULL,
  `dframe` int(11) NOT NULL,
  `eframe` int(11) NOT NULL,
  `pgmName` varchar(255) NOT NULL,
  `segment` varchar(100) NOT NULL,
  `serial_type` varchar(255) NOT NULL,
  `asset` varchar(255) DEFAULT NULL,
  `input_type` varchar(11) NOT NULL,
  `pgm_id` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `createdby` varchar(100) DEFAULT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updatedby` varchar(100) DEFAULT NULL,
  `rate_agreement` int(11) DEFAULT NULL,
  `rateAgreement_Line` int(11) DEFAULT NULL,
  `ordered` tinyint(1) NOT NULL,
  `invoiced` tinyint(1) NOT NULL,
  `invoice_no` varchar(20) DEFAULT NULL,
  `episode` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=30298 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rate_agreement`
--

CREATE TABLE IF NOT EXISTS `rate_agreement` (
`id` int(11) NOT NULL,
  `rate_agreementNo` int(11) NOT NULL,
  `agent_code` varchar(100) NOT NULL,
  `agent_name` varchar(100) NOT NULL,
  `startDate` timestamp NULL DEFAULT NULL,
  `endDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `approveDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `rate_agreementLine_ID` int(11) NOT NULL,
  `timeslot` varchar(100) NOT NULL,
  `positionName` varchar(100) DEFAULT NULL,
  `priority` varchar(100) DEFAULT NULL,
  `program` varchar(150) DEFAULT NULL,
  `timeBand` varchar(50) DEFAULT NULL,
  `rate` double NOT NULL,
  `adType` varchar(100) NOT NULL,
  `episode_no` int(11) NOT NULL,
  `limit1` double NOT NULL,
  `type` varchar(20) NOT NULL,
  `lineStartDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lineEndDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=840 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `registration`
--

CREATE TABLE IF NOT EXISTS `registration` (
  `fname` varchar(60) DEFAULT NULL,
  `lname` varchar(60) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `email_id` varchar(80) DEFAULT NULL,
  `user_id` varchar(80) DEFAULT NULL,
  `password` varchar(80) DEFAULT NULL,
`reg_id` int(11) NOT NULL,
  `r_roleid` varchar(50) NOT NULL,
  `employee_id` int(20) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `registration`
--

INSERT INTO `registration` (`fname`, `lname`, `address`, `email_id`, `user_id`, `password`, `reg_id`, `r_roleid`, `employee_id`) VALUES
('Kazi', 'Farms', 'dhaka', 'info@sysnova.com', 'sysnova', '036ee4f2b510e8efdc0138936398ffec', 9, 'SUPERADMIN', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events_lshape`
--
ALTER TABLE `events_lshape`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rate_agreement`
--
ALTER TABLE `rate_agreement`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `registration`
--
ALTER TABLE `registration`
 ADD PRIMARY KEY (`reg_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=333714;
--
-- AUTO_INCREMENT for table `events_lshape`
--
ALTER TABLE `events_lshape`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=30298;
--
-- AUTO_INCREMENT for table `rate_agreement`
--
ALTER TABLE `rate_agreement`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=840;
--
-- AUTO_INCREMENT for table `registration`
--
ALTER TABLE `registration`
MODIFY `reg_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=45;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
