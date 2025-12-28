<?php
// اختبار بسيط بدون CodeIgniter

$pdo = new PDO('mysql:host=localhost;dbname=complaints_system;charset=utf8mb4', 'root', '');

// إنشاء كلمة مرور مشفرة
$testPassword = 'testpassword123';
$hashedPassword = password_hash($testPassword, PASSWORD_DEFAULT);

echo "=== اختبار التشفير ===\n\n";
echo "كلمة المرور الأصلية: $testPassword\n";
echo "الهاش: $hashedPassword\n";
echo "طول الهاش: " . strlen($hashedPassword) . "\n\n";

// إدخال مستخدم تجريبي
$testEmail = 'directtest' . time() . '@test.com';
$stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, student_id, department, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
$stmt->execute(['مستخدم مباشر', $testEmail, $hashedPassword, 'student', '888888', 'قسم الاختبار', 1]);
$userId = $pdo->lastInsertId();

echo "تم إنشاء المستخدم ID: $userId\n";
echo "البريد: $testEmail\n\n";

// جلب المستخدم والتحقق
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

echo "=== اختبار تسجيل الدخول ===\n\n";
echo "كلمة المرور المخزنة: " . $user['password'] . "\n";

if (password_verify($testPassword, $user['password'])) {
    echo "✅ تسجيل الدخول ناجح!\n";
} else {
    echo "❌ فشل تسجيل الدخول!\n";
}

// حذف المستخدم التجريبي
$pdo->exec("DELETE FROM users WHERE id = $userId");
echo "\n(تم حذف المستخدم التجريبي)\n";
