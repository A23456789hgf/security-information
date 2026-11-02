@extends('layouts.app')

@section('title', 'استيراد بلاغات أمنية')

@section('content')
<div class="space-y-6" dir="rtl">
    <!-- Header Banner -->
    <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white flex items-center gap-3">
                <span class="w-10 h-10 rounded-2xl bg-brand-50 dark:bg-brand-900/30 text-brand-600 dark:text-brand-400 flex items-center justify-center text-lg shadow-sm">
                    <i class="fas fa-file-import"></i>
                </span>
                استيراد بلاغات من Excel
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm">رفع ملفات Excel ومطابقة البيانات مع سجلات البلاغات الأمنية.</p>
        </div>
        <div>
            <a href="{{ route('system.security-reports.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-2xl transition-all inline-flex items-center gap-2 text-sm">
                <i class="fas fa-arrow-right"></i>
                <span>العودة لقائمة البلاغات</span>
            </a>
        </div>
    </div>

    <!-- Upload Step Card -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm p-8 text-center" id="uploadStep">
        <div class="border-2 border-dashed border-brand-300 dark:border-brand-700/60 rounded-3xl p-12 cursor-pointer hover:bg-brand-50/50 dark:hover:bg-brand-900/10 transition-all max-w-2xl mx-auto" onclick="document.getElementById('excelFile').click()">
            <div class="w-16 h-16 rounded-2xl bg-brand-50 dark:bg-brand-900/30 text-brand-600 dark:text-brand-400 flex items-center justify-center text-3xl mx-auto mb-4">
                <i class="fas fa-cloud-upload-btnalt"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">اسحب ملف Excel هنا أو انقر للاختيار</h3>
            <p class="text-slate-500 dark:text-slate-400 text-sm">يدعم ملفات XLSX, XLS, CSV</p>
            <input type="file" id="excelFile" accept=".xlsx,.xls,.csv" class="hidden">
        </div>
        <div id="uploadProgress" class="mt-6 hidden">
            <div class="w-10 h-10 border-4 border-brand-200 border-t-brand-600 rounded-full animate-spin mx-auto mb-2"></div>
            <p class="text-sm font-bold text-brand-600 dark:text-brand-400">جاري تحليل ومعالجة الملف...</p>
        </div>
    </div>

    <!-- Mapping Step Card -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden hidden" id="mappingStep">
        <div class="bg-slate-50/80 dark:bg-slate-800/50 p-6 border-b border-slate-100 dark:border-slate-800">
            <h3 class="font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2 text-base">
                <i class="fas fa-columns text-brand-500"></i> مطابقة الأعمدة والبيانات
            </h3>
        </div>
        <div class="p-6 md:p-8">
            <form id="mappingForm">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    <!-- Column Selects -->
                    <div class="lg:col-span-8 bg-slate-50/80 dark:bg-slate-800/40 p-6 rounded-3xl border border-slate-100 dark:border-slate-800">
                        <h4 class="font-bold text-slate-900 dark:text-white mb-4 text-sm">حدد الأعمدة المقابلة للحقول المطلوبة</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Core Fields -->
                            <div class="space-y-4">
                                <h5 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b pb-2 dark:border-slate-700">البيانات الأساسية</h5>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">الاسم الكامل <span class="text-rose-500">*</span></label>
                                    <select name="mapping[full_name]" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs focus:ring-2 focus:ring-brand-500/20 header-select text-slate-800 dark:text-slate-200" data-field="full_name" required>
                                        <option value="">-- اختر العمود --</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">موضوع البلاغ <span class="text-rose-500">*</span></label>
                                    <select name="mapping[report_name]" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs focus:ring-2 focus:ring-brand-500/20 header-select text-slate-800 dark:text-slate-200" data-field="report_name" required>
                                        <option value="">-- اختر العمود --</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">تاريخ البلاغ</label>
                                    <select name="mapping[report_date]" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs focus:ring-2 focus:ring-brand-500/20 header-select text-slate-800 dark:text-slate-200" data-field="report_date">
                                        <option value="">-- اختر العمود --</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">الرقم العسكري</label>
                                    <select name="mapping[military_number]" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs focus:ring-2 focus:ring-brand-500/20 header-select text-slate-800 dark:text-slate-200" data-field="military_number">
                                        <option value="">-- اختر العمود --</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Person Details -->
                            <div class="space-y-4">
                                <h5 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b pb-2 dark:border-slate-700">بيانات الشخص</h5>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">الرقم الوطني / البطاقة</label>
                                    <select name="mapping[id_number]" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs focus:ring-2 focus:ring-brand-500/20 header-select text-slate-800 dark:text-slate-200" data-field="id_number">
                                        <option value="">-- اختر العمود --</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">الاسم الجهادي</label>
                                    <select name="mapping[jihadi_name]" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs focus:ring-2 focus:ring-brand-500/20 header-select text-slate-800 dark:text-slate-200" data-field="jihadi_name">
                                        <option value="">-- اختر العمود --</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">الهاتف</label>
                                    <select name="mapping[phone]" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs focus:ring-2 focus:ring-brand-500/20 header-select text-slate-800 dark:text-slate-200" data-field="phone">
                                        <option value="">-- اختر العمود --</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">الحالة الاجتماعية</label>
                                    <select name="mapping[marital_status]" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs focus:ring-2 focus:ring-brand-500/20 header-select text-slate-800 dark:text-slate-200" data-field="marital_status">
                                        <option value="">-- اختر العمود --</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Organizational Details -->
                            <div class="space-y-4">
                                <h5 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b pb-2 dark:border-slate-700">الوحدة والعمل</h5>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">الوحدة</label>
                                    <select name="mapping[unit]" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs focus:ring-2 focus:ring-brand-500/20 header-select text-slate-800 dark:text-slate-200" data-field="unit">
                                        <option value="">-- اختر العمود --</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">الحالة الوظيفية</label>
                                    <select name="mapping[job_status]" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs focus:ring-2 focus:ring-brand-500/20 header-select text-slate-800 dark:text-slate-200" data-field="job_status">
                                        <option value="">-- اختر العمود --</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">حالة القبض</label>
                                    <select name="mapping[arrest_status]" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs focus:ring-2 focus:ring-brand-500/20 header-select text-slate-800 dark:text-slate-200" data-field="arrest_status">
                                        <option value="">-- اختر العمود --</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar Preview -->
                    <div class="lg:col-span-4 bg-slate-900 text-white rounded-3xl p-6 flex flex-col justify-between space-y-6">
                        <div>
                            <h4 class="font-bold text-base mb-4 text-slate-100">ملخص الملف</h4>
                            <div class="flex items-center justify-between p-3 bg-slate-800/60 rounded-2xl border border-slate-700 mb-4">
                                <span class="text-xs text-slate-400">إجمالي السجلات:</span>
                                <span class="font-black text-lg text-brand-400" id="totalRows">0</span>
                            </div>
                            <div>
                                <h5 class="text-xs font-bold text-slate-400 mb-2">معاينة أول 5 سجلات:</h5>
                                <div class="overflow-x-auto rounded-xl border border-slate-700">
                                    <table class="w-full text-right text-xs text-slate-300" id="previewTable">
                                        <!-- Populated via JS -->
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-3 pt-4 border-t border-slate-800">
                            <button type="button" class="w-full py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-2xl shadow-lg shadow-brand-500/25 transition-all flex items-center justify-center gap-2 text-sm" onclick="submitImport()">
                                <i class="fas fa-check-circle"></i>
                                <span>تنفيذ الاستيراد النهائي</span>
                            </button>
                            <button type="button" class="w-full py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold rounded-2xl transition-all text-xs" onclick="resetImport()">بدء من جديد</button>
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


