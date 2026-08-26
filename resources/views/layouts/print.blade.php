<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'تقرير مطبوع')</title>
    
    <!-- Bootstrap RTL (Local) -->
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/bootstrap.rtl.min.css') }}">
    <!-- Font Awesome (Local) -->
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/all.min.css') }}">

    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-color: white;
            color: #000;
            padding: 0;
            margin: 0;
            font-size: 11pt;
            line-height: 1.6;
        }

        @page {
            size: A4;
            margin: 15mm;
        }

        .print-header {
            border-bottom: 2px solid #333;
            margin-bottom: 20px;
            padding-bottom: 15px;
        }

        .report-section {
            break-inside: avoid;
            margin-bottom: 20px;
        }

        .section-title {
            background-color: #f0f4f8;
            border-right: 5px solid #4e73df;
            padding: 8px 15px;
            font-weight: 700;
            margin-bottom: 10px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px 12px;
            text-align: right;
        }

        th {
            background-color: #f9fafb;
            width: 30%;
            font-weight: 600;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .no-print {
            display: none;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            
            button, .btn {
                display: none !important;
            }
        }

        .timestamp {
            font-size: 9pt;
            color: #666;
            margin-top: 30px;
            text-align: left;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        @yield('content')
        
        <div class="timestamp">
            استخرج بواسطة النظام في: {{ now()->format('Y-m-d H:i') }}
        </div>
    </div>

    <script>
        window.onload = function() {
            // Optional: window.print();
        };
    </script>
</body>
</html>
