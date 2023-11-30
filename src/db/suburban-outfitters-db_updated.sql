-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 30, 2023 at 10:24 PM
-- Server version: 8.0.31
-- PHP Version: 8.0.26

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
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `first_name`, `last_name`, `email`, `password`, `phone`, `shipping_address`, `billing_address`) VALUES
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
  PRIMARY KEY (`inventory_id`),
  KEY `product_id` (`product_id`),
  KEY `vendor_id` (`vendor_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`inventory_id`, `product_id`, `vendor_id`, `quantity`, `inventory_date`) VALUES
(1, 1, 9, 13, '2023-11-08'),
(2, 2, 9, 190, '2023-11-10'),
(3, 3, 9, 54, '2023-03-10'),
(4, 4, 9, 23, '2023-01-20'),
(5, 5, 9, 89, '2023-02-28'),
(6, 6, 9, 15, '2023-03-05'),
(7, 7, 9, 45, '2023-01-12'),
(8, 8, 9, 67, '2023-02-20'),
(9, 9, 9, 33, '2023-03-15'),
(10, 10, 9, 80, '2023-01-25'),
(11, 11, 9, 19, '2023-02-22'),
(12, 12, 9, 62, '2023-03-20'),
(13, 13, 9, 41, '2023-01-30'),
(14, 14, 9, 72, '2023-02-14'),
(15, 15, 9, 58, '2023-03-03'),
(16, 16, 9, 26, '2023-01-18'),
(17, 17, 9, 93, '2023-02-22'),
(18, 18, 9, 37, '2023-03-11'),
(19, 19, 9, 21, '2023-01-08'),
(20, 20, 9, 84, '2023-02-27');

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
  PRIMARY KEY (`line_id`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `admin_id` int NOT NULL,
  `vendor_id` int NOT NULL,
  `product_name` varchar(150) NOT NULL,
  `price` double(10,2) NOT NULL,
  `category` varchar(150) NOT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `admin_id`, `vendor_id`, `product_name`, `price`, `category`) VALUES
(3, 1, 9, 'NYCITY Joggers', 100.00, 'Men'),
(4, 1, 5, 'SubOut Tee', 60.00, 'Men'),
(5, 1, 6, 'SUBU Hat', 30.00, 'Headwear'),
(6, 1, 4, '1992D SubUR Shirt', 130.00, 'Men'),
(7, 1, 4, 'GTAVII Jeans', 140.00, 'Men'),
(8, 1, 9, 'MotorXD Jacket', 150.00, 'Men'),
(9, 1, 2, 'SUBU Beanie', 30.00, 'Headwear'),
(10, 1, 5, 'SUUB Tee', 50.00, 'Women'),
(11, 1, 2, 'Leafy SUBB Dress', 140.00, 'Women'),
(12, 1, 9, '$SUBURB Skirt', 90.00, 'Women'),
(13, 1, 6, 'SU=BB Jeans', 140.00, 'Women'),
(14, 1, 4, 'BANNII SUBBUR Long Sleeve', 130.00, 'Women'),
(15, 1, 2, 'Royal SUBR Shirt', 220.00, 'Women'),
(16, 1, 8, 'BurnOUT subURB Hoodie', 120.00, 'Women'),
(17, 1, 4, 'SUBIB Beanie', 20.00, 'Women'),
(18, 1, 4, '97 OUTFITTERS T-Shirt', 70.00, 'Women'),
(19, 1, 0, '99-99 T-Shirt', 60.00, 'Women'),
(20, 1, 9, 'SO Tee Apple90', 50.00, 'Women'),
(1, 1, 6, 'DefaultMode Hoodie', 200.00, 'Men'),
(2, 1, 2, 'SUBBUR 939 T-Shirt', 60.00, 'Men');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
