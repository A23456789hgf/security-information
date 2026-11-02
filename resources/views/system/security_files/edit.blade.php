@extends('layouts.app')

@section('title', 'تعديل الملف الأمني')
@section('page_title', 'الملفات الأمنية')

@section('content')
<div class="space-y-6" dir="rtl">

    {{-- Breadcrumb & Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-2 print:hidden">
        <div class="flex items-center gap-3.5">
            <a href="{{ route('system.security-files.index') }}" class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:text-brand-600 flex items-center justify-center transition-colors shadow-xs">
                <i class="fas fa-arrow-right text-sm"></i>
            </a>
            <div>
                <h1 class="font-display font-bold text-2xl text-slate-900 dark:text-white m-0">تعديل الملف الأمني #{{ $securityFile->file_number ?? $securityFile->id }}</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">تعديل بيانات الملف والملخصات والمرفقات الخاصة بـ {{ $securityFile->personalData->name ?? 'الفرد' }}.</p>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="p-4 bg-rose-500/10 border border-rose-500/20 rounded-2xl text-rose-600 dark:text-rose-400 text-xs font-bold space-y-1">
            @foreach ($errors->all() as $error)
                <div><i class="fas fa-exclamation-circle me-1"></i> {{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form action="{{ route('system.security-files.update', $securityFile) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- قسم 1: الفرد المرتبط --}}
        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
            <h2 class="text-base font-bold text-slate-900 dark:text-white border-b border-slate-100 dark:border-slate-800 pb-3 flex items-center gap-2">
                <i class="fas fa-user-shield text-brand-600"></i>
                <span>أولاً: الفرد المرتبط بالملف</span>
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">الفرد المسجل <span class="text-rose-500">*</span></label>
                    <select name="personal_data_id" id="personalDataSelect" required class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 transition-colors select2">
                        @foreach($allPeople as $person)
                            <option value="{{ $person->id }}" {{ old('personal_data_id', $securityFile->personal_data_id) == $person->id ? 'selected' : '' }}>
                                {{ $person->name }} {{ $person->jihadi_name ? '('.$person->jihadi_name.')' : '' }} {{ $person->military_number ? '#'.$person->military_number : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- بطاقة التعبئة التلقائية للفرد وعرض كامل بياناته --}}
                <div id="personPreviewCard" class="col-span-1 md:col-span-2 bg-gradient-to-r from-brand-50/70 via-white to-brand-50/40 dark:from-slate-800/80 dark:via-slate-800 dark:to-slate-850 p-5 rounded-2xl border-2 border-brand-500/30 dark:border-brand-500/20 shadow-sm hidden">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                        {{-- صورة الفرد --}}
                        <div class="relative w-24 h-24 rounded-2xl bg-white dark:bg-slate-800 overflow-hidden shrink-0 border-2 border-brand-500 shadow-sm flex items-center justify-center">
                            <img id="previewPhoto" src="" alt="" class="w-full h-full object-cover hidden">
                            <div id="previewIcon" class="flex flex-col items-center justify-center text-slate-400 p-2 text-center">
                                <i class="fas fa-user-shield text-3xl text-brand-600/50 mb-1"></i>
                                <span class="text-[10px]">بدون صورة</span>
                            </div>
                        </div>

                        {{-- تفاصيل الفرد كاملة --}}
                        <div class="space-y-2 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="font-bold text-slate-900 dark:text-white text-base" id="previewName"></span>
                                <span id="previewJihadiBadge" class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-500/20">
                                    الجهادي: <span id="previewJihadi">-</span>
                                </span>
                                <span id="previewMilitaryBadge" class="px-2.5 py-0.5 rounded-full text-xs font-mono font-bold bg-blue-500/10 text-blue-700 dark:text-blue-400 border border-blue-500/20">
                                    عسكري: <span id="previewMilitary">-</span>
                                </span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-x-4 gap-y-1.5 text-xs text-slate-600 dark:text-slate-300">
                                <div><strong class="text-slate-800 dark:text-slate-200">الرقم الوطني:</strong> <span id="previewNationalId">-</span></div>
                                <div><strong class="text-slate-800 dark:text-slate-200">رقم الهاتف:</strong> <span id="previewPhone">-</span></div>
                                <div><strong class="text-slate-800 dark:text-slate-200">الرتبة:</strong> <span id="previewRank">-</span></div>
                                <div><strong class="text-slate-800 dark:text-slate-200">جهة العمل / الوظيفة:</strong> <span id="previewWorkplace">-</span></div>
                                <div><strong class="text-slate-800 dark:text-slate-200">فصيلة الدم / الحالة:</strong> <span id="previewBloodAndStatus">-</span></div>
                                <div><strong class="text-slate-800 dark:text-slate-200">الملفات السابقة:</strong> <span id="previewFilesCount" class="font-bold text-brand-600">-</span></div>
                                <div class="sm:col-span-2 md:col-span-3"><strong class="text-slate-800 dark:text-slate-200">العنوان:</strong> <span id="previewAddress">-</span></div>
                            </div>
                        </div>

                        {{-- رابط لفتح السجل الشخصي --}}
                        <div class="self-start sm:self-center shrink-0">
                            <a id="previewProfileLink" href="#" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white dark:bg-slate-700 hover:bg-slate-50 dark:hover:bg-slate-600 text-brand-600 dark:text-brand-300 rounded-xl border border-slate-200 dark:border-slate-600 text-xs font-bold transition-all shadow-xs">
                                <i class="fas fa-id-card"></i>
                                <span>السجل الشخصي</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- قسم 2: بيانات الملف الأمني الأساسية --}}
        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
            <h2 class="text-base font-bold text-slate-900 dark:text-white border-b border-slate-100 dark:border-slate-800 pb-3 flex items-center gap-2">
                <i class="fas fa-file-alt text-brand-600"></i>
                <span>ثانياً: بيانات الملف الأمني</span>
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">رقم الملف الأمني</label>
                    <input type="text" name="file_number" value="{{ old('file_number', $securityFile->file_number) }}"
                        class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">تاريخ الملف الميلادي</label>
                    <input type="date" id="file_date_gregorian" name="file_date_gregorian" value="{{ old('file_date_gregorian', optional($securityFile->file_date_gregorian)->format('Y-m-d')) }}"
                        class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">تاريخ الملف الهجري</label>
                    <input type="text" id="file_date_hijri" name="file_date_hijri" value="{{ old('file_date_hijri', $securityFile->file_date_hijri) }}" readonly
                        class="w-full px-3 py-2.5 bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-700 dark:text-slate-300 font-mono">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">الجهة المعدة للملف</label>
                    <select name="preparing_entity_id" class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 transition-colors">
                        <option value="">-- اختر الجهة --</option>
                        @foreach($entities as $id => $name)
                            <option value="{{ $id }}" {{ old('preparing_entity_id', $securityFile->preparing_entity_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">المعد للملف</label>
                    <input type="text" name="file_preparer" value="{{ old('file_preparer', $securityFile->file_preparer) }}"
                        class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 transition-colors">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">أبرز البيانات الملخصة من الملف</label>
                <textarea name="highlight_summary" rows="4"
                    class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 transition-colors">{{ old('highlight_summary', $securityFile->highlight_summary) }}</textarea>
            </div>

            {{-- إتاحة رفع وإدارة مرفقات الملف الموجودة --}}
            <div class="space-y-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">مرفقات الملف المسجلة حالياً</label>
                @if(!empty($securityFile->file_attachments))
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
                        @foreach($securityFile->file_attachments as $idx => $path)
                            <div class="p-2.5 bg-slate-50 dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 flex items-center justify-between text-xs">
                                <a href="{{ asset('storage/' . $path) }}" target="_blank" class="text-brand-600 dark:text-brand-400 font-bold truncate me-2">
                                    <i class="fas fa-file-pdf me-1"></i> مرفق {{ $idx + 1 }}
                                </a>
                                <form action="{{ route('system.security-files.delete-attachment', $securityFile) }}" method="POST" onsubmit="return confirm('حذف المرفق؟')">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="type" value="file">
                                    <input type="hidden" name="index" value="{{ $idx }}">
                                    <button type="submit" class="text-rose-500 hover:text-rose-700 p-1"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-xs text-slate-400">لا توجد مرفقات ملف سابقة.</div>
                @endif
                <div class="space-y-2">
                    <div class="flex items-center justify-between gap-2">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">إضافة مرفقات ملف جديدة</label>
                        <button type="button" onclick="openDocumentScanner('file_attachments', 'مرفقات الملف الأمني')"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white rounded-xl text-xs font-bold shadow-sm hover:shadow transition-all cursor-pointer"
                            style="background-color: #059669 !important; color: #ffffff !important; display: inline-flex !important;">
                            <i class="fas fa-print text-white" style="color: #ffffff !important;"></i>
                            <span style="color: #ffffff !important;">سحب عبر السكانر</span>
                        </button>
                    </div>
                    <input type="file" id="file_attachments_input" name="file_attachments[]" multiple class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs">
                    <div id="file_attachments_preview_list" class="space-y-1.5 hidden"></div>
                </div>
            </div>
        </div>

        {{-- قسم 3: التلخيص والوضع الجهادي --}}
        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
            <h2 class="text-base font-bold text-slate-900 dark:text-white border-b border-slate-100 dark:border-slate-800 pb-3 flex items-center gap-2">
                <i class="fas fa-journal-whills text-brand-600"></i>
                <span>ثالثاً: بيانات التلخيص والوضع الجهادي</span>
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">معد التلخيص</label>
                    <input type="text" name="summary_preparer" value="{{ old('summary_preparer', $securityFile->summary_preparer) }}"
                        class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">تاريخ التلخيص الميلادي</label>
                    <input type="date" id="summary_date_gregorian" name="summary_date_gregorian" value="{{ old('summary_date_gregorian', optional($securityFile->summary_date_gregorian)->format('Y-m-d')) }}"
                        class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">تاريخ التلخيص الهجري</label>
                    <input type="text" id="summary_date_hijri" name="summary_date_hijri" value="{{ old('summary_date_hijri', $securityFile->summary_date_hijri) }}" readonly
                        class="w-full px-3 py-2.5 bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-700 dark:text-slate-300 font-mono">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">الوضع الجهادي</label>
                <textarea name="jihadist_status" rows="4"
                    class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 transition-colors">{{ old('jihadist_status', $securityFile->jihadist_status) }}</textarea>
            </div>

            {{-- إتاحة رفع وإدارة مرفقات الملخص --}}
            <div class="space-y-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">مرفقات الملخص المسجلة حالياً</label>
                @if(!empty($securityFile->summary_attachments))
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
                        @foreach($securityFile->summary_attachments as $idx => $path)
                            <div class="p-2.5 bg-slate-50 dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 flex items-center justify-between text-xs">
                                <a href="{{ asset('storage/' . $path) }}" target="_blank" class="text-indigo-600 dark:text-indigo-400 font-bold truncate me-2">
                                    <i class="fas fa-file-alt me-1"></i> ملخص {{ $idx + 1 }}
                                </a>
                                <form action="{{ route('system.security-files.delete-attachment', $securityFile) }}" method="POST" onsubmit="return confirm('حذف هذا الملخص؟')">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="type" value="summary">
                                    <input type="hidden" name="index" value="{{ $idx }}">
                                    <button type="submit" class="text-rose-500 hover:text-rose-700 p-1"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-xs text-slate-400">لا توجد مرفقات ملخص سابقة.</div>
                @endif
                <div class="space-y-2">
                    <div class="flex items-center justify-between gap-2">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">إضافة مرفقات ملخص جديدة</label>
                        <button type="button" onclick="openDocumentScanner('summary_attachments', 'مرفقات الملخص والوضع الجهادي')"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white rounded-xl text-xs font-bold shadow-sm hover:shadow transition-all cursor-pointer"
                            style="background-color: #059669 !important; color: #ffffff !important; display: inline-flex !important;">
                            <i class="fas fa-print text-white" style="color: #ffffff !important;"></i>
                            <span style="color: #ffffff !important;">سحب عبر السكانر</span>
                        </button>
                    </div>
                    <input type="file" id="summary_attachments_input" name="summary_attachments[]" multiple class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs">
                    <div id="summary_attachments_preview_list" class="space-y-1.5 hidden"></div>
                </div>
            </div>
        </div>

        {{-- Submit buttons --}}
        <div class="flex items-center gap-3">
            <button type="submit" class="px-6 py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-xl text-xs shadow-md transition-all flex items-center gap-2">
                <i class="fas fa-save"></i> تحديث الملف الأمني
            </button>
            <a href="{{ route('system.security-files.index') }}" class="px-6 py-3 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-bold rounded-xl text-xs hover:bg-slate-200 transition-colors">
                إلغاء
            </a>
        </div>
    </form>

</div>

@include('system.security_files.partials.scanner-modal')

@push('scripts')
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/select2/select2.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectElem = document.getElementById('personalDataSelect');
        const previewCard = document.getElementById('personPreviewCard');
        const previewName = document.getElementById('previewName');
        const previewJihadi = document.getElementById('previewJihadi');
        const previewMilitary = document.getElementById('previewMilitary');
        const previewNationalId = document.getElementById('previewNationalId');
        const previewPhone = document.getElementById('previewPhone');
        const previewRank = document.getElementById('previewRank');
        const previewWorkplace = document.getElementById('previewWorkplace');
        const previewBloodAndStatus = document.getElementById('previewBloodAndStatus');
        const previewFilesCount = document.getElementById('previewFilesCount');
        const previewAddress = document.getElementById('previewAddress');
        const previewPhoto = document.getElementById('previewPhoto');
        const previewIcon = document.getElementById('previewIcon');
        const previewProfileLink = document.getElementById('previewProfileLink');

        function fetchPersonDetails(personId) {
            if (!personId) {
                if (previewCard) previewCard.classList.add('hidden');
                return;
            }
            fetch(`/system/security-files/person-details/${personId}`)
                .then(res => {
                    if (!res.ok) throw new Error('Network error');
                    return res.json();
                })
                .then(data => {
                    if (previewName) previewName.textContent = data.name || '';
                    if (previewJihadi) previewJihadi.textContent = data.jihadi_name || '-';
                    if (previewMilitary) previewMilitary.textContent = data.military_number || '-';
                    if (previewNationalId) previewNationalId.textContent = data.national_id_number || '-';
                    if (previewPhone) previewPhone.textContent = data.phone || '-';
                    if (previewRank) previewRank.textContent = data.rank || '-';
                    if (previewWorkplace) previewWorkplace.textContent = (data.workplace || data.job) ? ((data.workplace || '') + (data.job ? ' - ' + data.job : '')) : '-';
                    if (previewBloodAndStatus) previewBloodAndStatus.textContent = `${data.blood_type || '-'} | ${data.marital_status || '-'}`;
                    if (previewFilesCount) previewFilesCount.textContent = (data.security_files_count !== undefined) ? `${data.security_files_count} ملف مسجل` : '-';
                    if (previewAddress) previewAddress.textContent = data.address || '-';
                    
                    if (previewProfileLink && data.profile_url) {
                        previewProfileLink.href = data.profile_url;
                    }

                    if (data.photo_url) {
                        if (previewPhoto) {
                            previewPhoto.src = data.photo_url;
                            previewPhoto.classList.remove('hidden');
                        }
                        if (previewIcon) previewIcon.classList.add('hidden');
                    } else {
                        if (previewPhoto) {
                            previewPhoto.src = '';
                            previewPhoto.classList.add('hidden');
                        }
                        if (previewIcon) previewIcon.classList.remove('hidden');
                    }

                    if (previewCard) previewCard.classList.remove('hidden');
                })
                .catch(err => {
                    console.error('Error fetching person details:', err);
                });
        }

        // Initialize Select2 if available
        if (typeof $ !== 'undefined' && $.fn.select2) {
            $('#personalDataSelect').select2({
                dir: 'rtl',
                language: 'ar',
                placeholder: '-- اختر الفرد --',
                allowClear: true,
                width: '100%'
            }).on('change select2:select', function () {
                fetchPersonDetails($(this).val());
            }).on('select2:clear', function() {
                if (previewCard) previewCard.classList.add('hidden');
            });
        }

        selectElem.addEventListener('change', function() {
            fetchPersonDetails(this.value);
        });

        // Auto-fetch for existing selected person on edit page load
        if (selectElem.value) {
            fetchPersonDetails(selectElem.value);
        }

        function convertToHijri(gregDateStr) {
            if (!gregDateStr) return '';
            const d = new Date(gregDateStr);
            if (isNaN(d.getTime())) return '';
            try {
                return new Intl.DateTimeFormat('ar-SA-u-ca-islamic', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                }).format(d);
            } catch(e) {
                return '';
            }
        }

        document.getElementById('file_date_gregorian').addEventListener('change', function() {
            document.getElementById('file_date_hijri').value = convertToHijri(this.value);
        });

        document.getElementById('summary_date_gregorian').addEventListener('change', function() {
            document.getElementById('summary_date_hijri').value = convertToHijri(this.value);
        });
    });
</script>
@endpush
@endsection
