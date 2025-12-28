<?php

namespace App\Models;

use CodeIgniter\Model;

class MessageModel extends Model
{
    protected $table            = 'messages';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'complaint_id', 'sender_id', 'message', 'is_read', 'read_at'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    /**
     * إرسال رسالة جديدة
     */
    public function send($complaintId, $senderId, $message)
    {
        return $this->insert([
            'complaint_id' => $complaintId,
            'sender_id'    => $senderId,
            'message'      => $message,
            'is_read'      => 0,
            'created_at'   => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * الحصول على رسائل البلاغ مع بيانات المرسل
     */
    public function getByComplaint($complaintId)
    {
        return $this->select('messages.*, users.name as sender_name, users.role as sender_role')
                    ->join('users', 'users.id = messages.sender_id', 'left')
                    ->where('complaint_id', $complaintId)
                    ->orderBy('messages.created_at', 'ASC')
                    ->findAll();
    }

    /**
     * تحديد الرسائل كمقروءة
     */
    public function markAsRead($complaintId, $userId)
    {
        return $this->where('complaint_id', $complaintId)
                    ->where('sender_id !=', $userId)
                    ->where('is_read', 0)
                    ->set(['is_read' => 1, 'read_at' => date('Y-m-d H:i:s')])
                    ->update();
    }

    /**
     * عدد الرسائل غير المقروءة لبلاغ معين
     */
    public function getUnreadCount($complaintId, $userId)
    {
        return $this->where('complaint_id', $complaintId)
                    ->where('sender_id !=', $userId)
                    ->where('is_read', 0)
                    ->countAllResults();
    }

    /**
     * إجمالي الرسائل غير المقروءة للمستخدم
     */
    public function getTotalUnreadForUser($userId)
    {
        $db = \Config\Database::connect();
        
        // Get complaints where user is owner or admin
        $builder = $db->table('messages m');
        $builder->select('COUNT(*) as count');
        $builder->join('complaints c', 'c.id = m.complaint_id');
        $builder->where('m.sender_id !=', $userId);
        $builder->where('m.is_read', 0);
        $builder->groupStart();
        $builder->where('c.user_id', $userId);
        $builder->orWhere('c.assigned_to', $userId);
        $builder->groupEnd();

        $result = $builder->get()->getRowArray();
        return $result['count'] ?? 0;
    }
}
