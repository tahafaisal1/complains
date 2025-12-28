-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 28, 2025 at 12:28 PM
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
-- Database: `complaints_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) UNSIGNED NOT NULL,
  `complaint_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL COMMENT 'المستخدم الذي قام بالإجراء',
  `action` varchar(100) NOT NULL COMMENT 'نوع الإجراء: created, status_changed, note_added, assigned, responded',
  `description` text NOT NULL COMMENT 'وصف الإجراء',
  `old_value` varchar(255) DEFAULT NULL,
  `new_value` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `complaint_id`, `user_id`, `action`, `description`, `old_value`, `new_value`, `created_at`) VALUES
(1, 6, 5, 'message_sent', 'تم إرسال رسالة', NULL, NULL, '2025-12-14 13:12:00'),
(2, 6, 1, 'message_sent', 'تم إرسال رسالة', NULL, NULL, '2025-12-14 13:12:45'),
(3, 6, 1, 'message_sent', 'تم إرسال رسالة', NULL, NULL, '2025-12-14 13:12:49'),
(4, 6, 1, 'status_changed', 'تم تغيير الحالة من \'مفتوح\' إلى \'تم الحل\'', 'open', 'resolved', '2025-12-14 13:13:00'),
(5, 7, 5, 'message_sent', 'تم إرسال رسالة', NULL, NULL, '2025-12-14 14:00:49'),
(6, 9, 1, 'status_changed', 'تم تغيير الحالة من \'مفتوح\' إلى \'تحت المعالجة\'', 'open', 'in_progress', '2025-12-27 10:05:22'),
(7, 10, 1, 'status_changed', 'تم تغيير الحالة من \'مفتوح\' إلى \'تحت المعالجة\'', 'open', 'in_progress', '2025-12-27 10:39:16'),
(8, 10, 1, 'status_changed', 'تم تغيير الحالة من \'تحت المعالجة\' إلى \'مفتوح\'', 'in_progress', 'open', '2025-12-27 10:43:10'),
(9, 10, 1, 'status_changed', 'تم تغيير الحالة من \'مفتوح\' إلى \'تحت المعالجة\'', 'open', 'in_progress', '2025-12-27 10:47:54'),
(10, 10, 1, 'status_changed', 'تم تغيير الحالة من \'تحت المعالجة\' إلى \'تم الحل\'', 'in_progress', 'resolved', '2025-12-27 10:52:38'),
(11, 10, 1, 'status_changed', 'تم تغيير الحالة من \'تم الحل\' إلى \'مغلق\'', 'resolved', 'closed', '2025-12-27 10:59:07'),
(12, 10, 1, 'status_changed', 'تم تغيير الحالة من \'مغلق\' إلى \'مغلق\'', 'closed', 'closed', '2025-12-27 11:07:04'),
(13, 10, 1, 'status_changed', 'تم تغيير الحالة من \'مغلق\' إلى \'تم الحل\'', 'closed', 'resolved', '2025-12-27 11:07:12'),
(14, 10, 1, 'status_changed', 'تم تغيير الحالة من \'تم الحل\' إلى \'تحت المعالجة\'', 'resolved', 'in_progress', '2025-12-28 10:37:45'),
(15, 10, 1, 'status_changed', 'تم تغيير الحالة من \'تحت المعالجة\' إلى \'مفتوح\'', 'in_progress', 'open', '2025-12-28 10:38:11');

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `category` varchar(100) NOT NULL COMMENT 'نوع البلاغ: أكاديمي، إداري، تقني، مالي، أخرى',
  `priority` enum('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
  `status` enum('open','in_progress','resolved','closed') NOT NULL DEFAULT 'open',
  `assigned_to` int(11) UNSIGNED DEFAULT NULL COMMENT 'الإداري المسؤول عن البلاغ',
  `attachment` varchar(255) DEFAULT NULL COMMENT 'مسار الملف المرفق',
  `admin_response` text DEFAULT NULL,
  `resolved_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `rating` tinyint(4) DEFAULT NULL COMMENT 'User satisfaction rating 1-5',
  `rating_comment` text DEFAULT NULL COMMENT 'Optional feedback comment',
  `rated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `user_id`, `title`, `description`, `category`, `priority`, `status`, `assigned_to`, `attachment`, `admin_response`, `resolved_at`, `created_at`, `updated_at`, `rating`, `rating_comment`, `rated_at`) VALUES
