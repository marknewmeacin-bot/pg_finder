-- phpMyAdmin SQL 
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 09, 2026 at 07:30 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pgfinder`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
--  data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'Admin dashboard', 'admin@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-05-09 16:54:41');

-- --------------------------------------------------------

--
-- Table structure for table `amenities`
--

CREATE TABLE `amenities` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- data for table `amenities`
--

INSERT INTO `amenities` (`id`, `name`) VALUES
(5, '24/7 Water'),
(2, 'AC'),
(7, 'Gym'),
(4, 'Laundry'),
(3, 'Mess'),
(6, 'Security'),
(8, 'Study Room'),
(1, 'WiFi');

-- --------------------------------------------------------

--
-- Table structure for table `interested_users`
--

CREATE TABLE `interested_users` (
  `user_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- data for table `interested_users`
--

INSERT INTO `interested_users` (`user_id`, `property_id`, `added_at`) VALUES
(3, 4, '2026-05-09 16:30:19');

-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

CREATE TABLE `properties` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `city` varchar(80) NOT NULL,
  `address` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `gender` enum('Male','Female','Coed') NOT NULL DEFAULT 'Coed',
  `rating` decimal(2,1) NOT NULL DEFAULT 4.5,
  `description` text NOT NULL,
  `image` varchar(255) DEFAULT 'images/pg-default.jpg',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
--  data for table `properties`
--

INSERT INTO `properties` (`id`, `name`, `city`, `address`, `price`, `gender`, `rating`, `description`, `image`, `created_at`) VALUES
(1, 'Campus Comfort PG', 'Delhi', 'Near Metro Station, Dwarka', 8500.00, 'Female', 4.7, 'A safe and modern PG with shared rooms, fast wifi, and nutritious meals.', 'images/pg1.jpg', '2026-05-09 15:32:17'),
(2, 'Student Stay Hub', 'Bangalore', 'Koramangala, Near Tech Parks', 10500.00, 'Coed', 4.5, 'Great community, gym access, study area, and clean rooms with laundry service.', 'images/pg2.jpg', '2026-05-09 15:32:17'),
(3, 'Metro Living PG', 'Mumbai', 'Khar West, Near Shoppers Stop', 9500.00, 'Male', 4.6, 'Premium PG for working students with AC rooms, mess, and 24/7 water supply.', 'images/pg3.jpg', '2026-05-09 15:32:17'),
(4, 'Heritage House', 'Chennai', 'T. Nagar, Close to Colleges', 7800.00, 'Female', 4.4, 'Affordable PG with all basic amenities, friendly staff, and quiet study spaces.', 'images/pg4.jpg', '2026-05-09 15:32:17'),
(5, 'Quick Stay PG', 'Pune', 'Kothrud, Near Bus Stop', 8900.00, 'Coed', 4.8, 'Comfortable rooms, flexible meals, and easy access to public transport.', 'images/pg5.jpg', '2026-05-09 15:32:17'),
(6, 'Campus Comfort PG', 'Delhi', 'Near Metro Station, Dwarka', 8500.00, 'Female', 4.7, 'A safe and modern PG with shared rooms, fast wifi, and nutritious meals.', '????\0JFIF\0\0\0\0\0\0??\0?\0	( \Z%!1!%)+...383-7(-.+\n\n\n\r/------+-+-+-+--+-++-++-------+--+---+----+------+-??\0\0?\"\0??\0\0\0\0\0\0\0\0\0\0\0\0\0\0??\0N\0	\0\0\0\0!1AQq\"2a????#$rs?????', '2026-05-09 15:53:41'),
(7, 'Student Stay Hub', 'Bangalore', 'Koramangala, Near Tech Parks', 10500.00, 'Coed', 4.5, 'Great community, gym access, study area, and clean rooms with laundry service.', '????\0JFIF\0\0\0\0\0\0??\0?\0	( %!1!%)+...383-7(-.+\n\n\n\r\Z-& %.0--.----------------------------------/----------??\0\0?\"\0??\0\0\0\0\0\0\0\0\0\0\0\0\0\0??\0K\0\0\0\0!1AQaq\"??2??#BRr???', '2026-05-09 15:53:41'),
(8, 'Metro Living PG', 'Mumbai', 'Khar West, Near Shoppers Stop', 9500.00, 'Male', 4.6, 'Premium PG for working students with AC rooms, mess, and 24/7 water supply.', '????\0JFIF\0\0\0\0\0\0??\0?\0	( \Z%!1!%)+...383-7(-.+\n\n\n\r\Z\Z- ------0+/----------+-------------+-+----------0---??\0\0?,\"\0??\0\0\0\0\0\0\0\0\0\0\0\0\0\0??\0L\0	\0\0\0\0!1AQ\"aq???2????#3BR', '2026-05-09 15:53:41'),
(9, 'Heritage House', 'Chennai', 'T. Nagar, Close to Colleges', 7800.00, 'Female', 4.4, 'Affordable PG with all basic amenities, friendly staff, and quiet study spaces.', '????\0JFIF\0\0\0\0\0\0??\0?\0	( \Z%!1!%)+...383-7(-.+\n\n\n\r\Z-%--------------------------------------------------??\0\0?I\"\0??\0\0\0\0\0\0\0\0\0\0\0\0\0\0??\0L\0	\0\0\0\0!1AQqa??????\"2R??BS', '2026-05-09 15:53:41'),
(10, 'Quick Stay PG', 'Pune', 'Kothrud, Near Bus Stop', 8900.00, 'Coed', 4.8, 'Comfortable rooms, flexible meals, and easy access to public transport.', '????\0JFIF\0\0\0\0\0\0??\0?\0	( %!1!%)+...383-7(-.+\n\n\n\r\Z-%%---------------+--------+-------------------------??\0\0?\"\0??\0\0\0\0\0\0\0\0\0\0\0\0\0\0??\0L\0\n\n\0\0\0\0!1AQ\"aq?????#2??BR', '2026-05-09 15:53:41'),
(11, 'Urban Nest PG', 'Hyderabad', 'Banjara Hills, Near IT Hub', 9200.00, 'Coed', 4.3, 'Modern PG with high-speed internet, gym, and proximity to major tech companies.', '????\0JFIF\0\0\0\0\0\0??\0?\0	( %!1!%)+...383-7(-.+\n\n\n\r\Z/%-------+----------------------------------------+-??\0\0?\"\0??\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0??\0F\0		\0\0\0\0!1AQaq?\"???2R????B?', '2026-05-09 15:53:41');

