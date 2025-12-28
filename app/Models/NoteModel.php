<?php

namespace App\Models;

use CodeIgniter\Model;

class NoteModel extends Model
{
    protected $table            = 'notes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    
    protected $allowedFields    = [
        'complaint_id',
        'user_id',
        'content',
        'is_internal',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * جلب ملاحظات بلاغ معين
     */
    public function getByComplaint(int $complaintId, bool $includeInternal = false): array
    {
        $builder = $this->select('notes.*, users.name as user_name, users.role as user_role')
                        ->join('users', 'users.id = notes.user_id')
                        ->where('notes.complaint_id', $complaintId);
        
        if (!$includeInternal) {
            $builder->where('notes.is_internal', 0);
        }
        
        return $builder->orderBy('notes.created_at', 'ASC')->findAll();
    }

    /**
     * إضافة ملاحظة جديدة
     */
    public function addNote(int $complaintId, int $userId, string $content, bool $isInternal = false): bool
    {
        return $this->insert([
            'complaint_id' => $complaintId,
            'user_id'      => $userId,
            'content'      => $content,
            'is_internal'  => $isInternal ? 1 : 0,
        ]);
    }
}
