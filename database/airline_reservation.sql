-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 20, 2025 at 08:55 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `airline_reservation`
--

-- --------------------------------------------------------

--
-- Table structure for table `airlines`
--

DROP TABLE IF EXISTS `airlines`;
CREATE TABLE IF NOT EXISTS `airlines` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `code` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `airlines`
--

INSERT INTO `airlines` (`id`, `name`, `code`) VALUES
(1, 'Air India', 'AI'),
(2, 'IndiGo', '6E'),
(3, 'SpiceJet', 'SG'),
(4, 'Vistara', 'UK');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
CREATE TABLE IF NOT EXISTS `bookings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `flight_id` int DEFAULT NULL,
  `booking_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('confirmed','cancelled','completed') DEFAULT 'confirmed',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `flight_id` (`flight_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `flight_id`, `booking_date`, `status`) VALUES
(1, 1, 1, '2025-05-12 14:12:30', 'confirmed'),
(2, 2, 1, '2025-05-13 12:39:37', 'confirmed'),
(3, 3, 9, '2025-05-13 13:43:05', 'confirmed'),
(4, 4, 10, '2025-05-13 13:48:04', 'confirmed'),
(5, 5, 10, '2025-05-13 14:17:04', 'confirmed');

-- --------------------------------------------------------

--
-- Table structure for table `flights`
--

DROP TABLE IF EXISTS `flights`;
CREATE TABLE IF NOT EXISTS `flights` (
  `id` int NOT NULL AUTO_INCREMENT,
  `flight_number` varchar(20) NOT NULL,
  `airline_id` int DEFAULT NULL,
  `origin` varchar(100) NOT NULL,
  `destination` varchar(100) NOT NULL,
  `departure_time` datetime NOT NULL,
  `arrival_time` datetime NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `available_seats` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `airline_id` (`airline_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `flights`
--

INSERT INTO `flights` (`id`, `flight_number`, `airline_id`, `origin`, `destination`, `departure_time`, `arrival_time`, `price`, `available_seats`) VALUES
(12, 'UK404', 4, 'Chennai', 'Delhi', '2025-06-20 13:00:00', '2025-06-20 15:30:00', 6000.00, 160),
(11, 'SG306', 3, 'Bangalore', 'Chennai', '2025-06-20 11:00:00', '2025-06-20 12:30:00', 3500.00, 120),
(10, '6E202', 2, 'Mumbai', 'Bangalore', '2025-06-20 09:30:00', '2025-06-20 11:30:00', 4500.00, 178),
(9, 'AI101', 1, 'Delhi', 'Mumbai', '2025-06-20 08:00:00', '2025-06-20 10:00:00', 5000.00, 149);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_admin` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `full_name`, `created_at`, `is_admin`) VALUES
(1, 'pranav', '$2y$10$xGVZepk7019Fa4e/acNaNuq2MYd471ggcdAFzX42.YswREiSrKjEm', 'pp@gmail.com', 'Pranav Raju Malwatkar', '2025-05-12 14:09:08', 0),
(2, 'sunil', '$2y$10$YwbpBFOmV/oAnldOVOjSpun/KMzw2bE2BzJpxuqlYJ7bpG0eHotB2', 'sunil@gmail.com', 'Sunil khaple', '2025-05-13 12:37:18', 0),
(3, 'Swapanil', '$2y$10$2vq5wyvpiikaOFF7uWDYaeYd2LWrVJjKAdcKGKteRQ8.s1ehgCpjK', 'Swapanil@gmail.com', 'Swapanil khaple', '2025-05-13 13:40:57', 0),
(4, 'Pawan', '$2y$10$KjuweCOneZ2jLDWXns8kxulfgG7nRR3ioSBpuFKeZOA8Tl2l6yTPm', 'Pawan@gmail.com', 'Pawan', '2025-05-13 13:46:25', 0),
(5, 'Saharsh', '$2y$10$RBf0F4/iLSNI1808ixE/yuBwM5LODoBZulqqFRqQZLPOjUnha7Aya', 'Saharsh@gmail.com', 'Saharsh swapanil khaple', '2025-05-13 14:15:06', 0),
(6, 'komal', '$2y$10$EyRpBPk2ECtTuqq0m5k7s.ctcdlP3ldpAdEvLPvG3qy9twswtzQie', 'komal@gmail.com', 'komal', '2025-05-20 08:53:59', 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
