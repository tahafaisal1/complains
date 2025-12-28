<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="mb-4">
    <a href="<?= base_url('admin/complaints') ?>" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-right me-2"></i>العودة للبلاغات
    </a>
</div>

<div class="row">
    <!-- Complaint Details -->
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>
                    <i class="fas fa-clipboard-list me-2"></i>
                    تفاصيل البلاغ #<?= $complaint['id'] ?>
                </span>
                <span class="badge badge-<?= $complaint['status'] ?> px-3 py-2">
                    <?= $statuses[$complaint['status']] ?>
                </span>
            </div>
            <div class="card-body">
                <h4 class="mb-3"><?= esc($complaint['title']) ?></h4>
                
                <div class="row mb-4">
                    <div class="col-md-3">
                        <small class="text-muted d-block">النوع</small>
                        <strong><?= esc($complaint['category']) ?></strong>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted d-block">الأولوية</small>
                        <span class="badge badge-<?= $complaint['priority'] ?>">
                            <?= $priorities[$complaint['priority']] ?>
                        </span>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted d-block">تاريخ الإرسال</small>
                        <strong><?= date('Y/m/d H:i', strtotime($complaint['created_at'])) ?></strong>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted d-block">آخر تحديث</small>
                        <strong><?= date('Y/m/d H:i', strtotime($complaint['updated_at'])) ?></strong>
                    </div>
                </div>

                <hr>

                <h6 class="text-muted mb-3">وصف البلاغ:</h6>
                <div class="p-3 bg-light rounded mb-4">
                    <?= nl2br(esc($complaint['description'])) ?>
                </div>

                <?php if (!empty($complaint['attachment'])): ?>
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">المرفقات:</h6>
                        <a href="#" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-paperclip me-2"></i>عرض الملف المرفق
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Admin Response -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-reply me-2"></i>الرد على البلاغ
            </div>
            <div class="card-body">
                <?php if (!empty($complaint['admin_response'])): ?>
                    <div class="alert alert-info mb-3">
                        <strong>الرد السابق:</strong><br>
                        <?= nl2br(esc($complaint['admin_response'])) ?>
                    </div>
                <?php endif; ?>
                
                <form action="<?= base_url('admin/complaints/respond/' . $complaint['id']) ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <textarea class="form-control" name="response" rows="4" 
                                  placeholder="اكتب ردك هنا..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-2"></i>إرسال الرد
                    </button>
                </form>
            </div>
        </div>

        <!-- Notes Section -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-sticky-note me-2"></i>الملاحظات والتعليقات
            </div>
            <div class="card-body">
                <!-- Add Note Form -->
                <form action="<?= base_url('admin/notes/add/' . $complaint['id']) ?>" method="POST" class="mb-4">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <textarea class="form-control" name="content" rows="3" 
                                  placeholder="أضف ملاحظة..."></textarea>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_internal" id="is_internal" value="1">
                            <label class="form-check-label" for="is_internal">
                                <i class="fas fa-lock me-1"></i>ملاحظة داخلية (لا تظهر للطالب)
                            </label>
                        </div>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>إضافة ملاحظة
                        </button>
                    </div>
                </form>

                <!-- Notes List -->
                <?php if (!empty($notes)): ?>
                    <hr>
                    <div class="timeline">
                        <?php foreach ($notes as $note): ?>
                            <div class="d-flex mb-4 <?= $note['is_internal'] ? 'bg-warning bg-opacity-10 p-3 rounded' : '' ?>">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm <?= $note['user_role'] == 'admin' ? 'bg-danger' : 'bg-primary' ?> text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <?= mb_substr($note['user_name'], 0, 1) ?>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <strong><?= esc($note['user_name']) ?></strong>
                                            <span class="badge bg-<?= $note['user_role'] == 'admin' ? 'danger' : 'primary' ?> ms-2">
                                                <?= $note['user_role'] == 'admin' ? 'إداري' : 'طالب' ?>
                                            </span>
                                            <?php if ($note['is_internal']): ?>
                                                <span class="badge bg-warning ms-1">
                                                    <i class="fas fa-lock me-1"></i>داخلي
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <small class="text-muted">
                                            <?= date('Y/m/d H:i', strtotime($note['created_at'])) ?>
                                        </small>
                                    </div>
                                    <p class="mb-0 mt-2"><?= nl2br(esc($note['content'])) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center mb-0">لا توجد ملاحظات بعد</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Messages Section -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <i class="fas fa-comments me-2"></i>المحادثة مع الطالب
            </div>
            <div class="card-body">
                <div id="messagesContainer" style="max-height: 300px; overflow-y: auto; min-height: 100px;">
                    <div class="text-center py-3 text-muted" id="loadingMessages">
                        <i class="fas fa-spinner fa-spin me-2"></i>جاري التحميل...
                    </div>
                </div>
                <hr>
                <form id="messageForm">
                    <div class="input-group">
                        <input type="text" class="form-control" id="messageInput" 
                               placeholder="اكتب رسالتك هنا...">
                        <button type="submit" class="btn btn-info text-white">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Activity Log Section -->
        <?php if (!empty($activityLogs)): ?>
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <i class="fas fa-history me-2"></i>سجل النشاط
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush" style="max-height: 300px; overflow-y: auto;">
                    <?php foreach ($activityLogs as $log): ?>
                        <div class="list-group-item">
                            <div class="d-flex align-items-start">
                                <div class="me-3">
                                    <i class="fas <?= \App\Models\ActivityLogModel::getActionIcon($log['action']) ?>"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between">
                                        <strong><?= esc($log['user_name']) ?></strong>
                                        <small class="text-muted">
                                            <?= date('Y/m/d H:i', strtotime($log['created_at'])) ?>
                                        </small>
                                    </div>
                                    <small class="text-muted"><?= esc($log['description']) ?></small>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Student Info -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-user me-2"></i>معلومات الطالب
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="user-avatar mx-auto mb-2" style="width: 60px; height: 60px; font-size: 1.5rem;">
                        <?= mb_substr($complaint['user_name'], 0, 1) ?>
                    </div>
                    <h5><?= esc($complaint['user_name']) ?></h5>
                    <p class="text-muted mb-0"><?= esc($complaint['user_email']) ?></p>
                </div>
                
                <hr>
                
                <div class="mb-2">
                    <small class="text-muted">الرقم الجامعي:</small>
                    <strong class="d-block"><?= esc($complaint['student_id']) ?></strong>
                </div>
                <div class="mb-2">
                    <small class="text-muted">القسم:</small>
                    <strong class="d-block"><?= esc($complaint['department']) ?></strong>
                </div>
                <?php if (!empty($complaint['phone'])): ?>
                    <div>
                        <small class="text-muted">الجوال:</small>
                        <strong class="d-block"><?= esc($complaint['phone']) ?></strong>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Status Update -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-edit me-2"></i>تغيير الحالة
            </div>
            <div class="card-body">
                <form action="<?= base_url('admin/complaints/update-status/' . $complaint['id']) ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <select name="status" class="form-select">
                            <?php foreach ($statuses as $key => $value): ?>
                                <option value="<?= $key ?>" <?= $complaint['status'] == $key ? 'selected' : '' ?>>
                                    <?= $value ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-2"></i>تحديث الحالة
                    </button>
                </form>
            </div>
        </div>

        <!-- Complaint Info -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-info-circle me-2"></i>معلومات البلاغ
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted d-block">رقم البلاغ</small>
                    <strong>#<?= $complaint['id'] ?></strong>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">تاريخ الإنشاء</small>
                    <strong><?= date('Y/m/d H:i', strtotime($complaint['created_at'])) ?></strong>
                </div>
                <?php if ($complaint['resolved_at']): ?>
                    <div class="mb-3">
                        <small class="text-muted d-block">تاريخ الحل</small>
                        <strong><?= date('Y/m/d H:i', strtotime($complaint['resolved_at'])) ?></strong>
                    </div>
                <?php endif; ?>
                <?php if ($complaint['assigned_to']): ?>
                    <div>
                        <small class="text-muted d-block">معين إلى</small>
                        <strong>إداري #<?= $complaint['assigned_to'] ?></strong>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
