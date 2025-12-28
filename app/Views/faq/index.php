<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <!-- Search Box -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="<?= base_url('faq') ?>" method="get" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control" 
                           placeholder="ابحث عن سؤال..." 
                           value="<?= esc($search ?? '') ?>">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                    <?php if (!empty($search)): ?>
                        <a href="<?= base_url('faq') ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
        
        <?php if (!empty($search)): ?>
            <div class="alert alert-info">
                <i class="fas fa-search me-2"></i>
                نتائج البحث عن: <strong><?= esc($search) ?></strong>
            </div>
        <?php endif; ?>
        
        <?php if (empty($faqGroups)): ?>
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-question-circle text-muted" style="font-size: 4rem;"></i>
                    <h4 class="mt-3 text-muted">لا توجد أسئلة</h4>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($faqGroups as $category => $faqs): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-folder me-2 text-primary"></i><?= esc($category) ?></h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="accordion accordion-flush" id="faq-<?= md5($category) ?>">
                            <?php foreach ($faqs as $faq): ?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" 
                                                data-bs-toggle="collapse" 
                                                data-bs-target="#faq-item-<?= $faq['id'] ?>">
                                            <i class="fas fa-question-circle text-primary me-2"></i>
                                            <?= esc($faq['question']) ?>
                                        </button>
                                    </h2>
                                    <div id="faq-item-<?= $faq['id'] ?>" 
                                         class="accordion-collapse collapse" 
                                         data-bs-parent="#faq-<?= md5($category) ?>">
                                        <div class="accordion-body bg-light">
                                            <?= nl2br(esc($faq['answer'])) ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
