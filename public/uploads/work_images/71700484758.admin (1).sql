-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 31, 2023 at 06:28 AM
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
-- Database: `admin`
--

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `banner` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `banner`, `status`, `created_at`, `updated_at`) VALUES
(1, 'http://localhost/adminpanel/public/uploads/banner1697623908.jpg', 0, '2023-10-18 04:41:48', '2023-10-18 05:59:06'),
(2, 'http://localhost/adminpanel/public/uploads/banner1697624030.jpg', 1, '2023-10-18 04:43:50', '2023-10-18 04:43:50'),
(3, 'http://localhost/adminpanel/public/uploads/banner1697628898.jpg', 1, '2023-10-18 06:04:58', '2023-10-18 06:04:58'),
(4, 'http://localhost/adminpanel/public/uploads/banner1697628953.jpg', 1, '2023-10-18 06:05:53', '2023-10-18 06:05:53');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `sub_cat_id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_id` int(11) NOT NULL,
  `item_qty` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `packers_movers_id` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `user_id`, `cat_id`, `sub_cat_id`, `item_name`, `item_id`, `item_qty`, `status`, `packers_movers_id`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 1, 'Center Table', 1, 2, 1, 1, '2023-10-25 01:10:09', '2023-10-25 01:10:44'),
(2, 3, 1, 1, 'Folding Table', 2, 1, 1, 1, '2023-10-25 01:10:10', '2023-10-25 01:10:44'),
(3, 3, 3, 7, 'Window AC', 11, 1, 1, 1, '2023-10-25 01:10:15', '2023-10-25 01:10:44');

-- --------------------------------------------------------

--
-- Table structure for table `categorise`
--

CREATE TABLE `categorise` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categorise`
--

INSERT INTO `categorise` (`id`, `name`, `type`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Furniture', 'packers & movers', 1, '2023-10-18 08:02:11', '2023-10-19 01:59:02'),
(2, 'Small Appliances', 'packers & movers', 1, '2023-10-18 08:02:19', '2023-10-19 02:00:58'),
(3, 'Large Appliances', 'packers & movers', 1, '2023-10-19 02:01:13', '2023-10-19 02:01:13'),
(4, 'Smaller/Loose Items', 'packers & movers', 1, '2023-10-19 02:01:33', '2023-10-19 02:01:33');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `homepagebanner`
--

CREATE TABLE `homepagebanner` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `section` varchar(255) NOT NULL,
  `image1` varchar(255) NOT NULL,
  `image2` varchar(255) NOT NULL,
  `image3` varchar(255) NOT NULL,
  `image4` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `homepagebanner`
--

INSERT INTO `homepagebanner` (`id`, `section`, `image1`, `image2`, `image3`, `image4`, `created_at`, `updated_at`) VALUES
(1, 'Packers & Movers', 'http://localhost/adminpanel/public/uploads/11697621891.jpg', 'http://localhost/adminpanel/public/uploads/21697621555.jpg', 'http://localhost/adminpanel/public/uploads/31697621555.jpg', 'http://localhost/adminpanel/public/uploads/41697621555.jpg', NULL, NULL),
(2, 'Interior Designing', 'http://localhost/adminpanel/public/uploads/11697621738.jpg', 'http://localhost/adminpanel/public/uploads/21697621738.jpg', 'http://localhost/adminpanel/public/uploads/31697621738.jpg', 'http://localhost/adminpanel/public/uploads/41697621738.jpg', NULL, NULL),
(3, 'Road Side Assistance', 'http://localhost/adminpanel/public/uploads/11697621760.jpg', 'http://localhost/adminpanel/public/uploads/21697621760.jpg', 'http://localhost/adminpanel/public/uploads/31697621760.jpg', 'http://localhost/adminpanel/public/uploads/41697621760.jpg', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cat_id` varchar(255) NOT NULL,
  `sub_cat_id` varchar(255) NOT NULL,
  `item` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `cat_id`, `sub_cat_id`, `item`, `status`, `created_at`, `updated_at`) VALUES
