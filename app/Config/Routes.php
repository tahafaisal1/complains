<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// =====================================================
// الصفحة الرئيسية
// =====================================================
$routes->get('/', 'Home::index');
$routes->get('test-login', 'TestController::testLogin');
$routes->get('test-notifications', 'TestController::testNotifications');

// =====================================================
// الأسئلة الشائعة (عامة - بدون تسجيل دخول)
// =====================================================
$routes->get('faq', 'FaqController::index');
$routes->post('faq/view/(:num)', 'FaqController::view/$1');

// =====================================================
// المساعد الذكي (Chatbot)
// =====================================================
$routes->post('chatbot/chat', 'ChatbotController::chat');

// =====================================================
// مسارات المصادقة (Authentication)
// =====================================================
$routes->group('', function ($routes) {
    $routes->get('login', 'AuthController::login');
    $routes->post('login', 'AuthController::attemptLogin');
    $routes->get('register', 'AuthController::register');
    $routes->post('register', 'AuthController::attemptRegister');
    $routes->get('logout', 'AuthController::logout');
});

// =====================================================
// مسارات الإشعارات (للمستخدمين المسجلين)
// =====================================================
$routes->group('notifications', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'NotificationController::index');
    $routes->post('mark-read/(:num)', 'NotificationController::markAsRead/$1');
    $routes->post('mark-all-read', 'NotificationController::markAllAsRead');
    $routes->get('unread-count', 'NotificationController::getUnreadCount');
    $routes->get('recent', 'NotificationController::getRecent');
});

// =====================================================
// مسارات الرسائل (للمستخدمين المسجلين)
// =====================================================
$routes->group('messages', ['filter' => 'auth'], function ($routes) {
    $routes->post('send/(:num)', 'MessageController::send/$1');
    $routes->get('get/(:num)', 'MessageController::getMessages/$1');
});

// =====================================================
// مسارات الطالب (Student Panel)
// =====================================================
$routes->group('student', ['filter' => 'student'], function ($routes) {
    // لوحة التحكم
    $routes->get('dashboard', 'Student\DashboardController::index');
    
    // البلاغات
    $routes->get('complaints', 'Student\ComplaintController::index');
    $routes->get('complaints/create', 'Student\ComplaintController::create');
    $routes->post('complaints/store', 'Student\ComplaintController::store');
    $routes->get('complaints/(:num)', 'Student\ComplaintController::show/$1');
    $routes->post('complaints/rate/(:num)', 'Student\ComplaintController::rate/$1');
    
    // الملف الشخصي
    $routes->get('profile', 'Student\ProfileController::index');
    $routes->post('profile/update', 'Student\ProfileController::update');
});

// =====================================================
// مسارات الإدارة (Admin Panel)
// =====================================================
$routes->group('admin', ['filter' => 'admin'], function ($routes) {
    // لوحة التحكم
    $routes->get('dashboard', 'Admin\DashboardController::index');
    
    // إدارة البلاغات
    $routes->get('complaints', 'Admin\ComplaintController::index');
    $routes->get('complaints/(:num)', 'Admin\ComplaintController::show/$1');
    $routes->post('complaints/update-status/(:num)', 'Admin\ComplaintController::updateStatus/$1');
    $routes->post('complaints/respond/(:num)', 'Admin\ComplaintController::respond/$1');
    $routes->post('complaints/assign/(:num)', 'Admin\ComplaintController::assign/$1');
    
    // إدارة الملاحظات
    $routes->post('notes/add/(:num)', 'Admin\NoteController::add/$1');
    
    // إدارة المستخدمين
    $routes->get('users', 'Admin\UserController::index');
    $routes->get('users/(:num)', 'Admin\UserController::show/$1');
    $routes->post('users/toggle-status/(:num)', 'Admin\UserController::toggleStatus/$1');
    
    // التقارير
    $routes->get('reports', 'Admin\ReportController::index');
    $routes->get('reports/by-category', 'Admin\ReportController::byCategory');
    $routes->get('reports/by-department', 'Admin\ReportController::byDepartment');
    $routes->get('reports/by-period', 'Admin\ReportController::byPeriod');
    $routes->get('reports/export-pdf', 'Admin\ReportController::exportPdf');
    $routes->get('reports/export-excel', 'Admin\ReportController::exportExcel');
    
    // إدارة الأسئلة الشائعة
    $routes->get('faq', 'Admin\FaqController::index');
    $routes->get('faq/create', 'Admin\FaqController::create');
    $routes->post('faq/store', 'Admin\FaqController::store');
    $routes->get('faq/edit/(:num)', 'Admin\FaqController::edit/$1');
    $routes->post('faq/update/(:num)', 'Admin\FaqController::update/$1');
    $routes->post('faq/delete/(:num)', 'Admin\FaqController::delete/$1');
    $routes->post('faq/toggle-status/(:num)', 'Admin\FaqController::toggleStatus/$1');
});

