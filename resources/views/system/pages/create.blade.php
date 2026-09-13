@extends('layouts.app')

@section('title', 'إضافة صفحة مخصصة')
@section('content')
<div class="space-y-6" dir="rtl">
    <!-- Header Banner -->
    <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col md:flex-flex flex-wrap gap-4 md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white flex items-center gap-3">
                <span class="w-10 h-10 rounded-2xl bg-brand-50 dark:bg-brand-900/30 text-brand-600 dark:text-brand-400 flex items-center justify-center text-lg shadow-sm">
                    <i class="fas fa-plus-circle"></i>
                </span>
                إضافة صفحة مخصصة جديدة
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm">قم بتعريف الصفحة الأساسية أولاً ثم أضف الحقول المخصصة لها.</p>
        </div>
        <div>
            <a href="{{ route('system.pages.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-2xl transition-all inline-flex items-center gap-2 text-sm">
                <i class="fas fa-arrow-right"></i>
                <span>العودة للقائمة</span>
            </a>
        </div>
    </div>

    <form action="{{ route('system.pages.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <!-- General Settings Card -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="bg-slate-50/80 dark:bg-slate-800/50 p-6 border-b border-slate-100 dark:border-slate-800">
                <h3 class="font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2 text-base">
                    <i class="fas fa-cog text-brand-500"></i> إعدادات الصفحة
                </h3>
            </div>
            <div class="p-6 md:p-8">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                    <div class="md:col-span-4">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">اسم الصفحة <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-sm focus:ring-2 focus:ring-brand-500/20 transition-all text-slate-800 dark:text-slate-200" required placeholder="أدخل اسم الصفحة" value="{{ old('name') }}">
                        <span class="text-xs text-slate-400 mt-1 block">سيتم توليد الرابط (URL) تلقائياً</span>
                    </div>
                    <div class="md:col-span-4">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">القسم الإداري <span class="text-rose-500">*</span></label>
                        <select name="section_id" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-sm focus:ring-2 focus:ring-brand-500/20 transition-all text-slate-800 dark:text-slate-200" required>
                            <option value="" selected disabled>اختر القسم الإداري</option>
                            @foreach($sections as $sec)
                                <option value="{{ $sec->id }}" {{ old('section_id') == $sec->id ? 'selected' : '' }}>
                                    {{ $sec->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-4">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">الأيقونة (Icon)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400">
                                <i class="fas fa-icons text-sm"></i>
                            </span>
                            <input type="text" name="icon" class="w-full pl-4 pr-10 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-sm focus:ring-2 focus:ring-brand-500/20 transition-all text-slate-800 dark:text-slate-200" placeholder="مثال: fa-users" value="{{ old('icon', 'fa-file-alt') }}" dir="ltr">
                        </div>
                        <span class="text-xs text-slate-400 mt-1 block">فئة FontAwesome</span>
                    </div>
                    <div class="md:col-span-3 flex items-center pt-6">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input class="w-4 h-4 text-brand-600 focus:ring-brand-500 border-gray-300 rounded" type="checkbox" name="is_unique_id" value="1" id="isUniqueId">
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300">معرف فريد (Unique ID)</span>
                        </label>
                    </div>
                    <div class="md:col-span-6">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input class="w-4 h-4 text-brand-600 focus:ring-brand-500 border-gray-300 rounded" type="checkbox" name="is_active" value="1" id="isActive" checked>
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300">تفعيل الصفحة</span>
                        </label>
                    </div>
                    <div class="md:col-span-6">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input class="w-4 h-4 text-brand-600 focus:ring-brand-500 border-gray-300 rounded" type="checkbox" name="show_in_sidebar" value="1" id="showInSidebar" checked>
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300">إظهار الصفحة في القائمة الجانبية</span>
                        </label>
                    </div>
                </div>

                <div class="col-span-1 md:col-span-6 mt-6 border-t border-slate-100 dark:border-slate-800 pt-6">
                    <label class="text-xs font-bold text-slate-400 mb-2 block px-2">موقع العرض في القائمة الجانبية (الترتيب داخل القسم)</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <label class="flex items-center gap-3 p-4 bg-slate-50 border border-slate-100 rounded-2xl cursor-pointer hover:bg-slate-100 transition-all">
                            <input type="radio" name="position_type" value="at_end" checked onchange="togglePageSelect()"
                                class="w-5 h-5 text-brand-600 rounded-full focus:ring-0">
                            <span class="font-bold text-slate-700 text-xs">في النهاية</span>
                        </label>
                        <label class="flex items-center gap-3 p-4 bg-slate-50 border border-slate-100 rounded-2xl cursor-pointer hover:bg-slate-100 transition-all">
                            <input type="radio" name="position_type" value="before" onchange="togglePageSelect()"
                                class="w-5 h-5 text-brand-600 rounded-full focus:ring-0">
                            <span class="font-bold text-slate-700 text-xs">قبل صفحة محددة</span>
                        </label>
                        <label class="flex items-center gap-3 p-4 bg-slate-50 border border-slate-100 rounded-2xl cursor-pointer hover:bg-slate-100 transition-all">
                            <input type="radio" name="position_type" value="after" onchange="togglePageSelect()"
                                class="w-5 h-5 text-brand-600 rounded-full focus:ring-0">
                            <span class="font-bold text-slate-700 text-xs">بعد صفحة محددة</span>
                        </label>
                    </div>
                    <div id="relativePageContainer" class="mt-4 hidden">
                        <select name="relative_page_id" id="relative_page_id"
                            class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-brand-100 transition-all font-bold text-slate-700">
                            <option value="">-- اختر الصفحة --</option>
                            @foreach($pages as $p)
                                <option value="{{ $p->id }}" data-section-id="{{ $p->section_id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                        <span class="text-[10px] text-slate-400 px-2 mt-1 block">تظهر فقط الصفحات التابعة لنفس القسم المحدد أعلاه.</span>
                    </div>
                </div>
                
                <script>
                    function togglePageSelect() {
                        const type = document.querySelector('input[name="position_type"]:checked').value;
                        const container = document.getElementById('relativePageContainer');
                        const select = document.getElementById('relative_page_id');
                        if (type === 'before' || type === 'after') {
                            container.classList.remove('hidden');
                            select.required = true;
                            filterPagesBySection();
                        } else {
                            container.classList.add('hidden');
                            select.required = false;
                        }
                    }

                    function filterPagesBySection() {
                        const sectionId = document.querySelector('select[name="section_id"]').value;
                        const select = document.getElementById('relative_page_id');
                        const options = select.querySelectorAll('option[data-section-id]');
                        let hasVisibleOptions = false;
                        
                        options.forEach(opt => {
                            if (opt.getAttribute('data-section-id') === sectionId) {
                                opt.style.display = '';
                                hasVisibleOptions = true;
                            } else {
                                opt.style.display = 'none';
                                if (select.value === opt.value) {
                                    select.value = "";
                                }
                            }
                        });
                    }
                    
                    // Initialize on load if section changes
                    document.addEventListener('DOMContentLoaded', function() {
                        const sectionSelect = document.querySelector('select[name="section_id"]');
                        if(sectionSelect) {
                            sectionSelect.addEventListener('change', filterPagesBySection);
                        }
                    });
                </script>
            </div>
        </div>

        <!-- Operations Selection Card -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden mb-6">
            <div class="bg-slate-50/80 dark:bg-slate-800/50 p-6 border-b border-slate-100 dark:border-slate-800">
                <h3 class="font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2 text-base">
                    <i class="fas fa-shield-alt text-brand-500"></i> صلاحيات وعمليات الصفحة (Operations)
                </h3>
                <p class="text-xs text-slate-500 mt-1">حدد العمليات التي تدعمها هذه الصفحة، سيتم توليد صلاحيات (Permissions) مخصصة لكل عملية تلقائياً.</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @foreach(\App\Models\Page::AVAILABLE_OPERATIONS as $key => $label)
                        <label class="flex items-center gap-2 cursor-pointer p-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700/50 rounded-xl hover:bg-slate-100 transition-colors">
                            <input class="w-4 h-4 text-brand-600 focus:ring-brand-500 border-gray-300 rounded" type="checkbox" name="operations[]" value="{{ $key }}" {{ in_array($key, ['view', 'create', 'edit', 'delete']) ? 'checked' : '' }}>
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Custom Fields Card -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="bg-slate-50/80 dark:bg-slate-800/50 p-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <h3 class="font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2 text-base">
                    <i class="fas fa-list text-brand-500"></i> تعريف حقول الصفحة
                </h3>
                <button type="button" class="px-4 py-2 bg-brand-50 dark:bg-brand-900/30 text-brand-600 dark:text-brand-400 hover:bg-brand-100 font-bold rounded-2xl transition-all text-xs flex items-center gap-1.5" id="add-field">
                    <i class="fas fa-plus"></i> إضافة حقل جديد
                </button>
            </div>
            <div class="p-0 overflow-x-auto">
                <table class="w-full text-right border-collapse" id="fields-table">
                    <thead>
                        <tr class="bg-slate-50/80 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800 text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                            <th class="py-3 px-6" style="width: 250px;">اسم الحقل</th>
                            <th class="py-3 px-4" style="width: 200px;">نوع البيانات</th>
                            <th class="py-3 px-4 text-center" style="width: 100px;">إلزامي؟</th>
                            <th class="py-3 px-4">إعدادات إضافية</th>
                            <th class="py-3 px-4 text-center" style="width: 80px;">إجراء</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                        <tr class="field-flex flex-wrap gap-4">
                            <td class="py-3 px-6">
                                <input type="text" name="fields[0][label]" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-2 focus:ring-brand-500/20 text-slate-800 dark:text-slate-200" required placeholder="مثلاً: الاسم الكامل">
                            </td>
                            <td class="py-3 px-4">
                                <select name="fields[0][type]" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-2 focus:ring-brand-500/20 field-type-select text-slate-800 dark:text-slate-200">
                                    <option value="text">بيانات (نص قصير)</option>
                                    <option value="textarea">نص (نص طويل)</option>
                                    <option value="date">تاريخ</option>
                                    <option value="datetime">تاريخ ووقت</option>
                                    <option value="number">أرقام (Int/Float/Currency)</option>
                                    <option value="checkbox">تحقق (نعم/لا)</option>
                                    <option value="select">قائمة منسدلة (خيارات)</option>
                                    <option value="link">ارتباط بصفحة أخرى</option>
                                    <option value="table">جدول فرعي (Child table)</option>
                                    <option value="file">مرفقات / صور</option>
                                </select>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <input class="w-4 h-4 text-brand-600 focus:ring-brand-500 border-gray-300 rounded" type="checkbox" name="fields[0][is_required]" value="1">
                            </td>
                            <td class="py-3 px-4">
                                <div class="extra-settings space-y-2">
                                    <!-- options for select -->
                                    <input type="text" name="fields[0][options]" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-2 focus:ring-brand-500/20 options-input hidden text-slate-800 dark:text-slate-200" placeholder="خيار1, خيار2, خيار3">
                                    
                                    <!-- page selection for link -->
                                    <select name="fields[0][metadata][target_page_id]" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-2 focus:ring-brand-500/20 link-page-select hidden text-slate-800 dark:text-slate-200">
                                        <option value="" disabled selected>اختر الصفحة المستهدفة</option>
                                        @foreach($pages as $p)
                                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                                        @endforeach
                                    </select>

                                    <!-- column names for table -->
                                    <input type="text" name="fields[0][metadata][table_columns]" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-2 focus:ring-brand-500/20 table-cols-input hidden text-slate-800 dark:text-slate-200" placeholder="أسماء الأعمدة (مثلاً: الاسم, السن, العنوان)">
                                </div>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <button type="button" class="w-8 h-8 rounded-full bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 hover:bg-rose-100 flex items-center justify-center mx-auto transition-colors remove-field disabled:opacity-30" disabled>
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4">
            <a href="{{ route('system.pages.index') }}" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-2xl transition-all text-sm">إلغاء</a>
            <button type="submit" class="px-8 py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-2xl shadow-lg shadow-brand-500/25 transition-all flex items-center gap-2 text-sm">
                <i class="fas fa-check-circle"></i>
                <span>إنشاء الصفحة</span>
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let fieldIndex = 1;
        const addBtn = document.getElementById('add-field');
        const tableBody = document.querySelector('#fields-table tbody');

        const pagesOptions = `@foreach($pages as $p)<option value="{{ $p->id }}">{{ $p->name }}</option>@endforeach`;

        addBtn.addEventListener('click', function() {
            const flex flex-wrap gap-4 = document.createElement('tr');
            flex flex-wrap gap-4.className = 'field-flex flex-wrap gap-4';
            flex flex-wrap gap-4.innerHTML = `
                <td class="ps-4">
                    <input type="text" name="fields[${fieldIndex}][label]" class="w-full pr-10 pl-4 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-brand-500/20 transition-all" required placeholder="مثلاً: ملاحظات إضافية">
                </td>
                <td>
                    <select name="fields[${fieldIndex}][type]" class="w-full pr-2 pl-2 py-2 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-brand-500/20 transition-all field-type-select">
                        <option value="text">بيانات (نص قصير)</option>
                        <option value="textarea">نص (نص طويل)</option>
                        <option value="date">تاريخ</option>
                        <option value="datetime">تاريخ ووقت</option>
                        <option value="number">أرقام (Int/Float/Currency)</option>
                        <option value="checkbox">تحقق (نعم/لا)</option>
                        <option value="select">قائمة منسدلة (خيارات)</option>
                        <option value="link">ارتباط بصفحة أخرى</option>
                        <option value="table">جدول فرعي (Child table)</option>
                        <option value="file">مرفقات / صور</option>
                    </select>
                </td>
                <td class="text-center">
                    <div class="form-check d-inline-block">
                        <input class="w-4 h-4 text-brand-600 focus:ring-brand-500 border-gray-300 rounded" type="checkbox" name="fields[${fieldIndex}][is_required]" value="1">
                    </div>
                </td>
                <td>
                    <div class="extra-settings">
                        <input type="text" name="fields[${fieldIndex}][options]" class="w-full pr-10 pl-4 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-brand-500/20 transition-all options-input d-none" placeholder="خيار1, خيار2, خيار3">
                        <select name="fields[${fieldIndex}][metadata][target_page_id]" class="w-full pr-2 pl-2 py-2 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-brand-500/20 transition-all link-page-select d-none">
                            <option value="" disabled selected>اختر الصفحة المستهدفة</option>
                            ${pagesOptions}
                        </select>
                        <input type="text" name="fields[${fieldIndex}][metadata][table_columns]" class="w-full pr-10 pl-4 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-brand-500/20 transition-all table-cols-input d-none" placeholder="أسماء الأعمدة (مثلاً: الاسم, السن, العنوان)">
                    </div>
                </td>
                <td class="text-center">
                    <button type="button" class=" -outline-danger -sm rounded-circle remove-field">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            tableBody.appendChild(flex flex-wrap gap-4);
            fieldIndex++;
            updateRemoveButtons();
        });

        tableBody.addEventListener('change', function(e) {
            if (e.target.classList.contains('field-type-select')) {
                const tr = e.target.closest('tr');
                const optionsInput = tr.querySelector('.options-input');
                const linkPageSelect = tr.querySelector('.link-page-select');
                const tableColsInput = tr.querySelector('.table-cols-input');
                
                // Hide all first
                optionsInput.classList.add('d-none');
                linkPageSelect.classList.add('d-none');
                tableColsInput.classList.add('d-none');

                // Show based on type
                if (e.target.value === 'select') {
                    optionsInput.classList.remove('d-none');
                } else if (e.target.value === 'link') {
                    linkPageSelect.classList.remove('d-none');
                } else if (e.target.value === 'table') {
                    tableColsInput.classList.remove('d-none');
                }
            }
        });

        tableBody.addEventListener('click', function(e) {
            if (e.target.closest('.remove-field')) {
                const flex flex-wrap gap-4 = e.target.closest('tr');
                if (document.querySelectorAll('.field-flex flex-wrap gap-4').length > 1) {
                    flex flex-wrap gap-4.remove();
                    updateRemoveButtons();
                }
            }
        });

        function updateRemoveButtons() {
            const buttons = document.querySelectorAll('.remove-field');
            const rowCount = buttons.length;
            buttons.forEach( => {
                .disabled = (rowCount <= 1);
            });
        }
    });
</script>
@endpush
@endsection


