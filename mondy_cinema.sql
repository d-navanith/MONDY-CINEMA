-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 10, 2025 at 11:32 PM
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
-- Database: `mondy_cinema`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `showtime_id` int(11) NOT NULL,
  `booking_reference` varchar(50) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_email` varchar(100) NOT NULL,
  `customer_phone` varchar(20) NOT NULL,
  `seats_booked` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`seats_booked`)),
  `total_seats` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `booking_status` enum('confirmed','cancelled','pending') DEFAULT 'confirmed',
  `payment_status` enum('paid','pending','failed') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cinemas`
--

CREATE TABLE `cinemas` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(50) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `total_screens` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cinemas`
--

INSERT INTO `cinemas` (`id`, `name`, `address`, `city`, `phone`, `email`, `description`, `image`, `total_screens`, `created_at`) VALUES
(1, 'Mondy Cinema Colombo', '123 Galle Road, Colombo 03', 'Colombo', '+94 11 234 5678', 'colombo@mondycinema.lk', 'Premium cinema experience in the heart of Colombo with state-of-the-art technology and comfortable seating.', 'colombo.jpg', 4, '2025-08-26 17:56:15'),
(2, 'Mondy Cinema Kandy', '456 Peradeniya Road, Kandy', 'Kandy', '+94 81 234 5678', 'kandy@mondycinema.lk', 'Modern cinema complex in Kandy featuring the latest movies with superior sound and visual quality.', 'kandy.jpg', 3, '2025-08-26 17:56:15'),
(3, 'Mondy Cinema Galle', '789 Matara Road, Galle', 'Galle', '+94 91 234 5678', 'galle@mondycinema.lk', 'Coastal cinema experience with premium amenities and comfortable seating for the whole family.', 'galle.jpg', 2, '2025-08-26 17:56:15');

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `genre` varchar(100) DEFAULT NULL,
  `duration` int(11) NOT NULL,
  `rating` varchar(10) DEFAULT NULL,
  `director` varchar(100) DEFAULT NULL,
  `cast` text DEFAULT NULL,
  `release_date` date DEFAULT NULL,
  `poster_image` varchar(255) DEFAULT NULL,
  `trailer_url` varchar(500) DEFAULT NULL,
  `status` enum('active','inactive','coming_soon') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`id`, `title`, `description`, `genre`, `duration`, `rating`, `director`, `cast`, `release_date`, `poster_image`, `trailer_url`, `status`, `created_at`) VALUES
(1, 'Coolie', 'Deva, a retired coolie leader, uncovers a deadly crime syndicate and organ-trafficking racket after his friend’s mysterious death.', 'Action/Mystery & Thriller/Drama/Musical', 170, '2.5/5', 'Lokesh Kanagaraj', 'Rajinikanth,Nagarjuna Akkineni,Shruti Haasan,Soubin Shahir,Sathyaraj,Aamir Khan,Reba Monica John,Pooja Hegde,Baburaj', '2025-08-14', 'coolie.jpg', 'https://www.youtube.com/watch?v=TnhorwP9tcs', 'active', '2025-08-26 17:56:15'),
(2, 'Clarence: Rhythm of the Guitar (2025)', 'A biographical musical drama celebrating Clarence Wijewardena, the “Father of Sinhala Pop Music,” who revolutionized the music scene with the electric guitar in the 1960s.', 'Drama/Musical/ Biographical', 139, 'null', 'Theja Iddamalgoda', 'Damith Wijayathunga,Saranga Disasekara,Nihari Perera,Dinakshie Priyasad,Eshanka Jahanvi,Fernie Roshani,Chamila Peiris, Srimal Wedsinghe', '2025-06-27', 'clarence.jpg', 'https://www.youtube.com/embed/iJuykWYYvdI', 'active', '2025-08-26 17:56:15'),
(3, 'Saiyaara (2025)', 'A poignant musical romantic drama where troubled musician Krish Kapoor forms a deep bond with shy poetess Vaani Batra. Their love blossoms, but a heartbreaking Alzheimer’s diagnosis threatens their connection.', 'Musical/Romance', 156, '7/10', 'Mohit Suri', 'Ahaan Panday, Aneet Padda', '2024-02-15', 'saiyaara.jpg', 'https://www.youtube.com/embed/9r-tT5IN0vg', 'active', '2025-08-26 17:56:15'),
(4, 'Jurassic World: Rebirth', 'Taking place a few years after the events of Jurassic World Dominion, this film explores a world where dinosaurs are living alongside humans in limited, isolated environments near the equator. A team is tasked with a mission to retrieve vital biomaterial from three of the largest prehistoric animals, which holds the key to a new heart disease treatment.', 'Science Fiction/Action/Adventure', 133, 'PG-13', 'Gareth Edwards', 'Scarlett Johansson, Mahershala Ali, Jonathan Bailey, Rupert Friend, Manuel Garcia-Rulfo, Ed Skrein', '2025-07-02', 'jurassic.jpg', 'https://www.youtube.com/watch?v=jan5CFWs9ic', 'coming_soon', '2025-08-26 17:56:15'),
(5, 'Weapons', 'From the director of Barbarian, this mystery horror film follows the mysterious and inexplicable disappearance of 17 children from the same classroom, who all vanished from their homes at the same time on the same night. The film explores the events from multiple viewpoints as the community tries to understand who or what is behind their disappearance.', 'Mystery/Horror/Thriller', 129, 'PG-13', 'Zach Cregger', 'Josh Brolin, Julia Garner, Alden Ehrenreich, Austin Abrams, Cary Christopher, Benedict Wong, Amy Madigan', '2025-08-08', 'weapons.jpg', 'https://www.youtube.com/watch?v=OpThntO9ixc', 'coming_soon', '2025-08-26 17:56:15'),
(6, 'Sketch', 'When a young girl\'s sketchbook falls into a strange, magical pond, her fantastical drawings of monsters and creatures come to life. As chaos erupts in their town, the family must work together to track down the monsters they unleashed and stop the disaster before it causes permanent damage.', 'Fantasy/Comedy/Horror', 92, 'PG-13', 'Seth Worley', 'Tony Hale, D\'Arcy Carden, Bianca Belle, Kue Lawrence', '2025-08-06', 'sketch.jpg', 'https://www.youtube.com/watch?v=mj0moG3P2pw', 'coming_soon', '2025-08-26 17:56:15'),
(7, 'How to Train Your Dragon', 'A young Viking named Hiccup befriends a dragon named Toothless, challenging his tribe\'s tradition of dragon slaying. Together, they bridge the gap between humans and dragons in a tale of courage and acceptance.', 'Animation/Adventure/ Fantasy', 98, 'PG', 'Dean DeBlois, Chris Sanders', 'Jay Baruchel, Gerard Butler, America Ferrera, Craig Ferguson', '2025-06-13', 'dragon.jpg', 'https://www.youtube.com/watch?v=22w7z_lT6YM', 'active', '2025-09-10 19:59:29'),
(8, 'Jurassic World: Rebirth', 'Set five years after Dominion, dinosaurs now coexist with humans but face extinction. A covert operation to harvest DNA from the three largest species leads to a perilous mission on a dinosaur-infested island.', 'Sci-Fi /Adventure/Thriller', 133, 'PG-13', 'Gareth Edwards', 'Scarlett Johansson, Mahershala Ali, Jonathan Bailey, Rupert Friend, Manuel Garcia-Rulfo', '2025-07-02', 'jurassic.jpg', 'https://www.youtube.com/watch?v=jan5CFWs9ic', 'active', '2025-09-10 20:24:32');

-- --------------------------------------------------------

--
-- Table structure for table `screens`
--

CREATE TABLE `screens` (
  `id` int(11) NOT NULL,
  `cinema_id` int(11) NOT NULL,
  `screen_number` int(11) NOT NULL,
  `screen_name` varchar(50) DEFAULT NULL,
  `total_seats` int(11) DEFAULT 100,
  `seat_layout` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`seat_layout`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `screens`
--

INSERT INTO `screens` (`id`, `cinema_id`, `screen_number`, `screen_name`, `total_seats`, `seat_layout`) VALUES
(1, 1, 1, 'Screen 1 - Premium', 120, NULL),
(2, 1, 2, 'Screen 2 - Standard', 100, NULL),
(3, 1, 3, 'Screen 3 - Standard', 100, NULL),
(4, 1, 4, 'Screen 4 - IMAX', 150, NULL),
(5, 2, 1, 'Screen 1 - Premium', 100, NULL),
(6, 2, 2, 'Screen 2 - Standard', 80, NULL),
(7, 2, 3, 'Screen 3 - Standard', 80, NULL),
(8, 3, 1, 'Screen 1 - Premium', 90, NULL),
(9, 3, 2, 'Screen 2 - Standard', 70, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `seat_bookings`
--

CREATE TABLE `seat_bookings` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `showtime_id` int(11) NOT NULL,
  `seat_number` varchar(10) NOT NULL,
  `seat_row` varchar(5) NOT NULL,
  `seat_column` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `showtimes`
--

CREATE TABLE `showtimes` (
  `id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `screen_id` int(11) NOT NULL,
  `show_date` date NOT NULL,
  `show_time` time NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `available_seats` int(11) DEFAULT 100,
  `status` enum('active','cancelled','full') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `showtimes`
