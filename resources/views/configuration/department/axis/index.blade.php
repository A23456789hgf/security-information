@extends('layouts.app')

@section('title', 'المحاور الجبهوية')
@section('page_title', 'الترميزات الأساسية')

@section('content')
    <div class="space-y-6" dir="rtl">
        {{-- الرأس مع إحصائيات مختصرة --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-brand-600 rounded-3xl shadow-xl shadow-blue-100 flex items-center justify-center text-white">
                    <i class="fas fa-compass text-2xl"></i>
                </div>
                <div>
                    <h2 class="font-display font-bold text-2xl text-slate-900 dark:text-white m-0">المحاور الجبهوية</h2>
                    <p class="text-slate-500 text-sm flex items-center gap-2">
                        <i class="fas fa-database text-slate-400"></i> إجمالي السجلات: 
                        <span class="text-slate-700 font-bold">{{ $axes->total() }}</span>
                    </p>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                @include('configuration.partials._import_export_modal', ['type' => 'axes'])
                <a href="{{ route('configuration.axes.create') }}"
                    class="px-4 py-2.5 bg-brand-600 text-white rounded-xl font-bold hover:bg-brand-700 transition-all shadow-sm shadow-brand-500/30 text-xs sm:text-sm flex items-center gap-2">
                    <i class="fas fa-plus-circle"></i> إضافة محور جديد
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

        {{-- منطقة البحث --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-4 shadow-sm border border-slate-200 dark:border-slate-800 mb-6 transition-colors">
            <form method="GET" action="{{ route('configuration.axes.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                <div class="md:col-span-8 relative">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="search"
                        class="w-full pr-10 pl-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all text-slate-900 dark:text-white placeholder:text-slate-400 text-sm font-medium"
                        placeholder="ابحث عن المحور أو الدائرة..." value="{{ request('search') }}">
                </div>
                <div class="md:col-span-2">
                    @include('partials._pagination_limit')
                </div>
                <div class="md:col-span-2">
                    <button type="submit" class="w-full px-5 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-sm font-bold transition-colors">
                        تصفية النتائج
                    </button>
                </div>
            </form>
        </div>

        {{-- جدول البيانات --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden transition-colors">
            <div class="overflow-x-auto">
                <table class="w-full text-right border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800">
                            <th class="px-8 py-6 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-xs">#</th>
                            <th class="px-6 py-6 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-xs">اسم المحور</th>
                            <th class="px-6 py-6 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-xs">الدائرة / المنطقة</th>
                            <th class="px-6 py-6 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-xs text-center">تاريخ التسجيل</th>
                            <th class="px-8 py-6 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-xs text-left">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($axes as $axis)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/25 transition-colors">
                                <td class="px-8 py-5">
                                    <span class="text-slate-400 font-mono text-xs">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-soft-primary rounded-xl flex items-center justify-center font-bold text-sm border border-brand-100">
                                            {{ substr($axis->name, 0, 2) }}
                                        </div>
                                        <span class="font-bold text-slate-700">{{ $axis->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="px-3 py-1 bg-slate-50 text-slate-600 rounded-2xl text-xs font-bold border border-slate-100">
                                        {{ $axis->district->name ?? 'غير محدد' }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <span class="px-2.5 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-xl text-xs font-semibold">
                                        <i class="far fa-calendar-alt me-1"></i>
                                        {{ $axis->created_at ? $axis->created_at->format('Y-m-d') : '-' }}
                                    </span>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex justify-end items-center gap-2">
                                        <a href="{{ route('configuration.axes.edit', $axis->id) }}" class="w-9 h-9 flex items-center justify-center rounded-2xl bg-amber-100 text-amber-700 hover:bg-amber-600 hover:text-white shadow-sm transition-all" title="تعديل"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"><path d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" /><path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" /></svg></a>
                                        <form action="{{ route('configuration.axes.destroy', $axis->id) }}"
                                            method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-2xl bg-rose-100 text-rose-700 hover:bg-rose-600 hover:text-white shadow-sm transition-all" title="حذف"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"><path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75V4H5a2 2 0 00-2 2v.25C3 6.664 3.336 7 3.75 7h12.5a.75.75 0 000-1.5h-.25V4A2.75 2.75 0 0013.25 1h-4.5zM6 5.5v-1.75C6 3.224 6.448 2.75 7 2.75h6c.552 0 1 .474 1 1V5.5H6z" clip-rule="evenodd" /><path d="M4 8.25V15.5A2.75 2.75 0 006.75 18.25h6.5A2.75 2.75 0 0016 15.5V8.25H4zM7.5 10a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 017.5 10zm5.25.75a.75.75 0 00-1.5 0v4.5a.75.75 0 001.5 0v-4.5z" /></svg></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                            <i class="fas fa-search text-slate-200 text-3xl"></i>
                                        </div>
                                        <p class="text-slate-400 font-bold">لا توجد محاور مسجلة حالياً</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- الترقيم --}}
        <div class="mt-10 flex justify-center">
            {{ $axes->withQueryString()->links() }}
        </div>
    </div>
@endsection


