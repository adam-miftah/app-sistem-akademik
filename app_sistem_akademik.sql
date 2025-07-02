-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 02, 2025 at 07:46 PM
-- Server version: 8.0.30
-- PHP Version: 8.3.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `app_sistem_akademik`
--

-- --------------------------------------------------------

--
-- Table structure for table `absen_dosens`
--

CREATE TABLE `absen_dosens` (
  `id` bigint UNSIGNED NOT NULL,
  `dosen_id` bigint UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `waktu_masuk` time DEFAULT NULL,
  `waktu_keluar` time DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `absen_dosens`
--

INSERT INTO `absen_dosens` (`id`, `dosen_id`, `tanggal`, `waktu_masuk`, `waktu_keluar`, `status`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 3, '2025-06-23', '03:06:00', '03:06:00', 'Hadir', 'Absen masuk & keluar otomatis', '2025-06-22 20:06:38', '2025-06-22 20:06:42');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Semua',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `user_id`, `title`, `content`, `target_role`, `created_at`, `updated_at`) VALUES
(1, 1, 'TEST', 'HALLO BANG', 'Mahasiswa', '2025-06-22 23:02:47', '2025-06-22 23:02:47'),
(2, 1, 'TEST', 'HALLO BROOOOO', 'Dosen', '2025-06-23 03:15:43', '2025-06-23 03:15:43');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dosens`
--

CREATE TABLE `dosens` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nidn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prodi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dosens`
--

INSERT INTO `dosens` (`id`, `user_id`, `nama`, `email`, `nidn`, `prodi`, `created_at`, `updated_at`) VALUES
(1, 3, 'IIS AISYAH S.Kom., M.Kom', 'dosen001@gmail.com', '0320039201', 'Teknik Informatika', '2025-06-22 17:32:56', '2025-06-22 17:32:56'),
(2, 4, 'RENGGA ERLANGGA S.Kom., M.Kom.', 'dosen002@gmail.com', '0416068807', 'Teknik Informatika', '2025-06-22 17:33:49', '2025-06-22 17:33:49'),
(3, 5, 'AFIANI AGUS ABDILLAH S.Kom., M.Kom.', 'dosen003@gmail.com', '0358774675230213', 'Teknik Informatika', '2025-06-22 17:34:33', '2025-06-22 17:34:33'),
(4, 6, 'ICHSAN RAMDHANI S.TP.,M.T.I', 'dosen004@gmail.com', '0412088506', 'Teknik Informatika', '2025-06-22 17:35:13', '2025-06-22 17:35:13'),
(5, 7, 'YULIANTI S.Kom., M.Kom.', 'dosen005@gmail.com', '0430079201', 'Teknik Informatika', '2025-06-22 17:36:19', '2025-06-22 17:36:19'),
(6, 8, 'YESKARWANI GULO S.Kom., M.Kom.', 'dosen006@gmail.com', '0412109303', 'Teknik Informatika', '2025-06-22 17:37:00', '2025-06-22 17:37:00'),
(7, 9, 'HADI ZAKARIA S.Kom.,M.Kom.,M.M.', 'dosen007@gmail.com', '0401066503', 'Teknik Informatika', '2025-06-22 17:37:44', '2025-06-22 17:37:44'),
(8, 10, 'TOMI HIDAYAT S.KOM., M.KOM.', 'dosen008@gmail.com', '0428128706', 'Teknik Informatika', '2025-06-22 17:38:24', '2025-06-22 17:38:24');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_kuliahs`
--

CREATE TABLE `jadwal_kuliahs` (
  `id` bigint UNSIGNED NOT NULL,
  `mata_kuliah_id` bigint UNSIGNED NOT NULL,
  `dosen_id` bigint UNSIGNED NOT NULL,
  `hari` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `ruangan` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kelas` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jadwal_kuliahs`
--

INSERT INTO `jadwal_kuliahs` (`id`, `mata_kuliah_id`, `dosen_id`, `hari`, `jam_mulai`, `jam_selesai`, `ruangan`, `kelas`, `created_at`, `updated_at`) VALUES
(1, 10, 1, 'Senin', '08:50:00', '10:30:00', 'V.530', '06TPLP009', '2025-06-22 18:40:48', '2025-06-22 18:40:48'),
(2, 6, 2, 'Senin', '10:30:00', '12:10:00', 'V.530', '06TPLP009', '2025-06-22 18:42:09', '2025-06-22 18:42:09'),
(3, 11, 3, 'Senin', '13:00:00', '14:40:00', 'V.530', '06TPLP009', '2025-06-22 18:42:54', '2025-06-22 18:42:54'),
(4, 8, 4, 'Selasa', '08:50:00', '10:30:00', 'V.530', '06TPLP009', '2025-06-22 18:43:41', '2025-06-22 18:43:41'),
(5, 7, 5, 'Selasa', '13:00:00', '14:40:00', 'V.530', '06TPLP009', '2025-06-22 18:44:25', '2025-06-22 18:44:25'),
(6, 4, 6, 'Rabu', '08:50:00', '10:30:00', 'V.530', '06TPLP009', '2025-06-22 18:45:13', '2025-06-22 18:45:13'),
(7, 5, 7, 'Jumat', '10:30:00', '12:10:00', 'V.530', '06TPLP009', '2025-06-22 18:45:49', '2025-06-22 18:45:49'),
(8, 9, 8, 'Jumat', '14:40:00', '16:20:00', 'V.530', '06TPLP009', '2025-06-22 18:46:17', '2025-06-22 18:46:17');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswas`
--

CREATE TABLE `mahasiswas` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `nim` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jurusan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `program_studi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prog_perkuliahan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kelas` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_mahasiswa` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Aktif',
  `angkatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telepon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mahasiswas`
--

INSERT INTO `mahasiswas` (`id`, `user_id`, `nim`, `nama`, `jurusan`, `program_studi`, `prog_perkuliahan`, `kelas`, `status_mahasiswa`, `angkatan`, `tanggal_lahir`, `email`, `telepon`, `alamat`, `created_at`, `updated_at`) VALUES
(3, 12, '221011400961', 'ADAM MIFTAHUL FALAH', 'teknik informatika', NULL, NULL, '06TPLP009', 'aktif', '2021', NULL, 'mahasiswa001@gmail.com', NULL, NULL, '2025-06-22 18:31:57', '2025-06-22 18:31:57'),
(4, 13, '221011403065', 'AFFAN SHUJA', 'teknik informatika', NULL, NULL, '06TPLP009', 'aktif', '2021', NULL, 'mahasiswa002@gmail.com', NULL, NULL, '2025-06-22 18:31:58', '2025-06-22 18:31:58'),
(5, 14, '221011402675', 'ALVEN ARYA SENA', 'teknik informatika', NULL, NULL, '06TPLP009', 'aktif', '2021', NULL, 'mahasiswa003@gmail.com', NULL, NULL, '2025-06-22 18:31:58', '2025-06-22 18:31:58'),
(6, 15, '221011401374', 'ALWI AL AGIV', 'teknik informatika', NULL, NULL, '06TPLP009', 'aktif', '2021', NULL, 'mahasiswa004@gmail.com', NULL, NULL, '2025-06-22 18:31:59', '2025-06-22 18:31:59'),
(7, 16, '221011402905', 'DEVITA MAULINA CAHYANI', 'teknik informatika', NULL, NULL, '06TPLP009', 'aktif', '2021', NULL, 'mahasiswa005@gmail.com', NULL, NULL, '2025-06-22 18:31:59', '2025-06-22 18:31:59'),
(8, 17, '221011402272', 'DONY SAPTA NURHAYADI', 'teknik informatika', NULL, NULL, '06TPLP009', 'aktif', '2021', NULL, 'mahasiswa006@gmail.com', NULL, NULL, '2025-06-22 18:31:59', '2025-06-22 18:31:59'),
(9, 18, '221011400399', 'EKA SETYA NINGSIH', 'teknik informatika', NULL, NULL, '06TPLP009', 'aktif', '2021', NULL, 'mahasiswa007@gmail.com', NULL, NULL, '2025-06-22 18:32:00', '2025-06-22 18:32:00'),
(10, 19, '221011401924', 'FAJRI HERNANDA SARIFUDIN', 'teknik informatika', NULL, NULL, '06TPLP009', 'aktif', '2021', NULL, 'mahasiswa008@gmail.com', NULL, NULL, '2025-06-22 18:32:00', '2025-06-22 18:32:00'),
(11, 20, '221011402652', 'FARHAN ASSHADATH', 'teknik informatika', NULL, NULL, '06TPLP009', 'aktif', '2021', NULL, 'mahasiswa009@gmail.com', NULL, NULL, '2025-06-22 18:32:01', '2025-06-22 18:32:01'),
(12, 21, '221011403311', 'FIKRI ADRIANSYAH', 'teknik informatika', NULL, NULL, '06TPLP009', 'aktif', '2021', NULL, 'mahasiswa0010@gmail.com', NULL, NULL, '2025-06-22 18:32:01', '2025-06-22 18:32:01'),
(13, 22, '221011402076', 'KEZIA PATRICIA ZEFANYA', 'teknik informatika', NULL, NULL, '06TPLP009', 'aktif', '2021', NULL, 'mahasiswa0011@gmail.com', NULL, NULL, '2025-06-22 18:32:02', '2025-06-22 18:32:02'),
(14, 23, '221011402056', 'MARSANDY RULIAN', 'teknik informatika', NULL, NULL, '06TPLP009', 'aktif', '2021', NULL, 'mahasiswa0012@gmail.com', NULL, NULL, '2025-06-22 18:32:02', '2025-06-22 18:32:02'),
(15, 24, '221011400397', 'MIFTAH FATHU RAMADHAN', 'teknik informatika', NULL, NULL, '06TPLP009', 'aktif', '2021', NULL, 'mahasiswa0013@gmail.com', NULL, NULL, '2025-06-22 18:32:03', '2025-06-22 18:32:03'),
(16, 25, '221011400378', 'MOCHAMMAD IQBAL SUYUDI WIJAYA', 'teknik informatika', NULL, NULL, '06TPLP009', 'aktif', '2021', NULL, 'mahasiswa0014@gmail.com', NULL, NULL, '2025-06-22 18:32:03', '2025-06-22 18:32:03'),
(17, 26, '221011400390', 'MU`AMMAR KURNIAWAN', 'teknik informatika', NULL, NULL, '06TPLP009', 'aktif', '2021', NULL, 'mahasiswa0015@gmail.com', NULL, NULL, '2025-06-22 18:32:04', '2025-06-22 18:32:04'),
(18, 27, '221011402222', 'MUCHAMAD UBAYDILLAH', 'teknik informatika', NULL, NULL, '06TPLP009', 'aktif', '2021', NULL, 'mahasiswa0016@gmail.com', NULL, NULL, '2025-06-22 18:32:04', '2025-06-22 18:32:04'),
(19, 28, '221011400375', 'MUHAMMAD AQIL FARHAN', 'teknik informatika', NULL, NULL, '06TPLP009', 'aktif', '2021', NULL, 'mahasiswa0017@gmail.com', NULL, NULL, '2025-06-22 18:32:05', '2025-06-22 18:32:05'),
(20, 29, '221011402074', 'MUHAMMAD IQBAL BINTANG', 'teknik informatika', NULL, NULL, '06TPLP009', 'aktif', '2021', NULL, 'mahasiswa0018@gmail.com', NULL, NULL, '2025-06-22 18:32:05', '2025-06-22 18:32:05'),
(21, 30, '221011400396', 'MUHAMMAD MICKO BIAGI', 'teknik informatika', NULL, NULL, '06TPLP009', 'aktif', '2021', NULL, 'mahasiswa0019@gmail.com', NULL, NULL, '2025-06-22 18:32:06', '2025-06-22 18:32:06'),
(22, 31, '221011402179', 'MUHAMMAD RAFLI', 'teknik informatika', NULL, NULL, '06TPLP009', 'aktif', '2021', NULL, 'mahasiswa0020@gmail.com', NULL, NULL, '2025-06-22 18:32:06', '2025-06-22 18:32:06'),
(23, 32, '221011401571', 'NABILA ZAHROTUL JANNAH', 'teknik informatika', NULL, NULL, '06TPLP009', 'aktif', '2021', NULL, 'mahasiswa0021@gmail.com', NULL, NULL, '2025-06-22 18:32:07', '2025-06-22 18:32:07'),
(24, 33, '221011401756', 'PUTRI AWALIYATUNNIZA', 'teknik informatika', NULL, NULL, '06TPLP009', 'aktif', '2021', NULL, 'mahasiswa0022@gmail.com', NULL, NULL, '2025-06-22 18:32:07', '2025-06-22 18:32:07'),
(25, 34, '221011401856', 'RAHMAD RAIHAN AMSYAH', 'teknik informatika', NULL, NULL, '06TPLP009', 'aktif', '2021', NULL, 'mahasiswa0023@gmail.com', NULL, NULL, '2025-06-22 18:32:08', '2025-06-22 18:32:08'),
(26, 35, '221011401732', 'RIANA DAMAYANTI', 'teknik informatika', NULL, NULL, '06TPLP009', 'aktif', '2021', NULL, 'mahasiswa0024@gmail.com', NULL, NULL, '2025-06-22 18:32:08', '2025-06-22 18:32:08'),
(27, 36, '221011402908', 'RIFQY SATRIO NAVIANTO', 'teknik informatika', NULL, NULL, '06TPLP009', 'aktif', '2021', NULL, 'mahasiswa0025@gmail.com', NULL, NULL, '2025-06-22 18:32:08', '2025-06-22 18:32:08'),
(28, 37, '221011402191', 'RIZKI AUFARRAMDHI', 'teknik informatika', NULL, NULL, '06TPLP009', 'aktif', '2021', NULL, 'mahasiswa0026@gmail.com', NULL, NULL, '2025-06-22 18:32:09', '2025-06-22 18:32:09'),
(29, 38, '221011402016', 'ROBY SAPUTRA', 'teknik informatika', NULL, NULL, '06TPLP009', 'aktif', '2021', NULL, 'mahasiswa0027@gmail.com', NULL, NULL, '2025-06-22 18:32:09', '2025-06-22 18:32:09'),
(30, 39, '221011400404', 'SOLAHUDIN', 'teknik informatika', NULL, NULL, '06TPLP009', 'aktif', '2021', NULL, 'mahasiswa0028@gmail.com', NULL, NULL, '2025-06-22 18:32:10', '2025-06-22 18:32:10'),
(31, 40, '221011400393', 'STEVEN JOE HADI SAPUTRA', 'teknik informatika', NULL, NULL, '06TPLP009', 'aktif', '2021', NULL, 'mahasiswa0029@gmail.com', NULL, NULL, '2025-06-22 18:32:10', '2025-06-22 18:32:10'),
(32, 41, '221011402114', 'SYAHRUL WUJUD', 'teknik informatika', NULL, NULL, '06TPLP009', 'aktif', '2021', NULL, 'mahasiswa0030@gmail.com', NULL, NULL, '2025-06-22 18:32:11', '2025-06-22 18:32:11');

-- --------------------------------------------------------

--
-- Table structure for table `mata_kuliahs`
--

CREATE TABLE `mata_kuliahs` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_mk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_mk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sks` int NOT NULL,
  `kelas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mata_kuliahs`
--

INSERT INTO `mata_kuliahs` (`id`, `kode_mk`, `nama_mk`, `sks`, `kelas`, `deskripsi`, `created_at`, `updated_at`) VALUES
(4, '22TIF0323', 'REKAYASA PERANGKAT LUNAK', 3, 'Reguler A', NULL, '2025-06-22 18:37:14', '2025-06-22 18:37:14'),
(5, '22TIF0332', 'KERJA PRAKTEK', 2, 'Reguler A', NULL, '2025-06-22 18:37:39', '2025-06-22 18:37:39'),
(6, '22TIF0342', 'TEKNOLOGI INTERNET OF THINGS', 2, 'Reguler A', NULL, '2025-06-22 18:38:16', '2025-06-22 18:38:16'),
(7, '22TIF0353', 'PEMROGRAMAN II', 3, 'Reguler A', NULL, '2025-06-22 18:38:34', '2025-06-22 18:38:34'),
(8, '22TIF0363', 'BASIS DATA II', 3, 'Reguler A', NULL, '2025-06-22 18:38:59', '2025-06-22 18:38:59'),
(9, '22TIF0443', 'MOBILE PROGRAMMING', 3, 'Reguler A', NULL, '2025-06-22 18:39:16', '2025-06-22 18:39:16'),
(10, '22TIF2012', 'SISTEM PENDUKUNG KEPUTUSAN', 2, 'Reguler A', NULL, '2025-06-22 18:39:38', '2025-06-22 18:39:38'),
(11, '22TIF3012', 'TEKNIK KOMPILASI', 2, 'Reguler A', NULL, '2025-06-22 18:39:58', '2025-06-22 18:39:58');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_05_18_130314_create_sessions_table', 1),
(5, '2025_05_18_144748_create_dosens_table', 1),
(6, '2025_05_18_145946_create_mata_kuliahs_table', 1),
(7, '2025_05_18_153550_create_mahasiswas_table', 1),
(8, '2025_05_18_154216_create_jadwal_kuliahs_table', 1),
(9, '2025_05_18_155317_create_pengampu_mata_kuliahs_table', 1),
(10, '2025_05_18_155822_create_nilai_mahasiswas_table', 1),
(11, '2025_05_24_172153_add_grade_components_to_nilai_mahasiswas_table', 1),
(12, '2025_05_24_174650_add_personal_details_to_mahasiswas_table', 1),
(13, '2025_05_28_103127_create_absen_dosens_table', 1),
(14, '2025_05_28_192043_add_hari_to_pengampu_mata_kuliahs_table', 1),
(15, '2025_05_28_192906_add_jadwal_details_to_pengampu_mata_kuliahs_table', 1),
(16, '2025_05_28_193115_remove_semester_from_pengampu_mata_kuliahs_table', 1),
(17, '2025_05_28_220020_create_presensi_mahasiswas_table', 1),
(18, '2025_05_28_221645_change_kehadiran_to_integer_in_nilai_mahasiswas_table', 1),
(19, '2025_05_28_223458_rename_semester_to_kelas_in_nilai_mahasiswas_table', 1),
(20, '2025_05_29_201819_add_kelas_to_mahasiswas_table', 1),
(21, '2025_05_29_204433_add_pengampu_mata_kuliah_id_to_nilai_mahasiswas_table', 1),
(23, '2025_06_20_041041_create_announcements_table', 2),
(24, '2025_06_23_025506_add_calculated_fields_to_nilai_mahasiswas_table', 2),
(26, '2025_06_23_054435_add_target_role_to_announcements_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `nilai_mahasiswas`
--

CREATE TABLE `nilai_mahasiswas` (
  `id` bigint UNSIGNED NOT NULL,
  `mahasiswa_id` bigint UNSIGNED NOT NULL,
  `mata_kuliah_id` bigint UNSIGNED NOT NULL,
  `dosen_id` bigint UNSIGNED DEFAULT NULL,
  `kelas` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kehadiran` int DEFAULT NULL,
  `nilai_tugas` decimal(5,2) DEFAULT NULL,
  `nilai_uts` decimal(5,2) DEFAULT NULL,
  `nilai_uas` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `pengampu_mata_kuliah_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nilai_mahasiswas`
--

INSERT INTO `nilai_mahasiswas` (`id`, `mahasiswa_id`, `mata_kuliah_id`, `dosen_id`, `kelas`, `kehadiran`, `nilai_tugas`, `nilai_uts`, `nilai_uas`, `created_at`, `updated_at`, `pengampu_mata_kuliah_id`) VALUES
(3, 3, 11, 3, '06TPLP009', 1, '90.00', '90.00', '90.00', '2025-06-22 20:00:33', '2025-06-22 20:00:33', NULL),
(4, 3, 10, 1, '06TPLP009', 1, '80.00', '80.00', '90.00', '2025-06-22 20:05:21', '2025-06-22 20:05:21', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pengampu_mata_kuliah`
--

CREATE TABLE `pengampu_mata_kuliah` (
  `id` bigint UNSIGNED NOT NULL,
  `mata_kuliah_id` bigint UNSIGNED NOT NULL,
  `dosen_id` bigint UNSIGNED NOT NULL,
  `hari` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `ruangan` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kelas` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengampu_mata_kuliah`
--

INSERT INTO `pengampu_mata_kuliah` (`id`, `mata_kuliah_id`, `dosen_id`, `hari`, `jam_mulai`, `jam_selesai`, `ruangan`, `kelas`, `created_at`, `updated_at`) VALUES
(1, 10, 1, 'Senin', '08:50:00', '10:30:00', 'V.530', '06TPLP009', '2025-06-22 18:40:48', '2025-06-22 18:40:48'),
(2, 6, 2, 'Senin', '10:30:00', '12:10:00', 'V.530', '06TPLP009', '2025-06-22 18:42:09', '2025-06-22 18:42:09'),
(3, 11, 3, 'Senin', '13:00:00', '14:40:00', 'V.530', '06TPLP009', '2025-06-22 18:42:54', '2025-06-22 18:42:54'),
(4, 8, 4, 'Selasa', '08:50:00', '10:30:00', 'V.530', '06TPLP009', '2025-06-22 18:43:41', '2025-06-22 18:43:41'),
(5, 7, 5, 'Selasa', '13:00:00', '14:40:00', 'V.530', '06TPLP009', '2025-06-22 18:44:25', '2025-06-22 18:44:25'),
(6, 4, 6, 'Rabu', '08:50:00', '10:30:00', 'V.530', '06TPLP009', '2025-06-22 18:45:13', '2025-06-22 18:45:13'),
(7, 5, 7, 'Jumat', '10:30:00', '12:10:00', 'V.530', '06TPLP009', '2025-06-22 18:45:49', '2025-06-22 18:45:49'),
(8, 9, 8, 'Jumat', '14:40:00', '16:20:00', 'V.530', '06TPLP009', '2025-06-22 18:46:17', '2025-06-22 18:46:17');

-- --------------------------------------------------------

--
-- Table structure for table `presensi_mahasiswas`
--

CREATE TABLE `presensi_mahasiswas` (
  `id` bigint UNSIGNED NOT NULL,
  `mahasiswa_id` bigint UNSIGNED NOT NULL,
  `pengampu_mata_kuliah_id` bigint UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `waktu_presensi` time NOT NULL,
  `status_kehadiran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `presensi_mahasiswas`
--

INSERT INTO `presensi_mahasiswas` (`id`, `mahasiswa_id`, `pengampu_mata_kuliah_id`, `tanggal`, `waktu_presensi`, `status_kehadiran`, `created_at`, `updated_at`) VALUES
(1, 3, 1, '2025-06-23', '05:31:00', 'Hadir', '2025-06-22 19:58:50', '2025-06-22 22:31:00'),
(3, 3, 2, '2025-06-23', '02:58:56', 'Hadir', '2025-06-22 19:58:56', '2025-06-22 19:58:56'),
(29, 3, 3, '2025-06-23', '06:41:27', 'Hadir', '2025-06-22 23:41:27', '2025-06-22 23:41:27');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('2MufHKbhxJaoI09ekj9xUsPLnwtjsLNfiddY09he', 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVGlDb0pTbjB5OW1DUHh1U2FpY2dXaFpnb3EwU1pSb3kwYVFnclYwWiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTA6Imh0dHA6Ly9hcHAtc2lzdGVtLWFrYWRlbWlrLmNvbS9tYWhhc2lzd2EvZGFzaGJvYXJkIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTI7fQ==', 1751479329),
('AUjKYx132EiQL0bY6ZUA0K449YMZjNq0OoWR6c2V', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSURya1VnSm1OcE9VTVgxSldhdUE4TE45YmRtNTcwMUo1RG84OHBLUyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDY6Imh0dHA6Ly9hcHAtc2lzdGVtLWFrYWRlbWlrLmNvbS9hZG1pbi9kYXNoYm9hcmQiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1751482180),
('OKs1WfRsjAYLw4T3g2e0UypgDqPWAxi35QdR1yci', 8, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSDNkakNQSkl6a2cwZnlTM3VaNE9Wd0tHSnRSeWtFNkwwbDJMQlo3MCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDY6Imh0dHA6Ly9hcHAtc2lzdGVtLWFrYWRlbWlrLmNvbS9kb3Nlbi9kYXNoYm9hcmQiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo4O30=', 1751479407);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('mahasiswa','dosen','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'mahasiswa',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin User', 'admin@gmail.com', NULL, '$2y$12$b1hYOg8hd4dFWm0LV0qcRuByxJSB9N7lJjNhaFEhxGPDizS20oK2m', 'admin', NULL, '2025-06-11 14:51:48', '2025-06-11 14:51:48'),
(3, 'IIS AISYAH S.Kom., M.Kom', 'dosen001@gmail.com', NULL, '$2y$12$SlEHGBsdsmEAjacUeO0fxeMUiQHjWRHAivucKV51p7FYoi6PKdo5S', 'dosen', NULL, '2025-06-22 17:32:56', '2025-06-22 17:32:56'),
(4, 'RENGGA ERLANGGA S.Kom., M.Kom.', 'dosen002@gmail.com', NULL, '$2y$12$kTssGuFYW24cN4Z9S0LgdelOcu7KThfuO5UWti/4bGlc0JV7wPgfO', 'dosen', NULL, '2025-06-22 17:33:49', '2025-06-22 17:33:49'),
(5, 'AFIANI AGUS ABDILLAH S.Kom., M.Kom.', 'dosen003@gmail.com', NULL, '$2y$12$f9yxnV.IEkUXh7J0gwnZ2O28WBMocKCOgCeFRTf2slGGP1omWYke2', 'dosen', NULL, '2025-06-22 17:34:33', '2025-06-22 17:34:33'),
(6, 'ICHSAN RAMDHANI S.TP.,M.T.I', 'dosen004@gmail.com', NULL, '$2y$12$df1tVmKlnyAgmWylZDAzWOdNZawyxit4RwfCfW7SbM5/wrby3fobG', 'dosen', NULL, '2025-06-22 17:35:13', '2025-06-22 17:35:13'),
(7, 'YULIANTI S.Kom., M.Kom.', 'dosen005@gmail.com', NULL, '$2y$12$yU/KRTv9y8lYw/MUodkDKuAWEIyRxBLYgFJDEdj9wugCFEi5J3Miu', 'dosen', NULL, '2025-06-22 17:36:19', '2025-06-22 17:36:19'),
(8, 'YESKARWANI GULO S.Kom., M.Kom.', 'dosen006@gmail.com', NULL, '$2y$12$l2e5/jVwfak6cfYZdvE3uOzMbPXo0pRFHE1s/FFNKBRprmbtx0rYG', 'dosen', NULL, '2025-06-22 17:37:00', '2025-06-22 17:37:00'),
(9, 'HADI ZAKARIA S.Kom.,M.Kom.,M.M.', 'dosen007@gmail.com', NULL, '$2y$12$HUuWw6uF1Hx.8MAuPTMvfutnJAFE.sq8dM7Fwgh8jrFt6PpaEDDuq', 'dosen', NULL, '2025-06-22 17:37:44', '2025-06-22 17:37:44'),
(10, 'TOMI HIDAYAT S.KOM., M.KOM.', 'dosen008@gmail.com', NULL, '$2y$12$BJSEQj.jkMehNV2WeGg3ze/q473bAnqyVKy2DBqwPmJxPFlrqHRAW', 'dosen', NULL, '2025-06-22 17:38:24', '2025-06-22 17:38:24'),
(12, 'ADAM MIFTAHUL FALAH', 'mahasiswa001@gmail.com', NULL, '$2y$12$7vmcISc9Jy9qp3MffQHZiedMPFRaxG14FypVBbY/.eA.A96liMpOi', 'mahasiswa', NULL, '2025-06-22 18:31:57', '2025-06-22 18:31:57'),
(13, 'AFFAN SHUJA', 'mahasiswa002@gmail.com', NULL, '$2y$12$x0eX3IG8neBZQdSA1UU5ROKVO2v1O.szOdnIBZPhnAQk2J95xwC8m', 'mahasiswa', NULL, '2025-06-22 18:31:58', '2025-06-22 18:31:58'),
(14, 'ALVEN ARYA SENA', 'mahasiswa003@gmail.com', NULL, '$2y$12$ZXkscLJ8y2lq8EYGUYwBFuWtFczoQgQz8tHYpBF9f1Fi1iTAuqU52', 'mahasiswa', NULL, '2025-06-22 18:31:58', '2025-06-22 18:31:58'),
(15, 'ALWI AL AGIV', 'mahasiswa004@gmail.com', NULL, '$2y$12$ZNt7XmIqSEl5u9UxgjBmsuFazXNkYrDCtBz9oFYd7Y.P.vxWD1z7G', 'mahasiswa', NULL, '2025-06-22 18:31:59', '2025-06-22 18:31:59'),
(16, 'DEVITA MAULINA CAHYANI', 'mahasiswa005@gmail.com', NULL, '$2y$12$geQ7rjRWpbiyloGwV82g0.F3LdCVMFD7Au7xicQum1nSRozRitIfe', 'mahasiswa', NULL, '2025-06-22 18:31:59', '2025-06-22 18:31:59'),
(17, 'DONY SAPTA NURHAYADI', 'mahasiswa006@gmail.com', NULL, '$2y$12$gLIsEbvh0v1jQXcF5AW7VOHD9YOyCLSMYZlLv7.Zh5eON4/LGh6S2', 'mahasiswa', NULL, '2025-06-22 18:31:59', '2025-06-22 18:31:59'),
(18, 'EKA SETYA NINGSIH', 'mahasiswa007@gmail.com', NULL, '$2y$12$FMuKU5TmggpoiYqJZKoR8Oodl/UxnGEzR7A/6qs27n3HG03UzoBKK', 'mahasiswa', NULL, '2025-06-22 18:32:00', '2025-06-22 18:32:00'),
(19, 'FAJRI HERNANDA SARIFUDIN', 'mahasiswa008@gmail.com', NULL, '$2y$12$s8QhJKuxoilvVPikhiERXuip9VHFA6e7BdQIe66PryknH2nA9oqjK', 'mahasiswa', NULL, '2025-06-22 18:32:00', '2025-06-22 18:32:00'),
(20, 'FARHAN ASSHADATH', 'mahasiswa009@gmail.com', NULL, '$2y$12$Ko2EbWDLWF8cL4uwLHK8AOXEb8KxgAaHr5zgeR9f9K1GmB1v3Y1cG', 'mahasiswa', NULL, '2025-06-22 18:32:01', '2025-06-22 18:32:01'),
(21, 'FIKRI ADRIANSYAH', 'mahasiswa0010@gmail.com', NULL, '$2y$12$0MEX2ebLtE30pdblWxUiC.BUjKL813Cadqiie/vhrrQqiXLacmYjG', 'mahasiswa', NULL, '2025-06-22 18:32:01', '2025-06-22 18:32:01'),
(22, 'KEZIA PATRICIA ZEFANYA', 'mahasiswa0011@gmail.com', NULL, '$2y$12$BbK2Ae.2foOQt.2S6gGPf.Xm87xWkuQgd8JcCUZTCEtWXU38XQobC', 'mahasiswa', NULL, '2025-06-22 18:32:02', '2025-06-22 18:32:02'),
(23, 'MARSANDY RULIAN', 'mahasiswa0012@gmail.com', NULL, '$2y$12$APrU07UjwiXHKIt7K3L9g.MJ1pjpi1ITXoa/sPJtUX4qP65WLVSxW', 'mahasiswa', NULL, '2025-06-22 18:32:02', '2025-06-22 18:32:02'),
(24, 'MIFTAH FATHU RAMADHAN', 'mahasiswa0013@gmail.com', NULL, '$2y$12$9QkbgrVtVsR0NlZaXbDzM.6IIPT86aqbhInrE2VBv7xqQZyVMtGWu', 'mahasiswa', NULL, '2025-06-22 18:32:03', '2025-06-22 18:32:03'),
(25, 'MOCHAMMAD IQBAL SUYUDI WIJAYA', 'mahasiswa0014@gmail.com', NULL, '$2y$12$S3gr2azvD82J63feYEAe9eyqMDxUWP3Bd0LfwwhF3ZBy8F2JU/fNO', 'mahasiswa', NULL, '2025-06-22 18:32:03', '2025-06-22 18:32:03'),
(26, 'MU`AMMAR KURNIAWAN', 'mahasiswa0015@gmail.com', NULL, '$2y$12$TBEeUZJwdnAHChaXGGxqwOwW7psWYbPZ4K9FNlJZlPXcYo2PGFFfK', 'mahasiswa', NULL, '2025-06-22 18:32:04', '2025-06-22 18:32:04'),
(27, 'MUCHAMAD UBAYDILLAH', 'mahasiswa0016@gmail.com', NULL, '$2y$12$pUjnSlb2dsLID2gVMH.D/e47OataDcflkEWIu/3Q2awBgOG855vjS', 'mahasiswa', NULL, '2025-06-22 18:32:04', '2025-06-22 18:32:04'),
(28, 'MUHAMMAD AQIL FARHAN', 'mahasiswa0017@gmail.com', NULL, '$2y$12$TemJEuwIRlNzlIQdxZqq8O0kNKOyFl5AsJNSQaKjBMPJmenWYH6RO', 'mahasiswa', NULL, '2025-06-22 18:32:05', '2025-06-22 18:32:05'),
(29, 'MUHAMMAD IQBAL BINTANG', 'mahasiswa0018@gmail.com', NULL, '$2y$12$3DFyyzDQ00sHP.nA0ZAL/OREPqwgoPr/l6ua2EgCq02jrQoy6rlby', 'mahasiswa', NULL, '2025-06-22 18:32:05', '2025-06-22 18:32:05'),
(30, 'MUHAMMAD MICKO BIAGI', 'mahasiswa0019@gmail.com', NULL, '$2y$12$u/W4km9zqza1ebwj8NOP1.phme60xHis/pifp8AEDSOn6EMH5S5X2', 'mahasiswa', NULL, '2025-06-22 18:32:06', '2025-06-22 18:32:06'),
(31, 'MUHAMMAD RAFLI', 'mahasiswa0020@gmail.com', NULL, '$2y$12$2Xg1AWceCbC0iJMLGkfgp.aggxd.BIw62BDgb6adqXOuS/jcPREde', 'mahasiswa', NULL, '2025-06-22 18:32:06', '2025-06-22 18:32:06'),
(32, 'NABILA ZAHROTUL JANNAH', 'mahasiswa0021@gmail.com', NULL, '$2y$12$esx.ocuc.eKX6OiO7H2zouCMuui.yetVA5lq8AWiD77p8Zi/uliPu', 'mahasiswa', NULL, '2025-06-22 18:32:07', '2025-06-22 18:32:07'),
(33, 'PUTRI AWALIYATUNNIZA', 'mahasiswa0022@gmail.com', NULL, '$2y$12$unTC7R63OO75u9o4Wq6zQuAKZ5Eq17HIKyEHm.RT7sgf.HcvETaqC', 'mahasiswa', NULL, '2025-06-22 18:32:07', '2025-06-22 18:32:07'),
(34, 'RAHMAD RAIHAN AMSYAH', 'mahasiswa0023@gmail.com', NULL, '$2y$12$kYfezRyTlSKUHJ/MZ79fTeBedtIfisxj.k8QRrPaYDaBbPZaEA0Vm', 'mahasiswa', NULL, '2025-06-22 18:32:08', '2025-06-22 18:32:08'),
(35, 'RIANA DAMAYANTI', 'mahasiswa0024@gmail.com', NULL, '$2y$12$GzLBAAgC6AggQGOE2eOqwOVakl2.2ypi7Ejo6nJyZ8FyGZ4vfkpHS', 'mahasiswa', NULL, '2025-06-22 18:32:08', '2025-06-22 18:32:08'),
(36, 'RIFQY SATRIO NAVIANTO', 'mahasiswa0025@gmail.com', NULL, '$2y$12$itKMkdF3YuR.6BZhIv3tt.bBVNcvi/I2udVDlafH6kf/8CsmkBeAe', 'mahasiswa', NULL, '2025-06-22 18:32:08', '2025-06-22 18:32:08'),
(37, 'RIZKI AUFARRAMDHI', 'mahasiswa0026@gmail.com', NULL, '$2y$12$bmWWf5yURB7uw4hBDQvbD.4rPs9QMPHBvgzLjeIuXkp3UjctztyYC', 'mahasiswa', NULL, '2025-06-22 18:32:09', '2025-06-22 18:32:09'),
(38, 'ROBY SAPUTRA', 'mahasiswa0027@gmail.com', NULL, '$2y$12$l18znkHJvcJaJk70Nfx0BewXUTvtmurwiOj1Vlezz2xpGGZb/Caru', 'mahasiswa', NULL, '2025-06-22 18:32:09', '2025-06-22 18:32:09'),
(39, 'SOLAHUDIN', 'mahasiswa0028@gmail.com', NULL, '$2y$12$jPPHqDLGrCkk9.ycrAO4i.BzencI4vTjukLMQrqYVvW204ISm84Fm', 'mahasiswa', NULL, '2025-06-22 18:32:10', '2025-06-22 18:32:10'),
(40, 'STEVEN JOE HADI SAPUTRA', 'mahasiswa0029@gmail.com', NULL, '$2y$12$mPe0bAZNRcLlKGxzjW2LO.cVhJn97x9922VNPVsHaWUeBdKpQ36nu', 'mahasiswa', NULL, '2025-06-22 18:32:10', '2025-06-22 18:32:10'),
(41, 'SYAHRUL WUJUD', 'mahasiswa0030@gmail.com', NULL, '$2y$12$9CpaJCVDdpO2nDVFz52iEOcqYCPo1r9MIOuqdd/s794Tgie9Kfcm6', 'mahasiswa', NULL, '2025-06-22 18:32:11', '2025-06-22 18:32:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absen_dosens`
--
ALTER TABLE `absen_dosens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `absen_dosens_dosen_id_foreign` (`dosen_id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `announcements_user_id_foreign` (`user_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `dosens`
--
ALTER TABLE `dosens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dosens_user_id_unique` (`user_id`),
  ADD UNIQUE KEY `dosens_email_unique` (`email`),
  ADD UNIQUE KEY `dosens_nidn_unique` (`nidn`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jadwal_kuliahs`
--
ALTER TABLE `jadwal_kuliahs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_jadwal_kuliah` (`mata_kuliah_id`,`dosen_id`,`hari`,`jam_mulai`,`jam_selesai`,`ruangan`,`kelas`),
  ADD KEY `jadwal_kuliahs_dosen_id_foreign` (`dosen_id`);

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
-- Indexes for table `mahasiswas`
--
ALTER TABLE `mahasiswas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mahasiswas_user_id_unique` (`user_id`),
  ADD UNIQUE KEY `mahasiswas_nim_unique` (`nim`),
  ADD UNIQUE KEY `mahasiswas_email_unique` (`email`);

--
-- Indexes for table `mata_kuliahs`
--
ALTER TABLE `mata_kuliahs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mata_kuliahs_kode_mk_unique` (`kode_mk`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nilai_mahasiswas`
--
ALTER TABLE `nilai_mahasiswas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_nilai_mk_mhs_kelas` (`mahasiswa_id`,`mata_kuliah_id`,`kelas`),
  ADD KEY `nilai_mahasiswas_mata_kuliah_id_foreign` (`mata_kuliah_id`),
  ADD KEY `nilai_mahasiswas_dosen_id_foreign` (`dosen_id`),
  ADD KEY `nilai_mahasiswas_pengampu_mata_kuliah_id_foreign` (`pengampu_mata_kuliah_id`);

--
-- Indexes for table `pengampu_mata_kuliah`
--
ALTER TABLE `pengampu_mata_kuliah`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_pengampu_mk` (`mata_kuliah_id`,`dosen_id`,`hari`,`jam_mulai`,`jam_selesai`,`ruangan`,`kelas`),
  ADD KEY `pengampu_mata_kuliah_dosen_id_foreign` (`dosen_id`);

--
-- Indexes for table `presensi_mahasiswas`
--
ALTER TABLE `presensi_mahasiswas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_presensi_per_day` (`mahasiswa_id`,`pengampu_mata_kuliah_id`,`tanggal`),
  ADD KEY `presensi_mahasiswas_pengampu_mata_kuliah_id_foreign` (`pengampu_mata_kuliah_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

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
-- AUTO_INCREMENT for table `absen_dosens`
--
ALTER TABLE `absen_dosens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `dosens`
--
ALTER TABLE `dosens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jadwal_kuliahs`
--
ALTER TABLE `jadwal_kuliahs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mahasiswas`
--
ALTER TABLE `mahasiswas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `mata_kuliahs`
--
ALTER TABLE `mata_kuliahs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `nilai_mahasiswas`
--
ALTER TABLE `nilai_mahasiswas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pengampu_mata_kuliah`
--
ALTER TABLE `pengampu_mata_kuliah`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `presensi_mahasiswas`
--
ALTER TABLE `presensi_mahasiswas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absen_dosens`
--
ALTER TABLE `absen_dosens`
  ADD CONSTRAINT `absen_dosens_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosens` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `dosens`
--
ALTER TABLE `dosens`
  ADD CONSTRAINT `dosens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jadwal_kuliahs`
--
ALTER TABLE `jadwal_kuliahs`
  ADD CONSTRAINT `jadwal_kuliahs_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosens` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jadwal_kuliahs_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliahs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mahasiswas`
--
ALTER TABLE `mahasiswas`
  ADD CONSTRAINT `mahasiswas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `nilai_mahasiswas`
--
ALTER TABLE `nilai_mahasiswas`
  ADD CONSTRAINT `nilai_mahasiswas_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosens` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `nilai_mahasiswas_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `nilai_mahasiswas_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliahs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `nilai_mahasiswas_pengampu_mata_kuliah_id_foreign` FOREIGN KEY (`pengampu_mata_kuliah_id`) REFERENCES `pengampu_mata_kuliah` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `pengampu_mata_kuliah`
--
ALTER TABLE `pengampu_mata_kuliah`
  ADD CONSTRAINT `pengampu_mata_kuliah_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosens` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pengampu_mata_kuliah_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliahs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `presensi_mahasiswas`
--
ALTER TABLE `presensi_mahasiswas`
  ADD CONSTRAINT `presensi_mahasiswas_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `presensi_mahasiswas_pengampu_mata_kuliah_id_foreign` FOREIGN KEY (`pengampu_mata_kuliah_id`) REFERENCES `pengampu_mata_kuliah` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
