@extends('layouts.app')

@section('title', 'إدخال سريع للقرى / الحارات')

@section('content')
<div class="space-y-6" dir="rtl">
    <!-- Header Card -->
    <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col md:flex-flex flex-wrap gap-4 md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white flex items-center gap-3">
                <span class="w-10 h-10 rounded-2xl bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 flex items-center justify-center text-lg shadow-sm">
                    <i class="fas fa-bolt"></i>
                </span>
                معالج الإدخال السريع للقرى والحارات
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm">اختر المنطقة أولاً، ثم أدخل قائمة القرى دفعة واحدة.</p>
        </div>
        <div id="speedMonitor" class="bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/60 rounded-2xl px-5 py-3 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center text-lg">
                <i class="fas fa-tachometer-alt"></i>
            </div>
            <div>
                <div class="text-2xl font-black text-slate-900 dark:text-white leading-none" id="liveRPM">0</div>
                <small class="text-xs text-slate-400 font-semibold">سجل / دقيقة (RPM)</small>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Scope Section -->
        <div class="lg:col-span-4 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden flex flex-col justify-between">
            <div class="bg-slate-50/80 dark:bg-slate-800/50 p-4 border-b border-slate-100 dark:border-slate-800">
                <h3 class="font-bold text-slate-800 dark:text-slate-200 text-sm">1. تحديد النطاق الجغرافي</h3>
            </div>
            <div class="p-6 space-y-4 flex-1">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">المحافظة <span class="text-rose-500">*</span></label>
                    <select id="governorate_id" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-sm focus:ring-2 focus:ring-brand-500/20 transition-all text-slate-800 dark:text-slate-200">
                        <option value="">اختر المحافظة</option>
                        @foreach($governorates as $gov)
                            <option value="{{ $gov->id }}">{{ $gov->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">المديرية <span class="text-rose-500">*</span></label>
                    <select id="directorate_id" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-sm focus:ring-2 focus:ring-brand-500/20 transition-all text-slate-800 dark:text-slate-200 disabled:opacity-50" disabled>
                        <option value="">اختر المديرية</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">العزلة / المنطقة <span class="text-rose-500">*</span></label>
                    <select id="sub_area_id" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-sm focus:ring-2 focus:ring-brand-500/20 transition-all text-slate-800 dark:text-slate-200 disabled:opacity-50" disabled>
                        <option value="">اختر العزلة</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Village List Section -->
        <div class="lg:col-span-8 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden flex flex-col justify-between">
            <div class="bg-slate-50/80 dark:bg-slate-800/50 p-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <h3 class="font-bold text-slate-800 dark:text-slate-200 text-sm">2. قائمة القرى / الحارات</h3>
                <span class="px-3 py-1 bg-brand-50 dark:bg-brand-900/30 text-brand-700 dark:text-brand-300 text-xs font-bold rounded-full font-mono" id="lineCountBadge">0 سجل</span>
            </div>
            <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                <div>
                    <label class="block text-xs text-slate-400 mb-2">أدخل اسم قرية واحدة في كل سطر. سيتم تجاهل الأسطر الفارغة تلقائياً.</label>
                    <textarea id="villageList" class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-2 focus:ring-brand-500/20 transition-all text-sm font-sans text-slate-800 dark:text-slate-200" rows="10" 
                        placeholder="قرية 1&#10;قرية 2&#10;حارة 3..." style="resize: none; line-height: 1.8;"></textarea>
                </div>
                
                <div class="flex justify-end pt-2">
                    <button class="px-8 py-3.5 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-2xl shadow-lg shadow-brand-500/25 transition-all flex items-center gap-2 text-sm" id="btnSaveAll">
                        <i class="fas fa-save"></i>
                        <span>حفظ كافة القرى الآن</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Results Toast/Container -->
    <div id="resultsCol" class="hidden">
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm p-6" id="saveResultContent">
            <!-- Dynamic results -->
        </div>
    </div>
</div>

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

        let  = $(this);
        .prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>جاري الحفظ...');

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
                .prop('disabled', false).html('<i class="fas fa-save me-2"></i>حفظ كافة القرى الآن');
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
                .prop('disabled', false).html('<i class="fas fa-save me-2"></i>حفظ كافة القرى الآن');
                alert('حدث خطأ أثناء الحفظ. يرجى التأكد من البيانات.');
            }
        });
    });
});
</script>
@endpush
@endsection


