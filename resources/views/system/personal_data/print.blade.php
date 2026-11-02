@extends('layouts.print')

{{-- 
=============================================================================
* تقرير السجل الأساسي والبيانات الشخصية - قالب الطباعة الأمثل (A4 Portrait)
* متوافق مع: الطباعة المباشرة (Ctrl+P)، DomPDF، Browsershot، Snappy
* الإصدار: 2.0 - نسخة معتمدة للطباعة والأرشفة الرسمية
=============================================================================
--}}

@section('title', 'تقرير السجل الأساسي - ' . ($personalData->name ?? 'منتسب'))

@section('styles')
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
        font-family: 'Cairo', 'Tajawal', 'Almarai', 'Segoe UI', Tahoma, sans-serif;
        direction: rtl;
        background: #ffffff;
        color: #0f172a;
        margin: 0;
        padding: 0;
        line-height: 1.45;
        font-size: 11pt;
    }

    .print-container {
        width: 100%;
        max-width: 1000px;
        margin: 0 auto;
        position: relative;
        background: #ffffff;
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

    .header-right  { text-align: right; padding-left: 10px; border-left: 1px dashed #cbd5e1; }
    .header-center { text-align: center; padding: 0 10px; }
    .header-left   { text-align: left;  padding-right: 10px; border-right: 1px dashed #cbd5e1; }

    .header-right .republic-name  { font-size: 15px; font-weight: 800; color: #0f2942; margin: 0; letter-spacing: .3px; }
    .header-right .ministry-title { font-size: 13px; font-weight: 700; color: #334155; margin: 2px 0; }
    .header-right .dept-title     { font-size: 11px; font-weight: 600; color: #64748b; margin: 0; }

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
        font-size: 19px;
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

    /* بطاقة التوثيق المرجعي والباركود */
    .meta-verification-card {
        display: inline-flex;
        flex-direction: column;
        background: #f8fafc;
        border: 1.5px solid #cbd5e1;
        padding: 6px 10px;
        border-radius: 6px;
        text-align: right;
        font-size: 10.5px;
        min-width: 175px;
    }

    .meta-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 3px;
        color: #334155;
    }
    .meta-row:last-child { margin-bottom: 0; }
    .meta-row strong { color: #0f2942; font-weight: 700; }

    .barcode-preview {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 4px;
        padding-top: 3px;
        border-top: 1px dashed #cbd5e1;
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
        width: 110px;
        height: 125px;
        margin: 0 auto 8px;
        border: 2px solid #0f2942;
        border-radius: 6px;
        overflow: hidden;
        background: #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .08);
    }
    .photo-frame img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .photo-frame .no-photo-icon { color: #94a3b8; font-size: 42px; }

    .profile-name-badge {
        font-size: 14px;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 3px 0;
        line-height: 1.3;
    }

    .profile-rank-tag {
        display: inline-block;
        font-size: 11.5px;
        font-weight: 800;
        color: #b91c1c;
        background: #fef2f2;
        padding: 2px 10px;
        border-radius: 12px;
        border: 1px solid #fecaca;
    }

    .jihadi-name-pill {
        margin-top: 6px;
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
        font-size: 12.5px;
        font-weight: 800;
        color: #0f2942;
    }
    .section-header-title i { color: #b91c1c; font-size: 13px; }

    .section-header-badge {
        font-size: 9.5px;
        font-weight: 700;
        background: #ffffff;
        color: #475569;
        padding: 1px 6px;
        border-radius: 3px;
        border: 1px solid #cbd5e1;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 11px;
    }

    .data-table th,
    .data-table td {
        padding: 6px 10px;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
    }
    .data-table tr:last-child th,
    .data-table tr:last-child td { border-bottom: none; }

    .data-table th {
        background-color: #f8fafc;
        color: #475569;
        font-weight: 700;
        width: 25%;
        border-left: 1px solid #e2e8f0;
        text-align: right;
        white-space: nowrap;
    }
    .data-table td {
        color: #0f172a;
        font-weight: 600;
        text-align: right;
    }
    .data-table td.highlight { color: #b91c1c; font-weight: 800; }

    .blood-badge {
        display: inline-block;
        background: #fee2e2;
        color: #b91c1c;
        padding: 1px 8px;
        border-radius: 4px;
        font-weight: 800;
        border: 1px solid #fca5a5;
        font-size: 11.5px;
    }

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
       7) التوقيعات والاعتماد الرسمي
    ========================================================= */
    .signatures-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 12px;
        margin-top: 14px;
        margin-bottom: 12px;
        page-break-inside: avoid;
        break-inside: avoid;
    }

    .signature-box {
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 8px 10px;
        background: #ffffff;
        text-align: center;
        min-height: 80px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .sig-role {
        font-size: 11px;
        font-weight: 800;
        color: #0f2942;
        border-bottom: 1px dashed #cbd5e1;
        padding-bottom: 4px;
        margin-bottom: 25px;
    }
    .sig-line { font-size: 10px; color: #64748b; }

    /* =========================================================
       8) التذييل والعلامة المائية
    ========================================================= */
    .report-footer {
        margin-top: 12px;
        padding-top: 8px;
        border-top: 1.5px solid #cbd5e1;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 9.5px;
        color: #64748b;
        page-break-inside: avoid;
        break-inside: avoid;
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

    /* =========================================================
       9) قواعد الطباعة
    ========================================================= */
    @media print {
        .no-print { display: none !important; }
        body { background: #ffffff !important; padding: 0 !important; margin: 0 !important; }
        .print-container {
            max-width: 100% !important;
            width: 100% !important;
            margin: 0 !important;
            border: none !important;
            box-shadow: none !important;
        }
        .section-card,
        .profile-card,
        .meta-verification-card,
        .signature-box,
        .notes-box { box-shadow: none !important; }
    }

    /* أزرار التحكم خارج الطباعة */
    .controls-wrapper {
        display: flex;
        justify-content: center;
        gap: 12px;
        margin-top: 25px;
        margin-bottom: 20px;
        padding: 16px;
        background: #f8fafc;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
    }
</style>
@endsection

@section('content')
<div class="print-container">

    {{-- العلامة المائية --}}
    <div class="watermark-layer">وثيقة رسمية معتمدة</div>

    {{-- 1) الترويسة الرسمية --}}
    <header class="official-header">
        <div class="header-right">
            <h3 class="republic-name">{{ $settings['ministry'] ?? 'الجمهورية اليمنية' }}</h3>
            <h4 class="ministry-title">{{ $settings['authority'] ?? 'التعبئة العامة' }}</h4>
            <p class="dept-title">{{ $settings['unit'] ?? 'وحدة نظم وتكنولوجيا المعلومات' }}</p>
        </div>

        <div class="header-center">
            <div class="national-emblem-wrap">
                <img src="{{ asset('images/logo1.png') }}" alt="شعار" class="national-logo" onerror="this.onerror=null; this.src='/images/logo1.png';">
            </div>
            <h2 class="report-main-title">{{ $settings['report_title'] ?? 'تقرير السجل الأساسي للمنتسب' }}</h2>
            <span class="report-sub-code">وثيقة قيد وسجل بيانات شخصية • معتمدة رقمياً</span>
        </div>

        <div class="header-left">
            <div class="meta-verification-card">
                <div class="meta-row">
                    <span>الرقم المرجعي:</span>
                    <strong>#{{ str_pad($personalData->id ?? 1, 6, '0', STR_PAD_LEFT) }}</strong>
                </div>
                <div class="meta-row">
                    <span>تاريخ الإصدار:</span>
                    <span>{{ now()->format('Y/m/d') }}</span>
                </div>
                <div class="meta-row">
                    <span>وقت التصدير:</span>
                    <span>{{ now()->format('H:i') }}</span>
                </div>
                <div class="barcode-preview">
                    <svg style="width: 100%; height: 20px;" viewBox="0 0 140 22" preserveAspectRatio="none">
                        @php
                            $bars = [2=>3,8=>1.5,13=>4,20=>2,25=>5,34=>2,40=>3,46=>1,50=>4,58=>2.5,64=>1.5,70=>4,78=>2,83=>5,92=>1.5,97=>3,103=>4,111=>2,116=>5,125=>2,130=>4];
                        @endphp
                        @foreach($bars as $x => $w)
                            <rect x="{{ $x }}" y="1" width="{{ $w }}" height="18" fill="#1e293b"/>
                        @endforeach
                    </svg>
                    <span style="font-size: 8px; color: #64748b; letter-spacing: 2px;">
                        {{ str_pad($personalData->military_number ?? $personalData->id ?? 0, 10, '0', STR_PAD_LEFT) }}
                    </span>
                </div>
            </div>
        </div>
    </header>

    {{-- 2) البيانات التعريفية + بطاقة الصورة --}}
    <section class="grid-profile-row">
        
        {{-- جدول البيانات الأساسية --}}
        <div class="section-card">
            <div class="section-header">
                <div class="section-header-title">
                    <i class="fas fa-id-card"></i>
                    <span>البيانات التعريفية الأساسية</span>
                </div>
                <span class="section-header-badge">السجل المدني والعسكري</span>
            </div>
            <table class="data-table">
                <tbody>
                    <tr>
                        <th>الاسم الكامل واللقب</th>
                        <td colspan="3" style="font-size: 13px; font-weight: 800; color: #0f172a;">
                            {{ $personalData->name ?? '-' }}
                        </td>
                    </tr>
                    <tr>
                        <th>الرقم الوطني / البطاقة</th>
                        <td style="font-family: monospace; font-size: 12px; font-weight: 700;">
                            {{ $personalData->national_id_number ?? '-' }}
                        </td>
                        <th>الرقم العسكري</th>
                        <td style="font-family: monospace; font-size: 12px; font-weight: 800; color: #b91c1c;">
                            {{ $personalData->military_number ?? '-' }}
                        </td>
                    </tr>
                    <tr>
                        <th>تاريخ الميلاد (ميلادي)</th>
                        <td>{{ !empty($personalData->date_of_birth_gregorian) ? (is_string($personalData->date_of_birth_gregorian) ? $personalData->date_of_birth_gregorian : $personalData->date_of_birth_gregorian->format('Y/m/d')) : '-' }}</td>
                        <th>تاريخ الميلاد (هجري)</th>
                        <td>{{ $personalData->date_of_birth_hijri ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>الجنسية</th>
                        <td>{{ $personalData->placeOfBirthCountry->name ?? 'يمني' }}</td>
                        <th>الديانة</th>
                        <td>مسلم</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- بطاقة الصورة --}}
        <div class="profile-card">
            <div class="photo-frame">
                @if(!empty($personalData->main_profile_photo))
                    <img src="{{ asset('storage/' . $personalData->main_profile_photo) }}" alt="الصورة الشخصية">
                @else
                    <i class="fas fa-user no-photo-icon" style="color: #94a3b8; font-size: 42px;"></i>
                @endif
            </div>
            
            <h4 class="profile-name-badge">
                {{ explode(' ', $personalData->name ?? '')[0] ?? 'المنتسب' }}
            </h4>
            
            <div class="profile-rank-tag">
                {{ $personalData->rank ?? 'غير محدد' }}
            </div>
            
            @if(!empty($personalData->jihadi_name))
                <div class="jihadi-name-pill">
                    <i class="fas fa-star" style="color: #b91c1c; font-size: 9px;"></i>
                    {{ $personalData->jihadi_name }}
                </div>
            @endif
        </div>
    </section>

    {{-- 3) بيانات إضافية (مقسمة لعمودين) --}}
    <section class="grid-two-columns">
        <div class="section-card">
            <div class="section-header">
                <div class="section-header-title">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>بيانات السكن والاتصال</span>
                </div>
            </div>
            <table class="data-table">
                <tbody>
                    <tr>
                        <th>المحافظة</th>
                        <td>{{ $personalData->governorate->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>المديرية</th>
                        <td>{{ $personalData->directorate->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>رقم الهاتف</th>
                        <td dir="ltr" style="text-align: right;">{{ $personalData->phone_number ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="section-card">
            <div class="section-header">
                <div class="section-header-title">
                    <i class="fas fa-file-medical"></i>
                    <span>البيانات الطبية والتجنيد</span>
                </div>
            </div>
            <table class="data-table">
                <tbody>
                    <tr>
                        <th>فصيلة الدم</th>
                        <td>
                            <span class="blood-badge">{{ $personalData->blood_type ?? '-' }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>الحالة الصحية</th>
                        <td>{{ $personalData->health_status ?? 'لائق' }}</td>
                    </tr>
                    <tr>
                        <th>تاريخ التجنيد</th>
                        <td>{{ $personalData->join_date ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    {{-- 4) الملاحظات الإضافية --}}
    <section class="notes-box">
        <div class="section-header">
            <div class="section-header-title">
                <i class="fas fa-sticky-note"></i>
                <span>ملاحظات السجل</span>
            </div>
        </div>
        <div class="notes-content">
            {{ $personalData->notes ?? 'لا توجد ملاحظات إضافية مقيدة في السجل حتى تاريخ التصدير.' }}
        </div>
    </section>

    {{-- 5) التوقيعات والاعتماد الرسمي --}}
    <section class="signatures-grid">
        <div class="signature-box">
            <div class="sig-role">مسؤول السجلات والبيانات</div>
            <div class="sig-line">الاسم: .......................................</div>
            <div class="sig-line" style="margin-top: 8px;">التوقيع: ....................................</div>
        </div>
        <div class="signature-box">
            <div class="sig-role">مدير إدارة الموارد البشرية</div>
            <div class="sig-line">الاسم: .......................................</div>
            <div class="sig-line" style="margin-top: 8px;">التوقيع: ....................................</div>
        </div>
        <div class="signature-box">
            <div class="sig-role">اعتماد قائد الوحدة / المدير العام</div>
            <div class="sig-line">الختم الرسمي:</div>
            <div class="sig-line" style="margin-top: 15px; text-align: center;">(ختم الجهة)</div>
        </div>
    </section>

    {{-- 6) التذييل --}}
    <footer class="report-footer">
        <div>
            تم استخراج هذه الوثيقة من النظام الإلكتروني بتاريخ {{ now()->format('Y-m-d H:i') }}
        </div>
        <div style="font-weight: 700;">
            {{ $settings['system_name'] ?? 'نظام السجل الإلكتروني' }} - صفحة 1 من 1
        </div>
    </footer>

</div>

{{-- أزرار الطباعة تظهر فقط على الشاشة وتختفي عند الطباعة --}}
<div class="controls-wrapper no-print">
    <button onclick="window.print()" style="background: #0f2942; color: #fff; border: none; padding: 8px 20px; border-radius: 6px; font-family: inherit; font-weight: bold; cursor: pointer;">
        <i class="fas fa-print"></i> طباعة التقرير
    </button>
    <a href="{{ url()->previous() }}" style="background: #e2e8f0; color: #0f172a; text-decoration: none; padding: 8px 20px; border-radius: 6px; font-family: inherit; font-weight: bold;">
        عودة
    </a>
</div>

@endsection