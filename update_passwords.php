<?php
// التحقق من كلمة المرور

$pdo = new PDO('mysql:host=localhost;dbname=complaints_system;charset=utf8mb4', 'root', '');

// جلب المستخدم
$stmt = $pdo->prepare("SELECT id, email, password FROM users WHERE email = ?");
$stmt->execute(['admin@example.com']);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

echo "معلومات المستخدم:\n";
print_r($user);

echo "\nاختبار كلمة المرور 'password':\n";
if (password_verify('password', $user['password'])) {
    echo "✅ كلمة المرور صحيحة!\n";
} else {
    echo "❌ كلمة المرور غير صحيحة!\n";
    echo "Hash في قاعدة البيانات: " . $user['password'] . "\n";
    echo "طول الهاش: " . strlen($user['password']) . "\n";
    
    // إنشاء هاش جديد
    $newHash = password_hash('password', PASSWORD_DEFAULT);
    echo "\nتحديث كلمة المرور...\n";
    $updateStmt = $pdo->prepare("UPDATE users SET password = ?");
    $updateStmt->execute([$newHash]);
    echo "تم تحديث كلمة المرور بنجاح!\n";
    echo "الهاش الجديد: " . $newHash . "\n";
}
