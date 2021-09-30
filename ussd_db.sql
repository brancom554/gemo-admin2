-- MySQL dump 10.19  Distrib 10.3.29-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: ussd_db
-- ------------------------------------------------------
-- Server version	10.3.29-MariaDB-0+deb10u1

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
-- Table structure for table `addresses`
--

DROP TABLE IF EXISTS `addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `addresses` (
  `address_id` int(11) NOT NULL AUTO_INCREMENT,
  `postal_address` varchar(250) DEFAULT NULL,
  `postal_code` varchar(50) DEFAULT NULL,
  `creation_date` date DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `company_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `company_token` varchar(105) DEFAULT NULL,
  `application_uuid` varchar(105) DEFAULT NULL,
  `data_version` varchar(105) DEFAULT NULL,
  PRIMARY KEY (`address_id`),
  KEY `fk_addresses_companies1_idx` (`company_id`),
  KEY `fk_addresses_countries1_idx` (`country_id`),
  CONSTRAINT `fk_addresses_companies1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`company_id`),
  CONSTRAINT `fk_addresses_countries1` FOREIGN KEY (`country_id`) REFERENCES `countries` (`country_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `addresses`
--

LOCK TABLES `addresses` WRITE;
/*!40000 ALTER TABLE `addresses` DISABLE KEYS */;
INSERT INTO `addresses` VALUES (1,'Avenue Steinmetz','00229','2021-07-08',NULL,'Cotonou',1,1,'TEST08072021','041750ce-e473-462c-af82-99567dc25b4b','1'),(2,'Rue de l\'Echangeur de Godomey','00229','2021-08-21',NULL,'Godomey',2,1,'DKO',NULL,'1'),(3,'Avenue Steinmetz','00229','2021-09-22',NULL,'Cotonou',3,1,NULL,NULL,'1'),(4,'rue des cheminots','75008','2021-09-28',NULL,'Seville',4,2,NULL,NULL,'1');
/*!40000 ALTER TABLE `addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(200) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `type_category` int(11) DEFAULT NULL,
  `type_category_libelle` varchar(250) DEFAULT NULL,
  `data_version` int(11) DEFAULT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'CREDIT','2021-07-08 12:30:47',1,'SERVICES TELEPHONIQUES',1),(2,'FORFAIT MAXI APPEL','2021-07-08 12:30:47',1,'SERVICES TELEPHONIQUES',1),(3,'DEPOT','2021-07-08 12:30:47',2,'SERVICES FINANCIERS',1),(4,'SOLDE TELEPHONIQUE','2021-07-16 10:52:50',1,'SERVICES TELEPHONIQUES',1),(5,'SOLDE SIM','2021-07-16 10:52:50',2,'SERVICES FINANCIERS',1),(6,'RETRAIT','2021-07-21 08:54:58',2,'SERVICES FINANCIERS',1),(7,'FORFAIT MAXI INTERNET','2021-08-02 15:55:03',1,'SERVICES TELEPHONIQUES',1),(8,'FORFAIT INTERNET','2021-08-02 15:55:03',1,'SERVICES TELEPHONIQUES',1),(9,'PASS BONUS APPEL','2021-08-26 10:19:32',1,'SERVICES TELEPHONIQUES',3),(10,'PASS BONUS INTERNET','2021-08-26 10:19:32',1,'SERVICES TELEPHONIQUES',3);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `category_ussd`
--

DROP TABLE IF EXISTS `category_ussd`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category_ussd` (
  `category_ussd_id` int(11) NOT NULL AUTO_INCREMENT,
  `ussd_code` varchar(150) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `operation_type_id` int(11) NOT NULL,
  `company_token` varchar(105) DEFAULT NULL,
  `application_uuid` varchar(105) DEFAULT NULL,
  `data_version` varchar(105) DEFAULT NULL,
  `network_operator_number` int(11) DEFAULT NULL,
  `network_operator_name` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`category_ussd_id`),
  KEY `fk_category_ussd_categories1_idx` (`category_id`),
  KEY `fk_category_ussd_operation_types1_idx` (`operation_type_id`),
  CONSTRAINT `fk_category_ussd_categories1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`),
  CONSTRAINT `fk_category_ussd_operation_types1` FOREIGN KEY (`operation_type_id`) REFERENCES `operation_types` (`operation_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category_ussd`
--

LOCK TABLES `category_ussd` WRITE;
/*!40000 ALTER TABLE `category_ussd` DISABLE KEYS */;
INSERT INTO `category_ussd` VALUES (1,'*855*1*1*1*',3,1,'TEST08072021','041750ce-e473-462c-af82-99567dc25b4b','3',2,'MOOV'),(2,'*880*3*1*',3,1,'TEST08072021','041750ce-e473-462c-af82-99567dc25b4b','3',1,'MTN'),(3,'*173*5*1#',4,6,NULL,NULL,'3',2,'MOOV'),(4,'*880*2*1*',6,2,NULL,NULL,'3',1,'MTN'),(5,'*855*4*1*',6,2,NULL,NULL,'3',2,'MOOV'),(6,'*106*10*3*',7,7,NULL,NULL,'3',1,'MTN'),(7,'*173*1*',10,7,NULL,NULL,'3',2,'MOOV'),(8,'*106*10*1*',8,4,NULL,NULL,'3',1,'MTN'),(9,'*123*7*',8,4,NULL,NULL,'3',2,'MOOV'),(10,'*106*10*3*',2,3,NULL,NULL,'3',1,'MTN'),(11,'*173*1*',9,8,NULL,NULL,'3',2,'MOOV'),(12,'*106*',1,8,NULL,NULL,'3',2,'MOOV'),(13,'*106*2*',1,8,NULL,NULL,'3',1,'MTN');
/*!40000 ALTER TABLE `category_ussd` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `companies`
--

DROP TABLE IF EXISTS `companies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `companies` (
  `company_id` int(11) NOT NULL AUTO_INCREMENT,
  `company_number` varchar(150) DEFAULT NULL COMMENT 'RCCM',
  `company_name` varchar(100) NOT NULL,
  `creation_date` datetime DEFAULT NULL,
  `company_token` varchar(105) DEFAULT NULL,
  `registration_number` varchar(65) DEFAULT NULL COMMENT 'IFU number',
  `application_uuid` varchar(105) DEFAULT NULL,
  `data_version` varchar(105) DEFAULT NULL,
  PRIMARY KEY (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `companies`
--

LOCK TABLES `companies` WRITE;
/*!40000 ALTER TABLE `companies` DISABLE KEYS */;
INSERT INTO `companies` VALUES (1,'RCCM021544','TEST0','2021-07-08 12:24:10','TEST0','1234567888','041750ce-e473-462c-af82-99567dc25b4b','1'),(2,'21082021','TEST1','2021-08-21 15:36:35','TEST1','20218012',NULL,'1'),(3,'RB/COT IFUTEST1','TTEST','2021-09-22 23:22:00','TTEST','7841202369',NULL,NULL),(4,'RB/COT 7','COMPAGNIE-A','2021-09-28 10:14:00','COMPAGNIEA','87554444551',NULL,NULL);
/*!40000 ALTER TABLE `companies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `countries` (
  `country_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(105) DEFAULT NULL,
  `country_short_name` varchar(45) NOT NULL,
  `creation_date` datetime DEFAULT NULL,
  `data_version` varchar(105) DEFAULT NULL,
  PRIMARY KEY (`country_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `countries`
--

LOCK TABLES `countries` WRITE;
/*!40000 ALTER TABLE `countries` DISABLE KEYS */;
INSERT INTO `countries` VALUES (1,'BENIN','BJ','2021-07-08 12:21:08','1'),(2,'FRANCE','FR','2021-07-08 12:21:08','1');
/*!40000 ALTER TABLE `countries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `database_version`
--

DROP TABLE IF EXISTS `database_version`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `database_version` (
  `database_version_id` int(11) NOT NULL AUTO_INCREMENT,
  `current_version` int(11) DEFAULT NULL,
  `last_update_date` datetime DEFAULT NULL,
  PRIMARY KEY (`database_version_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `database_version`
--

LOCK TABLES `database_version` WRITE;
/*!40000 ALTER TABLE `database_version` DISABLE KEYS */;
INSERT INTO `database_version` VALUES (1,3,'2021-08-13 00:22:42');
/*!40000 ALTER TABLE `database_version` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventories`
--

DROP TABLE IF EXISTS `inventories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventories` (
  `inventory_id` int(11) NOT NULL AUTO_INCREMENT,
  `date_from` date DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `report_file_url` varchar(155) DEFAULT NULL,
  `is_finished_flag` tinyint(1) DEFAULT NULL,
  `company_token` varchar(105) DEFAULT NULL,
  `inventory_name` varchar(105) DEFAULT NULL,
  `application_uuid` varchar(105) DEFAULT NULL,
  `data_version` varchar(105) DEFAULT NULL,
  PRIMARY KEY (`inventory_id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventories`
--

LOCK TABLES `inventories` WRITE;
/*!40000 ALTER TABLE `inventories` DISABLE KEYS */;
INSERT INTO `inventories` VALUES (1,'2021-09-22','2021-09-23','dnznjmznjd',1,'TTEST','inv22_au_24','01111111','1'),(2,'2021-09-21','2021-09-24',NULL,NULL,NULL,'inv. 2021-09-21/2021-09-24','f0658690-c7fe-48b8-8964-cf6d9e4c3ab5','2'),(3,'2021-09-21','2021-09-24',NULL,NULL,NULL,'inv. 2021-09-21/2021-09-24','f0658690-c7fe-48b8-8964-cf6d9e4c3ab5','2'),(4,'2021-09-21','2021-09-24',NULL,NULL,NULL,'inv. 2021-09-21/2021-09-24','f0658690-c7fe-48b8-8964-cf6d9e4c3ab5','2'),(5,'2021-09-21','2021-09-24',NULL,NULL,NULL,'inv. 2021-09-21/2021-09-24','f0658690-c7fe-48b8-8964-cf6d9e4c3ab5','2'),(6,'2021-09-21','2021-09-24',NULL,NULL,NULL,'inv. 2021-09-21/2021-09-24','f0658690-c7fe-48b8-8964-cf6d9e4c3ab5','2'),(7,'2021-09-21','2021-09-24',NULL,NULL,NULL,'inv. 2021-09-21/2021-09-24','f0658690-c7fe-48b8-8964-cf6d9e4c3ab5','2'),(8,'2021-09-21','2021-09-24',NULL,NULL,NULL,'inv. 2021-09-21/2021-09-24','f0658690-c7fe-48b8-8964-cf6d9e4c3ab5','2'),(9,'2021-09-21','2021-09-24',NULL,NULL,NULL,'inv. 2021-09-21/2021-09-24','f0658690-c7fe-48b8-8964-cf6d9e4c3ab5','2'),(10,'2021-09-21','2021-09-24',NULL,NULL,NULL,'inv. 2021-09-21/2021-09-24','f0658690-c7fe-48b8-8964-cf6d9e4c3ab5','2'),(11,'2021-09-21','2021-09-24',NULL,NULL,NULL,'inv. 2021-09-21/2021-09-24','f0658690-c7fe-48b8-8964-cf6d9e4c3ab5','2'),(12,'2021-09-21','2021-09-24',NULL,NULL,NULL,'inv. 2021-09-21/2021-09-24','f0658690-c7fe-48b8-8964-cf6d9e4c3ab5','2'),(13,'2021-09-21','2021-09-24',NULL,NULL,NULL,'inv. 2021-09-21/2021-09-24','f0658690-c7fe-48b8-8964-cf6d9e4c3ab5','2'),(14,'2021-09-21','2021-09-24',NULL,NULL,NULL,'inv. 2021-09-21/2021-09-24','f0658690-c7fe-48b8-8964-cf6d9e4c3ab5','2'),(15,'2021-09-21','2021-09-24',NULL,NULL,NULL,'inv. 2021-09-21/2021-09-24','f0658690-c7fe-48b8-8964-cf6d9e4c3ab5','2'),(16,'2021-09-21','2021-09-24',NULL,NULL,NULL,'inv. 2021-09-21/2021-09-24','f0658690-c7fe-48b8-8964-cf6d9e4c3ab5','2'),(17,'2021-09-21','2021-09-24',NULL,NULL,NULL,'inv. 2021-09-21/2021-09-24','f0658690-c7fe-48b8-8964-cf6d9e4c3ab5','2'),(18,'2021-09-21','2021-09-24',NULL,NULL,NULL,'inv. 2021-09-21/2021-09-24','f0658690-c7fe-48b8-8964-cf6d9e4c3ab5','2'),(19,'2021-09-21','2021-09-24',NULL,NULL,NULL,'inv. 2021-09-21/2021-09-24','f0658690-c7fe-48b8-8964-cf6d9e4c3ab5','2'),(20,'2021-09-21','2021-09-24',NULL,NULL,NULL,'inv. 2021-09-21/2021-09-24','f0658690-c7fe-48b8-8964-cf6d9e4c3ab5','2'),(21,'2021-09-21','2021-09-24',NULL,NULL,NULL,'inv. 2021-09-21/2021-09-24','f0658690-c7fe-48b8-8964-cf6d9e4c3ab5','2'),(22,'2021-09-21','2021-09-24',NULL,NULL,NULL,'inv. 2021-09-21/2021-09-24','f0658690-c7fe-48b8-8964-cf6d9e4c3ab5','2'),(23,'2021-09-21','2021-09-24',NULL,NULL,NULL,'inv. 2021-09-21/2021-09-24','f0658690-c7fe-48b8-8964-cf6d9e4c3ab5','2'),(24,'2021-09-21','2021-09-24',NULL,NULL,NULL,'inv. 2021-09-21/2021-09-24','f0658690-c7fe-48b8-8964-cf6d9e4c3ab5','2'),(25,'2021-09-21','2021-09-24',NULL,NULL,NULL,'inv. 2021-09-21/2021-09-24','f0658690-c7fe-48b8-8964-cf6d9e4c3ab5','2'),(26,'2021-09-21','2021-09-24',NULL,NULL,NULL,'inv. 2021-09-21/2021-09-24','f0658690-c7fe-48b8-8964-cf6d9e4c3ab5','2'),(27,'2021-09-21','2021-09-24',NULL,NULL,NULL,'inv. 2021-09-21/2021-09-24','f0658690-c7fe-48b8-8964-cf6d9e4c3ab5','2'),(28,'2021-09-21','2021-09-24',NULL,NULL,NULL,'inv. 2021-09-21/2021-09-24','f0658690-c7fe-48b8-8964-cf6d9e4c3ab5','2'),(29,'2021-09-21','2021-09-24',NULL,NULL,NULL,'inv. 2021-09-21/2021-09-24','f0658690-c7fe-48b8-8964-cf6d9e4c3ab5','2'),(30,'2021-09-21','2021-09-24',NULL,NULL,NULL,'inv. 2021-09-21/2021-09-24','f0658690-c7fe-48b8-8964-cf6d9e4c3ab5','2'),(31,'2021-09-21','2021-09-24',NULL,NULL,NULL,'inv. 2021-09-21/2021-09-24','f0658690-c7fe-48b8-8964-cf6d9e4c3ab5','2'),(32,'2021-09-21','2021-09-24',NULL,NULL,NULL,'inv. 2021-09-21/2021-09-24','f0658690-c7fe-48b8-8964-cf6d9e4c3ab5','2'),(33,'2021-09-21','2021-09-24',NULL,NULL,NULL,'inv. 2021-09-21/2021-09-24','f0658690-c7fe-48b8-8964-cf6d9e4c3ab5','2'),(34,'2021-09-21','2021-09-24',NULL,NULL,NULL,'inv. 2021-09-21/2021-09-24','f0658690-c7fe-48b8-8964-cf6d9e4c3ab5','2'),(35,'2021-09-21','2021-09-24',NULL,NULL,NULL,'inv. 2021-09-21/2021-09-24','f0658690-c7fe-48b8-8964-cf6d9e4c3ab5','2');
/*!40000 ALTER TABLE `inventories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_detail`
--

DROP TABLE IF EXISTS `inventory_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventory_detail` (
  `inventory_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `balance_start` int(11) DEFAULT NULL COMMENT 'solde de début periode',
  `balance_end` int(11) DEFAULT NULL COMMENT 'solde en fin de periode',
  `sales_amount` int(11) DEFAULT NULL COMMENT 'chiffre daffaires',
  `nb_transactions` int(11) DEFAULT NULL COMMENT 'nombre transactions sur la periode',
  `operation_type_id` int(11) DEFAULT NULL,
  `inventory_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`inventory_detail_id`),
  KEY `operation_type_id` (`operation_type_id`),
  KEY `inventory_id` (`inventory_id`),
  CONSTRAINT `inventory_id` FOREIGN KEY (`inventory_id`) REFERENCES `inventories` (`inventory_id`),
  CONSTRAINT `operation_type_id` FOREIGN KEY (`operation_type_id`) REFERENCES `operation_types` (`operation_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_detail`
--

LOCK TABLES `inventory_detail` WRITE;
/*!40000 ALTER TABLE `inventory_detail` DISABLE KEYS */;
INSERT INTO `inventory_detail` VALUES (1,1000,500,450,53,1,1),(2,0,2160,1000,16,1,2),(3,0,1815,700,16,1,3),(4,0,2160,1000,16,1,2),(5,0,1815,700,16,1,3),(6,0,2160,1000,16,1,2),(7,0,1815,700,16,1,3),(8,0,2160,1000,16,1,2),(9,0,1815,700,16,1,3),(10,0,2160,1000,16,1,2),(11,0,1815,700,16,1,3),(12,0,2160,1000,16,1,2),(13,0,1815,700,16,1,3),(14,0,2160,1000,16,1,2),(15,0,1815,700,16,1,3),(16,0,2160,1000,16,1,2),(17,0,1815,700,16,1,3),(18,0,2160,1000,16,1,2),(19,0,1815,700,16,1,3),(20,0,2160,1000,16,1,2),(21,0,1815,700,16,1,3),(22,0,2160,1000,16,1,2),(23,0,1815,700,16,1,3),(24,0,2160,1000,16,1,2),(25,0,1815,700,16,1,3),(26,0,2160,1000,16,1,2),(27,0,1815,700,16,1,3),(28,0,2160,1000,16,1,2),(29,0,1815,700,16,1,3),(30,0,2160,1000,16,1,2),(31,0,1815,700,16,1,3),(32,0,2160,1000,16,1,2),(33,0,1815,700,16,1,3),(34,0,2160,1000,16,1,2),(35,0,1815,700,16,1,3);
/*!40000 ALTER TABLE `inventory_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `licence_features`
--

DROP TABLE IF EXISTS `licence_features`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `licence_features` (
  `licence_feature_id` int(11) NOT NULL AUTO_INCREMENT,
  `licence_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `company_token` varchar(105) DEFAULT NULL,
  `application_uuid` varchar(105) DEFAULT NULL,
  `data_version` varchar(105) DEFAULT NULL,
  PRIMARY KEY (`licence_feature_id`,`licence_id`,`service_id`) USING BTREE,
  KEY `service_id` (`service_id`),
  KEY `fk_licence_features_licences_idx` (`licence_id`)
) ENGINE=InnoDB AUTO_INCREMENT=147 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `licence_features`
--

LOCK TABLES `licence_features` WRITE;
/*!40000 ALTER TABLE `licence_features` DISABLE KEYS */;
INSERT INTO `licence_features` VALUES (1,27,2,NULL,NULL,NULL),(2,31,3,NULL,NULL,NULL),(3,33,1,NULL,NULL,NULL),(4,33,2,NULL,NULL,NULL),(5,33,3,NULL,NULL,NULL),(6,33,4,NULL,NULL,NULL),(7,33,5,NULL,NULL,NULL),(8,33,6,NULL,NULL,NULL),(9,33,7,NULL,NULL,NULL),(10,33,8,NULL,NULL,NULL),(11,33,9,NULL,NULL,NULL),(12,34,1,NULL,NULL,NULL),(13,34,2,NULL,NULL,NULL),(14,34,3,NULL,NULL,NULL),(15,34,4,NULL,NULL,NULL),(16,34,5,NULL,NULL,NULL),(17,34,6,NULL,NULL,NULL),(18,34,7,NULL,NULL,NULL),(19,34,8,NULL,NULL,NULL),(20,34,9,NULL,NULL,NULL),(21,35,1,NULL,NULL,NULL),(22,35,2,NULL,NULL,NULL),(23,35,3,NULL,NULL,NULL),(24,35,4,NULL,NULL,NULL),(25,35,5,NULL,NULL,NULL),(26,35,6,NULL,NULL,NULL),(27,35,7,NULL,NULL,NULL),(28,35,8,NULL,NULL,NULL),(29,35,9,NULL,NULL,NULL),(30,36,1,NULL,NULL,NULL),(31,36,2,NULL,NULL,NULL),(32,36,3,NULL,NULL,NULL),(33,36,4,NULL,NULL,NULL),(34,36,5,NULL,NULL,NULL),(35,36,6,NULL,NULL,NULL),(36,36,7,NULL,NULL,NULL),(37,36,8,NULL,NULL,NULL),(38,36,9,NULL,NULL,NULL),(39,37,1,NULL,NULL,NULL),(40,37,2,NULL,NULL,NULL),(41,37,3,NULL,NULL,NULL),(42,37,4,NULL,NULL,NULL),(43,37,5,NULL,NULL,NULL),(44,37,6,NULL,NULL,NULL),(45,37,7,NULL,NULL,NULL),(46,37,8,NULL,NULL,NULL),(47,37,9,NULL,NULL,NULL),(48,38,1,NULL,NULL,NULL),(49,38,2,NULL,NULL,NULL),(50,38,3,NULL,NULL,NULL),(51,38,4,NULL,NULL,NULL),(52,38,5,NULL,NULL,NULL),(53,38,6,NULL,NULL,NULL),(54,38,7,NULL,NULL,NULL),(55,38,8,NULL,NULL,NULL),(56,38,9,NULL,NULL,NULL),(57,39,1,NULL,NULL,NULL),(58,39,2,NULL,NULL,NULL),(59,39,3,NULL,NULL,NULL),(60,39,4,NULL,NULL,NULL),(61,39,5,NULL,NULL,NULL),(62,39,6,NULL,NULL,NULL),(63,39,7,NULL,NULL,NULL),(64,39,8,NULL,NULL,NULL),(65,39,9,NULL,NULL,NULL),(66,40,1,NULL,NULL,NULL),(67,40,2,NULL,NULL,NULL),(68,40,3,NULL,NULL,NULL),(69,40,4,NULL,NULL,NULL),(70,40,5,NULL,NULL,NULL),(71,40,6,NULL,NULL,NULL),(72,40,7,NULL,NULL,NULL),(73,40,8,NULL,NULL,NULL),(74,40,9,NULL,NULL,NULL),(75,41,1,NULL,NULL,NULL),(76,41,2,NULL,NULL,NULL),(77,41,3,NULL,NULL,NULL),(78,41,4,NULL,NULL,NULL),(79,41,5,NULL,NULL,NULL),(80,41,6,NULL,NULL,NULL),(81,41,7,NULL,NULL,NULL),(82,41,8,NULL,NULL,NULL),(83,41,9,NULL,NULL,NULL),(84,42,1,NULL,NULL,NULL),(85,42,2,NULL,NULL,NULL),(86,42,3,NULL,NULL,NULL),(87,42,4,NULL,NULL,NULL),(88,42,5,NULL,NULL,NULL),(89,42,6,NULL,NULL,NULL),(90,42,7,NULL,NULL,NULL),(91,42,8,NULL,NULL,NULL),(92,42,9,NULL,NULL,NULL),(93,43,1,NULL,NULL,NULL),(94,43,2,NULL,NULL,NULL),(95,43,3,NULL,NULL,NULL),(96,43,4,NULL,NULL,NULL),(97,43,5,NULL,NULL,NULL),(98,43,6,NULL,NULL,NULL),(99,43,7,NULL,NULL,NULL),(100,43,8,NULL,NULL,NULL),(101,43,9,NULL,NULL,NULL),(102,44,1,NULL,NULL,NULL),(103,44,2,NULL,NULL,NULL),(104,44,3,NULL,NULL,NULL),(105,44,4,NULL,NULL,NULL),(106,44,5,NULL,NULL,NULL),(107,44,6,NULL,NULL,NULL),(108,44,7,NULL,NULL,NULL),(109,44,8,NULL,NULL,NULL),(110,44,9,NULL,NULL,NULL),(111,45,1,NULL,NULL,NULL),(112,45,2,NULL,NULL,NULL),(113,45,3,NULL,NULL,NULL),(114,45,4,NULL,NULL,NULL),(115,45,5,NULL,NULL,NULL),(116,45,6,NULL,NULL,NULL),(117,45,7,NULL,NULL,NULL),(118,45,8,NULL,NULL,NULL),(119,45,9,NULL,NULL,NULL),(120,46,1,NULL,NULL,NULL),(121,46,2,NULL,NULL,NULL),(122,46,3,NULL,NULL,NULL),(123,46,4,NULL,NULL,NULL),(124,46,5,NULL,NULL,NULL),(125,46,6,NULL,NULL,NULL),(126,46,7,NULL,NULL,NULL),(127,46,8,NULL,NULL,NULL),(128,46,9,NULL,NULL,NULL),(129,47,1,NULL,NULL,NULL),(130,47,2,NULL,NULL,NULL),(131,47,3,NULL,NULL,NULL),(132,47,4,NULL,NULL,NULL),(133,47,5,NULL,NULL,NULL),(134,47,6,NULL,NULL,NULL),(135,47,7,NULL,NULL,NULL),(136,47,8,NULL,NULL,NULL),(137,47,9,NULL,NULL,NULL),(138,49,1,NULL,NULL,NULL),(139,49,2,NULL,NULL,NULL),(140,49,3,NULL,NULL,NULL),(141,49,4,NULL,NULL,NULL),(142,49,5,NULL,NULL,NULL),(143,49,6,NULL,NULL,NULL),(144,49,7,NULL,NULL,NULL),(145,49,8,NULL,NULL,NULL),(146,49,9,NULL,NULL,NULL);
/*!40000 ALTER TABLE `licence_features` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `licence_types`
--

DROP TABLE IF EXISTS `licence_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `licence_types` (
  `licence_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `licence_type_name` varchar(105) DEFAULT NULL,
  `licence_nb_equipment` int(11) DEFAULT NULL COMMENT 'Nombre d''équipement autorisé',
  `licence_nb_transactions_day` int(11) DEFAULT NULL COMMENT 'nombre de transactions par jour',
  `is_active` tinyint(4) DEFAULT 1,
  `data_version` int(11) DEFAULT NULL,
  PRIMARY KEY (`licence_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `licence_types`
--

LOCK TABLES `licence_types` WRITE;
/*!40000 ALTER TABLE `licence_types` DISABLE KEYS */;
INSERT INTO `licence_types` VALUES (1,'ECO',2,500,1,1),(2,'BUSINESS',15,15000,1,1),(3,'TEST',1,100,1,1);
/*!40000 ALTER TABLE `licence_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `licences`
--

DROP TABLE IF EXISTS `licences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `licences` (
  `licence_id` int(11) NOT NULL AUTO_INCREMENT,
  `licence_key` varchar(100) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `created_by_email` varchar(100) DEFAULT NULL,
  `licence_file_url` varchar(100) DEFAULT NULL,
  `is_for_equipement_flag` tinyint(1) DEFAULT NULL,
  `application_uuid` varchar(105) DEFAULT NULL,
  `data_version` varchar(105) DEFAULT NULL,
  `created_for_company_id` int(11) DEFAULT NULL COMMENT 'Créer pour tel compagnie',
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `activation_date` datetime DEFAULT NULL COMMENT 'Date d''activation de la licence',
  `expiration_date` datetime DEFAULT NULL COMMENT 'Date d''expiration',
  `licence_parent_id` int(11) DEFAULT NULL COMMENT 'Détermine si c''est une licence secondaire',
  `licence_type_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`licence_id`),
  KEY `fk_licences_licence_types1_idx` (`licence_type_id`),
  KEY `fk_licences_user_idx` (`user_id`),
  CONSTRAINT `fk_licences_licence_types1` FOREIGN KEY (`licence_type_id`) REFERENCES `licence_types` (`licence_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `licences`
--

LOCK TABLES `licences` WRITE;
/*!40000 ALTER TABLE `licences` DISABLE KEYS */;
INSERT INTO `licences` VALUES (32,'5ec43-19f0e-3f81d-3ac4f-aa149','2021-09-22 23:22:00',NULL,NULL,0,NULL,NULL,3,0,NULL,'2021-10-20 00:00:00',NULL,2,NULL),(33,'0d5bc-03d72-2448d-31154-bfd8b','2021-09-22 23:22:00',NULL,NULL,1,'07fb1b03-f70c-49f5-81f2-53386ca5a039',NULL,3,1,'2021-09-22 00:00:00','2021-10-22 00:00:00',32,NULL,NULL),(34,'791e4-19ede-cd317-bce50-f0336','2021-09-22 23:22:00',NULL,NULL,1,NULL,NULL,3,0,NULL,NULL,32,NULL,NULL),(35,'d903c-8bd08-5978c-389db-291e1','2021-09-22 23:22:00',NULL,NULL,1,NULL,NULL,3,0,NULL,NULL,32,NULL,NULL),(36,'8949d-0cdd6-9277e-4966e-f0731','2021-09-22 23:22:00',NULL,NULL,1,NULL,NULL,3,0,NULL,NULL,32,NULL,NULL),(37,'edf8d-51918-1f3c5-20446-e4c85','2021-09-22 23:22:00',NULL,NULL,1,NULL,NULL,3,0,NULL,NULL,32,NULL,NULL),(38,'dd985-fe732-d17b4-34472-0c9d8','2021-09-22 23:22:00',NULL,NULL,1,NULL,NULL,3,0,NULL,NULL,32,NULL,NULL),(39,'73c12-45225-8d5b9-c108c-4eb3c','2021-09-22 23:22:00',NULL,NULL,1,NULL,NULL,3,0,NULL,NULL,32,NULL,NULL),(40,'5d630-8e9c4-b4a92-539f9-69ad5','2021-09-22 23:22:00',NULL,NULL,1,NULL,NULL,3,0,NULL,NULL,32,NULL,NULL),(41,'5e79b-badd2-10198-97e04-2a95f','2021-09-22 23:22:00',NULL,NULL,1,NULL,NULL,3,0,NULL,NULL,32,NULL,NULL),(42,'a948e-7081b-f917a-b01e4-523b7','2021-09-22 23:22:00',NULL,NULL,1,NULL,NULL,3,0,NULL,NULL,32,NULL,NULL),(43,'9f7ad-34103-b3807-dc83b-9d5d5','2021-09-22 23:22:00',NULL,NULL,1,NULL,NULL,3,0,NULL,NULL,32,NULL,NULL),(44,'49d1e-2a2d3-05aef-60491-afe4f','2021-09-22 23:22:00',NULL,NULL,1,NULL,NULL,3,0,NULL,NULL,32,NULL,NULL),(45,'cc77c-6ae8a-688f2-e485f-c4d23','2021-09-22 23:22:00',NULL,NULL,1,NULL,NULL,3,0,NULL,NULL,32,NULL,NULL),(46,'791c1-20dcd-bc2c0-43cf5-3e460','2021-09-22 23:22:00',NULL,NULL,1,NULL,NULL,3,0,NULL,NULL,32,NULL,NULL),(47,'5e1c7-dd378-09eac-6498b-1cd8c','2021-09-22 23:22:00',NULL,NULL,1,NULL,NULL,3,0,NULL,NULL,32,NULL,NULL),(48,'5f385-218dd-f0352-a0292-d86f0','2021-09-28 10:14:00',NULL,NULL,0,NULL,NULL,4,0,NULL,'2021-10-27 00:00:00',NULL,3,NULL),(49,'90e5a-a6e06-d4499-f317f-d5f94','2021-09-28 10:14:00',NULL,NULL,1,NULL,NULL,4,0,NULL,NULL,48,NULL,NULL),(50,'5e63e-d2490-1aa91-66b3f-1029d','2021-09-29 13:20:00',NULL,NULL,1,NULL,NULL,NULL,1,'2021-09-29 13:20:00','2021-10-30 00:00:00',NULL,NULL,NULL),(51,'b16ff-a5d4e-58e7e-610e7-766f4','2021-09-29 13:21:00',NULL,NULL,1,NULL,NULL,NULL,1,'2021-09-29 13:21:00','2021-11-30 00:00:00',NULL,NULL,NULL),(52,'67fd0-e2797-5294f-ca413-de861','2021-09-29 13:22:00',NULL,NULL,1,NULL,NULL,NULL,1,'2021-09-29 13:22:00','2021-09-30 00:00:00',NULL,NULL,NULL),(53,'48c52-a6a1a-3ac85-f8f3c-60c8c','2021-09-29 13:26:00',NULL,NULL,1,NULL,NULL,NULL,1,'2021-09-29 13:26:00',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `licences` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `operation_types`
--

DROP TABLE IF EXISTS `operation_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `operation_types` (
  `operation_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(100) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `comments` varchar(105) DEFAULT NULL,
  `data_version` varchar(105) DEFAULT NULL,
  PRIMARY KEY (`operation_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `operation_types`
--

LOCK TABLES `operation_types` WRITE;
/*!40000 ALTER TABLE `operation_types` DISABLE KEYS */;
INSERT INTO `operation_types` VALUES (1,'DEPOT','2021-07-08 12:29:42','DEPOT','2'),(2,'RETRAIT','2021-07-08 12:29:42','RETRAIT','2'),(3,'FORFAIT MAXI APPEL','2021-07-08 14:42:04','forfait maxi appel','2'),(4,'FORFAIT MAXI INTERNET','2021-07-08 14:42:04','forfait maxi internet','2'),(5,'PASS BONUS APPEL','2021-07-16 10:50:38','pass bonus appel','2'),(6,'PASS BONUS INTERNET','2021-07-16 10:55:12','pass bonus internet','2'),(7,'FORFAIT INTERNET','2021-08-02 14:24:11','forfait internet','2'),(8,'CREDIT','2021-08-02 14:57:06','Vente de crédits','2');
/*!40000 ALTER TABLE `operation_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `operations`
--

DROP TABLE IF EXISTS `operations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `operations` (
  `operation_id` int(11) NOT NULL AUTO_INCREMENT,
  `operation_type_id` int(11) NOT NULL,
  `company_token` varchar(45) DEFAULT NULL,
  `libelle` varchar(45) DEFAULT NULL,
  `application_uuid` varchar(105) DEFAULT NULL,
  `data_version` varchar(105) DEFAULT NULL,
  `balance_after_operate` int(11) DEFAULT NULL COMMENT 'solde après operation',
  `amount` int(11) DEFAULT NULL,
  `operation_date` datetime DEFAULT NULL,
  `network_operator_name` varchar(45) DEFAULT NULL,
  `statut_operation` varchar(150) DEFAULT NULL,
  `transaction_phone_number` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`operation_id`),
  KEY `fk_operations_operation_types1_idx` (`operation_type_id`),
  CONSTRAINT `fk_operations_operation_types1` FOREIGN KEY (`operation_type_id`) REFERENCES `operation_types` (`operation_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `operations`
--

--
-- Table structure for table `password_histories`
--

DROP TABLE IF EXISTS `password_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_histories` (
  `history_id` int(11) NOT NULL AUTO_INCREMENT,
  `logout_date` datetime DEFAULT NULL,
  `login_date` datetime DEFAULT NULL,
  `schedule_reset_date` datetime DEFAULT NULL,
  `effective_reset_date` datetime DEFAULT NULL,
  `next_reset_date` datetime DEFAULT NULL,
  `is_effective` tinyint(4) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`history_id`),
  KEY `fk_rapport_user_idx` (`user_id`),
  CONSTRAINT `fk_rapport_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_histories`
--

LOCK TABLES `password_histories` WRITE;
/*!40000 ALTER TABLE `password_histories` DISABLE KEYS */;
INSERT INTO `password_histories` VALUES (29,NULL,'2021-09-22 22:31:37','2021-09-29 22:31:37',NULL,NULL,0,8),(30,NULL,'2021-09-23 10:27:08','2021-09-30 10:27:08',NULL,NULL,0,8),(31,NULL,'2021-09-23 11:09:16','2021-09-30 11:09:16',NULL,NULL,0,8),(32,NULL,'2021-09-23 15:11:13','2021-09-30 15:11:13',NULL,NULL,0,8),(33,NULL,'2021-09-23 16:32:53','2021-09-30 16:32:53',NULL,NULL,0,8),(34,NULL,'2021-09-23 16:35:32','2021-09-30 16:35:32',NULL,NULL,0,8),(35,NULL,'2021-09-23 21:47:06','2021-09-30 21:47:06',NULL,NULL,0,8),(36,NULL,'2021-09-24 08:49:09','2021-10-01 08:49:09',NULL,NULL,0,8),(37,NULL,'2021-09-27 10:16:09','2021-10-04 10:16:09',NULL,NULL,0,8),(38,NULL,'2021-09-29 06:55:28','2021-10-06 06:55:28',NULL,NULL,0,8),(39,NULL,'2021-09-29 16:41:33','2021-10-06 16:41:33',NULL,NULL,0,8);
/*!40000 ALTER TABLE `password_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `services` (
  `service_id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(30) NOT NULL,
  `descriptions` text NOT NULL,
  PRIMARY KEY (`service_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES (1,'FORFAIT INTERNET','Test desciption'),(2,'FORFAIT MAXI APPEL','Test description service 2'),(3,'PASS BONUS APPEL','Test description service 3'),(4,'INVENTAIRE',''),(5,'PASS BONUS INTERNET',''),(6,'FORFAIT MAXI INTERNET',''),(7,'CREDIT',''),(8,'DEPOT',''),(9,'RETRAIT','');
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sms_tracker`
--

DROP TABLE IF EXISTS `sms_tracker`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sms_tracker` (
  `tracker_id` int(11) NOT NULL AUTO_INCREMENT,
  `from_number` varchar(100) DEFAULT NULL,
  `from_name` varchar(100) DEFAULT 'GEMO',
  `to_phone_number` text DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `content` mediumtext DEFAULT NULL,
  `delivery_flag` tinyint(1) NOT NULL DEFAULT 0,
  `user_operation_id` int(11) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `last_attempt_date` datetime DEFAULT NULL,
  `sms_sent_date` datetime DEFAULT NULL,
  `category` varchar(100) NOT NULL DEFAULT 'TRACKING',
  PRIMARY KEY (`tracker_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sms_tracker`
--

LOCK TABLES `sms_tracker` WRITE;
/*!40000 ALTER TABLE `sms_tracker` DISABLE KEYS */;
/*!40000 ALTER TABLE `sms_tracker` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_connections`
--

DROP TABLE IF EXISTS `user_connections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_connections` (
  `connection_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `application_uuid` varchar(150) DEFAULT NULL,
  `connection_date` date DEFAULT NULL,
  `sheduled_deconnection_date` date DEFAULT NULL,
  `deconnection_date` date DEFAULT NULL,
  PRIMARY KEY (`connection_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_connections`
--

LOCK TABLES `user_connections` WRITE;
/*!40000 ALTER TABLE `user_connections` DISABLE KEYS */;
INSERT INTO `user_connections` VALUES (12,8,'8a81f79e-a8b1-4e75-a2aa-96971852e552','2021-09-29',NULL,NULL);
/*!40000 ALTER TABLE `user_connections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_has_features`
--

DROP TABLE IF EXISTS `user_has_features`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_has_features` (
  `user_has_licence_id` int(11) NOT NULL AUTO_INCREMENT,
  `licence_activation_date` varchar(45) DEFAULT NULL,
  `licence_feature_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `company_token` varchar(105) DEFAULT NULL,
  `active_date_from` date DEFAULT NULL,
  `active_date_to` date DEFAULT NULL,
  `application_uuid` varchar(105) DEFAULT NULL,
  `data_version` varchar(105) DEFAULT NULL,
  PRIMARY KEY (`user_has_licence_id`),
  KEY `fk_user_has_features_licence_features1_idx` (`licence_feature_id`),
  KEY `fk_user_has_features_users1_idx` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=152 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_has_features`
--

LOCK TABLES `user_has_features` WRITE;
/*!40000 ALTER TABLE `user_has_features` DISABLE KEYS */;
INSERT INTO `user_has_features` VALUES (143,'2021-09-22 00:00:00',3,8,NULL,'2021-09-22','2021-10-22','07fb1b03-f70c-49f5-81f2-53386ca5a039','1'),(144,'2021-09-22 00:00:00',4,8,NULL,'2021-09-22','2021-10-22','07fb1b03-f70c-49f5-81f2-53386ca5a039','1'),(145,'2021-09-22 00:00:00',5,8,NULL,'2021-09-22','2021-10-22','07fb1b03-f70c-49f5-81f2-53386ca5a039','1'),(146,'2021-09-22 00:00:00',6,8,NULL,'2021-09-22','2021-10-22','07fb1b03-f70c-49f5-81f2-53386ca5a039','1'),(147,'2021-09-22 00:00:00',7,8,NULL,'2021-09-22','2021-10-22','07fb1b03-f70c-49f5-81f2-53386ca5a039','1'),(148,'2021-09-22 00:00:00',8,8,NULL,'2021-09-22','2021-10-22','07fb1b03-f70c-49f5-81f2-53386ca5a039','1'),(149,'2021-09-22 00:00:00',9,8,NULL,'2021-09-22','2021-10-22','07fb1b03-f70c-49f5-81f2-53386ca5a039','1'),(150,'2021-09-22 00:00:00',10,8,NULL,'2021-09-22','2021-10-22','07fb1b03-f70c-49f5-81f2-53386ca5a039','1'),(151,'2021-09-22 00:00:00',11,8,NULL,'2021-09-22','2021-10-22','07fb1b03-f70c-49f5-81f2-53386ca5a039','1');
/*!40000 ALTER TABLE `user_has_features` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_operations`
--

DROP TABLE IF EXISTS `user_operations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_operations` (
  `user_operation_id` int(11) NOT NULL AUTO_INCREMENT,
  `operation_date` datetime DEFAULT NULL,
  `created_by_user_id` int(11) DEFAULT NULL,
  `operation_id` int(11) NOT NULL,
  `company_token` varchar(45) DEFAULT NULL,
  `inventory_id` int(11) DEFAULT NULL,
  `application_uuid` varchar(105) DEFAULT NULL,
  `data_version` varchar(105) DEFAULT NULL,
  `statut_operation` varchar(150) DEFAULT NULL,
  `transaction_phone_number` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`user_operation_id`),
  KEY `fk_user_operations_users1_idx` (`created_by_user_id`),
  KEY `fk_user_operations_operations1_idx` (`operation_id`),
  KEY `fk_user_operations_inventories1_idx` (`inventory_id`),
  CONSTRAINT `fk_user_operations_inventories1` FOREIGN KEY (`inventory_id`) REFERENCES `inventories` (`inventory_id`),
  CONSTRAINT `fk_user_operations_operations1` FOREIGN KEY (`operation_id`) REFERENCES `operations` (`operation_id`),
  CONSTRAINT `fk_user_operations_users1` FOREIGN KEY (`created_by_user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_operations`
--


--
-- Table structure for table `users`
--


DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone_number` varchar(100) DEFAULT NULL,
  `encrypted_password` varchar(100) DEFAULT NULL,
  `hash` varchar(100) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `last_update_date` datetime DEFAULT NULL,
  `address_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `is_active_flag` tinyint(1) DEFAULT NULL,
  `active_date_from` date DEFAULT NULL,
  `active_date_to` date DEFAULT NULL,
  `is_manager` tinyint(1) DEFAULT NULL,
  `company_token` varchar(105) DEFAULT NULL,
  `application_uuid` varchar(105) DEFAULT NULL,
  `data_version` varchar(105) DEFAULT NULL,
  `licence_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  KEY `fk_users_addresses1_idx` (`address_id`),
  KEY `fk_users_companies1_idx` (`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contraintes pour les tables déchargées
--

INSERT INTO `users` (`user_id`, `firstname`, `lastname`, `email`, `phone_number`, `encrypted_password`, `hash`, `creation_date`, `last_update_date`, `address_id`, `company_id`, `is_active_flag`, `active_date_from`, `active_date_to`, `is_manager`, `company_token`, `application_uuid`, `data_version`, `licence_id`) VALUES
(1, 'AROUNA', 'Hafiz', 'test@test.com', '66152976', '$2y$10$OIEiX7O6f5Sie5i.zDclnexoqJI.W89xPxxOEovStEpcE/U0Wbdpa', '0cbc6611f5540bd0809a388dc95a615b', '2021-08-16 21:21:00', NULL, 1, 2, 1, '2021-08-16', '2021-08-27', 0, 'TEST08072021', '041750ce-e473-462c-af82-99567dc25b4b', '1', NULL),
(2, 'BAKARI', 'Mariama So Arouna', '', '0666265571', '$2y$10$/nssyIy7Z7OqAdNFj06K8urvevEk6gB3n0CxgX.kDOsqs..y/1XJi', '0cbc6611f5540bd0809a388dc95a615b', '2021-08-17 18:46:00', NULL, 1, 2, 1, '2021-08-17', '2021-08-20', 2, 'DKO', NULL, '1', NULL),
(3, 'DIAZ GARRIGOS', 'Carlos', 'brancom554@gmail.com', '94570130', '$2y$10$/sxchINV6nk.oaY4x6ZpguuHDFoces5/VmSImVGDOggbro7P2Yaim', '882baf28143fb700b388a87ef561a6e5', '2021-09-22 23:22:00', '2021-09-22 23:12:00', 3, 3, 1, '2021-09-22', '2021-10-20', 1, NULL, 'carlosdiaz', '1', NULL),
(8, 'GOLDMAN', 'Sharon', 'incnova3@gmail.com', '96209396', '$2y$10$V126r4PLHvMMfUkN6Gk0oegJrjSUQ450lzvOKmG7GfClc0tI5vJ9C', '1632400367', '2021-09-22 23:29:00', NULL, 2, 3, 1, '2021-09-22', '2021-10-28', 0, NULL, 'sharon', '1', NULL);

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_addresses1` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`address_id`),
  ADD CONSTRAINT `fk_users_companies1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`company_id`);
--
-- Table structure for table `validate_password`
--

DROP TABLE IF EXISTS `validate_password`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `validate_password` (
  `validate_id` int(11) NOT NULL AUTO_INCREMENT,
  `verify_code` varchar(45) DEFAULT NULL,
  `created_date` datetime NOT NULL,
  `is_used` tinyint(4) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`validate_id`),
  KEY `fk_validate_password_user1_idx` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `validate_password`
--


/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-09-30  9:45:48
