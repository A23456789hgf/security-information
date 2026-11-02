<div class="space-y-6">

    {{-- TAB 1: SECONDARY DATASETS MANAGER --}}
    <div id="tabContentSecondary" class="space-y-6">
        <div class="diff-card p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="font-bold text-lg text-slate-900 dark:text-white">مجموعات البيانات الثانوية المستوردة</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">قائمة بجميع مجموعات البيانات الثانوية المسجلة بشكل مستقل ومحفوظة للنظام.</p>
                </div>
                <button onclick="openUploadModal('secondary')" class="bg-brand-600 hover:bg-brand-700 text-white px-4 py-2 rounded-xl text-xs font-bold transition-all inline-flex items-center gap-2">
                    <i class="fas fa-file-upload"></i>
                    <span>استيراد مجموعة ثانوية جديدة</span>
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-right border-collapse diff-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>اسم المجموعة الثانوية</th>
                            <th>المصدر</th>
                            <th>عدد السجلات</th>
                            <th>المستخدم</th>
                            <th>تاريخ الاستيراد</th>
                            <th class="text-left">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="secondaryDatasetsTableBody">
                        @forelse($secondaryDatasets as $index => $dataset)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="font-mono text-xs font-bold">{{ $index + 1 }}</td>
                                <td class="font-bold text-slate-900 dark:text-white">{{ $dataset->name }}</td>
                                <td><span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">{{ $dataset->source ?: 'مستورد' }}</span></td>
                                <td class="font-bold text-brand-600 dark:text-brand-400">{{ number_format($dataset->records_count) }}</td>
                                <td class="text-xs text-slate-500">{{ $dataset->user->name ?? 'غير محدد' }}</td>
                                <td class="text-xs text-slate-500 font-mono">{{ $dataset->created_at->format('Y-m-d H:i') }}</td>
                                <td class="text-left">
                                    <div class="flex items-center justify-end gap-2">
                                        <button onclick="viewSecondaryDataset({{ $dataset->id }})" class="p-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-500/10 dark:text-blue-400 font-semibold text-xs transition-colors flex items-center gap-1">
                                            <i class="fas fa-eye"></i> <span>معاينة</span>
                                        </button>

                                        <button onclick="startComparisonWithDataset({{ $dataset->id }})" class="p-2 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-400 font-semibold text-xs transition-colors flex items-center gap-1">
                                            <i class="fas fa-code-compare"></i> <span>مقارنة</span>
                                        </button>

                                        <button onclick="openEditDatasetModal({{ $dataset->id }}, '{{ addslashes($dataset->name) }}', '{{ addslashes($dataset->source) }}')" class="p-2 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 dark:bg-amber-500/10 dark:text-amber-400 font-semibold text-xs transition-colors">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <button onclick="deleteSecondaryDataset({{ $dataset->id }})" class="p-2 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-500/10 dark:text-rose-400 font-semibold text-xs transition-colors">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-10 text-slate-400">
                                    <i class="fas fa-folder-open text-3xl mb-3 block"></i>
                                    لا توجد مجموعات بيانات ثانوية مسجلة. انقر على "استيراد مجموعة ثانوية جديد" للبدء.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- TAB 2: MAIN DATASET OVERVIEW --}}
    <div id="tabContentMain" class="space-y-6 hidden">
        <div class="diff-card p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="font-bold text-lg text-slate-900 dark:text-white">البيانات الرئيسية (Primary Dataset)</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">البيانات الأساسية المعتمده في النظام كمصدر مرجعي رئيسي للمطابقة.</p>
                </div>
                <button onclick="openUploadModal('main')" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-xl text-xs font-bold transition-all inline-flex items-center gap-2">
                    <i class="fas fa-upload"></i>
                    <span>تحديث / إعادة استيراد البيانات الرئيسية</span>
                </button>
            </div>

            @if(count($mainData) > 0)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-800">
                        <div class="text-xs text-slate-400 font-semibold mb-1">عدد السجلات الرئيسية</div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ number_format(count($mainData)) }}</div>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-800">
                        <div class="text-xs text-slate-400 font-semibold mb-1">عدد الأعمدة والحقول</div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ count($mainColumns) }}</div>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-800">
                        <div class="text-xs text-slate-400 font-semibold mb-1">تاريخ آخر تحديث</div>
                        <div class="text-sm font-bold text-slate-900 dark:text-white font-mono mt-1">{{ $mainTable ? $mainTable->updated_at->format('Y-m-d H:i') : '-' }}</div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-right border-collapse diff-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                @foreach($mainColumns as $col)
                                    <th>{{ $col }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(array_slice($mainData, 0, 50) as $idx => $row)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors">
                                    <td class="font-mono text-xs font-bold">{{ $idx + 1 }}</td>
                                    @foreach($mainColumns as $col)
                                        <td class="text-xs">{{ $row[$col] ?? '-' }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12 text-slate-400">
                    <i class="fas fa-database text-4xl mb-3 block"></i>
                    <p class="font-semibold text-slate-600 dark:text-slate-300">لا توجد بيانات رئيسية مستوردة بعد.</p>
                    <button onclick="openUploadModal('main')" class="mt-4 bg-brand-600 hover:bg-brand-700 text-white px-5 py-2.5 rounded-xl font-bold text-xs shadow-sm">
                        استيراد البيانات الرئيسية الآن
                    </button>
                </div>
            @endif
        </div>
    </div>

    {{-- TAB 3: COMPARISON WIZARD & ENGINE --}}
    <div id="tabContentComparison" class="space-y-6 hidden">
        <div class="diff-card p-6">
            <h3 class="font-bold text-lg text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                <span class="w-2.5 h-6 bg-brand-600 rounded-full"></span>
                إعدادات المطابقة والمقارنة الذكية
            </h3>

            <form id="comparisonForm" onsubmit="runComparisonExecution(event)" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- 1. Secondary Dataset Select --}}
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">1. اختر المجموعة الثانوية للمقارنة <span class="text-rose-500">*</span></label>
                        <select id="compSecondaryId" onchange="onSecondaryDatasetSelected()" required class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-brand-500">
                            <option value="">-- اختر مجموعة ثانوية --</option>
                            @foreach($secondaryDatasets as $dataset)
                                <option value="{{ $dataset->id }}">{{ $dataset->name }} ({{ number_format($dataset->records_count) }} سجل - {{ $dataset->source }})</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 2. Target Primary Source Choice --}}
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">2. اختر مصدر المقارنة المستهدف (الرئيسية أو صفحة نظام) <span class="text-rose-500">*</span></label>
                        <select id="compPrimarySourceType" onchange="togglePrimarySourceSelect()" required class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-brand-500">
                            <option value="main_table">البيانات الرئيسية المستوردة (Main Table)</option>
                            <option value="system_source">صفحات ومصادر بيانات النظام (System Pages / Models)</option>
                        </select>
                    </div>
                </div>

                {{-- Hidden Dropdown for System Source --}}
                <div id="systemSourceContainer" class="space-y-2 hidden">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">اختر صفحة/مصدر بيانات من النظام <span class="text-rose-500">*</span></label>
                    <select id="compSystemSourceKey" onchange="onSystemSourceSelected()" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-brand-500">
                        <option value="">-- اختر صفحة نظام --</option>
                        @foreach($systemSources as $src)
                            <option value="{{ $src['key'] }}">{{ $src['name'] }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Key Fields Selection Container --}}
                <div class="space-y-3">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">3. حدد حقول مفاتيح المطابقة (Match Keys) <span class="text-rose-500">*</span></label>
                    <p class="text-xs text-slate-500">اختر حتمياً حتلاً أو أكثر ليتم الاعتماد عليه كمفتاح الربط (مثل: الرقم العسكري، الهوية، الاسم الكامل).</p>
                    <div id="keyFieldsContainer" class="flex flex-wrap gap-3 p-4 bg-slate-50 dark:bg-slate-800/40 rounded-xl border border-slate-200 dark:border-slate-800">
                        <span class="text-xs text-slate-400">يرجى اختيار المجموعة الثانوية أولاً لعرض الحقول المتاحة.</span>
                    </div>
                </div>

                {{-- Mapping Fields Container --}}
                <div class="space-y-3">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">4. ضبط المطابقة ومقابلة الحقول (Field Mapping)</label>
                    <p class="text-xs text-slate-500">يمكنك اختيار الحقل المقابل بكل عمود حتى وإن اختلفت الأسماء، أو اختيار &lt;تجاهل العمود&gt;.</p>
                    <div id="comparisonMappingContainer" class="space-y-2 max-h-60 overflow-y-auto p-3 bg-slate-50 dark:bg-slate-800/40 rounded-xl border border-slate-200 dark:border-slate-800">
                        <span class="text-xs text-slate-400">سيتم توليد خيارات المطابقة تلقائياً عند تحديد المصادر.</span>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" id="btnRunComparison" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-bold py-3 px-6 rounded-xl text-sm shadow-md transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-play"></i>
                        <span>تنفيذ عملية المطابقة والمقارنة الآن</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- RESULTS DASHBOARD --}}
        <div id="comparisonResultsDashboard" class="space-y-6 hidden">
            {{-- 5 Metric Cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="diff-card p-4 border-emerald-500/30 bg-emerald-500/5 cursor-pointer" onclick="switchResultTab('matched')">
                    <div class="text-xs font-bold text-emerald-600 dark:text-emerald-400 mb-1">سجلات متطابقة</div>
                    <div class="text-2xl font-bold text-emerald-700 dark:text-emerald-300" id="resCountMatched">0</div>
                    <div class="text-[10px] text-slate-500 mt-1">تطابق المفتاح وكافة الحقول</div>
                </div>

                <div class="diff-card p-4 border-amber-500/30 bg-amber-500/5 cursor-pointer" onclick="switchResultTab('different')">
                    <div class="text-xs font-bold text-amber-600 dark:text-amber-400 mb-1">سجلات مختلفة</div>
                    <div class="text-2xl font-bold text-amber-700 dark:text-amber-300" id="resCountDifferent">0</div>
                    <div class="text-[10px] text-slate-500 mt-1">تطابق المفتاح واختلاف حقول</div>
                </div>

                <div class="diff-card p-4 border-blue-500/30 bg-blue-500/5 cursor-pointer" onclick="switchResultTab('in_sec_only')">
                    <div class="text-xs font-bold text-blue-600 dark:text-blue-400 mb-1">في الثانوية فقط</div>
                    <div class="text-2xl font-bold text-blue-700 dark:text-blue-300" id="resCountInSecOnly">0</div>
                    <div class="text-[10px] text-slate-500 mt-1">غير موجود بالرئيسية</div>
                </div>

                <div class="diff-card p-4 border-purple-500/30 bg-purple-500/5 cursor-pointer" onclick="switchResultTab('in_pri_only')">
                    <div class="text-xs font-bold text-purple-600 dark:text-purple-400 mb-1">في الرئيسية فقط</div>
                    <div class="text-2xl font-bold text-purple-700 dark:text-purple-300" id="resCountInPriOnly">0</div>
                    <div class="text-[10px] text-slate-500 mt-1">غير موجود بالثانوية</div>
                </div>

                <div class="diff-card p-4 border-rose-500/30 bg-rose-500/5 cursor-pointer" onclick="switchResultTab('incomplete')">
                    <div class="text-xs font-bold text-rose-600 dark:text-rose-400 mb-1">بيانات ناقصة</div>
                    <div class="text-2xl font-bold text-rose-700 dark:text-rose-300" id="resCountIncomplete">0</div>
                    <div class="text-[10px] text-slate-500 mt-1">مفقود قيم المفاتيح</div>
                </div>
            </div>

            {{-- Results Content Container --}}
            <div class="diff-card p-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                    <h4 class="font-bold text-slate-900 dark:text-white text-base flex items-center gap-2" id="resultTabTitle">
                        <span class="w-2.5 h-5 bg-brand-600 rounded-full"></span>
                        تفاصيل السجلات المتطابقة
                    </h4>

                    <div class="flex items-center gap-2">
                        <a id="btnExportCategory" href="#" target="_blank" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-xl text-xs font-bold transition-all inline-flex items-center gap-2">
                            <i class="fas fa-file-excel"></i>
                            <span>تصدير هذه النتائج (CSV)</span>
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto" id="resultsTableContainer">
                    {{-- Dynamically populated table --}}
                </div>
            </div>
        </div>
    </div>

    {{-- TAB 4: AUDIT LOGS --}}
    <div id="tabContentLogs" class="space-y-6 hidden">
        <div class="diff-card p-6">
            <h3 class="font-bold text-lg text-slate-900 dark:text-white mb-4">سجل عمليات الاستيراد والمطابقة والمقارنة</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-right border-collapse diff-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>المستخدم</th>
                            <th>نوع العملية</th>
                            <th>اسم المجموعة / المصدر</th>
                            <th>التفاصيل</th>
                            <th>التاريخ والوقت</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($auditLogs as $index => $log)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="font-mono text-xs font-bold">{{ $index + 1 }}</td>
                                <td class="font-bold text-slate-800 dark:text-slate-200">{{ $log->user->name ?? 'السيستم' }}</td>
                                <td>
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-brand-500/10 text-brand-600 dark:text-brand-400">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td class="font-bold text-slate-900 dark:text-white">{{ $log->dataset_name ?: '-' }}</td>
                                <td class="text-xs font-mono text-slate-500">{{ json_encode($log->details, JSON_UNESCAPED_UNICODE) }}</td>
                                <td class="text-xs font-mono text-slate-400">{{ $log->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-8 text-slate-400">لا توجد عمليات مسجلة بالسجل بعد.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
