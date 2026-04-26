-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 08, 2026 at 08:41 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `roomtestdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `activity_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `grades` varchar(255) DEFAULT NULL,
  `due_date` datetime DEFAULT NULL,
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_archived` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`activity_id`, `name`, `description`, `grades`, `due_date`, `section_id`, `user_id`, `created_at`, `updated_at`, `is_archived`) VALUES
(1, 'The \"Rush Hour\" POS Simulation', 'Students are split into \"Servers\" and \"Customers.\" Customers place rapid-fire orders with complex modifications (e.g., \"Medium-rare steak, no butter, sub fries for salad\"). Servers must accurately input these into a POS simulator. This activity teaches order entry precision and the use of modifiers to communicate clearly with the kitchen.', '100', '2026-01-28 11:59:00', 3, 1, '2026-01-23 01:05:12', '2026-01-23 01:24:16', 0),
(3, 'Real-Time Menu Management', 'Students are given a scenario where a key ingredient (e.g., salmon) has run out. They must use the POS system to \"86\" (deactivate) all related dishes across all terminals instantly. They then analyze POS data to suggest a \"Daily Special\" based on overstocked inventory items that need to be sold quickly.', NULL, '2026-02-07 03:59:00', 3, 1, '2026-01-23 01:26:24', '2026-01-23 01:45:05', 0),
(7, 'Managing B2B Operations', 'Students review a month of \"Receiving Logs\" against \"Purchase Orders\" in the system. They must identify which suppliers are consistently short-shipping or overcharging. This activity teaches how to use administrative data to evaluate business-to-business operations.', '10', '2026-01-31 11:59:00', 3, 1, '2026-01-23 03:19:39', '2026-01-23 03:19:39', 0),
(8, 'The Discrepancy Detective', 'Provide students with a digital inventory list of items (e.g., 50 bottles of water, 20 kg of coffee). Then, have them perform a manual count of a \"mini-warehouse.\" When the numbers don\'t match, students must investigate why—identifying common issues like unrecorded waste, theft, or data entry errors.', '10', NULL, 3, 1, '2026-01-26 03:23:57', '2026-01-26 03:23:57', 0),
(9, 'Setting the Safety Net', 'Students assign \"Par Levels\" (minimum stock) for various hospitality supplies (linens, sodas, toiletries). They must program these into a system and simulate a week of sales. The goal is to see if their reorder points successfully trigger automated purchase orders before the business runs out of stock.', '10', NULL, 3, 1, '2026-01-26 03:24:18', '2026-01-26 03:24:18', 0),
(10, 'Mastering Table Management', 'A \"table of six\" wants to split their bill: three pay for their own meals, two split a bottle of wine, and one pays for everyone’s appetizers. Students must use POS billing functions to split, merge, and transfer checks without errors, reflecting complex business-to-consumer transactions.', '10', NULL, 3, 1, '2026-01-26 03:24:36', '2026-01-26 03:24:36', 0),
(11, 'From Ingredient to Invoice', 'Using inventory software, students input a recipe (e.g., a Club Sandwich) and its individual ingredient costs. As they \"sell\" the sandwich on the POS, they monitor how the system automatically deducts slices of bread and grams of ham from the inventory, calculating the theoretical vs. actual food cost.', '10', '2026-02-28 11:59:00', 3, 1, '2026-01-26 03:25:05', '2026-01-26 03:25:05', 0),
(12, 'Prioritizing High-Value Assets', 'Students categorize a list of 50 hotel items into A (High value, e.g., Premium Liquor), B (Moderate, e.g., Uniforms), and C (Low, e.g., Napkins). They must then design different inventory control technical processes for each, such as daily counts for \"A\" items versus monthly for \"C\" items.', '10', '2026-01-31 23:59:00', 3, 1, '2026-01-26 03:25:31', '2026-01-27 12:41:15', 0),
(13, 'Tracking Invisible Loss', 'Students are given a POS sales report and an Inventory usage report that don\'t match. They must investigate \"Ghost Inventory\"—items that were deducted from stock but never sold (e.g., kitchen errors, complimentary drinks, or spills). They will practice logging \"Waste\" in the system to ensure the digital inventory stays accurate', '10', '2026-01-27 23:59:00', 3, 1, '2026-01-26 03:49:02', '2026-01-26 03:49:02', 0),
(14, 'Bulk Database Management', 'Students are tasked with updating the POS for a \"Summer Menu\" launch. This involves bulk-importing new SKUs (Stock Keeping Units), setting effective dates, and archiving old inventory items that are no longer being purchased from suppliers.', '10', '2026-01-26 23:59:00', 5, 1, '2026-01-26 06:52:51', '2026-01-26 06:52:51', 0),
(15, 'Raw to Real-Time Inventory', 'Students perform a physical yield test (e.g., weighing a whole chicken vs. the usable meat after prep). They must then input this yield percentage into the inventory system so that when the POS sells one \"Roasted Chicken,\" the system correctly deducts the proportional \"Raw Weight\" from the freezer stock.', '10', '2026-01-26 23:59:00', 5, 1, '2026-01-26 07:09:06', '2026-01-26 07:09:06', 0),
(16, 'When the Cloud Goes Dark', 'What happens if the POS internet goes down? Students must practice the manual \"Offline Mode\" procedures. They will learn how to take manual orders and handwritten \"chits,\" and then \"sync\" or back-enter the data into the system once the \"technology\" is restored to ensure inventory levels are corrected.', '10', NULL, 3, 1, '2026-01-27 13:02:15', '2026-01-27 13:02:15', 0);

-- --------------------------------------------------------

--
-- Table structure for table `activity_user_role`
--

CREATE TABLE `activity_user_role` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `activity_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_user_role`
--

INSERT INTO `activity_user_role` (`id`, `activity_id`, `user_id`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 1, '2026-01-26 03:47:21', '2026-01-26 03:47:21'),
(2, 16, 9, 1, '2026-02-05 00:38:36', '2026-02-05 00:38:36');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_archived` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `section_id`, `user_id`, `content`, `created_at`, `updated_at`, `is_archived`) VALUES
(5, 6, 1, 'test', '2026-01-27 01:17:41', '2026-01-27 01:17:41', 0),
(10, 3, 1, 'test', '2026-01-27 12:35:21', '2026-01-27 12:35:21', 0),
(12, 3, 1, 'AYOKO NA MAG CAPSTONEEEEEE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!', '2026-02-04 08:45:40', '2026-02-04 08:45:40', 0);

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE `attachments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `announcement_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `url` text NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attachments`
--

INSERT INTO `attachments` (`id`, `announcement_id`, `type`, `url`, `title`, `created_at`, `updated_at`) VALUES
(1, 5, 'link', 'https://gemini.google.com/u/1/app/69a4fcc969cace7f', NULL, '2026-01-27 01:17:41', '2026-01-27 01:17:41'),
(2, 5, 'youtube', 'https://www.youtube.com/watch?v=Rht8rS4cR1s&list=RDMMgv_aioUQiQU&index=12', NULL, '2026-01-27 01:17:41', '2026-01-27 01:17:41'),
(10, 10, 'gdrive', 'https://drive.google.com/drive/folders/1VqB1v4u5lVpN9UWp80G0Hw-d7EZ-n0NM?usp=drive_link', NULL, '2026-01-27 12:35:21', '2026-01-27 12:35:21'),
(11, 10, 'youtube', 'https://www.youtube.com/watch?v=Rht8rS4cR1s&list=RDMMgv_aioUQiQU&index=13', 'Multo - Cup of Joe (Official Lyric Video)', '2026-01-27 12:35:21', '2026-01-27 12:35:21'),
(12, 10, 'link', 'https://intrams.clsu.edu.ph/', NULL, '2026-01-27 12:35:21', '2026-01-27 12:35:21'),
(13, 10, 'file', 'announcements/files/LUqFAfiWbZqSUwRPRpWafkUf6zNVXGpq6FU1qEiX.docx', 'auto letter.docx', '2026-01-27 12:35:22', '2026-01-27 12:35:22');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('restausim-cache-0ade7c2cf97f75d009975f4d720d1fa6c19f4897', 'i:1;', 1770007611),
('restausim-cache-0ade7c2cf97f75d009975f4d720d1fa6c19f4897:timer', 'i:1770007611;', 1770007611),
('restausim-cache-356a192b7913b04c54574d18c28d46e6395428ab', 'i:1;', 1769865128),
('restausim-cache-356a192b7913b04c54574d18c28d46e6395428ab:timer', 'i:1769865128;', 1769865128);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_invites`
--

CREATE TABLE `email_invites` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `failed_jobs`
--

