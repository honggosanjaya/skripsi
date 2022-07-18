-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 17, 2022 at 05:05 PM
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
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_jenis` bigint(20) NOT NULL,
  `id_wilayah` bigint(20) NOT NULL,
  `id_staff` bigint(20) DEFAULT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat_utama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat_nomor` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan_alamat` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `koordinat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telepon` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `durasi_kunjungan` int(11) NOT NULL,
  `counter_to_effective_call` int(11) NOT NULL,
  `tipe_retur` bigint(20) DEFAULT NULL,
  `limit_pembelian` double DEFAULT NULL,
  `pengajuan_limit_pembelian` double DEFAULT NULL,
  `status_limit_pembelian` bigint(20) DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `time_to_effective_call` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `id_jenis`, `id_wilayah`, `id_staff`, `nama`, `email`, `password`, `alamat_utama`, `alamat_nomor`, `keterangan_alamat`, `koordinat`, `telepon`, `durasi_kunjungan`, `counter_to_effective_call`, `tipe_retur`, `limit_pembelian`, `pengajuan_limit_pembelian`, `status_limit_pembelian`, `foto`, `status`, `created_at`, `updated_at`, `time_to_effective_call`) VALUES
(1, 2, 9, 3, 'Customer Pertama', 'customer1@gmail.com', '$2y$10$RGTK4k.IJglxBl4hOWc7juRhh9JEP04GfeUc4BVr9TW36/3an8CpS', 'Jalan Soekarno Hatta', 'no 3', NULL, NULL, '0341657201', 7, 1, 1, 200000, NULL, NULL, 'CUST-Customer Pertama-20220716214052.png', 3, NULL, '2022-07-17 01:04:39', '2022-07-15 23:55:22'),
(2, 1, 10, 3, 'Customer Kedua', 'customer2@gmail.com', '$2y$10$1IV6.xnOt6WwFSxV1NMOXO1qW8oE..U13amNqeDIypMKi/2f3UnhO', 'Jalan Sawojajar', 'E5 no 15', 'Jual Sate dan Bakso', NULL, '086789024567', 7, 1, NULL, 200000, NULL, NULL, 'CUST-Customer Kedua-20220716211218.jpg', 3, NULL, '2022-07-17 01:04:28', '2022-07-16 00:12:18'),
(3, 1, 9, 3, 'Customer Ketiga', 'customer3@gmail.com', '$2y$10$IKW3c3YqDrF4MyUj6keyquGTTLVlMr5BOacs0hUYgt4v6msR1xP92', 'Perumahan Araya', 'Blok A1 no 1', 'Rumah Pagar Hitam', NULL, '085678934567', 7, 1, NULL, 200000, NULL, NULL, NULL, 3, NULL, '2022-07-17 01:04:48', '2022-07-17 00:52:09'),
(4, 1, 3, 3, 'Customer Keempat', NULL, NULL, 'Perumahaan Istana Dieng', 'no 10', 'Rumah cat pink', NULL, '085678925671', 7, 1, NULL, 200000, NULL, NULL, NULL, 3, NULL, '2022-07-17 01:04:57', '2022-07-17 00:55:36'),
(5, 1, 10, NULL, 'Customer Kelima', NULL, NULL, 'Jalan Danau Bratan', '13', NULL, '-8.2237269@111.7964117', NULL, 7, 1, NULL, NULL, NULL, NULL, NULL, 3, '2022-07-18 13:05:31', '2022-07-18 13:05:32', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
