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
                <svg viewBox="0 0 100 100" fill="currentColor">
                    <path d="M50 10 L62 38 L94 38 L68 56 L78 86 L50 68 L22 86 L32 56 L6 38 L38 38 Z" opacity="0.15" />
                    <path d="M50 15 C58 28 72 32 85 36 C75 48 65 60 50 82 C35 60 25 48 15 36 C28 32 42 28 50 15 Z" fill="none" stroke="currentColor" stroke-width="2.5" />
                    <circle cx="50" cy="42" r="14" fill="none" stroke="currentColor" stroke-width="2" />
                    <path d="M50 32 L54 42 L46 42 Z" fill="currentColor" />
                </svg>
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

        <div class="profile-card">
            <div class="photo-frame">
                @if(!empty($personalData->main_profile_photo))
                    @php
                        $photo = $personalData->main_profile_photo;
                        $photoUrl = Str::startsWith($photo, ['http://','https://']) ? $photo : asset('storage/' . $photo);
                    @endphp
                    <img src="{{ $photoUrl }}" alt="الصورة الشخصية"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="no-photo-icon" style="display:none;"><i class="fas fa-user-shield"></i></div>
                @else
                    <div class="no-photo-icon"><i class="fas fa-user-tie"></i></div>
                @endif
            </div>
            <h3 class="profile-name-badge">{{ $personalData->name ?? 'غير محدد' }}</h3>
            <div class="profile-rank-tag">{{ $personalData->rank->rank ?? 'بدون رتبة مسجلة' }}</div>
            @if(!empty($personalData->jihadi_name))
                <div class="jihadi-name-pill">
                    <i class="fas fa-tag"></i>
                    <span>{{ $personalData->jihadi_name }}</span>
                </div>
            @endif
        </div>
    </section>

    {{-- 3) البيانات الوظيفية + الطبية --}}
    <section class="grid-two-columns">
        <div class="section-card">
            <div class="section-header">
                <div class="section-header-title">
                    <i class="fas fa-award"></i>
                    <span>البيانات الوظيفية والعسكرية</span>
                </div>
                <span class="section-header-badge">الوضع الإداري</span>
            </div>
            <table class="data-table">
                <tbody>
                    <tr><th>الرتبة العسكرية</th><td class="highlight">{{ $personalData->rank->rank ?? '-' }}</td></tr>
                    <tr><th>الدائرة / المنطقة</th><td>{{ $personalData->militaryDistrict->name ?? '-' }}</td></tr>
                    <tr><th>الشعبة / الوحدة</th><td>{{ $personalData->division->name ?? '-' }}</td></tr>
                    <tr><th>حالة الوظيفة</th><td>{{ $personalData->jobStatus->job_status ?? '-' }}</td></tr>
                    <tr>
                        <th>تاريخ التعيين</th>
                        <td>{{ !empty($personalData->appointment_date) ? (is_string($personalData->appointment_date) ? $personalData->appointment_date : $personalData->appointment_date->format('Y/m/d')) : '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="section-card">
            <div class="section-header">
                <div class="section-header-title">
                    <i class="fas fa-notes-medical"></i>
                    <span>البيانات الطبية والاجتماعية</span>
                </div>
                <span class="section-header-badge">المؤهلات والحالة</span>
            </div>
            <table class="data-table">
                <tbody>
                    <tr>
                        <th>فصيلة الدم</th>
                        <td><span class="blood-badge">{{ $personalData->bloodType->type ?? '-' }}</span></td>
                    </tr>
                    <tr><th>الحالة الاجتماعية</th><td>{{ $personalData->maritalStatus->status ?? '-' }}</td></tr>
                    <tr><th>عدد الأبناء</th><td>{{ $personalData->number_of_children ?? '0' }}</td></tr>
                    <tr><th>المؤهل العلمي</th><td>{{ $personalData->qualification->name ?? '-' }}</td></tr>
                    <tr><th>التخصص الدقيق</th><td>{{ $personalData->specialization ?? '-' }}</td></tr>
                </tbody>
            </table>
        </div>
    </section>

    {{-- 4) السكن + مكان الميلاد --}}
    <section class="grid-two-columns">
        <div class="section-card">
            <div class="section-header">
                <div class="section-header-title">
                    <i class="fas fa-map-marked-alt"></i>
                    <span>بيانات السكن والإقامة الحالية</span>
                </div>
            </div>
            <table class="data-table">
                <tbody>
                    <tr><th>المحافظة</th><td>{{ $personalData->governorate->name ?? '-' }}</td></tr>
                    <tr><th>المديرية</th><td>{{ $personalData->directorate->name ?? '-' }}</td></tr>
                    <tr><th>العزلة / المنطقة</th><td>{{ $personalData->subArea->name ?? '-' }}</td></tr>
                    <tr><th>القرية / الشارع</th><td>{{ $personalData->village->name ?? '-' }}</td></tr>
                </tbody>
            </table>
        </div>

        <div class="section-card">
            <div class="section-header">
                <div class="section-header-title">
                    <i class="fas fa-compass"></i>
                    <span>مكان الميلاد والنشأة</span>
                </div>
            </div>
            <table class="data-table">
                <tbody>
                    <tr><th>الدولة</th><td>{{ $personalData->placeOfBirthCountry->name ?? 'اليمن' }}</td></tr>
                    <tr><th>المحافظة</th><td>{{ $personalData->placeOfBirthGovernorate->name ?? '-' }}</td></tr>
                    <tr><th>المديرية</th><td>{{ $personalData->placeOfBirthDirectorate->name ?? '-' }}</td></tr>
                    <tr><th>القرية / المحلة</th><td>{{ $personalData->placeOfBirthVillage->name ?? '-' }}</td></tr>
                </tbody>
            </table>
        </div>
    </section>

    {{-- 5) البيانات الثانوية والجهادية --}}
    @if($personalData->secondaryData)
        <section class="section-card" style="margin-bottom: 12px;">
            <div class="section-header">
                <div class="section-header-title">
                    <i class="fas fa-history"></i>
                    <span>البيانات الثانوية والسجل الجهادي</span>
                </div>
                <span class="section-header-badge">المعلومات الإضافية</span>
            </div>
            <table class="data-table">
                <tbody>
                    <tr>
                        <th>الحالة الصحية</th>
                        <td>{{ $personalData->secondaryData->healthStatus->name ?? '-' }}</td>
                        <th>المشرف المباشر</th>
                        <td>{{ $personalData->secondaryData->direct_supervisor ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>عام الالتحاق / الانطلاقة</th>
                        <td>{{ $personalData->secondaryData->start_date ? $personalData->secondaryData->start_date->format('Y') : '-' }}</td>
                        <th>النشاط الجهادي الحالي</th>
                        <td>{{ $personalData->secondaryData->current_jihadist_activity ?? '-' }}</td>
                    </tr>
                    @if($personalData->secondaryData->previous_jihadist_activity)
                    <tr>
                        <th>ملاحظات النشاط السابق</th>
                        <td colspan="3">{{ $personalData->secondaryData->previous_jihadist_activity }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>

            {{-- الأسلحة والعهد --}}
            @if($personalData->secondaryData->weapons && $personalData->secondaryData->weapons->count() > 0)
                <div style="padding: 6px 10px; background: #f8fafc; border-top: 1px solid #cbd5e1; font-weight: 800; font-size: 11px; color: #0f2942;">
                    الأسلحة والعهد المسجلة ({{ $personalData->secondaryData->weapons->count() }})
                </div>
                <table class="data-table">
                    <thead>
                        <tr style="background: #f1f5f9;">
                            <th style="width: 25%;">نوع السلاح</th>
                            <th style="width: 25%;">الرقم</th>
                            <th style="width: 25%;">نوع الحيازة</th>
                            <th style="width: 25%;">حالة المتابعة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($personalData->secondaryData->weapons as $weapon)
                            <tr>
                                <td>{{ $weapon->weapon_type ?? '-' }}</td>
                                <td>{{ $weapon->number ?? '-' }}</td>
                                <td>{{ $weapon->ownership ?? '-' }}</td>
                                <td>{{ $weapon->tracking ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            {{-- الانتماءات --}}
            @if($personalData->secondaryData->affiliations && $personalData->secondaryData->affiliations->count() > 0)
                <div style="padding: 6px 10px; background: #f8fafc; border-top: 1px solid #cbd5e1; font-weight: 800; font-size: 11px; color: #0f2942;">
                    سجل الانتماءات العقائدية والسياسية ({{ $personalData->secondaryData->affiliations->count() }})
                </div>
                <table class="data-table">
                    <thead>
                        <tr style="background: #f1f5f9;">
                            <th>الانتماء العقائدي</th>
                            <th>الانتماء السياسي</th>
                            <th>الجهة / الحزب</th>
                            <th>المسؤول المباشر</th>
                            <th>المدة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($personalData->secondaryData->affiliations as $aff)
                            <tr>
                                <td>{{ $aff->ideologicalAffiliation->name ?? '-' }}</td>
                                <td>{{ $aff->politicalAffiliation->name ?? '-' }}</td>
                                <td>{{ $aff->party ?? '-' }}</td>
                                <td>{{ $aff->direct_supervisor ?? '-' }}</td>
                                <td>{{ $aff->duration ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </section>
    @endif

    {{-- 6) الملاحظات --}}
    @if(!empty($personalData->notes))
        <section class="notes-box">
            <div class="section-header">
                <div class="section-header-title">
                    <i class="fas fa-comment-alt"></i>
                    <span>ملاحظات إدارية وتدقيق القيد</span>
                </div>
            </div>
            <p class="notes-content">{{ $personalData->notes }}</p>
        </section>
    @endif

    {{-- 6) التوقيعات والاعتماد --}}
    <section class="signatures-grid">
        <div class="signature-box">
            <div class="sig-role">إعداد وتجهيز المختص</div>
            <div class="sig-line">الاسم: .......................................</div>
            <div class="sig-line">التوقيع: .....................................</div>
        </div>

        <div class="signature-box">
            <div class="sig-role">تدقيق ومراجعة شؤون القوى البشرية</div>
            <div class="sig-line">الاسم: .......................................</div>
            <div class="sig-line">التوقيع: .....................................</div>
        </div>

        <div class="signature-box" style="position: relative;">
            <div class="sig-role">اعتماد مدير الدائرة / الختم الرسمي</div>
            <div style="position: absolute; bottom: 4px; left: 10px; width: 62px; height: 62px; border: 2px dashed #b91c1c; border-radius: 50%; display: flex; flex-direction: column; align-items: center; justify-content: center; transform: rotate(-8deg); opacity: .85;">
                <span style="font-size: 7px; color: #b91c1c; font-weight: 800;">الختم المعتمد</span>
                <i class="fas fa-certificate" style="font-size: 14px; color: #b91c1c; margin: 2px 0;"></i>
                <span style="font-size: 6px; color: #b91c1c;">وحدة المعلومات</span>
            </div>
            <div class="sig-line">التوقيع والختم: .......................</div>
        </div>
    </section>

    {{-- 7) التذييل --}}
    <footer class="report-footer">
        <div>
            <span>طُبع بواسطة: </span>
            <strong>{{ auth()->user()->name ?? 'النظام الآلي الموحد' }}</strong>
            <span style="margin: 0 6px;">|</span>
            <span>تاريخ الطباعة: {{ now()->format('Y/m/d - H:i:s') }}</span>
        </div>
        <div>
            <span>نظام إدارة السجلات والقوى البشرية الموحد</span>
            <span style="margin: 0 4px;">•</span>
            <span style="font-weight: 700;">صفحة 1 من 1</span>
        </div>
    </footer>
</div>

{{-- أزرار التحكم (مخفية عند الطباعة) --}}
<div class="no-print controls-wrapper">
    <button onclick="window.print()" class="px-6 py-2.5 bg-blue-700 text-white rounded-lg font-bold hover:bg-blue-800 transition-colors shadow flex items-center gap-2 text-sm cursor-pointer">
        <i class="fas fa-print"></i>
        <span>طباعة الاستمارة الرسمية (Ctrl + P)</span>
    </button>
    <button onclick="window.history.back()" class="px-5 py-2.5 bg-slate-200 text-slate-700 rounded-lg font-bold hover:bg-slate-300 transition-colors flex items-center gap-2 text-sm cursor-pointer">
        <i class="fas fa-arrow-right"></i>
        <span>العودة للنظام</span>
    </button>
</div>
@endsection