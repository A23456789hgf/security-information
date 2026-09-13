@extends('layouts.app')

@section('title', 'إضافة بلاغ أمني')
@section('page_title', 'السجلات الأساسية')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto" dir="rtl">
    {{-- Breadcrumbs --}}
    <nav class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-2 print:hidden">
        <a href="{{ route('dashboard') }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">الرئيسية</a>
        <span>/</span>
        <a href="{{ route('system.security-reports.index') }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">البلاغات الأمنية</a>
        <span>/</span>
        <span class="text-slate-900 dark:text-white font-semibold">إضافة بلاغ جديد</span>
    </nav>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-2 print:hidden">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center font-bold shadow-sm shrink-0">
                <i class="fas fa-file-contract text-xl"></i>
            </div>
            <div>
                <h1 class="font-display font-bold text-2xl text-slate-900 dark:text-white m-0">إضافة بلاغ أمني جديد</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">تسجيل بلاغ أمني مفصل وربطه بملف الشخص المعني.</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('system.security-reports.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 font-bold text-sm rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors shadow-sm">
                <i class="fas fa-arrow-right"></i>
                <span>عودة</span>
            </a>
            <button type="submit" form="reportForm"
                class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm shadow-sm shadow-brand-500/30 transition-all flex items-center gap-2">
                <i class="fas fa-save"></i>
                <span>حفظ البلاغ</span>
            </button>
        </div>
    </div>

    <form id="reportForm" action="{{ route('system.security-reports.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            {{-- Right Column: Person Selection & Live Info Box --}}
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 lg:sticky lg:top-24 transition-colors">
                    <div class="mb-5">
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">الشخص المعني بالبلاغ <span class="text-red-500">*</span></label>
                        <select id="personal_data_id" name="personal_data_id" required
                            class="w-full select2-selection bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none">
                            <option value="">-- اختر الشخص المعني --</option>
                            @foreach($personalDataList as $id => $name)
                                <option value="{{ $id }}" @selected(old('personal_data_id') == $id)>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Live Info Box --}}
                    <div id="personal_info_box" class="hidden">
                        <div class="p-4 rounded-xl bg-brand-50/50 dark:bg-brand-500/5 border border-brand-100 dark:border-brand-500/20 text-xs space-y-3">
                            {{-- Photo & Main Details Header --}}
                            <div class="flex items-center gap-3 p-2 bg-white dark:bg-slate-900 rounded-xl border border-brand-100 dark:border-brand-500/20">
                                <div class="w-14 h-14 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shrink-0 flex items-center justify-center shadow-xs">
                                    <img id="info_photo" src="" alt="الصورة الشخصية" class="w-full h-full object-cover hidden">
                                    <i id="info_photo_placeholder" class="fas fa-user text-slate-400 dark:text-slate-500 text-2xl"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <strong class="block font-bold text-brand-700 dark:text-brand-300 text-xs mb-1">
                                        <i class="fas fa-id-card me-1"></i> بيانات الشخص المختار:
                                    </strong>
                                    <div id="info_person_name" class="font-bold text-slate-800 dark:text-slate-200 text-xs truncate"></div>
                                    <div id="info_person_sub" class="text-[10px] text-slate-400 dark:text-slate-500 truncate"></div>
                                </div>
                            </div>

                            <div class="space-y-2 text-slate-600 dark:text-slate-400">
                                <div class="flex items-center justify-between">
                                    <span>الاسم الجهادي:</span>
                                    <span id="info_jihadi_name" class="font-bold text-slate-900 dark:text-white">-</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>الرقم العسكري:</span>
                                    <span id="info_military_number" class="font-mono font-bold text-brand-600 dark:text-brand-400">-</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>رقم الهوية:</span>
                                    <span id="info_id_number" class="font-mono font-bold text-slate-900 dark:text-white">-</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>فصيلة الدم:</span>
                                    <span id="info_blood_type" class="font-bold text-rose-600">-</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>تاريخ الميلاد:</span>
                                    <span id="info_dob" class="font-bold text-slate-900 dark:text-white">-</span>
                                </div>
                                <div class="flex items-center justify-between border-t border-brand-100 dark:border-brand-500/20 pt-2">
                                    <span>الهاتف:</span>
                                    <span id="info_phone_1" class="font-mono font-bold text-slate-900 dark:text-white">-</span>
                                </div>
                                <div class="border-t border-brand-100 dark:border-brand-500/20 pt-2">
                                    <span class="block mb-1">العنوان:</span>
                                    <span id="info_address" class="font-bold text-slate-900 dark:text-white block">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Left Column: Report Details --}}
            <div class="lg:col-span-8 space-y-6">
                
                {{-- Section 1: Basic Report Info --}}
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/30">
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-5 bg-brand-600 rounded-full"></div>
                            <h2 class="font-display font-bold text-base text-slate-900 dark:text-white m-0">بيانات البلاغ الأساسية</h2>
                        </div>
                        <span class="text-[10px] bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 px-2.5 py-1 rounded-full font-bold">إلزامي</span>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">موضوع البلاغ / العنوان <span class="text-red-500">*</span></label>
                            <input type="text" name="report_name" required
                                class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none"
                                placeholder="مثال: اشتباه في نشاط معادٍ" value="{{ old('report_name') }}">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">نوع البلاغ</label>
                            <input type="text" name="report_type"
                                class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none"
                                placeholder="تصنيف البلاغ" value="{{ old('report_type') }}">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">تاريخ البلاغ</label>
                            <input type="date" name="report_date"
                                class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none"
                                value="{{ old('report_date', date('Y-m-d')) }}">
                        </div>
                    </div>
                </div>

                {{-- Section 2: Unit and Rank --}}
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center gap-3 bg-slate-50/50 dark:bg-slate-800/30">
                        <div class="w-2 h-5 bg-emerald-500 rounded-full"></div>
                        <h2 class="font-display font-bold text-base text-slate-900 dark:text-white m-0">بيانات الوحدة والعمل</h2>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">الوحدة</label>
                            <input type="text" name="unit" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white" value="{{ old('unit') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">الوحدة الفرعية</label>
                            <input type="text" name="sub_unit" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white" value="{{ old('sub_unit') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">الوحدة التفصيلية</label>
                            <input type="text" name="detailed_unit" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white" value="{{ old('detailed_unit') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">الرتبة</label>
                            <input type="text" name="rank" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white" value="{{ old('rank') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">التعيين</label>
                            <input type="text" name="designation" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white" value="{{ old('designation') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">الجهة الداخلية</label>
                            <select name="internal_entity_id" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white">
                                <option value="">-- اختر الجهة --</option>
                                @foreach($entities as $entity)
                                    <option value="{{ $entity->id }}" @selected(old('internal_entity_id') == $entity->id)>{{ $entity->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Section 3: Legal & Actions --}}
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center gap-3 bg-slate-50/50 dark:bg-slate-800/30">
                        <div class="w-2 h-5 bg-rose-500 rounded-full"></div>
                        <h2 class="font-display font-bold text-base text-slate-900 dark:text-white m-0">الإجراءات والحالة القانونية</h2>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">التهمة المنسوبة</label>
                            <textarea name="charge" rows="2" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white">{{ old('charge') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">حالة القبض</label>
                            <input type="text" name="arrest_status" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white" value="{{ old('arrest_status') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">تاريخ القبض</label>
                            <input type="date" name="arrest_date" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white" value="{{ old('arrest_date') }}">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">الإجراءات المتخذة</label>
                            <textarea name="procedures" rows="2" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white">{{ old('procedures') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Section 4: Notes --}}
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center gap-3 bg-slate-50/50 dark:bg-slate-800/30">
                        <div class="w-2 h-5 bg-amber-500 rounded-full"></div>
                        <h2 class="font-display font-bold text-base text-slate-900 dark:text-white m-0">ملاحظات إضافية</h2>
                    </div>
                    <div class="p-6">
                        <textarea name="notes" rows="3"
                            class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white placeholder:text-slate-400"
                            placeholder="أدخل أي تفاصيل أو ملاحظات استخباراتية أخرى...">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-800">
                    <a href="{{ route('system.security-reports.index') }}"
                        class="px-6 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 font-semibold transition-colors text-sm">
                        إلغاء
                    </a>
                    <button type="submit"
                        class="px-8 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm shadow-sm shadow-brand-500/30 transition-all flex items-center gap-2">
                        <i class="fas fa-check"></i>
                        <span>اعتماد وحفظ البلاغ</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        const personSelect = $('#personal_data_id');
        const infoBox = $('#personal_info_box');

        personSelect.on('change', function() {
            const personId = $(this).val();
            if (!personId) {
                infoBox.addClass('hidden');
                return;
            }

            fetch(`/system/personal-data/details/${personId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.photo_url) {
                        $('#info_photo').attr('src', data.photo_url).removeClass('hidden');
                        $('#info_photo_placeholder').addClass('hidden');
                    } else {
                        $('#info_photo').attr('src', '').addClass('hidden');
                        $('#info_photo_placeholder').removeClass('hidden');
                    }
                    $('#info_person_name').text(data.name || '-');
                    $('#info_person_sub').text((data.military_number && data.military_number !== '-' ? 'الرقم العسكري: ' + data.military_number : '') + (data.jihadi_name && data.jihadi_name !== '-' ? ' | ' + data.jihadi_name : ''));
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

