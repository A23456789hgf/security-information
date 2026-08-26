@extends('layouts.app')

@section('title', 'استيراد بيانات - ' . $title)

@section('content')
<!-- Select2 CSS (Local) -->
<link href="{{ asset('vendor/select2/select2.min.css') }}" rel="stylesheet" />

<div class="container-fluid mt-4" dir="rtl">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route($routeName . '.index') }}">{{ $title }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">استيراد بيانات</li>
                </ol>
            </nav>
            <div class="bg-white p-3 rounded shadow-sm border-end border-primary border-5">
                <h2 class="mb-0 text-primary"><i class="fas fa-file-import me-2"></i>معالج استيراد البيانات الضخمة</h2>
                <p class="text-muted mb-0">تم تصميم هذا المعالج للتعامل مع ملفات تصل إلى ملايين السجلات بكفاءة عالية عبر المعالجة في الخلفية.</p>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-light py-3 border-bottom">
            <!-- Stepper -->
            <div class="d-flex justify-content-center">
                <div class="step-wizard d-flex align-items-center w-100" style="max-width: 800px;">
                    <div class="step-item active" id="step1-indicator">
                        <span class="step-num">1</span>
                        <span class="step-label">رفع الملف</span>
                    </div>
                    <div class="step-line"></div>
                    <div class="step-item" id="step2-indicator">
                        <span class="step-num">2</span>
                        <span class="step-label">تخطيط الحقول</span>
                    </div>
                    <div class="step-line"></div>
                    <div class="step-item" id="step3-indicator">
                        <span class="step-num">3</span>
                        <span class="step-label">مراجعة المعاينة</span>
                    </div>
                    <div class="step-line"></div>
                    <div class="step-item" id="step4-indicator">
                        <span class="step-num">4</span>
                        <span class="step-label">التنفيذ والمتابعة</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-body p-4 min-vh-50">
            <!-- Step 1: Upload -->
            <div id="uploadSection" class="import-step">
                <div class="text-center py-5">
                    <div class="upload-icon-box mb-4 mx-auto">
                        <i class="fas fa-cloud-upload-alt fa-3x text-primary"></i>
                    </div>
                    <h4 class="fw-bold">اختر ملف البيانات (Excel/CSV)</h4>
                    <p class="text-muted mb-4">يدعم النظام ملفات XSLX, XLS, CSV. تأكد من أن الصف الأول يحتوي على رؤوس الأعمدة.</p>
                    
                    <div class="mx-auto" style="max-width: 500px;">
                        <div class="input-group input-group-lg shadow-sm">
                            <input class="form-control" type="file" id="importFile" accept=".xlsx, .xls, .csv">
                            <button class="btn btn-primary px-4" id="btnNextToMapping">
                                فحص الملف <i class="fas fa-search ms-1"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route($routeName . '.template') }}" class="btn btn-outline-secondary rounded-pill">
                            <i class="fas fa-download me-1"></i>تحميل نموذج الملف القياسي
                        </a>
                    </div>
                </div>
            </div>

            <!-- Step 2: Mapping -->
            <div id="mappingSection" class="import-step" style="display: none;">
                <div class="row g-4">
                    <div class="col-lg-5">
                        <div class="p-4 bg-light rounded-4 border h-100">
                            <h5 class="fw-bold mb-3 border-bottom pb-2">خيارات المعالجة</h5>
                            <div class="mb-4">
                                <label class="d-block mb-3 fw-bold text-dark">كيف تريد التعامل مع السجلات الموجودة مسبقاً؟</label>
                                <div class="form-check card-radio mb-2">
                                    <input class="form-check-input" type="radio" name="import_mode" id="modeAppend" value="append" checked>
                                    <label class="form-check-label p-3 border rounded-3 w-100 cursor-pointer" for="modeAppend">
                                        <div class="fw-bold">إضافة جديد فقط</div>
                                        <small class="text-muted">تخطي أي سجل موجود مسبقاً في قاعدة البيانات.</small>
                                    </label>
                                </div>
                                <div class="form-check card-radio mb-2">
                                    <input class="form-check-input" type="radio" name="import_mode" id="modeUpdate" value="update">
                                    <label class="form-check-label p-3 border rounded-3 w-100 cursor-pointer" for="modeUpdate">
                                        <div class="fw-bold text-info">تحديث الموجود</div>
                                        <small class="text-muted">تعديل بيانات السجلات الموجودة فقط، ولن يتم إضافة أي جديد.</small>
                                    </label>
                                </div>
                                <div class="form-check card-radio">
                                    <input class="form-check-input" type="radio" name="import_mode" id="modeUpsert" value="upsert">
                                    <label class="form-check-label p-3 border rounded-3 w-100 cursor-pointer" for="modeUpsert">
                                        <div class="fw-bold text-success">دمج (إضافة وتحديث)</div>
                                        <small class="text-muted">أذكى خيار: تحديث الموجود وإضافة ما هو جديد.</small>
                                    </label>
                                </div>
                            </div>

                            <h5 class="fw-bold mb-3 border-bottom pb-2">مطابقة الأعمدة</h5>
                            <div id="mappingContainer">
                                <!-- Dynamic mapping selects -->
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="h-100 d-flex flex-column">
                            <h5 class="fw-bold mb-3 d-flex justify-content-between align-items-center">
                                <span>معاينة رؤوس الملف</span>
                                <span class="badge bg-primary rounded-pill font-monospace" id="totalColsBadge"></span>
                            </h5>
                            <div class="table-responsive border rounded-4 flex-grow-1 bg-white shadow-sm">
                                <table class="table table-hover mb-0" id="previewTable" style="font-size: 0.9rem;">
                                    <thead class="bg-light sticky-top shadow-none">
                                        <tr id="previewHeaders"></tr>
                                    </thead>
                                    <tbody id="previewBody"></tbody>
                                </table>
                            </div>
                            <div class="mt-4 text-end">
                                <button class="btn btn-outline-secondary btn-lg rounded-pill px-5 me-2" onclick="showStep(1)">
                                    السابق
                                </button>
                                <button class="btn btn-primary btn-lg rounded-pill px-5" id="btnNextToReview">
                                    المتابعة للمعاينة <i class="fas fa-arrow-left ms-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3: Review -->
            <div id="reviewSection" class="import-step" style="display: none;">
                <div class="alert alert-warning d-flex align-items-center rounded-4 border-0 shadow-sm mb-4">
                    <i class="fas fa-exclamation-triangle fa-2x me-3 opacity-50"></i>
                    <div>
                        <h6 class="fw-bold mb-1">ملاحظة هامة للمعاينة</h6>
                        <p class="mb-0 small">يتم عرض عينة من أول 100 سجل فقط لضمان سرعة الاستجابة. عند التأكيد، سيقوم النظام بمعالجة كافة البيانات في الخلفية.</p>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">تحليل عينة البيانات</h5>
                    <div id="readyCountBadge"></div>
                </div>

                <div class="table-responsive border rounded-4 bg-white shadow-sm" style="max-height: 500px;">
                    <table class="table table-hover table-bordered mb-0" id="reviewTable" style="font-size: 0.85rem;">
                        <thead class="bg-light sticky-top">
                            <tr>
                                <th class="text-center" width="80">الحالة</th>
                                <th width="200">النتيجة المتوقعة</th>
                                <th id="reviewDataHeaders">البيانات المقروءة من الملف</th>
                            </tr>
                        </thead>
                        <tbody id="reviewBody"></tbody>
                    </table>
                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <button class="btn btn-outline-secondary btn-lg rounded-pill px-5" onclick="showStep(2)">
                        تعديل التخطيط
                    </button>
                    <button class="btn btn-success btn-lg rounded-pill px-5 shadow" id="btnConfirmImport">
                        <i class="fas fa-rocket me-2"></i> بدء استيراد ملايين السجلات
                    </button>
                </div>
            </div>

            <!-- Step 4: Progress -->
            <div id="progressSection" class="import-step" style="display: none;">
                <div class="text-center py-5">
                    <div class="progress-container mb-5">
                        <div class="progress-ring-wrapper position-relative">
                            <svg class="progress-ring" width="220" height="220">
                                <circle class="progress-ring__circle-bg" stroke="#f0f2f5" stroke-width="12" fill="transparent" r="100" cx="110" cy="110"/>
                                <circle class="progress-ring__circle" id="progressRing" stroke="var(--bs-primary)" stroke-width="12" stroke-dasharray="628.3" stroke-dashoffset="628.3" fill="transparent" r="100" cx="110" cy="110"/>
                            </svg>
                            <div class="gauge-center text-center">
                                <div class="speed-label text-muted small mb-0">سرعة الاستيراد</div>
                                <div class="speed-value fs-2 fw-bold text-primary mb-0" id="rpmValue">0</div>
                                <div class="speed-unit text-muted tiny">سجل/دقيقة</div>
                                <div class="mt-2">
                                    <span class="fs-4 fw-bold" id="progressPercent">0%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <h4 class="fw-bold" id="progressStatusText">جاري المعالجة في الخلفية...</h4>
                    <p class="text-muted mb-4">يمكنك مغادرة هذه الصفحة، سيستمر النظام بالعمل حتى اكتمال الاستيراد.</p>
                    
                    <div class="row justify-content-center g-3 mb-4">
                        <div class="col-md-3">
                            <div class="p-3 border rounded-4 bg-light">
                                <small class="text-muted d-block mb-1">تمت معالجته</small>
                                <span class="fs-4 fw-bold text-dark" id="processedCount">0</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 border rounded-4 bg-light">
                                <small class="text-muted d-block mb-1">نجاح (إضافة)</small>
                                <span class="fs-4 fw-bold text-success" id="createdCount">0</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 border rounded-4 bg-light">
                                <small class="text-muted d-block mb-1">نجاح (تحديث)</small>
                                <span class="fs-4 fw-bold text-info" id="updatedCount">0</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 border rounded-4 bg-light">
                                <small class="text-muted d-block mb-1">فشل</small>
                                <span class="fs-4 fw-bold text-danger" id="failedCount">0</span>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route($routeName . '.index') }}" class="btn btn-outline-primary rounded-pill px-5 shadow-sm">
                        <i class="fas fa-home me-2"></i>العودة لجدول البيانات
                    </a>
                </div>
            </div>

            <!-- Loading Spinner -->
            <div id="globalLoading" class="text-center py-5" style="display: none;">
                <div class="spinner-grow text-primary" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h5 class="mt-4" id="globalLoadingText">جاري المعالجة...</h5>
            </div>
            
            <div id="globalError" class="alert alert-danger rounded-4 mt-4" style="display: none;"></div>
            <div id="globalSuccess" class="alert alert-success rounded-4 mt-4" style="display: none;"></div>
        </div>
    </div>
