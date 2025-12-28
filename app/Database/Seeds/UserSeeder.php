<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // إنشاء حساب إداري
        $adminData = [
            'name'       => 'مدير النظام',
            'email'      => 'admin@example.com',
            'password'   => password_hash('admin123', PASSWORD_DEFAULT),
            'role'       => 'admin',
            'student_id' => null,
            'department' => 'الإدارة العامة',
            'phone'      => '0500000000',
            'is_active'  => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $this->db->table('users')->insert($adminData);

        // إنشاء حسابات طلاب تجريبية
        $students = [
            [
                'name'       => 'أحمد محمد',
                'email'      => 'ahmed@student.com',
                'password'   => password_hash('student123', PASSWORD_DEFAULT),
                'role'       => 'student',
                'student_id' => 'STU001',
                'department' => 'علوم الحاسب',
                'phone'      => '0501111111',
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'فاطمة علي',
                'email'      => 'fatima@student.com',
                'password'   => password_hash('student123', PASSWORD_DEFAULT),
                'role'       => 'student',
                'student_id' => 'STU002',
                'department' => 'نظم المعلومات',
                'phone'      => '0502222222',
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'خالد سعيد',
                'email'      => 'khaled@student.com',
                'password'   => password_hash('student123', PASSWORD_DEFAULT),
                'role'       => 'student',
                'student_id' => 'STU003',
                'department' => 'هندسة البرمجيات',
                'phone'      => '0503333333',
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        foreach ($students as $student) {
            $this->db->table('users')->insert($student);
        }

        echo "تم إنشاء المستخدمين بنجاح!\n";
        echo "الإداري: admin@example.com / admin123\n";
        echo "الطلاب: ahmed@student.com, fatima@student.com, khaled@student.com / student123\n";
    }
}
