@extends('layouts.app')

@section('title', 'استيراد بيانات شخصية')

@section('content')
<div class="container-fluid mt-4" dir="rtl">
    <div class="row mb-4">
        <div class="col-12 text-end">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('system.personal-data.index') }}">البيانات الشخصية</a></li>
                    <li class="breadcrumb-item active" aria-current="page">استيراد بيانات</li>
                </ol>
            </nav>
            <div class="bg-white p-4 rounded-4 shadow-sm border-start border-primary border-5">
                <h2 class="mb-1 text-primary"><i class="fas fa-file-import me-2"></i>معالج استيراد البيانات الشخصية</h2>
                <p class="text-muted mb-0">استخدم هذا المعالج لرفع كميات كبيرة من السجلات دفعة واحدة. يدعم النظام المطابقة الذكية للارتباطات (المحافظات، المديريات، الرتب، إلخ).</p>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-light py-3 border-bottom">
            <div class="d-flex justify-content-center">
                <div class="step-wizard d-flex align-items-center w-100" style="max-width: 800px;">
                    <div class="step-item active" id="step1-indicator">
                        <span class="step-num">1</span>
                        <span class="step-label">نوع الملف</span>
                    </div>
                    <div class="step-line"></div>
                    <div class="step-item" id="step2-indicator">
                        <span class="step-num">2</span>
                        <span class="step-label">تخطيط الحقول</span>
                    </div>
                    <div class="step-line"></div>
                    <div class="step-item" id="step3-indicator">
                        <span class="step-num">3</span>
                        <span class="step-label">المعاينة</span>
                    </div>
                    <div class="step-line"></div>
                    <div class="step-item" id="step4-indicator">
                        <span class="step-num">4</span>
                        <span class="step-label">المعالجة</span>
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
                    <h4 class="fw-bold">اختر ملف الإكسل (Excel/CSV)</h4>
                    <p class="text-muted mb-4">تأكد أن الملف يحتوي على البيانات المطلوبة بشكل صحيح. يمكنك تحميل النموذج بالأسفل.</p>
                    
                    <div class="mx-auto" style="max-width: 500px;">
                        <input class="form-control form-control-lg mb-3 shadow-sm" type="file" id="importFile" accept=".xlsx, .xls, .csv">
                        <button class="btn btn-primary btn-lg w-100 rounded-pill px-5" id="btnNextToMapping">
                            بدء المعالجة <i class="fas fa-arrow-left ms-1"></i>
                        </button>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('system.personal-data.template') }}" class="btn btn-outline-secondary rounded-pill">
                            <i class="fas fa-download me-1"></i>تحميل نموذج استيراد البيانات الشخصية
                        </a>
                    </div>
                </div>
            </div>

            <!-- Step 2: Mapping -->
            <div id="mappingSection" class="import-step" style="display: none;">
                <div class="row g-4">
                    <div class="col-lg-5">
                        <div class="p-4 bg-light rounded-4 border">
                            <h5 class="fw-bold mb-4 border-bottom pb-2">طريقة الاستيراد</h5>
                            <div class="mb-4">
                                <div class="form-check card-radio mb-2">
                                    <input class="form-check-input" type="radio" name="import_mode" id="modeAppend" value="append" checked>
                                    <label class="form-check-label p-3 border rounded-3 w-100" for="modeAppend">
                                        <div class="fw-bold">تجاهل المكرر</div>
                                        <small class="text-muted">عدم تحديث السجلات الموجودة مسبقاً (اعتماداً على رقم الهوية).</small>
                                    </label>
                                </div>
                                <div class="form-check card-radio mb-2">
                                    <input class="form-check-input" type="radio" name="import_mode" id="modeUpsert" value="upsert">
                                    <label class="form-check-label p-3 border rounded-3 w-100" for="modeUpsert">
                                        <div class="fw-bold text-success">تحديث المكرر</div>
                                        <small class="text-muted">تحديث بيانات السجل إذا كان رقم الهوية موجود مسبقاً.</small>
                                    </label>
                                </div>
                            </div>

                            <h5 class="fw-bold mb-3 border-bottom pb-2">مطابقة الأعمدة</h5>
                            <div id="mappingContainer" style="max-height: 400px; overflow-y: auto; padding-right: 5px;">
                                <!-- Dynamic -->
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="h-100 d-flex flex-column">
                            <h5 class="fw-bold mb-3">معاينة الملف المرفوع</h5>
                            <div class="table-responsive border rounded-4 bg-white flex-grow-1 shadow-sm">
                                <table class="table table-hover mb-0" id="previewTable" style="font-size: 0.85rem;">
                                    <thead class="bg-light sticky-top">
                                        <tr id="previewHeaders"></tr>
                                    </thead>
                                    <tbody id="previewBody"></tbody>
                                </table>
                            </div>
                            <div class="mt-4 text-start">
                                <button class="btn btn-outline-secondary rounded-pill px-4 me-2" onclick="showStep(1)">السابق</button>
                                <button class="btn btn-primary rounded-pill px-5" id="btnNextToReview">مراجعة والتحقق</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3: Review -->
            <div id="reviewSection" class="import-step" style="display: none;">
                <div class="alert alert-info rounded-4 border-0 shadow-sm mb-4">
                    <i class="fas fa-info-circle me-2"></i>سيقوم النظام الآن بعرض عينة من 100 سجل للتحقق من صحة البيانات قبل التنفيذ الحقيقي.
                </div>
                <div class="table-responsive border rounded-4 bg-white shadow-sm" style="max-height: 450px;">
                    <table class="table table-hover table-bordered mb-0" id="reviewTable" style="font-size: 0.8rem;">
                        <thead class="bg-light sticky-top">
                            <tr>
                                <th class="text-center" width="60">الحالة</th>
                                <th>الأخطاء المحتملة</th>
                                <th>بيانات الصف</th>
                            </tr>
                        </thead>
                        <tbody id="reviewBody"></tbody>
                    </table>
                </div>
                <div class="mt-4 d-flex justify-content-between">
                    <button class="btn btn-outline-secondary rounded-pill px-4" onclick="showStep(2)">تعديل التخطيط</button>
                    <button class="btn btn-success rounded-pill px-5 shadow" id="btnConfirmImport">تأكيد وبدء الاستيراد النهائي</button>
                </div>
            </div>

            <!-- Step 4: Progress -->
            <div id="progressSection" class="import-step" style="display: none;">
                <div class="text-center py-5">
                        <div class="progress-ring-wrapper position-relative d-inline-flex align-items-center justify-content-center">
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
                        <p class="text-muted mt-3" id="progressStatusText">جاري المعالجة في الخلفية...</p>
                    <div class="row g-3 justify-content-center mb-4">
                        <div class="col-6 col-md-2">
                            <div class="p-3 border rounded-4 bg-light">
                                <small class="text-muted d-block mb-1">تمت معالجته</small>
                                <strong class="fs-4" id="processedCount">0</strong>
                            </div>
                        </div>
                        <div class="col-6 col-md-2">
                            <div class="p-3 border rounded-4 bg-light text-success">
                                <small class="text-muted d-block mb-1">جديد</small>
                                <strong class="fs-4" id="createdCount">0</strong>
                            </div>
                        </div>
                        <div class="col-6 col-md-2">
                            <div class="p-3 border rounded-4 bg-light text-info">
                                <small class="text-muted d-block mb-1">محدث</small>
                                <strong class="fs-4" id="updatedCount">0</strong>
                            </div>
                        </div>
                        <div class="col-6 col-md-2">
                            <div class="p-3 border rounded-4 bg-light text-danger">
                                <small class="text-muted d-block mb-1">فشل</small>
                                <strong class="fs-4" id="failedCount">0</strong>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('system.personal-data.index') }}" class="btn btn-primary rounded-pill px-5">الذهاب لجدول البيانات</a>
                </div>
            </div>

            <!-- Global Loading -->
            <div id="globalLoading" class="text-center py-5" style="display: none;">
                <div class="spinner-border text-primary mb-3" role="status"></div>
                <h5 id="globalLoadingText">جاري التحميل...</h5>
            </div>
        </div>
    </div>
