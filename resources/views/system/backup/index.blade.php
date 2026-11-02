@extends('layouts.app')

@section('title', 'النسخ الاحتياطي واستعادة النظام')
@section('page_title', 'أمن وصيانة النظام')

@section('content')
<div class="space-y-6" dir="rtl">
    {{-- رأس الصفحة --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10 max-w-6xl mx-auto">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-brand-600 rounded-3xl shadow-xl shadow-blue-100 flex items-center justify-center text-white">
                <i class="fas fa-shield-alt text-2xl"></i>
            </div>
            <div>
                <h2 class="font-display font-bold text-2xl text-slate-900 dark:text-white m-0">النسخ الاحتياطي واستعادة النظام</h2>
                <p class="text-slate-500 text-sm">إنشاء نسخ احتياطية مشفرة واستعادة وتحديث قواعد البيانات بأمان كامل.</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('system.backup.print') }}" target="_blank"
                class="px-5 py-3 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 transition-all shadow-xl shadow-blue-100 flex items-center gap-2 text-sm">
                <i class="fas fa-print"></i> طباعة سجل النسخ
            </a>
        </div>
    </div>

    {{-- رسائل التنبيه --}}
    @if(session('success'))
        <div class="max-w-6xl mx-auto bg-emerald-50 border border-emerald-100 text-emerald-700 px-6 py-4 rounded-2xl mb-8 flex items-center justify-between shadow-sm" role="alert">
            <div class="flex items-center gap-3">
                <i class="fas fa-check-circle text-xl"></i>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
            <button type="button" class="text-emerald-400 hover:text-emerald-600 transition-colors" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-6xl mx-auto bg-rose-50 border border-rose-100 text-rose-700 px-6 py-4 rounded-2xl mb-8 flex items-center justify-between shadow-sm" role="alert">
            <div class="flex items-center gap-3">
                <i class="fas fa-exclamation-triangle text-xl"></i>
                <span class="font-bold">{{ session('error') }}</span>
            </div>
            <button type="button" class="text-rose-400 hover:text-rose-600 transition-colors" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8">
        {{-- قسم التصدير المشفر --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden transition-colors flex flex-col justify-between">
            <div>
                <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50 flex items-center gap-3">
                    <span class="w-2 h-6 bg-brand-600 rounded-full"></span>
                    <h5 class="text-lg font-bold text-slate-800 dark:text-slate-200 header-font">تصدير البيانات (مشفر)</h5>
                </div>
                <div class="p-8 space-y-6">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-soft-primary rounded-2xl flex items-center justify-center border border-brand-100">
                            <i class="fas fa-file-export text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 dark:text-slate-200 text-base mb-1">نسخة احتياطية كاملة</h4>
                            <p class="text-slate-400 text-xs">تشفير وحفظ كامل بيانات النظام في ملف محمي</p>
                        </div>
                    </div>

                    <div class="bg-soft-primary border border-brand-100 rounded-2xl p-5 text-xs text-brand-900 leading-relaxed">
                        <div class="font-bold mb-2 flex items-center gap-2 text-brand-700">
                            <i class="fas fa-lock"></i> مواصفات التشفير والأمان:
                        </div>
                        <ul class="list-disc list-inside space-y-1.5 text-brand-800">
                            <li>تشفير عالي الأمان بمفتاح النظام الخاص (AES-256).</li>
                            <li>لا يمكن فك التشفير إلا على هذا النظام أو نظام مصرح.</li>
                            <li>حفظ ملف النسخة بصيغة مشفرة آمنة (.SQL.ENC).</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="p-8 pt-0">
                <form action="{{ route('system.backup.export') }}" method="GET">
                    <button type="submit" class="w-full py-4 px-6 bg-brand-600 hover:bg-brand-700 text-white rounded-2xl font-bold transition-all shadow-xl shadow-blue-100 flex items-center justify-center gap-2 text-sm">
                        <i class="fas fa-shield-alt"></i> تشفير وتصدير النسخة الاحتياطية
                    </button>
                </form>
            </div>
        </div>

        {{-- قسم الاستيراد وفك التشفير --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden transition-colors flex flex-col justify-between">
            <div>
                <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50 flex items-center gap-3">
                    <span class="w-2 h-6 bg-emerald-500 rounded-full"></span>
                    <h5 class="text-lg font-bold text-slate-800 dark:text-slate-200 header-font">استيراد البيانات (فك التشفير)</h5>
                </div>
                <div class="p-8 space-y-6">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center border border-emerald-100">
                            <i class="fas fa-file-import text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 dark:text-slate-200 text-base mb-1">استعادة قاعدة البيانات</h4>
                            <p class="text-slate-400 text-xs">تحديث النظام وإدراج السجلات من ملف النسخة الاحتياطية</p>
                        </div>
                    </div>

                    <div class="bg-emerald-50/60 border border-emerald-100 rounded-2xl p-5 text-xs text-emerald-900 leading-relaxed">
                        <div class="font-bold mb-1 text-emerald-800 flex items-center gap-2">
                            <i class="fas fa-sync-alt"></i> آلية المعالجة الذكية:
                        </div>
                        <p class="text-emerald-800">
                            يقوم النظام بتحديث السجلات المتطابقة وإضافة السجلات الجديدة دون حذف أي بيانات سابقة.
                        </p>
                    </div>
                </div>
            </div>

            <div class="p-8 pt-0">
                <form action="{{ route('system.backup.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <input type="file" name="backup_file" id="backup_file" class="hidden" accept=".enc,.sql" required onchange="document.getElementById('file-label-text').innerText = this.files[0].name">
                        <label for="backup_file" class="w-full border-2 border-dashed border-slate-200 hover:border-emerald-500 rounded-2xl py-6 flex flex-col items-center justify-center cursor-pointer transition-all bg-slate-50 hover:bg-emerald-50/40">
                            <i class="fas fa-cloud-upload-btnalt text-2xl text-slate-400 mb-2"></i>
                            <span id="file-label-text" class="text-slate-600 font-bold text-xs">اضغط هنا لاختيار ملف النسخة (.enc)</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full py-4 px-6 bg-slate-800 hover:bg-slate-900 text-white rounded-2xl font-bold transition-all shadow-lg shadow-slate-200 flex items-center justify-center gap-2 text-sm">
                        <i class="fas fa-unlock-alt"></i> فك التشفير واستعادة النظام
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


