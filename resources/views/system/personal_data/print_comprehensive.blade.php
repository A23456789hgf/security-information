@extends('layouts.print')

@section('title', 'تقرير الملف الشخصي الشامل - ' . ($personalData->name ?? 'بيانات'))

@section('styles')
<style>
    @font-face { font-family: 'Cairo'; font-display: swap; src: local('Cairo'), local('Segoe UI'); }
    @font-face { font-family: 'Tajawal'; font-display: swap; src: local('Tajawal'), local('Segoe UI'); }
    
    @page { size: A4 portrait; margin: 0; }
    
    body {
        margin: 0;
        padding: 0;
        background-color: #f1f5f9;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    .printable-area {
        width: 210mm;
        min-height: 297mm;
        padding: 14mm 15mm;
        margin: 0 auto;
        background: white;
        box-sizing: border-box;
        font-family: 'Cairo', 'Tajawal', sans-serif;
        direction: rtl;
        position: relative;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    
    @media print {
        body { background-color: white; }
        .printable-area {
            box-shadow: none;
            width: 100%;
            height: auto;
            min-height: auto;
            padding: 0;
            margin: 0;
        }
        .no-print { display: none !important; }
        .page-break { page-break-before: always; }
    }

    /* Custom Table Styles matching React prototype */
    .card-section {
        border: 1.5px solid #cbd5e1;
        border-radius: 0.5rem;
        overflow: hidden;
        margin-bottom: 10px;
        background: white;
        page-break-inside: avoid;
    }
    .card-header {
        background-color: #f0f4f8;
        border-bottom: 1.5px solid #cbd5e1;
        padding: 6px 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .card-header-title {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 800;
        color: #0f2942;
    }
    .card-header-title i {
        color: #dc2626;
        font-size: 12px;
        width: 16px;
        text-align: center;
    }
    .card-badge {
        font-size: 9px;
        font-weight: 700;
        color: #475569;
        background-color: #ffffff;
        padding: 2px 6px;
        border-radius: 4px;
        border: 1px solid #cbd5e1;
    }
    
    .card-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 10.5px;
    }
    .card-table th {
        background-color: #f8fafc;
        color: #475569;
        font-weight: 700;
        padding: 6px 8px;
        text-align: right;
        border-left: 1px solid #e2e8f0;
        border-bottom: 1px solid #e2e8f0;
    }
    .card-table td {
        padding: 6px 8px;
        color: #1e293b;
        font-weight: 600;
        border-bottom: 1px solid #e2e8f0;
        border-left: 1px solid #e2e8f0;
    }
    .card-table th:last-child, .card-table td:last-child { border-left: none; }
    .card-table tr:last-child th, .card-table tr:last-child td { border-bottom: none; }
    
    .card-table.table-striped tbody tr:nth-child(even) td {
        background-color: #f8fafc;
    }
    .card-table.table-center th, .card-table.table-center td {
        text-align: center;
    }

    /* Watermark */
    .watermark-element {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0.045;
        z-index: 0;
        pointer-events: none;
        overflow: hidden;
    }
    .watermark-text {
        font-weight: 900;
        color: #0f172a;
        font-size: 62pt;
        transform: rotate(-32deg);
        letter-spacing: 4px;
        white-space: nowrap;
    }
</style>
@endsection

@section('content')

<div class="printable-area">
    <div class="watermark-element">
        <span class="watermark-text">وثيقة رسمية معتمدة</span>
    </div>

    <div class="relative z-10 flex flex-col justify-between" style="min-height: 273mm;">
        <div>
            {{-- 1. Official Header --}}
            <header class="grid items-center pb-2.5 mb-3 relative" style="grid-template-columns: 1.2fr 1.6fr 1.2fr; border-bottom: 2.5px solid #0f2942;">
                <div class="absolute -bottom-[2.5px] right-0 h-[2.5px]" style="width: 38%; background-color: #dc2626;"></div>

                <div class="text-right pl-2 border-l border-dashed border-slate-300">
                    <h1 class="text-[14px] font-extrabold m-0 leading-tight" style="color: #0f2942;">الجمهورية اليمنية</h1>
                    <h2 class="text-[12.5px] font-bold text-slate-700 m-0 my-0.5">وزارة الدفاع - التعبئة العامة</h2>
                    <p class="text-[10.5px] font-semibold text-slate-500 m-0">نظام إدارة السجلات والقوى البشرية</p>
                </div>

                <div class="text-center px-2">
                    <div class="flex flex-col items-center justify-center mb-0.5">
                        <svg viewBox="0 0 100 100" class="w-10 h-10 transition-colors" style="fill: #0f2942;">
                            <path d="M50 12 C52 14 55 18 56 22 C64 24 74 27 86 33 C78 44 68 53 50 82 C32 53 22 44 14 33 C26 27 36 24 44 22 C45 18 48 14 50 12 Z" fill="#0f2942" opacity="0.12"/>
                            <path d="M50 16 L53 24 L61 24 L55 29 L57 37 L50 32 L43 37 L45 29 L39 24 L47 24 Z" fill="#0f2942"/>
                            <path d="M20 34 C30 31 40 28 50 28 C60 28 70 31 80 34 C72 45 62 55 50 78 C38 55 28 45 20 34 Z" fill="none" stroke="#0f2942" stroke-width="2.2"/>
                            <circle cx="50" cy="46" r="10" fill="none" stroke="#dc2626" stroke-width="1.8"/>
                            <path d="M46 44 L50 49 L54 44" fill="none" stroke="#0f2942" stroke-width="1.8"/>
                        </svg>
                    </div>
                    <h2 class="text-[17px] font-black tracking-tight m-0 leading-tight" style="color: #0f2942;">الملف المعلوماتي الشامل</h2>
                    <div class="inline-block mt-0.5">
                        <span class="text-[9.5px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                            وثيقة قيد وسجل بيانات شخصية • سري للغاية
                        </span>
                    </div>
                </div>

                <div class="text-left pr-2 border-r border-dashed border-slate-300 flex justify-end">
                    <div class="bg-slate-50 rounded p-1.5 text-right text-[10px] w-full max-w-[170px]" style="border: 1.5px solid #cbd5e1;">
                        <div class="flex justify-between items-center mb-0.5 text-slate-600">
                            <span>الرقم المرجعي:</span>
                            <strong class="font-mono text-slate-900 font-bold">#{{ str_pad($personalData->id, 6, '0', STR_PAD_LEFT) }}</strong>
                        </div>
                        <div class="flex justify-between items-center mb-0.5 text-slate-600">
                            <span>تاريخ الإصدار:</span>
                            <span class="font-mono">{{ now()->format('Y/m/d') }}</span>
                        </div>
                        <div class="flex justify-between items-center mb-0.5 text-slate-600">
                            <span>وقت التصدير:</span>
                            <span class="font-mono">{{ now()->format('H:i') }}</span>
                        </div>
                        <div class="mt-1 pt-1 border-t border-dashed border-slate-300 flex flex-col items-center">
                            <span class="font-mono text-[9px] text-slate-500 tracking-wider">
                                {{ str_pad($personalData->military_number ?? $personalData->id, 10, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>
                    </div>
                </div>
            </header>

            {{-- 2. Top Grid: Identity Info + Photo Card --}}
            <div class="grid gap-2.5 mb-2.5 items-stretch" style="grid-template-columns: 1fr 175px;">
                <div class="card-section m-0 flex flex-col justify-between">
                    <div class="card-header">
                        <div class="card-header-title">
                            <i class="fas fa-id-card"></i>
                            <span>البيانات التعريفية الأساسية</span>
                        </div>
                        <span class="card-badge">السجل المدني والعسكري</span>
                    </div>
                    <table class="card-table">
                        <tr>
                            <th style="width: 22%;">الاسم الرباعي واللقب</th>
                            <td colspan="3" class="font-bold text-[12.5px]">{{ $personalData->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th style="width: 22%;">الرقم الوطني / الهوية</th>
                            <td class="font-mono font-bold w-[28%]">{{ $personalData->national_id_number ?? '-' }}</td>
                            <th style="width: 22%;">الرقم العسكري</th>
                            <td class="font-mono font-bold text-[12px] w-[28%]" style="color: #dc2626;">{{ $personalData->military_number ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>تاريخ الميلاد (ميلادي)</th>
                            <td class="font-mono">{{ $personalData->date_of_birth_gregorian ? $personalData->date_of_birth_gregorian->format('Y/m/d') : '-' }}</td>
                            <th>تاريخ الميلاد (هجري)</th>
                            <td>{{ $personalData->date_of_birth_hijri ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>الجنسية</th>
                            <td>{{ $personalData->placeOfBirthCountry->name ?? 'يمني' }}</td>
                            <th>الديانة</th>
                            <td>مسلم</td>
                        </tr>
                        <tr>
                            <th>الاسم الجهادي</th>
                            <td>{{ $personalData->jihadi_name ?? '-' }}</td>
                            <th>الرتبة</th>
                            <td style="color: #dc2626; font-weight: 800;">{{ $personalData->rank->rank ?? '-' }}</td>
                        </tr>
                    </table>
                </div>

                <div class="bg-slate-50 rounded-lg p-2.5 text-center flex flex-col items-center justify-center" style="border: 1.5px solid #cbd5e1;">
                    <div class="w-24 h-28 mb-1.5 rounded overflow-hidden bg-slate-200 flex items-center justify-center" style="border: 2px solid #0f2942; box-shadow: 0 1px 2px rgba(0,0,0,0.08);">
                        @if($personalData->main_profile_photo)
                            <img src="{{ public_path('storage/' . $personalData->main_profile_photo) }}" alt="{{ $personalData->name }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-user-shield text-slate-400 text-4xl"></i>
                        @endif
                    </div>
                    <h3 class="text-[13px] font-extrabold text-slate-900 m-0 leading-tight">{{ $personalData->name ?? 'بدون اسم' }}</h3>
                    <div class="mt-1 inline-block text-[11px] font-bold px-2 py-0.5 rounded-full" style="color: #dc2626; background-color: #fee2e2; border: 1px solid #fecaca;">
                        {{ $personalData->rank->rank ?? 'بدون رتبة' }}
                    </div>
                    @if($personalData->jihadi_name)
                    <div class="mt-1 bg-slate-200/90 text-slate-700 text-[10px] font-semibold px-2 py-0.5 rounded flex items-center gap-1">
                        <i class="fas fa-tag text-[9px]"></i>
                        <span>{{ $personalData->jihadi_name }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- 3. Second Row: Job/Military + Medical/Social --}}
            <div class="grid grid-cols-2 gap-2.5 mb-2.5">
                <div class="card-section m-0">
                    <div class="card-header">
                        <div class="card-header-title"><i class="fas fa-award"></i><span>البيانات الوظيفية والعسكرية</span></div>
                        <span class="card-badge">الوضع الإداري</span>
                    </div>
                    <table class="card-table">
                        <tr>
                            <th style="width: 38%;">الدائرة / المنطقة</th>
                            <td>{{ $personalData->militaryDistrict->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>الشعبة / الوحدة</th>
                            <td>{{ $personalData->division->name ?? '-' }} {{ $personalData->unit ? ' - '.$personalData->unit : '' }}</td>
                        </tr>
                        <tr>
                            <th>جهة العمل</th>
                            <td>{{ $personalData->workplace ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>حالة الوظيفة</th>
                            <td>{{ $personalData->jobStatus->job_status ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>المسمى الوظيفي</th>
                            <td>{{ $personalData->job ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>تاريخ التعيين</th>
                            <td class="font-mono">{{ $personalData->appointment_date ?? '-' }}</td>
                        </tr>
                    </table>
                </div>

                <div class="card-section m-0">
                    <div class="card-header">
                        <div class="card-header-title"><i class="fas fa-heartbeat"></i><span>البيانات الطبية والاجتماعية</span></div>
                        <span class="card-badge">الحالة والمؤهل</span>
                    </div>
                    <table class="card-table">
                        <tr>
                            <th style="width: 38%;">فصيلة الدم</th>
                            <td>
                                <span class="inline-block bg-red-100 text-red-700 font-mono font-extrabold text-[12px] px-2 py-[1px] rounded border border-red-300">
                                    {{ $personalData->bloodType->type ?? '-' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>الحالة الاجتماعية</th>
                            <td>{{ $personalData->maritalStatus->status ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>عدد الأبناء</th>
                            <td class="font-bold font-mono">
                                @if(isset($personalData->secondaryData))
                                    {{ $personalData->secondaryData->total_children_count ?? (($personalData->secondaryData->male_children_count ?? 0) + ($personalData->secondaryData->female_children_count ?? 0)) }}
                                @else
                                    {{ $personalData->number_of_children ?? '0' }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>المؤهل العلمي</th>
                            <td>{{ $personalData->qualification->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>التخصص</th>
                            <td>{{ $personalData->specialization ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>الحالة الصحية</th>
                            <td>{{ $personalData->secondaryData->healthStatus->name ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- 4. Third Row: Address & Place of Birth --}}
            <div class="grid grid-cols-2 gap-2.5 mb-2.5">
                <div class="card-section m-0">
                    <div class="card-header">
                        <div class="card-header-title"><i class="fas fa-home"></i><span>بيانات السكن والإقامة الحالية</span></div>
                    </div>
                    <table class="card-table">
                        <tr>
                            <th style="width: 38%;">المحافظة</th>
                            <td>{{ $personalData->governorate->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>المديرية</th>
                            <td>{{ $personalData->directorate->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>العزلة / المنطقة</th>
                            <td>{{ $personalData->subArea->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>القرية / الشارع</th>
                            <td>{{ $personalData->village->name ?? '-' }}</td>
                        </tr>
                    </table>
                </div>

                <div class="card-section m-0">
                    <div class="card-header">
                        <div class="card-header-title"><i class="fas fa-map-marker-alt"></i><span>مكان الميلاد والنشأة</span></div>
                    </div>
                    <table class="card-table">
                        <tr>
                            <th style="width: 38%;">الدولة</th>
                            <td>{{ $personalData->placeOfBirthCountry->name ?? 'اليمن' }}</td>
                        </tr>
                        <tr>
                            <th>المحافظة</th>
                            <td>{{ $personalData->placeOfBirthGovernorate->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>المديرية</th>
                            <td>{{ $personalData->placeOfBirthDirectorate->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>القرية / الحي</th>
                            <td>{{ $personalData->placeOfBirthVillage->name ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- 5. Secondary Data & Jihadist Record --}}
            @if($personalData->secondaryData)
            <div class="card-section">
                <div class="card-header">
                    <div class="card-header-title"><i class="fas fa-history"></i><span>السجل الجهادي والبيانات الثانوية</span></div>
                </div>
                <table class="card-table" style="border-bottom: 1.5px solid #cbd5e1;">
                    <tr>
                        <th style="width: 25%;">تاريخ البدء / الانطلاقة</th>
                        <td style="width: 25%;">{{ $personalData->secondaryData->start_date ? $personalData->secondaryData->start_date->format('Y/m/d') : '-' }}</td>
                        <th style="width: 25%;">المسؤول المباشر</th>
                        <td style="width: 25%;">{{ $personalData->secondaryData->direct_supervisor ?? '-' }}</td>
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

                @if($personalData->secondaryData->affiliations && $personalData->secondaryData->affiliations->count() > 0)
                <div class="p-2 bg-slate-50 font-bold text-[11px] text-slate-700 border-b border-slate-200">سجل الانتماء السياسي والعقائدي</div>
                <table class="card-table table-center table-striped" style="border-bottom: 1.5px solid #cbd5e1;">
                    <thead>
                        <tr>
                            <th>الجهة</th>
                            <th>العقائدي</th>
                            <th>السياسي</th>
                            <th>الارتباط</th>
                            <th>المسؤول</th>
                            <th>الترك</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($personalData->secondaryData->affiliations as $aff)
                        <tr>
                            <td>{{ $aff->party ?? '-' }}</td>
                            <td>{{ $aff->ideologicalAffiliation->name ?? '-' }}</td>
                            <td>{{ $aff->politicalAffiliation->name ?? '-' }}</td>
                            <td>{{ $aff->affiliation_date ? $aff->affiliation_date->format('Y/m/d') : '-' }}</td>
                            <td>{{ $aff->direct_supervisor ?? '-' }}</td>
                            <td>{{ $aff->leaving_date ? $aff->leaving_date->format('Y/m/d') : '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif

                @if($personalData->secondaryData->previousJihadistActions && $personalData->secondaryData->previousJihadistActions->count() > 0)
                <div class="p-2 bg-slate-50 font-bold text-[11px] text-slate-700 border-b border-slate-200">الأعمال الجهادية السابقة</div>
                <table class="card-table table-center table-striped" style="border-bottom: 1.5px solid #cbd5e1;">
                    <thead>
                        <tr>
                            <th>النشاط</th>
                            <th>التاريخ</th>
                            <th>المدة</th>
                            <th>المكان</th>
                            <th>المسؤول المباشر</th>
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
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
                
                @if($personalData->secondaryData->weapons && $personalData->secondaryData->weapons->count() > 0)
                <div class="p-2 bg-slate-50 font-bold text-[11px] text-slate-700 border-b border-slate-200">سجل العهد (الأسلحة)</div>
                <table class="card-table table-center table-striped">
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
            @endif

            {{-- 6. Security Files --}}
            @if($personalData->securityFiles && $personalData->securityFiles->count() > 0)
            <div class="card-section">
                <div class="card-header">
                    <div class="card-header-title"><i class="fas fa-shield-alt"></i><span>سجل الملف الأمني السري</span></div>
                </div>
                <div class="p-2">
                    @foreach($personalData->securityFiles as $file)
                        <div class="bg-slate-50 border border-slate-200 rounded p-2 mb-2 last:mb-0">
                            <table class="card-table mb-2 bg-white rounded border border-slate-200">
                                <tr>
                                    <th style="width: 15%;">رقم الملف</th>
                                    <td style="width: 35%;" class="font-bold text-red-700">#{{ $file->file_number ?? str_pad($file->id, 5, '0', STR_PAD_LEFT) }}</td>
                                    <th style="width: 15%;">تاريخ الملف</th>
                                    <td>{{ $file->file_date_gregorian ? $file->file_date_gregorian->format('Y/m/d') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>الجهة المعدة</th>
                                    <td>{{ $file->preparingEntity->name ?? '-' }}</td>
                                    <th>المعد</th>
                                    <td>{{ $file->file_preparer ?? '-' }}</td>
                                </tr>
                            </table>
                            @if($file->highlight_summary)
                                <div class="text-[11px] font-bold text-slate-800 mb-1">أبرز البيانات الملخصة:</div>
                                <div class="bg-white border border-slate-200 p-2 rounded text-[10.5px] leading-relaxed whitespace-pre-line mb-2">{{ $file->highlight_summary }}</div>
                            @endif
                            @if($file->jihadist_status)
                                <div class="text-[11px] font-bold text-slate-800 mb-1">بيانات الوضع الجهادي:</div>
                                <div class="bg-white border border-slate-200 p-2 rounded text-[10.5px] leading-relaxed whitespace-pre-line">{{ $file->jihadist_status }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- 7. Wives --}}
            @if($personalData->wives && $personalData->wives->count() > 0)
            <div class="card-section">
                <div class="card-header">
                    <div class="card-header-title"><i class="fas fa-heart"></i><span>الحالة الاجتماعية (الزوجات)</span></div>
                </div>
                <table class="card-table table-center table-striped">
                    <thead>
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th>الاسم</th>
                            <th>رقم الهوية</th>
                            <th>تاريخ الميلاد</th>
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
                            <td>{{ $wife->governorate->name ?? '' }} - {{ $wife->directorate->name ?? '' }}</td>
                            <td>{{ $wife->status ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            {{-- 8. Relatives --}}
            @if(($personalData->relatives && $personalData->relatives->count() > 0) || ($personalData->enemyAreaRelatives && $personalData->enemyAreaRelatives->count() > 0) || ($personalData->travelingRelatives && $personalData->travelingRelatives->count() > 0))
            <div class="card-section">
                <div class="card-header">
                    <div class="card-header-title"><i class="fas fa-users"></i><span>بيانات الأهل والأقارب</span></div>
                </div>
                
                @if($personalData->relatives && $personalData->relatives->count() > 0)
                <div class="p-2 bg-slate-50 font-bold text-[11px] text-slate-700 border-b border-slate-200">الأقارب (العامة)</div>
                <table class="card-table table-center table-striped" style="border-bottom: 1.5px solid #cbd5e1;">
                    <thead>
                        <tr>
                            <th>الاسم الكامل</th>
                            <th>القرابة</th>
                            <th>العمر</th>
                            <th>العمل الحالي</th>
                            <th>السياسي</th>
                            <th>العقائدي</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($personalData->relatives as $relative)
                        <tr>
                            <td class="font-bold">{{ $relative->full_name }}</td>
                            <td>{{ $relative->kinship->name ?? '-' }}</td>
                            <td>{{ $relative->age ? $relative->age . ' سنة' : '-' }}</td>
                            <td>{{ $relative->current_job ?? '-' }}</td>
                            <td>{{ $relative->politicalAffiliation->name ?? '-' }}</td>
                            <td>{{ $relative->ideologicalAffiliation->name ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif

                @if($personalData->enemyAreaRelatives && $personalData->enemyAreaRelatives->count() > 0)
                <div class="p-2 bg-slate-50 font-bold text-[11px] text-slate-700 border-b border-slate-200">الأقارب في مناطق العدو</div>
                <table class="card-table table-center table-striped" style="border-bottom: 1.5px solid #cbd5e1;">
                    <thead>
                        <tr>
                            <th>الاسم الكامل</th>
                            <th>القرابة</th>
                            <th>السكن</th>
                            <th>العمل</th>
                            <th>السياسي</th>
                            <th>التواصل</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($personalData->enemyAreaRelatives as $er)
                        <tr>
                            <td class="font-bold">{{ $er->full_name }}</td>
                            <td>{{ $er->kinship->name ?? '-' }}</td>
                            <td>{{ $er->residence_place ?? '-' }}</td>
                            <td>{{ $er->job ?? '-' }}</td>
                            <td>{{ $er->politicalAffiliation->name ?? '-' }}</td>
                            <td>{{ $er->communication_method ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif

                @if($personalData->travelingRelatives && $personalData->travelingRelatives->count() > 0)
                <div class="p-2 bg-slate-50 font-bold text-[11px] text-slate-700 border-b border-slate-200">الأقارب المسافرين</div>
                <table class="card-table table-center table-striped">
                    <thead>
                        <tr>
                            <th>الاسم الكامل</th>
                            <th>القرابة</th>
                            <th>الدولة</th>
                            <th>سبب السفر</th>
                            <th>العمل</th>
                            <th>السياسي</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($personalData->travelingRelatives as $tr)
                        <tr>
                            <td class="font-bold">{{ $tr->full_name }}</td>
                            <td>{{ $tr->kinship->name ?? '-' }}</td>
                            <td>{{ $tr->country->name ?? '-' }}</td>
                            <td>{{ $tr->travel_reason ?? '-' }}</td>
                            <td>{{ $tr->current_job ?? '-' }}</td>
                            <td>{{ $tr->politicalAffiliation->name ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
            @endif

            {{-- 9. Courses --}}
            @if($personalData->courses && $personalData->courses->count() > 0)
            <div class="card-section">
                <div class="card-header">
                    <div class="card-header-title"><i class="fas fa-graduation-cap"></i><span>سجل التأهيل والدورات</span></div>
                </div>
                <table class="card-table table-center table-striped">
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
            @endif

            {{-- 10. Arrests --}}
            @if($personalData->arrests && $personalData->arrests->count() > 0)
            <div class="card-section">
                <div class="card-header">
                    <div class="card-header-title"><i class="fas fa-gavel"></i><span>سجل القضايا والتوقيفات</span></div>
                </div>
                <table class="card-table table-center table-striped">
                    <thead>
                        <tr>
                            <th>التاريخ</th>
                            <th>الجهة</th>
                            <th>القضية</th>
                            <th>الحالة</th>
                            <th>الإطلاق</th>
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
            @endif

            {{-- 11. Security Reports & Suspects --}}
            @if(($securityReports && $securityReports->count() > 0) || ($personalData->suspects && $personalData->suspects->count() > 0))
            <div class="card-section">
                <div class="card-header">
                    <div class="card-header-title"><i class="fas fa-exclamation-triangle"></i><span>البلاغات الأمنية وسجل الاشتباه</span></div>
                </div>
                
                @if($securityReports && $securityReports->count() > 0)
                <div class="p-2 bg-slate-50 font-bold text-[11px] text-slate-700 border-b border-slate-200">البلاغات الأمنية</div>
                <table class="card-table table-center table-striped" style="border-bottom: 1.5px solid #cbd5e1;">
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
                @endif

                @if($personalData->suspects && $personalData->suspects->count() > 0)
                <div class="p-2 bg-slate-50 font-bold text-[11px] text-slate-700 border-b border-slate-200">سجل الاشتباه</div>
                <table class="card-table table-center table-striped">
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
                @endif
            </div>
            @endif

            {{-- 12. Dynamic Records --}}
            @if($dynamicRecords && $dynamicRecords->count() > 0)
            <div class="card-section">
                <div class="card-header">
                    <div class="card-header-title"><i class="fas fa-database"></i><span>البيانات والسجلات الإضافية</span></div>
                </div>
                <div class="p-2">
                    @foreach($dynamicRecords as $record)
                        <div class="mb-2 pb-2 border-b border-dashed border-slate-300 last:border-0 last:mb-0 last:pb-0">
                            <strong class="text-[#0f2942] text-[11px]">سجل من صفحة: {{ $record->page->name }}</strong>
                            <div class="grid grid-cols-3 gap-2 mt-1">
                                @foreach($record->values as $val)
                                    <div class="text-[10.5px]">
                                        <span class="font-bold text-slate-600">{{ $val->field->label }}:</span>
                                        <span class="text-slate-900">{{ $val->value }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Notes --}}
            @if($personalData->notes)
            <div class="card-section">
                <div class="card-header">
                    <div class="card-header-title"><i class="fas fa-sticky-note"></i><span>ملاحظات إدارية وتدقيق القيد</span></div>
                </div>
                <div class="p-2 text-[10.5px] text-slate-700 leading-relaxed bg-white whitespace-pre-line">
                    {{ $personalData->notes }}
                </div>
            </div>
            @endif

            {{-- Signatures --}}
            <div class="grid grid-cols-3 gap-2.5 my-2 break-inside-avoid">
                <div class="border border-slate-300 rounded p-2 text-center bg-white min-h-[75px] flex flex-col justify-between">
                    <div class="text-[10.5px] font-extrabold pb-1 border-b border-dashed border-slate-300" style="color: #0f2942;">إعداد وتجهيز المختص</div>
                    <div class="text-[9.5px] text-slate-500">الاسم: {{ auth()->user()->name ?? '.............................' }}</div>
                    <div class="text-[9.5px] text-slate-500">التوقيع: ...........................</div>
                </div>
                <div class="border border-slate-300 rounded p-2 text-center bg-white min-h-[75px] flex flex-col justify-between">
                    <div class="text-[10.5px] font-extrabold pb-1 border-b border-dashed border-slate-300" style="color: #0f2942;">تدقيق شؤون القوى البشرية</div>
                    <div class="text-[9.5px] text-slate-500">الاسم: .............................</div>
                    <div class="text-[9.5px] text-slate-500">التوقيع: ...........................</div>
                </div>
                <div class="border border-slate-300 rounded p-2 text-center bg-white min-h-[75px] flex flex-col justify-between relative">
                    <div class="text-[10.5px] font-extrabold pb-1 border-b border-dashed border-slate-300" style="color: #0f2942;">اعتماد مدير الدائرة / الختم</div>
                    <div class="absolute left-2 bottom-1 w-14 h-14 rounded-full border-2 border-dashed flex flex-col items-center justify-center pointer-events-none" style="border-color: #dc2626; color: #dc2626; transform: rotate(-10deg); opacity: 0.85;">
                        <span class="text-[6.5px] font-black">الختم المعتمد</span>
                        <i class="fas fa-certificate text-[11px] my-0.5"></i>
                        <span class="text-[5.5px] font-bold">وحدة المعلومات</span>
                    </div>
                    <div class="text-[9.5px] text-slate-500">التوقيع والختم: .................</div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <footer class="pt-2 mt-2 flex items-center justify-between text-[9px] text-slate-500 border-t" style="border-color: #cbd5e1;">
            <div class="flex items-center gap-2">
                <span>طُبع بواسطة المشغل: <strong class="text-slate-800">{{ auth()->user()->name ?? 'النظام الآلي الموحد' }}</strong></span>
                <span>|</span>
                <span>تاريخ الطباعة: {{ now()->format('Y-m-d H:i') }}</span>
            </div>
            <div class="flex items-center gap-2">
                <span>نظام إدارة السجلات والقوى البشرية</span>
                <span>•</span>
                <span class="font-bold text-slate-700">سري للغاية</span>
                <div class="inline-flex items-center gap-1 bg-slate-100 p-0.5 px-1 rounded border border-slate-200">
                    <i class="fas fa-qrcode text-[10px] text-slate-700"></i>
                    <span class="text-[7.5px] font-mono">VRF-{{ str_pad($personalData->id, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
            </div>
        </footer>
    </div>
</div>

{{-- Control Buttons (No Print) --}}
<div class="no-print" style="position: fixed; bottom: 30px; left: 30px; display: flex; gap: 10px; z-index: 100;">
    <button onclick="window.print()" class="hover:bg-blue-800 transition-colors" style="padding: 12px 25px; background: #1e3a8a; color: #fff; border: none; border-radius: 50px; cursor: pointer; display: flex; align-items: center; box-shadow: 0 4px 15px rgba(0,0,0,0.2); font-weight: bold;">
        <i class="fas fa-print ml-2"></i> تنفيذ الطباعة
    </button>
    <button onclick="window.close()" class="hover:bg-red-600 transition-colors" style="padding: 12px 25px; background: #ef4444; color: #fff; border: none; border-radius: 50px; cursor: pointer; display: flex; align-items: center; box-shadow: 0 4px 15px rgba(0,0,0,0.2); font-weight: bold;">
        <i class="fas fa-times ml-2"></i> إغلاق
    </button>
</div>
@endsection
