-- MySQL dump 10.13  Distrib 8.0.36, for Win64 (x86_64)
--
-- Host: 13.60.4.254    Database: servo
-- ------------------------------------------------------
-- Server version	8.0.43-0ubuntu0.24.04.1

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
-- Table structure for table `Admin`
--

DROP TABLE IF EXISTS `Admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Admin` (
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
-- Table structure for table `Bids`
--

DROP TABLE IF EXISTS `Bids`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Bids` (
  `Bid_ID` int NOT NULL,
  `Comment` varchar(256) DEFAULT NULL,
  `Amount` double DEFAULT NULL,
  `Created_At` datetime DEFAULT NULL,
  `Est_Date` datetime DEFAULT NULL,
  `Status` varchar(45) DEFAULT NULL,
  `Post_ID` int NOT NULL,
  PRIMARY KEY (`Bid_ID`),
  KEY `fk_Bids_Post1_idx` (`Post_ID`),
  CONSTRAINT `fk_Bids_Post1` FOREIGN KEY (`Post_ID`) REFERENCES `Post` (`Post_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Category`
--

DROP TABLE IF EXISTS `Category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Category` (
  `Category_ID` int NOT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Description` varchar(100) DEFAULT NULL,
  `Icon` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`Category_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Client`
--

DROP TABLE IF EXISTS `Client`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Client` (
  `Client_ID` int NOT NULL,
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
  PRIMARY KEY (`Client_ID`),
  UNIQUE KEY `Email_UNIQUE` (`Email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Form_Structure`
--

DROP TABLE IF EXISTS `Form_Structure`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Form_Structure` (
  `Structure_ID` int NOT NULL,
  `Input_ID` int NOT NULL,
  `Category_ID` int NOT NULL,
  PRIMARY KEY (`Structure_ID`),
  KEY `fk_Form_Structure_Post_Inputs_idx` (`Input_ID`),
  KEY `fk_Form_Structure_Category1_idx` (`Category_ID`),
  CONSTRAINT `fk_Form_Structure_Category1` FOREIGN KEY (`Category_ID`) REFERENCES `Category` (`Category_ID`),
  CONSTRAINT `fk_Form_Structure_Post_Inputs` FOREIGN KEY (`Input_ID`) REFERENCES `Post_Inputs` (`Input_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Location`
--

DROP TABLE IF EXISTS `Location`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Location` (
  `Location_ID` int NOT NULL,
  `District` varchar(45) DEFAULT NULL,
  `City` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`Location_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Messages`
--

DROP TABLE IF EXISTS `Messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Messages` (
  `Message_ID` int NOT NULL,
  `Content` text,
  `Is_Client_To_Provider` tinyint DEFAULT NULL,
  `Sent_At` datetime DEFAULT NULL,
  `Delivered_At` datetime DEFAULT NULL,
  `Read_At` datetime DEFAULT NULL,
  `Status` varchar(45) DEFAULT NULL,
  `Provider_ID` int NOT NULL,
  `Client_ID` int NOT NULL,
  PRIMARY KEY (`Message_ID`),
  KEY `fk_Messages_Provider1_idx` (`Provider_ID`),
  KEY `fk_Messages_Client1_idx` (`Client_ID`),
  CONSTRAINT `fk_Messages_Client1` FOREIGN KEY (`Client_ID`) REFERENCES `Client` (`Client_ID`),
  CONSTRAINT `fk_Messages_Provider1` FOREIGN KEY (`Provider_ID`) REFERENCES `Provider` (`Provider_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Payment`
--

DROP TABLE IF EXISTS `Payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Payment` (
  `Payment_ID` int NOT NULL,
  `Amount` double DEFAULT NULL,
  `Status` varchar(45) DEFAULT NULL,
  `Hold_Time` datetime DEFAULT NULL,
  `Paid_Time` datetime DEFAULT NULL,
  `Commission` double DEFAULT NULL,
  `Project_ID` int NOT NULL,
  PRIMARY KEY (`Payment_ID`),
  KEY `fk_Payment_Project1_idx` (`Project_ID`),
  CONSTRAINT `fk_Payment_Project1` FOREIGN KEY (`Project_ID`) REFERENCES `Project` (`Project_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Post`
--

DROP TABLE IF EXISTS `Post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Post` (
  `Post_ID` int NOT NULL,
  `Created_At` datetime DEFAULT NULL,
  `Category_ID` int NOT NULL,
  `Post_Type` varchar(45) DEFAULT NULL,
  `Post_Status` varchar(45) DEFAULT NULL,
  `Client_ID` int NOT NULL,
  `Provider_ID` int DEFAULT NULL,
  `Title` varchar(100) DEFAULT NULL,
  `Description` varchar(2048) DEFAULT NULL,
  `Requesting_Price` double DEFAULT NULL,
  PRIMARY KEY (`Post_ID`),
  KEY `fk_Post_Category1_idx` (`Category_ID`),
  KEY `fk_Post_Client1_idx` (`Client_ID`),
  KEY `fk_Post_Provider1_idx` (`Provider_ID`),
  CONSTRAINT `fk_Post_Category1` FOREIGN KEY (`Category_ID`) REFERENCES `Category` (`Category_ID`),
  CONSTRAINT `fk_Post_Client1` FOREIGN KEY (`Client_ID`) REFERENCES `Client` (`Client_ID`),
  CONSTRAINT `fk_Post_Provider1` FOREIGN KEY (`Provider_ID`) REFERENCES `Provider` (`Provider_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Post_Data`
--

DROP TABLE IF EXISTS `Post_Data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Post_Data` (
  `ID` int NOT NULL,
  `Input_ID` int NOT NULL,
  `Value` varchar(2048) DEFAULT NULL,
  `Post_ID` int NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_Post_Data_Post_Inputs1_idx` (`Input_ID`),
  KEY `fk_Post_Data_Post1_idx` (`Post_ID`),
  CONSTRAINT `fk_Post_Data_Post1` FOREIGN KEY (`Post_ID`) REFERENCES `Post` (`Post_ID`),
  CONSTRAINT `fk_Post_Data_Post_Inputs1` FOREIGN KEY (`Input_ID`) REFERENCES `Post_Inputs` (`Input_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Post_Inputs`
--

DROP TABLE IF EXISTS `Post_Inputs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Post_Inputs` (
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
-- Table structure for table `Project`
--

DROP TABLE IF EXISTS `Project`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Project` (
  `Project_ID` int NOT NULL,
  `Post_ID` int NOT NULL,
  `Project_Status` varchar(45) DEFAULT NULL,
  `Started_At` datetime DEFAULT NULL,
  `Ended_At` datetime DEFAULT NULL,
  PRIMARY KEY (`Project_ID`),
  KEY `fk_Project_Post1_idx` (`Post_ID`),
  CONSTRAINT `fk_Project_Post1` FOREIGN KEY (`Post_ID`) REFERENCES `Post` (`Post_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Project_Update_Log`
--

DROP TABLE IF EXISTS `Project_Update_Log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Project_Update_Log` (
  `ID` int NOT NULL,
  `Title` varchar(45) DEFAULT NULL,
  `Description` varchar(256) DEFAULT NULL,
  `Date` datetime DEFAULT NULL,
  `Project_ID` int NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_Project_Update_Log_Project1_idx` (`Project_ID`),
  CONSTRAINT `fk_Project_Update_Log_Project1` FOREIGN KEY (`Project_ID`) REFERENCES `Project` (`Project_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Provider`
--

DROP TABLE IF EXISTS `Provider`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Provider` (
  `Provider_ID` int NOT NULL,
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
  PRIMARY KEY (`Provider_ID`),
  UNIQUE KEY `Email_UNIQUE` (`Email`),
  UNIQUE KEY `NIC_No_UNIQUE` (`NIC_No`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Provider_Categories`
--

DROP TABLE IF EXISTS `Provider_Categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Provider_Categories` (
  `ID` int NOT NULL,
  `Category_ID` int NOT NULL,
  `Provider_ID` int NOT NULL,
  `Title` varchar(100) DEFAULT NULL,
  `Description` varchar(1024) DEFAULT NULL,
  `Default_Price` double DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_Provider_Categories_Category1_idx` (`Category_ID`),
  KEY `fk_Provider_Categories_Provider1_idx` (`Provider_ID`),
  CONSTRAINT `fk_Provider_Categories_Category1` FOREIGN KEY (`Category_ID`) REFERENCES `Category` (`Category_ID`),
  CONSTRAINT `fk_Provider_Categories_Provider1` FOREIGN KEY (`Provider_ID`) REFERENCES `Provider` (`Provider_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Provider_Categories_has_Location`
--

DROP TABLE IF EXISTS `Provider_Categories_has_Location`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Provider_Categories_has_Location` (
  `Provider_Categories_ID` int NOT NULL,
  `Location_Location_ID` int NOT NULL,
  PRIMARY KEY (`Provider_Categories_ID`,`Location_Location_ID`),
  KEY `fk_Provider_Categories_has_Location_Location1_idx` (`Location_Location_ID`),
  KEY `fk_Provider_Categories_has_Location_Provider_Categories1_idx` (`Provider_Categories_ID`),
  CONSTRAINT `fk_Provider_Categories_has_Location_Location1` FOREIGN KEY (`Location_Location_ID`) REFERENCES `Location` (`Location_ID`),
  CONSTRAINT `fk_Provider_Categories_has_Location_Provider_Categories1` FOREIGN KEY (`Provider_Categories_ID`) REFERENCES `Provider_Categories` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Provider_Categories_has_Skills`
--

DROP TABLE IF EXISTS `Provider_Categories_has_Skills`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Provider_Categories_has_Skills` (
  `Provider_Categories_ID` int NOT NULL,
  `Skills_Skill_ID` int NOT NULL,
  PRIMARY KEY (`Provider_Categories_ID`,`Skills_Skill_ID`),
  KEY `fk_Provider_Categories_has_Skills_Skills1_idx` (`Skills_Skill_ID`),
  KEY `fk_Provider_Categories_has_Skills_Provider_Categories1_idx` (`Provider_Categories_ID`),
  CONSTRAINT `fk_Provider_Categories_has_Skills_Provider_Categories1` FOREIGN KEY (`Provider_Categories_ID`) REFERENCES `Provider_Categories` (`ID`),
  CONSTRAINT `fk_Provider_Categories_has_Skills_Skills1` FOREIGN KEY (`Skills_Skill_ID`) REFERENCES `Skills` (`Skill_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Reports`
--

DROP TABLE IF EXISTS `Reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Reports` (
  `Report_ID` int NOT NULL,
  `Reason` varchar(100) DEFAULT NULL,
  `Description` varchar(512) DEFAULT NULL,
  `Reported_At` datetime DEFAULT NULL,
  `Status` varchar(45) DEFAULT NULL,
  `Project_ID` int NOT NULL,
  PRIMARY KEY (`Report_ID`),
  KEY `fk_Reviews_Project1_idx` (`Project_ID`),
  CONSTRAINT `fk_Reviews_Project10` FOREIGN KEY (`Project_ID`) REFERENCES `Project` (`Project_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Reviews`
--

DROP TABLE IF EXISTS `Reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Reviews` (
  `Review_ID` int NOT NULL,
  `Title` varchar(100) DEFAULT NULL,
  `Description` varchar(512) DEFAULT NULL,
  `Rating` int DEFAULT NULL,
  `Left_At` datetime DEFAULT NULL,
  `Edited_At` datetime DEFAULT NULL,
  `Project_ID` int NOT NULL,
  PRIMARY KEY (`Review_ID`),
  KEY `fk_Reviews_Project1_idx` (`Project_ID`),
  CONSTRAINT `fk_Reviews_Project1` FOREIGN KEY (`Project_ID`) REFERENCES `Project` (`Project_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Selection_Options`
--

DROP TABLE IF EXISTS `Selection_Options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Selection_Options` (
  `Option_ID` int NOT NULL,
  `Title` varchar(100) DEFAULT NULL,
  `Value` varchar(100) DEFAULT NULL,
  `Structure_ID` int NOT NULL,
  PRIMARY KEY (`Option_ID`),
  KEY `fk_Selection_Options_Form_Structure1_idx` (`Structure_ID`),
  CONSTRAINT `fk_Selection_Options_Form_Structure1` FOREIGN KEY (`Structure_ID`) REFERENCES `Form_Structure` (`Structure_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Skills`
--

DROP TABLE IF EXISTS `Skills`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Skills` (
  `Skill_ID` int NOT NULL,
  `Skill` varchar(100) DEFAULT NULL,
  `Provider_Categories_ID` int NOT NULL,
  PRIMARY KEY (`Skill_ID`),
  KEY `fk_Skills_Provider_Categories1_idx` (`Provider_Categories_ID`),
  CONSTRAINT `fk_Skills_Provider_Categories1` FOREIGN KEY (`Provider_Categories_ID`) REFERENCES `Provider_Categories` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `User_Log`
--

DROP TABLE IF EXISTS `User_Log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `User_Log` (
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
  CONSTRAINT `fk_User_Log_Admin1` FOREIGN KEY (`User_ID`) REFERENCES `Admin` (`User_ID`),
  CONSTRAINT `fk_User_Log_Client1` FOREIGN KEY (`Client_ID`) REFERENCES `Client` (`Client_ID`),
  CONSTRAINT `fk_User_Log_Provider1` FOREIGN KEY (`Provider_ID`) REFERENCES `Provider` (`Provider_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-09-03  3:23:14
