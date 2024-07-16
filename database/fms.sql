-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 22, 2024 at 02:53 PM
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
-- Database: `fms`
--

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact` int(10) NOT NULL,
  `email` varchar(100) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = active , 0 = disabled'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `contact`, `email`, `active`) VALUES
('12ACD_07', 'Academic Block', 2147483647, 'academic@gmail.com', 1),
('12ADM_09', 'Administration', 1234567897, 'administration@gmail.com', 1),
('12CSE_11', 'Computer Science Dept', 2147483647, 'cse@gmail.com', 1),
('12ECE_02', 'Electrical Dept', 2147483647, 'ece@gmail.com', 1),
('12OFF_01', 'Office', 2147483647, 'office@gmail.com', 1),
('202cdc109', 'CDC', 1324567674, 'second.shrunited0702@gmail.com', 1),
('22CE_04', 'Civil Engineering', 1234567897, 'civil@civilDept', 0);

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` varchar(30) NOT NULL,
  `file_name` varchar(200) NOT NULL,
  `creator_id` varchar(20) DEFAULT NULL,
  `file_desc` varchar(500) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = active , 0 = disabled'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`id`, `file_name`, `creator_id`, `file_desc`, `date_created`, `active`) VALUES
('12cgf545647', 'sample file', '2020adm1058', 'error_log(print_r($_POST, true));', '2024-02-28 20:03:46', 1),
('12fg567647', 'Raman Hostel', '2020acd1058', 'Raman Hostel\r\n> Raman Hostel\r\n> Raman Hostel\r\n> Raman Hostel', '2024-03-01 09:30:06', 0),
('12new545690', 'file to admin', '2020acd1058', '> note1\r\n> note2\r\n> note3\r\n> note4', '2024-03-13 16:20:40', 1),
('12new545691', 'file to admin from apoorva', '22CEE_04', '> note from apoorva\r\n> note from apoorva\r\n> note from apoorva\r\n> note from apoorva\r\n> note from apoorva', '2024-03-13 19:33:30', 1),
('145gf545647', 'attendence notice', '2020acd1058', '> We need girlfriends\r\n> Bas ladki honi chchiye', '2024-03-02 12:37:04', 1),
('20fhdg283', 'CDC report', '2020csc1058', 'dummmy', '2024-02-28 19:49:17', 1),
('f108_cdc10798', 'Placement oppurtunities', '2020adm1058', 'Lorem ipsum dolor sit amet, at a sint. Voluptatibus, iure beatae aliquam illum animi reprehenderit voluptatum necessitatibus aperiam, veritatis a, ea fuga dolorum consequuntur.', '2024-02-25 18:46:20', 1),
('f168_dsw12798', 'Proposal for study tables', '2020adm1058', 'Lorem ipsum dolor sit amet, at a sint. Voluptatibus, iure beatae aliquam illum animi reprehenderit voluptatum necessitatibus aperiam, veritatis a, ea fuga dolorum consequuntur.', '2024-02-20 13:32:12', 1),
('newFile01', 'newFile01', '22CEE_04', 'newFile01', '2024-03-16 00:00:57', 1),
('NewFile02', 'NewFile02', '22CEE_04', 'NewFile02', '2024-03-16 00:01:10', 1);

-- --------------------------------------------------------

--
-- Table structure for table `new_user`
--

