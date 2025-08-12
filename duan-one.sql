-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 12, 2025 at 06:42 AM
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
(1, 1, 'Trắng', '#FFFFFF'),
(2, 1, 'Đen', '#000000'),
(3, 1, 'Be', '#FFF8F3'),
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
(5, 5, '2025-07-25 03:33:52', '2025-07-25 03:33:52'),
(6, 7, '2025-08-10 07:51:21', '2025-08-11 13:16:19');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `image_url`, `parent_id`, `created_at`, `updated_at`) VALUES
(1, 'Đồ nữ', 'images/categories/b7fe9153ce6fb295_1754915278.png', NULL, '2025-07-25 03:33:52', '2025-08-11 12:51:17'),
(2, 'Đầm', 'images/categories/b7fe9153ce6fb295_1754915278.png', 1, '2025-07-25 03:33:52', '2025-08-11 12:49:19'),
(3, 'Áo', 'images/categories/b7fe9153ce6fb295_1754915278.png', 1, '2025-07-25 03:33:52', '2025-08-11 12:49:26'),
(4, 'Quần', 'images/categories/5443344a1f264f28_1754915298.png', 1, '2025-07-25 03:33:52', '2025-08-11 12:49:33'),
(5, 'Chân váy', 'images/categories/d9e911c54d82b4e2_1754915458.png', 2, '2025-07-25 03:33:52', '2025-08-11 12:50:02'),
(11, 'Áo khoác', 'images/categories/8dc778459a119ac7_1754917456.png', 1, '2025-08-11 13:04:16', '2025-08-11 13:04:16');

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
(5, 5, 105, 1, '480000.00'),
(6, 6, 102, 1, '123000.00'),
(7, 6, 103, 2, '312000.00'),
(8, 6, 118, 1, '750000.00'),
(9, 7, 118, 1, '750000.00'),
(10, 8, 102, 1, '123000.00'),
(11, 9, 102, 1, '123000.00'),
(12, 10, 102, 1, '123000.00'),
(13, 11, 102, 1, '123000.00'),
(14, 12, 102, 1, '123000.00'),
(15, 13, 101, 1, '750000.00'),
(16, 14, 101, 1, '750000.00'),
(17, 15, 101, 1, '750000.00'),
(18, 16, 101, 1, '750000.00'),
(19, 17, 101, 1, '750000.00'),
(20, 18, 102, 1, '123000.00'),
(21, 19, 102, 1, '123000.00'),
(22, 20, 103, 1, '31200.00'),
(23, 21, 103, 1, '31200.00'),
(24, 22, 103, 1, '31200.00'),
(25, 23, 103, 1, '31200.00'),
(26, 24, 103, 1, '31200.00'),
(27, 25, 103, 1, '31200.00'),
(28, 26, 103, 1, '31200.00'),
(29, 27, 103, 1, '31200.00'),
(30, 28, 101, 1, '750000.00'),
(31, 29, 101, 1, '750000.00'),
(32, 30, 101, 2, '750000.00'),
(33, 31, 101, 1, '750000.00'),
(34, 32, 101, 1, '750000.00'),
(35, 33, 101, 1, '750000.00'),
(36, 34, 101, 1, '750000.00'),
(37, 35, 101, 1, '750000.00'),
(38, 36, 101, 1, '750000.00'),
(39, 37, 101, 1, '750000.00'),
(40, 38, 101, 1, '750000.00'),
(41, 39, 101, 1, '750000.00'),
(42, 40, 101, 1, '750000.00'),
(43, 41, 101, 1, '750000.00'),
(44, 42, 101, 1, '750000.00'),
(45, 43, 101, 1, '750000.00'),
(46, 44, 101, 1, '750000.00'),
(47, 45, 103, 1, '31200.00'),
(48, 45, 118, 1, '750000.00');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `order_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `receiver_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `receiver_phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled','refunded') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_method` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_status` enum('unpaid','paid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_code`, `total_amount`, `receiver_name`, `receiver_phone`, `shipping_address`, `status`, `payment_method`, `payment_status`, `created_at`, `updated_at`) VALUES
(1, 1, '5850779641', '750000.00', 'Trần An Nhiên', '0912345671', '123 Đường Xuân Thủy, Cầu Giấy, Hà Nội', 'delivered', 'COD', 'paid', '2025-07-25 03:33:52', '2025-08-11 10:05:16'),
(2, 2, '5850779642', '550000.00', 'Lê Minh Thư', '0912345672', '456 Đường Lê Lợi, Quận 1, TP. HCM', 'shipped', 'Bank Transfer', 'paid', '2025-07-25 03:33:52', '2025-08-11 10:05:16'),
(3, 3, '5850779643', '420000.00', 'Nguyễn Phương Chi', '0912345673', '789 Đường Hùng Vương, Hải Châu, Đà Nẵng', 'processing', 'COD', 'unpaid', '2025-07-25 03:33:52', '2025-08-11 10:05:16'),
(4, 4, '5850779644', '890000.00', 'Phạm Khánh Vy', '0912345674', '101 Đường Bà Triệu, Hoàn Kiếm, Hà Nội', 'pending', 'Momo', 'unpaid', '2025-07-25 03:33:52', '2025-08-11 10:05:16'),
(5, 5, '5850779645', '480000.00', 'Vũ Thảo My', '0912345675', '212 Đường 3/2, Quận 10, TP. HCM', 'cancelled', 'COD', 'unpaid', '2025-07-25 03:33:52', '2025-08-11 10:05:16'),
(6, 7, '5850779646', '1497000.00', 'aaaa', '123', 'Ccasa Hostel, 24 Sao Biển, Vĩnh Hải, Nha Trang, Khánh Hòa', 'pending', 'COD', 'unpaid', '2025-08-10 21:25:35', '2025-08-11 10:05:16'),
(7, 7, '5850779647', '750000.00', 'aaaa', '123', 'Ngõ 2 Ao Sen, Phường Mộ Lao, Quận Hà Đông, Thành phố Hà Nội', 'pending', 'COD', 'unpaid', '2025-08-10 21:32:20', '2025-08-11 10:05:16'),
(8, 7, '5850779648', '123000.00', 'aaaa', '123', 'Hà Nội', 'pending', 'COD', 'unpaid', '2025-08-10 21:33:22', '2025-08-11 10:05:16'),
(9, 7, '5850779649', '123000.00', 'aaaa', '123', 'Cần Thơ', 'pending', 'MOMO', 'unpaid', '2025-08-10 21:54:12', '2025-08-11 10:05:16'),
(10, 7, '58507796410', '123000.00', 'aaaa', '123', 'Ngõ 2 Văn Trì, Phường Minh Khai, Quận Bắc Từ Liêm, Thành phố Hà Nội', 'pending', 'MOMO', 'unpaid', '2025-08-10 22:03:03', '2025-08-11 10:05:16'),
(11, 7, '58507796411', '123000.00', 'aaaa', '123', 'Văn Phú, Thị trấn Cẩm Khê, Huyện Cẩm Khê, Tỉnh Phú Thọ', 'pending', 'MOMO', 'unpaid', '2025-08-10 22:05:03', '2025-08-11 10:05:16'),
(12, 7, '58507796412', '123000.00', 'aaaa', '123', 'Cần Thơ', 'pending', 'MOMO', 'unpaid', '2025-08-10 22:09:43', '2025-08-11 10:05:16'),
(13, 7, '58507796413', '750000.00', 'aaaa', '123', 'Cầu QL 37, Âu Lâu, Yên Bái, Yên Bái', 'pending', 'MOMO_ATM', 'unpaid', '2025-08-10 22:12:40', '2025-08-11 10:05:16'),
(14, 7, '58507796414', '750000.00', 'aaaa', '123', 'Cần Thơ', 'pending', 'MOMO_ATM', 'unpaid', '2025-08-10 22:24:33', '2025-08-11 10:05:16'),
(15, 7, '58507796415', '750000.00', 'aaaa', '123', 'Cần Thơ', 'pending', 'COD', 'unpaid', '2025-08-10 22:40:06', '2025-08-11 10:05:16'),
(16, 7, '58507796416', '750000.00', 'aaaa', '123', 'Cần Thơ', 'pending', 'COD', 'unpaid', '2025-08-10 22:43:54', '2025-08-11 10:05:16'),
(17, 7, '58507796417', '750000.00', 'aaaa', '123', 'Cần Thơ', 'pending', 'MOMO_ATM', 'unpaid', '2025-08-10 22:44:26', '2025-08-11 10:05:16'),
(18, 7, '58507796418', '123000.00', 'aaaa', '123', 'Cần Thơ', 'pending', 'MOMO_ATM', 'unpaid', '2025-08-10 22:53:17', '2025-08-11 10:05:16'),
(19, 7, '58507796419', '123000.00', 'aaaa', '123', 'Cần Thơ', 'pending', 'MOMO_ATM', 'unpaid', '2025-08-10 23:00:23', '2025-08-11 10:05:16'),
(20, 7, '58507796420', '31200.00', 'aaaa', '123', 'Cần Thơ', 'pending', 'MOMO_ATM', 'unpaid', '2025-08-10 23:03:04', '2025-08-11 10:05:16'),
(21, 7, '58507796421', '31200.00', 'aaaa', '123', 'Cao Bằng', 'pending', 'MOMO_ATM', 'unpaid', '2025-08-10 23:09:45', '2025-08-11 10:05:16'),
(22, 7, '58507796422', '31200.00', 'aaaa', '123', 'Cần Thơ', 'pending', 'MOMO_ATM', 'unpaid', '2025-08-10 23:19:15', '2025-08-11 10:05:16'),
(23, 7, '58507796423', '31200.00', 'aaaa', '123', 'Quận Đống Đa, Thành phố Hà Nội', 'pending', 'MOMO_CC', 'unpaid', '2025-08-10 23:21:09', '2025-08-11 10:05:16'),
(24, 7, '58507796424', '31200.00', 'aaaa', '123', 'Quận Cầu Giấy, Thành phố Hà Nội', 'pending', 'MOMO_CC', 'unpaid', '2025-08-10 23:30:19', '2025-08-11 10:05:16'),
(25, 7, '58507796425', '31200.00', 'aaaa', '123', 'Thành phố Cao Lãnh, Đồng Tháp', 'pending', 'MOMO_CC', 'unpaid', '2025-08-10 23:33:31', '2025-08-11 10:05:16'),
(26, 7, '58507796426', '31200.00', 'aaaa', '123', 'Thành phố Hưng Yên, Tỉnh Hưng Yên', 'pending', 'MOMO_CC', 'paid', '2025-08-10 23:36:56', '2025-08-11 10:05:16'),
(27, 7, '58507796427', '31200.00', 'aaaa', '123', 'Thành phố Hưng Yên, Tỉnh Hưng Yên', 'pending', 'MOMO_CC', 'paid', '2025-08-10 23:40:13', '2025-08-11 10:05:16'),
(28, 7, '58507796428', '750000.00', 'aaaa', '123', 'Hưng Vũ, Bắc Sơn, Lạng Sơn', 'pending', 'MOMO_CC', 'paid', '2025-08-11 08:05:09', '2025-08-11 10:05:16'),
(29, 7, '58507796429', '750000.00', 'aaaa', '123', 'Cầu Giấy, Quan Hoa, Cầu Giấy, Hà Nội', 'pending', 'VNPAY_ATM', 'unpaid', '2025-08-11 08:26:12', '2025-08-11 10:05:16'),
(30, 7, '58507796430', '1500000.00', 'aaaa', '123', 'Cần Thơ', 'pending', 'VNPAY_ATM', 'unpaid', '2025-08-11 08:31:32', '2025-08-11 10:05:16'),
(31, 7, '58507796431', '750000.00', 'aaaa', '123', 'Đà Lạt, Lâm Đồng', 'pending', 'STRIPE', 'unpaid', '2025-08-11 08:46:00', '2025-08-11 10:05:16'),
(32, 7, '58507796432', '750000.00', 'aaaa', '123', 'Cần Thơ', 'pending', 'STRIPE', 'unpaid', '2025-08-11 08:47:33', '2025-08-11 10:05:16'),
(33, 7, '58507796433', '750000.00', 'aaaa', '123', 'Cao Bằng', 'pending', 'STRIPE', 'unpaid', '2025-08-11 08:54:39', '2025-08-11 10:05:16'),
(34, 7, '58507796434', '750000.00', 'aaaa', '123', 'Đà Lạt, Lâm Đồng', 'pending', 'STRIPE', 'unpaid', '2025-08-11 08:58:40', '2025-08-11 10:05:16'),
(35, 7, '58507796435', '750000.00', 'aaaa', '123', 'Cần Thơ', 'pending', 'STRIPE', 'paid', '2025-08-11 09:05:10', '2025-08-11 10:05:16'),
(36, 7, '58507796436', '750000.00', 'aaaa', '123', 'Hải Phòng', 'pending', 'STRIPE', 'unpaid', '2025-08-11 09:07:58', '2025-08-11 10:05:16'),
(37, 7, '58507796437', '750000.00', 'aaaa', '123', 'Quận Cầu Giấy, Thành phố Hà Nội', 'pending', 'STRIPE', 'unpaid', '2025-08-11 09:08:45', '2025-08-11 10:05:16'),
(38, 7, '58507796438', '750000.00', 'aaaa', '123', 'Cà Mau', 'pending', 'MOMO_CC', 'unpaid', '2025-08-11 09:09:41', '2025-08-11 10:05:16'),
(39, 7, '58507796439', '750000.00', 'aaaa', '123', 'Cầu Tân An, Xã Tân An, Huyện Văn Bàn, Tỉnh Lào Cai', 'pending', 'STRIPE', 'unpaid', '2025-08-11 09:13:07', '2025-08-11 10:05:16'),
(40, 7, '58507796440', '750000.00', 'aaaa', '123', 'Cầu QL 37, Âu Lâu, Yên Bái, Yên Bái', 'pending', 'STRIPE', 'paid', '2025-08-11 09:16:56', '2025-08-11 10:05:16'),
(41, 7, '58507796441', '750000.00', 'aaaa', '123', 'Cầu TL 315B, Xã Hà Lộc, Thị xã Phú Thọ, Tỉnh Phú Thọ', 'pending', 'STRIPE', 'unpaid', '2025-08-11 09:23:45', '2025-08-11 10:05:16'),
(42, 7, '58507796442', '750000.00', 'aaaa', '123', 'Cầu Tân An, Xã Tân An, Huyện Văn Bàn, Tỉnh Lào Cai', 'pending', 'STRIPE', 'unpaid', '2025-08-11 09:27:42', '2025-08-11 10:05:16'),
(43, 7, '58507796443', '750000.00', 'aaaa', '123', 'Cầu TL 315B, Xã Hà Lộc, Thị xã Phú Thọ, Tỉnh Phú Thọ', 'pending', 'STRIPE', 'paid', '2025-08-11 09:32:01', '2025-08-11 10:05:16'),
(44, 7, '58507796444', '750000.00', 'aaaa', '123', 'Hà Nam', 'pending', 'MOMO_CC', 'paid', '2025-08-11 09:33:55', '2025-08-11 10:05:16'),
(45, 7, NULL, '781200.00', 'aaaa', '123', 'Tạp hóa Bác Ty, 40 Trưng Nhị, Trưng Trắc, Phúc Yên, Vĩnh Phúc', 'pending', 'STRIPE', 'paid', '2025-08-11 13:18:42', '2025-08-11 13:19:11');

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
(8, 105, 'images/product_images/variant_105_1754149106_688e30f294d73.jpg', '2025-08-02 15:38:26'),
(15, 105, 'images/product_images/variant_105_1754292012_68905f2ca3bc4.jpg', '2025-08-04 07:20:12'),
(16, 104, 'images/product_images/variant_104_1754292042_68905f4a4039d.jpg', '2025-08-04 07:20:42'),
(17, 104, 'images/product_images/variant_104_1754292042_68905f4a4079a.jpg', '2025-08-04 07:20:42'),
(18, 104, 'images/product_images/variant_104_1754292042_68905f4a409b7.jpg', '2025-08-04 07:20:42'),
(19, 104, 'images/product_images/variant_104_1754292042_68905f4a40bbf.jpg', '2025-08-04 07:20:42'),
(20, 104, 'images/product_images/variant_104_1754292051_68905f5376b44.jpg', '2025-08-04 07:20:51'),
(35, 115, 'images/product_images/variant_115_1754322621_6890d6bd3a1c6.jpg', '2025-08-04 15:50:21'),
(36, 115, 'images/product_images/variant_115_1754322621_6890d6bd3a484.jpg', '2025-08-04 15:50:21'),
(37, 115, 'images/product_images/variant_115_1754322621_6890d6bd3a6cb.jpg', '2025-08-04 15:50:21'),
(38, 115, 'images/product_images/variant_115_1754322621_6890d6bd3a8f3.jpg', '2025-08-04 15:50:21'),
(39, 115, 'images/product_images/variant_115_1754322621_6890d6bd3abb3.jpg', '2025-08-04 15:50:21'),
(40, 101, 'images/product_images/variant_101_1754494078_6893747e9d10a.jpg', '2025-08-06 15:27:58'),
(41, 101, 'images/product_images/variant_101_1754494078_6893747e9e111.jpg', '2025-08-06 15:27:58'),
(42, 101, 'images/product_images/variant_101_1754494078_6893747e9e614.jpg', '2025-08-06 15:27:58'),
(43, 101, 'images/product_images/variant_101_1754494078_6893747e9eae3.jpg', '2025-08-06 15:27:58'),
(44, 101, 'images/product_images/variant_101_1754494078_6893747e9ee72.jpg', '2025-08-06 15:27:58'),
(45, 101, 'images/product_images/variant_101_1754494078_6893747e9f1e9.jpg', '2025-08-06 15:27:58'),
(46, 101, 'images/product_images/variant_101_1754494078_6893747e9f520.jpg', '2025-08-06 15:27:58'),
(47, 103, 'images/product_images/variant_103_1754494474_6893760a5c214.jpg', '2025-08-06 15:34:34'),
(48, 103, 'images/product_images/variant_103_1754494474_6893760a5c68e.jpg', '2025-08-06 15:34:34'),
(49, 103, 'images/product_images/variant_103_1754494474_6893760a5cacc.jpg', '2025-08-06 15:34:34'),
(50, 103, 'images/product_images/variant_103_1754494474_6893760a5cf33.jpg', '2025-08-06 15:34:34');

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
(1, 'Váy Lụa Tay Phồng Cổ Vuông', 'Đầm Sát Nách Dáng Ôm Dài Buộc Nơ - Phong Cách Quý Cô Hiện Đại\r\n\r\nThiết kế mang đến sự kết hợp hoàn hảo giữa sự nữ tính và thanh lịch. Phom dáng ôm A với hai dây cổ U tôn lên vóc dáng người mặc một cách tự nhiên và quyến rũ. Điểm nhấn nằm ở phần thân trước với đè nẹp và cúc trang trí, tạo sự nổi bật đầy tinh tế. Phần thân sau có chi tiết xẻ tạo sự thoải mái và dễ dàng di chuyển, đồng thời thêm phần nơ sau lưng mang lại vẻ đẹp duyên dáng và độc đáo cho thiết kế. \r\n\r\nChất liệu vải Tweed cao cấp, chủ yếu là Polyester, mềm mịn và bền bỉ. Vải Tweed không chỉ mang đến sự thoải mái mà còn tạo nên vẻ ngoài sang trọng, phù hợp cho những quý cô yêu thích sự tinh tế và thanh lịch.\r\n\r\nSản phẩm là lựa chọn hoàn hảo cho các dịp tiệc tùng, gặp gỡ bạn bè hay dạo phố. Vẻ ngoài duyên dáng và thanh lịch của chiếc đầm giúp bạn dễ dàng thu hút mọi ánh nhìn, đồng thời tạo cảm giác thoải mái, tự tin trong mọi khoảnh khắc.\r\n\r\nMix & Match:\r\n\r\nPhong cách tiệc tùng: Kết hợp cùng một chiếc áo khoác tweed trong bộ sưu tập, giày cao gót và túi xách cầm tay nhỏ, bạn sẽ có một set đồ hoàn hảo, đầy sang trọng.\r\nPhong cách dạo phố: Phối đầm với giày sneakers và túi xách đơn giản để tạo nên một vẻ ngoài năng động, thoải mái nhưng không kém phần tinh tế.\r\nPhong cách thanh lịch: Để tạo nên một bộ đồ thanh lịch và quý phái, mix đầm với một chiếc áo khoác dáng dài và giày cao gót, sẵn sàng tỏa sáng trong mọi sự kiện.\r\nLưu ý khi sử dụng:\r\n\r\nĐầm có phần nơ sau lưng có thể tháo rời khi giặt, đồng thời cúc nơ kim loại trang trí cần được bảo quản kỹ. Khuyến khích giặt tay để tránh móng tay hoặc các vật dụng như túi xách có thể làm rút sợi vải và ảnh hưởng đến phom dáng của sản phẩm.', 'images/products_thumbnail/6893906dc7b20_1754501229.jpg', 2, '2025-07-25 03:33:52', '2025-08-06 17:27:09'),
(2, 'Áo Sơ Mi Lụa Satin Tay Dài', 'Áo sơ mi công sở cao cấp, chất liệu lụa satin thoáng mát, dễ phối đồ.', 'images/products_thumbnail/6893760a5aa27_1754494474.jpg', 2, '2025-07-25 03:33:52', '2025-08-06 15:34:34'),
(3, 'Chân Váy Chữ A Xếp Ly', '', 'images/products_thumbnail/6893929d7a8d0_1754501789.jpg', 4, '2025-07-25 03:33:52', '2025-08-06 17:36:29'),
(5, 'Quần Culottes Ống Rộng', 'Quần culottes vải đũi, ống rộng thoải mái, item không thể thiếu trong tủ đồ mùa hè.', 'images/products_thumbnail/689392a8a46d3_1754501800.jpg', 4, '2025-07-25 03:33:52', '2025-08-06 17:36:40'),
(16, 'aaa', 'aaa', 'images/products_thumbnail/6893929077011_1754501776.jpg', 5, '2025-08-04 13:41:43', '2025-08-06 17:36:16'),
(17, 'Chân váy A phối hoa nổi', '', 'images/products_thumbnail/689392db21779_1754501851.jpg', 2, '2025-08-06 17:37:31', '2025-08-06 17:37:31'),
(19, 'Chân váy dáng A ngắn, bèo đổ chân', '', 'images/products_thumbnail/6893931cee757_1754501916.jpg', 2, '2025-08-06 17:38:24', '2025-08-06 17:38:36');

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
(101, 1, '890000.00', '750000.00', 27, 'images/vay-lua-trang.jpg', '2025-07-25 03:33:52', '2025-08-11 09:33:55'),
(102, 1, '890000.00', '123000.00', 32, 'images/vay-lua-den.jpg', '2025-07-25 03:33:52', '2025-08-10 23:00:23'),
(103, 2, '550000.00', '31200.00', 69, 'images/ao-somi-be.jpg', '2025-07-25 03:33:52', '2025-08-11 13:18:42'),
(104, 3, '420000.00', NULL, 60, 'images/chan-vay-xeply-den.jpg', '2025-07-25 03:33:52', '2025-08-06 17:40:53'),
(105, 5, '480000.00', '435600.00', 75, 'images/quan-culottes-trang.jpg', '2025-07-25 03:33:52', '2025-08-06 17:36:40'),
(115, 16, '123123.00', NULL, 0, NULL, '2025-08-04 13:41:43', '2025-08-06 17:36:16'),
(116, 17, '555000.00', NULL, 0, NULL, '2025-08-06 17:37:31', '2025-08-06 17:37:31'),
(117, 19, '458000.00', NULL, 0, NULL, '2025-08-06 17:38:24', '2025-08-06 17:38:36'),
(118, 1, '890000.00', '750000.00', 57, 'images/vay-lua-trang.jpg', '2025-07-25 03:33:52', '2025-08-11 13:18:42');

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
(103, 1),
(105, 1),
(118, 1),
(102, 2),
(104, 2),
(103, 3),
(104, 4),
(101, 5),
(102, 5),
(103, 5),
(105, 6),
(118, 6);

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
(6, 'Ha lô', 'a@a.a', '01234567890', '$2y$10$l8foPSnMbV3Lkj4nk044P.USgoXlEodhfENRC2Oo0tJDsVmP7G2VW', NULL, 'admin', 'active', '2025-08-04 06:57:15', '2025-08-07 16:23:01'),
(7, 'aaaa', 'a@a.aa', '123', '$2y$10$a1WKHTE3E2Fi3E/CrI6OsOLAXr79O0YqT91lYiu2vPFKkNCd5OLYe', NULL, 'admin', 'active', '2025-08-07 10:49:00', '2025-08-09 10:20:49');

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
  ADD UNIQUE KEY `order_code` (`order_code`),
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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orderitems`
--
ALTER TABLE `orderitems`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `productimages`
--
ALTER TABLE `productimages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `productvariants`
--
ALTER TABLE `productvariants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
