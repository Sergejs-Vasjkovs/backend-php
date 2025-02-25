-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 25, 2025 at 12:31 PM
-- Server version: 5.7.39
-- PHP Version: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `products_api`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Smartphones', 'Mobile phones and accessories', 'active', '2025-02-24 08:24:16', '2025-02-24 08:24:16'),
(2, 'Laptops', 'Notebooks and laptops', 'active', '2025-02-24 08:24:16', '2025-02-24 08:24:16'),
(3, 'Audio', 'Headphones, speakers and audio devices', 'active', '2025-02-24 08:24:16', '2025-02-24 08:24:16'),
(4, 'Tablets', 'Tablets and e-readers', 'active', '2025-02-24 08:24:16', '2025-02-24 08:24:16'),
(5, 'Wearables', 'Smartwatches and fitness trackers', 'active', '2025-02-24 08:24:16', '2025-02-24 08:24:16');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `sku` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stock_quantity` int(11) DEFAULT '0',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `sku`, `stock_quantity`, `status`, `created_at`, `updated_at`, `category_id`) VALUES
(1, 'iPhone 15 Pro', 'Flagship smartphone with A17 Pro chip', '999.99', 'IPH15P-BLK', 50, 'active', '2025-02-24 08:23:47', '2025-02-24 08:24:27', 1),
(2, 'MacBook Air M2', '13.6-inch laptop with M2 chip', '1199.99', 'MBA-M2-SG', 30, 'active', '2025-02-24 08:23:47', '2025-02-24 08:24:27', 2),
(3, 'AirPods Pro', 'Wireless earbuds with noise cancellation', '249.99', 'APP2-WHT', 100, 'active', '2025-02-24 08:23:47', '2025-02-24 08:24:27', 3),
(4, 'iPad Air', '10.9-inch tablet with M1 chip', '599.99', 'IPAD-AIR-5', 45, 'active', '2025-02-24 08:23:47', '2025-02-24 08:24:27', 4),
(5, 'Apple Watch Series 8', 'Smartwatch with health features', '399.99', 'AWS8-45MM', 60, 'active', '2025-02-24 08:23:47', '2025-02-24 08:24:27', 5),
(6, 'Magic Keyboard', 'Wireless keyboard with numeric keypad', '129.99', 'MK-A1314', 15, 'inactive', '2025-02-24 08:23:47', '2025-02-24 08:24:27', 2),
(7, 'Magic Mouse', 'Wireless mouse with Multi-Touch', '79.99', 'MM-A1657', 25, 'active', '2025-02-24 08:23:47', '2025-02-24 08:24:27', 2),
(8, 'HomePod Mini', 'Smart speaker with Siri', '99.99', 'HPM-WHITE', 40, 'active', '2025-02-24 08:23:47', '2025-02-24 08:24:27', 3),
(9, 'Apple TV 4K', 'Streaming device with A15 Bionic', '179.99', 'ATV-4K-64', 20, 'active', '2025-02-24 08:23:47', '2025-02-24 08:24:27', 3),
(10, 'Mac Studio', 'Professional desktop computer', '1999.99', 'MSTUDIO-M1', 10, 'active', '2025-02-24 08:23:47', '2025-02-24 08:24:27', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `category_id` (`category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
