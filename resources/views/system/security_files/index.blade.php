@extends('layouts.app')

@section('title', 'إدارة الملفات الأمنية')
@section('page_title', 'إدارة الأفراد')

@section('content')
<div class="space-y-6" dir="rtl">

    {{-- Header with Stats & Actions --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-2 print:hidden">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center font-bold shadow-sm shrink-0">
                <i class="fas fa-shield-alt text-xl"></i>
            </div>
            <div>
                <h1 class="font-display font-bold text-2xl text-slate-900 dark:text-white m-0">سجل الملفات الأمنية</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    إدارة وتوثيق ملفات وتلخيصات الأفراد والتكليفات والوضع الجهادي.
                    (إجمالي: <span class="font-bold text-slate-700 dark:text-slate-200">{{ $totalCount }}</span> ملف)
                </p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <x-print-button :url="route('system.security-files.print-all', request()->all())" text="طباعة الكشف" />
            <a href="{{ route('system.security-files.export', request()->all()) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all">
                <i class="fas fa-file-excel"></i>
                <span>تصدير Excel</span>
            </a>
            <a href="{{ route('system.security-files.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all">
                <i class="fas fa-plus-circle"></i>
                <span>إضافة ملف أمني جديد</span>
            </a>
        </div>
    </div>

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

    {{-- Filter & Search Form --}}
    <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm print:hidden">
        <form method="GET" action="{{ route('system.security-files.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">البحث السريع</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="ابحث برقم الملف، الفرد، أوالرقم العسكري..."
                        class="w-full pl-4 pr-10 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 transition-colors">
                    <i class="fas fa-search absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">الفرد المرتبط بالملف</label>
                <select name="personal_data_id" onchange="this.form.submit()" class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 transition-colors">
                    <option value="">جميع الأفراد</option>
                    @foreach($personalDataList as $p)
                        <option value="{{ $p->id }}" {{ request('personal_data_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->name }} {{ $p->jihadi_name ? '('.$p->jihadi_name.')' : '' }} {{ $p->military_number ? '- م: '.$p->military_number : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">الجهة المعدة للملف</label>
                <select name="preparing_entity_id" class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 transition-colors">
                    <option value="">جميع الجهات</option>
                    @foreach($entities as $id => $name)
                        <option value="{{ $id }}" {{ request('preparing_entity_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="w-full py-2.5 bg-slate-900 dark:bg-slate-800 text-white font-bold rounded-xl text-xs hover:bg-slate-800 transition-colors flex items-center justify-center gap-2">
                    <i class="fas fa-filter"></i> تصفية النتائج
                </button>
                @if(request()->hasAny(['search', 'personal_data_id', 'preparing_entity_id']))
                    <a href="{{ route('system.security-files.index') }}" class="px-4 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-xl text-xs font-bold hover:bg-slate-200 transition-colors">
                        إلغاء
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Data Table --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 text-xs font-bold">
                        <th class="px-6 py-4">رقم الملف</th>
                        <th class="px-6 py-4">الفرد المرتبط</th>
                        <th class="px-6 py-4">الجهة المعدة للملف</th>
                        <th class="px-6 py-4">تاريخ الملف (ميلادي/هجري)</th>
                        <th class="px-6 py-4">المعد ومعد التلخيص</th>
                        <th class="px-6 py-4 text-center">المرفقات</th>
                        <th class="px-6 py-4 text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs text-slate-700 dark:text-slate-300 font-medium">
                    @forelse($securityFiles as $file)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="px-6 py-4 font-mono font-bold text-brand-600 dark:text-brand-400">
                                {{ $file->file_number ?? 'غير مدون' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($file->personalData && $file->personalData->photo_url)
                                        <img src="{{ $file->personalData->photo_url }}" class="w-10 h-10 rounded-full object-cover border border-slate-200 dark:border-slate-700 shadow-sm shrink-0" alt="{{ $file->personalData->name }}">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-400 text-xs shrink-0 font-bold">
                                            <i class="fas fa-user-shield"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-bold text-slate-900 dark:text-white">{{ $file->personalData->name ?? 'غير محدد' }}</div>
                                        <div class="text-[11px] text-slate-400">
                                            {{ $file->personalData->jihadi_name ? 'الجهادي: ' . $file->personalData->jihadi_name : '' }}
                                            {{ $file->personalData->military_number ? ' | عسكري: ' . $file->personalData->military_number : '' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 bg-slate-100 dark:bg-slate-800 rounded-lg text-xs font-semibold">
                                    {{ $file->preparingEntity->name ?? 'غير محدد' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div>{{ $file->file_date_gregorian ? $file->file_date_gregorian->format('Y/m/d') : '-' }}</div>
                                <div class="text-[11px] text-slate-400 font-mono">{{ $file->file_date_hijri ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div><strong>المعد:</strong> {{ $file->file_preparer ?? '-' }}</div>
                                <div class="text-[11px] text-slate-400"><strong>الملخص:</strong> {{ $file->summary_preparer ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $filesCount = count($file->file_attachments ?? []);
                                    $sumCount = count($file->summary_attachments ?? []);
                                    $totalAtt = $filesCount + $sumCount;
                                @endphp
                                @if($totalAtt > 0)
                                    <span class="px-2.5 py-1 bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 rounded-full font-bold text-[11px]">
                                        <i class="fas fa-paperclip me-1"></i> {{ $totalAtt }} مرفق
                                    </span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('system.security-files.print', $file) }}" target="_blank"
                                        class="w-8 h-8 flex items-center justify-center rounded-xl bg-purple-50 dark:bg-purple-950 text-purple-600 dark:text-purple-400 hover:bg-purple-600 hover:text-white transition-all shadow-xs" title="طباعة الملف">
                                        <i class="fas fa-print text-xs"></i>
                                    </a>
                                    <a href="{{ route('system.security-files.show', $file) }}"
                                        class="w-8 h-8 flex items-center justify-center rounded-xl bg-brand-50 dark:bg-brand-950 text-brand-600 dark:text-brand-400 hover:bg-brand-600 hover:text-white transition-all shadow-xs" title="عرض الملف">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                    <a href="{{ route('system.security-files.edit', $file) }}"
                                        class="w-8 h-8 flex items-center justify-center rounded-xl bg-amber-50 dark:bg-amber-950 text-amber-600 dark:text-amber-400 hover:bg-amber-600 hover:text-white transition-all shadow-xs" title="تعديل">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>
                                    <form action="{{ route('system.security-files.destroy', $file) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا الملف الأمني؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-xl bg-rose-50 dark:bg-rose-950 text-rose-600 dark:text-rose-400 hover:bg-rose-600 hover:text-white transition-all shadow-xs cursor-pointer" title="حذف">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-slate-400">
                                <i class="fas fa-folder-open text-3xl mb-2 block"></i>
                                لا توجد ملفات أمنية مسجلة حالياً
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($securityFiles->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50">
                {{ $securityFiles->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
