@extends('layouts.app')

@section('title', 'استيراد ردود ميدانية')
@section('page_title', 'السجلات الأمنية')

@section('content')
<div class="space-y-6" dir="rtl">
    <!-- Header Banner -->
    <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white flex items-center gap-3">
                <span class="w-10 h-10 rounded-2xl bg-brand-50 dark:bg-brand-900/30 text-brand-600 dark:text-brand-400 flex items-center justify-center text-lg shadow-sm">
                    <i class="fas fa-file-import"></i>
                </span>
                استيراد ردود ميدانية من Excel
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm">رفع ملفات Excel/CSV ومطابقة الأعمدة لحفظ الردود الميدانية للأشخاص.</p>
        </div>
        <div>
            <a href="{{ route('system.field-responses.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-2xl transition-all inline-flex items-center gap-2 text-sm">
                <i class="fas fa-arrow-right"></i>
                <span>العودة للقائمة</span>
            </a>
        </div>
    </div>

    <!-- Upload Step Card -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm p-8 text-center" id="uploadStep">
        <div class="border-2 border-dashed border-brand-300 dark:border-brand-700/60 rounded-3xl p-12 cursor-pointer hover:bg-brand-50/50 dark:hover:bg-brand-900/10 transition-all max-w-2xl mx-auto" onclick="document.getElementById('excelFile').click()">
            <div class="w-16 h-16 rounded-2xl bg-brand-50 dark:bg-brand-900/30 text-brand-600 dark:text-brand-400 flex items-center justify-center text-3xl mx-auto mb-4">
                <i class="fas fa-cloud-upload-alt"></i>
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
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Person Identification -->
                            <div class="space-y-4">
                                <h5 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b pb-2 dark:border-slate-700">التعرف على الشخص المعني</h5>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">اسم الشخص المعني</label>
                                    <select name="mapping[person_name]" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs focus:ring-2 focus:ring-brand-500/20 header-select text-slate-800 dark:text-slate-200" data-field="person_name">
                                        <option value="">-- اختر العمود --</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">الرقم الوطني / الهوية</label>
                                    <select name="mapping[national_id_number]" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs focus:ring-2 focus:ring-brand-500/20 header-select text-slate-800 dark:text-slate-200" data-field="national_id_number">
                                        <option value="">-- اختر العمود --</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Field Response Details -->
                            <div class="space-y-4">
                                <h5 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b pb-2 dark:border-slate-700">الرد الميداني والملاحظات</h5>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">نص الرد الميداني <span class="text-rose-500">*</span></label>
                                    <select name="mapping[field_response]" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs focus:ring-2 focus:ring-brand-500/20 header-select text-slate-800 dark:text-slate-200" data-field="field_response" required>
                                        <option value="">-- اختر العمود --</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">تاريخ الرد</label>
                                    <select name="mapping[response_date]" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs focus:ring-2 focus:ring-brand-500/20 header-select text-slate-800 dark:text-slate-200" data-field="response_date">
                                        <option value="">-- اختر العمود --</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">المصدر</label>
                                    <select name="mapping[source]" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs focus:ring-2 focus:ring-brand-500/20 header-select text-slate-800 dark:text-slate-200" data-field="source">
                                        <option value="">-- اختر العمود --</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">عمل المصدر</label>
                                    <select name="mapping[source_job]" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs focus:ring-2 focus:ring-brand-500/20 header-select text-slate-800 dark:text-slate-200" data-field="source_job">
                                        <option value="">-- اختر العمود --</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">الملاحظات</label>
                                    <select name="mapping[notes]" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs focus:ring-2 focus:ring-brand-500/20 header-select text-slate-800 dark:text-slate-200" data-field="notes">
                                        <option value="">-- اختر العمود --</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Summary & Actions Panel -->
                    <div class="lg:col-span-4 space-y-6">
                        <div class="bg-brand-50/50 dark:bg-brand-900/10 border border-brand-100 dark:border-brand-900/20 p-6 rounded-3xl">
                            <h4 class="font-bold text-slate-900 dark:text-white mb-2 text-sm">ملخص استيراد الملف</h4>
                            <div class="space-y-2 text-xs text-slate-600 dark:text-slate-400">
                                <div class="flex justify-between">
                                    <span>عدد السجلات المكتشفة:</span>
                                    <strong id="totalRowsCount" class="text-brand-600 dark:text-brand-400">0</strong>
                                </div>
                                <div class="flex justify-between">
                                    <span>عدد الأعمدة المتاحة:</span>
                                    <strong id="totalColsCount" class="text-slate-800 dark:text-slate-200">0</strong>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col gap-3">
                            <button type="submit" id="executeImportBtn" class="w-full py-3.5 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-2xl shadow-lg shadow-brand-600/20 transition-all text-sm flex items-center justify-center gap-2">
                                <i class="fas fa-check-circle"></i>
                                <span>بدء تنفيذ الاستيراد</span>
                            </button>
                            <button type="button" onclick="location.reload()" class="w-full py-3 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-2xl transition-all text-xs">
                                إلغاء وإعادة الرفع
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('excelFile').addEventListener('change', function(e) {
        let file = e.target.files[0];
        if (!file) return;

        let formData = new FormData();
        formData.append('excel_file', file);
        formData.append('_token', '{{ csrf_token() }}');

        document.getElementById('uploadProgress').classList.remove('hidden');

        fetch('{{ route("system.field-responses.upload") }}', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('uploadProgress').classList.add('hidden');
            if (data.success) {
                document.getElementById('uploadStep').classList.add('hidden');
                document.getElementById('mappingStep').classList.remove('hidden');

                document.getElementById('totalRowsCount').innerText = data.total_rows;
                document.getElementById('totalColsCount').innerText = data.columns.length;

                // Fill dropdowns
                let selects = document.querySelectorAll('.header-select');
                selects.forEach(select => {
                    select.innerHTML = '<option value="">-- اختر العمود --</option>';
                    data.columns.forEach((col, index) => {
                        let opt = document.createElement('option');
                        opt.value = index;
                        opt.innerText = col;
                        select.appendChild(opt);
                    });
                });
            } else {
                alert(data.message || 'حدث خطأ أثناء معالجة الملف');
            }
        })
        .catch(err => {
            document.getElementById('uploadProgress').classList.add('hidden');
            alert('حدث خطأ أثناء رفع الملف');
        });
    });

    document.getElementById('mappingForm').addEventListener('submit', function(e) {
        e.preventDefault();

        let btn = document.getElementById('executeImportBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري حفظ البيانات...';

        let formData = new FormData(this);

        fetch('{{ route("system.field-responses.store-import") }}', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.href = data.redirect;
            } else {
                alert(data.message || 'حدث خطأ أثناء الاستيراد');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-check-circle"></i> بدء تنفيذ الاستيراد';
            }
        })
        .catch(err => {
            alert('حدث خطأ أثناء الاتصال بالسيرفر');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check-circle"></i> بدء تنفيذ الاستيراد';
        });
    });
</script>
@endpush
@endsection
