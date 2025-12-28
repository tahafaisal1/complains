<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    
    protected $allowedFields    = [
        'name',
        'email',
        'password',
        'role',
        'student_id',
        'department',
        'phone',
        'is_active',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'name'     => 'required|min_length[3]|max_length[100]',
        'email'    => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password' => 'required|min_length[6]',
        'role'     => 'required|in_list[student,admin]',
    ];

    protected $validationMessages = [
        'name' => [
            'required'   => 'الاسم مطلوب',
            'min_length' => 'الاسم يجب أن يكون 3 أحرف على الأقل',
        ],
        'email' => [
            'required'    => 'البريد الإلكتروني مطلوب',
            'valid_email' => 'البريد الإلكتروني غير صالح',
            'is_unique'   => 'البريد الإلكتروني مستخدم مسبقاً',
        ],
        'password' => [
            'required'   => 'كلمة المرور مطلوبة',
            'min_length' => 'كلمة المرور يجب أن تكون 6 أحرف على الأقل',
        ],
    ];

    protected $skipValidation = false;

    // تفعيل الـ Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['hashPassword'];
    protected $beforeUpdate   = ['hashPassword'];

    /**
     * البحث عن مستخدم بالبريد الإلكتروني
     */
    public function findByEmail(string $email): ?array
    {
        return $this->where('email', $email)->first();
    }

    /**
     * التحقق من صحة بيانات الدخول
     */
    public function attemptLogin(string $email, string $password): array
    {
        $user = $this->findByEmail($email);
        
        if (!$user) {
            return [
                'success' => false,
                'message' => 'البريد الإلكتروني غير مسجل',
            ];
        }

        if (!$user['is_active']) {
            return [
                'success' => false,
                'message' => 'الحساب معطل، تواصل مع الإدارة',
            ];
        }

        if (!password_verify($password, $user['password'])) {
            return [
                'success' => false,
                'message' => 'كلمة المرور غير صحيحة',
            ];
        }

        return [
            'success' => true,
            'user'    => $user,
        ];
    }

    /**
     * جلب جميع الإداريين
     */
    public function getAdmins(): array
    {
        return $this->where('role', 'admin')
                    ->where('is_active', 1)
                    ->findAll();
    }

    /**
     * جلب جميع الطلاب
     */
    public function getStudents(): array
    {
        return $this->where('role', 'student')
                    ->where('is_active', 1)
                    ->findAll();
    }

    /**
     * تشفير كلمة المرور قبل الحفظ
     */
    protected function hashPassword(array $data): array
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }
}
