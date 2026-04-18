CREATE DATABASE  IF NOT EXISTS `servo` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `servo`;
-- MySQL dump 10.13  Distrib 8.0.40, for Win64 (x86_64)
--
-- Host: 74.208.174.246    Database: servo
-- ------------------------------------------------------
-- Server version	8.0.45-0ubuntu0.24.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin` (
  `User_ID` int NOT NULL,
  `Username` varchar(45) DEFAULT NULL,
  `Password` varchar(512) DEFAULT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Access_Level` varchar(45) DEFAULT NULL,
  `Status` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`User_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'gihan','$2a$12$v6hOJEI9GsipSzziyvebzejCRlXob4qVU7TlJk3TnF/mEBCKU/pbO','Gihan','Admin','Active');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bids`
--

DROP TABLE IF EXISTS `bids`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bids` (
  `Bid_ID` int NOT NULL,
  `Comment` varchar(256) DEFAULT NULL,
  `Amount` double DEFAULT NULL,
  `Created_At` datetime DEFAULT NULL,
  `Duration` datetime DEFAULT NULL,
  `Status` varchar(45) DEFAULT NULL,
  `Post_ID` int NOT NULL,
  `Provider_ID` int NOT NULL,
  PRIMARY KEY (`Bid_ID`),
  KEY `fk_Bids_Post1_idx` (`Post_ID`),
  KEY `fk_Bids_Provider` (`Provider_ID`),
  CONSTRAINT `fk_Bids_Post1` FOREIGN KEY (`Post_ID`) REFERENCES `post` (`Post_ID`),
  CONSTRAINT `fk_Bids_Provider` FOREIGN KEY (`Provider_ID`) REFERENCES `provider` (`Provider_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bids`
--

LOCK TABLES `bids` WRITE;
/*!40000 ALTER TABLE `bids` DISABLE KEYS */;
/*!40000 ALTER TABLE `bids` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `category` (
  `Category_ID` int NOT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Description` varchar(100) DEFAULT NULL,
  `Icon` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`Category_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES (1,'Software Developing',NULL,NULL);
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client`
--

