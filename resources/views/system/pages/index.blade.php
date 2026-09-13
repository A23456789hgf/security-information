@extends('layouts.app')

@section('title', 'إدارة الصفحات المخصصة')

@section('content')
<div class="container-fluid py-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-flex flex-wrap gap-4 md:items-center justify-between gap-6 mb-10">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-brand-600 rounded-3xl shadow-xl shadow-blue-100 flex items-center justify-center text-white">
                <i class="fas fa-layer-group text-2xl"></i>
            </div>
            <div>
                <h2 class="font-display font-bold text-2xl text-slate-900 dark:text-white m-0">إدارة الصفحات المخصصة</h2>
                <p class="text-slate-500 text-sm flex items-center gap-2">
                    قم بإنشاء وتعديل الصفحات الديناميكية للنظام
                    <span class="inline-flex items-center justify-center bg-brand-100 text-brand-700 px-2 py-0.5 rounded-full text-xs font-bold">
                        {{ $pages->count() }} صفحة
                    </span>
                </p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('system.pages.create') }}"
                class="flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white px-6 py-3 rounded-2xl font-bold transition-all shadow-lg shadow-blue-100">
                <i class="fas fa-plus"></i>
                <span>إضافة صفحة جديدة</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl px-6 py-4 flex items-center border-0 shadow-sm rounded-4 mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Main Content bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden mb-4 -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table  align-middle mb-0" style="font-size: 0.85rem;">
                <thead class="bg-slate-50/80 border-b border-slate-100">
                    <tr>
                        <th class="ps-6 py-4 text-slate-400 font-bold uppercase tracking-wider text-[10px]">اسم الصفحة</th>
                        <th class="py-4 text-slate-400 font-bold uppercase tracking-wider text-[10px]">الرابط (Slug)</th>
                        <th class="py-4 text-slate-400 font-bold uppercase tracking-wider text-[10px]">عدد الحقول</th>
                        <th class="py-4 text-slate-400 font-bold uppercase tracking-wider text-[10px]">الحالة</th>
                        <th class="text-center pe-6 py-4 text-slate-400 font-bold uppercase tracking-wider text-[10px]" style="width: 200px;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($pages as $page)
                    <tr>
                        <td class="ps-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-soft-primary rounded-xl flex items-center justify-center border border-brand-100">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <span class="fw-bold text-slate-700">{{ $page->name }}</span>
                            </div>
                        </td>
                        <td class="py-4"><code class="text-brand-600 bg-soft-primary px-2 py-0.5 rounded text-xs">{{ $page->slug }}</code></td>
                        <td class="py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800 dark:text-slate-200">
                                {{ $page->fields_count }} حقل
                            </span>
                        </td>
                        <td class="py-4">
                            @if($page->is_active)
                                <span class="inline-flex items-center px-2.5 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-bold border border-emerald-100">
                                    <i class="fas fa-check-circle me-1 opacity-50"></i> نشط
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 bg-rose-50 text-rose-600 rounded-full text-[10px] font-bold border border-rose-100">
                                    <i class="fas fa-times-circle me-1 opacity-50"></i> معطل
                                </span>
                            @endif
                        </td>
                        <td class="text-center pe-6 py-4">
                            <div class="flex justify-end items-center gap-2">
                                <a href="{{ route('system.dynamic-data.show', $page->slug) }}" class="w-9 h-9 flex items-center justify-center rounded-2xl bg-brand-100 text-brand-700 hover:bg-brand-600 hover:text-white shadow-sm transition-all" title="فتح الصفحة">
                                    <i class="fas fa-external-link-alt text-xs"></i>
                                </a>
                                <a href="{{ route('system.pages.edit', $page) }}" class="w-9 h-9 flex items-center justify-center rounded-2xl bg-amber-100 text-amber-700 hover:bg-amber-600 hover:text-white shadow-sm transition-all" title="تعديل">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                        <path d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" />
                                        <path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" />
                                    </svg>
                                </a>
                                <form action="{{ route('system.pages.destroy', $page) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف الصفحة وجميع بياناتها؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-2xl bg-rose-100 text-rose-700 hover:bg-rose-600 hover:text-white shadow-sm transition-all" title="حذف">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                            <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75V4H5a2 2 0 00-2 2v.25C3 6.664 3.336 7 3.75 7h12.5a.75.75 0 000-1.5h-.25V4A2.75 2.75 0 0013.25 1h-4.5zM6 5.5v-1.75C6 3.224 6.448 2.75 7 2.75h6c.552 0 1 .474 1 1V5.5H6z" clip-rule="evenodd" />
                                            <path d="M4 8.25V15.5A2.75 2.75 0 006.75 18.25h6.5A2.75 2.75 0 0016 15.5V8.25H4zM7.5 10a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 017.5 10zm5.25.75a.75.75 0 00-1.5 0v4.5a.75.75 0 001.5 0v-4.5z" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


