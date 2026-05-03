@extends('layouts.app')

@section('title', 'إضافة موقوف جديد')
@section('page_title', 'النظام')

@section('content')
    <div class="container-fluid mx-auto px-4 py-8" dir="rtl">
        {{-- رأس الصفحة --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-blue-600 rounded-3xl shadow-xl shadow-blue-100 flex items-center justify-center text-white">
                    <i class="fas fa-plus text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-800 header-font mb-1">إضافة موقوف جديد</h2>
                    <p class="text-slate-500 text-sm">تسجيل بيانات قضية التوقيف ومعلوماتها وارتباطها بالشخص الموقوف.</p>
                </div>
            </div>
            <a href="{{ route('system.arrests.index') }}"
                class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl font-bold hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2">
                <i class="fas fa-arrow-right"></i> عودة للقائمة
            </a>
        </div>

        @if($errors->any())
            <div class="max-w-4xl mx-auto bg-rose-50 border border-rose-100 text-rose-700 px-6 py-4 rounded-2xl mb-8 shadow-sm">
                <div class="flex items-start gap-3">
                    <i class="fas fa-exclamation-triangle text-xl mt-1"></i>
                    <div>
                        <span class="font-bold block mb-1">يرجى تصحيح الأخطاء التالية:</span>
                        <ul class="list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form id="arrestForm" action="{{ route('system.arrests.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- البيانات الشخصية --}}
            <div class="max-w-4xl mx-auto mb-8">
                <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
                    <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50">
                        <h5 class="text-lg font-bold text-slate-800 header-font flex items-center gap-3">
                            <span class="w-2 h-6 bg-blue-600 rounded-full"></span>
                            البيانات الشخصية (تُملأ تلقائيًا)
                        </h5>
                    </div>
                    <div class="p-8">
                        <div class="grid grid-cols-1 gap-6 mb-6">
                            <div class="col-span-1">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">الاسم *</label>
                                <input type="text" id="personal_data_search" class="w-full px-6 py-3 bg-slate-50 border-0 rounded-2xl mb-2 focus:ring-2 focus:ring-blue-100 transition-all text-sm font-bold text-slate-700" placeholder="ابحث عن الاسم..." autocomplete="off">
                                <select id="personal_data_id" name="personal_data_id" required 
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all font-bold text-slate-700 appearance-none cursor-pointer @error('personal_data_id') ring-2 ring-rose-100 @enderror">
                                    <option value="">-- اختر الاسم --</option>
                                    @foreach($personalDataList as $id => $name)
                                        <option value="{{ $id }}" @selected(old('personal_data_id', $selectedPersonalDataId) == $id)>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('personal_data_id')
                                    <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Personal Info Box -->
                        <div id="personal_info_box" class="mt-4 d-none">
                            <div class="bg-slate-50/50 p-6 rounded-[2rem] border border-slate-100">
                                <h6 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                                    <i class="fas fa-info-circle text-blue-600"></i>
                                    تفاصيل الشخص الموقوف
                                </h6>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-3 text-xs">
                                    <div class="flex items-center gap-3 text-slate-600 bg-white p-3 rounded-2xl border border-slate-50">
                                        <span class="font-bold text-slate-400 w-24">الاسم الجهادي:</span>
                                        <span id="info_jihadi_name" class="font-bold text-slate-700">-</span>
                                    </div>
                                    <div class="flex items-center gap-3 text-slate-600 bg-white p-3 rounded-2xl border border-slate-50">
                                        <span class="font-bold text-slate-400 w-24">الرقم العسكري:</span>
                                        <span id="info_military_number" class="font-bold text-slate-700">-</span>
                                    </div>
                                    <div class="flex items-center gap-3 text-slate-600 bg-white p-3 rounded-2xl border border-slate-50">
                                        <span class="font-bold text-slate-400 w-24">فصيلة الدم:</span>
                                        <span id="info_blood_type" class="text-rose-600 font-extrabold">-</span>
                                    </div>
                                    <div class="flex items-center gap-3 text-slate-600 bg-white p-3 rounded-2xl border border-slate-50">
                                        <span class="font-bold text-slate-400 w-24">تاريخ الميلاد:</span>
                                        <span id="info_dob" class="font-bold text-slate-700">-</span>
                                    </div>
                                    <div class="flex items-center gap-3 text-slate-600 bg-white p-3 rounded-2xl border border-slate-50">
                                        <span class="font-bold text-slate-400 w-24">الحالة الاجتماعية:</span>
                                        <span id="info_marital_status" class="font-bold text-slate-700">-</span>
                                    </div>
                                    <div class="flex items-center gap-3 text-slate-600 bg-white p-3 rounded-2xl border border-slate-50">
                                        <span class="font-bold text-slate-400 w-24">مكان الميلاد:</span>
                                        <span id="info_pob" class="font-bold text-slate-700">-</span>
                                    </div>
                                    <div class="flex items-center gap-3 text-slate-600 bg-white p-3 rounded-2xl border border-slate-50">
                                        <span class="font-bold text-slate-400 w-24">رقم الهوية:</span>
                                        <span id="info_id_number" class="font-bold text-slate-700">-</span>
                                    </div>
                                    <div class="flex items-center gap-3 text-slate-600 bg-white p-3 rounded-2xl border border-slate-50">
                                        <span class="font-bold text-slate-400 w-24">هاتف 1:</span>
                                        <span id="info_phone_1" class="font-bold text-slate-700">-</span>
                                    </div>
                                    <div class="flex items-center gap-3 text-slate-600 bg-white p-3 rounded-2xl border border-slate-50">
                                        <span class="font-bold text-slate-400 w-24">هاتف 2:</span>
                                        <span id="info_phone_2" class="font-bold text-slate-700">-</span>
                                    </div>
                                    <div class="flex items-center gap-3 text-slate-600 bg-white p-3 rounded-2xl border border-slate-50 col-span-1 md:col-span-2 mt-2">
                                        <span class="font-bold text-slate-400 w-24">العنوان:</span>
                                        <span id="info_address" class="font-bold text-slate-700">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- تفاصيل الموقوف --}}
            <div class="max-w-4xl mx-auto mb-8">
                <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
                    <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50">
                        <h5 class="text-lg font-bold text-slate-800 header-font flex items-center gap-3">
                            <span class="w-2 h-6 bg-emerald-500 rounded-full"></span>
                            تفاصيل الموقف
                        </h5>
                    </div>
                    <div class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-1">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">تاريخ الموقف</label>
                                <input type="date" name="arrest_date" 
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all font-bold text-slate-700 @error('arrest_date') ring-2 ring-rose-100 @enderror" 
                                    value="{{ old('arrest_date') }}">
                                @error('arrest_date')
                                    <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-1">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">نوع القضية</label>
                                <select name="case_type_id" 
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all font-bold text-slate-700 appearance-none cursor-pointer">
                                    <option value="">-- اختر نوع القضية --</option>
                                    @foreach($caseTypes as $id => $type)
                                        <option value="{{ $id }}" @selected(old('case_type_id') == $id)>{{ $type }}</option>
                                    @endforeach
                                </select>
                                @error('case_type_id')
                                    <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-1">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">الموقع</label>
                                <input type="text" name="location" 
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all font-bold text-slate-700 @error('location') ring-2 ring-rose-100 @enderror" 
                                    placeholder="موقع القضية..." value="{{ old('location') }}">
                                @error('location')
                                    <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-1">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">مكان التوقيف</label>
                                <input type="text" name="arrest_location" 
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all font-bold text-slate-700 @error('arrest_location') ring-2 ring-rose-100 @enderror" 
                                    placeholder="المكان الذي تم التوقيف فيه..." value="{{ old('arrest_location') }}">
                                @error('arrest_location')
                                    <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">تفاصيل القضية</label>
                                <textarea name="case_details" rows="3"
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all font-bold text-slate-700 @error('case_details') ring-2 ring-rose-100 @enderror" 
                                    placeholder="أدخل تفاصيل القضية...">{{ old('case_details') }}</textarea>
                                @error('case_details')
                                    <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">المضبوطات</label>
                                <textarea name="seized_items" rows="3"
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all font-bold text-slate-700 @error('seized_items') ring-2 ring-rose-100 @enderror" 
                                    placeholder="سرد المضبوطات إن وجدت...">{{ old('seized_items') }}</textarea>
                                @error('seized_items')
                                    <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">نتائج متابعة القضية</label>
                                <textarea name="case_follow_up_results" rows="3"
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all font-bold text-slate-700 @error('case_follow_up_results') ring-2 ring-rose-100 @enderror" 
                                    placeholder="نتائج المتابعة...">{{ old('case_follow_up_results') }}</textarea>
                                @error('case_follow_up_results')
                                    <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">معلومات ذات صلة</label>
                                <textarea name="related_information" rows="3"
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all font-bold text-slate-700 @error('related_information') ring-2 ring-rose-100 @enderror" 
                                    placeholder="معلومات أخرى...">{{ old('related_information') }}</textarea>
                                @error('related_information')
                                    <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-1">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">حالة القضية</label>
                                <select name="status" 
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all font-bold text-slate-700 appearance-none cursor-pointer">
                                    <option value="">-- اختر الحالة --</option>
                                    <option value="معتقل" @selected(old('status') == 'معتقل')>معتقل</option>
                                    <option value="مفرج عنه" @selected(old('status') == 'مفرج عنه')>مفرج عنه</option>
                                    <option value="قيد التحقيق" @selected(old('status') == 'قيد التحقيق')>قيد التحقيق</option>
                                    <option value="محال للمحكمة" @selected(old('status') == 'محال للمحكمة')>محال للمحكمة</option>
                                </select>
                                @error('status')
                                    <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-1">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">مدة الفترة</label>
                                <input type="text" name="duration_period" 
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all font-bold text-slate-700 @error('duration_period') ring-2 ring-rose-100 @enderror" 
                                    placeholder="مثال: 3 أشهر" value="{{ old('duration_period') }}">
                                @error('duration_period')
                                    <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">الخلاصة</label>
                                <textarea name="conclusion" rows="3"
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all font-bold text-slate-700 @error('conclusion') ring-2 ring-rose-100 @enderror" 
                                    placeholder="خلاصة القضية...">{{ old('conclusion') }}</textarea>
                                @error('conclusion')
                                    <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">تقارير التحقيق</label>
                                <textarea name="investigation_reports" rows="4"
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all font-bold text-slate-700 @error('investigation_reports') ring-2 ring-rose-100 @enderror" 
                                    placeholder="تفاصيل تقارير التحقيق...">{{ old('investigation_reports') }}</textarea>
                                @error('investigation_reports')
                                    <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">المرفقات</label>
                                <input type="file" name="attachments[]" multiple
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all font-bold text-slate-700 @error('attachments.*') ring-2 ring-rose-100 @enderror">
                                <p class="text-xs text-slate-400 mt-2 px-2">يمكنك رفع ملفات متعددة (PDF, DOC, DOCX, JPG, PNG, XLS, XLSX - حد أقصى 10MB لكل ملف)</p>
                                @error('attachments.*')
                                    <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-1">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">تاريخ الإفراج</label>
                                <input type="date" name="release_date" 
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all font-bold text-slate-700 @error('release_date') ring-2 ring-rose-100 @enderror" 
                                    value="{{ old('release_date') }}">
                                @error('release_date')
                                    <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-1">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">سبب الإفراج</label>
                                <textarea name="release_reason" rows="2"
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all font-bold text-slate-700 @error('release_reason') ring-2 ring-rose-100 @enderror" 
                                    placeholder="السبب...">{{ old('release_reason') }}</textarea>
                                @error('release_reason')
                                    <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">ملاحظات</label>
                                <textarea name="notes" rows="3"
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all font-bold text-slate-700 @error('notes') ring-2 ring-rose-100 @enderror" 
                                    placeholder="ملاحظات إضافية...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- أزرار الإجراءات --}}
            <div class="max-w-4xl mx-auto mb-10 flex items-center justify-end gap-4">
                <button type="button" onclick="history.back()" class="px-8 py-3 text-slate-400 font-bold hover:text-slate-600 transition-colors">إلغاء</button>
                <button type="submit" class="px-10 py-4 bg-blue-600 text-white rounded-[2rem] font-bold hover:bg-blue-700 transition-all shadow-xl shadow-blue-100 flex items-center gap-2">
                    <i class="fas fa-check-circle"></i> حفظ البيانات
                </button>
            </div>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search functionality for personal data dropdown
        const searchInput = document.getElementById('personal_data_search');
        const selectBox = document.getElementById('personal_data_id');
        const options = Array.from(selectBox.options).slice(1);

        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            
            selectBox.innerHTML = '<option value="">-- اختر الاسم --</option>';
            
            options.forEach(option => {
                if (option.text.toLowerCase().includes(searchTerm)) {
                    selectBox.appendChild(option.cloneNode(true));
                }
            });
        });

        searchInput.addEventListener('focus', function() {
            selectBox.innerHTML = '<option value="">-- اختر الاسم --</option>';
            options.forEach(option => {
                selectBox.appendChild(option.cloneNode(true));
            });
        });

        // Auto-populate personal data when selecting a person
        selectBox.addEventListener('change', function() {
            const personId = this.value;
            const infoBox = document.getElementById('personal_info_box');
            
            if (!personId) {
                infoBox.classList.add('d-none');
                return;
            }

            // Fetch personal data via AJAX
            fetch(`/system/personal-data/details/${personId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('info_jihadi_name').textContent = data.jihadi_name;
                    document.getElementById('info_military_number').textContent = data.military_number;
                    document.getElementById('info_blood_type').textContent = data.blood_type;
                    document.getElementById('info_dob').textContent = data.date_of_birth;
                    document.getElementById('info_marital_status').textContent = data.marital_status;
                    document.getElementById('info_pob').textContent = data.place_of_birth;
                    document.getElementById('info_id_number').textContent = data.national_id_number;
                    document.getElementById('info_phone_1').textContent = data.phone_1;
                    document.getElementById('info_phone_2').textContent = data.phone_2;
                    document.getElementById('info_address').textContent = data.address;

                    infoBox.classList.remove('d-none');
                })
                .catch(error => {
                    console.error('Error fetching personal data:', error);
                    alert('حدث خطأ أثناء جلب البيانات الشخصية');
                });
        });

        // Trigger change event if pre-selected
        if (selectBox.value) {
            selectBox.dispatchEvent(new Event('change'));
        }
    });
    </script>
@endsection
