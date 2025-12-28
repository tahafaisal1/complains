<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityLogModel extends Model
{
    protected $table            = 'activity_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'complaint_id', 'user_id', 'action', 'description', 'old_value', 'new_value'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    /**
     * تسجيل نشاط جديد
     */
    public function log($complaintId, $userId, $action, $description, $oldValue = null, $newValue = null)
    {
        return $this->insert([
            'complaint_id' => $complaintId,
            'user_id'      => $userId,
            'action'       => $action,
            'description'  => $description,
            'old_value'    => $oldValue,
            'new_value'    => $newValue,
            'created_at'   => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * الحصول على سجل نشاط البلاغ مع بيانات المستخدم
     */
    public function getByComplaint($complaintId)
    {
        return $this->select('activity_logs.*, users.name as user_name, users.role as user_role')
                    ->join('users', 'users.id = activity_logs.user_id', 'left')
                    ->where('complaint_id', $complaintId)
                    ->orderBy('activity_logs.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * الحصول على سجل نشاط المستخدم
     */
    public function getByUser($userId, $limit = 50)
    {
        return $this->select('activity_logs.*, complaints.title as complaint_title')
                    ->join('complaints', 'complaints.id = activity_logs.complaint_id', 'left')
                    ->where('activity_logs.user_id', $userId)
                    ->orderBy('activity_logs.created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * تسجيل إنشاء بلاغ
     */
    public function logCreation($complaintId, $userId)
    {
        return $this->log($complaintId, $userId, 'created', 'تم إنشاء البلاغ');
    }

    /**
     * تسجيل تغيير الحالة
     */
    public function logStatusChange($complaintId, $userId, $oldStatus, $newStatus)
    {
        $statuses = [
            'open'        => 'مفتوح',
            'in_progress' => 'تحت المعالجة',
            'resolved'    => 'تم الحل',
            'closed'      => 'مغلق',
        ];

        $description = "تم تغيير الحالة من '{$statuses[$oldStatus]}' إلى '{$statuses[$newStatus]}'";
        
        return $this->log($complaintId, $userId, 'status_changed', $description, $oldStatus, $newStatus);
    }

    /**
     * تسجيل إضافة ملاحظة
     */
    public function logNoteAdded($complaintId, $userId, $isInternal = false)
    {
        $type = $isInternal ? 'ملاحظة داخلية' : 'ملاحظة';
        return $this->log($complaintId, $userId, 'note_added', "تم إضافة {$type}");
    }

    /**
     * تسجيل التعيين لإداري
     */
    public function logAssignment($complaintId, $userId, $adminName)
    {
        return $this->log($complaintId, $userId, 'assigned', "تم تعيين البلاغ إلى {$adminName}");
    }

    /**
     * تسجيل الرد الإداري
     */
    public function logResponse($complaintId, $userId)
    {
        return $this->log($complaintId, $userId, 'responded', 'تم إضافة رد إداري');
    }

    /**
     * تسجيل إرسال رسالة
     */
    public function logMessageSent($complaintId, $userId)
    {
        return $this->log($complaintId, $userId, 'message_sent', 'تم إرسال رسالة');
    }

    /**
     * الحصول على أيقونة الإجراء
     */
    public static function getActionIcon($action)
    {
        $icons = [
            'created'        => 'fa-plus-circle text-success',
            'status_changed' => 'fa-exchange-alt text-primary',
            'note_added'     => 'fa-sticky-note text-info',
            'assigned'       => 'fa-user-check text-warning',
            'responded'      => 'fa-reply text-secondary',
            'message_sent'   => 'fa-envelope text-primary',
        ];

        return $icons[$action] ?? 'fa-circle text-muted';
    }
}
