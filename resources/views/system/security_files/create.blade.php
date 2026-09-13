@extends('layouts.app')

@section('title', 'إضافة ملف أمني جديد')
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
                <h1 class="font-display font-bold text-2xl text-slate-900 dark:text-white m-0">إضافة ملف أمني جديد</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">تعبئة وتوثيق بيانات الملف الأمني والملخصات والمرفقات.</p>
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

    <form action="{{ route('system.security-files.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- قسم 1: اختيار الفرد وجلب بياناته الرئيسية --}}
        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
            <h2 class="text-base font-bold text-slate-900 dark:text-white border-b border-slate-100 dark:border-slate-800 pb-3 flex items-center gap-2">
                <i class="fas fa-user-shield text-brand-600"></i>
                <span>أولاً: تحديد الفرد المرتبط بالملف (إجباري)</span>
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">
                        اختر الفرد من البيانات الشخصية <span class="text-rose-500">*</span>
                    </label>
                    <select name="personal_data_id" id="personalDataSelect" required class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 transition-colors select2">
                        <option value="">-- اختر الفرد --</option>
                        @foreach($allPeople as $person)
                            <option value="{{ $person->id }}" {{ (old('personal_data_id', $selectedPersonId) == $person->id) ? 'selected' : '' }}>
                                {{ $person->name }} {{ $person->jihadi_name ? '('.$person->jihadi_name.')' : '' }} {{ $person->military_number ? '#'.$person->military_number : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- بطاقة التعبئة التلقائية للفرد --}}
                <div id="personPreviewCard" class="bg-slate-50 dark:bg-slate-800/60 p-4 rounded-xl border border-slate-200 dark:border-slate-700 hidden">
                    <div class="flex items-center gap-3">
                        <div class="w-14 h-14 rounded-xl bg-slate-200 dark:bg-slate-700 overflow-hidden shrink-0 border border-slate-300 dark:border-slate-600 flex items-center justify-center">
                            <img id="previewPhoto" src="" alt="" class="w-full h-full object-cover hidden">
                            <i id="previewIcon" class="fas fa-user text-2xl text-slate-400"></i>
                        </div>
                        <div class="space-y-1 text-xs text-slate-700 dark:text-slate-300">
                            <div class="font-bold text-slate-900 dark:text-white text-sm" id="previewName"></div>
                            <div><strong>الاسم الجهادي:</strong> <span id="previewJihadi"></span></div>
                            <div><strong>الرقم العسكري:</strong> <span id="previewMilitary"></span></div>
                            <div><strong>العنوان:</strong> <span id="previewAddress"></span></div>
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
                    <input type="text" name="file_number" value="{{ old('file_number') }}" placeholder="أدخل رقم الملف"
                        class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">تاريخ الملف الميلادي</label>
                    <input type="date" id="file_date_gregorian" name="file_date_gregorian" value="{{ old('file_date_gregorian') }}"
                        class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">تاريخ الملف الهجري (تلقائي)</label>
                    <input type="text" id="file_date_hijri" name="file_date_hijri" value="{{ old('file_date_hijri') }}" placeholder="يحسب تلقائياً من الميلادي" readonly
                        class="w-full px-3 py-2.5 bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-700 dark:text-slate-300 font-mono">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">الجهة المعدة للملف</label>
                    <select name="preparing_entity_id" class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 transition-colors">
                        <option value="">-- اختر الجهة --</option>
                        @foreach($entities as $id => $name)
                            <option value="{{ $id }}" {{ old('preparing_entity_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">المعد للملف</label>
                    <input type="text" name="file_preparer" value="{{ old('file_preparer') }}" placeholder="اسم معد الملف"
                        class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">مرفقات الملف (يمكن رفع أكثر من ملف)</label>
                    <input type="file" name="file_attachments[]" multiple
                        class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-700 dark:text-slate-300">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">أبرز البيانات الملخصة من الملف</label>
                <textarea name="highlight_summary" rows="4" placeholder="اكتب الملخص الشامل للملف الأمني هنا..."
                    class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 transition-colors">{{ old('highlight_summary') }}</textarea>
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
                    <input type="text" name="summary_preparer" value="{{ old('summary_preparer') }}" placeholder="اسم معد التلخيص"
                        class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">تاريخ التلخيص الميلادي</label>
                    <input type="date" id="summary_date_gregorian" name="summary_date_gregorian" value="{{ old('summary_date_gregorian') }}"
                        class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">تاريخ التلخيص الهجري (تلقائي)</label>
                    <input type="text" id="summary_date_hijri" name="summary_date_hijri" value="{{ old('summary_date_hijri') }}" placeholder="يحسب تلقائياً من الميلادي" readonly
                        class="w-full px-3 py-2.5 bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-700 dark:text-slate-300 font-mono">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">الوضع الجهادي</label>
                <textarea name="jihadist_status" rows="4" placeholder="اكتب الملاحظات والبيانات الخاصة بالوضع الجهادي هنا..."
                    class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 transition-colors">{{ old('jihadist_status') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">مرفقات الملخص (يمكن رفع أكثر من ملف)</label>
                <input type="file" name="summary_attachments[]" multiple
                    class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-700 dark:text-slate-300">
            </div>
        </div>

        {{-- Submit buttons --}}
        <div class="flex items-center gap-3">
            <button type="submit" class="px-6 py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-xl text-xs shadow-md transition-all flex items-center gap-2">
                <i class="fas fa-save"></i> حفظ الملف الأمني
            </button>
            <a href="{{ route('system.security-files.index') }}" class="px-6 py-3 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-bold rounded-xl text-xs hover:bg-slate-200 transition-colors">
                إلغاء
            </a>
        </div>
    </form>

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectElem = document.getElementById('personalDataSelect');
        const previewCard = document.getElementById('personPreviewCard');
        const previewName = document.getElementById('previewName');
        const previewJihadi = document.getElementById('previewJihadi');
        const previewMilitary = document.getElementById('previewMilitary');
        const previewAddress = document.getElementById('previewAddress');
        const previewPhoto = document.getElementById('previewPhoto');
        const previewIcon = document.getElementById('previewIcon');

        function fetchPersonDetails(personId) {
            if (!personId) {
                previewCard.classList.add('hidden');
                return;
            }
            fetch(`/system/security-files/person-details/${personId}`)
                .then(res => res.json())
                .then(data => {
                    previewName.textContent = data.name || '';
                    previewJihadi.textContent = data.jihadi_name || '-';
                    previewMilitary.textContent = data.military_number || '-';
                    previewAddress.textContent = data.address || '-';
                    if (data.photo_url) {
                        previewPhoto.src = data.photo_url;
                        previewPhoto.classList.remove('hidden');
                        previewIcon.classList.add('hidden');
                    } else {
                        previewPhoto.classList.add('hidden');
                        previewIcon.classList.remove('hidden');
                    }
                    previewCard.classList.remove('hidden');
                })
                .catch(() => previewCard.classList.add('hidden'));
        }

        selectElem.addEventListener('change', function() {
            fetchPersonDetails(this.value);
        });

        if (selectElem.value) {
            fetchPersonDetails(selectElem.value);
        }

        // Hijri Date Calculation Function
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
