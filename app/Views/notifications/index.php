<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-bell me-2"></i>الإشعارات</h5>
        <?php if (!empty($notifications)): ?>
        <form action="<?= base_url('notifications/mark-all-read') ?>" method="post">
            <button type="submit" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-check-double me-1"></i>تحديد الكل كمقروء
            </button>
        </form>
        <?php endif; ?>
    </div>
    <div class="card-body p-0">
        <?php if (empty($notifications)): ?>
            <div class="text-center py-5">
                <i class="fas fa-bell-slash text-muted" style="font-size: 4rem;"></i>
                <p class="text-muted mt-3">لا توجد إشعارات</p>
            </div>
        <?php else: ?>
            <div class="list-group list-group-flush">
                <?php foreach ($notifications as $notification): ?>
                    <div class="list-group-item list-group-item-action <?= $notification['is_read'] ? '' : 'bg-light' ?>">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center mb-1">
                                    <?php if ($notification['type'] === 'status_change'): ?>
                                        <span class="badge bg-primary me-2"><i class="fas fa-exchange-alt"></i></span>
                                    <?php elseif ($notification['type'] === 'new_message'): ?>
                                        <span class="badge bg-success me-2"><i class="fas fa-envelope"></i></span>
                                    <?php else: ?>
                                        <span class="badge bg-info me-2"><i class="fas fa-info"></i></span>
                                    <?php endif; ?>
                                    <strong><?= esc($notification['title']) ?></strong>
                                    <?php if (!$notification['is_read']): ?>
                                        <span class="badge bg-danger ms-2">جديد</span>
                                    <?php endif; ?>
                                </div>
                                <p class="mb-1"><?= esc($notification['message']) ?></p>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    <?= date('Y-m-d H:i', strtotime($notification['created_at'])) ?>
                                </small>
                            </div>
                            <?php if ($notification['complaint_id']): ?>
                                <a href="<?= base_url((session()->get('user_role') === 'admin' ? 'admin' : 'student') . '/complaints/' . $notification['complaint_id']) ?>" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
