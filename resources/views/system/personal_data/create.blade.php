@extends('layouts.app')

@section('title', 'إضافة بيانات شخصية')
@section('page_title', 'السجلات الأساسية')

@section('content')
    <div class="container-fluid mx-auto px-4 py-8" dir="rtl">
        {{-- رأس الصفحة --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div class="flex items-center gap-4">
                <div
                    class="w-16 h-16 bg-blue-600 rounded-3xl shadow-xl shadow-blue-100 flex items-center justify-center text-white">
                    <i class="fas fa-user-plus text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-800 header-font mb-1">إضافة سجل جديد</h2>
                    <p class="text-slate-500 text-sm">يرجى إدخال البيانات الشخصية والمهنية بدقة عالية.</p>
                </div>
            </div>
            <a href="{{ route('system.personal-data.index') }}"
                class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl font-bold hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2">
                <i class="fas fa-arrow-right"></i> عودة للقائمة
            </a>
        </div>

        <form action="{{ route('system.personal-data.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                {{-- الجانب الأيمن(الرئيسي): جميع حقول البيانات --}}
                    <div class="lg:col-span-8 space-y-8">
                        {{-- المعلومات المدنية والشخصية (بما فيها الرقم القومي والهواتف) --}}
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
                                    <input type="text" name="name" required class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all font-bold text-slate-700" placeholder="كما هو في الهوية">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 mb-2 block">الاسم الجهادي</label>
                                    <input type="text" name="jihadi_name" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all" placeholder="الكنية">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 mb-2 block">تاريخ الميلاد</label>
                                    <input type="date" name="date_of_birth_gregorian" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 mb-2 block">فصيلة الدم</label>
                                    <select name="blood_type_id" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all appearance-none">
                                        <option value="">اختر الفصيلة...</option>
                                        @foreach($bloodTypes as $id => $type)
                                            <option value="{{ $id }}">{{ $type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 mb-2 block">الحالة الاجتماعية</label>
                                    <select name="marital_status_id" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all appearance-none">
                                        <option value="">اختر الحالة...</option>
                                        @foreach($maritalStatuses as $id => $status)
                                            <option value="{{ $id }}">{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 mb-2 block">المؤهل الدراسي</label>
                                    <select name="qualification_id" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all appearance-none">
                                        <option value="">اختر المؤهل...</option>
                                        @foreach($qualifications as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- الحقول المنقولة من قسم الصورة --}}
                                <div>
                                    <label class="text-xs font-bold text-slate-400 mb-2 block">الرقم القومي (الهوية) *</label>
                                    <div class="relative">
                                        <i class="fas fa-id-card absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                                        <input type="text" name="national_id_number" required class="w-full pl-10 pr-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all font-mono text-sm" placeholder="11 خانة">
                                    </div>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 mb-2 block">رقم الهاتف 1 *</label>
                                    <div class="relative">
                                        <i class="fas fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                                        <input type="text" name="phone_1" required class="w-full pl-10 pr-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all font-mono text-sm" placeholder="770 000 000">
                                    </div>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 mb-2 block">رقم الهاتف 2</label>
                                    <div class="relative">
                                        <i class="fas fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                                        <input type="text" name="phone_2" class="w-full pl-10 pr-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all font-mono text-sm" placeholder="771 000 000">
                                    </div>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 mb-2 block">رقم الهاتف 3</label>
                                    <div class="relative">
                                        <i class="fas fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                                        <input type="text" name="phone_3" class="w-full pl-10 pr-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all font-mono text-sm" placeholder="772 000 000">
                                    </div>
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
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 mb-2 block">الرقم العسكري</label>
                                    <input type="text" name="military_number" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all font-mono" placeholder="000000">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 mb-2 block">الرقم الجهادي</label>
                                    <input type="text" name="jihadist_number" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all font-mono" placeholder="0000">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 mb-2 block">تصنيف الرقم</label>
                                    <select name="number_classification_id" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all appearance-none">
                                        <option value="">اختر التصنيف...</option>
                                        @foreach($numberClassifications as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 mb-2 block">المنطقة العسكرية</label>
                                    <select name="military_district_id" id="military_district_id" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all appearance-none">
                                        <option value="">اختر المنطقة...</option>
                                        @foreach($militaryDistricts as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 mb-2 block">الشعبة</label>
                                    <select name="division_id" id="division_id" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all appearance-none">
                                        <option value="">اختر الشعبة...</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 mb-2 block">الوحدة</label>
                                    <input type="text" name="unit" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all" placeholder="اسم الوحدة">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 mb-2 block">الوظيفة</label>
                                    <input type="text" name="job" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all" placeholder="المسمى الوظيفي">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 mb-2 block">التخصص الوظيفي</label>
                                    <input type="text" name="job_specialty" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all" placeholder="التخصص">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 mb-2 block">مكان العمل</label>
                                    <input type="text" name="workplace" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all" placeholder="المقر">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 mb-2 block">حالة الوظيفة</label>
                                    <select name="job_status_id" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all appearance-none">
                                        <option value="">اختر الحالة...</option>
                                        @foreach($jobStatuses as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 mb-2 block">الحالة المالية</label>
                                    <select name="financial_status_id" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all appearance-none">
                                        <option value="">اختر الحالة...</option>
                                        @foreach($financialStatuses as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
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
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 mb-2 block">المديرية</label>
                                    <select name="place_of_birth_directorate_id" id="place_of_birth_directorate_id" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all appearance-none">
                                        <option value="">اختر المديرية...</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 mb-2 block">العزلة/المنطقة</label>
                                    <select name="place_of_birth_sub_area_id" id="place_of_birth_sub_area_id" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all appearance-none">
                                        <option value="">اختر العزلة...</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 mb-2 block">القرية/المحل</label>
                                    <select name="place_of_birth_village_id" id="place_of_birth_village_id" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all appearance-none">
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
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 mb-2 block">المديرية</label>
                                    <select name="directorate_id" id="directorate_id" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-purple-100 transition-all appearance-none">
                                        <option value="">اختر المديرية...</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 mb-2 block">العزلة/المنطقة</label>
                                    <select name="sub_area_id" id="sub_area_id" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-purple-100 transition-all appearance-none">
                                        <option value="">اختر العزلة...</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 mb-2 block">القرية/المحل</label>
                                    <select name="village_id" id="village_id" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-purple-100 transition-all appearance-none">
                                        <option value="">اختر القرية...</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- أزرار الحفظ --}}
                        <div class="flex items-center justify-end gap-4 pb-12">
                            <button type="button" onclick="history.back()" class="px-8 py-3 text-slate-400 font-bold hover:text-slate-600 transition-colors">إلغاء</button>
                            <button type="submit" class="px-10 py-4 bg-blue-600 text-white rounded-[2rem] font-bold hover:bg-blue-700 transition-all shadow-xl shadow-blue-100 flex items-center gap-2">
                                <i class="fas fa-check-circle"></i> حفظ البيانات
                            </button>
                        </div>
                    </div>

                    {{-- الجانب الأيسر (الصورة الشخصية) --}}
                    <div class="lg:col-span-4 space-y-8">
                        <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 p-8 text-center relative overflow-hidden sticky top-8">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-full -mr-16 -mt-16 opacity-50"></div>

                            <div class="relative z-10">
                                <div class="w-48 h-48 mx-auto mb-6 relative group">
                                    <img id="imagePreview"
                                        src="https://ui-avatars.com/api/?name=User&background=f8fafc&color=4361ee&size=200"
                                        class="w-full h-full object-cover rounded-[2rem] border-4 border-white shadow-2xl transition-transform group-hover:scale-105">
                                    <label for="main_profile_photo"
                                        class="absolute -bottom-2 -left-2 w-12 h-12 bg-blue-600 text-white rounded-2xl border-4 border-white flex items-center justify-center shadow-lg cursor-pointer hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-camera"></i>
                                    </label>
                                    <input type="file" id="main_profile_photo" name="main_profile_photo" class="hidden" accept="image/*">
                                </div>
                                <h5 class="text-lg font-bold text-slate-800 header-font mb-2">الصورة الشخصية</h5>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">صورة رسمية بخلفية واضحة<br> (اضغط على أيقونة الكاميرا لرفع صورة)</p>
                                <div class="mt-6 text-xs text-slate-400">
                                    <i class="fas fa-info-circle"></i> الصيغ المسموحة: JPG, PNG
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Profile Photo Preview
            $('#main_profile_photo').on('change', function() {
                const [file] = this.files
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#imagePreview').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Cascading Dropdowns Logic
            function setupCascading(govId, dirId, subId, vilId) {
                const $gov = $(`#${govId}`);
                const $dir = $(`#${dirId}`);
                const $sub = $(`#${subId}`);
                const $vil = $(`#${vilId}`);

                function clearDropdown($el, placeholder) {
                    $el.html(`<option value="">${placeholder}</option>`);
                }

                function loadOptions(url, $target, placeholder) {
                    $target.prop('disabled', true);
                    $target.html(`<option value="">جاري التحميل...</option>`);

                    fetch(url)
                        .then(r => r.json())
                        .then(data => {
                            $target.html(`<option value="">${placeholder}</option>`);
                            Object.keys(data).forEach(id => {
                                $target.append(`<option value="${id}">${data[id]}</option>`);
                            });
                            $target.prop('disabled', false);
                        })
                        .catch(err => {
                            console.error("Error loading options:", err);
                            $target.html('<option value="">خطأ في التحميل</option>').prop('disabled', false);
                        });
                }

                $gov.on('change', function() {
                    const id = $(this).val();
                    clearDropdown($dir, 'اختر المديرية...');
                    clearDropdown($sub, 'اختر العزلة...');
                    clearDropdown($vil, 'اختر القرية...');
                    if (id) loadOptions(`/api/geo/directorates/${id}`, $dir, 'اختر المديرية...');
                });

                $dir.on('change', function() {
                    const id = $(this).val();
                    clearDropdown($sub, 'اختر العزلة...');
                    clearDropdown($vil, 'اختر القرية...');
                    if (id) loadOptions(`/api/geo/sub-areas/${id}`, $sub, 'اختر العزلة...');
                });

                $sub.on('change', function() {
                    const id = $(this).val();
                    clearDropdown($vil, 'اختر القرية...');
                    if (id) loadOptions(`/api/geo/villages/${id}`, $vil, 'اختر القرية...');
                });
            }

            // Military District -> Division
            $('#military_district_id').on('change', function() {
                const id = $(this).val();
                const $div = $('#division_id');
                $div.prop('disabled', true).html('<option value="">جاري التحميل...</option>');

                if (!id) {
                    $div.html('<option value="">اختر الشعبة...</option>').prop('disabled', false);
                    return;
                }

                fetch(`/api/geo/divisions/${id}`)
                    .then(res => res.json())
                    .then(data => {
                        $div.html('<option value="">اختر الشعبة...</option>');
                        Object.keys(data).forEach(id => {
                            $div.append(`<option value="${id}">${data[id]}</option>`);
                        });
                        $div.prop('disabled', false);
                    })
                    .catch(err => {
                        $div.html('<option value="">خطأ في التحميل</option>').prop('disabled', false);
                    });
            });

            // Initialize Geo Cascading
            setupCascading('place_of_birth_governorate_id', 'place_of_birth_directorate_id', 'place_of_birth_sub_area_id', 'place_of_birth_village_id');
            setupCascading('governorate_id', 'directorate_id', 'sub_area_id', 'village_id');
        });
    </script>
@endpush