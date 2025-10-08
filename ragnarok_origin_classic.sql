-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 08, 2025 at 08:44 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ragnarok_origin_classic`
--

-- --------------------------------------------------------

--
-- Table structure for table `achievements`
--

CREATE TABLE `achievements` (
  `achievement_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `reward_item_id` int(11) DEFAULT NULL,
  `reward_title` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `achievements`
--

INSERT INTO `achievements` (`achievement_id`, `name`, `description`, `reward_item_id`, `reward_title`, `created_at`) VALUES
(1, 'First Blood', 'Defeat your first monster', NULL, 'Novice Slayer', '2025-10-02 18:03:31'),
(2, 'Boss Hunter', 'Defeat an MVP Boss', 6, 'MVP Killer', '2025-10-02 18:03:31'),
(3, 'Treasure Collector', 'Collect 1,000,000 zeny', NULL, 'Rich Kid', '2025-10-02 18:03:31');

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `log_id` int(11) NOT NULL,
  `char_id` int(11) NOT NULL,
  `activity_type` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `activity_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`log_id`, `char_id`, `activity_type`, `description`, `activity_date`) VALUES
(1, 1, 'LOGIN', 'Player logged in', '2025-10-02 16:58:58'),
(2, 2, 'TRADE', 'Player traded item with char_id 5', '2025-10-02 16:58:58'),
(3, 3, 'USE_ITEM', 'Used Potion', '2025-10-02 16:58:58'),
(4, 4, 'EQUIP', 'Equipped Sword of Dawn', '2025-10-02 16:58:58'),
(5, 5, 'LEVEL_UP', 'Reached level 50', '2025-10-02 16:58:58');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `player_id` int(11) NOT NULL,
  `role` enum('gm','admin') DEFAULT 'gm',
  `granted_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `email`, `password`, `player_id`, `role`, `granted_at`) VALUES
(0, 'admin@example.com', '123456', 53, 'admin', '2025-10-07 22:15:33'),
(1, 'chanasorn.eaf@gmail.com\r\n', '123456', 51, 'admin', '2025-10-02 17:55:02'),
(2, '', '123456', 52, 'admin', '2025-10-02 17:55:13');

-- --------------------------------------------------------

--
-- Table structure for table `admin_logs`
--

