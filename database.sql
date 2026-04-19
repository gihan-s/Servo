-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 19, 2026 at 07:16 AM
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
-- Database: `servo`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `User_ID` int(11) NOT NULL,
  `Username` varchar(45) DEFAULT NULL,
  `Password` varchar(512) DEFAULT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Access_Level` varchar(45) DEFAULT NULL,
  `Status` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`User_ID`, `Username`, `Password`, `Name`, `Access_Level`, `Status`) VALUES
(1, 'gihan', '$2a$12$v6hOJEI9GsipSzziyvebzejCRlXob4qVU7TlJk3TnF/mEBCKU/pbO', 'Gihan', 'Admin', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `bids`
--

CREATE TABLE `bids` (
  `Bid_ID` int(11) NOT NULL,
  `Comment` varchar(256) DEFAULT NULL,
  `Amount` double DEFAULT NULL,
  `Created_At` datetime DEFAULT NULL,
  `Duration` smallint(6) DEFAULT NULL,
  `Status` varchar(45) DEFAULT NULL,
  `Post_ID` int(11) NOT NULL,
  `Provider_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `Category_ID` int(11) NOT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Description` varchar(100) DEFAULT NULL,
  `Icon` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`Category_ID`, `Name`, `Description`, `Icon`) VALUES
(1, 'Software Developing', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `Client_ID` int(11) NOT NULL,
  `Email` varchar(256) DEFAULT NULL,
  `Contact_No` varchar(45) DEFAULT NULL,
  `Password` varchar(512) DEFAULT NULL,
  `Created_At` datetime DEFAULT NULL,
  `First_Name` varchar(100) DEFAULT NULL,
  `Last_Name` varchar(100) DEFAULT NULL,
  `Gender` varchar(45) DEFAULT NULL,
  `Profile_Picture` varchar(512) DEFAULT NULL,
  `Social_Link` varchar(512) DEFAULT NULL,
  `Bio` varchar(1024) DEFAULT NULL,
  `Status` varchar(45) DEFAULT NULL,
  `Is_Online` tinyint(1) DEFAULT 0,
  `Last_Seen` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`Client_ID`, `Email`, `Contact_No`, `Password`, `Created_At`, `First_Name`, `Last_Name`, `Gender`, `Profile_Picture`, `Social_Link`, `Bio`, `Status`, `Is_Online`, `Last_Seen`) VALUES
(1, 'himath@client.com', '0785902834', '$2y$10$a/azB8rXxJOirhVnxilPL.LdIykYOHSIO3AmKNLPJD6SmfIS/RHM2', '2026-04-05 00:07:41', 'Himath', 'Adithya', 'Male', NULL, '', '', 'Active', 0, '2026-04-12 01:29:13'),
(2, 'senesh.voxxsys@gmail.com', '0345435345', '$2y$10$QZNBUZ0QsK4cy43wqgobD.D1GPFX/DWyelnDvaHfWmHBfJ.8AUCda', '2026-04-05 03:25:00', 'Senesh', 'Anujaya', 'Male', NULL, '', 'this is test bio', 'Active', 0, '2026-04-05 03:55:14'),
(3, 'senarathna3035@gmail.com', '0785522369', '$2y$10$6imefaXg79zkFzrtX5zCPudjSBdthlzwFB1ZXpqZmCHPEd5xm.lqq', '2026-04-05 03:58:25', 'Hirusha', 'Randika', 'Male', '69d1906c22b873.40512976_360_F_431647519_usrbQ8Z983hTYe8zgA7t1XVc5fEtqcpa.jpg', '', '', 'Active', 0, NULL),
(4, 'maximusshehan@gmail.com', '0748589639', '$2y$10$6imefaXg79zkFzrtX5zCPudjSBdthlzwFB1ZXpqZmCHPEd5xm.lqq', '2026-04-05 04:05:25', 'Charith', 'Shehan', 'Male', '69d19227cc9790.93824262_fajne-zdjecia-profilowe-19.webp', '', '', 'Active', 0, NULL),
(5, 'chinthakaprasad@gmail.com', '0123455667', '$2y$10$wvRmpaVUDl5fjuowuj0BXuiPTibRglFWPYT73cZ6zM9YbEC6cB7lW', '2026-04-05 04:33:40', 'chinthaka ', 'prasad', 'Prefer not to say', NULL, '', '', 'Active', 0, NULL),
(6, 'kdn@gmail.com', '0777111202', '$2y$10$CIbAK9IWoisXFUWYF9QIVegs56PxteOi6f162gJKKd8c5N5lZMQKq', '2026-04-05 04:35:49', 'Kalindu', 'Dilshan', 'Male', '5035db92b764b2599b3d4139c47c0805.jpg', '', '', 'Active', 0, '2026-04-05 04:40:57'),
(7, 'binara@gmail.com', '0123456789', '$2y$10$CIbAK9IWoisXFUWYF9QIVegs56PxteOi6f162gJKKd8c5N5lZMQKq', '2026-04-05 04:36:31', 'binara', 'kaveen', 'Male', '1f75fb490870f35beeea9faabc6403cf.jpg', '', '', 'Active', 0, '2026-04-05 04:54:47'),
(8, 'vccchethiyabandara2@gmail.com', '0782332537', '$2y$10$IXrkc98QQ0Hs5GNGw2mTZeEnJlIzpxm/HjgIGIf22a29LMR4aEwP2', '2026-04-05 04:46:33', 'P.G.Chethiya', 'Bandara', 'Male', '09a6ff9f17006bd0c41a2ae0938c775c.png', 'modapasindu.lk', 'Moda pasindu', 'Active', 0, '2026-04-16 13:33:42'),
(9, 'ashinshanasadali@gmail.com', '0701231232', '$2y$10$A4dLjvhXN3ZQka2J/2RDcOH1I0DYw5LpHUOhgD2lz3Prd1mTJyCTm', '2026-04-05 18:10:56', 'Ahinshana', 'sadali', 'Female', '4ebbe8294b393bed225fe8bb91f39705.bmp', '', '', 'Active', 0, '2026-04-16 00:11:08'),
(10, 'senarathne3035@gmail.com', '0777296709', '$2y$10$H5gFoEwpPOcBmZ6F9P.EU.vA2ezYYUt8YW9bOIBYmGi7rgiqebu0K', '2026-04-14 01:19:09', 'Hirusha', 'Senarathna', 'Male', NULL, '', '', 'Active', 0, '2026-04-14 12:52:50'),
(11, 'harendri.dh@gmail.com', '0764164601', '$2y$10$3yTBbU7waauI5nFNulI.QO/icQOql/aAgjLSv6iVz7KWjbIZikxpq', '2026-04-14 01:28:44', 'Dinaly', 'Harendri', 'Female', NULL, '', '', 'Active', 0, '2026-04-14 02:18:32'),
(12, 'ravindu@gmail.com', '0718689629', '$2y$10$kXDv5Ykg3hxJfHSKlp7bHuL7H1w4QKB8SPkIH5njwro2xLLf9RFiq', '2026-04-14 01:37:48', 'Ravindu', 'Gimhan', 'Male', '8fa98594686bc535853e3fe8c9c23505.png', '', '', 'Active', 0, '2026-04-14 06:50:46'),
(13, 'senuthdilwan77@gmail.com', '0767584954', '$2y$10$DaXh9C4UQ3wOxgd3e02dUuUV5iBs2i7zM5cE.Nfv0UCuWh2iR0WBu', '2026-04-15 00:05:19', 'Senuth', 'Dilwan', 'Female', 'c2797a8c24223b9fa6353f93d6df9346.jpg', 'Magepuka.lk', 'Senuth Dilwan kiyanne Kariyek thama ithin gamema inna', 'Active', 0, '2026-04-15 07:04:41'),
(14, 'akila2001a@gmail.com', '0779915159', '$2y$10$ITUh8X.dS9pRQKzOgz85ZeN4LFes0/Oe7xfMBCbZLQ4icy7L42d52', '2026-04-18 15:50:55', 'Akila', 'Abeysundara', 'Male', NULL, '', 'I am a web Developer with 5 years experience in the industry.', 'Active', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `conversation`
--

CREATE TABLE `conversation` (
  `ID` int(11) NOT NULL,
  `Provider_ID` int(11) NOT NULL,
  `Client_ID` int(11) NOT NULL,
  `Is_Starred_By_Client` int(11) NOT NULL,
  `Is_Starred_By_Provider` int(11) NOT NULL,
  `Is_Archived_By_Client` int(11) NOT NULL,
  `Is_Archived_By_Provider` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `conversation`
--

INSERT INTO `conversation` (`ID`, `Provider_ID`, `Client_ID`, `Is_Starred_By_Client`, `Is_Starred_By_Provider`, `Is_Archived_By_Client`, `Is_Archived_By_Provider`) VALUES
(1, 1, 2, 0, 0, 0, 0),
(9, 1, 7, 0, 0, 0, 0),
(14, 1, 8, 0, 0, 0, 0),
(35, 1, 9, 0, 0, 0, 0),
(110, 1, 10, 0, 0, 0, 0),
(156, 1, 11, 0, 0, 0, 0),
(191, 1, 12, 0, 0, 0, 0),
(662, 1, 13, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `districts`
--

CREATE TABLE `districts` (
  `ID` int(11) NOT NULL,
  `District` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `form_structure`
--

CREATE TABLE `form_structure` (
  `Structure_ID` int(11) NOT NULL,
  `Input_ID` int(11) NOT NULL,
  `Category_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `Location_ID` int(11) NOT NULL,
  `District_ID` int(11) DEFAULT NULL,
  `City` varchar(45) DEFAULT NULL,
  `District` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`Location_ID`, `District_ID`, `City`, `District`) VALUES
(1, NULL, 'All', 'All'),
(2, NULL, 'All', 'Gampaha'),
(3, NULL, 'Mirigama', 'Gampaha'),
(4, NULL, 'Veyangoda', 'Gampaha'),
(5, NULL, 'Gampaha Town', 'Gampaha'),
(6, NULL, 'All', 'Colombo'),
(7, NULL, 'Rajagiriya', 'Colombo'),
(8, NULL, 'Colombo 01', 'Colombo'),
(9, NULL, 'Colombo 02', 'Colombo'),
(10, NULL, 'Colombo 03', 'Colombo'),
(11, NULL, 'Colombo 04', 'Colombo');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `Message_ID` int(11) NOT NULL,
  `Content` mediumtext DEFAULT NULL,
  `Is_Client_To_Provider` tinyint(4) DEFAULT NULL,
  `Sent_At` datetime DEFAULT NULL,
  `Delivered_At` datetime DEFAULT NULL,
  `Read_At` datetime DEFAULT NULL,
  `Status` varchar(45) DEFAULT NULL,
  `Conversation_ID` int(11) NOT NULL,
  `Replied_To_Message` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`Message_ID`, `Content`, `Is_Client_To_Provider`, `Sent_At`, `Delivered_At`, `Read_At`, `Status`, `Conversation_ID`, `Replied_To_Message`) VALUES