const complaintId = <?= $complaint['id'] ?>;
const currentUserId = <?= session()->get('user_id') ?>;

// Load messages
function loadMessages() {
    fetch('<?= base_url('messages/get/') ?>' + complaintId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const container = document.getElementById('messagesContainer');
                if (data.messages.length === 0) {
                    container.innerHTML = '<p class="text-center text-muted py-3">لا توجد رسائل بعد</p>';
                } else {
                    container.innerHTML = data.messages.map(msg => `
                        <div class="d-flex mb-3 ${msg.sender_id == currentUserId ? 'flex-row-reverse' : ''}">
                            <div class="p-3 rounded ${msg.sender_id == currentUserId ? 'bg-info text-white' : 'bg-light'}" 
                                 style="max-width: 80%;">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <strong class="small">${msg.sender_name}</strong>
                                    <small class="opacity-75">${new Date(msg.created_at).toLocaleString('ar-SA')}</small>
                                </div>
                                <p class="mb-0">${msg.message}</p>
                            </div>
                        </div>
                    `).join('');
                    container.scrollTop = container.scrollHeight;
                }
            }
        })
        .catch(() => {
            document.getElementById('messagesContainer').innerHTML = 
                '<p class="text-center text-muted py-3">لا توجد رسائل بعد</p>';
        });
}

// Send message
document.getElementById('messageForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const input = document.getElementById('messageInput');
    const message = input.value.trim();
    
    if (!message) return;
    
    fetch('<?= base_url('messages/send/') ?>' + complaintId, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'message=' + encodeURIComponent(message)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            input.value = '';
            loadMessages();
        } else {
            alert(data.message || 'حدث خطأ');
        }
    });
});

// Load messages on page load
loadMessages();
</script>
<?= $this->endSection() ?>
