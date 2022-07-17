-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 17, 2022 at 12:58 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `skripsi`
--

-- --------------------------------------------------------

--
-- Table structure for table `districts`
--

CREATE TABLE `districts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_parent` bigint(20) DEFAULT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `districts`
--

INSERT INTO `districts` (`id`, `id_parent`, `nama`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Indonesia', '2022-07-16 13:53:55', '2022-07-16 13:53:55'),
(2, 1, 'Jawa Timur', '2022-07-16 13:54:02', '2022-07-16 13:54:02'),
(3, 2, 'Malang', '2022-07-16 13:54:09', '2022-07-16 13:54:09'),
(4, 2, 'Tuban', '2022-07-17 10:32:32', '2022-07-17 10:34:13'),
(5, 1, 'Jawa Tengah', '2022-07-17 10:32:42', '2022-07-17 10:32:42'),
(6, 1, 'Jawa Barat', '2022-07-17 10:32:52', '2022-07-17 10:32:52'),
(7, 6, 'Bogor', '2022-07-17 10:33:24', '2022-07-17 10:33:24'),
(8, 5, 'Semarang', '2022-07-17 10:33:34', '2022-07-17 10:33:34'),
(9, 3, 'Blimbing', '2022-07-17 10:33:46', '2022-07-17 10:34:42'),
(10, 3, 'Kedungkandang', '2022-07-17 10:34:55', '2022-07-17 10:34:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `districts`
--
ALTER TABLE `districts`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `districts`
--
ALTER TABLE `districts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
