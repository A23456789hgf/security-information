@extends('layouts.app')

@section('title', 'إضافة الأهل والأقارب')
@section('page_title', 'السجلات الأساسية')

@section('content')
<div class="max-w-7xl mx-auto space-y-6" dir="rtl">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white flex items-center gap-3">
                <div class="p-2.5 bg-brand-500/10 text-brand-600 dark:text-brand-400 rounded-xl">
                    <i class="fas fa-users-cog text-xl"></i>
                </div>
                إضافة الأهل والأقارب
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                تسجيل الأقارب داخل الوطن، في مناطق العدو، والمسافرين خارج البلد.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('system.relatives.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-sm font-semibold rounded-xl transition-all shadow-sm">
                <i class="fas fa-arrow-right"></i>
                عودة للقائمة
            </a>
            <button type="submit" form="relativesForm"
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-sm font-semibold shadow-sm hover:shadow transition-all">
                <i class="fas fa-save"></i>
                حفظ البيانات
            </button>
        </div>
    </div>

    @if ($errors->any())
        <div class="p-4 bg-rose-500/10 border border-rose-500/20 rounded-xl text-rose-600 dark:text-rose-400">
            <div class="flex items-start gap-3">
                <i class="fas fa-exclamation-triangle text-lg mt-0.5"></i>
                <div>
                    <span class="font-bold block mb-1">يرجى تصحيح الأخطاء التالية:</span>
                    <ul class="list-disc list-inside text-sm space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form id="relativesForm" action="{{ route('system.relatives.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- 1. Primary Person Selection --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
            <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                <span class="w-2 h-5 bg-brand-600 rounded-full"></span>
                الفرد الأساسي
            </h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">ابحث عن الفرد واختاره <span class="text-rose-500">*</span></label>
                    <select id="personal_data_id" name="personal_data_id" required
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all cursor-pointer @error('personal_data_id') border-rose-500 @enderror">
                        <option value="">-- اختر الفرد --</option>
                        @foreach($personalDataList as $id => $name)
                            <option value="{{ $id }}" {{ (old('personal_data_id') ?? $selectedPersonalDataId) == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Person Preview --}}
                <div id="person_details_container" class="hidden">
                    <div class="bg-slate-50 dark:bg-slate-950/50 border border-slate-200 dark:border-slate-800 rounded-xl p-5 space-y-4">
                        {{-- Photo & Main Details Header --}}
                        <div class="flex items-center gap-3.5 p-2.5 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800">
                            <div class="w-16 h-16 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shrink-0 flex items-center justify-center shadow-xs">
                                <img id="p_photo" src="" alt="الصورة الشخصية" class="w-full h-full object-cover hidden">
                                <i id="p_photo_placeholder" class="fas fa-user text-slate-400 dark:text-slate-500 text-2xl"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1 flex items-center gap-1.5">
                                    <i class="fas fa-info-circle text-brand-600 dark:text-brand-400"></i>
                                    تفاصيل الفرد المختار
                                </h3>
                                <div id="p_person_name" class="font-bold text-slate-800 dark:text-slate-200 text-sm truncate"></div>
                                <div id="p_person_sub" class="text-xs text-slate-400 dark:text-slate-500 truncate"></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 text-xs">
                            <div class="bg-white dark:bg-slate-900 p-3 rounded-2xl border border-slate-200 dark:border-slate-800">
                                <span class="text-slate-400 block mb-0.5">الاسم الجهادي:</span>
                                <span id="p_jihadi_name" class="font-bold text-slate-800 dark:text-slate-200">-</span>
                            </div>
                            <div class="bg-white dark:bg-slate-900 p-3 rounded-2xl border border-slate-200 dark:border-slate-800">
                                <span class="text-slate-400 block mb-0.5">الرقم العسكري:</span>
                                <span id="p_military_number" class="font-bold text-slate-800 dark:text-slate-200">-</span>
                            </div>
                            <div class="bg-white dark:bg-slate-900 p-3 rounded-2xl border border-slate-200 dark:border-slate-800">
                                <span class="text-slate-400 block mb-0.5">فصيلة الدم:</span>
                                <span id="p_blood_type" class="font-bold text-rose-600 dark:text-rose-400">-</span>
                            </div>
                            <div class="bg-white dark:bg-slate-900 p-3 rounded-2xl border border-slate-200 dark:border-slate-800">
                                <span class="text-slate-400 block mb-0.5">رقم الهوية:</span>
                                <span id="p_national_id" class="font-bold text-slate-800 dark:text-slate-200">-</span>
                            </div>
                            <div class="bg-white dark:bg-slate-900 p-3 rounded-2xl border border-slate-200 dark:border-slate-800 sm:col-span-2 md:col-span-4">
                                <span class="text-slate-400 block mb-0.5">العنوان:</span>
                                <span id="p_address" class="font-bold text-slate-800 dark:text-slate-200">-</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. Dynamic Relatives table --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="w-2 h-5 bg-emerald-500 rounded-full"></span>
                    الأهل والأقارب (بيانات عامة)
                </h2>
                <button type="button" id="addRelativeRowBtn"
                    class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-500/20 rounded-xl text-xs font-semibold transition-all">
                    <i class="fas fa-plus"></i> إضافة قريب
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-right border-collapse" id="relativesTable">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 font-bold text-xs uppercase tracking-wider">
                            <th class="px-3 py-3 min-w-[170px]">الاسم الكامل</th>
                            <th class="px-3 py-3 min-w-[70px] w-20">العمر</th>
                            <th class="px-3 py-3 min-w-[130px]">صلة القرابة</th>
                            <th class="px-3 py-3 min-w-[130px]">العمل الحالي</th>
                            <th class="px-3 py-3 min-w-[130px]">الانتماء العقائدي</th>
                            <th class="px-3 py-3 min-w-[130px]">الانتماء السياسي</th>
                            <th class="px-3 py-3 min-w-[150px]">ملاحظات</th>
                            <th class="px-3 py-3 w-10 text-center"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                        <tr class="relative-row">
                            <td class="p-2">
                                <input type="text" name="relatives[0][full_name]"
                                    class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500"
                                    placeholder="الاسم الكامل...">
                            </td>
                            <td class="p-2">
                                <input type="number" name="relatives[0][age]" min="0" max="150"
                                    class="w-full px-2 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-900 dark:text-white text-center focus:outline-none focus:border-brand-500"
                                    placeholder="العمر">
                            </td>
                            <td class="p-2">
                                <select name="relatives[0][kinship_id]"
                                    class="w-full px-2.5 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 cursor-pointer">
                                    <option value="">-- صلة القرابة --</option>
                                    @foreach($kinships as $kId => $kName)
                                        <option value="{{ $kId }}">{{ $kName }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="p-2">
                                <input type="text" name="relatives[0][current_job]"
                                    class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500"
                                    placeholder="العمل الحالي">
                            </td>
                            <td class="p-2">
                                <select name="relatives[0][ideological_affiliation_id]"
                                    class="w-full px-2.5 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 cursor-pointer">
                                    <option value="">-- العقائدي --</option>
                                    @foreach($ideologicalAffiliations as $iaId => $iaName)
                                        <option value="{{ $iaId }}">{{ $iaName }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="p-2">
                                <select name="relatives[0][political_affiliation_id]"
                                    class="w-full px-2.5 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 cursor-pointer">
                                    <option value="">-- السياسي --</option>
                                    @foreach($politicalAffiliations as $paId => $paName)
                                        <option value="{{ $paId }}">{{ $paName }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="p-2">
                                <input type="text" name="relatives[0][notes]"
                                    class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500"
                                    placeholder="ملاحظات...">
                            </td>
                            <td class="p-2 text-center">
                                <button type="button" class="remove-relative-row text-rose-500 hover:text-rose-700 hidden">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 3. Enemy Area Relatives --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                <div>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="w-2 h-5 bg-rose-500 rounded-full"></span>
                        الأهل والأقارب الذين في مناطق العدو
                    </h2>
                    <p class="text-slate-400 text-xs mt-0.5">تسجيل الأقارب المتواجدين في المحافظات والمناطق الواقعة تحت سيطرة العدو</p>
                </div>
                <button type="button" id="addEnemyRowBtn"
                    class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-rose-500/10 text-rose-600 dark:text-rose-400 hover:bg-rose-500/20 rounded-xl text-xs font-semibold transition-all">
                    <i class="fas fa-plus"></i> إضافة قريب بمناطق العدو
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-right border-collapse" id="enemyTable">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 font-bold text-xs uppercase tracking-wider">
                            <th class="px-3 py-3 min-w-[150px]">الاسم</th>
                            <th class="px-3 py-3 min-w-[120px]">صلة القرابة</th>
                            <th class="px-3 py-3 min-w-[130px]">مكان السكن</th>
                            <th class="px-3 py-3 min-w-[120px]">العمل</th>
                            <th class="px-3 py-3 min-w-[130px]">الانتماء السياسي</th>
                            <th class="px-3 py-3 min-w-[130px]">الانتماء العقائدي</th>
                            <th class="px-3 py-3 min-w-[120px]">طريقة التواصل</th>
                            <th class="px-3 py-3 min-w-[120px]">العلاقة</th>
                            <th class="px-3 py-3 min-w-[120px]">الحالة المادية</th>
                            <th class="px-3 py-3 w-10 text-center"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                        <tr class="enemy-row">
                            <td class="p-2">
                                <input type="text" name="enemy_area_relatives[0][full_name]"
                                    class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500"
                                    placeholder="الاسم...">
                            </td>
                            <td class="p-2">
                                <select name="enemy_area_relatives[0][kinship_id]"
                                    class="w-full px-2.5 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 cursor-pointer">
                                    <option value="">-- صلة القرابة --</option>
                                    @foreach($kinships as $kId => $kName)
                                        <option value="{{ $kId }}">{{ $kName }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="p-2">
                                <input type="text" name="enemy_area_relatives[0][residence_place]"
                                    class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500"
                                    placeholder="مكان السكن">
                            </td>
                            <td class="p-2">
                                <input type="text" name="enemy_area_relatives[0][job]"
                                    class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500"
                                    placeholder="العمل">
                            </td>
                            <td class="p-2">
                                <select name="enemy_area_relatives[0][political_affiliation_id]"
                                    class="w-full px-2.5 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 cursor-pointer">
                                    <option value="">-- السياسي --</option>
                                    @foreach($politicalAffiliations as $paId => $paName)
                                        <option value="{{ $paId }}">{{ $paName }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="p-2">
                                <select name="enemy_area_relatives[0][ideological_affiliation_id]"
                                    class="w-full px-2.5 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 cursor-pointer">
                                    <option value="">-- العقائدي --</option>
                                    @foreach($ideologicalAffiliations as $iaId => $iaName)
                                        <option value="{{ $iaId }}">{{ $iaName }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="p-2">
                                <input type="text" name="enemy_area_relatives[0][communication_method]"
                                    class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500"
                                    placeholder="طريقة التواصل">
                            </td>
                            <td class="p-2">
                                <input type="text" name="enemy_area_relatives[0][relationship]"
                                    class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500"
                                    placeholder="العلاقة">
                            </td>
                            <td class="p-2">
                                <select name="enemy_area_relatives[0][financial_status_id]"
                                    class="w-full px-2.5 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 cursor-pointer">
                                    <option value="">-- الحالة المادية --</option>
                                    @foreach($financialStatuses as $fsId => $fsName)
                                        <option value="{{ $fsId }}">{{ $fsName }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="p-2 text-center">
                                <button type="button" class="remove-enemy-row text-rose-500 hover:text-rose-700 hidden">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 4. Traveling Relatives --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                <div>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="w-2 h-5 bg-sky-500 rounded-full"></span>
                        الأهل والأقارب المسافرين خارج البلد
                    </h2>
                    <p class="text-slate-400 text-xs mt-0.5">تسجيل الأقارب المتواجدين في دول الاغتراب والخارج</p>
                </div>
                <button type="button" id="addTravelingRowBtn"
                    class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-sky-500/10 text-sky-600 dark:text-sky-400 hover:bg-sky-500/20 rounded-xl text-xs font-semibold transition-all">
                    <i class="fas fa-plus"></i> إضافة قريب مسافر
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-right border-collapse" id="travelingTable">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 font-bold text-xs uppercase tracking-wider">
                            <th class="px-3 py-3 min-w-[150px]">الاسم</th>
                            <th class="px-3 py-3 min-w-[65px] w-16">العمر</th>
                            <th class="px-3 py-3 min-w-[120px]">صلة القرابة</th>
                            <th class="px-3 py-3 min-w-[130px]">سبب السفر</th>
                            <th class="px-3 py-3 min-w-[120px]">العمل الحالي</th>
                            <th class="px-3 py-3 min-w-[120px]">مكان العمل</th>
                            <th class="px-3 py-3 min-w-[130px]">الجهة التابع لها</th>
                            <th class="px-3 py-3 min-w-[120px]">الدولة</th>
                            <th class="px-3 py-3 min-w-[120px]">الانتماء السياسي</th>
                            <th class="px-3 py-3 min-w-[120px]">الانتماء العقائدي</th>
                            <th class="px-3 py-3 min-w-[150px]">انطباعه عن المسيرة</th>
                            <th class="px-3 py-3 w-10 text-center"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                        <tr class="traveling-row">
                            <td class="p-2">
                                <input type="text" name="traveling_relatives[0][full_name]"
                                    class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500"
                                    placeholder="الاسم...">
                            </td>
                            <td class="p-2">
                                <input type="number" name="traveling_relatives[0][age]" min="0" max="150"
                                    class="w-full px-2 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-900 dark:text-white text-center focus:outline-none focus:border-brand-500"
                                    placeholder="العمر">
                            </td>
                            <td class="p-2">
                                <select name="traveling_relatives[0][kinship_id]"
                                    class="w-full px-2.5 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 cursor-pointer">
                                    <option value="">-- صلة القرابة --</option>
                                    @foreach($kinships as $kId => $kName)
                                        <option value="{{ $kId }}">{{ $kName }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="p-2">
                                <input type="text" name="traveling_relatives[0][travel_reason]"
                                    class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500"
                                    placeholder="سبب السفر">
                            </td>
                            <td class="p-2">
                                <input type="text" name="traveling_relatives[0][current_job]"
                                    class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500"
                                    placeholder="العمل الحالي">
                            </td>
                            <td class="p-2">
                                <input type="text" name="traveling_relatives[0][workplace]"
                                    class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500"
                                    placeholder="مكانه">
                            </td>
                            <td class="p-2">
                                <input type="text" name="traveling_relatives[0][affiliated_entity]"
                                    class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500"
                                    placeholder="الجهة التابع لها">
                            </td>
                            <td class="p-2">
                                <select name="traveling_relatives[0][country_id]"
                                    class="w-full px-2.5 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 cursor-pointer">
                                    <option value="">-- الدولة --</option>
                                    @foreach($countries as $cId => $cName)
                                        <option value="{{ $cId }}">{{ $cName }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="p-2">
                                <select name="traveling_relatives[0][political_affiliation_id]"
                                    class="w-full px-2.5 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 cursor-pointer">
                                    <option value="">-- السياسي --</option>
                                    @foreach($politicalAffiliations as $paId => $paName)
                                        <option value="{{ $paId }}">{{ $paName }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="p-2">
                                <select name="traveling_relatives[0][ideological_affiliation_id]"
                                    class="w-full px-2.5 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 cursor-pointer">
                                    <option value="">-- العقائدي --</option>
                                    @foreach($ideologicalAffiliations as $iaId => $iaName)
                                        <option value="{{ $iaId }}">{{ $iaName }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="p-2">
                                <input type="text" name="traveling_relatives[0][march_impression]"
                                    class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500"
                                    placeholder="انطباعه عن المسيرة...">
                            </td>
                            <td class="p-2 text-center">
                                <button type="button" class="remove-traveling-row text-rose-500 hover:text-rose-700 hidden">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-800">
            <a href="{{ route('system.relatives.index') }}"
                class="px-5 py-2.5 text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:text-slate-200 dark:hover:text-slate-200 transition-colors">
                إلغاء
            </a>
            <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-sm font-semibold shadow-sm hover:shadow transition-all">
                <i class="fas fa-save"></i>
                حفظ جميع بيانات الأقارب
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const personSelect = document.getElementById('personal_data_id');
        const personDetails = document.getElementById('person_details_container');

        if (personSelect) {
            personSelect.addEventListener('change', function () {
                const personId = this.value;
                if (!personId) {
                    personDetails.classList.add('hidden');
                    return;
                }

                fetch(`/system/personal-data/details/${personId}`)
                    .then(res => res.json())
                    .then(data => {
                        const photoEl = document.getElementById('p_photo');
                        const placeholderEl = document.getElementById('p_photo_placeholder');
                        if (data.photo_url) {
                            photoEl.src = data.photo_url;
                            photoEl.classList.remove('hidden');
                            placeholderEl.classList.add('hidden');
                        } else {
                            photoEl.src = '';
                            photoEl.classList.add('hidden');
                            placeholderEl.classList.remove('hidden');
                        }

                        document.getElementById('p_person_name').textContent = data.name || '-';
                        document.getElementById('p_person_sub').textContent = (data.military_number && data.military_number !== '-' ? 'الرقم العسكري: ' + data.military_number : '') + (data.jihadi_name && data.jihadi_name !== '-' ? ' | ' + data.jihadi_name : '');
                        document.getElementById('p_jihadi_name').textContent = data.jihadi_name || '-';
                        document.getElementById('p_military_number').textContent = data.military_number || '-';
                        document.getElementById('p_blood_type').textContent = data.blood_type || '-';
                        document.getElementById('p_national_id').textContent = data.national_id_number || '-';
                        document.getElementById('p_address').textContent = data.address || '-';
                        personDetails.classList.remove('hidden');
                    })
                    .catch(err => {
                        console.error('Error:', err);
                        personDetails.classList.add('hidden');
                    });
            });

            if (personSelect.value) {
                personSelect.dispatchEvent(new Event('change'));
            }
        }

        const kinshipOptions = `@foreach($kinships as $kId => $kName)<option value="{{ $kId }}">{{ $kName }}</option>@endforeach`;
        const ideologicalOptions = `@foreach($ideologicalAffiliations as $iaId => $iaName)<option value="{{ $iaId }}">{{ $iaName }}</option>@endforeach`;
        const politicalOptions = `@foreach($politicalAffiliations as $paId => $paName)<option value="{{ $paId }}">{{ $paName }}</option>@endforeach`;
        const countryOptions = `@foreach($countries as $cId => $cName)<option value="{{ $cId }}">{{ $cName }}</option>@endforeach`;
        const financialOptions = `@foreach($financialStatuses as $fsId => $fsName)<option value="{{ $fsId }}">{{ $fsName }}</option>@endforeach`;

        // 1. Dynamic Relatives table
        let relIndex = 1;
        document.getElementById('addRelativeRowBtn')?.addEventListener('click', function () {
            const tr = document.createElement('tr');
            tr.className = 'relative-row';
            tr.innerHTML = `
                <td class="p-2">
                    <input type="text" name="relatives[${relIndex}][full_name]" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500" placeholder="الاسم الكامل...">
                </td>
                <td class="p-2">
                    <input type="number" name="relatives[${relIndex}][age]" min="0" max="150" class="w-full px-2 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-900 dark:text-white text-center focus:outline-none focus:border-brand-500" placeholder="العمر">
                </td>
                <td class="p-2">
                    <select name="relatives[${relIndex}][kinship_id]" class="w-full px-2.5 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 cursor-pointer">
                        <option value="">-- صلة القرابة --</option>
                        ${kinshipOptions}
                    </select>
                </td>
                <td class="p-2">
                    <input type="text" name="relatives[${relIndex}][current_job]" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500" placeholder="العمل الحالي">
                </td>
                <td class="p-2">
                    <select name="relatives[${relIndex}][ideological_affiliation_id]" class="w-full px-2.5 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 cursor-pointer">
                        <option value="">-- العقائدي --</option>
                        ${ideologicalOptions}
                    </select>
                </td>
                <td class="p-2">
                    <select name="relatives[${relIndex}][political_affiliation_id]" class="w-full px-2.5 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 cursor-pointer">
                        <option value="">-- السياسي --</option>
                        ${politicalOptions}
                    </select>
                </td>
                <td class="p-2">
                    <input type="text" name="relatives[${relIndex}][notes]" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500" placeholder="ملاحظات...">
                </td>
                <td class="p-2 text-center">
                    <button type="button" class="remove-relative-row text-rose-500 hover:text-rose-700">
                        <i class="fas fa-trash text-xs"></i>
                    </button>
                </td>
            `;
            document.querySelector('#relativesTable tbody').appendChild(tr);
            if (typeof initSelect2ForElements === 'function') {
                initSelect2ForElements($(tr).find('select'));
            }
            relIndex++;
            updateRelativeRemoveButtons();
        });

        document.addEventListener('click', function (e) {
            if (e.target.closest('.remove-relative-row')) {
                e.target.closest('tr').remove();
                updateRelativeRemoveButtons();
            }
        });

        function updateRelativeRemoveButtons() {
            const rows = document.querySelectorAll('.relative-row');
            document.querySelectorAll('.remove-relative-row').forEach(btn => {
                btn.classList.toggle('hidden', rows.length <= 1);
            });
        }

        // 2. Dynamic Enemy Area Relatives table
        let enemyIndex = 1;
        document.getElementById('addEnemyRowBtn')?.addEventListener('click', function () {
            const tr = document.createElement('tr');
            tr.className = 'enemy-row';
            tr.innerHTML = `
                <td class="p-2">
                    <input type="text" name="enemy_area_relatives[${enemyIndex}][full_name]" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500" placeholder="الاسم...">
                </td>
                <td class="p-2">
                    <select name="enemy_area_relatives[${enemyIndex}][kinship_id]" class="w-full px-2.5 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 cursor-pointer">
                        <option value="">-- صلة القرابة --</option>
                        ${kinshipOptions}
                    </select>
                </td>
                <td class="p-2">
                    <input type="text" name="enemy_area_relatives[${enemyIndex}][residence_place]" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500" placeholder="مكان السكن">
                </td>
                <td class="p-2">
                    <input type="text" name="enemy_area_relatives[${enemyIndex}][job]" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500" placeholder="العمل">
                </td>
                <td class="p-2">
                    <select name="enemy_area_relatives[${enemyIndex}][political_affiliation_id]" class="w-full px-2.5 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 cursor-pointer">
                        <option value="">-- السياسي --</option>
                        ${politicalOptions}
                    </select>
                </td>
                <td class="p-2">
                    <select name="enemy_area_relatives[${enemyIndex}][ideological_affiliation_id]" class="w-full px-2.5 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 cursor-pointer">
                        <option value="">-- العقائدي --</option>
                        ${ideologicalOptions}
                    </select>
                </td>
                <td class="p-2">
                    <input type="text" name="enemy_area_relatives[${enemyIndex}][communication_method]" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500" placeholder="طريقة التواصل">
                </td>
                <td class="p-2">
                    <input type="text" name="enemy_area_relatives[${enemyIndex}][relationship]" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500" placeholder="العلاقة">
                </td>
                <td class="p-2">
                    <select name="enemy_area_relatives[${enemyIndex}][financial_status_id]" class="w-full px-2.5 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 cursor-pointer">
                        <option value="">-- الحالة المادية --</option>
                        ${financialOptions}
                    </select>
                </td>
                <td class="p-2 text-center">
                    <button type="button" class="remove-enemy-row text-rose-500 hover:text-rose-700">
                        <i class="fas fa-trash text-xs"></i>
                    </button>
                </td>
            `;
            document.querySelector('#enemyTable tbody').appendChild(tr);
            if (typeof initSelect2ForElements === 'function') {
                initSelect2ForElements($(tr).find('select'));
            }
            enemyIndex++;
            updateEnemyRemoveButtons();
        });

        document.addEventListener('click', function (e) {
            if (e.target.closest('.remove-enemy-row')) {
                e.target.closest('tr').remove();
                updateEnemyRemoveButtons();
            }
        });

        function updateEnemyRemoveButtons() {
            const rows = document.querySelectorAll('.enemy-row');
            document.querySelectorAll('.remove-enemy-row').forEach(btn => {
                btn.classList.toggle('hidden', rows.length <= 1);
            });
        }

        // 3. Dynamic Traveling Relatives table
        let travelingIndex = 1;
        document.getElementById('addTravelingRowBtn')?.addEventListener('click', function () {
            const tr = document.createElement('tr');
            tr.className = 'traveling-row';
            tr.innerHTML = `
                <td class="p-2">
                    <input type="text" name="traveling_relatives[${travelingIndex}][full_name]" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500" placeholder="الاسم...">
                </td>
                <td class="p-2">
                    <input type="number" name="traveling_relatives[${travelingIndex}][age]" min="0" max="150" class="w-full px-2 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-900 dark:text-white text-center focus:outline-none focus:border-brand-500" placeholder="العمر">
                </td>
                <td class="p-2">
                    <select name="traveling_relatives[${travelingIndex}][kinship_id]" class="w-full px-2.5 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 cursor-pointer">
                        <option value="">-- صلة القرابة --</option>
                        ${kinshipOptions}
                    </select>
                </td>
                <td class="p-2">
                    <input type="text" name="traveling_relatives[${travelingIndex}][travel_reason]" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500" placeholder="سبب السفر">
                </td>
                <td class="p-2">
                    <input type="text" name="traveling_relatives[${travelingIndex}][current_job]" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500" placeholder="العمل الحالي">
                </td>
                <td class="p-2">
                    <input type="text" name="traveling_relatives[${travelingIndex}][workplace]" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500" placeholder="مكانه">
                </td>
                <td class="p-2">
                    <input type="text" name="traveling_relatives[${travelingIndex}][affiliated_entity]" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500" placeholder="الجهة التابع لها">
                </td>
                <td class="p-2">
                    <select name="traveling_relatives[${travelingIndex}][country_id]" class="w-full px-2.5 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 cursor-pointer">
                        <option value="">-- الدولة --</option>
                        ${countryOptions}
                    </select>
                </td>
                <td class="p-2">
                    <select name="traveling_relatives[${travelingIndex}][political_affiliation_id]" class="w-full px-2.5 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 cursor-pointer">
                        <option value="">-- السياسي --</option>
                        ${politicalOptions}
                    </select>
                </td>
                <td class="p-2">
                    <select name="traveling_relatives[${travelingIndex}][ideological_affiliation_id]" class="w-full px-2.5 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 cursor-pointer">
                        <option value="">-- العقائدي --</option>
                        ${ideologicalOptions}
                    </select>
                </td>
                <td class="p-2">
                    <input type="text" name="traveling_relatives[${travelingIndex}][march_impression]" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500" placeholder="انطباعه عن المسيرة...">
                </td>
                <td class="p-2 text-center">
                    <button type="button" class="remove-traveling-row text-rose-500 hover:text-rose-700">
                        <i class="fas fa-trash text-xs"></i>
                    </button>
                </td>
            `;
            document.querySelector('#travelingTable tbody').appendChild(tr);
            if (typeof initSelect2ForElements === 'function') {
                initSelect2ForElements($(tr).find('select'));
            }
            travelingIndex++;
            updateTravelingRemoveButtons();
        });

        document.addEventListener('click', function (e) {
            if (e.target.closest('.remove-traveling-row')) {
                e.target.closest('tr').remove();
                updateTravelingRemoveButtons();
            }
        });

        function updateTravelingRemoveButtons() {
            const rows = document.querySelectorAll('.traveling-row');
            document.querySelectorAll('.remove-traveling-row').forEach(btn => {
                btn.classList.toggle('hidden', rows.length <= 1);
            });
        }

        window.initSelect2ForElements = function(elements) {
            if (typeof $ !== 'undefined' && $.fn.select2) {
                elements.each(function() {
                    $(this).select2({
                        theme: 'bootstrap-5',
                        dir: 'rtl',
                        tags: true,
                        width: '100%',
                        createTag: function (params) {
                            const term = $.trim(params.term);
                            if (term === '') {
                                return null;
                            }
                            return {id: term, text: term, newTag: true};
                        },
                        templateResult: function (data) {
                            if (data.newTag) {
                                return $('<span>').text('إضافة "' + data.text + '"');
                            }
                            return data.text;
                        }
                    });
                });
            }
        };
        initSelect2ForElements($('select'));
    });
</script>
@endsection

