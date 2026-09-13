@extends('layouts.app')

@section('title', 'استيراد: ' . $page->name)
@section('content')
<div class="space-y-6" dir="rtl">
    <!-- Header Banner -->
    <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white flex items-center gap-3">
                <span class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-lg shadow-sm">
                    <i class="fas fa-file-import"></i>
                </span>
                استيراد بيانات: {{ $page->name }}
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm">
                ارفع ملف Excel أو CSV لإضافة سجلات جديدة لهذه الصفحة.
                <a href="{{ route('system.dynamic-data.template', $page->slug) }}" class="text-emerald-600 dark:text-emerald-400 font-bold hover:underline inline-flex items-center gap-1 ms-2">
                    <i class="fas fa-download text-xs"></i>
                    <span>تحميل نموذج الملف (Excel)</span>
                </a>
            </p>
        </div>
        <div>
            <a href="{{ route('system.dynamic-data.show', $page->slug) }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-2xl transition-all inline-flex items-center gap-2 text-sm">
                <i class="fas fa-arrow-right"></i>
                <span>العودة للقائمة</span>
            </a>
        </div>
    </div>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="bg-slate-50/80 dark:bg-slate-800/50 p-6 border-b border-slate-100 dark:border-slate-800">
                <h3 class="font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2 text-base">
                    <i class="fas fa-upload text-emerald-500"></i> رفع ملف الاستيراد
                </h3>
            </div>
            <div class="p-6 md:p-8 text-center">
                <form action="{{ route('system.dynamic-data.upload', $page->slug) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div class="border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-3xl p-8 bg-slate-50/50 dark:bg-slate-950/50 space-y-3">
                        <div class="w-16 h-16 rounded-2xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-3xl mx-auto">
                            <i class="fas fa-cloud-upload-btnalt"></i>
                        </div>
                        <input type="file" name="file" class="w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2.5 file:px-5 file:rounded-2xl file:border-0 file:text-xs file:font-bold file:bg-brand-50 file:text-brand-700 dark:file:bg-brand-900/30 dark:file:text-brand-300 hover:file:bg-brand-100 p-2 border border-slate-200 dark:border-slate-800 rounded-2xl bg-white dark:bg-slate-900" required accept=".xlsx,.xls,.csv">
                        <p class="text-xs text-slate-400">تأكد من وجود رؤوس أعمدة مطابقة في الصف الأول من الملف.</p>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <a href="{{ route('system.dynamic-data.show', $page->slug) }}" class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-2xl transition-all text-xs">إلغاء</a>
                        <button type="submit" class="px-8 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl shadow-lg shadow-emerald-500/25 transition-all flex items-center gap-2 text-xs">
                            <span>رفع ومعاينة البيانات</span>
                            <i class="fas fa-chevron-left text-[10px]"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


