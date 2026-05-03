@extends('layouts.app')

@section('content')
    <div class="container-fluid mx-auto px-4 py-8" dir="rtl">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-emerald-600 rounded-3xl shadow-xl shadow-emerald-100 flex items-center justify-center text-white">
                    <i class="fas fa-graduation-cap text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-800 header-font mb-1">إضافة دورة تدريبية</h2>
                    <p class="text-slate-500 text-sm">تسجيل دورة جديدة للفرد وتحديد جهة الإرسال والتاريخ.</p>
                </div>
            </div>
            <a href="{{ route('system.courses.index') }}"
                class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl font-bold hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2">
                <i class="fas fa-arrow-right"></i> عودة للقائمة
            </a>
        </div>

        <form id="courseForm" action="{{ route('system.courses.store') }}" method="POST">
            @csrf
            <div class="max-w-5xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    
                    {{-- Right Column: Person Selection & Info Card --}}
                    <div class="lg:col-span-5 space-y-6">
                        <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
                            <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50">
                                <h5 class="text-lg font-bold text-slate-800 header-font flex items-center gap-3">
                                    <span class="w-2 h-6 bg-blue-600 rounded-full"></span>
                                    بيانات المتدرب
                                </h5>
                            </div>
                            <div class="p-8">
                                <div class="mb-6">
                                    <label class="text-xs font-bold text-slate-400 mb-2 block px-2">ابحث عن الاسم واختاره *</label>
                                    <select id="personal_data_id" name="personal_data_id" required
                                        class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all font-bold text-slate-700 select2-selection">
                                        <option value="">-- اختر الاسم --</option>
                                        @foreach($allPeople as $person)
                                            <option value="{{ $person->id }}" @selected(old('personal_data_id', $selectedPersonalDataId) == $person->id)>
                                                {{ $person->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Standardized Info-Card --}}
                                <div id="personal_info_box" class="hidden">
                                    <div class="bg-blue-50 border border-blue-100 rounded-[2rem] p-6 text-xs mb-4">
                                        <h6 class="font-bold text-blue-700 mb-4 flex items-center gap-2">
                                            <i class="fas fa-info-circle"></i> تفاصيل المتدرب المختار:
                                        </h6>
                                        <div class="grid grid-cols-1 gap-y-3 text-slate-700">
                                            <div class="flex items-center justify-between"><span class="text-slate-400">الاسم الجهادي:</span> <span id="info_jihadi_name" class="font-bold">-</span></div>
                                            <div class="flex items-center justify-between"><span class="text-slate-400">الرقم العسكري:</span> <span id="info_military_number" class="font-bold">-</span></div>
                                            <div class="flex items-center justify-between"><span class="text-slate-400">رقم الهوية:</span> <span id="info_id_number" class="font-bold">-</span></div>
                                            <div class="flex items-center justify-between"><span class="text-slate-400">فصيلة الدم:</span> <span id="info_blood_type" class="text-rose-600 font-extrabold">-</span></div>
                                            <div class="flex items-center justify-between"><span class="text-slate-400">تاريخ الميلاد:</span> <span id="info_dob" class="font-bold">-</span></div>
                                            <div class="flex items-center justify-between"><span class="text-slate-400">الحالة الاجتماعية:</span> <span id="info_marital_status" class="font-bold">-</span></div>
                                            <div class="flex items-center justify-between border-t pt-2"><span class="text-slate-400">هاتف 1:</span> <span id="info_phone_1" class="font-bold">-</span></div>
                                            <div class="flex flex-col border-t pt-2"><span class="text-slate-400">العنوان:</span> <span id="info_address" class="font-bold">-</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Left Column: Course Details --}}
                    <div class="lg:col-span-7 space-y-6">
                        <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
                            <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50">
                                <h5 class="text-lg font-bold text-slate-800 header-font flex items-center gap-3">
                                    <span class="w-2 h-6 bg-emerald-500 rounded-full"></span>
                                    تفاصيل الدورة التدريبية
                                </h5>
                            </div>
                            <div class="p-8">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="md:col-span-2">
                                        <label class="text-xs font-bold text-slate-400 mb-2 block px-2">نوع الدورة *</label>
                                        <select name="course_type_id" required
                                            class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all font-bold text-slate-700">
                                            <option value="">-- اختر نوع الدورة --</option>
                                            @foreach($courseTypes as $id => $type)
                                                <option value="{{ $id }}" @selected(old('course_type_id') == $id)>{{ $type }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="text-xs font-bold text-slate-400 mb-2 block px-2">تاريخ الدورة (ميلادي) *</label>
                                        <input type="date" name="course_date_gregorian" required
                                            class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all font-bold text-slate-700"
                                            value="{{ old('course_date_gregorian') }}">
                                    </div>

                                    <div>
                                        <label class="text-xs font-bold text-slate-400 mb-2 block px-2">تاريخ الدورة (هجري)</label>
                                        <input type="text" name="course_date_hijri"
                                            class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all font-bold text-slate-700"
                                            placeholder="يوم/شهر/سنة" value="{{ old('course_date_hijri') }}">
                                    </div>

                                    <div>
                                        <label class="text-xs font-bold text-slate-400 mb-2 block px-2">مدة الدورة</label>
                                        <input type="text" name="course_duration"
                                            class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all font-bold text-slate-700"
                                            placeholder="مثلاً: شهر" value="{{ old('course_duration') }}">
                                    </div>

                                    <div>
                                        <label class="text-xs font-bold text-slate-400 mb-2 block px-2">جهة الإرسال</label>
                                        <select name="sending_entity_id"
                                            class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all font-bold text-slate-700">
                                            <option value="">-- اختر جهة الإرسال --</option>
                                            @foreach($fillingAgencies as $id => $agency)
                                                <option value="{{ $id }}" @selected(old('sending_entity_id') == $id)>{{ $agency }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="text-xs font-bold text-slate-400 mb-2 block px-2">مسؤول الدورة</label>
                                        <input type="text" name="course_administrator"
                                            class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all font-bold text-slate-700"
                                            placeholder="اسم المسؤول" value="{{ old('course_administrator') }}">
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="text-xs font-bold text-slate-400 mb-2 block px-2">ملاحظات إضافية</label>
                                        <textarea name="notes" rows="4"
                                            class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all font-bold text-slate-700"
                                            placeholder="أدخل أي ملاحظات هنا...">{{ old('notes') }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="p-8 border-top bg-slate-50/30 text-end">
                                <button type="submit"
                                    class="px-10 py-4 bg-emerald-600 text-white rounded-[2rem] font-bold hover:bg-emerald-700 transition-all shadow-xl shadow-emerald-100 flex items-center gap-2 ms-auto">
                                    <i class="fas fa-check-circle"></i> حفظ الدورة
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

            // Initialize Select2 if available
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
                        $('#info_marital_status').text(data.marital_status || '-');
                        $('#info_phone_1').text(data.phone_1 || '-');
                        $('#info_address').text(data.address || '-');

                        infoBox.removeClass('hidden');
                    })
                    .catch(err => {
                        console.error('Error fetching details:', err);
                        infoBox.addClass('hidden');
                    });
            });

            if (personSelect.val()) {
                personSelect.trigger('change');
            }
        });
    </script>
    @endpush
@endsection