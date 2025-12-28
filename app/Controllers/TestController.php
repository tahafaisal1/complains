<?php

namespace App\Controllers;

use App\Models\UserModel;

class TestController extends BaseController
{
    public function testLogin()
    {
        $userModel = new UserModel();
        
        $email = 'admin@example.com';
        $password = 'password';
        
        echo "<h2>اختبار تسجيل الدخول</h2>";
        echo "<p>البريد: $email</p>";
        echo "<p>كلمة المرور: $password</p>";
        
        // جلب المستخدم
        $user = $userModel->findByEmail($email);
        
        if (!$user) {
            echo "<p style='color:red'>❌ المستخدم غير موجود!</p>";
            return;
        }
        
        echo "<p>✅ المستخدم موجود</p>";
        echo "<p>الهاش المخزن: " . $user['password'] . "</p>";
        echo "<p>طول الهاش: " . strlen($user['password']) . "</p>";
        
        // اختبار كلمة المرور
        if (password_verify($password, $user['password'])) {
            echo "<p style='color:green'>✅ كلمة المرور صحيحة!</p>";
        } else {
            echo "<p style='color:red'>❌ كلمة المرور غير صحيحة!</p>";
        }
        
        // اختبار attemptLogin
        echo "<h3>اختبار attemptLogin:</h3>";
        $result = $userModel->attemptLogin($email, $password);
        echo "<pre>";
        print_r($result);
        echo "</pre>";
        
        if ($result['success']) {
            echo "<p style='color:green'>✅ تسجيل الدخول ناجح!</p>";
            
            // تسجيل الدخول فعلياً
            $sessionData = [
                'user_id'    => $result['user']['id'],
                'user_name'  => $result['user']['name'],
                'user_email' => $result['user']['email'],
                'user_role'  => $result['user']['role'],
                'isLoggedIn' => true,
            ];
            session()->set($sessionData);
            
            echo "<p><a href='/admin/dashboard'>انتقل للوحة التحكم</a></p>";
        }
    }

    public function testNotifications()
    {
        echo "<h1>Notification Debug</h1>";
        
        // Check session
        $session = session();
        echo "<h2>Session Info</h2>";
        echo "<p>logged_in: " . ($session->get('logged_in') ? 'YES' : 'NO') . "</p>";
        echo "<p>user_id: " . ($session->get('user_id') ?? 'NULL') . "</p>";
        
        if (!$session->get('logged_in')) {
            echo "<p style='color:red'>❌ Not logged in! Please login first.</p>";
            echo "<p><a href='/login'>Go to Login</a></p>";
            return;
        }
        
        // Direct DB query
        echo "<h2>Direct DB Query</h2>";
        $db = \Config\Database::connect();
        $userId = $session->get('user_id');
        $query = $db->query("SELECT COUNT(*) as cnt FROM notifications WHERE user_id = ? AND is_read = 0", [$userId]);
        $result = $query->getRowArray();
        echo "<p>Unread notifications (DB): <strong>" . $result['cnt'] . "</strong></p>";
        
        // Model query
        echo "<h2>Model Query (getUnreadCount)</h2>";
        $model = new \App\Models\NotificationModel();
        $count = $model->getUnreadCount($userId);
        echo "<p>Unread notifications (Model): <strong>" . $count . "</strong></p>";
        
        // Helper
        echo "<h2>Helper Function (get_unread_count)</h2>";
        helper('notification');
        $helperCount = get_unread_count();
        echo "<p>Unread notifications (Helper): <strong>" . $helperCount . "</strong></p>";
        
        // All notifications for user
        echo "<h2>All Notifications for User $userId</h2>";
        $allNotifications = $model->where('user_id', $userId)->findAll();
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Title</th><th>is_read</th><th>Created</th></tr>";
        foreach ($allNotifications as $n) {
            $readStatus = $n['is_read'] ? '✅ Read' : '❌ Unread';
            echo "<tr><td>{$n['id']}</td><td>{$n['title']}</td><td>{$readStatus}</td><td>{$n['created_at']}</td></tr>";
        }
        echo "</table>";
    }
}
