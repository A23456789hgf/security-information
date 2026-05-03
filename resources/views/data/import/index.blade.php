@extends('layouts.app')

@section('title', 'استيراد البيانات')

@section('content')
<style>
    .data-toolbar {
        background: linear-gradient(135deg, #1a1c2e 0%, #252841 100%);
        border-radius: 12px;
        padding: 15px 20px;
        margin-bottom: 20px;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
    }
    
    .toolbar-group {
        display: flex;
        gap: 8px;
        align-items: center;
        padding: 0 15px;
        border-left: 1px solid rgba(255,255,255,0.1);
    }
    
    .toolbar-group:first-child {
        border-left: none;
        padding-right: 0;
    }
    
    .toolbar-btn {
        background: rgba(255,255,255,0.1);
        border: none;
        color: #fff;
        padding: 8px 14px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .toolbar-btn:hover {
        background: rgba(76, 201, 240, 0.3);
        transform: translateY(-2px);
    }
    
    .toolbar-btn.primary {
        background: linear-gradient(135deg, #4cc9f0 0%, #4895ef 100%);
    }
    
    .toolbar-btn.danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    }
    
    .toolbar-btn.success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
    
    .data-table-container {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
    
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .data-table th {
        background: #1a1c2e;
        color: #fff;
        padding: 12px 15px;
        text-align: right;
        font-weight: 600;
        position: relative;
        cursor: pointer;
    }
    
    .data-table th:hover {
        background: #252841;
    }
    
    .data-table th .col-actions {
        display: none;
        position: absolute;
        left: 5px;
        top: 50%;
        transform: translateY(-50%);
        gap: 5px;
    }
    
    .data-table th:hover .col-actions {
        display: flex;
    }
    
    .col-action-btn {
        background: rgba(255,255,255,0.2);
        border: none;
        color: #fff;
        width: 22px;
        height: 22px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.7rem;
    }
    
    .data-table td {
        padding: 10px 15px;
        border-bottom: 1px solid #eee;
    }
    
    .data-table tr:hover td {
        background: #f8f9ff;
    }
    
    .data-table .row-checkbox {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }
    
    .editable-cell {
        cursor: text;
        min-width: 80px;
        display: block;
    }
    
    .editable-cell:focus {
        outline: 2px solid #4cc9f0;
        border-radius: 4px;
        padding: 2px 5px;
        margin: -2px -5px;
    }
    
    .upload-zone {
        border: 2px dashed #4cc9f0;
        border-radius: 12px;
        padding: 40px;
        text-align: center;
        background: rgba(76, 201, 240, 0.05);
        cursor: pointer;
        transition: all 0.3s;
        margin-bottom: 20px;
    }
    
    .upload-zone:hover, .upload-zone.dragover {
        background: rgba(76, 201, 240, 0.15);
        border-color: #4895ef;
    }
    
    .upload-zone i {
        font-size: 3rem;
        color: #4cc9f0;
        margin-bottom: 15px;
    }
    
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 1060;
        align-items: center;
        justify-content: center;
    }
    
    .modal-overlay.active {
        display: flex;
    }
    
    .modal-box {
        background: #fff;
        border-radius: 15px;
        padding: 25px;
        max-width: 600px;
        width: 90%;
        max-height: 80vh;
        overflow-y: auto;
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #666;
    }
    
    .empty-state i {
        font-size: 4rem;
        color: #ddd;
        margin-bottom: 20px;
    }
    
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }
    
    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a1c2e;
        margin: 0;
    }
    
    .nav-tabs-custom {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }
    
    .nav-tab {
        padding: 10px 20px;
        border-radius: 8px;
        background: #f0f0f0;
        color: #666;
        text-decoration: none;
        transition: all 0.3s;
    }
    
    .nav-tab.active, .nav-tab:hover {
        background: #1a1c2e;
        color: #fff;
    }

    .stats-bar {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }

    .stat-item {
        background: linear-gradient(135deg, #1a1c2e 0%, #252841 100%);
        padding: 15px 25px;
        border-radius: 10px;
        color: #fff;
    }

    .stat-item .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
    }

    .stat-item .stat-label {
        font-size: 0.85rem;
        opacity: 0.8;
    }
</style>

<div class="page-header">
    <h1 class="page-title"><i class="fas fa-file-import me-2"></i>استيراد وإدارة البيانات</h1>
    <div class="nav-tabs-custom">
        <a href="{{ route('data.import.index') }}" class="nav-tab active">
            <i class="fas fa-table me-1"></i> البيانات الرئيسية
        </a>
        <a href="{{ route('data.differentiation.index') }}" class="nav-tab">
            <i class="fas fa-code-compare me-1"></i> المطابقة
        </a>
    </div>
