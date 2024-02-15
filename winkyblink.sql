-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 13, 2024 at 04:06 PM
-- Server version: 10.4.16-MariaDB
-- PHP Version: 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `winkyblink`
--

-- --------------------------------------------------------

--
-- Table structure for table `blasts`
--

CREATE TABLE `blasts` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `opponent_id` int(11) DEFAULT NULL,
  `is_newly_added` tinyint(1) DEFAULT 1,
  `create_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `blasts`
--

INSERT INTO `blasts` (`id`, `user_id`, `opponent_id`, `is_newly_added`, `create_date`, `update_date`) VALUES
(1, 1, 2, 1, '2023-06-28 14:51:31', '2023-06-28 14:51:31'),
(31, 1, 43, 1, '2024-01-14 05:48:00', '2024-01-14 05:48:00'),
(34, 2, 43, 1, '2024-01-14 05:48:00', '2024-01-14 05:48:00'),
(35, 3, 43, 1, '2024-01-15 08:16:16', '2024-01-15 08:16:16'),
(36, 4, 43, 1, '2024-01-14 05:48:00', '2024-01-14 05:48:00'),
(37, 5, 43, 1, '2024-01-15 08:16:16', '2024-01-15 08:16:16'),
(38, 6, 43, 1, '2024-01-14 05:48:00', '2024-01-14 05:48:00'),
(39, 7, 43, 1, '2024-01-15 08:16:16', '2024-01-15 08:16:16'),
(40, 8, 43, 1, '2023-06-28 14:51:31', '2023-06-28 14:51:31'),
(41, 9, 43, 1, '2024-01-14 05:48:00', '2024-01-14 05:48:00'),
(42, 10, 43, 1, '2024-01-15 08:16:16', '2024-01-15 08:16:16'),
(43, 11, 43, 1, '2024-01-14 05:48:00', '2024-01-14 05:48:00'),
(44, 12, 43, 1, '2024-01-15 08:16:16', '2024-01-15 08:16:16'),
(45, 13, 43, 1, '2024-01-14 05:48:00', '2024-01-14 05:48:00'),
(46, 14, 43, 0, '2024-01-15 08:16:16', '2024-01-15 08:16:16'),
(47, 15, 43, 1, '2024-01-14 05:48:00', '2024-01-14 05:48:00'),
(48, 16, 43, 0, '2024-01-15 08:16:16', '2024-01-15 08:16:16'),
(65, 9, 43, 1, '2024-01-14 05:48:00', '2024-01-14 05:48:00'),
(66, 10, 43, 1, '2024-01-15 08:16:16', '2024-01-15 08:16:16'),
(67, 11, 43, 1, '2024-01-14 05:48:00', '2024-01-14 05:48:00'),
(68, 12, 43, 1, '2024-01-15 08:16:16', '2024-01-15 08:16:16'),
(69, 13, 43, 1, '2024-01-14 05:48:00', '2024-01-14 05:48:00'),
(70, 14, 43, 0, '2024-01-15 08:16:16', '2024-01-15 08:16:16'),
(71, 15, 43, 1, '2024-01-14 05:48:00', '2024-01-14 05:48:00'),
(72, 16, 43, 0, '2024-01-15 08:16:16', '2024-01-15 08:16:16');

-- --------------------------------------------------------

--
-- Table structure for table `blocks`
--

CREATE TABLE `blocks` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `opponent_id` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `blocks`
--

INSERT INTO `blocks` (`id`, `user_id`, `opponent_id`, `create_date`, `update_date`) VALUES
(1, 1, 2, '2023-06-28 14:59:25', '2023-06-28 14:59:25');

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `opponent_id` int(11) DEFAULT NULL,
  `is_newly_added` tinyint(1) DEFAULT 1,
  `last_message_user_id` int(11) DEFAULT NULL,
  `last_message` text DEFAULT NULL,
  `last_message_date` timestamp NULL DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `conversations`
--

INSERT INTO `conversations` (`id`, `user_id`, `opponent_id`, `is_newly_added`, `last_message_user_id`, `last_message`, `last_message_date`, `create_date`, `update_date`) VALUES
(1, 1, 2, 1, 1, 'Hi, How are you?', '0000-00-00 00:00:00', '2023-06-28 16:19:27', '2023-06-28 16:19:27'),
(2, 2, 1, 1, 1, 'Hi, How are you?', '0000-00-00 00:00:00', '2023-06-28 16:19:27', '2023-06-28 16:19:27');

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` int(100) NOT NULL,
  `question` char(255) DEFAULT NULL,
  `answer` varchar(500) DEFAULT NULL,
  `other` varchar(500) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `answer`, `other`, `create_date`, `update_date`) VALUES
(1, '1-Lorem ipsum dolor sit amet, cons ectetur adipiscing elit?', '1-sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. ', NULL, '2024-02-05 03:06:12', '2024-02-05 03:06:17'),
(2, '2-Lorem ipsum dolor sit amet, cons ectetur adipiscing elit?', '2-sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. ', NULL, '2024-02-05 03:07:00', '2024-02-05 03:07:03'),
(3, '3-Lorem ipsum dolor sit amet, cons ectetur adipiscing elit?', '3-sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. ', NULL, '2024-02-05 03:07:05', '2024-02-05 03:07:07'),
(4, '4-Lorem ipsum dolor sit amet, cons ectetur adipiscing elit?', '4-sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. ', NULL, '2024-02-05 03:07:10', '2024-02-05 03:07:12');

-- --------------------------------------------------------

--
-- Table structure for table `helps`
--

CREATE TABLE `helps` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` text DEFAULT NULL,
  `subject` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `keys`
--

CREATE TABLE `keys` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `key` varchar(40) NOT NULL,
  `level` int(2) NOT NULL,
  `ignore_limits` tinyint(1) NOT NULL DEFAULT 0,
  `is_private_key` tinyint(1) NOT NULL DEFAULT 0,
  `ip_addresses` text DEFAULT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `keys`
--

INSERT INTO `keys` (`id`, `user_id`, `key`, `level`, `ignore_limits`, `is_private_key`, `ip_addresses`, `date_created`) VALUES
(1, 1, 'CLOUDTENLABS_WANG', 0, 0, 0, NULL, '2018-10-11 13:34:33');

-- --------------------------------------------------------

--
-- Table structure for table `matches`
--

CREATE TABLE `matches` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `opponent_id` int(11) DEFAULT NULL,
  `is_newly_added` tinyint(1) DEFAULT 1,
  `create_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `matches`
--

