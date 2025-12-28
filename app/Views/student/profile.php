<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-user-cog me-2"></i>الملف الشخصي
            </div>
            <div class="card-body">
                <form action="<?= base_url('student/profile/update') ?>" method="POST">
                    <?= csrf_field() ?>

                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0 ps-3">
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="name" class="form-label">الاسم الكامل</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="<?= esc($user['name']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">البريد الإلكتروني</label>
                            <input type="email" class="form-control" id="email"
                                value="<?= esc($user['email']) ?>" disabled>
                            <div class="form-text">لا يمكن تغيير البريد الإلكتروني</div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="student_id" class="form-label">الرقم القيد</label>
                            <input type="text" class="form-control" id="student_id"
                                value="<?= esc($user['student_id']) ?>" disabled>
                        </div>
                        <div class="col-md-6">
                            <label for="department" class="form-label">القسم</label>
                            <input type="text" class="form-control" id="department"
                                value="<?= esc($user['department']) ?>" disabled>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="phone" class="form-label">رقم الهاتف</label>
                        <input type="tel" class="form-control" id="phone" name="phone"
                            value="<?= esc($user['phone']) ?>">
                    </div>

                    <hr class="my-4">

                    <h5 class="mb-3">تغيير كلمة المرور</h5>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="new_password" class="form-label">كلمة المرور الجديدة</label>
                            <input type="password" class="form-control" id="new_password" name="new_password">
                            <div class="form-text">اتركها فارغة إذا لم ترد تغييرها</div>
                        </div>
                        <div class="col-md-6">
                            <label for="confirm_password" class="form-label">تأكيد كلمة المرور</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save me-2"></i>حفظ التغييرات
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="user-avatar mx-auto mb-3" style="width: 100px; height: 100px; font-size: 3rem;">
                    <?= mb_substr($user['name'], 0, 1) ?>
                </div>
                <h4><?= esc($user['name']) ?></h4>
                <p class="text-muted mb-3"><?= esc($user['email']) ?></p>
                <span class="badge bg-primary px-3 py-2">طالب</span>

                <hr class="my-4">

                <div class="text-start">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">الرقم القيد:</span>
                        <strong><?= esc($user['student_id']) ?></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">القسم:</span>
                        <strong><?= esc($user['department']) ?></strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">تاريخ التسجيل:</span>
                        <strong><?= date('Y/m/d', strtotime($user['created_at'])) ?></strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>