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
                إضافة زوجة جديدة (متعدد)
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                إدخال معلومات الزوجات وتفاصيل العنوان والارتباط بالشخص. يمكنك إضافة أكثر من زوجة في نفس الوقت.
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

        {{-- Husband Selection --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
            <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                <span class="w-2 h-5 bg-brand-600 rounded-full"></span>
                اختيار الزوج (الفرد)
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
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
                </div>

                {{-- Person Preview --}}
                <div id="husband_details_container" class="md:col-span-2 hidden">
                    <div class="bg-slate-50 dark:bg-slate-950/50 border border-slate-200 dark:border-slate-800 rounded-xl p-5 space-y-4">
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
                    </div>
                </div>
            </div>
        </div>

        {{-- Multiple Wives Container --}}
        <div id="wives-container" class="space-y-6">
            {{-- Initial Wife Template (Index 0) --}}
            <div class="wife-entry bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm relative transition-all" data-index="0">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                        <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="w-2 h-5 bg-brand-500 rounded-full"></span>
                            بيانات الزوجة <span class="wife-counter text-brand-600 dark:text-brand-400">#1</span>
                        </h2>
                        <button type="button" class="remove-wife-btn hidden text-rose-500 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950 p-2 rounded-xl text-sm transition-colors flex items-center gap-2">
                            <i class="fas fa-trash-alt"></i> إزالة
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">اسم الزوجة <span class="text-rose-500">*</span></label>
                            <input type="text" name="wives[0][name]" required
                                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all"
                                placeholder="الاسم الكامل للزوجة...">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">الحالة <span class="text-rose-500">*</span></label>
                            <select name="wives[0][status]" required
                                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all cursor-pointer">
                                <option value="مستمرة">مستمرة</option>
                                <option value="متوفية">متوفية</option>
                                <option value="مطلقة">مطلقة</option>
                                <option value="أخرى">أخرى</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">رقم الهوية</label>
                            <input type="text" name="wives[0][wife_id_number]"
                                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-mono font-medium text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all"
                                placeholder="أدخل رقم الهوية">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">تاريخ الميلاد</label>
                            <input type="date" name="wives[0][wife_birth_date]"
                                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-medium text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">رقم الهاتف</label>
                            <input type="text" name="wives[0][wife_phone]"
                                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-mono font-medium text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all"
                                placeholder="أدخل رقم الهاتف">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">العنوان التفصيلي</label>
                            <input type="text" name="wives[0][wife_address]"
                                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all"
                                placeholder="العنوان...">
                        </div>
                    </div>

                    <div class="bg-slate-50/50 dark:bg-slate-950/30 rounded-xl p-4 border border-slate-100 dark:border-slate-800">
                        <h3 class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-4 flex items-center gap-2"><i class="fas fa-map-marker-alt text-emerald-500"></i> معلومات الموقع الجغرافي</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-[10px] uppercase tracking-wider font-bold text-slate-500 mb-1.5">المحافظة</label>
                                <select name="wives[0][governorate_id]" class="gov-select w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-semibold focus:ring-1 focus:ring-emerald-500 transition-all cursor-pointer">
                                    <option value="">-- اختر المحافظة --</option>
                                    @foreach($governorates as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] uppercase tracking-wider font-bold text-slate-500 mb-1.5">المديرية</label>
                                <select name="wives[0][directorate_id]" class="dir-select w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-semibold focus:ring-1 focus:ring-emerald-500 transition-all cursor-pointer">
                                    <option value="">-- اختر المديرية --</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] uppercase tracking-wider font-bold text-slate-500 mb-1.5">المنطقة</label>
                                <select name="wives[0][sub_area_id]" class="sub-select w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-semibold focus:ring-1 focus:ring-emerald-500 transition-all cursor-pointer">
                                    <option value="">-- اختر المنطقة --</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] uppercase tracking-wider font-bold text-slate-500 mb-1.5">القرية</label>
                                <select name="wives[0][village_id]" class="vil-select w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-semibold focus:ring-1 focus:ring-emerald-500 transition-all cursor-pointer">
                                    <option value="">-- اختر القرية --</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Add Another Button --}}
        <div class="flex justify-center">
            <button type="button" id="add-wife-btn"
                class="inline-flex items-center gap-2 px-6 py-3 bg-brand-50 hover:bg-brand-100 text-brand-700 dark:bg-brand-500/10 dark:hover:bg-brand-500/20 dark:text-brand-400 rounded-2xl text-sm font-bold border border-brand-200 dark:border-brand-500/20 transition-all border-dashed hover:border-solid">
                <i class="fas fa-plus"></i>
                إضافة زوجة أخرى
            </button>
        </div>

        {{-- Form Actions --}}
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-800 mt-8">
            <button type="button" onclick="history.back()"
                class="px-5 py-2.5 text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:text-slate-200 dark:hover:text-slate-200 transition-colors">
                إلغاء
            </button>
            <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-sm font-semibold shadow-sm hover:shadow transition-all">
                <i class="fas fa-check"></i>
                حفظ بيانات الزوجات
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- Person Details Preview ---
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

        // --- Dynamic Form Add/Remove ---
        const container = document.getElementById('wives-container');
        const addBtn = document.getElementById('add-wife-btn');
        let wifeIndex = 1;

        addBtn.addEventListener('click', function() {
            const firstEntry = container.querySelector('.wife-entry');
            const clone = firstEntry.cloneNode(true);
            
            clone.setAttribute('data-index', wifeIndex);
            
            // Update names and clear values
            clone.querySelectorAll('input, select').forEach(input => {
                const name = input.getAttribute('name');
                if (name) {
                    input.setAttribute('name', name.replace(/\[\d+\]/, `[${wifeIndex}]`));
                }
                if (input.tagName === 'INPUT') {
                    input.value = '';
                }
            });
            
            // Clear cascading dropdowns except Gov
            const dirSelect = clone.querySelector('.dir-select');
            const subSelect = clone.querySelector('.sub-select');
            const vilSelect = clone.querySelector('.vil-select');
            if(dirSelect) dirSelect.innerHTML = '<option value="">-- اختر المديرية --</option>';
            if(subSelect) subSelect.innerHTML = '<option value="">-- اختر المنطقة --</option>';
            if(vilSelect) vilSelect.innerHTML = '<option value="">-- اختر القرية --</option>';

            // Update counter and show remove btn
            clone.querySelector('.wife-counter').textContent = `#${wifeIndex + 1}`;
            clone.querySelector('.remove-wife-btn').classList.remove('hidden');

            container.appendChild(clone);
            wifeIndex++;
        });

        container.addEventListener('click', function(e) {
            const removeBtn = e.target.closest('.remove-wife-btn');
            if (removeBtn) {
                const entry = removeBtn.closest('.wife-entry');
                if (container.querySelectorAll('.wife-entry').length > 1) {
                    entry.remove();
                    updateCounters();
                }
            }
        });

        function updateCounters() {
            container.querySelectorAll('.wife-entry').forEach((entry, index) => {
                entry.querySelector('.wife-counter').textContent = `#${index + 1}`;
            });
        }

        // --- Event Delegation for Cascading Dropdowns ---
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

        container.addEventListener('change', function(e) {
            const target = e.target;
            const entry = target.closest('.wife-entry');
            if (!entry) return;

            const dirEl = entry.querySelector('.dir-select');
            const subEl = entry.querySelector('.sub-select');
            const vilEl = entry.querySelector('.vil-select');

            if (target.classList.contains('gov-select')) {
                const id = target.value;
                if(dirEl) dirEl.innerHTML = '<option value="">-- اختر المديرية --</option>';
                if(subEl) subEl.innerHTML = '<option value="">-- اختر المنطقة --</option>';
                if(vilEl) vilEl.innerHTML = '<option value="">-- اختر القرية --</option>';
                if (id) loadOptions(`/api/geo/directorates/${id}`, dirEl, '-- اختر المديرية --');
            }
            else if (target.classList.contains('dir-select')) {
                const id = target.value;
                if(subEl) subEl.innerHTML = '<option value="">-- اختر المنطقة --</option>';
                if(vilEl) vilEl.innerHTML = '<option value="">-- اختر القرية --</option>';
                if (id) loadOptions(`/api/geo/sub-areas/${id}`, subEl, '-- اختر المنطقة --');
            }
            else if (target.classList.contains('sub-select')) {
                const id = target.value;
                if(vilEl) vilEl.innerHTML = '<option value="">-- اختر القرية --</option>';
                if (id) loadOptions(`/api/geo/villages/${id}`, vilEl, '-- اختر القرية --');
            }
        });
    });
</script>
@endsection