CREATE TABLE `admin_logs` (
  `log_id` int(11) NOT NULL,
  `admin_name` varchar(50) NOT NULL,
  `action_type` varchar(50) NOT NULL,
  `action_detail` text NOT NULL,
  `log_time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_logs`
--

INSERT INTO `admin_logs` (`log_id`, `admin_name`, `action_type`, `action_detail`, `log_time`) VALUES
(1, 'AdminZero', 'BAN_PLAYER', 'Banned char_id 7 for cheating', '2025-10-02 17:00:39'),
(2, 'GameMasterLuna', 'GIVE_ITEM', 'Gave Hi-Potion to char_id 1', '2025-10-02 17:00:39'),
(3, 'AdminZero', 'MODIFY_ZENY', 'Added 10000 Zeny to char_id 3', '2025-10-02 17:00:39'),
(4, 'GM_Kris', 'UNBAN', 'Unbanned char_id 7 after investigation', '2025-10-02 17:00:39');

-- --------------------------------------------------------

--
-- Table structure for table `awakenings`
--

CREATE TABLE `awakenings` (
  `char_id` int(11) NOT NULL,
  `awake_level` int(11) DEFAULT 0,
  `awakened` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `awakenings`
--

INSERT INTO `awakenings` (`char_id`, `awake_level`, `awakened`) VALUES
(1, 0, 0),
(2, 0, 0),
(3, 0, 0),
(4, 0, 0),
(5, 5, 1),
(6, 0, 0),
(7, 5, 1),
(8, 0, 0),
(9, 0, 0),
(10, 0, 0),
(11, 0, 0),
(12, 5, 1),
(13, 0, 0),
(14, 0, 0),
(15, 0, 0),
(16, 0, 0),
(17, 0, 0),
(18, 0, 0),
(19, 0, 0),
(20, 5, 1),
(21, 0, 0),
(22, 0, 0),
(23, 0, 0),
(24, 0, 0),
(25, 0, 0),
(26, 0, 0),
(27, 0, 0),
(28, 0, 0),
(29, 0, 0),
(30, 0, 0),
(31, 0, 0),
(32, 0, 0),
(33, 5, 1),
(34, 0, 0),
(35, 0, 0),
(36, 0, 0),
(37, 0, 0),
(38, 0, 0),
(39, 0, 0),
(40, 0, 0),
(41, 5, 1),
(42, 0, 0),
(43, 0, 0),
(44, 0, 0),
(45, 0, 0),
(46, 0, 0),
(47, 0, 0),
(48, 0, 0),
(49, 0, 0),
(50, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `bans`
--

CREATE TABLE `bans` (
  `ban_id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `reason` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bans`
--

INSERT INTO `bans` (`ban_id`, `player_id`, `start_date`, `end_date`, `reason`, `created_at`) VALUES
(1, 2, '2025-10-07 23:57:35', '2025-10-08 23:57:35', 'เหงา', '2025-10-07 16:57:35');

-- --------------------------------------------------------

--
-- Table structure for table `blocked_users`
--

CREATE TABLE `blocked_users` (
  `block_id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  `blocked_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blocked_users`
--

INSERT INTO `blocked_users` (`block_id`, `player_id`, `blocked_id`, `created_at`) VALUES
(1, 1, 5, '2025-10-02 18:03:31'),
(2, 3, 6, '2025-10-02 18:03:31');

-- --------------------------------------------------------

--
-- Table structure for table `characters`
--

CREATE TABLE `characters` (
  `char_id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `gender` enum('M','F') NOT NULL,
  `level` int(11) DEFAULT 1,
  `exp` bigint(20) DEFAULT 0,
  `zenny` bigint(20) DEFAULT 0,
  `hp` int(11) DEFAULT 100,
  `mp` int(11) DEFAULT 50,
  `zeny` bigint(20) DEFAULT 0,
  `map_id` int(11) DEFAULT NULL,
  `pos_x` int(11) DEFAULT NULL,
  `pos_y` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `characters`
--

INSERT INTO `characters` (`char_id`, `player_id`, `name`, `gender`, `level`, `exp`, `zenny`, `hp`, `mp`, `zeny`, `map_id`, `pos_x`, `pos_y`, `created_at`) VALUES
(1, 1, 'Barbara', 'M', 49, 102116, 0, 2550, 1030, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(2, 2, 'Rebecca', 'M', 36, 0, 0, 1900, 770, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(3, 3, 'Nicholas', 'F', 59, 118590, 0, 3050, 1230, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(4, 4, 'Shelly', 'M', 82, 332182, 0, 4200, 1690, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(5, 5, 'James', 'M', 47, 155053, 0, 2450, 990, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(6, 6, 'Brittany', 'M', 21, 67347, 0, 1150, 470, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(7, 7, 'Maria', 'M', 48, 99648, 0, 2500, 1010, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(8, 8, 'John', 'M', 46, 186714, 0, 2400, 970, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(9, 9, 'Heidi', 'F', 27, 91638, 0, 1450, 590, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(10, 10, 'Kevin', 'M', 86, 236844, 0, 4400, 1770, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(11, 11, 'Gabrielle', 'M', 35, 163695, 0, 1850, 750, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(12, 12, 'Linda', 'M', 90, 305100, 0, 4600, 1850, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(13, 13, 'Laura', 'M', 88, 231880, 0, 4500, 1810, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(14, 14, 'Ashlee', 'M', 83, 206006, 0, 4250, 1710, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(15, 15, 'Blake', 'M', 10, 18980, 0, 600, 250, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(16, 16, 'Amanda', 'M', 78, 122148, 0, 4000, 1610, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(17, 17, 'Doris', 'F', 82, 253134, 0, 4200, 1690, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(18, 18, 'Casey', 'M', 22, 66462, 0, 1200, 490, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(19, 19, 'Deborah', 'F', 69, 94668, 0, 3550, 1430, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(20, 20, 'Alexander', 'F', 94, 384930, 0, 4800, 1930, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(21, 21, 'Robert', 'M', 32, 38144, 0, 1700, 690, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(22, 22, 'Tracy', 'M', 21, 95067, 0, 1150, 470, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(23, 23, 'Ryan', 'F', 60, 86940, 0, 3100, 1250, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(24, 24, 'Michael', 'F', 49, 79674, 0, 2550, 1030, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(25, 25, 'Patricia', 'F', 35, 124950, 0, 1850, 750, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(26, 26, 'Steve', 'M', 82, 135710, 0, 4200, 1690, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(27, 27, 'Eric', 'M', 89, 377716, 0, 4550, 1830, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(28, 28, 'Gregory', 'F', 72, 272664, 0, 3700, 1490, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(29, 29, 'Aimee', 'M', 29, 79141, 0, 1550, 630, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(30, 30, 'Debra', 'M', 88, 302896, 0, 4500, 1810, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(31, 31, 'Christina', 'F', 42, 52920, 0, 2200, 890, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(32, 32, 'Samantha', 'M', 99, 255024, 0, 5050, 2030, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(33, 33, 'Kim', 'F', 8, 20504, 0, 500, 210, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(34, 34, 'Steven', 'F', 30, 103200, 0, 1600, 650, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(35, 35, 'Margaret', 'F', 5, 14585, 0, 350, 150, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(36, 36, 'Marcus', 'M', 41, 129847, 0, 2150, 870, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(37, 37, 'Sarah', 'F', 52, 105508, 0, 2700, 1090, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(38, 38, 'Joshua', 'M', 35, 174195, 0, 1850, 750, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(39, 39, 'Shannon', 'F', 9, 29394, 0, 550, 230, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(40, 40, 'Karen', 'M', 28, 126700, 0, 1500, 610, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(41, 41, 'Ashley', 'F', 73, 354780, 0, 3750, 1510, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(42, 42, 'Elizabeth', 'F', 92, 96324, 0, 4700, 1890, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(43, 43, 'Matthew', 'M', 41, 155226, 0, 2150, 870, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(44, 44, 'Troy', 'M', 28, 110656, 0, 1500, 610, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(45, 45, 'Mark', 'M', 84, 123396, 0, 4300, 1730, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(46, 46, 'Tony', 'M', 64, 242688, 0, 3300, 1330, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(47, 47, 'Jonathan', 'F', 51, 235824, 0, 2650, 1070, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(48, 48, 'Caitlyn', 'M', 83, 265517, 0, 4250, 1710, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(49, 49, 'Nicole', 'M', 59, 240425, 0, 3050, 1230, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(50, 50, 'Angela', 'M', 19, 39748, 0, 1050, 430, NULL, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(0, 1, 'Zerox', 'M', 10, 2500, 500, 600, 200, 0, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(0, 2, 'Rinako', 'F', 20, 12000, 2000, 1500, 500, 0, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(0, 3, 'Kyren', 'M', 35, 28000, 4000, 3500, 800, 0, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(0, 4, 'Mitsuki', 'F', 47, 50000, 6000, 6000, 1200, 0, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(0, 5, 'Tetsu', 'M', 52, 90000, 8000, 9000, 1500, 0, NULL, NULL, NULL, '2025-10-07 23:41:57'),
(0, 23, 'undefined', 'F', 1, 0, 0, 100, 50, 0, NULL, NULL, NULL, '2025-10-08 00:15:35'),
(0, 24, 'undefined', 'F', 1, 0, 0, 100, 50, 0, NULL, NULL, NULL, '2025-10-08 00:16:32');

-- --------------------------------------------------------

--
-- Table structure for table `character_achievements`
--

CREATE TABLE `character_achievements` (
  `char_id` int(11) NOT NULL,
  `achievement_id` int(11) NOT NULL,
  `unlocked_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `character_achievements`
--

INSERT INTO `character_achievements` (`char_id`, `achievement_id`, `unlocked_at`) VALUES
(1, 1, '2025-10-02 18:03:31'),
(2, 2, '2025-10-02 18:03:31'),
(3, 3, '2025-10-02 18:03:31');

-- --------------------------------------------------------

--
-- Table structure for table `character_classes`
--

CREATE TABLE `character_classes` (
  `char_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `is_current` tinyint(1) DEFAULT 1,
  `change_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `character_classes`
--

INSERT INTO `character_classes` (`char_id`, `class_id`, `is_current`, `change_date`) VALUES
(1, 9, 1, '2025-10-02 16:48:29'),
(2, 6, 1, '2025-10-02 16:48:29'),
(3, 10, 1, '2025-10-02 16:48:29'),
(4, 13, 1, '2025-10-02 16:48:29'),
(5, 9, 1, '2025-10-02 16:48:29'),
(6, 3, 1, '2025-10-02 16:48:29'),
(7, 8, 1, '2025-10-02 16:48:29'),
(8, 5, 1, '2025-10-02 16:48:29'),
(9, 2, 1, '2025-10-02 16:48:29'),
(10, 12, 1, '2025-10-02 16:48:29'),
(11, 4, 1, '2025-10-02 16:48:29'),
(12, 13, 1, '2025-10-02 16:48:29'),
(13, 11, 1, '2025-10-02 16:48:29'),
(14, 10, 1, '2025-10-02 16:48:29'),
(15, 2, 1, '2025-10-02 16:48:29'),
(16, 14, 1, '2025-10-02 16:48:29'),
(17, 11, 1, '2025-10-02 16:48:29'),
(18, 3, 1, '2025-10-02 16:48:29'),
(19, 10, 1, '2025-10-02 16:48:29'),
(20, 12, 1, '2025-10-02 16:48:29'),
(21, 4, 1, '2025-10-02 16:48:29'),
(22, 2, 1, '2025-10-02 16:48:29'),
(23, 9, 1, '2025-10-02 16:48:29'),
(24, 8, 1, '2025-10-02 16:48:29'),
(25, 3, 1, '2025-10-02 16:48:29'),
(26, 13, 1, '2025-10-02 16:48:29'),
(27, 14, 1, '2025-10-02 16:48:29'),
(28, 12, 1, '2025-10-02 16:48:29'),
(29, 2, 1, '2025-10-02 16:48:29'),
(30, 13, 1, '2025-10-02 16:48:29'),
(31, 7, 1, '2025-10-02 16:48:29'),
(32, 14, 1, '2025-10-02 16:48:29'),
(33, 1, 1, '2025-10-02 16:48:29'),
(34, 2, 1, '2025-10-02 16:48:29'),
(35, 1, 1, '2025-10-02 16:48:29'),
(36, 4, 1, '2025-10-02 16:48:29'),
(37, 9, 1, '2025-10-02 16:48:29'),
(38, 3, 1, '2025-10-02 16:48:29'),
(39, 1, 1, '2025-10-02 16:48:29'),
(40, 5, 1, '2025-10-02 16:48:29'),
(41, 10, 1, '2025-10-02 16:48:29'),
(42, 14, 1, '2025-10-02 16:48:29'),
(43, 9, 1, '2025-10-02 16:48:29'),
(44, 6, 1, '2025-10-02 16:48:29'),
(45, 10, 1, '2025-10-02 16:48:29'),
(46, 13, 1, '2025-10-02 16:48:29'),
(47, 10, 1, '2025-10-02 16:48:29'),
(48, 13, 1, '2025-10-02 16:48:29'),
(49, 11, 1, '2025-10-02 16:48:29'),
(50, 3, 1, '2025-10-02 16:48:29');

-- --------------------------------------------------------

--
-- Table structure for table `character_skills`
--

CREATE TABLE `character_skills` (
  `char_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL,
  `level` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `character_skills`
--

INSERT INTO `character_skills` (`char_id`, `skill_id`, `level`) VALUES
(1, 22, 2),
(1, 37, 9),
(1, 59, 10),
(1, 64, 5),
(1, 71, 9),
(1, 73, 2),
(2, 6, 4),
(2, 28, 8),
(2, 47, 7),
(2, 57, 5),
(2, 70, 2),
(2, 79, 1),
(3, 12, 6),
(3, 16, 7),
(3, 31, 3),
(3, 34, 4),
(3, 45, 10),
(3, 57, 10),
(4, 8, 9),
(4, 18, 9),
(4, 27, 5),
(4, 73, 3),
(4, 78, 10),
(4, 79, 6),
(5, 35, 5),
(5, 38, 3),
(5, 42, 2),
(5, 66, 3),
(5, 74, 1),
(5, 79, 7),
(6, 17, 8),
(6, 19, 10),
(6, 32, 3),
(6, 42, 5),
(6, 51, 7),
(6, 79, 7),
(7, 10, 8),
(7, 12, 8),
(7, 36, 1),
(7, 52, 6),
(7, 58, 6),
(7, 66, 10),
(8, 9, 8),
(8, 12, 4),
(8, 46, 4),
(8, 51, 6),
(8, 59, 7),
(8, 69, 5),
(9, 17, 2),
(9, 37, 8),
(9, 44, 6),
(9, 71, 1),
(9, 73, 8),
(9, 74, 1),
(10, 1, 5),
(10, 3, 7),
(10, 16, 4),
(10, 21, 3),
(10, 57, 5),
(10, 59, 3),
(11, 11, 4),
(11, 12, 1),
(11, 22, 2),
(11, 33, 5),
(11, 47, 7),
(11, 48, 7),
(12, 1, 4),
(12, 10, 1),
(12, 13, 10),
(12, 17, 1),
(12, 28, 9),
(12, 62, 8),
(13, 10, 6),
(13, 16, 7),
(13, 17, 2),
(13, 44, 4),
(13, 55, 2),
(13, 62, 2),
(14, 18, 10),
(14, 19, 3),
(14, 39, 9),
(14, 48, 2),
(14, 49, 1),
(14, 78, 10),
(15, 20, 3),
(15, 28, 2),
(15, 45, 2),
(15, 53, 2),
(15, 57, 4),
(15, 80, 10),
(16, 18, 10),
(16, 32, 10),
(16, 41, 9),
(16, 45, 2),
(16, 50, 5),
(16, 55, 4),
(17, 4, 4),
(17, 27, 5),
(17, 37, 9),
(17, 39, 7),
(17, 67, 1),
(17, 78, 10),
(18, 15, 1),
(18, 31, 10),
(18, 50, 9),
(18, 64, 2),
(18, 79, 6),
(18, 80, 6),
(19, 17, 3),
(19, 47, 10),
(19, 50, 8),
(19, 54, 1),
(19, 70, 2),
(19, 73, 2),
(20, 2, 9),
(20, 6, 7),
(20, 8, 7),
(20, 28, 9),
(20, 34, 5),
(20, 51, 7),
(21, 11, 5),
(21, 20, 4),
(21, 36, 3),
(21, 69, 2),
(21, 79, 9),
(21, 80, 9),
(22, 9, 2),
(22, 40, 7),
(22, 42, 4),
(22, 51, 1),
(22, 56, 2),
(22, 76, 4),
(23, 8, 1),
(23, 23, 8),
(23, 40, 3),
(23, 59, 2),
(23, 78, 3),
(23, 79, 1),
(24, 22, 6),
(24, 34, 1),
(24, 45, 3),
(24, 48, 7),
(24, 67, 5),
(24, 79, 2),
(25, 9, 9),
(25, 10, 8),
(25, 34, 6),
(25, 35, 8),
(25, 68, 8),
(25, 74, 1),
(26, 14, 1),
(26, 21, 9),
(26, 22, 2),
(26, 33, 1),
(26, 48, 4),
(26, 73, 10),
(27, 2, 3),
(27, 6, 1),
(27, 32, 3),
(27, 47, 7),
(27, 50, 4),
(27, 61, 9),
(28, 10, 3),
(28, 32, 6),
(28, 35, 1),
(28, 41, 9),
(28, 42, 10),
(28, 54, 2),
(29, 6, 6),
(29, 9, 9),
(29, 29, 5),
(29, 52, 2),
(29, 60, 8),
(29, 67, 5),
(30, 2, 10),
(30, 26, 8),
(30, 38, 6),
(30, 39, 8),
(30, 48, 4),
(30, 73, 10),
(31, 1, 1),
(31, 20, 6),
(31, 26, 1),
(31, 32, 9),
(31, 53, 6),
(31, 70, 4),
(32, 1, 4),
(32, 14, 1),
(32, 27, 3),
(32, 40, 8),
(32, 49, 7),
(32, 68, 5),
(33, 6, 2),
(33, 12, 8),
(33, 30, 10),
(33, 48, 3),
(33, 53, 9),
(33, 67, 2),
(34, 8, 6),
(34, 10, 4),
(34, 51, 3),
(34, 56, 7),
(34, 73, 5),
(34, 76, 5),
(35, 10, 2),
(35, 30, 3),
(35, 35, 2),
(35, 41, 3),
(35, 42, 4),
(35, 58, 10),
(36, 16, 8),
(36, 22, 9),
(36, 41, 8),
(36, 53, 5),
(36, 58, 6),
(36, 60, 4),
(37, 6, 6),
(37, 12, 3),
(37, 16, 6),
(37, 21, 2),
(37, 34, 4),
(37, 39, 4),
(38, 7, 8),
(38, 23, 4),
(38, 34, 9),
(38, 39, 9),
(38, 51, 1),
(38, 66, 7),
(39, 2, 6),
(39, 13, 1),
(39, 14, 3),
(39, 16, 7),
(39, 18, 4),
(39, 60, 4),
(40, 3, 6),
(40, 10, 2),
(40, 11, 2),
(40, 34, 3),
(40, 75, 4),
(40, 76, 8),
(41, 6, 7),
(41, 7, 1),
(41, 16, 7),
(41, 22, 7),
(41, 60, 8),
(41, 74, 7),
(42, 23, 8),
(42, 24, 10),
(42, 28, 1),
(42, 36, 10),
(42, 46, 3),
(42, 76, 10),
(43, 32, 1),
(43, 38, 2),
(43, 53, 7),
(43, 62, 7),
(43, 64, 5),
(43, 71, 3),
(44, 3, 6),
(44, 26, 6),
(44, 32, 9),
(44, 49, 10),
(44, 68, 8),
(44, 70, 8),
(45, 4, 1),
(45, 24, 5),
(45, 35, 1),
(45, 42, 10),
(45, 50, 4),
(45, 65, 6),
(46, 21, 1),
(46, 30, 9),
(46, 46, 4),
(46, 61, 2),
(46, 68, 4),
(46, 75, 5),
(47, 23, 10),
(47, 29, 4),
(47, 46, 6),
(47, 50, 6),
(47, 74, 10),
(47, 76, 1),
(48, 19, 3),
(48, 25, 1),
(48, 46, 8),
(48, 73, 9),
(48, 78, 8),
(48, 79, 5),
(49, 3, 4),
(49, 8, 1),
(49, 12, 8),
(49, 29, 10),
(49, 30, 6),
(49, 68, 3),
(50, 3, 6),
(50, 8, 9),
(50, 19, 2),
(50, 23, 3),
(50, 42, 2),
(50, 44, 10);

-- --------------------------------------------------------

--
-- Table structure for table `character_stats`
--

CREATE TABLE `character_stats` (
  `char_id` int(11) NOT NULL,
  `str` int(11) DEFAULT 1,
  `agi` int(11) DEFAULT 1,
  `vit` int(11) DEFAULT 1,
  `int_stat` int(11) DEFAULT 1,
  `dex` int(11) DEFAULT 1,
  `luk` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `character_stats`
--

INSERT INTO `character_stats` (`char_id`, `str`, `agi`, `vit`, `int_stat`, `dex`, `luk`) VALUES
(1, 48, 91, 85, 51, 76, 13),
(2, 44, 39, 42, 18, 21, 94),
(3, 56, 82, 63, 84, 41, 23),
(4, 91, 72, 89, 79, 46, 29),
(5, 86, 76, 23, 49, 40, 94),
(6, 89, 38, 17, 23, 94, 1),
(7, 90, 74, 51, 99, 73, 5),
(8, 24, 78, 41, 79, 29, 82),
(9, 73, 14, 64, 19, 43, 96),
(10, 10, 31, 45, 41, 22, 82),
(11, 12, 91, 86, 82, 94, 43),
(12, 58, 2, 34, 27, 32, 89),
(13, 9, 45, 33, 14, 93, 1),
(14, 7, 50, 57, 94, 54, 22),
(15, 53, 64, 49, 45, 70, 49),
(16, 13, 62, 74, 84, 98, 88),
(17, 29, 21, 58, 10, 5, 38),
(18, 3, 41, 34, 14, 10, 44),
(19, 22, 49, 21, 94, 10, 71),
(20, 12, 44, 77, 79, 62, 91),
(21, 4, 56, 84, 22, 78, 56),
(22, 21, 7, 13, 43, 27, 25),
(23, 53, 90, 71, 93, 94, 70),
(24, 34, 85, 37, 39, 31, 13),
(25, 7, 51, 74, 71, 63, 20),
(26, 7, 47, 1, 55, 12, 38),
(27, 85, 81, 77, 62, 26, 13),
(28, 4, 27, 22, 82, 38, 11),
(29, 61, 15, 40, 51, 61, 63),
(30, 85, 34, 12, 83, 70, 50),
(31, 24, 48, 49, 48, 24, 58),
(32, 6, 34, 57, 60, 34, 29),
(33, 35, 73, 8, 20, 97, 90),
(34, 86, 13, 11, 86, 45, 70),
(35, 53, 97, 76, 30, 71, 8),
(36, 50, 67, 54, 69, 88, 61),
(37, 74, 31, 61, 39, 11, 51),
(38, 92, 5, 65, 74, 67, 74),
(39, 87, 80, 19, 16, 97, 58),
(40, 23, 22, 28, 25, 17, 6),
(41, 55, 11, 88, 56, 26, 81),
(42, 19, 77, 34, 41, 94, 9),
(43, 12, 50, 72, 51, 71, 42),
(44, 36, 67, 59, 2, 90, 80),
(45, 76, 67, 54, 15, 54, 20),
(46, 19, 73, 76, 75, 98, 13),
(47, 14, 73, 13, 37, 69, 45),
(48, 53, 34, 50, 84, 63, 74),
(49, 78, 61, 5, 23, 36, 52),
(50, 19, 80, 78, 88, 89, 52);

-- --------------------------------------------------------

--
-- Table structure for table `chat_logs`
--

CREATE TABLE `chat_logs` (
  `chat_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `sent_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_logs`
--

INSERT INTO `chat_logs` (`chat_id`, `sender_id`, `receiver_id`, `message`, `sent_at`) VALUES
(1, 1, 2, 'Hey, wanna party for Orc Dungeon?', '2025-10-02 18:03:31'),
(2, 2, 1, 'Sure! Let’s go.', '2025-10-02 18:03:31'),
(3, 3, 4, 'Sell Sword[3] 10k zeny', '2025-10-02 18:03:31');

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `class_id` int(11) NOT NULL,
  `class_name` varchar(50) NOT NULL,
  `base_hp` int(11) DEFAULT 100,
  `base_mp` int(11) DEFAULT 50,
  `base_attack` int(11) DEFAULT 10,
  `base_defense` int(11) DEFAULT 5,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`class_id`, `class_name`, `base_hp`, `base_mp`, `base_attack`, `base_defense`, `description`) VALUES
(1, 'Novice', 100, 50, 10, 5, NULL),
(2, 'Swordsman', 100, 50, 10, 5, NULL),
(3, 'Knight', 100, 50, 10, 5, NULL),
(4, 'Crusader', 100, 50, 10, 5, NULL),
(5, 'Mage', 100, 50, 10, 5, NULL),
(6, 'Wizard', 100, 50, 10, 5, NULL),
(7, 'Sage', 100, 50, 10, 5, NULL),
(8, 'Archer', 100, 50, 10, 5, NULL),
(9, 'Assassin', 100, 50, 10, 5, NULL),
(10, 'Hunter', 100, 50, 10, 5, NULL),
(11, 'Acolyte', 100, 50, 10, 5, NULL),
(12, 'Priest', 100, 50, 10, 5, NULL),
(13, 'Blacksmith', 100, 50, 10, 5, NULL),
(14, 'Alchemist', 100, 50, 10, 5, NULL),
(15, 'Novice', 100, 50, 10, 5, 'ผู้เริ่มต้นของโลก Ragnarok'),
(16, 'Swordsman', 250, 60, 30, 20, 'นักรบผู้ใช้ดาบและโล่'),
(17, 'Mage', 120, 200, 15, 8, 'จอมเวทย์ผู้ใช้พลังเวทมนตร์'),
(18, 'Archer', 150, 80, 25, 10, 'นักธนูผู้เชี่ยวชาญในการโจมตีระยะไกล'),
(19, 'Thief', 130, 70, 22, 12, 'ผู้เชี่ยวชาญด้านการหลบหลีกและโจมตีเร็ว'),
(20, 'Acolyte', 140, 180, 10, 8, 'ผู้ใช้พลังศักดิ์สิทธิ์ในการช่วยเหลือผู้อื่น'),
(21, 'Merchant', 160, 90, 18, 15, 'พ่อค้าผู้มีความสามารถในการแลกเปลี่ยน'),
(22, 'Knight', 500, 100, 40, 30, 'อัศวินผู้ปกป้องและต่อสู้แนวหน้า'),
(23, 'Priest', 300, 350, 20, 15, 'นักบวชผู้รักษาและชำระบาป'),
(24, 'Wizard', 250, 400, 50, 10, 'จอมเวทย์ผู้ควบคุมธาตุแห่งธรรมชาติ'),
(25, 'Assassin', 220, 120, 45, 15, 'นักฆ่าผู้ซ่อนเร้นในเงามืด'),
(26, 'Hunter', 200, 150, 38, 18, 'นักล่าผู้มีธนูและสัตว์เลี้ยงคู่ใจ'),
(27, 'Blacksmith', 300, 110, 35, 25, 'ช่างตีเหล็กผู้สร้างอาวุธชั้นยอด'),
(28, 'Crusader', 480, 120, 42, 35, 'นักรบศักดิ์สิทธิ์ผู้ปกป้องความยุติธรรม'),
(29, 'Monk', 320, 250, 50, 20, 'ผู้ฝึกฝนศิลปะต่อสู้และพลังจิต'),
(30, 'Sage', 270, 380, 28, 15, 'ปราชญ์ผู้ควบคุมเวทและความรู้ลึกซึ้ง'),
(31, 'Rogue', 200, 120, 33, 22, 'โจรผู้ใช้กลยุทธ์การต่อสู้เฉพาะตัว'),
(32, 'Bard', 260, 240, 30, 18, 'นักดนตรีผู้สร้างแรงบันดาลใจ'),
(33, 'Alchemist', 230, 260, 25, 17, 'นักปรุงยาผู้ชำนาญด้านเคมี'),
(34, 'Lord Knight', 700, 150, 60, 40, 'อัศวินระดับสูงที่เชี่ยวชาญศิลปะการต่อสู้');

-- --------------------------------------------------------

--
-- Table structure for table `class_advancements`
--

CREATE TABLE `class_advancements` (
  `adv_id` int(11) NOT NULL,
  `base_class_id` int(11) NOT NULL,
  `advanced_class_id` int(11) NOT NULL,
  `required_level` int(11) DEFAULT 50,
  `quest_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_advancements`
--

INSERT INTO `class_advancements` (`adv_id`, `base_class_id`, `advanced_class_id`, `required_level`, `quest_name`) VALUES
(1, 1, 6, 50, 'Warrior’s Trial'),
(2, 2, 7, 50, 'Mage’s Ascension'),
(3, 3, 8, 50, 'Priesthood Ceremony'),
(4, 4, 9, 50, 'Hunter’s Challenge'),
(5, 5, 10, 50, 'Assassin’s Path');

-- --------------------------------------------------------

--
-- Table structure for table `economy_logs`
--

CREATE TABLE `economy_logs` (
  `log_id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  `action` enum('earn','spend','trade') NOT NULL,
  `amount` int(11) NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `log_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `economy_logs`
--

INSERT INTO `economy_logs` (`log_id`, `player_id`, `action`, `amount`, `reason`, `log_date`) VALUES
(1, 1, 'earn', 500, 'Quest Reward', '2025-10-07 23:26:51'),
(2, 1, 'spend', 200, 'Buy Potion', '2025-10-07 23:26:51'),
(3, 2, 'trade', 1000, 'Market Sale', '2025-10-07 23:26:51'),
(4, 3, 'earn', 300, 'Monster Drop', '2025-10-07 23:26:51'),
(5, 3, 'spend', 150, 'Repair Equipment', '2025-10-07 23:26:51');

-- --------------------------------------------------------

--
-- Table structure for table `equipment`
--

CREATE TABLE `equipment` (
  `equip_id` int(11) NOT NULL,
  `char_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `slot` enum('head','body','weapon','shield','accessory') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `equipment`
--

INSERT INTO `equipment` (`equip_id`, `char_id`, `item_id`, `slot`) VALUES
(1, 1, 28, 'head'),
(2, 1, 20, 'body'),
(3, 1, 29, 'weapon'),
(4, 2, 9, 'head'),
(5, 2, 4, 'body'),
(6, 2, 20, 'weapon'),
(7, 3, 37, 'head'),
(8, 3, 54, 'body'),
(9, 3, 53, 'weapon'),
(10, 4, 40, 'head'),
(11, 4, 5, 'body'),
(12, 4, 9, 'weapon'),
(13, 5, 42, 'head'),
(14, 5, 52, 'body'),
(15, 5, 46, 'weapon'),
(16, 6, 2, 'head'),
(17, 6, 54, 'body'),
(18, 6, 43, 'weapon'),
(19, 7, 50, 'head'),
(20, 7, 20, 'body'),
(21, 7, 12, 'weapon'),
(22, 8, 28, 'head'),
(23, 8, 38, 'body'),
(24, 8, 1, 'weapon'),
(25, 9, 53, 'head'),
(26, 9, 33, 'body'),
(27, 9, 20, 'weapon'),
(28, 10, 50, 'head'),
(29, 10, 37, 'body'),
(30, 10, 15, 'weapon'),
(31, 11, 59, 'head'),
(32, 11, 7, 'body'),
(33, 11, 45, 'weapon'),
(34, 12, 34, 'head'),
(35, 12, 5, 'body'),
(36, 12, 45, 'weapon'),
(37, 13, 46, 'head'),
(38, 13, 43, 'body'),
(39, 13, 47, 'weapon'),
(40, 14, 2, 'head'),
(41, 14, 35, 'body'),
(42, 14, 41, 'weapon'),
(43, 15, 3, 'head'),
(44, 15, 54, 'body'),
(45, 15, 35, 'weapon'),
(46, 16, 34, 'head'),
(47, 16, 25, 'body'),
(48, 16, 2, 'weapon'),
(49, 17, 33, 'head'),
(50, 17, 5, 'body'),
(51, 17, 20, 'weapon'),
(52, 18, 59, 'head'),
(53, 18, 54, 'body'),
(54, 18, 8, 'weapon'),
(55, 19, 51, 'head'),
(56, 19, 32, 'body'),
(57, 19, 10, 'weapon'),
(58, 20, 3, 'head'),
(59, 20, 33, 'body'),
(60, 20, 46, 'weapon'),
(61, 21, 32, 'head'),
(62, 21, 56, 'body'),
(63, 21, 15, 'weapon'),
(64, 22, 60, 'head'),
(65, 22, 40, 'body'),
(66, 22, 41, 'weapon'),
(67, 23, 54, 'head'),
(68, 23, 15, 'body'),
(69, 23, 10, 'weapon'),
(70, 24, 5, 'head'),
(71, 24, 40, 'body'),
(72, 24, 3, 'weapon'),
(73, 25, 28, 'head'),
(74, 25, 16, 'body'),
(75, 25, 3, 'weapon'),
(76, 26, 16, 'head'),
(77, 26, 3, 'body'),
(78, 26, 30, 'weapon'),
(79, 27, 29, 'head'),
(80, 27, 43, 'body'),
(81, 27, 36, 'weapon'),
(82, 28, 46, 'head'),
(83, 28, 41, 'body'),
(84, 28, 25, 'weapon'),
(85, 29, 3, 'head'),
(86, 29, 56, 'body'),
(87, 29, 16, 'weapon'),
(88, 30, 28, 'head'),
(89, 30, 33, 'body'),
(90, 30, 4, 'weapon'),
(91, 31, 56, 'head'),
(92, 31, 32, 'body'),
(93, 31, 27, 'weapon'),
(94, 32, 9, 'head'),
(95, 32, 34, 'body'),
(96, 32, 38, 'weapon'),
(97, 33, 36, 'head'),
(98, 33, 39, 'body'),
(99, 33, 35, 'weapon'),
(100, 34, 32, 'head'),
(101, 34, 15, 'body'),
(102, 34, 42, 'weapon'),
(103, 35, 42, 'head'),
(104, 35, 34, 'body'),
(105, 35, 45, 'weapon'),
(106, 36, 27, 'head'),
(107, 36, 7, 'body'),
(108, 36, 38, 'weapon'),
(109, 37, 7, 'head'),
(110, 37, 38, 'body'),
(111, 37, 52, 'weapon'),
(112, 38, 15, 'head'),
(113, 38, 46, 'body'),
(114, 38, 28, 'weapon'),
(115, 39, 30, 'head'),
(116, 39, 8, 'body'),
(117, 39, 7, 'weapon'),
(118, 40, 30, 'head'),
(119, 40, 59, 'body'),
(120, 40, 28, 'weapon'),
(121, 41, 29, 'head'),
(122, 41, 39, 'body'),
(123, 41, 52, 'weapon'),
(124, 42, 38, 'head'),
(125, 42, 13, 'body'),
(126, 42, 39, 'weapon'),
(127, 43, 33, 'head'),
(128, 43, 39, 'body'),
(129, 43, 3, 'weapon'),
(130, 44, 33, 'head'),
(131, 44, 53, 'body'),
(132, 44, 38, 'weapon'),
(133, 45, 27, 'head'),
(134, 45, 54, 'body'),
(135, 45, 4, 'weapon'),
(136, 46, 5, 'head'),
(137, 46, 59, 'body'),
(138, 46, 54, 'weapon'),
(139, 47, 36, 'head'),
(140, 47, 34, 'body'),
(141, 47, 43, 'weapon'),
(142, 48, 60, 'head'),
(143, 48, 13, 'body'),
(144, 48, 2, 'weapon'),
(145, 49, 12, 'head'),
(146, 49, 52, 'body'),
(147, 49, 60, 'weapon'),
(148, 50, 39, 'head'),
(149, 50, 3, 'body'),
(150, 50, 10, 'weapon');

-- --------------------------------------------------------

--
-- Table structure for table `friendships`
--

CREATE TABLE `friendships` (
  `friend_id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  `friend_player_id` int(11) NOT NULL,
  `status` enum('pending','accepted','blocked') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `friendships`
--

INSERT INTO `friendships` (`friend_id`, `player_id`, `friend_player_id`, `status`, `created_at`) VALUES
(1, 1, 2, 'accepted', '2025-10-02 18:03:31'),
(2, 1, 3, 'pending', '2025-10-02 18:03:31'),
(3, 2, 4, 'accepted', '2025-10-02 18:03:31');

-- --------------------------------------------------------

--
-- Table structure for table `guilds`
--

CREATE TABLE `guilds` (
  `guild_id` int(11) NOT NULL,
  `guild_name` varchar(100) NOT NULL,
  `leader_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `creation_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guilds`
--

INSERT INTO `guilds` (`guild_id`, `guild_name`, `leader_id`, `description`, `creation_date`) VALUES
(1, 'Unit Guild', 18, NULL, '2025-10-07 23:30:03'),
(2, 'Them Guild', 3, NULL, '2025-10-07 23:30:03'),
(3, 'State Guild', 5, NULL, '2025-10-07 23:30:03'),
(4, 'Ok Guild', NULL, NULL, '2025-10-07 23:30:03'),
(5, 'Push Guild', 18, NULL, '2025-10-07 23:30:03'),
(6, 'People Guild', NULL, NULL, '2025-10-07 23:30:03'),
(7, 'Court Guild', NULL, NULL, '2025-10-07 23:30:03'),
(8, 'Special Guild', NULL, NULL, '2025-10-07 23:30:03'),
(9, 'Carry Guild', 3, NULL, '2025-10-07 23:30:03'),
(10, 'Lead Guild', 12, NULL, '2025-10-07 23:30:03'),
(11, 'Morning Guild', 21, NULL, '2025-10-07 23:30:03'),
(12, 'Whether Guild', 2, NULL, '2025-10-07 23:30:03'),
(13, 'Trouble Guild', 14, NULL, '2025-10-07 23:30:03'),
(14, 'Around Guild', NULL, NULL, '2025-10-07 23:30:03'),
(15, 'Theory Guild', 10, NULL, '2025-10-07 23:30:03'),
(16, 'Capital Guild', NULL, NULL, '2025-10-07 23:30:03'),
(17, 'National Guild', NULL, NULL, '2025-10-07 23:30:03'),
(18, 'Claim Guild', NULL, NULL, '2025-10-07 23:30:03'),
(19, 'Fly Guild', 5, NULL, '2025-10-07 23:30:03'),
(20, 'Community Guild', 20, NULL, '2025-10-07 23:30:03'),
(21, 'Eternal Flame', 1, 'กิลด์นักสู้แห่งไฟที่ไม่มอด', '2025-10-07 23:31:44'),
(22, 'Moonlight', 2, 'กิลด์ของเหล่านักเวทและมือธนู', '2025-10-07 23:31:44'),
(23, 'Aegis', 3, 'กิลด์ป้องกันแนวหน้าผู้ไม่ยอมแพ้', '2025-10-07 23:31:44'),
(24, 'Crimson Dawn', 4, 'กลุ่มนักรบผู้รุ่งโรจน์ในยามรุ่งอรุณ', '2025-10-07 23:31:44'),
(25, 'Shadow Order', 5, 'องค์กรลับผู้เชี่ยวชาญการลอบสังหาร', '2025-10-07 23:31:44'),
(26, 'Silver Wings', 6, 'กิลด์นักบวชและนักเวทย์ผู้สง่างาม', '2025-10-07 23:31:44'),
(27, 'NightFury', 7, 'นักล่าผู้ครอบครองพลังแห่งรัตติกาล', '2025-10-07 23:31:44'),
(28, 'Holy Crusade', 8, 'กิลด์ศักดิ์สิทธิ์ผู้พิทักษ์สันติภาพ', '2025-10-07 23:31:44'),
(29, 'DragonSoul', 9, 'นักรบผู้สืบทอดพลังแห่งมังกร', '2025-10-07 23:31:44'),
(30, 'FrostBorn', 10, 'กลุ่มผู้ใช้เวทน้ำแข็งจากแดนเหนือ', '2025-10-07 23:31:44');

-- --------------------------------------------------------

--
-- Table structure for table `guild_members`
--

CREATE TABLE `guild_members` (
  `guild_id` int(11) NOT NULL,
  `char_id` int(11) NOT NULL,
  `role` enum('Leader','Member','Officer') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guild_members`
--

INSERT INTO `guild_members` (`guild_id`, `char_id`, `role`) VALUES
(1, 9, 'Leader'),
(1, 11, 'Officer'),
(1, 16, 'Leader'),
(1, 22, 'Leader'),
(1, 25, 'Officer'),
(1, 35, 'Leader'),
(1, 37, 'Officer'),
(1, 44, 'Member'),
(1, 46, 'Member'),
(1, 49, 'Officer'),
(2, 2, 'Leader'),
(2, 5, 'Member'),
(2, 18, 'Officer'),
(2, 21, 'Member'),
(2, 22, 'Member'),
(2, 26, 'Member'),
(2, 39, 'Leader'),
(2, 40, 'Member'),
(2, 41, 'Officer'),
(2, 48, 'Officer'),
(3, 5, 'Member'),
(3, 6, 'Officer'),
(3, 10, 'Leader'),
(3, 16, 'Member'),
(3, 18, 'Leader'),
(3, 19, 'Officer'),
(3, 25, 'Officer'),
(3, 40, 'Leader'),
(3, 43, 'Member'),
(3, 48, 'Member'),
(4, 1, 'Member'),
(4, 6, 'Leader'),
(4, 7, 'Leader'),
(4, 9, 'Leader'),
(4, 12, 'Member'),
(4, 18, 'Member'),
(4, 20, 'Officer'),
(4, 24, 'Officer'),
(4, 28, 'Officer'),
(4, 29, 'Officer'),
(5, 1, 'Leader'),
(5, 6, 'Officer'),
(5, 19, 'Officer'),
(5, 21, 'Officer'),
(5, 25, 'Officer'),
(5, 27, 'Member'),
(5, 39, 'Officer'),
(5, 46, 'Leader'),
(5, 49, 'Leader'),
(5, 50, 'Leader'),
(6, 5, 'Leader'),
(6, 10, 'Member'),
(6, 11, 'Officer'),
(6, 18, 'Member'),
(6, 20, 'Member'),
(6, 27, 'Officer'),
(6, 28, 'Officer'),
(6, 32, 'Member'),
(6, 48, 'Member'),
(6, 50, 'Leader'),
(7, 1, 'Officer'),
(7, 7, 'Member'),
(7, 9, 'Member'),
(7, 13, 'Member'),
(7, 20, 'Officer'),
(7, 22, 'Member'),
(7, 26, 'Leader'),
(7, 30, 'Member'),
(7, 40, 'Member'),
(7, 50, 'Member'),
(8, 12, 'Member'),
(8, 13, 'Leader'),
(8, 19, 'Leader'),
(8, 21, 'Member'),
(8, 26, 'Member'),
(8, 31, 'Member'),
(8, 39, 'Member'),
(8, 41, 'Officer'),
(8, 45, 'Officer'),
(8, 46, 'Member'),
(9, 2, 'Leader'),
(9, 12, 'Member'),
(9, 13, 'Member'),
(9, 14, 'Member'),
(9, 29, 'Leader'),
(9, 30, 'Officer'),
(9, 35, 'Leader'),
(9, 36, 'Member'),
(9, 38, 'Officer'),
(9, 44, 'Officer'),
(10, 5, 'Officer'),
(10, 10, 'Officer'),
(10, 17, 'Officer'),
(10, 25, 'Member'),
(10, 28, 'Officer'),
(10, 29, 'Leader'),
(10, 31, 'Officer'),
(10, 39, 'Member'),
(10, 43, 'Member'),
(10, 46, 'Officer'),
(11, 6, 'Officer'),
(11, 7, 'Officer'),
(11, 11, 'Member'),
(11, 16, 'Officer'),
(11, 23, 'Member'),
(11, 35, 'Officer'),
(11, 39, 'Officer'),
(11, 41, 'Leader'),
(11, 43, 'Officer'),
(11, 44, 'Member'),
(12, 1, 'Member'),
(12, 7, 'Leader'),
(12, 15, 'Member'),
(12, 17, 'Member'),
(12, 33, 'Officer'),
(12, 34, 'Officer'),
(12, 36, 'Officer'),
(12, 37, 'Leader'),
(12, 38, 'Officer'),
(12, 50, 'Member'),
(13, 2, 'Member'),
(13, 6, 'Member'),
(13, 7, 'Leader'),
(13, 8, 'Member'),
(13, 10, 'Leader'),
(13, 19, 'Officer'),
(13, 23, 'Member'),
(13, 27, 'Member'),
(13, 31, 'Member'),
(13, 50, 'Member'),
(14, 2, 'Officer'),
(14, 3, 'Officer'),
(14, 5, 'Officer'),
(14, 8, 'Leader'),
(14, 20, 'Leader'),
(14, 21, 'Leader'),
(14, 22, 'Leader'),
(14, 29, 'Officer'),
(14, 45, 'Leader'),
(14, 47, 'Officer'),
(15, 12, 'Member'),
(15, 15, 'Officer'),
(15, 22, 'Officer'),
(15, 26, 'Officer'),
(15, 28, 'Member'),
(15, 30, 'Leader'),
(15, 36, 'Member'),
(15, 41, 'Leader'),
(15, 43, 'Officer'),
(15, 44, 'Member'),
(16, 1, 'Member'),
(16, 5, 'Member'),
(16, 7, 'Member'),
(16, 14, 'Leader'),
(16, 18, 'Leader'),
(16, 25, 'Leader'),
(16, 35, 'Officer'),
(16, 37, 'Officer'),
(16, 46, 'Member'),
(16, 47, 'Member'),
(17, 8, 'Officer'),
(17, 21, 'Officer'),
(17, 23, 'Leader'),
(17, 25, 'Officer'),
(17, 26, 'Member'),
(17, 38, 'Officer'),
(17, 39, 'Leader'),
(17, 40, 'Officer'),
(17, 41, 'Member'),
(17, 42, 'Officer'),
(18, 8, 'Officer'),
(18, 9, 'Member'),
(18, 10, 'Member'),
(18, 12, 'Member'),
(18, 16, 'Leader'),
(18, 20, 'Officer'),
(18, 21, 'Member'),
(18, 24, 'Leader'),
(18, 33, 'Officer'),
(18, 48, 'Member'),
(19, 9, 'Leader'),
(19, 11, 'Leader'),
(19, 12, 'Leader'),
(19, 19, 'Leader'),
(19, 32, 'Member'),
(19, 35, 'Member'),
(19, 36, 'Member'),
(19, 42, 'Officer'),
(19, 45, 'Member'),
(19, 47, 'Member'),
(20, 27, 'Leader'),
(20, 29, 'Leader'),
(20, 32, 'Leader'),
(20, 33, 'Leader'),
(20, 36, 'Leader'),
(20, 42, 'Officer'),
(20, 44, 'Member'),
(20, 45, 'Officer'),
(20, 46, 'Member'),
(20, 47, 'Leader');

-- --------------------------------------------------------

--
-- Table structure for table `inventories`
--

CREATE TABLE `inventories` (
  `inv_id` int(11) NOT NULL,
  `char_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventories`
--

INSERT INTO `inventories` (`inv_id`, `char_id`, `item_id`, `quantity`) VALUES
(1, 1, 14, 4),
(2, 1, 29, 12),
(3, 1, 59, 18),
(4, 1, 16, 12),
(5, 1, 55, 2),
(6, 1, 24, 13),
(7, 2, 18, 3),
(8, 2, 13, 7),
(9, 2, 8, 20),
(10, 2, 55, 1),
(11, 2, 53, 2),
(12, 2, 30, 11),
(13, 3, 16, 18),
(14, 3, 9, 7),
(15, 3, 51, 19),
(16, 3, 37, 7),
(17, 3, 14, 8),
(18, 3, 5, 11),
(19, 4, 50, 5),
(20, 4, 10, 5),
(21, 4, 51, 18),
(22, 4, 39, 9),
(23, 4, 1, 6),
(24, 4, 18, 4),
(25, 5, 43, 8),
(26, 5, 56, 19),
(27, 5, 2, 11),
(28, 5, 9, 1),
(29, 5, 1, 6),
(30, 5, 23, 9),
(31, 6, 4, 3),
(32, 6, 9, 16),
(33, 6, 48, 15),
(34, 6, 27, 12),
(35, 6, 34, 17),
(36, 6, 8, 19),
(37, 7, 7, 17),
(38, 7, 29, 10),
(39, 7, 33, 15),
(40, 7, 15, 1),
(41, 7, 40, 2),
(42, 7, 3, 16),
(43, 8, 55, 15),
(44, 8, 26, 3),
(45, 8, 28, 3),
(46, 8, 44, 11),
(47, 8, 7, 20),
(48, 8, 32, 5),
(49, 9, 5, 18),
(50, 9, 9, 11),
(51, 9, 18, 13),
(52, 9, 40, 20),
(53, 9, 41, 17),
(54, 9, 38, 10),
(55, 10, 30, 4),
(56, 10, 33, 18),
(57, 10, 39, 7),
(58, 10, 28, 14),
(59, 10, 7, 15),
(60, 10, 51, 8),
(61, 11, 27, 4),
(62, 11, 22, 11),
(63, 11, 53, 14),
(64, 11, 30, 11),
(65, 11, 26, 9),
(66, 11, 60, 12),
(67, 12, 10, 3),
(68, 12, 44, 3),
(69, 12, 31, 14),
(70, 12, 5, 4),
(71, 12, 6, 12),
(72, 12, 54, 5),
(73, 13, 36, 4),
(74, 13, 4, 14),
(75, 13, 38, 12),
(76, 13, 60, 14),
(77, 13, 57, 2),
(78, 13, 22, 10),
(79, 14, 39, 7),
(80, 14, 20, 5),
(81, 14, 23, 16),
(82, 14, 7, 8),
(83, 14, 37, 4),
(84, 14, 33, 12),
(85, 15, 55, 19),
(86, 15, 36, 8),
(87, 15, 24, 14),
(88, 15, 8, 18),
(89, 15, 49, 20),
(90, 15, 18, 20),
(91, 16, 44, 9),
(92, 16, 42, 1),
(93, 16, 36, 6),
(94, 16, 2, 9),
(95, 16, 39, 10),
(96, 16, 43, 11),
(97, 17, 23, 13),
(98, 17, 1, 3),
(99, 17, 12, 5),
(100, 17, 56, 1),
(101, 17, 10, 3),
(102, 17, 37, 17),
(103, 18, 14, 12),
(104, 18, 25, 10),
(105, 18, 27, 11),
(106, 18, 30, 19),
(107, 18, 22, 20),
(108, 18, 11, 3),
(109, 19, 57, 2),
(110, 19, 4, 3),
(111, 19, 10, 9),
(112, 19, 11, 15),
(113, 19, 49, 14),
(114, 19, 40, 16),
(115, 20, 39, 17),
(116, 20, 29, 4),
(117, 20, 27, 12),
(118, 20, 18, 14),
(119, 20, 14, 4),
(120, 20, 49, 10),
(121, 21, 44, 10),
(122, 21, 60, 2),
(123, 21, 38, 8),
(124, 21, 32, 13),
(125, 21, 34, 20),
(126, 21, 43, 2),
(127, 22, 1, 9),
(128, 22, 14, 10),
(129, 22, 20, 11),
(130, 22, 59, 4),
(131, 22, 50, 1),
(132, 22, 9, 16),
(133, 23, 48, 8),
(134, 23, 28, 17),
(135, 23, 12, 18),
(136, 23, 9, 12),
(137, 23, 25, 3),
(138, 23, 35, 13),
(139, 24, 56, 3),
(140, 24, 48, 11),
(141, 24, 3, 19),
(142, 24, 28, 14),
(143, 24, 2, 19),
(144, 24, 30, 13),
(145, 25, 46, 1),
(146, 25, 41, 11),
(147, 25, 27, 6),
(148, 25, 19, 20),
(149, 25, 8, 15),
(150, 25, 26, 12),
(151, 26, 6, 19),
(152, 26, 28, 13),
(153, 26, 55, 17),
(154, 26, 7, 3),
(155, 26, 16, 13),
(156, 26, 59, 10),
(157, 27, 48, 3),
(158, 27, 22, 17),
(159, 27, 15, 4),
(160, 27, 59, 17),
(161, 27, 50, 17),
(162, 27, 11, 7),
(163, 28, 58, 5),
(164, 28, 50, 8),
(165, 28, 23, 4),
(166, 28, 60, 5),
(167, 28, 47, 9),
(168, 28, 53, 7),
(169, 29, 12, 3),
(170, 29, 39, 6),
(171, 29, 10, 16),
(172, 29, 49, 15),
(173, 29, 57, 19),
(174, 29, 42, 19),
(175, 30, 29, 20),
(176, 30, 44, 11),
(177, 30, 57, 11),
(178, 30, 37, 5),
(179, 30, 42, 15),
(180, 30, 41, 3),
(181, 31, 31, 19),
(182, 31, 29, 2),
(183, 31, 41, 12),
(184, 31, 20, 17),
(185, 31, 51, 3),
(186, 31, 18, 10),
(187, 32, 30, 10),
(188, 32, 29, 3),
(189, 32, 3, 3),
(190, 32, 4, 20),
(191, 32, 24, 20),
(192, 32, 54, 17),
(193, 33, 25, 2),
(194, 33, 30, 15),
(195, 33, 38, 19),
(196, 33, 36, 7),
(197, 33, 51, 11),
(198, 33, 48, 20),
(199, 34, 31, 11),
(200, 34, 33, 3),
(201, 34, 10, 17),
(202, 34, 4, 6),
(203, 34, 29, 2),
(204, 34, 7, 8),
(205, 35, 46, 6),
(206, 35, 29, 12),
(207, 35, 59, 12),
(208, 35, 34, 10),
(209, 35, 57, 13),
(210, 35, 40, 14),
(211, 36, 50, 11),
(212, 36, 22, 3),
(213, 36, 44, 11),
(214, 36, 39, 4),
(215, 36, 4, 18),
(216, 36, 51, 13),
(217, 37, 19, 5),
(218, 37, 17, 11),
(219, 37, 47, 3),
(220, 37, 55, 19),
(221, 37, 43, 5),
(222, 37, 39, 12),
(223, 38, 20, 20),
(224, 38, 42, 3),
(225, 38, 45, 10),
(226, 38, 43, 18),
(227, 38, 26, 13),
(228, 38, 9, 11),
(229, 39, 53, 17),
(230, 39, 9, 3),
(231, 39, 43, 14),
(232, 39, 45, 17),
(233, 39, 54, 12),
(234, 39, 48, 1),
(235, 40, 24, 16),
(236, 40, 20, 7),
(237, 40, 12, 8),
(238, 40, 14, 5),
(239, 40, 22, 5),
(240, 40, 50, 3),
(241, 41, 19, 18),
(242, 41, 55, 17),
(243, 41, 51, 2),
(244, 41, 7, 11),
(245, 41, 33, 20),
(246, 41, 50, 5),
(247, 42, 39, 20),
(248, 42, 25, 6),
(249, 42, 10, 15),
(250, 42, 11, 2),
(251, 42, 12, 14),
(252, 42, 54, 12),
(253, 43, 44, 15),
(254, 43, 47, 8),
(255, 43, 16, 18),
(256, 43, 29, 8),
(257, 43, 40, 10),
(258, 43, 19, 16),
(259, 44, 58, 15),
(260, 44, 54, 15),
(261, 44, 13, 10),
(262, 44, 24, 13),
(263, 44, 44, 17),
(264, 44, 37, 17),
(265, 45, 27, 5),
(266, 45, 11, 9),
(267, 45, 53, 2),
(268, 45, 13, 16),
(269, 45, 52, 12),
(270, 45, 39, 18),
(271, 46, 60, 4),
(272, 46, 7, 10),
(273, 46, 46, 3),
(274, 46, 55, 6),
(275, 46, 34, 9),
(276, 46, 57, 15),
(277, 47, 58, 8),
(278, 47, 33, 15),
(279, 47, 10, 12),
(280, 47, 54, 1),
(281, 47, 28, 14),
(282, 47, 6, 2),
(283, 48, 26, 12),
(284, 48, 33, 8),
(285, 48, 24, 1),
(286, 48, 16, 11),
(287, 48, 25, 4),
(288, 48, 6, 11),
(289, 49, 51, 16),
(290, 49, 10, 5),
(291, 49, 9, 16),
(292, 49, 3, 15),
(293, 49, 19, 20),
(294, 49, 54, 1),
(295, 50, 58, 5),
(296, 50, 6, 18),
(297, 50, 2, 20),
(298, 50, 17, 17),
(299, 50, 14, 14),
(300, 50, 54, 4),
(301, 2, 1, 11);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `item_type` enum('weapon','armor','consumable','card','etc') NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `name`, `item_type`, `description`) VALUES
(1, 'Potion', 'weapon', 'Real position make society behavior.'),
(2, 'Hi-Potion', 'armor', 'Identify during professional hard network.'),
(3, 'Ether', 'etc', 'International second former reflect even edge.'),
(4, 'Sword of Dawn', 'weapon', 'Return firm sea week.'),
(5, 'Shield of Valor', 'weapon', 'Course school everybody operation.'),
(6, 'Jellopy', 'card', 'Others wonder strategy fast guess few remain.'),
(7, 'Apple', 'weapon', 'Ever window network recently.'),
(8, 'Fly Wing', 'etc', 'Point sell bill activity.'),
(9, 'Fluff', 'armor', 'Light key continue anything wait.'),
(10, 'Clover', 'armor', 'Box assume man officer rather charge specific.'),
(11, 'Worm Peeling', 'card', 'Be easy newspaper indicate other.'),
(12, 'Mushroom Spore', 'etc', 'Difficult mission late kind team wrong figure perform.'),
(13, 'Sticky Mucus', 'armor', 'Whether between several personal enough ball dream necessary.'),
(14, 'Knife', 'consumable', 'Able late order fact.'),
(15, 'Adventure Suit', 'etc', 'Explain executive teacher author do.'),
(16, 'Bow', 'etc', 'Skin person product value interesting.'),
(17, 'Leather Boots', 'card', 'Different chance enter central arrive society organization.'),
(18, 'Magic Robe', 'armor', 'Tv keep light fight I evening music.'),
(19, 'Iron Helm', 'etc', 'Ball always it focus economy before.'),
(20, 'Steel Sword', 'armor', 'Onto again share start office several compare.'),
(21, 'Silver Shield', 'consumable', 'Prepare trouble consider one play man before.'),
(22, 'Bronze Ring', 'card', 'Impact fly bit claim in many.'),
(23, 'Gemstone', 'consumable', 'Spend nearly lawyer fire follow wife.'),
(24, 'Antidote', 'card', 'Ten stay ability thank left approach.'),
(25, 'Revive', 'etc', 'Gun series personal service data near until.'),
(26, 'Teleport Scroll', 'card', 'Thing machine ahead picture son report.'),
(27, 'Herb', 'weapon', 'Nearly need behavior yeah tree.'),
(28, 'Elunium', 'armor', 'Water positive child usually factor relate indeed.'),
(29, 'Oridecon', 'armor', 'Woman during necessary himself two meet these.'),
(30, 'Crystal', 'weapon', 'Everybody so increase various.'),
(31, 'Pet Food', 'consumable', 'Environment able rise study oil process tend.'),
(32, 'Taming Rope', 'weapon', 'Mrs generation necessary myself lay focus country.'),
(33, 'Mysterious Card', 'etc', 'Occur do simply analysis seat.'),
(34, 'Hydra Card', 'etc', 'History professional star wonder manager already.'),
(35, 'Andre Card', 'armor', 'Whole forward beyond suddenly between treat address.'),
(36, 'Skeleton Card', 'etc', 'Improve pressure child light.'),
(37, 'Pupa Card', 'armor', 'Full realize power system system.'),
(38, 'Fabre Card', 'weapon', 'Here first responsibility service their along attention.'),
(39, 'Hornet Card', 'weapon', 'Range explain dinner bed within set region beyond.'),
(40, 'Thief Bug Card', 'weapon', 'Really tough animal someone.'),
(41, 'Holy Water', 'armor', 'Grow issue each include radio.'),
(42, 'Mana Potion', 'weapon', 'Color bad that people.'),
(43, 'Stamina Elixir', 'weapon', 'Marriage on discussion point least.'),
(44, 'Lucky Coin', 'consumable', 'Together let explain.'),
(45, 'Ancient Relic', 'weapon', 'Citizen kid generation onto police interesting economic.'),
(46, 'Map Fragment', 'etc', 'Current his low down occur.'),
(47, 'Explorer Hat', 'armor', 'Fast recognize against stop how account ten.'),
(48, 'Traveler Cloak', 'consumable', 'Treat seat strategy.'),
(49, 'Hunter Traps', 'card', 'Simply discover soon despite couple.'),
(50, 'Fishing Rod', 'armor', 'Question return process stuff pick.'),
(51, 'Treasure Key', 'etc', 'Position final kid often run bed far section.'),
(52, 'Nation', 'armor', 'Customer skill theory hand.'),
(53, 'Yourself', 'etc', 'Tree store either station loss southern second.'),
(54, 'Cut', 'etc', 'Sound life away senior difficult put.'),
(55, 'Bed', 'card', 'Whose source hand so add Mr.'),
(56, 'Effort', 'armor', 'Happy see energy herself police he push.'),
(57, 'Service', 'card', 'Agent this no trip determine as statement.'),
(58, 'Investment', 'card', 'Power bring animal also you.'),
(59, 'Middle', 'armor', 'Best thought career law.'),
(60, 'One', 'weapon', 'Industry score choice increase between majority impact.'),
(61, 'Potion', 'consumable', 'Restores 50 HP'),
(62, 'Hi-Potion', 'consumable', 'Restores 200 HP'),
(63, 'Ether', 'consumable', 'Restores 50 MP'),
(64, 'Sword of Dawn', 'weapon', 'Attack +15'),
(65, 'Shield of Valor', 'armor', 'Defense +10'),
(66, 'Magic Wand', 'weapon', 'Increase magic power'),
(67, 'Iron Armor', 'armor', 'Strong against physical attack'),
(68, 'Steel Helmet', 'armor', 'Basic head protection'),
(69, 'Elixir', 'consumable', 'Fully restores HP and MP'),
(70, 'Ruby Ring', '', 'Slightly boosts attack');

-- --------------------------------------------------------

--
-- Table structure for table `item_effects`
--

CREATE TABLE `item_effects` (
  `effect_id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `effect_type` varchar(50) DEFAULT NULL,
  `effect_value` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item_effects`
--

INSERT INTO `item_effects` (`effect_id`, `item_id`, `effect_type`, `effect_value`) VALUES
(1, 1, 'DEF', 121),
(2, 2, 'INT', 186),
(3, 3, 'DEX', 487),
(4, 4, 'ATK', 310),
(5, 5, 'HP', 348),
(6, 6, 'LUK', 486),
(7, 7, 'INT', 191),
(8, 8, 'LUK', 391),
(9, 9, 'DEF', 294),
(10, 10, 'HP', 272),
(11, 11, 'INT', 204),
(12, 12, 'INT', 333),
(13, 13, 'VIT', 128),
(14, 14, 'DEF', 14),
(15, 15, 'HP', 256),
(16, 16, 'DEX', 468),
(17, 17, 'DEF', 135),
(18, 18, 'VIT', 361),
(19, 19, 'LUK', 110),
(20, 20, 'VIT', 356),
(21, 21, 'LUK', 103),
(22, 22, 'DEF', 70),
(23, 23, 'DEF', 232),
(24, 24, 'HP', 461),
(25, 25, 'LUK', 45),
(26, 26, 'INT', 342),
(27, 27, 'INT', 364),
(28, 28, 'DEF', 282),
(29, 29, 'VIT', 457),
(30, 30, 'VIT', 436),
(31, 31, 'HP', 365),
(32, 32, 'HP', 407),
(33, 33, 'INT', 261),
(34, 34, 'MP', 63),
(35, 35, 'MP', 406),
(36, 36, 'HP', 122),
(37, 37, 'LUK', 14),
(38, 38, 'INT', 284),
(39, 39, 'INT', 240),
(40, 40, 'HP', 314);

-- --------------------------------------------------------

--
-- Table structure for table `level_requirements`
--

CREATE TABLE `level_requirements` (
  `level` int(11) NOT NULL,
  `required_exp` bigint(20) NOT NULL,
  `unlocks` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `market_transactions`
--

CREATE TABLE `market_transactions` (
  `transaction_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `price` int(11) NOT NULL,
  `transaction_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `market_transactions`
--

INSERT INTO `market_transactions` (`transaction_id`, `seller_id`, `buyer_id`, `item_id`, `quantity`, `price`, `transaction_date`) VALUES
(1, 1, 2, 4, 1, 1200, '2025-10-07 23:25:33'),
(2, 3, 5, 2, 3, 600, '2025-10-07 23:25:33'),
(3, 2, 1, 7, 2, 850, '2025-10-07 23:25:33'),
(4, 4, 6, 3, 5, 400, '2025-10-07 23:25:33'),
(5, 5, 3, 9, 1, 3000, '2025-10-07 23:25:33');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `sender` varchar(50) DEFAULT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`message_id`, `sender`, `receiver_id`, `message`, `created_at`) VALUES
(4, 'admin', NULL, 'Hello players!', '2025-10-08 00:07:19');

-- --------------------------------------------------------

--
-- Table structure for table `monsters`
--

CREATE TABLE `monsters` (
  `monster_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `level` int(11) DEFAULT 1,
  `element` varchar(50) DEFAULT 'Neutral',
  `hp` int(11) DEFAULT 100,
  `atk` int(11) DEFAULT 10,
  `def` int(11) DEFAULT 5,
  `exp_reward` int(11) DEFAULT 10
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `monsters`
--

INSERT INTO `monsters` (`monster_id`, `name`, `level`, `element`, `hp`, `atk`, `def`, `exp_reward`) VALUES
(1, 'Poring', 1, 'Water', 50, 5, 1, 5),
(2, 'Lunatic', 2, 'Earth', 70, 7, 2, 6),
(3, 'Poporing', 5, 'Poison', 120, 10, 3, 15),
(4, 'Hornet', 6, 'Wind', 150, 12, 4, 18),
(5, 'Fabre', 7, 'Earth', 180, 14, 4, 20),
(6, 'Wolf', 12, 'Neutral', 250, 25, 10, 45),
(7, 'Elder Willow', 15, 'Fire', 300, 35, 12, 55),
(8, 'Orc Warrior', 20, 'Earth', 500, 50, 25, 80),
(9, 'Munak', 25, 'Undead', 600, 60, 28, 95),
(10, 'Baphomet Jr.', 30, 'Dark', 900, 90, 35, 150);

-- --------------------------------------------------------

--
-- Table structure for table `monster_drops`
--

CREATE TABLE `monster_drops` (
  `drop_id` int(11) NOT NULL,
  `monster_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `drop_rate` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `monster_drops`
--

INSERT INTO `monster_drops` (`drop_id`, `monster_id`, `item_id`, `drop_rate`) VALUES
(1, 1, 1, 50.00),
(2, 1, 2, 10.00),
(3, 2, 1, 40.00),
(4, 3, 5, 15.00),
(5, 4, 7, 12.00),
(6, 5, 8, 10.00),
(7, 6, 10, 8.00),
(8, 7, 12, 6.00),
(9, 8, 15, 5.00),
(10, 9, 18, 2.00),
(11, 10, 20, 1.00);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notif_id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `message` text DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `sent_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notif_id`, `player_id`, `title`, `message`, `is_read`, `sent_at`) VALUES
(1, 1, '🎉 ยินดีต้อนรับ!', 'ขอบคุณที่เข้าร่วมเล่น Ragnarok Origin Classic!', 0, '2025-10-07 23:37:16'),
(2, 2, '🛒 ตลาดกลางเปิดแล้ว', 'ตอนนี้คุณสามารถซื้อขายไอเท็มใน Market ได้', 0, '2025-10-07 23:37:16'),
(3, 3, '⚔️ ประกาศ WoE', 'สงคราม Guild War จะเริ่มในวันศุกร์นี้!', 0, '2025-10-07 23:37:16'),
(4, 4, '🎁 ของรางวัลกิจกรรม', 'คุณได้รับรางวัลจากกิจกรรม Daily Login', 0, '2025-10-07 23:37:16'),
(5, 5, '💬 ข้อความจาก Admin', 'ขอบคุณที่ร่วมทดสอบระบบ Closed Beta!', 0, '2025-10-07 23:37:16');

-- --------------------------------------------------------

--
-- Table structure for table `pets`
--

CREATE TABLE `pets` (
  `pet_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `species` varchar(50) DEFAULT NULL,
  `level` int(11) DEFAULT 1,
  `happiness` int(11) DEFAULT 50
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pets`
--

INSERT INTO `pets` (`pet_id`, `owner_id`, `name`, `species`, `level`, `happiness`) VALUES
(1, 1, 'Poring', 'Slime', 3, 80),
(2, 2, 'Devi', 'Deviling', 5, 70),
(3, 3, 'Baphy', 'Mini Baphomet', 7, 90),
(4, 4, 'Luna', 'Lunatic', 4, 85),
(5, 5, 'Popo', 'Poporing', 2, 75);

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE `players` (
  `player_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `players`
--

INSERT INTO `players` (`player_id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'player1', 'player@test.com', '123456', '2025-10-07 15:17:21'),
(2, 'player2', 'user@example.com', '123456', '2025-10-07 15:17:21'),
(3, 'Ciel', 'ciel@example.com', '123456', '2025-10-07 16:23:13'),
(4, 'Rin', 'rin@example.com', '123456', '2025-10-07 16:23:13'),
(5, 'Yuki', 'yuki@example.com', '123456', '2025-10-07 16:23:13'),
(6, 'Kenji', 'kenji@example.com', '123456', '2025-10-07 16:23:13'),
(7, 'Maya', 'maya@example.com', '123456', '2025-10-07 16:23:13'),
(8, 'Haru', 'haru@example.com', '123456', '2025-10-07 16:23:13'),
(9, 'Luna', 'luna@example.com', '123456', '2025-10-07 16:23:13'),
(10, 'Kai', 'kai@example.com', '123456', '2025-10-07 16:23:13'),
(11, 'Sora', 'sora@example.com', '123456', '2025-10-07 16:23:13'),
(12, 'Rika', 'rika@example.com', '123456', '2025-10-07 16:23:13'),
(13, 'Zero', 'zero@example.com', '123456', '2025-10-07 16:23:13'),
(14, 'Neko', 'neko@example.com', '123456', '2025-10-07 16:23:13'),
(15, 'Arisa', 'arisa@example.com', '123456', '2025-10-07 16:23:13'),
(16, 'Tomo', 'tomo@example.com', '123456', '2025-10-07 16:23:13'),
(17, 'Yuto', 'yuto@example.com', '123456', '2025-10-07 16:23:13'),
(18, 'Airi', 'airi@example.com', '123456', '2025-10-07 16:23:13'),
(19, 'Rei', 'rei@example.com', '123456', '2025-10-07 16:23:13'),
(20, 'Yuna', 'yuna@example.com', '123456', '2025-10-07 16:23:13'),
(21, 'Ren', 'ren@example.com', '123456', '2025-10-07 16:23:13'),
(22, 'Suzu', 'suzu@example.com', '123456', '2025-10-07 16:23:13');

-- --------------------------------------------------------

--
-- Table structure for table `quests_progress`
--

CREATE TABLE `quests_progress` (
  `progress_id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  `quest_name` varchar(100) DEFAULT NULL,
  `status` enum('not_started','in_progress','completed') DEFAULT 'not_started',
  `progress_percent` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quests_progress`
--

INSERT INTO `quests_progress` (`progress_id`, `player_id`, `quest_name`, `status`, `progress_percent`) VALUES
(1, 1, 'Prologue: Awakening', 'completed', 100),
(2, 1, 'Forest Hunt', 'in_progress', 60),
(3, 1, 'Poring Extermination', 'completed', 100),
(4, 1, 'Goblin Cave', 'in_progress', 45),
(5, 1, 'Lost Necklace', 'not_started', 0),
(6, 1, 'Slay the Orc Chief', 'completed', 100),
(7, 1, 'Treasure of Prontera', 'in_progress', 70),
(8, 1, 'Final Trial', 'not_started', 0),
(9, 2, 'Prologue: Awakening', 'completed', 100),
(10, 2, 'Wolf Hunt', 'in_progress', 40),
(11, 2, 'Rescue the Merchant', 'completed', 100),
(12, 2, 'Mystic Woods', 'not_started', 0),
(13, 2, 'Ancient Relic', 'completed', 100),
(14, 2, 'Battle at Geffen', 'in_progress', 50),
(15, 2, 'Fallen Knight', 'in_progress', 65),
(16, 2, 'Dragon’s Nest', 'not_started', 0),
(17, 3, 'Prologue: Awakening', 'completed', 100),
(18, 3, 'Slime Cleaning', 'completed', 100),
(19, 3, 'Cursed Ruins', 'in_progress', 55),
(20, 3, 'Lost Memories', 'not_started', 0),
(21, 3, 'Bandit Ambush', 'completed', 100),
(22, 3, 'Moonlight Prayer', 'in_progress', 35),
(23, 3, 'Desert Mirage', 'completed', 100),
(24, 3, 'Guardian Spirit', 'not_started', 0),
(25, 4, 'Start of Journey', 'completed', 100),
(26, 4, 'Find Missing Pet', 'in_progress', 70),
(27, 4, 'Cursed Forest', 'in_progress', 20),
(28, 4, 'Ancient Tower', 'not_started', 0),
(29, 4, 'Path to Wizardry', 'completed', 100),
(30, 4, 'Undead Purge', 'completed', 100),
(31, 4, 'Forgotten Tomb', 'in_progress', 45),
(32, 4, 'Sword of Dawn', 'not_started', 0),
(33, 5, 'Prologue: Awakening', 'completed', 100),
(34, 5, 'Goblin Mine', 'in_progress', 60),
(35, 5, 'Hidden Village', 'completed', 100),
(36, 5, 'Demon’s Contract', 'not_started', 0),
(37, 5, 'Defend Prontera', 'completed', 100),
(38, 5, 'Battlefield Echo', 'in_progress', 50),
(39, 5, 'Seal of Odin', 'not_started', 0),
(40, 5, 'Test of Courage', 'completed', 100),
(41, 6, 'Lost in Morroc', 'completed', 100),
(42, 6, 'Guard the Caravan', 'in_progress', 50),
(43, 6, 'Shadow Assassin', 'not_started', 0),
(44, 6, 'Cave of Fire', 'completed', 100),
(45, 6, 'Artifact Recovery', 'in_progress', 65),
(46, 6, 'Siren’s Song', 'completed', 100),
(47, 6, 'Dark Horizon', 'not_started', 0),
(48, 6, 'Battle of Midgard', 'in_progress', 30),
(49, 7, 'Rebirth Trial', 'completed', 100),
(50, 7, 'Guild Invitation', 'in_progress', 40),
(51, 7, 'Moonlight Mystery', 'not_started', 0),
(52, 7, 'Training Ground', 'completed', 100),
(53, 7, 'Ancient Shrine', 'in_progress', 50),
(54, 7, 'Harpy Hunt', 'not_started', 0),
(55, 7, 'Magic Crystal', 'completed', 100),
(56, 7, 'Spirit Duel', 'in_progress', 25),
(57, 8, 'Royal Guard Test', 'completed', 100),
(58, 8, 'Lost Traveler', 'not_started', 0),
(59, 8, 'Storm Fortress', 'in_progress', 60),
(60, 8, 'Temple of Light', 'completed', 100),
(61, 8, 'Lava Pit', 'completed', 100),
(62, 8, 'Merchant Escort', 'in_progress', 55),
(63, 8, 'Crystal Plains', 'not_started', 0),
(64, 8, 'Ancient Tomb', 'completed', 100),
(65, 9, 'Hidden Passage', 'completed', 100),
(66, 9, 'Frost Cavern', 'in_progress', 45),
(67, 9, 'Goblin Ambush', 'not_started', 0),
(68, 9, 'Mystery of Rune', 'completed', 100),
(69, 9, 'Orc Invasion', 'in_progress', 50),
(70, 9, 'Bridge of Fate', 'not_started', 0),
(71, 9, 'Knight’s Honor', 'completed', 100),
(72, 9, 'Fallen Angel', 'in_progress', 60),
(73, 10, 'Training Quest', 'completed', 100),
(74, 10, 'Find the Blacksmith', 'in_progress', 70),
(75, 10, 'Frozen Plains', 'not_started', 0),
(76, 10, 'Underground Ruin', 'completed', 100),
(77, 10, 'Dark Spirit', 'in_progress', 35),
(78, 10, 'Blessing of Valkyrie', 'completed', 100),
(79, 10, 'Rogue Encounter', 'in_progress', 45),
(80, 10, 'Eternal Flame', 'not_started', 0),
(81, 11, 'Trial of the Sword', 'completed', 100),
(82, 11, 'Mystic Library', 'in_progress', 45),
(83, 11, 'Goblin’s Secret', 'completed', 100),
(84, 11, 'Echoes of the Past', 'not_started', 0),
(85, 11, 'Battle in the Mist', 'in_progress', 60),
(86, 11, 'Hidden Altar', 'completed', 100),
(87, 11, 'Silent Village', 'in_progress', 40),
(88, 11, 'Guardian’s Pact', 'not_started', 0),
(89, 12, 'First Light', 'completed', 100),
(90, 12, 'Witch of the East', 'in_progress', 55),
(91, 12, 'Treasure Hunt', 'completed', 100),
(92, 12, 'Dark Contract', 'not_started', 0),
(93, 12, 'Frozen Guardian', 'in_progress', 30),
(94, 12, 'Wind Temple', 'completed', 100),
(95, 12, 'Forgotten Melody', 'in_progress', 65),
(96, 12, 'Ancient Memory', 'not_started', 0),
(97, 13, 'Shattered Blade', 'completed', 100),
(98, 13, 'Crimson Tower', 'in_progress', 70),
(99, 13, 'Merchant’s Favor', 'completed', 100),
(100, 13, 'Shadow in the Woods', 'not_started', 0),
(101, 13, 'Frozen Dreams', 'completed', 100),
(102, 13, 'Dragon Soul', 'in_progress', 40),
(103, 13, 'Lunar Blessing', 'not_started', 0),
(104, 13, 'Bandit Hideout', 'completed', 100),
(105, 14, 'Trial of Courage', 'completed', 100),
(106, 14, 'Haunted Mines', 'in_progress', 35),
(107, 14, 'Wind Rider', 'completed', 100),
(108, 14, 'Crystal Mirage', 'not_started', 0),
(109, 14, 'Forest Guardian', 'in_progress', 50),
(110, 14, 'Ancient Heirloom', 'completed', 100),
(111, 14, 'Darkness Rising', 'not_started', 0),
(112, 14, 'Mystic Waterfall', 'in_progress', 60),
(113, 15, 'Rookie Training', 'completed', 100),
(114, 15, 'Shadows of Payon', 'in_progress', 40),
(115, 15, 'Castle Defense', 'completed', 100),
(116, 15, 'Runestone Mystery', 'not_started', 0),
(117, 15, 'Valkyrie’s Blessing', 'in_progress', 70),
(118, 15, 'Frozen Tears', 'completed', 100),
(119, 15, 'Ancient Whisper', 'in_progress', 55),
(120, 15, 'Sword of Time', 'not_started', 0),
(121, 16, 'Secret of Glast Heim', 'completed', 100),
(122, 16, 'Forest Ruins', 'in_progress', 60),
(123, 16, 'Pirate’s Revenge', 'completed', 100),
(124, 16, 'Goblin’s Gold', 'not_started', 0),
(125, 16, 'Light of Faith', 'completed', 100),
(126, 16, 'Phantom Cavern', 'in_progress', 40),
(127, 16, 'Rune Barrier', 'in_progress', 35),
(128, 16, 'Spirit’s Rest', 'not_started', 0),
(129, 17, 'Knight’s Oath', 'completed', 100),
(130, 17, 'Broken Crown', 'in_progress', 65),
(131, 17, 'Sandstorm Arena', 'completed', 100),
(132, 17, 'Wandering Bard', 'not_started', 0),
(133, 17, 'Ancient Curse', 'in_progress', 45),
(134, 17, 'Light of Hope', 'completed', 100),
(135, 17, 'Frozen Shadow', 'in_progress', 20),
(136, 17, 'Path of Valor', 'not_started', 0),
(137, 18, 'Lost Artifact', 'completed', 100),
(138, 18, 'Knight’s Duel', 'in_progress', 50),
(139, 18, 'Hidden Shrine', 'completed', 100),
(140, 18, 'Forest of Echoes', 'not_started', 0),
(141, 18, 'Soul of the Sea', 'in_progress', 60),
(142, 18, 'Eternal Flame', 'completed', 100),
(143, 18, 'Temple Guardian', 'in_progress', 30),
(144, 18, 'Mystic Wind', 'not_started', 0),
(145, 19, 'Prologue of Dawn', 'completed', 100),
(146, 19, 'Blacksmith’s Legacy', 'in_progress', 55),
(147, 19, 'Battle at the Bridge', 'completed', 100),
(148, 19, 'Whispering Forest', 'not_started', 0),
(149, 19, 'Ancient Relic', 'in_progress', 40),
(150, 19, 'Moonlight Blessing', 'completed', 100),
(151, 19, 'Dragon’s Wrath', 'in_progress', 65),
(152, 19, 'Crystal Heart', 'not_started', 0),
(153, 20, 'Path of the Priest', 'completed', 100),
(154, 20, 'Tower of Trials', 'in_progress', 35),
(155, 20, 'Holy Relic', 'completed', 100),
(156, 20, 'Bandit Chase', 'not_started', 0),
(157, 20, 'Sea of Silence', 'completed', 100),
(158, 20, 'Sacred Stone', 'in_progress', 55),
(159, 20, 'Magic Academy', 'not_started', 0),
(160, 20, 'Knight Tournament', 'completed', 100),
(161, 21, 'Guardian’s Light', 'completed', 100),
(162, 21, 'Eclipse Cavern', 'in_progress', 45),
(163, 21, 'Crystal Spire', 'completed', 100),
(164, 21, 'Black Rose', 'not_started', 0),
(165, 21, 'Trial of Destiny', 'in_progress', 65),
(166, 21, 'Wind of Eternity', 'completed', 100),
(167, 21, 'Flame Canyon', 'not_started', 0),
(168, 21, 'Rogue’s Path', 'completed', 100),
(169, 22, 'Awakening of Magic', 'completed', 100),
(170, 22, 'Frostbound Path', 'in_progress', 60),
(171, 22, 'Soul Contract', 'completed', 100),
(172, 22, 'Goblin Rebellion', 'not_started', 0),
(173, 22, 'Desert Mirage', 'completed', 100),
(174, 22, 'Lunar Gate', 'in_progress', 45),
(175, 22, 'Battle of Shadows', 'not_started', 0),
(176, 22, 'Legacy of Heroes', 'completed', 100);

-- --------------------------------------------------------

--
-- Table structure for table `ranking_board`
--

CREATE TABLE `ranking_board` (
  `rank_id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  `rank_type` enum('level','exp','pvp','guild') DEFAULT 'level',
  `score` int(11) DEFAULT 0,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ranking_board`
--

INSERT INTO `ranking_board` (`rank_id`, `player_id`, `rank_type`, `score`, `updated_at`) VALUES
(1, 1, 'level', 58, '2025-10-07 23:27:07'),
(2, 2, 'exp', 128000, '2025-10-07 23:27:07'),
(3, 3, 'pvp', 200, '2025-10-07 23:27:07'),
(4, 4, 'guild', 180, '2025-10-07 23:27:07'),
(5, 5, 'level', 65, '2025-10-07 23:27:07');

-- --------------------------------------------------------

--
-- Table structure for table `refine_logs`
--

CREATE TABLE `refine_logs` (
  `refine_id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `success` tinyint(1) DEFAULT 0,
  `refine_level` int(11) DEFAULT 1,
  `refine_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `skill_id` int(11) NOT NULL,
  `skill_name` varchar(100) NOT NULL,
  `class_id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `mp_cost` int(11) DEFAULT 0,
  `cooldown` float DEFAULT 0,
  `power` int(11) DEFAULT 10
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`skill_id`, `skill_name`, `class_id`, `description`, `mp_cost`, `cooldown`, `power`) VALUES
(1, 'Fire Bolt', 2, 'ยิงลูกไฟสร้างความเสียหายเวทไฟ', 8, 1.5, 35),
(2, 'Heal', 3, 'ฟื้นฟูพลังชีวิตให้เพื่อนร่วมทีม', 10, 2, 0),
(3, 'Double Attack', 1, 'โจมตีสองครั้งด้วยอาวุธหลัก', 5, 1.2, 25),
(4, 'Bash', 1, 'ฟาดด้วยอาวุธสร้างความเสียหายกายภาพสูง', 6, 1.5, 40),
(5, 'Frost Diver', 2, 'สร้างความเสียหายเวทน้ำและแช่แข็งเป้าหมาย', 12, 2.5, 45),
(6, 'Magnificat', 3, 'เพิ่มอัตราฟื้นฟู MP ของทีมชั่วคราว', 15, 8, 0),
(7, 'Arrow Shower', 4, 'ยิงลูกศรฝนตกโจมตีศัตรูเป็นวงกว้าง', 8, 2, 30),
(8, 'Envenom', 5, 'โจมตีด้วยพิษ เพิ่มสถานะ Poison', 5, 2, 20),
(9, 'Sonic Blow', 5, 'โจมตีศัตรูอย่างรวดเร็วด้วยอาวุธคู่', 12, 3, 50),
(10, 'Lord of Vermillion', 2, 'เวทสายฟ้าระดับสูง สร้างความเสียหายรอบตัว', 20, 6, 90);

-- --------------------------------------------------------

--
-- Table structure for table `woe_results`
--

CREATE TABLE `woe_results` (
  `woe_id` int(11) NOT NULL,
  `guild_id` int(11) NOT NULL,
  `castle_name` varchar(100) DEFAULT NULL,
  `result` enum('win','lose','draw') DEFAULT 'draw',
  `war_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `woe_results`
--

INSERT INTO `woe_results` (`woe_id`, `guild_id`, `castle_name`, `result`, `war_date`) VALUES
(1, 1, 'Prontera Castle', 'win', '2025-10-07 23:27:02'),
(2, 2, 'Payon Fortress', 'lose', '2025-10-07 23:27:02'),
(3, 3, 'Geffen Tower', 'win', '2025-10-07 23:27:02'),
(4, 4, 'Aldebaran Stronghold', 'draw', '2025-10-07 23:27:02');

-- --------------------------------------------------------

--
-- Table structure for table `world_boss_events`
--

CREATE TABLE `world_boss_events` (
  `event_id` int(11) NOT NULL,
  `boss_name` varchar(100) NOT NULL,
  `spawn_time` datetime NOT NULL,
  `defeated_by` int(11) DEFAULT NULL,
  `reward_item` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `world_boss_events`
--

INSERT INTO `world_boss_events` (`event_id`, `boss_name`, `spawn_time`, `defeated_by`, `reward_item`) VALUES
(1, 'Baphomet', '2025-10-07 23:27:12', 1, 4),
(2, 'Drake', '2025-10-07 23:27:12', 3, 9),
(3, 'Golden Thief Bug', '2025-10-07 23:27:12', 2, 5),
(4, 'Eddga', '2025-10-07 23:27:12', 4, 7),
(5, 'Stormy Knight', '2025-10-07 23:27:12', 5, 8);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `bans`
--
ALTER TABLE `bans`
  ADD PRIMARY KEY (`ban_id`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`class_id`);

--
-- Indexes for table `class_advancements`
--
ALTER TABLE `class_advancements`
  ADD PRIMARY KEY (`adv_id`),
  ADD KEY `base_class_id` (`base_class_id`),
  ADD KEY `advanced_class_id` (`advanced_class_id`);

--
-- Indexes for table `economy_logs`
--
ALTER TABLE `economy_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `player_id` (`player_id`);

--
-- Indexes for table `guilds`
--
ALTER TABLE `guilds`
  ADD PRIMARY KEY (`guild_id`),
  ADD KEY `fk_guild_leader` (`leader_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `market_transactions`
--
ALTER TABLE `market_transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `idx_seller` (`seller_id`),
  ADD KEY `idx_buyer` (`buyer_id`),
  ADD KEY `idx_item` (`item_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `fk_messages_receiver` (`receiver_id`);

--
-- Indexes for table `monsters`
--
ALTER TABLE `monsters`
  ADD PRIMARY KEY (`monster_id`);

--
-- Indexes for table `monster_drops`
--
ALTER TABLE `monster_drops`
  ADD PRIMARY KEY (`drop_id`),
  ADD KEY `monster_id` (`monster_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notif_id`),
  ADD KEY `player_id` (`player_id`);

--
-- Indexes for table `pets`
--
ALTER TABLE `pets`
  ADD PRIMARY KEY (`pet_id`),
  ADD KEY `owner_id` (`owner_id`);

--
-- Indexes for table `players`
--
ALTER TABLE `players`
  ADD PRIMARY KEY (`player_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `quests_progress`
--
ALTER TABLE `quests_progress`
  ADD PRIMARY KEY (`progress_id`),
  ADD KEY `player_id` (`player_id`);

--
-- Indexes for table `ranking_board`
--
ALTER TABLE `ranking_board`
  ADD PRIMARY KEY (`rank_id`),
  ADD KEY `player_id` (`player_id`);

--
-- Indexes for table `refine_logs`
--
ALTER TABLE `refine_logs`
  ADD PRIMARY KEY (`refine_id`),
  ADD KEY `player_id` (`player_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`skill_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `woe_results`
--
ALTER TABLE `woe_results`
  ADD PRIMARY KEY (`woe_id`),
  ADD KEY `guild_id` (`guild_id`);

--
-- Indexes for table `world_boss_events`
--
ALTER TABLE `world_boss_events`
  ADD PRIMARY KEY (`event_id`),
  ADD KEY `defeated_by` (`defeated_by`),
  ADD KEY `reward_item` (`reward_item`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bans`
--
ALTER TABLE `bans`
  MODIFY `ban_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `class_advancements`
--
ALTER TABLE `class_advancements`
  MODIFY `adv_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `economy_logs`
--
ALTER TABLE `economy_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `guilds`
--
ALTER TABLE `guilds`
  MODIFY `guild_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `market_transactions`
--
ALTER TABLE `market_transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `monsters`
--
ALTER TABLE `monsters`
  MODIFY `monster_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `monster_drops`
--
ALTER TABLE `monster_drops`
  MODIFY `drop_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notif_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pets`
--
ALTER TABLE `pets`
  MODIFY `pet_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `players`
--
ALTER TABLE `players`
  MODIFY `player_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `quests_progress`
--
ALTER TABLE `quests_progress`
  MODIFY `progress_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=177;

--
-- AUTO_INCREMENT for table `ranking_board`
--
ALTER TABLE `ranking_board`
  MODIFY `rank_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `refine_logs`
--
ALTER TABLE `refine_logs`
  MODIFY `refine_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `skill_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `woe_results`
--
ALTER TABLE `woe_results`
  MODIFY `woe_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `world_boss_events`
--
ALTER TABLE `world_boss_events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `class_advancements`
--
ALTER TABLE `class_advancements`
  ADD CONSTRAINT `class_advancements_ibfk_1` FOREIGN KEY (`base_class_id`) REFERENCES `classes` (`class_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_advancements_ibfk_2` FOREIGN KEY (`advanced_class_id`) REFERENCES `classes` (`class_id`) ON DELETE CASCADE;

--
-- Constraints for table `economy_logs`
--
ALTER TABLE `economy_logs`
  ADD CONSTRAINT `economy_logs_ibfk_1` FOREIGN KEY (`player_id`) REFERENCES `players` (`player_id`);

--
-- Constraints for table `guilds`
--
ALTER TABLE `guilds`
  ADD CONSTRAINT `fk_guild_leader` FOREIGN KEY (`leader_id`) REFERENCES `players` (`player_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `market_transactions`
--
ALTER TABLE `market_transactions`
  ADD CONSTRAINT `fk_market_buyer` FOREIGN KEY (`buyer_id`) REFERENCES `players` (`player_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_market_item` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_market_seller` FOREIGN KEY (`seller_id`) REFERENCES `players` (`player_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `fk_messages_receiver` FOREIGN KEY (`receiver_id`) REFERENCES `players` (`player_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `monster_drops`
--
ALTER TABLE `monster_drops`
  ADD CONSTRAINT `monster_drops_ibfk_1` FOREIGN KEY (`monster_id`) REFERENCES `monsters` (`monster_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `monster_drops_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`player_id`) REFERENCES `players` (`player_id`) ON DELETE CASCADE;

--
-- Constraints for table `pets`
--
ALTER TABLE `pets`
  ADD CONSTRAINT `pets_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `players` (`player_id`) ON DELETE CASCADE;

--
-- Constraints for table `quests_progress`
--
ALTER TABLE `quests_progress`
  ADD CONSTRAINT `quests_progress_ibfk_1` FOREIGN KEY (`player_id`) REFERENCES `players` (`player_id`);

--
-- Constraints for table `ranking_board`
--
ALTER TABLE `ranking_board`
  ADD CONSTRAINT `ranking_board_ibfk_1` FOREIGN KEY (`player_id`) REFERENCES `players` (`player_id`);

--
-- Constraints for table `refine_logs`
--
ALTER TABLE `refine_logs`
  ADD CONSTRAINT `refine_logs_ibfk_1` FOREIGN KEY (`player_id`) REFERENCES `players` (`player_id`),
  ADD CONSTRAINT `refine_logs_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`);

--
-- Constraints for table `skills`
--
ALTER TABLE `skills`
  ADD CONSTRAINT `skills_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`) ON DELETE CASCADE;

--
-- Constraints for table `woe_results`
--
ALTER TABLE `woe_results`
  ADD CONSTRAINT `woe_results_ibfk_1` FOREIGN KEY (`guild_id`) REFERENCES `guilds` (`guild_id`);

--
-- Constraints for table `world_boss_events`
--
ALTER TABLE `world_boss_events`
  ADD CONSTRAINT `world_boss_events_ibfk_1` FOREIGN KEY (`defeated_by`) REFERENCES `players` (`player_id`),
  ADD CONSTRAINT `world_boss_events_ibfk_2` FOREIGN KEY (`reward_item`) REFERENCES `items` (`item_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
