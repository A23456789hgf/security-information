{{-- 
    ══════════════════════════════════════════════════════════════════
    مودال سحب الوثائق عبر الماسح الضوئي (Scanner & Document Capture)
    التبويب الأول والافتراضي: السحب المباشر من الطابعة / الماسح الضوئي
    التبويب الثاني: كاميرا الوثائق الذكية
    ══════════════════════════════════════════════════════════════════
--}}

{{-- تضمين مكتبة jsPDF محلياً --}}
<script src="{{ asset('vendor/jspdf.umd.min.js') }}"></script>

{{-- نافذة المودال الرئيسية --}}
<div id="documentScannerModal" class="hidden fixed inset-0 flex items-center justify-center p-3 sm:p-5 overflow-y-auto bg-slate-950/75 backdrop-blur-sm transition-opacity duration-200" style="z-index: 99999; display: none;" dir="rtl">
    <div class="relative w-full max-w-4xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-2xl flex flex-col max-h-[92vh] overflow-hidden my-auto">
        
        {{-- رأس المودال --}}
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50 dark:bg-slate-800/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shadow-md shrink-0" style="background-color: #059669 !important; color: #ffffff !important;">
                    <i class="fas fa-print text-lg text-white"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="font-display font-bold text-base text-slate-900 dark:text-white m-0">سحب الوثائق عبر الماسح الضوئي والطابعة (Scanner)</h3>
                        <span id="scannerTargetBadge" class="text-[11px] px-2.5 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 font-bold border border-emerald-300 dark:border-emerald-800">
                            مرفقات الملف
                        </span>
                    </div>
                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">اسحب الوثائق مباشرة من الطابعة أو الكاميرا مع خيار التصدير كملف PDF مجمع أو صور منفصلة.</p>
                </div>
            </div>

            <button type="button" onclick="closeScannerModal()" class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 hover:text-slate-900 dark:text-slate-300 dark:hover:text-white hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center justify-center transition-colors">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>

        {{-- تبويبات مصادر السحب (التبويب الأول والافتراضي: السحب من الطابعة) --}}
        <div class="flex border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-6 pt-2 gap-2">
            {{-- التبويب الأول والافتراضي: السحب المباشر من الطابعة --}}
            <button type="button" id="tabBtnPrinter" onclick="switchScannerTab('printer')"
                class="px-4 py-2.5 text-xs font-bold border-b-2 border-emerald-600 text-emerald-600 dark:text-emerald-400 flex items-center gap-2 transition-colors cursor-pointer">
                <i class="fas fa-print"></i>
                <span>السحب المباشر من الطابعة / الماسح الضوئي</span>
            </button>
            {{-- التبويب الثاني: كاميرا الوثائق الذكية --}}
            <button type="button" id="tabBtnCamera" onclick="switchScannerTab('camera')"
                class="px-4 py-2.5 text-xs font-bold border-b-2 border-transparent text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200 flex items-center gap-2 transition-colors cursor-pointer">
                <i class="fas fa-camera"></i>
                <span>كاميرا الوثائق الذكية</span>
            </button>
        </div>

        {{-- جسم المودال القابل للتمرير --}}
        <div class="p-6 overflow-y-auto space-y-5 flex-1 bg-white dark:bg-slate-900">

            {{-- ═════════════════════════════════════════════════════════════ --}}
            {{-- التبويب 1 (الافتراضي): السحب المباشر من الطابعة والماسح ولصق الوثائق --}}
            {{-- ═════════════════════════════════════════════════════════════ --}}
            <div id="scannerTabPrinter" class="space-y-4">
                {{-- لوحة التحكم بمحرك السحب المباشر من الطابعة والماسح الضوئي --}}
                <div class="bg-gradient-to-br from-emerald-500/10 via-emerald-500/5 to-teal-500/10 dark:from-emerald-950/40 dark:via-slate-850 dark:to-teal-950/40 p-5 rounded-2xl border-2 border-emerald-500/30 space-y-4">
                    
                    {{-- 1. بطاقة الكشف التلقائي الذكي عن الطابعة المتصلة دون الحاجة للاختيار اليدوي --}}
                    <div id="autoScannerCard" class="p-4 bg-white dark:bg-slate-900 rounded-2xl border-2 border-emerald-500/30 shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-3.5">
                            <div id="autoScannerIconBox" class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center text-xl shadow-md shrink-0 transition-transform" style="background-color: #059669 !important; color: #ffffff !important;">
                                <i class="fas fa-print" id="autoScannerIcon"></i>
                            </div>
                            <div class="space-y-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h4 id="autoScannerTitle" class="font-bold text-sm text-slate-900 dark:text-white m-0">
                                        جاري الكشف التلقائي عن الطابعة والماسح الضوئي...
                                    </h4>
                                    <span id="autoScannerBadge" class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-300 dark:border-amber-800 flex items-center gap-1">
                                        <i class="fas fa-spinner fa-spin"></i> فحص الاتصال
                                    </span>
                                </div>
                                <p id="autoScannerSubtitle" class="text-xs text-slate-600 dark:text-slate-300 m-0">
                                    يتعرف النظام تلقائياً على طابعات الشبكة (IP) أو أجهزة USB دون الحاجة لتحديدها يدوياً.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0 self-end sm:self-center">
                            <button type="button" onclick="ScannerApp.autoDetectScanner(true)" title="إعادة الكشف التلقائي عن الطابعة"
                                class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-bold flex items-center gap-1.5 transition-colors cursor-pointer">
                                <i class="fas fa-sync-alt" id="reDetectIcon"></i>
                                <span>فحص مجدداً</span>
                            </button>
                            <button type="button" onclick="ScannerApp.toggleAdvancedSettings()"
                                class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-bold flex items-center gap-1.5 transition-colors cursor-pointer">
                                <i class="fas fa-sliders-h"></i>
                                <span>خيارات متقدمة</span>
                                <i class="fas fa-chevron-down text-[10px] transition-transform duration-200" id="advancedToggleChevron"></i>
                            </button>
                        </div>
                    </div>

                    {{-- 2. قسم الإعدادات المتقدمة واليدوية (مخفي افتراضياً لأن السحب يعمل تلقائياً) --}}
                    <div id="scannerAdvancedSection" class="hidden p-4 bg-slate-50 dark:bg-slate-800/60 rounded-2xl border border-slate-200 dark:border-slate-700 space-y-4">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 border-b border-slate-200 dark:border-slate-700 pb-3">
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300">
                                التبديل اليدوي بين أنواع الاتصال (في حال رغبت بتجاوز الاختيار التلقائي):
                            </span>
                            {{-- نوع الاتصال: طابعة شبكة أو USB --}}
                            <div class="flex items-center bg-white dark:bg-slate-900 p-1 rounded-xl border border-slate-200 dark:border-slate-700 text-xs shrink-0 shadow-xs">
                                <label class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg cursor-pointer transition-colors" id="connLabelNetwork">
                                    <input type="radio" name="scanner_connection_type" value="network" checked onchange="ScannerApp.setConnectionMode('network')" class="accent-emerald-600">
                                    <span class="font-bold text-slate-800 dark:text-slate-200"><i class="fas fa-network-wired text-emerald-600 me-1"></i> طابعة شبكة (IP)</span>
                                </label>
                                <label class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg cursor-pointer transition-colors" id="connLabelUsb">
                                    <input type="radio" name="scanner_connection_type" value="usb" onchange="ScannerApp.setConnectionMode('usb')" class="accent-emerald-600">
                                    <span class="font-bold text-slate-800 dark:text-slate-200"><i class="fas fa-usb text-blue-600 me-1"></i> كابل USB / ويندوز</span>
                                </label>
                            </div>
                        </div>

                        {{-- قسم ماسح الشبكة / IP --}}
                        <div id="scannerConfigNetwork" class="grid grid-cols-1 md:grid-cols-3 gap-3 items-end">
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 flex items-center justify-between">
                                    <span class="flex items-center gap-1.5"><i class="fas fa-wifi text-emerald-600"></i> عنوان IP الخاص بالطابعة أو الماسح:</span>
                                    <span class="text-[10px] text-slate-400">مثال: 192.168.1.102</span>
                                </label>
                                <div class="flex items-center gap-2">
                                    <input type="text" id="networkScannerIp" value="192.168.1.102" placeholder="أدخل IP الطابعة (مثال: 192.168.1.102)"
                                        class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white font-mono focus:border-emerald-500 focus:outline-hidden">
                                    <button type="button" id="testNetworkBtn" onclick="ScannerApp.testNetworkPrinter()"
                                        class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shrink-0 transition-colors shadow-xs cursor-pointer">
                                        <i class="fas fa-network-wired"></i> فحص الاتصال
                                    </button>
                                </div>
                                <div id="networkTestResult" class="hidden text-xs mt-1.5"></div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">مصدر تلقيم الورق</label>
                                <select id="scannerInputSourceSelect" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white font-medium focus:border-emerald-500 focus:outline-hidden">
                                    <option value="auto" selected>كشف تلقائي (تغذية ADF أو زجاج Platen)</option>
                                    <option value="platen">زجاج الماسح الضوئي (Platen Flatbed)</option>
                                    <option value="adf">وحدة التغذية التلقائية (ADF Feeder)</option>
                                </select>
                            </div>
                        </div>

                        {{-- قسم ماسح USB / ويندوز --}}
                        <div id="scannerConfigUsb" class="grid grid-cols-1 md:grid-cols-3 gap-3 items-end hidden">
                            <div class="md:col-span-2">
                                <div class="flex items-center justify-between mb-1.5">
                                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                                        <i class="fas fa-desktop text-blue-600"></i>
                                        <span>الماسح الضوئي / الطابعة المتصلة عبر USB:</span>
                                    </label>
                                    <button type="button" id="refreshWiaBtn" onclick="ScannerApp.refreshScannerDevices()" class="text-[11px] text-blue-700 dark:text-blue-400 hover:underline flex items-center gap-1 font-bold cursor-pointer">
                                        <i class="fas fa-sync-alt"></i>
                                        <span>تحديث الأجهزة المتصلة</span>
                                    </button>
                                </div>
                                <select id="wiaScannerSelect" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white font-medium focus:border-emerald-500 focus:outline-hidden">
                                    <option value="">الماسح الضوئي الافتراضي لنظام ويندوز</option>
                                </select>
                                <span id="wiaStatusLabel" class="text-[10px] text-slate-500 dark:text-slate-400 mt-1 block">جاهز للمسح الضوئي</span>
                            </div>
                        </div>

                        {{-- خيارات الجودة والألوان --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2 border-t border-slate-200 dark:border-slate-700">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">دقة المسح (DPI)</label>
                                <select id="scannerDpiSelect" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white font-medium focus:border-emerald-500 focus:outline-hidden">
                                    <option value="150">150 DPI (سحب سريع للمسودات)</option>
                                    <option value="200">200 DPI (دقة متوازنة سريعة)</option>
                                    <option value="300" selected>300 DPI (دقة قياسية ممتازة للمستندات والأوراق الرسمية)</option>
                                    <option value="600">600 DPI (دقة عالية جداً للصور)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">نمط الألوان</label>
                                <select id="scannerColorModeSelect" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white font-medium focus:border-emerald-500 focus:outline-hidden">
                                    <option value="color" selected>ملون كامل (RGB 24-bit)</option>
                                    <option value="bw">أبيض وأسود (وثائق نصية Black & White)</option>
                                    <option value="gray">تدرج رمادي (Grayscale 8-bit)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- 3. زر بدء السحب المباشر الفعلي ومؤشر الحالة --}}
                    <div class="pt-2 border-t border-emerald-500/20 flex flex-col sm:flex-row items-center justify-between gap-3">
                        <div class="text-xs text-slate-600 dark:text-slate-300 flex items-center gap-2">
                            <i class="fas fa-arrow-circle-left text-emerald-600 text-sm"></i>
                            <span>ضع الوثيقة في طابعة الماسح ثم اضغط لبدء السحب فوراً:</span>
                        </div>

                        <button type="button" id="startScanBtn" onclick="ScannerApp.startHardwareScan()"
                            class="w-full sm:w-auto px-8 py-3.5 bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-bold rounded-2xl text-sm shadow-lg shadow-emerald-600/30 flex items-center justify-center gap-2.5 transition-all cursor-pointer"
                            style="background-color: #059669 !important; color: #ffffff !important;">
                            <i class="fas fa-print text-lg text-white"></i>
                            <span class="text-white font-bold" id="startScanBtnText">بدء سحب الوثيقة من الطابعة الآن</span>
                        </button>
                    </div>

                    {{-- مؤشر الحالة المباشر أثناء دوران محرك الماسح --}}
                    <div id="hardwareScanStatus" class="p-4 bg-emerald-500/15 border-2 border-emerald-500/40 rounded-2xl text-emerald-900 dark:text-emerald-200 hidden">
                        <div class="flex items-center gap-3">
                            <div class="relative w-8 h-8 flex items-center justify-center shrink-0">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <i class="fas fa-spinner fa-spin text-xl text-emerald-600 dark:text-emerald-400"></i>
                            </div>
                            <div class="space-y-0.5">
                                <div class="font-bold text-xs" id="hardwareScanStatusText">جاري الاتصال بالطابعة وإصدار أمر السحب...</div>
                                <div class="text-[11px] text-slate-600 dark:text-slate-400" id="hardwareScanStatusSub">يرجى الانتظار بينما يقوم محرك الماسح الضوئي بسحب الوثيقة وتصويرها.</div>
                            </div>
                        </div>
                    </div>

                    {{-- رسالة الخطأ أو عدم وجود ماسح --}}
                    <div id="hardwareScanError" class="p-4 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/50 rounded-2xl text-rose-800 dark:text-rose-200 hidden space-y-2">
                        <div class="flex items-start gap-2.5">
                            <i class="fas fa-exclamation-circle text-rose-500 text-base mt-0.5 shrink-0"></i>
                            <div class="text-xs space-y-1">
                                <div class="font-bold" id="hardwareScanErrorText">تعذر الاتصال بالماسح الضوئي.</div>
                                <p class="text-slate-600 dark:text-slate-400">
                                    تأكد من تشغيل الطابعة وتوصيل كابل USB أو تشغيل Wi-Fi/الشبكة، أو استخدم الخيارات السريعة أدناه:
                                </p>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center gap-2 pt-1">
                            <a href="ms-scan:" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 transition-colors">
                                <i class="fab fa-windows"></i> فتح تطبيق ماسح ويندوز (Windows Scan)
                            </a>
                            <button type="button" onclick="document.getElementById('scannerDirectFileInput').click()" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-slate-200 rounded-xl text-xs font-bold hover:bg-slate-100 transition-colors cursor-pointer">
                                <i class="fas fa-folder-open text-amber-500"></i> اختيار ملف مسحوب مسبقاً
                            </button>
                        </div>
                    </div>

                </div>

                {{-- منطقة سحب وإفلات ملفات الطابعة / السكانر أو استيرادها كخيار بديل --}}
                <div class="p-6 border-2 border-dashed border-slate-300 dark:border-slate-700 hover:border-emerald-500 dark:hover:border-emerald-500 rounded-2xl bg-slate-50 dark:bg-slate-800/40 text-center transition-colors cursor-pointer"
                     onclick="document.getElementById('scannerDirectFileInput').click()"
                     ondragover="event.preventDefault(); this.classList.add('border-emerald-500')"
                     ondragleave="this.classList.remove('border-emerald-500')"
                     ondrop="handleScannerDrop(event)">
                    
                    <div class="flex items-center justify-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 flex items-center justify-center text-lg shrink-0">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <div class="text-right">
                            <h5 class="font-bold text-xs text-slate-900 dark:text-slate-200">أو اسحب وأفلت الملفات المسحوبة من الطابعة هنا أو اضغط للاختيار (JPG, PNG, PDF)</h5>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400">يمكنك أيضاً نسخ أي صورة أو وثيقة من برنامج الطابعة والضغط على <kbd class="px-1 py-0.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded font-mono font-bold text-emerald-600">Ctrl + V</kbd> للصقها فوراً في الصفحة.</p>
                        </div>
                    </div>

                    <input type="file" id="scannerDirectFileInput" multiple accept="image/*,application/pdf" class="hidden" onchange="handleScannerFileInput(this.files)">
                </div>
            </div>

            {{-- ═════════════════════════════════════════════════════════════ --}}
            {{-- التبويب 2: كاميرا الوثائق الذكية --}}
            {{-- ═════════════════════════════════════════════════════════════ --}}
            <div id="scannerTabCamera" class="space-y-4 hidden">
                <div class="relative bg-slate-950 rounded-2xl overflow-hidden aspect-[4/3] max-h-[360px] flex items-center justify-center border border-slate-800 shadow-inner group">
                    <video id="scannerVideo" autoplay playsinline muted class="w-full h-full object-contain"></video>
                    
                    {{-- إطار إرشادي لتوسيط المستند --}}
                    <div class="pointer-events-none absolute inset-6 border-2 border-dashed border-emerald-400/50 rounded-xl flex items-center justify-center">
                        <span class="text-[11px] bg-slate-950/80 text-emerald-300 px-3 py-1 rounded-full font-mono">
                            وجّه الوثيقة داخل الإطار
                        </span>
                    </div>

                    {{-- وميض الالتقاط --}}
                    <div id="scannerFlashEffect" class="pointer-events-none absolute inset-0 bg-white opacity-0 transition-opacity duration-150"></div>
                </div>

                {{-- أدوات التحكم بالكاميرا والفلاتر --}}
                <div class="flex flex-wrap items-center justify-between gap-3 bg-slate-100 dark:bg-slate-800/60 p-3 rounded-2xl border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center gap-2 flex-wrap">
                        {{-- اختيار الكاميرا --}}
                        <div class="flex items-center gap-1.5 text-xs text-slate-700 dark:text-slate-300">
                            <i class="fas fa-video text-slate-500"></i>
                            <select id="scannerCameraSelect" onchange="changeScannerCamera(this.value)" class="px-2.5 py-1.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-200 rounded-xl text-xs focus:outline-hidden">
                                <option value="">الكاميرا الافتراضية</option>
                            </select>
                        </div>

                        {{-- فلاتر تحسين الوثائق --}}
                        <div class="flex items-center gap-1 bg-white dark:bg-slate-900 p-1 rounded-xl border border-slate-200 dark:border-slate-700 text-xs">
                            <button type="button" onclick="setScannerFilter('none')" id="filterBtn_none" class="filter-btn px-2.5 py-1 rounded-lg font-bold bg-emerald-600 text-white transition-colors" style="background-color: #059669; color: #ffffff;">
                                طبيعي
                            </button>
                            <button type="button" onclick="setScannerFilter('bw')" id="filterBtn_bw" class="filter-btn px-2.5 py-1 rounded-lg text-slate-700 dark:text-slate-300 hover:text-slate-950 dark:hover:text-white transition-colors" title="تحسين نصوص المستندات">
                                أبيض وأسود
                            </button>
                            <button type="button" onclick="setScannerFilter('gray')" id="filterBtn_gray" class="filter-btn px-2.5 py-1 rounded-lg text-slate-700 dark:text-slate-300 hover:text-slate-950 dark:hover:text-white transition-colors">
                                تدرج رمادي
                            </button>
                        </div>
                    </div>

                    {{-- زر الالتقاط --}}
                    <button type="button" id="scannerCaptureBtn" onclick="captureScannerPage()"
                        class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-bold rounded-xl text-xs shadow-md flex items-center gap-2 transition-all cursor-pointer"
                        style="background-color: #059669 !important; color: #ffffff !important;">
                        <i class="fas fa-camera text-white"></i>
                        <span>التقاط الصفحة [مسافة]</span>
                    </button>
                </div>
            </div>

            {{-- ═════════════════════════════════════════════════════════════ --}}
            {{-- قسم الصفحات المسحوبة حالياً (سحب أكثر من ملف) --}}
            {{-- ═════════════════════════════════════════════════════════════ --}}
            <div class="space-y-3 pt-3 border-t border-slate-200 dark:border-slate-800">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <h4 class="font-bold text-xs text-slate-900 dark:text-slate-200">الصفحات والوثائق المسحوبة:</h4>
                        <span id="scannedPagesCountBadge" class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-200 border border-slate-200 dark:border-slate-700">
                            0 صفحة
                        </span>
                    </div>

                    <button type="button" id="clearScannedPagesBtn" onclick="clearAllScannedPages()" class="text-xs text-rose-600 hover:text-rose-700 dark:hover:text-rose-400 font-bold hidden">
                        <i class="fas fa-trash-alt me-1"></i> مسح جميع الصفحات
                    </button>
                </div>

                {{-- الحالة الفارغة --}}
                <div id="scannedPagesEmptyState" class="p-6 rounded-2xl border border-dashed border-slate-300 dark:border-slate-700 text-center text-xs text-slate-500 dark:text-slate-400">
                    <i class="fas fa-file-alt text-2xl mb-1 text-slate-400 dark:text-slate-600"></i>
                    <div>لم يتم سحب أي وثائق بعد. اضغط على زر "بدء سحب الوثيقة من الطابعة" أو الصق عبر (Ctrl+V) للبدء.</div>
                </div>

                {{-- معرض مصغرات الصفحات --}}
                <div id="scannedPagesGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 hidden">
                    {{-- يتم توليد كروت الصفحات ديناميكياً هنا --}}
                </div>
            </div>

            {{-- ═════════════════════════════════════════════════════════════ --}}
            {{-- قسم اختيار صيغة الإخراج (صورة أم PDF) --}}
            {{-- ═════════════════════════════════════════════════════════════ --}}
            <div class="space-y-3 pt-3 border-t border-slate-200 dark:border-slate-800">
                <label class="block text-xs font-bold text-slate-900 dark:text-slate-200">
                    صيغة الحفظ والإخراج المطلوبة:
                </label>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    {{-- خيار PDF --}}
                    <label class="relative flex items-start gap-3 p-3.5 bg-slate-50 dark:bg-slate-800/60 border-2 border-emerald-500 rounded-2xl cursor-pointer transition-all" id="formatCardPdf">
                        <input type="radio" name="scanner_format" value="pdf" checked onchange="updateFormatSelection()" class="mt-1 text-emerald-600 focus:ring-emerald-500">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-file-pdf text-rose-500 text-base"></i>
                                <span class="font-bold text-xs text-slate-900 dark:text-white">مستند PDF مجمع (موصى به)</span>
                            </div>
                            <p class="text-[11px] text-slate-600 dark:text-slate-400">دمج جميع الصفحات المسحوبة في ملف PDF واحد متناسق بحجم A4.</p>
                            <div class="pt-1.5" id="pdfNameContainer">
                                <input type="text" id="scannerPdfFileName" value="وثيقة_أمنية_مسحوبة.pdf" placeholder="اسم ملف الـ PDF"
                                    class="w-full px-2.5 py-1.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg text-xs font-mono">
                            </div>
                        </div>
                    </label>

                    {{-- خيار الصور المنفصلة --}}
                    <label class="relative flex items-start gap-3 p-3.5 bg-slate-50 dark:bg-slate-800/60 border-2 border-slate-200 dark:border-slate-700 rounded-2xl cursor-pointer transition-all" id="formatCardImages">
                        <input type="radio" name="scanner_format" value="images" onchange="updateFormatSelection()" class="mt-1 text-emerald-600 focus:ring-emerald-500">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-images text-indigo-500 text-base"></i>
                                <span class="font-bold text-xs text-slate-900 dark:text-white">صور منفصلة (JPG)</span>
                            </div>
                            <p class="text-[11px] text-slate-600 dark:text-slate-400">حفظ كل صفحة تم سحبها كصورة مستقلة لسهولة المعاينة الفردية.</p>
                            <div class="pt-1.5 hidden" id="imagesNameContainer">
                                <input type="text" id="scannerImagesPrefix" value="وثيقة_مسحوبة" placeholder="بادئة أسماء الصور"
                                    class="w-full px-2.5 py-1.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg text-xs font-mono">
                            </div>
                        </div>
                    </label>
                </div>
            </div>

        </div>

        {{-- تذييل المودال وأزرار الحفظ والإرفاق --}}
        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 flex items-center justify-between gap-3">
            <button type="button" onclick="closeScannerModal()" class="px-5 py-2.5 bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-bold transition-colors">
                إلغاء
            </button>

            <button type="button" id="confirmScannerAttachBtn" onclick="attachScannedDocumentsToForm()"
                class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white rounded-xl text-xs font-bold shadow-md flex items-center gap-2 transition-all cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                style="background-color: #059669 !important; color: #ffffff !important;">
                <i class="fas fa-check-circle text-white"></i>
                <span>إرفاق الوثائق بالملف الأمني (<span id="attachCountLabel">0</span>)</span>
            </button>
        </div>

    </div>
