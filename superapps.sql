-- MySQL dump 10.13  Distrib 8.0.28, for macos11 (arm64)
--
-- Host: localhost    Database: superapps
-- ------------------------------------------------------
-- Server version	8.0.28

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `card_counselings`
--

DROP TABLE IF EXISTS `card_counselings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `card_counselings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_student` bigint unsigned NOT NULL,
  `semester` tinyint unsigned NOT NULL,
  `sks` smallint unsigned NOT NULL,
  `ip` decimal(3,2) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `failed_courses` text COLLATE utf8mb4_unicode_ci,
  `retaken_courses` text COLLATE utf8mb4_unicode_ci,
  `komentar` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `card_counselings_id_student_foreign` (`id_student`),
  CONSTRAINT `card_counselings_id_student_foreign` FOREIGN KEY (`id_student`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `card_counselings`
--

LOCK TABLES `card_counselings` WRITE;
/*!40000 ALTER TABLE `card_counselings` DISABLE KEYS */;
INSERT INTO `card_counselings` VALUES (1,3,2,20,3.00,'2025-09-29','[]','[]','Joss','2025-09-29 13:49:39','2025-09-29 13:49:39');
/*!40000 ALTER TABLE `card_counselings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `courses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code_prefix` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_number` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `program_study` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lecturer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `room` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `day` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `note` text COLLATE utf8mb4_unicode_ci,
  `semester` tinyint unsigned NOT NULL,
  `sks` tinyint unsigned NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `courses`
--

LOCK TABLES `courses` WRITE;
/*!40000 ALTER TABLE `courses` DISABLE KEYS */;
/*!40000 ALTER TABLE `courses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `final_project_proposals`
--

