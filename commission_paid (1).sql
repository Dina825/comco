-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 14, 2020 at 01:04 PM
-- Server version: 10.1.25-MariaDB
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `comco-db`
--

-- --------------------------------------------------------

--
-- Table structure for table `commission_paid`
--

CREATE TABLE `commission_paid` (
  `paid_id` int(11) NOT NULL,
  `area` varchar(1000) NOT NULL,
  `route` varchar(1000) NOT NULL,
  `salesrep` varchar(1000) NOT NULL,
  `shop_id` varchar(1000) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `commission` varchar(1000) NOT NULL,
  `bonus` varchar(1000) NOT NULL,
  `cheque_number` varchar(1000) NOT NULL,
  `order_id` varchar(1000) NOT NULL,
  `given_type` int(11) NOT NULL,
  `given_date` date NOT NULL,
  `given_time` time NOT NULL,
  `return_received_date` date NOT NULL,
  `return_received_time` time NOT NULL,
  `status` int(11) NOT NULL,
  `updatetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `commission_paid`
--

INSERT INTO `commission_paid` (`paid_id`, `area`, `route`, `salesrep`, `shop_id`, `type`, `description`, `date`, `time`, `commission`, `bonus`, `cheque_number`, `order_id`, `given_type`, `given_date`, `given_time`, `return_received_date`, `return_received_time`, `status`, `updatetime`) VALUES
(1, '7', '44', '4', '1559', 2, 'Inhand orders for Account Comco -3G', '2020-07-13', '07:41:00', '1830', '244', '', '2', 3, '2020-07-13', '07:41:00', '0000-00-00', '00:00:00', 4, '2020-07-13 06:41:16'),
(2, '5', '10', '4', '1067', 1, 'Admin release cheque', '2020-07-14', '06:58:00', '10', '', 'tfft', '', 0, '0000-00-00', '00:00:00', '0000-00-00', '00:00:00', 0, '2020-07-14 05:58:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `commission_paid`
--
ALTER TABLE `commission_paid`
  ADD PRIMARY KEY (`paid_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `commission_paid`
--
ALTER TABLE `commission_paid`
  MODIFY `paid_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