DROP TABLE IF EXISTS `client`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `client` (
  `Client_ID` int NOT NULL AUTO_INCREMENT,
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
  `Is_Online` tinyint(1) DEFAULT '0',
  `Last_Seen` datetime DEFAULT NULL,
  PRIMARY KEY (`Client_ID`),
  UNIQUE KEY `Email_UNIQUE` (`Email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client`
--

LOCK TABLES `client` WRITE;
/*!40000 ALTER TABLE `client` DISABLE KEYS */;
/*!40000 ALTER TABLE `client` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `conversation`
--

DROP TABLE IF EXISTS `conversation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `conversation` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Provider_ID` int NOT NULL,
  `Client_ID` int NOT NULL,
  `Is_Starred_By_Client` int NOT NULL,
  `Is_Starred_By_Provider` int NOT NULL,
  `Is_Archived_By_Client` int NOT NULL,
  `Is_Archived_By_Provider` int NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `unique_conversation` (`Provider_ID`,`Client_ID`),
  KEY `Client_ID` (`Client_ID`),
  KEY `Provider_ID` (`Provider_ID`),
  CONSTRAINT `conversation_ibfk_1` FOREIGN KEY (`Client_ID`) REFERENCES `client` (`Client_ID`),
  CONSTRAINT `conversation_ibfk_2` FOREIGN KEY (`Provider_ID`) REFERENCES `provider` (`Provider_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conversation`
--

LOCK TABLES `conversation` WRITE;
/*!40000 ALTER TABLE `conversation` DISABLE KEYS */;
/*!40000 ALTER TABLE `conversation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `districts`
--

DROP TABLE IF EXISTS `districts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `districts` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `District` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `districts`
--

LOCK TABLES `districts` WRITE;
/*!40000 ALTER TABLE `districts` DISABLE KEYS */;
/*!40000 ALTER TABLE `districts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `form_structure`
--

DROP TABLE IF EXISTS `form_structure`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `form_structure` (
  `Structure_ID` int NOT NULL,
  `Input_ID` int NOT NULL,
  `Category_ID` int NOT NULL,
  PRIMARY KEY (`Structure_ID`),
  KEY `fk_Form_Structure_Post_Inputs_idx` (`Input_ID`),
  KEY `fk_Form_Structure_Category1_idx` (`Category_ID`),
  CONSTRAINT `fk_Form_Structure_Category1` FOREIGN KEY (`Category_ID`) REFERENCES `category` (`Category_ID`),
  CONSTRAINT `fk_Form_Structure_Post_Inputs` FOREIGN KEY (`Input_ID`) REFERENCES `post_inputs` (`Input_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `form_structure`
--

LOCK TABLES `form_structure` WRITE;
/*!40000 ALTER TABLE `form_structure` DISABLE KEYS */;
/*!40000 ALTER TABLE `form_structure` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `location`
--

DROP TABLE IF EXISTS `location`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `location` (
  `Location_ID` int NOT NULL,
  `District_ID` int DEFAULT NULL,
  `City` varchar(45) DEFAULT NULL,
  `District` varchar(50) NOT NULL,
  PRIMARY KEY (`Location_ID`),
  KEY `District_ID` (`District_ID`),
  CONSTRAINT `location_ibfk_1` FOREIGN KEY (`District_ID`) REFERENCES `districts` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `location`
--

LOCK TABLES `location` WRITE;
/*!40000 ALTER TABLE `location` DISABLE KEYS */;
INSERT INTO `location` VALUES (1,NULL,'All','All'),(2,NULL,'All','Gampaha'),(3,NULL,'Mirigama','Gampaha'),(4,NULL,'Veyangoda','Gampaha'),(5,NULL,'Gampaha Town','Gampaha'),(6,NULL,'All','Colombo'),(7,NULL,'Rajagiriya','Colombo'),(8,NULL,'Colombo 01','Colombo'),(9,NULL,'Colombo 02','Colombo'),(10,NULL,'Colombo 03','Colombo'),(11,NULL,'Colombo 04','Colombo');
/*!40000 ALTER TABLE `location` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messages` (
  `Message_ID` int NOT NULL AUTO_INCREMENT,
  `Content` text,
  `Is_Client_To_Provider` tinyint DEFAULT NULL,
  `Sent_At` datetime DEFAULT NULL,
  `Delivered_At` datetime DEFAULT NULL,
  `Read_At` datetime DEFAULT NULL,
  `Status` varchar(45) DEFAULT NULL,
  `Conversation_ID` int NOT NULL,
  PRIMARY KEY (`Message_ID`),
  KEY `Conversation_ID` (`Conversation_ID`),
  CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`Conversation_ID`) REFERENCES `conversation` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notification_center`
--

DROP TABLE IF EXISTS `notification_center`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notification_center` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Generated_Time` int NOT NULL,
  `Title` int NOT NULL,
  `Relevent_Section` int NOT NULL,
  `Is_Readed` int NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notification_center`
--

LOCK TABLES `notification_center` WRITE;
/*!40000 ALTER TABLE `notification_center` DISABLE KEYS */;
/*!40000 ALTER TABLE `notification_center` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment`
--

DROP TABLE IF EXISTS `payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment` (
  `Payment_ID` int NOT NULL,
  `Amount` double DEFAULT NULL,
  `Status` varchar(45) DEFAULT NULL,
  `Hold_Time` datetime DEFAULT NULL,
  `Paid_Time` datetime DEFAULT NULL,
  `Commission` double DEFAULT NULL,
  `Project_ID` int NOT NULL,
  PRIMARY KEY (`Payment_ID`),
  KEY `fk_Payment_Project1_idx` (`Project_ID`),
  CONSTRAINT `fk_Payment_Project1` FOREIGN KEY (`Project_ID`) REFERENCES `project` (`Project_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment`
--

LOCK TABLES `payment` WRITE;
/*!40000 ALTER TABLE `payment` DISABLE KEYS */;
/*!40000 ALTER TABLE `payment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post`
--

DROP TABLE IF EXISTS `post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `post` (
  `Post_ID` int NOT NULL AUTO_INCREMENT,
  `Created_At` datetime DEFAULT NULL,
  `Category_ID` int NOT NULL,
  `Post_Type` varchar(45) DEFAULT NULL,
  `Post_Status` varchar(45) DEFAULT NULL,
  `Client_ID` int NOT NULL,
  `Provider_ID` int DEFAULT NULL,
  `Provider_Categories_ID` int DEFAULT NULL,
  `Title` varchar(100) DEFAULT NULL,
  `Description` varchar(2048) DEFAULT NULL,
  `Requesting_Price` double DEFAULT NULL,
  `Price_Type` varchar(25) NOT NULL,
  `Level` varchar(30) NOT NULL,
  `End_At` date DEFAULT NULL,
  `Published_At` datetime DEFAULT NULL,
  `Est_Date` date NOT NULL,
  `Request_Status` varchar(15) NOT NULL,
  PRIMARY KEY (`Post_ID`),
  KEY `fk_Post_Category1_idx` (`Category_ID`),
  KEY `fk_Post_Client1_idx` (`Client_ID`),
  KEY `fk_Post_Provider1_idx` (`Provider_ID`),
  KEY `fk_Post_Provider_Categories1_idx` (`Provider_Categories_ID`),
  CONSTRAINT `fk_Post_Category1` FOREIGN KEY (`Category_ID`) REFERENCES `category` (`Category_ID`),
  CONSTRAINT `fk_Post_Client1` FOREIGN KEY (`Client_ID`) REFERENCES `client` (`Client_ID`),
  CONSTRAINT `fk_Post_Provider1` FOREIGN KEY (`Provider_ID`) REFERENCES `provider` (`Provider_ID`),
  CONSTRAINT `fk_Post_ProviderCategories1` FOREIGN KEY (`Provider_Categories_ID`) REFERENCES `provider_categories` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post`
--

LOCK TABLES `post` WRITE;
/*!40000 ALTER TABLE `post` DISABLE KEYS */;
/*!40000 ALTER TABLE `post` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post_data`
--

DROP TABLE IF EXISTS `post_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `post_data` (
  `ID` int NOT NULL,
  `Input_ID` int NOT NULL,
  `Value` varchar(2048) DEFAULT NULL,
  `Post_ID` int NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_Post_Data_Post_Inputs1_idx` (`Input_ID`),
  KEY `fk_Post_Data_Post1_idx` (`Post_ID`),
  CONSTRAINT `fk_Post_Data_Post1` FOREIGN KEY (`Post_ID`) REFERENCES `post` (`Post_ID`),
  CONSTRAINT `fk_Post_Data_Post_Inputs1` FOREIGN KEY (`Input_ID`) REFERENCES `post_inputs` (`Input_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_data`
--

LOCK TABLES `post_data` WRITE;
/*!40000 ALTER TABLE `post_data` DISABLE KEYS */;
/*!40000 ALTER TABLE `post_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post_inputs`
--

DROP TABLE IF EXISTS `post_inputs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `post_inputs` (
  `Input_ID` int NOT NULL,
  `Title` varchar(45) DEFAULT NULL,
  `Type` varchar(45) DEFAULT NULL,
  `Input_Name` varchar(45) DEFAULT NULL,
  `Default_Value` varchar(256) DEFAULT NULL,
  `Placeholder` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`Input_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_inputs`
--

LOCK TABLES `post_inputs` WRITE;
/*!40000 ALTER TABLE `post_inputs` DISABLE KEYS */;
/*!40000 ALTER TABLE `post_inputs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post_need_skills`
--

DROP TABLE IF EXISTS `post_need_skills`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `post_need_skills` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Skill_ID` int NOT NULL,
  `Post_ID` int NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Skill_ID` (`Skill_ID`),
  KEY `post_need_skills_ibfk_1` (`Post_ID`),
  CONSTRAINT `post_need_skills_ibfk_1` FOREIGN KEY (`Post_ID`) REFERENCES `post` (`Post_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `post_need_skills_ibfk_2` FOREIGN KEY (`Skill_ID`) REFERENCES `skills` (`Skill_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_need_skills`
--

LOCK TABLES `post_need_skills` WRITE;
/*!40000 ALTER TABLE `post_need_skills` DISABLE KEYS */;
/*!40000 ALTER TABLE `post_need_skills` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project`
--

DROP TABLE IF EXISTS `project`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `project` (
  `Project_ID` int NOT NULL,
  `Post_ID` int NOT NULL,
  `Project_Status` varchar(45) DEFAULT NULL,
  `Started_At` datetime DEFAULT NULL,
  `Ended_At` datetime DEFAULT NULL,
  PRIMARY KEY (`Project_ID`),
  KEY `fk_Project_Post1_idx` (`Post_ID`),
  CONSTRAINT `fk_Project_Post1` FOREIGN KEY (`Post_ID`) REFERENCES `post` (`Post_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project`
--

LOCK TABLES `project` WRITE;
/*!40000 ALTER TABLE `project` DISABLE KEYS */;
/*!40000 ALTER TABLE `project` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_update_log`
--

DROP TABLE IF EXISTS `project_update_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `project_update_log` (
  `ID` int NOT NULL,
  `Title` varchar(45) DEFAULT NULL,
  `Description` varchar(256) DEFAULT NULL,
  `Date` datetime DEFAULT NULL,
  `Project_ID` int NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_Project_Update_Log_Project1_idx` (`Project_ID`),
  CONSTRAINT `fk_Project_Update_Log_Project1` FOREIGN KEY (`Project_ID`) REFERENCES `project` (`Project_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_update_log`
--

LOCK TABLES `project_update_log` WRITE;
/*!40000 ALTER TABLE `project_update_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `project_update_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `provider`
--

DROP TABLE IF EXISTS `provider`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `provider` (
  `Provider_ID` int NOT NULL AUTO_INCREMENT,
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
  `Total_Earning` double NOT NULL DEFAULT '0',
  `Rating` double DEFAULT NULL,
  `Status` varchar(50) NOT NULL,
  `Reason_For_Rejection` text,
  `Is_Online` tinyint(1) DEFAULT '0',
  `Last_Seen` datetime DEFAULT NULL,
  PRIMARY KEY (`Provider_ID`),
  UNIQUE KEY `Email_UNIQUE` (`Email`),
  UNIQUE KEY `NIC_No_UNIQUE` (`NIC_No`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `provider`
--

LOCK TABLES `provider` WRITE;
/*!40000 ALTER TABLE `provider` DISABLE KEYS */;
INSERT INTO `provider` VALUES (1,'samaraweera.ghn@gmail.com','0717476839','200401300433','$2y$10$HI0/C7f1DNXmDJX9Y9pUPOOXPK/qPl9mazAWdJG/Cye0F2DDIbK0m','2026-04-04 17:05:15','Pasindu','Gihan','Male','69d0f667bdec73.37341075_fajne-zdjecia-profilowe-19.webp','Experienced Software Developer with a strong background in designing, developing, and maintaining scalable applications. Proficient in multiple programming languages and frameworks, with a focus on writing clean, efficient, and maintainable code. Passionate about solving complex problems, learning new technologies, and contributing to innovative projects. Adept at collaborating with cross-functional teams to deliver high-quality software solutions.','69d0f6ca7ac147.80601436_el-id-kaart-esikylg.webp','69d0f6ca7b1324.94632158_idback.png','69d0f6ca7b32c6.06894642_test.pdf','pasindugihan.me',0,NULL,'Active',NULL,0,NULL);
/*!40000 ALTER TABLE `provider` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `provider_categories`
--

DROP TABLE IF EXISTS `provider_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `provider_categories` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Category_ID` int NOT NULL,
  `Provider_ID` int NOT NULL,
  `Title` varchar(100) DEFAULT NULL,
  `Description` varchar(1024) DEFAULT NULL,
  `Default_Price` double DEFAULT NULL,
  `Price_Type` varchar(50) NOT NULL,
  `Portfolio_Link` varchar(2048) NOT NULL,
  `Price_Negotiability` tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `fk_Provider_Categories_Category1_idx` (`Category_ID`),
  KEY `fk_Provider_Categories_Provider1_idx` (`Provider_ID`),
  CONSTRAINT `fk_Provider_Categories_Category1` FOREIGN KEY (`Category_ID`) REFERENCES `category` (`Category_ID`),
  CONSTRAINT `fk_Provider_Categories_Provider1` FOREIGN KEY (`Provider_ID`) REFERENCES `provider` (`Provider_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `provider_categories`
--

LOCK TABLES `provider_categories` WRITE;
/*!40000 ALTER TABLE `provider_categories` DISABLE KEYS */;
INSERT INTO `provider_categories` VALUES (1,1,1,'Software Developing Tasks','I am a skilled Software Developer dedicated to delivering high-quality, reliable, and scalable solutions tailored to client needs. I specialize in building efficient applications, fixing bugs, and improving system performance. My goal is to understand your requirements clearly and provide solutions that are both functional and user-friendly. I value clear communication, timely delivery, and long-term client relationships.',1500,'Hourly','pasindugihan.me',1);
/*!40000 ALTER TABLE `provider_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `provider_categories_has_location`
--

DROP TABLE IF EXISTS `provider_categories_has_location`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `provider_categories_has_location` (
  `Provider_Categories_ID` int NOT NULL,
  `Location_Location_ID` int DEFAULT NULL,
  `ID` int NOT NULL AUTO_INCREMENT,
  `District_ID` int DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_Provider_Categories_has_Location_Location1_idx` (`Location_Location_ID`),
  KEY `fk_Provider_Categories_has_Location_Provider_Categories1_idx` (`Provider_Categories_ID`),
  KEY `District_ID` (`District_ID`),
  CONSTRAINT `fk_Provider_Categories_has_Location_Location1` FOREIGN KEY (`Location_Location_ID`) REFERENCES `location` (`Location_ID`),
  CONSTRAINT `fk_Provider_Categories_has_Location_Provider_Categories1` FOREIGN KEY (`Provider_Categories_ID`) REFERENCES `provider_categories` (`ID`),
  CONSTRAINT `provider_categories_has_location_ibfk_1` FOREIGN KEY (`District_ID`) REFERENCES `districts` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `provider_categories_has_location`
--

LOCK TABLES `provider_categories_has_location` WRITE;
/*!40000 ALTER TABLE `provider_categories_has_location` DISABLE KEYS */;
INSERT INTO `provider_categories_has_location` VALUES (1,1,1,NULL);
/*!40000 ALTER TABLE `provider_categories_has_location` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `provider_categories_has_skills`
--

DROP TABLE IF EXISTS `provider_categories_has_skills`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `provider_categories_has_skills` (
  `Provider_Categories_ID` int NOT NULL,
  `Skills_Skill_ID` int NOT NULL,
  PRIMARY KEY (`Provider_Categories_ID`,`Skills_Skill_ID`),
  KEY `fk_Provider_Categories_has_Skills_Skills1_idx` (`Skills_Skill_ID`),
  KEY `fk_Provider_Categories_has_Skills_Provider_Categories1_idx` (`Provider_Categories_ID`),
  CONSTRAINT `fk_Provider_Categories_has_Skills_Provider_Categories1` FOREIGN KEY (`Provider_Categories_ID`) REFERENCES `provider_categories` (`ID`),
  CONSTRAINT `fk_Provider_Categories_has_Skills_Skills1` FOREIGN KEY (`Skills_Skill_ID`) REFERENCES `skills` (`Skill_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `provider_categories_has_skills`
--

LOCK TABLES `provider_categories_has_skills` WRITE;
/*!40000 ALTER TABLE `provider_categories_has_skills` DISABLE KEYS */;
INSERT INTO `provider_categories_has_skills` VALUES (1,1),(1,2),(1,3);
/*!40000 ALTER TABLE `provider_categories_has_skills` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `provider_social`
--

DROP TABLE IF EXISTS `provider_social`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `provider_social` (
  `Social_ID` int NOT NULL AUTO_INCREMENT,
  `Social_Type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Social_Link` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Provider_ID` int NOT NULL,
  PRIMARY KEY (`Social_ID`),
  KEY `fk_Social_Provider` (`Provider_ID`),
  CONSTRAINT `fk_Social_Provider` FOREIGN KEY (`Provider_ID`) REFERENCES `provider` (`Provider_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `provider_social`
--

LOCK TABLES `provider_social` WRITE;
/*!40000 ALTER TABLE `provider_social` DISABLE KEYS */;
INSERT INTO `provider_social` VALUES (1,'facebook','https://www.facebook.com/GihanSamaraweeraX',1),(2,'instagram','',1),(3,'tiktok','',1),(4,'youtube','',1),(5,'github','https://github.com/gihan-s',1),(6,'linkedin','https://www.linkedin.com/in/gihan-samaraweera/',1);
/*!40000 ALTER TABLE `provider_social` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reports`
--

DROP TABLE IF EXISTS `reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reports` (
  `Report_ID` int NOT NULL,
  `Reason` varchar(100) DEFAULT NULL,
  `Description` varchar(512) DEFAULT NULL,
  `Reported_At` datetime DEFAULT NULL,
  `Status` varchar(45) DEFAULT NULL,
  `Project_ID` int NOT NULL,
  PRIMARY KEY (`Report_ID`),
  KEY `fk_Reviews_Project1_idx` (`Project_ID`),
  CONSTRAINT `fk_Reviews_Project10` FOREIGN KEY (`Project_ID`) REFERENCES `project` (`Project_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reports`
--

LOCK TABLES `reports` WRITE;
/*!40000 ALTER TABLE `reports` DISABLE KEYS */;
/*!40000 ALTER TABLE `reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reviews` (
  `Review_ID` int NOT NULL,
  `Title` varchar(100) DEFAULT NULL,
  `Description` varchar(512) DEFAULT NULL,
  `Rating` int DEFAULT NULL,
  `Left_At` datetime DEFAULT NULL,
  `Edited_At` datetime DEFAULT NULL,
  `Project_ID` int NOT NULL,
  PRIMARY KEY (`Review_ID`),
  KEY `fk_Reviews_Project1_idx` (`Project_ID`),
  CONSTRAINT `fk_Reviews_Project1` FOREIGN KEY (`Project_ID`) REFERENCES `project` (`Project_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `selection_options`
--

DROP TABLE IF EXISTS `selection_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `selection_options` (
  `Option_ID` int NOT NULL,
  `Title` varchar(100) DEFAULT NULL,
  `Value` varchar(100) DEFAULT NULL,
  `Structure_ID` int NOT NULL,
  PRIMARY KEY (`Option_ID`),
  KEY `fk_Selection_Options_Form_Structure1_idx` (`Structure_ID`),
  CONSTRAINT `fk_Selection_Options_Form_Structure1` FOREIGN KEY (`Structure_ID`) REFERENCES `form_structure` (`Structure_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `selection_options`
--

LOCK TABLES `selection_options` WRITE;
/*!40000 ALTER TABLE `selection_options` DISABLE KEYS */;
/*!40000 ALTER TABLE `selection_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `skills`
--

DROP TABLE IF EXISTS `skills`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `skills` (
  `Skill_ID` int NOT NULL AUTO_INCREMENT,
  `Skill` varchar(100) DEFAULT NULL,
  `Category_ID` int NOT NULL,
  PRIMARY KEY (`Skill_ID`),
  UNIQUE KEY `Skill` (`Skill`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `skills`
--

LOCK TABLES `skills` WRITE;
/*!40000 ALTER TABLE `skills` DISABLE KEYS */;
INSERT INTO `skills` VALUES (1,'React',1),(2,'Laravel',1),(3,'Wordpress',1);
/*!40000 ALTER TABLE `skills` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_log`
--

DROP TABLE IF EXISTS `user_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_log` (
  `ID` int NOT NULL,
  `Activity` varchar(45) DEFAULT NULL,
  `Description` varchar(100) DEFAULT NULL,
  `Date` datetime DEFAULT NULL,
  `Client_ID` int DEFAULT NULL,
  `User_ID` int DEFAULT NULL,
  `Provider_ID` int DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_User_Log_Client1_idx` (`Client_ID`),
  KEY `fk_User_Log_Admin1_idx` (`User_ID`),
  KEY `fk_User_Log_Provider1_idx` (`Provider_ID`),
  CONSTRAINT `fk_User_Log_Admin1` FOREIGN KEY (`User_ID`) REFERENCES `admin` (`User_ID`),
  CONSTRAINT `fk_User_Log_Client1` FOREIGN KEY (`Client_ID`) REFERENCES `client` (`Client_ID`),
  CONSTRAINT `fk_User_Log_Provider1` FOREIGN KEY (`Provider_ID`) REFERENCES `provider` (`Provider_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_log`
--

LOCK TABLES `user_log` WRITE;
/*!40000 ALTER TABLE `user_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_log` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-04 23:54:16
