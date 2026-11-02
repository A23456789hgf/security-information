<script>
    // Global State Variables
    let currentUploadStep = 1;
    let parsedHeaders = [];
    let parsedSampleRows = [];
    let parsedRowCount = 0;
    let selectedFileObj = null;
    let currentComparisonResults = null;
    let currentSavedMatchId = null;
    let activeResultCategory = 'matched';

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // ── 1. Tab Navigation ──
    function switchTab(tabName) {
        ['Secondary', 'Main', 'Comparison', 'Logs'].forEach(t => {
            const btn = document.getElementById('tabBtn' + t);
            const content = document.getElementById('tabContent' + t);
            if (btn && content) {
                btn.classList.remove('active');
                content.classList.add('hidden');
            }
        });

        const activeBtn = document.getElementById('tabBtn' + capitalizeFirst(tabName));
        const activeContent = document.getElementById('tabContent' + capitalizeFirst(tabName));
        if (activeBtn) activeBtn.classList.add('active');
        if (activeContent) activeContent.classList.remove('hidden');
    }

    function capitalizeFirst(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    // ── 2. Modal Stepper Management ──
    function openUploadModal(targetType = 'secondary') {
        document.getElementById('uploadTargetType').value = targetType;
        document.getElementById('uploadDatasetName').value = targetType === 'main' ? 'البيانات الرئيسية' : '';
        document.getElementById('uploadDatasetSource').value = '';
        document.getElementById('uploadFileInput').value = '';
        document.getElementById('selectedFileNameDisplay').classList.add('hidden');

        document.getElementById('uploadModalTitle').innerHTML = targetType === 'main' 
            ? '<i class="fas fa-database text-emerald-600 me-2"></i>استيراد وتحديث البيانات الرئيسية'
            : '<i class="fas fa-file-excel text-brand-600 me-2"></i>استيراد مجموعة بيانات ثانوية جديدة';

        currentUploadStep = 1;
        parsedHeaders = [];
        parsedSampleRows = [];
        selectedFileObj = null;
        updateStepperUI();

        document.getElementById('uploadModal').classList.remove('hidden');
    }

    function closeUploadModal() {
        document.getElementById('uploadModal').classList.add('hidden');
    }

    function updateStepperUI() {
        [1, 2, 3].forEach(step => {
            document.getElementById('uploadStep' + step).classList.add('hidden');
        });
        document.getElementById('uploadStep' + currentUploadStep).classList.remove('hidden');

        const btnBack = document.getElementById('btnUploadBack');
        const btnNext = document.getElementById('btnUploadNext');

        if (currentUploadStep === 1) {
            btnBack.classList.add('hidden');
            btnNext.textContent = 'التالي: مطابقة الحقول';
        } else if (currentUploadStep === 2) {
            btnBack.classList.remove('hidden');
            btnNext.textContent = 'التالي: المعاينة والتحقق';
        } else if (currentUploadStep === 3) {
            btnBack.classList.remove('hidden');
            btnNext.textContent = 'تأكيد وحفظ البيانات';
        }
    }

    function onFileSelected(event) {
        const file = event.target.files[0];
        if (!file) return;

        selectedFileObj = file;
        const display = document.getElementById('selectedFileNameDisplay');
        display.textContent = 'الملف المختار: ' + file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
        display.classList.remove('hidden');

        // Auto parse file via AJAX
        const formData = new FormData();
        formData.append('file', file);
        formData.append('_token', csrfToken);

        fetch("{{ route('differentiation.parse-file') }}", {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                parsedHeaders = data.headers;
                parsedSampleRows = data.sample;
                parsedRowCount = data.count;
            } else {
                alert('خطأ في قراءة الملف: ' + data.message);
                selectedFileObj = null;
                display.classList.add('hidden');
            }
        })
        .catch(err => {
            alert('حدث خطأ أثناء رفع الملف وقراءته.');
            console.error(err);
        });
    }

    function nextUploadStep() {
        if (currentUploadStep === 1) {
            const name = document.getElementById('uploadDatasetName').value.trim();
            if (!name) {
                alert('يرجى إدخال اسم لمجموعة البيانات.');
                return;
            }
            if (!selectedFileObj || parsedHeaders.length === 0) {
                alert('يرجى اختيار ملف بيانات صالحة والإنتظار لحين معالجته.');
                return;
            }

            renderMappingGrid();
            currentUploadStep = 2;
            updateStepperUI();
        } else if (currentUploadStep === 2) {
            renderPreviewTable();
            currentUploadStep = 3;
            updateStepperUI();
        } else if (currentUploadStep === 3) {
            submitUploadData();
        }
    }

    function prevUploadStep() {
        if (currentUploadStep > 1) {
            currentUploadStep--;
            updateStepperUI();
        }
    }

    // ── 3. Field Mapping Grid Renderer ──
    function renderMappingGrid() {
        const container = document.getElementById('mappingGridContainer');
        container.innerHTML = '';

        parsedHeaders.forEach((header, idx) => {
            const rowDiv = document.createElement('div');
            rowDiv.className = 'mapping-row flex items-center justify-between gap-4';

            rowDiv.innerHTML = `
                <div class="flex-1 font-bold text-xs text-slate-800 dark:text-slate-200">
                    <span class="text-slate-400 font-mono me-2">#${idx + 1}</span>
                    <span>${header}</span>
                </div>
                <div class="flex-1">
                    <input type="text" class="mapping-input w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg px-3 py-1.5 text-xs font-medium" data-header="${header}" value="${header}">
                </div>
                <div>
                    <label class="inline-flex items-center text-xs text-slate-500 cursor-pointer">
                        <input type="checkbox" class="mapping-ignore rounded text-brand-600 me-1" onchange="toggleMappingIgnore(this)">
                        <span>تجاهل</span>
                    </label>
                </div>
            `;
            container.appendChild(rowDiv);
        });
    }

    function toggleMappingIgnore(checkbox) {
        const row = checkbox.closest('.mapping-row');
        const input = row.querySelector('.mapping-input');
        if (checkbox.checked) {
            input.disabled = true;
            input.classList.add('opacity-50', 'line-through');
        } else {
            input.disabled = false;
            input.classList.remove('opacity-50', 'line-through');
        }
    }

    function getMappingValues() {
        const mapping = {};
        const rows = document.querySelectorAll('.mapping-row');
        rows.forEach(row => {
            const input = row.querySelector('.mapping-input');
            const checkbox = row.querySelector('.mapping-ignore');
            const header = input.getAttribute('data-header');

            if (checkbox && checkbox.checked) {
                mapping[header] = '<ignore>';
            } else {
                mapping[header] = input.value.trim() || header;
            }
        });
        return mapping;
    }

    // ── 4. Render Preview Table ──
    function renderPreviewTable() {
        const container = document.getElementById('previewTableContainer');
        const mapping = getMappingValues();

        let tableHtml = '<table class="w-full text-right border-collapse diff-table"><thead><tr><th>#</th>';
        parsedHeaders.forEach(h => {
            if (mapping[h] !== '<ignore>') {
                tableHtml += `<th>${mapping[h]}</th>`;
            }
        });
        tableHtml += '</tr></thead><tbody>';

        parsedSampleRows.forEach((row, idx) => {
            tableHtml += `<tr><td class="font-mono text-xs">${idx + 1}</td>`;
            parsedHeaders.forEach(h => {
                if (mapping[h] !== '<ignore>') {
                    tableHtml += `<td class="text-xs">${row[h] || '-'}</td>`;
                }
            });
            tableHtml += '</tr>';
        });

        tableHtml += '</tbody></table>';
        container.innerHTML = tableHtml;
    }

    // ── 5. Submit Upload Data ──
    function submitUploadData() {
        const targetType = document.getElementById('uploadTargetType').value;
        const name = document.getElementById('uploadDatasetName').value.trim();
        const source = document.getElementById('uploadDatasetSource').value.trim();
        const mapping = getMappingValues();

        const formData = new FormData();
        formData.append('file', selectedFileObj);
        formData.append('name', name);
        formData.append('source', source);
        formData.append('_token', csrfToken);

        Object.keys(mapping).forEach(key => {
            formData.append(`mapping[${key}]`, mapping[key]);
        });

        const url = targetType === 'main' 
            ? "{{ route('differentiation.store-main') }}" 
            : "{{ route('differentiation.store-secondary') }}";

        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                closeUploadModal();
                window.location.reload();
            } else {
                alert('خطأ أثناء الحفظ: ' + data.message);
            }
        })
        .catch(err => {
            alert('حدث خطأ أثناء اتصال السيرفر.');
            console.error(err);
        });
    }

    // ── 6. Comparison Engine Helpers & UI ──
    function onSecondaryDatasetSelected() {
        const secId = document.getElementById('compSecondaryId').value;
        if (!secId) return;

        fetch(`{{ url('/differentiation/secondary') }}/${secId}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                populateKeyFields(data.headers);
                generateComparisonMapping(data.headers);
            }
        });
    }

    function populateKeyFields(headers) {
        const container = document.getElementById('keyFieldsContainer');
        container.innerHTML = '';

        headers.forEach((h, idx) => {
            const label = document.createElement('label');
            label.className = 'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-xs font-bold cursor-pointer hover:border-brand-500';
            const checked = idx === 0 ? 'checked' : '';
            label.innerHTML = `<input type="checkbox" class="comp-key-checkbox rounded text-brand-600" value="${h}" ${checked}> <span>${h}</span>`;
            container.appendChild(label);
        });
    }

    function togglePrimarySourceSelect() {
        const type = document.getElementById('compPrimarySourceType').value;
        const container = document.getElementById('systemSourceContainer');
        if (type === 'system_source') {
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
        }
    }

    function onSystemSourceSelected() {
        const sourceKey = document.getElementById('compSystemSourceKey').value;
        if (!sourceKey) return;

        fetch(`{{ url('/differentiation/system-fields') }}/${sourceKey}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                updateComparisonMappingDropdowns(data.fields);
            }
        });
    }

    function generateComparisonMapping(secondaryHeaders) {
        const container = document.getElementById('comparisonMappingContainer');
        container.innerHTML = '';

        secondaryHeaders.forEach(h => {
            const div = document.createElement('div');
            div.className = 'flex items-center justify-between gap-4 p-2 bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800';
            div.innerHTML = `
                <span class="text-xs font-bold text-slate-700 dark:text-slate-300 w-1/3">${h}</span>
                <span class="text-slate-400 text-xs"><i class="fas fa-arrow-left"></i></span>
                <select class="comp-field-map-select w-2/3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg px-2.5 py-1 text-xs font-medium" data-sec-col="${h}">
                    <option value="${h}">${h} (طبيعي)</option>
                    <option value="<ignore>">&lt;تجاهل العمود&gt;</option>
                </select>
            `;
            container.appendChild(div);
        });
    }

    function updateComparisonMappingDropdowns(systemFields) {
        const selects = document.querySelectorAll('.comp-field-map-select');
        selects.forEach(sel => {
            const currentSecCol = sel.getAttribute('data-sec-col');
            let html = `<option value="${currentSecCol}">${currentSecCol} (مطابقة باسم العمود)</option>`;
            html += `<option value="<ignore>">&lt;تجاهل العمود&gt;</option>`;
            systemFields.forEach(f => {
                html += `<option value="${f.key}">${f.label} (${f.key})</option>`;
            });
            sel.innerHTML = html;
        });
    }

    // ── 7. Execute Comparison AJAX ──
    function runComparisonExecution(e) {
        e.preventDefault();

        const secondaryId = document.getElementById('compSecondaryId').value;
        const sourceType = document.getElementById('compPrimarySourceType').value;
        const systemSourceKey = document.getElementById('compSystemSourceKey').value;

        const keyFields = [];
        document.querySelectorAll('.comp-key-checkbox:checked').forEach(cb => {
            keyFields.push(cb.value);
        });

        if (keyFields.length === 0) {
            alert('يرجى تحديد حقل مفتاح واحد على الأقل للمطابقة.');
            return;
        }

        const fieldMapping = {};
        document.querySelectorAll('.comp-field-map-select').forEach(sel => {
            fieldMapping[sel.getAttribute('data-sec-col')] = sel.value;
        });

        const btn = document.getElementById('btnRunComparison');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> جاري مقارنة ومطابقة البيانات...';

        fetch("{{ route('differentiation.execute-comparison') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                secondary_id: secondaryId,
                primary_source_type: sourceType,
                system_source_key: systemSourceKey,
                key_fields: keyFields,
                field_mapping: fieldMapping
            })
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-play me-2"></i> تنفيذ عملية المطابقة والمقارنة الآن';

            if (data.success) {
                currentComparisonResults = data.results;
                currentSavedMatchId = data.saved_match_id;

                renderComparisonSummary(data.summary);
                document.getElementById('comparisonResultsDashboard').classList.remove('hidden');
                switchResultTab('matched');
            } else {
                alert('خطأ في عملية المقارنة: ' + data.message);
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-play me-2"></i> تنفيذ عملية المطابقة والمقارنة الآن';
            alert('حدث خطأ أثناء إجراء المقارنة.');
            console.error(err);
        });
    }

    function renderComparisonSummary(summary) {
        document.getElementById('resCountMatched').textContent = summary.matched_count;
        document.getElementById('resCountDifferent').textContent = summary.different_count;
        document.getElementById('resCountInSecOnly').textContent = summary.in_sec_only_count;
        document.getElementById('resCountInPriOnly').textContent = summary.in_pri_only_count;
        document.getElementById('resCountIncomplete').textContent = summary.incomplete_count;
    }

    // ── 8. Switch Result Tab & Render Details Table ──
    function switchResultTab(category) {
        activeResultCategory = category;
        const titleEl = document.getElementById('resultTabTitle');
        const container = document.getElementById('resultsTableContainer');
        const exportBtn = document.getElementById('btnExportCategory');

        exportBtn.href = `{{ route('differentiation.export-results') }}?saved_match_id=${currentSavedMatchId}&category=${category}`;

        let categoryTitle = 'تفاصيل السجلات المتطابقة';
        if (category === 'different') categoryTitle = 'تفاصيل السجلات المختلفة مع الفروقات';
        if (category === 'in_sec_only') categoryTitle = 'سجلات موجودة بالثانوية فقط (غير موجودة بالرئيسية)';
        if (category === 'in_pri_only') categoryTitle = 'سجلات موجودة بالرئيسية فقط (غير موجودة بالثانوية)';
        if (category === 'incomplete') categoryTitle = 'سجلات ذات بيانات ناقصة';

        titleEl.innerHTML = `<span class="w-2.5 h-5 bg-brand-600 rounded-full me-2"></span> ${categoryTitle}`;

        const items = currentComparisonResults[category] || [];
        if (items.length === 0) {
            container.innerHTML = '<div class="text-center py-8 text-slate-400">لا توجد سجلات في هذا التصنيف.</div>';
            return;
        }

        let html = '<table class="w-full text-right border-collapse diff-table"><thead><tr><th>#</th>';

        if (category === 'matched' || category === 'in_sec_only') {
            const firstRow = items[0].secondary_row || {};
            Object.keys(firstRow).forEach(k => html += `<th>${k}</th>`);
            html += '</tr></thead><tbody>';
            items.forEach((item, idx) => {
                html += `<tr><td class="font-mono text-xs">${item.secondary_index}</td>`;
                Object.keys(firstRow).forEach(k => html += `<td class="text-xs">${item.secondary_row[k] || '-'}</td>`);
                html += '</tr>';
            });
        } else if (category === 'in_pri_only') {
            const firstRow = items[0].primary_row || {};
            Object.keys(firstRow).forEach(k => html += `<th>${k}</th>`);
            html += '</tr></thead><tbody>';
            items.forEach((item, idx) => {
                html += `<tr><td class="font-mono text-xs">${item.primary_index}</td>`;
                Object.keys(firstRow).forEach(k => html += `<td class="text-xs">${item.primary_row[k] || '-'}</td>`);
                html += '</tr>';
            });
        } else if (category === 'different') {
            html += '<th>رقم السجل الثانوي</th><th>رقم السجل الرئيسي</th><th>تفاصيل الفروقات والاختلافات</th></tr></thead><tbody>';
            items.forEach(item => {
                let diffText = '';
                item.differences.forEach(d => {
                    diffText += `<div class="mb-1 text-xs"><span class="font-bold text-slate-700 dark:text-slate-300">[${d.field}]:</span> القيمة الرئيسية: <span class="diff-highlight">${d.primary_val}</span> مقابل الثانوية: <span class="diff-highlight">${d.secondary_val}</span></div>`;
                });
                html += `<tr>
                    <td class="font-mono text-xs font-bold">${item.secondary_index}</td>
                    <td class="font-mono text-xs font-bold">${item.primary_index}</td>
                    <td>${diffText}</td>
                </tr>`;
            });
        } else if (category === 'incomplete') {
            html += '<th>رقم السجل</th><th>سبب النقص</th><th>بيانات السجل</th></tr></thead><tbody>';
            items.forEach(item => {
                html += `<tr>
                    <td class="font-mono text-xs">${item.secondary_index}</td>
                    <td class="text-xs text-rose-600 font-bold">${item.reason}</td>
                    <td class="text-xs font-mono">${JSON.stringify(item.secondary_row)}</td>
                </tr>`;
            });
        }

        html += '</tbody></table>';
        container.innerHTML = html;
    }

    // ── 9. Dataset View, Edit, Delete Actions ──
    function startComparisonWithDataset(id) {
        switchTab('comparison');
        document.getElementById('compSecondaryId').value = id;
        onSecondaryDatasetSelected();
    }

    function viewSecondaryDataset(id) {
        fetch(`{{ url('/differentiation/secondary') }}/${id}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById('viewModalTitle').textContent = 'معاينة بيانات: ' + data.table.name;
                const container = document.getElementById('viewModalTableContainer');

                let html = '<table class="w-full text-right border-collapse diff-table"><thead><tr><th>#</th>';
                data.headers.forEach(h => html += `<th>${h}</th>`);
                html += '</tr></thead><tbody>';

                data.records.forEach((row, idx) => {
                    html += `<tr><td class="font-mono text-xs">${idx + 1}</td>`;
                    data.headers.forEach(h => html += `<td class="text-xs">${row[h] || '-'}</td>`);
                    html += '</tr>';
                });
                html += '</tbody></table>';
                container.innerHTML = html;

                document.getElementById('viewDatasetModal').classList.remove('hidden');
            }
        });
    }

    function closeViewDatasetModal() {
        document.getElementById('viewDatasetModal').classList.add('hidden');
    }

    function openEditDatasetModal(id, name, source) {
        document.getElementById('editDatasetId').value = id;
        document.getElementById('editDatasetName').value = name;
        document.getElementById('editDatasetSource').value = source || '';
        document.getElementById('editDatasetModal').classList.remove('hidden');
    }

    function closeEditDatasetModal() {
        document.getElementById('editDatasetModal').classList.add('hidden');
    }

    function submitEditDataset(e) {
        e.preventDefault();
        const id = document.getElementById('editDatasetId').value;
        const name = document.getElementById('editDatasetName').value;
        const source = document.getElementById('editDatasetSource').value;

        fetch(`{{ url('/differentiation/secondary') }}/${id}/update`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ name, source })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                closeEditDatasetModal();
                window.location.reload();
            } else {
                alert('خطأ: ' + data.message);
            }
        });
    }

    function deleteSecondaryDataset(id) {
        if (!confirm('هل أنت تأكد من رغبتك في حذف مجموعة البيانات الثانوية هذه نهائياً؟')) return;

        fetch(`{{ url('/differentiation/secondary') }}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.reload();
            } else {
                alert('خطأ أثناء الحذف: ' + data.message);
            }
        });
    }
</script>