</div>

{{-- نافذة معاينة مكبرة للصورة المسحوبة (Lightbox) --}}
<div id="scannerLightbox" class="hidden fixed inset-0 bg-slate-950/85 backdrop-blur-sm flex items-center justify-center p-4" style="z-index: 100000; display: none;" onclick="closeScannerLightbox()">
    <div class="relative max-w-4xl max-h-[90vh] flex flex-col items-center" onclick="event.stopPropagation()">
        <img id="lightboxImg" src="" alt="معاينة الوثيقة" class="max-w-full max-h-[82vh] object-contain rounded-2xl shadow-2xl border border-slate-800">
        <button type="button" onclick="closeScannerLightbox()" class="mt-3 px-4 py-1.5 bg-white/20 hover:bg-white/30 text-white rounded-xl text-xs font-bold transition-colors">
            إغلاق المعاينة
        </button>
    </div>
</div>

{{-- سكريبت إدارة المسح الضوئي --}}
<script>
    // حالة الماسح العام
    window.ScannerApp = {
        targetInputName: 'file_attachments',
        targetLabel: 'مرفقات الملف الأمني',
        pages: [], // { id, dataUrl, width, height, rotation, isDirectPdf, pdfFile, name }
        cameraStream: null,
        activeTab: 'printer', // الافتراضي هو السحب من الطابعة
        activeFilter: 'none',
        selectedDeviceId: null,
        detectedScanner: null, // الجهاز الذي تم التعرف عليه تلقائياً

        // تبديل إظهار/إخفاء الخيارات المتقدمة
        toggleAdvancedSettings: function() {
            const section = document.getElementById('scannerAdvancedSection');
            const chevron = document.getElementById('advancedToggleChevron');
            if (section) {
                const isHidden = section.classList.contains('hidden');
                if (isHidden) {
                    section.classList.remove('hidden');
                    if (chevron) chevron.style.transform = 'rotate(180deg)';
                } else {
                    section.classList.add('hidden');
                    if (chevron) chevron.style.transform = 'rotate(0deg)';
                }
            }
        },

        // تحميل الإعدادات المحفوظة
        loadSavedScannerSettings: function() {
            const savedIp = localStorage.getItem('saved_scanner_ip');
            const ipInput = document.getElementById('networkScannerIp');
            if (savedIp && ipInput) {
                ipInput.value = savedIp;
            }
        },

        // تبديل نمط الاتصال بين USB والشبكة يدوياً
        setConnectionMode: function(mode, autoRefresh = true) {
            const usbConfig = document.getElementById('scannerConfigUsb');
            const netConfig = document.getElementById('scannerConfigNetwork');
            if (mode === 'usb') {
                if (usbConfig) usbConfig.classList.remove('hidden');
                if (netConfig) netConfig.classList.add('hidden');
                if (autoRefresh) this.refreshScannerDevices();
            } else {
                if (usbConfig) usbConfig.classList.add('hidden');
                if (netConfig) netConfig.classList.remove('hidden');
            }
        },

        // الكشف التلقائي الذكي عن الطابعة / الماسح الضوئي المتصل واختياره فوراً
        autoDetectScanner: async function(manualClick = false) {
            const title = document.getElementById('autoScannerTitle');
            const subtitle = document.getElementById('autoScannerSubtitle');
            const badge = document.getElementById('autoScannerBadge');
            const reDetectIcon = document.getElementById('reDetectIcon');
            const startBtnText = document.getElementById('startScanBtnText');

            if (reDetectIcon) reDetectIcon.classList.add('fa-spin');
            if (badge) {
                badge.className = 'px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-300 dark:border-amber-800 flex items-center gap-1';
                badge.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الكشف التلقائي...';
            }
            if (title) title.textContent = 'جاري الكشف عن الطابعة والماسح الضوئي المتصل...';

            try {
                const res = await fetch('{{ route('system.security-files.scanner-devices') }}');
                const data = await res.json();

                if (data.success && data.auto_selected) {
                    this.detectedScanner = data.auto_selected;
                    const auto = data.auto_selected;

                    // تطبيق نوع الاتصال تلقائياً دون الحاجة للاختيار
                    if (auto.type === 'network') {
                        const netRadio = document.querySelector('input[name="scanner_connection_type"][value="network"]');
                        if (netRadio) netRadio.checked = true;
                        this.setConnectionMode('network', false);

                        const ipInput = document.getElementById('networkScannerIp');
                        if (ipInput && auto.ip) ipInput.value = auto.ip;

                        if (title) title.innerHTML = `تم التعرف التلقائي: <span class="text-emerald-700 dark:text-emerald-400 font-bold">${auto.name || 'طابعة شبكة'}</span>`;
                        if (subtitle) {
                            let extra = auto.adf_loaded ? ' (ورق جاهز بوحدة التغذية ADF)' : ' (السحب عبر زجاج الماسح Platen)';
                            subtitle.innerHTML = `<span class="inline-flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> متصلة تلقائياً بالشبكة (${auto.ip})${extra} - جاهزة للسحب الفوري دون الحاجة لاختيار.</span>`;
                        }
                        if (badge) {
                            badge.className = 'px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800 flex items-center gap-1';
                            badge.innerHTML = '<i class="fas fa-check-circle text-emerald-600"></i> متصلة وجاهزة (طابعة شبكة)';
                        }
                        if (startBtnText) {
                            startBtnText.textContent = `بدء السحب من (${auto.name}) الآن`;
                        }
                    } else if (auto.type === 'usb') {
                        const usbRadio = document.querySelector('input[name="scanner_connection_type"][value="usb"]');
                        if (usbRadio) usbRadio.checked = true;
                        this.setConnectionMode('usb', false);

                        if (title) title.innerHTML = `تم التعرف التلقائي: <span class="text-emerald-700 dark:text-emerald-400 font-bold">${auto.name}</span>`;
                        if (subtitle) subtitle.innerHTML = `<span class="inline-flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> متصل عبر كابل USB بنظام ويندوز - جاهز للسحب الفوري دون الحاجة لاختيار.</span>`;
                        if (badge) {
                            badge.className = 'px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800 flex items-center gap-1';
                            badge.innerHTML = '<i class="fas fa-check-circle text-emerald-600"></i> متصل وجاهز (كابل USB)';
                        }
                        if (startBtnText) {
                            startBtnText.textContent = `بدء السحب من (${auto.name}) الآن`;
                        }
                    }

                    // تحديث قائمة أجهزة WIA إذا وُجدت
                    if (data.wia_devices && data.wia_devices.length > 0) {
                        const selectElem = document.getElementById('wiaScannerSelect');
                        if (selectElem) {
                            selectElem.innerHTML = '';
                            data.wia_devices.forEach(dev => {
                                const opt = document.createElement('option');
                                opt.value = dev.DeviceID;
                                opt.textContent = `${dev.Name} (${dev.Description || 'متصل'})`;
                                selectElem.appendChild(opt);
                            });
                        }
                    }
                } else {
                    if (title) title.textContent = 'لم يتم العثور على طابعة متصلة تلقائياً';
                    if (subtitle) subtitle.textContent = 'يرجى التأكد من تشغيل الطابعة أو ربطها بالشبكة أو كابل USB ثم الضغط على "فحص مجدداً".';
                    if (badge) {
                        badge.className = 'px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-300 dark:border-rose-800 flex items-center gap-1';
                        badge.innerHTML = '<i class="fas fa-exclamation-triangle"></i> غير متصل';
                    }
                }
            } catch (err) {
                console.warn('خطأ في الكشف التلقائي عن الطابعة:', err);
                if (title) title.textContent = 'طابعة الشبكة الافتراضية (192.168.1.102)';
                if (subtitle) subtitle.textContent = 'جاهزة للاتصال عبر بروتوكول eSCL/HTTPS';
                if (badge) {
                    badge.className = 'px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-100 text-blue-800 dark:bg-blue-950/60 dark:text-blue-300 border border-blue-300 dark:border-blue-800 flex items-center gap-1';
                    badge.innerHTML = '<i class="fas fa-wifi"></i> طابعة افتراضية';
                }
            } finally {
                if (reDetectIcon) reDetectIcon.classList.remove('fa-spin');
            }
        },

        // تحديث وفحص أجهزة الماسح الضوئي المتصلة عبر USB / WIA
        refreshScannerDevices: async function() {
            const selectElem = document.getElementById('wiaScannerSelect');
            const refreshBtn = document.getElementById('refreshWiaBtn');
            const statusLabel = document.getElementById('wiaStatusLabel');

            if (!selectElem) return;

            if (refreshBtn) {
                refreshBtn.disabled = true;
                const icon = refreshBtn.querySelector('i');
                if (icon) icon.className = 'fas fa-spinner fa-spin';
            }
            if (statusLabel) statusLabel.textContent = 'جاري فحص الأجهزة المتصلة عبر USB وويندوز...';

            try {
                const res = await fetch('{{ route('system.security-files.scanner-devices') }}');
                const data = await res.json();
                selectElem.innerHTML = '';

                if (data.devices && data.devices.length > 0) {
                    data.devices.forEach(dev => {
                        const opt = document.createElement('option');
                        opt.value = dev.DeviceID;
                        opt.textContent = `${dev.Name} (${dev.Description || 'متصل'})`;
                        selectElem.appendChild(opt);
                    });
                    if (statusLabel) statusLabel.textContent = `تم العثور على ${data.devices.length} جهاز ماسح ضوئي متصل.`;
                } else {
                    const opt = document.createElement('option');
                    opt.value = '';
                    opt.textContent = 'الماسح الضوئي الافتراضي لنظام ويندوز (WIA Default)';
                    selectElem.appendChild(opt);
                    if (statusLabel) statusLabel.textContent = 'الماسح الافتراضي جاهز للاستخدام.';
                }
            } catch(e) {
                if (statusLabel) statusLabel.textContent = 'تعذر تحديث الأجهزة المتصلة.';
            } finally {
                if (refreshBtn) {
                    refreshBtn.disabled = false;
                    const icon = refreshBtn.querySelector('i');
                    if (icon) icon.className = 'fas fa-sync-alt';
                }
            }
        },

        // فحص الاتصال بطابعة الشبكة
        testNetworkPrinter: async function() {
            const ip = document.getElementById('networkScannerIp')?.value.trim();
            const btn = document.getElementById('testNetworkBtn');
            const resultBox = document.getElementById('networkTestResult');

            if (!ip) {
                alert('يرجى إدخال عنوان IP الخاص بالطابعة أولاً');
                return;
            }

            if (btn) btn.disabled = true;
            if (resultBox) {
                resultBox.className = 'text-xs mt-1.5 text-slate-500 flex items-center gap-1.5';
                resultBox.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري فحص الاتصال بالطابعة على الشبكة...';
                resultBox.classList.remove('hidden');
            }

            try {
                const res = await fetch(`{{ route('system.security-files.test-network-scanner') }}?ip=${encodeURIComponent(ip)}`);
                const data = await res.json();

                if (data.success) {
                    resultBox.className = 'text-xs mt-1.5 text-emerald-600 dark:text-emerald-400 font-bold flex items-center gap-1.5';
                    resultBox.innerHTML = `<i class="fas fa-check-circle"></i> ${data.message}`;
                    localStorage.setItem('saved_scanner_ip', ip);
                } else {
                    resultBox.className = 'text-xs mt-1.5 text-rose-600 dark:text-rose-400 font-bold flex items-center gap-1.5';
                    resultBox.innerHTML = `<i class="fas fa-exclamation-triangle"></i> ${data.error}`;
                }
            } catch (e) {
                resultBox.className = 'text-xs mt-1.5 text-rose-600 dark:text-rose-400 font-bold flex items-center gap-1.5';
                resultBox.innerHTML = `<i class="fas fa-exclamation-triangle"></i> تعذر الاتصال بالسيرفر لفحص الطابعة.`;
            } finally {
                if (btn) btn.disabled = false;
            }
        },

        // بدء السحب الفيزيائي المباشر من الماسح الضوئي للطابعة
        startHardwareScan: async function() {
            const btn = document.getElementById('startScanBtn');
            const statusContainer = document.getElementById('hardwareScanStatus');
            const statusText = document.getElementById('hardwareScanStatusText');
            const statusSub = document.getElementById('hardwareScanStatusSub');
            const errorBox = document.getElementById('hardwareScanError');
            const errorText = document.getElementById('hardwareScanErrorText');

            if (errorBox) errorBox.classList.add('hidden');
            if (statusContainer) statusContainer.classList.remove('hidden');
            if (btn) {
                btn.disabled = true;
                btn.classList.add('opacity-60', 'cursor-not-allowed');
            }

            // تحديد نمط الاتصال تلقائياً أو وفق المختار
            let mode = document.querySelector('input[name="scanner_connection_type"]:checked')?.value;
            if (!mode && this.detectedScanner) {
                mode = this.detectedScanner.type;
            }
            if (!mode) mode = 'network'; // افتراض طابعة شبكة

            const dpi = parseInt(document.getElementById('scannerDpiSelect')?.value) || 300;
            const colorMode = document.getElementById('scannerColorModeSelect')?.value || 'color';
            const inputSource = document.getElementById('scannerInputSourceSelect')?.value || 'auto';

            const devName = this.detectedScanner?.name || (mode === 'network' ? 'طابعة الشبكة' : 'الماسح الضوئي');
            if (statusText) statusText.textContent = `جاري الاتصال بـ (${devName}) وبدء السحب المباشر...`;
            if (statusSub) statusSub.textContent = 'يرجى الانتظار بينما يقوم محرك الماسح الضوئي بسحب الوثيقة وتصويرها ضوئياً.';

            try {
                let endpoint = '';
                let payload = {};

                if (mode === 'usb') {
                    endpoint = '{{ route('system.security-files.scan-wia') }}';
                    const deviceId = document.getElementById('wiaScannerSelect')?.value || this.detectedScanner?.device_id || '';
                    const colorModeVal = colorMode === 'bw' ? 4 : (colorMode === 'gray' ? 2 : 1);
                    payload = { device_id: deviceId, dpi: dpi, color_mode: colorModeVal };
                } else {
                    endpoint = '{{ route('system.security-files.scan-network') }}';
                    const ip = document.getElementById('networkScannerIp')?.value.trim() || this.detectedScanner?.ip || '192.168.1.102';
                    const port = this.detectedScanner?.port || 443;
                    const proto = this.detectedScanner?.protocol || 'https';
                    localStorage.setItem('saved_scanner_ip', ip);
                    
                    const colorModeStr = colorMode === 'bw' ? 'BlackAndWhite1' : (colorMode === 'gray' ? 'Grayscale8' : 'RGB24');
                    payload = {
                        ip: ip,
                        port: port,
                        protocol: proto,
                        dpi: dpi,
                        color_mode: colorModeStr,
                        input_source: inputSource
                    };
                }

                setTimeout(() => {
                    if (btn && btn.disabled && statusText) {
                        statusText.textContent = `محرك ${devName} يعمل الآن... جاري سحب الوثيقة وتصويرها ضوئياً...`;
                    }
                }, 1000);

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();

                if (data.success && data.base64) {
                    if (statusText) statusText.textContent = `تم استلام الوثيقة بنجاح من ${data.device || devName}!`;
                    setTimeout(() => {
                        if (statusContainer) statusContainer.classList.add('hidden');
                        if (btn) {
                            btn.disabled = false;
                            btn.classList.remove('opacity-60', 'cursor-not-allowed');
                        }
                    }, 800);

                    // إضافة الصفحة تلقائياً إلى المعاينة
                    this.addPage(data.base64, 1200, 1600, data.filename || 'وثيقة_مسحوبة.jpg');

                    // تمرير خفيف نحو المعاينة
                    const previewSection = document.getElementById('scannedPagesContainer');
                    if (previewSection) {
                        previewSection.scrollIntoView({ behavior: 'smooth' });
                    }
                } else {
                    throw new Error(data.error || 'فشل السحب من الماسح الضوئي.');
                }

            } catch (err) {
                if (statusContainer) statusContainer.classList.add('hidden');
                if (btn) {
                    btn.disabled = false;
                    btn.classList.remove('opacity-60', 'cursor-not-allowed');
                }

                if (errorText) errorText.textContent = err.message || 'حدث خطأ أثناء محاولة الاتصال بالماسح.';
                if (errorBox) errorBox.classList.remove('hidden');
            }
        },

        // فتح المودال
        open: function(inputName, labelText) {
            this.targetInputName = inputName || 'file_attachments';
            this.targetLabel = labelText || 'مرفقات الملف';
            document.getElementById('scannerTargetBadge').textContent = this.targetLabel;
            
            // توليد اسم افتراضي تلقائي مع الوقت
            const now = new Date();
            const dateSuffix = `${now.getFullYear()}${String(now.getMonth()+1).padStart(2,'0')}${String(now.getDate()).padStart(2,'0')}_${String(now.getHours()).padStart(2,'0')}${String(now.getMinutes()).padStart(2,'0')}`;
            document.getElementById('scannerPdfFileName').value = `وثيقة_مسحوبة_${dateSuffix}.pdf`;
            document.getElementById('scannerImagesPrefix').value = `وثيقة_مسحوبة_${dateSuffix}`;

            const modal = document.getElementById('documentScannerModal');
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
            document.body.classList.add('overflow-hidden');

            this.updatePagesUI();

            // فتح تبويب الطابعة افتراضياً والبدء بالكشف التلقائي الفوري
            this.switchTab('printer');
            this.loadSavedScannerSettings();
            this.autoDetectScanner();
        },

        // إغلاق المودال
        close: function() {
            this.stopCamera();
            const modal = document.getElementById('documentScannerModal');
            modal.classList.add('hidden');
            modal.style.display = 'none';
            document.body.classList.remove('overflow-hidden');
        },

        // بدء الكاميرا (يتم استدعاؤها فقط عند الانتقال لتبويب الكاميرا)
        initCamera: async function() {
            try {
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    alert('المتصفح لا يدعم الوصول المباشر للكاميرا. يرجى استخدام متصفح حديث أو استخدام تبويب السحب من الطابعة.');
                    this.switchTab('printer');
                    return;
                }

                // جلب قائمة الأجهزة
                const devices = await navigator.mediaDevices.enumerateDevices();
                const videoDevices = devices.filter(d => d.kind === 'videoinput');
                const selectElem = document.getElementById('scannerCameraSelect');
                selectElem.innerHTML = '';
                
                if (videoDevices.length > 0) {
                    videoDevices.forEach((device, index) => {
                        const opt = document.createElement('option');
                        opt.value = device.deviceId;
                        opt.text = device.label || `كاميرا / ماسح رقم ${index + 1}`;
                        if (device.deviceId === this.selectedDeviceId) opt.selected = true;
                        selectElem.appendChild(opt);
                    });
                } else {
                    const opt = document.createElement('option');
                    opt.value = '';
                    opt.text = 'الكاميرا الافتراضية';
                    selectElem.appendChild(opt);
                }

                const constraints = {
                    video: {
                        deviceId: this.selectedDeviceId ? { exact: this.selectedDeviceId } : undefined,
                        width: { ideal: 1920 },
                        height: { ideal: 1080 },
                        facingMode: 'environment'
                    }
                };

                if (this.cameraStream) {
                    this.cameraStream.getTracks().forEach(t => t.stop());
                }

                this.cameraStream = await navigator.mediaDevices.getUserMedia(constraints);
                const video = document.getElementById('scannerVideo');
                video.srcObject = this.cameraStream;
                await video.play();
            } catch (err) {
                console.warn('تعذر فتح الكاميرا:', err);
            }
        },

        // إيقاف الكاميرا
        stopCamera: function() {
            if (this.cameraStream) {
                this.cameraStream.getTracks().forEach(t => t.stop());
                this.cameraStream = null;
            }
            const video = document.getElementById('scannerVideo');
            if (video) video.srcObject = null;
        },

        // التبديل بين التبويبات (printer / camera)
        switchTab: function(tabName) {
            this.activeTab = tabName;
            const tabPrinter = document.getElementById('scannerTabPrinter');
            const tabCamera = document.getElementById('scannerTabCamera');
            const btnPrinter = document.getElementById('tabBtnPrinter');
            const btnCamera = document.getElementById('tabBtnCamera');

            if (tabName === 'printer') {
                tabPrinter.classList.remove('hidden');
                tabCamera.classList.add('hidden');
                btnPrinter.className = 'px-4 py-2.5 text-xs font-bold border-b-2 border-emerald-600 text-emerald-600 dark:text-emerald-400 flex items-center gap-2 transition-colors cursor-pointer';
                btnCamera.className = 'px-4 py-2.5 text-xs font-bold border-b-2 border-transparent text-slate-600 hover:text-slate-950 dark:text-slate-400 dark:hover:text-slate-200 flex items-center gap-2 transition-colors cursor-pointer';
                this.stopCamera();
            } else {
                tabCamera.classList.remove('hidden');
                tabPrinter.classList.add('hidden');
                btnCamera.className = 'px-4 py-2.5 text-xs font-bold border-b-2 border-emerald-600 text-emerald-600 dark:text-emerald-400 flex items-center gap-2 transition-colors cursor-pointer';
                btnPrinter.className = 'px-4 py-2.5 text-xs font-bold border-b-2 border-transparent text-slate-600 hover:text-slate-950 dark:text-slate-400 dark:hover:text-slate-200 flex items-center gap-2 transition-colors cursor-pointer';
                this.initCamera();
            }
        },

        // تغيير الفلتر
        setFilter: function(filterType) {
            this.activeFilter = filterType;
            ['none', 'bw', 'gray'].forEach(f => {
                const btn = document.getElementById(`filterBtn_${f}`);
                if (f === filterType) {
                    btn.className = 'filter-btn px-2.5 py-1 rounded-lg font-bold bg-emerald-600 text-white transition-colors';
                    btn.style.backgroundColor = '#059669';
                    btn.style.color = '#ffffff';
                } else {
                    btn.className = 'filter-btn px-2.5 py-1 rounded-lg text-slate-700 dark:text-slate-300 hover:text-slate-950 dark:hover:text-white transition-colors';
                    btn.style.backgroundColor = '';
                    btn.style.color = '';
                }
            });
        },

        // التقاط صفحة من الكاميرا
        capturePage: function() {
            const video = document.getElementById('scannerVideo');
            if (!video || !video.videoWidth) {
                alert('يرجى التأكد من تشغيل الكاميرا وتوجيهها للمستند');
                return;
            }

            // وميض بصري
            const flash = document.getElementById('scannerFlashEffect');
            flash.classList.remove('opacity-0');
            flash.classList.add('opacity-80');
            setTimeout(() => {
                flash.classList.remove('opacity-80');
                flash.classList.add('opacity-0');
            }, 120);

            const canvas = document.createElement('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            // تطبيق الفلتر
            this.applyFilter(ctx, canvas.width, canvas.height, this.activeFilter);

            const dataUrl = canvas.toDataURL('image/jpeg', 0.92);
            this.addPage(dataUrl, canvas.width, canvas.height);
        },

        // تطبيق فلتر على الكانفاس
        applyFilter: function(ctx, width, height, filterType) {
            if (filterType === 'none') return;
            const imgData = ctx.getImageData(0, 0, width, height);
            const d = imgData.data;
            for (let i = 0; i < d.length; i += 4) {
                const gray = 0.299 * d[i] + 0.587 * d[i + 1] + 0.114 * d[i + 2];
                if (filterType === 'gray') {
                    d[i] = gray;
                    d[i + 1] = gray;
                    d[i + 2] = gray;
                } else if (filterType === 'bw') {
                    const val = gray > 130 ? 255 : 0;
                    d[i] = val;
                    d[i + 1] = val;
                    d[i + 2] = val;
                }
            }
            ctx.putImageData(imgData, 0, 0);
        },

        // إضافة صفحة صورة جديدة
        addPage: function(dataUrl, width, height, customName) {
            const pageObj = {
                id: 'page_' + Date.now() + '_' + Math.random().toString(36).substr(2, 5),
                dataUrl: dataUrl,
                width: width || 1200,
                height: height || 1600,
                rotation: 0,
                isDirectPdf: false,
                name: customName || null
            };
            this.pages.push(pageObj);
            this.updatePagesUI();
        },

        // إضافة ملف PDF مباشر من الطابعة
        addDirectPdf: function(pdfFile) {
            const pageObj = {
                id: 'pdf_' + Date.now() + '_' + Math.random().toString(36).substr(2, 5),
                dataUrl: null,
                isDirectPdf: true,
                pdfFile: pdfFile,
                name: pdfFile.name,
                width: 1200,
                height: 1600,
                rotation: 0
            };
            this.pages.push(pageObj);
            this.updatePagesUI();
        },

        // تدوير صفحة 90 درجة
        rotatePage: function(pageId) {
            const page = this.pages.find(p => p.id === pageId);
            if (!page || page.isDirectPdf) return;

            const img = new Image();
            img.onload = () => {
                const canvas = document.createElement('canvas');
                canvas.width = img.height;
                canvas.height = img.width;
                const ctx = canvas.getContext('2d');
                ctx.translate(canvas.width / 2, canvas.height / 2);
                ctx.rotate((90 * Math.PI) / 180);
                ctx.drawImage(img, -img.width / 2, -img.height / 2);

                page.dataUrl = canvas.toDataURL('image/jpeg', 0.92);
                page.width = canvas.width;
                page.height = canvas.height;
                page.rotation = (page.rotation + 90) % 360;

                this.updatePagesUI();
            };
            img.src = page.dataUrl;
        },

        // حذف صفحة
        deletePage: function(pageId) {
            this.pages = this.pages.filter(p => p.id !== pageId);
            this.updatePagesUI();
        },

        // مسح كافة الصفحات
        clearAll: function() {
            if (this.pages.length === 0) return;
            if (confirm('هل أنت متأكد من مسح جميع الصفحات المسحوبة؟')) {
                this.pages = [];
                this.updatePagesUI();
            }
        },

        // تحديث واجهة الصفحات المسحوبة
        updatePagesUI: function() {
            const grid = document.getElementById('scannedPagesGrid');
            const emptyState = document.getElementById('scannedPagesEmptyState');
            const countBadge = document.getElementById('scannedPagesCountBadge');
            const clearBtn = document.getElementById('clearScannedPagesBtn');
            const attachCountLabel = document.getElementById('attachCountLabel');
            const confirmBtn = document.getElementById('confirmScannerAttachBtn');

            const count = this.pages.length;
            countBadge.textContent = `${count} ${count === 1 ? 'صفحة/ملف' : 'صفحات/ملفات'}`;
            attachCountLabel.textContent = count;

            if (count === 0) {
                grid.classList.add('hidden');
                grid.innerHTML = '';
                emptyState.classList.remove('hidden');
                clearBtn.classList.add('hidden');
                confirmBtn.disabled = true;
                return;
            }

            confirmBtn.disabled = false;
            emptyState.classList.add('hidden');
            clearBtn.classList.remove('hidden');
            grid.classList.remove('hidden');
            grid.innerHTML = '';

            this.pages.forEach((page, index) => {
                const card = document.createElement('div');
                card.className = 'relative bg-white dark:bg-slate-800 p-2 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs flex flex-col group';
                
                if (page.isDirectPdf) {
                    card.innerHTML = `
                        <div class="relative w-full aspect-[3/4] bg-rose-50 dark:bg-rose-950/30 rounded-lg overflow-hidden flex flex-col items-center justify-center p-3 text-center border border-rose-200 dark:border-rose-800/40">
                            <i class="fas fa-file-pdf text-4xl text-rose-500 mb-2"></i>
                            <span class="text-[11px] font-bold text-slate-800 dark:text-slate-200 truncate w-full" title="${page.name}">${page.name}</span>
                            <span class="text-[10px] text-slate-500 font-mono mt-1">${(page.pdfFile.size/1024).toFixed(0)} KB</span>
                            <span class="absolute top-1.5 right-1.5 px-2 py-0.5 bg-rose-600 text-white rounded-md text-[10px] font-bold">
                                PDF طابعة
                            </span>
                        </div>
                        <div class="flex items-center justify-end gap-1 mt-2 pt-1 border-t border-slate-200 dark:border-slate-700">
                            <button type="button" onclick="ScannerApp.deletePage('${page.id}')" title="حذف هذا الملف"
                                class="p-1.5 text-slate-600 dark:text-slate-300 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-slate-700 rounded-lg text-xs transition-colors">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    `;
                } else {
                    card.innerHTML = `
                        <div class="relative w-full aspect-[3/4] bg-slate-100 dark:bg-slate-900 rounded-lg overflow-hidden flex items-center justify-center">
                            <img src="${page.dataUrl}" alt="صفحة ${index + 1}" class="w-full h-full object-contain">
                            <span class="absolute top-1.5 right-1.5 px-2 py-0.5 bg-slate-900/80 text-white rounded-md text-[10px] font-bold">
                                صفحة ${index + 1}
                            </span>
                        </div>
                        <div class="flex items-center justify-between gap-1 mt-2 pt-1 border-t border-slate-200 dark:border-slate-700">
                            <button type="button" onclick="ScannerApp.rotatePage('${page.id}')" title="تدوير 90 درجة"
                                class="p-1.5 text-slate-600 dark:text-slate-300 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-slate-700 rounded-lg text-xs transition-colors">
                                <i class="fas fa-redo"></i>
                            </button>
                            <button type="button" onclick="ScannerApp.previewPage('${page.id}')" title="معاينة بحجم كامل"
                                class="p-1.5 text-slate-600 dark:text-slate-300 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-slate-700 rounded-lg text-xs transition-colors">
                                <i class="fas fa-search-plus"></i>
                            </button>
                            <button type="button" onclick="ScannerApp.deletePage('${page.id}')" title="حذف هذه الصفحة"
                                class="p-1.5 text-slate-600 dark:text-slate-300 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-slate-700 rounded-lg text-xs transition-colors">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    `;
                }
                grid.appendChild(card);
            });
        },

        // معاينة صفحة مكبرة
        previewPage: function(pageId) {
            const page = this.pages.find(p => p.id === pageId);
            if (!page || page.isDirectPdf) return;
            const lightbox = document.getElementById('scannerLightbox');
            const img = document.getElementById('lightboxImg');
            img.src = page.dataUrl;
            lightbox.classList.remove('hidden');
            lightbox.style.display = 'flex';
        },

        // إرفاق الوثائق المسحوبة بالنموذج
        attachToForm: async function() {
            if (this.pages.length === 0) {
                alert('يرجى سحب أو التقاط صفحة واحدة على الأقل أولاً.');
                return;
            }

            const formatRadio = document.querySelector('input[name="scanner_format"]:checked');
            const format = formatRadio ? formatRadio.value : 'pdf';

            const confirmBtn = document.getElementById('confirmScannerAttachBtn');
            const originalBtnHtml = confirmBtn.innerHTML;
            confirmBtn.disabled = true;
            confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري تجهيز الملفات...';

            try {
                let generatedFiles = [];

                // فصل الصفحات العادية عن ملفات PDF المباشرة من الطابعة
                const imgPages = this.pages.filter(p => !p.isDirectPdf);
                const directPdfs = this.pages.filter(p => p.isDirectPdf);

                // إضافة ملفات PDF المباشرة التي تم سحبها من الطابعة
                directPdfs.forEach(dp => {
                    generatedFiles.push(dp.pdfFile);
                });

                if (imgPages.length > 0) {
                    if (format === 'pdf') {
                        // توليد PDF مجمع للصفحات
                        const rawName = document.getElementById('scannerPdfFileName').value.trim() || 'وثيقة_أمنية_مسحوبة.pdf';
                        const fileName = rawName.endsWith('.pdf') ? rawName : `${rawName}.pdf`;

                        const pdfFile = await this.buildPdfFile(imgPages, fileName);
                        generatedFiles.push(pdfFile);
                    } else {
                        // حفظ كصور منفصلة
                        const prefix = document.getElementById('scannerImagesPrefix').value.trim() || 'وثيقة_مسحوبة';
                        for (let i = 0; i < imgPages.length; i++) {
                            const page = imgPages[i];
                            const blob = await (await fetch(page.dataUrl)).blob();
                            const fileName = `${prefix}_صفحة_${i + 1}.jpg`;
                            generatedFiles.push(new File([blob], fileName, { type: 'image/jpeg', lastModified: Date.now() }));
                        }
                    }
                }

                // إدراج الملفات في حقل الإدخال المستهدف
                const targetInput = document.getElementById(this.targetInputName + '_input') || document.querySelector(`input[name="${this.targetInputName}[]"]`);
                if (targetInput) {
                    const dt = new DataTransfer();
                    // إضافة الملفات الموجودة مسبقاً إذا وُجدت
                    if (targetInput.files && targetInput.files.length > 0) {
                        for (let i = 0; i < targetInput.files.length; i++) {
                            dt.items.add(targetInput.files[i]);
                        }
                    }
                    // إضافة الملفات المسحوبة الجديدة
                    generatedFiles.forEach(f => dt.items.add(f));
                    targetInput.files = dt.files;

                    // تحديث قائمة العرض أسفل الحقل
                    renderFormAttachmentsList(this.targetInputName);
                }

                // تصفير الصفحات وإغلاق المودال
                this.pages = [];
                this.close();

                // رسالة نجاح
                showScannerToast(`تم إرفاق ${generatedFiles.length} وثيقة بنجاح في ${this.targetLabel}`);
            } catch (err) {
                console.error('خطأ في إرفاق الوثائق:', err);
                alert('حدث خطأ أثناء معالجة الملفات: ' + err.message);
            } finally {
                confirmBtn.disabled = false;
                confirmBtn.innerHTML = originalBtnHtml;
            }
        },

        // بناء ملف PDF مجمع بحجم A4
        buildPdfFile: async function(pages, fileName) {
            if (!window.jspdf || !window.jspdf.jsPDF) {
                throw new Error('مكتبة إنشاء ملفات الـ PDF غير محملة بشكل صحيح.');
            }

            const { jsPDF } = window.jspdf;
            const firstPage = pages[0];
            const isLandscape = firstPage.width > firstPage.height;

            const doc = new jsPDF({
                orientation: isLandscape ? 'landscape' : 'portrait',
                unit: 'mm',
                format: 'a4'
            });

            for (let i = 0; i < pages.length; i++) {
                const page = pages[i];
                const pageIsLandscape = page.width > page.height;

                if (i > 0) {
                    doc.addPage('a4', pageIsLandscape ? 'landscape' : 'portrait');
                }

                const pageWidth = pageIsLandscape ? 297 : 210;
                const pageHeight = pageIsLandscape ? 210 : 297;

                // هامش 6 مم
                const margin = 6;
                const printableWidth = pageWidth - (margin * 2);
                const printableHeight = pageHeight - (margin * 2);

                const imgRatio = page.width / page.height;
                const printRatio = printableWidth / printableHeight;

                let renderW, renderH;
                if (imgRatio > printRatio) {
                    renderW = printableWidth;
                    renderH = printableWidth / imgRatio;
                } else {
                    renderH = printableHeight;
                    renderW = printableHeight * imgRatio;
                }

                const posX = margin + ((printableWidth - renderW) / 2);
                const posY = margin + ((printableHeight - renderH) / 2);

                doc.addImage(page.dataUrl, 'JPEG', posX, posY, renderW, renderH, undefined, 'FAST');
            }

            const blob = doc.output('blob');
            return new File([blob], fileName, { type: 'application/pdf', lastModified: Date.now() });
        }
    };

    // دوال الربط المباشرة مع الأزرار
    function openDocumentScanner(inputName, labelText) {
        ScannerApp.open(inputName, labelText);
    }
    function closeScannerModal() {
        ScannerApp.close();
    }
    function switchScannerTab(tab) {
        ScannerApp.switchTab(tab);
    }
    function setScannerFilter(filter) {
        ScannerApp.setFilter(filter);
    }
    function captureScannerPage() {
        ScannerApp.capturePage();
    }
    function clearAllScannedPages() {
        ScannerApp.clearAll();
    }
    function closeScannerLightbox() {
        const lightbox = document.getElementById('scannerLightbox');
        lightbox.classList.add('hidden');
        lightbox.style.display = 'none';
    }
    function attachScannedDocumentsToForm() {
        ScannerApp.attachToForm();
    }
    function changeScannerCamera(deviceId) {
        ScannerApp.selectedDeviceId = deviceId;
        ScannerApp.initCamera();
    }

    // تحديث خيارات التسمية حسب صيغة الإخراج
    function updateFormatSelection() {
        const val = document.querySelector('input[name="scanner_format"]:checked').value;
        const cardPdf = document.getElementById('formatCardPdf');
        const cardImages = document.getElementById('formatCardImages');
        const pdfContainer = document.getElementById('pdfNameContainer');
        const imagesContainer = document.getElementById('imagesNameContainer');

        if (val === 'pdf') {
            cardPdf.classList.add('border-emerald-500');
            cardPdf.classList.remove('border-slate-200', 'dark:border-slate-700');
            cardImages.classList.remove('border-emerald-500');
            cardImages.classList.add('border-slate-200', 'dark:border-slate-700');
            pdfContainer.classList.remove('hidden');
            imagesContainer.classList.add('hidden');
        } else {
            cardImages.classList.add('border-emerald-500');
            cardImages.classList.remove('border-slate-200', 'dark:border-slate-700');
            cardPdf.classList.remove('border-emerald-500');
            cardPdf.classList.add('border-slate-200', 'dark:border-slate-700');
            imagesContainer.classList.remove('hidden');
            pdfContainer.classList.add('hidden');
        }
    }

    // معالجة اختيار الملفات عبر السحب أو الاختيار المباشر من الطابعة / السكانر
    function handleScannerFileInput(files) {
        if (!files || !files.length) return;
        Array.from(files).forEach(file => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = new Image();
                    img.onload = function() {
                        ScannerApp.addPage(e.target.result, img.width, img.height, file.name);
                        showScannerToast(`تم استلام الوثيقة: ${file.name}`);
                    };
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            } else if (file.type === 'application/pdf' || file.name.endsWith('.pdf')) {
                // مستند PDF مباشر من الطابعة
                ScannerApp.addDirectPdf(file);
                showScannerToast(`تم استلام مستند PDF من الطابعة: ${file.name}`);
            }
        });
        document.getElementById('scannerDirectFileInput').value = '';
    }

    function handleScannerDrop(e) {
        e.preventDefault();
        e.currentTarget.classList.remove('border-emerald-500');
        if (e.dataTransfer && e.dataTransfer.files) {
            handleScannerFileInput(e.dataTransfer.files);
        }
    }

    // الاستماع لحدث اللصق (Ctrl + V) لإدراج وثائق الماسح فوراً
    window.addEventListener('paste', function(e) {
        const modal = document.getElementById('documentScannerModal');
        if (!modal || modal.style.display === 'none' || modal.classList.contains('hidden')) return;

        const items = (e.clipboardData || e.originalEvent.clipboardData).items;
        for (let item of items) {
            if (item.type.indexOf('image') !== -1) {
                const blob = item.getAsFile();
                const reader = new FileReader();
                reader.onload = function(event) {
                    const img = new Image();
                    img.onload = function() {
                        ScannerApp.addPage(event.target.result, img.width, img.height);
                        showScannerToast('تم إدراج الوثيقة الممسوحة من الحافظة/الطابعة بنجاح!');
                    };
                    img.src = event.target.result;
                };
                reader.readAsDataURL(blob);
                break;
            }
        }
    });

    // اختصار لوحة المفاتيح: المسافة للالتقاط في تبويب الكاميرا، و Esc للإغلاق
    window.addEventListener('keydown', function(e) {
        const modal = document.getElementById('documentScannerModal');
        if (!modal || modal.style.display === 'none' || modal.classList.contains('hidden')) return;

        if (e.key === ' ' && ScannerApp.activeTab === 'camera') {
            if (document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA') {
                e.preventDefault();
                ScannerApp.capturePage();
            }
        } else if (e.key === 'Escape') {
            closeScannerLightbox();
            ScannerApp.close();
        }
    });

    // إشعار عائم Toast
    function showScannerToast(message) {
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-5 left-5 z-[120] px-4 py-2.5 bg-slate-900/95 text-white text-xs font-bold rounded-xl shadow-xl border border-slate-700 flex items-center gap-2 animate-bounce';
        toast.style.zIndex = '100001';
        toast.innerHTML = `<i class="fas fa-check-circle text-emerald-400"></i> <span>${message}</span>`;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3500);
    }

    // إدارة وتحديث قائمة المرفقات المعروضة أسفل حقول الإدخال في الصفحة الرئيسية
    function renderFormAttachmentsList(inputName) {
        const targetInput = document.getElementById(inputName + '_input') || document.querySelector(`input[name="${inputName}[]"]`);
        const listContainer = document.getElementById(inputName + '_preview_list');
        if (!targetInput || !listContainer) return;

        const files = targetInput.files;
        if (!files || files.length === 0) {
            listContainer.innerHTML = '';
            listContainer.classList.add('hidden');
            return;
        }

        listContainer.classList.remove('hidden');
        listContainer.innerHTML = '';

        Array.from(files).forEach((file, index) => {
            const isPdf = file.type === 'application/pdf' || file.name.endsWith('.pdf');
            const isImg = file.type.startsWith('image/');
            const iconClass = isPdf ? 'fa-file-pdf text-rose-500' : (isImg ? 'fa-file-image text-emerald-500' : 'fa-file text-slate-400');
            const sizeStr = (file.size / 1024).toFixed(1) + ' ك.ب';

            const item = document.createElement('div');
            item.className = 'flex items-center justify-between p-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs shadow-xs';
            item.innerHTML = `
                <div class="flex items-center gap-2 min-w-0">
                    <i class="fas ${iconClass} text-sm"></i>
                    <span class="font-bold text-slate-800 dark:text-slate-200 truncate">${file.name}</span>
                    <span class="text-[11px] text-slate-500 dark:text-slate-400 font-mono">(${sizeStr})</span>
                </div>
                <button type="button" onclick="removeFileFromFormInput('${inputName}', ${index})" class="text-rose-500 hover:text-rose-700 p-1 transition-colors" title="إزالة هذا المرفق">
                    <i class="fas fa-times"></i>
                </button>
            `;
            listContainer.appendChild(item);
        });
    }

    // إزالة ملف محدد من حقل الإدخال
    function removeFileFromFormInput(inputName, removeIndex) {
        const targetInput = document.getElementById(inputName + '_input') || document.querySelector(`input[name="${inputName}[]"]`);
        if (!targetInput || !targetInput.files) return;

        const dt = new DataTransfer();
        Array.from(targetInput.files).forEach((file, idx) => {
            if (idx !== removeIndex) {
                dt.items.add(file);
            }
        });
        targetInput.files = dt.files;
        renderFormAttachmentsList(inputName);
    }

    // ربط تغيير حقول الإدخال لتحديث القائمة تلقائياً
    document.addEventListener('DOMContentLoaded', function() {
        if (window.ScannerApp && typeof window.ScannerApp.loadSavedScannerSettings === 'function') {
            window.ScannerApp.loadSavedScannerSettings();
        }

        ['file_attachments', 'summary_attachments'].forEach(name => {
            const input = document.getElementById(name + '_input') || document.querySelector(`input[name="${name}[]"]`);
            if (input) {
                input.addEventListener('change', function() {
                    renderFormAttachmentsList(name);
                });
            }
        });
    });
</script>
