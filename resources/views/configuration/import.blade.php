@extends('layouts.app')

@section('title', 'استيراد بيانات - ' . $title)

@section('content')
<!-- Select2 CSS (Local) -->
<link href="{{ asset('vendor/select2/select2.min.css') }}" rel="stylesheet" />

<div class="space-y-6" dir="rtl">
    <!-- Header Banner -->
    <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm">
        <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-3">
            <a href="{{ route('dashboard') }}" class="hover:text-brand-600 transition-colors">الرئيسية</a>
            <i class="fas fa-chevron-left text-[10px]"></i>
            <a href="{{ route($routeName . '.index') }}" class="hover:text-brand-600 transition-colors">{{ $title }}</a>
            <i class="fas fa-chevron-left text-[10px]"></i>
            <span class="text-slate-800 dark:text-slate-200">استيراد بيانات</span>
        </nav>
        <h1 class="text-2xl font-black text-slate-900 dark:text-white flex items-center gap-3">
            <span class="w-10 h-10 rounded-2xl bg-brand-50 dark:bg-brand-900/30 text-brand-600 dark:text-brand-400 flex items-center justify-center text-lg shadow-sm">
                <i class="fas fa-file-import"></i>
            </span>
            معالج استيراد البيانات الضخمة ({{ $title }})
        </h1>
        <p class="text-slate-500 dark:text-slate-400 mt-2 text-sm">
            تم تصميم هذا المعالج للتعامل مع ملفات تصل إلى ملايين السجلات بكفاءة عالية عبر المعالجة في الخلفية.
        </p>
    </div>

    <!-- Stepper Card -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="bg-slate-50/80 dark:bg-slate-800/50 p-6 border-b border-slate-100 dark:border-slate-800">
            <!-- Stepper -->
            <div class="flex items-center justify-between max-w-3xl mx-auto">
                <div class="step-item active flex flex-col items-center flex-1" id="step1-indicator">
                    <span class="step-num w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm bg-brand-600 text-white shadow-md shadow-brand-500/20 mb-2">1</span>
                    <span class="step-label text-xs font-bold text-slate-700 dark:text-slate-300">رفع الملف</span>
                </div>
                <div class="step-line flex-1 h-0.5 bg-slate-200 dark:bg-slate-700 mx-2 -mt-5"></div>
                <div class="step-item flex flex-col items-center flex-1 opacity-40" id="step2-indicator">
                    <span class="step-num w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-400 mb-2">2</span>
                    <span class="step-label text-xs font-bold text-slate-600 dark:text-slate-400">تخطيط الحقول</span>
                </div>
                <div class="step-line flex-1 h-0.5 bg-slate-200 dark:bg-slate-700 mx-2 -mt-5"></div>
                <div class="step-item flex flex-col items-center flex-1 opacity-40" id="step3-indicator">
                    <span class="step-num w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-400 mb-2">3</span>
                    <span class="step-label text-xs font-bold text-slate-600 dark:text-slate-400">مراجعة المعاينة</span>
                </div>
                <div class="step-line flex-1 h-0.5 bg-slate-200 dark:bg-slate-700 mx-2 -mt-5"></div>
                <div class="step-item flex flex-col items-center flex-1 opacity-40" id="step4-indicator">
                    <span class="step-num w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-400 mb-2">4</span>
                    <span class="step-label text-xs font-bold text-slate-600 dark:text-slate-400">التنفيذ والمتابعة</span>
                </div>
            </div>
        </div>
        
        <div class="p-6 md:p-8">
            <!-- Step 1: Upload -->
            <div id="uploadSection" class="import-step">
                <div class="text-center py-10 max-w-xl mx-auto">
                    <div class="w-20 h-20 bg-brand-50 dark:bg-brand-900/30 text-brand-600 dark:text-brand-400 rounded-3xl flex items-center justify-center mx-auto mb-6 text-3xl shadow-md shadow-brand-500/10">
                        <i class="fas fa-cloud-upload-btnalt"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">اختر ملف البيانات (Excel / CSV)</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mb-6">يدعم النظام ملفات XLSX, XLS, CSV. تأكد من أن الصف الأول يحتوي على رؤوس الأعمدة.</p>
                    
                    <div class="space-y-4">
                        <input class="w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-3 file:px-6 file:rounded-2xl file:border-0 file:text-sm file:font-bold file:bg-brand-50 file:text-brand-700 dark:file:bg-brand-900/30 dark:file:text-brand-300 hover:file:bg-brand-100 p-2 border border-slate-200 dark:border-slate-800 rounded-2xl bg-slate-50 dark:bg-slate-950" type="file" id="importFile" accept=".xlsx, .xls, .csv">
                        
                        <div class="flex items-center justify-center gap-3 pt-2">
                            <button class="px-8 py-3.5 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-2xl shadow-lg shadow-brand-500/25 transition-all flex items-center gap-2 text-sm" id="btnNextToMapping">
                                <span>فحص الملف</span>
                                <i class="fas fa-search text-xs"></i>
                            </button>
                            <a href="{{ route($routeName . '.template') }}" class="px-6 py-3.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-2xl transition-all flex items-center gap-2 text-sm">
                                <i class="fas fa-download text-xs"></i>
                                <span>تحميل النموذج القياسي</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Mapping -->
            <div id="mappingSection" class="import-step" class="hidden">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    <div class="lg:col-span-5 bg-slate-50/80 dark:bg-slate-800/40 p-6 rounded-3xl border border-slate-100 dark:border-slate-800 space-y-6">
                        <div>
                            <h4 class="text-base font-bold text-slate-900 dark:text-white mb-3">خيارات المعالجة</h4>
                            <label class="block mb-3 text-xs font-bold text-slate-500 dark:text-slate-400">كيف تريد التعامل مع السجلات الموجودة مسبقاً؟</label>
                            <div class="space-y-3">
                                <label class="flex items-start gap-3 p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl cursor-pointer hover:border-brand-500 transition-all">
                                    <input class="w-4 h-4 text-brand-600 focus:ring-brand-500 border-gray-300 rounded mt-1" type="radio" name="import_mode" id="modeAppend" value="append" checked>
                                    <div>
                                        <div class="font-bold text-sm text-slate-800 dark:text-slate-200">إضافة جديد فقط</div>
                                        <div class="text-xs text-slate-400">تخطي أي سجل موجود مسبقاً في قاعدة البيانات.</div>
                                    </div>
                                </label>
                                <label class="flex items-start gap-3 p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl cursor-pointer hover:border-brand-500 transition-all">
                                    <input class="w-4 h-4 text-brand-600 focus:ring-brand-500 border-gray-300 rounded mt-1" type="radio" name="import_mode" id="modeUpdate" value="update">
                                    <div>
                                        <div class="font-bold text-sm text-indigo-600 dark:text-indigo-400">تحديث الموجود</div>
                                        <div class="text-xs text-slate-400">تعديل بيانات السجلات الموجودة فقط، ولن يتم إضافة أي جديد.</div>
                                    </div>
                                </label>
                                <label class="flex items-start gap-3 p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl cursor-pointer hover:border-brand-500 transition-all">
                                    <input class="w-4 h-4 text-brand-600 focus:ring-brand-500 border-gray-300 rounded mt-1" type="radio" name="import_mode" id="modeUpsert" value="upsert">
                                    <div>
                                        <div class="font-bold text-sm text-emerald-600 dark:text-emerald-400">دمج (إضافة وتحديث)</div>
                                        <div class="text-xs text-slate-400">أذكى خيار: تحديث الموجود وإضافة ما هو جديد.</div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-base font-bold text-slate-900 dark:text-white mb-3">مطابقة الأعمدة</h4>
                            <div id="mappingContainer" class="space-y-4 max-h-96 overflow-y-auto pr-1">
                                <!-- Dynamic mapping selects -->
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-7 flex flex-col justify-between space-y-4">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="text-base font-bold text-slate-900 dark:text-white">معاينة رؤوس الملف</h4>
                                <span class="px-3 py-1 bg-brand-50 dark:bg-brand-900/30 text-brand-700 dark:text-brand-300 font-mono text-xs font-bold rounded-full" id="totalColsBadge"></span>
                            </div>
                            <div class="border border-slate-200 dark:border-slate-800 rounded-3xl overflow-hidden bg-white dark:bg-slate-900 shadow-sm">
                                <div class="overflow-x-auto max-h-96">
                                    <table class="w-full text-right border-collapse text-xs" id="previewTable">
                                        <thead class="bg-slate-50/80 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800 text-slate-400 font-bold uppercase sticky top-0">
                                            <tr id="previewHeaders"></tr>
                                        </thead>
                                        <tbody id="previewBody" class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                            <button class="px-6 py-3 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-2xl transition-all text-sm" onclick="showStep(1)">
                                السابق
                            </button>
                            <button class="px-8 py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-2xl shadow-lg shadow-brand-500/20 transition-all flex items-center gap-2 text-sm" id="btnNextToReview">
                                <span>المتابعة للمعاينة</span>
                                <i class="fas fa-arrow-left text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3: Review -->
            <div id="reviewSection" class="import-step" class="hidden">
                <div class="bg-amber-50 dark:bg-amber-950/30 border border-amber-200/80 dark:border-amber-800/50 p-4 rounded-2xl flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center flex-shrink-0 text-lg">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div>
                        <h5 class="font-bold text-amber-900 dark:text-amber-200 text-sm">ملاحظة هامة للمعاينة</h5>
                        <p class="text-amber-700 dark:text-amber-400 text-xs mt-0.5">يتم عرض عينة من أول 100 سجل فقط لضمان سرعة الاستجابة. عند التأكيد، سيقوم النظام بمعالجة كافة البيانات في الخلفية.</p>
                    </div>
                </div>

                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-bold text-slate-900 dark:text-white">تحليل عينة البيانات</h4>
                    <div id="readyCountBadge"></div>
                </div>

                <div class="border border-slate-200 dark:border-slate-800 rounded-3xl overflow-hidden bg-white dark:bg-slate-900 shadow-sm mb-6">
                    <div class="overflow-x-auto max-h-96">
                        <table class="w-full text-right border-collapse text-xs" id="reviewTable">
                            <thead class="bg-slate-50/80 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800 text-slate-400 font-bold uppercase sticky top-0">
                                <tr>
                                    <th class="py-3 px-4 text-center" width="80">الحالة</th>
                                    <th class="py-3 px-4" width="200">النتيجة المتوقعة</th>
                                    <th class="py-3 px-4" id="reviewDataHeaders">البيانات المقروءة من الملف</th>
                                </tr>
                            </thead>
                            <tbody id="reviewBody" class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300"></tbody>
                        </table>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-slate-100 dark:border-slate-800">
                    <button class="px-6 py-3 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-2xl transition-all text-sm" onclick="showStep(2)">
                        تعديل التخطيط
                    </button>
                    <button class="px-8 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl shadow-lg shadow-emerald-500/25 transition-all flex items-center gap-2 text-sm" id="btnConfirmImport">
                        <i class="fas fa-rocket"></i>
                        <span>بدء استيراد ملايين السجلات</span>
                    </button>
                </div>
            </div>

            <!-- Step 4: Progress -->
            <div id="progressSection" class="import-step" class="hidden">
                <div class="text-center py-10 max-w-2xl mx-auto">
                    <div class="progress-container mb-8">
                        <div class="relative inline-block">
                            <svg class="progress-ring" width="200" height="200">
                                <circle class="stroke-slate-100 dark:stroke-slate-800" stroke-width="12" fill="transparent" r="88" cx="100" cy="100"/>
                                <circle class="stroke-brand-600 transition-all duration-500" id="progressRing" stroke-width="12" stroke-dasharray="552.9" stroke-dashoffset="552.9" fill="transparent" r="88" cx="100" cy="100"/>
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-3xl font-black text-slate-900 dark:text-white" id="progressPercent">0%</span>
                                <span class="text-xs text-slate-400 mt-1 font-semibold" id="rpmValue">0 سجل/دقيقة</span>
                            </div>
                        </div>
                    </div>
                    
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2" id="progressStatusText">جاري المعالجة في الخلفية...</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mb-8">يمكنك مغادرة هذه الصفحة، سيستمر النظام بالعمل حتى اكتمال الاستيراد.</p>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
                        <div class="p-4 bg-slate-50 dark:bg-slate-800/40 rounded-2xl border border-slate-100 dark:border-slate-800">
                            <span class="text-xs text-slate-400 block mb-1 font-semibold">تمت معالجته</span>
                            <span class="text-2xl font-black text-slate-900 dark:text-white" id="processedCount">0</span>
                        </div>
                        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/30 rounded-2xl border border-emerald-100 dark:border-emerald-900/30">
                            <span class="text-xs text-emerald-600 dark:text-emerald-400 block mb-1 font-semibold">نجاح (إضافة)</span>
                            <span class="text-2xl font-black text-emerald-600 dark:text-emerald-400" id="createdCount">0</span>
                        </div>
                        <div class="p-4 bg-indigo-50 dark:bg-indigo-950/30 rounded-2xl border border-indigo-100 dark:border-indigo-900/30">
                            <span class="text-xs text-indigo-600 dark:text-indigo-400 block mb-1 font-semibold">نجاح (تحديث)</span>
                            <span class="text-2xl font-black text-indigo-600 dark:text-indigo-400" id="updatedCount">0</span>
                        </div>
                        <div class="p-4 bg-rose-50 dark:bg-rose-950/30 rounded-2xl border border-rose-100 dark:border-rose-900/30">
                            <span class="text-xs text-rose-600 dark:text-rose-400 block mb-1 font-semibold">فشل</span>
                            <span class="text-2xl font-black text-rose-600 dark:text-rose-400" id="failedCount">0</span>
                        </div>
                    </div>

                    <a href="{{ route($routeName . '.index') }}" class="px-8 py-3.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-2xl transition-all inline-flex items-center gap-2 text-sm">
                        <i class="fas fa-home"></i>
                        <span>العودة لجدول البيانات</span>
                    </a>
                </div>
            </div>

            <!-- Loading Spinner -->
            <div id="globalLoading" class="text-center py-12" class="hidden">
                <div class="w-12 h-12 border-4 border-brand-200 border-t-brand-600 rounded-full animate-spin mx-auto mb-4"></div>
                <h4 class="font-bold text-slate-800 dark:text-slate-200 text-base" id="globalLoadingText">جاري المعالجة...</h4>
            </div>
            
            <div id="globalError" class="bg-rose-50 border border-rose-200 text-rose-700 p-4 rounded-2xl text-sm font-semibold mt-4" class="hidden"></div>
            <div id="globalSuccess" class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 rounded-2xl text-sm font-semibold mt-4" class="hidden"></div>
        </div>
    </div>
