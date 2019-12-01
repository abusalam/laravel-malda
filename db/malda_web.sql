-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 29, 2019 at 09:22 AM
-- Server version: 5.7.24
-- PHP Version: 7.2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `malda_web`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_case_details`
--

CREATE TABLE `tbl_case_details` (
  `code` int(11) NOT NULL,
  `case_no` varchar(11) NOT NULL,
  `nxt_hearing_date` date NOT NULL,
  `description` varchar(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_case_details`
--

INSERT INTO `tbl_case_details` (`code`, `case_no`, `nxt_hearing_date`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Case2', '2019-11-30', 'Nothing', '2019-11-29 09:19:14', '2019-11-29 09:19:14');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_grievence_forwored`
--

CREATE TABLE `tbl_grievence_forwored` (
  `code` int(11) NOT NULL,
  `griv_code` int(11) NOT NULL,
  `to_forword` int(11) NOT NULL,
  `from_forword` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_grievence_forwored`
--

INSERT INTO `tbl_grievence_forwored` (`code`, `griv_code`, `to_forword`, `from_forword`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 1, '2019-11-29 09:20:28', '2019-11-29 09:20:28');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_grivense`
--

CREATE TABLE `tbl_grivense` (
  `code` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `mobile_no` varchar(10) NOT NULL,
  `email` varchar(30) NOT NULL,
  `complain` longtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_grivense`
--

INSERT INTO `tbl_grivense` (`code`, `name`, `mobile_no`, `email`, `complain`, `created_at`, `updated_at`) VALUES
(1, 'Santu', '7501386334', 'p@b.com', 'Complain1', '2019-11-29 09:20:15', '2019-11-29 09:20:15');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_mobile_verify`
--

CREATE TABLE `tbl_mobile_verify` (
  `code` int(10) NOT NULL,
  `mobile_no` varchar(20) NOT NULL,
  `otp` varchar(30) NOT NULL,
  `status_otp` int(10) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_mobile_verify`
--

INSERT INTO `tbl_mobile_verify` (`code`, `mobile_no`, `otp`, `status_otp`, `created_at`, `updated_at`) VALUES
(1, '7980417810', '7651', 0, '2019-11-29 08:13:21', '2019-11-29 08:13:21'),
(2, '7980417810', '4435', 0, '2019-11-29 09:17:55', '2019-11-29 09:17:55'),
(3, '7980417810', '1289', 0, '2019-11-29 09:18:10', '2019-11-29 09:18:10'),
(4, '7501386334', '1714', 0, '2019-11-29 09:19:55', '2019-11-29 09:19:55'),
(5, '7501386334', '4300', 0, '2019-11-29 09:20:47', '2019-11-29 09:20:47'),
(6, '7501386334', '4183', 0, '2019-11-29 09:21:04', '2019-11-29 09:21:04');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `code` int(11) NOT NULL,
  `mobile_no` varchar(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `designation` varchar(150) NOT NULL,
  `user_type` int(5) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`code`, `mobile_no`, `name`, `designation`, `user_type`, `created_at`, `updated_at`) VALUES
(1, '7980417810', 'Admin', 'Developer', 0, NULL, NULL),
(2, '7501386334', 'Santu Basuri', 'Tester', 1, '2019-11-29 08:13:47', '2019-11-29 08:14:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_case_details`
--
ALTER TABLE `tbl_case_details`
  ADD PRIMARY KEY (`code`);

--
-- Indexes for table `tbl_grievence_forwored`
--
ALTER TABLE `tbl_grievence_forwored`
  ADD PRIMARY KEY (`code`);

--
-- Indexes for table `tbl_grivense`
--
ALTER TABLE `tbl_grivense`
  ADD PRIMARY KEY (`code`);

--
-- Indexes for table `tbl_mobile_verify`
--
ALTER TABLE `tbl_mobile_verify`
  ADD PRIMARY KEY (`code`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_case_details`
--
ALTER TABLE `tbl_case_details`
  MODIFY `code` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_grievence_forwored`
--
ALTER TABLE `tbl_grievence_forwored`
  MODIFY `code` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_grivense`
--
ALTER TABLE `tbl_grivense`
  MODIFY `code` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_mobile_verify`
--
ALTER TABLE `tbl_mobile_verify`
  MODIFY `code` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `code` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
