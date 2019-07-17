-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 24, 2019 at 11:20 AM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecom_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `cat_id` int(10) UNSIGNED NOT NULL,
  `cat_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cat_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`cat_id`, `cat_title`, `cat_image`, `created_at`, `updated_at`) VALUES
(1, 'fsfsd', 'http://192.168.10.4/Ecommerce/public/uploads/categories/images.jpg', '2019-05-07 01:30:05', '2019-05-07 01:30:05'),
(2, 'Accesories', 'http://192.168.10.4/Ecommerce/public/uploads/categories/lKwHTxHFauHchp807gNhziAVIHLVtd722kVM2OGsEGSs3eYju2WBGKlu7vEw4-ealHRshDbI7nYlA_0FFLKjJso-TmYkrTtYW9kYag=w384-h384-nc.jpg', '2019-05-07 01:35:05', '2019-05-07 01:35:05'),
(3, 'Mobiles', 'http://192.168.10.4/Ecommerce/public/uploads/categories/Mobiles1558021374', '2019-05-16 10:42:54', '2019-05-16 10:42:54'),
(4, 'another cat', 'http://192.168.10.4/Ecommerce/public/uploads/categories/another cat1558029119', '2019-05-16 12:51:59', '2019-05-16 12:51:59');

-- --------------------------------------------------------

--
-- Table structure for table `checkout`
--

CREATE TABLE `checkout` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `town` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postalcode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phonenumber` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_processed` int(11) DEFAULT '0',
  `products_quantity` int(11) NOT NULL,
  `total_price` int(11) NOT NULL,
  `user_id` int(11) DEFAULT '0',
  `session_id` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `company` text COLLATE utf8mb4_unicode_ci,
  `is_checkout` int(11) DEFAULT '0',
  `is_redirected_to_payment` int(11) DEFAULT '0',
  `payment_status` int(11) DEFAULT '0',
  `is_paid` int(11) DEFAULT '0',
  `payer_id` text COLLATE utf8mb4_unicode_ci,
  `payment_id` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `checkout`
--

INSERT INTO `checkout` (`id`, `firstname`, `lastname`, `address`, `town`, `postalcode`, `email`, `phonenumber`, `created_at`, `updated_at`, `is_processed`, `products_quantity`, `total_price`, `user_id`, `session_id`, `company`, `is_checkout`, `is_redirected_to_payment`, `payment_status`, `is_paid`, `payer_id`, `payment_id`) VALUES
(1, 'Irfan', 'Ullah', 'KP,Pakistan', 'Mingora', '1920', 'thepukhtoonhacker@gmail.com', '03468723948', NULL, '2019-05-15 08:53:06', 1, 7, 7000, 3, '', NULL, 0, 0, 0, 0, NULL, NULL),
(3, 'Irfan', 'Ullah', 'KP,Pakistan', 'Mingora', '1920', 'thepukhtoonhacker@gmail.com', '03468723948', NULL, NULL, 1, 88, 17000, 3, '', NULL, 0, 0, 0, 0, NULL, NULL),
(8, 'Irfan none Ullah', NULL, 'Mohallah Al-huda Landikass', 'Mingora', '19130', 'theirfanullah@gmail.com', '3468723948', '2019-05-21 05:44:34', '2019-05-21 06:18:44', 0, 5, 100, 4, 'rW5u1iE7bDmSXcS3M3dJJzuuXK1CMFgIkGnJEdbk', 'irfitech', 1, 0, 0, 1, 'U6HZQ2WWU7QKQ', 'PAYID-LTR5YMI3AC51072W5431202D'),
(9, 'Irfan none Ullah', NULL, 'Mohallah Al-huda Landikass', 'Mingora', '19130', 'theirfanullah@gmail.com', '3468723948', '2019-05-21 06:22:08', '2019-05-21 06:22:41', 0, 3, 26364, 4, 'IDcdXpx0b3ckLZxp7xsv2aKLV00JnBEMexSaMwEX', 'irfitech', 1, 0, 0, 1, 'U6HZQ2WWU7QKQ', 'PAYID-LTR56ZA4Y633408RW537262G'),
(12, 'Irfan none Ullah', NULL, 'Mohallah Al-huda Landikass', 'Mingora', '19130', 'theirfanullah@gmail.com', '3468723948', '2019-05-22 05:52:04', '2019-05-23 09:01:09', 0, 7, 61516, 4, 'bfIfbo47X1jhto53lAytznstM6qC8IbPVOWWe9ne', 'irfitech', 1, 0, 0, 1, 'U6HZQ2WWU7QKQ', 'PAYID-LTTKOUY1YY49539E2274954B');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_05_07_040147_create_cat_table', 1),
(4, '2019_05_07_071251_create_product_table', 2),
(5, '2019_05_10_114324_create_wish_list_table', 3),
(7, '2019_05_10_120326_create_recently_viewed_table', 4),
(8, '2019_05_14_054619_create_checkout_table', 5),
(9, '2019_05_14_054748_create_productsordered_table', 6);

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `checkout_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `product_price` int(11) NOT NULL,
  `quantity_ordered` int(11) NOT NULL,
  `is_shipment_charges` int(11) NOT NULL DEFAULT '0',
  `shipment_charges` int(11) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `total_ordered_product_price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`id`, `checkout_id`, `product_id`, `product_price`, `quantity_ordered`, `is_shipment_charges`, `shipment_charges`, `created_at`, `updated_at`, `total_ordered_product_price`) VALUES
(1, 1, 7, 878, 6, 0, 44, NULL, NULL, 0),
(2, 1, 8, 2000, 7, 0, 33, NULL, NULL, 0),
(5, 8, 7, 8788, 2, 0, 0, '2019-05-21 05:44:34', '2019-05-21 05:44:34', 17576),
(6, 8, 8, 2000, 3, 0, 0, '2019-05-21 05:44:34', '2019-05-21 05:44:34', 6000),
(7, 9, 7, 8788, 3, 0, 0, '2019-05-21 06:22:08', '2019-05-21 06:22:08', 26364),
(12, 12, 7, 8788, 7, 0, 0, '2019-05-22 05:52:04', '2019-05-22 05:52:04', 61516);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_price` double(8,2) NOT NULL,
  `cat_id` int(10) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `sold` int(11) NOT NULL,
  `available` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `product_image`, `product_price`, `cat_id`, `quantity`, `sold`, `available`, `created_at`, `updated_at`) VALUES
