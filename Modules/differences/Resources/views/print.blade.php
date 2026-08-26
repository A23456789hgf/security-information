<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير نتائج تمايز وتحليل البيانات - {{ $analysis->filename }}</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #ffffff;
            color: #1e293b;
            margin: 0;
            padding: 30px;
            direction: rtl;
        }

        .header-container {
            border: 2px solid #0f172a;
            padding: 20px;
            margin-bottom: 30px;
            text-align: center;
            border-radius: 8px;
        }

        .header-title {
            font-size: 20px;
            font-weight: 900;
            margin: 0 0 10px 0;
            color: #0f172a;
        }

        .header-meta {
            font-size: 11px;
            color: #64748b;
            font-weight: 700;
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .stats-grid {
            display: grid;
            grid-template-cols: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }

        .stat-card {
            border: 1px solid #cbd5e1;
            padding: 15px;
            text-align: center;
            border-radius: 8px;
        }

        .stat-label {
            font-size: 10px;
            color: #64748b;
            font-weight: 700;
            text-transform: uppercase;
            display: block;
            margin-bottom: 5px;
        }

        .stat-value {
            font-size: 20px;
            font-weight: 900;
            color: #0f172a;
        }

        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 12px;
        }

        .results-table th, .results-table td {
            border: 1px solid #cbd5e1;
            padding: 10px;
            text-align: center;
        }

        .results-table th {
            background-color: #f8fafc;
            color: #0f172a;
            font-weight: 900;
        }

        .results-table td {
            font-weight: 700;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 900;
            display: inline-block;
        }

        .badge-match {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .badge-similarity {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .badge-duplicate {
            background-color: #ffedd5;
            color: #9a3412;
            border: 1px solid #fed7aa;
        }

        .badge-not-found {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
            .results-table th {
                background-color: #e2e8f0 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .badge-match {
                background-color: #d1fae5 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .badge-similarity {
                background-color: #fef3c7 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .badge-duplicate {
                background-color: #ffedd5 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .badge-not-found {
                background-color: #fee2e2 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

    <!-- Print Button (Visible on screen only) -->
    <div class="no-print" style="margin-bottom: 20px; text-align: left;">
        <button onclick="window.print()" style="padding: 10px 20px; background-color: #0f172a; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; font-family: 'Cairo';">
            إرسال إلى الطابعة أو الحفظ كـ PDF
        </button>
    </div>

    <!-- Official dossier Header -->
    <div class="header-container">
        <h1 class="header-title">تقرير تمايز ومطابقة البيانات الثنائية المتقدم</h1>
        <div class="header-meta">
            <span><strong>اسم الملف:</strong> {{ $analysis->filename }}</span>
            <span><strong>تاريخ التحليل:</strong> {{ $analysis->created_at->format('Y-m-d H:i:s') }}</span>
            <span><strong>حالة التقرير:</strong> مكتمل وموثق</span>
        </div>
    </div>

    <!-- Statistics Panel -->
    @php
        $stats = $analysis->stats ?? ['matches' => 0, 'similarities' => 0, 'duplicates' => 0, 'not_found' => 0];
        $total = ($stats['matches'] + $stats['similarities'] + $stats['duplicates'] + $stats['not_found']) ?: 1;
        $matchPercent = round((($stats['matches'] + $stats['similarities']) / $total) * 100, 2);
    @endphp
    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-label">نسبة المطابقة الإجمالية</span>
            <span class="stat-value">{{ $matchPercent }}%</span>
        </div>
        <div class="stat-card">
            <span class="stat-label">مطابقة تامة</span>
            <span class="stat-value" style="color: #065f46;">{{ $stats['matches'] }}</span>
        </div>
        <div class="stat-card">
            <span class="stat-label">تشابه قوي</span>
            <span class="stat-value" style="color: #92400e;">{{ $stats['similarities'] }}</span>
        </div>
        <div class="stat-card">
            <span class="stat-label">تكرار أو غير موجود</span>
            <span class="stat-value" style="color: #991b1b;">{{ $stats['duplicates'] + $stats['not_found'] }}</span>
        </div>
    </div>

    <!-- Results Table -->
    <table class="results-table">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 25%;">الاسم في الملف المستورد</th>
                <th style="width: 25%;">الاسم المطابق بالنظام</th>
                <th style="width: 10%;">نسبة المطابقة</th>
                <th style="width: 15%;">مستهدف البحث</th>
                <th style="width: 10%;">الحالة</th>
                <th style="width: 10%;">حالة السجل</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td style="text-align: right; padding-right: 15px;">{{ $item->external_name }}</td>
                    <td style="text-align: right; padding-right: 15px;">{{ $item->matching_name ?? '---' }}</td>
                    <td>{{ $item->matching_name ? $item->similarity_percentage . '%' : '0.00%' }}</td>
                    <td>{{ $item->source_target_name ?: '---' }}</td>
                    <td>
                        @if($item->status === 'match')
                            <span class="status-badge badge-match">مطابقة تامة</span>
                        @elseif($item->status === 'similarity')
                            <span class="status-badge badge-similarity">تشابه قوي</span>
                        @elseif($item->status === 'duplicate')
                            <span class="status-badge badge-duplicate">سجل مكرر</span>
                        @else
                            <span class="status-badge badge-not-found">غير موجود</span>
                        @endif
                    </td>
                    <td>{{ $item->record_status ?? '---' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
