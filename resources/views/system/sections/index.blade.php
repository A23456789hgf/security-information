@extends('layouts.app')

@section('title', 'إدارة الأقسام الإدارية')
@section('page_title', 'إدارة النظام')

@section('content')
<div class="space-y-6" dir="rtl">
    {{-- رأس الصفحة مع الإحصائيات --}}
    <div class="flex flex-col md:flex-flex flex-wrap gap-4 md:items-center justify-between gap-6 mb-10">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-brand-600 rounded-3xl shadow-xl shadow-blue-100 flex items-center justify-center text-white">
                <i class="fas fa-layer-group text-2xl"></i>
            </div>
            <div>
                <h2 class="font-display font-bold text-2xl text-slate-900 dark:text-white m-0">الأقسام الإدارية</h2>
                <p class="text-slate-500 text-sm flex items-center gap-2">
                    <i class="fas fa-database text-slate-400"></i> إجمالي الأقسام:
                    <span class="text-slate-700 font-bold">{{ $sections->count() }}</span>
                </p>
            </div>
        </div>
        <div class="flex flex-wrap gap-3">
            <x-print-button :url="route('system.sections.print')" text="طباعة الكشف" />
            <a href="{{ route('system.sections.create') }}"
                class="px-4 py-2.5 bg-brand-600 text-white rounded-xl font-bold hover:bg-brand-700 transition-all shadow-sm shadow-brand-500/30 text-xs sm:text-sm flex items-center gap-2 text-sm">
                <i class="fas fa-plus-circle"></i> إضافة قسم جديد
            </a>
        </div>
    </div>

    {{-- رسائل النظام --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-6 py-4 rounded-2xl mb-8 flex items-center justify-between shadow-sm" role="alert">
            <div class="flex items-center gap-3">
                <i class="fas fa-check-circle text-xl"></i>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
            <button type="button" class="text-emerald-400 hover:text-emerald-600 transition-colors" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    {{-- جدول البيانات --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden transition-colors">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800">
                        <th class="px-8 py-6 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-xs" style="width: 80px;">الترتيب</th>
                        <th class="px-6 py-6 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-xs">القسم</th>
                        <th class="px-6 py-6 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-xs">الأيقونة</th>
                        <th class="px-6 py-6 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-xs text-center">الصفحات التابعة</th>
                        <th class="px-6 py-6 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-xs text-center">الحالة</th>
                        <th class="px-8 py-6 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-xs text-left">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($sections as $section)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/25 transition-colors">
                            <td class="px-8 py-5">
                                <span class="w-8 h-8 rounded-2xl bg-slate-100 border border-slate-200 text-slate-700 font-bold text-xs flex items-center justify-center">
                                    {{ $section->order_column }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <span class="font-bold text-slate-800 dark:text-slate-200 block">{{ $section->name }}</span>
                                <span class="text-[10px] text-slate-400 font-mono">{{ $section->slug }}</span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="w-10 h-10 bg-soft-primary rounded-xl flex items-center justify-center border border-brand-100">
                                    <i class="fas {{ $section->icon }} text-base"></i>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span class="px-3 py-1 bg-soft-primary rounded-2xl text-xs font-bold border border-brand-100">
                                    {{ $section->pages_count }} صفحة
                                </span>
                            </td>
                            <td class="px-6 py-5 text-center">
                                @if($section->is_active)
                                    <span class="px-3 py-1 bg-emerald-50 text-emerald-700 rounded-2xl text-[10px] font-bold border border-emerald-100 inline-flex items-center gap-1">
                                        <i class="fas fa-check-circle text-[8px]"></i> نشط
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-rose-50 text-rose-700 rounded-2xl text-[10px] font-bold border border-rose-100 inline-flex items-center gap-1">
                                        <i class="fas fa-times-circle text-[8px]"></i> متوقف
                                    </span>
                                @endif
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex justify-end items-center gap-2">
                                    <a href="{{ route('system.sections.edit', $section) }}" class="w-9 h-9 flex items-center justify-center rounded-xl bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white shadow-sm transition-all" title="تعديل"><i class="fas fa-pen text-xs"></i></a>
                                    <form action="{{ route('system.sections.destroy', $section) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white shadow-sm transition-all {{ $section->pages_count > 0 ? 'opacity-40 cursor-not-allowed' : '' }}" title="حذف" {{ $section->pages_count > 0 ? 'disabled' : '' }}><i class="fas fa-trash text-xs"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-search text-slate-200 text-3xl"></i>
                                    </div>
                                    <p class="text-slate-400 font-bold">لا توجد أقسام مسجلة حالياً</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


