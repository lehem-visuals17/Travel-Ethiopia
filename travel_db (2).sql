-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 26, 2026 at 09:34 AM
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
-- Database: `travel_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `blog_posts`
--

CREATE TABLE `blog_posts` (
  `id` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `slug` varchar(200) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `summary` varchar(500) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `author_name` varchar(100) DEFAULT 'Admin User',
  `status` varchar(50) DEFAULT 'published',
  `views_count` int(11) DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blog_posts`
--

INSERT INTO `blog_posts` (`id`, `title`, `slug`, `category`, `summary`, `content`, `author_name`, `status`, `views_count`, `image`, `created_at`) VALUES
(1, 'ethiopian festivals', 'ethiopian-festivals', 'events', 'happy epiphany ', 'lomi bwerewr', 'bete', 'draft', 0, 'uploads/blog/1776796647_begena.copilot.png', '2026-04-21 18:37:27');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `package_id` int(11) DEFAULT NULL,
  `destination_id` int(11) DEFAULT NULL,
  `travel_date` date DEFAULT NULL,
  `people_count` int(11) DEFAULT NULL,
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  `total_price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `destinations`
--

CREATE TABLE `destinations` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `tagline` varchar(255) DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL,
  `type` enum('adventure','cultural','religious','historical','nature') DEFAULT NULL,
  `description` text DEFAULT NULL,
  `highlights` text DEFAULT NULL,
  `best_time` varchar(100) DEFAULT NULL,
  `dry_season` varchar(255) DEFAULT NULL,
  `rainy_season` varchar(255) DEFAULT NULL,
  `spring_weather` varchar(100) DEFAULT NULL,
  `summer_weather` varchar(100) DEFAULT NULL,
  `autumn_weather` varchar(100) DEFAULT NULL,
  `winter_weather` varchar(100) DEFAULT NULL,
  `average_cost` decimal(10,2) DEFAULT NULL,
  `budget_cost` varchar(50) DEFAULT NULL,
  `standard_cost` varchar(50) DEFAULT NULL,
  `luxury_cost` varchar(50) DEFAULT NULL,
  `accommodation` varchar(255) DEFAULT NULL,
  `rating` decimal(3,2) DEFAULT 0.00,
  `reviews` int(11) DEFAULT 0,
  `distance_info` varchar(255) DEFAULT NULL,
  `map_location` text DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `image2` varchar(255) DEFAULT NULL,
  `image3` varchar(255) DEFAULT NULL,
  `image4` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `destinations`
--

INSERT INTO `destinations` (`id`, `name`, `tagline`, `region`, `type`, `description`, `highlights`, `best_time`, `dry_season`, `rainy_season`, `spring_weather`, `summer_weather`, `autumn_weather`, `winter_weather`, `average_cost`, `budget_cost`, `standard_cost`, `luxury_cost`, `accommodation`, `rating`, `reviews`, `distance_info`, `map_location`, `video_url`, `image`, `image2`, `image3`, `image4`) VALUES
(2, 'lalibela', 'The New Jerusalem of Africa', 'Amhara Region,Ethiopia', 'adventure', 'Lalibela is a town in the Amhara Region of Ethiopia, famous for its rock-cut monolithic churches. The whole of Lalibela is a large antiquity of the medieval and post-medieval civilization of Ethiopia. Lalibela is one of Ethiopia\'s holiest cities, second only to Axum, and a center of pilgrimage.\r\n\r\nThe churches of Lalibela were hewn from the living rock to house the faithful after Muslim conquests halted Ethiopian pilgrimages to the Holy Land. Lalibela, revered as a saint, is said to have seen Jerusalem and then attempted to build a new one as his legacy. Most were carved downwards from the rock with roofs at ground level, some stand in open quarried caves, and a number occupy caverns. The churches were not constructed but rather excavated – each one was created by carving out the surrounding rock.', 'Spectacular Underground Passages,\r\nUNESCO World Heritage Site,Ancient Pilgrimage Site,11 Medieval Rock-Hewn ChurchesAnnual Timkat Festival,Unique Architecture', 'October to March', 'Oct - May', 'Jun - Sep', '22°C', '20°C', ' 24°C', '21°C', NULL, '$150-$200', '$500-$800', '$250-$350', '', 4.50, 0, '642 km (8-10 hours by road)', 'https://maps.google.com/maps?ll=12.017694,39.021333&z=16&t=m&hl=en&gl=ET&mapclient=embed', 'https://www.youtube.com/watch?v=zE5Qd26R9ek', '1776971466_2d6f62165c14bdbd91ffdb60c70e3aec.png', '1776971466_5561ca5fb5f299243830e64b720a293e.png', '1776971466_5bbabc32d63cfa99eb38e40d0a1896c4.png', '1776971466_2248d23a024900f308faf4767e0eb26c.png'),
(3, 'fasiledes', 'the great archtecture of medival time', 'Amhara Region, Ethiopia', 'historical', 'The Fasilides Castles, part of the larger Fasil Ghebbi (Royal Enclosure) in Gondar, Ethiopia, represent a unique 17th-century fortress-city often called the &quot;Camelot of Africa&quot;. Founded by Emperor Fasilides in 1636, this UNESCO World Heritage Site served as the permanent capital of the Ethiopian Empire for over two centuries.', 'Structures are built primarily with local stone and lime mortar, featuring massive towers, crenellated (battlemented) walls, and semicircular arches.', 'October to March', 'September–November  30°C', 'July-August', '22°C', '21°C', '24°C', '22°C', NULL, '150$-180$', '200$-250$', '280$-350$', '', 4.30, 0, '656 kilometers (408 miles) 15 hours and 10 minutes by car', 'https://maps.app.goo.gl/RNLpDeruTtjpWAt98', 'https://www.youtube.com/watch?v=IIEFSvlnT7k', '1776983706_gonder1 (2).jpg', '1776983706_gonder2 (2).jpg', '1776983706_gonder3.jpg', '1776983706_gonder4.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `destination_attractions`
--

CREATE TABLE `destination_attractions` (
  `id` int(11) NOT NULL,
  `destination_id` int(11) NOT NULL,
  `attraction_name` varchar(255) DEFAULT NULL,
  `attraction_description` text DEFAULT NULL,
  `attraction_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `destination_tips`
