-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 19, 2022 at 03:53 PM
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
(1, 1, 1, 3, 'Customer Pertama', 'customer1@gmail.com', '$2y$10$NCmCCdBtL6bSKh8VtrY1defHJ3f0brLm/PFKbY6IlsdIzzn5ZCg2e', 'Jalan Soekarno Hatta', 'no 3', NULL, '-6.135931@106.7837584', '0341657201', 7, 1, 2, 200000, NULL, NULL, 'CUST-Customer Pertama-20220716214052.png', 3, '2022-07-18 13:40:26', '2022-07-19 13:36:30', '2022-07-15 16:55:22'),
(2, 1, 1, 3, 'Customer Kedua', 'customer2@gmail.com', '$2y$10$CEnwu0JvgbqNBqaRKWGpv.f3fqRiFllNCa2alXMST3fNRnW83gOsm', 'Jalan Sawojajar', 'E5 no 15', 'Jual Sate dan Bakso', '-6.135931@106.7837584', '086789024567', 7, 1, 1, 200000, NULL, NULL, 'CUST-Customer Kedua-20220716211218.jpg', 3, '2022-07-18 13:42:47', '2022-07-18 13:56:17', '2022-07-15 17:12:18'),
(3, 1, 1, 3, 'Customer Ketiga', 'customer3@gmail.com', '$2y$10$f2X43oYDc7xY3qrfJQ2AiuzfGxmRzDAKWgEt03h9c/cUOz07SQrJK', 'Perumahan Araya', 'Blok A1 no 1', 'Rumah Pagar Hitam', '-6.1359337@106.7837626', '085678934567', 7, 1, 1, 200000, NULL, NULL, NULL, 3, '2022-07-18 14:14:56', '2022-07-19 13:16:03', '2022-07-16 17:52:09'),
(4, 1, 1, 3, 'Customer Keempat', 'customer4@gmail.com', '$2y$10$IsWuuCAPSwguke2GDmZO6eEFHFDkWF6elKtuT3EexTpchwkIzoa9u', 'Perumahaan Istana Dieng', 'no 10', 'Rumah cat pink', '-6.1359379@106.7837597', '085678925671', 7, 1, 1, 200000, NULL, NULL, NULL, 3, '2022-07-18 13:44:57', '2022-07-18 14:20:26', '2022-07-16 17:55:36'),
(5, 1, 10, 7, 'Customer Kelima', 'customer5@gmail.com', NULL, 'Jalan Danau Bratan', '13', NULL, '-8.2237269@111.7964117', '087859786498', 7, 3, 2, NULL, NULL, 6, NULL, 3, '2022-07-18 06:05:31', '2022-07-19 13:28:29', '2022-07-18 14:21:46'),
(6, 1, 4, 6, 'Customer Keenam', NULL, NULL, 'Jalan Blimbing Indah', 'Blok Q no 5', NULL, NULL, '087890897654', 7, 1, 1, 1000000, NULL, 5, NULL, 3, NULL, '2022-07-19 13:29:02', '2022-07-19 13:26:40');

-- --------------------------------------------------------

--
-- Table structure for table `customer_types`
--

CREATE TABLE `customer_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `diskon` double NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_types`
--

INSERT INTO `customer_types` (`id`, `nama`, `diskon`, `keterangan`) VALUES
(1, 'toko', 10, 'Diskon 10% untuk customer toko'),
(2, 'grosir', 20, 'customer grosir'),
(3, 'distributor', 25, 'distributor besar');

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
(1, NULL, 'Indonesia', '2022-07-15 23:53:55', '2022-07-15 23:53:55'),
(2, 1, 'Jawa Timur', '2022-07-15 23:54:02', '2022-07-15 23:54:02'),
(3, 2, 'Malang', '2022-07-15 23:54:09', '2022-07-15 23:54:09'),
(4, 2, 'Tuban', '2022-07-16 20:32:32', '2022-07-16 20:34:13'),
(5, 1, 'Jawa Tengah', '2022-07-16 20:32:42', '2022-07-16 20:32:42'),
(6, 1, 'Jawa Barat', '2022-07-16 20:32:52', '2022-07-16 20:32:52'),
(7, 6, 'Bogor', '2022-07-16 20:33:24', '2022-07-16 20:33:24'),
(8, 5, 'Semarang', '2022-07-16 20:33:34', '2022-07-16 20:33:34'),
(9, 3, 'Blimbing', '2022-07-16 20:33:46', '2022-07-16 20:34:42'),
(10, 3, 'Kedungkandang', '2022-07-16 20:34:55', '2022-07-16 20:34:55');

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
(1, 2, 'Kemerdekaan', 'Event untuk memperingati hari kemerdekaan Indonesia', 10, NULL, 100000, 'EVN0001', '2022-07-16 23:15:52', '2022-08-16 03:00:00', 16, NULL, '2022-07-16 01:07:20', '2022-07-16 01:07:20'),
(2, 2, 'Peringatan Ulang Tahun Perusahaan', 'Event untuk memperingati hari ulang tahun perusahaan', NULL, 10000, 100000, 'EVN0002', '2022-07-16 23:15:57', '2022-08-15 03:00:00', 16, NULL, '2022-07-16 01:19:09', '2022-07-16 01:19:09'),
(3, 2, 'Diskon Akhir Bulan', 'Event untuk memberikan diskon di akhir bulan', 5, NULL, 1000, 'EVN0003', '2022-07-16 23:16:02', '2022-08-30 03:00:00', 16, NULL, '2022-07-16 20:26:10', '2022-07-16 20:28:53'),
(4, 2, 'Diskon Awal Bulan', 'Event untuk memberikan diskon di awal bulan', 5, NULL, 100000, 'EVN0004', '2022-07-16 23:16:06', '2022-08-05 03:00:00', 16, NULL, '2022-07-16 20:28:35', '2022-07-16 20:29:58'),
(5, 2, 'Launching Sumpit Baru', 'Event untuk launching produk baru', NULL, 5000, 25000, 'EVN0005', '2022-07-16 23:16:10', '2022-07-16 03:00:00', 26, NULL, '2022-07-16 20:32:02', '2022-07-16 20:32:13');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `histories`
--