</div>

<style>
.step-item.active { opacity: 1; font-weight: bold; }
.progress-ring-wrapper { display: flex; align-items: center; justify-content: center; }
.gauge-center { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 140px; height: 140px; background: white; border-radius: 50%; box-shadow: 0 10px 25px rgba(0,0,0,0.05); display: flex; flex-direction: column; align-items: center; justify-content: center; }
.speed-value { line-height: 1; transition: all 0.3s ease; }
.speed-value.pulse { transform: scale(1.1); text-shadow: 0 0 10px rgba(var(--bs-primary-rgb), 0.3); }
.progress-ring__circle { transition: stroke-dashoffset 0.5s ease; transform: rotate(-90deg); transform-origin: 50% 50%; }
.tiny { font-size: 0.7rem; }
.step-num { width: 35px; height: 35px; background: #eee; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 5px; }
.step-item.active .step-num { background: var(--bs-primary); color: white; }
.step-line { flex: 1; height: 2px; background: #eee; margin-top: -30px; }
.upload-icon-box { width: 80px; height: 80px; background: #f8f9fa; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px dashed #ddd; }
.sticky-top { position: sticky; top: -1px; z-index: 100; }

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
</style>

@push('styles')
<link href="{{ asset('vendor/select2/select2.min.css') }}" rel="stylesheet" />
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
        $(`.step-item`).css('opacity', '0.3').removeClass('active');
        for(let i=1; i<=step; i++) $(`#step${i}-indicator`).css('opacity', i == step ? '1' : '0.6').addClass(i == step ? 'active' : '');

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
                    // Preview Headers
                    let hHtml = '';
                    res.columns.forEach(c => hHtml += '<th>'+(c||'-')+'</th>');
                    $('#previewHeaders').html(hHtml);

                    // Preview Body
                    let bHtml = '';
                    res.preview_data.forEach(row => {
                        bHtml += '<tr>';
                        row.forEach(cell => bHtml += '<td>'+(cell||'')+'</td>');
                        bHtml += '</tr>';
                    });
                    $('#previewBody').html(bHtml);

                    // Mapping
                    let mHtml = '';
                    Object.entries(targetFields).forEach(([key, label]) => {
                        mHtml += `<div class="mb-3">
                            <label class="form-label small fw-bold text-muted">${label}</label>
                            <select class="form-select form-select-sm mapping-select" data-field="${key}">
                                <option value="">تجاهل</option>
                                ${res.columns.map((c, i) => {
                                    let selected = (String(c).trim() === String(label).trim()) ? 'selected' : '';
                                    return `<option value="${i}" ${selected}>${c || ('عمود ' + (i+1))}</option>`;
                                }).join('')}
                            </select>
                        </div>`;
                    });
                    $('#mappingContainer').html(mHtml);
                    
                    // Initialize Select2 for mapping dropdowns
                    $('.mapping-select').select2({
                        dir: 'rtl',
                        width: '100%',
                        dropdownParent: $('#mappingSection')
                    });
                    
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
                    res.results.forEach(row => {
                        let status = row.status === 'error' ? '<i class="fas fa-times-circle text-danger"></i>' : '<i class="fas fa-check-circle text-success"></i>';
                        rHtml += `<tr>
                            <td class="text-center">${status}</td>
                            <td class="text-danger small">${row.errors.join('<br>')}</td>
                            <td><div class="text-truncate text-muted" style="max-width: 500px;">${row.data.join(' | ')}</div></td>
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
                    // Synchronous handle
                    $('#progressPercent').text('100%');
                    $('#processedCount').text(res.results.processed);
                    $('#createdCount').text(res.results.created);
                    $('#updatedCount').text(res.results.updated);
                    $('#failedCount').text(res.results.failed);
                    
                    if (res.results.errors && res.results.errors.length > 0) {
                        $('#progressStatusText').html('اكتمل مع وجود أخطاء:<br><small class="text-danger">' + res.results.errors.slice(0, 5).join('<br>') + (res.results.errors.length > 5 ? '<br>...' : '') + '</small>');
                    } else {
                        $('#progressStatusText').text('اكتمل بنجاح!').addClass('text-success');
                    }
                    
                    $('#rpmValue').text('-');
                    showStep(4);
                }
            } else {
                alert(res.message);
                showStep(3);
            }
        });
    });

    let startTime = null;
    let lastProcessedCount = 0;

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

                    if(res.status === 'completed') {
                        clearInterval(interval);
                        $('#progressStatusText').text('اكتمل بنجاح!').addClass('text-success');
                    }
                }
            });
        }, 2000);
    }
});
</script>
@endpush
@endsection
