@extends('layouts.app')

@section('title', 'إدارة البيانات الشخصية')
@section('page_title', 'السجلات الأساسية')

@section('content')
<div class="space-y-6" dir="rtl">
    {{-- Breadcrumbs --}}
    <nav class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400 mb-4 print:hidden" aria-label="Breadcrumb">
        <a href="{{ route('dashboard') }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors font-medium">الرئيسية</a>
        <i class="fas fa-chevron-left text-[10px] text-slate-400"></i>
        <span class="text-slate-900 dark:text-white font-bold" aria-current="page">إدارة السجلات الأساسية</span>
    </nav>

    {{-- Header & Metrics --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg shadow-sm p-5 print:hidden">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-5">
            
            {{-- Title & Stats --}}
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center border border-brand-100 dark:border-brand-500/20 shrink-0">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div>
                    <h1 class="font-bold text-2xl text-slate-900 dark:text-white m-0 tracking-tight">البيانات الشخصية</h1>
                    <div class="flex items-center gap-4 mt-1.5 text-sm text-slate-600 dark:text-slate-400">
                        <span class="flex items-center gap-1.5" title="إجمالي الأشخاص المسجلين في النظام">
                            <i class="fas fa-database text-slate-400"></i>
                            إجمالي الأشخاص: <strong class="text-slate-900 dark:text-slate-100">{{ number_format($personalDataList->total()) }}</strong>
                        </span>
                        <span class="w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                        <span class="flex items-center gap-1.5 text-emerald-600 dark:text-emerald-400" title="الأشخاص الذين تمت إضافتهم اليوم">
                            <i class="fas fa-arrow-trend-up"></i>
                            مُدخلات اليوم: <strong>{{ $personalDataList->getCollection()->where('created_at', '>=', now()->startOfDay())->count() }}</strong>
                        </span>
                    </div>
                </div>
            </div>

            {{-- Primary Actions --}}
            <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                <div class="flex items-center bg-slate-50 dark:bg-slate-800 p-1 rounded-md border border-slate-200 dark:border-slate-700">
                    <a href="{{ route('system.personal-data.export') }}" class="px-3 py-1.5 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-white dark:hover:bg-slate-700 hover:shadow-sm rounded transition-all" title="تصدير (Excel)">
                        <i class="fas fa-file-excel text-emerald-600 ml-1"></i> تصدير
                    </a>
                    <div class="w-px h-4 bg-slate-300 dark:bg-slate-600 mx-1"></div>
                    <a href="{{ route('system.personal-data.import') }}" class="px-3 py-1.5 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-white dark:hover:bg-slate-700 hover:shadow-sm rounded transition-all" title="استيراد دفعات">
                        <i class="fas fa-file-import text-brand-600 ml-1"></i> استيراد
                    </a>
                </div>

                <x-print-button :url="route('system.personal-data.print-list', request()->all())" text="طباعة الكشف" />

                <a href="{{ route('system.personal-data.fast-entry') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-amber-50 border border-amber-200 text-amber-700 hover:bg-amber-100 text-sm font-bold shadow-sm transition-colors dark:bg-amber-500/10 dark:border-amber-500/20 dark:text-amber-400 dark:hover:bg-amber-500/20">
                    <i class="fas fa-bolt"></i> إدخال سريع
                </a>
                
                <a href="{{ route('system.personal-data.create') }}" class="inline-flex items-center gap-2 px-5 py-2 rounded-md bg-brand-600 text-white hover:bg-brand-700 text-sm font-bold shadow-sm transition-all focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900">
                    <i class="fas fa-user-plus"></i> إضافة شخص جديد
                </a>
            </div>
        </div>
    </div>

    {{-- System Alerts --}}
    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-500/10 border-l-4 border-emerald-500 text-emerald-800 dark:text-emerald-300 px-4 py-3 rounded-md shadow-sm flex items-center justify-between" role="alert">
            <div class="flex items-center gap-3">
                <i class="fas fa-check-circle text-emerald-500"></i>
                <span class="text-sm font-bold">{{ session('success') }}</span>
            </div>
            <button type="button" class="text-emerald-500 hover:text-emerald-700 p-1" onclick="this.parentElement.remove()" aria-label="إغلاق">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    {{-- Data Grid Container --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg shadow-sm overflow-hidden">
        
        {{-- Toolbar (Search & Filters) --}}
        <div class="p-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/20">
            <form method="GET" action="{{ route('system.personal-data.index') }}" class="flex flex-col md:flex-row items-center gap-3">
                <div class="relative flex-1 w-full">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <i class="fas fa-search"></i>
                    </div>
                    <input type="text" name="search" 
                        class="w-full bg-white dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-md pr-10 pl-4 py-2 text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500 transition-all shadow-sm" 
                        placeholder="بحث برقم الهوية، الرقم العسكري، أو الاسم الرباعي..." 
                        value="{{ request('search') }}">
                </div>
                
                @if(isset($allPeople) && $allPeople->count() > 0)
                <div class="w-full md:w-64">
                    <select name="personal_data_id" onchange="this.form.submit()" class="w-full bg-white dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-md px-3 py-2 text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-brand-500 shadow-sm">
                        <option value="">-- فرز بالشخص المعني --</option>
                        @foreach($allPeople as $person)
                            <option value="{{ $person->id }}" {{ request('personal_data_id') == $person->id ? 'selected' : '' }}>
                                {{ $person->name }} {{ $person->jihadi_name ? '('.$person->jihadi_name.')' : '' }} {{ $person->military_number ? '- م: '.$person->military_number : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="w-full md:w-48">
                    @include('partials._pagination_limit')
                </div>
                
                <div class="flex items-center gap-2 w-full md:w-auto">
                    <button type="submit" class="w-full md:w-auto px-6 py-2 rounded-md bg-slate-800 text-white hover:bg-slate-700 dark:bg-slate-700 dark:hover:bg-slate-600 text-sm font-bold shadow-sm transition-colors">
                        استعلام
                    </button>
                    @if(request('search') || request('personal_data_id'))
                        <a href="{{ route('system.personal-data.index') }}" class="px-4 py-2 rounded-md border border-slate-300 dark:border-slate-600 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 text-sm font-semibold transition-colors bg-white dark:bg-slate-900" title="إلغاء التصفية">
                            إلغاء
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Enterprise Data Table --}}
        <div class="overflow-x-auto min-h-[360px]">
            <table class="w-full text-sm text-right whitespace-nowrap">
                <thead class="bg-slate-100 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300">
                    <tr>
                        <th scope="col" class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider w-20 border-r border-slate-200 dark:border-slate-700"># المرجع</th>
                        <th scope="col" class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider">بيانات السجل (الاسم الكامل)</th>
                        <th scope="col" class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider">المعرفات الرسمية</th>
                        <th scope="col" class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider">تاريخ الميلاد</th>
                        <th scope="col" class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider">حالة القيد</th>
                        <th scope="col" class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider text-left">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @forelse($personalDataList as $record)
                        <tr class="hover:bg-brand-50/50 dark:hover:bg-brand-900/10 even:bg-slate-50 dark:even:bg-slate-800/30 transition-colors">
                            
                            {{-- ID Column --}}
                            <td class="px-6 py-4 border-r border-slate-100 dark:border-slate-800 text-slate-500 dark:text-slate-400 font-mono text-xs">
                                {{ str_pad($record->id, 6, '0', STR_PAD_LEFT) }}
                            </td>
                            
                            {{-- Name Column --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($record->photo_url)
                                        <img src="{{ $record->photo_url }}" class="w-10 h-10 rounded-full object-cover border border-slate-200 dark:border-slate-700 shadow-sm shrink-0" alt="{{ $record->name }}">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 flex items-center justify-center font-bold text-xs shrink-0 border border-slate-300 dark:border-slate-600">
                                            {{ mb_substr($record->name ?? '-', 0, 2, 'utf-8') }}
                                        </div>
                                    @endif
                                    <div>
                                        <a href="{{ route('system.personal-data.show', $record) }}" class="font-bold text-slate-900 dark:text-white hover:text-brand-600 dark:hover:text-brand-400 hover:underline transition-all">
                                            {{ $record->name }}
                                        </a>
                                        @if($record->jihadi_name)
                                            <div class="text-[11px] text-slate-500 dark:text-slate-400 font-medium mt-0.5">
                                                <i class="fas fa-tag ml-1 text-slate-400"></i>{{ $record->jihadi_name }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            
                            {{-- Official IDs Column --}}
                            <td class="px-6 py-4">
                                <div class="space-y-1.5">
                                    @if($record->national_id_number)
                                        <div class="flex items-center gap-2 text-xs text-slate-700 dark:text-slate-300 font-mono">
                                            <span class="w-5 text-center"><i class="fas fa-id-card text-slate-400"></i></span>
                                            <span>{{ $record->national_id_number }}</span>
                                        </div>
                                    @endif
                                    @if($record->military_number)
                                        <div class="flex items-center gap-2 text-xs text-slate-700 dark:text-slate-300 font-mono">
                                            <span class="w-5 text-center"><i class="fas fa-shield-alt text-brand-500"></i></span>
                                            <span class="font-bold">{{ $record->military_number }}</span>
                                        </div>
                                    @endif
                                    @if(!$record->national_id_number && !$record->military_number)
                                        <span class="text-xs text-slate-400 italic">غير متوفر</span>
                                    @endif
                                </div>
                            </td>
                            
                            {{-- Date Column --}}
                            <td class="px-6 py-4 text-sm font-medium text-slate-700 dark:text-slate-300">
                                @if($record->date_of_birth_gregorian)
                                    <div class="flex items-center gap-2">
                                        <i class="far fa-calendar-alt text-slate-400"></i>
                                        <span dir="ltr">{{ $record->date_of_birth_gregorian->format('Y-m-d') }}</span>
                                    </div>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            
                            {{-- Status Column --}}
                            <td class="px-6 py-4">
                                <x-security-badge level="resolved" label="مكتمل" />
                            </td>
                            
                            {{-- Actions Column --}}
                            <td class="px-6 py-4 text-left">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('system.personal-data.show', $record) }}" class="p-2 rounded-lg bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-500/20 transition-all" title="استعراض الملف">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    
                                    <a href="{{ route('system.personal-data.edit', $record) }}" class="p-2 rounded-lg bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-500/20 transition-all" title="تعديل السجل">
                                        <i class="fas fa-pen text-sm"></i>
                                    </a>
                                    
                                    <form action="{{ route('system.personal-data.destroy', $record) }}" method="POST" class="inline m-0 p-0" onsubmit="return confirm('تحذير: سيتم حذف هذا السجل بشكل نهائي من قاعدة البيانات. هل أنت متأكد؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-500/20 transition-all" title="حذف القيد">
                                            <i class="fas fa-trash-alt text-sm"></i>
                                        </button>
                                    </form>

                                    {{-- Options Dropdown --}}
                                    <div class="relative inline-block text-right">
                                        <button onclick="toggleRowDropdown(this, event)" type="button" class="p-2 rounded-lg bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all focus:outline-none" title="خيارات إضافية">
                                            <i class="fas fa-ellipsis-v text-sm"></i>
                                        </button>
                                        <div class="dropdown-menu row-options-menu hidden origin-top-left absolute left-0 mt-2 w-56 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-200 dark:border-slate-700 py-1.5 z-50 text-xs">
                                            <a class="flex items-center px-4 py-2 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-brand-600 transition-colors" href="{{ route('system.personal-data.print', $record) }}" target="_blank">
                                                <i class="fas fa-print text-slate-400 w-5 ml-1"></i> طباعة البطاقة الأساسية
                                            </a>
                                            <a class="flex items-center px-4 py-2 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-brand-600 transition-colors" href="{{ route('system.personal-data.print-all', $record) }}" target="_blank">
                                                <i class="fas fa-file-contract text-slate-400 w-5 ml-1"></i> طباعة السجل الشامل
                                            </a>
                                            <div class="border-t border-slate-100 dark:border-slate-700 my-1"></div>
                                            <a class="flex items-center px-4 py-2 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors" href="{{ route('system.secondary-data.create', ['personal_data_id' => $record->id]) }}">
                                                <i class="fas fa-database text-purple-500 w-5 ml-1"></i> إلحاق بيانات ثانوية
                                            </a>
                                            <a class="flex items-center px-4 py-2 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors" href="{{ route('system.wives.create', ['personal_data_id' => $record->id]) }}">
                                                <i class="fas fa-heart text-pink-500 w-5 ml-1"></i> إضافة بيانات عائلية (زوجة)
                                            </a>
                                            <a class="flex items-center px-4 py-2 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors" href="{{ route('system.relatives.create', ['personal_data_id' => $record->id]) }}">
                                                <i class="fas fa-users text-indigo-500 w-5 ml-1"></i> ارتباطات (أهل وأقارب)
                                            </a>
                                            <a class="flex items-center px-4 py-2 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors" href="{{ route('system.courses.create', ['personal_data_id' => $record->id]) }}">
                                                <i class="fas fa-graduation-cap text-emerald-500 w-5 ml-1"></i> ربط دورات تدريبية
                                            </a>
                                            <a class="flex items-center px-4 py-2 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors" href="{{ route('system.arrests.create', ['personal_data_id' => $record->id]) }}">
                                                <i class="fas fa-gavel text-rose-500 w-5 ml-1"></i> تسجيل قيد توقيف
                                            </a>
                                            <div class="border-t border-slate-100 dark:border-slate-700 my-1"></div>
                                            <a class="flex items-center px-4 py-2 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-500/10 font-bold transition-colors" href="{{ route('system.security-files.create', ['personal_data_id' => $record->id]) }}">
                                                <i class="fas fa-shield-alt text-rose-500 w-5 ml-1"></i> إنشاء ملف أمني سري
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center bg-slate-50 dark:bg-slate-800/50">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center mb-4 text-slate-400 shadow-sm">
                                        <i class="fas fa-folder-open text-2xl"></i>
                                    </div>
                                    <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-1">لا توجد قيود مسجلة</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-5 max-w-sm">لم يتم العثور على أي سجلات تطابق معايير البحث الحالية، أو أن قاعدة البيانات فارغة.</p>
                                    <a href="{{ route('system.personal-data.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-brand-600 text-white hover:bg-brand-700 font-bold text-sm shadow-sm transition-all">
                                        <i class="fas fa-plus"></i> إدراج قيد جديد
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($personalDataList->hasPages())
            <div class="px-6 py-3 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900 flex items-center justify-between print:hidden">
                <div class="text-sm text-slate-500 dark:text-slate-400">
                    يتم عرض <span class="font-bold text-slate-900 dark:text-white">{{ $personalDataList->firstItem() ?? 0 }}</span> إلى <span class="font-bold text-slate-900 dark:text-white">{{ $personalDataList->lastItem() ?? 0 }}</span> من إجمالي <span class="font-bold text-slate-900 dark:text-white">{{ number_format($personalDataList->total()) }}</span> قيد
                </div>
                <div class="pagination-wrapper">
                    {{ $personalDataList->withQueryString()->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection