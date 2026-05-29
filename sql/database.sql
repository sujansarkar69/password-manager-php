-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 29, 2026 at 08:52 PM
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
-- Database: `password_manager`
--

-- --------------------------------------------------------

--
-- Table structure for table `password_records`
--

CREATE TABLE `password_records` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `service_name` varchar(150) NOT NULL,
  `encrypted_password` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_records`
--

INSERT INTO `password_records` (`id`, `user_id`, `service_name`, `encrypted_password`, `created_at`) VALUES
(1, 1, 'Gmail', '5H82zRpYzuF34kmSUrDqxHexKnG2PLarlP4mMuP/j8s=', '2026-05-29 12:34:22'),
(2, 2, 'room', 'RgNte2GeiUM99jlT82Bqqvfqb7MISvBNeGRKasMMxf4=', '2026-05-29 21:47:18');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `encrypted_key` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password_hash`, `encrypted_key`, `created_at`) VALUES
(1, 'student', '$2y$10$TsLfRe7sJAOFKGB/pi.uzeKUPL6N56odrSCAyLr1DehOcxBbdoCxa', 'YceOnVgtocHn1ukKf1nTUub+my3ll3BgrWrz2wHekL/sona02crBKOlB8eMn3lXm8IAY0YPe0kMs2clwtMgfw7/VL90IX6kiyQh+IMwtIMuPptNsvlzNVX61iGlFnWK0', '2026-05-29 12:19:57'),
(2, 'sujan', '$2y$10$FU3VsWNCNzVKPoKinvdOJu4z9MxYaH4REYdDmsShl0ThRSJaFkIay', 'ki8b5tbw5JCLFdIOM5Q/JTGZlRxYcl3l54VkexzOtu6WY5P0+jt/oxOb3OWIbgmC0WwW+jROgCR+c6lwr+/N1DJm1OKaXSggBkXfqvNWM1zonkLlg1ppDEoFBHaUVHVE', '2026-05-29 21:45:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `password_records`
--
ALTER TABLE `password_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `password_records`
--
ALTER TABLE `password_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `password_records`
--
ALTER TABLE `password_records`
  ADD CONSTRAINT `password_records_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
