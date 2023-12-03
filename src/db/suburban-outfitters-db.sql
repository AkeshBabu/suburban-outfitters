-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 03, 2023 at 09:12 PM
-- Server version: 8.0.31
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `suburban-outfitters-db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `admin_id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(12) NOT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `first_name`, `last_name`, `email`, `phone`) VALUES
(14, 'Bill', 'Smith', 'bsmith', '6506655872');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `customer_id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `shipping_address` varchar(150) NOT NULL,
  `billing_address` varchar(150) NOT NULL,
  PRIMARY KEY (`customer_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `first_name`, `last_name`, `email`, `password`, `phone`, `shipping_address`, `billing_address`) VALUES
(39, 'Chandler', 'Bing', 'sarcastic.bing@2023.com', '$2y$10$lHJ/fUFVXjR614N0bDZg9OxDf33MjHQapHQ8Raa4re/Kbpjt4vOXy', '6579988113', 'Central Perk, NY City', 'Central Perk, NY City'),
(34, 'Bill', 'Smith', 'bsmith', '$2y$10$HGM6UpVwrH7SMK9xMcLMke5/6Iro.eozkpyv4x4Y8/pOnH7leCUFm', '6506655872', '255B St. Street, Salt Lake City, UT', '255B St. Street, Salt Lake City, UT'),
(35, 'Pauline', 'Jones', 'pjones', '$2y$10$kS71tjwwcheGSeNPC8m3mOfVPYwQZV1I.kEu3pv6mx2X5GRLU8bzi', '2236767921', '656 E 700 W, Salt Lake City, UT', '656 E 700 W, Salt Lake City, UT');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

