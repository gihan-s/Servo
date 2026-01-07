-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 04, 2026 at 11:03 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.5.1

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bids`
--

CREATE TABLE `bids` (
  `Bid_ID` int(11) NOT NULL,
  `Comment` varchar(256) DEFAULT NULL,
  `Amount` double DEFAULT NULL,
  `Created_At` datetime DEFAULT NULL,
  `Est_Date` datetime DEFAULT NULL,
  `Status` varchar(45) DEFAULT NULL,
  `Post_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `Category_ID` int(11) NOT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Description` varchar(100) DEFAULT NULL,
  `Icon` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`Category_ID`, `Name`, `Description`, `Icon`) VALUES
(1, 'Graphic Designer', NULL, NULL),
(2, 'Plumber', NULL, NULL);

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
  `Status` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`Client_ID`, `Email`, `Contact_No`, `Password`, `Created_At`, `First_Name`, `Last_Name`, `Gender`, `Profile_Picture`, `Social_Link`, `Bio`, `Status`) VALUES
(1, 'Chethiya@gmail.com', '0782332537', '$2y$10$C4LiFSr3iw8FXCeER19hl.WttFvF36BOOCUbK.Bmc2c13o3M8lUNC', '2025-09-11 17:59:11', 'P.G.Chethiya', 'Bandara', 'Male', '/uploads/clients/0/profile_1757606351.jpg', '', '', NULL),
(2, 'bhagyasithu2003@gmail.com', '0782332537', '$2y$10$3xNt6kvuvFKFXFAJNNYGyOztuTbpWjDLt2xPHy.km.p5fltP7um2G', '2025-09-11 21:57:13', 'Bhagya', 'Sithumini', 'Female', '/uploads/clients/2/profile_1757620633.jpg', 'good.com', 'Im the godder one for you', NULL),
(3, 'vccchethiyabandara2@gmail.com', '0781095685', '$2y$10$Kvjndd1e5jz/LLwvnTfOYuidL0qRc3ryH43cyztVCwi/1S0az9xR6', '2025-10-20 19:31:07', 'Bhagya', 'Sithumini', 'Female', '68f6409103558_waving_graphic.png', 'bhagya.com', 'GG for me this works', 'active'),
(4, 'cb@gmail.com', '0782332537', '$2y$10$QXMOiBmQ8R7BIUTc.ECE7u34m3Mxu7ADMwoaVbCyFIbHj671v7O6C', '2025-10-22 11:37:50', 'Chethiya', 'Bandara', 'Male', '68f874a964d7b_waving_graphic.bmp', 'weqeq', 'ewqeszx zcxzczx', 'active');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `districts`
--

CREATE TABLE `districts` (
  `ID` int(11) NOT NULL,
  `District` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `districts`
--

INSERT INTO `districts` (`ID`, `District`) VALUES
(1, 'Gampaha'),
(2, 'Kandy');

-- --------------------------------------------------------

--
-- Table structure for table `form_structure`
--

CREATE TABLE `form_structure` (
  `Structure_ID` int(11) NOT NULL,
  `Input_ID` int(11) NOT NULL,
  `Category_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `Location_ID` int(11) NOT NULL,
  `District_ID` int(45) DEFAULT NULL,
  `City` varchar(45) DEFAULT NULL,
  `District` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`Location_ID`, `District_ID`, `City`, `District`) VALUES
