@extends('layouts.app')

@section('title', 'إدارة الدورات التدريبية')
@section('page_title', 'السجلات الأساسية')

@section('content')
<div class="space-y-6" dir="rtl">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white flex items-center gap-3">
                <div class="p-2.5 bg-brand-500/10 text-brand-600 dark:text-brand-400 rounded-xl">
                    <i class="fas fa-graduation-cap text-xl"></i>
                </div>
                الدورات التدريبية
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                إدارة ومتابعة الدورات التدريبية للأفراد وتفاصيلها (إجمالي السجلات: <span class="font-bold text-slate-700 dark:text-slate-200">{{ $courses->total() }}</span>)
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <x-print-button :url="route('system.courses.print-all', request()->all())" text="طباعة الكشف" />
            <a href="{{ route('system.courses.export', request()->all()) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow transition-all">
                <i class="fas fa-file-excel"></i>
                تصدير (Excel)
            </a>
            <a href="{{ route('data.import.index', ['type' => 'courses']) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow transition-all">
                <i class="fas fa-file-import"></i>
                استيراد
            </a>
            <a href="{{ route('system.courses.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow transition-all">
                <i class="fas fa-plus"></i>
                إضافة دورة جديدة
            </a>
        </div>
    </div>

    {{-- System Alerts --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl text-emerald-600 dark:text-emerald-400 flex items-center justify-between" role="alert">
            <div class="flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
            <button type="button" class="text-emerald-500 hover:text-emerald-700" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    {{-- Filter Toolbar --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-4 shadow-sm">
        <form method="GET" action="{{ route('system.courses.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
            <div class="md:col-span-4 relative">
                <i class="fas fa-search absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" name="search"
                    class="w-full pr-10 pl-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all font-medium"
                    placeholder="ابحث باسم المتدرب، الدورة..." value="{{ request('search') }}">
            </div>
            <div class="md:col-span-4">
                <select name="personal_data_id" onchange="this.form.submit()" class="w-full py-2.5 px-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all">
                    <option value="">-- فرز بالمتدرب/البيانات الشخصية (الكل) --</option>
                    @foreach($personalDataList as $p)
                        <option value="{{ $p->id }}" {{ request('personal_data_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->name }} {{ $p->jihadi_name ? '('.$p->jihadi_name.')' : '' }} {{ $p->military_number ? '- م: '.$p->military_number : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                @include('partials._pagination_limit')
            </div>
            <div class="md:col-span-2 flex gap-2">
                <button type="submit" class="flex-1 py-2.5 bg-slate-900 dark:bg-slate-800 hover:bg-slate-800 dark:hover:bg-slate-700 text-white rounded-xl text-sm font-semibold transition-all">
                    تصفية
                </button>
                @if(request()->anyFilled(['search', 'personal_data_id']))
                    <a href="{{ route('system.courses.index') }}" class="px-3 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl font-semibold text-xs transition-all flex items-center justify-center">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Data table --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 font-bold text-xs uppercase tracking-wider">
                        <th class="px-6 py-4">#</th>
                        <th class="px-6 py-4">اسم المتدرب</th>
                        <th class="px-6 py-4">نوع الدورة</th>
                        <th class="px-6 py-4 text-center">تاريخ الدورة</th>
                        <th class="px-6 py-4 text-center">المدة</th>
                        <th class="px-6 py-4">المسؤول / جهة الإرسال</th>
                        <th class="px-6 py-4 text-left">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                    @forelse($courses as $course)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/25 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-mono text-xs text-slate-400">#{{ str_pad($course->id, 4, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($course->personalData && $course->personalData->photo_url)
                                        <img src="{{ $course->personalData->photo_url }}" class="w-10 h-10 rounded-full object-cover border border-slate-200 dark:border-slate-700 shadow-sm shrink-0" alt="{{ $course->personalData->name }}">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-brand-500/10 border border-brand-500/20 text-brand-600 dark:text-brand-400 flex items-center justify-center font-bold text-xs shrink-0">
                                            <i class="fas fa-user-graduate"></i>
                                        </div>
                                    @endif
                                    <div>
                                        @if($course->personalData)
                                            <a href="{{ route('system.personal-data.show', $course->personalData->id) }}" class="font-bold text-slate-900 dark:text-white hover:text-brand-600 dark:hover:text-brand-400 transition-colors block">
                                                {{ $course->personalData->name }}
                                            </a>
                                            <div class="flex items-center gap-2 text-xs text-slate-400 font-mono">
                                                @if($course->personalData->jihadi_name)
                                                    <span>({{ $course->personalData->jihadi_name }})</span>
                                                @endif
                                                @if($course->personalData->military_number)
                                                    <span>م: {{ $course->personalData->military_number }}</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-2xl text-xs font-semibold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                    {{ $course->courseType->name ?? $course->course_name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-2xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
                                    <i class="far fa-calendar-alt text-slate-400"></i>
                                    {{ $course->course_date ? $course->course_date->format('Y-m-d') : ($course->date ?? '-') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-slate-700 dark:text-slate-300 text-xs">
                                {{ $course->duration ? $course->duration . ' يوم' : ($course->course_duration ?? '-') }}
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-600 dark:text-slate-300">
                                <div>{{ $course->supervisor ?? $course->course_administrator ?? '-' }}</div>
                                @if($course->sendingEntity)
                                    <div class="text-[11px] text-slate-400 mt-0.5">{{ $course->sendingEntity->name }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('system.courses.edit', $course->id) }}"
                                        class="p-2 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-950/50 transition-colors"
                                        title="تعديل">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>
                                    <form action="{{ route('system.courses.destroy', $course->id) }}"
                                        method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-2 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/50 transition-colors"
                                            title="حذف">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-slate-50 dark:bg-slate-800 rounded-2xl flex items-center justify-center mb-3">
                                        <i class="fas fa-graduation-cap text-slate-300 dark:text-slate-600 text-2xl"></i>
                                    </div>
                                    <p class="text-slate-500 dark:text-slate-400 font-medium">لا توجد سجلات دورات تدريبية حالياً</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="flex justify-center">
        {{ $courses->withQueryString()->links() }}
    </div>
</div>
@endsection