</div>

<div class="stats-bar">
    <div class="stat-item">
        <div class="stat-value" id="columnCount">{{ count($columns) }}</div>
        <div class="stat-label">عدد الأعمدة</div>
    </div>
    <div class="stat-item">
        <div class="stat-value" id="rowCount">{{ count($data) }}</div>
        <div class="stat-label">عدد السجلات</div>
    </div>
    <div class="stat-item">
        <div class="stat-value" id="selectedCount">0</div>
        <div class="stat-label">المحددة</div>
    </div>
</div>

<div class="upload-zone" id="uploadZone" onclick="document.getElementById('excelFile').click()">
    <i class="fas fa-cloud-upload-alt"></i>
    <h4>اسحب ملف Excel هنا أو انقر للاختيار</h4>
    <p class="text-muted mb-0">يدعم ملفات xlsx, xls, csv</p>
    <input type="file" id="excelFile" accept=".xlsx,.xls,.csv" style="display:none">
</div>

<div class="data-toolbar">
    <div class="toolbar-group">
        <button class="w-9 h-9 flex items-center justify-center rounded-lg bg-rose-100 text-rose-700 hover:bg-rose-600 hover:text-white shadow-sm transition-all" onclick="showCreateTableModal()">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                                    <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75V4H5a2 2 0 00-2 2v.25C3 6.664 3.336 7 3.75 7h12.5a.75.75 0 000-1.5h-.25V4A2.75 2.75 0 0013.25 1h-4.5zM6 5.5v-1.75C6 3.224 6.448 2.75 7 2.75h6c.552 0 1 .474 1 1V5.5H6z" clip-rule="evenodd" />
                                                    <path d="M4 8.25V15.5A2.75 2.75 0 006.75 18.25h6.5A2.75 2.75 0 0016 15.5V8.25H4zM7.5 10a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 017.5 10zm5.25.75a.75.75 0 00-1.5 0v4.5a.75.75 0 001.5 0v-4.5z" />
                                                </svg>
                                            </button>
    </div>
    
    <div class="toolbar-group">
        <button class="toolbar-btn" onclick="showNumberingModal()">
            <i class="fas fa-list-ol"></i> ترقيم
        </button>
        <button class="toolbar-btn" onclick="showRemoveSpacesModal()">
            <i class="fas fa-eraser"></i> مسافات
        </button>
        <button class="toolbar-btn" onclick="showReplaceTextModal()">
            <i class="fas fa-exchange-alt"></i> استبدال نص
        </button>
    </div>
    
</div>

<div class="data-table-container">
    @if(count($columns) > 0)
    <table class="data-table" id="dataTable">
        <thead>
            <tr>
                <th style="width:40px">
                    <input type="checkbox" class="row-checkbox" id="selectAll" onchange="toggleSelectAll()">
                </th>
                <th style="width:50px">#</th>
                @foreach($columns as $index => $column)
                <th data-index="{{ $index }}">
                    {{ $column }}
                    <div class="col-actions">
                        <button class="col-action-btn" onclick="editColumnName({{ $index }})" title="تعديل">
                            <i class="fas fa-pen"></i>
                        </button>
                        <button class="col-action-btn" onclick="deleteColumn({{ $index }})" title="حذف">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </th>
                @endforeach
            </tr>
        </thead>
        <tbody id="dataBody">
            @foreach($data as $rowIndex => $row)
            <tr data-row="{{ $rowIndex }}">
                <td><input type="checkbox" class="row-checkbox row-select" data-row="{{ $rowIndex }}"></td>
                <td>{{ $rowIndex + 1 }}</td>
                @foreach($row as $colIndex => $cell)
                <td>
                    <span class="editable-cell" contenteditable="true" 
                          data-row="{{ $rowIndex }}" data-col="{{ $colIndex }}"
                          onblur="updateCell(this)">{{ $cell }}</span>
                </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="empty-state">
        <i class="fas fa-table"></i>
        <h4>لا توجد بيانات</h4>
        <p>قم برفع ملف Excel أو إنشاء جدول جديد للبدء</p>
    </div>
    @endif
</div>

<!-- Create Table Modal -->
<div class="modal-overlay" id="createTableModal">
    <div class="modal-box">
        <h4 class="mb-4"><i class="fas fa-table me-2 text-primary"></i>إنشاء جدول جديد</h4>
        <div id="columnInputs">
            <div class="input-group mb-2">
                <input type="text" class="form-control column-name" placeholder="اسم العمود 1">
                <button class="btn btn-outline-danger" onclick="removeColumnInput(this)"><i class="fas fa-times"></i></button>
            </div>
        </div>
        <button class="btn btn-outline-primary btn-sm mb-3" onclick="addColumnInput()">
            <i class="fas fa-plus"></i> إضافة عمود
        </button>
        <div class="d-flex gap-2 justify-content-end">
            <button class="btn btn-secondary" onclick="closeModal('createTableModal')">إلغاء</button>
            <button class="btn btn-primary" onclick="createTable()">إنشاء</button>
        </div>
    </div>