DROP TABLE IF EXISTS `final_project_proposals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `final_project_proposals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `final_project_id` bigint unsigned NOT NULL,
  `registered_at` timestamp NULL DEFAULT NULL,
  `scheduled_at` timestamp NULL DEFAULT NULL,
  `status` enum('pending','approved','rejected','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `approval_notes` text COLLATE utf8mb4_unicode_ci,
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `grade` decimal(5,2) DEFAULT NULL,
  `result_notes` text COLLATE utf8mb4_unicode_ci,
  `examiner_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `final_project_proposals`
--

LOCK TABLES `final_project_proposals` WRITE;
/*!40000 ALTER TABLE `final_project_proposals` DISABLE KEYS */;
/*!40000 ALTER TABLE `final_project_proposals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `final_projects`
--

DROP TABLE IF EXISTS `final_projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `final_projects` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title_approved_at` timestamp NULL DEFAULT NULL,
  `supervisor_1_id` bigint unsigned DEFAULT NULL,
  `supervisor_2_id` bigint unsigned DEFAULT NULL,
  `status` enum('proposal','research','defense','completed','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'proposal',
  `progress_percentage` int NOT NULL DEFAULT '0',
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `final_projects_student_id_foreign` (`student_id`),
  KEY `final_projects_supervisor_1_id_foreign` (`supervisor_1_id`),
  KEY `final_projects_supervisor_2_id_foreign` (`supervisor_2_id`),
  CONSTRAINT `final_projects_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `final_projects_supervisor_1_id_foreign` FOREIGN KEY (`supervisor_1_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `final_projects_supervisor_2_id_foreign` FOREIGN KEY (`supervisor_2_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `final_projects`
--

LOCK TABLES `final_projects` WRITE;
/*!40000 ALTER TABLE `final_projects` DISABLE KEYS */;
/*!40000 ALTER TABLE `final_projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_09_05_132855_create_students_table',1),(5,'2025_09_07_132959_create_card_counselings_table',1),(6,'2025_09_15_141833_create_matkuls_table',1),(7,'2025_09_18_100956_add_alamat_point_to_students_table',1),(8,'2025_11_05_232548_add_program_studi_to_users_table',2),(9,'2025_11_06_000339_add_ipk_sks_to_students_table',3),(10,'2025_11_06_000644_add_ipk_sks_columns_to_students_table',4),(11,'2025_11_06_001340_create_student_achievements_table',5),(13,'2025_12_03_224120_create_final_projects_table',6);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('RMMqbNsPcN8JZ5cNPVVymN8cVQo4kVMa7f9uJPGr',1,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','YToxMDp7czo2OiJfdG9rZW4iO3M6NDA6Im1UOWp6Z1I0NnlCRmJFYlhwY0dsYXM5QWd6dWYxVGlNSkVhQ3ZKZDEiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM3OiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYWRtaW4vZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjc6InVzZXJfaWQiO3M6MjAwOiJleUpwZGlJNklsRnBkR3BXVUdsWmFuQmlkRXhaTldvNEszUXJRbEU5UFNJc0luWmhiSFZsSWpvaWMyMHZWbGhJYTNjcllWRnpVRWRoY0VrMVlYQlhVVDA5SWl3aWJXRmpJam9pT1dNMU16STFOMkUyWmpZd1l6Sm1NRGt4WVdVNFpEZzVNbU5sT0dJek5XTTBZbVUzWkdFeVpqUTVaR0ZpTkdSaU1qWmtaVFJtT0RFMFlUUTJaVGMwTXlJc0luUmhaeUk2SWlKOSI7czo5OiJ1c2VyX25hbWUiO3M6OToiVGVzdCBVc2VyIjtzOjEwOiJ1c2VyX2VtYWlsIjtzOjE0OiJhZG1pbkBtYWlsLmNvbSI7czo5OiJ1c2VyX3JvbGUiO3M6MTA6InN1cGVyYWRtaW4iO3M6MTA6InVzZXJfcGhvdG8iO3M6MToiMCI7czoxMDoidXNlcl9wcm9kaSI7czoxNDoiQmlzbmlzIERpZ2l0YWwiO30=',1764686856);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_achievements`
--

DROP TABLE IF EXISTS `student_achievements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `student_achievements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama event/kompetisi',
  `achievement` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Juara/prestasi yang diraih',
  `level` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tingkat: Regional/Nasional/Internasional',
  `certificate` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Path file piagam',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_achievements_student_id_foreign` (`student_id`),
  CONSTRAINT `student_achievements_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_achievements`
--

LOCK TABLES `student_achievements` WRITE;
/*!40000 ALTER TABLE `student_achievements` DISABLE KEYS */;
INSERT INTO `student_achievements` VALUES (1,3,'Kompetisi Game Mobile','Juara 3','Kota/Kabupaten','students/achievements/3/Kth5QuZiAhUQ6s0DjKHmDyBWReQX8jPBTQoTsL7T.jpg','2025-11-05 17:18:36','2025-11-05 17:18:36');
/*!40000 ALTER TABLE `student_achievements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `students` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_lecturer` int unsigned NOT NULL,
  `nama_lengkap` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_orangtua` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '$2y$12$aqwdS/d3gD144kwzQ2iJ6e7BRRhzYNWAYxLqBmot6auL5EMUNcv/W',
  `foto` text COLLATE utf8mb4_unicode_ci,
  `ttd` text COLLATE utf8mb4_unicode_ci,
  `nim` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `angkatan` int NOT NULL,
  `program_studi` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fakultas` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `alamat_lat` decimal(10,7) DEFAULT NULL,
  `alamat_lng` decimal(10,7) DEFAULT NULL,
  `no_telepon` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_mahasiswa` enum('Aktif','Cuti','Lulus','Mengundurkan Diri') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Aktif',
  `tanggal_masuk` date NOT NULL,
  `tanggal_lulus` date DEFAULT NULL,
  `is_counseling` int NOT NULL DEFAULT '0',
  `is_edited` tinyint(1) NOT NULL DEFAULT '0',
  `tanggal_counseling` date DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `ipk` decimal(3,2) DEFAULT NULL COMMENT 'Indeks Prestasi Kumulatif (0.00 - 4.00)',
  `sks` int DEFAULT NULL COMMENT 'Jumlah SKS yang telah ditempuh',
  `sks_lulus` int DEFAULT NULL COMMENT 'Total SKS yang sudah lulus',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `students_nim_unique` (`nim`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students`
--

LOCK TABLES `students` WRITE;
/*!40000 ALTER TABLE `students` DISABLE KEYS */;
INSERT INTO `students` VALUES (3,1,'Deny Prasetyo','fufufafa','$2y$12$aqwdS/d3gD144kwzQ2iJ6e7BRRhzYNWAYxLqBmot6auL5EMUNcv/W','students/foto/3/x6xv8KbAkSKKL03cu1mbzwIueOpIbKFbHwg2bRvR.jpg','students/ttd/3/vG7DTW9fAigfwTbCzG4ZPTdJm72FYnwEL6EHlPkZ.png','12345678',2025,'Bisnis Digital',NULL,'L','2025-11-05','Patoman',NULL,NULL,'087856850432','ushdeny@gmail.com','Aktif','2025-09-29',NULL,0,1,NULL,NULL,3.99,40,NULL,'2025-09-29 13:43:26','2025-11-05 17:08:14');
/*!40000 ALTER TABLE `students` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `NIDNorNUPTK` bigint DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin',
  `program_studi` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Bisnis Digital',
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ttd` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '$2y$12$aqwdS/d3gD144kwzQ2iJ6e7BRRhzYNWAYxLqBmot6auL5EMUNcv/W',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Test User','admin',NULL,'superadmin','Bisnis Digital',NULL,NULL,'admin@mail.com','2025-09-24 08:49:28','$2y$12$aqwdS/d3gD144kwzQ2iJ6e7BRRhzYNWAYxLqBmot6auL5EMUNcv/W','WMsvFoP9xm9lrPlImbyqaBpEmUL36ar6sLXMg5HwLymDmnvWXqprJEx6CHZK','2025-09-24 08:49:28','2025-09-24 08:49:28'),(2,'Deny Prasetyo',NULL,NULL,'admin','Bisnis Digital','users/photo/iLiFfi6lD8v6NDssKzoCAqaUWi4AC9Sy9pixFiPM.jpg',NULL,'ushdeny@gmail.com',NULL,'$2y$12$/P09VPWKyK8dw7OA2ASY7eM2Y9nIxsWrPGHWOmYgUnitbdR0fJ0dC',NULL,'2025-09-29 13:43:03','2025-11-05 16:30:48');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'superapps'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-03 23:04:20
