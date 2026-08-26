@extends('layouts.app')

@section('title', 'عرض تفاصيل البيانات الثانوية')

@section('content')
<div class="container-fluid mx-auto px-4 py-6" dir="rtl">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 max-w-7xl mx-auto">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-blue-600 rounded-2xl shadow-md flex items-center justify-center text-white">
                <i class="fas fa-id-card text-lg"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-800 header-font mb-0.5">تفاصيل السجل الثانوي</h2>
                <p class="text-slate-400 text-xs">عرض متكامل للبيانات الثانوية، الحالة الصحية، الأولاد، الانتماءات والأسلحة.</p>
            </div>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('system.secondary-data.index') }}"
                class="px-4 py-2 bg-white border border-slate-200 text-slate-600 font-bold text-xs rounded-xl hover:bg-slate-50 transition-all shadow-sm flex items-center gap-1.5">
                <i class="fas fa-arrow-right"></i> عودة للقائمة
            </a>
            <a href="{{ route('system.secondary-data.edit', $secondaryData) }}"
                class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs rounded-xl shadow-sm transition-all flex items-center gap-1.5">
                <i class="fas fa-edit text-xs"></i> تعديل السجل
            </a>
            @if($secondaryData->personalData)
                <a href="{{ route('system.personal-data.print-all', $secondaryData->personalData) }}" target="_blank"
                    class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs rounded-xl shadow-sm transition-all flex items-center gap-1.5">
                    <i class="fas fa-print text-xs"></i> طباعة الملف الشامل
                </a>
            @endif
        </div>
    </div>

    <div class="max-w-7xl mx-auto space-y-6">

        {{-- Top Summary Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
                {{-- Person Basic Info --}}
                <div class="lg:col-span-5 border-b lg:border-b-0 lg:border-l border-slate-100 pb-4 lg:pb-0 lg:pl-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center font-bold text-2xl border border-blue-100">
                            {{ mb_substr($secondaryData->personalData->name ?? 'ش', 0, 1) }}
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">{{ $secondaryData->personalData->name ?? 'غير محدد' }}</h3>
                            <div class="flex flex-wrap items-center gap-2 mt-1 text-xs text-slate-500">
                                @if($secondaryData->personalData?->jihadi_name)
                                    <span class="bg-slate-100 px-2 py-0.5 rounded text-slate-600 font-semibold">
                                        <i class="fas fa-user-tag text-blue-500 ml-1"></i> {{ $secondaryData->personalData->jihadi_name }}
                                    </span>
                                @endif
                                @if($secondaryData->personalData?->military_number)
                                    <span class="bg-slate-100 px-2 py-0.5 rounded text-slate-600 font-semibold">
                                        <i class="fas fa-id-badge text-slate-400 ml-1"></i> {{ $secondaryData->personalData->military_number }}
                                    </span>
                                @endif
                                @if($secondaryData->personalData?->bloodType)
                                    <span class="bg-rose-50 px-2 py-0.5 rounded text-rose-600 font-extrabold">
                                        <i class="fas fa-tint ml-1"></i> {{ $secondaryData->personalData->bloodType->type }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Key Stats (Health & Children) --}}
                <div class="lg:col-span-7 grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div class="bg-teal-50/60 border border-teal-100 p-3 rounded-xl text-center">
                        <span class="text-[11px] font-bold text-teal-600 block mb-1">الحالة الصحية</span>
                        <span class="text-sm font-extrabold text-teal-800">{{ $secondaryData->healthStatus->name ?? 'غير محددة' }}</span>
                    </div>
                    <div class="bg-blue-50/60 border border-blue-100 p-3 rounded-xl text-center">
                        <span class="text-[11px] font-bold text-blue-600 block mb-1">الذكور</span>
                        <span class="text-base font-extrabold text-blue-800">{{ $secondaryData->male_children_count ?? 0 }}</span>
                    </div>
                    <div class="bg-pink-50/60 border border-pink-100 p-3 rounded-xl text-center">
                        <span class="text-[11px] font-bold text-pink-600 block mb-1">الإناث</span>
                        <span class="text-base font-extrabold text-pink-800">{{ $secondaryData->female_children_count ?? 0 }}</span>
                    </div>
                    <div class="bg-purple-50/60 border border-purple-100 p-3 rounded-xl text-center">
                        <span class="text-[11px] font-bold text-purple-600 block mb-1">إجمالي الأولاد</span>
                        <span class="text-base font-extrabold text-purple-800">{{ $secondaryData->total_children_count ?? (($secondaryData->male_children_count ?? 0) + ($secondaryData->female_children_count ?? 0)) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- General Jihadist Info --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <h5 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
                <span class="w-1.5 h-4 bg-amber-500 rounded-full"></span>
                البيانات والمهام العامة
            </h5>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
                <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <span class="text-slate-400 block text-[11px] mb-1">تاريخ الانطلاقة / البدء:</span>
                    <span class="font-bold text-slate-700">{{ $secondaryData->start_date ? $secondaryData->start_date->format('Y-m-d') : '-' }}</span>
                </div>
                <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <span class="text-slate-400 block text-[11px] mb-1">المسؤول المباشر:</span>
                    <span class="font-bold text-slate-700">{{ $secondaryData->direct_supervisor ?? '-' }}</span>
                </div>
                <div class="bg-slate-50 p-3 rounded-xl border border-slate-100 md:col-span-2">
                    <span class="text-slate-400 block text-[11px] mb-1">النشاط الجهادي الحالي:</span>
                    <span class="font-bold text-slate-700">{{ $secondaryData->current_jihadist_activity ?? '-' }}</span>
                </div>
                @if($secondaryData->previous_jihadist_activity)
                <div class="bg-slate-50 p-3 rounded-xl border border-slate-100 md:col-span-2 lg:col-span-4">
                    <span class="text-slate-400 block text-[11px] mb-1">ملاحظات النشاط السابق:</span>
                    <span class="font-medium text-slate-700">{{ $secondaryData->previous_jihadist_activity }}</span>
                </div>
                @endif
            </div>
        </div>

        {{-- 1. Political & Ideological Affiliations Table --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
                <h5 class="text-base font-bold text-slate-800 header-font flex items-center gap-2">
                    <span class="w-1.5 h-5 bg-indigo-600 rounded-full"></span>
                    الانتماء السياسي والعقائدي
                    <span class="bg-indigo-100 text-indigo-700 text-xs px-2 py-0.5 rounded-full font-bold">
                        {{ $secondaryData->affiliations->count() }} سجل
                    </span>
                </h5>
            </div>
            <div class="p-0">
                @if($secondaryData->affiliations->isEmpty())
                    <div class="py-8 text-center text-slate-400 text-xs font-medium">
                        <i class="fas fa-info-circle text-slate-300 ml-1"></i> لا توجد سجلات انتماء سياسي أو عقائدي مسجلة.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-right text-xs">
                            <thead class="bg-slate-50/70 text-[11px] font-bold text-slate-500 border-b border-slate-100">
                                <tr>
                                    <th class="px-4 py-3">الجهة</th>
                                    <th class="px-4 py-3">الانتماء العقائدي</th>
                                    <th class="px-4 py-3">الانتماء السياسي</th>
                                    <th class="px-4 py-3">تاريخ الارتباط</th>
                                    <th class="px-4 py-3">مدة الانتماء</th>
                                    <th class="px-4 py-3">مكان العمل</th>
                                    <th class="px-4 py-3">نوع العمل</th>
                                    <th class="px-4 py-3">المسؤول المباشر</th>
                                    <th class="px-4 py-3">تاريخ الترك</th>
                                    <th class="px-4 py-3">سبب الترك</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($secondaryData->affiliations as $aff)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-4 py-3 font-bold text-slate-700">{{ $aff->party ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        @if($aff->ideologicalAffiliation)
                                            <span class="bg-indigo-50 text-indigo-700 font-bold px-2 py-0.5 rounded text-[11px]">
                                                {{ $aff->ideologicalAffiliation->name }}
                                            </span>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($aff->politicalAffiliation)
                                            <span class="bg-blue-50 text-blue-700 font-bold px-2 py-0.5 rounded text-[11px]">
                                                {{ $aff->politicalAffiliation->name }}
                                            </span>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-slate-600">{{ $aff->affiliation_date ? $aff->affiliation_date->format('Y-m-d') : '-' }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $aff->duration ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $aff->workplace ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $aff->job_type ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $aff->direct_supervisor ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $aff->leaving_date ? $aff->leaving_date->format('Y-m-d') : '-' }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $aff->leaving_reason ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- 2. Previous Jihadist Actions Table --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
                <h5 class="text-base font-bold text-slate-800 header-font flex items-center gap-2">
                    <span class="w-1.5 h-5 bg-amber-500 rounded-full"></span>
                    الأعمال الجهادية السابقة
                    <span class="bg-amber-100 text-amber-800 text-xs px-2 py-0.5 rounded-full font-bold">
                        {{ $secondaryData->previousJihadistActions->count() }} سجل
                    </span>
                </h5>
            </div>
            <div class="p-0">
                @if($secondaryData->previousJihadistActions->isEmpty())
                    <div class="py-8 text-center text-slate-400 text-xs font-medium">
                        <i class="fas fa-info-circle text-slate-300 ml-1"></i> لا توجد سجلات أعمال جهادية سابقة.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-right text-xs">
                            <thead class="bg-slate-50/70 text-[11px] font-bold text-slate-500 border-b border-slate-100">
                                <tr>
                                    <th class="px-4 py-3">النشاط</th>
                                    <th class="px-4 py-3">التاريخ</th>
                                    <th class="px-4 py-3">المدة الزمنية</th>
                                    <th class="px-4 py-3">المكان</th>
                                    <th class="px-4 py-3">المسؤول المباشر</th>
                                    <th class="px-4 py-3">سبب الترك</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($secondaryData->previousJihadistActions as $act)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-4 py-3 font-bold text-slate-800">{{ $act->activity ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $act->activity_date ? $act->activity_date->format('Y-m-d') : '-' }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $act->duration ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $act->location ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $act->direct_supervisor ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $act->leaving_reason ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- 3. Weapons & Custody Table --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
                <h5 class="text-base font-bold text-slate-800 header-font flex items-center gap-2">
                    <span class="w-1.5 h-5 bg-emerald-500 rounded-full"></span>
                    بيانات الأسلحة والعهدة
                    <span class="bg-emerald-100 text-emerald-800 text-xs px-2 py-0.5 rounded-full font-bold">
                        {{ $secondaryData->weapons->count() }} قطعة
                    </span>
                </h5>
            </div>
            <div class="p-0">
                @if($secondaryData->weapons->isEmpty())
                    <div class="py-8 text-center text-slate-400 text-xs font-medium">
                        <i class="fas fa-info-circle text-slate-300 ml-1"></i> لا توجد أسلحة أو عهدة مسجلة.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-right text-xs">
                            <thead class="bg-slate-50/70 text-[11px] font-bold text-slate-500 border-b border-slate-100">
                                <tr>
                                    <th class="px-4 py-3">نوع السلاح</th>
                                    <th class="px-4 py-3">الرقم</th>
                                    <th class="px-4 py-3">الملكية</th>
                                    <th class="px-4 py-3">التعقب</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($secondaryData->weapons as $weapon)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-4 py-3 font-bold text-slate-700">{{ $weapon->weapon_type ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $weapon->number ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $weapon->ownership ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $weapon->tracking ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
