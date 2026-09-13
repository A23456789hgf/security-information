@extends('layouts.app')

@section('title', 'إضافة سجل: ' . $page->name)
@section('content')
<div class="space-y-6" dir="rtl">
    <!-- Header Banner -->
    <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white flex items-center gap-3">
                <span class="w-10 h-10 rounded-2xl bg-brand-50 dark:bg-brand-900/30 text-brand-600 dark:text-brand-400 flex items-center justify-center text-lg shadow-sm">
                    <i class="fas fa-plus-circle"></i>
                </span>
                إضافة سجل: {{ $page->name }}
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm">أدخل البيانات المطلوبة أدناه، الحقول ذات <span class="text-rose-500 font-bold">*</span> إلزامية.</p>
        </div>
        <div>
            <a href="{{ route('system.dynamic-data.show', $page->slug) }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-2xl transition-all inline-flex items-center gap-2 text-sm">
                <i class="fas fa-arrow-right"></i>
                <span>العودة للقائمة</span>
            </a>
        </div>
    </div>

    <form action="{{ route('system.dynamic-data.store', $page->slug) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="bg-slate-50/80 dark:bg-slate-800/50 p-6 border-b border-slate-100 dark:border-slate-800">
                <h3 class="font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2 text-base">
                    <i class="fas fa-edit text-brand-500"></i> بيانات السجل
                </h3>
            </div>
            <div class="p-6 md:p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($page->fields as $field)
                        <div>
                            @include('fields.' . $field->type, ['field' => $field, 'values' => []])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4">
            <a href="{{ route('system.dynamic-data.show', $page->slug) }}" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-2xl transition-all text-sm">
                إلغاء
            </a>
            <button type="submit" class="px-8 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl shadow-lg shadow-emerald-500/25 transition-all flex items-center gap-2 text-sm">
                <i class="fas fa-save"></i>
                <span>حفظ السجل</span>
            </button>
        </div>
    </form>
</div>
@endsection


