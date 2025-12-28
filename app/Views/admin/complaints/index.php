<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form action="<?= base_url('admin/complaints') ?>" method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">الحالة</label>
                <select name="status" class="form-select">
                    <option value="">جميع الحالات</option>
                    <?php foreach ($statuses as $key => $value): ?>
                        <option value="<?= $key ?>" <?= $filters['status'] == $key ? 'selected' : '' ?>>
                            <?= $value ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">النوع</label>
                <select name="category" class="form-select">
                    <option value="">جميع الأنواع</option>
                    <?php foreach ($categories as $key => $value): ?>
                        <option value="<?= $key ?>" <?= $filters['category'] == $key ? 'selected' : '' ?>>
                            <?= $value ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">الأولوية</label>
                <select name="priority" class="form-select">
                    <option value="">جميع الأولويات</option>
                    <?php foreach ($priorities as $key => $value): ?>
                        <option value="<?= $key ?>" <?= $filters['priority'] == $key ? 'selected' : '' ?>>
                            <?= $value ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter me-2"></i>تصفية
                </button>
                <a href="<?= base_url('admin/complaints') ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Complaints Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-clipboard-list me-2"></i>جميع البلاغات</span>
        <span class="badge bg-primary"><?= count($complaints) ?> بلاغ</span>
    </div>
    <div class="card-body p-0">
        <?php if (empty($complaints)): ?>
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                <h4>لا توجد بلاغات</h4>
                <p class="text-muted">لا توجد بلاغات تطابق معايير البحث</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>العنوان</th>
                            <th>الطالب</th>
                            <th>النوع</th>
                            <th>الأولوية</th>
                            <th>الحالة</th>
                            <th>التاريخ</th>
                            <th>إجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($complaints as $complaint): ?>
                            <tr>
                                <td><strong>#<?= $complaint['id'] ?></strong></td>
                                <td>
                                    <a href="<?= base_url('admin/complaints/' . $complaint['id']) ?>" class="text-decoration-none">
                                        <?= esc(mb_substr($complaint['title'], 0, 40)) ?>
                                        <?= strlen($complaint['title']) > 40 ? '...' : '' ?>
                                    </a>
                                </td>
                                <td>
                                    <div>
                                        <strong><?= esc($complaint['user_name']) ?></strong>
                                        <br>
                                        <small class="text-muted"><?= esc($complaint['student_id']) ?></small>
                                    </div>
                                </td>
                                <td><?= esc($complaint['category']) ?></td>
                                <td>
                                    <span class="badge badge-<?= $complaint['priority'] ?>">
                                        <?= $priorities[$complaint['priority']] ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-<?= $complaint['status'] ?>">
                                        <?= $statuses[$complaint['status']] ?>
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?= date('Y/m/d', strtotime($complaint['created_at'])) ?>
                                    </small>
                                </td>
                                <td>
                                    <a href="<?= base_url('admin/complaints/' . $complaint['id']) ?>" 
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye me-1"></i>عرض
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

<?= $this->endSection() ?>
