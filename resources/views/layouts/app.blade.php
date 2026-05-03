{{--
Professional Light Dashboard Layout (app.blade.php)
- الوضع النهاري بالكامل (Full Light Mode)
- دعم كامل للغة العربية (RTL)
- تصميم عصري (Minimalist & Clean)
--}}

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'لوحة التحكم')</title>

    {{-- الخطوط: Tajawal للواجهات و Cairo للعناوين --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Cairo:wght@600;800&family=Tajawal:wght@400;500;700&display=swap"
        rel="stylesheet">

    {{-- Tailwind CSS via CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Bootstrap RTL for Grid & Components support --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f8fafc;
            color: #334155;
        }

        .header-font {
            font-family: 'Cairo', sans-serif;
        }

        /* المكونات المخصصة */
        .bg-gradient-primary {
            background: linear-gradient(135deg, #4361ee 0%, #3f37c9 100%) !important;
        }

        .bg-soft-primary {
            background-color: rgba(67, 97, 238, 0.1) !important;
            color: #4361ee !important;
        }

        .bg-soft-success {
            background-color: rgba(16, 185, 129, 0.1) !important;
            color: #10b981 !important;
        }

        .bg-soft-warning {
            background-color: rgba(245, 158, 11, 0.1) !important;
            color: #f59e0b !important;
        }

        .bg-soft-danger {
            background-color: rgba(239, 68, 68, 0.1) !important;
            color: #ef4444 !important;
        }

        .bg-soft-info {
            background-color: rgba(6, 182, 212, 0.1) !important;
            color: #06b8d4 !important;
        }

        .soft-shadow {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
        }

        /* تحسينات القوائم والأزرار */
        .hover-lift {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .hover-lift:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        }

        /* تخصيص السكرول بار */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        /* تخصيص القائمة المنسدلة للمستخدم */
        .dropdown-menu-custom {
            min-width: 180px;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
            margin-top: 8px;
        }
    </style>
</head>

<body class="antialiased">

    <div class="min-h-screen flex">

        {{-- القائمة الجانبية (Sidebar) --}}
        <aside id="sidebar"
            class="w-72 bg-white border-l border-slate-200 fixed lg:relative z-50 h-screen transition-transform -translate-x-full lg:translate-x-0 soft-shadow overflow-y-auto">
            <div class="h-20 flex items-center px-8 border-b border-slate-100 sticky top-0 bg-white z-10">
                <div
                    class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-200">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-3.682A23.684 23.684 0 015.558 13.5M12 11a23.684 23.684 0 01-1.242-7.5m1.242 7.5c1.744 2.772 2.753 6.054 2.753 9.571m3.44-3.682c.395-1.558.602-3.197.602-4.882 0-2.435-.444-4.767-1.242-6.918"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
                <span class="mr-3 text-xl font-extrabold text-slate-800 header-font tracking-tight">نظام الإدارة</span>
            </div>

            <nav class="p-6 space-y-1 overflow-y-auto" style="max-height: calc(100vh - 160px);">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4 px-4">القائمة الرئيسية</p>

                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700 border-blue-100/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} rounded-2xl font-bold transition-all border border-transparent">
                    <i class="fas fa-chart-pie w-5 h-5 flex items-center justify-center"></i>
                    <span>لوحة التحكم</span>
                </a>

                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-6 mb-4 px-4">إدارة الأفراد
                </p>

                <a href="{{ route('system.personal-data.index') }}"
                    class="flex items-center gap-3 px-4 py-3 {{ request()->is('system/personal-data*') ? 'bg-blue-50 text-blue-700 border-blue-100/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} rounded-2xl font-bold transition-all border border-transparent">
                    <i class="fas fa-users w-5 h-5 flex items-center justify-center"></i>
                    <span>البيانات الشخصية</span>
                </a>

                <a href="{{ route('system.secondary-data.index') }}"
                    class="flex items-center gap-3 px-4 py-3 {{ request()->is('system/secondary-data*') ? 'bg-blue-50 text-blue-700 border-blue-100/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} rounded-2xl font-bold transition-all border border-transparent">
                    <i class="fas fa-address-card w-5 h-5 flex items-center justify-center"></i>
                    <span>البيانات الثانوية</span>
                </a>

                <a href="{{ route('system.wives.index') }}"
                    class="flex items-center gap-3 px-4 py-3 {{ request()->is('system/wives*') ? 'bg-blue-50 text-blue-700 border-blue-100/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} rounded-2xl font-bold transition-all border border-transparent">
                    <i class="fas fa-female w-5 h-5 flex items-center justify-center"></i>
                    <span>بيانات الزوجات</span>
                </a>

                <a href="{{ route('system.courses.index') }}"
                    class="flex items-center gap-3 px-4 py-3 {{ request()->is('system/courses*') ? 'bg-blue-50 text-blue-700 border-blue-100/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} rounded-2xl font-bold transition-all border border-transparent">
                    <i class="fas fa-graduation-cap w-5 h-5 flex items-center justify-center"></i>
                    <span>الدورات التدريبية</span>
                </a>

                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-6 mb-4 px-4">السجلات الأمنية
                </p>

                <a href="{{ route('system.arrests.index') }}"
                    class="flex items-center gap-3 px-4 py-3 {{ request()->is('system/arrests*') ? 'bg-blue-50 text-blue-700 border-blue-100/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} rounded-2xl font-bold transition-all border border-transparent">
                    <i class="fas fa-user-lock w-5 h-5 flex items-center justify-center"></i>
                    <span>سجلات الضبط</span>
                </a>

                <a href="{{ route('system.suspicious.index') }}"
                    class="flex items-center gap-3 px-4 py-3 {{ request()->is('system/suspicious*') ? 'bg-blue-50 text-blue-700 border-blue-100/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} rounded-2xl font-bold transition-all border border-transparent">
                    <i class="fas fa-user-secret w-5 h-5 flex items-center justify-center"></i>
                    <span>المشتبه بهم</span>
                </a>

                <a href="{{ route('system.security-reports.index') }}"
                    class="flex items-center gap-3 px-4 py-3 {{ request()->is('system/security-reports*') ? 'bg-blue-50 text-blue-700 border-blue-100/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} rounded-2xl font-bold transition-all border border-transparent">
                    <i class="fas fa-file-shield w-5 h-5 flex items-center justify-center"></i>
                    <span>البلاغات الأمنية</span>
                </a>

                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-6 mb-4 px-4">أدوات البيانات
                </p>

                <a href="{{ route('system.pages.index') }}"
                    class="flex items-center gap-3 px-4 py-3 {{ request()->is('system/pages*') ? 'bg-blue-50 text-blue-700 border-blue-100/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} rounded-2xl font-bold transition-all border border-transparent">
                    <i class="fas fa-layer-group w-5 h-5 flex items-center justify-center"></i>
                    <span>الصفحات الديناميكية</span>
                </a>

                <a href="{{ route('data.import.index') }}"
                    class="flex items-center gap-3 px-4 py-3 {{ request()->is('data/import*') ? 'bg-blue-50 text-blue-700 border-blue-100/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} rounded-2xl font-bold transition-all border border-transparent">
                    <i class="fas fa-file-import w-5 h-5 flex items-center justify-center"></i>
                    <span>استيراد البيانات</span>
                </a>

                <a href="{{ route('data.differentiation.index') }}"
                    class="flex items-center gap-3 px-4 py-3 {{ request()->is('data/differentiation*') ? 'bg-blue-50 text-blue-700 border-blue-100/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} rounded-2xl font-bold transition-all border border-transparent">
                    <i class="fas fa-code-compare w-5 h-5 flex items-center justify-center"></i>
                    <span>مقارنة وتمايز البيانات</span>
                </a>

                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-6 mb-4 px-4">الترميزات
                    الجغرافية</p>

                <a href="{{ route('configuration.countries.index') }}"
                    class="flex items-center gap-3 px-4 py-3 {{ request()->is('configuration/countries*') ? 'bg-blue-50 text-blue-700 border-blue-100/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} rounded-2xl font-bold transition-all border border-transparent">
                    <i class="fas fa-globe w-5 h-5 flex items-center justify-center"></i>
                    <span>الدول</span>
                </a>

                <a href="{{ route('configuration.governorates.index') }}"
                    class="flex items-center gap-3 px-4 py-3 {{ request()->is('configuration/governorates*') ? 'bg-blue-50 text-blue-700 border-blue-100/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} rounded-2xl font-bold transition-all border border-transparent">
                    <i class="fas fa-map w-5 h-5 flex items-center justify-center"></i>
                    <span>المحافظات</span>
                </a>

                <a href="{{ route('configuration.directorates.index') }}"
                    class="flex items-center gap-3 px-4 py-3 {{ request()->is('configuration/directorates*') ? 'bg-blue-50 text-blue-700 border-blue-100/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} rounded-2xl font-bold transition-all border border-transparent">
                    <i class="fas fa-map-location w-5 h-5 flex items-center justify-center"></i>
                    <span>المديريات</span>
                </a>

                <a href="{{ route('configuration.sub-areas.index') }}"
                    class="flex items-center gap-3 px-4 py-3 {{ request()->is('configuration/sub-areas*') ? 'bg-blue-50 text-blue-700 border-blue-100/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} rounded-2xl font-bold transition-all border border-transparent">
                    <i class="fas fa-map-pin w-5 h-5 flex items-center justify-center"></i>
                    <span>العزل والناطق</span>
                </a>

                <a href="{{ route('configuration.villages.index') }}"
                    class="flex items-center gap-3 px-4 py-3 {{ request()->is('configuration/villages*') ? 'bg-blue-50 text-blue-700 border-blue-100/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} rounded-2xl font-bold transition-all border border-transparent">
                    <i class="fas fa-house-chimney w-5 h-5 flex items-center justify-center"></i>
                    <span>القرى والمحلات</span>
                </a>

                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-6 mb-4 px-4">ترميزات الحالة
                </p>

                <a href="{{ route('configuration.blood-types.index') }}"
                    class="flex items-center gap-3 px-4 py-3 {{ request()->is('configuration/blood-types*') ? 'bg-blue-50 text-blue-700 border-blue-100/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} rounded-2xl font-bold transition-all border border-transparent">
                    <i class="fas fa-tint w-5 h-5 flex items-center justify-center"></i>
                    <span>فصائل الدم</span>
                </a>

                <a href="{{ route('configuration.marital-statuses.index') }}"
                    class="flex items-center gap-3 px-4 py-3 {{ request()->is('configuration/marital-statuses*') ? 'bg-blue-50 text-blue-700 border-blue-100/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} rounded-2xl font-bold transition-all border border-transparent">
                    <i class="fas fa-ring w-5 h-5 flex items-center justify-center"></i>
                    <span>الحالات الاجتماعية</span>
                </a>

                <a href="{{ route('configuration.qualifications.index') }}"
                    class="flex items-center gap-3 px-4 py-3 {{ request()->is('configuration/qualifications*') ? 'bg-blue-50 text-blue-700 border-blue-100/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} rounded-2xl font-bold transition-all border border-transparent">
                    <i class="fas fa-certificate w-5 h-5 flex items-center justify-center"></i>
                    <span>المؤهلات الدراسية</span>
                </a>

                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-6 mb-4 px-4">ترميزات العمل
                </p>

                <a href="{{ route('configuration.ranks.index') }}"
                    class="flex items-center gap-3 px-4 py-3 {{ request()->is('configuration/ranks*') ? 'bg-blue-50 text-blue-700 border-blue-100/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} rounded-2xl font-bold transition-all border border-transparent">
                    <i class="fas fa-medal w-5 h-5 flex items-center justify-center"></i>
                    <span>الرتب العسكرية</span>
                </a>

                <a href="{{ route('configuration.job-statuses.index') }}"
                    class="flex items-center gap-3 px-4 py-3 {{ request()->is('configuration/job-statuses*') ? 'bg-blue-50 text-blue-700 border-blue-100/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} rounded-2xl font-bold transition-all border border-transparent">
                    <i class="fas fa-briefcase w-5 h-5 flex items-center justify-center"></i>
                    <span>الحالات الوظيفية</span>
                </a>

                <a href="{{ route('configuration.financial-statuses.index') }}"
                    class="flex items-center gap-3 px-4 py-3 {{ request()->is('configuration/financial-statuses*') ? 'bg-blue-50 text-blue-700 border-blue-100/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} rounded-2xl font-bold transition-all border border-transparent">
                    <i class="fas fa-money-bill-wave w-5 h-5 flex items-center justify-center"></i>
                    <span>الحالات المالية</span>
                </a>

                <a href="{{ route('configuration.number-classifications.index') }}"
                    class="flex items-center gap-3 px-4 py-3 {{ request()->is('configuration/number-classifications*') ? 'bg-blue-50 text-blue-700 border-blue-100/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} rounded-2xl font-bold transition-all border border-transparent">
                    <i class="fas fa-list-ol w-5 h-5 flex items-center justify-center"></i>
                    <span>تصنيفات الأرقام</span>
                </a>

                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-6 mb-4 px-4">ترميزات عسكرية
                </p>

                <a href="{{ route('configuration.military-districts.index') }}"
                    class="flex items-center gap-3 px-4 py-3 {{ request()->is('configuration/military-districts*') ? 'bg-blue-50 text-blue-700 border-blue-100/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} rounded-2xl font-bold transition-all border border-transparent">
                    <i class="fas fa-shield w-5 h-5 flex items-center justify-center"></i>
                    <span>المناطق العسكرية</span>
                </a>

                <a href="{{ route('configuration.divisions.index') }}"
                    class="flex items-center gap-3 px-4 py-3 {{ request()->is('configuration/divisions*') ? 'bg-blue-50 text-blue-700 border-blue-100/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} rounded-2xl font-bold transition-all border border-transparent">
                    <i class="fas fa-users-rectangle w-5 h-5 flex items-center justify-center"></i>
                    <span>الشعب والوحدات</span>
                </a>

                <a href="{{ route('configuration.axes.index') }}"
                    class="flex items-center gap-3 px-4 py-3 {{ request()->is('configuration/axes*') ? 'bg-blue-50 text-blue-700 border-blue-100/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} rounded-2xl font-bold transition-all border border-transparent">
                    <i class="fas fa-compass w-5 h-5 flex items-center justify-center"></i>
                    <span>المحاور الجبهوية</span>
                </a>

                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-6 mb-4 px-4">ترميزات أخرى
                </p>

                <a href="{{ route('configuration.course-types.index') }}"
                    class="flex items-center gap-3 px-4 py-3 {{ request()->is('configuration/course-types*') ? 'bg-blue-50 text-blue-700 border-blue-100/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} rounded-2xl font-bold transition-all border border-transparent">
                    <i class="fas fa-book w-5 h-5 flex items-center justify-center"></i>
                    <span>أنواع الدورات</span>
                </a>

                <a href="{{ route('configuration.filling-agencies.index') }}"
                    class="flex items-center gap-3 px-4 py-3 {{ request()->is('configuration/filling-agencies*') ? 'bg-blue-50 text-blue-700 border-blue-100/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} rounded-2xl font-bold transition-all border border-transparent">
                    <i class="fas fa-building w-5 h-5 flex items-center justify-center"></i>
                    <span>جهات التعبئة</span>
                </a>

                <a href="{{ route('configuration.electronic-inputs.index') }}"
                    class="flex items-center gap-3 px-4 py-3 {{ request()->is('configuration/electronic-inputs*') ? 'bg-blue-50 text-blue-700 border-blue-100/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} rounded-2xl font-bold transition-all border border-transparent">
                    <i class="fas fa-computer w-5 h-5 flex items-center justify-center"></i>
                    <span>المدخلات الإلكترونية</span>
                </a>

                <a href="{{ route('configuration.arresting-authorities.index') }}"
                    class="flex items-center gap-3 px-4 py-3 {{ request()->is('configuration/arresting-authorities*') ? 'bg-blue-50 text-blue-700 border-blue-100/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} rounded-2xl font-bold transition-all border border-transparent">
                    <i class="fas fa-building-columns w-5 h-5 flex items-center justify-center"></i>
                    <span>جهات الضبط</span>
                </a>

                <a href="{{ route('configuration.case-types.index') }}"
                    class="flex items-center gap-3 px-4 py-3 {{ request()->is('configuration/case-types*') ? 'bg-blue-50 text-blue-700 border-blue-100/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} rounded-2xl font-bold transition-all border border-transparent">
                    <i class="fas fa-gavel w-5 h-5 flex items-center justify-center"></i>
                    <span>أنواع القضايا</span>
                </a>

                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-6 mb-4 px-4">الإدارة
                    والصلاحيات</p>

                <a href="{{ route('system.users.index') }}"
                    class="flex items-center gap-3 px-4 py-3 {{ request()->is('system/users*') ? 'bg-blue-50 text-blue-700 border-blue-100/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} rounded-2xl font-bold transition-all border border-transparent">
                    <i class="fas fa-user-shield w-5 h-5 flex items-center justify-center"></i>
                    <span>إدارة المستخدمين</span>
                </a>

                <a href="{{ route('system.roles.index') }}"
                    class="flex items-center gap-3 px-4 py-3 {{ request()->is('system/roles*') ? 'bg-blue-50 text-blue-700 border-blue-100/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} rounded-2xl font-bold transition-all border border-transparent">
                    <i class="fas fa-shield-halved w-5 h-5 flex items-center justify-center"></i>
                    <span>الأدوار والصلاحيات</span>
                </a>

                <a href="{{ route('system.entities.index') }}"
                    class="flex items-center gap-3 px-4 py-3 {{ request()->is('system/entities*') ? 'bg-blue-50 text-blue-700 border-blue-100/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} rounded-2xl font-bold transition-all border border-transparent">
                    <i class="fas fa-building-shield w-5 h-5 flex items-center justify-center"></i>
                    <span>إدارة الجهات</span>
                </a>

                <a href="{{ route('system.sections.index') }}"
                    class="flex items-center gap-3 px-4 py-3 {{ request()->is('system/sections*') ? 'bg-blue-50 text-blue-700 border-blue-100/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} rounded-2xl font-bold transition-all border border-transparent">
                    <i class="fas fa-sitemap w-5 h-5 flex items-center justify-center"></i>
                    <span>إدارة الأقسام</span>
                </a>
            </nav>
        </aside>

        {{-- منطقة المحتوى --}}
        <div class="flex-1 flex flex-col h-screen overflow-hidden">

            {{-- الشريط العلوي (Navbar) --}}
            <header
                class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-8 sticky top-0 z-40">
                <div class="flex items-center gap-4">
                    <button class="p-2 hover:bg-slate-100 rounded-lg lg:hidden" onclick="toggleSidebar()">
                        <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M4 6h16M4 12h16M4 18h16" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"></path>
                        </svg>
                    </button>
                    <h1 class="text-xl font-bold text-slate-800 header-font">@yield('page_title', 'نظرة عامة')</h1>
                </div>

                <div class="flex items-center gap-4">
                    {{-- الإشعارات --}}
                    <button class="p-2 text-slate-400 hover:text-blue-600 relative transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                        <span
                            class="absolute top-2 left-2 w-2 h-2 bg-rose-500 rounded-full border-2 border-white"></span>
                    </button>

                    {{-- Dropdown المستخدم --}}
                    @php
                        $user = auth()->user();
                        $userName = $user->name ?? 'مستخدم';
                        $userRole = $user->roles->first()->name ?? 'مستخدم النظام';
                        $userInitial = mb_substr($userName, 0, 1, 'UTF-8');
                    @endphp

                    <div class="relative">
                        <button type="button"
                            class="flex items-center gap-3 pr-4 border-r border-slate-200 hover:opacity-80 transition-opacity"
                            id="userMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="text-left hidden sm:block">
                                <p class="text-sm font-bold text-slate-800">{{ $userName }}</p>
                                <p class="text-[10px] text-slate-500 text-left">{{ $userRole }}</p>
                            </div>
                            <div
                                class="w-10 h-10 rounded-full bg-blue-600 border-2 border-blue-200 flex items-center justify-center font-extrabold text-white uppercase shadow-md shadow-blue-100">
                                {{ $userInitial }}
                            </div>
                        </button>

                        <ul class="dropdown-menu dropdown-menu-custom p-2" aria-labelledby="userMenuButton">
                            <li>
                                <a href="{{ route('system.profile.index') }}"
                                    class="dropdown-item rounded-xl px-3 py-2 flex items-center gap-3 hover:bg-slate-50 transition-colors">
                                    <i class="fas fa-user-circle w-4 h-4 text-slate-500"></i>
                                    <span class="text-slate-700">الملف الشخصي</span>
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider my-1">
                            </li>
                            <li>
                                <button type="button"
                                    class="dropdown-item rounded-xl px-3 py-2 flex items-center gap-3 w-full text-right hover:bg-rose-50 transition-colors"
                                    data-bs-toggle="modal" data-bs-target="#logoutConfirmationModal">
                                    <i class="fas fa-sign-out-alt w-4 h-4 text-rose-500"></i>
                                    <span class="text-rose-600">تسجيل الخروج</span>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            {{-- المحتوى الرئيسي --}}
            <main class="flex-1 overflow-y-auto p-4 md:p-8">
                <div class="max-w-7xl mx-auto">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    {{-- مودال تأكيد تسجيل الخروج --}}
    <div class="modal fade" id="logoutConfirmationModal" tabindex="-1" aria-labelledby="logoutModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-xl border-0 rounded-2xl">
                <div class="modal-body p-6 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-rose-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-sign-out-alt text-rose-500 text-2xl"></i>
                    </div>
                    <h5 class="text-xl font-bold text-slate-800 mb-2 header-font">تسجيل الخروج</h5>
                    <p class="text-slate-500">هل أنت متأكد من رغبتك في تسجيل الخروج؟</p>
                </div>
                <div class="modal-footer border-0 p-4 pt-0 flex gap-3 justify-center">
                    <button type="button"
                        class="px-6 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold transition-colors"
                        data-bs-dismiss="modal">لا، إلغاء</button>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                            class="px-6 py-2 bg-rose-500 hover:bg-rose-600 text-white rounded-xl font-bold transition-colors shadow-md shadow-rose-200">نعم،
                            تسجيل الخروج</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('-translate-x-full');
        }

        // تفعيل Bootstrap dropdown بشكل يدوي (في حال عدم عمل data-bs-toggle بشكل صحيح)
        document.addEventListener('DOMContentLoaded', function () {
            var dropdownElementList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'));
            dropdownElementList.map(function (dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl);
            });
        });
    </script>

    {{-- Bootstrap JS Bundle via CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>