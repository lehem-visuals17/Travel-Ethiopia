-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 08, 2026 at 05:37 PM
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
  `cover_image` varchar(255) DEFAULT NULL,
  `slider_images` text DEFAULT NULL,
  `content` text DEFAULT NULL,
  `author_name` varchar(100) DEFAULT 'Admin User',
  `read_time` varchar(50) DEFAULT '5 min read',
  `status` varchar(50) DEFAULT 'published',
  `views_count` int(11) DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blog_posts`
--

INSERT INTO `blog_posts` (`id`, `title`, `slug`, `category`, `summary`, `cover_image`, `slider_images`, `content`, `author_name`, `read_time`, `status`, `views_count`, `image`, `created_at`) VALUES
(1, 'ethiopian festivals', 'ethiopian-festivals', 'events', 'happy epiphany ', '', '', 'lomi bwerewr', 'bete', '5 min read', 'published', 0, 'uploads/blog/1776796647_begena.copilot.png', '2026-04-21 18:37:27');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `package_id` int(11) DEFAULT NULL,
  `experience_id` int(11) DEFAULT NULL,
  `destination_id` int(11) DEFAULT NULL,
  `travel_date` date DEFAULT NULL,
  `people_count` int(11) DEFAULT NULL,
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  `payment_status` enum('unpaid','paid','refunded') DEFAULT 'unpaid',
  `total_price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `guide_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `package_id`, `experience_id`, `destination_id`, `travel_date`, `people_count`, `status`, `payment_status`, `total_price`, `created_at`, `guide_id`) VALUES
(1, NULL, NULL, NULL, 4, '2026-05-15', 1, 'confirmed', 'unpaid', NULL, '2026-05-06 10:45:36', 2),
(2, NULL, NULL, NULL, 3, '2026-05-22', 1, 'pending', 'unpaid', NULL, '2026-05-06 12:20:46', 3),
(3, 2, NULL, NULL, 3, '2026-05-22', 1, 'confirmed', 'unpaid', NULL, '2026-05-06 12:20:46', 3),
(4, 11, NULL, NULL, 2, '2026-05-14', 1, 'pending', 'unpaid', NULL, '2026-05-07 06:10:44', 4),
(5, 11, NULL, NULL, 2, '2026-05-14', 1, 'pending', 'unpaid', NULL, '2026-05-07 06:16:33', 4),
(6, 2, NULL, NULL, 2, '2026-05-22', 1, 'pending', 'unpaid', NULL, '2026-05-08 06:34:57', 4);

-- --------------------------------------------------------

--
-- Table structure for table `deals`
--

CREATE TABLE `deals` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `discount_badge` varchar(50) DEFAULT NULL,
  `deal_note` varchar(100) DEFAULT NULL,
  `old_price` decimal(10,2) DEFAULT NULL,
  `new_price` decimal(10,2) DEFAULT NULL,
  `end_datetime` datetime NOT NULL,
  `status` varchar(20) DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `deals`
--

INSERT INTO `deals` (`id`, `title`, `description`, `image_url`, `discount_badge`, `deal_note`, `old_price`, `new_price`, `end_datetime`, `status`) VALUES
(1, 'Early Bird Lalibela Package', 'Book 3 months in advance and save 25% on our popular Lalibela heritage tour package.', 'https://media.gettyimages.com/id/458259481/photo/saint-george-church.jpg?s=612x612&w=0&k=20&c=ZOM25x5YaxRsPuE2p4vY4XDaY06VFExCzNQ5pwti8wo=', 'Early Booking Discount', '', 1200.00, 900.00, '2026-05-09 21:29:00', 'active');

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
(3, 'fasiledes', 'the great archtecture of medival time', 'Amhara Region, Ethiopia', 'historical', 'The Fasilides Castles, part of the larger Fasil Ghebbi (Royal Enclosure) in Gondar, Ethiopia, represent a unique 17th-century fortress-city often called the &quot;Camelot of Africa&quot;. Founded by Emperor Fasilides in 1636, this UNESCO World Heritage Site served as the permanent capital of the Ethiopian Empire for over two centuries.', 'Structures are built primarily with local stone and lime mortar, featuring massive towers, crenellated (battlemented) walls, and semicircular arches.', 'October to March', 'September–November  30°C', 'July-August', '22°C', '21°C', '24°C', '22°C', NULL, '150$-180$', '200$-250$', '280$-350$', '', 4.30, 0, '656 kilometers (408 miles) 15 hours and 10 minutes by car', 'https://maps.app.goo.gl/RNLpDeruTtjpWAt98', 'https://www.youtube.com/watch?v=IIEFSvlnT7k', '1776983706_gonder1 (2).jpg', '1776983706_gonder2 (2).jpg', '1776983706_gonder3.jpg', '1776983706_gonder4.jpg'),
(4, 'Danakil Depression', 'Gateway to Hell, Beauty of Earth', 'Afar Region', 'nature', 'The Danakil Depression is a geological wonderland in northeastern Ethiopia, located at the junction of three tectonic plates (Arabian, Nubian, and Somalian) that are slowly pulling apart. This process creates one of the most extreme environments on Earth—it is simultaneously the hottest place on the planet by average annual temperature and one of its lowest points, sitting at roughly 125 meters below sea level.', 'Dallol: Alien-looking neon acid ponds and vibrant mineral springs.,\r\nErta Ale: A rare, glowing lava lake inside an active volcano.,\r\nLake Karum: Blinding white salt flats that create a mirror-like horizon.,\r\nSalt Caravans: Hundreds of camels transporting hand-mined salt across the desert.,\r\nLake Afrera: A deep blue lake where you can float effortlessly.', 'November to February', 'November – February', 'June to September(not recommended)', '39-45', '48-53', '38-43', '35-38', NULL, '90', '150', '350', '', 4.80, 0, '894KM from addis abeba (14 hours of driving and 1 hour and 15 minutes flight', 'https://maps.app.goo.gl/egKcTnCs5QCnzdyc8', 'https://www.youtube.com/watch?v=ArEOa_i-sio&t=0s', '1777759961_danakil.jpg', '1777759961_danakil3.jpg', '1777759961_danakil1.jpg', '1777759961_danakil4.jpg');

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
  `category` varchar(50) DEFAULT NULL,
  `location` varchar(150) DEFAULT NULL,
  `map_link` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `schedule` varchar(100) DEFAULT 'Daily at 11:00 AM',
  `languages` varchar(100) DEFAULT 'English',
  `description` text DEFAULT NULL,
  `whats_included` text DEFAULT NULL,
  `not_included` text DEFAULT NULL,
  `itinerary` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `gallery` text DEFAULT NULL,
  `difficulty` enum('Easy','Moderate','Challenging') DEFAULT 'Easy',
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `capacity` int(11) DEFAULT 0,
  `rating` decimal(2,1) DEFAULT 5.0,
  `is_featured` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `experiences`
--

INSERT INTO `experiences` (`id`, `name`, `category`, `location`, `map_link`, `price`, `duration`, `schedule`, `languages`, `description`, `whats_included`, `not_included`, `itinerary`, `image`, `gallery`, `difficulty`, `status`, `capacity`, `rating`, `is_featured`) VALUES
(3, 'Mountain Trail Adventure', 'Nature', 'Simien Mountains', '13.30213451777049, 38.29895675058659', 85.00, 'Full day (8 hours)', 'Daily (weather permitting), starts at 6:00 AM', 'English, Amharic', 'Hike through spectacular mountain scenery, encounter Gelada baboons, and enjoy breathtaking views. Perfect for those seeking an unforgettable day trek. Experience the dramatic landscapes of the Simien Mountains, often called the \'Roof of Africa,\' with its jagged peaks, deep valleys, and unique wildlife.', 'Professional guide\r\nPark scout\r\nPark fees\r\nPacked lunch\r\nWater\r\nTransportation from Gondar', 'Personal hiking gear\r\nTravel insurance\r\nAdditional snacks\r\nGratuities', '6:00 AM|Departure from Gondar|\r\nPickup from your hotel in Gondar and drive to Simien Mountains National Park (approximately 2 hours).\r\nPark Entry and Trail Start\r\n8:00 AM\r\nMeet your park scout, complete registration, and begin your hike through stunning mountain terrain.', '-5465140525049962895_121.jpg', 'https://images.unsplash.com/photo-1603475429038-44361bcde123?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1080\r\nhttps://images.unsplash.com/photo-1713860052825-4798abffb5b9?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1080\r\nhttps://images.unsplash.com/photo-1548713466-70b0e7bb7cd2?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1080\r\nhttps://images.unsplash.com/photo-1643386165206-d1be6dcc76c2?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1080\r\nhttps://images.unsplash.com/photo-1583003293857-6850a9a6e663?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1080', 'Moderate', 'Active', 2, 4.9, 0);

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
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `destination_id` int(11) DEFAULT NULL,
  `package_id` int(11) DEFAULT NULL,
  `experience_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guides`
--

CREATE TABLE `guides` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `destination_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `language` varchar(50) DEFAULT NULL,
  `experience_years` int(11) DEFAULT NULL,
  `rating` decimal(3,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guide_availability`
--

CREATE TABLE `guide_availability` (
  `id` int(11) NOT NULL,
  `guide_id` int(11) DEFAULT NULL,
  `available_date` date DEFAULT NULL,
  `status` enum('available','booked') DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `includes_list` text DEFAULT NULL,
  `rating` decimal(3,2) DEFAULT NULL,
  `reviews_count` int(11) DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `max_people` int(11) DEFAULT 1,
  `featured` tinyint(1) DEFAULT 0,
  `badge_text` varchar(50) DEFAULT NULL,
  `guide_name` varchar(100) DEFAULT 'To be assigned',
  `guide_phone` varchar(20) DEFAULT '+251 900 000 000',
  `hotel_name` varchar(100) DEFAULT 'TBD',
  `pickup_location` varchar(150) DEFAULT 'Main Airport'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `title`, `type`, `description`, `price`, `duration`, `includes`, `includes_list`, `rating`, `reviews_count`, `image`, `max_people`, `featured`, `badge_text`, `guide_name`, `guide_phone`, `hotel_name`, `pickup_location`) VALUES
(1, 'honey moon package', 'honeymoon', 'enjoy your honey moon with us', 12000.00, '7days/6nights', 'hotel,guide', NULL, 4.00, 0, '1776624696_1776544011_adventure.jpg', 2, 1, NULL, 'To be assigned', '+251 900 000 000', 'TBD', 'Main Airport'),
(2, 'family time', 'family', 'live the moment with your family', 3000.00, '4days/3 nights', 'hotel,transport,meals', NULL, 4.80, 0, '1776625170_adventure.jpg', 8, 1, NULL, 'To be assigned', '+251 900 000 000', 'TBD', 'Main Airport'),
(3, 'luxury time', 'luxury', 'good time', 5000.00, '3 days/2 nights', 'hotel,transport,meals,guide', '', 5.00, 0, '1776625241_1776544011_adventure.jpg', 3, 1, '', 'To be assigned', '+251 900 000 000', 'TBD', 'Main Airport'),
(4, 'hih', 'budget', 'hey u', 2000.00, '10 days', 'hotel', 'hohoh,hehe,kksdkljalksjd', 4.30, 44, '-5965188371728942657_120.jpg', 4, 1, 'hehe', 'To be assigned', '+251 900 000 000', 'TBD', 'Main Airport'),
(5, 'Historic North Ethiopia Tour', 'family', 'Explore Ethiopia\'s ancient Christian heritage and historic treasures. Visit the rock-hewn churches of Lalibela, the castles of Gondar, and the ancient obelisks of Axum.', 1850.00, '10 days', NULL, 'Monolithic and Semi-Monolithic Architecture,\"Monkey Head\" Construction,Ancient Monumental Stelae,Symbolic \"New Jerusalem\" Layout,Medieval European-Style Fortifications', 4.90, 233, '-6017365623010608261_121.jpg', 12, 1, 'cultural tour', 'To be assigned', '+251 900 000 000', 'TBD', 'Main Airport');

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
  `payment_type` enum('normal','premium') DEFAULT 'normal',
  `method` enum('cash','bank','mobile','card') DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','completed','failed','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `booking_id`, `user_id`, `payment_type`, `method`, `amount`, `status`, `created_at`) VALUES
(1, 4, 11, 'normal', 'mobile', 0.00, 'completed', '2026-05-07 06:10:44'),
(2, 5, 11, 'normal', 'mobile', 0.00, 'completed', '2026-05-07 06:16:33'),
(3, 6, 2, 'normal', 'mobile', 0.00, 'completed', '2026-05-08 06:34:57');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `destination_id` int(11) DEFAULT NULL,
  `package_id` int(11) DEFAULT NULL,
  `guide_id` int(11) DEFAULT NULL,
  `hotel_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'approved',
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
  `role` enum('admin','customer','tour_guide') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','suspended') DEFAULT 'active',
  `gender` varchar(20) DEFAULT 'Not Specified',
  `nationality` varchar(50) DEFAULT 'Ethiopian',
  `profile_pic` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `username`, `email`, `password`, `phone`, `role`, `created_at`, `status`, `gender`, `nationality`, `profile_pic`) VALUES
