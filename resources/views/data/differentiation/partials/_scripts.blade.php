<script>
// Global variables to hold active search results
window.activeGroupData = null;
window.activeGroupName = '';

function switchMainTab(tab) {
    document.getElementById('primaryTab').classList.toggle('active', tab === 'primary');
    document.getElementById('referenceTab').classList.toggle('active', tab === 'reference');
    document.getElementById('primaryContent').style.display = tab === 'primary' ? 'block' : 'none';
    document.getElementById('referenceContent').style.display = tab === 'reference' ? 'block' : 'none';
    
    if (tab === 'reference') {
        document.getElementById('detailPanel').classList.remove('active');
        document.querySelectorAll('.result-item').forEach(i => i.classList.remove('active'));
    }
}

function showModal(id) {
    document.getElementById(id).classList.add('active');
}

function closeModal(id) {
    document.getElementById(id).classList.remove('active');
}

function showAddColumnModal() {
    showModal('addColumnModal');
}

function addColumn() {
    const name = document.getElementById('newColumnName').value.trim();
    if (!name) return;

    fetch('{{ route("data.differentiation.add-column") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ name })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) location.reload();
    });
}

function integrateAll() {
    if (!confirm('هل تريد دمج جميع البيانات المرجعية في الجدول الرئيسي؟')) return;

    fetch('{{ route("data.differentiation.integrate-all") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(r => r.json())
    .then(data => {
        alert(data.message);
        if (data.success) location.reload();
    });
}

function integrateSelected(indices) {
    if (!confirm('هل تريد دمج السجلات المختارة؟')) return;

    fetch('{{ route("data.differentiation.integrate-selected") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ indices })
    })
    .then(r => r.json())
    .then(data => {
        alert(data.message);
        if (data.success) location.reload();
    });
}

// File Upload Handler with Elegant Animated Multi-step Progress Bar
document.getElementById('secondaryFile').addEventListener('change', function(e) {
    if (!e.target.files.length) return;
    
    // Reset steps UI in progress modal
    const steps = ['read', 'init', 'compare', 'results'];
    steps.forEach(s => {
        const item = document.getElementById(`step-${s}`);
        item.style.opacity = '0.5';
        item.classList.remove('active', 'completed');
        
        const icon = document.getElementById(`icon-${s}`);
        icon.className = 'far fa-circle me-2';
    });
    
    document.getElementById('progressBarFill').style.width = '0%';
    document.getElementById('progressPercent').textContent = '0%';
    document.getElementById('matchingSpinIcon').className = 'fas fa-sync fa-spin text-primary';
    document.getElementById('matchingProgressSubtitle').textContent = 'يرجى الانتظار، يتم الآن معالجة الملفات واحتساب التباين...';
    
    // Open the progress modal
    showModal('progressModal');
    
    // Simulate steps progress on client-side
    let currentPercent = 0;
    let progressTimer = null;
    
    const startSimulatedProgress = () => {
        progressTimer = setInterval(() => {
            if (currentPercent < 95) {
                currentPercent += Math.floor(Math.random() * 4) + 1; // Increment by 1-4%
                if (currentPercent > 95) currentPercent = 95;
                
                document.getElementById('progressBarFill').style.width = `${currentPercent}%`;
                document.getElementById('progressPercent').textContent = `${currentPercent}%`;
                
                // Transition through stages based on percentage
                if (currentPercent >= 0 && currentPercent < 25) {
                    setStepActive('read');
                } else if (currentPercent >= 25 && currentPercent < 50) {
                    setStepCompleted('read');
                    setStepActive('init');
                } else if (currentPercent >= 50 && currentPercent < 75) {
                    setStepCompleted('init');
                    setStepActive('compare');
                } else if (currentPercent >= 75) {
                    setStepCompleted('compare');
                    setStepActive('results');
                }
            }
        }, 150);
    };
    
    function setStepActive(stepId) {
        const item = document.getElementById(`step-${stepId}`);
        if (!item.classList.contains('active') && !item.classList.contains('completed')) {
            item.classList.add('active');
            item.style.opacity = '1';
            const icon = document.getElementById(`icon-${stepId}`);
            icon.className = 'fas fa-circle-notch fa-spin text-primary me-2';
        }
    }
    
    function setStepCompleted(stepId) {
        const item = document.getElementById(`step-${stepId}`);
        if (!item.classList.contains('completed')) {
            item.classList.remove('active');
            item.classList.add('completed');
            item.style.opacity = '0.9';
            const icon = document.getElementById(`icon-${stepId}`);
            icon.className = 'fas fa-check-circle text-success me-2';
        }
    }
    
    startSimulatedProgress();
    
    const formData = new FormData();
    formData.append('excel_file', e.target.files[0]);
    formData.append('_token', '{{ csrf_token() }}');
    
    fetch('{{ route("data.differentiation.analyze") }}', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        clearInterval(progressTimer);
        
        if (data.success) {
            // Speed up to 100% on success
            document.getElementById('progressBarFill').style.width = '100%';
            document.getElementById('progressPercent').textContent = '100%';
            steps.forEach(s => setStepCompleted(s));
            
            document.getElementById('matchingSpinIcon').className = 'fas fa-check-circle text-success';
            document.getElementById('matchingProgressSubtitle').textContent = 'اكتملت المطابقة بنجاح! جاري عرض النتائج...';
            
            setTimeout(() => {
                location.reload();
            }, 800);
        } else {
            closeModal('progressModal');
            alert('حدث خطأ في التحليل');
        }
    })
    .catch(err => {
        clearInterval(progressTimer);
        closeModal('progressModal');
        alert('خطأ في الاتصال بالسيرفر');
    });
});

