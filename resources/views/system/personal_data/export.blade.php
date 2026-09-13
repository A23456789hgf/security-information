@extends('layouts.app')

@section('title', 'تصدير البيانات الشخصية')
@section('page_title', 'السجلات الأساسية')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto" dir="rtl">
    {{-- Breadcrumbs --}}
    <nav class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-2 print:hidden">
        <a href="{{ route('dashboard') }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">الرئيسية</a>
        <span>/</span>
        <a href="{{ route('system.personal-data.index') }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">إدارة البيانات الشخصية</a>
        <span>/</span>
        <span class="text-slate-900 dark:text-white font-semibold">تصدير البيانات</span>
    </nav>

    {{-- Header --}}
    <div class="flex items-center justify-between gap-4 mb-4">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold shadow-sm shrink-0">
                <i class="fas fa-file-excel text-xl"></i>
            </div>
            <div>
                <h1 class="font-display font-bold text-2xl text-slate-900 dark:text-white m-0">تصدير البيانات الشخصية</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">اختر المعايير التي تريد تصفية البيانات بها قبل تصديرها إلى ملف Excel.</p>
            </div>
        </div>
        <a href="{{ route('system.personal-data.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 font-bold text-sm rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors shadow-sm">
            <i class="fas fa-arrow-right"></i>
            <span>عودة للقائمة</span>
        </a>
    </div>

    {{-- Form bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden mb-4 --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 md:p-8 transition-colors">
        <form action="{{ route('system.personal-data.export-download') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">بحث عام (الاسم / رقم الهوية / الرقم العسكري)</label>
                <input type="text" name="search"
                    class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all"
                    placeholder="اتركه فارغاً لتصدير جميع السجلات...">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">المحافظة</label>
                    <select name="governorate_id" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all" id="gov_id">
                        <option value="">كل المحافظات</option>
                        @foreach(\App\Models\Governorate::all() as $gov)
                            <option value="{{ $gov->id }}">{{ $gov->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">صيغة الملف</label>
                    <input type="text" value="ملف Excel القياسي (.xlsx)" disabled
                        class="w-full bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-500 dark:text-slate-400 cursor-not-allowed">
                </div>
            </div>

            {{-- Warning Banner matching tem --}}
            <div class="p-4 rounded-xl bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 flex items-start gap-3">
                <i class="fas fa-shield-alt text-amber-600 dark:text-amber-400 text-lg mt-0.5 shrink-0"></i>
                <div class="text-xs">
                    <strong class="block font-bold text-amber-900 dark:text-amber-300 mb-0.5">تنبيه سرية وخصوصية</strong>
                    <p class="text-amber-800 dark:text-amber-400 m-0">يرجى العلم أن الملف المصدر يحتوي على بيانات شخصية وحساسة. تعامل معه بحذر والتزم بسياسات وإجراءات الأمن المعتمدة.</p>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-800">
                <a href="{{ route('system.personal-data.index') }}"
                    class="px-6 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 font-semibold transition-colors text-sm">
                    إلغاء
                </a>
                <button type="submit"
                    class="px-8 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold shadow-sm shadow-emerald-500/30 transition-all text-sm flex items-center gap-2">
                    <i class="fas fa-file-download"></i>
                    <span>توليد وتنزيل ملف Excel</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

