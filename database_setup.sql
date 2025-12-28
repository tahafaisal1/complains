-- =====================================================
-- نظام إدارة البلاغات - Complaints Management System
-- =====================================================
-- قم بتشغيل هذا الملف في MySQL/phpMyAdmin لإنشاء قاعدة البيانات

-- إنشاء قاعدة البيانات
CREATE DATABASE IF NOT EXISTS complaints_system 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE complaints_system;

-- =====================================================
-- جدول المستخدمين (users)
-- =====================================================
CREATE TABLE IF NOT EXISTS users (
    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'admin') NOT NULL DEFAULT 'student',
    student_id VARCHAR(50) NULL DEFAULT NULL,
    department VARCHAR(100) NULL DEFAULT NULL,
    phone VARCHAR(20) NULL DEFAULT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NULL DEFAULT NULL,
    updated_at DATETIME NULL DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY email (email),
    KEY student_id (student_id),
    KEY role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- جدول البلاغات (complaints)
-- =====================================================
CREATE TABLE IF NOT EXISTS complaints (
    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT(11) UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    category VARCHAR(100) NOT NULL COMMENT 'نوع البلاغ: أكاديمي، إداري، تقني، مالي، أخرى',
    priority ENUM('low', 'medium', 'high', 'urgent') NOT NULL DEFAULT 'medium',
    status ENUM('open', 'in_progress', 'resolved', 'closed') NOT NULL DEFAULT 'open',
    assigned_to INT(11) UNSIGNED NULL DEFAULT NULL COMMENT 'الإداري المسؤول عن البلاغ',
    attachment VARCHAR(255) NULL DEFAULT NULL COMMENT 'مسار الملف المرفق',
    admin_response TEXT NULL DEFAULT NULL,
    resolved_at DATETIME NULL DEFAULT NULL,
    created_at DATETIME NULL DEFAULT NULL,
    updated_at DATETIME NULL DEFAULT NULL,
    PRIMARY KEY (id),
    KEY user_id (user_id),
    KEY status (status),
    KEY category (category),
    KEY priority (priority),
    KEY assigned_to (assigned_to),
    KEY created_at (created_at),
    CONSTRAINT fk_complaints_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_complaints_admin FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- جدول الملاحظات (notes)
-- =====================================================
CREATE TABLE IF NOT EXISTS notes (
    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    complaint_id INT(11) UNSIGNED NOT NULL,
    user_id INT(11) UNSIGNED NOT NULL COMMENT 'المستخدم الذي أضاف الملاحظة',
    content TEXT NOT NULL,
    is_internal TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'ملاحظة داخلية للإدارة فقط',
    created_at DATETIME NULL DEFAULT NULL,
    updated_at DATETIME NULL DEFAULT NULL,
    PRIMARY KEY (id),
    KEY complaint_id (complaint_id),
    KEY user_id (user_id),
    CONSTRAINT fk_notes_complaint FOREIGN KEY (complaint_id) REFERENCES complaints(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_notes_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- جدول الجلسات (ci_sessions) - لـ CodeIgniter
-- =====================================================
CREATE TABLE IF NOT EXISTS ci_sessions (
    id VARCHAR(128) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    timestamp INT(10) UNSIGNED NOT NULL DEFAULT 0,
    data BLOB NOT NULL,
    PRIMARY KEY (id),
    KEY timestamp (timestamp)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- بيانات تجريبية - مستخدمين
-- =====================================================
-- كلمة المرور للإداري: admin123
-- كلمة المرور للطلاب: student123

INSERT INTO users (name, email, password, role, student_id, department, phone, is_active, created_at, updated_at) VALUES
('مدير النظام', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NULL, 'الإدارة العامة', '0500000000', 1, NOW(), NOW()),
('أحمد محمد', 'ahmed@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 'STU001', 'علوم الحاسب', '0501111111', 1, NOW(), NOW()),
('فاطمة علي', 'fatima@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 'STU002', 'نظم المعلومات', '0502222222', 1, NOW(), NOW()),
('خالد سعيد', 'khaled@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 'STU003', 'هندسة البرمجيات', '0503333333', 1, NOW(), NOW());

-- =====================================================
-- بيانات تجريبية - بلاغات
-- =====================================================
INSERT INTO complaints (user_id, title, description, category, priority, status, assigned_to, admin_response, resolved_at, created_at, updated_at) VALUES
(2, 'مشكلة في التسجيل للمواد', 'لا أستطيع التسجيل في مادة البرمجة المتقدمة رغم توفر المتطلبات السابقة. يظهر لي رسالة خطأ عند محاولة التسجيل.', 'أكاديمي', 'high', 'open', NULL, NULL, NULL, DATE_SUB(NOW(), INTERVAL 5 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY)),
(2, 'طلب شهادة حضور', 'أحتاج شهادة تثبت حضوري للفصل الدراسي الحالي لتقديمها لجهة عمل.', 'إداري', 'medium', 'in_progress', 1, NULL, NULL, DATE_SUB(NOW(), INTERVAL 3 DAY), DATE_SUB(NOW(), INTERVAL 1 DAY)),
(3, 'مشكلة في بوابة الطالب', 'لا أستطيع الدخول لبوابة الطالب منذ يومين. تظهر رسالة أن الحساب معطل.', 'تقني', 'urgent', 'open', NULL, NULL, NULL, DATE_SUB(NOW(), INTERVAL 2 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY)),
(3, 'استفسار عن الرسوم الدراسية', 'أريد الاستفسار عن تفاصيل الرسوم الدراسية وموعد السداد للفصل القادم.', 'مالي', 'low', 'resolved', 1, 'تم إرسال تفاصيل الرسوم على بريدك الإلكتروني. موعد السداد هو 15 من الشهر القادم.', DATE_SUB(NOW(), INTERVAL 1 DAY), DATE_SUB(NOW(), INTERVAL 7 DAY), DATE_SUB(NOW(), INTERVAL 1 DAY)),
(4, 'اقتراح تحسين المكتبة', 'أقترح زيادة ساعات عمل المكتبة خلال فترة الاختبارات لتكون متاحة حتى منتصف الليل.', 'أخرى', 'low', 'closed', 1, 'شكراً لاقتراحك. تم رفع الاقتراح للإدارة العليا وسيتم دراسته.', DATE_SUB(NOW(), INTERVAL 2 DAY), DATE_SUB(NOW(), INTERVAL 10 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY));

-- =====================================================
-- ملاحظات تجريبية
-- =====================================================
INSERT INTO notes (complaint_id, user_id, content, is_internal, created_at, updated_at) VALUES
(2, 1, 'تم استلام الطلب وجاري مراجعته مع قسم شؤون الطلاب.', 0, DATE_SUB(NOW(), INTERVAL 2 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY)),
(2, 1, 'ملاحظة داخلية: يحتاج الطالب ختم من عميد الكلية.', 1, DATE_SUB(NOW(), INTERVAL 1 DAY), DATE_SUB(NOW(), INTERVAL 1 DAY)),
(4, 1, 'تم التواصل مع الشؤون المالية وسيتم الرد خلال 24 ساعة.', 0, DATE_SUB(NOW(), INTERVAL 3 DAY), DATE_SUB(NOW(), INTERVAL 3 DAY));

-- =====================================================
-- انتهى التثبيت بنجاح ✅
-- =====================================================
SELECT '✅ تم إنشاء قاعدة البيانات بنجاح!' AS message;
SELECT '📧 حساب الإداري: admin@example.com' AS admin_info;
SELECT '🔑 كلمة المرور: password (للجميع)' AS password_info;