INSERT INTO `matches` (`id`, `user_id`, `opponent_id`, `is_newly_added`, `create_date`, `update_date`) VALUES
(1, 2, 1, 1, '2023-06-28 16:00:35', '2023-06-28 16:00:35'),
(2, 1, 2, 1, '2023-06-28 16:00:35', '2023-06-28 16:00:35'),
(79, 1, 36, 1, '2024-01-15 13:50:01', '2024-01-15 13:50:01'),
(80, 36, 1, 1, '2024-01-15 13:50:01', '2024-01-15 13:50:01'),
(81, 3, 36, 1, '2024-01-15 13:50:02', '2024-01-15 13:50:02'),
(82, 36, 3, 1, '2024-01-15 13:50:02', '2024-01-15 13:50:02'),
(83, 11, 36, 1, '2024-01-15 13:50:03', '2024-01-15 13:50:03'),
(84, 36, 11, 1, '2024-01-15 13:50:04', '2024-01-15 13:50:04'),
(85, 43, 42, 1, '2024-01-15 13:50:03', '2024-01-15 13:50:03'),
(86, 42, 43, 1, '2024-01-15 13:50:04', '2024-01-15 13:50:04'),
(87, 44, 45, 1, '2024-01-15 13:50:03', '2024-01-15 13:50:03'),
(88, 45, 44, 1, '2024-01-15 13:50:04', '2024-01-15 13:50:04'),
(89, 1, 43, 1, '2024-01-15 13:50:03', '2024-01-15 13:50:03'),
(90, 43, 1, 1, '2024-01-15 13:50:04', '2024-01-15 13:50:04'),
(91, 2, 43, 1, '2024-01-15 13:50:03', '2024-01-15 13:50:03'),
(92, 43, 2, 1, '2024-01-15 13:50:04', '2024-01-15 13:50:04'),
(93, 3, 43, 1, '2023-06-28 16:00:35', '2023-06-28 16:00:35'),
(94, 43, 3, 1, '2023-06-28 16:00:35', '2023-06-28 16:00:35'),
(95, 4, 43, 1, '2024-01-15 13:50:01', '2024-01-15 13:50:01'),
(96, 43, 4, 1, '2024-01-15 13:50:01', '2024-01-15 13:50:01'),
(97, 5, 43, 1, '2024-01-15 13:50:02', '2024-01-15 13:50:02'),
(98, 43, 5, 1, '2024-01-15 13:50:02', '2024-01-15 13:50:02'),
(99, 6, 43, 1, '2024-01-15 13:50:03', '2024-01-15 13:50:03'),
(100, 43, 6, 1, '2024-01-15 13:50:04', '2024-01-15 13:50:04'),
(101, 7, 43, 1, '2024-01-15 13:50:03', '2024-01-15 13:50:03'),
(102, 43, 7, 1, '2024-01-15 13:50:04', '2024-01-15 13:50:04'),
(103, 8, 43, 1, '2024-01-15 13:50:03', '2024-01-15 13:50:03'),
(104, 43, 8, 0, '2024-01-15 13:50:04', '2024-01-15 13:50:04'),
(105, 9, 43, 1, '2024-01-15 13:50:03', '2024-01-15 13:50:03'),
(106, 43, 9, 0, '2024-01-15 13:50:04', '2024-01-15 13:50:04'),
(107, 10, 43, 1, '2024-01-15 13:50:03', '2024-01-15 13:50:03'),
(108, 43, 10, 1, '2024-01-15 13:50:04', '2024-01-15 13:50:04'),
(109, 6, 43, 1, '2024-01-15 13:50:03', '2024-01-15 13:50:03'),
(110, 43, 6, 1, '2024-01-15 13:50:04', '2024-01-15 13:50:04'),
(111, 7, 43, 1, '2024-01-15 13:50:03', '2024-01-15 13:50:03'),
(112, 43, 7, 1, '2024-01-15 13:50:04', '2024-01-15 13:50:04'),
(113, 8, 43, 1, '2024-01-15 13:50:03', '2024-01-15 13:50:03'),
(114, 43, 8, 0, '2024-01-15 13:50:04', '2024-01-15 13:50:04'),
(115, 9, 43, 1, '2024-01-15 13:50:03', '2024-01-15 13:50:03'),
(116, 43, 9, 0, '2024-01-15 13:50:04', '2024-01-15 13:50:04'),
(117, 10, 43, 1, '2024-01-15 13:50:03', '2024-01-15 13:50:03'),
(118, 43, 10, 1, '2024-01-15 13:50:04', '2024-01-15 13:50:04'),
(119, 10, 43, 1, '2024-01-15 13:50:03', '2024-01-15 13:50:03'),
(120, 43, 10, 1, '2024-01-15 13:50:04', '2024-01-15 13:50:04'),
(121, 6, 43, 1, '2024-01-15 13:50:03', '2024-01-15 13:50:03'),
(122, 43, 6, 1, '2024-01-15 13:50:04', '2024-01-15 13:50:04'),
(123, 7, 43, 1, '2024-01-15 13:50:03', '2024-01-15 13:50:03'),
(124, 43, 7, 1, '2024-01-15 13:50:04', '2024-01-15 13:50:04'),
(125, 8, 43, 1, '2024-01-15 13:50:03', '2024-01-15 13:50:03'),
(126, 43, 8, 0, '2024-01-15 13:50:04', '2024-01-15 13:50:04'),
(127, 9, 43, 1, '2024-01-15 13:50:03', '2024-01-15 13:50:03'),
(128, 43, 9, 0, '2024-01-15 13:50:04', '2024-01-15 13:50:04'),
(129, 10, 43, 1, '2024-01-15 13:50:03', '2024-01-15 13:50:03'),
(130, 43, 10, 1, '2024-01-15 13:50:04', '2024-01-15 13:50:04'),
(131, 1, 43, 1, '2024-01-15 13:50:03', '2024-01-15 13:50:03'),
(132, 43, 1, 1, '2024-01-15 13:50:04', '2024-01-15 13:50:04'),
(133, 2, 43, 1, '2024-01-15 13:50:03', '2024-01-15 13:50:03'),
(134, 43, 2, 1, '2024-01-15 13:50:04', '2024-01-15 13:50:04'),
(135, 3, 43, 1, '2023-06-28 16:00:35', '2023-06-28 16:00:35'),
(136, 43, 3, 1, '2023-06-28 16:00:35', '2023-06-28 16:00:35'),
(137, 4, 43, 1, '2024-01-15 13:50:01', '2024-01-15 13:50:01'),
(138, 43, 4, 1, '2024-01-15 13:50:01', '2024-01-15 13:50:01');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `source_type` varchar(16) DEFAULT NULL,
  `source_id` int(8) DEFAULT NULL,
  `is_newly_added` tinyint(1) DEFAULT 1,
  `create_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) UNSIGNED NOT NULL,
  `question` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `create_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `opponent_id` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `user_id`, `opponent_id`, `create_date`, `update_date`) VALUES
(1, 1, 2, '2023-06-28 15:00:19', '2023-06-28 15:00:19'),
(3, 43, 42, '2024-01-28 12:51:11', '2024-01-28 12:51:11');

-- --------------------------------------------------------

--
-- Table structure for table `swipes`
--

CREATE TABLE `swipes` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `opponent_id` int(11) DEFAULT NULL,
  `type` enum('Wink','Blink') DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `swipes`
--