</div>

<!-- Add Column Modal -->
<div class="modal-overlay" id="addColumnModal">
    <div class="modal-box">
        <h4 class="mb-4"><i class="fas fa-columns me-2 text-primary"></i>إضافة عمود جديد</h4>
        <input type="text" class="form-control mb-3" id="newColumnName" placeholder="اسم العمود">
        <div class="d-flex gap-2 justify-content-end">
            <button class="btn btn-secondary" onclick="closeModal('addColumnModal')">إلغاء</button>
            <button class="btn btn-primary" onclick="addColumn()">إضافة</button>
        </div>
    </div>
</div>

<!-- Numbering Modal -->
<div class="modal-overlay" id="numberingModal">
    <div class="modal-box">
        <h4 class="mb-4"><i class="fas fa-list-ol me-2 text-primary"></i>ترقيم السجلات</h4>
        <div class="mb-3">
            <label class="form-label">العمود</label>
            <select class="form-select" id="numberingColumn"></select>
        </div>
        <div class="mb-3">
            <label class="form-label">البدء من</label>
            <input type="number" class="form-control" id="numberingStart" value="1">
        </div>
        <div class="d-flex gap-2 justify-content-end">
            <button class="btn btn-secondary" onclick="closeModal('numberingModal')">إلغاء</button>
            <button class="btn btn-primary" onclick="applyNumbering()">تطبيق</button>
        </div>
    </div>
</div>

<!-- Remove Spaces Modal -->
<div class="modal-overlay" id="removeSpacesModal">
    <div class="modal-box">
        <h4 class="mb-4"><i class="fas fa-eraser me-2 text-primary"></i>إزالة المسافات</h4>
        <div class="mb-3">
            <label class="form-label">العمود</label>
            <select class="form-select" id="spacesColumn">
                <option value="">جميع الأعمدة</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">النوع</label>
            <select class="form-select" id="spacesMode">
                <option value="trim">إزالة من البداية والنهاية</option>
                <option value="extra">إزالة المسافات الزائدة</option>
                <option value="all">إزالة جميع المسافات</option>
            </select>
        </div>
        <div class="d-flex gap-2 justify-content-end">
            <button class="btn btn-secondary" onclick="closeModal('removeSpacesModal')">إلغاء</button>
            <button class="btn btn-primary" onclick="applyRemoveSpaces()">تطبيق</button>
        </div>
    </div>
</div>

