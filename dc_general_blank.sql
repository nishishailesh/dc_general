-- MySQL dump 10.17  Distrib 10.3.18-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: dc_general
-- ------------------------------------------------------
-- Server version	10.3.18-MariaDB-0+deb10u1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `examination`
--

DROP TABLE IF EXISTS `examination`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `examination` (
  `examination_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_bin NOT NULL,
  `description` varchar(300) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`examination_id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `examination`
--

LOCK TABLES `examination` WRITE;
/*!40000 ALTER TABLE `examination` DISABLE KEYS */;
INSERT INTO `examination` VALUES (1,'mrd',NULL),(2,'LMP',NULL),(3,'EDD',NULL),(4,'Parity',NULL),(5,'Abortion',NULL),(6,'Living_Children',NULL),(7,'Previous_LSCS',NULL),(8,'Other_Previous_Complications',NULL),(12,'direct_in_labor',NULL),(13,'Gestational_age_wks',NULL),(14,'Systolic_BP',NULL),(15,'Diastolic_BP',NULL),(16,'Temperature_F',NULL),(17,'Proteinuria',NULL),(18,'Hb',NULL),(19,'Blood_Group(ABO)',NULL),(20,'Blood_Group(Rh)',NULL),(21,'HIV',NULL),(22,'Syphilis',NULL),(23,'Malaria',NULL),(24,'Hepatitis_B',NULL),(25,'Hepatitis_C',NULL),(26,'Referred_from',NULL),(27,'High_Risk',NULL),(29,'Year_SN',NULL),(30,'Month_SN',NULL),(31,'Date_of_Reg',NULL),(32,'MCTS_No',NULL),(33,'Name',NULL),(34,'Husband_Name',NULL),(35,'Father_Name',NULL),(36,'DOB',NULL),(37,'Age',NULL),(38,'Address',NULL),(39,'Mobile',NULL),(40,'BPL_MBS',NULL),(41,'Aadhar',NULL),(42,'Bank_Name',NULL),(43,'Bank_Account_Number',NULL),(44,'ASHA_Name',NULL),(45,'ASHA_Mobile',NULL);
/*!40000 ALTER TABLE `examination` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profile`
--

DROP TABLE IF EXISTS `profile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `profile` (
  `profile_id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `examination_id_list` varchar(500) NOT NULL,
  PRIMARY KEY (`profile_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profile`
--

LOCK TABLES `profile` WRITE;
/*!40000 ALTER TABLE `profile` DISABLE KEYS */;
INSERT INTO `profile` VALUES (1,'Obstetric_History','1,2,3,4,5,6,7,8'),(2,'Admission_Details','12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27'),(3,'Client_Detail','28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45');
/*!40000 ALTER TABLE `profile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `result`
--

DROP TABLE IF EXISTS `result`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `result` (
  `sample_id` bigint(20) NOT NULL,
  `examination_id` int(11) NOT NULL,
  `result` varchar(5000) DEFAULT NULL,
  `recording_time` datetime DEFAULT NULL,
  `recorded_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`sample_id`,`examination_id`),
  KEY `examination_id` (`examination_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `result`
--

LOCK TABLES `result` WRITE;
/*!40000 ALTER TABLE `result` DISABLE KEYS */;
INSERT INTO `result` VALUES (0,9,NULL,NULL,NULL),(0,10,NULL,NULL,NULL),(0,11,NULL,NULL,NULL),(0,12,NULL,NULL,NULL),(0,13,NULL,NULL,NULL),(0,14,NULL,NULL,NULL),(0,15,NULL,NULL,NULL),(0,16,NULL,NULL,NULL),(0,17,NULL,NULL,NULL),(0,18,NULL,NULL,NULL),(0,19,NULL,NULL,NULL),(0,20,NULL,NULL,NULL),(0,21,NULL,NULL,NULL),(0,22,NULL,NULL,NULL),(0,23,NULL,NULL,NULL),(0,24,NULL,NULL,NULL),(0,25,NULL,NULL,NULL),(0,26,NULL,NULL,NULL),(0,27,NULL,NULL,NULL),(0,44,NULL,NULL,NULL),(0,45,NULL,NULL,NULL),(1,1,'SUR/19/11223344','2019-12-18 21:32:06','1'),(2,1,'SUR/19/11223344','2019-12-18 21:32:32','1'),(3,1,'SUR/19/11223344','2019-12-18 21:32:46','1'),(4,1,'SUR/19/11223344','2019-12-18 21:35:14','1'),(4,9,NULL,NULL,NULL),(4,10,NULL,NULL,NULL),(4,11,NULL,NULL,NULL),(4,12,NULL,NULL,NULL),(4,13,NULL,NULL,NULL),(4,14,NULL,NULL,NULL),(4,15,NULL,NULL,NULL),(4,16,NULL,NULL,NULL),(4,17,NULL,NULL,NULL),(4,18,NULL,NULL,NULL),(4,19,NULL,NULL,NULL),(4,20,NULL,NULL,NULL),(4,21,NULL,NULL,NULL),(4,22,NULL,NULL,NULL),(4,23,NULL,NULL,NULL),(4,24,NULL,NULL,NULL),(4,25,NULL,NULL,NULL),(4,26,NULL,NULL,NULL),(4,27,NULL,NULL,NULL),(4,44,NULL,NULL,NULL),(4,45,NULL,NULL,NULL),(5,1,'SUR/19/11223344','2019-12-18 21:35:33','1'),(5,9,NULL,NULL,NULL),(5,10,NULL,NULL,NULL),(5,11,NULL,NULL,NULL),(5,12,NULL,NULL,NULL),(5,13,NULL,NULL,NULL),(5,14,NULL,NULL,NULL),(5,15,NULL,NULL,NULL),(5,16,NULL,NULL,NULL),(5,17,NULL,NULL,NULL),(5,18,NULL,NULL,NULL),(5,19,NULL,NULL,NULL),(5,20,NULL,NULL,NULL),(5,21,NULL,NULL,NULL),(5,22,NULL,NULL,NULL),(5,23,NULL,NULL,NULL),(5,24,NULL,NULL,NULL),(5,25,NULL,NULL,NULL),(5,26,NULL,NULL,NULL),(5,27,NULL,NULL,NULL),(5,44,NULL,NULL,NULL),(5,45,NULL,NULL,NULL),(6,1,'SUR/19/11223344','2019-12-18 21:38:59','1'),(6,12,NULL,NULL,NULL),(6,13,NULL,NULL,NULL),(6,14,NULL,NULL,NULL),(6,15,NULL,NULL,NULL),(6,16,NULL,NULL,NULL),(6,17,NULL,NULL,NULL),(6,18,NULL,NULL,NULL),(6,19,NULL,NULL,NULL),(6,20,NULL,NULL,NULL),(6,21,NULL,NULL,NULL),(6,22,NULL,NULL,NULL),(6,23,NULL,NULL,NULL),(6,24,NULL,NULL,NULL),(6,25,NULL,NULL,NULL),(6,26,NULL,NULL,NULL),(6,27,NULL,NULL,NULL),(6,44,NULL,NULL,NULL),(6,45,NULL,NULL,NULL),(7,1,'SUR/19/11667788','2019-12-18 21:39:28','1'),(8,1,'SUR/19/11667788','2019-12-18 21:39:59','1'),(9,1,'SUR/19/11667788','2019-12-18 21:40:26','1'),(10,1,'SUR/19/11667788','2019-12-18 21:41:46','1');
/*!40000 ALTER TABLE `result` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `result_blob`
--

DROP TABLE IF EXISTS `result_blob`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `result_blob` (
  `sample_id` bigint(20) NOT NULL,
  `examination_id` int(11) NOT NULL,
  `result` mediumblob DEFAULT NULL,
  `fname` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`sample_id`,`examination_id`),
  KEY `examination_id` (`examination_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `result_blob`
--

LOCK TABLES `result_blob` WRITE;
/*!40000 ALTER TABLE `result_blob` DISABLE KEYS */;
/*!40000 ALTER TABLE `result_blob` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `user` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `password` varchar(1000) NOT NULL,
  `expirydate` date NOT NULL,
  PRIMARY KEY (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'Shailesh','$2y$10$rK6tUXxwZc0a07pu8YiQx.lXJLCevgepyiVt4kS391BwcPOqvmiNu','2020-06-18');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-12-20  8:00:05
