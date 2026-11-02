@extends('layouts.app')

@section('title', 'تعديل بيانات المستخدم')
@section('page_title', 'إدارة النظام')

@section('content')
<div class="max-w-5xl mx-auto space-y-6" dir="rtl">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white flex items-center gap-3">
                <div class="p-2.5 bg-amber-500/10 text-amber-600 dark:text-amber-400 rounded-xl">
                    <i class="fas fa-user-edit text-xl"></i>
                </div>
                تعديل بيانات المستخدم
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                تحديث بيانات حساب: <span class="font-bold text-brand-600 dark:text-brand-400">{{ $user->name }}</span>
            </p>
        </div>
        <a href="{{ route('system.users.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-sm font-semibold rounded-xl transition-all shadow-sm">
            <i class="fas fa-arrow-right"></i>
            عودة للقائمة
        </a>
    </div>

    @if ($errors->any())
        <div class="p-4 bg-rose-500/10 border border-rose-500/20 rounded-xl text-rose-600 dark:text-rose-400">
            <div class="flex items-start gap-3">
                <i class="fas fa-exclamation-triangle text-lg mt-0.5"></i>
                <div>
                    <span class="font-bold block mb-1">يرجى تصحيح الأخطاء التالية:</span>
                    <ul class="list-disc list-inside text-sm space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('system.users.update', $user) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- 1. Basic Information --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
            <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                <span class="w-2 h-5 bg-amber-500 rounded-full"></span>
                المعلومات الأساسية
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">الاسم الكامل <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" required
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all @error('name') border-rose-500 @enderror"
                        value="{{ old('name', $user->name) }}">
                    @error('name')
                        <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">اسم المستخدم <span class="text-rose-500">*</span></label>
                    <input type="text" name="username" required
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-mono font-semibold text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all @error('username') border-rose-500 @enderror"
                        value="{{ old('username', $user->username) }}">
                    @error('username')
                        <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">رقم الهاتف</label>
                    <input type="text" name="phone"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-medium text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all"
                        value="{{ old('phone', $user->phone) }}">
                </div>
            </div>
        </div>

        {{-- 2. Password Change (Optional) --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="w-2 h-5 bg-slate-400 rounded-full"></span>
                    تغيير كلمة المرور
                </h2>
                <span class="text-xs text-slate-400 font-medium">اختياري - اتركه فارغاً إذا لم ترغب بالتغيير</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">كلمة المرور الجديدة</label>
                    <input type="password" name="password"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-medium text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all"
                        placeholder="••••••••">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">تأكيد كلمة المرور الجديدة</label>
                    <input type="password" name="password_confirmation"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-medium text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all"
                        placeholder="••••••••">
                </div>
            </div>
        </div>

        {{-- 3. Administrative Scope & Roles --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
            <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                <span class="w-2 h-5 bg-purple-600 rounded-full"></span>
                النطاق الإداري والأدوار
            </h2>
            <div class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2 flex items-center justify-between">
                            <span>النطاق الإداري (الجهة)</span>
                            <span class="text-[11px] text-brand-600 dark:text-brand-400 font-normal">مربوط بالجهات الإدارية (/system/entities)</span>
                        </label>
                        <select name="internal_entity_id"
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all cursor-pointer">
                            <option value="">-- عام / غير محدد (كافة الجهات) --</option>
                            @foreach($entities as $entity)
                                <option value="{{ $entity->id }}" @selected(old('internal_entity_id', $user->internal_entity_id) == $entity->id)>
                                    {{ $entity->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">حالة الحساب</label>
                        <select name="is_active"
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all cursor-pointer">
                            <option value="1" @selected(old('is_active', (string)$user->is_active) == '1')>نشط (مفعل)</option>
                            <option value="0" @selected(old('is_active', (string)$user->is_active) == '0')>غير نشط (معطل)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-3">الأدوار والصلاحيات <span class="text-rose-500">*</span></label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($roles as $role)
                            <label class="flex items-center gap-3 p-3.5 bg-slate-50 dark:bg-slate-950/50 border border-slate-200 dark:border-slate-800 rounded-xl cursor-pointer hover:border-brand-500/40 transition-all">
                                <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                    @checked(in_array($role->name, old('roles', $user->roles->pluck('name')->toArray())))
                                    class="w-4 h-4 text-brand-600 rounded border-slate-300 dark:border-slate-700 focus:ring-brand-500">
                                <span class="font-semibold text-slate-800 dark:text-slate-200 text-xs">{{ $role->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. Geographical Scope Master-Details Table --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                <div>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="w-2 h-5 bg-emerald-600 rounded-full"></span>
                        النطاق الجغرافي (جدول المحافظات والمديريات)
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                        إضافة وتعديل المحافظات والمديريات المسموح للمستخدم الاطلاع على البيانات المضافة ضمن نطاقها.
                    </p>
                </div>
                <button type="button" id="addGeoRowBtn"
                    class="inline-flex items-center gap-2 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl transition-all shadow-sm">
                    <i class="fas fa-plus"></i>
                    إضافة نطاق جغرافي
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-right border-collapse text-xs" id="geoScopesTable">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/60 text-slate-600 dark:text-slate-300 font-bold border-b border-slate-200 dark:border-slate-800">
                            <th class="p-3 w-12 text-center">#</th>
                            <th class="p-3 min-w-[240px]">المحافظة / نوع النطاق</th>
                            <th class="p-3 min-w-[240px]">المديرية</th>
                            <th class="p-3 w-16 text-center">إجراء</th>
                        </tr>
                    </thead>
                    <tbody id="geoScopesBody" class="divide-y divide-slate-100 dark:divide-slate-800">
                        {{-- Dynamic rows --}}
                    </tbody>
                </table>
            </div>

            <p id="noGeoMessage" class="text-center py-6 text-slate-400 text-xs border border-dashed border-slate-200 dark:border-slate-800 rounded-xl mt-3" style="display: none;">
                لم يتم تحديد نطاق جغرافي مخصص بعد (سيكون للمستخدم صلاحية افتراضية). انقر فوق "إضافة نطاق جغرافي" لإضافة قيود المكان.
            </p>
        </div>

        {{-- Form Actions --}}
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-800">
            <a href="{{ route('system.users.index') }}"
                class="px-5 py-2.5 text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 transition-colors">
                إلغاء
            </a>
            <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-sm font-semibold shadow-sm hover:shadow transition-all">
                <i class="fas fa-check"></i>
                حفظ التعديلات
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    const governorates = @json($governorates);
    const directorates = @json($directorates);
    const existingScopes = @json($user->geographicScopes);
    let rowIndex = 0;

    function renderDirectorates(govId, selectedDirId = null) {
        if (!govId) {
            return `<option value="">[كل المديريات]</option>`;
        }
        const filtered = directorates.filter(d => d.governorate_id == govId);
        let html = `<option value="">[كل المديريات في هذه المحافظة]</option>`;
        filtered.forEach(d => {
            const sel = (selectedDirId && selectedDirId == d.id) ? 'selected' : '';
            html += `<option value="${d.id}" ${sel}>${d.name}</option>`;
        });
        return html;
    }

    function addGeoRow(scopeType = 'specific', govId = '', dirId = '') {
        const tbody = document.getElementById('geoScopesBody');
        document.getElementById('noGeoMessage').style.display = 'none';

        const tr = document.createElement('tr');
        tr.className = 'hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors';
        tr.id = `geo_row_${rowIndex}`;

        let govOptionsHtml = `
            <option value="ALL" ${scopeType === 'all' ? 'selected' : ''}>-- كل المحافظات وكل المديريات --</option>
            <option value="NONE" ${scopeType === 'none' ? 'selected' : ''}>-- بدون محافظة --</option>
            <optgroup label="المحافظات المسجلة">
        `;
        governorates.forEach(g => {
            const sel = (scopeType === 'specific' && govId == g.id) ? 'selected' : '';
            govOptionsHtml += `<option value="${g.id}" ${sel}>${g.name}</option>`;
        });
        govOptionsHtml += `</optgroup>`;

        const dirDisabledHtml = (scopeType === 'all' || scopeType === 'none') ? 'disabled' : '';

        tr.innerHTML = `
            <td class="p-3 text-center font-mono text-slate-400 font-bold row-num">${tbody.children.length + 1}</td>
            <td class="p-3">
                <input type="hidden" name="geographic_scopes[${rowIndex}][scope_type]" class="scope-type-input" value="${scopeType}">
                <select name="geographic_scopes[${rowIndex}][governorate_id]" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 gov-select">
                    <option value="">-- اختر النطاق أو المحافظة --</option>
                    ${govOptionsHtml}
                </select>
            </td>
            <td class="p-3">
                <select name="geographic_scopes[${rowIndex}][directorate_id]" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-brand-500 dir-select" ${dirDisabledHtml}>
                    ${renderDirectorates(govId, dirId)}
                </select>
            </td>
            <td class="p-3 text-center">
                <button type="button" class="p-2 text-rose-500 hover:text-rose-700 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-xl transition-colors delete-row-btn" title="حذف النطاق">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
        `;

        tbody.appendChild(tr);

        const govSelect = tr.querySelector('.gov-select');
        const dirSelect = tr.querySelector('.dir-select');
        const typeInput = tr.querySelector('.scope-type-input');
        const delBtn = tr.querySelector('.delete-row-btn');

        govSelect.addEventListener('change', function() {
            const val = this.value;
            if (val === 'ALL') {
                typeInput.value = 'all';
                dirSelect.innerHTML = `<option value="">[كل المديريات]</option>`;
                dirSelect.disabled = true;
            } else if (val === 'NONE') {
                typeInput.value = 'none';
                dirSelect.innerHTML = `<option value="">[غير ينطبق]</option>`;
                dirSelect.disabled = true;
            } else {
                typeInput.value = 'specific';
                dirSelect.disabled = false;
                dirSelect.innerHTML = renderDirectorates(val);
            }
        });

        delBtn.addEventListener('click', function() {
            tr.remove();
            reindexRows();
        });

        rowIndex++;
    }

    function reindexRows() {
        const tbody = document.getElementById('geoScopesBody');
        const rows = tbody.querySelectorAll('tr');
        if (rows.length === 0) {
            document.getElementById('noGeoMessage').style.display = 'block';
        } else {
            document.getElementById('noGeoMessage').style.display = 'none';
            rows.forEach((r, idx) => {
                const num = r.querySelector('.row-num');
                if (num) num.textContent = idx + 1;
            });
        }
    }

    document.getElementById('addGeoRowBtn').addEventListener('click', function() {
        addGeoRow('specific', '', '');
    });

    // Populate existing scopes on load
    document.addEventListener('DOMContentLoaded', function() {
        if (existingScopes && existingScopes.length > 0) {
            existingScopes.forEach(s => {
                addGeoRow(s.scope_type || 'specific', s.governorate_id || '', s.directorate_id || '');
            });
        } else {
            document.getElementById('noGeoMessage').style.display = 'block';
        }
    });
</script>
@endpush
@endsection
