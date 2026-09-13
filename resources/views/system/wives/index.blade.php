@extends('layouts.app')

@section('title', 'إدارة الزوجات')
@section('page_title', 'السجلات الأساسية')

@section('content')
<div class="space-y-6" dir="rtl">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white flex items-center gap-3">
                <div class="p-2.5 bg-brand-500/10 text-brand-600 dark:text-brand-400 rounded-xl">
                    <i class="fas fa-female text-xl"></i>
                </div>
                إدارة الزوجات
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                سجلات وبيانات الزوجات والارتباطات العائلية (إجمالي السجلات: <span class="font-bold text-slate-700 dark:text-slate-200">{{ $wives->total() }}</span>)
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <x-print-button :url="route('system.wives.print-all', request()->all())" text="طباعة الكشف" />
            <a href="{{ route('system.wives.export', request()->all()) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow transition-all">
                <i class="fas fa-file-excel"></i>
                تصدير (Excel)
            </a>
            <a href="{{ route('data.import.index', ['type' => 'wives']) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow transition-all">
                <i class="fas fa-file-import"></i>
                استيراد
            </a>
            <a href="{{ route('system.wives.create', request()->only('personal_data_id')) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow transition-all">
                <i class="fas fa-plus"></i>
                @if(request()->filled('personal_data_id'))
                    إضافة زوجة للزوج المختار
                @else
                    إضافة زوجة جديدة
                @endif
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

    {{-- Search and Filter Toolbar --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-4 shadow-sm">
        <form method="GET" action="{{ route('system.wives.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
            <div class="md:col-span-4 relative">
                <i class="fas fa-search absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" name="search"
                    class="w-full pr-10 pl-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all font-medium"
                    placeholder="ابحث باسم الزوجة، الزوج، الهوية..." value="{{ request('search') }}">
            </div>
            <div class="md:col-span-4">
                <select name="personal_data_id" id="personal_data_id_filter" onchange="this.form.submit()" class="select2-filter w-full py-2.5 px-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all">
                    <option value="">-- فرز بالزوج/البيانات الشخصية (الكل) --</option>
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
                    <a href="{{ route('system.wives.index') }}" class="px-3 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl font-semibold text-xs transition-all flex items-center justify-center">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    @if(request()->filled('personal_data_id'))
        @php
            $selectedHusband = $personalDataList->firstWhere('id', request('personal_data_id'));
        @endphp
        @if($selectedHusband)
            <div class="p-4 bg-brand-500/10 border border-brand-500/20 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs font-bold text-brand-700 dark:text-brand-300">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-brand-600/20 flex items-center justify-center text-brand-600 dark:text-brand-400 text-sm">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div>
                        <span class="text-slate-500 dark:text-slate-400 block text-[11px] font-normal">الزوج المختار لفلترة السجلات الإضافة:</span>
                        <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $selectedHusband->name }}</span>
                    </div>
                </div>
                <a href="{{ route('system.wives.create', ['personal_data_id' => $selectedHusband->id]) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-xs font-semibold shadow-xs transition-all">
                    <i class="fas fa-plus"></i>
                    إضافة زوجة جديدة لـ {{ $selectedHusband->name }}
                </a>
            </div>
        @endif
    @endif

    {{-- Data table --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 font-bold text-xs uppercase tracking-wider">
                        <th class="px-6 py-4">#</th>
                        <th class="px-6 py-4">اسم الزوجة</th>
                        <th class="px-6 py-4">الزوج المرتبط</th>
                        <th class="px-6 py-4">الهوية / الهاتف</th>
                        <th class="px-6 py-4">المحافظة / المديرية</th>
                        <th class="px-6 py-4 text-center">الحالة</th>
                        <th class="px-6 py-4 text-left">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                    @forelse($wives as $wife)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/25 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-mono text-xs text-slate-400">#{{ str_pad($wife->id, 4, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-pink-500/10 border border-pink-500/20 text-pink-600 dark:text-pink-400 flex items-center justify-center font-bold text-xs">
                                        <i class="fas fa-female"></i>
                                    </div>
                                    <div>
                                        <a href="{{ route('system.wives.show', $wife->id) }}" class="font-bold text-slate-900 dark:text-white hover:text-brand-600 dark:hover:text-brand-400 transition-colors block">
                                            {{ $wife->name ?? $wife->wife_name ?? '-' }}
                                        </a>
                                        @if($wife->wife_birth_date)
                                            <span class="text-xs text-slate-400">{{ $wife->wife_birth_date }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($wife->personalData)
                                    <div class="flex items-center gap-2.5">
                                        @if($wife->personalData->photo_url)
                                            <img src="{{ $wife->personalData->photo_url }}" class="w-8 h-8 rounded-full object-cover border border-slate-200 dark:border-slate-700 shadow-sm shrink-0" alt="{{ $wife->personalData->name }}">
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-400 text-xs shrink-0">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <a href="{{ route('system.personal-data.show', $wife->personalData->id) }}" class="font-bold text-brand-600 dark:text-brand-400 hover:underline block leading-tight">
                                                {{ $wife->personalData->name }}
                                            </a>
                                            @if($wife->personalData->jihadi_name)
                                                <span class="text-[10px] text-slate-400 font-medium">({{ $wife->personalData->jihadi_name }})</span>
                                            @endif
                                            <a href="{{ route('system.wives.create', ['personal_data_id' => $wife->personal_data_id]) }}" class="text-[10px] text-emerald-600 dark:text-emerald-400 hover:underline font-semibold flex items-center gap-1 mt-0.5" title="إضافة زوجة جديدة لهذا الزوج">
                                                <i class="fas fa-plus-circle text-[9px]"></i> إضافة زوجة لـ هذا الزوج
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs">
                                    <div class="font-mono text-slate-800 dark:text-slate-200 dark:text-slate-200 font-semibold">{{ $wife->wife_id_number ?? '-' }}</div>
                                    <div class="text-slate-400 font-mono text-[11px]">{{ $wife->wife_phone ?? '-' }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-300 text-xs">
                                {{ ($wife->governorate->name ?? '') . ($wife->directorate ? ' - ' . $wife->directorate->name : '') }}
                                @if(!$wife->governorate && !$wife->directorate)
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($wife->status == 'على ذمته' || $wife->status == 'نشط' || $wife->status == 'مستمرة')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        {{ $wife->status }}
                                    </span>
                                @elseif($wife->status)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-slate-500/10 text-slate-600 dark:text-slate-400 border border-slate-500/20">
                                        {{ $wife->status }}
                                    </span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('system.wives.show', $wife->id) }}"
                                        class="p-2 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-brand-50 dark:hover:bg-brand-950/50 transition-colors"
                                        title="عرض">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                    <a href="{{ route('system.wives.edit', $wife->id) }}"
                                        class="p-2 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-950/50 transition-colors"
                                        title="تعديل">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>
                                    <form action="{{ route('system.wives.destroy', $wife->id) }}"
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
                                        <i class="fas fa-female text-slate-300 dark:text-slate-600 text-2xl"></i>
                                    </div>
                                    <p class="text-slate-500 dark:text-slate-400 font-medium">لا توجد سجلات زوجات مسجلة حالياً</p>
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
        {{ $wives->withQueryString()->links() }}
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof $ !== 'undefined' && $.fn.select2) {
            $('#personal_data_id_filter').select2({
                dir: "rtl",
                language: "ar",
                placeholder: "-- فرز بالزوج/البيانات الشخصية (الكل) --",
                allowClear: true,
                tags: true,
                width: '100%'
            }).on('select2:select select2:clear', function () {
                this.form.submit();
            });
        }
    });
</script>
@endsection

