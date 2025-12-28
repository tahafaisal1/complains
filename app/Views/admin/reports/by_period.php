<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
$months = [
    1 => 'يناير', 2 => 'فبراير', 3 => 'مارس', 4 => 'أبريل',
    5 => 'مايو', 6 => 'يونيو', 7 => 'يوليو', 8 => 'أغسطس',
    9 => 'سبتمبر', 10 => 'أكتوبر', 11 => 'نوفمبر', 12 => 'ديسمبر'
];
?>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">نوع الفترة</label>
                <select name="period" class="form-select" id="periodSelect">
                    <option value="monthly" <?= ($filters['period'] ?? 'monthly') === 'monthly' ? 'selected' : '' ?>>شهري</option>
                    <option value="weekly" <?= ($filters['period'] ?? '') === 'weekly' ? 'selected' : '' ?>>أسبوعي</option>
                    <option value="daily" <?= ($filters['period'] ?? '') === 'daily' ? 'selected' : '' ?>>يومي</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">السنة</label>
                <select name="year" class="form-select">
                    <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                        <option value="<?= $y ?>" <?= ($filters['year'] ?? date('Y')) == $y ? 'selected' : '' ?>>
                            <?= $y ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-3" id="monthWrapper" style="<?= ($filters['period'] ?? 'monthly') !== 'daily' ? 'display:none;' : '' ?>">
                <label class="form-label">الشهر</label>
                <select name="month" class="form-select">
                    <?php foreach ($months as $num => $name): ?>
                        <option value="<?= $num ?>" <?= ($filters['month'] ?? date('m')) == $num ? 'selected' : '' ?>>
                            <?= $name ?>
                        </option>
                    <?php endforeach; ?>
                </select>
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
        <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>تقرير حسب الفترة</h5>
    </div>
    <div class="card-body">
        <?php if (empty($results)): ?>
            <div class="text-center py-4">
                <i class="fas fa-calendar-alt text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-2">لا توجد بيانات</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>الفترة</th>
                            <th class="text-center">عدد البلاغات</th>
                            <th>الرسم البياني</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $maxTotal = max(array_column($results, 'total'));
                        foreach ($results as $row): 
                            $period = $filters['period'] ?? 'monthly';
                            $label = $row['period_label'];
                            
                            if ($period === 'monthly') {
                                $label = $months[$row['period_label']] ?? $row['period_label'];
                            } elseif ($period === 'daily') {
                                $label = date('Y-m-d', strtotime($row['period_label']));
                            }
                            
                            $percentage = $maxTotal > 0 ? ($row['total'] / $maxTotal) * 100 : 0;
                        ?>
                            <tr>
                                <td><strong><?= esc($label) ?></strong></td>
                                <td class="text-center"><span class="badge bg-primary"><?= $row['total'] ?></span></td>
                                <td style="width: 50%;">
                                    <div class="progress" style="height: 25px;">
                                        <div class="progress-bar bg-primary" role="progressbar" 
                                             style="width: <?= $percentage ?>%;">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
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

<?= $this->section('scripts') ?>
<script>
    document.getElementById('periodSelect').addEventListener('change', function() {
        document.getElementById('monthWrapper').style.display = 
            this.value === 'daily' ? 'block' : 'none';
    });
</script>
<?= $this->endSection() ?>
