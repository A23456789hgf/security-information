@extends('layouts.app')

@section('title', 'إدارة الدورات التدريبية')
@section('page_title', 'النظام')

@section('content')
<div class="container-fluid py-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-blue-600 rounded-3xl shadow-xl shadow-blue-100 flex items-center justify-center text-white">
                <i class="fas fa-graduation-cap text-2xl"></i>
            </div>
            <div>
                <h2 class="text-3xl font-extrabold text-slate-800 header-font mb-1">الدورات التدريبية</h2>
                <p class="text-slate-500 text-sm flex items-center gap-2">
                    إدارة ومتابعة الدورات التدريبية للموظفين
                    <span class="inline-flex items-center justify-center bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full text-xs font-bold">
                        {{ $courses->total() }} سجل
                    </span>
                </p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('system.courses.create') }}"
                class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-bold transition-all shadow-lg shadow-blue-100">
                <i class="fas fa-plus"></i>
                <span>إضافة دورة جديدة</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- بطاقة البحث -->
    <div class="bg-white rounded-3xl soft-shadow border border-slate-100 p-4 mb-8">
        <form method="GET" action="{{ route('system.courses.index') }}" class="row g-3">
            <div class="col-md-8">
                <div class="relative group">
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                        <i class="fas fa-search"></i>
                    </div>
                    <input type="text" name="search" class="w-full pr-11 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all" 
                           placeholder="ابحث عن الاسم أو نوع الدورة أو مسؤول الدورة..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                @include('partials._pagination_limit')
            </div>
            <div class="col-md-2">
                <button type="submit" class="w-full py-3 bg-slate-800 hover:bg-slate-900 text-white rounded-2xl font-bold transition-all shadow-sm">
                    بحث
                </button>
            </div>
        </form>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white rounded-3xl soft-shadow border border-slate-100" style="overflow: visible !important;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                <thead class="bg-slate-50/80 border-b border-slate-100">
                    <tr>
                        <th class="ps-6 py-4 text-slate-400 font-bold uppercase tracking-wider text-[10px]" style="width: 80px;">الرقم</th>
                        <th class="py-4 text-slate-400 font-bold uppercase tracking-wider text-[10px]">اسم الطالب</th>
                        <th class="py-4 text-slate-400 font-bold uppercase tracking-wider text-[10px]">نوع الدورة</th>
                        <th class="py-4 text-slate-400 font-bold uppercase tracking-wider text-[10px] text-center">تاريخ الدورة</th>
                        <th class="py-4 text-slate-400 font-bold uppercase tracking-wider text-[10px] text-center">المدة</th>
                        <th class="py-4 text-slate-400 font-bold uppercase tracking-wider text-[10px]">المسؤول</th>
                        <th class="text-center pe-6 py-4 text-slate-400 font-bold uppercase tracking-wider text-[10px]" style="width: 150px;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($courses as $course)
                        <tr class="group hover:bg-slate-50/50 transition-colors">
                            <td class="ps-6 py-4">
                                <span class="text-slate-400 font-mono text-[10px]">{{ str_pad($course->id, 5, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td class="py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center border border-blue-100">
                                        <i class="fas fa-certificate"></i>
                                    </div>
                                    <div>
                                        <span class="fw-bold text-slate-700">{{ $course->personalData->name ?? '-' }}</span>
                                        @if($course->personalData)
                                            @php $pData = $course->personalData; @endphp
                                            <div class="mt-2 grid grid-cols-2 gap-x-4 gap-y-1 text-[10px] bg-slate-50 p-2 rounded-xl border border-slate-100">
                                                <div class="flex items-center gap-1 text-slate-500">
                                                    <i class="fas fa-user-tag w-4"></i>
                                                    <span class="font-bold">الاسم الجهادي:</span> {{ $pData->jihadi_name ?? '-' }}
                                                </div>
                                                <div class="flex items-center gap-1 text-slate-500">
                                                    <i class="fas fa-id-badge w-4"></i>
                                                    <span class="font-bold">الرقم العسكري:</span> {{ $pData->military_number ?? '-' }}
                                                </div>
                                                <div class="flex items-center gap-1 text-slate-500">
                                                    <i class="fas fa-tint w-4 text-rose-500"></i>
                                                    <span class="font-bold">فصيلة الدم:</span> {{ $pData->bloodType->type ?? '-' }}
                                                </div>
                                                <div class="flex items-center gap-1 text-slate-500">
                                                    <i class="fas fa-calendar w-4"></i>
                                                    <span class="font-bold">تاريخ الميلاد:</span> {{ $pData->date_of_birth_gregorian ? $pData->date_of_birth_gregorian->format('Y-m-d') : '-' }}
                                                </div>
                                                <div class="flex items-center gap-1 text-slate-500">
                                                    <i class="fas fa-ring w-4"></i>
                                                    <span class="font-bold">الحالة الاجتماعية:</span> {{ $pData->maritalStatus->name ?? '-' }}
                                                </div>
                                                <div class="flex items-center gap-1 text-slate-500">
                                                    <i class="fas fa-map-marker-alt w-4"></i>
                                                    <span class="font-bold">مكان الميلاد:</span> {{ $pData->placeOfBirthDirectorate->name ?? '-' }}
                                                </div>
                                                <div class="flex items-center gap-1 text-slate-500">
                                                    <i class="fas fa-id-card w-4"></i>
                                                    <span class="font-bold">رقم الهوية:</span> {{ $pData->national_id_number ?? '-' }}
                                                </div>
                                                <div class="flex items-center gap-1 text-slate-500">
                                                    <i class="fas fa-phone w-4"></i>
                                                    <span class="font-bold">هاتف 1:</span> {{ $pData->phone_1 ?? '-' }}
                                                </div>
                                                <div class="flex items-center gap-1 text-slate-500">
                                                    <i class="fas fa-mobile-alt w-4"></i>
                                                    <span class="font-bold">هاتف 2:</span> {{ $pData->phone_2 ?? '-' }}
                                                </div>
                                                <div class="flex items-center gap-1 text-slate-500 col-span-2 border-t border-slate-200 mt-1 pt-1">
                                                    <i class="fas fa-home w-4"></i>
                                                    <span class="font-bold">العنوان:</span> {{ ($pData->governorate->name ?? '') . ' - ' . ($pData->directorate->name ?? '') . ' - ' . ($pData->village->name ?? '') }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 font-bold text-slate-600">{{ $course->courseType->name ?? '-' }}</td>
                            <td class="py-4 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 bg-slate-50 text-slate-500 rounded-lg text-[10px] font-bold border border-slate-100">
                                    <i class="far fa-calendar-alt me-1 opacity-50"></i>
                                    {{ $course->course_date_gregorian ? (is_string($course->course_date_gregorian) ? \Carbon\Carbon::parse($course->course_date_gregorian)->format('d/m/Y') : $course->course_date_gregorian->format('d/m/Y')) : '-' }}
                                </span>
                            </td>
                            <td class="py-4 text-center font-bold text-slate-600">{{ $course->course_duration ?? '-' }}</td>
                            <td class="py-4 text-slate-600">{{ $course->course_administrator ?? '-' }}</td>
                            <td class="text-center pe-6 py-4">
                                <div class="flex justify-end items-center gap-2">
                                    <a href="{{ route('system.courses.edit', $course->id) }}" class="w-9 h-9 flex items-center justify-center rounded-lg bg-amber-100 text-amber-700 hover:bg-amber-600 hover:text-white shadow-sm transition-all" title="تعديل">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                            <path d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" />
                                            <path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('system.courses.destroy', $course->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-lg bg-rose-100 text-rose-700 hover:bg-rose-600 hover:text-white shadow-sm transition-all" title="حذف">
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
                            <td colspan="7" class="py-20 text-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-search text-slate-200 text-3xl"></i>
                                </div>
                                <p class="text-slate-400 font-bold header-font">لا توجد دورات تدريبية حالياً</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-10 flex justify-center">
        {{ $courses->withQueryString()->links() }}
    </div>
</div>
@endsection