(1, 'Teshome Asrat', 'teshe', 'betelhembelayneh58@gmail.com', '$2y$10$QvnvZEb9yGBZZ5Iwz8ItHurLwUE9i5.JAmF4wZg9ewQLqL39hMUjW', '0923234444', 'admin', '2026-04-16 17:33:12', 'active', 'Not Specified', 'Ethiopian', NULL),
(2, 'Betelhem', 'lehem', 'betelhem.belayneh21@gmail.com', '$2y$10$WoO7h.9u5EJGo1Yu6JPJvu9sA9J5i45DH59oOJulLJyfV.XBPtUOa', '0956264326', 'customer', '2026-04-17 20:57:28', 'active', 'Not Specified', 'Ethiopian', 'profile_2_1777623983.png'),
(3, 'avi', 'avi32@gmail.com', 'avi32@gmail.com', '$2y$10$eAVHrWZJcJ6wY4iuJAwUW.mAPtndBkid.EhSoFaFnv6729j5HIPge', '0924322824', 'customer', '2026-04-18 10:52:17', 'active', 'Not Specified', 'Ethiopian', NULL),
(4, 'tigst belayneh', 'tigi@gmail.com', 'tigi@gmail.com', '$2y$10$movLGzMaiB9SPrnoSlQgxOD0OtWJZn1BSZBffQ8YBNTuhwygXtB5.', '0956264326', 'customer', '2026-04-18 11:24:46', 'active', 'Not Specified', 'Ethiopian', NULL),
(11, 'Bete', 'betora_ad', 'betelhem.belayneh58@gmail.com', '$2y$10$OdlTUXRWz5tSKPrqRLALJegHmga9DAx319LuXpwA2Sq0PRoPPqxvO', '0956264326', 'admin', '2026-04-23 08:15:45', 'active', 'Not Specified', 'Ethiopian', NULL),
(12, 'avenezer', 'avijii', '', '$2y$10$ASGgCVTGmLtnanjAScpZZutYi8xEHFbgqKijF4YSIFTpBkjGzHCYi', '', 'customer', '2026-04-28 22:36:00', 'active', 'Not Specified', 'Ethiopian', NULL),
(13, 'avenezer', 'avenezer3@gmail.com', 'avenezer3@gmail.com', '$2y$10$17Zj5AS1ifqPCmdfuhAV6OYIS3sOGvtcguZCaY2ccFpdo.KU0gCFS', '0910203040', 'customer', '2026-04-29 09:36:42', 'active', 'Not Specified', 'Ethiopian', NULL),
(14, 'avenezer', 'avenezer33@gmail.com', 'avenezer33@gmail.com', '$2y$10$PXy9Rl0AU3nh4tgkHxBHhOU.e22LLTXCu5hASVH0f2VLIS4B8CK/6', '0940504050', 'customer', '2026-04-29 09:40:23', 'active', 'Not Specified', 'Ethiopian', NULL),
(15, 'habte', 'habteGiorgis', 'habte@gmail.com', '$2y$10$bfY6ucRDwvX3Nc5HsyAYh.OtPawKiGg.aW5XsKowHIZPDdYZQgJ1q', '0988580336', 'customer', '2026-04-29 05:58:50', 'active', 'Not Specified', 'Ethiopian', NULL),
(16, 'brhane berihun', 'briti', 'brhane@gmail.com', '$2y$10$xd15dWDl7UdOMrr7vryoleqoJRjMm.a.gcNS/okuOiX2U8hAe4iFm', '0989786756', '', '2026-05-01 18:51:21', 'active', 'Not Specified', 'Ethiopian', NULL),
(17, 'hanna belayneh', 'hann', 'hanna@gmail.com', '$2y$10$TuHXy7Ymth2i5GRg74r6seZQa0ConYMzvxkWIVJsOWPhg42EEy1LK', '0910204455', 'customer', '2026-05-08 10:27:42', 'active', 'Not Specified', 'Ethiopian', NULL);

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
-- Indexes for table `deals`
--
ALTER TABLE `deals`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `guides`
--
ALTER TABLE `guides`
  ADD PRIMARY KEY (`id`),
  ADD KEY `destination_id` (`destination_id`),
  ADD KEY `fk_user_guide` (`user_id`);

--
-- Indexes for table `guide_availability`
--
ALTER TABLE `guide_availability`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_guide_availability_guide` (`guide_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `deals`
--
ALTER TABLE `deals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `destinations`
--
ALTER TABLE `destinations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guides`
--
ALTER TABLE `guides`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `guide_availability`
--
ALTER TABLE `guide_availability`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `package_destinations`
--
ALTER TABLE `package_destinations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

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
  ADD CONSTRAINT `fk_user_guide` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `guides_ibfk_1` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`);

--
-- Constraints for table `guide_availability`
--
ALTER TABLE `guide_availability`
  ADD CONSTRAINT `fk_guide_availability_guide` FOREIGN KEY (`guide_id`) REFERENCES `guides` (`id`) ON DELETE CASCADE;

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
