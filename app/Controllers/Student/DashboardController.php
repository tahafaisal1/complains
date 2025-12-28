<?php

namespace App\Controllers\Student;

use App\Controllers\BaseController;
use App\Models\ComplaintModel;

class DashboardController extends BaseController
{
    protected $complaintModel;

    public function __construct()
    {
        $this->complaintModel = new ComplaintModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');
        
        $data = [
            'title'     => 'لوحة التحكم',
            'pageTitle' => 'لوحة التحكم',
            'stats'     => $this->complaintModel->getUserStats($userId),
            'recent'    => $this->complaintModel->where('user_id', $userId)
                                                ->orderBy('created_at', 'DESC')
                                                ->limit(5)
                                                ->findAll(),
        ];

        return view('student/dashboard', $data);
    }
}