<!-- Reports Mapping Modal -->
<div class="modal-overlay" id="reportsMappingModal">
    <div class="modal-box" style="max-width: 1200px; width: 95%;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="text-primary mb-0"><i class="fas fa-file-import me-2"></i>مطابقة واستيراد البلاغات الأمنية</h4>
            <button type="button" class="btn-close" onclick="closeModal('reportsMappingModal')"></button>
        </div>
        
        <div class="alert alert-info py-2 small">
            <i class="fas fa-info-circle me-1"></i> سيتم استيراد البيانات الحالية (المعالجة) إلى قاعدة بيانات البلاغات. يرجى مطابقة الأعمدة أدناه.
        </div>

        <form id="mappingForm">
            <div class="row">
                <!-- Mapping Columns -->
                <div class="col-md-8">
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3 border-bottom pb-2">مطابقة الأعمدة</h6>
                            <div class="row g-3" style="max-height: 500px; overflow-y: auto;">
                                <!-- Column 1: الأساسية -->
                                <div class="col-md-3">
                                    <h6 class="text-primary small fw-bold border-bottom pb-1 mb-2">البيانات الأساسية</h6>
                                    <div class="mb-2">
                                        <label class="form-label mb-0 small x-small">الاسم الكامل *</label>
                                        <select name="mapping[full_name]" class="form-select form-select-sm mapping-select" data-field="full_name"></select>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label mb-0 small x-small">اسم الشهرة (الجهادي)</label>
                                        <select name="mapping[jihadi_name]" class="form-select form-select-sm mapping-select" data-field="jihadi_name"></select>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label mb-0 small x-small">موضوع البلاغ *</label>
                                        <select name="mapping[report_name]" class="form-select form-select-sm mapping-select" data-field="report_name"></select>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label mb-0 small x-small">نوع البلاغ</label>
                                        <select name="mapping[report_type]" class="form-select form-select-sm mapping-select" data-field="report_type"></select>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label mb-0 small x-small">تاريخ البلاغ</label>
                                        <select name="mapping[report_date]" class="form-select form-select-sm mapping-select" data-field="report_date"></select>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label mb-0 small x-small">الرقم العسكري</label>
                                        <select name="mapping[military_number]" class="form-select form-select-sm mapping-select" data-field="military_number"></select>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label mb-0 small x-small">رقم البطاقة</label>
                                        <select name="mapping[id_number]" class="form-select form-select-sm mapping-select" data-field="id_number"></select>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label mb-0 small x-small">الهاتف</label>
                                        <select name="mapping[phone]" class="form-select form-select-sm mapping-select" data-field="phone"></select>
                                    </div>
                                </div>
                                
                                <!-- Column 2: العمل والوحدة -->
                                <div class="col-md-3">
                                    <h6 class="text-primary small fw-bold border-bottom pb-1 mb-2">الوحدة والعمل</h6>
                                    <div class="mb-2">
                                        <label class="form-label mb-0 small x-small">الوحدة</label>
                                        <select name="mapping[unit]" class="form-select form-select-sm mapping-select" data-field="unit"></select>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label mb-0 small x-small">الوحدة الفرعية</label>
                                        <select name="mapping[sub_unit]" class="form-select form-select-sm mapping-select" data-field="sub_unit"></select>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label mb-0 small x-small">الوحدة التفصيلية</label>
                                        <select name="mapping[detailed_unit]" class="form-select form-select-sm mapping-select" data-field="detailed_unit"></select>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label mb-0 small x-small">الرتبة</label>
                                        <select name="mapping[rank]" class="form-select form-select-sm mapping-select" data-field="rank"></select>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label mb-0 small x-small">التعيين</label>
                                        <select name="mapping[designation]" class="form-select form-select-sm mapping-select" data-field="designation"></select>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label mb-0 small x-small">المسمى الوظيفي</label>
                                        <select name="mapping[job_title]" class="form-select form-select-sm mapping-select" data-field="job_title"></select>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label mb-0 small x-small">العمل / الجهة</label>
                                        <select name="mapping[work]" class="form-select form-select-sm mapping-select" data-field="work"></select>
                                    </div>
                                </div>

                                <!-- Column 3: السكن والأصل -->
                                <div class="col-md-3">
                                    <h6 class="text-primary small fw-bold border-bottom pb-1 mb-2">الأصل والسكن</h6>
                                    <div class="mb-2">
                                        <label class="form-label mb-0 small x-small">الأصل/المحافظة</label>
                                        <select name="mapping[original_governorate]" class="form-select form-select-sm mapping-select" data-field="original_governorate"></select>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label mb-0 small x-small">السكن/المحافظة</label>
                                        <select name="mapping[residence_governorate]" class="form-select form-select-sm mapping-select" data-field="residence_governorate"></select>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label mb-0 small x-small">التخصص</label>
                                        <select name="mapping[specialization]" class="form-select form-select-sm mapping-select" data-field="specialization"></select>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label mb-0 small x-small">الحالة الاجتماعية</label>
                                        <select name="mapping[marital_status]" class="form-select form-select-sm mapping-select" data-field="marital_status"></select>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label mb-0 small x-small">الانتماءات</label>
                                        <select name="mapping[affiliations]" class="form-select form-select-sm mapping-select" data-field="affiliations"></select>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label mb-0 small x-small">الحالة القانونية</label>
                                        <select name="mapping[legal_status]" class="form-select form-select-sm mapping-select" data-field="legal_status"></select>
                                    </div>
                                </div>

                                <!-- Column 4: القانونية -->
                                <div class="col-md-3">
                                    <h6 class="text-primary small fw-bold border-bottom pb-1 mb-2">الحالة القانونية</h6>
                                    <div class="mb-2">
                                        <label class="form-label mb-0 small x-small">حالة القبض</label>
                                        <select name="mapping[arrest_status]" class="form-select form-select-sm mapping-select" data-field="arrest_status"></select>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label mb-0 small x-small">تاريخ القبض</label>
                                        <select name="mapping[arrest_date]" class="form-select form-select-sm mapping-select" data-field="arrest_date"></select>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label mb-0 small x-small">تاريخ الإيداع</label>
                                        <select name="mapping[deposit_date]" class="form-select form-select-sm mapping-select" data-field="deposit_date"></select>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label mb-0 small x-small">التهمة</label>
                                        <select name="mapping[charge]" class="form-select form-select-sm mapping-select" data-field="charge"></select>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label mb-0 small x-small">الإجراءات</label>
                                        <select name="mapping[procedures]" class="form-select form-select-sm mapping-select" data-field="procedures"></select>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label mb-0 small x-small">تاريخ الإطلاق</label>
                                        <select name="mapping[date_of_release]" class="form-select form-select-sm mapping-select" data-field="date_of_release"></select>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label mb-0 small x-small">النشاط الحالي</label>
                                        <select name="mapping[current_activity]" class="form-select form-select-sm mapping-select" data-field="current_activity"></select>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label mb-0 small x-small">ملاحظات</label>
                                        <select name="mapping[notes]" class="form-select form-select-sm mapping-select" data-field="notes"></select>
                                    </div>
                                </div>
                                <div class="col-md-12 text-muted small">
                                    * تذكير: الحقول المؤشر عليها بـ (*) إلزامية لحفظ السجل.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Preview Area -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white py-3">
                            <h6 class="fw-bold mb-0">معاينة البيانات المحددة</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive" style="max-height: 520px;">
                                <table class="table table-sm small mb-0" id="importPreviewTable">
                                    <thead class="bg-light sticky-top">
                                        <tr><th>#</th><th>الاسم (المتوقع)</th><th>الموضوع (المتوقع)</th></tr>
                                    </thead>
                                    <tbody id="importPreviewBody">
                                        <!-- Will be populated by JS -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-white text-center py-3">
                            <button type="button" class="btn btn-primary w-100 mb-2" onclick="submitReportsImport()">
                                <i class="fas fa-check-circle me-1"></i> استيراد النهائي
                            </button>
                            <button type="button" class="btn btn-outline-secondary w-100" onclick="closeModal('reportsMappingModal')">إلغاء</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Import Preview Modal -->
