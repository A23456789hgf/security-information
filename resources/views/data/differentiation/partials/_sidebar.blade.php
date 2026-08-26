<div class="sidebar-panel">
    <div class="sidebar-title">
        <i class="fas fa-chart-bar me-2"></i>نتائج المطابقة
    </div>

    <div class="upload-secondary" onclick="document.getElementById('secondaryFile').click()">
        <i class="fas fa-file-upload"></i>
        <div>رفع بيانات ثانوية للمقارنة</div>
        <small style="opacity:0.6">xlsx, xls, csv</small>
    </div>
    <input type="file" id="secondaryFile" accept=".xlsx,.xls,.csv" style="display:none">

    @if($results)
    <div class="category-header">التطابق الكامل</div>
    <div class="result-item" data-type="one_to_one">
        <span class="result-label">{{ $results['one_to_one']['label'] }}</span>
        <span class="result-count {{ $results['one_to_one']['count'] == 0 ? 'zero' : '' }}">
            {{ $results['one_to_one']['count'] }}
        </span>
    </div>

    <div class="category-header">التطابق الجزئي</div>
    @foreach(['two_total', 'three_total', 'four_total', 'five_total', 'six_total'] as $key)
    <div class="result-item" data-type="{{ $key }}">
        <span class="result-label">{{ $results[$key]['label'] }}</span>
        <span class="result-count {{ $results[$key]['count'] == 0 ? 'zero' : '' }}">
            {{ $results[$key]['count'] }}
        </span>
    </div>
    @endforeach

    <div class="category-header">الاختلافات</div>
    <div class="result-item" data-type="letter_difference">
        <span class="result-label">{{ $results['letter_difference']['label'] }}</span>
        <span class="result-count {{ $results['letter_difference']['count'] == 0 ? 'zero' : '' }}">
            {{ $results['letter_difference']['count'] }}
        </span>
    </div>
    <div class="result-item" data-type="total_variations">
        <span class="result-label">{{ $results['total_variations']['label'] }}</span>
        <span class="result-count {{ $results['total_variations']['count'] == 0 ? 'zero' : '' }}">
            {{ $results['total_variations']['count'] }}
        </span>
    </div>
    <div class="result-item" data-type="last">
        <span class="result-label">{{ $results['last']['label'] }}</span>
        <span class="result-count {{ $results['last']['count'] == 0 ? 'zero' : '' }}">
            {{ $results['last']['count'] }}
        </span>
    </div>

    <div class="category-header">مع اختلاف أخير</div>
    @foreach(['single_last_diff', 'double_last_diff', 'triple_last_diff', 'quadruple_last_diff', 'centruple_last_diff', 'sextuple_last_diff'] as $key)
    <div class="result-item" data-type="{{ $key }}">
        <span class="result-label">{{ $results[$key]['label'] }}</span>
        <span class="result-count {{ $results[$key]['count'] == 0 ? 'zero' : '' }}">
            {{ $results[$key]['count'] }}
        </span>
    </div>
    @endforeach

    <div class="category-header">الصافي</div>
    <div class="result-item" data-type="net">
        <span class="result-label">{{ $results['net']['label'] }}</span>
        <span class="result-count {{ $results['net']['count'] == 0 ? 'zero' : '' }}">
            {{ $results['net']['count'] }}
        </span>
    </div>

    <button class="btn-action primary w-100 mt-3 justify-content-center" onclick="exportAllResultsSummary()" style="background: #4cc9f0; color: #1a1c2e; border: none; font-weight: 700;">
        <i class="fas fa-download me-1"></i> تصدير ملخص كافة النتائج
    </button>
    @else
    <div class="empty-results">
        <i class="fas fa-chart-pie"></i>
        <div>لا توجد نتائج</div>
        <small>قم برفع ملف للمقارنة</small>
    </div>
    @endif
</div>
