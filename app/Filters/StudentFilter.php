<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * فلتر للتحقق من صلاحيات الطالب
 */
class StudentFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'يجب تسجيل الدخول للوصول لهذه الصفحة');
        }

        if ($session->get('user_role') !== 'student') {
            return redirect()->to('/admin/dashboard')->with('error', 'هذه الصفحة للطلاب فقط');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // لا شيء بعد الطلب
    }
}