(1, 2, 'مشكلة في التسجيل للمواد', 'لا أستطيع التسجيل في مادة البرمجة المتقدمة رغم توفر المتطلبات السابقة. يظهر لي رسالة خطأ عند محاولة التسجيل.', 'أكاديمي', 'high', 'open', NULL, NULL, NULL, NULL, '2025-12-09 12:24:40', '2025-12-09 12:24:40', NULL, NULL, NULL),
(2, 2, 'طلب شهادة حضور', 'أحتاج شهادة تثبت حضوري للفصل الدراسي الحالي لتقديمها لجهة عمل.', 'إداري', 'medium', 'in_progress', 1, NULL, NULL, NULL, '2025-12-11 12:24:40', '2025-12-13 12:24:40', NULL, NULL, NULL),
(3, 3, 'مشكلة في بوابة الطالب', 'لا أستطيع الدخول لبوابة الطالب منذ يومين. تظهر رسالة أن الحساب معطل.', 'تقني', 'urgent', 'open', 1, NULL, 'hhhh', NULL, '2025-12-12 12:24:40', '2025-12-15 22:06:16', NULL, NULL, NULL),
(4, 3, 'استفسار عن الرسوم الدراسية', 'أريد الاستفسار عن تفاصيل الرسوم الدراسية وموعد السداد للفصل القادم.', 'مالي', 'low', 'resolved', 1, NULL, 'تم إرسال تفاصيل الرسوم على بريدك الإلكتروني. موعد السداد هو 15 من الشهر القادم.', '2025-12-13 12:24:40', '2025-12-07 12:24:40', '2025-12-13 12:24:40', NULL, NULL, NULL),
(5, 4, 'اقتراح تحسين المكتبة', 'أقترح زيادة ساعات عمل المكتبة خلال فترة الاختبارات لتكون متاحة حتى منتصف الليل.', 'أخرى', 'low', 'closed', 1, NULL, 'شكراً لاقتراحك. تم رفع الاقتراح للإدارة العليا وسيتم دراسته.', '2025-12-12 12:24:40', '2025-12-04 12:24:40', '2025-12-12 12:24:40', NULL, NULL, NULL),
(6, 5, 'nlnjkjljh', 'luuhyliugl', 'إداري', 'medium', 'resolved', 1, NULL, NULL, '2025-12-14 13:13:00', '2025-12-14 13:11:45', '2025-12-14 13:13:00', NULL, NULL, NULL),
(7, 5, 'موفق السيارات غير منظم ', 'ان لا يوجد الية في تدريس ', 'أخرى', 'high', 'open', NULL, NULL, NULL, NULL, '2025-12-14 13:59:29', '2025-12-14 13:59:29', NULL, NULL, NULL),
(8, 2, 'tsretseysy', 'setsryerydeydey', 'تقني', 'medium', 'open', NULL, '1766007030_71fae8781b166f0660f5.pdf', NULL, NULL, '2025-12-17 21:30:30', '2025-12-17 21:30:30', NULL, NULL, NULL),
(9, 2, 'avadv', 'dsgsbssfbdsbsdbsteste', 'أكاديمي', 'medium', 'in_progress', 1, '1766009211_4bc7629ad41a1a787b40.pdf', NULL, NULL, '2025-12-17 22:06:51', '2025-12-27 10:05:22', NULL, NULL, NULL),
(10, 6, 'ttttttt', 'tttttttttttttttttttt', 'أكاديمي', 'low', 'open', 1, NULL, NULL, '2025-12-27 11:07:12', '2025-12-27 10:38:17', '2025-12-28 10:38:11', 3, '', '2025-12-27 11:45:30');

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` int(11) UNSIGNED NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `category` varchar(100) DEFAULT NULL COMMENT 'تصنيف السؤال',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `views` int(11) NOT NULL DEFAULT 0 COMMENT 'عدد المشاهدات',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `answer`, `category`, `sort_order`, `is_active`, `views`, `created_at`, `updated_at`) VALUES
(1, 'كيف أرسل بلاغ جديد؟', 'يمكنك إرسال بلاغ جديد عن طريق تسجيل الدخول ثم الضغط على \"إرسال بلاغ جديد\" من القائمة الجانبية. قم بملء النموذج بالتفاصيل المطلوبة واضغط إرسال.', 'عام', 1, 1, 0, '2025-12-14 12:26:38', '2025-12-14 12:26:38'),
(2, 'كم المدة المتوقعة للرد على البلاغ؟', 'عادة يتم الرد على البلاغات خلال 2-3 أيام عمل. البلاغات العاجلة يتم التعامل معها بشكل أسرع.', 'عام', 2, 1, 0, '2025-12-14 12:26:38', '2025-12-14 12:26:38'),
(3, 'هل يمكنني تعديل البلاغ بعد إرساله؟', 'لا يمكن تعديل البلاغ بعد إرساله، لكن يمكنك إضافة تعليقات أو رسائل إضافية توضح المزيد من التفاصيل.', 'البلاغات', 3, 1, 0, '2025-12-14 12:26:38', '2025-12-14 12:26:38'),
(4, 'كيف أتابع حالة البلاغ؟', 'يمكنك متابعة حالة بلاغك من صفحة \"بلاغاتي\" حيث تظهر جميع البلاغات مع حالتها الحالية. كما ستصلك إشعارات عند أي تحديث.', 'البلاغات', 4, 1, 0, '2025-12-14 12:26:38', '2025-12-14 12:26:38'),
(5, 'ما هي أنواع البلاغات المقبولة؟', 'نستقبل البلاغات الأكاديمية، الإدارية، التقنية، المالية، وغيرها. يرجى اختيار النوع المناسب عند إرسال البلاغ.', 'البلاغات', 5, 1, 0, '2025-12-14 12:26:38', '2025-12-14 12:26:38'),
(6, 'نسيت كلمة المرور، ماذا أفعل؟', 'حالياً يمكنك التواصل مع الدعم الفني لإعادة تعيين كلمة المرور. سيتم إضافة خاصية استعادة كلمة المرور قريباً.', 'الحساب', 6, 1, 0, '2025-12-14 12:26:38', '2025-12-14 12:26:38');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) UNSIGNED NOT NULL,
  `complaint_id` int(11) UNSIGNED NOT NULL,
  `sender_id` int(11) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `complaint_id`, `sender_id`, `message`, `is_read`, `read_at`, `created_at`) VALUES
(1, 6, 5, 'k.hukggyflbj.', 1, '2025-12-14 13:12:36', '2025-12-14 13:12:00'),
(2, 6, 1, 'hjgjftfhjgghfthjfjhfyfjyffhtfjhg', 1, '2025-12-14 13:13:43', '2025-12-14 13:12:45'),
(3, 6, 1, 'hkih', 1, '2025-12-14 13:13:43', '2025-12-14 13:12:49'),
(4, 7, 5, 'للللللل', 1, '2025-12-14 14:02:51', '2025-12-14 14:00:49');

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` int(11) UNSIGNED NOT NULL,
  `complaint_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL COMMENT 'المستخدم الذي أضاف الملاحظة',
  `content` text NOT NULL,
  `is_internal` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'ملاحظة داخلية للإدارة فقط',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`id`, `complaint_id`, `user_id`, `content`, `is_internal`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'تم استلام الطلب وجاري مراجعته مع قسم شؤون الطلاب.', 0, '2025-12-12 12:24:40', '2025-12-12 12:24:40'),
(2, 2, 1, 'ملاحظة داخلية: يحتاج الطالب ختم من عميد الكلية.', 1, '2025-12-13 12:24:40', '2025-12-13 12:24:40'),
(3, 4, 1, 'تم التواصل مع الشؤون المالية وسيتم الرد خلال 24 ساعة.', 0, '2025-12-11 12:24:40', '2025-12-11 12:24:40'),
(4, 6, 1, 'تم تغيير حالة البلاغ إلى: تم الحل', 0, '2025-12-14 13:13:00', '2025-12-14 13:13:00'),
(5, 3, 1, 'hhhh', 0, '2025-12-15 22:06:16', '2025-12-15 22:06:16'),
(6, 9, 1, 'تم تغيير حالة البلاغ إلى: تحت المعالجة', 0, '2025-12-27 10:05:22', '2025-12-27 10:05:22'),
(7, 10, 1, 'تم تغيير حالة البلاغ إلى: تحت المعالجة', 0, '2025-12-27 10:39:16', '2025-12-27 10:39:16'),
(8, 10, 1, 'تم تغيير حالة البلاغ إلى: مفتوح', 0, '2025-12-27 10:43:10', '2025-12-27 10:43:10'),
(9, 10, 1, 'تم تغيير حالة البلاغ إلى: تحت المعالجة', 0, '2025-12-27 10:47:54', '2025-12-27 10:47:54'),
(10, 10, 1, 'تم تغيير حالة البلاغ إلى: تم الحل', 0, '2025-12-27 10:52:38', '2025-12-27 10:52:38'),
(11, 10, 1, 'تم تغيير حالة البلاغ إلى: مغلق', 0, '2025-12-27 10:59:07', '2025-12-27 10:59:07'),
(12, 10, 1, 'تم تغيير حالة البلاغ إلى: مغلق', 0, '2025-12-27 11:07:04', '2025-12-27 11:07:04'),
(13, 10, 1, 'تم تغيير حالة البلاغ إلى: تم الحل', 0, '2025-12-27 11:07:12', '2025-12-27 11:07:12'),
(14, 10, 1, 'تم تغيير حالة البلاغ إلى: تحت المعالجة', 0, '2025-12-28 10:37:45', '2025-12-28 10:37:45'),
(15, 10, 1, 'تم تغيير حالة البلاغ إلى: مفتوح', 0, '2025-12-28 10:38:11', '2025-12-28 10:38:11');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL COMMENT 'المستخدم المستلم للإشعار',
  `complaint_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'البلاغ المرتبط (اختياري)',
  `type` varchar(50) NOT NULL COMMENT 'نوع الإشعار: status_change, new_message, note_added',
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `complaint_id`, `type`, `title`, `message`, `is_read`, `read_at`, `created_at`) VALUES
(1, 5, 6, 'new_message', 'رسالة جديدة', 'لديك رسالة جديدة من مدير النظام في البلاغ رقم 6', 0, NULL, '2025-12-14 13:12:45'),
(2, 5, 6, 'new_message', 'رسالة جديدة', 'لديك رسالة جديدة من مدير النظام في البلاغ رقم 6', 0, NULL, '2025-12-14 13:12:49'),
(3, 5, 6, 'status_change', 'تحديث حالة البلاغ', 'تم تحديث حالة بلاغ رقم 6 إلى تم الحل', 0, NULL, '2025-12-14 13:13:00'),
(4, 2, 9, 'status_change', 'تحديث حالة البلاغ', 'تم تحديث حالة بلاغ رقم 9 إلى تحت المعالجة', 0, NULL, '2025-12-27 10:05:22'),
(5, 6, 10, 'status_change', 'تحديث حالة البلاغ', 'تم تحديث حالة بلاغ رقم 10 إلى تحت المعالجة', 1, '2025-12-27 11:14:24', '2025-12-27 10:39:16'),
(6, 6, 10, 'status_change', 'تحديث حالة البلاغ', 'تم تحديث حالة بلاغ رقم 10 إلى مفتوح', 1, '2025-12-27 11:14:24', '2025-12-27 10:43:20'),
(7, 6, 10, 'status_change', 'تحديث حالة البلاغ', 'تم تحديث حالة بلاغ رقم 10 إلى تحت المعالجة', 1, '2025-12-27 11:14:24', '2025-12-27 10:48:00'),
(8, 6, 10, 'status_change', 'تحديث حالة البلاغ', 'تم تحديث حالة بلاغ رقم 10 إلى تم الحل', 1, '2025-12-27 11:14:24', '2025-12-27 10:52:46'),
(9, 6, 10, 'status_change', 'تحديث حالة البلاغ', 'تم تحديث حالة بلاغ رقم 10 إلى مغلق', 1, '2025-12-27 11:14:24', '2025-12-27 10:59:17'),
(10, 6, 10, 'status_change', 'تحديث حالة البلاغ', 'تم تحديث حالة بلاغ رقم 10 إلى مغلق', 1, '2025-12-27 11:14:24', '2025-12-27 11:07:12'),
(11, 6, 10, 'status_change', 'تحديث حالة البلاغ', 'تم تحديث حالة بلاغ رقم 10 إلى تم الحل', 1, '2025-12-27 11:14:24', '2025-12-27 11:07:18'),
(12, 6, 10, 'status_change', 'تحديث حالة البلاغ', 'تم تحديث حالة بلاغ رقم 10 إلى تحت المعالجة', 0, NULL, '2025-12-28 10:37:57'),
(13, 6, 10, 'status_change', 'تحديث حالة البلاغ', 'تم تحديث حالة بلاغ رقم 10 إلى مفتوح', 0, NULL, '2025-12-28 10:38:17');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','admin') NOT NULL DEFAULT 'student',
  `student_id` varchar(50) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `student_id`, `department`, `phone`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'مدير النظام', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NULL, 'الإدارة العامة', '0500000000', 1, '2025-12-14 12:24:40', '2025-12-14 12:24:40'),