<div class="modal-overlay" id="importPreviewModal">
    <div class="modal-box" style="max-width: 800px">
        <h4 class="mb-4"><i class="fas fa-file-excel me-2 text-success"></i>معاينة الاستيراد</h4>
        <p class="text-muted">تم العثور على <strong id="previewRowCount">0</strong> سجل</p>
        <div class="table-responsive mb-3" style="max-height: 300px; overflow-y: auto;">
            <table class="table table-sm table-bordered" id="previewTable"></table>
        </div>
        <div class="d-flex gap-2 justify-content-end">
            <button class="btn btn-secondary" onclick="closeModal('importPreviewModal')">إلغاء</button>
            <button class="btn btn-primary" onclick="confirmImport()">استيراد البيانات</button>
        </div>
    </div>
</div>

<script>
let currentColumns = @json($columns);
let currentData = @json($data);
let pendingImport = null;

// Upload zone drag & drop
const uploadZone = document.getElementById('uploadZone');
uploadZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadZone.classList.add('dragover');
});
uploadZone.addEventListener('dragleave', () => uploadZone.classList.remove('dragover'));
uploadZone.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadZone.classList.remove('dragover');
    if (e.dataTransfer.files.length) {
        handleFileUpload(e.dataTransfer.files[0]);
    }
});

document.getElementById('excelFile').addEventListener('change', (e) => {
    if (e.target.files.length) handleFileUpload(e.target.files[0]);
});

document.getElementById('replaceFile').addEventListener('change', (e) => {
    if (e.target.files.length) replaceTable(e.target.files[0]);
});

document.getElementById('mergeFile').addEventListener('change', (e) => {
    if (e.target.files.length) mergeData(e.target.files[0]);
});

function handleFileUpload(file) {
    const formData = new FormData();
    formData.append('excel_file', file);
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('origin', '{{ request('origin', 'default') }}');
    
    // Show a loading state if possible
    const uploadBtn = document.querySelector('.upload-btn');
    if (uploadBtn) uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الرفع...';

    fetch('{{ route("data.import.upload") }}', {
        method: 'POST',
        headers: {
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (uploadBtn) uploadBtn.innerHTML = '<i class="fas fa-upload"></i> اختر ملف إكسل';
        if (data.success) {
            pendingImport = data;
            showImportPreview(data);
        } else {
            alert('خطأ في قراءة الملف: ' + (data.message || 'خطأ غير معروف'));
        }
    })
    .catch(err => {
        if (uploadBtn) uploadBtn.innerHTML = '<i class="fas fa-upload"></i> اختر ملف إكسل';
        console.error('Upload error:', err);
        alert('حدث خطأ أثناء رفع الملف');
    });
}

function showImportPreview(data) {
    document.getElementById('previewRowCount').textContent = data.total_rows;
    
    let html = '<thead><tr>';
    data.columns.forEach(col => html += `<th>${col}</th>`);
    html += '</tr></thead><tbody>';
    
    data.preview_data.forEach(row => {
        html += '<tr>';
        row.forEach(cell => html += `<td>${cell || ''}</td>`);
        html += '</tr>';
    });
    html += '</tbody>';
    
    document.getElementById('previewTable').innerHTML = html;
    document.getElementById('importPreviewModal').classList.add('active');
}

function confirmImport() {
    if (!pendingImport) return;
    
    // In the persistent version, handleFileUpload already saved the data.
    // We just need to reload the page to see the new data in the main table.
    closeModal('importPreviewModal');
    location.reload(); 
}

function replaceTableDirect() {
    const file = document.getElementById('excelFile').files[0];
    if (!file) return;
    replaceTable(file);
}

function replaceTable(file) {
    const formData = new FormData();
    formData.append('excel_file', file);
    formData.append('_token', '{{ csrf_token() }}');
    
    fetch('{{ route("data.import.replace-table") }}', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) location.reload();
    });
}

