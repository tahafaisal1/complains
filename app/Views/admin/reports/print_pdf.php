<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير البلاغات - <?= date('Y-m-d') ?></title>
    
    <!-- Google Fonts - Cairo for Arabic -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Cairo', 'Segoe UI', Tahoma, sans-serif;
            box-sizing: border-box;
        }
        
        body {
            direction: rtl;
            text-align: right;
            background: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .no-print {
            margin-bottom: 20px;
            text-align: center;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #4f46e5;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            margin: 0 5px;
        }
        
        .btn:hover {
            background: #4338ca;
        }
        
        .btn-secondary {
            background: #6b7280;
        }
        
        .btn-secondary:hover {
            background: #4b5563;
        }
        
        h1 {
            text-align: center;
            color: #4f46e5;
            margin-bottom: 10px;
        }
        
        .info {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th, td {
            border: 1px solid #e5e7eb;
            padding: 12px 15px;
            text-align: right;
        }
        
        th {
            background: #4f46e5;
            color: white;
            font-weight: 600;
        }
        
        tr:nth-child(even) {
            background: #f9fafb;
        }
        
        tr:hover {
            background: #f3f4f6;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .badge-open { background: #fef3c7; color: #92400e; }
        .badge-in_progress { background: #dbeafe; color: #1e40af; }
        .badge-resolved { background: #d1fae5; color: #065f46; }
        .badge-closed { background: #e5e7eb; color: #374151; }
        
        .badge-low { background: #e0e7ff; color: #3730a3; }
        .badge-medium { background: #fef3c7; color: #92400e; }
        .badge-high { background: #fed7aa; color: #c2410c; }
        .badge-urgent { background: #fee2e2; color: #991b1b; }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .container {
                box-shadow: none;
                padding: 0;
            }
            
            .no-print {
                display: none !important;
            }
            
            table {
                font-size: 11px;
            }
            
            th, td {
                padding: 8px 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="no-print">
            <button class="btn" onclick="window.print()">
                <i class="fas fa-print"></i> طباعة / حفظ PDF
            </button>
            <a href="<?= base_url('admin/reports') ?>" class="btn btn-secondary">
                رجوع
            </a>
            <p style="margin-top: 15px; color: #666; font-size: 14px;">
                💡 لحفظ كـ PDF: اختر "Save as PDF" أو "حفظ كـ PDF" من خيارات الطابعة
            </p>
        </div>
        
        <h1>📊 تقرير البلاغات</h1>
        <div class="info">
            <p><strong>الفترة:</strong> <?= $dateFrom ?: 'البداية' ?> - <?= $dateTo ?: 'الآن' ?></p>
            <p><strong>عدد البلاغات:</strong> <?= count($complaints) ?></p>
            <p><strong>تاريخ التقرير:</strong> <?= date('Y-m-d H:i') ?></p>
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
            <tbody>
                <?php foreach ($complaints as $complaint): ?>
                <tr>
                    <td><?= $complaint['id'] ?></td>
                    <td><?= esc($complaint['title']) ?></td>
                    <td><?= esc($complaint['category']) ?></td>
                    <td>
                        <span class="badge badge-<?= $complaint['priority'] ?>">
                            <?= $priorities[$complaint['priority']] ?? $complaint['priority'] ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-<?= $complaint['status'] ?>">
                            <?= $statuses[$complaint['status']] ?? $complaint['status'] ?>
                        </span>
                    </td>
                    <td><?= esc($complaint['user_name']) ?></td>
                    <td><?= esc($complaint['department']) ?></td>
                    <td><?= date('Y-m-d', strtotime($complaint['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <script>
        // Auto-trigger print dialog after page loads
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
