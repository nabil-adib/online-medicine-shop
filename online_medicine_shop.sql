-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 19, 2026 at 01:50 AM
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
-- Database: `online_medicine_shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `medicine_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `added_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category_type` enum('liquid','solid') NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `category_type`, `created_at`) VALUES
(1, 'Antibiotics', 'solid', '2026-05-16 04:50:48'),
(2, 'Analgesics & Pain Relief', 'solid', '2026-05-16 04:50:48'),
(3, 'Antihistamines', 'liquid', '2026-05-16 04:50:48'),
(4, 'Cough Syrups', 'liquid', '2026-05-16 04:50:48'),
(5, 'Cardiovascular Drugs', 'solid', '2026-05-16 04:50:48'),
(7, 'Diuretics', 'liquid', '2026-05-17 00:53:21'),
(9, 'asd', 'solid', '2026-05-18 00:51:18');

-- --------------------------------------------------------

--
-- Table structure for table `medicines`
--

CREATE TABLE `medicines` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category_id` int(11) NOT NULL,
  `vendor_name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `availability` int(11) DEFAULT 0,
  `description` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicines`
--

INSERT INTO `medicines` (`id`, `name`, `category_id`, `vendor_name`, `price`, `availability`, `description`, `image_path`, `created_at`) VALUES
(1, 'Napa Extend 665mg', 2, 'Beximco Pharmaceuticals Ltd.', 24.00, 494, 'Paracetamol extended release tablet for long-lasting pain and fever relief.', 'public/assets/pictures/napa_extend.jpg', '2026-05-16 05:00:33'),
(2, 'Seclo 20mg', 5, 'Square Pharmaceuticals PLC', 60.00, 987, 'Omeprazole capsule used to treat gastric acidity, heartburn, and GERD.', 'public/assets/pictures/seclo_20.jpg', '2026-05-16 05:00:33'),
(3, 'Zimax 500mg', 1, 'Square Pharmaceuticals PLC', 135.00, 112, 'Azithromycin tablet used for treating bacterial infections of the respiratory tract.', 'public/assets/pictures/zimax_500.jpg', '2026-05-16 05:00:33'),
(4, 'Alatrol Syrup', 3, 'Incepta Pharmaceuticals Ltd.', 35.00, 292, 'Cetirizine hydrochloride syrup for quick relief from seasonal allergies and hives.', 'public/assets/pictures/alatrol_syrup.jpg', '2026-05-16 05:00:33'),
(5, 'Tusca Syrup 100ml', 4, 'SQUARE Pharmaceuticals PLC', 85.00, 77, 'Effective cough suppressant and expectorant syrup for soothing dry coughs.', 'public/assets/pictures/tusca_syrup.jpg', '2026-05-16 05:00:33'),
(6, 'Fenadin 120mg', 3, 'Renata Limited', 90.00, 243, 'Fexofenadine non-drowsy antihistamine for managing allergic rhinitis symptoms.', 'public/assets/pictures/fenadin_120.jpg', '2026-05-16 05:00:33'),
(14, 'Napa 500mg', 2, 'Beximco Pharmaceuticals Ltd.', 8.00, 996, 'Standard paracetamol tablet for fever and mild pain relief.', NULL, '2026-05-19 00:25:13'),
(15, 'Napa Extra 665mg', 2, 'Beximco Pharmaceuticals Ltd.', 12.00, 750, 'Extra strength paracetamol for enhanced pain relief.', NULL, '2026-05-19 00:25:13'),
(16, 'Napa Suppository 125mg', 2, 'Beximco Pharmaceuticals Ltd.', 25.00, 300, 'Paracetamol suppository for children and patients unable to take oral medication.', NULL, '2026-05-19 00:25:13'),
(17, 'Maxpro 20mg', 5, 'Beximco Pharmaceuticals Ltd.', 45.00, 500, 'Esomeprazole for acid reflux and GERD treatment.', NULL, '2026-05-19 00:25:13'),
(18, 'Maxpro 40mg', 5, 'Beximco Pharmaceuticals Ltd.', 65.00, 400, 'Higher strength esomeprazole for severe acid reflux.', NULL, '2026-05-19 00:25:13'),
(19, 'Astafen 10mg', 3, 'Beximco Pharmaceuticals Ltd.', 30.00, 600, 'Fexofenadine for allergy relief.', NULL, '2026-05-19 00:25:13'),
(20, 'Astafen 180mg', 3, 'Beximco Pharmaceuticals Ltd.', 55.00, 450, 'Stronger fexofenadine for severe allergies.', NULL, '2026-05-19 00:25:13'),
(21, 'Napa 500mg', 2, 'Square Pharmaceuticals PLC', 7.50, 1200, 'Square brand paracetamol tablet for fever and pain relief.', NULL, '2026-05-19 00:25:13'),
(22, 'Napa Extra 665mg', 2, 'Square Pharmaceuticals PLC', 11.50, 800, 'Square brand extra strength paracetamol.', NULL, '2026-05-19 00:25:13'),
(23, 'Seclo 10mg', 5, 'Square Pharmaceuticals PLC', 35.00, 600, 'Lower dose omeprazole for mild acidity.', NULL, '2026-05-19 00:25:13'),
(24, 'Seclo 40mg', 5, 'Square Pharmaceuticals PLC', 90.00, 350, 'Higher dose omeprazole for severe GERD.', NULL, '2026-05-19 00:25:13'),
(25, 'Zimax 250mg', 1, 'Square Pharmaceuticals PLC', 75.00, 500, 'Lower dose azithromycin for mild bacterial infections.', NULL, '2026-05-19 00:25:13'),
(26, 'Zimax 200mg/5ml Suspension', 1, 'Square Pharmaceuticals PLC', 65.00, 300, 'Azithromycin suspension for children.', NULL, '2026-05-19 00:25:13'),
(27, 'Zithro 500mg', 1, 'Square Pharmaceuticals PLC', 140.00, 200, 'Alternative brand azithromycin tablet.', NULL, '2026-05-19 00:25:13'),
(28, 'Fexo 120mg', 3, 'Square Pharmaceuticals PLC', 85.00, 400, 'Fexofenadine antihistamine tablet.', NULL, '2026-05-19 00:25:13'),
(29, 'Fexo 180mg', 3, 'Square Pharmaceuticals PLC', 110.00, 350, 'Stronger fexofenadine antihistamine.', NULL, '2026-05-19 00:25:13'),
(30, 'Tuska Syrup 100ml', 4, 'Square Pharmaceuticals PLC', 80.00, 200, 'Alternative spelling cough syrup.', NULL, '2026-05-19 00:25:13'),
(31, 'Tuska Plus Syrup', 4, 'Square Pharmaceuticals PLC', 120.00, 150, 'Cough syrup with additional expectorant.', NULL, '2026-05-19 00:25:13'),
(32, 'Napa 500mg', 2, 'Incepta Pharmaceuticals Ltd.', 7.00, 1500, 'Incepta brand paracetamol tablet.', NULL, '2026-05-19 00:25:13'),
(33, 'Napa Rapid 500mg', 2, 'Incepta Pharmaceuticals Ltd.', 10.00, 900, 'Fast dissolving paracetamol tablet.', NULL, '2026-05-19 00:25:13'),
(34, 'Omeprazole 20mg', 5, 'Incepta Pharmaceuticals Ltd.', 40.00, 700, 'Generic omeprazole for acidity.', NULL, '2026-05-19 00:25:13'),
(35, 'Omeprazole 40mg', 5, 'Incepta Pharmaceuticals Ltd.', 60.00, 500, 'Higher dose generic omeprazole.', NULL, '2026-05-19 00:25:13'),
(36, 'Cetrizine Syrup', 3, 'Incepta Pharmaceuticals Ltd.', 30.00, 400, 'Cetirizine antihistamine syrup for allergies.', NULL, '2026-05-19 00:25:13'),
(37, 'Cetrizine 10mg', 3, 'Incepta Pharmaceuticals Ltd.', 25.00, 800, 'Cetirizine tablet for allergy relief.', NULL, '2026-05-19 00:25:13'),
(38, 'Azithromycin 500mg', 1, 'Incepta Pharmaceuticals Ltd.', 130.00, 300, 'Generic azithromycin tablet.', NULL, '2026-05-19 00:25:13'),
(39, 'Azithromycin 250mg', 1, 'Incepta Pharmaceuticals Ltd.', 70.00, 450, 'Lower dose generic azithromycin.', NULL, '2026-05-19 00:25:13'),
(40, 'Amoxicillin 500mg', 1, 'Incepta Pharmaceuticals Ltd.', 40.00, 600, 'Broad spectrum antibiotic for bacterial infections.', NULL, '2026-05-19 00:25:13'),
(41, 'Amoxicillin 250mg', 1, 'Incepta Pharmaceuticals Ltd.', 25.00, 700, 'Lower dose amoxicillin.', NULL, '2026-05-19 00:25:13'),
(42, 'Cough Relief Syrup', 4, 'Incepta Pharmaceuticals Ltd.', 75.00, 250, 'General purpose cough syrup.', NULL, '2026-05-19 00:25:13'),
(43, 'Dry Cough Syrup', 4, 'Incepta Pharmaceuticals Ltd.', 90.00, 200, 'Cough syrup for dry, irritating coughs.', NULL, '2026-05-19 00:25:13'),
(44, 'Napa 500mg', 2, 'Renata Limited', 7.50, 1100, 'Renata brand paracetamol tablet.', NULL, '2026-05-19 00:25:13'),
(45, 'Napa Extra 665mg', 2, 'Renata Limited', 11.00, 850, 'Renata brand extra strength paracetamol.', NULL, '2026-05-19 00:25:13'),
(46, 'Omeprazole 20mg', 5, 'Renata Limited', 42.00, 550, 'Renata brand omeprazole capsule.', NULL, '2026-05-19 00:25:13'),
(47, 'Omeprazole 40mg', 5, 'Renata Limited', 62.00, 400, 'Renata brand higher dose omeprazole.', NULL, '2026-05-19 00:25:13'),
(48, 'Fexofenadine 120mg', 3, 'Renata Limited', 88.00, 450, 'Generic fexofenadine tablet.', NULL, '2026-05-19 00:25:13'),
(49, 'Fexofenadine 180mg', 3, 'Renata Limited', 115.00, 380, 'Stronger generic fexofenadine.', NULL, '2026-05-19 00:25:13'),
(50, 'Azithromycin 500mg', 1, 'Renata Limited', 132.00, 280, 'Generic azithromycin.', NULL, '2026-05-19 00:25:13'),
(51, 'Cough Syrup DM', 4, 'Renata Limited', 95.00, 180, 'Cough syrup with dextromethorphan.', NULL, '2026-05-19 00:25:13'),
(52, 'Expectorant Syrup', 4, 'Renata Limited', 85.00, 220, 'Cough syrup with guaifenesin expectorant.', NULL, '2026-05-19 00:25:13'),
(53, 'Ciprofloxacin 500mg', 1, 'Beximco Pharmaceuticals Ltd.', 35.00, 500, 'Antibiotic for urinary tract and bacterial infections.', NULL, '2026-05-19 00:25:13'),
(54, 'Ciprofloxacin 250mg', 1, 'Square Pharmaceuticals PLC', 20.00, 600, 'Lower dose ciprofloxacin.', NULL, '2026-05-19 00:25:13'),
(55, 'Doxycycline 100mg', 1, 'Incepta Pharmaceuticals Ltd.', 30.00, 450, 'Antibiotic for respiratory and skin infections.', NULL, '2026-05-19 00:25:13'),
(56, 'Doxycycline 50mg', 1, 'Renata Limited', 18.00, 550, 'Lower dose doxycycline.', NULL, '2026-05-19 00:25:13'),
(57, 'Cefixime 200mg', 1, 'Square Pharmaceuticals PLC', 55.00, 350, 'Third generation cephalosporin antibiotic.', NULL, '2026-05-19 00:25:13'),
(58, 'Cefixime 100mg', 1, 'Beximco Pharmaceuticals Ltd.', 35.00, 400, 'Lower dose cefixime for children.', NULL, '2026-05-19 00:25:13');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `shipping_address` text NOT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending',
  `payment_method` enum('Credit Card','bKash','Nagad','Bank Transfer','Cash on Delivery') NOT NULL,
  `order_date` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `shipping_address`, `status`, `payment_method`, `order_date`) VALUES
(12, 7, 360.00, 'Mirpur 1216', 'rejected', 'Credit Card', '2026-05-18'),
(13, 7, 360.00, 'Mirpur 1216', 'accepted', 'Cash on Delivery', '2026-05-18'),
(14, 7, 120.00, 'Mirpur 1216', 'accepted', 'Credit Card', '2026-05-18'),
(17, 7, 822.00, 'Mirpur 1216', 'accepted', 'Cash on Delivery', '2026-05-19'),
(19, 12, 294.00, 'Mirpur 1', 'accepted', 'bKash', '2026-05-19'),
(20, 7, 135.00, 'Mirpur 1216', 'pending', 'Cash on Delivery', '2026-05-19'),
(21, 7, 140.00, 'Mirpur 1216', 'rejected', 'Cash on Delivery', '2026-05-19'),
(22, 7, 60.00, 'Mirpur 1216', 'accepted', 'Cash on Delivery', '2026-05-19');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `medicine_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `medicine_id`, `quantity`, `unit_price`) VALUES
(47, 12, 6, 1, 90.00),
(48, 12, 3, 2, 135.00),
(49, 13, 6, 4, 90.00),
(50, 14, 2, 2, 60.00),
(54, 17, 6, 1, 90.00),
(55, 17, 3, 4, 135.00),
(56, 17, 2, 2, 60.00),
(57, 17, 1, 3, 24.00),
(61, 19, 5, 1, 85.00),
(62, 19, 2, 1, 60.00),
(63, 19, 4, 1, 35.00),
(64, 19, 1, 1, 24.00),
(65, 19, 6, 1, 90.00),
(66, 20, 3, 1, 135.00),
(67, 21, 4, 4, 35.00),
(68, 22, 2, 1, 60.00);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(100) NOT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `payment_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `amount`, `payment_method`, `transaction_id`, `payment_date`) VALUES
(2, 12, 360.00, 'Credit Card', 'TXN1779049922850', '2026-05-18 02:32:02'),
(3, 13, 360.00, 'Cash on Delivery', 'TXN1779119005872', '2026-05-18 21:43:25'),
(4, 14, 120.00, 'Credit Card', 'TXN1779125074580', '2026-05-18 23:24:34'),
(7, 17, 822.00, 'Cash on Delivery', 'TXN1779146321207', '2026-05-19 05:18:41'),
(9, 19, 294.00, 'bKash', 'TXN1779146589434', '2026-05-19 05:23:09'),
(10, 20, 135.00, 'Cash on Delivery', 'TXN1779147758597', '2026-05-19 05:42:38'),
(11, 21, 140.00, 'Cash on Delivery', 'TXN1779147768867', '2026-05-19 05:42:48'),
(12, 22, 60.00, 'Cash on Delivery', 'TXN1779147776313', '2026-05-19 05:42:56');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','customer','vendor') NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `role`, `profile_picture`, `address`, `phone`, `created_at`) VALUES
(4, 'Admin', 'admin@mail.com', '$2y$10$mNMxN5oWJX9yi8KL5wf2UuWMPVhv2zuF1am2ozlBVeaf53eBrSSO2', 'admin', NULL, NULL, NULL, '2026-05-16 16:16:46'),
(7, 'Nabil Adib', 'nabil@gmail.com', '$2y$10$mDJs2LTlQRRCx/2pVhXG9.tkRlUKqsjuFYSMY7BMCph41XtjlcB7i', 'customer', NULL, 'Mirpur 1216', '017625364781', '2026-05-17 01:52:12'),
(9, 'Nabil Adib', 'nabilvendor111@gmail.com', '$2y$10$wNUJs934wSCpwx8l5tElQOOIhW8Sf4FjqdOb8zv.OiqXbAn8skN0W', 'vendor', 'public/uploads/profile_9_1779126627.jpg', 'banani 112', '01987654321', '2026-05-18 01:34:47'),
(12, 'Maisha Tahseen', 'maisha1@gmail.com', '$2y$10$xEH8miIK6vL6ynY6QBCvbeHGPcy8gJjd14IwYZEJ4bsmQbzfKuWai', 'customer', 'public/uploads/profile_12_1779146963.jpg', 'Mirpur 1', '01798726531', '2026-05-19 05:22:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `medicine_id` (`medicine_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medicines`
--
ALTER TABLE `medicines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `medicine_id` (`medicine_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `medicines`
--
ALTER TABLE `medicines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`);

--
-- Constraints for table `medicines`
--
ALTER TABLE `medicines`
  ADD CONSTRAINT `medicines_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