INSERT INTO `swipes` (`id`, `user_id`, `opponent_id`, `type`, `create_date`, `update_date`) VALUES
(2, 2, 1, 'Wink', '2023-06-10 00:00:00', '2023-06-10 00:00:00'),
(5, 1, 2, 'Wink', '2023-06-28 16:00:35', '2023-06-28 16:00:35'),
(207, 1, 36, 'Wink', '2024-01-15 04:54:03', '2024-01-15 04:54:03'),
(210, 3, 36, 'Wink', '2024-01-15 04:54:03', '2024-01-15 04:54:03'),
(211, 11, 36, 'Wink', '2024-01-15 04:54:49', '2024-01-15 04:54:49'),
(230, 36, 1, 'Wink', '2024-01-15 13:50:01', '2024-01-15 13:50:01'),
(231, 36, 3, 'Wink', '2024-01-15 13:50:02', '2024-01-15 13:50:02'),
(232, 36, 11, 'Wink', '2024-01-15 13:50:03', '2024-01-15 13:50:03'),
(233, 42, 43, 'Wink', '2024-01-15 13:50:02', '2024-01-15 13:50:02'),
(234, 43, 42, 'Wink', '2024-01-15 13:50:03', '2024-01-15 13:50:03'),
(235, 43, 1, 'Blink', '2024-01-27 19:05:20', '2024-01-27 19:05:20'),
(236, 43, 2, 'Blink', '2024-01-27 19:05:32', '2024-01-27 19:05:32'),
(237, 43, 3, 'Blink', '2024-01-27 19:08:36', '2024-01-27 19:08:36'),
(238, 43, 4, 'Blink', '2024-01-27 19:11:39', '2024-01-27 19:11:39');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `qb_id` varchar(64) DEFAULT NULL,
  `uid` varchar(64) DEFAULT NULL,
  `stripe_customer_id` varchar(128) DEFAULT NULL,
  `password` varchar(64) DEFAULT NULL,
  `name` varchar(64) DEFAULT NULL,
  `verification_level` int(11) DEFAULT NULL,
  `verification_code` varchar(64) DEFAULT NULL,
  `gender` enum('Male','Female') DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  `zip_code` varchar(64) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `subscribed_plan` enum('Basic','Plus','Premium') DEFAULT NULL,
  `subscribed_date` datetime DEFAULT NULL,
  `subscribed_plan_assigned_by_admin` enum('Basic','Plus','Premium') DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `body_type` varchar(64) DEFAULT NULL,
  `drink_type` enum('Never','Ocassionally','Regularly','Socially') DEFAULT NULL,
  `smoke_type` enum('Never','Ocassionally','Regularly','Socially') DEFAULT NULL,
  `education_level` varchar(64) DEFAULT NULL,
  `consider_myself` text DEFAULT NULL,
  `idea_of_fun` text DEFAULT NULL,
  `cultural_background` text DEFAULT NULL,
  `favorite_movies` text DEFAULT NULL,
  `favorite_artists` text DEFAULT NULL,
  `interests` text DEFAULT NULL,
  `hobbies` text DEFAULT NULL,
  `fun_fact_about_me` text DEFAULT NULL,
  `introduction_video_clip` varchar(64) DEFAULT NULL,
  `is_terms_accepted` tinyint(1) DEFAULT 0,
  `is_privacy_accepted` tinyint(1) DEFAULT 0,
  `latitude` double DEFAULT NULL,
  `longtidue` double DEFAULT NULL,
  `address` text DEFAULT NULL,
  `is_flex_gps_enabled` tinyint(1) DEFAULT 0,
  `is_ghost_mode_enabled` tinyint(1) DEFAULT 0,
  `is_travel_mode_enabled` tinyint(1) DEFAULT 0,
  `is_winkyblinking_enabled` tinyint(1) DEFAULT 0,
  `is_winky_badge_enabled` tinyint(1) DEFAULT 0,
  `is_in_app_audio_chat_enabled` tinyint(1) DEFAULT 0,
  `winkyblasts_count` int(11) DEFAULT 0,
  `is_notification_promotional_enabled` tinyint(1) DEFAULT 0,
  `is_notification_message_enabled` tinyint(1) DEFAULT 0,
  `is_notification_winkyblasts_enabled` tinyint(1) DEFAULT 0,
  `is_notification_speed_dating_enabled` tinyint(1) DEFAULT 0,
  `is_notification_virtual_dates_enabled` tinyint(1) DEFAULT 0,
  `create_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `is_swipe_tutorial_shown` tinyint(1) DEFAULT 0,
  `is_home_tutorial_shown` tinyint(1) DEFAULT 0,
  `is_deleted` tinyint(1) DEFAULT 0,
  `in_app_audio_subscribed_date` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `winkyblink_subscribed_date` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `travel_mode_subscribed_date` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `ghost_mode_subscribed_date` datetime NOT NULL DEFAULT '1000-01-01 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `qb_id`, `uid`, `stripe_customer_id`, `password`, `name`, `verification_level`, `verification_code`, `gender`, `email`, `zip_code`, `date_of_birth`, `subscribed_plan`, `subscribed_date`, `subscribed_plan_assigned_by_admin`, `height`, `body_type`, `drink_type`, `smoke_type`, `education_level`, `consider_myself`, `idea_of_fun`, `cultural_background`, `favorite_movies`, `favorite_artists`, `interests`, `hobbies`, `fun_fact_about_me`, `introduction_video_clip`, `is_terms_accepted`, `is_privacy_accepted`, `latitude`, `longtidue`, `address`, `is_flex_gps_enabled`, `is_ghost_mode_enabled`, `is_travel_mode_enabled`, `is_winkyblinking_enabled`, `is_winky_badge_enabled`, `is_in_app_audio_chat_enabled`, `winkyblasts_count`, `is_notification_promotional_enabled`, `is_notification_message_enabled`, `is_notification_winkyblasts_enabled`, `is_notification_speed_dating_enabled`, `is_notification_virtual_dates_enabled`, `create_date`, `update_date`, `is_swipe_tutorial_shown`, `is_home_tutorial_shown`, `is_deleted`, `in_app_audio_subscribed_date`, `winkyblink_subscribed_date`, `travel_mode_subscribed_date`, `ghost_mode_subscribed_date`) VALUES
(1, '138620483', 'rXcPpezFuFUQ9NH87IqybjPjTNR2', NULL, 'Zaq12345!@#', 'Hipolito Moraine', 1, NULL, 'Male', 'eliseo_cassin@hotmail.com', '67228', '1998-12-30', 'Plus', '2023-09-13 20:58:30', NULL, 69, 'Mom/Dad Body', 'Regularly', 'Never', 'Graduate Degree', 'Apolitical', 'Dinner and a movie or show', 'African American||American Indian', 'Dumbo||Shrek', 'Pop||Rock', 'Sport||Art||Game', 'Writing||Reading||Travel', 'Consectetur similique quas. Perferendis nobis dicta eius tempore libero. Eos debitis nostrum sequi atque earum soluta nesciunt sapiente. Accusantium exercitationem laboriosam corrupti corporis and other non cupiditate quod qui iste. Esse molestiae blanditiis nulla optio eos perspiciatis sit.', NULL, 1, 1, 18.3076, -99.2211, NULL, 0, 0, 0, 0, 1, 0, 0, 1, 1, 0, 0, 0, '2023-09-13 04:29:46', '2023-10-15 17:55:49', 0, 0, 0, '0000-00-00 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00'),
(2, '138620565', 'YXsJ0ybVuIV3ofVWkQ6ktTeQSbh2', NULL, 'Zaq12345!@#', 'Bari Holod', 1, NULL, 'Female', 'orrin_block25@yahoo.com', '04428', '1999-02-25', 'Plus', '2023-09-13 20:58:30', NULL, 66, 'Mom/Dad Body', 'Regularly', 'Never', 'Graduate Degree', 'Apolitical', 'Dinner and a movie or show', 'African American||American Indian', 'Dumbo||Shrek', 'Pop||Rock', 'Sport||Art||Game', 'Writing||Reading||Travel', 'Labore alias a ducimus doloribus. Cumque ipsum modi quia quo neque. Aut eveniet porro eius veniam illo. Quae tempora recusandae quas. Modi fuga earum. Placeat porro beatae delectus quasi asperiores.', NULL, 1, 1, 60.802, 14.8809, NULL, 0, 0, 0, 0, 1, 0, 0, 1, 1, 0, 0, 0, '2023-09-13 04:29:46', '2023-09-18 14:51:32', 0, 0, 0, '0000-00-00 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00'),
(3, '138620571', 'udEFqdmqXHWAx181iTt3mVuHEll2', NULL, 'Zaq12345!@#', 'Elijah Magnuson', 1, NULL, 'Male', 'sandrine39@gmail.com', '84636', '1993-12-06', 'Plus', '2023-09-13 20:58:30', NULL, 68, 'Mom/Dad Body', 'Regularly', 'Never', 'Graduate Degree', 'Apolitical', 'Dinner and a movie or show', 'African American||American Indian', 'Dumbo||Shrek', 'Pop||Rock', 'Sport||Art||Game', 'Writing||Reading||Travel', 'Sint dolorem fugit dolorem possimus voluptas repellendus pariatur amet. Maxime quam atque laudantium quos minus eligendi voluptate excepturi. Aspernatur porro velit corporis alias quasi. Est nisi quod non enim quidem. Cum error quae quis distinctio. Ipsa quo autem adipisci.', NULL, 1, 1, 15.2466, 121.2077, NULL, 0, 0, 0, 0, 1, 0, 0, 1, 1, 0, 0, 0, '2023-09-13 04:29:46', '2023-09-18 14:51:32', 0, 0, 0, '0000-00-00 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00'),
(4, '138620574', '04LddiwQFsXkPyEAYn15AVekIa32', NULL, 'Zaq12345!@#', 'Jaquelyn Chadwell', 1, NULL, 'Female', 'sadye.adams74@gmail.com', '96033', '2001-08-20', 'Plus', '2023-09-13 20:58:30', NULL, 62, 'Mom/Dad Body', 'Regularly', 'Never', 'Graduate Degree', 'Apolitical', 'Dinner and a movie or show', 'African American||American Indian', 'Dumbo||Shrek', 'Pop||Rock', 'Sport||Art||Game', 'Writing||Reading||Travel', 'Ducimus quaerat quibusdam nulla fugit ducimus ratione. Culpa atque odio qui aliquam dolor voluptate. Quis repudiandae accusantium perspiciatis incidunt modi dolorem doloremque in tempore. Repellendus harum accusantium quisquam aspernatur quae corrupti aliquid.', NULL, 1, 1, -31.516, 22.5545, NULL, 0, 0, 0, 0, 1, 0, 0, 1, 1, 0, 0, 0, '2023-09-13 04:29:46', '2023-09-18 14:51:32', 0, 0, 0, '0000-00-00 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00'),
(5, '138620576', 'ScO3EECNyhMtxSGFxvwinVyn8ij1', NULL, 'Zaq12345!@#', 'Bari Holod', 1, NULL, 'Female', 'audreanne_mayer@gmail.com', '08558', '1978-06-10', 'Plus', '2023-09-13 20:58:30', NULL, 64, 'Mom/Dad Body', 'Regularly', 'Never', 'Graduate Degree', 'Apolitical', 'Dinner and a movie or show', 'African American||American Indian', 'Dumbo||Shrek', 'Pop||Rock', 'Sport||Art||Game', 'Writing||Reading||Travel', 'Beatae quia quis. Ducimus sequi pariatur voluptatum. Ex quia quod consequuntur quis non sit rerum veniam itaque. Deserunt tempora fugit quaerat hic beatae hic. Reprehenderit veritatis ducimus eius asperiores libero nobis ducimus soluta.', NULL, 1, 1, -29.177, 47.5768, NULL, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0, '2023-09-13 04:29:46', '2023-09-18 14:51:32', 0, 0, 0, '0000-00-00 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00'),
(6, '138620579', 'Yjx0CQcKUSXCWbPuLk0vvZ2ynad2', NULL, 'Zaq12345!@#', 'Brenton Ingleton', 1, NULL, 'Male', 'etha50@hotmail.com', '44076', '1982-07-24', 'Plus', '2023-09-13 20:58:30', NULL, 71, 'Mom/Dad Body', 'Regularly', 'Never', 'Graduate Degree', 'Apolitical', 'Dinner and a movie or show', 'African American||American Indian', 'Dumbo||Shrek', 'Pop||Rock', 'Sport||Art||Game', 'Writing||Reading||Travel', 'Cupiditate dolor distinctio quaerat repellat blanditiis dignissimos optio aliquid quibusdam. Quo iure perspiciatis fugit dolorum fugit. Nesciunt aut porro veritatis eius modi sunt repudiandae. Quis dolorum pariatur veritatis.', NULL, 1, 1, -2.0135, 23.7584, NULL, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0, '2023-09-13 04:29:46', '2023-09-18 14:51:32', 0, 0, 0, '0000-00-00 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00'),
(7, '138620581', 'cxDWlJxNZ8dxh51pjIpIG2QBJaq1', NULL, 'Zaq12345!@#', 'Jerri Yonker', 1, NULL, 'Female', 'frederic90@gmail.com', '74401', '1994-11-12', 'Plus', '2023-09-13 20:58:30', NULL, 62, 'Mom/Dad Body', 'Regularly', 'Never', 'Graduate Degree', 'Apolitical', 'Dinner and a movie or show', 'African American||American Indian', 'Dumbo||Shrek', 'Pop||Rock', 'Sport||Art||Game', 'Writing||Reading||Travel', 'Nam iure voluptatibus voluptatum in iste exercitationem omnis temporibus. Dolore suscipit quaerat quis tempora sunt magni recusandae fugit iusto. Veniam accusamus non molestias rerum nostrum culpa cumque eos. Saepe debitis tenetur. Itaque quas optio quis quaerat nihil totam quidem. Iure tenetur maiores id qui ullam repudiandae consequatur ratione quia.', NULL, 1, 1, 13.2412, 17.5182, NULL, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0, '2023-09-13 04:29:46', '2023-09-18 14:51:32', 0, 0, 0, '0000-00-00 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00'),
(8, '138620582', 'V56Z5KASrUVdC1sHqHRly66uuv42', NULL, 'Zaq12345!@#', 'Melania Vruwink', 1, NULL, 'Female', 'rnest_johns@hotmail.com', '15345', '1970-11-25', 'Plus', '2023-09-13 20:58:30', NULL, 60, 'Mom/Dad Body', 'Regularly', 'Never', 'Graduate Degree', 'Apolitical', 'Dinner and a movie or show', 'African American||American Indian', 'Dumbo||Shrek', 'Pop||Rock', 'Sport||Art||Game', 'Writing||Reading||Travel', 'Molestias sed modi unde quos dolorem quo. Alias harum laboriosam totam iusto voluptates incidunt fuga similique. Officiis eligendi excepturi assumenda. Officia deserunt reprehenderit facere explicabo rerum tempore mollitia debitis. Libero delectus nobis nulla. Doloribus veniam vel doloribus dolor sint.', NULL, 1, 1, 24.0477, 5.3014, NULL, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0, '2023-09-13 04:29:46', '2023-09-18 14:51:32', 0, 0, 0, '0000-00-00 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00'),
(9, '138620584', '36mMtqQr48ghw4bckWHGjGBUaDP2', NULL, 'Zaq12345!@#', 'Kasey Sigafus', 1, NULL, 'Male', 'jerad.dibbert@gmail.com', '02026', '1974-06-30', 'Plus', '2023-09-13 20:58:30', NULL, 72, 'Mom/Dad Body', 'Regularly', 'Never', 'Graduate Degree', 'Apolitical', 'Dinner and a movie or show', 'African American||American Indian', 'Dumbo||Shrek', 'Pop||Rock', 'Sport||Art||Game', 'Writing||Reading||Travel', 'Consequuntur quas totam. Corporis similique ipsum dignissimos deserunt hic. Laboriosam repudiandae ea necessitatibus dolor praesentium quo. Sunt quas earum expedita aliquam libero soluta animi a. Illum eveniet molestiae expedita quod voluptatibus laborum tempora esse. Impedit ducimus aliquam quis blanditiis aliquam voluptatibus.', NULL, 1, 1, 62.5942, 15.4536, NULL, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0, '2023-09-13 04:29:46', '2023-09-18 14:51:32', 0, 0, 0, '0000-00-00 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00'),
(10, '138620585', 'VfGGQKzCq6cxZyvamZBABNLbJkw1', NULL, 'Zaq12345!@#', 'Jean Dinarte', 1, NULL, 'Female', 'john_roberts8@hotmail.com', '28119', '1988-02-07', 'Plus', '2023-09-13 20:58:30', NULL, 62, 'Mom/Dad Body', 'Regularly', 'Never', 'Graduate Degree', 'Apolitical', 'Dinner and a movie or show', 'African American||American Indian', 'Dumbo||Shrek', 'Pop||Rock', 'Sport||Art||Game', 'Writing||Reading||Travel', 'Voluptas temporibus rem cum sequi dignissimos similique. Assumenda aspernatur provident reiciendis vel sunt vel at. Doloribus excepturi nihil harum ea.', NULL, 1, 1, 64.775, -18.0328, NULL, 0, 0, 0, 0, 1, 0, 0, 1, 1, 0, 0, 0, '2023-09-13 04:29:46', '2023-09-18 14:51:32', 0, 0, 0, '0000-00-00 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00'),
(11, '138620586', '5JERKpCxRVX5jFv0S5oU7CVj7kb2', NULL, 'Zaq12345!@#', 'Stefan Turino', 1, NULL, 'Male', 'edyth_sporer78@yahoo.com', '07730', '1980-09-01', 'Plus', '2023-09-13 20:58:30', NULL, 66, 'Mom/Dad Body', 'Regularly', 'Never', 'Graduate Degree', 'Apolitical', 'Dinner and a movie or show', 'African American||American Indian', 'Dumbo||Shrek', 'Pop||Rock', 'Sport||Art||Game', 'Writing||Reading||Travel', 'Accusantium consequuntur porro. Voluptates illum quos. Veniam vero beatae mollitia deserunt. Repudiandae laudantium consequatur iure libero magni voluptates.', NULL, 1, 1, 77.9443, -30.8195, NULL, 0, 0, 0, 0, 1, 0, 0, 1, 1, 0, 0, 0, '2023-09-13 04:29:46', '2023-09-18 14:51:32', 0, 0, 0, '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00'),
(12, '138620588', 'l73T6qVzHoWL32rZDrQJntIuz2v1', NULL, 'Zaq12345!@#', 'Amy Piacitelli', 1, NULL, 'Female', 'camille.klein@hotmail.com', '12581', '1974-07-11', 'Plus', '2023-09-13 20:58:30', NULL, 65, 'Mom/Dad Body', 'Regularly', 'Never', 'Graduate Degree', 'Apolitical', 'Dinner and a movie or show', 'African American||American Indian', 'Dumbo||Shrek', 'Pop||Rock', 'Sport||Art||Game', 'Writing||Reading||Travel', 'Quaerat iure possimus iure consectetur accusamus. Sapiente et labore molestiae natus voluptas. Odit commodi maxime quaerat veritatis doloribus voluptatem placeat molestias. Quo ex architecto magni quae aperiam qui eligendi ab quia.', NULL, 1, 1, 14.0857, 99.8508, NULL, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0, '2023-09-13 04:29:46', '2023-09-18 14:51:32', 0, 0, 0, '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00'),
(13, '138620589', 'LYTzO7PRHGRsWEa6EFRVXEisl9i2', NULL, 'Zaq12345!@#', 'Everette Rieffer', 1, NULL, 'Male', 'jenifer70@gmail.com', '20886', '1984-05-19', 'Plus', '2023-09-13 20:58:30', NULL, 70, 'Mom/Dad Body', 'Regularly', 'Never', 'Graduate Degree', 'Apolitical', 'Dinner and a movie or show', 'African American||American Indian', 'Dumbo||Shrek', 'Pop||Rock', 'Sport||Art||Game', 'Writing||Reading||Travel', 'Beatae doloremque in exercitationem sint. Molestiae itaque corporis voluptas officia natus. At debitis optio in at doloribus sequi. Ducimus sunt ratione totam vel perferendis.', NULL, 1, 1, -27.0226, 142.0381, NULL, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0, '2023-09-13 04:29:46', '2023-09-18 14:51:32', 0, 0, 0, '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00'),
(14, '138620590', 'GpkavuQlstU3UETvHjvPS69ktfH3', NULL, 'Zaq12345!@#', 'Erasmo Pethan', 1, NULL, 'Male', 'leila_kuvalis92@gmail.com', '58831', '1989-01-23', 'Plus', '2023-09-13 20:58:30', NULL, 73, 'Mom/Dad Body', 'Regularly', 'Never', 'Graduate Degree', 'Apolitical', 'Dinner and a movie or show', 'African American||American Indian', 'Dumbo||Shrek', 'Pop||Rock', 'Sport||Art||Game', 'Writing||Reading||Travel', 'Molestiae ratione id accusamus nobis occaecati ad voluptatem quibusdam. Ut quaerat est molestiae tempore distinctio beatae. A ullam molestias quae aliquid corporis expedita ex. Ex mollitia officiis labore numquam nihil perspiciatis dolorum consectetur. Id id sed et delectus molestiae hic voluptates cumque architecto. Facere dolorem veritatis culpa pariatur.', NULL, 1, 1, 19.3499, 80.5147, NULL, 0, 0, 0, 0, 1, 0, 0, 1, 1, 0, 0, 0, '2023-09-13 04:29:46', '2023-09-18 14:51:32', 0, 0, 0, '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00'),
(15, '138620592', 'nma3mowKlFN4nkrUVQ2B2xsWzc32', NULL, 'Zaq12345!@#', 'Ray Teshome', 1, NULL, 'Male', 'joe.franecki12@hotmail.com', '28777', '1972-01-21', 'Plus', '2023-09-13 20:58:30', NULL, 23, 'Mom/Dad Body', 'Regularly', 'Never', 'Graduate Degree', 'Apolitical', 'Dinner and a movie or show', 'African American||American Indian', 'Dumbo||Shrek', 'Pop||Rock', 'Sport||Art||Game', 'Writing||Reading||Travel', 'Quae amet inventore voluptatum animi eos explicabo corrupti. Reiciendis mollitia nesciunt voluptas quas. Voluptas sit ex id. Molestias sapiente inventore natus. Minima occaecati natus necessitatibus ullam recusandae nobis quo modi. Tempora distinctio nihil quam quaerat ratione numquam dolor.', NULL, 1, 1, 64.8713, 164.2702, NULL, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0, '2023-09-13 04:29:46', '2023-09-18 14:51:32', 0, 0, 0, '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00'),
(16, '138620593', '9IdSkDTztSY9rhP9if9aYAC0Yni1', NULL, 'Zaq12345!@#', 'Siobhan Calliham', 1, NULL, 'Female', 'eddie_rowe46@gmail.com', '16878', '1986-03-22', 'Plus', '2023-09-13 20:58:30', NULL, 61, 'Mom/Dad Body', 'Regularly', 'Never', 'Graduate Degree', 'Apolitical', 'Dinner and a movie or show', 'African American||American Indian', 'Dumbo||Shrek', 'Pop||Rock', 'Sport||Art||Game', 'Writing||Reading||Travel', 'Labore quisquam delectus quae aut sequi officia. Quidem praesentium qui deleniti rem quam quasi impedit iure dolorum. Atque laudantium optio soluta quisquam. Debitis vero deleniti assumenda.', NULL, 1, 1, 53.8832, -97.4231, NULL, 0, 0, 0, 0, 1, 0, 0, 1, 1, 0, 0, 0, '2023-09-13 04:29:46', '2023-09-18 14:51:32', 0, 0, 0, '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00'),
(17, '138620594', '8gdOX8EiawfWDovn9RHOzapzans2', NULL, 'Zaq12345!@#', 'Kasey Mattis', 1, NULL, 'Female', 'domenica14@yahoo.com', '77055', '2005-06-10', 'Plus', '2023-09-13 20:58:30', NULL, 61, 'Mom/Dad Body', 'Regularly', 'Never', 'Graduate Degree', 'Apolitical', 'Dinner and a movie or show', 'African American||American Indian', 'Dumbo||Shrek', 'Pop||Rock', 'Sport||Art||Game', 'Writing||Reading||Travel', 'Beatae id neque soluta deleniti quaerat. Ipsam veniam ad ab repudiandae culpa fugiat expedita in. Nemo consequatur corrupti provident animi delectus. Laudantium itaque quidem corporis laboriosam atque cupiditate.', NULL, 1, 1, -3.268, -56.8962, NULL, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0, '2023-09-13 04:29:46', '2023-09-18 14:51:32', 0, 0, 0, '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00'),
(18, '138620596', 'y2ruqjReobPhi8RZnGpr64eporF2', NULL, 'Zaq12345!@#', 'Marg Kos', 1, NULL, 'Female', 'ismael.runolfsson87@hotmail.com', '14170', '2001-10-23', 'Plus', '2023-09-13 20:58:30', NULL, 64, 'Mom/Dad Body', 'Regularly', 'Never', 'Graduate Degree', 'Apolitical', 'Dinner and a movie or show', 'African American||American Indian', 'Dumbo||Shrek', 'Pop||Rock', 'Sport||Art||Game', 'Writing||Reading||Travel', 'Dolorum corrupti sapiente nam quibusdam iste repellat. Quia accusantium dolores aut sint consequuntur minima magni porro. Nulla natus voluptatum voluptatem quae dolorem eaque. Est expedita corporis at odio. Facere laborum a autem atque nostrum optio consequuntur corrupti.', NULL, 1, 1, -32.433, -57.8632, NULL, 0, 0, 0, 0, 1, 0, 0, 1, 1, 0, 0, 0, '2023-09-13 04:29:46', '2023-09-18 14:51:32', 0, 0, 0, '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00'),
(19, '138620597', 'HKN51i9MEBV4S3cvnQ4CRk5Qyta2', NULL, 'Zaq12345!@#', 'Wes Spieker', 1, NULL, 'Male', 'marvin_daugherty32@gmail.com', '55049', '1999-01-15', 'Plus', '2023-09-13 20:58:30', NULL, 74, 'Mom/Dad Body', 'Regularly', 'Never', 'Graduate Degree', 'Apolitical', 'Dinner and a movie or show', 'African American||American Indian', 'Dumbo||Shrek', 'Pop||Rock', 'Sport||Art||Game', 'Writing||Reading||Travel', 'Magni excepturi sapiente voluptatem sint excepturi voluptates. Dignissimos quidem placeat voluptates illum. Magnam rem officia saepe veritatis error eum iure.', NULL, 1, 1, 8.6248, -65.2572, NULL, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0, '2023-09-13 04:29:46', '2023-09-18 14:51:32', 0, 0, 0, '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00'),
(20, '138620599', 'GU2v8zRksEZSI1te19gf17rJ9P93', NULL, 'Zaq12345!@#', 'Maxwell Afton', 1, NULL, 'Male', 'haylee33@hotmail.com', '59243', '1984-03-26', 'Plus', '2023-09-13 20:58:30', NULL, 70, 'Mom/Dad Body', 'Regularly', 'Never', 'Graduate Degree', 'Apolitical', 'Dinner and a movie or show', 'African American||American Indian', 'Dumbo||Shrek', 'Pop||Rock', 'Sport||Art||Game', 'Writing||Reading||Travel', 'Id voluptates porro occaecati laboriosam perferendis voluptate omnis perspiciatis. Illum natus qui quam debitis facere voluptas asperiores eos. Nesciunt quibusdam ratione voluptas eveniet libero expedita corrupti voluptas delectus. Nostrum laudantium quidem magni omnis quam. Repellat nemo magni sit adipisci veritatis ea distinctio quae. Voluptates dicta inventore.', NULL, 1, 1, -9.2319, -74.4858, NULL, 0, 0, 0, 0, 1, 0, 0, 1, 1, 0, 0, 0, '2023-09-13 04:29:46', '2023-09-18 14:51:32', 0, 0, 0, '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00'),
(23, '138620739', 'Of9onUIrBkamHtIVkCTbMJzMT062', NULL, 'Zaq12345!@#', 'Aisling Whitaker', 1, NULL, 'Female', 'aisling@gmail.com', '98092', '1987-01-01', 'Plus', '2023-09-20 02:27:30', NULL, 61, 'Athletic/Fit', 'Ocassionally', 'Regularly', 'Trade/Tech School', 'Moderate', 'Cocktails and Dinner', 'American Indian||Black||Caucasian', 'Rock', 'Gomez', 'Travel', 'Billionaire', 'The only way to get the money back is if we have to go back and', NULL, 1, 1, -34.3264, -55.0754, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2023-09-19 18:26:36', '2023-09-19 19:02:00', 0, 0, 0, '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00'),
(25, '138626379', 'bnM3HFeddic62mOtMCpZ35gnI5A2', NULL, 'Zaq12345!@#', 'Kathleen French', 1, NULL, 'Female', 'kathleen@dyrep.com', '98005', '1989-08-26', 'Plus', '2023-09-21 02:04:42', NULL, 66, 'Average Build', 'Ocassionally', 'Ocassionally', 'Trade/Tech School', 'Moderate', 'Cocktails and Dinner', 'African American||Asian', 'Rock', 'Gomez', 'Travel', 'Sport', 'The only thing that makes sense is that you can use any kind a computer for the computer', '1695235202616.mp4', 1, 1, 58.4465, -73.3305, NULL, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, '2023-09-20 18:03:44', '2023-09-20 18:40:11', 0, 0, 0, '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00'),
(26, '138737602', 'rj1qiyH8puQd4XAeXijUQzPSNbo1', NULL, 'Zaq12345!@#', 'Wang Ping', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 65.7297, -89.9419, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2023-10-04 15:41:54', '2023-10-04 15:41:54', 0, 0, 0, '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00'),
(32, '138738054', 'SsRbkHtG2qYTgRCSaJj8KTT0iGx1', NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 24.2386, 41.8607, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2023-10-04 16:56:04', '2023-10-04 16:56:07', 0, 0, 0, '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00'),
(33, '138738204', 'mqAHcngQ5PavuBd0gbNzFGKWYhp2', NULL, NULL, 'Wang Louin', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 62.7276, 28.7241, NULL, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, '2023-10-04 17:29:31', '2023-10-04 17:29:37', 0, 0, 0, '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00'),
(35, '138738205', 'HcFT8MJh5BXF0wFguGqeP2XoHpx1', NULL, '123456Sjs!', 'Test user11', 1, NULL, 'Male', 'tiger94815@gmail.com', '+1', '2001-08-15', 'Plus', '2024-01-11 11:08:13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 63.8871899004, -150.632350482, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2024-01-11 01:46:14', '2024-01-11 02:08:14', 0, 0, 0, '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00'),
(36, '139352308', 'bZSLQjZbGacBEdNz3S6wVyh1kFm1', NULL, '1234567Sjs!', 'Roni', 1, NULL, 'Male', 'Roni@gmail.com', '+1', '2001-08-01', 'Plus', '2024-01-11 11:08:13', NULL, 72, 'Athletic/Fit', 'Ocassionally', 'Never', 'Graduate Degree', 'Liberal', 'Goint to a concert or sporting event', 'Asian', 'Roni\'s movie', 'Roni\'s Artists', 'Roni\'s Interest', 'Roni\'s Hobby', 'Roni\'s Fun facts', NULL, 1, 1, 64.9799954821, -150.632350482, NULL, 0, 1, 1, 0, 1, 0, 1, 1, 1, 1, 1, 1, '2024-01-11 02:43:27', '2024-01-15 04:11:12', 0, 0, 0, '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00'),
(42, '139353254', 'qlYKqeFRekZEBAiyy3n9PBRlKRZ2', NULL, '123456Sjs!', 'test_user19(Plus)', 1, NULL, 'Male', 'test_user18', '+1', '2000-02-01', 'Plus', '2024-02-05 10:46:28', NULL, 65, 'Mom/Dad Body', 'Never', 'Never', 'High School', 'Moderate', 'Dinner and a movie or show', 'Asian', 'test movie', 'test artist', 'test interest', 'test hobby', 'This is test user18', NULL, 1, 1, 64.9799954821, -150.632350482, NULL, 0, 0, 0, 0, 0, 0, 10, 0, 0, 0, 0, 0, '2024-01-15 18:58:53', '2024-02-05 14:35:00', 0, 0, 0, '2024-01-01 23:02:06', '2024-02-05 23:26:17', '2024-02-05 23:24:54', '2024-02-05 23:22:29'),
(43, '139353310', 'XEmg4rbJcIVu658huCgj6uNeZOr2', NULL, '123456Sjs!', 'test_user20(Premium)', 1, NULL, 'Male', 'test_user20@gmail.com', '+34', '2005-01-01', 'Plus', '2024-01-16 04:19:07', NULL, 67, 'Overweight', 'Never', 'Never', 'High School', 'Moderate', 'Goint to a concert or sporting event', 'Asian', 'test_hoby', 'test_hoby', 'test_hoby', 'test_hobby', 'This is me..', NULL, 1, 1, 37.421998333333335, -122.084, 'Mountain View,California', 0, 0, 0, 0, 1, 0, 16, 0, 0, 0, 0, 0, '2024-01-15 19:19:20', '2024-02-12 18:19:08', 0, 0, 0, '1000-01-01 00:00:00', '2024-02-12 23:01:37', '1000-01-01 00:00:00', '1000-01-01 00:00:00'),
(44, '139355153', 'dt6TzlkhLFSh4biW8EWr02fR3wJ3', NULL, '123456Sjs!', 'User 1', 1, NULL, 'Male', 'user1@mail.com', '+32', '2005-01-01', 'Basic', '2024-01-16 17:12:17', NULL, 71, 'Athletic/Fit', 'Never', 'Never', 'High School', 'Moderate', 'Cocktails and Dinner', 'Hawaiian', 'User 1', 'User 1', 'User 1', 'User1', 'this is user 1', NULL, 1, 1, 64.9799954821, -150.632350482, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2024-01-16 08:12:50', '2024-01-16 08:16:46', 0, 0, 0, '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00'),
(45, '139355173', '4Q9YsDSjxpQtDTY5C3YQhYjZxtE3', NULL, '123456Sjs!', 'User2', 1, NULL, 'Male', 'user2@mail.com', '+44', '2005-01-01', 'Premium', '2024-01-16 17:19:37', NULL, 53, 'Curvy/Husky', 'Never', 'Never', 'High School', 'Moderate', 'Cocktails and Dinner', 'Hawaiian', 'user2', 'user2', 'user2', 'user2', 'This is user2', NULL, 1, 1, 64.9799954821, -150.632350482, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2024-01-16 08:20:07', '2024-01-16 08:22:18', 0, 0, 0, '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00'),
(46, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, 0, 0, '0000-00-00 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00'),
(47, '139451485', 'EZ2s2x3qDYVxuR1sXPBy95SfUsf1', NULL, '123456Sjs!', 'Test_user21(Basic)', 1, NULL, 'Male', 'testuserbasic@gmail.com', '+231', '2005-01-01', 'Basic', '2024-02-05 22:14:26', NULL, 64, 'Slim/Skinny', 'Never', 'Socially', 'Undergraduate Degree', 'Liberal', 'Netflix and Chill', 'Caucasian||Latina/Hispanic', 'Basic', 'Basic', 'Basic', 'Basic', 'This is Basic user', NULL, 1, 1, 37.421998333333335, -122.084, NULL, 0, 0, 0, 0, 0, 0, 10, 0, 0, 0, 0, 0, '2024-02-05 10:00:31', '2024-02-05 11:24:35', 0, 0, 0, '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users_answers`
--

