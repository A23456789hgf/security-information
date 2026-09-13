@extends('layouts.app')

@section('title', 'إدخال سريع للبيانات')
@section('page_title', 'السجلات الأساسية')

@section('content')
<div class="space-y-6" dir="rtl">
    {{-- Breadcrumbs --}}
    <nav class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-2 print:hidden">
        <a href="{{ route('dashboard') }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">الرئيسية</a>
        <span>/</span>
        <a href="{{ route('system.personal-data.index') }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">إدارة البيانات الشخصية</a>
        <span>/</span>
        <span class="text-slate-900 dark:text-white font-semibold">معالج الإدخال السريع</span>
    </nav>

    {{-- Header with Speed Monitor --}}
    <div class="flex flex-col sm:flex-flex flex-wrap gap-4 justify-between items-start sm:items-center gap-4 mb-2">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold shadow-sm shrink-0">
                <i class="fas fa-bolt text-xl"></i>
            </div>
            <div>
                <h1 class="font-display font-bold text-2xl text-slate-900 dark:text-white m-0">معالج الإدخال السريع (High-Speed Entry)</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">أدخل كميات من السجلات يدوياً بسرعة فائقة. تنقل بين الحقول باستخدام زر <kbd class="px-1.5 py-0.5 rounded bg-slate-200 dark:bg-slate-700 text-xs font-mono font-bold text-slate-700 dark:text-slate-200">Tab</kbd>.</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="flex items-center gap-3 px-4 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm">
                <div class="w-8 h-8 rounded-xl bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center font-bold">
                    <i class="fas fa-tachometer-alt"></i>
                </div>
                <div class="leading-tight">
                    <span class="text-base font-bold text-slate-900 dark:text-white font-mono" id="liveRPM">0</span>
                    <span class="block text-[10px] text-slate-400 font-bold">سجل/دقيقة</span>
                </div>
            </div>
            <a href="{{ route('system.personal-data.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-sm rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                <i class="fas fa-arrow-right"></i>
                <span>عودة</span>
            </a>
        </div>
    </div>

    {{-- Notification Area --}}
    <div id="resultsCol" class="hidden">
        <div class="bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-800 dark:text-emerald-300 px-4 py-3 rounded-2xl flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <i class="fas fa-check-circle text-emerald-500 text-base"></i>
                <div>
                    <strong class="block font-bold">تمت العملية بنجاح!</strong>
                    <span class="text-xs" id="saveSummary"></span>
                </div>
            </div>
        </div>
    </div>

    {{-- Fast Entry table Container --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden transition-colors">
        <div class="overflow-x-auto" style="max-height: 540px;">
            <table class="w-full text-xs text-right border-collapse" id="fastEntryTable">
                <thead class="bg-slate-50 dark:bg-slate-800/80 border-b border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300 sticky top-0 z-10">
                    <tr>
                        <th class="py-3 px-3 w-12 text-center font-bold">#</th>
                        <th class="py-3 px-3 font-bold min-w-[140px]">الاسم الكامل <span class="text-red-500">*</span></th>
                        <th class="py-3 px-3 font-bold min-w-[110px]">الاسم الجهادي</th>
                        <th class="py-3 px-3 font-bold min-w-[120px]">رقم الهوية <span class="text-red-500">*</span></th>
                        <th class="py-3 px-3 font-bold min-w-[110px]">الرقم العسكري</th>
                        <th class="py-3 px-3 font-bold min-w-[120px]">تاريخ الميلاد</th>
                        <th class="py-3 px-3 font-bold min-w-[110px]">المحافظة</th>
                        <th class="py-3 px-3 font-bold min-w-[100px]">الرتبة</th>
                        <th class="py-3 px-3 font-bold min-w-[100px]">الرقم الجهادي</th>
                        <th class="py-3 px-3 font-bold min-w-[120px]">الدائرة العسكرية</th>
                        <th class="py-3 px-3 font-bold min-w-[110px]">الشعبة</th>
                        <th class="py-3 px-3 font-bold min-w-[100px]">الوحدة</th>
                        <th class="py-3 px-3 font-bold min-w-[100px]">الوظيفة</th>
                        <th class="py-3 px-3 font-bold min-w-[100px]">مكان العمل</th>
                        <th class="py-3 px-3 font-bold min-w-[100px]">التخصص</th>
                        <th class="py-3 px-3 font-bold min-w-[110px]">الهاتف 1</th>
                        <th class="py-3 px-3 font-bold min-w-[110px]">الحالة المالية</th>
                        <th class="py-3 px-3 w-10 text-center"></th>
                    </tr>
                </thead>
                <tbody id="entryBody" class="divide-y divide-slate-100 dark:divide-slate-800">
                    <!-- Dynamic rows -->
                </tbody>
            </table>
        </div>

        {{-- table Actions Footer --}}
        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <button type="button" id="btnAddRow"
                    class="px-4 py-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 font-bold text-xs shadow-sm transition-colors flex items-center gap-2">
                    <i class="fas fa-plus text-brand-600"></i>
                    <span>إضافة صف (F2)</span>
                </button>
                <span class="text-xs text-slate-500 dark:text-slate-400">إجمالي الصفوف: <strong id="rowCount" class="font-bold text-slate-800 dark:text-slate-200 dark:text-slate-200">0</strong></span>
            </div>
            <div>
                <button type="button" id="btnSaveAll"
                    class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm shadow-sm shadow-brand-500/30 transition-all flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    <span>حفظ كافة السجلات (Ctrl+S)</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    #fastEntryTable input, #fastEntryTable select {
        border: 1px solid transparent;
        background: transparent;
        width: 100%;
        padding: 8px 10px;
        font-size: 0.8rem;
        outline: none;
        border-radius: 6px;
        transition: all 0.15s ease;
    }
    #fastEntryTable input:focus, #fastEntryTable select:focus {
        background: #ffffff;
        border-color: #4f46e5;
        box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.15);
    }
    .dark #fastEntryTable input:focus, .dark #fastEntryTable select:focus {
        background: #0f172a;
        border-color: #6366f1;
    }
    #fastEntryTable select option {
        background: #ffffff;
        color: #0f172a;
    }
    .dark #fastEntryTable select option {
        background: #0f172a;
        color: #f8fafc;
    }
    .flex flex-wrap gap-4-index {
        font-family: monospace;
        font-weight: bold;
        color: #94a3b8;
        text-align: center;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    let rowId = 0;
    let entryCount = 0;
    let startTime = null;

    const governorates = @json($governorates);
    const ranks = @json($ranks);
    const financialStatuses = @json($financialStatuses);
    const militaryDistricts = @json($militaryDistricts);
    const divisions = @json($divisions);

    function addRow() {
        rowId++;
        let html = `<tr id="flex flex-wrap gap-4-${rowId}" class="entry-flex flex-wrap gap-4 hover:bg-slate-50/50 dark:hover:bg-slate-800/25 transition-colors">
            <td class="flex flex-wrap gap-4-index px-2">${rowId}</td>
            <td class="p-1"><input type="text" class="data-name text-slate-800 dark:text-slate-200 dark:text-slate-100 font-semibold" placeholder="الاسم الكامل" required></td>
            <td class="p-1"><input type="text" class="data-jihadi text-slate-800 dark:text-slate-200 dark:text-slate-100" placeholder="الاسم الجهادي"></td>
            <td class="p-1"><input type="text" class="data-id text-slate-800 dark:text-slate-200 dark:text-slate-100 font-mono" placeholder="رقم الهوية" required></td>
            <td class="p-1"><input type="text" class="data-military text-slate-800 dark:text-slate-200 dark:text-slate-100 font-mono" placeholder="الرقم العسكري"></td>
            <td class="p-1"><input type="date" class="data-dob text-slate-800 dark:text-slate-200 dark:text-slate-100"></td>
            <td class="p-1">
                <select class="data-gov text-slate-800 dark:text-slate-200 dark:text-slate-100">
                    <option value="">اختر</option>
                    ${governorates.map(g => `<option value="${g.id}">${g.name}</option>`).join('')}
                </select>
            </td>
            <td class="p-1">
                <select class="data-rank text-slate-800 dark:text-slate-200 dark:text-slate-100">
                    <option value="">اختر</option>
                    ${ranks.map(r => `<option value="${r.id}">${r.rank}</option>`).join('')}
                </select>
            </td>
            <td class="p-1"><input type="text" class="data-jihadist text-slate-800 dark:text-slate-200 dark:text-slate-100 font-mono" placeholder="الرقم الجهادي"></td>
            <td class="p-1">
                <select class="data-district text-slate-800 dark:text-slate-200 dark:text-slate-100">
                    <option value="">اختر</option>
                    ${militaryDistricts.map(d => `<option value="${d.id}">${d.name}</option>`).join('')}
                </select>
            </td>
            <td class="p-1">
                <select class="data-division text-slate-800 dark:text-slate-200 dark:text-slate-100">
                    <option value="">اختر</option>
                    ${divisions.map(dv => `<option value="${dv.id}">${dv.name}</option>`).join('')}
                </select>
            </td>
            <td class="p-1"><input type="text" class="data-unit text-slate-800 dark:text-slate-200 dark:text-slate-100" placeholder="الوحدة"></td>
            <td class="p-1"><input type="text" class="data-job text-slate-800 dark:text-slate-200 dark:text-slate-100" placeholder="الوظيفة"></td>
            <td class="p-1"><input type="text" class="data-workplace text-slate-800 dark:text-slate-200 dark:text-slate-100" placeholder="مكان العمل"></td>
            <td class="p-1"><input type="text" class="data-specialty text-slate-800 dark:text-slate-200 dark:text-slate-100" placeholder="التخصص"></td>
            <td class="p-1"><input type="text" class="data-phone text-slate-800 dark:text-slate-200 dark:text-slate-100 font-mono" placeholder="رقم الهاتف"></td>
            <td class="p-1">
                <select class="data-financial text-slate-800 dark:text-slate-200 dark:text-slate-100">
                    <option value="">اختر</option>
                    ${financialStatuses.map(f => `<option value="${f.id}">${f.name}</option>`).join('')}
                </select>
            </td>
            <td class="p-1 text-center">
                <button type="button" class="p-1.5 text-slate-400 hover:text-red-500 rounded-2xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" onclick="removeRow(${rowId})">
                    <i class="fas fa-times"></i>
                </button>
            </td>
        </tr>`;
        $('#entryBody').append(html);
        updateCounters();
        $(`#flex flex-wrap gap-4-${rowId} .data-name`).focus();
    }

    window.removeRow = function(id) {
        $(`#flex flex-wrap gap-4-${id}`).remove();
        updateCounters();
    }

    function updateCounters() {
        $('#rowCount').text($('.entry-flex flex-wrap gap-4').length);
    }

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

    for(let i=0; i<5; i++) addRow();

    $('#btnAddRow').click(addRow);

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
        $('.entry-flex flex-wrap gap-4').each(function() {
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

