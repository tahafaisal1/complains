<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">النوع</label>
                <select name="category" class="form-select">
                    <option value="">جميع الأنواع</option>
                    <?php foreach ($categories as $key => $value): ?>
                        <option value="<?= $key ?>" <?= ($filters['category'] ?? '') === $key ? 'selected' : '' ?>>
                            <?= $value ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">من تاريخ</label>
                <input type="date" name="date_from" class="form-control" 
                       value="<?= $filters['date_from'] ?? '' ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">إلى تاريخ</label>
                <input type="date" name="date_to" class="form-control" 
                       value="<?= $filters['date_to'] ?? '' ?>">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter me-1"></i>تصفية
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Results -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>تقرير حسب النوع</h5>
    </div>
    <div class="card-body">
        <?php if (empty($results)): ?>
            <div class="text-center py-4">
                <i class="fas fa-chart-bar text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-2">لا توجد بيانات</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>النوع</th>
                            <th class="text-center">الإجمالي</th>
                            <th class="text-center">مفتوح</th>
                            <th class="text-center">تحت المعالجة</th>
                            <th class="text-center">تم الحل</th>
                            <th class="text-center">مغلق</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $totalAll = 0;
                        $totalOpen = 0;
                        $totalInProgress = 0;
                        $totalResolved = 0;
                        $totalClosed = 0;
                        ?>
                        <?php foreach ($results as $row): ?>
                            <?php
                            $totalAll += $row['total'];
                            $totalOpen += $row['open_count'];
                            $totalInProgress += $row['in_progress_count'];
                            $totalResolved += $row['resolved_count'];
                            $totalClosed += $row['closed_count'];
                            ?>
                            <tr>
                                <td><strong><?= esc($row['category']) ?></strong></td>
                                <td class="text-center"><span class="badge bg-primary"><?= $row['total'] ?></span></td>
                                <td class="text-center"><span class="badge badge-open"><?= $row['open_count'] ?></span></td>
                                <td class="text-center"><span class="badge badge-in_progress"><?= $row['in_progress_count'] ?></span></td>
                                <td class="text-center"><span class="badge badge-resolved"><?= $row['resolved_count'] ?></span></td>
                                <td class="text-center"><span class="badge badge-closed"><?= $row['closed_count'] ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="table-light">
                            <th>الإجمالي</th>
                            <th class="text-center"><?= $totalAll ?></th>
                            <th class="text-center"><?= $totalOpen ?></th>
                            <th class="text-center"><?= $totalInProgress ?></th>
                            <th class="text-center"><?= $totalResolved ?></th>
                            <th class="text-center"><?= $totalClosed ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="mt-3">
    <a href="<?= base_url('admin/reports') ?>" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-right me-1"></i>رجوع للتقارير
    </a>
</div>

<?= $this->endSection() ?>
