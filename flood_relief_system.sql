-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 14, 2026 at 05:21 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `flood_relief_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `regions`
--

CREATE TABLE `regions` (
  `id` int(11) NOT NULL,
  `district_name` varchar(100) NOT NULL,
  `severity_level` enum('Low','Medium','High') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `regions`
--

INSERT INTO `regions` (`id`, `district_name`, `severity_level`) VALUES
(1, 'Colombo', 'Low'),
(2, 'Gampaha', 'Medium'),
(3, 'Kalutara', 'Low'),
(4, 'Galle', 'Low'),
(5, 'Ratnapura', 'High');

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `region_id` int(11) NOT NULL,
  `divisional_secretariat` varchar(100) NOT NULL,
  `gn_division` varchar(100) NOT NULL,
  `relief_type` enum('Food','Water','Medicine','Shelter') NOT NULL,
  `contact_person` varchar(100) NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `family_members` int(11) NOT NULL,
  `severity` enum('Low','Medium','High') NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('Pending','Delivered','Approved','Rejected') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`id`, `user_id`, `region_id`, `divisional_secretariat`, `gn_division`, `relief_type`, `contact_person`, `contact_number`, `address`, `family_members`, `severity`, `description`, `status`, `created_at`) VALUES
(2, NULL, 5, 'Colombo DS', 'GN-101', 'Food', 'Sunil Perera', '0771112223', '123 Main St, Colombo', 4, 'High', 'Food aid successfully provided.', 'Delivered', '2026-03-10 02:51:03'),
(4, NULL, 5, 'Colombo DS', 'GN-101', 'Food', 'Sunil Perera', '0771112223', '123 Main St, Colombo', 4, 'High', 'Food aid successfully provided.', 'Delivered', '2026-03-10 02:51:05'),
(6, NULL, 5, 'Colombo DS', 'GN-101', 'Food', 'Sunil Perera', '0771112223', '123 Main St, Colombo', 4, 'High', 'Food aid successfully provided.', 'Delivered', '2026-03-10 02:51:05'),
(8, NULL, 5, 'Colombo DS', 'GN-101', 'Food', 'Sunil Perera', '0771112223', '123 Main St, Colombo', 4, 'High', 'Food aid successfully provided.', 'Approved', '2026-03-10 02:55:16'),
(11, NULL, 2, 'Gampaha DS', 'GN-202', 'Medicine', 'Kamal Silva', '0719876543', '45 Park Rd, Gampaha', 2, 'Medium', 'Medicine required for children.', 'Pending', '2026-03-10 02:57:36'),
(12, NULL, 3, 'Kalutara DS', 'GN-303', 'Shelter', 'Nimali Perera', '0751114445', '78 River Rd, Kalutara', 5, 'Low', 'Shelter tents requested.', 'Rejected', '2026-03-10 03:01:57'),
(18, 16008, 5, 'colombo DS', 'GN-101', 'Medicine', 'hi how are you ', '0987763452', 'no25/10, Colombo 10', 2, 'Low', NULL, 'Pending', '2026-03-12 05:39:27'),
(19, 16008, 5, 'colombo DS', 'GN-101', 'Medicine', 'hi how are you ', '0987763452', 'no25/10, Colombo 10', 2, 'Low', NULL, 'Rejected', '2026-03-12 05:41:13');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Amila Perera', 'amilaperera@gmail.com', '2026AP@', 'user', '2026-02-22 03:46:17'),
(2, 'Chamil Pathum', 'chamilpathum@gmail.com', 'cp10#d', 'user', '2026-02-22 03:51:37'),
(3, 'shashika De Silva', 'shashikasilva@gmail.com', '39ss@9', 'user', '2026-02-22 03:53:20'),
(16007, 'sandali Wijerathne', 'san@gmail.com', 'san2005', 'admin', '2026-03-10 03:18:05'),
(16008, 'hi how are you ', 'hi@gmail.com', 'hi123', 'user', '2026-03-12 04:23:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `district_name` (`district_name`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `region_id` (`region_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `regions`
--
ALTER TABLE `regions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16009;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `requests_ibfk_2` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
