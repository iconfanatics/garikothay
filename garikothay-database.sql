-- MySQL dump 10.13  Distrib 8.4.9, for Linux (x86_64)
--
-- Host: localhost    Database: garden_grow
-- ------------------------------------------------------
-- Server version	8.4.9-0ubuntu0.26.04.1

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
-- Table structure for table `addresses`
--

DROP TABLE IF EXISTS `addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `addresses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `label` enum('home','office','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'home',
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_line_1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_line_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `district` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `division` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `addresses_user_id_index` (`user_id`),
  CONSTRAINT `addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `addresses`
--

LOCK TABLES `addresses` WRITE;
/*!40000 ALTER TABLE `addresses` DISABLE KEYS */;
/*!40000 ALTER TABLE `addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_super_admin` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES (1,'Super Admin','admin@gardenngrow.com','$2y$12$/vjqsmeTVW9DMmXTrwTZ1.JyaiQ8EPyLy8YjSRzBZwvvKNCsN8..W',NULL,1,1,NULL,'2026-06-05 02:44:33','2026-06-05 02:44:33');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banner_translations`
--

DROP TABLE IF EXISTS `banner_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `banner_translations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `banner_id` bigint unsigned NOT NULL,
  `locale` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtitle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `banner_translations_banner_id_locale_unique` (`banner_id`,`locale`),
  CONSTRAINT `banner_translations_banner_id_foreign` FOREIGN KEY (`banner_id`) REFERENCES `banners` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banner_translations`
--

LOCK TABLES `banner_translations` WRITE;
/*!40000 ALTER TABLE `banner_translations` DISABLE KEYS */;
INSERT INTO `banner_translations` VALUES (1,1,'en','Upgrade Your Gear','Get up to 20% Off on Premium Mechanical Keyboards & Gaming Mice',NULL),(2,1,'bn','আপনার গিয়ার আপগ্রেড করুন','প্রিমিয়াম মেকানিক্যাল কিবোর্ড এবং গেমিং মাউসে ২০% পর্যন্ত ছাড়',NULL),(3,2,'en','Speed Up Your System','Unleash extreme performance with high-speed SSDs & RAM modules',NULL),(4,2,'bn','আপনার পিসি ফাস্ট করুন','হাই-স্পিড এসএসডি এবং র‍্যামের সাথে চমৎকার পারফরম্যান্স',NULL),(5,3,'en','Free Delivery Above ৳1500','Shop now and save on shipping costs',NULL),(6,3,'bn','৳১৫০০-এর উপরে ফ্রি ডেলিভারি','এখনই কেনাকাটা করুন এবং শিপিং চার্জ বাঁচান',NULL);
/*!40000 ALTER TABLE `banner_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banners`
--

DROP TABLE IF EXISTS `banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `banners` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('hero_slider','popup','promotional') COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` smallint unsigned NOT NULL DEFAULT '0',
  `starts_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banners`
--

LOCK TABLES `banners` WRITE;
/*!40000 ALTER TABLE `banners` DISABLE KEYS */;
INSERT INTO `banners` VALUES (1,'hero_slider','products/keyboard.png',NULL,'/shop',1,NULL,NULL,1,'2026-06-05 02:46:15','2026-06-05 02:46:15'),(2,'hero_slider','products/ssd.png',NULL,'/shop',2,NULL,NULL,1,'2026-06-05 02:46:15','2026-06-05 02:46:15'),(3,'promotional','banners/placeholder.jpg',NULL,'/shop',1,NULL,NULL,1,'2026-06-05 02:46:15','2026-06-05 02:46:15');
/*!40000 ALTER TABLE `banners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blog_categories`
--

DROP TABLE IF EXISTS `blog_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blog_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blog_categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_categories`
--

LOCK TABLES `blog_categories` WRITE;
/*!40000 ALTER TABLE `blog_categories` DISABLE KEYS */;
INSERT INTO `blog_categories` VALUES (1,'buying-guides','2026-06-05 02:46:15','2026-06-05 02:46:15'),(2,'tech-tips','2026-06-05 02:46:15','2026-06-05 02:46:15'),(3,'desk-setups','2026-06-05 02:46:15','2026-06-05 02:46:15');
/*!40000 ALTER TABLE `blog_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blog_category_translations`
--

DROP TABLE IF EXISTS `blog_category_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blog_category_translations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `blog_category_id` bigint unsigned NOT NULL,
  `locale` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blog_category_translations_blog_category_id_locale_unique` (`blog_category_id`,`locale`),
  CONSTRAINT `blog_category_translations_blog_category_id_foreign` FOREIGN KEY (`blog_category_id`) REFERENCES `blog_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_category_translations`
--

LOCK TABLES `blog_category_translations` WRITE;
/*!40000 ALTER TABLE `blog_category_translations` DISABLE KEYS */;
INSERT INTO `blog_category_translations` VALUES (1,1,'en','Buying Guides'),(2,1,'bn','ক্রয় গাইড'),(3,2,'en','Tech Tips'),(4,2,'bn','টেক টিপস'),(5,3,'en','Desk Setups'),(6,3,'bn','ডেস্ক সেটআপ');
/*!40000 ALTER TABLE `blog_category_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blog_translations`
--

DROP TABLE IF EXISTS `blog_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blog_translations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `blog_id` bigint unsigned NOT NULL,
  `locale` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` text COLLATE utf8mb4_unicode_ci,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blog_translations_blog_id_locale_unique` (`blog_id`,`locale`),
  CONSTRAINT `blog_translations_blog_id_foreign` FOREIGN KEY (`blog_id`) REFERENCES `blogs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_translations`
--

LOCK TABLES `blog_translations` WRITE;
/*!40000 ALTER TABLE `blog_translations` DISABLE KEYS */;
INSERT INTO `blog_translations` VALUES (1,1,'en','How to Choose the Right Mechanical Keyboard Switch','Confused between Red, Blue, and Brown mechanical switches? This guide explains the differences to help you select.','<p>Mechanical keyboards are highly customizable, and your typing experience largely depends on the switches you choose.</p><h2>Switch Types</h2><p>1. Linear Switches (usually Red) are quiet and smooth, excellent for fast gaming.</p><p>2. Tactile Switches (usually Brown) offer a quiet bump feedback, great for typing.</p><p>3. Clicky Switches (usually Blue) make a loud clicky sound, preferred by retro typists.</p>',NULL,NULL),(2,1,'bn','কীভাবে সঠিক মেকানিক্যাল কিবোর্ড সুইচ নির্বাচন করবেন','লাল, নীল এবং বাদামী মেকানিক্যাল সুইচের মধ্যে বিভ্রান্ত? এই গাইডটি আপনাকে সঠিকটি বেছে নিতে সাহায্য করবে।','<p>মেকানিক্যাল কিবোর্ড টাইপিংয়ের জন্য চমৎকার এবং এর অভিজ্ঞতা সুইচের ওপর নির্ভর করে।</p>',NULL,NULL),(3,2,'en','SSD vs HDD: Why Your PC Needs an SSD Upgrade','Is your computer running slow? Upgrading to an SSD is the single best hardware investment you can make.','<p>Solid State Drives (SSDs) have revolutionized computer storage, replacing the older spinning platter Hard Disk Drives (HDDs).</p><h2>Major Benefits of SSD</h2><p>SSDs are up to 10 times faster than standard HDDs, ensuring fast Windows boot times and instant app loading.</p>',NULL,NULL),(4,2,'bn','এসএসডি বনাম হার্ডডিস্ক: আপনার পিসির কেন একটি এসএসডি প্রয়োজন','আপনার কম্পিউটার কি ধীরগতির হয়ে গিয়েছে? একটি এসএসডি আপগ্রেড আপনাকে দিতে পারে সুপারফাস্ট গতি।','<p>সলিড স্টেট ড্রাইভ (এসএসডি) আপনার কম্পিউটারের গতি বহুগুণ বাড়িয়ে দেয়।</p>',NULL,NULL),(5,3,'en','Ultimate Guide to Cable Management for Clean Desks','Organize messy cables on your workstation with our step-by-step setup guides and tools.','<p>A clean desk leads to a productive mind. Cable management is key to maintaining a premium aesthetic setup.</p><h2>Tips to Manage Cables</h2><p>Use sleeve organizers, cable clips, and under-desk trays to hide all power lines.</p>',NULL,NULL),(6,3,'bn','ক্লিন ডেস্কের জন্য ক্যাবল ম্যানেজমেন্ট গাইড','আমাদের ধাপে ধাপে নির্দেশনার সাহায্যে আপনার ওয়ার্কস্টেশনের অগোছালো তারগুলো সাজিয়ে নিন।','<p>একটি গোছানো ডেস্ক আপনার কাজের উৎপাদনশীলতা বাড়ায়। ক্যাবল ম্যানেজমেন্টের মাধ্যমে ডেস্ক রাখুন ছবির মতো পরিষ্কার।</p>',NULL,NULL);
/*!40000 ALTER TABLE `blog_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blogs`
--

DROP TABLE IF EXISTS `blogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blogs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `blog_category_id` bigint unsigned NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `featured_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author_id` bigint unsigned NOT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT '0',
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blogs_slug_unique` (`slug`),
  KEY `blogs_blog_category_id_foreign` (`blog_category_id`),
  KEY `blogs_author_id_foreign` (`author_id`),
  KEY `blogs_slug_index` (`slug`),
  KEY `blogs_is_published_index` (`is_published`),
  CONSTRAINT `blogs_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE,
  CONSTRAINT `blogs_blog_category_id_foreign` FOREIGN KEY (`blog_category_id`) REFERENCES `blog_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blogs`
--

LOCK TABLES `blogs` WRITE;
/*!40000 ALTER TABLE `blogs` DISABLE KEYS */;
INSERT INTO `blogs` VALUES (1,1,'how-to-choose-the-right-mechanical-keyboard-switch',NULL,1,1,'2026-05-31 02:46:15','2026-06-05 02:46:15','2026-06-05 02:46:15'),(2,2,'ssd-vs-hdd-why-your-pc-needs-an-ssd-upgrade',NULL,1,1,'2026-05-10 02:46:15','2026-06-05 02:46:15','2026-06-05 02:46:15'),(3,3,'ultimate-guide-to-cable-management-for-clean-desks',NULL,1,1,'2026-05-12 02:46:15','2026-06-05 02:46:15','2026-06-05 02:46:15');
/*!40000 ALTER TABLE `blogs` ENABLE KEYS */;
UNLOCK TABLES;

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
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('dinajpuritpark-cache-setting:site_name','s:10:\"Garikothay\";',1780659454),('garikothay-cache-setting:address','s:48:\"House 24, Road 7, Banani, Dhaka 1213, Bangladesh\";',1780659478),('garikothay-cache-setting:email','s:22:\"support@garikothay.com\";',1780659478),('garikothay-cache-setting:guest_checkout_enabled','b:1;',1780659739),('garikothay-cache-setting:meta_description','s:112:\"Browse verified cars, bikes, spare parts, and auto deals from trusted sellers across Bangladesh with Garikothay.\";',1780659478),('garikothay-cache-setting:meta_title','s:59:\"Garikothay - Trusted Cars, Bikes & Auto Deals in Bangladesh\";',1780659477),('garikothay-cache-setting:phone','s:16:\"+880 1911-223344\";',1780659478),('garikothay-cache-setting:site_logo','s:29:\"settings/dinajpur_it_logo.png\";',1780659478),('garikothay-cache-setting:site_name','s:10:\"Garikothay\";',1780659472),('garikothay-cache-setting:site_tagline','s:59:\"Find trusted cars, bikes, and auto deals across Bangladesh.\";',1780659477),('garikothay-cache-setting:whatsapp','s:13:\"8801911223344\";',1780659478);
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
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
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
-- Table structure for table `cart_items`
--

DROP TABLE IF EXISTS `cart_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cart_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `variant_id` bigint unsigned DEFAULT NULL,
  `quantity` int unsigned NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cart_items_variant_id_foreign` (`variant_id`),
  KEY `cart_items_cart_id_index` (`cart_id`),
  KEY `cart_items_product_id_index` (`product_id`),
  CONSTRAINT `cart_items_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_items_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_items`
--

LOCK TABLES `cart_items` WRITE;
/*!40000 ALTER TABLE `cart_items` DISABLE KEYS */;
INSERT INTO `cart_items` VALUES (1,3,8,NULL,1,4500.00,'2026-06-05 04:32:27','2026-06-05 04:32:27');
/*!40000 ALTER TABLE `cart_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carts`
--

DROP TABLE IF EXISTS `carts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `carts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `session_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coupon_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `carts_coupon_id_foreign` (`coupon_id`),
  KEY `carts_user_id_index` (`user_id`),
  KEY `carts_session_id_index` (`session_id`),
  CONSTRAINT `carts_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE SET NULL,
  CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carts`
--

LOCK TABLES `carts` WRITE;
/*!40000 ALTER TABLE `carts` DISABLE KEYS */;
INSERT INTO `carts` VALUES (1,NULL,'p3h7dYzCfcFLgFFwnJpFSu9vf8EGGv7b5brjciiE',NULL,'2026-06-05 02:54:54','2026-06-05 02:54:54'),(2,NULL,'B6m0zVp0gMOULsGHNkoh0rb4GYcPtel6OHYPoE59',NULL,'2026-06-05 03:18:57','2026-06-05 03:18:57'),(3,NULL,'KX4yFbw7XUOaHELdygLTF4KiQptyn9HmRYq16gN9',NULL,'2026-06-05 03:46:28','2026-06-05 03:46:28'),(4,NULL,'CgKLgoVrEdzHvo5rMLn4ZwbKBVcP2hTwmMWAShUq',NULL,'2026-06-05 04:37:29','2026-06-05 04:37:29');
/*!40000 ALTER TABLE `carts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint unsigned DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` smallint unsigned NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`),
  KEY `categories_slug_index` (`slug`),
  KEY `categories_is_active_index` (`is_active`),
  KEY `categories_parent_id_index` (`parent_id`),
  CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,NULL,'keyboards','⌨️',NULL,1,1,'2026-06-05 02:46:13','2026-06-05 02:46:13'),(2,1,'mechanical-keyboards',NULL,NULL,1,1,'2026-06-05 02:46:13','2026-06-05 02:46:13'),(3,1,'wireless-keyboards',NULL,NULL,2,1,'2026-06-05 02:46:13','2026-06-05 02:46:13'),(4,1,'membrane-keyboards',NULL,NULL,3,1,'2026-06-05 02:46:13','2026-06-05 02:46:13'),(5,NULL,'mice','🖱️',NULL,2,1,'2026-06-05 02:46:13','2026-06-05 02:46:13'),(6,5,'gaming-mice',NULL,NULL,1,1,'2026-06-05 02:46:13','2026-06-05 02:46:13'),(7,5,'wireless-mice',NULL,NULL,2,1,'2026-06-05 02:46:13','2026-06-05 02:46:13'),(8,5,'office-mice',NULL,NULL,3,1,'2026-06-05 02:46:13','2026-06-05 02:46:13'),(9,NULL,'headphones-audio','🎧',NULL,3,1,'2026-06-05 02:46:13','2026-06-05 02:46:13'),(10,9,'gaming-headsets',NULL,NULL,1,1,'2026-06-05 02:46:13','2026-06-05 02:46:13'),(11,9,'bluetooth-earphones',NULL,NULL,2,1,'2026-06-05 02:46:13','2026-06-05 02:46:13'),(12,9,'desktop-speakers',NULL,NULL,3,1,'2026-06-05 02:46:13','2026-06-05 02:46:13'),(13,NULL,'storage-devices','💾',NULL,4,1,'2026-06-05 02:46:13','2026-06-05 02:46:13'),(14,13,'solid-state-drives-ssds',NULL,NULL,1,1,'2026-06-05 02:46:13','2026-06-05 02:46:13'),(15,13,'usb-pendrives',NULL,NULL,2,1,'2026-06-05 02:46:13','2026-06-05 02:46:13'),(16,13,'external-hard-drives',NULL,NULL,3,1,'2026-06-05 02:46:13','2026-06-05 02:46:13'),(17,NULL,'pc-components','🔌',NULL,5,1,'2026-06-05 02:46:13','2026-06-05 02:46:13'),(18,17,'ddr4-ddr5-ram',NULL,NULL,1,1,'2026-06-05 02:46:13','2026-06-05 02:46:13'),(19,17,'power-supplies',NULL,NULL,2,1,'2026-06-05 02:46:13','2026-06-05 02:46:13'),(20,17,'cooling-fans',NULL,NULL,3,1,'2026-06-05 02:46:13','2026-06-05 02:46:13'),(21,NULL,'laptop-accessories','💻',NULL,6,1,'2026-06-05 02:46:13','2026-06-05 02:46:13'),(22,21,'laptop-stands',NULL,NULL,1,1,'2026-06-05 02:46:14','2026-06-05 02:46:14'),(23,21,'laptop-bags-sleeves',NULL,NULL,2,1,'2026-06-05 02:46:14','2026-06-05 02:46:14'),(24,21,'power-chargers',NULL,NULL,3,1,'2026-06-05 02:46:14','2026-06-05 02:46:14'),(25,NULL,'networking-accessories','🌐',NULL,7,1,'2026-06-05 02:46:14','2026-06-05 02:46:14'),(26,25,'wi-fi-routers',NULL,NULL,1,1,'2026-06-05 02:46:14','2026-06-05 02:46:14'),(27,25,'ethernet-lan-cables',NULL,NULL,2,1,'2026-06-05 02:46:14','2026-06-05 02:46:14'),(28,25,'usb-wi-fi-adapters',NULL,NULL,3,1,'2026-06-05 02:46:14','2026-06-05 02:46:14');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `category_translations`
--

DROP TABLE IF EXISTS `category_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `category_translations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `locale` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `category_translations_category_id_locale_unique` (`category_id`,`locale`),
  KEY `category_translations_category_id_locale_index` (`category_id`,`locale`),
  CONSTRAINT `category_translations_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category_translations`
--

LOCK TABLES `category_translations` WRITE;
/*!40000 ALTER TABLE `category_translations` DISABLE KEYS */;
INSERT INTO `category_translations` VALUES (1,1,'en','Keyboards','Keyboards and computing devices.'),(2,1,'bn','কিবোর্ড','কিবোর্ড এবং কম্পিউটার সামগ্রী।'),(3,2,'en','Mechanical Keyboards',NULL),(4,2,'bn','মেকানিক্যাল কিবোর্ড',NULL),(5,3,'en','Wireless Keyboards',NULL),(6,3,'bn','ওয়্যারলেস কিবোর্ড',NULL),(7,4,'en','Membrane Keyboards',NULL),(8,4,'bn','মেমব্রেন কিবোর্ড',NULL),(9,5,'en','Mice','Mice and computing devices.'),(10,5,'bn','মাউস','মাউস এবং কম্পিউটার সামগ্রী।'),(11,6,'en','Gaming Mice',NULL),(12,6,'bn','গেমিং মাউস',NULL),(13,7,'en','Wireless Mice',NULL),(14,7,'bn','ওয়্যারলেস মাউস',NULL),(15,8,'en','Office Mice',NULL),(16,8,'bn','অফিস মাউস',NULL),(17,9,'en','Headphones & Audio','Headphones & Audio and computing devices.'),(18,9,'bn','হেডফোন ও অডিও','হেডফোন ও অডিও এবং কম্পিউটার সামগ্রী।'),(19,10,'en','Gaming Headsets',NULL),(20,10,'bn','গেমিং হেডসেট',NULL),(21,11,'en','Bluetooth Earphones',NULL),(22,11,'bn','ব্লুটুথ ইয়ারফোন',NULL),(23,12,'en','Desktop Speakers',NULL),(24,12,'bn','ডেস্কটপ স্পিকার',NULL),(25,13,'en','Storage Devices','Storage Devices and computing devices.'),(26,13,'bn','স্টোরেজ ডিভাইস','স্টোরেজ ডিভাইস এবং কম্পিউটার সামগ্রী।'),(27,14,'en','Solid State Drives (SSDs)',NULL),(28,14,'bn','এসএসডি (SSD)',NULL),(29,15,'en','USB Pendrives',NULL),(30,15,'bn','ইউএসবি পেনড্রাইভ',NULL),(31,16,'en','External Hard Drives',NULL),(32,16,'bn','এক্সটার্নাল হার্ড ড্রাইভ',NULL),(33,17,'en','PC Components','PC Components and computing devices.'),(34,17,'bn','পিসি কম্পোনেন্ট','পিসি কম্পোনেন্ট এবং কম্পিউটার সামগ্রী।'),(35,18,'en','DDR4 & DDR5 RAM',NULL),(36,18,'bn','র‍্যাম (RAM)',NULL),(37,19,'en','Power Supplies',NULL),(38,19,'bn','পাওয়ার সাপ্লাই',NULL),(39,20,'en','Cooling Fans',NULL),(40,20,'bn','কুলিং ফ্যান',NULL),(41,21,'en','Laptop Accessories','Laptop Accessories and computing devices.'),(42,21,'bn','ল্যাপটপ অ্যাক্সেসরিজ','ল্যাপটপ অ্যাক্সেসরিজ এবং কম্পিউটার সামগ্রী।'),(43,22,'en','Laptop Stands',NULL),(44,22,'bn','ল্যাপটপ স্ট্যান্ড',NULL),(45,23,'en','Laptop Bags & Sleeves',NULL),(46,23,'bn','ল্যাপটপ ব্যাগ',NULL),(47,24,'en','Power Chargers',NULL),(48,24,'bn','পাওয়ার চার্জার',NULL),(49,25,'en','Networking Accessories','Networking Accessories and computing devices.'),(50,25,'bn','নেটওয়ার্কিং অ্যাক্সেসরিজ','নেটওয়ার্কিং অ্যাক্সেসরিজ এবং কম্পিউটার সামগ্রী।'),(51,26,'en','Wi-Fi Routers',NULL),(52,26,'bn','ওয়াই-ফাই রাউটার',NULL),(53,27,'en','Ethernet LAN Cables',NULL),(54,27,'bn','ল্যান ক্যাবল',NULL),(55,28,'en','USB Wi-Fi Adapters',NULL),(56,28,'bn','ওয়াই-ফাই অ্যাডাপ্টার',NULL);
/*!40000 ALTER TABLE `category_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `coupons` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('fixed','percentage') COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `min_order_amount` decimal(10,2) DEFAULT NULL,
  `max_discount_amount` decimal(10,2) DEFAULT NULL,
  `usage_limit` int unsigned DEFAULT NULL,
  `used_count` int unsigned NOT NULL DEFAULT '0',
  `starts_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coupons_code_unique` (`code`),
  KEY `coupons_code_index` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coupons`
--

LOCK TABLES `coupons` WRITE;
/*!40000 ALTER TABLE `coupons` DISABLE KEYS */;
INSERT INTO `coupons` VALUES (1,'WELCOME10','percentage',10.00,500.00,200.00,1000,0,NULL,NULL,1,'2026-06-05 02:46:15','2026-06-05 02:46:15'),(2,'PLANT20','percentage',20.00,1000.00,500.00,500,0,NULL,NULL,1,'2026-06-05 02:46:15','2026-06-05 02:46:15'),(3,'FREESHIP','fixed',120.00,800.00,NULL,NULL,0,NULL,NULL,1,'2026-06-05 02:46:15','2026-06-05 02:46:15');
/*!40000 ALTER TABLE `coupons` ENABLE KEYS */;
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
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2024_01_01_000001_create_addresses_table',1),(5,'2024_01_01_000002_create_admins_table',1),(6,'2024_01_01_000003_create_categories_table',1),(7,'2024_01_01_000004_create_products_table',1),(8,'2024_01_01_000005_create_coupons_table',1),(9,'2024_01_01_000006_create_carts_table',1),(10,'2024_01_01_000007_create_orders_table',1),(11,'2024_01_01_000008_create_marketing_tables',1),(12,'2024_01_01_000009_create_content_tables',1),(13,'2024_01_01_000010_create_settings_tables',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `newsletters`
--

DROP TABLE IF EXISTS `newsletters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `newsletters` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_subscribed` tinyint(1) NOT NULL DEFAULT '1',
  `subscribed_at` timestamp NULL DEFAULT NULL,
  `unsubscribed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `newsletters_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `newsletters`
--

LOCK TABLES `newsletters` WRITE;
/*!40000 ALTER TABLE `newsletters` DISABLE KEYS */;
/*!40000 ALTER TABLE `newsletters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `variant_id` bigint unsigned DEFAULT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int unsigned NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_variant_id_foreign` (`variant_id`),
  KEY `order_items_order_id_index` (`order_id`),
  KEY `order_items_product_id_index` (`product_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `order_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Format: GNG-YYYYMMDD-XXXX',
  `status` enum('pending','confirmed','processing','shipped','out_for_delivery','delivered','cancelled','returned','refunded') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_status` enum('unpaid','paid','partially_refunded','refunded') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `payment_method` enum('cod','sslcommerz','stripe','bkash') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cod',
  `subtotal` decimal(10,2) NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `shipping_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total` decimal(10,2) NOT NULL,
  `coupon_id` bigint unsigned DEFAULT NULL,
  `shipping_address` json NOT NULL,
  `billing_address` json DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `shipped_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_coupon_id_foreign` (`coupon_id`),
  KEY `orders_order_number_index` (`order_number`),
  KEY `orders_status_index` (`status`),
  KEY `orders_user_id_index` (`user_id`),
  CONSTRAINT `orders_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_translations`
--

DROP TABLE IF EXISTS `page_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `page_translations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `page_id` bigint unsigned NOT NULL,
  `locale` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_translations_page_id_locale_unique` (`page_id`,`locale`),
  CONSTRAINT `page_translations_page_id_foreign` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_translations`
--

LOCK TABLES `page_translations` WRITE;
/*!40000 ALTER TABLE `page_translations` DISABLE KEYS */;
INSERT INTO `page_translations` VALUES (1,1,'en','Warranty & FAQ','<h2>General Questions</h2><p>Here you can find answers to delivery times, official brand warranties, and customer support channels.</p>',NULL,NULL),(2,1,'bn','ওয়ারেন্টি ও সাধারণ জিজ্ঞাসা','<h2>সাধারণ প্রশ্নসমূহ</h2><p>এখানে আপনি ডেলিভারি সময়সীমা, অফিসিয়াল ব্র্যান্ড ওয়ারেন্টি এবং আমাদের সাপোর্ট চ্যানেল সম্পর্কে জানতে পারবেন।</p>',NULL,NULL),(3,2,'en','Warranty & Replacement Policy','<h2>Warranty & Refund Policy</h2><p>We provide a 7-day hassle-free replacement warranty for manufacturing defects and official brand warranty coverage.</p>',NULL,NULL),(4,2,'bn','ওয়ারেন্টি ও রিপ্লেসমেন্ট পলিসি','<h2>ওয়ারেন্টি এবং রিফান্ড পলিসি</h2><p>আমরা উৎপাদনগত ত্রুটির জন্য ৭ দিনের সহজ রিপ্লেসমেন্ট ওয়ারেন্টি এবং অফিসিয়াল ব্র্যান্ড ওয়ারেন্টি কভারেজ প্রদান করি।</p>',NULL,NULL),(5,3,'en','About Us','<h2>Welcome to Dinajpur IT Park</h2><p>Your premium destination for authentic computer accessories, mechanical keyboards, gaming mice, and premium networking gear.</p>',NULL,NULL),(6,3,'bn','আমাদের সম্পর্কে','<h2>দিনাজপুর আইটি পার্ক-এ স্বাগতম</h2><p>খাঁটি কম্পিউটার অ্যাক্সেসরিজ, মেকানিক্যাল কিবোর্ড, গেমিং মাউস এবং নেটওয়ার্কিং পণ্যের অন্যতম বিশ্বস্ত ও নির্ভরযোগ্য আউটলেট।</p>',NULL,NULL),(7,4,'en','Terms & Conditions','<h2>Terms of Service</h2><p>By purchasing from Dinajpur IT Park, you agree to our brand warranty terms and secure computing transaction rules.</p>',NULL,NULL),(8,4,'bn','শর্তাবলী','<h2>সেবার শর্তাবলী</h2><p>দিনাজপুর আইটি পার্ক থেকে কেনাকাটার ক্ষেত্রে আমাদের অফিশিয়াল ব্র্যান্ড ওয়ারেন্টি শর্তাবলী প্রযোজ্য হবে।</p>',NULL,NULL),(9,5,'en','Privacy Policy','<h2>Privacy Policy</h2><p>Your security is our absolute priority. We do not sell your personal or billing information to anyone.</p>',NULL,NULL),(10,5,'bn','গোপনীয়তা নীতি','<h2>গোপনীয়তা নীতি</h2><p>আপনার তথ্যের নিরাপত্তা আমাদের সর্বোচ্চ অগ্রাধিকার। আমরা আপনার ব্যক্তিগত বা পেমেন্ট সংক্রান্ত তথ্য অন্য কারো সাথে শেয়ার করি না।</p>',NULL,NULL);
/*!40000 ALTER TABLE `page_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pages_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES (1,'faq','2026-06-05 02:46:15','2026-06-05 02:46:15'),(2,'return-policy','2026-06-05 02:46:15','2026-06-05 02:46:15'),(3,'about','2026-06-05 02:46:16','2026-06-05 02:46:16'),(4,'terms','2026-06-05 02:46:16','2026-06-05 02:46:16'),(5,'privacy','2026-06-05 02:46:16','2026-06-05 02:46:16');
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
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
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'BDT',
  `status` enum('pending','completed','failed','refunded') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `gateway_response` json DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payments_transaction_id_unique` (`transaction_id`),
  KEY `payments_order_id_index` (`order_id`),
  KEY `payments_transaction_id_index` (`transaction_id`),
  CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_images` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alt_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` smallint unsigned NOT NULL DEFAULT '0',
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_images_product_id_index` (`product_id`),
  CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_images`
--

LOCK TABLES `product_images` WRITE;
/*!40000 ALTER TABLE `product_images` DISABLE KEYS */;
INSERT INTO `product_images` VALUES (1,1,'products/keyboard.png','Fantech MaxFit61 Mechanical Keyboard',1,1,'2026-06-05 02:46:14','2026-06-05 02:46:14'),(2,2,'products/keyboard.png','Redragon K552 Kumara Rainbow Keyboard',1,1,'2026-06-05 02:46:14','2026-06-05 02:46:14'),(3,3,'products/mouse.png','Razer DeathAdder Essential Gaming Mouse',1,1,'2026-06-05 02:46:14','2026-06-05 02:46:14'),(4,4,'products/mouse.png','Logitech G102 Lightsync Gaming Mouse',1,1,'2026-06-05 02:46:14','2026-06-05 02:46:14'),(5,5,'products/headphones.png','Logitech G435 Lightspeed Gaming Headset',1,1,'2026-06-05 02:46:14','2026-06-05 02:46:14'),(6,6,'products/ssd.png','Samsung 980 Pro 1TB NVMe M.2 SSD',1,1,'2026-06-05 02:46:14','2026-06-05 02:46:14'),(7,7,'products/router.png','TP-Link Archer C6 AC1200 Wi-Fi Router',1,1,'2026-06-05 02:46:14','2026-06-05 02:46:14'),(8,8,'products/ssd.png','Corsair Vengeance LPX 16GB DDR4 RAM',1,1,'2026-06-05 02:46:14','2026-06-05 02:46:14'),(9,9,'products/keyboard.png','Aluminum Ergonomic Laptop Stand',1,1,'2026-06-05 02:46:14','2026-06-05 02:46:14'),(10,10,'products/headphones.png','Fantech GS201 RGB Desktop Speakers',1,1,'2026-06-05 02:46:14','2026-06-05 02:46:14');
/*!40000 ALTER TABLE `product_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_translations`
--

DROP TABLE IF EXISTS `product_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_translations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `locale` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_description` text COLLATE utf8mb4_unicode_ci,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `care_instructions` text COLLATE utf8mb4_unicode_ci,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_translations_product_id_locale_unique` (`product_id`,`locale`),
  KEY `product_translations_product_id_locale_index` (`product_id`,`locale`),
  CONSTRAINT `product_translations_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_translations`
--

LOCK TABLES `product_translations` WRITE;
/*!40000 ALTER TABLE `product_translations` DISABLE KEYS */;
INSERT INTO `product_translations` VALUES (1,1,'en','Fantech MaxFit61 Mechanical Keyboard','A compact 60% layout mechanical keyboard with hot-swappable switches and custom RGB lighting.','<p>A compact 60% layout mechanical keyboard with hot-swappable switches and custom RGB lighting.</p><h4>Specifications</h4><ul><li>Brand Official Product</li><li>Quality Guaranteed</li><li>Excellent Ergonomics & Durability</li></ul>','Keep clean and dry. Avoid spills. Plug carefully.','Buy Fantech MaxFit61 Mechanical Keyboard Online | Dinajpur IT Park','Get your hands on Fantech MaxFit61 Mechanical Keyboard at the best rates in Bangladesh. Order now from Dinajpur IT Park.'),(2,1,'bn','ফ্যানটেক ম্যাক্সফিট৬১ মেকানিক্যাল কিবোর্ড','হট-সোয়াপবল সুইচ এবং কাস্টম আরজিবি লাইটিং সহ একটি কমপ্যাক্ট ৬০% লেআউট মেকানিক্যাল কিবোর্ড।','<p>হট-সোয়াপবল সুইচ এবং কাস্টম আরজিবি লাইটিং সহ একটি কমপ্যাক্ট ৬০% লেআউট মেকানিক্যাল কিবোর্ড।</p><h4>স্পেসিফিকেশন</h4><ul><li>অফিসিয়াল প্রোডাক্ট</li><li>গুনগত মান সম্পন্ন</li><li>চমৎকার এর্গোনমিক্স ও স্থায়িত্ব</li></ul>','পরিষ্কার ও শুষ্ক রাখুন। তরল পদার্থ থেকে দূরে রাখুন। সাবধানে প্লাগ করুন।',NULL,NULL),(3,2,'en','Redragon K552 Kumara Rainbow Keyboard','Tenkeyless mechanical gaming keyboard with tactile blue switches and metallic construction.','<p>Tenkeyless mechanical gaming keyboard with tactile blue switches and metallic construction.</p><h4>Specifications</h4><ul><li>Brand Official Product</li><li>Quality Guaranteed</li><li>Excellent Ergonomics & Durability</li></ul>','Keep clean and dry. Avoid spills. Plug carefully.','Buy Redragon K552 Kumara Rainbow Keyboard Online | Dinajpur IT Park','Get your hands on Redragon K552 Kumara Rainbow Keyboard at the best rates in Bangladesh. Order now from Dinajpur IT Park.'),(4,2,'bn','রেড্রাগন কে৫৫২ কুমারা রেইনবো কিবোর্ড','ট্যাকটাইল ব্লু সুইচ এবং মেটালিক বডি সহ টেনকিল্যাস মেকানিক্যাল গেমিং কিবোর্ড।','<p>ট্যাকটাইল ব্লু সুইচ এবং মেটালিক বডি সহ টেনকিল্যাস মেকানিক্যাল গেমিং কিবোর্ড।</p><h4>স্পেসিফিকেশন</h4><ul><li>অফিসিয়াল প্রোডাক্ট</li><li>গুনগত মান সম্পন্ন</li><li>চমৎকার এর্গোনমিক্স ও স্থায়িত্ব</li></ul>','পরিষ্কার ও শুষ্ক রাখুন। তরল পদার্থ থেকে দূরে রাখুন। সাবধানে প্লাগ করুন।',NULL,NULL),(5,3,'en','Razer DeathAdder Essential Gaming Mouse','Ergonomic gaming mouse with a 6400 DPI optical sensor and 5 programmable Hyperesponse buttons.','<p>Ergonomic gaming mouse with a 6400 DPI optical sensor and 5 programmable Hyperesponse buttons.</p><h4>Specifications</h4><ul><li>Brand Official Product</li><li>Quality Guaranteed</li><li>Excellent Ergonomics & Durability</li></ul>','Keep clean and dry. Avoid spills. Plug carefully.','Buy Razer DeathAdder Essential Gaming Mouse Online | Dinajpur IT Park','Get your hands on Razer DeathAdder Essential Gaming Mouse at the best rates in Bangladesh. Order now from Dinajpur IT Park.'),(6,3,'bn','রেজার ডেথঅ্যাডার এসেনশিয়াল গেমিং মাউস','৬৪০০ ডিপিআই অপটিক্যাল সেন্সর এবং ৫টি প্রোগ্রাবেল বাটন সহ এর্গোনমিক গেমিং মাউস।','<p>৬৪০০ ডিপিআই অপটিক্যাল সেন্সর এবং ৫টি প্রোগ্রাবেল বাটন সহ এর্গোনমিক গেমিং মাউস।</p><h4>স্পেসিফিকেশন</h4><ul><li>অফিসিয়াল প্রোডাক্ট</li><li>গুনগত মান সম্পন্ন</li><li>চমৎকার এর্গোনমিক্স ও স্থায়িত্ব</li></ul>','পরিষ্কার ও শুষ্ক রাখুন। তরল পদার্থ থেকে দূরে রাখুন। সাবধানে প্লাগ করুন।',NULL,NULL),(7,4,'en','Logitech G102 Lightsync Gaming Mouse','Classic design with an 8000 DPI gaming-grade sensor and customizable Lightsync RGB color waves.','<p>Classic design with an 8000 DPI gaming-grade sensor and customizable Lightsync RGB color waves.</p><h4>Specifications</h4><ul><li>Brand Official Product</li><li>Quality Guaranteed</li><li>Excellent Ergonomics & Durability</li></ul>','Keep clean and dry. Avoid spills. Plug carefully.','Buy Logitech G102 Lightsync Gaming Mouse Online | Dinajpur IT Park','Get your hands on Logitech G102 Lightsync Gaming Mouse at the best rates in Bangladesh. Order now from Dinajpur IT Park.'),(8,4,'bn','লজিটেক জি১০২ লাইটসিঙ্ক গেমিং মাউস','৮০০০ ডিপিআই গেমিং-গ্রেড সেন্সর এবং কাস্টমাইজযোগ্য আরজিবি লাইটসিঙ্ক সহ ক্লাসিক গেমিং মাউস।','<p>৮০০০ ডিপিআই গেমিং-গ্রেড সেন্সর এবং কাস্টমাইজযোগ্য আরজিবি লাইটসিঙ্ক সহ ক্লাসিক গেমিং মাউস।</p><h4>স্পেসিফিকেশন</h4><ul><li>অফিসিয়াল প্রোডাক্ট</li><li>গুনগত মান সম্পন্ন</li><li>চমৎকার এর্গোনমিক্স ও স্থায়িত্ব</li></ul>','পরিষ্কার ও শুষ্ক রাখুন। তরল পদার্থ থেকে দূরে রাখুন। সাবধানে প্লাগ করুন।',NULL,NULL),(9,5,'en','Logitech G435 Lightspeed Gaming Headset','Ultra-lightweight wireless headset with low-latency Bluetooth and built-in beamforming microphones.','<p>Ultra-lightweight wireless headset with low-latency Bluetooth and built-in beamforming microphones.</p><h4>Specifications</h4><ul><li>Brand Official Product</li><li>Quality Guaranteed</li><li>Excellent Ergonomics & Durability</li></ul>','Keep clean and dry. Avoid spills. Plug carefully.','Buy Logitech G435 Lightspeed Gaming Headset Online | Dinajpur IT Park','Get your hands on Logitech G435 Lightspeed Gaming Headset at the best rates in Bangladesh. Order now from Dinajpur IT Park.'),(10,5,'bn','লজিটেক জি৪৩৫ লাইটস্পিড গেমিং হেডসেট','লো-লেটেন্সি ব্লুটুথ এবং বিল্ট-ইন বিমফর্মিং মাইক্রোফোন সহ অতি-হালকা ওয়্যারলেস হেডসেট।','<p>লো-লেটেন্সি ব্লুটুথ এবং বিল্ট-ইন বিমফর্মিং মাইক্রোফোন সহ অতি-হালকা ওয়্যারলেস হেডসেট।</p><h4>স্পেসিফিকেশন</h4><ul><li>অফিসিয়াল প্রোডাক্ট</li><li>গুনগত মান সম্পন্ন</li><li>চমৎকার এর্গোনমিক্স ও স্থায়িত্ব</li></ul>','পরিষ্কার ও শুষ্ক রাখুন। তরল পদার্থ থেকে দূরে রাখুন। সাবধানে প্লাগ করুন।',NULL,NULL),(11,6,'en','Samsung 980 Pro 1TB NVMe M.2 SSD','Next-level PCIe Gen4 SSD reaching read speeds up to 7000 MB/s for extreme gaming and content creation.','<p>Next-level PCIe Gen4 SSD reaching read speeds up to 7000 MB/s for extreme gaming and content creation.</p><h4>Specifications</h4><ul><li>Brand Official Product</li><li>Quality Guaranteed</li><li>Excellent Ergonomics & Durability</li></ul>','Keep clean and dry. Avoid spills. Plug carefully.','Buy Samsung 980 Pro 1TB NVMe M.2 SSD Online | Dinajpur IT Park','Get your hands on Samsung 980 Pro 1TB NVMe M.2 SSD at the best rates in Bangladesh. Order now from Dinajpur IT Park.'),(12,6,'bn','স্যামসাং ৯৮০ প্রো ১টিবি এনভিএমই এম.২ এসএসডি','গেমিং এবং ভারী কাজের জন্য ৭০০০ এমবি/সেকেন্ড রিড স্পিড সম্পন্ন চমৎকার পিসিআইই জেন৪ এসএসডি।','<p>গেমিং এবং ভারী কাজের জন্য ৭০০০ এমবি/সেকেন্ড রিড স্পিড সম্পন্ন চমৎকার পিসিআইই জেন৪ এসএসডি।</p><h4>স্পেসিফিকেশন</h4><ul><li>অফিসিয়াল প্রোডাক্ট</li><li>গুনগত মান সম্পন্ন</li><li>চমৎকার এর্গোনমিক্স ও স্থায়িত্ব</li></ul>','পরিষ্কার ও শুষ্ক রাখুন। তরল পদার্থ থেকে দূরে রাখুন। সাবধানে প্লাগ করুন।',NULL,NULL),(13,7,'en','TP-Link Archer C6 AC1200 Wi-Fi Router','Dual-band MU-MIMO Gigabit Wi-Fi router with 4 external antennas providing smooth wireless coverage.','<p>Dual-band MU-MIMO Gigabit Wi-Fi router with 4 external antennas providing smooth wireless coverage.</p><h4>Specifications</h4><ul><li>Brand Official Product</li><li>Quality Guaranteed</li><li>Excellent Ergonomics & Durability</li></ul>','Keep clean and dry. Avoid spills. Plug carefully.','Buy TP-Link Archer C6 AC1200 Wi-Fi Router Online | Dinajpur IT Park','Get your hands on TP-Link Archer C6 AC1200 Wi-Fi Router at the best rates in Bangladesh. Order now from Dinajpur IT Park.'),(14,7,'bn','টিপি-লিংক আর্চার সি৬ এসি১২০০ ওয়াই-ফাই রাউটার','মসৃণ ওয়্যারলেস কভারেজের জন্য ৪টি এক্সটার্নাল অ্যান্টেনা সহ ডুয়াল-ব্যান্ড গিগাবিট ওয়াই-ফাই রাউটার।','<p>মসৃণ ওয়্যারলেস কভারেজের জন্য ৪টি এক্সটার্নাল অ্যান্টেনা সহ ডুয়াল-ব্যান্ড গিগাবিট ওয়াই-ফাই রাউটার।</p><h4>স্পেসিফিকেশন</h4><ul><li>অফিসিয়াল প্রোডাক্ট</li><li>গুনগত মান সম্পন্ন</li><li>চমৎকার এর্গোনমিক্স ও স্থায়িত্ব</li></ul>','পরিষ্কার ও শুষ্ক রাখুন। তরল পদার্থ থেকে দূরে রাখুন। সাবধানে প্লাগ করুন।',NULL,NULL),(15,8,'en','Corsair Vengeance LPX 16GB DDR4 RAM','High-performance DDR4 RAM module clocked at 3200MHz, designed for Intel motherboard overclocking.','<p>High-performance DDR4 RAM module clocked at 3200MHz, designed for Intel motherboard overclocking.</p><h4>Specifications</h4><ul><li>Brand Official Product</li><li>Quality Guaranteed</li><li>Excellent Ergonomics & Durability</li></ul>','Keep clean and dry. Avoid spills. Plug carefully.','Buy Corsair Vengeance LPX 16GB DDR4 RAM Online | Dinajpur IT Park','Get your hands on Corsair Vengeance LPX 16GB DDR4 RAM at the best rates in Bangladesh. Order now from Dinajpur IT Park.'),(16,8,'bn','করসেয়ার ভেঞ্জেন্স এলপিএক্স ১৬জিবি ডিডিআর৪ র‍্যাম','ইন্টেল মাদারবোর্ড ওভারক্লকিংয়ের জন্য ডিজাইন করা ৩২-শহী ১০০ মেগাহার্টজ ডিডিআর৪ র‍্যাম।','<p>ইন্টেল মাদারবোর্ড ওভারক্লকিংয়ের জন্য ডিজাইন করা ৩২-শহী ১০০ মেগাহার্টজ ডিডিআর৪ র‍্যাম।</p><h4>স্পেসিফিকেশন</h4><ul><li>অফিসিয়াল প্রোডাক্ট</li><li>গুনগত মান সম্পন্ন</li><li>চমৎকার এর্গোনমিক্স ও স্থায়িত্ব</li></ul>','পরিষ্কার ও শুষ্ক রাখুন। তরল পদার্থ থেকে দূরে রাখুন। সাবধানে প্লাগ করুন।',NULL,NULL),(17,9,'en','Aluminum Ergonomic Laptop Stand','Adjustable aluminum laptop stand offering optimum heat dissipation and improved typing posture.','<p>Adjustable aluminum laptop stand offering optimum heat dissipation and improved typing posture.</p><h4>Specifications</h4><ul><li>Brand Official Product</li><li>Quality Guaranteed</li><li>Excellent Ergonomics & Durability</li></ul>','Keep clean and dry. Avoid spills. Plug carefully.','Buy Aluminum Ergonomic Laptop Stand Online | Dinajpur IT Park','Get your hands on Aluminum Ergonomic Laptop Stand at the best rates in Bangladesh. Order now from Dinajpur IT Park.'),(18,9,'bn','অ্যালুমিনিয়াম এর্গোনমিক ল্যাপটপ স্ট্যান্ড','সর্বোত্তম তাপ নিষ্কাশন এবং উন্নত টাইপিং ভঙ্গি নিশ্চিত করতে সামঞ্জস্যযোগ্য ল্যাপটপ স্ট্যান্ড।','<p>সর্বোত্তম তাপ নিষ্কাশন এবং উন্নত টাইপিং ভঙ্গি নিশ্চিত করতে সামঞ্জস্যযোগ্য ল্যাপটপ স্ট্যান্ড।</p><h4>স্পেসিফিকেশন</h4><ul><li>অফিসিয়াল প্রোডাক্ট</li><li>গুনগত মান সম্পন্ন</li><li>চমৎকার এর্গোনমিক্স ও স্থায়িত্ব</li></ul>','পরিষ্কার ও শুষ্ক রাখুন। তরল পদার্থ থেকে দূরে রাখুন। সাবধানে প্লাগ করুন।',NULL,NULL),(19,10,'en','Fantech GS201 RGB Desktop Speakers','Compact USB-powered desk speakers with modern breathing RGB lighting effects and clear high-resolution audio.','<p>Compact USB-powered desk speakers with modern breathing RGB lighting effects and clear high-resolution audio.</p><h4>Specifications</h4><ul><li>Brand Official Product</li><li>Quality Guaranteed</li><li>Excellent Ergonomics & Durability</li></ul>','Keep clean and dry. Avoid spills. Plug carefully.','Buy Fantech GS201 RGB Desktop Speakers Online | Dinajpur IT Park','Get your hands on Fantech GS201 RGB Desktop Speakers at the best rates in Bangladesh. Order now from Dinajpur IT Park.'),(20,10,'bn','ফ্যানটেক জিএস২০১ আরজিবি ডেস্কটপ স্পিকার','আধুনিক আরজিবি লাইটিং ইফেক্ট এবং উচ্চ রেজোলিউশনের অডিও সহ ইউএসবি স্পিকার।','<p>আধুনিক আরজিবি লাইটিং ইফেক্ট এবং উচ্চ রেজোলিউশনের অডিও সহ ইউএসবি স্পিকার।</p><h4>স্পেসিফিকেশন</h4><ul><li>অফিসিয়াল প্রোডাক্ট</li><li>গুনগত মান সম্পন্ন</li><li>চমৎকার এর্গোনমিক্স ও স্থায়িত্ব</li></ul>','পরিষ্কার ও শুষ্ক রাখুন। তরল পদার্থ থেকে দূরে রাখুন। সাবধানে প্লাগ করুন।',NULL,NULL);
/*!40000 ALTER TABLE `product_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_variants`
--

DROP TABLE IF EXISTS `product_variants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_variants` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price_modifier` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT 'Added to product base price, can be negative',
  `stock_quantity` int unsigned NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_variants_sku_unique` (`sku`),
  KEY `product_variants_product_id_index` (`product_id`),
  CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_variants`
--

LOCK TABLES `product_variants` WRITE;
/*!40000 ALTER TABLE `product_variants` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_variants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `barcode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `compare_price` decimal(10,2) DEFAULT NULL COMMENT 'Original price for showing discount',
  `cost_price` decimal(10,2) DEFAULT NULL COMMENT 'Internal cost for profit calculation',
  `stock_quantity` int unsigned NOT NULL DEFAULT '0',
  `low_stock_threshold` int unsigned NOT NULL DEFAULT '5',
  `weight_grams` int unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `is_new_arrival` tinyint(1) NOT NULL DEFAULT '0',
  `requires_shipping` tinyint(1) NOT NULL DEFAULT '1',
  `tax_rate` decimal(5,2) NOT NULL DEFAULT '0.00',
  `plant_type` enum('indoor','outdoor','both','not_plant') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sunlight` enum('full_sun','partial_shade','full_shade','any') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `watering` enum('daily','weekly','biweekly','monthly') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `difficulty` enum('beginner','intermediate','expert') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mature_size` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  UNIQUE KEY `products_sku_unique` (`sku`),
  KEY `products_slug_index` (`slug`),
  KEY `products_sku_index` (`sku`),
  KEY `products_is_active_index` (`is_active`),
  KEY `products_is_featured_index` (`is_featured`),
  KEY `products_category_id_index` (`category_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,2,'fantech-maxfit61-mechanical-keyboard','DIT-KB-0001',NULL,3800.00,4370.00,NULL,76,5,NULL,1,1,0,1,0.00,NULL,NULL,NULL,NULL,NULL,'2026-06-05 02:46:14','2026-06-05 02:46:14',NULL),(2,2,'redragon-k552-kumara-rainbow-keyboard','DIT-KB-0002',NULL,3200.00,3680.00,NULL,68,5,NULL,1,1,0,1,0.00,NULL,NULL,NULL,NULL,NULL,'2026-06-05 02:46:14','2026-06-05 02:46:14',NULL),(3,6,'razer-deathadder-essential-gaming-mouse','DIT-MS-0001',NULL,1850.00,2127.50,NULL,91,5,NULL,1,1,0,1,0.00,NULL,NULL,NULL,NULL,NULL,'2026-06-05 02:46:14','2026-06-05 02:46:14',NULL),(4,6,'logitech-g102-lightsync-gaming-mouse','DIT-MS-0002',NULL,2100.00,2415.00,NULL,94,5,NULL,1,1,0,1,0.00,NULL,NULL,NULL,NULL,NULL,'2026-06-05 02:46:14','2026-06-05 02:46:14',NULL),(5,10,'logitech-g435-lightspeed-gaming-headset','DIT-AD-0001',NULL,6800.00,7820.00,NULL,64,5,NULL,1,1,1,1,0.00,NULL,NULL,NULL,NULL,NULL,'2026-06-05 02:46:14','2026-06-05 02:46:14',NULL),(6,14,'samsung-980-pro-1tb-nvme-m2-ssd','DIT-ST-0001',NULL,11500.00,NULL,NULL,76,5,NULL,1,1,1,1,0.00,NULL,NULL,NULL,NULL,NULL,'2026-06-05 02:46:14','2026-06-05 02:46:14',NULL),(7,26,'tp-link-archer-c6-ac1200-wi-fi-router','DIT-NW-0001',NULL,2850.00,NULL,NULL,19,5,NULL,1,0,1,1,0.00,NULL,NULL,NULL,NULL,NULL,'2026-06-05 02:46:14','2026-06-05 02:46:14',NULL),(8,18,'corsair-vengeance-lpx-16gb-ddr4-ram','DIT-CP-0001',NULL,4500.00,5175.00,NULL,95,5,NULL,1,0,1,1,0.00,NULL,NULL,NULL,NULL,NULL,'2026-06-05 02:46:14','2026-06-05 02:46:14',NULL),(9,22,'aluminum-ergonomic-laptop-stand','DIT-LA-0001',NULL,1250.00,NULL,NULL,62,5,NULL,1,0,1,1,0.00,NULL,NULL,NULL,NULL,NULL,'2026-06-05 02:46:14','2026-06-05 02:46:14',NULL),(10,12,'fantech-gs201-rgb-desktop-speakers','DIT-AD-0002',NULL,950.00,NULL,NULL,57,5,NULL,1,0,1,1,0.00,NULL,NULL,NULL,NULL,NULL,'2026-06-05 02:46:14','2026-06-05 02:46:14',NULL);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reviews` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `rating` tinyint unsigned NOT NULL COMMENT '1-5',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `is_approved` tinyint(1) NOT NULL DEFAULT '0',
  `admin_reply` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reviews_product_id_index` (`product_id`),
  KEY `reviews_user_id_index` (`user_id`),
  CONSTRAINT `reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
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
INSERT INTO `sessions` VALUES ('CgKLgoVrEdzHvo5rMLn4ZwbKBVcP2hTwmMWAShUq',NULL,'127.0.0.1','curl/8.18.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoibFUwQlVUZ0hjU1JsNFdrdXpNUDV3OThTS1pISEtKTWcwdUVPNW5KQiI7czo2OiJsb2NhbGUiO3M6MjoiYm4iO3M6NToiZXJyb3IiO3M6NTM6IllvdXIgY2FydCBpcyBlbXB0eS4gUGxlYXNlIGFkZCBpdGVtcyBiZWZvcmUgY2hlY2tvdXQuIjtzOjY6Il9mbGFzaCI7YToyOntzOjM6Im5ldyI7YTowOnt9czozOiJvbGQiO2E6MTp7aTowO3M6NToiZXJyb3IiO319fQ==',1780655849),('iq3wZ31M1sQqWHD9aAiS7hV9zUuG1HwqOE1pzGVn',NULL,'127.0.0.1','curl/8.18.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTUU5WUVweXc5SnRGRTlZcEJOeEpVb1h6YWREODBNaUcyZ0YyUzlJaiI7czo2OiJsb2NhbGUiO3M6MjoiYm4iO3M6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjIxOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAiO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1780652797),('KX4yFbw7XUOaHELdygLTF4KiQptyn9HmRYq16gN9',NULL,'127.0.0.1','Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:151.0) Gecko/20100101 Firefox/151.0','YTo4OntzOjY6Il90b2tlbiI7czo0MDoiUnNISU91OW01cDd4SjVZSTRwYnBWVTJNdFRuWDZuU3hiN25pR1NESyI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjIxOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAiO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTI6ImxvZ2luX2FkbWluXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjE5OiJwYXNzd29yZF9oYXNoX2FkbWluIjtzOjY0OiI4MzZhMjBkZGNmOGE2Nzk5ZjhhOTYwMzNlYThlZmY1NDNlYzRmMGNmNWI2OTU4MjYyMWM3YzgwZjBjZWY4ZWZiIjtzOjM6InVybCI7YToxOntzOjg6ImludGVuZGVkIjtzOjMwOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvY2hlY2tvdXQiO31zOjg6ImZpbGFtZW50IjthOjA6e319',1780656207),('p3h7dYzCfcFLgFFwnJpFSu9vf8EGGv7b5brjciiE',NULL,'127.0.0.1','Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:151.0) Gecko/20100101 Firefox/151.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTm1ZeU9DVXh0VWdJanVLT1JwZDA4cFdsRVZ4WktDb3Z1M2pWcUlSZSI7czo2OiJsb2NhbGUiO3M6MjoiYm4iO3M6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjIxOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAiO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1780649694),('qnPkC5XvEyQ4ztSLZ2o4uLgLnOACnjIfqe0XkGk5',NULL,'127.0.0.1','curl/8.18.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWU5yOUQ1UGpuZ09FRHlaOGJZS2ZXa0llN0JCOFlDdFIxVUY5TzFnQSI7czo2OiJsb2NhbGUiO3M6MjoiYm4iO3M6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjIxOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAiO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1780651400),('U6b71qHunar0KmOvFuOOiCSkQTtrH7fgmTdonMEG',NULL,'127.0.0.1','curl/8.18.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQzUwM1lsUkdrSWxKWnB1UEJrMEQ2VXZtcGVBNHZ1ekxNUHBCNnBoUCI7czo2OiJsb2NhbGUiO3M6MjoiYm4iO3M6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjIxOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAiO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1780654173),('V4Znc2QRXt9hvGTtpIBdeJN7FvDtjyAsINnQXAXd',NULL,'127.0.0.1','curl/8.18.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiM3pQbU9ieDluYThOeTRHd0I4M05tNWhEblJSUGNFSlN6eW1PUWsxQSI7czo2OiJsb2NhbGUiO3M6MjoiYm4iO3M6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1780649693),('X0IDWnM1hd2PYpCFM0jOI0aGdJ0ZZ3VF9dWxtYhS',NULL,'127.0.0.1','Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:151.0) Gecko/20100101 Firefox/151.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNFFUNnZ3eFVWT1BETXF2VFNDY1RvVEx3OGNFWm03R0I4bU9XUFc2NiI7czo2OiJsb2NhbGUiO3M6MjoiYm4iO3M6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjIxOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAiO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1780655497);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `type` enum('text','textarea','number','boolean','json','image') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`),
  KEY `settings_group_index` (`group`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'general','site_name','Garikothay','text','2026-06-05 02:46:12','2026-06-05 03:22:51'),(2,'general','site_tagline','Find trusted cars, bikes, and auto deals across Bangladesh.','text','2026-06-05 02:46:12','2026-06-05 03:22:51'),(3,'general','site_logo',NULL,'image','2026-06-05 02:46:12','2026-06-05 04:42:06'),(4,'general','site_favicon',NULL,'image','2026-06-05 02:46:12','2026-06-05 02:46:12'),(5,'general','free_shipping_threshold','1500','number','2026-06-05 02:46:12','2026-06-05 02:46:12'),(6,'contact','phone','+8801911223344','text','2026-06-05 02:46:12','2026-06-05 04:42:06'),(7,'contact','email','support@garikothay.com','text','2026-06-05 02:46:12','2026-06-05 03:22:51'),(8,'contact','address','House 24, Road 7, Banani, Dhaka 1213, Bangladesh','textarea','2026-06-05 02:46:12','2026-06-05 03:22:51'),(9,'contact','whatsapp','8801911223344','text','2026-06-05 02:46:12','2026-06-05 03:22:51'),(10,'social','facebook','https://facebook.com/garikothay','text','2026-06-05 02:46:12','2026-06-05 03:22:51'),(11,'social','instagram','https://instagram.com/garikothay','text','2026-06-05 02:46:13','2026-06-05 03:22:51'),(12,'social','youtube','https://youtube.com/@garikothay','text','2026-06-05 02:46:13','2026-06-05 03:22:51'),(13,'seo','meta_title','Garikothay - Trusted Cars, Bikes & Auto Deals in Bangladesh','text','2026-06-05 02:46:13','2026-06-05 03:22:51'),(14,'seo','meta_description','Browse verified cars, bikes, spare parts, and auto deals from trusted sellers across Bangladesh with Garikothay.','textarea','2026-06-05 02:46:13','2026-06-05 03:22:51'),(15,'seo','google_analytics_id','','text','2026-06-05 02:46:13','2026-06-05 02:46:13'),(16,'seo','facebook_pixel_id','','text','2026-06-05 02:46:13','2026-06-05 02:46:13'),(17,'payment','cod_enabled','1','boolean','2026-06-05 02:46:13','2026-06-05 02:46:13'),(18,'payment','sslcommerz_enabled','1','boolean','2026-06-05 02:46:13','2026-06-05 02:46:13'),(19,'payment','stripe_enabled','0','boolean','2026-06-05 02:46:13','2026-06-05 02:46:13'),(20,'checkout','guest_checkout_enabled','1','boolean','2026-06-05 04:37:00','2026-06-05 04:37:00');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
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
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `phone_verified_at` timestamp NULL DEFAULT NULL,
  `locale` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'bn',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_phone_unique` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wishlists`
--

DROP TABLE IF EXISTS `wishlists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wishlists` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wishlists_user_id_product_id_unique` (`user_id`,`product_id`),
  KEY `wishlists_product_id_foreign` (`product_id`),
  KEY `wishlists_user_id_index` (`user_id`),
  CONSTRAINT `wishlists_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wishlists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wishlists`
--

LOCK TABLES `wishlists` WRITE;
/*!40000 ALTER TABLE `wishlists` DISABLE KEYS */;
/*!40000 ALTER TABLE `wishlists` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-09 11:39:57
