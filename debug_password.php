<?php
// التحقق الشامل

$pdo = new PDO('mysql:host=localhost;dbname=complaints_system;charset=utf8mb4', 'root', '');

// التحقق من بنية العمود
$stmt = $pdo->query("SHOW COLUMNS FROM users WHERE Field = 'password'");
$column = $stmt->fetch(PDO::FETCH_ASSOC);
echo "بنية عمود password:\n";
print_r($column);

// جلب المستخدم
$stmt = $pdo->prepare("SELECT id, email, password, LENGTH(password) as pass_len FROM users WHERE email = ?");
$stmt->execute(['admin@example.com']);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

echo "\nمعلومات المستخدم:\n";
echo "Email: " . $user['email'] . "\n";
echo "Password length: " . $user['pass_len'] . "\n";
echo "Password hash: " . $user['password'] . "\n";

echo "\nاختبار password_verify:\n";
$testPassword = 'password';
echo "Testing: '$testPassword'\n";
$result = password_verify($testPassword, $user['password']);
echo "Result: " . ($result ? 'TRUE' : 'FALSE') . "\n";
