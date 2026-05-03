@extends('layouts.app')

@section('title', 'تعديل بيانات الموقوف')
@section('page_title', 'النظام')

@section('content')
    <div class="container-fluid mx-auto px-4 py-8" dir="rtl">
        {{-- رأس الصفحة --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-amber-500 rounded-3xl shadow-xl shadow-amber-100 flex items-center justify-center text-white">
                    <i class="fas fa-edit text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-800 header-font mb-1">تعديل بيانات الموقوف</h2>
                    <p class="text-slate-500 text-sm">تعديل بيانات قضية التوقيف الخاصة بـ: <span class="text-amber-600 font-bold">{{ $arrest->personalData->name }}</span></p>
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

        <form id="arrestForm" action="{{ route('system.arrests.update', $arrest) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- البيانات الشخصية --}}
            <div class="max-w-4xl mx-auto mb-8">
                <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
                    <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50">
                        <h5 class="text-lg font-bold text-slate-800 header-font flex items-center gap-3">
                            <span class="w-2 h-6 bg-amber-500 rounded-full"></span>
                            البيانات الشخصية
                        </h5>
                    </div>
                    <div class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="md:col-span-1">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">الاسم *</label>
                                <input type="text" id="personal_data_search" class="w-full px-6 py-3 bg-slate-50 border-0 rounded-2xl mb-2 focus:ring-2 focus:ring-amber-100 transition-all text-sm font-bold text-slate-700" placeholder="ابحث عن الاسم..." autocomplete="off">
                                <select id="personal_data_id" name="personal_data_id" required 
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700 appearance-none cursor-pointer @error('personal_data_id') ring-2 ring-rose-100 @enderror">
                                    <option value="">-- اختر الاسم --</option>
                                    @foreach($personalDataList as $id => $name)
                                        <option value="{{ $id }}" @selected(old('personal_data_id', $arrest->personal_data_id) == $id)>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('personal_data_id')
                                    <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                        <!-- Standardized Info-Card -->
                        <div id="personal_info_box" class="mt-4 d-none">
                            <div class="bg-blue-50 border border-blue-100 rounded-[2rem] p-6 text-xs shadow-sm">
                                <h6 class="font-bold text-blue-700 mb-4 flex items-center gap-2">
                                    <i class="fas fa-info-circle"></i> بيانات الشخص المختار:
                                </h6>
                                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 text-slate-700">
                                    <div class="flex flex-col"><span class="text-slate-400">الاسم الجهادي:</span> <span id="info_jihadi_name" class="font-bold">-</span></div>
                                    <div class="flex flex-col"><span class="text-slate-400">الرقم العسكري:</span> <span id="info_military_number" class="font-bold">-</span></div>
                                    <div class="flex flex-col"><span class="text-slate-400">رقم الهوية:</span> <span id="info_id_number" class="font-bold">-</span></div>
                                    <div class="flex flex-col"><span class="text-slate-400">فصيلة الدم:</span> <span id="info_blood_type" class="text-rose-600 font-extrabold">-</span></div>
                                    <div class="flex flex-col"><span class="text-slate-400">تاريخ الميلاد:</span> <span id="info_dob" class="font-bold">-</span></div>
                                    <div class="flex flex-col"><span class="text-slate-400">الحالة الاجتماعية:</span> <span id="info_marital_status" class="font-bold">-</span></div>
                                    <div class="lg:col-span-2 flex flex-col"><span class="text-slate-400">مكان الميلاد:</span> <span id="info_pob" class="font-bold">-</span></div>
                                    <div class="lg:col-span-2 flex flex-col"><span class="text-slate-400">رقم الهاتف 1:</span> <span id="info_phone_1" class="font-bold">-</span></div>
                                    <div class="lg:col-span-2 flex flex-col"><span class="text-slate-400">رقم الهاتف 2:</span> <span id="info_phone_2" class="font-bold">-</span></div>
                                    <div class="md:col-span-3 lg:col-span-4 border-top pt-2 flex flex-col"><span class="text-slate-400">العنوان:</span> <span id="info_address" class="font-bold">-</span></div>
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
                            <span class="w-2 h-6 bg-amber-500 rounded-full"></span>
                            تفاصيل الموقف
                        </h5>
                    </div>
                    <div class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-1">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">تاريخ الموقف</label>
                                <input type="date" name="arrest_date" 
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700 @error('arrest_date') ring-2 ring-rose-100 @enderror" 
                                    value="{{ old('arrest_date', (is_string($arrest->arrest_date) ? \Carbon\Carbon::parse($arrest->arrest_date)->format('Y-m-d') : ($arrest->arrest_date ? $arrest->arrest_date->format('Y-m-d') : ''))) }}">
                                @error('arrest_date')
                                    <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-1">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">نوع القضية</label>
                                <select name="case_type_id" 
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700 appearance-none cursor-pointer">
                                    <option value="">-- اختر نوع القضية --</option>
                                    @foreach($caseTypes as $id => $type)
                                        <option value="{{ $id }}" @selected(old('case_type_id', $arrest->case_type_id) == $id)>{{ $type }}</option>
                                    @endforeach
                                </select>
                                @error('case_type_id')
                                    <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-1">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">الموقع</label>
                                <input type="text" name="location" 
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700 @error('location') ring-2 ring-rose-100 @enderror" 
                                    placeholder="موقع القضية..." value="{{ old('location', $arrest->location) }}">
                                @error('location')
                                    <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-1">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">مكان التوقيف</label>
                                <input type="text" name="arrest_location" 
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700 @error('arrest_location') ring-2 ring-rose-100 @enderror" 
                                    placeholder="المكان الذي تم التوقيف فيه..." value="{{ old('arrest_location', $arrest->arrest_location) }}">
                                @error('arrest_location')
                                    <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">تفاصيل القضية</label>
                                <textarea name="case_details" rows="3"
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700 @error('case_details') ring-2 ring-rose-100 @enderror" 
                                    placeholder="أدخل تفاصيل القضية...">{{ old('case_details', $arrest->case_details) }}</textarea>
                                @error('case_details')
                                    <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">المضبوطات</label>
                                <textarea name="seized_items" rows="3"
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700 @error('seized_items') ring-2 ring-rose-100 @enderror" 
                                    placeholder="سرد المضبوطات إن وجدت...">{{ old('seized_items', $arrest->seized_items) }}</textarea>
                                @error('seized_items')
                                    <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">نتائج متابعة القضية</label>
                                <textarea name="case_follow_up_results" rows="3"
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700 @error('case_follow_up_results') ring-2 ring-rose-100 @enderror" 
                                    placeholder="نتائج المتابعة...">{{ old('case_follow_up_results', $arrest->case_follow_up_results) }}</textarea>
                                @error('case_follow_up_results')
                                    <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">معلومات ذات صلة</label>
                                <textarea name="related_information" rows="3"
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700 @error('related_information') ring-2 ring-rose-100 @enderror" 
                                    placeholder="معلومات أخرى...">{{ old('related_information', $arrest->related_information) }}</textarea>
                                @error('related_information')
                                    <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-1">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">حالة القضية</label>
                                <select name="status" 
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700 appearance-none cursor-pointer">
                                    <option value="">-- اختر الحالة --</option>
                                    <option value="معتقل" @selected(old('status', $arrest->status) == 'معتقل')>معتقل</option>
                                    <option value="مفرج عنه" @selected(old('status', $arrest->status) == 'مفرج عنه')>مفرج عنه</option>
                                    <option value="قيد التحقيق" @selected(old('status', $arrest->status) == 'قيد التحقيق')>قيد التحقيق</option>
                                    <option value="محال للمحكمة" @selected(old('status', $arrest->status) == 'محال للمحكمة')>محال للمحكمة</option>
                                </select>
                                @error('status')
                                    <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-1">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">مدة الفترة</label>
                                <input type="text" name="duration_period" 
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700 @error('duration_period') ring-2 ring-rose-100 @enderror" 
                                    placeholder="مثال: 3 أشهر" value="{{ old('duration_period', $arrest->duration_period) }}">
                                @error('duration_period')
                                    <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">الخلاصة</label>
                                <textarea name="conclusion" rows="3"
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700 @error('conclusion') ring-2 ring-rose-100 @enderror" 
                                    placeholder="خلاصة القضية...">{{ old('conclusion', $arrest->conclusion) }}</textarea>
                                @error('conclusion')
                                    <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">تقارير التحقيق</label>
                                <textarea name="investigation_reports" rows="4"
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700 @error('investigation_reports') ring-2 ring-rose-100 @enderror" 
                                    placeholder="تفاصيل تقارير التحقيق...">{{ old('investigation_reports', $arrest->investigation_reports) }}</textarea>
                                @error('investigation_reports')
                                    <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">إضافة مرفقات جديدة</label>
                                <input type="file" name="attachments[]" multiple
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700 @error('attachments.*') ring-2 ring-rose-100 @enderror">
                                <p class="text-xs text-slate-400 mt-2 px-2">يمكنك رفع ملفات متعددة (PDF, DOC, DOCX, JPG, PNG, XLS, XLSX - حد أقصى 10MB لكل ملف)</p>
                                @error('attachments.*')
                                    <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            @if($arrest->attachments && count($arrest->attachments) > 0)
                                <div class="md:col-span-2 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                    <label class="text-xs font-bold text-slate-400 mb-3 block px-2">المرفقات الحالية</label>
                                    <div class="flex flex-col gap-2">
                                        @foreach($arrest->attachments as $index => $path)
                                            <div class="flex items-center justify-between bg-white px-4 py-3 rounded-xl shadow-sm border border-slate-100">
                                                <a href="{{ Storage::url($path) }}" target="_blank" class="text-blue-600 hover:text-blue-800 font-bold text-sm flex items-center gap-2">
                                                    <i class="fas fa-file-alt"></i> مرفق #{{ $index + 1 }}
                                                </a>
                                                <button type="submit" form="deleteAttachmentForm_{{ $index }}" class="text-rose-400 hover:text-rose-600 transition-colors" onclick="return confirm('هل تريد حذف هذا المرفق؟')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="md:col-span-1">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">تاريخ الإفراج</label>
                                <input type="date" name="release_date" 
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700 @error('release_date') ring-2 ring-rose-100 @enderror" 
                                    value="{{ old('release_date', (is_string($arrest->release_date) ? \Carbon\Carbon::parse($arrest->release_date)->format('Y-m-d') : ($arrest->release_date ? $arrest->release_date->format('Y-m-d') : ''))) }}">
                                @error('release_date')
                                    <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-1">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">سبب الإفراج</label>
                                <textarea name="release_reason" rows="2"
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700 @error('release_reason') ring-2 ring-rose-100 @enderror" 
                                    placeholder="السبب...">{{ old('release_reason', $arrest->release_reason) }}</textarea>
                                @error('release_reason')
                                    <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">ملاحظات</label>
                                <textarea name="notes" rows="3"
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700 @error('notes') ring-2 ring-rose-100 @enderror" 
                                    placeholder="ملاحظات إضافية...">{{ old('notes', $arrest->notes) }}</textarea>
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
                <button type="submit" class="px-10 py-4 bg-amber-500 text-white rounded-[2rem] font-bold hover:bg-amber-600 transition-all shadow-xl shadow-amber-100 flex items-center gap-2">
                    <i class="fas fa-check-circle"></i> حفظ التعديلات
                </button>
            </div>
        </form>

        {{-- Forms for deleting attachments --}}
        @if($arrest->attachments && count($arrest->attachments) > 0)
            @foreach($arrest->attachments as $index => $path)
                <form id="deleteAttachmentForm_{{ $index }}" action="{{ route('system.arrests.delete-attachment', $arrest) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="file_index" value="{{ $index }}">
                </form>
            @endforeach
        @endif
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
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

        selectBox.addEventListener('change', function() {
            const personId = this.value;
            const infoBox = document.getElementById('personal_info_box');
            
            if (!personId) {
                infoBox.classList.add('d-none');
                return;
            }

            // Standardized fetch
            fetch(`/system/personal-data/details/${personId}`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('info_jihadi_name').textContent = data.jihadi_name || '-';
                    document.getElementById('info_military_number').textContent = data.military_number || '-';
                    document.getElementById('info_id_number').textContent = data.national_id_number || '-';
                    document.getElementById('info_blood_type').textContent = data.blood_type || '-';
                    document.getElementById('info_dob').textContent = data.date_of_birth || '-';
                    document.getElementById('info_marital_status').textContent = data.marital_status || '-';
                    document.getElementById('info_pob').textContent = data.place_of_birth || '-';
                    document.getElementById('info_phone_1').textContent = data.phone_1 || '-';
                    document.getElementById('info_phone_2').textContent = data.phone_2 || '-';
                    document.getElementById('info_address').textContent = data.address || '-';
                    
                    infoBox.classList.remove('d-none');
                })
                .catch(err => {
                    console.error('Error:', err);
                    infoBox.classList.add('d-none');
                });
        });

        // Trigger on load
        if (selectBox.value) {
            selectBox.dispatchEvent(new Event('change'));
        }
    });
    </script>
@endsection
