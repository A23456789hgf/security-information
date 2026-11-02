@extends('layouts.app')

@section('title', 'إضافة زوجة')
@section('page_title', 'السجلات الأساسية')

@section('content')
<div class="max-w-5xl mx-auto space-y-6" dir="rtl">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white flex items-center gap-3">
                <div class="p-2.5 bg-brand-500/10 text-brand-600 dark:text-brand-400 rounded-xl">
                    <i class="fas fa-user-plus text-xl"></i>
                </div>
                إضافة زوجة جديدة
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                إدخال معلومات الزوجة وتفاصيل العنوان والارتباط بالشخص.
            </p>
        </div>
        <a href="{{ route('system.wives.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-sm font-semibold rounded-xl transition-all shadow-sm">
            <i class="fas fa-arrow-right"></i>
            عودة للقائمة
        </a>
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

    <form action="{{ route('system.wives.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Basic Information --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
            <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                <span class="w-2 h-5 bg-brand-600 rounded-full"></span>
                البيانات الأساسية
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                {{-- Husband Selection --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">الفرد / الزوج <span class="text-rose-500">*</span></label>
                    <select id="personal_data_id" name="personal_data_id" required
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all cursor-pointer @error('personal_data_id') border-rose-500 @enderror">
                        <option value="">-- اختر الفرد --</option>
                        @foreach($personalDataList as $id => $name)
                            <option value="{{ $id }}" {{ (old('personal_data_id') ?? $selectedPersonalDataId) == $id ? 'selected' : '' }}>
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
                        placeholder="الاسم الكامل للزوجة..." value="{{ old('name') }}">
                    @error('name')
                        <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">الحالة <span class="text-rose-500">*</span></label>
                    <select name="status" required
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all cursor-pointer">
                        <option value="مستمرة" {{ old('status') == 'مستمرة' ? 'selected' : '' }}>مستمرة</option>
                        <option value="متوفية" {{ old('status') == 'متوفية' ? 'selected' : '' }}>متوفية</option>
                        <option value="مطلقة" {{ old('status') == 'مطلقة' ? 'selected' : '' }}>مطلقة</option>
                        <option value="أخرى" {{ old('status') == 'أخرى' ? 'selected' : '' }}>أخرى</option>
                    </select>
                    @error('status')
                        <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">رقم الهوية</label>
                    <input type="text" name="wife_id_number"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-mono font-medium text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all @error('wife_id_number') border-rose-500 @enderror"
                        placeholder="أدخل رقم الهوية" value="{{ old('wife_id_number') }}">
                    @error('wife_id_number')
                        <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">تاريخ الميلاد</label>
                    <input type="date" name="wife_birth_date"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-medium text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all @error('wife_birth_date') border-rose-500 @enderror"
                        value="{{ old('wife_birth_date') }}">
                    @error('wife_birth_date')
                        <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">رقم الهاتف</label>
                    <input type="text" name="wife_phone"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-mono font-medium text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all @error('wife_phone') border-rose-500 @enderror"
                        placeholder="أدخل رقم الهاتف" value="{{ old('wife_phone') }}">
                    @error('wife_phone')
                        <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">العنوان التفصيلي</label>
                    <input type="text" name="wife_address"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all @error('wife_address') border-rose-500 @enderror"
                        placeholder="العنوان..." value="{{ old('wife_address') }}">
                    @error('wife_address')
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
                            <option value="{{ $id }}" {{ old('governorate_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">المديرية</label>
                    <select id="directorate_id" name="directorate_id"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all cursor-pointer">
                        <option value="">-- اختر المديرية --</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">العزلة / المنطقة</label>
                    <select id="sub_area_id" name="sub_area_id"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all cursor-pointer">
                        <option value="">-- اختر المنطقة --</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">القرية / الحي</label>
                    <select id="village_id" name="village_id"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all cursor-pointer">
                        <option value="">-- اختر القرية / الحي --</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-800">
            <button type="button" onclick="history.back()"
                class="px-5 py-2.5 text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:text-slate-200 dark:hover:text-slate-200 transition-colors">
                إلغاء
            </button>
            <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-sm font-semibold shadow-sm hover:shadow transition-all">
                <i class="fas fa-check"></i>
                حفظ البيانات
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

                fetch(`/system/personal-data/details/${husbandId}`)
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

        // Cascading Dropdowns
        const govEl = document.getElementById('governorate_id');
        const dirEl = document.getElementById('directorate_id');
        const subEl = document.getElementById('sub_area_id');
        const vilEl = document.getElementById('village_id');

        function loadOptions(url, targetEl, placeholder) {
            targetEl.disabled = true;
            targetEl.innerHTML = '<option value="">جاري التحميل...</option>';
            return fetch(url)
                .then(r => r.json())
                .then(data => {
                    targetEl.innerHTML = `<option value="">${placeholder}</option>`;
                    Object.keys(data).forEach(id => {
                        const opt = document.createElement('option');
                        opt.value = id;
                        opt.textContent = data[id];
                        targetEl.appendChild(opt);
                    });
                    targetEl.disabled = false;
                });
        }

        if (govEl) {
            govEl.addEventListener('change', function () {
                const id = this.value;
                dirEl.innerHTML = '<option value="">-- اختر المديرية --</option>';
                subEl.innerHTML = '<option value="">-- اختر المنطقة --</option>';
                vilEl.innerHTML = '<option value="">-- اختر القرية --</option>';
                if (id) loadOptions(`/api/geo/directorates/${id}`, dirEl, '-- اختر المديرية --');
            });

            dirEl.addEventListener('change', function () {
                const id = this.value;
                subEl.innerHTML = '<option value="">-- اختر المنطقة --</option>';
                vilEl.innerHTML = '<option value="">-- اختر القرية --</option>';
                if (id) loadOptions(`/api/geo/sub-areas/${id}`, subEl, '-- اختر المنطقة --');
            });

            subEl.addEventListener('change', function () {
                const id = this.value;
                vilEl.innerHTML = '<option value="">-- اختر القرية --</option>';
                if (id) loadOptions(`/api/geo/villages/${id}`, vilEl, '-- اختر القرية --');
            });
        }
    });
</script>
@endsection

