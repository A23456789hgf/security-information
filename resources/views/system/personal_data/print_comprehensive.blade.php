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
        color: #0f172a;
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
        border: 1px solid #bae6fd;
        border-radius: 8px;
        margin-bottom: 20px;
        overflow: hidden;
        page-break-inside: avoid;
    }
    
    .section-header {
        background-color: #dbeafe;         /* سماوي فاتح */
        padding: 8px 15px;
        border-bottom: 1px solid #bae6fd;
        font-weight: 800;
        font-size: 15px;
        color: #0c4a6e;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .section-header i {
        color: #0284c7;
        width: 20px;
        text-align: center;
    }
    
    .section-body {
        padding: 15px;
        background: #f8fcff;              /* خلفية سماوية شفافة جداً */
    }
    
    .info-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .info-table th, .info-table td {
        padding: 8px 12px;
        border: 1px solid #cfe9f7;
        text-align: right;
    }
    
    .info-table th {
        background-color: #e8f4fd;         /* سماوي ناعم */
        color: #0c4a6e;
        font-weight: 700;
        width: 20%;
    }
    
    .info-table td {
        background-color: #ffffff;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }
    
    .data-table th, .data-table td {
        border: 1px solid #bae6fd;
        padding: 6px 8px;
        text-align: center;
    }
    
    .data-table th {
        background-color: #dbeafe;         /* سماوي فاتح */
        font-weight: 800;
        color: #0c4a6e;
    }
    
    .data-table tbody tr:nth-child(even) td {
        background-color: #f8fcff;         /* تخطيط صفوف سماوي فاتح جداً */
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
        color: rgba(2,132,199,0.04);       /* لمسة سماوية للعلامة المائية */
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
        border: 1px solid #bae6fd;
        border-radius: 5px;
        padding: 5px;
        text-align: center;
        background: #f8fcff;
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
        background: #dbeafe;
        color: #0c4a6e;
    }

    @media print {
        .no-print { display: none !important; }
        .print-page { padding: 0.2cm; }
        .section-card { border-color: #0284c7; }
        .info-table th, .info-table td, .data-table th, .data-table td { border-color: #0284c7; }
        .section-header { background-color: #bae6fd !important; -webkit-print-color-adjust: exact; }
        .info-table th,
        .data-table th { background-color: #bae6fd !important; -webkit-print-color-adjust: exact; }
        .section-body { background-color: #f0f9ff !important; -webkit-print-color-adjust: exact; }
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
            <div style="margin-top:5px; height: 60px; border: 1px solid #bae6fd; border-radius: 50%; width: 60px; margin: 5px auto; display: flex; align-items: center; justify-content: center; font-size: 8px; color: #0284c7; background: #f0f9ff;">شعار الوزارة</div>
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
            @if($personalData->photo_url || $personalData->main_profile_photo)
                <div class="profile-photo-container">
                    <img src="{{ $personalData->photo_url ?? asset('storage/' . $personalData->main_profile_photo) }}" class="profile-photo" alt="صورة المنتسب">
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
                        <th>الرقم الوطني / الهوية</th>
                        <td>{{ $personalData->national_id_number ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>تاريخ الميلاد (ميلادي)</th>
                        <td>{{ $personalData->date_of_birth_gregorian ? $personalData->date_of_birth_gregorian->format('Y/m/d') : '-' }}</td>
                        <th>تاريخ الميلاد (هجري)</th>
                        <td>{{ $personalData->date_of_birth_hijri ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>الرقم العسكري</th>
                        <td>{{ $personalData->military_number ?? '-' }}</td>
                        <th>الرقم الجهادي</th>
                        <td>{{ $personalData->jihadist_number ?? '-' }}</td>
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
                        <th>الحالة المادية</th>
                        <td>{{ $personalData->financialStatus->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>المؤهل العلمي</th>
                        <td>{{ $personalData->qualification->name ?? '-' }}</td>
                        <th>التخصص الدقيق</th>
                        <td>{{ $personalData->job_specialty ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>جهة العمل والكتيبة</th>
                        <td>{{ $personalData->workplace ?? '-' }} @if($personalData->unit) ({{ $personalData->unit }}) @endif</td>
                        <th>المسمى الوظيفي / العمل</th>
                        <td>{{ $personalData->job ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>تصنيف الرقم</th>
                        <td>{{ $personalData->numberClassification->name ?? '-' }}</td>
                        <th>أرقام التواصل والتلفون</th>
                        <td style="font-weight: bold; font-family: monospace; color: #0c4a6e;">
                            {{ implode(' - ', array_filter([$personalData->phone_1, $personalData->phone_2, $personalData->phone_3])) ?: '-' }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- 2. الموقع الجغرافي والسكن --}}
    <div class="section-card">
        <div class="section-header">
            <i class="fas fa-map-marker-alt"></i> ثانياً: بيانات الإقامة والميلاد والتوزيع الإداري
        </div>
        <div class="section-body">
            <table class="info-table">
                <tr>
                    <th style="width: 20%;">مكان الميلاد والنشأة</th>
                    <td colspan="3">
                        {{ implode(' - ', array_filter([
                            $personalData->placeOfBirthCountry->name ?? null,
                            $personalData->placeOfBirthGovernorate->name ?? null,
                            $personalData->placeOfBirthDirectorate->name ?? null,
                            $personalData->placeOfBirthSubArea->name ?? null,
                            $personalData->placeOfBirthVillage->name ?? null,
                        ])) ?: '-' }}
                    </td>
                </tr>
                <tr>
                    <th>محافظة السكن الحالية</th>
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
                    <th>الشعبة / الوحدة / الجهة</th>
                    <td>{{ $personalData->division->name ?? '-' }} / {{ $personalData->unit ?? '-' }} @if($personalData->entity) ({{ $personalData->entity->name }}) @endif</td>
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
                    <th style="width: 20%;">عدد الأولاد</th>
                    <td style="width: 30%;">
                        <strong>{{ $personalData->secondaryData->total_children_count ?? (($personalData->secondaryData->male_children_count ?? 0) + ($personalData->secondaryData->female_children_count ?? 0)) }}</strong>
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

    {{-- سجل الملف الأمني السري --}}
    @if($personalData->securityFiles && $personalData->securityFiles->count() > 0)
    <div class="section-card">
        <div class="section-header">
            <i class="fas fa-shield-alt"></i> رابعاً: سجل الملف الأمني السري
        </div>
        <div class="section-body">
            @foreach($personalData->securityFiles as $file)
                <div style="margin-bottom: 15px; border: 1px solid #bae6fd; border-radius: 6px; padding: 12px; background-color: #f0f9ff;">
                    <table class="info-table" style="margin-bottom: 10px;">
                        <tr>
                            <th style="width: 15%;">رقم الملف</th>
                            <td style="width: 35%; font-weight: bold; color: #b91c1c;">#{{ $file->file_number ?? str_pad($file->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <th style="width: 15%;">تاريخ الملف</th>
                            <td>{{ $file->file_date_gregorian ? $file->file_date_gregorian->format('Y/m/d') : '-' }} @if($file->file_date_hijri) ({{ $file->file_date_hijri }}) @endif</td>
                        </tr>
                        <tr>
                            <th>الجهة المعدة للملف</th>
                            <td>{{ $file->preparingEntity->name ?? '-' }}</td>
                            <th>المعد للملف</th>
                            <td>{{ $file->file_preparer ?? '-' }}</td>
                        </tr>
                        @if($file->summary_preparer || $file->summary_date_gregorian)
                        <tr>
                            <th>معد الملخص</th>
                            <td>{{ $file->summary_preparer ?? '-' }}</td>
                            <th>تاريخ الملخص</th>
                            <td>{{ $file->summary_date_gregorian ? $file->summary_date_gregorian->format('Y/m/d') : '-' }} @if($file->summary_date_hijri) ({{ $file->summary_date_hijri }}) @endif</td>
                        </tr>
                        @endif
                    </table>

                    @if($file->highlight_summary)
                    <div style="margin-top: 8px;">
                        <strong style="display: block; margin-bottom: 4px; font-size: 13px; color: #0c4a6e;">أبرز البيانات الملخصة من الملف:</strong>
                        <div style="background: #fff; border: 1px solid #bae6fd; padding: 8px 12px; border-radius: 4px; font-size: 12px; white-space: pre-line;">{{ $file->highlight_summary }}</div>
                    </div>
                    @endif

                    @if($file->jihadist_status)
                    <div style="margin-top: 8px;">
                        <strong style="display: block; margin-bottom: 4px; font-size: 13px; color: #0c4a6e;">بيانات الوضع الجهادي:</strong>
                        <div style="background: #fff; border: 1px solid #bae6fd; padding: 8px 12px; border-radius: 4px; font-size: 12px; white-space: pre-line;">{{ $file->jihadist_status }}</div>
                    </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- 5. سجل الزوجات --}}
    @if($personalData->wives && $personalData->wives->count() > 0)
    <div class="section-card">
        <div class="section-header">
            <i class="fas fa-heart"></i> خامساً: الحالة الاجتماعية (الزوجات) ({{ $personalData->wives->count() }} زوجة)
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
                        <th>العنوان والإقامة</th>
                        <th>الحالة</th>
                        <th>ملاحظات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($personalData->wives as $index => $wife)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td style="font-weight: bold;">{{ $wife->wife_name ?? $wife->name }}</td>
                            <td>{{ $wife->wife_id_number ?? '-' }}</td>
                            <td>{{ $wife->wife_birth_date ?? '-' }}</td>
                            <td>{{ $wife->wife_phone ?? '-' }}</td>
                            <td>{{ $wife->governorate->name ?? '' }} {{ $wife->directorate ? '- ' . $wife->directorate->name : '' }}</td>
                            <td>{{ $wife->status ?? '-' }}</td>
                            <td>{{ $wife->notes ?? '-' }}</td>
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
                            <th>مكان السكن والتلفون</th>
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
                                <td>{{ $relative->residence_place ?? '-' }} {{ $relative->phone ? '('.$relative->phone.')' : '' }}</td>
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
            <i class="fas fa-graduation-cap"></i> خامساً: سجل التأهيل والدورات ({{ $personalData->courses->count() }} دورة)
        </div>
        <div class="section-body">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 4%;">#</th>
                        <th>نوع / اسم الدورة</th>
                        <th>مكان الانعقاد</th>
                        <th>تاريخ الانعقاد</th>
                        <th>المدة</th>
                        <th>جهة الإرسال</th>
                        <th>المسؤول</th>
                        <th>ملاحظات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($personalData->courses as $index => $course)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td style="font-weight: bold;">
                                {{ $course->courseType->name ?? '-' }}
                                @if($course->course_name) ({{ $course->course_name }}) @endif
                            </td>
                            <td>{{ $course->place ?? $course->location ?? '-' }}</td>
                            <td>{{ $course->course_date_gregorian ? $course->course_date_gregorian->format('Y/m/d') : ($course->start_date ? $course->start_date->format('Y/m/d') : '-') }}</td>
                            <td>{{ $course->course_duration ?? '-' }}</td>
                            <td>{{ $course->sendingEntity->name ?? '-' }}</td>
                            <td>{{ $course->course_administrator ?? '-' }}</td>
                            <td>{{ $course->notes ?? '-' }}</td>
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
            <i class="fas fa-gavel"></i> سادساً: سجل القضايا والتوقيفات ({{ $personalData->arrests->count() }} قضية)
        </div>
        <div class="section-body">
            @foreach($personalData->arrests as $arrestIndex => $arrest)
                <div style="margin-bottom: 18px; border: 1px solid #bae6fd; border-radius: 8px; overflow: hidden; @if(!$loop->last) margin-bottom: 18px; @endif">
                    {{-- رأس القضية --}}
                    <div style="background: #1e3a8a; color: #fff; padding: 8px 14px; display: flex; justify-content: space-between; align-items: center;">
                        <strong style="font-size: 13px;">
                            <i class="fas fa-file-alt" style="margin-left: 6px;"></i>
                            القضية رقم {{ $arrestIndex + 1 }}: {{ $arrest->caseType->name ?? 'غير محدد' }}
                        </strong>
                        <span style="font-size: 11px; background: {{ $arrest->status == 'released' ? '#16a34a' : '#dc2626' }}; padding: 2px 10px; border-radius: 12px;">
                            {{ $arrest->status == 'released' ? 'مطلق سراحه' : 'لا يزال محتجزاً' }}
                        </span>
                    </div>

                    {{-- البيانات الأساسية للقضية --}}
                    <table class="info-table" style="margin-bottom: 0; border-radius: 0;">
                        <tr>
                            <th style="width: 16%;">جهة التوقيف</th>
                            <td style="width: 34%;">{{ $arrest->arrestingAuthority->name ?? '-' }}</td>
                            <th style="width: 16%;">تاريخ التوقيف</th>
                            <td>{{ $arrest->arrest_date ? $arrest->arrest_date->format('Y/m/d') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>مكان التوقيف</th>
                            <td>{{ $arrest->arrest_location ?? $arrest->location ?? '-' }}</td>
                            <th>مدة التوقيف</th>
                            <td>{{ $arrest->duration_period ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>تاريخ الإطلاق</th>
                            <td>{{ $arrest->release_date ? $arrest->release_date->format('Y/m/d') : '-' }}</td>
                            <th>سبب الإطلاق</th>
                            <td>{{ $arrest->release_reason ?? '-' }}</td>
                        </tr>
                    </table>

                    {{-- تفاصيل القضية التفصيلية --}}
                    @php
                        $hasDetails = $arrest->case_details || $arrest->seized_items || $arrest->case_follow_up_results || $arrest->related_information || $arrest->investigation_reports || $arrest->conclusion || $arrest->notes;
                    @endphp
                    @if($hasDetails)
                        <div style="padding: 10px 14px; background: #eff6ff; border-top: 1px solid #bae6fd;">
                            @if($arrest->case_details)
                                <div style="margin-bottom: 8px;">
                                    <strong style="color: #0c4a6e; display: block; margin-bottom: 3px; font-size: 12px;"><i class="fas fa-clipboard-list" style="margin-left: 4px;"></i>تفاصيل القضية:</strong>
                                    <div style="background: #fff; border: 1px solid #bae6fd; padding: 7px 10px; border-radius: 4px; font-size: 11.5px; white-space: pre-line; line-height: 1.6;">{{ $arrest->case_details }}</div>
                                </div>
                            @endif
                            @if($arrest->seized_items)
                                <div style="margin-bottom: 8px;">
                                    <strong style="color: #b45309; display: block; margin-bottom: 3px; font-size: 12px;"><i class="fas fa-box" style="margin-left: 4px;"></i>المضبوطات والأدلة:</strong>
                                    <div style="background: #fffbeb; border: 1px solid #fcd34d; padding: 7px 10px; border-radius: 4px; font-size: 11.5px; white-space: pre-line; line-height: 1.6;">{{ $arrest->seized_items }}</div>
                                </div>
                            @endif
                            @if($arrest->investigation_reports)
                                <div style="margin-bottom: 8px;">
                                    <strong style="color: #6d28d9; display: block; margin-bottom: 3px; font-size: 12px;"><i class="fas fa-file-signature" style="margin-left: 4px;"></i>محاضر التحقيق:</strong>
                                    <div style="background: #f5f3ff; border: 1px solid #ddd6fe; padding: 7px 10px; border-radius: 4px; font-size: 11.5px; white-space: pre-line; line-height: 1.6;">{{ $arrest->investigation_reports }}</div>
                                </div>
                            @endif
                            @if($arrest->case_follow_up_results)
                                <div style="margin-bottom: 8px;">
                                    <strong style="color: #0369a1; display: block; margin-bottom: 3px; font-size: 12px;"><i class="fas fa-search" style="margin-left: 4px;"></i>نتائج المتابعة:</strong>
                                    <div style="background: #f0f9ff; border: 1px solid #bae6fd; padding: 7px 10px; border-radius: 4px; font-size: 11.5px; white-space: pre-line; line-height: 1.6;">{{ $arrest->case_follow_up_results }}</div>
                                </div>
                            @endif
                            @if($arrest->related_information)
                                <div style="margin-bottom: 8px;">
                                    <strong style="color: #047857; display: block; margin-bottom: 3px; font-size: 12px;"><i class="fas fa-link" style="margin-left: 4px;"></i>المعلومات ذات الصلة:</strong>
                                    <div style="background: #ecfdf5; border: 1px solid #a7f3d0; padding: 7px 10px; border-radius: 4px; font-size: 11.5px; white-space: pre-line; line-height: 1.6;">{{ $arrest->related_information }}</div>
                                </div>
                            @endif
                            @if($arrest->conclusion)
                                <div style="margin-bottom: 8px;">
                                    <strong style="color: #1e3a8a; display: block; margin-bottom: 3px; font-size: 12px;"><i class="fas fa-flag-checkered" style="margin-left: 4px;"></i>الخلاصة والحكم:</strong>
                                    <div style="background: #eff6ff; border: 1px solid #bfdbfe; padding: 7px 10px; border-radius: 4px; font-size: 11.5px; white-space: pre-line; line-height: 1.6;">{{ $arrest->conclusion }}</div>
                                </div>
                            @endif
                            @if($arrest->notes)
                                <div>
                                    <strong style="color: #374151; display: block; margin-bottom: 3px; font-size: 12px;"><i class="fas fa-sticky-note" style="margin-left: 4px;"></i>ملاحظات:</strong>
                                    <div style="background: #fff; border: 1px solid #bae6fd; padding: 7px 10px; border-radius: 4px; font-size: 11.5px; white-space: pre-line; line-height: 1.6;">{{ $arrest->notes }}</div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- 7. البلاغات الأمنية --}}
    @if($securityReports && $securityReports->count() > 0)
    <div class="section-card">
        <div class="section-header">
            <i class="fas fa-exclamation-triangle"></i> سابعاً: البلاغات الأمنية المرتبطة ({{ $securityReports->count() }} بلاغ)
        </div>
        <div class="section-body">
            @foreach($securityReports as $reportIndex => $report)
                <div style="margin-bottom: 20px; border: 1px solid #bae6fd; border-radius: 8px; overflow: hidden; @if(!$loop->last) margin-bottom: 20px; @endif">
                    {{-- رأس البلاغ --}}
                    <div style="background: #7c2d12; color: #fff; padding: 8px 14px; display: flex; justify-content: space-between; align-items: center;">
                        <strong style="font-size: 13px;">
                            <i class="fas fa-exclamation-circle" style="margin-left: 6px;"></i>
                            البلاغ رقم {{ $reportIndex + 1 }}: {{ $report->report_name ?? ('بلاغ أمني - ' . ($report->report_type ?? 'غير محدد')) }}
                        </strong>
                        <span style="font-size: 11px; background: rgba(255,255,255,0.2); padding: 2px 10px; border-radius: 12px;">
                            {{ $report->status ?? 'غير محدد' }}
                        </span>
                    </div>

                    {{-- البيانات الأساسية للبلاغ --}}
                    <table class="info-table" style="margin-bottom: 0; border-radius: 0;">
                        <tr>
                            <th style="width: 15%;">نوع البلاغ</th>
                            <td style="width: 35%;">{{ $report->report_type ?? '-' }}</td>
                            <th style="width: 15%;">تاريخ البلاغ</th>
                            <td>{{ $report->report_date ? $report->report_date->format('Y/m/d') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>الجهة المختصة</th>
                            <td>{{ $report->entity->name ?? '-' }}</td>
                            <th>تصنيف القضية</th>
                            <td>{{ $report->case_classification ?? $report->classification ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>التهمة / الشبهة</th>
                            <td>{{ $report->charge ?? '-' }}</td>
                            <th>تصنيف الخطورة</th>
                            <td>{{ $report->classification ?? '-' }}</td>
                        </tr>

                        @if($report->arrest_date || $report->arrest_status)
                        <tr>
                            <th>حالة التوقيف</th>
                            <td>{{ $report->arrest_status ?? '-' }}</td>
                            <th>تاريخ التوقيف</th>
                            <td>{{ $report->arrest_date ? $report->arrest_date->format('Y/m/d') : '-' }}</td>
                        </tr>
                        @endif

                        @if($report->deposit_date || $report->detention_source)
                        <tr>
                            <th>جهة الاحتجاز</th>
                            <td>{{ $report->detention_source ?? '-' }}</td>
                            <th>تاريخ الإيداع</th>
                            <td>{{ $report->deposit_date ? $report->deposit_date->format('Y/m/d') : '-' }}</td>
                        </tr>
                        @endif

                        @if($report->referral_authority)
                        <tr>
                            <th>جهة الإحالة</th>
                            <td colspan="3">{{ $report->referral_authority }}</td>
                        </tr>
                        @endif

                        @if($report->date_of_release || $report->source_of_release || $report->case_fate)
                        <tr>
                            <th>مصير القضية</th>
                            <td>{{ $report->case_fate ?? '-' }}</td>
                            <th>تاريخ الإطلاق</th>
                            <td>{{ $report->date_of_release ? $report->date_of_release->format('Y/m/d') : '-' }}</td>
                        </tr>
                        @if($report->source_of_release)
                        <tr>
                            <th>مصدر الإطلاق</th>
                            <td colspan="3">{{ $report->source_of_release }}</td>
                        </tr>
                        @endif
                        @endif

                        @if($report->front_of_operation || $report->position_with_enemy)
                        <tr>
                            <th>جبهة / منطقة التواجد</th>
                            <td>{{ $report->front_of_operation ?? '-' }}</td>
                            <th>الموقف مع العدو</th>
                            <td>{{ $report->position_with_enemy ?? '-' }}</td>
                        </tr>
                        @endif

                        @if($report->broker_name || $report->broker_number)
                        <tr>
                            <th>اسم الوسيط</th>
                            <td>{{ $report->broker_name ?? '-' }}</td>
                            <th>رقم الوسيط</th>
                            <td>{{ $report->broker_number ?? '-' }}</td>
                        </tr>
                        @endif

                        @if($report->police_station)
                        <tr>
                            <th>مركز الشرطة</th>
                            <td colspan="3">{{ $report->police_station }}</td>
                        </tr>
                        @endif
                    </table>

                    {{-- الحقول النصية التفصيلية --}}
                    @php
                        $reportHasDetails = $report->procedures || $report->current_activity || $report->notes || $report->additional_notes || $report->positional_assets;
                    @endphp
                    @if($reportHasDetails)
                        <div style="padding: 10px 14px; background: #eff6ff; border-top: 1px solid #bae6fd;">
                            @if($report->current_activity)
                                <div style="margin-bottom: 8px;">
                                    <strong style="color: #7c2d12; display: block; margin-bottom: 3px; font-size: 12px;"><i class="fas fa-bolt" style="margin-left: 4px;"></i>النشاط الحالي:</strong>
                                    <div style="background: #fff; border: 1px solid #fed7aa; padding: 7px 10px; border-radius: 4px; font-size: 11.5px; white-space: pre-line; line-height: 1.6;">{{ $report->current_activity }}</div>
                                </div>
                            @endif
                            @if($report->positional_assets)
                                <div style="margin-bottom: 8px;">
                                    <strong style="color: #7c2d12; display: block; margin-bottom: 3px; font-size: 12px;"><i class="fas fa-briefcase" style="margin-left: 4px;"></i>الأصول / الممتلكات:</strong>
                                    <div style="background: #fff; border: 1px solid #fed7aa; padding: 7px 10px; border-radius: 4px; font-size: 11.5px; white-space: pre-line; line-height: 1.6;">{{ $report->positional_assets }}</div>
                                </div>
                            @endif
                            @if($report->procedures)
                                <div style="margin-bottom: 8px;">
                                    <strong style="color: #7c2d12; display: block; margin-bottom: 3px; font-size: 12px;"><i class="fas fa-tasks" style="margin-left: 4px;"></i>الإجراءات المتخذة:</strong>
                                    <div style="background: #fff; border: 1px solid #fed7aa; padding: 7px 10px; border-radius: 4px; font-size: 11.5px; white-space: pre-line; line-height: 1.6;">{{ $report->procedures }}</div>
                                </div>
                            @endif
                            @if($report->notes)
                                <div style="@if($report->additional_notes) margin-bottom: 8px; @endif">
                                    <strong style="color: #374151; display: block; margin-bottom: 3px; font-size: 12px;"><i class="fas fa-sticky-note" style="margin-left: 4px;"></i>ملاحظات:</strong>
                                    <div style="background: #fff; border: 1px solid #bae6fd; padding: 7px 10px; border-radius: 4px; font-size: 11.5px; white-space: pre-line; line-height: 1.6;">{{ $report->notes }}</div>
                                </div>
                            @endif
                            @if($report->additional_notes)
                                <div>
                                    <strong style="color: #374151; display: block; margin-bottom: 3px; font-size: 12px;"><i class="fas fa-info-circle" style="margin-left: 4px;"></i>ملاحظات إضافية:</strong>
                                    <div style="background: #fff; border: 1px solid #bae6fd; padding: 7px 10px; border-radius: 4px; font-size: 11.5px; white-space: pre-line; line-height: 1.6;">{{ $report->additional_notes }}</div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- 8. سجل الاشتباه --}}
    @if($personalData->suspects && $personalData->suspects->count() > 0)
    <div class="section-card">
        <div class="section-header">
            <i class="fas fa-search-minus"></i> ثامناً: سجل الاشتباه والمراقبة ({{ $personalData->suspects->count() }} قيد)
        </div>
        <div class="section-body">
            @foreach($personalData->suspects as $suspectIndex => $suspect)
                <div style="margin-bottom: 15px; border: 1px solid #bae6fd; border-radius: 6px; overflow: hidden; background: #fff;">
                    <div style="background: #0f172a; color: #fff; padding: 6px 12px; display: flex; justify-content: space-between; align-items: center; font-size: 12px;">
                        <strong><i class="fas fa-user-secret" style="margin-left: 6px;"></i>قيد اشتباه رقم {{ $suspectIndex + 1 }} @if($suspect->suspect_number) (#{{ $suspect->suspect_number }}) @endif: {{ $suspect->suspicion_type ?? 'غير محدد' }}</strong>
                        <span style="font-size: 10px; background: {{ $suspect->is_arrested ? '#dc2626' : '#0284c7' }}; padding: 2px 8px; border-radius: 10px;">
                            {{ $suspect->is_arrested ? 'موقوف' : 'قيد المتابعة والمراقبة' }}
                        </span>
                    </div>
                    <table class="info-table" style="margin-bottom: 0;">
                        <tr>
                            <th style="width: 15%;">تاريخ الاشتباه</th>
                            <td style="width: 35%;">{{ $suspect->suspicion_date ? $suspect->suspicion_date->format('Y/m/d') : '-' }}</td>
                            <th style="width: 15%;">مستوى الخطورة</th>
                            <td>{{ $suspect->suspicion_level ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>مكان الاشتباه</th>
                            <td>{{ $suspect->suspicion_location ?? '-' }}</td>
                            <th>مصدر المعلومات</th>
                            <td>{{ $suspect->information_source ?? '-' }}</td>
                        </tr>
                        @if($suspect->suspicion_follower)
                        <tr>
                            <th>المتابع للاشتباه</th>
                            <td colspan="3">{{ $suspect->suspicion_follower }}</td>
                        </tr>
                        @endif
                    </table>

                    @php
                        $suspectHasDetails = $suspect->suspicion_details || $suspect->monitoring_results || $suspect->suspicion_report || $suspect->observations || $suspect->description || $suspect->notes;
                    @endphp
                    @if($suspectHasDetails)
                        <div style="padding: 8px 12px; background: #f0f9ff; border-top: 1px solid #bae6fd;">
                            @if($suspect->suspicion_details || $suspect->description)
                                <div style="margin-bottom: 6px;">
                                    <strong style="color: #0c4a6e; display: block; font-size: 11.5px; margin-bottom: 2px;">تفاصيل الاشتباه:</strong>
                                    <div style="background: #fff; border: 1px solid #bae6fd; padding: 6px 10px; border-radius: 4px; font-size: 11px; white-space: pre-line;">{{ $suspect->suspicion_details ?? $suspect->description }}</div>
                                </div>
                            @endif
                            @if($suspect->monitoring_results)
                                <div style="margin-bottom: 6px;">
                                    <strong style="color: #b45309; display: block; font-size: 11.5px; margin-bottom: 2px;">نتائج المتابعة والرصد:</strong>
                                    <div style="background: #fffbeb; border: 1px solid #fcd34d; padding: 6px 10px; border-radius: 4px; font-size: 11px; white-space: pre-line;">{{ $suspect->monitoring_results }}</div>
                                </div>
                            @endif
                            @if($suspect->suspicion_report)
                                <div style="margin-bottom: 6px;">
                                    <strong style="color: #6d28d9; display: block; font-size: 11.5px; margin-bottom: 2px;">تقرير الاشتباه:</strong>
                                    <div style="background: #f5f3ff; border: 1px solid #ddd6fe; padding: 6px 10px; border-radius: 4px; font-size: 11px; white-space: pre-line;">{{ $suspect->suspicion_report }}</div>
                                </div>
                            @endif
                            @if($suspect->observations || $suspect->notes)
                                <div>
                                    <strong style="color: #374151; display: block; font-size: 11.5px; margin-bottom: 2px;">ملاحظات:</strong>
                                    <div style="background: #fff; border: 1px solid #bae6fd; padding: 6px 10px; border-radius: 4px; font-size: 11px; white-space: pre-line;">{{ $suspect->observations ?? $suspect->notes }}</div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- 8.5. الردود الميدانية --}}
    @if($personalData->fieldResponses && $personalData->fieldResponses->count() > 0)
    <div class="section-card">
        <div class="section-header">
            <i class="fas fa-satellite-dish"></i> سجل الرد الميداني والإفادات ({{ $personalData->fieldResponses->count() }} قيد)
        </div>
        <div class="section-body">
            @foreach($personalData->fieldResponses as $respIndex => $fieldResp)
                <div style="margin-bottom: 15px; border: 1px solid #bae6fd; border-radius: 6px; overflow: hidden; background: #fff;">
                    <div style="background: #0284c7; color: #fff; padding: 6px 12px; display: flex; justify-content: space-between; align-items: center; font-size: 12px;">
                        <strong><i class="fas fa-satellite-dish" style="margin-left: 6px;"></i>رد ميداني رقم {{ $respIndex + 1 }} (#{{ str_pad($fieldResp->id, 6, '0', STR_PAD_LEFT) }})</strong>
                        <span style="font-size: 11px; background: rgba(255,255,255,0.2); padding: 2px 10px; border-radius: 10px;">
                            {{ $fieldResp->response_date ? $fieldResp->response_date->format('Y/m/d') : 'تاريخ غير محدد' }}
                        </span>
                    </div>

                    <table class="info-table" style="margin-bottom: 0;">
                        <tr>
                            <th style="width: 15%;">المصدر الميداني</th>
                            <td style="width: 35%; font-weight: 700;">{{ $fieldResp->source ?: '-' }}</td>
                            <th style="width: 15%;">عمل المصدر</th>
                            <td>{{ $fieldResp->source_job ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th>الجهة المختصة</th>
                            <td><strong style="color: #0284c7;">{{ $fieldResp->entity->name ?? '-' }}</strong></td>
                            <th>المُدخل بواسطة</th>
                            <td>{{ $fieldResp->user->name ?? 'النظام' }}</td>
                        </tr>
                    </table>

                    <div style="padding: 10px 14px; background: #f8fafc; border-top: 1px solid #bae6fd;">
                        <strong style="color: #0f2942; display: block; font-size: 12px; margin-bottom: 4px;">
                            <i class="fas fa-comment-alt" style="margin-left: 4px; color: #0284c7;"></i>مضمون الرد الميداني الكامل:
                        </strong>
                        <div style="background: #fff; border: 1px solid #cbd5e1; padding: 8px 12px; border-radius: 6px; font-size: 12px; white-space: pre-line; line-height: 1.7; color: #0f172a; font-weight: 600;">
                            {{ $fieldResp->field_response }}
                        </div>

                        @if($fieldResp->notes)
                            <div style="margin-top: 8px;">
                                <strong style="color: #475569; display: block; font-size: 11.5px; margin-bottom: 2px;">
                                    <i class="fas fa-sticky-note" style="margin-left: 4px;"></i>ملاحظات:
                                </strong>
                                <div style="background: #fff; border: 1px solid #e2e8f0; padding: 6px 10px; border-radius: 4px; font-size: 11px; white-space: pre-line;">
                                    {{ $fieldResp->notes }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
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
                <div style="margin-bottom: 10px; border-bottom: 1px dashed #bae6fd; padding-bottom: 5px;">
                    <strong style="color: #0284c7;">سجل من صفحة: {{ $record->page->name }}</strong>
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-top: 5px;">
                        @foreach($record->values as $val)
                            <div style="font-size: 11px;">
                                <span style="font-weight: bold; color: #0284c7;">{{ $val->field->label }}:</span>
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
            <i class="fas fa-images"></i> حادي عشر: الوثائق والمرفقات المصورة ({{ $personalData->photos->count() }} وثيقة)
        </div>
        <div class="section-body">
            <div class="attachment-grid">
                @foreach($personalData->photos as $photo)
                    <div class="attachment-item">
                        <img src="{{ Str::startsWith($photo->photo_path, ['http://', 'https://']) ? $photo->photo_path : asset('storage/' . $photo->photo_path) }}" class="attachment-img" alt="وثيقة مرفقة">
                        @if(!empty($photo->photo_title) || !empty($photo->title))
                            <div style="font-size: 10px; margin-top: 4px; font-weight: bold; color: #0c4a6e;">{{ $photo->photo_title ?? $photo->title }}</div>
                        @endif
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
        <div style="width: 150px; height: 150px; border: 2px dashed #7dd3fc; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #0284c7; font-size: 10px; background: #f0f9ff;">
            ختم المركز الرسمي
        </div>
        <div style="width: 200px;">
            <p><strong>المصادقة (مدير الإدارة)</strong></p>
            <div style="margin-top:40px; border-bottom: 1px solid #000;"></div>
        </div>
    </div>
</div>

{{-- أزرار التحكم - لا تظهر في الطباعة --}}
<div class="no-print" style="position: fixed; bottom: 30px; left: 30px; display: flex; gap: 10px; z-index: 9999;">
    <button onclick="window.print()" style="padding: 12px 25px; background: #1e3a8a; color: #fff; border: none; border-radius: 50px; cursor: pointer; display: flex; align-items: center; box-shadow: 0 4px 15px rgba(0,0,0,0.2); font-weight: bold;">
        <i class="fas fa-print" style="margin-left: 10px;"></i> طباعة السجل الشامل
    </button>
    <a href="{{ route('system.personal-data.print', $personalData) }}" target="_blank" style="padding: 12px 25px; background: #0284c7; color: #fff; text-decoration: none; border-radius: 50px; cursor: pointer; display: flex; align-items: center; box-shadow: 0 4px 15px rgba(0,0,0,0.2); font-weight: bold;">
        <i class="fas fa-id-card" style="margin-left: 10px;"></i> طباعة البطاقة الأساسية
    </a>
    <button onclick="window.close()" style="padding: 12px 25px; background: #ef4444; color: #fff; border: none; border-radius: 50px; cursor: pointer; display: flex; align-items: center; box-shadow: 0 4px 15px rgba(0,0,0,0.2); font-weight: bold;">
        <i class="fas fa-times" style="margin-left: 10px;"></i> إغلاق
    </button>
</div>
@endsection