@extends('layouts.app')

@section('title', 'إضافة بيانات ثانوية')

@section('content')
<div class="container-fluid mx-auto px-4 py-6" dir="rtl">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 max-w-7xl mx-auto">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-slate-800 rounded-2xl shadow-md flex items-center justify-center text-white">
                <i class="fas fa-file-medical text-lg"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-800 header-font mb-0.5">إضافة بيانات ثانوية</h2>
                <p class="text-slate-400 text-xs">تسجيل الحالة الصحية، الأولاد، الانتماءات، الأعمال الجهادية، والأسلحة للشخص.</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('system.secondary-data.index') }}"
                class="px-4 py-2 bg-white border border-slate-200 text-slate-600 font-bold text-xs rounded-xl hover:bg-slate-50 transition-all shadow-sm flex items-center gap-1.5">
                <i class="fas fa-arrow-right"></i> عودة
            </a>
            <button type="submit" form="secondaryDataForm"
                class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-1.5">
                <i class="fas fa-save text-xs"></i> حفظ البيانات
            </button>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-rose-50 border border-rose-100 rounded-2xl p-4 mb-6 max-w-7xl mx-auto">
            <div class="flex items-center gap-2 text-rose-700 mb-2 font-bold text-xs">
                <i class="fas fa-exclamation-triangle"></i>
                <span>يرجى تصحيح الأخطاء التالية:</span>
            </div>
            <ul class="list-disc list-inside text-rose-600 text-xs space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="secondaryDataForm" action="{{ route('system.secondary-data.store') }}" method="POST">
        @csrf
        <div class="max-w-7xl mx-auto space-y-6">

            {{-- Row 1: Person Selection & Basic / Family Information --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                {{-- Person Selection Card --}}
                <div class="lg:col-span-4">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden h-full">
                        <div class="px-5 py-4 border-b border-slate-50 bg-slate-50/50">
                            <h5 class="text-base font-bold text-slate-800 header-font flex items-center gap-2">
                                <span class="w-1.5 h-5 bg-blue-600 rounded-full"></span>
                                اختيار الشخص
                            </h5>
                        </div>
                        <div class="p-5 space-y-4">
                            <div>
                                <label class="text-xs font-semibold text-slate-600 mb-1.5 block">ابحث عن الشخص واختاره <span class="text-rose-500">*</span></label>
                                <select name="personal_data_id" id="personal_data_id"
                                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:ring-1 focus:ring-blue-500 focus:bg-white transition-all select2-selection"
                                    required>
                                    <option value="">-- اختر الشخص --</option>
                                    @foreach($personalDataList as $id => $name)
                                        <option value="{{ $id }}" {{ (isset($selectedPersonalDataId) && $selectedPersonalDataId == $id) || old('personal_data_id') == $id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Person Info Box --}}
                            <div id="personal_info_box" class="hidden">
                                <div class="bg-slate-50 border border-slate-100 rounded-xl p-3.5 text-xs space-y-2">
                                    <h6 class="text-[11px] font-bold text-slate-500 uppercase flex items-center gap-1.5 mb-2">
                                        <i class="fas fa-info-circle text-blue-600"></i> تفاصيل الفرد المختار
                                    </h6>
                                    <div class="grid grid-cols-2 gap-2 text-[11px]">
                                        <div class="bg-white p-2 rounded-lg border border-slate-100">
                                            <span class="text-slate-400 block text-[10px]">الاسم الجهادي:</span>
                                            <span id="info_jihadi_name" class="font-bold text-slate-700">-</span>
                                        </div>
                                        <div class="bg-white p-2 rounded-lg border border-slate-100">
                                            <span class="text-slate-400 block text-[10px]">الرقم العسكري:</span>
                                            <span id="info_military_number" class="font-bold text-slate-700">-</span>
                                        </div>
                                        <div class="bg-white p-2 rounded-lg border border-slate-100">
                                            <span class="text-slate-400 block text-[10px]">فصيلة الدم:</span>
                                            <span id="info_blood_type" class="font-extrabold text-rose-600">-</span>
                                        </div>
                                        <div class="bg-white p-2 rounded-lg border border-slate-100">
                                            <span class="text-slate-400 block text-[10px]">الحالة الاجتماعية:</span>
                                            <span id="info_marital_status" class="font-bold text-slate-700">-</span>
                                        </div>
                                        <div class="bg-white p-2 rounded-lg border border-slate-100 col-span-2">
                                            <span class="text-slate-400 block text-[10px]">العنوان:</span>
                                            <span id="info_address" class="font-bold text-slate-700 truncate block">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-2 border-t border-slate-100 space-y-3">
                                <div>
                                    <label class="text-xs font-semibold text-slate-600 mb-1 block">تاريخ الانطلاقة / الالتحاق</label>
                                    <input type="date" name="start_date"
                                        class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:ring-1 focus:ring-blue-500 focus:bg-white transition-all"
                                        value="{{ old('start_date') }}">
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-slate-600 mb-1 block">المسؤول المباشر</label>
                                    <input type="text" name="direct_supervisor"
                                        class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:ring-1 focus:ring-blue-500 focus:bg-white transition-all"
                                        value="{{ old('direct_supervisor') }}" placeholder="اسم المسؤول المباشر">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Health Status, Children, & Activity Card --}}
                <div class="lg:col-span-8">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden h-full">
                        <div class="px-5 py-4 border-b border-slate-50 bg-slate-50/50">
                            <h5 class="text-base font-bold text-slate-800 header-font flex items-center gap-2">
                                <span class="w-1.5 h-5 bg-teal-500 rounded-full"></span>
                                الحالة الصحية والبيانات الأسرية والجهادية
                            </h5>
                        </div>
                        <div class="p-5 space-y-5">
                            {{-- Health Status & Children Row --}}
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-4 bg-slate-50/70 rounded-2xl border border-slate-100">
                                {{-- Health Status Dropdown --}}
                                <div class="md:col-span-1">
                                    <label class="text-xs font-bold text-slate-700 mb-1.5 block flex items-center gap-1.5">
                                        <i class="fas fa-heartbeat text-rose-500"></i> الحالة الصحية
                                    </label>
                                    <select name="health_status_id" id="health_status_id"
                                        class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:ring-1 focus:ring-teal-500 transition-all">
                                        <option value="">-- اختر الحالة الصحية --</option>
                                        @foreach($healthStatuses as $id => $name)
                                            <option value="{{ $id }}" {{ old('health_status_id') == $id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Male Children --}}
                                <div class="md:col-span-1">
                                    <label class="text-xs font-bold text-slate-700 mb-1.5 block flex items-center gap-1.5">
                                        <i class="fas fa-male text-blue-500"></i> عدد الأولاد الذكور
                                    </label>
                                    <input type="number" name="male_children_count" id="male_children_count" min="0" max="100"
                                        class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:ring-1 focus:ring-blue-500 transition-all children-calc"
                                        value="{{ old('male_children_count', 0) }}" placeholder="0">
                                </div>

                                {{-- Female Children --}}
                                <div class="md:col-span-1">
                                    <label class="text-xs font-bold text-slate-700 mb-1.5 block flex items-center gap-1.5">
                                        <i class="fas fa-female text-pink-500"></i> عدد الأولاد الإناث
                                    </label>
                                    <input type="number" name="female_children_count" id="female_children_count" min="0" max="100"
                                        class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:ring-1 focus:ring-pink-500 transition-all children-calc"
                                        value="{{ old('female_children_count', 0) }}" placeholder="0">
                                </div>

                                {{-- Total Children (Auto-Calculated) --}}
                                <div class="md:col-span-1">
                                    <label class="text-xs font-bold text-slate-700 mb-1.5 block flex items-center gap-1.5">
                                        <i class="fas fa-users text-purple-600"></i> الإجمالي (المجموع)
                                    </label>
                                    <div class="relative">
                                        <input type="text" id="total_children_display" readonly
                                            class="w-full px-3.5 py-2.5 bg-purple-50 border border-purple-200 rounded-xl text-xs font-extrabold text-purple-700 cursor-not-allowed text-center"
                                            value="{{ (int)old('male_children_count', 0) + (int)old('female_children_count', 0) }}">
                                        <span class="absolute left-3 top-2.5 text-[10px] text-purple-400 font-bold">أولاد</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Jihadist Activity Textareas --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-semibold text-slate-600 mb-1.5 block">النشاط الجهادي الحالي</label>
                                    <textarea name="current_jihadist_activity" rows="3"
                                        class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:ring-1 focus:ring-blue-500 focus:bg-white transition-all"
                                        placeholder="وصف النشاط والمهمة الحالية...">{{ old('current_jihadist_activity') }}</textarea>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-slate-600 mb-1.5 block">ملاحظات النشاط الجهادي السابق</label>
                                    <textarea name="previous_jihadist_activity" rows="3"
                                        class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:ring-1 focus:ring-blue-500 focus:bg-white transition-all"
                                        placeholder="ملاحظات عامة حول النشاط السابق...">{{ old('previous_jihadist_activity') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Master-Detail Section 1: Political & Ideological Affiliations --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-50 bg-slate-50/50 flex flex-wrap justify-between items-center gap-2">
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-5 bg-indigo-600 rounded-full"></span>
                        <h5 class="text-base font-bold text-slate-800 header-font">الانتماء السياسي والعقائدي</h5>
                        <span class="text-slate-400 text-xs font-normal">(جدول متعدد السجلات)</span>
                    </div>
                    <button type="button" id="add-affiliation-row"
                        class="px-3.5 py-1.5 bg-indigo-50 text-indigo-700 rounded-xl font-bold text-xs hover:bg-indigo-100 transition-all flex items-center gap-1.5 shadow-sm">
                        <i class="fas fa-plus text-xs"></i> إضافة انتماء
                    </button>
                </div>
                <div class="p-0">
                    <div class="overflow-x-auto">
                        <table class="w-full text-right text-xs" id="affiliations-table">
                            <thead class="bg-slate-50/60 text-[11px] font-bold text-slate-500 border-b border-slate-100">
                                <tr>
                                    <th class="px-3 py-3 min-w-[130px]">الجهة</th>
                                    <th class="px-3 py-3 min-w-[150px]">الانتماء العقائدي</th>
                                    <th class="px-3 py-3 min-w-[150px]">الانتماء السياسي</th>
                                    <th class="px-3 py-3 min-w-[130px]">تاريخ الارتباط</th>
                                    <th class="px-3 py-3 min-w-[110px]">مدة الانتماء</th>
                                    <th class="px-3 py-3 min-w-[130px]">مكان العمل</th>
                                    <th class="px-3 py-3 min-w-[120px]">نوع العمل</th>
                                    <th class="px-3 py-3 min-w-[130px]">المسؤول المباشر</th>
                                    <th class="px-3 py-3 min-w-[130px]">تاريخ الترك</th>
                                    <th class="px-3 py-3 min-w-[150px]">سبب الترك</th>
                                    <th class="px-3 py-3 w-10 text-center"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 text-xs">
                                <tr class="affiliation-row group hover:bg-slate-50/50 transition-all">
                                    <td class="p-2">
                                        <input type="text" name="affiliations[0][party]"
                                            class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:ring-1 focus:ring-indigo-500 focus:bg-white"
                                            placeholder="الجهة...">
                                    </td>
                                    <td class="p-2">
                                        <select name="affiliations[0][ideological_affiliation_id]"
                                            class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:ring-1 focus:ring-indigo-500 focus:bg-white">
                                            <option value="">-- اختر --</option>
                                            @foreach($ideologicalAffiliations as $id => $name)
                                                <option value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="p-2">
                                        <select name="affiliations[0][political_affiliation_id]"
                                            class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:ring-1 focus:ring-indigo-500 focus:bg-white">
                                            <option value="">-- اختر --</option>
                                            @foreach($politicalAffiliations as $id => $name)
                                                <option value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="p-2">
                                        <input type="date" name="affiliations[0][affiliation_date]"
                                            class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:ring-1 focus:ring-indigo-500 focus:bg-white">
                                    </td>
                                    <td class="p-2">
                                        <input type="text" name="affiliations[0][duration]"
                                            class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:ring-1 focus:ring-indigo-500 focus:bg-white"
                                            placeholder="مثل: سنتين">
                                    </td>
                                    <td class="p-2">
                                        <input type="text" name="affiliations[0][workplace]"
                                            class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:ring-1 focus:ring-indigo-500 focus:bg-white"
                                            placeholder="مكان العمل">
                                    </td>
                                    <td class="p-2">
                                        <input type="text" name="affiliations[0][job_type]"
                                            class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:ring-1 focus:ring-indigo-500 focus:bg-white"
                                            placeholder="نوع العمل">
                                    </td>
                                    <td class="p-2">
                                        <input type="text" name="affiliations[0][direct_supervisor]"
                                            class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:ring-1 focus:ring-indigo-500 focus:bg-white"
                                            placeholder="المسؤول المباشر">
                                    </td>
                                    <td class="p-2">
                                        <input type="date" name="affiliations[0][leaving_date]"
                                            class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:ring-1 focus:ring-indigo-500 focus:bg-white">
                                    </td>
                                    <td class="p-2">
                                        <input type="text" name="affiliations[0][leaving_reason]"
                                            class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:ring-1 focus:ring-indigo-500 focus:bg-white"
                                            placeholder="سبب الترك...">
                                    </td>
                                    <td class="p-2 text-center">
                                        <button type="button" class="remove-affiliation-row text-rose-400 hover:text-rose-600 transition-colors hidden">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Master-Detail Section 2: Previous Jihadist Actions --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-50 bg-slate-50/50 flex flex-wrap justify-between items-center gap-2">
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-5 bg-amber-500 rounded-full"></span>
                        <h5 class="text-base font-bold text-slate-800 header-font">الأعمال الجهادية السابقة</h5>
                        <span class="text-slate-400 text-xs font-normal">(جدول تفصيلي للأنشطة السابقة)</span>
                    </div>
                    <button type="button" id="add-action-row"
                        class="px-3.5 py-1.5 bg-amber-50 text-amber-700 rounded-xl font-bold text-xs hover:bg-amber-100 transition-all flex items-center gap-1.5 shadow-sm">
                        <i class="fas fa-plus text-xs"></i> إضافة عمل جهادي
                    </button>
                </div>
                <div class="p-0">
                    <div class="overflow-x-auto">
                        <table class="w-full text-right text-xs" id="actions-table">
                            <thead class="bg-slate-50/60 text-[11px] font-bold text-slate-500 border-b border-slate-100">
                                <tr>
                                    <th class="px-3 py-3 min-w-[200px]">النشاط</th>
                                    <th class="px-3 py-3 min-w-[140px]">التاريخ</th>
                                    <th class="px-3 py-3 min-w-[130px]">المدة الزمنية</th>
                                    <th class="px-3 py-3 min-w-[150px]">المكان</th>
                                    <th class="px-3 py-3 min-w-[150px]">المسؤول المباشر</th>
                                    <th class="px-3 py-3 min-w-[180px]">سبب الترك</th>
                                    <th class="px-3 py-3 w-10 text-center"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 text-xs">
                                <tr class="action-row group hover:bg-slate-50/50 transition-all">
                                    <td class="p-2">
                                        <input type="text" name="previous_jihadist_actions[0][activity]"
                                            class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:ring-1 focus:ring-amber-500 focus:bg-white"
                                            placeholder="اسم النشاط / المهمة...">
                                    </td>
                                    <td class="p-2">
                                        <input type="date" name="previous_jihadist_actions[0][activity_date]"
                                            class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:ring-1 focus:ring-amber-500 focus:bg-white">
                                    </td>
                                    <td class="p-2">
                                        <input type="text" name="previous_jihadist_actions[0][duration]"
                                            class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:ring-1 focus:ring-amber-500 focus:bg-white"
                                            placeholder="مثل: 6 أشهر">
                                    </td>
                                    <td class="p-2">
                                        <input type="text" name="previous_jihadist_actions[0][location]"
                                            class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:ring-1 focus:ring-amber-500 focus:bg-white"
                                            placeholder="المكان / الجبهة">
                                    </td>
                                    <td class="p-2">
                                        <input type="text" name="previous_jihadist_actions[0][direct_supervisor]"
                                            class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:ring-1 focus:ring-amber-500 focus:bg-white"
                                            placeholder="المسؤول المباشر">
                                    </td>
                                    <td class="p-2">
                                        <input type="text" name="previous_jihadist_actions[0][leaving_reason]"
                                            class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:ring-1 focus:ring-amber-500 focus:bg-white"
                                            placeholder="سبب الترك...">
                                    </td>
                                    <td class="p-2 text-center">
                                        <button type="button" class="remove-action-row text-rose-400 hover:text-rose-600 transition-colors hidden">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Master-Detail Section 3: Weapons & Custody --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-5 bg-emerald-500 rounded-full"></span>
                        <h5 class="text-base font-bold text-slate-800 header-font">بيانات الأسلحة والعهدة</h5>
                    </div>
                    <button type="button" id="add-weapon-row"
                        class="px-3.5 py-1.5 bg-emerald-50 text-emerald-600 rounded-xl font-bold text-xs hover:bg-emerald-100 transition-all flex items-center gap-1.5 shadow-sm">
                        <i class="fas fa-plus text-xs"></i> إضافة سلاح
                    </button>
                </div>
                <div class="p-0">
                    <div class="overflow-x-auto">
                        <table class="w-full text-right text-xs" id="weapons-table">
                            <thead class="bg-slate-50/60 text-[11px] font-bold text-slate-500 border-b border-slate-100">
                                <tr>
                                    <th class="px-4 py-3 min-w-[200px]">نوع السلاح</th>
                                    <th class="px-4 py-3 min-w-[150px]">الرقم</th>
                                    <th class="px-4 py-3 min-w-[150px]">الملكية</th>
                                    <th class="px-4 py-3 min-w-[150px]">التعقب</th>
                                    <th class="px-4 py-3 w-10 text-center"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 text-xs">
                                <tr class="weapon-row group hover:bg-slate-50/50 transition-all">
                                    <td class="p-2">
                                        <input type="text" name="weapons[0][weapon_type]"
                                            class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:ring-1 focus:ring-emerald-500 focus:bg-white"
                                            placeholder="نوع السلاح...">
                                    </td>
                                    <td class="p-2">
                                        <input type="text" name="weapons[0][number]"
                                            class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:ring-1 focus:ring-emerald-500 focus:bg-white"
                                            placeholder="الرقم">
                                    </td>
                                    <td class="p-2">
                                        <input type="text" name="weapons[0][ownership]"
                                            class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:ring-1 focus:ring-emerald-500 focus:bg-white"
                                            placeholder="الملكية">
                                    </td>
                                    <td class="p-2">
                                        <input type="text" name="weapons[0][tracking]"
                                            class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:ring-1 focus:ring-emerald-500 focus:bg-white"
                                            placeholder="التعقب">
                                    </td>
                                    <td class="p-2 text-center">
                                        <button type="button" class="remove-weapon-row text-rose-400 hover:text-rose-600 transition-colors hidden">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Submit Section --}}
            <div class="flex justify-end gap-3 pt-2">
                <button type="submit"
                    class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-lg transition-all flex items-center gap-2">
                    <i class="fas fa-check-circle text-xs"></i> اعتماد وحفظ السجل الثانوي بالكامل
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    $(document).ready(function () {
        // Select2 initialization
        if ($.fn.select2) {
            $('.select2-selection').select2({
                theme: 'bootstrap-5',
                dir: 'rtl',
                width: '100%'
            });
        }

        // Person info ajax fetch
        const personSelect = $('#personal_data_id');
        const infoBox = $('#personal_info_box');

        personSelect.on('change', function () {
            const personId = $(this).val();
            if (!personId) {
                infoBox.addClass('hidden');
                return;
            }

            fetch(`/system/personal-data/details/${personId}`)
                .then(res => res.json())
                .then(data => {
                    $('#info_jihadi_name').text(data.jihadi_name || '-');
                    $('#info_military_number').text(data.military_number || '-');
                    $('#info_blood_type').text(data.blood_type || '-');
                    $('#info_marital_status').text(data.marital_status || '-');
                    $('#info_address').text(data.address || '-');
                    infoBox.removeClass('hidden');
                })
                .catch(err => {
                    console.error('Error:', err);
                    infoBox.addClass('hidden');
                });
        });

        if (personSelect.val()) {
            personSelect.trigger('change');
        }

        // Automatic Total Children Calculation
        function calculateTotalChildren() {
            const male = parseInt($('#male_children_count').val()) || 0;
            const female = parseInt($('#female_children_count').val()) || 0;
            $('#total_children_display').val(male + female);
        }

        $('.children-calc').on('input change keyup', calculateTotalChildren);
        calculateTotalChildren();

        // 1. Dynamic Affiliation Rows
        let affIndex = 1;
        const ideologicalOptions = `@foreach($ideologicalAffiliations as $id => $name)<option value="{{ $id }}">{{ $name }}</option>@endforeach`;
        const politicalOptions = `@foreach($politicalAffiliations as $id => $name)<option value="{{ $id }}">{{ $name }}</option>@endforeach`;

        $('#add-affiliation-row').click(function () {
            const newRow = `
                <tr class="affiliation-row group hover:bg-slate-50/50 transition-all border-t border-slate-50">
                    <td class="p-2">
                        <input type="text" name="affiliations[${affIndex}][party]" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:ring-1 focus:ring-indigo-500 focus:bg-white" placeholder="الجهة...">
                    </td>
                    <td class="p-2">
                        <select name="affiliations[${affIndex}][ideological_affiliation_id]" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:ring-1 focus:ring-indigo-500 focus:bg-white">
                            <option value="">-- اختر --</option>
                            ${ideologicalOptions}
                        </select>
                    </td>
                    <td class="p-2">
                        <select name="affiliations[${affIndex}][political_affiliation_id]" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:ring-1 focus:ring-indigo-500 focus:bg-white">
                            <option value="">-- اختر --</option>
                            ${politicalOptions}
                        </select>
                    </td>
                    <td class="p-2">
                        <input type="date" name="affiliations[${affIndex}][affiliation_date]" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:ring-1 focus:ring-indigo-500 focus:bg-white">
                    </td>
                    <td class="p-2">
                        <input type="text" name="affiliations[${affIndex}][duration]" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:ring-1 focus:ring-indigo-500 focus:bg-white" placeholder="مثل: سنتين">
                    </td>
                    <td class="p-2">
                        <input type="text" name="affiliations[${affIndex}][workplace]" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:ring-1 focus:ring-indigo-500 focus:bg-white" placeholder="مكان العمل">
                    </td>
                    <td class="p-2">
                        <input type="text" name="affiliations[${affIndex}][job_type]" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:ring-1 focus:ring-indigo-500 focus:bg-white" placeholder="نوع العمل">
                    </td>
                    <td class="p-2">
                        <input type="text" name="affiliations[${affIndex}][direct_supervisor]" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:ring-1 focus:ring-indigo-500 focus:bg-white" placeholder="المسؤول المباشر">
                    </td>
                    <td class="p-2">
                        <input type="date" name="affiliations[${affIndex}][leaving_date]" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:ring-1 focus:ring-indigo-500 focus:bg-white">
                    </td>
                    <td class="p-2">
                        <input type="text" name="affiliations[${affIndex}][leaving_reason]" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:ring-1 focus:ring-indigo-500 focus:bg-white" placeholder="سبب الترك...">
                    </td>
                    <td class="p-2 text-center">
                        <button type="button" class="remove-affiliation-row text-rose-400 hover:text-rose-600 transition-colors">
                            <i class="fas fa-trash-alt text-xs"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#affiliations-table tbody').append(newRow);
            affIndex++;
            updateAffiliationRemoveButtons();
        });

        $(document).on('click', '.remove-affiliation-row', function () {
            $(this).closest('tr').remove();
            updateAffiliationRemoveButtons();
        });

        function updateAffiliationRemoveButtons() {
            $('.remove-affiliation-row').toggle($('.affiliation-row').length > 1);
        }

        // 2. Dynamic Previous Jihadist Action Rows
        let actIndex = 1;
        $('#add-action-row').click(function () {
            const newRow = `
                <tr class="action-row group hover:bg-slate-50/50 transition-all border-t border-slate-50">
                    <td class="p-2">
                        <input type="text" name="previous_jihadist_actions[${actIndex}][activity]" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:ring-1 focus:ring-amber-500 focus:bg-white" placeholder="اسم النشاط / المهمة...">
                    </td>
                    <td class="p-2">
                        <input type="date" name="previous_jihadist_actions[${actIndex}][activity_date]" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:ring-1 focus:ring-amber-500 focus:bg-white">
                    </td>
                    <td class="p-2">
                        <input type="text" name="previous_jihadist_actions[${actIndex}][duration]" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:ring-1 focus:ring-amber-500 focus:bg-white" placeholder="مثل: 6 أشهر">
                    </td>
                    <td class="p-2">
                        <input type="text" name="previous_jihadist_actions[${actIndex}][location]" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:ring-1 focus:ring-amber-500 focus:bg-white" placeholder="المكان / الجبهة">
                    </td>
                    <td class="p-2">
                        <input type="text" name="previous_jihadist_actions[${actIndex}][direct_supervisor]" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:ring-1 focus:ring-amber-500 focus:bg-white" placeholder="المسؤول المباشر">
                    </td>
                    <td class="p-2">
                        <input type="text" name="previous_jihadist_actions[${actIndex}][leaving_reason]" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:ring-1 focus:ring-amber-500 focus:bg-white" placeholder="سبب الترك...">
                    </td>
                    <td class="p-2 text-center">
                        <button type="button" class="remove-action-row text-rose-400 hover:text-rose-600 transition-colors">
                            <i class="fas fa-trash-alt text-xs"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#actions-table tbody').append(newRow);
            actIndex++;
            updateActionRemoveButtons();
        });

        $(document).on('click', '.remove-action-row', function () {
            $(this).closest('tr').remove();
            updateActionRemoveButtons();
        });

        function updateActionRemoveButtons() {
            $('.remove-action-row').toggle($('.action-row').length > 1);
        }

        // 3. Dynamic Weapon Rows
        let weaponIndex = 1;
        $('#add-weapon-row').click(function () {
            const newRow = `
                <tr class="weapon-row group hover:bg-slate-50/50 transition-all border-t border-slate-50">
                    <td class="p-2">
                        <input type="text" name="weapons[${weaponIndex}][weapon_type]" class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:ring-1 focus:ring-emerald-500 focus:bg-white" placeholder="نوع السلاح...">
                    </td>
                    <td class="p-2">
                        <input type="text" name="weapons[${weaponIndex}][number]" class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:ring-1 focus:ring-emerald-500 focus:bg-white" placeholder="الرقم">
                    </td>
                    <td class="p-2">
                        <input type="text" name="weapons[${weaponIndex}][ownership]" class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:ring-1 focus:ring-emerald-500 focus:bg-white" placeholder="الملكية">
                    </td>
                    <td class="p-2">
                        <input type="text" name="weapons[${weaponIndex}][tracking]" class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:ring-1 focus:ring-emerald-500 focus:bg-white" placeholder="التعقب">
                    </td>
                    <td class="p-2 text-center">
                        <button type="button" class="remove-weapon-row text-rose-400 hover:text-rose-600 transition-colors">
                            <i class="fas fa-trash-alt text-xs"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#weapons-table tbody').append(newRow);
            weaponIndex++;
            updateWeaponRemoveButtons();
        });

        $(document).on('click', '.remove-weapon-row', function () {
            $(this).closest('tr').remove();
            updateWeaponRemoveButtons();
        });

        function updateWeaponRemoveButtons() {
            $('.remove-weapon-row').toggle($('.weapon-row').length > 1);
        }
    });
</script>
@endpush
@endsection