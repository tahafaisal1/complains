<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="mb-4">
    <a href="<?= base_url('admin/users') ?>" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-right me-2"></i>العودة للمستخدمين
    </a>
</div>

<div class="row">
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-body text-center">
                <div class="user-avatar mx-auto mb-3" style="width: 100px; height: 100px; font-size: 3rem;">
                    <?= mb_substr($user['name'], 0, 1) ?>
                </div>
                <h4><?= esc($user['name']) ?></h4>
                <p class="text-muted mb-3"><?= esc($user['email']) ?></p>
                
                <span class="badge bg-<?= $user['role'] == 'admin' ? 'danger' : 'primary' ?> px-3 py-2 mb-3">
                    <?= $user['role'] == 'admin' ? 'إداري' : 'طالب' ?>
                </span>
                
                <?php if ($user['is_active']): ?>
                    <span class="badge bg-success px-3 py-2">
                        <i class="fas fa-check me-1"></i>نشط
                    </span>
                <?php else: ?>
                    <span class="badge bg-danger px-3 py-2">
                        <i class="fas fa-ban me-1"></i>معطل
                    </span>
                <?php endif; ?>
                
                <hr class="my-4">
                
                <div class="text-start">
                    <?php if ($user['student_id']): ?>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">الرقم الجامعي:</span>
                            <strong><?= esc($user['student_id']) ?></strong>
                        </div>
                    <?php endif; ?>
                    <?php if ($user['department']): ?>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">القسم:</span>
                            <strong><?= esc($user['department']) ?></strong>
                        </div>
                    <?php endif; ?>
                    <?php if ($user['phone']): ?>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">الجوال:</span>
                            <strong><?= esc($user['phone']) ?></strong>
                        </div>
                    <?php endif; ?>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">تاريخ التسجيل:</span>
                        <strong><?= date('Y/m/d', strtotime($user['created_at'])) ?></strong>
                    </div>
                </div>
                
                <?php if ($user['id'] != session()->get('user_id')): ?>
                    <hr class="my-4">
                    <form action="<?= base_url('admin/users/toggle-status/' . $user['id']) ?>" method="POST">
                        <?= csrf_field() ?>
                        <button type="submit" 
                                class="btn btn-<?= $user['is_active'] ? 'danger' : 'success' ?> w-100"
                                onclick="return confirm('<?= $user['is_active'] ? 'هل تريد تعطيل هذا الحساب؟' : 'هل تريد تفعيل هذا الحساب؟' ?>')">
                            <i class="fas fa-<?= $user['is_active'] ? 'ban' : 'check' ?> me-2"></i>
                            <?= $user['is_active'] ? 'تعطيل الحساب' : 'تفعيل الحساب' ?>
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-clipboard-list me-2"></i>بلاغات المستخدم</span>
                <span class="badge bg-primary"><?= count($complaints) ?> بلاغ</span>
            </div>
            <div class="card-body p-0">
                <?php if (empty($complaints)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-0">لا توجد بلاغات لهذا المستخدم</p>
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
                                <?php foreach ($complaints as $complaint): ?>
                                    <tr>
                                        <td><strong>#<?= $complaint['id'] ?></strong></td>
                                        <td><?= esc(mb_substr($complaint['title'], 0, 40)) ?>...</td>
                                        <td><?= esc($complaint['category']) ?></td>
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
