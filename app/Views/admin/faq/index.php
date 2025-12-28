<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-question-circle me-2"></i>إدارة الأسئلة الشائعة</h5>
        <a href="<?= base_url('admin/faq/create') ?>" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i>إضافة سؤال
        </a>
    </div>
    <div class="card-body p-0">
        <?php if (empty($faqs)): ?>
            <div class="text-center py-5">
                <i class="fas fa-question-circle text-muted" style="font-size: 4rem;"></i>
                <p class="text-muted mt-3">لا توجد أسئلة</p>
                <a href="<?= base_url('admin/faq/create') ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>إضافة سؤال جديد
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>السؤال</th>
                            <th style="width: 120px;">التصنيف</th>
                            <th style="width: 80px;" class="text-center">الترتيب</th>
                            <th style="width: 80px;" class="text-center">المشاهدات</th>
                            <th style="width: 80px;" class="text-center">الحالة</th>
                            <th style="width: 150px;" class="text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($faqs as $faq): ?>
                            <tr>
                                <td><?= $faq['id'] ?></td>
                                <td>
                                    <strong><?= esc(mb_substr($faq['question'], 0, 60)) ?><?= mb_strlen($faq['question']) > 60 ? '...' : '' ?></strong>
                                </td>
                                <td>
                                    <span class="badge bg-secondary"><?= esc($faq['category'] ?: 'عام') ?></span>
                                </td>
                                <td class="text-center"><?= $faq['sort_order'] ?></td>
                                <td class="text-center"><?= $faq['views'] ?></td>
                                <td class="text-center">
                                    <?php if ($faq['is_active']): ?>
                                        <span class="badge bg-success">نشط</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">معطل</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= base_url('admin/faq/edit/' . $faq['id']) ?>" 
                                           class="btn btn-outline-primary" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="<?= base_url('admin/faq/toggle-status/' . $faq['id']) ?>" 
                                              method="post" class="d-inline">
                                            <button type="submit" class="btn btn-outline-<?= $faq['is_active'] ? 'warning' : 'success' ?>" 
                                                    title="<?= $faq['is_active'] ? 'تعطيل' : 'تفعيل' ?>">
                                                <i class="fas fa-<?= $faq['is_active'] ? 'eye-slash' : 'eye' ?>"></i>
                                            </button>
                                        </form>
                                        <form action="<?= base_url('admin/faq/delete/' . $faq['id']) ?>" 
                                              method="post" class="d-inline" 
                                              onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                            <button type="submit" class="btn btn-outline-danger" title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