// Click on result items to view details
document.querySelectorAll('.result-item').forEach(item => {
    item.addEventListener('click', function() {
        const type = this.dataset.type;
        const count = parseInt(this.querySelector('.result-count').textContent);
        
        if (count === 0) return;

        document.querySelectorAll('.result-item').forEach(i => i.classList.remove('active'));
        this.classList.add('active');
        
        loadDetail(type, this.querySelector('.result-label').textContent);
    });
});

function loadDetail(type, label) {
    fetch('{{ route("data.differentiation.detail") }}?type=' + type)
    .then(r => r.json())
    .then(data => {
        // Save globally for exports/printing
        window.activeGroupData = data;
        window.activeGroupName = label;
        
        displayDetail(data, label);
    });
}

function displayDetail(data, label) {
    document.getElementById('detailTitle').textContent = label + ' (' + data.details.length + ')';
    
    let html = '';
    
    // Add Category Integrate Button
    const secondaryIndices = data.details
        .filter(d => d.secondary_index !== null)
        .map(d => d.secondary_index);
    
    if (secondaryIndices.length > 0) {
        html += `
            <div class="action-bar">
                <button class="btn-action primary" onclick="integrateSelected([${secondaryIndices.join(',')}])">
                    <i class="fas fa-file-import"></i> دمج هذه الفئة (${secondaryIndices.length} سجل)
                </button>
            </div>
        `;
    }

    html += '<table>';
    
    // Headers
    html += '<thead><tr><th>الرقم</th>';
    if (data.primary_columns.length) {
        data.primary_columns.forEach(col => html += '<th>' + col + '</th>');
    }
    if (data.secondary_columns.length && data.type !== 'net') {
        html += '<th style="background:#ef4444">المصدر</th>';
        data.secondary_columns.forEach(col => html += '<th style="background:#b91c1c">' + col + '</th>');
    }
    html += '<th>تصدير</th>';
    html += '</tr></thead><tbody>';
    
    // Data rows
    data.details.forEach((detail, i) => {
        html += '<tr><td>' + (i + 1) + '</td>';
        
        if (detail.primary) {
            detail.primary.forEach(cell => html += '<td>' + (cell || '-') + '</td>');
        } else if (data.primary_columns.length) {
            data.primary_columns.forEach(() => html += '<td>-</td>');
        }
        
        if (detail.secondary && data.type !== 'net') {
            html += '<td style="background:#fee2e2">ثانوي</td>';
            detail.secondary.forEach(cell => html += '<td style="background:#fef2f2">' + (cell || '-') + '</td>');
        }
        
        // Row operations
        html += `<td>
            <button class="row-btn-action" onclick="exportIndividualResult(${i})">
                <i class="fas fa-file-csv"></i> تصدير
            </button>
        </td>`;
        
        html += '</tr>';
    });
    
    html += '</tbody></table>';
    
    document.getElementById('detailContent').innerHTML = html;
    document.getElementById('overviewPanel').style.display = 'none';
    document.getElementById('detailPanel').classList.add('active');
}

