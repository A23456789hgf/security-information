@extends('layouts.app')

@section('title', 'مطابقة الحقول: ' . $page->name)
@section('content')
<div class="space-y-6" dir="rtl">
    <!-- Header Banner -->
    <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col md:flex-flex flex-wrap gap-4 md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white flex items-center gap-3">
                <span class="w-10 h-10 rounded-2xl bg-brand-50 dark:bg-brand-900/30 text-brand-600 dark:text-brand-400 flex items-center justify-center text-lg shadow-sm">
                    <i class="fas fa-project-diagram"></i>
                </span>
                مطابقة أعمدة الملف: {{ $page->name }}
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm">اختر العمود المناسب من ملف الرفع لكل حقل من حقول الصفحة.</p>
        </div>
        <div>
            <a href="{{ route('system.dynamic-data.import', $page->slug) }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-2xl transition-all inline-flex items-center gap-2 text-sm">
                <i class="fas fa-arrow-right"></i>
                <span>تغيير الملف</span>
            </a>
        </div>
    </div>

    <form action="{{ route('system.dynamic-data.import-store', $page->slug) }}" method="POST" class="space-y-6">
        @csrf
        
        <!-- Mapping Card -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="bg-slate-50/80 dark:bg-slate-800/50 p-6 border-b border-slate-100 dark:border-slate-800">
                <h3 class="font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2 text-base">
                    <i class="fas fa-link text-brand-500"></i> خريطة مطابقة الحقول
                </h3>
            </div>
            <div class="p-0 overflow-x-auto">
                <table class="w-full text-right border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800 text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                            <th class="py-3.5 px-6" style="width: 40%;">حقل الصفحة الرسمي</th>
                            <th class="py-3.5 px-6">العمود المختار من الملف</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                        @foreach($page->fields as $field)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="py-3.5 px-6 font-bold text-slate-900 dark:text-white">{{ $field->label }}</td>
                            <td class="py-3.5 px-6">
                                <select name="mapping[{{ $field->id }}]" class="w-full max-w-md px-4 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-xs focus:ring-2 focus:ring-brand-500/20 text-slate-800 dark:text-slate-200">
                                    <option value="">-- تجاهل هذا الحقل --</option>
                                    @foreach($headers as $index => $header)
                                        <option value="{{ $index }}">{{ $header }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Preview Card -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="bg-slate-50/80 dark:bg-slate-800/50 p-6 border-b border-slate-100 dark:border-slate-800">
                <h3 class="font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2 text-base">
                    <i class="fas fa-eye text-brand-500"></i> معاينة البيانات (أول 5 صفوف)
                </h3>
            </div>
            <div class="p-0 overflow-x-auto">
                <table class="w-full text-right border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/80 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800 text-slate-400 font-bold uppercase">
                            @foreach($headers as $header)
                                <th class="py-3 px-4">{{ $header }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                        @foreach($preview as $row)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                            @foreach($row as $cell)
                                <td class="py-2.5 px-4">{{ $cell }}</td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4">
            <button type="submit" class="px-8 py-3.5 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-2xl shadow-lg shadow-brand-500/25 transition-all flex items-center gap-2 text-sm">
                <span>بدء عملية الاستيراد</span>
                <i class="fas fa-play text-xs"></i>
            </button>
        </div>
    </form>
</div>
@endsection


