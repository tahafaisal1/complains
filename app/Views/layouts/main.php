<?php helper('notification'); ?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'نظام إدارة البلاغات' ?></title>
    
    <!-- Bootstrap RTL CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Google Fonts - Cairo -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-dark: #4338ca;
            --secondary-color: #06b6d4;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #3b82f6;
            --dark-color: #1e293b;
            --light-bg: #f1f5f9;
            --sidebar-width: 280px;
        }
        
        * {
            font-family: 'Cairo', sans-serif;
        }
        
        body {
            background-color: var(--light-bg);
            min-height: 100vh;
        }
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            right: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, var(--dark-color) 0%, #0f172a 100%);
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
        }
        
        .sidebar-header {
            padding: 25px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-brand {
            color: white;
            font-size: 1.4rem;
            font-weight: 700;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .sidebar-brand i {
            font-size: 1.8rem;
            color: var(--secondary-color);
        }
        
        .sidebar-menu {
            padding: 20px 0;
        }
        
        .menu-label {
            color: rgba(255,255,255,0.4);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 0 20px;
            margin-bottom: 10px;
        }
        
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 20px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: all 0.3s ease;
            border-right: 3px solid transparent;
        }
        
        .sidebar-link:hover, .sidebar-link.active {
            background: rgba(255,255,255,0.08);
            color: white;
            border-right-color: var(--secondary-color);
        }
        
        .sidebar-link i {
            width: 24px;
            font-size: 1.1rem;
        }
        
        /* Main Content */
        .main-content {
            margin-right: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s ease;
        }
        
        /* Top Navbar */
        .top-navbar {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-color);
            margin: 0;
        }
        
        .user-dropdown {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.2rem;
        }
        
        .user-info {
            text-align: right;
        }
        
        .user-name {
            font-weight: 600;
            color: var(--dark-color);
            font-size: 0.95rem;
        }
        
        .user-role {
            font-size: 0.8rem;
            color: #64748b;
        }
        
        /* Content Area */
        .content-area {
            padding: 30px;
        }
        
        /* Cards */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 25px rgba(0,0,0,0.06);
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 35px rgba(0,0,0,0.1);
        }
        
        .card-header {
            background: transparent;
            border-bottom: 1px solid #e2e8f0;
            padding: 20px 25px;
            font-weight: 600;
        }
        
        .card-body {
            padding: 25px;
        }
        
        /* Stat Cards */
        .stat-card {
            border-radius: 16px;
            padding: 25px;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
        }
        
        .stat-card.primary { background: linear-gradient(135deg, #4f46e5, #7c3aed); }
        .stat-card.success { background: linear-gradient(135deg, #10b981, #059669); }
        .stat-card.warning { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .stat-card.danger { background: linear-gradient(135deg, #ef4444, #dc2626); }
        .stat-card.info { background: linear-gradient(135deg, #3b82f6, #2563eb); }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 15px;
        }
        
        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .stat-label {
            opacity: 0.9;
            font-size: 0.95rem;
        }
        
        /* Buttons */
        .btn {
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border: none;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(79, 70, 229, 0.4);
        }
        
        /* Status Badges */
        .badge {
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.85rem;
        }
        
        .badge-open { background: #fef3c7; color: #92400e; }
        .badge-in_progress { background: #dbeafe; color: #1e40af; }
        .badge-resolved { background: #d1fae5; color: #065f46; }
        .badge-closed { background: #e2e8f0; color: #475569; }
        
        .badge-low { background: #e0e7ff; color: #3730a3; }
        .badge-medium { background: #fef3c7; color: #92400e; }
        .badge-high { background: #fed7aa; color: #c2410c; }
        .badge-urgent { background: #fee2e2; color: #991b1b; }
        
        /* Tables */
        .table {
            margin-bottom: 0;
        }
        
        .table th {
            background: var(--light-bg);
            font-weight: 600;
            color: var(--dark-color);
            border: none;
            padding: 15px;
        }
        
        .table td {
            padding: 15px;
            vertical-align: middle;
            border-color: #f1f5f9;
        }
        
        /* Forms */
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e2e8f0;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }
        
        /* Alerts */
        .alert {
            border: none;
            border-radius: 12px;
            padding: 16px 20px;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-right: 0;
            }
        }
    </style>
    
    <?= $this->renderSection('styles') ?>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <a href="<?= base_url() ?>" class="sidebar-brand">
                <i class="fas fa-headset"></i>
                <span>نظام البلاغات</span>
            </a>
        </div>
        
        <nav class="sidebar-menu">
            <?php $role = session()->get('user_role'); ?>
            
            <?php if ($role === 'student'): ?>
                <p class="menu-label">القائمة الرئيسية</p>
                <a href="<?= base_url('student/dashboard') ?>" class="sidebar-link <?= uri_string() == 'student/dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-home"></i>
                    <span>لوحة التحكم</span>
                </a>
                <a href="<?= base_url('student/complaints') ?>" class="sidebar-link <?= strpos(uri_string(), 'student/complaints') !== false ? 'active' : '' ?>">
                    <i class="fas fa-clipboard-list"></i>
                    <span>بلاغاتي</span>
                </a>
                <a href="<?= base_url('student/complaints/create') ?>" class="sidebar-link">
                    <i class="fas fa-plus-circle"></i>
                    <span>إرسال بلاغ جديد</span>
                </a>
                <a href="<?= base_url('notifications') ?>" class="sidebar-link <?= uri_string() == 'notifications' ? 'active' : '' ?>">
                    <i class="fas fa-bell"></i>
                    <span>الإشعارات</span>
                </a>
                
                <p class="menu-label mt-4">المساعدة</p>
                <a href="<?= base_url('faq') ?>" class="sidebar-link <?= uri_string() == 'faq' ? 'active' : '' ?>">
                    <i class="fas fa-question-circle"></i>
                    <span>الأسئلة الشائعة</span>
                </a>
                
                <p class="menu-label mt-4">الإعدادات</p>
                <a href="<?= base_url('student/profile') ?>" class="sidebar-link <?= uri_string() == 'student/profile' ? 'active' : '' ?>">
                    <i class="fas fa-user-cog"></i>
                    <span>الملف الشخصي</span>
                </a>
            <?php else: ?>
                <p class="menu-label">القائمة الرئيسية</p>
                <a href="<?= base_url('admin/dashboard') ?>" class="sidebar-link <?= uri_string() == 'admin/dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-chart-pie"></i>
                    <span>لوحة التحكم</span>
                </a>
                <a href="<?= base_url('admin/complaints') ?>" class="sidebar-link <?= strpos(uri_string(), 'admin/complaints') !== false ? 'active' : '' ?>">
                    <i class="fas fa-clipboard-list"></i>
                    <span>إدارة البلاغات</span>
                </a>
                
                <p class="menu-label mt-4">الإدارة</p>
                <a href="<?= base_url('admin/users') ?>" class="sidebar-link <?= strpos(uri_string(), 'admin/users') !== false ? 'active' : '' ?>">
                    <i class="fas fa-users"></i>
                    <span>إدارة المستخدمين</span>
                </a>
                <a href="<?= base_url('admin/reports') ?>" class="sidebar-link <?= strpos(uri_string(), 'admin/reports') !== false ? 'active' : '' ?>">
                    <i class="fas fa-chart-bar"></i>
                    <span>التقارير</span>
                </a>
                <a href="<?= base_url('admin/faq') ?>" class="sidebar-link <?= strpos(uri_string(), 'admin/faq') !== false ? 'active' : '' ?>">
                    <i class="fas fa-question-circle"></i>
                    <span>الأسئلة الشائعة</span>
                </a>
                <a href="<?= base_url('notifications') ?>" class="sidebar-link <?= uri_string() == 'notifications' ? 'active' : '' ?>">
                    <i class="fas fa-bell"></i>
                    <span>الإشعارات</span>
                </a>
            <?php endif; ?>
            
            <p class="menu-label mt-4">الحساب</p>
            <a href="<?= base_url('logout') ?>" class="sidebar-link">
                <i class="fas fa-sign-out-alt"></i>
                <span>تسجيل الخروج</span>
            </a>
        </nav>
    </aside>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Navbar -->
        <header class="top-navbar">
            <h1 class="page-title"><?= $pageTitle ?? 'لوحة التحكم' ?></h1>
            
            <div class="d-flex align-items-center gap-3">
                <!-- Notification Bell -->
                <div class="dropdown">
                    <a href="#" class="position-relative text-dark" data-bs-toggle="dropdown" id="notificationBell">
                        <i class="fas fa-bell" style="font-size: 1.3rem;"></i>
                        <?php $unreadCount = get_unread_count(); ?>
                        <?php if ($unreadCount > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-count">
                                <?= $unreadCount ?>
                            </span>
                        <?php else: ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-count" style="display: none;">0</span>
                        <?php endif; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" style="min-width: 320px;" id="notificationDropdown">
                        <li class="dropdown-header d-flex justify-content-between align-items-center">
                            <span>الإشعارات</span>
                            <a href="<?= base_url('notifications') ?>" class="small">عرض الكل</a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li class="text-center py-3 text-muted" id="noNotifications">لا توجد إشعارات جديدة</li>
                    </ul>
                </div>
                
            <div class="user-dropdown dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none" data-bs-toggle="dropdown">
                    <div class="user-info me-3">
                        <div class="user-name"><?= session()->get('user_name') ?></div>
                        <div class="user-role">
                            <?= session()->get('user_role') === 'admin' ? 'مدير النظام' : 'طالب' ?>
                        </div>
                    </div>
                    <div class="user-avatar">
                        <?= mb_substr(session()->get('user_name'), 0, 1) ?>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="<?= base_url(session()->get('user_role') . '/profile') ?>">
                            <i class="fas fa-user me-2"></i>الملف الشخصي
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="<?= base_url('logout') ?>">
                            <i class="fas fa-sign-out-alt me-2"></i>تسجيل الخروج
                        </a>
                    </li>
                </ul>
            </div>
            </div>
        </header>
        
        <!-- Content Area -->
        <div class="content-area">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?= $this->renderSection('content') ?>
        </div>
    </main>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <?= $this->renderSection('scripts') ?>
    
    <!-- AI Chatbot Widget -->
    <script src="<?= base_url('js/chatbot.js') ?>"></script>
</body>
</html>
