<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateComplaintsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'description' => [
                'type' => 'TEXT',
            ],
            'category' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'comment'    => 'نوع البلاغ: أكاديمي، إداري، تقني، مالي، أخرى',
            ],
            'priority' => [
                'type'       => 'ENUM',
                'constraint' => ['low', 'medium', 'high', 'urgent'],
                'default'    => 'medium',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['open', 'in_progress', 'resolved', 'closed'],
                'default'    => 'open',
            ],
            'assigned_to' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'الإداري المسؤول عن البلاغ',
            ],
            'attachment' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'comment'    => 'مسار الملف المرفق',
            ],
            'admin_response' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'resolved_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addKey('status');
        $this->forge->addKey('category');
        $this->forge->addKey('priority');
        $this->forge->addKey('assigned_to');
        $this->forge->addKey('created_at');
        
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('assigned_to', 'users', 'id', 'SET NULL', 'CASCADE');
        
        $this->forge->createTable('complaints');
    }

    public function down()
    {
        $this->forge->dropTable('complaints');
    }
}
