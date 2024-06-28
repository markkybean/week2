-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 28, 2024 at 04:26 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `classroom`
--

-- --------------------------------------------------------

--
-- Table structure for table `fetchers`
--

CREATE TABLE `fetchers` (
  `id` int(11) NOT NULL,
  `fetcher_code` int(11) NOT NULL,
  `fetcher_name` varchar(255) NOT NULL,
  `contact_no` varchar(20) DEFAULT NULL,
  `register_date` date NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fetchers`
--

INSERT INTO `fetchers` (`id`, `fetcher_code`, `fetcher_name`, `contact_no`, `register_date`, `status`) VALUES
(27, 1, 'Tangol', '09485726456', '2024-06-27', 1),
(28, 2, 'Baby Giant', '09348572845', '2024-06-27', 0),
(29, 3, 'Roda', '09473857462', '2024-06-26', 1),
(30, 4, 'Maui', '09457684932', '2024-06-24', 1),
(31, 5, 'Cardo', '09485736548', '2024-06-28', 0);

-- --------------------------------------------------------

--
-- Table structure for table `fetchers_students`
--

CREATE TABLE `fetchers_students` (
  `id` int(11) NOT NULL,
  `fetcher_code` int(11) DEFAULT NULL,
  `studentcode` int(11) DEFAULT NULL,
  `relationship` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fetchers_students`
--

INSERT INTO `fetchers_students` (`id`, `fetcher_code`, `studentcode`, `relationship`) VALUES
(32, 1, 201910433, 'Guardian'),
(33, 1, 20193323, 'Father'),
(34, 1, 25032455, 'Uncle'),
(35, 1, 201910434, 'Brother'),
(36, 1, 20193324, 'Brother'),
(37, 1, 25032456, 'Best Friend'),
(38, 2, 201910433, 'Guardian'),
(39, 2, 20193323, 'Friend'),
(40, 2, 202010435, 'Kaibigan'),
(41, 2, 25032457, 'Brother'),
(42, 2, 201910435, 'Father'),
(43, 3, 201910433, 'Kumare'),
(44, 3, 201910434, 'Kumpare'),
(45, 3, 201910435, 'Mother'),
(46, 3, 20193323, 'Father'),
(47, 3, 20193324, 'Sister'),
(48, 3, 25032455, 'Uncle'),
(49, 3, 20193325, 'Auntie'),
(50, 4, 201910433, 'Guardian'),
(51, 4, 20193323, 'Father'),
(52, 4, 25032455, 'Kaibigan'),
(53, 4, 201910434, 'Brother'),
(54, 4, 20193325, 'Sister'),
(55, 4, 20193324, 'Uncle'),
(56, 4, 201910435, 'Auntie'),
(57, 4, 202010435, 'Test'),
(58, 4, 25032456, 'Test'),
(59, 4, 25032457, 'Test'),
(60, 4, 201910436, 'Test'),
(61, 5, 201910433, 'Guardian'),
(62, 5, 20193323, 'Father'),
(63, 5, 25032455, 'Uncle'),
(64, 5, 201910434, 'Brother'),
(65, 5, 20193324, 'Brother');

-- --------------------------------------------------------

--
-- Table structure for table `studentfile`
--

CREATE TABLE `studentfile` (
  `recid` int(11) NOT NULL,
  `studentcode` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `studentfile`
--

INSERT INTO `studentfile` (`recid`, `studentcode`, `fullname`) VALUES
(1, 201910433, 'Mark Santos'),
(2, 20193323, 'Peter'),
(3, 25032455, 'Parker'),
(4, 201910434, 'John Doe'),
(5, 20193324, 'Jane Smith'),
(6, 25032456, 'Alice Johnson'),
(7, 201910435, 'Bob Brown'),
(8, 20193325, 'Charlie Davis'),
(9, 25032457, 'Eve White'),
(10, 201910436, 'Frank Green'),
(11, 202010435, 'Mariel Bagunas');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `fetchers`
--
ALTER TABLE `fetchers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_fetcher_code` (`fetcher_code`);

--
-- Indexes for table `fetchers_students`
--
ALTER TABLE `fetchers_students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fetcher_code` (`fetcher_code`),
  ADD KEY `studentcode` (`studentcode`);

--
-- Indexes for table `studentfile`
--
ALTER TABLE `studentfile`
  ADD PRIMARY KEY (`recid`),
  ADD KEY `idx_student_code` (`studentcode`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `fetchers`
--
ALTER TABLE `fetchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `fetchers_students`
--
ALTER TABLE `fetchers_students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `studentfile`
--
ALTER TABLE `studentfile`
  MODIFY `recid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `fetchers_students`
--
ALTER TABLE `fetchers_students`
  ADD CONSTRAINT `fetchers_students_ibfk_1` FOREIGN KEY (`fetcher_code`) REFERENCES `fetchers` (`fetcher_code`),
  ADD CONSTRAINT `fetchers_students_ibfk_2` FOREIGN KEY (`studentcode`) REFERENCES `studentfile` (`studentcode`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
