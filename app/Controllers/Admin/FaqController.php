<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\FaqModel;

class FaqController extends BaseController
{
    protected $faqModel;

    public function __construct()
    {
        $this->faqModel = new FaqModel();
    }

    /**
     * عرض جميع الأسئلة
     */
    public function index()
    {
        try {
            $faqs = $this->faqModel->getAllForAdmin();
        } catch (\Exception $e) {
            // جدول faqs غير موجود - يجب تشغيل database_additions.sql
            return redirect()->to('/admin/dashboard')->with('error', 'خطأ: يجب تشغيل ملف database_additions.sql في قاعدة البيانات أولاً');
        }

        $data = [
            'title'     => 'إدارة الأسئلة الشائعة',
            'pageTitle' => 'إدارة الأسئلة الشائعة',
            'faqs'      => $faqs,
        ];

        return view('admin/faq/index', $data);
    }

    /**
     * صفحة إضافة سؤال جديد
     */
    public function create()
    {
        $data = [
            'title'      => 'إضافة سؤال جديد',
            'pageTitle'  => 'إضافة سؤال جديد',
            'categories' => $this->faqModel->getCategories(),
        ];

        return view('admin/faq/create', $data);
    }

    /**
     * حفظ سؤال جديد
     */
    public function store()
    {
        $rules = [
            'question' => 'required|min_length[10]',
            'answer'   => 'required|min_length[10]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->faqModel->insert([
            'question'   => $this->request->getPost('question'),
            'answer'     => $this->request->getPost('answer'),
            'category'   => $this->request->getPost('category') ?: null,
            'sort_order' => (int) $this->request->getPost('sort_order'),
            'is_active'  => $this->request->getPost('is_active') ? 1 : 0,
        ]);

        return redirect()->to('/admin/faq')->with('success', 'تم إضافة السؤال بنجاح');
    }

    /**
     * صفحة تعديل سؤال
     */
    public function edit($id)
    {
        $faq = $this->faqModel->find($id);
        
        if (!$faq) {
            return redirect()->to('/admin/faq')->with('error', 'السؤال غير موجود');
        }

        $data = [
            'title'      => 'تعديل السؤال',
            'pageTitle'  => 'تعديل السؤال',
            'faq'        => $faq,
            'categories' => $this->faqModel->getCategories(),
        ];

        return view('admin/faq/edit', $data);
    }

    /**
     * تحديث سؤال
     */
    public function update($id)
    {
        $faq = $this->faqModel->find($id);
        
        if (!$faq) {
            return redirect()->to('/admin/faq')->with('error', 'السؤال غير موجود');
        }

        $rules = [
            'question' => 'required|min_length[10]',
            'answer'   => 'required|min_length[10]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->faqModel->update($id, [
            'question'   => $this->request->getPost('question'),
            'answer'     => $this->request->getPost('answer'),
            'category'   => $this->request->getPost('category') ?: null,
            'sort_order' => (int) $this->request->getPost('sort_order'),
            'is_active'  => $this->request->getPost('is_active') ? 1 : 0,
        ]);

        return redirect()->to('/admin/faq')->with('success', 'تم تحديث السؤال بنجاح');
    }

    /**
     * حذف سؤال
     */
    public function delete($id)
    {
        $faq = $this->faqModel->find($id);
        
        if (!$faq) {
            return redirect()->to('/admin/faq')->with('error', 'السؤال غير موجود');
        }

        $this->faqModel->delete($id);

        return redirect()->to('/admin/faq')->with('success', 'تم حذف السؤال بنجاح');
    }

    /**
     * تغيير حالة السؤال (تفعيل/تعطيل)
     */
    public function toggleStatus($id)
    {
        $faq = $this->faqModel->find($id);
        
        if (!$faq) {
            return redirect()->to('/admin/faq')->with('error', 'السؤال غير موجود');
        }

        $this->faqModel->update($id, [
            'is_active' => $faq['is_active'] ? 0 : 1,
        ]);

        $status = $faq['is_active'] ? 'تعطيل' : 'تفعيل';
        return redirect()->to('/admin/faq')->with('success', "تم {$status} السؤال بنجاح");
    }
}
