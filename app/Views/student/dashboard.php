<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="stat-card primary">
            <div class="stat-icon">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <div class="stat-value"><?= $stats['total'] ?></div>
            <div class="stat-label">إجمالي البلاغات</div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card warning">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-value"><?= $stats['open'] ?></div>
            <div class="stat-label">بلاغات مفتوحة</div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card info">
            <div class="stat-icon">
                <i class="fas fa-spinner"></i>
            </div>
            <div class="stat-value"><?= $stats['in_progress'] ?></div>
            <div class="stat-label">تحت المعالجة</div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card success">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-value"><?= $stats['resolved'] ?></div>
            <div class="stat-label">تم حلها</div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">مرحباً، <?= session()->get('user_name') ?>! 👋</h5>
                        <p class="text-muted mb-0">هل تواجه مشكلة؟ أرسل بلاغك الآن وسنساعدك</p>
                    </div>
                    <a href="<?= base_url('student/complaints/create') ?>" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus-circle me-2"></i>إرسال بلاغ جديد
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Complaints -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-history me-2"></i>آخر البلاغات</span>
                <a href="<?= base_url('student/complaints') ?>" class="btn btn-sm btn-outline-primary">
                    عرض الكل
                </a>
            </div>
            <div class="card-body p-0">
                <?php if (empty($recent)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">لم ترسل أي بلاغات بعد</p>
                        <a href="<?= base_url('student/complaints/create') ?>" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>إرسال أول بلاغ
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>العنوان</th>
                                    <th>النوع</th>
                                    <th>الحالة</th>
                                    <th>التاريخ</th>
                                    <th>إجراء</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent as $complaint): ?>
                                    <tr>
                                        <td><strong>#<?= $complaint['id'] ?></strong></td>
                                        <td><?= esc($complaint['title']) ?></td>
                                        <td><?= esc($complaint['category']) ?></td>
                                        <td>
                                            <span class="badge badge-<?= $complaint['status'] ?>">
                                                <?php
                                                $statusLabels = [
                                                    'open' => 'مفتوح',
                                                    'in_progress' => 'تحت المعالجة',
                                                    'resolved' => 'تم الحل',
                                                    'closed' => 'مغلق'
                                                ];
                                                echo $statusLabels[$complaint['status']] ?? $complaint['status'];
                                                ?>
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?= date('Y/m/d', strtotime($complaint['created_at'])) ?>
                                            </small>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('student/complaints/' . $complaint['id']) ?>" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
