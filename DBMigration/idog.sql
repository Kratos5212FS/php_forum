-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 30, 2021 at 04:07 AM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `idog`
--

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `article` text NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `title`, `article`, `created_at`) VALUES
(44, 7, 'im gabi', 'hello i am gabi', '2021-11-26 10:08:48'),
(45, 7, 'im new here', 'im new hereim new here', '2021-11-26 10:08:57'),
(52, 8, 'asdasd', 'asdasdasdadsasd', '2021-11-27 03:57:47'),
(54, 6, 'sdfsdfsa', 'adsfasdfasdfasdf', '2021-11-27 05:24:27'),
(55, 6, 'sdasdas', 'adsasasdasds123', '2021-11-27 05:38:29'),
(93, 9, 'daasdfasdf', 'agfsdfghsdfhgadha', '2021-11-30 04:29:24'),
(95, 9, 'asdfasd', 'asdfasdfasdfasfd', '2021-11-30 04:35:09'),
(96, 9, 'fdgdf', 'fdgdfgfddg', '2021-11-30 04:38:50'),
(97, 9, 'fgsdfg', 'fgsdgsdfgsdfgsdfg', '2021-11-30 04:39:27'),
(98, 10, 'test', 'aljk;ofdhadsljkfhlkajs', '2021-11-30 04:41:07'),
(99, 10, 'sassad', 'saddfsdsdgfdfg', '2021-11-30 04:50:05');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `profile_image` varchar(255) NOT NULL,
  `joined_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `name`, `profile_image`, `joined_at`) VALUES
(5, 'gabigosh@gmail.com', '$2y$10$r.0w6MHIWWErd06nghqcUuAgVNUzPOB.Qf1kwjrkV9jQK3Lt/lGlW', 'Gabi Goshen', 'profile-image.png', '2021-11-26'),
(6, 'someguy@gmail.com', '$2y$10$ImzBlkFK2AulawPPC3CBjOzRiMxVI9NP/obX3EAd2pZwzk5xWSYGO', 'someguy', 'profile-image.png', '2021-11-26'),
(7, 'gabi@gmail.com', '$2y$10$jIyv41JpDcFHcURs0fDrFuyeSpJQI4tmC3LPEhKQXfaom0VR28oxy', 'Gabi Goshen', 'profile-image.png', '2021-11-26'),
(8, 'abughoshg@gmail.com', '$2y$10$EyM6nDe7gBxjAHAGFi5KseFaDxCJYB9/4vdRNcJNsb8xQCVQINo/G', 'Ghaleb', '1637978259_86.svg', '2021-11-27'),
(9, 'gabushi@gmail.com', '$2y$10$wo4.lkq3Mp2EJxv7ydRtOeV/AhxbBwC89zKPh5K53TfLsj.xR3Bxy', 'Gabushi', '2021.11.27.03.14.42-dog.jpg', '2021-11-27'),
(10, 'gabgab@gmail.com', '$2y$10$QO5Mh1YkSsnln8GPpFYUrON/7w0OIbDGYm0TGLlLG4sNrexuuMJd6', 'Gabgab', '2021.11.30.03.40.21-dog.jpg', '2021-11-30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
