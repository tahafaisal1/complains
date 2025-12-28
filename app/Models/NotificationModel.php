<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table            = 'notifications';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'user_id', 'complaint_id', 'type', 'title', 'message', 'is_read', 'read_at'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    /**
     * إنشاء إشعار جديد
     */
    public function createNotification($userId, $type, $title, $message, $complaintId = null)
    {
        return $this->insert([
            'user_id'      => $userId,
            'complaint_id' => $complaintId,
            'type'         => $type,
            'title'        => $title,
            'message'      => $message,
            'is_read'      => 0,
            'created_at'   => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * الحصول على إشعارات المستخدم
     */
    public function getUserNotifications($userId, $limit = 20)
    {
        return $this->where('user_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->find();
    }

    /**
     * الحصول على الإشعارات غير المقروءة
     */
    public function getUnread($userId)
    {
        return $this->where('user_id', $userId)
                    ->where('is_read', 0)
                    ->orderBy('created_at', 'DESC')
                    ->find();
    }

    /**
     * عدد الإشعارات غير المقروءة
     */
    public function getUnreadCount($userId)
    {
        return $this->where('user_id', $userId)
                    ->where('is_read', 0)
                    ->countAllResults();
    }

    /**
     * تحديد إشعار كمقروء
     */
    public function markAsRead($id, $userId)
    {
        return $this->where('id', $id)
                    ->where('user_id', $userId)
                    ->set(['is_read' => 1, 'read_at' => date('Y-m-d H:i:s')])
                    ->update();
    }

    /**
     * تحديد جميع الإشعارات كمقروءة
     */
    public function markAllAsRead($userId)
    {
        return $this->where('user_id', $userId)
                    ->where('is_read', 0)
                    ->set(['is_read' => 1, 'read_at' => date('Y-m-d H:i:s')])
                    ->update();
    }

    /**
     * إرسال إشعار تغيير حالة البلاغ
     */
    public function notifyStatusChange($complaintId, $userId, $oldStatus, $newStatus)
    {
        $statuses = [
            'open'        => 'مفتوح',
            'in_progress' => 'تحت المعالجة',
            'resolved'    => 'تم الحل',
            'closed'      => 'مغلق',
        ];

        $title = 'تحديث حالة البلاغ';
        $message = "تم تحديث حالة بلاغ رقم {$complaintId} إلى {$statuses[$newStatus]}";

        // إرسال بريد إلكتروني
        $this->sendEmailNotification($userId, $title, $message);
        
        return $this->createNotification($userId, 'status_change', $title, $message, $complaintId);
    }

    /**
     * إرسال إشعار رسالة جديدة
     */
    public function notifyNewMessage($complaintId, $userId, $senderName)
    {
        $title = 'رسالة جديدة';
        $message = "لديك رسالة جديدة من {$senderName} في البلاغ رقم {$complaintId}";

        // إرسال بريد إلكتروني
        $this->sendEmailNotification($userId, $title, $message);

        return $this->createNotification($userId, 'new_message', $title, $message, $complaintId);
    }

    /**
     * إرسال تنبيه بالبريد الإلكتروني
     */
    private function sendEmailNotification($userId, $subject, $message)
    {
        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($userId);
        
        if (!$user || empty($user['email'])) {
            return false;
        }

        $email = \Config\Services::email();

        // إعدادات البريد (يجب تكوينها في .env أو Config/Email.php)
        // $email->setFrom('notifications@example.com', 'نظام البلاغات');
        
        $email->setTo($user['email']);
        $email->setSubject($subject);
        $email->setMessage($message);

        // محاولة إرسال البريد - نتجاهل الأخطاء لعدم تعطيل النظام
        try {
            return $email->send();
        } catch (\Exception $e) {
            log_message('error', 'Email sending failed: ' . $e->getMessage());
            return false;
        }
    }
}
