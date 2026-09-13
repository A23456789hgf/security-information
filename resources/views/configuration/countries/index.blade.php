@extends('layouts.app')

@section('title', 'إدارة الدول')
@section('page_title', 'الترميزات الجغرافية')

@section('content')
    <div class="space-y-6" dir="rtl">
        {{-- الرأس مع إحصائيات مختصرة --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-brand-600 rounded-3xl shadow-xl shadow-blue-100 flex items-center justify-center text-white">
                    <i class="fas fa-globe text-2xl"></i>
                </div>
                <div>
                    <h2 class="font-display font-bold text-2xl text-slate-900 dark:text-white m-0">الدول المسجلة</h2>
                    <p class="text-slate-500 text-sm flex items-center gap-2">
                        <i class="fas fa-database text-slate-400"></i> إجمالي السجلات: 
                        <span class="text-slate-700 font-bold">{{ $countries->total() }}</span>
                    </p>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('configuration.countries.create') }}"
                    class="px-4 py-2.5 bg-brand-600 text-white rounded-xl font-bold hover:bg-brand-700 transition-all shadow-sm shadow-brand-500/30 text-xs sm:text-sm flex items-center gap-2">
                    <i class="fas fa-plus-circle"></i> إضافة دولة جديدة
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
            <form method="GET" action="{{ route('configuration.countries.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
    <div class="md:col-span-8">
        <x-form-input name="search" placeholder="ابحث باسم الدولة..." :value="request('search')" />
    </div>
    <div class="md:col-span-2">
        @include('partials._pagination_limit')
    </div>
    <div class="md:col-span-2">
        <x-button type="submit">تصفية النتائج</x-button>
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
                            <th class="px-6 py-6 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-xs">اسم الدولة</th>
                            <th class="px-6 py-6 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-xs text-center">تاريخ الإضافة</th>
                            <th class="px-8 py-6 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-xs text-left">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($countries as $country)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/25 transition-colors">
                                <td class="px-8 py-5">
                                    <span class="text-slate-400 font-mono text-xs">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-soft-primary rounded-xl flex items-center justify-center font-bold text-sm border border-brand-100">
                                            {{ substr($country->name, 0, 2) }}
                                        </div>
                                        <span class="font-bold text-slate-700">{{ $country->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <span class="px-2.5 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-xl text-xs font-semibold">
                                        <i class="far fa-calendar-alt me-1"></i>
                                        {{ $country->created_at ? $country->created_at->format('Y-m-d') : '-' }}
                                    </span>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex justify-end items-center gap-2">
                                        <a href="{{ route('configuration.countries.edit', $country->id) }}" class="w-9 h-9 flex items-center justify-center rounded-2xl bg-amber-100 text-amber-700 hover:bg-amber-600 hover:text-white shadow-sm transition-all" title="تعديل"><i class="fas fa-pen text-sm"></i></a>
                                        <form action="{{ route('configuration.countries.destroy', $country->id) }}"
                                            method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-2xl bg-rose-100 text-rose-700 hover:bg-rose-600 hover:text-white shadow-sm transition-all" title="حذف"><i class="fas fa-trash text-sm"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                            <i class="fas fa-search text-slate-200 text-3xl"></i>
                                        </div>
                                        <p class="text-slate-400 font-bold">لا توجد دول مسجلة حالياً</p>
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
            {{ $countries->withQueryString()->links() }}
        </div>
    </div>
@endsection

