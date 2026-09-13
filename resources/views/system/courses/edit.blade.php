@extends('layouts.app')

@section('content')
<div class="space-y-6">
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="p-2.5 bg-brand-500/10 text-brand-600 dark:text-brand-400 rounded-xl">
                <i class="fas fa-edit text-xl"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-900 dark:text-white">تعديل الدورة التدريبية</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400">تحديث بيانات الدورة التدريبية المسجلة للفرد</p>
            </div>
        </div>
        <a href="{{ route('system.courses.index') }}"
           class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-750 text-slate-700 dark:text-slate-200 text-sm font-semibold rounded-xl shadow-sm transition-all">
            <i class="fas fa-arrow-right text-xs"></i>
            <span>عودة للقائمة</span>
        </a>
    </div>

    <form id="courseForm" action="{{ route('system.courses.update', $course->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            {{-- Right Column: Person Selection & Info bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden mb-4 --}}
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm space-y-4">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2 pb-3 border-b border-slate-100 dark:border-slate-800">
                        <span class="w-1.5 h-4 bg-brand-500 rounded-full"></span>
                        بيانات المتدرب
                    </h3>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">المتدرب المختار <span class="text-rose-500">*</span></label>
                        <select id="personal_data_id" name="personal_data_id" required
                                class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all select2-selection">
                            <option value="">-- اختر الاسم --</option>
                            @foreach($allPeople as $person)
                                <option value="{{ $person->id }}" @selected(old('personal_data_id', $course->personal_data_id) == $person->id)>
                                    {{ $person->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Standardized Info Box --}}
                    <div id="personal_info_box" class="hidden">
                        <div class="bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl p-4 text-xs space-y-3">
                            {{-- Photo & Main Details Header --}}
                            <div class="flex items-center gap-3 p-2 bg-white dark:bg-slate-900 rounded-xl border border-slate-100 dark:border-slate-800">
                                <div class="w-14 h-14 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shrink-0 flex items-center justify-center shadow-xs">
                                    <img id="info_photo" src="" alt="الصورة الشخصية" class="w-full h-full object-cover hidden">
                                    <i id="info_photo_placeholder" class="fas fa-user text-slate-400 dark:text-slate-500 text-2xl"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="font-bold text-brand-600 dark:text-brand-400 text-xs flex items-center gap-1.5 mb-1">
                                        <i class="fas fa-id-card"></i>
                                        <span>تفاصيل المتدرب المختار:</span>
                                    </div>
                                    <div id="info_person_name" class="font-bold text-slate-800 dark:text-slate-200 text-xs truncate"></div>
                                    <div id="info_person_sub" class="text-[10px] text-slate-400 dark:text-slate-500 truncate"></div>
                                </div>
                            </div>

                            <div class="space-y-2 text-slate-700 dark:text-slate-300">
                                <div class="flex items-center justify-between"><span class="text-slate-500 dark:text-slate-400">الاسم الجهادي:</span> <span id="info_jihadi_name" class="font-semibold text-slate-900 dark:text-white">-</span></div>
                                <div class="flex items-center justify-between"><span class="text-slate-500 dark:text-slate-400">الرقم العسكري:</span> <span id="info_military_number" class="font-semibold text-slate-900 dark:text-white">-</span></div>
                                <div class="flex items-center justify-between"><span class="text-slate-500 dark:text-slate-400">رقم الهوية:</span> <span id="info_id_number" class="font-semibold text-slate-900 dark:text-white">-</span></div>
                                <div class="flex items-center justify-between"><span class="text-slate-500 dark:text-slate-400">فصيلة الدم:</span> <span id="info_blood_type" class="text-rose-600 dark:text-rose-400 font-bold">-</span></div>
                                <div class="flex items-center justify-between"><span class="text-slate-500 dark:text-slate-400">تاريخ الميلاد:</span> <span id="info_dob" class="font-semibold text-slate-900 dark:text-white">-</span></div>
                                <div class="flex items-center justify-between pt-2 border-t border-slate-200 dark:border-slate-800"><span class="text-slate-500 dark:text-slate-400">هاتف 1:</span> <span id="info_phone_1" class="font-semibold text-slate-900 dark:text-white">-</span></div>
                                <div class="flex flex-col pt-2 border-t border-slate-200 dark:border-slate-800"><span class="text-slate-500 dark:text-slate-400 mb-1">العنوان:</span> <span id="info_address" class="font-semibold text-slate-900 dark:text-white">-</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Left Column: Course Details --}}
            <div class="lg:col-span-8 space-y-6">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2 pb-4 mb-6 border-b border-slate-100 dark:border-slate-800">
                        <span class="w-1.5 h-4 bg-brand-500 rounded-full"></span>
                        تفاصيل الدورة التدريبية
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">نوع الدورة <span class="text-rose-500">*</span></label>
                            <select name="course_type_id" required
                                    class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all">
                                <option value="">-- اختر نوع الدورة --</option>
                                @foreach($courseTypes as $id => $type)
                                    <option value="{{ $id }}" @selected(old('course_type_id', $course->course_type_id) == $id)>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">تاريخ الدورة (ميلادي) <span class="text-rose-500">*</span></label>
                            <input type="date" name="course_date_gregorian" required id="course_date_gregorian"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all"
                                   value="{{ old('course_date_gregorian', $course->course_date_gregorian?->format('Y-m-d')) }}">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">تاريخ الدورة (هجري)</label>
                            <input type="text" name="course_date_hijri" id="course_date_hijri"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all"
                                   placeholder="يوم/شهر/سنة" value="{{ old('course_date_hijri', $course->course_date_hijri) }}">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">مدة الدورة</label>
                            <input type="text" name="course_duration"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all"
                                   placeholder="مثلاً: شهر" value="{{ old('course_duration', $course->course_duration) }}">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">جهة الإرسال</label>
                            <select name="sending_entity_id"
                                    class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all">
                                <option value="">-- اختر جهة الإرسال --</option>
                                @foreach($fillingAgencies as $id => $agency)
                                    <option value="{{ $id }}" @selected(old('sending_entity_id', $course->sending_entity_id) == $id)>{{ $agency }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">مسؤول الدورة</label>
                            <input type="text" name="course_administrator"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all"
                                   placeholder="اسم المسؤول" value="{{ old('course_administrator', $course->course_administrator) }}">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">ملاحظات إضافية</label>
                            <textarea name="notes" rows="3"
                                      class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all"
                                      placeholder="أدخل أي ملاحظات هنا...">{{ old('notes', $course->notes) }}</textarea>
                        </div>
                    </div>

                    {{-- Add New Courses (Optional) --}}
                    <div class="mt-8 pt-8 border-t border-slate-200 dark:border-slate-800">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2 mb-6">
                            <span class="w-2 h-6 bg-emerald-500 rounded-full"></span>
                            إضافة دورات أخرى (اختياري)
                        </h2>
                        <div id="new-courses-container" class="space-y-6">
                            {{-- Templates will be appended here --}}
                        </div>
                        
                        <div class="flex justify-center mt-6">
                            <button type="button" id="add-new-course-btn"
                                class="inline-flex items-center gap-2 px-6 py-3 bg-brand-50 hover:bg-brand-100 text-brand-700 dark:bg-brand-500/10 dark:hover:bg-brand-500/20 dark:text-brand-400 rounded-2xl text-sm font-bold border border-brand-200 dark:border-brand-500/20 transition-all border-dashed hover:border-solid">
                                <i class="fas fa-plus"></i>
                                إضافة دورة جديدة
                            </button>
                        </div>
                    </div>

                    <div class="mb-4">
            <label for="gregorian_year" class="block text-sm font-medium text-gray-700 dark:text-gray-200">السنة الميلادية</label>
            <input type="number" name="gregorian_year" id="gregorian_year" value="{{ old('gregorian_year', $course->gregorian_year ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
        </div>
        <div class="mb-4">
            <label for="hijri_year" class="block text-sm font-medium text-gray-700 dark:text-gray-200">السنة الهجرية (محسوبة)</label>
            <input type="text" id="hijri_year" readonly class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-800 shadow-sm sm:text-sm" placeholder="سيتم حسابها تلقائيًا">
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-hijri/2.1.2/moment-hijri.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function(){
                const gInput = document.getElementById('gregorian_year');
                const hInput = document.getElementById('hijri_year');
                function updateHijri(){
                    const gYear = gInput.value;
                    if(gYear){
                        const hijri = moment(`${gYear}-01-01`, 'YYYY-MM-DD').format('iYYYY');
                        hInput.value = hijri;
                    } else {
                        hInput.value = '';
                    }
                }
                gInput.addEventListener('input', updateHijri);
                // initial load
                updateHijri();
            });
        </script>

                    {{-- Template for New Course --}}
                    <template id="new-course-template">
                        <div class="course-entry bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm relative transition-all">
                            <div class="p-6">
                                <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                                    <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                        <span class="w-2 h-5 bg-brand-500 rounded-full"></span>
                                        بيانات الدورة <span class="course-counter text-brand-600 dark:text-brand-400">#</span>
                                    </h2>
                                    <button type="button" class="remove-course-btn text-rose-500 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950 p-2 rounded-xl text-sm transition-colors flex items-center gap-2">
                                        <i class="fas fa-trash-alt"></i> إزالة
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">نوع الدورة <span class="text-rose-500">*</span></label>
                                        <select name="new_courses[INDEX][course_type_id]" required
                                                class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all">
                                            <option value="">-- اختر نوع الدورة --</option>
                                            @foreach($courseTypes as $id => $type)
                                                <option value="{{ $id }}">{{ $type }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">تاريخ الدورة (ميلادي) <span class="text-rose-500">*</span></label>
                                        <input type="date" name="new_courses[INDEX][course_date_gregorian]" required class="date-gregorian-input w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">تاريخ الدورة (هجري)</label>
                                        <input type="text" name="new_courses[INDEX][course_date_hijri]" class="date-hijri-input w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all" placeholder="يوم/شهر/سنة">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">مدة الدورة</label>
                                        <input type="text" name="new_courses[INDEX][course_duration]" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all" placeholder="مثلاً: شهر">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">جهة الإرسال</label>
                                        <select name="new_courses[INDEX][sending_entity_id]" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all">
                                            <option value="">-- اختر جهة الإرسال --</option>
                                            @foreach($fillingAgencies as $id => $agency)
                                                <option value="{{ $id }}">{{ $agency }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">مسؤول الدورة</label>
                                        <input type="text" name="new_courses[INDEX][course_administrator]" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all" placeholder="اسم المسؤول">
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">ملاحظات إضافية</label>
                                        <textarea name="new_courses[INDEX][notes]" rows="3" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all" placeholder="أدخل أي ملاحظات هنا..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <div class="pt-6 mt-6 border-t border-slate-100 dark:border-slate-800 flex justify-end">
                        <button type="submit"
                                class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                            <i class="fas fa-save"></i>
                            <span>حفظ التعديلات</span>
                        </button>
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
                tags: true,
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

        // Date Conversion for Main Form
        $('#course_date_gregorian').on('change', function() {
            if (!this.value) return;
            try {
                const date = new Date(this.value);
                const hijri = new Intl.DateTimeFormat('ar-SA-u-ca-islamic-umalqura-nu-latn', {
                    day: 'numeric', month: 'numeric', year: 'numeric'
                }).format(date);
                $('#course_date_hijri').val(hijri.replace(/[\u0660-\u0669]/g, function(d) {
                    return d.charCodeAt(0) - 1632;
                }).replace(/\u200f/g, '').replace(/\u200e/g, '').replace(/هـ/g, '').trim());
            } catch (e) {}
        });

        // --- Dynamic New Courses Form ---
        const newCoursesContainer = document.getElementById('new-courses-container');
        const addNewCourseBtn = document.getElementById('add-new-course-btn');
        const template = document.getElementById('new-course-template');
        let newCourseIndex = 0;

        if (addNewCourseBtn && template) {
            addNewCourseBtn.addEventListener('click', function() {
                const clone = template.content.cloneNode(true);
                const entry = clone.querySelector('.course-entry');
                
                // Replace INDEX placeholder
                entry.innerHTML = entry.innerHTML.replace(/INDEX/g, newCourseIndex);
                entry.querySelector('.course-counter').textContent = `#${newCourseIndex + 1}`;
                
                newCoursesContainer.appendChild(clone);
                newCourseIndex++;
            });

            newCoursesContainer.addEventListener('click', function(e) {
                const removeBtn = e.target.closest('.remove-course-btn');
                if (removeBtn) {
                    const entry = removeBtn.closest('.course-entry');
                    entry.remove();
                    
                    // Re-index counters visually
                    newCoursesContainer.querySelectorAll('.course-entry').forEach((el, index) => {
                        el.querySelector('.course-counter').textContent = `#${index + 1}`;
                    });
                }
            });

            // Date Conversion for Dynamic Inputs
            newCoursesContainer.addEventListener('change', function(e) {
                if (e.target.classList.contains('date-gregorian-input')) {
                    if (!e.target.value) return;
                    try {
                        const date = new Date(e.target.value);
                        const hijri = new Intl.DateTimeFormat('ar-SA-u-ca-islamic-umalqura-nu-latn', {
                            day: 'numeric', month: 'numeric', year: 'numeric'
                        }).format(date);
                        
                        const hijriVal = hijri.replace(/[\u0660-\u0669]/g, function(d) {
                            return d.charCodeAt(0) - 1632;
                        }).replace(/\u200f/g, '').replace(/\u200e/g, '').replace(/هـ/g, '').trim();
                        
                        const entry = e.target.closest('.course-entry');
                        const hijriInput = entry.querySelector('.date-hijri-input');
                        if (hijriInput) {
                            hijriInput.value = hijriVal;
                        }
                    } catch (err) {}
                }
            });
        }
    });
</script>
@endpush
@endsection