CREATE TABLE `users_answers` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `question_id` int(11) DEFAULT NULL,
  `answer` varchar(64) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users_answers`
--

INSERT INTO `users_answers` (`id`, `user_id`, `question_id`, `answer`, `create_date`, `update_date`) VALUES
(27, 36, 0, 'Someone who can make me laugh', '2024-01-12 14:42:46', '2024-01-13 16:25:23'),
(28, 36, 1, 'Somewhat Agree', '2024-01-12 14:43:02', '2024-01-13 16:25:30'),
(29, 36, 2, 'Strongly Disagree', '2024-01-12 14:43:07', '2024-01-13 16:25:30'),
(30, 36, 3, 'Strongly Agree', '2024-01-12 14:43:23', '2024-01-13 16:25:30'),
(31, 36, 4, 'Use a tent and sleeping bag', '2024-01-12 14:43:26', '2024-01-13 16:25:30'),
(32, 36, 5, 'Strongly Disagree', '2024-01-12 14:43:35', '2024-01-13 16:25:30'),
(33, 36, 6, 'Strongly Disagree', '2024-01-12 14:43:38', '2024-01-13 16:25:31'),
(34, 36, 7, 'Strongly Disagree', '2024-01-12 14:43:42', '2024-01-13 16:25:31'),
(35, 36, 8, 'Unattractive', '2024-01-12 14:43:45', '2024-01-13 16:25:31'),
(36, 36, 9, 'Strongly Disagree', '2024-01-12 14:43:59', '2024-01-13 16:25:31'),
(37, 36, 10, 'Strongly Disagree', '2024-01-12 14:44:02', '2024-01-13 16:25:31'),
(38, 36, 11, 'Strongly Disagree', '2024-01-12 14:44:04', '2024-01-13 16:25:32'),
(39, 36, 12, 'Loyal, kind, trustworthy', '2024-01-12 14:44:06', '2024-01-13 16:25:32'),
(40, 36, 13, 'Strongly Disagree', '2024-01-12 14:44:08', '2024-01-13 16:25:32'),
(41, 36, 14, 'Strongly Disagree', '2024-01-12 14:44:09', '2024-01-13 16:25:33'),
(42, 36, 15, 'Strongly Disagree', '2024-01-12 14:44:11', '2024-01-13 16:25:33'),
(43, 36, 16, 'Gross', '2024-01-12 14:44:13', '2024-01-13 16:25:34'),
(44, 36, 17, 'Strongly Disagree', '2024-01-12 14:44:15', '2024-01-13 16:25:35'),
(45, 36, 18, 'Strongly Disagree', '2024-01-12 14:44:25', '2024-01-13 17:03:37'),
(46, 36, 19, 'Strongly Disagree', '2024-01-12 14:44:34', '2024-01-13 17:08:21');

-- --------------------------------------------------------

--
-- Table structure for table `users_photos`
--

CREATE TABLE `users_photos` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `photo` varchar(64) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users_photos`
--

