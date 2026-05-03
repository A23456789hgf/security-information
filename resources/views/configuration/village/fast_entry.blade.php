@extends('layouts.app')

@section('title', 'إدخال سريع للقرى / الحارات')

@section('content')
<div class="container-fluid mt-4" dir="rtl">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold text-primary">
                <i class="fas fa-bolt me-2"></i>معالج الإدخال السريع للقرى
            </h2>
            <p class="text-muted">اختر المنطقة أولاً، ثم أدخل قائمة القرى دفعة واحدة.</p>
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

    <div class="row">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="fw-bold mb-0">1. تحديد النطاق الجغرافي</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">المحافظة <span class="text-danger">*</span></label>
                        <select id="governorate_id" class="form-select rounded-pill">
                            <option value="">اختر المحافظة</option>
                            @foreach($governorates as $gov)
                                <option value="{{ $gov->id }}">{{ $gov->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">المديرية <span class="text-danger">*</span></label>
                        <select id="directorate_id" class="form-select rounded-pill" disabled>
                            <option value="">اختر المديرية</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">العزلة / المنطقة <span class="text-danger">*</span></label>
                        <select id="sub_area_id" class="form-select rounded-pill" disabled>
                            <option value="">اختر العزلة</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">2. قائمة القرى / الحارات</h5>
                    <span class="badge bg-soft-primary text-primary rounded-pill" id="lineCountBadge">0 سجل</span>
                </div>
                <div class="card-body">
                    <label class="form-label text-muted small mb-2">أدخل اسم قرية واحدة في كل سطر. سيتم تجاهل الأسطر الفارغة.</label>
                    <textarea id="villageList" class="form-control rounded-4 font-monospace" rows="12" 
                        placeholder="قرية 1&#10;قرية 2&#10;حارة 3..." style="resize: none; font-size: 1.1rem; line-height: 1.6;"></textarea>
                    
                    <div class="mt-4 text-end">
                        <button class="btn btn-success btn-lg rounded-pill px-5 shadow-sm" id="btnSaveAll">
                            <i class="fas fa-save me-2"></i>حفظ كافة القرى الآن
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="resultsCol" class="mt-4" style="display: none;">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body" id="saveResultContent">
                <!-- Dynamic results -->
            </div>
        </div>
    </div>
</div>

<style>
    .bg-soft-primary { background-color: rgba(13, 110, 253, 0.1); }
    .bg-soft-success { background-color: rgba(25, 135, 84, 0.1); }
    .bg-soft-danger { background-color: rgba(220, 53, 69, 0.1); }
    kbd { background-color: #f8f9fa; color: #333; border: 1px solid #dee2e6; box-shadow: 0 2px 0 rgba(0,0,0,0.1); }
</style>

@push('scripts')
<script>
$(document).ready(function() {
    let entryCountTotal = 0;
    let startTime = null;

    // Dependent Dropdowns
    $('#governorate_id').change(function() {
        let id = $(this).val();
        $('#directorate_id').prop('disabled', !id).html('<option value="">اختر المديرية</option>');
        $('#sub_area_id').prop('disabled', true).html('<option value="">اختر العزلة</option>');
        if (id) {
            $.get('{{ url("get-directorates") }}/' + id, function(data) {
                Object.entries(data).forEach(([val, text]) => {
                    $('#directorate_id').append(`<option value="${val}">${text}</option>`);
                });
            });
        }
    });

    $('#directorate_id').change(function() {
        let id = $(this).val();
        $('#sub_area_id').prop('disabled', !id).html('<option value="">اختر العزلة</option>');
        if (id) {
            $.get('{{ url("get-sub-areas") }}/' + id, function(data) {
                Object.entries(data).forEach(([val, text]) => {
                    $('#sub_area_id').append(`<option value="${val}">${text}</option>`);
                });
            });
        }
    });

    // Line counter
    $('#villageList').on('input', function() {
        if (!startTime) startTime = Date.now();
        let lines = $(this).val().split('\n').filter(line => line.trim() !== '').length;
        $('#lineCountBadge').text(lines + ' سجل');
    });

    // Speed Tracking
    setInterval(() => {
        if (startTime && entryCountTotal > 0) {
            let elapsedMin = (Date.now() - startTime) / 60000;
            let rpm = Math.round(entryCountTotal / elapsedMin);
            $('#liveRPM').text(rpm);
        }
    }, 2000);

    $('#btnSaveAll').click(function() {
        let govId = $('#governorate_id').val();
        let dirId = $('#directorate_id').val();
        let subId = $('#sub_area_id').val();
        let villages = $('#villageList').val().split('\n').filter(v => v.trim() !== '');

        if (!govId || !dirId || !subId) return alert('يرجى اختيار المحافظة والمديرية والعزلة');
        if (villages.length === 0) return alert('يرجى إدخال اسم قرية واحد على الأقل');

        let btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>جاري الحفظ...');

        $.ajax({
            url: '{{ route("configuration.villages.bulk-store") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                governorate_id: govId,
                directorate_id: dirId,
                sub_area_id: subId,
                villages: villages
            },
            success: function(res) {
                btn.prop('disabled', false).html('<i class="fas fa-save me-2"></i>حفظ كافة القرى الآن');
                if (res.success) {
                    entryCountTotal += res.results.success;
                    $('#resultsCol').fadeIn();
                    
                    let html = `
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-info-circle fa-2x text-primary me-3"></i>
                            <h5 class="mb-0 fw-bold">${res.message}</h5>
                        </div>`;
                    
                    if (res.results.failed > 0) {
                        html += `
                            <div class="alert bg-soft-danger text-danger border-0">
                                <ul class="mb-0">
                                    ${res.results.errors.map(err => `<li>${err}</li>`).join('')}
                                </ul>
                            </div>`;
                    } else {
                        html += `<div class="alert bg-soft-success text-success border-0 mb-0">تم حفظ كافة القرى المدخلة بنجاح!</div>`;
                    }
                    
                    $('#saveResultContent').html(html);
                    $('#villageList').val('');
                    $('#lineCountBadge').text('0 سجل');
                    
                    setTimeout(() => $('#resultsCol').fadeOut(), 10000);
                }
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="fas fa-save me-2"></i>حفظ كافة القرى الآن');
                alert('حدث خطأ أثناء الحفظ. يرجى التأكد من البيانات.');
            }
        });
    });
});
</script>
@endpush
@endsection
