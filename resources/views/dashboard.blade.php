@extends('layouts.app')

@section('title', 'لوحة التحكم والتحليل')
@section('page_title', 'لوحة التحكم والتحليل')

@section('content')
<div class="space-y-6" dir="rtl">

    {{-- Breadcrumbs --}}
    <nav class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-6 print:hidden">
        <a href="{{ route('dashboard') }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">الرئيسية</a>
        <span>/</span>
        <span class="text-slate-900 dark:text-white font-semibold">لوحة التحكم</span>
    </nav>

    {{-- Global Search Toolbar (Matching tem Dashboard.tsx) --}}
    <div class="bg-white dark:bg-slate-900 p-3 sm:p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col md:flex-row gap-3 items-center transition-colors relative" id="searchContainer">
        <form action="{{ route('system.personal-data.index') }}" method="GET" class="relative flex-1 w-full" id="dashboardSearchForm">
            <svg class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 w-5 h-5 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
            </svg>
            <input 
                type="text" 
                id="dashboardSearchInput"
                name="search" 
                placeholder="بحث شامل في البلاغات الأمنية، المشتبه بهم، وسجلات الضبط..." 
                class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl py-2.5 pr-11 pl-4 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all text-sm font-medium placeholder:text-slate-400 dark:placeholder:text-slate-500 text-slate-900 dark:text-white"
                autocomplete="off"
            >
            
            {{-- Search History Dropdown --}}
            <div id="searchHistoryDropdown" class="hidden absolute top-full right-0 left-0 mt-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-lg z-50 overflow-hidden overflow-y-auto max-h-60 transition-colors">
                <div class="px-4 py-2 border-b border-slate-100 dark:border-slate-800 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">
                    عمليات البحث الأخيرة
                </div>
                <ul class="divide-y divide-slate-100 dark:divide-slate-800/60" id="searchHistoryList">
                    <li class="group flex items-center justify-between px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-800/50 cursor-pointer" onclick="selectSearchHistory('سجل المشتبه بهم 2024')">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-slate-400 group-hover:text-brand-500 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 14 14"/></svg>
                            <span class="text-sm font-semibold text-slate-700 dark:text-slate-300 group-hover:text-brand-600 dark:group-hover:text-brand-400">سجل المشتبه بهم 2024</span>
                        </div>
                    </li>
                    <li class="group flex items-center justify-between px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-800/50 cursor-pointer" onclick="selectSearchHistory('سيارة مشبوهة قطاع 3')">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-slate-400 group-hover:text-brand-500 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 14 14"/></svg>
                            <span class="text-sm font-semibold text-slate-700 dark:text-slate-300 group-hover:text-brand-600 dark:group-hover:text-brand-400">سيارة مشبوهة قطاع 3</span>
                        </div>
                    </li>
                    <li class="group flex items-center justify-between px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-800/50 cursor-pointer" onclick="selectSearchHistory('تصريح دخول مؤقت')">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-slate-400 group-hover:text-brand-500 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 14 14"/></svg>
                            <span class="text-sm font-semibold text-slate-700 dark:text-slate-300 group-hover:text-brand-600 dark:group-hover:text-brand-400">تصريح دخول مؤقت</span>
                        </div>
                    </li>
                </ul>
            </div>
        </form>

        <div class="flex gap-2 w-full md:w-auto">
            <select class="bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2.5 text-sm font-semibold text-slate-600 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 cursor-pointer flex-1 md:flex-none transition-colors">
                <option value="all">جميع السجلات</option>
                <option value="personal">البيانات الشخصية</option>
                <option value="suspects">المشتبه بهم</option>
                <option value="reports">البلاغات الأمنية</option>
            </select>
            <a href="{{ route('system.personal-data.create') }}" class="bg-brand-600 hover:bg-brand-700 text-white px-6 py-2.5 rounded-xl font-bold transition-all text-sm shadow-sm shadow-brand-500/30 shrink-0 inline-flex items-center gap-2">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                <span>إضافة سجل</span>
            </a>
        </div>
    </div>

    {{-- Alert Banner (Matching tem AlertTriangle) --}}
    <div class="flex items-center gap-4 p-4 rounded-2xl bg-red-50 dark:bg-red-500/10 border border-red-100 dark:border-red-500/20 shadow-sm transition-colors">
        <div class="w-10 h-10 rounded-xl bg-red-500 text-white flex items-center justify-center shrink-0 shadow-lg shadow-red-500/30">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        </div>
        <div class="flex-1">
            <h6 class="font-display font-bold text-red-600 dark:text-red-400 mb-0.5">تنبيه أمني ومتابعة يومية</h6>
            <p class="text-sm text-slate-600 dark:text-red-200/70 m-0">تم تسجيل <span class="font-bold text-red-700 dark:text-red-300">{{ $stats['total_today'] ?? 5 }}</span> سجلات جديدة اليوم تتطلب مراجعة ومقاطعة مع سجلات المنظومة.</p>
        </div>
    </div>

    {{-- Stats Grid (Matching tem Dashboard.tsx 4-Cards) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Stat Card 1 --}}
        <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col justify-between transition-colors">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">إجمالي السجلات الشخصية</p>
                    <h3 class="font-display font-bold text-3xl text-slate-900 dark:text-white">{{ number_format($stats['personal_data_count'] ?? 142) }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
            </div>
            <div class="flex items-center gap-1.5 mt-2">
                <x-security-badge level="resolved" label="12% زيادة عن الأمس" />
            </div>
        </div>

        {{-- Stat Card 2 --}}
        <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col justify-between transition-colors">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">المشتبه بهم الجدد</p>
                    <h3 class="font-display font-bold text-3xl text-slate-900 dark:text-white">{{ number_format($stats['suspicious_count'] ?? 28) }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a5 5 0 0 0-5 5v2a5 5 0 0 0 10 0V7a5 5 0 0 0-5-5Z"/><path d="M4 19v-2a8 8 0 0 1 16 0v2"/><circle cx="12" cy="11" r="1"/></svg>
                </div>
            </div>
            <div class="flex items-center gap-1.5 mt-2">
                <x-security-badge level="critical" label="5 حالات شديدة الخطورة" />
            </div>
        </div>

        {{-- Stat Card 3 --}}
        <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col justify-between transition-colors">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">حالات الضبط</p>
                    <h3 class="font-display font-bold text-3xl text-slate-900 dark:text-white">{{ number_format($stats['arrests_count'] ?? 14) }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
            </div>
            <div class="flex items-center gap-1.5 mt-2">
                <x-security-badge level="neutral" label="مستقر" :showIcon="false" />
            </div>
        </div>

        {{-- Stat Card 4 --}}
        <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col justify-between transition-colors">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">نشاط النظام والأمان</p>
                    <h3 class="font-display font-bold text-3xl text-slate-900 dark:text-white">98%</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="18 15 12 9 6 15"/></svg>
                </div>
            </div>
            <div class="flex items-center gap-1.5 mt-2">
                <x-security-badge level="info" label="طبيعي" :showIcon="false" />
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         📊 ANALYTICS CHARTS (Matching tem AnalyticsCharts.tsx)
    ═══════════════════════════════════════════════════════════ --}}
    <div class="space-y-6 mt-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Bar Chart for Reports --}}
            <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm transition-colors">
                <h6 class="font-display font-bold text-slate-900 dark:text-white mb-6">معدل البلاغات الأمنية (آخر 7 أيام)</h6>
                <div class="h-72 relative">
                    <canvas id="reportsChart"></canvas>
                </div>
            </div>

            {{-- Pie/Doughnut Chart for Suspects by Severity --}}
            <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm transition-colors">
                <h6 class="font-display font-bold text-slate-900 dark:text-white mb-6">تصنيف المشتبه بهم (حسب الخطورة)</h6>
                <div class="h-72 relative flex items-center justify-center">
                    <canvas id="suspectsPieChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Active Cases Trend (Full Width) --}}
        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm transition-colors">
            <div class="mb-6">
                <h6 class="font-display font-bold text-slate-900 dark:text-white">اتجاه الحالات النشطة (آخر 7 أيام)</h6>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mt-1">
                    مؤشر يوضح تتبع الحالات الأمنية المفتوحة وقيد المعالجة
                </p>
            </div>
            <div class="h-72 relative">
                <canvas id="activeCasesChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Secondary Modules Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 pt-2">
        <a href="{{ route('system.personal-data.fast-entry') }}" class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm hover:border-brand-500/30 transition-all flex items-center gap-4 group">
            <div class="w-12 h-12 rounded-xl bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"/></svg>
            </div>
            <div>
                <h5 class="font-display font-bold text-slate-900 dark:text-white text-base mb-0.5">إدخال أفراد سريع</h5>
                <p class="text-xs text-slate-500 dark:text-slate-400 m-0">تعبئة بيانات متعددة في صفحة واحدة</p>
            </div>
        </a>

        <a href="{{ route('configuration.villages.fast-entry') }}" class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm hover:border-brand-500/30 transition-all flex items-center gap-4 group">
            <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
            </div>
            <div>
                <h5 class="font-display font-bold text-slate-900 dark:text-white text-base mb-0.5">إدخال مناطق سريع</h5>
                <p class="text-xs text-slate-500 dark:text-slate-400 m-0">إضافة مديريات وعزل وقرى دفعة واحدة</p>
            </div>
        </a>

        <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl shrink-0">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
            <div>
                <h5 class="font-display font-bold text-slate-900 dark:text-white text-base mb-0.5">حماية وتشفير البيانات</h5>
                <p class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold m-0">النظام مؤمن ومحمي بنسبة 100%</p>
            </div>
        </div>

        <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 flex items-center justify-center text-xl shrink-0">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/><path d="M3 12c0 1.66 4 3 9 3s9-1.34 9-3"/></svg>
            </div>
            <div>
                <h5 class="font-display font-bold text-slate-900 dark:text-white text-base mb-0.5">النسخ الاحتياطي</h5>
                <p class="text-xs text-slate-500 dark:text-slate-400 m-0">قاعدة البيانات محدثة ومحمية</p>
            </div>
        </div>
    </div>

</div>

@push('scripts')
{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ─── Search History Interactivity ───
    const searchInput = document.getElementById('dashboardSearchInput');
    const searchDropdown = document.getElementById('searchHistoryDropdown');

    searchInput?.addEventListener('focus', () => searchDropdown?.classList.remove('hidden'));
    document.addEventListener('click', (e) => {
        if (!e.target.closest('#searchContainer')) {
            searchDropdown?.classList.add('hidden');
        }
    });

    window.selectSearchHistory = function(query) {
        if (searchInput) {
            searchInput.value = query;
            searchDropdown?.classList.add('hidden');
            document.getElementById('dashboardSearchForm')?.submit();
        }
    };

    // ─── Chart.js Defaults for Cairo font & Dark mode ───
    const isDark = document.documentElement.classList.contains('dark');
    const textColor = isDark ? '#94a3b8' : '#64748b';
    const gridColor = isDark ? 'rgba(148, 163, 184, 0.1)' : 'rgba(148, 163, 184, 0.15)';
    Chart.defaults.font.family = 'Cairo, sans-serif';
    Chart.defaults.color = textColor;

    // ─── 1. Reports Bar Chart (Matching AnalyticsCharts.tsx) ───
    const reportsCtx = document.getElementById('reportsChart')?.getContext('2d');
    if (reportsCtx) {
        new Chart(reportsCtx, {
            type: 'bar',
            data: {
                labels: ['السبت', 'الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة'],
                datasets: [{
                    label: 'عدد البلاغات',
                    data: [12, 19, 15, 22, 30, 25, 18],
                    backgroundColor: '#4f46e5',
                    borderRadius: 6,
                    barThickness: 28
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: { grid: { display: false } },
                    y: { grid: { color: gridColor }, beginAtZero: true }
                }
            }
        });
    }

    // ─── 2. Suspects Doughnut Chart ───
    const suspectsCtx = document.getElementById('suspectsPieChart')?.getContext('2d');
    if (suspectsCtx) {
        new Chart(suspectsCtx, {
            type: 'doughnut',
            data: {
                labels: ['حرج', 'تحذير', 'معلومة / عادي'],
                datasets: [{
                    data: [5, 12, 11],
                    backgroundColor: ['#ef4444', '#f59e0b', '#6366f1'],
                    borderWidth: 0,
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 16,
                            font: { size: 12, weight: 'bold' }
                        }
                    }
                }
            }
        });
    }

    // ─── 3. Active Cases Bar/Area Chart ───
    const activeCasesCtx = document.getElementById('activeCasesChart')?.getContext('2d');
    if (activeCasesCtx) {
        new Chart(activeCasesCtx, {
            type: 'bar',
            data: {
                labels: ['السبت', 'الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة'],
                datasets: [{
                    label: 'الحالات النشطة',
                    data: [45, 52, 38, 65, 48, 55, 40],
                    backgroundColor: '#f59e0b',
                    borderRadius: 6,
                    barThickness: 36
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: { grid: { display: false } },
                    y: { grid: { color: gridColor }, beginAtZero: true }
                }
            }
        });
    }
});
</script>
@endpush
@endsection