CREATE TABLE `histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_customer` bigint(20) NOT NULL,
  `id_item` bigint(20) NOT NULL,
  `stok_maksimal_customer` int(11) NOT NULL,
  `stok_terakhir_customer` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `histories`
--

INSERT INTO `histories` (`id`, `id_customer`, `id_item`, `stok_maksimal_customer`, `stok_terakhir_customer`, `created_at`, `updated_at`) VALUES
(1, 2, 3, 2, 2, '2022-07-18 14:27:00', '2022-07-18 14:27:00'),
(2, 2, 6, 5, 5, '2022-07-18 14:27:00', '2022-07-18 14:27:00'),
(3, 1, 1, 2, 2, '2022-07-18 14:27:24', '2022-07-18 14:27:24'),
(4, 1, 4, 2, 2, '2022-07-18 14:27:24', '2022-07-18 14:27:24'),
(5, 1, 5, 1, 1, '2022-07-18 14:27:24', '2022-07-18 14:27:24'),
(6, 3, 2, 9, 9, '2022-07-18 14:36:28', '2022-07-19 13:17:03'),
(7, 3, 8, 3, 3, '2022-07-18 14:36:28', '2022-07-18 14:36:28'),
(8, 3, 9, 27, 27, '2022-07-18 14:36:28', '2022-07-18 15:08:09'),
(9, 4, 4, 6, 6, '2022-07-18 14:36:39', '2022-07-18 14:36:39'),
(10, 4, 5, 5, 5, '2022-07-18 14:36:39', '2022-07-18 14:36:39'),
(11, 4, 9, 17, 17, '2022-07-18 14:36:39', '2022-07-18 14:36:39'),
(12, 4, 10, 6, 6, '2022-07-18 14:36:39', '2022-07-18 14:36:39'),
(13, 5, 6, 10, 10, '2022-07-18 14:36:52', '2022-07-18 14:36:52'),
(14, 5, 8, 8, 8, '2022-07-18 14:36:52', '2022-07-18 14:36:52'),
(15, 3, 10, 16, 16, '2022-07-18 15:08:09', '2022-07-18 15:08:09'),
(16, 3, 3, 4, 4, '2022-07-18 15:08:09', '2022-07-18 15:08:09'),
(17, 3, 1, 9, 9, '2022-07-18 15:26:05', '2022-07-19 13:17:03');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_order` bigint(20) NOT NULL,
  `id_event` bigint(20) DEFAULT NULL,
  `nomor_invoice` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga_total` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `id_order`, `id_event`, `nomor_invoice`, `harga_total`, `created_at`, `updated_at`) VALUES
