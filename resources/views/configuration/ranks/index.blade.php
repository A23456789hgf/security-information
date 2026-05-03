@extends('layouts.app')

@section('title', 'الرتب العسكرية')
@section('page_title', 'الترميزات الأساسية')

@section('content')
    <div class="container-fluid mx-auto px-4 py-8" dir="rtl">
        {{-- الرأس مع إحصائيات مختصرة --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-blue-600 rounded-3xl shadow-xl shadow-blue-100 flex items-center justify-center text-white">
                    <i class="fas fa-medal text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-800 header-font mb-1">الرتب العسكرية</h2>
                    <p class="text-slate-500 text-sm flex items-center gap-2">
                        <i class="fas fa-database text-slate-400"></i> إجمالي السجلات: 
                        <span class="text-slate-700 font-bold">{{ $ranks->total() }}</span>
                    </p>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('configuration.ranks.create') }}"
                    class="px-6 py-3 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 transition-all shadow-xl shadow-blue-100 flex items-center gap-2">
                    <i class="fas fa-plus-circle"></i> إضافة رتبة جديدة
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
        <div class="bg-white rounded-[2rem] p-4 soft-shadow border border-slate-100 mb-8">
            <form method="GET" action="{{ route('configuration.ranks.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                <div class="md:col-span-8 relative">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="search"
                        class="w-full pl-12 pr-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all text-slate-600 font-bold"
                        placeholder="ابحث باسم الرتبة..." value="{{ request('search') }}">
                </div>
                <div class="md:col-span-2">
                    @include('partials._pagination_limit')
                </div>
                <div class="md:col-span-2">
                    <button type="submit" class="w-full py-3 bg-slate-800 text-white rounded-2xl font-bold hover:bg-slate-900 transition-all shadow-lg shadow-slate-200">
                        تصفية النتائج
                    </button>
                </div>
            </form>
        </div>

        {{-- جدول البيانات --}}
        <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-right border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-8 py-6 text-slate-400 font-bold uppercase tracking-widest text-[10px]">#</th>
                            <th class="px-6 py-6 text-slate-400 font-bold uppercase tracking-widest text-[10px]">الرتبة</th>
                            <th class="px-6 py-6 text-slate-400 font-bold uppercase tracking-widest text-[10px] text-center">تاريخ التسجيل</th>
                            <th class="px-8 py-6 text-slate-400 font-bold uppercase tracking-widest text-[10px] text-left">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($ranks as $rank)
                            <tr class="group hover:bg-slate-50/30 transition-colors">
                                <td class="px-8 py-5">
                                    <span class="text-slate-400 font-mono text-xs">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center font-bold text-sm border border-blue-100">
                                            <i class="fas fa-star text-xs"></i>
                                        </div>
                                        <span class="font-bold text-slate-700">{{ $rank->rank }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <span class="px-3 py-1 bg-slate-100 text-slate-500 rounded-lg text-[10px] font-bold">
                                        <i class="far fa-calendar-alt me-1"></i>
                                        {{ $rank->created_at ? $rank->created_at->format('Y-m-d') : '-' }}
                                    </span>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex justify-end items-center gap-2">
                                        <a href="{{ route('configuration.ranks.edit', $rank->id) }}" class="w-9 h-9 flex items-center justify-center rounded-lg bg-amber-100 text-amber-700 hover:bg-amber-600 hover:text-white shadow-sm transition-all" title="تعديل"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"><path d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" /><path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" /></svg></a>
                                        <form action="{{ route('configuration.ranks.destroy', $rank->id) }}"
                                            method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-lg bg-rose-100 text-rose-700 hover:bg-rose-600 hover:text-white shadow-sm transition-all" title="حذف"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"><path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75V4H5a2 2 0 00-2 2v.25C3 6.664 3.336 7 3.75 7h12.5a.75.75 0 000-1.5h-.25V4A2.75 2.75 0 0013.25 1h-4.5zM6 5.5v-1.75C6 3.224 6.448 2.75 7 2.75h6c.552 0 1 .474 1 1V5.5H6z" clip-rule="evenodd" /><path d="M4 8.25V15.5A2.75 2.75 0 006.75 18.25h6.5A2.75 2.75 0 0016 15.5V8.25H4zM7.5 10a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 017.5 10zm5.25.75a.75.75 0 00-1.5 0v4.5a.75.75 0 001.5 0v-4.5z" /></svg></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                            <i class="fas fa-medal text-slate-200 text-3xl"></i>
                                        </div>
                                        <p class="text-slate-400 font-bold">لا توجد رتب مسجلة حالياً</p>
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
            {{ $ranks->withQueryString()->links() }}
        </div>
    </div>
@endsection