(2, 'أحمد محمد', 'ahmed@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 'STU001', 'علوم الحاسب', '0501111111', 1, '2025-12-14 12:24:40', '2025-12-14 12:24:40'),
(3, 'فاطمة علي', 'fatima@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 'STU002', 'نظم المعلومات', '0502222222', 1, '2025-12-14 12:24:40', '2025-12-14 12:24:40'),
(4, 'خالد سعيد', 'khaled@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 'STU003', 'هندسة البرمجيات', '0503333333', 1, '2025-12-14 12:24:40', '2025-12-14 12:24:40'),
(5, 'taha', 'tahaabdalla413@gmail.com', '$2y$10$DZ4lXgJkUMKBRl5inrE/6OZ4oRcmMwHurZ8AiDX6jzJS8ENYusTsa', 'student', '211604', 'تحكم', '0910984206', 1, '2025-12-14 12:33:31', '2025-12-14 12:33:31'),
(6, 'taha', 't.faisal@btg.com.ly', '$2y$10$Fn6H6gdlba3Xfv.DX8ciw.KIHF4jfJ19w19NwxoV0affgWeKC/Vle', 'student', '211604', 'حاسب', '0910984206', 1, '2025-12-27 10:37:36', '2025-12-27 10:37:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `complaint_id` (`complaint_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `action` (`action`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `timestamp` (`timestamp`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `status` (`status`),
  ADD KEY `category` (`category`),
  ADD KEY `priority` (`priority`),
  ADD KEY `assigned_to` (`assigned_to`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category` (`category`),
  ADD KEY `is_active` (`is_active`),
  ADD KEY `sort_order` (`sort_order`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `complaint_id` (`complaint_id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `is_read` (`is_read`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `complaint_id` (`complaint_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `complaint_id` (`complaint_id`),
  ADD KEY `is_read` (`is_read`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `role` (`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `fk_activity_logs_complaint` FOREIGN KEY (`complaint_id`) REFERENCES `complaints` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_activity_logs_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `fk_complaints_admin` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_complaints_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `fk_messages_complaint` FOREIGN KEY (`complaint_id`) REFERENCES `complaints` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_messages_sender` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `fk_notes_complaint` FOREIGN KEY (`complaint_id`) REFERENCES `complaints` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_notes_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_notifications_complaint` FOREIGN KEY (`complaint_id`) REFERENCES `complaints` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_notifications_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
