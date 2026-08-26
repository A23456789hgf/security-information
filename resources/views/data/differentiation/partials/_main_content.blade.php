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
            <div class="d-flex gap-2 align-items-center">
                <button class="btn-action" onclick="printActiveGroup()">
                    <i class="fas fa-print text-primary"></i> طباعة الفئة
                </button>
                <button class="btn-action" onclick="exportActiveGroup()">
                    <i class="fas fa-file-csv text-success"></i> تصدير الفئة (CSV)
                </button>
                <button class="back-btn" onclick="showOverview()" style="margin-right: 15px;">
                    <i class="fas fa-arrow-right me-1"></i> رجوع
                </button>
            </div>
        </div>
        <div class="data-preview" id="detailContent"></div>
    </div>
</div>
