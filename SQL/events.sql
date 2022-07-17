-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 17, 2022 at 03:16 PM
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
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_staff` bigint(20) NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `diskon` tinyint(4) DEFAULT NULL,
  `potongan` double DEFAULT NULL,
  `min_pembelian` double DEFAULT NULL,
  `kode` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_start` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `date_end` timestamp NULL DEFAULT NULL,
  `status` bigint(20) NOT NULL,
  `gambar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `id_staff`, `nama`, `keterangan`, `diskon`, `potongan`, `min_pembelian`, `kode`, `date_start`, `date_end`, `status`, `gambar`, `created_at`, `updated_at`) VALUES
(1, 2, 'Kemerdekaan', 'Event untuk memperingati hari kemerdekaan Indonesia', 10, NULL, 100000, 'EVN0001', '2022-07-17 13:15:52', '2022-08-16 17:00:00', 16, NULL, '2022-07-16 15:07:20', '2022-07-16 15:07:20'),
(2, 2, 'Peringatan Ulang Tahun Perusahaan', 'Event untuk memperingati hari ulang tahun perusahaan', NULL, 10000, 100000, 'EVN0002', '2022-07-17 13:15:57', '2022-08-15 17:00:00', 16, NULL, '2022-07-16 15:19:09', '2022-07-16 15:19:09'),
(3, 2, 'Diskon Akhir Bulan', 'Event untuk memberikan diskon di akhir bulan', 5, NULL, 1000, 'EVN0003', '2022-07-17 13:16:02', '2022-08-30 17:00:00', 16, NULL, '2022-07-17 10:26:10', '2022-07-17 10:28:53'),
(4, 2, 'Diskon Awal Bulan', 'Event untuk memberikan diskon di awal bulan', 5, NULL, 100000, 'EVN0004', '2022-07-17 13:16:06', '2022-08-05 17:00:00', 16, NULL, '2022-07-17 10:28:35', '2022-07-17 10:29:58'),
(5, 2, 'Launching Sumpit Baru', 'Event untuk launching produk baru', NULL, 5000, 25000, 'EVN0005', '2022-07-17 13:16:10', '2022-07-16 17:00:00', 26, NULL, '2022-07-17 10:32:02', '2022-07-17 10:32:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