(1, 'Hi', 0, '2026-04-05 03:53:43', NULL, NULL, 'Read', 1, NULL),
(2, 'hi', 1, '2026-04-05 03:53:54', NULL, NULL, 'Read', 1, NULL),
(3, 'Moko wenne', 0, '2026-04-05 03:54:08', NULL, NULL, 'Read', 1, NULL),
(4, 'Thanks for the update!', 1, '2026-04-05 03:54:13', NULL, NULL, 'Read', 1, NULL),
(5, 'Can you clarify the timeline?', 1, '2026-04-05 03:54:16', NULL, NULL, 'Read', 1, NULL),
(6, 'Let\'s schedule a call to discuss further.', 1, '2026-04-05 03:54:18', NULL, NULL, 'Read', 1, NULL),
(7, 'Thanks for the update!', 1, '2026-04-05 03:54:21', NULL, NULL, 'Read', 1, NULL),
(8, 'uttooo', 1, '2026-04-05 04:41:08', NULL, NULL, 'Read', 9, NULL),
(9, 'tea eka hadamauda', 1, '2026-04-05 04:41:17', NULL, NULL, 'Read', 9, NULL),
(10, 'eka thama pago mechchra wela kiyuwe', 0, '2026-04-05 04:42:28', NULL, NULL, 'Read', 9, NULL),
(11, 'Hi suddah', 0, '2026-04-05 04:47:44', NULL, NULL, 'Read', 14, NULL),
(12, 'hi pnnaya', 1, '2026-04-05 04:47:54', NULL, NULL, 'Read', 14, NULL),
(13, 'kohomada ithin', 1, '2026-04-05 04:47:59', NULL, NULL, 'Read', 14, NULL),
(14, 'puca sududa', 1, '2026-04-05 04:48:06', NULL, NULL, 'Read', 14, NULL),
(15, 'patan gaththu gaman dammane kunuharapayak', 0, '2026-04-05 04:48:14', NULL, NULL, 'Read', 14, NULL),
(16, 'modayaa', 0, '2026-04-05 04:48:18', NULL, NULL, 'Read', 14, NULL),
(17, 'uba offlinelu\neka haduwe ndda', 1, '2026-04-05 04:48:30', NULL, NULL, 'Read', 14, NULL),
(18, 'refresh karapn', 0, '2026-04-05 04:48:39', NULL, NULL, 'Read', 14, NULL),
(19, 'he he.. ethana bug ekak thiye', 0, '2026-04-05 04:48:52', NULL, NULL, 'Read', 14, NULL),
(20, 'ehema nathuwa barida', 1, '2026-04-05 04:48:52', NULL, NULL, 'Read', 14, NULL),
(21, 'ado kauda photo eke inna gay kolla', 1, '2026-04-05 04:49:12', NULL, NULL, 'Read', 14, NULL),
(22, 'ado ado', 0, '2026-04-05 04:49:23', NULL, NULL, 'Read', 14, NULL),
(23, 'archive ekai star ekai hadanawane passe', 1, '2026-04-05 04:49:38', NULL, NULL, 'Read', 14, NULL),
(24, 'enter button ekata send krnnath danna', 1, '2026-04-05 04:49:48', NULL, NULL, 'Read', 14, NULL),
(25, 'passe hadamu', 0, '2026-04-05 04:50:11', NULL, NULL, 'Read', 14, NULL),
(26, 'me mn msg nobala innam. delivered withark wenaawada balahn', 1, '2026-04-05 04:50:15', NULL, NULL, 'Read', 14, NULL),
(27, 'ow ow', 0, '2026-04-05 04:50:22', NULL, NULL, 'Read', 14, NULL),
(28, 'thama anith page wala iddi wada karanna hadala nah. msg page ekema uba wena chat ekak open karan hitiyama delivered wenawa', 0, '2026-04-05 04:51:04', NULL, NULL, 'Read', 14, NULL),
(29, 'chutto', 0, '2026-04-05 18:08:01', NULL, NULL, 'Read', 14, NULL),
(30, 'Hiii', 0, '2026-04-05 18:14:44', NULL, NULL, 'Read', 35, NULL),
(31, 'hii', 1, '2026-04-05 18:14:57', NULL, NULL, 'Read', 35, NULL),
(32, 'Kohomadaa', 0, '2026-04-05 18:15:08', NULL, NULL, 'Read', 35, NULL),
(33, 'nicee', 1, '2026-04-05 18:15:21', NULL, NULL, 'Read', 35, NULL),
(34, 'Profile Pic ekak upload kare nadda', 0, '2026-04-05 18:15:24', NULL, NULL, 'Read', 35, NULL),
(35, 'aneee', 1, '2026-04-05 18:15:33', NULL, NULL, 'Read', 35, NULL),
(36, 'naa naa load wenne nathi hinda ahuwe', 0, '2026-04-05 18:15:42', NULL, NULL, 'Read', 35, NULL),
(37, 'system eke awlakda balanna', 0, '2026-04-05 18:15:54', NULL, NULL, 'Read', 35, NULL),
(38, 'load kale na', 1, '2026-04-05 18:16:21', NULL, NULL, 'Read', 35, NULL),
(39, 'Hah... Shoiii', 0, '2026-04-05 18:16:30', NULL, NULL, 'Read', 35, NULL),
(40, 'enter eka press kalama send wenne nane.', 1, '2026-04-05 18:16:38', NULL, NULL, 'Read', 35, NULL),
(41, 'hee', 1, '2026-04-05 18:16:44', NULL, NULL, 'Read', 35, NULL),
(42, 'hadala naa thama... ewa', 0, '2026-04-05 18:16:47', NULL, NULL, 'Read', 35, NULL),
(43, 'nicee', 1, '2026-04-05 18:16:57', NULL, NULL, 'Read', 35, NULL),
(44, 'Byeeeeee', 0, '2026-04-05 18:17:04', NULL, NULL, 'Read', 35, NULL),
(45, 'byee', 1, '2026-04-05 18:17:12', NULL, NULL, 'Read', 35, NULL),
(46, 'Let\'s schedule a call to discuss further.', 1, '2026-04-05 18:17:16', NULL, NULL, 'Read', 35, NULL),
(47, 'Can you clarify the timeline? Thanks for the update!', 1, '2026-04-05 18:17:24', NULL, NULL, 'Read', 35, NULL),
(48, 'Thanks for the update!', 0, '2026-04-05 18:17:26', NULL, NULL, 'Read', 35, NULL),
(49, 'oooiii', 1, '2026-04-06 13:00:59', NULL, NULL, 'Read', 14, NULL),
(50, 'dhdhasdasd', 1, '2026-04-06 16:01:31', NULL, NULL, 'Read', 14, NULL),
(51, 'eka eka paksha walata pupa denna epa', 0, '2026-04-14 01:01:15', NULL, NULL, 'Read', 14, NULL),
(52, 'hii', 0, '2026-04-14 01:16:23', NULL, NULL, 'Read', 35, NULL),
(53, '?', 0, '2026-04-14 01:16:35', NULL, NULL, 'Read', 35, NULL),
(54, 'ggggg', 0, '2026-04-14 01:16:46', NULL, NULL, 'Read', 14, NULL),
(55, 'hi yaluwe', 0, '2026-04-14 01:22:07', NULL, NULL, 'Read', 110, NULL),
(56, 'ooi', 0, '2026-04-14 01:27:43', NULL, NULL, 'Delivered', 110, NULL),
(57, 'Hy kunoo', 1, '2026-04-14 01:28:23', NULL, NULL, 'Read', 110, NULL),
(58, 'ado ado', 0, '2026-04-14 01:28:54', NULL, NULL, 'Delivered', 110, NULL),
(59, 'Hi', 0, '2026-04-14 01:33:19', NULL, NULL, 'Read', 156, NULL),
(60, 'hi', 1, '2026-04-14 01:34:15', NULL, NULL, 'Read', 156, NULL),
(61, 'hellooo', 0, '2026-04-14 01:34:42', NULL, NULL, 'Read', 156, NULL),
(62, 'kohomadaaa', 0, '2026-04-14 01:34:45', NULL, NULL, 'Read', 156, NULL),
(63, 'pissuda', 1, '2026-04-14 01:36:31', NULL, NULL, 'Read', 156, NULL),
(64, 'hi suddh', 0, '2026-04-14 01:38:47', NULL, NULL, 'Read', 191, NULL),
(65, 'moko wenne', 0, '2026-04-14 01:39:00', NULL, NULL, 'Read', 191, NULL),
(66, 'awlakda mokk hari', 0, '2026-04-14 01:39:11', NULL, NULL, 'Read', 191, NULL),
(67, 'ff', 0, '2026-04-14 01:39:14', NULL, NULL, 'Read', 191, NULL),
(68, 'Na na', 1, '2026-04-14 01:39:34', NULL, NULL, 'Read', 191, NULL),
(69, 'What', 1, '2026-04-14 01:40:06', NULL, NULL, 'Read', 191, NULL),
(70, 'ow', 0, '2026-04-14 01:41:10', NULL, NULL, 'Read', 156, NULL),
(71, 'Yoow', 1, '2026-04-14 01:43:28', NULL, NULL, 'Read', 191, NULL),
(72, 'Me', 1, '2026-04-14 01:43:39', NULL, NULL, 'Read', 191, NULL),
(73, 'meeeeeee mn meda para unit 2k fail wei wage whatsapp balnnako', 1, '2026-04-14 01:44:20', NULL, NULL, 'Read', 156, NULL),
(74, 'ai e', 0, '2026-04-14 01:45:06', NULL, NULL, 'Read', 156, NULL),
(75, 'das deken hodata balanna', 1, '2026-04-14 01:45:49', NULL, NULL, 'Read', 156, NULL),
(76, 'shook', 0, '2026-04-14 01:46:04', NULL, NULL, 'Read', 156, NULL),
(77, 'physics n science', 1, '2026-04-14 01:46:28', NULL, NULL, 'Read', 156, NULL),
(78, 'science eka patta lesi hbai miss mara napuru widiyata papers balanne late submissions walata marks dennema nh kiyla patan gatte mn iwaraiiiiiiii', 1, '2026-04-14 01:47:32', NULL, NULL, 'Read', 156, NULL),
(79, 'mta marks dena ekk nadda', 1, '2026-04-14 01:47:44', NULL, NULL, 'Read', 156, NULL),
(80, 'mee katawath kiyannh hodeeee', 1, '2026-04-14 01:47:52', NULL, NULL, 'Read', 156, NULL),
(81, 'ithin eka dana danath ai yko welawata kare naththe', 0, '2026-04-14 01:49:54', NULL, NULL, 'Read', 156, NULL),
(82, 'draft watila thiyena eka mage prshnayak nemei', 1, '2026-04-14 01:50:37', NULL, NULL, 'Read', 156, NULL),
(83, 'mage thamaiiiiiii', 1, '2026-04-14 01:50:42', NULL, NULL, 'Read', 156, NULL),
(84, 'dawasama oshi gawa lap eka mn iye thiyan hitiyanm oka aniwaren dakinawa me ganigen illa gattamath kaa gahanawa magula', 1, '2026-04-14 01:51:29', NULL, NULL, 'Read', 156, NULL),
(85, 'dan ithin oka hithala wadak nane', 0, '2026-04-14 02:00:33', NULL, NULL, 'Read', 156, NULL),
(86, 'miss ta email ekak dala hitha hadaganna', 0, '2026-04-14 02:01:00', NULL, NULL, 'Read', 156, NULL),
(87, 'fefee', 0, '2026-04-14 02:01:15', NULL, NULL, 'Read', 156, NULL),
(88, 'fefefe', 0, '2026-04-14 02:01:17', NULL, NULL, 'Read', 156, NULL),
(89, 'fefefef', 0, '2026-04-14 02:01:20', NULL, NULL, 'Read', 156, NULL),
(90, 'gegagaegae', 0, '2026-04-14 02:01:23', NULL, NULL, 'Read', 156, NULL),
(91, 'petta bila nh thamai', 1, '2026-04-14 02:01:35', NULL, NULL, 'Read', 156, NULL),
(92, 'nah hitapu gaman msg send wenne nathuw yanwa', 0, '2026-04-14 02:01:51', NULL, NULL, 'Read', 156, NULL),
(93, 'hithaganna bah ai kiyala', 0, '2026-04-14 02:01:55', NULL, NULL, 'Read', 156, NULL),
(94, 'me wenne', 1, '2026-04-14 02:02:07', NULL, NULL, 'Read', 156, NULL),
(95, 'kalin pennuwa', 1, '2026-04-14 02:02:24', NULL, NULL, 'Read', 156, NULL),
(96, 'na na', 0, '2026-04-14 02:03:16', NULL, NULL, 'Read', 156, NULL),
(97, 'Sending kiyala watila nikn thiyenw', 0, '2026-04-14 02:03:24', NULL, NULL, 'Read', 156, NULL),
(98, 'nh mnussayo', 1, '2026-04-14 02:03:35', NULL, NULL, 'Read', 156, NULL),
(99, 'api dan cht karapu tika', 1, '2026-04-14 02:03:47', NULL, NULL, 'Read', 156, NULL),
(100, 'nikanma delete wela', 1, '2026-04-14 02:03:58', NULL, NULL, 'Read', 156, NULL),
(101, 'mn kare nh', 1, '2026-04-14 02:04:01', NULL, NULL, 'Read', 156, NULL),
(102, 'mn whatsapp damma', 0, '2026-04-14 02:04:13', NULL, NULL, 'Read', 156, NULL),
(103, 'e msg eka oyata awada', 0, '2026-04-14 02:04:30', NULL, NULL, 'Read', 156, NULL),
(104, 'eka random wenne kiyala man damme', 0, '2026-04-14 02:04:36', NULL, NULL, 'Read', 156, NULL),
(105, 'natha', 1, '2026-04-14 02:04:42', NULL, NULL, 'Read', 156, NULL),
(106, 'ai danne nah ehema wenne', 0, '2026-04-14 02:05:02', NULL, NULL, 'Read', 156, NULL),
(107, 'dakkane wadak karaganna bh hariyata', 1, '2026-04-14 02:05:10', NULL, NULL, 'Read', 156, NULL),
(108, 'Viva eke pennaddi eka wunoth winasai', 0, '2026-04-14 02:05:12', NULL, NULL, 'Read', 156, NULL),
(109, 'matanm baninawa', 1, '2026-04-14 02:05:14', NULL, NULL, 'Read', 156, NULL),
(110, 'ane mechchara ekak hari hadala pennanawako puluwannm', 0, '2026-04-14 02:05:33', NULL, NULL, 'Read', 156, NULL),
(111, 'AI AI Artificial Intelligence oya nemei', 1, '2026-04-14 02:08:52', NULL, NULL, 'Read', 156, NULL),
(112, 'last month eke 3450k mn spend kare kohomada mn danne meka create kare', 1, '2026-04-14 02:11:55', NULL, NULL, 'Read', 156, NULL),
(113, 'dakkan mistakes', 1, '2026-04-14 02:12:51', NULL, NULL, 'Read', 156, NULL),
(114, 'meka mn wage buddi mathek nm ganna salli walata', 1, '2026-04-14 02:13:09', NULL, NULL, 'Read', 156, NULL),
(115, 'e me mn ynawa nida gana bye good night n happy new year!!!', 1, '2026-04-14 02:18:30', NULL, NULL, 'Read', 156, NULL),
(116, 'fe', 0, '2026-04-14 02:19:10', NULL, NULL, 'Sent', 156, NULL),
(117, 'fe', 0, '2026-04-14 02:19:13', NULL, NULL, 'Sent', 156, NULL),
(118, 'testing', 0, '2026-04-14 02:21:45', NULL, NULL, 'Sent', 156, NULL),
(119, 'testing', 0, '2026-04-14 02:21:47', NULL, NULL, 'Sent', 156, NULL),
(120, 'testing', 0, '2026-04-14 02:21:49', NULL, NULL, 'Sent', 156, NULL),
(121, 'ane', 0, '2026-04-14 02:21:50', NULL, NULL, 'Sent', 156, NULL),
(122, 'ekakwath', 0, '2026-04-14 02:21:52', NULL, NULL, 'Sent', 156, NULL),
(123, 'nawathiyaaan', 0, '2026-04-14 02:21:56', NULL, NULL, 'Sent', 156, NULL),
(124, 'yanna epa', 0, '2026-04-14 02:21:59', NULL, NULL, 'Sent', 156, NULL),
(125, 'sending watenna', 0, '2026-04-14 02:22:01', NULL, NULL, 'Sent', 156, NULL),
(126, 'anee', 0, '2026-04-14 02:22:03', NULL, NULL, 'Sent', 156, NULL),
(127, 'inna', 0, '2026-04-14 02:22:13', NULL, NULL, 'Sent', 156, NULL),
(128, 'test', 0, '2026-04-14 02:50:38', NULL, NULL, 'Sent', 156, NULL),
(129, 'test 1', 0, '2026-04-14 02:50:40', NULL, NULL, 'Sent', 156, NULL),
(130, '2', 0, '2026-04-14 02:50:40', NULL, NULL, 'Sent', 156, NULL),
(131, '3', 0, '2026-04-14 02:50:41', NULL, NULL, 'Sent', 156, NULL),
(132, '4', 0, '2026-04-14 02:50:41', NULL, NULL, 'Sent', 156, NULL),
(133, '5', 0, '2026-04-14 02:50:42', NULL, NULL, 'Sent', 156, NULL),
(134, 'hari', 0, '2026-04-14 02:50:44', NULL, NULL, 'Sent', 156, NULL),
(135, 'dan hari', 0, '2026-04-14 02:50:46', NULL, NULL, 'Sent', 156, NULL),
(136, 'yeee', 0, '2026-04-14 02:50:47', NULL, NULL, 'Sent', 156, NULL),
(137, 'Suduuu', 0, '2026-04-14 02:50:52', NULL, NULL, 'Read', 35, NULL),
(138, 'kadila thibba eka haduwa', 0, '2026-04-14 02:50:57', NULL, NULL, 'Read', 35, NULL),
(139, 'ye ye', 0, '2026-04-14 02:50:58', NULL, NULL, 'Read', 35, NULL),
(140, 'Mage wade Moko wenne', 1, '2026-04-14 02:52:35', NULL, NULL, 'Read', 191, NULL),
(141, 'aa eka balana gaman thiyenne', 0, '2026-04-14 02:52:53', NULL, NULL, 'Read', 191, NULL),
(142, 'next week eke denna puluwan komath', 0, '2026-04-14 02:53:00', NULL, NULL, 'Read', 191, NULL),
(143, 'Thanks brother', 1, '2026-04-14 02:53:16', NULL, NULL, 'Read', 191, NULL),
(144, 'Hi bokka', 1, '2026-04-14 03:34:37', NULL, NULL, 'Read', 191, NULL),
(145, 'mmmm', 0, '2026-04-14 03:34:53', NULL, NULL, 'Read', 191, NULL),
(146, 'mm', 0, '2026-04-14 03:35:18', NULL, NULL, 'Read', 191, NULL),
(147, 'Chutto', 1, '2026-04-14 03:37:12', NULL, NULL, 'Read', 14, NULL),
(148, 'hehe', 0, '2026-04-14 03:37:53', NULL, NULL, 'Read', 14, NULL),
(149, 'uba wena page ekaka hitiyath ubata enwa dan msg eka', 0, '2026-04-14 03:38:11', NULL, NULL, 'Read', 14, NULL),
(150, 'Sirwt', 1, '2026-04-14 03:38:27', NULL, NULL, 'Read', 14, NULL),
(151, 'uba palayanko dashboard ekata', 0, '2026-04-14 03:38:34', NULL, NULL, 'Read', 14, NULL),
(152, 'Idhn dan dahn blnn', 1, '2026-04-14 03:38:40', NULL, NULL, 'Read', 14, NULL),
(153, 'hello', 0, '2026-04-14 03:38:44', NULL, NULL, 'Read', 14, NULL),
(154, 'hello', 0, '2026-04-14 03:38:48', NULL, NULL, 'Read', 14, NULL),
(155, 'awada', 0, '2026-04-14 03:38:51', NULL, NULL, 'Read', 14, NULL),
(156, 'notification ekak', 0, '2026-04-14 03:38:55', NULL, NULL, 'Read', 14, NULL),
(157, 'notification ekak nemei.. ara toast ekak', 0, '2026-04-14 03:39:03', NULL, NULL, 'Read', 14, NULL),
(158, 'he he', 0, '2026-04-14 03:39:05', NULL, NULL, 'Read', 14, NULL),
(159, 'Ado awa', 1, '2026-04-14 03:39:08', NULL, NULL, 'Read', 14, NULL),
(160, 'Athal', 1, '2026-04-14 03:39:12', NULL, NULL, 'Read', 14, NULL),
(161, 'he he', 0, '2026-04-14 03:39:13', NULL, NULL, 'Read', 14, NULL),
(162, 'responsive ekath loku awlk nane', 0, '2026-04-14 03:39:19', NULL, NULL, 'Read', 14, NULL),
(163, 'Mobile responsive eke awl kipayak thiye', 1, '2026-04-14 03:39:23', NULL, NULL, 'Read', 14, NULL),
(164, 'cursor eken gahuwe ekanm', 0, '2026-04-14 03:39:23', NULL, NULL, 'Read', 14, NULL),
(165, 'mn gahapu ekak nemei. he he', 0, '2026-04-14 03:39:32', NULL, NULL, 'Read', 14, NULL),
(166, 'Message eka scroll wena eke bug ekk thiye message daddi ena ewwa awlk nah', 1, '2026-04-14 03:39:56', NULL, NULL, 'Read', 14, NULL),
(167, 'mata hena katha wadak una bn', 0, '2026-04-14 03:40:15', NULL, NULL, 'Read', 14, NULL),
(168, 'Thawa message section eka scroll wena eka nwttamna eka scroll wenna one na yata margin tika awl', 1, '2026-04-14 03:40:21', NULL, NULL, 'Read', 14, NULL),
(169, 'mn socket eka update karala eka restart karala thibbe nah. eka hinda errors wagayak thibba', 0, '2026-04-14 03:41:01', NULL, NULL, 'Read', 14, NULL),
(170, 'It psse chat ekt giyama uda main navbar ain wenawa ekanhdhn', 1, '2026-04-14 03:41:08', NULL, NULL, 'Read', 14, NULL),
(171, 'passe mn eka restart karata passe melo deyak wada nathuw giya', 0, '2026-04-14 03:41:12', NULL, NULL, 'Read', 14, NULL),
(172, 'mn hithuwe hari thama kiyaa', 0, '2026-04-14 03:41:18', NULL, NULL, 'Read', 14, NULL),
(173, 'Mokd wela thibbe', 1, '2026-04-14 03:41:28', NULL, NULL, 'Read', 14, NULL),
(174, 'ita passe baladdi AI eken mage eka file ekak full clear karala pissu yaka', 0, '2026-04-14 03:41:41', NULL, NULL, 'Read', 14, NULL),
(175, 'wada nathi wela thiyenne eka hinda', 0, '2026-04-14 03:41:46', NULL, NULL, 'Read', 14, NULL),
(176, 'Ahhhh', 1, '2026-04-14 03:41:52', NULL, NULL, 'Read', 14, NULL),
(177, 'mn arehe araka restart una hinda kiyala hithan hoyanw hoynw', 0, '2026-04-14 03:41:55', NULL, NULL, 'Read', 14, NULL),
(178, 'passe baladdi ara file eka empty', 0, '2026-04-14 03:42:01', NULL, NULL, 'Read', 14, NULL),
(179, 'pissu pagaya', 0, '2026-04-14 03:42:07', NULL, NULL, 'Read', 14, NULL),
(180, 'Mn damma tika blhn', 1, '2026-04-14 03:42:23', NULL, NULL, 'Read', 14, NULL),
(181, 'Ah aye', 1, '2026-04-14 03:42:26', NULL, NULL, 'Read', 14, NULL),
(182, 'Thawa', 1, '2026-04-14 03:42:30', NULL, NULL, 'Read', 14, NULL),
(183, 'Chat eke idan aye me chat ektm enna click krddi bug ekk wela msg pennane nathuwa yanawa', 1, '2026-04-14 03:42:54', NULL, NULL, 'Read', 14, NULL),
(184, 'ah thwa emoji danna puluwan ??', 0, '2026-04-14 03:42:56', NULL, NULL, 'Read', 14, NULL),
(185, 'Ado niceee ??', 1, '2026-04-14 03:43:11', NULL, NULL, 'Read', 14, NULL),
(186, 'render wenna podi welawak ynw mn hithanne', 0, '2026-04-14 03:43:18', NULL, NULL, 'Read', 14, NULL),
(187, 'Its okay ?‍?️', 1, '2026-04-14 03:43:31', NULL, NULL, 'Read', 14, NULL),
(188, 'he he', 0, '2026-04-14 03:43:47', NULL, NULL, 'Read', 14, NULL),
(189, 'ubala database balnna epa', 0, '2026-04-14 03:43:52', NULL, NULL, 'Read', 14, NULL),
(190, 'mn meke wiwida aya ekka chat kara test karanna', 0, '2026-04-14 03:43:59', NULL, NULL, 'Read', 14, NULL),
(191, 'msg kiywnna epa', 0, '2026-04-14 03:44:01', NULL, NULL, 'Read', 14, NULL),
(192, 'Ehnn mkkhri athi blnnone db eka psse', 1, '2026-04-14 03:44:30', NULL, NULL, 'Read', 14, NULL),
(193, 'mee', 0, '2026-04-14 03:44:46', NULL, NULL, 'Read', 14, NULL),
(194, 'epaa', 0, '2026-04-14 03:44:47', NULL, NULL, 'Read', 14, NULL),
(195, 'ahapanko', 0, '2026-04-14 03:44:50', NULL, NULL, 'Read', 14, NULL),
(196, 'Oo kiynnaaa', 1, '2026-04-14 03:44:56', NULL, NULL, 'Read', 14, NULL),
(197, 'attachment send karana eka hadissid ?', 0, '2026-04-14 03:45:06', NULL, NULL, 'Read', 14, NULL),
(198, 'Nahhh', 1, '2026-04-14 03:45:15', NULL, NULL, 'Read', 14, NULL),
(199, 'Anith tika iwr krla idhn', 1, '2026-04-14 03:45:22', NULL, NULL, 'Read', 14, NULL),
(200, 'elm', 0, '2026-04-14 03:45:29', NULL, NULL, 'Read', 14, NULL),
(201, 'Attachment psse hdmu bn', 1, '2026-04-14 03:45:39', NULL, NULL, 'Read', 14, NULL),
(202, 'Mn profile update ekath gahala thiyenne', 0, '2026-04-14 03:45:44', NULL, NULL, 'Read', 14, NULL),
(203, 'Mokada godak weleta linkne ywnne', 1, '2026-04-14 03:45:50', NULL, NULL, 'Read', 14, NULL),
(204, 'aye thiyenne category customization seen ekai uba ara kiyuw mokkd profile kathawai', 0, '2026-04-14 03:46:08', NULL, NULL, 'Read', 14, NULL),
(205, 'Ah profile update ek ghuwanm nice mkkda route ek', 1, '2026-04-14 03:46:12', NULL, NULL, 'Read', 14, NULL),
(206, 'me anith un ithuru tka gahanwd', 0, '2026-04-14 03:46:18', NULL, NULL, 'Read', 14, NULL),
(207, 'nikn profile eka click karala palayan', 0, '2026-04-14 03:46:26', NULL, NULL, 'Read', 14, NULL),
(208, 'dakunu paththe uda', 0, '2026-04-14 03:46:29', NULL, NULL, 'Read', 14, NULL),
(209, 'eka neda uba kiyuwe', 0, '2026-04-14 03:46:33', NULL, NULL, 'Read', 14, NULL),
(210, 'wena ekakda', 0, '2026-04-14 03:46:34', NULL, NULL, 'Read', 14, NULL),
(211, 'Na na i mean mta one thanakta set krnna pluwan wenna epai', 1, '2026-04-14 03:46:44', NULL, NULL, 'Read', 14, NULL),
(212, 'Ah profile update', 1, '2026-04-14 03:47:01', NULL, NULL, 'Read', 14, NULL),
(213, 'profile view eka gahuwe nah', 0, '2026-04-14 03:47:19', NULL, NULL, 'Read', 14, NULL),
(214, 'Mn hithuwe ub update kiwwa eka verb ekk widiyata', 1, '2026-04-14 03:47:24', NULL, NULL, 'Read', 14, NULL),
(215, 'eka cursor ekata passe dila balanw', 0, '2026-04-14 03:47:30', NULL, NULL, 'Read', 14, NULL),
(216, 'He he', 1, '2026-04-14 03:47:31', NULL, NULL, 'Read', 14, NULL),
(217, 'ane ane pancho', 0, '2026-04-14 03:47:39', NULL, NULL, 'Read', 14, NULL),
(218, '> me anith un ithuru tka gahanwd \n???', 0, '2026-04-14 03:47:46', NULL, NULL, 'Read', 14, NULL),
(219, 'Thawa thiyenne monada', 1, '2026-04-14 03:47:48', NULL, NULL, 'Read', 14, NULL),
(220, 'Anith un gahanawa', 1, '2026-04-14 03:47:53', NULL, NULL, 'Read', 14, NULL),
(221, 'Mata project ek ghnn thiye thawa filtering part ekk thiye echchrai', 1, '2026-04-14 03:48:08', NULL, NULL, 'Read', 14, NULL),
(222, 'ethota okkoma harid', 0, '2026-04-14 03:48:17', NULL, NULL, 'Read', 14, NULL),
(223, '?', 0, '2026-04-14 03:48:24', NULL, NULL, 'Read', 14, NULL),
(224, 'matanm satha pahakata wishawasa nah arunge code', 0, '2026-04-14 03:48:41', NULL, NULL, 'Read', 14, NULL),
(225, 'Bashi ghnawa project eka client ptte', 1, '2026-04-14 03:48:42', NULL, NULL, 'Read', 14, NULL),
(226, 'Bashi bala bala ghnne', 1, '2026-04-14 03:48:50', NULL, NULL, 'Read', 14, NULL),
(227, 'Akila gahala iwrailu', 1, '2026-04-14 03:48:57', NULL, NULL, 'Read', 14, NULL),
(228, 'me ahapanko', 0, '2026-04-14 03:49:01', NULL, NULL, 'Read', 14, NULL),
(229, 'mn dan ara category wala field customization eka gahanawane', 0, '2026-04-14 03:49:13', NULL, NULL, 'Read', 14, NULL),
(230, 'Akilage eka anthimt pull krnnone nttm check krnna bane', 1, '2026-04-14 03:49:19', NULL, NULL, 'Read', 14, NULL),
(231, 'ethota eka enne client request ekak daddi neda.. e tika fill karanna', 0, '2026-04-14 03:49:30', NULL, NULL, 'Read', 14, NULL),
(232, 'Ado eka anthimt ghpn pease', 1, '2026-04-14 03:49:34', NULL, NULL, 'Read', 14, NULL),
(233, 'Admin eke okkoma iwr krla inta', 1, '2026-04-14 03:50:04', NULL, NULL, 'Read', 14, NULL),
(234, 'admin eka onemd ithin', 0, '2026-04-14 03:50:15', NULL, NULL, 'Read', 14, NULL),
(235, 'he he', 0, '2026-04-14 03:50:15', NULL, NULL, 'Read', 14, NULL),
(236, 'Category customization thama awasanetama ghnne', 1, '2026-04-14 03:50:17', NULL, NULL, 'Read', 14, NULL),
(237, 'Ado iwr krgnnone htto', 1, '2026-04-14 03:50:32', NULL, NULL, 'Read', 14, NULL),
(238, 'Category customization nathi unath shape anith tika nttm hikenawa', 1, '2026-04-14 03:50:47', NULL, NULL, 'Read', 14, NULL),
(239, 'atawala pennanna barid', 0, '2026-04-14 03:50:51', NULL, NULL, 'Read', 14, NULL),
(240, 'he he', 0, '2026-04-14 03:50:54', NULL, NULL, 'Read', 14, NULL),
(241, 'Atawanna ba pco', 1, '2026-04-14 03:51:01', NULL, NULL, 'Read', 14, NULL),
(242, 'anee', 0, '2026-04-14 03:51:10', NULL, NULL, 'Read', 14, NULL),
(243, 'Aneee anee neme', 1, '2026-04-14 03:51:21', NULL, NULL, 'Read', 14, NULL),
(244, 'Eka thama ai dala ghamu kiwwe', 1, '2026-04-14 03:51:27', NULL, NULL, 'Read', 14, NULL),
(245, 'ane un welwkt bula kelinw', 0, '2026-04-14 03:51:40', NULL, NULL, 'Read', 14, NULL),
(246, 'ara cursor witharai set matanm', 0, '2026-04-14 03:51:45', NULL, NULL, 'Read', 14, NULL),
(247, 'Mtath kammali dan meka ghnn bn laravel react huru wela', 1, '2026-04-14 03:51:47', NULL, NULL, 'Read', 14, NULL),
(248, 'uge limit eka pannoth mn nikn innw bimja', 0, '2026-04-14 03:52:04', NULL, NULL, 'Read', 14, NULL),
(249, 'Bula kelinne na htto claude. Piccuda', 1, '2026-04-14 03:52:13', NULL, NULL, 'Read', 14, NULL),
(250, 'Claude buy krgnn thinbanm supiri', 1, '2026-04-14 03:52:29', NULL, NULL, 'Read', 14, NULL),
(251, 'uwa use karala nah mn', 0, '2026-04-14 03:53:33', NULL, NULL, 'Read', 14, NULL),
(252, 'copilot full bula welawakta', 0, '2026-04-14 03:53:41', NULL, NULL, 'Read', 14, NULL),
(253, 'Copilot auto eka ehemanthama', 1, '2026-04-14 03:53:58', NULL, NULL, 'Read', 14, NULL),
(254, 'But clause sonnatn hari opus hri dmmoth kapa', 1, '2026-04-14 03:54:15', NULL, NULL, 'Read', 14, NULL),
(255, 'Karima ghillak ghnne', 1, '2026-04-14 03:54:26', NULL, NULL, 'Read', 14, NULL),
(256, 'blnnmko', 0, '2026-04-14 03:54:37', NULL, NULL, 'Read', 14, NULL),
(257, 'Kari piliwelai htto', 1, '2026-04-14 03:54:37', NULL, NULL, 'Read', 14, NULL),
(258, 'Ape araka gahuwe ekenne', 1, '2026-04-14 03:54:48', NULL, NULL, 'Read', 14, NULL),
(259, 'Component okkoma gahuwe eken', 1, '2026-04-14 03:54:57', NULL, NULL, 'Read', 14, NULL),
(260, 'mee ooi', 0, '2026-04-14 03:55:03', NULL, NULL, 'Read', 14, NULL),
(261, 'eke ubala product eka kawadda denne', 0, '2026-04-14 03:55:09', NULL, NULL, 'Read', 14, NULL),
(262, 'Mara lssnt customizable widiyt ghuwa', 1, '2026-04-14 03:55:11', NULL, NULL, 'Read', 14, NULL),
(263, '> eke ubala product eka kawadda denne', 1, '2026-04-14 03:55:17', NULL, NULL, 'Read', 14, NULL),
(264, '> eke ubala product eka kawadda denne nisalgen ahnnta', 1, '2026-04-14 03:55:25', NULL, NULL, 'Read', 14, NULL),
(265, 'Mn sales ek iwr krnawa me sathiye', 1, '2026-04-14 03:55:40', NULL, NULL, 'Read', 14, NULL),
(266, 'mn ubata hondee wade baradunnee..', 0, '2026-04-14 03:55:46', NULL, NULL, 'Read', 14, NULL),
(267, 'Hari hari ithin ubbnodanna ekek wagene nisal', 1, '2026-04-14 03:56:01', NULL, NULL, 'Read', 14, NULL),
(268, 'Kariya', 1, '2026-04-14 03:56:04', NULL, NULL, 'Read', 14, NULL),
(269, 'Mee eka ikmnt iwr krnnone me labba iwr wela', 1, '2026-04-14 03:56:30', NULL, NULL, 'Read', 14, NULL),
(270, 'Ado eekanm nice ekt awa ee unta', 1, '2026-04-14 03:56:41', NULL, NULL, 'Read', 14, NULL),
(271, 'ow ow bn', 0, '2026-04-14 03:56:41', NULL, NULL, 'Read', 14, NULL),
(272, 'ah me', 0, '2026-04-14 03:56:45', NULL, NULL, 'Read', 14, NULL),
(273, 'eke wade hari giyoth poddk maintain karan yanna ekek one', 0, '2026-04-14 03:56:56', NULL, NULL, 'Read', 14, NULL),
(274, 'Mn thama ithin', 1, '2026-04-14 03:57:08', NULL, NULL, 'Read', 14, NULL),
(275, 'oya customer support ekak dila podi podi dewal tika karanna', 0, '2026-04-14 03:57:08', NULL, NULL, 'Read', 14, NULL),
(276, 'salary eka lokuwt denna bah ekatanm habai.. shape eke ganak denna puluwan', 0, '2026-04-14 03:57:23', NULL, NULL, 'Read', 14, NULL),
(277, 'Krmu krmu salli hambenawanmnaye monada.', 1, '2026-04-14 03:57:27', NULL, NULL, 'Read', 14, NULL),
(278, 'he he.. gammk', 0, '2026-04-14 03:57:43', NULL, NULL, 'Read', 14, NULL),
(279, '3%kwth denna kiyhn system eken', 1, '2026-04-14 03:57:57', NULL, NULL, 'Read', 14, NULL),
(280, 'Anna aru 20n psse ahnawa', 1, '2026-04-14 03:58:13', NULL, NULL, 'Read', 14, NULL),
(281, 'Uu poddak traka painawa bn complex logic ekk awa gmn ekai oyanhira wela thiynne nttm oka amaru nah', 1, '2026-04-14 03:58:55', NULL, NULL, 'Read', 14, NULL),
(282, 'e me me', 0, '2026-04-14 03:58:58', NULL, NULL, 'Read', 14, NULL),
(283, 'echchara kal inna bah oi', 0, '2026-04-14 03:59:08', NULL, NULL, 'Read', 14, NULL),
(284, 'aru mawa kai', 0, '2026-04-14 03:59:09', NULL, NULL, 'Read', 14, NULL),
(285, 'Ahpn godk thiyed kiyl thawa', 1, '2026-04-14 03:59:23', NULL, NULL, 'Read', 14, NULL),
(286, '> 3%kwth denna kiyhn system eken \n\newwata precentage denne nah ithin ?', 0, '2026-04-14 03:59:48', NULL, NULL, 'Read', 14, NULL),
(287, 'Ghnwt mkuth hambenne ndda', 1, '2026-04-14 04:00:02', NULL, NULL, 'Read', 14, NULL),
(288, 'Salary ekak denne ekata thama bijjooo', 0, '2026-04-14 04:00:19', NULL, NULL, 'Read', 14, NULL),
(289, 'Apita dana dana eken kiykhri', 1, '2026-04-14 04:00:20', NULL, NULL, 'Read', 14, NULL),
(290, 'Ubata hambeine eka bedagamu api', 1, '2026-04-14 04:00:38', NULL, NULL, 'Read', 14, NULL),
(291, 'Ubath sathutin apith sathutin', 1, '2026-04-14 04:00:50', NULL, NULL, 'Read', 14, NULL),
(292, 'mata hambena eken ithin api somiyak damu', 0, '2026-04-14 04:00:52', NULL, NULL, 'Read', 14, NULL),
(293, 'eka wenama kathawak', 0, '2026-04-14 04:00:58', NULL, NULL, 'Read', 14, NULL),
(294, 'Ubt 5%n 2% mn gnnam', 1, '2026-04-14 04:01:06', NULL, NULL, 'Read', 14, NULL),
(295, 'Adareta', 1, '2026-04-14 04:01:16', NULL, NULL, 'Read', 14, NULL),
(296, '5%??', 0, '2026-04-14 04:01:27', NULL, NULL, 'Read', 14, NULL),
(297, 'Ah ubt denw kiwwe ndda', 1, '2026-04-14 04:01:34', NULL, NULL, 'Read', 14, NULL),
(298, 'Denwa.. percentage katha kare nah thama', 0, '2026-04-14 04:02:04', NULL, NULL, 'Read', 14, NULL),
(299, 'Ah ekane eken kotsn ithin mage thama', 1, '2026-04-14 04:02:20', NULL, NULL, 'Read', 14, NULL),
(300, 'Ube ganata widinawa mn', 1, '2026-04-14 04:02:27', NULL, NULL, 'Read', 14, NULL),
(301, 'aneee anee', 0, '2026-04-14 04:02:37', NULL, NULL, 'Read', 14, NULL),
(302, 'En dawst kanna ganna kne', 1, '2026-04-14 04:02:39', NULL, NULL, 'Read', 14, NULL),
(303, 'eh meka athalne bn msg karnna', 0, '2026-04-14 04:02:45', NULL, NULL, 'Read', 14, NULL),
(304, 'Adaretane me okkoma', 1, '2026-04-14 04:02:48', NULL, NULL, 'Read', 14, NULL),
(305, 'amuthu gathiyak thiye', 0, '2026-04-14 04:02:49', NULL, NULL, 'Read', 14, NULL),
(306, 'whatsapp ekata wada', 0, '2026-04-14 04:02:51', NULL, NULL, 'Read', 14, NULL),
(307, 'Ow ooi ape ekaka msg krddi', 1, '2026-04-14 04:03:00', NULL, NULL, 'Read', 14, NULL),
(308, 'apema ekaka msg karaddi ??', 0, '2026-04-14 04:03:01', NULL, NULL, 'Read', 14, NULL),
(309, 'Ee unt awla thama mention krnna bari eka message reply krddi', 1, '2026-04-14 04:03:17', NULL, NULL, 'Read', 14, NULL),
(310, 'Ek thibbanm kari wsi.', 1, '2026-04-14 04:03:24', NULL, NULL, 'Read', 14, NULL),
(311, 'eka hadannm mn', 0, '2026-04-14 04:03:31', NULL, NULL, 'Read', 14, NULL),
(312, 'matath asai eka hadanna', 0, '2026-04-14 04:03:34', NULL, NULL, 'Read', 14, NULL),
(313, 'he hee', 0, '2026-04-14 04:03:42', NULL, NULL, 'Read', 14, NULL),
(314, 'Shape eke hdhn it psse meken withrai msg krnne', 1, '2026-04-14 04:03:49', NULL, NULL, 'Read', 14, NULL),
(315, 'Ah typing ek hdnna amaruda bn', 1, '2026-04-14 04:03:59', NULL, NULL, 'Read', 14, NULL),
(316, 'mmm loku case ekak nathi wei.. AI dala balannm shape eke', 0, '2026-04-14 04:04:18', NULL, NULL, 'Read', 14, NULL),
(317, 'Adarei', 1, '2026-04-14 04:05:04', NULL, NULL, 'Read', 14, NULL),
(318, 'Mn yano kaun wlt sup krnna it psse meka blnawa', 1, '2026-04-14 04:05:17', NULL, NULL, 'Read', 14, NULL),
(319, 'Ub kaumak haduwasa', 1, '2026-04-14 04:05:23', NULL, NULL, 'Read', 14, NULL),
(320, 'Kokisak', 1, '2026-04-14 04:05:28', NULL, NULL, 'Read', 14, NULL),
(321, 'Sup ekk dunnada', 1, '2026-04-14 04:05:36', NULL, NULL, 'Read', 14, NULL),
(322, 'oi', 0, '2026-04-14 04:08:51', NULL, NULL, 'Read', 14, NULL),
(323, 'nonagathe welawe kama hadanna bah', 0, '2026-04-14 04:08:57', NULL, NULL, 'Read', 14, NULL),
(324, 'kokis haduw', 0, '2026-04-14 04:09:03', NULL, NULL, 'Read', 14, NULL),
(325, 'Test', 0, '2026-04-14 06:28:26', NULL, NULL, 'Read', 191, 146),
(326, 'Mokkd case ek', 1, '2026-04-14 06:29:05', NULL, NULL, 'Read', 191, 325),
(327, 'Eka gathi', 1, '2026-04-14 06:29:18', NULL, NULL, 'Read', 191, 326),
(328, 'Dunna dunna', 0, '2026-04-14 06:31:11', NULL, NULL, 'Read', 14, 321),
(329, 'Hiii', 0, '2026-04-14 12:48:51', NULL, NULL, 'Read', 35, NULL),
(330, 'Hi', 1, '2026-04-14 12:49:09', NULL, NULL, 'Read', 35, NULL),
(331, 'Msg walata reply karahaki', 0, '2026-04-14 12:49:23', NULL, NULL, 'Read', 35, 330),
(332, 'Anaaa', 1, '2026-04-14 12:49:30', NULL, NULL, 'Read', 35, NULL),
(333, 'Pandarath dala', 1, '2026-04-14 12:49:37', NULL, NULL, 'Read', 35, NULL),
(334, 'Emoji dannath puluwan ?', 0, '2026-04-14 12:49:44', NULL, NULL, 'Read', 35, NULL),
(335, 'Encrypted wenne na ned', 1, '2026-04-14 12:49:46', NULL, NULL, 'Read', 35, NULL),
(336, '?', 1, '2026-04-14 12:49:53', NULL, NULL, 'Read', 35, NULL),
(337, 'Shoiiii', 1, '2026-04-14 12:49:58', NULL, NULL, 'Read', 35, NULL),
(338, 'Naa.. delete karamu chat eka', 0, '2026-04-14 12:50:00', NULL, NULL, 'Read', 35, NULL),
(339, '??', 0, '2026-04-14 12:50:06', NULL, NULL, 'Read', 35, NULL),
(340, 'Hah', 1, '2026-04-14 12:50:13', NULL, NULL, 'Read', 35, NULL),
(341, 'Mage msg ekakata reply ekak dannako', 0, '2026-04-14 12:50:17', NULL, NULL, 'Read', 35, NULL),
(342, 'Hri', 1, '2026-04-14 12:50:26', NULL, NULL, 'Read', 35, 341),
(343, 'Whatsapp wage adinna paththata', 0, '2026-04-14 12:50:28', NULL, NULL, 'Read', 35, NULL),
(344, 'Jollyyyy', 0, '2026-04-14 12:50:38', NULL, NULL, 'Read', 35, 342),
(345, 'Ooh', 1, '2026-04-14 12:50:39', NULL, NULL, 'Read', 35, 343),
(346, 'Shoiiiii', 1, '2026-04-14 12:50:47', NULL, NULL, 'Read', 35, NULL),
(347, 'Shoiii neh', 0, '2026-04-14 12:50:50', NULL, NULL, 'Read', 35, NULL),
(348, 'Send button ek telegram wage', 1, '2026-04-14 12:51:02', NULL, NULL, 'Read', 35, NULL),
(349, '???', 0, '2026-04-14 12:51:12', NULL, NULL, 'Read', 35, 348),
(350, '?', 1, '2026-04-14 12:51:14', NULL, NULL, 'Read', 35, NULL),
(351, 'Heeee', 0, '2026-04-14 12:51:38', NULL, NULL, 'Read', 35, NULL),
(352, 'Shoiiiiiii', 1, '2026-04-14 12:52:01', NULL, NULL, 'Read', 35, NULL),
(353, 'Yamuuuu', 0, '2026-04-14 12:52:10', NULL, NULL, 'Read', 35, NULL),
(354, 'oi', 0, '2026-04-15 00:05:59', NULL, NULL, 'Read', 662, NULL),
(355, 'oo paco', 1, '2026-04-15 00:06:09', NULL, NULL, 'Read', 662, NULL),
(356, 'link eka onetha', 0, '2026-04-15 00:06:15', NULL, NULL, 'Read', 662, 355),
(357, 'mokada wenne pnnayo', 1, '2026-04-15 00:06:17', NULL, NULL, 'Read', 662, 354),
(358, 'https://www.crazygames.com/game/simplyup-io?czy_invite=true&utm_source=invite&roomId=DC7uUf3l2&levelId=level1', 0, '2026-04-15 00:06:25', NULL, NULL, 'Read', 662, NULL),
(359, 'oi', 0, '2026-04-15 00:16:16', NULL, NULL, 'Read', 662, NULL),
(360, 'ai htto', 1, '2026-04-15 00:16:34', NULL, NULL, 'Read', 662, NULL),
(361, 'moda pacec uba', 1, '2026-04-15 00:16:43', NULL, NULL, 'Read', 662, 359),
(362, 'mage wade harida', 0, '2026-04-15 00:16:44', NULL, NULL, 'Read', 662, 360),
(363, 'wade karala dipan huththo', 0, '2026-04-15 00:16:53', NULL, NULL, 'Read', 662, NULL),
(364, 'salli gaththanm', 0, '2026-04-15 00:16:56', NULL, NULL, 'Read', 662, NULL),
(365, 'senuthage kellata message ekk damu', 1, '2026-04-15 00:17:14', NULL, NULL, 'Read', 662, 363),
(366, 'hgaaa', 0, '2026-04-15 00:17:19', NULL, NULL, 'Read', 662, NULL),
(367, 'https://hideandseek.world/gamesetup?roomId=-OqCLCrmsRo2a8Ib5au3&gamemode=classic', 0, '2026-04-15 00:26:19', NULL, NULL, 'Read', 662, NULL),
(368, 'Come play Smash Karts \nRoom: eu624041 \nBrowser: https://www.crazygames.com/game/smash-karts?czy_invite=true&utm_source=invite&room=eu624041&wpns=159212&mode=67109640&arena=boneyardbasin \nMobile App: https://smashkarts.io/link/?mode=67109640&wpns=159212&room=eu624041&arena=boneyardbasin \nArena: Boneyard Basin \nRules: Free For All - 3 Mins', 0, '2026-04-15 00:39:41', NULL, NULL, 'Read', 662, NULL),
(369, 'https://www.crazygames.com/game/smash-karts?czy_invite=true&utm_source=invite&room=eu624041&wpns=159212&mode=67109640&arena=boneyardbasin', 0, '2026-04-15 00:40:54', NULL, NULL, 'Read', 662, NULL),
(370, 'https://www.crazygames.com/game/smash-karts?czy_invite=true&utm_source=invite&room=eu657900&wpns=159212&mode=67109640&arena=skyarena-pinball', 0, '2026-04-15 00:41:53', NULL, NULL, 'Read', 662, NULL),
(371, 'https://www.crazygames.com/game/bank-heist?czy_invite=true&utm_source=invite&roomId=b0225e25-52ab-46ef-8708-46f9663d6d74&region=eu', 1, '2026-04-15 01:50:42', NULL, NULL, 'Read', 662, NULL),
(372, 'https://www.crazygames.com/game/foono-online-multiplayer-card-game?czy_invite=true&utm_source=invite&roomId=ackx53pm', 1, '2026-04-15 02:01:25', NULL, NULL, 'Read', 662, NULL),
(373, 'https://krunker.io/?game=MBI:h01wm', 1, '2026-04-15 02:05:29', NULL, NULL, 'Read', 662, NULL),
(374, 'https://krunker.io/?game=MBI:imphc', 1, '2026-04-15 02:09:16', NULL, NULL, 'Read', 662, NULL),
(375, 'https://skribbl.io/?tyCI738k', 0, '2026-04-15 02:17:00', NULL, NULL, 'Read', 662, NULL),
(376, 'https://skribbl.io/?tyCI738k', 0, '2026-04-15 02:44:27', NULL, NULL, 'Read', 662, NULL),
(377, 'https://skribbl.io/?4jCkhylY', 0, '2026-04-15 02:45:21', NULL, NULL, 'Read', 662, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notification_center`
--

CREATE TABLE `notification_center` (
  `ID` int(11) NOT NULL,
  `Generated_Time` int(11) NOT NULL,
  `Title` int(11) NOT NULL,
  `Relevent_Section` int(11) NOT NULL,
  `Is_Readed` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `Payment_ID` int(11) NOT NULL,
  `Amount` double DEFAULT NULL,
  `Status` varchar(45) DEFAULT NULL,
  `Hold_Time` datetime DEFAULT NULL,
  `Paid_Time` datetime DEFAULT NULL,
  `Commission` double DEFAULT NULL,
  `Project_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`Payment_ID`, `Amount`, `Status`, `Hold_Time`, `Paid_Time`, `Commission`, `Project_ID`) VALUES
