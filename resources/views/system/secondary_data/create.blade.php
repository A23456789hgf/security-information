@extends('layouts.app')

@section('content')
    <div class="container-fluid mx-auto px-4 py-8" dir="rtl">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div class="flex items-center gap-4">
                <div
                    class="w-16 h-16 bg-slate-800 rounded-3xl shadow-xl shadow-slate-100 flex items-center justify-center text-white">
                    <i class="fas fa-file-medical text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-800 header-font mb-1">إضافة بيانات ثانوية</h2>
                    <p class="text-slate-500 text-sm">تسجيل البيانات الجهادية والأسلحة والعهدة للشخص.</p>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('system.secondary-data.index') }}"
                    class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl font-bold hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2">
                    <i class="fas fa-arrow-right"></i> عودة
                </a>
                <button type="submit" form="secondaryDataForm"
                    class="px-8 py-3 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 flex items-center gap-2">
                    <i class="fas fa-save"></i> حفظ البيانات
                </button>
            </div>
        </div>

        @if ($errors->any())
            <div class="bg-rose-50 border border-rose-100 rounded-3xl p-6 mb-8">
                <div class="flex items-center gap-3 text-rose-700 mb-4 font-bold">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>يرجى تصحيح الأخطاء التالية:</span>
                </div>
                <ul class="list-disc list-inside text-rose-600 text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="secondaryDataForm" action="{{ route('system.secondary-data.store') }}" method="POST">
            @csrf
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                    {{-- Right Column: Person Info & Dates --}}
                    <div class="lg:col-span-4 space-y-6">
                        <div
                            class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden sticky top-8">
                            <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50">
                                <h5 class="text-lg font-bold text-slate-800 header-font flex items-center gap-3">
                                    <span class="w-2 h-6 bg-blue-600 rounded-full"></span>
                                    اختيار الشخص
                                </h5>
                            </div>
                            <div class="p-8">
                                <div class="mb-6">
                                    <label class="text-xs font-bold text-slate-400 mb-2 block px-2">ابحث عن الشخص واختاره
                                        *</label>
                                    <select name="personal_data_id" id="personal_data_id"
                                        class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all font-bold text-slate-700 select2-selection"
                                        required>
                                        <option value="">-- اختر الشخص --</option>
                                        @foreach($personalDataList as $id => $name)
                                            <option value="{{ $id }}" {{ (isset($selectedPersonalDataId) && $selectedPersonalDataId == $id) || old('personal_data_id') == $id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Standardized Info-Card --}}
                                <!-- <div id="personal_info_box" class="hidden">
                                    <div class="bg-blue-50 border border-blue-100 rounded-[2rem] p-6 text-[11px] mb-6 shadow-inner">
                                        <h6 class="font-bold text-blue-700 mb-4 flex items-center gap-2 text-xs">
                                            <i class="fas fa-info-circle"></i> بيانات الشخص المختار:
                                        </h6>
                                        <div class="grid grid-cols-1 gap-y-3 text-slate-700">
                                            <div class="flex items-center justify-between"><span class="text-slate-400">الاسم الجهادي:</span> <span id="info_jihadi_name" class="font-bold">-</span></div>
                                            <div class="flex items-center justify-between"><span class="text-slate-400">الرقم العسكري:</span> <span id="info_military_number" class="font-bold text-blue-600">-</span></div>
                                            <div class="flex items-center justify-between"><span class="text-slate-400">رقم الهوية:</span> <span id="info_id_number" class="font-bold">-</span></div>
                                            <div class="flex items-center justify-between"><span class="text-slate-400">فصيلة الدم:</span> <span id="info_blood_type" class="text-rose-600 font-extrabold">-</span></div>
                                            <div class="flex items-center justify-between"><span class="text-slate-400">تاريخ الميلاد:</span> <span id="info_dob" class="font-bold">-</span></div>
                                            <div class="flex items-center justify-between border-t pt-2"><span class="text-slate-400">هاتف 1:</span> <span id="info_phone_1" class="font-bold">-</span></div>
                                            <div class="flex flex-col border-t pt-2"><span class="text-slate-400 mb-1">العنوان:</span> <span id="info_address" class="font-bold leading-relaxed">-</span></div>
                                        </div>
                                    </div>
                                </div> -->
                                <div id="husband_details_container" class="md:col-span-2 hidden mb-4">
                                    <div class="bg-slate-50 border border-slate-100 rounded-[2rem] p-6">
                                        <h6
                                            class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                                            <i class="fas fa-info-circle text-blue-600"></i>
                                            تفاصيل الفرد المختار
                                        </h6>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-3 text-xs">
                                            <div
                                                class="flex items-center gap-3 text-slate-600 bg-white p-3 rounded-2xl border border-slate-50">
                                                <span class="font-bold text-slate-400 w-24">الاسم الجهادي:</span>
                                                <span id="h_jihadi_name" class="font-bold text-slate-700">-</span>
                                            </div>
                                            <div
                                                class="flex items-center gap-3 text-slate-600 bg-white p-3 rounded-2xl border border-slate-50">
                                                <span class="font-bold text-slate-400 w-24">الرقم العسكري:</span>
                                                <span id="h_military_number" class="font-bold text-slate-700">-</span>
                                            </div>
                                            <div
                                                class="flex items-center gap-3 text-slate-600 bg-white p-3 rounded-2xl border border-slate-50">
                                                <span class="font-bold text-slate-400 w-24">فصيلة الدم:</span>
                                                <span id="h_blood_type" class="text-rose-600 font-extrabold">-</span>
                                            </div>
                                            <div
                                                class="flex items-center gap-3 text-slate-600 bg-white p-3 rounded-2xl border border-slate-50">
                                                <span class="font-bold text-slate-400 w-24">تاريخ الميلاد:</span>
                                                <span id="h_dob" class="font-bold text-slate-700">-</span>
                                            </div>
                                            <div
                                                class="flex items-center gap-3 text-slate-600 bg-white p-3 rounded-2xl border border-slate-50">
                                                <span class="font-bold text-slate-400 w-24">الحالة الاجتماعية:</span>
                                                <span id="h_marital" class="font-bold text-slate-700">-</span>
                                            </div>
                                            <div
                                                class="flex items-center gap-3 text-slate-600 bg-white p-3 rounded-2xl border border-slate-50">
                                                <span class="font-bold text-slate-400 w-24">مكان الميلاد:</span>
                                                <span id="h_birth_place" class="font-bold text-slate-700">-</span>
                                            </div>
                                            <div
                                                class="flex items-center gap-3 text-slate-600 bg-white p-3 rounded-2xl border border-slate-50">
                                                <span class="font-bold text-slate-400 w-24">رقم الهوية:</span>
                                                <span id="h_national_id" class="font-bold text-slate-700">-</span>
                                            </div>
                                            <div
                                                class="flex items-center gap-3 text-slate-600 bg-white p-3 rounded-2xl border border-slate-50">
                                                <span class="font-bold text-slate-400 w-24">هاتف 1:</span>
                                                <span id="h_phone1" class="font-bold text-slate-700">-</span>
                                            </div>
                                            <div
                                                class="flex items-center gap-3 text-slate-600 bg-white p-3 rounded-2xl border border-slate-50">
                                                <span class="font-bold text-slate-400 w-24">هاتف 2:</span>
                                                <span id="h_phone2" class="font-bold text-slate-700">-</span>
                                            </div>
                                            <div
                                                class="flex items-center gap-3 text-slate-600 bg-white p-3 rounded-2xl border border-slate-50 col-span-1 md:col-span-2 mt-2">
                                                <span class="font-bold text-slate-400 w-24">العنوان:</span>
                                                <span id="h_address" class="font-bold text-slate-700">-</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <label class="text-xs font-bold text-slate-400 mb-2 block px-2">تاريخ
                                            الانطلاقة</label>
                                        <input type="date" name="start_date"
                                            class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all font-bold text-slate-700"
                                            value="{{ old('start_date') }}">
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-slate-400 mb-2 block px-2">المسؤول
                                            المباشر</label>
                                        <input type="text" name="direct_supervisor"
                                            class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all font-bold text-slate-700"
                                            value="{{ old('direct_supervisor') }}" placeholder="اسم المسؤول">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Left Column: Weapons & Activity --}}
                    <div class="lg:col-span-8 space-y-8">

                        {{-- Weapons Section --}}
                        <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
                            <div
                                class="px-8 py-6 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
                                <h5 class="text-lg font-bold text-slate-800 header-font flex items-center gap-3">
                                    <span class="w-2 h-6 bg-emerald-500 rounded-full"></span>
                                    بيانات الأسلحة والعهدة
                                </h5>
                                <button type="button" id="add-weapon-row"
                                    class="px-4 py-2 bg-emerald-50 text-emerald-600 rounded-xl font-bold text-xs hover:bg-emerald-100 transition-all flex items-center gap-2">
                                    <i class="fas fa-plus"></i> إضافة سلاح
                                </button>
                            </div>
                            <div class="p-0">
                                <div class="overflow-x-auto">
                                    <table class="w-full text-right" id="weapons-table">
                                        <thead class="bg-slate-50/50 text-[10px] uppercase font-bold text-slate-400">
                                            <tr>
                                                <th class="px-8 py-4">نوع السلاح</th>
                                                <th class="px-4 py-4">الرقم</th>
                                                <th class="px-4 py-4">الملكية</th>
                                                <th class="px-4 py-4">التعقب</th>
                                                <th class="px-8 py-4 w-10"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-50">
                                            <tr class="weapon-row group hover:bg-slate-50/50 transition-all">
                                                <td class="px-8 py-2">
                                                    <input type="text" name="weapons[0][weapon_type]"
                                                        class="w-full bg-transparent border-0 focus:ring-0 font-bold text-slate-700 placeholder-slate-300"
                                                        placeholder="نوع السلاح...">
                                                </td>
                                                <td class="px-4 py-2">
                                                    <input type="text" name="weapons[0][number]"
                                                        class="w-full bg-transparent border-0 focus:ring-0 font-bold text-slate-700 placeholder-slate-300"
                                                        placeholder="الرقم">
                                                </td>
                                                <td class="px-4 py-2">
                                                    <input type="text" name="weapons[0][ownership]"
                                                        class="w-full bg-transparent border-0 focus:ring-0 font-bold text-slate-700 placeholder-slate-300"
                                                        placeholder="الملكية">
                                                </td>
                                                <td class="px-4 py-2">
                                                    <input type="text" name="weapons[0][tracking]"
                                                        class="w-full bg-transparent border-0 focus:ring-0 font-bold text-slate-700 placeholder-slate-300"
                                                        placeholder="التعقب">
                                                </td>
                                                <td class="px-8 py-2">
                                                    <button type="button"
                                                        class="remove-row text-rose-300 hover:text-rose-500 transition-colors hidden">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- Activity Section --}}
                        <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
                            <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50">
                                <h5 class="text-lg font-bold text-slate-800 header-font flex items-center gap-3">
                                    <span class="w-2 h-6 bg-amber-400 rounded-full"></span>
                                    النشاط والمهام
                                </h5>
                            </div>
                            <div class="p-8">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div>
                                        <label class="text-xs font-bold text-slate-400 mb-2 block px-2">النشاط الجهادي
                                            السابق</label>
                                        <textarea name="previous_jihadist_activity" rows="5"
                                            class="w-full px-6 py-4 bg-slate-50 border-0 rounded-3xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700">{{ old('previous_jihadist_activity') }}</textarea>
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-slate-400 mb-2 block px-2">النشاط الجهادي
                                            الحالي</label>
                                        <textarea name="current_jihadist_activity" rows="5"
                                            class="w-full px-6 py-4 bg-slate-50 border-0 rounded-3xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700">{{ old('current_jihadist_activity') }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="p-8 border-t border-slate-50 bg-slate-50/30 flex justify-end">
                                <button type="submit"
                                    class="px-12 py-5 bg-blue-600 text-white rounded-[2rem] font-bold hover:bg-blue-700 transition-all shadow-2xl shadow-blue-100 flex items-center gap-3 text-xl">
                                    <i class="fas fa-check-double"></i> اعتماد وحفظ السجل
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function () {
                const personSelect = $('#personal_data_id');
                const infoBox = $('#personal_info_box');

                if ($.fn.select2) {
                    $('.select2-selection').select2({
                        theme: 'bootstrap-5',
                        dir: 'rtl',
                        width: '100%'
                    });
                }

                personSelect.on('change', function () {
                    const personId = $(this).val();
                    if (!personId) {
                        infoBox.addClass('hidden');
                        return;
                    }

                    fetch(`/system/personal-data/details/${personId}`)
                        .then(res => res.json())
                        .then(data => {
                            $('#info_jihadi_name').text(data.jihadi_name || '-');
                            $('#info_military_number').text(data.military_number || '-');
                            $('#info_id_number').text(data.national_id_number || '-');
                            $('#info_blood_type').text(data.blood_type || '-');
                            $('#info_dob').text(data.date_of_birth || '-');
                            $('#info_phone_1').text(data.phone_1 || '-');
                            $('#info_address').text(data.address || '-');

                            infoBox.removeClass('hidden');
                        })
                        .catch(err => {
                            console.error('Error:', err);
                            infoBox.addClass('hidden');
                        });
                });

                if (personSelect.val()) {
                    personSelect.trigger('change');
                }

                // Weapon Dynamic Rows
                let weaponIndex = 1;
                $('#add-weapon-row').click(function () {
                    const newRow = `
                        <tr class="weapon-row group hover:bg-slate-50/50 transition-all border-t border-slate-50">
                            <td class="px-8 py-2">
                                <input type="text" name="weapons[${weaponIndex}][weapon_type]" class="w-full bg-transparent border-0 focus:ring-0 font-bold text-slate-700 placeholder-slate-300" placeholder="نوع السلاح...">
                            </td>
                            <td class="px-4 py-2">
                                <input type="text" name="weapons[${weaponIndex}][number]" class="w-full bg-transparent border-0 focus:ring-0 font-bold text-slate-700 placeholder-slate-300" placeholder="الرقم">
                            </td>
                            <td class="px-4 py-2">
                                <input type="text" name="weapons[${weaponIndex}][ownership]" class="w-full bg-transparent border-0 focus:ring-0 font-bold text-slate-700 placeholder-slate-300" placeholder="الملكية">
                            </td>
                            <td class="px-4 py-2">
                                <input type="text" name="weapons[${weaponIndex}][tracking]" class="w-full bg-transparent border-0 focus:ring-0 font-bold text-slate-700 placeholder-slate-300" placeholder="التعقب">
                            </td>
                            <td class="px-8 py-2 text-center">
                                <button type="button" class="remove-row text-rose-300 hover:text-rose-500 transition-colors">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    $('#weapons-table tbody').append(newRow);
                    weaponIndex++;
                    updateRemoveButtons();
                });

                $(document).on('click', '.remove-row', function () {
                    $(this).closest('tr').remove();
                    updateRemoveButtons();
                });

                function updateRemoveButtons() {
                    const count = $('.weapon-row').length;
                    $('.remove-row').toggle(count > 1);
                }
            });
        </script>
    @endpush
@endsection