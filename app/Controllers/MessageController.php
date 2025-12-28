<?php

namespace App\Controllers;

use App\Models\MessageModel;
use App\Models\ComplaintModel;
use App\Models\NotificationModel;
use App\Models\ActivityLogModel;

class MessageController extends BaseController
{
    protected $messageModel;
    protected $complaintModel;
    protected $notificationModel;
    protected $activityLogModel;

    public function __construct()
    {
        $this->messageModel = new MessageModel();
        $this->complaintModel = new ComplaintModel();
        $this->notificationModel = new NotificationModel();
        $this->activityLogModel = new ActivityLogModel();
    }

    /**
     * إرسال رسالة جديدة
     */
    public function send($complaintId)
    {
        $message = $this->request->getPost('message');
        $userId = session()->get('user_id');
        $userName = session()->get('user_name');
        
        if (empty($message)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'الرسالة مطلوبة'
            ]);
        }

        // التحقق من أن المستخدم له حق في هذا البلاغ
        $complaint = $this->complaintModel->find($complaintId);
        if (!$complaint) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'البلاغ غير موجود'
            ]);
        }

        $userRole = session()->get('user_role');
        if ($userRole === 'student' && $complaint['user_id'] !== $userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ليس لديك صلاحية'
            ]);
        }

        // إرسال الرسالة
        $this->messageModel->send($complaintId, $userId, $message);

        // تسجيل النشاط
        $this->activityLogModel->logMessageSent($complaintId, $userId);

        // إرسال إشعار للطرف الآخر
        if ($userRole === 'student') {
            // إشعار للإداري المسؤول
            if ($complaint['assigned_to']) {
                $this->notificationModel->notifyNewMessage($complaintId, $complaint['assigned_to'], $userName);
            }
        } else {
            // إشعار لصاحب البلاغ
            $this->notificationModel->notifyNewMessage($complaintId, $complaint['user_id'], $userName);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'تم إرسال الرسالة بنجاح'
        ]);
    }

    /**
     * جلب رسائل البلاغ (AJAX)
     */
    public function getMessages($complaintId)
    {
        $userId = session()->get('user_id');
        
        // التحقق من الصلاحية
        $complaint = $this->complaintModel->find($complaintId);
        if (!$complaint) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'البلاغ غير موجود'
            ]);
        }

        $userRole = session()->get('user_role');
        if ($userRole === 'student' && $complaint['user_id'] !== $userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ليس لديك صلاحية'
            ]);
        }

        // تحديد الرسائل كمقروءة
        $this->messageModel->markAsRead($complaintId, $userId);

        $messages = $this->messageModel->getByComplaint($complaintId);

        return $this->response->setJSON([
            'success'  => true,
            'messages' => $messages,
            'user_id'  => $userId,
        ]);
    }
}
