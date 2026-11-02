{{-- 1. UPLOAD & FIELD MAPPING STEPPER MODAL --}}
<div id="uploadModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xl w-full max-w-3xl overflow-hidden flex flex-col max-h-[90vh]">
        <div class="p-5 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
            <h3 class="font-bold text-base text-slate-900 dark:text-white flex items-center gap-2" id="uploadModalTitle">
                <i class="fas fa-file-excel text-brand-600"></i>
                <span>استيراد مجموعة بيانات ومطابقة الحقول</span>
            </h3>
            <button onclick="closeUploadModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <div class="p-6 overflow-y-auto flex-1 space-y-6">
            <input type="hidden" id="uploadTargetType" value="secondary">

            {{-- Step 1: File Upload & Metadata --}}
            <div id="uploadStep1" class="space-y-4">
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">اسم مجموعة البيانات <span class="text-rose-500">*</span></label>
                    <input type="text" id="uploadDatasetName" placeholder="مثال: كشف أسماء المستفيدين - سبتمبر 2026" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-brand-500">
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">المصدر / وصف المجموعة</label>
                    <input type="text" id="uploadDatasetSource" placeholder="مثال: ملف إكسل من الإدارة الفرعية" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-brand-500">
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">اختر ملف البيانات (Excel / CSV) <span class="text-rose-500">*</span></label>
                    <div class="border-2 border-dashed border-slate-300 dark:border-slate-700 rounded-2xl p-6 text-center hover:border-brand-500 transition-colors cursor-pointer" onclick="document.getElementById('uploadFileInput').click()">
                        <i class="fas fa-cloud-upload-alt text-3xl text-brand-600 mb-2"></i>
                        <p class="text-xs font-bold text-slate-700 dark:text-slate-300">اضغط هنا لاختيار الملف أو اسحبه إلى هنا</p>
                        <p class="text-[11px] text-slate-400 mt-1">يدعم صيغ XLSX, XLS, CSV</p>
                        <input type="file" id="uploadFileInput" accept=".xlsx,.xls,.csv" class="hidden" onchange="onFileSelected(event)">
                    </div>
                    <p class="text-xs font-bold text-emerald-600 hidden" id="selectedFileNameDisplay"></p>
                </div>
            </div>

            {{-- Step 2: Field Mapping Grid --}}
            <div id="uploadStep2" class="space-y-4 hidden">
                <div class="bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 p-3 rounded-xl text-xs text-amber-700 dark:text-amber-400">
                    <i class="fas fa-info-circle me-1"></i> يرجى اختيار الحقل المقابل بكل عمود، أو اختيار &lt;تجاهل العمود&gt; للاستثناء.
                </div>

                <div id="mappingGridContainer" class="space-y-3 max-h-72 overflow-y-auto pr-1">
                    {{-- Dynamically generated mapping rows --}}
                </div>
            </div>

            {{-- Step 3: Validation & Preview --}}
            <div id="uploadStep3" class="space-y-4 hidden">
                <h4 class="font-bold text-sm text-slate-900 dark:text-white">معاينة البيانات قبل الحفظ النهائية</h4>
                <div class="overflow-x-auto max-h-60" id="previewTableContainer">
                    {{-- Dynamically generated preview --}}
                </div>
            </div>
        </div>

        <div class="p-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 flex items-center justify-between">
            <button onclick="closeUploadModal()" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-200 dark:text-slate-400 dark:hover:bg-slate-800 transition-colors">
                إلغاء
            </button>

            <div class="flex items-center gap-2">
                <button id="btnUploadBack" onclick="prevUploadStep()" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-200 text-slate-700 hover:bg-slate-300 dark:bg-slate-800 dark:text-slate-300 hidden">
                    السابق
                </button>
                <button id="btnUploadNext" onclick="nextUploadStep()" class="px-5 py-2 rounded-xl text-xs font-bold bg-brand-600 hover:bg-brand-700 text-white shadow-sm">
                    التالي: مطابقة الحقول
                </button>
            </div>
        </div>
    </div>
</div>

{{-- 2. VIEW SECONDARY DATASET MODAL --}}
<div id="viewDatasetModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xl w-full max-w-4xl overflow-hidden flex flex-col max-h-[90vh]">
        <div class="p-5 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
            <h3 class="font-bold text-base text-slate-900 dark:text-white" id="viewModalTitle">معاينة بيانات المجموعة</h3>
            <button onclick="closeViewDatasetModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto flex-1" id="viewModalTableContainer">
            {{-- Dynamic View Table --}}
        </div>
        <div class="p-4 border-t border-slate-200 dark:border-slate-800 text-left">
            <button onclick="closeViewDatasetModal()" class="px-5 py-2 rounded-xl text-xs font-bold bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                إغلاق
            </button>
        </div>
    </div>
</div>

{{-- 3. EDIT SECONDARY DATASET MODAL --}}
<div id="editDatasetModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
        <div class="p-5 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
            <h3 class="font-bold text-base text-slate-900 dark:text-white">تعديل بيانات المجموعة الثانوية</h3>
            <button onclick="closeEditDatasetModal()" class="text-slate-400 hover:text-slate-600">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        <form id="editDatasetForm" onsubmit="submitEditDataset(event)" class="p-6 space-y-4">
            <input type="hidden" id="editDatasetId">
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">اسم المجموعة <span class="text-rose-500">*</span></label>
                <input type="text" id="editDatasetName" required class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm font-medium">
            </div>
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">المصدر</label>
                <input type="text" id="editDatasetSource" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm font-medium">
            </div>
            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="closeEditDatasetModal()" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-200 dark:text-slate-400">إلغاء</button>
                <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-brand-600 hover:bg-brand-700 text-white">حفظ التغييرات</button>
            </div>
        </form>
    </div>
</div>
