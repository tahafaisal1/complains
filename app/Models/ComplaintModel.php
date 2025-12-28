<?php

namespace App\Models;

use CodeIgniter\Model;

class ComplaintModel extends Model
{
    protected $table            = 'complaints';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    
    protected $allowedFields    = [
        'user_id',
        'title',
        'description',
        'category',
        'priority',
        'status',
        'assigned_to',
        'attachment',
        'admin_response',
        'resolved_at',
        'rating',
        'rating_comment',
        'rated_at',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'user_id'     => 'required|integer',
        'title'       => 'required|min_length[5]|max_length[255]',
        'description' => 'required|min_length[10]',
        'category'    => 'required',
        'priority'    => 'required|in_list[low,medium,high,urgent]',
    ];

    protected $validationMessages = [
        'title' => [
            'required'   => 'عنوان البلاغ مطلوب',
            'min_length' => 'العنوان يجب أن يكون 5 أحرف على الأقل',
        ],
        'description' => [
            'required'   => 'وصف البلاغ مطلوب',
            'min_length' => 'الوصف يجب أن يكون 10 أحرف على الأقل',
        ],
        'category' => [
            'required' => 'نوع البلاغ مطلوب',
        ],
    ];

    /**
     * جلب بلاغات مستخدم معين
     */
    public function getByUser(int $userId): array
    {
        return $this->where('user_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * جلب جميع البلاغات مع بيانات المستخدم
     */
    public function getAllWithUser(): array
    {
        return $this->select('complaints.*, users.name as user_name, users.email as user_email, users.student_id')
                    ->join('users', 'users.id = complaints.user_id')
                    ->orderBy('complaints.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * جلب بلاغ واحد مع بيانات المستخدم
     */
    public function getWithUser(int $id): ?array
    {
        return $this->select('complaints.*, users.name as user_name, users.email as user_email, users.student_id, users.department, users.phone')
                    ->join('users', 'users.id = complaints.user_id')
                    ->where('complaints.id', $id)
                    ->first();
    }

    /**
     * تصفية البلاغات
     */
    public function filter(?string $status = null, ?string $category = null, ?string $priority = null): array
    {
        $builder = $this->select('complaints.*, users.name as user_name, users.student_id')
                        ->join('users', 'users.id = complaints.user_id');
        
        if ($status) {
            $builder->where('complaints.status', $status);
        }
        
        if ($category) {
            $builder->where('complaints.category', $category);
        }
        
        if ($priority) {
            $builder->where('complaints.priority', $priority);
        }
        
        return $builder->orderBy('complaints.created_at', 'DESC')->findAll();
    }

    /**
     * إحصائيات البلاغات
     */
    public function getStats(): array
    {
        $total = $this->countAll();
        $open = $this->where('status', 'open')->countAllResults();
        $inProgress = $this->where('status', 'in_progress')->countAllResults();
        $resolved = $this->where('status', 'resolved')->countAllResults();
        $closed = $this->where('status', 'closed')->countAllResults();
        
        return [
            'total'       => $total,
            'open'        => $open,
            'in_progress' => $inProgress,
            'resolved'    => $resolved,
            'closed'      => $closed,
        ];
    }

    /**
     * إحصائيات بلاغات مستخدم معين
     */
    public function getUserStats(int $userId): array
    {
        $total = $this->where('user_id', $userId)->countAllResults();
        $open = $this->where('user_id', $userId)->where('status', 'open')->countAllResults();
        $inProgress = $this->where('user_id', $userId)->where('status', 'in_progress')->countAllResults();
        $resolved = $this->where('user_id', $userId)->where('status', 'resolved')->countAllResults();
        
        return [
            'total'       => $total,
            'open'        => $open,
            'in_progress' => $inProgress,
            'resolved'    => $resolved,
        ];
    }

    /**
     * تحديث حالة البلاغ
     */
    public function updateStatus(int $id, string $status): bool
    {
        $data = ['status' => $status];
        
        if ($status === 'resolved' || $status === 'closed') {
            $data['resolved_at'] = date('Y-m-d H:i:s');
        }
        
        return $this->update($id, $data);
    }

    /**
     * الحصول على التصنيفات المتاحة
     */
    public static function getCategories(): array
    {
        return [
            'أكاديمي' => 'أكاديمي',
            'إداري'   => 'إداري',
            'تقني'    => 'تقني',
            'مالي'    => 'مالي',
            'أخرى'    => 'أخرى',
        ];
    }

    /**
     * الحصول على الأولويات المتاحة
     */
    public static function getPriorities(): array
    {
        return [
            'low'    => 'منخفضة',
            'medium' => 'متوسطة',
            'high'   => 'عالية',
            'urgent' => 'عاجلة',
        ];
    }

    /**
     * الحصول على الحالات المتاحة
     */
    public static function getStatuses(): array
    {
        return [
            'open'        => 'مفتوح',
            'in_progress' => 'تحت المعالجة',
            'resolved'    => 'تم الحل',
            'closed'      => 'مغلق',
        ];
    }
}
