@extends('layouts.app')

@section('title', 'تعديل بيانات القريب')
@section('page_title', 'السجلات الأساسية')

@section('content')
<div class="max-w-5xl mx-auto space-y-6" dir="rtl">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white flex items-center gap-3">
                <div class="p-2.5 bg-amber-500/10 text-amber-600 dark:text-amber-400 rounded-xl">
                    <i class="fas fa-user-edit text-xl"></i>
                </div>
                تعديل بيانات القريب
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                تحديث معلومات القريب: <span class="text-brand-600 dark:text-brand-400 font-bold">{{ $relative->full_name }}</span>
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('system.relatives.show', $relative->id) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-sm font-semibold rounded-xl transition-all shadow-sm">
                <i class="fas fa-eye"></i>
                عرض التفاصيل
            </a>
            <a href="{{ route('system.relatives.index') }}"
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

    <form action="{{ route('system.relatives.update', $relative->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
            <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                <span class="w-2 h-5 bg-amber-500 rounded-full"></span>
                البيانات الأساسية للقريب
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                {{-- Person Selection --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">الفرد الأساسي <span class="text-rose-500">*</span></label>
                    <select id="personal_data_id" name="personal_data_id" required
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all cursor-pointer @error('personal_data_id') border-rose-500 @enderror">
                        <option value="">-- اختر الفرد --</option>
                        @foreach($personalDataList as $id => $name)
                            <option value="{{ $id }}" {{ (old('personal_data_id') ?? $relative->personal_data_id) == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Person Preview --}}
                <div id="person_details_container" class="md:col-span-2 hidden">
                    <div class="bg-slate-50 dark:bg-slate-950/50 border border-slate-200 dark:border-slate-800 rounded-xl p-5 space-y-4">
                        {{-- Photo & Main Details Header --}}
                        <div class="flex items-center gap-3.5 p-2.5 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800">
                            <div class="w-16 h-16 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shrink-0 flex items-center justify-center shadow-xs">
                                <img id="p_photo" src="" alt="الصورة الشخصية" class="w-full h-full object-cover hidden">
                                <i id="p_photo_placeholder" class="fas fa-user text-slate-400 dark:text-slate-500 text-2xl"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1 flex items-center gap-1.5">
                                    <i class="fas fa-info-circle text-brand-600 dark:text-brand-400"></i>
                                    تفاصيل الفرد المختار
                                </h3>
                                <div id="p_person_name" class="font-bold text-slate-800 dark:text-slate-200 text-sm truncate"></div>
                                <div id="p_person_sub" class="text-xs text-slate-400 dark:text-slate-500 truncate"></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 text-xs">
                            <div class="bg-white dark:bg-slate-900 p-3 rounded-2xl border border-slate-200 dark:border-slate-800">
                                <span class="text-slate-400 block mb-0.5">الاسم الجهادي:</span>
                                <span id="p_jihadi_name" class="font-bold text-slate-800 dark:text-slate-200">-</span>
                            </div>
                            <div class="bg-white dark:bg-slate-900 p-3 rounded-2xl border border-slate-200 dark:border-slate-800">
                                <span class="text-slate-400 block mb-0.5">الرقم العسكري:</span>
                                <span id="p_military_number" class="font-bold text-slate-800 dark:text-slate-200">-</span>
                            </div>
                            <div class="bg-white dark:bg-slate-900 p-3 rounded-2xl border border-slate-200 dark:border-slate-800">
                                <span class="text-slate-400 block mb-0.5">فصيلة الدم:</span>
                                <span id="p_blood_type" class="font-bold text-rose-600 dark:text-rose-400">-</span>
                            </div>
                            <div class="bg-white dark:bg-slate-900 p-3 rounded-2xl border border-slate-200 dark:border-slate-800">
                                <span class="text-slate-400 block mb-0.5">رقم الهوية:</span>
                                <span id="p_national_id" class="font-bold text-slate-800 dark:text-slate-200">-</span>
                            </div>
                            <div class="bg-white dark:bg-slate-900 p-3 rounded-2xl border border-slate-200 dark:border-slate-800 sm:col-span-2 md:col-span-4">
                                <span class="text-slate-400 block mb-0.5">العنوان:</span>
                                <span id="p_address" class="font-bold text-slate-800 dark:text-slate-200">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">الاسم الكامل للقريب <span class="text-rose-500">*</span></label>
                    <input type="text" name="full_name" required
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all @error('full_name') border-rose-500 @enderror"
                        placeholder="الاسم الكامل للقريب..." value="{{ old('full_name') ?? $relative->full_name }}">
                    @error('full_name')
                        <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">العمر</label>
                    <input type="number" name="age" min="0" max="150"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-medium text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all"
                        placeholder="أدخل العمر بالسنوات" value="{{ old('age') ?? $relative->age }}">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">صلة القرابة</label>
                    <select name="kinship_id"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all cursor-pointer">
                        <option value="">-- اختر صلة القرابة --</option>
                        @foreach($kinships as $id => $name)
                            <option value="{{ $id }}" {{ (old('kinship_id') ?? $relative->kinship_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">العمل الحالي</label>
                    <input type="text" name="current_job"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-medium text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all"
                        placeholder="المهنة أو العمل الحالي..." value="{{ old('current_job') ?? $relative->current_job }}">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">الانتماء العقائدي</label>
                    <select name="ideological_affiliation_id"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all cursor-pointer">
                        <option value="">-- اختر الانتماء العقائدي --</option>
                        @foreach($ideologicalAffiliations as $id => $name)
                            <option value="{{ $id }}" {{ (old('ideological_affiliation_id') ?? $relative->ideological_affiliation_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">الانتماء السياسي</label>
                    <select name="political_affiliation_id"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all cursor-pointer">
                        <option value="">-- اختر الانتماء السياسي --</option>
                        @foreach($politicalAffiliations as $id => $name)
                            <option value="{{ $id }}" {{ (old('political_affiliation_id') ?? $relative->political_affiliation_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">ملاحظات إضافية</label>
                    <textarea name="notes" rows="3"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all"
                        placeholder="أدخل أي ملاحظات إضافية...">{{ old('notes') ?? $relative->notes }}</textarea>
                </div>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-800">
            <a href="{{ route('system.relatives.index') }}"
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
        const personSelect = document.getElementById('personal_data_id');
        const personDetails = document.getElementById('person_details_container');

        if (personSelect) {
            personSelect.addEventListener('change', function () {
                const personId = this.value;
                if (!personId) {
                    personDetails.classList.add('hidden');
                    return;
                }

                fetch(`/system/personal-data/details/${personId}`)
                    .then(res => res.json())
                    .then(data => {
                        const photoEl = document.getElementById('p_photo');
                        const placeholderEl = document.getElementById('p_photo_placeholder');
                        if (data.photo_url) {
                            photoEl.src = data.photo_url;
                            photoEl.classList.remove('hidden');
                            placeholderEl.classList.add('hidden');
                        } else {
                            photoEl.src = '';
                            photoEl.classList.add('hidden');
                            placeholderEl.classList.remove('hidden');
                        }

                        document.getElementById('p_person_name').textContent = data.name || '-';
                        document.getElementById('p_person_sub').textContent = (data.military_number && data.military_number !== '-' ? 'الرقم العسكري: ' + data.military_number : '') + (data.jihadi_name && data.jihadi_name !== '-' ? ' | ' + data.jihadi_name : '');
                        document.getElementById('p_jihadi_name').textContent = data.jihadi_name || '-';
                        document.getElementById('p_military_number').textContent = data.military_number || '-';
                        document.getElementById('p_blood_type').textContent = data.blood_type || '-';
                        document.getElementById('p_national_id').textContent = data.national_id_number || '-';
                        document.getElementById('p_address').textContent = data.address || '-';
                        personDetails.classList.remove('hidden');
                    })
                    .catch(err => {
                        console.error('Error:', err);
                        personDetails.classList.add('hidden');
                    });
            });

            if (personSelect.value) {
                personSelect.dispatchEvent(new Event('change'));
            }
        }

        if (typeof $ !== 'undefined' && $.fn.select2) {
            $('select').select2({
                theme: 'bootstrap-5',
                dir: 'rtl',
                tags: true,
                width: '100%'
            });
        }
    });
</script>
@endsection
