<?php

namespace App\Controllers\Student;

use App\Controllers\BaseController;
use App\Models\UserModel;

class ProfileController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        $data = [
            'title'     => 'الملف الشخصي',
            'pageTitle' => 'الملف الشخصي',
            'user'      => $user,
        ];

        return view('student/profile', $data);
    }

    public function update()
    {
        $userId = session()->get('user_id');
        
        $rules = [
            'name'  => 'required|min_length[3]|max_length[100]',
            'phone' => 'permit_empty|max_length[20]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name'  => $this->request->getPost('name'),
            'phone' => $this->request->getPost('phone'),
        ];

        // تحديث كلمة المرور إذا تم إدخالها
        $newPassword = $this->request->getPost('new_password');
        if (!empty($newPassword)) {
            if (strlen($newPassword) < 6) {
                return redirect()->back()->with('error', 'كلمة المرور يجب أن تكون 6 أحرف على الأقل');
            }
            $data['password'] = $newPassword;
        }

        if ($this->userModel->update($userId, $data)) {
            // تحديث الاسم في الجلسة
            session()->set('user_name', $data['name']);
            return redirect()->back()->with('success', 'تم تحديث الملف الشخصي بنجاح');
        }

        return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث البيانات');
    }
}
