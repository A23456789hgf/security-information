@extends('layouts.app')

@section('title', 'إدارة الدورات التدريبية')
@section('page_title', 'السجلات الأساسية')

@section('content')
<div class="container-fluid mx-auto px-4 py-8" dir="rtl">
    {{-- رأس الصفحة مع الإحصائيات --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-blue-600 rounded-3xl shadow-xl shadow-blue-100 flex items-center justify-center text-white">
                <i class="fas fa-graduation-cap text-2xl"></i>
            </div>
            <div>
                <h2 class="text-3xl font-extrabold text-slate-800 header-font mb-1">الدورات التدريبية</h2>
                <p class="text-slate-500 text-sm flex items-center gap-2">
                    <i class="fas fa-database text-slate-400"></i> إجمالي السجلات:
                    <span class="text-slate-700 font-bold">{{ $courses->total() }}</span>
                </p>
            </div>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('system.courses.export', request()->all()) }}"
                class="px-5 py-3 bg-emerald-600 text-white rounded-2xl font-bold hover:bg-emerald-700 transition-all shadow-xl shadow-emerald-100 flex items-center gap-2 text-sm">
                <i class="fas fa-file-excel"></i> تصدير (Excel)
            </a>
            <a href="{{ route('data.import.index', ['type' => 'courses']) }}"
                class="px-5 py-3 bg-indigo-600 text-white rounded-2xl font-bold hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-100 flex items-center gap-2 text-sm">
                <i class="fas fa-file-import"></i> استيراد
            </a>
            <a href="{{ route('system.courses.create') }}"
                class="px-6 py-3 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 transition-all shadow-xl shadow-blue-100 flex items-center gap-2 text-sm">
                <i class="fas fa-plus-circle"></i> إضافة دورة جديدة
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
        <form method="GET" action="{{ route('system.courses.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
            <div class="md:col-span-8 relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="search"
                    class="w-full pl-12 pr-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all text-slate-600 font-bold"
                    placeholder="ابحث بالاسم، نوع الدورة، أو المسؤول..." value="{{ request('search') }}">
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
                    <a href="{{ route('system.courses.index') }}" class="text-xs text-slate-400 hover:text-slate-600 flex items-center gap-1 transition-colors">
                        <i class="fas fa-times-circle"></i> مسح البحث
                    </a>
                </div>
            @endif
        </form>
    </div>

    {{-- جدول البيانات --}}
    <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-8 py-6 text-slate-400 font-bold uppercase tracking-widest text-[10px]">#</th>
                        <th class="px-6 py-6 text-slate-400 font-bold uppercase tracking-widest text-[10px]">اسم المتدرب</th>
                        <th class="px-6 py-6 text-slate-400 font-bold uppercase tracking-widest text-[10px]">نوع الدورة</th>
                        <th class="px-6 py-6 text-slate-400 font-bold uppercase tracking-widest text-[10px] text-center">تاريخ الدورة</th>
                        <th class="px-6 py-6 text-slate-400 font-bold uppercase tracking-widest text-[10px] text-center">المدة</th>
                        <th class="px-6 py-6 text-slate-400 font-bold uppercase tracking-widest text-[10px]">المسؤول / جهة الإرسال</th>
                        <th class="px-8 py-6 text-slate-400 font-bold uppercase tracking-widest text-[10px] text-left">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($courses as $course)
                        <tr class="group hover:bg-slate-50/30 transition-colors">
                            <td class="px-8 py-5">
                                <span class="text-slate-400 font-mono text-xs">{{ str_pad($course->id, 4, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center font-bold text-sm border border-blue-100">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    <div>
                                        @if($course->personalData)
                                            <a href="{{ route('system.personal-data.show', $course->personalData->id) }}" class="font-bold text-slate-700 hover:text-blue-600 transition-colors block">
                                                {{ $course->personalData->name }}
                                            </a>
                                            <span class="text-[10px] text-slate-400 font-mono">{{ $course->personalData->military_number ?? '' }}</span>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-lg text-xs font-bold">
                                    {{ $course->courseType->name ?? $course->course_name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-[10px] font-bold">
                                    <i class="far fa-calendar-alt me-1 text-slate-400"></i>
                                    {{ $course->course_date ? $course->course_date->format('Y-m-d') : ($course->date ?? '-') }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-center font-bold text-slate-700 text-xs">
                                {{ $course->duration ? $course->duration . ' يوم' : '-' }}
                            </td>
                            <td class="px-6 py-5 text-xs text-slate-600">
                                <div>{{ $course->supervisor ?? '-' }}</div>
                                @if($course->sendingEntity)
                                    <div class="text-[10px] text-slate-400">{{ $course->sendingEntity->name }}</div>
                                @endif
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex justify-end items-center gap-2">
                                    <a href="{{ route('system.courses.edit', $course->id) }}" class="w-9 h-9 flex items-center justify-center rounded-xl bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white shadow-sm transition-all" title="تعديل"><i class="fas fa-pen text-xs"></i></a>
                                    <form action="{{ route('system.courses.destroy', $course->id) }}"
                                        method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white shadow-sm transition-all" title="حذف"><i class="fas fa-trash text-xs"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-search text-slate-200 text-3xl"></i>
                                    </div>
                                    <p class="text-slate-400 font-bold">لا توجد سجلات دورات تدريبية حالياً</p>
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
        {{ $courses->withQueryString()->links() }}
    </div>
</div>
@endsection
