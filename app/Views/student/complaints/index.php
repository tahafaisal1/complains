<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">جميع بلاغاتي</h4>
        <small class="text-muted">عدد البلاغات: <?= count($complaints) ?></small>
    </div>
    <a href="<?= base_url('student/complaints/create') ?>" class="btn btn-primary">
        <i class="fas fa-plus-circle me-2"></i>إرسال بلاغ جديد
    </a>
</div>

<?php if (empty($complaints)): ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="fas fa-inbox fa-4x text-muted mb-4"></i>
            <h4>لا توجد بلاغات</h4>
            <p class="text-muted mb-4">لم ترسل أي بلاغات بعد. ابدأ بإرسال بلاغك الأول!</p>
            <a href="<?= base_url('student/complaints/create') ?>" class="btn btn-primary btn-lg">
                <i class="fas fa-plus me-2"></i>إرسال بلاغ جديد
            </a>
        </div>
    </div>
<?php else: ?>
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>العنوان</th>
                            <th>النوع</th>
                            <th>الأولوية</th>
                            <th>الحالة</th>
                            <th>تاريخ الإرسال</th>
                            <th>إجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($complaints as $complaint): ?>
                            <tr>
                                <td><strong>#<?= $complaint['id'] ?></strong></td>
                                <td>
                                    <a href="<?= base_url('student/complaints/' . $complaint['id']) ?>" class="text-decoration-none">
                                        <?= esc($complaint['title']) ?>
                                    </a>
                                </td>
                                <td><?= esc($complaint['category']) ?></td>
                                <td>
                                    <span class="badge badge-<?= $complaint['priority'] ?>">
                                        <?php
                                        $priorityLabels = [
                                            'low' => 'منخفضة',
                                            'medium' => 'متوسطة',
                                            'high' => 'عالية',
                                            'urgent' => 'عاجلة'
                                        ];
                                        echo $priorityLabels[$complaint['priority']] ?? $complaint['priority'];
                                        ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-<?= $complaint['status'] ?>">
                                        <?= $statuses[$complaint['status']] ?? $complaint['status'] ?>
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        <?= date('Y/m/d H:i', strtotime($complaint['created_at'])) ?>
                                    </small>
                                </td>
                                <td>
                                    <a href="<?= base_url('student/complaints/' . $complaint['id']) ?>" 
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye me-1"></i>عرض
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
