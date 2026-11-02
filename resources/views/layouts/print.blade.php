<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'تقرير رسمي') — {{ config('app.name', 'نظام المعلومات الأمنية') }}</title>
    
    <!-- خطوط جوجل (Cairo) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">

    <!-- أيقونات FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* =========================================================
           1) إعدادات الصفحة والخطوط
        ========================================================= */
        @page {
            size: A4 portrait;
            margin: 10mm 12mm;
        }

        *, *::before, *::after {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
        }

        body {
            font-family: 'Cairo', Tahoma, sans-serif;
            direction: rtl;
            background: #e2e8f0;
            color: #0f172a;
            margin: 0;
            padding: 20px 0;
            line-height: 1.45;
            font-size: 11pt;
        }

        .print-container {
            width: 100%;
            max-width: 950px;
            margin: 0 auto 30px auto;
            position: relative;
            background: #ffffff;
            padding: 28px 32px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border-radius: 8px;
            overflow: hidden;
        }

        /* =========================================================
           2) الترويسة الرسمية (3 أعمدة هندسية متوازنة)
        ========================================================= */
        .official-header {
            display: grid;
            grid-template-columns: 1.2fr 1.6fr 1.2fr;
            align-items: center;
            border-bottom: 2.5px solid #0f2942;
            padding-bottom: 12px;
            margin-bottom: 14px;
            position: relative;
        }

        .official-header::after {
            content: '';
            position: absolute;
            bottom: -2.5px;
            right: 0;
            width: 38%;
            height: 2.5px;
            background-color: #b91c1c;
        }

        .header-right {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            text-align: right;
            padding-left: 15px;
            border-left: 1px dashed #cbd5e1;
            height: 100%;
        }

        .header-center {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 0 10px;
            height: 100%;
        }

        .header-left {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-end;
            text-align: left;
            padding-right: 15px;
            border-right: 1px dashed #cbd5e1;
            height: 100%;
        }

        .header-right .republic-name {
            font-size: 15px;
            font-weight: 800;
            color: #0f2942;
            margin: 0 0 3px 0;
            letter-spacing: .3px;
        }

        .header-right .ministry-title {
            font-size: 13px;
            font-weight: 700;
            color: #334155;
            margin: 0 0 3px 0;
        }

        .header-right .dept-title {
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            margin: 0;
        }

        .national-emblem-wrap {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-bottom: 4px;
        }

        .national-emblem-wrap img,
        .national-logo {
            max-width: 65px;
            max-height: 52px;
            width: auto;
            height: auto;
            object-fit: contain;
            display: block;
        }

        .national-emblem-wrap svg {
            width: 44px;
            height: 44px;
            fill: #0f2942;
        }

        .report-main-title {
            font-size: 18px;
            font-weight: 900;
            color: #0f2942;
            margin: 2px 0;
            letter-spacing: -0.2px;
        }

        .report-sub-code {
            display: inline-block;
            font-size: 10px;
            color: #64748b;
            margin: 0;
            font-weight: 600;
            background: #f1f5f9;
            padding: 2px 8px;
            border-radius: 4px;
            border: 1px solid #e2e8f0;
        }

        .meta-verification-card {
            display: inline-flex;
            flex-direction: column;
            background: #f8fafc;
            border: 1.5px solid #cbd5e1;
            padding: 6px 10px;
            border-radius: 6px;
            text-align: right;
            font-size: 10.5px;
            width: 100%;
            max-width: 200px;
        }

        .meta-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
            color: #334155;
        }

        .meta-row:last-child {
            margin-bottom: 0;
        }

        .meta-row strong {
            color: #0f2942;
            font-weight: 700;
        }

        /* =========================================================
           3) الشبكات (Grid)
        ========================================================= */
        .grid-profile-row {
            display: grid;
            grid-template-columns: 1fr 190px;
            gap: 12px;
            margin-bottom: 12px;
            align-items: stretch;
        }

        .grid-two-columns {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 12px;
        }

        .grid-three-columns {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 10px;
            margin-bottom: 12px;
        }

        /* =========================================================
           4) بطاقة الهوية والصورة
        ========================================================= */
        .profile-card {
            background: #f8fafc;
            border: 1.5px solid #cbd5e1;
            border-radius: 8px;
            padding: 12px 10px;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        .photo-frame {
            width: 130px;
            height: 150px;
            margin: 0 auto;
            border: 2px solid #0f2942;
            border-radius: 6px;
            overflow: hidden;
            background: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .08);
        }

        .photo-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .profile-rank-tag {
            display: inline-block;
            font-size: 11px;
            font-weight: 800;
            color: #b91c1c;
            background: #fef2f2;
            padding: 2px 10px;
            border-radius: 12px;
            border: 1px solid #fecaca;
        }

        .jihadi-name-pill {
            background: #e2e8f0;
            color: #1e293b;
            font-size: 10.5px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        /* =========================================================
           5) البطاقات والجداول
        ========================================================= */
        .section-card {
            background: #ffffff;
            border: 1.5px solid #cbd5e1;
            border-radius: 8px;
            overflow: hidden;
            page-break-inside: avoid;
            break-inside: avoid;
            margin-bottom: 12px;
        }

        .section-header {
            background: #f0f4f8;
            padding: 7px 12px;
            border-bottom: 1.5px solid #cbd5e1;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .section-header-title {
            display: flex;
            align-items: center;
            gap: 6px;
            margin: 0;
            font-size: 12px;
            font-weight: 800;
            color: #0f2942;
        }

        .section-header-title i {
            color: #b91c1c;
            font-size: 12.5px;
        }

        .section-header-badge {
            font-size: 9.5px;
            font-weight: 700;
            background: #ffffff;
            color: #475569;
            padding: 1px 6px;
            border-radius: 3px;
            border: 1px solid #cbd5e1;
        }

        table, .table, .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin-bottom: 0;
        }

        .data-table th,
        .data-table td,
        table th,
        table td {
            padding: 7px 10px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        .data-table tr:last-child th,
        .data-table tr:last-child td,
        table tr:last-child th,
        table tr:last-child td {
            border-bottom: none;
        }

        .data-table th,
        table th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 700;
            border-left: 1px solid #e2e8f0;
            text-align: right;
            white-space: nowrap;
        }

        .data-table td,
        table td {
            color: #0f172a;
            font-weight: 600;
            text-align: right;
        }

        .blood-badge {
            display: inline-block;
            background: #fee2e2;
            color: #b91c1c;
            padding: 1px 8px;
            border-radius: 4px;
            font-weight: 800;
            border: 1px solid #fca5a5;
            font-size: 11px;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 700;
        }
        .status-badge-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .status-badge-danger { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .status-badge-warning { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .status-badge-info { background: #e0f2fe; color: #075985; border: 1px solid #bae6fd; }

        /* =========================================================
           6) الملاحظات
        ========================================================= */
        .notes-box {
            background: #fcfcfd;
            border: 1.5px solid #cbd5e1;
            border-radius: 8px;
            margin-bottom: 12px;
            overflow: hidden;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .notes-content {
            padding: 8px 12px;
            font-size: 11px;
            color: #334155;
            line-height: 1.5;
            margin: 0;
            background: #ffffff;
        }

        /* =========================================================
           7) التذييل والعلامة المائية
        ========================================================= */
        .report-footer {
            margin-top: 14px;
            padding-top: 8px;
            border-top: 1.5px solid #cbd5e1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 9.5px;
            color: #64748b;
        }

        .watermark-layer {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-32deg);
            font-size: 68pt;
            font-weight: 900;
            color: rgba(15, 23, 42, 0.035);
            white-space: nowrap;
            pointer-events: none;
            z-index: 0;
            user-select: none;
            letter-spacing: 4px;
        }

        /* أزرار التحكم خارج الطباعة */
        .controls-wrapper {
            display: flex;
            justify-content: center;
            gap: 12px;
            max-width: 950px;
            margin: 0 auto 20px auto;
        }

        /* قواعد الطباعة */
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .print-container {
                max-width: 100% !important;
                width: 100% !important;
                margin: 0 !important;
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
            }
            .section-card,
            .profile-card,
            .meta-verification-card,
            .notes-box {
                box-shadow: none !important;
            }
        }
    </style>
    @yield('styles')
    @stack('styles')
</head>
<body>

    <!-- أزرار التحكم خارج الطباعة -->
    <div class="controls-wrapper no-print">
        <button onclick="window.print()" style="background: #0f2942; color: #fff; border: none; padding: 10px 24px; border-radius: 6px; font-family: inherit; font-size: 14px; font-weight: bold; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(15, 41, 66, 0.2);">
            <i class="fas fa-print"></i> طباعة المستند الآن
        </button>
        <button onclick="window.close()" style="background: #ffffff; color: #334155; border: 1px solid #cbd5e1; padding: 10px 20px; border-radius: 6px; font-family: inherit; font-size: 14px; font-weight: bold; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
            <i class="fas fa-times"></i> إغلاق النافذة
        </button>
    </div>

    @yield('content')

    @stack('scripts')
</body>
</html>