function showOverview() {
    document.getElementById('detailPanel').classList.remove('active');
    document.getElementById('overviewPanel').style.display = 'block';
    document.querySelectorAll('.result-item').forEach(i => i.classList.remove('active'));
    
    window.activeGroupData = null;
    window.activeGroupName = '';
}

// ----------------------------------------------------
// EXPORT & PRINT FUNCTIONS
// ----------------------------------------------------

/**
 * Downloads high-fidelity CSV files with UTF-8 Arabic support
 */
function downloadCSV(filename, csvData) {
    const blob = new Blob(["\uFEFF" + csvData], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement("a");
    if (link.download !== undefined) {
        const url = URL.createObjectURL(blob);
        link.setAttribute("href", url);
        link.setAttribute("download", filename);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
}

/**
 * Export matching results summary checklist from sidebar cards
 */
function exportAllResultsSummary() {
    let csv = "الفئة,العدد\r\n";
    document.querySelectorAll('.result-item').forEach(item => {
        const label = item.querySelector('.result-label').textContent.trim();
        const count = item.querySelector('.result-count').textContent.trim();
        csv += `"${label.replace(/"/g, '""')}",${count}\r\n`;
    });
    downloadCSV("ملخص_نتائج_المطابقة.csv", csv);
}

/**
 * Export active matching group results from details table
 */
function exportActiveGroup() {
    if (!window.activeGroupData) return;
    
    const data = window.activeGroupData;
    const headers = ["الرقم"];
    
    if (data.primary_columns.length) {
        data.primary_columns.forEach(col => headers.push(col));
    }
    if (data.secondary_columns.length && data.type !== 'net') {
        headers.push("المصدر");
        data.secondary_columns.forEach(col => headers.push(`ثانوي: ${col}`));
    }
    
    let csv = headers.map(h => `"${h.replace(/"/g, '""')}"`).join(",") + "\r\n";
    
    data.details.forEach((detail, idx) => {
        const row = [idx + 1];
        
        if (detail.primary) {
            detail.primary.forEach(cell => row.push(cell || '-'));
        } else if (data.primary_columns.length) {
            data.primary_columns.forEach(() => row.push('-'));
        }
        
        if (detail.secondary && data.type !== 'net') {
            row.push("ثانوي");
            detail.secondary.forEach(cell => row.push(cell || '-'));
        }
        
        csv += row.map(cell => `"${cell.toString().replace(/"/g, '""')}"`).join(",") + "\r\n";
    });
    
    const sanitizedFilename = `مطابقة_بيانات_${window.activeGroupName.replace(/\s+/g, '_')}.csv`;
    downloadCSV(sanitizedFilename, csv);
}

/**
 * Export a single individual matched row record
 */
function exportIndividualResult(index) {
    if (!window.activeGroupData || !window.activeGroupData.details[index]) return;
    
    const data = window.activeGroupData;
    const detail = data.details[index];
    const headers = [];
    const row = [];
    
    if (detail.primary) {
        data.primary_columns.forEach(col => headers.push(`رئيسي: ${col}`));
        detail.primary.forEach(cell => row.push(cell || '-'));
    }
    if (detail.secondary && data.type !== 'net') {
        data.secondary_columns.forEach(col => headers.push(`ثانوي: ${col}`));
        detail.secondary.forEach(cell => row.push(cell || '-'));
    }
    
    let csv = headers.map(h => `"${h.replace(/"/g, '""')}"`).join(",") + "\r\n";
    csv += row.map(cell => `"${cell.toString().replace(/"/g, '""')}"`).join(",") + "\r\n";
    
    downloadCSV(`سجل_مطابقة_فردي_${index + 1}.csv`, csv);
}

/**
 * Opens printable layout in clean print template window
 */
function printActiveGroup() {
    if (!window.activeGroupData) return;
    
    const data = window.activeGroupData;
    const printWindow = window.open('', '_blank', 'width=1000,height=700');
    
    let headersHtml = '<th>#</th>';
    if (data.primary_columns.length) {
        data.primary_columns.forEach(col => headersHtml += `<th>${col}</th>`);
    }
    if (data.secondary_columns.length && data.type !== 'net') {
        headersHtml += '<th style="background:#f3f4f6">المصدر</th>';
        data.secondary_columns.forEach(col => headersHtml += `<th style="background:#fee2e2">${col}</th>`);
    }
    
    let rowsHtml = '';
    data.details.forEach((detail, i) => {
        rowsHtml += '<tr>';
        rowsHtml += `<td>${i + 1}</td>`;
        
        if (detail.primary) {
            detail.primary.forEach(cell => rowsHtml += `<td>${cell || '-'}</td>`);
        } else if (data.primary_columns.length) {
            data.primary_columns.forEach(() => rowsHtml += '<td>-</td>');
        }
        
        if (detail.secondary && data.type !== 'net') {
            rowsHtml += '<td style="background:#f9fafb; font-weight:bold;">ثانوي</td>';
            detail.secondary.forEach(cell => rowsHtml += `<td style="background:#fef2f2">${cell || '-'}</td>`);
        }
        rowsHtml += '</tr>';
    });
    
    const printContent = `
        <!DOCTYPE html>
        <html dir="rtl" lang="ar">
        <head>
            <meta charset="utf-8">
            <title>تقرير مطابقة البيانات - ${window.activeGroupName}</title>
            <style>
                body {
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    padding: 30px;
                    color: #333;
                    background: #fff;
                }
                .report-header {
                    border-bottom: 2px solid #1a1c2e;
                    padding-bottom: 15px;
                    margin-bottom: 30px;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }
                .title {
                    font-size: 1.6rem;
                    font-weight: bold;
                    color: #1a1c2e;
                    margin: 0;
                }
                .meta {
                    font-size: 0.9rem;
                    color: #666;
                    text-align: left;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 20px;
                    font-size: 0.85rem;
                }
                th {
                    background: #1a1c2e;
                    color: #fff;
                    padding: 10px;
                    border: 1px solid #ddd;
                    text-align: right;
                }
                td {
                    padding: 8px 10px;
                    border: 1px solid #ddd;
                    text-align: right;
                }
                tr:nth-child(even) td {
                    background: #fcfcfc;
                }
                @media print {
                    .no-print { display: none; }
                    body { padding: 0; }
                }
            </style>
        </head>
        <body>
            <div class="report-header">
                <div>
                    <h1 class="title">تقرير مطابقة البيانات</h1>
                    <div style="font-size: 1.1rem; color: #555; margin-top: 5px;">فئة المطابقة: ${window.activeGroupName}</div>
                </div>
                <div class="meta">
                    <div>التاريخ: ${new Date().toLocaleDateString('ar-EG')}</div>
                    <div>إجمالي السجلات: ${data.details.length}</div>
                </div>
            </div>
            
            <table>
                <thead>
                    <tr>${headersHtml}</tr>
                </thead>
                <tbody>
                    ${rowsHtml}
                </tbody>
            </table>
            
            <script>
                window.onload = function() {
                    window.print();
                    setTimeout(function() { window.close(); }, 500);
                };
            <\/script>
        </body>
        </html>
    `;
    
    printWindow.document.open();
    printWindow.document.write(printContent);
    printWindow.document.close();
}
</script>
