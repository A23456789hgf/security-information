@extends('layouts.app')

@section('title', 'سجل الرد الميداني')
@section('page_title', 'السجلات الأمنية')

@section('content')
<div class="space-y-6" dir="rtl">
    {{-- Breadcrumbs --}}
    <nav class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-2 print:hidden">
        <a href="{{ route('dashboard') }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">الرئيسية</a>
        <span>/</span>
        <span class="text-slate-900 dark:text-white font-semibold">الرد الميداني</span>
    </nav>

    {{-- Header & Actions --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-2 print:hidden">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center font-bold shadow-sm shrink-0">
                <i class="fas fa-satellite-dish text-xl"></i>
            </div>
            <div>
                <h1 class="font-display font-bold text-2xl text-slate-900 dark:text-white m-0">سجل الرد الميداني</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">إدارة ومتابعة كافة الردود الميدانية المسجلة للأشخاص المعنيين.</p>
            </div>
        </div>
        
        <div class="flex flex-wrap items-center gap-2.5">
            <x-print-button :url="route('system.field-responses.print-all', request()->all())" text="طباعة الكشف" />
            <a href="{{ route('system.field-responses.export', request()->all()) }}"
                class="inline-flex items-center gap-2 px-3.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors text-xs sm:text-sm font-bold shadow-sm">
                <i class="fas fa-file-excel text-emerald-600"></i>
                <span>تصدير Excel</span>
            </a>
            <a href="{{ route('system.field-responses.import') }}"
                class="inline-flex items-center gap-2 px-3.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors text-xs sm:text-sm font-bold shadow-sm">
                <i class="fas fa-file-import text-brand-600"></i>
                <span>استيراد</span>
            </a>
            <a href="{{ route('system.field-responses.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-600 text-white rounded-xl hover:bg-brand-700 transition-all text-xs sm:text-sm font-bold shadow-sm shadow-brand-500/30">
                <i class="fas fa-plus"></i>
                <span>إضافة رد ميداني جديد</span>
            </a>
        </div>
    </div>

    {{-- System Alerts --}}
    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-800 dark:text-emerald-300 px-4 py-3 rounded-2xl flex items-center justify-between shadow-sm" role="alert">
            <div class="flex items-center gap-3">
                <i class="fas fa-check-circle text-emerald-500 text-base"></i>
                <span class="text-sm font-bold">{{ session('success') }}</span>
            </div>
            <button type="button" class="text-emerald-400 hover:text-emerald-600 p-1" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    {{-- Search and Filter Toolbar --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-4 shadow-sm transition-colors">
        <form method="GET" action="{{ route('system.field-responses.index') }}" class="flex flex-col md:flex-row items-center gap-3">
            <div class="relative flex-1 w-full">
                <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                    <i class="fas fa-search"></i>
                </div>
                <input type="text" name="search"
                    class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl pr-10 pl-4 py-2.5 text-sm text-slate-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all"
                    placeholder="ابحث باسم الشخص، نص الرد الميداني، المصدر، عمل المصدر، الملاحظات..." value="{{ request('search') }}">
            </div>
            <div class="w-full md:w-48">
                @include('partials._pagination_limit')
            </div>
            <div class="flex items-center gap-2 w-full md:w-auto">
                <button type="submit"
                    class="w-full md:w-auto px-5 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-sm font-bold transition-colors">
                    تصفية النتائج
                </button>
                @if(request('search'))
                    <a href="{{ route('system.field-responses.index') }}"
                        class="px-3 py-2.5 rounded-xl text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 text-sm font-semibold transition-colors"
                        title="مسح البحث">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Data table Container --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden transition-colors">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-right">
                <thead class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 font-bold">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider w-16">#</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">الشخص المعني</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">الرد الميداني</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center">تاريخ الرد</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">المصدر / العمل</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">الجهة / الملاحظات</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center w-36">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @forelse($responses as $resp)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-slate-500 font-bold">
                                {{ $loop->iteration + ($responses->currentPage() - 1) * $responses->perPage() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden shrink-0 flex items-center justify-center border border-slate-200 dark:border-slate-700">
                                        @if($resp->personalData && ($resp->personalData->photo_path || $resp->personalData->photo))
                                            <img src="{{ asset('storage/' . ($resp->personalData->photo_path ?? $resp->personalData->photo)) }}" alt="{{ $resp->personalData->name }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-user text-slate-400"></i>
                                        @endif
                                    </div>
                                    <div>
                                        @if($resp->personalData)
                                            <a href="{{ route('system.personal-data.show', $resp->personalData->id) }}" class="font-bold text-slate-900 dark:text-white hover:text-brand-600 dark:hover:text-brand-400 transition-colors">
                                                {{ $resp->personalData->name }}
                                            </a>
                                            <div class="text-xs text-slate-400 mt-0.5">
                                                هوية: {{ $resp->personalData->national_id_number ?? '-' }}
                                            </div>
                                        @else
                                            <span class="text-slate-400 font-medium">غير متاح</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-800 dark:text-slate-200 line-clamp-2 max-w-xs" title="{{ $resp->field_response }}">
                                    {{ $resp->field_response }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-slate-600 dark:text-slate-300">
                                {{ $resp->response_date ? $resp->response_date->format('Y-m-d') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-bold text-slate-800 dark:text-slate-200">
                                    {{ $resp->source ?: '-' }}
                                </div>
                                <div class="text-xs text-slate-400 mt-0.5">
                                    {{ $resp->source_job ?: '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                    {{ $resp->entity->name ?? 'غير محدد' }}
                                </span>
                                @if($resp->notes)
                                    <div class="text-xs text-slate-400 mt-1 line-clamp-1 max-w-xs" title="{{ $resp->notes }}">
                                        {{ $resp->notes }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('system.field-responses.show', $resp->id) }}"
                                        class="p-2 text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors" title="عرض التفاصيل">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <a href="{{ route('system.field-responses.edit', $resp->id) }}"
                                        class="p-2 text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors" title="تعديل">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    <a href="{{ route('system.field-responses.print', $resp->id) }}" target="_blank"
                                        class="p-2 text-slate-400 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors" title="طباعة">
                                        <i class="fas fa-print text-sm"></i>
                                    </a>
                                    <form action="{{ route('system.field-responses.destroy', $resp->id) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت تأكد من رغبتك في حذف هذا الرد الميداني؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors" title="حذف">
                                            <i class="fas fa-trash-alt text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500">
                                <div class="flex flex-col items-center gap-3">
                                    <i class="fas fa-satellite-dish text-4xl opacity-30"></i>
                                    <span class="text-base font-semibold">لا توجد ردود ميدانية مسجلة حتى الآن</span>
                                    <a href="{{ route('system.field-responses.create') }}" class="text-xs text-brand-600 dark:text-brand-400 font-bold hover:underline">إضافة رد ميداني جديد</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($responses->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800">
                {{ $responses->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
