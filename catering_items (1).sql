-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 11, 2025 at 03:01 PM
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
-- Database: `sorridi-pizza`
--

-- --------------------------------------------------------

--
-- Table structure for table `catering_items`
--

CREATE TABLE `catering_items` (
  `id` int(11) NOT NULL,
  `catering_title` varchar(255) NOT NULL,
  `catering_detail` text DEFAULT NULL,
  `more_details` text DEFAULT NULL,
  `images` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `catering_items`
--

INSERT INTO `catering_items` (`id`, `catering_title`, `catering_detail`, `more_details`, `images`) VALUES
(18, 'Hammad', 'Hassan', 'Great Deal offer!', 'assets/img/1754475894_deal-box-2.png,assets/img/1754475894_deal-box-3.png,assets/img/1754475894_deal-box-4.png,assets/img/1754475894_deal-box-5.png'),
(20, 'Sorridi', 'Pizza', 'Pizzza! Maze se khao', 'uploaded_images/1754901313_6899ab41b5d05.jpg,uploaded_images/1754901313_6899ab41b6003.jpg,assets/img/1754476227_card-bg-img3.png,assets/img/1754476227_card-bg-img4.png'),
(23, 'Sajwal', 'Digital marketing', 'good  in his field..', 'uploaded_images/1754724549_6896f8c5d1500.png,uploaded_images/1754724549_6896f8c5d180e.png,uploaded_images/1754724549_6896f8c5d1988.png,uploaded_images/1754724549_6896f8c5d1af6.png'),
(25, 'Business Lunches', 'write a line a of paragraph', 'These are my business Lunches team.. They have experience to do multiple jobs.  ', 'assets/img/1754740237_about-img1.png,assets/img/1754740237_about-img2.png,assets/img/1754740237_about-img3.png,assets/img/1754740237_about-img4.png'),
(28, 'Furqan', 'Ali', 'I like pizza with Mint Cold drink.', 'assets/img/1754899099_about-img1.png,assets/img/1754899099_hero-profile-img (3).png,assets/img/1754899099_catering1 (1).jpg,assets/img/1754899099_jelly-cake.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `catering_items`
--
ALTER TABLE `catering_items`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `catering_items`
--
ALTER TABLE `catering_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
