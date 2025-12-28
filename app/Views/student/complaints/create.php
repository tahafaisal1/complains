<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-plus-circle me-2"></i>إرسال بلاغ جديد
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0 ps-3">
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('student/complaints/store') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    
                    <div class="mb-4">
                        <label for="title" class="form-label">عنوان البلاغ <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" 
                               value="<?= old('title') ?>" placeholder="أدخل عنوان مختصر للبلاغ" required>
                        <div class="form-text">اكتب عنواناً واضحاً ومختصراً يصف المشكلة</div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="category" class="form-label">نوع البلاغ <span class="text-danger">*</span></label>
                            <select class="form-select" id="category" name="category" required>
                                <option value="">اختر النوع</option>
                                <?php foreach ($categories as $key => $value): ?>
                                    <option value="<?= $key ?>" <?= old('category') == $key ? 'selected' : '' ?>>
                                        <?= $value ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="priority" class="form-label">الأولوية <span class="text-danger">*</span></label>
                            <select class="form-select" id="priority" name="priority" required>
                                <option value="">اختر الأولوية</option>
                                <?php foreach ($priorities as $key => $value): ?>
                                    <option value="<?= $key ?>" <?= old('priority', 'medium') == $key ? 'selected' : '' ?>>
                                        <?= $value ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label">تفاصيل البلاغ <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="description" name="description" rows="6" 
                                  placeholder="اشرح المشكلة بالتفصيل..." required><?= old('description') ?></textarea>
                        <div class="form-text">كلما كانت التفاصيل أكثر وضوحاً، كلما استطعنا مساعدتك بشكل أفضل</div>
                    </div>

                    <div class="mb-4">
                        <label for="attachment" class="form-label">إرفاق ملف (اختياري)</label>
                        <input type="file" class="form-control" id="attachment" name="attachment">
                        <div class="form-text">يمكنك إرفاق صورة أو مستند يوضح المشكلة (الحد الأقصى: 2MB)</div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-paper-plane me-2"></i>إرسال البلاغ
                        </button>
                        <a href="<?= base_url('student/complaints') ?>" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-times me-2"></i>إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
