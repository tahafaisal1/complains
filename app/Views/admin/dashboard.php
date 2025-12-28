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
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-value"><?= $totalUsers ?></div>
            <div class="stat-label">عدد الطلاب</div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Urgent Complaints -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                <span><i class="fas fa-exclamation-triangle me-2"></i>البلاغات العاجلة</span>
                <span class="badge bg-white text-danger"><?= count($urgentComplaints) ?></span>
            </div>
            <div class="card-body p-0">
                <?php if (empty($urgentComplaints)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <p class="text-muted mb-0">لا توجد بلاغات عاجلة 🎉</p>
                    </div>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($urgentComplaints as $complaint): ?>
                            <a href="<?= base_url('admin/complaints/' . $complaint['id']) ?>" 
                               class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1"><?= esc($complaint['title']) ?></h6>
                                        <small class="text-muted">
                                            <i class="fas fa-user me-1"></i><?= esc($complaint['user_name']) ?>
                                        </small>
                                    </div>
                                    <span class="badge badge-<?= $complaint['status'] ?>">
                                        <?= $statuses[$complaint['status']] ?>
                                    </span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Recent Complaints -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-history me-2"></i>آخر البلاغات</span>
                <a href="<?= base_url('admin/complaints') ?>" class="btn btn-sm btn-outline-primary">
                    عرض الكل
                </a>
            </div>
            <div class="card-body p-0">
                <?php if (empty($recentComplaints)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-0">لا توجد بلاغات</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>العنوان</th>
                                    <th>الطالب</th>
                                    <th>الحالة</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($recentComplaints, 0, 5) as $complaint): ?>
                                    <tr>
                                        <td><strong>#<?= $complaint['id'] ?></strong></td>
                                        <td>
                                            <a href="<?= base_url('admin/complaints/' . $complaint['id']) ?>" class="text-decoration-none">
                                                <?= mb_substr($complaint['title'], 0, 30) ?>...
                                            </a>
                                        </td>
                                        <td><?= esc($complaint['user_name']) ?></td>
                                        <td>
                                            <span class="badge badge-<?= $complaint['status'] ?>">
                                                <?= $statuses[$complaint['status']] ?>
                                            </span>
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

<!-- Quick Stats Row -->
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-check-double fa-3x text-success mb-3"></i>
                <h3><?= $stats['resolved'] ?></h3>
                <p class="text-muted mb-0">تم حلها</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-lock fa-3x text-secondary mb-3"></i>
                <h3><?= $stats['closed'] ?></h3>
                <p class="text-muted mb-0">مغلقة</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card text-center">
            <div class="card-body">
                <?php 
                $pendingRate = $stats['total'] > 0 
                    ? round((($stats['open'] + $stats['in_progress']) / $stats['total']) * 100) 
                    : 0;
                ?>
                <i class="fas fa-chart-pie fa-3x text-primary mb-3"></i>
                <h3><?= $pendingRate ?>%</h3>
                <p class="text-muted mb-0">نسبة البلاغات المعلقة</p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