INSERT INTO `failed_jobs` (`id`, `uuid`, `connection`, `queue`, `payload`, `exception`, `failed_at`) VALUES
(1, 'df980357-7294-48f9-952c-3adbd6c69e62', 'database', 'default', '{\"uuid\":\"df980357-7294-48f9-952c-3adbd6c69e62\",\"displayName\":\"App\\\\Mail\\\\SectionInviteMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:26:\\\"App\\\\Mail\\\\SectionInviteMail\\\":4:{s:6:\\\"invite\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:23:\\\"App\\\\Models\\\\EmailInvites\\\";s:2:\\\"id\\\";i:9;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:7:\\\"section\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Section\\\";s:2:\\\"id\\\";i:13;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"avbalonzo30@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1763206552,\"delay\":null}', 'Illuminate\\Database\\Eloquent\\ModelNotFoundException: No query results for model [App\\Models\\EmailInvites]. in C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php:765\nStack trace:\n#0 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers.php(110): Illuminate\\Database\\Eloquent\\Builder->firstOrFail()\n#1 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers.php(63): App\\Mail\\SectionInviteMail->restoreModel(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#2 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesModels.php(97): App\\Mail\\SectionInviteMail->getRestoredPropertyValue(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#3 [internal function]: App\\Mail\\SectionInviteMail->__unserialize(Array)\n#4 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(95): unserialize(\'O:34:\"Illuminat...\')\n#5 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(62): Illuminate\\Queue\\CallQueuedHandler->getCommand(Array)\n#6 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#7 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#8 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(394): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#9 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(180): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#10 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#11 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#12 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#13 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#14 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#15 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#16 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(835): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#17 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#18 C:\\xampp\\htdocs\\room\\vendor\\symfony\\console\\Command\\Command.php(318): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#19 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#20 C:\\xampp\\htdocs\\room\\vendor\\symfony\\console\\Application.php(1092): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#21 C:\\xampp\\htdocs\\room\\vendor\\symfony\\console\\Application.php(341): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#22 C:\\xampp\\htdocs\\room\\vendor\\symfony\\console\\Application.php(192): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#23 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(197): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#24 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1234): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#25 C:\\xampp\\htdocs\\room\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#26 {main}', '2025-11-20 08:53:39'),
(2, '542fe0eb-1d76-4463-a558-5c8cc0069a04', 'database', 'default', '{\"uuid\":\"542fe0eb-1d76-4463-a558-5c8cc0069a04\",\"displayName\":\"App\\\\Mail\\\\SectionInviteMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:26:\\\"App\\\\Mail\\\\SectionInviteMail\\\":4:{s:6:\\\"invite\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:23:\\\"App\\\\Models\\\\EmailInvites\\\";s:2:\\\"id\\\";i:10;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:7:\\\"section\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Section\\\";s:2:\\\"id\\\";i:10;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:29:\\\"ronnel.baldovino@clsu2.edu.ph\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1763251857,\"delay\":null}', 'Illuminate\\Database\\Eloquent\\ModelNotFoundException: No query results for model [App\\Models\\EmailInvites]. in C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php:765\nStack trace:\n#0 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers.php(110): Illuminate\\Database\\Eloquent\\Builder->firstOrFail()\n#1 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers.php(63): App\\Mail\\SectionInviteMail->restoreModel(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#2 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesModels.php(97): App\\Mail\\SectionInviteMail->getRestoredPropertyValue(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#3 [internal function]: App\\Mail\\SectionInviteMail->__unserialize(Array)\n#4 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(95): unserialize(\'O:34:\"Illuminat...\')\n#5 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(62): Illuminate\\Queue\\CallQueuedHandler->getCommand(Array)\n#6 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#7 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#8 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(394): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#9 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(180): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#10 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#11 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#12 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#13 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#14 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#15 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#16 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(835): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#17 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#18 C:\\xampp\\htdocs\\room\\vendor\\symfony\\console\\Command\\Command.php(318): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#19 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#20 C:\\xampp\\htdocs\\room\\vendor\\symfony\\console\\Application.php(1092): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#21 C:\\xampp\\htdocs\\room\\vendor\\symfony\\console\\Application.php(341): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#22 C:\\xampp\\htdocs\\room\\vendor\\symfony\\console\\Application.php(192): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#23 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(197): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#24 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1234): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#25 C:\\xampp\\htdocs\\room\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#26 {main}', '2025-11-20 08:53:39'),
(3, '9a64238c-5500-4585-b904-715433231e14', 'database', 'default', '{\"uuid\":\"9a64238c-5500-4585-b904-715433231e14\",\"displayName\":\"App\\\\Mail\\\\SectionInviteMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:26:\\\"App\\\\Mail\\\\SectionInviteMail\\\":4:{s:6:\\\"invite\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:23:\\\"App\\\\Models\\\\EmailInvites\\\";s:2:\\\"id\\\";i:11;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:7:\\\"section\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Section\\\";s:2:\\\"id\\\";i:10;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:18:\\\"hoonp845@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1763251857,\"delay\":null}', 'Illuminate\\Database\\Eloquent\\ModelNotFoundException: No query results for model [App\\Models\\EmailInvites]. in C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php:765\nStack trace:\n#0 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers.php(110): Illuminate\\Database\\Eloquent\\Builder->firstOrFail()\n#1 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers.php(63): App\\Mail\\SectionInviteMail->restoreModel(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#2 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesModels.php(97): App\\Mail\\SectionInviteMail->getRestoredPropertyValue(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#3 [internal function]: App\\Mail\\SectionInviteMail->__unserialize(Array)\n#4 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(95): unserialize(\'O:34:\"Illuminat...\')\n#5 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(62): Illuminate\\Queue\\CallQueuedHandler->getCommand(Array)\n#6 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#7 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#8 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(394): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#9 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(180): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#10 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#11 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#12 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#13 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#14 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#15 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#16 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(835): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#17 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#18 C:\\xampp\\htdocs\\room\\vendor\\symfony\\console\\Command\\Command.php(318): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#19 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#20 C:\\xampp\\htdocs\\room\\vendor\\symfony\\console\\Application.php(1092): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#21 C:\\xampp\\htdocs\\room\\vendor\\symfony\\console\\Application.php(341): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#22 C:\\xampp\\htdocs\\room\\vendor\\symfony\\console\\Application.php(192): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#23 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(197): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#24 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1234): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#25 C:\\xampp\\htdocs\\room\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#26 {main}', '2025-11-20 08:53:39'),
(4, '96bd4d50-b8f5-4a97-9506-0c09d2f0d7b4', 'database', 'default', '{\"uuid\":\"96bd4d50-b8f5-4a97-9506-0c09d2f0d7b4\",\"displayName\":\"App\\\\Mail\\\\SectionInviteMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:26:\\\"App\\\\Mail\\\\SectionInviteMail\\\":4:{s:6:\\\"invite\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:23:\\\"App\\\\Models\\\\EmailInvites\\\";s:2:\\\"id\\\";i:12;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:7:\\\"section\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Section\\\";s:2:\\\"id\\\";i:10;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:22:\\\"mahalko04.hr@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1763251857,\"delay\":null}', 'Illuminate\\Database\\Eloquent\\ModelNotFoundException: No query results for model [App\\Models\\EmailInvites]. in C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php:765\nStack trace:\n#0 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers.php(110): Illuminate\\Database\\Eloquent\\Builder->firstOrFail()\n#1 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers.php(63): App\\Mail\\SectionInviteMail->restoreModel(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#2 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesModels.php(97): App\\Mail\\SectionInviteMail->getRestoredPropertyValue(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#3 [internal function]: App\\Mail\\SectionInviteMail->__unserialize(Array)\n#4 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(95): unserialize(\'O:34:\"Illuminat...\')\n#5 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(62): Illuminate\\Queue\\CallQueuedHandler->getCommand(Array)\n#6 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#7 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#8 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(394): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#9 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(180): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#10 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#11 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#12 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#13 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#14 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#15 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#16 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(835): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#17 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#18 C:\\xampp\\htdocs\\room\\vendor\\symfony\\console\\Command\\Command.php(318): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#19 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#20 C:\\xampp\\htdocs\\room\\vendor\\symfony\\console\\Application.php(1092): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#21 C:\\xampp\\htdocs\\room\\vendor\\symfony\\console\\Application.php(341): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#22 C:\\xampp\\htdocs\\room\\vendor\\symfony\\console\\Application.php(192): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#23 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(197): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#24 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1234): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#25 C:\\xampp\\htdocs\\room\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#26 {main}', '2025-11-20 08:53:39'),
(5, '075153fb-8b8d-43ee-a94f-b1437ed4076d', 'database', 'default', '{\"uuid\":\"075153fb-8b8d-43ee-a94f-b1437ed4076d\",\"displayName\":\"App\\\\Mail\\\\SectionInviteMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:26:\\\"App\\\\Mail\\\\SectionInviteMail\\\":4:{s:6:\\\"invite\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:23:\\\"App\\\\Models\\\\EmailInvites\\\";s:2:\\\"id\\\";i:13;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:7:\\\"section\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Section\\\";s:2:\\\"id\\\";i:10;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:19:\\\"psh794004@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1763251857,\"delay\":null}', 'Illuminate\\Database\\Eloquent\\ModelNotFoundException: No query results for model [App\\Models\\EmailInvites]. in C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php:765\nStack trace:\n#0 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers.php(110): Illuminate\\Database\\Eloquent\\Builder->firstOrFail()\n#1 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers.php(63): App\\Mail\\SectionInviteMail->restoreModel(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#2 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesModels.php(97): App\\Mail\\SectionInviteMail->getRestoredPropertyValue(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#3 [internal function]: App\\Mail\\SectionInviteMail->__unserialize(Array)\n#4 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(95): unserialize(\'O:34:\"Illuminat...\')\n#5 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(62): Illuminate\\Queue\\CallQueuedHandler->getCommand(Array)\n#6 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#7 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#8 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(394): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#9 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(180): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#10 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#11 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#12 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#13 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#14 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#15 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#16 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(835): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#17 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#18 C:\\xampp\\htdocs\\room\\vendor\\symfony\\console\\Command\\Command.php(318): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#19 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#20 C:\\xampp\\htdocs\\room\\vendor\\symfony\\console\\Application.php(1092): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#21 C:\\xampp\\htdocs\\room\\vendor\\symfony\\console\\Application.php(341): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#22 C:\\xampp\\htdocs\\room\\vendor\\symfony\\console\\Application.php(192): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#23 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(197): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#24 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1234): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#25 C:\\xampp\\htdocs\\room\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#26 {main}', '2025-11-20 08:53:39'),
(6, 'b3fd614e-686c-4361-be79-30a3c3b42364', 'database', 'default', '{\"uuid\":\"b3fd614e-686c-4361-be79-30a3c3b42364\",\"displayName\":\"App\\\\Mail\\\\SectionInviteMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:26:\\\"App\\\\Mail\\\\SectionInviteMail\\\":5:{s:6:\\\"invite\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:23:\\\"App\\\\Models\\\\EmailInvites\\\";s:2:\\\"id\\\";i:15;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:7:\\\"section\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Section\\\";s:2:\\\"id\\\";i:11;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:7:\\\"faculty\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:24:\\\"hoonpark113020@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1763627336,\"delay\":null}', 'Illuminate\\Database\\Eloquent\\ModelNotFoundException: No query results for model [App\\Models\\EmailInvites]. in C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php:765\nStack trace:\n#0 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers.php(110): Illuminate\\Database\\Eloquent\\Builder->firstOrFail()\n#1 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers.php(63): App\\Mail\\SectionInviteMail->restoreModel(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#2 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesModels.php(97): App\\Mail\\SectionInviteMail->getRestoredPropertyValue(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#3 [internal function]: App\\Mail\\SectionInviteMail->__unserialize(Array)\n#4 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(95): unserialize(\'O:34:\"Illuminat...\')\n#5 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(62): Illuminate\\Queue\\CallQueuedHandler->getCommand(Array)\n#6 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#7 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#8 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(394): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#9 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(180): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#10 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#11 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#12 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#13 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#14 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#15 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#16 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(835): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#17 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#18 C:\\xampp\\htdocs\\room\\vendor\\symfony\\console\\Command\\Command.php(318): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#19 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#20 C:\\xampp\\htdocs\\room\\vendor\\symfony\\console\\Application.php(1092): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#21 C:\\xampp\\htdocs\\room\\vendor\\symfony\\console\\Application.php(341): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#22 C:\\xampp\\htdocs\\room\\vendor\\symfony\\console\\Application.php(192): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#23 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(197): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#24 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1234): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#25 C:\\xampp\\htdocs\\room\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#26 {main}', '2025-11-20 08:53:39');
INSERT INTO `failed_jobs` (`id`, `uuid`, `connection`, `queue`, `payload`, `exception`, `failed_at`) VALUES
(7, 'ae14c65b-81fa-4953-b737-1f6e2d30838e', 'database', 'default', '{\"uuid\":\"ae14c65b-81fa-4953-b737-1f6e2d30838e\",\"displayName\":\"App\\\\Mail\\\\SectionInviteMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:26:\\\"App\\\\Mail\\\\SectionInviteMail\\\":5:{s:6:\\\"invite\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:23:\\\"App\\\\Models\\\\EmailInvites\\\";s:2:\\\"id\\\";i:16;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:7:\\\"section\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Section\\\";s:2:\\\"id\\\";i:11;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:7:\\\"faculty\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"avbalonzo30@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1763627470,\"delay\":null}', 'Illuminate\\Database\\Eloquent\\ModelNotFoundException: No query results for model [App\\Models\\EmailInvites]. in C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php:765\nStack trace:\n#0 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers.php(110): Illuminate\\Database\\Eloquent\\Builder->firstOrFail()\n#1 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers.php(63): App\\Mail\\SectionInviteMail->restoreModel(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#2 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesModels.php(97): App\\Mail\\SectionInviteMail->getRestoredPropertyValue(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#3 [internal function]: App\\Mail\\SectionInviteMail->__unserialize(Array)\n#4 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(95): unserialize(\'O:34:\"Illuminat...\')\n#5 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(62): Illuminate\\Queue\\CallQueuedHandler->getCommand(Array)\n#6 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#7 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#8 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(394): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#9 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(180): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#10 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#11 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#12 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#13 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#14 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#15 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#16 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(835): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#17 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#18 C:\\xampp\\htdocs\\room\\vendor\\symfony\\console\\Command\\Command.php(318): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#19 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#20 C:\\xampp\\htdocs\\room\\vendor\\symfony\\console\\Application.php(1092): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#21 C:\\xampp\\htdocs\\room\\vendor\\symfony\\console\\Application.php(341): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#22 C:\\xampp\\htdocs\\room\\vendor\\symfony\\console\\Application.php(192): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#23 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(197): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#24 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1234): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#25 C:\\xampp\\htdocs\\room\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#26 {main}', '2025-11-20 08:53:39'),
(8, '38cd9d50-6778-4ec9-b29a-2ace487e9eb1', 'database', 'default', '{\"uuid\":\"38cd9d50-6778-4ec9-b29a-2ace487e9eb1\",\"displayName\":\"App\\\\Mail\\\\SectionInviteMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:26:\\\"App\\\\Mail\\\\SectionInviteMail\\\":5:{s:6:\\\"invite\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:23:\\\"App\\\\Models\\\\EmailInvites\\\";s:2:\\\"id\\\";i:17;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:7:\\\"section\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Section\\\";s:2:\\\"id\\\";i:11;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:7:\\\"faculty\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:18:\\\"hoonp845@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1763627470,\"delay\":null}', 'Illuminate\\Database\\Eloquent\\ModelNotFoundException: No query results for model [App\\Models\\EmailInvites]. in C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php:765\nStack trace:\n#0 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers.php(110): Illuminate\\Database\\Eloquent\\Builder->firstOrFail()\n#1 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers.php(63): App\\Mail\\SectionInviteMail->restoreModel(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#2 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesModels.php(97): App\\Mail\\SectionInviteMail->getRestoredPropertyValue(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#3 [internal function]: App\\Mail\\SectionInviteMail->__unserialize(Array)\n#4 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(95): unserialize(\'O:34:\"Illuminat...\')\n#5 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(62): Illuminate\\Queue\\CallQueuedHandler->getCommand(Array)\n#6 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#7 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#8 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(394): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#9 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(180): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#10 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#11 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#12 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#13 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#14 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#15 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#16 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(835): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#17 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#18 C:\\xampp\\htdocs\\room\\vendor\\symfony\\console\\Command\\Command.php(318): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#19 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#20 C:\\xampp\\htdocs\\room\\vendor\\symfony\\console\\Application.php(1092): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#21 C:\\xampp\\htdocs\\room\\vendor\\symfony\\console\\Application.php(341): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#22 C:\\xampp\\htdocs\\room\\vendor\\symfony\\console\\Application.php(192): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#23 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(197): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#24 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1234): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#25 C:\\xampp\\htdocs\\room\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#26 {main}', '2025-11-20 08:53:39'),
(9, 'a586230f-46ae-498b-93d3-9a633eac94ed', 'database', 'default', '{\"uuid\":\"a586230f-46ae-498b-93d3-9a633eac94ed\",\"displayName\":\"App\\\\Mail\\\\SectionInviteMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:26:\\\"App\\\\Mail\\\\SectionInviteMail\\\":5:{s:6:\\\"invite\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:23:\\\"App\\\\Models\\\\EmailInvites\\\";s:2:\\\"id\\\";i:18;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:7:\\\"section\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Section\\\";s:2:\\\"id\\\";i:11;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:7:\\\"faculty\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:34:\\\"ronnelbaldovino123456789@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1763627471,\"delay\":null}', 'Illuminate\\Database\\Eloquent\\ModelNotFoundException: No query results for model [App\\Models\\EmailInvites]. in C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php:765\nStack trace:\n#0 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers.php(110): Illuminate\\Database\\Eloquent\\Builder->firstOrFail()\n#1 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers.php(63): App\\Mail\\SectionInviteMail->restoreModel(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#2 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesModels.php(97): App\\Mail\\SectionInviteMail->getRestoredPropertyValue(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#3 [internal function]: App\\Mail\\SectionInviteMail->__unserialize(Array)\n#4 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(95): unserialize(\'O:34:\"Illuminat...\')\n#5 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(62): Illuminate\\Queue\\CallQueuedHandler->getCommand(Array)\n#6 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#7 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#8 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(394): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#9 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(180): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#10 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#11 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#12 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#13 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#14 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#15 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#16 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(835): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#17 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#18 C:\\xampp\\htdocs\\room\\vendor\\symfony\\console\\Command\\Command.php(318): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#19 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#20 C:\\xampp\\htdocs\\room\\vendor\\symfony\\console\\Application.php(1092): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#21 C:\\xampp\\htdocs\\room\\vendor\\symfony\\console\\Application.php(341): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#22 C:\\xampp\\htdocs\\room\\vendor\\symfony\\console\\Application.php(192): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#23 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(197): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#24 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1234): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#25 C:\\xampp\\htdocs\\room\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#26 {main}', '2025-11-20 08:53:39'),
(10, '492c5a23-1f99-41c6-8690-95d78d010dbd', 'database', 'default', '{\"uuid\":\"492c5a23-1f99-41c6-8690-95d78d010dbd\",\"displayName\":\"App\\\\Mail\\\\SectionInviteMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:26:\\\"App\\\\Mail\\\\SectionInviteMail\\\":5:{s:6:\\\"invite\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:23:\\\"App\\\\Models\\\\EmailInvites\\\";s:2:\\\"id\\\";i:19;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:7:\\\"section\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Section\\\";s:2:\\\"id\\\";i:11;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:7:\\\"faculty\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:27:\\\"ronnbaldovino1130@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1763627741,\"delay\":null}', 'Illuminate\\Database\\Eloquent\\ModelNotFoundException: No query results for model [App\\Models\\EmailInvites]. in C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php:765\nStack trace:\n#0 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers.php(110): Illuminate\\Database\\Eloquent\\Builder->firstOrFail()\n#1 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers.php(63): App\\Mail\\SectionInviteMail->restoreModel(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#2 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesModels.php(97): App\\Mail\\SectionInviteMail->getRestoredPropertyValue(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#3 [internal function]: App\\Mail\\SectionInviteMail->__unserialize(Array)\n#4 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(95): unserialize(\'O:34:\"Illuminat...\')\n#5 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(62): Illuminate\\Queue\\CallQueuedHandler->getCommand(Array)\n#6 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#7 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#8 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(394): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#9 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(180): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#10 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#11 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#12 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#13 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#14 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#15 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#16 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(835): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#17 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#18 C:\\xampp\\htdocs\\room\\vendor\\symfony\\console\\Command\\Command.php(318): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#19 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#20 C:\\xampp\\htdocs\\room\\vendor\\symfony\\console\\Application.php(1092): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#21 C:\\xampp\\htdocs\\room\\vendor\\symfony\\console\\Application.php(341): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#22 C:\\xampp\\htdocs\\room\\vendor\\symfony\\console\\Application.php(192): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#23 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(197): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#24 C:\\xampp\\htdocs\\room\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1234): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#25 C:\\xampp\\htdocs\\room\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#26 {main}', '2025-11-20 08:53:39');

-- --------------------------------------------------------

--
-- Table structure for table `floor_plans`
--

CREATE TABLE `floor_plans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `filepath` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ingredients`
--

CREATE TABLE `ingredients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `menu_item_id` bigint(20) UNSIGNED NOT NULL,
  `inventory_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` double NOT NULL,
  `unit_of_measurement_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ingredients`
--

INSERT INTO `ingredients` (`id`, `menu_item_id`, `inventory_id`, `quantity`, `unit_of_measurement_id`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 1, 2, '2026-01-31 12:59:41', '2026-01-31 12:59:41'),
(2, 2, 2, 1, 2, '2026-01-31 12:59:41', '2026-01-31 12:59:41'),
(3, 2, 3, 1, 2, '2026-01-31 12:59:41', '2026-01-31 12:59:41'),
(4, 2, 4, 1, 2, '2026-01-31 12:59:41', '2026-01-31 12:59:41'),
(5, 3, 24, 2, 2, '2026-01-31 13:12:58', '2026-01-31 13:12:58'),
(6, 3, 23, 2, 2, '2026-01-31 13:12:58', '2026-01-31 13:12:58'),
(7, 3, 21, 2, 4, '2026-01-31 13:12:58', '2026-01-31 13:12:58'),
(8, 3, 19, 2, 5, '2026-01-31 13:12:58', '2026-01-31 13:12:58'),
(9, 3, 20, 2, 3, '2026-01-31 13:12:58', '2026-01-31 13:12:58'),
(10, 4, 16, 2, 4, '2026-02-02 04:49:37', '2026-02-02 04:49:37'),
(11, 4, 58, 2, 3, '2026-02-02 04:49:37', '2026-02-02 04:49:37'),
(12, 4, 70, 2, 3, '2026-02-02 04:49:37', '2026-02-02 04:49:37'),
(13, 4, 225, 3, 3, '2026-02-02 04:49:37', '2026-02-02 04:49:37'),
(14, 4, 160, 1, 3, '2026-02-02 04:49:37', '2026-02-02 04:49:37'),
(15, 4, 253, 1, 3, '2026-02-02 04:49:37', '2026-02-02 04:49:37'),
(16, 4, 255, 1, 3, '2026-02-02 04:49:37', '2026-02-02 04:49:37');

-- --------------------------------------------------------

--
-- Table structure for table `inventories`
--

CREATE TABLE `inventories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `opening_quantity` decimal(10,3) NOT NULL,
  `quantity_on_hand` decimal(10,3) NOT NULL,
  `unit_cost` decimal(10,2) NOT NULL,
  `par_level` decimal(10,3) NOT NULL,
  `inventory_category_id` bigint(20) UNSIGNED NOT NULL,
  `inventory_unit_id` bigint(20) UNSIGNED NOT NULL,
  `cost_unit_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventories`