CREATE TABLE `new_user` (
  `user_id` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `new_user`
--

INSERT INTO `new_user` (`user_id`) VALUES
('22Sinesh23');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset`
--

CREATE TABLE `password_reset` (
  `email` varchar(250) NOT NULL,
  `otp` int(6) NOT NULL,
  `expiration_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `reference_number` varchar(30) NOT NULL,
  `sender_id` varchar(20) DEFAULT NULL,
  `recipient_id` varchar(20) DEFAULT NULL,
  `file_id` varchar(30) NOT NULL,
  `sender_notes` varchar(500) NOT NULL,
  `receiver_notes` varchar(500) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = sent, 2 = accepted',
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `receiver_timestamp` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`reference_number`, `sender_id`, `recipient_id`, `file_id`, `sender_notes`, `receiver_notes`, `status`, `date_created`, `receiver_timestamp`) VALUES
('1ZXwS4LEefcTiho', '2020acd1058', '2020adm1058', '145gf545647', '> attendence notice file sent to admin', 'file accetted <<<<<using new queries>>>>>>', 2, '2024-03-13 22:51:47', '2024-03-15 22:14:26'),
('202406231415', '2020adm1058', '2020acd1058', 'f108_cdc10798', 'Lorem ipsum dolor sit amet, at a sint. Voluptatibus, iure beatae aliquam illum animi reprehenderit voluptatum necessitatibus aperiam, veritatis a, ea fuga dolorum consequuntur.', 'Lorem ipsum dolor sit amet, at a sint. Voluptatibus, iure beatae aliquam illum animi reprehenderit voluptatum necessitatibus aperiam, veritatis a, ea fuga dolorum consequuntur.', 2, '2024-02-20 16:15:46', '2024-03-09 23:14:17'),
('202406231416', '2020acd1058', '2020ece1058', 'f108_cdc10798', 'sender notes here', 'reciever notes heere', 2, '2024-03-09 10:36:31', '2024-03-10 10:36:31'),
('202406234245', '2020ece1058', '2020acd1058', 'f168_dsw12798', 'Lorem ipsum dolor sit amet, at a sint. Voluptatibus, iure beatae aliquam illum animi reprehenderit voluptatum necessitatibus aperiam, veritatis a, ea fuga dolorum consequuntur.', '> file recieved using ref number', 2, '2024-02-20 16:15:46', '2024-03-13 22:50:46'),
('4Svwj26yxo1M3lY', '2020acd1058', '2020adm1058', '12new545690', '> Satyam\'s Remarks\r\n> Satyam\'s Remarks\r\n> Satyam\'s Remarks\r\n> Satyam\'s Remarks\r\n> Satyam\'s Remarks', 'first file returned back to admin', 4, '2024-03-13 16:21:24', '2024-03-13 20:32:48'),
('6x2mtSjMVvBq8XF', '2020acd1058', '2020adm1058', '12new545690', '2nd file sent using <<<<<<<<new queries>>>>>>>>', '<<<<<file to admin accepted>>>>>> ', 2, '2024-03-15 22:16:03', '2024-03-15 22:17:13'),
('BabgkRwj8JOhUv4', '22CEE_04', '2020adm1058', 'newFile01', '<<<<<new File 1 : trasns 1 - > satyam to admin >>>>>', '<<<<< newFIle 01  : trans 02 , admin returned the file >>>>>', 2, '2024-03-16 00:01:56', '2024-03-16 00:03:53'),
('CgX28LSolNbJcu9', '22CEE_04', '2020adm1058', 'newFile01', '<<<<morning file-sent to admin>>>>', 'returnd file after dix\n', 2, '2024-03-21 07:52:30', '2024-03-21 08:00:34'),
('d59apMN4IRq1xHh', '2020adm1058', '2020acd1058', 'f168_dsw12798', '<<<<<returning file back to satyam from admin>>>>>>', '', 2, '2024-03-15 22:20:49', '2024-03-15 23:53:23'),
('d9YmMaJWV1', '2020acd1058', '2020adm1058', '12cgf545647', '<<<file returd to admin>>', 'file accepted by admin after changes', 2, '2024-03-21 07:58:16', '2024-04-20 00:47:45'),
('fBDSVyoWalzpXRe', '22CEE_04', '2020adm1058', 'NewFile02', '<<<<< newFIle 02  : trans 01 , satyam -> admin >>>>>', '<<<<< file 02 : transacti 02 : forwarding to bahnu <<<accepted>>>>>>>>', 2, '2024-03-16 00:02:24', '2024-03-16 00:05:22'),
('forward0101', '2020adm1058', '2020acd1058', '12cgf545647', '<<<<file forwarded by admin ///forward >>>after fix>>>>>', '<<<file returd to admin>>', 2, '2024-03-21 07:55:30', '2024-03-21 07:58:16'),
('FWD-a8nAO2yDco', '2020adm1058', '22CEE_04', 'f108_cdc10798', 'file forwarded after changes', '', 1, '2024-04-20 00:50:46', NULL),
('HFoP4s3VauXnGMO', '2020adm1058', '2020acd1058', '12new545690', '> file send to satyam from admin', 'file accepted <<<<now>>>>', 2, '2024-03-13 20:09:55', '2024-03-15 21:32:52'),
('hWGgUIcBjqvmo7R', '22CEE_04', '2020adm1058', '12cgf545647', '<<<morning file 02 - sent to admin>>>>', '<<<<file received by admin ///forward >>>after fix>>>>>', 2, '2024-03-21 07:52:59', '2024-03-21 07:55:30'),
('LXROzJwfgxhVEZt', '2020adm1058', '22CEE_04', '12cgf545647', '> first file sent using system', '<<<<<file received by apoorva: forwarding >>>>>>\n', 2, '2024-03-10 23:02:57', '2024-03-15 23:57:03'),
('Ov4Di5yt2FHbx9c', '2020acd1058', '2020adm1058', 'f168_dsw12798', 'file sent using <<<<<new queries>>>>>>', '<<<<<returning file back to satyam from admin>>>>>>', 2, '2024-03-15 22:15:36', '2024-03-15 22:20:49'),
('RET-5VmZX4Nj1I', '22CEE_04', '2020adm1058', 'newFile01', 'file retruned by apporava aftere changes\n', '', 1, '2024-04-20 00:48:50', NULL),
('RET-HzpFsjguXm', '2020adm1058', '22CEE_04', 'newFile01', 'returnd file after dix\n', 'file retruned by apporava aftere changes\n', 2, '2024-03-21 08:00:34', '2024-04-20 00:48:50'),
('SND-26ofEXQlMe', '2020adm1058', '22CEE_04', 'NewFile02', 'colour check', '', 1, '2024-04-10 23:21:08', NULL),
('SND-APQHgNexOq', '2020ece1058', '2020adm1058', 'f108_cdc10798', 'fsdfsdffsfsd', 'file forwarded after changes', 2, '2024-04-20 00:50:02', '2024-04-20 00:50:46'),
('SND-tjqCODpod4', '2020adm1058', '2020acd1058', '145gf545647', 'mail  check', '', 1, '2024-04-22 17:30:17', NULL),
('tB5whSVDvAPRUci', '22CEE_04', '2020adm1058', '12new545691', 'remarks from apooracv\r\n', '> first file accepted using system\n', 2, '2024-03-13 19:35:09', '2024-03-13 20:05:34'),
('UlXT9bdhDGZkJns', '2020adm1058', '22CEE_04', 'newFile01', '<<<<< newFIle 01  : trans 02 , admin returned the file >>>>>', '<<<<file recieved <<<<forwrding>>>>>> >>>>>newFIle01', 2, '2024-03-16 00:03:53', '2024-03-16 00:14:05');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` varchar(20) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 2 COMMENT '1 = admin, 2 = staff',
  `dept_id` varchar(20) DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = active , 0 = disabled'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `password`, `type`, `dept_id`, `date_created`, `active`) VALUES
('2020acd1058', 'Satyam', 'katiyar', 'shivamray07022002@gmail.com', 'e6053eb8d35e02ae40beeeacef203c1a', 2, '12ACD_07', '2024-02-25 18:37:59', 1),
('2020adm1058', 'Shivam', 'Rai', 'admin@admin.com', '0192023a7bbd73250516f069df18b500', 1, '12ADM_09', '2024-02-20 10:57:04', 1),
('2020civ1058', 'apoorva', 'sharma', 'ap@apporva', '0192023a7bbd73250516f069df18b500', 2, '12CSE_11', '2024-02-28 11:39:54', 0),
('2020csc1058', 'Bhanu', 'Pratap', 'superuser@admin.com', 'd40242fb23c45206fadee4e2418f274f', 1, '12CSE_11', '2024-02-20 13:32:12', 1),
('2020ece1058', 'somnath', 'dw', 'somanth@12', '0192023a7bbd73250516f069df18b500', 2, '12ECE_02', '2024-02-25 18:46:20', 1),
('2024Amna', 'aman ', 'saini', 'aman@aman', 'e10adc3949ba59abbe56e057f20f883e', 2, '12ECE_02', '2024-03-27 21:50:58', 1),
('22CEE_04', 'Satyam', 'Apporva', 'ap@apporva', '0192023a7bbd73250516f069df18b500', 2, '12CSE_11', '2024-02-28 11:49:19', 1),
('22newUser23', 'new ', 'user', 'new@new', 'e10adc3949ba59abbe56e057f20f883e', 2, '202cdc109', '2024-03-27 17:39:43', 1),
('22Sinesh23', 'dinesh', 'gurjar', 'dinesh@gurjar', '22Sinesh23#98591', 2, '12CSE_11', '2024-03-28 14:53:51', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `files_fk` (`creator_id`);

--
-- Indexes for table `new_user`
--
ALTER TABLE `new_user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `password_reset`
--
ALTER TABLE `password_reset`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`reference_number`),
  ADD KEY `trans_file_fk` (`file_id`),
  ADD KEY `trans_receiver_fk` (`recipient_id`),
  ADD KEY `trans_sender_fk` (`sender_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_fk` (`dept_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `files_fk` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `trans_file_fk` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `trans_receiver_fk` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `trans_sender_fk` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_fk` FOREIGN KEY (`dept_id`) REFERENCES `departments` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