(1, '1', '1', 'Center Table', 1, '2023-10-19 06:07:45', '2023-10-19 02:06:03'),
(2, '1', '1', 'Folding Table', 1, '2023-10-19 01:25:05', '2023-10-19 02:06:23'),
(3, '2', '4', 'Dish Washer', 1, '2023-10-19 01:25:27', '2023-10-19 02:06:49'),
(4, '2', '4', 'Mixer/Grinder', 1, '2023-10-19 01:55:43', '2023-10-19 02:07:18'),
(5, '2', '5', 'Speaker', 1, '2023-10-19 01:56:00', '2023-10-19 02:07:48'),
(6, '2', '4', 'Vacuum Cleaner', 1, '2023-10-19 01:56:25', '2023-10-19 02:08:11'),
(7, '2', '5', 'Air Cooler', 1, '2023-10-19 02:08:33', '2023-10-19 02:08:33'),
(8, '3', '6', 'Fridge Single Door', 1, '2023-10-19 02:09:02', '2023-10-19 02:09:02'),
(9, '3', '6', 'Fridge 300-499lts', 1, '2023-10-19 02:09:42', '2023-10-19 02:09:42'),
(10, '3', '6', 'Fridge Above 500 lts', 1, '2023-10-19 02:10:00', '2023-10-19 02:10:00'),
(11, '3', '7', 'Window AC', 1, '2023-10-19 02:10:32', '2023-10-19 02:10:32'),
(12, '3', '7', 'Split AC', 1, '2023-10-19 02:10:43', '2023-10-19 02:10:43'),
(13, '3', '8', 'Washing machine 8kg+', 1, '2023-10-19 02:11:06', '2023-10-19 02:11:06'),
(14, '4', '10', 'Carrom Board', 1, '2023-10-19 02:11:35', '2023-10-19 02:11:35');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(5, '2023_10_16_071656_create_packers_movers_table', 2),
(6, '2023_10_16_114033_create_categorise_table', 3),
(7, '2023_10_16_114253_create_sub_categorise_table', 3),
(8, '2023_10_16_114318_create_sub_sub_categorise_table', 3),
(9, '2023_10_18_074539_create_homepagebanner_table', 4),
(10, '2023_10_18_095142_create_banners_table', 5),
(11, '2023_10_19_060301_create_items_table', 6),
(12, '2023_10_19_130403_create_cart_items_table', 7),
(13, '2023_10_25_131037_create_vehicle_table', 8),
(14, '2023_10_25_131340_create_road_side_assistance_table', 9);

-- --------------------------------------------------------

--
-- Table structure for table `packers_movers`
--

