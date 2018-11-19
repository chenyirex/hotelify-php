-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2018 at 12:56 AM
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
(1, 'Canada', 'BC', 'Vancouver', '791 W Georgia St', 'V6T 1Z2'),
(2, 'United States', 'CA', 'Irvine', '123 any street', '92602'),
(3, 'Canada', 'BC', 'Vancouver', '5959 Student Union Blvd', 'V6T 1K2'),
(4, 'Canada', 'BC', 'Richmond', '3111 Grant McConachie Way', 'V6C 2T4'),
(6, 'Canada', 'BC', 'Vancouver', '475 Howe Street', 'V6B 2B3'),
(7, 'Canada', 'ON', 'Toronto', '33 Gerrard Street West', 'M5G 1Z4'),
(8, 'Canada', 'BC', 'Vancouver', '900 W Georgia St', 'V6C 2W6'),
(9, 'US', 'WA', 'Seattle', '1112 4th Ave', '98101'),
(10, 'Canada', 'BC', 'Vancouver', '711 W. Broadway @ Heather Street', 'V5Z 3Y2');

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
  `card_holder_name` char(20) NOT NULL,
  `csv` char(5) NOT NULL,
  `expire_date` date NOT NULL,
  `username` char(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `card`
--

INSERT INTO `card` (`card_number`, `card_holder_name`, `csv`, `expire_date`, `username`) VALUES
('1234432112344321', 'Kanglong Qiu', '111', '2022-02-01', 'qkl'),
('2345432123454321', 'Chen Yi', '888', '2022-10-01', 'rex'),
('2345678998765432', 'Haus Tang', '777', '2019-09-01', 'Suiyobi'),
('4563456378900987', 'Yuxiang Huang', '336', '2019-08-01', 'Daniel God'),
('4567765412344321', 'Tang Ao', '666', '2019-07-01', 'rex'),
('5643564356435643', 'Scott Wang', '711', '2019-09-01', 'Scott'),
('7777888899990000', 'Haus Tang', '888', '2020-04-01', 'Suiyobi'),
('8888888888888888', 'Rex Chen', '345', '2019-09-09', 'rex'),
('9911991199119911', 'Scott Wang', '888', '2022-11-01', 'Scott'),
('9999888877776666', 'Haus Tang', '333', '2022-04-01', 'Suiyobi');

-- --------------------------------------------------------

--
-- Table structure for table `coupon`
--

CREATE TABLE `coupon` (
  `id` int(11) NOT NULL,
  `username` char(255) DEFAULT NULL,
  `coupon_type_id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `expire_date` date NOT NULL,
  `is_used` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `coupon`
--

INSERT INTO `coupon` (`id`, `username`, `coupon_type_id`, `hotel_id`, `expire_date`, `is_used`) VALUES
(1, 'rex', 1, 1, '2019-09-09', 1),
(4, 'qkl', 3, 1, '2020-01-01', 0),
(5, 'rex', 3, 2, '2018-11-27', 0),
(6, 'Scott', 3, 1, '2020-01-01', 1),
(7, 'scott', 6, 1, '2018-11-29', 0),
(8, 'scott', 7, 1, '2018-11-29', 0),
(9, 'suiyobi', 7, 1, '2018-11-29', 0),
(10, 'suiyobi', 3, 1, '2018-11-29', 0),
(11, 'rex', 3, 1, '2018-11-29', 0),
(12, 'Daniel God', 3, 1, '2020-01-01', 0),
(13, 'qkl', 2, 7, '2019-01-16', 0),
(14, 'rex', 2, 7, '2019-01-16', 0),
(15, 'Scott', 2, 7, '2019-01-16', 0),
(16, 'suiyobi', 2, 7, '2019-01-16', 0),
(17, 'Daniel God', 2, 7, '2019-01-16', 0);

-- --------------------------------------------------------

--
-- Table structure for table `coupon_type`
--

CREATE TABLE `coupon_type` (
  `id` int(11) NOT NULL,
  `value` int(11) NOT NULL,
  `discount_type` char(255) NOT NULL,
  `points_cost` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `coupon_type`
--

INSERT INTO `coupon_type` (`id`, `value`, `discount_type`, `points_cost`) VALUES
(1, 30, '%', 1500),
(2, 100, '$', 500),
(3, 10, '%', 0),
(6, 50, '$', 300),
(7, 20, '%', 1100);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `first_name` char(20) NOT NULL,
  `last_name` char(20) NOT NULL,
  `username` char(20) NOT NULL,
  `password` char(20) NOT NULL,
  `email` char(20) NOT NULL,
  `phone_number` char(15) NOT NULL,
  `address_id` int(11) DEFAULT NULL,
  `points` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`first_name`, `last_name`, `username`, `password`, `email`, `phone_number`, `address_id`, `points`) VALUES
('Daniel', 'Huang', 'Daniel God', '123', 'fake@gmail.com', '7786810109', 7, 0),
('Kanglong', 'Qiu', 'qkl', 'qkl', 'qkl@gmail.com', '7786810868', 1, 1750),
('rex', 'chen', 'rex', 'rex', 'rex@gmail.com', '7789911125', 1, 1050),
('jingrui', 'wang', 'Scott', '666', 'wjr@gmail.com', '7786814260', 2, 9520),
('ao', 'tang', 'suiyobi', '123', '123@qq.com', '6047158011', 2, 880);

--
-- Triggers `customer`
--
DELIMITER $$
CREATE TRIGGER `New User Coupon event(UBC)` AFTER INSERT ON `customer` FOR EACH ROW INSERT INTO coupon VALUES(null,NEW.username, 3, 1, '2020-01-01',0)
$$
DELIMITER ;

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
(1, 'Walter Gage', 'UBC', 3, 3, 'Walter Gage is known for its positive energy and superb location. Three high-rise towers are conveniently located near the The Nest, bus loop, and many campus recreational facilities.', NULL, '6048221000'),
(2, 'Fairmont', 'Vancouver Airport', 4, 4, 'Set within the Vancouver International Airport, this upscale hotel is 1 km from YVR-Airport Station train station and 9 km from VanDusen Botanical Garden.', NULL, '6042075200'),
(4, 'Exchange Hotel', 'Vancouver', 4, 6, 'Situated within 200 metres of Waterfront Centre Mall Vancouver and Vancouver Lookout at Harbour Centre, EXchange Hotel Vancouver features rooms with air conditioning. Free WiFi is available.', NULL, '6045634693'),
(5, 'Four Seasons', 'Vancouver', 5, 1, 'Modern rooms & plush suites with skyline views, plus an indoor/outdoor pool & a seafood restaurant.', NULL, '6046899333'),
(6, 'Chelsea Hotel', 'Toronto', 3, 7, 'World class attractions, beaches and shopping hubs are only minutes away and the hotel is located two blocks from the Broadway-City Hall Skytrain Station which offers easy and convenient access to downtown Vancouver and Vancouver International Airport (YV', NULL, '4165951975'),
(7, 'Fairmont', 'Vancouver Downtown', 4, 8, 'A 2-minute walk from the Vancouver Art Gallery and a 4-minute walk from Vancouver City Centre Station, this elegant hotel dating from 1939 is a 10-minute walk from the Canada Place convention centre.', NULL, '6046843131'),
(8, 'W', 'Seattle', 4, 9, 'Experience all the excitement of Seattle city centre at this centrally located hotel, only seconds from major area attractions, and offering unrivaled personalized services along with first-class amenities and luxurious accommodations.', NULL, '2062646000'),
(9, 'Holiday Inn', 'Broadway', 3, 10, 'A 6-minute walk from the Broadway-City Hall Skytrain station (which offers direct access to downtown Vancouver), this contemporary hotel is also 2.3 km from Granville Island.', NULL, '6048790511');

-- --------------------------------------------------------

--
-- Table structure for table `hotel_tag`
--

CREATE TABLE `hotel_tag` (
  `hotel_id` int(11) NOT NULL,
  `tag_name` char(20) NOT NULL,
  `popularity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hotel_tag`
--

INSERT INTO `hotel_tag` (`hotel_id`, `tag_name`, `popularity`) VALUES
(1, 'cheap', 4),
(1, 'clean', 7),
(1, 'quiet', 1),
(2, 'Clean', 2),
(2, 'Convenient', 1),
(2, 'good service', 1),
(4, 'clean', 2),
(4, 'noisy', 1),
(5, 'Clean', 3),
(5, 'Close to Downtown', 3),
(5, 'Noisy', 1),
(6, 'Close to Downtown', 2),
(6, 'Noisy', 1),
(7, 'Clean', 1),
(7, 'Close to Downtown', 2),
(8, 'Clean', 1),
(8, 'Close to downtown', 3),
(8, 'Free wifi', 1),
(9, 'Clean', 2),
(9, 'Good service', 1);

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

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`id`, `amount`, `coupon_id`, `card_number`) VALUES
(7, 500, NULL, '8888888888888888'),
(18, 350, 1, '8888888888888888'),
(19, 150, NULL, '1234432112344321'),
(20, 700, NULL, '1234432112344321'),
(21, 6000, NULL, '5643564356435643'),
(22, 270, 6, '5643564356435643'),
(23, 380, NULL, '2345678998765432'),
(24, 250, NULL, '1234432112344321'),
(25, 100, NULL, '1234432112344321'),
(26, 300, NULL, '1234432112344321'),
(27, 150, NULL, '1234432112344321'),
(28, 300, NULL, '4567765412344321'),
(29, 400, NULL, '4567765412344321'),
(30, 150, NULL, '5643564356435643'),
(31, 800, NULL, '9911991199119911'),
(32, 1800, NULL, '9911991199119911'),
(33, 500, NULL, '5643564356435643'),
(34, 500, NULL, '7777888899990000');

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
(4, 'rex', 7),
(14, 'rex', 18),
(17, 'suiyobi', NULL),
(18, 'suiyobi', NULL),
(19, 'qkl', 25),
(20, 'suiyobi', NULL),
(21, 'suiyobi', NULL),
(22, 'suiyobi', 23),
(23, 'suiyobi', NULL),
(24, 'qkl', 19),
(25, 'qkl', 20),
(26, 'Scott', 21),
(27, 'Scott', 22),
(28, 'qkl', NULL),
(29, 'qkl', NULL),
(30, 'rex', NULL),
(31, 'qkl', NULL),
(32, 'qkl', NULL),
(33, 'rex', NULL),
(34, 'qkl', 24),
(35, 'qkl', NULL),
(36, 'qkl', 26),
(37, 'qkl', 27),
(38, 'rex', 28),
(39, 'rex', 29),
(40, 'Scott', 30),
(41, 'Scott', 31),
(42, 'Scott', 32),
(43, 'Scott', 33),
(44, 'Suiyobi', 34),
(45, 'qkl', NULL);

-- --------------------------------------------------------

--
-- Stand-in structure for view `reservations_per_hotel`
-- (See below for the actual view)
--
CREATE TABLE `reservations_per_hotel` (
`hotel_id` int(11)
,`result` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `reservation_hotel`
-- (See below for the actual view)
--
CREATE TABLE `reservation_hotel` (
`reservation_id` int(11)
,`hotel_id` int(11)
);

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
(14, 19, '2018-11-07', '2018-11-22'),
(14, 20, '2018-11-07', '2018-11-22'),
(14, 21, '2018-11-07', '2018-11-22'),
(17, 41, '2018-11-23', '2018-11-24'),
(18, 19, '2018-09-18', '2018-09-19'),
(19, 19, '2018-08-07', '2018-08-08'),
(20, 35, '2017-09-18', '2017-09-19'),
(21, 53, '2016-11-07', '2016-11-22'),
(22, 55, '2015-01-01', '2015-01-03'),
(23, 60, '2015-05-01', '2015-06-01'),
(24, 46, '2018-11-27', '2018-11-28'),
(25, 42, '2018-11-02', '2018-11-03'),
(26, 65, '2019-01-03', '2019-01-09'),
(26, 66, '2019-01-03', '2019-01-09'),
(27, 22, '2018-11-24', '2018-11-25'),
(28, 67, '2018-11-24', '2018-11-25'),
(29, 30, '2018-11-24', '2018-11-25'),
(30, 25, '2018-11-24', '2018-11-25'),
(31, 20, '2018-11-23', '2018-11-24'),
(32, 23, '2018-11-21', '2018-11-22'),
(32, 26, '2018-11-21', '2018-11-22'),
(32, 31, '2018-11-21', '2018-11-22'),
(33, 64, '2018-11-29', '2018-11-30'),
(34, 72, '2018-11-23', '2018-11-24'),
(35, 32, '2018-11-01', '2018-11-02'),
(36, 37, '2018-11-07', '2018-11-08'),
(37, 53, '2018-11-06', '2018-11-07'),
(38, 37, '2018-11-08', '2018-11-09'),
(39, 59, '2018-10-31', '2018-11-01'),
(40, 53, '2018-11-10', '2018-11-11'),
(41, 59, '2018-11-13', '2018-11-15'),
(42, 77, '2018-11-13', '2018-11-15'),
(42, 82, '2018-11-13', '2018-11-15'),
(43, 73, '2018-11-13', '2018-11-15'),
(44, 82, '2018-11-15', '2018-11-16'),
(45, 83, '2018-11-15', '2018-11-16');

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

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`id`, `hotel_id`, `username`, `rating`, `comment`) VALUES
(1, 6, 'qkl', 5, 'Very good! The hotel is close to downtown, and the room is clean!'),
(2, 6, 'qkl', 3, 'The hotel is noisy and there are too many people.'),
(3, 5, 'suiyobi', 5, 'Tremendous! Close to Downtown'),
(4, 5, 'suiyobi', 4, 'Good, but too expensive.'),
(5, 5, 'suiyobi', 5, 'Good and worth it!'),
(6, 1, 'qkl', 4, 'Clean and close to UBC. Good view!'),
(7, 2, 'qkl', 5, 'Close to the airport, you can see the plane.'),
(8, 1, 'qkl', 4, 'Clean and quiet.'),
(9, 4, 'qkl', 4, 'Close to downtown'),
(10, 2, 'rex', 5, 'Nice room and good service!'),
(11, 7, 'rex', 5, 'Good location, close to tourist attractions.'),
(12, 4, 'Scott', 5, 'Good service and delicious breakfast.'),
(13, 7, 'Scott', 4, 'Fantastic service and even a gift for our honeymoon'),
(14, 9, 'Scott', 4, 'Close to skytrain station.'),
(15, 9, 'Scott', 5, 'My stay was excellent from clean room to staff.'),
(16, 8, 'Scott', 5, 'The location is convenient to downtown. There are many restaurants within walking distance.'),
(17, 8, 'Suiyobi', 5, 'Super friendly staff, beautiful and clean room and perfect location in downtown Seattle'),
(18, 8, 'Suiyobi', 4, 'Nothing special, rooms are average.');

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
(40, 2, 35),
(41, 6, 41),
(42, 6, 41),
(43, 6, 41),
(44, 6, 41),
(45, 6, 41),
(46, 6, 42),
(47, 6, 42),
(48, 6, 42),
(49, 6, 42),
(50, 6, 42),
(51, 6, 43),
(52, 6, 43),
(53, 4, 44),
(54, 4, 44),
(55, 5, 45),
(59, 7, 48),
(60, 7, 48),
(61, 7, 48),
(62, 7, 48),
(63, 7, 48),
(64, 5, 49),
(65, 8, 51),
(66, 8, 51),
(67, 8, 51),
(68, 8, 51),
(69, 8, 51),
(70, 9, 52),
(71, 9, 52),
(72, 9, 53),
(73, 9, 53),
(74, 9, 53),
(75, 9, 53),
(76, 9, 53),
(77, 8, 54),
(78, 8, 54),
(79, 8, 54),
(80, 8, 54),
(81, 8, 54),
(82, 8, 55),
(83, 8, 55),
(84, 8, 55),
(85, 8, 55),
(86, 8, 55),
(87, 7, 56),
(88, 7, 56),
(89, 7, 56),
(90, 7, 56),
(91, 7, 56),
(92, 7, 56),
(93, 7, 56),
(94, 7, 56),
(95, 7, 56),
(96, 7, 56);

-- --------------------------------------------------------

--
-- Stand-in structure for view `rooms_per_reservation`
-- (See below for the actual view)
--
CREATE TABLE `rooms_per_reservation` (
`reservation_id` int(11)
,`numberOfRooms` bigint(21)
);

-- --------------------------------------------------------

--
-- Table structure for table `room_type`
--

CREATE TABLE `room_type` (
  `id` int(11) NOT NULL,
  `type_name` char(50) NOT NULL,
  `occupancy` int(11) NOT NULL,
  `description` char(255) DEFAULT NULL,
  `price` int(11) NOT NULL,
  `total_slots` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `room_type`
--

INSERT INTO `room_type` (`id`, `type_name`, `occupancy`, `description`, `price`, `total_slots`) VALUES
(35, 'Regular Double Bed Room', 2, 'this room is for rex', 100, 2),
(36, 'Regular Single Bed Room', 2, 'this room is for haus', 100, 2),
(37, 'Standard Queen Room', 3, 'this room is for scott', 300, 3),
(38, 'Standard King Room', 5, 'this room is for kanglong', 300, 3),
(39, 'Deluxe President Suite', 5, 'For Hotelify developers only', 1000, 1),
(40, 'Deluxe King Room', 2, 'Deluxe King', 800, 2),
(41, 'Deluxe Queen Room', 2, 'Deluxe Queen', 700, 2),
(42, 'Standard Queen Room', 2, 'Standard Queen', 150, 5),
(43, 'Standard Double Room', 4, 'Standard double', 300, 2),
(44, 'Deluxe Queen Room', 2, 'Great views', 150, 2),
(45, 'Deluxe Queen Room', 2, 'Great views', 190, 1),
(48, 'Standard Queen Room', 2, 'Standard Queen', 400, 5),
(49, 'Deluxe King Room', 2, 'Room with great city view', 700, 1),
(50, 'Regular Double Bed Room', 2, 'Extra beds available', 400, 0),
(51, 'City View King Room', 2, 'Luxury room with great city view of Downtown Seattle', 500, 5),
(52, 'City View Queen Room', 2, 'Big room with fantastic view', 400, 2),
(53, 'City View Single Room', 1, 'One-bed room with city view', 250, 5),
(54, 'Cozy Room', 2, 'One King Bed', 400, 5),
(55, 'Wonderful Room', 2, 'One king bed with view', 500, 5),
(56, 'Standard King Room', 2, 'Standard King', 450, 10);

-- --------------------------------------------------------

--
-- Structure for view `reservations_per_hotel`
--
DROP TABLE IF EXISTS `reservations_per_hotel`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `reservations_per_hotel`  AS  select `h`.`id` AS `hotel_id`,count(distinct `rv`.`reservation_id`) AS `result` from ((`reservation_room` `rv` join `room` `r`) join `hotel` `h`) where ((`rv`.`room_id` = `r`.`id`) and (`r`.`hotel_id` = `h`.`id`)) group by `h`.`id` ;

-- --------------------------------------------------------

--
-- Structure for view `reservation_hotel`
--
DROP TABLE IF EXISTS `reservation_hotel`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `reservation_hotel`  AS  select `rv`.`reservation_id` AS `reservation_id`,`h`.`id` AS `hotel_id` from ((`reservation_room` `rv` join `room` `r`) join `hotel` `h`) where ((`rv`.`room_id` = `r`.`id`) and (`r`.`hotel_id` = `h`.`id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `rooms_per_reservation`
--
DROP TABLE IF EXISTS `rooms_per_reservation`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `rooms_per_reservation`  AS  select `reservation_room`.`reservation_id` AS `reservation_id`,count(0) AS `numberOfRooms` from `reservation_room` group by `reservation_room`.`reservation_id` ;

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
  ADD KEY `type_id` (`coupon_type_id`),
  ADD KEY `hotel_id` (`hotel_id`);

--
-- Indexes for table `coupon_type`
--
ALTER TABLE `coupon_type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `coupon`
--
ALTER TABLE `coupon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `coupon_type`
--
ALTER TABLE `coupon_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `hotel`
--
ALTER TABLE `hotel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `room_type`
--
ALTER TABLE `room_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `card`
--
ALTER TABLE `card`
  ADD CONSTRAINT `card_ibfk_1` FOREIGN KEY (`username`) REFERENCES `customer` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `coupon`
--
ALTER TABLE `coupon`
  ADD CONSTRAINT `coupon_ibfk_1` FOREIGN KEY (`username`) REFERENCES `customer` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `coupon_ibfk_2` FOREIGN KEY (`coupon_type_id`) REFERENCES `coupon_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `coupon_ibfk_3` FOREIGN KEY (`hotel_id`) REFERENCES `hotel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
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
  ADD CONSTRAINT `payment_ibfk_2` FOREIGN KEY (`card_number`) REFERENCES `card` (`card_number`);

--
-- Constraints for table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`username`) REFERENCES `customer` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
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
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`username`) REFERENCES `customer` (`username`) ON UPDATE CASCADE,
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