DROP TABLE IF EXISTS `inventory`;
CREATE TABLE IF NOT EXISTS `inventory` (
  `inventory_id` int NOT NULL AUTO_INCREMENT,
  `product_id` int DEFAULT NULL,
  `vendor_id` int DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `inventory_date` date DEFAULT NULL,
  `size` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`inventory_id`),
  KEY `product_id` (`product_id`),
  KEY `vendor_id` (`vendor_id`)
) ENGINE=MyISAM AUTO_INCREMENT=170 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`inventory_id`, `product_id`, `vendor_id`, `quantity`, `inventory_date`, `size`) VALUES
(21, 1, 101, 40, '2023-01-15', 'xs'),
(22, 1, 101, 40, '2023-01-15', 'sm'),
(23, 1, 101, 62, '2023-01-15', 'md'),
(24, 1, 101, 32, '2023-01-15', 'lg'),
(25, 1, 101, 21, '2023-01-15', 'xl'),
(26, 1, 101, 21, '2023-01-15', '2xl'),
(27, 1, 101, 29, '2023-01-15', '3xl'),
(28, 2, 102, 33, '2023-02-10', 'xs'),
(29, 2, 102, 12, '2023-02-10', 'sm'),
(30, 2, 102, 32, '2023-02-10', 'md'),
(31, 2, 102, 19, '2023-02-10', 'lg'),
(32, 2, 102, 45, '2023-02-10', 'xl'),
(33, 2, 102, 55, '2023-02-10', '2xl'),
(34, 2, 102, 60, '2023-02-10', '3xl'),
(167, 51, 112, -2, '2023-12-03', 'md'),
(36, 9, 101, 42, '2023-06-12', 'None'),
(37, 17, 103, 55, '2023-08-09', 'None'),
(38, 3, 101, 12, '2023-03-20', 'xs'),
(39, 3, 101, 3, '2023-03-20', 'sm'),
(40, 3, 101, -5, '2023-03-20', 'md'),
(41, 3, 101, 8, '2023-03-20', 'lg'),
(42, 3, 101, 18, '2023-03-20', 'xl'),
(43, 3, 101, 43, '2023-03-20', '2xl'),
(44, 3, 101, 2, '2023-03-20', '3xl'),
(45, 4, 104, 40, '2023-04-25', 'xs'),
(46, 4, 104, 25, '2023-04-25', 'sm'),
(47, 4, 104, 37, '2023-04-25', 'md'),
(48, 4, 104, 50, '2023-04-25', 'lg'),
(49, 4, 104, 13, '2023-04-25', 'xl'),
(50, 4, 104, 19, '2023-04-25', '2xl'),
(51, 4, 104, 14, '2023-04-25', '3xl'),
(52, 6, 101, 18, '2023-06-06', 'xs'),
(53, 6, 101, 19, '2023-06-06', 'sm'),
(54, 6, 101, 24, '2023-06-06', 'md'),
(55, 6, 101, 25, '2023-06-06', 'lg'),
(56, 6, 101, 24, '2023-06-06', 'xl'),
(57, 6, 101, 32, '2023-06-06', '2xl'),
(58, 6, 101, 30, '2023-06-06', '3xl'),
(59, 7, 107, 32, '2023-07-07', 'xs'),
(60, 7, 107, 20, '2023-07-07', 'sm'),
(61, 7, 107, 23, '2023-07-07', 'md'),
(62, 7, 107, 23, '2023-07-07', 'lg'),
(63, 7, 107, 23, '2023-07-07', 'xl'),
(64, 7, 107, 33, '2023-07-07', '2xl'),
(65, 7, 107, 33, '2023-07-07', '3xl'),
(66, 8, 104, 23, '2023-08-08', 'xs'),
(67, 8, 104, 22, '2023-08-08', 'sm'),
(68, 8, 104, 24, '2023-08-08', 'md'),
(69, 8, 104, 25, '2023-08-08', 'lg'),
(70, 8, 104, 11, '2023-08-08', 'xl'),
(71, 8, 104, 23, '2023-08-08', '2xl'),
(72, 8, 104, 22, '2023-08-08', '3xl'),
(73, 10, 104, 30, '2023-10-10', 'xs'),
(74, 10, 104, 50, '2023-10-10', 'sm'),
(75, 10, 104, 16, '2023-10-10', 'md'),
(76, 10, 104, 5, '2023-10-10', 'lg'),
(77, 10, 104, 60, '2023-10-10', 'xl'),
(78, 10, 104, 50, '2023-10-10', '2xl'),
(79, 10, 104, 20, '2023-10-10', '3xl'),
(80, 11, 111, 22, '2023-11-11', 'xs'),
(81, 11, 111, 22, '2023-11-11', 'sm'),
(82, 11, 111, 22, '2023-11-11', 'md'),
(83, 11, 111, 22, '2023-11-11', 'lg'),
(84, 11, 111, 17, '2023-11-11', 'xl'),
(85, 11, 111, 19, '2023-11-11', '2xl'),
(86, 11, 111, 22, '2023-11-11', '3xl'),
(87, 12, 112, 24, '2023-12-12', 'xs'),
(88, 12, 112, 21, '2023-12-12', 'sm'),
(89, 12, 112, 24, '2023-12-12', 'md'),
(90, 12, 112, 18, '2023-12-12', 'lg'),
(91, 12, 112, 24, '2023-12-12', 'xl'),
(92, 12, 112, 24, '2023-12-12', '2xl'),
(93, 12, 112, 24, '2023-12-12', '3xl'),
(94, 13, 111, 26, '2023-01-13', 'xs'),
(95, 13, 111, 23, '2023-01-13', 'sm'),
(96, 13, 111, 25, '2023-01-13', 'md'),
(97, 13, 111, 20, '2023-01-13', 'lg'),
(98, 13, 111, 26, '2023-01-13', 'xl'),
(99, 13, 111, 26, '2023-01-13', '2xl'),
(100, 13, 111, 26, '2023-01-13', '3xl'),
(101, 14, 114, 28, '2023-02-14', 'xs'),
(102, 14, 114, 28, '2023-02-14', 'sm'),
(103, 14, 114, 27, '2023-02-14', 'md'),
(104, 14, 114, 28, '2023-02-14', 'lg'),
(105, 14, 114, 28, '2023-02-14', 'xl'),
(106, 14, 114, 28, '2023-02-14', '2xl'),
(107, 14, 114, 28, '2023-02-14', '3xl'),
(108, 15, 111, 30, '2023-03-15', 'xs'),
(109, 15, 111, 30, '2023-03-15', 'sm'),
(110, 15, 111, 30, '2023-03-15', 'md'),
(111, 15, 111, 30, '2023-03-15', 'lg'),
(112, 15, 111, 30, '2023-03-15', 'xl'),
(113, 15, 111, 30, '2023-03-15', '2xl'),
(114, 15, 111, 30, '2023-03-15', '3xl'),
(115, 16, 116, 32, '2023-04-16', 'xs'),
(116, 16, 116, 32, '2023-04-16', 'sm'),
(117, 16, 116, 32, '2023-04-16', 'md'),
(118, 16, 116, 30, '2023-04-16', 'lg'),
(119, 16, 116, 32, '2023-04-16', 'xl'),
(120, 16, 116, 32, '2023-04-16', '2xl'),
(121, 16, 116, 32, '2023-04-16', '3xl'),
(122, 18, 111, 34, '2023-06-18', 'xs'),
(123, 18, 111, 34, '2023-06-18', 'sm'),
(124, 18, 111, 34, '2023-06-18', 'md'),
(125, 18, 111, 34, '2023-06-18', 'lg'),
(126, 18, 111, 34, '2023-06-18', 'xl'),
(127, 18, 111, 34, '2023-06-18', '2xl'),
(128, 18, 111, 34, '2023-06-18', '3xl'),
(129, 19, 119, 36, '2023-07-19', 'xs'),
(130, 19, 119, 36, '2023-07-19', 'sm'),
(131, 19, 119, 36, '2023-07-19', 'md'),
(132, 19, 119, 36, '2023-07-19', 'lg'),
(133, 19, 119, 36, '2023-07-19', 'xl'),
(134, 19, 119, 36, '2023-07-19', '2xl'),
(135, 19, 119, 36, '2023-07-19', '3xl'),
(136, 20, 107, 38, '2023-08-20', 'xs'),
(137, 20, 107, 38, '2023-08-20', 'sm'),
(138, 20, 107, 38, '2023-08-20', 'md'),
(139, 20, 107, 38, '2023-08-20', 'lg'),
(140, 20, 107, 38, '2023-08-20', 'xl'),
(141, 20, 107, 38, '2023-08-20', '2xl'),
(142, 20, 107, 38, '2023-08-20', '3xl'),
(155, 23, 111, 38, '2023-12-02', 'None'),
(164, 48, 112, 100, '2023-12-02', 'sm'),
(147, 25, 22, 38, '2023-11-30', 'None'),
(148, 25, 12, 10, '2023-11-28', 'xs'),
(149, 27, 22, 22, '2023-11-30', 'lg'),
(153, 43, 2, 12, '0001-12-13', 'None'),
(168, 52, 112, 40, '2023-12-03', 'md'),
(156, 22, 111, 33, '2023-12-02', 'None'),
(157, 21, 111, 28, '2023-12-02', 'None'),
(158, 24, 111, 37, '2023-12-02', 'None'),
(159, 25, 111, 38, '2023-12-02', 'None'),
(162, 5, 111, 93, '2023-12-02', 'None'),
(169, 53, 112, 150, '2023-12-03', 'md');

