@extends('layouts.app')

@section('title', 'مطابقة البيانات')

@section('content')
<style>
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

    .diff-layout {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 25px;
    }

    .main-panel {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        padding: 25px;
    }

    .sidebar-panel {
        background: linear-gradient(180deg, #1a1c2e 0%, #252841 100%);
        border-radius: 12px;
        padding: 20px;
        color: #fff;
        max-height: calc(100vh - 200px);
        overflow-y: auto;
    }

    .sidebar-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .result-item {
        background: rgba(255,255,255,0.05);
        border-radius: 10px;
        padding: 12px 15px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .result-item:hover {
        background: rgba(76, 201, 240, 0.15);
        transform: translateX(-5px);
    }

    .result-item.active {
        background: rgba(76, 201, 240, 0.25);
        border-right: 3px solid #4cc9f0;
    }

    .result-label {
        font-size: 0.9rem;
    }

    .result-count {
        background: #4cc9f0;
        color: #1a1c2e;
        padding: 3px 12px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.85rem;
    }

    .result-count.zero {
        background: rgba(255,255,255,0.2);
        color: #999;
    }

    .upload-secondary {
        border: 2px dashed rgba(255,255,255,0.3);
        border-radius: 12px;
        padding: 30px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        margin-bottom: 20px;
    }

    .upload-secondary:hover {
        background: rgba(255,255,255,0.05);
        border-color: #4cc9f0;
    }

    .upload-secondary i {
        font-size: 2rem;
        color: #4cc9f0;
        margin-bottom: 10px;
    }

    .primary-info {
        background: rgba(76, 201, 240, 0.1);
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 20px;
    }

    .primary-info .info-label {
        font-size: 0.8rem;
        color: #888;
    }

    .primary-info .info-value {
        font-size: 1.3rem;
        font-weight: 700;
        color: #1a1c2e;
    }

    .data-preview {
        max-height: 400px;
        overflow-y: auto;
    }

    .data-preview table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-preview th {
        background: #1a1c2e;
        color: #fff;
        padding: 10px 12px;
        text-align: right;
        font-size: 0.85rem;
        position: sticky;
        top: 0;
    }

    .data-preview td {
        padding: 8px 12px;
        border-bottom: 1px solid #eee;
        font-size: 0.85rem;
    }

    .data-preview tr:hover td {
        background: #f8f9ff;
    }

    .match-highlight {
        background: #d4edda !important;
    }

    .diff-highlight {
        background: #f8d7da !important;
    }

    .empty-results {
        text-align: center;
        padding: 40px 20px;
        color: rgba(255,255,255,0.5);
    }

    .empty-results i {
        font-size: 3rem;
        margin-bottom: 15px;
    }

    .category-header {
        font-size: 0.75rem;
        text-transform: uppercase;
        color: rgba(255,255,255,0.5);
        padding: 10px 0 5px;
        margin-top: 15px;
        border-top: 1px solid rgba(255,255,255,0.1);
    }

    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.7);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 2000;
        flex-direction: column;
        gap: 20px;
    }

    .loading-overlay.active {
        display: flex;
    }

    .loading-spinner {
        width: 50px;
        height: 50px;
        border: 4px solid rgba(255,255,255,0.2);
        border-top-color: #4cc9f0;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .detail-panel {
        display: none;
    }

    .detail-panel.active {
        display: block;
    }

    .detail-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .back-btn {
        background: none;
        border: none;
        color: #4cc9f0;
        cursor: pointer;
        font-size: 0.9rem;
    }

    .tabs-container {
        display: flex;
        gap: 15px;
        border-bottom: 1px solid #eee;
        margin-bottom: 20px;
    }

    .tab-btn {
        padding: 10px 5px;
        background: none;
        border: none;
        color: #666;
        font-weight: 600;
        cursor: pointer;
        position: relative;
        transition: all 0.3s;
    }

    .tab-btn.active {
        color: #1a1c2e;
    }

    .tab-btn.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        right: 0;
        height: 2px;
        background: #1a1c2e;
    }

    .action-bar {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
        justify-content: flex-end;
    }

    .btn-action {
        padding: 6px 15px;
        border-radius: 6px;
        font-size: 0.85rem;
        border: 1px solid #ddd;
        background: #fff;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .btn-action:hover {
        background: #f8f9fa;
        border-color: #ccc;
    }

    .btn-action.primary {
        background: #1a1c2e;
        color: #fff;
        border-color: #1a1c2e;
    }

    .btn-action.primary:hover {
        background: #252841;
    }
