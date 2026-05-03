@extends('layouts.print')

@section('title', 'تقرير البيانات الشامل - ' . ($personalData->name ?? 'بيانات'))

@section('styles')
<style>
    body {
        font-family: 'Tajawal', 'Cairo', 'Tahoma', sans-serif;
        direction: rtl;
        background: white;
        padding: 1cm;
        margin: 0;
        line-height: 1.5;
        font-size: 14px;
    }
    
    .print-container {
        max-width: 100%;
        margin: 0 auto;
    }
    
    .official-header {
        border-bottom: 2px solid #2c3e50;
        padding-bottom: 15px;
        margin-bottom: 20px;
    }
    
    .report-title {
        font-size: 20px;
        font-weight: bold;
        text-align: center;
        margin: 10px 0;
        color: #2c3e50;
    }
    
    .section-card {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        margin-bottom: 20px;
        page-break-inside: avoid;
    }
    
    .section-header {
        background-color: #f8f9fa;
        padding: 8px 15px;
        border-bottom: 1px solid #dee2e6;
        font-weight: bold;
        color: #2c3e50;
        display: flex;
        align-items: center;
    }
    
    .section-header i {
        margin-left: 10px;
    }
    
    .section-body {
        padding: 15px;
    }
    
    .info-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .info-table th, .info-table td {
        padding: 8px;
        border: 1px solid #dee2e6;
        text-align: right;
    }
    
    .info-table th {
        background-color: #fafbfc;
        width: 20%;
        font-weight: 600;
    }
    
    /* صوره شخصية */
    .profile-photo {
        width: 120px;
        height: 150px;
        border: 1px solid #ddd;
        object-fit: cover;
    }

    .photo-area {
        flex: 0 0 130px;
        text-align: center;
    }

    .data-area {
        flex: 1;
    }

    .flex-row {
        display: flex;
        gap: 20px;
    }

    .badge {
        display: inline-block;
        padding: 3px 8px;
        background: #eee;
        border-radius: 4px;
        font-size: 12px;
    }

    .signature-area {
        margin-top: 40px;
        display: flex;
        justify-content: space-around;
        text-align: center;
    }

    @media print {
        .no-print { display: none; }
        body { padding: 0; }
        .section-card { border: 1px solid #000; }
    }
</style>
@endsection

@section('content')
<div class="print-container">
    {{-- رأس التقرير --}}
    <div class="official-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div style="text-align: center;">
                <h6 style="margin:0;">الجمهورية اليمنية</h6>
                <h6 style="margin:0;">وزارة الداخلية</h6>
            </div>
            <div style="text-align: center;">
                <h2 class="report-title">تقرير الملف الشخصي المتكامل</h2>
            </div>
            <div style="text-align: right; font-size: 12px;">
                <p style="margin:0;">التاريخ: {{ now()->format('Y/m/d') }}</p>
                <p style="margin:0;">رقم القيد: #{{ $personalData->id }}</p>
            </div>
        </div>
    </div>

    {{-- البيانات الأساسية --}}
    <div class="section-card">
        <div class="section-header">
            <i class="fas fa-user-id"></i> البيانات الشخصية والتعريفية
        </div>
        <div class="section-body">
            <div class="flex-row">
                <div class="photo-area">
                    @if($personalData->main_profile_photo)
                        <img src="{{ public_path('storage/' . $personalData->main_profile_photo) }}" class="profile-photo">
                    @else
                        <div style="width:120px; height:150px; background:#f0f0f0; display:flex; align-items:center; justify-content:center; border:1px solid #ddd;">
                            لا يوجد صورة
                        </div>
                    @endif
                </div>
                <div class="data-area">
                    <table class="info-table">
                        <tr>
                            <th>الاسم الرباعي</th>
                            <td colspan="3" style="font-weight:bold; font-size:16px;">{{ $personalData->name }}</td>
                        </tr>
                        <tr>
                            <th>الاسم الجهادي</th>
                            <td>{{ $personalData->jihadi_name ?? '-' }}</td>
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
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- بيانات الموقع والسكن --}}
    <div class="section-card">
        <div class="section-header">
            <i class="fas fa-map-marker-alt"></i> بيانات الإقامة الحالية
        </div>
        <div class="section-body">
            <table class="info-table">
                <tr>
                    <th>المحافظة</th>
                    <td>{{ $personalData->governorate->name ?? '-' }}</td>
                    <th>المديرية</th>
                    <td>{{ $personalData->directorate->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>المنطقة الفرعية</th>
                    <td>{{ $personalData->subArea->name ?? '-' }}</td>
                    <th>القرية / الحي</th>
                    <td>{{ $personalData->village->name ?? '-' }}</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- بيانات الزوجات --}}
    <div class="section-card">
        <div class="section-header">
            <i class="fas fa-users"></i> بيانات أفراد الأسرة (الزوجات)
        </div>
        <div class="section-body">
            @if($personalData->wives && $personalData->wives->count() > 0)
                <table class="info-table">
                    <thead>
                        <tr style="background:#f1f1f1;">
                            <th style="width:5%;">#</th>
                            <th>اسم الزوجة</th>
                            <th>رقم الهوية</th>
                            <th>تاريخ الميلاد</th>
                            <th>رقم الهاتف</th>
                            <th>الموقع / السكن</th>
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
                                <td>
                                    {{ $wife->governorate->name ?? '' }} - {{ $wife->directorate->name ?? '' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="text-align:center; color:#666;">لا توجد بيانات زوجات مسجلة.</p>
            @endif
        </div>
    </div>

    {{-- بيانات الدورات --}}
    <div class="section-card">
        <div class="section-header">
            <i class="fas fa-graduation-cap"></i> السجل التدريبي والدورات
        </div>
        <div class="section-body">
            @if($personalData->courses && $personalData->courses->count() > 0)
                <table class="info-table">
                    <thead>
                        <tr style="background:#f1f1f1;">
                            <th style="width:5%;">#</th>
                            <th>نوع الدورة</th>
                            <th>تاريخ الانعقاد</th>
                            <th>المدة</th>
                            <th>جهة الإرسال</th>
                            <th>المسؤول</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($personalData->courses as $index => $course)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $course->courseType->name ?? 'دورة تدريبية' }}</td>
                                <td>{{ $course->course_date_gregorian ? $course->course_date_gregorian->format('Y/m/d') : '-' }}</td>
                                <td>{{ $course->course_duration ?? '-' }}</td>
                                <td>{{ $course->sendingEntity->name ?? '-' }}</td>
                                <td>{{ $course->course_administrator ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="text-align:center; color:#666;">لا توجد دورات تدريبية مسجلة.</p>
            @endif
        </div>
    </div>

    {{-- التوقيعات --}}
    <div class="signature-area">
        <div>
            <p>توقيع المختص</p>
            <div style="margin-top:40px; border-top:1px solid #000; width:150px;"></div>
        </div>
        <div>
            <p>ختم الإدارة</p>
            <div style="margin-top:40px; border:2px solid #ddd; width:100px; height:100px; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#ddd;">الختم الرسمي</div>
        </div>
        <div>
            <p>المصادقة</p>
            <div style="margin-top:40px; border-top:1px solid #000; width:150px;"></div>
        </div>
    </div>
</div>

<div class="no-print" style="margin-top:50px; text-align:center;">
    <button onclick="window.print()" style="padding:10px 30px; background:#2563eb; color:#fff; border:none; border-radius:5px; cursor:pointer;">
        <i class="fas fa-print"></i> طباعة التقرير الشامل
    </button>
    <button onclick="window.history.back()" style="padding:10px 30px; background:#64748b; color:#fff; border:none; border-radius:5px; cursor:pointer; margin-right:10px;">
        رجوع
    </button>
</div>
@endsection
