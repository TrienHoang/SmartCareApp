-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 05, 2025 at 12:05 AM
-- Server version: 8.0.41
-- PHP Version: 8.2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smartcareapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'ID lịch hẹn',
  `patient_id` bigint UNSIGNED NOT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `service_id` bigint UNSIGNED NOT NULL,
  `appointment_time` datetime DEFAULT NULL COMMENT 'Thời gian khám',
  `status` enum('pending','confirmed','completed','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Trạng thái lịch hẹn',
  `reason` text COLLATE utf8mb4_unicode_ci COMMENT 'Lý do đặt lịch',
  `cancel_reason` text COLLATE utf8mb4_unicode_ci COMMENT 'Lý do hủy',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `patient_id`, `doctor_id`, `service_id`, `appointment_time`, `status`, `reason`, `cancel_reason`, `created_at`, `updated_at`) VALUES
(1, 3, 2, 1, '2025-05-31 19:12:37', 'completed', 'Minus ducimus et fuga et.', NULL, '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(2, 10, 7, 5, '2025-05-30 08:36:21', 'confirmed', 'Rerum dolores dolores unde rerum.', NULL, '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(3, 9, 7, 2, '2025-06-01 01:42:15', 'cancelled', 'Deleniti fugiat deserunt voluptatem aut.', NULL, '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(4, 7, 5, 5, '2025-05-24 00:54:42', 'confirmed', 'Error sunt officia dolor itaque.', NULL, '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(5, 8, 3, 5, '2025-05-27 07:37:34', 'confirmed', 'Id voluptas voluptatem nihil dolor recusandae facere.', 'Eaque consequatur cum et repudiandae qui sit hic dolorem.', '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(6, 4, 1, 6, '2025-05-24 12:07:22', 'cancelled', 'Voluptates placeat magni alias mollitia aut.', 'Sapiente distinctio voluptatibus et dicta consequatur harum ut.', '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(7, 6, 2, 5, '2025-05-27 20:10:22', 'cancelled', 'Voluptas ex magnam voluptas aliquid.', NULL, '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(8, 8, 3, 10, '2025-05-24 13:25:51', 'cancelled', 'Autem sapiente voluptatibus ullam voluptatem aut.', NULL, '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(9, 7, 3, 9, '2025-05-23 19:41:55', 'pending', 'Laboriosam in dolores explicabo neque architecto.', NULL, '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(10, 1, 2, 9, '2025-05-26 21:08:58', 'completed', 'Commodi fuga iste ab quasi eos.', NULL, '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(11, 5, 9, 2, '2025-05-23 17:19:45', 'pending', 'Quia rem sed unde nobis ut repudiandae.', NULL, '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(12, 5, 1, 9, '2025-05-24 15:40:31', 'confirmed', 'Id excepturi vel aut illum unde veniam.', 'Ad enim nulla dolorem fugit est corporis ut.', '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(13, 10, 10, 10, '2025-05-28 00:12:08', 'pending', 'Dignissimos excepturi non unde et quia.', 'Quia nostrum error delectus totam facere.', '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(14, 7, 5, 7, '2025-05-24 11:43:57', 'confirmed', 'Et a expedita id facilis.', NULL, '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(15, 5, 8, 6, '2025-05-31 05:45:22', 'pending', 'Temporibus et quia iste necessitatibus.', NULL, '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(16, 3, 1, 5, '2025-05-29 21:45:44', 'completed', 'Sit beatae et sit id velit.', NULL, '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(17, 9, 3, 9, '2025-05-30 20:08:46', 'cancelled', 'Et aut consequuntur autem iusto eaque.', 'Recusandae officia necessitatibus omnis dignissimos impedit.', '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(18, 6, 10, 6, '2025-05-24 09:06:55', 'cancelled', 'Omnis aut aperiam est nihil veniam voluptate omnis ipsum.', 'Repellat saepe officia iure ducimus.', '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(19, 9, 4, 7, '2025-05-27 16:39:42', 'completed', 'Voluptatem et animi voluptatem quisquam ab quam omnis a.', NULL, '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(20, 4, 3, 7, '2025-05-26 23:19:06', 'confirmed', 'Porro consequatur aperiam incidunt dolores error.', NULL, '2025-05-21 19:43:47', '2025-05-21 19:43:47');

-- --------------------------------------------------------

--
-- Table structure for table `appointment_logs`
--

CREATE TABLE `appointment_logs` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'ID log cuộc hẹn',
  `appointment_id` bigint UNSIGNED NOT NULL,
  `changed_by` bigint UNSIGNED NOT NULL,
  `status_before` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Trạng thái trước đó',
  `status_after` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Trạng thái sau thay đổi',
  `change_time` datetime DEFAULT NULL COMMENT 'Thời điểm thay đổi',
  `note` text COLLATE utf8mb4_unicode_ci COMMENT 'Ghi chú thay đổi'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `appointment_logs`
--

INSERT INTO `appointment_logs` (`id`, `appointment_id`, `changed_by`, `status_before`, `status_after`, `change_time`, `note`) VALUES
(1, 1, 2, 'confirmed', 'completed', '2025-05-22 02:43:48', 'Qui recusandae cupiditate nobis quis ipsa maiores nemo vitae.'),
(2, 3, 2, 'confirmed', 'confirmed', '2025-05-22 02:43:48', 'Culpa voluptatem velit ipsam dolorem cupiditate.'),
(3, 16, 5, 'confirmed', 'cancelled', '2025-05-22 02:43:48', 'Aut alias et non nihil qui laborum illo.'),
(4, 3, 1, 'confirmed', 'cancelled', '2025-05-22 02:43:48', 'Rerum est et et sequi esse.'),
(5, 18, 4, 'pending', 'cancelled', '2025-05-22 02:43:48', 'Ullam vel dignissimos quo quis eligendi distinctio totam qui.'),
(6, 20, 4, 'confirmed', 'completed', '2025-05-22 02:43:48', 'Vel nihil tenetur vel quo.'),
(7, 6, 10, 'confirmed', 'completed', '2025-05-22 02:43:48', 'Placeat sapiente odio quo qui ea.'),
(8, 14, 1, 'pending', 'confirmed', '2025-05-22 02:43:48', 'Commodi nobis minima quis vitae autem totam minus.'),
(9, 15, 9, 'pending', 'confirmed', '2025-05-22 02:43:48', 'Dolore ut autem labore ullam sunt reprehenderit quasi.'),
(10, 16, 3, 'confirmed', 'completed', '2025-05-22 02:43:48', 'Facilis similique incidunt debitis aut.');

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `author_id` bigint UNSIGNED NOT NULL,
  `published_at` datetime DEFAULT NULL,
  `thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`id`, `title`, `slug`, `content`, `author_id`, `published_at`, `thumbnail`) VALUES
(1, 'Quaerat nisi nihil aut fugiat voluptas aut.', 'quaerat-nisi-nihil-aut-fugiat-voluptas-aut', 'Et numquam omnis id odio laudantium. Autem dolores optio error natus tempore perspiciatis sunt. Ipsam repellendus sapiente nemo eum ut ipsam. Quis alias molestias amet aut non exercitationem delectus iste. Eaque eos dicta dignissimos nemo. Et ut et ab rem perferendis aut nam consequuntur.', 7, '2025-05-22 02:43:48', 'https://via.placeholder.com/640x480.png/0055aa?text=sit'),
(2, 'Quae maiores quas suscipit aut.', 'quae-maiores-quas-suscipit-aut', 'Ullam expedita voluptatem dolor alias neque repudiandae. Sed accusamus consequuntur quasi tempore dolorem beatae. Dolorem dolor unde recusandae velit neque alias tenetur. Reiciendis molestiae sit porro aut rem. Magni enim pariatur non itaque et. Quos tenetur quas iste qui non. Dolores officiis numquam consequatur laborum incidunt et non.', 10, '2025-05-22 02:43:48', 'https://via.placeholder.com/640x480.png/003344?text=qui'),
(3, 'Eum ullam quia et.', 'eum-ullam-quia-et', 'Quaerat beatae voluptatem libero itaque ut minus cumque nostrum. Exercitationem beatae quidem in. Perspiciatis consequuntur optio quia rerum. Nesciunt voluptatum at dignissimos et debitis molestiae molestias. Ea commodi eum consectetur eaque.', 6, '2025-05-22 02:43:48', 'https://via.placeholder.com/640x480.png/003388?text=voluptatem'),
(4, 'Ducimus nobis neque mollitia ut.', 'ducimus-nobis-neque-mollitia-ut', 'Expedita adipisci dolores architecto tenetur id nobis. Deleniti quasi dicta harum enim atque et aut omnis. Reprehenderit iste quis odit soluta. Totam consectetur et perspiciatis omnis occaecati impedit.', 9, '2025-05-22 02:43:48', 'https://via.placeholder.com/640x480.png/004455?text=in'),
(5, 'Fuga cupiditate sit fuga architecto.', 'fuga-cupiditate-sit-fuga-architecto', 'Soluta et iure placeat distinctio. Architecto beatae natus dolor voluptas eum quo unde. Sapiente ea dolor laborum id aspernatur explicabo. Omnis beatae impedit perferendis eum itaque. Magnam autem accusamus voluptas ad dolor. Alias ut commodi eos iste sint impedit. Voluptatibus accusamus quia alias.', 3, '2025-05-22 02:43:48', 'https://via.placeholder.com/640x480.png/00ddee?text=facere'),
(6, 'Ipsam ut laboriosam exercitationem laborum qui et.', 'ipsam-ut-laboriosam-exercitationem-laborum-qui-et', 'Fugit id debitis tempora consequatur expedita accusantium. Ea magni magni et nulla et ut asperiores. Dolores doloremque ut perferendis quidem doloribus voluptas. Ut qui ut quis non commodi. Necessitatibus accusamus et expedita sapiente et.', 6, '2025-05-22 02:43:48', 'https://via.placeholder.com/640x480.png/0000bb?text=repudiandae'),
(7, 'Ad repellat sapiente cumque laboriosam repellat molestiae ipsa sint.', 'ad-repellat-sapiente-cumque-laboriosam-repellat-molestiae-ipsa-sint', 'Possimus repellat magnam ad id amet natus. Quis minima deleniti laboriosam perferendis id aperiam. Dicta quas sapiente optio eaque dolores delectus sapiente. Iure aut debitis dolor harum. Dolorem nesciunt autem deserunt perspiciatis quibusdam architecto dolorum. Enim est impedit nam repudiandae qui itaque tempora exercitationem.', 10, '2025-05-22 02:43:48', 'https://via.placeholder.com/640x480.png/0011cc?text=fuga'),
(8, 'Eius nihil non laudantium cumque accusamus quas animi.', 'eius-nihil-non-laudantium-cumque-accusamus-quas-animi', 'Ut ipsum quis nesciunt occaecati. Et rerum optio architecto. In accusantium sit non praesentium. Consectetur voluptatum voluptas aspernatur amet sed voluptatum.', 8, '2025-05-22 02:43:48', 'https://via.placeholder.com/640x480.png/009944?text=quos'),
(9, 'Quae numquam aperiam sint aut numquam adipisci ea.', 'quae-numquam-aperiam-sint-aut-numquam-adipisci-ea', 'Delectus ut exercitationem nihil autem voluptatem nulla ut soluta. Possimus voluptas voluptatem numquam itaque natus sed. Qui cumque esse sint ut pariatur optio totam. In ea aliquid maxime. Hic consequatur sit pariatur voluptates. Nemo nesciunt exercitationem quae aut laboriosam soluta officiis. Magnam fuga sint sint pariatur.', 5, '2025-05-22 02:43:48', 'https://via.placeholder.com/640x480.png/00dd88?text=illo'),
(10, 'Labore at numquam eum ipsam est praesentium.', 'labore-at-numquam-eum-ipsam-est-praesentium', 'Ad inventore rem et deserunt. Odio et est blanditiis dolor ratione occaecati deserunt. Quia et temporibus et deleniti quo. Quis ipsa voluptatibus ullam amet tempore quis aut. Praesentium enim dicta dolore suscipit qui maxime. Veritatis incidunt harum iusto voluptatem dolores. Ut eveniet officia velit.', 10, '2025-05-22 02:43:48', 'https://via.placeholder.com/640x480.png/00ee88?text=molestias');

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
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'ID phòng ban',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên phòng ban',
  `description` text COLLATE utf8mb4_unicode_ci COMMENT 'Mô tả phòng ban',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Quigley, Stracke and Marquardt', 'Accusamus voluptas sit earum ut animi.', '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(2, 'Smitham Ltd', 'Magnam reprehenderit quisquam nesciunt laborum officiis ipsa.', '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(3, 'Heller-Paucek', 'Dolorem voluptas omnis sit autem quo vel dolores.', '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(4, 'Senger-Towne', 'Sunt reiciendis harum iste vero ut sunt.', '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(5, 'Kuhlman, Langworth and Schuster', 'Possimus laboriosam eos expedita et.', '2025-05-21 19:43:47', '2025-05-21 19:43:47');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'ID bác sĩ',
  `user_id` bigint UNSIGNED NOT NULL,
  `room_id` bigint UNSIGNED DEFAULT NULL,
  `department_id` bigint UNSIGNED DEFAULT NULL,
  `specialization` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Chuyên môn bác sĩ',
  `biography` text COLLATE utf8mb4_unicode_ci COMMENT 'Tiểu sử, giới thiệu bác sĩ',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `user_id`, `room_id`, `department_id`, `specialization`, `biography`, `created_at`, `updated_at`) VALUES
(1, 4, 4, 1, 'Extruding Machine Operator', 'Perspiciatis voluptatem iure quaerat est. Ullam excepturi ratione qui ipsam. Accusamus dolorum laborum enim quas.', '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(2, 3, 10, 3, 'Aircraft Mechanics OR Aircraft Service Technician', 'Nesciunt accusantium est non non. Porro voluptates perspiciatis odit doloribus. Velit quia voluptates dolores enim assumenda ducimus sunt rerum.', '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(3, 10, 8, 3, 'Civil Engineer', 'Quasi veritatis molestiae aut accusamus aut. Commodi pariatur et qui inventore dolorum voluptatem eveniet. Sapiente quo voluptatum dolorem et rerum debitis.', '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(4, 7, 9, 5, 'Soil Scientist', 'Praesentium et nesciunt nemo voluptas. Voluptate asperiores sint mollitia quisquam quia voluptatum sint sint. Excepturi doloremque illo et adipisci ullam et laborum.', '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(5, 8, 6, 3, 'Anthropologist OR Archeologist', 'Culpa est rem saepe voluptas dolorum omnis eius. Aut aut quasi porro sint nostrum omnis nulla aut. Consequatur qui architecto ut aut quidem maiores. Dignissimos iusto id tempora cupiditate labore ad. Eaque animi mollitia et ut aliquam vitae.', '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(6, 2, 10, 4, 'Human Resources Assistant', 'Ullam unde occaecati consequuntur eligendi sunt omnis nihil. Voluptatem sint dolore architecto voluptas sapiente dolores debitis non. Natus quod cupiditate voluptatibus ullam.', '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(7, 5, 7, 5, 'Crushing Grinding Machine Operator', 'Rem consequatur sit architecto. Nesciunt illum delectus voluptas. Labore ut placeat velit enim voluptatum aut soluta. Sed et pariatur sed mollitia.', '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(8, 10, 9, 2, 'Machinist', 'Facere explicabo rem aut possimus. Amet praesentium molestias quod tempora. Officiis autem quam aut non consequatur ut.', '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(9, 2, 2, 4, 'Gluing Machine Operator', 'Voluptates est optio nam ab in voluptatem nesciunt. Qui quae quis illo recusandae. Praesentium et quod commodi. Corrupti nesciunt impedit quasi excepturi.', '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(10, 1, 6, 2, 'Housekeeper', 'Soluta mollitia quia voluptates. Explicabo iusto harum soluta tempora. Mollitia magnam voluptas expedita labore qui.', '2025-05-21 19:43:47', '2025-05-21 19:43:47');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_leaves`
--

CREATE TABLE `doctor_leaves` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'ID nghỉ phép',
  `doctor_id` bigint UNSIGNED NOT NULL,
  `start_date` date DEFAULT NULL COMMENT 'Ngày bắt đầu',
  `end_date` date DEFAULT NULL COMMENT 'Ngày kết thúc',
  `reason` text COLLATE utf8mb4_unicode_ci COMMENT 'Lý do nghỉ',
  `created_at` datetime DEFAULT NULL COMMENT 'Thời gian lập phiếu',
  `approved` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Đã duyệt chưa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `doctor_leaves`
--

INSERT INTO `doctor_leaves` (`id`, `doctor_id`, `start_date`, `end_date`, `reason`, `created_at`, `approved`) VALUES
(1, 9, '2025-05-02', '2025-06-09', 'Fuga perspiciatis ut quo.', '2025-05-22 02:43:47', 1),
(2, 3, '2025-05-17', '2025-06-19', 'Ut iusto omnis consectetur quae non.', '2025-05-22 02:43:47', 0),
(3, 5, '2025-05-11', '2025-06-09', 'Amet odio veniam corrupti a.', '2025-05-22 02:43:47', 1),
(4, 4, '2025-05-17', '2025-06-10', 'Architecto placeat harum suscipit adipisci et.', '2025-05-22 02:43:47', 0),
(5, 5, '2025-05-09', '2025-05-26', 'Aut qui error accusamus explicabo excepturi ut labore libero.', '2025-05-22 02:43:47', 1),
(6, 8, '2025-05-01', '2025-06-07', 'Corporis ea accusantium et.', '2025-05-22 02:43:47', 1),
(7, 8, '2025-05-12', '2025-05-29', 'Illo enim veniam aliquam animi ut animi ab sunt.', '2025-05-22 02:43:47', 1),
(8, 9, '2025-05-04', '2025-05-30', 'Ut corrupti magni quia non provident dignissimos.', '2025-05-22 02:43:47', 1),
(9, 2, '2025-05-17', '2025-06-05', 'Laborum quis nobis nulla odio quaerat autem tempora.', '2025-05-22 02:43:47', 1),
(10, 5, '2025-05-07', '2025-06-16', 'Nobis harum repellat architecto et.', '2025-05-22 02:43:47', 1);

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
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` bigint UNSIGNED NOT NULL,
  `question` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `answer`, `is_active`) VALUES
(1, 'Autem minima quis minima et et.?', 'Aut pariatur animi consectetur nostrum quaerat quis sed. Dolorum saepe sint voluptatum tempore. Qui inventore ducimus et occaecati quibusdam saepe.', 1),
(2, 'Ducimus omnis excepturi temporibus nam quia a animi.?', 'Aperiam facere aperiam rerum illum excepturi excepturi. Sint dolor odio consequuntur aut. Quam et est cum qui aliquam eveniet sit. Nihil nihil totam minus quo. Enim ex reiciendis quisquam in sed maxime.', 1),
(3, 'Nobis voluptas doloribus amet tempora reiciendis dolorem ad vero.?', 'Dolores quibusdam et rerum eius sapiente. Voluptas reprehenderit et similique cumque occaecati quia. Odit hic eveniet voluptate et officiis voluptatem qui unde. Doloremque in veritatis sit numquam. Cupiditate corporis quia dolor et rerum aspernatur.', 0),
(4, 'Sapiente maxime fugiat perspiciatis provident repudiandae blanditiis.?', 'Sint odit vel atque et minima earum. Error quas beatae vel consequatur voluptas. Placeat alias architecto perferendis nam corrupti perferendis rem et. Libero facilis sunt omnis.', 0),
(5, 'Maxime corrupti saepe totam sapiente et minima.?', 'Nesciunt in laboriosam id id optio magni rem. Et non optio molestiae et iure vel non. Et inventore architecto iste voluptas qui sunt distinctio. Illum dolorem eos voluptatem excepturi.', 0),
(6, 'Sit nihil quam nihil aut numquam minus totam.?', 'Voluptatibus nisi asperiores veniam et. Rem aperiam hic ab commodi quisquam corrupti. Cupiditate officiis repellat ut culpa sunt nobis qui.', 1),
(7, 'Odio ut commodi ut rerum fugiat quod.?', 'Et distinctio aliquam perspiciatis et et laboriosam. Dicta est dolorum molestias qui assumenda in in. Voluptatum ipsum consequatur nisi ipsa atque.', 0),
(8, 'Perspiciatis et sit voluptatibus suscipit quia saepe nesciunt.?', 'Et omnis dolor non doloribus non eius. Sapiente consectetur dolor adipisci dolores accusantium id. Nesciunt architecto animi expedita quibusdam. Nihil fugiat natus saepe odio atque. Eos adipisci omnis accusantium ullam.', 0),
(9, 'Aspernatur harum hic doloremque dignissimos molestiae.?', 'Inventore voluptatibus sint et itaque sequi. Hic doloribus natus eos.', 1),
(10, 'Consequatur nobis aut ut cum.?', 'Quos eaque eum sed perspiciatis qui totam enim. Nemo consequatur qui nesciunt doloribus. Ut consequuntur et quibusdam magni dignissimos rerum. Vel non veritatis deleniti voluptates dolores eos.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `file_uploads`
--

CREATE TABLE `file_uploads` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `appointment_id` bigint UNSIGNED DEFAULT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_category` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uploaded_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `file_uploads`
--

INSERT INTO `file_uploads` (`id`, `user_id`, `appointment_id`, `file_name`, `file_path`, `file_category`, `uploaded_at`) VALUES
(1, 6, 8, 'laborum.pdf', 'uploads/bbb07306-fc96-3857-9a27-c92c680469b7.pdf', 'prescription', '2025-05-22 02:43:48'),
(2, 1, 15, 'expedita.pdf', 'uploads/49e8ec36-cebe-3e58-af16-d6693b505e77.pdf', 'prescription', '2025-05-22 02:43:48'),
(3, 3, 3, 'excepturi.pdf', 'uploads/0a8b9bee-975a-3623-b319-0d7faeea0171.pdf', 'prescription', '2025-05-22 02:43:48'),
(4, 8, 6, 'quod.pdf', 'uploads/fbc9f171-a227-3a79-921a-30c71d8d0d25.pdf', 'xray', '2025-05-22 02:43:48'),
(5, 7, 12, 'vitae.pdf', 'uploads/5a28fe93-1a1f-3bb6-861e-d8677a27c5fd.pdf', 'prescription', '2025-05-22 02:43:48'),
(6, 8, 4, 'occaecati.pdf', 'uploads/fb4a6062-88f5-3477-a256-0f9d9c9a44c8.pdf', 'form', '2025-05-22 02:43:48'),
(7, 8, 17, 'qui.pdf', 'uploads/0aff7a5c-fcf8-3635-8bdb-bf00d8678fa0.pdf', 'prescription', '2025-05-22 02:43:48'),
(8, 6, 3, 'vero.pdf', 'uploads/d90d81d2-068a-3310-b6e3-a66c833a5205.pdf', 'form', '2025-05-22 02:43:48'),
(9, 2, 5, 'perferendis.pdf', 'uploads/efdb5beb-a540-3582-bf83-e6b5ba25b5ef.pdf', 'form', '2025-05-22 02:43:48'),
(10, 9, 10, 'quod.pdf', 'uploads/a50da940-a3ab-38b1-bc83-e02427a5f895.pdf', 'form', '2025-05-22 02:43:48'),
(11, 8, 12, 'quasi.pdf', 'uploads/56749850-f197-3cec-be2a-ed3e18e8cea8.pdf', 'xray', '2025-05-22 02:43:48'),
(12, 4, 12, 'perspiciatis.pdf', 'uploads/8e2fd195-c459-3ac7-8187-14b77251201f.pdf', 'form', '2025-05-22 02:43:48'),
(13, 3, 5, 'quod.pdf', 'uploads/6bbda344-e885-3b04-b892-143e2df60faa.pdf', 'form', '2025-05-22 02:43:48'),
(14, 5, 19, 'aut.pdf', 'uploads/cc79d873-45c3-362e-a828-054ded1d2743.pdf', 'prescription', '2025-05-22 02:43:48'),
(15, 2, 5, 'earum.pdf', 'uploads/8994eb4f-6cae-3728-aa0f-fa8a460e2b67.pdf', 'prescription', '2025-05-22 02:43:48');

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
-- Table structure for table `medical_records`
--

CREATE TABLE `medical_records` (
  `id` bigint UNSIGNED NOT NULL,
  `appointment_id` bigint UNSIGNED NOT NULL,
  `symptoms` text COLLATE utf8mb4_unicode_ci,
  `diagnosis` text COLLATE utf8mb4_unicode_ci,
  `treatment` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `medical_records`
--

INSERT INTO `medical_records` (`id`, `appointment_id`, `symptoms`, `diagnosis`, `treatment`, `notes`, `created_at`) VALUES
(1, 20, 'Officia sint voluptas fugit est voluptatem tempore.', 'iure error ad', 'Quaerat et ea repudiandae amet soluta.', NULL, '2025-05-22 02:43:48'),
(2, 4, 'Similique atque aut aut quas ut.', 'facilis aut enim', 'Excepturi repellat ducimus illo.', NULL, '2025-05-22 02:43:48'),
(3, 8, 'Voluptatum optio quidem facere velit.', 'architecto sed corrupti', 'Ipsa eum tenetur dolorem in sunt.', NULL, '2025-05-22 02:43:48'),
(4, 7, 'Facere et esse est velit.', 'non et omnis', 'Nihil qui aliquam labore ab.', NULL, '2025-05-22 02:43:48'),
(5, 4, 'Rerum vitae rerum quis non voluptatum.', 'in id temporibus', 'Molestiae possimus hic sed consequatur pariatur sequi.', 'Quisquam debitis quia eius maiores perspiciatis. Voluptatem minima enim optio repellendus. Quos omnis pariatur nemo nostrum est enim. Voluptatum omnis ut odit.', '2025-05-22 02:43:48'),
(6, 10, 'Sed sint repellendus quia sed qui ex quod.', 'officiis id voluptas', 'Sint omnis cum quam et adipisci officiis praesentium error.', 'Laudantium velit consequuntur numquam sit esse vel nisi. Eveniet sint nihil omnis error est inventore voluptas. Velit quo ullam dolore. Eum eveniet quia harum corporis.', '2025-05-22 02:43:48'),
(7, 5, 'Rerum voluptatibus rerum minima quidem.', 'aut velit ipsum', 'Dolorem et repudiandae culpa ducimus corporis.', 'Voluptates ullam accusamus quibusdam dicta. Sit ea et hic. Facere voluptas asperiores ut sunt quae impedit. Porro ex provident doloremque tempore id qui.', '2025-05-22 02:43:48'),
(8, 9, 'Odit beatae rerum ipsum debitis vel sit.', 'ratione neque nobis', 'Quasi reiciendis dolores nihil blanditiis modi voluptatum.', NULL, '2025-05-22 02:43:48'),
(9, 16, 'Tenetur totam eligendi mollitia excepturi.', 'laborum quod id', 'Ut consequuntur sed enim nisi quo laborum.', 'Sunt repudiandae est eum est. Exercitationem ut omnis ea ea illo omnis sint. Laudantium fuga laboriosam distinctio fuga sint tempore et.', '2025-05-22 02:43:48'),
(10, 9, 'Molestiae est rerum eum.', 'quis itaque id', 'At nihil fuga rem in et repellendus molestias.', 'Adipisci ut nobis provident dolor voluptas praesentium. Voluptas eos ut et fugit nemo aut. Omnis repudiandae quidem fugit.', '2025-05-22 02:43:48'),
(11, 13, 'Quia aliquam dolorem sed autem ex est nihil officia.', 'incidunt doloremque ut', 'Vitae reiciendis explicabo praesentium necessitatibus sed.', NULL, '2025-05-22 02:43:48'),
(12, 14, 'Commodi soluta cumque ducimus.', 'in nihil minus', 'Velit nam recusandae distinctio nostrum totam.', 'Perspiciatis delectus cumque velit hic expedita voluptates. Omnis temporibus atque aut ut nobis. Corrupti labore alias reprehenderit in iusto porro necessitatibus.', '2025-05-22 02:43:48'),
(13, 19, 'Adipisci voluptas aut eius facilis.', 'dolorem ducimus est', 'Eum molestiae autem accusantium esse.', 'Et assumenda odit impedit qui quam nihil est sunt. Temporibus provident adipisci voluptatibus porro. Quia voluptate laborum placeat ad libero. Doloribus alias cum illo repudiandae vitae. Quaerat voluptates non totam ut laborum totam saepe.', '2025-05-22 02:43:48'),
(14, 17, 'Odio enim et voluptatem harum saepe voluptas porro.', 'ullam placeat aperiam', 'Sit animi distinctio rerum ut nemo aut repellat.', NULL, '2025-05-22 02:43:48'),
(15, 9, 'A maiores delectus voluptatibus quidem est.', 'ex voluptatibus sunt', 'Quis quis repellat similique quam incidunt.', 'Velit ut tenetur omnis explicabo itaque earum. Et debitis perspiciatis consequatur eaque animi facere. Aliquam vero nam voluptatibus deserunt neque maxime rerum voluptas. Beatae dolor minima in suscipit est modi est quasi. Amet sit ut tempora adipisci nihil quas dolorem.', '2025-05-22 02:43:48');

-- --------------------------------------------------------

--
-- Table structure for table `medicines`
--

CREATE TABLE `medicines` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `unit` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dosage` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `medicines`
--

INSERT INTO `medicines` (`id`, `name`, `description`, `unit`, `dosage`, `price`, `created_at`) VALUES
(1, 'Minimamax', 'Qui cumque est officia ullam rerum fugit.', 'gói', '5ml/lần', '63.10', '2025-05-22 02:43:48'),
(2, 'Solutamax', 'Totam nam adipisci alias ut provident aut aliquid.', 'gói', '1 viên/ngày', '25.21', '2025-05-22 02:43:48'),
(3, 'Nesciuntmax', 'Temporibus consequuntur cupiditate et exercitationem.', 'viên', '2 viên/ngày', '63.78', '2025-05-22 02:43:48'),
(4, 'Aperiammax', 'Totam consequuntur autem omnis accusantium.', 'viên', '5ml/lần', '43.63', '2025-05-22 02:43:48'),
(5, 'Autmax', 'Exercitationem corrupti magni quibusdam accusamus sint et aliquam.', 'gói', '2 viên/ngày', '23.89', '2025-05-22 02:43:48'),
(6, 'Distinctiomax', 'Dolorem ipsa maiores sit eligendi eos nam.', 'ml', '1 viên/ngày', '65.97', '2025-05-22 02:43:48'),
(7, 'Explicabomax', 'Soluta quaerat nemo temporibus perspiciatis aperiam.', 'gói', '2 viên/ngày', '48.65', '2025-05-22 02:43:48'),
(8, 'Illummax', 'Aliquam accusantium et quae in sed consequatur.', 'viên', '2 viên/ngày', '86.40', '2025-05-22 02:43:48'),
(9, 'Nesciuntmax', 'Atque blanditiis aut placeat earum.', 'viên', '1 viên/ngày', '76.75', '2025-05-22 02:43:48'),
(10, 'Autmax', 'Pariatur eveniet mollitia ipsa ut est magni quas.', 'viên', '1 viên/ngày', '38.92', '2025-05-22 02:43:48'),
(11, 'Minimamax', 'Quia modi voluptates ea veritatis repudiandae doloremque omnis.', 'gói', '2 viên/ngày', '27.72', '2025-05-22 02:43:48'),
(12, 'Doloresmax', 'Quos voluptas ut beatae aut tempore.', 'gói', '5ml/lần', '68.04', '2025-05-22 02:43:48'),
(13, 'Nobismax', 'Earum totam magni praesentium esse deserunt magni.', 'ml', '1 viên/ngày', '87.70', '2025-05-22 02:43:48'),
(14, 'Autmax', 'Iusto provident nisi maxime.', 'viên', '5ml/lần', '58.28', '2025-05-22 02:43:48'),
(15, 'Voluptatesmax', 'Ut odit recusandae adipisci rerum id ipsam.', 'ml', '2 viên/ngày', '24.88', '2025-05-22 02:43:48'),
(16, 'Autmax', 'Officiis nihil doloribus sunt deserunt maiores nemo aliquam.', 'ml', '2 viên/ngày', '97.89', '2025-05-22 02:43:48'),
(17, 'Cupiditatemax', 'Animi labore qui rem rerum.', 'ml', '1 viên/ngày', '34.49', '2025-05-22 02:43:48'),
(18, 'Voluptatemmax', 'Vitae necessitatibus quis saepe sunt quasi sit atque.', 'ml', '5ml/lần', '37.35', '2025-05-22 02:43:48'),
(19, 'Velitmax', 'Similique architecto quisquam sit quas sapiente asperiores necessitatibus.', 'ml', '5ml/lần', '58.30', '2025-05-22 02:43:48'),
(20, 'Voluptatummax', 'Fugit a occaecati totam.', 'viên', '5ml/lần', '24.03', '2025-05-22 02:43:48');

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
(1, '0001_01_01_000001_create_cache_table', 1),
(2, '0001_01_01_000002_create_jobs_table', 1),
(3, '2025_05_21_072930_create_roles_table', 1),
(4, '2025_05_21_072931_create_permissions_table', 1),
(5, '2025_05_21_072932_create_role_permissions_table', 1),
(6, '2025_05_21_072935_create_users_table', 1),
(7, '2025_05_21_072936_create_departments_table', 1),
(8, '2025_05_21_072936_create_rooms_table', 1),
(9, '2025_05_21_072937_create_doctors_table', 1),
(10, '2025_05_21_072937_create_working_schedules_table', 1),
(11, '2025_05_21_072938_create_doctor_leaves_table', 1),
(12, '2025_05_21_072938_create_service_categories_table', 1),
(13, '2025_05_21_072938_create_services_table', 1),
(14, '2025_05_21_072939_create_appointments_table', 1),
(15, '2025_05_21_072939_create_promotions_table', 1),
(16, '2025_05_21_072940_create_appointment_logs_table', 1),
(17, '2025_05_21_072940_create_payments_table', 1),
(18, '2025_05_21_072941_create_payment_histories_table', 1),
(19, '2025_05_21_072942_create_medical_records_table', 1),
(20, '2025_05_21_072942_create_medicines_table', 1),
(21, '2025_05_21_072942_create_prescriptions_table', 1),
(22, '2025_05_21_072943_create_prescription_items_table', 1),
(23, '2025_05_21_072944_create_treatment_plans_table', 1),
(24, '2025_05_21_072945_create_file_uploads_table', 1),
(25, '2025_05_21_072945_create_treatment_histories_table', 1),
(26, '2025_05_21_072946_create_upload_histories_table', 1),
(27, '2025_05_21_072947_create_blogs_table', 1),
(28, '2025_05_21_072947_create_notifications_table', 1),
(29, '2025_05_21_072948_create_faqs_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `is_read`, `created_at`) VALUES
(1, 4, 'Impedit ea vel recusandae a ut doloribus.', 'Dolor repellat rerum in quas. Voluptatem accusamus ut sed enim beatae. Maiores sint facilis neque qui distinctio. Voluptas voluptatem qui aut maxime. Ut maiores nihil ullam ut odio qui.', 1, '2025-05-22 02:43:48'),
(2, 1, 'Vero non placeat iure illum.', 'Placeat ducimus voluptate culpa blanditiis consequuntur dolorem. Id quos ea libero sunt.', 1, '2025-05-22 02:43:48'),
(3, 6, 'Cum omnis rerum optio.', 'Magni aut sint iure aliquam a cum. Beatae voluptas nesciunt qui molestiae sunt. Accusamus ea nemo repellat aut. Debitis nemo recusandae vel nulla animi.', 0, '2025-05-22 02:43:48'),
(4, 3, 'Quo aliquid ex dolorem laboriosam.', 'Qui aut dolorum magni libero earum sunt possimus placeat. Quia eos sunt adipisci quisquam consequatur. Eaque optio sed ut quia occaecati enim enim.', 0, '2025-05-22 02:43:48'),
(5, 9, 'Cumque deleniti repudiandae voluptates fuga recusandae quibusdam.', 'Consequatur voluptas dignissimos molestiae mollitia maiores quasi. Consequuntur rerum laudantium et rem. Aliquid ut non qui mollitia.', 0, '2025-05-22 02:43:48'),
(6, 3, 'Quas suscipit commodi itaque.', 'Aliquid minima libero qui perferendis. Dolorem consequuntur non magni sed est esse quos hic. Voluptate similique ducimus quod est et ea esse enim.', 0, '2025-05-22 02:43:48'),
(7, 9, 'Dolor dolor qui enim pariatur placeat eos.', 'Quo quibusdam quibusdam optio enim in. Necessitatibus qui similique illum culpa eligendi et. In voluptas in at molestiae vero et cumque unde. Expedita explicabo ea quia ratione iure non.', 1, '2025-05-22 02:43:48'),
(8, 6, 'Cum sit dicta quam unde velit id vel.', 'Ut inventore vel aut consequatur suscipit voluptas qui voluptatem. Dolore minima nobis explicabo soluta. Officiis voluptatum doloribus ut recusandae velit. Explicabo vel et voluptas ipsam sit.', 1, '2025-05-22 02:43:48'),
(9, 2, 'Perspiciatis impedit quasi aperiam magni quia dolor et.', 'Qui sit magni ea eum error. Consequatur hic quis laborum ad molestias quis nemo. Vel magnam repudiandae incidunt nemo magni iusto amet. Quo molestias molestiae voluptas dolores. Placeat sit ullam sed animi.', 0, '2025-05-22 02:43:48'),
(10, 10, 'Aliquid pariatur provident a saepe repellendus.', 'Corrupti impedit sequi enim voluptatem. Est ad eius distinctio sed quia aut. Officia ut quo ut totam.', 0, '2025-05-22 02:43:48'),
(11, 1, 'Dolor magnam sint quam vel.', 'Impedit iure et in veniam non architecto enim. Veniam eius non vero molestiae alias minus numquam. Et odit reprehenderit exercitationem id aut sed ipsum. Fuga incidunt et omnis eaque odio rerum.', 1, '2025-05-22 02:43:48'),
(12, 2, 'Commodi vel expedita nisi reprehenderit officia laborum.', 'Esse eligendi et dolores aliquid totam provident beatae. Pariatur voluptatum quo provident officiis. Est eligendi blanditiis amet cum. Laudantium sed neque tempora deserunt.', 1, '2025-05-22 02:43:48'),
(13, 8, 'Incidunt dolores suscipit error quod voluptatum placeat.', 'Asperiores autem dolorum et occaecati asperiores et. Facere dignissimos vitae quibusdam facere id numquam. Eius eos magni molestias consectetur odio. Ex pariatur saepe explicabo velit quo consequuntur.', 0, '2025-05-22 02:43:48'),
(14, 8, 'Repellendus molestiae nam et laboriosam.', 'Voluptatem eius voluptas quas dolorum. Et iste aspernatur recusandae iste. Corrupti voluptas natus sint sapiente eligendi unde dolorem.', 1, '2025-05-22 02:43:48'),
(15, 10, 'Aut qui ea ut rerum pariatur.', 'Eligendi doloremque atque tenetur doloribus illum iste vitae. Dolor inventore quia assumenda pariatur accusantium voluptas. A dicta corporis sed dolore labore fuga accusamus. Harum facere est nobis libero nostrum aperiam et.', 1, '2025-05-22 02:43:48'),
(16, 6, 'Ut consequuntur dolorum sequi voluptate dolorem at minima.', 'Et consequatur officiis tempore voluptatem consequuntur. Incidunt qui a quia nemo rem. Consequatur nihil et est magni provident aperiam quia.', 0, '2025-05-22 02:43:48'),
(17, 8, 'Beatae voluptatem vel non non nemo dicta eum voluptates.', 'Provident aut magni maiores dolorem sint similique. Et harum aut reprehenderit est rem.', 0, '2025-05-22 02:43:48'),
(18, 10, 'Laudantium velit consequatur corporis minima.', 'Blanditiis recusandae maxime est placeat ut error ipsam omnis. Quod perferendis sapiente est similique natus aut. Nihil esse nihil corporis. Sunt assumenda facilis est aut fugit ipsum.', 0, '2025-05-22 02:43:48'),
(19, 8, 'Sapiente eos quod perspiciatis laborum.', 'Quis doloribus sunt et in iusto non. Est dicta commodi officiis ut quibusdam dolorem cupiditate voluptas. Repellendus ab est non adipisci velit provident aut.', 1, '2025-05-22 02:43:48'),
(20, 7, 'Nam possimus aut rem voluptas vel provident.', 'Pariatur ipsa qui quia maxime autem ducimus mollitia. Qui ex quis aperiam voluptates. Odio animi corrupti quia quas voluptatibus sapiente. Rerum rerum cumque ratione. Neque dignissimos molestias minima voluptates eveniet necessitatibus officia.', 0, '2025-05-22 02:43:48'),
(21, 9, 'Nulla quis fuga eum porro pariatur atque.', 'Error asperiores eligendi dolores esse qui odit. Dolore corporis vel culpa et consequatur ut. Voluptatem non aspernatur consequuntur dicta.', 1, '2025-05-22 02:43:48'),
(22, 4, 'Quasi recusandae magnam sint maxime quis distinctio adipisci aperiam.', 'Delectus impedit nulla illo exercitationem fugiat iure. Quia eaque velit autem culpa veniam. Quae est quo asperiores porro sapiente. Suscipit totam tempore vel facere repellat expedita dolorem.', 1, '2025-05-22 02:43:48'),
(23, 10, 'Exercitationem et vero corporis dolorum sequi nihil.', 'Quod rerum consequatur quia nihil. Autem ipsa nobis magnam eveniet sapiente perspiciatis. Iusto adipisci quos dolorem dolore molestiae sed assumenda. Dolores corporis magni dicta aliquid.', 0, '2025-05-22 02:43:48'),
(24, 10, 'Ad possimus nemo et debitis aut.', 'Iusto ea similique ut fuga quaerat quisquam aut. Ut consequatur omnis aut mollitia. Molestiae sapiente praesentium atque. Rerum quia impedit minus vel dignissimos.', 1, '2025-05-22 02:43:48'),
(25, 3, 'Aliquam quidem sed inventore maxime.', 'Sit aut et omnis velit ut vitae. Eos rem accusamus iure veniam quae molestias reiciendis. Autem sapiente provident magni cum quis perspiciatis.', 0, '2025-05-22 02:43:48');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'ID thanh toán',
  `appointment_id` bigint UNSIGNED NOT NULL,
  `promotion_id` bigint UNSIGNED DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL COMMENT 'Tổng tiền',
  `payment_method` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Phương thức thanh toán',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Trạng thái thanh toán',
  `paid_at` datetime DEFAULT NULL COMMENT 'Thời gian thanh toán'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `appointment_id`, `promotion_id`, `amount`, `payment_method`, `status`, `paid_at`) VALUES
(1, 1, 1, '476.27', 'momo', 'pending', '2025-05-22 02:43:48'),
(2, 4, 3, '119.87', 'cash', 'pending', '2025-05-22 02:43:48'),
(3, 14, 4, '387.93', 'momo', 'paid', '2025-05-22 02:43:48'),
(4, 2, 4, '103.26', 'momo', 'pending', '2025-05-22 02:43:48'),
(5, 1, 2, '259.07', 'cash', 'paid', '2025-05-22 02:43:48'),
(6, 15, 4, '292.86', 'momo', 'pending', '2025-05-22 02:43:48'),
(7, 11, 3, '203.49', 'cash', 'paid', '2025-05-22 02:43:48'),
(8, 1, 3, '281.25', 'cash', 'paid', '2025-05-22 02:43:48'),
(9, 18, 4, '133.13', 'momo', 'paid', '2025-05-22 02:43:48'),
(10, 13, 5, '464.49', 'card', 'paid', '2025-05-22 02:43:48'),
(11, 16, 3, '372.52', 'card', 'pending', '2025-05-22 02:43:48'),
(12, 18, 5, '440.10', 'cash', 'paid', '2025-05-22 02:43:48'),
(13, 12, 3, '313.82', 'cash', 'paid', '2025-05-22 02:43:48'),
(14, 16, 4, '177.37', 'card', 'paid', '2025-05-22 02:43:48'),
(15, 6, 5, '359.22', 'momo', 'pending', '2025-05-22 02:43:48'),
(16, 10, 4, '372.48', 'card', 'pending', '2025-05-22 02:43:48'),
(17, 16, 2, '161.40', 'momo', 'pending', '2025-05-22 02:43:48'),
(18, 4, 5, '441.52', 'card', 'pending', '2025-05-22 02:43:48'),
(19, 19, 3, '133.57', 'momo', 'paid', '2025-05-22 02:43:48'),
(20, 6, 1, '255.37', 'cash', 'paid', '2025-05-22 02:43:48');

-- --------------------------------------------------------

--
-- Table structure for table `payment_histories`
--

CREATE TABLE `payment_histories` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'ID lịch sử thanh toán',
  `payment_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL COMMENT 'Số tiền thanh toán',
  `payment_method` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Phương thức',
  `payment_date` datetime DEFAULT NULL COMMENT 'Ngày thanh toán'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_histories`
--

INSERT INTO `payment_histories` (`id`, `payment_id`, `amount`, `payment_method`, `payment_date`) VALUES
(1, 20, '460.66', 'card', '2025-05-22 02:43:48'),
(2, 12, '370.18', 'card', '2025-05-22 02:43:48'),
(3, 10, '433.24', 'cash', '2025-05-22 02:43:48'),
(4, 18, '447.96', 'card', '2025-05-22 02:43:48'),
(5, 9, '395.10', 'cash', '2025-05-22 02:43:48'),
(6, 7, '429.31', 'card', '2025-05-22 02:43:48'),
(7, 3, '307.32', 'cash', '2025-05-22 02:43:48'),
(8, 16, '238.33', 'cash', '2025-05-22 02:43:48'),
(9, 18, '415.38', 'card', '2025-05-22 02:43:48'),
(10, 16, '249.50', 'card', '2025-05-22 02:43:48'),
(11, 9, '201.94', 'cash', '2025-05-22 02:43:48'),
(12, 2, '421.89', 'card', '2025-05-22 02:43:48'),
(13, 18, '228.40', 'cash', '2025-05-22 02:43:48'),
(14, 7, '244.83', 'card', '2025-05-22 02:43:48'),
(15, 13, '235.86', 'cash', '2025-05-22 02:43:48');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'ID quyền',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên quyền',
  `description` text COLLATE utf8mb4_unicode_ci COMMENT 'Mô tả quyền'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `description`) VALUES
(11, 'view_users', 'Xem danh sách người dùng'),
(12, 'create_users', 'Tạo người dùng mới'),
(13, 'edit_users', 'Chỉnh sửa người dùng'),
(14, 'delete_users', 'Xoá người dùng'),
(15, 'assign_roles', 'Phân quyền tài khoản'),
(16, 'view_doctors', 'Xem danh sách bác sĩ'),
(17, 'create_doctors', 'Thêm bác sĩ mới'),
(18, 'edit_doctors', 'Chỉnh sửa thông tin bác sĩ'),
(19, 'delete_doctors', 'Xoá bác sĩ'),
(20, 'view_departments', 'Xem danh sách phòng ban'),
(21, 'create_departments', 'Thêm phòng ban mới'),
(22, 'edit_departments', 'Chỉnh sửa phòng ban'),
(23, 'delete_departments', 'Xoá phòng ban'),
(24, 'view_schedules', 'Xem lịch làm việc của bác sĩ'),
(25, 'create_schedules', 'Tạo lịch làm việc'),
(26, 'edit_schedules', 'Chỉnh sửa lịch làm việc'),
(27, 'delete_schedules', 'Xoá lịch làm việc'),
(28, 'view_appointments', 'Xem lịch hẹn'),
(29, 'create_appointments', 'Đặt lịch hẹn'),
(30, 'edit_appointments', 'Chỉnh sửa lịch hẹn'),
(31, 'delete_appointments', 'Xoá lịch hẹn'),
(32, 'approve_appointments', 'Duyệt lịch hẹn'),
(33, 'cancel_appointments', 'Huỷ lịch hẹn'),
(34, 'view_services', 'Xem danh sách dịch vụ'),
(35, 'create_services', 'Thêm dịch vụ'),
(36, 'edit_services', 'Chỉnh sửa dịch vụ'),
(37, 'delete_services', 'Xoá dịch vụ'),
(38, 'view_prescriptions', 'Xem đơn thuốc'),
(39, 'create_prescriptions', 'Tạo đơn thuốc'),
(40, 'edit_prescriptions', 'Chỉnh sửa đơn thuốc'),
(41, 'delete_prescriptions', 'Xoá đơn thuốc'),
(42, 'view_coupons', 'Xem mã giảm giá'),
(43, 'create_coupons', 'Tạo mã giảm giá'),
(44, 'edit_coupons', 'Chỉnh sửa mã giảm giá'),
(45, 'delete_coupons', 'Xoá mã giảm giá'),
(46, 'view_orders', 'Xem đơn hàng'),
(47, 'manage_payments', 'Xử lý thanh toán'),
(48, 'view_payment_history', 'Xem lịch sử thanh toán'),
(49, 'view_medical_records', 'Xem hồ sơ bệnh án'),
(50, 'create_medical_records', 'Tạo hồ sơ bệnh án'),
(51, 'edit_medical_records', 'Chỉnh sửa hồ sơ bệnh án'),
(52, 'delete_medical_records', 'Xoá hồ sơ bệnh án'),
(53, 'view_treatment_plans', 'Xem kế hoạch điều trị'),
(54, 'update_treatment_plans', 'Cập nhật kế hoạch điều trị'),
(55, 'upload_files', 'Tải file lên'),
(56, 'delete_files', 'Xoá file'),
(57, 'view_medical_documents', 'Xem tài liệu y tế'),
(58, 'view_reviews', 'Xem đánh giá'),
(59, 'delete_reviews', 'Xoá đánh giá'),
(60, 'manage_support_content', 'Quản lý nội dung hỗ trợ'),
(61, 'send_notifications', 'Gửi thông báo'),
(62, 'view_statistics', 'Xem thống kê');

-- --------------------------------------------------------

--
-- Table structure for table `prescriptions`
--

CREATE TABLE `prescriptions` (
  `id` bigint UNSIGNED NOT NULL,
  `medical_record_id` bigint UNSIGNED NOT NULL,
  `prescribed_at` datetime DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `prescriptions`
--

INSERT INTO `prescriptions` (`id`, `medical_record_id`, `prescribed_at`, `notes`) VALUES
(1, 3, '2025-05-22 02:43:48', 'Sed sint itaque reprehenderit omnis est.'),
(2, 8, '2025-05-22 02:43:48', 'Ea perspiciatis odit expedita.'),
(3, 15, '2025-05-22 02:43:48', 'Saepe ut iure et iste enim officia.'),
(4, 4, '2025-05-22 02:43:48', 'Magni repudiandae dolore est aliquid.'),
(5, 12, '2025-05-22 02:43:48', 'Et autem voluptatibus dolores.'),
(6, 5, '2025-05-22 02:43:48', 'Nihil velit beatae odit architecto a.'),
(7, 11, '2025-05-22 02:43:48', 'Necessitatibus aperiam atque soluta ut voluptatem occaecati fugit.'),
(8, 10, '2025-05-22 02:43:48', 'Sapiente expedita aut et animi.'),
(9, 15, '2025-05-22 02:43:48', 'Soluta provident nemo dolorum perferendis velit esse et.'),
(10, 10, '2025-05-22 02:43:48', 'Dolores blanditiis similique beatae in in itaque molestiae accusantium.'),
(11, 4, '2025-05-22 02:43:48', 'Officiis reprehenderit corporis sed consectetur eveniet illum velit.'),
(12, 1, '2025-05-22 02:43:48', 'Nam quia quo reprehenderit officiis qui.'),
(13, 7, '2025-05-22 02:43:48', 'Ut odit quas quia nihil ipsa autem.'),
(14, 9, '2025-05-22 02:43:48', 'Incidunt culpa hic fuga.'),
(15, 15, '2025-05-22 02:43:48', 'Quidem consectetur accusantium sint libero voluptatem.');

-- --------------------------------------------------------

--
-- Table structure for table `prescription_items`
--

CREATE TABLE `prescription_items` (
  `id` bigint UNSIGNED NOT NULL,
  `prescription_id` bigint UNSIGNED NOT NULL,
  `medicine_id` bigint UNSIGNED NOT NULL,
  `quantity` int DEFAULT NULL,
  `usage_instructions` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `prescription_items`
--

INSERT INTO `prescription_items` (`id`, `prescription_id`, `medicine_id`, `quantity`, `usage_instructions`) VALUES
(1, 12, 12, 3, 'Ut nisi voluptas et illum veritatis ut nostrum.'),
(2, 6, 15, 3, 'Aut ipsam molestiae dolorum ut hic ad quasi.'),
(3, 6, 1, 3, 'Doloremque et unde recusandae nisi qui placeat.'),
(4, 4, 7, 4, 'Voluptatem voluptatem quidem beatae vel ut.'),
(5, 14, 6, 9, 'Fuga nemo natus quam eum.'),
(6, 14, 4, 9, 'Cupiditate et libero laborum.'),
(7, 13, 4, 4, 'Sint aut ipsum provident iure aperiam enim.'),
(8, 1, 8, 10, 'Laudantium consequatur quia natus.'),
(9, 15, 13, 7, 'Tempore velit iste qui nisi.'),
(10, 11, 7, 10, 'Iste consequatur sunt beatae enim consequatur rem.'),
(11, 8, 11, 9, 'Asperiores provident veritatis aut sed.'),
(12, 11, 4, 3, 'Quia neque maiores commodi quis natus dolores.'),
(13, 15, 6, 8, 'Ut voluptas adipisci id neque eum quod dignissimos voluptatum.'),
(14, 3, 8, 3, 'Alias id provident ipsam ducimus culpa magni eaque.'),
(15, 11, 20, 1, 'Non voluptatibus vel soluta rerum incidunt.');

-- --------------------------------------------------------

--
-- Table structure for table `promotions`
--

CREATE TABLE `promotions` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'ID khuyến mãi',
  `code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã code',
  `description` text COLLATE utf8mb4_unicode_ci COMMENT 'Mô tả',
  `discount_percentage` decimal(5,2) DEFAULT NULL COMMENT 'Phần trăm giảm',
  `valid_from` datetime DEFAULT NULL,
  `valid_until` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `promotions`
--

INSERT INTO `promotions` (`id`, `code`, `description`, `discount_percentage`, `valid_from`, `valid_until`) VALUES
(1, 'PROMO68VF', 'Architecto id ratione animi et aut.', '39.58', '2025-05-22 02:43:48', '2025-06-21 02:43:48'),
(2, 'PROMO45JX', 'Consequatur atque perspiciatis et error est vitae vitae.', '17.25', '2025-05-22 02:43:48', '2025-06-21 02:43:48'),
(3, 'PROMO26JJ', 'Nihil facilis voluptate deleniti quibusdam corporis.', '30.11', '2025-05-22 02:43:48', '2025-06-21 02:43:48'),
(4, 'PROMO06SA', 'Sapiente officiis sequi repudiandae eius ipsa quidem excepturi.', '43.24', '2025-05-22 02:43:48', '2025-06-21 02:43:48'),
(5, 'PROMO41JX', 'Sed sed ut iusto saepe autem doloribus.', '48.61', '2025-05-22 02:43:48', '2025-06-21 02:43:48');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'ID vai trò',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên vai trò',
  `description` text COLLATE utf8mb4_unicode_ci COMMENT 'Mô tả vai trò',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Hic sint consequatur dolorum iure quia ex ut et.', '2025-05-21 19:43:45', '2025-05-21 19:43:45'),
(2, 'doctor', 'Facere dolor id omnis eaque.', '2025-05-21 19:43:45', '2025-05-21 19:43:45'),
(3, 'Metal Fabricator', 'Ut repudiandae aut asperiores voluptate et inventore molestiae.', '2025-05-21 19:43:45', '2025-05-21 19:43:45'),
(4, 'Cleaners of Vehicles', 'Fugiat odio in excepturi ut consequuntur.', '2025-05-21 19:43:45', '2025-05-21 19:43:45'),
(6, 'Lễ tân', NULL, '2025-06-04 01:23:51', '2025-06-04 01:23:51'),
(9, 'ávxxc', NULL, '2025-06-04 09:31:48', '2025-06-04 09:31:48');

-- --------------------------------------------------------

--
-- Table structure for table `role_permission`
--

CREATE TABLE `role_permission` (
  `role_id` bigint UNSIGNED NOT NULL,
  `permission_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_permission`
--

INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES
(1, 11),
(6, 11),
(1, 12),
(1, 13),
(1, 14),
(1, 15),
(1, 16),
(1, 17),
(1, 18),
(1, 19),
(1, 20),
(6, 20),
(1, 21),
(1, 22),
(1, 23),
(1, 24),
(1, 25),
(1, 26),
(6, 26),
(1, 27),
(1, 28),
(1, 29),
(1, 30),
(1, 31),
(1, 32),
(1, 33),
(1, 34),
(1, 35),
(1, 36),
(1, 37),
(1, 38),
(1, 39),
(1, 40),
(1, 41),
(1, 42),
(6, 42),
(1, 43),
(1, 44),
(1, 45),
(1, 46),
(6, 46),
(1, 47),
(6, 47),
(1, 48),
(6, 48),
(1, 49),
(1, 50),
(1, 51),
(1, 52),
(1, 53),
(1, 54),
(1, 55),
(9, 55),
(1, 56),
(1, 57),
(1, 58),
(9, 58),
(1, 59),
(1, 60),
(1, 61),
(1, 62);

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'ID phòng khám',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên phòng',
  `department_id` bigint UNSIGNED DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci COMMENT 'Mô tả phòng',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `name`, `department_id`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Phòng 18', 3, 'Dolorum tempora quo quibusdam quae porro cumque fugiat.', '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(2, 'Phòng 18', 3, 'Labore soluta et minima consequatur blanditiis aliquam.', '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(3, 'Phòng 71', 3, 'Doloribus perferendis impedit voluptatibus consequatur quisquam dolor fugit excepturi.', '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(4, 'Phòng 08', 5, 'Aliquid et dolorem velit autem temporibus sed.', '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(5, 'Phòng 45', 2, 'Natus deleniti quam sed sed incidunt.', '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(6, 'Phòng 07', 5, 'Hic dolor quisquam nihil omnis quis sit perspiciatis.', '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(7, 'Phòng 46', 2, 'Aut blanditiis ea tenetur consequuntur quaerat commodi.', '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(8, 'Phòng 10', 5, 'Architecto aperiam assumenda expedita unde.', '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(9, 'Phòng 83', 5, 'Reprehenderit a nostrum asperiores quaerat sapiente officia.', '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(10, 'Phòng 80', 5, 'Saepe hic perspiciatis id sunt.', '2025-05-21 19:43:47', '2025-05-21 19:43:47');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'ID dịch vụ',
  `service_cate_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên dịch vụ',
  `description` text COLLATE utf8mb4_unicode_ci COMMENT 'Mô tả ngắn về dịch vụ',
  `price` decimal(12,2) DEFAULT NULL COMMENT 'Giá dịch vụ',
  `duration` int DEFAULT NULL COMMENT 'Thời lượng (phút)',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Trạng thái dịch vụ',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian xóa (soft-delete), null nếu chưa bị xóa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `service_cate_id`, `name`, `description`, `price`, `duration`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 4, 'autem non iure', 'Voluptates sunt mollitia odio voluptatem voluptatem id.', '422.43', 62, 'inactive', '2025-05-21 19:43:47', '2025-05-21 19:43:47', NULL),
(2, 5, 'reprehenderit est odit', 'Quod harum rerum est cupiditate consectetur et.', '309.67', 61, 'inactive', '2025-05-21 19:43:47', '2025-05-21 19:43:47', NULL),
(3, 2, 'voluptatibus est alias', 'Nobis a qui adipisci odio ratione deserunt.', '231.55', 49, 'inactive', '2025-05-21 19:43:47', '2025-05-21 19:43:47', NULL),
(4, 3, 'dolores cumque inventore', 'Deleniti quia illo et reprehenderit ab.', '482.84', 84, 'inactive', '2025-05-21 19:43:47', '2025-05-21 19:43:47', NULL),
(5, 3, 'sunt aliquam omnis', 'Blanditiis qui ea iusto veniam earum quaerat et.', '335.93', 88, 'inactive', '2025-05-21 19:43:47', '2025-05-21 19:43:47', NULL),
(6, 5, 'qui amet molestiae', 'Reiciendis error illo vero corporis eaque.', '347.62', 63, 'inactive', '2025-05-21 19:43:47', '2025-05-21 19:43:47', NULL),
(7, 4, 'eligendi consectetur molestias', 'Quam officia consequatur iusto repudiandae velit.', '482.25', 77, 'inactive', '2025-05-21 19:43:47', '2025-05-21 19:43:47', NULL),
(8, 2, 'aut aut et', 'Quod beatae repudiandae qui qui expedita magnam.', '271.92', 58, 'inactive', '2025-05-21 19:43:47', '2025-05-21 19:43:47', NULL),
(9, 3, 'sint autem nostrum', 'Iste quibusdam architecto nostrum et.', '138.11', 87, 'active', '2025-05-21 19:43:47', '2025-05-21 19:43:47', NULL),
(10, 1, 'ut ducimus quibusdam', 'Et officia error veritatis non assumenda eos.', '412.30', 72, 'inactive', '2025-05-21 19:43:47', '2025-05-21 19:43:47', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `service_categories`
--

CREATE TABLE `service_categories` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'ID danh mục dịch vụ',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên danh mục',
  `description` text COLLATE utf8mb4_unicode_ci COMMENT 'Mô tả danh mục',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_categories`
--

INSERT INTO `service_categories` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'voluptas placeat', 'Quod sequi pariatur illum repudiandae ea ut iste.', '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(2, 'id atque', 'Exercitationem dolores ratione et quasi.', '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(3, 'natus quas', 'Porro maxime et et veniam quia qui ea.', '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(4, 'nesciunt debitis', 'In enim commodi non.', '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(5, 'et assumenda', 'Necessitatibus et voluptas voluptas delectus eos expedita.', '2025-05-21 19:43:47', '2025-05-21 19:43:47');

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
('F2PV6MY1zstFZl5akOsyktlfojoqrOFk4CKi1YKq', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiSkNCbms2dHZ5c0ZRYlZCZG8yOEt3TWpMb0dTWnk5S0pBelAxTHFCciI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3JvbGVzIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9zY2hlZHVsZXMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6NzoidXNlcl9pZCI7aToxO30=', 1749058102);

-- --------------------------------------------------------

--
-- Table structure for table `treatment_histories`
--

CREATE TABLE `treatment_histories` (
  `id` bigint UNSIGNED NOT NULL,
  `patient_id` bigint UNSIGNED NOT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `treatment_description` text COLLATE utf8mb4_unicode_ci,
  `treatment_date` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `treatment_histories`
--

INSERT INTO `treatment_histories` (`id`, `patient_id`, `doctor_id`, `treatment_description`, `treatment_date`, `created_at`) VALUES
(1, 1, 6, 'Repellendus cumque eos alias aspernatur.', '2025-05-08 02:23:09', '2025-05-22 02:43:48'),
(2, 5, 3, 'Dolore in voluptatem reprehenderit assumenda dolorem.', '2025-05-02 20:06:31', '2025-05-22 02:43:48'),
(3, 10, 7, 'Officia corrupti unde nesciunt tenetur voluptas odio impedit.', '2025-04-27 19:27:46', '2025-05-22 02:43:48'),
(4, 6, 10, 'Nobis et soluta ut deleniti aspernatur.', '2025-04-29 23:40:55', '2025-05-22 02:43:48'),
(5, 2, 10, 'Ut ea voluptas sint voluptatem et commodi.', '2025-05-15 18:45:59', '2025-05-22 02:43:48'),
(6, 5, 5, 'Vel ut porro molestias magni rerum.', '2025-04-27 05:20:14', '2025-05-22 02:43:48'),
(7, 3, 8, 'Et et aliquid temporibus id provident.', '2025-05-21 10:08:30', '2025-05-22 02:43:48'),
(8, 6, 3, 'Neque saepe mollitia quas qui quia porro quidem cupiditate.', '2025-05-18 20:52:52', '2025-05-22 02:43:48'),
(9, 10, 7, 'Reprehenderit cupiditate quae omnis hic sit qui.', '2025-04-26 15:51:23', '2025-05-22 02:43:48'),
(10, 2, 3, 'Quis cumque nulla nisi minima dignissimos delectus vero aut.', '2025-05-17 14:49:58', '2025-05-22 02:43:48'),
(11, 7, 8, 'Quos pariatur quaerat ut quia.', '2025-05-21 23:50:19', '2025-05-22 02:43:48'),
(12, 7, 7, 'Quo eligendi quia consectetur est libero.', '2025-05-20 05:32:42', '2025-05-22 02:43:48'),
(13, 1, 7, 'Veritatis ea est aut.', '2025-05-10 07:46:59', '2025-05-22 02:43:48'),
(14, 1, 5, 'Eum doloribus non voluptatem.', '2025-04-22 13:51:23', '2025-05-22 02:43:48'),
(15, 10, 4, 'Earum enim soluta et voluptas nostrum animi dolorum.', '2025-05-06 23:41:07', '2025-05-22 02:43:48');

-- --------------------------------------------------------

--
-- Table structure for table `treatment_plans`
--

CREATE TABLE `treatment_plans` (
  `id` bigint UNSIGNED NOT NULL,
  `patient_id` bigint UNSIGNED NOT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `plan_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_estimated_cost` decimal(10,2) DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `treatment_plans`
--

INSERT INTO `treatment_plans` (`id`, `patient_id`, `doctor_id`, `plan_title`, `total_estimated_cost`, `notes`, `created_at`) VALUES
(1, 3, 5, 'Điều trị facilis', '1822.64', 'Voluptas voluptatum incidunt magni in.', '2025-05-22 02:43:48'),
(2, 8, 2, 'Điều trị consequatur', '1909.92', NULL, '2025-05-22 02:43:48'),
(3, 8, 2, 'Điều trị molestiae', '1916.73', 'Necessitatibus necessitatibus cumque corporis hic est.', '2025-05-22 02:43:48'),
(4, 10, 8, 'Điều trị ipsa', '1474.37', 'Pariatur laborum ipsa beatae quisquam.', '2025-05-22 02:43:48'),
(5, 2, 4, 'Điều trị aut', '1986.08', 'Delectus optio sunt facere molestiae.', '2025-05-22 02:43:48'),
(6, 2, 8, 'Điều trị repellat', '760.97', NULL, '2025-05-22 02:43:48'),
(7, 8, 1, 'Điều trị deleniti', '1384.54', NULL, '2025-05-22 02:43:48'),
(8, 9, 5, 'Điều trị est', '1064.63', 'Esse quo facere nobis qui officiis.', '2025-05-22 02:43:48'),
(9, 8, 7, 'Điều trị nostrum', '1122.05', NULL, '2025-05-22 02:43:48'),
(10, 4, 9, 'Điều trị modi', '1343.13', NULL, '2025-05-22 02:43:48');

-- --------------------------------------------------------

--
-- Table structure for table `upload_histories`
--

CREATE TABLE `upload_histories` (
  `id` bigint UNSIGNED NOT NULL,
  `file_upload_id` bigint UNSIGNED NOT NULL,
  `action` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `timestamp` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `upload_histories`
--

INSERT INTO `upload_histories` (`id`, `file_upload_id`, `action`, `timestamp`) VALUES
(1, 14, 'upload', '2025-05-22 02:43:48'),
(2, 1, 'delete', '2025-05-22 02:43:48'),
(3, 15, 'delete', '2025-05-22 02:43:48'),
(4, 14, 'delete', '2025-05-22 02:43:48'),
(5, 9, 'update', '2025-05-22 02:43:48'),
(6, 2, 'update', '2025-05-22 02:43:48'),
(7, 1, 'upload', '2025-05-22 02:43:48'),
(8, 12, 'delete', '2025-05-22 02:43:48'),
(9, 2, 'update', '2025-05-22 02:43:48'),
(10, 5, 'update', '2025-05-22 02:43:48'),
(11, 6, 'update', '2025-05-22 02:43:48'),
(12, 11, 'upload', '2025-05-22 02:43:48'),
(13, 1, 'upload', '2025-05-22 02:43:48'),
(14, 9, 'delete', '2025-05-22 02:43:48'),
(15, 15, 'delete', '2025-05-22 02:43:48'),
(16, 8, 'delete', '2025-05-22 02:43:48'),
(17, 12, 'delete', '2025-05-22 02:43:48'),
(18, 5, 'delete', '2025-05-22 02:43:48'),
(19, 13, 'update', '2025-05-22 02:43:48'),
(20, 9, 'update', '2025-05-22 02:43:48'),
(21, 13, 'update', '2025-05-22 02:43:48'),
(22, 11, 'upload', '2025-05-22 02:43:48'),
(23, 10, 'upload', '2025-05-22 02:43:48'),
(24, 13, 'delete', '2025-05-22 02:43:48'),
(25, 11, 'delete', '2025-05-22 02:43:48'),
(26, 15, 'delete', '2025-05-22 02:43:48'),
(27, 3, 'upload', '2025-05-22 02:43:48'),
(28, 3, 'update', '2025-05-22 02:43:48'),
(29, 3, 'delete', '2025-05-22 02:43:48'),
(30, 12, 'delete', '2025-05-22 02:43:48');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'ID người dùng',
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên đăng nhập',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mật khẩu đã mã hóa',
  `full_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Họ tên đầy đủ',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số điện thoại',
  `gender` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Giới tính',
  `date_of_birth` date DEFAULT NULL COMMENT 'Ngày sinh',
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Địa chỉ',
  `role_id` bigint UNSIGNED DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Đường dẫn ảnh đại diện',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `full_name`, `email`, `email_verified_at`, `remember_token`, `phone`, `gender`, `date_of_birth`, `address`, `role_id`, `avatar`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$12$eyV44RucASJxEWZ2myrF3OtOVftfPCX/hkba8.AKUIFIURSY0p7TK', 'Brown Collier', 'fbosco@example.org', NULL, NULL, '+16517360531', 'Nam', '1992-06-18', '3333 Cloyd Estates Suite 625\nEttietown, IN 22151-6966', 1, 'https://via.placeholder.com/200x200.png/006600?text=people+quas', '2025-05-21 19:43:45', '2025-05-21 19:43:45'),
(2, 'client', '$2y$12$ev4hvikp9hGWYLmUxnw1OuAOUyXbj99nZ2Of7IZIaWZ94FLo4XqBO', 'Dr. Jeffrey Nitzsche', 'zharvey@example.com', NULL, NULL, '1-575-255-0452', 'Nam', '2000-01-12', '468 Frami Drive\nSouth Eddiefurt, CT 27002-5733', 2, 'https://via.placeholder.com/200x200.png/0022cc?text=people+repellat', '2025-05-21 19:43:45', '2025-05-21 19:43:45'),
(3, 'margaret.carroll', '$2y$12$CsbVFwJgGzM1kltAwdg3je5X4v47LkT3mt6Eis4DJrPwlObqaLIFK', 'Mrs. Christelle Torp Sr.', 'desiree.mann@example.com', NULL, NULL, '785.557.3840', 'Nữ', '1999-07-05', '64435 Eryn Mountains\nWest Leopoldview, OK 49880', 1, 'https://via.placeholder.com/200x200.png/003388?text=people+cupiditate', '2025-05-21 19:43:45', '2025-05-21 19:43:45'),
(4, 'brakus.cassandra', '$2y$12$n/zb5FYbjdUqag85fJpXiO5ysw/ErRFtL6sTLKZr9UoVrBitnuZR2', 'Delphine Kihn', 'bartoletti.lila@example.net', NULL, NULL, '+1 (954) 401-0871', 'Nữ', '2015-04-05', '6957 Morissette Locks Apt. 418\nOrtizburgh, AL 32046', NULL, 'https://via.placeholder.com/200x200.png/002244?text=people+culpa', '2025-05-21 19:43:46', '2025-05-21 19:43:46'),
(5, 'smith.ova', '$2y$12$4jXrVHV9bu71btNgyJrFmeO2brikqSRvoPmbTW/4N6XzCLOv.G6l6', 'Dr. Dedric Barton MD', 'zjast@example.com', NULL, NULL, '805-803-5202', 'Nữ', '2019-07-09', '465 Donnelly Stream Apt. 348\nNew Walker, WY 50374-7078', 4, 'https://via.placeholder.com/200x200.png/00cc44?text=people+consequatur', '2025-05-21 19:43:46', '2025-05-21 19:43:46'),
(6, 'wisozk.daryl', '$2y$12$B32CL46VJBUUOKWnjU0WFOOynSegWvj1jMO0QCh0HUvyt1NUWrSfO', 'Dr. Jared Monahan', 'mcdermott.tyreek@example.net', NULL, NULL, '(651) 918-4377', 'Nữ', '2009-07-06', '20899 Koelpin Grove\nCreminfurt, KY 77787-4820', 3, 'https://via.placeholder.com/200x200.png/00ff00?text=people+maxime', '2025-05-21 19:43:46', '2025-05-21 19:43:46'),
(7, 'bruen.maryjane', '$2y$12$9AUcr3v26xXI2JP1gXPi7.j7ApBDp2/h1EAstJ7j.3AkGQmOsGjGa', 'Derick West', 'crawford.schneider@example.org', NULL, NULL, '+1-540-365-1205', 'Nam', '1980-01-10', '999 Marco Knoll\nJamarcuschester, MD 04225-3825', 1, 'https://via.placeholder.com/200x200.png/00cc66?text=people+ad', '2025-05-21 19:43:46', '2025-05-21 19:43:46'),
(8, 'yasmin.balistreri', '$2y$12$GhwTXxcAffrg5psHDuGtI.IOewOZdgRb0jmo4mCOU/WiTlXJ2eg36', 'Jerrod Murray', 'king58@example.org', NULL, NULL, '+1 (906) 674-4320', 'Nam', '1981-06-02', '1469 Dicki Mews Suite 694\nNorth Karley, OR 59993-8583', 3, 'https://via.placeholder.com/200x200.png/005522?text=people+cum', '2025-05-21 19:43:46', '2025-05-21 19:43:46'),
(9, 'wiza.collin', '$2y$12$nAGOCcJwbRW/in9nqGdgKuSw7JXUsQP7PEvBZVx9rzKT6zkhMyufy', 'Valerie Jakubowski', 'bosco.darrin@example.net', NULL, NULL, '325.921.7310', 'Nam', '2011-08-15', '68715 Ramon Junctions\nPort Karlee, TX 61203', NULL, 'https://via.placeholder.com/200x200.png/00cc77?text=people+suscipit', '2025-05-21 19:43:47', '2025-05-21 19:43:47'),
(10, 'rbernhard', '$2y$12$HHABjyx.SgLuhpNaUkZVY.b5fOKdn.qUhDX5eDRzjFBVo4kq.YLO6', 'Gerry Gottlieb', 'swift.isaac@example.net', NULL, NULL, '+1-559-829-7375', 'Nữ', '1991-08-16', '5363 Gerlach Trail Suite 390\nPort Jany, MN 25925', NULL, 'https://via.placeholder.com/200x200.png/00ccbb?text=people+rerum', '2025-05-21 19:43:47', '2025-05-21 19:43:47');

-- --------------------------------------------------------

--
-- Table structure for table `working_schedules`
--

CREATE TABLE `working_schedules` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'ID lịch làm việc',
  `doctor_id` bigint UNSIGNED NOT NULL,
  `day_of_week` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Thứ trong tuần',
  `start_time` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Giờ bắt đầu',
  `end_time` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Giờ kết thúc',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `working_schedules`
--

INSERT INTO `working_schedules` (`id`, `doctor_id`, `day_of_week`, `start_time`, `end_time`, `created_at`, `updated_at`) VALUES
(1, 10, 'Tuesday', '08:00', '17:00', NULL, NULL),
(2, 10, 'Tuesday', '08:00', '17:00', NULL, NULL),
(3, 4, 'Saturday', '08:00', '17:00', NULL, NULL),
(4, 8, 'Saturday', '08:00', '17:00', NULL, NULL),
(5, 8, 'Tuesday', '08:00', '17:00', NULL, NULL),
(6, 5, 'Tuesday', '08:00', '17:00', NULL, NULL),
(7, 4, 'Tuesday', '08:00', '17:00', NULL, NULL),
(8, 7, 'Monday', '08:00', '17:00', NULL, NULL),
(9, 10, 'Sunday', '08:00', '17:00', NULL, NULL),
(10, 9, 'Wednesday', '08:00', '17:00', NULL, NULL),
(11, 9, 'Tuesday', '08:00', '17:00', NULL, NULL),
(12, 6, 'Monday', '08:00', '17:00', NULL, NULL),
(13, 6, 'Thursday', '08:00', '17:00', NULL, NULL),
(14, 5, 'Friday', '08:00', '17:00', NULL, NULL),
(15, 4, 'Thursday', '08:00', '17:00', NULL, NULL),
(16, 4, 'Friday', '08:00', '17:00', NULL, NULL),
(17, 5, 'Thursday', '08:00', '17:00', NULL, NULL),
(18, 10, 'Friday', '08:00', '17:00', NULL, NULL),
(19, 9, 'Thursday', '08:00', '17:00', NULL, NULL),
(20, 10, 'Sunday', '08:00', '17:00', NULL, NULL),
(21, 1, 'Sunday', '08:00', '17:00', NULL, NULL),
(22, 1, 'Sunday', '08:00', '17:00', NULL, NULL),
(23, 2, 'Thursday', '08:00', '17:00', NULL, NULL),
(24, 3, 'Tuesday', '08:00', '17:00', NULL, NULL),
(25, 2, 'Tuesday', '08:00', '17:00', NULL, NULL),
(26, 4, 'Thursday', '08:00', '17:00', NULL, NULL),
(27, 9, 'Monday', '08:00', '17:00', NULL, NULL),
(28, 2, 'Saturday', '08:00', '17:00', NULL, NULL),
(29, 9, 'Friday', '08:00', '17:00', NULL, NULL),
(30, 9, 'Thursday', '08:00', '17:00', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointments_patient_id_foreign` (`patient_id`),
  ADD KEY `appointments_doctor_id_foreign` (`doctor_id`),
  ADD KEY `appointments_service_id_foreign` (`service_id`);

--
-- Indexes for table `appointment_logs`
--
ALTER TABLE `appointment_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointment_logs_appointment_id_foreign` (`appointment_id`),
  ADD KEY `appointment_logs_changed_by_foreign` (`changed_by`);

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blogs_author_id_foreign` (`author_id`);

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
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctors_user_id_foreign` (`user_id`),
  ADD KEY `doctors_room_id_foreign` (`room_id`),
  ADD KEY `doctors_department_id_foreign` (`department_id`);

--
-- Indexes for table `doctor_leaves`
--
ALTER TABLE `doctor_leaves`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_leaves_doctor_id_foreign` (`doctor_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `file_uploads`
--
ALTER TABLE `file_uploads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `file_uploads_user_id_foreign` (`user_id`),
  ADD KEY `file_uploads_appointment_id_foreign` (`appointment_id`);

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
-- Indexes for table `medical_records`
--
ALTER TABLE `medical_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medical_records_appointment_id_foreign` (`appointment_id`);

--
-- Indexes for table `medicines`
--
ALTER TABLE `medicines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_foreign` (`user_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_appointment_id_foreign` (`appointment_id`),
  ADD KEY `payments_promotion_id_foreign` (`promotion_id`);

--
-- Indexes for table `payment_histories`
--
ALTER TABLE `payment_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_histories_payment_id_foreign` (`payment_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prescriptions_medical_record_id_foreign` (`medical_record_id`);

--
-- Indexes for table `prescription_items`
--
ALTER TABLE `prescription_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prescription_items_prescription_id_foreign` (`prescription_id`),
  ADD KEY `prescription_items_medicine_id_foreign` (`medicine_id`);

--
-- Indexes for table `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_permission`
--
ALTER TABLE `role_permission`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `role_permission_permission_id_foreign` (`permission_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rooms_department_id_foreign` (`department_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `services_service_cate_id_foreign` (`service_cate_id`);

--
-- Indexes for table `service_categories`
--
ALTER TABLE `service_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `treatment_histories`
--
ALTER TABLE `treatment_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `treatment_histories_patient_id_foreign` (`patient_id`),
  ADD KEY `treatment_histories_doctor_id_foreign` (`doctor_id`);

--
-- Indexes for table `treatment_plans`
--
ALTER TABLE `treatment_plans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `treatment_plans_patient_id_foreign` (`patient_id`),
  ADD KEY `treatment_plans_doctor_id_foreign` (`doctor_id`);

--
-- Indexes for table `upload_histories`
--
ALTER TABLE `upload_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `upload_histories_file_upload_id_foreign` (`file_upload_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- Indexes for table `working_schedules`
--
ALTER TABLE `working_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `working_schedules_doctor_id_foreign` (`doctor_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID lịch hẹn', AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `appointment_logs`
--
ALTER TABLE `appointment_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID log cuộc hẹn', AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID phòng ban', AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID bác sĩ', AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `doctor_leaves`
--
ALTER TABLE `doctor_leaves`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID nghỉ phép', AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `file_uploads`
--
ALTER TABLE `file_uploads`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medical_records`
--
ALTER TABLE `medical_records`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `medicines`
--
ALTER TABLE `medicines`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID thanh toán', AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `payment_histories`
--
ALTER TABLE `payment_histories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID lịch sử thanh toán', AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID quyền', AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `prescriptions`
--
ALTER TABLE `prescriptions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `prescription_items`
--
ALTER TABLE `prescription_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID khuyến mãi', AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID vai trò', AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID phòng khám', AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID dịch vụ', AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `service_categories`
--
ALTER TABLE `service_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID danh mục dịch vụ', AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `treatment_histories`
--
ALTER TABLE `treatment_histories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `treatment_plans`
--
ALTER TABLE `treatment_plans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `upload_histories`
--
ALTER TABLE `upload_histories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID người dùng', AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `working_schedules`
--
ALTER TABLE `working_schedules`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID lịch làm việc', AUTO_INCREMENT=31;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `appointment_logs`
--
ALTER TABLE `appointment_logs`
  ADD CONSTRAINT `appointment_logs_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointment_logs_changed_by_foreign` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `blogs`
--
ALTER TABLE `blogs`
  ADD CONSTRAINT `blogs_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `doctors_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `doctors_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `doctor_leaves`
--
ALTER TABLE `doctor_leaves`
  ADD CONSTRAINT `doctor_leaves_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `file_uploads`
--
ALTER TABLE `file_uploads`
  ADD CONSTRAINT `file_uploads_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `file_uploads_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `medical_records`
--
ALTER TABLE `medical_records`
  ADD CONSTRAINT `medical_records_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_promotion_id_foreign` FOREIGN KEY (`promotion_id`) REFERENCES `promotions` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `payment_histories`
--
ALTER TABLE `payment_histories`
  ADD CONSTRAINT `payment_histories_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD CONSTRAINT `prescriptions_medical_record_id_foreign` FOREIGN KEY (`medical_record_id`) REFERENCES `medical_records` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `prescription_items`
--
ALTER TABLE `prescription_items`
  ADD CONSTRAINT `prescription_items_medicine_id_foreign` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prescription_items_prescription_id_foreign` FOREIGN KEY (`prescription_id`) REFERENCES `prescriptions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_permission`
--
ALTER TABLE `role_permission`
  ADD CONSTRAINT `role_permission_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permission_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_service_cate_id_foreign` FOREIGN KEY (`service_cate_id`) REFERENCES `service_categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `treatment_histories`
--
ALTER TABLE `treatment_histories`
  ADD CONSTRAINT `treatment_histories_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `treatment_histories_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `treatment_plans`
--
ALTER TABLE `treatment_plans`
  ADD CONSTRAINT `treatment_plans_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `treatment_plans_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `upload_histories`
--
ALTER TABLE `upload_histories`
  ADD CONSTRAINT `upload_histories_file_upload_id_foreign` FOREIGN KEY (`file_upload_id`) REFERENCES `file_uploads` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `working_schedules`
--
ALTER TABLE `working_schedules`
  ADD CONSTRAINT `working_schedules_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
