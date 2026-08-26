@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8" dir="rtl">
    <!-- Header with Back link -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <div class="flex items-center gap-3 text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">
                <a href="{{ route('data.differences.index') }}" class="hover:text-blue-600 transition-colors">نظام التمايز</a>
                <i class="fas fa-chevron-left text-[10px]"></i>
                <span>إعدادات النظام والسجل الديناميكي</span>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight flex items-center gap-3">
                <i class="fas fa-sliders-h text-blue-600"></i>
                إعدادات نظام التمايز الذكي
            </h1>
            <p class="text-slate-500 mt-1 text-sm font-semibold">تخصيص مستويات التشابه، قواعد المطابقة، وإدارة سجل الأوعية والصفحات الديناميكية.</p>
        </div>
        
        <a href="{{ route('data.differences.index') }}" 
           class="flex items-center gap-2 px-5 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-2xl transition-all duration-300">
            <i class="fas fa-arrow-right"></i>
            <span>العودة للرئيسية</span>
        </a>
    </div>

    <!-- Feedback Alerts -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-2xl font-bold flex items-center gap-3">
            <i class="fas fa-check-circle text-lg"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <!-- Left: Settings Form and Rules Manager -->
        <div class="xl:col-span-1 space-y-8">
            <!-- Global Config Card -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                <h2 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <i class="fas fa-sliders-h text-slate-500"></i>
                    تكوين المعايير العامة
                </h2>

                <form action="{{ route('data.differences.settings.update') }}" method="POST">
                    @csrf
                    
                    <!-- Similarity Threshold -->
                    <div class="mb-6">
                        <label class="block text-slate-700 text-sm font-bold mb-2 flex justify-between">
                            <span>نسبة عتبة التشابه الدنيا</span>
                            <span id="threshold-val" class="text-blue-600 font-black">{{ $setting->similarity_threshold ?? 80 }}%</span>
                        </label>
                        <input type="range" name="similarity_threshold" id="similarity_threshold" 
                               min="50" max="100" value="{{ $setting->similarity_threshold ?? 80 }}" 
                               class="w-full h-2 bg-slate-100 rounded-lg appearance-none cursor-pointer accent-blue-600 focus:outline-none"
                               oninput="document.getElementById('threshold-val').innerText = this.value + '%'">
                        <span class="text-slate-400 text-xxs block mt-1">تجاهل أي سجلات تشابه تقل نسبتها الإملائية عن هذه النسبة.</span>
                    </div>

                    <!-- Matching Strategy Driver -->
                    <div class="mb-6">
                        <label class="block text-slate-700 text-sm font-bold mb-2">خوارزمية محرك التمايز والتشابه</label>
                        <select name="selected_strategy" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                            <option value="standard" {{ ($setting->selected_strategy ?? 'standard') === 'standard' ? 'selected' : '' }}>محرك تمايز الهجاء والكلمات (افتراضي)</option>
                            <option value="soundex" {{ ($setting->selected_strategy ?? '') === 'soundex' ? 'selected' : '' }}>محرك التشابه الصوتي واللفظي (Arabic Soundex)</option>
                            <option value="nlp" {{ ($setting->selected_strategy ?? '') === 'nlp' ? 'selected' : '' }}>محرك معالجة اللغات الطبيعية والتداخل (NLP Similarity)</option>
                        </select>
                        <span class="text-slate-400 text-xxs block mt-1">المحرك القياسي يدمج معالجة حركات الهجاء والمسافات والهمزات.</span>
                    </div>

                    <!-- Stopwords / Excluded Words -->
                    <div class="mb-6">
                        <label class="block text-slate-700 text-sm font-bold mb-2">قائمة الكلمات المستبعدة (Stopwords)</label>
                        <textarea name="excluded_words" rows="3" 
                                  placeholder="مثل: السيد، الدكتور، الشيخ، ام، ابو..." 
                                  class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-semibold text-slate-700 placeholder-slate-400 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">{{ !empty($setting->excluded_words) ? implode(', ', $setting->excluded_words) : '' }}</textarea>
                        <span class="text-slate-400 text-xxs block mt-1">افصل بين الكلمات بفاصلة (,). يتم استبعاد الألقاب لضمان دقة مقارنة الأسماء الأساسية.</span>
                    </div>

                    <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl transition-all duration-300 shadow hover:shadow-lg flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i>
                        <span>حفظ المعايير والكلمات</span>
                    </button>
                </form>
            </div>

            <!-- Precedence Matching Rules Card -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                <h2 class="text-lg font-bold text-slate-800 mb-2 flex items-center gap-2">
                    <i class="fas fa-list-ol text-slate-500"></i>
                    قواعد تصنيف المطابقة
                </h2>
                <p class="text-slate-400 text-xxs font-bold mb-6">قواعد مركبة تحدد لون وحالة المطابقة حسب شروط الحقول.</p>

                <div class="space-y-4">
                    @foreach(($setting->matching_rules ?? []) as $rule)
                        <div class="p-4 bg-slate-50 border border-slate-150 rounded-2xl flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <span class="w-7 h-7 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center text-xs font-black">{{ $loop->iteration }}</span>
                                <div>
                                    <span class="text-sm font-bold text-slate-800 block mb-0.5">{{ $rule['label'] ?? '' }}</span>
                                    <span class="text-xxs text-slate-400 font-bold">الحقول المشترطة: 
                                        @foreach($rule['columns'] as $c)
                                            <span class="px-1.5 py-0.5 bg-white border border-slate-200 rounded text-slate-600 mx-0.5">{{ $c }}</span>
                                        @endforeach
                                    </span>
                                </div>
                            </div>

                            @if($rule['result_status'] === 'match')
                                <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-150 rounded-full text-xxs font-bold">تطابق تام (أخضر)</span>
                            @else
                                <span class="px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-150 rounded-full text-xxs font-bold">تشابه قوي (أصفر)</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right: Search Targets Dynamic Registry -->
        <div class="xl:col-span-2 space-y-8">
            <!-- Registry Browser -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                <h2 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <i class="fas fa-folder-open text-slate-500"></i>
                    السجل الديناميكي لمستهدفات المطابقة
                </h2>

                <div class="overflow-x-auto">
                    <table class="w-full text-right border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-slate-400 text-xs font-bold uppercase tracking-wider">
                                <th class="pb-3 pr-2">اسم الوعاء</th>
                                <th class="pb-3">نوع الوعاء</th>
                                <th class="pb-3">المعرف البرمجي</th>
                                <th class="pb-3">تخطيط الحقول (الاسم / الهوية / الهاتف / السكن)</th>
                                <th class="pb-3 pl-2 text-center">نشط</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm font-semibold text-slate-700">
                            @foreach($targets as $item)
                                <tr class="hover:bg-slate-50/50 transition-colors duration-200">
                                    <td class="py-4 pr-2 font-bold text-slate-800">{{ $item->name }}</td>
                                    <td class="py-4">
                                        @if($item->type === 'static')
                                            <span class="px-2.5 py-0.5 bg-blue-50 text-blue-700 border border-blue-150 rounded-lg text-xxs font-bold">صفحة ثابتة (سجل)</span>
                                        @elseif($item->type === 'dynamic')
                                            <span class="px-2.5 py-0.5 bg-purple-50 text-purple-700 border border-purple-150 rounded-lg text-xxs font-bold">استمارة ديناميكية</span>
                                        @else
                                            <span class="px-2.5 py-0.5 bg-slate-50 text-slate-700 border border-slate-150 rounded-lg text-xxs font-bold">جدول مستخدم مخصص</span>
                                        @endif
                                    </td>
                                    <td class="py-4 text-xs text-slate-400 font-mono truncate max-w-[120px]">{{ $item->source_identifier }}</td>
                                    <td class="py-4 text-slate-500 font-semibold text-xs leading-relaxed">
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($item->columns as $stdName => $physName)
                                                @if(!empty($physName))
                                                    <span class="px-1.5 py-0.5 bg-slate-50 border border-slate-150 text-slate-600 rounded">
                                                        <strong>{{ $stdName === 'name' ? 'الاسم' : ($stdName === 'id' ? 'الهوية' : ($stdName === 'phone' ? 'الهاتف' : 'السكن')) }}:</strong> {{ $physName }}
                                                    </span>
                                                @endif
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="py-4 pl-2 text-center">
                                        <!-- Custom Checkbox Toggle -->
                                        <label class="inline-flex relative items-center cursor-pointer">
                                            <input type="checkbox" value="" class="sr-only peer" {{ $item->is_active ? 'checked' : '' }}
                                                   onchange="toggleTargetStatus({{ $item->id }}, this)">
                                            <div class="w-10 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:right-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                                        </label>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Target Registration Form -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                <h2 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <i class="fas fa-plus-circle text-blue-500"></i>
                    تسجيل وربط مستهدف مطابقة جديد
                </h2>

                <form action="{{ route('data.differences.settings.add-target') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <!-- Display Name -->
                        <div>
                            <label class="block text-slate-700 text-sm font-bold mb-2">اسم مستهدف المطابقة</label>
                            <input type="text" name="name" required placeholder="مثال: بيانات المعلمين" 
                                   class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                        </div>

                        <!-- Target Type -->
                        <div>
                            <label class="block text-slate-700 text-sm font-bold mb-2">نوع المستهدف بالنظام</label>
                            <select name="type" id="target_type" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all"
                                    onchange="adjustSourceIdentifierOptions(this.value)">
                                <option value="static">صفحة/جدول ثابت (سجل Model)</option>
                                <option value="dynamic">استمارة صفحة ديناميكية</option>
                                <option value="custom_table">جدول مخصص (ملفات مرفوعة)</option>
                            </select>
                        </div>

                        <!-- Source Identifier -->
                        <div>
                            <label class="block text-slate-700 text-sm font-bold mb-2">المعرف البرمجي / المصدر</label>
                            <!-- Container for dynamic options replacement -->
                            <div id="source-identifier-container">
                                <input type="text" name="source_identifier" id="source_identifier_text" required placeholder="مثال: App\Models\Employee" 
                                       class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Column Mappings -->
                    <h4 class="text-xs font-black text-slate-500 mb-4 uppercase tracking-wider border-t border-slate-50 pt-4">تخطيط حقول البيانات للمطابقة (اسم الحقل البرمجي أو معرف الحقل الديناميكي)</h4>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
                        <div>
                            <label class="block text-slate-700 text-xs font-bold mb-2">حقل اسم الشخص <span class="text-rose-500">*</span></label>
                            <input type="text" name="col_name" required placeholder="name" 
                                   class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none focus:border-blue-400 transition-all">
                        </div>
                        <div>
                            <label class="block text-slate-700 text-xs font-bold mb-2">حقل رقم الهوية (اختياري)</label>
                            <input type="text" name="col_id" placeholder="national_id" 
                                   class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none focus:border-blue-400 transition-all">
                        </div>
                        <div>
                            <label class="block text-slate-700 text-xs font-bold mb-2">حقل رقم الهاتف (اختياري)</label>
                            <input type="text" name="col_phone" placeholder="phone_number" 
                                   class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none focus:border-blue-400 transition-all">
                        </div>
                        <div>
                            <label class="block text-slate-700 text-xs font-bold mb-2">حقل السكن/العنوان (اختياري)</label>
                            <input type="text" name="col_address" placeholder="current_address" 
                                   class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none focus:border-blue-400 transition-all">
                        </div>
                    </div>

                    <button type="submit" class="px-6 py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl transition-all duration-300 shadow hover:shadow-md flex items-center justify-center gap-2">
                        <i class="fas fa-plus"></i>
                        <span>ربط مستهدف المطابقة</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Dynamic data templates for the source selector -->