(1, 1, 'Mirigama', ''),
(2, 2, 'Kadugannawa', '');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `Message_ID` int(11) NOT NULL,
  `Content` text DEFAULT NULL,
  `Is_Client_To_Provider` tinyint(4) DEFAULT NULL,
  `Sent_At` datetime DEFAULT NULL,
  `Delivered_At` datetime DEFAULT NULL,
  `Read_At` datetime DEFAULT NULL,
  `Status` varchar(45) DEFAULT NULL,
  `Conversation_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
  `Title` varchar(100) DEFAULT NULL,
  `Description` varchar(2048) DEFAULT NULL,
  `Requesting_Price` double DEFAULT NULL,
  `Price_Type` varchar(25) NOT NULL,
  `Duration` varchar(50) NOT NULL,
  `Level` varchar(30) NOT NULL,
  `End_At` date NOT NULL,
  `Published_At` datetime NOT NULL,
  `Duration_Type` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`Post_ID`, `Created_At`, `Category_ID`, `Post_Type`, `Post_Status`, `Client_ID`, `Provider_ID`, `Title`, `Description`, `Requesting_Price`, `Price_Type`, `Duration`, `Level`, `End_At`, `Published_At`, `Duration_Type`) VALUES
(1, '2025-10-01 09:15:00', 1, 'Job', 'active', 4, 3, 'Website Redesign', 'I’m a passionate Web Designer and Developer with a strong focus on creating visually appealing, responsive, and user-friendly websites. With a background in Computer Science and hands-on experience using HTML, CSS, JavaScript, PHP, and MySQL, I build sites that are both functional and beautifully designed.\n\nI specialize in crafting clean, modern interfaces that adapt perfectly to all screen sizes — from desktops to smartphones. Whether it’s a portfolio, business site, eCommerce store, or booking platform, I handle everything from front-end design to back-end logic using the MVC architecture.\n\nHaving worked closely with real businesses, I understand the importance of blending creativity with results — ensuring every project not only looks great but also helps my clients attract customers and grow their brand online.', 450, 'Fixed', '2', 'Intermediate', '2025-11-05', '2025-10-14 00:00:00', ''),
(2, '2025-10-02 10:40:00', 1, 'Task', 'active', 4, 5, 'Logo Creation', 'Looking for a unique logo for a clothing brand.', 120, 'Fixed', '3', 'Beginner', '2025-10-26', '2025-10-14 17:41:40', ''),
(3, '2025-10-04 11:05:00', 1, 'Gig', 'expired', 4, 2, 'SEO Optimization', 'Need on-page SEO for a WordPress site.', 300, 'Hourly', '1 week', 'Intermediate', '2025-10-29', '2025-10-20 17:42:32', ''),
(4, '2025-10-05 12:10:00', 1, 'Job', 'active', 4, 4, 'Mobile App Prototype', 'Design a clickable prototype for a finance app.', 600, 'Fixed', '10 days', 'Advanced', '2025-11-01', '2025-10-12 17:42:37', ''),
(5, '2025-10-03 13:45:00', 1, 'Task', 'draft', 4, 6, 'Bug Fixing', 'Fix UI bugs and minor API issues in dashboard.', 200, 'Hourly', '5 days', 'Intermediate', '2025-10-28', '2025-10-15 17:42:51', ''),
(6, '2025-10-22 14:20:00', 1, 'Gig', 'draft', 4, 8, 'Data Entry Work', 'Need accurate entry of product listings.', 80, 'Fixed', '1 week', 'Beginner', '2025-10-29', '0000-00-00 00:00:00', ''),
(7, '2025-10-22 15:10:00', 1, 'Job', 'active', 4, 9, 'Landing Page Design', 'Design a responsive landing page for a SaaS.', 250, 'Fixed', '4 days', 'Intermediate', '2025-10-26', '0000-00-00 00:00:00', ''),
(8, '2025-10-22 15:45:00', 1, 'Task', 'expired', 4, 7, 'API Integration', 'Integrate payment gateway with backend.', 350, 'Hourly', '1 week', 'Advanced', '2025-10-30', '0000-00-00 00:00:00', ''),
(9, '2025-10-22 16:30:00', 1, 'Gig', 'expired', 4, 10, 'Database Cleanup', 'Normalize and clean up redundant database entries.', 150, 'Fixed', '3 days', 'Intermediate', '2025-10-25', '0000-00-00 00:00:00', ''),
(10, '2025-10-22 17:00:00', 1, 'Job', 'draft', 4, 11, 'Content Writing', 'Write 5 SEO-friendly blog posts.', 100, 'Fixed', '1 week', 'Beginner', '2025-10-29', '0000-00-00 00:00:00', ''),
(11, '2025-10-22 22:36:28', 1, NULL, 'draft', 4, NULL, 'csad', 'sdadasd', 321, 'Hourly', '32', 'Intermediate', '2025-10-23', '0000-00-00 00:00:00', 'Days'),
(12, '2025-10-22 22:37:20', 1, NULL, 'draft', 4, NULL, 'csad', 'sdadasd', 321, 'Hourly', '32', 'Intermediate', '2025-10-23', '0000-00-00 00:00:00', 'Days'),
(13, '2025-10-22 22:42:17', 1, 'post', 'active', 4, NULL, 'Professional Web Designer for Modern, Responsive, and SEO-Optimized Websites', 'I’m a creative and detail-oriented Web Designer with experience in building clean, responsive, and visually appealing websites using HTML, CSS, JavaScript, PHP, and MySQL.\r\n\r\nI design and develop websites that not only look professional but also perform efficiently across all devices. Whether you need a personal portfolio, a business website, or a complete online store, I can deliver a full solution — from layout design to final deployment.\r\n\r\nWhat I Offer:\r\n• Custom website design (HTML/CSS/JS/PHP)\r\n• Mobile-friendly and SEO-optimized pages\r\n• Website redesigns and UI/UX improvements\r\n• Fast loading speed and clean code\r\n• Integration with forms, databases, or APIs\r\n\r\nLet’s work together to turn your vision into a professional, user-friendly website that stands out online', 200, 'Hourly', '4', 'Intermediate', '2025-10-30', '2025-10-22 22:42:17', 'Months'),
(14, '2025-10-22 22:51:22', 1, 'post', 'active', 4, NULL, 'Professional Web Designer for Modern, Responsive, and SEO-Optimized Websites', 'I’m a creative and detail-oriented Web Designer with experience in building clean, responsive, and visually appealing websites using HTML, CSS, JavaScript, PHP, and MySQL.\r\n\r\nI design and develop websites that not only look professional but also perform efficiently across all devices. Whether you need a personal portfolio, a business website, or a complete online store, I can deliver a full solution — from layout design to final deployment.\r\n\r\nWhat I Offer:\r\n• Custom website design (HTML/CSS/JS/PHP)\r\n• Mobile-friendly and SEO-optimized pages\r\n• Website redesigns and UI/UX improvements\r\n• Fast loading speed and clean code\r\n• Integration with forms, databases, or APIs\r\n\r\nLet’s work together to turn your vision into a professional, user-friendly website that stands out online.', 200, 'Hourly', '12', 'Intermediate', '2025-10-30', '2025-10-22 22:51:22', 'Weeks'),
(15, '2025-10-22 22:52:16', 1, 'post', 'active', 4, NULL, 'Professional Web Designer for Modern, Responsive, and SEO-Optimized Websites', 'I’m a creative and detail-oriented Web Designer with experience in building clean, responsive, and visually appealing websites using HTML, CSS, JavaScript, PHP, and MySQL.\r\n\r\nI design and develop websites that not only look professional but also perform efficiently across all devices. Whether you need a personal portfolio, a business website, or a complete online store, I can deliver a full solution — from layout design to final deployment.\r\n\r\nWhat I Offer:\r\n• Custom website design (HTML/CSS/JS/PHP)\r\n• Mobile-friendly and SEO-optimized pages\r\n• Website redesigns and UI/UX improvements\r\n• Fast loading speed and clean code\r\n• Integration with forms, databases, or APIs\r\n\r\nLet’s work together to turn your vision into a professional, user-friendly website that stands out online.', 200, 'Hourly', '12', 'Intermediate', '2025-10-30', '2025-10-22 22:52:16', 'Weeks'),
(16, '2025-10-22 22:52:25', 1, 'post', 'active', 4, NULL, 'Professional Web Designer for Modern, Responsive, and SEO-Optimized Websites', 'I’m a creative and detail-oriented Web Designer with experience in building clean, responsive, and visually appealing websites using HTML, CSS, JavaScript, PHP, and MySQL.\r\n\r\nI design and develop websites that not only look professional but also perform efficiently across all devices. Whether you need a personal portfolio, a business website, or a complete online store, I can deliver a full solution — from layout design to final deployment.\r\n\r\nWhat I Offer:\r\n• Custom website design (HTML/CSS/JS/PHP)\r\n• Mobile-friendly and SEO-optimized pages\r\n• Website redesigns and UI/UX improvements\r\n• Fast loading speed and clean code\r\n• Integration with forms, databases, or APIs\r\n\r\nLet’s work together to turn your vision into a professional, user-friendly website that stands out online.', 200, 'Hourly', '12', 'Intermediate', '2025-10-30', '2025-10-22 22:52:25', 'Weeks'),
(17, '2025-10-22 22:53:22', 1, 'post', 'active', 4, NULL, 'Im in', 'I’m a creative and detail-oriented Web Designer with experience in building clean, responsive, and visually appealing websites using HTML, CSS, JavaScript, PHP, and MySQL.\r\n\r\nI design and develop websites that not only look professional but also perform efficiently across all devices. Whether you need a personal portfolio, a business website, or a complete online store, I can deliver a full solution — from layout design to final deployment.\r\n\r\nWhat I Offer:\r\n• Custom website design (HTML/CSS/JS/PHP)\r\n• Mobile-friendly and SEO-optimized pages\r\n• Website redesigns and UI/UX improvements\r\n• Fast loading speed and clean code\r\n• Integration with forms, databases, or APIs\r\n\r\nLet’s work together to turn your vision into a professional, user-friendly website that stands out online.', 23, 'Fixed', '12', 'Intermediate', '0000-00-00', '2025-10-22 22:53:22', 'Days'),
(18, '2025-10-22 22:54:33', 1, 'post', 'active', 4, NULL, 'Im in', 'I’m a creative and detail-oriented Web Designer with experience in building clean, responsive, and visually appealing websites using HTML, CSS, JavaScript, PHP, and MySQL.\r\n\r\nI design and develop websites that not only look professional but also perform efficiently across all devices. Whether you need a personal portfolio, a business website, or a complete online store, I can deliver a full solution — from layout design to final deployment.\r\n\r\nWhat I Offer:\r\n• Custom website design (HTML/CSS/JS/PHP)\r\n• Mobile-friendly and SEO-optimized pages\r\n• Website redesigns and UI/UX improvements\r\n• Fast loading speed and clean code\r\n• Integration with forms, databases, or APIs\r\n\r\nLet’s work together to turn your vision into a professional, user-friendly website that stands out online.', 23, 'Fixed', '12', 'Intermediate', '0000-00-00', '2025-10-22 22:54:33', 'Days');

-- --------------------------------------------------------

--
-- Table structure for table `post_data`
--

CREATE TABLE `post_data` (
  `ID` int(11) NOT NULL,
  `Input_ID` int(11) NOT NULL,
  `Value` varchar(2048) DEFAULT NULL,
  `Post_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post_need_skills`
--

CREATE TABLE `post_need_skills` (
  `ID` int(11) NOT NULL,
  `Skill_ID` int(11) NOT NULL,
  `Post_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post_need_skills`
--

INSERT INTO `post_need_skills` (`ID`, `Skill_ID`, `Post_ID`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 3, 1),
(4, 4, 1),
(5, 3, 18),
(6, 1, 18);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_update_log`
--

