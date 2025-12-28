<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ComplaintSeeder extends Seeder
{
    public function run()
    {
        $complaints = [
            [
                'user_id'     => 2, // أحمد محمد
                'title'       => 'مشكلة في التسجيل للمواد',
                'description' => 'لا أستطيع التسجيل في مادة البرمجة المتقدمة رغم توفر المتطلبات السابقة. يظهر لي رسالة خطأ عند محاولة التسجيل.',
                'category'    => 'أكاديمي',
                'priority'    => 'high',
                'status'      => 'open',
                'assigned_to' => null,
                'created_at'  => date('Y-m-d H:i:s', strtotime('-5 days')),
                'updated_at'  => date('Y-m-d H:i:s', strtotime('-5 days')),
            ],
            [
                'user_id'     => 2, // أحمد محمد
                'title'       => 'طلب شهادة حضور',
                'description' => 'أحتاج شهادة تثبت حضوري للفصل الدراسي الحالي لتقديمها لجهة عمل.',
                'category'    => 'إداري',
                'priority'    => 'medium',
                'status'      => 'in_progress',
                'assigned_to' => 1,
                'created_at'  => date('Y-m-d H:i:s', strtotime('-3 days')),
                'updated_at'  => date('Y-m-d H:i:s', strtotime('-1 day')),
            ],
            [
                'user_id'     => 3, // فاطمة علي
                'title'       => 'مشكلة في بوابة الطالب',
                'description' => 'لا أستطيع الدخول لبوابة الطالب منذ يومين. تظهر رسالة أن الحساب معطل.',
                'category'    => 'تقني',
                'priority'    => 'urgent',
                'status'      => 'open',
                'assigned_to' => null,
                'created_at'  => date('Y-m-d H:i:s', strtotime('-2 days')),
                'updated_at'  => date('Y-m-d H:i:s', strtotime('-2 days')),
            ],
            [
                'user_id'     => 3, // فاطمة علي
                'title'       => 'استفسار عن الرسوم الدراسية',
                'description' => 'أريد الاستفسار عن تفاصيل الرسوم الدراسية وموعد السداد للفصل القادم.',
                'category'    => 'مالي',
                'priority'    => 'low',
                'status'      => 'resolved',
                'assigned_to' => 1,
                'admin_response' => 'تم إرسال تفاصيل الرسوم على بريدك الإلكتروني. موعد السداد هو 15 من الشهر القادم.',
                'resolved_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'created_at'  => date('Y-m-d H:i:s', strtotime('-7 days')),
                'updated_at'  => date('Y-m-d H:i:s', strtotime('-1 day')),
            ],
            [
                'user_id'     => 4, // خالد سعيد
                'title'       => 'اقتراح تحسين المكتبة',
                'description' => 'أقترح زيادة ساعات عمل المكتبة خلال فترة الاختبارات لتكون متاحة حتى منتصف الليل.',
                'category'    => 'أخرى',
                'priority'    => 'low',
                'status'      => 'closed',
                'assigned_to' => 1,
                'admin_response' => 'شكراً لاقتراحك. تم رفع الاقتراح للإدارة العليا وسيتم دراسته.',
                'resolved_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'created_at'  => date('Y-m-d H:i:s', strtotime('-10 days')),
                'updated_at'  => date('Y-m-d H:i:s', strtotime('-2 days')),
            ],
        ];

        foreach ($complaints as $complaint) {
            $this->db->table('complaints')->insert($complaint);
        }

        echo "تم إنشاء البلاغات التجريبية بنجاح!\n";
    }
}
