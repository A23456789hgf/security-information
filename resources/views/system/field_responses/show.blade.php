@extends('layouts.app')

@section('title', 'تفاصيل الرد الميداني')
@section('page_title', 'السجلات الأمنية')

@section('content')
<div class="space-y-6" dir="rtl">
    {{-- Breadcrumbs --}}
    <nav class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-2">
        <a href="{{ route('dashboard') }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">الرئيسية</a>
        <span>/</span>
        <a href="{{ route('system.field-responses.index') }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">الرد الميداني</a>
        <span>/</span>
        <span class="text-slate-900 dark:text-white font-semibold">تفاصيل الرد #{{ $fieldResponse->id }}</span>
    </nav>

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center font-bold shadow-sm shrink-0">
                <i class="fas fa-satellite-dish text-xl"></i>
            </div>
            <div>
                <h1 class="font-display font-bold text-2xl text-slate-900 dark:text-white m-0">تفاصيل الرد الميداني</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">عرض السجل التفص للرد الميداني الخاص بالشخص المعني.</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('system.field-responses.print', $fieldResponse->id) }}" target="_blank"
                class="px-4 py-2 bg-amber-500 text-white rounded-xl hover:bg-amber-600 transition-colors text-xs font-bold shadow-sm">
                <i class="fas fa-print ml-1"></i> طباعة التقرير
            </a>
            <a href="{{ route('system.field-responses.edit', $fieldResponse->id) }}"
                class="px-4 py-2 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-colors text-xs font-bold shadow-sm">
                <i class="fas fa-edit ml-1"></i> تعديل البيانات
            </a>
            <a href="{{ route('system.field-responses.index') }}"
                class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors text-xs font-bold">
                <i class="fas fa-arrow-right ml-1"></i> عودة
            </a>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Column 1: Person Card --}}
        <div class="lg:col-span-1 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-6">
            <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2 border-b border-slate-100 dark:border-slate-800 pb-3">
                <i class="fas fa-user-circle text-brand-500"></i>
                <span>بيانات الشخص المعني</span>
            </h2>

            <div class="flex flex-col items-center text-center space-y-3">
                <div class="w-28 h-28 rounded-2xl bg-slate-100 dark:bg-slate-800 overflow-hidden border border-slate-200 dark:border-slate-700 shadow-inner flex items-center justify-center">
                    @if($fieldResponse->personalData && ($fieldResponse->personalData->photo_path || $fieldResponse->personalData->photo))
                        <img src="{{ asset('storage/' . ($fieldResponse->personalData->photo_path ?? $fieldResponse->personalData->photo)) }}" alt="{{ $fieldResponse->personalData->name }}" class="w-full h-full object-cover">
                    @else
                        <i class="fas fa-user text-4xl text-slate-400"></i>
                    @endif
                </div>
                <div>
                    @if($fieldResponse->personalData)
                        <h3 class="font-bold text-lg text-slate-900 dark:text-white">
                            {{ $fieldResponse->personalData->name }}
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            {{ $fieldResponse->personalData->job_title ?? 'لا توجد صفة وظيفية' }}
                        </p>
                        <a href="{{ route('system.personal-data.show', $fieldResponse->personalData->id) }}" class="inline-block text-xs font-bold text-brand-600 dark:text-brand-400 hover:underline mt-2">
                            عرض ملف الشخص الكامل <i class="fas fa-external-link-alt text-[10px] mr-1"></i>
                        </a>
                    @else
                        <span class="text-slate-400 font-bold">غير متاح</span>
                    @endif
                </div>
            </div>

            <div class="space-y-3 pt-4 border-t border-slate-100 dark:border-slate-800 text-xs">
                <div class="flex justify-between py-1.5 border-b border-slate-50 dark:border-slate-800/50">
                    <span class="text-slate-500 dark:text-slate-400">الرقم الوطني:</span>
                    <span class="font-bold text-slate-900 dark:text-white">{{ $fieldResponse->personalData->national_id_number ?? '-' }}</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-50 dark:border-slate-800/50">
                    <span class="text-slate-500 dark:text-slate-400">الرقم العسكري:</span>
                    <span class="font-bold text-slate-900 dark:text-white">{{ $fieldResponse->personalData->military_number ?? '-' }}</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-50 dark:border-slate-800/50">
                    <span class="text-slate-500 dark:text-slate-400">الجهة التابع لها:</span>
                    <span class="font-bold text-slate-900 dark:text-white">{{ $fieldResponse->entity->name ?? ($fieldResponse->personalData->entity->name ?? '-') }}</span>
                </div>
            </div>
        </div>

        {{-- Column 2 & 3: Field Response Details --}}
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-6">
            <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2 border-b border-slate-100 dark:border-slate-800 pb-3">
                <i class="fas fa-satellite-dish text-brand-500"></i>
                <span>مضمون الرد الميداني</span>
            </h2>

            <div class="bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl p-5">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">نص الرد الميداني الكامل</label>
                <div class="text-sm font-semibold text-slate-900 dark:text-slate-100 leading-relaxed whitespace-pre-line">
                    {{ $fieldResponse->field_response }}
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-4">
                    <span class="block text-xs text-slate-400 font-bold mb-1"><i class="far fa-calendar-alt ml-1"></i> تاريخ الرد</span>
                    <span class="text-sm font-bold text-slate-900 dark:text-white">
                        {{ $fieldResponse->response_date ? $fieldResponse->response_date->format('Y-m-d') : 'غير محدد' }}
                    </span>
                </div>

                <div class="bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-4">
                    <span class="block text-xs text-slate-400 font-bold mb-1"><i class="fas fa-user-secret ml-1"></i> المصدر الميداني</span>
                    <span class="text-sm font-bold text-slate-900 dark:text-white">
                        {{ $fieldResponse->source ?: 'غير مدخل' }}
                    </span>
                </div>

                <div class="bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-4">
                    <span class="block text-xs text-slate-400 font-bold mb-1"><i class="fas fa-briefcase ml-1"></i> عمل المصدر</span>
                    <span class="text-sm font-bold text-slate-900 dark:text-white">
                        {{ $fieldResponse->source_job ?: 'غير مدخل' }}
                    </span>
                </div>

                <div class="bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-4">
                    <span class="block text-xs text-slate-400 font-bold mb-1"><i class="fas fa-building ml-1"></i> الجهة</span>
                    <span class="text-sm font-bold text-slate-900 dark:text-white">
                        {{ $fieldResponse->entity->name ?? 'غير محدد' }}
                    </span>
                </div>
            </div>

            @if($fieldResponse->notes)
                <div class="bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-4">
                    <span class="block text-xs text-slate-400 font-bold mb-1"><i class="fas fa-sticky-note ml-1"></i> الملاحظات</span>
                    <p class="text-sm text-slate-800 dark:text-slate-200 m-0">
                        {{ $fieldResponse->notes }}
                    </p>
                </div>
            @endif

            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex flex-wrap justify-between items-center text-xs text-slate-400">
                <div>
                    <span>تم التسجيل بواسطة: </span>
                    <strong class="text-slate-600 dark:text-slate-300">{{ $fieldResponse->user->name ?? 'النظام' }}</strong>
                </div>
                <div>
                    <span>تاريخ التسجيل: </span>
                    <strong class="text-slate-600 dark:text-slate-300">{{ $fieldResponse->created_at ? $fieldResponse->created_at->format('Y-m-d H:i') : '-' }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