(1, 6, 3, 'INV-1-20220718205617', 76500, '2022-07-18 13:56:17', '2022-07-18 14:27:00'),
(2, 7, NULL, 'INV-2-20220718210256', 38700, '2022-07-18 14:02:56', '2022-07-18 14:56:48'),
(3, 8, 2, 'INV-3-20220718211044', 98000, '2022-07-18 14:10:44', NULL),
(4, 9, NULL, 'INV-4-20220718211638', 66600, '2022-07-18 14:16:38', '2022-07-18 15:12:50'),
(5, 10, 1, 'INV-5-20220718212026', 172800, '2022-07-18 14:20:26', '2022-07-18 14:36:39'),
(6, 11, NULL, 'INV-6-20220718212146', 108000, '2022-07-18 14:21:46', '2022-07-18 14:36:52'),
(7, 12, 2, 'INV-7-20220718220737', 80000, '2022-07-18 15:07:37', '2022-07-18 15:31:27'),
(8, 14, NULL, 'INV-8-20220718221750', 149400, '2022-07-18 15:17:50', '2022-07-19 13:21:37'),
(9, 15, NULL, 'INV-9-20220719201603', 187200, '2022-07-19 13:16:03', '2022-07-19 13:17:03');

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
(1, 'sapu lidi', 'P385', 504, 10, 500, 490, 'pcs', 28000, 10, '2022-07-16 21:46:20', '2022-07-19 13:47:49', NULL, 50),
(2, 'sapu ijuk', 'M932', 704, 20, 1000, 980, 'pcs', 32000, 10, '2022-07-16 21:49:55', '2022-07-19 13:47:49', NULL, 50),
(3, 'pengki', 'F712', 496, 10, 400, 390, 'pcs', 20000, 10, '2022-07-16 21:50:42', '2022-07-18 15:08:09', NULL, 45),
(4, 'tusuk sate', 'T790', 490, 15, 300, 285, 'kg', 18000, 10, '2022-07-16 21:52:05', '2022-07-19 13:42:28', NULL, 75),
(5, 'tusuk gigi', 'L930', 600, 20, 500, 480, 'kg', 15000, 10, '2022-07-16 21:52:48', '2022-07-19 13:42:28', NULL, 65),
(6, 'kain pel', 'C749', 1290, 10, 1000, 990, 'pcs', 10000, 10, '2022-07-16 21:53:46', '2022-07-18 14:36:52', NULL, 20),
(7, 'tempat sampah plastik', 'S772', 500, 10, 500, 490, 'pcs', 12000, 10, '2022-07-16 21:54:43', '2022-07-17 11:37:08', NULL, 100),
(8, 'sumpit', 'U991', 994, 10, 1000, 990, 'pcs', 14000, 10, '2022-07-16 21:55:57', '2022-07-19 13:21:37', NULL, 15),
(9, 'sikat wc', 'G678', 683, 20, 500, 480, 'pcs', 8000, 10, '2022-07-16 21:56:59', '2022-07-18 15:12:50', NULL, 15),
(10, 'sulak', 'B902', 589, 10, 1000, 990, 'pcs', 10000, 10, '2022-07-16 21:57:39', '2022-07-18 15:12:50', NULL, 25);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2022_05_15_110841_create_histories_table', 1),
(6, '2022_05_15_110937_create_trips_table', 1),
(7, '2022_05_15_111011_create_staff_table', 1),
(8, '2022_05_15_111044_create_customers_table', 1),
(9, '2022_05_15_111125_create_customer_types_table', 1),
(10, '2022_05_15_111146_create_districts_table', 1),
(11, '2022_05_15_111217_create_statuses_table', 1),
(12, '2022_05_15_111246_create_staff_roles_table', 1),
(13, '2022_05_15_111312_create_items_table', 1),
(14, '2022_05_15_111341_create_pengadaans_table', 1),
(15, '2022_05_15_111404_create_order_items_table', 1),
(16, '2022_05_15_111426_create_returs_table', 1),
(17, '2022_05_15_111444_create_orders_table', 1),
(18, '2022_05_15_111507_create_invoices_table', 1),
(19, '2022_05_15_111528_create_retur_types_table', 1),
(20, '2022_05_15_111549_create_vehicles_table', 1),
(21, '2022_05_15_111616_create_order_tracks_table', 1),
(22, '2022_05_15_111638_create_events_table', 1),
(23, '2022_05_16_031120_add_order_to_statuses_table', 1),
(24, '2022_05_16_031348_add_gambar_to_items_table', 1),
(25, '2022_05_23_111811_alter_order_tracks_make_estimasi_nullable', 1),
(26, '2022_05_25_151459_add_volume_to_items_table', 1),
(27, '2022_06_14_140935_add_ec_pertama_to_customers_table', 1),
(28, '2022_06_14_160253_change_nullableidstaff_in_customers_table', 1),
(29, '2022_06_29_192137_change_int_to_char', 1),
(30, '2022_06_29_192948_change_20_to_255', 1),
(31, '2022_06_29_193026_change_20_to_255_s', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_customer` bigint(20) NOT NULL,
  `id_staff` bigint(20) DEFAULT NULL,
  `status` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `id_customer`, `id_staff`, `status`, `created_at`, `updated_at`) VALUES
(1, 0, 3, 14, '2022-07-17 11:33:00', NULL),
(2, 0, 3, 14, '2022-07-17 11:34:17', NULL),
(3, 0, 3, 14, '2022-07-17 11:35:17', NULL),
(4, 0, 3, 14, '2022-07-17 11:36:20', NULL),
(5, 0, 3, 14, '2022-07-17 11:37:08', NULL),
(6, 2, 4, 14, '2022-07-18 13:56:16', '2022-07-18 14:27:00'),
(7, 1, 4, 14, '2022-07-18 14:02:56', '2022-07-18 14:27:24'),
(8, 3, 4, 15, '2022-07-18 14:10:44', NULL),
(9, 3, 7, 14, '2022-07-18 14:16:38', '2022-07-18 14:36:28'),
(10, 4, 7, 14, '2022-07-18 14:20:26', '2022-07-18 14:36:39'),
(11, 5, 7, 14, '2022-07-18 14:21:46', '2022-07-18 14:36:52'),
(12, 3, 7, 14, '2022-07-18 14:58:58', '2022-07-18 15:08:09'),
(14, 3, 4, 14, '2022-07-18 15:17:50', '2022-07-18 15:26:05'),
(15, 3, 4, 14, '2022-07-19 13:12:03', '2022-07-19 13:17:03'),
(16, 0, 6, 14, '2022-07-19 13:42:28', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_order` bigint(20) NOT NULL,
  `id_item` bigint(20) NOT NULL,
  `kuantitas` int(11) NOT NULL,
  `harga_satuan` double NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `id_order`, `id_item`, `kuantitas`, `harga_satuan`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 1, 3, -50, 0, 'hilang kurang tahu kenapa', '2022-07-17 11:33:00', NULL),
