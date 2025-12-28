<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * فلتر للتحقق من صلاحيات الإداري
 */
class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'يجب تسجيل الدخول للوصول لهذه الصفحة');
        }

        if ($session->get('user_role') !== 'admin') {
            return redirect()->to('/student/dashboard')->with('error', 'ليس لديك صلاحية للوصول لهذه الصفحة');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // لا شيء بعد الطلب
    }
}
