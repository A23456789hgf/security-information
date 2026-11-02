<!DOCTYPE html>
<html lang="ar" dir="rtl" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة التحكم') — {{ config('app.name', 'الأمن الاستخباراتي') }}</title>

    {{-- Zero-Flash Dark Mode Initialization & Global Toggle Function --}}
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();

        function toggleTheme() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            window.dispatchEvent(new CustomEvent('theme-changed', { detail: { isDark } }));
        }
        window.toggleTheme = toggleTheme;
        window.toggleDarkMode = toggleTheme;
    </script>

    {{-- Google Fonts: Cairo & Tajawal (Non-blocking for fast LAN load) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">

    {{-- Select2 Searchable Dropdowns (Loaded Locally for Fast LAN Performance) --}}
    <link rel="stylesheet" href="{{ asset('vendor/select2/select2.min.css') }}">

    {{-- FontAwesome Icons (Local) --}}
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/all.min.css') }}">

    {{-- App Compiled Styles and Scripts --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ═══════════════════════════════════════════════════════════
           🔍 MODERN SELECT2 SEARCHABLE DROPDOWNS (RTL & DARK MODE)
           ═══════════════════════════════════════════════════════════ */
        .select2-container {
            width: 100% !important;
            text-align: right;
        }
        .select2-container--default .select2-selection--single {
            background-color: #f8fafc !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 0.75rem !important; /* rounded-xl */
            height: 42px !important;
            display: flex !important;
            align-items: center !important;
            padding: 0 0.5rem !important;
            transition: all 0.2s ease-in-out;
        }
        html.dark .select2-container--default .select2-selection--single {
            border-color: #334155 !important;
            background-color: #0f172a !important;
        }
        .select2-container--default .select2-selection--single:focus,
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #059669 !important; /* brand emerald */
            box-shadow: 0 0 0 2px rgba(5, 150, 105, 0.2) !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #0f172a !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
            padding-right: 0.5rem !important;
            padding-left: 1.75rem !important;
            line-height: normal !important;
        }
        html.dark .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #f8fafc !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100% !important;
            top: 0 !important;
            left: 8px !important;
            right: auto !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #64748b transparent transparent transparent !important;
        }
        html.dark .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #94a3b8 transparent transparent transparent !important;
        }

        /* Select2 Dropdown Popup Window */
        .select2-dropdown {
            background-color: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 0.75rem !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
            overflow: hidden !important;
            z-index: 9999 !important;
        }
        html.dark .select2-dropdown {
            background-color: #0f172a !important;
            border-color: #334155 !important;
            color: #f8fafc !important;
        }
        .select2-search--dropdown {
            padding: 8px !important;
        }
        .select2-search--dropdown .select2-search__field {
            background-color: #f8fafc !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 0.5rem !important;
            padding: 8px 12px !important;
            color: #0f172a !important;
            font-size: 0.875rem !important;
            text-align: right !important;
            outline: none !important;
        }
        html.dark .select2-search--dropdown .select2-search__field {
            background-color: #020617 !important;
            border-color: #334155 !important;
            color: #f8fafc !important;
        }
        .select2-results__option {
            padding: 8px 12px !important;
            font-size: 0.875rem !important;
            color: #334155 !important;
            text-align: right !important;
        }
        html.dark .select2-results__option {
            color: #cbd5e1 !important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #059669 !important;
            color: #ffffff !important;
        }
        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #e2e8f0 !important;
            color: #0f172a !important;
            font-weight: bold !important;
        }
        html.dark .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #1e293b !important;
            color: #ffffff !important;
        }
    </style>

    <style>
        @media print {
            aside#sidebar,
            #sidebarBackdrop,
            header,
            .print\:hidden,
            .no-print,
            button,
            .btn-action,
            .pagination-wrapper,
            nav[aria-label="Pagination Navigation"] {
                display: none !important;
            }

            #mainWrapper {
                padding-right: 0 !important;
                margin: 0 !important;
                width: 100% !important;
            }

            body {
                background: #ffffff !important;
                color: #000000 !important;
                font-size: 11pt !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .bg-white, .dark\:bg-slate-900, .dark\:bg-slate-950 {
                background-color: #ffffff !important;
                color: #000000 !important;
                box-shadow: none !important;
                border-color: #e2e8f0 !important;
            }

            table {
                width: 100% !important;
                border-collapse: collapse !important;
            }

            th, td {
                border: 1px solid #cbd5e1 !important;
                padding: 6px 10px !important;
                color: #0f172a !important;
            }
        }
        [x-cloak] {
            display: none !important;
        }
    </style>

    @stack('styles')
</head>