function mergeData(file) {
    const formData = new FormData();
    formData.append('excel_file', file);
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('origin', '{{ request('origin', 'default') }}');
    
    // Show loading indicator if possible
    const mergeBtn = document.querySelector('button[onclick*="mergeFile"]');
    if (mergeBtn) {
        mergeBtn.disabled = true;
        mergeBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> دمج...';
    }

    fetch('{{ route("data.import.merge") }}', {
        method: 'POST',
        headers: {
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(r => r.json().catch(() => ({ success: false, message: 'استجابة غير صالحة من الخادم' })))
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            if (mergeBtn) {
                mergeBtn.disabled = false;
                mergeBtn.innerHTML = '<i class="fas fa-code-merge"></i> دمج';
            }
            alert('خطأ في الدمج: ' + (data.message || 'خطأ غير معروف'));
        }
    })
    .catch(err => {
        if (mergeBtn) {
            mergeBtn.disabled = false;
            mergeBtn.innerHTML = '<i class="fas fa-code-merge"></i> دمج';
        }
        console.error('Merge error:', err);
        alert('حدث خطأ أثناء الاتصال بالخادم');
    });
}

function replaceTable(file) {
    const formData = new FormData();
    formData.append('excel_file', file);
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('origin', '{{ request('origin', 'default') }}');

    fetch('{{ route("data.import.replace-table") }}', {
        method: 'POST',
        headers: {
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(r => r.json().catch(() => ({ success: false, message: 'استجابة غير صالحة من الخادم' })))
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('خطأ في الاستبدال: ' + (data.message || 'خطأ غير معروف'));
        }
    })
    .catch(err => {
        console.error('Replace error:', err);
        alert('حدث خطأ أثناء الاتصال بالخادم');
    });
}

// Table operations
function toggleSelectAll() {
    const checked = document.getElementById('selectAll').checked;
    document.querySelectorAll('.row-select').forEach(cb => cb.checked = checked);
    updateSelectedCount();
}

function updateSelectedCount() {
    const count = document.querySelectorAll('.row-select:checked').length;
    document.getElementById('selectedCount').textContent = count;
}

document.querySelectorAll('.row-select').forEach(cb => {
    cb.addEventListener('change', updateSelectedCount);
});

function updateCell(el) {
    const row = el.dataset.row;
    const col = el.dataset.col;
    const value = el.textContent;
    
    fetch('{{ route("data.import.update-cell") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ row, col, value })
    });
}

function insertRow() {
    fetch('{{ route("data.import.insert-row") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({})
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) location.reload();
    });
}

function saveTable() {
    const saveBtn = document.getElementById('saveBtn');
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';

    // Extract columns properly without the actions div
    const columns = Array.from(document.querySelectorAll('.data-table th[data-index]')).map(th => {
        const textNode = Array.from(th.childNodes).find(node => node.nodeType === Node.TEXT_NODE);
        return textNode ? textNode.textContent.trim() : th.innerText.replace(/\n.*/g, '').trim();
    });

    const data = [];
    document.querySelectorAll('.data-table tbody tr').forEach(tr => {
        const row = [];
        tr.querySelectorAll('.editable-cell').forEach(cell => {
            row.push(cell.textContent.trim());
        });
        if (row.length > 0) data.push(row);
    });

    fetch('{{ route("data.import.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ columns, data })
    })
    .then(r => r.json())
    .then(res => {
        saveBtn.disabled = false;
        saveBtn.innerHTML = '<i class="fas fa-save"></i> حفظ التعديلات';
        if (res.success) {
            alert('تم حفظ البيانات بنجاح');
        } else {
            alert('خطأ في الحفظ: ' + res.message);
        }
    })
    .catch(err => {
        saveBtn.disabled = false;
        saveBtn.innerHTML = '<i class="fas fa-save"></i> حفظ التعديلات';
        console.error('Save error:', err);
        alert('حدث خطأ أثناء الاتصال بالخادم');
    });
}

