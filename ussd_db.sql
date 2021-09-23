-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 09, 2021 at 08:52 AM
-- Server version: 8.0.23
-- PHP Version: 7.4.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ussd_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `address_id` int NOT NULL,
  `postal_address` varchar(250) DEFAULT NULL,
  `postal_code` varchar(50) DEFAULT NULL,
  `creation_date` date DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `company_id` int NOT NULL,
  `country_id` int NOT NULL,
  `company_token` varchar(105) DEFAULT NULL,
  `application_uuid` varchar(105) DEFAULT NULL,
  `data_version` varchar(105) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`address_id`, `postal_address`, `postal_code`, `creation_date`, `updated_date`, `city`, `company_id`, `country_id`, `company_token`, `application_uuid`, `data_version`) VALUES
(1, 'Avenue Steinmetz', '00229', '2021-07-08', NULL, 'Cotonou', 1, 1, 'TEST08072021', '041750ce-e473-462c-af82-99567dc25b4b', '1');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int NOT NULL,
  `libelle` varchar(200) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `type_category` int DEFAULT NULL,
  `type_category_libelle` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `libelle`, `creation_date`, `type_category`, `type_category_libelle`) VALUES
(1, 'CREDIT', '2021-07-08 12:30:47', 1, 'SERVICES TELEPHONIQUES'),
(2, 'FORFAIT APPEL', '2021-07-08 12:30:47', 1, 'SERVICES TELEPHONIQUES'),
(3, 'DEPOT', '2021-07-08 12:30:47', 2, 'SERVICES FINANCIERS'),
(4, 'SOLDE TELEPHONIQUE', '2021-07-16 10:52:50', 1, 'SERVICES TELEPHONIQUES'),
(5, 'SOLDE SIM', '2021-07-16 10:52:50', 2, 'SERVICES FINANCIERS'),
(6, 'RETRAIT', '2021-07-21 08:54:58', 2, 'SERVICES FINANCIERS'),
(7, 'FORFAIT APPEL ET INTERNET', '2021-08-02 15:55:03', 1, 'SERVICES TELEPHONIQUES'),
(8, 'FORFAIT INTERNET', '2021-08-02 15:55:03', 1, 'SERVICES TELEPHONIQUES');

-- --------------------------------------------------------

--
-- Table structure for table `category_ussd`
--

CREATE TABLE `category_ussd` (
  `category_ussd_id` int NOT NULL,
  `ussd_code` varchar(150) DEFAULT NULL,
  `category_id` int NOT NULL,
  `operation_type_id` int NOT NULL,
  `company_token` varchar(105) DEFAULT NULL,
  `application_uuid` varchar(105) DEFAULT NULL,
  `data_version` varchar(105) DEFAULT NULL,
  `network_operator_number` int DEFAULT NULL,
  `network_operator_name` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `category_ussd`
--

INSERT INTO `category_ussd` (`category_ussd_id`, `ussd_code`, `category_id`, `operation_type_id`, `company_token`, `application_uuid`, `data_version`, `network_operator_number`, `network_operator_name`) VALUES
(1, '*855*1*1*1*', 3, 1, 'TEST08072021', '041750ce-e473-462c-af82-99567dc25b4b', '1', 2, 'MOOV'),
(2, '*880*1*1*', 3, 1, 'TEST08072021', '041750ce-e473-462c-af82-99567dc25b4b', '1', 1, 'MTN'),
(3, '*173*5*1#', 4, 6, NULL, NULL, '1', 2, 'MOOV'),
(4, '*880*2*2*1*', 6, 2, NULL, NULL, '1', 1, 'MTN'),
(5, '*855*4*1*', 6, 2, NULL, NULL, '1', 2, 'MOOV'),
(6, '*106*10*3*', 7, 7, NULL, NULL, NULL, 1, 'MTN'),
(7, '*106*10*3*', 7, 7, NULL, NULL, NULL, 2, 'MOOV'),
(8, '*106*10*1*', 8, 4, NULL, NULL, NULL, 1, 'MTN'),
(9, '*106*10*1*', 8, 4, NULL, NULL, NULL, 2, 'MOOV'),
(10, '*106*2*', 2, 3, NULL, NULL, NULL, 1, 'MTN'),
(11, '*106*2*', 2, 8, NULL, NULL, NULL, 2, 'MOOV');

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `company_id` int NOT NULL,
  `company_number` varchar(150) DEFAULT NULL COMMENT 'RCCM',
  `company_name` varchar(100) NOT NULL,
  `creation_date` datetime DEFAULT NULL,
  `company_token` varchar(105) DEFAULT NULL,
  `registration_number` varchar(65) DEFAULT NULL COMMENT 'IFU number',
  `application_uuid` varchar(105) DEFAULT NULL,
  `data_version` varchar(105) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`company_id`, `company_number`, `company_name`, `creation_date`, `company_token`, `registration_number`, `application_uuid`, `data_version`) VALUES
