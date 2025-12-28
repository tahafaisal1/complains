<?php

namespace App\Models;

use CodeIgniter\Model;

class FaqModel extends Model
{
    protected $table            = 'faqs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'question', 'answer', 'category', 'sort_order', 'is_active', 'views'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * الحصول على الأسئلة النشطة
     */
    public function getActive()
    {
        return $this->where('is_active', 1)
                    ->orderBy('sort_order', 'ASC')
                    ->orderBy('id', 'ASC')
                    ->findAll();
    }

    /**
     * الحصول على الأسئلة مجمعة حسب التصنيف
     */
    public function getGroupedByCategory()
    {
        $faqs = $this->getActive();
        $grouped = [];
        
        foreach ($faqs as $faq) {
            $category = $faq['category'] ?: 'عام';
            if (!isset($grouped[$category])) {
                $grouped[$category] = [];
            }
            $grouped[$category][] = $faq;
        }
        
        return $grouped;
    }

    /**
     * البحث في الأسئلة
     */
    public function search($keyword)
    {
        return $this->where('is_active', 1)
                    ->groupStart()
                    ->like('question', $keyword)
                    ->orLike('answer', $keyword)
                    ->groupEnd()
                    ->orderBy('sort_order', 'ASC')
                    ->findAll();
    }

    /**
     * زيادة عدد المشاهدات
     */
    public function incrementViews($id)
    {
        return $this->set('views', 'views + 1', false)
                    ->where('id', $id)
                    ->update();
    }

    /**
     * الحصول على التصنيفات المتاحة
     */
    public function getCategories()
    {
        $result = $this->select('category')
                       ->where('category IS NOT NULL')
                       ->where('category !=', '')
                       ->distinct()
                       ->findAll();
        
        return array_column($result, 'category');
    }

    /**
     * جميع الأسئلة للإدارة
     */
    public function getAllForAdmin()
    {
        return $this->orderBy('sort_order', 'ASC')
                    ->orderBy('id', 'ASC')
                    ->findAll();
    }
}
