<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="mb-4">
    <a href="<?= base_url('student/complaints') ?>" class="btn btn-outline-secondary">
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
                <span class="badge badge-<?= $complaint['status'] ?>">
                    <?= $statuses[$complaint['status']] ?? $complaint['status'] ?>
                </span>
            </div>
            <div class="card-body">
                <h4 class="mb-3"><?= esc($complaint['title']) ?></h4>
                
                <div class="row mb-4">
                    <div class="col-md-4">
                        <small class="text-muted d-block">النوع</small>
                        <strong><?= esc($complaint['category']) ?></strong>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">الأولوية</small>
                        <span class="badge badge-<?= $complaint['priority'] ?>">
                            <?= $priorities[$complaint['priority']] ?? $complaint['priority'] ?>
                        </span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">تاريخ الإرسال</small>
                        <strong><?= date('Y/m/d H:i', strtotime($complaint['created_at'])) ?></strong>
                    </div>
                </div>

                <hr>

                <h6 class="text-muted mb-3">وصف البلاغ:</h6>
                <div class="p-3 bg-light rounded">
                    <?= nl2br(esc($complaint['description'])) ?>
                </div>

                <?php if (!empty($complaint['attachment'])): ?>
                    <div class="mt-4">
                        <h6 class="text-muted mb-2">المرفقات:</h6>
                        <a href="#" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-paperclip me-2"></i>عرض الملف المرفق
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Admin Response -->
        <?php if (!empty($complaint['admin_response'])): ?>
            <div class="card mb-4 border-success">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-reply me-2"></i>رد الإدارة
                </div>
                <div class="card-body">
                    <?= nl2br(esc($complaint['admin_response'])) ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Rating Section (shown when resolved) -->
        <?php if ($complaint['status'] === 'resolved'): ?>
            <div class="card mb-4 border-warning">
                <div class="card-header bg-warning text-dark">
                    <i class="fas fa-star me-2"></i>تقييم الخدمة
                </div>
                <div class="card-body">
                    <?php if (empty($complaint['rating'])): ?>
                        <!-- Rating Form -->
                        <p class="text-muted mb-3">تم حل بلاغك! يرجى تقييم تجربتك مع خدمتنا لمساعدتنا في التحسين.</p>
                        <form action="<?= base_url('student/complaints/rate/' . $complaint['id']) ?>" method="POST">
                            <?= csrf_field() ?>
                            
                            <div class="mb-4 text-center">
                                <div class="rating-stars" id="ratingStars">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star star-icon" data-rating="<?= $i ?>" style="font-size: 2.5rem; color: #ddd; cursor: pointer; margin: 0 5px; transition: all 0.2s;"></i>
                                    <?php endfor; ?>
                                </div>
                                <input type="hidden" name="rating" id="ratingInput" value="" required>
                                <p class="mt-2 text-muted" id="ratingText">اختر تقييمك</p>
                            </div>
                            
                            <div class="mb-3">
                                <label for="rating_comment" class="form-label">تعليق (اختياري)</label>
                                <textarea class="form-control" name="rating_comment" id="rating_comment" rows="3" 
                                          placeholder="شاركنا رأيك في الخدمة المقدمة..."></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-warning" id="submitRatingBtn" disabled>
                                <i class="fas fa-paper-plane me-2"></i>إرسال التقييم
                            </button>
                        </form>
                        
                        <style>
                            .star-icon:hover, .star-icon.active {
                                color: #f59e0b !important;
                                transform: scale(1.2);
                            }
                            .star-icon.active {
                                color: #f59e0b !important;
                            }
                        </style>
                        
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const stars = document.querySelectorAll('.star-icon');
                                const ratingInput = document.getElementById('ratingInput');
                                const ratingText = document.getElementById('ratingText');
                                const submitBtn = document.getElementById('submitRatingBtn');
                                const texts = ['', 'ضعيف جداً', 'ضعيف', 'مقبول', 'جيد', 'ممتاز'];
                                
                                stars.forEach(star => {
                                    star.addEventListener('click', function() {
                                        const rating = this.dataset.rating;
                                        ratingInput.value = rating;
                                        ratingText.textContent = texts[rating];
                                        submitBtn.disabled = false;
                                        
                                        stars.forEach((s, i) => {
                                            if (i < rating) {
                                                s.classList.add('active');
                                            } else {
                                                s.classList.remove('active');
                                            }
                                        });
                                    });
                                    
                                    star.addEventListener('mouseenter', function() {
                                        const rating = this.dataset.rating;
                                        stars.forEach((s, i) => {
                                            if (i < rating) {
                                                s.style.color = '#f59e0b';
                                            }
                                        });
                                    });
                                    
                                    star.addEventListener('mouseleave', function() {
                                        stars.forEach((s, i) => {
                                            if (!s.classList.contains('active')) {
                                                s.style.color = '#ddd';
                                            }
                                        });
                                    });
                                });
                            });
                        </script>
                    <?php else: ?>
                        <!-- Display Submitted Rating -->
                        <div class="text-center">
                            <p class="text-muted mb-2">تقييمك للخدمة:</p>
                            <div class="mb-3">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star" style="font-size: 2rem; color: <?= $i <= $complaint['rating'] ? '#f59e0b' : '#ddd' ?>;"></i>
                                <?php endfor; ?>
                            </div>
                            <p class="badge bg-success fs-6">
                                <?php
                                    $ratingTexts = ['', 'ضعيف جداً', 'ضعيف', 'مقبول', 'جيد', 'ممتاز'];
                                    echo $ratingTexts[$complaint['rating']] ?? '';
                                ?>
                            </p>
                            <?php if (!empty($complaint['rating_comment'])): ?>
                                <div class="mt-3 p-3 bg-light rounded text-start">
                                    <small class="text-muted">تعليقك:</small>
                                    <p class="mb-0"><?= esc($complaint['rating_comment']) ?></p>
                                </div>
                            <?php endif; ?>
                            <p class="text-muted small mt-3">
                                <i class="fas fa-clock me-1"></i>
                                تم التقييم بتاريخ: <?= date('Y/m/d H:i', strtotime($complaint['rated_at'])) ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Notes Timeline -->
        <?php if (!empty($notes)): ?>
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-comments me-2"></i>التعليقات والتحديثات
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <?php foreach ($notes as $note): ?>
                            <div class="d-flex mb-4">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <?= mb_substr($note['user_name'], 0, 1) ?>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="d-flex justify-content-between">
                                        <strong><?= esc($note['user_name']) ?></strong>
                                        <small class="text-muted">
                                            <?= date('Y/m/d H:i', strtotime($note['created_at'])) ?>
                                        </small>
                                    </div>
                                    <span class="badge bg-<?= $note['user_role'] == 'admin' ? 'danger' : 'primary' ?> mb-2">
                                        <?= $note['user_role'] == 'admin' ? 'إداري' : 'طالب' ?>
                                    </span>
                                    <p class="mb-0"><?= nl2br(esc($note['content'])) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Messages Section -->
        <div class="card mt-4">
            <div class="card-header bg-info text-white">
                <i class="fas fa-comments me-2"></i>المحادثة مع الإدارة
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
    </div>

    <!-- Sidebar Info -->
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-info-circle me-2"></i>معلومات البلاغ
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted d-block">رقم البلاغ</small>
                    <strong>#<?= $complaint['id'] ?></strong>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">الحالة</small>
                    <span class="badge badge-<?= $complaint['status'] ?>">
                        <?= $statuses[$complaint['status']] ?? $complaint['status'] ?>
                    </span>
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
            </div>
        </div>

        <!-- Status Guide -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-question-circle me-2"></i>دليل الحالات
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <span class="badge badge-open me-2">مفتوح</span>
                    <small>البلاغ قيد الانتظار</small>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <span class="badge badge-in_progress me-2">تحت المعالجة</span>
                    <small>جاري العمل على حل المشكلة</small>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <span class="badge badge-resolved me-2">تم الحل</span>
                    <small>تم حل المشكلة بنجاح</small>
                </div>
                <div class="d-flex align-items-center">
                    <span class="badge badge-closed me-2">مغلق</span>
                    <small>تم إغلاق البلاغ</small>
                </div>
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
                    container.innerHTML = '<p class="text-center text-muted py-3">لا توجد رسائل بعد. أرسل رسالة للتواصل مع الإدارة.</p>';
                } else {
                    container.innerHTML = data.messages.map(msg => `
                        <div class="d-flex mb-3 ${msg.sender_id == currentUserId ? 'flex-row-reverse' : ''}">
                            <div class="p-3 rounded ${msg.sender_id == currentUserId ? 'bg-primary text-white' : 'bg-light'}" 
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
                '<p class="text-center text-muted py-3">لا توجد رسائل بعد. أرسل رسالة للتواصل مع الإدارة.</p>';
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
