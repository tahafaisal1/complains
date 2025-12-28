<?php
// تحديث كلمات المرور لجميع المستخدمين الذين كلمة مرورهم غير مشفرة

$pdo = new PDO('mysql:host=localhost;dbname=complaints_system;charset=utf8mb4', 'root', '');

// جلب جميع المستخدمين
$stmt = $pdo->query("SELECT id, email, password FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "=== فحص وتحديث كلمات المرور ===\n\n";

foreach ($users as $user) {
    echo "المستخدم: {$user['email']}\n";
    echo "  - طول كلمة المرور: " . strlen($user['password']) . "\n";
    
    // التحقق مما إذا كانت كلمة المرور مشفرة (تبدأ بـ $2y$)
    if (strpos($user['password'], '$2y$') === 0 && strlen($user['password']) === 60) {
        echo "  - ✅ مشفرة بشكل صحيح\n";
        
        // اختبار مع 'password'
        if (password_verify('password', $user['password'])) {
            echo "  - ✅ كلمة المرور 'password' صحيحة\n";
        }
    } else {
        echo "  - ⚠️ كلمة المرور غير مشفرة، جاري التحديث...\n";
        
        // تشفير كلمة المرور (نفترض أنها 'password' الافتراضية)
        $newHash = password_hash('password', PASSWORD_DEFAULT);
        $updateStmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $updateStmt->execute([$newHash, $user['id']]);
        echo "  - ✅ تم تحديث كلمة المرور\n";
    }
    echo "\n";
}

echo "تم الانتهاء!\n";
