@extends('layouts.app')

@section('title', 'استيراد بيانات شخصية')
@section('page_title', 'السجلات الأساسية')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto" dir="rtl">
    {{-- Breadcrumbs --}}
    <nav class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-2 print:hidden">
        <a href="{{ route('dashboard') }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">الرئيسية</a>
        <span>/</span>
        <a href="{{ route('system.personal-data.index') }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">إدارة البيانات الشخصية</a>
        <span>/</span>
        <span class="text-slate-900 dark:text-white font-semibold">استيراد بيانات</span>
    </nav>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-flex flex-wrap gap-4 justify-between items-start sm:items-center gap-4 mb-2">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center font-bold shadow-sm shrink-0">
                <i class="fas fa-file-import text-xl"></i>
            </div>
            <div>
                <h1 class="font-display font-bold text-2xl text-slate-900 dark:text-white m-0">معالج استيراد البيانات الشخصية</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">رفع كميات كبيرة من السجلات دفعة واحدة عبر ملفات Excel و CSV مع مطابقة ذكية للحقول.</p>
            </div>
        </div>
        <a href="{{ route('system.personal-data.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 font-bold text-sm rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors shadow-sm">
            <i class="fas fa-arrow-right"></i>
            <span>عودة للقائمة</span>
        </a>
    </div>

    {{-- Wizard bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden mb-4 --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors">
        
        {{-- Step Navigation Header --}}
        <div class="bg-slate-50/70 dark:bg-slate-800/40 border-b border-slate-200 dark:border-slate-800 py-4 px-6">
            <div class="flex items-center justify-between max-w-3xl mx-auto">
                <div class="step-item flex items-center gap-2.5" id="step1-indicator">
                    <span class="w-8 h-8 rounded-xl bg-brand-600 text-white font-bold text-xs flex items-center justify-center step-num">1</span>
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200 dark:text-slate-200 step-label">اختيار الملف</span>
                </div>
                <div class="step-line flex-1 h-0.5 bg-slate-200 dark:bg-slate-700 mx-3"></div>
                <div class="step-item flex items-center gap-2.5 opacity-40" id="step2-indicator">
                    <span class="w-8 h-8 rounded-xl bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold text-xs flex items-center justify-center step-num">2</span>
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200 dark:text-slate-200 step-label">تخطيط الحقول</span>
                </div>
                <div class="step-line flex-1 h-0.5 bg-slate-200 dark:bg-slate-700 mx-3"></div>
                <div class="step-item flex items-center gap-2.5 opacity-40" id="step3-indicator">
                    <span class="w-8 h-8 rounded-xl bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold text-xs flex items-center justify-center step-num">3</span>
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200 dark:text-slate-200 step-label">المعاينة</span>
                </div>
                <div class="step-line flex-1 h-0.5 bg-slate-200 dark:bg-slate-700 mx-3"></div>
                <div class="step-item flex items-center gap-2.5 opacity-40" id="step4-indicator">
                    <span class="w-8 h-8 rounded-xl bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold text-xs flex items-center justify-center step-num">4</span>
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200 dark:text-slate-200 step-label">المعالجة</span>
                </div>
            </div>
        </div>

        <div class="p-6 md:p-8">
            <!-- Step 1: Upload -->
            <div id="uploadSection" class="import-step">
                <div class="text-center py-8 max-w-xl mx-auto">
                    <div class="w-20 h-20 rounded-2xl bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center mx-auto mb-4 border border-brand-100 dark:border-brand-500/20">
                        <i class="fas fa-cloud-upload-btnalt text-3xl"></i>
                    </div>
                    <h3 class="font-display font-bold text-xl text-slate-900 dark:text-white mb-2">اختر ملف الإكسل (Excel / CSV)</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-6">تأكد من أن الملف يحتوي على البيانات المطلوبة بشكل صحيح. يمكنك تحميل النموذج الاسترشادي بالأسفل.</p>
                    
                    <div class="space-y-4">
                        <input class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-2xl file:border-0 file:text-xs file:font-bold file:bg-brand-50 dark:file:bg-brand-500/10 file:text-brand-600"
                            type="file" id="importFile" accept=".xlsx, .xls, .csv">
                        <button class="w-full py-3 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm shadow-sm shadow-brand-500/30 transition-all flex items-center justify-center gap-2" id="btnNextToMapping">
                            <span>بدء المعالجة والتخطيط</span>
                            <i class="fas fa-arrow-left"></i>
                        </button>
                    </div>
                    
                    <div class="mt-6 pt-6 border-t border-slate-100 dark:border-slate-800">
                        <a href="{{ route('system.personal-data.template') }}" class="inline-flex items-center gap-2 text-xs font-bold text-brand-600 dark:text-brand-400 hover:underline">
                            <i class="fas fa-download"></i>
                            <span>تحميل نموذج استيراد البيانات الشخصية الفارغ</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Step 2: Mapping -->
            <div id="mappingSection" class="import-step" class="hidden">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <div class="lg:col-span-5 bg-slate-50 dark:bg-slate-950 p-5 rounded-xl border border-slate-200 dark:border-slate-800 space-y-5">
                        <div>
                            <h4 class="font-bold text-sm text-slate-900 dark:text-white mb-3">طريقة الاستيراد</h4>
                            <div class="space-y-2">
                                <label class="flex items-start gap-3 p-3 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 cursor-pointer">
                                    <input type="radio" name="import_mode" id="modeAppend" value="append" checked class="mt-1 text-brand-600">
                                    <div class="text-xs">
                                        <strong class="block font-bold text-slate-800 dark:text-slate-200 dark:text-slate-200">تجاهل المكرر</strong>
                                        <span class="text-slate-400">عدم تحديث السجلات الموجودة مسبقاً (اعتماداً على رقم الهوية).</span>
                                    </div>
                                </label>
                                <label class="flex items-start gap-3 p-3 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 cursor-pointer">
                                    <input type="radio" name="import_mode" id="modeUpsert" value="upsert" class="mt-1 text-emerald-600">
                                    <div class="text-xs">
                                        <strong class="block font-bold text-emerald-600 dark:text-emerald-400">تحديث المكرر</strong>
                                        <span class="text-slate-400">تحديث بيانات السجل إذا كان رقم الهوية موجوداً مسبقاً.</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div>
                            <h4 class="font-bold text-sm text-slate-900 dark:text-white mb-3">مطابقة الأعمدة</h4>
                            <div id="mappingContainer" class="space-y-3 overflow-y-auto max-h-[380px] pr-1">
                                <!-- Dynamic mapping -->
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-7 flex flex-col justify-between">
                        <div>
                            <h4 class="font-bold text-sm text-slate-900 dark:text-white mb-3">معاينة الملف المرفوع</h4>
                            <div class="border border-slate-200 dark:border-slate-800 rounded-xl overflow-x-auto bg-white dark:bg-slate-900 shadow-sm max-h-[440px]">
                                <table class="w-full text-xs text-right" id="previewTable">
                                    <thead class="bg-slate-50 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300 font-bold sticky top-0">
                                        <tr id="previewHeaders"></tr>
                                    </thead>
                                    <tbody id="previewBody" class="divide-y divide-slate-100 dark:divide-slate-800"></tbody>
                                </table>
                            </div>
                        </div>

                        <div class="flex items-center justify-between gap-3 pt-4 border-t border-slate-100 dark:border-slate-800 mt-6">
                            <button type="button" class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-semibold text-xs hover:bg-slate-50 dark:hover:bg-slate-800" onclick="showStep(1)">السابق</button>
                            <button type="button" class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-sm" id="btnNextToReview">مراجعة والتحقق</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3: Review -->
            <div id="reviewSection" class="import-step" class="hidden">
                <div class="p-4 rounded-xl bg-brand-50/50 dark:bg-brand-500/5 border border-brand-100 dark:border-brand-500/20 text-xs text-brand-800 dark:text-brand-300 mb-4 flex items-center gap-2">
                    <i class="fas fa-info-circle text-brand-600"></i>
                    <span>سيقوم النظام الآن بعرض عينة من 100 سجل للتحقق من صحة البيانات قبل التنفيذ الفعلي في قاعدة البيانات.</span>
                </div>

                <div class="border border-slate-200 dark:border-slate-800 rounded-xl overflow-x-auto bg-white dark:bg-slate-900 shadow-sm max-h-[450px]">
                    <table class="w-full text-xs text-right" id="reviewTable">
                        <thead class="bg-slate-50 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300 font-bold sticky top-0">
                            <tr>
                                <th class="py-3 px-4 w-16 text-center">الحالة</th>
                                <th class="py-3 px-4 w-48">الأخطاء المحتملة</th>
                                <th class="py-3 px-4">بيانات الصف</th>
                            </tr>
                        </thead>
                        <tbody id="reviewBody" class="divide-y divide-slate-100 dark:divide-slate-800"></tbody>
                    </table>
                </div>

                <div class="flex items-center justify-between gap-3 pt-6 border-t border-slate-100 dark:border-slate-800 mt-6">
                    <button type="button" class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-semibold text-xs hover:bg-slate-50 dark:hover:bg-slate-800" onclick="showStep(2)">تعديل التخطيط</button>
                    <button type="button" class="px-7 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm shadow-emerald-500/30" id="btnConfirmImport">تأكيد وبدء الاستيراد النهائي</button>
                </div>
            </div>

            <!-- Step 4: Progress -->
            <div id="progressSection" class="import-step" class="hidden">
                <div class="text-center py-8">
                    <div class="inline-flex items-center justify-center p-6 rounded-full bg-brand-50 dark:bg-brand-500/10 mb-4">
                        <div class="text-center">
                            <span class="block text-3xl font-display font-bold text-brand-600 dark:text-brand-400 font-mono" id="progressPercent">0%</span>
                            <span class="text-[10px] text-slate-400 font-bold">نسبة الإنجاز</span>
                        </div>
                    </div>
                    <p class="text-sm font-semibold text-slate-600 dark:text-slate-300 mt-2" id="progressStatusText">جاري المعالجة في الخلفية...</p>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-xl mx-auto my-6">
                        <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                            <span class="block text-[11px] text-slate-400 font-bold mb-1">تمت معالجته</span>
                            <strong class="text-lg font-bold text-slate-900 dark:text-white font-mono" id="processedCount">0</strong>
                        </div>
                        <div class="p-3.5 rounded-xl bg-emerald-50/50 dark:bg-emerald-500/5 border border-emerald-200 dark:border-emerald-500/20">
                            <span class="block text-[11px] text-emerald-600 dark:text-emerald-400 font-bold mb-1">جديد</span>
                            <strong class="text-lg font-bold text-emerald-700 dark:text-emerald-300 font-mono" id="createdCount">0</strong>
                        </div>
                        <div class="p-3.5 rounded-xl bg-sky-50/50 dark:bg-sky-500/5 border border-sky-200 dark:border-sky-500/20">
                            <span class="block text-[11px] text-sky-600 dark:text-sky-400 font-bold mb-1">محدث</span>
                            <strong class="text-lg font-bold text-sky-700 dark:text-sky-300 font-mono" id="updatedCount">0</strong>
                        </div>
                        <div class="p-3.5 rounded-xl bg-red-50/50 dark:bg-red-500/5 border border-red-200 dark:border-red-500/20">
                            <span class="block text-[11px] text-red-600 dark:text-red-400 font-bold mb-1">فشل</span>
                            <strong class="text-lg font-bold text-red-700 dark:text-red-300 font-mono" id="failedCount">0</strong>
                        </div>
                    </div>

                    <a href="{{ route('system.personal-data.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm shadow-sm">
                        <span>الذهاب لجدول البيانات الشخصية</span>
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
            </div>

            <!-- Global Loading -->
            <div id="globalLoading" class="text-center py-12" class="hidden">
                <div class="w-10 h-10 border-4 border-brand-600 border-t-transparent rounded-full animate-spin mx-auto mb-3"></div>
                <h4 class="font-bold text-sm text-slate-800 dark:text-slate-200 dark:text-slate-200" id="globalLoadingText">جاري التحميل والمعالجة...</h4>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="{{ asset('vendor/select2/select2.min.css') }}" rel="stylesheet" />
<style>
    .step-item.active .step-num { background-color: #4f46e5; color: white; }
    .step-item.active { opacity: 1 !important; }
</style>
@endpush

@push('scripts')
<script src="{{ asset('vendor/select2/select2.min.js') }}"></script>
<script>
$(document).ready(function() {
    let targetFields = @json($fields);
    let sessionId = null;

    window.showStep = function(step) {
        $('.import-step').hide();
        $('#globalLoading').hide();
        $('.step-item').css('opacity', '0.4').removeClass('active');
        for(let i=1; i<=step; i++) {
            $(`#step${i}-indicator`).css('opacity', i == step ? '1' : '0.7').addClass(i == step ? 'active' : '');
        }

        if(step === 1) $('#uploadSection').show();
        if(step === 2) $('#mappingSection').show();
        if(step === 3) $('#reviewSection').show();
        if(step === 4) $('#progressSection').show();
    }

    $('#btnNextToMapping').click(function() {
        let file = $('#importFile')[0].files[0];
        if(!file) return alert('اختر ملفاً أولاً');

        let fd = new FormData();
        fd.append('excel_file', file);
        fd.append('_token', '{{ csrf_token() }}');

        $('#uploadSection').hide();
        $('#globalLoading').show();
        $('#globalLoadingText').text('جاري فحص هيكل الملف...');

        $.ajax({
            url: '{{ route("system.personal-data.import-parse") }}',
            type: 'POST', data: fd, processData: false, contentType: false,
            success: function(res) {
                if(res.success) {
                    let hHtml = '';
                    res.columns.forEach(c => hHtml += '<th class="py-2.5 px-3">'+(c||'-')+'</th>');
                    $('#previewHeaders').html(hHtml);

                    let bHtml = '';
                    res.preview_data.forEach(flex flex-wrap gap-4 => {
                        bHtml += '<tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/25">';
                        flex flex-wrap gap-4.forEach(cell => bHtml += '<td class="py-2 px-3 text-slate-700 dark:text-slate-300">'+(cell||'')+'</td>');
                        bHtml += '</tr>';
                    });
                    $('#previewBody').html(bHtml);

                    let mHtml = '';
                    Object.entries(targetFields).forEach(([key, label]) => {
                        mHtml += `<div class="p-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">${label}</label>
                            <select class="w-full mapping-select bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl px-3 py-1.5 text-xs text-slate-900 dark:text-white" data-field="${key}">
                                <option value="">تجاهل</option>
                                ${res.columns.map((c, i) => {
                                    let selected = (String(c).trim() === String(label).trim()) ? 'selected' : '';
                                    return `<option value="${i}" ${selected}>${c || ('عمود ' + (i+1))}</option>`;
                                }).join('')}
                            </select>
                        </div>`;
                    });
                    $('#mappingContainer').html(mHtml);
                    
                    showStep(2);
                } else alert(res.message);
            }
        });
    });

    $('#btnNextToReview').click(function() {
        let mapping = {};
        $('.mapping-select').each(function() { mapping[$(this).data('field')] = $(this).val(); });

        $('#mappingSection').hide();
        $('#globalLoading').show();

        $.ajax({
            url: '{{ route("system.personal-data.validate-import") }}',
            type: 'POST', data: { _token: '{{ csrf_token() }}', mapping: mapping, mode: $('input[name="import_mode"]:checked').val() },
            success: function(res) {
                if(res.success) {
                    let rHtml = '';
                    res.results.forEach(flex flex-wrap gap-4 => {
                        let status = flex flex-wrap gap-4.status === 'error' ? '<i class="fas fa-times-circle text-red-500"></i>' : '<i class="fas fa-check-circle text-emerald-500"></i>';
                        rHtml += `<tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/25">
                            <td class="text-center py-2.5 px-3">${status}</td>
                            <td class="text-red-500 font-semibold py-2.5 px-3 text-xs">${flex flex-wrap gap-4.errors.join('<br>')}</td>
                            <td class="py-2.5 px-3"><div class="truncate text-slate-600 dark:text-slate-400 text-xs" style="max-width: 500px;">${flex flex-wrap gap-4.data.join(' | ')}</div></td>
                        </tr>`;
                    });
                    $('#reviewBody').html(rHtml);
                    showStep(3);
                }
            }
        });
    });

    $('#btnConfirmImport').click(function() {
        $('#reviewSection').hide();
        $('#globalLoading').show();
        $('#globalLoadingText').text('جاري المعالجة... قد يستغرق ذلك بضع دقائق للملفات الكبيرة');

        $.post('{{ route("system.personal-data.import-store") }}', { _token: '{{ csrf_token() }}' }, function(res) {
            if(res.success) {
                if (res.is_async) {
                    sessionId = res.session_id;
                    showStep(4);
                    startPolling();
                } else {
                    $('#progressPercent').text('100%');
                    $('#processedCount').text(res.results.processed);
                    $('#createdCount').text(res.results.created);
                    $('#updatedCount').text(res.results.updated);
                    $('#failedCount').text(res.results.failed);
                    
                    if (res.results.errors && res.results.errors.length > 0) {
                        $('#progressStatusText').html('اكتمل مع وجود أخطاء:<br><small class="text-red-500">' + res.results.errors.slice(0, 5).join('<br>') + (res.results.errors.length > 5 ? '<br>...' : '') + '</small>');
                    } else {
                        $('#progressStatusText').text('اكتمل بنجاح!').addClass('text-emerald-600 dark:text-emerald-400');
                    }
                    
                    showStep(4);
                }
            } else {
                alert(res.message);
                showStep(3);
            }
        });
    });

    let startTime = null;

    function startPolling() {
        startTime = Date.now();
        let interval = setInterval(() => {
            $.get('{{ route("configuration.import.progress") }}', { session_id: sessionId }, function(res) {
                if(res.success) {
                    $('#progressPercent').text(res.progress_percentage + '%');
                    $('#processedCount').text(res.processed_rows);
                    $('#createdCount').text(res.created_count);
                    $('#updatedCount').text(res.updated_count);
                    $('#failedCount').text(res.failed_count);

                    if(res.status === 'completed') {
                        clearInterval(interval);
                        $('#progressStatusText').text('اكتمل بنجاح!').addClass('text-emerald-600 dark:text-emerald-400');
                    }
                }
            });
        }, 2000);
    }
});
</script>
@endpush