(1, 'RCCM021544', 'SOCIETE TEST', '2021-07-08 12:24:10', 'TEST08072021', '1234567888', '041750ce-e473-462c-af82-99567dc25b4b', '1');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `country_id` int NOT NULL,
  `description` varchar(105) DEFAULT NULL,
  `country_short_name` varchar(45) NOT NULL,
  `creation_date` datetime DEFAULT NULL,
  `data_version` varchar(105) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`country_id`, `description`, `country_short_name`, `creation_date`, `data_version`) VALUES
(1, 'BENIN', 'BJ', '2021-07-08 12:21:08', '1'),
(2, 'FRANCE', 'FR', '2021-07-08 12:21:08', '1');

-- --------------------------------------------------------

--
-- Table structure for table `database_version`
--

CREATE TABLE `database_version` (
  `database_version_id` int NOT NULL,
  `current_version` int DEFAULT NULL,
  `last_update_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `inventories`
--

CREATE TABLE `inventories` (
  `inventory_id` int NOT NULL,
  `date_from` date DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `report_file_url` varchar(155) DEFAULT NULL,
  `is_finished_flag` tinyint(1) DEFAULT NULL,
  `company_token` varchar(105) DEFAULT NULL,
  `inventory_name` varchar(105) DEFAULT NULL,
  `application_uuid` varchar(105) DEFAULT NULL,
  `data_version` varchar(105) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `licences`
--

CREATE TABLE `licences` (
  `licence_id` int NOT NULL,
  `licence_key` varchar(100) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `created_by_email` varchar(100) DEFAULT NULL,
  `licence_file_url` varchar(100) DEFAULT NULL,
  `is_for_equipement_flag` tinyint(1) DEFAULT NULL,
  `application_uuid` varchar(105) DEFAULT NULL,
  `data_version` varchar(105) DEFAULT NULL,
  `created_for_company_id` int DEFAULT NULL COMMENT 'Créer pour tel compagnie',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `activation_date` datetime DEFAULT NULL COMMENT 'Date d''activation de la licence',
  `expiration_date` datetime DEFAULT NULL COMMENT 'Date d''expiration'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `licences`
--

INSERT INTO `licences` (`licence_id`, `licence_key`, `creation_date`, `created_by_email`, `licence_file_url`, `is_for_equipement_flag`, `application_uuid`, `data_version`, `created_for_company_id`, `is_active`, `activation_date`, `expiration_date`) VALUES
(1, '534c0-c4e21-265f5-c2282-54663', '2021-08-06 01:59:00', NULL, NULL, 1, '1', NULL, 1, 1, NULL, '2021-08-31 00:00:00'),
(2, '986e8-784cc-4622a-980ab-54b77', '2021-08-06 10:45:00', NULL, NULL, 1, NULL, NULL, 1, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `licence_features`
--

CREATE TABLE `licence_features` (
  `licence_feature_id` int NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `libelle` varchar(150) DEFAULT NULL,
  `licence_id` int NOT NULL,
  `company_token` varchar(105) DEFAULT NULL,
  `application_uuid` varchar(105) DEFAULT NULL,
  `data_version` varchar(105) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `operations`
--

CREATE TABLE `operations` (
  `operation_id` int NOT NULL,
  `operation_type_id` int NOT NULL,
  `company_token` varchar(45) DEFAULT NULL,
  `libelle` varchar(45) DEFAULT NULL,
  `application_uuid` varchar(105) DEFAULT NULL,
  `data_version` varchar(105) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `operations`
--

INSERT INTO `operations` (`operation_id`, `operation_type_id`, `company_token`, `libelle`, `application_uuid`, `data_version`) VALUES
(1, 3, 'TEST08072021', '200F', '041750ce-e473-462c-af82-99567dc25b4b', '1'),
(2, 3, 'TEST08072021', '500F', '041750ce-e473-462c-af82-99567dc25b4b', '1'),
(3, 3, 'TEST08072021', '1000F', '041750ce-e473-462c-af82-99567dc25b4b', '1'),
(4, 5, 'TEST08072021', 'SOLDE', '041750ce-e473-462c-af82-99567dc25b4b', '1'),
(5, 5, 'TEST08072021', 'SOLDE MOMO', '041750ce-e473-462c-af82-99567dc25b4b', '1'),
(6, 2, 'TEST08072021', 'RETRAIT NATIONAL', NULL, '1');

-- --------------------------------------------------------

--
-- Table structure for table `operation_types`
--

CREATE TABLE `operation_types` (
  `operation_type_id` int NOT NULL,
  `libelle` varchar(100) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `comments` varchar(105) DEFAULT NULL,
  `data_version` varchar(105) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `operation_types`
--

INSERT INTO `operation_types` (`operation_type_id`, `libelle`, `creation_date`, `comments`, `data_version`) VALUES
(1, 'DEPOT', '2021-07-08 12:29:42', 'DEPOT', '1'),
(2, 'RETRAIT', '2021-07-08 12:29:42', 'RETRAIT', '1'),
(3, 'APPEL', '2021-07-08 14:42:04', 'FORFAIT APPEL', '1'),
(4, 'INTERNET', '2021-07-08 14:42:04', 'INTERNET', '1'),
(5, 'SOLDE CREDIT', '2021-07-16 10:50:38', 'Consulter le solde', '1'),
(6, 'SOLDE MOMO', '2021-07-16 10:55:12', 'SOLDE DU COMPTE MOMO', '1'),
(7, 'FORFAIT APPEL DATA', '2021-08-02 14:24:11', 'Forfait APPEL et Internet', NULL),
(8, 'CREDIT', '2021-08-02 14:57:06', 'Vente de crédits', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_histories`
--

CREATE TABLE `password_histories` (
  `history_id` int NOT NULL,
  `logout_date` datetime DEFAULT NULL,
  `login_date` datetime DEFAULT NULL,
  `schedule_reset_date` datetime DEFAULT NULL,
  `effective_reset_date` datetime DEFAULT NULL,
  `next_reset_date` datetime DEFAULT NULL,
  `is_effective` tinyint DEFAULT NULL,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `password_histories`
--

INSERT INTO `password_histories` (`history_id`, `logout_date`, `login_date`, `schedule_reset_date`, `effective_reset_date`, `next_reset_date`, `is_effective`, `user_id`) VALUES
(1, NULL, '2021-08-02 13:22:47', '2021-08-14 13:22:47', NULL, NULL, 0, 1),
(2, NULL, '2021-08-02 13:22:47', '2021-08-14 13:22:47', NULL, NULL, 0, 1),
(3, NULL, '2021-08-02 13:22:47', '2021-08-14 13:22:47', NULL, NULL, 0, 1),
(6, NULL, '2021-08-04 22:47:33', '2021-08-11 22:47:33', NULL, NULL, 0, 2),
(7, NULL, '2021-08-05 14:52:48', '2021-08-12 14:52:48', NULL, NULL, 0, 1),
(8, NULL, '2021-08-05 17:12:44', '2021-08-12 17:12:44', NULL, NULL, 0, 2),
(9, NULL, '2021-08-05 17:26:27', '2021-08-12 17:26:27', NULL, NULL, 0, 2),
(10, NULL, '2021-08-05 18:13:49', '2021-08-12 18:13:49', NULL, NULL, 0, 1),
(11, NULL, '2021-08-07 11:00:14', '2021-08-14 11:00:14', NULL, NULL, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `service_id` int NOT NULL,
  `libelle` varchar(30) NOT NULL,
  `descriptions` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`service_id`, `libelle`, `descriptions`) VALUES
(1, 'Test10', 'Test desciption'),
(2, 'Service 2', 'Test description service 2'),
(3, 'Service 3', 'Test description service 3');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone_number` varchar(100) DEFAULT NULL,
  `encrypted_password` varchar(100) DEFAULT NULL,
  `hash` varchar(100) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `last_update_date` datetime DEFAULT NULL,
  `address_id` int NOT NULL,
  `company_id` int NOT NULL,
  `is_active_flag` tinyint(1) DEFAULT NULL,
  `active_date_from` date DEFAULT NULL,
  `active_date_to` date DEFAULT NULL,
  `is_manager` tinyint(1) DEFAULT NULL,
  `company_token` varchar(105) DEFAULT NULL,
  `application_uuid` varchar(105) DEFAULT NULL,
  `data_version` varchar(105) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `firstname`, `lastname`, `email`, `phone_number`, `encrypted_password`, `hash`, `creation_date`, `last_update_date`, `address_id`, `company_id`, `is_active_flag`, `active_date_from`, `active_date_to`, `is_manager`, `company_token`, `application_uuid`, `data_version`) VALUES
(1, 'testeur', 'testeuse', 'test@gmail.com', '61292948', '$2y$10$Gzzjey21tv5hkoVb2/Tse.exS0Lc3stg9wFbEx.6c5kZ3lyDo4GNa', '1627913739', '2021-07-14 14:47:57', NULL, 1, 1, 1, '2021-07-01', NULL, 0, 'TEST08072021', '041750ce-e473-462c-af82-99567dc25b4b', '1'),
(2, 'nuptia', 'M', 'testa@gmail.com', '69126070', '$2y$10$tmyVCeydp0uLpwJ/SsxBTu9o9n6OJ50K1nPnlJdkJB51bW4nLBy9S', '1627923221', NULL, NULL, 1, 1, 1, '2021-08-01', NULL, NULL, 'TEST08072021', '041750ce-e473-462c-af82-99567dc25b4b', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_has_features`
--

CREATE TABLE `user_has_features` (
  `user_has_licence_id` int NOT NULL,
  `licence_activation_date` varchar(45) DEFAULT NULL,
  `licence_feature_id` int NOT NULL,
  `user_id` int NOT NULL,
  `company_token` varchar(105) DEFAULT NULL,
  `active_date_from` date DEFAULT NULL,
  `active_date_to` date DEFAULT NULL,
  `application_uuid` varchar(105) DEFAULT NULL,
  `data_version` varchar(105) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_operations`
--

CREATE TABLE `user_operations` (
  `user_operation_id` int NOT NULL,
  `operation_date` datetime DEFAULT NULL,
  `created_by_user_id` int DEFAULT NULL,
  `operation_id` int NOT NULL,
  `company_token` varchar(45) DEFAULT NULL,
  `inventory_id` int DEFAULT NULL,
  `application_uuid` varchar(105) DEFAULT NULL,
  `data_version` varchar(105) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_operations`
--

INSERT INTO `user_operations` (`user_operation_id`, `operation_date`, `created_by_user_id`, `operation_id`, `company_token`, `inventory_id`, `application_uuid`, `data_version`) VALUES
(1, '2021-07-14 14:47:43', 1, 1, 'TEST08072021', NULL, '041750ce-e473-462c-af82-99567dc25b4b', '1'),
(2, '2021-07-14 14:47:43', 1, 1, 'TEST08072021', NULL, '041750ce-e473-462c-af82-99567dc25b4b', '1'),
(3, '2021-07-14 14:47:43', 1, 1, 'TEST08072021', NULL, '041750ce-e473-462c-af82-99567dc25b4b', '1'),
(4, '2021-07-14 14:47:43', 1, 1, 'TEST08072021', NULL, '041750ce-e473-462c-af82-99567dc25b4b', '1');

-- --------------------------------------------------------

--
-- Table structure for table `validate_password`
--

CREATE TABLE `validate_password` (
  `validate_id` int NOT NULL,
  `verify_code` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `created_date` datetime NOT NULL,
  `is_used` tinyint DEFAULT NULL,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `validate_password`
--

INSERT INTO `validate_password` (`validate_id`, `verify_code`, `created_date`, `is_used`, `user_id`) VALUES
(1, 'YPF3UEZnGD', '2021-08-02 14:16:00', 1, 1),
(2, 'yOaXTUOZSc', '2021-08-02 15:29:00', 0, 1),
(3, 'ZWeuPogEYK', '2021-08-02 15:49:00', 0, 1),
(4, 'nmbjot6fuk', '2021-08-02 16:42:00', 0, 2),
(5, 'T8YUB0rB2Q', '2021-08-02 16:46:00', 0, 2),
(6, 'fuGbFJ18EY', '2021-08-02 16:54:00', 0, 2),
(7, 'wBNY5fcy57', '2021-08-02 18:12:00', 0, 2),
(8, '4WX37mGSMa', '2021-08-02 19:52:00', 0, 2),
(9, 'Vbi9sndSHJ', '2021-08-02 20:33:00', 0, 2),
(10, 'ICjpfAYRNl', '2021-08-02 20:53:00', 0, 2),
(11, 'KvTRfuJ4lj', '2021-08-02 23:36:00', 0, 2),
(12, 'PlYvpREsOq', '2021-08-02 23:39:00', 0, 2),
(13, 'd9v84ENcw8', '2021-08-03 00:35:00', 0, 2),
(14, 'Zsca15BxNx', '2021-08-03 00:46:00', 0, 2),
(15, 'sHoyslWsC7', '2021-08-03 01:00:00', 0, 2),
(16, 'xaW8JqTHjT', '2021-08-03 09:03:00', 0, 2),
(17, 'kWzrtYMrFF', '2021-08-03 14:04:00', 0, 2),
(18, 'oH8WvSkNUf', '2021-08-03 15:01:00', 0, 2),
(19, 'PcKxM8bct2', '2021-08-03 15:15:00', 0, 2),
(20, '7DfaQbX5up', '2021-08-03 15:55:00', 0, 2),
(21, '03GhLKe4kL', '2021-08-03 17:26:00', 0, 1),
(22, 'tYzf6hylrG', '2021-08-03 17:34:00', 0, 1),
(23, 'N8KnvrdZnO', '2021-08-03 17:34:00', 0, 1),
(24, 'rFbLQxCr0l', '2021-08-04 19:39:00', 0, 2),
(25, 'wWpxOqhiFs', '2021-08-04 21:45:00', 0, 2),
(26, 'yy7BPUZYb2', '2021-08-05 13:51:00', 0, 1),
(27, '22980', '2021-08-05 14:34:00', 1, 1),
(28, '24480', '2021-08-05 14:58:00', 0, 2),
(29, '20887', '2021-08-05 16:08:00', 0, 2),
(30, '14064', '2021-08-05 16:09:00', 0, 2),
(31, '21598', '2021-08-05 16:12:00', 1, 2),
(32, '16860', '2021-08-05 16:26:00', 1, 2),
(33, '23442', '2021-08-05 17:13:00', 1, 1),
(34, '25174', '2021-08-07 09:52:00', 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `fk_addresses_companies1_idx` (`company_id`),
  ADD KEY `fk_addresses_countries1_idx` (`country_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `category_ussd`
--
ALTER TABLE `category_ussd`
  ADD PRIMARY KEY (`category_ussd_id`),
  ADD KEY `fk_category_ussd_categories1_idx` (`category_id`),
  ADD KEY `fk_category_ussd_operation_types1_idx` (`operation_type_id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`company_id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`country_id`);

--
-- Indexes for table `database_version`
--
ALTER TABLE `database_version`
  ADD PRIMARY KEY (`database_version_id`);

--
-- Indexes for table `inventories`
--
ALTER TABLE `inventories`
  ADD PRIMARY KEY (`inventory_id`);

--
-- Indexes for table `licences`
--
ALTER TABLE `licences`
  ADD PRIMARY KEY (`licence_id`);

--
-- Indexes for table `licence_features`
--
ALTER TABLE `licence_features`
  ADD PRIMARY KEY (`licence_feature_id`),
  ADD KEY `fk_licence_features_licences_idx` (`licence_id`);

--
-- Indexes for table `operations`
--
ALTER TABLE `operations`
  ADD PRIMARY KEY (`operation_id`),
  ADD KEY `fk_operations_operation_types1_idx` (`operation_type_id`);

--
-- Indexes for table `operation_types`
--
ALTER TABLE `operation_types`
  ADD PRIMARY KEY (`operation_type_id`);

--
-- Indexes for table `password_histories`
--
ALTER TABLE `password_histories`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `fk_rapport_user_idx` (`user_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`service_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `fk_users_addresses1_idx` (`address_id`),
  ADD KEY `fk_users_companies1_idx` (`company_id`);

--
-- Indexes for table `user_has_features`
--
ALTER TABLE `user_has_features`
  ADD PRIMARY KEY (`user_has_licence_id`),
  ADD KEY `fk_user_has_features_licence_features1_idx` (`licence_feature_id`),
  ADD KEY `fk_user_has_features_users1_idx` (`user_id`);

--
-- Indexes for table `user_operations`
--
ALTER TABLE `user_operations`
  ADD PRIMARY KEY (`user_operation_id`),
  ADD KEY `fk_user_operations_users1_idx` (`created_by_user_id`),
  ADD KEY `fk_user_operations_operations1_idx` (`operation_id`),
  ADD KEY `fk_user_operations_inventories1_idx` (`inventory_id`);

--
-- Indexes for table `validate_password`
--
ALTER TABLE `validate_password`
  ADD PRIMARY KEY (`validate_id`),
  ADD KEY `fk_validate_password_user1_idx` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `address_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `category_ussd`
--
ALTER TABLE `category_ussd`
  MODIFY `category_ussd_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `company_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `country_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `database_version`
--
ALTER TABLE `database_version`
  MODIFY `database_version_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventories`
--
ALTER TABLE `inventories`
  MODIFY `inventory_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `licences`
--
ALTER TABLE `licences`
  MODIFY `licence_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `licence_features`
--
ALTER TABLE `licence_features`
  MODIFY `licence_feature_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `operations`
--
ALTER TABLE `operations`
  MODIFY `operation_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `operation_types`
--
ALTER TABLE `operation_types`
  MODIFY `operation_type_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `password_histories`
--
ALTER TABLE `password_histories`
  MODIFY `history_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `service_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_has_features`
--
ALTER TABLE `user_has_features`
  MODIFY `user_has_licence_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_operations`
--
ALTER TABLE `user_operations`
  MODIFY `user_operation_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `validate_password`
--
ALTER TABLE `validate_password`
  MODIFY `validate_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `fk_addresses_companies1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`company_id`),
  ADD CONSTRAINT `fk_addresses_countries1` FOREIGN KEY (`country_id`) REFERENCES `countries` (`country_id`);

--
-- Constraints for table `category_ussd`
--
ALTER TABLE `category_ussd`
  ADD CONSTRAINT `fk_category_ussd_categories1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`),
  ADD CONSTRAINT `fk_category_ussd_operation_types1` FOREIGN KEY (`operation_type_id`) REFERENCES `operation_types` (`operation_type_id`);

--
-- Constraints for table `licence_features`
--
ALTER TABLE `licence_features`
  ADD CONSTRAINT `fk_licence_features_licences` FOREIGN KEY (`licence_id`) REFERENCES `licences` (`licence_id`);

--
-- Constraints for table `operations`
--
ALTER TABLE `operations`
  ADD CONSTRAINT `fk_operations_operation_types1` FOREIGN KEY (`operation_type_id`) REFERENCES `operation_types` (`operation_type_id`);

--
-- Constraints for table `password_histories`
--
ALTER TABLE `password_histories`
  ADD CONSTRAINT `fk_rapport_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_addresses1` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`address_id`),
  ADD CONSTRAINT `fk_users_companies1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`company_id`);

--
-- Constraints for table `user_has_features`
--
ALTER TABLE `user_has_features`
  ADD CONSTRAINT `fk_user_has_features_licence_features1` FOREIGN KEY (`licence_feature_id`) REFERENCES `licence_features` (`licence_feature_id`),
  ADD CONSTRAINT `fk_user_has_features_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `user_operations`
--
ALTER TABLE `user_operations`
  ADD CONSTRAINT `fk_user_operations_inventories1` FOREIGN KEY (`inventory_id`) REFERENCES `inventories` (`inventory_id`),
  ADD CONSTRAINT `fk_user_operations_operations1` FOREIGN KEY (`operation_id`) REFERENCES `operations` (`operation_id`),
  ADD CONSTRAINT `fk_user_operations_users1` FOREIGN KEY (`created_by_user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `validate_password`
--
ALTER TABLE `validate_password`
  ADD CONSTRAINT `fk_validate_password_user1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
