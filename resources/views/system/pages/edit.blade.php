@extends('layouts.app')

@section('title', 'باني الصفحات: ' . $page->name)
@section('content')
<div class="space-y-6" dir="rtl">
    <!-- Header Banner -->
    <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col md:flex-flex flex-wrap gap-4 md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white flex items-center gap-3">
                <span class="w-10 h-10 rounded-2xl bg-brand-50 dark:bg-brand-900/30 text-brand-600 dark:text-brand-400 flex items-center justify-center text-lg shadow-sm">
                    <i class="fas fa-tools"></i>
                </span>
                باني الصفحات: <span class="text-brand-600 dark:text-brand-400">{{ $page->name }}</span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm">تحكم في إعدادات الصفحة وحقولها المخصصة.</p>
        </div>
        <div>
            <a href="{{ route('system.pages.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-2xl transition-all inline-flex items-center gap-2 text-sm">
                <i class="fas fa-arrow-right"></i>
                <span>العودة لقائمة الصفحات</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- إعدادات الصفحة -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="bg-slate-50/80 dark:bg-slate-800/50 p-6 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2 text-base">
                        <i class="fas fa-cog text-brand-500"></i> الإعدادات العامة
                    </h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('system.pages.update', $page) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">اسم الصفحة</label>
                            <input type="text" name="name" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-sm focus:ring-2 focus:ring-brand-500/20 text-slate-800 dark:text-slate-200" value="{{ $page->name }}" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">القسم الإداري <span class="text-rose-500">*</span></label>
                            <select name="section_id" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-sm focus:ring-2 focus:ring-brand-500/20 text-slate-800 dark:text-slate-200" required>
                                <option value="" disabled>اختر القسم الإداري</option>
                                @foreach($sections as $sec)
                                    <option value="{{ $sec->id }}" {{ old('section_id', $page->section_id) == $sec->id ? 'selected' : '' }}>
                                        {{ $sec->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="text-xs text-slate-400 mt-1 block">يستخدم لتجميع الصفحات في القائمة الجانبية</span>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">الرابط (Slug)</label>
                            <input type="text" class="w-full px-4 py-2 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl text-xs text-slate-500 cursor-not-allowed font-mono" value="{{ $page->slug }}" readonly disabled>
                            <span class="text-xs text-slate-400 mt-1 block">يتم توليد الرابط تلقائياً ولا يمكن تعديله.</span>
                        </div>
                        <div class="pt-2 space-y-3">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input class="w-4 h-4 text-brand-600 focus:ring-brand-500 border-gray-300 rounded" type="checkbox" name="is_unique_id" value="1" id="isUniqueId" {{ $page->is_unique_id ? 'checked' : '' }}>
                                <span class="text-xs font-bold text-slate-700 dark:text-slate-300">معرف فريد (Unique ID)</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input class="w-4 h-4 text-brand-600 focus:ring-brand-500 border-gray-300 rounded" type="checkbox" name="is_active" value="1" id="isActive" {{ $page->is_active ? 'checked' : '' }}>
                                <span class="text-xs font-bold text-slate-700 dark:text-slate-300">نشطة (تظهر للمستخدمين)</span>
                            </label>
                        </div>
                        <div class="pt-4">
                            <button type="submit" class="w-full py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-2xl shadow-lg shadow-brand-500/25 transition-all flex items-center justify-center gap-2 text-sm">
                                <i class="fas fa-save"></i>
                                <span>تحديث الإعدادات</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-indigo-50 dark:bg-indigo-950/30 border border-indigo-200/80 dark:border-indigo-800/50 p-4 rounded-3xl flex items-start gap-3">
                <div class="text-indigo-600 dark:text-indigo-400 mt-0.5"><i class="fas fa-info-circle"></i></div>
                <p class="text-xs text-indigo-900 dark:text-indigo-300 leading-relaxed">
                    تأكد من أن أسماء الحقول التدخلية (Database Name) فريدة داخل نفس الصفحة لضمان سلامة تخزين البيانات.
                </p>
            </div>
        </div>

        <!-- إدارة الحقول -->
        <div class="lg:col-span-8">
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="bg-slate-50/80 dark:bg-slate-800/50 p-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2 text-base">
                        <i class="fas fa-list-ul text-brand-500"></i> هيكلية الصفحة (الحقول)
                    </h3>
                    <button type="button" class="px-4 py-2 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 font-bold rounded-2xl transition-all text-xs flex items-center gap-1.5" data-bs-toggle="modal" data-bs-target="#addFieldModal">
                        <i class="fas fa-plus"></i> إضافة حقل جديد
                    </button>
                </div>
                <div class="p-0">
                    @if($page->fields->isEmpty())
                        <div class="text-center py-12">
                            <div class="w-16 h-16 rounded-3xl bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center mx-auto mb-3 text-2xl">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <h4 class="font-bold text-slate-700 dark:text-slate-300 text-sm">لا توجد حقول حتى الآن</h4>
                            <p class="text-xs text-slate-400 mt-1">ابدأ بإضافة أول حقل لبناء هذه الصفحة</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-right border-collapse">
                                <thead>
                                    <tr class="bg-slate-50/80 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800 text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                                        <th class="py-3 px-6">اسم الحقل</th>
                                        <th class="py-3 px-4">النوع</th>
                                        <th class="py-3 px-4">الاسم التقني</th>
                                        <th class="py-3 px-4 text-center">إلزامي</th>
                                        <th class="py-3 px-6 text-center">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                                    @foreach($page->fields as $field)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                        <td class="py-3.5 px-6 font-bold text-slate-900 dark:text-white">{{ $field->label }}</td>
                                        <td class="py-3.5 px-4 text-xs text-slate-600 dark:text-slate-400">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800 font-medium">
                                                {{ $field->type }}
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4"><code class="text-xs font-mono text-brand-600 dark:text-brand-400">{{ $field->name }}</code></td>
                                        <td class="py-3.5 px-4 text-center">
                                            @if($field->is_required)
                                                <span class="w-6 h-6 rounded-full bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 inline-flex items-center justify-center text-xs"><i class="fas fa-check"></i></span>
                                            @else
                                                <span class="text-slate-300 dark:text-slate-600">-</span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-6 text-center">
                                            <form action="{{ route('system.fields.destroy', $field) }}" method="POST" class="inline-block" onsubmit="return confirm('هل تريد حذف هذا الحقل؟ سيؤدي ذلك لحذف البيانات المرتبطة به في السجلات.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-8 h-8 rounded-full bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 hover:bg-rose-100 flex items-center justify-center mx-auto transition-colors" title="حذف الحقل">
                                                    <i class="fas fa-trash text-xs"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal إضافة حقل -->
<div class="modal fade" id="addFieldModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('system.pages.fields.store', $page) }}" method="POST" class="w-full">
            @csrf
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden">
                <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2 text-base">
                        <i class="fas fa-plus-square text-brand-500"></i> إضافة حقل جديد
                    </h3>
                    <button type="button" class="text-slate-400 hover:text-slate-600" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">اسم الحقل (يظهر للمستخدم)</label>
                        <input type="text" name="label" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-sm focus:ring-2 focus:ring-brand-500/20 text-slate-800 dark:text-slate-200" required placeholder="مثلاً: تاريخ الواقعة">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">نوع البيانات</label>
                        <select name="type" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-sm focus:ring-2 focus:ring-brand-500/20 text-slate-800 dark:text-slate-200 field-type-select-modal" required>
                            <option value="text">بيانات (نص قصير)</option>
                            <option value="textarea">نص (نص طويل)</option>
                            <option value="date">تاريخ</option>
                            <option value="datetime">تاريخ ووقت</option>
                            <option value="number">أرقام (Int/Float/Currency)</option>
                            <option value="checkbox">تحقق (نعم/لا)</option>
                            <option value="select">قائمة منسدلة (خيارات)</option>
                            <option value="link">ارتباط بصفحة أخرى</option>
                            <option value="table">جدول فرعي (Child table)</option>
                            <option value="file">مرفقات / صور</option>
                        </select>
                    </div>
                    <div class="extra-settings-modal space-y-3">
                        <div class="options-group" class="hidden">
                            <label class="block text-xs font-bold text-brand-600 dark:text-brand-400 mb-1.5">خيارات القائمة (فصل بينها بفاصلة)</label>
                            <textarea name="options" class="w-full p-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-xs focus:ring-2 focus:ring-brand-500/20 text-slate-800 dark:text-slate-200" rows="3" placeholder='عسكري, مدني, آخر'></textarea>
                        </div>
                        
                        <div class="link-group" class="hidden">
                            <label class="block text-xs font-bold text-brand-600 dark:text-brand-400 mb-1.5">الصفحة المستهدفة</label>
                            <select name="metadata[target_page_id]" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-sm focus:ring-2 focus:ring-brand-500/20 text-slate-800 dark:text-slate-200">
                                <option value="" disabled selected>اختر الصفحة</option>
                                @foreach(App\Models\Page::where('is_active', true)->get() as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="table-group" class="hidden">
                            <label class="block text-xs font-bold text-brand-600 dark:text-brand-400 mb-1.5">أعمدة الجدول (فصل بينها بفاصلة)</label>
                            <input type="text" name="metadata[table_columns]" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-sm focus:ring-2 focus:ring-brand-500/20 text-slate-800 dark:text-slate-200" placeholder="الاسم, السن, العنوان">
                        </div>
                    </div>
                    <div>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input class="w-4 h-4 text-brand-600 focus:ring-brand-500 border-gray-300 rounded" type="checkbox" name="is_required" value="1" id="isRequired">
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300">هذا الحقل إلزامي</span>
                        </label>
                    </div>
                </div>
                <div class="p-6 bg-slate-50/80 dark:bg-slate-800/50 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-3">
                    <button type="button" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-2xl transition-all text-xs" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-2xl shadow-lg shadow-brand-500/25 transition-all text-xs">إضافة الحقل</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.querySelector('.field-type-select-modal');
        const extraSettings = document.querySelector('.extra-settings-modal');
        const optionsGroup = extraSettings.querySelector('.options-group');
        const linkGroup = extraSettings.querySelector('.link-group');
        const tableGroup = extraSettings.querySelector('.table-group');

        typeSelect.addEventListener('change', function() {
            // Hide all
            optionsGroup.style.display = 'none';
            linkGroup.style.display = 'none';
            tableGroup.style.display = 'none';

            if (this.value === 'select') {
                optionsGroup.style.display = 'block';
            } else if (this.value === 'link') {
                linkGroup.style.display = 'block';
            } else if (this.value === 'table') {
                tableGroup.style.display = 'block';
            }
        });
    });
</script>
@endpush
@endsection