--

INSERT INTO `showtimes` (`id`, `movie_id`, `screen_id`, `show_date`, `show_time`, `price`, `available_seats`, `status`, `created_at`) VALUES
(1, 1, 1, '2025-08-26', '10:00:00', 1500.00, 120, 'active', '2025-08-26 17:56:15'),
(2, 1, 1, '2025-08-26', '13:30:00', 1500.00, 120, 'active', '2025-08-26 17:56:15'),
(3, 1, 1, '2025-08-26', '17:00:00', 1800.00, 120, 'active', '2025-08-26 17:56:15'),
(4, 1, 1, '2025-08-26', '20:30:00', 1800.00, 120, 'active', '2025-08-26 17:56:15'),
(5, 2, 2, '2025-08-26', '11:00:00', 1400.00, 100, 'active', '2025-08-26 17:56:15'),
(6, 2, 2, '2025-08-26', '15:00:00', 1400.00, 100, 'active', '2025-08-26 17:56:15'),
(7, 2, 2, '2025-08-26', '19:00:00', 1600.00, 100, 'active', '2025-08-26 17:56:15'),
(8, 3, 3, '2025-08-26', '12:00:00', 1400.00, 100, 'active', '2025-08-26 17:56:15'),
(9, 3, 3, '2025-08-26', '16:00:00', 1400.00, 100, 'active', '2025-08-26 17:56:15'),
(10, 3, 3, '2025-08-26', '20:00:00', 1600.00, 100, 'active', '2025-08-26 17:56:15'),
(11, 1, 1, '2025-08-27', '10:00:00', 1500.00, 120, 'active', '2025-08-26 17:56:15'),
(12, 1, 1, '2025-08-27', '13:30:00', 1500.00, 120, 'active', '2025-08-26 17:56:15'),
(13, 1, 1, '2025-08-27', '17:00:00', 1800.00, 120, 'active', '2025-08-26 17:56:15'),
(14, 1, 1, '2025-08-27', '20:30:00', 1800.00, 120, 'active', '2025-08-26 17:56:15'),
(15, 2, 2, '2025-08-27', '11:00:00', 1400.00, 100, 'active', '2025-08-26 17:56:15'),
(16, 2, 2, '2025-08-27', '15:00:00', 1400.00, 100, 'active', '2025-08-26 17:56:15'),
(17, 2, 2, '2025-08-27', '19:00:00', 1600.00, 100, 'active', '2025-08-26 17:56:15'),
(18, 3, 3, '2025-08-27', '12:00:00', 1400.00, 100, 'active', '2025-08-26 17:56:15'),
(19, 3, 3, '2025-08-27', '16:00:00', 1400.00, 100, 'active', '2025-08-26 17:56:15'),
(20, 3, 3, '2025-08-27', '20:00:00', 1600.00, 100, 'active', '2025-08-26 17:56:15');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('admin','customer') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `terms_accepted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `phone`, `role`, `created_at`, `updated_at`, `terms_accepted`) VALUES
(1, 'Admin User', 'admin@mondycinema.lk', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'admin', '2025-08-26 17:56:15', '2025-09-08 21:13:10', 1),
(2, 'John Doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'customer', '2025-08-26 17:56:15', '2025-09-08 21:15:38', 1),
(3, 'De Silva', 'silva@gmail.com', '$2y$10$iV6qhNktJIjfjRAeRfzud.xW2nqpKA27i/F08FlB/kXPCOdYMQtcS', '0712345678', 'customer', '2025-09-10 16:12:30', '2025-09-10 16:12:30', 0),
(4, 'example', 'example@gmail.com', '$2y$10$lovW/qLzP9I26n2mYf5VD.1H0ocFfp7cFJQuID1rAO81t8W7L1TGK', '0714567891', 'customer', '2025-09-10 19:55:01', '2025-09-10 19:56:44', 0),
(5, 'Example 1', 'example1@gmail.com', '$2y$10$bjwWoXfplTs/i5FmK7hdn.K4upxQIi9lDfb/99SJk5alGtg5Fp2b.', '0714567842', 'customer', '2025-09-10 20:52:39', '2025-09-10 20:54:25', 0),
(6, 'Example2', 'example2@gmail.com', '$2y$10$ZdJzvmUdL8NxY9Yxrgy.n.Cfwc8q.TBpqxAsMkJbAI3KNqYeDCpou', '0718889991', 'customer', '2025-09-10 21:04:04', '2025-09-10 21:05:37', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `booking_reference` (`booking_reference`),
  ADD KEY `idx_bookings_user` (`user_id`),
  ADD KEY `idx_bookings_showtime` (`showtime_id`);

--
-- Indexes for table `cinemas`
--
ALTER TABLE `cinemas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `screens`
--
ALTER TABLE `screens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cinema_id` (`cinema_id`);

