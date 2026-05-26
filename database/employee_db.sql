-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 26, 2026 at 09:03 AM
-- Server version: 12.2.2-MariaDB
-- PHP Version: 8.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `employee_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `accesses`
--

CREATE TABLE `accesses` (
  `id` bigint(20) NOT NULL,
  `id_role` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `read` varchar(16) DEFAULT NULL,
  `view` varchar(16) DEFAULT NULL,
  `create` varchar(16) DEFAULT NULL,
  `update` varchar(16) DEFAULT NULL,
  `delete` varchar(16) DEFAULT NULL,
  `publish` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_nopad_ci;

--
-- Dumping data for table `accesses`
--

INSERT INTO `accesses` (`id`, `id_role`, `id_menu`, `read`, `view`, `create`, `update`, `delete`, `publish`) VALUES
(13, 2, 1, 'all', 'all', 'none', 'none', 'none', 'none'),
(14, 2, 2, 'all', 'all', 'all', 'all', 'none', 'none'),
(15, 2, 3, 'all', 'all', 'all', 'all', 'all', 'all'),
(16, 2, 4, 'all', 'all', 'all', 'all', 'all', 'all'),
(17, 2, 5, 'all', 'all', 'all', 'all', 'all', 'all'),
(18, 4, 1, 'all', 'all', 'all', 'all', 'all', 'all'),
(19, 4, 2, 'all', 'all', 'all', 'all', 'all', 'all'),
(20, 4, 3, 'all', 'all', 'all', 'all', 'all', 'all'),
(21, 4, 4, 'all', 'all', 'all', 'all', 'all', 'all'),
(22, 4, 5, 'all', 'all', 'all', 'all', 'all', 'all'),
(23, 4, 6, 'only', 'only', 'only', 'only', 'only', 'only'),
(24, 1, 1, 'all', 'all', 'all', 'all', 'all', 'all'),
(25, 1, 2, 'all', 'all', 'all', 'all', 'all', 'all'),
(26, 1, 3, 'all', 'all', 'all', 'all', 'all', 'all'),
(27, 1, 4, 'all', 'all', 'all', 'all', 'all', 'all'),
(28, 1, 5, 'all', 'all', 'all', 'all', 'all', 'all'),
(29, 1, 6, 'all', 'all', 'all', 'all', 'all', 'all'),
(30, 1, 7, 'all', 'all', 'all', 'all', 'all', 'all'),
(37, 3, 1, 'all', 'all', 'only', 'none', 'none', 'none'),
(38, 3, 2, 'only', 'only', 'none', 'none', 'none', 'none'),
(39, 3, 4, 'only', 'only', 'none', 'none', 'none', 'none'),
(40, 3, 7, 'only', 'only', 'none', 'none', 'none', 'none'),
(41, 5, 1, 'all', 'none', 'none', 'none', 'none', 'none');

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `id` bigint(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` mediumtext DEFAULT NULL,
  `ua` varchar(256) DEFAULT NULL,
  `ip` varchar(64) DEFAULT NULL,
  `url` text DEFAULT NULL,
  `browser` varchar(64) DEFAULT NULL,
  `platform` varchar(64) DEFAULT NULL,
  `negara` varchar(64) DEFAULT NULL,
  `provinsi` varchar(64) DEFAULT NULL,
  `kabupaten` varchar(64) DEFAULT NULL,
  `kecamatan` varchar(64) DEFAULT NULL,
  `kelurahan` varchar(64) DEFAULT NULL,
  `latitude` double UNSIGNED DEFAULT NULL,
  `longitude` double UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_nopad_ci;

-- --------------------------------------------------------

--
-- Table structure for table `child_data`
--

CREATE TABLE `child_data` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_nopad_ci;

--
-- Dumping data for table `child_data`
--