</style>

<div class="page-header">
    <h1 class="page-title"><i class="fas fa-code-compare me-2"></i>مطابقة البيانات</h1>
    <div class="nav-tabs-custom">
        <a href="{{ route('data.import.index') }}" class="nav-tab">
            <i class="fas fa-table me-1"></i> البيانات الرئيسية
        </a>
        <a href="{{ route('data.differentiation.index') }}" class="nav-tab active">
            <i class="fas fa-code-compare me-1"></i> المطابقة
        </a>
    </div>
</div>

<div class="diff-layout">
    <div class="main-panel">
        <div class="tabs-container">
            <button class="tab-btn active" id="primaryTab" onclick="switchMainTab('primary')">البيانات الرئيسية</button>
            <button class="tab-btn" id="referenceTab" onclick="switchMainTab('reference')">البيانات المرجعية</button>
        </div>

        <div id="primaryContent">
            <div class="primary-info">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-label">البيانات الرئيسية</div>
                        <div class="info-value">{{ count($primaryData) }} سجل</div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-label">الأعمدة</div>
                        <div class="info-value">{{ count($primaryColumns) }} عمود</div>
                    </div>
                </div>
            </div>

            <div id="overviewPanel">
                <h5 class="mb-3">البيانات الرئيسية</h5>
                @if(count($primaryData) > 0)
                <div class="data-preview">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                @foreach($primaryColumns as $col)
                                <th>{{ $col }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(array_slice($primaryData, 0, 50) as $i => $row)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                @foreach($row as $cell)
                                <td>{{ $cell }}</td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if(count($primaryData) > 50)
                <p class="text-muted mt-2 small">عرض أول 50 سجل من {{ count($primaryData) }}</p>
                @endif
                @else
                <div class="text-center py-5">
                    <i class="fas fa-database fa-3x text-muted mb-3"></i>
                    <p class="text-muted">لا توجد بيانات رئيسية. <a href="{{ route('data.import.index') }}">قم بالاستيراد أولاً</a></p>
                </div>
                @endif
            </div>
        </div>

        <div id="referenceContent" style="display:none">
            <div class="action-bar">
                <button class="btn-action" onclick="showAddColumnModal()">
                    <i class="fas fa-columns"></i> إضافة عمود
                </button>
                <button class="btn-action primary" onclick="integrateAll()">
                    <i class="fas fa-file-import"></i> دمج الكل في الرئيسية
                </button>
            </div>
            
            @if(count($secondaryData) > 0)
            <div class="data-preview">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            @foreach($secondaryColumns as $col)
                            <th>{{ $col }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(array_slice($secondaryData, 0, 50) as $i => $row)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            @foreach($row as $cell)
                            <td>{{ $cell }}</td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if(count($secondaryData) > 50)
            <p class="text-muted mt-2 small">عرض أول 50 سجل من {{ count($secondaryData) }}</p>
            @endif
            @else
            <div class="text-center py-5">
                <i class="fas fa-file-excel fa-3x text-muted mb-3"></i>
                <p class="text-muted">لا توجد بيانات مرجعية. قم برفع ملف للمقارنة.</p>
            </div>
            @endif
        </div>

        <div id="detailPanel" class="detail-panel">
            <div class="detail-header">
                <h5 id="detailTitle"></h5>
                <button class="back-btn" onclick="showOverview()">
                    <i class="fas fa-arrow-right me-1"></i> رجوع
                </button>
            </div>
            <div class="data-preview" id="detailContent"></div>
        </div>
    </div>

    <div class="sidebar-panel">
        <div class="sidebar-title">
            <i class="fas fa-chart-bar me-2"></i>نتائج المطابقة
        </div>

        <div class="upload-secondary" onclick="document.getElementById('secondaryFile').click()">
            <i class="fas fa-file-upload"></i>
            <div>رفع بيانات ثانوية للمقارنة</div>
            <small style="opacity:0.6">xlsx, xls, csv</small>
        </div>
        <input type="file" id="secondaryFile" accept=".xlsx,.xls,.csv" style="display:none">

        @if($results)
        <div class="category-header">التطابق الكامل</div>
        <div class="result-item" data-type="one_to_one">
            <span class="result-label">{{ $results['one_to_one']['label'] }}</span>
            <span class="result-count {{ $results['one_to_one']['count'] == 0 ? 'zero' : '' }}">
                {{ $results['one_to_one']['count'] }}
            </span>
        </div>

        <div class="category-header">التطابق الجزئي</div>
        @foreach(['two_total', 'three_total', 'four_total', 'five_total', 'six_total'] as $key)
        <div class="result-item" data-type="{{ $key }}">
            <span class="result-label">{{ $results[$key]['label'] }}</span>
            <span class="result-count {{ $results[$key]['count'] == 0 ? 'zero' : '' }}">
                {{ $results[$key]['count'] }}
            </span>
        </div>
        @endforeach

        <div class="category-header">الاختلافات</div>
        <div class="result-item" data-type="letter_difference">
            <span class="result-label">{{ $results['letter_difference']['label'] }}</span>
            <span class="result-count {{ $results['letter_difference']['count'] == 0 ? 'zero' : '' }}">
                {{ $results['letter_difference']['count'] }}
            </span>
        </div>
        <div class="result-item" data-type="total_variations">
            <span class="result-label">{{ $results['total_variations']['label'] }}</span>
            <span class="result-count {{ $results['total_variations']['count'] == 0 ? 'zero' : '' }}">
                {{ $results['total_variations']['count'] }}
            </span>
        </div>
        <div class="result-item" data-type="last">
            <span class="result-label">{{ $results['last']['label'] }}</span>
            <span class="result-count {{ $results['last']['count'] == 0 ? 'zero' : '' }}">
                {{ $results['last']['count'] }}
            </span>
        </div>

        <div class="category-header">مع اختلاف أخير</div>
        @foreach(['single_last_diff', 'double_last_diff', 'triple_last_diff', 'quadruple_last_diff', 'centruple_last_diff', 'sextuple_last_diff'] as $key)
        <div class="result-item" data-type="{{ $key }}">
            <span class="result-label">{{ $results[$key]['label'] }}</span>
            <span class="result-count {{ $results[$key]['count'] == 0 ? 'zero' : '' }}">
                {{ $results[$key]['count'] }}
            </span>
        </div>
        @endforeach

        <div class="category-header">الصافي</div>
        <div class="result-item" data-type="net">
            <span class="result-label">{{ $results['net']['label'] }}</span>
            <span class="result-count {{ $results['net']['count'] == 0 ? 'zero' : '' }}">
                {{ $results['net']['count'] }}
            </span>
        </div>
        @else
        <div class="empty-results">
            <i class="fas fa-chart-pie"></i>
            <div>لا توجد نتائج</div>
            <small>قم برفع ملف للمقارنة</small>
        </div>
        @endif
    </div>
</div>

<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner"></div>
    <div style="color:#fff">جاري تحليل البيانات...</div>
</div>

<!-- Add Column Modal -->
<div class="modal-overlay" id="addColumnModal">
    <div class="modal-box">
        <h4 class="mb-4"><i class="fas fa-columns me-2 text-primary"></i>إضافة عمود جديد للمرجعية</h4>
        <div class="mb-3">
            <label class="form-label">اسم العمود</label>
            <input type="text" class="form-control" id="newColumnName" placeholder="مثلاً: ملاحظات">
        </div>
        <div class="d-flex gap-2 justify-content-end">
            <button class="btn btn-secondary" onclick="closeModal('addColumnModal')">إلغاء</button>
            <button class="btn btn-primary" onclick="addColumn()">إضافة</button>
        </div>
    </div>
</div>

<script>
function switchMainTab(tab) {
    document.getElementById('primaryTab').classList.toggle('active', tab === 'primary');
    document.getElementById('referenceTab').classList.toggle('active', tab === 'reference');
    document.getElementById('primaryContent').style.display = tab === 'primary' ? 'block' : 'none';
    document.getElementById('referenceContent').style.display = tab === 'reference' ? 'block' : 'none';
    
    if (tab === 'reference') {
        document.getElementById('detailPanel').classList.remove('active');
        document.querySelectorAll('.result-item').forEach(i => i.classList.remove('active'));
    }
}

function showModal(id) {
    document.getElementById(id).classList.add('active');
}

function closeModal(id) {
    document.getElementById(id).classList.remove('active');
}

function showAddColumnModal() {
    showModal('addColumnModal');
}

function addColumn() {
    const name = document.getElementById('newColumnName').value.trim();
    if (!name) return;

    fetch('{{ route("data.differentiation.add-column") }}', {
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

function integrateAll() {
    if (!confirm('هل تريد دمج جميع البيانات المرجعية في الجدول الرئيسي؟')) return;

    fetch('{{ route("data.differentiation.integrate-all") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(r => r.json())
    .then(data => {
        alert(data.message);
        if (data.success) location.reload();
    });
}

function integrateSelected(indices) {
    if (!confirm('هل تريد دمج السجلات المختارة؟')) return;

    fetch('{{ route("data.differentiation.integrate-selected") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ indices })
    })
    .then(r => r.json())
    .then(data => {
        alert(data.message);
        if (data.success) location.reload();
    });
}

document.getElementById('secondaryFile').addEventListener('change', function(e) {
    if (!e.target.files.length) return;
    
    document.getElementById('loadingOverlay').classList.add('active');
    
    const formData = new FormData();
    formData.append('excel_file', e.target.files[0]);
    formData.append('_token', '{{ csrf_token() }}');
    
    fetch('{{ route("data.differentiation.analyze") }}', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('loadingOverlay').classList.remove('active');
        if (data.success) {
            location.reload();
        } else {
            alert('حدث خطأ في التحليل');
        }
    })
    .catch(err => {
        document.getElementById('loadingOverlay').classList.remove('active');
        alert('خطأ في الاتصال');
    });
});

// Click on result items to view details
document.querySelectorAll('.result-item').forEach(item => {
    item.addEventListener('click', function() {
        const type = this.dataset.type;
        const count = parseInt(this.querySelector('.result-count').textContent);
        
        if (count === 0) return;

        document.querySelectorAll('.result-item').forEach(i => i.classList.remove('active'));
        this.classList.add('active');
        
        loadDetail(type, this.querySelector('.result-label').textContent);
    });
});

function loadDetail(type, label) {
    fetch('{{ route("data.differentiation.detail") }}?type=' + type)
    .then(r => r.json())
    .then(data => {
        displayDetail(data, label);
    });
}

function displayDetail(data, label) {
    document.getElementById('detailTitle').textContent = label + ' (' + data.details.length + ')';
    
    let html = '';
    
    // Add Category Integrate Button
    const secondaryIndices = data.details
        .filter(d => d.secondary_index !== null)
        .map(d => d.secondary_index);
    
    if (secondaryIndices.length > 0) {
        html += `
            <div class="action-bar">
                <button class="btn-action primary" onclick="integrateSelected([${secondaryIndices.join(',')}])">
                    <i class="fas fa-file-import"></i> دمج هذه الفئة (${secondaryIndices.length} سجل)
                </button>
            </div>
        `;
    }

    html += '<table>';
    
    // Headers
    html += '<thead><tr><th>الرقم</th>';
    if (data.primary_columns.length) {
        data.primary_columns.forEach(col => html += '<th>' + col + '</th>');
    }
    if (data.secondary_columns.length && data.type !== 'net') {
        html += '<th style="background:#ef4444">المصدر</th>';
        data.secondary_columns.forEach(col => html += '<th style="background:#b91c1c">' + col + '</th>');
    }
    html += '</tr></thead><tbody>';
    
    // Data rows
    data.details.forEach((detail, i) => {
        html += '<tr><td>' + (i + 1) + '</td>';
        
        if (detail.primary) {
            detail.primary.forEach(cell => html += '<td>' + (cell || '-') + '</td>');
        } else if (data.primary_columns.length) {
            data.primary_columns.forEach(() => html += '<td>-</td>');
        }
        
        if (detail.secondary && data.type !== 'net') {
            html += '<td style="background:#fee2e2">ثانوي</td>';
            detail.secondary.forEach(cell => html += '<td style="background:#fef2f2">' + (cell || '-') + '</td>');
        }
        
        html += '</tr>';
    });
    
    html += '</tbody></table>';
    
    document.getElementById('detailContent').innerHTML = html;
    document.getElementById('overviewPanel').style.display = 'none';
    document.getElementById('detailPanel').classList.add('active');
}

function showOverview() {
    document.getElementById('detailPanel').classList.remove('active');
    document.getElementById('overviewPanel').style.display = 'block';
    document.querySelectorAll('.result-item').forEach(i => i.classList.remove('active'));
}
</script>
@endsection
