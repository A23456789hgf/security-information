@extends('layouts.app')

@section('title', 'تعديل بيانات شخصية')
@section('page_title', 'السجلات الأساسية')

@section('content')
    <div class="container-fluid mx-auto px-4 py-8" dir="rtl">
        {{-- رأس الصفحة --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-amber-500 rounded-3xl shadow-xl shadow-amber-100 flex items-center justify-center text-white">
                    <i class="fas fa-user-edit text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-800 header-font mb-1">تعديل سجل: {{ $personalData->name }}</h2>
                    <p class="text-slate-500 text-sm">تحديث البيانات الشخصية والمهنية لهذا السجل.</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('system.personal-data.show', $personalData->id) }}" class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl font-bold hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2">
                    <i class="fas fa-eye"></i> عرض السجل
                </a>
                <a href="{{ route('system.personal-data.index') }}" class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl font-bold hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2">
                    <i class="fas fa-arrow-right"></i> عودة للقائمة
                </a>
            </div>
        </div>

        <form action="{{ route('system.personal-data.update', $personalData->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                {{-- الجانب الأيمن: الصورة والبيانات الأساسية --}}
                <div class="lg:col-span-4 space-y-8">
                    <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 p-8 text-center relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-full -mr-16 -mt-16 opacity-50"></div>
                        
                        <div class="relative z-10">
                            <div class="w-48 h-48 mx-auto mb-6 relative group">
                                <img id="imagePreview"
                                    src="{{ $personalData->main_profile_photo ? asset('storage/' . $personalData->main_profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($personalData->name) . '&background=f8fafc&color=4361ee&size=200' }}"
                                    class="w-full h-full object-cover rounded-[2rem] border-4 border-white shadow-2xl transition-transform group-hover:scale-105">
                                <label for="main_profile_photo"
                                    class="absolute -bottom-2 -left-2 w-12 h-12 bg-blue-600 text-white rounded-2xl border-4 border-white flex items-center justify-center shadow-lg cursor-pointer hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-camera"></i>
                                </label>
                                <input type="file" id="main_profile_photo" name="main_profile_photo" class="hidden" accept="image/*">
                            </div>
                            <h5 class="text-lg font-bold text-slate-800 header-font mb-2">الصورة الشخصية</h5>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">تحديث الصورة الرسمية</p>

                            <div class="mt-10 space-y-6 text-right">
                                <div>
                                    <label class="text-xs font-bold text-slate-400 uppercase mb-2 block">الرقم القومي (الهوية) *</label>
                                    <div class="relative">
                                        <i class="fas fa-id-card absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                                        <input type="text" name="national_id_number" value="{{ old('national_id_number', $personalData->national_id_number) }}" required class="w-full pl-10 pr-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all font-mono text-sm" placeholder="11 خانة">
                                    </div>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 uppercase mb-2 block">رقم الهاتف 1 *</label>
                                    <div class="relative">
                                        <i class="fas fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                                        <input type="text" name="phone_1" value="{{ old('phone_1', $personalData->phone_1) }}" required class="w-full pl-10 pr-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all font-mono text-sm" placeholder="770 000 000">
                                    </div>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 uppercase mb-2 block">رقم الهاتف 2</label>
                                    <div class="relative">
                                        <i class="fas fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                                        <input type="text" name="phone_2" value="{{ old('phone_2', $personalData->phone_2) }}" class="w-full pl-10 pr-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all font-mono text-sm" placeholder="771 000 000">
                                    </div>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 uppercase mb-2 block">رقم الهاتف 3</label>
                                    <div class="relative">
                                        <i class="fas fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                                        <input type="text" name="phone_3" value="{{ old('phone_3', $personalData->phone_3) }}" class="w-full pl-10 pr-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all font-mono text-sm" placeholder="772 000 000">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- الجانب الأيسر: بقية البيانات --}}
                <div class="lg:col-span-8 space-y-8">
                    {{-- المعلومات المدنية --}}
                    <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
                        <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                            <h5 class="text-lg font-bold text-slate-800 header-font flex items-center gap-3">
                                <span class="w-2 h-6 bg-blue-600 rounded-full"></span>
                                المعلومات المدنية والشخصية
                            </h5>
                        </div>
                        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="text-xs font-bold text-slate-400 mb-2 block">الاسم الكامل رباعياً *</label>
                                <input type="text" name="name" value="{{ old('name', $personalData->name) }}" required class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all font-bold text-slate-700" placeholder="كما هو في الهوية">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 mb-2 block">الاسم الجهادي</label>
                                <input type="text" name="jihadi_name" value="{{ old('jihadi_name', $personalData->jihadi_name) }}" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all" placeholder="الكنية">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 mb-2 block">تاريخ الميلاد</label>
                                <input type="date" name="date_of_birth_gregorian" value="{{ old('date_of_birth_gregorian', $personalData->date_of_birth_gregorian ? $personalData->date_of_birth_gregorian->format('Y-m-d') : '') }}" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 mb-2 block">فصيلة الدم</label>
                                <select name="blood_type_id" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all appearance-none">
                                    <option value="">اختر الفصيلة...</option>
                                    @foreach($bloodTypes as $id => $type)
                                        <option value="{{ $id }}" {{ old('blood_type_id', $personalData->blood_type_id) == $id ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 mb-2 block">الحالة الاجتماعية</label>
                                <select name="marital_status_id" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all appearance-none">
                                    <option value="">اختر الحالة...</option>
                                    @foreach($maritalStatuses as $id => $status)
                                        <option value="{{ $id }}" {{ old('marital_status_id', $personalData->marital_status_id) == $id ? 'selected' : '' }}>{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 mb-2 block">المؤهل الدراسي</label>
                                <select name="qualification_id" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all appearance-none">
                                    <option value="">اختر المؤهل...</option>
                                    @foreach($qualifications as $id => $name)
                                        <option value="{{ $id }}" {{ old('qualification_id', $personalData->qualification_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- السجل العسكري والمهني --}}
                    <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
                        <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                            <h5 class="text-lg font-bold text-slate-800 header-font flex items-center gap-3">
                                <span class="w-2 h-6 bg-emerald-600 rounded-full"></span>
                                السجل العسكري والمهني
                            </h5>
                        </div>
                        <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="text-xs font-bold text-slate-400 mb-2 block">الرتبة العسكرية</label>
                                <select name="rank_id" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all appearance-none">
                                    <option value="">اختر الرتبة...</option>
                                    @foreach($ranks as $id => $name)
                                        <option value="{{ $id }}" {{ old('rank_id', $personalData->rank_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 mb-2 block">الرقم العسكري</label>
                                <input type="text" name="military_number" value="{{ old('military_number', $personalData->military_number) }}" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all font-mono" placeholder="000000">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 mb-2 block">الرقم الجهادي</label>
                                <input type="text" name="jihadist_number" value="{{ old('jihadist_number', $personalData->jihadist_number) }}" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all font-mono" placeholder="0000">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 mb-2 block">تصنيف الرقم</label>
                                <select name="number_classification_id" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all appearance-none">
                                    <option value="">اختر التصنيف...</option>
                                    @foreach($numberClassifications as $id => $name)
                                        <option value="{{ $id }}" {{ old('number_classification_id', $personalData->number_classification_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 mb-2 block">المنطقة العسكرية</label>
                                <select name="military_district_id" id="military_district_id" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all appearance-none">
                                    <option value="">اختر المنطقة...</option>
                                    @foreach($militaryDistricts as $id => $name)
                                        <option value="{{ $id }}" {{ old('military_district_id', $personalData->military_district_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 mb-2 block">الشعبة</label>
                                <select name="division_id" id="division_id" data-selected="{{ old('division_id', $personalData->division_id) }}" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all appearance-none">
                                    <option value="">اختر الشعبة...</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 mb-2 block">الوحدة</label>
                                <input type="text" name="unit" value="{{ old('unit', $personalData->unit) }}" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all" placeholder="اسم الوحدة">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 mb-2 block">الوظيفة</label>
                                <input type="text" name="job" value="{{ old('job', $personalData->job) }}" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all" placeholder="المسمى الوظيفي">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 mb-2 block">التخصص الوظيفي</label>
                                <input type="text" name="job_specialty" value="{{ old('job_specialty', $personalData->job_specialty) }}" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all" placeholder="التخصص">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 mb-2 block">مكان العمل</label>
                                <input type="text" name="workplace" value="{{ old('workplace', $personalData->workplace) }}" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all" placeholder="المقر">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 mb-2 block">حالة الوظيفة</label>
                                <select name="job_status_id" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all appearance-none">
                                    <option value="">اختر الحالة...</option>
                                    @foreach($jobStatuses as $id => $name)
                                        <option value="{{ $id }}" {{ old('job_status_id', $personalData->job_status_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 mb-2 block">الحالة المالية</label>
                                <select name="financial_status_id" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all appearance-none">
                                    <option value="">اختر الحالة...</option>
                                    @foreach($financialStatuses as $id => $name)
                                        <option value="{{ $id }}" {{ old('financial_status_id', $personalData->financial_status_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- مكان الميلاد --}}
                    <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
                        <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                            <h5 class="text-lg font-bold text-slate-800 header-font flex items-center gap-3">
                                <span class="w-2 h-6 bg-amber-500 rounded-full"></span>
                                مكان الميلاد
                            </h5>
                        </div>
                        <div class="p-8 grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div>
                                <label class="text-xs font-bold text-slate-400 mb-2 block">المحافظة</label>
                                <select name="place_of_birth_governorate_id" id="place_of_birth_governorate_id" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all appearance-none">
                                    <option value="">اختر المحافظة...</option>
                                    @foreach($governorates as $id => $name)
                                        <option value="{{ $id }}" {{ old('place_of_birth_governorate_id', $personalData->place_of_birth_governorate_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 mb-2 block">المديرية</label>
                                <select name="place_of_birth_directorate_id" id="place_of_birth_directorate_id" data-selected="{{ old('place_of_birth_directorate_id', $personalData->place_of_birth_directorate_id) }}" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all appearance-none">
                                    <option value="">اختر المديرية...</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 mb-2 block">العزلة/المنطقة</label>
                                <select name="place_of_birth_sub_area_id" id="place_of_birth_sub_area_id" data-selected="{{ old('place_of_birth_sub_area_id', $personalData->place_of_birth_sub_area_id) }}" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all appearance-none">
                                    <option value="">اختر العزلة...</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 mb-2 block">القرية/المحل</label>
                                <select name="place_of_birth_village_id" id="place_of_birth_village_id" data-selected="{{ old('place_of_birth_village_id', $personalData->place_of_birth_village_id) }}" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all appearance-none">
                                    <option value="">اختر القرية...</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- السكن الحالي --}}
                    <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
                        <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                            <h5 class="text-lg font-bold text-slate-800 header-font flex items-center gap-3">
                                <span class="w-2 h-6 bg-purple-500 rounded-full"></span>
                                السكن الحالي
                            </h5>
                        </div>
                        <div class="p-8 grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div>
                                <label class="text-xs font-bold text-slate-400 mb-2 block">المحافظة</label>
                                <select name="governorate_id" id="governorate_id" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-purple-100 transition-all appearance-none">
                                    <option value="">اختر المحافظة...</option>
                                    @foreach($governorates as $id => $name)
                                        <option value="{{ $id }}" {{ old('governorate_id', $personalData->governorate_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 mb-2 block">المديرية</label>
                                <select name="directorate_id" id="directorate_id" data-selected="{{ old('directorate_id', $personalData->directorate_id) }}" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-purple-100 transition-all appearance-none">
                                    <option value="">اختر المديرية...</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 mb-2 block">العزلة/المنطقة</label>
                                <select name="sub_area_id" id="sub_area_id" data-selected="{{ old('sub_area_id', $personalData->sub_area_id) }}" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-purple-100 transition-all appearance-none">
                                    <option value="">اختر العزلة...</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 mb-2 block">القرية/المحل</label>
                                <select name="village_id" id="village_id" data-selected="{{ old('village_id', $personalData->village_id) }}" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-purple-100 transition-all appearance-none">
                                    <option value="">اختر القرية...</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- أزرار الحفظ --}}
                    <div class="flex items-center justify-end gap-4 pb-12">
                        <button type="button" onclick="history.back()" class="px-8 py-3 text-slate-400 font-bold hover:text-slate-600 transition-colors">إلغاء التعديلات</button>
                        <button type="submit" class="px-10 py-4 bg-amber-600 text-white rounded-[2rem] font-bold hover:bg-amber-700 transition-all shadow-xl shadow-amber-100 flex items-center gap-2">
                            <i class="fas fa-save"></i> تحديث البيانات
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Profile Photo Preview
            $('#main_profile_photo').on('change', function() {
                const [file] = this.files
                if (file) {
                    $('#imagePreview').attr('src', URL.createObjectURL(file))
                }
            });

            // Cascading Dropdowns Setup
            function setupCascading(govId, dirId, subId, vilId) {
                const $gov = $(`#${govId}`);
                const $dir = $(`#${dirId}`);
                const $sub = $(`#${subId}`);
                const $vil = $(`#${vilId}`);

                function loadOptions(url, $el, placeholder, selectedId) {
                    return fetch(url).then(r => r.json()).then(data => {
                        $el.html(`<option value="">${placeholder}</option>`);
                        Object.keys(data).forEach(id => {
                            $el.append(`<option value="${id}" ${id == selectedId ? 'selected' : ''}>${data[id]}</option>`);
                        });
                        $el.prop('disabled', false);
                    });
                }

                $gov.on('change', function() {
                    const id = $(this).val();
                    $dir.prop('disabled', true).html('<option>جاري التحميل...</option>');
                    if (id) loadOptions(`/api/geo/directorates/${id}`, $dir, 'اختر المديرية...', $dir.data('selected')).then(() => {
                        $dir.data('selected', null);
                        $dir.trigger('change');
                    });
                });

                $dir.on('change', function() {
                    const id = $(this).val();
                    $sub.prop('disabled', true).html('<option>جاري التحميل...</option>');
                    if (id) loadOptions(`/api/geo/sub-areas/${id}`, $sub, 'اختر العزلة...', $sub.data('selected')).then(() => {
                        $sub.data('selected', null);
                        $sub.trigger('change');
                    });
                });

                $sub.on('change', function() {
                    const id = $(this).val();
                    $vil.prop('disabled', true).html('<option>جاري التحميل...</option>');
                    if (id) loadOptions(`/api/geo/villages/${id}`, $vil, 'اختر القرية...', $vil.data('selected')).then(() => {
                        $vil.data('selected', null);
                    });
                });

                // Initial trigger for edit mode
                if ($gov.val()) {
                    $gov.trigger('change');
                }
            }

            setupCascading('place_of_birth_governorate_id', 'place_of_birth_directorate_id', 'place_of_birth_sub_area_id', 'place_of_birth_village_id');
            setupCascading('governorate_id', 'directorate_id', 'sub_area_id', 'village_id');

            // Military District -> Division Cascading
            $('#military_district_id').on('change', function() {
                const id = $(this).val();
                const $div = $('#division_id');
                const sel = $div.data('selected');
                
                $div.prop('disabled', true).html('<option>جاري التحميل...</option>');
                if (id) {
                    fetch(`/api/geo/divisions/${id}`).then(r => r.json()).then(data => {
                        $div.html('<option value="">اختر الشعبة...</option>');
                        Object.keys(data).forEach(did => {
                            $div.append(`<option value="${did}" ${did == sel ? 'selected' : ''}>${data[did]}</option>`);
                        });
                        $div.prop('disabled', false).data('selected', null);
                    });
                }
            });

            if ($('#military_district_id').val()) {
                $('#military_district_id').trigger('change');
            }
        });
    </script>
@endpush

@push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Noto+Sans+Arabic:wght@300;400;500;600;700;800&display=swap');
        
        :root {
            --header-font: 'Outfit', 'Noto Sans Arabic', sans-serif;
            --body-font: 'Outfit', 'Noto Sans Arabic', sans-serif;
        }

        body {
            font-family: var(--body-font);
            background-color: #f8fafc;
        }

        .header-font {
            font-family: var(--header-font);
        }

        .soft-shadow {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
        }

        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(0.5);
            cursor: pointer;
        }
    </style>
@endpush