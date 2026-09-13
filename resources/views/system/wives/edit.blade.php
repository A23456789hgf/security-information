@extends('layouts.app')

@section('title', 'تعديل بيانات الزوجة')
@section('page_title', 'السجلات الأساسية')

@section('content')
<div class="max-w-5xl mx-auto space-y-6" dir="rtl">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white flex items-center gap-3">
                <div class="p-2.5 bg-amber-500/10 text-amber-600 dark:text-amber-400 rounded-xl">
                    <i class="fas fa-edit text-xl"></i>
                </div>
                تعديل بيانات الزوجة
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                تحديث معلومات الزوجة: <span class="text-brand-600 dark:text-brand-400 font-bold">{{ $wife->name }}</span>
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('system.wives.show', $wife->id) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-sm font-semibold rounded-xl transition-all shadow-sm">
                <i class="fas fa-eye"></i>
                عرض الملف
            </a>
            <a href="{{ route('system.wives.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-sm font-semibold rounded-xl transition-all shadow-sm">
                <i class="fas fa-arrow-right"></i>
                عودة للقائمة
            </a>
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

    <form action="{{ route('system.wives.update', $wife->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Basic Information --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
            <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                <span class="w-2 h-5 bg-amber-500 rounded-full"></span>
                البيانات الأساسية
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                {{-- Husband Selection --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">الفرد / الزوج <span class="text-rose-500">*</span></label>
                    <select id="personal_data_id" name="personal_data_id" required
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all cursor-pointer @error('personal_data_id') border-rose-500 @enderror">
                        <option value="">-- اختر الزوج --</option>
                        @foreach($personalDataList as $id => $name)
                            <option value="{{ $id }}" {{ (old('personal_data_id') ?? $wife->personal_data_id) == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                    @error('personal_data_id')
                        <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Person Preview --}}
                <div id="husband_details_container" class="md:col-span-2 hidden">
                    <div class="bg-slate-50 dark:bg-slate-950/50 border border-slate-200 dark:border-slate-800 rounded-xl p-5 space-y-4">
                        {{-- Photo & Main Info Header --}}
                        <div class="flex items-center gap-3.5 p-2.5 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800">
                            <div class="w-16 h-16 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shrink-0 flex items-center justify-center shadow-xs">
                                <img id="h_photo" src="" alt="الصورة الشخصية" class="w-full h-full object-cover hidden">
                                <i id="h_photo_placeholder" class="fas fa-user text-slate-400 dark:text-slate-500 text-2xl"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1 flex items-center gap-1.5">
                                    <i class="fas fa-info-circle text-brand-600 dark:text-brand-400"></i>
                                    تفاصيل الفرد المختار (الزوج)
                                </h3>
                                <div id="h_person_name" class="font-bold text-slate-800 dark:text-slate-200 text-sm truncate"></div>
                                <div id="h_person_sub" class="text-xs text-slate-400 dark:text-slate-500 truncate"></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 text-xs">
                            <div class="bg-white dark:bg-slate-900 p-3 rounded-2xl border border-slate-200 dark:border-slate-800">
                                <span class="text-slate-400 block mb-0.5">الاسم الجهادي:</span>
                                <span id="h_jihadi_name" class="font-bold text-slate-800 dark:text-slate-200">-</span>
                            </div>
                            <div class="bg-white dark:bg-slate-900 p-3 rounded-2xl border border-slate-200 dark:border-slate-800">
                                <span class="text-slate-400 block mb-0.5">الرقم العسكري:</span>
                                <span id="h_military_number" class="font-bold text-slate-800 dark:text-slate-200">-</span>
                            </div>
                            <div class="bg-white dark:bg-slate-900 p-3 rounded-2xl border border-slate-200 dark:border-slate-800">
                                <span class="text-slate-400 block mb-0.5">فصيلة الدم:</span>
                                <span id="h_blood_type" class="font-bold text-rose-600 dark:text-rose-400">-</span>
                            </div>
                            <div class="bg-white dark:bg-slate-900 p-3 rounded-2xl border border-slate-200 dark:border-slate-800">
                                <span class="text-slate-400 block mb-0.5">تاريخ الميلاد:</span>
                                <span id="h_dob" class="font-bold text-slate-800 dark:text-slate-200">-</span>
                            </div>
                            <div class="bg-white dark:bg-slate-900 p-3 rounded-2xl border border-slate-200 dark:border-slate-800">
                                <span class="text-slate-400 block mb-0.5">الحالة الاجتماعية:</span>
                                <span id="h_marital" class="font-bold text-slate-800 dark:text-slate-200">-</span>
                            </div>
                            <div class="bg-white dark:bg-slate-900 p-3 rounded-2xl border border-slate-200 dark:border-slate-800">
                                <span class="text-slate-400 block mb-0.5">مكان الميلاد:</span>
                                <span id="h_birth_place" class="font-bold text-slate-800 dark:text-slate-200">-</span>
                            </div>
                            <div class="bg-white dark:bg-slate-900 p-3 rounded-2xl border border-slate-200 dark:border-slate-800">
                                <span class="text-slate-400 block mb-0.5">رقم الهوية:</span>
                                <span id="h_national_id" class="font-bold text-slate-800 dark:text-slate-200">-</span>
                            </div>
                            <div class="bg-white dark:bg-slate-900 p-3 rounded-2xl border border-slate-200 dark:border-slate-800">
                                <span class="text-slate-400 block mb-0.5">هاتف 1:</span>
                                <span id="h_phone1" class="font-bold text-slate-800 dark:text-slate-200">-</span>
                            </div>
                            <div class="bg-white dark:bg-slate-900 p-3 rounded-2xl border border-slate-200 dark:border-slate-800">
                                <span class="text-slate-400 block mb-0.5">هاتف 2:</span>
                                <span id="h_phone2" class="font-bold text-slate-800 dark:text-slate-200">-</span>
                            </div>
                            <div class="bg-white dark:bg-slate-900 p-3 rounded-2xl border border-slate-200 dark:border-slate-800 sm:col-span-2 md:col-span-3">
                                <span class="text-slate-400 block mb-0.5">العنوان:</span>
                                <span id="h_address" class="font-bold text-slate-800 dark:text-slate-200">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">اسم الزوجة <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" required
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all @error('name') border-rose-500 @enderror"
                        placeholder="الاسم الكامل للزوجة..." value="{{ old('name') ?? $wife->name }}">
                    @error('name')
                        <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">الحالة <span class="text-rose-500">*</span></label>
                    <select name="status" required
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all cursor-pointer">
                        <option value="مستمرة" {{ (old('status') ?? $wife->status) == 'مستمرة' ? 'selected' : '' }}>مستمرة</option>
                        <option value="متوفية" {{ (old('status') ?? $wife->status) == 'متوفية' ? 'selected' : '' }}>متوفية</option>
                        <option value="مطلقة" {{ (old('status') ?? $wife->status) == 'مطلقة' ? 'selected' : '' }}>مطلقة</option>
                        <option value="أخرى" {{ (old('status') ?? $wife->status) == 'أخرى' ? 'selected' : '' }}>أخرى</option>
                    </select>
                    @error('status')
                        <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">رقم الهوية</label>
                    <input type="text" name="wife_id_number"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-mono font-medium text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all @error('wife_id_number') border-rose-500 @enderror"
                        placeholder="أدخل رقم الهوية" value="{{ old('wife_id_number') ?? $wife->wife_id_number }}">
                    @error('wife_id_number')
                        <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">تاريخ الميلاد</label>
                    <input type="date" name="wife_birth_date"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-medium text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all @error('wife_birth_date') border-rose-500 @enderror"
                        value="{{ old('wife_birth_date') ?? $wife->wife_birth_date }}">
                    @error('wife_birth_date')
                        <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">رقم الهاتف</label>
                    <input type="text" name="wife_phone"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-mono font-medium text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all @error('wife_phone') border-rose-500 @enderror"
                        placeholder="أدخل رقم الهاتف" value="{{ old('wife_phone') ?? $wife->wife_phone }}">
                    @error('wife_phone')
                        <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Geographic Information --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
            <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                <span class="w-2 h-5 bg-emerald-500 rounded-full"></span>
                معلومات الموقع الجغرافي
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">المحافظة</label>
                    <select id="governorate_id" name="governorate_id"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all cursor-pointer">
                        <option value="">-- اختر المحافظة --</option>
                        @foreach($governorates as $id => $name)
                            <option value="{{ $id }}" {{ (old('governorate_id') ?? $wife->governorate_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">المديرية</label>
                    <select id="directorate_id" name="directorate_id" data-selected="{{ old('directorate_id') ?? $wife->directorate_id }}"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all cursor-pointer">
                        <option value="">-- اختر المديرية --</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">العزلة / المنطقة</label>
                    <select id="sub_area_id" name="sub_area_id" data-selected="{{ old('sub_area_id') ?? $wife->sub_area_id }}"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all cursor-pointer">
                        <option value="">-- اختر المنطقة --</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">القرية / الحي</label>
                    <select id="village_id" name="village_id" data-selected="{{ old('village_id') ?? $wife->village_id }}"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all cursor-pointer">
                        <option value="">-- اختر القرية / الحي --</option>
                    </select>
                </div>
        </div>

        {{-- Add New Wives (Optional) --}}
        <div class="mt-8 pt-8 border-t border-slate-200 dark:border-slate-800">
            <h2 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2 mb-6">
                <span class="w-2 h-6 bg-emerald-500 rounded-full"></span>
                إضافة زوجات أخرى (اختياري)
            </h2>
            <div id="new-wives-container" class="space-y-6">
                {{-- Templates will be appended here --}}
            </div>
            
            <div class="flex justify-center mt-6">
                <button type="button" id="add-new-wife-btn"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-brand-50 hover:bg-brand-100 text-brand-700 dark:bg-brand-500/10 dark:hover:bg-brand-500/20 dark:text-brand-400 rounded-2xl text-sm font-bold border border-brand-200 dark:border-brand-500/20 transition-all border-dashed hover:border-solid">
                    <i class="fas fa-plus"></i>
                    إضافة زوجة جديدة
                </button>
            </div>
        </div>
        
        {{-- Template for New Wife --}}
        <template id="new-wife-template">
            <div class="wife-entry bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm relative transition-all">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                        <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="w-2 h-5 bg-brand-500 rounded-full"></span>
                            بيانات الزوجة <span class="wife-counter text-brand-600 dark:text-brand-400">#</span>
                        </h2>
                        <button type="button" class="remove-wife-btn text-rose-500 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950 p-2 rounded-xl text-sm transition-colors flex items-center gap-2">
                            <i class="fas fa-trash-alt"></i> إزالة
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">اسم الزوجة <span class="text-rose-500">*</span></label>
                            <input type="text" name="new_wives[INDEX][name]" required
                                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all"
                                placeholder="الاسم الكامل للزوجة...">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">الحالة <span class="text-rose-500">*</span></label>
                            <select name="new_wives[INDEX][status]" required
                                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all cursor-pointer">
                                <option value="مستمرة">مستمرة</option>
                                <option value="متوفية">متوفية</option>
                                <option value="مطلقة">مطلقة</option>
                                <option value="أخرى">أخرى</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">رقم الهوية</label>
                            <input type="text" name="new_wives[INDEX][wife_id_number]"
                                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-mono font-medium text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all"
                                placeholder="أدخل رقم الهوية">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">تاريخ الميلاد</label>
                            <input type="date" name="new_wives[INDEX][wife_birth_date]"
                                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-medium text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">رقم الهاتف</label>
                            <input type="text" name="new_wives[INDEX][wife_phone]"
                                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-mono font-medium text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all"
                                placeholder="أدخل رقم الهاتف">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">العنوان التفصيلي</label>
                            <input type="text" name="new_wives[INDEX][wife_address]"
                                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all"
                                placeholder="العنوان...">
                        </div>
                    </div>

                    <div class="bg-slate-50/50 dark:bg-slate-950/30 rounded-xl p-4 border border-slate-100 dark:border-slate-800">
                        <h3 class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-4 flex items-center gap-2"><i class="fas fa-map-marker-alt text-emerald-500"></i> معلومات الموقع الجغرافي</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-[10px] uppercase tracking-wider font-bold text-slate-500 mb-1.5">المحافظة</label>
                                <select name="new_wives[INDEX][governorate_id]" class="gov-select w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-semibold focus:ring-1 focus:ring-emerald-500 transition-all cursor-pointer">
                                    <option value="">-- اختر المحافظة --</option>
                                    @foreach($governorates as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] uppercase tracking-wider font-bold text-slate-500 mb-1.5">المديرية</label>
                                <select name="new_wives[INDEX][directorate_id]" class="dir-select w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-semibold focus:ring-1 focus:ring-emerald-500 transition-all cursor-pointer">
                                    <option value="">-- اختر المديرية --</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] uppercase tracking-wider font-bold text-slate-500 mb-1.5">المنطقة</label>
                                <select name="new_wives[INDEX][sub_area_id]" class="sub-select w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-semibold focus:ring-1 focus:ring-emerald-500 transition-all cursor-pointer">
                                    <option value="">-- اختر المنطقة --</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] uppercase tracking-wider font-bold text-slate-500 mb-1.5">القرية</label>
                                <select name="new_wives[INDEX][village_id]" class="vil-select w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-semibold focus:ring-1 focus:ring-emerald-500 transition-all cursor-pointer">
                                    <option value="">-- اختر القرية --</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        {{-- Form Actions --}}
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-800">
            <a href="{{ route('system.wives.show', $wife->id) }}"
                class="px-5 py-2.5 text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:text-slate-200 dark:hover:text-slate-200 transition-colors">
                إلغاء
            </a>
            <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-sm font-semibold shadow-sm hover:shadow transition-all">
                <i class="fas fa-check"></i>
                حفظ التعديلات
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const husbandSelect = document.getElementById('personal_data_id');
        const husbandContainer = document.getElementById('husband_details_container');

        if (husbandSelect) {
            husbandSelect.addEventListener('change', function () {
                const husbandId = this.value;
                if (!husbandId) {
                    husbandContainer.classList.add('hidden');
                    return;
                }

                fetch(`/system/wives/person-details/${husbandId}`)
                    .then(res => res.json())
                    .then(data => {
                        const photoEl = document.getElementById('h_photo');
                        const placeholderEl = document.getElementById('h_photo_placeholder');
                        if (data.photo_url) {
                            photoEl.src = data.photo_url;
                            photoEl.classList.remove('hidden');
                            placeholderEl.classList.add('hidden');
                        } else {
                            photoEl.src = '';
                            photoEl.classList.add('hidden');
                            placeholderEl.classList.remove('hidden');
                        }

                        document.getElementById('h_person_name').textContent = data.name || '-';
                        document.getElementById('h_person_sub').textContent = (data.military_number && data.military_number !== '-' ? 'الرقم العسكري: ' + data.military_number : '') + (data.jihadi_name && data.jihadi_name !== '-' ? ' | ' + data.jihadi_name : '');
                        document.getElementById('h_jihadi_name').textContent = data.jihadi_name || '-';
                        document.getElementById('h_military_number').textContent = data.military_number || '-';
                        document.getElementById('h_national_id').textContent = data.national_id_number || '-';
                        document.getElementById('h_blood_type').textContent = data.blood_type || '-';
                        document.getElementById('h_dob').textContent = data.date_of_birth || '-';
                        document.getElementById('h_marital').textContent = data.marital_status || '-';
                        document.getElementById('h_birth_place').textContent = data.place_of_birth || '-';
                        document.getElementById('h_phone1').textContent = data.phone_1 || '-';
                        document.getElementById('h_phone2').textContent = data.phone_2 || '-';
                        document.getElementById('h_address').textContent = data.address || '-';
                        
                        husbandContainer.classList.remove('hidden');
                    })
                    .catch(err => {
                        console.error('Error fetching husband details:', err);
                        husbandContainer.classList.add('hidden');
                    });
            });

            if (husbandSelect.value) {
                husbandSelect.dispatchEvent(new Event('change'));
            }
        }

        // Robust Cascading
        const govEl = document.getElementById('governorate_id');
        const dirEl = document.getElementById('directorate_id');
        const subEl = document.getElementById('sub_area_id');
        const vilEl = document.getElementById('village_id');

        const load = (url, targetEl, placeholder, nextSelected) => {
            return fetch(url).then(r => r.json()).then(data => {
                targetEl.innerHTML = `<option value="">${placeholder}</option>`;
                Object.keys(data).forEach(id => {
                    const opt = document.createElement('option');
                    opt.value = id;
                    opt.textContent = data[id];
                    if (id == nextSelected) opt.selected = true;
                    targetEl.appendChild(opt);
                });
            });
        };

        if (govEl) {
            govEl.addEventListener('change', function() {
                const id = this.value;
                if (!id) return;
                dirEl.innerHTML = '<option>جاري التحميل...</option>';
                load(`/api/geo/directorates/${id}`, dirEl, '-- اختر المديرية --').then(() => dirEl.dispatchEvent(new Event('change')));
            });

            dirEl.addEventListener('change', function() {
                const id = this.value;
                if (!id) { subEl.innerHTML = '<option value="">-- اختر المنطقة --</option>'; return; }
                subEl.innerHTML = '<option>جاري التحميل...</option>';
                load(`/api/geo/sub-areas/${id}`, subEl, '-- اختر المنطقة --').then(() => subEl.dispatchEvent(new Event('change')));
            });

            subEl.addEventListener('change', function() {
                const id = this.value;
                if (!id) { vilEl.innerHTML = '<option value="">-- اختر القرية --</option>'; return; }
                vilEl.innerHTML = '<option>جاري التحميل...</option>';
                load(`/api/geo/villages/${id}`, vilEl, '-- اختر القرية --');
            });

            if (govEl.value) {
                const dSel = dirEl.getAttribute('data-selected');
                const sSel = subEl.getAttribute('data-selected');
                const vSel = vilEl.getAttribute('data-selected');
                
                load(`/api/geo/directorates/${govEl.value}`, dirEl, '-- اختر المديرية --', dSel).then(() => {
                    if (dSel) return load(`/api/geo/sub-areas/${dSel}`, subEl, '-- اختر المنطقة --', sSel);
                }).then(() => {
                    if (sSel) return load(`/api/geo/villages/${sSel}`, vilEl, '-- اختر القرية --', vSel);
                });
            }
        }

        // --- Dynamic New Wives Form ---
        const newWivesContainer = document.getElementById('new-wives-container');
        const addNewWifeBtn = document.getElementById('add-new-wife-btn');
        const template = document.getElementById('new-wife-template');
        let newWifeIndex = 0;

        if (addNewWifeBtn && template) {
            addNewWifeBtn.addEventListener('click', function() {
                const clone = template.content.cloneNode(true);
                const entry = clone.querySelector('.wife-entry');
                
                // Replace INDEX placeholder
                entry.innerHTML = entry.innerHTML.replace(/INDEX/g, newWifeIndex);
                entry.querySelector('.wife-counter').textContent = `#${newWifeIndex + 1}`;
                
                newWivesContainer.appendChild(clone);
                newWifeIndex++;
            });

            newWivesContainer.addEventListener('click', function(e) {
                const removeBtn = e.target.closest('.remove-wife-btn');
                if (removeBtn) {
                    const entry = removeBtn.closest('.wife-entry');
                    entry.remove();
                    
                    // Re-index counters (display only, hidden names stay as they are to avoid re-rendering)
                    newWivesContainer.querySelectorAll('.wife-entry').forEach((el, index) => {
                        el.querySelector('.wife-counter').textContent = `#${index + 1}`;
                    });
                }
            });

            // Event Delegation for Cascading Geography in Dynamic Entries
            newWivesContainer.addEventListener('change', function(e) {
                const target = e.target;
                const entry = target.closest('.wife-entry');
                if (!entry) return;

                const dirEl = entry.querySelector('.dir-select');
                const subEl = entry.querySelector('.sub-select');
                const vilEl = entry.querySelector('.vil-select');

                const loadOptions = (url, tgt, placeholder) => {
                    tgt.disabled = true;
                    tgt.innerHTML = '<option>جاري التحميل...</option>';
                    return fetch(url).then(r => r.json()).then(data => {
                        tgt.innerHTML = `<option value="">${placeholder}</option>`;
                        Object.keys(data).forEach(id => {
                            const opt = document.createElement('option');
                            opt.value = id;
                            opt.textContent = data[id];
                            tgt.appendChild(opt);
                        });
                        tgt.disabled = false;
                    });
                };

                if (target.classList.contains('gov-select')) {
                    const id = target.value;
                    if(dirEl) dirEl.innerHTML = '<option value="">-- اختر المديرية --</option>';
                    if(subEl) subEl.innerHTML = '<option value="">-- اختر المنطقة --</option>';
                    if(vilEl) vilEl.innerHTML = '<option value="">-- اختر القرية --</option>';
                    if (id) loadOptions(`/api/geo/directorates/${id}`, dirEl, '-- اختر المديرية --');
                } else if (target.classList.contains('dir-select')) {
                    const id = target.value;
                    if(subEl) subEl.innerHTML = '<option value="">-- اختر المنطقة --</option>';
                    if(vilEl) vilEl.innerHTML = '<option value="">-- اختر القرية --</option>';
                    if (id) loadOptions(`/api/geo/sub-areas/${id}`, subEl, '-- اختر المنطقة --');
                } else if (target.classList.contains('sub-select')) {
                    const id = target.value;
                    if(vilEl) vilEl.innerHTML = '<option value="">-- اختر القرية --</option>';
                    if (id) loadOptions(`/api/geo/villages/${id}`, vilEl, '-- اختر القرية --');
                }
            });
        }
    });
</script>
@endsection