-- --------------------------------------------------------

--
-- Table structure for table `orderline`
--

DROP TABLE IF EXISTS `orderline`;
CREATE TABLE IF NOT EXISTS `orderline` (
  `line_id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` varchar(100) NOT NULL,
  `quantity` varchar(100) NOT NULL,
  `total_amount` double(10,2) NOT NULL,
  `size` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`line_id`)
) ENGINE=MyISAM AUTO_INCREMENT=167 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orderline`
--

INSERT INTO `orderline` (`line_id`, `order_id`, `product_id`, `quantity`, `total_amount`, `size`) VALUES
(133, 93, '10', '4', 200.00, 'md'),
(132, 93, '10', '1', 50.00, 'lg'),
(131, 93, '17', '3', 60.00, 'None'),
(129, 91, '22', '3', 570.00, 'None'),
(128, 91, '5', '6', 180.00, 'None'),
(127, 91, '3', '3', 450.00, 'lg'),
(126, 90, '3', '3', 450.00, 'sm'),
(125, 90, '3', '4', 600.00, '2xl'),
(130, 92, '21', '2', 500.00, 'None'),
(123, 88, '3', '2', 300.00, 'xs'),
(121, 87, '3', '3', 450.00, 'lg'),
(122, 87, '3', '2', 300.00, 'xl'),
(134, 93, '3', '2', 300.00, 'lg'),
(135, 93, '7', '2', 280.00, 'sm'),
(136, 93, '3', '1', 150.00, '2xl'),
(137, 94, '5', '1', 30.00, 'None'),
(138, 95, '25', '2', 560.00, 'None'),
(139, 95, '24', '3', 900.00, 'None'),
(140, 96, '21', '3', 750.00, 'None'),
(141, 97, '17', '5', 100.00, 'None'),
(142, 98, '22', '3', 570.00, 'None'),
(143, 98, '12', '4', 360.00, 'lg'),
(144, 99, '11', '3', 420.00, 'xl'),
(145, 100, '4', '3', 180.00, 'lg'),
(146, 101, '14', '1', 130.00, 'md'),
(147, 102, '6', '1', 130.00, 'md'),
(148, 103, '3', '1', 150.00, 'xl'),
(149, 103, '3', '3', 450.00, 'sm'),
(150, 104, '23', '2', 520.00, 'None'),
(151, 105, '2', '2', 120.00, 'lg'),
(152, 106, '2', '2', 120.00, 'lg'),
(153, 107, '6', '2', 260.00, 'xl'),
(154, 108, '16', '2', 240.00, 'lg'),
(155, 109, '51', '52', 4160.00, 'md'),
(156, 110, '6', '3', 390.00, 'xl'),
(157, 110, '6', '2', 260.00, 'lg'),
(158, 110, '9', '2', 60.00, 'None'),
(159, 111, '4', '2', 120.00, 'lg'),
(160, 112, '4', '3', 180.00, 'md'),
(161, 112, '21', '2', 500.00, 'None'),
(162, 113, '52', '100', 10000.00, 'md'),
(163, 114, '13', '3', 420.00, 'lg'),
(164, 114, '13', '3', 420.00, 'sm'),
(165, 114, '17', '2', 40.00, 'None'),
(166, 115, '53', '100', 10000.00, 'md');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` int NOT NULL AUTO_INCREMENT,
  `customer_id` int NOT NULL,
  `admin_id` int NOT NULL,
  `order_date` date NOT NULL,
  `total_amount` double(10,2) NOT NULL,
  `status` varchar(50) NOT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=116 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `customer_id`, `admin_id`, `order_date`, `total_amount`, `status`) VALUES