</div>

<style>
/* CSS styles for layout */
.min-vh-50 { min-height: 50vh; }
.step-wizard { gap: 10px; margin: 20px 0; }
.step-item { display: flex; flex-direction: column; align-items: center; position: relative; z-index: 1; opacity: 0.4; transition: 0.3s; flex: 1; }
.step-item.active { opacity: 1; transform: scale(1.05); }
.step-item.completed { opacity: 0.8; }
.step-num { width: 38px; height: 38px; background: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; margin-bottom: 8px; border: 3px solid #dee2e6; color: #adb5bd; }
.step-item.active .step-num { background: var(--bs-primary); border-color: #cfe2ff; color: white; box-shadow: 0 0 15px rgba(13, 110, 253, 0.3); }
.step-item.completed .step-num { background: var(--bs-success); border-color: #d1e7dd; color: white; }
.step-label { font-size: 0.85rem; font-weight: 700; color: #6c757d; }
.step-item.active .step-label { color: var(--bs-primary); }
.step-line { flex: 1; height: 2px; background: #dee2e6; margin-top: -30px; position: relative; top: 19px; }
.step-item.completed + .step-line { background: var(--bs-success); }

.upload-icon-box { width: 100px; height: 100px; background: #f0f7ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--bs-primary); border: 2px dashed #cfe2ff; }
.card-radio input { display: none; }
.card-radio .form-check-label { border: 2px solid #dee2e6; transition: 0.2s; cursor: pointer; }
.card-radio input:checked + .form-check-label { border-color: var(--bs-primary); background-color: #f0f7ff; }

.progress-container { position: relative; display: inline-block; }
.progress-content { position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; transform: rotate(0deg); }
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
                    response.preview_data.forEach(row => {
                        bodyHtml += '<tr>';
                        row.forEach(cell => bodyHtml += '<td>' + (cell || '') + '</td>');
                        bodyHtml += '</tr>';
                    });
                    $('#previewBody').html(bodyHtml);

                    // Mapping UI
                    let mappingHtml = '';
                    Object.entries(targetFields).forEach(([field, label]) => {
                        mappingHtml += `
                            <div class="mb-3">
                                <label class="form-label small fw-bold">${label}</label>
                                <select class="form-select column-map-select" data-field="${field}">
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
                    response.results.forEach(row => {
                        let status = row.status === 'ready' ? '<i class="fas fa-check-circle text-success"></i>' : 
                                     (row.status === 'update' ? '<i class="fas fa-sync text-info"></i>' : '<i class="fas fa-times-circle text-danger"></i>');
                        reviewHtml += `<tr>
                            <td class="text-center">${status}</td>
                            <td>${row.errors.length ? row.errors.join(', ') : 'سجل سليم'}</td>
                            <td><div class="text-truncate text-muted" style="max-width: 600px;">${row.data.join(' | ')}</div></td>
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
