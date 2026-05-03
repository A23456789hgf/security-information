@extends('layouts.app')

@section('title', 'إدارة الأقسام الإدارية')
@section('content')
<div class="container-fluid py-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-blue-600 rounded-3xl shadow-xl shadow-blue-100 flex items-center justify-center text-white">
                <i class="fas fa-layer-group text-2xl"></i>
            </div>
            <div>
                <h2 class="text-3xl font-extrabold text-slate-800 header-font mb-1">إدارة الأقسام الإدارية</h2>
                <p class="text-slate-500 text-sm flex items-center gap-2">
                    قم بتنظيم الصفحات والنماذج تحت مسميات إدارية واضحة
                    <span class="inline-flex items-center justify-center bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full text-xs font-bold">
                        {{ $sections->count() }} قسم
                    </span>
                </p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('system.sections.create') }}"
                class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-bold transition-all shadow-lg shadow-blue-100">
                <i class="fas fa-plus"></i>
                <span>إضافة قسم جديد</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Main Content Card -->
    <div class="bg-white rounded-3xl soft-shadow border border-slate-100" style="overflow: visible !important;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                <thead class="bg-slate-50/80 border-b border-slate-100">
                    <tr>
                        <th class="ps-6 py-4 text-slate-400 font-bold uppercase tracking-wider text-[10px]" style="width: 80px;">الترتيب</th>
                        <th class="py-4 text-slate-400 font-bold uppercase tracking-wider text-[10px]">القسم</th>
                        <th class="py-4 text-slate-400 font-bold uppercase tracking-wider text-[10px]">الأيقونة</th>
                        <th class="text-center py-4 text-slate-400 font-bold uppercase tracking-wider text-[10px]">عدد الصفحات</th>
                        <th class="text-center py-4 text-slate-400 font-bold uppercase tracking-wider text-[10px]">الحالة</th>
                        <th class="text-center pe-6 py-4 text-slate-400 font-bold uppercase tracking-wider text-[10px]" style="width: 150px;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($sections as $section)
                        <tr>
                            <td class="ps-6 py-4">
                                <span class="inline-flex items-center justify-center bg-slate-100 text-slate-700 w-8 h-8 rounded-lg border border-slate-200 font-bold text-xs">{{ $section->order_column }}</span>
                            </td>
                            <td class="py-4">
                                <div class="fw-bold text-slate-700">{{ $section->name }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">{{ $section->slug }}</div>
                            </td>
                            <td class="py-4">
                                <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-500 border border-slate-100">
                                    <i class="fas {{ $section->icon }} fa-lg"></i>
                                </div>
                            </td>
                            <td class="text-center py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $section->pages_count }} صفحة
                                </span>
                            </td>
                            <td class="text-center py-4">
                                @if($section->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">نشط</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800">متوقف</span>
                                @endif
                            </td>
                            <td class="text-center pe-6 py-4">
                                <div class="flex justify-end items-center gap-2">
                                    <a href="{{ route('system.sections.edit', $section) }}" class="w-9 h-9 flex items-center justify-center rounded-lg bg-amber-100 text-amber-700 hover:bg-amber-600 hover:text-white shadow-sm transition-all" title="تعديل">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                            <path d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" />
                                            <path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('system.sections.destroy', $section) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا القسم؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-lg bg-rose-100 text-rose-700 hover:bg-rose-600 hover:text-white shadow-sm transition-all" title="حذف" {{ $section->pages_count > 0 ? 'disabled' : '' }}>
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                                <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75V4H5a2 2 0 00-2 2v.25C3 6.664 3.336 7 3.75 7h12.5a.75.75 0 000-1.5h-.25V4A2.75 2.75 0 0013.25 1h-4.5zM6 5.5v-1.75C6 3.224 6.448 2.75 7 2.75h6c.552 0 1 .474 1 1V5.5H6z" clip-rule="evenodd" />
                                                <path d="M4 8.25V15.5A2.75 2.75 0 006.75 18.25h6.5A2.75 2.75 0 0016 15.5V8.25H4zM7.5 10a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 017.5 10zm5.25.75a.75.75 0 00-1.5 0v4.5a.75.75 0 001.5 0v-4.5z" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-10">
                                <div class="text-slate-400">
                                    <i class="fas fa-layer-group fa-3x mb-3 opacity-20"></i>
                                    <p class="header-font text-lg">لا توجد أقسام مضافة حالياً</p>
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
