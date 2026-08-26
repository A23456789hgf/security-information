@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8" dir="rtl" id="analysis-page">
    <!-- Header Controls -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <div class="flex items-center gap-3 text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">
                <a href="{{ route('data.differences.index') }}" class="hover:text-blue-600 transition-colors">نظام التمايز</a>
                <i class="fas fa-chevron-left text-[10px]"></i>
                <span>تفاصيل المطابقة</span>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight flex items-center gap-3">
                <i class="fas fa-file-invoice text-blue-600"></i>
                نتائج مطابقة: {{ $analysis->filename }}
            </h1>
            <p class="text-slate-500 mt-1 text-sm font-semibold">تاريخ البدء: {{ $analysis->created_at->format('Y-m-d H:i:s') }}</p>
        </div>

        <div class="flex items-center gap-3">
            @if($analysis->status === 'completed')
                <a href="{{ route('data.differences.export-excel', $analysis->id) }}" 
                   class="flex items-center gap-2 px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl transition-all duration-300 shadow shadow-emerald-100 hover:shadow-lg hover:shadow-emerald-200">
                    <i class="fas fa-file-excel text-lg"></i>
                    <span>تصدير Excel</span>
                </a>
                
                <a href="{{ route('data.differences.print-pdf', $analysis->id) }}" target="_blank"
                   class="flex items-center gap-2 px-5 py-3 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-2xl transition-all duration-300 shadow hover:shadow-lg">
                    <i class="fas fa-print text-lg"></i>
                    <span>طباعة الملف PDF</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Active Process Polling Panel -->
    <div id="progress-panel" class="mb-8 p-6 bg-white border border-slate-100 rounded-3xl shadow-sm {{ in_array($analysis->status, ['pending', 'processing']) ? '' : 'hidden' }}">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">حالة المعالجة الحالية</span>
                <h3 id="progress-status" class="text-lg font-extrabold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-spinner fa-spin text-blue-600"></i>
                    <span>جاري تحليل البيانات ومطابقة الأسماء...</span>
                </h3>
            </div>
            <span id="progress-percentage-text" class="text-2xl font-black text-blue-600">0%</span>
        </div>

        <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden mb-6">
            <div id="progress-bar-fill" class="bg-blue-600 h-3 rounded-full transition-all duration-500 animate-pulse" style="width: 0%"></div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-slate-50/50 p-4 rounded-2xl border border-slate-100 text-center">
                <span class="text-slate-400 text-xxs font-bold block mb-1">إجمالي الصفوف</span>
                <span id="progress-total-rows" class="text-base font-black text-slate-800">---</span>
            </div>
            <div class="bg-slate-50/50 p-4 rounded-2xl border border-slate-100 text-center">
                <span class="text-slate-400 text-xxs font-bold block mb-1">صفوف معالجة</span>
                <span id="progress-processed-rows" class="text-base font-black text-slate-800">---</span>
            </div>
            <div class="bg-slate-50/50 p-4 rounded-2xl border border-slate-100 text-center col-span-2">
                <span class="text-slate-400 text-xxs font-bold block mb-1">معدل المعالجة</span>
                <span id="progress-rate" class="text-base font-black text-blue-600">جاري الحساب...</span>
            </div>
        </div>

        <!-- Live Terminal logs -->
        <h4 class="text-xs font-bold text-slate-500 mb-3 flex items-center gap-2">
            <i class="fas fa-terminal"></i>
            خطوات المعالجة المباشرة
        </h4>
        <div id="live-logs" class="bg-slate-950 text-slate-200 p-4 rounded-2xl font-mono text-xs overflow-y-auto max-h-[150px] leading-relaxed flex flex-col gap-2 shadow-inner border border-slate-900">
            <div class="text-slate-500">في انتظار تحديثات المعالجة الفورية...</div>
        </div>
    </div>

    <!-- Overview Statistics Dashboard -->
    <div id="stats-dashboard" class="grid grid-cols-2 lg:grid-cols-5 gap-6 mb-8 {{ $analysis->status === 'completed' ? '' : 'hidden' }}">
        @php
            $stats = $analysis->stats ?? ['matches' => 0, 'similarities' => 0, 'duplicates' => 0, 'not_found' => 0];
            $total = ($stats['matches'] + $stats['similarities'] + $stats['duplicates'] + $stats['not_found']) ?: 1;
            $matchPercent = round((($stats['matches'] + $stats['similarities']) / $total) * 100, 2);
        @endphp
        
        <!-- Overall Match % -->
        <div class="col-span-2 lg:col-span-1 bg-gradient-to-br from-blue-600 to-indigo-700 text-white rounded-3xl p-6 shadow-md flex flex-col justify-between">
            <span class="font-bold text-xs uppercase tracking-wider opacity-80">النسبة الإجمالية للتطابق</span>
            <div class="my-4">
                <span id="stat-match-percent" class="text-4xl font-black block tracking-tight">{{ $matchPercent }}%</span>
                <span class="text-xxs opacity-70 mt-1 block">تطابق قوي + تطابق تام</span>
            </div>
            <div class="w-full bg-white/20 h-1.5 rounded-full overflow-hidden">
                <div id="stat-match-bar" class="bg-white h-1.5 rounded-full" style="width: {{ $matchPercent }}%"></div>
            </div>
        </div>

        <!-- Matches (Green) -->
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm flex flex-col justify-between hover:shadow transition-shadow">
            <div class="flex justify-between items-start">
                <span class="font-bold text-xs text-slate-400">مطابقة تامة</span>
                <span class="w-7 h-7 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center text-xs"><i class="fas fa-check"></i></span>
            </div>
            <div class="mt-4">
                <span id="stat-matches" class="text-3xl font-black text-slate-800 block tracking-tight">{{ $stats['matches'] }}</span>
                <span class="text-xxs text-emerald-600 font-bold mt-1 block">تطابق 100% بالاسم والهوية</span>
            </div>
        </div>

        <!-- Similarities (Yellow) -->
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm flex flex-col justify-between hover:shadow transition-shadow">
            <div class="flex justify-between items-start">
                <span class="font-bold text-xs text-slate-400">تشابه قوي</span>
                <span class="w-7 h-7 bg-amber-50 text-amber-600 rounded-full flex items-center justify-center text-xs"><i class="fas fa-adjust"></i></span>
            </div>
            <div class="mt-4">
                <span id="stat-similarities" class="text-3xl font-black text-slate-800 block tracking-tight">{{ $stats['similarities'] }}</span>
                <span class="text-xxs text-amber-600 font-bold mt-1 block">اختلاف ترتيب أو حرف إملائي</span>
            </div>
        </div>

        <!-- Duplicates (Orange) -->
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm flex flex-col justify-between hover:shadow transition-shadow">
            <div class="flex justify-between items-start">
                <span class="font-bold text-xs text-slate-400">تكرار بالنظام</span>
                <span class="w-7 h-7 bg-orange-50 text-orange-600 rounded-full flex items-center justify-center text-xs"><i class="fas fa-copy"></i></span>
            </div>
            <div class="mt-4">
                <span id="stat-duplicates" class="text-3xl font-black text-slate-800 block tracking-tight">{{ $stats['duplicates'] }}</span>
                <span class="text-xxs text-orange-600 font-bold mt-1 block">مطابقة مع عدة سجلات بالنظام</span>
            </div>
        </div>

        <!-- Not Found (Red) -->
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm flex flex-col justify-between hover:shadow transition-shadow">
            <div class="flex justify-between items-start">
                <span class="font-bold text-xs text-slate-400">غير موجود</span>
                <span class="w-7 h-7 bg-rose-50 text-rose-600 rounded-full flex items-center justify-center text-xs"><i class="fas fa-times"></i></span>
            </div>
            <div class="mt-4">
                <span id="stat-not-found" class="text-3xl font-black text-slate-800 block tracking-tight">{{ $stats['not_found'] }}</span>
                <span class="text-xxs text-rose-600 font-bold mt-1 block">لا يوجد أي تشابهات مقبولة</span>
            </div>
        </div>
    </div>

    <!-- Tabular Interactive Grid -->
    <div id="results-card" class="bg-white border border-slate-100 rounded-3xl shadow-sm overflow-hidden {{ $analysis->status === 'completed' ? '' : 'hidden' }}">
        
        <!-- Controls panel: Search, filters and count -->
        <div class="p-6 bg-slate-50/50 border-b border-slate-100">
            <form action="{{ route('data.differences.show', $analysis->id) }}" method="GET" class="flex flex-col lg:flex-row gap-4 items-stretch lg:items-center">
                <!-- Text Search -->
                <div class="flex-grow relative">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="البحث بالاسم المستورد أو الاسم المطابق..." 
                           class="w-full pr-11 pl-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 placeholder-slate-400 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                    <i class="fas fa-search absolute right-4 top-3.5 text-slate-400 text-base"></i>
                </div>

                <!-- Status Filter -->
                <div class="w-full lg:w-48">
                    <select name="status" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                        <option value="">جميع الحالات</option>
                        <option value="match" {{ request('status') === 'match' ? 'selected' : '' }}>مطابقة تامة</option>
                        <option value="similarity" {{ request('status') === 'similarity' ? 'selected' : '' }}>تشابه قوي</option>
                        <option value="duplicate" {{ request('status') === 'duplicate' ? 'selected' : '' }}>تكرار بالنظام</option>
                        <option value="not_found" {{ request('status') === 'not_found' ? 'selected' : '' }}>غير موجود</option>
                    </select>
                </div>

                <!-- Target Registry Filter -->
                <div class="w-full lg:w-56">
                    <select name="target" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                        <option value="">كافة مستهدفات البحث</option>
                        @foreach($targets as $t)
                            <option value="{{ $t->id }}" {{ request('target') == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Action Button -->
                <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl transition-all duration-300 shadow hover:shadow-md flex items-center justify-center gap-2">
                    <i class="fas fa-filter"></i>
                    <span>تصفية وتحديث</span>
                </button>

                @if(request()->anyFilled(['search', 'status', 'target']))
                    <a href="{{ route('data.differences.show', $analysis->id) }}" class="px-5 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-2xl text-center text-sm transition-all duration-300">إلغاء الفلاتر</a>
                @endif
            </form>
        </div>

        <!-- Table Grid -->
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-slate-50/20 border-b border-slate-100 text-slate-400 text-xs font-bold uppercase tracking-wider">
                        <th class="py-4 pr-6">المستهدف</th>
                        <th class="py-4">الجدول</th>
                        <th class="py-4">الاسم المستورد (الملف)</th>
                        <th class="py-4">الاسم المطابق (النظام)</th>
                        <th class="py-4 text-center">نسبة المطابقة</th>
                        <th class="py-4 text-center">تكرار</th>
                        <th class="py-4 text-center">الحالة</th>
                        <th class="py-4 text-center">حالة السجل</th>
                        <th class="py-4 pl-6 text-center">الخيارات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm font-semibold text-slate-700">
                    @if($results->isEmpty())
                        <tr>
                            <td colspan="9" class="py-12 text-center text-slate-400">
                                <i class="fas fa-folder-open text-4xl mb-3 text-slate-200"></i>
                                <span class="block font-bold">لا توجد نتائج مطابقة للفلاتر المحددة</span>
                            </td>
                        </tr>
                    @else
                        @foreach($results as $item)
                            <tr class="hover:bg-slate-50/40 transition-colors duration-200">
                                <td class="py-4 pr-6">
                                    <span class="px-3 py-1 text-xxs font-extrabold rounded-full border shadow-sm
                                        {{ $item->source_page_type === 'static' ? 'bg-blue-50 text-blue-700 border-blue-150' : '' }}
                                        {{ $item->source_page_type === 'dynamic' ? 'bg-purple-50 text-purple-700 border-purple-150' : '' }}
                                        {{ $item->source_page_type === 'custom_table' ? 'bg-slate-50 text-slate-700 border-slate-150' : '' }}
                                    ">
                                        {{ $item->source_target_name ?: '---' }}
                                    </span>
                                </td>
                                <td class="py-4 text-xs text-slate-400 font-mono">{{ $item->source_table ?: '---' }}</td>
                                <td class="py-4 font-bold text-slate-800">{{ $item->external_name }}</td>
                                <td class="py-4 text-slate-600 font-bold">{{ $item->matching_name ?: '---' }}</td>
                                <td class="py-4 text-center">
                                    @if($item->matching_name)
                                        <span class="px-2.5 py-1 rounded-lg font-bold text-xs
                                            {{ $item->similarity_percentage >= 95 ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}
                                        ">
                                            {{ $item->similarity_percentage }}%
                                        </span>
                                    @else
                                        <span class="text-slate-300 font-bold">0.00%</span>
                                    @endif
                                </td>
                                <td class="py-4 text-center">
                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded-lg text-xs">{{ $item->occurrences_count }}</span>
                                </td>
                                <td class="py-4 text-center">
                                    @if($item->status === 'match')
                                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-150 rounded-full text-xs font-bold">مطابقة تامة</span>
                                    @elseif($item->status === 'similarity')
                                        <span class="px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-150 rounded-full text-xs font-bold">تشابه قوي</span>
                                    @elseif($item->status === 'duplicate')
                                        <span class="px-2.5 py-1 bg-orange-50 text-orange-700 border border-orange-150 rounded-full text-xs font-bold">تكرار بالنظام</span>
                                    @else
                                        <span class="px-2.5 py-1 bg-rose-50 text-rose-700 border border-rose-150 rounded-full text-xs font-bold">غير موجود</span>
                                    @endif
                                </td>
                                <td class="py-4 text-center">
                                    @if($item->record_status)
                                        <span class="px-2 py-0.5 rounded-lg text-xxs font-bold border border-slate-150 bg-slate-50 text-slate-600">{{ $item->record_status }}</span>
                                    @else
                                        <span class="text-slate-300">---</span>
                                    @endif
                                </td>
                                <td class="py-4 pl-6 text-center">
                                    <button onclick="openDetailModal({{ $item->id }})" 
                                            class="p-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-xl transition-all duration-300 hover:scale-105 shadow-sm">
                                        <i class="fas fa-search-plus"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($results->isNotEmpty())
            <div class="p-6 bg-slate-50/20 border-t border-slate-100">
                {{ $results->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Interactive Detail Modal -->
<div id="detail-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden animate-fade-in" dir="rtl">
    <div class="bg-white w-full max-w-4xl rounded-3xl shadow-xl border border-slate-100 overflow-hidden max-h-[90vh] flex flex-col scale-95 transition-all duration-300" id="modal-container">
        <!-- Modal Header -->
        <div class="px-6 py-5 bg-slate-50 border-b border-slate-100 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                    <i class="fas fa-info-circle text-blue-600"></i>
                    <span>تفاصيل وتحليل التباين للسجل</span>
                </h3>
                <p class="text-slate-400 text-xxs font-bold mt-1">فحص التماثل وحركات الهجاء والمقارنة بالحقول الفرعية.</p>
            </div>
            <button onclick="closeDetailModal()" class="w-9 h-9 bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-full flex items-center justify-center transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Modal Body (Scrollable) -->
        <div class="p-6 overflow-y-auto flex-grow space-y-6">
            <!-- Dynamic Side-by-Side Diff Highlighter -->
            <div class="bg-slate-50 border border-slate-100 rounded-2xl p-5">
                <h4 class="text-xs font-black text-slate-500 mb-4 uppercase tracking-wider">التباين الإملائي المباشر (مقارنة الهجاء)</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-white p-4 rounded-xl border border-slate-100">
                        <span class="text-slate-400 text-xxs font-bold block mb-1">الاسم في الملف المستورد</span>
                        <div id="modal-diff-external" class="font-extrabold text-slate-800 text-base leading-relaxed">---</div>
                    </div>
                    <div class="bg-white p-4 rounded-xl border border-slate-100">
                        <span class="text-slate-400 text-xxs font-bold block mb-1">الاسم المطابق في النظام</span>
                        <div id="modal-diff-system" class="font-extrabold text-slate-800 text-base leading-relaxed">---</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Original System Record Data properties -->
                <div class="bg-white border border-slate-100 rounded-2xl p-5">
                    <h4 class="text-xs font-black text-slate-500 mb-4 uppercase tracking-wider">تفاصيل وحقول السجل المطابق بالنظام</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-right text-xs">
                            <tbody class="divide-y divide-slate-50" id="modal-system-fields">
                                <tr>
                                    <td class="py-2.5 text-slate-400 font-bold">يرجى الانتظار...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <a id="modal-original-link" href="#" target="_blank"
                       class="w-full mt-4 py-3 bg-blue-50 hover:bg-blue-100 text-blue-600 font-bold rounded-xl transition-all text-xs flex items-center justify-center gap-2">
                        <i class="fas fa-external-link-alt"></i>
                        <span>عرض وتعديل السجل الأصلي بالنظام</span>
                    </a>
                </div>

                <!-- Secondary Matches (All matches list) -->
                <div class="bg-white border border-slate-100 rounded-2xl p-5">
                    <h4 class="text-xs font-black text-slate-500 mb-4 uppercase tracking-wider flex justify-between items-center">
                        <span>سجلات أخرى مشابهة تم العثور عليها</span>
                        <span id="modal-match-count" class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded-full text-xxs font-bold">---</span>
                    </h4>
                    
                    <div class="space-y-3 max-h-[250px] overflow-y-auto pr-1" id="modal-other-matches">
                        <div class="text-slate-400 text-xs">لا توجد سجلات تشابه فرعية.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // 1. AJAX Progress Polling Implementation
    @if(in_array($analysis->status, ['pending', 'processing']))
        const pollingUrl = "{{ route('data.differences.progress', $analysis->id) }}";
        let startTime = Date.now();

        function pollProgress() {
            fetch(pollingUrl)
                .then(res => res.json())
                .then(data => {
                    // Update progress bar
                    document.getElementById('progress-bar-fill').style.width = data.progress + '%';
                    document.getElementById('progress-percentage-text').innerText = data.progress + '%';
                    
                    // Update counts
                    document.getElementById('progress-total-rows').innerText = data.total_rows || '---';
                    document.getElementById('progress-processed-rows').innerText = data.processed_rows || '---';
                    
                    // Calculate rate
                    if (data.processed_rows > 0) {
                        let elapsed = (Date.now() - startTime) / 1000; // seconds
                        let rate = Math.round(data.processed_rows / elapsed);
                        document.getElementById('progress-rate').innerText = `${rate} صف / ثانية`;
                    }

                    // Update live terminal logs
                    const logsContainer = document.getElementById('live-logs');
                    if (data.logs && data.logs.length > 0) {
                        logsContainer.innerHTML = '';
                        // Render in reverse to show latest at bottom or top
                        data.logs.forEach(log => {
                            let color = 'text-slate-200';
                            if (log.level === 'warning') color = 'text-amber-400';
                            if (log.level === 'error') color = 'text-rose-400';
                            
                            const div = document.createElement('div');
                            div.className = `flex gap-2 ${color}`;
                            div.innerHTML = `<span class="text-slate-600">[${log.time}]</span> <span>${log.message}</span>`;
                            logsContainer.appendChild(div);
                        });
                    }

                    // On completion, reload to display results
                    if (data.status === 'completed' || data.status === 'failed') {
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        setTimeout(pollProgress, 2000);
                    }
                })
                .catch(err => {
                    console.error("Polling error: ", err);
                    setTimeout(pollProgress, 4000);
                });
        }

        // Start Polling
        setTimeout(pollProgress, 1000);
    @endif

    // 2. Interactive Details Modal & String Diff Highlighter
    function openDetailModal(resultId) {
        const modal = document.getElementById('detail-modal');
        const container = document.getElementById('modal-container');
        
        // Show modal backdrop
        modal.classList.remove('hidden');
        setTimeout(() => {
            container.classList.remove('scale-95');
        }, 50);

        // Fetch detail properties
        fetch(`/data/differences/result/${resultId}/detail`)
            .then(res => res.json())
            .then(data => {
                // Dynamic diff highlighters
                const extVal = data.external_name;
                const sysVal = data.matching_name || '---';

                if (data.matching_name) {
                    const diffs = highlightStringDiff(extVal, sysVal);
                    document.getElementById('modal-diff-external').innerHTML = diffs.external;
                    document.getElementById('modal-diff-system').innerHTML = diffs.system;
                } else {
                    document.getElementById('modal-diff-external').innerText = extVal;
                    document.getElementById('modal-diff-system').innerText = '---';
                }

                // Render system record properties
                const fieldsTable = document.getElementById('modal-system-fields');
                fieldsTable.innerHTML = '';
                if (data.original_record_data && Object.keys(data.original_record_data).length > 0) {
                    for (const [key, value] of Object.entries(data.original_record_data)) {
                        // Skip system arrays / metadata objects to keep clean
                        if (typeof value === 'object' || key.startsWith('_') || key === 'differentiation_results' || key === 'column_headers') continue;
                        
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td class="py-2 text-slate-400 font-bold pr-2">${key}</td>
                            <td class="py-2 text-slate-800 font-bold">${value !== null ? value : '---'}</td>
                        `;
                        fieldsTable.appendChild(tr);
                    }
                } else {
                    fieldsTable.innerHTML = '<tr><td class="py-4 text-center text-slate-400 font-bold">لا توجد تفاصيل حقول بالنظام (سجل غير متوفر)</td></tr>';
                }

                // Render detail record link
                const linkBtn = document.getElementById('modal-original-link');
                if (data.original_url) {
                    linkBtn.setAttribute('href', data.original_url);
                    linkBtn.classList.remove('hidden');
                } else {
                    linkBtn.classList.add('hidden');
                }

                // Render matches list
                document.getElementById('modal-match-count').innerText = `${data.occurrences_count} مطابقات`;
                const otherContainer = document.getElementById('modal-other-matches');
                otherContainer.innerHTML = '';

                if (data.all_matches && data.all_matches.length > 0) {
                    data.all_matches.forEach(item => {
                        const rec = item.sys_record;
                        
                        let badgeColor = 'bg-rose-50 text-rose-700';
                        let labelAr = 'غير مطابق';
                        if (item.status === 'match') {
                            badgeColor = 'bg-emerald-50 text-emerald-700';
                            labelAr = 'مطابقة تامة';
                        } else if (item.status === 'similarity') {
                            badgeColor = 'bg-amber-50 text-amber-700';
                            labelAr = 'تشابه قوي';
                        } else if (item.status === 'duplicate') {
                            badgeColor = 'bg-orange-50 text-orange-700';
                            labelAr = 'تكرار';
                        }

                        const div = document.createElement('div');
                        div.className = 'p-3 bg-slate-50 border border-slate-100 rounded-xl flex justify-between items-center gap-4';
                        div.innerHTML = `
                            <div>
                                <span class="text-xs font-black text-slate-800 block mb-1">${rec.name || '---'}</span>
                                <span class="text-xxs text-slate-400 font-bold">${rec._source_page || '---'} (${rec._source_table || '---'})</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 text-xxs font-extrabold rounded-lg bg-blue-50 text-blue-700">${item.percentage}%</span>
                                <span class="px-2 py-0.5 text-xxs font-extrabold rounded-lg ${badgeColor}">${labelAr}</span>
                            </div>
                        `;
                        otherContainer.appendChild(div);
                    });
                } else {
                    otherContainer.innerHTML = '<div class="text-slate-400 text-xs py-4 text-center font-bold">لم يتم العثور على تشابهات إضافية.</div>';
                }
            })
            .catch(err => {
                console.error("Modal load error: ", err);
            });
    }

    function closeDetailModal() {
        const modal = document.getElementById('detail-modal');
        const container = document.getElementById('modal-container');
        
        container.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 150);
    }

    // Git-style Character-level Text Difference Highlighter
    function highlightStringDiff(ext, sys) {
        let extOut = '';
        let sysOut = '';

        // Simple character mismatch comparison
        let i = 0;
        let j = 0;
        const lenExt = ext.length;
        const lenSys = sys.length;

        while (i < lenExt || j < lenSys) {
            const charExt = i < lenExt ? ext[i] : null;
            const charSys = j < lenSys ? sys[j] : null;

            if (charExt === charSys) {
                if (charExt !== null) {
                    extOut += charExt;
                    sysOut += charSys;
                }
                i++;
                j++;
            } else {
                // Letter mismatch: Highlight deletions in red, additions in green
                if (charExt !== null) {
                    extOut += `<span class="bg-rose-100 text-rose-800 px-0.5 rounded">${charExt}</span>`;
                    i++;
                }
                if (charSys !== null) {
                    sysOut += `<span class="bg-emerald-100 text-emerald-800 px-0.5 rounded">${charSys}</span>`;
                    j++;
                }
            }
        }

        return {
            external: extOut,
            system: sysOut
        };
    }
</script>
@endsection
