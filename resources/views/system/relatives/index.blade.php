@extends('layouts.app')

@section('title', 'إدارة الأهل والأقارب')
@section('page_title', 'السجلات الأساسية')

@section('content')
<div class="space-y-6" dir="rtl">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white flex items-center gap-3">
                <div class="p-2.5 bg-brand-500/10 text-brand-600 dark:text-brand-400 rounded-xl">
                    <i class="fas fa-users text-xl"></i>
                </div>
                إدارة الأهل والأقارب
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                إدارة ومتابعة سجلات الأهل والأقارب، المتواجدين بمناطق العدو، والمسافرين للخارج
                (إجمالي: <span class="font-bold text-slate-700 dark:text-slate-200">{{ $relativesCount + $enemyCount + $travelingCount }}</span> سجل)
            </p>
        </div>
        <div class="flex items-center gap-3 flex-wrap">
            <x-print-button :url="route('system.relatives.print-all', request()->all())" text="طباعة الكشف الحالي" />
            <a href="{{ route('system.relatives.export', request()->all()) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow transition-all">
                <i class="fas fa-file-excel"></i>
                تصدير Excel ({{ $tab === 'enemy' ? 'مناطق العدو' : ($tab === 'traveling' ? 'المسافرين' : 'الأساسيين') }})
            </a>
            <a href="{{ route('system.relatives.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow transition-all">
                <i class="fas fa-plus"></i>
                إضافة أقارب جدد
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

    {{-- Tabs Navigation --}}
    <div class="flex flex-wrap items-center gap-2 border-b border-slate-200 dark:border-slate-800 pb-2">
        <a href="{{ route('system.relatives.index', ['tab' => 'relatives']) }}"
            class="px-4 py-2 rounded-xl font-semibold text-xs transition-all flex items-center gap-2 {{ $tab === 'relatives' ? 'bg-brand-600 text-white shadow-sm' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-800' }}">
            <i class="fas fa-user-friends"></i>
            <span>الأهل والأقارب (العام)</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $tab === 'relatives' ? 'bg-brand-700 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300' }}">{{ $relativesCount }}</span>
        </a>

        <a href="{{ route('system.relatives.index', ['tab' => 'enemy']) }}"
            class="px-4 py-2 rounded-xl font-semibold text-xs transition-all flex items-center gap-2 {{ $tab === 'enemy' ? 'bg-rose-600 text-white shadow-sm' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-800' }}">
            <i class="fas fa-map-marked-alt"></i>
            <span>الأقارب في مناطق العدو</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $tab === 'enemy' ? 'bg-rose-700 text-white' : 'bg-rose-500/10 text-rose-600 dark:text-rose-400 font-bold' }}">{{ $enemyCount }}</span>
        </a>

        <a href="{{ route('system.relatives.index', ['tab' => 'traveling']) }}"
            class="px-4 py-2 rounded-xl font-semibold text-xs transition-all flex items-center gap-2 {{ $tab === 'traveling' ? 'bg-sky-600 text-white shadow-sm' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-800' }}">
            <i class="fas fa-plane-departure"></i>
            <span>الأقارب المسافرين خارج البلد</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $tab === 'traveling' ? 'bg-sky-700 text-white' : 'bg-sky-500/10 text-sky-600 dark:text-sky-400 font-bold' }}">{{ $travelingCount }}</span>
        </a>
    </div>

    {{-- Filter Toolbar --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-4 shadow-sm">
        <form method="GET" action="{{ route('system.relatives.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <div class="md:col-span-4 relative">
                <i class="fas fa-search absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" name="search"
                    class="w-full pr-10 pl-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all font-medium" 
                    placeholder="ابحث بالاسم، العمل، أو الفرد..." value="{{ request('search') }}">
            </div>
            <div class="md:col-span-4">
                <select name="personal_data_id" onchange="this.form.submit()" class="w-full py-2.5 px-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all">
                    <option value="">-- فرز بالفرد الأساسي (الكل) --</option>
                    @foreach($personalDataList as $p)
                        <option value="{{ $p->id }}" {{ request('personal_data_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->name }} {{ $p->jihadi_name ? '('.$p->jihadi_name.')' : '' }} {{ $p->military_number ? '- م: '.$p->military_number : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <select name="per_page" class="w-full py-2.5 px-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 سجلات</option>
                    <option value="25" {{ request('per_page') == 25 || !request('per_page') ? 'selected' : '' }}>25 سجل</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 سجل</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 سجل</option>
                </select>
            </div>
            <div class="md:col-span-2 flex gap-2">
                <button type="submit" class="flex-1 py-2.5 bg-slate-900 dark:bg-slate-800 hover:bg-slate-800 dark:hover:bg-slate-700 text-white rounded-xl font-semibold text-xs transition-all shadow-sm flex items-center justify-center gap-1.5">
                    <i class="fas fa-filter text-[10px]"></i> بحث
                </button>
                @if(request()->anyFilled(['search', 'personal_data_id']))
                    <a href="{{ route('system.relatives.index', ['tab' => $tab]) }}" class="px-3 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl font-semibold text-xs transition-all flex items-center justify-center">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Content table --}}
    <div>
        {{-- TAB 1: Standard Relatives --}}
        @if($tab === 'relatives')
            @if($relatives->isEmpty())
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 py-16 text-center shadow-sm">
                    <div class="w-16 h-16 bg-slate-50 dark:bg-slate-800 rounded-2xl flex items-center justify-center mx-auto mb-3 text-slate-400">
                        <i class="fas fa-user-friends text-2xl"></i>
                    </div>
                    <h2 class="text-slate-700 dark:text-slate-300 text-base font-bold mb-1">لا توجد سجلات أقارب</h2>
                    <p class="text-slate-400 text-xs mb-4">لم يتم العثور على أي سجلات أقارب عامة.</p>
                    <a href="{{ route('system.relatives.create') }}" class="inline-flex items-center gap-2 bg-brand-600 text-white px-4 py-2 rounded-xl font-semibold text-xs hover:bg-brand-700 transition-all shadow-sm">
                        <i class="fas fa-plus text-xs"></i> إضافة جديد
                    </a>
                </div>
            @else
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-right border-collapse">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 font-bold text-xs uppercase tracking-wider">
                                    <th class="px-5 py-4 w-16">#</th>
                                    <th class="px-5 py-4 min-w-[170px]">القريب</th>
                                    <th class="px-5 py-4 min-w-[170px]">الفرد الأساسي</th>
                                    <th class="px-5 py-4">صلة القرابة</th>
                                    <th class="px-5 py-4 text-center">العمر</th>
                                    <th class="px-5 py-4">العمل الحالي</th>
                                    <th class="px-5 py-4">الانتماء العقائدي</th>
                                    <th class="px-5 py-4">الانتماء السياسي</th>
                                    <th class="px-5 py-4 text-left">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                                @foreach($relatives as $relative)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/25 transition-colors">
                                    <td class="px-5 py-4 text-slate-400 font-mono text-xs">#{{ $relative->id }}</td>
                                    <td class="px-5 py-4 font-bold text-slate-900 dark:text-white">{{ $relative->full_name }}</td>
                                    <td class="px-5 py-4">
                                        @if($relative->personalData)
                                            <div class="flex items-center gap-2.5">
                                                @if($relative->personalData->photo_url)
                                                    <img src="{{ $relative->personalData->photo_url }}" class="w-8 h-8 rounded-full object-cover border border-slate-200 dark:border-slate-700 shadow-sm shrink-0" alt="{{ $relative->personalData->name }}">
                                                @else
                                                    <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-400 text-xs shrink-0">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <a href="{{ route('system.personal-data.show', $relative->personalData->id) }}" class="text-brand-600 dark:text-brand-400 font-semibold hover:underline block leading-tight">
                                                        {{ $relative->personalData->name }}
                                                    </a>
                                                    @if($relative->personalData->jihadi_name)
                                                        <span class="text-[10px] text-slate-400 font-medium">({{ $relative->personalData->jihadi_name }})</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-2xl text-xs font-semibold bg-brand-500/10 text-brand-600 dark:text-brand-400 border border-brand-500/20">
                                            {{ $relative->kinship->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-center text-slate-600 dark:text-slate-300">
                                        {{ $relative->age ? $relative->age . ' سنة' : '-' }}
                                    </td>
                                    <td class="px-5 py-4 text-slate-700 dark:text-slate-300">{{ $relative->current_job ?? '-' }}</td>
                                    <td class="px-5 py-4">
                                        @if($relative->ideologicalAffiliation)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-2xl text-xs font-semibold bg-purple-500/10 text-purple-600 dark:text-purple-400 border border-purple-500/20">
                                                {{ $relative->ideologicalAffiliation->name }}
                                            </span>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4">
                                        @if($relative->politicalAffiliation)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-2xl text-xs font-semibold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                                {{ $relative->politicalAffiliation->name }}
                                            </span>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <a href="{{ route('system.relatives.show', $relative->id) }}" class="p-2 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-brand-50 dark:hover:bg-brand-950/50 transition-colors" title="عرض">
                                                <i class="fas fa-eye text-xs"></i>
                                            </a>
                                            <a href="{{ route('system.relatives.edit', $relative->id) }}" class="p-2 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-950/50 transition-colors" title="تعديل">
                                                <i class="fas fa-edit text-xs"></i>
                                            </a>
                                            <form action="{{ route('system.relatives.destroy', $relative->id) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/50 transition-colors" title="حذف">
                                                    <i class="fas fa-trash text-xs"></i>
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
                <div class="flex justify-center">
                    {{ $relatives->withQueryString()->links() }}
                </div>
            @endif

        {{-- TAB 2: Enemy Area Relatives --}}
        @elseif($tab === 'enemy')
            @if($enemyRelatives->isEmpty())
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 py-16 text-center shadow-sm">
                    <div class="w-16 h-16 bg-rose-50 dark:bg-rose-950/50 rounded-2xl flex items-center justify-center mx-auto mb-3 text-rose-400">
                        <i class="fas fa-map-marked-alt text-2xl"></i>
                    </div>
                    <h2 class="text-slate-700 dark:text-slate-300 text-base font-bold mb-1">لا توجد سجلات في مناطق العدو</h2>
                    <p class="text-slate-400 text-xs mb-4">لم يتم تسجيل أقارب متواجدين في مناطق العدو بعد.</p>
                    <a href="{{ route('system.relatives.create') }}" class="inline-flex items-center gap-2 bg-rose-600 text-white px-4 py-2 rounded-xl font-semibold text-xs hover:bg-rose-700 transition-all shadow-sm">
                        <i class="fas fa-plus text-xs"></i> إضافة جديد
                    </a>
                </div>
            @else
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-right border-collapse">
                            <thead>
                                <tr class="bg-rose-50/50 dark:bg-rose-950/20 border-b border-rose-100 dark:border-rose-900/40 text-rose-700 dark:text-rose-400 font-bold text-xs uppercase tracking-wider">
                                    <th class="px-4 py-4 w-14">#</th>
                                    <th class="px-4 py-4 min-w-[150px]">الاسم</th>
                                    <th class="px-4 py-4 min-w-[150px]">الفرد الأساسي</th>
                                    <th class="px-4 py-4">صلة القرابة</th>
                                    <th class="px-4 py-4">مكان السكن</th>
                                    <th class="px-4 py-4">العمل</th>
                                    <th class="px-4 py-4">الانتماء السياسي</th>
                                    <th class="px-4 py-4">الانتماء العقائدي</th>
                                    <th class="px-4 py-4">طريقة التواصل</th>
                                    <th class="px-4 py-4">العلاقة</th>
                                    <th class="px-4 py-4">الحالة المادية</th>
                                    <th class="px-4 py-4 text-left">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                                @foreach($enemyRelatives as $er)
                                <tr class="hover:bg-rose-50/20 dark:hover:bg-rose-950/10 transition-colors">
                                    <td class="px-4 py-4 text-slate-400 font-mono text-xs">#{{ $er->id }}</td>
                                    <td class="px-4 py-4 font-bold text-slate-900 dark:text-white">{{ $er->full_name }}</td>
                                    <td class="px-4 py-4">
                                        @if($er->personalData)
                                            <div class="flex items-center gap-2">
                                                @if($er->personalData->photo_url)
                                                    <img src="{{ $er->personalData->photo_url }}" class="w-8 h-8 rounded-full object-cover border border-slate-200 dark:border-slate-700 shadow-sm shrink-0" alt="{{ $er->personalData->name }}">
                                                @else
                                                    <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-400 text-xs shrink-0">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <a href="{{ route('system.personal-data.show', $er->personalData->id) }}" class="text-brand-600 dark:text-brand-400 font-semibold hover:underline block leading-tight">
                                                        {{ $er->personalData->name }}
                                                    </a>
                                                    @if($er->personalData->jihadi_name)
                                                        <span class="text-[10px] text-slate-400 font-medium">({{ $er->personalData->jihadi_name }})</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-2xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                            {{ $er->kinship->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-slate-600 dark:text-slate-300">{{ $er->residence_place ?? '-' }}</td>
                                    <td class="px-4 py-4 text-slate-600 dark:text-slate-300">{{ $er->job ?? '-' }}</td>
                                    <td class="px-4 py-4">
                                        @if($er->politicalAffiliation)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                                                {{ $er->politicalAffiliation->name }}
                                            </span>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">
                                        @if($er->ideologicalAffiliation)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold bg-purple-500/10 text-purple-600 dark:text-purple-400">
                                                {{ $er->ideologicalAffiliation->name }}
                                            </span>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-slate-600 dark:text-slate-300">{{ $er->communication_method ?? '-' }}</td>
                                    <td class="px-4 py-4 text-slate-600 dark:text-slate-300">{{ $er->relationship ?? '-' }}</td>
                                    <td class="px-4 py-4">
                                        @if($er->financialStatus)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold bg-amber-500/10 text-amber-600 dark:text-amber-400">
                                                {{ $er->financialStatus->name }}
                                            </span>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center justify-end">
                                            <form action="{{ route('system.relatives.destroy-enemy', $er->id) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا السجل؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/50 transition-colors" title="حذف">
                                                    <i class="fas fa-trash text-xs"></i>
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
                <div class="flex justify-center">
                    {{ $enemyRelatives->withQueryString()->links() }}
                </div>
            @endif

        {{-- TAB 3: Traveling Relatives --}}
        @elseif($tab === 'traveling')
            @if($travelingRelatives->isEmpty())
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 py-16 text-center shadow-sm">
                    <div class="w-16 h-16 bg-sky-50 dark:bg-sky-950/50 rounded-2xl flex items-center justify-center mx-auto mb-3 text-sky-400">
                        <i class="fas fa-plane-departure text-2xl"></i>
                    </div>
                    <h2 class="text-slate-700 dark:text-slate-300 text-base font-bold mb-1">لا توجد سجلات للمسافرين</h2>
                    <p class="text-slate-400 text-xs mb-4">لم يتم تسجيل أقارب مسافرين خارج البلد بعد.</p>
                    <a href="{{ route('system.relatives.create') }}" class="inline-flex items-center gap-2 bg-sky-600 text-white px-4 py-2 rounded-xl font-semibold text-xs hover:bg-sky-700 transition-all shadow-sm">
                        <i class="fas fa-plus text-xs"></i> إضافة جديد
                    </a>
                </div>
            @else
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-right border-collapse">
                            <thead>
                                <tr class="bg-sky-50/50 dark:bg-sky-950/20 border-b border-sky-100 dark:border-sky-900/40 text-sky-700 dark:text-sky-400 font-bold text-xs uppercase tracking-wider">
                                    <th class="px-4 py-4 w-14">#</th>
                                    <th class="px-4 py-4 min-w-[140px]">الاسم</th>
                                    <th class="px-4 py-4 min-w-[140px]">الفرد الأساسي</th>
                                    <th class="px-4 py-4 text-center">العمر</th>
                                    <th class="px-4 py-4">صلة القرابة</th>
                                    <th class="px-4 py-4">الدولة</th>
                                    <th class="px-4 py-4">سبب السفر</th>
                                    <th class="px-4 py-4">العمل ومكانه</th>
                                    <th class="px-4 py-4">الجهة التابع لها</th>
                                    <th class="px-4 py-4">السياسي / العقائدي</th>
                                    <th class="px-4 py-4 min-w-[140px]">انطباعه عن المسيرة</th>
                                    <th class="px-4 py-4 text-left">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                                @foreach($travelingRelatives as $tr)
                                <tr class="hover:bg-sky-50/20 dark:hover:bg-sky-950/10 transition-colors">
                                    <td class="px-4 py-4 text-slate-400 font-mono text-xs">#{{ $tr->id }}</td>
                                    <td class="px-4 py-4 font-bold text-slate-900 dark:text-white">{{ $tr->full_name }}</td>
                                    <td class="px-4 py-4">
                                        @if($tr->personalData)
                                            <div class="flex items-center gap-2">
                                                @if($tr->personalData->photo_url)
                                                    <img src="{{ $tr->personalData->photo_url }}" class="w-8 h-8 rounded-full object-cover border border-slate-200 dark:border-slate-700 shadow-sm shrink-0" alt="{{ $tr->personalData->name }}">
                                                @else
                                                    <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-400 text-xs shrink-0">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <a href="{{ route('system.personal-data.show', $tr->personalData->id) }}" class="text-brand-600 dark:text-brand-400 font-semibold hover:underline block leading-tight">
                                                        {{ $tr->personalData->name }}
                                                    </a>
                                                    @if($tr->personalData->jihadi_name)
                                                        <span class="text-[10px] text-slate-400 font-medium">({{ $tr->personalData->jihadi_name }})</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-center text-slate-600 dark:text-slate-300">{{ $tr->age ? $tr->age . ' سنة' : '-' }}</td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-2xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                            {{ $tr->kinship->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        @if($tr->country)
                                            <span class="inline-flex items-center gap-1 font-semibold text-sky-600 dark:text-sky-400 bg-sky-500/10 px-2 py-0.5 rounded-2xl border border-sky-500/20">
                                                <i class="fas fa-globe text-[10px]"></i> {{ $tr->country->name }}
                                            </span>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-slate-600 dark:text-slate-300">{{ $tr->travel_reason ?? '-' }}</td>
                                    <td class="px-4 py-4 text-slate-600 dark:text-slate-300">
                                        <div>{{ $tr->current_job ?? '-' }}</div>
                                        @if($tr->workplace)
                                            <div class="text-[10px] text-slate-400">{{ $tr->workplace }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-slate-600 dark:text-slate-300">{{ $tr->affiliated_entity ?? '-' }}</td>
                                    <td class="px-4 py-4">
                                        <div class="flex flex-col gap-1">
                                            @if($tr->politicalAffiliation)
                                                <span class="bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 font-semibold px-1.5 py-0.5 rounded text-[10px]">
                                                    {{ $tr->politicalAffiliation->name }}
                                                </span>
                                            @endif
                                            @if($tr->ideologicalAffiliation)
                                                <span class="bg-purple-500/10 text-purple-600 dark:text-purple-400 font-semibold px-1.5 py-0.5 rounded text-[10px]">
                                                    {{ $tr->ideologicalAffiliation->name }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-slate-600 dark:text-slate-300">{{ $tr->march_impression ?? '-' }}</td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center justify-end">
                                            <form action="{{ route('system.relatives.destroy-traveling', $tr->id) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا السجل؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/50 transition-colors" title="حذف">
                                                    <i class="fas fa-trash text-xs"></i>
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
                <div class="flex justify-center">
                    {{ $travelingRelatives->withQueryString()->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection

