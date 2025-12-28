<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ComplaintModel;
use App\Models\UserModel;

class DashboardController extends BaseController
{
    protected $complaintModel;
    protected $userModel;

    public function __construct()
    {
        $this->complaintModel = new ComplaintModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $stats = $this->complaintModel->getStats();
        
        // البلاغات الأخيرة
        $recentComplaints = $this->complaintModel
            ->select('complaints.*, users.name as user_name')
            ->join('users', 'users.id = complaints.user_id')
            ->orderBy('complaints.created_at', 'DESC')
            ->limit(10)
            ->findAll();
        
        // البلاغات العاجلة المفتوحة
        $urgentComplaints = $this->complaintModel
            ->select('complaints.*, users.name as user_name')
            ->join('users', 'users.id = complaints.user_id')
            ->where('complaints.priority', 'urgent')
            ->whereIn('complaints.status', ['open', 'in_progress'])
            ->orderBy('complaints.created_at', 'ASC')
            ->findAll();

        $data = [
            'title'            => 'لوحة التحكم',
            'pageTitle'        => 'لوحة التحكم',
            'stats'            => $stats,
            'recentComplaints' => $recentComplaints,
            'urgentComplaints' => $urgentComplaints,
            'totalUsers'       => $this->userModel->where('role', 'student')->countAllResults(),
            'statuses'         => ComplaintModel::getStatuses(),
        ];

        return view('admin/dashboard', $data);
    }
}
