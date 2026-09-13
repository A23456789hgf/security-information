@extends('layouts.app')

@section('title', 'تفاصيل البلاغ الأمني - ' . ($securityReport->full_name ?? $securityReport->name))
@section('page_title', 'السجلات الأساسية')

@section('content')
<div class="space-y-6" dir="rtl">
    {{-- Breadcrumbs --}}
    <nav class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-2 print:hidden">
        <a href="{{ route('dashboard') }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">الرئيسية</a>
        <span>/</span>
        <a href="{{ route('system.security-reports.index') }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">البلاغات الأمنية</a>
        <span>/</span>
        <span class="text-slate-900 dark:text-white font-semibold">تفاصيل البلاغ</span>
    </nav>

    {{-- Action Toolbar --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4 print:hidden">
        <div>
            <h1 class="font-display font-bold text-2xl text-slate-900 dark:text-white m-0">{{ $securityReport->full_name }}</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">موضوع البلاغ: {{ $securityReport->report_name }}</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-2.5">
            <x-print-button :url="route('system.security-reports.print', $securityReport)" text="طباعة البلاغ الرسمي" variant="primary" />
            <button onclick="window.print()"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors text-sm font-bold shadow-sm">
                <i class="fas fa-print text-slate-400"></i>
                <span>طباعة سريعة</span>
            </button>
            <a href="{{ route('system.security-reports.edit', $securityReport) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 text-amber-700 dark:text-amber-400 rounded-xl hover:bg-amber-100 dark:hover:bg-amber-500/20 transition-colors text-sm font-bold shadow-sm">
                <i class="fas fa-edit"></i>
                <span>تعديل البلاغ</span>
            </a>
            <a href="{{ route('system.security-reports.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors text-sm font-bold">
                <i class="fas fa-arrow-right"></i>
                <span>عودة للقائمة</span>
            </a>
        </div>
    </div>

    {{-- Main Grid Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Right Column: Profile Summary (Sticky) --}}
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 lg:sticky lg:top-24 transition-colors print:shadow-none print:border-slate-300">
                <div class="text-center mb-6">
                    <div class="w-24 h-24 rounded-2xl bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-500/20 mx-auto mb-4 flex items-center justify-center shadow-inner">
                        <i class="fas fa-shield-alt text-4xl"></i>
                    </div>
                    
                    <h3 class="font-display font-bold text-xl text-slate-900 dark:text-white mb-2">{{ $securityReport->full_name }}</h3>
                    <x-security-badge level="warning" :label="$securityReport->report_type ?? 'بلاغ أمني'" />
                </div>

                <hr class="border-slate-100 dark:border-slate-800 my-6" />

                <div class="space-y-4">
                    <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800">
                        <span class="block text-xs font-bold text-slate-400 dark:text-slate-500 mb-1">تاريخ البلاغ</span>
                        <div class="font-semibold text-slate-800 dark:text-slate-200 dark:text-slate-200 flex items-center gap-2 text-sm">
                            <i class="far fa-calendar-alt text-slate-400 text-xs"></i>
                            {{ $securityReport->report_date ? $securityReport->report_date->format('Y-m-d') : '-' }}
                        </div>
                    </div>

                    @if($securityReport->military_number)
                        <div class="p-3.5 rounded-xl bg-brand-50/50 dark:bg-brand-500/5 border border-brand-100 dark:border-brand-500/20">
                            <span class="block text-xs font-bold text-brand-600 dark:text-brand-400 mb-1">الرقم العسكري</span>
                            <div class="font-mono font-bold text-brand-700 dark:text-brand-300 flex items-center gap-2 text-sm">
                                <i class="fas fa-id-badge text-brand-500 text-xs"></i>
                                {{ $securityReport->military_number }}
                            </div>
                        </div>
                    @endif

                    @if($securityReport->id_number)
                        <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800">
                            <span class="block text-xs font-bold text-slate-400 dark:text-slate-500 mb-1">رقم الهوية</span>
                            <div class="font-mono font-bold text-slate-800 dark:text-slate-200 dark:text-slate-200 flex items-center gap-2 text-sm">
                                <i class="fas fa-id-card text-slate-400 text-xs"></i>
                                {{ $securityReport->id_number }}
                            </div>
                        </div>
                    @endif

                    @if($securityReport->phone)
                        <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800">
                            <span class="block text-xs font-bold text-slate-400 dark:text-slate-500 mb-1">رقم الهاتف</span>
                            <div class="font-mono font-bold text-slate-800 dark:text-slate-200 dark:text-slate-200 flex items-center gap-2 text-sm">
                                <i class="fas fa-phone text-slate-400 text-xs"></i>
                                {{ $securityReport->phone }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Left Column: Details Cards --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Section 1: Unit and Agency --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors print:shadow-none print:border-slate-300">
                <div class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 px-5 py-4 flex items-center gap-3">
                    <div class="w-2 h-5 bg-brand-600 rounded-full"></div>
                    <h2 class="font-display font-bold text-base text-slate-900 dark:text-white m-0">بيانات الوحدة والجهة</h2>
                </div>
                
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <span class="block text-xs font-bold text-slate-400 dark:text-slate-500 mb-1">الوحدة</span>
                        <div class="text-sm font-bold text-slate-800 dark:text-slate-200 dark:text-slate-200">
                            {{ $securityReport->unit ?: '-' }}
                        </div>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-slate-400 dark:text-slate-500 mb-1">الوحدة الفرعية</span>
                        <div class="text-sm font-bold text-slate-800 dark:text-slate-200 dark:text-slate-200">
                            {{ $securityReport->sub_unit ?: '-' }}
                        </div>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-slate-400 dark:text-slate-500 mb-1">الرتبة</span>
                        <div class="text-sm font-bold text-slate-800 dark:text-slate-200 dark:text-slate-200">
                            {{ $securityReport->rank ?: '-' }}
                        </div>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-slate-400 dark:text-slate-500 mb-1">المسمى الوظيفي</span>
                        <div class="text-sm font-bold text-slate-800 dark:text-slate-200 dark:text-slate-200">
                            {{ $securityReport->job_title ?: '-' }}
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <span class="block text-xs font-bold text-slate-400 dark:text-slate-500 mb-1">العمل / الجهة</span>
                        <div class="text-sm font-bold text-slate-800 dark:text-slate-200 dark:text-slate-200">
                            {{ $securityReport->entity ? $securityReport->entity->name : ($securityReport->work ?: '-') }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 2: Origin and Residence --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors print:shadow-none print:border-slate-300">
                <div class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 px-5 py-4 flex items-center gap-3">
                    <div class="w-2 h-5 bg-emerald-600 rounded-full"></div>
                    <h2 class="font-display font-bold text-base text-slate-900 dark:text-white m-0">بيانات الأصل والسكن</h2>
                </div>
                
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4 p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800">
                        <span class="block text-xs font-bold text-emerald-600 dark:text-emerald-400 mb-2">
                            <i class="fas fa-map-marker-alt me-1"></i> بيانات الأصل
                        </span>
                        <div>
                            <span class="block text-[11px] text-slate-400 mb-0.5">المحافظة:</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200 dark:text-slate-200 text-xs">{{ $securityReport->original_governorate ?: '-' }}</span>
                        </div>
                        <div>
                            <span class="block text-[11px] text-slate-400 mb-0.5">المديرية:</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200 dark:text-slate-200 text-xs">{{ $securityReport->original_directorate ?: '-' }}</span>
                        </div>
                        <div>
                            <span class="block text-[11px] text-slate-400 mb-0.5">القرية:</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200 dark:text-slate-200 text-xs">{{ $securityReport->original_village ?: '-' }}</span>
                        </div>
                    </div>

                    <div class="space-y-4 p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800">
                        <span class="block text-xs font-bold text-purple-600 dark:text-purple-400 mb-2">
                            <i class="fas fa-home me-1"></i> بيانات السكن
                        </span>
                        <div>
                            <span class="block text-[11px] text-slate-400 mb-0.5">المحافظة:</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200 dark:text-slate-200 text-xs">{{ $securityReport->residence_governorate ?: '-' }}</span>
                        </div>
                        <div>
                            <span class="block text-[11px] text-slate-400 mb-0.5">المديرية:</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200 dark:text-slate-200 text-xs">{{ $securityReport->residence_directorate ?: '-' }}</span>
                        </div>
                        <div>
                            <span class="block text-[11px] text-slate-400 mb-0.5">القرية / المنطقة:</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200 dark:text-slate-200 text-xs">{{ $securityReport->residence_village ?: ($securityReport->area ?: '-') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 3: Legal & Arrest Status --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors print:shadow-none print:border-slate-300">
                <div class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 px-5 py-4 flex items-center gap-3">
                    <div class="w-2 h-5 bg-rose-600 rounded-full"></div>
                    <h2 class="font-display font-bold text-base text-slate-900 dark:text-white m-0">الحالة القانونية والإجراءات</h2>
                </div>
                
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <span class="block text-xs font-bold text-slate-400 dark:text-slate-500 mb-1">حالة القبض</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200 dark:text-slate-200 text-xs">{{ $securityReport->arrest_status ?: '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-slate-400 dark:text-slate-500 mb-1">تاريخ القبض</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200 dark:text-slate-200 text-xs">{{ $securityReport->arrest_date ? $securityReport->arrest_date->format('Y-m-d') : '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-slate-400 dark:text-slate-500 mb-1">تاريخ الإيداع</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200 dark:text-slate-200 text-xs">{{ $securityReport->deposit_date ? $securityReport->deposit_date->format('Y-m-d') : '-' }}</span>
                    </div>

                    <div class="md:col-span-3">
                        <span class="block text-xs font-bold text-slate-400 dark:text-slate-500 mb-1">التهمة المنسوبة</span>
                        <p class="text-sm font-semibold text-slate-800 dark:text-slate-200 dark:text-slate-200 bg-slate-50 dark:bg-slate-950 p-3 rounded-xl border border-slate-100 dark:border-slate-800 m-0">
                            {{ $securityReport->charge ?: 'لا توجد تهمة مسجلة' }}
                        </p>
                    </div>

                    <div class="md:col-span-3">
                        <span class="block text-xs font-bold text-slate-400 dark:text-slate-500 mb-1">الإجراءات المتخذة</span>
                        <p class="text-sm font-semibold text-slate-800 dark:text-slate-200 dark:text-slate-200 bg-slate-50 dark:bg-slate-950 p-3 rounded-xl border border-slate-100 dark:border-slate-800 m-0">
                            {{ $securityReport->procedures ?: 'لا توجد إجراءات مسجلة' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Section 4: Notes --}}
            @if($securityReport->notes || $securityReport->additional_notes)
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors print:shadow-none print:border-slate-300">
                    <div class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 px-5 py-4 flex items-center gap-3">
                        <div class="w-2 h-5 bg-amber-500 rounded-full"></div>
                        <h2 class="font-display font-bold text-base text-slate-900 dark:text-white m-0">ملاحظات التحليل الأمني</h2>
                    </div>
                    
                    <div class="p-6 space-y-4 text-sm text-slate-700 dark:text-slate-300 leading-relaxed">
                        @if($securityReport->notes)
                            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800">
                                <span class="block text-xs font-bold text-slate-400 dark:text-slate-500 mb-1">الملاحظات الأساسية:</span>
                                <p class="m-0">{{ $securityReport->notes }}</p>
                            </div>
                        @endif
                        @if($securityReport->additional_notes)
                            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800">
                                <span class="block text-xs font-bold text-slate-400 dark:text-slate-500 mb-1">ملاحظات إضافية:</span>
                                <p class="m-0">{{ $securityReport->additional_notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection

