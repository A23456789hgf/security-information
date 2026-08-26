@extends('layouts.app')

@section('title', 'إدخال سريع للبيانات')

@section('content')
<div class="container-fluid mt-4" dir="rtl">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold text-primary">
                <i class="fas fa-bolt me-2"></i>معالج الإدخال السريع (High-Speed Entry)
            </h2>
            <p class="text-muted">استخدم هذا الوضع لإدخال البيانات يدوياً بسرعة فائقة. تنقل بين الحقول باستخدام الزر <kbd>Tab</kbd>.</p>
        </div>
        <div class="col-md-4 text-end">
            <div id="speedMonitor" class="card border-0 shadow-sm rounded-4 d-inline-block px-4 py-3 bg-white">
                <div class="d-flex align-items-center">
                    <div class="me-3 p-2 bg-soft-primary rounded-circle">
                        <i class="fas fa-tachometer-alt fa-2x text-primary"></i>
                    </div>
                    <div>
                        <div class="h3 fw-bold mb-0" id="liveRPM">0</div>
                        <small class="text-muted">سجل/دقيقة (RPM)</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 500px;">
                <table class="table table-bordered table-hover mb-0" id="fastEntryTable">
                    <thead class="bg-light sticky-top" style="z-index: 10;">
                        <tr>
                            <th width="50">#</th>
                            <th>الاسم الكامل <span class="text-danger">*</span></th>
                            <th>الاسم الجهادي</th>
                            <th>رقم الهوية <span class="text-danger">*</span></th>
                            <th>الرقم العسكري</th>
                            <th>تاريخ الميلاد (G)</th>
                            <th>المحافظة</th>
                            <th>الرتبة</th>
                            <th>الرقم الجهادي</th>
                            <th>الدائرة العسكرية</th>
                            <th>الشعبة</th>
                            <th>الوحدة</th>
                            <th>الوظيفة</th>
                            <th>مكان العمل</th>
                            <th>التخصص</th>
                            <th>الهاتف 1</th>
                            <th>الحالة المالية</th>
                            <th width="50"></th>
                        </tr>
                    </thead>
                    <tbody id="entryBody">
                        <!-- Dynamic rows -->
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white p-3 border-top d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div class="d-flex align-items-center flex-wrap gap-2">
                <button class="btn btn-outline-primary rounded-pill px-3 sm:px-4 text-xs sm:text-sm" id="btnAddRow">
                    <i class="fas fa-plus me-1"></i>إضافة صف (F2)
                </button>
                <small class="text-muted ms-2">إجمالي الصفوف: <span id="rowCount" class="fw-bold">0</span></small>
            </div>
            <div>
                <button class="btn btn-success rounded-pill px-4 sm:px-5 py-2 shadow-sm text-xs sm:text-sm fw-bold w-100" id="btnSaveAll">
                    <i class="fas fa-save me-2"></i>حفظ كافة السجلات (Ctrl+S)
                </button>
            </div>
        </div>
    </div>

    <div id="resultsCol" class="mt-4" style="display: none;">
        <div class="alert alert-success rounded-4 border-0 shadow-sm d-flex align-items-center">
            <i class="fas fa-check-circle fa-2x me-3"></i>
            <div>
                <h5 class="alert-heading mb-1">تمت العملية!</h5>
                <p class="mb-0" id="saveSummary"></p>
            </div>
        </div>
    </div>
</div>

