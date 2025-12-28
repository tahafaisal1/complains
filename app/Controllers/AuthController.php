<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class AuthController extends BaseController
{
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = session();
    }

    /**
     * صفحة تسجيل الدخول
     */
    public function login()
    {
        // إذا كان المستخدم مسجل دخوله، توجيهه للصفحة المناسبة
        if ($this->session->get('isLoggedIn')) {
            return $this->redirectByRole();
        }

        return view('auth/login');
    }

    /**
     * معالجة تسجيل الدخول
     */
    public function attemptLogin()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[6]',
        ];

        $messages = [
            'email' => [
                'required'    => 'البريد الإلكتروني مطلوب',
                'valid_email' => 'البريد الإلكتروني غير صالح',
            ],
            'password' => [
                'required'   => 'كلمة المرور مطلوبة',
                'min_length' => 'كلمة المرور يجب أن تكون 6 أحرف على الأقل',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $result = $this->userModel->attemptLogin($email, $password);

        if (!$result['success']) {
            return redirect()->back()->withInput()->with('error', $result['message']);
        }

        $user = $result['user'];

        // حفظ بيانات المستخدم في الجلسة
        $sessionData = [
            'user_id'    => $user['id'],
            'user_name'  => $user['name'],
            'user_email' => $user['email'],
            'user_role'  => $user['role'],
            'isLoggedIn' => true,
        ];

        $this->session->set($sessionData);

        return $this->redirectByRole();
    }

    /**
     * صفحة التسجيل (للطلاب فقط)
     */
    public function register()
    {
        if ($this->session->get('isLoggedIn')) {
            return $this->redirectByRole();
        }

        return view('auth/register');
    }

    /**
     * معالجة تسجيل حساب جديد
     */
    public function attemptRegister()
    {
        $rules = [
            'name'             => 'required|min_length[3]|max_length[100]',
            'email'            => 'required|valid_email|is_unique[users.email]',
            'password'         => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
            'student_id'       => 'required|min_length[3]',
            'department'       => 'required',
        ];

        $messages = [
            'name' => [
                'required'   => 'الاسم مطلوب',
                'min_length' => 'الاسم يجب أن يكون 3 أحرف على الأقل',
            ],
            'email' => [
                'required'    => 'البريد الإلكتروني مطلوب',
                'valid_email' => 'البريد الإلكتروني غير صالح',
                'is_unique'   => 'البريد الإلكتروني مستخدم مسبقاً',
            ],
            'password' => [
                'required'   => 'كلمة المرور مطلوبة',
                'min_length' => 'كلمة المرور يجب أن تكون 6 أحرف على الأقل',
            ],
            'confirm_password' => [
                'required' => 'تأكيد كلمة المرور مطلوب',
                'matches'  => 'كلمة المرور غير متطابقة',
            ],
            'student_id' => [
                'required' => 'الرقم الجامعي مطلوب',
            ],
            'department' => [
                'required' => 'القسم مطلوب',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userData = [
            'name'       => $this->request->getPost('name'),
            'email'      => $this->request->getPost('email'),
            'password'   => $this->request->getPost('password'),
            'role'       => 'student',
            'student_id' => $this->request->getPost('student_id'),
            'department' => $this->request->getPost('department'),
            'phone'      => $this->request->getPost('phone'),
            'is_active'  => 1,
        ];

        if ($this->userModel->insert($userData)) {
            return redirect()->to('/login')->with('success', 'تم إنشاء الحساب بنجاح! يمكنك الآن تسجيل الدخول.');
        }

        return redirect()->back()->withInput()->with('error', 'حدث خطأ أثناء إنشاء الحساب');
    }

    /**
     * تسجيل الخروج
     */
    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/login')->with('success', 'تم تسجيل الخروج بنجاح');
    }

    /**
     * توجيه المستخدم حسب دوره
     */
    private function redirectByRole()
    {
        $role = $this->session->get('user_role');
        
        if ($role === 'admin') {
            return redirect()->to('/admin/dashboard');
        }
        
        return redirect()->to('/student/dashboard');
    }
}
