@extends('layouts.app')

@section('title', 'تعديل بيانات الزوجة')
@section('page_title', 'النظام')

@section('content')
    <div class="container-fluid mx-auto px-4 py-8" dir="rtl">
        {{-- رأس الصفحة --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-amber-500 rounded-3xl shadow-xl shadow-amber-100 flex items-center justify-center text-white">
                    <i class="fas fa-user-edit text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-800 header-font mb-1">تعديل بيانات الزوجة</h2>
                    <p class="text-slate-500 text-sm">تحديث معلومات الزوجة: <span class="text-amber-600 font-bold">{{ $wife->name }}</span></p>
                </div>
            </div>
            <a href="{{ route('system.wives.show', $wife->id) }}"
                class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl font-bold hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2">
                <i class="fas fa-arrow-right"></i> عودة للعرض
            </a>
        </div>

        <form action="{{ route('system.wives.update', $wife->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- البيانات الأساسية --}}
            <div class="max-w-4xl mx-auto mb-8">
                <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
                    <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50">
                        <h5 class="text-lg font-bold text-slate-800 header-font flex items-center gap-3">
                            <span class="w-2 h-6 bg-amber-500 rounded-full"></span>
                            البيانات الأساسية
                        </h5>
                    </div>
                    <div class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            {{-- اختيار الزوج --}}
                            <div class="md:col-span-2">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">الزوج *</label>
                                <div class="relative">
                                    <select id="personal_data_id" name="personal_data_id" required 
                                        class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700 appearance-none cursor-pointer @error('personal_data_id') ring-2 ring-rose-100 @enderror">
                                        <option value="">-- اختر الزوج --</option>
                                        @foreach($personalDataList as $id => $name)
                                            <option value="{{ $id }}" {{ (old('personal_data_id') ?? $wife->personal_data_id) == $id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('personal_data_id')
                                    <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- تفاصيل الزوج (تظهر عند الاختيار) --}}
                            <div id="husband_details_container" class="md:col-span-2 hidden mb-4">
                                <div class="bg-blue-50 border border-blue-100 rounded-[2rem] p-6 text-sm">
                                    <h6 class="font-bold text-blue-700 mb-4 flex items-center gap-2">
                                        <i class="fas fa-info-circle"></i> بيانات الزوج المختار:
                                    </h6>
                                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                        <div><span class="text-slate-400">الاسم الجهادي:</span> <span id="h_jihadi_name" class="font-bold text-slate-700 block">-</span></div>
                                        <div><span class="text-slate-400">الرقم العسكري:</span> <span id="h_military_number" class="font-bold text-slate-700 block">-</span></div>
                                        <div><span class="text-slate-400">رقم الهوية:</span> <span id="h_national_id" class="font-bold text-slate-700 block">-</span></div>
                                        <div><span class="text-slate-400">فصيلة الدم:</span> <span id="h_blood_type" class="font-bold text-slate-700 block">-</span></div>
                                        <div><span class="text-slate-400">تاريخ الميلاد:</span> <span id="h_dob" class="font-bold text-slate-700 block">-</span></div>
                                        <div><span class="text-slate-400">الحالة الاجتماعية:</span> <span id="h_marital" class="font-bold text-slate-700 block">-</span></div>
                                        <div class="lg:col-span-2"><span class="text-slate-400">مكان الميلاد:</span> <span id="h_birth_place" class="font-bold text-slate-700 block">-</span></div>
                                        <div class="lg:col-span-2"><span class="text-slate-400">رقم الهاتف 1:</span> <span id="h_phone1" class="font-bold text-slate-700 block">-</span></div>
                                        <div class="lg:col-span-2"><span class="text-slate-400">رقم الهاتف 2:</span> <span id="h_phone2" class="font-bold text-slate-700 block">-</span></div>
                                        <div class="md:col-span-3 lg:col-span-4"><span class="text-slate-400">العنوان:</span> <span id="h_address" class="font-bold text-slate-700 block">-</span></div>
                                    </div>
                                </div>
                            </div>

                            {{-- اسم الزوجة --}}
                            <div class="md:col-span-1">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">اسم الزوجة *</label>
                                <input type="text" name="name" required 
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700 @error('name') ring-2 ring-rose-100 @enderror" 
                                    placeholder="الاسم الكامل للزوجة..." value="{{ old('name') ?? $wife->name }}">
                                @error('name')
                                    <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- حالة الزوجة --}}
                            <div class="md:col-span-1">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">الحالة *</label>
                                <select name="status" required 
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700 appearance-none cursor-pointer">
                                    <option value="مستمرة" {{ (old('status') ?? $wife->status) == 'مستمرة' ? 'selected' : '' }}>مستمرة</option>
                                    <option value="متوفية" {{ (old('status') ?? $wife->status) == 'متوفية' ? 'selected' : '' }}>متوفية</option>
                                    <option value="مطلقة" {{ (old('status') ?? $wife->status) == 'مطلقة' ? 'selected' : '' }}>مطلقة</option>
                                    <option value="أخرى" {{ (old('status') ?? $wife->status) == 'أخرى' ? 'selected' : '' }}>أخرى</option>
                                </select>
                                @error('status')
                                    <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- رقم الهوية --}}
                            <div class="md:col-span-1">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">رقم الهوية</label>
                                <input type="text" name="wife_id_number" 
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700 font-mono @error('wife_id_number') ring-2 ring-rose-100 @enderror" 
                                    placeholder="أدخل رقم الهوية" value="{{ old('wife_id_number') ?? $wife->wife_id_number }}">
                                @error('wife_id_number')
                                    <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- تاريخ الميلاد --}}
                            <div class="md:col-span-1">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">تاريخ الميلاد</label>
                                <input type="date" name="wife_birth_date" 
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700 @error('wife_birth_date') ring-2 ring-rose-100 @enderror" 
                                    value="{{ old('wife_birth_date') ?? $wife->wife_birth_date }}">
                                @error('wife_birth_date')
                                    <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- رقم الهاتف --}}
                            <div class="md:col-span-2">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">رقم الهاتف</label>
                                <input type="text" name="wife_phone" 
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700 font-mono @error('wife_phone') ring-2 ring-rose-100 @enderror" 
                                    placeholder="أدخل رقم الهاتف" value="{{ old('wife_phone') ?? $wife->wife_phone }}">
                                @error('wife_phone')
                                    <p class="text-rose-500 text-xs mt-2 px-2 font-bold">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- معلومات الموقع --}}
            <div class="max-w-4xl mx-auto mb-8">
                <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
                    <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50">
                        <h5 class="text-lg font-bold text-slate-800 header-font flex items-center gap-3">
                            <span class="w-2 h-6 bg-emerald-500 rounded-full"></span>
                            معلومات الموقع
                        </h5>
                    </div>
                    <div class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- المحافظة --}}
                            <div class="md:col-span-1">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">المحافظة</label>
                                <select id="governorate_id" name="governorate_id" 
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all font-bold text-slate-700 appearance-none cursor-pointer">
                                    <option value="">-- اختر المحافظة --</option>
                                    @foreach($governorates as $id => $name)
                                        <option value="{{ $id }}" {{ (old('governorate_id') ?? $wife->governorate_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- المديرية --}}
                            <div class="md:col-span-1">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">المديرية</label>
                                <select id="directorate_id" name="directorate_id" data-selected="{{ old('directorate_id') ?? $wife->directorate_id }}"
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all font-bold text-slate-700 appearance-none cursor-pointer">
                                    <option value="">-- اختر المديرية --</option>
                                </select>
                            </div>

                            {{-- المنطقة الفرعية --}}
                            <div class="md:col-span-1">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">العزلة / المنطقة</label>
                                <select id="sub_area_id" name="sub_area_id" data-selected="{{ old('sub_area_id') ?? $wife->sub_area_id }}"
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all font-bold text-slate-700 appearance-none cursor-pointer">
                                    <option value="">-- اختر المنطقة --</option>
                                </select>
                            </div>

                            {{-- القرية --}}
                            <div class="md:col-span-1">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">القرية / الحي</label>
                                <select id="village_id" name="village_id" data-selected="{{ old('village_id') ?? $wife->village_id }}"
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all font-bold text-slate-700 appearance-none cursor-pointer">
                                    <option value="">-- اختر القرية / الحي --</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- أزرار الإجراءات --}}
            <div class="max-w-4xl mx-auto mb-10 flex items-center justify-end gap-4">
                <a href="{{ route('system.wives.show', $wife->id) }}" class="px-8 py-3 text-slate-400 font-bold hover:text-slate-600 transition-colors">إلغاء</a>
                <button type="submit" class="px-10 py-4 bg-amber-500 text-white rounded-[2rem] font-bold hover:bg-amber-600 transition-all shadow-xl shadow-amber-100 flex items-center gap-2">
                    <i class="fas fa-check-circle"></i> حفظ التعديلات
                </button>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            const husbandSelect = $('#personal_data_id');
            const husbandContainer = $('#husband_details_container');

            // Husband details retrieval
            husbandSelect.on('change', function () {
                const husbandId = $(this).val();
                if (!husbandId) {
                    husbandContainer.addClass('hidden');
                    return;
                }

                fetch(`/system/wives/person-details/${husbandId}`)
                    .then(res => res.json())
                    .then(data => {
                        $('#h_jihadi_name').text(data.jihadi_name || 'غير متوفر');
                        $('#h_military_number').text(data.military_number || 'غير متوفر');
                        $('#h_national_id').text(data.national_id_number || 'غير متوفر');
                        $('#h_blood_type').text(data.blood_type || 'غير متوفر');
                        $('#h_dob').text(data.date_of_birth || 'غير متوفر');
                        $('#h_marital').text(data.marital_status || 'غير متوفر');
                        $('#h_birth_place').text(data.place_of_birth || 'غير متوفر');
                        $('#h_phone1').text(data.phone_1 || 'غير متوفر');
                        $('#h_phone2').text(data.phone_2 || 'غير متوفر');
                        $('#h_address').text(data.address || 'غير متوفر');
                        
                        husbandContainer.removeClass('hidden');
                    })
                    .catch(err => {
                        console.error('Error fetching husband details:', err);
                        husbandContainer.addClass('hidden');
                    });
            });

            // Trigger husband details if pre-selected
            if (husbandSelect.val()) {
                husbandSelect.trigger('change');
            }

            // Robust Cascading
            function robustCascading(govId, dirId, subId, vilId) {
                const $gov = $(`#${govId}`);
                const $dir = $(`#${dirId}`);
                const $sub = $(`#${subId}`);
                const $vil = $(`#${vilId}`);

                const load = (url, $el, placeholder, nextSelected) => {
                    return fetch(url).then(r => r.json()).then(data => {
                        $el.html(`<option value="">${placeholder}</option>`);
                        Object.keys(data).forEach(id => {
                            $el.append(`<option value="${id}" ${id == nextSelected ? 'selected' : ''}>${data[id]}</option>`);
                        });
                    });
                };

                $gov.on('change', function() {
                    const id = $(this).val();
                    if (!id) return;
                    $dir.html('<option>جاري التحميل...</option>');
                    load(`/api/geo/directorates/${id}`, $dir, '-- اختر المديرية --').then(() => $dir.trigger('change'));
                });

                $dir.on('change', function() {
                    const id = $(this).val();
                    if (!id) { $sub.html('<option value="">-- اختر المنطقة --</option>'); return; }
                    $sub.html('<option>جاري التحميل...</option>');
                    load(`/api/geo/sub-areas/${id}`, $sub, '-- اختر المنطقة --').then(() => $sub.trigger('change'));
                });

                $sub.on('change', function() {
                    const id = $(this).val();
                    if (!id) { $vil.html('<option value="">-- اختر القرية --</option>'); return; }
                    $vil.html('<option>جاري التحميل...</option>');
                    load(`/api/geo/villages/${id}`, $vil, '-- اختر القرية --');
                });

                // Initial load for edit
                if ($gov.val()) {
                    const dSel = $dir.data('selected');
                    const sSel = $sub.data('selected');
                    const vSel = $vil.data('selected');
                    
                    load(`/api/geo/directorates/${$gov.val()}`, $dir, '-- اختر المديرية --', dSel).then(() => {
                        if (dSel) return load(`/api/geo/sub-areas/${dSel}`, $sub, '-- اختر المنطقة --', sSel);
                    }).then(() => {
                        if (sSel) return load(`/api/geo/villages/${sSel}`, $vil, '-- اختر القرية --', vSel);
                    });
                }
            }
            
            robustCascading('governorate_id', 'directorate_id', 'sub_area_id', 'village_id');
        });
    </script>
@endsection