INSERT INTO `users_photos` (`id`, `user_id`, `photo`, `create_date`, `update_date`) VALUES
(1, 1, '1694978591473.jpg', '2023-09-17 19:23:13', '2023-09-17 19:23:13'),
(2, 1, '1694978591474.jpg', '2023-09-17 19:23:14', '2023-09-17 19:23:14'),
(3, 2, 'f1.jpg', '2023-09-17 19:23:14', '2023-09-17 19:23:14'),
(4, 3, 'm2.jpg', '2023-09-17 19:23:14', '2023-09-17 19:23:14'),
(5, 4, 'f2.jpg', '2023-09-17 19:23:14', '2023-09-17 19:23:14'),
(6, 5, 'f3.jpg', '2023-09-17 19:23:14', '2023-09-17 19:23:14'),
(7, 6, 'm3.jpg', '2023-09-17 19:23:14', '2023-09-17 19:23:14'),
(8, 7, 'f4.jpg', '2023-09-17 19:23:14', '2023-09-17 19:23:14'),
(9, 8, 'f5.jpg', '2023-09-17 19:23:14', '2023-09-17 19:23:14'),
(10, 9, 'm4.jpg', '2023-09-17 19:23:14', '2023-09-17 19:23:14'),
(11, 10, 'f10.jpg', '2023-09-17 19:23:14', '2023-09-17 19:23:14'),
(12, 11, 'm5.jpg', '2023-09-17 19:23:14', '2023-09-17 19:23:14'),
(13, 12, 'f6.jpg', '2023-09-17 19:23:14', '2023-09-17 19:23:14'),
(14, 13, 'm6.jpg', '2023-09-17 19:23:14', '2023-09-17 19:23:14'),
(15, 14, 'm7.jpg', '2023-09-17 19:23:14', '2023-09-17 19:23:14'),
(16, 15, 'm8.jpg', '2023-09-17 19:23:14', '2023-09-17 19:23:14'),
(17, 16, 'f7.jpg', '2023-09-17 19:23:14', '2023-09-17 19:23:14'),
(18, 17, 'f8.jpg', '2023-09-17 19:23:14', '2023-09-17 19:23:14'),
(19, 18, 'f9.jpg', '2023-09-17 19:23:14', '2023-09-17 19:23:14'),
(20, 19, 'm9.jpg', '2023-09-17 19:23:14', '2023-09-17 19:23:14'),
(21, 20, 'm10.jpg', '2023-09-17 19:23:14', '2023-09-17 19:23:14'),
(22, 23, '1695149892531.jpg', '2023-09-19 18:58:14', '2023-09-19 18:58:14'),
(23, 23, '1695149892532.jpg', '2023-09-19 18:58:14', '2023-09-19 18:58:14'),
(24, 25, '1695233300440.jpg', '2023-09-20 18:08:22', '2023-09-20 18:08:22'),
(25, 36, '1695233300441.jpg', '2023-09-20 18:08:22', '2023-09-20 18:08:22'),
(26, 42, '1695233300440.jpg', '2023-09-20 18:08:22', '2023-09-20 18:08:22'),
(28, 44, 'f4.jpg', '2023-09-20 18:08:22', '2023-09-20 18:08:22'),
(29, 45, 'm8.jpg', '2023-09-20 18:08:22', '2023-09-20 18:08:22'),
(30, 43, '1695233300441.jpg', '2024-01-26 19:26:24', '2024-01-26 19:26:24'),
(31, 43, '1706297213178.jpg', '2024-01-26 19:26:24', '2024-01-26 19:26:24'),
(32, 47, '1707129475489.jpg', '2024-02-05 11:11:47', '2024-02-05 11:11:47'),
(33, 47, '1707129475490.jpg', '2024-02-05 11:11:47', '2024-02-05 11:11:47'),
(34, 47, '1707129475491.jpg', '2024-02-05 11:11:47', '2024-02-05 11:11:47');