<div id="dynamic-pages-select-tmpl" class="hidden">
    <select name="source_identifier" required class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:outline-none focus:border-blue-400 transition-all">
        @foreach($dynamicPages as $p)
            <option value="{{ $p->slug }}">{{ $p->name }}</option>
        @endforeach
    </select>
</div>

<div id="custom-tables-select-tmpl" class="hidden">
    <select name="source_identifier" required class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:outline-none focus:border-blue-400 transition-all">
        @foreach($customTables as $t)
            <option value="{{ $t->id }}">{{ $t->name }} ({{ $t->type }})</option>
        @endforeach
    </select>
</div>

<div id="static-text-input-tmpl" class="hidden">
    <input type="text" name="source_identifier" required placeholder="مثال: App\Models\Employee" 
           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-blue-400 transition-all">
</div>

<script>
    // 1. Dynamic selector options swapping
    function adjustSourceIdentifierOptions(type) {
        const container = document.getElementById('source-identifier-container');
        if (type === 'static') {
            container.innerHTML = document.getElementById('static-text-input-tmpl').innerHTML;
        } else if (type === 'dynamic') {
            container.innerHTML = document.getElementById('dynamic-pages-select-tmpl').innerHTML;
        } else if (type === 'custom_table') {
            container.innerHTML = document.getElementById('custom-tables-select-tmpl').innerHTML;
        }
    }

    // 2. AJAX Target Toggle Implementation
    function toggleTargetStatus(targetId, checkbox) {
        fetch(`/data/differences/settings/toggle-target/${targetId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                checkbox.checked = data.is_active;
            }
        })
        .catch(err => {
            console.error("Toggle error: ", err);
            checkbox.checked = !checkbox.checked; // Revert
        });
    }
</script>
@endsection
