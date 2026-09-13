<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'تقرير مطبوع') — {{ config('app.name', 'الأمن الاستخباراتي') }}</title>
    
    {{-- Google Fonts: Cairo & Tajawal (Identical to tem) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/all.min.css') }}">

    <!-- Compiled App Styles -->
    @vite(['resources/css/app.css'])

    <style>
        body {
            font-family: 'Cairo', system-ui, sans-serif;
            background-color: #ffffff;
            color: #0f172a;
            padding: 0;
            margin: 0;
            font-size: 11pt;
            line-height: 1.6;
        }

        @page {
            size: A4 portrait;
            margin: 15mm;
        }

        .print-header {
            border-bottom: 2px solid #0f172a;
            margin-bottom: 24px;
            padding-bottom: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .report-section {
            break-inside: avoid;
            page-break-inside: avoid;
            margin-bottom: 24px;
        }

        .section-title {
            background-color: #f1f5f9;
            border-right: 4px solid #4f46e5;
            padding: 8px 16px;
            font-weight: 700;
            margin-bottom: 12px;
            border-radius: 4px 0 0 4px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        table, .table, .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        th, td {
            border: 1px solid #e2e8f0;
            padding: 8px 12px;
            text-align: right;
            font-size: 10pt;
        }

        th {
            background-color: #f8fafc;
            width: 30%;
            font-weight: 700;
            color: #334155;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .no-print {
            display: none;
        }

        @media print {
            .no-print, button, .btn {
                display: none !important;
            }
            body {
                background: #ffffff !important;
                color: #000000 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            ::-webkit-scrollbar {
                display: none;
            }
        }

        .timestamp {
            font-size: 9pt;
            color: #64748b;
            margin-top: 32px;
            text-align: left;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-white text-slate-900">
    <div class="container-fluid p-4">
        @yield('content')
        
        <div class="timestamp">
            استخرج بواسطة النظام في: {{ now()->format('Y-m-d H:i') }}
        </div>
    </div>

    @stack('scripts')
</body>
</html>
