<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row g-4 mb-4">
    <!-- Reports Cards -->
    <div class="col-md-4">
        <a href="<?= base_url('admin/reports/by-category') ?>" class="text-decoration-none">
            <div class="stat-card primary">
                <div class="stat-icon">
                    <i class="fas fa-tags"></i>
                </div>
                <div class="stat-label">تقرير حسب النوع</div>
                <p class="small mt-2 mb-0 opacity-75">عرض البلاغات مصنفة حسب نوعها</p>
            </div>
        </a>
    </div>
    
    <div class="col-md-4">
        <a href="<?= base_url('admin/reports/by-department') ?>" class="text-decoration-none">
            <div class="stat-card success">
                <div class="stat-icon">
                    <i class="fas fa-building"></i>
                </div>
                <div class="stat-label">تقرير حسب القسم</div>
                <p class="small mt-2 mb-0 opacity-75">عرض البلاغات مصنفة حسب الأقسام</p>
            </div>
        </a>
    </div>
    
    <div class="col-md-4">
        <a href="<?= base_url('admin/reports/by-period') ?>" class="text-decoration-none">
            <div class="stat-card warning">
                <div class="stat-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="stat-label">تقرير حسب الفترة</div>
                <p class="small mt-2 mb-0 opacity-75">عرض البلاغات على مدار الزمن</p>
            </div>
        </a>
    </div>
</div>

<!-- Export Section -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-download me-2"></i>تصدير التقارير</h5>
    </div>
    <div class="card-body">
        <form class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">من تاريخ</label>
                <input type="date" name="date_from" class="form-control" id="dateFrom">
            </div>
            <div class="col-md-4">
                <label class="form-label">إلى تاريخ</label>
                <input type="date" name="date_to" class="form-control" id="dateTo">
            </div>
            <div class="col-md-4">
                <div class="d-flex gap-2">
                    <a href="#" id="exportPdf" class="btn btn-danger flex-fill">
                        <i class="fas fa-file-pdf me-1"></i>تصدير PDF
                    </a>
                    <a href="#" id="exportExcel" class="btn btn-success flex-fill">
                        <i class="fas fa-file-excel me-1"></i>تصدير Excel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.getElementById('exportPdf').addEventListener('click', function(e) {
        e.preventDefault();
        const dateFrom = document.getElementById('dateFrom').value;
        const dateTo = document.getElementById('dateTo').value;
        let url = '<?= base_url('admin/reports/export-pdf') ?>?';
        if (dateFrom) url += 'date_from=' + dateFrom + '&';
        if (dateTo) url += 'date_to=' + dateTo;
        window.location.href = url;
    });
    
    document.getElementById('exportExcel').addEventListener('click', function(e) {
        e.preventDefault();
        const dateFrom = document.getElementById('dateFrom').value;
        const dateTo = document.getElementById('dateTo').value;
        let url = '<?= base_url('admin/reports/export-excel') ?>?';
        if (dateFrom) url += 'date_from=' + dateFrom + '&';
        if (dateTo) url += 'date_to=' + dateTo;
        window.location.href = url;
    });
</script>
<?= $this->endSection() ?>
