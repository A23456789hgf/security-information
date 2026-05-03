<div class="mb-4 bg-light p-3 rounded-4 border shadow-sm">
    <div class="row align-items-center g-3">
        <div class="col-md-7">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0 rounded-start-pill ms-2">
                    <i class="fas fa-search text-muted"></i>
                </span>
                <input type="text" id="permissionSearch" class="form-control border-start-0 rounded-end-pill py-2" placeholder="بحث عن صلاحية أو صفحة...">
            </div>
        </div>
        <div class="col-md-5 text-md-end">
            <div class="form-check form-switch d-inline-block">
                <input class="form-check-input" type="checkbox" id="toggleSelectedOnly" {{ isset($rolePermissions) ? 'checked' : '' }}>
                <label class="form-check-label fw-bold ms-2" for="toggleSelectedOnly">عرض المختار فقط</label>
            </div>
        </div>
    </div>
</div>

<div id="permissionsContainer">
    @foreach($permissions as $domain => $modules)
        <div class="mb-5 domain-wrapper" data-domain="{{ $domain }}">
            <h5 class="fw-bold text-dark border-bottom pb-2 mb-4 d-flex align-items-center">
                <i class="fas fa-layer-group text-primary me-2"></i>
                <span class="ms-2">{{ $domain }}</span>
                <span class="badge bg-soft-primary text-primary ms-auto rounded-pill domain-selected-count">0</span>
            </h5>
            
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 matrix-table">
                        <thead class="bg-dark text-white">
                            <tr>
                                <th class="ps-4 py-3" style="min-width: 250px;">اسم الموديول / الصفحة</th>
                                <th class="text-center">عرض</th>
                                <th class="text-center">إضافة</th>
                                <th class="text-center">تعديل</th>
                                <th class="text-center">حذف</th>
                                <th class="text-center" style="min-width: 160px;">نطاق الوصول</th>
                                <th class="text-center" style="width: 100px;">تحديد الكل</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($modules as $module)
                                <tr class="page-row" data-page="{{ $module['name'] }}" data-slug="{{ $module['slug'] }}">
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-soft-primary p-2 rounded-3 me-3">
                                                <i class="fas fa-folder-open text-primary"></i>
                                            </div>
                                            <div>
                                                <span class="fw-bold d-block">{{ $module['name'] }}</span>
                                                <small class="text-muted">{{ $module['slug'] }}</small>
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
                                        <td class="text-center matrix-cell {{ $isChecked ? 'bg-soft-primary' : '' }}" 
                                            data-perm="{{ $permission ? $permission->name : '' }}">
                                            @if($permission)
                                                <div class="form-check d-inline-block p-0">
                                                    <input class="form-check-input permission-checkbox matrix-checkbox" type="checkbox" name="permissions[]" 
                                                           value="{{ $permission->name }}" 
                                                           id="perm_{{ $permission->id }}" 
                                                           {{ $isChecked ? 'checked' : '' }}>
                                                </div>
                                            @else
                                                <span class="text-muted small">-</span>
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
                                    <td class="text-center scope-cell">
                                        <select name="scopes[{{ $module['slug'] }}]" 
                                                class="scope-select form-select form-select-sm scope-{{ $currentScope }}"
                                                data-slug="{{ $module['slug'] }}">
                                            <option value="all"        {{ $currentScope === 'all'        ? 'selected' : '' }}>🌐 إظهار الكل</option>
                                            <option value="affiliated" {{ $currentScope === 'affiliated' ? 'selected' : '' }}>🏢 منتسبو الجهة فقط</option>
                                            <option value="own"        {{ $currentScope === 'own'        ? 'selected' : '' }}>👤 البيانات الخاصة فقط</option>
                                            <option value="none"       {{ $currentScope === 'none'       ? 'selected' : '' }}>🚫 حظر الكل</option>
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 select-row-all">الكل</button>
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


<style>
    .transition { transition: all 0.2s ease-in-out; }
    .hover-bg-light:hover { background-color: #f8f9fa; }
    .bg-soft-primary { background-color: rgba(13, 110, 253, 0.1); }
    .bg-soft-success { background-color: rgba(25, 135, 84, 0.1); }
    .cursor-pointer { cursor: pointer; }
    .matrix-table th { font-size: 0.85rem; letter-spacing: 0.5px; }
    .matrix-cell { transition: background-color 0.2s; }
    .matrix-cell:hover { background-color: rgba(0,0,0,0.02); }
    .selected-count { font-size: 0.75rem; min-width: 24px; }

    /* Scope dropdown styles */
    .scope-select {
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 0.3rem 0.6rem;
        cursor: pointer;
        transition: all 0.2s ease;
        border: 2px solid transparent;
    }
    .scope-all        { background-color: #d1fae5; color: #065f46; border-color: #6ee7b7; }
    .scope-affiliated { background-color: #ede9fe; color: #4c1d95; border-color: #c4b5fd; }
    .scope-own        { background-color: #dbeafe; color: #1e3a8a; border-color: #93c5fd; }
    .scope-none       { background-color: #fee2e2; color: #991b1b; border-color: #fca5a5; }
    .scope-select:focus { box-shadow: 0 0 0 3px rgba(99,102,241,0.2); outline: none; }
    .scope-cell { vertical-align: middle; }
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
            badge.classList.toggle('bg-primary', count > 0);
            badge.classList.toggle('text-white', count > 0);
            badge.classList.toggle('bg-soft-primary', count === 0);
            badge.classList.toggle('text-primary', count === 0);
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
                
                row.classList.toggle('d-none', !matches);
                if (matches) hasVisible = true;
            });

            wrapper.classList.toggle('d-none', !hasVisible);
        });
    }

    container.addEventListener('change', function(e) {
        if (e.target.classList.contains('permission-checkbox')) {
            if (e.target.classList.contains('matrix-checkbox')) {
                e.target.closest('.matrix-cell').classList.toggle('bg-soft-primary', e.target.checked);
            }
            updateCounters();
            if (toggleSwitch.checked) filterPermissions();
        }

        // Scope dropdown colour
        if (e.target.classList.contains('scope-select')) {
            const val = e.target.value;
            e.target.className = e.target.className
                .replace(/scope-(all|affiliated|own|none)/g, '')
                .trim();
            e.target.classList.add('scope-' + val);
        }
    });

    // Make matrix cells clickable to toggle checkbox
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

    // Select row all handler
    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('select-row-all')) {
            const row = e.target.closest('.page-row');
            const checkboxes = row.querySelectorAll('.permission-checkbox');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            
            checkboxes.forEach(cb => {
                cb.checked = !allChecked;
                cb.closest('.matrix-cell').classList.toggle('bg-soft-primary', !allChecked);
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
