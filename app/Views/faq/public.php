<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'الأسئلة الشائعة' ?></title>
    
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
        }
        
        * {
            font-family: 'Cairo', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%);
            min-height: 100vh;
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 60px 0 80px;
            margin-bottom: -40px;
        }
        
        .search-box {
            background: white;
            border-radius: 50px;
            padding: 8px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        }
        
        .search-box input {
            border: none;
            padding: 15px 25px;
            border-radius: 50px;
            font-size: 1.1rem;
        }
        
        .search-box input:focus {
            outline: none;
            box-shadow: none;
        }
        
        .search-box button {
            border-radius: 50px;
            padding: 15px 30px;
        }
        
        .faq-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            margin-bottom: 20px;
            overflow: hidden;
        }
        
        .category-title {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 15px 20px;
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .accordion-button {
            font-weight: 600;
            padding: 20px;
        }
        
        .accordion-button:not(.collapsed) {
            background: #f8fafc;
            color: var(--primary-color);
        }
        
        .accordion-button:focus {
            box-shadow: none;
            border-color: transparent;
        }
        
        .accordion-body {
            padding: 20px;
            background: #f8fafc;
            line-height: 1.8;
        }
        
        .login-cta {
            background: white;
            border-radius: 16px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.4rem;
        }
        
        .navbar-brand i {
            color: var(--secondary-color);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: var(--primary-color);">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url() ?>">
                <i class="fas fa-headset me-2"></i>نظام البلاغات
            </a>
            <div class="ms-auto">
                <a href="<?= base_url('login') ?>" class="btn btn-light btn-sm">
                    <i class="fas fa-sign-in-alt me-1"></i>تسجيل الدخول
                </a>
            </div>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container text-center">
            <h1 class="mb-3 display-4 fw-bold"><i class="fas fa-question-circle me-2"></i>الأسئلة الشائعة</h1>
            <p class="lead mb-5 opacity-75">نحن هنا لمساعدتك. ابحث عن إجابات لأسئلتك الشائعة</p>
            
            <div class="row justify-content-center mb-5">
                <div class="col-md-8">
                    <form action="<?= base_url('faq') ?>" method="get">
                        <div class="search-box d-flex bg-white rounded-pill p-1 shadow-lg">
                            <input type="text" name="search" class="form-control border-0 rounded-pill ps-4 py-3" 
                                   style="font-size: 1.1rem; box-shadow: none;"
                                   placeholder="ابحث عن سؤالك هنا..." 
                                   value="<?= esc($search ?? '') ?>">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 m-1 fw-bold">
                                <i class="fas fa-search me-1"></i> بحث
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Stats Section -->
            <div class="row justify-content-center g-4 mt-4">
                <div class="col-md-3 col-sm-6">
                    <div class="stats-card glass-effect p-3 rounded-4 text-white">
                        <div class="display-5 fw-bold mb-1"><?= $stats['total'] ?? 0 ?></div>
                        <div class="small opacity-75"><i class="fas fa-clipboard-list me-1"></i> إجمالي البلاغات</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stats-card glass-effect p-3 rounded-4 text-white">
                        <div class="display-5 fw-bold mb-1"><?= $stats['resolved'] ?? 0 ?></div>
                        <div class="small opacity-75"><i class="fas fa-check-circle me-1"></i> تم حلها</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stats-card glass-effect p-3 rounded-4 text-white">
                        <div class="display-5 fw-bold mb-1"><?= $stats['in_progress'] ?? 0 ?></div>
                        <div class="small opacity-75"><i class="fas fa-sync-alt me-1 fa-spin"></i> قيد المعالجة</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stats-card glass-effect p-3 rounded-4 text-white">
                        <div class="display-5 fw-bold mb-1">24/7</div>
                        <div class="small opacity-75"><i class="fas fa-headset me-1"></i> دعم متواصل</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: transform 0.3s ease;
        }
        .stats-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.15);
        }
    </style>
    
    <!-- FAQ Content -->
    <div class="container py-5">
        <?php if (!empty($search)): ?>
            <div class="alert alert-info mb-4">
                <i class="fas fa-search me-2"></i>
                نتائج البحث عن: <strong><?= esc($search) ?></strong>
                <a href="<?= base_url('faq') ?>" class="btn btn-sm btn-outline-info me-3">
                    <i class="fas fa-times"></i> مسح البحث
                </a>
            </div>
        <?php endif; ?>
        
        <div class="row">
            <div class="col-lg-8">
                <?php if (empty($faqGroups)): ?>
                    <div class="faq-card p-5 text-center">
                        <i class="fas fa-search text-muted" style="font-size: 4rem;"></i>
                        <h4 class="mt-3 text-muted">لا توجد نتائج</h4>
                        <p class="text-muted">جرب البحث بكلمات مختلفة</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($faqGroups as $category => $faqs): ?>
                        <div class="faq-card">
                            <div class="category-title">
                                <i class="fas fa-folder me-2"></i><?= esc($category) ?>
                            </div>
                            <div class="accordion" id="faq-<?= md5($category) ?>">
                                <?php foreach ($faqs as $index => $faq): ?>
                                    <div class="accordion-item border-0 border-bottom">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" 
                                                    data-bs-toggle="collapse" 
                                                    data-bs-target="#faq-item-<?= $faq['id'] ?>">
                                                <i class="fas fa-question-circle text-primary me-2"></i>
                                                <?= esc($faq['question']) ?>
                                            </button>
                                        </h2>
                                        <div id="faq-item-<?= $faq['id'] ?>" 
                                             class="accordion-collapse collapse" 
                                             data-bs-parent="#faq-<?= md5($category) ?>">
                                            <div class="accordion-body">
                                                <?= nl2br(esc($faq['answer'])) ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <div class="col-lg-4">
                <div class="login-cta sticky-top" style="top: 20px;">
                    <i class="fas fa-user-circle text-primary mb-3" style="font-size: 4rem;"></i>
                    <h5>لم تجد إجابتك؟</h5>
                    <p class="text-muted">سجل دخولك وأرسل بلاغك مباشرة</p>
                    <a href="<?= base_url('login') ?>" class="btn btn-primary w-100 mb-2">
                        <i class="fas fa-sign-in-alt me-1"></i>تسجيل الدخول
                    </a>
                    <a href="<?= base_url('register') ?>" class="btn btn-outline-primary w-100">
                        <i class="fas fa-user-plus me-1"></i>إنشاء حساب جديد
                    </a>
                </div>
                
                <?php if (!empty($categories)): ?>
                <div class="faq-card mt-4 p-3">
                    <h6 class="mb-3"><i class="fas fa-tags me-2"></i>التصنيفات</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <?php foreach ($categories as $cat): ?>
                            <a href="<?= base_url('faq?search=' . urlencode($cat)) ?>" 
                               class="btn btn-sm btn-outline-primary">
                                <?= esc($cat) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; <?= date('Y') ?> نظام إدارة البلاغات - جميع الحقوق محفوظة</p>
        </div>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
