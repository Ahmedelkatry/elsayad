-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 16, 2026 at 10:41 PM
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
-- Database: `sayad`
--

-- --------------------------------------------------------

--
-- Table structure for table `badel_transports`
--

CREATE TABLE `badel_transports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mony` int(11) DEFAULT NULL,
  `employees_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
('spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:97:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:16:\"الموظفين\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:19:\"اضافه موظف\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:19:\"تعديل موظف\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:15:\"حذف موظف\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:8:\"شيفت\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:19:\"اضافه شيفت\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:19:\"تعديل شيفت\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:15:\"حذف شيفت\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:19:\"تبديل شيفت\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:28:\"تبديل شيفت موظف\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:28:\"اضافه شيفت موظف\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:28:\"تعديل شيفت موظف\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:25:\"حذف  شيفت موظف\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:26:\"تبديل شيفت قسم\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:26:\"اضافه شيفت قسم\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:26:\"تعديل شيفت قسم\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:23:\"حذف  شيفت قسم\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:6:\"اذن\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:17:\"اضافه اذن\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:17:\"تعديل اذن\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:13:\"حذف اذن\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:38:\"اداره بصمات الموظفين\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:28:\"اضافه بصمه موظف\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:28:\"تعديل بصمه موظف\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:24:\"حذف بصمه موظف\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:25;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:17:\"طلب اجازه\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:26;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:21:\"اضافه اجازه\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:27;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:21:\"تعديل اجازه\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:28;a:4:{s:1:\"a\";i:29;s:1:\"b\";s:17:\"حذف اجازه\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:29;a:4:{s:1:\"a\";i:30;s:1:\"b\";s:14:\"الاقسام\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:30;a:4:{s:1:\"a\";i:31;s:1:\"b\";s:17:\"اضافه قسم\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:31;a:4:{s:1:\"a\";i:32;s:1:\"b\";s:17:\"تعديل قسم\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:32;a:4:{s:1:\"a\";i:33;s:1:\"b\";s:13:\"حذف قسم\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:33;a:4:{s:1:\"a\";i:34;s:1:\"b\";s:10:\"الخصم\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:34;a:4:{s:1:\"a\";i:35;s:1:\"b\";s:17:\"اضافه خصم\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:35;a:4:{s:1:\"a\";i:36;s:1:\"b\";s:17:\"تعديل خصم\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:36;a:4:{s:1:\"a\";i:37;s:1:\"b\";s:13:\"حذف خصم\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:37;a:4:{s:1:\"a\";i:38;s:1:\"b\";s:6:\"سلف\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:38;a:4:{s:1:\"a\";i:39;s:1:\"b\";s:17:\"اضافه سلف\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:39;a:4:{s:1:\"a\";i:40;s:1:\"b\";s:17:\"تعديل سلف\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:40;a:4:{s:1:\"a\";i:41;s:1:\"b\";s:13:\"حذف سلف\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:41;a:4:{s:1:\"a\";i:42;s:1:\"b\";s:14:\"التقرير\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:42;a:4:{s:1:\"a\";i:43;s:1:\"b\";s:19:\"تقرير موظف\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:43;a:4:{s:1:\"a\";i:44;s:1:\"b\";s:30:\"استيراد ملف موظف\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:44;a:4:{s:1:\"a\";i:45;s:1:\"b\";s:15:\"بحث موظف\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:45;a:4:{s:1:\"a\";i:46;s:1:\"b\";s:23:\"تقرير اجمالي\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:46;a:4:{s:1:\"a\";i:47;s:1:\"b\";s:23:\"تقرير بالقسم\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:47;a:4:{s:1:\"a\";i:48;s:1:\"b\";s:28:\"استيراد ملف قسم\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:48;a:4:{s:1:\"a\";i:49;s:1:\"b\";s:17:\"بحث القسم\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:49;a:4:{s:1:\"a\";i:50;s:1:\"b\";s:33:\"اعدادات المستخدين\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:50;a:4:{s:1:\"a\";i:51;s:1:\"b\";s:20:\"المستخدمين\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:51;a:4:{s:1:\"a\";i:52;s:1:\"b\";s:23:\"اضافه مستخدم\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:52;a:4:{s:1:\"a\";i:53;s:1:\"b\";s:23:\"تعديل مستخدم\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:53;a:4:{s:1:\"a\";i:54;s:1:\"b\";s:19:\"حذف مستخدم\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:54;a:4:{s:1:\"a\";i:55;s:1:\"b\";s:18:\"الصلاحيات\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:55;a:4:{s:1:\"a\";i:56;s:1:\"b\";s:19:\"عرض صلاحية\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:56;a:4:{s:1:\"a\";i:57;s:1:\"b\";s:23:\"اضافة صلاحية\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:57;a:4:{s:1:\"a\";i:58;s:1:\"b\";s:23:\"تعديل صلاحية\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:58;a:4:{s:1:\"a\";i:59;s:1:\"b\";s:19:\"حذف صلاحية\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:59;a:4:{s:1:\"a\";i:60;s:1:\"b\";s:36:\"سحب السجلات الهواتف\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:60;a:4:{s:1:\"a\";i:61;s:1:\"b\";s:17:\"IP-الاتصال\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:61;a:4:{s:1:\"a\";i:62;s:1:\"b\";s:41:\"سحب الموظفين من البصمه\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:62;a:4:{s:1:\"a\";i:63;s:1:\"b\";s:8:\"حافز\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:63;a:4:{s:1:\"a\";i:64;s:1:\"b\";s:19:\"اضافه حافز\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:64;a:4:{s:1:\"a\";i:65;s:1:\"b\";s:19:\"تعديل حافز\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:65;a:4:{s:1:\"a\";i:66;s:1:\"b\";s:15:\"حذف حافز\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:66;a:4:{s:1:\"a\";i:67;s:1:\"b\";s:23:\"شؤن الموظفين\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:67;a:4:{s:1:\"a\";i:68;s:1:\"b\";s:6:\"قرض\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:68;a:4:{s:1:\"a\";i:69;s:1:\"b\";s:17:\"اضافه قرض\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:69;a:4:{s:1:\"a\";i:70;s:1:\"b\";s:17:\"تعديل قرض\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:70;a:4:{s:1:\"a\";i:71;s:1:\"b\";s:13:\"حذف قرض\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:71;a:4:{s:1:\"a\";i:72;s:1:\"b\";s:21:\"خصم مواصلات\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:72;a:4:{s:1:\"a\";i:73;s:1:\"b\";s:28:\"حذف خصم مواصلات\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:73;a:4:{s:1:\"a\";i:74;s:1:\"b\";s:32:\"تعديل خصم مواصلات\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:74;a:4:{s:1:\"a\";i:75;s:1:\"b\";s:32:\"اضافه خصم مواصلات\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:75;a:4:{s:1:\"a\";i:76;s:1:\"b\";s:21:\"بدل مواصلات\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:76;a:4:{s:1:\"a\";i:77;s:1:\"b\";s:28:\"حذف بدل مواصلات\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:77;a:4:{s:1:\"a\";i:78;s:1:\"b\";s:32:\"تعديل بدل مواصلات\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:78;a:4:{s:1:\"a\";i:79;s:1:\"b\";s:32:\"اضافه بدل مواصلات\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:79;a:4:{s:1:\"a\";i:80;s:1:\"b\";s:18:\"التامينات\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:80;a:4:{s:1:\"a\";i:81;s:1:\"b\";s:25:\"اضافه تامينات\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:81;a:4:{s:1:\"a\";i:82;s:1:\"b\";s:21:\"حذف تامينات\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:82;a:4:{s:1:\"a\";i:83;s:1:\"b\";s:25:\"تعديل تامينات\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:83;a:4:{s:1:\"a\";i:84;s:1:\"b\";s:18:\"المشتريات\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:84;a:4:{s:1:\"a\";i:85;s:1:\"b\";s:25:\"اضافه مشتريات\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:85;a:4:{s:1:\"a\";i:86;s:1:\"b\";s:21:\"حذف مشتريات\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:86;a:4:{s:1:\"a\";i:87;s:1:\"b\";s:25:\"تعديل مشتريات\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:87;a:4:{s:1:\"a\";i:88;s:1:\"b\";s:19:\"ايام العيد\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:88;a:4:{s:1:\"a\";i:89;s:1:\"b\";s:17:\"اضافه عيد\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:89;a:4:{s:1:\"a\";i:90;s:1:\"b\";s:17:\"تعديل عيد\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:90;a:4:{s:1:\"a\";i:91;s:1:\"b\";s:13:\"حذف عيد\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:91;a:4:{s:1:\"a\";i:92;s:1:\"b\";s:19:\"عديه العيد\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:92;a:4:{s:1:\"a\";i:93;s:1:\"b\";s:30:\"اضافه عديه العيد\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:93;a:4:{s:1:\"a\";i:94;s:1:\"b\";s:30:\"تعديل عديه العيد\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:94;a:4:{s:1:\"a\";i:95;s:1:\"b\";s:26:\"حذف عديه العيد\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:95;a:4:{s:1:\"a\";i:96;s:1:\"b\";s:38:\"طلبات الموظفين ايميل\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:96;a:4:{s:1:\"a\";i:97;s:1:\"b\";s:36:\"اداره الوقت الاضافي\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}}s:5:\"roles\";a:1:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:5:\"owner\";s:1:\"c\";s:3:\"web\";}}}', 1792191354);

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
-- Table structure for table `central_admins`
--

CREATE TABLE `central_admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `central_admin_role_id` bigint(20) UNSIGNED NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `central_admins`
--

INSERT INTO `central_admins` (`id`, `name`, `email`, `password`, `central_admin_role_id`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'مدير النظام', 'katry@hr-saas.com', '$2y$12$MCb9.Ok64rKTa4gmpPw3YOTs1N8iBLdeJe4Y09fuwxSCpUGUjE8AS', 1, 1, NULL, '2026-06-15 20:43:11', '2026-06-15 21:37:30');

-- --------------------------------------------------------

--
-- Table structure for table `central_admin_roles`
--

CREATE TABLE `central_admin_roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`permissions`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `central_admin_roles`
--

INSERT INTO `central_admin_roles` (`id`, `slug`, `name`, `permissions`, `created_at`, `updated_at`) VALUES
(1, 'super_admin', 'مدير النظام', '[\"*\"]', '2026-06-15 20:43:11', '2026-06-15 20:43:11'),
(2, 'manager', 'مدير', '[\"tenants.view\",\"tenants.create\",\"tenants.edit\",\"tenants.toggle\",\"plans.view\",\"plans.edit\"]', '2026-06-15 20:43:11', '2026-06-15 20:43:11'),
(3, 'support', 'دعم فني', '[\"tenants.view\",\"tenants.edit\",\"tenants.toggle\",\"plans.view\"]', '2026-06-15 20:43:11', '2026-06-15 20:43:11'),
(4, 'viewer', 'مشاهدة فقط', '[\"tenants.view\",\"plans.view\"]', '2026-06-15 20:43:11', '2026-06-15 20:43:11');

-- --------------------------------------------------------

--
-- Table structure for table `change_shifts`
--

CREATE TABLE `change_shifts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `shift_id` bigint(20) UNSIGNED NOT NULL,
  `employees_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `company_locations`
--

CREATE TABLE `company_locations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `radius` int(11) NOT NULL DEFAULT 100,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `countips`
--

CREATE TABLE `countips` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `numid` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `shift` varchar(255) DEFAULT NULL,
  `in_duty` time DEFAULT NULL,
  `out_duty` time DEFAULT NULL,
  `date` date DEFAULT NULL,
  `absent` int(11) DEFAULT NULL,
  `friday` int(11) DEFAULT NULL,
  `in` time DEFAULT NULL,
  `out` time DEFAULT NULL,
  `ot` time DEFAULT NULL,
  `othours` double DEFAULT NULL,
  `dayh` int(11) DEFAULT NULL,
  `early` time DEFAULT NULL,
  `dis` double DEFAULT NULL,
  `disout` double DEFAULT NULL,
  `kabdd` double DEFAULT NULL,
  `kabddis` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `counts`
--

