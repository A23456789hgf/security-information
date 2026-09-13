@extends('layouts.app')

@section('title', 'سجل البلاغات الأمنية')
@section('page_title', 'السجلات الأساسية')

@section('content')
<div class="space-y-6" dir="rtl">
    {{-- Breadcrumbs --}}
    <nav class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-2 print:hidden">
        <a href="{{ route('dashboard') }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">الرئيسية</a>
        <span>/</span>
        <span class="text-slate-900 dark:text-white font-semibold">البلاغات الأمنية</span>
    </nav>

    {{-- Header & Actions --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-2 print:hidden">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center font-bold shadow-sm shrink-0">
                <i class="fas fa-shield-alt text-xl"></i>
            </div>
            <div>
                <h1 class="font-display font-bold text-2xl text-slate-900 dark:text-white m-0">سجل البلاغات الأمنية</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">إدارة ومتابعة كافة البلاغات والتقارير الأمنية المسجلة في النظام.</p>
            </div>
        </div>
        
        <div class="flex flex-wrap items-center gap-2.5">
            <x-print-button :url="route('system.security-reports.print-all', request()->all())" text="طباعة الكشف" />
            <a href="{{ route('system.security-reports.export', request()->all()) }}"
                class="inline-flex items-center gap-2 px-3.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors text-xs sm:text-sm font-bold shadow-sm">
                <i class="fas fa-file-excel text-emerald-600"></i>
                <span>تصدير Excel</span>
            </a>
            <a href="{{ route('system.security-reports.import') }}"
                class="inline-flex items-center gap-2 px-3.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors text-xs sm:text-sm font-bold shadow-sm">
                <i class="fas fa-file-import text-brand-600"></i>
                <span>استيراد</span>
            </a>
            <a href="{{ route('system.security-reports.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-600 text-white rounded-xl hover:bg-brand-700 transition-all text-xs sm:text-sm font-bold shadow-sm shadow-brand-500/30">
                <i class="fas fa-plus"></i>
                <span>إضافة بلاغ جديد</span>
            </a>
        </div>
    </div>

    {{-- System Alerts --}}
    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-800 dark:text-emerald-300 px-4 py-3 rounded-2xl flex items-center justify-between shadow-sm" role="alert">
            <div class="flex items-center gap-3">
                <i class="fas fa-check-circle text-emerald-500 text-base"></i>
                <span class="text-sm font-bold">{{ session('success') }}</span>
            </div>
            <button type="button" class="text-emerald-400 hover:text-emerald-600 p-1" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    {{-- Search and Filter Toolbar --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-4 shadow-sm transition-colors">
        <form method="GET" action="{{ route('system.security-reports.index') }}" class="flex flex-col md:flex-row items-center gap-3">
            <div class="relative flex-1 w-full">
                <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                    <i class="fas fa-search"></i>
                </div>
                <input type="text" name="search"
                    class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl pr-10 pl-4 py-2.5 text-sm text-slate-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all"
                    placeholder="ابحث بالاسم، موضوع البلاغ، الرقم العسكري، أو رقم الهوية..." value="{{ request('search') }}">
            </div>
            <div class="w-full md:w-48">
                @include('partials._pagination_limit')
            </div>
            <div class="flex items-center gap-2 w-full md:w-auto">
                <button type="submit"
                    class="w-full md:w-auto px-5 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-sm font-bold transition-colors">
                    تصفية النتائج
                </button>
                @if(request('search'))
                    <a href="{{ route('system.security-reports.index') }}"
                        class="px-3 py-2.5 rounded-xl text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 text-sm font-semibold transition-colors"
                        title="مسح البحث">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Data table Container (DataTable matching tem) --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden transition-colors">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-right">
                <thead class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 font-bold">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider w-20">#</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">الاسم المبلغ عنه</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">موضوع البلاغ</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center">تاريخ البلاغ</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">الجهة المبلغة</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center">نوع البلاغ</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-left">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($reports as $report)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/25 transition-colors">
                            <td class="px-6 py-4 text-slate-400 dark:text-slate-500 font-mono text-xs font-semibold">
                                #{{ str_pad($report->id, 4, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center font-bold text-xs shrink-0">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <div>
                                        <a href="{{ route('system.security-reports.show', $report->id) }}" class="font-bold text-slate-900 dark:text-white hover:text-brand-600 dark:hover:text-brand-400 transition-colors">
                                            {{ $report->reported_person_name ?? $report->name ?? '-' }}
                                        </a>
                                        @if($report->id_number || $report->military_number)
                                            <span class="block text-xs font-mono text-slate-400 dark:text-slate-500">
                                                {{ $report->military_number ?? $report->id_number }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-800 dark:text-slate-200 dark:text-slate-200">
                                {{ $report->report_topic ?? $report->subject ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-xs font-semibold">
                                    <i class="far fa-calendar-alt text-slate-400"></i>
                                    {{ $report->report_date ? $report->report_date->format('Y-m-d') : ($report->date ?? '-') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-600 dark:text-slate-400 font-medium">
                                {{ $report->reporting_entity ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <x-security-badge level="warning" :label="$report->report_type ?? 'بلاغ'" />
                            </td>
                            <td class="px-6 py-4 text-left">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('system.security-reports.show', $report->id) }}"
                                        class="p-2 rounded-2xl bg-slate-100 dark:bg-slate-800 text-brand-600 dark:text-brand-400 hover:bg-brand-50 dark:hover:bg-brand-500/20 transition-colors"
                                        title="عرض التفاصيل">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <a href="{{ route('system.security-reports.edit', $report->id) }}"
                                        class="p-2 rounded-2xl bg-slate-100 dark:bg-slate-800 text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-500/20 transition-colors"
                                        title="تعديل">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    <form action="{{ route('system.security-reports.destroy', $report->id) }}"
                                        method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا البلاغ؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-2 rounded-2xl bg-slate-100 dark:bg-slate-800 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/20 transition-colors"
                                            title="حذف">
                                            <i class="fas fa-trash-alt text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center mb-3 text-slate-400 dark:text-slate-500">
                                        <i class="fas fa-shield-alt text-2xl"></i>
                                    </div>
                                    <p class="font-bold text-slate-700 dark:text-slate-300 mb-1">لا توجد بلاغات أمنية مسجلة حالياً</p>
                                    <a href="{{ route('system.security-reports.create') }}"
                                        class="mt-3 inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-brand-600 text-white hover:bg-brand-700 font-bold text-xs shadow-sm transition-all">
                                        <i class="fas fa-plus"></i> إضافة بلاغ جديد الآن
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($reports->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 flex items-center justify-between transition-colors print:hidden">
                <div class="text-xs text-slate-500 dark:text-slate-400">
                    عرض <span class="font-bold text-slate-900 dark:text-white">{{ $reports->firstItem() ?? 0 }}</span> إلى <span class="font-bold text-slate-900 dark:text-white">{{ $reports->lastItem() ?? 0 }}</span> من أصل <span class="font-bold text-slate-900 dark:text-white">{{ $reports->total() }}</span> بلاغ
                </div>
                <div>
                    {{ $reports->withQueryString()->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