-- --------------------------------------------------------

--
-- Table structure for table `users_preferences`
--

CREATE TABLE `users_preferences` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `looking_for` enum('Male','Female','Both') DEFAULT NULL,
  `age_min` int(11) DEFAULT NULL,
  `age_max` int(11) DEFAULT NULL,
  `distance_min` int(11) DEFAULT NULL,
  `distance_max` int(11) DEFAULT NULL,
  `height_min` int(11) DEFAULT NULL,
  `height_max` int(11) DEFAULT NULL,
  `hoping_to_find` text DEFAULT NULL,
  `idea_of_fun` text DEFAULT NULL,
  `body_types` text DEFAULT NULL,
  `smoke_types` text DEFAULT NULL,
  `drink_types` text DEFAULT NULL,
  `education_levels` text DEFAULT NULL,
  `political_preferences` text DEFAULT NULL,
  `cultural_background` text DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users_preferences`
--

INSERT INTO `users_preferences` (`id`, `user_id`, `looking_for`, `age_min`, `age_max`, `distance_min`, `distance_max`, `height_min`, `height_max`, `hoping_to_find`, `idea_of_fun`, `body_types`, `smoke_types`, `drink_types`, `education_levels`, `political_preferences`, `cultural_background`, `create_date`, `update_date`) VALUES
(2, 1, 'Both', 18, 70, 0, 100, 25, 90, 'A partner in crime/companion||A long-term relationship', 'Dinner and a movie or show', 'Slim/Skinny||Average Build', 'Drinks occasionally||Smokes occasionally', 'Drinks occasionally', 'High School||Trade/Tech School', 'High School||Trade/Tech School', 'Afircan American||American Indian', '2023-09-14 18:05:50', '2023-09-15 16:24:58'),
(3, 2, 'Both', 18, 70, 0, 100, 25, 90, 'A partner in crime/companion||A long-term relationship', 'Dinner and a movie or show', 'Slim/Skinny||Average Build', 'Drinks occasionally||Smokes occasionally', 'Drinks occasionally', 'High School||Trade/Tech School', 'High School||Trade/Tech School', 'Afircan American||American Indian', '2023-09-14 18:05:50', '2023-09-15 16:24:58'),
(4, 3, 'Both', 18, 70, 0, 100, 25, 90, 'A partner in crime/companion||A long-term relationship', 'Dinner and a movie or show', 'Slim/Skinny||Average Build', 'Drinks occasionally||Smokes occasionally', 'Drinks occasionally', 'High School||Trade/Tech School', 'High School||Trade/Tech School', 'Afircan American||American Indian', '2023-09-14 18:05:50', '2023-09-15 16:24:58'),
(5, 4, 'Both', 18, 70, 0, 100, 25, 90, 'A partner in crime/companion||A long-term relationship', 'Dinner and a movie or show', 'Slim/Skinny||Average Build', 'Drinks occasionally||Smokes occasionally', 'Drinks occasionally', 'High School||Trade/Tech School', 'High School||Trade/Tech School', 'Afircan American||American Indian', '2023-09-14 18:05:50', '2023-09-15 16:24:58'),
(6, 5, 'Both', 18, 70, 0, 100, 25, 90, 'A partner in crime/companion||A long-term relationship', 'Dinner and a movie or show', 'Slim/Skinny||Average Build', 'Drinks occasionally||Smokes occasionally', 'Drinks occasionally', 'High School||Trade/Tech School', 'High School||Trade/Tech School', 'Afircan American||American Indian', '2023-09-14 18:05:50', '2023-09-15 16:24:58'),
(7, 6, 'Both', 18, 70, 0, 100, 25, 90, 'A partner in crime/companion||A long-term relationship', 'Dinner and a movie or show', 'Slim/Skinny||Average Build', 'Drinks occasionally||Smokes occasionally', 'Drinks occasionally', 'High School||Trade/Tech School', 'High School||Trade/Tech School', 'Afircan American||American Indian', '2023-09-14 18:05:50', '2023-09-15 16:24:58'),
(8, 7, 'Both', 18, 70, 0, 100, 25, 90, 'A partner in crime/companion||A long-term relationship', 'Dinner and a movie or show', 'Slim/Skinny||Average Build', 'Drinks occasionally||Smokes occasionally', 'Drinks occasionally', 'High School||Trade/Tech School', 'High School||Trade/Tech School', 'Afircan American||American Indian', '2023-09-14 18:05:50', '2023-09-15 16:24:58'),
(9, 8, 'Both', 18, 70, 0, 100, 25, 90, 'A partner in crime/companion||A long-term relationship', 'Dinner and a movie or show', 'Slim/Skinny||Average Build', 'Drinks occasionally||Smokes occasionally', 'Drinks occasionally', 'High School||Trade/Tech School', 'High School||Trade/Tech School', 'Afircan American||American Indian', '2023-09-14 18:05:50', '2023-09-15 16:24:58'),
(10, 9, 'Both', 18, 70, 0, 100, 25, 90, 'A partner in crime/companion||A long-term relationship', 'Dinner and a movie or show', 'Slim/Skinny||Average Build', 'Drinks occasionally||Smokes occasionally', 'Drinks occasionally', 'High School||Trade/Tech School', 'High School||Trade/Tech School', 'Afircan American||American Indian', '2023-09-14 18:05:50', '2023-09-15 16:24:58'),
(11, 10, 'Both', 18, 70, 0, 100, 25, 90, 'A partner in crime/companion||A long-term relationship', 'Dinner and a movie or show', 'Slim/Skinny||Average Build', 'Drinks occasionally||Smokes occasionally', 'Drinks occasionally', 'High School||Trade/Tech School', 'High School||Trade/Tech School', 'Afircan American||American Indian', '2023-09-14 18:05:50', '2023-09-15 16:24:58'),
(12, 11, 'Both', 18, 70, 0, 100, 25, 90, 'A partner in crime/companion||A long-term relationship', 'Dinner and a movie or show', 'Slim/Skinny||Average Build', 'Drinks occasionally||Smokes occasionally', 'Drinks occasionally', 'High School||Trade/Tech School', 'High School||Trade/Tech School', 'Afircan American||American Indian', '2023-09-14 18:05:50', '2023-09-15 16:24:58'),
(13, 12, 'Both', 18, 70, 0, 100, 25, 90, 'A partner in crime/companion||A long-term relationship', 'Dinner and a movie or show', 'Slim/Skinny||Average Build', 'Drinks occasionally||Smokes occasionally', 'Drinks occasionally', 'High School||Trade/Tech School', 'High School||Trade/Tech School', 'Afircan American||American Indian', '2023-09-14 18:05:50', '2023-09-15 16:24:58'),
(14, 13, 'Both', 18, 70, 0, 100, 25, 90, 'A partner in crime/companion||A long-term relationship', 'Dinner and a movie or show', 'Slim/Skinny||Average Build', 'Drinks occasionally||Smokes occasionally', 'Drinks occasionally', 'High School||Trade/Tech School', 'High School||Trade/Tech School', 'Afircan American||American Indian', '2023-09-14 18:05:50', '2023-09-15 16:24:58'),
(15, 14, 'Both', 18, 70, 0, 100, 25, 90, 'A partner in crime/companion||A long-term relationship', 'Dinner and a movie or show', 'Slim/Skinny||Average Build', 'Drinks occasionally||Smokes occasionally', 'Drinks occasionally', 'High School||Trade/Tech School', 'High School||Trade/Tech School', 'Afircan American||American Indian', '2023-09-14 18:05:50', '2023-09-15 16:24:58'),
(16, 15, 'Both', 18, 70, 0, 100, 25, 90, 'A partner in crime/companion||A long-term relationship', 'Dinner and a movie or show', 'Slim/Skinny||Average Build', 'Drinks occasionally||Smokes occasionally', 'Drinks occasionally', 'High School||Trade/Tech School', 'High School||Trade/Tech School', 'Afircan American||American Indian', '2023-09-14 18:05:50', '2023-09-15 16:24:58'),
(17, 16, 'Both', 18, 70, 0, 100, 25, 90, 'A partner in crime/companion||A long-term relationship', 'Dinner and a movie or show', 'Slim/Skinny||Average Build', 'Drinks occasionally||Smokes occasionally', 'Drinks occasionally', 'High School||Trade/Tech School', 'High School||Trade/Tech School', 'Afircan American||American Indian', '2023-09-14 18:05:50', '2023-09-15 16:24:58'),
(18, 17, 'Both', 18, 70, 0, 100, 25, 90, 'A partner in crime/companion||A long-term relationship', 'Dinner and a movie or show', 'Slim/Skinny||Average Build', 'Drinks occasionally||Smokes occasionally', 'Drinks occasionally', 'High School||Trade/Tech School', 'High School||Trade/Tech School', 'Afircan American||American Indian', '2023-09-14 18:05:50', '2023-09-15 16:24:58'),
(19, 18, 'Both', 18, 70, 0, 100, 25, 90, 'A partner in crime/companion||A long-term relationship', 'Dinner and a movie or show', 'Slim/Skinny||Average Build', 'Drinks occasionally||Smokes occasionally', 'Drinks occasionally', 'High School||Trade/Tech School', 'High School||Trade/Tech School', 'Afircan American||American Indian', '2023-09-14 18:05:50', '2023-09-15 16:24:58'),
(20, 19, 'Both', 18, 70, 0, 100, 25, 90, 'A partner in crime/companion||A long-term relationship', 'Dinner and a movie or show', 'Slim/Skinny||Average Build', 'Drinks occasionally||Smokes occasionally', 'Drinks occasionally', 'High School||Trade/Tech School', 'High School||Trade/Tech School', 'Afircan American||American Indian', '2023-09-14 18:05:50', '2023-09-15 16:24:58'),
(21, 20, 'Both', 18, 70, 0, 100, 25, 90, 'A partner in crime/companion||A long-term relationship', 'Dinner and a movie or show', 'Slim/Skinny||Average Build', 'Drinks occasionally||Smokes occasionally', 'Drinks occasionally', 'High School||Trade/Tech School', 'High School||Trade/Tech School', 'Afircan American||American Indian', '2023-09-14 18:05:50', '2023-09-15 16:24:58'),
(22, 23, 'Both', 26, 58, 25, 88, 65, 90, 'A partner in crime/companion||A long-term relationship', 'Dinner and a movie or show||Cocktails and Dinner', 'Slim/Skinny||Athletic/Fit', 'Smokes occasionally', 'Drinks occasionally', 'High School||Undergraduate Degree', 'Moderate||Conservative', 'African American||American Indian', '2023-09-19 19:01:55', '2023-09-19 19:01:55'),
(23, 25, 'Both', 23, 48, 10, 90, 58, 90, 'A partner in crime/companion||A long-term relationship', 'Dinner and a movie or show||Cocktails and Dinner', 'Athletic/Fit', 'Smokes occasionally', 'Drinks occasionally', 'High School||Trade/Tech School', 'Apolitical||Moderate', 'Asian||African American', '2023-09-20 18:09:04', '2023-09-20 18:09:04'),
(24, 36, 'Male', 18, 70, 1, 70, 10, 70, 'A partner in crime/companion||A husband/wife', 'Dinner and a movie or show||Cocktails and Dinner', 'Slim/Skinny||Average Build', 'Doesn\'t smoke||Smokes occasionally', 'Drinks occasionally||Doesn\'t drink', 'High School', 'Apolitical', 'Asian', '2024-01-11 06:43:14', '2024-01-14 06:14:37'),
(25, 39, 'Male', 18, 50, 0, 69, 10, 60, 'A partner in crime/companion', 'Dinner and a movie or show', 'Slim/Skinny', 'Doesn\'t smoke||I don\'t have a preference', 'Doesn\'t drink', 'High School', 'Apolitical', 'Asian', '2024-01-15 14:56:30', '2024-01-15 14:56:30'),
(26, 42, 'Both', 18, 70, 0, 100, 19, 50, 'A partner in crime/companion||A long-term relationship', 'Goint to a concert or sporting event||Cocktails and Dinner', 'Overweight||Athletic/Fit', 'Smokes socially||Smokes occasionally||I don\'t have a preference||Doesn\'t smoke', 'Drinks socially||I don\'t have a preference||Drinks occasionally||Doesn\'t drink', 'High School', 'Moderate', 'Asian', '2024-01-15 19:02:52', '2024-01-24 18:23:54'),
(27, 43, 'Male', 18, 70, 0, 100, 10, 34, 'A husband/wife', 'Cocktails and Dinner', 'Curvy/Husky||Mom/Dad Bod', 'I don\'t have a preference', 'Doesn\'t drink', 'Undergraduate Degree', 'Apolitical', 'Caribeean/Haitian', '2024-01-15 19:22:07', '2024-02-11 11:31:05'),
(28, 44, 'Both', 18, 70, 0, 100, 10, 69, 'A long-term relationship', 'Cocktails and Dinner', 'Mom/Dad Bod', 'Smokes socially', 'Drinks occasionally', 'High School', 'Apolitical', 'Caribeean/Haitian', '2024-01-16 08:16:40', '2024-01-16 08:16:40'),
(29, 45, 'Both', 18, 70, 0, 100, 20, 48, 'A long-term relationship||I\'m just checking things out', 'Dinner and a movie or show||Goint to a concert or sporting event', 'Slim/Skinny||Mom/Dad Bod', 'I don\'t have a preference||Doesn\'t smoke', 'Doesn\'t drink||I don\'t have a preference', 'High School||Undergraduate Degree', 'Apolitical', 'Caribeean/Haitian||Asian', '2024-01-16 08:22:12', '2024-01-16 08:22:12'),
(30, 47, 'Both', 18, 70, 0, 100, 10, 100, 'A long-term relationship||I\'m just checking things out', 'Cocktails and Dinner||Netflix and Chill', 'Slim/Skinny||Mom/Dad Bod||I like them all', 'Doesn\'t smoke||I don\'t have a preference', 'Doesn\'t drink||I don\'t have a preference', 'Undergraduate Degree||Graduate Degree', 'Apolitical||Conservative', 'Asian||Latina/Hispanic||Middle Eastern||Mixed', '2024-02-05 11:24:27', '2024-02-05 11:24:27');

-- --------------------------------------------------------

--
-- Table structure for table `virtual_dates`
--

CREATE TABLE `virtual_dates` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `opponent_id` int(11) DEFAULT NULL,
  `session_length` int(11) DEFAULT NULL,
  `date_time` timestamp NULL DEFAULT NULL,
  `approval_status` enum('Pending','Approved','Rejected','Canceled') DEFAULT 'Pending',
  `create_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `virtual_dates`
