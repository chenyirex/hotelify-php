-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 14, 2018 at 07:45 AM
-- Server version: 10.1.36-MariaDB
-- PHP Version: 7.2.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hotelify`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `id` int(11) NOT NULL,
  `country` char(20) NOT NULL,
  `province` char(20) NOT NULL,
  `city` char(20) NOT NULL,
  `street` char(255) NOT NULL,
  `postal_code` char(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`id`, `country`, `province`, `city`, `street`, `postal_code`) VALUES
(1, 'Canada', 'British Columbia', 'Vancouver', '791 W Georgia St', 'V6C 2T4'),
(2, 'United States', 'California', 'Irvine', '123 any street', '92602'),
(3, 'Canada', 'British Columbia', 'Vancouver', '5959 Student Union Blvd', 'V6T 1K2'),
(4, 'Canada', 'British Columbia', 'Richmond', '3111 Grant McConachie Way', 'V6C 2T4'),
(5, 'China', 'ChongQing', 'ChongQing', 'Yuzhou road', '400039'),
(6, 'Canada', 'BC', 'Vancouver', 'downtown', 'V6T1Z2');

-- --------------------------------------------------------

--
-- Table structure for table `administrator`
--

CREATE TABLE `administrator` (
  `username` char(20) NOT NULL,
  `password` char(20) NOT NULL,
  `first_name` char(20) NOT NULL,
  `last_name` char(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `administrator`
--

INSERT INTO `administrator` (`username`, `password`, `first_name`, `last_name`) VALUES
('admin', 'admin', 'Tor', 'Guy');

-- --------------------------------------------------------

--
-- Table structure for table `card`
--

CREATE TABLE `card` (
  `card_number` char(20) NOT NULL,
  `card_holder_name` char(10) NOT NULL,
  `csv` char(5) NOT NULL,
  `expire_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `username` char(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `coupon`
--

CREATE TABLE `coupon` (
  `id` int(11) NOT NULL,
  `username` char(255) DEFAULT NULL,
  `type_id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `expire_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_type`
--

CREATE TABLE `coupon_type` (
  `id` int(11) NOT NULL,
  `value` int(11) NOT NULL,
  `discount_type` char(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `first_name` char(20) NOT NULL,
  `last_name` char(20) NOT NULL,
  `username` char(20) NOT NULL,
  `password` char(20) NOT NULL,
  `email` char(20) NOT NULL,
  `phone` char(15) NOT NULL,
  `address_id` int(11) DEFAULT NULL,
  `points` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`first_name`, `last_name`, `username`, `password`, `email`, `phone`, `address_id`, `points`) VALUES
('rex', 'chen', 'rex', 'rex', 'rex@gmail.com', '7789919999', 1, 0),
('ao', 'tang', 'suiyobi', '123', '123', '', 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `hotel`
--

CREATE TABLE `hotel` (
  `id` int(11) NOT NULL,
  `brand_name` char(30) NOT NULL,
  `branch_name` char(30) DEFAULT NULL,
  `property_class` int(11) DEFAULT NULL,
  `address_id` int(11) NOT NULL,
  `description` char(255) DEFAULT NULL,
  `overall_rating` char(50) DEFAULT NULL,
  `phone_number` char(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hotel`
--

INSERT INTO `hotel` (`id`, `brand_name`, `branch_name`, `property_class`, `address_id`, `description`, `overall_rating`, `phone_number`) VALUES
(1, 'Walter Gage', 'UBC', 3, 3, 'Walter Gage is known for its positive energy and superb location. Three high-rise towers are conveniently located near the The Nest, bus loop, and many campus recreational facilities.', 'good', '6048221020'),
(2, 'Fairmont', 'Vancouver Airport', 4, 4, 'cheapst hotel, good hotel', 'Excellent', '8008208820'),
(3, 'Deluxe Seven Day', 'sevenify', 1, 5, 'not cheap hotel, good hotel', NULL, '13308002132'),
(4, 'Exchange Hotel', '', 4, 6, 'Situated within 200 metres of Waterfront Centre Mall Vancouver and Vancouver Lookout at Harbour Centre, EXchange Hotel Vancouver features rooms with air conditioning. Free WiFi is available.', NULL, '6047190900'),
(5, 'Four Seasons', 'Vancouver', 5, 1, 'Modern rooms & plush suites with skyline views, plus an indoor/outdoor pool & a seafood restaurant.', 'very good', '6046899333');

-- --------------------------------------------------------

--
-- Table structure for table `hotel_tag`
--

CREATE TABLE `hotel_tag` (
  `hotel_id` int(11) NOT NULL,
  `tag_name` char(20) NOT NULL,
  `popularity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `coupon_id` int(11) DEFAULT NULL,
  `card_number` char(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

CREATE TABLE `reservation` (
  `id` int(11) NOT NULL,
  `username` char(20) NOT NULL,
  `payment_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reservation`
--

INSERT INTO `reservation` (`id`, `username`, `payment_id`) VALUES
(4, 'rex', NULL),
(14, 'rex', NULL),
(15, 'rex', NULL),
(16, 'rex', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reservation_room`
--

CREATE TABLE `reservation_room` (
  `reservation_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `checkin_date` date NOT NULL,
  `checkout_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reservation_room`
--

INSERT INTO `reservation_room` (`reservation_id`, `room_id`, `checkin_date`, `checkout_date`) VALUES
(4, 19, '2018-11-12', '2018-12-12'),
(4, 29, '2018-11-12', '2018-12-12'),
(14, 19, '2018-12-21', '2019-01-12'),
(15, 21, '2018-11-21', '2019-11-25'),
(16, 22, '2018-11-21', '2019-11-25');

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `username` char(20) NOT NULL,
  `rating` double NOT NULL,
  `comment` char(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `room_type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`id`, `hotel_id`, `room_type_id`) VALUES
(19, 1, 35),
(20, 1, 36),
(21, 1, 36),
(22, 1, 37),
(23, 1, 37),
(24, 1, 37),
(25, 1, 38),
(26, 1, 38),
(27, 1, 38),
(28, 1, 38),
(29, 1, 39),
(30, 1, 39),
(31, 1, 39),
(32, 1, 39),
(33, 1, 39),
(34, 1, 40),
(35, 2, 40),
(36, 2, 39),
(37, 2, 38),
(38, 2, 37),
(39, 2, 36),
(40, 2, 35);

-- --------------------------------------------------------

--
-- Table structure for table `room_type`
--

CREATE TABLE `room_type` (
  `id` int(11) NOT NULL,
  `type_name` char(20) NOT NULL,
  `occupancy` int(11) NOT NULL,
  `description` char(255) DEFAULT NULL,
  `price` int(11) NOT NULL,
  `total_slots` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `room_type`
--

INSERT INTO `room_type` (`id`, `type_name`, `occupancy`, `description`, `price`, `total_slots`) VALUES
(35, 'rex room', 3, 'this room is for rex', 100, 1),
(36, 'haus room', 3, 'this room is for haus', 100, 2),
(37, 'scott room', 3, 'this room is for scott', 100, 3),
(38, 'kanglong room', 3, 'this room is for kanglong', 100, 4),
(39, 'general room', 2, 'this room for general use', 100, 5),
(40, 'emergency room', 2, 'this room for emergency use', 100, 1);

-- --------------------------------------------------------

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `administrator`
--
ALTER TABLE `administrator`
  ADD PRIMARY KEY (`username`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `card`
--
ALTER TABLE `card`
  ADD PRIMARY KEY (`card_number`),
  ADD UNIQUE KEY `card_number` (`card_number`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `coupon`
--
ALTER TABLE `coupon`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `username` (`username`),
  ADD KEY `type_id` (`type_id`),
  ADD KEY `hotel_id` (`hotel_id`);

--
-- Indexes for table `coupon_type`
--
ALTER TABLE `coupon_type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`username`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `address_foreign_key` (`address_id`);

--
-- Indexes for table `hotel`
--
ALTER TABLE `hotel`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `address_id` (`address_id`);

--
-- Indexes for table `hotel_tag`
--
ALTER TABLE `hotel_tag`
  ADD PRIMARY KEY (`hotel_id`,`tag_name`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `coupon_id` (`coupon_id`),
  ADD KEY `card_number` (`card_number`);

--
-- Indexes for table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `username` (`username`),
  ADD KEY `payment_id` (`payment_id`);

--
-- Indexes for table `reservation_room`
--
ALTER TABLE `reservation_room`
  ADD PRIMARY KEY (`reservation_id`,`room_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `username` (`username`),
  ADD KEY `hotel_id` (`hotel_id`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotel_id` (`hotel_id`),
  ADD KEY `room_type_id` (`room_type_id`) USING BTREE;

--
-- Indexes for table `room_type`
--
ALTER TABLE `room_type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);


--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `coupon`
--
ALTER TABLE `coupon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupon_type`
--
ALTER TABLE `coupon_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hotel`
--
ALTER TABLE `hotel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `room_type`
--
ALTER TABLE `room_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `card`
--
ALTER TABLE `card`
  ADD CONSTRAINT `card_ibfk_1` FOREIGN KEY (`username`) REFERENCES `customers` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `coupon`
--
ALTER TABLE `coupon`
  ADD CONSTRAINT `coupon_ibfk_1` FOREIGN KEY (`username`) REFERENCES `customers` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `coupon_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `coupon_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `coupon_ibfk_3` FOREIGN KEY (`hotel_id`) REFERENCES `hotel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `address_foreign_key` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `hotel`
--
ALTER TABLE `hotel`
  ADD CONSTRAINT `hotel_ibfk_1` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `hotel_tag`
--
ALTER TABLE `hotel_tag`
  ADD CONSTRAINT `hotel_tag_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`coupon_id`) REFERENCES `coupon` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `payment_ibfk_2` FOREIGN KEY (`card_number`) REFERENCES `card` (`card_number`) ON UPDATE CASCADE;

--
-- Constraints for table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`username`) REFERENCES `customers` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reservation_ibfk_2` FOREIGN KEY (`payment_id`) REFERENCES `payment` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `reservation_room`
--
ALTER TABLE `reservation_room`
  ADD CONSTRAINT `reservation_id` FOREIGN KEY (`reservation_id`) REFERENCES `reservation` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `room_id` FOREIGN KEY (`room_id`) REFERENCES `room` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`username`) REFERENCES `customers` (`username`) ON UPDATE CASCADE,
  ADD CONSTRAINT `review_ibfk_2` FOREIGN KEY (`hotel_id`) REFERENCES `hotel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `room`
--
ALTER TABLE `room`
  ADD CONSTRAINT `room_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `room_ibfk_2` FOREIGN KEY (`room_type_id`) REFERENCES `room_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
