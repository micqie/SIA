-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 18, 2025 at 01:47 PM
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
-- Database: `sia_db2`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `account_id` int(11) NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('U','A') NOT NULL DEFAULT 'U'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`account_id`, `username`, `password`, `role`) VALUES
(1, 'johndoe01', '$2y$10$MuEgt5yRBLdcUD54pc7.suqCSLJ0zuvs9H4imdsA8TOWmHjXXsPsS', 'U'),
(4, 'micah@gmail.com', '$2y$10$P2g3Fu36FLLsEYuC8FX3AuZ33XasLtcyH6yBffrj44zOK6eEgezsO', 'U'),
(13, 'nor@gmail.com', '$2y$10$HTp/Zw.CIQMMjRi/kZ4Bqenaz6z1AmKNtS974kVM..kLMbD3gPrlC', 'U'),
(16, 'mi@gmail.com', '$2y$10$3MaQbCm4thE7qPUgJkaNVeYS6jI4e3PyM6cD.AWqcPGTA.hTzGFQy', 'U'),
(20, 'admin', '$2y$10$31a5goF8c/15YoCmSxBP6OP5IyLj/i1vdnCNDh8X58cJpRoGtKrGq', 'A'),
(21, 'ash@gmail.com', '$2y$10$VKPJw4HL46NCQ/XN9Tf.N.IhwyEYT08uDswhSLdVL7MyvjvBHeRW.', 'U'),
(22, 'micah2005@gmail', '$2y$10$/F6sD.5kML2qOTjiU6gZ0eB/CdnXGz/L/dZEk3Wh1y6wwCPdjgpnu', 'U'),
(23, 'laurice@gmail.c', '$2y$10$2YBv0s.2bBctV.Arqk6zIen2o9bLCaRX7SwNDbyEkw3UsXuK3dRtq', 'U'),
(24, 'shan@gmail.com', '$2y$10$Z.W2SOLWGQ1rsIkD6EAUn.A7aAnFViet3ZrepHpo9g.vIescEL/um', 'U'),
(25, 'micah2@gmail.co', '$2y$10$Wpb88YuW7qH/RUTvD9dAjeWfEwoMKPDgVgX2qiHedIgX2RfT8/Cpu', 'U'),
(27, 'norelyn@gmail.com', '$2y$10$HTp/Zw.CIQMMjRi/kZ4Bqenaz6z1AmKNtS974kVM..kLMbD3gPrlC', 'U');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `booking_reference` varchar(15) NOT NULL,
  `verification_code` varchar(12) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `processing_fee` decimal(10,2) NOT NULL,
  `preferred_date` date NOT NULL,
  `special_instructions` text DEFAULT NULL,
  `qr_code_path` varchar(255) DEFAULT NULL,
  `status` enum('pending','confirmed','processing','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `payment_status` enum('pending','paid','failed') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `account_id`, `booking_reference`, `verification_code`, `total_amount`, `processing_fee`, `preferred_date`, `special_instructions`, `qr_code_path`, `status`, `created_at`, `updated_at`, `payment_status`) VALUES
(1, 24, 'BK2025031803200', '', 10500.00, 525.00, '2025-03-19', '', NULL, 'confirmed', '2025-03-18 02:20:02', '2025-03-18 02:35:11', 'pending'),
(2, 24, 'BK2025031809150', '', 14700.00, 735.00, '2025-03-22', 'sasa', NULL, 'confirmed', '2025-03-18 08:15:05', '2025-03-18 08:22:11', 'pending'),
(3, 24, 'BK2025031809203', '', 7350.00, 367.50, '2025-03-21', '', NULL, 'confirmed', '2025-03-18 08:20:32', '2025-03-18 08:22:15', 'paid'),
(4, 24, 'BK2025031809243', '', 7350.00, 367.50, '2025-03-26', '', NULL, '', '2025-03-18 08:24:31', '2025-03-18 08:25:19', 'paid'),
(5, 24, 'BK2025031809534', '', 7350.00, 367.50, '2025-03-28', 'sdasd', NULL, 'pending', '2025-03-18 08:53:42', NULL, 'paid');

-- --------------------------------------------------------

--
-- Table structure for table `booking_customizations`
--

CREATE TABLE `booking_customizations` (
  `customization_id` int(11) NOT NULL,
  `booking_detail_id` int(11) DEFAULT NULL,
  `option_id` int(11) DEFAULT NULL,
  `custom_value` text DEFAULT NULL,
  `additional_cost` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_details`
--

CREATE TABLE `booking_details` (
  `detail_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_details`
--

INSERT INTO `booking_details` (`detail_id`, `booking_id`, `product_id`, `quantity`, `unit_price`, `subtotal`, `created_at`) VALUES
(1, 1, 2, 2, 5000.00, 10000.00, '2025-03-18 02:20:02'),
(2, 2, 4, 2, 7000.00, 14000.00, '2025-03-18 08:15:05'),
(3, 3, 6, 1, 7000.00, 7000.00, '2025-03-18 08:20:32'),
(4, 4, 6, 1, 7000.00, 7000.00, '2025-03-18 08:24:31'),
(5, 5, 4, 1, 7000.00, 7000.00, '2025-03-18 08:53:42');

-- --------------------------------------------------------

--
-- Table structure for table `bundles`
--

CREATE TABLE `bundles` (
  `bundle_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `bundle_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `pieces_count` int(11) DEFAULT 200,
  `is_special` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bundles`
--

INSERT INTO `bundles` (`bundle_id`, `product_id`, `bundle_name`, `description`, `price`, `pieces_count`, `is_special`, `is_active`, `created_at`) VALUES
(1, 1, 'Christmas Joy Bundle', 'Luxurious leather wallet, card holder, and matching belt with festive designs', 4999.00, 200, 1, 1, '2025-03-17 07:26:19'),
(2, 2, 'Birthday Special Bundle', 'Premium leather bag with personalized initials and matching accessories', 3999.00, 200, 1, 1, '2025-03-17 07:26:19'),
(3, 3, 'Anniversary Collection', 'Matching couple wallets with custom engravings and luxury packaging', 5999.00, 200, 1, 1, '2025-03-17 07:26:19'),
(4, 1, 'Desk Organizer Bundle', '200 pieces of premium leather desk organizers', 7000.00, 200, 0, 1, '2025-03-17 07:26:19'),
(5, 2, 'Cord Organizer Bundle', '200 pieces of elegant leather cord organizers', 5000.00, 200, 0, 1, '2025-03-17 07:26:19'),
(6, 3, 'Keychain Bundle', '200 pieces of stylish leather keychains', 5000.00, 200, 0, 1, '2025-03-17 07:26:19'),
(7, 4, 'Stethoscope Sleeve Bundle', '200 pieces of professional leather stethoscope sleeves', 7000.00, 200, 0, 1, '2025-03-17 07:26:19'),
(8, 5, 'Bag Tags Bundle', '200 pieces of personalized leather bag tags', 7000.00, 200, 0, 1, '2025-03-17 07:26:19'),
(9, 6, 'Coin Purse Bundle', '200 pieces of compact leather coin purses', 7000.00, 200, 0, 1, '2025-03-17 07:26:19');

-- --------------------------------------------------------

--
-- Table structure for table `bundle_availability`
--

CREATE TABLE `bundle_availability` (
  `availability_id` int(11) NOT NULL,
  `bundle_id` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `slots_available` int(11) DEFAULT 5,
  `status` enum('available','limited','fully_booked') DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customization_options`
--

CREATE TABLE `customization_options` (
  `option_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `option_name` varchar(50) NOT NULL,
  `option_type` enum('color','text','size','material') NOT NULL,
  `option_values` text DEFAULT NULL,
  `is_required` tinyint(1) DEFAULT 0,
  `additional_cost` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customization_options`
--

INSERT INTO `customization_options` (`option_id`, `product_id`, `option_name`, `option_type`, `option_values`, `is_required`, `additional_cost`) VALUES
(1, 1, 'Color', 'color', 'Brown,Black,Tan,Navy', 1, 0.00),
(2, 1, 'Name Engraving', 'text', NULL, 0, 100.00),
(3, 1, 'Size', 'size', 'Small,Medium,Large', 1, 0.00),
(4, 1, 'Material Grade', 'material', 'Standard,Premium,Luxury', 0, 200.00);

-- --------------------------------------------------------

--
-- Table structure for table `guest_bookings`
--

CREATE TABLE `guest_bookings` (
  `guest_booking_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `guest_reference` varchar(15) NOT NULL,
  `guest_name` varchar(100) NOT NULL,
  `guest_email` varchar(100) NOT NULL,
  `guest_phone` varchar(20) NOT NULL,
  `customization_details` text DEFAULT NULL,
  `qr_code_path` varchar(255) DEFAULT NULL,
  `status` enum('pending','confirmed','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `account_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guest_bookings`
--

INSERT INTO `guest_bookings` (`guest_booking_id`, `booking_id`, `guest_reference`, `guest_name`, `guest_email`, `guest_phone`, `customization_details`, `qr_code_path`, `status`, `created_at`, `updated_at`, `account_id`) VALUES
(1, 5, '', 'lago', '', '', '{\"color\":\"#C0C0C0\",\"customization_code\":\"CUST202503183563\",\"timestamp\":\"2025-03-18 11:59:14\"}', NULL, 'pending', '2025-03-18 09:05:32', '2025-03-18 11:18:24', 24);

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `package_id` int(11) NOT NULL,
  `package_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `base_price` decimal(10,2) NOT NULL,
  `max_items` int(11) DEFAULT 5,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`package_id`, `package_name`, `description`, `base_price`, `max_items`, `is_active`, `created_at`) VALUES
(1, 'Birthday Bundle', 'Perfect for birthday celebrations', 1500.00, 3, 1, '2025-03-18 08:19:30'),
(2, 'Christmas Special', 'Festive leather accessories', 2000.00, 4, 1, '2025-03-18 08:19:30'),
(3, 'Wedding Collection', 'Elegant leather gifts for weddings', 2500.00, 5, 1, '2025-03-18 08:19:30'),
(4, 'Corporate Package', 'Professional leather accessories', 3000.00, 5, 1, '2025-03-18 08:19:30');

-- --------------------------------------------------------

--
-- Table structure for table `package_products`
--

CREATE TABLE `package_products` (
  `package_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `payment_status` enum('pending','completed','failed') DEFAULT 'pending',
  `transaction_id` varchar(100) DEFAULT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `booking_id`, `amount`, `payment_method`, `payment_status`, `transaction_id`, `payment_date`) VALUES
(1, 3, 7350.00, 'gcash', 'completed', '12345', '2025-03-18 08:20:32'),
(2, 4, 7350.00, 'cash', 'completed', '232323', '2025-03-18 08:24:31'),
(3, 5, 7350.00, 'gcash', 'completed', '12345', '2025-03-18 08:53:42');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `product_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `base_price` decimal(10,2) NOT NULL,
  `pieces_per_bundle` int(11) DEFAULT 200,
  `stock` int(11) DEFAULT 0,
  `image_path` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `category_id`, `product_name`, `description`, `base_price`, `pieces_per_bundle`, `stock`, `image_path`, `is_active`, `created_at`) VALUES
(1, 1, 'Desk Organizer', 'Premium leather desk organizer for office essentials', 7000.00, 200, 1000, 'assets/desk_organizer.png', 1, '2025-03-17 07:26:19'),
(2, 1, 'Cord Organizer', 'Elegant leather cord organizer for cable management', 5000.00, 200, 800, 'assets/cord_organizer.png', 1, '2025-03-17 07:26:19'),
(3, 1, 'Keychain', 'Stylish leather keychain with custom design', 5000.00, 200, 1200, 'assets/keychain.png', 1, '2025-03-17 07:26:19'),
(4, 1, 'Stethoscope Sleeve', 'Professional leather stethoscope sleeve for medical professionals', 7000.00, 200, 600, 'assets/stetho_sleeve.png', 1, '2025-03-17 07:26:19'),
(5, 1, 'Bag Tags', 'Personalized leather bag tags with custom engravings', 7000.00, 200, 100, '0', 1, '2025-03-17 07:26:19'),
(6, 1, 'Coin Purse', 'Compact leather coin purse with secure closure', 7000.00, 200, 700, 'assets/coin_purse.png', 1, '2025-03-17 07:26:19');

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`category_id`, `category_name`, `description`, `created_at`) VALUES
(1, 'Leather Accessories', 'High-quality leather accessories for everyday use', '2025-03-17 07:26:19'),
(2, 'Special Occasions', 'Custom leather items for special events and celebrations', '2025-03-17 07:26:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD UNIQUE KEY `booking_reference` (`booking_reference`),
  ADD KEY `account_id` (`account_id`);

--
-- Indexes for table `booking_customizations`
--
ALTER TABLE `booking_customizations`
  ADD PRIMARY KEY (`customization_id`),
  ADD KEY `booking_detail_id` (`booking_detail_id`),
  ADD KEY `option_id` (`option_id`);

--
-- Indexes for table `booking_details`
--
ALTER TABLE `booking_details`
  ADD PRIMARY KEY (`detail_id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `bundles`
--
ALTER TABLE `bundles`
  ADD PRIMARY KEY (`bundle_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `bundle_availability`
--
ALTER TABLE `bundle_availability`
  ADD PRIMARY KEY (`availability_id`),
  ADD KEY `bundle_id` (`bundle_id`);

--
-- Indexes for table `customization_options`
--
ALTER TABLE `customization_options`
  ADD PRIMARY KEY (`option_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `guest_bookings`
--
ALTER TABLE `guest_bookings`
  ADD PRIMARY KEY (`guest_booking_id`),
  ADD UNIQUE KEY `guest_reference` (`guest_reference`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `fk_guest_bookings_account` (`account_id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`package_id`);

--
-- Indexes for table `package_products`
--
ALTER TABLE `package_products`
  ADD PRIMARY KEY (`package_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `booking_customizations`
--
ALTER TABLE `booking_customizations`
  MODIFY `customization_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_details`
--
ALTER TABLE `booking_details`
  MODIFY `detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `bundles`
--
ALTER TABLE `bundles`
  MODIFY `bundle_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `bundle_availability`
--
ALTER TABLE `bundle_availability`
  MODIFY `availability_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customization_options`
--
ALTER TABLE `customization_options`
  MODIFY `option_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `guest_bookings`
--
ALTER TABLE `guest_bookings`
  MODIFY `guest_booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `package_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`);

--
-- Constraints for table `booking_customizations`
--
ALTER TABLE `booking_customizations`
  ADD CONSTRAINT `booking_customizations_ibfk_1` FOREIGN KEY (`booking_detail_id`) REFERENCES `booking_details` (`booking_id`),
  ADD CONSTRAINT `booking_customizations_ibfk_2` FOREIGN KEY (`option_id`) REFERENCES `customization_options` (`option_id`);

--
-- Constraints for table `booking_details`
--
ALTER TABLE `booking_details`
  ADD CONSTRAINT `booking_details_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`),
  ADD CONSTRAINT `booking_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `bundles`
--
ALTER TABLE `bundles`
  ADD CONSTRAINT `bundles_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `bundle_availability`
--
ALTER TABLE `bundle_availability`
  ADD CONSTRAINT `bundle_availability_ibfk_1` FOREIGN KEY (`bundle_id`) REFERENCES `bundles` (`bundle_id`);

--
-- Constraints for table `customization_options`
--
ALTER TABLE `customization_options`
  ADD CONSTRAINT `customization_options_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `guest_bookings`
--
ALTER TABLE `guest_bookings`
  ADD CONSTRAINT `fk_guest_bookings_account` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`),
  ADD CONSTRAINT `guest_bookings_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`);

--
-- Constraints for table `package_products`
--
ALTER TABLE `package_products`
  ADD CONSTRAINT `package_products_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `packages` (`package_id`),
  ADD CONSTRAINT `package_products_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`category_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
