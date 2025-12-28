<?php

namespace App\Controllers;

use App\Models\FaqModel;
use App\Models\ComplaintModel;

class FaqController extends BaseController
{
    protected $faqModel;
    protected $complaintModel;

    public function __construct()
    {
        $this->faqModel = new FaqModel();
        $this->complaintModel = new ComplaintModel();
    }

    /**
     * عرض صفحة الأسئلة الشائعة
     */
    public function index()
    {
        $search = $this->request->getGet('search');
        
        if ($search) {
            $faqs = $this->faqModel->search($search);
            $grouped = [];
            foreach ($faqs as $faq) {
                $category = $faq['category'] ?: 'عام';
                if (!isset($grouped[$category])) {
                    $grouped[$category] = [];
                }
                $grouped[$category][] = $faq;
            }
        } else {
            $grouped = $this->faqModel->getGroupedByCategory();
        }

        // Get stats
        $stats = $this->complaintModel->getStats();

        $data = [
            'title'      => 'الأسئلة الشائعة',
            'pageTitle'  => 'الأسئلة الشائعة',
            'faqGroups'  => $grouped,
            'search'     => $search,
            'categories' => $this->faqModel->getCategories(),
            'stats'      => $stats,
        ];

        // If user is logged in, use main layout, otherwise use a public layout
        if (session()->get('logged_in')) {
            return view('faq/index', $data);
        }
        
        return view('faq/public', $data);
    }

    /**
     * زيادة عدد المشاهدات (AJAX)
     */
    public function view($id)
    {
        $this->faqModel->incrementViews($id);
        return $this->response->setJSON(['success' => true]);
    }
}
