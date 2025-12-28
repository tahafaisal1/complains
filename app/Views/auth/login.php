<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <div class="logo-icon">
                <i class="fas fa-headset"></i>
            </div>
            <h1>نظام إدارة البلاغات</h1>
            <p>قم بتسجيل الدخول للمتابعة</p>
        </div>
        
        <div class="auth-body">
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0 ps-3">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <form action="<?= base_url('login') ?>" method="POST">
                <?= csrf_field() ?>
                
                <div class="form-floating">
                    <input type="email" class="form-control" id="email" name="email" 
                           placeholder="البريد الإلكتروني" value="<?= old('email') ?>" required>
                    <label for="email">
                        <i class="fas fa-envelope me-2"></i>البريد الإلكتروني
                    </label>
                </div>
                
                <div class="form-floating">
                    <input type="password" class="form-control" id="password" name="password" 
                           placeholder="كلمة المرور" required>
                    <label for="password">
                        <i class="fas fa-lock me-2"></i>كلمة المرور
                    </label>
                </div>
                
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-sign-in-alt me-2"></i>تسجيل الدخول
                    </button>
                </div>
            </form>
        </div>
        
        <div class="auth-footer">
            <p class="mb-0">
                ليس لديك حساب؟ 
                <a href="<?= base_url('register') ?>">سجّل الآن</a>
            </p>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
