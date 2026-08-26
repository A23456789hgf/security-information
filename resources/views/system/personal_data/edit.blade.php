@extends('layouts.app')

@section('title', 'تعديل بيانات شخصية - ' . $personalData->name)
@section('page_title', 'السجلات الأساسية')

@section('content')
    <div class="container-fluid mx-auto px-4 py-6" dir="rtl">
        
        {{-- رأس الصفحة المدمج (Compact Header) --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 max-w-7xl mx-auto">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-amber-500 rounded-2xl shadow-md shadow-amber-100 flex items-center justify-center text-white">
                    <i class="fas fa-user-edit text-lg"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-800 header-font mb-0.5">تعديل سجل: {{ $personalData->name }}</h2>
                    <p class="text-slate-400 text-[11px]">يرجى تحديث البيانات الشخصية والمهنية بدقة عالية.</p>
                </div>
            </div>
            <a href="{{ route('system.personal-data.index') }}"
                class="px-4 py-2 bg-white border border-slate-200 text-slate-600 font-bold text-xs rounded-xl hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2">
                <i class="fas fa-arrow-right"></i> عودة للقائمة
            </a>
        </div>

        <form action="{{ route('system.personal-data.update', $personalData) }}" method="POST" enctype="multipart/form-data" class="max-w-7xl mx-auto">
            @csrf
            @method('PUT')

            {{-- الكارد الرئيسي المدمج --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 md:p-6">
                
                {{-- 1. المعلومات المدنية والشخصية --}}
                <div class="mb-8">
                    <h4 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2 border-b border-slate-100 pb-3">
                        <span class="w-1.5 h-5 bg-blue-600 rounded-full"></span>
                        المعلومات المدنية والشخصية
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        {{-- الحقل 1 --}}
                        <div>
                            <label class="text-[11px] font-semibold text-slate-500 mb-1.5 block">الاسم الكامل رباعياً *</label>
                            <input type="text" name="name" value="{{ old('name', $personalData->name) }}" required class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:ring-1 focus:ring-blue-500 focus:bg-white transition-all" placeholder="كما هو في الهوية">
                        </div>
                        
                        {{-- الحقل 2 --}}
                        <div>
                            <label class="text-[11px] font-semibold text-slate-500 mb-1.5 block">الرقم القومي (الهوية)</label>
                            <div class="relative">
                                <i class="fas fa-id-card absolute left-3 top-1/2 -translate-y-1/2 text-slate-300 text-xs"></i>
                                <input type="text" name="national_id_number" value="{{ old('national_id_number', $personalData->national_id_number) }}" class="w-full pl-8 pr-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-medium text-slate-700 focus:ring-1 focus:ring-blue-500 focus:bg-white transition-all" placeholder="11 خانة">
                            </div>
                        </div>

                        {{-- الصورة الشريحة المدمجة --}}
                        <div class="lg:row-span-2 flex flex-col items-center justify-center p-4 bg-slate-50/70 border border-slate-200/60 rounded-xl h-full">
                            <div class="w-24 h-24 relative group mb-2">
                                <img id="imagePreview"
                                    src="{{ $personalData->main_profile_photo ? asset('storage/' . $personalData->main_profile_photo) : "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='200' height='200' viewBox='0 0 24 24' fill='%2394a3b8'><path d='M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z'/></svg>" }}"
                                    class="w-full h-full object-cover rounded-xl border-2 border-white shadow-sm transition-transform group-hover:scale-105">
                                <label for="main_profile_photo"
                                    class="absolute -bottom-1.5 -left-1.5 w-7 h-7 bg-blue-600 text-white rounded-lg border border-white flex items-center justify-center shadow-md cursor-pointer hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-camera text-[10px]"></i>
                                </label>
                                <input type="file" id="main_profile_photo" name="main_profile_photo" class="hidden" accept="image/*">
                            </div>
                            <h6 class="text-xs font-bold text-slate-700">الصورة الشخصية</h6>
                            <p class="text-[10px] text-slate-400 mt-0.5">صيغة JPG, PNG</p>
                        </div>

                        {{-- الحقل 3 --}}
                        <div>
                            <label class="text-[11px] font-semibold text-slate-500 mb-1.5 block">الاسم الجهادي</label>
                            <input type="text" name="jihadi_name" value="{{ old('jihadi_name', $personalData->jihadi_name) }}" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:ring-1 focus:ring-blue-500 focus:bg-white transition-all" placeholder="الكنية">
                        </div>
                        
                        {{-- الحقل 4 --}}
                        <div>
                            <label class="text-[11px] font-semibold text-slate-500 mb-1.5 block">تاريخ الميلاد</label>
                            <input type="date" name="date_of_birth_gregorian" value="{{ old('date_of_birth_gregorian', $personalData->date_of_birth_gregorian) }}" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:ring-1 focus:ring-blue-500 focus:bg-white transition-all">
                        </div>

                        {{-- الحقل 5 --}}
                        <div>
                            <label class="text-[11px] font-semibold text-slate-500 mb-1.5 block">فصيلة الدم</label>
                            <select name="blood_type_id" class="w-full select2-geo px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:ring-1 focus:ring-blue-500 focus:bg-white transition-all">
                                <option value="">اختر الفصيلة...</option>
                                @foreach($bloodTypes as $id => $type)
                                    <option value="{{ $id }}" {{ old('blood_type_id', $personalData->blood_type_id) == $id ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        {{-- الحقل 6 --}}
                        <div>
                            <label class="text-[11px] font-semibold text-slate-500 mb-1.5 block">الحالة الاجتماعية</label>
                            <select name="marital_status_id" class="w-full select2-geo px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:ring-1 focus:ring-blue-500 focus:bg-white transition-all">
                                <option value="">اختر الحالة...</option>
                                @foreach($maritalStatuses as $id => $status)
                                    <option value="{{ $id }}" {{ old('marital_status_id', $personalData->marital_status_id) == $id ? 'selected' : '' }}>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        {{-- الحقل 7 --}}
                        <div>
                            <label class="text-[11px] font-semibold text-slate-500 mb-1.5 block">المؤهل الدراسي</label>
                            <select name="qualification_id" class="w-full select2-geo px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:ring-1 focus:ring-blue-500 focus:bg-white transition-all">
                                <option value="">اختر المؤهل...</option>
                                @foreach($qualifications as $id => $name)
                                    <option value="{{ $id }}" {{ old('qualification_id', $personalData->qualification_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- 2. معلومات الاتصال --}}
                <div class="mb-8">
                    <h4 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2 border-b border-slate-100 pb-3">
                        <span class="w-1.5 h-5 bg-indigo-500 rounded-full"></span>
                        معلومات الاتصال
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="text-[11px] font-semibold text-slate-500 mb-1.5 block">رقم الهاتف 1 *</label>
                            <div class="relative">
                                <i class="fas fa-phone absolute left-3 top-1/2 -translate-y-1/2 text-slate-300 text-xs"></i>
                                <input type="text" name="phone_1" value="{{ old('phone_1', $personalData->phone_1) }}" required class="w-full pl-8 pr-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-medium text-slate-700 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all" placeholder="770 000 000">
                            </div>
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-slate-500 mb-1.5 block">رقم الهاتف 2</label>
                            <div class="relative">
                                <i class="fas fa-phone absolute left-3 top-1/2 -translate-y-1/2 text-slate-300 text-xs"></i>
                                <input type="text" name="phone_2" value="{{ old('phone_2', $personalData->phone_2) }}" class="w-full pl-8 pr-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-medium text-slate-700 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all" placeholder="771 000 000">
                            </div>
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-slate-500 mb-1.5 block">رقم الهاتف 3</label>
                            <div class="relative">
                                <i class="fas fa-phone absolute left-3 top-1/2 -translate-y-1/2 text-slate-300 text-xs"></i>
                                <input type="text" name="phone_3" value="{{ old('phone_3', $personalData->phone_3) }}" class="w-full pl-8 pr-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-medium text-slate-700 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all" placeholder="772 000 000">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 3. السجل العسكري والمهني --}}
                <div class="mb-8">
                    <h4 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2 border-b border-slate-100 pb-3">
                        <span class="w-1.5 h-5 bg-emerald-500 rounded-full"></span>
                        السجل العسكري والمهني
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="text-[11px] font-semibold text-slate-500 mb-1.5 block">الرتبة العسكرية</label>
                            <select name="rank_id" class="w-full select2-geo px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:ring-1 focus:ring-emerald-500 focus:bg-white transition-all">
                                <option value="">اختر الرتبة...</option>
                                @foreach($ranks as $id => $name)
                                    <option value="{{ $id }}" {{ old('rank_id', $personalData->rank_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-slate-500 mb-1.5 block">الرقم العسكري</label>
                            <input type="text" name="military_number" value="{{ old('military_number', $personalData->military_number) }}" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-medium text-slate-700 focus:ring-1 focus:ring-emerald-500 focus:bg-white transition-all" placeholder="000000">
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-slate-500 mb-1.5 block">الرقم الجهادي</label>
                            <input type="text" name="jihadist_number" value="{{ old('jihadist_number', $personalData->jihadist_number) }}" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-medium text-slate-700 focus:ring-1 focus:ring-emerald-500 focus:bg-white transition-all" placeholder="0000">
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-slate-500 mb-1.5 block">تصنيف الرقم</label>
                            <select name="number_classification_id" class="w-full select2-geo px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:ring-1 focus:ring-emerald-500 focus:bg-white transition-all">
                                <option value="">اختر التصنيف...</option>
                                @foreach($numberClassifications as $id => $name)
                                    <option value="{{ $id }}" {{ old('number_classification_id', $personalData->number_classification_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-slate-500 mb-1.5 block">المنطقة العسكرية</label>
                            <select name="military_district_id" id="military_district_id" class="w-full select2-geo px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:ring-1 focus:ring-emerald-500 focus:bg-white transition-all">
                                <option value="">اختر المنطقة...</option>
                                @foreach($militaryDistricts as $id => $name)
                                    <option value="{{ $id }}" {{ old('military_district_id', $personalData->military_district_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-slate-500 mb-1.5 block">الشعبة</label>
                            <select name="division_id" id="division_id" class="w-full select2-geo px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:ring-1 focus:ring-emerald-500 focus:bg-white transition-all">
                                <option value="">اختر الشعبة...</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-slate-500 mb-1.5 block">الوحدة</label>
                            <input type="text" name="unit" value="{{ old('unit', $personalData->unit) }}" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:ring-1 focus:ring-emerald-500 focus:bg-white transition-all" placeholder="اسم الوحدة">
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-slate-500 mb-1.5 block">الوظيفة</label>
                            <input type="text" name="job" value="{{ old('job', $personalData->job) }}" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:ring-1 focus:ring-emerald-500 focus:bg-white transition-all" placeholder="المسمى الوظيفي">
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-slate-500 mb-1.5 block">التخصص الوظيفي</label>
                            <input type="text" name="job_specialty" value="{{ old('job_specialty', $personalData->job_specialty) }}" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:ring-1 focus:ring-emerald-500 focus:bg-white transition-all" placeholder="التخصص">
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-slate-500 mb-1.5 block">مكان العمل</label>
                            <input type="text" name="workplace" value="{{ old('workplace', $personalData->workplace) }}" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:ring-1 focus:ring-emerald-500 focus:bg-white transition-all" placeholder="المقر">
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-slate-500 mb-1.5 block">حالة الوظيفة</label>
                            <select name="job_status_id" class="w-full select2-geo px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:ring-1 focus:ring-emerald-500 focus:bg-white transition-all">
                                <option value="">اختر الحالة...</option>
                                @foreach($jobStatuses as $id => $name)
                                    <option value="{{ $id }}" {{ old('job_status_id', $personalData->job_status_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-slate-500 mb-1.5 block">الحالة المالية</label>
                            <select name="financial_status_id" class="w-full select2-geo px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:ring-1 focus:ring-emerald-500 focus:bg-white transition-all">
                                <option value="">اختر الحالة...</option>
                                @foreach($financialStatuses as $id => $name)
                                    <option value="{{ $id }}" {{ old('financial_status_id', $personalData->financial_status_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- 4. البيانات الجغرافية (العناوين) --}}
                <div class="mb-4">
                    <h4 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2 border-b border-slate-100 pb-3">
                        <span class="w-1.5 h-5 bg-amber-500 rounded-full"></span>
                        البيانات الجغرافية (العناوين)
                    </h4>
                    
                    {{-- قسم مكان الميلاد --}}
                    <div class="mb-6">
                        <h6 class="font-bold text-xs text-slate-600 mb-3 flex items-center gap-1.5">
                            <i class="fas fa-map-marker-alt text-amber-500 text-xs"></i> مكان الميلاد
                        </h6>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label class="text-[11px] font-semibold text-slate-500 mb-1.5 block">المحافظة</label>
                                <select name="place_of_birth_governorate_id" id="place_of_birth_governorate_id" class="w-full select2-geo px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:ring-1 focus:ring-amber-500 focus:bg-white transition-all">
                                    <option value="">اختر المحافظة...</option>
                                    @foreach($governorates as $id => $name)
                                        <option value="{{ $id }}" {{ old('place_of_birth_governorate_id', $personalData->place_of_birth_governorate_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-[11px] font-semibold text-slate-500 mb-1.5 block">المديرية</label>
                                <select name="place_of_birth_directorate_id" id="place_of_birth_directorate_id" data-selected="{{ old('place_of_birth_directorate_id', $personalData->place_of_birth_directorate_id) }}" class="w-full select2-geo px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:ring-1 focus:ring-amber-500 focus:bg-white transition-all" disabled>
                                    <option value="">اختر المديرية...</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[11px] font-semibold text-slate-500 mb-1.5 block">العزلة/المنطقة <span class="text-slate-400 font-normal">(اختياري)</span></label>
                                <select name="place_of_birth_sub_area_id" id="place_of_birth_sub_area_id" data-selected="{{ old('place_of_birth_sub_area_id', $personalData->place_of_birth_sub_area_id) }}" class="w-full select2-geo px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:ring-1 focus:ring-amber-500 focus:bg-white transition-all" disabled>
                                    <option value="">اختر العزلة...</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[11px] font-semibold text-slate-500 mb-1.5 block">القرية/المحل <span class="text-slate-400 font-normal">(اختياري)</span></label>
                                <select name="place_of_birth_village_id" id="place_of_birth_village_id" data-selected="{{ old('place_of_birth_village_id', $personalData->place_of_birth_village_id) }}" class="w-full select2-geo px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:ring-1 focus:ring-amber-500 focus:bg-white transition-all" disabled>
                                    <option value="">اختر القرية...</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- قسم السكن الحالي --}}
                    <div>
                        <h6 class="font-bold text-xs text-slate-600 mb-3 flex items-center gap-1.5">
                            <i class="fas fa-home text-purple-500 text-xs"></i> السكن الحالي
                        </h6>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label class="text-[11px] font-semibold text-slate-500 mb-1.5 block">المحافظة</label>
                                <select name="governorate_id" id="governorate_id" class="w-full select2-geo px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:ring-1 focus:ring-purple-500 focus:bg-white transition-all">
                                    <option value="">اختر المحافظة...</option>
                                    @foreach($governorates as $id => $name)
                                        <option value="{{ $id }}" {{ old('governorate_id', $personalData->governorate_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-[11px] font-semibold text-slate-500 mb-1.5 block">المديرية</label>
                                <select name="directorate_id" id="directorate_id" data-selected="{{ old('directorate_id', $personalData->directorate_id) }}" class="w-full select2-geo px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:ring-1 focus:ring-purple-500 focus:bg-white transition-all" disabled>
                                    <option value="">اختر المديرية...</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[11px] font-semibold text-slate-500 mb-1.5 block">العزلة/المنطقة <span class="text-slate-400 font-normal">(اختياري)</span></label>
                                <select name="sub_area_id" id="sub_area_id" data-selected="{{ old('sub_area_id', $personalData->sub_area_id) }}" class="w-full select2-geo px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:ring-1 focus:ring-purple-500 focus:bg-white transition-all" disabled>
                                    <option value="">اختر العزلة...</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[11px] font-semibold text-slate-500 mb-1.5 block">القرية/المحل <span class="text-slate-400 font-normal">(اختياري)</span></label>
                                <select name="village_id" id="village_id" data-selected="{{ old('village_id', $personalData->village_id) }}" class="w-full select2-geo px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:ring-1 focus:ring-purple-500 focus:bg-white transition-all" disabled>
                                    <option value="">اختر القرية...</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- أزرار الحفظ المدمجة --}}
                <div class="flex items-center justify-end gap-3 mt-8 pt-5 border-t border-slate-100">
                    <button type="button" onclick="history.back()" class="px-4 py-2 bg-white border border-slate-200 text-slate-600 font-bold text-xs rounded-xl hover:bg-slate-50 transition-all">
                        إلغاء
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-1.5">
                        <i class="fas fa-save text-xs"></i> تحديث السجل
                    </button>
                </div>

            </div>
        </form>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('vendor/select2/select2.min.css') }}">
    <style>
        .select2-container--default .select2-selection--single {
            height: 38px !important;
            padding: 4px 12px !important;
            border-radius: 0.75rem !important;
            border: 1px solid #e2e8f0 !important;
            background-color: #f8fafc !important;
            display: flex !important;
            align-items: center !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #334155 !important;
            font-weight: 500 !important;
            font-size: 0.8125rem !important;
            padding-right: 0 !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px !important;
            left: 8px !important;
            right: auto !important;
        }
        .select2-dropdown {
            border-radius: 0.75rem !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08) !important;
            overflow: hidden !important;
            z-index: 9999 !important;
        }
        .select2-search__field {
            border-radius: 0.75rem !important;
            padding: 8px 12px !important;
            border: 1px solid #cbd5e1 !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('vendor/select2/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            // ─── Embedded Geo Data (loaded server-side, no AJAX needed) ───────────────
            const GEO = {
                directorates: @json($allDirectorates),
                subAreas:     @json($allSubAreas),
                villages:     @json($allVillages)
            };

            // ─── Profile Photo Preview ────────────────────────────────────────────────
            $('#main_profile_photo').on('change', function() {
                const [file] = this.files;
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) { $('#imagePreview').attr('src', e.target.result); };
                    reader.readAsDataURL(file);
                }
            });

            // ─── Select2 Initializer ──────────────────────────────────────────────────
            function initSelect2($el, placeholder) {
                if ($el.data('select2')) $el.select2('destroy');
                $el.select2({
                    dir: 'rtl',
                    language: {
                        noResults:  function() { return 'لا توجد نتائج مطابقة'; },
                        searching:  function() { return 'جاري البحث...'; }
                    },
                    placeholder: placeholder,
                    allowClear: true,
                    width: '100%'
                });
            }

            // Initialize static selects
            $('select.select2-geo, select[name="military_district_id"], select[name="division_id"], select[name="blood_type_id"], select[name="marital_status_id"], select[name="qualification_id"], select[name="rank_id"], select[name="number_classification_id"], select[name="job_status_id"], select[name="financial_status_id"]').each(function() {
                initSelect2($(this), $(this).find('option:first').text() || 'اختر...');
            });

            // ─── Core: Populate dropdown from filtered data array ─────────────────────
            function fillDropdown($el, items, placeholder, selectedId) {
                $el.empty().append('<option value="">' + placeholder + '</option>');
                if (!items || items.length === 0) {
                    $el.append('<option value="" disabled>— لا توجد عناصر —</option>');
                    $el.prop('disabled', true).attr('disabled', 'disabled');
                } else {
                    items.forEach(function(item) {
                        const sel = (selectedId && String(item.id) === String(selectedId)) ? ' selected' : '';
                        $el.append('<option value="' + item.id + '"' + sel + '>' + item.name + '</option>');
                    });
                    $el.prop('disabled', false).removeAttr('disabled');
                }
                initSelect2($el, placeholder);
                if (selectedId) $el.val(String(selectedId)).trigger('change.select2');
            }

            function resetDropdown($el, placeholder) {
                $el.empty().append('<option value="">' + placeholder + '</option>');
                $el.prop('disabled', true).attr('disabled', 'disabled');
                initSelect2($el, placeholder);
            }

            // ─── Cascading Setup (pure client-side, instant) ──────────────────────────
            function setupCascading(govId, dirId, subId, vilId, savedDir, savedSub, savedVil) {
                const $gov = $('#' + govId);
                const $dir = $('#' + dirId);
                const $sub = $('#' + subId);
                const $vil = $('#' + vilId);

                // Governorate → Directorates
                $gov.on('change select2:select', function() {
                    const govVal = $(this).val();
                    resetDropdown($sub, 'اختر العزلة...');
                    resetDropdown($vil, 'اختر القرية...');

                    if (!govVal) {
                        resetDropdown($dir, 'اختر المديرية...');
                        return;
                    }
                    const dirs = GEO.directorates.filter(function(d) {
                        return String(d.governorate_id) === String(govVal);
                    });
                    fillDropdown($dir, dirs, 'اختر المديرية...', null);
                });

                // Directorate → Sub-areas
                $dir.on('change select2:select', function() {
                    const dirVal = $(this).val();
                    resetDropdown($vil, 'اختر القرية...');

                    if (!dirVal) {
                        resetDropdown($sub, 'اختر العزلة...');
                        return;
                    }
                    const subs = GEO.subAreas.filter(function(s) {
                        return String(s.directorate_id) === String(dirVal);
                    });
                    fillDropdown($sub, subs, 'اختر العزلة...', null);
                });

                // Sub-area → Villages
                $sub.on('change select2:select', function() {
                    const subVal = $(this).val();
                    if (!subVal) {
                        resetDropdown($vil, 'اختر القرية...');
                        return;
                    }
                    const vils = GEO.villages.filter(function(v) {
                        return String(v.sub_area_id) === String(subVal);
                    });
                    fillDropdown($vil, vils, 'اختر القرية...', null);
                });

                // ── Restore saved values chain (for old() on validation fail) ──
                if ($gov.val()) {
                    const govVal = $gov.val();
                    const dirs = GEO.directorates.filter(function(d) {
                        return String(d.governorate_id) === String(govVal);
                    });
                    fillDropdown($dir, dirs, 'اختر المديرية...', savedDir);

                    if (savedDir) {
                        const subs = GEO.subAreas.filter(function(s) {
                            return String(s.directorate_id) === String(savedDir);
                        });
                        fillDropdown($sub, subs, 'اختر العزلة...', savedSub);

                        if (savedSub) {
                            const vils = GEO.villages.filter(function(v) {
                                return String(v.sub_area_id) === String(savedSub);
                            });
                            fillDropdown($vil, vils, 'اختر القرية...', savedVil);
                        }
                    }
                }
            }

            // ─── Military District → Division (AJAX stays, uses server-side route) ─────
            (function setupMilitaryCascading() {
                const $dist = $('#military_district_id');
                const $div  = $('#division_id');
                const geoBase = '{{ url("api/geo") }}';

                function loadDivisions(distVal, preselected) {
                    resetDropdown($div, '⏳ جاري التحميل...');
                    fetch(geoBase + '/divisions/' + distVal, {
                        credentials: 'same-origin',
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(function(r) { return r.ok ? r.json() : {}; })
                    .then(function(data) {
                        const keys = Object.keys(data);
                        if (keys.length === 0) {
                            resetDropdown($div, 'لا توجد شعب');
                        } else {
                            $div.empty().append('<option value="">اختر الشعبة...</option>');
                            keys.forEach(function(id) {
                                const sel = (preselected && String(id) === String(preselected)) ? ' selected' : '';
                                $div.append('<option value="' + id + '"' + sel + '>' + data[id] + '</option>');
                            });
                            $div.prop('disabled', false);
                            initSelect2($div, 'اختر الشعبة...');
                        }
                    })
                    .catch(function() { resetDropdown($div, '⚠ خطأ في التحميل'); });
                }

                $dist.on('change', function() {
                    const v = $(this).val();
                    if (v) loadDivisions(v, null);
                    else resetDropdown($div, 'اختر الشعبة...');
                });

                initSelect2($div, 'اختر الشعبة...');
                const initialDist = $dist.val();
                if (initialDist) {
                    loadDivisions(initialDist, '{{ old("division_id", $personalData->division_id) }}');
                }
            })();

            // ─── Activate both geo sections ───────────────────────────────────────────
            setupCascading(
                'place_of_birth_governorate_id',
                'place_of_birth_directorate_id',
                'place_of_birth_sub_area_id',
                'place_of_birth_village_id',
                $('#place_of_birth_directorate_id').data('selected'),
                $('#place_of_birth_sub_area_id').data('selected'),
                $('#place_of_birth_village_id').data('selected')
            );
            setupCascading(
                'governorate_id',
                'directorate_id',
                'sub_area_id',
                'village_id',
                $('#directorate_id').data('selected'),
                $('#sub_area_id').data('selected'),
                $('#village_id').data('selected')
            );
        });
    </script>
@endpush