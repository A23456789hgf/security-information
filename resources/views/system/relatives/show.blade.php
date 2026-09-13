@extends('layouts.app')

@section('title', 'عرض بيانات القريب')
@section('page_title', 'السجلات الأساسية')

@section('content')
<div class="space-y-6" dir="rtl">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white flex items-center gap-3">
                <div class="p-2.5 bg-brand-500/10 text-brand-600 dark:text-brand-400 rounded-xl">
                    <i class="fas fa-user-friends text-xl"></i>
                </div>
                بيانات القريب: {{ $relative->full_name }}
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                عرض تفاصيل القريب، الانتماء، وصلة القرابة بالفرد الأساسي
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('system.relatives.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-sm font-semibold rounded-xl transition-all shadow-sm">
                <i class="fas fa-arrow-right"></i>
                عودة للقائمة
            </a>
            <a href="{{ route('system.relatives.edit', $relative->id) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow transition-all">
                <i class="fas fa-edit"></i>
                تعديل
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Right Summary bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden mb-4 --}}
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm text-center">
                <div class="w-20 h-20 mx-auto rounded-2xl bg-brand-500/10 border border-brand-500/20 text-brand-600 dark:text-brand-400 flex items-center justify-center font-bold text-2xl uppercase mb-4">
                    <i class="fas fa-user"></i>
                </div>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-1">{{ $relative->full_name }}</h2>
                
                <div class="mb-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-brand-500/10 text-brand-600 dark:text-brand-400 border border-brand-500/20">
                        {{ $relative->kinship->name ?? 'صلة القرابة غير محددة' }}
                    </span>
                </div>

                <div class="border-t border-slate-100 dark:border-slate-800 pt-4 space-y-3 text-right text-xs">
                    <div>
                        <span class="text-slate-400 block mb-0.5">الفرد الأساسي المرتبط</span>
                        @if($relative->personalData)
                            <a href="{{ route('system.personal-data.show', $relative->personalData->id) }}" class="font-bold text-brand-600 dark:text-brand-400 hover:underline">
                                {{ $relative->personalData->name }}
                            </a>
                        @else
                            <span class="text-slate-500">-</span>
                        @endif
                    </div>
                    <div>
                        <span class="text-slate-400 block mb-0.5">العمر</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200 dark:text-slate-200">{{ $relative->age ? $relative->age . ' سنة' : '-' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block mb-0.5">العمل الحالي</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200 dark:text-slate-200">{{ $relative->current_job ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Left Details bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden mb-4 --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
                <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                    <span class="w-2 h-5 bg-brand-600 rounded-full"></span>
                    معلومات الانتماء والملاحظات
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="bg-slate-50 dark:bg-slate-950/50 p-4 rounded-xl border border-slate-200 dark:border-slate-800">
                        <span class="text-slate-400 block mb-1">الانتماء العقائدي</span>
                        @if($relative->ideologicalAffiliation)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-2xl text-xs font-semibold bg-purple-500/10 text-purple-600 dark:text-purple-400">
                                {{ $relative->ideologicalAffiliation->name }}
                            </span>
                        @else
                            <span class="text-slate-500">-</span>
                        @endif
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-950/50 p-4 rounded-xl border border-slate-200 dark:border-slate-800">
                        <span class="text-slate-400 block mb-1">الانتماء السياسي</span>
                        @if($relative->politicalAffiliation)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-2xl text-xs font-semibold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                                {{ $relative->politicalAffiliation->name }}
                            </span>
                        @else
                            <span class="text-slate-500">-</span>
                        @endif
                    </div>
                    @if($relative->notes)
                        <div class="sm:col-span-2 bg-slate-50 dark:bg-slate-950/50 p-4 rounded-xl border border-slate-200 dark:border-slate-800">
                            <span class="text-slate-400 block mb-1">ملاحظات إضافية</span>
                            <p class="text-slate-800 dark:text-slate-200 dark:text-slate-200 leading-relaxed">{{ $relative->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Other relatives in enemy area --}}
            @if($relative->personalData && $relative->personalData->enemyAreaRelatives && $relative->personalData->enemyAreaRelatives->count() > 0)
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-rose-50/30 dark:bg-rose-950/20">
                    <h4 class="text-sm font-bold text-rose-700 dark:text-rose-400 flex items-center gap-2">
                        <span class="w-2 h-4 bg-rose-500 rounded-full"></span>
                        أقارب الفرد في مناطق العدو ({{ $relative->personalData->enemyAreaRelatives->count() }})
                    </h4>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-right border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 font-semibold">
                                <th class="px-4 py-3">الاسم</th>
                                <th class="px-4 py-3">صلة القرابة</th>
                                <th class="px-4 py-3">مكان السكن</th>
                                <th class="px-4 py-3">العمل</th>
                                <th class="px-4 py-3">السياسي / العقائدي</th>
                                <th class="px-4 py-3">طريقة التواصل</th>
                                <th class="px-4 py-3">الحالة المادية</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @foreach($relative->personalData->enemyAreaRelatives as $er)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/25">
                                <td class="px-4 py-3 font-bold text-slate-900 dark:text-white">{{ $er->full_name }}</td>
                                <td class="px-4 py-3">{{ $er->kinship->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $er->residence_place ?? '-' }}</td>
                                <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $er->job ?? '-' }}</td>
                                <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ ($er->politicalAffiliation->name ?? '-') . ' / ' . ($er->ideologicalAffiliation->name ?? '-') }}</td>
                                <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $er->communication_method ?? '-' }}</td>
                                <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $er->financialStatus->name ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            {{-- Other traveling relatives --}}
            @if($relative->personalData && $relative->personalData->travelingRelatives && $relative->personalData->travelingRelatives->count() > 0)
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-sky-50/30 dark:bg-sky-950/20">
                    <h4 class="text-sm font-bold text-sky-700 dark:text-sky-400 flex items-center gap-2">
                        <span class="w-2 h-4 bg-sky-500 rounded-full"></span>
                        أقارب الفرد المسافرين خارج البلد ({{ $relative->personalData->travelingRelatives->count() }})
                    </h4>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-right border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 font-semibold">
                                <th class="px-4 py-3">الاسم</th>
                                <th class="px-4 py-3">العمر</th>
                                <th class="px-4 py-3">صلة القرابة</th>
                                <th class="px-4 py-3">الدولة</th>
                                <th class="px-4 py-3">سبب السفر</th>
                                <th class="px-4 py-3">العمل ومكانه</th>
                                <th class="px-4 py-3">الجهة التابع لها</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @foreach($relative->personalData->travelingRelatives as $tr)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/25">
                                <td class="px-4 py-3 font-bold text-slate-900 dark:text-white">{{ $tr->full_name }}</td>
                                <td class="px-4 py-3">{{ $tr->age ? $tr->age . ' سنة' : '-' }}</td>
                                <td class="px-4 py-3">{{ $tr->kinship->name ?? '-' }}</td>
                                <td class="px-4 py-3 font-semibold text-sky-600 dark:text-sky-400">{{ $tr->country->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $tr->travel_reason ?? '-' }}</td>
                                <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $tr->current_job ?? '-' }} {{ $tr->workplace ? '('.$tr->workplace.')' : '' }}</td>
                                <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $tr->affiliated_entity ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

