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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `addresses`
--
INSERT INTO `addresses` VALUES (1,'Rue de l\'Echangeur de Godomey','00229','2021-08-21',NULL,'Godomey',2,1,'DKO',NULL,'1');


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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` VALUES 
(1,'CREDIT','2021-07-08 12:30:47',1,'SERVICES TELEPHONIQUES',1),
(2,'FORFAIT MAXI APPEL','2021-07-08 12:30:47',1,'SERVICES TELEPHONIQUES',1),
(3,'DEPOT','2021-07-08 12:30:47',2,'SERVICES FINANCIERS',1),
(4,'SOLDE TELEPHONIQUE','2021-07-16 10:52:50',1,'SERVICES TELEPHONIQUES',1),
(5,'SOLDE SIM','2021-07-16 10:52:50',2,'SERVICES FINANCIERS',1),
(6,'RETRAIT','2021-07-21 08:54:58',2,'SERVICES FINANCIERS',1),
(7,'FORFAIT MAXI INTERNET','2021-08-02 15:55:03',1,'SERVICES TELEPHONIQUES',1),
(8,'FORFAIT INTERNET','2021-08-02 15:55:03',1,'SERVICES TELEPHONIQUES',1),
(9,'PASS BONUS APPEL','2021-08-26 10:19:32',1,'SERVICES TELEPHONIQUES',3),
(10,'PASS BONUS INTERNET','2021-08-26 10:19:32',1,'SERVICES TELEPHONIQUES',3),
(11,'GOPACK','2021-08-26 10:19:32',1,'SERVICES TELEPHONIQUES',3);



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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category_ussd`
--


INSERT INTO `category_ussd` VALUES 
(1,'*855*1*1*1*',3,1,'TEST08072021','041750ce-e473-462c-af82-99567dc25b4b','3',2,'MOOV'),
(2,'*880*3*1*',3,1,'TEST08072021','041750ce-e473-462c-af82-99567dc25b4b','3',1,'MTN'),
(3,'*173*5*1#',4,6,NULL,NULL,'3',2,'MOOV'),
(4,'*880*2*1*',6,2,NULL,NULL,'3',1,'MTN'),
(5,'*855*4*1*',6,2,NULL,NULL,'3',2,'MOOV'),
(6,'*106*10*3*',7,7,NULL,NULL,'3',1,'MTN'),
(7,'*173*1*',10,7,NULL,NULL,'3',2,'MOOV'),
(8,'*106*10*1*',8,4,NULL,NULL,'3',1,'MTN'),
(9,'*123*7*',8,4,NULL,NULL,'3',2,'MOOV'),
(10,'*106*10*3*',2,3,NULL,NULL,'3',1,'MTN'),
(11,'*173*1*',9,8,NULL,NULL,'3',2,'MOOV'),
(12,'*106*',1,8,NULL,NULL,'3',2,'MOOV'),
(13,'*106*2*',1,8,NULL,NULL,'3',1,'MTN'),
(14,'*106*2*',1,9,NULL,NULL,'3',1,'MTN');


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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` VALUES (1,'RCCM021544','TEST0','2021-07-08 12:24:10','TEST0','1234567888','041750ce-e473-462c-af82-99567dc25b4b','1');


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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` VALUES (1,'BENIN','BJ','2021-07-08 12:21:08','1'),(2,'FRANCE','FR','2021-07-08 12:21:08','1');


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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `database_version`
--

INSERT INTO `database_version` VALUES (1,3,'2021-08-13 00:22:42');
/*!40000 ALTER TABLE `database_version` ENABLE KEYS */;


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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventories`
--


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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_detail`
--


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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `licence_features`
--

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `licence_types`
--

/*!40000 ALTER TABLE `licence_types` DISABLE KEYS */;
INSERT INTO `licence_types` VALUES (1,'ECO',2,500,1,1),(2,'BUSINESS',15,15000,1,1),(3,'TEST',1,100,1,1);


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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `licences`
--


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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


INSERT INTO `operation_types` VALUES  (1,'DEPOT','2021-07-08 12:29:42','DEPOT','2'),
(2,'RETRAIT','2021-07-08 12:29:42','RETRAIT','2'),
(3,'FORFAIT MAXI APPEL','2021-07-08 14:42:04','forfait maxi appel','2'),
(4,'FORFAIT MAXI INTERNET','2021-07-08 14:42:04','forfait maxi internet','2'),
(5,'PASS BONUS APPEL','2021-07-16 10:50:38','pass bonus appel','2'),
(6,'PASS BONUS INTERNET','2021-07-16 10:55:12','pass bonus internet','2'),
(7,'FORFAIT INTERNET','2021-08-02 14:24:11','forfait internet','2'),
(8,'CREDIT','2021-08-02 14:57:06','Vente de crédits','2'),
(9,'GOPACK','2021-08-02 14:57:06','Forfait go pack','2');


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
  `operation_id_source` int(11) DEFAULT NULL,
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_histories`
--


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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

INSERT INTO `services` VALUES (1,'FORFAIT INTERNET','Test desciption'),
(2,'FORFAIT MAXI APPEL','Test description service 2'),
(3,'PASS BONUS APPEL','Test description service 3'),
(4,'INVENTAIRE',''),
(5,'PASS BONUS INTERNET',''),
(6,'FORFAIT MAXI INTERNET',''),
(7,'CREDIT',''),
(8,'DEPOT',''),
(9,'RETRAIT',''),
(10,'GOPACK','') ;

/*!40000 ALTER TABLE `services` ENABLE KEYS */;


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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;



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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_has_features`
--

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
  `operation_id_source` int(11) DEFAULT NULL,
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
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
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
  KEY `fk_users_companies1_idx` (`company_id`),
  CONSTRAINT `fk_users_addresses1` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`address_id`),
  CONSTRAINT `fk_users_companies1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

INSERT INTO `users` VALUES (1,'SUPER ADMIN','GEMO','','61292948','$2y$10$/nssyIy7Z7OqAdNFj06K8urvevEk6gB3n0CxgX.kDOsqs..y/1XJi','0cbc6611f5540bd0809a388dc95a615b','2021-08-17 18:46:00',NULL,1,1,1,'2021-08-17','2021-08-20',2,'DKO',NULL,'1',NULL);

--
-- Table structure for table `validate_password`
--


CREATE TABLE `validate_password` (
  `validate_id` int(11) NOT NULL AUTO_INCREMENT,
  `verify_code` varchar(45) DEFAULT NULL,
  `created_date` datetime NOT NULL,
  `is_used` tinyint(4) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`validate_id`),
  KEY `fk_validate_password_user1_idx` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-10-08  9:35:18