function deleteSelectedRows() {
    const indices = [];
    document.querySelectorAll('.row-select:checked').forEach(cb => {
        indices.push(parseInt(cb.dataset.row));
    });
    
    if (!indices.length) {
        alert('الرجاء تحديد سجلات للحذف');
        return;
    }
    
    if (!confirm(`هل تريد حذف ${indices.length} سجل؟`)) return;
    
    fetch('{{ route("data.import.delete-rows") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ indices })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) location.reload();
    });
}

// Column operations
function editColumnName(index) {
    const newName = prompt('اسم العمود الجديد:');
    if (!newName) return;
    
    fetch('{{ route("data.import.rename-column") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ index, name: newName })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) location.reload();
    });
}

function deleteColumn(index) {
    if (!confirm('هل تريد حذف هذا العمود؟')) return;
    
    fetch('{{ route("data.import.delete-column") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ index })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) location.reload();
    });
}

// Modals
function showModal(id) {
    document.getElementById(id).classList.add('active');
}

function closeModal(id) {
    document.getElementById(id).classList.remove('active');
}

function showCreateTableModal() {
    showModal('createTableModal');
}

function addColumnInput() {
    const count = document.querySelectorAll('.column-name').length + 1;
    const html = `
        <div class="input-group mb-2">
            <input type="text" class="form-control column-name" placeholder="اسم العمود ${count}">
            <button class="btn btn-outline-danger" onclick="removeColumnInput(this)"><i class="fas fa-times"></i></button>
        </div>
    `;
    document.getElementById('columnInputs').insertAdjacentHTML('beforeend', html);
}

function removeColumnInput(btn) {
    btn.parentElement.remove();
}

function createTable() {
    const columns = [];
    document.querySelectorAll('.column-name').forEach(input => {
        if (input.value.trim()) columns.push(input.value.trim());
    });
    
    if (!columns.length) {
        alert('الرجاء إدخال اسم عمود واحد على الأقل');
        return;
    }
    
    fetch('{{ route("data.import.create-table") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ columns })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) location.reload();
    });
}

function showAddColumnModal() {
    document.getElementById('newColumnName').value = '';
    showModal('addColumnModal');
}

function addColumn() {
    const name = document.getElementById('newColumnName').value.trim();
    if (!name) {
        alert('الرجاء إدخال اسم العمود');
        return;
    }
    
    fetch('{{ route("data.import.insert-column") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ name })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) location.reload();
    });
}

function populateColumnSelect(selectId) {
    const select = document.getElementById(selectId);
    select.innerHTML = '';
    currentColumns.forEach((col, i) => {
        select.innerHTML += `<option value="${i}">${col}</option>`;
    });
}

function showNumberingModal() {
    populateColumnSelect('numberingColumn');
    showModal('numberingModal');
}

function applyNumbering() {
    const columnIndex = document.getElementById('numberingColumn').value;
    const start = document.getElementById('numberingStart').value || 1;
    
    fetch('{{ route("data.import.number-records") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ column_index: columnIndex, start })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) location.reload();
    });
}

function showRemoveSpacesModal() {
    const select = document.getElementById('spacesColumn');
    select.innerHTML = '<option value="">جميع الأعمدة</option>';
    currentColumns.forEach((col, i) => {
        select.innerHTML += `<option value="${i}">${col}</option>`;
    });
    showModal('removeSpacesModal');
}

function applyRemoveSpaces() {
    const columnIndex = document.getElementById('spacesColumn').value || null;
    const mode = document.getElementById('spacesMode').value;
    
    fetch('{{ route("data.import.remove-spaces") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ column_index: columnIndex, mode })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) location.reload();
    });
}

function showReplaceTextModal() {
    populateColumnSelect('replaceColumn');
    document.getElementById('findText').value = '';
    document.getElementById('replaceWith').value = '';
    showModal('replaceTextModal');
}

function applyReplaceText() {
    const columnIndex = document.getElementById('replaceColumn').value;
    const find = document.getElementById('findText').value;
    const replace = document.getElementById('replaceWith').value;
    
    if (!find) {
        alert('الرجاء إدخال نص للبحث');
        return;
    }
    
    fetch('{{ route("data.import.replace-text") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ column_index: columnIndex, find, replace })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        }
    });
}

function exportData() {
    window.location.href = '{{ route("data.import.export") }}';
}

const currentData = @json($data);

