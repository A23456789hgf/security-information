@extends('layouts.app')

@section('title', 'إدارة فصائل الدم')
@section('page_title', 'الترميزات الأساسية')

@section('content')
    <div class="container-fluid mx-auto px-4 py-8" dir="rtl">
        {{-- الرأس مع إحصائيات مختصرة --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-rose-600 rounded-3xl shadow-xl shadow-rose-100 flex items-center justify-center text-white">
                    <i class="fas fa-tint text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-800 header-font mb-1">فصائل الدم</h2>
                    <p class="text-slate-500 text-sm flex items-center gap-2">
                        <i class="fas fa-database text-slate-400"></i> إجمالي السجلات: 
                        <span class="text-slate-700 font-bold">{{ $bloodTypes->total() }}</span>
                    </p>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                @include('configuration.partials._import_export_modal', ['type' => 'blood-types'])
                <a href="{{ route('configuration.blood-types.create') }}"
                    class="px-6 py-3 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 transition-all shadow-xl shadow-blue-100 flex items-center gap-2">
                    <i class="fas fa-plus-circle"></i> إضافة فصيلة جديدة
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
            <form method="GET" action="{{ route('configuration.blood-types.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                <div class="md:col-span-8 relative">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="search"
                        class="w-full pl-12 pr-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all text-slate-600 font-bold"
                        placeholder="ابحث عن فصيلة (مثلاً: A+)..." value="{{ request('search') }}">
                </div>
                <div class="md:col-span-2">
                    @include('partials._pagination_limit')
                </div>
                <div class="md:col-span-2">
                    <button type="submit" class="w-full py-3 bg-slate-800 text-white rounded-2xl font-bold hover:bg-slate-900 transition-all shadow-lg shadow-slate-200">
                        تصفية النتائج
                    </button>
                </div>
                @if(request('search'))
                    <div class="md:col-span-12 px-2">
                        <a href="{{ route('configuration.blood-types.index') }}" class="text-xs text-slate-400 hover:text-slate-600 flex items-center gap-1 transition-colors">
                            <i class="fas fa-times-circle"></i> مسح البحث
                        </a>
                    </div>
                @endif
            </form>
        </div>

        {{-- جدول البيانات - مع إزالة أي overflow hidden --}}
        <div class="bg-white rounded-3xl soft-shadow border border-slate-100" style="overflow: visible !important;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                    <thead class="bg-slate-50/80 border-b border-slate-100">
                        <tr>
                            <th class="ps-6 py-4 text-slate-400 font-bold uppercase tracking-wider text-[10px]" style="width: 80px;">الرقم</th>
                            <th class="py-4 text-slate-400 font-bold uppercase tracking-wider text-[10px]">الفصيلة</th>
                            <th class="py-4 text-slate-400 font-bold uppercase tracking-wider text-[10px] text-center">تاريخ التسجيل</th>
                            <th class="pe-6 py-4 text-slate-400 font-bold uppercase tracking-wider text-[10px] text-end">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($bloodTypes as $bloodType)
                            <tr class="group hover:bg-slate-50/50 transition-colors">
                                <td class="ps-6 py-4">
                                    <span class="text-slate-400 font-mono text-[10px]">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center font-bold text-sm border border-rose-100">
                                            {{ $bloodType->type }}
                                        </div>
                                        <span class="font-bold text-slate-700">{{ $bloodType->type }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <span class="px-3 py-1 bg-slate-100 text-slate-500 rounded-lg text-[10px] font-bold">
                                        <i class="far fa-calendar-alt me-1"></i>
                                        {{ $bloodType->created_at ? $bloodType->created_at->format('Y-m-d') : '-' }}
                                    </span>
                                </td>
                                <td class="pe-6 py-3 text-end">
                                    <div class="flex justify-end items-center gap-2">
                                        <a href="{{ route('configuration.blood-types.edit', $bloodType->id) }}" class="w-9 h-9 flex items-center justify-center rounded-lg bg-amber-100 text-amber-700 hover:bg-amber-600 hover:text-white shadow-sm transition-all" title="تعديل"><i class="fas fa-pen text-sm"></i></a>
                                        <form action="{{ route('configuration.blood-types.destroy', $bloodType->id) }}"
                                            method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-lg bg-rose-100 text-rose-700 hover:bg-rose-600 hover:text-white shadow-sm transition-all" title="حذف"><i class="fas fa-trash text-sm"></i></button>
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
                                        <p class="text-slate-400 font-bold">لا توجد فصائل دم مسجلة حالياً</p>
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
            {{ $bloodTypes->withQueryString()->links() }}
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .pagination { @apply flex gap-2; }
        .page-item .page-link { @apply border-0 rounded-xl text-slate-500 font-bold text-sm px-4 py-2 hover:bg-slate-100 transition-all shadow-sm bg-white; }
        .page-item.active .page-link { @apply bg-blue-600 text-white shadow-xl shadow-blue-100; }
    </style>
@endpush