(7, 'product', 'http://192.168.10.4/Ecommerce/public/uploads/products/15573111783316628.jpg', 8788.00, 1, 695, 0, 695, '2019-05-08 05:26:18', '2019-05-15 05:27:46'),
(8, 'product', 'http://192.168.10.4/Ecommerce/public/uploads/products/15573111783316628.jpg', 2000.00, 2, 33, 0, 33, '2019-05-08 05:26:18', '2019-05-08 05:26:18');

-- --------------------------------------------------------

--
-- Table structure for table `recentlyviewed`
--

CREATE TABLE `recentlyviewed` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` int(11) NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `shipmentduration` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `token`, `role_name`, `role`, `remember_token`, `created_at`, `updated_at`, `shipmentduration`) VALUES
(1, 'Irfan Ullah', 'ii@ii.com', NULL, '$2y$10$8sCitbZdB9ZniyL5ibR16e7cJSMfseb.2OqglSh1yM4U5XV.KwMtK', '$2y$10$iI/5UTq7dQr1rw3/wXGggeUwmHnTR96QEcurWDyDxZcju5H3n3dNu', 'admin', 1, NULL, NULL, '2019-05-24 04:14:12', 2),
(3, 'Irfan Ullah', 'i@i.com', NULL, '$2y$10$D8QzcKKY8zeiUJA9VPlRt.zlHmej7mh3QNAkqe67jzCGrlRWnZNeu', '$2y$10$f1KG8nNZUB03SGiz9/qz/.FVPSx/FgLxTp.ePPJicAnfEHXjdOe9u', 'common', 0, NULL, '2019-05-12 02:51:25', '2019-05-12 02:51:25', 0),
(4, 'Irfan', 'g@g.com', NULL, '$2y$10$pzhONJx.nmVrtU0UQgFTMeVqhEDoqgcWxJ9/WnzC05/3W7HWI8NvW', '$2y$10$rhW1MSDMj8v3G0QiLAQA8u/Oh5K3IWOT4Qxk461IuyzVqy5gQGiPq', 'common', 0, NULL, '2019-05-12 04:29:26', '2019-05-21 09:58:20', 0);

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `checkout`
--
ALTER TABLE `checkout`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_checkout_id_foreign` (`checkout_id`),
  ADD KEY `order_product_id_foreign` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `products_cat_id_foreign` (`cat_id`);

--
-- Indexes for table `recentlyviewed`
--
ALTER TABLE `recentlyviewed`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recentlyviewed_product_id_foreign` (`product_id`),
  ADD KEY `recentlyviewed_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wishlist_product_id_foreign` (`product_id`),
  ADD KEY `wishlist_user_id_foreign` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `cat_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `checkout`
--
ALTER TABLE `checkout`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `recentlyviewed`
--
ALTER TABLE `recentlyviewed`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_checkout_id_foreign` FOREIGN KEY (`checkout_id`) REFERENCES `checkout` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_cat_id_foreign` FOREIGN KEY (`cat_id`) REFERENCES `categories` (`cat_id`);

--
-- Constraints for table `recentlyviewed`
--
ALTER TABLE `recentlyviewed`
  ADD CONSTRAINT `recentlyviewed_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recentlyviewed_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
  ADD CONSTRAINT `wishlist_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