--

INSERT INTO `virtual_dates` (`id`, `user_id`, `opponent_id`, `session_length`, `date_time`, `approval_status`, `create_date`, `update_date`) VALUES
(1, 43, 7, 30, '0000-00-00 00:00:00', 'Approved', '2023-06-28 16:26:12', '2023-06-28 16:26:29'),
(2, 43, 6, 60, '2024-02-12 09:30:00', 'Pending', '2024-02-11 08:34:31', '2024-02-11 08:34:31'),
(3, 43, 15, 30, '2024-01-31 23:30:00', 'Pending', '2024-02-11 09:35:55', '2024-02-11 09:35:55'),
(4, 23, 43, 60, '2024-02-02 00:30:00', 'Pending', '2024-02-12 00:12:55', '2024-02-11 15:39:55'),
(5, 47, 43, 30, '2024-01-31 23:30:00', 'Pending', '2024-02-12 00:13:06', '2024-02-12 00:13:08'),
(8, 8, 43, 30, '2024-02-12 09:30:00', 'Approved', '2024-02-12 02:14:41', '2024-02-12 02:14:44'),
(9, 12, 43, 30, '2024-02-12 09:30:00', 'Approved', '2024-02-12 02:14:46', '2024-02-12 02:14:49'),
(10, 43, 18, 60, '2024-02-12 09:30:00', 'Approved', '2024-02-12 02:15:24', '2024-02-12 02:15:24'),
(11, 43, 19, 30, '2024-02-12 09:30:00', 'Approved', '2024-02-12 02:15:24', '2024-02-12 02:15:24'),
(12, 43, 9, 30, '2024-02-28 00:00:00', 'Pending', '2024-02-12 14:07:24', '2024-02-12 14:07:24'),
(13, 43, 35, 61, '2024-01-31 23:30:00', 'Pending', '2024-02-12 14:42:02', '2024-02-12 14:42:02'),
(14, 43, 11, 30, '2024-02-02 00:00:00', 'Pending', '2024-02-12 14:48:52', '2024-02-12 14:48:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blasts`
--
ALTER TABLE `blasts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blocks`
--
ALTER TABLE `blocks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `helps`
--
ALTER TABLE `helps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `keys`
--
ALTER TABLE `keys`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `matches`
--
ALTER TABLE `matches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `swipes`
--
ALTER TABLE `swipes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_answers`
--
ALTER TABLE `users_answers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_photos`
--
ALTER TABLE `users_photos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_preferences`
--
ALTER TABLE `users_preferences`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `virtual_dates`
--
ALTER TABLE `virtual_dates`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blasts`
--
ALTER TABLE `blasts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `blocks`
--
ALTER TABLE `blocks`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `helps`
--
ALTER TABLE `helps`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `keys`
--
ALTER TABLE `keys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `matches`
--
ALTER TABLE `matches`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `swipes`
--
ALTER TABLE `swipes`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=239;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `users_answers`
--
ALTER TABLE `users_answers`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `users_photos`
--
ALTER TABLE `users_photos`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `users_preferences`
--
ALTER TABLE `users_preferences`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `virtual_dates`
--
ALTER TABLE `virtual_dates`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
