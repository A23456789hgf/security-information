@extends('layouts.app')

@section('title', 'البيانات الثانوية')

@section('content')
<div class="container-fluid py-6" dir="rtl">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row flex-wrap gap-4 md:items-center justify-between gap-6 mb-8 max-w-7xl mx-auto">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-slate-800 dark:bg-slate-700 rounded-2xl shadow-lg flex items-center justify-center text-white">
                <i class="fas fa-database text-2xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white header-font mb-1">البيانات الثانوية</h2>
                <p class="text-slate-500 dark:text-slate-400 text-xs flex items-center gap-2">
                    إدارة السجلات التكميلية، الحالة الصحية، الأولاد، الانتماءات، والأعمال الجهادية
                    <span class="inline-flex items-center justify-center bg-brand-100 dark:bg-brand-950/60 text-brand-700 dark:text-brand-300 border border-brand-200 dark:border-brand-800 px-2.5 py-0.5 rounded-full text-xs font-bold">
                        {{ $secondaryDataList->total() }} سجل
                    </span>
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2.5 flex-wrap">
            <x-print-button :url="route('system.secondary-data.print-all', request()->all())" text="طباعة القائمة" />
            <a href="{{ route('system.secondary-data.export', request()->all()) }}"
                class="flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-xl font-bold text-xs transition-all shadow-sm">
                <i class="fas fa-file-excel"></i>
                <span>تصدير Excel</span>
            </a>
            <a href="{{ route('data.import.index', ['type' => 'secondary_data']) }}"
                class="flex items-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl font-bold text-xs transition-all shadow-sm">
                <i class="fas fa-file-import"></i>
                <span>استيراد</span>
            </a>
            <a href="{{ route('system.secondary-data.create') }}"
                class="flex items-center gap-1.5 bg-brand-600 hover:bg-brand-700 text-white px-5 py-2.5 rounded-xl font-bold text-xs transition-all shadow-md">
                <i class="fas fa-plus"></i>
                <span>إضافة سجل جديد</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800/80 rounded-2xl p-4 mb-6 max-w-7xl mx-auto flex items-center gap-3 text-emerald-800 dark:text-emerald-200 text-xs font-bold">
            <i class="fas fa-check-circle text-emerald-600 dark:text-emerald-400 text-base"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- بطاقة البحث والتصفية -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 p-4 mb-6 max-w-7xl mx-auto">
        <form method="GET" action="{{ route('system.secondary-data.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
            <div class="md:col-span-4">
                <div class="relative group">
                    <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                        <i class="fas fa-search text-xs"></i>
                    </div>
                    <input type="text" name="search" class="w-full pr-9 pl-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-brand-500 focus:bg-white dark:focus:bg-slate-900 transition-all font-medium" 
                           placeholder="ابحث بالاسم أو الرقم..." 
                           value="{{ request('search') }}">
                </div>
            </div>
            <div class="md:col-span-3">
                <select name="personal_data_id" onchange="this.form.submit()" class="w-full py-2 px-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-brand-500 focus:bg-white dark:focus:bg-slate-900 transition-all font-medium">
                    <option value="">-- فرز بالبيانات الشخصية (الكل) --</option>
                    @foreach($personalDataList as $p)
                        <option value="{{ $p->id }}" {{ request('personal_data_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->name }} {{ $p->jihadi_name ? '('.$p->jihadi_name.')' : '' }} {{ $p->military_number ? '- م: '.$p->military_number : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <select name="health_status_id" class="w-full py-2 px-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-brand-500 focus:bg-white dark:focus:bg-slate-900 transition-all font-medium">
                    <option value="">-- الحالة الصحية --</option>
                    @foreach($healthStatuses as $id => $name)
                        <option value="{{ $id }}" {{ request('health_status_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-1">
                <select name="per_page" class="w-full py-2 px-1 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-brand-500 focus:bg-white dark:focus:bg-slate-900 transition-all font-medium">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 || !request('per_page') ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                </select>
            </div>
            <div class="md:col-span-2 flex gap-2">
                <button type="submit" class="flex-1 py-2 bg-slate-800 dark:bg-slate-700 hover:bg-slate-900 dark:hover:bg-slate-600 text-white rounded-xl font-bold text-xs transition-all shadow-sm flex items-center justify-center gap-1.5 cursor-pointer">
                    <i class="fas fa-filter text-[10px]"></i> تصفية
                </button>
                @if(request()->anyFilled(['search', 'personal_data_id', 'health_status_id', 'per_page']))
                    <a href="{{ route('system.secondary-data.index') }}" class="px-3 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl font-bold text-xs transition-all flex items-center justify-center">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    @if($secondaryDataList->isEmpty())
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 py-16 text-center max-w-7xl mx-auto">
            <div class="w-20 h-20 bg-slate-50 dark:bg-slate-800/60 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300 dark:text-slate-600">
                <i class="fas fa-folder-open text-3xl"></i>
            </div>
            <h4 class="text-slate-700 dark:text-slate-200 header-font text-lg font-bold mb-1">لا توجد بيانات ثانوية</h4>
            <p class="text-slate-400 dark:text-slate-500 text-xs mb-5">لم يتم العثور على أي سجلات بيانات ثانوية مطابقة.</p>
            <a href="{{ route('system.secondary-data.create') }}" class="inline-flex items-center gap-2 bg-brand-600 text-white px-5 py-2.5 rounded-xl font-bold text-xs hover:bg-brand-700 transition-all shadow-sm">
                <i class="fas fa-plus text-xs"></i> إضافة سجل جديد
            </a>
        </div>
    @else
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden max-w-7xl mx-auto">
            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs" style="min-width: 1000px;">
                    <thead class="bg-slate-50/80 dark:bg-slate-950/60 border-b border-slate-200 dark:border-slate-800 text-[11px] font-bold text-slate-500 dark:text-slate-400">
                        <tr>
                            <th class="px-5 py-3.5">الشخص / البيانات الأساسية</th>
                            <th class="px-4 py-3.5">الحالة الصحية</th>
                            <th class="px-4 py-3.5 text-center">عدد الأولاد</th>
                            <th class="px-4 py-3.5">عام الانطلاقة / المسؤول</th>
                            <th class="px-4 py-3.5 text-center">الانتماءات والأعمال</th>
                            <th class="px-4 py-3.5 text-center">الأسلحة والعهدة</th>
                            <th class="px-5 py-3.5 text-center w-28">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                        @foreach($secondaryDataList as $record)
                        <tr class="group hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-colors">
                            {{-- Person Info Column --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    @if($record->personalData && $record->personalData->photo_url)
                                        <img src="{{ $record->personalData->photo_url }}" class="w-10 h-10 rounded-full object-cover border border-slate-200 dark:border-slate-700 shadow-sm shrink-0" alt="{{ $record->personalData->name }}">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-400 text-sm shrink-0">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-bold text-slate-800 dark:text-slate-100 text-sm mb-0.5">
                                            {{ $record->personalData->name ?? '-' }}
                                        </div>
                                        @if($record->personalData)
                                            @php $pData = $record->personalData; @endphp
                                            <div class="flex flex-wrap items-center gap-1.5 text-[10px] text-slate-500 dark:text-slate-400">
                                                @if($pData->jihadi_name)
                                                    <span class="bg-slate-100 dark:bg-slate-800 border border-slate-200/60 dark:border-slate-700/60 px-1.5 py-0.5 rounded text-slate-700 dark:text-slate-300 font-medium">
                                                        <i class="fas fa-user-tag text-brand-500 ml-0.5"></i> {{ $pData->jihadi_name }}
                                                    </span>
                                                @endif
                                                @if($pData->military_number)
                                                    <span class="bg-slate-100 dark:bg-slate-800 border border-slate-200/60 dark:border-slate-700/60 px-1.5 py-0.5 rounded text-slate-700 dark:text-slate-300 font-medium">
                                                        <i class="fas fa-id-badge text-slate-400 ml-0.5"></i> {{ $pData->military_number }}
                                                    </span>
                                                @endif
                                                @if($pData->bloodType)
                                                    <span class="bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-900/50 px-1.5 py-0.5 rounded text-rose-600 dark:text-rose-400 font-bold">
                                                <i class="fas fa-tint ml-0.5"></i> {{ $pData->bloodType->type }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </td>

                            {{-- Health Status --}}
                            <td class="px-4 py-3.5">
                                @if($record->healthStatus)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-2xl text-xs font-bold bg-teal-50 dark:bg-teal-950/50 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-800/80">
                                        <i class="fas fa-heartbeat text-teal-500"></i>
                                        {{ $record->healthStatus->name }}
                                    </span>
                                @else
                                    <span class="text-slate-400 dark:text-slate-600">-</span>
                                @endif
                            </td>

                            {{-- Children Counts --}}
                            <td class="px-4 py-3.5 text-center">
                                @php
                                    $tot = $record->total_children_count ?? (($record->male_children_count ?? 0) + ($record->female_children_count ?? 0));
                                @endphp
                                @if($tot > 0)
                                    <span class="inline-flex items-center gap-1 bg-purple-50 dark:bg-purple-950/50 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800/80 px-2.5 py-1 rounded-xl text-xs font-extrabold">
                                        <i class="fas fa-users text-purple-500"></i> {{ $tot }}
                                    </span>
                                @else
                                    <span class="text-slate-400 dark:text-slate-600 font-medium">0</span>
                                @endif
                            </td>

                            {{-- Dates & Supervisor --}}
                            <td class="px-4 py-3.5">
                                <div class="text-slate-800 dark:text-slate-200 font-medium">
                                    <i class="far fa-calendar-alt text-slate-400 dark:text-slate-500 ml-1"></i>
                                    {{ $record->start_date ? $record->start_date->format('Y') : '-' }}
                                </div>
                                <div class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5">
                                    <i class="fas fa-user-shield text-slate-300 dark:text-slate-600 ml-1"></i>
                                    {{ $record->direct_supervisor ?? 'بدون مسؤول' }}
                                </div>
                            </td>

                            {{-- Affiliations & Jihadist Actions Badges --}}
                            <td class="px-4 py-3.5 text-center">
                                <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                    @if($record->affiliations->count() > 0)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-bold bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800/80" title="عدد الانتماءات السياسية والعقائدية">
                                            <i class="fas fa-landmark text-indigo-500"></i>
                                            {{ $record->affiliations->count() }} انتماء
                                        </span>
                                    @endif
                                    @if($record->previousJihadistActions->count() > 0)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-bold bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800/80" title="عدد الأعمال الجهادية السابقة">
                                            <i class="fas fa-fist-raised text-amber-500"></i>
                                            {{ $record->previousJihadistActions->count() }} عمل سابق
                                        </span>
                                    @endif
                                    @if($record->affiliations->count() == 0 && $record->previousJihadistActions->count() == 0)
                                        <span class="text-slate-400 dark:text-slate-600 text-[11px]">-</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Weapons Count --}}
                            <td class="px-4 py-3.5 text-center">
                                @if($record->weapons->count() > 0)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-xs font-bold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/80">
                                        <i class="fas fa-shield-alt text-emerald-500"></i>
                                        {{ $record->weapons->count() }} قطعة
                                    </span>
                                @else
                                    <span class="text-slate-400 dark:text-slate-600">لا يوجد</span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-5 py-3.5 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('system.secondary-data.print', $record) }}" target="_blank" class="w-8 h-8 flex items-center justify-center rounded-xl bg-purple-50 dark:bg-purple-950/50 text-purple-600 dark:text-purple-400 hover:bg-purple-600 hover:text-white dark:hover:bg-purple-600 dark:hover:text-white transition-all shadow-xs" title="طباعة السجل">
                                        <i class="fas fa-print text-xs"></i>
                                    </a>
                                    <a href="{{ route('system.secondary-data.show', $record) }}" class="w-8 h-8 flex items-center justify-center rounded-xl bg-brand-50 dark:bg-brand-950/50 text-brand-600 dark:text-brand-400 hover:bg-brand-600 hover:text-white dark:hover:bg-brand-600 dark:hover:text-white transition-all shadow-xs" title="عرض التفاصيل">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                    <a href="{{ route('system.secondary-data.edit', $record) }}" class="w-8 h-8 flex items-center justify-center rounded-xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 hover:bg-amber-600 hover:text-white dark:hover:bg-amber-600 dark:hover:text-white transition-all shadow-xs" title="تعديل">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>
                                    <form action="{{ route('system.secondary-data.destroy', $record) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا السجل؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-xl bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 hover:bg-rose-600 hover:text-white dark:hover:bg-rose-600 dark:hover:text-white transition-all shadow-xs cursor-pointer" title="حذف">
                                            <i class="fas fa-trash-alt text-xs"></i>
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

        <div class="mt-6 flex justify-center max-w-7xl mx-auto">
            {{ $secondaryDataList->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
