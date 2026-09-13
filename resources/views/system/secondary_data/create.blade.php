@extends('layouts.app')

@section('title', 'إضافة بيانات ثانوية')

@section('content')
<div class="container-fluid mx-auto px-4 py-6" dir="rtl">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 max-w-7xl mx-auto">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-slate-800 dark:bg-slate-700 rounded-2xl shadow-md flex items-center justify-center text-white">
                <i class="fas fa-file-medical text-lg"></i>
            </div>
            <div>
                <h2 class="font-display font-bold text-xl text-slate-900 dark:text-white mb-0.5">إضافة بيانات ثانوية</h2>
                <p class="text-slate-500 dark:text-slate-400 text-xs">تسجيل الحالة الصحية، الأولاد، الانتماءات، الأعمال الجهادية، والأسلحة للشخص.</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('system.secondary-data.index') }}"
                class="px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-all shadow-sm flex items-center gap-1.5">
                <i class="fas fa-arrow-right"></i> عودة
            </a>
            <button type="submit" form="secondaryDataForm"
                class="px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-1.5 cursor-pointer">
                <i class="fas fa-save text-xs"></i> حفظ البيانات
            </button>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-900/80 rounded-2xl p-4 mb-6 max-w-7xl mx-auto">
            <div class="flex items-center gap-2 text-rose-700 dark:text-rose-300 mb-2 font-bold text-xs">
                <i class="fas fa-exclamation-triangle"></i>
                <span>يرجى تصحيح الأخطاء التالية:</span>
            </div>
            <ul class="list-disc list-inside text-rose-600 dark:text-rose-400 text-xs space-y-1">
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
                {{-- Person Selection --}}
                <div class="lg:col-span-4">
                    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden h-full">
                        <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-950/50">
                            <h5 class="text-base font-bold text-slate-800 dark:text-slate-100 header-font flex items-center gap-2">
                                <span class="w-1.5 h-5 bg-brand-600 rounded-full"></span>
                                اختيار الشخص
                            </h5>
                        </div>
                        <div class="p-5 space-y-4">
                            <div>
                                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5 block">ابحث عن الشخص واختاره <span class="text-rose-500">*</span></label>
                                <select name="personal_data_id" id="personal_data_id"
                                    class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-brand-500 focus:bg-white dark:focus:bg-slate-900 transition-all select2-selection"
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
                                <div class="bg-slate-50/80 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-xs space-y-2.5">
                                    {{-- Profile Photo & Header --}}
                                    <div class="flex items-center gap-3 p-2 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800">
                                        <div class="w-14 h-14 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shrink-0 flex items-center justify-center shadow-xs">
                                            <img id="info_photo" src="" alt="الصورة الشخصية" class="w-full h-full object-cover hidden">
                                            <i id="info_photo_placeholder" class="fas fa-user text-slate-400 dark:text-slate-500 text-2xl"></i>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <h6 class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase flex items-center gap-1.5 mb-1">
                                                <i class="fas fa-id-card text-brand-600"></i> تفاصيل الفرد المختار
                                            </h6>
                                            <div id="info_person_name" class="font-bold text-slate-800 dark:text-slate-100 text-xs truncate"></div>
                                            <div id="info_person_sub" class="text-[10px] text-slate-400 dark:text-slate-500 truncate"></div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-2 text-[11px]">
                                        <div class="bg-white dark:bg-slate-900 p-2 rounded-xl border border-slate-200 dark:border-slate-800">
                                            <span class="text-slate-400 dark:text-slate-500 block text-[10px]">الاسم الجهادي:</span>
                                            <span id="info_jihadi_name" class="font-bold text-slate-700 dark:text-slate-300">-</span>
                                        </div>
                                        <div class="bg-white dark:bg-slate-900 p-2 rounded-xl border border-slate-200 dark:border-slate-800">
                                            <span class="text-slate-400 dark:text-slate-500 block text-[10px]">الرقم العسكري:</span>
                                            <span id="info_military_number" class="font-bold text-slate-700 dark:text-slate-300">-</span>
                                        </div>
                                        <div class="bg-white dark:bg-slate-900 p-2 rounded-xl border border-slate-200 dark:border-slate-800">
                                            <span class="text-slate-400 dark:text-slate-500 block text-[10px]">فصيلة الدم:</span>
                                            <span id="info_blood_type" class="font-extrabold text-rose-600 dark:text-rose-400">-</span>
                                        </div>
                                        <div class="bg-white dark:bg-slate-900 p-2 rounded-xl border border-slate-200 dark:border-slate-800">
                                            <span class="text-slate-400 dark:text-slate-500 block text-[10px]">الحالة الاجتماعية:</span>
                                            <span id="info_marital_status" class="font-bold text-slate-700 dark:text-slate-300">-</span>
                                        </div>
                                        <div class="bg-white dark:bg-slate-900 p-2 rounded-xl border border-slate-200 dark:border-slate-800 col-span-2">
                                            <span class="text-slate-400 dark:text-slate-500 block text-[10px]">العنوان:</span>
                                            <span id="info_address" class="font-bold text-slate-700 dark:text-slate-300 truncate block">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-2 border-t border-slate-200 dark:border-slate-800 space-y-3">
                                <div>
                                    <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1 block">عام الانطلاقة / الالتحاق</label>
                                    <input type="number" min="1900" max="2099" name="start_date"
                                        class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-brand-500 focus:bg-white dark:focus:bg-slate-900 transition-all"
                                        value="{{ old('start_date') }}" placeholder="العام (مثال: 2024)">
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1 block">المسؤول المباشر</label>
                                    <input type="text" name="direct_supervisor"
                                        class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-brand-500 focus:bg-white dark:focus:bg-slate-900 transition-all"
                                        value="{{ old('direct_supervisor') }}" placeholder="اسم المسؤول المباشر">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Health Status, Children, & Activity --}}
                <div class="lg:col-span-8">
                    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden h-full">
                        <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-950/50">
                            <h5 class="text-base font-bold text-slate-800 dark:text-slate-100 header-font flex items-center gap-2">
                                <span class="w-1.5 h-5 bg-teal-500 rounded-full"></span>
                                الحالة الصحية والبيانات الأسرية والجهادية
                            </h5>
                        </div>
                        <div class="p-5 space-y-5">
                            {{-- Health Status & Children Row --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-slate-50/70 dark:bg-slate-950/50 rounded-2xl border border-slate-200 dark:border-slate-800">
                                {{-- Health Status Dropdown --}}
                                <div>
                                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 block flex items-center gap-1.5">
                                        <i class="fas fa-heartbeat text-rose-500"></i> الحالة الصحية
                                    </label>
                                    <select name="health_status_id" id="health_status_id"
                                        class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-teal-500 transition-all">
                                        <option value="">-- اختر الحالة الصحية --</option>
                                        @foreach($healthStatuses as $id => $name)
                                            <option value="{{ $id }}" {{ old('health_status_id') == $id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Children Count --}}
                                <div>
                                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 block flex items-center gap-1.5">
                                        <i class="fas fa-users text-purple-600 dark:text-purple-400"></i> عدد الأولاد
                                    </label>
                                    <input type="number" name="total_children_count" id="total_children_count" min="0" max="100"
                                        class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-bold text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-purple-500 transition-all"
                                        value="{{ old('total_children_count') }}" placeholder="اختياري (0-100)">
                                </div>
                            </div>

                            {{-- Jihadist Activity Textareas --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5 block">النشاط الجهادي الحالي</label>
                                    <textarea name="current_jihadist_activity" rows="3"
                                        class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-brand-500 focus:bg-white dark:focus:bg-slate-900 transition-all"
                                        placeholder="وصف النشاط والمهمة الحالية...">{{ old('current_jihadist_activity') }}</textarea>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5 block">ملاحظات النشاط الجهادي السابق</label>
                                    <textarea name="previous_jihadist_activity" rows="3"
                                        class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-brand-500 focus:bg-white dark:focus:bg-slate-900 transition-all"
                                        placeholder="ملاحظات عامة حول النشاط السابق...">{{ old('previous_jihadist_activity') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Master-Detail Section 1: Political & Ideological Affiliations --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-950/50 flex flex-wrap justify-between items-center gap-2">
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-5 bg-indigo-600 rounded-full"></span>
                        <h5 class="text-base font-bold text-slate-800 dark:text-slate-100 header-font">الانتماء السياسي والعقائدي</h5>
                        <span class="text-slate-400 dark:text-slate-500 text-xs font-normal">(جدول متعدد السجلات)</span>
                    </div>
                    <button type="button" id="add-affiliation-row"
                        class="px-3.5 py-1.5 bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800/80 rounded-xl font-bold text-xs hover:bg-indigo-100 dark:hover:bg-indigo-900/60 transition-all flex items-center gap-1.5 shadow-sm cursor-pointer">
                        <i class="fas fa-plus text-xs"></i> إضافة انتماء
                    </button>
                </div>
                <div class="p-0">
                    <div class="overflow-x-auto">
                        <table class="w-full text-right text-xs" id="affiliations-table">
                            <thead class="bg-slate-50/80 dark:bg-slate-950/60 text-[11px] font-bold text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                                <tr>
                                    <th class="px-3 py-3 min-w-[130px]">الجهة</th>
                                    <th class="px-3 py-3 min-w-[150px]">الانتماء العقائدي</th>
                                    <th class="px-3 py-3 min-w-[150px]">الانتماء السياسي</th>
                                    <th class="px-3 py-3 min-w-[130px]">عام الارتباط</th>
                                    <th class="px-3 py-3 min-w-[110px]">مدة الانتماء</th>
                                    <th class="px-3 py-3 min-w-[130px]">مكان العمل</th>
                                    <th class="px-3 py-3 min-w-[120px]">نوع العمل</th>
                                    <th class="px-3 py-3 min-w-[130px]">المسؤول المباشر</th>
                                    <th class="px-3 py-3 min-w-[130px]">عام الترك</th>
                                    <th class="px-3 py-3 min-w-[150px]">سبب الترك</th>
                                    <th class="px-3 py-3 w-10 text-center"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                                <tr class="affiliation-row group hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-all">
                                    <td class="p-2">
                                        <input type="text" name="affiliations[0][party]"
                                            class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900"
                                            placeholder="الجهة...">
                                    </td>
                                    <td class="p-2">
                                        <select name="affiliations[0][ideological_affiliation_id]"
                                            class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900">
                                            <option value="">-- اختر --</option>
                                            @foreach($ideologicalAffiliations as $id => $name)
                                                <option value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="p-2">
                                        <select name="affiliations[0][political_affiliation_id]"
                                            class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900">
                                            <option value="">-- اختر --</option>
                                            @foreach($politicalAffiliations as $id => $name)
                                                <option value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="p-2">
                                        <input type="number" min="1900" max="2099" name="affiliations[0][affiliation_date]"
                                            class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900"
                                            placeholder="العام (2024)">
                                    </td>
                                    <td class="p-2">
                                        <input type="text" name="affiliations[0][duration]"
                                            class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900"
                                            placeholder="مثل: سنتين">
                                    </td>
                                    <td class="p-2">
                                        <input type="text" name="affiliations[0][workplace]"
                                            class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900"
                                            placeholder="مكان العمل">
                                    </td>
                                    <td class="p-2">
                                        <input type="text" name="affiliations[0][job_type]"
                                            class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900"
                                            placeholder="نوع العمل">
                                    </td>
                                    <td class="p-2">
                                        <input type="text" name="affiliations[0][direct_supervisor]"
                                            class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900"
                                            placeholder="المسؤول المباشر">
                                    </td>
                                    <td class="p-2">
                                        <input type="number" min="1900" max="2099" name="affiliations[0][leaving_date]"
                                            class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900"
                                            placeholder="العام (2024)">
                                    </td>
                                    <td class="p-2">
                                        <input type="text" name="affiliations[0][leaving_reason]"
                                            class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900"
                                            placeholder="سبب الترك...">
                                    </td>
                                    <td class="p-2 text-center">
                                        <button type="button" class="remove-affiliation-row text-rose-400 hover:text-rose-600 transition-colors hidden cursor-pointer">
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
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-950/50 flex flex-wrap justify-between items-center gap-2">
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-5 bg-amber-500 rounded-full"></span>
                        <h5 class="text-base font-bold text-slate-800 dark:text-slate-100 header-font">الأعمال الجهادية السابقة</h5>
                        <span class="text-slate-400 dark:text-slate-500 text-xs font-normal">(جدول تفصيلي للأنشطة السابقة)</span>
                    </div>
                    <button type="button" id="add-action-row"
                        class="px-3.5 py-1.5 bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800/80 rounded-xl font-bold text-xs hover:bg-amber-100 dark:hover:bg-amber-900/60 transition-all flex items-center gap-1.5 shadow-sm cursor-pointer">
                        <i class="fas fa-plus text-xs"></i> إضافة عمل جهادي
                    </button>
                </div>
                <div class="p-0">
                    <div class="overflow-x-auto">
                        <table class="w-full text-right text-xs" id="actions-table">
                            <thead class="bg-slate-50/80 dark:bg-slate-950/60 text-[11px] font-bold text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                                <tr>
                                    <th class="px-3 py-3 min-w-[200px]">النشاط</th>
                                    <th class="px-3 py-3 min-w-[140px]">العام</th>
                                    <th class="px-3 py-3 min-w-[130px]">المدة الزمنية</th>
                                    <th class="px-3 py-3 min-w-[150px]">المكان</th>
                                    <th class="px-3 py-3 min-w-[150px]">المسؤول المباشر</th>
                                    <th class="px-3 py-3 min-w-[180px]">سبب الترك</th>
                                    <th class="px-3 py-3 w-10 text-center"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                                <tr class="action-row group hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-all">
                                    <td class="p-2">
                                        <input type="text" name="previous_jihadist_actions[0][activity]"
                                            class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-amber-500 focus:bg-white dark:focus:bg-slate-900"
                                            placeholder="اسم النشاط / المهمة...">
                                    </td>
                                    <td class="p-2">
                                        <input type="number" min="1900" max="2099" name="previous_jihadist_actions[0][activity_date]"
                                            class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-amber-500 focus:bg-white dark:focus:bg-slate-900"
                                            placeholder="العام (2024)">
                                    </td>
                                    <td class="p-2">
                                        <input type="text" name="previous_jihadist_actions[0][duration]"
                                            class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-amber-500 focus:bg-white dark:focus:bg-slate-900"
                                            placeholder="مثل: 6 أشهر">
                                    </td>
                                    <td class="p-2">
                                        <input type="text" name="previous_jihadist_actions[0][location]"
                                            class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-amber-500 focus:bg-white dark:focus:bg-slate-900"
                                            placeholder="المكان / الجبهة">
                                    </td>
                                    <td class="p-2">
                                        <input type="text" name="previous_jihadist_actions[0][direct_supervisor]"
                                            class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-amber-500 focus:bg-white dark:focus:bg-slate-900"
                                            placeholder="المسؤول المباشر">
                                    </td>
                                    <td class="p-2">
                                        <input type="text" name="previous_jihadist_actions[0][leaving_reason]"
                                            class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-amber-500 focus:bg-white dark:focus:bg-slate-900"
                                            placeholder="سبب الترك...">
                                    </td>
                                    <td class="p-2 text-center">
                                        <button type="button" class="remove-action-row text-rose-400 hover:text-rose-600 transition-colors hidden cursor-pointer">
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
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-950/50 flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-5 bg-emerald-500 rounded-full"></span>
                        <h5 class="text-base font-bold text-slate-800 dark:text-slate-100 header-font">بيانات الأسلحة والعهدة</h5>
                    </div>
                    <button type="button" id="add-weapon-row"
                        class="px-3.5 py-1.5 bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/80 rounded-xl font-bold text-xs hover:bg-emerald-100 dark:hover:bg-emerald-900/60 transition-all flex items-center gap-1.5 shadow-sm cursor-pointer">
                        <i class="fas fa-plus text-xs"></i> إضافة سلاح
                    </button>
                </div>
                <div class="p-0">
                    <div class="overflow-x-auto">
                        <table class="w-full text-right text-xs" id="weapons-table">
                            <thead class="bg-slate-50/80 dark:bg-slate-950/60 text-[11px] font-bold text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                                <tr>
                                    <th class="px-4 py-3 min-w-[200px]">نوع السلاح</th>
                                    <th class="px-4 py-3 min-w-[150px]">الرقم</th>
                                    <th class="px-4 py-3 min-w-[150px]">الملكية</th>
                                    <th class="px-4 py-3 min-w-[150px]">التعقب</th>
                                    <th class="px-4 py-3 w-10 text-center"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                                <tr class="weapon-row group hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-all">
                                    <td class="p-2">
                                        <input type="text" name="weapons[0][weapon_type]"
                                            class="w-full px-3 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-emerald-500 focus:bg-white dark:focus:bg-slate-900"
                                            placeholder="نوع السلاح...">
                                    </td>
                                    <td class="p-2">
                                        <input type="text" name="weapons[0][number]"
                                            class="w-full px-3 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-emerald-500 focus:bg-white dark:focus:bg-slate-900"
                                            placeholder="الرقم">
                                    </td>
                                    <td class="p-2">
                                        <input type="text" name="weapons[0][ownership]"
                                            class="w-full px-3 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-emerald-500 focus:bg-white dark:focus:bg-slate-900"
                                            placeholder="الملكية">
                                    </td>
                                    <td class="p-2">
                                        <input type="text" name="weapons[0][tracking]"
                                            class="w-full px-3 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-emerald-500 focus:bg-white dark:focus:bg-slate-900"
                                            placeholder="التعقب">
                                    </td>
                                    <td class="p-2 text-center">
                                        <button type="button" class="remove-weapon-row text-rose-400 hover:text-rose-600 transition-colors hidden cursor-pointer">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

@push('scripts')
<script>
    $(document).ready(function () {
        // Select2 initialization
        if ($.fn.select2) {
            $('.select2-selection, #health_status_id, select[name*="[ideological_affiliation_id]"], select[name*="[political_affiliation_id]"]').select2({
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
                    if (data.photo_url) {
                        $('#info_photo').attr('src', data.photo_url).removeClass('hidden');
                        $('#info_photo_placeholder').addClass('hidden');
                    } else {
                        $('#info_photo').attr('src', '').addClass('hidden');
                        $('#info_photo_placeholder').removeClass('hidden');
                    }
                    $('#info_person_name').text(data.name || '-');
                    $('#info_person_sub').text((data.military_number && data.military_number !== '-' ? 'الرقم العسكري: ' + data.military_number : '') + (data.jihadi_name && data.jihadi_name !== '-' ? ' | ' + data.jihadi_name : ''));
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

        // 1. Dynamic Affiliation Rows
        let affIndex = 1;
        const ideologicalOptions = `@foreach($ideologicalAffiliations as $id => $name)<option value="{{ $id }}">{{ $name }}</option>@endforeach`;
        const politicalOptions = `@foreach($politicalAffiliations as $id => $name)<option value="{{ $id }}">{{ $name }}</option>@endforeach`;

        $('#add-affiliation-row').click(function () {
            const newRow = `
                <tr class="affiliation-row group hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-all border-t border-slate-100 dark:border-slate-800">
                    <td class="p-2">
                        <input type="text" name="affiliations[${affIndex}][party]" class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900" placeholder="الجهة...">
                    </td>
                    <td class="p-2">
                        <select name="affiliations[${affIndex}][ideological_affiliation_id]" class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900">
                            <option value="">-- اختر أو اكتب --</option>
                            ${ideologicalOptions}
                        </select>
                    </td>
                    <td class="p-2">
                        <select name="affiliations[${affIndex}][political_affiliation_id]" class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900">
                            <option value="">-- اختر أو اكتب --</option>
                            ${politicalOptions}
                        </select>
                    </td>
                    <td class="p-2">
                        <input type="number" min="1900" max="2099" name="affiliations[${affIndex}][affiliation_date]" class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900" placeholder="العام (2024)">
                    </td>
                    <td class="p-2">
                        <input type="text" name="affiliations[${affIndex}][duration]" class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900" placeholder="مثل: سنتين">
                    </td>
                    <td class="p-2">
                        <input type="text" name="affiliations[${affIndex}][workplace]" class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900" placeholder="مكان العمل">
                    </td>
                    <td class="p-2">
                        <input type="text" name="affiliations[${affIndex}][job_type]" class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900" placeholder="نوع العمل">
                    </td>
                    <td class="p-2">
                        <input type="text" name="affiliations[${affIndex}][direct_supervisor]" class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900" placeholder="المسؤول المباشر">
                    </td>
                    <td class="p-2">
                        <input type="number" min="1900" max="2099" name="affiliations[${affIndex}][leaving_date]" class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900" placeholder="العام (2024)">
                    </td>
                    <td class="p-2">
                        <input type="text" name="affiliations[${affIndex}][leaving_reason]" class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900" placeholder="سبب الترك...">
                    </td>
                    <td class="p-2 text-center">
                        <button type="button" class="remove-affiliation-row text-rose-400 hover:text-rose-600 transition-colors cursor-pointer">
                            <i class="fas fa-trash-alt text-xs"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#affiliations-table tbody').append(newRow);
            if ($.fn.select2) {
                $('#affiliations-table tr:last-child select').select2({
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
            }
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
                <tr class="action-row group hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-all border-t border-slate-100 dark:border-slate-800">
                    <td class="p-2">
                        <input type="text" name="previous_jihadist_actions[${actIndex}][activity]" class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-amber-500 focus:bg-white dark:focus:bg-slate-900" placeholder="اسم النشاط / المهمة...">
                    </td>
                    <td class="p-2">
                        <input type="number" min="1900" max="2099" name="previous_jihadist_actions[${actIndex}][activity_date]" class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-amber-500 focus:bg-white dark:focus:bg-slate-900" placeholder="العام (2024)">
                    </td>
                    <td class="p-2">
                        <input type="text" name="previous_jihadist_actions[${actIndex}][duration]" class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-amber-500 focus:bg-white dark:focus:bg-slate-900" placeholder="مثل: 6 أشهر">
                    </td>
                    <td class="p-2">
                        <input type="text" name="previous_jihadist_actions[${actIndex}][location]" class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-amber-500 focus:bg-white dark:focus:bg-slate-900" placeholder="المكان / الجبهة">
                    </td>
                    <td class="p-2">
                        <input type="text" name="previous_jihadist_actions[${actIndex}][direct_supervisor]" class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-amber-500 focus:bg-white dark:focus:bg-slate-900" placeholder="المسؤول المباشر">
                    </td>
                    <td class="p-2">
                        <input type="text" name="previous_jihadist_actions[${actIndex}][leaving_reason]" class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-amber-500 focus:bg-white dark:focus:bg-slate-900" placeholder="سبب الترك...">
                    </td>
                    <td class="p-2 text-center">
                        <button type="button" class="remove-action-row text-rose-400 hover:text-rose-600 transition-colors cursor-pointer">
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
                <tr class="weapon-row group hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-all border-t border-slate-100 dark:border-slate-800">
                    <td class="p-2">
                        <input type="text" name="weapons[${weaponIndex}][weapon_type]" class="w-full px-3 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-emerald-500 focus:bg-white dark:focus:bg-slate-900" placeholder="نوع السلاح...">
                    </td>
                    <td class="p-2">
                        <input type="text" name="weapons[${weaponIndex}][number]" class="w-full px-3 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-emerald-500 focus:bg-white dark:focus:bg-slate-900" placeholder="الرقم">
                    </td>
                    <td class="p-2">
                        <input type="text" name="weapons[${weaponIndex}][ownership]" class="w-full px-3 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-emerald-500 focus:bg-white dark:focus:bg-slate-900" placeholder="الملكية">
                    </td>
                    <td class="p-2">
                        <input type="text" name="weapons[${weaponIndex}][tracking]" class="w-full px-3 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-emerald-500 focus:bg-white dark:focus:bg-slate-900" placeholder="التعقب">
                    </td>
                    <td class="p-2 text-center">
                        <button type="button" class="remove-weapon-row text-rose-400 hover:text-rose-600 transition-colors cursor-pointer">
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
