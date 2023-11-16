-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 11, 2023 at 10:43 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `summerhouse`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`) VALUES
(1, 'Coffee Beverages'),
(2, 'Tea Beverages'),
(3, 'Bakery Items'),
(4, 'Sandwiches and Wraps'),
(5, 'Salads'),
(6, 'Smoothies and Fresh Juices'),
(7, 'Specialty Drinks'),
(8, 'Desserts'),
(9, 'Dairy Alternatives'),
(10, 'Merchandise'),
(11, 'Snacks'),
(12, 'Condiments and Extras');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,0) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `customer_name` varchar(255) NOT NULL,
  `payment_type` enum('cash','gcash','bank transfer','card') NOT NULL,
  `amount_tendered` int(11) NOT NULL,
  `change_amount` int(11) NOT NULL,
  `notes` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `total_amount`, `created_at`, `customer_name`, `payment_type`, `amount_tendered`, `change_amount`, `notes`) VALUES
(2, 1, '186', '2023-10-06 19:23:14', 'drgdrg', 'cash', 0, 0, 'drgdrgdrgdrg'),
(3, 1, '372', '2023-10-06 19:29:39', 'John', 'cash', 0, 0, 'Add cream to all'),
(18, 1, '450', '2023-10-07 03:51:40', 'uihjgjghghjghjghjghj', 'cash', 0, 0, 'igh jgh jh ggjh jgh gjh h jggh j'),
(19, 1, '1078', '2023-10-07 04:58:58', 'Prince Aj', 'gcash', 0, 0, 'Pack it all with bubble wrap.'),
(20, 10, '186', '2023-10-08 12:57:27', 'Jhe', 'cash', 0, 0, 'Sample order'),
(21, 10, '377', '2023-10-08 15:50:45', 'John Doe', 'cash', 0, 0, 'Order for pickup.'),
(22, 10, '503', '2023-10-08 15:51:38', 'Johnny', 'cash', 0, 0, 'Deliver to CR.');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `order_detail_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `subtotal` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`order_detail_id`, `order_id`, `product_id`, `quantity`, `subtotal`) VALUES
(1, 2, 4, 1, '60'),
(2, 2, 3, 1, '60'),
(3, 2, 2, 1, '66'),
(4, 3, 4, 2, '120'),
(5, 3, 3, 2, '120'),
(6, 3, 2, 2, '132'),
(47, 18, 5, 2, '130'),
(48, 18, 8, 2, '140'),
(49, 18, 4, 3, '180'),
(50, 19, 5, 1, '65'),
(51, 19, 4, 6, '360'),
(52, 19, 40, 2, '180'),
(53, 19, 45, 4, '188'),
(54, 19, 62, 6, '120'),
(55, 19, 50, 1, '15'),
(56, 19, 51, 2, '50'),
(57, 19, 49, 2, '70'),
(58, 19, 48, 3, '30'),
(59, 20, 2, 1, '66'),
(60, 20, 3, 1, '60'),
(61, 20, 4, 1, '60'),
(62, 21, 5, 1, '65'),
(63, 21, 4, 2, '120'),
(64, 21, 3, 1, '60'),
(65, 21, 2, 2, '132'),
(66, 22, 3, 2, '120'),
(67, 22, 2, 3, '198'),
(68, 22, 4, 2, '120'),
(69, 22, 5, 1, '65');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `category_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `price`, `category_id`, `image`) VALUES
(2, 'Espresso', '70', 1, 'product-images/espresso.webp'),
(3, 'Americano', '60', 1, 'product-images/americano.jpg'),
(4, 'Latte', '60', 1, 'product-images/latte.jpg'),
(5, 'Cappuccino', '65', 1, 'product-images/cappuccino.jpg'),
(6, 'Macchiato', '70', 1, 'product-images/macchiato.jpeg'),
(7, 'Mocha', '65', 1, 'product-images/mocha.jpg'),
(8, 'Flat White', '70', 1, 'product-images/flat-white.webp'),
(9, 'Affogato', '79', 1, 'product-images/affogato.jpg'),
(11, 'Iced Coffee', '50', 1, 'product-images/iced-coffee.jpg'),
(12, 'Hot Tea', '65', 2, 'product-images/hot-tea.jpg'),
(13, 'Iced Tea', '50', 2, 'product-images/iced-tea.jpg'),
(14, 'Chai Latte', '89', 2, 'product-images/chai-latte.webp'),
(15, 'Matcha Latte', '75', 2, 'product-images/matcha-latte.jpg'),
(16, 'Croissants', '45', 3, 'product-images/croissants.webp'),
(18, 'Muffins', '30', 3, 'product-images/muffins.jpg'),
(19, 'Scones', '75', 3, 'product-images/scones.png'),
(20, 'Bagels', '35', 3, 'product-images/bagels.jpg'),
(21, 'Cookies', '25', 3, 'product-images/cookies.jpg'),
(22, 'Brownies', '30', 3, 'product-images/brownies.jpg'),
(23, 'Breakfast Sandwiches', '50', 4, 'product-images/breakfast-sandwiches.jpg'),
(24, 'Paninis', '85', 4, 'product-images/paninis.png'),
(25, 'Grilled Cheese', '55', 4, 'product-images/grilled-cheese.jpg'),
(26, 'Wraps', '70', 4, 'product-images/wraps.jpg'),
(27, 'Fresh Salad', '70', 5, 'product-images/fresh-salad.jpg'),
(28, 'Chicken Caesar Salad', '70', 5, 'product-images/chicken-ceasar-salad.jpg'),
(29, 'Quinoa Salad', '79', 5, 'product-images/quinoa-salad.jpg'),
(30, 'Fruit Smoothies', '85', 6, 'product-images/fruit-smoothies.jpg'),
(31, 'Green Smoothies', '80', 6, 'product-images/green-smoothies.jpeg'),
(32, 'Freshly Squeezed Juices', '95', 6, 'product-images/freshly-squeezed-juices.jpg'),
(33, 'Seasonal Specials', '89', 7, 'product-images/seasonal-specials.webp'),
(34, 'Signature Drinks', '89', 7, 'product-images/signature-drinks.webp'),
(36, 'Cake Slices', '40', 8, 'product-images/cake-slices.jpg'),
(37, 'Pies', '95', 8, 'product-images/pies.jpg'),
(38, 'Tarts', '35', 8, 'product-images/tarts.jpg'),
(39, 'Ice Cream', '60', 8, 'product-images/ice-cream.jpg'),
(40, 'Soy Milk', '90', 9, 'product-images/soy-milk.jpg'),
(41, 'Almond Milk', '75', 9, 'product-images/almond-milk.webp'),
(42, 'Oat Milk', '80', 9, 'product-images/oat-milk.jpg'),
(43, 'Coconut Milk', '40', 9, 'product-images/coconut-milk.webp'),
(44, 'Trail Mix', '30', 11, 'product-images/trail-mix.jpg'),
(45, 'Granola Bars', '47', 11, 'product-images/granola-bars.jpg'),
(46, 'Pretzels', '36', 11, 'product-images/pretzels.jpg'),
(47, 'Chips', '30', 11, 'product-images/chips.jpg'),
(48, 'Sugar Packets', '10', 12, 'product-images/sugar-packets.jpg'),
(49, 'Honey', '35', 12, 'product-images/honey.webp'),
(50, 'Syrups (vanilla, caramel, etc.)', '15', 12, 'product-images/syrups.jpg'),
(51, 'Whipped Cream', '25', 12, 'product-images/whipped-cream.jpg'),
(62, 'Shirt', '400', 10, 'product-images/85dbd24b65a88db051644e3cb3229781.jpg'),
(63, 'Mug', '90', 10, 'product-images/italian_nordic_coffee_cup_set__1665498419_457ff33a_progressive.jpg'),
(79, 'Cold Brew', '65', 1, 'product-images/cold-brew.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `type`, `description`) VALUES
(1, 'owner', 'Admin access'),
(2, 'employee', 'Limited access');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `role_id`, `username`, `password`, `created_at`) VALUES
(1, 1, 'admin', '$2y$10$2RwJ3S/xsCyf/AL3a8EY/e0gqv.sCVHIO602KQW5Fjhv7IXgo5WJC', '2023-10-05 19:20:51'),
(2, 2, 'user', '$2y$10$r5hDoUTbTE9hT1MPRbXn3eyUW0OJfPujiW3b.nAEPVJp6l9WsW7y6', '2023-10-05 19:21:02'),
(3, 2, 'test', '$2y$10$er.24STwB3j0/ugyGHxIL.E5.3DC1doTMkEl/LbxGAGUCCqMBPbwe', '2023-10-05 19:11:09'),
(10, 2, 'jhe', '$2y$10$AW683hBUouEAmND.lDVKG.SnqSmSTRbHNxWTBJPsNbaN9tn6f5iKy', '2023-10-08 12:37:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`order_detail_id`),
  ADD KEY `fk_order_id` (`order_id`),
  ADD KEY `fk_product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `fk_category_id` (`category_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `fk_role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `order_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `fk_order_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_category_id` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_role_id` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