--
-- Indexes for table `seat_bookings`
--
ALTER TABLE `seat_bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_seat_showtime` (`showtime_id`,`seat_number`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `idx_seat_bookings_showtime` (`showtime_id`);

--
-- Indexes for table `showtimes`
--
ALTER TABLE `showtimes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `screen_id` (`screen_id`),
  ADD KEY `idx_showtimes_date` (`show_date`),
  ADD KEY `idx_showtimes_movie` (`movie_id`);

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
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cinemas`
--
ALTER TABLE `cinemas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `screens`
--
ALTER TABLE `screens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `seat_bookings`
--
ALTER TABLE `seat_bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `showtimes`
--
ALTER TABLE `showtimes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`showtime_id`) REFERENCES `showtimes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `screens`
--
ALTER TABLE `screens`
  ADD CONSTRAINT `screens_ibfk_1` FOREIGN KEY (`cinema_id`) REFERENCES `cinemas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `seat_bookings`
--
ALTER TABLE `seat_bookings`
  ADD CONSTRAINT `seat_bookings_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `seat_bookings_ibfk_2` FOREIGN KEY (`showtime_id`) REFERENCES `showtimes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `showtimes`
--
ALTER TABLE `showtimes`
  ADD CONSTRAINT `showtimes_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `showtimes_ibfk_2` FOREIGN KEY (`screen_id`) REFERENCES `screens` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
