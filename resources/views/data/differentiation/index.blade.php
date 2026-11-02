@extends('layouts.app')

@section('title', 'مطابقة ومقارنة البيانات - Differentiation')

@section('content')
<div class="diff-container space-y-6" dir="rtl">

    @include('data.differentiation.partials._styles')

    {{-- Breadcrumbs & Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-brand-600 transition-colors">الرئيسية</a>
                <span>/</span>
                <span class="text-slate-900 dark:text-white font-semibold">مطابقة ومقارنة البيانات</span>
            </nav>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white flex items-center gap-3">
                <span class="p-2 bg-brand-500/10 text-brand-600 dark:text-brand-400 rounded-xl">
                    <i class="fas fa-code-compare text-xl"></i>
                </span>
                نظام مطابقة ومقارنة البيانات الشامل
            </h1>
        </div>

        <div class="flex items-center gap-3">
            <button onclick="openUploadModal('secondary')" class="bg-brand-600 hover:bg-brand-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-sm flex items-center gap-2 transition-all">
                <i class="fas fa-plus"></i>
                <span>إضافة مجموعة ثانوية جديد</span>
            </button>

            <button onclick="openUploadModal('main')" class="bg-slate-800 hover:bg-slate-900 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-sm flex items-center gap-2 transition-all dark:bg-slate-700 dark:hover:bg-slate-600">
                <i class="fas fa-database"></i>
                <span>استيراد بيانات رئيسية</span>
            </button>
        </div>
    </div>

    {{-- Navigation Tabs --}}
    <div class="diff-card p-2 flex flex-wrap gap-2">
        <button class="diff-tab-btn active" id="tabBtnSecondary" onclick="switchTab('secondary')">
            <i class="fas fa-layer-group"></i>
            <span>مجموعات البيانات الثانوية</span>
            <span class="stat-badge bg-brand-500/10 text-brand-600 dark:text-brand-400" id="secondaryCountBadge">{{ count($secondaryDatasets) }}</span>
        </button>

        <button class="diff-tab-btn" id="tabBtnMain" onclick="switchTab('main')">
            <i class="fas fa-database"></i>
            <span>البيانات الرئيسية المسجلة</span>
            <span class="stat-badge bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">{{ count($mainData) }} سجل</span>
        </button>

        <button class="diff-tab-btn" id="tabBtnComparison" onclick="switchTab('comparison')">
            <i class="fas fa-sliders"></i>
            <span>معالج المطابقة والمقارنة</span>
        </button>

        <button class="diff-tab-btn" id="tabBtnLogs" onclick="switchTab('logs')">
            <i class="fas fa-history"></i>
            <span>سجل العمليات والتدقيق</span>
        </button>
    </div>

    {{-- Main Content Views --}}
    @include('data.differentiation.partials._main_content')

    {{-- Modals --}}
    @include('data.differentiation.partials._modals')

</div>

@push('scripts')
    @include('data.differentiation.partials._scripts')
@endpush
@endsection
