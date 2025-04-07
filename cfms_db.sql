-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 28, 2025 at 10:06 AM
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
-- Database: `cfms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `church_groups`
--

CREATE TABLE `church_groups` (
  `group_id` int(11) NOT NULL,
  `group_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `church_groups`
--

INSERT INTO `church_groups` (`group_id`, `group_name`) VALUES
(1, 'Church'),
(3, 'Men Group'),
(5, 'Sunday School'),
(2, 'Women Group'),
(4, 'Youth');

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `donation_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `group_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `type` enum('tithe','offering','donation') NOT NULL,
  `payment_method` enum('cash','Mpesa') NOT NULL,
  `transaction_code` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `phone_number` varchar(20) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donations`
--

INSERT INTO `donations` (`donation_id`, `user_id`, `group_id`, `amount`, `type`, `payment_method`, `transaction_code`, `created_at`, `phone_number`, `full_name`) VALUES
(1, 18, 1, 2000.00, 'donation', 'cash', NULL, '2025-03-18 23:07:19', '', NULL),
(5, 18, 1, 1.00, 'donation', 'Mpesa', 'TCK6HIO6SI', '2025-03-20 19:13:52', '', NULL),
(6, 20, 1, 1.00, 'donation', 'Mpesa', 'TCK1HK7SHR', '2025-03-20 19:29:45', '', NULL),
(7, 18, 1, 1.00, 'donation', 'Mpesa', 'TCK6HKBLYS', '2025-03-20 19:30:59', '', NULL),
(8, 20, 1, 1000.00, 'donation', 'cash', NULL, '2025-03-20 22:27:36', '0722334455', NULL),
(9, 20, 1, 1000.00, 'donation', 'cash', NULL, '2025-03-25 22:28:01', '0722334455', NULL),
(10, 6, 1, 10000.00, 'donation', 'cash', NULL, '2025-03-25 22:28:50', '0745678901', NULL),
(11, 9, 1, 2000.00, 'offering', 'Mpesa', 'TCK6HKBLQR', '2025-03-25 22:30:22', '0778901234', NULL),
(14, NULL, 1, 2000.00, 'donation', 'cash', NULL, '2025-03-25 23:09:44', '0792567890', 'Violet Mwenda'),
(15, NULL, 1, 2000.00, 'donation', 'cash', NULL, '2025-03-16 08:10:50', '0792567890', 'Violet Mwenda'),
(16, 9, 1, 1499.00, 'tithe', 'cash', NULL, '2025-03-25 23:20:18', '0778901234', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `expense_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `added_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`expense_id`, `group_id`, `description`, `amount`, `added_by`, `created_at`) VALUES
(3, 1, 'Chairs', 10000.00, 2, '2025-03-18 21:19:43'),
(4, 5, 'Sweets', 2450.00, 2, '2025-03-18 21:21:41'),
(5, 3, 'Tiles', 25000.00, 19, '2025-03-18 21:23:38'),
(6, 1, 'Sound system', 200000.00, 2, '2025-03-18 22:54:36');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `progress` int(11) NOT NULL CHECK (`progress` between 0 and 100),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('ongoing','completed') NOT NULL DEFAULT 'ongoing'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('member','leader','admin') DEFAULT 'member',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `gender` varchar(10) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `marital_status` varchar(15) DEFAULT NULL,
  `num_children` int(11) DEFAULT 0,
  `occupation` varchar(50) DEFAULT NULL,
  `residential_address` varchar(255) DEFAULT NULL,
  `baptism_date` date DEFAULT NULL,
  `ministry_involvement` varchar(50) DEFAULT NULL,
  `emergency_contact` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `phone`, `password`, `role`, `created_at`, `gender`, `age`, `marital_status`, `num_children`, `occupation`, `residential_address`, `baptism_date`, `ministry_involvement`, `emergency_contact`) VALUES
(1, 'Newton Gitonga', 'gitosh@gmail.com', '0745277170', '$2y$10$f0WhIH2xPbDRDPuIAmzYZu/foD3CqFbSIrLWt25630lfYXySroaTW', 'admin', '2025-03-03 19:23:32', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL),
(2, 'Erickson Muriungi', 'erick@outlook.com', '0110454521', '$2y$10$HU7NAmTc3RJv0.VyNuNNXeAQtS988Aq0GNMLnjvLueRPQ/SQWtng6', 'leader', '2025-03-03 19:31:19', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL),
(3, 'John Mwangi', 'john.mwangi@example.com', '0712345678', '$2y$10$Tr2P/7qQzq60KN2x4AN5eeEqmO0BMq7mE1Zly7LQmXy7Q8bbT8FFy', 'member', '2025-03-03 19:44:24', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL),
(4, 'Mary Wanjiru', 'mary.wanjiru@example.com', '0723456789', '$2y$10$Tr2P/7qQzq60KN2x4AN5eeEqmO0BMq7mE1Zly7LQmXy7Q8bbT8FFy', 'member', '2025-03-03 19:44:24', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL),
(5, 'James Kamau', 'james.kamau@example.com', '0734567890', '$2y$10$Tr2P/7qQzq60KN2x4AN5eeEqmO0BMq7mE1Zly7LQmXy7Q8bbT8FFy', 'leader', '2025-03-03 19:44:24', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL),
(6, 'Alice Njeri', 'alice.njeri@example.com', '0745678901', '$2y$10$Tr2P/7qQzq60KN2x4AN5eeEqmO0BMq7mE1Zly7LQmXy7Q8bbT8FFy', 'member', '2025-03-03 19:44:24', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL),
(7, 'Peter Otieno', 'peter.otieno@example.com', '0756789012', '$2y$10$Tr2P/7qQzq60KN2x4AN5eeEqmO0BMq7mE1Zly7LQmXy7Q8bbT8FFy', 'member', '2025-03-03 19:44:24', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL),
(8, 'Sarah Achieng', 'sarah.achieng@example.com', '0767890123', '$2y$10$Tr2P/7qQzq60KN2x4AN5eeEqmO0BMq7mE1Zly7LQmXy7Q8bbT8FFy', 'leader', '2025-03-03 19:44:24', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL),
(9, 'Michael Kariuki', 'michael.kariuki@example.com', '0778901234', '$2y$10$Tr2P/7qQzq60KN2x4AN5eeEqmO0BMq7mE1Zly7LQmXy7Q8bbT8FFy', 'member', '2025-03-03 19:44:24', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL),
(10, 'Lucy Muthoni', 'lucy.muthoni@example.com', '0789012345', '$2y$10$Tr2P/7qQzq60KN2x4AN5eeEqmO0BMq7mE1Zly7LQmXy7Q8bbT8FFy', 'member', '2025-03-03 19:44:24', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL),
(11, 'Samuel Njenga', 'samuel.njenga@example.com', '0790123456', '$2y$10$Tr2P/7qQzq60KN2x4AN5eeEqmO0BMq7mE1Zly7LQmXy7Q8bbT8FFy', 'member', '2025-03-03 19:44:24', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL),
(12, 'Admin User', 'admin@example.com', '0798765432', '$2y$10$Tr2P/7qQzq60KN2x4AN5eeEqmO0BMq7mE1Zly7LQmXy7Q8bbT8FFy', 'admin', '2025-03-03 19:44:24', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL),
(18, 'Caroline Mwangi', 'carol@gmail.com', '01123456', '$2y$10$vT6El38S5zL3cEH4ANnZpuaWiOSDATHtVhjGk4mCj5i7XT62Z5be.', 'member', '2025-03-14 09:59:53', 'Female', 23, 'Single', 0, 'Farmer', 'Githongo', NULL, 'Youth Ministry', 'Sarah 0723456789'),
(19, 'Boniface Mwiti', 'boni@outlook.com', '0701318829', '$2y$10$gIZzippu0wRlkA8NF2rBU..Hdbd5Gu0LLVzKIcW7.vUjHJ2wJa2Tq', 'leader', '2025-03-18 21:22:40', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL),
(20, 'Ayden Kirimi', 'ayden@gmail.com', '0722334455', '$2y$10$VzoiHZNOr3WmDh6W6LN4AO5pJS0M1ZdJYjXAamoQ2TBsFJPStNyOO', 'member', '2025-03-20 19:26:43', 'Male', 2, 'Single', 0, 'Null', '47-60202', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `church_groups`
--
ALTER TABLE `church_groups`
  ADD PRIMARY KEY (`group_id`),
  ADD UNIQUE KEY `group_name` (`group_name`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`donation_id`),
  ADD KEY `donations_fk` (`group_id`),
  ADD KEY `donations_ibfk_1` (`user_id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`expense_id`),
  ADD KEY `added_by` (`added_by`),
  ADD KEY `expenses_fk` (`group_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `church_groups`
--
ALTER TABLE `church_groups`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `donation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `expense_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `donations`
--
ALTER TABLE `donations`
  ADD CONSTRAINT `donations_fk` FOREIGN KEY (`group_id`) REFERENCES `church_groups` (`group_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `donations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_fk` FOREIGN KEY (`group_id`) REFERENCES `church_groups` (`group_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `expenses_ibfk_2` FOREIGN KEY (`added_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
