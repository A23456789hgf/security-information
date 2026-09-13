@extends('layouts.app')

@section('title', 'خطأ في الخادم (500)')

@section('content')
<div class="min-h-[60vh] flex flex-col items-center justify-center text-center p-6" dir="rtl">
    <div class="w-20 h-20 rounded-3xl bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 flex items-center justify-center mb-6 shadow-lg shadow-red-500/20">
        <svg class="w-10 h-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
    </div>
    <h1 class="font-display font-bold text-3xl text-slate-900 dark:text-white mb-2">500 — حدث خطأ غير متوقع</h1>
    <p class="text-sm text-slate-500 dark:text-slate-400 max-w-md mb-8">
        واجه النظام مشكلة مؤقتة أثناء معالجة الطلب. يرجى المحاولة مرة أخرى أو مراجعة مسؤول النظام.
    </p>
    <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm rounded-xl transition-all shadow-lg shadow-brand-500/30 inline-flex items-center gap-2">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        <span>العودة للرئيسية</span>
    </a>
</div>
@endsection
