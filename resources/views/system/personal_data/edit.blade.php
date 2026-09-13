@extends('layouts.app')

@section('title', 'تعديل بيانات شخصية - ' . $personalData->name)
@section('page_title', 'السجلات الأساسية')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto" dir="rtl">
    
    {{-- Breadcrumbs --}}
    <nav class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-2 print:hidden">
        <a href="{{ route('dashboard') }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">الرئيسية</a>
        <span>/</span>
        <a href="{{ route('system.personal-data.index') }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">إدارة البيانات الشخصية</a>
        <span>/</span>
        <span class="text-slate-900 dark:text-white font-semibold">تعديل سجل: {{ $personalData->name }}</span>
    </nav>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4 print:hidden">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold shadow-sm shrink-0">
                <i class="fas fa-user-edit text-xl"></i>
            </div>
            <div>
                <h1 class="font-display font-bold text-2xl text-slate-900 dark:text-white m-0">تعديل سجل: {{ $personalData->name }}</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">تحديث وتعديل البيانات الشخصية والمهنية في قاعدة البيانات.</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('system.personal-data.show', $personalData) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 font-bold text-sm rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors shadow-sm">
                <i class="fas fa-eye text-brand-600"></i>
                <span>عرض السجل</span>
            </a>
            <a href="{{ route('system.personal-data.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-sm rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                <i class="fas fa-arrow-right"></i>
                <span>عودة للقائمة</span>
            </a>
        </div>
    </div>

    {{-- Form --}}
    <form action="{{ route('system.personal-data.update', $personalData) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Row 1: Personal info + Profile Photo --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">
            
            {{-- 1. Personal & Civil Data (2 Cols on lg) --}}
            <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center gap-3 bg-slate-50/50 dark:bg-slate-800/30">
                    <div class="w-2 h-5 bg-brand-600 rounded-full"></div>
                    <h2 class="font-display font-bold text-base text-slate-900 dark:text-white m-0">المعلومات المدنية والشخصية</h2>
                </div>
                
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">الاسم الكامل رباعياً <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required value="{{ old('name', $personalData->name) }}"
                            class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-600 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all"
                            placeholder="كما هو مدون في بطاقة الهوية">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">الرقم القومي (الهوية)</label>
                        <input type="text" name="national_id_number" value="{{ old('national_id_number', $personalData->national_id_number) }}"
                            class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm font-mono text-slate-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-600 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all"
                            placeholder="رقم الهوية الوطنية">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">الاسم الجهادي</label>
                        <input type="text" name="jihadi_name" value="{{ old('jihadi_name', $personalData->jihadi_name) }}"
                            class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-600 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all"
                            placeholder="الكنية أو اللقب">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">تاريخ الميلاد</label>
                        <input type="date" name="date_of_birth_gregorian" value="{{ old('date_of_birth_gregorian', $personalData->date_of_birth_gregorian ? $personalData->date_of_birth_gregorian->format('Y-m-d') : '') }}"
                            class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">فصيلة الدم</label>
                        <select name="blood_type_id" class="w-full select2-geo bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all">
                            <option value="">اختر الفصيلة...</option>
                            @foreach($bloodTypes as $id => $type)
                                <option value="{{ $id }}" {{ old('blood_type_id', $personalData->blood_type_id) == $id ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">الحالة الاجتماعية</label>
                        <select name="marital_status_id" class="w-full select2-geo bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all">
                            <option value="">اختر الحالة...</option>
                            @foreach($maritalStatuses as $id => $status)
                                <option value="{{ $id }}" {{ old('marital_status_id', $personalData->marital_status_id) == $id ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">المؤهل الدراسي</label>
                        <select name="qualification_id" class="w-full select2-geo bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all">
                            <option value="">اختر المؤهل...</option>
                            @foreach($qualifications as $id => $name)
                                <option value="{{ $id }}" {{ old('qualification_id', $personalData->qualification_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- 2. Photo Upload Box (matching tem ImageUpload) --}}
            <div class="lg:col-span-1 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 flex flex-col items-center justify-center transition-colors">
                <div class="text-center mb-4">
                    <h3 class="font-display font-bold text-base text-slate-900 dark:text-white mb-1">الصورة الشخصية</h3>
                    <p class="text-xs text-slate-400">صورة واضحة بخلفية بيضاء (JPG, PNG)</p>
                </div>

                <div class="relative w-40 h-40 rounded-2xl border-2 border-dashed border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 flex items-center justify-center overflow-hidden mb-5 shadow-sm group">
                    <img id="imagePreview"
                        src="{{ $personalData->main_profile_photo ? asset('storage/' . $personalData->main_profile_photo) : "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='160' height='160' viewBox='0 0 24 24' fill='%2394a3b8'><path d='M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z'/></svg>" }}"
                        class="w-full h-full object-cover">
                    <input type="file" id="main_profile_photo" name="main_profile_photo" class="hidden" accept="image/jpeg, image/png, image/jpg">
                </div>

                <label for="main_profile_photo"
                    class="w-full py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-bold text-sm rounded-xl hover:bg-brand-50 dark:hover:bg-brand-500/10 hover:border-brand-300 hover:text-brand-600 dark:hover:text-brand-400 transition-all cursor-pointer flex items-center justify-center gap-2 text-center">
                    <i class="fas fa-camera"></i>
                    <span>تغيير الصورة</span>
                </label>
            </div>
        </div>

        {{-- Row 2: Contact Details --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center gap-3 bg-slate-50/50 dark:bg-slate-800/30">
                <div class="w-2 h-5 bg-indigo-500 rounded-full"></div>
                <h2 class="font-display font-bold text-base text-slate-900 dark:text-white m-0">معلومات الاتصال</h2>
            </div>
            
            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">رقم الهاتف الأساسي <span class="text-red-500">*</span></label>
                    <input type="text" name="phone_1" required value="{{ old('phone_1', $personalData->phone_1) }}"
                        class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm font-mono text-slate-900 dark:text-white placeholder:text-slate-400 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all"
                        placeholder="770 000 000">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">رقم هاتف إضافي 1</label>
                    <input type="text" name="phone_2" value="{{ old('phone_2', $personalData->phone_2) }}"
                        class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm font-mono text-slate-900 dark:text-white placeholder:text-slate-400 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all"
                        placeholder="771 000 000">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">رقم هاتف إضافي 2</label>
                    <input type="text" name="phone_3" value="{{ old('phone_3', $personalData->phone_3) }}"
                        class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm font-mono text-slate-900 dark:text-white placeholder:text-slate-400 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all"
                        placeholder="772 000 000">
                </div>
            </div>
        </div>

        {{-- Row 3: Military & Professional Data --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center gap-3 bg-slate-50/50 dark:bg-slate-800/30">
                <div class="w-2 h-5 bg-emerald-500 rounded-full"></div>
                <h2 class="font-display font-bold text-base text-slate-900 dark:text-white m-0">السجل العسكري والمهني</h2>
            </div>
            
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">الرتبة العسكرية</label>
                    <select name="rank_id" class="w-full select2-geo bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all">
                        <option value="">اختر الرتبة...</option>
                        @foreach($ranks as $id => $name)
                            <option value="{{ $id }}" {{ old('rank_id', $personalData->rank_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">الرقم العسكري</label>
                    <input type="text" name="military_number" value="{{ old('military_number', $personalData->military_number) }}"
                        class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm font-mono font-bold text-slate-900 dark:text-white placeholder:text-slate-400 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all"
                        placeholder="000000">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">الرقم الجهادي</label>
                    <input type="text" name="jihadist_number" value="{{ old('jihadist_number', $personalData->jihadist_number) }}"
                        class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm font-mono font-bold text-slate-900 dark:text-white placeholder:text-slate-400 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all"
                        placeholder="0000">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">تصنيف الرقم</label>
                    <select name="number_classification_id" class="w-full select2-geo bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all">
                        <option value="">اختر التصنيف...</option>
                        @foreach($numberClassifications as $id => $name)
                            <option value="{{ $id }}" {{ old('number_classification_id', $personalData->number_classification_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">المنطقة العسكرية</label>
                    <select name="military_district_id" id="military_district_id" class="w-full select2-geo bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all">
                        <option value="">اختر المنطقة...</option>
                        @foreach($militaryDistricts as $id => $name)
                            <option value="{{ $id }}" {{ old('military_district_id', $personalData->military_district_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">الشعبة</label>
                    <select name="division_id" id="division_id" class="w-full select2-geo bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all">
                        <option value="">اختر الشعبة...</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">الوحدة</label>
                    <input type="text" name="unit" value="{{ old('unit', $personalData->unit) }}"
                        class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all"
                        placeholder="اسم الوحدة">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">المسمى الوظيفي</label>
                    <input type="text" name="job" value="{{ old('job', $personalData->job) }}"
                        class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all"
                        placeholder="المسمى الوظيفي">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">التخصص الوظيفي</label>
                    <input type="text" name="job_specialty" value="{{ old('job_specialty', $personalData->job_specialty) }}"
                        class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all"
                        placeholder="التخصص">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">مكان العمل</label>
                    <input type="text" name="workplace" value="{{ old('workplace', $personalData->workplace) }}"
                        class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all"
                        placeholder="المقر أو المعسكر">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">حالة الوظيفة</label>
                    <select name="job_status_id" class="w-full select2-geo bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all">
                        <option value="">اختر الحالة...</option>
                        @foreach($jobStatuses as $id => $name)
                            <option value="{{ $id }}" {{ old('job_status_id', $personalData->job_status_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">الحالة المالية</label>
                    <select name="financial_status_id" class="w-full select2-geo bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all">
                        <option value="">اختر الحالة...</option>
                        @foreach($financialStatuses as $id => $name)
                            <option value="{{ $id }}" {{ old('financial_status_id', $personalData->financial_status_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Row 4: Geographic & Address Data --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center gap-3 bg-slate-50/50 dark:bg-slate-800/30">
                <div class="w-2 h-5 bg-amber-500 rounded-full"></div>
                <h2 class="font-display font-bold text-base text-slate-900 dark:text-white m-0">البيانات الجغرافية والعناوين</h2>
            </div>
            
            <div class="p-6 space-y-6">
                {{-- Place of Birth --}}
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800">
                    <span class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-3">
                        <i class="fas fa-map-marker-alt text-amber-500 me-1"></i> مكان الميلاد
                    </span>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">المحافظة</label>
                            <select name="place_of_birth_governorate_id" id="place_of_birth_governorate_id" class="w-full select2-geo bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2 text-sm text-slate-900 dark:text-white">
                                <option value="">اختر المحافظة...</option>
                                @foreach($governorates as $id => $name)
                                    <option value="{{ $id }}" {{ old('place_of_birth_governorate_id', $personalData->place_of_birth_governorate_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">المديرية</label>
                            <select name="place_of_birth_directorate_id" id="place_of_birth_directorate_id" data-selected="{{ old('place_of_birth_directorate_id', $personalData->place_of_birth_directorate_id) }}" class="w-full select2-geo bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2 text-sm text-slate-900 dark:text-white" disabled>
                                <option value="">اختر المديرية...</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">العزلة / المنطقة</label>
                            <select name="place_of_birth_sub_area_id" id="place_of_birth_sub_area_id" data-selected="{{ old('place_of_birth_sub_area_id', $personalData->place_of_birth_sub_area_id) }}" class="w-full select2-geo bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2 text-sm text-slate-900 dark:text-white" disabled>
                                <option value="">اختر أو اكتب العزلة...</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">القرية / المحل</label>
                            <select name="place_of_birth_village_id" id="place_of_birth_village_id" data-selected="{{ old('place_of_birth_village_id', $personalData->place_of_birth_village_id) }}" class="w-full select2-geo bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2 text-sm text-slate-900 dark:text-white" disabled>
                                <option value="">اختر أو اكتب القرية...</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Current Residence --}}
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800">
                    <span class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-3">
                        <i class="fas fa-home text-purple-500 me-1"></i> عنوان السكن الحالي
                    </span>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">المحافظة</label>
                            <select name="governorate_id" id="governorate_id" class="w-full select2-geo bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2 text-sm text-slate-900 dark:text-white">
                                <option value="">اختر المحافظة...</option>
                                @foreach($governorates as $id => $name)
                                    <option value="{{ $id }}" {{ old('governorate_id', $personalData->governorate_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">المديرية</label>
                            <select name="directorate_id" id="directorate_id" data-selected="{{ old('directorate_id', $personalData->directorate_id) }}" class="w-full select2-geo bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2 text-sm text-slate-900 dark:text-white" disabled>
                                <option value="">اختر المديرية...</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">العزلة / المنطقة</label>
                            <select name="sub_area_id" id="sub_area_id" data-selected="{{ old('sub_area_id', $personalData->sub_area_id) }}" class="w-full select2-geo bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2 text-sm text-slate-900 dark:text-white" disabled>
                                <option value="">اختر أو اكتب العزلة...</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">القرية / المحل</label>
                            <select name="village_id" id="village_id" data-selected="{{ old('village_id', $personalData->village_id) }}" class="w-full select2-geo bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2 text-sm text-slate-900 dark:text-white" disabled>
                                <option value="">اختر أو اكتب القرية...</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Action Buttons --}}
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-800">
            <a href="{{ route('system.personal-data.index') }}"
                class="px-6 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 font-semibold transition-colors text-sm">
                إلغاء
            </a>
            <button type="submit"
                class="px-8 py-2.5 rounded-xl bg-brand-600 text-white hover:bg-brand-700 font-bold shadow-sm shadow-brand-500/30 transition-all text-sm flex items-center gap-2">
                <i class="fas fa-check"></i>
                <span>حفظ التعديلات</span>
            </button>
        </div>

    </form>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('vendor/select2/select2.min.css') }}">
    <style>
        .select2-container--default .select2-selection--single {
            height: 42px !important;
            padding: 6px 12px !important;
            border-radius: 0.75rem !important;
            border: 1px solid #e2e8f0 !important;
            background-color: #f8fafc !important;
            display: flex !important;
            align-items: center !important;
            transition: all 0.2s ease;
        }
        .dark .select2-container--default .select2-selection--single {
            border-color: #1e293b !important;
            background-color: #020617 !important;
        }
        .select2-container--default.select2-container--open .select2-selection--single,
        .select2-container--default .select2-selection--single:focus {
            border-color: #4f46e5 !important;
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.2) !important;
            background-color: #ffffff !important;
        }
        .dark .select2-container--default.select2-container--open .select2-selection--single,
        .dark .select2-container--default .select2-selection--single:focus {
            background-color: #0f172a !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: inherit !important;
            font-weight: 600 !important;
            font-size: 0.875rem !important;
            padding-right: 0 !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 42px !important;
            left: 12px !important;
            right: auto !important;
        }
        .select2-dropdown {
            border-radius: 0.75rem !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1) !important;
            z-index: 9999 !important;
        }
        .dark .select2-dropdown {
            border-color: #1e293b !important;
            background-color: #0f172a !important;
            color: #f8fafc !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('vendor/select2/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            const GEO = {
                directorates: @json($allDirectorates),
                subAreas:     @json($allSubAreas),
                villages:     @json($allVillages)
            };

            $('#main_profile_photo').on('change', function() {
                const [file] = this.files;
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) { 
                        $('#imagePreview').attr('src', e.target.result).hide().fadeIn(300); 
                    };
                    reader.readAsDataURL(file);
                }
            });

            function initSelect2($el, placeholder, isTag = false) {
                if ($el.data('select2')) $el.select2('destroy');
                $el.select2({
                    dir: 'rtl',
                    language: {
                        noResults: function() { 
                            return isTag ? 'لا توجد نتائج مطابقة (اضغط Enter للإضافة)' : 'لا توجد نتائج مطابقة'; 
                        },
                        searching: function() { return 'جاري البحث...'; }
                    },
                    placeholder: placeholder,
                    allowClear: true,
                    tags: isTag,
                    createTag: isTag ? function(params) {
                        const term = $.trim(params.term);
                        if (term === '') return null;
                        return {
                            id: term,
                            text: term,
                            isNew: true
                        };
                    } : undefined,
                    templateResult: isTag ? function(data) {
                        if (!data.id) return data.text;
                        const $res = $('<span></span>').text(data.text);
                        if (data.isNew) {
                            $res.append(' <span class="bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300 text-[10px] font-bold px-1.5 py-0.5 rounded-md mr-1.5">جديد +</span>');
                        }
                        return $res;
                    } : undefined,
                    width: '100%'
                });
            }

            $('select.select2-geo, select[name="military_district_id"], select[name="division_id"], select[name="blood_type_id"], select[name="marital_status_id"], select[name="qualification_id"], select[name="rank_id"], select[name="number_classification_id"], select[name="job_status_id"], select[name="financial_status_id"]').each(function() {
                const isTag = true;
                initSelect2($(this), $(this).find('option:first').text() || 'اختر...', isTag);
            });

            function fillDropdown($el, items, placeholder, selectedId, isTag = true) {
                $el.empty().append('<option value="">' + placeholder + '</option>');
                let foundSelected = false;

                if (items && items.length > 0) {
                    items.forEach(function(item) {
                        const isSel = (selectedId && (String(item.id) === String(selectedId) || item.name === String(selectedId)));
                        if (isSel) foundSelected = true;
                        $el.append('<option value="' + item.id + '"' + (isSel ? ' selected' : '') + '>' + item.name + '</option>');
                    });
                }

                if (selectedId && !foundSelected && String(selectedId).trim() !== '') {
                    $el.append('<option value="' + selectedId + '" selected>' + selectedId + '</option>');
                }

                if (isTag) {
                    $el.prop('disabled', false).removeAttr('disabled');
                } else {
                    if (!items || items.length === 0) {
                        $el.append('<option value="" disabled>— لا توجد عناصر —</option>');
                        $el.prop('disabled', true).attr('disabled', 'disabled');
                    } else {
                        $el.prop('disabled', false).removeAttr('disabled');
                    }
                }

                initSelect2($el, placeholder, isTag);
                if (selectedId) $el.val(String(selectedId)).trigger('change.select2');
            }

            function resetDropdown($el, placeholder, isTag = false) {
                $el.empty().append('<option value="">' + placeholder + '</option>');
                if (isTag) {
                    $el.prop('disabled', false).removeAttr('disabled');
                } else {
                    $el.prop('disabled', true).attr('disabled', 'disabled');
                }
                initSelect2($el, placeholder, isTag);
            }

            function setupCascading(govId, dirId, subId, vilId, savedDir, savedSub, savedVil) {
                const $gov = $('#' + govId);
                const $dir = $('#' + dirId);
                const $sub = $('#' + subId);
                const $vil = $('#' + vilId);

                $gov.on('change select2:select', function() {
                    const govVal = $(this).val();
                    resetDropdown($sub, 'اختر أو اكتب العزلة...', true);
                    resetDropdown($vil, 'اختر أو اكتب القرية...', true);

                    if (!govVal) {
                        resetDropdown($dir, 'اختر أو اكتب المديرية...', true);
                        return;
                    }
                    const dirs = GEO.directorates.filter(function(d) {
                        return String(d.governorate_id) === String(govVal);
                    });
                    fillDropdown($dir, dirs, 'اختر أو اكتب المديرية...', null, true);
                });

                $dir.on('change select2:select', function() {
                    const dirVal = $(this).val();
                    resetDropdown($vil, 'اختر أو اكتب القرية...', true);

                    if (!dirVal) {
                        resetDropdown($sub, 'اختر أو اكتب العزلة...', true);
                        return;
                    }
                    const subs = GEO.subAreas.filter(function(s) {
                        return String(s.directorate_id) === String(dirVal);
                    });
                    fillDropdown($sub, subs, 'اختر أو اكتب العزلة...', null, true);
                });

                $sub.on('change select2:select', function() {
                    const subVal = $(this).val();
                    if (!subVal) {
                        resetDropdown($vil, 'اختر أو اكتب القرية...', true);
                        return;
                    }
                    const vils = GEO.villages.filter(function(v) {
                        return String(v.sub_area_id) === String(subVal);
                    });
                    fillDropdown($vil, vils, 'اختر أو اكتب القرية...', null, true);
                });

                if ($gov.val()) {
                    const govVal = $gov.val();
                    const dirs = GEO.directorates.filter(function(d) {
                        return String(d.governorate_id) === String(govVal);
                    });
                    fillDropdown($dir, dirs, 'اختر أو اكتب المديرية...', savedDir, true);

                    if (savedDir) {
                        const subs = GEO.subAreas.filter(function(s) {
                            return String(s.directorate_id) === String(savedDir);
                        });
                        fillDropdown($sub, subs, 'اختر أو اكتب العزلة...', savedSub, true);

                        if (savedSub) {
                            const vils = GEO.villages.filter(function(v) {
                                return String(v.sub_area_id) === String(savedSub);
                            });
                            fillDropdown($vil, vils, 'اختر أو اكتب القرية...', savedVil, true);
                        }
                    }
                } else {
                    resetDropdown($dir, 'اختر أو اكتب المديرية...', true);
                }
            }

            (function setupMilitaryCascading() {
                const $dist = $('#military_district_id');
                const $div  = $('#division_id');
                const geoBase = '{{ url("api/geo") }}';

                initSelect2($dist, 'اختر أو اكتب المنطقة العسكرية...', true);

                function loadDivisions(distVal, preselected) {
                    resetDropdown($div, '⏳ جاري التحميل...', true);
                    fetch(geoBase + '/divisions/' + distVal, {
                        credentials: 'same-origin',
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(function(r) { return r.ok ? r.json() : {}; })
                    .then(function(data) {
                        const keys = Object.keys(data);
                        const items = keys.map(id => ({ id: id, name: data[id] }));
                        fillDropdown($div, items, 'اختر أو اكتب الشعبة...', preselected, true);
                    })
                    .catch(function() { resetDropdown($div, 'اختر أو اكتب الشعبة...', true); });
                }

                $dist.on('change select2:select', function() {
                    const v = $(this).val();
                    if (v) loadDivisions(v, '{{ old("division_id", $personalData->division_id) }}');
                    else resetDropdown($div, 'اختر أو اكتب الشعبة...', true);
                });

                initSelect2($div, 'اختر أو اكتب الشعبة...', true);
                if ($dist.val()) loadDivisions($dist.val(), '{{ old("division_id", $personalData->division_id) }}');
            })();

            setupCascading('place_of_birth_governorate_id', 'place_of_birth_directorate_id', 'place_of_birth_sub_area_id', 'place_of_birth_village_id', '{{ old("place_of_birth_directorate_id", $personalData->place_of_birth_directorate_id) }}', '{{ old("place_of_birth_sub_area_id", $personalData->place_of_birth_sub_area_id) }}', '{{ old("place_of_birth_village_id", $personalData->place_of_birth_village_id) }}');
            setupCascading('governorate_id', 'directorate_id', 'sub_area_id', 'village_id', '{{ old("directorate_id", $personalData->directorate_id) }}', '{{ old("sub_area_id", $personalData->sub_area_id) }}', '{{ old("village_id", $personalData->village_id) }}');
        });
    </script>
@endpush