CREATE TABLE `packers_movers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `city` varchar(255) DEFAULT NULL,
  `shifting_from` varchar(255) NOT NULL,
  `shifting_to` varchar(255) NOT NULL,
  `date_slot` date DEFAULT NULL,
  `time_slot` varchar(255) DEFAULT NULL,
  `confirm_status` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `packers_movers`
--

INSERT INTO `packers_movers` (`id`, `user_id`, `city`, `shifting_from`, `shifting_to`, `date_slot`, `time_slot`, `confirm_status`, `created_at`, `updated_at`) VALUES
(1, 3, NULL, 'Kanpur', 'Delhi', '2023-10-25', '5PM', 1, '2023-10-25 01:06:40', '2023-10-25 01:09:59');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `road_side_assistance`
--

CREATE TABLE `road_side_assistance` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `booking_type` varchar(255) NOT NULL,
  `contact_no` bigint(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `landmark` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `save_locality` varchar(255) DEFAULT NULL,
  `date` varchar(255) NOT NULL,
  `time` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `road_side_assistance`
--

INSERT INTO `road_side_assistance` (`id`, `user_id`, `vehicle_id`, `booking_type`, `contact_no`, `address`, `landmark`, `created_at`, `updated_at`, `save_locality`, `date`, `time`, `name`, `email`) VALUES
(1, 3, 3, '2W General Services', 8077619781, 'T4/201', 'Near SBI', '2023-10-30 01:05:58', '2023-10-30 01:05:58', 'Noida UttarPradesh', '', '', '', ''),
(2, 3, 3, '2W General Services', 8077619781, 'T4/201', 'Near SBI', '2023-10-30 01:44:45', '2023-10-30 01:44:45', 'Noida UttarPradesh', '', '', '', ''),
(3, 3, 3, '2W General Services', 8077619781, 'T4/201', 'Near SBI', '2023-10-30 01:51:23', '2023-10-30 01:51:23', 'Noida UttarPradesh', '', '', '', ''),
(4, 3, 3, '2W General Services', 8077619781, 'T4/201', 'Near SBI', '2023-10-30 01:52:57', '2023-10-30 01:52:57', 'Noida UttarPradesh', '', '', '', ''),
(5, 3, 2, 'Towing', 9898123111, 'T4/201', 'Near SBI', '2023-10-30 04:36:13', '2023-10-30 04:36:13', 'Noida UttarPradesh', '', '', '', ''),
(6, 3, 2, 'Towing', 9898123111, 'T4/201', 'Near SBI', '2023-10-30 04:51:05', '2023-10-30 04:51:05', 'Noida UttarPradesh', '', '', '', ''),
(7, 3, 2, 'Towing', 9898123111, 'T4/201', 'Near SBI', '2023-10-30 04:55:08', '2023-10-30 04:55:08', 'Noida UttarPradesh', '', '', '', ''),
(8, 3, 2, 'Towing', 9898123111, 'T4/201', 'Near SBI', '2023-10-30 04:55:17', '2023-10-30 04:55:17', 'Noida UttarPradesh', '', '', '', ''),
(9, 3, 2, 'Towing', 9898123111, 'T4/201', 'Near SBI', '2023-10-30 04:56:23', '2023-10-30 04:56:23', 'Noida UttarPradesh', '', '', '', ''),
(10, 3, 2, 'Towing', 9898123111, 'T4/201', 'Near SBI', '2023-10-30 06:07:03', '2023-10-30 06:07:03', 'Noida UttarPradesh', '', '', '', ''),
(11, 3, 2, 'Towing', 9898123111, 'T4/201', 'Near SBI', '2023-10-30 06:09:05', '2023-10-30 06:10:49', 'Noida UttarPradesh', '1970-01-01', '7:00 PM', 'suraj', 'suraj@nd.com');

-- --------------------------------------------------------

--
-- Table structure for table `sub_categorise`
--

CREATE TABLE `sub_categorise` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cat_id` int(11) NOT NULL,
  `sub_cat_name` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sub_categorise`
--

INSERT INTO `sub_categorise` (`id`, `cat_id`, `sub_cat_name`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Table', 1, '2023-10-19 05:02:25', '2023-10-19 02:02:28'),
(2, 1, 'Bed/Matteress', 1, '2023-10-18 23:46:13', '2023-10-19 02:02:51'),
(3, 1, 'Chair', 1, '2023-10-18 23:49:11', '2023-10-19 02:03:08'),
(4, 2, 'Kitchen Appliances', 1, '2023-10-19 00:09:56', '2023-10-19 02:03:35'),
(5, 2, 'General Appliances', 1, '2023-10-19 02:03:54', '2023-10-19 02:03:54'),
(6, 3, 'Refrigerator', 1, '2023-10-19 02:04:17', '2023-10-19 02:04:17'),
(7, 3, 'Air Conditioner', 1, '2023-10-19 02:04:32', '2023-10-19 02:04:32'),
(8, 3, 'Washing Machine', 1, '2023-10-19 02:04:42', '2023-10-19 02:04:42'),
(9, 4, 'Bedding', 1, '2023-10-19 02:04:51', '2023-10-19 02:04:51'),
(10, 4, 'Toys', 1, '2023-10-19 02:04:57', '2023-10-19 02:04:57'),
(11, 4, 'Clothes & Shoes', 1, '2023-10-19 02:05:14', '2023-10-19 02:05:14'),
(12, 4, 'Books', 1, '2023-10-19 02:05:19', '2023-10-19 02:05:19');

-- --------------------------------------------------------

--
-- Table structure for table `sub_sub_categorise`
--

CREATE TABLE `sub_sub_categorise` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cat_id` int(11) NOT NULL,
  `sub_cat_id` int(11) NOT NULL,
  `sub_sub_cat_name` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tmp_noti`
--

CREATE TABLE `tmp_noti` (
  `id` int(11) NOT NULL,
  `notidata` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `role_id` int(11) DEFAULT 1 COMMENT '1=user',
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `state_id` varchar(255) DEFAULT NULL,
  `city_id` varchar(255) DEFAULT NULL,
  `referral_code` varchar(255) DEFAULT NULL,
  `dob` varchar(255) DEFAULT NULL,
  `longitude` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT 'male',
  `remember_token` varchar(255) DEFAULT NULL,
  `wallet` varchar(255) DEFAULT '0',
  `actual_amount` double NOT NULL DEFAULT 0,
  `free_cash` double NOT NULL DEFAULT 0,
  `refer_user_id` varchar(255) DEFAULT NULL,
  `latitude` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  `image` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `token` longtext DEFAULT NULL,
  `is_delete` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `time_of_birth` varchar(100) DEFAULT NULL,
  `social_id` text DEFAULT NULL,
  `social_type` varchar(100) DEFAULT NULL,
  `language` varchar(255) DEFAULT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `is_businesses` int(11) NOT NULL DEFAULT 0,
  `is_notify` int(11) NOT NULL DEFAULT 1,
  `is_notify_full` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `phone`, `state_id`, `city_id`, `referral_code`, `dob`, `longitude`, `gender`, `remember_token`, `wallet`, `actual_amount`, `free_cash`, `refer_user_id`, `latitude`, `status`, `image`, `password`, `token`, `is_delete`, `created_at`, `updated_at`, `time_of_birth`, `social_id`, `social_type`, `language`, `google_id`, `is_businesses`, `is_notify`, `is_notify_full`) VALUES
(1, 2, 'admin', 'admin@broopi.com', '8077619780', NULL, NULL, NULL, NULL, '77.4036616', 'male', NULL, '0', 0, 0, NULL, '28.4952738', 1, NULL, '$2y$10$d6jHSHzxPF5bIXr/gfftgOpAGLDpUROjbDJGD6qE7gJrJNi9yyLYS', NULL, 0, '2023-09-05 10:50:04', '2023-09-21 12:19:52', NULL, NULL, NULL, NULL, NULL, 0, 1, 1),
(3, 1, 'Suraj', 'suraj@yopmail.com', '8077619781', NULL, NULL, NULL, NULL, NULL, 'male', NULL, '0', 0, 0, NULL, NULL, 1, NULL, NULL, NULL, 0, '2023-10-11 00:17:54', '2023-10-11 00:17:54', NULL, NULL, NULL, NULL, NULL, 0, 1, 1),
(4, 1, 'Mukul', 'mukul@yopmail.com', '9319009459', NULL, NULL, NULL, NULL, NULL, 'male', NULL, '0', 0, 0, NULL, NULL, 1, NULL, NULL, NULL, 0, '2023-10-11 00:44:47', '2023-10-11 00:44:47', NULL, NULL, NULL, NULL, NULL, 0, 1, 1),
(5, 1, 'Mukul', 'mukul@yopmail.com', '9319009459', NULL, NULL, NULL, NULL, NULL, 'male', NULL, '0', 0, 0, NULL, NULL, 1, NULL, NULL, NULL, 0, '2023-10-11 00:44:47', '2023-10-11 00:44:47', NULL, NULL, NULL, NULL, NULL, 0, 1, 1),
(6, 1, 'Mukul3', 'mukul@yopmail.com', '9319009459', NULL, NULL, NULL, NULL, NULL, 'male', NULL, '0', 0, 0, NULL, NULL, 1, NULL, NULL, NULL, 0, '2023-10-11 00:44:47', '2023-10-11 00:44:47', NULL, NULL, NULL, NULL, NULL, 0, 1, 1),
(7, 1, 'Mukul4', 'mukul@yopmail.com', '9319009459', NULL, NULL, NULL, NULL, NULL, 'male', NULL, '0', 0, 0, NULL, NULL, 1, NULL, NULL, NULL, 0, '2023-10-11 00:44:47', '2023-10-11 00:44:47', NULL, NULL, NULL, NULL, NULL, 0, 1, 1),
(8, 1, 'Mukul5', 'mukul@yopmail.com', '9319009459', NULL, NULL, NULL, NULL, NULL, 'male', NULL, '0', 0, 0, NULL, NULL, 1, NULL, NULL, NULL, 0, '2023-10-11 00:44:47', '2023-10-11 00:44:47', NULL, NULL, NULL, NULL, NULL, 0, 1, 1),
(9, 1, 'Mukul6', 'mukul@yopmail.com', '9319009459', NULL, NULL, NULL, NULL, NULL, 'male', NULL, '0', 0, 0, NULL, NULL, 1, NULL, NULL, NULL, 0, '2023-10-11 00:44:47', '2023-10-11 00:44:47', NULL, NULL, NULL, NULL, NULL, 0, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_otp`
--