(10294, 300, 'Refunded', '2026-01-18 10:00:00', '2026-01-22 16:30:00', 30, 208),
(10321, 420, 'Refunded', '2026-02-20 11:00:00', '2026-02-24 11:00:00', 42, 207),
(10374, 1480, 'Paid', '2026-02-10 09:00:00', '2026-02-22 09:00:00', 148, 206),
(10398, 950, 'Paid', '2026-02-27 12:00:00', '2026-02-28 14:15:00', 95, 205),
(10463, 680, 'Pending', '2026-03-11 14:00:00', NULL, 68, 204),
(10464, 1800, 'Paid', '2026-04-18 15:45:13', '2026-04-18 15:45:13', 180, 202),
(10465, 4320, 'Paid', '2026-04-18 15:45:21', '2026-04-18 15:45:21', 432, 201);

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE `post` (
  `Post_ID` int(11) NOT NULL,
  `Created_At` datetime DEFAULT NULL,
  `Category_ID` int(11) NOT NULL,
  `Post_Type` varchar(45) DEFAULT NULL,
  `Post_Status` varchar(45) DEFAULT NULL,
  `Client_ID` int(11) NOT NULL,
  `Provider_ID` int(11) DEFAULT NULL,
  `Provider_Categories_ID` int(11) DEFAULT NULL,
  `Title` varchar(100) DEFAULT NULL,
  `Description` varchar(2048) DEFAULT NULL,
  `Requesting_Price` double DEFAULT NULL,
  `Price_Type` varchar(25) NOT NULL,
  `Level` varchar(30) NOT NULL,
  `End_At` date DEFAULT NULL,
  `Published_At` datetime DEFAULT NULL,
  `Est_Date` date NOT NULL,
  `Request_Status` varchar(15) NOT NULL,
  `Request_Reject_Reason` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`Post_ID`, `Created_At`, `Category_ID`, `Post_Type`, `Post_Status`, `Client_ID`, `Provider_ID`, `Provider_Categories_ID`, `Title`, `Description`, `Requesting_Price`, `Price_Type`, `Level`, `End_At`, `Published_At`, `Est_Date`, `Request_Status`, `Request_Reject_Reason`) VALUES