<style>
    .sticky-top { position: sticky; top: 0; }
    #fastEntryTable input, #fastEntryTable select {
        border: none;
        background: transparent;
        width: 100%;
        padding: 5px;
    }
    #fastEntryTable input:focus, #fastEntryTable select:focus {
        background: #eef2ff;
        outline: none;
    }
    .bg-soft-primary { background-color: rgba(13, 110, 253, 0.1); }
    .table td { padding: 0; vertical-align: middle; }
    .row-index { padding: 10px !important; text-align: center; background: #f8f9fa; font-weight: bold; }
</style>

@push('scripts')
<script>
$(document).ready(function() {
    let rowId = 0;
    let entryCount = 0;
    let startTime = null;

    // Data for dropdowns
    const governorates = @json($governorates);
    const ranks = @json($ranks);
    const financialStatuses = @json($financialStatuses);
    const militaryDistricts = @json($militaryDistricts);
    const divisions = @json($divisions);

    function addRow() {
        rowId++;
        let html = `<tr id="row-${rowId}" class="entry-row">
            <td class="row-index">${rowId}</td>
            <td><input type="text" class="data-name" placeholder="الاسم الكامل" required></td>
            <td><input type="text" class="data-jihadi" placeholder="الاسم الجهادي"></td>
            <td><input type="text" class="data-id" placeholder="رقم الهوية" required></td>
            <td><input type="text" class="data-military" placeholder="الرقم العسكري"></td>
            <td><input type="date" class="data-dob"></td>
            <td>
                <select class="data-gov">
                    <option value="">اختر</option>
                    ${governorates.map(g => `<option value="${g.id}">${g.name}</option>`).join('')}
                </select>
            </td>
            <td>
                <select class="data-rank">
                    <option value="">اختر</option>
                    ${ranks.map(r => `<option value="${r.id}">${r.rank}</option>`).join('')}
                </select>
            </td>
            <td><input type="text" class="data-jihadist" placeholder="الرقم الجهادي"></td>
            <td>
                <select class="data-district">
                    <option value="">اختر</option>
                    ${militaryDistricts.map(d => `<option value="${d.id}">${d.name}</option>`).join('')}
                </select>
            </td>
            <td>
                <select class="data-division">
                    <option value="">اختر</option>
                    ${divisions.map(dv => `<option value="${dv.id}">${dv.name}</option>`).join('')}
                </select>
            </td>
            <td><input type="text" class="data-unit" placeholder="الوحدة"></td>
            <td><input type="text" class="data-job" placeholder="الوظيفة"></td>
            <td><input type="text" class="data-workplace" placeholder="مكان العمل"></td>
            <td><input type="text" class="data-specialty" placeholder="التخصص"></td>
            <td><input type="text" class="data-phone" placeholder="رقم الهاتف"></td>
            <td>
                <select class="data-financial">
                    <option value="">اختر</option>
                    ${financialStatuses.map(f => `<option value="${f.id}">${f.name}</option>`).join('')}
                </select>
            </td>
            <td class="text-center">
                <button class="btn btn-link text-danger p-0" onclick="removeRow(${rowId})">
                    <i class="fas fa-times"></i>
                </button>
            </td>
        </tr>`;
        $('#entryBody').append(html);
        updateCounters();
        
        // Auto-focus name field of new row
        $(`#row-${rowId} .data-name`).focus();
    }

    window.removeRow = function(id) {
        $(`#row-${id}`).remove();
        updateCounters();
    }

    function updateCounters() {
        $('#rowCount').text($('.entry-row').length);
    }

    // Speed Calculation
    $(document).on('input', '.data-name', function() {
        if (!startTime) startTime = Date.now();
    });

    setInterval(() => {
        if (startTime && entryCount > 0) {
            let elapsedMin = (Date.now() - startTime) / 60000;
            let rpm = Math.round(entryCount / elapsedMin);
            $('#liveRPM').text(rpm);
        }
    }, 2000);

    // Initial rows
    for(let i=0; i<5; i++) addRow();

    $('#btnAddRow').click(addRow);

    // Keyboard shortcuts
    $(document).keydown(function(e) {
        if (e.key === "F2") {
            e.preventDefault();
            addRow();
        }
        if ((e.ctrlKey || e.metaKey) && e.key === "s") {
            e.preventDefault();
            $('#btnSaveAll').click();
        }
    });

    $('#btnSaveAll').click(function() {
        let rowsData = [];
        $('.entry-row').each(function() {
            let name = $(this).find('.data-name').val();
            let idNum = $(this).find('.data-id').val();
            
            if (name && idNum) {
                rowsData.push({
                    name: name,
                    jihadi_name: $(this).find('.data-jihadi').val(),
                    national_id_number: idNum,
                    military_number: $(this).find('.data-military').val(),
                    date_of_birth_gregorian: $(this).find('.data-dob').val(),
                    governorate_id: $(this).find('.data-gov').val(),
                    rank_id: $(this).find('.data-rank').val(),
                    jihadist_number: $(this).find('.data-jihadist').val(),
                    military_district_id: $(this).find('.data-district').val(),
                    division_id: $(this).find('.data-division').val(),
                    unit: $(this).find('.data-unit').val(),
                    job: $(this).find('.data-job').val(),
                    workplace: $(this).find('.data-workplace').val(),
                    job_specialty: $(this).find('.data-specialty').val(),
                    phone_1: $(this).find('.data-phone').val(),
                    financial_status_id: $(this).find('.data-financial').val(),
                });
            }
        });

        if (rowsData.length === 0) {
            return alert('يرجى ملء الاسم ورقم الهوية لواحد على الأقل من السجلات');
        }

        $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>جاري الحفظ...');

        $.ajax({
            url: '{{ route("system.personal-data.bulk-store") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                rows: rowsData
            },
            success: function(res) {
                if (res.success) {
                    entryCount += res.results.success;
                    $('#btnSaveAll').prop('disabled', false).html('<i class="fas fa-save me-2"></i>حفظ كافة السجلات (Ctrl+S)');
                    $('#resultsCol').fadeIn();
                    $('#saveSummary').text(res.message);
                    
                    // Clear saved rows (keeping empty rows if needed or just clear all and add blank)
                    $('#entryBody').empty();
                    for(let i=0; i<5; i++) addRow();
                    
                    setTimeout(() => $('#resultsCol').fadeOut(), 5000);
                }
            }
        });
    });
});
</script>
@endpush
@endsection
