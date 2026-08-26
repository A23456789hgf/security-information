@extends('layouts.app')

@section('title', 'إضافة الأهل والأقارب')
@section('page_title', 'النظام')

@section('content')
<div class="container-fluid mx-auto px-4 py-8" dir="rtl">
    {{-- رأس الصفحة --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8 max-w-7xl mx-auto">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-blue-600 rounded-3xl shadow-xl shadow-blue-100 flex items-center justify-center text-white">
                <i class="fas fa-users-cog text-2xl"></i>
            </div>
            <div>
                <h2 class="text-3xl font-extrabold text-slate-800 header-font mb-1">إضافة الأهل والأقارب</h2>
                <p class="text-slate-500 text-sm">تسجيل الأقارب داخل الوطن، في مناطق العدو، والمسافرين خارج البلد.</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('system.relatives.index') }}"
                class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl font-bold hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2 text-sm">
                <i class="fas fa-arrow-right"></i> عودة للقائمة
            </a>
            <button type="submit" form="relativesForm"
                class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold shadow-lg shadow-blue-100 transition-all flex items-center gap-2 text-sm">
                <i class="fas fa-save"></i> حفظ البيانات
            </button>
        </div>
    </div>

    @if ($errors->any())
        <div class="max-w-7xl mx-auto mb-8 p-6 bg-rose-50 border border-rose-200 rounded-3xl text-rose-700">
            <div class="flex items-center gap-3 mb-2 font-bold text-base">
                <i class="fas fa-exclamation-circle text-rose-500 text-lg"></i>
                يرجى تصحيح الأخطاء التالية:
            </div>
            <ul class="list-disc list-inside text-sm space-y-1 pr-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="relativesForm" action="{{ route('system.relatives.store') }}" method="POST">
        @csrf
        <div class="max-w-7xl mx-auto space-y-8">

            {{-- 1. اختيار الفرد الأساسي --}}
            <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50">
                    <h5 class="text-lg font-bold text-slate-800 header-font flex items-center gap-3">
                        <span class="w-2 h-6 bg-blue-600 rounded-full"></span>
                        الفرد الأساسي
                    </h5>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="text-xs font-bold text-slate-400 mb-2 block px-2">ابحث عن الفرد واختاره *</label>
                            <select id="personal_data_id" name="personal_data_id" required
                                class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all font-bold text-slate-700 select2-selection">
                                <option value="">-- اختر الفرد --</option>
                                @foreach($personalDataList as $id => $name)
                                    <option value="{{ $id }}" {{ (old('personal_data_id') ?? $selectedPersonalDataId) == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- تفاصيل الفرد المختار --}}
                        <div id="person_details_container" class="md:col-span-2 hidden">
                            <div class="bg-slate-50 border border-slate-100 rounded-[2rem] p-6">
                                <h6 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                                    <i class="fas fa-info-circle text-blue-600"></i> تفاصيل الفرد المختار
                                </h6>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                                    <div class="flex items-center gap-3 text-slate-600 bg-white p-3 rounded-2xl border border-slate-50">
                                        <span class="font-bold text-slate-400 w-24">الاسم الجهادي:</span>
                                        <span id="p_jihadi_name" class="font-bold text-slate-700">-</span>
                                    </div>
                                    <div class="flex items-center gap-3 text-slate-600 bg-white p-3 rounded-2xl border border-slate-50">
                                        <span class="font-bold text-slate-400 w-24">الرقم العسكري:</span>
                                        <span id="p_military_number" class="font-bold text-slate-700">-</span>
                                    </div>
                                    <div class="flex items-center gap-3 text-slate-600 bg-white p-3 rounded-2xl border border-slate-50">
                                        <span class="font-bold text-slate-400 w-24">فصيلة الدم:</span>
                                        <span id="p_blood_type" class="text-rose-600 font-extrabold">-</span>
                                    </div>
                                    <div class="flex items-center gap-3 text-slate-600 bg-white p-3 rounded-2xl border border-slate-50">
                                        <span class="font-bold text-slate-400 w-24">رقم الهوية:</span>
                                        <span id="p_national_id" class="font-bold text-slate-700">-</span>
                                    </div>
                                    <div class="flex items-center gap-3 text-slate-600 bg-white p-3 rounded-2xl border border-slate-50 col-span-1 md:col-span-2">
                                        <span class="font-bold text-slate-400 w-24">العنوان:</span>
                                        <span id="p_address" class="font-bold text-slate-700">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. جدول إدخال الأهل والأقارب (الأساسي) --}}
            <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <span class="w-2 h-6 bg-emerald-500 rounded-full"></span>
                        <h5 class="text-lg font-bold text-slate-800 header-font">الأهل والأقارب (بيانات عامة)</h5>
                    </div>
                    <button type="button" id="addRelativeRowBtn"
                        class="px-5 py-2.5 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 rounded-2xl font-bold transition-all flex items-center gap-2 text-xs">
                        <i class="fas fa-plus-circle"></i> إضافة قريب
                    </button>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-right text-xs" id="relativesTable">
                            <thead class="bg-slate-50/60 text-slate-500 font-bold border-b border-slate-100">
                                <tr>
                                    <th class="py-3 px-2 min-w-[170px]">الاسم الكامل</th>
                                    <th class="py-3 px-2 min-w-[70px] w-20">العمر</th>
                                    <th class="py-3 px-2 min-w-[130px]">صلة القرابة</th>
                                    <th class="py-3 px-2 min-w-[130px]">العمل الحالي</th>
                                    <th class="py-3 px-2 min-w-[130px]">الانتماء العقائدي</th>
                                    <th class="py-3 px-2 min-w-[130px]">الانتماء السياسي</th>
                                    <th class="py-3 px-2 min-w-[150px]">ملاحظات</th>
                                    <th class="py-3 px-2 w-10 text-center"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                <tr class="relative-row">
                                    <td class="p-1.5">
                                        <input type="text" name="relatives[0][full_name]"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-emerald-500 focus:bg-white"
                                            placeholder="الاسم الكامل...">
                                    </td>
                                    <td class="p-1.5">
                                        <input type="number" name="relatives[0][age]" min="0" max="150"
                                            class="w-full px-2 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-emerald-500 focus:bg-white text-center"
                                            placeholder="العمر">
                                    </td>
                                    <td class="p-1.5">
                                        <select name="relatives[0][kinship_id]"
                                            class="w-full px-2.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-emerald-500 focus:bg-white">
                                            <option value="">-- صلة القرابة --</option>
                                            @foreach($kinships as $kId => $kName)
                                                <option value="{{ $kId }}">{{ $kName }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="p-1.5">
                                        <input type="text" name="relatives[0][current_job]"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-emerald-500 focus:bg-white"
                                            placeholder="العمل الحالي">
                                    </td>
                                    <td class="p-1.5">
                                        <select name="relatives[0][ideological_affiliation_id]"
                                            class="w-full px-2.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-emerald-500 focus:bg-white">
                                            <option value="">-- العقائدي --</option>
                                            @foreach($ideologicalAffiliations as $iaId => $iaName)
                                                <option value="{{ $iaId }}">{{ $iaName }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="p-1.5">
                                        <select name="relatives[0][political_affiliation_id]"
                                            class="w-full px-2.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-emerald-500 focus:bg-white">
                                            <option value="">-- السياسي --</option>
                                            @foreach($politicalAffiliations as $paId => $paName)
                                                <option value="{{ $paId }}">{{ $paName }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="p-1.5">
                                        <input type="text" name="relatives[0][notes]"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-emerald-500 focus:bg-white"
                                            placeholder="ملاحظات...">
                                    </td>
                                    <td class="p-1.5 text-center">
                                        <button type="button" class="remove-relative-row text-rose-400 hover:text-rose-600 transition-colors hidden">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- 3. جدول الأهل والأقارب في مناطق العدو --}}
            <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <span class="w-2 h-6 bg-rose-500 rounded-full"></span>
                        <div>
                            <h5 class="text-lg font-bold text-slate-800 header-font">الأهل والأقارب الذين في مناطق العدو</h5>
                            <p class="text-slate-400 text-xs mt-0.5">تسجيل الأقارب المتواجدين في المحافظات والمناطق الواقعة تحت سيطرة العدو</p>
                        </div>
                    </div>
                    <button type="button" id="addEnemyRowBtn"
                        class="px-5 py-2.5 bg-rose-50 text-rose-700 hover:bg-rose-100 rounded-2xl font-bold transition-all flex items-center gap-2 text-xs">
                        <i class="fas fa-plus-circle"></i> إضافة قريب بمناطق العدو
                    </button>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-right text-xs" id="enemyTable">
                            <thead class="bg-slate-50/60 text-slate-500 font-bold border-b border-slate-100">
                                <tr>
                                    <th class="py-3 px-2 min-w-[150px]">الاسم</th>
                                    <th class="py-3 px-2 min-w-[120px]">صلة القرابة</th>
                                    <th class="py-3 px-2 min-w-[130px]">مكان السكن</th>
                                    <th class="py-3 px-2 min-w-[120px]">العمل</th>
                                    <th class="py-3 px-2 min-w-[130px]">الانتماء السياسي</th>
                                    <th class="py-3 px-2 min-w-[130px]">الانتماء العقائدي</th>
                                    <th class="py-3 px-2 min-w-[120px]">طريقة التواصل</th>
                                    <th class="py-3 px-2 min-w-[120px]">العلاقة</th>
                                    <th class="py-3 px-2 min-w-[120px]">الحالة المادية</th>
                                    <th class="py-3 px-2 w-10 text-center"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                <tr class="enemy-row">
                                    <td class="p-1.5">
                                        <input type="text" name="enemy_area_relatives[0][full_name]"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-rose-500 focus:bg-white"
                                            placeholder="الاسم...">
                                    </td>
                                    <td class="p-1.5">
                                        <select name="enemy_area_relatives[0][kinship_id]"
                                            class="w-full px-2.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-rose-500 focus:bg-white">
                                            <option value="">-- صلة القرابة --</option>
                                            @foreach($kinships as $kId => $kName)
                                                <option value="{{ $kId }}">{{ $kName }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="p-1.5">
                                        <input type="text" name="enemy_area_relatives[0][residence_place]"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-rose-500 focus:bg-white"
                                            placeholder="مكان السكن">
                                    </td>
                                    <td class="p-1.5">
                                        <input type="text" name="enemy_area_relatives[0][job]"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-rose-500 focus:bg-white"
                                            placeholder="العمل">
                                    </td>
                                    <td class="p-1.5">
                                        <select name="enemy_area_relatives[0][political_affiliation_id]"
                                            class="w-full px-2.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-rose-500 focus:bg-white">
                                            <option value="">-- السياسي --</option>
                                            @foreach($politicalAffiliations as $paId => $paName)
                                                <option value="{{ $paId }}">{{ $paName }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="p-1.5">
                                        <select name="enemy_area_relatives[0][ideological_affiliation_id]"
                                            class="w-full px-2.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-rose-500 focus:bg-white">
                                            <option value="">-- العقائدي --</option>
                                            @foreach($ideologicalAffiliations as $iaId => $iaName)
                                                <option value="{{ $iaId }}">{{ $iaName }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="p-1.5">
                                        <input type="text" name="enemy_area_relatives[0][communication_method]"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-rose-500 focus:bg-white"
                                            placeholder="طريقة التواصل">
                                    </td>
                                    <td class="p-1.5">
                                        <input type="text" name="enemy_area_relatives[0][relationship]"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-rose-500 focus:bg-white"
                                            placeholder="العلاقة">
                                    </td>
                                    <td class="p-1.5">
                                        <select name="enemy_area_relatives[0][financial_status_id]"
                                            class="w-full px-2.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-rose-500 focus:bg-white">
                                            <option value="">-- الحالة المادية --</option>
                                            @foreach($financialStatuses as $fsId => $fsName)
                                                <option value="{{ $fsId }}">{{ $fsName }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="p-1.5 text-center">
                                        <button type="button" class="remove-enemy-row text-rose-400 hover:text-rose-600 transition-colors hidden">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- 4. جدول الأهل والأقارب المسافرين خارج البلد --}}
            <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <span class="w-2 h-6 bg-sky-500 rounded-full"></span>
                        <div>
                            <h5 class="text-lg font-bold text-slate-800 header-font">الأهل والأقارب المسافرين خارج البلد</h5>
                            <p class="text-slate-400 text-xs mt-0.5">تسجيل الأقارب المتواجدين في دول الاغتراب والخارج</p>
                        </div>
                    </div>
                    <button type="button" id="addTravelingRowBtn"
                        class="px-5 py-2.5 bg-sky-50 text-sky-700 hover:bg-sky-100 rounded-2xl font-bold transition-all flex items-center gap-2 text-xs">
                        <i class="fas fa-plus-circle"></i> إضافة قريب مسافر
                    </button>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-right text-xs" id="travelingTable">
                            <thead class="bg-slate-50/60 text-slate-500 font-bold border-b border-slate-100">
                                <tr>
                                    <th class="py-3 px-2 min-w-[150px]">الاسم</th>
                                    <th class="py-3 px-2 min-w-[65px] w-16">العمر</th>
                                    <th class="py-3 px-2 min-w-[120px]">صلة القرابة</th>
                                    <th class="py-3 px-2 min-w-[130px]">سبب السفر</th>
                                    <th class="py-3 px-2 min-w-[120px]">العمل الحالي</th>
                                    <th class="py-3 px-2 min-w-[120px]">مكانه</th>
                                    <th class="py-3 px-2 min-w-[130px]">الجهة التابع لها</th>
                                    <th class="py-3 px-2 min-w-[120px]">الدولة</th>
                                    <th class="py-3 px-2 min-w-[120px]">الانتماء السياسي</th>
                                    <th class="py-3 px-2 min-w-[120px]">الانتماء العقائدي</th>
                                    <th class="py-3 px-2 min-w-[150px]">انطباعه عن المسيرة</th>
                                    <th class="py-3 px-2 w-10 text-center"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                <tr class="traveling-row">
                                    <td class="p-1.5">
                                        <input type="text" name="traveling_relatives[0][full_name]"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-sky-500 focus:bg-white"
                                            placeholder="الاسم...">
                                    </td>
                                    <td class="p-1.5">
                                        <input type="number" name="traveling_relatives[0][age]" min="0" max="150"
                                            class="w-full px-2 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-sky-500 focus:bg-white text-center"
                                            placeholder="العمر">
                                    </td>
                                    <td class="p-1.5">
                                        <select name="traveling_relatives[0][kinship_id]"
                                            class="w-full px-2.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-sky-500 focus:bg-white">
                                            <option value="">-- صلة القرابة --</option>
                                            @foreach($kinships as $kId => $kName)
                                                <option value="{{ $kId }}">{{ $kName }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="p-1.5">
                                        <input type="text" name="traveling_relatives[0][travel_reason]"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-sky-500 focus:bg-white"
                                            placeholder="سبب السفر">
                                    </td>
                                    <td class="p-1.5">
                                        <input type="text" name="traveling_relatives[0][current_job]"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-sky-500 focus:bg-white"
                                            placeholder="العمل الحالي">
                                    </td>
                                    <td class="p-1.5">
                                        <input type="text" name="traveling_relatives[0][workplace]"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-sky-500 focus:bg-white"
                                            placeholder="مكانه">
                                    </td>
                                    <td class="p-1.5">
                                        <input type="text" name="traveling_relatives[0][affiliated_entity]"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-sky-500 focus:bg-white"
                                            placeholder="الجهة التابع لها">
                                    </td>
                                    <td class="p-1.5">
                                        <select name="traveling_relatives[0][country_id]"
                                            class="w-full px-2.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-sky-500 focus:bg-white">
                                            <option value="">-- الدولة --</option>
                                            @foreach($countries as $cId => $cName)
                                                <option value="{{ $cId }}">{{ $cName }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="p-1.5">
                                        <select name="traveling_relatives[0][political_affiliation_id]"
                                            class="w-full px-2.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-sky-500 focus:bg-white">
                                            <option value="">-- السياسي --</option>
                                            @foreach($politicalAffiliations as $paId => $paName)
                                                <option value="{{ $paId }}">{{ $paName }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="p-1.5">
                                        <select name="traveling_relatives[0][ideological_affiliation_id]"
                                            class="w-full px-2.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-sky-500 focus:bg-white">
                                            <option value="">-- العقائدي --</option>
                                            @foreach($ideologicalAffiliations as $iaId => $iaName)
                                                <option value="{{ $iaId }}">{{ $iaName }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="p-1.5">
                                        <input type="text" name="traveling_relatives[0][march_impression]"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-sky-500 focus:bg-white"
                                            placeholder="انطباعه عن المسيرة...">
                                    </td>
                                    <td class="p-1.5 text-center">
                                        <button type="button" class="remove-traveling-row text-rose-400 hover:text-rose-600 transition-colors hidden">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- زر الحفظ --}}
            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('system.relatives.index') }}" class="px-8 py-3 text-slate-400 font-bold hover:text-slate-600 transition-colors">إلغاء</a>
                <button type="submit"
                    class="px-10 py-4 bg-blue-600 text-white rounded-[2rem] font-bold hover:bg-blue-700 transition-all shadow-xl shadow-blue-100 flex items-center gap-2">
                    <i class="fas fa-save"></i> حفظ جميع بيانات الأقارب
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    $(document).ready(function () {
        // Select2
        if ($.fn.select2) {
            $('.select2-selection').select2({
                theme: 'bootstrap-5',
                dir: 'rtl',
                width: '100%'
            });
        }

        // Person info ajax fetch
        const personSelect = $('#personal_data_id');
        const personDetails = $('#person_details_container');

        personSelect.on('change', function () {
            const personId = $(this).val();
            if (!personId) {
                personDetails.addClass('hidden');
                return;
            }

            fetch(`/system/personal-data/details/${personId}`)
                .then(res => res.json())
                .then(data => {
                    $('#p_jihadi_name').text(data.jihadi_name || '-');
                    $('#p_military_number').text(data.military_number || '-');
                    $('#p_blood_type').text(data.blood_type || '-');
                    $('#p_national_id').text(data.national_id_number || '-');
                    $('#p_address').text(data.address || '-');
                    personDetails.removeClass('hidden');
                })
                .catch(err => {
                    console.error('Error:', err);
                    personDetails.addClass('hidden');
                });
        });

        if (personSelect.val()) {
            personSelect.trigger('change');
        }

        // Shared dropdown HTML templates
        const kinshipOptions = `@foreach($kinships as $kId => $kName)<option value="{{ $kId }}">{{ $kName }}</option>@endforeach`;
        const ideologicalOptions = `@foreach($ideologicalAffiliations as $iaId => $iaName)<option value="{{ $iaId }}">{{ $iaName }}</option>@endforeach`;
        const politicalOptions = `@foreach($politicalAffiliations as $paId => $paName)<option value="{{ $paId }}">{{ $paName }}</option>@endforeach`;
        const countryOptions = `@foreach($countries as $cId => $cName)<option value="{{ $cId }}">{{ $cName }}</option>@endforeach`;
        const financialOptions = `@foreach($financialStatuses as $fsId => $fsName)<option value="{{ $fsId }}">{{ $fsName }}</option>@endforeach`;

        // 1. Dynamic Relatives Table
        let relIndex = 1;
        $('#addRelativeRowBtn').click(function () {
            const newRow = `
                <tr class="relative-row">
                    <td class="p-1.5">
                        <input type="text" name="relatives[${relIndex}][full_name]" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-emerald-500 focus:bg-white" placeholder="الاسم الكامل...">
                    </td>
                    <td class="p-1.5">
                        <input type="number" name="relatives[${relIndex}][age]" min="0" max="150" class="w-full px-2 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-emerald-500 focus:bg-white text-center" placeholder="العمر">
                    </td>
                    <td class="p-1.5">
                        <select name="relatives[${relIndex}][kinship_id]" class="w-full px-2.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-emerald-500 focus:bg-white">
                            <option value="">-- صلة القرابة --</option>
                            ${kinshipOptions}
                        </select>
                    </td>
                    <td class="p-1.5">
                        <input type="text" name="relatives[${relIndex}][current_job]" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-emerald-500 focus:bg-white" placeholder="العمل الحالي">
                    </td>
                    <td class="p-1.5">
                        <select name="relatives[${relIndex}][ideological_affiliation_id]" class="w-full px-2.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-emerald-500 focus:bg-white">
                            <option value="">-- العقائدي --</option>
                            ${ideologicalOptions}
                        </select>
                    </td>
                    <td class="p-1.5">
                        <select name="relatives[${relIndex}][political_affiliation_id]" class="w-full px-2.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-emerald-500 focus:bg-white">
                            <option value="">-- السياسي --</option>
                            ${politicalOptions}
                        </select>
                    </td>
                    <td class="p-1.5">
                        <input type="text" name="relatives[${relIndex}][notes]" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-emerald-500 focus:bg-white" placeholder="ملاحظات...">
                    </td>
                    <td class="p-1.5 text-center">
                        <button type="button" class="remove-relative-row text-rose-400 hover:text-rose-600 transition-colors">
                            <i class="fas fa-trash-alt text-xs"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#relativesTable tbody').append(newRow);
            relIndex++;
            updateRelativeRemoveButtons();
        });

        $(document).on('click', '.remove-relative-row', function () {
            $(this).closest('tr').remove();
            updateRelativeRemoveButtons();
        });

        function updateRelativeRemoveButtons() {
            $('.remove-relative-row').toggle($('.relative-row').length > 1);
        }

        // 2. Dynamic Enemy Area Relatives Table
        let enemyIndex = 1;
        $('#addEnemyRowBtn').click(function () {
            const newRow = `
                <tr class="enemy-row">
                    <td class="p-1.5">
                        <input type="text" name="enemy_area_relatives[${enemyIndex}][full_name]" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-rose-500 focus:bg-white" placeholder="الاسم...">
                    </td>
                    <td class="p-1.5">
                        <select name="enemy_area_relatives[${enemyIndex}][kinship_id]" class="w-full px-2.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-rose-500 focus:bg-white">
                            <option value="">-- صلة القرابة --</option>
                            ${kinshipOptions}
                        </select>
                    </td>
                    <td class="p-1.5">
                        <input type="text" name="enemy_area_relatives[${enemyIndex}][residence_place]" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-rose-500 focus:bg-white" placeholder="مكان السكن">
                    </td>
                    <td class="p-1.5">
                        <input type="text" name="enemy_area_relatives[${enemyIndex}][job]" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-rose-500 focus:bg-white" placeholder="العمل">
                    </td>
                    <td class="p-1.5">
                        <select name="enemy_area_relatives[${enemyIndex}][political_affiliation_id]" class="w-full px-2.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-rose-500 focus:bg-white">
                            <option value="">-- السياسي --</option>
                            ${politicalOptions}
                        </select>
                    </td>
                    <td class="p-1.5">
                        <select name="enemy_area_relatives[${enemyIndex}][ideological_affiliation_id]" class="w-full px-2.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-rose-500 focus:bg-white">
                            <option value="">-- العقائدي --</option>
                            ${ideologicalOptions}
                        </select>
                    </td>
                    <td class="p-1.5">
                        <input type="text" name="enemy_area_relatives[${enemyIndex}][communication_method]" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-rose-500 focus:bg-white" placeholder="طريقة التواصل">
                    </td>
                    <td class="p-1.5">
                        <input type="text" name="enemy_area_relatives[${enemyIndex}][relationship]" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-rose-500 focus:bg-white" placeholder="العلاقة">
                    </td>
                    <td class="p-1.5">
                        <select name="enemy_area_relatives[${enemyIndex}][financial_status_id]" class="w-full px-2.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-rose-500 focus:bg-white">
                            <option value="">-- الحالة المادية --</option>
                            ${financialOptions}
                        </select>
                    </td>
                    <td class="p-1.5 text-center">
                        <button type="button" class="remove-enemy-row text-rose-400 hover:text-rose-600 transition-colors">
                            <i class="fas fa-trash-alt text-xs"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#enemyTable tbody').append(newRow);
            enemyIndex++;
            updateEnemyRemoveButtons();
        });

        $(document).on('click', '.remove-enemy-row', function () {
            $(this).closest('tr').remove();
            updateEnemyRemoveButtons();
        });

        function updateEnemyRemoveButtons() {
            $('.remove-enemy-row').toggle($('.enemy-row').length > 1);
        }

        // 3. Dynamic Traveling Relatives Table
        let travelIndex = 1;
        $('#addTravelingRowBtn').click(function () {
            const newRow = `
                <tr class="traveling-row">
                    <td class="p-1.5">
                        <input type="text" name="traveling_relatives[${travelIndex}][full_name]" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-sky-500 focus:bg-white" placeholder="الاسم...">
                    </td>
                    <td class="p-1.5">
                        <input type="number" name="traveling_relatives[${travelIndex}][age]" min="0" max="150" class="w-full px-2 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-sky-500 focus:bg-white text-center" placeholder="العمر">
                    </td>
                    <td class="p-1.5">
                        <select name="traveling_relatives[${travelIndex}][kinship_id]" class="w-full px-2.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-sky-500 focus:bg-white">
                            <option value="">-- صلة القرابة --</option>
                            ${kinshipOptions}
                        </select>
                    </td>
                    <td class="p-1.5">
                        <input type="text" name="traveling_relatives[${travelIndex}][travel_reason]" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-sky-500 focus:bg-white" placeholder="سبب السفر">
                    </td>
                    <td class="p-1.5">
                        <input type="text" name="traveling_relatives[${travelIndex}][current_job]" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-sky-500 focus:bg-white" placeholder="العمل الحالي">
                    </td>
                    <td class="p-1.5">
                        <input type="text" name="traveling_relatives[${travelIndex}][workplace]" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-sky-500 focus:bg-white" placeholder="مكانه">
                    </td>
                    <td class="p-1.5">
                        <input type="text" name="traveling_relatives[${travelIndex}][affiliated_entity]" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-sky-500 focus:bg-white" placeholder="الجهة التابع لها">
                    </td>
                    <td class="p-1.5">
                        <select name="traveling_relatives[${travelIndex}][country_id]" class="w-full px-2.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-sky-500 focus:bg-white">
                            <option value="">-- الدولة --</option>
                            ${countryOptions}
                        </select>
                    </td>
                    <td class="p-1.5">
                        <select name="traveling_relatives[${travelIndex}][political_affiliation_id]" class="w-full px-2.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-sky-500 focus:bg-white">
                            <option value="">-- السياسي --</option>
                            ${politicalOptions}
                        </select>
                    </td>
                    <td class="p-1.5">
                        <select name="traveling_relatives[${travelIndex}][ideological_affiliation_id]" class="w-full px-2.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-sky-500 focus:bg-white">
                            <option value="">-- العقائدي --</option>
                            ${ideologicalOptions}
                        </select>
                    </td>
                    <td class="p-1.5">
                        <input type="text" name="traveling_relatives[${travelIndex}][march_impression]" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-sky-500 focus:bg-white" placeholder="انطباعه عن المسيرة...">
                    </td>
                    <td class="p-1.5 text-center">
                        <button type="button" class="remove-traveling-row text-rose-400 hover:text-rose-600 transition-colors">
                            <i class="fas fa-trash-alt text-xs"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#travelingTable tbody').append(newRow);
            travelIndex++;
            updateTravelingRemoveButtons();
        });

        $(document).on('click', '.remove-traveling-row', function () {
            $(this).closest('tr').remove();
            updateTravelingRemoveButtons();
        });

        function updateTravelingRemoveButtons() {
            $('.remove-traveling-row').toggle($('.traveling-row').length > 1);
        }
    });
</script>
@endpush
@endsection
