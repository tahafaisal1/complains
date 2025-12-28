<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\ComplaintModel;

class UserController extends BaseController
{
    protected $userModel;
    protected $complaintModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->complaintModel = new ComplaintModel();
    }

    /**
     * عرض جميع المستخدمين
     */
    public function index()
    {
        $users = $this->userModel->orderBy('created_at', 'DESC')->findAll();

        $data = [
            'title'     => 'إدارة المستخدمين',
            'pageTitle' => 'إدارة المستخدمين',
            'users'     => $users,
        ];

        return view('admin/users/index', $data);
    }

    /**
     * عرض تفاصيل مستخدم
     */
    public function show($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'المستخدم غير موجود');
        }

        $complaints = $this->complaintModel->getByUser($id);

        $data = [
            'title'      => 'تفاصيل المستخدم',
            'pageTitle'  => 'تفاصيل المستخدم',
            'user'       => $user,
            'complaints' => $complaints,
            'statuses'   => ComplaintModel::getStatuses(),
        ];

        return view('admin/users/show', $data);
    }

    /**
     * تفعيل/تعطيل مستخدم
     */
    public function toggleStatus($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'المستخدم غير موجود');
        }

        // لا يمكن تعطيل المستخدم الحالي
        if ($user['id'] == session()->get('user_id')) {
            return redirect()->back()->with('error', 'لا يمكنك تعطيل حسابك الخاص');
        }

        $newStatus = $user['is_active'] ? 0 : 1;
        
        if ($this->userModel->update($id, ['is_active' => $newStatus])) {
            $message = $newStatus ? 'تم تفعيل الحساب بنجاح' : 'تم تعطيل الحساب بنجاح';
            return redirect()->back()->with('success', $message);
        }

        return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث الحالة');
    }
}