</div>

<style>
.progress-ring { transform: rotate(-90deg); }
.step-item.completed span.step-num { background-color: #10b981 !important; color: white !important; }
.step-item.active { opacity: 1 !important; }
</style>r; transform: rotate(0deg); }
.progress-ring { transform: rotate(-90deg); }
.progress-ring__circle { transition: stroke-dashoffset 0.5s; stroke-linecap: round; }

.sticky-top { top: -1px; background: #f8f9fa; z-index: 100; border-bottom: 2px solid #dee2e6; }
.cursor-pointer { cursor: pointer; }

/* Select2 RTL and Bootstrap 5 adjustments */
.select2-container--default .select2-selection--single {
    height: 38px;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
}
.select2-container[dir="rtl"] .select2-selection--single .select2-selection__rendered {
    padding-right: 12px;
    padding-left: 20px;
    line-height: 36px;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px;
}
.select2-dropdown {
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}
</style>

@push('scripts')
<!-- Select2 JS (Local) -->
<script src="{{ asset('vendor/select2/select2.min.js') }}"></script>
<script>
$(document).ready(function() {
    let type = '{{ $type }}';
    let targetFields = @json($fields);
    let originalHeaders = [];
    let sessionId = null;

    function showStep(step) {
        $('.import-step').hide();
        $('#globalLoading').hide();
        
        $('[id$="-indicator"]').removeClass('active completed');
        for(let i=1; i<step; i++) $(`#step${i}-indicator`).addClass('completed');
        $(`#step${step}-indicator`).addClass('active');

        if(step === 1) $('#uploadSection').show();
        else if(step === 2) $('#mappingSection').show();
        else if(step === 3) $('#reviewSection').show();
        else if(step === 4) $('#progressSection').show();
    }

    // --- Step 1: Parse ---
    $('#btnNextToMapping').on('click', function() {
        let fileInput = $('#importFile')[0];
        if (fileInput.files.length === 0) return alert('يرجى اختيار ملف');

        let formData = new FormData();
        formData.append('excel_file', fileInput.files[0]);
        formData.append('_token', '{{ csrf_token() }}');

        $('#uploadSection').hide();
        $('#globalLoading').show();
        $('#globalLoadingText').text('جاري تحليل هيكل الملف للملايين من السجلات...');

        $.ajax({
            url: '{{ route($routeName . ".import-parse") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    originalHeaders = response.columns;
                    $('#totalColsBadge').text(originalHeaders.length + ' عمود');
                    
                    // Headers Preview
                    let headHtml = '';
                    response.columns.forEach(col => headHtml += '<th>' + (col || 'بدون عنوان') + '</th>');
                    $('#previewHeaders').html(headHtml);

                    // Row Preview
                    let bodyHtml = '';
                    response.preview_data.forEach(flex flex-wrap gap-4 => {
                        bodyHtml += '<tr>';
                        flex flex-wrap gap-4.forEach(cell => bodyHtml += '<td>' + (cell || '') + '</td>');
                        bodyHtml += '</tr>';
                    });
                    $('#previewBody').html(bodyHtml);

                    // Mapping UI
                    let mappingHtml = '';
                    Object.entries(targetFields).forEach(([field, label]) => {
                        mappingHtml += `
                            <div class="mb-3">
                                <label class="form-label small fw-bold">${label}</label>
                                <select class="w-full pr-2 pl-2 py-2 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-brand-500/20 transition-all column-map-select" data-field="${field}">
                                    <option value="">تجاهل هذا الحقل</option>
                                    ${response.columns.map((col, idx) => {
                                        let cleanCol = String(col || '').trim().replace(/\s+/g, ' ');
                                        let cleanLabel = String(label || '').trim().replace(/\s+/g, ' ');
                                        let selected = (cleanCol === cleanLabel) ? 'selected' : '';
                                        return `<option value="${idx}" ${selected}>${col || ('عمود ' + (idx + 1))}</option>`;
                                    }).join('')}
                                </select>
                            </div>`;
                    });
                    $('#mappingContainer').html(mappingHtml);
                    
                    // Initialize Select2
                    $('.column-map-select').select2({
                        dir: "rtl",
                        theme: "bootstrap-5",
                        tags: true,
                        width: '100%'
                    });
                    
                    showStep(2);
                } else {
                    alert(response.message);
                    showStep(1);
                }
            }
        });
    });

    // --- Step 2: Validate Preview ---
    $('#btnNextToReview').on('click', function() {
        let mapping = {};
        $('.column-map-select').each(function() { mapping[$(this).data('field')] = $(this).val(); });

        $('#mappingSection').hide();
        $('#globalLoading').show();
        $('#globalLoadingText').text('جاري فحص عينة من البيانات للتأكد من المخطط...');

        $.ajax({
            url: '{{ route($routeName . ".validate-import") }}',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}', mapping: mapping, mode: $('input[name="import_mode"]:checked').val() },
            success: function(response) {
                if (response.success) {
                    $('#readyCountBadge').html(`
                        <span class="badge bg-success rounded-pill px-3">جاهز للتنفيذ</span>
                        <span class="badge bg-info rounded-pill px-3">سيتم التحديث</span>
                    `);

                    let reviewHtml = '';
                    response.results.forEach(flex flex-wrap gap-4 => {
                        let status = flex flex-wrap gap-4.status === 'ready' ? '<i class="fas fa-check-circle text-success"></i>' : 
                                     (flex flex-wrap gap-4.status === 'update' ? '<i class="fas fa-sync text-info"></i>' : '<i class="fas fa-times-circle text-danger"></i>');
                        reviewHtml += `<tr>
                            <td class="text-center">${status}</td>
                            <td>${flex flex-wrap gap-4.errors.length ? flex flex-wrap gap-4.errors.join(', ') : 'سجل سليم'}</td>
                            <td><div class="text-truncate text-muted" style="max-width: 600px;">${flex flex-wrap gap-4.data.join(' | ')}</div></td>
                        </tr>`;
                    });
                    $('#reviewBody').html(reviewHtml);
                    showStep(3);
                }
            }
        });
    });

    // --- Step 3: Trigger Background Job ---
    $('#btnConfirmImport').on('click', function() {
        $('#reviewSection').hide();
        $('#globalLoading').show();
        $('#globalLoadingText').text('جاري المعالجة... قد يستغرق ذلك بضع دقائق للملفات الكبيرة');

        $.ajax({
            url: '{{ route($routeName . ".import-store") }}',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                if (response.success) {
                    if (response.is_async) {
                        sessionId = response.session_id;
                        startProgressPolling();
                        showStep(4);
                    } else {
                        // Synchronous handle
                        setProgress(100);
                        $('#processedCount').text(response.results.processed);
                        $('#createdCount').text(response.results.created);
                        $('#updatedCount').text(response.results.updated);
                        $('#failedCount').text(response.results.failed);
                        
                        if (response.results.errors && response.results.errors.length > 0) {
                            $('#progressStatusText').html('اكتمل مع وجود أخطاء:<br><small class="text-danger">' + response.results.errors.slice(0, 5).join('<br>') + (response.results.errors.length > 5 ? '<br>...' : '') + '</small>');
                        } else {
                            $('#progressStatusText').text('اكتمل بنجاح!').addClass('text-success');
                        }
                        
                        $('#rpmValue').text('-');
                        showStep(4);
                    }
                } else {
                    alert(response.message);
                    showStep(3);
                }
            }
        });
    });

    function setProgress(percent) {
        const circle = document.getElementById('progressRing');
        const radius = circle.r.baseVal.value;
        const circumference = radius * 2 * Math.PI;
        const offset = circumference - (percent / 100 * circumference);
        circle.style.strokeDashoffset = offset;
        $('#progressPercent').text(percent + '%');
    }

    let startTime = null;

    function startProgressPolling() {
        startTime = Date.now();
        let poll = setInterval(() => {
            $.get('{{ route('configuration.import.progress') }}', { session_id: sessionId }, function(res) {
                if (res.success) {
                    setProgress(res.progress_percentage);
                    $('#processedCount').text(res.processed_rows);
                    $('#createdCount').text(res.created_count);
                    $('#updatedCount').text(res.updated_count);
                    $('#failedCount').text(res.failed_count);

                    // Speed Calculation
                    if (res.processed_rows > 0 && startTime) {
                        let elapsedMinutes = (Date.now() - startTime) / 60000;
                        if (elapsedMinutes > 0.05) { 
                            let rpm = Math.round(res.processed_rows / elapsedMinutes);
                            let oldRpm = parseInt($('#rpmValue').text()) || 0;
                            $('#rpmValue').text(rpm);
                            
                            if (rpm > oldRpm) {
                                $('#rpmValue').addClass('pulse');
                                setTimeout(() => $('#rpmValue').removeClass('pulse'), 500);
                            }
                        }
                    }

                    if (res.status === 'completed') {
                        clearInterval(poll);
                        $('#progressStatusText').text('اكتمل الاستيراد بنجاح!').addClass('text-success');
                        $('#progressPercent').addClass('text-success');
                    } else if (res.status === 'failed') {
                        clearInterval(poll);
                        $('#progressStatusText').text('فشلت العملية.').addClass('text-danger');
                    }
                }
            });
        }, 2000);
    }
});
</script>
@endpush
@endsection


