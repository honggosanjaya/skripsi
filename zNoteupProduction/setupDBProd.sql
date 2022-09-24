INSERT INTO `retur_types` (`id`, `nama`, `detail`, `created_at`, `updated_at`) VALUES
(1, 'potongan', 'potongan terhadap invoice yang telah selesai dan belum lunas', NULL, NULL),
(2, 'tukar guling', 'pertukaran barang saja', NULL, NULL);

INSERT INTO `staff_roles` (`id`, `nama`, `detail`, `created_at`, `updated_at`) VALUES
(1, 'owner', 'pemilik (hanya boleh 1)', NULL, NULL),
(2, 'supervisor', 'penanggung jawab (hanya boleh 1 yang aktif)', NULL, NULL),
(3, 'salesman', 'tenaga penjual', NULL, NULL),
(4, 'shipper', 'tenaga pengirim', NULL, NULL),
(5, 'administrasi', 'admin', NULL, NULL);