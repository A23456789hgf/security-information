@extends('layouts.app')

@section('title', 'إدارة البيانات الشخصية')
@section('page_title', 'السجلات الأساسية')

@section('content')
    <div class="container-fluid mt-3" dir="rtl">
        {{-- الرأس مع إحصائيات مختصرة --}}
        <div class="row align-items-center mb-4">
            <div class="col-md-7">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-blue-500 bg-opacity-10 p-3 rounded-2xl text-blue-600">
                        <i class="fas fa-id-card fa-xl"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-1 text-slate-800 header-font">قاعدة البيانات الشخصية</h4>
                        <div class="flex gap-4">
                            <small class="text-slate-500" style="font-size: 0.75rem;">
                                <i class="fas fa-database me-1 text-slate-400"></i> إجمالي السجلات: <span
                                    class="text-slate-700 font-bold">{{ $personalDataList->total() }}</span>
                            </small>
                            <small class="text-slate-500" style="font-size: 0.75rem;">
                                <i class="fas fa-plus-circle me-1 text-emerald-500"></i> سُجل اليوم: <span
                                    class="text-emerald-600 font-bold">{{ $personalDataList->getCollection()->where('created_at', '>=', now()->startOfDay())->count() }}</span>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5 text-md-end mt-3 mt-md-0">
                <div class="flex flex-wrap gap-2 justify-content-md-end">
                    <a href="{{ route('system.personal-data.fast-entry') }}"
                        class="px-4 py-2 bg-amber-500 text-white rounded-2xl font-bold hover:bg-amber-600 transition-all shadow-lg shadow-amber-100 flex items-center gap-2"
                        style="font-size: 0.85rem;">
                        <i class="fas fa-bolt"></i> إدخال سريع
                    </a>
                    <a href="{{ route('system.personal-data.create') }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 flex items-center gap-2"
                        style="font-size: 0.85rem;">
                        <i class="fas fa-plus-circle"></i> إضافة سجل جديد
                    </a>
                </div>
            </div>
        </div>

        {{-- رسائل النظام --}}
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-4 py-3 rounded-2xl mb-4 flex items-center justify-between shadow-sm animate-pulse"
                role="alert">
                <div class="flex items-center gap-3">
                    <i class="fas fa-check-circle"></i>
                    <span class="text-sm font-bold">{{ session('success') }}</span>
                </div>
                <button type="button" class="text-emerald-400 hover:text-emerald-600" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        {{-- منطقة البحث والفلترة --}}
        <div class="bg-white rounded-3xl p-3 soft-shadow border border-slate-100 mb-6">
            <form method="GET" action="{{ route('system.personal-data.index') }}" class="row g-2 align-items-center">
                <div class="col-md-8">
                    <div class="input-group input-group-sm bg-slate-50 rounded-2xl border-0 px-3 py-1">
                        <span class="bg-transparent border-0 flex items-center px-2">
                            <i class="fas fa-search text-slate-400"></i>
                        </span>
                        <input type="text" name="search"
                            class="form-control bg-transparent border-0 focus:ring-0 text-slate-600"
                            placeholder="ابحث بالاسم، الرقم العسكري، أو رقم الهوية..." value="{{ request('search') }}"
                            style="font-size: 0.9rem;">
                    </div>
                </div>
                <div class="col-md-2">
                    @include('partials._pagination_limit')
                </div>
                <div class="col-md-2">
                    <button type="submit"
                        class="w-full py-2 bg-slate-800 text-white rounded-2xl font-bold hover:bg-slate-900 transition-all text-sm">
                        تصفية النتائج
                    </button>
                </div>
                @if(request('search'))
                    <div class="col-12 mt-2 px-3">
                        <a href="{{ route('system.personal-data.index') }}"
                            class="text-xs text-slate-400 hover:text-slate-600 flex items-center gap-1">
                            <i class="fas fa-times-circle"></i> مسح سجل البحث
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
                            <th class="ps-6 py-4 text-slate-400 font-bold uppercase tracking-wider text-[10px]"
                                style="width: 80px;">الرقم</th>
                            <th class="py-4 text-slate-400 font-bold uppercase tracking-wider text-[10px]">الاسم الكامل</th>
                            <th class="py-4 text-slate-400 font-bold uppercase tracking-wider text-[10px]">الهوية / العسكري
                            </th>
                            <th class="py-4 text-slate-400 font-bold uppercase tracking-wider text-[10px]">تاريخ الميلاد
                            </th>
                            <th class="py-4 text-slate-400 font-bold uppercase tracking-wider text-[10px]">الحالة</th>
                            <th class="pe-6 py-4 text-slate-400 font-bold uppercase tracking-wider text-[10px] text-end">
                                الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($personalDataList as $record)
                            <tr class="group hover:bg-slate-50/50 transition-colors">
                                <td class="ps-6">
                                    <span
                                        class="text-slate-400 font-mono text-[10px]">{{ str_pad($record->id, 5, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center font-bold text-xs">
                                            {{ substr($record->name ?? '-', 0, 2) }}
                                        </div>
                                        <!-- <div>
                                                                                    <p class="font-bold text-slate-700 mb-0">{{ $record->name }}</p>
                                                                                    <div
                                                                                        class="mt-2 grid grid-cols-2 gap-x-4 gap-y-1 text-[10px] bg-slate-50 p-2 rounded-xl border border-slate-100">
                                                                                        <div class="flex items-center gap-1 text-slate-500">
                                                                                            <i class="fas fa-user-tag w-4"></i>
                                                                                            <span class="font-bold">الاسم الجهادي:</span>
                                                                                            {{ $record->jihadi_name ?? '-' }}
                                                                                        </div>
                                                                                        <div class="flex items-center gap-1 text-slate-500">
                                                                                            <i class="fas fa-id-badge w-4"></i>
                                                                                            <span class="font-bold">الرقم العسكري:</span>
                                                                                            {{ $record->military_number ?? '-' }}
                                                                                        </div>
                                                                                        <div class="flex items-center gap-1 text-slate-500">
                                                                                            <i class="fas fa-tint w-4 text-rose-500"></i>
                                                                                            <span class="font-bold">فصيلة الدم:</span>
                                                                                            {{ $record->bloodType->type ?? '-' }}
                                                                                        </div>
                                                                                        <div class="flex items-center gap-1 text-slate-500">
                                                                                            <i class="fas fa-calendar w-4"></i>
                                                                                            <span class="font-bold">تاريخ الميلاد:</span>
                                                                                            {{ $record->date_of_birth_gregorian ? $record->date_of_birth_gregorian->format('Y-m-d') : '-' }}
                                                                                        </div>
                                                                                        <div class="flex items-center gap-1 text-slate-500">
                                                                                            <i class="fas fa-ring w-4"></i>
                                                                                            <span class="font-bold">الحالة الاجتماعية:</span>
                                                                                            {{ $record->maritalStatus->name ?? '-' }}
                                                                                        </div>
                                                                                        <div class="flex items-center gap-1 text-slate-500">
                                                                                            <i class="fas fa-map-marker-alt w-4"></i>
                                                                                            <span class="font-bold">مكان الميلاد:</span>
                                                                                            {{ $record->placeOfBirthDirectorate->name ?? '-' }}
                                                                                        </div>
                                                                                        <div class="flex items-center gap-1 text-slate-500">
                                                                                            <i class="fas fa-id-card w-4"></i>
                                                                                            <span class="font-bold">رقم الهوية:</span>
                                                                                            {{ $record->national_id_number ?? '-' }}
                                                                                        </div>
                                                                                        <div class="flex items-center gap-1 text-slate-500">
                                                                                            <i class="fas fa-phone w-4"></i>
                                                                                            <span class="font-bold">هاتف 1:</span> {{ $record->phone_1 ?? '-' }}
                                                                                        </div>
                                                                                        <div class="flex items-center gap-1 text-slate-500">
                                                                                            <i class="fas fa-mobile-alt w-4"></i>
                                                                                            <span class="font-bold">هاتف 2:</span> {{ $record->phone_2 ?? '-' }}
                                                                                        </div>
                                                                                        <div
                                                                                            class="flex items-center gap-1 text-slate-500 col-span-2 border-t border-slate-200 mt-1 pt-1">
                                                                                            <i class="fas fa-home w-4"></i>
                                                                                            <span class="font-bold">العنوان:</span>
                                                                                            {{ ($record->governorate->name ?? '') . ' - ' . ($record->directorate->name ?? '') . ' - ' . ($record->village->name ?? '') }}
                                                                                        </div>
                                                                                    </div>
                                                                                </div> -->
                                    </div>
                                </td>
                                <td>
                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-id-card text-slate-300 text-[10px]"></i>
                                            <span
                                                class="text-slate-600 font-mono text-[11px]">{{ $record->national_id_number ?? '-' }}</span>
                                        </div>
                                        @if($record->military_number)
                                            <div class="flex items-center gap-2">
                                                <i class="fas fa-id-badge text-blue-300 text-[10px]"></i>
                                                <span
                                                    class="text-blue-600 font-mono text-[11px] font-bold">{{ $record->military_number }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="px-2 py-1 bg-slate-100 text-slate-600 rounded-lg text-[10px] font-bold">
                                        <i class="far fa-calendar-alt me-1"></i>
                                        {{ $record->date_of_birth_gregorian ? $record->date_of_birth_gregorian->format('Y-m-d') : '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span
                                        class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-bold border border-emerald-100">
                                        مكتمل
                                    </span>
                                </td>
                                <td class="pe-6 py-3 text-end">
                                    <div class="flex justify-end items-center gap-2">
                                        <!-- زر العرض -->
                                        <a href="{{ route('system.personal-data.show', $record) }}"
                                            class="w-9 h-9 flex items-center justify-center rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-600 hover:text-white shadow-sm transition-all"
                                            title="عرض">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                                class="w-5 h-5">
                                                <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
                                                <path fill-rule="evenodd"
                                                    d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </a>

                                        <!-- زر التعديل -->
                                        <a href="{{ route('system.personal-data.edit', $record) }}"
                                            class="w-9 h-9 flex items-center justify-center rounded-lg bg-amber-100 text-amber-700 hover:bg-amber-600 hover:text-white shadow-sm transition-all"
                                            title="تعديل">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                                class="w-5 h-5">
                                                <path
                                                    d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" />
                                                <path
                                                    d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" />
                                            </svg>
                                        </a>

                                        <!-- زر الحذف -->
                                        <form action="{{ route('system.personal-data.destroy', $record) }}" method="POST"
                                            class="inline" onsubmit="return confirm('هل أنت متأكد؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-9 h-9 flex items-center justify-center rounded-lg bg-rose-100 text-rose-700 hover:bg-rose-600 hover:text-white shadow-sm transition-all"
                                                title="حذف">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                                    class="w-5 h-5">
                                                    <path fill-rule="evenodd"
                                                        d="M8.75 1A2.75 2.75 0 006 3.75V4H5a2 2 0 00-2 2v.25C3 6.664 3.336 7 3.75 7h12.5a.75.75 0 000-1.5h-.25V4A2.75 2.75 0 0013.25 1h-4.5zM6 5.5v-1.75C6 3.224 6.448 2.75 7 2.75h6c.552 0 1 .474 1 1V5.5H6z"
                                                        clip-rule="evenodd" />
                                                    <path
                                                        d="M4 8.25V15.5A2.75 2.75 0 006.75 18.25h6.5A2.75 2.75 0 0016 15.5V8.25H4zM7.5 10a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 017.5 10zm5.25.75a.75.75 0 00-1.5 0v4.5a.75.75 0 001.5 0v-4.5z" />
                                                </svg>
                                            </button>
                                        </form>

                                        <!-- زر الإضافات السريعة (dropdown) - مع تحسينات z-index -->
                                        <div class="dropdown d-inline-block" style="position: relative; z-index: 1;">
                                            <button
                                                class="w-9 h-9 flex items-center justify-center rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition-all border-0 shadow-sm focus:ring-2 focus:ring-slate-300"
                                                type="button" data-bs-toggle="dropdown" aria-expanded="false"
                                                title="إجراءات إضافية">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 4.5v15m7.5-7.5h-15" />
                                                </svg>
                                            </button>

                                            <!-- القائمة المنسدلة مع z-index عالٍ جدًا -->
                                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-2xl rounded-2xl p-2 mt-2"
                                                style="font-size: 0.85rem; min-width: 200px; position: absolute; z-index: 9999;">

                                                {{-- خيار الطباعة الشاملة --}}
                                                <li>
                                                    <a class="dropdown-item rounded-xl py-2.5 px-3 flex items-center gap-3 hover:bg-blue-50 hover:text-blue-600 transition-colors font-bold"
                                                        href="{{ route('system.personal-data.print-all', $record) }}"
                                                        target="_blank">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                            stroke-width="1.5" stroke="currentColor"
                                                            class="w-4 h-4 text-blue-500">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M6.72 13.89l-4.72-4.72g1.5-1.5L6.72 10.89l4.72-4.72m-12 0l4.72 4.72-4.72 4.72" />
                                                            <path
                                                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                        طباعة بيانات شخصية
                                                    </a>
                                                </li>
                                                {{-- خيار الطباعة الشاملة --}}
                                                <li>
                                                    <a class="dropdown-item rounded-xl py-2.5 px-3 flex items-center gap-3 hover:bg-blue-50 hover:text-blue-600 transition-colors font-bold"
                                                        href="{{ route('system.personal-data.print', $record) }}"
                                                        target="_blank">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                            stroke-width="1.5" stroke="currentColor"
                                                            class="w-4 h-4 text-blue-500">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M6.72 13.89l-4.72-4.72g1.5-1.5L6.72 10.89l4.72-4.72m-12 0l4.72 4.72-4.72 4.72" />
                                                            <path
                                                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                        طباعة شاملة
                                                    </a>
                                                </li>

                                                {{-- خيار الطباعة الشاملة --}}
                                                <li>
                                                    <a class="dropdown-item rounded-xl py-2.5 px-3 flex items-center gap-3 hover:bg-blue-50 hover:text-blue-600 transition-colors font-bold"
                                                        href="{{ route('system.personal-data.print-all', $record) }}"
                                                        target="_blank">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                            stroke-width="1.5" stroke="currentColor"
                                                            class="w-4 h-4 text-blue-500">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M6.72 13.89l-4.72-4.72g1.5-1.5L6.72 10.89l4.72-4.72m-12 0l4.72 4.72-4.72 4.72" />
                                                            <path
                                                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                        طباعة شاملة
                                                    </a>
                                                </li>

                                                <li>
                                                    <hr class="dropdown-divider border-slate-100 my-1">
                                                </li>

                                                {{-- خيار إضافة دورات --}}
                                                <li>
                                                    <a class="dropdown-item rounded-xl py-2.5 px-3 flex items-center gap-3 hover:bg-emerald-50 hover:text-emerald-600 transition-colors font-bold"
                                                        href="{{ route('system.courses.create', ['personal_data_id' => $record->id]) }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                            stroke-width="1.5" stroke="currentColor"
                                                            class="w-4 h-4 text-emerald-500">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M4.26 10.174L10.778 19.03a1.5 1.5 0 012.444 0l6.518-8.856A1.5 1.5 0 0118.522 8h-13.044a1.5 1.5 0 01-1.218-2.174z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                                        </svg>
                                                        إضافة دورات
                                                    </a>
                                                </li>

                                                {{-- خيار بيانات ثانوية --}}
                                                <li>
                                                    <a class="dropdown-item rounded-xl py-2.5 px-3 flex items-center gap-3 hover:bg-purple-50 hover:text-purple-600 transition-colors font-bold"
                                                        href="{{ route('system.secondary-data.create', ['personal_data_id' => $record->id]) }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                            stroke-width="1.5" stroke="currentColor"
                                                            class="w-4 h-4 text-purple-500">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.128 16.556 17.975 12 17.975s-8.25-1.847-8.25-4.125v-3.75m16.5 0v3.75" />
                                                        </svg>
                                                        إضافة بيانات ثانوية
                                                    </a>
                                                </li>

                                                {{-- خيار توقيف --}}
                                                <li>
                                                    <a class="dropdown-item rounded-xl py-2.5 px-3 flex items-center gap-3 hover:bg-rose-50 hover:text-rose-600 transition-colors font-bold"
                                                        href="{{ route('system.arrests.create', ['personal_data_id' => $record->id]) }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                            stroke-width="1.5" stroke="currentColor"
                                                            class="w-4 h-4 text-rose-500">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                                        </svg>
                                                        إضافة سجلات توقيف
                                                    </a>
                                                </li>

                                                {{-- خيار إضافة زوجة --}}
                                                <li>
                                                    <a class="dropdown-item rounded-xl py-2.5 px-3 flex items-center gap-3 hover:bg-pink-50 hover:text-pink-600 transition-colors font-bold"
                                                        href="{{ route('system.wives.create', ['personal_data_id' => $record->id]) }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                            stroke-width="1.5" stroke="currentColor"
                                                            class="w-4 h-4 text-pink-500">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                                        </svg>
                                                        إضافة زوجة
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                            <i class="fas fa-folder-open text-slate-200 text-3xl"></i>
                                        </div>
                                        <p class="text-slate-400 font-bold">لا توجد سجلات مطابقة للبحث</p>
                                        <a href="{{ route('system.personal-data.create') }}"
                                            class="mt-4 text-blue-600 font-bold text-sm underline underline-offset-4">إضافة سجل
                                            جديد الآن</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- الترقيم --}}
        <div class="mt-8 flex justify-center">
            {{ $personalDataList->withQueryString()->links() }}
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .pagination {
            @apply flex gap-1;
        }

        .page-item .page-link {
            @apply border-0 rounded-xl text-slate-500 font-bold text-xs px-4 py-2 hover:bg-slate-100 transition-all;
        }

        .page-item.active .page-link {
            @apply bg-blue-600 text-white shadow-lg shadow-blue-100;
        }

        /* ضمان ظهور القوائم المنسدلة فوق أي عنصر آخر في الصفحة */
        .dropdown-menu {
            z-index: 9999 !important;
            position: absolute !important;
        }

        /* التأكد من أن أي حاوية لا تحتوي على overflow: hidden */
        .table-responsive,
        .bg-white.rounded-3xl,
        .container-fluid {
            overflow: visible !important;
        }

        /* منع أي تقطيع للقوائم */
        .table {
            overflow: visible !important;
        }

        /* إضافة سياق تراصح للخلية التي تحتوي على dropdown لتجنب مشاكل z-index النسبية */
        td.pe-6 {
            position: relative;
            z-index: auto;
        }

        /* التأكد من أن عنصر dropdown له سياق تراصح خاص به */
        .dropdown {
            position: relative;
            z-index: 100;
        }

        /* حل إضافي: جعل القائمة تظهر فوق كل شيء حتى لو كان هناك عناصر أخرى */
        .dropdown-menu.show {
            display: block;
            z-index: 9999 !important;
        }
    </style>
@endpush