--

CREATE TABLE `destination_tips` (
  `id` int(11) NOT NULL,
  `destination_id` int(11) NOT NULL,
  `tip_title` varchar(255) DEFAULT NULL,
  `tip_detail` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `experiences`
--

CREATE TABLE `experiences` (
  `id` int(11) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `location` varchar(150) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `schedule` varchar(100) DEFAULT 'Daily at 11:00 AM',
  `description` text DEFAULT NULL,
  `whats_included` text DEFAULT NULL,
  `not_included` text DEFAULT NULL,
  `itinerary` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `gallery_images` text DEFAULT NULL,
  `difficulty` enum('Easy','Moderate','Challenging') DEFAULT 'Easy',
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `capacity` int(11) DEFAULT 0,
  `is_featured` tinyint(1) DEFAULT 0,
  `availability_status` varchar(50) DEFAULT 'Available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `experiences`
--

INSERT INTO `experiences` (`id`, `name`, `type`, `category`, `location`, `price`, `duration`, `schedule`, `description`, `whats_included`, `not_included`, `itinerary`, `image`, `gallery_images`, `difficulty`, `status`, `capacity`, `is_featured`, `availability_status`) VALUES
(1, 'mm', NULL, 'asas', 'fds', 1212.00, NULL, 'Daily at 11:00 AM', 'fssdfssAs', NULL, NULL, NULL, '1776544011_adventure.jpg', NULL, 'Easy', 'Active', 11, 0, 'Available'),
(2, 'bb', NULL, 'Nature', NULL, 6565.00, NULL, 'Daily at 11:00 AM', 'jjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjj', NULL, NULL, NULL, 'default.jpg', NULL, 'Moderate', 'Active', 7, 0, 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `experience_bookings`
--

CREATE TABLE `experience_bookings` (
  `id` int(11) NOT NULL,
  `experience_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `people_count` int(11) DEFAULT 1,
  `total_price` decimal(10,2) DEFAULT 0.00,
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guides`
--

CREATE TABLE `guides` (
  `id` int(11) NOT NULL,
  `destination_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `language` varchar(50) DEFAULT NULL,
  `experience_years` int(11) DEFAULT NULL,
  `rating` decimal(3,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guides`
--

INSERT INTO `guides` (`id`, `destination_id`, `name`, `phone`, `language`, `experience_years`, `rating`, `image`) VALUES
(1, 2, 'avi', '0989878978', 'amharic', 2, 0.00, '1776544383_Apply a mystical Eth.png');

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` int(11) NOT NULL,
  `title` varchar(150) DEFAULT NULL,
  `type` enum('honeymoon','family','adventure','luxury','budget') DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `includes` text DEFAULT NULL,
  `rating` decimal(3,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `max_people` int(11) DEFAULT 1,
  `featured` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `title`, `type`, `description`, `price`, `duration`, `includes`, `rating`, `image`, `max_people`, `featured`) VALUES
(1, 'honey moon package', 'honeymoon', 'enjoy your honey moon with us', 12000.00, '7days/6nights', 'hotel,guide', 4.00, '1776624696_1776544011_adventure.jpg', 2, 1),
(2, 'family time', 'family', 'live the moment with your family', 3000.00, '4days/3 nights', 'hotel,transport,meals', 4.80, '1776625170_adventure.jpg', 8, 1),
(3, 'luxury time', 'luxury', 'good time', 5000.00, '3 days/2 nights', 'hotel,transport,meals,guide', 5.00, '1776625241_1776544011_adventure.jpg', 3, 0),
(4, 'hihi', 'budget', 'hihi hihi', 2000.00, '10 days', 'hotel', 4.30, '1776625296_1776544011_adventure.jpg', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `package_destinations`
--

CREATE TABLE `package_destinations` (
  `id` int(11) NOT NULL,
  `package_id` int(11) DEFAULT NULL,
  `destination_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `payment_type` enum('normal','premium') DEFAULT NULL,
  `method` enum('cash','bank','mobile','card') DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','paid','failed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `destination_id` int(11) DEFAULT NULL,
  `package_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `type` enum('flight','hotel','car','tour') DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('admin','customer') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','suspended') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `username`, `email`, `password`, `phone`, `role`, `created_at`, `status`) VALUES
(1, 'Bete', 'admin', 'betelhembelayneh58@gmail.com', '$2y$10$jgXebJ3QUHACE.jo.Npo6eW/1HeuufaVpFdEAo73dcJx5TeAvM7we', '0956264326', 'admin', '2026-04-16 17:33:12', 'active'),
(2, 'Betelhem', 'lehem', 'betelhem.belayneh21@gmail.com', '$2y$10$Zcs/0jWRpYGhihfX1C3RTuVgytPu3qGeLAdgyD0RwwBh663EPsDyK', '0956264326', 'customer', '2026-04-17 20:57:28', 'active'),
(3, 'avi', 'avi32@gmail.com', 'avi32@gmail.com', '$2y$10$eAVHrWZJcJ6wY4iuJAwUW.mAPtndBkid.EhSoFaFnv6729j5HIPge', '0924322824', 'customer', '2026-04-18 10:52:17', 'active'),
(4, 'tigst belayneh', 'tigi@gmail.com', 'tigi@gmail.com', '$2y$10$movLGzMaiB9SPrnoSlQgxOD0OtWJZn1BSZBffQ8YBNTuhwygXtB5.', '0956264326', 'customer', '2026-04-18 11:24:46', 'active'),
(11, 'Bete', 'betora_ad', 'betelhem.belayneh58@gmail.com', '$2y$10$OdlTUXRWz5tSKPrqRLALJegHmga9DAx319LuXpwA2Sq0PRoPPqxvO', '0956264326', 'admin', '2026-04-23 08:15:45', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `package_id` (`package_id`),
  ADD KEY `destination_id` (`destination_id`);

--
-- Indexes for table `destinations`
--
ALTER TABLE `destinations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `destination_attractions`
--
ALTER TABLE `destination_attractions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `destination_id` (`destination_id`);

--
-- Indexes for table `destination_tips`
--
ALTER TABLE `destination_tips`
  ADD PRIMARY KEY (`id`),
  ADD KEY `destination_id` (`destination_id`);

--
-- Indexes for table `experiences`
--
ALTER TABLE `experiences`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `experience_bookings`
--
ALTER TABLE `experience_bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `guides`
--
ALTER TABLE `guides`
  ADD PRIMARY KEY (`id`),
  ADD KEY `destination_id` (`destination_id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `package_destinations`
--
ALTER TABLE `package_destinations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `package_id` (`package_id`),
  ADD KEY `destination_id` (`destination_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `email_2` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blog_posts`
--
ALTER TABLE `blog_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `destinations`
--
ALTER TABLE `destinations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `destination_attractions`
--
ALTER TABLE `destination_attractions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `destination_tips`
--
ALTER TABLE `destination_tips`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `experiences`
--
ALTER TABLE `experiences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `experience_bookings`
--
ALTER TABLE `experience_bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guides`
--
ALTER TABLE `guides`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `package_destinations`
--
ALTER TABLE `package_destinations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`),
  ADD CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`);

--
-- Constraints for table `destination_attractions`
--
ALTER TABLE `destination_attractions`
  ADD CONSTRAINT `destination_attractions_ibfk_1` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `destination_tips`
--
ALTER TABLE `destination_tips`
  ADD CONSTRAINT `destination_tips_ibfk_1` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `guides`
--
ALTER TABLE `guides`
  ADD CONSTRAINT `guides_ibfk_1` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`);

--
-- Constraints for table `package_destinations`
--
ALTER TABLE `package_destinations`
  ADD CONSTRAINT `package_destinations_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`),
  ADD CONSTRAINT `package_destinations_ibfk_2` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`),
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
