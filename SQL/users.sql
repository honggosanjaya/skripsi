-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 17, 2022 at 05:06 PM
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
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_users` bigint(20) NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tabel` enum('staffs','customers') COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `id_users`, `email`, `email_verified_at`, `password`, `tabel`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 1, 'owner@gmail.com', NULL, '$2y$10$hDXFvn3LFeKLMoTk4Dwxdu6UZAGVq7/KeclE7xxmChTRTlqWWMPoy', 'staffs', NULL, '2022-07-16 13:50:41', '2022-07-16 13:50:41'),
(2, 2, 'supervisor@gmail.com', NULL, '$2y$10$mXSH.wYCx7JZ.M0V/hjUTuNv/qAD56fRZkqT6HmI/nHuDadnFIWPu', 'staffs', NULL, '2022-07-16 13:51:19', '2022-07-16 13:51:19'),
(3, 3, 'administrasi@gmail.com', NULL, '$2y$10$Y10B/Aqbo39Q2a5CZVemO./TrbFIMH8ZHyayfgPhJa9T3eaVsEE0G', 'staffs', NULL, '2022-07-16 13:51:54', '2022-07-16 13:51:54'),
(4, 4, 'salesman@gmail.com', NULL, '$2y$10$NxdmGMHn1tMrPheTmd.X8uGHGsZ1LCfrSYM0Vjw7vb1wU6vzgnVB.', 'staffs', NULL, '2022-07-16 13:52:11', '2022-07-16 13:52:11'),
(5, 5, 'shipper@gmail.com', NULL, '$2y$10$qaPmdNBq8Wnwba5wZtsHEuYUOWKNME6vBVFQV44B/YEahxACx5cde', 'staffs', NULL, '2022-07-16 13:52:32', '2022-07-16 13:52:32'),
(6, 1, 'customer1@gmail.com', NULL, '$2y$10$jjrOmjZC8FzRMcdFC2rLz.p6dKW/CHxSNT1P3Hk7gJv9qGHkSCbtu', 'customers', NULL, '2022-07-16 13:55:23', '2022-07-16 13:55:23'),
(7, 2, 'customer2@gmail.com', NULL, '$2y$10$WS07ME5SMQrsD/wkVN67.usR5q/wpAWB9a2CTa4V.re6gYgUWpGiK', 'customers', NULL, '2022-07-16 14:12:18', '2022-07-16 14:12:18'),
(8, 6, 'administrasi2@gmail.com', NULL, '$2y$10$kGjB7ScHZbRR2KBga2ahxua6fQOdk/whYCO3k/QmFVWjPbKRUFCTa', 'staffs', NULL, '2022-07-16 14:42:05', '2022-07-16 14:42:05'),
(9, 7, 'salesman2@gmail.com', NULL, '$2y$10$odsVuujA16yWcWt91naHg.vCfyA6YF7hweKV7ovu/sT5uXnXEjWNW', 'staffs', NULL, '2022-07-16 14:42:29', '2022-07-16 14:42:29'),
(10, 8, 'shipper2@gmail.com', NULL, '$2y$10$RH.ZetPSYB88PxPYlqYtmuSbagStoZUp3GpN9k087BKqgAnAH33Ei', 'staffs', NULL, '2022-07-16 14:42:58', '2022-07-16 14:42:58'),
(11, 9, 'supervisor2@gmail.com', NULL, '$2y$10$4fNi27UnS4JetaaDRBUkIeZwkPhQLcDxcvqjMpeTCNpGuRSZJWFtC', 'staffs', NULL, '2022-07-16 14:44:13', '2022-07-16 14:44:13'),
(12, 3, 'customer3@gmail.com', NULL, '$2y$10$puXX9p43O0DnGWHncc8NTO/K/vcDd9UWGpv0RHh0kAdJh5eMJ8due', 'customers', NULL, '2022-07-17 14:52:10', '2022-07-17 14:52:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
