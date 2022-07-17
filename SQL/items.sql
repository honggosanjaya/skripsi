-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 17, 2022 at 03:17 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aplikasi_salesman`
--

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_barang` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `min_stok` int(11) NOT NULL DEFAULT 0,
  `max_stok` int(11) NOT NULL DEFAULT 0,
  `max_pengadaan` int(11) NOT NULL DEFAULT 0,
  `satuan` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga_satuan` double NOT NULL,
  `status` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `gambar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `volume` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `nama`, `kode_barang`, `stok`, `min_stok`, `max_stok`, `max_pengadaan`, `satuan`, `harga_satuan`, `status`, `created_at`, `updated_at`, `gambar`, `volume`) VALUES
(1, 'sapu lidi', 'P385', 300, 10, 500, 490, 'pcs', 28000, 10, '2022-07-17 11:46:20', '2022-07-17 11:46:20', NULL, 50),
(2, 'sapu ijuk', 'M932', 450, 20, 1000, 980, 'pcs', 32000, 10, '2022-07-17 11:49:55', '2022-07-17 11:49:55', NULL, 50),
(3, 'pengki', 'F712', 350, 10, 400, 390, 'pcs', 20000, 10, '2022-07-17 11:50:42', '2022-07-17 11:50:42', NULL, 45),
(4, 'tusuk sate', 'T790', 250, 15, 300, 285, 'kg', 18000, 10, '2022-07-17 11:52:05', '2022-07-17 11:52:05', NULL, 75),
(5, 'tusuk gigi', 'L930', 400, 20, 500, 480, 'kg', 15000, 10, '2022-07-17 11:52:48', '2022-07-17 11:52:48', NULL, 65),
(6, 'kain pel', 'C749', 800, 10, 1000, 990, 'pcs', 10000, 10, '2022-07-17 11:53:46', '2022-07-17 11:53:46', NULL, 20),
(7, 'tempat sampah plastik', 'S772', 300, 10, 500, 490, 'pcs', 12000, 10, '2022-07-17 11:54:43', '2022-07-17 11:54:43', NULL, 100),
(8, 'sumpit', 'U991', 1000, 10, 1000, 990, 'pcs', 14000, 10, '2022-07-17 11:55:57', '2022-07-17 11:55:57', NULL, 15),
(9, 'sikat wc', 'G678', 450, 20, 500, 480, 'pcs', 8000, 10, '2022-07-17 11:56:59', '2022-07-17 11:56:59', NULL, 15),
(10, 'sulak', 'B902', 600, 10, 1000, 990, 'pcs', 10000, 10, '2022-07-17 11:57:39', '2022-07-17 11:57:39', NULL, 25);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
