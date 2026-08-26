@extends('layouts.app')

@section('title', 'عرض بيانات القريب')
@section('page_title', 'النظام')

@section('content')
<div class="container-fluid mx-auto px-4 py-8" dir="rtl">
    {{-- رأس الصفحة --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8 max-w-6xl mx-auto">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-blue-600 rounded-3xl shadow-xl shadow-blue-100 flex items-center justify-center text-white">
                <i class="fas fa-user-circle text-2xl"></i>
            </div>
            <div>
                <h2 class="text-3xl font-extrabold text-slate-800 header-font mb-1">بيانات القريب</h2>
                <p class="text-slate-500 text-sm">عرض تفاصيل القريب: <span class="text-blue-600 font-bold">{{ $relative->full_name }}</span></p>
            </div>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('system.relatives.edit', $relative->id) }}"
                class="px-6 py-3 bg-amber-500 text-white rounded-2xl font-bold hover:bg-amber-600 transition-all shadow-xl shadow-amber-100 flex items-center gap-2 text-sm">
                <i class="fas fa-edit"></i> تعديل
            </a>
            <a href="{{ route('system.relatives.index') }}"
                class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl font-bold hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2 text-sm">
                <i class="fas fa-arrow-right"></i> عودة للقائمة
            </a>
        </div>
    </div>

    <div class="max-w-6xl mx-auto space-y-8">
        {{-- البيانات الأساسية --}}
        <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50">
                <h5 class="text-lg font-bold text-slate-800 header-font flex items-center gap-3">
                    <span class="w-2 h-6 bg-blue-600 rounded-full"></span>
                    البيانات الأساسية للقريب
                </h5>
            </div>
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 flex flex-col gap-1">
                        <span class="text-xs font-bold text-slate-400">الفرد الأساسي المرتبط</span>
                        <span class="font-bold text-slate-700">
                            @if($relative->personalData)
                                <a href="{{ route('system.personal-data.show', $relative->personalData->id) }}" class="text-blue-600 hover:text-blue-800 font-bold hover:underline">
                                    {{ $relative->personalData->name }}
                                </a>
                            @else
                                -
                            @endif
                        </span>
                    </div>
                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 flex flex-col gap-1">
                        <span class="text-xs font-bold text-slate-400">اسم القريب</span>
                        <span class="font-bold text-slate-800 text-base">{{ $relative->full_name }}</span>
                    </div>
                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 flex flex-col gap-1">
                        <span class="text-xs font-bold text-slate-400">صلة القرابة</span>
                        <div>
                            <span class="px-3 py-1 rounded-lg text-xs font-bold border inline-block mt-1 bg-blue-50 text-blue-700 border-blue-100">
                                {{ $relative->kinship->name ?? '-' }}
                            </span>
                        </div>
                    </div>
                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 flex flex-col gap-1">
                        <span class="text-xs font-bold text-slate-400">العمر</span>
                        <span class="font-bold text-slate-700">{{ $relative->age ? $relative->age . ' سنة' : '-' }}</span>
                    </div>
                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 flex flex-col gap-1">
                        <span class="text-xs font-bold text-slate-400">العمل الحالي</span>
                        <span class="font-bold text-slate-700">{{ $relative->current_job ?? '-' }}</span>
                    </div>
                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 flex flex-col gap-1">
                        <span class="text-xs font-bold text-slate-400">الانتماء العقائدي</span>
                        <div>
                            <span class="px-3 py-1 rounded-lg text-xs font-bold border inline-block mt-1 bg-purple-50 text-purple-700 border-purple-100">
                                {{ $relative->ideologicalAffiliation->name ?? '-' }}
                            </span>
                        </div>
                    </div>
                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 flex flex-col gap-1">
                        <span class="text-xs font-bold text-slate-400">الانتماء السياسي</span>
                        <div>
                            <span class="px-3 py-1 rounded-lg text-xs font-bold border inline-block mt-1 bg-emerald-50 text-emerald-700 border-emerald-100">
                                {{ $relative->politicalAffiliation->name ?? '-' }}
                            </span>
                        </div>
                    </div>
                    @if($relative->notes)
                        <div class="md:col-span-2 bg-slate-50 rounded-2xl p-4 border border-slate-100 flex flex-col gap-1">
                            <span class="text-xs font-bold text-slate-400">ملاحظات</span>
                            <span class="font-medium text-slate-700">{{ $relative->notes }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- الأقارب الآخرين لنفس الفرد في مناطق العدو --}}
        @if($relative->personalData && $relative->personalData->enemyAreaRelatives && $relative->personalData->enemyAreaRelatives->count() > 0)
        <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-50 bg-rose-50/40 flex justify-between items-center">
                <h5 class="text-base font-bold text-slate-800 header-font flex items-center gap-3">
                    <span class="w-2 h-5 bg-rose-500 rounded-full"></span>
                    أقارب الفرد في مناطق العدو ({{ $relative->personalData->enemyAreaRelatives->count() }})
                </h5>
            </div>
            <div class="p-0">
                <div class="overflow-x-auto">
                    <table class="w-full text-right text-xs">
                        <thead class="bg-slate-50/70 border-b border-slate-100 text-[11px] font-bold text-slate-500">
                            <tr>
                                <th class="px-4 py-3">الاسم</th>
                                <th class="px-4 py-3">صلة القرابة</th>
                                <th class="px-4 py-3">مكان السكن</th>
                                <th class="px-4 py-3">العمل</th>
                                <th class="px-4 py-3">السياسي / العقائدي</th>
                                <th class="px-4 py-3">طريقة التواصل</th>
                                <th class="px-4 py-3">الحالة المادية</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($relative->personalData->enemyAreaRelatives as $er)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-4 py-3 font-bold text-slate-800">{{ $er->full_name }}</td>
                                <td class="px-4 py-3">{{ $er->kinship->name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $er->residence_place ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $er->job ?? '-' }}</td>
                                <td class="px-4 py-3">{{ ($er->politicalAffiliation->name ?? '-') . ' / ' . ($er->ideologicalAffiliation->name ?? '-') }}</td>
                                <td class="px-4 py-3">{{ $er->communication_method ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $er->financialStatus->name ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        {{-- الأقارب الآخرين لنفس الفرد المسافرين خارج البلد --}}
        @if($relative->personalData && $relative->personalData->travelingRelatives && $relative->personalData->travelingRelatives->count() > 0)
        <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-50 bg-sky-50/40 flex justify-between items-center">
                <h5 class="text-base font-bold text-slate-800 header-font flex items-center gap-3">
                    <span class="w-2 h-5 bg-sky-500 rounded-full"></span>
                    أقارب الفرد المسافرين خارج البلد ({{ $relative->personalData->travelingRelatives->count() }})
                </h5>
            </div>
            <div class="p-0">
                <div class="overflow-x-auto">
                    <table class="w-full text-right text-xs">
                        <thead class="bg-slate-50/70 border-b border-slate-100 text-[11px] font-bold text-slate-500">
                            <tr>
                                <th class="px-4 py-3">الاسم</th>
                                <th class="px-4 py-3">العمر</th>
                                <th class="px-4 py-3">صلة القرابة</th>
                                <th class="px-4 py-3">الدولة</th>
                                <th class="px-4 py-3">سبب السفر</th>
                                <th class="px-4 py-3">العمل الحالي ومكانه</th>
                                <th class="px-4 py-3">الجهة التابع لها</th>
                                <th class="px-4 py-3">انطباعه عن المسيرة</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($relative->personalData->travelingRelatives as $tr)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-4 py-3 font-bold text-slate-800">{{ $tr->full_name }}</td>
                                <td class="px-4 py-3">{{ $tr->age ? $tr->age . ' سنة' : '-' }}</td>
                                <td class="px-4 py-3">{{ $tr->kinship->name ?? '-' }}</td>
                                <td class="px-4 py-3 font-bold text-sky-700">{{ $tr->country->name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $tr->travel_reason ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $tr->current_job ?? '-' }} {{ $tr->workplace ? '('.$tr->workplace.')' : '' }}</td>
                                <td class="px-4 py-3">{{ $tr->affiliated_entity ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $tr->march_impression ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