(108, 34, 0, '2023-12-03', 240.00, 'Placed'),
(107, 34, 0, '2023-12-03', 260.00, 'Placed'),
(106, 34, 0, '2023-12-03', 120.00, 'Placed'),
(105, 35, 14, '2023-12-03', 120.00, 'Processed'),
(104, 35, 14, '2023-12-03', 520.00, 'Processed'),
(103, 35, 0, '2023-12-03', 600.00, 'Placed'),
(102, 35, 0, '2023-12-03', 130.00, 'Placed'),
(101, 35, 0, '2023-12-03', 130.00, 'Placed'),
(100, 35, 14, '2023-12-03', 180.00, 'Processed'),
(99, 34, 0, '2023-12-03', 420.00, 'Placed'),
(98, 34, 14, '2023-12-03', 930.00, 'Processed'),
(97, 34, 14, '2023-12-03', 100.00, 'Processed'),
(95, 35, 14, '2023-12-03', 1460.00, 'Processed'),
(96, 34, 0, '2023-12-03', 750.00, 'Placed'),
(94, 35, 0, '2023-12-03', 30.00, 'Placed'),
(93, 35, 14, '2023-12-03', 1040.00, 'Processed'),
(92, 35, 0, '2023-12-03', 500.00, 'Placed'),
(91, 35, 0, '2023-12-03', 1200.00, 'Placed'),
(90, 34, 14, '2023-12-03', 1050.00, 'Processed'),
(89, 34, 0, '2023-12-03', 3000.00, 'Placed'),
(88, 34, 14, '2023-12-03', 300.00, 'Processed'),
(109, 34, 14, '2023-12-03', 4160.00, 'Processed'),
(111, 39, 0, '2023-12-03', 120.00, 'Placed'),
(112, 40, 14, '2023-12-03', 680.00, 'Processed'),
(113, 34, 0, '2023-12-03', 10000.00, 'Placed'),
(114, 35, 14, '2023-12-03', 880.00, 'Processed'),
(115, 34, 0, '2023-12-03', 10000.00, 'Placed');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `admin_id` int NOT NULL,
  `product_name` varchar(150) NOT NULL,
  `price` double(10,2) NOT NULL,
  `category` varchar(150) NOT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `admin_id`, `product_name`, `price`, `category`) VALUES
