<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-users me-2"></i>إدارة المستخدمين</span>
        <span class="badge bg-primary"><?= count($users) ?> مستخدم</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>البريد الإلكتروني</th>
                        <th>الدور</th>
                        <th>القسم</th>
                        <th>الحالة</th>
                        <th>تاريخ التسجيل</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr class="<?= !$user['is_active'] ? 'table-secondary' : '' ?>">
                            <td><strong>#<?= $user['id'] ?></strong></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar me-2" style="width: 35px; height: 35px; font-size: 0.9rem;">
                                        <?= mb_substr($user['name'], 0, 1) ?>
                                    </div>
                                    <div>
                                        <strong><?= esc($user['name']) ?></strong>
                                        <?php if ($user['student_id']): ?>
                                            <br><small class="text-muted"><?= esc($user['student_id']) ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td><?= esc($user['email']) ?></td>
                            <td>
                                <span class="badge bg-<?= $user['role'] == 'admin' ? 'danger' : 'primary' ?>">
                                    <?= $user['role'] == 'admin' ? 'إداري' : 'طالب' ?>
                                </span>
                            </td>
                            <td><?= esc($user['department'] ?? '-') ?></td>
                            <td>
                                <?php if ($user['is_active']): ?>
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i>نشط
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger">
                                        <i class="fas fa-ban me-1"></i>معطل
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <?= date('Y/m/d', strtotime($user['created_at'])) ?>
                                </small>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?= base_url('admin/users/' . $user['id']) ?>" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if ($user['id'] != session()->get('user_id')): ?>
                                        <form action="<?= base_url('admin/users/toggle-status/' . $user['id']) ?>" 
                                              method="POST" class="d-inline">
                                            <?= csrf_field() ?>
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-<?= $user['is_active'] ? 'danger' : 'success' ?>"
                                                    onclick="return confirm('<?= $user['is_active'] ? 'هل تريد تعطيل هذا الحساب؟' : 'هل تريد تفعيل هذا الحساب؟' ?>')">
                                                <i class="fas fa-<?= $user['is_active'] ? 'ban' : 'check' ?>"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