CREATE TABLE `project_update_log` (
  `ID` int(11) NOT NULL,
  `Title` varchar(45) DEFAULT NULL,
  `Description` varchar(256) DEFAULT NULL,
  `Date` datetime DEFAULT NULL,
  `Project_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
  `Status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `provider`
--

INSERT INTO `provider` (`Provider_ID`, `Email`, `Contact_No`, `NIC_No`, `Password`, `Created_At`, `First_Name`, `Last_Name`, `Gender`, `Profile_Picture`, `Bio`, `NIC_Front`, `NIC_Back`, `Resume`, `Website`, `Status`) VALUES
(4, 'Chethiya@gmail.com', '0782332537', '200335900739', '$2y$10$TfS.1w9aDzfZV5wsuuY12uGtz5C0qqe3o8VsFHkH5xhZnlGYwhKTW', '2025-09-12 01:14:07', 'P.G.Chethiya', 'Bandara', 'Male', '/uploads/providers/4/profile_1757619847.jpg', '', '/uploads/providers/4/nic_front_1757619847.jpg', '/uploads/providers/4/nic_back_1757619847.jpg', NULL, '', 'pending'),
(5, 'Bagya@gmail.com', '0782332537', '200335900738', '$2y$10$x3CYSC8ac2ic.RQvuMTwe.jHkWZ6meNzPkrIp0xHkoKrJGlPkif/y', '2025-09-12 01:23:55', 'P.G.Chethiya', 'Bandara', 'Male', '/uploads/providers/5/profile_1757620435.jpg', '', '/uploads/providers/5/nic_front_1757620435.jpg', '/uploads/providers/5/nic_back_1757620435.jpg', NULL, '', 'pending'),
(6, 'himath@gmail.com', '0782332537', '200335900731', '$2y$10$lz6PfkrZLTTquxMa2GHJIOGBxDdtGFHLL9ZfYcLVhxX0MHzASj5lO', '2025-09-12 12:52:57', 'Himath', 'Adithya', 'Male', '/uploads/providers/6/profile_1757661777.jpg', 'Mama malak.....', '/uploads/providers/6/nic_front_1757661777.jpg', '/uploads/providers/6/nic_back_1757661777.jpg', NULL, 'himath.malkakula.lk', 'pending');

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
  `Default_Price` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `provider_categories`
--

INSERT INTO `provider_categories` (`ID`, `Category_ID`, `Provider_ID`, `Title`, `Description`, `Default_Price`) VALUES
(1, 1, 4, 'fafdad', 'dasdasd', NULL),
(2, 2, 4, 'dsada', 'sdad', NULL),
(3, 1, 5, 'feec', 'fxzcxz', NULL),
(4, 2, 5, 'dada', 'dsadsa', NULL),
(5, 1, 6, 'Kari Designer', 'Gammata gahala dennam (Athe)', 2000),
(6, 2, 6, 'Kari Bas', 'bata bassala dennam athulatama', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `provider_categories_has_location`
--

CREATE TABLE `provider_categories_has_location` (
  `Provider_Categories_ID` int(11) NOT NULL,
  `Location_Location_ID` int(11) DEFAULT NULL,
  `ID` int(11) NOT NULL,
  `District_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `provider_categories_has_location`
--

INSERT INTO `provider_categories_has_location` (`Provider_Categories_ID`, `Location_Location_ID`, `ID`, `District_ID`) VALUES
(1, NULL, 1, 1),
(2, NULL, 2, 2),
(2, 1, 3, NULL),
(3, 1, 4, 1),
(4, 1, 5, 1),
(5, 1, 6, 1),
(5, 2, 7, 2),
(6, 2, 8, 2),
(6, 1, 9, 1);

-- --------------------------------------------------------

--
-- Table structure for table `provider_categories_has_skills`
--

CREATE TABLE `provider_categories_has_skills` (
  `Provider_Categories_ID` int(11) NOT NULL,
  `Skills_Skill_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `provider_categories_has_skills`
--

INSERT INTO `provider_categories_has_skills` (`Provider_Categories_ID`, `Skills_Skill_ID`) VALUES
(1, 1),
(2, 2),
(3, 1),
(3, 2),
(4, 1),
(4, 2),
(5, 1),
(6, 2);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `selection_options`
--

CREATE TABLE `selection_options` (
  `Option_ID` int(11) NOT NULL,
  `Title` varchar(100) DEFAULT NULL,
  `Value` varchar(100) DEFAULT NULL,
  `Structure_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `Skill_ID` int(11) NOT NULL,
  `Skill` varchar(100) DEFAULT NULL,
  `Category_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`Skill_ID`, `Skill`, `Category_ID`) VALUES
(1, 'Logo Designing', 1),
(2, 'Water Plumbing', 2),
(3, 'Graphic Card', 1),
(4, 'Himathta gasima', 1);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
  ADD KEY `fk_Bids_Post1_idx` (`Post_ID`);

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
  ADD KEY `fk_Post_Provider1_idx` (`Provider_ID`);

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
  ADD KEY `Post_ID` (`Post_ID`),
  ADD KEY `Skill_ID` (`Skill_ID`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`Project_ID`),
  ADD KEY `fk_Project_Post1_idx` (`Post_ID`);

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
  ADD PRIMARY KEY (`Skill_ID`);

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
  MODIFY `Client_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `conversation`
--
ALTER TABLE `conversation`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `districts`
--
ALTER TABLE `districts`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `notification_center`
--
ALTER TABLE `notification_center`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `Post_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `post_need_skills`
--
ALTER TABLE `post_need_skills`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `provider`
--
ALTER TABLE `provider`
  MODIFY `Provider_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bids`
--
ALTER TABLE `bids`
  ADD CONSTRAINT `fk_Bids_Post1` FOREIGN KEY (`Post_ID`) REFERENCES `post` (`Post_ID`);

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
  ADD CONSTRAINT `fk_Post_Provider1` FOREIGN KEY (`Provider_ID`) REFERENCES `provider` (`Provider_ID`);

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
  ADD CONSTRAINT `post_need_skills_ibfk_1` FOREIGN KEY (`Post_ID`) REFERENCES `post` (`Post_ID`),
  ADD CONSTRAINT `post_need_skills_ibfk_2` FOREIGN KEY (`Skill_ID`) REFERENCES `skills` (`Skill_ID`);

--
-- Constraints for table `project`
--
ALTER TABLE `project`
  ADD CONSTRAINT `fk_Project_Post1` FOREIGN KEY (`Post_ID`) REFERENCES `post` (`Post_ID`);

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