--

INSERT INTO `inventories` (`id`, `name`, `code`, `image`, `opening_quantity`, `quantity_on_hand`, `unit_cost`, `par_level`, `inventory_category_id`, `inventory_unit_id`, `cost_unit_id`, `created_at`, `updated_at`) VALUES
(1, 'Chicken Breast', 'MEAT-001', 'inventory_images/Iwy0GjTV7126xtcoTUmqpgLo4KjGqobxFQrIHr4Y.jpg', 50.000, 0.000, 100.00, 60.000, 1, 2, 2, '2026-01-30 23:17:59', '2026-02-02 00:35:59'),
(2, 'Chicken Thighs', 'MEAT-002', 'chicken-thighs.jpg', 40.000, 236.000, 10.75, 50.000, 1, 2, 2, '2026-01-30 23:17:59', '2026-02-02 05:03:22'),
(3, 'Chicken Wings', 'MEAT-003', 'chicken-wings.jpg', 30.000, 325.000, 9.99, 40.000, 1, 2, 2, '2026-01-30 23:17:59', '2026-02-02 00:36:00'),
(4, 'Chicken Drumsticks', 'MEAT-004', 'chicken-drumsticks.jpg', 35.000, 28.000, 8.50, 45.000, 1, 2, 2, '2026-01-30 23:17:59', '2026-01-30 23:17:59'),
(5, 'Whole Chicken', 'MEAT-005', 'whole-chicken.jpg', 20.000, 115.000, 7.25, 25.000, 1, 2, 2, '2026-01-30 23:17:59', '2026-02-02 05:03:22'),
(6, 'Ground Beef', 'MEAT-006', 'ground-beef.jpg', 45.000, 38.000, 11.99, 55.000, 1, 2, 2, '2026-01-30 23:17:59', '2026-01-30 23:17:59'),
(7, 'Beef Sirloin', 'MEAT-007', 'beef-sirloin.jpg', 30.000, 24.000, 18.50, 40.000, 1, 2, 2, '2026-01-30 23:17:59', '2026-01-30 23:17:59'),
(8, 'Beef Ribeye', 'MEAT-008', 'beef-ribeye.jpg', 25.000, 20.000, 22.99, 35.000, 1, 2, 2, '2026-01-30 23:17:59', '2026-01-30 23:17:59'),
(9, 'Beef Tenderloin', 'MEAT-009', 'beef-tenderloin.jpg', 20.000, 116.000, 28.75, 30.000, 1, 2, 2, '2026-01-30 23:17:59', '2026-02-02 05:03:22'),
(10, 'Beef Brisket', 'MEAT-010', 'beef-brisket.jpg', 35.000, 28.000, 14.50, 45.000, 1, 2, 2, '2026-01-30 23:17:59', '2026-01-30 23:17:59'),
(11, 'Beef Short Ribs', 'MEAT-011', 'beef-short-ribs.jpg', 28.000, 22.000, 16.99, 35.000, 1, 2, 2, '2026-01-30 23:17:59', '2026-01-30 23:17:59'),
(12, 'Pork Chops', 'MEAT-012', 'pork-chops.jpg', 32.000, 26.000, 13.25, 40.000, 1, 2, 2, '2026-01-30 23:17:59', '2026-01-30 23:17:59'),
(13, 'Pork Tenderloin', 'MEAT-013', 'pork-tenderloin.jpg', 25.000, 20.000, 15.75, 32.000, 1, 2, 2, '2026-01-30 23:17:59', '2026-01-30 23:17:59'),
(14, 'Pork Belly', 'MEAT-014', 'pork-belly.jpg', 30.000, 24.000, 12.50, 38.000, 1, 2, 2, '2026-01-30 23:17:59', '2026-01-30 23:17:59'),
(15, 'Pork Ribs', 'MEAT-015', 'pork-ribs.jpg', 28.000, 22.500, 11.99, 35.000, 1, 2, 2, '2026-01-30 23:17:59', '2026-01-30 23:17:59'),
(16, 'Ground Pork', 'MEAT-016', 'ground-pork.jpg', 40.000, 33.000, 9.75, 50.000, 1, 2, 2, '2026-01-30 23:17:59', '2026-01-30 23:17:59'),
(17, 'Pork Shoulder', 'MEAT-017', 'pork-shoulder.jpg', 35.000, 28.000, 8.99, 45.000, 1, 2, 2, '2026-01-30 23:17:59', '2026-01-30 23:17:59'),
(18, 'Bacon Strips', 'MEAT-018', 'bacon-strips.jpg', 20.000, 16.500, 14.50, 28.000, 1, 2, 2, '2026-01-30 23:17:59', '2026-01-30 23:17:59'),
(19, 'Lamb Chops', 'MEAT-019', 'lamb-chops.jpg', 18.000, 14.000, 24.99, 25.000, 1, 2, 2, '2026-01-30 23:17:59', '2026-01-30 23:17:59'),
(20, 'Lamb Leg', 'MEAT-020', 'lamb-leg.jpg', 22.000, 18.000, 22.50, 30.000, 1, 2, 2, '2026-01-30 23:17:59', '2026-01-30 23:17:59'),
(21, 'Ground Lamb', 'MEAT-021', 'ground-lamb.jpg', 25.000, 20.000, 18.75, 32.000, 1, 2, 2, '2026-01-30 23:17:59', '2026-01-30 23:17:59'),
(22, 'Turkey Breast', 'MEAT-022', 'turkey-breast.jpg', 30.000, 24.000, 13.99, 38.000, 1, 2, 2, '2026-01-30 23:17:59', '2026-01-30 23:17:59'),
(23, 'Whole Turkey', 'MEAT-023', 'whole-turkey.jpg', 15.000, 12.000, 8.50, 20.000, 1, 2, 2, '2026-01-30 23:17:59', '2026-01-30 23:17:59'),
(24, 'Ground Turkey', 'MEAT-024', 'ground-turkey.jpg', 28.000, 22.000, 10.25, 35.000, 1, 2, 2, '2026-01-30 23:17:59', '2026-01-30 23:17:59'),
(25, 'Duck Breast', 'MEAT-025', 'duck-breast.jpg', 12.000, 9.500, 26.50, 18.000, 1, 2, 2, '2026-01-30 23:17:59', '2026-01-30 23:17:59'),
(26, 'Whole Duck', 'MEAT-026', 'whole-duck.jpg', 10.000, 8.000, 18.99, 15.000, 1, 2, 2, '2026-01-30 23:17:59', '2026-01-30 23:17:59'),
(27, 'Sausage Links (Pork)', 'MEAT-027', 'sausage-links.jpg', 25.000, 20.000, 12.75, 32.000, 1, 2, 2, '2026-01-30 23:17:59', '2026-01-30 23:17:59'),
(28, 'Italian Sausage', 'MEAT-028', 'italian-sausage.jpg', 22.000, 18.000, 13.50, 30.000, 1, 2, 2, '2026-01-30 23:17:59', '2026-01-30 23:17:59'),
(29, 'Chorizo', 'MEAT-029', 'chorizo.jpg', 18.000, 14.500, 15.25, 25.000, 1, 2, 2, '2026-01-30 23:17:59', '2026-01-30 23:17:59'),
(30, 'Hot Dogs', 'MEAT-030', 'hot-dogs.jpg', 30.000, 24.000, 8.99, 38.000, 1, 2, 2, '2026-01-30 23:17:59', '2026-01-30 23:17:59'),
(31, 'Beef Liver', 'MEAT-031', 'beef-liver.jpg', 15.000, 12.000, 6.50, 20.000, 1, 2, 2, '2026-01-30 23:17:59', '2026-01-30 23:17:59'),
(32, 'Chicken Liver', 'MEAT-032', 'chicken-liver.jpg', 12.000, 9.500, 5.75, 18.000, 1, 2, 2, '2026-01-30 23:17:59', '2026-01-30 23:17:59'),
(33, 'Veal Cutlets', 'MEAT-033', 'veal-cutlets.jpg', 16.000, 13.000, 28.99, 22.000, 1, 2, 2, '2026-01-30 23:17:59', '2026-01-30 23:17:59'),
(34, 'Beef Tongue', 'MEAT-034', 'beef-tongue.jpg', 10.000, 8.000, 12.50, 15.000, 1, 2, 2, '2026-01-30 23:17:59', '2026-01-30 23:17:59'),
(35, 'Ox Tail', 'MEAT-035', 'ox-tail.jpg', 14.000, 11.000, 15.99, 20.000, 1, 2, 2, '2026-01-30 23:17:59', '2026-01-30 23:17:59'),
(36, 'Beef Tripe', 'MEAT-036', 'beef-tripe.jpg', 12.000, 9.500, 7.25, 18.000, 1, 2, 2, '2026-01-30 23:17:59', '2026-01-30 23:17:59'),
(37, 'Ham Sliced', 'MEAT-037', 'ham-sliced.jpg', 20.000, 16.000, 11.50, 28.000, 1, 2, 2, '2026-01-30 23:17:59', '2026-01-30 23:17:59'),
(38, 'Prosciutto', 'MEAT-038', 'prosciutto.jpg', 8.000, 6.500, 32.99, 12.000, 1, 2, 2, '2026-01-30 23:17:59', '2026-01-30 23:17:59'),
(39, 'Salami', 'MEAT-039', 'salami.jpg', 15.000, 12.000, 18.75, 22.000, 1, 2, 2, '2026-01-30 23:17:59', '2026-01-30 23:17:59'),
(40, 'Pepperoni', 'MEAT-040', 'pepperoni.jpg', 18.000, 14.500, 16.50, 25.000, 1, 2, 2, '2026-01-30 23:17:59', '2026-01-30 23:17:59'),
(41, 'Beef Patties (Frozen)', 'MEAT-041', 'beef-patties.jpg', 40.000, 32.000, 13.99, 50.000, 1, 2, 2, '2026-01-30 23:17:59', '2026-01-30 23:17:59'),
(42, 'Chicken Nuggets (Frozen)', 'MEAT-042', 'chicken-nuggets.jpg', 35.000, 28.000, 10.50, 45.000, 1, 2, 2, '2026-01-30 23:17:59', '2026-01-30 23:17:59'),
(43, 'Meatballs (Frozen)', 'MEAT-043', 'meatballs.jpg', 30.000, 24.000, 12.25, 38.000, 1, 2, 2, '2026-01-30 23:17:59', '2026-01-30 23:17:59'),
(44, 'Beef Stew Meat', 'MEAT-044', 'beef-stew-meat.jpg', 28.000, 22.500, 14.75, 35.000, 1, 2, 2, '2026-01-30 23:17:59', '2026-01-30 23:17:59'),
(45, 'Pork Loin', 'MEAT-045', 'pork-loin.jpg', 26.000, 21.000, 11.99, 33.000, 1, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(46, 'Beef Chuck Roast', 'MEAT-046', 'beef-chuck-roast.jpg', 32.000, 26.000, 13.50, 40.000, 1, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(47, 'Venison', 'MEAT-047', 'venison.jpg', 10.000, 8.000, 25.99, 15.000, 1, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(48, 'Rabbit', 'MEAT-048', 'rabbit.jpg', 8.000, 6.500, 22.50, 12.000, 1, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(49, 'Quail', 'MEAT-049', 'quail.jpg', 6.000, 4.500, 28.99, 10.000, 1, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(50, 'Cornish Hen', 'MEAT-050', 'cornish-hen.jpg', 12.000, 9.500, 16.75, 18.000, 1, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(51, 'Whole Milk', 'DAIRY-001', 'whole-milk.jpg', 100.000, 85.000, 3.99, 120.000, 2, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(52, 'Skim Milk', 'DAIRY-002', 'skim-milk.jpg', 80.000, 68.000, 3.75, 100.000, 2, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(53, '2% Milk', 'DAIRY-003', '2-percent-milk.jpg', 90.000, 75.000, 3.85, 110.000, 2, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(54, 'Chocolate Milk', 'DAIRY-004', 'chocolate-milk.jpg', 60.000, 48.000, 4.50, 75.000, 2, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(55, 'Almond Milk', 'DAIRY-005', 'almond-milk.jpg', 50.000, 42.000, 5.25, 65.000, 2, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(56, 'Soy Milk', 'DAIRY-006', 'soy-milk.jpg', 45.000, 38.000, 4.99, 60.000, 2, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(57, 'Oat Milk', 'DAIRY-007', 'oat-milk.jpg', 40.000, 33.000, 5.50, 55.000, 2, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(58, 'Heavy Cream', 'DAIRY-008', 'heavy-cream.jpg', 30.000, 24.000, 6.99, 40.000, 2, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(59, 'Half and Half', 'DAIRY-009', 'half-and-half.jpg', 25.000, 20.000, 5.75, 35.000, 2, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(60, 'Whipping Cream', 'DAIRY-010', 'whipping-cream.jpg', 22.000, 18.000, 6.25, 30.000, 2, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(61, 'Sour Cream', 'DAIRY-011', 'sour-cream.jpg', 20.000, 16.000, 4.50, 28.000, 2, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(62, 'Greek Yogurt', 'DAIRY-012', 'greek-yogurt.jpg', 35.000, 28.000, 8.99, 45.000, 2, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(63, 'Plain Yogurt', 'DAIRY-013', 'plain-yogurt.jpg', 30.000, 24.000, 6.50, 40.000, 2, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(64, 'Strawberry Yogurt', 'DAIRY-014', 'strawberry-yogurt.jpg', 25.000, 20.000, 7.25, 35.000, 2, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(65, 'Vanilla Yogurt', 'DAIRY-015', 'vanilla-yogurt.jpg', 28.000, 22.000, 7.50, 38.000, 2, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(66, 'Cottage Cheese', 'DAIRY-016', 'cottage-cheese.jpg', 18.000, 14.500, 5.99, 25.000, 2, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(67, 'Cream Cheese', 'DAIRY-017', 'cream-cheese.jpg', 15.000, 12.000, 8.50, 22.000, 2, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(68, 'Ricotta Cheese', 'DAIRY-018', 'ricotta-cheese.jpg', 12.000, 9.500, 9.75, 18.000, 2, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(69, 'Mascarpone', 'DAIRY-019', 'mascarpone.jpg', 8.000, 6.500, 12.99, 12.000, 2, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(70, 'Cheddar Cheese', 'DAIRY-020', 'cheddar-cheese.jpg', 25.000, 20.000, 18.50, 32.000, 2, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(71, 'Mozzarella Cheese', 'DAIRY-021', 'mozzarella-cheese.jpg', 30.000, 24.000, 16.75, 38.000, 2, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(72, 'Parmesan Cheese', 'DAIRY-022', 'parmesan-cheese.jpg', 15.000, 12.000, 24.99, 20.000, 2, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(73, 'Swiss Cheese', 'DAIRY-023', 'swiss-cheese.jpg', 18.000, 14.500, 19.50, 25.000, 2, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(74, 'Provolone Cheese', 'DAIRY-024', 'provolone-cheese.jpg', 16.000, 13.000, 17.25, 22.000, 2, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(75, 'Blue Cheese', 'DAIRY-025', 'blue-cheese.jpg', 10.000, 8.000, 22.50, 15.000, 2, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(76, 'Feta Cheese', 'DAIRY-026', 'feta-cheese.jpg', 12.000, 9.500, 15.99, 18.000, 2, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(77, 'Gouda Cheese', 'DAIRY-027', 'gouda-cheese.jpg', 14.000, 11.000, 18.75, 20.000, 2, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(78, 'Brie Cheese', 'DAIRY-028', 'brie-cheese.jpg', 8.000, 6.500, 21.50, 12.000, 2, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(79, 'Camembert Cheese', 'DAIRY-029', 'camembert-cheese.jpg', 7.000, 5.500, 20.99, 10.000, 2, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(80, 'Monterey Jack Cheese', 'DAIRY-030', 'monterey-jack-cheese.jpg', 16.000, 13.000, 16.50, 22.000, 2, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(81, 'Colby Cheese', 'DAIRY-031', 'colby-cheese.jpg', 14.000, 11.500, 15.75, 20.000, 2, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(82, 'Pepper Jack Cheese', 'DAIRY-032', 'pepper-jack-cheese.jpg', 12.000, 9.500, 17.25, 18.000, 2, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(83, 'American Cheese Slices', 'DAIRY-033', 'american-cheese.jpg', 20.000, 16.000, 12.99, 28.000, 2, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(84, 'Shredded Mexican Blend', 'DAIRY-034', 'mexican-blend-cheese.jpg', 22.000, 18.000, 14.50, 30.000, 2, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(85, 'Shredded Italian Blend', 'DAIRY-035', 'italian-blend-cheese.jpg', 20.000, 16.500, 15.25, 28.000, 2, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(86, 'Butter (Salted)', 'DAIRY-036', 'salted-butter.jpg', 30.000, 24.000, 8.99, 40.000, 2, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(87, 'Butter (Unsalted)', 'DAIRY-037', 'unsalted-butter.jpg', 28.000, 22.500, 9.25, 38.000, 2, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(88, 'Margarine', 'DAIRY-038', 'margarine.jpg', 25.000, 20.000, 6.50, 35.000, 2, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(89, 'Ghee', 'DAIRY-039', 'ghee.jpg', 10.000, 8.000, 14.99, 15.000, 2, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(90, 'Large Eggs', 'DAIRY-040', 'large-eggs.jpg', 300.000, 245.000, 0.35, 400.000, 2, 15, 15, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(91, 'Extra Large Eggs', 'DAIRY-041', 'extra-large-eggs.jpg', 250.000, 200.000, 0.40, 350.000, 2, 15, 15, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(92, 'Medium Eggs', 'DAIRY-042', 'medium-eggs.jpg', 200.000, 165.000, 0.30, 300.000, 2, 15, 15, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(93, 'Organic Eggs', 'DAIRY-043', 'organic-eggs.jpg', 180.000, 145.000, 0.50, 250.000, 2, 15, 15, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(94, 'Free Range Eggs', 'DAIRY-044', 'free-range-eggs.jpg', 160.000, 130.000, 0.45, 220.000, 2, 15, 15, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(95, 'Brown Eggs', 'DAIRY-045', 'brown-eggs.jpg', 220.000, 180.000, 0.38, 300.000, 2, 15, 15, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(96, 'Egg Whites (Liquid)', 'DAIRY-046', 'egg-whites.jpg', 15.000, 12.000, 8.50, 22.000, 2, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(97, 'Liquid Eggs', 'DAIRY-047', 'liquid-eggs.jpg', 20.000, 16.000, 10.99, 28.000, 2, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(98, 'Buttermilk', 'DAIRY-048', 'buttermilk.jpg', 35.000, 28.000, 4.25, 45.000, 2, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(99, 'Evaporated Milk', 'DAIRY-049', 'evaporated-milk.jpg', 40.000, 32.000, 3.50, 50.000, 2, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(100, 'Condensed Milk', 'DAIRY-050', 'condensed-milk.jpg', 35.000, 28.500, 4.75, 45.000, 2, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(101, 'Tomatoes', 'PROD-001', 'tomatoes.jpg', 50.000, 42.000, 4.25, 65.000, 3, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(102, 'Cherry Tomatoes', 'PROD-002', 'cherry-tomatoes.jpg', 30.000, 24.000, 6.50, 40.000, 3, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(103, 'Roma Tomatoes', 'PROD-003', 'roma-tomatoes.jpg', 40.000, 33.000, 4.75, 52.000, 3, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(104, 'Lettuce (Iceberg)', 'PROD-004', 'iceberg-lettuce.jpg', 60.000, 48.000, 2.50, 75.000, 3, 15, 15, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(105, 'Lettuce (Romaine)', 'PROD-005', 'romaine-lettuce.jpg', 55.000, 44.000, 2.75, 70.000, 3, 15, 15, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(106, 'Lettuce (Butterhead)', 'PROD-006', 'butterhead-lettuce.jpg', 45.000, 36.000, 3.25, 60.000, 3, 15, 15, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(107, 'Spinach', 'PROD-007', 'spinach.jpg', 35.000, 28.000, 5.50, 45.000, 3, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(108, 'Kale', 'PROD-008', 'kale.jpg', 28.000, 22.000, 6.25, 38.000, 3, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(109, 'Arugula', 'PROD-009', 'arugula.jpg', 20.000, 16.000, 7.50, 28.000, 3, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(110, 'Cabbage (Green)', 'PROD-010', 'green-cabbage.jpg', 40.000, 32.000, 2.99, 52.000, 3, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(111, 'Cabbage (Red)', 'PROD-011', 'red-cabbage.jpg', 32.000, 26.000, 3.50, 42.000, 3, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(112, 'Onions (Yellow)', 'PROD-012', 'yellow-onions.jpg', 60.000, 48.000, 2.25, 75.000, 3, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(113, 'Onions (Red)', 'PROD-013', 'red-onions.jpg', 45.000, 36.000, 2.75, 58.000, 3, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(114, 'Onions (White)', 'PROD-014', 'white-onions.jpg', 40.000, 32.000, 2.50, 52.000, 3, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(115, 'Green Onions', 'PROD-015', 'green-onions.jpg', 100.000, 80.000, 0.50, 130.000, 3, 15, 15, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(116, 'Garlic', 'PROD-016', 'garlic.jpg', 25.000, 20.000, 8.50, 35.000, 3, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(117, 'Ginger', 'PROD-017', 'ginger.jpg', 15.000, 12.000, 9.99, 22.000, 3, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(118, 'Potatoes (Russet)', 'PROD-018', 'russet-potatoes.jpg', 80.000, 65.000, 1.99, 100.000, 3, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(119, 'Potatoes (Red)', 'PROD-019', 'red-potatoes.jpg', 70.000, 56.000, 2.25, 90.000, 3, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(120, 'Potatoes (Yukon Gold)', 'PROD-020', 'yukon-gold-potatoes.jpg', 65.000, 52.000, 2.50, 85.000, 3, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(121, 'Sweet Potatoes', 'PROD-021', 'sweet-potatoes.jpg', 55.000, 44.000, 3.25, 72.000, 3, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(122, 'Carrots', 'PROD-022', 'carrots.jpg', 50.000, 40.000, 2.75, 65.000, 3, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(123, 'Baby Carrots', 'PROD-023', 'baby-carrots.jpg', 40.000, 32.000, 3.50, 52.000, 3, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(124, 'Celery', 'PROD-024', 'celery.jpg', 35.000, 28.000, 3.25, 48.000, 3, 15, 15, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(125, 'Bell Peppers (Green)', 'PROD-025', 'green-bell-peppers.jpg', 30.000, 24.000, 4.50, 40.000, 3, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(126, 'Bell Peppers (Red)', 'PROD-026', 'red-bell-peppers.jpg', 28.000, 22.000, 5.50, 38.000, 3, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(127, 'Bell Peppers (Yellow)', 'PROD-027', 'yellow-bell-peppers.jpg', 25.000, 20.000, 5.75, 35.000, 3, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(128, 'Jalapeño Peppers', 'PROD-028', 'jalapeno-peppers.jpg', 15.000, 12.000, 6.50, 22.000, 3, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(129, 'Serrano Peppers', 'PROD-029', 'serrano-peppers.jpg', 12.000, 9.500, 7.25, 18.000, 3, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(130, 'Broccoli', 'PROD-030', 'broccoli.jpg', 40.000, 32.000, 4.99, 52.000, 3, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(131, 'Cauliflower', 'PROD-031', 'cauliflower.jpg', 35.000, 28.000, 5.50, 48.000, 3, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(132, 'Zucchini', 'PROD-032', 'zucchini.jpg', 32.000, 26.000, 3.75, 42.000, 3, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(133, 'Yellow Squash', 'PROD-033', 'yellow-squash.jpg', 28.000, 22.500, 4.25, 38.000, 3, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(134, 'Eggplant', 'PROD-034', 'eggplant.jpg', 25.000, 20.000, 4.75, 35.000, 3, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(135, 'Cucumbers', 'PROD-035', 'cucumbers.jpg', 38.000, 30.000, 3.50, 50.000, 3, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(136, 'Mushrooms (White Button)', 'PROD-036', 'white-button-mushrooms.jpg', 30.000, 24.000, 8.99, 40.000, 3, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(137, 'Mushrooms (Portobello)', 'PROD-037', 'portobello-mushrooms.jpg', 20.000, 16.000, 12.50, 28.000, 3, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(138, 'Mushrooms (Shiitake)', 'PROD-038', 'shiitake-mushrooms.jpg', 15.000, 12.000, 16.99, 22.000, 3, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(139, 'Avocados', 'PROD-039', 'avocados.jpg', 150.000, 120.000, 1.50, 200.000, 3, 15, 15, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(140, 'Corn (Fresh)', 'PROD-040', 'fresh-corn.jpg', 100.000, 80.000, 0.75, 130.000, 3, 15, 15, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(141, 'Green Beans', 'PROD-041', 'green-beans.jpg', 35.000, 28.000, 5.25, 48.000, 3, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(142, 'Asparagus', 'PROD-042', 'asparagus.jpg', 25.000, 20.000, 8.50, 35.000, 3, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(143, 'Brussels Sprouts', 'PROD-043', 'brussels-sprouts.jpg', 22.000, 18.000, 6.75, 30.000, 3, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(144, 'Radishes', 'PROD-044', 'radishes.jpg', 18.000, 14.500, 4.50, 25.000, 3, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(145, 'Beets', 'PROD-045', 'beets.jpg', 20.000, 16.000, 5.25, 28.000, 3, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(146, 'Turnips', 'PROD-046', 'turnips.jpg', 18.000, 14.000, 3.99, 25.000, 3, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(147, 'Parsnips', 'PROD-047', 'parsnips.jpg', 15.000, 12.000, 4.75, 22.000, 3, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(148, 'Leeks', 'PROD-048', 'leeks.jpg', 20.000, 16.000, 6.50, 28.000, 3, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(149, 'Fresh Herbs (Cilantro)', 'PROD-049', 'cilantro.jpg', 150.000, 120.000, 0.75, 200.000, 3, 15, 15, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(150, 'Fresh Herbs (Parsley)', 'PROD-050', 'parsley.jpg', 140.000, 112.000, 0.70, 180.000, 3, 15, 15, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(151, 'All-Purpose Flour', 'DRY-001', 'all-purpose-flour.jpg', 200.000, 165.000, 1.50, 250.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(152, 'Bread Flour', 'DRY-002', 'bread-flour.jpg', 150.000, 120.000, 1.75, 200.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(153, 'Cake Flour', 'DRY-003', 'cake-flour.jpg', 100.000, 82.000, 2.25, 130.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(154, 'Whole Wheat Flour', 'DRY-004', 'whole-wheat-flour.jpg', 120.000, 95.000, 1.99, 160.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(155, 'White Rice', 'DRY-005', 'white-rice.jpg', 180.000, 145.000, 2.25, 230.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(156, 'Brown Rice', 'DRY-006', 'brown-rice.jpg', 140.000, 112.000, 2.75, 180.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(157, 'Basmati Rice', 'DRY-007', 'basmati-rice.jpg', 120.000, 96.000, 3.50, 160.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(158, 'Jasmine Rice', 'DRY-008', 'jasmine-rice.jpg', 110.000, 88.000, 3.25, 145.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(159, 'Arborio Rice', 'DRY-009', 'arborio-rice.jpg', 80.000, 64.000, 4.50, 105.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(160, 'Pasta (Spaghetti)', 'DRY-010', 'spaghetti.jpg', 100.000, 80.000, 3.50, 130.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(161, 'Pasta (Penne)', 'DRY-011', 'penne.jpg', 95.000, 76.000, 3.75, 125.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(162, 'Pasta (Fettuccine)', 'DRY-012', 'fettuccine.jpg', 90.000, 72.000, 3.99, 120.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(163, 'Pasta (Linguine)', 'DRY-013', 'linguine.jpg', 85.000, 68.000, 3.85, 115.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(164, 'Pasta (Rigatoni)', 'DRY-014', 'rigatoni.jpg', 80.000, 64.000, 4.25, 105.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(165, 'Pasta (Fusilli)', 'DRY-015', 'fusilli.jpg', 75.000, 60.000, 4.50, 100.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(166, 'Pasta (Macaroni)', 'DRY-016', 'macaroni.jpg', 90.000, 72.000, 3.25, 120.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(167, 'Pasta (Lasagna Sheets)', 'DRY-017', 'lasagna-sheets.jpg', 60.000, 48.000, 5.50, 80.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(168, 'Quinoa', 'DRY-018', 'quinoa.jpg', 70.000, 56.000, 6.99, 95.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(169, 'Couscous', 'DRY-019', 'couscous.jpg', 60.000, 48.000, 4.75, 80.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(170, 'Bulgur Wheat', 'DRY-020', 'bulgur-wheat.jpg', 50.000, 40.000, 5.25, 68.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(171, 'Oats (Rolled)', 'DRY-021', 'rolled-oats.jpg', 100.000, 80.000, 2.99, 130.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(172, 'Oats (Steel Cut)', 'DRY-022', 'steel-cut-oats.jpg', 80.000, 64.000, 3.75, 105.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(173, 'Cornmeal', 'DRY-023', 'cornmeal.jpg', 70.000, 56.000, 2.50, 95.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(174, 'Cornstarch', 'DRY-024', 'cornstarch.jpg', 60.000, 48.000, 3.25, 80.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(175, 'Granulated Sugar', 'DRY-025', 'granulated-sugar.jpg', 150.000, 120.000, 1.99, 200.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(176, 'Brown Sugar', 'DRY-026', 'brown-sugar.jpg', 100.000, 80.000, 2.50, 130.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(177, 'Powdered Sugar', 'DRY-027', 'powdered-sugar.jpg', 80.000, 64.000, 2.75, 105.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(178, 'Honey', 'DRY-028', 'honey.jpg', 40.000, 32.000, 8.99, 55.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(179, 'Maple Syrup', 'DRY-029', 'maple-syrup.jpg', 30.000, 24.000, 12.50, 42.000, 4, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(180, 'Baking Powder', 'DRY-030', 'baking-powder.jpg', 25.000, 20.000, 6.50, 35.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(181, 'Baking Soda', 'DRY-031', 'baking-soda.jpg', 30.000, 24.000, 4.25, 40.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(182, 'Active Dry Yeast', 'DRY-032', 'active-dry-yeast.jpg', 5.000, 4.000, 15.99, 8.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(183, 'Instant Yeast', 'DRY-033', 'instant-yeast.jpg', 4.500, 3.600, 17.50, 7.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(184, 'Cocoa Powder', 'DRY-034', 'cocoa-powder.jpg', 20.000, 16.000, 12.99, 28.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(185, 'Vanilla Extract', 'DRY-035', 'vanilla-extract.jpg', 8.000, 6.400, 24.99, 12.000, 4, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(186, 'Almond Extract', 'DRY-036', 'almond-extract.jpg', 5.000, 4.000, 18.50, 8.000, 4, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(187, 'Chocolate Chips', 'DRY-037', 'chocolate-chips.jpg', 40.000, 32.000, 8.99, 55.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(188, 'Dried Beans (Black)', 'DRY-038', 'black-beans.jpg', 60.000, 48.000, 3.50, 80.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(189, 'Dried Beans (Kidney)', 'DRY-039', 'kidney-beans.jpg', 55.000, 44.000, 3.75, 72.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(190, 'Dried Beans (Pinto)', 'DRY-040', 'pinto-beans.jpg', 50.000, 40.000, 3.25, 68.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(191, 'Lentils (Red)', 'DRY-041', 'red-lentils.jpg', 45.000, 36.000, 4.50, 60.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(192, 'Lentils (Green)', 'DRY-042', 'green-lentils.jpg', 40.000, 32.000, 4.25, 55.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(193, 'Chickpeas (Dried)', 'DRY-043', 'dried-chickpeas.jpg', 50.000, 40.000, 3.99, 68.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(194, 'Split Peas', 'DRY-044', 'split-peas.jpg', 35.000, 28.000, 3.50, 48.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(195, 'Almonds (Whole)', 'DRY-045', 'whole-almonds.jpg', 30.000, 24.000, 14.99, 42.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(196, 'Almonds (Sliced)', 'DRY-046', 'sliced-almonds.jpg', 25.000, 20.000, 16.50, 35.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(197, 'Walnuts', 'DRY-047', 'walnuts.jpg', 28.000, 22.400, 18.99, 38.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(198, 'Pecans', 'DRY-048', 'pecans.jpg', 22.000, 17.600, 22.50, 30.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(199, 'Cashews', 'DRY-049', 'cashews.jpg', 26.000, 20.800, 19.99, 36.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(200, 'Peanuts (Roasted)', 'DRY-050', 'roasted-peanuts.jpg', 35.000, 28.000, 8.50, 48.000, 4, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(201, 'Olive Oil (Extra Virgin)', 'COND-001', 'extra-virgin-olive-oil.jpg', 40.000, 32.000, 15.00, 55.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(202, 'Olive Oil (Regular)', 'COND-002', 'olive-oil.jpg', 50.000, 40.000, 10.50, 68.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(203, 'Vegetable Oil', 'COND-003', 'vegetable-oil.jpg', 60.000, 48.000, 6.99, 80.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(204, 'Canola Oil', 'COND-004', 'canola-oil.jpg', 55.000, 44.000, 7.50, 72.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(205, 'Sesame Oil', 'COND-005', 'sesame-oil.jpg', 20.000, 16.000, 12.99, 28.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(206, 'Coconut Oil', 'COND-006', 'coconut-oil.jpg', 25.000, 20.000, 14.50, 35.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(207, 'Peanut Oil', 'COND-007', 'peanut-oil.jpg', 30.000, 24.000, 9.75, 42.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(208, 'Soy Sauce', 'COND-008', 'soy-sauce.jpg', 35.000, 28.000, 8.50, 48.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(209, 'Soy Sauce (Low Sodium)', 'COND-009', 'low-sodium-soy-sauce.jpg', 30.000, 24.000, 9.25, 42.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(210, 'Fish Sauce', 'COND-010', 'fish-sauce.jpg', 25.000, 20.000, 7.99, 35.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(211, 'Oyster Sauce', 'COND-011', 'oyster-sauce.jpg', 28.000, 22.400, 6.75, 38.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(212, 'Worcestershire Sauce', 'COND-012', 'worcestershire-sauce.jpg', 22.000, 17.600, 8.25, 30.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(213, 'Hot Sauce (Tabasco)', 'COND-013', 'tabasco.jpg', 20.000, 16.000, 5.50, 28.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(214, 'Hot Sauce (Sriracha)', 'COND-014', 'sriracha.jpg', 25.000, 20.000, 6.99, 35.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(215, 'Tomato Ketchup', 'COND-015', 'ketchup.jpg', 40.000, 32.000, 4.75, 55.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(216, 'Mustard (Yellow)', 'COND-016', 'yellow-mustard.jpg', 30.000, 24.000, 3.99, 42.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(217, 'Mustard (Dijon)', 'COND-017', 'dijon-mustard.jpg', 25.000, 20.000, 6.50, 35.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(218, 'Mustard (Whole Grain)', 'COND-018', 'whole-grain-mustard.jpg', 20.000, 16.000, 7.25, 28.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(219, 'Mayonnaise', 'COND-019', 'mayonnaise.jpg', 35.000, 28.000, 5.99, 48.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(220, 'Mayonnaise (Light)', 'COND-020', 'light-mayonnaise.jpg', 28.000, 22.400, 6.50, 38.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(221, 'BBQ Sauce', 'COND-021', 'bbq-sauce.jpg', 32.000, 25.600, 5.75, 45.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(222, 'Teriyaki Sauce', 'COND-022', 'teriyaki-sauce.jpg', 28.000, 22.400, 6.99, 38.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(223, 'Hoisin Sauce', 'COND-023', 'hoisin-sauce.jpg', 22.000, 17.600, 7.50, 30.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(224, 'Sweet Chili Sauce', 'COND-024', 'sweet-chili-sauce.jpg', 25.000, 20.000, 5.99, 35.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(225, 'Tomato Sauce', 'COND-025', 'tomato-sauce.jpg', 50.000, 40.000, 3.50, 68.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(226, 'Tomato Paste', 'COND-026', 'tomato-paste.jpg', 40.000, 32.000, 2.99, 55.000, 5, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(227, 'Crushed Tomatoes', 'COND-027', 'crushed-tomatoes.jpg', 45.000, 36.000, 3.75, 60.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(228, 'Diced Tomatoes (Canned)', 'COND-028', 'diced-tomatoes.jpg', 55.000, 44.000, 2.50, 72.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(229, 'Pasta Sauce (Marinara)', 'COND-029', 'marinara-sauce.jpg', 40.000, 32.000, 4.99, 55.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(230, 'Pasta Sauce (Alfredo)', 'COND-030', 'alfredo-sauce.jpg', 35.000, 28.000, 6.50, 48.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(231, 'Pesto Sauce', 'COND-031', 'pesto-sauce.jpg', 20.000, 16.000, 8.99, 28.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(232, 'Salsa (Mild)', 'COND-032', 'mild-salsa.jpg', 32.000, 25.600, 4.25, 45.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(233, 'Salsa (Medium)', 'COND-033', 'medium-salsa.jpg', 30.000, 24.000, 4.50, 42.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(234, 'Salsa (Hot)', 'COND-034', 'hot-salsa.jpg', 25.000, 20.000, 4.75, 35.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(235, 'Ranch Dressing', 'COND-035', 'ranch-dressing.jpg', 28.000, 22.400, 5.99, 38.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(236, 'Caesar Dressing', 'COND-036', 'caesar-dressing.jpg', 25.000, 20.000, 6.50, 35.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(237, 'Italian Dressing', 'COND-037', 'italian-dressing.jpg', 22.000, 17.600, 5.25, 30.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(238, 'Balsamic Vinaigrette', 'COND-038', 'balsamic-vinaigrette.jpg', 20.000, 16.000, 7.99, 28.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(239, 'White Vinegar', 'COND-039', 'white-vinegar.jpg', 35.000, 28.000, 3.50, 48.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(240, 'Apple Cider Vinegar', 'COND-040', 'apple-cider-vinegar.jpg', 30.000, 24.000, 4.75, 42.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(241, 'Balsamic Vinegar', 'COND-041', 'balsamic-vinegar.jpg', 25.000, 20.000, 8.99, 35.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(242, 'Rice Vinegar', 'COND-042', 'rice-vinegar.jpg', 22.000, 17.600, 5.50, 30.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(243, 'Pickle Relish', 'COND-043', 'pickle-relish.jpg', 18.000, 14.400, 4.25, 25.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(244, 'Capers', 'COND-044', 'capers.jpg', 12.000, 9.600, 6.99, 18.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(245, 'Olives (Black, Sliced)', 'COND-045', 'black-olives-sliced.jpg', 25.000, 20.000, 5.75, 35.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(246, 'Olives (Green, Whole)', 'COND-046', 'green-olives-whole.jpg', 22.000, 17.600, 6.50, 30.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(247, 'Kalamata Olives', 'COND-047', 'kalamata-olives.jpg', 18.000, 14.400, 8.99, 25.000, 5, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(248, 'Peanut Butter', 'COND-048', 'peanut-butter.jpg', 30.000, 24.000, 7.50, 42.000, 5, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(249, 'Almond Butter', 'COND-049', 'almond-butter.jpg', 20.000, 16.000, 12.99, 28.000, 5, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(250, 'Tahini', 'COND-050', 'tahini.jpg', 15.000, 12.000, 9.99, 22.000, 5, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(251, 'Sea Salt', 'SPICE-001', 'sea-salt.jpg', 10.000, 8.000, 8.00, 15.000, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(252, 'Kosher Salt', 'SPICE-002', 'kosher-salt.jpg', 12.000, 9.600, 6.50, 18.000, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(253, 'Table Salt', 'SPICE-003', 'table-salt.jpg', 15.000, 12.000, 4.25, 20.000, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(254, 'Black Pepper (Whole)', 'SPICE-004', 'black-pepper-whole.jpg', 3.000, 2.400, 25.00, 5.000, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(255, 'Black Pepper (Ground)', 'SPICE-005', 'black-pepper-ground.jpg', 2.500, 2.000, 28.00, 4.000, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(256, 'White Pepper', 'SPICE-006', 'white-pepper.jpg', 1.500, 1.200, 32.00, 2.500, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(257, 'Cayenne Pepper', 'SPICE-007', 'cayenne-pepper.jpg', 1.200, 0.960, 18.50, 2.000, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(258, 'Paprika', 'SPICE-008', 'paprika.jpg', 2.000, 1.600, 15.00, 3.000, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(259, 'Smoked Paprika', 'SPICE-009', 'smoked-paprika.jpg', 1.500, 1.200, 22.00, 2.500, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(260, 'Garlic Powder', 'SPICE-010', 'garlic-powder.jpg', 3.000, 2.400, 18.00, 4.500, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(261, 'Onion Powder', 'SPICE-011', 'onion-powder.jpg', 2.500, 2.000, 16.50, 4.000, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(262, 'Cumin (Ground)', 'SPICE-012', 'cumin-ground.jpg', 2.000, 1.600, 14.99, 3.000, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(263, 'Cumin (Whole Seeds)', 'SPICE-013', 'cumin-seeds.jpg', 1.800, 1.440, 12.50, 2.800, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(264, 'Coriander (Ground)', 'SPICE-014', 'coriander-ground.jpg', 1.500, 1.200, 13.75, 2.500, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(265, 'Coriander Seeds', 'SPICE-015', 'coriander-seeds.jpg', 1.300, 1.040, 11.99, 2.200, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(266, 'Turmeric', 'SPICE-016', 'turmeric.jpg', 2.200, 1.760, 16.00, 3.500, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(267, 'Ginger Powder', 'SPICE-017', 'ginger-powder.jpg', 1.800, 1.440, 19.50, 3.000, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(268, 'Cinnamon (Ground)', 'SPICE-018', 'cinnamon-ground.jpg', 2.500, 2.000, 20.00, 4.000, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(269, 'Cinnamon Sticks', 'SPICE-019', 'cinnamon-sticks.jpg', 1.000, 0.800, 28.99, 1.800, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(270, 'Nutmeg (Ground)', 'SPICE-020', 'nutmeg-ground.jpg', 0.800, 0.640, 35.00, 1.500, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(271, 'Nutmeg (Whole)', 'SPICE-021', 'nutmeg-whole.jpg', 0.600, 0.480, 42.00, 1.200, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(272, 'Cloves (Whole)', 'SPICE-022', 'cloves-whole.jpg', 0.500, 0.400, 38.50, 1.000, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(273, 'Cloves (Ground)', 'SPICE-023', 'cloves-ground.jpg', 0.600, 0.480, 40.00, 1.200, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(274, 'Cardamom (Ground)', 'SPICE-024', 'cardamom-ground.jpg', 0.400, 0.320, 55.00, 0.800, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(275, 'Cardamom Pods', 'SPICE-025', 'cardamom-pods.jpg', 0.500, 0.400, 60.00, 1.000, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(276, 'Bay Leaves', 'SPICE-026', 'bay-leaves.jpg', 0.300, 0.240, 22.50, 0.600, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(277, 'Oregano (Dried)', 'SPICE-027', 'oregano-dried.jpg', 1.000, 0.800, 18.00, 1.800, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(278, 'Basil (Dried)', 'SPICE-028', 'basil-dried.jpg', 1.200, 0.960, 20.00, 2.000, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(279, 'Thyme (Dried)', 'SPICE-029', 'thyme-dried.jpg', 0.800, 0.640, 24.00, 1.500, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(280, 'Rosemary (Dried)', 'SPICE-030', 'rosemary-dried.jpg', 0.700, 0.560, 26.50, 1.300, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(281, 'Sage (Dried)', 'SPICE-031', 'sage-dried.jpg', 0.600, 0.480, 28.00, 1.200, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(282, 'Parsley (Dried)', 'SPICE-032', 'parsley-dried.jpg', 1.000, 0.800, 16.50, 1.800, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(283, 'Dill (Dried)', 'SPICE-033', 'dill-dried.jpg', 0.700, 0.560, 19.00, 1.300, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(284, 'Chili Powder', 'SPICE-034', 'chili-powder.jpg', 2.000, 1.600, 15.50, 3.200, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(285, 'Chili Flakes', 'SPICE-035', 'chili-flakes.jpg', 1.500, 1.200, 14.00, 2.500, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(286, 'Italian Seasoning', 'SPICE-036', 'italian-seasoning.jpg', 1.800, 1.440, 17.50, 3.000, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(287, 'Herbs de Provence', 'SPICE-037', 'herbs-de-provence.jpg', 1.200, 0.960, 24.99, 2.000, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(288, 'Cajun Seasoning', 'SPICE-038', 'cajun-seasoning.jpg', 1.500, 1.200, 18.50, 2.500, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(289, 'Taco Seasoning', 'SPICE-039', 'taco-seasoning.jpg', 2.000, 1.600, 12.99, 3.200, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(290, 'Curry Powder', 'SPICE-040', 'curry-powder.jpg', 1.800, 1.440, 16.50, 3.000, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(291, 'Garam Masala', 'SPICE-041', 'garam-masala.jpg', 1.500, 1.200, 22.00, 2.500, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(292, 'Five Spice Powder', 'SPICE-042', 'five-spice-powder.jpg', 1.000, 0.800, 26.50, 1.800, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(293, 'Allspice (Ground)', 'SPICE-043', 'allspice-ground.jpg', 0.800, 0.640, 21.00, 1.500, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(294, 'Mustard Seeds (Yellow)', 'SPICE-044', 'yellow-mustard-seeds.jpg', 1.200, 0.960, 11.50, 2.000, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(295, 'Mustard Seeds (Black)', 'SPICE-045', 'black-mustard-seeds.jpg', 1.000, 0.800, 13.00, 1.800, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(296, 'Fennel Seeds', 'SPICE-046', 'fennel-seeds.jpg', 1.100, 0.880, 14.50, 2.000, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(297, 'Sesame Seeds (White)', 'SPICE-047', 'white-sesame-seeds.jpg', 3.000, 2.400, 9.99, 5.000, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(298, 'Sesame Seeds (Black)', 'SPICE-048', 'black-sesame-seeds.jpg', 2.000, 1.600, 12.50, 3.500, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(299, 'Poppy Seeds', 'SPICE-049', 'poppy-seeds.jpg', 0.800, 0.640, 28.00, 1.500, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(300, 'Celery Seeds', 'SPICE-050', 'celery-seeds.jpg', 0.600, 0.480, 18.50, 1.200, 6, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(301, 'Orange Juice', 'BEV-001', 'orange-juice.jpg', 80.000, 64.000, 5.50, 105.000, 7, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(302, 'Apple Juice', 'BEV-002', 'apple-juice.jpg', 70.000, 56.000, 4.99, 95.000, 7, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(303, 'Cranberry Juice', 'BEV-003', 'cranberry-juice.jpg', 60.000, 48.000, 6.25, 80.000, 7, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(304, 'Grape Juice', 'BEV-004', 'grape-juice.jpg', 55.000, 44.000, 5.75, 72.000, 7, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(305, 'Pineapple Juice', 'BEV-005', 'pineapple-juice.jpg', 50.000, 40.000, 5.99, 68.000, 7, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(306, 'Tomato Juice', 'BEV-006', 'tomato-juice.jpg', 45.000, 36.000, 4.50, 60.000, 7, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(307, 'Lemonade', 'BEV-007', 'lemonade.jpg', 65.000, 52.000, 4.25, 85.000, 7, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(308, 'Iced Tea (Sweetened)', 'BEV-008', 'sweet-iced-tea.jpg', 70.000, 56.000, 3.99, 95.000, 7, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(309, 'Iced Tea (Unsweetened)', 'BEV-009', 'unsweet-iced-tea.jpg', 60.000, 48.000, 3.75, 80.000, 7, 7, 7, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(310, 'Coffee Beans (Arabica)', 'BEV-010', 'arabica-coffee-beans.jpg', 25.000, 20.000, 22.00, 35.000, 7, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(311, 'Coffee Beans (Robusta)', 'BEV-011', 'robusta-coffee-beans.jpg', 20.000, 16.000, 18.50, 30.000, 7, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(312, 'Ground Coffee (Medium Roast)', 'BEV-012', 'medium-roast-coffee.jpg', 30.000, 24.000, 20.99, 42.000, 7, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(313, 'Ground Coffee (Dark Roast)', 'BEV-013', 'dark-roast-coffee.jpg', 28.000, 22.400, 21.50, 38.000, 7, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(314, 'Espresso Beans', 'BEV-014', 'espresso-beans.jpg', 22.000, 17.600, 24.99, 32.000, 7, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(315, 'Decaf Coffee', 'BEV-015', 'decaf-coffee.jpg', 18.000, 14.400, 23.50, 26.000, 7, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00'),
(316, 'Black Tea', 'BEV-016', 'black-tea.jpg', 8.000, 6.400, 15.00, 12.000, 7, 2, 2, '2026-01-30 23:18:00', '2026-01-30 23:18:00');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_categories`
--

CREATE TABLE `inventory_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory_categories`
--

INSERT INTO `inventory_categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Meat/Poultry', '2026-01-28 13:25:12', '2026-01-28 13:25:12'),
(2, 'Dairy/Eggs', '2026-01-28 13:25:12', '2026-01-28 13:25:12'),
(3, 'Produce', '2026-01-28 13:25:12', '2026-01-28 13:25:12'),
(4, 'Dry Goods/Grains', '2026-01-28 13:25:12', '2026-01-28 13:25:12'),
(5, 'Condiments/Sauces', '2026-01-28 13:25:12', '2026-01-28 13:25:12'),
(6, 'Spices/Seasonings', '2026-01-28 13:25:12', '2026-01-28 13:25:12'),
(7, 'Beverages', '2026-01-28 13:25:12', '2026-01-28 13:25:12');

-- --------------------------------------------------------

--
-- Table structure for table `item_orders`
--

CREATE TABLE `item_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `menu_item_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','preparing','completed','cancelled') NOT NULL DEFAULT 'pending',
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_order_customizations`
--

CREATE TABLE `item_order_customizations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_order_id` bigint(20) UNSIGNED NOT NULL,
  `menu_item_customization_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `price` double NOT NULL,
  `menu_item_category_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `name`, `description`, `image`, `price`, `menu_item_category_id`, `created_at`, `updated_at`) VALUES
(2, 'sdsds', 'dsdsd', 'menu_images/YEvLmQq8sn0OkG3g9jNOqps8EkTuWaCyTRNg2gmI.jpg', 100, 5, '2026-01-31 12:59:41', '2026-01-31 12:59:41'),
(3, 'dfdf', 'dfdfdf', 'menu_images/67EDWXgXA6TONSL71iNrqMS7k3Ck4b0DY86Dvm49.jpg', 100, 7, '2026-01-31 13:12:58', '2026-01-31 13:12:58'),
(4, 'Filipino Spaghetti', 'Filipino Spaghetti is perfect for birthdays, family gatherings, or any celebration where you want a sweet pasta dish.', 'menu_images/fKGyWqtUE7utBdH1Newy9Lr039qyavlDRfbUOlcy.jpg', 250, 4, '2026-02-02 04:49:37', '2026-02-02 04:49:37');

-- --------------------------------------------------------

--
-- Table structure for table `menu_item_categories`
--

CREATE TABLE `menu_item_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu_item_categories`
--

INSERT INTO `menu_item_categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Starters/Appetizers', '2026-01-28 13:25:12', '2026-01-28 13:25:12'),
(2, 'Soups & Salads', '2026-01-28 13:25:12', '2026-01-28 13:25:12'),
(3, 'Main Courses', '2026-01-28 13:25:12', '2026-01-28 13:25:12'),
(4, 'Pasta & Risotto', '2026-01-28 13:25:12', '2026-01-28 13:25:12'),
(5, 'Sides / Accompaniments', '2026-01-28 13:25:12', '2026-01-28 13:25:12'),
(6, 'Desserts', '2026-01-28 13:25:12', '2026-01-28 13:25:12'),
(7, 'Beverages', '2026-01-28 13:25:12', '2026-01-28 13:25:12');

-- --------------------------------------------------------

--
-- Table structure for table `menu_item_customizations`
--

CREATE TABLE `menu_item_customizations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `menu_item_id` bigint(20) UNSIGNED NOT NULL,
  `ingredient_id` bigint(20) UNSIGNED DEFAULT NULL,
  `inventory_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `quantity` double DEFAULT NULL,
  `unit_of_measurement_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu_item_customizations`
--

INSERT INTO `menu_item_customizations` (`id`, `menu_item_id`, `ingredient_id`, `inventory_id`, `name`, `quantity`, `unit_of_measurement_id`, `action`, `created_at`, `updated_at`) VALUES
(1, 3, 5, 2, NULL, 2, 2, 'replace', '2026-01-31 13:12:58', '2026-01-31 13:12:58'),
(2, 3, 5, 3, NULL, 2, 2, 'replace', '2026-01-31 13:12:58', '2026-01-31 13:12:58'),
(3, 3, 5, 4, NULL, 1, 2, 'replace', '2026-01-31 13:12:58', '2026-01-31 13:12:58'),
(4, 3, 5, 9, NULL, 2, 2, 'replace', '2026-01-31 13:12:58', '2026-01-31 13:12:58'),
(5, 3, 5, NULL, NULL, NULL, NULL, 'remove', '2026-01-31 13:12:58', '2026-01-31 13:12:58'),
(6, 3, 6, NULL, NULL, NULL, NULL, 'remove', '2026-01-31 13:12:58', '2026-01-31 13:12:58'),
(7, 3, 7, NULL, NULL, NULL, NULL, 'remove', '2026-01-31 13:12:58', '2026-01-31 13:12:58'),
(8, 3, 8, NULL, NULL, NULL, NULL, 'remove', '2026-01-31 13:12:58', '2026-01-31 13:12:58'),
(9, 3, 9, NULL, NULL, NULL, NULL, 'remove', '2026-01-31 13:12:58', '2026-01-31 13:12:58'),
(10, 3, NULL, 255, NULL, 2, 5, 'add', '2026-01-31 13:12:58', '2026-01-31 13:12:58'),
(11, 3, NULL, 260, NULL, 2, 2, 'add', '2026-01-31 13:12:58', '2026-01-31 13:12:58'),
(12, 3, NULL, 257, NULL, 2, 5, 'add', '2026-01-31 13:12:58', '2026-01-31 13:12:58'),
(13, 3, NULL, 253, NULL, 1, 4, 'add', '2026-01-31 13:12:58', '2026-01-31 13:12:58'),
(14, 3, NULL, 269, NULL, 2, 5, 'add', '2026-01-31 13:12:58', '2026-01-31 13:12:58'),
(15, 3, NULL, 267, NULL, 2, 5, 'add', '2026-01-31 13:12:58', '2026-01-31 13:12:58');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_06_23_094915_add_timestamps_to_sections_table', 2),
(5, '2025_08_13_103939_create_activities_table', 3),
(6, '2025_08_25_015336_create_users_table', 4),
(7, '2025_08_25_020513_add_role_to_user_table', 5),
(8, '2025_08_25_022106_add_name_to_user_table', 6),
(9, '2025_08_25_111756_update_users_nullable_columns', 7),
(15, '2025_08_26_121338_create_section_table', 8),
(16, '2025_08_29_221216_create_activities_table', 8),
(17, '2025_08_29_224451_add_section_id_to_activities_table', 9),
(18, '2025_09_10_123949_add_user_id_to_activities_table', 10),
(19, '2025_09_10_131811_add_user_id_to_section_table', 10),
(20, '2025_09_12_053236_add_profile_image_to_users_table', 11),
(21, '2025_09_25_021418_add_grades_to_activities', 12),
(22, '2025_09_27_022356_add_provider_to_users_table', 13),
(23, '2025_09_27_055817_add_google_id_in_users_table', 14),
(24, '2025_09_28_103312_create_activities_table', 15),
(25, '2025_09_29_075119_add_section_id_to_users_table', 16),
(26, '2025_10_07_114117_add_share_code_to_section_table', 16),
(27, '2025_10_07_114759_add_share_code_to_section_table', 17),
(28, '2025_10_20_080730_create_roles_table', 17),
(29, '2025_10_20_080758_create_activity_user_role_table', 18),
(30, '2025_10_21_130217_create_recent_activities_table', 18),
(31, '2025_10_21_132433_add_section_id_to_recent_activities_table', 19),
(32, '2025_10_29_075721_add_class_code_to_section_table', 19),
(33, '2025_11_02_124358_add_is_archived_in_section_table', 20),
(34, '2025_11_05_031659_create_section_invitations_table', 21),
(35, '2025_11_05_032500_add_invite_code_to_section_table', 21),
(36, '2025_11_12_203315_create_announcements_tabble', 22),
(37, '2025_11_14_094734_add_is_archived_to_activities_table', 22),
(38, '2025_11_14_102026_add_is_archived_to_section_members_table', 23),
(39, '2025_11_15_084516_create_email_invites_table', 24),
(40, '2025_11_16_081959_add_status_to_email_invites_table', 25),
(41, '2025_11_16_194820_create_simulation_attempts_table', 26),
(42, '2025_11_16_194848_create_simulation_logs_table', 26),
(43, '2025_11_16_204103_create_simulation_grades_table', 27),
(47, '2025_11_17_074641_create_simulation_sessions_table', 28),
(48, '2025_11_17_074642_create_simulation_actions_table', 28),
(49, '2025_11_17_074643_create_simulation_scenarios_table', 29),
(50, '2025_12_29_092643_create_student_quizzes_table', 30),
(51, '2026_01_27_083302_create_attachment_table', 31),
(52, '2025_11_05_141510_create_inventory_categories_table', 32),
(53, '2025_11_05_141543_create_unit_of_measurements_table', 32),
(54, '2025_11_05_141827_create_inventories_table', 32),
(55, '2025_11_16_044815_create_menu_item_categories_table', 32),
(56, '2025_11_16_044847_create_menu_items_table', 32),
(57, '2025_11_16_045119_create_ingredients_table', 32),
(58, '2025_11_16_045245_create_menu_item_customizations_table', 32),
(59, '2025_12_02_090521_create_floor_plans_table', 32),
(60, '2025_12_02_090609_create_tables_table', 32),
(61, '2025_12_14_094714_create_orders_table', 32),
(62, '2025_12_14_094817_create_item_orders_table', 32),
(63, '2025_12_14_094905_create_item_order_customizations_table', 32);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `table_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` enum('standard','priority','take-out') NOT NULL DEFAULT 'standard',
  `status` enum('preparing','completed') NOT NULL DEFAULT 'preparing',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `table_id`, `type`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, 'standard', 'preparing', '2026-01-29 00:02:52', '2026-01-29 00:02:52');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('hoonp845@gmail.com', '$2y$12$X8DTgznuNaQVqFlfsmLjnu2EaDtWjHaMmWastK5B4X.u7JzxbReXu', '2026-01-22 00:06:20'),
('hoonpark113020@gmail.com', '$2y$12$O7YQD94fom9LNi6khrb3HOwxw7ZEt.FOHEJFBO9ajgGsaWLSYR9Ly', '2026-01-22 00:07:05');

-- --------------------------------------------------------

--
-- Table structure for table `recent_activities`
--

CREATE TABLE `recent_activities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `section_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `recent_activities`
--

INSERT INTO `recent_activities` (`id`, `user_id`, `section_id`, `action`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'Created new section', 'Section: BSHM 1-1', '2026-01-22 12:07:43', '2026-01-22 12:07:43'),
(2, 1, NULL, 'Created new section', 'Section: BSHM 1-1', '2026-01-22 12:11:33', '2026-01-22 12:11:33'),
(3, 1, NULL, 'Deleted the section', 'Section: BSHM 1-1', '2026-01-22 12:14:19', '2026-01-22 12:14:19'),
(4, 1, NULL, 'Created new section', 'Section: BSHM 1-1', '2026-01-22 12:14:26', '2026-01-22 12:14:26'),
(5, 1, NULL, 'Created new section', 'Section: BSHM 1-1', '2026-01-22 12:15:01', '2026-01-22 12:15:01'),
(6, 1, NULL, 'Created new section', 'Section: BSHM 1-1', '2026-01-22 12:18:05', '2026-01-22 12:18:05'),
(7, 1, NULL, 'Deleted the section', 'Section: BSHM 1-1', '2026-01-22 23:58:09', '2026-01-22 23:58:09'),
(8, 1, NULL, 'Deleted the section', 'Section: BSHM 1-1', '2026-01-22 23:58:14', '2026-01-22 23:58:14'),
(9, 1, NULL, 'Created new activity', 'Activity: The \"Rush Hour\" POS Simulation', '2026-01-23 01:05:12', '2026-01-23 01:05:12'),
(10, 1, NULL, 'Updated the activity', 'Activity: The \"Rush Hour\" POS Simulation', '2026-01-23 01:21:55', '2026-01-23 01:21:55'),
(11, 1, NULL, 'Created new activity', 'Activity: Activity 1: Exploring the RestauSim', '2026-01-23 01:23:27', '2026-01-23 01:23:27'),
(12, 1, NULL, 'Updated the activity', 'Activity: The \"Rush Hour\" POS Simulation', '2026-01-23 01:23:42', '2026-01-23 01:23:42'),
(13, 1, NULL, 'Updated the activity', 'Activity: The \"Rush Hour\" POS Simulation', '2026-01-23 01:24:16', '2026-01-23 01:24:16'),
(14, 1, NULL, 'Updated the activity', 'Activity: Activity 1: Exploring the RestauSim', '2026-01-23 01:25:36', '2026-01-23 01:25:36'),
(15, 1, NULL, 'Deleted the activity', 'Activity: Activity 1: Exploring the RestauSim', '2026-01-23 01:25:49', '2026-01-23 01:25:49'),
(16, 1, NULL, 'Created new activity', 'Activity: Real-Time Menu Management', '2026-01-23 01:26:24', '2026-01-23 01:26:24'),
(17, 1, NULL, 'Created new activity', 'Activity: Activity 1: Exploring the RestauSim', '2026-01-23 01:26:44', '2026-01-23 01:26:44'),
(18, 1, NULL, 'Deleted the activity', 'Activity: Activity 1: Exploring the RestauSim', '2026-01-23 01:27:31', '2026-01-23 01:27:31'),
(19, 1, NULL, 'Updated the activity', 'Activity: Real-Time Menu Management', '2026-01-23 01:45:05', '2026-01-23 01:45:05'),
(20, 1, NULL, 'Created new activity', 'Activity: Activity 1: Exploring the RestauSim', '2026-01-23 03:11:46', '2026-01-23 03:11:46'),
(21, 1, NULL, 'Created new activity', 'Activity: Activity 1: Exploring the RestauSim sds', '2026-01-23 03:15:49', '2026-01-23 03:15:49'),
(22, 1, NULL, 'Deleted the activity', 'Activity: Activity 1: Exploring the RestauSim sds', '2026-01-23 03:15:59', '2026-01-23 03:15:59'),
(23, 1, NULL, 'Deleted the activity', 'Activity: Activity 1: Exploring the RestauSim', '2026-01-23 03:16:06', '2026-01-23 03:16:06'),
(24, 1, NULL, 'Created new activity', 'Activity: Managing B2B Operations', '2026-01-23 03:19:39', '2026-01-23 03:19:39'),
(26, 1, NULL, 'Created new activity', 'Activity: The Discrepancy Detective', '2026-01-26 03:24:01', '2026-01-26 03:24:01'),
(27, 1, NULL, 'Created new activity', 'Activity: Setting the Safety Net', '2026-01-26 03:24:22', '2026-01-26 03:24:22'),
(28, 1, NULL, 'Created new activity', 'Activity: Mastering Table Management', '2026-01-26 03:24:41', '2026-01-26 03:24:41'),
(29, 1, NULL, 'Created new activity', 'Activity: From Ingredient to Invoice', '2026-01-26 03:25:09', '2026-01-26 03:25:09'),
(30, 1, NULL, 'Created new activity', 'Activity: Prioritizing High-Value Assets', '2026-01-26 03:25:35', '2026-01-26 03:25:35'),
(31, 1, NULL, 'Assigned Role', 'Assigned role \'Cashier\' to student Gu Wei Yi for activity \'The \"Rush Hour\" POS Simulation\'', '2026-01-26 03:47:21', '2026-01-26 03:47:21'),
(32, 1, NULL, 'Created new activity', 'Activity: Tracking Invisible Loss', '2026-01-26 03:49:06', '2026-01-26 03:49:06'),
(33, 1, NULL, 'Created new section', 'Section: BSHM 1-1', '2026-01-26 06:49:53', '2026-01-26 06:49:53'),
(34, 1, NULL, 'Created new section', 'Section: BSTM 1-2', '2026-01-26 06:50:13', '2026-01-26 06:50:13'),
(35, 1, NULL, 'Created new activity', 'Activity: Bulk Database Management', '2026-01-26 06:52:51', '2026-01-26 06:52:51'),
(36, 1, NULL, 'Created new activity', 'Activity: Raw to Real-Time Inventory', '2026-01-26 07:09:06', '2026-01-26 07:09:06'),
(37, 1, NULL, 'Updated the activity', 'Activity: Prioritizing High-Value Assets', '2026-01-27 12:40:49', '2026-01-27 12:40:49'),
(38, 1, NULL, 'Updated the activity', 'Activity: Tracking Invisible Loss', '2026-01-27 12:40:53', '2026-01-27 12:40:53'),
(39, 1, NULL, 'Updated the activity', 'Activity: Tracking Invisible Loss', '2026-01-27 12:40:53', '2026-01-27 12:40:53'),
(40, 1, NULL, 'Updated the activity', 'Activity: Prioritizing High-Value Assets', '2026-01-27 12:40:58', '2026-01-27 12:40:58'),
(41, 1, NULL, 'Updated the activity', 'Activity: Prioritizing High-Value Assets', '2026-01-27 12:41:15', '2026-01-27 12:41:15'),
(42, 9, 3, 'A student joined to this section', 'Student: Student User joined Section: BSHM 1-1', '2026-01-27 12:45:45', '2026-01-27 12:45:45'),
(43, 1, NULL, 'Created new activity', 'Activity: When the Cloud Goes Dark', '2026-01-27 13:02:22', '2026-01-27 13:02:22'),
(44, 1, NULL, 'Assigned Role', 'Assigned role \'Cashier\' to student Student User for activity \'When the Cloud Goes Dark\'', '2026-02-05 00:38:36', '2026-02-05 00:38:36');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Cashier', NULL, NULL),
(2, 'Waiter', NULL, NULL),
(3, 'Kitchen Staff', NULL, NULL),
(4, 'Host/Front Desk', NULL, NULL),
(5, 'Manager', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE `section` (
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `section_name` varchar(255) DEFAULT NULL,
  `class_name` varchar(255) DEFAULT NULL,
  `class_code` varchar(255) DEFAULT NULL,
  `share_code` varchar(20) DEFAULT NULL,
  `invite_code` varchar(20) DEFAULT NULL,
  `is_archived` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `section`
--

INSERT INTO `section` (`section_id`, `user_id`, `section_name`, `class_name`, `class_code`, `share_code`, `invite_code`, `is_archived`, `created_at`, `updated_at`) VALUES
(3, 1, 'BSHM 1-1', 'Applied Tools and Technologies Lab', 'HM 1135', 'RST-CIHFI', NULL, 0, '2026-01-22 12:15:01', '2026-01-22 12:15:01'),
(5, 1, 'BSHM 1-1', 'Applied Tools and Technologies', 'HM 1135', 'RST-S8IRO', NULL, 0, '2026-01-26 06:49:53', '2026-01-26 06:49:53'),
(6, 1, 'BSTM 1-2', 'Applied Tools and Technologies', 'TM 1135', 'RST-GQUGS', NULL, 0, '2026-01-26 06:50:13', '2026-01-26 06:50:13');

-- --------------------------------------------------------

--
-- Table structure for table `section_members`
--

CREATE TABLE `section_members` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_archived` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `section_members`
--

INSERT INTO `section_members` (`id`, `section_id`, `user_id`, `created_at`, `updated_at`, `joined_at`, `is_archived`) VALUES
(2, 3, 9, '2026-01-27 12:45:45', '2026-01-27 12:45:45', '2026-01-27 12:45:45', 0);

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

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('bJUWk5LlYbAkrVwu8DmegJsu45s22DNWzRYo6qq9', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiS1RvVFRkb2piYkU0VkNHcmU0S3FPT1F2WjRiY2JzQ2J5RkRhMmVUbyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMzoiaHR0cDovL2xvY2FsaG9zdC9yb29tL3B1YmxpYy9tZW51Ijt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly9sb2NhbGhvc3Qvcm9vbS9wdWJsaWMvbWVudS9jcmVhdGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1770349146);

-- --------------------------------------------------------

--
-- Table structure for table `simulation_actions`
--

CREATE TABLE `simulation_actions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `session_id` bigint(20) UNSIGNED NOT NULL,
  `action_type` varchar(255) NOT NULL,
  `action_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`action_data`)),
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_correct` tinyint(1) DEFAULT NULL,
  `points_earned` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `simulation_actions`
--

INSERT INTO `simulation_actions` (`id`, `session_id`, `action_type`, `action_data`, `timestamp`, `is_correct`, `points_earned`, `created_at`, `updated_at`) VALUES
(312, 29, 'item_added', '{\"item\":\"Classic Burger\",\"price\":149,\"category\":\"Main\"}', '2026-02-04 16:40:09', NULL, NULL, '2026-02-05 00:40:10', '2026-02-05 00:40:10'),
(313, 29, 'item_added', '{\"item\":\"Cheese Pizza\",\"price\":199,\"category\":\"Main\"}', '2026-02-04 16:40:10', NULL, NULL, '2026-02-05 00:40:10', '2026-02-05 00:40:10'),
(314, 29, 'item_added', '{\"item\":\"Grilled Chicken\",\"price\":179,\"category\":\"Main\"}', '2026-02-04 16:40:10', NULL, NULL, '2026-02-05 00:40:11', '2026-02-05 00:40:11'),
(315, 29, 'item_added', '{\"item\":\"French Fries\",\"price\":59,\"category\":\"Appetizer\"}', '2026-02-04 16:40:11', NULL, NULL, '2026-02-05 00:40:12', '2026-02-05 00:40:12'),
(316, 29, 'item_added', '{\"item\":\"Caesar Salad\",\"price\":89,\"category\":\"Appetizer\"}', '2026-02-04 16:40:12', NULL, NULL, '2026-02-05 00:40:12', '2026-02-05 00:40:12'),
(317, 29, 'item_added', '{\"item\":\"Onion Rings\",\"price\":69,\"category\":\"Appetizer\"}', '2026-02-04 16:40:12', NULL, NULL, '2026-02-05 00:40:13', '2026-02-05 00:40:13'),
(318, 29, 'discount_applied', '{\"type\":\"manager\"}', '2026-02-04 16:40:15', NULL, NULL, '2026-02-05 00:40:15', '2026-02-05 00:40:15'),
(319, 29, 'payment_method_selected', '{\"method\":\"card\"}', '2026-02-04 16:40:16', NULL, NULL, '2026-02-05 00:40:16', '2026-02-05 00:40:16'),
(320, 29, 'payment_processed', '{\"order_items\":[{\"name\":\"Classic Burger\",\"price\":149,\"quantity\":1},{\"name\":\"Cheese Pizza\",\"price\":199,\"quantity\":1},{\"name\":\"Grilled Chicken\",\"price\":179,\"quantity\":1},{\"name\":\"French Fries\",\"price\":59,\"quantity\":1},{\"name\":\"Caesar Salad\",\"price\":89,\"quantity\":1},{\"name\":\"Onion Rings\",\"price\":69,\"quantity\":1}],\"subtotal\":\"744.00\",\"discount_type\":\"manager\",\"discount\":\"111.60\",\"total\":\"632.40\",\"payment_method\":\"card\",\"cash_received\":null,\"change\":null}', '2026-02-04 16:40:18', NULL, NULL, '2026-02-05 00:40:19', '2026-02-05 00:40:19');

-- --------------------------------------------------------

--
-- Table structure for table `simulation_scenarios`
--

CREATE TABLE `simulation_scenarios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `activity_id` bigint(20) UNSIGNED NOT NULL,
  `scenario_type` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `parameters` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`parameters`)),
  `grading_rubric` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`grading_rubric`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `simulation_sessions`
--

CREATE TABLE `simulation_sessions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `activity_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role_name` varchar(255) NOT NULL,
  `started_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `submitted_at` timestamp NULL DEFAULT NULL,
  `status` enum('in_progress','submitted','graded') NOT NULL DEFAULT 'in_progress',
  `score` decimal(5,2) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `session_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`session_data`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `simulation_sessions`
--

INSERT INTO `simulation_sessions` (`id`, `activity_id`, `user_id`, `role_name`, `started_at`, `submitted_at`, `status`, `score`, `feedback`, `session_data`, `created_at`, `updated_at`) VALUES
(29, 16, 9, 'Cashier', '2026-02-05 00:40:24', '2026-02-05 00:40:24', 'submitted', NULL, NULL, '{\"total_actions\":9,\"duration_minutes\":1.12,\"actions_by_type\":{\"item_added\":6,\"discount_applied\":1,\"payment_method_selected\":1,\"payment_processed\":1},\"total_orders\":1,\"total_revenue\":\"632.40\",\"avg_order_value\":\"632.40\",\"session_duration_minutes\":\"0.30\",\"orders_per_minute\":\"3.34\"}', '2026-02-05 00:39:17', '2026-02-05 00:40:24');

-- --------------------------------------------------------

--
-- Table structure for table `student_quizzes`
--

CREATE TABLE `student_quizzes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `age` int(2) DEFAULT NULL,
  `sex` varchar(7) NOT NULL,
  `section` varchar(255) DEFAULT NULL,
  `quiz_type` varchar(255) NOT NULL DEFAULT 'pre-assessment',
  `score` int(11) NOT NULL,
  `answers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`answers`)),
  `completed_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_quizzes`
--

INSERT INTO `student_quizzes` (`id`, `user_id`, `age`, `sex`, `section`, `quiz_type`, `score`, `answers`, `completed_at`, `created_at`, `updated_at`) VALUES
(8, 8, 21, 'Female', 'BSHM 1-1', 'pre-assessment', 6, '{\"1\":{\"answer\":\"D\",\"correct\":\"B\",\"is_correct\":false},\"2\":{\"answer\":\"A\",\"correct\":\"C\",\"is_correct\":false},\"3\":{\"answer\":\"B\",\"correct\":\"B\",\"is_correct\":true},\"4\":{\"answer\":\"B\",\"correct\":\"B\",\"is_correct\":true},\"5\":{\"answer\":\"B\",\"correct\":\"B\",\"is_correct\":true},\"6\":{\"answer\":\"C\",\"correct\":\"B\",\"is_correct\":false},\"7\":{\"answer\":\"A\",\"correct\":\"C\",\"is_correct\":false},\"8\":{\"answer\":\"D\",\"correct\":\"B\",\"is_correct\":false},\"9\":{\"answer\":\"D\",\"correct\":\"B\",\"is_correct\":false},\"10\":{\"answer\":\"B\",\"correct\":\"C\",\"is_correct\":false},\"11\":{\"answer\":\"C\",\"correct\":\"C\",\"is_correct\":true},\"12\":{\"answer\":\"A\",\"correct\":\"B\",\"is_correct\":false},\"13\":{\"answer\":\"D\",\"correct\":\"B\",\"is_correct\":false},\"14\":{\"answer\":\"A\",\"correct\":\"B\",\"is_correct\":false},\"15\":{\"answer\":\"A\",\"correct\":\"B\",\"is_correct\":false},\"16\":{\"answer\":\"A\",\"correct\":\"C\",\"is_correct\":false},\"17\":{\"answer\":\"B\",\"correct\":\"B\",\"is_correct\":true},\"18\":{\"answer\":\"C\",\"correct\":\"B\",\"is_correct\":false},\"19\":{\"answer\":\"A\",\"correct\":\"B\",\"is_correct\":false},\"20\":{\"answer\":\"B\",\"correct\":\"C\",\"is_correct\":false},\"21\":{\"answer\":\"B\",\"correct\":\"B\",\"is_correct\":true},\"22\":{\"answer\":\"A\",\"correct\":\"C\",\"is_correct\":false},\"23\":{\"answer\":\"A\",\"correct\":\"B\",\"is_correct\":false},\"24\":{\"answer\":\"A\",\"correct\":\"B\",\"is_correct\":false},\"25\":{\"answer\":\"C\",\"correct\":\"B\",\"is_correct\":false}}', '2026-01-27 01:34:34', '2026-01-27 01:34:34', '2026-01-27 01:34:34'),
(9, 9, 21, 'Female', 'bshm 1-1', 'pre-assessment', 7, '{\"1\":{\"answer\":\"C\",\"correct\":\"B\",\"is_correct\":false},\"2\":{\"answer\":\"D\",\"correct\":\"C\",\"is_correct\":false},\"3\":{\"answer\":\"D\",\"correct\":\"B\",\"is_correct\":false},\"4\":{\"answer\":\"D\",\"correct\":\"B\",\"is_correct\":false},\"5\":{\"answer\":\"B\",\"correct\":\"B\",\"is_correct\":true},\"6\":{\"answer\":\"C\",\"correct\":\"B\",\"is_correct\":false},\"7\":{\"answer\":\"A\",\"correct\":\"C\",\"is_correct\":false},\"8\":{\"answer\":\"A\",\"correct\":\"B\",\"is_correct\":false},\"9\":{\"answer\":\"A\",\"correct\":\"B\",\"is_correct\":false},\"10\":{\"answer\":\"A\",\"correct\":\"C\",\"is_correct\":false},\"11\":{\"answer\":\"C\",\"correct\":\"C\",\"is_correct\":true},\"12\":{\"answer\":\"B\",\"correct\":\"B\",\"is_correct\":true},\"13\":{\"answer\":\"A\",\"correct\":\"B\",\"is_correct\":false},\"14\":{\"answer\":\"A\",\"correct\":\"B\",\"is_correct\":false},\"15\":{\"answer\":\"C\",\"correct\":\"B\",\"is_correct\":false},\"16\":{\"answer\":\"C\",\"correct\":\"C\",\"is_correct\":true},\"17\":{\"answer\":\"D\",\"correct\":\"B\",\"is_correct\":false},\"18\":{\"answer\":\"C\",\"correct\":\"B\",\"is_correct\":false},\"19\":{\"answer\":\"D\",\"correct\":\"B\",\"is_correct\":false},\"20\":{\"answer\":\"A\",\"correct\":\"C\",\"is_correct\":false},\"21\":{\"answer\":\"B\",\"correct\":\"B\",\"is_correct\":true},\"22\":{\"answer\":\"B\",\"correct\":\"C\",\"is_correct\":false},\"23\":{\"answer\":\"B\",\"correct\":\"B\",\"is_correct\":true},\"24\":{\"answer\":\"C\",\"correct\":\"B\",\"is_correct\":false},\"25\":{\"answer\":\"B\",\"correct\":\"B\",\"is_correct\":true}}', '2026-01-27 12:45:34', '2026-01-27 12:45:34', '2026-01-27 12:45:34');

-- --------------------------------------------------------

--
-- Table structure for table `tables`
--

CREATE TABLE `tables` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `floor_plan_id` bigint(20) UNSIGNED NOT NULL,
  `table_code` varchar(255) NOT NULL,
  `status` enum('available','reserved','occupied','dirty') NOT NULL DEFAULT 'available',
  `svg_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `unit_of_measurements`
--

CREATE TABLE `unit_of_measurements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `symbol` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `unit_of_measurements`
--

INSERT INTO `unit_of_measurements` (`id`, `name`, `symbol`, `category`, `created_at`, `updated_at`) VALUES
(1, 'gram', 'g', 'weight', '2026-01-28 13:25:12', '2026-01-28 13:25:12'),
(2, 'kilogram', 'kg', 'weight', '2026-01-28 13:25:12', '2026-01-28 13:25:12'),
(3, 'ounce', 'oz', 'weight', '2026-01-28 13:25:12', '2026-01-28 13:25:12'),
(4, 'pound', 'lb', 'weight', '2026-01-28 13:25:12', '2026-01-28 13:25:12'),
(5, 'milligram', 'mg', 'weight', '2026-01-28 13:25:12', '2026-01-28 13:25:12'),
(6, 'milliliter', 'ml', 'volume', '2026-01-28 13:25:12', '2026-01-28 13:25:12'),
(7, 'liter', 'l', 'volume', '2026-01-28 13:25:12', '2026-01-28 13:25:12'),
(8, 'teaspoon', 'tsp', 'volume', '2026-01-28 13:25:12', '2026-01-28 13:25:12'),
(9, 'tablespoon', 'tbsp', 'volume', '2026-01-28 13:25:12', '2026-01-28 13:25:12'),
(10, 'fluid ounce', 'fl oz', 'volume', '2026-01-28 13:25:12', '2026-01-28 13:25:12'),
(11, 'cup', 'cup', 'volume', '2026-01-28 13:25:12', '2026-01-28 13:25:12'),
(12, 'pint', 'pt', 'volume', '2026-01-28 13:25:12', '2026-01-28 13:25:12'),
(13, 'quart', 'qt', 'volume', '2026-01-28 13:25:12', '2026-01-28 13:25:12'),
(14, 'gallon', 'gal', 'volume', '2026-01-28 13:25:12', '2026-01-28 13:25:12'),
(15, 'piece', 'pc', 'count', '2026-01-28 13:25:12', '2026-01-28 13:25:12');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `section_id` varchar(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` enum('student','faculty','admin','superadmin') NOT NULL DEFAULT 'student',
  `name` varchar(255) NOT NULL,
  `google_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `section_id`, `email`, `profile_image`, `password`, `created_at`, `updated_at`, `role`, `name`, `google_id`) VALUES
(1, 'facultyuser', NULL, 'faculty@someone.com', 'user_1769062693_1559.png', '$2y$12$QB1YuPK6RKv4YnlJk2BYJOJ/2CuBvvFapieuc7wHFvJ3qYQ.scb8S', '2026-01-22 06:18:13', '2026-01-22 06:18:13', 'faculty', 'Faculty User', NULL),
(2, 'adminuser', NULL, 'adminuser@gmail.com', 'user_1769208468_8130.png', '$2y$12$oMQynDDOp69h6tXhVcV6N.2byFwbVS1.bUlPqMKgk1L5gURC2qb7e', '2026-01-23 22:47:49', '2026-01-23 22:47:49', 'admin', 'Admin', NULL),
(3, 'hoon', NULL, 'avbalonzo30@gmail.com', 'user_1769437199_2497.png', '$2y$12$82p/oFQPeJ50hlBFX3u7S.tEZofGeiE7xWBVCQQ.iiHjjQDjEKgfy', '2026-01-26 14:19:59', '2026-01-26 14:19:59', 'faculty', 'Yoon Chan-Young', NULL),
(7, 'superadmin', NULL, 'superadmin@gmail.com', 'user_1769477535_9881.png', '$2y$12$0TMwFgyfwskqetu8D3Lsr.lxtzv./Flc3qxmZQXKf/riNGDHPzzEK', '2026-01-27 01:32:15', '2026-01-27 01:32:15', 'superadmin', 'SuperAdminUser', NULL),
(8, 'hoonpark113020', NULL, 'hoonpark113020@gmail.com', 'user_697816026ea8b.png', NULL, '2026-01-27 01:33:54', '2026-01-27 01:33:54', 'student', 'Hoon Park', '112595900369304172643'),
(9, 'studentuser', NULL, 'studentuser@gmail.com', 'user_1769517898_7922.png', '$2y$12$DJO4C5WLm4DmtN2Jt0N2XOH6QAXdX4.tCt1eDndks/Ectl4riW2yK', '2026-01-27 12:44:59', '2026-01-27 12:44:59', 'student', 'Student User', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`activity_id`),
  ADD KEY `activities_section_id_foreign` (`section_id`),
  ADD KEY `activities_user_id_foreign` (`user_id`);

--
-- Indexes for table `activity_user_role`
--
ALTER TABLE `activity_user_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attachments`
--
ALTER TABLE `attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attachments_announcement_id_foreign` (`announcement_id`);

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
-- Indexes for table `email_invites`
--
ALTER TABLE `email_invites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_invites_token_unique` (`token`),
  ADD KEY `email_invites_section_id_foreign` (`section_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `floor_plans`
--
ALTER TABLE `floor_plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ingredients_menu_item_id_foreign` (`menu_item_id`),
  ADD KEY `ingredients_inventory_id_foreign` (`inventory_id`),
  ADD KEY `ingredients_unit_of_measurement_id_foreign` (`unit_of_measurement_id`);

--
-- Indexes for table `inventories`
--
ALTER TABLE `inventories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `inventories_code_unique` (`code`),
  ADD KEY `inventories_inventory_category_id_foreign` (`inventory_category_id`),
  ADD KEY `inventories_inventory_unit_id_foreign` (`inventory_unit_id`),
  ADD KEY `inventories_cost_unit_id_foreign` (`cost_unit_id`);

--
-- Indexes for table `inventory_categories`
--
ALTER TABLE `inventory_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item_orders`
--
ALTER TABLE `item_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_orders_order_id_foreign` (`order_id`),
  ADD KEY `item_orders_menu_item_id_foreign` (`menu_item_id`);

--
-- Indexes for table `item_order_customizations`
--
ALTER TABLE `item_order_customizations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_order_customizations_item_order_id_foreign` (`item_order_id`),
  ADD KEY `item_order_customizations_menu_item_customization_id_foreign` (`menu_item_customization_id`);

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
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_items_menu_item_category_id_foreign` (`menu_item_category_id`);

--
-- Indexes for table `menu_item_categories`
--
ALTER TABLE `menu_item_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_item_customizations`
--
ALTER TABLE `menu_item_customizations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_item_customizations_menu_item_id_foreign` (`menu_item_id`),
  ADD KEY `menu_item_customizations_ingredient_id_foreign` (`ingredient_id`),
  ADD KEY `menu_item_customizations_inventory_id_foreign` (`inventory_id`),
  ADD KEY `menu_item_customizations_unit_of_measurement_id_foreign` (`unit_of_measurement_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_table_id_foreign` (`table_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `recent_activities`
--
ALTER TABLE `recent_activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recent_activities_user_id_foreign` (`user_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `section`
--
ALTER TABLE `section`
  ADD PRIMARY KEY (`section_id`),
  ADD UNIQUE KEY `section_share_code_unique` (`share_code`),
  ADD UNIQUE KEY `section_invite_code_unique` (`invite_code`),
  ADD KEY `section_user_id_foreign` (`user_id`);

--
-- Indexes for table `section_members`
--
ALTER TABLE `section_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `section_members_section_id_foreign` (`section_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `simulation_actions`
--
ALTER TABLE `simulation_actions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `simulation_actions_session_id_index` (`session_id`),
  ADD KEY `simulation_actions_action_type_index` (`action_type`);

--
-- Indexes for table `simulation_scenarios`
--
ALTER TABLE `simulation_scenarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `simulation_scenarios_activity_id_foreign` (`activity_id`);

--
-- Indexes for table `simulation_sessions`
--
ALTER TABLE `simulation_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `simulation_sessions_user_id_foreign` (`user_id`),
  ADD KEY `simulation_sessions_activity_id_user_id_index` (`activity_id`,`user_id`),
  ADD KEY `simulation_sessions_status_index` (`status`);

--
-- Indexes for table `student_quizzes`
--
ALTER TABLE `student_quizzes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_quizzes_user_id_quiz_type_index` (`user_id`,`quiz_type`);

--
-- Indexes for table `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tables_floor_plan_id_foreign` (`floor_plan_id`);

--
-- Indexes for table `unit_of_measurements`
--
ALTER TABLE `unit_of_measurements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `activity_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `activity_user_role`
--
ALTER TABLE `activity_user_role`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `attachments`
--
ALTER TABLE `attachments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `email_invites`
--
ALTER TABLE `email_invites`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `floor_plans`
--
ALTER TABLE `floor_plans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ingredients`
--
ALTER TABLE `ingredients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `inventories`
--
ALTER TABLE `inventories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=317;

--
-- AUTO_INCREMENT for table `inventory_categories`
--
ALTER TABLE `inventory_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `item_orders`
--
ALTER TABLE `item_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_order_customizations`
--
ALTER TABLE `item_order_customizations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `menu_item_categories`
--
ALTER TABLE `menu_item_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `menu_item_customizations`
--
ALTER TABLE `menu_item_customizations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `recent_activities`
--
ALTER TABLE `recent_activities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `section`
--
ALTER TABLE `section`
  MODIFY `section_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `section_members`
--
ALTER TABLE `section_members`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `simulation_actions`
--
ALTER TABLE `simulation_actions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=321;

--
-- AUTO_INCREMENT for table `simulation_scenarios`
--
ALTER TABLE `simulation_scenarios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `simulation_sessions`
--
ALTER TABLE `simulation_sessions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `student_quizzes`
--
ALTER TABLE `student_quizzes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tables`
--
ALTER TABLE `tables`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `unit_of_measurements`
--
ALTER TABLE `unit_of_measurements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `activities_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `section` (`section_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `activities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `attachments`
--
ALTER TABLE `attachments`
  ADD CONSTRAINT `attachments_announcement_id_foreign` FOREIGN KEY (`announcement_id`) REFERENCES `announcements` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `email_invites`
--
ALTER TABLE `email_invites`
  ADD CONSTRAINT `email_invites_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `section` (`section_id`) ON DELETE CASCADE;

--
-- Constraints for table `ingredients`
--
ALTER TABLE `ingredients`
  ADD CONSTRAINT `ingredients_inventory_id_foreign` FOREIGN KEY (`inventory_id`) REFERENCES `inventories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ingredients_menu_item_id_foreign` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ingredients_unit_of_measurement_id_foreign` FOREIGN KEY (`unit_of_measurement_id`) REFERENCES `unit_of_measurements` (`id`);

--
-- Constraints for table `inventories`
--
ALTER TABLE `inventories`
  ADD CONSTRAINT `inventories_cost_unit_id_foreign` FOREIGN KEY (`cost_unit_id`) REFERENCES `unit_of_measurements` (`id`),
  ADD CONSTRAINT `inventories_inventory_category_id_foreign` FOREIGN KEY (`inventory_category_id`) REFERENCES `inventory_categories` (`id`),
  ADD CONSTRAINT `inventories_inventory_unit_id_foreign` FOREIGN KEY (`inventory_unit_id`) REFERENCES `unit_of_measurements` (`id`);

--
-- Constraints for table `item_orders`
--
ALTER TABLE `item_orders`
  ADD CONSTRAINT `item_orders_menu_item_id_foreign` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items` (`id`),
  ADD CONSTRAINT `item_orders_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `item_order_customizations`
--
ALTER TABLE `item_order_customizations`
  ADD CONSTRAINT `item_order_customizations_item_order_id_foreign` FOREIGN KEY (`item_order_id`) REFERENCES `item_orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_order_customizations_menu_item_customization_id_foreign` FOREIGN KEY (`menu_item_customization_id`) REFERENCES `menu_item_customizations` (`id`);

--
-- Constraints for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `menu_items_menu_item_category_id_foreign` FOREIGN KEY (`menu_item_category_id`) REFERENCES `menu_item_categories` (`id`);

--
-- Constraints for table `menu_item_customizations`
--
ALTER TABLE `menu_item_customizations`
  ADD CONSTRAINT `menu_item_customizations_ingredient_id_foreign` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `menu_item_customizations_inventory_id_foreign` FOREIGN KEY (`inventory_id`) REFERENCES `inventories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `menu_item_customizations_menu_item_id_foreign` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `menu_item_customizations_unit_of_measurement_id_foreign` FOREIGN KEY (`unit_of_measurement_id`) REFERENCES `unit_of_measurements` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_table_id_foreign` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`);

--
-- Constraints for table `recent_activities`
--
ALTER TABLE `recent_activities`
  ADD CONSTRAINT `recent_activities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `section`
--
ALTER TABLE `section`
  ADD CONSTRAINT `section_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `section_members`
--
ALTER TABLE `section_members`
  ADD CONSTRAINT `section_members_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `section` (`section_id`) ON DELETE CASCADE;

--
-- Constraints for table `simulation_actions`
--
ALTER TABLE `simulation_actions`
  ADD CONSTRAINT `simulation_actions_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `simulation_sessions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `simulation_scenarios`
--
ALTER TABLE `simulation_scenarios`
  ADD CONSTRAINT `simulation_scenarios_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`activity_id`) ON DELETE CASCADE;

--
-- Constraints for table `simulation_sessions`
--
ALTER TABLE `simulation_sessions`
  ADD CONSTRAINT `simulation_sessions_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`activity_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `simulation_sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_quizzes`
--
ALTER TABLE `student_quizzes`
  ADD CONSTRAINT `student_quizzes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tables`
--
ALTER TABLE `tables`
  ADD CONSTRAINT `tables_floor_plan_id_foreign` FOREIGN KEY (`floor_plan_id`) REFERENCES `floor_plans` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
