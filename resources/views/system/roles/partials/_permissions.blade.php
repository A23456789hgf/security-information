<div class="space-y-6">
    {{-- Search and Filter Toolbar --}}
    <div class="bg-slate-50 dark:bg-slate-950/50 p-4 rounded-xl border border-slate-200 dark:border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="relative flex-1 max-w-md">
            <i class="fas fa-search absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            <input type="text" id="permissionSearch"
                class="w-full pr-10 pl-4 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-semibold text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all"
                placeholder="بحث عن صلاحية أو صفحة...">
        </div>
        <div class="flex items-center gap-2">
            <label class="relative inline-flex items-center cursor-pointer gap-2.5">
                <input type="checkbox" id="toggleSelectedOnly" class="sr-only peer" {{ isset($rolePermissions) ? 'checked' : '' }}>
                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:right-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-brand-600"></div>
                <span class="text-xs font-bold text-slate-700 dark:text-slate-300">عرض المختار فقط</span>
            </label>
        </div>
    </div>

    {{-- Permissions Matrix Container --}}
    <div id="permissionsContainer" class="space-y-6">
        @foreach($permissions as $domain => $modules)
            <div class="domain-wrapper space-y-3" data-domain="{{ $domain }}">
                <div class="flex items-center justify-between pb-2 border-b border-slate-200 dark:border-slate-800">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="fas fa-layer-group text-brand-600 dark:text-brand-400"></i>
                        <span>{{ $domain }}</span>
                    </h3>
                    <span class="domain-selected-count inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-brand-500/10 text-brand-600 dark:text-brand-400 border border-brand-500/20">
                        0
                    </span>
                </div>

                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-right border-collapse matrix-table">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 font-bold text-xs uppercase tracking-wider">
                                    <th class="px-5 py-3.5 min-w-[200px]">اسم الموديول / الصفحة</th>
                                    <th class="px-4 py-3.5 text-center w-20">عرض</th>
                                    <th class="px-4 py-3.5 text-center w-20">إضافة</th>
                                    <th class="px-4 py-3.5 text-center w-20">تعديل</th>
                                    <th class="px-4 py-3.5 text-center w-20">حذف</th>
                                    <th class="px-4 py-3.5 text-center min-w-[170px]">نطاق الوصول</th>
                                    <th class="px-4 py-3.5 text-center w-24">تحديد الكل</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                                @foreach($modules as $module)
                                    <tr class="page-row hover:bg-slate-50/50 dark:hover:bg-slate-800/25 transition-colors" data-page="{{ $module['name'] }}" data-slug="{{ $module['slug'] }}">
                                        <td class="px-5 py-3">
                                            <div class="flex items-center gap-2.5">
                                                <div class="w-7 h-7 rounded-2xl bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center text-xs">
                                                    <i class="fas fa-folder-open"></i>
                                                </div>
                                                <div>
                                                    <span class="font-bold text-slate-800 dark:text-slate-200 dark:text-slate-200 block">{{ $module['name'] }}</span>
                                                    <span class="text-[10px] text-slate-400 font-mono">{{ $module['slug'] }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        @foreach(['view', 'create', 'edit', 'delete'] as $op)
                                            @php
                                                $permission = $module['permissions'][$op] ?? null;
                                                $isChecked = false;
                                                if ($permission) {
                                                    if (is_array(old('permissions'))) {
                                                        $isChecked = in_array($permission->name, old('permissions'));
                                                    } elseif (isset($rolePermissions) && is_array($rolePermissions)) {
                                                        $isChecked = in_array($permission->name, $rolePermissions);
                                                    }
                                                }
                                            @endphp
                                            <td class="px-4 py-3 text-center matrix-cell cursor-pointer transition-colors {{ $isChecked ? 'bg-brand-500/10' : '' }}" 
                                                data-perm="{{ $permission ? $permission->name : '' }}">
                                                @if($permission)
                                                    <input class="permission-checkbox matrix-checkbox w-4 h-4 text-brand-600 rounded border-slate-300 dark:border-slate-700 focus:ring-brand-500 cursor-pointer"
                                                           type="checkbox" name="permissions[]" 
                                                           value="{{ $permission->name }}" 
                                                           id="perm_{{ $permission->id }}" 
                                                           {{ $isChecked ? 'checked' : '' }}>
                                                @else
                                                    <span class="text-slate-300 dark:text-slate-700">-</span>
                                                @endif
                                            </td>
                                        @endforeach
                                        @php
                                            $currentScope = old("scopes.{$module['slug']}",
                                                isset($roleScopes) && isset($roleScopes[$module['slug']])
                                                    ? $roleScopes[$module['slug']]
                                                    : 'all'
                                            );
                                        @endphp
                                        <td class="px-4 py-3 text-center scope-cell">
                                            <select name="scopes[{{ $module['slug'] }}]" 
                                                    class="scope-select text-xs font-semibold py-1.5 px-3 rounded-xl border transition-all cursor-pointer scope-{{ $currentScope }}"
                                                    data-slug="{{ $module['slug'] }}">
                                                <option value="all"        {{ $currentScope === 'all'        ? 'selected' : '' }}>🌐 إظهار الكل</option>
                                                <option value="affiliated" {{ $currentScope === 'affiliated' ? 'selected' : '' }}>🏢 منتسبو الجهة فقط</option>
                                                <option value="own"        {{ $currentScope === 'own'        ? 'selected' : '' }}>👤 البيانات الخاصة فقط</option>
                                                <option value="none"       {{ $currentScope === 'none'       ? 'selected' : '' }}>🚫 حظر الكل</option>
                                            </select>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <button type="button" class="select-row-all px-3 py-1 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-2xl text-xs font-bold transition-all">
                                                الكل
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<style>
    .scope-all        { background-color: rgba(16, 185, 129, 0.12); color: #059669; border-color: rgba(16, 185, 129, 0.3); }
    .scope-affiliated { background-color: rgba(139, 92, 246, 0.12); color: #7c3aed; border-color: rgba(139, 92, 246, 0.3); }
    .scope-own        { background-color: rgba(59, 130, 246, 0.12); color: #2563eb; border-color: rgba(59, 130, 246, 0.3); }
    .scope-none       { background-color: rgba(239, 68, 68, 0.12); color: #dc2626; border-color: rgba(239, 68, 68, 0.3); }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('permissionSearch');
    const toggleSwitch = document.getElementById('toggleSelectedOnly');
    const container = document.getElementById('permissionsContainer');

    function updateCounters() {
        container.querySelectorAll('.domain-wrapper').forEach(wrapper => {
            const count = wrapper.querySelectorAll('.permission-checkbox:checked').length;
            const badge = wrapper.querySelector('.domain-selected-count');
            badge.textContent = count;
            if (count > 0) {
                badge.className = 'domain-selected-count inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-brand-600 text-white';
            } else {
                badge.className = 'domain-selected-count inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-brand-500/10 text-brand-600 dark:text-brand-400 border border-brand-500/20';
            }
        });
    }

    function filterPermissions() {
        const searchTerm = searchInput.value.toLowerCase();
        const showOnlySelected = toggleSwitch.checked;

        container.querySelectorAll('.domain-wrapper').forEach(wrapper => {
            const domainName = wrapper.dataset.domain.toLowerCase();
            let hasVisible = false;

            wrapper.querySelectorAll('.page-row').forEach(row => {
                const pageName = row.dataset.page.toLowerCase();
                const pageSlug = row.dataset.slug.toLowerCase();
                const isSelected = row.querySelectorAll('.permission-checkbox:checked').length > 0;
                
                const matches = (pageName.includes(searchTerm) || pageSlug.includes(searchTerm) || domainName.includes(searchTerm)) 
                                && (!showOnlySelected || isSelected);
                
                row.classList.toggle('hidden', !matches);
                if (matches) hasVisible = true;
            });

            wrapper.classList.toggle('hidden', !hasVisible);
        });
    }

    container.addEventListener('change', function(e) {
        if (e.target.classList.contains('permission-checkbox')) {
            if (e.target.classList.contains('matrix-checkbox')) {
                e.target.closest('.matrix-cell').classList.toggle('bg-brand-500/10', e.target.checked);
            }
            updateCounters();
            if (toggleSwitch.checked) filterPermissions();
        }

        if (e.target.classList.contains('scope-select')) {
            const val = e.target.value;
            e.target.className = e.target.className
                .replace(/scope-(all|affiliated|own|none)/g, '')
                .trim();
            e.target.classList.add('scope-' + val);
        }
    });

    container.querySelectorAll('.matrix-cell').forEach(cell => {
        cell.addEventListener('click', function(e) {
            if (e.target.tagName !== 'INPUT') {
                const cb = this.querySelector('input');
                if (cb) {
                    cb.checked = !cb.checked;
                    cb.dispatchEvent(new Event('change', { bubbles: true }));
                }
            }
        });
    });

    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('select-row-all')) {
            const row = e.target.closest('.page-row');
            const checkboxes = row.querySelectorAll('.permission-checkbox');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            
            checkboxes.forEach(cb => {
                cb.checked = !allChecked;
                cb.closest('.matrix-cell').classList.toggle('bg-brand-500/10', !allChecked);
            });
            
            updateCounters();
            if (toggleSwitch.checked) filterPermissions();
        }
    });

    searchInput.addEventListener('input', filterPermissions);
    toggleSwitch.addEventListener('change', filterPermissions);
    
    updateCounters();
    filterPermissions();
});
</script>