-- --------------------------------------------------------

--
-- Table structure for table `property_amenities`
--

CREATE TABLE `property_amenities` (
  `property_id` int(11) NOT NULL,
  `amenity_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
--  data for table `property_amenities`
--

INSERT INTO `property_amenities` (`property_id`, `amenity_id`) VALUES
(1, 1),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(2, 1),
(2, 2),
(2, 3),
(2, 4),
(2, 7),
(3, 1),
(3, 5),
(3, 6),
(3, 8),
(4, 1),
(4, 4),
(4, 5),
(4, 8),
(5, 1),
(5, 2),
(5, 3),
(5, 5),
(6, 1),
(6, 2),
(6, 7),
(6, 8);

-- --------------------------------------------------------

--
-- Table structure for table `property_images`
--

CREATE TABLE `property_images` (
  `id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
--  data for table `property_images`
--

INSERT INTO `property_images` (`id`, `property_id`, `image`) VALUES
(1, 1, 'pg1.jpg'),
(2, 2, 'pg2.jpg'),
(3, 3, 'pg3.jpg'),
(4, 4, '\r\npg4.jpg'),
(5, 5, 'pg5.jpg'),
(6, 6, 'pg6.jpg'),
(7, 1, 'pg1.jpg'),
(8, 1, 'pg2.jpg'),
(9, 2, 'pg3.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_blocked` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
--  data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `created_at`, `is_blocked`) VALUES
(3, 'Adung', 'adung@gmail.com', '$2y$10$tzZnrXckGXwqPbm6n8gKaeuWO3grdoH7DQRWfKjw5PdLSqHrWzXJ2', '', '2026-05-09 16:27:08', 0);

--
-- Indexes for  tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `amenities`
--
ALTER TABLE `amenities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `interested_users`
--
ALTER TABLE `interested_users`
  ADD PRIMARY KEY (`user_id`,`property_id`),
  ADD KEY `property_id` (`property_id`);

--
-- Indexes for table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `property_amenities`
--
ALTER TABLE `property_amenities`
  ADD PRIMARY KEY (`property_id`,`amenity_id`),
  ADD KEY `amenity_id` (`amenity_id`);

--
-- Indexes for table `property_images`
--
ALTER TABLE `property_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_id` (`property_id`);

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
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `amenities`
--
ALTER TABLE `amenities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `property_images`
--
ALTER TABLE `property_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for  tables
--

--
-- Constraints for table `interested_users`
--
ALTER TABLE `interested_users`
  ADD CONSTRAINT `interested_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `interested_users_ibfk_2` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `property_amenities`
--
ALTER TABLE `property_amenities`
  ADD CONSTRAINT `property_amenities_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `property_amenities_ibfk_2` FOREIGN KEY (`amenity_id`) REFERENCES `amenities` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `property_images`
--
ALTER TABLE `property_images`
  ADD CONSTRAINT `property_images_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
