@extends('layouts.print')

@section('title', 'تقرير الملف الشخصي الشامل - ' . ($personalData->name ?? 'بيانات'))

@section('styles')
<style>
    @font-face { font-family: 'Cairo'; font-display: swap; src: local('Cairo'), local('Segoe UI'); }
    @font-face { font-family: 'Tajawal'; font-display: swap; src: local('Tajawal'), local('Segoe UI'); }

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
        padding: 1cm 1.5cm;
        background: white;
        min-height: 29.7cm; /* A4 height */
    }
    
    .official-header {
        border-bottom: 2px solid #1a2a3a;
        padding-bottom: 15px;
        margin-bottom: 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .header-logo {
        text-align: center;
        width: 180px;
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
        font-family: 'Cairo', sans-serif;
        font-size: 26px;
        font-weight: 900;
        margin: 5px 0;
        color: #1a2a3a;
    }
    
    .section-card {
        border: 1px solid #d1d5db;
        border-radius: 8px;
        margin-bottom: 20px;
        overflow: hidden;
        page-break-inside: avoid;
    }
    
    .section-header {
        background-color: #f3f4f6;
        padding: 8px 15px;
        border-bottom: 1px solid #d1d5db;
        font-weight: 800;
        font-size: 15px;
        color: #111827;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .section-header i {
        color: #2563eb;
        width: 20px;
        text-align: center;
    }
    
    .section-body {
        padding: 15px;
    }
    
    .info-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .info-table th, .info-table td {
        padding: 8px 12px;
        border: 1px solid #e5e7eb;
        text-align: right;
    }
    
    .info-table th {
        background-color: #f9fafb;
        color: #4b5563;
        font-weight: 700;
        width: 20%;
    }
    
    .info-table td {
        background-color: #fff;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }
    
    .data-table th, .data-table td {
        border: 1px solid #d1d5db;
        padding: 6px 8px;
        text-align: center;
    }
    
    .data-table th {
        background-color: #f3f4f6;
        font-weight: 800;
        color: #374151;
    }

    /* صوره شخصية */
    .profile-photo-container {
        float: left;
        width: 130px;
        height: 160px;
        border: 1px solid #1a2a3a;
        padding: 2px;
        margin-right: 15px;
        background: #fff;
    }

    .profile-photo {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .watermark {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-45deg);
        font-size: 100px;
        color: rgba(0,0,0,0.02);
        z-index: -1;
        pointer-events: none;
        white-space: nowrap;
        font-weight: 900;
    }

    .attachment-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
    }

    .attachment-item {
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 5px;
        text-align: center;
    }

    .attachment-img {
        max-width: 100%;
        height: 100px;
        object-fit: cover;
    }

    .badge {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 10px;
        font-weight: 700;
        background: #eee;
    }

    @media print {
        .no-print { display: none !important; }
        .print-page { padding: 0.2cm; }
        .section-card { border-color: #000; }
        .info-table th, .info-table td, .data-table th, .data-table td { border-color: #000; }
        .section-header { background-color: #eee !important; -webkit-print-color-adjust: exact; }
    }
</style>
@endsection

@section('content')
<div class="watermark">سري للغاية - للجهات المختصة</div>

<div class="print-page">
    {{-- رأس التقرير --}}
    <div class="official-header">
        <div class="header-logo">
            <h6 style="margin:0; font-size: 14px;">الجمهورية اليمنية</h6>
            <h6 style="margin:0; font-size: 14px;">وزارة الدفاع - التعبئة العامة</h6>
            <div style="margin-top:5px; height: 60px; border: 1px solid #ddd; border-radius: 50%; width: 60px; margin: 5px auto; display: flex; align-items: center; justify-content: center; font-size: 8px; color: #999;">شعار الوزارة</div>
        </div>
        
        <div class="header-title">
            <h2 class="report-main-title">الملف المعلوماتي الشامل</h2>
            <div style="border-top: 1px solid #1a2a3a; width: 60%; margin: 5px auto;"></div>
            <p style="margin:0; font-weight: 900; color: #dc2626;">( سري للغاية )</p>
        </div>
        
        <div class="header-info">
            <p style="margin:0;">تاريخ التقرير: {{ now()->format('Y/m/d') }}</p>
            <p style="margin:0;">رقم القيد: PD-{{ str_pad($personalData->id, 6, '0', STR_PAD_LEFT) }}</p>
            <p style="margin:0;">بواسطة: {{ auth()->user()->name ?? 'System' }}</p>
        </div>
    </div>

    {{-- 1. البيانات الشخصية الأساسية --}}
    <div class="section-card">
        <div class="section-header">
            <i class="fas fa-user-tie"></i> أولاً: البيانات التعريفية والأساسية
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
                        <th style="width: 15%;">الاسم الكامل</th>
                        <td colspan="3" style="font-size: 18px; font-weight: 900; color: #1e3a8a;">{{ $personalData->name }}</td>
                    </tr>
                    <tr>
                        <th>الاسم الجهادي</th>
                        <td style="width: 35%;">{{ $personalData->jihadi_name ?? '-' }}</td>
                        <th>الرقم الوطني</th>
                        <td>{{ $personalData->national_id_number ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>تاريخ الميلاد</th>
                        <td>{{ $personalData->date_of_birth_gregorian ? $personalData->date_of_birth_gregorian->format('Y/m/d') : '-' }}</td>
                        <th>الرقم العسكري</th>
                        <td>{{ $personalData->military_number ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>الرتبة الحالية</th>
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
                    <tr>
                        <th>جهة العمل</th>
                        <td>{{ $personalData->workplace ?? '-' }}</td>
                        <th>المسمى الوظيفي</th>
                        <td>{{ $personalData->job ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- 2. الموقع الجغرافي والسكن --}}
    <div class="section-card">
        <div class="section-header">
            <i class="fas fa-map-marker-alt"></i> ثانياً: بيانات الإقامة والتوزيع الإداري
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

    {{-- 3. البيانات الثانوية والجهادية --}}
    @if($personalData->secondaryData)
    <div class="section-card">
        <div class="section-header">
            <i class="fas fa-history"></i> ثالثاً: السجل الجهادي والبيانات الثانوية
        </div>
        <div class="section-body">
            <table class="info-table" style="margin-bottom: 15px;">
                <tr>
                    <th style="width: 20%;">الحالة الصحية</th>
                    <td style="width: 30%;">{{ $personalData->secondaryData->healthStatus->name ?? '-' }}</td>
                    <th style="width: 20%;">عدد الأولاد (ذكور / إناث / إجمالي)</th>
                    <td style="width: 30%;">
                        ذكور: {{ $personalData->secondaryData->male_children_count ?? 0 }} | 
                        إناث: {{ $personalData->secondaryData->female_children_count ?? 0 }} | 
                        الإجمالي: <strong>{{ $personalData->secondaryData->total_children_count ?? (($personalData->secondaryData->male_children_count ?? 0) + ($personalData->secondaryData->female_children_count ?? 0)) }}</strong>
                    </td>
                </tr>
                <tr>
                    <th>تاريخ البدء / الانطلاقة</th>
                    <td>{{ $personalData->secondaryData->start_date ? $personalData->secondaryData->start_date->format('Y/m/d') : '-' }}</td>
                    <th>المسؤول المباشر</th>
                    <td>{{ $personalData->secondaryData->direct_supervisor ?? '-' }}</td>
                </tr>
                <tr>
                    <th>النشاط الجهادي الحالي</th>
                    <td colspan="3">{{ $personalData->secondaryData->current_jihadist_activity ?? '-' }}</td>
                </tr>
                @if($personalData->secondaryData->previous_jihadist_activity)
                <tr>
                    <th>ملاحظات النشاط السابق</th>
                    <td colspan="3">{{ $personalData->secondaryData->previous_jihadist_activity }}</td>
                </tr>
                @endif
            </table>

            {{-- جدول الانتماء السياسي والعقائدي --}}
            @if($personalData->secondaryData->affiliations && $personalData->secondaryData->affiliations->count() > 0)
                <h6 style="margin: 12px 0 6px 0; font-weight: bold;">سجل الانتماء السياسي والعقائدي:</h6>
                <table class="data-table" style="margin-bottom: 15px;">
                    <thead>
                        <tr>
                            <th>الجهة</th>
                            <th>الانتماء العقائدي</th>
                            <th>الانتماء السياسي</th>
                            <th>تاريخ الارتباط</th>
                            <th>المدة</th>
                            <th>مكان العمل</th>
                            <th>نوع العمل</th>
                            <th>المسؤول المباشر</th>
                            <th>تاريخ الترك</th>
                            <th>سبب الترك</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($personalData->secondaryData->affiliations as $aff)
                            <tr>
                                <td>{{ $aff->party ?? '-' }}</td>
                                <td>{{ $aff->ideologicalAffiliation->name ?? '-' }}</td>
                                <td>{{ $aff->politicalAffiliation->name ?? '-' }}</td>
                                <td>{{ $aff->affiliation_date ? $aff->affiliation_date->format('Y/m/d') : '-' }}</td>
                                <td>{{ $aff->duration ?? '-' }}</td>
                                <td>{{ $aff->workplace ?? '-' }}</td>
                                <td>{{ $aff->job_type ?? '-' }}</td>
                                <td>{{ $aff->direct_supervisor ?? '-' }}</td>
                                <td>{{ $aff->leaving_date ? $aff->leaving_date->format('Y/m/d') : '-' }}</td>
                                <td>{{ $aff->leaving_reason ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            {{-- جدول الأعمال الجهادية السابقة --}}
            @if($personalData->secondaryData->previousJihadistActions && $personalData->secondaryData->previousJihadistActions->count() > 0)
                <h6 style="margin: 12px 0 6px 0; font-weight: bold;">سجل الأعمال الجهادية السابقة:</h6>
                <table class="data-table" style="margin-bottom: 15px;">
                    <thead>
                        <tr>
                            <th>النشاط</th>
                            <th>التاريخ</th>
                            <th>المدة الزمنية</th>
                            <th>المكان</th>
                            <th>المسؤول المباشر</th>
                            <th>سبب الترك</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($personalData->secondaryData->previousJihadistActions as $act)
                            <tr>
                                <td>{{ $act->activity ?? '-' }}</td>
                                <td>{{ $act->activity_date ? $act->activity_date->format('Y/m/d') : '-' }}</td>
                                <td>{{ $act->duration ?? '-' }}</td>
                                <td>{{ $act->location ?? '-' }}</td>
                                <td>{{ $act->direct_supervisor ?? '-' }}</td>
                                <td>{{ $act->leaving_reason ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            {{-- جدول الأسلحة والعهدة --}}
            @if($personalData->secondaryData->weapons && $personalData->secondaryData->weapons->count() > 0)
                <h6 style="margin: 12px 0 6px 0; font-weight: bold;">سجل العهد (الأسلحة):</h6>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>نوع السلاح</th>
                            <th>الرقم</th>
                            <th>نوع الحيازة</th>
                            <th>المتابعة</th>
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
        </div>
    </div>
    @endif

    {{-- 4. سجل الزوجات --}}
    @if($personalData->wives && $personalData->wives->count() > 0)
    <div class="section-card">
        <div class="section-header">
            <i class="fas fa-heart"></i> رابعاً: الحالة الاجتماعية (الزوجات)
        </div>
        <div class="section-body">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th>اسم الزوجة</th>
                        <th>رقم الهوية</th>
                        <th>تاريخ الميلاد</th>
                        <th>رقم الهاتف</th>
                        <th>العنوان</th>
                        <th>الحالة</th>
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
                            <td>{{ $wife->status ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- سجل الأهل والأقارب --}}
    @if(($personalData->relatives && $personalData->relatives->count() > 0) || ($personalData->enemyAreaRelatives && $personalData->enemyAreaRelatives->count() > 0) || ($personalData->travelingRelatives && $personalData->travelingRelatives->count() > 0))
    <div class="section-card">
        <div class="section-header">
            <i class="fas fa-users"></i> بيانات الأهل والأقارب
        </div>
        <div class="section-body">
            {{-- 1. الأهل والأقارب (الأساسيون) --}}
            @if($personalData->relatives && $personalData->relatives->count() > 0)
                <h6 style="margin: 5px 0 8px 0; font-weight: bold;">أولاً: بيانات الأهل والأقارب (العامة):</h6>
                <table class="data-table" style="margin-bottom: 15px;">
                    <thead>
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th>الاسم الكامل</th>
                            <th>العمر</th>
                            <th>صلة القرابة</th>
                            <th>العمل الحالي</th>
                            <th>الانتماء العقائدي</th>
                            <th>الانتماء السياسي</th>
                            <th>ملاحظات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($personalData->relatives as $index => $relative)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td style="font-weight: bold;">{{ $relative->full_name }}</td>
                                <td>{{ $relative->age ? $relative->age . ' سنة' : '-' }}</td>
                                <td>{{ $relative->kinship->name ?? '-' }}</td>
                                <td>{{ $relative->current_job ?? '-' }}</td>
                                <td>{{ $relative->ideologicalAffiliation->name ?? '-' }}</td>
                                <td>{{ $relative->politicalAffiliation->name ?? '-' }}</td>
                                <td>{{ $relative->notes ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            {{-- 2. الأهل والأقارب في مناطق العدو --}}
            @if($personalData->enemyAreaRelatives && $personalData->enemyAreaRelatives->count() > 0)
                <h6 style="margin: 12px 0 8px 0; font-weight: bold;">ثانياً: الأهل والأقارب الذين في مناطق العدو:</h6>
                <table class="data-table" style="margin-bottom: 15px;">
                    <thead>
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th>الاسم الكامل</th>
                            <th>صلة القرابة</th>
                            <th>مكان السكن</th>
                            <th>العمل</th>
                            <th>الانتماء السياسي</th>
                            <th>الانتماء العقائدي</th>
                            <th>طريقة التواصل</th>
                            <th>العلاقة</th>
                            <th>الحالة المادية</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($personalData->enemyAreaRelatives as $index => $er)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td style="font-weight: bold;">{{ $er->full_name }}</td>
                                <td>{{ $er->kinship->name ?? '-' }}</td>
                                <td>{{ $er->residence_place ?? '-' }}</td>
                                <td>{{ $er->job ?? '-' }}</td>
                                <td>{{ $er->politicalAffiliation->name ?? '-' }}</td>
                                <td>{{ $er->ideologicalAffiliation->name ?? '-' }}</td>
                                <td>{{ $er->communication_method ?? '-' }}</td>
                                <td>{{ $er->relationship ?? '-' }}</td>
                                <td>{{ $er->financialStatus->name ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            {{-- 3. الأهل والأقارب المسافرين خارج البلد --}}
            @if($personalData->travelingRelatives && $personalData->travelingRelatives->count() > 0)
                <h6 style="margin: 12px 0 8px 0; font-weight: bold;">ثالثاً: الأهل والأقارب المسافرين خارج البلد:</h6>
                <table class="data-table" style="margin-bottom: 10px;">
                    <thead>
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th>الاسم الكامل</th>
                            <th>العمر</th>
                            <th>صلة القرابة</th>
                            <th>الدولة</th>
                            <th>سبب السفر</th>
                            <th>العمل الحالي ومكانه</th>
                            <th>الجهة التابع لها</th>
                            <th>الانتماء السياسي</th>
                            <th>الانتماء العقائدي</th>
                            <th>انطباعه عن المسيرة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($personalData->travelingRelatives as $index => $tr)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td style="font-weight: bold;">{{ $tr->full_name }}</td>
                                <td>{{ $tr->age ? $tr->age . ' سنة' : '-' }}</td>
                                <td>{{ $tr->kinship->name ?? '-' }}</td>
                                <td>{{ $tr->country->name ?? '-' }}</td>
                                <td>{{ $tr->travel_reason ?? '-' }}</td>
                                <td>{{ $tr->current_job ?? '-' }} {{ $tr->workplace ? '('.$tr->workplace.')' : '' }}</td>
                                <td>{{ $tr->affiliated_entity ?? '-' }}</td>
                                <td>{{ $tr->politicalAffiliation->name ?? '-' }}</td>
                                <td>{{ $tr->ideologicalAffiliation->name ?? '-' }}</td>
                                <td>{{ $tr->march_impression ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
    @endif

    {{-- 5. سجل الدورات --}}
    @if($personalData->courses && $personalData->courses->count() > 0)
    <div class="section-card">
        <div class="section-header">
            <i class="fas fa-graduation-cap"></i> خامساً: سجل التأهيل والدورات
        </div>
        <div class="section-body">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
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
                            <td>{{ $course->courseType->name ?? '-' }}</td>
                            <td>{{ $course->course_date_gregorian ? $course->course_date_gregorian->format('Y/m/d') : '-' }}</td>
                            <td>{{ $course->course_duration ?? '-' }}</td>
                            <td>{{ $course->sendingEntity->name ?? '-' }}</td>
                            <td>{{ $course->course_administrator ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- 6. سجل التوقيفات والقضايا --}}
    @if($personalData->arrests && $personalData->arrests->count() > 0)
    <div class="section-card">
        <div class="section-header">
            <i class="fas fa-gavel"></i> سادساً: سجل القضايا والتوقيفات
        </div>
        <div class="section-body">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>تاريخ التوقيف</th>
                        <th>الجهة</th>
                        <th>القضية</th>
                        <th>الحالة</th>
                        <th>تاريخ الإطلاق</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($personalData->arrests as $arrest)
                        <tr>
                            <td>{{ $arrest->arrest_date ? $arrest->arrest_date->format('Y/m/d') : '-' }}</td>
                            <td>{{ $arrest->arrestingAuthority->name ?? '-' }}</td>
                            <td>{{ $arrest->caseType->name ?? '-' }}</td>
                            <td>{{ $arrest->status == 'released' ? 'مطلق سراحه' : 'محتجز' }}</td>
                            <td>{{ $arrest->release_date ? $arrest->release_date->format('Y/m/d') : '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- 7. البلاغات الأمنية --}}
    @if($securityReports && $securityReports->count() > 0)
    <div class="section-card">
        <div class="section-header">
            <i class="fas fa-exclamation-triangle"></i> سابعاً: البلاغات الأمنية المرتبطة
        </div>
        <div class="section-body">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>النوع</th>
                        <th>الموضوع</th>
                        <th>الجهة</th>
                        <th>الوضعية</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($securityReports as $report)
                        <tr>
                            <td>{{ $report->report_date ? $report->report_date->format('Y/m/d') : '-' }}</td>
                            <td>{{ $report->report_type ?? '-' }}</td>
                            <td>{{ $report->report_name ?? '-' }}</td>
                            <td>{{ $report->entity->name ?? '-' }}</td>
                            <td>{{ $report->status ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- 8. سجل الاشتباه --}}
    @if($personalData->suspects && $personalData->suspects->count() > 0)
    <div class="section-card">
        <div class="section-header">
            <i class="fas fa-search-minus"></i> ثامناً: سجل الاشتباه والمراقبة
        </div>
        <div class="section-body">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>نوع الاشتباه</th>
                        <th>المستوى</th>
                        <th>المصدر</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($personalData->suspects as $suspect)
                        <tr>
                            <td>{{ $suspect->suspicion_date ? $suspect->suspicion_date->format('Y/m/d') : '-' }}</td>
                            <td>{{ $suspect->suspicion_type ?? '-' }}</td>
                            <td>{{ $suspect->suspicion_level ?? '-' }}</td>
                            <td>{{ $suspect->information_source ?? '-' }}</td>
                            <td>{{ $suspect->is_arrested ? 'موقوف' : 'قيد المتابعة' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- 9. البيانات الديناميكية المضافة --}}
    @if($dynamicRecords && $dynamicRecords->count() > 0)
    <div class="section-card">
        <div class="section-header">
            <i class="fas fa-database"></i> تاسعاً: البيانات والسجلات الإضافية (النظام الديناميكي)
        </div>
        <div class="section-body">
            @foreach($dynamicRecords as $record)
                <div style="margin-bottom: 10px; border-bottom: 1px dashed #ddd; padding-bottom: 5px;">
                    <strong style="color: #2563eb;">سجل من صفحة: {{ $record->page->name }}</strong>
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-top: 5px;">
                        @foreach($record->values as $val)
                            <div style="font-size: 11px;">
                                <span style="font-weight: bold; color: #666;">{{ $val->field->label }}:</span>
                                <span>{{ $val->value }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- 10. حساب النظام المرتبط --}}
    @if($relatedUser)
    <div class="section-card">
        <div class="section-header">
            <i class="fas fa-user-shield"></i> عاشراً: بيانات حساب النظام (المستخدم)
        </div>
        <div class="section-body">
            <table class="info-table">
                <tr>
                    <th>اسم المستخدم</th>
                    <td>{{ $relatedUser->username }}</td>
                    <th>الحالة</th>
                    <td>{{ $relatedUser->is_active ? 'نشط' : 'معطل' }}</td>
                </tr>
                <tr>
                    <th>الأدوار / الصلاحيات</th>
                    <td>
                        @foreach($relatedUser->roles as $role)
                            <span class="badge">{{ $role->name }}</span>
                        @endforeach
                    </td>
                    <th>الجهة المرتبط بها</th>
                    <td>{{ $relatedUser->internal_entity->name ?? '-' }}</td>
                </tr>
            </table>
        </div>
    </div>
    @endif

    {{-- 11. المرفقات والوثائق --}}
    @if($personalData->photos && $personalData->photos->count() > 0)
    <div class="section-card">
        <div class="section-header">
            <i class="fas fa-images"></i> حادي عشر: الوثائق والمرفقات المصورة
        </div>
        <div class="section-body">
            <div class="attachment-grid">
                @foreach($personalData->photos as $photo)
                    <div class="attachment-item">
                        <img src="{{ public_path('storage/' . $photo->photo_path) }}" class="attachment-img">
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- التوقيعات --}}
    <div style="margin-top: 40px; display: flex; justify-content: space-between; text-align: center;">
        <div style="width: 200px;">
            <p><strong>توقيع مختص الأرشيف</strong></p>
            <div style="margin-top:40px; border-bottom: 1px solid #000;"></div>
        </div>
        <div style="width: 150px; height: 150px; border: 2px dashed #ccc; border-radius: 50%; display: flex; items-center justify-center; color: #ccc; font-size: 10px;">
            ختم المركز الرسمي
        </div>
        <div style="width: 200px;">
            <p><strong>المصادقة (مدير الإدارة)</strong></p>
            <div style="margin-top:40px; border-bottom: 1px solid #000;"></div>
        </div>
    </div>
</div>

{{-- أزرار التحكم - لا تظهر في الطباعة --}}
<div class="no-print" style="position: fixed; bottom: 30px; left: 30px; display: flex; gap: 10px;">
    <button onclick="window.print()" style="padding: 12px 25px; background: #1e3a8a; color: #fff; border: none; border-radius: 50px; cursor: pointer; display: flex; align-items: center; box-shadow: 0 4px 15px rgba(0,0,0,0.2); font-weight: bold;">
        <i class="fas fa-print" style="margin-left: 10px;"></i> تنفيذ الطباعة
    </button>
    <button onclick="window.close()" style="padding: 12px 25px; background: #ef4444; color: #fff; border: none; border-radius: 50px; cursor: pointer; display: flex; align-items: center; box-shadow: 0 4px 15px rgba(0,0,0,0.2); font-weight: bold;">
        <i class="fas fa-times" style="margin-left: 10px;"></i> إغلاق
    </button>
</div>
@endsection