CREATE TABLE `counts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `numid` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `shift` varchar(255) DEFAULT NULL,
  `in_duty` time DEFAULT NULL,
  `out_duty` time DEFAULT NULL,
  `date` date DEFAULT NULL,
  `absent` int(11) DEFAULT NULL,
  `absent_dis` double DEFAULT NULL,
  `friday` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL,
  `in` time DEFAULT NULL,
  `out` time DEFAULT NULL,
  `dis_loan` decimal(8,2) DEFAULT NULL,
  `break_in` time DEFAULT NULL,
  `break_out` time DEFAULT NULL,
  `ot` time DEFAULT NULL,
  `othours` double DEFAULT NULL,
  `count_ot` double DEFAULT NULL,
  `dayh` int(11) DEFAULT NULL,
  `early` time DEFAULT NULL,
  `dis` double DEFAULT NULL,
  `disout` double DEFAULT NULL,
  `kabdd` double DEFAULT NULL,
  `kabddis` double DEFAULT NULL,
  `check_loction` int(11) DEFAULT NULL,
  `shift_id` bigint(20) UNSIGNED NOT NULL,
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `employees_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `latitude_in` decimal(10,8) DEFAULT NULL,
  `longitude_in` decimal(11,8) DEFAULT NULL,
  `latitude_out` decimal(10,8) DEFAULT NULL,
  `longitude_out` decimal(11,8) DEFAULT NULL,
  `latitude_break_in` decimal(10,8) DEFAULT NULL,
  `longitude_break_in` decimal(11,8) DEFAULT NULL,
  `latitude_break_out` decimal(10,8) DEFAULT NULL,
  `longitude_break_out` decimal(11,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `counts`
--

INSERT INTO `counts` (`id`, `numid`, `name`, `shift`, `in_duty`, `out_duty`, `date`, `absent`, `absent_dis`, `friday`, `eid`, `in`, `out`, `dis_loan`, `break_in`, `break_out`, `ot`, `othours`, `count_ot`, `dayh`, `early`, `dis`, `disout`, `kabdd`, `kabddis`, `check_loction`, `shift_id`, `section_id`, `employees_id`, `created_at`, `updated_at`, `latitude_in`, `longitude_in`, `latitude_out`, `longitude_out`, `latitude_break_in`, `longitude_break_in`, `latitude_break_out`, `longitude_break_out`) VALUES
(1, 1, 'gamal', NULL, '10:00:00', '19:00:00', '2026-10-01', 1, 40.73, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 1222, NULL, NULL, 7, 4, 32, '2026-10-16 18:15:34', '2026-10-16 18:15:34', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 1, 'gamal', NULL, '10:00:00', '19:00:00', '2026-10-02', 1, 40.73, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 1222, NULL, NULL, 7, 4, 32, '2026-10-16 18:15:34', '2026-10-16 18:15:34', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 1, 'gamal', NULL, '10:00:00', '19:00:00', '2026-10-03', 1, 40.73, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 1222, NULL, NULL, 7, 4, 32, '2026-10-16 18:15:34', '2026-10-16 18:15:34', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 1, 'gamal', NULL, '10:00:00', '19:00:00', '2026-10-04', 1, 40.73, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 1222, NULL, NULL, 7, 4, 32, '2026-10-16 18:15:34', '2026-10-16 18:15:34', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 1, 'gamal', NULL, '10:00:00', '19:00:00', '2026-10-05', 1, 40.73, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 1222, NULL, NULL, 7, 4, 32, '2026-10-16 18:15:34', '2026-10-16 18:15:34', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 1, 'gamal', NULL, '10:00:00', '19:00:00', '2026-10-06', 1, 40.73, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 1222, NULL, NULL, 7, 4, 32, '2026-10-16 18:15:34', '2026-10-16 18:15:34', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 1, 'gamal', NULL, '10:00:00', '19:00:00', '2026-10-07', 1, 40.73, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 1222, NULL, NULL, 7, 4, 32, '2026-10-16 18:15:34', '2026-10-16 18:15:34', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 1, 'gamal', NULL, '10:00:00', '19:00:00', '2026-10-08', 2, NULL, NULL, NULL, '15:11:29', '15:11:35', NULL, NULL, NULL, NULL, NULL, NULL, 6, '05:11:29', 22.63, 0, 1222, NULL, NULL, 7, 4, 32, '2026-10-16 18:15:34', '2026-10-16 18:15:34', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 1, 'gamal', NULL, '10:00:00', '19:00:00', '2026-10-09', 1, 40.73, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 1222, NULL, NULL, 7, 4, 32, '2026-10-16 18:15:34', '2026-10-16 18:15:34', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 1, 'gamal', NULL, '10:00:00', '19:00:00', '2026-10-10', 1, 40.73, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 1222, NULL, NULL, 7, 4, 32, '2026-10-16 18:15:34', '2026-10-16 18:15:34', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, 1, 'gamal', NULL, '10:00:00', '19:00:00', '2026-10-11', 1, 40.73, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 1222, NULL, NULL, 7, 4, 32, '2026-10-16 18:15:34', '2026-10-16 18:15:34', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 1, 'gamal', NULL, '10:00:00', '19:00:00', '2026-10-12', 1, 40.73, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 1222, NULL, NULL, 7, 4, 32, '2026-10-16 18:15:34', '2026-10-16 18:15:34', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(13, 1, 'gamal', NULL, '10:00:00', '19:00:00', '2026-10-13', 1, 40.73, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 1222, NULL, NULL, 7, 4, 32, '2026-10-16 18:15:34', '2026-10-16 18:15:34', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(14, 1, 'gamal', NULL, '10:00:00', '19:00:00', '2026-10-14', 1, 40.73, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 1222, NULL, NULL, 7, 4, 32, '2026-10-16 18:15:34', '2026-10-16 18:15:34', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(15, 1, 'gamal', NULL, '10:00:00', '19:00:00', '2026-10-15', 2, NULL, NULL, NULL, '20:10:57', '20:12:58', NULL, NULL, NULL, '20:12:58', 4378, NULL, 121, '10:10:57', 22.63, NULL, 1222, NULL, NULL, 7, 4, 32, '2026-10-16 18:15:34', '2026-10-16 18:15:34', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(16, 1, 'gamal', NULL, '10:00:00', '19:00:00', '2026-10-16', 2, NULL, NULL, NULL, '19:07:00', '19:15:05', NULL, NULL, NULL, '19:15:05', 905, NULL, 485, '09:07:00', 22.63, NULL, 1222, NULL, NULL, 7, 4, 32, '2026-10-16 18:15:34', '2026-10-16 18:15:34', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `counts_emps`
--

CREATE TABLE `counts_emps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `numid` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `shift` varchar(255) DEFAULT NULL,
  `in_duty` time DEFAULT NULL,
  `out_duty` time DEFAULT NULL,
  `date` date DEFAULT NULL,
  `absent` int(11) DEFAULT NULL,
  `absent_dis` double DEFAULT NULL,
  `friday` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL,
  `in` time DEFAULT NULL,
  `out` time DEFAULT NULL,
  `dis_loan` decimal(8,2) DEFAULT NULL,
  `break_in` time DEFAULT NULL,
  `break_out` time DEFAULT NULL,
  `ot` time DEFAULT NULL,
  `othours` double DEFAULT NULL,
  `count_ot` double DEFAULT NULL,
  `dayh` int(11) DEFAULT NULL,
  `early` time DEFAULT NULL,
  `dis` double DEFAULT NULL,
  `disout` double DEFAULT NULL,
  `kabdd` double DEFAULT NULL,
  `kabddis` double DEFAULT NULL,
  `shift_id` int(11) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `employees_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `counts_emps`
--

INSERT INTO `counts_emps` (`id`, `numid`, `name`, `shift`, `in_duty`, `out_duty`, `date`, `absent`, `absent_dis`, `friday`, `eid`, `in`, `out`, `dis_loan`, `break_in`, `break_out`, `ot`, `othours`, `count_ot`, `dayh`, `early`, `dis`, `disout`, `kabdd`, `kabddis`, `shift_id`, `section_id`, `employees_id`, `created_at`, `updated_at`) VALUES
(1, 9, 'ياسر مكي', NULL, NULL, NULL, '2026-04-01', 2, NULL, NULL, NULL, '13:54:55', '18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 14705, '03:54:55', 130.51, NULL, 8000, NULL, 5, 2, 10, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(2, 9, 'ياسر مكي', NULL, NULL, NULL, '2026-04-02', 2, NULL, NULL, NULL, '14:15:00', '21:25:20', NULL, NULL, NULL, NULL, NULL, 0, 25820, '00:49:40', 27.59, NULL, 8000, NULL, 5, 2, 10, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(3, 9, 'ياسر مكي', NULL, NULL, NULL, '2026-04-03', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 8000, NULL, 5, 2, 10, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(4, 9, 'ياسر مكي', NULL, NULL, NULL, '2026-04-04', 2, NULL, NULL, NULL, '14:07:12', '21:23:24', NULL, NULL, NULL, NULL, NULL, NULL, 26172, '00:43:48', 24.33, NULL, 8000, NULL, 5, 2, 10, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(5, 9, 'ياسر مكي', NULL, NULL, NULL, '2026-04-05', 2, NULL, NULL, NULL, '14:05:05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 8000, NULL, 5, 2, 10, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(6, 9, 'ياسر مكي', NULL, NULL, NULL, '2026-04-06', 2, NULL, NULL, NULL, '14:04:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 8000, NULL, 5, 2, 10, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(7, 9, 'ياسر مكي', NULL, NULL, NULL, '2026-04-07', 2, NULL, NULL, NULL, '13:46:17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 8000, NULL, 5, 2, 10, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(8, 9, 'ياسر مكي', NULL, NULL, NULL, '2026-04-08', 2, NULL, NULL, NULL, '13:17:52', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 8000, NULL, 5, 2, 10, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(9, 9, 'ياسر مكي', NULL, NULL, NULL, '2026-04-09', 2, NULL, NULL, NULL, '14:16:59', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 8000, NULL, 5, 2, 10, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(10, 9, 'ياسر مكي', NULL, NULL, NULL, '2026-04-10', 0, 0, 1, NULL, '11:54:08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 8000, NULL, 5, 2, 10, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(11, 9, 'ياسر مكي', NULL, '14:15:00', '22:00:00', '2026-04-11', 2, NULL, NULL, NULL, '11:08:28', '19:33:33', NULL, NULL, NULL, NULL, NULL, NULL, 30305, NULL, NULL, 0, 8000, NULL, 3, 2, 10, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(12, 9, 'ياسر مكي', NULL, NULL, NULL, '2026-04-12', 2, NULL, NULL, NULL, '11:13:52', '19:36:07', NULL, NULL, NULL, '19:36:07', 1335, NULL, 30135, NULL, NULL, NULL, 8000, NULL, 5, 2, 10, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(13, 9, 'ياسر مكي', NULL, NULL, NULL, '2026-04-13', 1, 266.67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 8000, NULL, 5, 2, 10, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(14, 9, 'ياسر مكي', NULL, NULL, NULL, '2026-04-14', 2, NULL, NULL, NULL, '11:08:31', '20:32:23', NULL, NULL, NULL, '20:32:23', 5032, NULL, 33832, NULL, NULL, NULL, 8000, NULL, 5, 2, 10, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(15, 9, 'ياسر مكي', NULL, NULL, NULL, '2026-04-15', 2, NULL, NULL, NULL, '11:15:19', '19:02:47', NULL, NULL, NULL, NULL, NULL, NULL, 28048, '00:12:32', 6.96, NULL, 8000, NULL, 5, 2, 10, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(16, 9, 'ياسر مكي', NULL, NULL, NULL, '2026-04-16', 2, NULL, NULL, NULL, '11:17:52', '20:58:00', NULL, NULL, NULL, '20:58:00', 6008, NULL, 34808, NULL, NULL, NULL, 8000, NULL, 5, 2, 10, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(17, 9, 'ياسر مكي', NULL, NULL, NULL, '2026-04-17', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 8000, NULL, 5, 2, 10, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(18, 9, 'ياسر مكي', NULL, NULL, NULL, '2026-04-18', 2, NULL, NULL, NULL, '11:11:42', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 8000, NULL, 5, 2, 10, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(19, 9, 'ياسر مكي', NULL, NULL, NULL, '2026-04-19', 2, NULL, NULL, NULL, '11:11:26', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 8000, NULL, 5, 2, 10, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(20, 9, 'ياسر مكي', NULL, NULL, NULL, '2026-04-20', 1, 266.67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 8000, NULL, 5, 2, 10, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(21, 9, 'ياسر مكي', NULL, NULL, NULL, '2026-04-21', 1, 266.67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 8000, NULL, 5, 2, 10, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(22, 9, 'ياسر مكي', NULL, NULL, NULL, '2026-04-22', 1, 266.67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 8000, NULL, 5, 2, 10, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(23, 9, 'ياسر مكي', NULL, NULL, NULL, '2026-04-23', 1, 266.67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 8000, NULL, 5, 2, 10, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(24, 9, 'ياسر مكي', NULL, NULL, NULL, '2026-04-24', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 8000, NULL, 5, 2, 10, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(25, 9, 'ياسر مكي', NULL, NULL, NULL, '2026-04-25', 1, 266.67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 8000, NULL, 5, 2, 10, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(26, 9, 'ياسر مكي', NULL, NULL, NULL, '2026-04-26', 1, 266.67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 8000, NULL, 5, 2, 10, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(27, 9, 'ياسر مكي', NULL, NULL, NULL, '2026-04-27', 1, 266.67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 8000, NULL, 5, 2, 10, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(28, 9, 'ياسر مكي', NULL, NULL, NULL, '2026-04-28', 1, 266.67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 8000, NULL, 5, 2, 10, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(29, 9, 'ياسر مكي', NULL, NULL, NULL, '2026-04-29', 1, 266.67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 8000, NULL, 5, 2, 10, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(30, 9, 'ياسر مكي', NULL, NULL, NULL, '2026-04-30', 1, 266.67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 8000, NULL, 5, 2, 10, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(31, 8, 'عمرو نعيم', NULL, '11:15:00', '19:15:00', '2026-04-01', 2, NULL, NULL, NULL, '11:32:55', '19:19:14', NULL, NULL, NULL, '19:19:14', 254, NULL, 27979, '00:17:55', 14.93, NULL, 6000, NULL, 1, 1, 9, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(32, 8, 'عمرو نعيم', NULL, '11:15:00', '19:15:00', '2026-04-02', 2, NULL, NULL, NULL, '11:31:22', '20:59:58', NULL, NULL, NULL, '20:59:58', 6298, NULL, 34116, '00:16:22', 13.64, NULL, 6000, NULL, 1, 1, 9, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(33, 8, 'عمرو نعيم', NULL, '11:15:00', '19:15:00', '2026-04-03', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6000, NULL, 1, 1, 9, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(34, 8, 'عمرو نعيم', NULL, '11:15:00', '19:15:00', '2026-04-04', 2, NULL, NULL, NULL, '11:21:21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6000, NULL, 1, 1, 9, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(35, 8, 'عمرو نعيم', NULL, '11:15:00', '19:15:00', '2026-04-05', 2, NULL, NULL, NULL, '11:31:22', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6000, NULL, 1, 1, 9, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(36, 8, 'عمرو نعيم', NULL, '11:15:00', '19:15:00', '2026-04-06', 2, NULL, NULL, NULL, '11:04:07', '19:17:24', NULL, NULL, NULL, '19:17:24', 144, NULL, 29597, NULL, NULL, NULL, 6000, NULL, 1, 1, 9, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(37, 8, 'عمرو نعيم', NULL, '11:15:00', '19:15:00', '2026-04-07', 2, NULL, NULL, NULL, '11:23:28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6000, NULL, 1, 1, 9, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(38, 8, 'عمرو نعيم', NULL, '11:15:00', '19:15:00', '2026-04-08', 1, 200, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6000, NULL, 1, 1, 9, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(39, 8, 'عمرو نعيم', NULL, '11:15:00', '19:15:00', '2026-04-09', 2, NULL, NULL, NULL, '11:19:10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6000, NULL, 1, 1, 9, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(40, 8, 'عمرو نعيم', NULL, '11:15:00', '19:15:00', '2026-04-10', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6000, NULL, 1, 1, 9, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(41, 8, 'عمرو نعيم', NULL, '11:15:00', '19:15:00', '2026-04-11', 2, NULL, NULL, NULL, '11:35:31', '19:02:47', NULL, NULL, NULL, NULL, NULL, NULL, 26836, '00:20:31', 17.1, 5.09, 6000, NULL, 1, 1, 9, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(42, 8, 'عمرو نعيم', NULL, '11:15:00', '19:15:00', '2026-04-12', 2, NULL, NULL, NULL, '11:26:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6000, NULL, 1, 1, 9, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(43, 8, 'عمرو نعيم', NULL, '11:15:00', '19:15:00', '2026-04-13', 1, 200, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6000, NULL, 1, 1, 9, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(44, 8, 'عمرو نعيم', NULL, '11:15:00', '19:15:00', '2026-04-14', 2, NULL, NULL, NULL, '11:35:21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6000, NULL, 1, 1, 9, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(45, 8, 'عمرو نعيم', NULL, '11:15:00', '19:15:00', '2026-04-15', 2, NULL, NULL, NULL, '11:25:04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6000, NULL, 1, 1, 9, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(46, 8, 'عمرو نعيم', NULL, '11:15:00', '19:15:00', '2026-04-16', 2, NULL, NULL, NULL, '11:23:10', '19:45:09', NULL, NULL, NULL, '19:45:09', 1809, NULL, 30119, NULL, NULL, NULL, 6000, NULL, 1, 1, 9, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(47, 8, 'عمرو نعيم', NULL, '11:15:00', '19:15:00', '2026-04-17', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6000, NULL, 1, 1, 9, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(48, 8, 'عمرو نعيم', NULL, '11:15:00', '19:15:00', '2026-04-18', 2, NULL, NULL, NULL, '11:24:00', '19:25:18', NULL, NULL, NULL, '19:25:18', 618, NULL, 28878, NULL, NULL, NULL, 6000, NULL, 1, 1, 9, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(49, 8, 'عمرو نعيم', NULL, '11:15:00', '19:15:00', '2026-04-19', 2, NULL, NULL, NULL, '11:28:09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6000, NULL, 1, 1, 9, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(50, 8, 'عمرو نعيم', NULL, '11:15:00', '19:15:00', '2026-04-20', 1, 200, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6000, NULL, 1, 1, 9, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(51, 8, 'عمرو نعيم', NULL, '11:15:00', '19:15:00', '2026-04-21', 1, 200, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6000, NULL, 1, 1, 9, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(52, 8, 'عمرو نعيم', NULL, '11:15:00', '19:15:00', '2026-04-22', 1, 200, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6000, NULL, 1, 1, 9, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(53, 8, 'عمرو نعيم', NULL, '11:15:00', '19:15:00', '2026-04-23', 1, 200, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6000, NULL, 1, 1, 9, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(54, 8, 'عمرو نعيم', NULL, '11:15:00', '19:15:00', '2026-04-24', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6000, NULL, 1, 1, 9, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(55, 8, 'عمرو نعيم', NULL, '11:15:00', '19:15:00', '2026-04-25', 1, 200, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6000, NULL, 1, 1, 9, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(56, 8, 'عمرو نعيم', NULL, '11:15:00', '19:15:00', '2026-04-26', 1, 200, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6000, NULL, 1, 1, 9, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(57, 8, 'عمرو نعيم', NULL, '11:15:00', '19:15:00', '2026-04-27', 1, 200, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6000, NULL, 1, 1, 9, '2026-05-09 16:55:18', '2026-05-09 16:55:18'),
(58, 8, 'عمرو نعيم', NULL, '11:15:00', '19:15:00', '2026-04-28', 1, 200, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6000, NULL, 1, 1, 9, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(59, 8, 'عمرو نعيم', NULL, '11:15:00', '19:15:00', '2026-04-29', 1, 200, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6000, NULL, 1, 1, 9, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(60, 8, 'عمرو نعيم', NULL, '11:15:00', '19:15:00', '2026-04-30', 2, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, 0, 6000, NULL, 1, 1, 9, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(61, 6, 'محمد وليد', NULL, '11:15:00', '19:15:00', '2026-04-01', 2, NULL, NULL, NULL, '19:04:46', '19:04:57', NULL, NULL, NULL, NULL, NULL, NULL, 11, '07:49:46', 489.34, 3.49, 5000, NULL, 1, 1, 13, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(62, 6, 'محمد وليد', NULL, '11:15:00', '19:15:00', '2026-04-02', 2, NULL, NULL, NULL, '11:30:04', '18:51:57', NULL, NULL, NULL, NULL, NULL, NULL, 26513, '00:15:04', 10.46, 8, 5000, NULL, 1, 1, 13, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(63, 6, 'محمد وليد', NULL, '11:15:00', '19:15:00', '2026-04-03', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 5000, NULL, 1, 1, 13, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(64, 6, 'محمد وليد', NULL, '11:15:00', '19:15:00', '2026-04-04', 2, NULL, NULL, NULL, '18:39:26', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 5000, NULL, 1, 1, 13, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(65, 6, 'محمد وليد', NULL, '11:15:00', '19:15:00', '2026-04-05', 2, NULL, NULL, NULL, '11:40:04', '19:04:10', NULL, NULL, NULL, NULL, NULL, NULL, 26646, '00:25:04', 17.41, 3.76, 5000, NULL, 1, 1, 13, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(66, 6, 'محمد وليد', NULL, '11:15:00', '19:15:00', '2026-04-06', 2, NULL, NULL, NULL, '11:30:05', '19:25:44', NULL, NULL, NULL, '19:25:44', 644, NULL, 28539, '00:15:05', 10.47, NULL, 5000, NULL, 1, 1, 13, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(67, 6, 'محمد وليد', NULL, '11:15:00', '19:15:00', '2026-04-07', 1, 166.67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 5000, NULL, 1, 1, 13, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(68, 6, 'محمد وليد', NULL, '11:15:00', '19:15:00', '2026-04-08', 1, 166.67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 5000, NULL, 1, 1, 13, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(69, 6, 'محمد وليد', NULL, '11:15:00', '19:15:00', '2026-04-09', 2, NULL, NULL, NULL, '11:34:28', '19:32:05', NULL, NULL, NULL, '19:32:05', 1025, NULL, 28657, '00:19:28', 13.52, NULL, 5000, NULL, 1, 1, 13, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(70, 6, 'محمد وليد', NULL, '11:15:00', '19:15:00', '2026-04-10', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 5000, NULL, 1, 1, 13, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(71, 6, 'محمد وليد', NULL, '11:15:00', '19:15:00', '2026-04-11', 2, NULL, NULL, NULL, '18:59:11', '18:59:28', NULL, NULL, NULL, NULL, NULL, NULL, 17, '07:44:11', 483.52, 5.39, 5000, NULL, 1, 1, 13, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(72, 6, 'محمد وليد', NULL, '11:15:00', '19:15:00', '2026-04-12', 2, NULL, NULL, NULL, '11:22:39', '20:07:21', NULL, NULL, NULL, '20:07:21', 3141, NULL, 31482, NULL, NULL, NULL, 5000, NULL, 1, 1, 13, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(73, 6, 'محمد وليد', NULL, '11:15:00', '19:15:00', '2026-04-13', 1, 166.67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 5000, NULL, 1, 1, 13, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(74, 6, 'محمد وليد', NULL, '11:15:00', '19:15:00', '2026-04-14', 2, NULL, NULL, NULL, '11:17:33', '21:16:54', NULL, NULL, NULL, '21:16:54', 7314, NULL, 35961, NULL, NULL, NULL, 5000, NULL, 1, 1, 13, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(75, 6, 'محمد وليد', NULL, '11:15:00', '19:15:00', '2026-04-15', 2, NULL, NULL, NULL, '11:34:41', '20:18:49', NULL, NULL, NULL, '20:18:49', 3829, NULL, 31448, '00:19:41', 13.67, NULL, 5000, NULL, 1, 1, 13, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(76, 6, 'محمد وليد', NULL, '11:15:00', '19:15:00', '2026-04-16', 1, 166.67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 5000, NULL, 1, 1, 13, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(77, 6, 'محمد وليد', NULL, '11:15:00', '19:15:00', '2026-04-17', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 5000, NULL, 1, 1, 13, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(78, 6, 'محمد وليد', NULL, '11:15:00', '19:15:00', '2026-04-18', 2, NULL, NULL, NULL, '11:36:08', '20:07:22', NULL, NULL, NULL, '20:07:22', 3142, NULL, 30674, '00:21:08', 14.68, NULL, 5000, NULL, 1, 1, 13, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(79, 6, 'محمد وليد', NULL, '11:15:00', '19:15:00', '2026-04-19', 2, NULL, NULL, NULL, '11:18:59', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 5000, NULL, 1, 1, 13, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(80, 6, 'محمد وليد', NULL, '11:15:00', '19:15:00', '2026-04-20', 1, 166.67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 5000, NULL, 1, 1, 13, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(81, 6, 'محمد وليد', NULL, '11:15:00', '19:15:00', '2026-04-21', 1, 166.67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 5000, NULL, 1, 1, 13, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(82, 6, 'محمد وليد', NULL, '11:15:00', '19:15:00', '2026-04-22', 1, 166.67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 5000, NULL, 1, 1, 13, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(83, 6, 'محمد وليد', NULL, '11:15:00', '19:15:00', '2026-04-23', 1, 166.67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 5000, NULL, 1, 1, 13, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(84, 6, 'محمد وليد', NULL, '11:15:00', '19:15:00', '2026-04-24', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 5000, NULL, 1, 1, 13, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(85, 6, 'محمد وليد', NULL, '11:15:00', '19:15:00', '2026-04-25', 1, 166.67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 5000, NULL, 1, 1, 13, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(86, 6, 'محمد وليد', NULL, '11:15:00', '19:15:00', '2026-04-26', 1, 166.67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 5000, NULL, 1, 1, 13, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(87, 6, 'محمد وليد', NULL, '11:15:00', '19:15:00', '2026-04-27', 1, 166.67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 5000, NULL, 1, 1, 13, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(88, 6, 'محمد وليد', NULL, '11:15:00', '19:15:00', '2026-04-28', 1, 166.67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 5000, NULL, 1, 1, 13, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(89, 6, 'محمد وليد', NULL, '11:15:00', '19:15:00', '2026-04-29', 1, 166.67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 5000, NULL, 1, 1, 13, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(90, 6, 'محمد وليد', NULL, '11:15:00', '19:15:00', '2026-04-30', 1, 166.67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 5000, NULL, 1, 1, 13, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(91, 5, 'عبد الرحمن  عز', NULL, '11:15:00', '19:15:00', '2026-04-01', 2, NULL, NULL, NULL, '11:11:18', '20:38:11', NULL, NULL, NULL, '20:38:11', 4991, NULL, 34013, NULL, NULL, NULL, 6600, NULL, 1, 1, 6, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(92, 5, 'عبد الرحمن  عز', NULL, '11:15:00', '19:15:00', '2026-04-02', 2, NULL, NULL, NULL, '11:20:42', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 6, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(93, 5, 'عبد الرحمن  عز', NULL, '11:15:00', '19:15:00', '2026-04-03', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 6, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(94, 5, 'عبد الرحمن  عز', NULL, '11:15:00', '19:15:00', '2026-04-04', 2, NULL, NULL, NULL, '11:06:21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 6, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(95, 5, 'عبد الرحمن  عز', NULL, '11:15:00', '19:15:00', '2026-04-05', 2, NULL, NULL, NULL, '11:18:17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 6, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(96, 5, 'عبد الرحمن  عز', NULL, '11:15:00', '19:15:00', '2026-04-06', 2, NULL, NULL, NULL, '11:01:25', '19:59:59', NULL, NULL, NULL, '19:59:59', 2699, NULL, 32314, NULL, NULL, NULL, 6600, NULL, 1, 1, 6, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(97, 5, 'عبد الرحمن  عز', NULL, '11:15:00', '19:15:00', '2026-04-07', 2, NULL, NULL, NULL, '10:45:50', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 6, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(98, 5, 'عبد الرحمن  عز', NULL, '11:15:00', '19:15:00', '2026-04-08', 2, NULL, NULL, NULL, '11:10:43', '21:02:53', NULL, NULL, NULL, '21:02:53', 6473, NULL, 35530, NULL, NULL, NULL, 6600, NULL, 1, 1, 6, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(99, 5, 'عبد الرحمن  عز', NULL, '11:15:00', '19:15:00', '2026-04-09', 2, NULL, NULL, NULL, '10:44:02', '20:56:02', NULL, NULL, NULL, '20:56:02', 6062, NULL, 36720, NULL, NULL, NULL, 6600, NULL, 1, 1, 6, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(100, 5, 'عبد الرحمن  عز', NULL, '11:15:00', '19:15:00', '2026-04-10', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 6, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(101, 5, 'عبد الرحمن  عز', NULL, '11:15:00', '19:15:00', '2026-04-11', 2, NULL, NULL, NULL, '10:53:10', '19:44:11', NULL, NULL, NULL, '19:44:11', 1751, NULL, 31861, NULL, NULL, NULL, 6600, NULL, 1, 1, 6, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(102, 5, 'عبد الرحمن  عز', NULL, '11:15:00', '19:15:00', '2026-04-12', 2, NULL, NULL, NULL, '10:45:50', '20:23:27', NULL, NULL, NULL, '20:23:27', 4107, NULL, 34657, NULL, NULL, NULL, 6600, NULL, 1, 1, 6, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(103, 5, 'عبد الرحمن  عز', NULL, '11:15:00', '19:15:00', '2026-04-13', 1, 220, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 6, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(104, 5, 'عبد الرحمن  عز', NULL, '11:15:00', '19:15:00', '2026-04-14', 2, NULL, NULL, NULL, '11:03:28', '20:26:54', NULL, NULL, NULL, '20:26:54', 4314, NULL, 33806, NULL, NULL, NULL, 6600, NULL, 1, 1, 6, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(105, 5, 'عبد الرحمن  عز', NULL, '11:15:00', '19:15:00', '2026-04-15', 2, NULL, NULL, NULL, '10:59:10', '20:12:16', NULL, NULL, NULL, '20:12:16', 3436, NULL, 33186, NULL, NULL, NULL, 6600, NULL, 1, 1, 6, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(106, 5, 'عبد الرحمن  عز', NULL, '11:15:00', '19:15:00', '2026-04-16', 2, NULL, NULL, NULL, '10:50:58', '20:50:41', NULL, NULL, NULL, '20:50:41', 5741, NULL, 35983, NULL, NULL, NULL, 6600, NULL, 1, 1, 6, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(107, 5, 'عبد الرحمن  عز', NULL, '11:15:00', '19:15:00', '2026-04-17', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 6, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(108, 5, 'عبد الرحمن  عز', NULL, '11:15:00', '19:15:00', '2026-04-18', 2, NULL, NULL, NULL, '10:54:19', '19:53:50', NULL, NULL, NULL, '19:53:50', 2330, NULL, 32371, NULL, NULL, NULL, 6600, NULL, 1, 1, 6, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(109, 5, 'عبد الرحمن  عز', NULL, '11:15:00', '19:15:00', '2026-04-19', 2, NULL, NULL, NULL, '11:11:32', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 6, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(110, 5, 'عبد الرحمن  عز', NULL, '11:15:00', '19:15:00', '2026-04-20', 1, 220, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 6, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(111, 5, 'عبد الرحمن  عز', NULL, '11:15:00', '19:15:00', '2026-04-21', 1, 220, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 6, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(112, 5, 'عبد الرحمن  عز', NULL, '11:15:00', '19:15:00', '2026-04-22', 1, 220, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 6, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(113, 5, 'عبد الرحمن  عز', NULL, '11:15:00', '19:15:00', '2026-04-23', 1, 220, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 6, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(114, 5, 'عبد الرحمن  عز', NULL, '11:15:00', '19:15:00', '2026-04-24', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 6, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(115, 5, 'عبد الرحمن  عز', NULL, '11:15:00', '19:15:00', '2026-04-25', 1, 220, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 6, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(116, 5, 'عبد الرحمن  عز', NULL, '11:15:00', '19:15:00', '2026-04-26', 1, 220, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 6, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(117, 5, 'عبد الرحمن  عز', NULL, '11:15:00', '19:15:00', '2026-04-27', 1, 220, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 6, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(118, 5, 'عبد الرحمن  عز', NULL, '11:15:00', '19:15:00', '2026-04-28', 1, 220, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 6, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(119, 5, 'عبد الرحمن  عز', NULL, '11:15:00', '19:15:00', '2026-04-29', 1, 220, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 6, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(120, 5, 'عبد الرحمن  عز', NULL, '11:15:00', '19:15:00', '2026-04-30', 1, 220, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 6, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(121, 10, 'عبد الرحمن المحلاوي', NULL, '14:15:00', '22:00:00', '2026-04-01', 2, NULL, NULL, NULL, '11:00:09', '19:43:21', NULL, NULL, NULL, NULL, NULL, NULL, 31392, NULL, NULL, 0, 7000, NULL, 3, 1, 11, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(122, 10, 'عبد الرحمن المحلاوي', NULL, '14:15:00', '22:00:00', '2026-04-02', 2, NULL, NULL, NULL, '11:06:02', '19:02:45', NULL, NULL, NULL, NULL, NULL, NULL, 28603, NULL, NULL, 0, 7000, NULL, 3, 1, 11, '2026-05-09 16:55:19', '2026-05-09 16:55:19'),
(123, 10, 'عبد الرحمن المحلاوي', NULL, '14:15:00', '22:00:00', '2026-04-03', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 7000, NULL, 3, 1, 11, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(124, 10, 'عبد الرحمن المحلاوي', NULL, '14:15:00', '22:00:00', '2026-04-04', 2, NULL, NULL, NULL, '11:16:12', '19:16:37', NULL, NULL, NULL, NULL, NULL, NULL, 28825, NULL, NULL, 0, 7000, NULL, 3, 1, 11, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(125, 10, 'عبد الرحمن المحلاوي', NULL, '14:15:00', '22:00:00', '2026-04-05', 2, NULL, NULL, NULL, '11:08:16', '19:15:24', NULL, NULL, NULL, NULL, NULL, NULL, 29228, NULL, NULL, 0, 7000, NULL, 3, 1, 11, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(126, 10, 'عبد الرحمن المحلاوي', NULL, '14:15:00', '22:00:00', '2026-04-06', 2, NULL, NULL, NULL, '20:10:49', '20:14:15', NULL, NULL, NULL, NULL, NULL, NULL, 206, '05:55:49', 518.9, 0, 7000, NULL, 3, 1, 11, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(127, 10, 'عبد الرحمن المحلاوي', NULL, '14:15:00', '22:00:00', '2026-04-07', 2, NULL, NULL, NULL, '11:25:06', '19:12:39', NULL, NULL, NULL, NULL, NULL, NULL, 28053, NULL, NULL, 0, 7000, NULL, 3, 1, 11, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(128, 10, 'عبد الرحمن المحلاوي', NULL, '14:15:00', '22:00:00', '2026-04-08', 2, NULL, NULL, NULL, '11:12:17', '19:14:35', NULL, NULL, NULL, NULL, NULL, NULL, 28938, NULL, NULL, 0, 7000, NULL, 3, 1, 11, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(129, 10, 'عبد الرحمن المحلاوي', NULL, '14:15:00', '22:00:00', '2026-04-09', 2, NULL, NULL, NULL, '11:25:55', '21:08:16', NULL, NULL, NULL, NULL, NULL, NULL, 34941, NULL, NULL, 0, 7000, NULL, 3, 1, 11, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(130, 10, 'عبد الرحمن المحلاوي', NULL, '14:15:00', '22:00:00', '2026-04-10', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 7000, NULL, 3, 1, 11, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(131, 10, 'عبد الرحمن المحلاوي', NULL, '14:15:00', '22:00:00', '2026-04-11', 2, NULL, NULL, NULL, '13:52:34', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 7000, NULL, 3, 1, 11, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(132, 7, 'احمد رضا', NULL, '11:15:00', '19:15:00', '2026-04-01', 1, 220, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 8, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(133, 12, 'محمد الشهاوي', NULL, '12:00:00', '20:00:00', '2026-04-01', 1, 150, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 4500, NULL, 4, 1, 14, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(134, 7, 'احمد رضا', NULL, '11:15:00', '19:15:00', '2026-04-02', 1, 220, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 8, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(135, 12, 'محمد الشهاوي', NULL, '12:00:00', '20:00:00', '2026-04-02', 1, 150, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 4500, NULL, 4, 1, 14, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(136, 7, 'احمد رضا', NULL, '11:15:00', '19:15:00', '2026-04-03', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 8, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(137, 12, 'محمد الشهاوي', NULL, '12:00:00', '20:00:00', '2026-04-03', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 4500, NULL, 4, 1, 14, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(138, 7, 'احمد رضا', NULL, '11:15:00', '19:15:00', '2026-04-04', 1, 220, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 8, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(139, 12, 'محمد الشهاوي', NULL, '12:00:00', '20:00:00', '2026-04-04', 1, 150, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 4500, NULL, 4, 1, 14, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(140, 7, 'احمد رضا', NULL, '11:15:00', '19:15:00', '2026-04-05', 1, 220, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 8, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(141, 12, 'محمد الشهاوي', NULL, '12:00:00', '20:00:00', '2026-04-05', 1, 150, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 4500, NULL, 4, 1, 14, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(142, 7, 'احمد رضا', NULL, '11:15:00', '19:15:00', '2026-04-06', 1, 220, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 8, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(143, 12, 'محمد الشهاوي', NULL, '12:00:00', '20:00:00', '2026-04-06', 1, 150, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 4500, NULL, 4, 1, 14, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(144, 7, 'احمد رضا', NULL, '11:15:00', '19:15:00', '2026-04-07', 1, 220, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 8, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(145, 12, 'محمد الشهاوي', NULL, '12:00:00', '20:00:00', '2026-04-07', 1, 150, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 4500, NULL, 4, 1, 14, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(146, 7, 'احمد رضا', NULL, '11:15:00', '19:15:00', '2026-04-08', 1, 220, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 8, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(147, 12, 'محمد الشهاوي', NULL, '12:00:00', '20:00:00', '2026-04-08', 1, 150, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 4500, NULL, 4, 1, 14, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(148, 7, 'احمد رضا', NULL, '11:15:00', '19:15:00', '2026-04-09', 1, 220, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 8, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(149, 12, 'محمد الشهاوي', NULL, '12:00:00', '20:00:00', '2026-04-09', 1, 150, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 4500, NULL, 4, 1, 14, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(150, 7, 'احمد رضا', NULL, '11:15:00', '19:15:00', '2026-04-10', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 8, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(151, 12, 'محمد الشهاوي', NULL, '12:00:00', '20:00:00', '2026-04-10', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 4500, NULL, 4, 1, 14, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(152, 7, 'احمد رضا', NULL, '11:15:00', '19:15:00', '2026-04-11', 1, 220, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 8, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(153, 12, 'محمد الشهاوي', NULL, '12:00:00', '20:00:00', '2026-04-11', 1, 150, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 4500, NULL, 4, 1, 14, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(154, 7, 'احمد رضا', NULL, '11:15:00', '19:15:00', '2026-04-12', 1, 220, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 8, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(155, 10, 'عبد الرحمن المحلاوي', NULL, '14:15:00', '22:00:00', '2026-04-12', 1, 233.33, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 7000, NULL, 3, 1, 11, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(156, 12, 'محمد الشهاوي', NULL, '12:00:00', '20:00:00', '2026-04-12', 1, 150, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 4500, NULL, 4, 1, 14, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(157, 7, 'احمد رضا', NULL, '11:15:00', '19:15:00', '2026-04-13', 1, 220, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 8, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(158, 10, 'عبد الرحمن المحلاوي', NULL, '14:15:00', '22:00:00', '2026-04-13', 1, 233.33, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 7000, NULL, 3, 1, 11, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(159, 12, 'محمد الشهاوي', NULL, '12:00:00', '20:00:00', '2026-04-13', 1, 150, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 4500, NULL, 4, 1, 14, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(160, 7, 'احمد رضا', NULL, '11:15:00', '19:15:00', '2026-04-14', 1, 220, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 8, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(161, 10, 'عبد الرحمن المحلاوي', NULL, '14:15:00', '22:00:00', '2026-04-14', 1, 233.33, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 7000, NULL, 3, 1, 11, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(162, 12, 'محمد الشهاوي', NULL, '12:00:00', '20:00:00', '2026-04-14', 1, 150, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 4500, NULL, 4, 1, 14, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(163, 7, 'احمد رضا', NULL, '11:15:00', '19:15:00', '2026-04-15', 1, 220, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 8, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(164, 10, 'عبد الرحمن المحلاوي', NULL, '14:15:00', '22:00:00', '2026-04-15', 1, 233.33, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 7000, NULL, 3, 1, 11, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(165, 12, 'محمد الشهاوي', NULL, '12:00:00', '20:00:00', '2026-04-15', 1, 150, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 4500, NULL, 4, 1, 14, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(166, 7, 'احمد رضا', NULL, '11:15:00', '19:15:00', '2026-04-16', 1, 220, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 8, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(167, 10, 'عبد الرحمن المحلاوي', NULL, '14:15:00', '22:00:00', '2026-04-16', 1, 233.33, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 7000, NULL, 3, 1, 11, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(168, 12, 'محمد الشهاوي', NULL, '12:00:00', '20:00:00', '2026-04-16', 1, 150, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 4500, NULL, 4, 1, 14, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(169, 7, 'احمد رضا', NULL, '11:15:00', '19:15:00', '2026-04-17', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 8, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(170, 10, 'عبد الرحمن المحلاوي', NULL, '14:15:00', '22:00:00', '2026-04-17', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 7000, NULL, 3, 1, 11, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(171, 12, 'محمد الشهاوي', NULL, '12:00:00', '20:00:00', '2026-04-17', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 4500, NULL, 4, 1, 14, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(172, 7, 'احمد رضا', NULL, '11:15:00', '19:15:00', '2026-04-18', 1, 220, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 8, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(173, 10, 'عبد الرحمن المحلاوي', NULL, '14:15:00', '22:00:00', '2026-04-18', 1, 233.33, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 7000, NULL, 3, 1, 11, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(174, 12, 'محمد الشهاوي', NULL, '12:00:00', '20:00:00', '2026-04-18', 1, 150, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 4500, NULL, 4, 1, 14, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(175, 7, 'احمد رضا', NULL, '11:15:00', '19:15:00', '2026-04-19', 1, 220, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 8, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(176, 10, 'عبد الرحمن المحلاوي', NULL, '14:15:00', '22:00:00', '2026-04-19', 1, 233.33, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 7000, NULL, 3, 1, 11, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(177, 12, 'محمد الشهاوي', NULL, '12:00:00', '20:00:00', '2026-04-19', 1, 150, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 4500, NULL, 4, 1, 14, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(178, 7, 'احمد رضا', NULL, '11:15:00', '19:15:00', '2026-04-20', 1, 220, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 8, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(179, 10, 'عبد الرحمن المحلاوي', NULL, '14:15:00', '22:00:00', '2026-04-20', 1, 233.33, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 7000, NULL, 3, 1, 11, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(180, 12, 'محمد الشهاوي', NULL, '12:00:00', '20:00:00', '2026-04-20', 1, 150, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 4500, NULL, 4, 1, 14, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(181, 7, 'احمد رضا', NULL, '11:15:00', '19:15:00', '2026-04-21', 1, 220, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 8, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(182, 10, 'عبد الرحمن المحلاوي', NULL, '14:15:00', '22:00:00', '2026-04-21', 1, 233.33, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 7000, NULL, 3, 1, 11, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(183, 12, 'محمد الشهاوي', NULL, '12:00:00', '20:00:00', '2026-04-21', 1, 150, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 4500, NULL, 4, 1, 14, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(184, 7, 'احمد رضا', NULL, '11:15:00', '19:15:00', '2026-04-22', 1, 220, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 8, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(185, 10, 'عبد الرحمن المحلاوي', NULL, '14:15:00', '22:00:00', '2026-04-22', 1, 233.33, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 7000, NULL, 3, 1, 11, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(186, 12, 'محمد الشهاوي', NULL, '12:00:00', '20:00:00', '2026-04-22', 1, 150, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 4500, NULL, 4, 1, 14, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(187, 7, 'احمد رضا', NULL, '11:15:00', '19:15:00', '2026-04-23', 1, 220, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 8, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(188, 10, 'عبد الرحمن المحلاوي', NULL, '14:15:00', '22:00:00', '2026-04-23', 1, 233.33, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 7000, NULL, 3, 1, 11, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(189, 12, 'محمد الشهاوي', NULL, '12:00:00', '20:00:00', '2026-04-23', 1, 150, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 4500, NULL, 4, 1, 14, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(190, 7, 'احمد رضا', NULL, '11:15:00', '19:15:00', '2026-04-24', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 8, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(191, 10, 'عبد الرحمن المحلاوي', NULL, '14:15:00', '22:00:00', '2026-04-24', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 7000, NULL, 3, 1, 11, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(192, 12, 'محمد الشهاوي', NULL, '12:00:00', '20:00:00', '2026-04-24', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 4500, NULL, 4, 1, 14, '2026-05-09 16:55:20', '2026-05-09 16:55:20'),
(193, 7, 'احمد رضا', NULL, '11:15:00', '19:15:00', '2026-04-25', 1, 220, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 8, '2026-05-09 16:55:21', '2026-05-09 16:55:21'),
(194, 10, 'عبد الرحمن المحلاوي', NULL, '14:15:00', '22:00:00', '2026-04-25', 1, 233.33, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 7000, NULL, 3, 1, 11, '2026-05-09 16:55:21', '2026-05-09 16:55:21'),
(195, 12, 'محمد الشهاوي', NULL, '12:00:00', '20:00:00', '2026-04-25', 1, 150, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 4500, NULL, 4, 1, 14, '2026-05-09 16:55:21', '2026-05-09 16:55:21'),
(196, 7, 'احمد رضا', NULL, '11:15:00', '19:15:00', '2026-04-26', 1, 220, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 8, '2026-05-09 16:55:21', '2026-05-09 16:55:21'),
(197, 10, 'عبد الرحمن المحلاوي', NULL, '14:15:00', '22:00:00', '2026-04-26', 1, 233.33, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 7000, NULL, 3, 1, 11, '2026-05-09 16:55:21', '2026-05-09 16:55:21'),
(198, 12, 'محمد الشهاوي', NULL, '12:00:00', '20:00:00', '2026-04-26', 1, 150, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 4500, NULL, 4, 1, 14, '2026-05-09 16:55:21', '2026-05-09 16:55:21'),
(199, 7, 'احمد رضا', NULL, '11:15:00', '19:15:00', '2026-04-27', 1, 220, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 8, '2026-05-09 16:55:21', '2026-05-09 16:55:21'),
(200, 10, 'عبد الرحمن المحلاوي', NULL, '14:15:00', '22:00:00', '2026-04-27', 1, 233.33, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 7000, NULL, 3, 1, 11, '2026-05-09 16:55:21', '2026-05-09 16:55:21'),
(201, 12, 'محمد الشهاوي', NULL, '12:00:00', '20:00:00', '2026-04-27', 1, 150, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 4500, NULL, 4, 1, 14, '2026-05-09 16:55:21', '2026-05-09 16:55:21'),
(202, 7, 'احمد رضا', NULL, '11:15:00', '19:15:00', '2026-04-28', 1, 220, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 8, '2026-05-09 16:55:21', '2026-05-09 16:55:21'),
(203, 10, 'عبد الرحمن المحلاوي', NULL, '14:15:00', '22:00:00', '2026-04-28', 1, 233.33, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 7000, NULL, 3, 1, 11, '2026-05-09 16:55:21', '2026-05-09 16:55:21'),
(204, 12, 'محمد الشهاوي', NULL, '12:00:00', '20:00:00', '2026-04-28', 1, 150, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 4500, NULL, 4, 1, 14, '2026-05-09 16:55:21', '2026-05-09 16:55:21'),
(205, 7, 'احمد رضا', NULL, '11:15:00', '19:15:00', '2026-04-29', 1, 220, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 8, '2026-05-09 16:55:21', '2026-05-09 16:55:21'),
(206, 10, 'عبد الرحمن المحلاوي', NULL, '14:15:00', '22:00:00', '2026-04-29', 1, 233.33, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 7000, NULL, 3, 1, 11, '2026-05-09 16:55:21', '2026-05-09 16:55:21'),
(207, 12, 'محمد الشهاوي', NULL, '12:00:00', '20:00:00', '2026-04-29', 1, 150, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 4500, NULL, 4, 1, 14, '2026-05-09 16:55:21', '2026-05-09 16:55:21'),
(208, 7, 'احمد رضا', NULL, '11:15:00', '19:15:00', '2026-04-30', 1, 220, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 6600, NULL, 1, 1, 8, '2026-05-09 16:55:21', '2026-05-09 16:55:21'),
(209, 10, 'عبد الرحمن المحلاوي', NULL, '14:15:00', '22:00:00', '2026-04-30', 1, 233.33, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 7000, NULL, 3, 1, 11, '2026-05-09 16:55:21', '2026-05-09 16:55:21'),
(210, 12, 'محمد الشهاوي', NULL, '12:00:00', '20:00:00', '2026-04-30', 1, 150, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '00:00:00', 0, NULL, 4500, NULL, 4, 1, 14, '2026-05-09 16:55:21', '2026-05-09 16:55:21');

-- --------------------------------------------------------

--
-- Table structure for table `disemps`
--

CREATE TABLE `disemps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `dis` double DEFAULT NULL,
  `date` date DEFAULT NULL,
  `dec` text DEFAULT NULL,
  `employees_id` bigint(20) UNSIGNED NOT NULL,
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dis_transports`
--

CREATE TABLE `dis_transports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mony` int(11) DEFAULT NULL,
  `employees_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `domains`
--

CREATE TABLE `domains` (
  `id` int(10) UNSIGNED NOT NULL,
  `domain` varchar(255) NOT NULL,
  `tenant_id` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `eids`
--

CREATE TABLE `eids` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `num` int(11) NOT NULL,
  `zkt_synced` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0=not synced, 1=synced to ZK device',
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `password_email` varchar(255) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `mobile` int(11) DEFAULT NULL,
  `role_zkt` int(11) DEFAULT NULL,
  `title` text DEFAULT NULL,
  `friday` int(11) DEFAULT NULL,
  `free_d` varchar(255) DEFAULT NULL,
  `sysemp` int(11) DEFAULT NULL,
  `choosesys` int(11) DEFAULT NULL,
  `check` int(11) DEFAULT NULL,
  `ipz` varchar(255) DEFAULT NULL,
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `shift_id` bigint(20) UNSIGNED NOT NULL,
  `kabd` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `fingerprint` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `num`, `zkt_synced`, `name`, `email`, `password`, `password_email`, `age`, `mobile`, `role_zkt`, `title`, `friday`, `free_d`, `sysemp`, `choosesys`, `check`, `ipz`, `section_id`, `shift_id`, `kabd`, `created_at`, `updated_at`, `fingerprint`) VALUES
(32, 1, 1, 'gamal', NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, '[\"\\u0644\\u0627 \\u064a\\u0648\\u062c\\u062f\"]', 30, 1, 0, 'A6F5224260946', 4, 7, 1222, '2026-10-16 17:05:00', '2026-10-16 17:11:47', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employee_allowed_locations`
--

CREATE TABLE `employee_allowed_locations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `radius` int(11) NOT NULL DEFAULT 100,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_company_location`
--

CREATE TABLE `employee_company_location` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `company_location_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('both','in','out') NOT NULL DEFAULT 'both',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_locations`
--

CREATE TABLE `employee_locations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employees_id` bigint(20) UNSIGNED NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `recorded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `evaluations`
--

CREATE TABLE `evaluations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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

-- --------------------------------------------------------

--
-- Table structure for table `forget_fingers`
--

CREATE TABLE `forget_fingers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `in` time DEFAULT NULL,
  `out` time DEFAULT NULL,
  `date` date DEFAULT NULL,
  `absent` int(11) DEFAULT NULL,
  `dis_in` int(11) DEFAULT NULL,
  `dis_out` int(11) DEFAULT NULL,
  `employees_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hafzeids`
--

CREATE TABLE `hafzeids` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hafzeid` int(11) NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hafzs`
--

CREATE TABLE `hafzs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `hafz` double DEFAULT NULL,
  `date` date DEFAULT NULL,
  `dec` text DEFAULT NULL,
  `employees_id` bigint(20) UNSIGNED NOT NULL,
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `head_sections`
--

CREATE TABLE `head_sections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `shift_id` bigint(20) UNSIGNED NOT NULL,
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ipzkts`
--

CREATE TABLE `ipzkts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `sn` varchar(50) DEFAULT NULL COMMENT 'Serial Number of the ZKT device (used for ADMS)',
  `start` int(11) NOT NULL,
  `end` int(11) NOT NULL,
  `last_activity` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ipzkts`
--

INSERT INTO `ipzkts` (`id`, `name`, `ip`, `sn`, `start`, `end`, `last_activity`, `created_at`, `updated_at`) VALUES
(3, 'FF', NULL, 'A6F5224260946', 1, 1000, '2026-08-10 01:21:32', '2026-08-10 00:56:56', '2026-08-10 01:21:32');

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
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date_end` date DEFAULT NULL,
  `date_start` date DEFAULT NULL,
  `mony` int(11) DEFAULT NULL,
  `mony_now` int(11) DEFAULT NULL,
  `mony_after` int(11) DEFAULT NULL,
  `month` int(11) DEFAULT NULL,
  `month_now` int(11) DEFAULT NULL,
  `employees_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `location_requests`
--

CREATE TABLE `location_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `numid` varchar(255) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `lat` varchar(255) DEFAULT NULL,
  `lon` varchar(255) DEFAULT NULL,
  `response_time` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `location_requests`
--

INSERT INTO `location_requests` (`id`, `numid`, `admin_id`, `status`, `lat`, `lon`, `response_time`, `created_at`, `updated_at`) VALUES
(2, '1', 1, 1, '37.4219983', '-122.084', '2026-07-19 19:13:26', '2026-07-19 18:50:14', '2026-07-19 19:13:26');

-- --------------------------------------------------------

--
-- Table structure for table `manager_section`
--

CREATE TABLE `manager_section` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(4, '2023_11_30_233428_create_ipzkts_table', 1),
(5, '2024_09_04_202117_create_sections_table', 1),
(6, '2024_09_04_202348_create_shifts_table', 1),
(7, '2024_09_05_213527_create_employees_table', 1),
(8, '2024_09_06_015650_create_salves_table', 1),
(9, '2024_09_06_232900_create_disemps_table', 1),
(10, '2024_09_07_001619_create_counts_table', 1),
(11, '2024_09_17_233419_create_zkts_table', 1),
(12, '2024_10_03_223601_create_permission_tables', 1),
(13, '2024_10_16_003536_create_hafzs_table', 1),
(14, '2024_10_16_232112_add_user_agent_to_users_table', 1),
(15, '2024_10_18_191911_create_countips_table', 1),
(16, '2024_10_24_234233_create_nozkts_table', 1),
(17, '2024_11_02_224310_create_mikros_table', 1),
(18, '2024_11_08_205153_create_change_shifts_table', 1),
(19, '2024_11_11_211422_create_pleases_table', 1),
(20, '2024_11_15_202934_create_forget_fingers_table', 1),
(21, '2024_11_18_003008_create_vacations_table', 1),
(22, '2024_11_29_213544_create_tlbpleases_table', 1),
(23, '2024_12_27_201151_create_notifications_table', 1),
(24, '2025_01_13_233239_create_head_sections_table', 1),
(25, '2025_01_19_020823_create_loans_table', 1),
(26, '2025_01_22_213040_create_dis_transports_table', 1),
(27, '2025_01_23_034646_create_badel_transports_table', 1),
(28, '2025_01_24_195845_create_taminats_table', 1),
(29, '2025_01_24_203331_create_purchases_table', 1),
(30, '2025_02_01_010224_create_eids_table', 1),
(31, '2025_02_01_095607_create_hafzeids_table', 1),
(32, '2025_03_25_003053_create_counts_emps_table', 1),
(33, '2025_05_23_002047_create_plzots_table', 1),
(34, '2025_12_12_152245_create_shift_weeks_table', 1),
(35, '2025_12_14_032456_create_evaluations_table', 1),
(36, '2026_04_18_011347_add_location_to_zkts_table', 1),
(37, '2026_04_18_013800_add_indices_to_attendance_tables', 1),
(38, '2026_04_18_014500_add_location_to_counts_table', 1),
(39, '2026_04_18_022207_add_break_location_to_counts_table', 1),
(40, '2026_04_18_043058_create_location_requests_table', 1),
(41, '2026_04_22_215230_create_manager_section_table', 1),
(42, '2026_04_23_014354_add_fingerprint_to_employees_table', 1),
(43, '2026_04_24_192904_create_sup_shifts_table', 1),
(44, '2026_04_29_031325_add_dateshift_end_to_tlbpleases_table', 2),
(45, '2026_06_03_140543_add_status_to_forget_fingers_table', 3),
(46, '2019_09_15_000010_create_tenants_table', 4),
(47, '2019_09_15_000020_create_domains_table', 4),
(48, '2026_06_13_000000_add_email_and_phone_to_tenants', 5),
(49, '2026_06_15_000001_add_subscription_dates_to_tenants', 6),
(50, '2026_06_15_000002_create_pricing_plans_table', 7),
(51, '2026_06_15_000003_add_description_to_pricing_plans', 8),
(52, '2026_06_15_000003_add_plan_to_tenants', 9),
(53, '2026_06_15_222126_remove_slug_from_pricing_plans_table', 10),
(54, '2026_06_16_000001_create_central_admin_roles_table', 11),
(55, '2026_06_16_000002_create_central_admins_table', 11),
(56, '2026_06_16_002546_add_status_to_tenants_table', 12),
(57, '2026_06_25_015107_add_duration_days_to_pricing_plans_table', 13),
(58, '2026_06_30_000001_create_employee_allowed_locations_table', 14),
(59, '2026_06_18_214849_add_user_agent_mobile_to_users_table', 15),
(60, '2026_06_30_203528_create_company_locations_table', 15),
(61, '2026_07_03_000001_add_type_to_employee_company_location_table', 15),
(62, '2026_07_07_000001_add_sn_to_ipzkts_table', 16),
(63, '2026_07_07_164241_add_last_activity_to_ipzkts_table', 17),
(64, '2026_07_11_133506_add_phone_password_to_employees_table', 18),
(65, '2026_07_04_005652_create_employee_locations_table', 19),
(66, '2026_10_16_183326_add_zkt_synced_to_employees_table', 20),
(67, '2026_10_16_190823_create_zkt_deleted_users_table', 21),
(68, '2026_10_16_194400_create_zkt_synced_device_users_table', 22),
(69, '2026_10_16_195500_create_zkt_device_sync_tables', 23);

-- --------------------------------------------------------

--
-- Table structure for table `mikros`
--

CREATE TABLE `mikros` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(1, 'App\\Models\\User', 22);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('0b884ede-ceeb-4f77-a003-e7db61c7241c', 'App\\Notifications\\salf_notiy', 'App\\Models\\User', 13, '{\"masg\":\"\\u062e\\u0635\\u0645 100\",\"id\":56,\"date\":\"2026-06-12\",\"dec\":null,\"type\":\"dis\",\"user\":\"Ahmed elkatry\"}', NULL, '2026-06-12 14:30:54', '2026-06-12 14:30:54'),
('17781c8d-1b55-4663-ad0f-b5506ca1f7f2', 'App\\Notifications\\salf_notiy', 'App\\Models\\User', 1, '{\"masg\":\"\\u0637\\u0644\\u0628 \\u062a\\u0628\\u062f\\u064a\\u0644 \\u0634\\u064a\\u0641\\u062a \\u0627\\u0644\\u064a it \\u0645\\u0646 \\u062a\\u0627\\u0631\\u064a\\u062e 2026-04-29 \\u0627\\u0644\\u064a \\u062a\\u0627\\u0631\\u064a\\u062e 2026-04-29\",\"id\":10,\"date\":\"2026-04-29T00:31:16.000000Z\",\"dec\":\"kl;\",\"type\":\"tlpchange_shift\",\"user\":\"\\u0639\\u0628\\u062f \\u0627\\u0644\\u0631\\u062d\\u0645\\u0646  \\u0639\\u0632\"}', '2026-05-06 23:04:03', '2026-04-29 00:31:16', '2026-05-06 23:04:03'),
('290e578a-88ed-4daf-9675-fb5c01b5430e', 'App\\Notifications\\salf_notiy', 'App\\Models\\User', 3, '{\"masg\":\"\\u062a\\u0645 \\u0642\\u0628\\u0648\\u0644 \\u062a\\u063a\\u064a\\u0631 \\u0627\\u0644\\u0634\\u064a\\u0641\\u062a \\u0627\\u0644\\u064a \\u0635\\u0628\\u0627\\u062d\\u064a \\u0645\\u0646 \\u062a\\u0627\\u0631\\u064a\\u062e 2026-04-26 \\u0627\\u0644\\u064a \\u062a\\u0627\\u0631\\u064a\\u062e 2026-04-29\",\"id\":6,\"date\":\"2026-04-26\",\"dec\":\"\\u062a\\u062a\\u062a\\u062a\\u062a\",\"type\":\"ok_shift\",\"user\":\"Ahmed elkatry\"}', NULL, '2026-04-29 00:21:07', '2026-04-29 00:21:07'),
('2f074285-cd86-4bcc-87d1-5a727a146b6f', 'App\\Notifications\\salf_notiy', 'App\\Models\\User', 1, '{\"masg\":\"\\u0637\\u0644\\u0628 \\u0627\\u062c\\u0627\\u0632\\u0647 \\u064a\\u0648\\u0645 2026-06-06\",\"id\":13,\"date\":\"2026-06-06T00:00:30.000000Z\",\"dec\":\"sdfsdf\",\"type\":\"tlpfree\",\"user\":\"Katry\"}', NULL, '2026-06-06 00:00:30', '2026-06-06 00:00:30'),
('31dc8df2-d5e4-4b23-85e9-481a144435df', 'App\\Notifications\\salf_notiy', 'App\\Models\\User', 1, '{\"masg\":\"\\u0637\\u0644\\u0628 \\u0627\\u062c\\u0627\\u0632\\u0647 \\u064a\\u0648\\u0645 2026-04-29\",\"id\":2,\"date\":\"2026-04-29T00:01:44.000000Z\",\"dec\":\"\\u0633\\u064a\\u0628\\u0633\\u064a\\u0628\",\"type\":\"tlpfree\",\"user\":\"\\u0639\\u0628\\u062f \\u0627\\u0644\\u0631\\u062d\\u0645\\u0646  \\u0639\\u0632\"}', '2026-05-06 23:04:03', '2026-04-29 00:01:44', '2026-05-06 23:04:03'),
('3391327d-c0af-423b-b8d0-3302f56d3118', 'App\\Notifications\\salf_notiy', 'App\\Models\\User', 1, '{\"masg\":\"\\u0637\\u0644\\u0628 \\u062a\\u0628\\u062f\\u064a\\u0644 \\u0634\\u064a\\u0641\\u062a \\u0627\\u0644\\u064a it \\u0645\\u0646 \\u062a\\u0627\\u0631\\u064a\\u062e 2026-04-29 \\u0627\\u0644\\u064a \\u062a\\u0627\\u0631\\u064a\\u062e 2026-04-29\",\"id\":9,\"date\":\"2026-04-29T00:27:27.000000Z\",\"dec\":\"\\u0628\\u0627\\u0628\\u0644\",\"type\":\"tlpchange_shift\",\"user\":\"\\u0639\\u0628\\u062f \\u0627\\u0644\\u0631\\u062d\\u0645\\u0646  \\u0639\\u0632\"}', '2026-05-06 23:04:03', '2026-04-29 00:27:27', '2026-05-06 23:04:03'),
('36016e57-3c25-4847-a1a9-0a5fd4a828f2', 'App\\Notifications\\salf_notiy', 'App\\Models\\User', 1, '{\"masg\":\"\\u0637\\u0644\\u0628 \\u062a\\u0628\\u062f\\u064a\\u0644 \\u0634\\u064a\\u0641\\u062a \\u0627\\u0644\\u064a \\u0635\\u0628\\u0627\\u062d\\u064a \\u0645\\u0646 \\u062a\\u0627\\u0631\\u064a\\u062e 2026-04-26 \\u0627\\u0644\\u064a \\u062a\\u0627\\u0631\\u064a\\u062e 2026-04-29\",\"id\":6,\"date\":\"2026-04-29T00:20:23.000000Z\",\"dec\":\"\\u062a\\u062a\\u062a\\u062a\\u062a\",\"type\":\"tlpchange_shift\",\"user\":\"\\u0639\\u0628\\u062f \\u0627\\u0644\\u0631\\u062d\\u0645\\u0646  \\u0639\\u0632\"}', '2026-05-06 23:04:03', '2026-04-29 00:20:23', '2026-05-06 23:04:03'),
('416959a1-17ad-4927-bc51-5ab2b6322ef8', 'App\\Notifications\\salf_notiy', 'App\\Models\\User', 3, '{\"masg\":\" \\u062a\\u0645 \\u0631\\u0641\\u0636 \\u0627\\u0644\\u0627\\u062c\\u0627\\u0632\\u0647 \\u0628\\u062a\\u0627\\u0631\\u064a\\u062e 2026-04-29 \",\"id\":2,\"date\":\"2026-04-29\",\"dec\":\"\\u0633\\u064a\\u0628\\u0633\\u064a\\u0628\",\"type\":\"no_free\",\"user\":\"Ahmed elkatry\"}', '2026-04-29 00:02:45', '2026-04-29 00:02:25', '2026-04-29 00:02:45'),
('494798cb-6f96-4662-9181-b48fb25d1437', 'App\\Notifications\\salf_notiy', 'App\\Models\\User', 1, '{\"masg\":\"\\u0637\\u0644\\u0628 \\u062a\\u0628\\u062f\\u064a\\u0644 \\u0634\\u064a\\u0641\\u062a \\u0627\\u0644\\u064a \\u0635\\u0628\\u0627\\u062d\\u064a \",\"id\":3,\"date\":\"2026-04-29T00:04:51.000000Z\",\"dec\":\"\\u064a\\u0633\\u064a\\u0634\",\"type\":\"tlpchange_shift\",\"user\":\"\\u0639\\u0628\\u062f \\u0627\\u0644\\u0631\\u062d\\u0645\\u0646  \\u0639\\u0632\"}', '2026-05-06 23:04:03', '2026-04-29 00:04:51', '2026-05-06 23:04:03'),
('512d9fef-c556-4a4f-8805-b1c54993dd56', 'App\\Notifications\\salf_notiy', 'App\\Models\\User', 1, '{\"masg\":\"\\u0637\\u0644\\u0628 \\u062a\\u0628\\u062f\\u064a\\u0644 \\u0634\\u064a\\u0641\\u062a \\u0627\\u0644\\u064a it \",\"id\":5,\"date\":\"2026-04-29T00:15:30.000000Z\",\"dec\":\"\\u062a\\u0627\\u0644\\u062a\\u0646\",\"type\":\"tlpchange_shift\",\"user\":\"\\u0639\\u0628\\u062f \\u0627\\u0644\\u0631\\u062d\\u0645\\u0646  \\u0639\\u0632\"}', '2026-05-06 23:04:03', '2026-04-29 00:15:30', '2026-05-06 23:04:03'),
('53c580bf-838a-4c66-9100-868fa703c6e0', 'App\\Notifications\\salf_notiy', 'App\\Models\\User', 13, '{\"masg\":\"\\u062a\\u0645 \\u0642\\u0628\\u0648\\u0644 \\u0633\\u0644\\u0641\\u0647 \\u0628\\u0642\\u064a\\u0645\\u0647 50 \\u062c\\u0646\\u064a\\u0647\",\"id\":298,\"date\":\"2026-06-12\",\"dec\":null,\"type\":\"salf\",\"user\":\"Ahmed elkatry\"}', NULL, '2026-06-12 14:30:54', '2026-06-12 14:30:54'),
('5d7f4e1b-7a73-41cb-b70a-579c743b9ffc', 'App\\Notifications\\salf_notiy', 'App\\Models\\User', 1, '{\"masg\":\"\\u0637\\u0644\\u0628 \\u062a\\u0628\\u062f\\u064a\\u0644 \\u0634\\u064a\\u0641\\u062a \\u0627\\u0644\\u064a it \\u0645\\u0646 \\u062a\\u0627\\u0631\\u064a\\u062e 2026-04-29 \\u0627\\u0644\\u064a \\u062a\\u0627\\u0631\\u064a\\u062e 2026-04-29\",\"id\":11,\"date\":\"2026-04-29T00:31:57.000000Z\",\"dec\":\"zZXZ\",\"type\":\"tlpchange_shift\",\"user\":\"\\u0639\\u0628\\u062f \\u0627\\u0644\\u0631\\u062d\\u0645\\u0646  \\u0639\\u0632\"}', '2026-05-06 23:04:03', '2026-04-29 00:31:57', '2026-05-06 23:04:03'),
('6ead8ae1-7562-4a51-99cb-3939270a7c06', 'App\\Notifications\\salf_notiy', 'App\\Models\\User', 2, '{\"masg\":\"\\u062e\\u0635\\u0645 200\",\"id\":1,\"date\":\"2026-04-01\",\"dec\":\"vdfgvfd\",\"type\":\"dis\",\"user\":\"Ahmed elkatry\"}', NULL, '2026-05-01 20:41:28', '2026-05-01 20:41:28'),
('b3c1c67a-067d-4397-8a16-c0f45987150e', 'App\\Notifications\\salf_notiy', 'App\\Models\\User', 1, '{\"masg\":\"\\u0637\\u0644\\u0628 \\u0627\\u062c\\u0627\\u0632\\u0647 \\u064a\\u0648\\u0645 2026-04-29\",\"id\":1,\"date\":\"2026-04-28T23:51:30.000000Z\",\"dec\":\"cvfvds\",\"type\":\"tlpfree\",\"user\":\"\\u0639\\u0628\\u062f \\u0627\\u0644\\u0631\\u062d\\u0645\\u0646  \\u0639\\u0632\"}', '2026-05-06 23:04:03', '2026-04-28 23:51:30', '2026-05-06 23:04:03'),
('b5cefa8e-bd3d-49c7-a06b-364d330f5619', 'App\\Notifications\\salf_notiy', 'App\\Models\\User', 13, '{\"masg\":\"\\u062d\\u0627\\u0641\\u0632 100\",\"id\":72,\"date\":\"2026-06-12\",\"dec\":null,\"type\":\"hafz\",\"user\":\"Ahmed elkatry\"}', NULL, '2026-06-12 14:30:54', '2026-06-12 14:30:54'),
('b9676cf1-afd1-4bdc-99e5-cd49eb85b1dc', 'App\\Notifications\\salf_notiy', 'App\\Models\\User', 3, '{\"masg\":\"\\u062a\\u0645 \\u0642\\u0628\\u0648\\u0644 \\u0627\\u0644\\u0627\\u062c\\u0627\\u0632\\u0647 \\u0628\\u062a\\u0627\\u0631\\u064a\\u062e  2026-04-29 \",\"id\":1,\"date\":\"2026-04-29\",\"dec\":null,\"type\":\"ok_free\",\"user\":\"Ahmed elkatry\"}', '2026-04-29 00:02:45', '2026-04-28 23:52:13', '2026-04-29 00:02:45'),
('c582d09c-64a4-4dc5-83d0-0cbffc873d9d', 'App\\Notifications\\salf_notiy', 'App\\Models\\User', 1, '{\"masg\":\"\\u0637\\u0644\\u0628 \\u062a\\u0628\\u062f\\u064a\\u0644 \\u0634\\u064a\\u0641\\u062a \\u0627\\u0644\\u064a \\u0635\\u0628\\u0627\\u062d\\u064a 2 \\u0645\\u0646 \\u062a\\u0627\\u0631\\u064a\\u062e 2026-04-29 \\u0627\\u0644\\u064a \\u062a\\u0627\\u0631\\u064a\\u062e 2026-04-29\",\"id\":7,\"date\":\"2026-04-29T00:26:57.000000Z\",\"dec\":\"\\u0641\\u063a\\u0627\\u0641\\u0642\",\"type\":\"tlpchange_shift\",\"user\":\"\\u0639\\u0628\\u062f \\u0627\\u0644\\u0631\\u062d\\u0645\\u0646  \\u0639\\u0632\"}', '2026-05-06 23:04:03', '2026-04-29 00:26:57', '2026-05-06 23:04:03'),
('d1edebac-0dd2-4a43-9287-5de95bc21415', 'App\\Notifications\\salf_notiy', 'App\\Models\\User', 1, '{\"masg\":\"\\u0637\\u0644\\u0628 \\u0627\\u0630\\u0646  \\u064a\\u0648\\u0645 2026-06-06\",\"id\":12,\"date\":\"2026-06-05T23:59:36.000000Z\",\"dec\":\"sfsdf\",\"type\":\"tlpplz\",\"user\":\"Katry\"}', NULL, '2026-06-05 23:59:36', '2026-06-05 23:59:36'),
('df9e88b5-bd44-49e8-a8bb-a772c955413d', 'App\\Notifications\\salf_notiy', 'App\\Models\\User', 1, '{\"masg\":\"\\u0637\\u0644\\u0628 \\u062a\\u0628\\u062f\\u064a\\u0644 \\u0634\\u064a\\u0641\\u062a \\u0627\\u0644\\u064a \\u0635\\u0628\\u0627\\u062d\\u064a \",\"id\":4,\"date\":\"2026-04-29T00:15:15.000000Z\",\"dec\":\"0\",\"type\":\"tlpchange_shift\",\"user\":\"\\u0639\\u0628\\u062f \\u0627\\u0644\\u0631\\u062d\\u0645\\u0646  \\u0639\\u0632\"}', '2026-05-06 23:04:03', '2026-04-29 00:15:16', '2026-05-06 23:04:03'),
('ff14b083-11dd-4de6-a2d7-408ae953aa52', 'App\\Notifications\\salf_notiy', 'App\\Models\\User', 1, '{\"masg\":\"\\u0637\\u0644\\u0628 \\u062a\\u0628\\u062f\\u064a\\u0644 \\u0634\\u064a\\u0641\\u062a \\u0627\\u0644\\u064a it \\u0645\\u0646 \\u062a\\u0627\\u0631\\u064a\\u062e 2026-04-29 \\u0627\\u0644\\u064a \\u062a\\u0627\\u0631\\u064a\\u062e 2026-04-29\",\"id\":8,\"date\":\"2026-04-29T00:27:11.000000Z\",\"dec\":\"\\u063a\\u0641\\u0639\",\"type\":\"tlpchange_shift\",\"user\":\"\\u0639\\u0628\\u062f \\u0627\\u0644\\u0631\\u062d\\u0645\\u0646  \\u0639\\u0632\"}', '2026-05-06 23:04:03', '2026-04-29 00:27:11', '2026-05-06 23:04:03');

-- --------------------------------------------------------

--
-- Table structure for table `nozkts`
--

CREATE TABLE `nozkts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `numid` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `absent` varchar(255) DEFAULT NULL,
  `in` time DEFAULT NULL,
  `out` time DEFAULT NULL,
  `no_out` int(11) DEFAULT NULL,
  `break_in` time DEFAULT NULL,
  `break_out` time DEFAULT NULL,
  `latitude_in` decimal(10,8) DEFAULT NULL,
  `longitude_in` decimal(11,8) DEFAULT NULL,
  `latitude_out` decimal(10,8) DEFAULT NULL,
  `longitude_out` decimal(11,8) DEFAULT NULL,
  `ok_out` int(11) DEFAULT NULL,
  `ok_in` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'الموظفين', 'web', '2026-04-25 23:01:33', '2026-04-25 23:01:33'),
(2, 'اضافه موظف', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(3, 'تعديل موظف', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(4, 'حذف موظف', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(5, 'شيفت', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(6, 'اضافه شيفت', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(7, 'تعديل شيفت', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(8, 'حذف شيفت', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(9, 'تبديل شيفت', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(10, 'تبديل شيفت موظف', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(11, 'اضافه شيفت موظف', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(12, 'تعديل شيفت موظف', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(13, 'حذف  شيفت موظف', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(14, 'تبديل شيفت قسم', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(15, 'اضافه شيفت قسم', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(16, 'تعديل شيفت قسم', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(17, 'حذف  شيفت قسم', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(18, 'اذن', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(19, 'اضافه اذن', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(20, 'تعديل اذن', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(21, 'حذف اذن', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(22, 'اداره بصمات الموظفين', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(23, 'اضافه بصمه موظف', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(24, 'تعديل بصمه موظف', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(25, 'حذف بصمه موظف', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(26, 'طلب اجازه', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(27, 'اضافه اجازه', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(28, 'تعديل اجازه', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(29, 'حذف اجازه', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(30, 'الاقسام', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(31, 'اضافه قسم', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(32, 'تعديل قسم', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(33, 'حذف قسم', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(34, 'الخصم', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(35, 'اضافه خصم', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(36, 'تعديل خصم', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(37, 'حذف خصم', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(38, 'سلف', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(39, 'اضافه سلف', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(40, 'تعديل سلف', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(41, 'حذف سلف', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(42, 'التقرير', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(43, 'تقرير موظف', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(44, 'استيراد ملف موظف', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(45, 'بحث موظف', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(46, 'تقرير اجمالي', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(47, 'تقرير بالقسم', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(48, 'استيراد ملف قسم', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(49, 'بحث القسم', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(50, 'اعدادات المستخدين', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(51, 'المستخدمين', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(52, 'اضافه مستخدم', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(53, 'تعديل مستخدم', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(54, 'حذف مستخدم', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(55, 'الصلاحيات', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(56, 'عرض صلاحية', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(57, 'اضافة صلاحية', 'web', '2026-04-25 23:01:34', '2026-04-25 23:01:34'),
(58, 'تعديل صلاحية', 'web', '2026-04-25 23:01:35', '2026-04-25 23:01:35'),
(59, 'حذف صلاحية', 'web', '2026-04-25 23:01:35', '2026-04-25 23:01:35'),
(60, 'سحب السجلات الهواتف', 'web', '2026-04-25 23:01:35', '2026-04-25 23:01:35'),
(61, 'IP-الاتصال', 'web', '2026-04-25 23:01:35', '2026-04-25 23:01:35'),
(62, 'سحب الموظفين من البصمه', 'web', '2026-04-25 23:01:35', '2026-04-25 23:01:35'),
(63, 'حافز', 'web', '2026-04-25 23:01:35', '2026-04-25 23:01:35'),
(64, 'اضافه حافز', 'web', '2026-04-25 23:01:35', '2026-04-25 23:01:35'),
(65, 'تعديل حافز', 'web', '2026-04-25 23:01:35', '2026-04-25 23:01:35'),
(66, 'حذف حافز', 'web', '2026-04-25 23:01:35', '2026-04-25 23:01:35'),
(67, 'شؤن الموظفين', 'web', '2026-04-25 23:01:35', '2026-04-25 23:01:35'),
(68, 'قرض', 'web', '2026-04-25 23:01:35', '2026-04-25 23:01:35'),
(69, 'اضافه قرض', 'web', '2026-04-25 23:01:35', '2026-04-25 23:01:35'),
(70, 'تعديل قرض', 'web', '2026-04-25 23:01:35', '2026-04-25 23:01:35'),
(71, 'حذف قرض', 'web', '2026-04-25 23:01:35', '2026-04-25 23:01:35'),
(72, 'خصم مواصلات', 'web', '2026-04-25 23:01:35', '2026-04-25 23:01:35'),
(73, 'حذف خصم مواصلات', 'web', '2026-04-25 23:01:35', '2026-04-25 23:01:35'),
(74, 'تعديل خصم مواصلات', 'web', '2026-04-25 23:01:35', '2026-04-25 23:01:35'),
(75, 'اضافه خصم مواصلات', 'web', '2026-04-25 23:01:35', '2026-04-25 23:01:35'),
(76, 'بدل مواصلات', 'web', '2026-04-25 23:01:35', '2026-04-25 23:01:35'),
(77, 'حذف بدل مواصلات', 'web', '2026-04-25 23:01:35', '2026-04-25 23:01:35'),
(78, 'تعديل بدل مواصلات', 'web', '2026-04-25 23:01:35', '2026-04-25 23:01:35'),
(79, 'اضافه بدل مواصلات', 'web', '2026-04-25 23:01:35', '2026-04-25 23:01:35'),
(80, 'التامينات', 'web', '2026-04-25 23:01:35', '2026-04-25 23:01:35'),
(81, 'اضافه تامينات', 'web', '2026-04-25 23:01:35', '2026-04-25 23:01:35'),
(82, 'حذف تامينات', 'web', '2026-04-25 23:01:35', '2026-04-25 23:01:35'),
(83, 'تعديل تامينات', 'web', '2026-04-25 23:01:35', '2026-04-25 23:01:35'),
(84, 'المشتريات', 'web', '2026-04-25 23:01:35', '2026-04-25 23:01:35'),
(85, 'اضافه مشتريات', 'web', '2026-04-25 23:01:35', '2026-04-25 23:01:35'),
(86, 'حذف مشتريات', 'web', '2026-04-25 23:01:35', '2026-04-25 23:01:35'),
(87, 'تعديل مشتريات', 'web', '2026-04-25 23:01:35', '2026-04-25 23:01:35'),
(88, 'ايام العيد', 'web', '2026-04-25 23:01:35', '2026-04-25 23:01:35'),
(89, 'اضافه عيد', 'web', '2026-04-25 23:01:35', '2026-04-25 23:01:35'),
(90, 'تعديل عيد', 'web', '2026-04-25 23:01:35', '2026-04-25 23:01:35'),
(91, 'حذف عيد', 'web', '2026-04-25 23:01:35', '2026-04-25 23:01:35'),
(92, 'عديه العيد', 'web', '2026-04-25 23:01:35', '2026-04-25 23:01:35'),
(93, 'اضافه عديه العيد', 'web', '2026-04-25 23:01:35', '2026-04-25 23:01:35'),
(94, 'تعديل عديه العيد', 'web', '2026-04-25 23:01:36', '2026-04-25 23:01:36'),
(95, 'حذف عديه العيد', 'web', '2026-04-25 23:01:36', '2026-04-25 23:01:36'),
(96, 'طلبات الموظفين ايميل', 'web', '2026-04-25 23:01:36', '2026-04-25 23:01:36'),
(97, 'اداره الوقت الاضافي', 'web', '2026-04-25 23:01:36', '2026-04-25 23:01:36');

-- --------------------------------------------------------

--
-- Table structure for table `pleases`
--

CREATE TABLE `pleases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date DEFAULT NULL,
  `in_out` int(11) DEFAULT NULL,
  `dis_value` int(11) DEFAULT NULL,
  `dis` int(11) DEFAULT NULL,
  `minutes` int(11) DEFAULT NULL,
  `dec` text DEFAULT NULL,
  `employees_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `plzots`
--

CREATE TABLE `plzots` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `numid` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `in_duty` time DEFAULT NULL,
  `out_duty` time DEFAULT NULL,
  `date` date DEFAULT NULL,
  `in` time DEFAULT NULL,
  `out` time DEFAULT NULL,
  `ot` time DEFAULT NULL,
  `othours` double DEFAULT NULL,
  `count_ot` decimal(8,2) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `latitude_in` decimal(10,8) DEFAULT NULL,
  `longitude_in` decimal(11,8) DEFAULT NULL,
  `latitude_out` decimal(10,8) DEFAULT NULL,
  `longitude_out` decimal(11,8) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `plzots`
--

INSERT INTO `plzots` (`id`, `numid`, `name`, `in_duty`, `out_duty`, `date`, `in`, `out`, `ot`, `othours`, `count_ot`, `status`, `latitude_in`, `longitude_in`, `latitude_out`, `longitude_out`, `created_at`, `updated_at`) VALUES
(3868, 1, 'gamal', '10:00:00', '19:00:00', '2026-10-15', '20:10:57', '20:12:58', NULL, 4378, 5.50, 0, NULL, NULL, NULL, NULL, '2026-10-16 18:15:34', '2026-10-16 18:15:34'),
(3869, 1, 'gamal', '10:00:00', '19:00:00', '2026-10-16', '19:07:00', '19:15:05', NULL, 905, 1.14, 0, NULL, NULL, NULL, NULL, '2026-10-16 18:15:34', '2026-10-16 18:15:34');

-- --------------------------------------------------------

--
-- Table structure for table `pricing_plans`
--

CREATE TABLE `pricing_plans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `currency` varchar(3) NOT NULL DEFAULT 'USD',
  `trial_days` int(11) NOT NULL DEFAULT 14,
  `duration_days` int(11) NOT NULL DEFAULT 30,
  `max_employees` int(11) NOT NULL DEFAULT 10,
  `features` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pricing_plans`
--

INSERT INTO `pricing_plans` (`id`, `name`, `description`, `price`, `currency`, `trial_days`, `duration_days`, `max_employees`, `features`, `is_active`, `is_featured`, `created_at`, `updated_at`) VALUES
(13, 'الخطه المجانيه', 'مناسبة للشركات الناشئة لتجربة النظام بالكامل', 0.00, 'EGP', 14, 14, 3, '[\"\\u0639\\u062f\\u062f \\u0627\\u0644\\u0645\\u0648\\u0638\\u0641\\u064a\\u0646 10 \\u0641\\u0642\\u0637\",\"\\u0645\\u062a\\u0627\\u062d \\u0644\\u0644\\u0628\\u0635\\u0645\\u0647 \\u0645\\u0646 \\u062e\\u0644\\u0627\\u0644 \\u0627\\u0644\\u0645\\u0648\\u0628\\u064a\\u0644\",\"\\u0627\\u0646\\u0634\\u0627\\u0621 \\u0644\\u0627\\u0626\\u062d\\u0647 \\u062b\\u0627\\u0628\\u062a\\u0639 \\u0644\\u0644\\u0645\\u0648\\u0638\\u0641\\u064a\\u0646\",\"\\u062a\\u062d\\u0643\\u0645 \\u0643\\u0627\\u0645\\u0644 \\u0641\\u064a \\u0627\\u0644\\u0631\\u0648\\u0627\\u062a\\u0628 \\u0648\\u0627\\u0644\\u0645\\u0644\\u0628\\u0627\\u062a \\u0644\\u0644\\u0645\\u0648\\u0638\\u0641\\u064a\\u0646\"]', 1, 0, '2026-06-15 19:25:57', '2026-06-19 00:30:17'),
(14, 'الخطه النص سنويه', 'مناسبة للشركات المتوسطه', 5000.00, 'EGP', 0, 180, 40, '[\"\\u0639\\u062f\\u062f \\u0627\\u0644\\u0645\\u0648\\u0638\\u0641\\u064a\\u0646 40\",\"\\u062a\\u062d\\u0643\\u0645 \\u0643\\u0627\\u0645\\u0644 \\u0641\\u064a \\u0627\\u0644\\u0645\\u0631\\u062a\\u0628\",\"\\u0641\\u064a \\u062d\\u0627\\u0644\\u0647 \\u0648\\u062c\\u0648\\u062f \\u0628\\u0635\\u0645\\u0647 zkt \\u0641\\u064a \\u0627\\u0644\\u0645\\u0648\\u0642\\u0639 \\u064a\\u0645\\u0643\\u0646 \\u0627\\u0644\\u062a\\u0648\\u0627\\u0635\\u0644 \\u0645\\u0639 \\u0627\\u0644\\u0634\\u0631\\u0643\\u0647\"]', 1, 1, '2026-06-15 19:29:44', '2026-06-30 16:38:45'),
(15, 'سنويه', 'جامده جداا', 10000.00, 'EGP', 0, 365, 10, '[\"\\u0628\\u0644\\u0627\\u0644\\u064a\\u0628\\u0644\\u064a\\u0628\\u0644\\u0628\\u064a\\u0644\\u064a\\u0628\\u0644\",\"\\u0628\\u064a\\u0644\\u0623][\\u0644\\u0623\\u064a\\u0628\",\"\\u0644\\u0623\",\"\\u0628\\u064a\\u0644\\u0623\",\"\\u064a\\u0628\\u0644\\u0623\",\"\\u064a\\u0628\\u0644\",\"][\\u0644\"]', 1, 1, '2026-06-24 23:11:05', '2026-06-24 23:11:05');

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mony` int(11) DEFAULT NULL,
  `dec` text DEFAULT NULL,
  `date` date DEFAULT NULL,
  `employees_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'owner', 'web', '2026-04-25 23:01:36', '2026-04-25 23:01:36');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(41, 1),
(42, 1),
(43, 1),
(44, 1),
(45, 1),
(46, 1),
(47, 1),
(48, 1),
(49, 1),
(50, 1),
(51, 1),
(52, 1),
(53, 1),
(54, 1),
(55, 1),
(56, 1),
(57, 1),
(58, 1),
(59, 1),
(60, 1),
(61, 1),
(62, 1),
(63, 1),
(64, 1),
(65, 1),
(66, 1),
(67, 1),
(68, 1),
(69, 1),
(70, 1),
(71, 1),
(72, 1),
(73, 1),
(74, 1),
(75, 1),
(76, 1),
(77, 1),
(78, 1),
(79, 1),
(80, 1),
(81, 1),
(82, 1),
(83, 1),
(84, 1),
(85, 1),
(86, 1),
(87, 1),
(88, 1),
(89, 1),
(90, 1),
(91, 1),
(92, 1),
(93, 1),
(94, 1),
(95, 1),
(96, 1),
(97, 1);

-- --------------------------------------------------------

--
-- Table structure for table `salves`
--

CREATE TABLE `salves` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `salf` double DEFAULT NULL,
  `date` date DEFAULT NULL,
  `dec` text DEFAULT NULL,
  `employees_id` bigint(20) UNSIGNED NOT NULL,
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `salves`
--

INSERT INTO `salves` (`id`, `name`, `salf`, `date`, `dec`, `employees_id`, `section_id`, `created_at`, `updated_at`) VALUES
(299, NULL, 100, '2026-10-16', '000', 32, 4, '2026-10-16 18:15:23', '2026-10-16 18:15:23');

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `name`, `created_at`, `updated_at`) VALUES
(4, 'AAAA', '2026-08-10 01:10:05', '2026-08-10 01:10:05');

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
('RsvrJr2trXGdH4bT4ns542IN0aqvSid4WZL5SXGq', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36 Edg/155.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiNm5MQjlyZ3N6azh1dTVtVldDOW9rUTRiaW5jSWRoQWVsMXg3YzR6YyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM2OiJodHRwOi8vc2F5YWQ6NzMvbm90aWZpY2F0aW9ucy9sYXRlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1792178140);

-- --------------------------------------------------------

--
-- Table structure for table `shifts`
--

CREATE TABLE `shifts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `from` time DEFAULT NULL,
  `to` time DEFAULT NULL,
  `shift_time` int(11) DEFAULT NULL,
  `late` double DEFAULT NULL,
  `late1` double DEFAULT NULL,
  `late2` double DEFAULT NULL,
  `late3` double DEFAULT NULL,
  `bdry` double DEFAULT NULL,
  `workh` double DEFAULT NULL,
  `dism` double DEFAULT NULL,
  `dism1` double DEFAULT NULL,
  `dism2` double DEFAULT NULL,
  `dism3` double DEFAULT NULL,
  `disout` double DEFAULT NULL,
  `break_in` time DEFAULT NULL,
  `break_out` time DEFAULT NULL,
  `b_out_dis` double DEFAULT NULL,
  `b_in_dis` double DEFAULT NULL,
  `absent` double DEFAULT NULL,
  `start_ot` time DEFAULT NULL,
  `ot` double NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shifts`
--

INSERT INTO `shifts` (`id`, `name`, `from`, `to`, `shift_time`, `late`, `late1`, `late2`, `late3`, `bdry`, `workh`, `dism`, `dism1`, `dism2`, `dism3`, `disout`, `break_in`, `break_out`, `b_out_dis`, `b_in_dis`, `absent`, `start_ot`, `ot`, `created_at`, `updated_at`) VALUES
(7, 'SHIFT', '10:00:00', '19:00:00', 1, 15, 120, 0, 0, 0, 9, 60, 300, 0, 0, 0, NULL, NULL, NULL, NULL, 1, '19:00:00', 1, '2026-08-10 01:11:02', '2026-10-15 21:01:19');

-- --------------------------------------------------------

--
-- Table structure for table `shift_weeks`
--

CREATE TABLE `shift_weeks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `shift_id` bigint(20) UNSIGNED NOT NULL,
  `employees_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sup_shifts`
--

CREATE TABLE `sup_shifts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `shift_id` bigint(20) UNSIGNED NOT NULL,
  `override_shift_id` bigint(20) UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `taminats`
--

CREATE TABLE `taminats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mony` int(11) DEFAULT NULL,
  `employees_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tenants`
--

CREATE TABLE `tenants` (
  `id` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `pass` varchar(255) DEFAULT NULL,
  `subscription_start_at` timestamp NULL DEFAULT NULL,
  `subscription_end_at` timestamp NULL DEFAULT NULL,
  `plan` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `requested_plan` int(11) DEFAULT NULL,
  `requested_plan_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tenants`
--

INSERT INTO `tenants` (`id`, `email`, `pass`, `subscription_start_at`, `subscription_end_at`, `plan`, `phone`, `created_at`, `updated_at`, `data`, `status`, `requested_plan`, `requested_plan_at`) VALUES
('pixel', 'katryana22@gmail.com', 'aaaa1111', '2026-06-30 15:32:09', '2026-12-27 16:32:09', '14', '01121483373', '2026-06-30 15:32:09', '2026-06-30 16:25:12', '{\"tenancy_db_name\":\"tenantpixel\",\"company_name\":\"pixel\",\"owner_name\":\"ahmed elkatry\",\"password\":\"aaaa1111\"}', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tlbpleases`
--

CREATE TABLE `tlbpleases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `num` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `datesalf` date DEFAULT NULL,
  `dateshift` date DEFAULT NULL,
  `dateshift_end` date DEFAULT NULL,
  `dateplz` date DEFAULT NULL,
  `in_out` int(11) DEFAULT NULL,
  `min` int(11) DEFAULT NULL,
  `dis` double DEFAULT NULL,
  `dis_value` double DEFAULT NULL,
  `textfree` text DEFAULT NULL,
  `textshift` text DEFAULT NULL,
  `textsalf` text DEFAULT NULL,
  `textplz` text DEFAULT NULL,
  `salf` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `shift_id` bigint(20) UNSIGNED NOT NULL,
  `employees_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `num` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `user_agent_mobile` varchar(255) DEFAULT NULL,
  `roles_name` text NOT NULL,
  `Status` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `num`, `email`, `phone`, `email_verified_at`, `password`, `user_agent`, `user_agent_mobile`, `roles_name`, `Status`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'ahmed elkatry', NULL, 'katryana22@gmail.com', '01121483373', NULL, '$2y$12$CRxz/tbiV5cz0bfpd/2tduO9Z9cVGPw.GQ5PaZ7MzKCY9SOg7cq3W', NULL, NULL, '[\"owner\"]', 'مفعل', '8LSJhqhNWzKXEU3manMEXr0MS25sZz3m5pLORoKa7xhHeibU4k0CwgBJZqxA', '2025-05-23 18:50:51', '2025-05-23 18:50:51'),
(22, 'pixel', NULL, 'pixel@pixel.com', '01101101100', NULL, '$2y$12$8KoAZPwY65OjfapKSjbimOahLB1WcMkW6HjmyFnzq78Tf1JdkPhme', '[\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/151.0.0.0 Safari\\/537.36\"]', NULL, '[\"owner\"]', 'مفعل', NULL, '2026-08-10 00:51:24', '2026-08-10 00:52:31');

-- --------------------------------------------------------

--
-- Table structure for table `vacations`
--

CREATE TABLE `vacations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date DEFAULT NULL,
  `dec` text DEFAULT NULL,
  `disvac` int(11) DEFAULT NULL,
  `employees_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `zkts`
--

CREATE TABLE `zkts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `numid` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `absent` varchar(255) DEFAULT NULL,
  `in` time DEFAULT NULL,
  `out` time DEFAULT NULL,
  `break_in` time DEFAULT NULL,
  `break_out` time DEFAULT NULL,
  `date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `latitude_in` decimal(10,8) DEFAULT NULL,
  `longitude_in` decimal(11,8) DEFAULT NULL,
  `latitude_out` decimal(10,8) DEFAULT NULL,
  `longitude_out` decimal(11,8) DEFAULT NULL,
  `latitude_break_in` decimal(10,8) DEFAULT NULL,
  `longitude_break_in` decimal(11,8) DEFAULT NULL,
  `latitude_break_out` decimal(10,8) DEFAULT NULL,
  `longitude_break_out` decimal(11,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `zkts`
--

INSERT INTO `zkts` (`id`, `numid`, `name`, `absent`, `in`, `out`, `break_in`, `break_out`, `date`, `created_at`, `updated_at`, `latitude_in`, `longitude_in`, `latitude_out`, `longitude_out`, `latitude_break_in`, `longitude_break_in`, `latitude_break_out`, `longitude_break_out`) VALUES
(499, 1, 'gamal', 'حاضر', '19:07:00', '19:15:05', NULL, NULL, '2026-10-16', '2026-10-16 17:14:50', '2026-10-16 18:14:06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(500, 1, 'gamal', 'حاضر', '15:11:29', '15:11:35', NULL, NULL, '2026-10-08', '2026-10-16 18:14:06', '2026-10-16 18:14:06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(501, 1, 'gamal', 'حاضر', '20:10:57', '20:12:58', NULL, NULL, '2026-10-15', '2026-10-16 18:14:06', '2026-10-16 18:14:06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `zkt_commands`
--

CREATE TABLE `zkt_commands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sn` varchar(255) NOT NULL,
  `command` text NOT NULL,
  `executed` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `zkt_commands`
--

INSERT INTO `zkt_commands` (`id`, `sn`, `command`, `executed`, `created_at`, `updated_at`) VALUES
(6, 'A6F5224260946', 'DATA USER PIN=1	Name=aaaa	Pri=0', 1, '2026-08-10 01:15:07', '2026-08-10 01:15:18'),
(7, 'A6F5224260946', 'DATA DELETE userinfo PIN=1', 1, '2026-08-10 01:15:41', '2026-08-10 01:15:47'),
(8, 'A6F5224260946', 'DATA USER PIN=1	Name=wwww	Pri=0', 1, '2026-08-10 01:16:25', '2026-08-10 01:16:33'),
(9, 'A6F5224260946', 'DATA USER PIN=1	Name=wwww	Pri=0', 1, '2026-08-10 01:17:13', '2026-08-10 01:17:18'),
(10, 'A6F5224260946', 'DATA DELETE userinfo PIN=1', 1, '2026-08-10 01:17:24', '2026-08-10 01:17:28'),
(11, 'A6F5224260946', 'DATA USER PIN=1	Name=eeee	Pri=0', 1, '2026-08-10 01:18:28', '2026-08-10 01:18:41'),
(12, 'A6F5224260946', 'DATA USER PIN=1	Name=ahmed	Pri=0', 0, '2026-10-16 15:52:02', '2026-10-16 15:52:02'),
(13, 'A6F5224260946', 'DATA DELETE userinfo PIN=1', 0, '2026-10-16 15:59:14', '2026-10-16 15:59:14'),
(14, 'A6F5224260946', 'DATA USER PIN=1	Name=ahmed	Pri=0', 0, '2026-10-16 16:34:53', '2026-10-16 16:34:53'),
(15, 'A6F5224260946', 'DATA DELETE userinfo PIN=1', 0, '2026-10-16 16:35:34', '2026-10-16 16:35:34'),
(16, 'A6F5224260946', 'DATA DELETE userinfo PIN=1', 0, '2026-10-16 16:39:35', '2026-10-16 16:39:35'),
(17, 'A6F5224260946', 'DATA USER PIN=1	Name=gamal	Pri=0', 0, '2026-10-16 17:05:00', '2026-10-16 17:05:00'),
(18, 'A6F5224260946', 'DATA USER PIN=1	Name=gamal	Pri=0', 0, '2026-10-16 17:10:59', '2026-10-16 17:10:59');

-- --------------------------------------------------------

--
-- Table structure for table `zkt_deleted_users`
--

CREATE TABLE `zkt_deleted_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `num` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `device_key` varchar(255) DEFAULT NULL,
  `executed` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `zkt_deleted_users`
--

INSERT INTO `zkt_deleted_users` (`id`, `num`, `name`, `device_key`, `executed`, `created_at`, `updated_at`) VALUES
(3, '1', 'katry', NULL, 1, '2026-10-16 16:39:35', '2026-10-16 16:40:29');

-- --------------------------------------------------------

--
-- Table structure for table `zkt_device_deletions`
--

CREATE TABLE `zkt_device_deletions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `device_key` varchar(191) NOT NULL,
  `emp_num` varchar(191) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `zkt_device_deletions`
--

INSERT INTO `zkt_device_deletions` (`id`, `device_key`, `emp_num`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'default', '1', '2026-10-16 17:04:00', NULL, '2026-10-16 17:04:00'),
(4, 'جهاز الفرع الرئيسي', '1', '2026-10-16 17:04:37', NULL, '2026-10-16 17:04:37');

-- --------------------------------------------------------

--
-- Table structure for table `zkt_device_sync`
--

CREATE TABLE `zkt_device_sync` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `device_key` varchar(191) NOT NULL,
  `emp_num` varchar(191) NOT NULL,
  `synced_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `zkt_device_sync`
--

INSERT INTO `zkt_device_sync` (`id`, `device_key`, `emp_num`, `synced_at`, `created_at`, `updated_at`) VALUES
(4, 'جهاز الفرع الرئيسي', '1', '2026-10-16 17:11:47', NULL, '2026-10-16 17:11:47');

-- --------------------------------------------------------

--
-- Table structure for table `zkt_synced_device_users`
--

CREATE TABLE `zkt_synced_device_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `device_key` varchar(191) NOT NULL,
  `num` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `badel_transports`
--
ALTER TABLE `badel_transports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `badel_transports_employees_id_foreign` (`employees_id`);

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
-- Indexes for table `central_admins`
--
ALTER TABLE `central_admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `central_admins_email_unique` (`email`),
  ADD KEY `central_admins_central_admin_role_id_foreign` (`central_admin_role_id`);

--
-- Indexes for table `central_admin_roles`
--
ALTER TABLE `central_admin_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `central_admin_roles_slug_unique` (`slug`);

--
-- Indexes for table `change_shifts`
--
ALTER TABLE `change_shifts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `change_shifts_shift_id_foreign` (`shift_id`),
  ADD KEY `change_shifts_employees_id_foreign` (`employees_id`);

--
-- Indexes for table `company_locations`
--
ALTER TABLE `company_locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countips`
--
ALTER TABLE `countips`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `counts`
--
ALTER TABLE `counts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `counts_shift_id_foreign` (`shift_id`),
  ADD KEY `counts_section_id_foreign` (`section_id`),
  ADD KEY `counts_employees_id_foreign` (`employees_id`);

--
-- Indexes for table `counts_emps`
--
ALTER TABLE `counts_emps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `disemps`
--
ALTER TABLE `disemps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `disemps_employees_id_foreign` (`employees_id`),
  ADD KEY `disemps_section_id_foreign` (`section_id`);

--
-- Indexes for table `dis_transports`
--
ALTER TABLE `dis_transports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dis_transports_employees_id_foreign` (`employees_id`);

--
-- Indexes for table `domains`
--
ALTER TABLE `domains`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `domains_domain_unique` (`domain`),
  ADD KEY `domains_tenant_id_foreign` (`tenant_id`);

--
-- Indexes for table `eids`
--
ALTER TABLE `eids`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employees_num_unique` (`num`),
  ADD UNIQUE KEY `employees_email_unique` (`email`),
  ADD KEY `employees_section_id_foreign` (`section_id`),
  ADD KEY `employees_shift_id_foreign` (`shift_id`);

--
-- Indexes for table `employee_allowed_locations`
--
ALTER TABLE `employee_allowed_locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_allowed_locations_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `employee_company_location`
--
ALTER TABLE `employee_company_location`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_company_location_employee_id_foreign` (`employee_id`),
  ADD KEY `employee_company_location_company_location_id_foreign` (`company_location_id`);

--
-- Indexes for table `employee_locations`
--
ALTER TABLE `employee_locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_locations_employees_id_foreign` (`employees_id`);

--
-- Indexes for table `evaluations`
--
ALTER TABLE `evaluations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `forget_fingers`
--
ALTER TABLE `forget_fingers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `forget_fingers_employees_id_foreign` (`employees_id`);

--
-- Indexes for table `hafzeids`
--
ALTER TABLE `hafzeids`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hafzs`
--
ALTER TABLE `hafzs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hafzs_employees_id_foreign` (`employees_id`),
  ADD KEY `hafzs_section_id_foreign` (`section_id`);

--
-- Indexes for table `head_sections`
--
ALTER TABLE `head_sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `head_sections_shift_id_foreign` (`shift_id`),
  ADD KEY `head_sections_section_id_foreign` (`section_id`);

--
-- Indexes for table `ipzkts`
--
ALTER TABLE `ipzkts`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `loans_employees_id_foreign` (`employees_id`);

--
-- Indexes for table `location_requests`
--
ALTER TABLE `location_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `manager_section`
--
ALTER TABLE `manager_section`
  ADD PRIMARY KEY (`id`),
  ADD KEY `manager_section_employee_id_foreign` (`employee_id`),
  ADD KEY `manager_section_section_id_foreign` (`section_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mikros`
--
ALTER TABLE `mikros`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `nozkts`
--
ALTER TABLE `nozkts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nozkts_numid_date_index` (`numid`,`date`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `pleases`
--
ALTER TABLE `pleases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pleases_employees_id_foreign` (`employees_id`);

--
-- Indexes for table `plzots`
--
ALTER TABLE `plzots`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pricing_plans`
--
ALTER TABLE `pricing_plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchases_employees_id_foreign` (`employees_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `salves`
--
ALTER TABLE `salves`
  ADD PRIMARY KEY (`id`),
  ADD KEY `salves_employees_id_foreign` (`employees_id`),
  ADD KEY `salves_section_id_foreign` (`section_id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `shifts`
--
ALTER TABLE `shifts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shift_weeks`
--
ALTER TABLE `shift_weeks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shift_weeks_shift_id_foreign` (`shift_id`),
  ADD KEY `shift_weeks_employees_id_foreign` (`employees_id`);

--
-- Indexes for table `sup_shifts`
--
ALTER TABLE `sup_shifts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sup_shifts_shift_id_foreign` (`shift_id`),
  ADD KEY `sup_shifts_override_shift_id_foreign` (`override_shift_id`);

--
-- Indexes for table `taminats`
--
ALTER TABLE `taminats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `taminats_employees_id_foreign` (`employees_id`);

--
-- Indexes for table `tenants`
--
ALTER TABLE `tenants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tenants_email_unique` (`email`);

--
-- Indexes for table `tlbpleases`
--
ALTER TABLE `tlbpleases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tlbpleases_shift_id_foreign` (`shift_id`),
  ADD KEY `tlbpleases_employees_id_foreign` (`employees_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `vacations`
--
ALTER TABLE `vacations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vacations_employees_id_foreign` (`employees_id`);

--
-- Indexes for table `zkts`
--
ALTER TABLE `zkts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `zkts_numid_date_index` (`numid`,`date`);

--
-- Indexes for table `zkt_commands`
--
ALTER TABLE `zkt_commands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zkt_deleted_users`
--
ALTER TABLE `zkt_deleted_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zkt_device_deletions`
--
ALTER TABLE `zkt_device_deletions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `zkt_device_deletions_device_key_emp_num_unique` (`device_key`,`emp_num`);

--
-- Indexes for table `zkt_device_sync`
--
ALTER TABLE `zkt_device_sync`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `zkt_device_sync_device_key_emp_num_unique` (`device_key`,`emp_num`);

--
-- Indexes for table `zkt_synced_device_users`
--
ALTER TABLE `zkt_synced_device_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `zkt_synced_device_users_device_key_num_unique` (`device_key`,`num`),
  ADD KEY `zkt_synced_device_users_device_key_index` (`device_key`),
  ADD KEY `zkt_synced_device_users_num_index` (`num`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `badel_transports`
--
ALTER TABLE `badel_transports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `central_admins`
--
ALTER TABLE `central_admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `central_admin_roles`
--
ALTER TABLE `central_admin_roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `change_shifts`
--
ALTER TABLE `change_shifts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `company_locations`
--
ALTER TABLE `company_locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `countips`
--
ALTER TABLE `countips`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `counts`
--
ALTER TABLE `counts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `counts_emps`
--
ALTER TABLE `counts_emps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=211;

--
-- AUTO_INCREMENT for table `disemps`
--
ALTER TABLE `disemps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `dis_transports`
--
ALTER TABLE `dis_transports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `domains`
--
ALTER TABLE `domains`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `eids`
--
ALTER TABLE `eids`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `employee_allowed_locations`
--
ALTER TABLE `employee_allowed_locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_company_location`
--
ALTER TABLE `employee_company_location`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_locations`
--
ALTER TABLE `employee_locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `forget_fingers`
--
ALTER TABLE `forget_fingers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=225;

--
-- AUTO_INCREMENT for table `hafzeids`
--
ALTER TABLE `hafzeids`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hafzs`
--
ALTER TABLE `hafzs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `head_sections`
--
ALTER TABLE `head_sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ipzkts`
--
ALTER TABLE `ipzkts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `location_requests`
--
ALTER TABLE `location_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `manager_section`
--
ALTER TABLE `manager_section`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `mikros`
--
ALTER TABLE `mikros`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nozkts`
--
ALTER TABLE `nozkts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `pleases`
--
ALTER TABLE `pleases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `plzots`
--
ALTER TABLE `plzots`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3870;

--
-- AUTO_INCREMENT for table `pricing_plans`
--
ALTER TABLE `pricing_plans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `salves`
--
ALTER TABLE `salves`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=300;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `shifts`
--
ALTER TABLE `shifts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `shift_weeks`
--
ALTER TABLE `shift_weeks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sup_shifts`
--
ALTER TABLE `sup_shifts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `taminats`
--
ALTER TABLE `taminats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tlbpleases`
--
ALTER TABLE `tlbpleases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `vacations`
--
ALTER TABLE `vacations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `zkts`
--
ALTER TABLE `zkts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=502;

--
-- AUTO_INCREMENT for table `zkt_commands`
--
ALTER TABLE `zkt_commands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `zkt_deleted_users`
--
ALTER TABLE `zkt_deleted_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `zkt_device_deletions`
--
ALTER TABLE `zkt_device_deletions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `zkt_device_sync`
--
ALTER TABLE `zkt_device_sync`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `zkt_synced_device_users`
--
ALTER TABLE `zkt_synced_device_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `badel_transports`
--
ALTER TABLE `badel_transports`
  ADD CONSTRAINT `badel_transports_employees_id_foreign` FOREIGN KEY (`employees_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `central_admins`
--
ALTER TABLE `central_admins`
  ADD CONSTRAINT `central_admins_central_admin_role_id_foreign` FOREIGN KEY (`central_admin_role_id`) REFERENCES `central_admin_roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `change_shifts`
--
ALTER TABLE `change_shifts`
  ADD CONSTRAINT `change_shifts_employees_id_foreign` FOREIGN KEY (`employees_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `change_shifts_shift_id_foreign` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `counts`
--
ALTER TABLE `counts`
  ADD CONSTRAINT `counts_employees_id_foreign` FOREIGN KEY (`employees_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `counts_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `counts_shift_id_foreign` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `disemps`
--
ALTER TABLE `disemps`
  ADD CONSTRAINT `disemps_employees_id_foreign` FOREIGN KEY (`employees_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `disemps_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `dis_transports`
--
ALTER TABLE `dis_transports`
  ADD CONSTRAINT `dis_transports_employees_id_foreign` FOREIGN KEY (`employees_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `domains`
--
ALTER TABLE `domains`
  ADD CONSTRAINT `domains_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `employees_shift_id_foreign` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employee_allowed_locations`
--
ALTER TABLE `employee_allowed_locations`
  ADD CONSTRAINT `employee_allowed_locations_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employee_company_location`
--
ALTER TABLE `employee_company_location`
  ADD CONSTRAINT `employee_company_location_company_location_id_foreign` FOREIGN KEY (`company_location_id`) REFERENCES `company_locations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `employee_company_location_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employee_locations`
--
ALTER TABLE `employee_locations`
  ADD CONSTRAINT `employee_locations_employees_id_foreign` FOREIGN KEY (`employees_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `forget_fingers`
--
ALTER TABLE `forget_fingers`
  ADD CONSTRAINT `forget_fingers_employees_id_foreign` FOREIGN KEY (`employees_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hafzs`
--
ALTER TABLE `hafzs`
  ADD CONSTRAINT `hafzs_employees_id_foreign` FOREIGN KEY (`employees_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hafzs_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `head_sections`
--
ALTER TABLE `head_sections`
  ADD CONSTRAINT `head_sections_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `head_sections_shift_id_foreign` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `loans`
--
ALTER TABLE `loans`
  ADD CONSTRAINT `loans_employees_id_foreign` FOREIGN KEY (`employees_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `manager_section`
--
ALTER TABLE `manager_section`
  ADD CONSTRAINT `manager_section_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `manager_section_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pleases`
--
ALTER TABLE `pleases`
  ADD CONSTRAINT `pleases_employees_id_foreign` FOREIGN KEY (`employees_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_employees_id_foreign` FOREIGN KEY (`employees_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `salves`
--
ALTER TABLE `salves`
  ADD CONSTRAINT `salves_employees_id_foreign` FOREIGN KEY (`employees_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `salves_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shift_weeks`
--
ALTER TABLE `shift_weeks`
  ADD CONSTRAINT `shift_weeks_employees_id_foreign` FOREIGN KEY (`employees_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shift_weeks_shift_id_foreign` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sup_shifts`
--
ALTER TABLE `sup_shifts`
  ADD CONSTRAINT `sup_shifts_override_shift_id_foreign` FOREIGN KEY (`override_shift_id`) REFERENCES `shifts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sup_shifts_shift_id_foreign` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `taminats`
--
ALTER TABLE `taminats`
  ADD CONSTRAINT `taminats_employees_id_foreign` FOREIGN KEY (`employees_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tlbpleases`
--
ALTER TABLE `tlbpleases`
  ADD CONSTRAINT `tlbpleases_employees_id_foreign` FOREIGN KEY (`employees_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tlbpleases_shift_id_foreign` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vacations`
--
ALTER TABLE `vacations`
  ADD CONSTRAINT `vacations_employees_id_foreign` FOREIGN KEY (`employees_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
