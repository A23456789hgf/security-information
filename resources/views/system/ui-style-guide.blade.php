@extends('layouts.app')

@section('title', 'دليل التنسيقات والأنماط (UI Style Guide)')
@section('page_title', 'دليل التنسيقات والأنماط (UI Style Guide)')

@section('content')
<div class="space-y-10">
    
    <!-- Intro Banner -->
    <div class="relative overflow-hidden bg-gradient-to-r from-brand-600 via-indigo-600 to-brand-700 text-white p-8 rounded-3xl shadow-xl shadow-brand-500/10">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/20 text-white backdrop-blur-md mb-3 border border-white/20">
                    <i class="fas fa-sparkles text-amber-300"></i> نظام التصميم الموحد Modern UI
                </span>
                <h1 class="text-3xl font-extrabold tracking-tight">نظام الأنماط والتصميم المتكامل</h1>
                <p class="text-white/80 mt-2 text-sm max-w-2xl leading-relaxed">
                    توثيق شامل لكافة عناصر واجهة المستخدم، الرموز، البطاقات، الجداول، وأنماط الألوان المتوافقة بنسبة 100% مع الوضعين الفاتح والداكن (Dark Mode).
                </p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="toggleTheme()" class="px-5 py-2.5 rounded-2xl bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/20 text-white font-bold text-sm transition-all flex items-center gap-2">
                    <i class="fas fa-moon dark:hidden"></i>
                    <i class="fas fa-sun hidden dark:inline"></i>
                    <span>تبديل الوضع</span>
                </button>
            </div>
        </div>
    </div>

    {{-- 1. Typography & Hierarchy --}}
    <section class="space-y-4">
        <div class="flex items-center gap-3 border-r-4 border-brand-500 pr-3">
            <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                <i class="fas fa-font text-brand-500"></i> طباعة النصوص (Typography)
            </h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-6 rounded-2xl shadow-sm space-y-4">
                <h6 class="text-xs font-bold text-slate-400 uppercase tracking-wider">التسلسل الهرمي للعناوين</h6>
                <div class="space-y-3">
                    <h1 class="text-3xl font-black text-slate-900 dark:text-white">عنوان مستوى H1 (30px / 900)</h1>
                    <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white">عنوان مستوى H2 (24px / 800)</h2>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">عنوان مستوى H3 (20px / 700)</h3>
                    <h4 class="text-lg font-semibold text-slate-800 dark:text-slate-200">عنوان مستوى H4 (18px / 600)</h4>
                    <h5 class="text-base font-medium text-slate-800 dark:text-slate-200">عنوان مستوى H5 (16px / 500)</h5>
                    <h6 class="text-sm font-medium text-slate-700 dark:text-slate-300">عنوان مستوى H6 (14px / 500)</h6>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-6 rounded-2xl shadow-sm space-y-4">
                <h6 class="text-xs font-bold text-slate-400 uppercase tracking-wider">الفقرات والنصوص</h6>
                <p class="text-lg font-medium text-slate-800 dark:text-slate-200 leading-relaxed">هذا نص تمهيدي (Lead Text) يستخدم في مقدمات الصفحات والملخصات لتوفير وضوح عالي.</p>
                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                    هذا نص عادي (Body Text) يوضح سهولة القراءة وتناسق الخط العربي Cairo عبر كافة أحجام الشاشات. تم ضبط ارتفاع السطور والتباعد بدقة لتوفير تجربة بصرية مريحة للعينين.
                </p>
                <p class="text-xs text-slate-400 dark:text-slate-500">هذا نص ثانوي (Muted Text) للتفاصيل الإضافية، التواريخ، وملاحظات التلميح.</p>
                <div class="pt-2 flex items-center gap-4">
                    <a href="#" class="text-brand-600 dark:text-brand-400 font-bold text-sm hover:underline flex items-center gap-1">
                        رابط أساسي نشط <i class="fas fa-arrow-left text-xs"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- 2. Color Tokens --}}
    <section class="space-y-4">
        <div class="flex items-center gap-3 border-r-4 border-brand-500 pr-3">
            <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                <i class="fas fa-palette text-brand-500"></i> لوحة الألوان والسمات (Color Palette)
            </h2>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
                <div class="h-20 bg-brand-600 flex items-center justify-center text-white font-bold">Brand</div>
                <div class="p-3 text-center">
                    <p class="font-bold text-xs text-slate-800 dark:text-slate-200">#3B82F6</p>
                    <code class="text-[10px] text-slate-400">bg-brand-600</code>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
                <div class="h-20 bg-emerald-500 flex items-center justify-center text-white font-bold">Success</div>
                <div class="p-3 text-center">
                    <p class="font-bold text-xs text-slate-800 dark:text-slate-200">#10B981</p>
                    <code class="text-[10px] text-slate-400">bg-emerald-500</code>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
                <div class="h-20 bg-amber-500 flex items-center justify-center text-white font-bold">Warning</div>
                <div class="p-3 text-center">
                    <p class="font-bold text-xs text-slate-800 dark:text-slate-200">#F59E0B</p>
                    <code class="text-[10px] text-slate-400">bg-amber-500</code>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
                <div class="h-20 bg-rose-500 flex items-center justify-center text-white font-bold">Danger</div>
                <div class="p-3 text-center">
                    <p class="font-bold text-xs text-slate-800 dark:text-slate-200">#EF4444</p>
                    <code class="text-[10px] text-slate-400">bg-rose-500</code>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
                <div class="h-20 bg-purple-500 flex items-center justify-center text-white font-bold">Purple</div>
                <div class="p-3 text-center">
                    <p class="font-bold text-xs text-slate-800 dark:text-slate-200">#A855F7</p>
                    <code class="text-[10px] text-slate-400">bg-purple-500</code>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
                <div class="h-20 bg-slate-900 flex items-center justify-center text-white font-bold">Dark Slate</div>
                <div class="p-3 text-center">
                    <p class="font-bold text-xs text-slate-800 dark:text-slate-200">#0F172A</p>
                    <code class="text-[10px] text-slate-400">bg-slate-900</code>
                </div>
            </div>
        </div>
    </section>

    {{-- 3. Buttons --}}
    <section class="space-y-4">
        <div class="flex items-center gap-3 border-r-4 border-brand-500 pr-3">
            <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                <i class="fas fa-mouse text-brand-500"></i> أنماط الأزرار (Buttons)
            </h2>
        </div>
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-6 rounded-2xl shadow-sm">
            <div class="flex flex-wrap items-center gap-4">
                <button class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-2xl shadow-md shadow-brand-500/20 transition-all flex items-center gap-2 text-sm">
                    <i class="fas fa-plus"></i> زر رئيسي (Brand Primary)
                </button>
                <button class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl shadow-md shadow-emerald-500/20 transition-all flex items-center gap-2 text-sm">
                    <i class="fas fa-check"></i> زر حفظ (Success)
                </button>
                <button class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-2xl shadow-md shadow-rose-500/20 transition-all flex items-center gap-2 text-sm">
                    <i class="fas fa-trash"></i> زر حذف (Danger)
                </button>
                <button class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-2xl shadow-md shadow-amber-500/20 transition-all flex items-center gap-2 text-sm">
                    <i class="fas fa-edit"></i> زر تعديل (Warning)
                </button>
                <button class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-2xl transition-all flex items-center gap-2 text-sm">
                    <i class="fas fa-arrow-right"></i> زر ثانوي (Secondary)
                </button>
                <button class="px-5 py-2.5 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold rounded-2xl transition-all flex items-center gap-2 text-sm">
                    <i class="fas fa-download"></i> تصدير (Outline)
                </button>
            </div>
        </div>
    </section>

    {{-- 4. Badges & Tags --}}
    <section class="space-y-4">
        <div class="flex items-center gap-3 border-r-4 border-brand-500 pr-3">
            <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                <i class="fas fa-tags text-brand-500"></i> الشارات والأوسمة (Badges)
            </h2>
        </div>
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-6 rounded-2xl shadow-sm">
            <div class="flex flex-wrap items-center gap-3">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-brand-50 text-brand-700 border border-brand-200 dark:bg-brand-900/30 dark:text-brand-300 dark:border-brand-800/50">
                    <span class="w-1.5 h-1.5 rounded-full bg-brand-500"></span> نشط (Brand)
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-300 dark:border-emerald-800/50">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> مكتمل (Success)
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-900/30 dark:text-amber-300 dark:border-amber-800/50">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> قيد المراجعة (Pending)
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-900/30 dark:text-rose-300 dark:border-rose-800/50">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> محظور (Danger)
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200 dark:bg-purple-900/30 dark:text-purple-300 dark:border-purple-800/50">
                    <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span> مسؤول النظام (Admin)
                </span>
            </div>
        </div>
    </section>

    {{-- 5. Modern Data Table --}}
    <section class="space-y-4">
        <div class="flex items-center gap-3 border-r-4 border-brand-500 pr-3">
            <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                <i class="fas fa-table text-brand-500"></i> نمط الجداول (Modern Data Table)
            </h2>
        </div>
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-right border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800 text-slate-400 dark:text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                            <th class="py-4 px-6">المعرف</th>
                            <th class="py-4 px-6">الاسم الكامل</th>
                            <th class="py-4 px-6">البريد الإلكتروني</th>
                            <th class="py-4 px-6">الحالة</th>
                            <th class="py-4 px-6 text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-sm">
                        <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="py-4 px-6 font-bold text-slate-900 dark:text-white">#1001</td>
                            <td class="py-4 px-6 text-slate-800 dark:text-slate-200 font-medium">أحمد محمد علي</td>
                            <td class="py-4 px-6 text-slate-500 dark:text-slate-400">ahmed@example.com</td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-300 dark:border-emerald-800/50">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> نشط
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button class="p-2 text-brand-600 hover:bg-brand-50 dark:hover:bg-brand-900/30 rounded-xl transition-all">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="p-2 text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/30 rounded-xl transition-all">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

</div>
@endsection


