<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>
<div class="auth-container">
    <div class="auth-card" style="max-width: 500px;">
        <div class="auth-header">
            <div class="logo-icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <h1>إنشاء حساب جديد</h1>
            <p>سجّل حسابك للبدء في استخدام النظام</p>
        </div>

        <div class="auth-body">
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= session()->getFlashdata('error') ?>
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

            <form action="<?= base_url('register') ?>" method="POST">
                <?= csrf_field() ?>

                <div class="form-floating">
                    <input type="text" class="form-control" id="name" name="name"
                        placeholder="الاسم الكامل" value="<?= old('name') ?>" required>
                    <label for="name">
                        <i class="fas fa-user me-2"></i>الاسم الكامل
                    </label>
                </div>

                <div class="form-floating">
                    <input type="email" class="form-control" id="email" name="email"
                        placeholder="البريد الإلكتروني" value="<?= old('email') ?>" required>
                    <label for="email">
                        <i class="fas fa-envelope me-2"></i>البريد الإلكتروني
                    </label>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="student_id" name="student_id"
                                placeholder="الرقم القيد" value="<?= old('student_id') ?>" required>
                            <label for="student_id">
                                <i class="fas fa-id-card me-2"></i>الرقم الرقيد
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="tel" class="form-control" id="phone" name="phone"
                                placeholder="رقم الهاتف" value="<?= old('phone') ?>">
                            <label for="phone">
                                <i class="fas fa-phone me-2"></i>رقم الهاتف
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-floating">
                    <select class="form-control" id="department" name="department" required>
                        <option value="">اختر القسم</option>
                        <option value="حاسب" <?= old('department') == 'حاسب' ? 'selected' : '' ?>>حاسب</option>
                        <option value="تحكم" <?= old('department') == 'تحكم' ? 'selected' : '' ?>>تحكم</option>
                        <option value="اتصالات" <?= old('department') == 'اتصالات' ? 'selected' : '' ?>>اتصالات</option>
                    </select>
                    <label for="department">
                        <i class="fas fa-building me-2"></i>القسم
                    </label>
                </div>

                <div class="form-floating">
                    <input type="password" class="form-control" id="password" name="password"
                        placeholder="كلمة المرور" required>
                    <label for="password">
                        <i class="fas fa-lock me-2"></i>كلمة المرور
                    </label>
                </div>

                <div class="form-floating">
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                        placeholder="تأكيد كلمة المرور" required>
                    <label for="confirm_password">
                        <i class="fas fa-lock me-2"></i>تأكيد كلمة المرور
                    </label>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-user-plus me-2"></i>إنشاء الحساب
                    </button>
                </div>
            </form>
        </div>

        <div class="auth-footer">
            <p class="mb-0">
                لديك حساب بالفعل؟
                <a href="<?= base_url('login') ?>">تسجيل الدخول</a>
            </p>
        </div>
    </div>
</div>
<?= $this->endSection() ?>