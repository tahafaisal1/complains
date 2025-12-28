-- =====================================================
-- إضافات قاعدة البيانات - الميزات الجديدة
-- =====================================================

USE complaints_system;

-- =====================================================
-- جدول الإشعارات (notifications)
-- =====================================================
CREATE TABLE IF NOT EXISTS notifications (
    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT(11) UNSIGNED NOT NULL COMMENT 'المستخدم المستلم للإشعار',
    complaint_id INT(11) UNSIGNED NULL DEFAULT NULL COMMENT 'البلاغ المرتبط (اختياري)',
    type VARCHAR(50) NOT NULL COMMENT 'نوع الإشعار: status_change, new_message, note_added',
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    read_at DATETIME NULL DEFAULT NULL,
    created_at DATETIME NULL DEFAULT NULL,
    PRIMARY KEY (id),
    KEY user_id (user_id),
    KEY complaint_id (complaint_id),
    KEY is_read (is_read),
    KEY created_at (created_at),
    CONSTRAINT fk_notifications_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_notifications_complaint FOREIGN KEY (complaint_id) REFERENCES complaints(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- جدول سجل النشاط (activity_logs)
-- =====================================================
CREATE TABLE IF NOT EXISTS activity_logs (
    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    complaint_id INT(11) UNSIGNED NOT NULL,
    user_id INT(11) UNSIGNED NOT NULL COMMENT 'المستخدم الذي قام بالإجراء',
    action VARCHAR(100) NOT NULL COMMENT 'نوع الإجراء: created, status_changed, note_added, assigned, responded',
    description TEXT NOT NULL COMMENT 'وصف الإجراء',
    old_value VARCHAR(255) NULL DEFAULT NULL,
    new_value VARCHAR(255) NULL DEFAULT NULL,
    created_at DATETIME NULL DEFAULT NULL,
    PRIMARY KEY (id),
    KEY complaint_id (complaint_id),
    KEY user_id (user_id),
    KEY action (action),
    KEY created_at (created_at),
    CONSTRAINT fk_activity_logs_complaint FOREIGN KEY (complaint_id) REFERENCES complaints(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_activity_logs_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- جدول الرسائل (messages)
-- =====================================================
CREATE TABLE IF NOT EXISTS messages (
    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    complaint_id INT(11) UNSIGNED NOT NULL,
    sender_id INT(11) UNSIGNED NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    read_at DATETIME NULL DEFAULT NULL,
    created_at DATETIME NULL DEFAULT NULL,
    PRIMARY KEY (id),
    KEY complaint_id (complaint_id),
    KEY sender_id (sender_id),
    KEY is_read (is_read),
    KEY created_at (created_at),
    CONSTRAINT fk_messages_complaint FOREIGN KEY (complaint_id) REFERENCES complaints(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_messages_sender FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- جدول الأسئلة الشائعة (faqs)
-- =====================================================
CREATE TABLE IF NOT EXISTS faqs (
    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    question TEXT NOT NULL,
    answer TEXT NOT NULL,
    category VARCHAR(100) NULL DEFAULT NULL COMMENT 'تصنيف السؤال',
    sort_order INT(11) NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    views INT(11) NOT NULL DEFAULT 0 COMMENT 'عدد المشاهدات',
    created_at DATETIME NULL DEFAULT NULL,
    updated_at DATETIME NULL DEFAULT NULL,
    PRIMARY KEY (id),
    KEY category (category),
    KEY is_active (is_active),
    KEY sort_order (sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- بيانات تجريبية للأسئلة الشائعة
-- =====================================================
INSERT INTO faqs (question, answer, category, sort_order, is_active, created_at, updated_at) VALUES
('كيف أرسل بلاغ جديد؟', 'يمكنك إرسال بلاغ جديد عن طريق تسجيل الدخول ثم الضغط على "إرسال بلاغ جديد" من القائمة الجانبية. قم بملء النموذج بالتفاصيل المطلوبة واضغط إرسال.', 'عام', 1, 1, NOW(), NOW()),
('كم المدة المتوقعة للرد على البلاغ؟', 'عادة يتم الرد على البلاغات خلال 2-3 أيام عمل. البلاغات العاجلة يتم التعامل معها بشكل أسرع.', 'عام', 2, 1, NOW(), NOW()),
('هل يمكنني تعديل البلاغ بعد إرساله؟', 'لا يمكن تعديل البلاغ بعد إرساله، لكن يمكنك إضافة تعليقات أو رسائل إضافية توضح المزيد من التفاصيل.', 'البلاغات', 3, 1, NOW(), NOW()),
('كيف أتابع حالة البلاغ؟', 'يمكنك متابعة حالة بلاغك من صفحة "بلاغاتي" حيث تظهر جميع البلاغات مع حالتها الحالية. كما ستصلك إشعارات عند أي تحديث.', 'البلاغات', 4, 1, NOW(), NOW()),
('ما هي أنواع البلاغات المقبولة؟', 'نستقبل البلاغات الأكاديمية، الإدارية، التقنية، المالية، وغيرها. يرجى اختيار النوع المناسب عند إرسال البلاغ.', 'البلاغات', 5, 1, NOW(), NOW()),
('نسيت كلمة المرور، ماذا أفعل؟', 'حالياً يمكنك التواصل مع الدعم الفني لإعادة تعيين كلمة المرور. سيتم إضافة خاصية استعادة كلمة المرور قريباً.', 'الحساب', 6, 1, NOW(), NOW());

-- =====================================================
-- انتهى التثبيت بنجاح ✅
-- =====================================================
SELECT '✅ تم إنشاء الجداول الإضافية بنجاح!' AS message;