(3, 1, 'NYCITY Joggers', 150.00, 'Men'),
(4, 1, 'SubOut Tee', 60.00, 'Men'),
(5, 1, 'SUBU Hat', 30.00, 'Headwear'),
(6, 1, '1992D SubUR Shirt', 130.00, 'Men'),
(7, 1, 'GTAVII Jeans', 140.00, 'Men'),
(8, 1, 'MotorXD Jacket', 150.00, 'Men'),
(9, 1, 'SUBU Beanie', 30.00, 'Headwear'),
(10, 1, 'SUUB Tee', 50.00, 'Women'),
(11, 1, 'Leafy SUBB Dress', 140.00, 'Women'),
(12, 1, '$SUBURB Skirt', 90.00, 'Women'),
(13, 1, 'SU=BB Jeans', 140.00, 'Women'),
(14, 1, 'BANNII Long Sleeve', 130.00, 'Women'),
(15, 1, 'Royal SUBR Shirt', 220.00, 'Women'),
(16, 1, 'BurnOUT subURB Hoodie', 120.00, 'Women'),
(17, 1, 'SUBIB Beanie', 20.00, 'Headwear'),
(18, 1, '97 OUTFITTERS T-Shirt', 70.00, 'Women'),
(19, 1, '99-99 T-Shirt', 60.00, 'Women'),
(20, 1, 'SO Tee Apple90', 50.00, 'Women'),
(1, 1, 'DefaultMode Hoodie', 200.00, 'Men'),
(2, 1, 'SUBBUR 939 T-Shirt', 60.00, 'Men'),
(23, 13, 'Outfitters Sneakers', 260.00, 'Footwear'),
(22, 13, 'SubOuts Casual Shoes', 190.00, 'Footwear'),
(21, 13, 'Unniquee Sneakers', 250.00, 'Footwear'),
(24, 13, 'SO Formal Shoes- Men', 300.00, 'Footwear'),
(25, 13, 'SO Formal Shoes- Women', 280.00, 'Footwear'),
(26, 13, 'Ultimate SportZ Shoes', 380.00, 'Footwear'),
(53, 14, 'SO Classix T-Shirt', 70.00, 'Men');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

DROP TABLE IF EXISTS `wishlist`;
CREATE TABLE IF NOT EXISTS `wishlist` (
  `wishlist_id` int NOT NULL AUTO_INCREMENT,
  `customer_id` int NOT NULL,
  `product_id` int NOT NULL,
  `date_added` date NOT NULL,
  PRIMARY KEY (`wishlist_id`)
) ENGINE=MyISAM AUTO_INCREMENT=72 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`wishlist_id`, `customer_id`, `product_id`, `date_added`) VALUES
(46, 39, 1, '2023-12-03'),
(45, 39, 6, '2023-12-03'),
(44, 39, 4, '2023-12-03'),
(43, 39, 3, '2023-12-03'),
(42, 34, 26, '2023-12-02'),
(40, 35, 4, '2023-12-02'),
(39, 34, 22, '2023-12-02'),
(38, 34, 11, '2023-12-02'),
(37, 34, 3, '2023-12-02'),
(47, 39, 14, '2023-12-03'),
(48, 39, 26, '2023-12-03'),
(49, 39, 23, '2023-12-03'),
(50, 39, 17, '2023-12-03'),
(51, 39, 5, '2023-12-03'),
(52, 39, 21, '2023-12-03'),
(53, 34, 10, '2023-12-03'),
(54, 34, 9, '2023-12-03'),
(55, 34, 23, '2023-12-03'),
(56, 34, 13, '2023-12-03'),
(57, 34, 25, '2023-12-03'),
(58, 35, 22, '2023-12-03'),
(59, 35, 23, '2023-12-03'),
(61, 35, 8, '2023-12-03'),
(62, 35, 7, '2023-12-03'),
(63, 35, 14, '2023-12-03'),
(64, 35, 19, '2023-12-03'),
(65, 35, 15, '2023-12-03');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
