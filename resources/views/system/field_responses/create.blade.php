@extends('layouts.app')

@section('title', 'إضافة رد ميداني جديد')
@section('page_title', 'السجلات الأمنية')

@section('content')
<div class="space-y-6" dir="rtl">
    {{-- Breadcrumbs --}}
    <nav class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-2">
        <a href="{{ route('dashboard') }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">الرئيسية</a>
        <span>/</span>
        <a href="{{ route('system.field-responses.index') }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">الرد الميداني</a>
        <span>/</span>
        <span class="text-slate-900 dark:text-white font-semibold">إضافة جديد</span>
    </nav>

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center font-bold shadow-sm shrink-0">
                <i class="fas fa-plus text-xl"></i>
            </div>
            <div>
                <h1 class="font-display font-bold text-2xl text-slate-900 dark:text-white m-0">إضافة رد ميداني جديد</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">قم بتعبئة بيانات الرد الميداني للشخص المعني.</p>
            </div>
        </div>
        <a href="{{ route('system.field-responses.index') }}"
            class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors text-xs font-bold">
            <i class="fas fa-arrow-right ml-1"></i> عودة للقائمة
        </a>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 text-rose-800 dark:text-rose-300 px-4 py-3 rounded-2xl">
            <ul class="list-disc list-inside text-sm font-bold space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('system.field-responses.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Section 1: Person Selection & Comprehensive Live Card --}}
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-6">
            <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2 border-b border-slate-100 dark:border-slate-800 pb-3">
                <i class="fas fa-user-check text-brand-500"></i>
                <span>اختيار الشخص المعني وكارت البيانات الكاملة</span>
            </h2>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                <div class="lg:col-span-5 space-y-4">
                    <label for="personal_data_id" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">
                        اختر أو ابحث عن الشخص من القائمة <span class="text-rose-500">*</span>
                    </label>
                    <select name="personal_data_id" id="personal_data_id" class="select2-searchable w-full" required>
                        <option value="">-- اختر أو ابحث باسم الشخص / الرقم الوطني --</option>
                        @foreach($personalDataList as $person)
                            <option value="{{ $person->id }}" {{ old('personal_data_id') == $person->id ? 'selected' : '' }}>
                                {{ $person->name }} {{ $person->national_id_number ? "({$person->national_id_number})" : '' }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-[11px] text-slate-400">يمكنك الكتابة داخل القائمة المنسدلة للبحث الفوري عن أي فرد بالاسم أو الرقم الوطني.</p>
                </div>

                {{-- Full Person Live Card --}}
                <div class="lg:col-span-7">
                    <div id="person_card" class="bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-sm transition-all">
                        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5">
                            {{-- Person Photo --}}
                            <div id="person_photo_container" class="w-24 h-24 rounded-2xl bg-slate-200 dark:bg-slate-800 overflow-hidden shrink-0 flex items-center justify-center border-2 border-slate-300 dark:border-slate-700 shadow-sm">
                                <i class="fas fa-user text-3xl text-slate-400"></i>
                            </div>

                            {{-- Person Header & Attributes Grid --}}
                            <div class="flex-1 w-full space-y-3">
                                <div>
                                    <h3 id="person_name" class="font-bold text-lg text-slate-900 dark:text-white m-0">لم يتم اختيار شخص</h3>
                                    <p id="person_job_title" class="text-xs font-semibold text-brand-600 dark:text-brand-400 m-0 mt-0.5">الصفة / الوظيفة: -</p>
                                </div>

                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 text-xs border-t border-slate-200/60 dark:border-slate-800/80 pt-3">
                                    <div class="bg-white dark:bg-slate-900 p-2 rounded-xl border border-slate-200/80 dark:border-slate-800">
                                        <span class="block text-[10px] text-slate-400 font-bold">الرقم الوطني:</span>
                                        <strong id="person_national_id" class="text-slate-800 dark:text-slate-200 font-mono text-[11px]">-</strong>
                                    </div>
                                    <div class="bg-white dark:bg-slate-900 p-2 rounded-xl border border-slate-200/80 dark:border-slate-800">
                                        <span class="block text-[10px] text-slate-400 font-bold">الرقم العسكري:</span>
                                        <strong id="person_military_id" class="text-slate-800 dark:text-slate-200 font-mono text-[11px]">-</strong>
                                    </div>
                                    <div class="bg-white dark:bg-slate-900 p-2 rounded-xl border border-slate-200/80 dark:border-slate-800">
                                        <span class="block text-[10px] text-slate-400 font-bold">الرقم الجهادي:</span>
                                        <strong id="person_jihadist_id" class="text-slate-800 dark:text-slate-200 text-[11px]">-</strong>
                                    </div>
                                    <div class="bg-white dark:bg-slate-900 p-2 rounded-xl border border-slate-200/80 dark:border-slate-800">
                                        <span class="block text-[10px] text-slate-400 font-bold">الجهة التابع لها:</span>
                                        <strong id="person_entity" class="text-slate-800 dark:text-slate-200 text-[11px]">-</strong>
                                    </div>
                                    <div class="bg-white dark:bg-slate-900 p-2 rounded-xl border border-slate-200/80 dark:border-slate-800">
                                        <span class="block text-[10px] text-slate-400 font-bold">المؤهل العلمي:</span>
                                        <strong id="person_qualification" class="text-slate-800 dark:text-slate-200 text-[11px]">-</strong>
                                    </div>
                                    <div class="bg-white dark:bg-slate-900 p-2 rounded-xl border border-slate-200/80 dark:border-slate-800">
                                        <span class="block text-[10px] text-slate-400 font-bold">فصيلة الدم:</span>
                                        <strong id="person_blood_type" class="text-slate-800 dark:text-slate-200 text-[11px]">-</strong>
                                    </div>
                                </div>

                                <div class="bg-white dark:bg-slate-900 p-2.5 rounded-xl border border-slate-200/80 dark:border-slate-800 text-xs flex flex-wrap justify-between gap-2">
                                    <div>
                                        <span class="text-[10px] text-slate-400 font-bold">تاريخ الميلاد: </span>
                                        <strong id="person_dob" class="text-slate-800 dark:text-slate-200 font-mono text-[11px]">-</strong>
                                    </div>
                                    <div>
                                        <span class="text-[10px] text-slate-400 font-bold">العنوان: </span>
                                        <strong id="person_address" class="text-slate-800 dark:text-slate-200 text-[11px]">-</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 2: Response Details --}}
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-6">
            <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2 border-b border-slate-100 dark:border-slate-800 pb-3">
                <i class="fas fa-satellite-dish text-brand-500"></i>
                <span>تفاصيل الرد الميداني</span>
            </h2>

            <div class="space-y-4">
                <div>
                    <label for="field_response" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">
                        نص الرد الميداني <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="field_response" id="field_response" rows="4" required
                        class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500"
                        placeholder="أدخل نص الرد الميداني بالتفصيل...">{{ old('field_response') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="response_date" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">
                            تاريخ الرد
                        </label>
                        <input type="date" name="response_date" id="response_date" value="{{ old('response_date', date('Y-m-d')) }}"
                            class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                    </div>

                    <div>
                        <label for="source" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">
                            المصدر (يمكن الاختيار أو الكتابة المباشرة)
                        </label>
                        <input type="text" name="source" id="source" value="{{ old('source') }}"
                            class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500"
                            placeholder="اسم أو رمز المصدر الميداني">
                    </div>

                    <div>
                        <label for="source_job" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">
                            عمل المصدر
                        </label>
                        <input type="text" name="source_job" id="source_job" value="{{ old('source_job') }}"
                            class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500"
                            placeholder="الصفة / الوظيفة للمصدر">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="internal_entity_id" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">
                            الجهة التابع لها البلاغ/الرد (قابل للبحث والتدقيق)
                        </label>
                        <select name="internal_entity_id" id="internal_entity_id" class="select2-searchable w-full">
                            <option value="">-- اختياري (سيتم استخدام جهة الشخص إن لم تُحدد) --</option>
                            @foreach($entities as $entity)
                                <option value="{{ $entity->id }}" {{ old('internal_entity_id') == $entity->id ? 'selected' : '' }}>
                                    {{ $entity->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="notes" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">
                            الملاحظات
                        </label>
                        <textarea name="notes" id="notes" rows="2"
                            class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500"
                            placeholder="أية ملاحظات إضافية...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Controls --}}
        <div class="flex items-center justify-end gap-3 pt-4">
            <a href="{{ route('system.field-responses.index') }}"
                class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors text-xs font-bold">
                إلغاء
            </a>
            <button type="submit"
                class="px-6 py-2.5 bg-brand-600 text-white rounded-xl hover:bg-brand-700 transition-all text-xs font-bold shadow-sm shadow-brand-500/30">
                <i class="fas fa-save ml-1"></i> حفظ الرد الميداني
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize all select2 dropdowns with full search & type capabilities
        $('.select2-searchable').select2({
            dir: "rtl",
            allowClear: true,
            width: '100%',
            minimumResultsForSearch: 0,
            placeholder: "-- اختر من القائمة أو ابحث ببدء الكتابة --"
        });

        $('#personal_data_id').on('change', function() {
            var personId = $(this).val();
            if (!personId) {
                resetPersonCard();
                return;
            }

            $.ajax({
                url: '{{ url("/system/field-responses/personal-data") }}/' + personId,
                type: 'GET',
                success: function(res) {
                    if (res.success && res.person) {
                        var p = res.person;
                        $('#person_name').text(p.name);
                        $('#person_job_title').text('الصفة / الوظيفة: ' + (p.job_title || '-'));
                        $('#person_national_id').text(p.national_id_number);
                        $('#person_military_id').text(p.military_number);
                        $('#person_jihadist_id').text(p.jihadist_number);
                        $('#person_entity').text(p.entity_name);
                        $('#person_qualification').text(p.qualification);
                        $('#person_blood_type').text(p.blood_type);
                        $('#person_dob').text(p.date_of_birth);

                        var addressParts = [];
                        if (p.governorate !== '-') addressParts.push(p.governorate);
                        if (p.directorate !== '-') addressParts.push(p.directorate);
                        if (p.sub_area !== '-') addressParts.push(p.sub_area);
                        if (p.village !== '-') addressParts.push(p.village);

                        $('#person_address').text(addressParts.length ? addressParts.join(' - ') : 'غير محدد');

                        if (p.photo_url) {
                            $('#person_photo_container').html('<img src="' + p.photo_url + '" class="w-full h-full object-cover">');
                        } else {
                            $('#person_photo_container').html('<i class="fas fa-user text-3xl text-slate-400"></i>');
                        }
                    }
                },
                error: function() {
                    resetPersonCard();
                }
            });
        });

        function resetPersonCard() {
            $('#person_name').text('لم يتم اختيار شخص');
            $('#person_job_title').text('الصفة / الوظيفة: -');
            $('#person_national_id').text('-');
            $('#person_military_id').text('-');
            $('#person_jihadist_id').text('-');
            $('#person_entity').text('-');
            $('#person_qualification').text('-');
            $('#person_blood_type').text('-');
            $('#person_dob').text('-');
            $('#person_address').text('-');
            $('#person_photo_container').html('<i class="fas fa-user text-3xl text-slate-400"></i>');
        }

        if ($('#personal_data_id').val()) {
            $('#personal_data_id').trigger('change');
        }
    });
</script>
@endpush
@endsection
