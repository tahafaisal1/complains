<?php
// اختبار تسجيل الدخول مباشرة عبر CodeIgniter

// تحميل CodeIgniter
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
chdir(FCPATH);

require_once __DIR__ . '/vendor/autoload.php';

// Boot CodeIgniter
$paths = new \Config\Paths();
$app = \Config\Boot::bootSpark($paths);

// الآن اختبر UserModel
$userModel = new \App\Models\UserModel();

echo "=== اختبار دالة attemptLogin ===\n\n";

$email = 'admin@example.com';
$password = 'password';

echo "البريد: $email\n";
echo "كلمة المرور: $password\n\n";

$result = $userModel->attemptLogin($email, $password);

echo "النتيجة:\n";
print_r($result);