INSERT INTO `child_data` (`id`, `parent_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, 'Anak Pertama Budi', NULL, NULL),
(2, 1, 'Anak Kedua Budi', NULL, NULL),
(3, 2, 'Anak Pertama Budi dari Ibu', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` char(36) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `employee_code` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `birth_place` varchar(255) NOT NULL,
  `birth_date` date NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `marital_status` enum('kawin','tidak kawin') NOT NULL,
  `children_count` int(11) NOT NULL DEFAULT 0,
  `kecamatan` varchar(255) NOT NULL,
  `kabupaten` varchar(255) NOT NULL,
  `provinsi` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `distance_km` decimal(8,2) NOT NULL DEFAULT 0.00,
  `position` enum('manager','staf','magang') NOT NULL,
  `employment_status` enum('contract','permanent','intern') NOT NULL,
  `department` enum('marketing','hrd','production','executive','commissioner') NOT NULL,
  `join_date` date NOT NULL,
  `resign_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_nopad_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `photo`, `employee_code`, `full_name`, `email`, `phone`, `birth_place`, `birth_date`, `gender`, `marital_status`, `children_count`, `kecamatan`, `kabupaten`, `provinsi`, `address`, `distance_km`, `position`, `employment_status`, `department`, `join_date`, `resign_date`, `is_active`, `created_at`, `updated_at`) VALUES
('0a9a6b6a-3b4b-4b2b-8b1b-0b9b8b7b6b5b', NULL, 'EMP-001', 'Budi Santoso Siregar', 'budi.santoso@example.com', '081234567890', 'Jakarta', '1990-01-01', 'male', 'kawin', 2, 'Cilandak', 'Jakarta Selatan', 'DKI Jakarta', 'Jl. TB Simatupang No. 1', 10.50, 'manager', 'permanent', 'executive', '2015-01-15', NULL, 1, NULL, '2026-05-21 08:58:55'),
('1b8b7b6b-4c5c-5c3c-9c2c-1c8c7c6c5c4c', NULL, 'EMP-002', 'Ani Yudhoyono', 'ani.yudhoyono@example.com', '081234567891', 'Bandung', '1992-02-02', 'female', 'tidak kawin', 0, 'Coblong', 'Bandung', 'Jawa Barat', 'Jl. Dago No. 2', 5.20, 'staf', 'permanent', 'marketing', '2018-03-20', NULL, 1, NULL, NULL),
('1fc61e0b-683d-457c-bf5f-611d306fa6ec', NULL, 'EMP-023', 'Alex Lanang', 'asaas2@gmail.com', '082351082001', 'Mana', '2006-04-22', 'male', 'tidak kawin', 0, 'Coblong', 'Yogyakarata', 'Yogyakarta', 'Jln. Sorosuton pakelrejo UH 6', 12.00, 'magang', 'intern', 'production', '2026-05-22', NULL, 1, '2026-05-22 00:59:22', '2026-05-22 00:59:22'),
('2c7c6c5c-5d6d-6d4d-0d3d-2d7d6d5d4d3d', NULL, 'EMP-003', 'Cici Paramida', 'cici.paramida@example.com', '081234567892', 'Surabaya', '1995-03-03', 'female', 'tidak kawin', 0, 'Gubeng', 'Surabaya', 'Jawa Timur', 'Jl. Raya Gubeng No. 3', 15.00, 'staf', 'contract', 'production', '2020-07-01', NULL, 1, NULL, NULL),
('34d2a53c-c7bd-4c4b-a9f2-adb03ebe52f8', 'employees/strc0P3DN2o4GTCSLd0pyRpW4d0ttxC1WZ5AhttP.png', 'EMP-020', 'Ani Yudhoyono Asal', 'ekopatrio@gmail.com', '0812345679128', 'Mana AJa', '2004-04-16', 'male', 'kawin', 0, 'asasa', 'asasa', 'asasas', 'Jl. Dago No. 2asasas', 0.00, 'staf', 'contract', 'production', '2026-04-21', NULL, 1, '2026-05-21 09:02:55', '2026-05-21 20:00:23'),
('3d6d5d4d-6e7e-7e5e-1e4e-3d6d5d4d3d2d', NULL, 'EMP-004', 'Dedi Mizwar', 'dedi.mizwar@example.com', '081234567893', 'Medan', '1988-04-04', 'male', 'kawin', 3, 'Medan Baru', 'Medan', 'Sumatera Utara', 'Jl. Jamin Ginting No. 4', 8.70, 'manager', 'permanent', 'hrd', '2014-11-10', NULL, 1, NULL, NULL),
('4e5e4e3e-7f8f-8f6f-2f5f-4e5e4e3e2e1e', NULL, 'EMP-005', 'Eko Patrio', 'eko.patrio@example.com', '081234567894', 'Semarang', '2000-05-05', 'male', 'tidak kawin', 0, 'Banyumanik', 'Semarang', 'Jawa Tengah', 'Jl. Setiabudi No. 5', 2.10, 'magang', 'intern', 'hrd', '2023-01-10', NULL, 1, NULL, NULL),
('9c858eb0-86b4-4dcb-a624-632ad1d6ce4d', NULL, 'EMP-123', 'Agus Kusnandar Triplesix', 'exam@wem.com', '08456788765', 'jauh', '2000-02-10', 'male', 'kawin', 2, 'Jauh', 'jauh', 'jauh Banget', 'jauh', 3.00, 'staf', 'contract', 'production', '2026-02-10', NULL, 1, '2026-05-25 18:48:59', '2026-05-26 01:28:59'),
('f5aaef98-8ccd-4c74-9990-d0ed3b939d76', NULL, 'EMP-022', 'Aldi Tulus Pribadi', 'aldtls223@gmail.com', '082351082000', 'Magetan', '2004-03-10', 'male', 'tidak kawin', 0, 'Coblong', 'Yogyakarata', 'Yogyakarta', 'Jln. Sorosuton pakelrejo UH 6', 10.50, 'magang', 'contract', 'hrd', '2025-08-18', NULL, 1, '2026-05-21 20:07:51', '2026-05-22 00:59:48');

-- --------------------------------------------------------

--
-- Table structure for table `employee_educations`
--

CREATE TABLE `employee_educations` (
  `id` char(36) NOT NULL,
  `employee_id` char(36) NOT NULL,
  `level` varchar(255) NOT NULL,
  `institution` varchar(255) NOT NULL,
  `major` varchar(255) DEFAULT NULL,
  `graduation_year` year(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_nopad_ci;

--
-- Dumping data for table `employee_educations`
--

INSERT INTO `employee_educations` (`id`, `employee_id`, `level`, `institution`, `major`, `graduation_year`, `created_at`, `updated_at`) VALUES
('019e4e97-cd5e-7314-b388-e40b0fecb1df', '0a9a6b6a-3b4b-4b2b-8b1b-0b9b8b7b6b5b', 'S1', 'Universitas Indonesia', 'Manajemen', '2012', '2026-05-22 00:30:31', '2026-05-22 00:30:31'),
('ba0681ea-54ec-11f1-ad38-e89c2591fed9', '1b8b7b6b-4c5c-5c3c-9c2c-1c8c7c6c5c4c', 'S1', 'Institut Teknologi Bandung', 'Desain Komunikasi Visual', '2014', NULL, NULL),
('ba068269-54ec-11f1-ad38-e89c2591fed9', '1b8b7b6b-4c5c-5c3c-9c2c-1c8c7c6c5c4c', 'SMA', 'SMA Negeri 3 Bandung', 'IPA', '2010', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `file`
--

CREATE TABLE `file` (
  `id` bigint(20) NOT NULL,
  `name` varchar(256) NOT NULL,
  `mime` varchar(256) NOT NULL,
  `size` varchar(32) DEFAULT NULL,
  `path` text NOT NULL,
  `parent_id` char(36) NOT NULL,
  `parent_table` varchar(256) NOT NULL,
  `parent_field` varchar(256) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `updated_by` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_nopad_ci;

--
-- Dumping data for table `file`
--

INSERT INTO `file` (`id`, `name`, `mime`, `size`, `path`, `parent_id`, `parent_table`, `parent_field`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'Aldi.png', 'image/png', '944245', '2026/05/22/0307516a0fcad3e3893.png', 'f5aaef98-8ccd-4c74-9990-d0ed3b939d76', 'employees', 'photo', '2026-05-22 03:17:27', '2026-05-22 03:17:39', NULL, NULL),
(2, 'Sekolah.jpg', 'image/jpeg', '60847', '2026/05/22/0730316a1006171aa8e.jpg', '0a9a6b6a-3b4b-4b2b-8b1b-0b9b8b7b6b5b', 'employees', 'photo', '2026-05-22 07:30:31', '2026-05-22 07:30:31', NULL, NULL),
(3, '1779023122983.png', 'image/png', '249222', '2026/05/21/1602556a13f57d95993.png', '34d2a53c-c7bd-4c4b-a9f2-adb03ebe52f8', 'employees', 'photo', '2026-05-25 07:08:45', '2026-05-25 07:08:45', NULL, NULL),
(4, 'maxresdefault-4042709886.jpg', 'image/jpeg', '129301', '2026/05/26/0148596a14fc54ba028.jpg', '9c858eb0-86b4-4dcb-a624-632ad1d6ce4d', 'employees', 'photo', '2026-05-26 01:50:12', '2026-05-26 01:50:12', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` int(11) NOT NULL,
  `id_menu` int(11) DEFAULT NULL,
  `name` varchar(128) NOT NULL,
  `type` varchar(16) NOT NULL,
  `status` varchar(16) NOT NULL,
  `route_name` varchar(256) DEFAULT NULL,
  `route_params` varchar(256) DEFAULT NULL,
  `href` varchar(256) DEFAULT NULL,
  `sort` tinyint(4) DEFAULT NULL,
  `icon` varchar(128) DEFAULT NULL,
  `target` varchar(8) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_nopad_ci ROW_FORMAT=COMPACT;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `id_menu`, `name`, `type`, `status`, `route_name`, `route_params`, `href`, `sort`, `icon`, `target`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Dashboard', 'owner_sidebar', 'publish', 'dashboard', NULL, NULL, 1, '<i class=\"fa fa-home\"></i>', NULL, NULL, NULL),
(2, NULL, 'Employees', 'owner_sidebar', 'publish', 'employees.index', NULL, NULL, 2, '<i class=\"fa fa-users\"></i>', NULL, NULL, NULL),
(3, NULL, 'HR', 'owner_sidebar', 'publish', NULL, NULL, NULL, 3, '<i class=\"fa fa-folder\"></i>', NULL, NULL, NULL),
(4, 3, 'Transport Allowances', 'owner_sidebar', 'publish', 'transport-allowances.index', NULL, NULL, 1, '<i class=\"fa fa-money-bill-wave\"></i>', NULL, NULL, NULL),
(5, 3, 'Settings', 'owner_sidebar', 'publish', 'transport-settings.index', NULL, NULL, 2, '<i class=\"fa fa-cog\"></i>', NULL, NULL, NULL),
(6, NULL, 'Role & Permission', 'owner_sidebar', 'publish', 'role-permission.index', '', NULL, 4, '<i class=\"fa fa-cog\"></i>', NULL, NULL, NULL),
(7, NULL, 'User', 'owner_sidebar', 'publish', 'users.index', NULL, NULL, 7, '<i class=\"fa fa-user\"></i>', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifies`
--

CREATE TABLE `notifies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_user` char(36) NOT NULL,
  `content` text NOT NULL,
  `status` varchar(16) NOT NULL DEFAULT 'unread',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `parent_data`
--

CREATE TABLE `parent_data` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_nopad_ci;

--
-- Dumping data for table `parent_data`
--

INSERT INTO `parent_data` (`id`, `employee_id`, `name`, `created_at`, `updated_at`) VALUES
(1, '0a9a6b6a-3b4b-4b2b-8b1b-0b9b8b7b6b5b', 'Ayah Budi', NULL, NULL),
(2, '0a9a6b6a-3b4b-4b2b-8b1b-0b9b8b7b6b5b', 'Ibu Budi', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_nopad_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', '2026-05-21 15:11:54', '2026-05-21 15:11:54'),
(2, 'Admin HRD', '2026-05-21 15:11:54', '2026-05-21 15:11:54'),
(3, 'Employee', '2026-05-21 15:11:54', '2026-05-21 15:11:54'),
(4, 'Manager HR', '2026-05-21 14:01:51', '2026-05-21 14:01:51'),
(5, 'Role Baru', '2026-05-26 02:03:47', '2026-05-26 02:03:47');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transport_allowances`
--

CREATE TABLE `transport_allowances` (
  `id` char(36) NOT NULL,
  `employee_id` char(36) NOT NULL,
  `month` tinyint(4) NOT NULL,
  `year` int(11) NOT NULL,
  `base_fare` decimal(15,2) NOT NULL,
  `distance_km` decimal(8,2) NOT NULL,
  `work_days` int(11) NOT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `updated_by` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_nopad_ci;

--
-- Dumping data for table `transport_allowances`
--

INSERT INTO `transport_allowances` (`id`, `employee_id`, `month`, `year`, `base_fare`, `distance_km`, `work_days`, `total_amount`, `notes`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
('019e4eaa-00bc-7117-875f-3b6132e133f5', '0a9a6b6a-3b4b-4b2b-8b1b-0b9b8b7b6b5b', 5, 2026, 200000.00, 10.50, 20, 4000000.00, 'Layak', 'a1b2c3d4-e5f6-a7b8-c9d0-e1f2a3b4c5d6', NULL, '2026-05-22 00:50:24', '2026-05-22 00:50:24'),
('019e61fa-11dd-72cf-b16d-7330aef8c050', '9c858eb0-86b4-4dcb-a624-632ad1d6ce4d', 1, 2026, 200000.00, 3.00, 20, 0.00, 'Tidak layak: pegawai non-tetap. Tidak layak: jarak minimal harus lebih dari 5 km.', 'a1b2c3d4-e5f6-a7b8-c9d0-e1f2a3b4c5d6', NULL, '2026-05-25 18:50:39', '2026-05-25 18:50:39');

-- --------------------------------------------------------

--
-- Table structure for table `transport_settings`
--

CREATE TABLE `transport_settings` (
  `id` char(36) NOT NULL,
  `base_fare` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_by` char(36) DEFAULT NULL,
  `updated_by` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_nopad_ci;

--
-- Dumping data for table `transport_settings`
--

INSERT INTO `transport_settings` (`id`, `base_fare`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
('ba074f84-54ec-11f1-ad38-e89c2591fed9', 250000.00, 'a1b2c3d4-e5f6-a7b8-c9d0-e1f2a3b4c5d6', 'a1b2c3d4-e5f6-a7b8-c9d0-e1f2a3b4c5d6', NULL, '2026-05-25 18:54:56');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` char(36) NOT NULL,
  `employee_id` char(36) DEFAULT NULL,
  `role_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_nopad_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `employee_id`, `role_id`, `username`, `email`, `phone`, `password`, `is_active`, `remember_token`, `last_login_at`, `created_at`, `updated_at`) VALUES
('8a4923c7-8fa3-4c27-b6ae-703a442a86e7', '4e5e4e3e-7f8f-8f6f-2f5f-4e5e4e3e2e1e', 2, 'ekopatrio', 'ekopatrio@gmail.com', '08213123121', '$2y$12$LAHUteefDjHykJ/Df79H8ecycb7CYgbO.xTKOeIGQF7BfpqVn2MMi', 1, NULL, NULL, '2026-05-21 07:29:20', '2026-05-25 02:47:14'),
('a1b2c3d4-e5f6-a7b8-c9d0-e1f2a3b4c5d6', '0a9a6b6a-3b4b-4b2b-8b1b-0b9b8b7b6b5b', 1, 'superadmin', 'superadmin@example.com', '081111111111', '$2y$12$4m6iWfHldxEI1tW.MjW1Ve0JNNkGjm0vtXN7eEVTQuYU3ve8Hl07C', 1, NULL, NULL, NULL, '2026-05-21 05:52:58'),
('b2c3d4e5-f6a7-b8c9-d0e1-f2a3b4c5d6a7', '3d6d5d4d-6e7e-7e5e-1e4e-3d6d5d4d3d2d', 2, 'adminhrd', 'adminhrd@example.com', '082222222222', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0, NULL, NULL, NULL, '2026-05-25 01:44:38'),
('c3d4e5f6-a7b8-c9d0-e1f2-a3b4c5d6a7b8', '1b8b7b6b-4c5c-5c3c-9c2c-1c8c7c6c5c4c', 3, 'aniyudhoyono', 'ani.yudhoyono@example.com', '081234567891', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0, NULL, NULL, NULL, '2026-05-25 01:44:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accesses`
--
ALTER TABLE `accesses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unik` (`id_role`,`id_menu`),
  ADD KEY `web_level_admin_menus_ibfk_1` (`id_menu`);

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `child_data`
--
ALTER TABLE `child_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `child_data_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_code` (`employee_code`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD KEY `employees_employee_code_index` (`employee_code`),
  ADD KEY `employees_full_name_index` (`full_name`),
  ADD KEY `employees_gender_index` (`gender`),
  ADD KEY `employees_marital_status_index` (`marital_status`),
  ADD KEY `employees_position_index` (`position`),
  ADD KEY `employees_employment_status_index` (`employment_status`),
  ADD KEY `employees_department_index` (`department`),
  ADD KEY `employees_join_date_index` (`join_date`),
  ADD KEY `employees_is_active_index` (`is_active`);

--
-- Indexes for table `employee_educations`
--
ALTER TABLE `employee_educations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_educations_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `file`
--
ALTER TABLE `file`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `index` (`parent_id`,`parent_table`,`parent_field`) USING BTREE;

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_menu` (`id_menu`) USING BTREE,
  ADD KEY `indek` (`id_menu`,`type`,`status`) USING BTREE;

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifies`
--
ALTER TABLE `notifies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifies_id_user_status_index` (`id_user`,`status`);

--
-- Indexes for table `parent_data`
--
ALTER TABLE `parent_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_data_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `transport_allowances`
--
ALTER TABLE `transport_allowances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_id` (`employee_id`,`month`,`year`),
  ADD KEY `transport_allowances_created_by_foreign` (`created_by`),
  ADD KEY `transport_allowances_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `transport_settings`
--
ALTER TABLE `transport_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transport_settings_created_by_foreign` (`created_by`),
  ADD KEY `transport_settings_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `employee_id` (`employee_id`),
  ADD KEY `idx_users_employee_id` (`employee_id`),
  ADD KEY `idx_users_role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accesses`
--
ALTER TABLE `accesses`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `child_data`
--
ALTER TABLE `child_data`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `file`
--
ALTER TABLE `file`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifies`
--
ALTER TABLE `notifies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `parent_data`
--
ALTER TABLE `parent_data`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accesses`
--
ALTER TABLE `accesses`
  ADD CONSTRAINT `id_menu_fk` FOREIGN KEY (`id_menu`) REFERENCES `menus` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `id_role_fk` FOREIGN KEY (`id_role`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `child_data`
--
ALTER TABLE `child_data`
  ADD CONSTRAINT `child_data_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `parent_data` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `employee_educations`
--
ALTER TABLE `employee_educations`
  ADD CONSTRAINT `employee_educations_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `file`
--
ALTER TABLE `file`
  ADD CONSTRAINT `file_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `file_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `menus`
--
ALTER TABLE `menus`
  ADD CONSTRAINT `menus_id_menu_fk` FOREIGN KEY (`id_menu`) REFERENCES `menus` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `parent_data`
--
ALTER TABLE `parent_data`
  ADD CONSTRAINT `parent_data_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transport_allowances`
--
ALTER TABLE `transport_allowances`
  ADD CONSTRAINT `transport_allowances_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `transport_allowances_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transport_allowances_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `transport_settings`
--
ALTER TABLE `transport_settings`
  ADD CONSTRAINT `transport_settings_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `transport_settings_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