(2, 1, 4, -50, 0, 'kebawa selama ekspedisi', '2022-07-17 11:33:00', NULL),
(3, 2, 8, 50, 0, 'barang promo', '2022-07-17 11:34:17', NULL),
(4, 2, 5, 40, 0, 'barang ketelisut ditemukan', '2022-07-17 11:34:17', NULL),
(5, 3, 5, 10, 0, 'barang opname', '2022-07-17 11:35:17', NULL),
(6, 4, 8, -100, 0, 'pengadaan kurang', '2022-07-17 11:36:20', NULL),
(7, 5, 7, 100, 0, 'pengadaan kurang', '2022-07-17 11:37:08', NULL),
(8, 6, 3, 2, 18000, 'pengki 2 kain pel 5', '2022-07-18 13:56:16', NULL),
(9, 6, 6, 5, 9000, 'pengki 2 kain pel 5', '2022-07-18 13:56:16', NULL),
(10, 7, 1, 2, 25200, 'sapu lidi 2, tusuk sate 2, tusuk gigi 1', '2022-07-18 14:02:56', NULL),
(11, 7, 4, 2, 16200, 'sapu lidi 2, tusuk sate 2, tusuk gigi 1', '2022-07-18 14:02:56', NULL),
(12, 7, 5, 1, 13500, 'sapu lidi 2, tusuk sate 2, tusuk gigi 1', '2022-07-18 14:02:56', NULL),
(13, 8, 7, 5, 10800, 'tempat sampah plastik 5, sulak 6', '2022-07-18 14:10:44', NULL),
(14, 8, 10, 6, 9000, 'tempat sampah plastik 5, sulak 6', '2022-07-18 14:10:44', NULL),
(15, 9, 2, 2, 28800, 'sapu ijuk 2, sumpit 3, sikat wc 14', '2022-07-18 14:16:38', NULL),
(16, 9, 8, 3, 12600, 'sapu ijuk 2, sumpit 3, sikat wc 14', '2022-07-18 14:16:38', NULL),
(17, 9, 9, 14, 7200, 'sapu ijuk 2, sumpit 3, sikat wc 14', '2022-07-18 14:16:38', NULL),
(18, 10, 4, 4, 16200, 'tusuk sate 4, tusuk gigi 4, sikat wc 3, sulak 6', '2022-07-18 14:20:26', NULL),
(19, 10, 5, 4, 13500, 'tusuk sate 4, tusuk gigi 4, sikat wc 3, sulak 6', '2022-07-18 14:20:26', NULL),
(20, 10, 9, 3, 7200, 'tusuk sate 4, tusuk gigi 4, sikat wc 3, sulak 6', '2022-07-18 14:20:26', NULL),
(21, 10, 10, 6, 9000, 'tusuk sate 4, tusuk gigi 4, sikat wc 3, sulak 6', '2022-07-18 14:20:26', NULL),
(22, 11, 6, 5, 9000, 'kain pel 5, sumpit 5', '2022-07-18 14:21:46', NULL),
(23, 11, 8, 5, 12600, 'kain pel 5, sumpit 5', '2022-07-18 14:21:46', NULL),
(24, 12, 10, 10, 9000, 'pengki 2, sikat wc 10, sulak 10', '2022-07-18 15:07:37', '2022-07-18 15:07:37'),
(25, 12, 9, 10, 7200, 'pengki 2, sikat wc 10, sulak 10', '2022-07-18 15:07:37', '2022-07-18 15:07:37'),
(26, 12, 3, 2, 18000, 'pengki 2, sikat wc 10, sulak 10', '2022-07-18 15:07:37', '2022-07-18 15:07:37'),
(29, 14, 1, 3, 25200, 'sapu lidi 3, sapu ijuk 4', '2022-07-18 15:17:50', NULL),
(30, 14, 2, 4, 28800, 'sapu lidi 3, sapu ijuk 4', '2022-07-18 15:17:50', NULL),
(31, 15, 1, 4, 25200, 'sapu lidi 4, sapu ijuk 3', '2022-07-19 13:16:03', '2022-07-19 13:16:03'),
(32, 15, 2, 3, 28800, 'sapu lidi 4, sapu ijuk 3', '2022-07-19 13:16:03', '2022-07-19 13:16:03'),
(33, 16, 4, -6, 0, 'hilang 6', '2022-07-19 13:42:28', NULL),
(34, 16, 5, 5, 0, 'bonus 5', '2022-07-19 13:42:28', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_tracks`
--

CREATE TABLE `order_tracks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_order` bigint(20) NOT NULL,
  `id_staff_pengonfirmasi` bigint(20) DEFAULT NULL,
  `id_staff_pengirim` bigint(20) DEFAULT NULL,
  `id_vehicle` bigint(20) DEFAULT NULL,
  `status` bigint(20) NOT NULL,
  `waktu_order` timestamp NULL DEFAULT NULL,
  `waktu_diteruskan` timestamp NULL DEFAULT NULL,
  `waktu_dikonfirmasi` timestamp NULL DEFAULT NULL,
  `waktu_berangkat` timestamp NULL DEFAULT NULL,
  `waktu_sampai` timestamp NULL DEFAULT NULL,
  `estimasi_waktu_pengiriman` tinyint(4) DEFAULT NULL,
  `foto_pengiriman` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_tracks`
--

INSERT INTO `order_tracks` (`id`, `id_order`, `id_staff_pengonfirmasi`, `id_staff_pengirim`, `id_vehicle`, `status`, `waktu_order`, `waktu_diteruskan`, `waktu_dikonfirmasi`, `waktu_berangkat`, `waktu_sampai`, `estimasi_waktu_pengiriman`, `foto_pengiriman`, `created_at`, `updated_at`) VALUES
(1, 6, 3, NULL, NULL, 21, '2022-07-18 13:56:16', '2022-07-18 13:56:16', '2022-07-18 14:27:00', NULL, NULL, 6, NULL, '2022-07-18 13:56:16', '2022-07-18 14:27:00'),
(2, 7, 3, 5, 1, 23, '2022-07-18 14:02:56', '2022-07-18 14:02:56', '2022-07-18 14:27:24', '2022-07-18 14:40:30', '2022-07-18 14:45:20', 6, 'DLV-7.jpg', '2022-07-18 14:02:56', '2022-07-18 14:45:20'),
(3, 8, NULL, NULL, NULL, 25, '2022-07-18 14:10:44', '2022-07-18 14:10:44', '2022-07-18 14:28:25', NULL, NULL, 6, NULL, '2022-07-18 14:10:44', '2022-07-18 14:28:25'),
(4, 9, 6, 8, 2, 23, '2022-07-18 14:16:38', '2022-07-18 14:16:38', '2022-07-18 14:36:28', '2022-07-18 14:41:09', '2022-07-18 14:46:09', 6, 'DLV-9.jpg', '2022-07-18 14:16:38', '2022-07-18 14:46:09'),
(5, 10, 6, NULL, NULL, 21, '2022-07-18 14:20:26', '2022-07-18 14:20:26', '2022-07-18 14:36:39', NULL, NULL, 3, NULL, '2022-07-18 14:20:26', '2022-07-18 14:36:39'),
(6, 11, 6, 8, 1, 23, '2022-07-18 14:21:46', '2022-07-18 14:21:46', '2022-07-18 14:36:52', '2022-07-18 14:41:32', '2022-07-18 14:46:19', 7, 'DLV-11.jpg', '2022-07-18 14:21:46', '2022-07-18 14:46:19'),
(7, 12, 3, 5, 3, 23, '2022-07-18 14:58:58', '2022-07-18 15:07:37', '2022-07-18 15:08:09', '2022-07-18 15:08:19', '2022-07-18 15:08:43', 7, 'DLV-12.jpg', '2022-07-18 14:58:58', '2022-07-18 15:08:43'),
(9, 14, 3, 5, 1, 23, '2022-07-18 15:17:50', '2022-07-18 15:17:50', '2022-07-18 15:26:05', '2022-07-18 15:26:11', '2022-07-18 15:26:54', 5, 'DLV-14.jpg', '2022-07-18 15:17:50', '2022-07-18 15:26:54'),
(10, 15, 3, 5, 1, 23, '2022-07-19 13:12:03', '2022-07-19 13:16:03', '2022-07-19 13:17:03', '2022-07-19 13:17:28', '2022-07-19 13:17:53', 6, 'DLV-15.jpg', '2022-07-19 13:12:03', '2022-07-19 13:17:53');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengadaans`
--

CREATE TABLE `pengadaans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_staff` bigint(20) NOT NULL,
  `id_item` bigint(20) NOT NULL,
  `no_pengadaan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_nota` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kuantitas` int(11) NOT NULL,
  `harga_total` double NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengadaans`
--

INSERT INTO `pengadaans` (`id`, `id_staff`, `id_item`, `no_pengadaan`, `no_nota`, `kuantitas`, `harga_total`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 'PGD-1-20220717215605', '0001', 50, 1250000, 'bapak suril yang kirim', '2022-07-17 07:56:05', NULL),
(2, 3, 2, 'PGD-1-20220717215605', '0001', 100, 2800000, 'bapak suril yang kirim', '2022-07-17 07:56:05', NULL),
(3, 3, 4, 'PGD-1-20220717215605', '0001', 100, 1200000, 'bapak suril yang kirim', '2022-07-17 07:56:05', NULL),
(4, 3, 8, 'PGD-1-20220717215605', '0001', 50, 600000, 'bapak suril yang kirim', '2022-07-17 07:56:05', NULL),
(5, 3, 3, 'PGD-2-20220717215820', '0002', 200, 3000000, 'bapak sinu yang kirim', '2022-07-17 07:58:20', NULL),
(6, 3, 5, 'PGD-2-20220717215820', '0002', 150, 1500000, 'bapak sinu yang kirim', '2022-07-17 07:58:20', NULL),
(7, 3, 9, 'PGD-2-20220717215820', '0002', 50, 250000, 'bapak sinu yang kirim', '2022-07-17 07:58:20', NULL),
(8, 3, 1, 'PGD-3-20220718012236', '0003', 30, 720000, 'yang kirim pak hun', '2022-07-17 11:22:36', NULL),
(9, 3, 2, 'PGD-3-20220718012236', '0003', 20, 500000, 'yang kirim pak hun', '2022-07-17 11:22:36', NULL),
(10, 3, 6, 'PGD-4-20220718012828', '0004', 500, 4000000, 'ini dikirim ekspedisi', '2022-07-17 11:28:28', NULL),
(11, 3, 9, 'PGD-4-20220718012828', '0004', 200, 1000000, 'ini dikirim ekspedisi', '2022-07-17 11:28:28', NULL),
(12, 3, 7, 'PGD-4-20220718012828', '0004', 100, 1000000, 'ini dikirim ekspedisi', '2022-07-17 11:28:28', NULL),
(13, 3, 1, 'PGD-5-20220718013012', '0005', 10, 250000, 'dikirim pengiriman online', '2022-07-17 11:30:12', NULL),
(14, 3, 2, 'PGD-5-20220718013012', '0005', 40, 1000000, 'dikirim pengiriman online', '2022-07-17 11:30:12', NULL),
(15, 6, 1, 'PGD-6-20220719204749', '0006', 10, 200000, 'dikirim pak supriyadi', '2022-07-19 13:47:49', NULL),
(16, 6, 2, 'PGD-6-20220719204749', '0006', 10, 250000, 'dikirim pak supriyadi', '2022-07-19 13:47:49', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `returs`
--

CREATE TABLE `returs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_customer` bigint(20) NOT NULL,
  `id_staff_pengaju` bigint(20) NOT NULL,
  `id_staff_pengonfirmasi` bigint(20) DEFAULT NULL,
  `id_item` bigint(20) NOT NULL,
  `id_invoice` bigint(20) DEFAULT NULL,
  `no_retur` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kuantitas` int(11) NOT NULL,
  `harga_satuan` double NOT NULL,
  `tipe_retur` bigint(20) NOT NULL,
  `alasan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `returs`
--

INSERT INTO `returs` (`id`, `id_customer`, `id_staff_pengaju`, `id_staff_pengonfirmasi`, `id_item`, `id_invoice`, `no_retur`, `kuantitas`, `harga_satuan`, `tipe_retur`, `alasan`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 8, 6, 8, 4, 'RTR-1-20220718214912', 1, 12600, 1, 'panjang tidak sama', 12, '2022-07-18 14:49:12', '2022-07-18 14:55:52'),
(2, 3, 8, 6, 9, 4, 'RTR-1-20220718214912', 5, 7200, 1, 'pecah', 12, '2022-07-18 14:49:12', '2022-07-18 14:55:52'),
(3, 1, 5, 6, 1, 2, 'RTR-2-20220718215326', 1, 25200, 1, 'lidi patah', 12, '2022-07-18 14:53:26', '2022-07-18 14:56:48'),
(4, 1, 5, 6, 4, 2, 'RTR-2-20220718215326', 2, 16200, 1, 'bundu', 12, '2022-07-18 14:53:26', '2022-07-18 14:56:48'),
(5, 3, 5, 6, 9, 4, 'RTR-3-20220718221032', 5, 7200, 1, 'pecah', 12, '2022-07-18 15:10:32', '2022-07-18 15:12:50'),
(6, 3, 5, 6, 10, 4, 'RTR-3-20220718221032', 5, 9000, 1, 'bulunya rontok', 12, '2022-07-18 15:10:32', '2022-07-18 15:12:50'),
(7, 3, 5, 6, 1, 7, 'RTR-4-20220718222738', 2, 25200, 1, 'lidi patah', 12, '2022-07-18 15:27:38', '2022-07-18 15:31:27'),
(8, 3, 5, 6, 2, 7, 'RTR-4-20220718222738', 2, 28800, 1, 'gagang patah', 12, '2022-07-18 15:27:38', '2022-07-18 15:31:27'),
(9, 3, 5, 6, 2, 8, 'RTR-5-20220719202017', 1, 28800, 1, 'patah', 12, '2022-07-19 13:20:17', '2022-07-19 13:21:37'),
(10, 3, 5, 6, 8, 8, 'RTR-5-20220719202017', 1, 12600, 1, 'patah', 12, '2022-07-19 13:20:17', '2022-07-19 13:21:37');

-- --------------------------------------------------------

--
-- Table structure for table `retur_types`
--

CREATE TABLE `retur_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `detail` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `retur_types`
--

INSERT INTO `retur_types` (`id`, `nama`, `detail`, `created_at`, `updated_at`) VALUES
(1, 'potongan', 'potongan terhadap invoice yang telah selesai dan belum lunas', NULL, NULL),
(2, 'tukar guling', 'pertukaran barang saja', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `staffs`
--

CREATE TABLE `staffs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telepon` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto_profil` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` bigint(20) NOT NULL,
  `status` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `staffs`
--

INSERT INTO `staffs` (`id`, `nama`, `email`, `password`, `telepon`, `foto_profil`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Owner', 'owner@gmail.com', '$2y$10$YgwFcCmfxN3geJqg8X9CQurqxeGfNJtf4xwBjr0rDl4RCPpbkkFie', '0819282882', NULL, 1, 8, '2022-07-16 06:50:40', '2022-07-19 13:34:19'),
(2, 'Supervisor', 'supervisor@gmail.com', '$2y$10$bS.JRqyMPuszRA7sxWtZTeMkhzga.ks2frSsZ0E6teJAqnKqYSLGG', '08192828992', NULL, 2, 8, '2022-07-16 06:51:19', '2022-07-19 13:35:00'),
(3, 'Administrasi', 'administrasi@gmail.com', '$2y$10$TdaRvcraSBilG3vUoC5HJeht2FC18h.F8PPbQZtdptYK6okytAUDa', '0829282982', NULL, 5, 8, '2022-07-16 06:51:54', '2022-07-19 13:35:40'),
(4, 'Salesman', 'salesman@gmail.com', '$2y$10$an/LDOpqTkFVKm5nsTjhOufua/No3lGLXnDl3y52/et.FKZ39afIO', '08349282882', NULL, 3, 8, '2022-07-16 06:52:11', '2022-07-19 13:37:18'),
(5, 'Shipper', 'shipper@gmail.com', '$2y$10$yDHuBYDMbL1lHRq1BVCNxum1zouGddAB9YRlshUAgofkmoMPP5TU6', '08998282882', NULL, 4, 8, '2022-07-16 06:52:32', '2022-07-19 13:38:33'),
(6, 'Administrasi2', 'administrasi2@gmail.com', '$2y$10$kGjB7ScHZbRR2KBga2ahxua6fQOdk/whYCO3k/QmFVWjPbKRUFCTa', '085726391754', NULL, 5, 8, '2022-07-16 07:42:05', NULL),
(7, 'Salesman2', 'salesman2@gmail.com', '$2y$10$odsVuujA16yWcWt91naHg.vCfyA6YF7hweKV7ovu/sT5uXnXEjWNW', '08961538290', NULL, 3, 8, '2022-07-16 07:42:29', NULL),
(8, 'Shipper2', 'shipper2@gmail.com', '$2y$10$RH.ZetPSYB88PxPYlqYtmuSbagStoZUp3GpN9k087BKqgAnAH33Ei', '081325164815', NULL, 4, 8, '2022-07-16 07:42:58', NULL),
(9, 'Supervisor2', 'supervisor2@gmail.com', '$2y$10$4fNi27UnS4JetaaDRBUkIeZwkPhQLcDxcvqjMpeTCNpGuRSZJWFtC', '086718402550', NULL, 2, 9, '2022-07-16 07:44:13', '2022-07-16 07:44:13');

-- --------------------------------------------------------

--
-- Table structure for table `staff_roles`
--

CREATE TABLE `staff_roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `detail` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `staff_roles`
--

INSERT INTO `staff_roles` (`id`, `nama`, `detail`, `created_at`, `updated_at`) VALUES
(1, 'owner', 'pemilik (hanya boleh 1)', NULL, NULL),
(2, 'supervisor', 'penanggung jawab (hanya boleh 1 yang aktif)', NULL, NULL),
(3, 'salesman', 'tenaga penjual', NULL, NULL),
(4, 'shipper', 'tenaga pengirim', NULL, NULL),
(5, 'administrasi', 'admin', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `statuses`
--

CREATE TABLE `statuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tabel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `detail` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `order` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `statuses`
--

INSERT INTO `statuses` (`id`, `nama`, `tabel`, `detail`, `created_at`, `updated_at`, `order`) VALUES
(1, 'trip', 'trips', 'adalah status yang hanya melakukan kunjungan dan tidak melakukan order', NULL, NULL, 1),
(2, 'order', 'trips', 'adalah status yang melakukan kunjungan dan order dengan nama lain effective call', NULL, NULL, 2),
(3, 'active', 'customers', 'customer masih beroperasi sebagai penjual atau pembeli yang aktif', NULL, NULL, 1),
(4, 'inactive', 'customers', 'customer sudah tidak pernah aktif lagi sebagai penjual ( toko tidak berjualan lagi, toko dianggap tidak mampu melakukan pembelian lagi )', NULL, NULL, -1),
(5, 'disetujui', 'customerslimit', 'pengajuan limit customer terakhir disetujui oleh supervisor ( merubah limit pembelian sama dengan pengajuan limit pembelian ) ', NULL, NULL, 1),
(6, 'tidak disetujui', 'customerslimit', 'pengajuan limit customer terakhir tidak disetujui oleh supervisor ( kondisi yaitu limit pembelian berbeda dengan pengajuan limit pembelian berlangsung sampai adanya pengajuan ulang ) ', NULL, NULL, -1),
(7, 'diajukan', 'customerslimit', 'pengajuan limit customer sedang diajukan dan menunggu konfirmasi supervisor', NULL, NULL, 0),
(8, 'active', 'staffs', 'staff masih dinyatakan aktif dalam perusahaan\r\n', NULL, NULL, 1),
(9, 'inactive', 'staffs', 'staff dinyatakan tidak aktif lagi dalam perusahaan', NULL, NULL, -1),
(10, 'active', 'items', 'item masih diperjual-belikan didalam perusahaan', NULL, NULL, 1),
(11, 'inactive', 'items', 'item tidak lagi diperjual-belikan didalam perusahaan', NULL, NULL, -1),
(12, 'dikonfirmasi', 'returs', 'pengajuan retur telah dikonfirmasi oleh admin dan telah diproses juga:\r\ndisetujui = pemrosesan yang dilakukan adalah tukar guling atau potongan invoice\r\ntidak disetujui = pemrosesan selanjutnya yang dilakukan adalah tukar guling \r\npemrosesan ini dapat dilakukan dari sisi salesman maupun administrasi (NB: administrasi dapat melakukan kontak tersendiri pada customer untuk proses yang akan dilakukan)(NB: jika admin melakukan perubahan pada tipe retur disaat ada pengajuan retur maka tipe retur tersebut tidak dicatat pada profile customer)', NULL, NULL, 1),
(13, 'diajukan', 'returs', 'tenaga pengirim melakukan pengajuan retur barang', NULL, NULL, 0),
(14, 'active', 'orders', 'order telah dikonfirmasi dan sudah mengurangi stok', NULL, NULL, 1),
(15, 'inactive', 'orders', 'order masih merupakan sebuah draft', NULL, NULL, -1),
(16, 'active', 'events', 'event masih sedang berlangsung', NULL, NULL, 1),
(17, 'inactive', 'events', 'event tidak lagi berlangsung \r\n- event telah lewat hari berlakunya (cron)\r\n- event dinyatakan tidak berlaku oleh supervisor sendiri', NULL, NULL, -1),
(18, 'belum mulai', 'drop_events', 'event akan berlangsung', NULL, NULL, 0),
(19, 'diajukan customer', 'order_tracks', 'order dilakukan oleh customer sendiri dan menunggu salesman untuk meneruskan kepada admin', NULL, NULL, 0),
(20, 'diajukan salesman', 'order_tracks', 'order sudah diajukan oleh salesman dan menunggu konfirmasi admin', NULL, NULL, 1),
(21, 'dikonfirmasi admin', 'order_tracks', 'order sudah dikonfirmasi admin menunggu keberangkatan barang', NULL, NULL, 2),
(22, 'dalam perjalanan', 'order_tracks', 'order sudah dalam perjalanan ', NULL, NULL, 3),
(23, 'order telah sampai', 'order_tracks', 'order telah sampai dan menunggu konfirmasi admin', NULL, NULL, 4),
(24, 'order selesai', 'order_tracks', 'order telah selesai ', NULL, NULL, 5),
(25, 'order ditolak', 'order_tracks', 'order telah ditolak ', NULL, NULL, -1),
(26, 'deleted', 'events', 'event tidak lagi berlangsung dan dihapus ', NULL, NULL, -2);

-- --------------------------------------------------------

--
-- Table structure for table `trips`
--

CREATE TABLE `trips` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_customer` bigint(20) NOT NULL,
  `id_staff` bigint(20) NOT NULL,
  `alasan_penolakan` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `koordinat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `waktu_masuk` timestamp NULL DEFAULT NULL,
  `waktu_keluar` timestamp NULL DEFAULT NULL,
  `status` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `trips`
--

INSERT INTO `trips` (`id`, `id_customer`, `id_staff`, `alasan_penolakan`, `koordinat`, `waktu_masuk`, `waktu_keluar`, `status`, `created_at`, `updated_at`) VALUES
(1, 6, 4, NULL, '-8.2237269@111.7964117', '2022-07-18 06:04:43', '2022-07-18 06:05:32', 1, '2022-07-18 06:05:32', '2022-07-18 06:05:32'),
(2, 1, 4, 'belum dulu', '-6.135931@106.7837584', '2022-07-18 13:40:21', '2022-07-18 13:40:26', 1, '2022-07-18 13:40:26', '2022-07-18 13:40:26'),
(3, 2, 4, 'belum dulu', '-6.135931@106.7837584', '2022-07-18 13:42:09', '2022-07-18 13:42:47', 1, '2022-07-18 13:42:47', '2022-07-18 13:42:47'),
(4, 4, 7, 'belum dulu', '-6.1359379@106.7837597', '2022-07-18 13:44:55', '2022-07-18 13:44:57', 1, '2022-07-18 13:44:57', '2022-07-18 13:44:57'),
(5, 2, 4, NULL, '-6.1359344@106.7837601', '2022-07-18 13:50:29', '2022-07-18 13:56:17', 2, '2022-07-18 13:50:30', '2022-07-18 13:56:17'),
(6, 1, 4, NULL, '-6.1359337@106.7837626', '2022-07-18 14:00:37', '2022-07-18 14:02:56', 2, '2022-07-18 14:00:38', '2022-07-18 14:02:56'),
(7, 3, 4, NULL, '-6.1359337@106.7837626', '2022-07-18 14:03:24', '2022-07-18 14:10:44', 2, '2022-07-18 14:03:25', '2022-07-18 14:10:44'),
(8, 3, 7, NULL, '-6.1359337@106.7837626', '2022-07-18 14:14:57', '2022-07-18 14:16:38', 2, '2022-07-18 14:14:59', '2022-07-18 14:16:38'),
(9, 4, 7, NULL, '-6.1359338@106.7837629', '2022-07-18 14:17:49', '2022-07-18 14:20:26', 2, '2022-07-18 14:17:50', '2022-07-18 14:20:26'),
(10, 5, 7, NULL, '-6.1359382@106.7837622', '2022-07-18 14:20:37', '2022-07-18 14:21:46', 2, '2022-07-18 14:20:38', '2022-07-18 14:21:46'),
(11, 3, 7, NULL, '-6.1359354@106.783762', '2022-07-18 14:59:34', '2022-07-18 15:07:37', 2, '2022-07-18 14:59:35', '2022-07-18 15:07:37'),
(12, 3, 4, NULL, '-6.1359314@106.7837587', '2022-07-18 15:15:03', '2022-07-18 15:17:50', 2, '2022-07-18 15:15:04', '2022-07-18 15:17:50'),
(13, 3, 4, NULL, '-6.1359325@106.7837652', '2022-07-19 13:13:02', '2022-07-19 13:16:03', 2, '2022-07-19 13:13:03', '2022-07-19 13:16:03');

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
(1, 1, 'owner@gmail.com', NULL, '$2y$10$YgwFcCmfxN3geJqg8X9CQurqxeGfNJtf4xwBjr0rDl4RCPpbkkFie', 'staffs', NULL, '2022-07-15 23:50:41', '2022-07-19 13:34:19'),
(2, 2, 'supervisor@gmail.com', NULL, '$2y$10$bS.JRqyMPuszRA7sxWtZTeMkhzga.ks2frSsZ0E6teJAqnKqYSLGG', 'staffs', NULL, '2022-07-15 23:51:19', '2022-07-19 13:35:00'),
(3, 3, 'administrasi@gmail.com', NULL, '$2y$10$TdaRvcraSBilG3vUoC5HJeht2FC18h.F8PPbQZtdptYK6okytAUDa', 'staffs', NULL, '2022-07-15 23:51:54', '2022-07-19 13:35:40'),
(4, 4, 'salesman@gmail.com', NULL, '$2y$10$nmj0rYIuTR2gg9Klw3JjiuqYEnHh52HqsczHCq397BcfN/TaCllLS', 'staffs', NULL, '2022-07-15 23:52:11', '2022-07-19 13:37:18'),
(5, 5, 'shipper@gmail.com', NULL, '$2y$10$Da8r2yqylw2VcntsRJVW2.RYNYMI8EdveNKAieZQBxbdfoocDFzrK', 'staffs', NULL, '2022-07-15 23:52:32', '2022-07-19 13:38:33'),
(6, 1, 'customer1@gmail.com', NULL, '$2y$10$NCmCCdBtL6bSKh8VtrY1defHJ3f0brLm/PFKbY6IlsdIzzn5ZCg2e', 'customers', NULL, '2022-07-15 23:55:23', '2022-07-19 13:36:30'),
(7, 2, 'customer2@gmail.com', NULL, '$2y$10$WS07ME5SMQrsD/wkVN67.usR5q/wpAWB9a2CTa4V.re6gYgUWpGiK', 'customers', NULL, '2022-07-16 00:12:18', '2022-07-16 00:12:18'),
(8, 6, 'administrasi2@gmail.com', NULL, '$2y$10$kGjB7ScHZbRR2KBga2ahxua6fQOdk/whYCO3k/QmFVWjPbKRUFCTa', 'staffs', NULL, '2022-07-16 00:42:05', '2022-07-16 00:42:05'),
(9, 7, 'salesman2@gmail.com', NULL, '$2y$10$odsVuujA16yWcWt91naHg.vCfyA6YF7hweKV7ovu/sT5uXnXEjWNW', 'staffs', NULL, '2022-07-16 00:42:29', '2022-07-16 00:42:29'),
(10, 8, 'shipper2@gmail.com', NULL, '$2y$10$RH.ZetPSYB88PxPYlqYtmuSbagStoZUp3GpN9k087BKqgAnAH33Ei', 'staffs', NULL, '2022-07-16 00:42:58', '2022-07-16 00:42:58'),
(11, 9, 'supervisor2@gmail.com', NULL, '$2y$10$4fNi27UnS4JetaaDRBUkIeZwkPhQLcDxcvqjMpeTCNpGuRSZJWFtC', 'staffs', NULL, '2022-07-16 00:44:13', '2022-07-16 00:44:13'),
(12, 3, 'customer3@gmail.com', NULL, '$2y$10$puXX9p43O0DnGWHncc8NTO/K/vcDd9UWGpv0RHh0kAdJh5eMJ8due', 'customers', NULL, '2022-07-17 00:52:10', '2022-07-17 00:52:10'),
(13, 4, 'customer4@gmail.com', NULL, '$2y$10$uFb4cH2udo5LHRPiPbI7PeO6pq1E1DHvPJSsJa.PbJ4oDlk.G78C2', 'customers', NULL, '2022-07-18 13:44:57', '2022-07-18 13:44:57'),
(14, 5, 'customer5@gmail.com', NULL, '$2y$10$7UBm1I10VGhQ76zdyhflb.IZJhu5ytZTggY5vAL8bA.LG.RylNrV6', 'customers', NULL, '2022-07-18 13:46:32', '2022-07-18 13:46:32');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kapasitas_volume` int(11) NOT NULL,
  `kapasitas_harga` double DEFAULT NULL,
  `kode_kendaraan` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `nama`, `kapasitas_volume`, `kapasitas_harga`, `kode_kendaraan`, `created_at`, `updated_at`) VALUES
(1, 'pickup L300', 4600000, 20000000, 'n2331e', '2022-07-17 06:12:36', '2022-07-17 06:12:36'),
(2, 'CDE', 8900000, 40000000, 'n2331y', '2022-07-17 06:14:55', '2022-07-17 06:14:55'),
(3, 'truk', 15000000, 60000000, 'n2331s', '2022-07-17 06:15:37', '2022-07-17 06:15:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_types`
--
ALTER TABLE `customer_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `districts`
--
ALTER TABLE `districts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `histories`
--
ALTER TABLE `histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_tracks`
--
ALTER TABLE `order_tracks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `pengadaans`
--
ALTER TABLE `pengadaans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `returs`
--
ALTER TABLE `returs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `retur_types`
--
ALTER TABLE `retur_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staffs`
--
ALTER TABLE `staffs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff_roles`
--
ALTER TABLE `staff_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `statuses`
--
ALTER TABLE `statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trips`
--
ALTER TABLE `trips`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `customer_types`
--
ALTER TABLE `customer_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `districts`
--
ALTER TABLE `districts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `histories`
--
ALTER TABLE `histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `order_tracks`
--
ALTER TABLE `order_tracks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pengadaans`
--
ALTER TABLE `pengadaans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `returs`
--
ALTER TABLE `returs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `retur_types`
--
ALTER TABLE `retur_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `staffs`
--
ALTER TABLE `staffs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `staff_roles`
--
ALTER TABLE `staff_roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `statuses`
--
ALTER TABLE `statuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `trips`
--
ALTER TABLE `trips`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
