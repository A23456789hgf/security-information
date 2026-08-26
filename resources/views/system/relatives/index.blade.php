@extends('layouts.app')

@section('title', 'إدارة الأهل والأقارب')
@section('page_title', 'النظام')

@section('content')
<div class="container-fluid py-6" dir="rtl">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8 max-w-7xl mx-auto">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-blue-600 rounded-2xl shadow-lg flex items-center justify-center text-white">
                <i class="fas fa-users text-2xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-extrabold text-slate-800 header-font mb-1">إدارة الأهل والأقارب</h2>
                <p class="text-slate-500 text-xs flex items-center gap-2">
                    إدارة ومتابعة سجلات الأهل والأقارب، المتواجدين بمناطق العدو، والمسافرين للخارج
                    <span class="inline-flex items-center justify-center bg-blue-100 text-blue-700 px-2.5 py-0.5 rounded-full text-xs font-bold">
                        {{ $relativesCount + $enemyCount + $travelingCount }} سجل كلي
                    </span>
                </p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('system.relatives.export', request()->all()) }}"
                class="flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-xl font-bold text-xs transition-all shadow-sm">
                <i class="fas fa-file-excel"></i>
                <span>تصدير Excel ({{ $tab === 'enemy' ? 'مناطق العدو' : ($tab === 'traveling' ? 'المسافرين' : 'الأساسيين') }})</span>
            </a>
            <a href="{{ route('system.relatives.create') }}"
                class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold text-xs transition-all shadow-md">
                <i class="fas fa-plus"></i>
                <span>إضافة أقارب جدد</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-4 mb-6 max-w-7xl mx-auto flex items-center gap-3 text-emerald-800 text-xs font-bold">
            <i class="fas fa-check-circle text-emerald-600 text-base"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Tabs Navigation --}}
    <div class="max-w-7xl mx-auto mb-6 flex flex-wrap gap-2 border-b border-slate-200 pb-2">
        <a href="{{ route('system.relatives.index', ['tab' => 'relatives']) }}"
            class="px-5 py-2.5 rounded-2xl font-bold text-xs transition-all flex items-center gap-2 {{ $tab === 'relatives' ? 'bg-blue-600 text-white shadow-md shadow-blue-100' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
            <i class="fas fa-user-friends text-sm"></i>
            <span>الأهل والأقارب (العام)</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $tab === 'relatives' ? 'bg-blue-700 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $relativesCount }}</span>
        </a>

        <a href="{{ route('system.relatives.index', ['tab' => 'enemy']) }}"
            class="px-5 py-2.5 rounded-2xl font-bold text-xs transition-all flex items-center gap-2 {{ $tab === 'enemy' ? 'bg-rose-600 text-white shadow-md shadow-rose-100' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
            <i class="fas fa-map-marked-alt text-sm"></i>
            <span>الأقارب في مناطق العدو</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $tab === 'enemy' ? 'bg-rose-700 text-white' : 'bg-rose-50 text-rose-600 font-bold' }}">{{ $enemyCount }}</span>
        </a>

        <a href="{{ route('system.relatives.index', ['tab' => 'traveling']) }}"
            class="px-5 py-2.5 rounded-2xl font-bold text-xs transition-all flex items-center gap-2 {{ $tab === 'traveling' ? 'bg-sky-600 text-white shadow-md shadow-sky-100' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
            <i class="fas fa-plane-departure text-sm"></i>
            <span>الأقارب المسافرين خارج البلد</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $tab === 'traveling' ? 'bg-sky-700 text-white' : 'bg-sky-50 text-sky-600 font-bold' }}">{{ $travelingCount }}</span>
        </a>
    </div>

    <!-- بطاقة البحث -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-6 max-w-7xl mx-auto">
        <form method="GET" action="{{ route('system.relatives.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <div class="md:col-span-8">
                <div class="relative group">
                    <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fas fa-search text-xs"></i>
                    </div>
                    <input type="text" name="search" class="w-full pr-9 pl-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-1 focus:ring-blue-500 focus:bg-white transition-all font-medium" 
                           placeholder="ابحث بالاسم، العمل، أو الفرد الأساسي..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="md:col-span-2">
                <select name="per_page" class="w-full py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-1 focus:ring-blue-500 focus:bg-white transition-all font-medium">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 سجلات</option>
                    <option value="25" {{ request('per_page') == 25 || !request('per_page') ? 'selected' : '' }}>25 سجل</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 سجل</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 سجل</option>
                </select>
            </div>
            <div class="md:col-span-2 flex gap-2">
                <button type="submit" class="flex-1 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl font-bold text-xs transition-all shadow-sm flex items-center justify-center gap-1.5">
                    <i class="fas fa-filter text-[10px]"></i> بحث
                </button>
                @if(request()->filled('search'))
                    <a href="{{ route('system.relatives.index', ['tab' => $tab]) }}" class="px-3 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-bold text-xs transition-all flex items-center justify-center">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Content Table --}}
    <div class="max-w-7xl mx-auto">
        {{-- TAB 1: Standard Relatives --}}
        @if($tab === 'relatives')
            @if($relatives->isEmpty())
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 py-16 text-center">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                        <i class="fas fa-user-friends text-3xl"></i>
                    </div>
                    <h4 class="text-slate-600 header-font text-lg font-bold mb-1">لا توجد سجلات أقارب</h4>
                    <p class="text-slate-400 text-xs mb-5">لم يتم العثور على أي سجلات أقارب عامة.</p>
                    <a href="{{ route('system.relatives.create') }}" class="inline-flex items-center gap-2 bg-blue-600 text-white px-5 py-2.5 rounded-xl font-bold text-xs hover:bg-blue-700 transition-all shadow-sm">
                        <i class="fas fa-plus text-xs"></i> إضافة جديد
                    </a>
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-right text-xs">
                            <thead class="bg-slate-50/80 border-b border-slate-100 text-[11px] font-bold text-slate-500">
                                <tr>
                                    <th class="px-4 py-3.5 w-16">#</th>
                                    <th class="px-4 py-3.5 min-w-[170px]">القريب</th>
                                    <th class="px-4 py-3.5 min-w-[170px]">الفرد الأساسي</th>
                                    <th class="px-4 py-3.5">صلة القرابة</th>
                                    <th class="px-4 py-3.5 text-center">العمر</th>
                                    <th class="px-4 py-3.5">العمل الحالي</th>
                                    <th class="px-4 py-3.5">الانتماء العقائدي</th>
                                    <th class="px-4 py-3.5">الانتماء السياسي</th>
                                    <th class="px-4 py-3.5 text-center w-28">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($relatives as $relative)
                                <tr class="group hover:bg-slate-50/50 transition-colors">
                                    <td class="px-4 py-3.5 text-slate-400 font-mono text-[11px]">{{ $relative->id }}</td>
                                    <td class="px-4 py-3.5 font-bold text-slate-800">{{ $relative->full_name }}</td>
                                    <td class="px-4 py-3.5">
                                        @if($relative->personalData)
                                            <a href="{{ route('system.personal-data.show', $relative->personalData->id) }}" class="text-blue-600 hover:text-blue-800 font-bold hover:underline">
                                                {{ $relative->personalData->name }}
                                            </a>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                            {{ $relative->kinship->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5 text-center">
                                        {{ $relative->age ? $relative->age . ' سنة' : '-' }}
                                    </td>
                                    <td class="px-4 py-3.5 text-slate-700">{{ $relative->current_job ?? '-' }}</td>
                                    <td class="px-4 py-3.5">
                                        @if($relative->ideologicalAffiliation)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold bg-purple-50 text-purple-700 border border-purple-100">
                                                {{ $relative->ideologicalAffiliation->name }}
                                            </span>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5">
                                        @if($relative->politicalAffiliation)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                {{ $relative->politicalAffiliation->name }}
                                            </span>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <a href="{{ route('system.relatives.show', $relative->id) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all" title="عرض">
                                                <i class="fas fa-eye text-xs"></i>
                                            </a>
                                            <a href="{{ route('system.relatives.edit', $relative->id) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white transition-all" title="تعديل">
                                                <i class="fas fa-edit text-xs"></i>
                                            </a>
                                            <form action="{{ route('system.relatives.destroy', $relative->id) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition-all" title="حذف">
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
                <div class="mt-6 flex justify-center">
                    {{ $relatives->withQueryString()->links() }}
                </div>
            @endif

        {{-- TAB 2: Enemy Area Relatives --}}
        @elseif($tab === 'enemy')
            @if($enemyRelatives->isEmpty())
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 py-16 text-center">
                    <div class="w-20 h-20 bg-rose-50 rounded-full flex items-center justify-center mx-auto mb-4 text-rose-300">
                        <i class="fas fa-map-marked-alt text-3xl"></i>
                    </div>
                    <h4 class="text-slate-600 header-font text-lg font-bold mb-1">لا توجد سجلات في مناطق العدو</h4>
                    <p class="text-slate-400 text-xs mb-5">لم يتم تسجيل أقارب متواجدين في مناطق العدو بعد.</p>
                    <a href="{{ route('system.relatives.create') }}" class="inline-flex items-center gap-2 bg-rose-600 text-white px-5 py-2.5 rounded-xl font-bold text-xs hover:bg-rose-700 transition-all shadow-sm">
                        <i class="fas fa-plus text-xs"></i> إضافة جديد
                    </a>
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-right text-xs">
                            <thead class="bg-rose-50/50 border-b border-rose-100 text-[11px] font-bold text-rose-800">
                                <tr>
                                    <th class="px-3 py-3.5 w-14">#</th>
                                    <th class="px-3 py-3.5 min-w-[150px]">الاسم</th>
                                    <th class="px-3 py-3.5 min-w-[150px]">الفرد الأساسي</th>
                                    <th class="px-3 py-3.5">صلة القرابة</th>
                                    <th class="px-3 py-3.5">مكان السكن</th>
                                    <th class="px-3 py-3.5">العمل</th>
                                    <th class="px-3 py-3.5">الانتماء السياسي</th>
                                    <th class="px-3 py-3.5">الانتماء العقائدي</th>
                                    <th class="px-3 py-3.5">طريقة التواصل</th>
                                    <th class="px-3 py-3.5">العلاقة</th>
                                    <th class="px-3 py-3.5">الحالة المادية</th>
                                    <th class="px-3 py-3.5 text-center w-20">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($enemyRelatives as $er)
                                <tr class="group hover:bg-rose-50/30 transition-colors">
                                    <td class="px-3 py-3.5 text-slate-400 font-mono text-[11px]">{{ $er->id }}</td>
                                    <td class="px-3 py-3.5 font-bold text-slate-800">{{ $er->full_name }}</td>
                                    <td class="px-3 py-3.5">
                                        @if($er->personalData)
                                            <a href="{{ route('system.personal-data.show', $er->personalData->id) }}" class="text-blue-600 hover:text-blue-800 font-bold hover:underline">
                                                {{ $er->personalData->name }}
                                            </a>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3.5">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[11px] font-bold bg-slate-100 text-slate-700">
                                            {{ $er->kinship->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-3.5 text-slate-600">{{ $er->residence_place ?? '-' }}</td>
                                    <td class="px-3 py-3.5 text-slate-600">{{ $er->job ?? '-' }}</td>
                                    <td class="px-3 py-3.5">
                                        @if($er->politicalAffiliation)
                                            <span class="bg-emerald-50 text-emerald-700 font-bold px-2 py-0.5 rounded text-[11px]">
                                                {{ $er->politicalAffiliation->name }}
                                            </span>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3.5">
                                        @if($er->ideologicalAffiliation)
                                            <span class="bg-purple-50 text-purple-700 font-bold px-2 py-0.5 rounded text-[11px]">
                                                {{ $er->ideologicalAffiliation->name }}
                                            </span>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3.5 text-slate-600">{{ $er->communication_method ?? '-' }}</td>
                                    <td class="px-3 py-3.5 text-slate-600">{{ $er->relationship ?? '-' }}</td>
                                    <td class="px-3 py-3.5">
                                        @if($er->financialStatus)
                                            <span class="bg-amber-50 text-amber-700 font-bold px-2 py-0.5 rounded text-[11px]">
                                                {{ $er->financialStatus->name }}
                                            </span>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3.5 text-center">
                                        <form action="{{ route('system.relatives.destroy-enemy', $er->id) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا السجل؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition-all mx-auto" title="حذف">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mt-6 flex justify-center">
                    {{ $enemyRelatives->withQueryString()->links() }}
                </div>
            @endif

        {{-- TAB 3: Traveling Relatives --}}
        @elseif($tab === 'traveling')
            @if($travelingRelatives->isEmpty())
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 py-16 text-center">
                    <div class="w-20 h-20 bg-sky-50 rounded-full flex items-center justify-center mx-auto mb-4 text-sky-300">
                        <i class="fas fa-plane-departure text-3xl"></i>
                    </div>
                    <h4 class="text-slate-600 header-font text-lg font-bold mb-1">لا توجد سجلات للمسافرين</h4>
                    <p class="text-slate-400 text-xs mb-5">لم يتم تسجيل أقارب مسافرين خارج البلد بعد.</p>
                    <a href="{{ route('system.relatives.create') }}" class="inline-flex items-center gap-2 bg-sky-600 text-white px-5 py-2.5 rounded-xl font-bold text-xs hover:bg-sky-700 transition-all shadow-sm">
                        <i class="fas fa-plus text-xs"></i> إضافة جديد
                    </a>
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-right text-xs">
                            <thead class="bg-sky-50/50 border-b border-sky-100 text-[11px] font-bold text-sky-800">
                                <tr>
                                    <th class="px-3 py-3.5 w-14">#</th>
                                    <th class="px-3 py-3.5 min-w-[140px]">الاسم</th>
                                    <th class="px-3 py-3.5 min-w-[140px]">الفرد الأساسي</th>
                                    <th class="px-3 py-3.5 text-center">العمر</th>
                                    <th class="px-3 py-3.5">صلة القرابة</th>
                                    <th class="px-3 py-3.5">الدولة</th>
                                    <th class="px-3 py-3.5">سبب السفر</th>
                                    <th class="px-3 py-3.5">العمل ومكانه</th>
                                    <th class="px-3 py-3.5">الجهة التابع لها</th>
                                    <th class="px-3 py-3.5">السياسي / العقائدي</th>
                                    <th class="px-3 py-3.5 min-w-[140px]">انطباعه عن المسيرة</th>
                                    <th class="px-3 py-3.5 text-center w-20">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($travelingRelatives as $tr)
                                <tr class="group hover:bg-sky-50/30 transition-colors">
                                    <td class="px-3 py-3.5 text-slate-400 font-mono text-[11px]">{{ $tr->id }}</td>
                                    <td class="px-3 py-3.5 font-bold text-slate-800">{{ $tr->full_name }}</td>
                                    <td class="px-3 py-3.5">
                                        @if($tr->personalData)
                                            <a href="{{ route('system.personal-data.show', $tr->personalData->id) }}" class="text-blue-600 hover:text-blue-800 font-bold hover:underline">
                                                {{ $tr->personalData->name }}
                                            </a>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3.5 text-center">{{ $tr->age ? $tr->age . ' سنة' : '-' }}</td>
                                    <td class="px-3 py-3.5">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[11px] font-bold bg-slate-100 text-slate-700">
                                            {{ $tr->kinship->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-3.5">
                                        @if($tr->country)
                                            <span class="inline-flex items-center gap-1 font-bold text-sky-700 bg-sky-50 px-2 py-0.5 rounded-lg border border-sky-100">
                                                <i class="fas fa-globe text-[10px]"></i> {{ $tr->country->name }}
                                            </span>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3.5 text-slate-600">{{ $tr->travel_reason ?? '-' }}</td>
                                    <td class="px-3 py-3.5 text-slate-600">
                                        <div>{{ $tr->current_job ?? '-' }}</div>
                                        @if($tr->workplace)
                                            <div class="text-[10px] text-slate-400">{{ $tr->workplace }}</div>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3.5 text-slate-600">{{ $tr->affiliated_entity ?? '-' }}</td>
                                    <td class="px-3 py-3.5">
                                        <div class="flex flex-col gap-1">
                                            @if($tr->politicalAffiliation)
                                                <span class="bg-emerald-50 text-emerald-700 font-bold px-1.5 py-0.2 rounded text-[10px]">
                                                    {{ $tr->politicalAffiliation->name }}
                                                </span>
                                            @endif
                                            @if($tr->ideologicalAffiliation)
                                                <span class="bg-purple-50 text-purple-700 font-bold px-1.5 py-0.2 rounded text-[10px]">
                                                    {{ $tr->ideologicalAffiliation->name }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-3 py-3.5 text-slate-600">{{ $tr->march_impression ?? '-' }}</td>
                                    <td class="px-3 py-3.5 text-center">
                                        <form action="{{ route('system.relatives.destroy-traveling', $tr->id) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا السجل؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition-all mx-auto" title="حذف">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mt-6 flex justify-center">
                    {{ $travelingRelatives->withQueryString()->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
