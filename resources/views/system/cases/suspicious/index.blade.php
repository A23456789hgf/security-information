@extends('layouts.app')

@section('title', 'سجل المشتبه بهم')
@section('page_title', 'السجلات الأساسية')

@section('content')
<div class="space-y-6" dir="rtl">
    {{-- Breadcrumbs --}}
    <nav class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-2 print:hidden">
        <a href="{{ route('dashboard') }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">الرئيسية</a>
        <span>/</span>
        <span class="text-slate-900 dark:text-white font-semibold">المشتبه بهم</span>
    </nav>

    {{-- Header & Actions --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-2 print:hidden">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold shadow-sm shrink-0">
                <i class="fas fa-user-secret text-xl"></i>
            </div>
            <div>
                <h1 class="font-display font-bold text-2xl text-slate-900 dark:text-white m-0">سجل المشتبه بهم</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">متابعة وإدارة ملفات المشتبه بهم ودرجات الخطورة الأمنية.</p>
            </div>
        </div>
        
        <div class="flex flex-wrap items-center gap-2.5">
            <x-print-button :url="route('system.suspicious.print-all', request()->all())" text="طباعة الكشف" />
            <a href="{{ route('system.suspicious.export', request()->all()) }}"
                class="inline-flex items-center gap-2 px-3.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors text-xs sm:text-sm font-bold shadow-sm">
                <i class="fas fa-file-excel text-emerald-600"></i>
                <span>تصدير Excel</span>
            </a>
            <a href="{{ route('data.import.index', ['type' => 'suspicious']) }}"
                class="inline-flex items-center gap-2 px-3.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors text-xs sm:text-sm font-bold shadow-sm">
                <i class="fas fa-file-import text-brand-600"></i>
                <span>استيراد</span>
            </a>
            <a href="{{ route('system.suspicious.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-600 text-white rounded-xl hover:bg-brand-700 transition-all text-xs sm:text-sm font-bold shadow-sm shadow-brand-500/30">
                <i class="fas fa-plus"></i>
                <span>إضافة مشتبه به</span>
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

    {{-- Search Toolbar --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-4 shadow-sm transition-colors">
        <form method="GET" action="{{ route('system.suspicious.index') }}" class="flex flex-col md:flex-row items-center gap-3">
            <div class="relative flex-1 w-full">
                <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                    <i class="fas fa-search"></i>
                </div>
                <input type="text" name="search"
                    class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl pr-10 pl-4 py-2.5 text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all"
                    placeholder="ابحث بالاسم، رقم الأشتباه، النوع..." value="{{ request('search') }}">
            </div>

            <div class="w-full md:w-64">
                <select name="personal_data_id" onchange="this.form.submit()" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2.5 text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-brand-500/20 shadow-sm">
                    <option value="">-- فرز بالشخص المعني (الكل) --</option>
                    @foreach($personalDataList as $p)
                        <option value="{{ $p->id }}" {{ request('personal_data_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->name }} {{ $p->jihadi_name ? '('.$p->jihadi_name.')' : '' }} {{ $p->military_number ? '- م: '.$p->military_number : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="w-full md:w-36">
                @include('partials._pagination_limit')
            </div>

            <div class="flex items-center gap-2 w-full md:w-auto">
                <button type="submit"
                    class="w-full md:w-auto px-5 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-sm font-bold transition-colors">
                    تصفية
                </button>
                @if(request()->anyFilled(['search', 'personal_data_id']))
                    <a href="{{ route('system.suspicious.index') }}"
                        class="px-3 py-2.5 rounded-xl text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 text-sm font-semibold transition-colors"
                        title="مسح البحث">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Data table Container --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden transition-colors">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-right">
                <thead class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 font-bold">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider w-20">#</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">اسم المشتبه به</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">رقم الاشتباه</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center">تاريخ الاشتباه</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">نوع الاشتباه</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center">مستوى الخطورة</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-left">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($suspects as $suspect)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/25 transition-colors">
                            <td class="px-6 py-4 text-slate-400 dark:text-slate-500 font-mono text-xs font-semibold">
                                #{{ str_pad($suspect->id, 4, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($suspect->personalData && $suspect->personalData->photo_url)
                                        <img src="{{ $suspect->personalData->photo_url }}" class="w-10 h-10 rounded-full object-cover border border-slate-200 dark:border-slate-700 shadow-sm shrink-0" alt="{{ $suspect->personalData->name }}">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold text-xs shrink-0">
                                            <i class="fas fa-user-secret"></i>
                                        </div>
                                    @endif
                                    <div>
                                        @if($suspect->personalData)
                                            <a href="{{ route('system.personal-data.show', $suspect->personalData->id) }}" class="font-bold text-slate-900 dark:text-white hover:text-brand-600 dark:hover:text-brand-400 transition-colors block">
                                                {{ $suspect->personalData->name }}
                                            </a>
                                            <div class="flex items-center gap-2 text-xs font-mono text-slate-400 dark:text-slate-500">
                                                @if($suspect->personalData->jihadi_name)
                                                    <span>({{ $suspect->personalData->jihadi_name }})</span>
                                                @endif
                                                @if($suspect->personalData->military_number)
                                                    <span>م: {{ $suspect->personalData->military_number }}</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="font-bold text-slate-900 dark:text-white">{{ $suspect->name ?? '-' }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-mono font-bold text-xs text-slate-700 dark:text-slate-300">
                                {{ $suspect->suspect_number ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-xs font-semibold">
                                    <i class="far fa-calendar-alt text-slate-400"></i>
                                    {{ $suspect->suspect_date ? $suspect->suspect_date->format('Y-m-d') : '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs font-semibold text-slate-700 dark:text-slate-300">
                                {{ $suspect->suspect_type ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if(in_array($suspect->danger_level, ['عالي', 'خطير', 'مطلوب أمنياً']))
                                    <x-security-badge level="critical" :label="$suspect->danger_level" />
                                @elseif($suspect->danger_level)
                                    <x-security-badge level="warning" :label="$suspect->danger_level" />
                                @else
                                    <x-security-badge level="neutral" label="عادي" />
                                @endif
                            </td>
                            <td class="px-6 py-4 text-left">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('system.suspicious.edit', $suspect->id) }}"
                                        class="p-2 rounded-2xl bg-slate-100 dark:bg-slate-800 text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-500/20 transition-colors"
                                        title="تعديل">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    <form action="{{ route('system.suspicious.destroy', $suspect->id) }}"
                                        method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا السجل؟')">
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
                                        <i class="fas fa-user-secret text-2xl"></i>
                                    </div>
                                    <p class="font-bold text-slate-700 dark:text-slate-300 mb-1">لا توجد سجلات للمشتبه بهم حالياً</p>
                                    <a href="{{ route('system.suspicious.create') }}"
                                        class="mt-3 inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-brand-600 text-white hover:bg-brand-700 font-bold text-xs shadow-sm transition-all">
                                        <i class="fas fa-plus"></i> إضافة مشتبه به جديد الآن
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($suspects->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 flex items-center justify-between transition-colors print:hidden">
                <div class="text-xs text-slate-500 dark:text-slate-400">
                    عرض <span class="font-bold text-slate-900 dark:text-white">{{ $suspects->firstItem() ?? 0 }}</span> إلى <span class="font-bold text-slate-900 dark:text-white">{{ $suspects->lastItem() ?? 0 }}</span> من أصل <span class="font-bold text-slate-900 dark:text-white">{{ $suspects->total() }}</span> سجل
                </div>
                <div>
                    {{ $suspects->withQueryString()->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