<body class="min-h-screen bg-slate-50 dark:bg-slate-950 font-sans text-slate-900 dark:text-slate-50 antialiased transition-colors duration-300 selection:bg-brand-500/20 selection:text-brand-600">

    <div class="min-h-screen flex relative overflow-x-hidden">

        {{-- ═══════════════════════════════════════════════════════════
             📱 MOBILE BACKDROP
        ═══════════════════════════════════════════════════════════ --}}
        <div 
            id="sidebarBackdrop" 
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden hidden transition-opacity duration-300 opacity-0"
            aria-hidden="true"
        ></div>

        {{-- ═══════════════════════════════════════════════════════════
             🎯 SIDEBAR (Matching tem Layout.tsx)
        ═══════════════════════════════════════════════════════════ --}}
        <aside 
            id="sidebar" 
            class="fixed top-0 right-0 h-full bg-white dark:bg-slate-900 border-l border-slate-200 dark:border-slate-800 z-50 flex flex-col transition-all duration-300 ease-in-out print:hidden w-72 max-w-[85vw] translate-x-full lg:translate-x-0 shadow-2xl lg:shadow-none"
            aria-label="القائمة الجانبية"
        >
            {{-- Sidebar Header --}}
            <div class="h-16 flex items-center justify-between px-4 lg:px-5 border-b border-slate-200 dark:border-slate-800 shrink-0">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 overflow-hidden group">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 text-white flex items-center justify-center font-display font-bold shadow-lg shadow-brand-500/30 shrink-0 transition-transform group-hover:scale-105">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-display font-bold text-base text-slate-900 dark:text-white whitespace-nowrap leading-tight">
                            {{ config('app.name', 'نظام المعلومات') }}
                        </span>
                        <span class="text-[10px] text-slate-400 dark:text-slate-500 font-medium">بالأمن الوقائي بالتعبئة العامة</span>
                    </div>
                </a>
                <button 
                    id="sidebarCloseBtn"
                    class="lg:hidden p-2 text-slate-500 dark:text-slate-400 hover:text-red-500 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-colors"
                    aria-label="إغلاق القائمة"
                >
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            {{-- Sidebar Navigation --}}
            <div class="flex-1 overflow-y-auto overflow-x-hidden p-3 space-y-1 custom-scrollbar">

                {{-- Section: الرئيسية --}}
                <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest px-3 py-2 mt-1">
                    الرئيسية
                </div>
                
                @php $isDashboard = request()->routeIs('dashboard'); @endphp
                <a href="{{ route('dashboard') }}" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all relative group {{ $isDashboard ? 'bg-gradient-to-l from-brand-50 to-indigo-50/50 dark:from-brand-500/10 dark:to-indigo-500/5 text-brand-600 dark:text-brand-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    @if($isDashboard)
                        <span class="absolute right-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-brand-600 dark:bg-brand-500 rounded-l-full"></span>
                    @endif
                    <div class="shrink-0 transition-colors {{ $isDashboard ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400 dark:text-slate-500 group-hover:text-brand-600 dark:group-hover:text-brand-400' }}">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="7" height="9" x="3" y="3" rx="1"/>
                            <rect width="7" height="5" x="14" y="3" rx="1"/>
                            <rect width="7" height="9" x="14" y="12" rx="1"/>
                            <rect width="7" height="5" x="3" y="16" rx="1"/>
                        </svg>
                    </div>
                    <span class="whitespace-nowrap">لوحة التحكم</span>
                </a>

                {{-- Section: إدارة الأفراد --}}
                <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest px-3 py-2 mt-4">
                    إدارة الأفراد
                </div>

                @php $isPersonal = request()->is('system/personal-data*'); @endphp
                <a href="{{ route('system.personal-data.index') }}" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all relative group {{ $isPersonal ? 'bg-gradient-to-l from-brand-50 to-indigo-50/50 dark:from-brand-500/10 dark:to-indigo-500/5 text-brand-600 dark:text-brand-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    @if($isPersonal) <span class="absolute right-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-brand-600 dark:bg-brand-500 rounded-l-full"></span> @endif
                    <div class="shrink-0 {{ $isPersonal ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400 dark:text-slate-500 group-hover:text-brand-600 dark:group-hover:text-brand-400' }}">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <span class="whitespace-nowrap">البيانات الشخصية</span>
                </a>

                @php $isSecData = request()->is('system/secondary-data*'); @endphp
                <a href="{{ route('system.secondary-data.index') }}" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all relative group {{ $isSecData ? 'bg-gradient-to-l from-brand-50 to-indigo-50/50 dark:from-brand-500/10 dark:to-indigo-500/5 text-brand-600 dark:text-brand-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    @if($isSecData) <span class="absolute right-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-brand-600 dark:bg-brand-500 rounded-l-full"></span> @endif
                    <div class="shrink-0 {{ $isSecData ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400 dark:text-slate-500 group-hover:text-brand-600 dark:group-hover:text-brand-400' }}">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="14" x="3" y="5" rx="2"/><circle cx="8" cy="11" r="2"/><path d="M14 9h2"/><path d="M14 13h2"/></svg>
                    </div>
                    <span class="whitespace-nowrap">البيانات الثانوية</span>
                </a>

                @php $isWives = request()->is('system/wives*'); @endphp
                <a href="{{ route('system.wives.index') }}" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all relative group {{ $isWives ? 'bg-gradient-to-l from-brand-50 to-indigo-50/50 dark:from-brand-500/10 dark:to-indigo-500/5 text-brand-600 dark:text-brand-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    @if($isWives) <span class="absolute right-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-brand-600 dark:bg-brand-500 rounded-l-full"></span> @endif
                    <div class="shrink-0 {{ $isWives ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400 dark:text-slate-500 group-hover:text-brand-600 dark:group-hover:text-brand-400' }}">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                    </div>
                    <span class="whitespace-nowrap">بيانات الزوجات</span>
                </a>

                @php $isRelatives = request()->is('system/relatives*'); @endphp
                <a href="{{ route('system.relatives.index') }}" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all relative group {{ $isRelatives ? 'bg-gradient-to-l from-brand-50 to-indigo-50/50 dark:from-brand-500/10 dark:to-indigo-500/5 text-brand-600 dark:text-brand-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    @if($isRelatives) <span class="absolute right-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-brand-600 dark:bg-brand-500 rounded-l-full"></span> @endif
                    <div class="shrink-0 {{ $isRelatives ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400 dark:text-slate-500 group-hover:text-brand-600 dark:group-hover:text-brand-400' }}">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <span class="whitespace-nowrap">الأهل والأقارب</span>
                </a>

                @php $isSecFiles = request()->is('system/security-files*'); @endphp
                <a href="{{ route('system.security-files.index') }}" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all relative group {{ $isSecFiles ? 'bg-gradient-to-l from-brand-50 to-indigo-50/50 dark:from-brand-500/10 dark:to-indigo-500/5 text-brand-600 dark:text-brand-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    @if($isSecFiles) <span class="absolute right-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-brand-600 dark:bg-brand-500 rounded-l-full"></span> @endif
                    <div class="shrink-0 {{ $isSecFiles ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400 dark:text-slate-500 group-hover:text-brand-600 dark:group-hover:text-brand-400' }}">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
                    </div>
                    <span class="whitespace-nowrap">الملف الأمني</span>
                </a>

                @php $isCourses = request()->is('system/courses*'); @endphp
                <a href="{{ route('system.courses.index') }}" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all relative group {{ $isCourses ? 'bg-gradient-to-l from-brand-50 to-indigo-50/50 dark:from-brand-500/10 dark:to-indigo-500/5 text-brand-600 dark:text-brand-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    @if($isCourses) <span class="absolute right-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-brand-600 dark:bg-brand-500 rounded-l-full"></span> @endif
                    <div class="shrink-0 {{ $isCourses ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400 dark:text-slate-500 group-hover:text-brand-600 dark:group-hover:text-brand-400' }}">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                    </div>
                    <span class="whitespace-nowrap">الدورات التدريبية</span>
                </a>

                {{-- Section: السجلات الأمنية --}}
                <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest px-3 py-2 mt-4">
                    السجلات الأمنية
                </div>

                @php $isArrests = request()->is('system/arrests*'); @endphp
                <a href="{{ route('system.arrests.index') }}" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all relative group {{ $isArrests ? 'bg-gradient-to-l from-brand-50 to-indigo-50/50 dark:from-brand-500/10 dark:to-indigo-500/5 text-brand-600 dark:text-brand-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    @if($isArrests) <span class="absolute right-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-brand-600 dark:bg-brand-500 rounded-l-full"></span> @endif
                    <div class="shrink-0 {{ $isArrests ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400 dark:text-slate-500 group-hover:text-brand-600 dark:group-hover:text-brand-400' }}">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </div>
                    <span class="whitespace-nowrap">سجلات الضبط</span>
                </a>

                @php $isSuspicious = request()->is('system/suspicious*'); @endphp
                <a href="{{ route('system.suspicious.index') }}" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all relative group {{ $isSuspicious ? 'bg-gradient-to-l from-brand-50 to-indigo-50/50 dark:from-brand-500/10 dark:to-indigo-500/5 text-brand-600 dark:text-brand-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    @if($isSuspicious) <span class="absolute right-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-brand-600 dark:bg-brand-500 rounded-l-full"></span> @endif
                    <div class="shrink-0 {{ $isSuspicious ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400 dark:text-slate-500 group-hover:text-brand-600 dark:group-hover:text-brand-400' }}">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a5 5 0 0 0-5 5v2a5 5 0 0 0 10 0V7a5 5 0 0 0-5-5Z"/><path d="M4 19v-2a8 8 0 0 1 16 0v2"/><circle cx="12" cy="11" r="1"/></svg>
                    </div>
                    <span class="whitespace-nowrap">المشتبه بهم</span>
                </a>

                @php $isReports = request()->is('system/security-reports*'); @endphp
                <a href="{{ route('system.security-reports.index') }}" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all relative group {{ $isReports ? 'bg-gradient-to-l from-brand-50 to-indigo-50/50 dark:from-brand-500/10 dark:to-indigo-500/5 text-brand-600 dark:text-brand-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    @if($isReports) <span class="absolute right-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-brand-600 dark:bg-brand-500 rounded-l-full"></span> @endif
                    <div class="shrink-0 {{ $isReports ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400 dark:text-slate-500 group-hover:text-brand-600 dark:group-hover:text-brand-400' }}">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
                    </div>
                    <span class="whitespace-nowrap">البلاغات الأمنية</span>
                </a>

                @php $isFieldResp = request()->is('system/field-responses*'); @endphp
                <a href="{{ route('system.field-responses.index') }}" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all relative group {{ $isFieldResp ? 'bg-gradient-to-l from-brand-50 to-indigo-50/50 dark:from-brand-500/10 dark:to-indigo-500/5 text-brand-600 dark:text-brand-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    @if($isFieldResp) <span class="absolute right-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-brand-600 dark:bg-brand-500 rounded-l-full"></span> @endif
                    <div class="shrink-0 {{ $isFieldResp ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400 dark:text-slate-500 group-hover:text-brand-600 dark:group-hover:text-brand-400' }}">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="2"/><path d="M16.24 7.76a6 6 0 0 1 0 8.49"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14"/><path d="M7.76 16.24a6 6 0 0 1 0-8.49"/><path d="M4.93 19.07a10 10 0 0 1 0-14.14"/></svg>
                    </div>
                    <span class="whitespace-nowrap">الرد الميداني</span>
                </a>

                {{-- Section: أدوات البيانات --}}
                <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest px-3 py-2 mt-4">
                    أدوات البيانات
                </div>

                @php $isPages = request()->is('system/pages*'); @endphp
                <a href="{{ route('system.pages.index') }}" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all relative group {{ $isPages ? 'bg-gradient-to-l from-brand-50 to-indigo-50/50 dark:from-brand-500/10 dark:to-indigo-500/5 text-brand-600 dark:text-brand-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    @if($isPages) <span class="absolute right-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-brand-600 dark:bg-brand-500 rounded-l-full"></span> @endif
                    <div class="shrink-0 {{ $isPages ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400 dark:text-slate-500 group-hover:text-brand-600 dark:group-hover:text-brand-400' }}">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
                    </div>
                    <span class="whitespace-nowrap">الصفحات الديناميكية</span>
                </a>

                @php $isImport = request()->is('data/import*'); @endphp
                <a href="{{ route('data.import.index') }}" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all relative group {{ $isImport ? 'bg-gradient-to-l from-brand-50 to-indigo-50/50 dark:from-brand-500/10 dark:to-indigo-500/5 text-brand-600 dark:text-brand-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    @if($isImport) <span class="absolute right-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-brand-600 dark:bg-brand-500 rounded-l-full"></span> @endif
                    <div class="shrink-0 {{ $isImport ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400 dark:text-slate-500 group-hover:text-brand-600 dark:group-hover:text-brand-400' }}">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    </div>
                    <span class="whitespace-nowrap">استيراد البيانات</span>
                </a>

                @php $isDiff = request()->is('data/differentiation*'); @endphp
                <a href="{{ route('data.differentiation.index') }}" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all relative group {{ $isDiff ? 'bg-gradient-to-l from-brand-50 to-indigo-50/50 dark:from-brand-500/10 dark:to-indigo-500/5 text-brand-600 dark:text-brand-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    @if($isDiff) <span class="absolute right-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-brand-600 dark:bg-brand-500 rounded-l-full"></span> @endif
                    <div class="shrink-0 {{ $isDiff ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400 dark:text-slate-500 group-hover:text-brand-600 dark:group-hover:text-brand-400' }}">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 3 21 3 21 8"/><line x1="4" y1="20" x2="21" y2="3"/><polyline points="21 16 21 21 16 21"/><line x1="15" y1="15" x2="21" y2="21"/><line x1="4" y1="4" x2="9" y2="9"/></svg>
                    </div>
                    <span class="whitespace-nowrap">مقارنة وتمايز البيانات</span>
                </a>

                {{-- Accordion Section: الترميزات والإعدادات --}}
                <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest px-3 py-2 mt-4">
                    إدارة المنظومة والترميزات
                </div>

                <details class="group/acc [&_summary::-webkit-details-marker]:hidden" {{ request()->is('configuration*') ? 'open' : '' }}>
                    <summary class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white cursor-pointer transition-all">
                        <div class="flex items-center gap-3">
                            <div class="shrink-0 text-slate-400 dark:text-slate-500 group-hover/acc:text-brand-600">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/></svg>
                            </div>
                            <span>الترميزات الجغرافية</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform group-open/acc:-rotate-180 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                    </summary>
                    <div class="pr-7 pl-2 py-1 space-y-1 border-r border-slate-200 dark:border-slate-800 mr-4 mt-1">
                        <a href="{{ route('configuration.countries.index') }}" class="block px-3 py-1.5 rounded-lg text-xs font-medium {{ request()->is('configuration/countries*') ? 'text-brand-600 dark:text-brand-400 font-bold bg-brand-50/60 dark:bg-brand-500/10' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">الدول</a>
                        <a href="{{ route('configuration.governorates.index') }}" class="block px-3 py-1.5 rounded-lg text-xs font-medium {{ request()->is('configuration/governorates*') ? 'text-brand-600 dark:text-brand-400 font-bold bg-brand-50/60 dark:bg-brand-500/10' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">المحافظات</a>
                        <a href="{{ route('configuration.directorates.index') }}" class="block px-3 py-1.5 rounded-lg text-xs font-medium {{ request()->is('configuration/directorates*') ? 'text-brand-600 dark:text-brand-400 font-bold bg-brand-50/60 dark:bg-brand-500/10' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">المديريات</a>
                        <a href="{{ route('configuration.sub-areas.index') }}" class="block px-3 py-1.5 rounded-lg text-xs font-medium {{ request()->is('configuration/sub-areas*') ? 'text-brand-600 dark:text-brand-400 font-bold bg-brand-50/60 dark:bg-brand-500/10' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">العزل والمناطق</a>
                        <a href="{{ route('configuration.villages.index') }}" class="block px-3 py-1.5 rounded-lg text-xs font-medium {{ request()->is('configuration/villages*') ? 'text-brand-600 dark:text-brand-400 font-bold bg-brand-50/60 dark:bg-brand-500/10' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">القرى والمحلات</a>
                    </div>
                </details>

                <details class="group/acc [&_summary::-webkit-details-marker]:hidden" {{ request()->is('configuration/blood-types*', 'configuration/health-statuses*', 'configuration/marital-statuses*', 'configuration/qualifications*', 'configuration/kinships*', 'configuration/ideological-affiliations*', 'configuration/political-affiliations*') ? 'open' : '' }}>
                    <summary class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white cursor-pointer transition-all">
                        <div class="flex items-center gap-3">
                            <div class="shrink-0 text-slate-400 dark:text-slate-500 group-hover/acc:text-brand-600">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/></svg>
                            </div>
                            <span>ترميزات الحالة</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform group-open/acc:-rotate-180 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                    </summary>
                    <div class="pr-7 pl-2 py-1 space-y-1 border-r border-slate-200 dark:border-slate-800 mr-4 mt-1">
                        <a href="{{ route('configuration.blood-types.index') }}" class="block px-3 py-1.5 rounded-lg text-xs font-medium text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white">فصائل الدم</a>
                        <a href="{{ route('configuration.health-statuses.index') }}" class="block px-3 py-1.5 rounded-lg text-xs font-medium text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white">الحالات الصحية</a>
                        <a href="{{ route('configuration.marital-statuses.index') }}" class="block px-3 py-1.5 rounded-lg text-xs font-medium text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white">الحالات الاجتماعية</a>
                        <a href="{{ route('configuration.qualifications.index') }}" class="block px-3 py-1.5 rounded-lg text-xs font-medium text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white">المؤهلات الدراسية</a>
                        <a href="{{ route('configuration.kinships.index') }}" class="block px-3 py-1.5 rounded-lg text-xs font-medium text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white">صلات القرابة</a>
                        <a href="{{ route('configuration.ideological-affiliations.index') }}" class="block px-3 py-1.5 rounded-lg text-xs font-medium text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white">الانتماءات العقائدية</a>
                        <a href="{{ route('configuration.political-affiliations.index') }}" class="block px-3 py-1.5 rounded-lg text-xs font-medium text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white">الانتماءات السياسية</a>
                    </div>
                </details>

                <details class="group/acc [&_summary::-webkit-details-marker]:hidden" {{ request()->is('configuration/ranks*', 'configuration/job-statuses*', 'configuration/financial-statuses*', 'configuration/number-classifications*', 'configuration/military-districts*', 'configuration/divisions*', 'configuration/axes*', 'configuration/course-types*', 'configuration/filling-agencies*', 'configuration/electronic-inputs*', 'configuration/arresting-authorities*', 'configuration/case-types*') ? 'open' : '' }}>
                    <summary class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white cursor-pointer transition-all">
                        <div class="flex items-center gap-3">
                            <div class="shrink-0 text-slate-400 dark:text-slate-500 group-hover/acc:text-brand-600">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                            </div>
                            <span>الترميزات العسكرية والمهنية</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform group-open/acc:-rotate-180 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                    </summary>
                    <div class="pr-7 pl-2 py-1 space-y-1 border-r border-slate-200 dark:border-slate-800 mr-4 mt-1">
                        <a href="{{ route('configuration.ranks.index') }}" class="block px-3 py-1.5 rounded-lg text-xs font-medium text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white">الرتب العسكرية</a>
                        <a href="{{ route('configuration.job-statuses.index') }}" class="block px-3 py-1.5 rounded-lg text-xs font-medium text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white">الحالات الوظيفية</a>
                        <a href="{{ route('configuration.military-districts.index') }}" class="block px-3 py-1.5 rounded-lg text-xs font-medium text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white">المناطق العسكرية</a>
                        <a href="{{ route('configuration.divisions.index') }}" class="block px-3 py-1.5 rounded-lg text-xs font-medium text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white">الشعب والوحدات</a>
                        <a href="{{ route('configuration.axes.index') }}" class="block px-3 py-1.5 rounded-lg text-xs font-medium text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white">المحاور الجبهوية</a>
                        <a href="{{ route('configuration.arresting-authorities.index') }}" class="block px-3 py-1.5 rounded-lg text-xs font-medium text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white">جهات الضبط</a>
                        <a href="{{ route('configuration.case-types.index') }}" class="block px-3 py-1.5 rounded-lg text-xs font-medium text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white">أنواع القضايا</a>
                    </div>
                </details>

                {{-- Section: الإدارة والصلاحيات --}}
                <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest px-3 py-2 mt-4">
                    الإدارة والصلاحيات
                </div>

                @php $isUsers = request()->is('system/users*'); @endphp
                <a href="{{ route('system.users.index') }}" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all relative group {{ $isUsers ? 'bg-gradient-to-l from-brand-50 to-indigo-50/50 dark:from-brand-500/10 dark:to-indigo-500/5 text-brand-600 dark:text-brand-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    @if($isUsers) <span class="absolute right-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-brand-600 dark:bg-brand-500 rounded-l-full"></span> @endif
                    <div class="shrink-0 {{ $isUsers ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400 dark:text-slate-500 group-hover:text-brand-600 dark:group-hover:text-brand-400' }}">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <span class="whitespace-nowrap">إدارة المستخدمين</span>
                </a>

                @php $isRoles = request()->is('system/roles*'); @endphp
                <a href="{{ route('system.roles.index') }}" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all relative group {{ $isRoles ? 'bg-gradient-to-l from-brand-50 to-indigo-50/50 dark:from-brand-500/10 dark:to-indigo-500/5 text-brand-600 dark:text-brand-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    @if($isRoles) <span class="absolute right-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-brand-600 dark:bg-brand-500 rounded-l-full"></span> @endif
                    <div class="shrink-0 {{ $isRoles ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400 dark:text-slate-500 group-hover:text-brand-600 dark:group-hover:text-brand-400' }}">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21 2-2 2m-6 6 2-2m-4-4 2-2M3 21l9-9"/><circle cx="17.5" cy="6.5" r="3.5"/></svg>
                    </div>
                    <span class="whitespace-nowrap">الأدوار والصلاحيات</span>
                </a>

                @php $isEntities = request()->is('system/entities*', 'system/sections*'); @endphp
                <a href="{{ route('system.entities.index') }}" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all relative group {{ $isEntities ? 'bg-gradient-to-l from-brand-50 to-indigo-50/50 dark:from-brand-500/10 dark:to-indigo-500/5 text-brand-600 dark:text-brand-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    @if($isEntities) <span class="absolute right-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-brand-600 dark:bg-brand-500 rounded-l-full"></span> @endif
                    <div class="shrink-0 {{ $isEntities ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400 dark:text-slate-500 group-hover:text-brand-600 dark:group-hover:text-brand-400' }}">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01"/><path d="M16 6h.01"/><path d="M12 6h.01"/><path d="M12 10h.01"/><path d="M12 14h.01"/><path d="M16 10h.01"/><path d="M16 14h.01"/><path d="M8 10h.01"/><path d="M8 14h.01"/></svg>
                    </div>
                    <span class="whitespace-nowrap">الجهات والأقسام</span>
                </a>
                
                <div class="h-6"></div>
            </div>

            {{-- Sidebar Footer --}}
            <div class="p-4 border-t border-slate-200 dark:border-slate-800 shrink-0">
                <button 
                    type="button" 
                    data-bs-toggle="modal" 
                    data-bs-target="#logoutModal"
                    class="flex items-center gap-3 w-full p-2.5 text-slate-600 dark:text-slate-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-xl transition-all font-semibold text-sm cursor-pointer"
                >
                    <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                    <span class="whitespace-nowrap">تسجيل الخروج</span>
                </button>
            </div>
        </aside>

        {{-- ═══════════════════════════════════════════════════════════
             📄 MAIN CONTENT WRAPPER
        ═══════════════════════════════════════════════════════════ --}}
        <div id="mainWrapper" class="flex-1 flex flex-col min-w-0 transition-all duration-300 ease-in-out print:pr-0 lg:pr-72">

            {{-- Sticky Header --}}
            <header class="h-16 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 flex items-center justify-between px-4 lg:px-8 sticky top-0 z-30 transition-colors duration-300 print:hidden">
                <div class="flex items-center gap-4">
                    {{-- Sidebar Toggle Button --}}
                    <button 
                        id="sidebarToggleBtn"
                        class="p-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-brand-50 dark:hover:bg-brand-500/10 hover:border-brand-100 dark:hover:border-brand-500/30 transition-all shadow-sm cursor-pointer"
                        aria-label="تبديل القائمة الجانبية"
                    >
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/>
                        </svg>
                    </button>
                    <h1 class="font-display font-bold text-lg text-slate-900 dark:text-white hidden sm:block">
                        @hasSection('page_title')
                            @yield('page_title')
                        @else
                            @yield('title', 'لوحة التحكم والتحليل')
                        @endif
                    </h1>
                </div>

                <div class="flex items-center gap-3">
                    {{-- Print Page Button --}}
                    <button 
                        type="button"
                        onclick="window.print()"
                        class="p-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-brand-50 dark:hover:bg-brand-500/10 hover:border-brand-200 dark:hover:border-brand-500/30 text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 transition-colors shadow-sm cursor-pointer print:hidden"
                        aria-label="طباعة الصفحة الحالية"
                        title="طباعة الصفحة الحالية (Ctrl+P)"
                    >
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 6 2 18 2 18 9"></polyline>
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                            <rect x="6" y="14" width="12" height="8"></rect>
                        </svg>
                    </button>

                    {{-- Dark Mode Toggle Button --}}
                    <button 
                        id="themeToggleBtn"
                        onclick="toggleTheme()"
                        class="p-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 transition-colors shadow-sm cursor-pointer"
                        aria-label="تبديل المظهر"
                        title="تبديل الوضع الليلي / النهاري"
                    >
                        {{-- Sun Icon (shown in dark mode) --}}
                        <svg class="w-5 h-5 hidden dark:block text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/>
                        </svg>
                        {{-- Moon Icon (shown in light mode) --}}
                        <svg class="w-5 h-5 block dark:hidden text-slate-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/>
                        </svg>
                    </button>

                    {{-- Notifications Button --}}
                    <button 
                        class="p-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 relative transition-colors shadow-sm cursor-pointer"
                        aria-label="الإشعارات"
                    >
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                        </svg>
                        <span class="absolute top-1.5 right-2 w-2.5 h-2.5 rounded-full bg-red-500 border-2 border-white dark:border-slate-800"></span>
                    </button>

                    {{-- User Profile Pill --}}
                    @php
                        $user = auth()->user();
                        $userName = $user->name ?? 'مستخدم';
                        $userRole = $user->roles->first()->name ?? 'محلل أمني';
                        $userEmail = $user->email ?? 'guest@system.app';
                        $userInitial = mb_substr($userName, 0, 1, 'UTF-8');
                    @endphp

                    <div class="relative" id="userMenuWrapper">
                        <button 
                            id="userMenuBtn"
                            type="button"
                            onclick="document.getElementById('userMenuDropdown').classList.toggle('hidden')"
                            class="flex items-center gap-3 p-1.5 pr-1.5 pl-4 rounded-full border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 cursor-pointer hover:shadow-sm transition-all hover:border-slate-300 dark:hover:border-slate-600"
                        >
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-brand-500 to-brand-700 flex items-center justify-center text-white text-sm font-bold shrink-0 shadow-sm shadow-brand-500/30">
                                {{ $userInitial }}
                            </div>
                            <div class="hidden sm:block text-right leading-tight">
                                <div class="text-sm font-bold text-slate-900 dark:text-white">{{ $userName }}</div>
                                <div class="text-[10px] text-slate-500 dark:text-slate-400">{{ $userRole }}</div>
                            </div>
                            <svg class="w-4 h-4 text-slate-400 hidden sm:block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                        </button>

                        <div id="userMenuDropdown" class="hidden absolute left-0 top-full mt-2 p-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xl min-w-[240px] z-50">
                            <div class="px-3 py-2 border-b border-slate-100 dark:border-slate-800 mb-1">
                                <div class="font-bold text-sm text-slate-900 dark:text-white">{{ $userName }}</div>
                                <div class="text-xs text-slate-400 dark:text-slate-500">{{ $userEmail }}</div>
                            </div>
                            <a href="{{ route('system.profile.index') }}" class="flex items-center gap-2.5 px-3 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-colors">
                                <svg class="w-4 h-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                <span>الملف الشخصي</span>
                            </a>
                            <div class="border-t border-slate-100 dark:border-slate-800 my-1"></div>
                            <button 
                                type="button" 
                                onclick="document.getElementById('userMenuDropdown').classList.add('hidden'); document.getElementById('logoutModal').classList.remove('hidden'); document.getElementById('logoutModal').classList.add('flex');"
                                class="w-full flex items-center gap-2.5 px-3 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-xl transition-colors font-medium cursor-pointer"
                            >
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                <span>تسجيل الخروج</span>
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Main Page Content --}}
            <main class="flex-1 p-4 lg:p-8 overflow-x-hidden">
                @hasSection('breadcrumbs')
                    <nav class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-6 flex-wrap" aria-label="مسار التصفح">
                        @yield('breadcrumbs')
                    </nav>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         🚪 LOGOUT MODAL (Matching tem modern card dialog)
    ═══════════════════════════════════════════════════════════ --}}
    <div id="logoutModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-all duration-300" aria-modal="true" role="dialog">
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-2xl p-6 text-center max-w-sm w-full relative transform transition-all">
            <div class="w-14 h-14 rounded-2xl bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 flex items-center justify-center mx-auto mb-4 border border-red-100 dark:border-red-500/20 shadow-sm">
                <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
            </div>
            <h5 class="font-display font-bold text-xl text-slate-900 dark:text-white mb-2">
                تسجيل الخروج
            </h5>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">
                هل أنت متأكد من رغبتك في إنهاء الجلسة وتسجيل الخروج؟
            </p>
            <div class="flex gap-3 justify-center">
                <button type="button" onclick="document.getElementById('logoutModal').classList.add('hidden'); document.getElementById('logoutModal').classList.remove('flex');" class="flex-1 px-4 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-sm rounded-xl transition-colors cursor-pointer">
                    إلغاء
                </button>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="flex-1 m-0">
                    @csrf
                    <button type="submit" class="w-full px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold text-sm rounded-xl transition-colors shadow-lg shadow-red-500/30 cursor-pointer">
                        تأكيد الخروج
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         🔔 TOAST NOTIFICATION CONTAINER (Matching ToastContext.tsx)
    ═══════════════════════════════════════════════════════════ --}}
    <div id="toastContainer" class="fixed bottom-5 left-5 z-50 flex flex-col gap-3 pointer-events-none max-w-sm w-full" aria-live="polite"></div>

    {{-- ═══════════════════════════════════════════════════════════
         ⚙️ JAVASCRIPT & INTERACTIONS
    ═══════════════════════════════════════════════════════════ --}}
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/select2/select2.min.js') }}"></script>

    <script>
    (function() {
        'use strict';

        // ─── DOM Elements ───
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebarBackdrop');
        const toggleBtn = document.getElementById('sidebarToggleBtn');
        const closeBtn = document.getElementById('sidebarCloseBtn');
        const mainWrapper = document.getElementById('mainWrapper');
        const themeToggleBtn = document.getElementById('themeToggleBtn');

        // ─── Sidebar Responsiveness & Toggle ───
        let isCollapsedDesktop = false;

        function toggleSidebar() {
            if (window.innerWidth < 1024) {
                // Mobile behavior: Slide drawer
                const isOpen = !sidebar.classList.contains('translate-x-full');
                if (isOpen) {
                    closeMobileSidebar();
                } else {
                    openMobileSidebar();
                }
            } else {
                // Desktop behavior: Collapse/Expand
                isCollapsedDesktop = !isCollapsedDesktop;
                if (isCollapsedDesktop) {
                    sidebar.classList.add('lg:w-20');
                    sidebar.classList.remove('lg:w-72');
                    mainWrapper.classList.add('lg:pr-20');
                    mainWrapper.classList.remove('lg:pr-72');
                } else {
                    sidebar.classList.remove('lg:w-20');
                    sidebar.classList.add('lg:w-72');
                    mainWrapper.classList.remove('lg:pr-20');
                    mainWrapper.classList.add('lg:pr-72');
                }
            }
        }

        function openMobileSidebar() {
            sidebar.classList.remove('translate-x-full');
            backdrop.classList.remove('hidden');
            requestAnimationFrame(() => {
                backdrop.classList.remove('opacity-0');
            });
            document.body.style.overflow = 'hidden';
        }

        function closeMobileSidebar() {
            sidebar.classList.add('translate-x-full');
            backdrop.classList.add('opacity-0');
            setTimeout(() => {
                backdrop.classList.add('hidden');
                document.body.style.overflow = '';
            }, 300);
        }

        toggleBtn?.addEventListener('click', toggleSidebar);
        closeBtn?.addEventListener('click', closeMobileSidebar);
        backdrop?.addEventListener('click', closeMobileSidebar);

        document.addEventListener('click', (e) => {
            const userWrapper = document.getElementById('userMenuWrapper');
            const userMenuDropdown = document.getElementById('userMenuDropdown');
            if (userWrapper && userMenuDropdown && !userWrapper.contains(e.target)) {
                userMenuDropdown.classList.add('hidden');
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                document.getElementById('userMenuDropdown')?.classList.add('hidden');
                document.getElementById('logoutModal')?.classList.add('hidden');
                document.getElementById('logoutModal')?.classList.remove('flex');
                if (window.innerWidth < 1024 && !sidebar.classList.contains('translate-x-full')) {
                    closeMobileSidebar();
                }
            }
        });

        // ─── Modern Toast System (Matching tem ToastContext.tsx) ───
        window.showToast = function(type = 'info', title = '', message = '', duration = 4000) {
            const container = document.getElementById('toastContainer');
            if (!container) return;

            const toast = document.createElement('div');
            toast.className = 'pointer-events-auto flex items-start gap-3 p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xl transition-all duration-300 transform translate-y-4 opacity-0';

            const config = {
                success: {
                    border: 'border-emerald-500/30',
                    bg: 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400',
                    icon: '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>'
                },
                error: {
                    border: 'border-rose-500/30',
                    bg: 'bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400',
                    icon: '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>'
                },
                warning: {
                    border: 'border-amber-500/30',
                    bg: 'bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400',
                    icon: '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>'
                },
                info: {
                    border: 'border-brand-500/30',
                    bg: 'bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400',
                    icon: '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>'
                }
            }[type] || config.info;

            toast.innerHTML = `
                <div class="w-9 h-9 rounded-xl ${config.bg} flex items-center justify-center shrink-0">
                    ${config.icon}
                </div>
                <div class="flex-1 min-w-0">
                    ${title ? `<h4 class="text-sm font-bold text-slate-900 dark:text-white leading-snug">${title}</h4>` : ''}
                    ${message ? `<p class="text-xs text-slate-600 dark:text-slate-300 mt-0.5 leading-relaxed">${message}</p>` : ''}
                </div>
                <button class="text-slate-400 hover:text-slate-600 dark:hover:text-white p-1 rounded-lg transition-colors cursor-pointer" onclick="this.parentElement.remove()">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            `;

            container.appendChild(toast);

            requestAnimationFrame(() => {
                toast.classList.remove('translate-y-4', 'opacity-0');
            });

            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-2');
                setTimeout(() => toast.remove(), 300);
            }, duration);
        };

        // ─── Flash Messages from Laravel ───
        @if(session('success')) showToast('success', 'نجاح العملية', @json(session('success'))); @endif
        @if(session('error'))   showToast('error', 'خطأ في النظام', @json(session('error'))); @endif
        @if(session('warning')) showToast('warning', 'تنبيه', @json(session('warning'))); @endif
        @if(session('info'))    showToast('info', 'معلومات', @json(session('info'))); @endif

        // ─── Initialize Lucide Icons ───
        if (window.lucide) {
            lucide.createIcons();
        }

        // ─── Global Searchable Selects (Select2) ───
        window.initSearchableSelects = function() {
            if (typeof jQuery !== 'undefined' && jQuery.fn.select2) {
                $('select').not('.no-select2').not('.swal2-select').each(function() {
                    var $select = $(this);
                    if ($select.hasClass('select2-hidden-accessible')) {
                        return;
                    }
                    $select.select2({
                        tags: true,
                        dir: "rtl",
                        language: {
                            noResults: function() { return "لا توجد نتائج مطابقة (يمكنك الكتابة للإضافة)"; },
                            searching: function() { return "جاري البحث..."; }
                        },
                        width: '100%'
                    });

                    $select.on('select2:select', function(e) {
                        if ($select.attr('onchange') && $select.attr('onchange').includes('submit')) {
                            if (this.form) {
                                this.form.submit();
                            }
                        }
                    });
                });
            }
        };

        // ─── Global AJAX Setup & Dropdown Toggle ───
        if (typeof jQuery !== 'undefined') {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).ready(function() {
                window.initSearchableSelects();
            });
        }

        window.toggleRowDropdown = function(btn, event) {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }
            const container = btn.closest('.relative, .dropdown');
            const row = btn.closest('tr');
            const menu = container ? container.querySelector('.dropdown-menu, .row-options-menu') : null;
            if (!menu) return;

            const isCurrentlyHidden = menu.classList.contains('hidden') || getComputedStyle(menu).display === 'none';

            document.querySelectorAll('.dropdown-menu, .row-options-menu').forEach(m => {
                if (m !== menu && !m.id.includes('userMenu')) {
                    m.classList.add('hidden');
                    m.classList.remove('show');
                    m.style.display = '';
                    const r = m.closest('tr');
                    if (r) r.style.zIndex = '';
                    const c = m.closest('.relative, .dropdown');
                    if (c) c.style.zIndex = '';
                }
            });

            if (isCurrentlyHidden) {
                if (row) row.style.zIndex = '60';
                if (container) container.style.zIndex = '60';

                const rect = btn.getBoundingClientRect();
                const spaceBelow = window.innerHeight - rect.bottom;
                if (spaceBelow < 320 && rect.top > 320) {
                    menu.classList.remove('top-full', 'mt-2', 'origin-top-left');
                    menu.classList.add('bottom-full', 'mb-2', 'origin-bottom-left');
                } else {
                    menu.classList.remove('bottom-full', 'mb-2', 'origin-bottom-left');
                    menu.classList.add('top-full', 'mt-2', 'origin-top-left');
                }

                menu.classList.remove('hidden');
                menu.classList.add('show');
                menu.style.display = 'block';
            } else {
                menu.classList.add('hidden');
                menu.classList.remove('show');
                menu.style.display = 'none';
                if (row) row.style.zIndex = '';
                if (container) container.style.zIndex = '';
            }
        };

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown-menu') && !e.target.closest('.row-options-menu') && !e.target.closest('[onclick*="toggleRowDropdown"]') && !e.target.closest('[data-bs-toggle="dropdown"]')) {
                document.querySelectorAll('.dropdown-menu, .row-options-menu').forEach(m => {
                    if (!m.id.includes('userMenu')) {
                        m.classList.add('hidden');
                        m.classList.remove('show');
                        m.style.display = '';
                        const r = m.closest('tr');
                        if (r) r.style.zIndex = '';
                        const c = m.closest('.relative, .dropdown');
                        if (c) c.style.zIndex = '';
                    }
                });
            }
        });

    })();
    </script>

    @stack('scripts')
</body>
</html>
