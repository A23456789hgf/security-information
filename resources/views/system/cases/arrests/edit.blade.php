@extends('layouts.app')

@section('title', 'تعديل بيانات الموقوف')
@section('page_title', 'السجلات الأساسية')

@section('content')
<div class="max-w-5xl mx-auto space-y-6" dir="rtl">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white flex items-center gap-3">
                <div class="p-2.5 bg-amber-500/10 text-amber-600 dark:text-amber-400 rounded-xl">
                    <i class="fas fa-edit text-xl"></i>
                </div>
                تعديل بيانات الموقوف
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                تعديل بيانات قضية التوقيف الخاصة بـ: <span class="text-brand-600 dark:text-brand-400 font-bold">{{ $arrest->personalData->name ?? '-' }}</span>
            </p>
        </div>
        <a href="{{ route('system.arrests.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-sm font-semibold rounded-xl transition-all shadow-sm">
            <i class="fas fa-arrow-right"></i>
            عودة للقائمة
        </a>
    </div>

    @if($errors->any())
        <div class="p-4 bg-rose-500/10 border border-rose-500/20 rounded-xl text-rose-600 dark:text-rose-400">
            <div class="flex items-start gap-3">
                <i class="fas fa-exclamation-triangle text-lg mt-0.5"></i>
                <div>
                    <span class="font-bold block mb-1">يرجى تصحيح الأخطاء التالية:</span>
                    <ul class="list-disc list-inside text-sm space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form id="arrestForm" action="{{ route('system.arrests.update', $arrest) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Personal Data Section --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
            <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                <span class="w-2 h-5 bg-amber-500 rounded-full"></span>
                البيانات الشخصية
            </h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">الشخص الموقوف <span class="text-rose-500">*</span></label>
                    <input type="text" id="personal_data_search" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-700 dark:text-slate-300 placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all mb-2" placeholder="ابحث عن الاسم..." autocomplete="off">
                    <select id="personal_data_id" name="personal_data_id" required
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all cursor-pointer @error('personal_data_id') border-rose-500 @enderror">
                        <option value="">-- اختر الاسم --</option>
                        @foreach($personalDataList as $id => $name)
                            <option value="{{ $id }}" @selected(old('personal_data_id', $arrest->personal_data_id) == $id)>{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('personal_data_id')
                        <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Personal Info Preview -->
                <div id="personal_info_box" class="mt-4 hidden">
                    <div class="bg-slate-50 dark:bg-slate-950/50 border border-slate-200 dark:border-slate-800 rounded-xl p-5 space-y-4">
                        {{-- Photo & Main Details Header --}}
                        <div class="flex items-center gap-3.5 p-2.5 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800">
                            <div class="w-16 h-16 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shrink-0 flex items-center justify-center shadow-xs">
                                <img id="info_photo" src="" alt="الصورة الشخصية" class="w-full h-full object-cover hidden">
                                <i id="info_photo_placeholder" class="fas fa-user text-slate-400 dark:text-slate-500 text-2xl"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1 flex items-center gap-1.5">
                                    <i class="fas fa-info-circle text-brand-600 dark:text-brand-400"></i>
                                    بيانات الشخص المختار
                                </h3>
                                <div id="info_person_name" class="font-bold text-slate-800 dark:text-slate-200 text-sm truncate"></div>
                                <div id="info_person_sub" class="text-xs text-slate-400 dark:text-slate-500 truncate"></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 text-xs">
                            <div class="bg-white dark:bg-slate-900 p-3 rounded-2xl border border-slate-200 dark:border-slate-800">
                                <span class="text-slate-400 block mb-0.5">الاسم الجهادي:</span>
                                <span id="info_jihadi_name" class="font-bold text-slate-800 dark:text-slate-200">-</span>
                            </div>
                            <div class="bg-white dark:bg-slate-900 p-3 rounded-2xl border border-slate-200 dark:border-slate-800">
                                <span class="text-slate-400 block mb-0.5">الرقم العسكري:</span>
                                <span id="info_military_number" class="font-bold text-slate-800 dark:text-slate-200">-</span>
                            </div>
                            <div class="bg-white dark:bg-slate-900 p-3 rounded-2xl border border-slate-200 dark:border-slate-800">
                                <span class="text-slate-400 block mb-0.5">فصيلة الدم:</span>
                                <span id="info_blood_type" class="font-bold text-rose-600 dark:text-rose-400">-</span>
                            </div>
                            <div class="bg-white dark:bg-slate-900 p-3 rounded-2xl border border-slate-200 dark:border-slate-800">
                                <span class="text-slate-400 block mb-0.5">تاريخ الميلاد:</span>
                                <span id="info_dob" class="font-bold text-slate-800 dark:text-slate-200">-</span>
                            </div>
                            <div class="bg-white dark:bg-slate-900 p-3 rounded-2xl border border-slate-200 dark:border-slate-800">
                                <span class="text-slate-400 block mb-0.5">الحالة الاجتماعية:</span>
                                <span id="info_marital_status" class="font-bold text-slate-800 dark:text-slate-200">-</span>
                            </div>
                            <div class="bg-white dark:bg-slate-900 p-3 rounded-2xl border border-slate-200 dark:border-slate-800">
                                <span class="text-slate-400 block mb-0.5">مكان الميلاد:</span>
                                <span id="info_pob" class="font-bold text-slate-800 dark:text-slate-200">-</span>
                            </div>
                            <div class="bg-white dark:bg-slate-900 p-3 rounded-2xl border border-slate-200 dark:border-slate-800">
                                <span class="text-slate-400 block mb-0.5">رقم الهوية:</span>
                                <span id="info_id_number" class="font-bold text-slate-800 dark:text-slate-200">-</span>
                            </div>
                            <div class="bg-white dark:bg-slate-900 p-3 rounded-2xl border border-slate-200 dark:border-slate-800">
                                <span class="text-slate-400 block mb-0.5">هاتف 1:</span>
                                <span id="info_phone_1" class="font-bold text-slate-800 dark:text-slate-200">-</span>
                            </div>
                            <div class="bg-white dark:bg-slate-900 p-3 rounded-2xl border border-slate-200 dark:border-slate-800">
                                <span class="text-slate-400 block mb-0.5">هاتف 2:</span>
                                <span id="info_phone_2" class="font-bold text-slate-800 dark:text-slate-200">-</span>
                            </div>
                            <div class="bg-white dark:bg-slate-900 p-3 rounded-2xl border border-slate-200 dark:border-slate-800 sm:col-span-2 md:col-span-3">
                                <span class="text-slate-400 block mb-0.5">العنوان:</span>
                                <span id="info_address" class="font-bold text-slate-800 dark:text-slate-200">-</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Case Details Section --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
            <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                <span class="w-2 h-5 bg-emerald-500 rounded-full"></span>
                تفاصيل الموقف والقضية
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">تاريخ الموقف</label>
                    <input type="date" name="arrest_date"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-medium text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all @error('arrest_date') border-rose-500 @enderror"
                        value="{{ old('arrest_date', (is_string($arrest->arrest_date) ? \Carbon\Carbon::parse($arrest->arrest_date)->format('Y-m-d') : ($arrest->arrest_date ? $arrest->arrest_date->format('Y-m-d') : ''))) }}">
                    @error('arrest_date')
                        <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">نوع القضية</label>
                    <select name="case_type_id"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all cursor-pointer">
                        <option value="">-- اختر نوع القضية --</option>
                        @foreach($caseTypes as $id => $type)
                            <option value="{{ $id }}" @selected(old('case_type_id', $arrest->case_type_id) == $id)>{{ $type }}</option>
                        @endforeach
                    </select>
                    @error('case_type_id')
                        <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">الموقع</label>
                    <input type="text" name="location"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all @error('location') border-rose-500 @enderror"
                        placeholder="موقع القضية..." value="{{ old('location', $arrest->location) }}">
                    @error('location')
                        <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">مكان التوقيف</label>
                    <input type="text" name="arrest_location"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all @error('arrest_location') border-rose-500 @enderror"
                        placeholder="المكان الذي تم التوقيف فيه..." value="{{ old('arrest_location', $arrest->arrest_location) }}">
                    @error('arrest_location')
                        <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">تفاصيل القضية</label>
                    <textarea name="case_details" rows="3"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all @error('case_details') border-rose-500 @enderror"
                        placeholder="أدخل تفاصيل القضية...">{{ old('case_details', $arrest->case_details) }}</textarea>
                    @error('case_details')
                        <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">المضبوطات</label>
                    <textarea name="seized_items" rows="3"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all @error('seized_items') border-rose-500 @enderror"
                        placeholder="سرد المضبوطات إن وجدت...">{{ old('seized_items', $arrest->seized_items) }}</textarea>
                    @error('seized_items')
                        <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">نتائج متابعة القضية</label>
                    <textarea name="case_follow_up_results" rows="3"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all @error('case_follow_up_results') border-rose-500 @enderror"
                        placeholder="نتائج المتابعة...">{{ old('case_follow_up_results', $arrest->case_follow_up_results) }}</textarea>
                    @error('case_follow_up_results')
                        <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">معلومات ذات صلة</label>
                    <textarea name="related_information" rows="3"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all @error('related_information') border-rose-500 @enderror"
                        placeholder="معلومات أخرى...">{{ old('related_information', $arrest->related_information) }}</textarea>
                    @error('related_information')
                        <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">حالة القضية</label>
                    <select name="status"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all cursor-pointer">
                        <option value="">-- اختر الحالة --</option>
                        <option value="معتقل" @selected(old('status', $arrest->status) == 'معتقل')>معتقل</option>
                        <option value="مفرج عنه" @selected(old('status', $arrest->status) == 'مفرج عنه')>مفرج عنه</option>
                        <option value="قيد التحقيق" @selected(old('status', $arrest->status) == 'قيد التحقيق')>قيد التحقيق</option>
                        <option value="محال للمحكمة" @selected(old('status', $arrest->status) == 'محال للمحكمة')>محال للمحكمة</option>
                    </select>
                    @error('status')
                        <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">مدة الفترة</label>
                    <input type="text" name="duration_period"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all @error('duration_period') border-rose-500 @enderror"
                        placeholder="مثال: 3 أشهر" value="{{ old('duration_period', $arrest->duration_period) }}">
                    @error('duration_period')
                        <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">الخلاصة</label>
                    <textarea name="conclusion" rows="3"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all @error('conclusion') border-rose-500 @enderror"
                        placeholder="خلاصة القضية...">{{ old('conclusion', $arrest->conclusion) }}</textarea>
                    @error('conclusion')
                        <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">تقارير التحقيق</label>
                    <textarea name="investigation_reports" rows="4"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all @error('investigation_reports') border-rose-500 @enderror"
                        placeholder="تفاصيل تقارير التحقيق...">{{ old('investigation_reports', $arrest->investigation_reports) }}</textarea>
                    @error('investigation_reports')
                        <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">إضافة مرفقات جديدة</label>
                    <input type="file" name="attachments[]" multiple
                        class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-700 dark:text-slate-300 file:mr-4 file:py-2 file:px-4 file:rounded-2xl file:border-0 file:text-xs file:font-semibold file:bg-brand-500/10 file:text-brand-600 dark:file:text-brand-400 hover:file:bg-brand-500/20 @error('attachments.*') border-rose-500 @enderror">
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">يمكنك رفع ملفات متعددة (PDF, DOC, DOCX, JPG, PNG, XLS, XLSX - حد أقصى 10MB لكل ملف)</p>
                    @error('attachments.*')
                        <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                @if($arrest->attachments && count($arrest->attachments) > 0)
                    <div class="md:col-span-2 bg-slate-50 dark:bg-slate-950/50 p-4 rounded-xl border border-slate-200 dark:border-slate-800">
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-3">المرفقات الحالية</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @foreach($arrest->attachments as $index => $path)
                                <div class="flex items-center justify-between bg-white dark:bg-slate-900 px-4 py-2.5 rounded-2xl border border-slate-200 dark:border-slate-800">
                                    <a href="{{ Storage::url($path) }}" target="_blank" class="text-brand-600 dark:text-brand-400 hover:underline font-semibold text-xs flex items-center gap-2">
                                        <i class="fas fa-file-alt"></i> مرفق #{{ $index + 1 }}
                                    </a>
                                    <button type="submit" form="deleteAttachmentForm_{{ $index }}" class="text-rose-500 hover:text-rose-700 p-1 rounded transition-colors" onclick="return confirm('هل تريد حذف هذا المرفق؟')">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">تاريخ الإفراج</label>
                    <input type="date" name="release_date"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-medium text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all @error('release_date') border-rose-500 @enderror"
                        value="{{ old('release_date', (is_string($arrest->release_date) ? \Carbon\Carbon::parse($arrest->release_date)->format('Y-m-d') : ($arrest->release_date ? $arrest->release_date->format('Y-m-d') : ''))) }}">
                    @error('release_date')
                        <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">سبب الإفراج</label>
                    <textarea name="release_reason" rows="2"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all @error('release_reason') border-rose-500 @enderror"
                        placeholder="السبب...">{{ old('release_reason', $arrest->release_reason) }}</textarea>
                    @error('release_reason')
                        <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">ملاحظات</label>
                    <textarea name="notes" rows="3"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all @error('notes') border-rose-500 @enderror"
                        placeholder="ملاحظات إضافية...">{{ old('notes', $arrest->notes) }}</textarea>
                    @error('notes')
                        <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-800">
            <button type="button" onclick="history.back()"
                class="px-5 py-2.5 text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:text-slate-200 dark:hover:text-slate-200 transition-colors">
                إلغاء
            </button>
            <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-sm font-semibold shadow-sm hover:shadow transition-all">
                <i class="fas fa-check"></i>
                حفظ التعديلات
            </button>
        </div>
    </form>

    {{-- Forms for deleting attachments --}}
    @if($arrest->attachments && count($arrest->attachments) > 0)
        @foreach($arrest->attachments as $index => $path)
            <form id="deleteAttachmentForm_{{ $index }}" action="{{ route('system.arrests.delete-attachment', $arrest) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
                <input type="hidden" name="file_index" value="{{ $index }}">
            </form>
        @endforeach
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('personal_data_search');
    const selectBox = document.getElementById('personal_data_id');
    const options = Array.from(selectBox.options).slice(1);

    searchInput.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        selectBox.innerHTML = '<option value="">-- اختر الاسم --</option>';
        options.forEach(option => {
            if (option.text.toLowerCase().includes(searchTerm)) {
                selectBox.appendChild(option.cloneNode(true));
            }
        });
    });

    selectBox.addEventListener('change', function() {
        const personId = this.value;
        const infoBox = document.getElementById('personal_info_box');
        
        if (!personId) {
            infoBox.classList.add('hidden');
            return;
        }

        fetch(`/system/personal-data/details/${personId}`)
            .then(res => res.json())
            .then(data => {
                const photoEl = document.getElementById('info_photo');
                const placeholderEl = document.getElementById('info_photo_placeholder');
                if (data.photo_url) {
                    photoEl.src = data.photo_url;
                    photoEl.classList.remove('hidden');
                    placeholderEl.classList.add('hidden');
                } else {
                    photoEl.src = '';
                    photoEl.classList.add('hidden');
                    placeholderEl.classList.remove('hidden');
                }

                document.getElementById('info_person_name').textContent = data.name || '-';
                document.getElementById('info_person_sub').textContent = (data.military_number && data.military_number !== '-' ? 'الرقم العسكري: ' + data.military_number : '') + (data.jihadi_name && data.jihadi_name !== '-' ? ' | ' + data.jihadi_name : '');
                document.getElementById('info_jihadi_name').textContent = data.jihadi_name || '-';
                document.getElementById('info_military_number').textContent = data.military_number || '-';
                document.getElementById('info_id_number').textContent = data.national_id_number || '-';
                document.getElementById('info_blood_type').textContent = data.blood_type || '-';
                document.getElementById('info_dob').textContent = data.date_of_birth || '-';
                document.getElementById('info_marital_status').textContent = data.marital_status || '-';
                document.getElementById('info_pob').textContent = data.place_of_birth || '-';
                document.getElementById('info_phone_1').textContent = data.phone_1 || '-';
                document.getElementById('info_phone_2').textContent = data.phone_2 || '-';
                document.getElementById('info_address').textContent = data.address || '-';
                
                infoBox.classList.remove('hidden');
            })
            .catch(err => {
                console.error('Error:', err);
                infoBox.classList.add('hidden');
            });
    });

    if (selectBox.value) {
        selectBox.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection

