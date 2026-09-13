@extends('layouts.app')

@section('title', 'إضافة دور جديد')
@section('page_title', 'إدارة النظام')

@section('content')
<div class="max-w-6xl mx-auto space-y-6" dir="rtl">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white flex items-center gap-3">
                <div class="p-2.5 bg-brand-500/10 text-brand-600 dark:text-brand-400 rounded-xl">
                    <i class="fas fa-shield-alt text-xl"></i>
                </div>
                إضافة دور وصلاحيات
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                تحديد اسم الدور ومصفوفة الصلاحيات ونطاقات الوصول الممنوحة له في النظام.
            </p>
        </div>
        <a href="{{ route('system.roles.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-sm font-semibold rounded-xl transition-all shadow-sm">
            <i class="fas fa-arrow-right"></i>
            عودة للقائمة
        </a>
    </div>

    @if($errors->any())
        <div class="p-4 bg-rose-500/10 border border-rose-500/20 rounded-xl text-rose-600 dark:text-rose-400">
            <div class="flex items-start gap-3">
                <i class="fas fa-exclamation-triangle text-lg mt-0.5"></i>
                <div>
                    <span class="font-bold block mb-1">يرجى تصحيح الأخطاء التالية:</span>
                    <ul class="list-disc list-inside text-sm space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('system.roles.store') }}" method="POST" class="space-y-6">
        @csrf
        
        {{-- Role Name bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden mb-4 --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
            <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                <span class="w-2 h-5 bg-brand-600 rounded-full"></span>
                البيانات الأساسية للدور
            </h2>
            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">اسم الدور <span class="text-rose-500">*</span></label>
                <input type="text" name="name" required
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all @error('name') border-rose-500 @enderror"
                    placeholder="مثلاً: مشرف أمني، مدخل بيانات، مدير تقارير..." value="{{ old('name') }}">
                @error('name')
                    <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Permissions Matrix bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden mb-4 --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
            <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                <span class="w-2 h-5 bg-purple-600 rounded-full"></span>
                مصفوفة الصلاحيات والعمليات
            </h2>
            <div>
                @include('system.roles.partials._permissions')
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-800">
            <a href="{{ route('system.roles.index') }}"
                class="px-5 py-2.5 text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:text-slate-200 dark:hover:text-slate-200 transition-colors">
                إلغاء
            </a>
            <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-sm font-semibold shadow-sm hover:shadow transition-all">
                <i class="fas fa-check"></i>
                حفظ الدور والصلاحيات
            </button>
        </div>
    </form>
</div>
@endsection

