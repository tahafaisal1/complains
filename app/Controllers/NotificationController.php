<?php

namespace App\Controllers;

use App\Models\NotificationModel;

class NotificationController extends BaseController
{
    protected $notificationModel;

    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
    }

    /**
     * عرض صفحة الإشعارات
     */
    public function index()
    {
        $userId = session()->get('user_id');
        
        $data = [
            'title'         => 'الإشعارات',
            'pageTitle'     => 'الإشعارات',
            'notifications' => $this->notificationModel->getUserNotifications($userId, 50),
        ];

        return view('notifications/index', $data);
    }

    /**
     * تحديد إشعار كمقروء
     */
    public function markAsRead($id)
    {
        $userId = session()->get('user_id');
        
        if ($this->notificationModel->markAsRead($id, $userId)) {
            return $this->response->setJSON(['success' => true]);
        }

        return $this->response->setJSON(['success' => false]);
    }

    /**
     * تحديد جميع الإشعارات كمقروءة
     */
    public function markAllAsRead()
    {
        $userId = session()->get('user_id');
        
        if ($this->notificationModel->markAllAsRead($userId)) {
            return redirect()->back()->with('success', 'تم تحديد جميع الإشعارات كمقروءة');
        }

        return redirect()->back()->with('error', 'حدث خطأ');
    }

    /**
     * الحصول على عدد الإشعارات غير المقروءة (AJAX)
     */
    public function getUnreadCount()
    {
        $userId = session()->get('user_id');
        $count = $this->notificationModel->getUnreadCount($userId);
        
        return $this->response->setJSON(['count' => $count]);
    }

    /**
     * الحصول على الإشعارات الأخيرة (AJAX)
     */
    public function getRecent()
    {
        $userId = session()->get('user_id');
        $notifications = $this->notificationModel->getUserNotifications($userId, 5);
        
        return $this->response->setJSON([
            'notifications' => $notifications,
            'unread_count'  => $this->notificationModel->getUnreadCount($userId),
        ]);
    }
}
