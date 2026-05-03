@extends('layouts.print')

@section('title', 'تقرير الملف الشخصي الشامل - ' . ($personalData->name ?? 'بيانات'))

@section('styles')
<style>
    body {
        font-family: 'Tajawal', 'Cairo', 'Tahoma', sans-serif;
        direction: rtl;
        background: white;
        padding: 0;
        margin: 0;
        line-height: 1.6;
        font-size: 14px;
        color: #333;
    }
    
    .print-page {
        padding: 1.5cm;
        background: white;
        min-height: 29.7cm; /* A4 height */
    }
    
    .official-header {
        border-bottom: 3px double #2c3e50;
        padding-bottom: 15px;
        margin-bottom: 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .header-logo {
        text-align: center;
        width: 150px;
    }
    
    .header-title {
        text-align: center;
        flex: 1;
    }
    
    .header-info {
        text-align: right;
        width: 180px;
        font-size: 11px;
    }
    
    .report-main-title {
        font-size: 24px;
        font-weight: 800;
        margin: 5px 0;
        color: #1a2a3a;
        text-decoration: underline;
        text-underline-offset: 8px;
    }
    
    .section-card {
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        margin-bottom: 25px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        page-break-inside: avoid;
    }
    
    .section-header {
        background-color: #f1f4f8;
        padding: 10px 18px;
        border-bottom: 1px solid #e0e0e0;
        font-weight: 700;
        font-size: 16px;
        color: #2c3e50;
        display: flex;
        align-items: center;
    }
    
    .section-header i {
        margin-left: 12px;
        color: #3498db;
        width: 20px;
        text-align: center;
    }
    
    .section-body {
        padding: 18px;
    }
    
    .info-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 0;
    }
    
    .info-table th, .info-table td {
        padding: 10px 12px;
        border: 1px solid #e0e0e0;
        text-align: right;
    }
    
    .info-table th {
        background-color: #f9fafb;
        color: #555;
        font-weight: 600;
        width: 22%;
    }
    
    .info-table td {
        background-color: #fff;
    }

    .data-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0;
    }

    /* صوره شخصية */
    .profile-photo-container {
        float: left;
        width: 140px;
        height: 170px;
        border: 2px solid #2c3e50;
        padding: 3px;
        margin-right: 20px;
        background: #fff;
    }

    .profile-photo {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* الجداول في الأقسام */
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .data-table th, .data-table td {
        border: 1px solid #e0e0e0;
        padding: 8px 10px;
        text-align: center;
        font-size: 13px;
    }
    
    .data-table th {
        background-color: #f1f4f8;
        font-weight: bold;
    }

    .signature-box {
        margin-top: 50px;
        display: flex;
        justify-content: space-around;
        text-align: center;
        padding: 20px 0;
    }

    .signature-item {
        width: 200px;
    }

    .seal-placeholder {
        width: 110px;
        height: 110px;
        border: 2px dashed #ccc;
        border-radius: 50%;
        margin: 15px auto;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ccc;
        font-size: 10px;
    }

    .watermark {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-45deg);
        font-size: 80px;
        color: rgba(0,0,0,0.03);
        z-index: -1;
        pointer-events: none;
        white-space: nowrap;
    }

    @media print {
        .no-print { display: none !important; }
        .print-page { padding: 0.5cm; }
        body { background: white; }
        .section-card { border-color: #999; }
        .info-table th, .info-table td, .data-table th, .data-table td { border-color: #999; }
    }
</style>
@endsection

@section('content')
<div class="watermark">سرية للغاية - للمختصين فقط</div>

<div class="print-page">
    {{-- رأس التقرير --}}
    <div class="official-header">
        <div class="header-logo">
            <h6 style="margin:0;">الجمهورية اليمنية</h6>
            <h6 style="margin:0;">التعبئة العامة</h6>
            <div style="margin-top:5px; height: 50px; border: 1px solid #eee; display: flex; align-items: center; justify-content: center; font-size: 8px; color: #ccc;">شعار الوزارة</div>
        </div>
        
        <div class="header-title">
            <h2 class="report-main-title">تقرير الملف الشخصي المتكامل</h2>
            <p style="margin:0; font-weight: bold;">(سري للغاية)</p>
        </div>
        
        <div class="header-info">
            <p style="margin:0;">التاريخ: {{ now()->format('Y/m/d') }}م</p>
            <p style="margin:0;">الوقت: {{ now()->format('H:i') }}</p>
            <p style="margin:0;">رقم السجل الرقمي: PD-{{ str_pad($personalData->id, 6, '0', STR_PAD_LEFT) }}</p>
            <p style="margin:0;">المستخدم المطبوع: {{ auth()->user()->name ?? 'نظام آلي' }}</p>
        </div>
    </div>

    {{-- 1. البيانات الأساسية --}}
    <div class="section-card">
        <div class="section-header">
            <i class="fas fa-id-card"></i> البيانات الشخصية والتعريفية الأساسية
        </div>
        <div class="section-body">
            @if($personalData->main_profile_photo)
                <div class="profile-photo-container">
                    <img src="{{ public_path('storage/' . $personalData->main_profile_photo) }}" class="profile-photo">
                </div>
            @endif
            
            <div style="overflow: hidden;">
                <table class="info-table">
                    <tr>
                        <th style="width: 15%;">الاسم الرباعي واللقب</th>
                        <td colspan="3" style="font-size: 18px; font-weight: 800; color: #1a2a3a;">{{ $personalData->name }}</td>
                    </tr>
                    <tr>
                        <th>الاسم الجهادي</th>
                        <td style="width: 35%;">{{ $personalData->jihadi_name ?? '-' }}</td>
                        <th>رقم الهوية</th>
                        <td>{{ $personalData->national_id_number ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>تاريخ الميلاد</th>
                        <td>{{ $personalData->date_of_birth_gregorian ? $personalData->date_of_birth_gregorian->format('Y/m/d') : '-' }}</td>
                        <th>الرقم العسكري</th>
                        <td>{{ $personalData->military_number ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>الرتبة</th>
                        <td>{{ $personalData->rank->rank ?? '-' }}</td>
                        <th>فصيلة الدم</th>
                        <td>{{ $personalData->bloodType->type ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>الحالة الاجتماعية</th>
                        <td>{{ $personalData->maritalStatus->status ?? '-' }}</td>
                        <th>المؤهل العلمي</th>
                        <td>{{ $personalData->qualification->name ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- 2. بيانات السكن والموقع --}}
    <div class="section-card">
        <div class="section-header">
            <i class="fas fa-map-marked-alt"></i> بيانات الإقامة والموقع الجغرافي
        </div>
        <div class="section-body">
            <table class="info-table">
                <tr>
                    <th>محافظة السكن</th>
                    <td>{{ $personalData->governorate->name ?? '-' }}</td>
                    <th>المديرية</th>
                    <td>{{ $personalData->directorate->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>العزلة / المنطقة</th>
                    <td>{{ $personalData->subArea->name ?? '-' }}</td>
                    <th>القرية / المحل</th>
                    <td>{{ $personalData->village->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>المنطقة العسكرية</th>
                    <td>{{ $personalData->militaryDistrict->name ?? '-' }}</td>
                    <th>الشعبة / الوحدة</th>
                    <td>{{ $personalData->division->name ?? '' }} / {{ $personalData->unit ?? '-' }}</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- 3. سجل الدورات التدريبية --}}
    <div class="section-card">
        <div class="section-header">
            <i class="fas fa-graduation-cap"></i> سجل التأهيل والتدريب (الدورات)
        </div>
        <div class="section-body">
            @if($personalData->courses && $personalData->courses->count() > 0)
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 25%;">نوع الدورة</th>
                            <th>تاريخ الانعقاد</th>
                            <th>المدة</th>
                            <th>جهة الإرسال</th>
                            <th>المسؤول المباشر</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($personalData->courses as $index => $course)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $course->courseType->name ?? '-' }}</td>
                                <td>{{ $course->course_date_gregorian ? $course->course_date_gregorian->format('Y/m/d') : '-' }}</td>
                                <td>{{ $course->course_duration ?? '-' }}</td>
                                <td>{{ $course->sendingEntity->name ?? '-' }}</td>
                                <td>{{ $course->course_administrator ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div style="text-align:center; padding: 10px; color: #7f8c8d;">لا يوجد سجل دورات تدريبية لهذا الشخص.</div>
            @endif
        </div>
    </div>

    {{-- 4. سجل التوقيفات والاحتجاز --}}
    <div class="section-card">
        <div class="section-header">
            <i class="fas fa-handcuffs"></i> سجل التوقيفات والاحتجاز (سجل الأحداث)
        </div>
        <div class="section-body">
            @if($personalData->arrests && $personalData->arrests->count() > 0)
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th>تاريخ التوقيف</th>
                            <th>جهة التوقيف</th>
                            <th>نوع القضية</th>
                            <th>الحالة</th>
                            <th>تاريخ الإطلاق</th>
                            <th>النتائج</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($personalData->arrests as $index => $arrest)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $arrest->arrest_date ? $arrest->arrest_date->format('Y/m/d') : '-' }}</td>
                                <td>{{ $arrest->arrestingAuthority->name ?? '-' }}</td>
                                <td>{{ $arrest->caseType->name ?? '-' }}</td>
                                <td>
                                    @if($arrest->status == 'released') مطلح سراحه @elseif($arrest->status == 'detained') محتجز @else {{ $arrest->status }} @endif
                                </td>
                                <td>{{ $arrest->release_date ? $arrest->release_date->format('Y/m/d') : '-' }}</td>
                                <td>{{ Str::limit($arrest->case_follow_up_results, 50) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div style="text-align:center; padding: 10px; color: #7f8c8d;">لا يوجد سجل توقيفات لهذا الشخص.</div>
            @endif
        </div>
    </div>

    {{-- 5. سجل البلاغات الأمنية المنسوبة --}}
    <div class="section-card">
        <div class="section-header">
            <i class="fas fa-shield-alt"></i> سجل البلاغات الأمنية المرتبطة
        </div>
        <div class="section-body">
            @if($securityReports && $securityReports->count() > 0)
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th>تاريخ البلاغ</th>
                            <th>نوع البلاغ</th>
                            <th>الموضوع / العنوان</th>
                            <th>الجهة المرتبطة</th>
                            <th>الوضعية</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($securityReports as $index => $report)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $report->report_date ? $report->report_date->format('Y/m/d') : '-' }}</td>
                                <td>{{ $report->report_type ?? '-' }}</td>
                                <td>{{ $report->report_name ?? '-' }}</td>
                                <td>{{ $report->entity->name ?? '-' }}</td>
                                <td>{{ $report->status ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div style="text-align:center; padding: 10px; color: #7f8c8d;">لا توجد بلاغات أمنية مرتبطة بهذا الشخص.</div>
            @endif
        </div>
    </div>

    {{-- 6. بيانات الزوجات --}}
    <div class="section-card">
        <div class="section-header">
            <i class="fas fa-users"></i> الحالة الاجتماعية (بيانات الزوجات)
        </div>
        <div class="section-body">
            @if($personalData->wives && $personalData->wives->count() > 0)
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th>اسم الزوجة</th>
                            <th>رقم الهوية</th>
                            <th>تاريخ الميلاد</th>
                            <th>رقم الهاتف</th>
                            <th>السكن</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($personalData->wives as $index => $wife)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $wife->wife_name ?? $wife->name }}</td>
                                <td>{{ $wife->wife_id_number ?? '-' }}</td>
                                <td>{{ $wife->wife_birth_date ?? '-' }}</td>
                                <td>{{ $wife->wife_phone ?? '-' }}</td>
                                <td>{{ $wife->governorate->name ?? '' }} - {{ $wife->directorate->name ?? '' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div style="text-align:center; padding: 10px; color: #7f8c8d;">لا يوجد بيانات أسرية مسجلة.</div>
            @endif
        </div>
    </div>

    {{-- التوقيعات والختم --}}
    <!-- <div class="signature-box">
        <div class="signature-item">
            <p><strong>توقيع مختص الأرشفة</strong></p>
            <div style="margin-top:45px; border-bottom: 1px solid #000; width: 140px; margin-left: auto; margin-right: auto;"></div>
            <p style="font-size: 11px; margin-top: 5px;">الرتبة / .......................</p>
        </div>
        
        <div class="signature-item">
            <div class="seal-placeholder">
                الختم الرسمي للمركز
            </div>
            <p style="font-size: 11px; margin-top: 5px;">تاريخ المصادقة: {{ now()->format('Y/m/d') }}م</p>
        </div>
        
        <div class="signature-item">
            <p><strong>المصادقة (مدير الإدارة)</strong></p>
            <div style="margin-top:45px; border-bottom: 1px solid #000; width: 140px; margin-left: auto; margin-right: auto;"></div>
            <p style="font-size: 11px; margin-top: 5px;">الاسم / .......................</p>
        </div>
    </div> -->
</div>

{{-- أزرار التحكم - لا تظهر في الطباعة --}}
<div class="no-print" style="position: fixed; bottom: 30px; left: 30px; display: flex; gap: 10px;">
    <button onclick="window.print()" style="padding: 12px 25px; background: #2c3e50; color: #fff; border: none; border-radius: 50px; cursor: pointer; display: flex; align-items: center; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
        <i class="fas fa-print" style="margin-left: 10px;"></i> بدء الطباعة الفورية
    </button>
    <button onclick="window.close()" style="padding: 12px 25px; background: #e74c3c; color: #fff; border: none; border-radius: 50px; cursor: pointer; display: flex; align-items: center; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
        <i class="fas fa-times" style="margin-left: 10px;"></i> إغلاق النافذة
    </button>
</div>
@endsection
