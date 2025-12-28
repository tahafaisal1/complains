<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ComplaintModel;
use App\Models\UserModel;

class ReportController extends BaseController
{
    protected $complaintModel;
    protected $userModel;

    public function __construct()
    {
        $this->complaintModel = new ComplaintModel();
        $this->userModel = new UserModel();
    }

    /**
     * لوحة التقارير الرئيسية
     */
    public function index()
    {
        $data = [
            'title'      => 'التقارير',
            'pageTitle'  => 'التقارير',
            'categories' => ComplaintModel::getCategories(),
            'statuses'   => ComplaintModel::getStatuses(),
        ];

        return view('admin/reports/index', $data);
    }

    /**
     * تقرير حسب النوع/الفئة
     */
    public function byCategory()
    {
        $category = $this->request->getGet('category');
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');

        $builder = $this->complaintModel->builder();
        $builder->select('category, COUNT(*) as total, 
                         SUM(CASE WHEN status = "open" THEN 1 ELSE 0 END) as open_count,
                         SUM(CASE WHEN status = "in_progress" THEN 1 ELSE 0 END) as in_progress_count,
                         SUM(CASE WHEN status = "resolved" THEN 1 ELSE 0 END) as resolved_count,
                         SUM(CASE WHEN status = "closed" THEN 1 ELSE 0 END) as closed_count');
        
        if ($category) {
            $builder->where('category', $category);
        }
        if ($dateFrom) {
            $builder->where('created_at >=', $dateFrom . ' 00:00:00');
        }
        if ($dateTo) {
            $builder->where('created_at <=', $dateTo . ' 23:59:59');
        }
        
        $builder->groupBy('category');
        $results = $builder->get()->getResultArray();

        $data = [
            'title'      => 'تقرير حسب النوع',
            'pageTitle'  => 'تقرير حسب النوع',
            'results'    => $results,
            'filters'    => [
                'category'  => $category,
                'date_from' => $dateFrom,
                'date_to'   => $dateTo,
            ],
            'categories' => ComplaintModel::getCategories(),
        ];

        return view('admin/reports/by_category', $data);
    }

    /**
     * تقرير حسب القسم
     */
    public function byDepartment()
    {
        $department = $this->request->getGet('department');
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');

        $builder = $this->complaintModel->builder();
        $builder->select('users.department, COUNT(*) as total,
                         SUM(CASE WHEN complaints.status = "open" THEN 1 ELSE 0 END) as open_count,
                         SUM(CASE WHEN complaints.status = "in_progress" THEN 1 ELSE 0 END) as in_progress_count,
                         SUM(CASE WHEN complaints.status = "resolved" THEN 1 ELSE 0 END) as resolved_count,
                         SUM(CASE WHEN complaints.status = "closed" THEN 1 ELSE 0 END) as closed_count');
        $builder->join('users', 'users.id = complaints.user_id');
        
        if ($department) {
            $builder->where('users.department', $department);
        }
        if ($dateFrom) {
            $builder->where('complaints.created_at >=', $dateFrom . ' 00:00:00');
        }
        if ($dateTo) {
            $builder->where('complaints.created_at <=', $dateTo . ' 23:59:59');
        }
        
        $builder->groupBy('users.department');
        $results = $builder->get()->getResultArray();

        // Get all departments
        $departments = $this->userModel->select('department')
                                       ->where('department IS NOT NULL')
                                       ->where('department !=', '')
                                       ->distinct()
                                       ->findAll();

        $data = [
            'title'       => 'تقرير حسب القسم',
            'pageTitle'   => 'تقرير حسب القسم',
            'results'     => $results,
            'filters'     => [
                'department' => $department,
                'date_from'  => $dateFrom,
                'date_to'    => $dateTo,
            ],
            'departments' => array_column($departments, 'department'),
        ];

        return view('admin/reports/by_department', $data);
    }

    /**
     * تقرير حسب الفترة الزمنية
     */
    public function byPeriod()
    {
        $period = $this->request->getGet('period') ?: 'monthly';
        $year = $this->request->getGet('year') ?: date('Y');

        $builder = $this->complaintModel->builder();
        
        if ($period === 'daily') {
            $builder->select("DATE(created_at) as period_label, COUNT(*) as total");
            $builder->where("YEAR(created_at)", $year);
            $builder->where("MONTH(created_at)", $this->request->getGet('month') ?: date('m'));
            $builder->groupBy("DATE(created_at)");
        } elseif ($period === 'weekly') {
            $builder->select("YEARWEEK(created_at, 1) as period_label, COUNT(*) as total");
            $builder->where("YEAR(created_at)", $year);
            $builder->groupBy("YEARWEEK(created_at, 1)");
        } else {
            $builder->select("MONTH(created_at) as period_label, COUNT(*) as total");
            $builder->where("YEAR(created_at)", $year);
            $builder->groupBy("MONTH(created_at)");
        }
        
        $builder->orderBy("period_label", "ASC");
        $results = $builder->get()->getResultArray();

        $data = [
            'title'     => 'تقرير حسب الفترة',
            'pageTitle' => 'تقرير حسب الفترة',
            'results'   => $results,
            'filters'   => [
                'period' => $period,
                'year'   => $year,
                'month'  => $this->request->getGet('month'),
            ],
        ];

        return view('admin/reports/by_period', $data);
    }

    /**
     * تصدير PDF (عرض صفحة للطباعة)
     */
    public function exportPdf()
    {
        $type = $this->request->getGet('type') ?: 'all';
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');

        $builder = $this->complaintModel->builder();
        $builder->select('complaints.*, users.name as user_name, users.department');
        $builder->join('users', 'users.id = complaints.user_id');
        
        if ($dateFrom) {
            $builder->where('complaints.created_at >=', $dateFrom . ' 00:00:00');
        }
        if ($dateTo) {
            $builder->where('complaints.created_at <=', $dateTo . ' 23:59:59');
        }
        
        $builder->orderBy('complaints.created_at', 'DESC');
        $complaints = $builder->get()->getResultArray();

        // Render printable HTML page
        $data = [
            'complaints' => $complaints,
            'dateFrom'   => $dateFrom,
            'dateTo'     => $dateTo,
            'statuses'   => ComplaintModel::getStatuses(),
            'priorities' => ComplaintModel::getPriorities(),
        ];

        return view('admin/reports/print_pdf', $data);
    }

    /**
     * تصدير Excel
     */
    public function exportExcel()
    {
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');

        $builder = $this->complaintModel->builder();
        $builder->select('complaints.id, complaints.title, complaints.category, complaints.priority, 
                         complaints.status, complaints.created_at, users.name as user_name, users.department');
        $builder->join('users', 'users.id = complaints.user_id');
        
        if ($dateFrom) {
            $builder->where('complaints.created_at >=', $dateFrom . ' 00:00:00');
        }
        if ($dateTo) {
            $builder->where('complaints.created_at <=', $dateTo . ' 23:59:59');
        }
        
        $builder->orderBy('complaints.created_at', 'DESC');
        $complaints = $builder->get()->getResultArray();

        try {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setRightToLeft(true);

            // Headers
            $headers = ['رقم', 'العنوان', 'النوع', 'الأولوية', 'الحالة', 'الطالب', 'القسم', 'التاريخ'];
            $col = 1;
            foreach ($headers as $header) {
                $sheet->setCellValueByColumnAndRow($col++, 1, $header);
            }

            // Style headers
            $sheet->getStyle('A1:H1')->getFont()->setBold(true);
            $sheet->getStyle('A1:H1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
            $sheet->getStyle('A1:H1')->getFill()->getStartColor()->setRGB('4F46E5');
            $sheet->getStyle('A1:H1')->getFont()->getColor()->setRGB('FFFFFF');

            // Data
            $row = 2;
            $statuses = ComplaintModel::getStatuses();
            $priorities = ComplaintModel::getPriorities();
            
            foreach ($complaints as $complaint) {
                $sheet->setCellValueByColumnAndRow(1, $row, $complaint['id']);
                $sheet->setCellValueByColumnAndRow(2, $row, $complaint['title']);
                $sheet->setCellValueByColumnAndRow(3, $row, $complaint['category']);
                $sheet->setCellValueByColumnAndRow(4, $row, $priorities[$complaint['priority']] ?? $complaint['priority']);
                $sheet->setCellValueByColumnAndRow(5, $row, $statuses[$complaint['status']] ?? $complaint['status']);
                $sheet->setCellValueByColumnAndRow(6, $row, $complaint['user_name']);
                $sheet->setCellValueByColumnAndRow(7, $row, $complaint['department']);
                $sheet->setCellValueByColumnAndRow(8, $row, $complaint['created_at']);
                $row++;
            }

            // Auto-size columns
            foreach (range('A', 'H') as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }

            $filename = 'تقرير_البلاغات_' . date('Y-m-d') . '.xlsx';
            
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'خطأ في تصدير Excel: ' . $e->getMessage());
        }
    }

    /**
     * توليد HTML للتقرير
     */
    private function generateReportHtml($complaints, $dateFrom, $dateTo)
    {
        $statuses = ComplaintModel::getStatuses();
        $priorities = ComplaintModel::getPriorities();
        
        $html = '
        <!DOCTYPE html>
        <html dir="rtl" lang="ar">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
            <style>
                * { font-family: DejaVu Sans, sans-serif; }
                body { direction: rtl; text-align: right; unicode-bidi: bidi-override; }
                h1 { text-align: center; color: #4f46e5; }
                .info { text-align: center; margin-bottom: 20px; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: right; font-size: 11px; unicode-bidi: embed; }
                th { background: #4f46e5; color: white; }
                tr:nth-child(even) { background: #f9f9f9; }
            </style>
        </head>
        <body>
            <h1>تقرير البلاغات</h1>
            <div class="info">
                <p>الفترة: ' . ($dateFrom ?: 'البداية') . ' - ' . ($dateTo ?: 'الآن') . '</p>
                <p>عدد البلاغات: ' . count($complaints) . '</p>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>العنوان</th>
                        <th>النوع</th>
                        <th>الأولوية</th>
                        <th>الحالة</th>
                        <th>الطالب</th>
                        <th>القسم</th>
                        <th>التاريخ</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($complaints as $complaint) {
            $html .= '<tr>
                <td>' . $complaint['id'] . '</td>
                <td>' . htmlspecialchars($complaint['title']) . '</td>
                <td>' . htmlspecialchars($complaint['category']) . '</td>
                <td>' . ($priorities[$complaint['priority']] ?? $complaint['priority']) . '</td>
                <td>' . ($statuses[$complaint['status']] ?? $complaint['status']) . '</td>
                <td>' . htmlspecialchars($complaint['user_name']) . '</td>
                <td>' . htmlspecialchars($complaint['department']) . '</td>
                <td>' . $complaint['created_at'] . '</td>
            </tr>';
        }

        $html .= '</tbody></table></body></html>';
        
        return $html;
    }
}
