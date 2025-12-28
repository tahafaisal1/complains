<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-plus me-2"></i>إضافة سؤال جديد</h5>
    </div>
    <div class="card-body">
        <?php if (session()->has('errors')): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach (session('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form action="<?= base_url('admin/faq/store') ?>" method="post">
            <div class="mb-3">
                <label class="form-label">السؤال <span class="text-danger">*</span></label>
                <textarea name="question" class="form-control" rows="2" required
                          placeholder="اكتب السؤال هنا..."><?= old('question') ?></textarea>
            </div>
            
            <div class="mb-3">
                <label class="form-label">الإجابة <span class="text-danger">*</span></label>
                <textarea name="answer" class="form-control" rows="5" required
                          placeholder="اكتب الإجابة هنا..."><?= old('answer') ?></textarea>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">التصنيف</label>
                        <input type="text" name="category" class="form-control" 
                               value="<?= old('category') ?>" 
                               placeholder="مثال: عام، البلاغات، الحساب"
                               list="categories">
                        <datalist id="categories">
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= esc($cat) ?>">
                            <?php endforeach; ?>
                        </datalist>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">الترتيب</label>
                        <input type="number" name="sort_order" class="form-control" 
                               value="<?= old('sort_order', 0) ?>" min="0">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">الحالة</label>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="is_active" 
                                   id="isActive" checked>
                            <label class="form-check-label" for="isActive">نشط</label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>حفظ
                </button>
                <a href="<?= base_url('admin/faq') ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-1"></i>إلغاء
                </a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