(1, '2026-04-05 04:53:22', 1, 'post', 'expired', 8, NULL, NULL, 'Post', 'Nothing', 2000, 'Fixed', 'Intermediate', '2026-04-06', '2026-04-05 04:53:53', '2026-04-30', '', NULL),
(2, '2026-04-15 19:38:17', 1, 'direct', 'active', 9, 1, 4, 'Want to create a website', 'I want to create an modern looking website for my restaurant', 25000, 'Fixed', 'Beginner', '2026-05-15', '2026-04-15 19:38:17', '2026-04-22', 'ongoing', NULL),
(101, '2026-03-10 09:15:00', 1, 'direct', 'accepted', 1, 1, NULL, 'E-Commerce Storefront Build', 'Build a responsive storefront with Stripe checkout, product catalog, and admin dashboard. Next.js + Postgres preferred.', 4320, 'Fixed', 'Expert', '2026-04-30', '2026-03-10 09:15:00', '2026-04-30', 'Accepted', NULL),
(102, '2026-03-18 14:00:00', 1, 'direct', 'accepted', 1, 3, NULL, 'Brand Identity & Logo Package', 'Complete brand kit: primary + secondary logos, color palette, typography scale, usage guide.', 1800, 'Fixed', 'Intermediate', '2026-04-25', '2026-03-18 14:00:00', '2026-04-25', 'Accepted', NULL),
(103, '2026-02-20 10:30:00', 1, 'direct', 'accepted', 1, 2, NULL, 'Authentication Module Sprint', 'Implement email/OAuth login, password reset, 2FA, and session management with JWT.', 1200, 'Fixed', 'Expert', '2026-03-20', '2026-02-20 10:30:00', '2026-03-20', 'Accepted', NULL),
(104, '2026-03-02 11:00:00', 1, 'post', 'accepted', 1, 1, NULL, 'UI Design Phase ÔÇö Mobile App', 'Figma mockups for onboarding, dashboard, and settings screens. Dark mode variants required.', 680, 'Fixed', 'Intermediate', '2026-03-25', '2026-03-02 11:00:00', '2026-03-25', 'Accepted', NULL),
(105, '2026-02-05 09:00:00', 1, 'direct', 'completed', 1, 3, NULL, 'Logo & Brand Pack Delivery', 'Final vector logo assets, color guide, and typography scale for marketing rollout.', 950, 'Fixed', 'Intermediate', '2026-02-28', '2026-02-05 09:00:00', '2026-02-28', 'Accepted', NULL),
(106, '2026-01-12 08:30:00', 1, 'post', 'completed', 1, 2, NULL, 'Analytics Dashboard MVP', 'Charts, KPI cards, and CSV export for the BI platform. React + Recharts.', 1480, 'Fixed', 'Expert', '2026-02-15', '2026-01-12 08:30:00', '2026-02-15', 'Accepted', NULL),
(107, '2026-02-01 12:00:00', 1, 'direct', 'completed', 1, 1, NULL, 'QA Testing Cycle ÔÇö Partial', 'Manual QA across 3 flows. Scope was reduced mid-cycle; remaining work credited back.', 420, 'Hourly', 'Intermediate', '2026-02-20', '2026-02-01 12:00:00', '2026-02-20', 'Accepted', NULL),
(108, '2026-01-05 10:00:00', 1, 'direct', 'completed', 1, 3, NULL, 'Initial Wireframes ÔÇö Design System', 'Wireframe set that was later descoped after stakeholder review.', 300, 'Fixed', 'Intermediate', '2026-01-20', '2026-01-05 10:00:00', '2026-01-20', 'Accepted', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `post_data`
--

CREATE TABLE `post_data` (
  `ID` int(11) NOT NULL,
  `Input_ID` int(11) NOT NULL,
  `Value` varchar(2048) DEFAULT NULL,
  `Post_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post_inputs`
--

CREATE TABLE `post_inputs` (
  `Input_ID` int(11) NOT NULL,
  `Title` varchar(45) DEFAULT NULL,
  `Type` varchar(45) DEFAULT NULL,
  `Input_Name` varchar(45) DEFAULT NULL,
  `Default_Value` varchar(256) DEFAULT NULL,
  `Placeholder` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post_need_skills`
--

CREATE TABLE `post_need_skills` (
  `ID` int(11) NOT NULL,
  `Skill_ID` int(11) NOT NULL,
  `Post_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `post_need_skills`
--

INSERT INTO `post_need_skills` (`ID`, `Skill_ID`, `Post_ID`) VALUES
(1, 6, 1),
(2, 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE `project` (
  `Project_ID` int(11) NOT NULL,
  `Post_ID` int(11) NOT NULL,
  `Project_Status` varchar(45) DEFAULT NULL,
  `Started_At` datetime DEFAULT NULL,
  `Ended_At` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`Project_ID`, `Post_ID`, `Project_Status`, `Started_At`, `Ended_At`) VALUES
(201, 101, 'Pending', '2026-03-12 09:00:00', NULL),
(202, 102, 'Pending', '2026-03-20 10:00:00', NULL),
(203, 103, 'Completed', '2026-02-22 09:00:00', '2026-03-18 17:00:00'),
(204, 104, 'Cancelled', '2026-03-04 10:00:00', '2026-03-10 12:00:00'),
(205, 105, 'Completed', '2026-02-07 09:00:00', '2026-02-27 16:00:00'),
(206, 106, 'Cancelled', '2026-01-14 09:00:00', '2026-02-10 14:00:00'),
(207, 107, 'Completed', '2026-02-03 09:00:00', '2026-02-19 17:00:00'),
(208, 108, 'Completed', '2026-01-07 09:00:00', '2026-01-18 15:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `project_requirements`
--

CREATE TABLE `project_requirements` (
  `Requirement_ID` int(11) NOT NULL,
  `Project_ID` int(11) NOT NULL,
  `Requirement_Text` text NOT NULL,
  `Created_At` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_update_log`
--

CREATE TABLE `project_update_log` (
  `ID` int(11) NOT NULL,
  `Title` varchar(45) DEFAULT NULL,
  `Description` varchar(256) DEFAULT NULL,
  `Date` datetime DEFAULT NULL,
  `Project_ID` int(11) NOT NULL,
  `Worked_Hours` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provider`
--

CREATE TABLE `provider` (
  `Provider_ID` int(11) NOT NULL,
  `Email` varchar(256) DEFAULT NULL,
  `Contact_No` varchar(45) DEFAULT NULL,
  `NIC_No` varchar(45) DEFAULT NULL,
  `Password` varchar(512) DEFAULT NULL,
  `Created_At` datetime DEFAULT NULL,
  `First_Name` varchar(100) DEFAULT NULL,
  `Last_Name` varchar(100) DEFAULT NULL,
  `Gender` varchar(45) DEFAULT NULL,
  `Profile_Picture` varchar(512) DEFAULT NULL,
  `Bio` varchar(2048) DEFAULT NULL,
  `NIC_Front` varchar(512) DEFAULT NULL,
  `NIC_Back` varchar(512) DEFAULT NULL,
  `Resume` varchar(512) DEFAULT NULL,
  `Website` varchar(256) DEFAULT NULL,
  `Total_Earning` double NOT NULL DEFAULT 0,
  `Rating` double DEFAULT NULL,
  `Status` varchar(50) NOT NULL,
  `Reason_For_Rejection` mediumtext DEFAULT NULL,
  `Is_Online` tinyint(1) DEFAULT 0,
  `Last_Seen` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `provider`
--

INSERT INTO `provider` (`Provider_ID`, `Email`, `Contact_No`, `NIC_No`, `Password`, `Created_At`, `First_Name`, `Last_Name`, `Gender`, `Profile_Picture`, `Bio`, `NIC_Front`, `NIC_Back`, `Resume`, `Website`, `Total_Earning`, `Rating`, `Status`, `Reason_For_Rejection`, `Is_Online`, `Last_Seen`) VALUES
(1, 'samaraweera.ghn@gmail.com', '0717476839', '200401300433', '$2y$10$A4dLjvhXN3ZQka2J/2RDcOH1I0DYw5LpHUOhgD2lz3Prd1mTJyCTm', '2026-04-04 17:05:15', 'Pasindu', 'Gihan', 'Male', 'ad5a92c6616d7d6e8e47cd8d59ff9c28.jpeg', 'Experienced Software Developer with a strong background in designing, developing, and maintaining scalable applications. Proficient in multiple programming languages and frameworks, with a focus on writing clean, efficient, and maintainable code. Passionate about solving complex problems, learning new technologies, and contributing to innovative projects. Adept at collaborating with cross-functional teams to deliver high-quality software solutions.', '69d0f6ca7ac147.80601436_el-id-kaart-esikylg.webp', '69d0f6ca7b1324.94632158_idback.png', '69d0f6ca7b32c6.06894642_test.pdf', 'pasindugihan.me', 0, NULL, 'Active', NULL, 0, '2026-04-15 05:17:49'),
(2, 'himath@provider.com', '0785902834', '200331000413', '$2y$10$8UycSSuIQO3L0UJ3Sodbj.rYN4oCl/bj.3dj09cpeS5eKVRnZKjSe', '2026-04-05 00:17:21', 'Himath', 'Adithya', 'Male', '69d15c1f05b1c1.33577122_user_18746642.png', '', '69d15c3b696815.87584725_hold-up-his-writing-is-this-fire.gif', '69d15c3b699351.93558210_image.png', NULL, '', 0, NULL, 'Active', NULL, 0, NULL),
(3, 'sanjaya@gmail.com', '0748855693', '200014522698', '$2y$10$A4dLjvhXN3ZQka2J/2RDcOH1I0DYw5LpHUOhgD2lz3Prd1mTJyCTm', '2026-04-05 04:01:33', 'Sanjaya', 'Wathsunu', 'Male', '69d190f837be13.55170077_fajne-zdjecia-profilowe-19.webp', '', '69d19124261dc3.48385883_el-id-kaart-esikylg.webp', '69d19124266f91.45568584_idback.png', NULL, '', 0, NULL, 'Active', NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `provider_categories`
--

CREATE TABLE `provider_categories` (
  `ID` int(11) NOT NULL,
  `Category_ID` int(11) NOT NULL,
  `Provider_ID` int(11) NOT NULL,
  `Title` varchar(100) DEFAULT NULL,
  `Description` varchar(1024) DEFAULT NULL,
  `Default_Price` double DEFAULT NULL,
  `Price_Type` varchar(50) NOT NULL,
  `Portfolio_Link` varchar(2048) NOT NULL,
  `Price_Negotiability` tinyint(4) NOT NULL DEFAULT 1,
  `Status` varchar(45) NOT NULL DEFAULT 'Active',
  `Total_Earning` double DEFAULT 0,
  `Rating` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `provider_categories`
--

INSERT INTO `provider_categories` (`ID`, `Category_ID`, `Provider_ID`, `Title`, `Description`, `Default_Price`, `Price_Type`, `Portfolio_Link`, `Price_Negotiability`, `Status`, `Total_Earning`, `Rating`) VALUES
(1, 1, 1, 'Software Developing Tasks', 'I am a skilled Software Developer dedicated to delivering high-quality, reliable, and scalable solutions tailored to client needs. I specialize in building efficient applications, fixing bugs, and improving system performance. My goal is to understand your requirements clearly and provide solutions that are both functional and user-friendly. I value clear communication, timely delivery, and long-term client relationships.', 1500, 'Hourly', 'pasindugihan.me', 1, 'Active', 0, 0),
(2, 1, 2, 'Software Developing', 'I', 6767, 'Hourly', '', 1, 'Active', 0, 0),
(3, 1, 3, 'Software Developing', 'Software Developing', 1000, 'Hourly', '', 1, 'Active', 0, 0),
(4, 1, 1, 'Static Website Development', 'Creating fast, lightweight websites using fixed content built with HTML, CSS, and JavaScript. These websites do not rely on databases or server-side processing, making them highly secure, easy to host, and quick to load. They are ideal for portfolios, business landing pages, and informational sites that require minimal updates while delivering a smooth user experience.', 25000, 'Fixed', 'pasindugihan.me', 1, 'Active', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `provider_categories_has_location`
--

CREATE TABLE `provider_categories_has_location` (
  `Provider_Categories_ID` int(11) NOT NULL,
  `Location_Location_ID` int(11) DEFAULT NULL,
  `ID` int(11) NOT NULL,
  `District_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `provider_categories_has_location`
--

INSERT INTO `provider_categories_has_location` (`Provider_Categories_ID`, `Location_Location_ID`, `ID`, `District_ID`) VALUES
(1, 1, 1, NULL),
(2, 1, 2, NULL),
(3, 1, 3, NULL),
(4, 1, 4, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `provider_categories_has_skills`
--

CREATE TABLE `provider_categories_has_skills` (
  `Provider_Categories_ID` int(11) NOT NULL,
  `Skills_Skill_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `provider_categories_has_skills`
--

INSERT INTO `provider_categories_has_skills` (`Provider_Categories_ID`, `Skills_Skill_ID`) VALUES
(1, 1),
(1, 2),
(1, 3),
(2, 4),
(2, 5),
(2, 6),
(2, 7),
(3, 2),
(3, 4),
(3, 6),
(4, 1),
(4, 3),
(4, 4),
(4, 6);

-- --------------------------------------------------------

--
-- Table structure for table `provider_social`
--

CREATE TABLE `provider_social` (
  `Social_ID` int(11) NOT NULL,
  `Social_Type` varchar(50) NOT NULL,
  `Social_Link` varchar(2048) NOT NULL,
  `Provider_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `provider_social`
--

INSERT INTO `provider_social` (`Social_ID`, `Social_Type`, `Social_Link`, `Provider_ID`) VALUES
(1, 'facebook', 'https://www.facebook.com/GihanSamaraweeraX', 1),
(2, 'instagram', '', 1),
(3, 'tiktok', '', 1),
(4, 'youtube', '', 1),
(5, 'github', 'https://github.com/gihan-s', 1),
(6, 'linkedin', 'https://www.linkedin.com/in/gihan-samaraweera/', 1),
(7, 'facebook', '', 2),
(8, 'instagram', '', 2),
(9, 'tiktok', '', 2),
(10, 'youtube', '', 2),
(11, 'github', '', 2),
(12, 'linkedin', '', 2),
(13, 'facebook', '', 3),
(14, 'instagram', '', 3),
(15, 'tiktok', '', 3),
(16, 'youtube', '', 3),
(17, 'github', '', 3),
(18, 'linkedin', '', 3);

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `Report_ID` int(11) NOT NULL,
  `Reason` varchar(100) DEFAULT NULL,
  `Description` varchar(512) DEFAULT NULL,
  `Reported_At` datetime DEFAULT NULL,
  `Status` varchar(45) DEFAULT NULL,
  `Project_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `Review_ID` int(11) NOT NULL,
  `Title` varchar(100) DEFAULT NULL,
  `Description` varchar(512) DEFAULT NULL,
  `Rating` int(11) DEFAULT NULL,
  `Left_At` datetime DEFAULT NULL,
  `Edited_At` datetime DEFAULT NULL,
  `Project_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `selection_options`
--

CREATE TABLE `selection_options` (
  `Option_ID` int(11) NOT NULL,
  `Title` varchar(100) DEFAULT NULL,
  `Value` varchar(100) DEFAULT NULL,
  `Structure_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `Skill_ID` int(11) NOT NULL,
  `Skill` varchar(100) DEFAULT NULL,
  `Category_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`Skill_ID`, `Skill`, `Category_ID`) VALUES
(1, 'React', 1),
(2, 'Laravel', 1),
(3, 'Wordpress', 1),
(4, 'JS', 1),
(5, 'PHP', 1),
(6, 'HTML', 1),
(7, 'Rust', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_log`
--

CREATE TABLE `user_log` (
  `ID` int(11) NOT NULL,
  `Activity` varchar(45) DEFAULT NULL,
  `Description` varchar(100) DEFAULT NULL,
  `Date` datetime DEFAULT NULL,
  `Client_ID` int(11) DEFAULT NULL,
  `User_ID` int(11) DEFAULT NULL,
  `Provider_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`User_ID`);

--
-- Indexes for table `bids`
--
ALTER TABLE `bids`
  ADD PRIMARY KEY (`Bid_ID`),
  ADD KEY `fk_Bids_Post1_idx` (`Post_ID`),
  ADD KEY `fk_Bids_Provider` (`Provider_ID`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`Category_ID`);

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`Client_ID`),
  ADD UNIQUE KEY `Email_UNIQUE` (`Email`);

--
-- Indexes for table `conversation`
--
ALTER TABLE `conversation`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `unique_conversation` (`Provider_ID`,`Client_ID`),
  ADD KEY `Client_ID` (`Client_ID`),
  ADD KEY `Provider_ID` (`Provider_ID`);

--
-- Indexes for table `districts`
--
ALTER TABLE `districts`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `form_structure`
--
ALTER TABLE `form_structure`
  ADD PRIMARY KEY (`Structure_ID`),
  ADD KEY `fk_Form_Structure_Post_Inputs_idx` (`Input_ID`),
  ADD KEY `fk_Form_Structure_Category1_idx` (`Category_ID`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`Location_ID`),
  ADD KEY `District_ID` (`District_ID`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`Message_ID`),
  ADD KEY `Conversation_ID` (`Conversation_ID`);

--
-- Indexes for table `notification_center`
--
ALTER TABLE `notification_center`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`Payment_ID`),
  ADD KEY `fk_Payment_Project1_idx` (`Project_ID`);

--
-- Indexes for table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`Post_ID`),
  ADD KEY `fk_Post_Category1_idx` (`Category_ID`),
  ADD KEY `fk_Post_Client1_idx` (`Client_ID`),
  ADD KEY `fk_Post_Provider1_idx` (`Provider_ID`),
  ADD KEY `fk_Post_Provider_Categories1_idx` (`Provider_Categories_ID`);

--
-- Indexes for table `post_data`
--
ALTER TABLE `post_data`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_Post_Data_Post_Inputs1_idx` (`Input_ID`),
  ADD KEY `fk_Post_Data_Post1_idx` (`Post_ID`);

--
-- Indexes for table `post_inputs`
--
ALTER TABLE `post_inputs`
  ADD PRIMARY KEY (`Input_ID`);

--
-- Indexes for table `post_need_skills`
--
ALTER TABLE `post_need_skills`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Skill_ID` (`Skill_ID`),
  ADD KEY `post_need_skills_ibfk_1` (`Post_ID`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`Project_ID`),
  ADD KEY `fk_Project_Post1_idx` (`Post_ID`);

--
-- Indexes for table `project_requirements`
--
ALTER TABLE `project_requirements`
  ADD PRIMARY KEY (`Requirement_ID`),
  ADD KEY `fk_project` (`Project_ID`);

--
-- Indexes for table `project_update_log`
--
ALTER TABLE `project_update_log`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_Project_Update_Log_Project1_idx` (`Project_ID`);

--
-- Indexes for table `provider`
--
ALTER TABLE `provider`
  ADD PRIMARY KEY (`Provider_ID`),
  ADD UNIQUE KEY `Email_UNIQUE` (`Email`),
  ADD UNIQUE KEY `NIC_No_UNIQUE` (`NIC_No`);

--
-- Indexes for table `provider_categories`
--
ALTER TABLE `provider_categories`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_Provider_Categories_Category1_idx` (`Category_ID`),
  ADD KEY `fk_Provider_Categories_Provider1_idx` (`Provider_ID`);

--
-- Indexes for table `provider_categories_has_location`
--
ALTER TABLE `provider_categories_has_location`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_Provider_Categories_has_Location_Location1_idx` (`Location_Location_ID`),
  ADD KEY `fk_Provider_Categories_has_Location_Provider_Categories1_idx` (`Provider_Categories_ID`),
  ADD KEY `District_ID` (`District_ID`);

--
-- Indexes for table `provider_categories_has_skills`
--
ALTER TABLE `provider_categories_has_skills`
  ADD PRIMARY KEY (`Provider_Categories_ID`,`Skills_Skill_ID`),
  ADD KEY `fk_Provider_Categories_has_Skills_Skills1_idx` (`Skills_Skill_ID`),
  ADD KEY `fk_Provider_Categories_has_Skills_Provider_Categories1_idx` (`Provider_Categories_ID`);

--
-- Indexes for table `provider_social`
--
ALTER TABLE `provider_social`
  ADD PRIMARY KEY (`Social_ID`),
  ADD KEY `fk_Social_Provider` (`Provider_ID`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`Report_ID`),
  ADD KEY `fk_Reviews_Project1_idx` (`Project_ID`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`Review_ID`),
  ADD KEY `fk_Reviews_Project1_idx` (`Project_ID`);

--
-- Indexes for table `selection_options`
--
ALTER TABLE `selection_options`
  ADD PRIMARY KEY (`Option_ID`),
  ADD KEY `fk_Selection_Options_Form_Structure1_idx` (`Structure_ID`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`Skill_ID`),
  ADD UNIQUE KEY `Skill` (`Skill`);

--
-- Indexes for table `user_log`
--
ALTER TABLE `user_log`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_User_Log_Client1_idx` (`Client_ID`),
  ADD KEY `fk_User_Log_Admin1_idx` (`User_ID`),
  ADD KEY `fk_User_Log_Provider1_idx` (`Provider_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
  MODIFY `Client_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `conversation`
--
ALTER TABLE `conversation`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=687;

--
-- AUTO_INCREMENT for table `districts`
--
ALTER TABLE `districts`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `Message_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=378;

--
-- AUTO_INCREMENT for table `notification_center`
--
ALTER TABLE `notification_center`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `Post_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT for table `post_need_skills`
--
ALTER TABLE `post_need_skills`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `project_requirements`
--
ALTER TABLE `project_requirements`
  MODIFY `Requirement_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_update_log`
--
ALTER TABLE `project_update_log`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `provider`
--
ALTER TABLE `provider`
  MODIFY `Provider_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `provider_categories`
--
ALTER TABLE `provider_categories`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `provider_categories_has_location`
--
ALTER TABLE `provider_categories_has_location`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `provider_social`
--
ALTER TABLE `provider_social`
  MODIFY `Social_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `Skill_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bids`
--
ALTER TABLE `bids`
  ADD CONSTRAINT `fk_Bids_Post1` FOREIGN KEY (`Post_ID`) REFERENCES `post` (`Post_ID`),
  ADD CONSTRAINT `fk_Bids_Provider` FOREIGN KEY (`Provider_ID`) REFERENCES `provider` (`Provider_ID`);

--
-- Constraints for table `conversation`
--
ALTER TABLE `conversation`
  ADD CONSTRAINT `conversation_ibfk_1` FOREIGN KEY (`Client_ID`) REFERENCES `client` (`Client_ID`),
  ADD CONSTRAINT `conversation_ibfk_2` FOREIGN KEY (`Provider_ID`) REFERENCES `provider` (`Provider_ID`);

--
-- Constraints for table `form_structure`
--
ALTER TABLE `form_structure`
  ADD CONSTRAINT `fk_Form_Structure_Category1` FOREIGN KEY (`Category_ID`) REFERENCES `category` (`Category_ID`),
  ADD CONSTRAINT `fk_Form_Structure_Post_Inputs` FOREIGN KEY (`Input_ID`) REFERENCES `post_inputs` (`Input_ID`);

--
-- Constraints for table `location`
--
ALTER TABLE `location`
  ADD CONSTRAINT `location_ibfk_1` FOREIGN KEY (`District_ID`) REFERENCES `districts` (`ID`);

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`Conversation_ID`) REFERENCES `conversation` (`ID`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `fk_Payment_Project1` FOREIGN KEY (`Project_ID`) REFERENCES `project` (`Project_ID`);

--
-- Constraints for table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `fk_Post_Category1` FOREIGN KEY (`Category_ID`) REFERENCES `category` (`Category_ID`),
  ADD CONSTRAINT `fk_Post_Client1` FOREIGN KEY (`Client_ID`) REFERENCES `client` (`Client_ID`),
  ADD CONSTRAINT `fk_Post_Provider1` FOREIGN KEY (`Provider_ID`) REFERENCES `provider` (`Provider_ID`),
  ADD CONSTRAINT `fk_Post_ProviderCategories1` FOREIGN KEY (`Provider_Categories_ID`) REFERENCES `provider_categories` (`ID`);

--
-- Constraints for table `post_data`
--
ALTER TABLE `post_data`
  ADD CONSTRAINT `fk_Post_Data_Post1` FOREIGN KEY (`Post_ID`) REFERENCES `post` (`Post_ID`),
  ADD CONSTRAINT `fk_Post_Data_Post_Inputs1` FOREIGN KEY (`Input_ID`) REFERENCES `post_inputs` (`Input_ID`);

--
-- Constraints for table `post_need_skills`
--
ALTER TABLE `post_need_skills`
  ADD CONSTRAINT `post_need_skills_ibfk_1` FOREIGN KEY (`Post_ID`) REFERENCES `post` (`Post_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `post_need_skills_ibfk_2` FOREIGN KEY (`Skill_ID`) REFERENCES `skills` (`Skill_ID`);

--
-- Constraints for table `project`
--
ALTER TABLE `project`
  ADD CONSTRAINT `fk_Project_Post1` FOREIGN KEY (`Post_ID`) REFERENCES `post` (`Post_ID`);

--
-- Constraints for table `project_requirements`
--
ALTER TABLE `project_requirements`
  ADD CONSTRAINT `fk_project` FOREIGN KEY (`Project_ID`) REFERENCES `project` (`Project_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `project_update_log`
--
ALTER TABLE `project_update_log`
  ADD CONSTRAINT `fk_Project_Update_Log_Project1` FOREIGN KEY (`Project_ID`) REFERENCES `project` (`Project_ID`);

--
-- Constraints for table `provider_categories`
--
ALTER TABLE `provider_categories`
  ADD CONSTRAINT `fk_Provider_Categories_Category1` FOREIGN KEY (`Category_ID`) REFERENCES `category` (`Category_ID`),
  ADD CONSTRAINT `fk_Provider_Categories_Provider1` FOREIGN KEY (`Provider_ID`) REFERENCES `provider` (`Provider_ID`);

--
-- Constraints for table `provider_categories_has_location`
--
ALTER TABLE `provider_categories_has_location`
  ADD CONSTRAINT `fk_Provider_Categories_has_Location_Location1` FOREIGN KEY (`Location_Location_ID`) REFERENCES `location` (`Location_ID`),
  ADD CONSTRAINT `fk_Provider_Categories_has_Location_Provider_Categories1` FOREIGN KEY (`Provider_Categories_ID`) REFERENCES `provider_categories` (`ID`),
  ADD CONSTRAINT `provider_categories_has_location_ibfk_1` FOREIGN KEY (`District_ID`) REFERENCES `districts` (`ID`);

--
-- Constraints for table `provider_categories_has_skills`
--
ALTER TABLE `provider_categories_has_skills`
  ADD CONSTRAINT `fk_Provider_Categories_has_Skills_Provider_Categories1` FOREIGN KEY (`Provider_Categories_ID`) REFERENCES `provider_categories` (`ID`),
  ADD CONSTRAINT `fk_Provider_Categories_has_Skills_Skills1` FOREIGN KEY (`Skills_Skill_ID`) REFERENCES `skills` (`Skill_ID`);

--
-- Constraints for table `provider_social`
--
ALTER TABLE `provider_social`
  ADD CONSTRAINT `fk_Social_Provider` FOREIGN KEY (`Provider_ID`) REFERENCES `provider` (`Provider_ID`);

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `fk_Reviews_Project10` FOREIGN KEY (`Project_ID`) REFERENCES `project` (`Project_ID`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_Reviews_Project1` FOREIGN KEY (`Project_ID`) REFERENCES `project` (`Project_ID`);

--
-- Constraints for table `selection_options`
--
ALTER TABLE `selection_options`
  ADD CONSTRAINT `fk_Selection_Options_Form_Structure1` FOREIGN KEY (`Structure_ID`) REFERENCES `form_structure` (`Structure_ID`);

--
-- Constraints for table `user_log`
--
ALTER TABLE `user_log`
  ADD CONSTRAINT `fk_User_Log_Admin1` FOREIGN KEY (`User_ID`) REFERENCES `admin` (`User_ID`),
  ADD CONSTRAINT `fk_User_Log_Client1` FOREIGN KEY (`Client_ID`) REFERENCES `client` (`Client_ID`),
  ADD CONSTRAINT `fk_User_Log_Provider1` FOREIGN KEY (`Provider_ID`) REFERENCES `provider` (`Provider_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
