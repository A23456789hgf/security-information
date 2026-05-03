@extends('layouts.app')

@section('title', 'استيراد بلاغات أمنية')

@section('content')
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center bg-white p-3 rounded shadow-sm">
                <div>
                    <h2 class="mb-0"><i class="fas fa-file-import text-primary me-2"></i>استيراد بلاغات من Excel</h2>
                    <p class="text-muted mb-0">رفع ملفات Excel ومطابقة البيانات مع سجلات البلاغات</p>
                </div>
                <a href="{{ route('system.security-reports.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-right me-1"></i> العودة لقائمة البلاغات
                </a>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4" id="uploadStep">
        <div class="card-body p-5 text-center">
            <div class="upload-zone py-5" onclick="document.getElementById('excelFile').click()" style="border: 2px dashed #4f46e5; border-radius: 15px; cursor: pointer; transition: 0.3s; background: #f8fafc;">
                <i class="fas fa-cloud-upload-alt fa-4x text-primary mb-3"></i>
                <h3>اسحب ملف Excel هنا أو انقر للاختيار</h3>
                <p class="text-muted">يدعم ملفات xlsx, xls, csv</p>
                <input type="file" id="excelFile" accept=".xlsx,.xls,.csv" style="display:none">
            </div>
            <div id="uploadProgress" class="mt-4 d-none">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">جاري المعالجة...</span>
                </div>
                <p class="mt-2 text-primary fw-bold">جاري تحليل الملف...</p>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm d-none" id="mappingStep">
        <div class="card-header bg-white py-3 border-0">
            <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-columns me-2"></i>مطابقة الأعمدة والبيانات</h5>
        </div>
        <div class="card-body">
            <form id="mappingForm">
                @csrf
                <div class="row">
                    <div class="col-md-8">
                        <div class="bg-light p-4 rounded-3 mb-4">
                            <h6 class="fw-bold mb-3 border-bottom pb-2">حدد الأعمدة المقابلة للحقول المطلوبة</h6>
                            <div class="row g-3">
                                <!-- Core Fields -->
                                <div class="col-md-4">
                                    <h6 class="text-muted small fw-bold mb-3">البيانات الأساسية</h6>
                                    <div class="mb-3">
                                        <label class="form-label small">الاسم الكامل *</label>
                                        <select name="mapping[full_name]" class="form-select form-select-sm header-select" data-field="full_name" required>
                                            <option value="">-- اختر العمود --</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small">موضوع البلاغ *</label>
                                        <select name="mapping[report_name]" class="form-select form-select-sm header-select" data-field="report_name" required>
                                            <option value="">-- اختر العمود --</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small">تاريخ البلاغ</label>
                                        <select name="mapping[report_date]" class="form-select form-select-sm header-select" data-field="report_date">
                                            <option value="">-- اختر العمود --</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small">الرقم العسكري</label>
                                        <select name="mapping[military_number]" class="form-select form-select-sm header-select" data-field="military_number">
                                            <option value="">-- اختر العمود --</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Person Details -->
                                <div class="col-md-4">
                                    <h6 class="text-muted small fw-bold mb-3">بيانات الشخص</h6>
                                    <div class="mb-3">
                                        <label class="form-label small">الرقم الوطني/البطاقة</label>
                                        <select name="mapping[id_number]" class="form-select form-select-sm header-select" data-field="id_number">
                                            <option value="">-- اختر العمود --</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small">الاسم الجهادي</label>
                                        <select name="mapping[jihadi_name]" class="form-select form-select-sm header-select" data-field="jihadi_name">
                                            <option value="">-- اختر العمود --</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small">الهاتف</label>
                                        <select name="mapping[phone]" class="form-select form-select-sm header-select" data-field="phone">
                                            <option value="">-- اختر العمود --</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small">الحالة الاجتماعية</label>
                                        <select name="mapping[marital_status]" class="form-select form-select-sm header-select" data-field="marital_status">
                                            <option value="">-- اختر العمود --</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Organizational Details -->
                                <div class="col-md-4">
                                    <h6 class="text-muted small fw-bold mb-3">الوحدة والعمل</h6>
                                    <div class="mb-3">
                                        <label class="form-label small">الوحدة</label>
                                        <select name="mapping[unit]" class="form-select form-select-sm header-select" data-field="unit">
                                            <option value="">-- اختر العمود --</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small">الحالة الوظيفية</label>
                                        <select name="mapping[job_status]" class="form-select form-select-sm header-select" data-field="job_status">
                                            <option value="">-- اختر العمود --</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small">حالة القبض</label>
                                        <select name="mapping[arrest_status]" class="form-select form-select-sm header-select" data-field="arrest_status">
                                            <option value="">-- اختر العمود --</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card h-100 border-0 bg-dark text-white p-4" style="border-radius: 15px;">
                            <h5 class="fw-bold mb-4">ملخص الملف</h5>
                            <div class="mb-3 d-flex justify-content-between">
                                <span class="opacity-75">إجمالي السجلات:</span>
                                <span class="fw-bold" id="totalRows">0</span>
                            </div>
                            <div class="mb-4">
                                <h6 class="opacity-75 mb-2 small">معاينة أول 5 سجلات (قبل المطابقة):</h6>
                                <div class="table-responsive rounded overflow-hidden">
                                    <table class="table table-sm table-dark table-bordered small mb-0" id="previewTable">
                                        <!-- Populated via JS -->
                                    </table>
                                </div>
                            </div>
                            <div class="mt-auto pt-4 border-top border-secondary">
                                <button type="button" class="btn btn-primary w-100 py-3 fw-bold" onclick="submitImport()">
                                    <i class="fas fa-check-circle me-1"></i> تنفيذ الاستيراد النهائي
                                </button>
                                <button type="button" class="btn btn-outline-light w-100 mt-2" onclick="resetImport()">بدء من جديد</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let headers = [];
    let previewData = [];

    document.getElementById('excelFile').addEventListener('change', function(e) {
        if (this.files.length) handleUpload(this.files[0]);
    });

    function handleUpload(file) {
        document.getElementById('uploadProgress').classList.remove('d-none');
        
        let formData = new FormData();
        formData.append('excel_file', file);
        formData.append('_token', '{{ csrf_token() }}');

        fetch('{{ route("system.security-reports.upload") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('uploadProgress').classList.add('d-none');
            if (data.success) {
                headers = data.columns;
                previewData = data.preview_data;
                document.getElementById('totalRows').textContent = data.total_rows;
                
                populateSelects(headers);
                showPreviewTable(headers, previewData);
                
                document.getElementById('uploadStep').classList.add('d-none');
                document.getElementById('mappingStep').classList.remove('d-none');
            } else {
                alert('خطأ: ' + (data.message || 'فشل تحميل الملف'));
            }
        })
        .catch(error => {
            document.getElementById('uploadProgress').classList.add('d-none');
            alert('حدث خطأ فني أثناء المعالجة');
        });
    }

    function populateSelects(headers) {
        const selects = document.querySelectorAll('.header-select');
        selects.forEach(select => {
            // Clear existing options except first
            while (select.options.length > 1) select.remove(1);
            
            headers.forEach((header, index) => {
                const option = new Option(header, index);
                select.add(option);

                // Auto-match by name
                const fieldName = select.dataset.field;
                const normalizedHeader = header.trim().toLowerCase();
                
                if (
                    (fieldName === 'full_name' && (normalizedHeader.includes('اسم') || normalizedHeader.includes('الكامل'))) ||
                    (fieldName === 'report_name' && (normalizedHeader.includes('موضوع') || normalizedHeader.includes('بلاغ'))) ||
                    (fieldName === 'military_number' && (normalizedHeader.includes('رقم') && normalizedHeader.includes('عسكر')))
                ) {
                    option.selected = true;
                }
            });
        });
    }

    function showPreviewTable(headers, data) {
        const table = document.getElementById('previewTable');
        let html = '<thead><tr>';
        headers.slice(0, 3).forEach(h => html += `<th>${h}</th>`);
        html += '</tr></thead><tbody>';
        
        data.slice(0, 5).forEach(row => {
            html += '<tr>';
            row.slice(0, 3).forEach(cell => html += `<td>${cell || ''}</td>`);
            html += '</tr>';
        });
        html += '</tbody>';
        table.innerHTML = html;
    }

    function submitImport() {
        const form = document.getElementById('mappingForm');
        if (!form.checkValidity()) {
            alert('يرجى تعبئة الحقول المطلوبة (الاسم الكامل وموضوع البلاغ)');
            return;
        }

        const formData = new FormData(form);
        
        fetch('{{ route("system.security-reports.store-import") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                if (data.redirect) window.location.href = data.redirect;
            } else {
                alert('فشل: ' + data.message);
            }
        });
    }

    function resetImport() {
        location.reload();
    }
</script>
@endpush
@endsection
