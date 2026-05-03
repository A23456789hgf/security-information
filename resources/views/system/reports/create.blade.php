@extends('layouts.app')

@section('content')
<div class="container-fluid mx-auto px-4 py-8" dir="rtl">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-slate-800 rounded-3xl shadow-xl shadow-slate-100 flex items-center justify-center text-white">
                <i class="fas fa-file-contract text-2xl"></i>
            </div>
            <div>
                <h2 class="text-3xl font-extrabold text-slate-800 header-font mb-1">إضافة بلاغ أمني</h2>
                <p class="text-slate-500 text-sm">تسجيل بلاغ أمني مفصل وربطه بملف الشخص المعني.</p>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('system.security-reports.index') }}"
                class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl font-bold hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2">
                <i class="fas fa-arrow-right"></i> عودة
            </a>
            <button type="submit" form="reportForm"
                class="px-8 py-3 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 flex items-center gap-2">
                <i class="fas fa-save"></i> حفظ البلاغ
            </button>
        </div>
    </div>

    <form id="reportForm" action="{{ route('system.security-reports.store') }}" method="POST">
        @csrf
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                {{-- Right Column: Selection & Info Card --}}
                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden sticky top-8">
                        <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50">
                            <h5 class="text-lg font-bold text-slate-800 header-font flex items-center gap-3">
                                <span class="w-2 h-6 bg-blue-600 rounded-full"></span>
                                اختيار الشخص
                            </h5>
                        </div>
                        <div class="p-8">
                            <div class="mb-6">
                                <label class="text-xs font-bold text-slate-400 mb-2 block px-2">ابحث عن الشخص *</label>
                                <select id="personal_data_id" name="personal_data_id" required
                                    class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all font-bold text-slate-700 select2-selection">
                                    <option value="">-- اختر الشخص --</option>
                                    @foreach($personalDataList as $id => $name)
                                        <option value="{{ $id }}" @selected(old('personal_data_id') == $id)>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Standardized Info-Card --}}
                            <div id="personal_info_box" class="hidden">
                                <div class="bg-blue-50 border border-blue-100 rounded-[2rem] p-6 text-[11px] mb-4">
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
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Left Column: Report Details --}}
                <div class="lg:col-span-8 space-y-8">
                    
                    {{-- Section 1: Basic Report Info --}}
                    <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
                        <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
                            <h5 class="text-lg font-bold text-slate-800 header-font flex items-center gap-3">
                                <span class="w-2 h-6 bg-emerald-500 rounded-full"></span>
                                بيانات البلاغ الأساسية
                            </h5>
                            <span class="text-[10px] bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full font-bold">إلزامي</span>
                        </div>
                        <div class="p-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <label class="text-xs font-bold text-slate-400 mb-2 block px-2">موضوع البلاغ / العنوان *</label>
                                    <input type="text" name="report_name" required
                                        class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all font-bold text-slate-700"
                                        placeholder="مثال: اشتباه في نشاط معادي" value="{{ old('report_name') }}">
                                </div>

                                <div>
                                    <label class="text-xs font-bold text-slate-400 mb-2 block px-2">نوع البلاغ</label>
                                    <input type="text" name="report_type"
                                        class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all font-bold text-slate-700"
                                        placeholder="تصنيف البلاغ" value="{{ old('report_type') }}">
                                </div>

                                <div>
                                    <label class="text-xs font-bold text-slate-400 mb-2 block px-2">تاريخ البلاغ</label>
                                    <input type="date" name="report_date"
                                        class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-emerald-100 transition-all font-bold text-slate-700"
                                        value="{{ old('report_date', date('Y-m-d')) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section 2: Unit & Rank --}}
                    <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
                        <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50">
                            <h5 class="text-lg font-bold text-slate-800 header-font flex items-center gap-3">
                                <span class="w-2 h-6 bg-blue-500 rounded-full"></span>
                                بيانات الوحدة والعمل
                            </h5>
                        </div>
                        <div class="p-8">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="text-xs font-bold text-slate-400 mb-2 block px-2">الوحدة</label>
                                    <input type="text" name="unit" class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all font-bold text-slate-700" value="{{ old('unit') }}">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 mb-2 block px-2">الوحدة الفرعية</label>
                                    <input type="text" name="sub_unit" class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all font-bold text-slate-700" value="{{ old('sub_unit') }}">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 mb-2 block px-2">الوحدة التفصيلية</label>
                                    <input type="text" name="detailed_unit" class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all font-bold text-slate-700" value="{{ old('detailed_unit') }}">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 mb-2 block px-2">الرتبة</label>
                                    <input type="text" name="rank" class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all font-bold text-slate-700" value="{{ old('rank') }}">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 mb-2 block px-2">التعيين</label>
                                    <input type="text" name="designation" class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all font-bold text-slate-700" value="{{ old('designation') }}">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 mb-2 block px-2">الجهة الداخلية</label>
                                    <select name="internal_entity_id" class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all font-bold text-slate-700">
                                        <option value="">-- اختر الجهة --</option>
                                        @foreach($entities as $entity)
                                            <option value="{{ $entity->id }}" @selected(old('internal_entity_id') == $entity->id)>{{ $entity->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section 3: Legal & Actions --}}
                    <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
                        <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50">
                            <h5 class="text-lg font-bold text-slate-800 header-font flex items-center gap-3">
                                <span class="w-2 h-6 bg-rose-500 rounded-full"></span>
                                الإجراءات والحالة القانونية
                            </h5>
                        </div>
                        <div class="p-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <label class="text-xs font-bold text-slate-400 mb-2 block px-2">التهمة</label>
                                    <textarea name="charge" rows="2" class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-rose-100 transition-all font-bold text-slate-700">{{ old('charge') }}</textarea>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 mb-2 block px-2">حالة القبض</label>
                                    <input type="text" name="arrest_status" class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-rose-100 transition-all font-bold text-slate-700" value="{{ old('arrest_status') }}">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 mb-2 block px-2">تاريخ القبض</label>
                                    <input type="date" name="arrest_date" class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-rose-100 transition-all font-bold text-slate-700" value="{{ old('arrest_date') }}">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="text-xs font-bold text-slate-400 mb-2 block px-2">الإجراءات المتخذة</label>
                                    <textarea name="procedures" rows="2" class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-rose-100 transition-all font-bold text-slate-700">{{ old('procedures') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section 4: Notes --}}
                    <div class="bg-white rounded-[2.5rem] soft-shadow border border-slate-100 overflow-hidden">
                        <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50">
                            <h5 class="text-lg font-bold text-slate-800 header-font flex items-center gap-3">
                                <span class="w-2 h-6 bg-amber-400 rounded-full"></span>
                                ملاحظات إضافية
                            </h5>
                        </div>
                        <div class="p-8">
                            <textarea name="notes" rows="4"
                                class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-amber-100 transition-all font-bold text-slate-700"
                                placeholder="أدخل أي تفاصيل أو ملاحظات أخرى هنا...">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit"
                            class="px-12 py-5 bg-blue-600 text-white rounded-[2rem] font-bold hover:bg-blue-700 transition-all shadow-2xl shadow-blue-100 flex items-center gap-3 text-xl">
                            <i class="fas fa-check-double"></i> اعتماد وحفظ البلاغ
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        const personSelect = $('#personal_data_id');
        const infoBox = $('#personal_info_box');

        if ($.fn.select2) {
            $('.select2-selection').select2({
                theme: 'bootstrap-5',
                dir: 'rtl',
                width: '100%'
            });
        }

        personSelect.on('change', function() {
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
