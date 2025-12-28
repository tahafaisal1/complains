<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // تشغيل جميع الـ Seeders بالترتيب
        $this->call('UserSeeder');
        $this->call('ComplaintSeeder');
        
        echo "\n✅ تم تجهيز قاعدة البيانات بجميع البيانات التجريبية!\n";
    }
}
