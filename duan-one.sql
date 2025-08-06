-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 06, 2025 at 01:53 PM
-- Server version: 8.0.30
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `duan-one`
--

-- --------------------------------------------------------

--
-- Table structure for table `attributes`
--

CREATE TABLE `attributes` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attributes`
--

INSERT INTO `attributes` (`id`, `name`) VALUES
(2, 'Kích Thước'),
(1, 'Màu Sắc');

-- --------------------------------------------------------

--
-- Table structure for table `attributevalues`
--

CREATE TABLE `attributevalues` (
  `id` int UNSIGNED NOT NULL,
  `attribute_id` int UNSIGNED NOT NULL,
  `value` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color_code` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attributevalues`
--

INSERT INTO `attributevalues` (`id`, `attribute_id`, `value`, `color_code`) VALUES
(1, 1, 'Trắng', NULL),
(2, 1, 'Đen', NULL),
(3, 1, 'Be', NULL),
(4, 2, 'S', NULL),
(5, 2, 'M', NULL),
(6, 2, 'L', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cartitems`
--

CREATE TABLE `cartitems` (
  `id` bigint UNSIGNED NOT NULL,
  `cart_id` bigint UNSIGNED NOT NULL,
  `variant_id` bigint UNSIGNED NOT NULL,
  `quantity` int UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cartitems`
--

INSERT INTO `cartitems` (`id`, `cart_id`, `variant_id`, `quantity`) VALUES
(1, 1, 101, 1),
(2, 2, 104, 2),
(3, 3, 103, 1),
(4, 4, 105, 1),
(5, 5, 102, 1);

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2025-07-25 03:33:52', '2025-07-25 03:33:52'),
(2, 2, '2025-07-25 03:33:52', '2025-07-25 03:33:52'),
(3, 3, '2025-07-25 03:33:52', '2025-07-25 03:33:52'),
(4, 4, '2025-07-25 03:33:52', '2025-07-25 03:33:52'),
(5, 5, '2025-07-25 03:33:52', '2025-07-25 03:33:52');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `parent_id`, `created_at`, `updated_at`) VALUES
(1, 'Trang Phục Nữ', NULL, '2025-07-25 03:33:52', '2025-07-25 03:33:52'),
(2, 'Váy & Đầm', 1, '2025-07-25 03:33:52', '2025-07-25 03:33:52'),
(3, 'Áo Nữ', 1, '2025-07-25 03:33:52', '2025-07-25 03:33:52'),
(4, 'Quần & Chân Váy', 1, '2025-07-25 03:33:52', '2025-07-25 03:33:52'),
(5, 'Váy Dự Tiệc', 2, '2025-07-25 03:33:52', '2025-07-25 03:33:52');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `product_id`, `user_id`, `content`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Váy đẹp xuất sắc, chất vải mềm mịn, mặc lên tôn dáng lắm ạ. Sẽ ủng hộ shop tiếp.', '2025-07-25 03:33:52', '2025-07-25 03:33:52'),
(2, 2, 2, 'Áo sơ mi đúng như hình, màu be rất tây, mình mặc đi làm ai cũng khen.', '2025-07-25 03:33:52', '2025-07-25 03:33:52'),
(3, 1, 3, 'Giao hàng nhanh, váy mặc rất thoải mái. Tuy nhiên mình thấy màu trắng hơi mỏng một chút.', '2025-07-25 03:33:52', '2025-07-25 03:33:52'),
(4, 3, 4, 'Chân váy xinh, dễ phối đồ, nhưng đường may chưa được tỉ mỉ lắm.', '2025-07-25 03:33:52', '2025-07-25 03:33:52'),
(5, 5, 5, 'Quần mát, nhẹ, mặc mùa hè rất thích. Shop tư vấn nhiệt tình.', '2025-07-25 03:33:52', '2025-07-25 03:33:52');

-- --------------------------------------------------------

--
-- Table structure for table `orderitems`
--

CREATE TABLE `orderitems` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `variant_id` bigint UNSIGNED DEFAULT NULL,
  `quantity` int UNSIGNED NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orderitems`
--

INSERT INTO `orderitems` (`id`, `order_id`, `variant_id`, `quantity`, `price`) VALUES
(1, 1, 101, 1, '750000.00'),
(2, 2, 103, 1, '550000.00'),
(3, 3, 104, 1, '420000.00'),
(4, 4, 102, 1, '890000.00'),
(5, 5, 105, 1, '480000.00');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `receiver_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `receiver_phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_method` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_status` enum('unpaid','paid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `receiver_name`, `receiver_phone`, `shipping_address`, `status`, `payment_method`, `payment_status`, `created_at`, `updated_at`) VALUES
(1, 1, '750000.00', 'Trần An Nhiên', '0912345671', '123 Đường Xuân Thủy, Cầu Giấy, Hà Nội', 'delivered', 'COD', 'paid', '2025-07-25 03:33:52', '2025-07-25 03:33:52'),
(2, 2, '550000.00', 'Lê Minh Thư', '0912345672', '456 Đường Lê Lợi, Quận 1, TP. HCM', 'shipped', 'Bank Transfer', 'paid', '2025-07-25 03:33:52', '2025-07-25 03:33:52'),
(3, 3, '420000.00', 'Nguyễn Phương Chi', '0912345673', '789 Đường Hùng Vương, Hải Châu, Đà Nẵng', 'processing', 'COD', 'unpaid', '2025-07-25 03:33:52', '2025-07-25 03:33:52'),
(4, 4, '890000.00', 'Phạm Khánh Vy', '0912345674', '101 Đường Bà Triệu, Hoàn Kiếm, Hà Nội', 'pending', 'Momo', 'unpaid', '2025-07-25 03:33:52', '2025-07-25 03:33:52'),
(5, 5, '480000.00', 'Vũ Thảo My', '0912345675', '212 Đường 3/2, Quận 10, TP. HCM', 'cancelled', 'COD', 'unpaid', '2025-07-25 03:33:52', '2025-07-25 03:33:52');

-- --------------------------------------------------------

--
-- Table structure for table `productimages`
--

CREATE TABLE `productimages` (
  `id` bigint UNSIGNED NOT NULL,
  `variant_id` bigint UNSIGNED NOT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `productimages`
--

INSERT INTO `productimages` (`id`, `variant_id`, `image_url`, `created_at`) VALUES
(6, 103, 'images/product_images/variant_103_1754154955_688e47cb1e55f.jpg', '2025-08-02 11:10:14'),
(8, 105, 'images/product_images/variant_105_1754149106_688e30f294d73.jpg', '2025-08-02 15:38:26'),
(11, 103, 'images/product_images/variant_103_1754156030_688e4bfe44b98.jpg', '2025-08-02 17:33:50'),
(12, 103, 'images/product_images/variant_103_1754156030_688e4bfe44f4b.jpg', '2025-08-02 17:33:50'),
(15, 105, 'images/product_images/variant_105_1754292012_68905f2ca3bc4.jpg', '2025-08-04 07:20:12'),
(16, 104, 'images/product_images/variant_104_1754292042_68905f4a4039d.jpg', '2025-08-04 07:20:42'),
(17, 104, 'images/product_images/variant_104_1754292042_68905f4a4079a.jpg', '2025-08-04 07:20:42'),
(18, 104, 'images/product_images/variant_104_1754292042_68905f4a409b7.jpg', '2025-08-04 07:20:42'),
(19, 104, 'images/product_images/variant_104_1754292042_68905f4a40bbf.jpg', '2025-08-04 07:20:42'),
(20, 104, 'images/product_images/variant_104_1754292051_68905f5376b44.jpg', '2025-08-04 07:20:51'),
(32, 101, 'images/product_images/variant_101_1754322522_6890d65a9d344.jpg', '2025-08-04 15:48:42'),
(33, 101, 'images/product_images/variant_101_1754322522_6890d65a9d614.jpg', '2025-08-04 15:48:42'),
(34, 101, 'images/product_images/variant_101_1754322522_6890d65a9d811.jpg', '2025-08-04 15:48:42'),
(35, 115, 'images/product_images/variant_115_1754322621_6890d6bd3a1c6.jpg', '2025-08-04 15:50:21'),
(36, 115, 'images/product_images/variant_115_1754322621_6890d6bd3a484.jpg', '2025-08-04 15:50:21'),
(37, 115, 'images/product_images/variant_115_1754322621_6890d6bd3a6cb.jpg', '2025-08-04 15:50:21'),
(38, 115, 'images/product_images/variant_115_1754322621_6890d6bd3a8f3.jpg', '2025-08-04 15:50:21'),
(39, 115, 'images/product_images/variant_115_1754322621_6890d6bd3abb3.jpg', '2025-08-04 15:50:21');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image_thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `image_thumbnail`, `category_id`, `created_at`, `updated_at`) VALUES
(1, 'Váy Lụa Tay Phồng Cổ Vuông', '', 'images/products_thumbnail/6890d3006956b_1754321664.jpg', 2, '2025-07-25 03:33:52', '2025-08-04 15:49:31'),
(2, 'Áo Sơ Mi Lụa Satin Tay Dài', 'Áo sơ mi công sở cao cấp, chất liệu lụa satin thoáng mát, dễ phối đồ.', 'images/products_thumbnail/6890e83330281_1754327091.jpg', 3, '2025-07-25 03:33:52', '2025-08-04 17:04:51'),
(3, 'Chân Váy Chữ A Xếp Ly', '', 'images/products_thumbnail/6890cd677e7b7_1754320231.jpg', 4, '2025-07-25 03:33:52', '2025-08-04 15:10:31'),
(5, 'Quần Culottes Ống Rộng', 'Quần culottes vải đũi, ống rộng thoải mái, item không thể thiếu trong tủ đồ mùa hè.', 'images/products_thumbnail/6890e83ae1c17_1754327098.jpg', 4, '2025-07-25 03:33:52', '2025-08-04 17:04:58'),
(16, 'aaa', 'aaa', 'images/products_thumbnail/6890d6bd393d7_1754322621.jpg', 5, '2025-08-04 13:41:43', '2025-08-04 16:23:51');

-- --------------------------------------------------------

--
-- Table structure for table `productvariants`
--

CREATE TABLE `productvariants` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `quantity` int UNSIGNED NOT NULL DEFAULT '0',
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `productvariants`
--

INSERT INTO `productvariants` (`id`, `product_id`, `price`, `sale_price`, `quantity`, `image_url`, `created_at`, `updated_at`) VALUES
(101, 1, '890000.00', '750000.00', 50, 'images/vay-lua-trang.jpg', '2025-07-25 03:33:52', '2025-08-04 15:49:31'),
(102, 1, '890000.00', '123000.00', 40, 'images/vay-lua-den.jpg', '2025-07-25 03:33:52', '2025-08-04 08:39:33'),
(103, 2, '550000.00', '312000.00', 80, 'images/ao-somi-be.jpg', '2025-07-25 03:33:52', '2025-08-04 17:04:51'),
(104, 3, '420000.00', '399000.00', 60, 'images/chan-vay-xeply-den.jpg', '2025-07-25 03:33:52', '2025-08-04 15:10:31'),
(105, 5, '480000.00', '435600.00', 75, 'images/quan-culottes-trang.jpg', '2025-07-25 03:33:52', '2025-08-04 17:04:58'),
(115, 16, '123123.00', NULL, 0, NULL, '2025-08-04 13:41:43', '2025-08-04 16:23:51');

-- --------------------------------------------------------

--
-- Table structure for table `productvariantvalues`
--

CREATE TABLE `productvariantvalues` (
  `variant_id` bigint UNSIGNED NOT NULL,
  `value_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `productvariantvalues`
--

INSERT INTO `productvariantvalues` (`variant_id`, `value_id`) VALUES
(101, 1),
(105, 1),
(102, 2),
(104, 2),
(103, 3),
(101, 4),
(104, 4),
(102, 5),
(103, 5),
(105, 6);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `full_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `role` enum('customer','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `phone_number`, `password_hash`, `address`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Trần An Nhiên', 'annhien.tran@example.com', '0912345671', 'hashed_password_placeholder_1', '123 Đường Xuân Thủy, Cầu Giấy, Hà Nội', 'customer', 'active', '2025-07-25 03:33:52', '2025-07-25 03:33:52'),
(2, 'Lê Minh Thư', 'minhthu.le@example.com', '0912345672', 'hashed_password_placeholder_2', '456 Đường Lê Lợi, Quận 1, TP. HCM', 'customer', 'inactive', '2025-07-25 03:33:52', '2025-08-04 17:47:20'),
(3, 'Nguyễn Phương Chi', 'phuongchi.nguyen@example.com', '0912345673', 'hashed_password_placeholder_3', '789 Đường Hùng Vương, Hải Châu, Đà Nẵng', 'admin', 'active', '2025-07-25 03:33:52', '2025-08-05 09:52:35'),
(4, 'Phạm Khánh Vy', 'khanhvy.pham@example.com', '0912345674', 'hashed_password_placeholder_4', '101 Đường Bà Triệu, Hoàn Kiếm, Hà Nội', 'customer', 'active', '2025-07-25 03:33:52', '2025-07-25 03:33:52'),
(5, 'Vũ Thảo My', 'thaomy.vu@example.com', '0912345675', 'hashed_password_placeholder_5', '212 Đường 3/2, Quận 10, TP. HCM', 'customer', 'active', '2025-07-25 03:33:52', '2025-07-25 03:33:52'),
(6, 'Ha lô', 'a@a.a', NULL, '123a', NULL, 'admin', 'active', '2025-08-04 06:57:15', '2025-08-04 06:57:15');

-- --------------------------------------------------------

--
-- Table structure for table `wishlistitems`
--

CREATE TABLE `wishlistitems` (
  `user_id` bigint UNSIGNED NOT NULL,
  `variant_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wishlistitems`
--

INSERT INTO `wishlistitems` (`user_id`, `variant_id`, `created_at`) VALUES
(1, 103, '2025-07-25 03:33:52'),
(2, 101, '2025-07-25 03:33:52'),
(3, 105, '2025-07-25 03:33:52'),
(4, 102, '2025-07-25 03:33:52'),
(5, 104, '2025-07-25 03:33:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attributes`
--
ALTER TABLE `attributes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `attributevalues`
--
ALTER TABLE `attributevalues`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attribute_id` (`attribute_id`);

--
-- Indexes for table `cartitems`
--
ALTER TABLE `cartitems`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cart_id` (`cart_id`,`variant_id`),
  ADD KEY `variant_id` (`variant_id`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `variant_id` (`variant_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `productimages`
--
ALTER TABLE `productimages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `variant_id` (`variant_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `productvariants`
--
ALTER TABLE `productvariants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `productvariantvalues`
--
ALTER TABLE `productvariantvalues`
  ADD PRIMARY KEY (`variant_id`,`value_id`),
  ADD KEY `value_id` (`value_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone_number` (`phone_number`);

--
-- Indexes for table `wishlistitems`
--
ALTER TABLE `wishlistitems`
  ADD PRIMARY KEY (`user_id`,`variant_id`),
  ADD KEY `variant_id` (`variant_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attributes`
--
ALTER TABLE `attributes`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `attributevalues`
--
ALTER TABLE `attributevalues`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cartitems`
--
ALTER TABLE `cartitems`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orderitems`
--
ALTER TABLE `orderitems`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `productimages`
--
ALTER TABLE `productimages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `productvariants`
--
ALTER TABLE `productvariants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attributevalues`
--
ALTER TABLE `attributevalues`
  ADD CONSTRAINT `attributevalues_ibfk_1` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cartitems`
--
ALTER TABLE `cartitems`
  ADD CONSTRAINT `cartitems_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cartitems_ibfk_2` FOREIGN KEY (`variant_id`) REFERENCES `productvariants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD CONSTRAINT `orderitems_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orderitems_ibfk_2` FOREIGN KEY (`variant_id`) REFERENCES `productvariants` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `productimages`
--
ALTER TABLE `productimages`
  ADD CONSTRAINT `productimages_ibfk_1` FOREIGN KEY (`variant_id`) REFERENCES `productvariants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `productvariants`
--
ALTER TABLE `productvariants`
  ADD CONSTRAINT `productvariants_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `productvariantvalues`
--
ALTER TABLE `productvariantvalues`
  ADD CONSTRAINT `productvariantvalues_ibfk_1` FOREIGN KEY (`variant_id`) REFERENCES `productvariants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `productvariantvalues_ibfk_2` FOREIGN KEY (`value_id`) REFERENCES `attributevalues` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlistitems`
--
ALTER TABLE `wishlistitems`
  ADD CONSTRAINT `wishlistitems_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlistitems_ibfk_2` FOREIGN KEY (`variant_id`) REFERENCES `productvariants` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