const fieldLabels = {
    'full_name': ['الاسم الكامل', 'الاسم واللقب', 'الاسم', 'name', 'full name'],
    'report_name': ['موضوع البلاغ', 'الموضوع', 'البلاغ', 'subject', 'report name'],
    'report_type': ['نوع البلاغ', 'النوع', 'type'],
    'report_date': ['تاريخ البلاغ', 'التاريخ', 'date'],
    'military_number': ['الرقم العسكري', 'رقم عسكري', 'military id'],
    'id_number': ['رقم البطاقة', 'رقم الهوية', 'الرقم الوطني', 'id number'],
    'phone': ['الهاتف', 'رقم الهاتف', 'الجوال', 'phone'],
    'age': ['العمر', 'age'],
    'unit': ['الوحدة', 'الجهة', 'unit'],
    'sub_unit': ['الوحدة الفرعية', 'sub unit'],
    'rank': ['الرتبة', 'rank'],
    'designation': ['التعيين', 'التكليف', 'designation'],
    'qualification': ['المؤهل', 'التعليم', 'qualification'],
    'arrest_status': ['حالة القبض', 'الحالة', 'arrest status'],
    'arrest_date': ['تاريخ القبض', 'تاريخ الحجز', 'arrest date'],
    'charge': ['التهمة', 'القضية', 'charge'],
    'residence_governorate': ['المحافظة', 'محافظة السكن', 'governorate'],
    'notes': ['ملاحظات', 'ملاحظة', 'notes']
};

function showReportsMappingModal() {
    const selects = document.querySelectorAll('.mapping-select');
    selects.forEach(select => {
        const fieldName = select.getAttribute('data-field');
        select.innerHTML = '<option value="">-- اختر العمود --</option>';
        
        currentColumns.forEach((col, i) => {
            let isSelected = false;
            const normalizedCol = col.trim().toLowerCase();
            
            // Map using dictionary
            if (fieldLabels[fieldName]) {
                isSelected = fieldLabels[fieldName].some(label => 
                    normalizedCol.includes(label.toLowerCase()) || label.toLowerCase().includes(normalizedCol)
                );
            }
            
            // Fallback to exact field name match
            if (!isSelected) {
                isSelected = normalizedCol.includes(fieldName.toLowerCase());
            }

            select.innerHTML += `<option value="${i}" ${isSelected ? 'selected' : ''}>${col}</option>`;
        });
        
        select.addEventListener('change', updateImportPreview);
    });
    
    updateImportPreview();
    showModal('reportsMappingModal');
}

function updateImportPreview() {
    const body = document.getElementById('importPreviewBody');
    body.innerHTML = '';
    
    const fullNameCol = document.querySelector('select[name="mapping[full_name]"]').value;
    const reportNameCol = document.querySelector('select[name="mapping[report_name]"]').value;
    
    // Show first 10 rows as preview
    currentData.slice(0, 10).forEach((row, i) => {
        const name = fullNameCol !== '' ? row[fullNameCol] : '<لم يحدد>';
        const subject = reportNameCol !== '' ? row[reportNameCol] : '<لم يحدد>';
        
        body.innerHTML += `
            <tr>
                <td>${i + 1}</td>
                <td><span class="text-truncate d-inline-block" style="max-width: 150px;">${name || '-'}</span></td>
                <td><span class="text-truncate d-inline-block" style="max-width: 150px;">${subject || '-'}</span></td>
            </tr>
        `;
    });
    
    if (currentData.length > 10) {
        body.innerHTML += `<tr><td colspan="3" class="text-center text-muted small">... و ${currentData.length - 10} سجلات أخرى</td></tr>`;
    }
}

function submitReportsImport() {
    const form = document.getElementById('mappingForm');
    const formData = new FormData(form);
    const mapping = {};
    
    let hasMapping = false;
    formData.forEach((value, key) => {
        const fieldMatch = key.match(/\[(.*?)\]/);
        if (fieldMatch) {
            const field = fieldMatch[1];
            mapping[field] = value;
            if (value !== '') hasMapping = true;
        }
    });

    if (mapping.full_name === '' && mapping.report_name === '') {
        alert('الرجاء تحديد عمود الاسم الكامل أو موضوع البلاغ على الأقل لإجراء الاستيراد');
        return;
    }

    if (!confirm(`سيتم استيراد ${currentData.length} سجل إلى قاعدة بيانات البلاغات. هل أنت متأكد؟`)) return;

    // Show loading state
    const btn = event.target;
    const originalContent = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> جاري الاستيراد...';

    fetch('{{ route("system.security-reports.store-import") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ mapping })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            window.location.href = data.redirect;
        } else {
            alert('خطأ: ' + data.message);
            btn.disabled = false;
            btn.innerHTML = originalContent;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ غير متوقع أثناء عملية الاستيراد');
        btn.disabled = false;
        btn.innerHTML = originalContent;
    });
}

// Close modals on outside click
document.querySelectorAll('.modal-overlay').forEach(modal => {
    modal.addEventListener('click', (e) => {
        if (e.target === modal) modal.classList.remove('active');
    });
});
</script>
@endsection
