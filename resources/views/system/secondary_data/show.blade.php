@extends('layouts.app')

@section('title', 'عرض تفاصيل البيانات الثانوية')

@section('content')
<div class="container-fluid mx-auto px-4 py-6" dir="rtl">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 max-w-7xl mx-auto">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-brand-600 rounded-2xl shadow-md flex items-center justify-center text-white">
                <i class="fas fa-id-card text-lg"></i>
            </div>
            <div>
                <h2 class="font-display font-bold text-xl text-slate-900 dark:text-white mb-0.5">تفاصيل السجل الثانوي</h2>
                <p class="text-slate-500 dark:text-slate-400 text-xs">عرض متكامل للبيانات الثانوية، الحالة الصحية، الأولاد، الانتماءات والأسلحة.</p>
            </div>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('system.secondary-data.index') }}"
                class="px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-all shadow-sm flex items-center gap-1.5">
                <i class="fas fa-arrow-right"></i> عودة للقائمة
            </a>
            <a href="{{ route('system.secondary-data.edit', $secondaryData) }}"
                class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs rounded-xl shadow-sm transition-all flex items-center gap-1.5">
                <i class="fas fa-edit text-xs"></i> تعديل السجل
            </a>
            <x-print-button :url="route('system.secondary-data.print', $secondaryData)" text="طباعة التقرير الثانوي" variant="primary" />
            @if($secondaryData->personalData)
                <a href="{{ route('system.personal-data.print-all', $secondaryData->personalData) }}" target="_blank"
                    class="px-4 py-2 bg-slate-800 dark:bg-slate-700 hover:bg-slate-900 dark:hover:bg-slate-600 text-white font-bold text-xs rounded-xl shadow-sm transition-all flex items-center gap-1.5">
                    <i class="fas fa-print text-xs"></i> طباعة الملف الشامل
                </a>
            @endif
        </div>
    </div>

    <div class="max-w-7xl mx-auto space-y-6">

        {{-- Top Summary Card --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 p-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
                {{-- Person Basic Info --}}
                <div class="lg:col-span-6 border-b lg:border-b-0 lg:border-l border-slate-100 dark:border-slate-800 pb-4 lg:pb-0 lg:pl-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-300 rounded-2xl flex items-center justify-center font-bold text-2xl border border-brand-200 dark:border-brand-800 shadow-xs">
                            {{ mb_substr($secondaryData->personalData->name ?? 'ش', 0, 1) }}
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">{{ $secondaryData->personalData->name ?? 'غير محدد' }}</h3>
                            <div class="flex flex-wrap items-center gap-2 mt-1 text-xs text-slate-500 dark:text-slate-400">
                                @if($secondaryData->personalData?->jihadi_name)
                                    <span class="bg-slate-100 dark:bg-slate-800 border border-slate-200/60 dark:border-slate-700/60 px-2 py-0.5 rounded text-slate-700 dark:text-slate-300 font-semibold">
                                        <i class="fas fa-user-tag text-brand-500 ml-1"></i> {{ $secondaryData->personalData->jihadi_name }}
                                    </span>
                                @endif
                                @if($secondaryData->personalData?->military_number)
                                    <span class="bg-slate-100 dark:bg-slate-800 border border-slate-200/60 dark:border-slate-700/60 px-2 py-0.5 rounded text-slate-700 dark:text-slate-300 font-semibold">
                                        <i class="fas fa-id-badge text-slate-400 ml-1"></i> {{ $secondaryData->personalData->military_number }}
                                    </span>
                                @endif
                                @if($secondaryData->personalData?->bloodType)
                                    <span class="bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-900/50 px-2 py-0.5 rounded text-rose-600 dark:text-rose-400 font-extrabold">
                                        <i class="fas fa-tint ml-1"></i> {{ $secondaryData->personalData->bloodType->type }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Key Stats (Health & Children) --}}
                <div class="lg:col-span-6 grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="bg-teal-50/60 dark:bg-teal-950/40 border border-teal-200 dark:border-teal-800/80 p-3.5 rounded-xl text-center">
                        <span class="text-[11px] font-bold text-teal-600 dark:text-teal-400 block mb-1">الحالة الصحية</span>
                        <span class="text-sm font-extrabold text-teal-800 dark:text-teal-200">{{ $secondaryData->healthStatus->name ?? 'غير محددة' }}</span>
                    </div>
                    <div class="bg-purple-50/60 dark:bg-purple-950/40 border border-purple-200 dark:border-purple-800/80 p-3.5 rounded-xl text-center">
                        <span class="text-[11px] font-bold text-purple-600 dark:text-purple-400 block mb-1">عدد الأولاد</span>
                        <span class="text-base font-extrabold text-purple-800 dark:text-purple-200">{{ $secondaryData->total_children_count ?? (($secondaryData->male_children_count ?? 0) + ($secondaryData->female_children_count ?? 0)) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- General Jihadist Info --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 p-5">
            <h5 class="text-sm font-bold text-slate-800 dark:text-slate-100 mb-4 flex items-center gap-2">
                <span class="w-1.5 h-4 bg-amber-500 rounded-full"></span>
                البيانات والمهام العامة
            </h5>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
                <div class="bg-slate-50 dark:bg-slate-950/70 p-3 rounded-xl border border-slate-200 dark:border-slate-800">
                    <span class="text-slate-400 dark:text-slate-500 block text-[11px] mb-1">عام الانطلاقة / البدء:</span>
                    <span class="font-bold text-slate-800 dark:text-slate-200">{{ $secondaryData->start_date ? $secondaryData->start_date->format('Y') : '-' }}</span>
                </div>
                <div class="bg-slate-50 dark:bg-slate-950/70 p-3 rounded-xl border border-slate-200 dark:border-slate-800">
                    <span class="text-slate-400 dark:text-slate-500 block text-[11px] mb-1">المسؤول المباشر:</span>
                    <span class="font-bold text-slate-800 dark:text-slate-200">{{ $secondaryData->direct_supervisor ?? '-' }}</span>
                </div>
                <div class="bg-slate-50 dark:bg-slate-950/70 p-3 rounded-xl border border-slate-200 dark:border-slate-800 md:col-span-2">
                    <span class="text-slate-400 dark:text-slate-500 block text-[11px] mb-1">النشاط الجهادي الحالي:</span>
                    <span class="font-bold text-slate-800 dark:text-slate-200">{{ $secondaryData->current_jihadist_activity ?? '-' }}</span>
                </div>
                @if($secondaryData->previous_jihadist_activity)
                <div class="bg-slate-50 dark:bg-slate-950/70 p-3 rounded-xl border border-slate-200 dark:border-slate-800 md:col-span-2 lg:col-span-4">
                    <span class="text-slate-400 dark:text-slate-500 block text-[11px] mb-1">ملاحظات النشاط السابق:</span>
                    <span class="font-medium text-slate-700 dark:text-slate-300">{{ $secondaryData->previous_jihadist_activity }}</span>
                </div>
                @endif
            </div>
        </div>

        {{-- 1. Political & Ideological Affiliations table --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-950/50 flex justify-between items-center">
                <h5 class="text-base font-bold text-slate-800 dark:text-slate-100 header-font flex items-center gap-2">
                    <span class="w-1.5 h-5 bg-indigo-600 rounded-full"></span>
                    الانتماء السياسي والعقائدي
                    <span class="bg-indigo-100 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800 text-xs px-2.5 py-0.5 rounded-full font-bold">
                        {{ $secondaryData->affiliations->count() }} سجل
                    </span>
                </h5>
            </div>
            <div class="p-0">
                @if($secondaryData->affiliations->isEmpty())
                    <div class="py-8 text-center text-slate-400 dark:text-slate-500 text-xs font-medium">
                        <i class="fas fa-info-circle text-slate-300 dark:text-slate-600 ml-1"></i> لا توجد سجلات انتماء سياسي أو عقائدي مسجلة.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-right text-xs">
                            <thead class="bg-slate-50/80 dark:bg-slate-950/60 text-[11px] font-bold text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                                <tr>
                                    <th class="px-4 py-3">الجهة</th>
                                    <th class="px-4 py-3">الانتماء العقائدي</th>
                                    <th class="px-4 py-3">الانتماء السياسي</th>
                                    <th class="px-4 py-3">عام الارتباط</th>
                                    <th class="px-4 py-3">مدة الانتماء</th>
                                    <th class="px-4 py-3">مكان العمل</th>
                                    <th class="px-4 py-3">نوع العمل</th>
                                    <th class="px-4 py-3">المسؤول المباشر</th>
                                    <th class="px-4 py-3">عام الترك</th>
                                    <th class="px-4 py-3">سبب الترك</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @foreach($secondaryData->affiliations as $aff)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-colors">
                                    <td class="px-4 py-3 font-bold text-slate-800 dark:text-slate-200">{{ $aff->party ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        @if($aff->ideologicalAffiliation)
                                            <span class="bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800/80 font-bold px-2 py-0.5 rounded text-[11px]">
                                                {{ $aff->ideologicalAffiliation->name }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 dark:text-slate-600">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($aff->politicalAffiliation)
                                            <span class="bg-brand-50 dark:bg-brand-950/50 text-brand-700 dark:text-brand-300 border border-brand-200 dark:border-brand-800/80 font-bold px-2 py-0.5 rounded text-[11px]">
                                                {{ $aff->politicalAffiliation->name }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 dark:text-slate-600">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-slate-700 dark:text-slate-300">{{ $aff->affiliation_date ? $aff->affiliation_date->format('Y') : '-' }}</td>
                                    <td class="px-4 py-3 text-slate-700 dark:text-slate-300">{{ $aff->duration ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-700 dark:text-slate-300">{{ $aff->workplace ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-700 dark:text-slate-300">{{ $aff->job_type ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-700 dark:text-slate-300">{{ $aff->direct_supervisor ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-700 dark:text-slate-300">{{ $aff->leaving_date ? $aff->leaving_date->format('Y') : '-' }}</td>
                                    <td class="px-4 py-3 text-slate-700 dark:text-slate-300">{{ $aff->leaving_reason ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- 2. Previous Jihadist Actions table --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-950/50 flex justify-between items-center">
                <h5 class="text-base font-bold text-slate-800 dark:text-slate-100 header-font flex items-center gap-2">
                    <span class="w-1.5 h-5 bg-amber-500 rounded-full"></span>
                    الأعمال الجهادية السابقة
                    <span class="bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-800 text-xs px-2.5 py-0.5 rounded-full font-bold">
                        {{ $secondaryData->previousJihadistActions->count() }} سجل
                    </span>
                </h5>
            </div>
            <div class="p-0">
                @if($secondaryData->previousJihadistActions->isEmpty())
                    <div class="py-8 text-center text-slate-400 dark:text-slate-500 text-xs font-medium">
                        <i class="fas fa-info-circle text-slate-300 dark:text-slate-600 ml-1"></i> لا توجد سجلات أعمال جهادية سابقة.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-right text-xs">
                            <thead class="bg-slate-50/80 dark:bg-slate-950/60 text-[11px] font-bold text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                                <tr>
                                    <th class="px-4 py-3">النشاط</th>
                                    <th class="px-4 py-3">العام</th>
                                    <th class="px-4 py-3">المدة الزمنية</th>
                                    <th class="px-4 py-3">المكان</th>
                                    <th class="px-4 py-3">المسؤول المباشر</th>
                                    <th class="px-4 py-3">سبب الترك</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @foreach($secondaryData->previousJihadistActions as $act)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-colors">
                                    <td class="px-4 py-3 font-bold text-slate-800 dark:text-slate-200">{{ $act->activity ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-700 dark:text-slate-300">{{ $act->activity_date ? $act->activity_date->format('Y') : '-' }}</td>
                                    <td class="px-4 py-3 text-slate-700 dark:text-slate-300">{{ $act->duration ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-700 dark:text-slate-300">{{ $act->location ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-700 dark:text-slate-300">{{ $act->direct_supervisor ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-700 dark:text-slate-300">{{ $act->leaving_reason ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- 3. Weapons & Custody table --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-950/50 flex justify-between items-center">
                <h5 class="text-base font-bold text-slate-800 dark:text-slate-100 header-font flex items-center gap-2">
                    <span class="w-1.5 h-5 bg-emerald-500 rounded-full"></span>
                    بيانات الأسلحة والعهدة
                    <span class="bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 text-xs px-2.5 py-0.5 rounded-full font-bold">
                        {{ $secondaryData->weapons->count() }} قطعة
                    </span>
                </h5>
            </div>
            <div class="p-0">
                @if($secondaryData->weapons->isEmpty())
                    <div class="py-8 text-center text-slate-400 dark:text-slate-500 text-xs font-medium">
                        <i class="fas fa-info-circle text-slate-300 dark:text-slate-600 ml-1"></i> لا توجد أسلحة أو عهدة مسجلة.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-right text-xs">
                            <thead class="bg-slate-50/80 dark:bg-slate-950/60 text-[11px] font-bold text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                                <tr>
                                    <th class="px-4 py-3">نوع السلاح</th>
                                    <th class="px-4 py-3">الرقم</th>
                                    <th class="px-4 py-3">الملكية</th>
                                    <th class="px-4 py-3">التعقب</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @foreach($secondaryData->weapons as $weapon)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-colors">
                                    <td class="px-4 py-3 font-bold text-slate-800 dark:text-slate-200">{{ $weapon->weapon_type ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-700 dark:text-slate-300">{{ $weapon->number ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-700 dark:text-slate-300">{{ $weapon->ownership ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-700 dark:text-slate-300">{{ $weapon->tracking ?? '-' }}</td>
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