CREATE TABLE `user_otp` (
  `id` int(11) NOT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `otp` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `type` varchar(255) DEFAULT NULL,
  `timestamp` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_otp`
--

INSERT INTO `user_otp` (`id`, `mobile`, `otp`, `created_at`, `type`, `timestamp`, `updated_at`) VALUES
(1, '8077619781', '123456', '2023-10-09 23:16:02', 'web_login', '2023-10-30 10:19:22', '2023-10-30 04:34:23'),
(2, '9319009459', '913774', '2023-10-10 23:14:09', 'web_login', '2023-10-11 06:28:33', '2023-10-11 00:43:33');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle`
--

CREATE TABLE `vehicle` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `vehicle_type` varchar(255) NOT NULL,
  `reg_no` varchar(255) DEFAULT NULL,
  `chassis_no` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vehicle`
--

INSERT INTO `vehicle` (`id`, `user_id`, `vehicle_type`, `reg_no`, `chassis_no`, `created_at`, `updated_at`) VALUES
(1, 3, 'Bike', 'UP 16 HR 3316', NULL, '2023-10-26 01:56:26', '2023-10-26 01:56:26'),
(2, 3, 'car', 'UP 16 HR 3312', NULL, '2023-10-26 01:56:58', '2023-10-26 01:56:58'),
(3, 3, 'Car', 'UP 16 HR 33122', NULL, '2023-10-30 01:05:22', '2023-10-30 01:05:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categorise`
--
ALTER TABLE `categorise`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `homepagebanner`
--
ALTER TABLE `homepagebanner`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `packers_movers`
--
ALTER TABLE `packers_movers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `road_side_assistance`
--
ALTER TABLE `road_side_assistance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_categorise`
--
ALTER TABLE `sub_categorise`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_sub_categorise`
--
ALTER TABLE `sub_sub_categorise`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tmp_noti`
--
ALTER TABLE `tmp_noti`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`,`name`,`email`,`phone`);

--
-- Indexes for table `user_otp`
--
ALTER TABLE `user_otp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vehicle`
--
ALTER TABLE `vehicle`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `categorise`
--
ALTER TABLE `categorise`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `homepagebanner`
--
ALTER TABLE `homepagebanner`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `packers_movers`
--
ALTER TABLE `packers_movers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `road_side_assistance`
--
ALTER TABLE `road_side_assistance`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `sub_categorise`
--
ALTER TABLE `sub_categorise`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `sub_sub_categorise`
--
ALTER TABLE `sub_sub_categorise`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tmp_noti`
--
ALTER TABLE `tmp_noti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user_otp`
--
ALTER TABLE `user_otp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `vehicle`
--
ALTER TABLE `vehicle`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
